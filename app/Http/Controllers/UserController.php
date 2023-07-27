<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function __construct() {
       $this->middleware("auth:api");
    }

    public function me() {
        return response()->json(Auth::guard('api')->user()); //verifica autenticação e obtém uma resposta Json com dados do user;
    }

    public function list() {
        return response()->json(User::get());
    }

    public function searchUsers(Request $request){
        $name = $request->get("user");
        if ($name) {
            $users = User::where('name', 'like', '%' . $name . '%')->get();
            return response()->json(['users' => $users]);
        } else {
            return response()->json(['message' => 'Nome de usuário não fornecido.'], 400);
        }
    }

}
