<?php

class Tho_klaviyo_Form_Action extends \ElementorPro\Modules\Forms\Classes\Integration_Base {


    //$thofields = $record->get( 'fields' );

    //public $phoneFormName = '';
    private $log_file;
    
    public $phoneActive;
    public function __construct(){
        $this->phoneActive = apply_filters( 'include_klaviyo_phone_active', false);
        $this->log_file = plugin_dir_path( __FILE__ ) . 'debug.log';
    }

    public function get_name() {
        return 'tho_klaviyo_for_elementor_form';
    }

    public function get_label() {
        return __( 'Klaviyo');
    }

    public function run( $record, $ajax_handler ) {
        $settings = $record->get( 'form_settings' );

        //$subscriber = $this->map_fields( $record );

        //  Make sure that there is a API key
        if ( empty( $settings['klaviyo_key'] ) ) {
            return;
        }

        //  Make sure that there is a list ID
        if ( empty( $settings['klaviyo_list'] ) ) {
            return;
        }


        // Get submitetd Form data
        $raw_fields = $record->get( 'fields' );

        // Normalize the Form Data
        $fields = [];
        foreach ( $raw_fields as $id => $field ) {
            $fields[ $id ] = $field['value'];
        }

        // Make sure that there is a Email field if using custom ID

        if ( !empty( $settings['source_name'] ) ) {

            $source = $settings['source_name'];

            $fields[ 'source_name' ] = $source;

        }

        // Make sure that there is a Email field if using custom ID

       if ( !empty( $settings['klaviyo_email'] ) ) {

            $email_id = $settings['klaviyo_email'];

            $fields[ 'email' ] = $fields[ $email_id ];

            if($email_id != 'email'){
                unset($fields[ $email_id ]);
            }

        }

         if($this->phoneActive === true){

                $nationcode = '';
                if(!empty( $settings['klaviyo_phone_nation_code'])){
                    $nationcode = $settings['klaviyo_phone_nation_code'];
                }

                if ( !empty( $settings['klaviyo_phone'] ) ) {

                $phone_id = $settings['klaviyo_phone'];

                $phone_num = preg_replace('~\D~', '', $fields[ $phone_id ]);

                $phone_num = $nationcode.$phone_num;

                //$phone_num = 'tel:'.$phone_num;

                $fields[ 'phone_number' ] = $phone_num;

                if($phone_id != 'phone_number'){
                    unset($fields[ $phone_id ]);
                }

            }

         }

        if ( !empty( $settings['klaviyo_fname'] ) ) {

            $fname_id = $settings['klaviyo_fname'];

            $fields[ 'first_name' ] = $fields[ $fname_id ];

            if($fname_id != 'first_name'){
                unset($fields[ $fname_id ]);
            }

        }

         if ( !empty( $settings['klaviyo_lname'] ) ) {

            $lname_id = $settings['klaviyo_lname'];

            $fields[ 'last_name' ] = $fields[ $lname_id ];

            if($lname_id != 'last_name'){
                unset($fields[ $lname_id ]);
            }

        }

        if ( $settings['klaviyo_consent_email'] == 'enable') {
            $fields[ '$consent' ] = 'email';
        }


        // If we got this far we can start building our request data
        // Based on the list API at https://www.klaviyo.com/docs/api/v2/lists
        $pkkey = preg_replace('/\s+/', '', $settings['klaviyo_key']);
        $list_id = preg_replace('/\s+/', '', $settings['klaviyo_list']);

        $klaviyo_data = [
            'api_key' => $pkkey,
            'profiles' => array((object)$fields)
        ];

        if ( !empty( $settings['klaviyo_action'] ) ) {

            $action = $settings['klaviyo_action'];

        }

        //Add filter for custom action
    //     $form_name = apply_filters( 'include_klaviyo_sub_form', '' );
    //     $defaultAction = 'members';
    //     $action = apply_filters( 'include_klaviyo_action', $defaultAction );
    //    if($form_name != ''){
    //         $current_form_name = $record->get_form_settings( 'form_name' );
    //         if($form_name == $current_form_name){$action = 'subscribe';}else{
    //             $action = $defaultAction;
    //         }
    //     }


        // Send the request using Klaviyo API

        $request = wp_remote_retrieve_body(wp_remote_post( "https://a.klaviyo.com/api/v2/list/".$list_id."/".$action, [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($klaviyo_data)
        ]));

        //error_log( print_r( $klaviyo_data, true ) );

        if ( $settings['klaviyo_debug'] == 'activate') {
            $log_handle = fopen( $this->log_file, 'a' );
            $message = $klaviyo_data;
            $message['api_key'] = '';
            $message['action'] = $action;
            $messsage['consent'] = $settings['klaviyo_consent_email'];
            $message['response'] = $request;
            $message = print_r($message, true);
            fwrite( $log_handle, $message . "\n" );
            fclose( $log_handle );
        }

        //error_log( print_r( $action, true ) );

        //error_log( print_r( $request, true ) );

    }

