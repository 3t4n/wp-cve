<?php

if( ! class_exists( 'ChatGPT_Assistant_General_Settings_DB_Actions' ) ){
	ob_start();

    /*
     ChatGPT_Assistant_General_Settings_DB_Actions Stands for storing data in 
     Setting page extending main functions from ChatGPT_Assistant_Main_DB_Actions
    */ 
    class ChatGPT_Assistant_General_Settings_DB_Actions extends ChatGPT_Assistant_Main_DB_Actions{

        public function store_data(){
            if( isset( $_REQUEST["general_settings_action"] ) && wp_verify_nonce( $_REQUEST["general_settings_action"], 'general_settings_action' ) ){

				$settings = array();
				if( isset( $_REQUEST[ CHATGPT_ASSISTANT_NAME_PREFIX . '_settings' ] ) && !empty( $_REQUEST[ CHATGPT_ASSISTANT_NAME_PREFIX . '_settings' ] )) {
					foreach($_REQUEST[ CHATGPT_ASSISTANT_NAME_PREFIX . '_settings' ] as $each_setting_key => $each_setting_value){
						if(is_array($each_setting_value)){
							$settings[$each_setting_key] = json_encode($each_setting_value);
						}
						else{
							$each_setting_value = isset($each_setting_value) && $each_setting_value != '' ? sanitize_text_field($each_setting_value) : '';
							$each_setting_key   = isset($each_setting_key) && $each_setting_key != '' ? sanitize_text_field($each_setting_key) : '';
							$settings[$each_setting_key] = $each_setting_value;
						}
                    }
                }

				if( is_array( $settings ) && ! empty( $settings ) ){
					foreach ( $settings as $meta_key => $meta_value ){
						if( $this->get_setting( $meta_key , '' ) ) {
							$this->update_setting($meta_key, $meta_value );
						}else{
							$this->add_setting( $meta_key, $meta_value );
						}
					}
				}

                $message = "saved";
                // if($success > 0){
                    $url = admin_url('admin.php') . '?page=' . $this->plugin_name .'-general-settings' . '&status=' . $message;
                    wp_redirect( $url );
                    exit();
                // }
            }

        }

		public function store_settings_db_data ($data) {
			global $wpdb;
			$settings_table_name = $wpdb->prefix . CHATGPT_ASSISTANT_DB_PREFIX . 'settings';
			
			$settings = $this->get_settings_db_data();

			$this->update_setting('options', json_encode( $settings ), "meta_value" , "meta_key", "", "", $settings_table_name);
		}

		public function get_settings_db_data () {
			global $wpdb;
			$settings_table_name = $wpdb->prefix . CHATGPT_ASSISTANT_DB_PREFIX . 'settings';

			$options = ($this->get_setting('options', $settings_table_name) === false) ? array() : json_decode($this->get_setting('options', $settings_table_name), true);

			return $options;
		}
    }
}
