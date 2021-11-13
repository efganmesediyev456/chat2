<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserDashboardController extends Controller
{
    public function index(){
        $users=User::whereNotIn('id',auth()->user()->blocks()->pluck('id')->toArray())->get();
        $comments=Comment::with('user','likes','dislikes')->get();
        return view("user.dashboard",compact('users','comments'));
    }
    public function commentShare(Request $request){
        $rules = [
            'comment' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(
               $validator->errors()
            );
        }

        $comment=$request->input('comment');
        $comment=Comment::create([
           'comment'=>$comment,
            'user_id'=>auth()->user()->id
        ]);

        $view = view('user.partials.userComment', compact('comment'))->render();
        return response()->json([
            'success'=>'ok',
            'view'=>$view,
        ]);

    }

    public function commentDelete(Request $request){

        $rules = [
            'comment_id' => 'required|numeric',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors()
            );
        }


        $comment_id=$request->input('comment_id');
        $comment=Comment::find($comment_id);
        if(!$comment){
            $validator->getMessageBag()->add('comment_id', 'No found data');
            return response()->json(

                    $validator->errors()

            );
        }

        if($comment->user->id!=auth()->user()->id){
            $validator->getMessageBag()->add('comment_id', 'You dont delete this comment');
            return response()->json(
                $validator->errors()
            );
        }

        foreach ($comment->replies as $reply){
            $reply->delete();
        }
        Comment::destroy($comment_id);
        return response()->json([
            'success'=>'ok',
            'status'=>200,
            'comment_id'=>$comment_id
        ]);
    }


    public function commentUpdate(Request $request){
        $comment=Comment::find(trim($request->comment_id));
        $comment->comment=$request->comment;
        $comment->save();
        $view = view('user.partials.userComment', compact('comment'))->render();
        return response()->json([
            'success'=>'ok',
            'status'=>200,
            'view'=>$view,
        ]);
    }

    public function replyShare(Request $request){

        $rules = [
            'comment_id' => 'required|numeric',
            'reply' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors()
            );
        }

        $blocks=$this->authBlockList();
        if(in_array(Comment::find($request->comment_id)->user->id,$blocks)){

            $validator->getMessageBag()->add('comment_id', 'You are not reply share this comment');
            return response()->json(
                $validator->errors()
            );

        }

       $reply=Reply::create([
           'reply'=>$request->reply,
           'user_id'=>auth()->user()->id,
           'comment_id'=>$request->comment_id,
       ]);


        $view = view('user.partials.userReply', compact('reply'))->render();
        return response()->json([
            'success'=>'ok',
            'view'=>$view,
        ]);

    }



    public function replyDelete(Request $request){


        $rules = [
            'reply_id' => 'required|numeric',
        ];
        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(
                $validator->errors()
            );
        }


        $reply_id=$request->input('reply_id');
        $reply=Reply::find($reply_id);
        if(!$reply){
            $validator->getMessageBag()->add('reply_id', 'No found data');
            return response()->json(
                    $validator->errors()
            );
        }
        if($reply->user->id!=auth()->user()->id){
            $validator->getMessageBag()->add('reply_id', 'You dont delete this reply');
            return response()->json(
                $validator->errors()
            );
        }
        Reply::destroy($reply_id);
        return response()->json([
            'success'=>'ok',
            'status'=>200,
            'comment_id'=>$reply_id
        ]);
    }


    public function replyUpdate(Request $request){
        $reply=Reply::find(trim($request->reply_id));
        $reply->reply=$request->reply;
        $reply->save();
        $view = view('user.partials.userReply', compact('reply'))->render();
        return response()->json([
            'success'=>'ok',
            'status'=>200,
            'view'=>$view,
        ]);
    }


    public function authBlockList(){
        return auth()->user()->blocks()->pluck('id')->toArray();
    }



    public function commentLike(Request $request){

        $blocks=$this->authBlockList();

        $rules = [
            'comment_id' => 'required|numeric',
        ];
        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(
                $validator->errors()
            );
        }

        $comment=Comment::find($request->comment_id);

        if(!$comment){
            $validator->getMessageBag()->add('comment_id', 'Comment not Found');
            return response()->json(

                    $validator->errors()

            );
        }

        foreach ($blocks as $block){
            if($block==$comment->user->id){
                $validator->getMessageBag()->add('comment_id', 'this user is blocked');
                return response()->json(
                        $validator->errors()

                );
            }
        }






       $likes=auth()->user()->likes;
       $check=false;
       foreach ($likes as $like){
           if($like->comment_id==$request->comment_id){
               $check=true;
               if($like->status==1){
                   $like->delete();
               }elseif($like->status==0){
                   $like->status=1;
                   $like->save();
               }
           }
       }
       if($check==false){
           auth()->user()->likes()->create([
               'user_id'=>auth()->user()->id,
               'comment_id'=>$request->comment_id,
               'status'=>1
           ]);
       }

        $comment=Comment::find($request->comment_id);


        return response()->json([
            'success'=>'ok',
            'like_count'=>$comment->likes()->count(),
            'dislike_count'=>$comment->dislikes()->count(),
        ]);


    }


    public function replyLike(Request $request){
       $likes=auth()->user()->replyLikes;



       $check=false;
       foreach ($likes as $like){

           if($like->reply_id==$request->reply_id){

               $check=true;
               if($like->status==1){

                   $like->delete();
               }elseif($like->status==0){
                   $like->status=1;
                   $like->save();
               }
           }
       }
       if($check==false){
           auth()->user()->replyLikes()->create([
               'user_id'=>auth()->user()->id,
               'reply_id'=>$request->reply_id,
               'status'=>1
           ]);
       }

        $reply=Reply::find($request->reply_id);


        return response()->json([
            'success'=>'ok',
            'like_count'=>$reply->likes()->count(),
            'dislike_count'=>$reply->dislikes()->count(),
        ]);


    }


    public function commentDislike(Request $request){





        $rules = [
            'comment_id' => 'required|numeric',
        ];
        $validator = Validator::make($request->all(), $rules);



        if ($validator->fails()) {
            return response()->json(
                $validator->errors()
            );
        }

        $blocks=$this->authBlockList();

        $comment=Comment::find($request->comment_id);

        if(!$comment){
            $validator->getMessageBag()->add('comment_id', 'Comment not Found');
            return response()->json(

                $validator->errors()

            );
        }

        foreach ($blocks as $block){
            if($block==$comment->user->id){
                $validator->getMessageBag()->add('comment_id', 'this user is blocked');
                return response()->json(
                    $validator->errors()
                );
            }
        }

        $likes=auth()->user()->likes;
        $check=false;
        foreach ($likes as $like){
            if($like->comment_id==$request->comment_id){
                $check=true;
                if($like->status==0){
                    $like->delete();
                }elseif($like->status==1){
                    $like->status=0;
                    $like->save();
                }
            }
        }
        if($check==false){
            auth()->user()->likes()->create([
                'user_id'=>auth()->user()->id,
                'comment_id'=>$request->comment_id,
                'status'=>0
            ]);
        }

        $comment=Comment::find($request->comment_id);


        return response()->json([
            'success'=>'ok',
            'like_count'=>$comment->likes()->count(),
            'dislike_count'=>$comment->dislikes()->count(),
        ]);

    }


    public function replyDislike(Request $request){
        $likes=auth()->user()->replyLikes;
        $check=false;
        foreach ($likes as $like){
            if($like->reply_id==$request->reply_id){
                $check=true;
                if($like->status==0){
                    $like->delete();
                }elseif($like->status==1){
                    $like->status=0;
                    $like->save();
                }
            }
        }
        if($check==false){
            auth()->user()->replyLikes()->create([
                'user_id'=>auth()->user()->id,
                'reply_id'=>$request->reply_id,
                'status'=>0
            ]);
        }

        $reply=Reply::find($request->reply_id);


        return response()->json([
            'success'=>'ok',
            'like_count'=>$reply->likes()->count(),
            'dislike_count'=>$reply->dislikes()->count(),
        ]);


    }

}
