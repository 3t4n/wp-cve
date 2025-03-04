<?php

namespace km_message_filter;

$km_wp_forms_spam_status = false;
class WpFormsModule extends Module
{
    private static  $instance ;
    private  $count_updated = false ;
    private  $spam_word_error ;
    private  $spam_email_error ;
    private  $form_fields = array() ;
    private  $fields = array() ;
    private  $form_id ;
    private  $prevent_default_validation ;
    public function __construct()
    {
        parent::__construct();
        $this->prevent_default_validation = ( get_option( 'kmcfmf_hide_error_message' ) == 'on' ? true : false );
        $this->getErrorMessages();
        self::$instance = $this;
    }
    
    /**
     * Retrieves custom error messages
     * @since v1.4.0
     */
    private function getErrorMessages()
    {
        $this->spam_word_error = ( get_option( 'kmcfmf_spam_word_error', false ) ? get_option( 'kmcfmf_spam_word_error' ) : __( "One or more fields have an error. Please check and try again.", 'contact-form-7' ) );
        $this->spam_email_error = ( get_option( 'kmcfmf_spam_email_error', false ) ? get_option( 'kmcfmf_spam_email_error' ) : __( 'The e-mail address entered is invalid.', KMCF7MS_TEXT_DOMAIN ) );
    }
    
    /**
     * @since v1.4.0
     * Gets and group all tags from wp forma forms into three categories: email, text and textarea
     * @returns  array
     */
    public static function getTags()
    {
        $email = array( array(
            'text'  => '*',
            'value' => '*',
        ) );
        $text = array( array(
            'text'  => '*',
            'value' => '*',
        ) );
        $textarea = array( array(
            'text'  => '*',
            'value' => '*',
        ) );
        
        if ( function_exists( 'wpforms' ) ) {
            $args['post_status'] = 'publish';
            $forms = wpforms()->get( 'form' )->get( '', $args );
            foreach ( $forms as $form ) {
                $content = json_decode( $form->post_content, true );
                $fields = $content['fields'];
                foreach ( $fields as $field ) {
                    switch ( $field['type'] ) {
                        case 'email':
                            array_push( $email, array(
                                'text'  => $field['label'],
                                'value' => $field['label'],
                            ) );
                            break;
                        case 'text':
                        case 'name':
                            array_push( $text, array(
                                'text'  => $field['label'],
                                'value' => $field['label'],
                            ) );
                            break;
                        case 'textarea':
                            array_push( $textarea, array(
                                'text'  => $field['label'],
                                'value' => $field['label'],
                            ) );
                            break;
                    }
                }
            }
        }
        
        $tags = array(
            'text'     => array_unique( $text, SORT_REGULAR ),
            'textarea' => array_unique( $textarea, SORT_REGULAR ),
            'email'    => array_unique( $email, SORT_REGULAR ),
        );
        return $tags;
    }
    
    /**
     * @since v1.4.8
     * Gets all registered WP forms
     * @returns  array
     */
    public static function getForms()
    {
        $forms = array();
        
        if ( function_exists( 'wpforms' ) ) {
            $args['post_status'] = 'publish';
            $wp_forms = wpforms()->get( 'form' )->get( '', $args );
            foreach ( $wp_forms as $form ) {
                array_push( $forms, array(
                    'text'  => $form->post_title,
                    'value' => $form->ID,
                ) );
            }
        }
        
        return $forms;
    }
    
    /**
     * Filters text from form text elements from elems_names List
     * @since 1.4.0
     */
    function textValidationFilter( $errors, $form_data )
    {
        $fields = $_POST['wpforms']['fields'];
        $form_fields = $form_data['fields'];
        $this->form_fields = $form_fields;
        $this->fields = $fields;
        $this->form_id = sanitize_text_field( $_POST['wpforms']['id'] );
        $names = explode( ',', get_option( 'kmcfmf_wp_forms_text_fields' ) );
        $invalid_fields = ( empty($errors[$_POST['wpforms']['id']]) ? array() : $errors[$_POST['wpforms']['id']] );
        $is_exempted = $this->isFormExempted( $this->form_id );
        if ( !$is_exempted ) {
            
            if ( in_array( '*', $names ) ) {
                foreach ( $form_fields as $field ) {
                    if ( $field['type'] == 'name' || $field['type'] == 'text' ) {
                        
                        if ( $this->validateTextField( $fields[$field['id']] ) ) {
                            $invalid_fields[$field['id']] = $this->spam_word_error;
                            $errors[$_POST['wpforms']['id']] = $invalid_fields;
                            //						return $errors;
                        }
                    
                    }
                }
            } else {
                foreach ( $form_fields as $field ) {
                    if ( ($field['type'] == 'name' || $field['type'] == 'text') && in_array( $field['label'], $names ) ) {
                        
                        if ( $this->validateTextField( $fields[$field['id']] ) ) {
                            $invalid_fields[$field['id']] = $this->spam_word_error;
                            $errors[$_POST['wpforms']['id']] = $invalid_fields;
                            //						return $errors;
                        }
                    
                    }
                }
            }
        
        }
        return $errors;
    }
    
