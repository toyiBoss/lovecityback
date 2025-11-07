<?php

namespace App\Http\Controllers;

use App\Models\DMessage;
use App\Models\Discussion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\GiftType;
use App\Models\User;

class DMessageController extends Controller
{
    /**
     * Lister messages d'une discussion (paginated optionnel)
     */
    public function index(Request $request, $discussionId)
    {
        $discussion = Discussion::findOrFail($discussionId);

        $userId = auth()->user()->user_id ?? auth()->id();
        if ($discussion->user1Id !== $userId && $discussion->user2Id !== $userId) {
            return response()->json(['message'=>'Forbidden'], 403);
        }

        $perPage = (int) $request->query('per_page', 50);
        $messages = DMessage::where('discussionId', $discussionId)
            ->orderBy('timestamp','asc')
            ->paginate($perPage);

        return response()->json($messages);
    }

    /**
     * Envoyer un message : texte, image (mediaUrl) ou gift.
     * Met à jour discussion.lastMessage, lastMessageTimestamp et unread counts.
     */
public function store(Request $request, $discussionId)
{
    $request->validate([
        'text' => 'nullable|string',
        'mediaUrl' => 'nullable|url',
        'type' => 'required|in:TEXT,IMAGE,GIFT',
        'giftId' => 'required_if:type,GIFT|string'
    ]);

    $discussion = Discussion::findOrFail($discussionId);
    $senderId = auth()->user()->user_id ?? auth()->id();

    // Vérifier que l'utilisateur est dans la conversation
    if ($discussion->user1Id !== $senderId && $discussion->user2Id !== $senderId) {
        return response()->json(['message'=>'Forbidden'], 403);
    }

    // Destinataire
    $recipientId = ($discussion->user1Id === $senderId) ? $discussion->user2Id : $discussion->user1Id;

    DB::beginTransaction();
    try {

        // 🟡 Si type = GIFT → Débiter coins et enregistrer le cadeau
        if ($request->type === 'GIFT') {
            $gift = GiftType::where('giftId',$request->giftId)
                ->where('isActive',true)
                ->firstOrFail();

            $sender = User::where('user_id', $senderId)->first();
            if($sender->coins < $gift->costInCoins){
                return response()->json(['error'=>'Solde insuffisant'],422);
            }

            // Débit
            $sender->coins -= $gift->costInCoins;
            $sender->save();

            // On transforme le cadeau en message
            $request->merge([
                'text' => $gift->name,
                'mediaUrl' => $gift->imageUrl
            ]);
        }

        // Création du message
        $message = DMessage::create([
            'messageId' => (string) Str::uuid(),
            'discussionId' => $discussion->discussionId,
            'senderId' => $senderId,
            'text' => $request->text,
            'mediaUrl' => $request->mediaUrl,
            'type' => $request->type,
            'timestamp' => now(),
            'isSeen' => false,
        ]);

        // Mise à jour discussion
        $discussion->lastMessage = $this->summarizeLastMessage($message);
        $discussion->lastMessageTimestamp = now();

        if ($recipientId === $discussion->user1Id) {
            $discussion->unreadCount_U1++;
        } else {
            $discussion->unreadCount_U2++;
        }

        $discussion->save();

        DB::commit();
        return response()->json($message, 201);

    } catch (\Throwable $e) {
        DB::rollBack();
        throw $e;
    }
}


    /**
     * Mark a message as seen by the authenticated user.
     * Also decrement unreadCount on discussion for that user if needed.
     */
    public function markSeen($messageId)
    {
        $message = DMessage::findOrFail($messageId);
        $userId = auth()->user()->user_id ?? auth()->id();

        $discussion = Discussion::where('discussionId', $message->discussionId)->firstOrFail();
        if ($discussion->user1Id !== $userId && $discussion->user2Id !== $userId) {
            return response()->json(['message'=>'Forbidden'], 403);
        }

        if ($message->isSeen) {
            return response()->json(['message'=>'Already seen']);
        }

        DB::beginTransaction();
        try {
            $message->isSeen = true;
            $message->save();

            // si le viewer is recipient (not sender) decrement unread
            if ($message->senderId !== $userId) {
                if ($userId === $discussion->user1Id && $discussion->unreadCount_U1 > 0) {
                    $discussion->unreadCount_U1 = max(0, $discussion->unreadCount_U1 - 1);
                } elseif ($userId === $discussion->user2Id && $discussion->unreadCount_U2 > 0) {
                    $discussion->unreadCount_U2 = max(0, $discussion->unreadCount_U2 - 1);
                }
                $discussion->save();
            }

            DB::commit();
            return response()->json(['message'=>'Marked seen']);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function summarizeLastMessage(DMessage $m)
    {
        if ($m->type === 'TEXT') {
            return mb_strimwidth($m->text ?? '', 0, 200, '...');
        }
        if ($m->type === 'IMAGE') {
            return '[Image]';
        }
        if ($m->type === 'GIFT') {
            return '[Gift]';
        }
        return '[Message]';
    }
}
