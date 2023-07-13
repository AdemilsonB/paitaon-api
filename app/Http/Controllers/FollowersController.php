<?php

namespace App\Http\Controllers;

use App\Models\Followers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function follow($id)
    {
        $follower = User::findOrFail($id);

        $user = Auth::user();

        $existingFollow = Followers::where('user_id', $user->id)
            ->where('follower_id', $follower->id)
            ->first();

        if ($existingFollow) {
            $existingFollow->delete();
            return response()->json(['message' => 'Você parou de seguir'], 200);
        }

        $follow = new Followers();
        $follow->user_id = $user->id;
        $follow->follower_id = $follower->id;
        $follow->save();

        return response()->json(['message' => 'Você começou a seguir', 'user' => $user->name]);
    }

    public function unfollow($id)
    {
        $follower = User::findOrFail($id);

        $user = Auth::user();

        $existingFollow = Followers::where('user_id', $user->id)
            ->where('follower_id', $follower->id)
            ->first();

        if ($existingFollow) {
            $existingFollow->delete();
            return response()->json(['message' => 'Você parou de seguir'], 200);
        }

        return response()->json(['message' => 'Você não segue este usuário'], 400);
    }

    public function countFollowers()
    {
        $user = Auth::user();

        $followers = Followers::where('follower_id', $user->id)
            ->count();

        return response()->json(['message' => 'Quantidade de seguidores', 'quantidade' => $followers], 200);
    }

    public function listFollowers(){
        $user = Auth::user();

        $followers = Followers::where('follower_id',$user->id)->get();

        return response()->json(['followers' => $followers], 200);
    }

    public function listFollowing(){
        $user = Auth::user();

        $following = Followers::where('user_id',$user->id)->get();

        return response()->json(['followings' => $following], 200);
    }
}
