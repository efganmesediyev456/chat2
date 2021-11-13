@extends("layouts.user")
@section("content")
    <div class = "col-xl-8 col-lg-8 col-md-8 col-sm-9 col-9">
        <div class = "selected-user">
            <span><span class = "name">{{auth()->user()->name}}</span> <span>{{auth()->user()->email}}</span></span>
        </div>
        <div class = "chat-container">
            <ul class = "chat-box chatContainerScroll" id = "chat-box">
                @forelse($comments->whereNotIn('user_id',auth()->user()->blocks()->pluck("id")->toArray()) as $comment)

                    <li class = "chat-left">
                        <div class = "chat-avatar">
                            <img src = "https://www.bootdey.com/img/Content/avatar/avatar3.png" alt = "Retail Admin">
                            <div class = "chat-name">{{$comment->user->name}}</div>
                        </div>
                        <div class = "chat-text">{{$comment->comment}}
                        </div>
                        <div class = "chat-hour">{{$comment->updated_at->diffForHumans()}}

                            <span class = "fa fa-check-circle"></span>
                            @if($comment->user->id==auth()->user()->id)
                                <a href = "#" class = "deleteComment"
                                        data-comment-id = "{{$comment->id}}">Delete Comment</a>
                            @endif
                            &nbsp; &nbsp;
                            @if($comment->user->id==auth()->user()->id)
                                <a href = "#" class = "editComment"
                                        data-comment-id = "{{$comment->id}}">Edit Comment</a>
                            @endif
                            &nbsp; &nbsp; <a href = "#" class = "replyComment"
                                    data-comment-id = "{{$comment->id}}">Reply</a> &nbsp;
                            <div class="likecommentclass">
                                <span>{{$comment->likes()->count()}}</span>
                                <a href = "" class="likeComment" data-comment-id="{{$comment->id}}"><i class="fa fa-thumbs-up " aria-hidden="true"></i></a> &nbsp;
                                <span>{{$comment->dislikes()->count()}}</span>
                                <a href = "" class="dislikeComment" data-comment-id="{{$comment->id}}"> <i class="fa fa-thumbs-down" aria-hidden="true"></i></a>

                            </div>
                        </div>

                        <div class = "chat-reply">
                            @foreach($comment->replies->whereNotIn('user_id',auth()->user()->blocks()->pluck("id")->toArray()) as $reply)
                               <div  class="reply-item" style="position: relative">
                                   <img src = "https://www.bootdey.com/img/Content/avatar/avatar3.png" alt = "Retail Admin">
                                   <div class = "chat-name" style="">{{$reply->user->name}}</div>
                                   <div class = "chat-text">{{$reply->reply}}</div>
                                   <div class = "chat-hour">{{$reply->updated_at->diffForHumans()}}
                                       <span class = "fa fa-check-circle"></span>
                                       @if($reply->user->id==auth()->user()->id)
                                           <a href = "#" class = "deleteReply"
                                                   data-reply-id = "{{$reply->id}}">Delete Reply</a>
                                       @endif
                                       &nbsp; &nbsp;
                                       @if($reply->user->id==auth()->user()->id)
                                           <a href = "#" class = "editReply"
                                                   data-reply-id = "{{$reply->id}}">Edit Reply</a>
                                       @endif

                                       <div class="likecommentclass">
                                           <span>{{$reply->likes()->count()}}</span>
                                           <a href = "" class="likeReply" data-reply-id="{{$reply->id}}"><i class="fa fa-thumbs-up " aria-hidden="true"></i></a> &nbsp;
                                           <span>{{$reply->dislikes()->count()}}</span>
                                           <a href = "" class="dislikeReply" data-reply-id="{{$reply->id}}"> <i class="fa fa-thumbs-down" aria-hidden="true"></i></a>

                                       </div>
                                   </div>
                               </div>
                                @endforeach
                            <form action = "" method = "post" style="display: none;">
                                <div class = "form-group mt-3 mb-0">
                                    <textarea  class = "form-control" rows = "3" placeholder = "Type your message here..."></textarea>
                                </div>
                                <div class = "form-group my-3">
                                    <a type = "button"  data-comment-id = "{{$comment->id}}" class = "btn btn-success btn-sm shareAltComment" >Share</a>
                                </div>
                            </form>
                        </div>
                    </li>
                @empty
                    No comment Yet
                @endforelse
            </ul>
            <form action = "" method = "post">
                <div class = "form-group mt-3 mb-0">
                    <textarea id = "textComment" class = "form-control" rows = "3"
                            placeholder = "Type your message here..."></textarea>
                </div>
                <div class = "form-group my-3">
                    <a type = "button" class = "btn btn-success btn-sm" id = "share">Share</a>
                </div>
            </form>
        </div>
    </div>
