
<div class="col-xl-4 col-lg-4 col-md-4 col-sm-3 col-3">
    <div class="users-container">
        <div class="chat-search-box">
            <div class="input-group">
                <input class="form-control" placeholder="Search">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-info">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <ul class="users">
            @foreach($users as $user)
            <li class="person" data-chat="person1">

                <div class="user">
                    <a href = "{{route('user.profile',["user"=>$user->id])}}">
                    <img src="https://www.bootdey.com/img/Content/avatar/avatar3.png" alt="Retail Admin">
                    </a>
                    <span class="status busy"></span>
                </div>
                <p class="name-time">
                    <span class="name">{{$user->name}}</span>
                    <span class="time">{{$user->created_at->diffForHumans()}}</span>
                </p>

            </li>
            @endforeach

            {{--<li class="person active-user" data-chat="person2">--}}
                {{--<div class="user">--}}
                    {{--<img src="https://www.bootdey.com/img/Content/avatar/avatar2.png" alt="Retail Admin">--}}
                    {{--<span class="status away"></span>--}}
                {{--</div>--}}
                {{--<p class="name-time">--}}
                    {{--<span class="name">Peter Gregor</span>--}}
                    {{--<span class="time">12/02/2019</span>--}}
                {{--</p>--}}
            {{--</li>--}}

        </ul>
    </div>
</div>