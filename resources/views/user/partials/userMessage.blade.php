<li class="chat-right">
    <div class="chat-hour">{{$data->created_at->diffForHumans()}}  <span class="fa fa-check-circle"></span></div>
    <div class="chat-text">{{$data->message}}</div>
    <div class="chat-avatar">
        <img src="https://www.bootdey.com/img/Content/avatar/avatar3.png" alt="Retail Admin">
        <div class="chat-name">{{auth()->user()->name}}</div>
    </div>
</li>