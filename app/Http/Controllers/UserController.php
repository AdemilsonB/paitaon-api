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

    public function register(Request $request) {

        $validator = Validator::make($request->all(),[
            "name" => "required",
            "email"=> "required|unique:users|email",
            "password" => "required|confirmed",
            "bio" => "required",
            "image_perfil" => "image"
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(),403);
        }

        $user = new User($request->all());

        $file_path = 'files/imagePerfil';
        $extensionD = $request->all()['image_perfil']->getClientOriginalExtension();
        $nameFileD = uniqid() . ".{$extensionD}";

        $user->image_perfil = $nameFileD;

        if($user->save()){
            $uploadD = $request->all()['image_perfil']->storeAs($file_path, $nameFileD);

            return response()->json(["message" => "Usuario salvo", "data" => $user->name],200);
        }

        return response()->json(["message"=> "Erro ao salvar"],500);
    }

    public function update(Request $request, $id) {
        $data = $request->all();
        $user = User::find($id);

        if($data){
            $user->deleteImage();

            $file_path = 'files/image_perfil';
            if ($request->hasFile('image_perfil')) {
                $extension = $request->all()('image_perfil')->getClientOriginalExtension();
                $nameFile = uniqid() . ".{$extension}";
                $request->all()('image_perfil')->storeAs($file_path, $nameFile);
                $data['image_perfil'] = $nameFile;
            }

            $user->update($data);

            return response()->json(['message' => "Usuário editado com sucesso"], 200);
        }

        return response()->json(["message" => "Usuário não encontrado"], 404);
    }

    public function delete($id) {
        $datas = User::find($id);

        if($datas){
            if($datas->deleteImage());
        }else{
            return response()->json(['message' => "Usuário não encontrado"], 404);
        }

        if($datas->delete()){
            return response()->json(["message"=> "Usuário deletado com sucesso"],200);
        }

        return response()->json(["message"=> "Falha na exclusão do usuário"], 400);
    }

    public function me() {
        return response()->json(Auth::guard('api')->user());
    }

    public function list() {
        return response()->json(User::get());
    }

    public function searchUsers(Request $request) {
        $name = $request->get("user");

        if ($name) {
            $users = User::where('name', 'like', '%' . $name . '%')->get();
            return response()->json(['users' => $users]);
        } else {
            return response()->json(['message' => 'Nome de usuário não fornecido.'], 400);
        }
    }
}