@endsection


@push("scripts")
    <script>
        $(function () {
            //share comment
            function toasterOptions() {
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": true,
                    "positionClass": "toast-top-center",
                    "preventDuplicates": true,
                    "onclick": null,
                    "showDuration": "100",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "show",
                    "hideMethod": "hide"
                };
            };


            toasterOptions();
            $("#share").click(function () {
                var data = $("#textComment").val();
                $.ajax({
                    url: '/user/comment/share',
                    type: 'json',
                    method: 'post',
                    data: {'_token': '{{csrf_token()}}', 'comment': data},
                    success: function (e) {
                        if (e.success) {
                            $("#chat-box").append(e.view);
                            $("#textComment").val('');
                        } else {
                            toastr.error(e.comment)
                            console.log(e)
                        }
                    }
                })
                return false;
            })

            //delete comment

            $("body").on('click', '.deleteComment', function (e) {

                var comment_id = $(this).attr('data-comment-id');

                _this=this;
                $.ajax({
                    url: '/user/comment/delete',
                    type: 'json',
                    method: 'post',
                    data: {'_token': '{{csrf_token()}}', 'comment_id': comment_id},
                    success: function (e) {
                        if (e.success) {
                            $('[data-comment-id="' + e.comment_id + '"]').parent().parent().remove();
                        } else {
                            console.log(e)
                        }
                    },
                })

                return false;
            })


            //edit comment

            $("body").on('click', '.editComment', function (e) {
                var comment_id = $(this).attr('data-comment-id');
                var data = $(this).parents('.chat-hour').siblings('.chat-text').text();
                $(this).parents('.chat-hour').siblings('.chat-text').html('<input class="form-control" type="text" value="' + data + '">');


                // -----------------focus etmek ucun------------------------
                var num = $(this).parents('.chat-hour').siblings('.chat-text').find('input').val();
                $(this).parents('.chat-hour').siblings('.chat-text').find('input').focus().val('').val(num)


                $(this).parent().find('.likecommentclass').before('&nbsp&nbsp;<a href="#"  class="saveComment" data-comment-id="' + comment_id + '">Save Comment</a>');
                $(this).remove();
                return false;
            })


            //save comment

            $("body").on('click', '.saveComment', function (e) {
                var comment_id = $(this).attr('data-comment-id');
                var comment = $(this).parents('.chat-hour').siblings('.chat-text').find('input').val();
                comment = $.trim(comment);

                _this = this;
                $.ajax({
                    url: '/user/comment/update',
                    type: 'json',
                    method: 'post',
                    data: {'_token': '{{csrf_token()}}', 'comment_id': comment_id, 'comment': comment},
                    success: function (e) {
                        if (e.success) {
                            $(_this).parents('.chat-left').replaceWith(e.view);
                        } else {
                            console.log(e);
                        }
                    },
                })

                return false;
            })



            //reply cick  textarea show

            $("body").on('click', '.replyComment', function (e) {

                $(this).parents('.chat-hour').siblings('.chat-reply').find('form').css('display','block');

                return false;
            })



            //share alt comment

            $("body").on('click', '.shareAltComment', function (e) {

                var comment_id = $(this).attr('data-comment-id');

                var reply=$(this).parents('form').find('textarea').val();

                _this=this;

                $.ajax({
                    url: '/user/reply/share',
                    type: 'json',
                    method: 'post',
                    data: {'_token': '{{csrf_token()}}', 'comment_id': comment_id, 'reply': reply},
                    success: function (e) {

                       if(e.success=='ok'){
                           $(_this).parents('form').before(e.view);

                           $(_this).parents('form').find('textarea').val('');
                       }else{
                           console.log(e);
                       }

                    },
                })


                return false;
            })



            //delete reply

            $("body").on('click', '.deleteReply', function (e) {

                var reply_id = $(this).attr('data-reply-id');


                _this=this;
                $.ajax({
                    url: '/user/reply/delete',
                    type: 'json',
                    method: 'post',
                    data: {'_token': '{{csrf_token()}}', 'reply_id': reply_id},
                    success: function (e) {
                        if (e.success) {
                            $(_this).parents('.reply-item').remove();
                        } else {
                            console.log(e)
                        }
                    },
                })

                return false;
            })

            //edit reply

            $("body").on('click', '.editReply', function (e) {
                var reply_id = $(this).attr('data-reply-id');
                var data = $(this).parents('.chat-hour').siblings('.chat-text').text();
                $(this).parents('.chat-hour').siblings('.chat-text').html('<input class="form-control" type="text" value="' + data + '">');


                // -----------------focus etmek ucun------------------------
                var num = $(this).parents('.chat-hour').siblings('.chat-text').find('input').val();
                $(this).parents('.chat-hour').siblings('.chat-text').find('input').focus().val('').val(num)


                $(this).parent().append('<a href="#"  class="saveReply" data-reply-id="' + reply_id + '">Save Reply</a>');
                $(this).remove();
                return false;
            })



            //save reply

            $("body").on('click', '.saveReply', function (e) {
                var reply_id = $(this).attr('data-reply-id');
                var reply = $(this).parents('.chat-hour').siblings('.chat-text').find('input').val();
                reply = $.trim(reply);

                _this = this;
                $.ajax({
                    url: '/user/reply/update',
                    type: 'json',
                    method: 'post',
                    data: {'_token': '{{csrf_token()}}', 'reply_id': reply_id, 'reply': reply},
                    success: function (e) {
                        if (e.success) {
                            $(_this).parents('.reply-item').replaceWith(e.view);
                        } else {
                            console.log(e);
                        }
                    },
                })

                return false;
            })




            //like comment
            $('body').on('click','.likeComment',function (e) {
                var comment_id = $(this).attr('data-comment-id');
                _this = this;
                $.ajax({
                    url: '/user/comment/like',
                    type: 'json',
                    method: 'post',
                    data: {'_token': '{{csrf_token()}}', 'comment_id': comment_id},
                    success: function (e) {
                        if (e.success=='ok') {
                            $(_this).parents('.likecommentclass').find('span:first').text(e.like_count);
                            $(_this).parents('.likecommentclass').find('span:last').text(e.dislike_count);
                        } else {
                            console.log(e);
                        }
                    },
                })

                return false;
            })



            //dislike comment
            $('body').on('click','.dislikeComment',function (e) {
                var comment_id = $(this).attr('data-comment-id');
                _this = this;

                $.ajax({
                    url: '/user/comment/dislike',
                    type: 'json',
                    method: 'post',
                    data: {'_token': '{{csrf_token()}}', 'comment_id': comment_id},
                    success: function (e) {
                        if (e.success=='ok') {
                            $(_this).parents('.likecommentclass').find('span:first').text(e.like_count);
                            $(_this).parents('.likecommentclass').find('span:last').text(e.dislike_count);
                        } else {
                            console.log(e);
                        }
                    },
                })

                return false;
            })


            //like reply
            $('body').on('click','.likeReply',function (e) {
                var reply_id = $(this).attr('data-reply-id');
                _this = this;
                $.ajax({
                    url: '/user/reply/like',
                    type: 'json',
                    method: 'post',
                    data: {'_token': '{{csrf_token()}}', 'reply_id': reply_id},
                    success: function (e) {
                        if (e.success=='ok') {
                            $(_this).parents('.likecommentclass').find('span:first').text(e.like_count);
                            $(_this).parents('.likecommentclass').find('span:last').text(e.dislike_count);
                        } else {
                            console.log(e);
                        }
                    },
                })

                return false;
            })



            //dislike reply
            $('body').on('click','.dislikeReply',function (e) {

                var reply_id = $(this).attr('data-reply-id');
                _this = this;

                $.ajax({
                    url: '/user/reply/dislike',
                    type: 'json',
                    method: 'post',
                    data: {'_token': '{{csrf_token()}}', 'reply_id': reply_id},
                    success: function (e) {
                        if (e.success=='ok') {
                            $(_this).parents('.likecommentclass').find('span:first').text(e.like_count);
                            $(_this).parents('.likecommentclass').find('span:last').text(e.dislike_count);
                        } else {
                            console.log(e);
                        }
                    },
                })

                return false;
            })


        })
    </script>
@endpush
