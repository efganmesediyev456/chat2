@extends("layouts.user")
@section("content")
<div class="col-xl-8 col-lg-8 col-md-8 col-sm-9 col-9">
    <div class="selected-user">
        <span>To: <span class="name">{{$user->name}}</span></span>
    </div>
    <div class="chat-container">
        <ul class="chat-box chatContainerScroll">

            @foreach($messages as $a)


                @if($a->to_id==auth()->user()->id)
            <li class="chat-left">
                <div class="chat-avatar">
                    <img src="https://www.bootdey.com/img/Content/avatar/avatar3.png" alt="Retail Admin">
                    <div class="chat-name">{{$user->name}}</div>
                </div>
                <div class="chat-text">{{$a->message}}
                   </div>
                <div class="chat-hour">{{$a->created_at->diffForHumans()}} <span class="fa fa-check-circle"></span></div>
            </li>
                @endif
                    @if($a->from_id==auth()->user()->id)
            <li class="chat-right">
                <div class="chat-hour">{{$a->created_at->diffForHumans()}}  <span class="fa fa-check-circle"></span></div>
                <div class="chat-text">{{$a->message}}</div>
                <div class="chat-avatar">
                    <img src="https://www.bootdey.com/img/Content/avatar/avatar3.png" alt="Retail Admin">
                    <div class="chat-name">{{auth()->user()->name}}</div>
                </div>
            </li>
                    @endif
            @endforeach

        </ul>
        <div class="form-group mt-3 mb-0">

            <div class="row my-3">
                <textarea id="message" class="form-control" rows="3" placeholder="Type your message here..."></textarea>

                <button data_user_id="{{$user->id}}" class="btn btn-primary my-3" id="send">Send</button>
            </div>
        </div>
    </div>
</div>
    @endsection


@push("scripts")
    <script>
        $(function () {

            $('body').on('click', '#send', function () {

                var data_user_id = $(this).attr('data_user_id');
                var message = $("#message").val();
                _this = this;

                $.ajax({
                    url: '/user/message',
                    type: 'json',
                    method: 'post',
                    data: {'_token': '{{csrf_token()}}', 'user_id': data_user_id,'message':message},
                    success: function (e) {
                        if (e.success == 'ok') {

                            $('.chat-box').append(e.view);
                            $('#message').val('');
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
