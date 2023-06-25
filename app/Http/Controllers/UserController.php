<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth:api");
    }

    public function me() {
        return response()->json(Auth::guard('api')->user()); //verifica autenticação e obtém uma resposta Json com dados do user;
    }

}
