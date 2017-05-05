@extends('layouts.app')
@section('content')
    <!-- new-photo-modal -->
    <div id="upload_photo_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 style="float: left;">Update Profile Picture</h6>
                    <button type="button" class="btn btn-transparent glyphicon glyphicon-remove pull-right" data-dismiss="modal"></button>
                </div>
                {!! Form::open(['route' => 'new_photo', 'files' => true]) !!}
                <div class="modal-body">
                    <div class="form-group" style="overflow: hidden;">
                        <label type="button" class="btn btn-default upload_photo_label">
                            <span class="glyphicon glyphicon-plus"></span> upload photo
                            {{Form::file('user_photo', ['class' => 'form-control hide upload_photo_input', 'accept' => 'image/*'])}}
                        </label>
                    </div>
                    <div class="upload_photo_container">
                        <span class="glyphicon glyphicon-remove remove_upload_photo"></span>
                        <img class="img-responsive photo" src="" />
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::submit('Save', ['class' => 'btn btn-default btn-primary save_photo', 'name'=>'add_new_photo']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- end modal -->

    <div class="container">
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
            <div class="photo_container">
                <img class="img-responsive img-thumbnail"
                     src="@if(empty($photo)){{asset('/images/no_profile_photo.png')}} @else {{$photo[0]['photo']}}@endif"
                     alt="user photo">
                <span class="upload_new_photo" data-toggle="modal" data-target="#upload_photo_modal">
                    <span class="glyphicon glyphicon-camera"></span> &nbsp;&nbsp;Add Photo
                </span>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
            <a class="btn btn-block btn-default" href="{{route('photos_view')}}">Photos</a>
            <a class="btn btn-block btn-default" href="{{route('users_view')}}">Users</a>
            <a class="btn btn-block btn-default" href="{{route('friends_view')}}">Friends</a>
            <a href="{{route('friend_requests')}}" class="btn-group col-xs-12" style="margin-top: 5px; padding: 0;">
                <button class="btn @if($friendRequests == 0) btn-block @else col-xs-10 @endif btn-default">Friend Requests</button>
                @if($friendRequests > 0) <button type="button" class="btn col-xs-2 btn-danger">{{$friendRequests}}</button> @endif
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 pull-right" style="overflow: hidden; background-color: #ffffff; padding: 20px 0 0 0 !important;">
            @foreach($onlineFriends as $friend)
                <a href="{{route('chat', $friend['id'])}}" data-id="{{$friend['id']}}" class="online_user_container col-xs-12" data-friend="friend">
                    <div class="online_user_photo col-xs-3">
                        <img class="img-responsive"
                             src="@if(!empty($friend['main_photo'])){{$friend['main_photo'][0]['photo']}}@else{{asset('/images/no_profile_photo.png')}}@endif"
                             alt="user photo">
                    </div>
                    <span class="col-xs-6 online_user_name">{{$friend['name']}}</span>
                    @if(count($friend['unread_messages']) > 0)
                        <span class="col-xs-3 unread_messages">
                            <span class="glyphicon glyphicon-envelope"></span> &nbsp;
                            <span class="unread_messages_count">{{count($friend['unread_messages'])}}</span>
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
@endsection
