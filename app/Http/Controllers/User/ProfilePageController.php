<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ProfilePageController extends Controller
{
    public function index(User $user){
        $users=User::whereNotIn('id',auth()->user()->blocks()->pluck('id')->toArray())->get();

        $status=0;
        foreach(auth()->user()->friend1 as $friend) {
            if ($friend->id == $user->id and $friend->pivot->status == 1) {
                $status = 3;  //dostdular
            }
            if($friend->id==$user->id and $friend->pivot->status==0){
                $status=1; //dostluq atilib menden
            }
        }

        foreach(auth()->user()->friend2 as $friend){
            if($friend->id== $user->id  and $friend->pivot->status==1){
               $status=3;
            }
            if($friend->id== $user->id  and $friend->pivot->status==0){
                $status=2; //dostlugu o mene atib
            }
        }


        foreach(auth()->user()->block1 as $friend) {
            if ($friend->id == $user->id) {
                $status = 4;  //dostdular

            }
        }

        foreach(auth()->user()->block2 as $friend){
            if($friend->id== $user->id){
                abort(404);
            }
        }


        return view('user.profile',compact('user','users','status'));
    }

    public function addFriend(Request $request){



        $check=false;
        foreach(auth()->user()->friend1 as $friend){
            if($friend->id==$request->user_id and $friend->pivot->status==1){
                $check=true;
                return response()->json([
                    'error'=>'siz dostsuz siz atmisiz'
                ]);
            }
            if($friend->id==$request->user_id and $friend->pivot->status==0){
                $check=true;
                return response()->json([
                    'error'=>'siz dostluq atmisiz artiq'
                ]);
            }
        }

        foreach(auth()->user()->friend2 as $friend){
            if($friend->id==$request->user_id and $friend->pivot->status==1){
                $check=true;
                return response()->json([
                    'error'=>'siz dostsuz dostlugu o atib '
                ]);
            }
            if($friend->id==$request->user_id and $friend->pivot->status==0){
                $check=true;
                return response()->json([
                    'error'=>'size o dostluq atib'
                ]);
            }
        }


        if($check==false){

            auth()->user()->friend1()->attach([
                $request->user_id=>[
                    "status"=>0
                ]
            ]);

            $user=User::find($request->user_id);

//            1 dostluq atilib cavab gozlenilir

            $status=1;

            $view = view('user.partials.profileActions', compact('user','status'))->render();
            return response()->json([
                'success'=>'ok',
                'view'=>$view,
            ]);

        }



    }


    public function backRequest(Request $request){

        $check=false;
        foreach(auth()->user()->friend1 as $friend){
            if($friend->id==$request->user_id and $friend->pivot->status==1){
                $check=true;
                return response()->json([
                    'error'=>'siz dostsuz dostluq teklifi geri ala bilmezsiz'
                ]);
            }
            if($friend->id==$request->user_id and $friend->pivot->status==0){

                $friend->pivot->delete();
                $status=0;

                $user=User::find($request->user_id);
                $view = view('user.partials.profileActions', compact('user','status'))->render();
                return response()->json([
                    'success'=>'ok',
                    'view'=>$view,
                ]);
            }
        }


        foreach(auth()->user()->friend2 as $friend){
            if($friend->id==$request->user_id and $friend->pivot->status==1){
                $check=true;
                return response()->json([
                    'error'=>'siz dostsuz dostluq teklifi geri ala bilmezsiz'
                ]);
            }
            if($friend->id==$request->user_id and $friend->pivot->status==0){
                $check=true;
                return response()->json([
                    'error'=>'o size dostluq atib teklifi geri ala bilmezsiz'
                ]);
            }
        }

        if($check==false){
            return response()->json([
                    'error'=>'Ilk once dostluq atmaq lazimdir'
                ]);
        }

    }


    public function accept(Request $request){


        foreach(auth()->user()->friend1 as $friend){
            if($friend->id==$request->user_id and $friend->pivot->status==1){

                return response()->json([
                    'error'=>'siz dostsuz dostluq teklifi qebul ede bilmezsiz'
                ]);
            }
            if($friend->id==$request->user_id and $friend->pivot->status==0){


                return response()->json([
                    'error'=>'siz ona dostluw teklifi etmisiz teklifi qebul ede bilmezsiz'
                ]);
            }
        }


        foreach(auth()->user()->friend2 as $friend){
            if($friend->id==$request->user_id and $friend->pivot->status==1){
                return response()->json([
                    'error'=>'siz dostsuz dostluq teklifi qebul ede bilmezsiz'
                ]);
            }
            if($friend->id==$request->user_id and $friend->pivot->status==0){

                $friend->pivot->status=1;
                $friend->pivot->save();
                $status=3;

                $user=User::find($request->user_id);
                $view = view('user.partials.profileActions', compact('user','status'))->render();
                return response()->json([
                    'success'=>'ok',
                    'view'=>$view,
                    'friendrequestcount'=>auth()->user()->friend2()->wherePivot('status',0)->count(),
                ]);
            }
        }

        return response()->json([
            'error'=>'ilk once dostluq atin'
        ]);

    }


    public function decline(Request $request){


        foreach(auth()->user()->friend1 as $friend){
            if($friend->id==$request->user_id and $friend->pivot->status==1){

                return response()->json([
                    'error'=>'siz dostsuz . teklifi decline ede bilmezsiz'
                ]);
            }
            if($friend->id==$request->user_id and $friend->pivot->status==0){


                return response()->json([
                    'error'=>'siz ona dostluw teklifi etmisiz teklifi qebul ede bilmezsiz'
                ]);
            }
        }


        foreach(auth()->user()->friend2 as $friend){
            if($friend->id==$request->user_id and $friend->pivot->status==1){
                return response()->json([
                    'error'=>'siz dostsuz dostluq teklifi decline ede bilmezsiz'
                ]);
            }
            if($friend->id==$request->user_id and $friend->pivot->status==0){

                $friend->pivot->delete();

                $status=0;
                $user=User::find($request->user_id);
                $view = view('user.partials.profileActions', compact('user','status'))->render();
                return response()->json([
                    'success'=>'ok',
                    'view'=>$view,
                    'friendrequestcount'=>auth()->user()->friend2()->wherePivot('status',0)->count(),
                ]);
            }
        }

        return response()->json([
            'error'=>'ilk once dostluq atin'
        ]);

    }

    public function removeFriend(Request $request){


        foreach(auth()->user()->friend1 as $friend){
            if($friend->id==$request->user_id and $friend->pivot->status==1){

                $friend->pivot->delete();

                $status=0;
                $user=User::find($request->user_id);
                $view = view('user.partials.profileActions', compact('user','status'))->render();
                return response()->json([
                    'success'=>'ok',
                    'view'=>$view,
                ]);
            }
            if($friend->id==$request->user_id and $friend->pivot->status==0){


                return response()->json([
                    'error'=>'siz ona dostluq teklifi etmisiz dostluqdan o adami sile bilmezsiz'
                ]);
            }
        }


        foreach(auth()->user()->friend2 as $friend){
            if($friend->id==$request->user_id and $friend->pivot->status==1){
                $friend->pivot->delete();

                $status=0;
                $user=User::find($request->user_id);
                $view = view('user.partials.profileActions', compact('user','status'))->render();
                return response()->json([
                    'success'=>'ok',
                    'view'=>$view,
                ]);
            }
            if($friend->id==$request->user_id and $friend->pivot->status==0){

                return response()->json([
                    'error'=>'O size dostluq teklifi edib dostluqdan o adami sile bilmezsiz'
                ]);
            }
        }

        return response()->json([
            'error'=>'ilk once dostluq atin'
        ]);

    }

    public function blockFriend(Request $request){


        foreach(auth()->user()->block1 as $friend){
            if($friend->id==$request->user_id){

                return response()->json([
                    'error'=>'siz onu evvelceden bloklamisiniz'
                ]);

            }
        }

        foreach(auth()->user()->block2 as $friend){
            if($friend->id==$request->user_id){

                return response()->json([
                    'error'=>'o sizi bloklayib'
                ]);

            }
        }


        foreach(auth()->user()->friend1 as $friend){
            if($friend->id==$request->user_id and $friend->pivot->status==1){

                $friend->pivot->delete();

                $friend->block1()->attach($request->user_id);

                $status=4; // qabaqdakini blokladiz
                $user=User::find($request->user_id);
                $view = view('user.partials.profileActions', compact('user','status'))->render();
                return response()->json([
                    'success'=>'ok',
                    'view'=>$view,
                ]);
            }
        }


        foreach(auth()->user()->friend2 as $friend){
            $friend->pivot->delete();

            $friend->block1()->attach($request->user_id);

            $status=4; // qabaqdakini blokladiz
            $user=User::find($request->user_id);
            $view = view('user.partials.profileActions', compact('user','status'))->render();
            return response()->json([
                'success'=>'ok',
                'view'=>$view,
            ]);


        }


        auth()->user()->block1()->attach($request->user_id);


        $status=4; // qabaqdakini blokladiz
        $user=User::find($request->user_id);
        $view = view('user.partials.profileActions', compact('user','status'))->render();
        return response()->json([
            'success'=>'ok',
            'view'=>$view,
        ]);


    }

    public function blockEscape(Request $request){


        foreach(auth()->user()->block1 as $friend){
            if($friend->id==$request->user_id){

                $friend->pivot->delete();



                $status=0; // qabaqdakini blokladiz
                $user=User::find($request->user_id);
                $view = view('user.partials.profileActions', compact('user','status'))->render();
                return response()->json([
                    'success'=>'ok',
                    'view'=>$view,
                ]);

            }
        }

        foreach(auth()->user()->block2 as $friend){
            if($friend->id==$request->user_id){

                return response()->json([
                    'error'=>'o sizi bloklayib ona gore onu blokdan cixara bilmezsiniz'
                ]);

            }
        }


        return response()->json([
            'error'=>'Bu adam bloklistinizde yoxdur'
        ]);

    }
}