    /**
     * @since 1.4.8
     * Checks if the form is exempted from validation
     */
    private function isFormExempted( $id )
    {
        return false;
    }
    
    /**
     * Runs spam filter on text fields and text area fields
     * @since 1.4.0
     */
    private function validateTextField( $message )
    {
        global  $km_wp_forms_spam_status ;
        if ( is_array( $message ) ) {
            $message = implode( ' ', $message );
        }
        $filter = new Filter();
        $spam_word = $filter->validateTextField( $message );
        $return = false;
        // Spam word is recognized
        
        if ( $spam_word ) {
            $invalidate_field = $this->preventDefaultValidation();
            
            if ( $invalidate_field ) {
                $return = true;
            } else {
                $km_wp_forms_spam_status = true;
            }
            
            
            if ( !$this->count_updated ) {
                $data = array(
                    'word'    => $spam_word,
                    'form'    => 'wpforms',
                    'message' => json_encode( $this->getPostedData() ),
                    'form_id' => $this->form_id,
                );
                MessagesModule::getInstance()->updateDatabase( $data );
                $this->count_updated = true;
            }
            
            do_action( 'km_wp_forms_after_invalidate_text_field' );
        }
        
        return $return;
    }
    
    /**
     * Prevent default validation if a spam is found
     *
     * @return  bool
     *
     * @since v1.4.0
     */
    private function preventDefaultValidation()
    {
        
        if ( $this->prevent_default_validation ) {
            $this->removeActions__premium_only();
            return false;
        }
        
        return true;
    }
    
    /**
     * @since v1.4.0
     * Matches submitted data to their field names
     * @returns array
     */
    private function getPostedData()
    {
        $postedData = array();
        foreach ( $this->form_fields as $field ) {
            
            if ( is_array( $this->fields[$field['id']] ) ) {
                $postedData[$field['label']] = implode( ' ', $this->fields[$field['id']] );
            } else {
                $postedData[$field['label']] = $this->fields[$field['id']];
            }
        
        }
        return $postedData;
    }
    
    /**
     * @since v1.4.9
     * @author kofimokome
     */
    public static function getInstance() : WpFormsModule
    {
        return self::$instance;
    }
    
    /**
     * Filters text from textarea
     * @since 1.4.0
     */
    function textareaValidationFilter( $errors, $form_data )
    {
        $fields = $_POST['wpforms']['fields'];
        $form_fields = $form_data['fields'];
        $this->form_fields = $form_fields;
        $this->fields = $fields;
        $this->form_id = sanitize_text_field( $_POST['wpforms']['id'] );
        $names = explode( ',', get_option( 'kmcfmf_wp_forms_textarea_fields' ) );
        $invalid_fields = ( empty($errors[$_POST['wpforms']['id']]) ? array() : $errors[$_POST['wpforms']['id']] );
        $is_exempted = $this->isFormExempted( $this->form_id );
        if ( !$is_exempted ) {
            
            if ( in_array( '*', $names ) ) {
                foreach ( $form_fields as $field ) {
                    if ( $field['type'] == 'textarea' ) {
                        
                        if ( $this->validateTextField( $fields[$field['id']] ) ) {
                            $invalid_fields[$field['id']] = $this->spam_word_error;
                            $errors[$_POST['wpforms']['id']] = $invalid_fields;
                            //						return $errors;
                        }
                    
                    }
                }
            } else {
                foreach ( $form_fields as $field ) {
                    if ( $field['type'] == 'textarea' && in_array( $field['label'], $names ) ) {
                        
                        if ( $this->validateTextField( $fields[$field['id']] ) ) {
                            $invalid_fields[$field['id']] = $this->spam_word_error;
                            $errors[$_POST['wpforms']['id']] = $invalid_fields;
                            //						return $errors;
                        }
                    
                    }
                }
            }
        
        }
        return $errors;
    }
    
