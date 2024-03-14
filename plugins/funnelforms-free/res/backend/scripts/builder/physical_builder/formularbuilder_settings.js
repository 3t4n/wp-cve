jQuery( document ).ready(function() {

    af2_builder_object.af2_save_object.name = af2_builder_object.af2_save_object.name != null ? af2_builder_object.af2_save_object.name : '';
    af2_builder_object.af2_save_object.showLoading = af2_builder_object.af2_save_object.showLoading != null ? af2_builder_object.af2_save_object.showLoading : false;
    af2_builder_object.af2_save_object.rtl_layout = af2_builder_object.af2_save_object.rtl_layout != null ? af2_builder_object.af2_save_object.rtl_layout : false;
    af2_builder_object.af2_save_object.showFontAwesome = af2_builder_object.af2_save_object.showFontAwesome != null ? af2_builder_object.af2_save_object.showFontAwesome : true;
    af2_builder_object.af2_save_object.send_error_mail = af2_builder_object.af2_save_object.send_error_mail != null ? af2_builder_object.af2_save_object.send_error_mail : true;
    af2_builder_object.af2_save_object.activateScrollToAnchor = af2_builder_object.af2_save_object.activateScrollToAnchor != null ? af2_builder_object.af2_save_object.activateScrollToAnchor : false;

    af2_builder_object.af2_save_object.showSuccessScreen = af2_builder_object.af2_save_object.showSuccessScreen != null ? af2_builder_object.af2_save_object.showSuccessScreen : true;
    af2_builder_object.af2_save_object.success_text = af2_builder_object.af2_save_object.success_text != null ? af2_builder_object.af2_save_object.success_text : af2_formularbuilder_settings_object.strings.success_text;
    af2_builder_object.af2_save_object.success_image = af2_builder_object.af2_save_object.success_image != null ? af2_builder_object.af2_save_object.success_image : af2_formularbuilder_settings_object.standard_success_image;
    af2_builder_object.af2_save_object.styling = af2_builder_object.af2_save_object.styling != null ? af2_builder_object.af2_save_object.styling : {};
    
    
    af2_builder_object.af2_save_object.adjust_containersize = af2_builder_object.af2_save_object.adjust_containersize != null ? af2_builder_object.af2_save_object.adjust_containersize : false;
    
    af2_builder_object.af2_save_object.styling.fe_title = af2_builder_object.af2_save_object.styling.fe_title != null ? af2_builder_object.af2_save_object.styling.fe_title : '';
    af2_builder_object.af2_save_object.styling.global_main_color = af2_builder_object.af2_save_object.styling.global_main_color != null ? af2_builder_object.af2_save_object.styling.global_main_color : 'rgba(157,65,221,1)';
    af2_builder_object.af2_save_object.styling.global_main_background_color = af2_builder_object.af2_save_object.styling.global_main_background_color != null ? af2_builder_object.af2_save_object.styling.global_main_background_color : 'rgba(255,255,255,1)';
    af2_builder_object.af2_save_object.styling.global_prev_text = af2_builder_object.af2_save_object.styling.global_prev_text != null ? af2_builder_object.af2_save_object.styling.global_prev_text : null;
    af2_builder_object.af2_save_object.styling.global_next_text = af2_builder_object.af2_save_object.styling.global_next_text != null ? af2_builder_object.af2_save_object.styling.global_next_text : null;
    af2_builder_object.af2_save_object.styling.global_font = af2_builder_object.af2_save_object.styling.global_font != null ? af2_builder_object.af2_save_object.styling.global_font : 'Montserrat';

    af2_builder_object.af2_save_object.styling.form_heading_color = af2_builder_object.af2_save_object.styling.form_heading_color != null ? af2_builder_object.af2_save_object.styling.form_heading_color : 'rgba(157,65,221,1)';
    af2_builder_object.af2_save_object.styling.form_question_heading_color = af2_builder_object.af2_save_object.styling.form_question_heading_color != null ? af2_builder_object.af2_save_object.styling.form_question_heading_color : 'rgba(51,51,51,1)';
    af2_builder_object.af2_save_object.styling.form_question_description_color = af2_builder_object.af2_save_object.styling.form_question_description_color != null ? af2_builder_object.af2_save_object.styling.form_question_description_color : 'rgba(51,51,51,1)';
    af2_builder_object.af2_save_object.styling.form_answer_card_text_color = af2_builder_object.af2_save_object.styling.form_answer_card_text_color != null ? af2_builder_object.af2_save_object.styling.form_answer_card_text_color : 'rgba(51,51,51,1)';
    af2_builder_object.af2_save_object.styling.form_answer_card_icon_color = af2_builder_object.af2_save_object.styling.form_answer_card_icon_color != null ? af2_builder_object.af2_save_object.styling.form_answer_card_icon_color : 'rgba(157,65,221,1)';
    af2_builder_object.af2_save_object.styling.form_loader_color = af2_builder_object.af2_save_object.styling.form_loader_color != null ? af2_builder_object.af2_save_object.styling.form_loader_color : 'rgba(0,0,0,1)';
    af2_builder_object.af2_save_object.styling.form_background_color = af2_builder_object.af2_save_object.styling.form_background_color != null ? af2_builder_object.af2_save_object.styling.form_background_color : 'rgba(255,255,255,1)';
    af2_builder_object.af2_save_object.styling.form_answer_card_background_color = af2_builder_object.af2_save_object.styling.form_answer_card_background_color != null ? af2_builder_object.af2_save_object.styling.form_answer_card_background_color : 'rgba(255,255,255,1)';
    af2_builder_object.af2_save_object.styling.form_button_background_color = af2_builder_object.af2_save_object.styling.form_button_background_color != null ? af2_builder_object.af2_save_object.styling.form_button_background_color : 'rgba(157,65,221,1)';
    af2_builder_object.af2_save_object.styling.form_button_disabled_background_color = af2_builder_object.af2_save_object.styling.form_button_disabled_background_color != null ? af2_builder_object.af2_save_object.styling.form_button_disabled_background_color : 'rgba(225,225,225,1)';
    af2_builder_object.af2_save_object.styling.form_box_shadow_color = af2_builder_object.af2_save_object.styling.form_box_shadow_color != null ? af2_builder_object.af2_save_object.styling.form_box_shadow_color : 'rgba(157,65,221,1)';
    af2_builder_object.af2_save_object.styling.form_box_shadow_color_answer_card = af2_builder_object.af2_save_object.styling.form_box_shadow_color_answer_card != null ? af2_builder_object.af2_save_object.styling.form_box_shadow_color_answer_card : 'rgba(225,225,225,1)';
    af2_builder_object.af2_save_object.styling.form_box_shadow_color_unfocus = af2_builder_object.af2_save_object.styling.form_box_shadow_color_unfocus != null ? af2_builder_object.af2_save_object.styling.form_box_shadow_color_unfocus : 'rgba(225,225,225,1)';
    af2_builder_object.af2_save_object.styling.form_border_color = af2_builder_object.af2_save_object.styling.form_border_color != null ? af2_builder_object.af2_save_object.styling.form_border_color : 'rgba(157,65,221,1)';
    af2_builder_object.af2_save_object.styling.form_progress_bar_color = af2_builder_object.af2_save_object.styling.form_progress_bar_color != null ? af2_builder_object.af2_save_object.styling.form_progress_bar_color : 'rgba(157,65,221,1)';
    af2_builder_object.af2_save_object.styling.form_progress_bar_unfilled_background_color = af2_builder_object.af2_save_object.styling.form_progress_bar_unfilled_background_color != null ? af2_builder_object.af2_save_object.styling.form_progress_bar_unfilled_background_color : 'rgba(225,225,225,1)';
    af2_builder_object.af2_save_object.styling.form_slider_frage_bullet_color = af2_builder_object.af2_save_object.styling.form_slider_frage_bullet_color != null ? af2_builder_object.af2_save_object.styling.form_slider_frage_bullet_color : 'rgba(157,65,221,1)';
    af2_builder_object.af2_save_object.styling.form_slider_frage_thumb_background_color = af2_builder_object.af2_save_object.styling.form_slider_frage_thumb_background_color != null ? af2_builder_object.af2_save_object.styling.form_slider_frage_thumb_background_color : 'rgba(157,65,221,1)';
    af2_builder_object.af2_save_object.styling.form_slider_frage_background_color = af2_builder_object.af2_save_object.styling.form_slider_frage_background_color != null ? af2_builder_object.af2_save_object.styling.form_slider_frage_background_color : 'rgba(225,225,225,1)';
    af2_builder_object.af2_save_object.styling.form_input_background_color = af2_builder_object.af2_save_object.styling.form_input_background_color != null ? af2_builder_object.af2_save_object.styling.form_input_background_color : 'rgba(253,253,253,1)';
    af2_builder_object.af2_save_object.styling.form_datepicker_background_color = af2_builder_object.af2_save_object.styling.form_datepicker_background_color != null ? af2_builder_object.af2_save_object.styling.form_datepicker_background_color : 'rgba(157,65,221,1)';
    af2_builder_object.af2_save_object.styling.form_datepicker_color = af2_builder_object.af2_save_object.styling.form_datepicker_color != null ? af2_builder_object.af2_save_object.styling.form_datepicker_color : 'rgba(255,255,255,1)';
    
    af2_builder_object.af2_save_object.styling.form_heading_size_desktop = af2_builder_object.af2_save_object.styling.form_heading_size_desktop != null ? af2_builder_object.af2_save_object.styling.form_heading_size_desktop : '32';
    af2_builder_object.af2_save_object.styling.form_heading_size_mobile = af2_builder_object.af2_save_object.styling.form_heading_size_mobile != null ? af2_builder_object.af2_save_object.styling.form_heading_size_mobile : '20';
    af2_builder_object.af2_save_object.styling.form_heading_weight = af2_builder_object.af2_save_object.styling.form_heading_weight != null ? af2_builder_object.af2_save_object.styling.form_heading_weight : '300';
    af2_builder_object.af2_save_object.styling.form_heading_line_height_desktop = af2_builder_object.af2_save_object.styling.form_heading_line_height_desktop != null ? af2_builder_object.af2_save_object.styling.form_heading_line_height_desktop : '42';
    af2_builder_object.af2_save_object.styling.form_heading_line_height_mobile = af2_builder_object.af2_save_object.styling.form_heading_line_height_mobile != null ? af2_builder_object.af2_save_object.styling.form_heading_line_height_mobile : '30';
    af2_builder_object.af2_save_object.styling.form_question_heading_size_desktop = af2_builder_object.af2_save_object.styling.form_question_heading_size_desktop != null ? af2_builder_object.af2_save_object.styling.form_question_heading_size_desktop : '32';
    af2_builder_object.af2_save_object.styling.form_question_heading_size_mobile = af2_builder_object.af2_save_object.styling.form_question_heading_size_mobile != null ? af2_builder_object.af2_save_object.styling.form_question_heading_size_mobile : '20';
    af2_builder_object.af2_save_object.styling.form_question_heading_weight = af2_builder_object.af2_save_object.styling.form_question_heading_weight != null ? af2_builder_object.af2_save_object.styling.form_question_heading_weight : '600';
    af2_builder_object.af2_save_object.styling.form_question_heading_line_height_desktop = af2_builder_object.af2_save_object.styling.form_question_heading_line_height_desktop != null ? af2_builder_object.af2_save_object.styling.form_question_heading_line_height_desktop : '42';
    af2_builder_object.af2_save_object.styling.form_question_heading_line_height_mobile = af2_builder_object.af2_save_object.styling.form_question_heading_line_height_mobile != null ? af2_builder_object.af2_save_object.styling.form_question_heading_line_height_mobile : '30';
    af2_builder_object.af2_save_object.styling.form_answer_card_text_size_desktop = af2_builder_object.af2_save_object.styling.form_answer_card_text_size_desktop != null ? af2_builder_object.af2_save_object.styling.form_answer_card_text_size_desktop : '17';
    af2_builder_object.af2_save_object.styling.form_answer_card_text_size_mobile = af2_builder_object.af2_save_object.styling.form_answer_card_text_size_mobile != null ? af2_builder_object.af2_save_object.styling.form_answer_card_text_size_mobile : '15';
    af2_builder_object.af2_save_object.styling.form_answer_card_text_weight = af2_builder_object.af2_save_object.styling.form_answer_card_text_weight != null ? af2_builder_object.af2_save_object.styling.form_answer_card_text_weight : '500';
    af2_builder_object.af2_save_object.styling.form_answer_card_text_line_height_desktop = af2_builder_object.af2_save_object.styling.form_answer_card_text_line_height_desktop != null ? af2_builder_object.af2_save_object.styling.form_answer_card_text_line_height_desktop : '27';
    af2_builder_object.af2_save_object.styling.form_answer_card_text_line_height_mobile = af2_builder_object.af2_save_object.styling.form_answer_card_text_line_height_mobile != null ? af2_builder_object.af2_save_object.styling.form_answer_card_text_line_height_mobile : '20';
    af2_builder_object.af2_save_object.styling.form_text_input_size_desktop = af2_builder_object.af2_save_object.styling.form_text_input_size_desktop != null ? af2_builder_object.af2_save_object.styling.form_text_input_size_desktop : '17';
    af2_builder_object.af2_save_object.styling.form_text_input_size_mobile = af2_builder_object.af2_save_object.styling.form_text_input_size_mobile != null ? af2_builder_object.af2_save_object.styling.form_text_input_size_mobile : '15';
    af2_builder_object.af2_save_object.styling.form_text_input_text_weight = af2_builder_object.af2_save_object.styling.form_text_input_text_weight != null ? af2_builder_object.af2_save_object.styling.form_text_input_text_weight : '500';
    af2_builder_object.af2_save_object.styling.form_text_input_line_height_desktop = af2_builder_object.af2_save_object.styling.form_text_input_line_height_desktop != null ? af2_builder_object.af2_save_object.styling.form_text_input_line_height_desktop : '27';
    af2_builder_object.af2_save_object.styling.form_text_input_line_height_mobile = af2_builder_object.af2_save_object.styling.form_text_input_line_height_mobile != null ? af2_builder_object.af2_save_object.styling.form_text_input_line_height_mobile : '25';
    af2_builder_object.af2_save_object.styling.form_question_description_size_desktop = af2_builder_object.af2_save_object.styling.form_question_description_size_desktop != null ? af2_builder_object.af2_save_object.styling.form_question_description_size_desktop : '20';
    af2_builder_object.af2_save_object.styling.form_question_description_size_mobile = af2_builder_object.af2_save_object.styling.form_question_description_size_mobile != null ? af2_builder_object.af2_save_object.styling.form_question_description_size_mobile : '16';
    af2_builder_object.af2_save_object.styling.form_question_description_weight = af2_builder_object.af2_save_object.styling.form_question_description_weight != null ? af2_builder_object.af2_save_object.styling.form_question_description_weight : '300';
    af2_builder_object.af2_save_object.styling.form_question_description_line_height_desktop = af2_builder_object.af2_save_object.styling.form_question_description_line_height_desktop != null ? af2_builder_object.af2_save_object.styling.form_question_description_line_height_desktop : '30';
    af2_builder_object.af2_save_object.styling.form_question_description_line_height_mobile = af2_builder_object.af2_save_object.styling.form_question_description_line_height_mobile != null ? af2_builder_object.af2_save_object.styling.form_question_description_line_height_mobile : '26';
    af2_builder_object.af2_save_object.styling.form_button_label_size_desktop = af2_builder_object.af2_save_object.styling.form_button_label_size_desktop != null ? af2_builder_object.af2_save_object.styling.form_button_label_size_desktop : '17';
    af2_builder_object.af2_save_object.styling.form_button_label_size_mobile = af2_builder_object.af2_save_object.styling.form_button_label_size_mobile != null ? af2_builder_object.af2_save_object.styling.form_button_label_size_mobile : '15';
    af2_builder_object.af2_save_object.styling.icon_size_desktop_grid = af2_builder_object.af2_save_object.styling.icon_size_desktop_grid != null ? af2_builder_object.af2_save_object.styling.icon_size_desktop_grid : '90';
    af2_builder_object.af2_save_object.styling.icon_size_desktop_list_1 = af2_builder_object.af2_save_object.styling.icon_size_desktop_list_1 != null ? af2_builder_object.af2_save_object.styling.icon_size_desktop_list_1 : '70';
    af2_builder_object.af2_save_object.styling.icon_size_desktop_list_2 = af2_builder_object.af2_save_object.styling.icon_size_desktop_list_2 != null ? af2_builder_object.af2_save_object.styling.icon_size_desktop_list_2 : '60';
    af2_builder_object.af2_save_object.styling.icon_size_mobile_grid = af2_builder_object.af2_save_object.styling.icon_size_mobile_grid != null ? af2_builder_object.af2_save_object.styling.icon_size_mobile_grid : '25';
    af2_builder_object.af2_save_object.styling.icon_size_mobile_list = af2_builder_object.af2_save_object.styling.icon_size_mobile_list != null ? af2_builder_object.af2_save_object.styling.icon_size_mobile_list : '25';


    af2_builder_object.af2_save_object.styling.form_answer_card_border_radius = af2_builder_object.af2_save_object.styling.form_answer_card_border_radius != null ? af2_builder_object.af2_save_object.styling.form_answer_card_border_radius : '10';
    af2_builder_object.af2_save_object.styling.form_text_input_border_radius = af2_builder_object.af2_save_object.styling.form_text_input_border_radius != null ? af2_builder_object.af2_save_object.styling.form_text_input_border_radius : '10';

    af2_builder_object.af2_save_object.styling.form_contact_form_button_background_color = af2_builder_object.af2_save_object.styling.form_contact_form_button_background_color != null ? af2_builder_object.af2_save_object.styling.form_contact_form_button_background_color : 'rgba(157,65,221,1)';
    af2_builder_object.af2_save_object.styling.form_contact_form_button_color = af2_builder_object.af2_save_object.styling.form_contact_form_button_color != null ? af2_builder_object.af2_save_object.styling.form_contact_form_button_color : 'rgba(255,255,255,1)';
    af2_builder_object.af2_save_object.styling.form_contact_form_font_color = af2_builder_object.af2_save_object.styling.form_contact_form_font_color != null ? af2_builder_object.af2_save_object.styling.form_contact_form_font_color : 'rgba(51,51,51,1)';
    af2_builder_object.af2_save_object.styling.form_contact_form_button_size = af2_builder_object.af2_save_object.styling.form_contact_form_button_size != null ? af2_builder_object.af2_save_object.styling.form_contact_form_button_size : '17';
    af2_builder_object.af2_save_object.styling.form_contact_form_button_weight = af2_builder_object.af2_save_object.styling.form_contact_form_button_weight != null ? af2_builder_object.af2_save_object.styling.form_contact_form_button_weight : '500';
    af2_builder_object.af2_save_object.styling.form_contact_form_button_padding_top_bottom = af2_builder_object.af2_save_object.styling.form_contact_form_button_padding_top_bottom != null ? af2_builder_object.af2_save_object.styling.form_contact_form_button_padding_top_bottom : '12';
    af2_builder_object.af2_save_object.styling.form_contact_form_button_padding_left_right = af2_builder_object.af2_save_object.styling.form_contact_form_button_padding_left_right != null ? af2_builder_object.af2_save_object.styling.form_contact_form_button_padding_left_right : '12';
    af2_builder_object.af2_save_object.styling.form_contact_form_button_border_radius = af2_builder_object.af2_save_object.styling.form_contact_form_button_border_radius != null ? af2_builder_object.af2_save_object.styling.form_contact_form_button_border_radius : '10';
    af2_builder_object.af2_save_object.styling.form_contact_form_label_size = af2_builder_object.af2_save_object.styling.form_contact_form_label_size != null ? af2_builder_object.af2_save_object.styling.form_contact_form_label_size : '17';
    af2_builder_object.af2_save_object.styling.form_contact_form_label_weight = af2_builder_object.af2_save_object.styling.form_contact_form_label_weight != null ? af2_builder_object.af2_save_object.styling.form_contact_form_label_weight : '500';
    af2_builder_object.af2_save_object.styling.form_contact_form_input_size = af2_builder_object.af2_save_object.styling.form_contact_form_input_size != null ? af2_builder_object.af2_save_object.styling.form_contact_form_input_size : '15';
    af2_builder_object.af2_save_object.styling.form_contact_form_input_weight = af2_builder_object.af2_save_object.styling.form_contact_form_input_weight != null ? af2_builder_object.af2_save_object.styling.form_contact_form_input_weight : '400';
    af2_builder_object.af2_save_object.styling.form_contact_form_cb_size = af2_builder_object.af2_save_object.styling.form_contact_form_cb_size != null ? af2_builder_object.af2_save_object.styling.form_contact_form_cb_size : '15';
    af2_builder_object.af2_save_object.styling.form_contact_form_cb_weight = af2_builder_object.af2_save_object.styling.form_contact_form_cb_weight != null ? af2_builder_object.af2_save_object.styling.form_contact_form_cb_weight : '400';
    af2_builder_object.af2_save_object.styling.form_contact_form_input_height = af2_builder_object.af2_save_object.styling.form_contact_form_input_height != null ? af2_builder_object.af2_save_object.styling.form_contact_form_input_height : '50';
    af2_builder_object.af2_save_object.styling.form_contact_form_input_border_radius = af2_builder_object.af2_save_object.styling.form_contact_form_input_border_radius != null ? af2_builder_object.af2_save_object.styling.form_contact_form_input_border_radius : '10';
    af2_builder_object.af2_save_object.styling.form_contact_form_input_padding_left_right = af2_builder_object.af2_save_object.styling.form_contact_form_input_padding_left_right != null ? af2_builder_object.af2_save_object.styling.form_contact_form_input_padding_left_right : '10';


    af2_builder_object.af2_save_object.all_entries = af2_builder_object.af2_save_object.all_entries != null ? af2_builder_object.af2_save_object.all_entries : [];
    af2_builder_object.af2_save_object.all_paths = af2_builder_object.af2_save_object.all_paths != null ? af2_builder_object.af2_save_object.all_paths : [];
    af2_builder_object.af2_save_object.sections = af2_builder_object.af2_save_object.sections != null ? af2_builder_object.af2_save_object.sections : [];

    if(af2_builder_object.af2_save_object.all_entries.length == 0) af2_builder_object.af2_save_object.all_entries.push(new Af2Entry('0', '-1'));

    af2_builder_object.af2_save_object.entries_num = 0;
    af2_builder_object.af2_save_object.paths_num = 0;

    af2_builder_object.af2_save_object.percentage = af2_builder_object.af2_save_object.percentage != null ? parseInt(af2_builder_object.af2_save_object.percentage) : 100;
    af2_builder_object.af2_save_object.percentage_add = 30;

    af2_builder_object.af2_save_object.dsgvo = af2_builder_object.af2_save_object.dsgvo != null ? af2_builder_object.af2_save_object.dsgvo : false;

    af2_load_object_data();

    jQuery('#af2_goto_formularbuilder').on('click', _ => {
        af2_save_builder( _ => {
            window.location.href = af2_formularbuilder_settings_object.redirect_formularbuilder_url;
        });
    });

    jQuery('.af2_show_preview').on('click', _ => {
        af2_save_builder( _ => {
            window.location.href = af2_formularbuilder_settings_object.redirect_formularbuilder_preview_url;
        }, true);
    });

    jQuery(document).on('click', '.af2_show .af2_upload_file_button', () => {
        var fileInput = jQuery('.af2_show #af2FontFile')[0];
        var file = fileInput.files[0];

        if (file) {
            let formData = new FormData();
            formData.append('af2FontFile', file);
            formData.append('action', 'af2_fnsf_add_af2_font');
            formData.append('nonce', af2_builder_object.nonce);

            jQuery.ajax({
                url: af2_builder_object.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,  // Daten nicht prozessieren
                contentType: false,  // Daten-Typ nicht setzen
                success: (response) => { 
                    window.location.href = window.location.href + '&af2OpenFontModal=true';
                    console.log(response); 
                },
                error: () => { console.log('error'); }
            });
        } else {
            console.log('Bitte wählen Sie eine Datei aus.');
        }
    });

    jQuery(document).on('click', '.af2_show .af2_font_delete', function() {
        const deletefilename = jQuery(this).data('deletefile');
        jQuery.ajax({
            url: af2_builder_object.ajax_url,
            type: 'POST',
            data: {
                action: 'af2_fnsf_delete_af2_font',
                nonce: af2_builder_object.nonce,
                deletefile: deletefilename
            },
            success: (response) => { 
                window.location.href = window.location.href + '&af2OpenFontModal=true';
                console.log(response); 
            },
            error: () => { console.log('error'); }
        });
    });

    var urlParams = new URLSearchParams(window.location.search);
    var extraParamValue = urlParams.get('af2OpenFontModal');

    if (extraParamValue !== null && extraParamValue == 'true') {
        jQuery('.af2_modal_btn[data-target="af2_manage_fonts"]').trigger('click');
    }
});

