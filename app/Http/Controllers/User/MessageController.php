<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Friend;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function index(User $user){



        $friendlists=$this->authUserList();


        if(!in_array($user->id,$friendlists)){
            abort(404);
        }

        $users=User::whereNotIn('id',auth()->user()->blocks()->pluck('id')->toArray())->get();


        $messages=Message::where('from_id',auth()->user()->id)->where('to_id',$user->id)->orWhere('from_id',$user->id)->where('to_id',auth()->user()->id)->get();

        return view('user.message',compact('users','messages','user'));
    }


    public function authUserList(){
        $f1=auth()->user()->friend1()->wherePivot('status',1)->get()->pluck('id');
        $f2=auth()->user()->friend2()->wherePivot('status',1)->get()->pluck('id');
        $friendlists=array_merge($f1->toArray(),$f2->toArray());
        return $friendlists;
    }


    public function send(Request $request){

        $rules = [
            'message' => 'required',
            'user_id'=>'required|numeric'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(
                [ $validator->errors()]
            );
        }

        $user=User::find($request->user_id);
        if(!$user){
            $validator->getMessageBag()->add('user_id', 'No found User');
            return response()->json(
                [
                    $validator->errors()
                ]
            );
        }

        if(in_array($user->id,auth()->user()->blocks()->pluck('id')->toArray())){
            $validator->getMessageBag()->add('user_id', 'this user is blocked');
            return response()->json(
                [
                    $validator->errors()
                ]
            );
        }

        $f1=auth()->user()->friend1()->wherePivot('status',1)->get()->pluck('id');
        $f2=auth()->user()->friend2()->wherePivot('status',1)->get()->pluck('id');



        $friendlists=$this->authUserList();
        if(!in_array($user->id,$friendlists)){
            $validator->getMessageBag()->add('user_id', 'this user is not your friend');
            return response()->json(
                [
                    $validator->errors()
                ]
            );
        }


        $data=auth()->user()->message1()->attach([
            $user->id=>[
                    'message'=>$request->message
            ]
        ]);

        $data=Message::find(\DB::getPdo()->lastInsertId());

        $view = view('user.partials.userMessage', compact('data'))->render();
        return response()->json([
            'success'=>'ok',
            'view'=>$view,
        ]);




    }


}