    /**
     * Filters text from email fields
     * @since 1.4.0
     */
    function emailValidationFilter( $errors, $form_data )
    {
        $fields = $_POST['wpforms']['fields'];
        $form_fields = $form_data['fields'];
        $this->form_fields = $form_fields;
        $this->fields = $fields;
        $this->form_id = sanitize_text_field( $_POST['wpforms']['id'] );
        $names = explode( ',', get_option( 'kmcfmf_wp_forms_email_fields' ) );
        $invalid_fields = ( empty($errors[$_POST['wpforms']['id']]) ? array() : $errors[$_POST['wpforms']['id']] );
        $is_exempted = $this->isFormExempted( $this->form_id );
        if ( !$is_exempted ) {
            
            if ( in_array( '*', $names ) ) {
                foreach ( $form_fields as $field ) {
                    if ( $field['type'] == 'email' ) {
                        
                        if ( $this->validateEmailField( $fields[$field['id']] ) ) {
                            $invalid_fields[$field['id']] = $this->spam_email_error;
                            $errors[$_POST['wpforms']['id']] = $invalid_fields;
                            //						return $errors;
                        }
                    
                    }
                }
            } else {
                foreach ( $form_fields as $field ) {
                    if ( $field['type'] == 'email' && in_array( $field['label'], $names ) ) {
                        
                        if ( $this->validateEmailField( $fields[$field['id']] ) ) {
                            $invalid_fields[$field['id']] = $this->spam_email_error;
                            $errors[$_POST['wpforms']['id']] = $invalid_fields;
                            //						return $errors;
                        }
                    
                    }
                }
            }
        
        }
        return $errors;
    }
    
    /**
     * Runs spam filter on email fields
     * @return bool
     * @since v1.4.0
     */
    private function validateEmailField( $value )
    {
        global  $km_wp_forms_spam_status ;
        if ( is_array( $value ) ) {
            $value = implode( ' ', $value );
        }
        $filter = new Filter();
        $spam = $filter->validateEmail( $value );
        $return = false;
        
        if ( $spam ) {
            $invalidate_field = $this->preventDefaultValidation();
            
            if ( $invalidate_field ) {
                $return = true;
            } else {
                $km_wp_forms_spam_status = true;
            }
            
            
            if ( !$this->count_updated ) {
                $data = array(
                    'email'   => $spam,
                    'form'    => 'wpforms',
                    'message' => json_encode( $this->getPostedData() ),
                    'form_id' => $this->form_id,
                );
                MessagesModule::getInstance()->updateDatabase( $data );
                $this->count_updated = true;
            }
            
            do_action( 'km_wp_forms_after_invalidate_email_field' );
        }
        
        return $return;
    }
    
    /**
     * Skips sending of mails on submission if a spam word is found
     * @return bool
     * @since v1.4.0
     */
    public function skipMail( $value, $object )
    {
        global  $km_wp_forms_spam_status ;
        if ( $km_wp_forms_spam_status && $this->prevent_default_validation ) {
            return true;
        }
        return $value;
    }
    
    /**
     * Prevents sending of mails if skipMail() fails prevent sending of mails
     * @return array
     * @since v1.4.0
     */
    public function removeEmailAddress(
        $email,
        $fields,
        $entry,
        $form_data,
        $notification_id
    )
    {
        global  $km_wp_forms_spam_status ;
        if ( $km_wp_forms_spam_status && $this->prevent_default_validation ) {
            $email['address'] = array();
        }
        return $email;
    }
    
    protected function addFilters()
    {
        parent::addFilters();
        $enable_message_filter = ( get_option( 'kmcfmf_message_filter_toggle' ) == 'on' ? true : false );
        $enable_email_filter = ( get_option( 'kmcfmf_email_filter_toggle' ) == 'on' ? true : false );
        $enable_wp_form_filter = ( get_option( 'kmcfmf_enable_wp_forms_toggle' ) == 'on' ? true : false );
        if ( $enable_email_filter && $enable_wp_form_filter ) {
            add_filter(
                'wpforms_process_initial_errors',
                array( $this, 'emailValidationFilter' ),
                999,
                2
            );
        }
        
        if ( $enable_message_filter && $enable_wp_form_filter ) {
            add_filter(
                'wpforms_process_initial_errors',
                array( $this, 'textValidationFilter' ),
                999,
                2
            );
            add_filter(
                'wpforms_process_initial_errors',
                array( $this, 'textareaValidationFilter' ),
                999,
                2
            );
        }
    
    }
    
    protected function addActions()
    {
        parent::addActions();
        add_action(
            'wpforms_disable_all_emails',
            array( $this, 'skipMail' ),
            999,
            2
        );
        add_action(
            'wpforms_entry_email_atts',
            array( $this, 'removeEmailAddress' ),
            999,
            5
        );
    }

}