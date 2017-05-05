@extends('layouts.app')
@section('content')
    <div class="messages_container">
        @foreach($messages as $message)
            <div class="message_container col-xs-12 @if($message['user_from']['id'] == $id) right @else left @endif">
                <div class="message_and_name col-xs-10">
                    <span class="name">{{$message['user_from']['name']}}</span>
                    <span class="message">{{$message['message']}}</span>
                </div>
                <div class="user_photo col-xs-2">
                    <img class="img-responsive"
                         src="@if(!empty($message['user_from']['main_photo'])){{$message['user_from']['main_photo'][0]['photo']}}@else{{asset('/images/no_profile_photo.png')}}@endif"
                         alt="user photo">
                </div>
            </div>
        @endforeach
    </div>
    <div class="send_message_container">
        <div class="form-group">
            <label for="comment">Message:</label>
            {!! Form::textarea('message', '', ['class' => 'form-control', 'id' => 'message', 'rows' => '5']) !!}
        </div>
        {!! Form::submit('SEND', ['class' => 'btn btn-block btn-default btn-primary', 'name'=>'send_message', 'id' => 'send_message', 'data-id' => $friendId]) !!}
    </div>
@endsection