<?php
if (!defined('ABSPATH')) die('-1');

class VCE_fmcLeadGen extends VCE_component {
    function __construct() {
        parent::__construct();

        $this->vars = $this->integration_view_vars();

        add_action( 'init', array( $this, 'integrateWithVC' ) );
    }

    protected function integration_view_vars(){
    
        $vars = array();
        $vars['title'] = 'Lead Generation';
        $vars["title_description"] = flexmlsConnect::special_location_tag_text();
        $vars['blurb'] = '';
        $vars['success_message'] = 'Thank you for your request';
        $vars['buttontext'] = "Submit";
        $vars["use_captcha"] = 'yes';
    
        return $vars;
    }

    protected function setParams(){       

        extract($this->vars);

        $fmc_params = array(
            array(
                "type" => 'textfield',
                "heading" => 'Title',
                "param_name" => 'title',
                "value" => $title,
                "description" => $title_description,
                'admin_label' => true,
                'std' => $title
            ),
            array(
                "type" => 'textarea',
                "heading" => 'Description',
                "param_name" => 'blurb',
                "value" => $blurb,
                "description" => 'This text appears below the title',
            ),
            array(
                "type" => 'textarea',
                "heading" => 'Success Message',
                "param_name" => 'success',
                "value" => $success_message,
                "description" => 'This text appears after the user sends the information',
            ),
            array(
                "type" => 'textfield',
                "heading" => 'Button Text',
                "param_name" => "buttontext",
                "value" => $buttontext,
                "description" => 'Customize the text of the submit button',
                'admin_label' => true
            ),
            array(
                'type' => 'checkbox',
                "heading" => 'Use Captcha?',
                "param_name" => 'use_captcha',
                "value" => $use_captcha,
                'admin_label' => true
            )
        );

        return $fmc_params;
    }
}

new VCE_fmcLeadGen();