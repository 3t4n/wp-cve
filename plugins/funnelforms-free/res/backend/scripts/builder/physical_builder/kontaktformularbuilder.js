jQuery( document ).ready(function($) {

    af2_builder_object.af2_save_object.cftitle = af2_builder_object.af2_save_object.cftitle != null ? af2_builder_object.af2_save_object.cftitle : '';
    af2_builder_object.af2_save_object.description = af2_builder_object.af2_save_object.description != null ? af2_builder_object.af2_save_object.description : '';
    af2_builder_object.af2_save_object.name = af2_builder_object.af2_save_object.name != null ? af2_builder_object.af2_save_object.name : '';
    af2_builder_object.af2_save_object.send_button = af2_builder_object.af2_save_object.send_button != null ? af2_builder_object.af2_save_object.send_button : af2_kontaktformularbuilder_object.strings.send_form;

    af2_builder_object.af2_save_object.mailfrom_name = af2_builder_object.af2_save_object.mailfrom_name != null ? af2_builder_object.af2_save_object.mailfrom_name : af2_kontaktformularbuilder_object.page_title;
    af2_builder_object.af2_save_object.mailfrom = af2_builder_object.af2_save_object.mailfrom != null ? af2_builder_object.af2_save_object.mailfrom : af2_kontaktformularbuilder_object.wordpress_mail_url;
    af2_builder_object.af2_save_object.mailto = af2_builder_object.af2_save_object.mailto != null ? af2_builder_object.af2_save_object.mailto : '';
    af2_builder_object.af2_save_object.mailcc = af2_builder_object.af2_save_object.mailcc != null ? af2_builder_object.af2_save_object.mailcc : '';
    af2_builder_object.af2_save_object.mailbcc = af2_builder_object.af2_save_object.mailbcc != null ? af2_builder_object.af2_save_object.mailbcc : '';
    af2_builder_object.af2_save_object.mail_replyto = af2_builder_object.af2_save_object.mail_replyto != null ? af2_builder_object.af2_save_object.mail_replyto : '';
    af2_builder_object.af2_save_object.mailsubject = af2_builder_object.af2_save_object.mailsubject != null ? af2_builder_object.af2_save_object.mailsubject : af2_kontaktformularbuilder_object.strings.subject;
    
    af2_builder_object.af2_save_object.mailtext = af2_builder_object.af2_save_object.mailtext != null ? af2_builder_object.af2_save_object.mailtext : null;
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
        {typ: 'text_type_name', icon: 'fas fa-user', label: '', placeholder: af2_kontaktformularbuilder_object.strings.name_placeholder, required: true, id: af2_kontaktformularbuilder_object.strings.name},
        {typ: 'text_type_mail', icon: 'fas fa-envelope', label: '', placeholder: af2_kontaktformularbuilder_object.strings.mail_placeholder, required: true, id: af2_kontaktformularbuilder_object.strings.mail, b2bMailValidation: false},
        {typ: 'text_type_phone', icon: 'fas fa-phone', label: '', required: true, id: af2_kontaktformularbuilder_object.strings.telefon},
        {typ: 'checkbox_type', text: af2_kontaktformularbuilder_object.strings.checkbox_text, required: true, id: af2_kontaktformularbuilder_object.strings.checkbox}
    ];


    const af2_add_element_builder_manipulation = (type, position) => {
        let obj = {};
        obj.typ = type;

        switch(type) {
            case 'salutation_type': {
                obj.label = '';
                obj.required = false;
                obj.id = af2KontaktformularFindId(af2_kontaktformularbuilder_object.strings.anrede, 0);
                obj.allowSalutationMale = true;
                obj.allowSalutationFemale = true;
                obj.allowSalutationDivers = true;
                obj.allowSalutationCompany = true;
                break;
            }
            case 'text_type_name': {
                obj.label = '';
                obj.placeholder = af2_kontaktformularbuilder_object.strings.name_placeholder;
                obj.required = false;
                obj.icon = 'fas fa-user';
                obj.id = af2KontaktformularFindId(af2_kontaktformularbuilder_object.strings.name, 0);
                break;
            }
            case 'text_type_mail': { 
                obj.label = '';
                obj.placeholder = af2_kontaktformularbuilder_object.strings.mail_placeholder;
                obj.required = false;
                obj.icon = 'fas fa-envelope';
                obj.id = af2KontaktformularFindId(af2_kontaktformularbuilder_object.strings.mail, 0);
                break;
            }
            case 'text_type_phone': { 
                obj.label = '';
                obj.placeholder = af2_kontaktformularbuilder_object.strings.phone_placeholder;
                obj.required = false;
                obj.icon = 'fas fa-phone';
                obj.id = af2KontaktformularFindId(af2_kontaktformularbuilder_object.strings.telefon, 0);
                break;
            }
            case 'text_type_phone_verification': { 
                obj.label = '';
                obj.placeholder = af2_kontaktformularbuilder_object.strings.mobile_placeholder;
                obj.required = false;
                obj.icon = 'fas fa-phone';
                obj.id = af2KontaktformularFindId(af2_kontaktformularbuilder_object.strings.mobile, 0);
                break;
            }
            case 'text_type_plain': {
                obj.label = '';
                obj.placeholder = af2_kontaktformularbuilder_object.strings.text_placeholder;
                obj.required = false;
                obj.icon = null;
                obj.id = af2KontaktformularFindId(af2_kontaktformularbuilder_object.strings.text, 0);
                break;
            }
            case 'checkbox_type': { 
                obj.text = '';
                obj.required = true;
                obj.id = af2KontaktformularFindId(af2_kontaktformularbuilder_object.strings.checkbox, 0);
                break;
            }
            case 'text_type_phone_verification': { 
                break;
            }
        }

        af2_builder_object.af2_save_object.questions.splice(position, 0, obj);
    }


    const af2KontaktformularBuildQuestionPresets = () => {
        let presets = '';
        af2_builder_object.af2_save_object.questions.forEach((element, index) => {
            presets += af2KontaktformularBuildQuestionPreset(index);
        });

        return presets;
    }

    const af2KontaktformularBuildQuestionPreset = (editcontentarrayid) => {
        let preset = '';

        preset += '<div class="af2_question_wrapper af2_builder_editable_object af2_builder_deletable_object af2_array_draggable" data-deletetriggerid="af2_questions_container" data-saveobjectid="questions" data-editcontentid="question" data-editcontentarrayid="'+editcontentarrayid+'">';
            preset += '<span class="af2_delete_object"><i class="fas fa-trash"></i></span>';

        const type = af2_builder_object.af2_save_object['questions'][editcontentarrayid].typ;
        switch(type) {
            case 'salutation_type': {
                preset += '<div id="af2_question_label" class="af2_question_label" data-editcontentarrayid="'+editcontentarrayid+'"></div>';
                preset += '<div class="af2_question_salutation_wrapper">';
                    preset += '<div id="af2_salutation_male" class="af2_question_salutation_radio" data-editcontentarrayid="'+editcontentarrayid+'">';
                        preset += '<input type="radio" disabled>';
                        preset += '<label>'+af2_kontaktformularbuilder_object.strings.herr+'</label>';
                    preset += '</div>';
                    preset += '<div id="af2_salutation_female" class="af2_question_salutation_radio" data-editcontentarrayid="'+editcontentarrayid+'">';
                        preset += '<input type="radio" disabled>';
                        preset += '<label>'+af2_kontaktformularbuilder_object.strings.frau+'</label>';
                    preset += '</div>';
                    preset += '<div id="af2_salutation_divers" class="af2_question_salutation_radio" data-editcontentarrayid="'+editcontentarrayid+'">';
                        preset += '<input type="radio" disabled>';
                        preset += '<label>'+af2_kontaktformularbuilder_object.strings.divers+'</label>';
                    preset += '</div>';
                    preset += '<div id="af2_salutation_company" class="af2_question_salutation_radio" data-editcontentarrayid="'+editcontentarrayid+'">';
                        preset += '<input type="radio" disabled>';
                        preset += '<label>'+af2_kontaktformularbuilder_object.strings.firma+'</label>';
                    preset += '</div>';
                preset += '</div>';
                break;
            }
            case 'text_type_name': {
                // Fallthrough
            }
            case 'text_type_mail': { 
                // Fallthrough
            }
            case 'text_type_phone': { 
                // Fallthrough
            }
            case 'text_type_phone_verification': { 
                // Fallthrough
            }
            case 'text_type_plain': { 
                preset += '<div id="af2_question_label" class="af2_question_label" data-editcontentarrayid="'+editcontentarrayid+'"></div>';
                preset += '<div class="af2_question_input_wrapper">';
                preset += '<div id="af2_question_input_icon" class="af2_question_input_icon" data-editcontentarrayid="'+editcontentarrayid+'"></div>';
                preset += '<input type="text" id="af2_question_input_field" class="af2_question_input_field" data-editcontentarrayid="'+editcontentarrayid+'" disabled></input>';
                preset += '<div class="af2_savediv"></div>'
                preset += '</div>'
                break;    
            }
            case 'checkbox_type': {
                preset += '<div class="af2_question_checkbox_wrapper">'; 
                preset += '<input type="checkbox" id="af2_question_checkbox_'+editcontentarrayid+'" class="af2_question_checkbox_field" disabled>';
                preset += '<label id="af2_question_checkbox_text_field" class="af2_question_checkbox_text_field" for="af2_question_checkbox_'+editcontentarrayid+'" data-editcontentarrayid="'+editcontentarrayid+'"></label>';
                preset += '</div>';
                break;
            }
            case 'google_recaptcha': { 
                preset += '<div class="af2_question_recaptcha_wrapper">'; 
                preset += af2_kontaktformularbuilder_object.strings.error_google;
                preset += '</div>';
                break;
            }
            case 'html_content': { 
                preset += af2_kontaktformularbuilder_object.strings.error_html;
                break;
            }
        }

        preset += '</div>';

        return preset;
    }

    // On Delete function
    jQuery(document).on('af2_deleted_deleteable_object', '.af2_questions_container', _ => {
        jQuery('.af2_questions_container').find('.af2_question_wrapper').not('.af2_dragging').remove();
        jQuery('.af2_questions_container').prepend(af2KontaktformularBuildQuestionPresets());
        const dataHandler = af2_builder_object.sidebar_elements.find(element => element.editContentId == 'question');
        dataHandler.fields.forEach(field => af2_load_field_html_data(dataHandler, field));
    });

    // On Add Drag function
    jQuery(document).on('af2_draggin_add_dragable_object', '.af2_questions_container', ev => {
        const handlerElement = ev.handlerElement;

        jQuery(handlerElement).attr('data-saveobjectid', 'questions');
        jQuery(handlerElement).attr('data-deletetriggerid', 'af2_questions_container');

        const selContainer = jQuery('.af2_questions_container');
        const selElement = jQuery('.af2_questions_container').find('.af2_question_wrapper').not('.af2_dragging');
        af2_create_array_dropzones_in(selContainer, selElement, 'af2_question_dropzone');
    });

    // On Drag function
    jQuery(document).on('af2_draggin_dragable_object', '.af2_questions_container', ev => {
        jQuery('.af2_questions_container').find('.af2_question_wrapper').not('.af2_dragging').remove();
        jQuery('.af2_questions_container').prepend(af2KontaktformularBuildQuestionPresets());
        const dataHandler = af2_builder_object.sidebar_elements.find(element => element.editContentId == 'question');
        dataHandler.fields.forEach(field => af2_load_field_html_data(dataHandler, field));

        const selContainer = jQuery('.af2_questions_container');
        const selElement = jQuery('.af2_questions_container').find('.af2_question_wrapper').not('.af2_dragging');
        af2_create_array_dropzones_in(selContainer, selElement, 'af2_question_dropzone');
    });

     // On Drop function
     jQuery(document).on('af2_dropped_dragable_object', '.af2_questions_container', ev => {
        jQuery('.af2_questions_container').find('.af2_question_wrapper').remove();
        jQuery('.af2_questions_container').find('.af2_array_dropzone_in').remove();

        if(jQuery(ev.addElement).hasClass('af2_array_add_draggable')) af2_add_element_builder_manipulation(jQuery(ev.addElement).data('elementid'), ev.position);

        jQuery('.af2_questions_container').prepend(af2KontaktformularBuildQuestionPresets());
        const dataHandler = af2_builder_object.sidebar_elements.find(element => element.editContentId == 'question');
        dataHandler.fields.forEach(field => af2_load_field_html_data(dataHandler, field));
    });

    const af2KontaktformularFindId = (id, iteration) => {
        const iterationstr = iteration > 0 ? iteration.toString() : '';
        if(af2_builder_object.af2_save_object.questions == null) return id;
        
        let val = id+iterationstr;
        af2_builder_object.af2_save_object.questions.forEach(element => {
            if(element.id == id+iterationstr) val = af2KontaktformularFindId(id, iteration+1);
        });
        return val;
    }

    // Leere laden!!!
    jQuery('.af2_questions_container').prepend(af2KontaktformularBuildQuestionPresets());
    af2_load_object_data();
    af2_load_input_html_data();


    jQuery('#af2_goto_kontaktformularbuilder_settings').on('click', _ => {
        af2_save_builder( _ => {
            window.location.href = af2_kontaktformularbuilder_object.redirect_kontaktformularbuilder_settings_url;
        });
    });

    jQuery(document).on('set_salutation', '.af2_question_salutation_radio', function(ev) {
        if(ev.value == true) jQuery(ev.currentTarget).removeClass('af2_hide');
        if(ev.value == false) jQuery(ev.currentTarget).addClass('af2_hide');
    });

    const set_required = (ev) => {
        const editContentArrayId = jQuery(ev.currentTarget).data('editcontentarrayid');
        const question = af2_builder_object.af2_save_object.questions[editContentArrayId];

        let selector = null;
        let attr = null;
        let value = '';

        let selector2 = null;
        let value2 = '';
        let attr2 = null;

        switch(question.typ) {
            case 'salutation_type': {
                if(question.label.trim() != '') selector = jQuery(ev.currentTarget).find('#af2_question_label');
                value = question.label;
                break;
            }
            case 'text_type_name': {
                // Fallthrough
            }
            case 'text_type_mail': { 
                // Fallthrough
            }
            case 'text_type_phone': { 
                // Fallthrough
            }
            case 'text_type_phone_verification': {
                // Fallthrough
            }
            case 'text_type_plain': {

                if(typeof question.placeholder == 'undefined') {
                    question.placeholder = " ";
                }

                value = question.label;
                let str = '#af2_question_label[data-editcontentarrayid="'+editContentArrayId+'"]';
                selector = jQuery(str).not('.af2_dragging '+str);

                str = '#af2_question_input_field[data-editcontentarrayid="'+editContentArrayId+'"]';
                selector2 = jQuery(str).not('.af2_dragging '+str);
                value2 = question.placeholder;
                attr2 = 'placeholder';
                if(question.label.trim() == '') {
                    value = question.placeholder;
                    str = '#af2_question_input_field[data-editcontentarrayid="'+editContentArrayId+'"]';
                    selector = jQuery(str).not('.af2_dragging '+str);
                    attr = 'placeholder';

                    value2 = question.label;
                    str = '#af2_question_label[data-editcontentarrayid="'+editContentArrayId+'"]';
                    selector2 = jQuery(str).not('.af2_dragging '+str);
                    attr2 = null;
                }
                break;    
            }
            case 'checkbox_type': {
                selector = jQuery(ev.currentTarget).find('#af2_question_checkbox_text_field');
                value = question.text;
                break;
            }
        }

        let required = af2_builder_object.af2_save_object.questions[editContentArrayId].required;

        if(question.typ == 'text_type_phone_verification') required = 'true';

        if(required == 'true') required = true;
        if(required == 'false') required = false;

        if(selector == null) return;

        if(required == true) value += ' *'
        if(required == false) value;

        if(attr == null) selector.html(value);
        else selector.attr(attr, value);


        if(selector2 == null) return;

        if(required == true) value2;
        if(required == false) value2;

        if(attr2 == null) selector2.html(value2);
        else selector2.attr(attr2, value2);
    }

    const set_icon = (ev) => {
        const editContentArrayId = jQuery(ev.currentTarget).data('editcontentarrayid');

        if(af2_builder_object.af2_save_object.questions[editContentArrayId].icon != null && af2_builder_object.af2_save_object.questions[editContentArrayId].icon != '') jQuery(ev.currentTarget).removeClass('af2_hide');
        else jQuery(ev.currentTarget).addClass('af2_hide');
    }

    jQuery(document).on('set_required', '#af2_question_label', ev => set_required(ev));
    jQuery(document).on('set_required', '.af2_question_wrapper', ev => set_required(ev));
    jQuery(document).on('set_required', '#af2_question_input_field', ev => set_required(ev));
    jQuery(document).on('set_required', '#af2_question_checkbox_text_field', ev => set_required(ev));

    jQuery(document).on('set_icon', '#af2_question_input_icon', ev => set_icon(ev));

    const set_question_sidebar_element = af2_builder_object.sidebar_elements.find(element => element.editContentId == 'question');
    const set_salutation_sidebar_field_herr = set_question_sidebar_element.fields.find(element => element.details.saveObjectIdField == 'allowSalutationMale');
    const set_salutation_sidebar_field_frau = set_question_sidebar_element.fields.find(element => element.details.saveObjectIdField == 'allowSalutationFemale');
    const set_salutation_sidebar_field_divers = set_question_sidebar_element.fields.find(element => element.details.saveObjectIdField == 'allowSalutationDivers');
    const set_salutation_sidebar_field_firma = set_question_sidebar_element.fields.find(element => element.details.saveObjectIdField == 'allowSalutationCompany');
    af2_load_field_html_data(set_question_sidebar_element, set_salutation_sidebar_field_herr);
    af2_load_field_html_data(set_question_sidebar_element, set_salutation_sidebar_field_frau);
    af2_load_field_html_data(set_question_sidebar_element, set_salutation_sidebar_field_divers);
    af2_load_field_html_data(set_question_sidebar_element, set_salutation_sidebar_field_firma);

    const set_required_field = set_question_sidebar_element.fields.find(element => element.details.saveObjectIdField == 'required');
    af2_load_field_html_data(set_question_sidebar_element, set_required_field);

    const set_icon_field = set_question_sidebar_element.fields.find(element => element.details.saveObjectIdField == 'icon');
    af2_load_field_html_data(set_question_sidebar_element, set_icon_field);
});
