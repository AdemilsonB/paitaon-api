<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class AuthController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()){
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 403);
        }

        $credentials = $request->only('email', 'password');

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        return response()->json(['status' => 'sucess', 'message' => $token], 200);
    }

    public function register(Request $request){

        $validator = Validator::make($request->all(),[
            "name" => "required",
            "email"=> "required|unique:users|email",
            "password" => "required|confirmed",
            "bio" => "required", 
            "image_perfil" => "image"
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),403);
        }

        $user = new User($request->all());

        $file_path = 'files/imagePerfil';
        $extensionD = $request->all()['image_perfil']->getClientOriginalExtension();
        $nameFileD = uniqid() . ".{$extensionD}";
        
        $uploadD = $request->all()['image_perfil']->storeAs($file_path, $nameFileD);

        $user->image_perfil = $nameFileD;

        if($user->save()){
            return response()->json(["message" => "Usuario salvo", "data" => $user->name],200);
        }

        return response()->json(["message"=> "Erro ao salvar"],500);
    }

    public function delete($id)
    {
        $datas = User::find($id);

        if($datas){
            $file_pathI = 'files/image_perfil';
            if(Storage::exists($file_pathI. '/' . $datas->image_perfil)) {
                Storage::delete($file_pathI . '/' . $datas->image_perfil);
            }
        }else{
            return response()->json(['message' => "Usuário não encontrado"], 404);
        }

        if($datas->delete()){
            return response()->json(["message"=> "Usuário deletado com sucesso"],200);
        }

        return response()->json(["message"=> "Falha na exclusão do usuário"], 400);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['status' => 'success', 'message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer'
            ]
        ]);
    }

}
