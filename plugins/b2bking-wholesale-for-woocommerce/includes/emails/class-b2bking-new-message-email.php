<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class B2bkingcore_New_Message_Email extends WC_Email {

    public function __construct() {

        // set ID, this simply needs to be a unique name
        $this->id = 'b2bking_new_message_email';

        // this is the title in WooCommerce Email settings
        $this->title = esc_html__('New message (B2BKing Premium)', 'b2bking');

        // this is the description in WooCommerce email settings
        $this->description = esc_html__('This email is sent when a new message (quote request, or user inquiry, or user conversation) is sent', 'b2bking');

        // these are the default heading and subject lines that can be overridden using the settings
        $this->heading = esc_html__('New message (conversations)', 'b2bking');
        $this->subject = esc_html__('New message (conversations)', 'b2bking');

        $this->template_base  = B2BKINGCORE_DIR . 'includes/emails/templates/';
        $this->template_html  = 'new-message-email-template.php';
        $this->template_plain =  'plain-new-message-email-template.php';
        
        // Call parent constructor to load any other defaults not explicity defined here
        parent::__construct();

        add_action( 'b2bking_new_message_notification', array( $this, 'trigger'), 10, 4 );

    }

    public function trigger($email_address, $message, $userid, $conversationid) {

        $this->recipient = $email_address;
        $this->message = $message;
        $this->userid = $userid;
        $this->conversationid = $conversationid;

        if ( ! $this->is_enabled() || ! $this->get_recipient() ){
           return;
        }
        
        $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
     
    }

    public function get_content_html() {
        ob_start();
        if (method_exists($this, 'get_additional_content')){
            $additional_content_checked = $this->get_additional_content();
        } else {
            $additional_content_checked = false;
        }
        wc_get_template( $this->template_html, array(
            'email_heading'      => $this->get_heading(),
            'additional_content' => $additional_content_checked,
            'message'            => $this->message,
            'userid'             => $this->userid,
            'conversationid'     => $this->conversationid,
            'email'              => $this,
        ), $this->template_base, $this->template_base  );
        return ob_get_clean();
    }


    public function get_content_plain() {
        ob_start();
        if (method_exists($this, 'get_additional_content')){
            $additional_content_checked = $this->get_additional_content();
        } else {
            $additional_content_checked = false;
        }
        wc_get_template( $this->template_plain, array(
            'email_heading'      => $this->get_heading(),
			'additional_content' => $additional_content_checked,
            'message'            => $this->message,
            'userid'             => $this->userid,
            'conversationid'     => $this->conversationid,
			'email'              => $this,
        ), $this->template_base, $this->template_base );
        return ob_get_clean();
    }

    public function init_form_fields() {

        $this->form_fields = array(
            'enabled'    => array(
                'title'   => esc_html__( 'Enable/Disable', 'b2bking' ),
                'type'    => 'checkbox',
                'label'   => esc_html__( 'Enable this email notification', 'b2bking' ),
                'default' => 'yes',
            ),
            'subject'    => array(
                'title'       => 'Subject',
                'type'        => 'text',
                'description' => esc_html__('This controls the email subject line. Leave blank to use the default subject: ','b2bking').sprintf( '<code>%s</code>.', $this->subject ),
                'placeholder' => '',
                'default'     => ''
            ),
            'heading'    => array(
                'title'       => esc_html__('Email Heading','b2bking'),
                'type'        => 'text',
                'description' => esc_html__('This controls the main heading contained within the email notification. Leave blank to use the default heading: ','b2bking').sprintf( '<code>%s</code>.', $this->heading ),
                'placeholder' => '',
                'default'     => ''
            ),
            'email_type' => array(
                'title'       => esc_html__('Email type','b2bking'),
                'type'        => 'select',
                'description' => esc_html__('Choose which format of email to send.','b2bking'),
                'default'     => 'html',
                'class'       => 'email_type',
                'options'     => array(
                    'plain'     => 'Plain text',
                    'html'      => 'HTML', 'woocommerce',
                    'multipart' => 'Multipart', 'woocommerce',
                )
            )
        );
    }

}
return new B2bkingcore_New_Message_Email();