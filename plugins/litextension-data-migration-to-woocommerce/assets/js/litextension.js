jQuery(document).ready(function($) {
    $('#signin').on('submit', function(e) {
        e.preventDefault();
        var email = $('#email').val();
        var password = $('#password').val();
        var url = $('#url').val();

        $.ajax({
            type:"POST",
            url: url,
            dataType : 'json',
            data: {
                email: email,
                password: password
            },
            success: function(response) {
                if (response['status'] == 'error'){
                    $('#msg').text(response['msg']);
                    if ( $('.lit-dialog').is(":hidden") ){
                        $('.lit-dialog').show();
                    }
                    //$('.lit-dialog').css('display', 'block');
                } else {

                    $("#security_token").val(response['security_token']);
                    addClassDisplay($('.lit-dialog'));
                    $('#user-msg').text('You are logged in LitExtension as ' + email + '.');
                    if ( $('.lit-dialog-user').is(":hidden") ){
                        $('.lit-dialog-user').show();
                    }
                    //$('.lit-dialog-user').css('display', 'block');
                    $('#lit-signin').addClass('done');
                    addClassDisplay($('#body-signin'));

                    if ( $('#lit-connect').hasClass('hidden-panel') ){
                        $('#lit-connect').removeClass('hidden-panel');
                    }
                    /*
                    if ( $('#lit-connect').is(":hidden")){
                        $('#lit-connect').show();
                    }*/
                    $('#body-connect').show();
                    $.ajax({
                        type:"GET",
                        url: $('#admin-url').val() + '?page=add-session&litEmail=' + email + "&security_token=" + response['security_token'],
                        dataType : 'text',
                        success: function(response) {
                            console.log(response);
                        }
                    });
                }
            }
        });
    });

    $('#button-close').click(function (e) {
        addClassDisplay($('.lit-dialog'));
    });

    $('#user-button-logout').click(function (e) {
        addClassDisplay($('.lit-dialog-user'));
        if ( $('#lit-signin').hasClass('done') ){
            $('#lit-signin').removeClass('done');
        }
        $('#lit-signin').show();
        removeClassDisplay($('#body-signin'));
        if (!$('#lit-connect').hasClass('hidden-panel') ){
            $('#lit-connect').addClass('hidden-panel');
        }
        $('#body-connect').hide();
        $.ajax({
            type:"GET",
            url: $('#admin-url').val() + '?page=clear-session',
            dataType : 'text',
            success: function(response) {
                console.log(response);
            }
        });
    });

    $('#connect').on('submit', function(e) {
        e.preventDefault();
        var src_type = $('select#src-type option:checked').val();
        var src_url = $('#src-url').val();
        var url = $('#connect-url').val();
        var security_token = $('#security_token').val();
        url = url + '&src_type='+src_type+'&src_url=' + encodeURI(src_url) + "&security_token=" + encodeURI(security_token);
        // console.log(src_type);
        window.open(url, '_blank');
    });

    function addClassDisplay(child) {
        if (!child.hasClass('display-none')){
            child.addClass('display-none');
            setTimeout(function (e) {child.css('display', 'none');}, 500)
        }
    }

    function removeClassDisplay(child) {
        if (child.hasClass('display-none')){
            child.removeClass('display-none');
            setTimeout(function (e) {child.css('display', 'block');}, 500)
        }
    }
});