<?php

require_once FNSF_AF2_MENU_PARENTS_CLASS;
class Fnsf_Af2Fragenbuilder extends Fnsf_Af2MenuBuilder {

    protected function fnsf_get_builder_heading() { return array('label' => 'Question editor', 'icon' => 'fas fa-edit'); }
    protected function fnsf_get_builder_sidebar_data_() { return array('label' => 'Elements', 'icon' => 'fas fa-atom'); }
    protected function fnsf_get_builder_sidebar_edit_() { return array('label' => __('Settings' , 'funnelforms-free')); }
    protected function fnsf_get_builder_template() { return FNSF_AF2_QUESTION_TYPE_GENERAL; }

    protected function fnsf_get_close_editor_url() { return admin_url('/admin.php?page='.FNSF_FRAGE_SLUG ); }

    protected function fnsf_get_menu_builder_control_buttons_() { return array(array('id' => 'af2_create_new_question', 'icon' => 'fas fa-plus', 'label' => __('New question', 'funnelforms-free'))); }
    protected function fnsf_get_builder_sidebar_content_elements_() {
        return array(
            array('label' => 'Single selection', 'image' => '/res/images/question_types/single_selection.png', 'elementid' => 'af2_select'),
            array('label' => 'Multiple selection', 'image' => '/res/images/question_types/multiple_selection.png', 'elementid' => 'af2_multiselect'),
            array('label' => 'Text row', 'image' => '/res/images/question_types/text_row.png', 'elementid' => 'af2_textfeld'),
            array('label' => 'Text area', 'image' => '/res/images/question_types/text_area.png', 'elementid' => 'af2_textbereich'),
            array('label' => 'Date', 'image' => '/res/images/question_types/date.png', 'elementid' => 'af2_datum', 'disabled' => true),
            array('label' => 'Slider', 'image' => '/res/images/question_types/slider.png', 'elementid' => 'af2_slider', 'disabled' => true),
            array('label' => 'HTML content', 'image' => '/res/images/question_types/html.png', 'elementid' => 'af2_content', 'disabled' => true),
            array('label' => 'File upload', 'image' => '/res/images/question_types/file_upload.png', 'elementid' => 'af2_dateiupload', 'disabled' => true),
            array('label' => 'Dropdown', 'image' => '/res/images/question_types/dropdown.png', 'elementid' => 'af2_dropdown', 'disabled' => true),
            array('label' => 'Address', 'image' => '/res/images/question_types/address.png', 'elementid' => 'af2_adressfeld', 'disabled' => true),
            array('label' => 'Appointment booking', 'image' => '/res/images/question_types/appointment_booking.png', 'elementid' => 'af2_terminbuchung', 'disabled' => true),
        );
    }
    protected function fnsf_get_builder_sidebar_edit_elements_() {
        require_once FNSF_AF2_MENU_FRAGENBUILDER_ELEMENTS_PATH;
        return fnsf_get_fragenbuilder_elements();
    }

    protected function fnsf_get_builder_script() { return 'af2_fragenbuilder'; }
    protected function fnsf_get_builder_style() { return 'af2_fragenbuilder_style'; }
    protected function fnsf_get_builder_script_object_name() { return 'af2_fragenbuilder_object';  }
    protected function fnsf_get_builder_script_localize_array() { 
        return array(
            'question_types' => $this->fnsf_get_question_type_templates(),
            'create_new_question_url' => admin_url('/admin.php?page='.FNSF_FRAGE_SLUG.'&action=af2CreatePost&custom_post_type='.FNSF_FRAGE_POST_TYPE.'&redirect_slug='.FNSF_FRAGENBUILDER_SLUG.'&time='.time()),
            'supported_server_size' => 'AIzaSyBndbQcPBJHZyoqmdgexoTStZUk53dHRNw',
            'strings' => array(
                'antwort' => __('Answer text', 'funnelforms-free'),
                'dot' => __(',', 'funnelforms-free'),
            )
        );
    }

    function fnsf_get_question_type_templates() {
        return array(
            'general' => $this->Admin->fnsf_af2_read_template(FNSF_AF2_QUESTION_TYPE_GENERAL, null),
            'af2_select' => $this->Admin->fnsf_af2_read_template(FNSF_AF2_QUESTION_TYPE_SELECT, null),
            'af2_multiselect' => $this->Admin->fnsf_af2_read_template(FNSF_AF2_QUESTION_TYPE_MULTISELECT, null),
            'af2_textfeld' => $this->Admin->fnsf_af2_read_template(FNSF_AF2_QUESTION_TYPE_TEXTROW, null),
            'af2_textbereich' => $this->Admin->fnsf_af2_read_template(FNSF_AF2_QUESTION_TYPE_TEXTAREA, null),
        );
    }

    protected function fnsf_load_resources() {
        wp_enqueue_script('af2_interact_js');
        wp_enqueue_script('af2_drag_drop');
        
        require_once FNSF_AF2_RESOURCE_HANDLER_PATH;
        load_media_iconpicker();

        parent::fnsf_load_resources();
    }

    public static function fnsf_save_function($content) {
        $own_content = $content;
        $echo_content = array();

        if(!isset($own_content['name']) || trim($own_content['name']) == '') {
            array_push($echo_content, array('label' => __('No title specified!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '#af2_question_type_heading'));
        }

        if(!isset($own_content['typ']) || empty($own_content['typ']) || $own_content['typ'] == 'none' ) {
            $own_content['typ'] = 'none';
            array_push($echo_content, array('label' => __('No question type selected!', 'funnelforms-free'), 'type' => 'af2_error'));
        }

        switch($own_content['typ']) {
            case 'af2_multiselect': {
                if(!isset($own_content['answers']) || empty($own_content['answers'])) {

                }
                else if(isset($own_content['condition']) && !empty($own_content['condition'])) {
                    if(intval($own_content['condition']) < 2) {
                        array_push($echo_content, array('label' => __('The maximum number must be a numeric value above 1!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '#af2_question_type_condition'));
                    }
                    if(intval($own_content['condition']) > sizeof($own_content['answers'])) {
                        array_push($echo_content, array('label' => __('The maximum number is too high (too few answer options)', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '#af2_question_type_condition'));
                    }
                }
            }
            case 'af2_select': {
                if(!isset($own_content['answers']) || empty($own_content['answers'])) {
                    array_push($echo_content, array('label' => __('At least two answers must be created!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '#af2_answer_wrapper_add'));
                }
                else if(sizeof($own_content['answers']) < 2) {
                    array_push($echo_content, array('label' => __('At least two answers must be created!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '#af2_answer_wrapper_add'));
                }

                $i = 0;
                foreach( $own_content['answers'] as $answer ) {
                    $text = $answer['text'];
                    $img = $answer['img'];
                    if(!isset($text) || !isset($img) || trim($text) == '' || empty($img)) {
                        array_push($echo_content, array('label' => __('The contents of the following answers are incomplete: ', 'funnelforms-free').$i, 'type' => 'af2_error', 'error_object' => '.af2_answer_wrapper[data-editcontentarrayid="'.$i.'"]'));
                    }
                    $i++;
                }
                break;
            }
        }

        $own_content['error'] = false;

        foreach( $echo_content as $content ) {
            if($content['type'] == 'af2_error') {
                $own_content['error'] = true;
                break;
            }
        }

        array_push($echo_content, array('label' => __('Saved successfully!', 'funnelforms-free'), 'type' => 'af2_success'));

        echo json_encode($echo_content);
        return $own_content;
    }
}