    public function register_settings_section( $widget) {

        //$settings = $record->get( 'form_settings' );

         $widget->start_controls_section(
            'klaviyo_setting',
            [
                'label' => __( 'Klaviyo setting'),
                'condition' => [
                    'submit_actions' => $this->get_name(),
                ],
            ]
        );

        $widget->add_control(
            'klaviyo_key',
            [
                'label' => __( 'Setup guide: <a href="https://youtu.be/H4SApK4CRM8" target="_blank"><b>Here</b></a></br>klaviyo private API key - <b>REQUIRED</b>' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'separator' => 'before',

                'description' => __( 'Enter your klaviyo private API key <a href="https://help.klaviyo.com/hc/en-us/articles/115005062267-Manage-Your-Account-s-API-Keys" target="_blank"><b>more detail here</b></a>' ),
            ]
        );

        $widget->add_control(
            'klaviyo_list',
            [
                'label' => __( 'klaviyo List ID - <b>REQUIRED</b>' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'separator' => 'before',
                'description' => __( 'the list id you want to add/subscribe a user to <a href="https://help.klaviyo.com/hc/en-us/articles/115005078647-Find-a-List-ID#find-your-list-id0" target="_blank"><b>more detail here</b></a>' ),
            ]
        );

        $widget->add_control(
            'source_name',
            [
                'label' => __( 'Source Name - <b>OPTIONAL</b>' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'separator' => 'before',
                'description' => __( 'enter the "source name" you want to include to the property’s destination list' ),
            ]
        );

        $widget->add_control(
            'klaviyo_email',
            [
                'label' => __( 'Email Field ID - <b>REQUIRED</b>'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'separator' => 'before',
                'description' => __( 'enter ID for email feild' ),
            ]
        );

        $widget->add_control(
            'klaviyo_consent_email',
            [
                'label' => __( 'Enable Email Consent' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'disable' => ( 'Disable' ),
                    'enable' => ( 'Enable' ),
                ],
                'default' => 'disable',
                'label_block' => true,
                'separator' => 'before',
                'description' => __( 'Enable Email Consent' ),
            ]
        );

        $widget->add_control(
            'klaviyo_action',
            [
                'label' => __( 'Action' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'members' => ( 'Members' ),
                    'subscribe' => ( 'Subscribe' ),
                ],
                'default' => 'members',
                'label_block' => true,
                'separator' => 'before',
                'description' => __( 'Action to do' ),
            ]
        );

        if($this->phoneActive === true){
                $widget->add_control(
                'klaviyo_phone',
                [
                    'label' => __( 'Phone Field ID - <b>OPTIONAL</b>'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'label_block' => true,
                    'separator' => 'before',
                    'description' => __( 'enter ID for phone feild if you want to auto merge phone number to your list <b style="color:black;">Note: due to Klaviyo require, phone number without nation code will cause submit to fail</b>' ),
                ]
            );

            $widget->add_control(
                'klaviyo_phone_nation_code',
                [
                    'label' => __( 'Nation Code'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'label_block' => true,
                    'separator' => 'before',
                    'description' => __( 'enter the Nation Code incase you don\'t want your audience must to add it' ),
                ]
            );
        }

        $widget->add_control(
            'klaviyo_fname',
            [
                'label' => __( 'First name Field ID - <b>OPTIONAL</b>'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'separator' => 'before',
                'description' => __( 'enter ID for first name feild if you want to auto merge phone number to your list' ),
            ]
        );


        $widget->add_control(
            'klaviyo_lname',
            [
                'label' => __( 'Last name Field ID - <b>OPTIONAL</b>'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'separator' => 'before',
                'description' => __( 'enter ID for last name feild if you want to auto merge phone number to your list' ),
            ]
        );

        $widget->add_control(
            'klaviyo_debug',
            [
                'label' => __( 'Debug Log' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'activate' => ( 'Activate' ),
                    'deactivate' => ( 'Deactivate' ),
                ],
                'default' => 'deactivate',
                'label_block' => true,
                'separator' => 'before',
                'description' => __( 'Enable Log for debug' ),
            ]
        );


        $widget->end_controls_section();
    }

    private function tho_get_fields_id(){
        //error_log(print_r($record, true));
    }

    public function on_export( $element ) {
          unset(
            $element['klaviyo_key'],
            $element['klaviyo_list'],
            $element['klaviyo_email'],
            $element['source_name'],
            $element['klaviyo_fname'],
            $element['klaviyo_lname'],
            $element['klaviyo_consent_email'],
            $element['klaviyo_action'],
            $element['klaviyo_debug']
        );

          //if($this->phoneActive === true){
            unset($element['klaviyo_phone']);
            unset($element['klaviyo_phone_nation_code']);
          //}

        return $element;
    }

}

