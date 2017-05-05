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
        <div class="col-xs-12">
            <span class="new_photo" data-toggle="modal" data-target="#upload_photo_modal">
                <span class="glyphicon glyphicon-camera"></span> &nbsp;&nbsp;Add New Photo
            </span>
        </div>
        <div class="col-xs-12" style="margin-top: 40px">
            @foreach($photos as $photo)
                <div class="photo_container col-lg-4 col-md-4 col-sm-4 col-xs-12 @if($photo['main'] == 1) main_photo @endif">
                    <img class="img-responsive img-thumbnail user_photo" data-id="{{$photo['id']}}" src="{{$photo['photo']}}" alt="user photo">
                </div>
            @endforeach
        </div>
    </div>

    <!-- view-photo-modal -->
    <div id="view_photo_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    {!! Form::button('Delete', ['class' => 'btn btn-default btn-warning pull-left delete_photo']) !!}
                    {!! Form::button('Make main photo', ['class' => 'btn btn-default btn-success pull-right edit_main_photo']) !!}
                </div>
                <div class="modal-footer">
                    {!! Form::button('Close', ['class' => 'btn btn-default btn-link pull-right', 'data-dismiss' => 'modal']) !!}
                </div>
            </div>

        </div>
    </div>
    <!-- end modal -->
@endsection