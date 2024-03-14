jQuery( document ).ready(function() {

    af2_builder_object.af2_save_object.cftitle = af2_builder_object.af2_save_object.cftitle != null ? af2_builder_object.af2_save_object.cftitle : '';
    af2_builder_object.af2_save_object.description = af2_builder_object.af2_save_object.description != null ? af2_builder_object.af2_save_object.description : '';
    af2_builder_object.af2_save_object.name = af2_builder_object.af2_save_object.name != null ? af2_builder_object.af2_save_object.name : '';
    af2_builder_object.af2_save_object.send_button = af2_builder_object.af2_save_object.send_button != null ? af2_builder_object.af2_save_object.send_button : af2_kontaktformularbuilder_settings_object.strings.send_form;

    af2_builder_object.af2_save_object.mailfrom_name = af2_builder_object.af2_save_object.mailfrom_name != null ? af2_builder_object.af2_save_object.mailfrom_name : af2_kontaktformularbuilder_object.page_title;
    af2_builder_object.af2_save_object.mailfrom_name = af2_builder_object.af2_save_object.mailfrom_name != null ? af2_builder_object.af2_save_object.mailfrom_name : '';
    af2_builder_object.af2_save_object.mailfrom = af2_builder_object.af2_save_object.mailfrom != null ? af2_builder_object.af2_save_object.mailfrom : af2_kontaktformularbuilder_settings_object.wordpress_mail_url;
    af2_builder_object.af2_save_object.mailto = af2_builder_object.af2_save_object.mailto != null ? af2_builder_object.af2_save_object.mailto : '';
    af2_builder_object.af2_save_object.mailcc = af2_builder_object.af2_save_object.mailcc != null ? af2_builder_object.af2_save_object.mailcc : '';
    af2_builder_object.af2_save_object.mailbcc = af2_builder_object.af2_save_object.mailbcc != null ? af2_builder_object.af2_save_object.mailbcc : '';
    af2_builder_object.af2_save_object.mail_replyto = af2_builder_object.af2_save_object.mail_replyto != null ? af2_builder_object.af2_save_object.mail_replyto : '';
    af2_builder_object.af2_save_object.mailsubject = af2_builder_object.af2_save_object.mailsubject != null ? af2_builder_object.af2_save_object.mailsubject : af2_kontaktformularbuilder_settings_object.strings.subject;

    af2_builder_object.af2_save_object.show_bottombar = af2_builder_object.af2_save_object.show_bottombar != null ? af2_builder_object.af2_save_object.show_bottombar : true;
    af2_builder_object.af2_save_object.use_autorespond = af2_builder_object.af2_save_object.use_autorespond != null ? af2_builder_object.af2_save_object.use_autorespond : false;
    af2_builder_object.af2_save_object.use_smtp = af2_builder_object.af2_save_object.use_smtp != null ? af2_builder_object.af2_save_object.use_smtp : false;
    af2_builder_object.af2_save_object.use_wp_mail = af2_builder_object.af2_save_object.use_wp_mail != null ? af2_builder_object.af2_save_object.use_wp_mail : false;
    af2_builder_object.af2_save_object.tracking_code = af2_builder_object.af2_save_object.tracking_code != null ? af2_builder_object.af2_save_object.tracking_code : '';
    af2_builder_object.af2_save_object.autoresponder_field = af2_builder_object.af2_save_object.autoresponder_field != null ? af2_builder_object.af2_save_object.autoresponder_field : '';
    af2_builder_object.af2_save_object.autoresponder_subject = af2_builder_object.af2_save_object.autoresponder_subject != null ? af2_builder_object.af2_save_object.autoresponder_subject : '';
    af2_builder_object.af2_save_object.autoresponder_nachricht = af2_builder_object.af2_save_object.autoresponder_nachricht != null ? af2_builder_object.af2_save_object.autoresponder_nachricht : '';
    af2_builder_object.af2_save_object.smtp_host = af2_builder_object.af2_save_object.smtp_host != null ? af2_builder_object.af2_save_object.smtp_host : '';
    af2_builder_object.af2_save_object.smtp_username = af2_builder_object.af2_save_object.smtp_username != null ? af2_builder_object.af2_save_object.smtp_username : '';
    af2_builder_object.af2_save_object.smtp_password = af2_builder_object.af2_save_object.smtp_password != null ? af2_builder_object.af2_save_object.smtp_password : '';
    af2_builder_object.af2_save_object.smtp_port = af2_builder_object.af2_save_object.smtp_port != null ? af2_builder_object.af2_save_object.smtp_port : '';
    af2_builder_object.af2_save_object.smtp_type = af2_builder_object.af2_save_object.smtp_type != null ? af2_builder_object.af2_save_object.smtp_type : 'ssl';
    af2_builder_object.af2_save_object.attachment_url = af2_builder_object.af2_save_object.attachment_url != null ? af2_builder_object.af2_save_object.attachment_url : null;
    af2_builder_object.af2_save_object.redirect_params = af2_builder_object.af2_save_object.redirect_params != null ? af2_builder_object.af2_save_object.redirect_params : [];

    af2_builder_object.af2_save_object.smsSender = af2_builder_object.af2_save_object.smsSender != null ? af2_builder_object.af2_save_object.smsSender : '';
    af2_builder_object.af2_save_object.smsText = af2_builder_object.af2_save_object.smsText != null ? af2_builder_object.af2_save_object.smsText : '';

    const questions = af2_builder_object.af2_save_object.questions;
    af2_builder_object.af2_save_object.questions = questions != null && questions != [] ? questions : [
        {typ: 'text_type_name', icon: 'fas fa-user', label: '', placeholder: af2_kontaktformularbuilder_settings_object.strings.name_placeholder, required: true, id: af2_kontaktformularbuilder_settings_object.strings.name},
        {typ: 'text_type_mail', icon: 'fas fa-envelope', label: '', placeholder: af2_kontaktformularbuilder_settings_object.strings.mail_placeholder, required: true, id: af2_kontaktformularbuilder_settings_object.strings.mail, b2bMailValidation: false},
        {typ: 'text_type_phone', icon: 'fas fa-phone', label: '', required: true, id: af2_kontaktformularbuilder_settings_object.strings.telefon},
        {typ: 'checkbox_type', text: af2_kontaktformularbuilder_settings_object.strings.checkbox_text, required: true, id: af2_kontaktformularbuilder_settings_object.strings.checkbox}
    ];


    if( af2_builder_object.af2_save_object.mailtext != null && af2_builder_object.af2_save_object.mailtext.trim() != '' ) {}
    else {
        let text = '';
        af2_builder_object.af2_save_object.questions.forEach(element => {
            text += '['+element.id+']\n\n';
        });
        text += af2_kontaktformularbuilder_settings_object.strings.answers_tag + '\n\n';
        af2_builder_object.af2_save_object.mailtext = text;
    } 

    jQuery('#af2_goto_kontaktformularbuilder').on('click', _ => {
        af2_save_builder( _ => {
            window.location.href = af2_kontaktformularbuilder_settings_object.redirect_kontaktformularbuilder_url;
        });
    });

    const af2_load_placeholder = () => {
        const analytics_tags = af2_get_analytics_tags();
        const answer_tag = af2_get_answer_tag();
        const questions_tags = af2_get_questions_tags();
        const email_tags = af2_get_email_tags();
        const code_tags = af2_get_code_tag();

        let replytoSpan = '';
        replytoSpan += '<span>';
        replytoSpan += af2_add_tags(email_tags);
        replytoSpan += '</span>';

        let subjectSpan = '';
        subjectSpan += '<span>';
        subjectSpan += af2_add_tags(questions_tags);
        subjectSpan += '</span>';

        let textSpan = '';
        textSpan += '<span>';
        textSpan += af2_add_tags(af2_get_id());
        textSpan += af2_add_tags(answer_tag);
        textSpan += af2_add_tags(questions_tags);
        textSpan += af2_add_tags(analytics_tags);
        textSpan += '</span>';

        jQuery('#af2_mailreplyto').append(replytoSpan);
        jQuery('#af2_mailsubject').append(subjectSpan);
        jQuery('#af2_mailtext').append(textSpan);


        let smstags = '';
        smstags += '<span>';
        smstags += af2_add_tags(code_tags);
        smstags += '</span>';
        

        jQuery('#af2_sms_message').append(smstags);


        let contactformRedirectParams = '';
        contactformRedirectParams += '<span>';
        contactformRedirectParams += af2_add_tags(answer_tag);
        contactformRedirectParams += af2_add_tags(questions_tags);
        contactformRedirectParams += '</span>';
        jQuery('#af2_contactform_redirect_params').append(contactformRedirectParams);
    }

    const af2_add_tags = (tags) => {
        let str = '<br>';
        tags.forEach(tag => {
            str += tag + ' ';
        });

        return str;
    }

    const af2_get_id = () => {
        return [
            af2_kontaktformularbuilder_settings_object.strings.id_tag
        ]
    }

    const af2_get_analytics_tags = () => {
        return [
            af2_kontaktformularbuilder_settings_object.strings.querystring_tag,
            af2_kontaktformularbuilder_settings_object.strings.url_tag
        ]
    }

    const af2_get_answer_tag = () => {
        return [
            af2_kontaktformularbuilder_settings_object.strings.answers_tag
        ]
    }

    const af2_get_code_tag = () => {
        return [
            af2_kontaktformularbuilder_settings_object.strings.code_tag
        ]
    }

    const af2_get_questions_tags = () => {
        let question_ids = [];
        af2_builder_object.af2_save_object.questions.forEach(element => {
            question_ids.push('['+element.id+']');
        });

        return question_ids;
    }

    const af2_get_email_tags = () => {
        let email_ids = [];
        af2_builder_object.af2_save_object.questions.forEach(element => {
            if(element.typ == 'text_type_mail') email_ids.push('['+element.id+']');
        });

        return email_ids;
    }

    af2_load_placeholder();



    jQuery(document).on('click', '#af2_send_test_message', (ev) => {

        jQuery('#af2_send_test_message .loading').removeClass('af2_hide');

        jQuery.ajax({
            url: af2_kontaktformularbuilder_settings_object.ajax_url,
            type: "POST",
            data: {
                action: 'fnsf_af2_test_mail',
                nonce: af2_builder_object.nonce,
                use_wp_mail: af2_builder_object.af2_save_object.use_wp_mail,
                use_smtp: af2_builder_object.af2_save_object.use_smtp,
                host: af2_builder_object.af2_save_object.smtp_host,
                username: af2_builder_object.af2_save_object.smtp_username,
                password: af2_builder_object.af2_save_object.smtp_password,
                port: af2_builder_object.af2_save_object.smtp_port,
                from: af2_builder_object.af2_save_object.mailfrom,
                to: af2_builder_object.af2_save_object.mailto,
                type: af2_builder_object.af2_save_object.smtp_type,
                cc: af2_builder_object.af2_save_object.mailcc,
                bcc: af2_builder_object.af2_save_object.mailbcc,
                reply_to: af2_builder_object.af2_save_object.mail_replyto,
                from_name: af2_builder_object.af2_save_object.mailfrom_name
            },
            success: (cont) =>
            {
                jQuery('#af2_send_test_message .loading').addClass('af2_hide');
                af2_clear_toast('af2_toast_wrapper', _ => {
                    jQuery('#af2_testnachricht_modal .af2_modal_content').html('<p>'+cont+'</p>');

                    af2_create_toast('af2_toast_wrapper', af2_kontaktformularbuilder_settings_object.strings.testnachricht, 'af2_info', true, af2_open_modal, '#af2_testnachricht_modal');
                });
            },
            error: () =>
            {
                jQuery('#af2_send_test_message .loading').addClass('af2_hide');
                af2_clear_toast('af2_toast_wrapper', _ => {
                    af2_create_toast('af2_toast_wrapper', af2_builder_object.strings.support, 'af2_error');
                });
            }
        });
    });



    af2_load_object_data();
    af2_load_input_html_data();
});
