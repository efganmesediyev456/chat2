@extends('layouts.user')

@section('content')



    <div class = "container2">
        <div class = "profile">
            <img src = "https://www.bootdey.com/img/Content/avatar/avatar3.png" alt = "">
        </div>
        <div class = "data">
            <ul>
                <li>
                    <span style = "float: left">Name</span>
                    <span style = "float: right">{{$user->name}}</span>
                </li>
                <div style = "clear: both"></div>
                <li>
                    <span style = "float: left">Email</span>
                    <span style = "float: right">{{$user->email}}</span>
                </li>
                <div style = "clear: both"></div>
                @if($user->id!=auth()->user()->id)
                    <li class = "actions">
                        @if($status==1)
                            <span style = "float: right"><a href = "" data-user-id = "{{$user->id}}" id = "backfriend"
                                        class = "btn btn-success btn-sm">Back Friend Request</a></span>
                        @endif
                        @if($status==2)
                            <span style = "float: left">Size dostluq atib</span>
                            <span style = "float: right"><a href = "" data-user-id = "{{$user->id}}" id = "decline"
                                        class = "btn btn-danger btn-sm">Redd ed</a></span>
                            <span style = "float: right"><a href = "" data-user-id = "{{$user->id}}" id = "accept"
                                        class = "btn btn-success btn-sm">Qebul Et</a></span>
                        @endif
                        @if($status==3)
                            <span style = "float: left">Siz dostsuz</span>
                            <span style = "float: right"><a href = "" data-user-id = "{{$user->id}}" id = "removeFriend"
                                        class = "btn btn-danger btn-sm">Remove Friend</a></span>
                                <span style = "float: right"><a href = "{{route('user.message',['user'=>$user->id])}}" data-user-id = "{{$user->id}}"
                                        class = "btn btn-primary btn-sm">Message</a></span>
                        @endif
                        @if($status==0)
                            <span style = "float: right"><a href = "" id = "block" class = "btn btn-danger btn-sm"
                                        data-user-id = "{{$user->id}}">Block</a></span>
                            <span style = "float: right"><a href = "" data-user-id = "{{$user->id}}" id = "addfriend"
                                        class = "btn btn-success btn-sm">Add Friends</a></span>

                        @endif


                            @if($status==4)
                                <span style = "float: left">Bu adami bloklamisiniz geri almaq ucun basin</span>
                                <span style = "float: right"><a href = "" id = "blokEscape" class = "btn btn-danger btn-sm"
                                            data-user-id = "{{$user->id}}">Escape block</a></span>

                            @endif
                    </li>
                @endif
            </ul>
        </div>
    </div>

@endsection


@push("scripts")
    <script>
        $(function () {




            // dostluq atmaq
            $('body').on('click', '#addfriend', function () {

                var data_user_id = $(this).attr('data-user-id');
                _this = this;

                $.ajax({
                    url: '/user/friend/add',
                    type: 'json',
                    method: 'post',
                    data: {'_token': '{{csrf_token()}}', 'user_id': data_user_id},
                    success: function (e) {
                        if (e.success == 'ok') {
                            $(".actions").html(e.view);
                        } else {
                            console.log(e);
                        }
                    },
                })

                return false;
            })


            //dostluq teklifini geri alma
            $('body').on('click', '#backfriend', function () {

                var data_user_id = $(this).attr('data-user-id');
                _this = this;

                $.ajax({
                    url: '/user/friend/back',
                    type: 'json',
                    method: 'post',
                    data: {'_token': '{{csrf_token()}}', 'user_id': data_user_id},
                    success: function (e) {
                        if (e.success == 'ok') {
                            $(".actions").html(e.view);
                        } else {
                            console.log(e);
                        }
                    },
                })

                return false;
            })



            //dostluq teklifini qebul etmek
            $('body').on('click', '#accept', function () {

                var data_user_id = $(this).attr('data-user-id');
                _this = this;

                $.ajax({
                    url: '/user/friend/accept',
                    type: 'json',
                    method: 'post',
                    data: {'_token': '{{csrf_token()}}', 'user_id': data_user_id},
                    success: function (e) {
                        if (e.success == 'ok') {
                            $(".actions").html(e.view);
                            $("#friendrequestcount").text(e.friendrequestcount)
                        } else {
                            console.log(e);
                        }
                    },
                })

                return false;
            })


            //dostluq teklifini redd etmek
            $('body').on('click', '#decline', function () {

                var data_user_id = $(this).attr('data-user-id');
                _this = this;

                $.ajax({
                    url: '/user/friend/decline',
                    type: 'json',
                    method: 'post',
                    data: {'_token': '{{csrf_token()}}', 'user_id': data_user_id},
                    success: function (e) {
                        if (e.success == 'ok') {
                            $(".actions").html(e.view);
                            $("#friendrequestcount").text(e.friendrequestcount)
                        } else {
                            console.log(e);
                        }
                    },
                })

                return false;
            })



            //dostu silmek



            $('body').on('click', '#removeFriend', function () {

                var data_user_id = $(this).attr('data-user-id');
                _this = this;

                $.ajax({
                    url: '/user/friend/remove',
                    type: 'json',
                    method: 'post',
                    data: {'_token': '{{csrf_token()}}', 'user_id': data_user_id},
                    success: function (e) {
                        if (e.success == 'ok') {
                            $(".actions").html(e.view);
                        } else {
                            console.log(e);
                        }
                    },
                })

                return false;
            })


            //bloklamaq


            $('body').on('click', '#block', function () {

                var data_user_id = $(this).attr('data-user-id');
                _this = this;

                $.ajax({
                    url: '/user/friend/block',
                    type: 'json',
                    method: 'post',
                    data: {'_token': '{{csrf_token()}}', 'user_id': data_user_id},
                    success: function (e) {
                        if (e.success == 'ok') {
                            $(".actions").html(e.view);
                        } else {
                            console.log(e);
                        }
                    },
                })

                return false;
            })


            $('body').on('click', '#blokEscape', function () {

                var data_user_id = $(this).attr('data-user-id');
                _this = this;

                $.ajax({
                    url: '/user/friend/block/escape',
                    type: 'json',
                    method: 'post',
                    data: {'_token': '{{csrf_token()}}', 'user_id': data_user_id},
                    success: function (e) {
                        if (e.success == 'ok') {
                            $(".actions").html(e.view);
                        } else {
                            console.log(e);
                        }
                    },
                })

                return false;
            })


        })
    </script>
@endpush()
