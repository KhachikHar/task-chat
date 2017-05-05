@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="col-xs-12" style="margin-top: 40px">
            @foreach($requests as $request)
                <div data-id="{{$request['user_id']}}"
                    class="user_container col-lg-4 col-md-4 col-sm-4 col-xs-12 @if($request['request_notification'] == 0) main_photo @endif"
                    data-friend="to">
                    <img class="img-responsive img-thumbnail"
                         src="@if(!empty($request['user_from']['main_photo'])){{$request['user_from']['main_photo'][0]['photo']}}@else{{asset('/images/no_profile_photo.png')}}@endif"
                         alt="user photo">
                    <div class='col-xs-12'>
                        {{$request['user_from']['name']}}
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