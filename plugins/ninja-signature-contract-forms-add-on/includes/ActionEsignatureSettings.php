<?php if ( ! defined( 'ABSPATH' ) ) exit;

if (!function_exists('WP_E_Sig'))
return;



$form_id = ESIG_GET('form_id');     




return apply_filters( 'ninja_forms_action_esignature_settings', array(

    /*
    |--------------------------------------------------------------------------
    | Primary Settings
    |--------------------------------------------------------------------------
    */

    /*
     * Signer name
     */  

    'signer_name' => array(
        'name' => 'signer_name',
        'type' => 'textbox',
        'group' => 'primary',
        'label' => __('Signer Name', 'esign'),
        'placeholder' => __('Name or fields', 'esign'),
        'value' => '',
        'help' => __('Please input a signer Name field this will be used as signer name', 'esign'),
        'width' => 'full',
        'use_merge_tags' => TRUE,
    ),  
    
    
    /*
     * Signer email
     */

    'signer_email' => array(
        'name' => 'signer_email',
        'type' => 'field-select',
        'group' => 'primary',
        'width' => 'full',
        'label' => __( 'SIGNER EMAIL', 'esign' ),
        'help' => __('Please input a signer E-mail field this will be used as signer email address', 'ninja-forms'),
        'field_types' => array(
            'email',
        ),
    ),  

    /*
     * Signing Logic
     */

    'signing_logic' => array(
        'name' => 'signing_logic',
        'type' => 'select',
            'options' => array(
                array( 'label' => __( 'Redirect user to Contract/Agreement after Submission', 'esign' ), 'value' => 'redirect' ),
                array( 'label' => __( 'Send User an Email Requesting their Signature after Submission', 'esign' ), 'value' => 'email' )
            ),
        'group' => 'primary',
        'width' => 'full',
        'label' => __( 'Signing logic', 'esign' ),
        'value' => 'redirect'
        
    ),
    'underline_data' => array(
        'name' => 'underline_data',
        'type' => 'select',
            'options' => array(
                array( 'label' => __( 'Underline the data That was submitted from this ninja form', 'esign' ), 'value' => 'underline' ),
                array( 'label' => __( 'Do not underline the data that was submitted from the Ninja Form', 'esign' ), 'value' => 'not_under' )
            ),
        
        'group' => 'primary',
        'width' => 'full',
        'value' => 'underline',
    ),
    
    'select_sad' => array(
        'name' => 'select_sad',
        'type' => 'select',
            'options' =>  Esig_NF_Setting::get_sad_option()
            ,
        'group' => 'primary',
        'width' => 'full',
        'label' => __( 'Select Sad', 'esign' ),
    ),  
    'signing_reminder_email' => array(
        'name' => 'signing_reminder_email',
        'type' => 'toggle',  
        'label' => __( 'Signing Reminder Email', 'esign' ),
        'width' => 'full',
        'group' => 'advanced',
        
    ),
     'reminder_email' => array(
        'name' => 'reminder_email',
        'type' => 'select',

        'group' => 'advanced',
        'width' => 'one-third',
        'label' => __( 'Send the first reminder to the signer this many days after the initial signing request: ', 'esign' ),
        'options' =>  ESIG_NF_SETTING::generate_reminder_date()
       
    ),
   
    'first_reminder_send' => array(
        'name' => 'first_reminder_send',
        'type' => 'select',
     
        'group' => 'advanced',
       'width' => 'one-third',
        'label' => __( 'Send the second reminder to the signer this many days after the initial signing request:', 'esign' ),
        'options' =>  ESIG_NF_SETTING::generate_reminder_date(),
    ),
    
    'expire_reminder' => array(
        'name' => 'expire_reminder',
        'type' => 'select',
        'group' => 'advanced',
        'width' => 'one-third',
        'label' => __( 'Send the last reminder to the signer this many days after the initial signing request:', 'esign' ),
        'options' =>  ESIG_NF_SETTING::generate_reminder_date()
    ),
   

   
));
