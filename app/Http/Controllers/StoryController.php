<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:image,video,text',
            'caption' => 'nullable|string',
            // 'file' => 'required_if:type,image,video|file',
                    'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg,mp4|max:2048',

        ]);

        $userId = auth()->id();
        $storyId = Str::uuid();

        $storagePath = null;

        // 📌 Si c'est une image ou vidéo → upload fichier
        if (in_array($request->type, ['image', 'video'])) {
            $storagePath = $request->file('file')->store('stories', 'public');
        }

        // 📌 Création de la story
        $story = Story::create([
            'storyId' => $storyId,
            'userId' => $userId,
            'storagePath' => $storagePath,      // Peut être null pour type texte
            'type' => $request->type,
            'caption' => $request->caption,
            'timestamp' => now(),
            'expiresAt' => now()->addHours(24),
            'views' => [],
        ]);

        return response()->json($story, 201);
    }

    public function index(Request $request)
{
    $now = now();

    $stories = Story::where(function($q) use ($now) {
        $q->whereNull('expiresAt')->orWhere('expiresAt', '>', $now);
    })->orderBy('timestamp', 'desc')->get();

    // Récupérer tous les userIds qui ont vu au moins 1 story
    $allViewerIds = [];
    foreach ($stories as $s) {
        $views = (array) $s->views;
        $ids = array_keys($views);
        $allViewerIds = array_merge($allViewerIds, $ids);
    }
    $allViewerIds = array_values(array_unique($allViewerIds));

    // Récupérer les utilisateurs (select fields utiles)
    $viewers = \App\Models\User::whereIn('user_id', $allViewerIds)
                ->get(['user_id','name','first_name','email','photo_url'])
                ->keyBy('user_id'); // clé par user_id

    // Construire la réponse enrichie
    $result = $stories->map(function($s) use ($viewers) {
        $viewsMap = (array) $s->views;
        $viewerIds = array_keys($viewsMap);

        $viewerList = [];
        foreach ($viewerIds as $uid) {
            if (isset($viewers[$uid])) {
                $u = $viewers[$uid];
                $viewerList[] = [
                    'user_id' => $u->user_id,
                    'name' => $u->name,
                    'first_name' => $u->first_name,
                    'photo_url' => $u->photo_url,
                ];
            } else {
                // fallback : renvoyer seulement l'uid si pas trouvé
                $viewerList[] = ['user_id' => $uid];
            }
        }

        return [
            'storyId' => $s->storyId,
            'userId' => $s->userId,
            'storagePath' => $s->storagePath,
            'type' => $s->type,
            'caption' => $s->caption,
            'timestamp' => $s->timestamp,
            'expiresAt' => $s->expiresAt,
            'views_count' => count($viewerList),
            'viewers' => $viewerList,
        ];
    });

    return response()->json($result);
}

}
