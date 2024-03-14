<?php

require_once FNSF_AF2_MENU_PARENTS_CLASS;
class Fnsf_Af2KontaktformularbuilderSettings extends Fnsf_Af2MenuBuilder {

    protected function fnsf_get_builder_heading() { return array('label' => 'Contact form editor', 'icon' => 'fas fa-edit'); }
    protected function fnsf_get_builder_template() { return FNSF_AF2_BUILDER_TEMPLATE_KONTAKTFORMULAR_SETTINGS; }
    protected function fnsf_get_builder_sidebar_edit_() { return array('label' =>  __('Settings' , 'funnelforms-free')); }
    protected function fnsf_get_menu_builder_control_buttons_() { return array(array('id' => 'af2_goto_kontaktformularbuilder', 'icon' => 'fas fa-cog', 'label' => __('Back to Editor' , 'funnelforms-free'))); }

    protected function fnsf_get_close_editor_url() { return admin_url('/admin.php?page='.FNSF_KONTAKTFNSF_FORMULAR_SLUG ); }

    protected function fnsf_get_builder_script() { return 'af2_kontaktformularbuilder_settings'; }
    protected function fnsf_get_builder_style() { return 'af2_kontaktformularbuilder_settings_style'; }
    protected function fnsf_get_builder_script_object_name() { return 'af2_kontaktformularbuilder_settings_object';  }
    protected function fnsf_get_builder_script_localize_array() { 
        $idKobs = sanitize_text_field($_GET['id']);
        return array(
            'redirect_kontaktformularbuilder_url' => admin_url('/admin.php?page='.FNSF_KONTAKTFNSF_FORMULARBUILDER_SLUG.'&id='.$idKobs),
            'wordpress_mail_url' => 'wordpress@'.parse_url(get_site_url(), PHP_URL_HOST),
            'page_title' => get_option('blogname'),
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('af2_FE_nonce'),
            'strings' => array(
                'id_tag' => __('[ID]', 'funnelforms-free'),
                'answers_tag' => __('[ANTWORTEN]', 'funnelforms-free'),
                'querystring_tag' => __('[QUERYSTRING]', 'funnelforms-free'),
                'code_tag' => __('[CODE]', 'funnelforms-free'),
                'url_tag' => __('[URL]', 'funnelforms-free'),
                'name_placeholder' => __('Your name...', 'funnelforms-free'),
                'mail_placeholder' => __('Your e-mail...', 'funnelforms-free'),
                'phone_placeholder' => __('Your phone number...', 'funnelforms-free'),
                'checkbox_text' => __('I accept the privacy policy', 'funnelforms-free'),
                'name' => __('name', 'funnelforms-free'),
                'mail' => __('mail', 'funnelforms-free'),
                'telefon' => __('phone', 'funnelforms-free'),
                'checkbox' => __('checkbox', 'funnelforms-free'),
                'send_form' => __('Submit form', 'funnelforms-free'),
                'subject' => __('New Lead', 'funnelforms-free'),
                'testnachricht' => __('Click here to view the test message log', 'funnelforms-free'),
            ),

        );
    }


    protected function fnsf_get_builder_sidebar_edit_elements_() {
        require_once FNSF_AF2_MENU_KONTAKTFORMULARBUILDER_SETTINGS_ELEMENTS_PATH;
        return fnsf_get_kontaktformularbuilder_settings_elements();
    }

    protected function fnsf_load_resources() {
        require_once FNSF_AF2_RESOURCE_HANDLER_PATH;
        load_media_iconpicker();

        parent::fnsf_load_resources();
    }


