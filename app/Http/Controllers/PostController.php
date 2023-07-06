<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Coments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api");
    }

    public function list(){
        return response()->json(Post::paginate(5));
    }

    public function create(Request $request){

        $validator = Validator::make($request->all(),[
            "title" => "required|unique:posts",
            "body" => "required",
            "thumbnail" => "image"
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),403);
        }

        $user = Auth::guard("api")->user();
        $post = new Post($request->all());

        $file_path = 'files/thumbnail';
        $extensionD = $request->all()['thumbnail']->getClientOriginalExtension();
        $nameFileD = uniqid() . ".{$extensionD}";
        
        $uploadD = $request->all()['thumbnail']->storeAs($file_path, $nameFileD);

        $post->thumbnail = $nameFileD;

        $post->creator()->associate($user);
        if($post->save()){
            return response()->json(["message" => "Post salvo", "data" => $post],200);
        }

        return response()->json(["message"=> "Erro ao salvar"],500);
    }

    public function delete($id)
    {
        $datas = Post::find($id);

        if($datas){
            $file_pathI = 'files/thumbnail';
            if(Storage::exists($file_pathI. '/' . $datas->thubnail)) {
                Storage::delete($file_pathI . '/' . $datas->thubnail);
            }
        }else{
            return response()->json(['message' => "Registro não encontrado"], 404);
        }

        if($datas->delete()){
            return response()->json(["message"=> "Registro deletado com sucesso"],200);
        }

        return response()->json(["message"=> "Falha na exclusão do registro {{id}}"], 400);
    }

    public function addComent(Request $request,Post $post){

        $validator = Validator::make($request->all(),[
            "message" => "required",
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),403);
        }

        $user = Auth::guard("api")->user();

        $coment = new Coments($request->all());
        $coment->creator()->associate($user);
        $coment->post()->associate($post);

        if($coment->save()){
            return response()->json(["message" => "comentario salvo", "data" => $coment],200);
        }

        return response()->json(["message"=> "Erro ao salvar"],500);
    }

    public function deleteComent($postId, $comentId)
    {
        $post = Post::findOrFail($postId);
        $coment = Coments::findOrFail($comentId);

        // Verificar se o comentário pertence ao post
        if ($coment->post_id == $post->id) {
            $coment->delete();

            return response()->json(['message' => 'Comentário excluído com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Comentário não encontrado nesse post'], 404);
        }
    }

}
