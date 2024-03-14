// JavaScript Document
jQuery(document).ready(function ($) {
    $("#save_prayer_data").click(function () {
        $(".help-block").hide();
        if ($("#prayer_author_name").val() == '') {
            $("#prayer_author_name").focus();
            $("#prayer_author_name_error").show();
            return false;
        }
        if ($("#prayer_author_email").val() == '') {
            $("#prayer_author_email").focus();
            $("#prayer_author_email_error").show();
            return false;
        }
        if ($("#prayer_title").val() == '') {
            $("#prayer_title").focus();
            $("#prayer_title_error").show();
            return false;
        }
        if ($("#prayer_messages").val() == '') {
            $("#prayer_messages").focus();
            $("#prayer_messages_error").show();
            return false;
        }
    });
    $('.show_hide').click(function () {
        var ID = $(this).attr('id');
        $('#commentlist_' + ID).slideToggle('slow');
    });
    $('.cancelcomment').click(function () {
        var id = $(this).attr('id');
        $('#reply_' + id).hide();
    });
    $('.pray-replay').click(function () {
        var id = $(this).attr('id');
        $('#reply_' + id).show();
    });
    $(document).on("click", '.comment-reply-link', function (e) {
        var commentlist = $(this).closest('.commentlist').attr('id');
        var prayer_id = commentlist.replace('commentlist_', "");
        $('#comment_post_ID').val(prayer_id);
    });
    $(document).on("click", '.commentlist #submit', function (event) {
        var logged_in = $('#logged_in').val();
        event.preventDefault();
        //$(this).closest('.form-submit').html('Wait...');
        var form = $(this).closest('form').attr('id');
        var url = $('#current-page').val();
        var prayer_id = $(this).closest(".comment-form").find("#comment_post_ID").val();
        var comment_parent = $(this).closest(".comment-form").find("#comment_parent").val();
        var pray_reply = $(this).closest(".comment-form").find("#comment").val();
        var comment_author = $(this).closest(".comment-form").find("#author").val();
        var comment_author_email = $(this).closest(".comment-form").find("#email").val();
        var comment_author_url = $(this).closest(".comment-form").find("#url").val();
        var ajax_url = $("#admin-ajax").val();
        $.ajax({
            url: ajax_url,
            type: 'post',
            data: {
                action: 'ajax_pray_response',
                prayer_id: prayer_id,
                comment_parent: comment_parent,
                pray_reply: pray_reply,
                comment_author: comment_author,
                comment_author_email: comment_author_email,
                comment_author_url: comment_author_url,
            },
            success: function (response) {
                $('#commentlist_' + prayer_id).slideToggle('slow');
                window.location.href = url + '/#commentlist_' + prayer_id;
            }
        });
    });
    $(document).on("click", '.prayresponse', function () {
        //$(this).val('Wait..');
        var url = $('#current_url').val();
        var prayer_id = $(this).attr('id');
        var pray_reply = $("#pray_reply_" + prayer_id).val();
        var comment_author = $("#author_" + prayer_id).val();
        var comment_author_email = $("#email_" + prayer_id).val();
        var comment_author_url = $("#url_" + prayer_id).val();
        if (pray_reply == '') {
            $("#pray_reply_" + prayer_id).focus();
            return false;
        }
        if (comment_author == '') {
            $("#author_" + prayer_id).focus();
            return false;
        }
        if (comment_author_email == '') {
            $("#email_" + prayer_id).focus();
            return false;
        }
        var comment_parent = 0;
        var ajax_url = $("#admin-ajax").val();
        $.ajax({
            url: ajax_url,
            type: 'post',
            data: {
                action: 'ajax_pray_response',
                prayer_id: prayer_id,
                comment_parent: comment_parent,
                pray_reply: pray_reply,
                comment_author: comment_author,
                comment_author_email: comment_author_email,
                comment_author_url: comment_author_url
            },
            success: function (response) {
                $('#commentlist_' + prayer_id).slideToggle('slow');
                window.location.href = url + '/#commentlist_' + prayer_id;
            }
        });
    });
    $(document).on("click", '.prayresponsreply', function () {
        var url = $("#current_url").val();
        var prayer_id = $(this).attr('id');
        var pray_reply = $("#pray_reply_" + prayer_id).val();
        var comment_author = $("#author_" + prayer_id).val();
        var comment_author_email = $("#email_" + prayer_id).val();
        var comment_author_url = $("#url_" + prayer_id).val();
        var comment_post_ID = $("#comment_post_ID_" + prayer_id).val();
        var comment_parent = $("#comment_parent_" + prayer_id).val();
        if (pray_reply == '') {
            $("#pray_reply_" + prayer_id).focus();
            return false;
        }
        if (comment_author == '') {
            $("#author_" + prayer_id).focus();
            return false;
        }
        if (comment_author_email == '') {
            $("#email_" + prayer_id).focus();
            return false;
        }
        //$(this).val('Wait..');
        var ajax_url = $("#admin-ajax").val();
        $.ajax({
            url: ajax_url,
            type: 'post',
            data: {
                action: 'ajax_pray_response',
                prayer_id: comment_post_ID,
                comment_parent: comment_parent,
                pray_reply: pray_reply,
                comment_author: comment_author,
                comment_author_email: comment_author_email,
                comment_author_url: comment_author_url
            },
            success: function (response) {
                $('#commentlist_' + comment_post_ID).slideToggle('slow');
                window.location.href = url + '/#commentlist_' + comment_post_ID;
            }
        });
    });
});

