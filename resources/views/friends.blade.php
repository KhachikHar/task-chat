@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="col-xs-12" style="margin-top: 40px">
            @foreach($friends as $friend)
                <div data-id="@if($friend['user_id'] != Auth::user()->id){{$friend['user_id']}} @else {{$friend['user_id']}} @endif"
                     class="user_container col-lg-4 col-md-4 col-sm-4 col-xs-12"
                     data-friend="friend">
                    <img class="img-responsive img-thumbnail"
                         src="@if(!empty($friend['user_to']) && !empty($friend['user_to']['main_photo'])){{$friend['user_to']['main_photo'][0]['photo']}}@elseif(!empty($friend['user_from']) && !empty($friend['user_from']['main_photo'])){{$friend['user_from']['main_photo'][0]['photo']}}@else{{asset('/images/no_profile_photo.png')}}@endif"
                         alt="user photo">
                    <div class='col-xs-12'>
                        @if(!empty($friend['user_to'])) {{$friend['user_to']['name']}}
                        @else {{$friend['user_from']['name']}} @endif
                    </div>
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