    public static function fnsf_save_function($content) {
        require_once FNSF_AF2_MISC_FUNCTIONS_PATH;
        $own_content = $content;
        $echo_content = array();


        if(!isset($own_content['cftitle']) || empty($own_content['cftitle'])) {
            array_push($echo_content, array('label' => __('No contact form title (frontend) specified!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '#af2_goto_kontaktformularbuilder'));
        }

        if(!isset($own_content['send_button']) || empty($own_content['send_button'])) {
            array_push($echo_content, array('label' => __('No button text specified!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '#af2_goto_kontaktformularbuilder'));
        }

        if(!isset($own_content['name']) || empty($own_content['name'])) {
            array_push($echo_content, array('label' => __('No contact form title (backend) specified!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '#af2_contact_form_backend_heading'));
        }

        if(isset($own_content['questions'])) {
            $i = 0;

            $ids_array = array();

            foreach($own_content['questions'] as $question) {
                if(!isset($question['id']) || empty($question['id'])) {
                    array_push($echo_content, array('label' => __('All elements must have an ID!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '#af2_goto_kontaktformularbuilder'));
                }
                else if($question['typ'] == 'salutation_type') {
                   if(
                        (!filter_var($question['allowSalutationCompany'], FILTER_VALIDATE_BOOLEAN)) &&
                        (!filter_var($question['allowSalutationDivers'], FILTER_VALIDATE_BOOLEAN)) &&
                        (!filter_var($question['allowSalutationFemale'], FILTER_VALIDATE_BOOLEAN)) &&
                        (!filter_var($question['allowSalutationMale'], FILTER_VALIDATE_BOOLEAN))
                    ) {
                        array_push($echo_content, array('label' => __('At least one field must be displayed for the salutation!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '#af2_goto_kontaktformularbuilder'));
                    }
                }
                else if($question['typ'] == 'google_recaptcha') {
                    array_push($echo_content, array('label' => __('In the Free Version Google reCaptcha element can not be used!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '#af2_goto_kontaktformularbuilder'));
                }
                else if($question['typ'] == 'html_content') {
                    array_push($echo_content, array('label' => __('In the Free Version HTML content element can not be used!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '#af2_goto_kontaktformularbuilder'));
                }

                
                if(in_array($question['id'], $ids_array)) {
                    array_push($echo_content, array('label' => __('An ID cannot be assigned twice!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '#af2_goto_kontaktformularbuilder'));
                }
                array_push($ids_array, $question['id']);
                $i++;
            }
        }

        if(!isset($own_content['mailtext']) || empty($own_content['mailtext'])) {
            array_push($echo_content, array('label' => __('No e-mail message specified!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '.custom_builder_content_card_box [data-saveobjectid="mailtext"]'));
        }
        else {
            require_once FNSF_AF2_MISC_FUNCTIONS_PATH;
            $translations = fnsf_af2GetAnswersTranslations();
            $gotAnswers = false;
            foreach($translations as $translation) {
                if(fnsf_af2_str_contains(strtolower($own_content['mailtext']), strtolower($translation))) $gotAnswers = true;
            }
            if(!$gotAnswers) {
                array_push($echo_content, array('label' => __('The tag [ANSWERS] is not included in the e-mail message!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '.custom_builder_content_card_box [data-saveobjectid="mailtext"]'));
            }
        }
        if(!isset($own_content['mailsubject']) || empty($own_content['mailsubject'])) {
            array_push($echo_content, array('label' => __('No e-mail subject specified!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '.custom_builder_content_card_box [data-saveobjectid="mailsubject"]'));
        }
        if(!isset($own_content['mailto']) || empty($own_content['mailto'])) {
            array_push($echo_content, array('label' => __('No e-mail recipient specified!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '.custom_builder_content_card_box [data-saveobjectid="mailto"]'));
        }
        if(!isset($own_content['mailfrom']) || empty($own_content['mailfrom'])) {
            array_push($echo_content, array('label' => __('No sender e-mail specified!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '.custom_builder_content_card_box [data-saveobjectid="mailfrom"]'));
        }
        if(!isset($own_content['mailfrom_name']) || empty($own_content['mailfrom_name'])) {
            array_push($echo_content, array('label' => __('No sender name specified!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '.custom_builder_content_card_box [data-saveobjectid="mailfrom_name"]'));
        }
        if($own_content['use_smtp'] == 'true') {
            if(!isset($own_content['smtp_host']) || empty($own_content['smtp_host'])) {
                array_push($echo_content, array('label' => __('No SMTP server specified!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '.custom_builder_content_card_box [data-saveobjectid="smtp_host"]'));
            }
            if(!isset($own_content['smtp_username']) || empty($own_content['smtp_username'])) {
                array_push($echo_content, array('label' => __('No SMTP username specified!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '.custom_builder_content_card_box [data-saveobjectid="smtp_username"]'));
            }
            if(!isset($own_content['smtp_password']) || empty($own_content['smtp_password'])) {
                array_push($echo_content, array('label' => __('No SMTP password specified!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '.custom_builder_content_card_box [data-saveobjectid="smtp_password"]'));
            }
            if(!isset($own_content['smtp_port']) || empty($own_content['smtp_port'])) {
                array_push($echo_content, array('label' => __('No SMTP port specified!', 'funnelforms-free'), 'type' => 'af2_error', 'error_object' => '.custom_builder_content_card_box [data-saveobjectid="smtp_port"]'));
            }
        }


        array_push($echo_content, array('label' => __('Saved successfully!', 'funnelforms-free'), 'type' => 'af2_success'));


        $own_content['error'] = false;

        foreach( $echo_content as $content ) {
            if($content['type'] == 'af2_error') {
                $own_content['error'] = true;
                break;
            }
        }


        echo json_encode($echo_content);
        return $own_content;
    }

}
