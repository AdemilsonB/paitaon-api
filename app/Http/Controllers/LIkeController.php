<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like($id)
    {
        $post = Post::findOrFail($id);

        $user = Auth::user();

        $existingLike = Like::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            return response()->json(['message' => 'Like removido com sucesso'], 200);
        }

        $like = new Like();
        $like->post_id = $post->id;
        $like->user_id = $user->id;
        $like->type = 'like';
        $like->save();

        return response()->json(['message' => 'Like adicionado com sucesso'], 200);
    }

    public function dislike($id)
    {
        $post = Post::findOrFail($id);

        $user = Auth::user();

        $existingDislike = Like::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        if ($existingDislike) {
            $existingDislike->delete();
            return response()->json(['message' => 'Dislike removido com sucesso'], 200);
        }

        $like = new Like();
        $like->post_id = $post->id;
        $like->user_id = $user->id;
        $like->type = 'like';
        $like->save();

        return response()->json(['message' => 'Dislike adicionado com sucesso'], 200);
    }
}