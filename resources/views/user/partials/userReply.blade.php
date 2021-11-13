
<div  class="reply-item" style="position: relative">
    <img src = "https://www.bootdey.com/img/Content/avatar/avatar3.png" alt = "Retail Admin">
    <div style="position: absolute;bottom: -5px; left:20px;" class = "chat-name">{{auth()->user()->name}}</div>
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