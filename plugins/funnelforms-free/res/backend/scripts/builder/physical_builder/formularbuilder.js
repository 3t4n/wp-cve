jQuery( document ).ready(function($) {
 
    // Parameters
    {
    af2_builder_object.af2_save_object.name = af2_builder_object.af2_save_object.name != null ? af2_builder_object.af2_save_object.name : '';
    af2_builder_object.af2_save_object.showLoading = af2_builder_object.af2_save_object.showLoading != null ? af2_builder_object.af2_save_object.showLoading : false;
    af2_builder_object.af2_save_object.rtl_layout = af2_builder_object.af2_save_object.rtl_layout != null ? af2_builder_object.af2_save_object.rtl_layout : false;
    af2_builder_object.af2_save_object.showFontAwesome = af2_builder_object.af2_save_object.showFontAwesome != null ? af2_builder_object.af2_save_object.showFontAwesome : true;
    af2_builder_object.af2_save_object.send_error_mail = af2_builder_object.af2_save_object.send_error_mail != null ? af2_builder_object.af2_save_object.send_error_mail : true;
    af2_builder_object.af2_save_object.activateScrollToAnchor = af2_builder_object.af2_save_object.activateScrollToAnchor != null ? af2_builder_object.af2_save_object.activateScrollToAnchor : false;
    af2_builder_object.af2_save_object.showSuccessScreen = af2_builder_object.af2_save_object.showSuccessScreen != null ? af2_builder_object.af2_save_object.showSuccessScreen : true;
    af2_builder_object.af2_save_object.success_text = af2_builder_object.af2_save_object.success_text != null ? af2_builder_object.af2_save_object.success_text : af2_formularbuilder_object.strings.success_text;
    af2_builder_object.af2_save_object.success_image = af2_builder_object.af2_save_object.success_image != null ? af2_builder_object.af2_save_object.success_image : af2_formularbuilder_object.standard_success_image;
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
    af2_builder_object.af2_save_object.redirect_num = 0;
    af2_builder_object.af2_save_object.paths_num = 0;

    af2_builder_object.af2_save_object.percentage = af2_builder_object.af2_save_object.percentage != null ? parseInt(af2_builder_object.af2_save_object.percentage) : 100;
    af2_builder_object.af2_save_object.percentage_add = 30;

    af2_builder_object.af2_save_object.dsgvo = af2_builder_object.af2_save_object.dsgvo != null ? af2_builder_object.af2_save_object.dsgvo : false;

    }

    // Deleting a path
    const af2_delete_path = (lineUid) => {

        let path = af2_builder_object.af2_save_object.all_paths.find( element => element.uid == lineUid );

        // Delete outgoing path
        let outgoing = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == path.fromUid );
        if(outgoing != null) {
            let index = outgoing.outgoing_paths.findIndex( element => element == path.uid );
            outgoing.outgoing_paths.splice(index, 1);
            
            jQuery('.af2_form_question_connect_dot[data-formquestionentryid="'+path.fromUid+'"][data-lineid="'+path.lineId+'"]').removeClass('af2_connected');
            jQuery('.af2_form_question_connect_dot[data-formquestionentryid="'+path.fromUid+'"][data-lineid="'+path.lineId+'"]').addClass('af2_line_draggable');


            // Determine
            const object_type = af2_determine_object_type(outgoing.elementid);
    
            if(object_type.typ == 'contact_form') af2_redraw_single_element(outgoing);
            if(object_type.typ == 'question' && object_type.obj.content.typ == 'af2_slider') af2_redraw_single_element(outgoing);
        }

        // Find entry where to path goes to and delete that path
        let incoming = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == path.toUid );
        if(incoming != null) {
            let index = incoming.incoming_paths.findIndex( element => element == path.uid );
            incoming.incoming_paths.splice(index, 1);


            const moreConnects = af2_builder_object.af2_save_object.all_paths.filter( element => element.toUid == path.toUid );
            if(moreConnects.length < 2 ) jQuery('.af2_form_question_drop_dot[data-formquestionentryid="'+path.toUid+'"]').removeClass('af2_connected');
        }

        // Remove the whole path
        jQuery('#' + path.uid).remove();
        let pathIndex = af2_builder_object.af2_save_object.all_paths.findIndex( element => element.uid == lineUid );
        af2_builder_object.af2_save_object.all_paths.splice(pathIndex, 1);
    }

    // Trigger for deleting outgoing line
    jQuery(document).on('click', '.af2_form_question_connect_dot.af2_connected', function() {
        const lineid = jQuery(this).data('lineid');
        const entryuid = jQuery(this).data('formquestionentryid');

        const path = af2_builder_object.af2_save_object.all_paths.find( element => element.lineId == lineid && element.fromUid == entryuid );

        af2_delete_path(path.uid);
    });

    // Trigger for deleting incoming line
    jQuery(document).on('click', '.af2_form_question_drop_dot.af2_connected', function() {
        const entryuid = jQuery(this).data('formquestionentryid');

        const paths = af2_builder_object.af2_save_object.all_paths.filter( element => element.toUid == entryuid );

        paths.forEach(path => af2_delete_path(path.uid));
    });

    // On Delete function
    jQuery(document).on('af2_deleted_deleteable_object', '.af2_form_questions_container', ev => {
        const dom_element = ev.element;
        const entryuid = jQuery(dom_element).data('formquestionentryid');

        // deleting the entry out
        const index = af2_builder_object.af2_save_object.all_entries.findIndex( element => element.uid == entryuid );
        af2_builder_object.af2_save_object.all_entries.splice(index, 1);
        jQuery(dom_element).remove();

        // deleting the paths
        const outgoingpaths = af2_builder_object.af2_save_object.all_paths.filter( element => element.fromUid == entryuid );
        outgoingpaths.forEach(path => af2_delete_path(path.uid));

        const incomingpaths = af2_builder_object.af2_save_object.all_paths.filter( element => element.toUid == entryuid );
        incomingpaths.forEach(path => af2_delete_path(path.uid));

        af2_builder_object.af2_save_object.percentage -= af2_builder_object.af2_save_object.percentage_add;

        if(af2_builder_object.af2_save_object.percentage < 100) af2_builder_object.af2_save_object.percentage = 100;


        // Adjusting the container size
        jQuery('#af2_form_questions_container svg').attr('width', af2_builder_object.af2_save_object.percentage + '%');
        jQuery('#af2_form_questions_container svg').attr('height', af2_builder_object.af2_save_object.percentage + '%');

        jQuery('#af2_form_questions_container').attr('style', 'width: '+af2_builder_object.af2_save_object.percentage + '%; height: '+af2_builder_object.af2_save_object.percentage + '%;');
    });

    const af2_determine_object_type = (elementid) => {
        const questionObject = af2_formularbuilder_object.fragen_contents.find( question => question.elementid == elementid );
        const contactformObject = af2_formularbuilder_object.kontaktformular_contents.find( question => question.elementid == elementid );

        if(questionObject != null)                          return { typ: 'question', obj: questionObject };
        else if(contactformObject != null)                  return { typ: 'contact_form', obj: contactformObject };
        else if(elementid.toString().includes('redirect:')) return { typ: 'redirect', elementid: elementid };
        else if(elementid.toString().includes(':'))         return { typ: 'interface', elementid: elementid };
        else                                                return { typ: 'error' };
    }

    const af2_transform_create_object = (elementid_, uid, errorHandler = false) => {

        const entry = af2_builder_object.af2_save_object.all_entries.find( entry => entry.uid == uid );
        const elementid = elementid_ != null ? elementid_ : entry.elementid;

        let obj = '';
        let error = '';

        const objectType = af2_determine_object_type(elementid);

        if(objectType.typ == 'question') obj = af2_transform_create_question(objectType.obj, uid);
        if(objectType.typ == 'contact_form') obj = af2_transform_create_contact_form(objectType.obj, uid);
        if(objectType.typ == 'redirect') obj = af2_transform_create_redirect(uid, elementid);
        if(objectType.typ == 'interface') obj = af2_transform_create_interface(elementid, uid);
        if(objectType.typ == 'error') {
            obj = af2_transform_create_error(uid);
            error = 'af2_form_question_error';
        }

        if(errorHandler == true) return {'obj': obj, 'error': error};
        return obj;
    }


    // Drawing and Dragging
    const af2_transform_drag_add_object = (element) => {
        jQuery('.af2_form_questions_container').append(jQuery(element));

        // Class handling
        jQuery(element).removeClass('af2_builder_sidebar_content');
        jQuery(element).removeClass('af2_builder_sidebar_element');

        jQuery(element).addClass('af2_form_question');
        jQuery(element).addClass('af2_no_delete');
        jQuery(element).addClass('af2_no_position');
        jQuery(element).addClass('af2_builder_deletable_object');
        jQuery(element).addClass('af2_no_remove');

        const elementid = jQuery(element).data('elementid');
        const uid = jQuery(element).data('formquestionentryid');
        
        // Getting object

        let newStructure = af2_transform_create_object(elementid, uid);

        // appending object
        jQuery(element).html(newStructure);
    }

    const af2_recalculate_line_ids = (entry) => {
        let paths = af2_builder_object.af2_save_object.all_paths.filter(element => entry.outgoing_paths.includes(element.uid) && element.lineId != -1);

        paths.forEach((path, i) => {
            path.lineId = i;
        });
    }

    // Creating a question
    const af2_transform_create_question = (questionObject, uid) => {
        let newStructure = '';
        let lineId = -1;

        let margin = '';
        const typ = questionObject.content.typ;
        const elementid = questionObject.elementid;

        const entry = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == uid );

        // Adding margin for special cases
        if(typ == 'af2_select' || typ == 'af2_multiselect' || typ == 'af2_dropdown' || typ == 'af2_slider') margin = 'mb15';

        
    
        newStructure += '<span class="af2_delete_object"><i class="fas fa-trash"></i></span>';
        newStructure += '<span class="af2_edit_object" data-editpage="af2_fragenbuilder" data-editid="'+elementid+'" data-navigateBackBuilder="af2_formularbuilder" data-navigateBackId="'+af2_formularbuilder_object.own_id+'"><i class="fas fa-edit"></i></span>';

        newStructure += '<div class="af2_form_question_heading '+margin+'">';
        newStructure += '<div class="af2_form_question_drop_dot af2_form_question_connect_dot_general"><div class="af2_form_question_connect_dot_fill"></div></div>';
        newStructure += '<h5>'+questionObject.content.name+'</h5>';
        newStructure += '<div class="af2_form_question_connect_dot af2_line_draggable af2_form_question_connect_dot_general" data-lineid="'+lineId+'"><div class="af2_form_question_connect_dot_fill"></div></div>';
        newStructure += '</div>';

        // Special Class handling
        if(typ == 'af2_select' || typ == 'af2_multiselect') {
            newStructure += '<div class="af2_form_question_answers">';
            questionObject.content.answers.forEach(el => {
                lineId++;
                newStructure += '<div class="af2_form_question_answer">';
                newStructure += '<p>'+el.text+'</p>';
                if(typ == 'af2_select') newStructure += '<div class="af2_form_question_connect_dot af2_line_draggable af2_form_question_connect_dot_general" data-lineid="'+lineId+'"><div class="af2_form_question_connect_dot_fill"></div></div>';
                newStructure += '</div>';
            });
            newStructure += '</div>';
        }

        if(typ == 'af2_slider') {
            newStructure += '<div class="af2_form_question_answers">';

            let lineId = 0;

            if(entry != undefined) {
                af2_recalculate_line_ids(entry);
                entry.outgoing_paths.forEach(el => {
                    const path = af2_builder_object.af2_save_object.all_paths.find(path => path.uid == el);
                    if(path.lineId == -1) return;
                    newStructure += '<div class="af2_form_question_answer">';

                    const number = path.number != null ? path.number : '';
                    const operator_empty = path.operator != '>' && path.operator != '<' && path.operator != '=' ? 'selected' : '';
                    const operator_big = path.operator == '>' ? 'selected' : '';
                    const operator_small = path.operator == '<' ? 'selected' : '';
                    const operator_equal = path.operator == '=' ? 'selected' : '';

                    newStructure += '<div class="af2_slider_input">';
                    newStructure += '<select class="af2_slider_input_operator" data-lineid="'+lineId+'" value="">';
                        newStructure += '<option class="af2_slider_input_operator_option" value="empty" '+operator_empty+'>...</option>';
                        newStructure += '<option class="af2_slider_input_operator_option" value=">" '+operator_big+'>></option>';
                        newStructure += '<option class="af2_slider_input_operator_option" value="<" '+operator_small+'><</option>';
                        newStructure += '<option class="af2_slider_input_operator_option" value="=" '+operator_equal+'>=</option>';
                    newStructure += '</select>';
                    newStructure += '<input type="text" class="af2_slider_input_number" data-lineid="'+lineId+'" value="'+number+'">';
                    newStructure += '</div>';

                    newStructure += '<div class="af2_form_question_connect_dot af2_line_draggable af2_form_question_connect_dot_general" data-lineid="'+lineId+'"><div class="af2_form_question_connect_dot_fill"></div></div>';
                    newStructure += '</div>';
        
                    lineId++;
                });
            }

            newStructure += '<div class="af2_form_question_answer">';
            newStructure += '<p>'+af2_formularbuilder_object.strings.addcondition+'</p>';
            newStructure += '<div class="af2_form_question_connect_dot af2_line_draggable af2_form_question_connect_dot_general" data-lineid="'+lineId+'"><div class="af2_form_question_connect_dot_fill"></div></div>';
            newStructure += '</div>';

            newStructure += '</div>';
        }

        // Special Class handling
        if(typ == 'af2_dropdown') {
            newStructure += '<div class="af2_form_question_answers">';
            questionObject.content.dropdown_options.forEach(el => {
                lineId++;
                newStructure += '<div class="af2_form_question_answer">';
                newStructure += '<p>'+el.label+'</p>';
                newStructure += '<div class="af2_form_question_connect_dot af2_line_draggable af2_form_question_connect_dot_general" data-lineid="'+lineId+'"><div class="af2_form_question_connect_dot_fill"></div></div>';
                newStructure += '</div>';
            });
            newStructure += '</div>';
        }

        return newStructure;
    }

    // Creating a redirect
    const af2_transform_create_redirect = (uid, elementid) => {
        let newStructure = '';

        let own_uid = uid != null ? uid : af2_builder_object.af2_save_object.redirect_num;

        let inputuid = 'redirect_checkbox_'+own_uid;

        // finding the right entries num
        while(jQuery('.af2_form_question input#'+inputuid).length > 0) {
            af2_builder_object.af2_save_object.redirect_num++;
            inputuid = 'redirect_checkbox_'+af2_builder_object.af2_save_object.redirect_num;
        }

        const redirect = elementid.length > 9 ? elementid.substr(9) : '';

        let checkbox = '';
        if(uid != null) {
            const entry = af2_builder_object.af2_save_object.all_entries.find(element => element.uid == uid);

            if(entry.newtab) checkbox = 'checked';
        }

        newStructure += '<span class="af2_delete_object"><i class="fas fa-trash"></i></span>';

        newStructure += '<div class="af2_form_question_heading mb15">';
        newStructure += '<div class="af2_form_question_drop_dot af2_form_question_connect_dot_general"><div class="af2_form_question_connect_dot_fill"></div></div>';
        newStructure += '<i class="fas fa-external-link-alt"></i>';
        newStructure += '<h5>'+af2_formularbuilder_object.strings.redirect+'</h5>';
        newStructure += '</div>';
        newStructure += '<div class="af2_form_question_redirect_content">';
        newStructure += '<input type="text" placeholder="'+af2_formularbuilder_object.strings.redirect_placeholder+'" value="'+redirect +'" class="af2_form_question_redirect_input">';
        newStructure += '<div class="af2_option_div">';
            newStructure += '<input id="'+inputuid+'" class="af2_form_question_redirect_checkbox" type="checkbox" '+checkbox+'>';
            newStructure += '<label for="'+inputuid+'">'+af2_formularbuilder_object.strings.redirect_checkbox+'</label>';
        newStructure += '</div>';
        newStructure += '</div>';

        return newStructure;
    }

    const af2_transform_create_interface = (elementid, uid) => {
        let newStructure = '';

        newStructure += '<span class="af2_delete_object"><i class="fas fa-trash"></i></span>';

        newStructure += '<div class="af2_form_question_heading mb15">';
        newStructure += '<div class="af2_form_question_drop_dot af2_form_question_connect_dot_general"><div class="af2_form_question_connect_dot_fill"></div></div>';
        newStructure += '<i class="fas fa-rocket"></i>';
        newStructure += '<h5>'+af2_formularbuilder_object.strings[elementid]+'</h5>';
        newStructure += '</div>';
        newStructure += '<div class="af2_form_question_interface_content">';
        newStructure += '<div class="af2_btn af2_btn_primary af2_open_interface_modal" data-elementid="'+elementid+'" data-uid="'+uid+'"><i class="fas fa-edit"></i>'+af2_formularbuilder_object.strings.editinterface+'</div>';
        newStructure += '</div>';

        return newStructure;
    }

    const af2_transform_create_error = (uid) => {

        const entry = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == uid );

        let newStructure = '';
        let lineId = -1;

        let margin = '';

        // Adding margin for special cases
        margin = 'mb15';

        newStructure += '<span class="af2_delete_object"><i class="fas fa-trash"></i></span>';

        newStructure += '<div class="af2_form_question_heading '+margin+'">';
        newStructure += '<div class="af2_form_question_drop_dot af2_form_question_connect_dot_general"><div class="af2_form_question_connect_dot_fill"></div></div>';
        newStructure += '<h5>'+af2_formularbuilder_object.strings.no_element_error+'</h5>';
        newStructure += '<div class="af2_form_question_connect_dot af2_line_draggable af2_form_question_connect_dot_general" data-lineid="'+lineId+'"><div class="af2_form_question_connect_dot_fill"></div></div>';
        newStructure += '</div>';

            newStructure += '<div class="af2_form_question_answers">';
            entry.outgoing_paths.forEach(el => {
                lineId++;
                newStructure += '<div class="af2_form_question_answer">';
                newStructure += '<p>...</p>';
                newStructure += '<div class="af2_form_question_connect_dot af2_line_draggable af2_form_question_connect_dot_general" data-lineid="'+lineId+'"><div class="af2_form_question_connect_dot_fill"></div></div>';
                newStructure += '</div>';
            });
            newStructure += '</div>';

        return newStructure;
    }

    // creating a contact form
    const af2_transform_create_contact_form = (contactFormObject, uid) => {

        const entry = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == uid );

        if(entry != null) af2_recalculate_line_ids(entry);

        const elementid = contactFormObject.elementid;

        let newStructure = '';

        newStructure += '<span class="af2_delete_object"><i class="fas fa-trash"></i></span>';
        newStructure += '<span class="af2_edit_object" data-editpage="af2_kontaktformularbuilder" data-editid="'+elementid+'" data-navigateBackBuilder="af2_formularbuilder" data-navigateBackId="'+af2_formularbuilder_object.own_id+'"><i class="fas fa-edit"></i></span>';

        newStructure += '<div class="af2_form_question_heading mb15">';
        newStructure += '<div class="af2_form_question_drop_dot af2_form_question_connect_dot_general"><div class="af2_form_question_connect_dot_fill"></div></div>';
        newStructure += '<i class="fas fa-envelope"></i>';
        newStructure += '<h5>'+contactFormObject.content.name+'</h5>';
        newStructure += '</div>';

        newStructure += '<div class="af2_form_question_answers">';

        let lineId = 0;

        if(entry != undefined) {
            entry.outgoing_paths.forEach(el => {
                const path = af2_builder_object.af2_save_object.all_paths.find(path => path.uid == el);
                const element = af2_builder_object.af2_save_object.all_entries.find(entry => entry.uid == path.toUid);
                if(element == null) return;
                const object_type = af2_determine_object_type(element.elementid);
                let str = af2_formularbuilder_object.strings[object_type.typ];
                if(object_type.typ == 'interface') str = af2_formularbuilder_object.strings[object_type.elementid];
                newStructure += '<div class="af2_form_question_answer">';
                newStructure += '<p>'+str+'</p>';
                newStructure += '<div class="af2_form_question_connect_dot af2_line_draggable af2_form_question_connect_dot_general" data-lineid="'+lineId+'"><div class="af2_form_question_connect_dot_fill"></div></div>';
                newStructure += '</div>';
    
                lineId++;
            });
        }

        newStructure += '<div class="af2_form_question_answer">';
        newStructure += '<p>'+af2_formularbuilder_object.strings.addconnection+'</p>';
        newStructure += '<div class="af2_form_question_connect_dot af2_line_draggable af2_form_question_connect_dot_general" data-lineid="'+lineId+'"><div class="af2_form_question_connect_dot_fill"></div></div>';
        newStructure += '</div>';

        newStructure += '</div>';

        return newStructure;
    }

    // Redraw  Lines
    const af2_redraw_lines = (dragged_element) => {
        const formquestionentryid = jQuery(dragged_element).data('formquestionentryid');

        // Getting the affected Entry from element
        const affectedEntry = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == formquestionentryid );

        
        // Getting all paths to adjust
        outgoingPaths = affectedEntry.outgoing_paths;
        incomingPaths = affectedEntry.incoming_paths;

        // redrawing outgoing paths
        if(outgoingPaths != null)
        outgoingPaths.forEach(path_ => {
            let path = af2_builder_object.af2_save_object.all_paths.find( element => element.uid == path_ );
            const lineid = path.lineId;
            const dotElement = jQuery(dragged_element).find('div[data-lineid="'+lineid+'"]');

            const {x1, y1} = af2_svg_calc_initial_path(dotElement[0], '.af2_draw_svg');
            af2_recalculate(x1, y1, path.x2, path.y2, path);
            af2_redraw();
        });

        // redrawing incoming paths
        if(incomingPaths != null)
        incomingPaths.forEach(path_ => {
            let path = af2_builder_object.af2_save_object.all_paths.find( element => element.uid == path_ );
            const {x1, y1} = af2_svg_calc_initial_path( jQuery(dragged_element).find('.af2_form_question_drop_dot')[0], '.af2_draw_svg');
            af2_recalculate(path.x1, path.y1, x1, y1, path);
            af2_redraw();
        });
    }

    // On Add Drag function
    jQuery(document).on('af2_draggin_add_dragable_object', '.af2_form_questions_container', ev => {

        af2_builder_object.af2_save_object.percentage += af2_builder_object.af2_save_object.percentage_add;
        jQuery('#af2_form_questions_container svg').attr('width', af2_builder_object.af2_save_object.percentage + '%');
        jQuery('#af2_form_questions_container svg').attr('height', af2_builder_object.af2_save_object.percentage + '%');

        jQuery('#af2_form_questions_container').attr('style', 'width: '+af2_builder_object.af2_save_object.percentage + '%; height: '+af2_builder_object.af2_save_object.percentage + '%;');

        const handlerElement = ev.handlerElement;

        jQuery(handlerElement).attr('data-saveobjectid', 'all_entries');
        jQuery(handlerElement).attr('data-deletetriggerid', 'af2_form_questions_container');

        af2_transform_drag_add_object(handlerElement);
    });

    // On Dragging existing object
    jQuery(document).on('af2_active_draggin_dragable_object', '.af2_form_questions_container', ev => {
        const handlerElement = ev.handlerElement;
        af2_redraw_lines(handlerElement);
    });

     // On Drop function
     jQuery(document).on('af2_dropped_dragable_object', '.af2_form_questions_container', ev => {

        // if it is a new element add it
        if(jQuery(ev.addElement).hasClass('af2_array_add_draggable')) {

            let entries_id = af2_builder_object.af2_save_object.entries_num;

            // finding the right entries num
            while(jQuery('.af2_form_question[data-formquestionentryid="'+entries_id+'"]').length > 0) {
                af2_builder_object.af2_save_object.entries_num++;
                entries_id = af2_builder_object.af2_save_object.entries_num;
            }
            
            jQuery(ev.addElement).removeClass('af2_array_add_draggable');
            jQuery(ev.addElement).removeClass('af2_flex_sidebar_heading');
            jQuery(ev.addElement).addClass('af2_array_draggable');
            jQuery(ev.addElement).addClass('af2_array_draggable_no_border');
            jQuery(ev.addElement).attr('data-formquestionentryid', entries_id);
            jQuery(ev.addElement).find('.af2_form_question_connect_dot_general').each((i, el) => {
                jQuery(el).attr('data-formquestionentryid', entries_id);
            }) 
            jQuery(ev.addElement).find('.af2_open_interface_modal').each((i, el) => {
                jQuery(el).attr('data-uid', entries_id);
            });

            const elementid = jQuery(ev.addElement).data('elementid');
            af2_builder_object.af2_save_object.all_entries.push(new Af2Entry(elementid, entries_id));

            af2_builder_object.af2_save_object.entries_num++;
        }

        // just edit it
        jQuery(ev.addElement).removeClass('af2_dragging');
    });

    const af2_redraw_single_element = (entry) => {
        af2_save_position_to_entry(entry);

        jQuery('.af2_form_question[data-formquestionentryid="'+entry.uid+'"]').not('.af2_start').each((i, el) => {
            el.remove();
        });

        
        const newStructure = af2_draw_structure_element(entry, entry.translationX, entry.translationY, false);
        jQuery('#af2_form_questions_container').append(newStructure);

        jQuery('.af2_form_question[data-formquestionentryid="'+entry.uid+'"]').find('.af2_form_question_connect_dot_general').each((i, el) => {
            jQuery(el).attr('data-formquestionentryid', entry.uid);
        });

        jQuery('.af2_form_question[data-formquestionentryid="'+entry.uid+'"]').not('.af2_start').each((i, el) => {
            af2_redraw_lines(el);
        });
    }

    // Dropping a line
    jQuery(document).on('af2_dropped_line', '.af2_form_questions_container', ev => {
        let id = 'af2_path_' + af2_builder_object.af2_save_object.paths_num;

        // finding the right paths num
        while(jQuery('#'+id).length > 0) {
            af2_builder_object.af2_save_object.paths_num++;
            id = 'af2_path_' + af2_builder_object.af2_save_object.paths_num;
        }

        // appending path
        af2_svg_append_path('.af2_draw_svg', id, ev.x1, ev.y1, ev.x2, ev.y2);
        const {d, x2, y2} = af2_svg_calc_moving_path('#'+id, 0, 0);
        af2_svg_change_path('#'+id, d, null, null, x2, y2);
        af2_svg_redraw('.af2_draw_svg');

        // get entrie elements and edit them
        const outgoing = ev.dragElement;
        const incoming = ev.dropElement;

        jQuery(outgoing).removeClass('af2_line_draggable');
        jQuery(outgoing).addClass('af2_connected');
        jQuery(incoming).addClass('af2_connected');
        const outgoingLineId = jQuery(outgoing).data('lineid');

        jQuery('#'+id).attr('data-formquestionlineid', id);


        // add path to structure
        const path = new Af2Path(id, outgoingLineId, jQuery(outgoing).data('formquestionentryid'), jQuery(incoming).data('formquestionentryid'), ev.x1, x2, ev.y1, y2);
        af2_builder_object.af2_save_object.all_paths.push(path);

        


        // add path to entries
        let outgoingEntry = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == jQuery(outgoing).data('formquestionentryid') );
        outgoingEntry.outgoing_paths.push(path.uid);

        let incomingEntry = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == jQuery(incoming).data('formquestionentryid') );
        incomingEntry.incoming_paths.push(path.uid);

        // Determine
        const object_type = af2_determine_object_type(outgoingEntry.elementid);
  
        if(object_type.typ == 'contact_form') af2_redraw_single_element(outgoingEntry);
        if(object_type.typ == 'question' && object_type.obj.content.typ == 'af2_slider') af2_redraw_single_element(outgoingEntry);

        af2_builder_object.af2_save_object.paths_num++;
    });

    // mouseover handler
    jQuery(document).on('mouseenter', '.af2_form_question_drop_dot', function() {
        const drag_id = jQuery('.af2_dragging_line').data('formquestionentryid');
        const dot_id = jQuery(this).data('formquestionentryid');

        if(drag_id != dot_id) jQuery(this).addClass('af2_is_droppable');
    });
    jQuery(document).on('mouseleave', '.af2_form_question_drop_dot', function() {
        jQuery(this).removeClass('af2_is_droppable');
    });

    
    // go to settings (save function)
    jQuery('#af2_goto_formularbuilder_settings').on('click', _ => {
        af2_save_to_structure();
        af2_save_positions_to_entry();
        af2_save_builder( _ => {
            window.location.href = af2_formularbuilder_object.redirect_formularbuilder_settings_url;
        });
    });


    // Save and Building Methods 
    const af2_save_to_structure = () => {

        // set all sorteds to false (factor to work with)
        af2_builder_object.af2_save_object.all_entries.forEach(el => {
            el.sorted = false;
            el.section = null;
            el.content = null;
            if(el.outgoing_paths == null) el.outgoing_paths = [];
        });
        af2_builder_object.af2_save_object.all_paths.forEach(el => {
            el.sorted = false;
        });

        // clear the sections element
        af2_builder_object.af2_save_object.sections = [];
        const startElement = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == '-1' );
        
        // Nothing to do if nothing is appended to the start element
        if(startElement.outgoing_paths.length == 0) return;

        // finding the first element
        let path = af2_builder_object.af2_save_object.all_paths.find( element => element.uid == startElement.outgoing_paths[0] );

        // first element editing
        const firstElement = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == path.toUid);
        firstElement.sorted = true;
        firstElement.section = 0;
        firstElement.content = 0;

        // push the data structure
        af2_builder_object.af2_save_object.sections.push({contents:[]});
        af2_builder_object.af2_save_object.sections[0].contents.push({data: firstElement.elementid.toString(), connections: [], incoming_connections: [], uid: firstElement.uid, api_values: firstElement.api_values});

        // go into iteration
        af2_save_to_structure_section_iterate(firstElement);

        // sort contact forms into the back
        af2_builder_object.af2_save_object.sections.forEach(section => {
            section.contents.forEach(content => {
                const element = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == content.uid);

                // Determine
                const contactformObject = af2_formularbuilder_object.kontaktformular_contents.find( question => question.elementid == element.elementid );
                
                if(contactformObject != null) {
                    af2_calculate_contact_form_shift(element);
                }
            });
        });

    }

    // Iterate method for aligning everything
    const af2_save_to_structure_section_iterate = (element) => {
        // iterate all paths
        element.outgoing_paths.forEach(el => {

            // getting the path and the to element
            let path = af2_builder_object.af2_save_object.all_paths.find( element => element.uid == el );
            let toElement = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == path.toUid);

            if(!toElement.sorted) {
                // add element into structure and iterate it
                toElement.sorted = true;
                
                const section = element.section+1;
                if(af2_builder_object.af2_save_object.sections[section] == null) af2_builder_object.af2_save_object.sections.push({contents:[]});

                let obj = {data: toElement.elementid.toString(), connections: [], incoming_connections: [], uid: toElement.uid, api_values: toElement.api_values};
                if(toElement.newtab != null) obj.newtab = toElement.newtab;
                af2_builder_object.af2_save_object.sections[section].contents.push(obj);

                const content = af2_builder_object.af2_save_object.sections[section].contents.length - 1;
                toElement.section = section;
                toElement.content = content;

                af2_save_to_structure_section_iterate(toElement);
            }
            else {
                // calculate if we need shifts
                af2_calculate_object_shift(toElement, element)
            }

            // adding path to the connections in the structure
            if(!path.sorted) {
                path.sorted = true;
                af2_builder_object.af2_save_object.sections[toElement.section].contents[toElement.content].incoming_connections.push({from_section: element.section, from_content: element.content, uid: path.uid});
                
                let obj = {from: path.lineId, to_section: toElement.section, to_content: toElement.content, uid: path.uid};
                if(path.operator != null) obj.operator = path.operator;
                if(path.number != null) obj.number = path.number;
                af2_builder_object.af2_save_object.sections[element.section].contents[element.content].connections.push(obj);
            }
        });
    }

    // Calculate if a object should get shifted foward
    const af2_calculate_object_shift = (toElement, element) => {

        // check if we even need a shift
        if(toElement.section > element.section) return;

        // copy the object
        const obj = Object.assign({}, af2_builder_object.af2_save_object.sections[toElement.section].contents[toElement.content]);

        // setting new section and splicing the old
        const section = element.section+1;
        af2_builder_object.af2_save_object.sections[toElement.section].contents.splice(toElement.content, 1);

        // maybe creating new section and then appending
        if(af2_builder_object.af2_save_object.sections[section] == null) af2_builder_object.af2_save_object.sections.push({contents:[]});
        af2_builder_object.af2_save_object.sections[section].contents.push(obj);

        adjusted_old_content = [];
        // Old content adjusting
        af2_builder_object.af2_save_object.sections[toElement.section].contents.forEach((ele, i) => {
            const entry = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == ele.uid);
            if(entry.content >= toElement.content) {
                entry.content--;
                adjusted_old_content.push(entry);
            }
        });
        adjusted_old_content.forEach(ele => {
            af2_calculate_object_move_paths(ele.section, ele.content);
        });
        
        // New content adjusting
        const content = af2_builder_object.af2_save_object.sections[section].contents.length - 1;
        toElement.section = section;
        toElement.content = content;

        af2_calculate_object_move_paths(section, content);

        // maybe do an object shift cuz of the old shift
        af2_builder_object.af2_save_object.sections[section].contents[content].connections.forEach(ele => {
            const path = af2_builder_object.af2_save_object.all_paths.find( element => element.uid == ele.uid);
            const fromEntry = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == path.fromUid);
            const toEntry = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == path.toUid);

            af2_calculate_object_shift(toEntry, fromEntry);
        });
    }

    const af2_calculate_contact_form_shift_section = (section) => {
        if(section == 0) return section;

        const sections  = af2_builder_object.af2_save_object.sections;
        const contents = sections[section-1].contents;

        let questionCount = 0;
        let contactFormCount = 0;

        contents.forEach((el, contentIndex) => {
            const entry = af2_builder_object.af2_save_object.all_entries.find(element => element.section == section - 1 && element.content == contentIndex);
            const questionObject = af2_formularbuilder_object.fragen_contents.find( question => question.elementid == entry.elementid );
            const contactformObject = af2_formularbuilder_object.kontaktformular_contents.find( question => question.elementid == entry.elementid );

            if(questionObject != null) questionCount++;
            if(contactformObject != null) contactFormCount++;
        });

        return questionCount == 0 ? af2_calculate_contact_form_shift_section(section-1) : section;
    }

    const af2_calculate_contact_form_shift = (element) => {
        
        const shiftSection = af2_calculate_contact_form_shift_section(af2_builder_object.af2_save_object.sections.length);

        // Shift if sections do not match
        if(element.section == shiftSection) return;

        const obj = Object.assign({}, af2_builder_object.af2_save_object.sections[element.section].contents[element.content]);

        // Check if new Section and set Section
        let section = shiftSection;

        // splice old element out
        af2_builder_object.af2_save_object.sections[element.section].contents.splice(element.content, 1);

        // Add (section) and content
        if(af2_builder_object.af2_save_object.sections[section] == null) af2_builder_object.af2_save_object.sections.push({contents:[]});
        af2_builder_object.af2_save_object.sections[section].contents.push(obj);

        // Other contents now checking and shift path tracking / calc old contents
        af2_builder_object.af2_save_object.sections[element.section].contents.forEach((ele, i) => {
            const entry = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == ele.uid);
            if(entry.content >= element.content) {
                entry.content--;
                af2_calculate_object_move_paths(entry.section, entry.content);
            }
        });

        // calc new contents
        const content = af2_builder_object.af2_save_object.sections[section].contents.length - 1;
        element.section = section;
        element.content = content;

        af2_calculate_object_move_paths(section, content);

        // edit all connections from moved one
        af2_builder_object.af2_save_object.sections[section].contents[content].connections.forEach(ele => {
            const path = af2_builder_object.af2_save_object.all_paths.find( element => element.uid == ele.uid);
            const fromEntry = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == path.fromUid);
            const toEntry = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == path.toUid);

            af2_calculate_object_shift(toEntry, fromEntry);
        });
    }

    // Recalculate the paths after shifts
    const af2_calculate_object_move_paths = (section, content) => {
        // calculating incoming connections
        af2_builder_object.af2_save_object.sections[section].contents[content].incoming_connections.forEach(ele => {
            const path = af2_builder_object.af2_save_object.all_paths.find(element => element.uid == ele.uid);
            const fromEntry = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == path.fromUid);
            let connection = af2_builder_object.af2_save_object.sections[fromEntry.section].contents[fromEntry.content].connections.find(element => element.uid == path.uid);
            if(connection != null) connection.to_section = section;
            if(connection != null) connection.to_content = content;
        });

        // calculating connections
        af2_builder_object.af2_save_object.sections[section].contents[content].connections.forEach(ele => {
            const path = af2_builder_object.af2_save_object.all_paths.find(element => element.uid == ele.uid);
            const toEntry = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == path.toUid);
            let connection = af2_builder_object.af2_save_object.sections[toEntry.section].contents[toEntry.content].incoming_connections.find(element => element.uid == path.uid);
            if(connection != null) connection.from_section = section;
            if(connection != null) connection.from_content = content;
        });
    }


    const af2_draw_from_structure = () => {
        jQuery('.af2_form_question').not('.af2_start').each((i, el) => {
            const entry = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == jQuery(el).data('formquestionentryid'));
            if(entry.sorted) el.remove();
        });

        af2_draw_form_questions();

        jQuery('.af2_form_question').each((i, el) => {
            af2_redraw_lines(el);
        });
    }

    const af2_draw_form_questions = () => {
        af2_builder_object.af2_save_object.sections.forEach((section, sectionIndex) => {
            section.contents.forEach((content, contentIndex) => {
                const entry = af2_builder_object.af2_save_object.all_entries.find(element => element.section == sectionIndex && element.content == contentIndex)
        
                const newStructure = af2_draw_structure_element(entry, null, null, false);

                jQuery('#af2_form_questions_container').append(newStructure);


                jQuery('.af2_form_question[data-formquestionentryid="'+entry.uid+'"]').find('.af2_form_question_connect_dot_general').each((i, el) => {
                    jQuery(el).attr('data-formquestionentryid', entry.uid);
                });
            });
        });
    }

    // Draws the element offset
    const af2_draw_structure_element = (entry, translationX_, translationY_, modified) => {
        let draw = '';

        const translationXoffset = 150;
        const translationXsectionOffset = 300;
        let translationX = translationXoffset + (entry.section * translationXsectionOffset);

        let elementYOffset = 0;
        
        if(entry.content > 0) {
            for (let index = 0; index < entry.content; index++) {
                const element = af2_builder_object.af2_save_object.all_entries.find(element => element.section == entry.section && element.content == index);
                elementYOffset += jQuery('.af2_form_question[data-formquestionentryid="'+element.uid+'"]').height();
            }
        }

        const translationYoffset = 0;
        const translationYcontentOffset = 75;
        let translationY = translationYoffset + (entry.content * translationYcontentOffset) + elementYOffset;

        translationX = translationX_ != null ? translationX_ : translationX;
        translationY = translationY_ != null ? translationY_ : translationY;

        let x = af2_transform_create_object(null, entry.uid, true);
        let buffer = x.obj;
        let error = x.error;

        if(modified == true) error = 'af2_form_question_error';

        draw += '<div class="af2_form_question '+error+' af2_no_delete af2_no_position af2_builder_deletable_object af2_no_remove af2_array_draggable af2_array_draggable_no_border"';
        draw += 'data-elementid="'+entry.elementid+'"';
        draw += 'style="width: 303px; transform: translate('+translationX+'px, '+translationY+'px);"';
        draw += 'data-saveobjectid="all_entries"';
        draw += 'data-deletetriggerid="af2_form_questions_container"';
        draw += 'data-x="'+translationX+'"';
        draw += 'data-y="'+translationY+'"';
        draw += 'data-translationx="'+translationX+'"';
        draw += 'data-translationy="'+translationY+'"';
        draw += 'data-formquestionentryid="'+entry.uid+'">';
            draw += buffer;
        draw += '</div>';

        return draw;
    }

    var wdt_builder_elements = [];
    var maxZoomOut = false;
    var maxZoomIn = true;

    jQuery('#af2_form_questions_container').on('af2_zoomed_out', ev => {
        /*let thisZoomLevel = parseInt(ev.target.dataset.zoomlevel);
        if(maxZoomOut) return;
        if(thisZoomLevel == 1) maxZoomOut = true;
        if(thisZoomLevel < 5) maxZoomIn = false;
        let outX = 1;
        jQuery('.af2_form_question').each((i, el) => {
            let uid = el.dataset.formquestionentryid;
            let elHeight = el.offsetHeight;
            let elWidth = el.offsetHeight;

            if(uid != -1 ){
                let x =  el.dataset.x;
                let y = el.dataset.y;

                // storing the last positions of elements.
                if(wdt_builder_elements[thisZoomLevel+1] === undefined){
                    wdt_builder_elements[thisZoomLevel+1] = []; 
                }
                wdt_builder_elements[thisZoomLevel+1][i] ={ 'x':x,'y':y };
                
                let xMinus = 20;
                let yMinus = 20;
                // xMinus = 20*outX;
                xMinus = (elWidth * 20 /100)*outX;
                yMinus = (elHeight * 20 /100);
                // if(thisZoomLevel == 4) yMinus = (elHeight * 20 /100);
                // if(thisZoomLevel == 3)  yMinus = (elHeight * 8 /100);
                // if(thisZoomLevel == 2)  yMinus = (elHeight * 9 /100);
                let new_x = parseFloat(x) - xMinus;
                let new_y = parseFloat(y) - yMinus;
                /*console.log(yMinus);*
                new_x = (new_x > 0) ? new_x : 0;
                new_y = (new_y > 0) ? new_y : 0;
                
                /*console.log(new_x, new_y);*
                jQuery(el).attr('data-x', new_x);
                jQuery(el).attr('data-y', new_y);
                jQuery(el).attr('data-translationx', new_x);
                jQuery(el).attr('data-translationy', new_y);
                jQuery(el).attr('style', 'transform: translate('+new_x+'px, '+new_y+'px)');
                outX = outX*2;
            }
        });

        jQuery('.af2_form_question').each((i, el) => {
            af2_redraw_lines(el);
        });*/
    });

    jQuery('#af2_form_questions_container').on('af2_zoomed_in', ev => {
        /*let thisZoomLevel = parseInt(ev.target.dataset.zoomlevel);
        if(thisZoomLevel > 1) maxZoomOut = false;
        if( maxZoomIn) return;
        if(thisZoomLevel == 5) maxZoomIn = true;

        jQuery('.af2_form_question').each((i, el) => {
            let uid = el.dataset.formquestionentryid;
            if(uid != -1){
                let x = el.dataset.x;
                let y = el.dataset.y;
                if(wdt_builder_elements[thisZoomLevel] !== undefined){
                    var old_values = wdt_builder_elements[thisZoomLevel][i];
                    var new_x = old_values.x;
                    var new_y = old_values.y;
                    jQuery(el).attr('data-x', new_x);
                    jQuery(el).attr('data-y', new_y);
                    jQuery(el).attr('data-translationx', new_x);
                    jQuery(el).attr('data-translationy', new_y);
                    jQuery(el).attr('style', 'transform: translate('+new_x+'px, '+new_y+'px)');
                }
            }
        });

        jQuery('.af2_form_question').each((i, el) => {
            af2_redraw_lines(el);
        });*/
    });


    jQuery('#af2_sort_form_questions').on('click', _ => {

        af2_save_to_structure();
        af2_draw_from_structure();


        jQuery('#af2_form_questions_container svg').attr('width', af2_builder_object.af2_save_object.percentage + '%');
        jQuery('#af2_form_questions_container svg').attr('height', af2_builder_object.af2_save_object.percentage + '%');

        jQuery('#af2_form_questions_container').attr('style', 'width: '+af2_builder_object.af2_save_object.percentage + '%; height: '+af2_builder_object.af2_save_object.percentage + '%;');
    });

    const af2_save_positions_to_entry = () => {
        af2_builder_object.af2_save_object.all_entries.forEach(entry => {
            af2_save_position_to_entry(entry);
        });
    }

    const af2_save_position_to_entry = (entry) => {
        entry.translationX = jQuery('.af2_form_question[data-formquestionentryid="'+entry.uid+'"]').attr('data-translationx');
        entry.translationY = jQuery('.af2_form_question[data-formquestionentryid="'+entry.uid+'"]').attr('data-translationy');
    }


    jQuery('#af2_save_post_').on('click', _ => {
        af2_save_to_structure();
        af2_save_positions_to_entry();

        af2_save_builder();
    });

    const af2_recalculate = (x1, y1, x2, y2, path) => {
        const d = af2_svg_calc_moved_paths_d(x1, y1, x2, y2);
        af2_svg_change_path('#'+path.uid, d, x1, y1, x2, y2);
        path.x1 = x1;
        path.x2 = x2;
        path.y1 = y1;
        path.y2 = y2;


        jQuery('.af2_form_question_connect_dot[data-formquestionentryid="'+path.fromUid+'"][data-lineid="'+path.lineId+'"]').addClass('af2_connected');
        jQuery('.af2_form_question_connect_dot[data-formquestionentryid="'+path.fromUid+'"][data-lineid="'+path.lineId+'"]').removeClass('af2_line_draggable');

        
        jQuery('.af2_form_question_drop_dot[data-formquestionentryid="'+path.toUid+'"]').addClass('af2_connected');
    }

    const af2_redraw = () => {
        af2_svg_redraw('.af2_draw_svg');
    }

    const af2_draw_entries = () => {

        af2_builder_object.af2_save_object.all_paths.forEach(path => {
            path.lineId = parseInt(path.lineId);
            path.fromUid = parseInt(path.fromUid);
            path.toUid = parseInt(path.toUid);
            path.x1 = parseFloat(path.x1);
            path.x2 = parseFloat(path.x2);
            path.y1 = parseFloat(path.y1);
            path.y2 = parseFloat(path.y2);
            if(path.sorted === 'true') path.sorted = true;
            if(path.sorted === 'false') path.sorted = false;
        });

        af2_builder_object.af2_save_object.sections.forEach(section => {
            section.contents.forEach(content => {
                // content.data = parseInt(content.data);
                content.uid = parseInt(content.uid);

                if(content.incoming_connections == null) content.incoming_connections = [];
                if(content.connections == null) content.connections = [];

                content.connections.forEach(connection => {
                    connection.from = parseInt(connection.from);
                    connection.to_section = parseInt(connection.to_section);
                    connection.to_content = parseInt(connection.to_content);
                });
                content.incoming_connections.forEach(connection => {
                    connection.from_section = parseInt(connection.from_section);
                    connection.from_content = parseInt(connection.from_content);
                });
            });
        });

        af2_builder_object.af2_save_object.all_entries.forEach(entry => {

            const questionObject = af2_formularbuilder_object.fragen_contents.find( question => question.elementid == entry.elementid );
            let answerCount = 0;
            let pathsToDelete = [];
            let modified = false;


            // keep all values
            if(entry.outgoing_paths == null) entry.outgoing_paths = [];
            if(entry.incoming_paths == null) entry.incoming_paths = [];

            if(entry.section === '') entry.section = null;
            else entry.section = parseInt(entry.section);
            if(entry.content === '') entry.content = null;
            else entry.content = parseInt(entry.content);

            if(entry.sorted === 'true') entry.sorted = true;
            if(entry.sorted === 'false') entry.sorted = false;

            if(entry.newtab != null && entry.newtab === 'true') entry.newtab = true;
            if(entry.newtab != null && entry.newtab === 'false') entry.newtab = false;

            entry.elementid = entry.elementid.toString().includes(':') ? entry.elementid : parseInt(entry.elementid);
            entry.uid = parseInt(entry.uid);
            entry.translationX = parseFloat(entry.translationX);
            entry.translationY = parseFloat(entry.translationY);
            entry.api_values = entry.api_values != null ? entry.api_values : {};

            entry.incoming_paths.forEach(path => {
                path.lineId = parseInt(path.lineId);
                path.fromUid = parseInt(path.fromUid);
                path.toUid = parseInt(path.toUid);
                path.x1 = parseInt(path.x1);
                path.x2 = parseInt(path.x2);
                path.y1 = parseInt(path.y1);
                path.y2 = parseInt(path.y2);
                if(path.sorted === 'true') path.sorted = true;
                if(path.sorted === 'false') path.sorted = false;
            });
            entry.outgoing_paths.forEach(path => {
                path.lineId = parseInt(path.lineId);
                path.fromUid = parseInt(path.fromUid);
                path.toUid = parseInt(path.toUid);
                path.x1 = parseInt(path.x1);
                path.x2 = parseInt(path.x2);
                path.y1 = parseInt(path.y1);
                path.y2 = parseInt(path.y2);
                if(path.sorted === 'true') path.sorted = true;
                if(path.sorted === 'false') path.sorted = false;
                if(path.operator != null && path.operator === 'false') path.operator = false;

                if(questionObject != null && (questionObject.content.typ == 'af2_select' || questionObject.content.typ == 'af2_multiselect')) {
                    if(questionObject != null) answerCount = questionObject.content.answers.length;
                    const connectingPath = af2_builder_object.af2_save_object.all_paths.find( searchpath => searchpath.uid == path );

                    if(connectingPath.lineId >= answerCount) {
                        modified = true;
                        pathsToDelete.push(path);
                    }
                }

            });

            pathsToDelete.forEach(path => af2_delete_path(path));

            if(entry.uid != -1) {
                const newStructure = af2_draw_structure_element(entry, entry.translationX, entry.translationY, modified);

                jQuery('#af2_form_questions_container').append(newStructure);


                jQuery('.af2_form_question[data-formquestionentryid="'+entry.uid+'"]').find('.af2_form_question_connect_dot_general').each((i, el) => {
                    jQuery(el).attr('data-formquestionentryid', entry.uid);
                });
            }
        });
    }

    const af2_draw_lines = () => {
        af2_builder_object.af2_save_object.all_paths.forEach(path => {

            if(path.sorted === 'true') path.sorted = true;
            if(path.sorted === 'false') path.sorted = false;

            af2_svg_append_path('.af2_draw_svg', path.uid, path.x1, path.y1, path.x2, path.y2);
            const {d, x2, y2} = af2_svg_calc_moving_path('#'+path.uid, 0, 0);
            af2_svg_change_path('#'+path.uid, d, null, null, null, null);

            af2_recalculate(path.x1, path.y1, path.x2, path.y2, path);
        });

        af2_redraw();
    }

    const af2_adjust_lines = () => {
        jQuery('.af2_form_question').each((i, el) => {
            af2_redraw_lines(el);
        });
    }

    const af2_do_load_adjustments = () => {
        jQuery('#af2_form_questions_container svg').attr('width', af2_builder_object.af2_save_object.percentage + '%');
        jQuery('#af2_form_questions_container svg').attr('height', af2_builder_object.af2_save_object.percentage + '%');

        jQuery('#af2_form_questions_container').attr('style', 'width: '+af2_builder_object.af2_save_object.percentage + '%; height: '+af2_builder_object.af2_save_object.percentage + '%;');
    }

    const af2_draw_interface_content = (elementid, uid) => {
        let drawObjectStructure = af2_formularbuilder_object[elementid];
        if(drawObjectStructure == null) return '';

        const entry = af2_builder_object.af2_save_object.all_entries.find( entry => entry.uid == uid );
        const path = af2_builder_object.af2_save_object.all_paths.find( path => path.uid == entry.incoming_paths[0] );
        const contactFormEntry = af2_builder_object.af2_save_object.all_entries.find( entry => entry.uid == path.fromUid );

        const contactForm = af2_formularbuilder_object.kontaktformular_contents.find( contactform => contactform.elementid == contactFormEntry.elementid );
        const questions = contactForm.content.questions;

        

        let content = '';

        drawObjectStructure.forEach( area => {
            content += '<div class="af2_interface_area">';
                content += '<h4 class="af2_interface_area_heading">'+area.label+'</h4>';
                content += '<div class="af2_interface_area_content">';
                    area.fields.forEach(field => {

                        let label = field.label;
                        label = field.required ? label + ': *' : label + ':';

                        const savedvalue = entry.api_values[field.value] != null ? entry.api_values[field.value] : '';

                        content += '<div class="af2_interface_connect_row">';
                            content += '<div class="af2_field_label">';
                                content += '<h5>'+label+'</h5>';
                            content += '</div>';
                            switch(field.type) {
                                case 'select': {
                                    content += '<select id="" class="af2_interface_select af2_interface_input_element" value="" data-entryuid="'+uid+'" data-apivalue="'+field.value+'">';
                                        content += '<option value="">'+af2_formularbuilder_object.strings.choose+'</option>';
                                        
                                        questions.forEach(el => {
                                            const value = '['+el.id+']';
                                            let selected = '';
                                            if(savedvalue == value) selected = 'selected';
                                            content += '<option value="'+value+'" '+selected+'>'+value+'</option>';
                                        })
                                    content += '</select>';
                                    break;
                                }
                                case 'select_': {
                                    let condition = '';
                                    if(field.conditioned != null) condition='data-condition="'+field.conditioned+'"';
                                    content += '<select id="" '+condition+' class="af2_interface_select af2_interface_input_element" value="" data-entryuid="'+uid+'" data-apivalue="'+field.value+'">';
                                        content += '<option value="">'+af2_formularbuilder_object.strings.choose+'</option>';
                                        
                                        af2_formularbuilder_object.api_fields[entry.elementid][field.type_label].forEach(el => {
                                            let selected = '';
                                            if(savedvalue == el.value) selected = 'selected';
                                            content += '<option value="'+el.value+'" '+selected+'>'+el.label+'</option>';
                                        });
                                    content += '</select>';
                                    break;
                                }
                                case 'select_conditioned': {
                                    content += '<select id="" data-entryelementid="'+entry.elementid+'" data-fieldtypelabel="'+field.type_label+'" class="af2_interface_select af2_interface_input_element" value="" data-entryuid="'+uid+'" data-apivalue="'+field.value+'">';
                                        content += '<option value="">'+af2_formularbuilder_object.strings.choose+'</option>';

                                        const value = entry.api_values[field.conditioned_from];

                                        if(value == '' || value == null) { }
                                        else {
                                            const entryelementid = entry.elementid;
                                            const fieldtypelabel = field.type_label;
                                            // needs elementid, needs the integration label, needs the selected value -> then goes to iterate
                                            af2_formularbuilder_object.api_fields[entryelementid][fieldtypelabel][value].forEach(el => {
                                                let selected = '';
                                                if(savedvalue == el.value) selected = 'selected';
                                                content += '<option value="'+el.value+'" '+selected+'>'+el.label+'</option>';
                                            });
                                        }

                                    content += '</select>';
                                    break;
                                }
                                case 'text': {
                                    content += '<input id="" type="text" class="af2_interface_text af2_interface_input_element" value="'+savedvalue+'" data-entryuid="'+uid+'" data-apivalue="'+field.value+'">';
                                    break;
                                }
                                default: {
                                    break;
                                }
                            }
                        content += '</div>';
                    });
                content += '</div>';
            content += '</div>';
        });

        return content;
    }

    jQuery(document).on('input', '.af2_form_question_redirect_content .af2_form_question_redirect_input', function() {
        const val = jQuery(this).val();

        const formQuestionEntry = jQuery(this).closest('.af2_form_question');
        const uid = jQuery(formQuestionEntry).attr('data-formquestionentryid');

        let entry = af2_builder_object.af2_save_object.all_entries.find(element => element.uid == uid);
        entry.elementid = 'redirect:'+val;
    });
    jQuery(document).on('click', '.af2_form_question_redirect_content .af2_form_question_redirect_checkbox', function() {
        const checked = jQuery(this).is(":checked");
        const val = checked ? true : false;

        const formQuestionEntry = jQuery(this).closest('.af2_form_question');
        const uid = jQuery(formQuestionEntry).attr('data-formquestionentryid');

        let entry = af2_builder_object.af2_save_object.all_entries.find(element => element.uid == uid);
        entry.newtab = val;
    });

    jQuery(document).on('input', '.af2_slider_input .af2_slider_input_number', function() {
        const val = jQuery(this).val();
        const lineid = jQuery(this).attr('data-lineid');

        const formQuestionEntry = jQuery(this).closest('.af2_form_question');
        const uid = jQuery(formQuestionEntry).attr('data-formquestionentryid');

        let path = af2_builder_object.af2_save_object.all_paths.find(element => element.fromUid == uid && element.lineId == lineid);
        path.number = val;
    });
    jQuery(document).on('change', '.af2_slider_input .af2_slider_input_operator', function() {
        const val = jQuery(this).val();
        const lineid = jQuery(this).attr('data-lineid');

        const formQuestionEntry = jQuery(this).closest('.af2_form_question');
        const uid = jQuery(formQuestionEntry).attr('data-formquestionentryid');

        let path = af2_builder_object.af2_save_object.all_paths.find(element => element.fromUid == uid && element.lineId == lineid);
        path.operator = val;
    });


    jQuery(document).on('click', '.af2_open_interface_modal', function() {
        const elementid = jQuery(this).data('elementid');
        const uid = jQuery(this).data('uid');

        jQuery('#af2_interface_modal .af2_modal_content').html(af2_draw_interface_content(elementid, uid));
        af2_open_modal('#af2_interface_modal');
    });

    jQuery(document).on('change', '.af2_interface_select', function() {
        const uid = jQuery(this).attr('data-entryuid');
        const entry = af2_builder_object.af2_save_object.all_entries.find( entry => entry.uid == uid );
        const value = jQuery(this).val();
        const apivalue = jQuery(this).attr('data-apivalue');

        entry.api_values[apivalue] = value;

        const conditioned = jQuery(this).attr('data-condition');
        if(conditioned == null || conditioned == '') return;
        const parent = jQuery(this).closest('.af2_interface_area');
        const selectElement = jQuery(parent).find('select[data-apivalue="'+conditioned+'"]');
        
        jQuery(selectElement).html('');

        let content = '';
        content += '<option value="">'+af2_formularbuilder_object.strings.choose+'</option>';
                
        const entryelementid = jQuery(selectElement).attr('data-entryelementid');
        const fieldtypelabel = jQuery(selectElement).attr('data-fieldtypelabel');

        if(value == '' || value == null) {
            const entryuid = jQuery(selectElement).attr('data-entryuid');
            const _entry = af2_builder_object.af2_save_object.all_entries.find( entry => entry.uid == uid );
            _entry.api_values[fieldtypelabel] = '';
            jQuery(selectElement).html(content);
            return;
        }

        // needs elementid, needs the integration label, needs the selected value -> then goes to iterate
        af2_formularbuilder_object.api_fields[entryelementid][fieldtypelabel][value].forEach(el => {
            content += '<option value="'+el.value+'">'+el.label+'</option>';
        });

        jQuery(selectElement).html(content);
    });

    jQuery(document).on('input', '.af2_interface_text', function() {
        const uid = jQuery(this).attr('data-entryuid');
        const entry = af2_builder_object.af2_save_object.all_entries.find( entry => entry.uid == uid );
        const value = jQuery(this).val();
        const apivalue = jQuery(this).attr('data-apivalue');

        entry.api_values[apivalue] = value;
    });

    jQuery(document).on('click', 'span.af2_edit_object', function(ev) { 
        ev.stopPropagation();

        af2_save_positions_to_entry();
        af2_save_builder( _ => {
            let url = af2_builder_object.admin_url + jQuery(this).attr('data-editpage') + '&id=' + jQuery(this).attr('data-editid');
            url += '&navigateBackBuilder='+jQuery(this).attr('data-navigateBackBuilder');
            url += '&navigateBackID='+jQuery(this).attr('data-navigateBackId');
            window.location.href = url;
        });
    });

    const check_for_loops = () => {
        const startEntry = af2_builder_object.af2_save_object.all_entries.find(element => element.uid == -1);

        // Nothing to do if nothing is appended to the start element
        if(startEntry.outgoing_paths.length == 0) return;

        // finding the first element
        let startPath = af2_builder_object.af2_save_object.all_paths.find( element => element.uid == startEntry.outgoing_paths[0] );

        // first element editing
        const firstEntry = af2_builder_object.af2_save_object.all_entries.find( element => element.uid == startPath.toUid);
    }

    const check_for_loops_to_element = () => {
        
    }

    


    const af2_do_migration = () => {
        af2_builder_object.af2_save_object.migration = false;

        // first of all build the entries
        af2_builder_object.af2_save_object.sections.forEach((section, x) => {
            section.contents.forEach((content, y) => {
                af2_builder_object.af2_save_object.sections[x].contents[y].uid = af2_builder_object.af2_save_object.entries_num;
                let entry = new Af2Entry(content.data, af2_builder_object.af2_save_object.entries_num);
                entry.newtab = content.newtab != null ? content.newtab : null;
                af2_builder_object.af2_save_object.all_entries.push(entry);

                af2_builder_object.af2_save_object.percentage += af2_builder_object.af2_save_object.percentage_add;
                af2_builder_object.af2_save_object.entries_num++;

                // build the connection for first entry from start off
                if(x == 0 && y == 0) {
                    const uid = 'af2_path_'+af2_builder_object.af2_save_object.paths_num;

                    // create path
                    let path = new Af2Path(uid, -1, -1, 0, 0, 0, 0, 0);
                    af2_builder_object.af2_save_object.all_paths.push(path);

                    // add to entry
                    const fromEntry = af2_builder_object.af2_save_object.all_entries.find(element => element.uid == -1);
                    const toEntry = af2_builder_object.af2_save_object.all_entries.find(element => element.uid == 0);

                    fromEntry.outgoing_paths.push(uid);
                    toEntry.incoming_paths.push(uid);

                    af2_builder_object.af2_save_object.paths_num++;
                }
            });
        });

        // build the paths
        af2_builder_object.af2_save_object.sections.forEach((section, x) => {
            section.contents.forEach((content, y) => {
                if(content.connections != null) {
                    content.connections.forEach((connection, z) => {
                        const uid = 'af2_path_'+af2_builder_object.af2_save_object.paths_num;
                        const lineId = connection.from;
                        const fromUid = content.uid;
                        const toUid = af2_builder_object.af2_save_object.sections[connection.to_section].contents[connection.to_content].uid;

                        // add uid to outgoing connection
                        af2_builder_object.af2_save_object.sections[x].contents[y].connections[z].uid = uid;
                        if(af2_builder_object.af2_save_object.sections[connection.to_section].contents[connection.to_content].incoming_connections == null) af2_builder_object.af2_save_object.sections[connection.to_section].contents[connection.to_content].incoming_connections = [];
                        // add uid to incoming connection
                        const incoming_connection = af2_builder_object.af2_save_object.sections[connection.to_section].contents[connection.to_content].incoming_connections.find(element => element.from_section == x && element.from_content == y && element.uid == null);
                        if(incoming_connection == null ) af2_builder_object.af2_save_object.sections[connection.to_section].contents[connection.to_content].incoming_connections.push({ from_content: y, from_section: x, uid: uid });
                        else incoming_connection.uid = uid;

                        // create path
                        let path = new Af2Path(uid, lineId, fromUid, toUid, 0, 0, 0, 0);
                        path.operator = connection.operator != null ? connection.operator : null;
                        path.number = connection.number != null ? connection.number : null;
                        af2_builder_object.af2_save_object.all_paths.push(path);

                        // add to entry
                        const fromEntry = af2_builder_object.af2_save_object.all_entries.find(element => element.uid == fromUid);
                        const toEntry = af2_builder_object.af2_save_object.all_entries.find(element => element.uid == toUid);

                        fromEntry.outgoing_paths.push(uid);
                        toEntry.incoming_paths.push(uid);

                        af2_builder_object.af2_save_object.paths_num++;
                    });
                }
            });
        });

        af2_save_to_structure();
        af2_draw_lines();
        af2_draw_from_structure();


        jQuery('#af2_form_questions_container svg').attr('width', af2_builder_object.af2_save_object.percentage + '%');
        jQuery('#af2_form_questions_container svg').attr('height', af2_builder_object.af2_save_object.percentage + '%');

        jQuery('#af2_form_questions_container').attr('style', 'width: '+af2_builder_object.af2_save_object.percentage + '%; height: '+af2_builder_object.af2_save_object.percentage + '%;');

        af2_save_positions_to_entry();
    }

    if(af2_builder_object.af2_save_object.migration != null && (af2_builder_object.af2_save_object.migration == 'true' || af2_builder_object.af2_save_object.migration == true))
        af2_do_migration(); 
    else {
        af2_do_load_adjustments();
        af2_draw_entries();
        af2_draw_lines();
        af2_adjust_lines();
    }

    jQuery(document).on('change', '.af2_category_select', function() {
        const category_value = jQuery(this).val();

        jQuery('.af2_builder_sidebar_element').removeClass('af2_hide');

        if(category_value != 'empty') {
            jQuery('.af2_builder_sidebar_element').addClass('af2_hide');
            jQuery('.af2_builder_sidebar_element[data-selectvalue="'+category_value+'"]').removeClass('af2_hide');
        } 
    });
    
});

class Af2Entry {
    constructor(elementid, uid) {
        this.elementid = elementid;
        this.uid = uid;
        this.sorted = false;
        this.section = null;
        this.content = null;
        this.translationX = null;
        this.translationY = null;

        this.incoming_paths = [];
        this.outgoing_paths = [];

        this.api_values = {};

        this.newtab = null;
    }
}

class Af2Path {
    constructor(uid, lineId, fromUid, toUid, x1, x2, y1, y2) {
        this.uid = uid;
        this.lineId = lineId;
        this.fromUid = fromUid;
        this.toUid = toUid;
        this.x1 = x1;
        this.x2 = x2;
        this.y1 = y1;
        this.y2 = y2;

        this.sorted = false;

        this.operator = null;
        this.number = null;
    }
}
