<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => 'searchPost']);
    }

    public function list() {
        return response()->json(Post::paginate(10));
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(),[
            'title' => 'required|unique:posts',
            'body' => 'required',
            'thumbnail' => 'image'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(),403);
        }

        $user = Auth::guard('api')->user();
        $post = new Post($request->all());

        $file_path = 'files/thumbnail';
        $extensionD = $request->all()['thumbnail']->getClientOriginalExtension();
        $nameFileD = uniqid() . ".{$extensionD}";

        $post->thumbnail = $nameFileD;

        $post->creator()->associate($user);
        if($post->save()){
            $uploadD = $request->all()['thumbnail']->storeAs($file_path, $nameFileD);

            return response()->json(['message' => 'Post salvo', 'data' => $post],200);
        }

        return response()->json(['message'=> 'Erro ao salvar'],500);
    }

    public function update(Request $request, $id) {
        $data = $request->all();
        $post = Post::find($id);

        if($data){
            $post->deleteThumbnail();

            $file_path = 'files/thumbnail';
            if ($request->hasFile('thumbnail')) {
                $extension = $request->all()('thumbnail')->getClientOriginalExtension();
                $nameFile = uniqid() . '.{$extension}';
                $request->all()('thumbnail')->storeAs($file_path, $nameFile);
                $data['thumbnail'] = $nameFile;
            }

            $post->update($data);

            return response()->json(['message' => 'Registro editado com sucesso'], 200);
        }

        return response()->json(['message' => 'Registro não encontrado'], 404);

    }

    public function delete($id) {
        $datas = Post::find($id);

        if($datas){
            if($datas->deleteThumbnail());
        }else{
            return response()->json(['message' => 'Registro não encontrado'], 404);
        }

        if($datas->delete()){
            return response()->json(['message'=> 'Registro deletado com sucesso'],200);
        }

        return response()->json(['message'=> 'Falha na exclusão do registro {{id}}'], 400);
    }

    public function searchPost(Request $request) {
        $post = $request->get('posts');
        if ($post) {
            $title = Post::where('title', 'like', '%' . $post . '%')->get();
            return response()->json(['title' => $title]);
        } else {
            return response()->json(['message' => 'Nenhum registro encontrado'], 400);
        }
    }

    public function addComment(Request $request,Post $post) {
        $validator = Validator::make($request->all(),[
            'message' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),403);
        }

        $user = Auth::guard('api')->user();

        $comment = new Comments($request->all());
        $comment->creator()->associate($user);
        $comment->post()->associate($post);

        if($comment->save()){
            return response()->json(['message' => 'Comentário salvo', 'data' => $comment],200);
        }

        return response()->json(['message'=> 'Erro ao salvar'],500);
    }

    public function deleteComment($postId, $commentId) {
        $post = Post::findOrFail($postId);
        $comment = Comments::findOrFail($commentId);

        // Verificar se o comentário pertence ao post
        if ($comment->post_id == $post->id) {
            $comment->delete();

            return response()->json(['message' => 'Comentário excluído com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Comentário não encontrado nesse post'], 404);
        }
    }
}