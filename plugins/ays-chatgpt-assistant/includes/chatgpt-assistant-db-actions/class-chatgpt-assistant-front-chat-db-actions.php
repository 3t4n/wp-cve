<?php

if( ! class_exists( 'ChatGPT_Assistant_Front_Chat_DB_Actions' ) ){
	ob_start();

	/**
	 * Class ChatGPT_Assistant_Settings_DB_Actions
	 * Class contains functions to interact with settings database
	 *
	 * Main functionality belong to inserting, updating and deleting
	 *
	 * Hooks used in the class
	 * @hooks           @filters        ays_chatgpt_assistant_item_save_settings
	 *
	 * Database tables without prefixes
	 * @tables          settings
	 *
	 * @param           $plugin_name
	 *
	 * @since           1.0.0
	 * @package         Ays_Chatgpt_Assistant
 	 * @subpackage      Ays_Chatgpt_Assistant/includes
 	 * @author          Ays_ChatGPT Assistant Team <info@ays-pro.com>
	 */
    class ChatGPT_Assistant_Front_Chat_DB_Actions extends ChatGPT_Assistant_Main_DB_Actions {

        public function store_data(){
            if( isset( $_REQUEST["front_chat_action"] ) && wp_verify_nonce( $_REQUEST["front_chat_action"], 'front_chat_action' ) ){

				$this->store_settings_db_data($_REQUEST);
				
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

				$settings['enable_request_limitations'] = ( isset( $settings['enable_request_limitations'] ) && $settings['enable_request_limitations'] != '' ) ? sanitize_text_field($settings['enable_request_limitations']) : 'off';
				$settings['access_for_logged_in'] = ( isset( $settings['access_for_logged_in'] ) && $settings['access_for_logged_in'] != '' ) ? sanitize_text_field($settings['access_for_logged_in']) : 'off';
				$settings['access_for_guests'] = ( isset( $settings['access_for_guests'] ) && $settings['access_for_guests'] != '' ) ? sanitize_text_field($settings['access_for_guests']) : 'off';
				$settings['enable_icon_text'] = ( isset( $settings['enable_icon_text'] ) && $settings['enable_icon_text'] != '' ) ? sanitize_text_field($settings['enable_icon_text']) : 'off';
				$settings['icon_text_show_once'] = ( isset( $settings['icon_text_show_once'] ) && $settings['icon_text_show_once'] != '' ) ? sanitize_text_field($settings['icon_text_show_once']) : 'off';
				$settings['password_protection'] = ( isset( $settings['password_protection'] ) && $settings['password_protection'] != '' ) ? sanitize_text_field($settings['password_protection']) : 'off';

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
                    $url = admin_url('admin.php') . '?page=' . $this->plugin_name .'-front-chat' . '&status=' . $message;
                    wp_redirect( $url );
                    exit();
                // }
            }

        }

		public function store_settings_db_data ($data) {
			global $wpdb;
			$settings_table_name = $wpdb->prefix . CHATGPT_ASSISTANT_DB_PREFIX . 'settings';
			
			$settings = $this->get_settings_db_data();

			// Show chat window on front end
			$chatbox_onoff = (isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_onoff']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_onoff'] != '') ? sanitize_text_field($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_chatbox_onoff']) : 'off';

			$settings['chatbox_onoff'] = $chatbox_onoff;
			
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
