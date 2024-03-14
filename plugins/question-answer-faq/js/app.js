jQuery(document).ready(function($) {

    $('#form-mideal-faq').submit(function() {
        return false;
    });

    function midealValidateEmail(email) {
        var reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return reg.test(email);
    }

    $("#form-mideal-faq input").focus(function() {
        $(this).parent().removeClass("has-error");
    });
    $("#form-mideal-faq textarea").focus(function() {
        $(this).parent().removeClass("has-error");
    });


    //----------------add Question-----------------
    $(document).on('click', '#form-mideal-faq .sent-mideal-faq', function() {
        var mideal_faq_email_val = $("#form-mideal-faq input[name$='mideal_faq_email']").val()
        var mideal_faq_email_valid = midealValidateEmail(mideal_faq_email_val);
        if (mideal_faq_email_valid == false) {
            $("#form-mideal-faq input[name$='mideal_faq_email']").parent().addClass("has-error");
        } else if (mideal_faq_email_valid == true) {
            $("#form-mideal-faq input[name$='mideal_faq_email']").parent().removeClass("has-error");
        }

        var mideal_faq_name_val = $("#form-mideal-faq input[name$='mideal_faq_name']").val();
        var mideal_faq_nam_len = mideal_faq_name_val.length;
        if (mideal_faq_nam_len < 3) {
            $("#form-mideal-faq input[name$='mideal_faq_name']").parent().addClass("has-error");
        } else {
            $("#form-mideal-faq input[name$='mideal_faq_name']").parent().removeClass("has-error");
        }

        var question_val = $("#form-mideal-faq textarea[name$='mideal_faq_question']").val();
        var question_len = question_val.length;
        if (question_len < 3) {
            $("#form-mideal-faq textarea[name$='mideal_faq_question']").parent().addClass("has-error");
        } else {
            $("#form-mideal-faq textarea[name$='mideal_faq_question']").parent().removeClass("has-error");
        }

        if (mideal_faq_email_valid == true & question_len > 2 & mideal_faq_nam_len > 2) {
            var sentdata = "action=mideal_faq_add&nonce=" + midealfaqajax.nonce + "&" + $("#form-mideal-faq").serialize();
            $.ajax({
                type: "POST",
                url: midealfaqajax.url,
                dataType: "html",
                data: sentdata,
                beforeSend: function() {
                    $(this).attr('disabled', true);
                },
                error: function() {
                    $(this).attr('disabled', false);
                    $("#form-mideal-faq .message-error-sent").html(mideal_faq_l10n.errorajax);
                },
                success: function(result) {
                    if(result=="norecaptcha") {
                        $("#form-mideal-faq .message-error-sent").html(mideal_faq_l10n.nogooglecapcha);
                    } else {
                        $("#form-mideal-faq").html(mideal_faq_l10n.okajax);
                    }
                }
            });
        }
    });

    //----------------Delete Question-----------------
    $(document).on('click', '#mideal-faq-list .mideal-faq-delete-post', function(e) {
        event.preventDefault();
        var ID = $(this).attr('data-id');
        var sentdata = "action=mideal_faq_delete&nonce=" + midealfaqajax.nonce + "&ID=" + ID;
        $.ajax({
            type: "POST",
            url: midealfaqajax.url,
            dataType: "html",
            data: sentdata,
            beforeSend: function() {
            },
            error: function() {
                alert(mideal_faq_l10n.errorajax);
            },
            success: function(result) {
                $("#mideal-faq-list li.media-list-item[data-id='" + ID + "']").remove();
            }
        });
    });

    //----------------Publish question-----------------
    $(document).on('click', '#mideal-faq-list .mideal-faq-publish-post', function(event) {
        event.preventDefault();
        var linc = $(this);
        var ID = $(this).attr('data-id');
        var status = $(this).attr('data-status');
        var sentdata = "action=mideal_faq_publish&nonce=" + midealfaqajax.nonce + "&ID=" + ID + "&post_status=" + status;
        $.ajax({
            type: "POST",
            url: midealfaqajax.url,
            dataType: "html",
            data: sentdata,
            beforeSend: function() {},
            error: function() {
                alert(mideal_faq_l10n.errorajax);
            },
            success: function(result) {
                if (status != 'publish') {
                    $(linc).html(mideal_faq_l10n.unpublish);
                    $(linc).attr('data-status', 'publish');
                    $("#mideal-faq-list li.media-list-item[data-id='" + ID + "']").removeClass('no-published');
                } else {
                    $(linc).html(mideal_faq_l10n.publish);
                    $(linc).attr('data-status', 'pending');
                    $("#mideal-faq-list li.media-list-item[data-id='" + ID + "']").addClass('no-published');
                }
            }
        });
    });


    //----------------reply btn-----------------
    $(document).on('click', '.mideal-answer-reply', function(event) {
        var insertreply = "<div class='faq-answer'><div class='faq-header'>"+mideal_faq_l10n.nameanswer+"</div><div class='clearfix'></div><img class='media-object chat-avatar' src='"+mideal_faq_l10n.imageanswer+"' alt='avatar'><div class='chat-text' contenteditable='true' style='border-color:"+mideal_faq_l10n.backgroundanswer+";background:"+mideal_faq_l10n.backgroundanswer+";color:"+mideal_faq_l10n.coloranswer+";'> </div></div>";
        event.preventDefault();
        var ID = $(this).attr('data-id');
        $("#mideal-faq-list li.media-list-item[data-id='" + ID + "'] .faq-question").append(insertreply);
        $("#mideal-faq-list li.media-list-item[data-id='" + ID + "'] .faq-answer .chat-text").focus();
        $(this).removeClass('mideal-answer-reply');
        $(this).html(mideal_faq_l10n.save);
        $(this).addClass('mideal-answer-save');
    });


    //----------------save btn-----------------
    $(document).on('click', '.mideal-answer-save', function(event) {
        event.preventDefault();
        var element = $(this);
        var ID = $(this).attr('data-id');
        var mideal_faq_answer = $("#mideal-faq-list li.media-list-item[data-id='" + ID + "'] .faq-answer .chat-text").html();
        var sentdata = "action=mideal_faq_save&nonce=" + midealfaqajax.nonce + "&ID=" + ID + "&mideal_faq_answer=" + mideal_faq_answer;
        $.ajax({
            type: "POST",
            url: midealfaqajax.url,
            dataType: "html",
            data: sentdata,
            beforeSend: function() {
            },
            error: function() {
                alert(mideal_faq_l10n.errorajax);
            },
            success: function(result) {
                console.log(result);
                $("#mideal-faq-list li.media-list-item[data-id='" + ID + "'] .faq-answer .chat-text").attr('contenteditable','false');
                $(element).removeClass('mideal-answer-save');
                $(element).addClass('mideal-answer-edit');
                $(element).html(mideal_faq_l10n.edit);
            }
        });


    });

    //----------------edit btn-----------------
    $(document).on('click', '.mideal-answer-edit', function(event) {
        event.preventDefault();
        var ID = $(this).attr('data-id');
        $(this).html(mideal_faq_l10n.save);
        $("#mideal-faq-list li.media-list-item[data-id='" + ID + "'] .faq-answer .chat-text").attr('contenteditable','true');
        $("#mideal-faq-list li.media-list-item[data-id='" + ID + "'] .faq-answer .chat-text").focus();
        $(this).removeClass('mideal-answer-edit');
        $(this).addClass('mideal-answer-save');
    });

});
