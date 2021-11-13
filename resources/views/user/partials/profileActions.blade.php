@if($status==1)
    <span style = "float: right"><a href = "" data-user-id = "{{$user->id}}" id = "backfriend"
                class = "btn btn-success btn-sm">Back Friend Request</a></span>
@endif
@if($status==2)
    <span style = "float: left">Size dostluq atib</span>
    <span style = "float: right"><a href = "" data-user-id = "{{$user->id}}" id = "remove"
                class = "btn btn-danger btn-sm">Redd ed</a></span>
    <span style = "float: right"><a href = "" data-user-id = "{{$user->id}}" id = "accept"
                class = "btn btn-success btn-sm">Qebul Et</a></span>
@endif
@if($status==3)
    <span style = "float: left">Siz dostsuz</span>
    <span style = "float: right"><a href = "" data-user-id = "{{$user->id}}" id = "addfriend"
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