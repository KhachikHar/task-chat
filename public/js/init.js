$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {
    var url = window.location.pathname.substr(1).split('/');
    var select_messages = true;

    function selectMessages() {
        var friend_id = url[2];
        var main_photo = '';
        var position = '';
        var name = '';
        var message = '';
        select_messages = false;
        $.ajax({
            method: "POST",
            url: '/home/select/messages',
            data: {
                friend_id: friend_id
            },
            success: function(result) {
                if(result.success) {
                    $.each (result[0], function(){
                        name = this.user_from.name;
                        message = this.message;
                        if(this.user_from.id == friend_id){
                            position = 'left';
                        } else {
                            position = 'right';
                        }
                        if(this.user_from.main_photo.length > 0){
                            main_photo = this.user_from.main_photo[0].photo;
                        } else {
                            main_photo = '/images/no_profile_photo.png';
                        }
                        $('.messages_container').append('<div class="message_container col-xs-12 '+position+'">' +
                            '<div class="message_and_name col-xs-10">' +
                            '<span class="name">'+name+'</span>' +
                            '<span class="message">'+message+'</span>' +
                            '</div>' +
                            '<div class="user_photo col-xs-2">' +
                            '<img class="img-responsive" src="'+main_photo+'"> ' +
                            '</div>' +
                            '</div>');
                    });
                    select_messages = true;
                } else {
                    console.log('Something went wrong, please try again with correct data.');
                    select_messages = true;
                }
            },
            error: function () {
                console.log('There were some error, while trying to make request.');
                select_messages = true;
            }
        });

    }

    if(url[0] == 'home' && url[1] == 'chat'){
        setInterval(function(){
            if(select_messages == true) {
                selectMessages();
            }
        },2500);
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.photo').attr('src', e.target.result);
                $('.upload_photo_container').slideDown();
                $('#upload_photo_modal').find('.modal-footer').slideDown();
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".upload_photo_input").change(function(){
        readURL(this);
    });
    $(document).on('click', '.remove_upload_photo', function () {
        $(".upload_photo_input").val('');
        $('#upload_photo_modal').find('.modal-footer').slideUp(300, function () {
            $('.upload_photo_container').slideUp();
            $('.photo').attr('src', '');
        });
    });

    $('.user_photo').click(function () {
        id = $(this).data('id');
        this_container = $(this).parent('.photo_container');
        $('#view_photo_modal').modal('show');
    });
    $(document).on('click', '.edit_main_photo', function () {
        var this_btn = $(this);
        this_btn.attr('disabled', true);
        this_btn.parent().find('.delete_photo').attr('disabled', true);
        $.ajax({
            method: "POST",
            url: '/home/change/main/photo',
            data: {
                id: id
            },
            success: function(result) {
                if(result.success) {
                    $('.photo_container').removeClass('main_photo');
                    this_container.addClass('main_photo');
                    $('#view_photo_modal').modal('hide');
                    this_btn.attr('disabled', false);
                    this_btn.parent().find('.delete_photo').attr('disabled', false);
                } else {
                    alert('Something went wrong, please try again with correct data.');
                    this_btn.attr('disabled', false);
                    this_btn.parent().find('.delete_photo').attr('disabled', false);
                }
            },
            error: function () {
                alert('There were some error, while trying to make request.');
                this_btn.attr('disabled', false);
                this_btn.parent().find('.delete_photo').attr('disabled', false);
            }
        });
    });
    $(document).on('click', '.delete_photo', function () {
        var this_btn = $(this);
        this_btn.attr('disabled', true);
        this_btn.parent().find('.edit_main_photo').attr('disabled', true);
        $.ajax({
            method: "POST",
            url: '/home/delete/photo',
            data: {
                id: id
            },
            success: function(result) {
                if(result.success) {
                    this_container.remove();
                    if(result[0] != ''){
                        $('.user_photo').each(function () {
                            if($(this).data('id') == result[0]){
                                $(this).parent('.photo_container').addClass('main_photo');
                            }
                        });
                    }
                    $('#view_photo_modal').modal('hide');
                    this_btn.attr('disabled', false);
                    this_btn.parent().find('.delete_photo').attr('disabled', false);
                } else {
                    alert('Something went wrong, please try again with correct data.');
                    this_btn.attr('disabled', false);
                    this_btn.parent().find('.delete_photo').attr('disabled', false);
                }
            },
            error: function () {
                alert('There were some error, while trying to make request.');
                this_btn.attr('disabled', false);
                this_btn.parent().find('.delete_photo').attr('disabled', false);
            }
        });
    });

    $('.user_container').click(function () {
       id = $(this).data('id');
       this_container = $(this);
       var friend = $(this).data('friend');
       var modal_header = $('#add_friend_modal').find('.modal-header');
       if(friend == 'empty'){
           modal_header.html('<button type="button" class="btn btn-block btn-success add_friend">(+) Add friend</button>');
       } else if(friend == 'from') {
           modal_header.html('<button type="button" class="btn btn-block btn-success">Friend request sent</button>');
       } else if(friend == 'to') {
           modal_header.html('<div class="btn-group col-xs-12">' +
                   '<button type="button" class="btn btn-danger col-xs-6 delete_friend_request">delete friend request</button>' +
                   '<button type="button" class="btn btn-success col-xs-6 confirm_friend_request">confirm friend request</button>' +
               '</div>')
        } else {
           modal_header.html('<a href="/home/chat/'+id+'" type="button" class="btn btn-block btn-primary col-xs-6 send_message">Chat</a>')
        }
        if($(this).hasClass('main_photo')){
            $(this).removeClass('main_photo');
        }
        $('#add_friend_modal').modal('show');
    });
    $(document).on('click', '.add_friend', function () {
        $.ajax({
            method: "POST",
            url: '/home/add/friend',
            data: {
                user_id: id
            },
            success: function(result) {
                if(result.success) {
                    this_container.data('friend', 'from');
                    $('#add_friend_modal').modal('hide');
                } else {
                    alert('Something went wrong, please try again with correct data.');
                }
            },
            error: function () {
                alert('There were some error, while trying to make request.');
            }
        });
    });
    $(document).on('click', '.delete_friend_request', function () {
        $.ajax({
            method: "POST",
            url: '/home/delete/friend/request',
            data: {
                id: id
            },
            success: function(result) {
                if(result.success) {
                    this_container.data('friend', 'empty');
                    $('#add_friend_modal').modal('hide');
                    if(url[1] == 'friend' && url[2] == 'requests'){
                        this_container.remove();
                        if($('.user_container').length == 0){
                            window.location.href = '/home';
                        }
                    }
                } else {
                    alert('Something went wrong, please try again with correct data.');
                }
            },
            error: function () {
                alert('There were some error, while trying to make request.');
            }
        });
    });
    $(document).on('click', '.confirm_friend_request', function () {
        $.ajax({
            method: "POST",
            url: '/home/confirm/friend',
            data: {
                id: id
            },
            success: function(result) {
                if(result.success) {
                    this_container.remove();
                    $('#add_friend_modal').modal('hide');
                    if($('.user_container').length == 0){
                        window.location.href = '/home';
                    }
                } else {
                    alert('Something went wrong, please try again with correct data.');
                }
            },
            error: function () {
                alert('There were some error, while trying to make request.');
            }
        });
    });

    $('#send_message').click(function () {
        var message = $('#message').val().replace(/\s+/g,' ').trim();
        var friend_id = $(this).data('id');
        if(message != ''){
            $.ajax({
                method: "POST",
                url: '/home/send/message',
                data: {
                    message: message,
                    friend_id: friend_id
                },
                success: function(result) {
                    if(result.success) {
                        $('#message').val('');
                        if(select_messages = true) {
                            select_messages = false;
                            selectMessages();
                        }
                    } else {
                        alert('Something went wrong, please try again with correct data.');
                    }
                },
                error: function () {
                    alert('There were some error, while trying to make request.');
                }
            });
        }
    });

});
