@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="col-xs-12" style="margin-top: 40px">
            @foreach($users as $user)
                <div data-id="{{$user['id']}}"
                     class="user_container col-lg-4 col-md-4 col-sm-4 col-xs-12"
                     @if(!empty($user['friend_to']) && $user['friend_to'][0]['request'] == 0) data-friend="from"
                     @elseif(!empty($user['friend_from']) && $user['friend_from'][0]['request'] == 0) data-friend="to"
                     @elseif(isset($user['friend_to'][0]['request']) == 1 || isset($user['friend_from'][0]['request']) == 1) data-friend="friend"
                     @else data-friend="empty" @endif>
                    <img class="img-responsive img-thumbnail"
                         src="@if(empty($user['main_photo'])){{asset('/images/no_profile_photo.png')}} @else {{$user['main_photo'][0]['photo']}}@endif"
                         alt="user photo">
                    <div class='col-xs-12'>{{$user['name']}}</div>
                </div>
            @endforeach
        </div>
    </div>
    <!-- add-friend-modal -->
    <div id="add_friend_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-footer">
                    {!! Form::button('Close', ['class' => 'btn btn-default btn-link pull-right', 'data-dismiss' => 'modal']) !!}
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->
@endsection