function refreshCaptcha() {
    var img = document.images['captchaimg'];
    img.src = img.src.substring(0, img.src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000;
}

function do_pray(pray_id, time_interval, user_id) {
    var ajax_url = jQuery("#admin-ajax").val();
    jQuery.ajax({
        url: ajax_url,
        type: 'post',
        data: {
            action: 'ajax_do_pray',
            prayer_id: pray_id
        },
        beforeSend: function () {
            jQuery('#do_pray_' + pray_id).attr('disabled', 'disabled');
            jQuery('#do_pray_' + pray_id).addClass('prayed');
        },
        success: function (data) {
            //if(data=="Prayed" || data=="Orado"|| data=="Prier"){
            var count = jQuery('#prayer_count_' + pray_id).html();
            count = parseInt(count) + 1;
            jQuery('#do_pray_' + pray_id).val(data);
            jQuery('#prayer_count_' + pray_id).html(count);
            //jQuery('#do_pray_'+pray_id).removeAttr('onclick');
            jQuery('#do_pray_' + pray_id).attr('disabled');
            time_interval = time_interval * 1000;
            /*if(user_id==0){
                setTimeout(function(){
                    jQuery('#do_pray_'+pray_id).attr('onclick');
                    if(data=="Prayed"){
                        jQuery('#do_pray_'+pray_id).val('Pray');
                    } else {
                        jQuery('#do_pray_'+pray_id).val('Orar');
                    }
                },time_interval);
            }*/
            setTimeout(function () {
                jQuery('#do_pray_' + pray_id).removeAttr('disabled');
                if (data == "Prayed") {
                    jQuery('#do_pray_' + pray_id).val('Pray');
                }
                if (data == "Orado") {
                    jQuery('#do_pray_' + pray_id).val('Orar');
                }
                if (data == "Prié") {
                    jQuery('#do_pray_' + pray_id).val('Prier');
                }
                if (data == "Gebeden") {
                    jQuery('#do_pray_' + pray_id).val('Bidden');
                }
                if (data == "Rukoiltu") {
                    jQuery('#do_pray_' + pray_id).val('Rukoile');
                }
                if (data == "Elmondva") {
                    jQuery('#do_pray_' + pray_id).val('Ima');
                }
                if (data == "Zmówiono") {
                    jQuery('#do_pray_' + pray_id).val('Pomódl się');
                }
                if (data == "祈祷") {
                    jQuery('#do_pray_' + pray_id).val('祈祷');
                }
                if (data == "祈禱") {
                    jQuery('#do_pray_' + pray_id).val('祈禱');
                }
                // else {
                //	jQuery('#do_pray_'+pray_id).val('Orar');
                //}
            }, time_interval);
            //}
        }
    });
}

jQuery(document).ready(function () {
    var is_class = jQuery("#form_gc").find("div").hasClass("alert-success");

    if (is_class == true) {
        jQuery(".form-group").hide();
    }

    window.onpageshow = function (event) {
        if (event.persisted || performance.getEntriesByType("navigation")[0].type === 'back_forward') {
            location.reload();
        }
    };
});