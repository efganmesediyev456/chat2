<li class = "chat-left">
    <div class = "chat-avatar">
        <img src = "https://www.bootdey.com/img/Content/avatar/avatar3.png" alt = "Retail Admin">
        <div class = "chat-name">{{auth()->user()->name}}</div>
    </div>
    <div class = "chat-text">{{$comment->comment}}
    </div>
    <div class = "chat-hour">{{$comment->updated_at->diffForHumans()}}
        <span class = "fa fa-check-circle"></span>
        <a href = "#" class = "deleteComment" data-comment-id = "{{$comment->id}}">Delete Comment</a>
        &nbsp;
        &nbsp;&nbsp;
        <a href = "#" class="editComment" data-comment-id="{{$comment->id}}">Edit Comment</a>
        &nbsp; &nbsp;
        <a href = "#" class = "replyComment"
                data-comment-id = "{{$comment->id}}">Reply</a>
        <div class="likecommentclass">
            <span>{{$comment->likes()->count()}}</span>
            <a href = "" class="likeComment" data-comment-id="{{$comment->id}}"><i class="fa fa-thumbs-up " aria-hidden="true"></i></a> &nbsp;
            <span>{{$comment->dislikes()->count()}}</span>
            <a href = "" class="dislikeComment" data-comment-id="{{$comment->id}}"> <i class="fa fa-thumbs-down" aria-hidden="true"></i></a>

        </div>

    </div>

    <div class = "chat-reply">
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