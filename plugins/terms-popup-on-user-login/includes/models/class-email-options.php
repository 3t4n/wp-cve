<?php

class TPUL_Email_Options {


    private $options = [];

    private $options_name = 'tpul_email_options';
    private $section_id = "email_section";

    private $defaults = array(
        'email_send_to_user'          =>    false,
        'email_send_to_admins'        =>    true,
        'email_notify_about_anonymous'        =>    false,
        'email_admin_addr'        =>    '',
        'email_subject'         =>    "Confirmation - You've Accepted Our Terms",
        'email_text_content'    =>  "Dear [user-name],
        
    We hope this message finds you well! We are writing to inform you that we have received your acceptance of the Terms and Conditions for using our website, [website-url]. Your commitment to adhering to these terms is greatly appreciated.
    
    Your acceptance signifies your understanding and agreement to the rules and guidelines that govern the use of our platform. These terms are in place to ensure a safe, enjoyable, and secure experience for all our users. You can review the Terms and Conditions by clicking on the following link:
    
    Terms and Conditions Page LINK TO PAGE
    
    As a valued member of our community, your continued support means a lot to us. If you have any questions or concerns regarding the Terms and Conditions or any other aspect of our platform, please don't hesitate to reach out to our customer support team. We are here to assist you.
    
    Thank you for choosing [website-name]. We are excited to have you as part of our community and look forward to providing you with an exceptional online experience.
    
    Best regards,
    
    YOUR NAME
    YOUR TITLE / POSITION
    [website-name] Team
    CONTACT INFORMATION
        ",
    );


    public function __construct() {
        $this->options = get_option($this->options_name);
    }

    public function default_options() {
        return $this->defaults;
    }

    public function get_section_id() {
        return $this->options_name;
    }
    public function get_option_name() {
        return $this->options_name;
    }

    public function get_options() {
        if (false ==  $this->options) {
            return $this->default_options();
        }
        return $this->options;
    }
}
