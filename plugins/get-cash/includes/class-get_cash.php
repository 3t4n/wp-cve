<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( !class_exists( 'Get_Cash' ) ) {
    class Get_Cash
    {
        private  $get_cash_options ;
        public function __construct()
        {
            if ( is_admin() ) {
                add_action( 'admin_init', array( $this, 'get_cash_page_init' ) );
            }
        }
        
        public function get_cash_option( $option )
        {
            if ( empty($option) ) {
                return '';
            }
            // if ( !empty( $this->get_cash_options ) ) {
            // 	$get_cash_options = $this->get_cash_options;
            // } else {
            // 	$get_cash_options = get_option( 'get_cash_option_name' ); // Array of All Options
            // 	$this->get_cash_options = $get_cash_options;
            // }
            $get_cash_options = get_option( 'get_cash_option_name' );
            // Array of All Options
            return ( is_array( $get_cash_options ) && array_key_exists( $option, $get_cash_options ) ? wp_kses_post( $get_cash_options[$option] ) : '' );
        }
        
        public function get_cash_page_init()
        {
            register_setting(
                'get_cash_option_group',
                // option_group
                'get_cash_option_name',
                // option_name
                array( $this, 'get_cash_sanitize' )
            );
            $new = " <sup style='color:#0c0;'>NEW</sup>";
            $improved = " <sup style='color:#0c0;'>IMPROVED</sup>";
            $comingSoon = " <sup style='color:#00c;'>COMING SOON</sup>";
            global  $getcash_fs ;
            $upgrade_url = getcash_fs()->get_upgrade_url();
            $pro = '<a style="text-decoration:none" href="' . $upgrade_url . '" target="_blank"><sup style="color:red">available in PRO</sup></a>' . '<p>Cash App/Venmo/PayPal logos and specifying amount in shortcode <a style="text-decoration:none" href="https://theafricanboss.com/get-cash/" target="_blank"><sup style="color:red">also available in PRO</sup></a></p>';
            $edit_with_pro = ' <a style="text-decoration:none" href="' . $upgrade_url . '" target="_blank"><sup style="color:red">APPLY CHANGES WITH PRO</sup></a>';
            /*
             * Section Payments info
             */
            add_settings_section(
                'get_cash_required_info_section',
                // id
                'Add Receiver info',
                // title
                array( $this, 'get_cash_section_info' ),
                // callback
                'get-cash-admin'
            );
            add_settings_field(
                'receiver_cash_app',
                // id
                'Add your Cash App $cashtag (example: our cashtag is <a href="https://cash.app/theafricanboss/1" target="_blank">$theafricanboss</a>)',
                // title
                array( $this, 'get_cash_receiver_cash_app_callback' ),
                // callback
                'get-cash-admin',
                // page
                'get_cash_required_info_section'
            );
            add_settings_field(
                'receiver_venmo',
                // id
                'Add your venmo username (example: our username is <a href="https://venmo.com/theafricanboss?txn=pay&amount=1&note=Thank you for the plugin" target="_blank">theafricanboss</a>)',
                // title
                array( $this, 'get_cash_receiver_venmo_callback' ),
                // callback
                'get-cash-admin',
                // page
                'get_cash_required_info_section'
            );
            add_settings_field(
                'receiver_paypal',
                // id
                'Add your PayPal.me username (example: our username is <a href="https://paypal.me/theafricanboss/1" target="_blank">theafricanboss</a>)',
                // title
                array( $this, 'get_cash_receiver_paypal_callback' ),
                // callback
                'get-cash-admin',
                // page
                'get_cash_required_info_section'
            );
            /*
             * Section Zelle info
             */
            add_settings_section(
                'get_cash_additional_info_section',
                // id
                'Input Zelle or Additional info',
                // title
                array( $this, 'get_cash_section_additional_info' ),
                // callback
                'get-cash-admin'
            );
            add_settings_field(
                'receiver_no',
                // id
                'Receiver Phone Number',
                // title
                array( $this, 'get_cash_receiver_no_callback' ),
                // callback
                'get-cash-admin',
                // page
                'get_cash_additional_info_section'
            );
            add_settings_field(
                'receiver_email',
                // id
                'Receiver Email',
                // title
                array( $this, 'get_cash_receiver_email_callback' ),
                // callback
                'get-cash-admin',
                // page
                'get_cash_additional_info_section'
            );
            add_settings_field(
                'receiver_owner',
                // id
                'Receiver Name',
                // title
                array( $this, 'get_cash_receiver_owner_callback' ),
                // callback
                'get-cash-admin',
                // page
                'get_cash_additional_info_section'
            );
            /*
             * Section PRO
             */
            add_settings_section(
                'get_cash_premium_features_section',
                // id
                'Premium Features' . $pro,
                // title
                array( $this, 'get_cash_section_premium_features' ),
                // callback
                'get-cash-admin'
            );
            add_settings_field(
                'donate_button_text',
                // id
                'Change Donate Button Text' . $edit_with_pro,
                // title
                array( $this, 'get_cash_donate_button_text_callback' ),
                // callback
                'get-cash-admin',
                // page
                'get_cash_premium_features_section'
            );
            add_settings_field(
                'donate_button_display',
                // id
                'Full width Centered On/Off' . $edit_with_pro,
                // title
                array( $this, 'get_cash_donate_button_display_callback' ),
                // callback
                'get-cash-admin',
                // page
                'get_cash_premium_features_section'
            );
            add_settings_field(
                'donate_button_shadow',
                // id
                'Shadow On/Off' . $edit_with_pro,
                // title
                array( $this, 'get_cash_donate_button_shadow_callback' ),
                // callback
                'get-cash-admin',
                // page
                'get_cash_premium_features_section'
            );
        }
        
        /*
         * Fields sanitize function
         */
        public function get_cash_sanitize( $input )
        {
            $sanitary_values = array();
            if ( isset( $input['receiver_cash_app'] ) ) {
                // $cash_app = strpos($input['receiver_cash_app'], '$') !== false ? $input['receiver_cash_app'] : '$' . $input['receiver_cash_app'];
                // $sanitary_values['receiver_cash_app'] = trim(wp_kses_post( sanitize_text_field( str_replace(' ', '', $cash_app) ) ));
                $sanitary_values['receiver_cash_app'] = trim( wp_kses_post( sanitize_text_field( $input['receiver_cash_app'] ) ) );
            }
            if ( isset( $input['receiver_venmo'] ) ) {
                $sanitary_values['receiver_venmo'] = trim( wp_kses_post( sanitize_text_field( str_replace( ' ', '', str_replace( '@', '', $input['receiver_venmo'] ) ) ) ) );
            }
            if ( isset( $input['receiver_paypal'] ) ) {
                $sanitary_values['receiver_paypal'] = trim( wp_kses_post( sanitize_text_field( str_replace( ' ', '', $input['receiver_paypal'] ) ) ) );
            }
            if ( isset( $input['receiver_no'] ) ) {
                $sanitary_values['receiver_no'] = trim( wp_kses_post( sanitize_text_field( $input['receiver_no'] ) ) );
            }
            if ( isset( $input['receiver_owner'] ) ) {
                $sanitary_values['receiver_owner'] = trim( wp_kses_post( sanitize_text_field( $input['receiver_owner'] ) ) );
            }
            if ( isset( $input['receiver_email'] ) ) {
                $sanitary_values['receiver_email'] = trim( wp_kses_post( sanitize_text_field( $input['receiver_email'] ) ) );
            }
            if ( isset( $input['donate_button_text'] ) ) {
                $sanitary_values['donate_button_text'] = trim( wp_kses_post( sanitize_text_field( $input['donate_button_text'] ) ) );
            }
            if ( isset( $input['donate_button_shadow'] ) ) {
                $sanitary_values['donate_button_shadow'] = wp_kses_post( $input['donate_button_shadow'] );
            }
            if ( isset( $input['donate_button_display'] ) ) {
                $sanitary_values['donate_button_display'] = wp_kses_post( $input['donate_button_display'] );
            }
            return $sanitary_values;
        }
        
        /*
         * Sections callback functions
         */
        public function get_cash_section_info()
        {
            echo  __( '', GET_CASH_PLUGIN_TEXT_DOMAIN ) ;
        }
        
        public function get_cash_section_additional_info()
        {
            echo  __( '', GET_CASH_PLUGIN_TEXT_DOMAIN ) ;
        }
        
        public function get_cash_section_premium_features()
        {
            echo  __( '', GET_CASH_PLUGIN_TEXT_DOMAIN ) ;
        }
        
        /*
         * Fields callback functions
         */
        public function get_cash_receiver_cash_app_callback()
        {
            
            if ( !empty($this->get_cash_option( 'receiver_cash_app' )) ) {
                $test = '<a class="link-primary" href="https://cash.me/' . $this->get_cash_option( 'receiver_cash_app' ) . '" target="_blank">Test</a>';
            } else {
                $test = null;
            }
            
            printf( '<input class="gc-text" type="text" name="get_cash_option_name[receiver_cash_app]" id="receiver_cash_app" value="%s"> ' . $test, $this->get_cash_option( 'receiver_cash_app' ) );
        }
        
        public function get_cash_receiver_venmo_callback()
        {
            
            if ( !empty($this->get_cash_option( 'receiver_venmo' )) ) {
                $test = '<a class="link-primary" href="https://venmo.com/' . esc_attr( wp_kses_post( $this->get_cash_option( 'receiver_venmo' ) ) ) . '?txn=pay&amount=0.01&note=Thank you" target="_blank">Test</a>';
            } else {
                $test = null;
            }
            
            printf( '<input class="gc-text" type="text" name="get_cash_option_name[receiver_venmo]" id="receiver_venmo" value="%s">' . $test, $this->get_cash_option( 'receiver_venmo' ) );
        }
        
        public function get_cash_receiver_paypal_callback()
        {
            
            if ( !empty($this->get_cash_option( 'receiver_paypal' )) ) {
                $test = '<a class="link-primary" href="https://paypal.me/' . $this->get_cash_option( 'receiver_paypal' ) . '" target="_blank">Test</a>';
            } else {
                $test = null;
            }
            
            printf( '<input class="gc-text" type="text" name="get_cash_option_name[receiver_paypal]" id="receiver_paypal" value="%s">' . $test, $this->get_cash_option( 'receiver_paypal' ) );
        }
        
        public function get_cash_receiver_no_callback()
        {
            printf( '<input class="gc-text" type="text" name="get_cash_option_name[receiver_no]" id="receiver_no" value="%s">', $this->get_cash_option( 'receiver_no' ) );
        }
        
        public function get_cash_receiver_owner_callback()
        {
            printf( '<input class="gc-text" type="text" name="get_cash_option_name[receiver_owner]" id="receiver_owner" value="%s">', $this->get_cash_option( 'receiver_owner' ) );
        }
        
        public function get_cash_receiver_email_callback()
        {
            printf( '<input class="gc-text" type="text" name="get_cash_option_name[receiver_email]" id="receiver_email" value="%s">', $this->get_cash_option( 'receiver_email' ) );
        }
        
        // PRO Features
        public function get_cash_donate_button_text_callback()
        {
            printf( '<input disabled class="gc-text" type="text" name="get_cash_option_name[donate_button_text]" id="donate_button_text" value="%s">', $this->get_cash_option( 'donate_button_text' ) );
        }
        
        public function get_cash_donate_button_display_callback()
        {
            printf( '<input disabled class="gc-checkbox" type="checkbox" name="get_cash_option_name[donate_button_display]" id="donate_button_display" value="donate_button_display" %s>' . '<label for="donate_button_display"> Enable / Disable</label>', ( $this->get_cash_option( 'donate_button_display' ) === 'donate_button_display' ? 'checked' : '' ) );
        }
        
        public function get_cash_donate_button_shadow_callback()
        {
            printf( '<input disabled checked class="gc-checkbox" type="checkbox" name="get_cash_option_name[donate_button_shadow]" id="donate_button_shadow" value="donate_button_shadow" %s>' . '<label for="donate_button_shadow"> Enable / Disable</label>', ( $this->get_cash_option( 'donate_button_shadow' ) === 'donate_button_shadow' ? 'checked' : '' ) );
        }
    
    }
}
$get_cash = new Get_Cash();
/*
* Retrieve values with:
* $get_cash_options = get_option( 'get_cash_option_name' ); // Array of All Options
* $receiver_cash_app = $this->get_cash_option('receiver_cash_app'); // Receiver Cash App
*/