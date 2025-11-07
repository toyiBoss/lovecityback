<?php

namespace App\Http\Controllers;

use App\Models\UserProfileGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UserProfileGalleryController extends Controller
{
    // Lister la galerie de l'utilisateur connecté (ordonnée)
    public function index()
    {
        $userId = auth()->user()->user_id ?? auth()->id();

        $items = UserProfileGallery::where('user_id', $userId)
            ->orderBy('order', 'asc')
            ->get()
            ->map(function($item) {
                // rendre l'URL publique si c'est un fichier stocké localement
                $url = $item->storagePath;
                if ($url && ! str_starts_with($url, 'http')) {
                    // si tu stockes le chemin relatif (ex: stories/xyz.jpg)
                    $url = Storage::disk('public')->url($item->storagePath);
                }
                $item->image_url = $url;
                return $item;
            });

        return response()->json($items);
    }

    // Ajouter une image (form-data file) ou via storagePath (url)
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:51200',
            'storagePath' => 'nullable|url',
            'order' => 'nullable|integer|min:1',
        ]);

        $userId = auth()->user()->user_id ?? auth()->id();

        // Priorité au fichier uploadé
        $storagePath = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('gallery', 'public'); // storage/app/public/gallery/...
            $storagePath = $path; // on stocke le chemin relatif pour pouvoir utiliser Storage::disk('public')->url(...)
        } elseif ($request->filled('storagePath')) {
            // si l'utilisateur fournit une URL externe
            $storagePath = $request->input('storagePath');
        } else {
            return response()->json(['message' => 'file or storagePath is required'], 422);
        }

        $media = UserProfileGallery::create([
            'mediaId' => (string) Str::uuid(),
            'user_id' => $userId,
            'storagePath' => $storagePath,
            'order' => $request->input('order', 1),
            'isVerified' => false,
            'timestamp' => now(),
        ]);

        // Retourne l'url publique si applicable
        $media->image_url = (! str_starts_with($media->storagePath, 'http')) 
            ? Storage::disk('public')->url($media->storagePath) 
            : $media->storagePath;

        return response()->json($media, 201);
    }

    // Mettre à jour l'ordre ou la vérification
    public function update(Request $request, $mediaId)
    {
        $request->validate([
            'order' => 'nullable|integer|min:1',
            'isVerified' => 'nullable|boolean',
        ]);

        $userId = auth()->user()->user_id ?? auth()->id();

        $media = UserProfileGallery::where('mediaId', $mediaId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $media->fill($request->only(['order', 'isVerified']));
        $media->save();

        $media->image_url = (! str_starts_with($media->storagePath, 'http')) 
            ? Storage::disk('public')->url($media->storagePath) 
            : $media->storagePath;

        return response()->json($media);
    }

    // Supprimer une image (et supprimer le fichier si stockage local)
    public function destroy($mediaId)
    {
        $userId = auth()->user()->user_id ?? auth()->id();

        $media = UserProfileGallery::where('mediaId', $mediaId)
            ->where('user_id', $userId)
            ->firstOrFail();

        // supprimer fichier local si c'est un chemin relatif
        if ($media->storagePath && ! str_starts_with($media->storagePath, 'http')) {
            Storage::disk('public')->delete($media->storagePath);
        }

        $media->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
