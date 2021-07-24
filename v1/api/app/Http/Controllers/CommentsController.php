<?php

namespace App\Http\Controllers;
$base = $_SERVER["DOCUMENT_ROOT"];
require_once($base . '/api/Simpla.php');
require_once($base . '/api/Comments.php');

use App\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Welcome Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders the "marketing page" for the application and
    | is configured to only allow guests. Like most of the other sample
    | controllers, you are free to modify or remove it as you desire.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('guest');
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function getOne($id)
    {
        $comment = new \Comments();
        $getall = $comment->get_comment($id);
        return response()->json($getall);


    }

    public function getAll()
    {
        parse_str($_SERVER['QUERY_STRING'], $get_array);
      //  dd($get_array);
        $comment = new \Comments();
        $getall = $comment->get_comments($get_array);
        return response()->json($getall);
    }

    public function addComment(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'text' => 'required',
            'object_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 1, 'message' => $validator->messages()->first()], 400);
        } else{

            $comment = new Comment();
            $comment->object_id = $request->object_id;
            $comment->name = $request->name;
            $comment->text = $request->text;
            $comment->date = date('Y-m-d H:i:s');
            $comment->ip = $_SERVER['REMOTE_ADDR'];
            $comment->save();
            return response()->json('Success',200);
        }

    }

    public function deleteComment($id)
    {
        $user = Comment::find($id);

        if ($user->delete()){
            return response()->json(['message' => 'Succes'], 200);
        }else {
            return response()->json(['message' => 'Something wrong'], 400);
        }

    }

}
