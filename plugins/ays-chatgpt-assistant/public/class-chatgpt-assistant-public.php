<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Ays_Chatgpt_Assistant
 * @subpackage Ays_Chatgpt_Assistant/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ays_Chatgpt_Assistant
 * @subpackage Ays_Chatgpt_Assistant/public
 * @author     Ays_ChatGPT Assistant Team <info@ays-pro.com>
 */
class Chatgpt_Assistant_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	private $settings_db_name;
	private $front_chat_db_name;
	private $data_db_name;
	private $db_obj;
	private $front_settings_obj;
	private $settings_obj;
	private $api_key;
	private $limitations;
	private $chatgpt_assistant_full_screen_mode;
	private $chatgpt_assistant_chat_model;
	private $chatgpt_assistant_chat_temprature;
	private $chatgpt_assistant_chat_top_p;
	private $chatgpt_assistant_max_tokens;
	private $chatgpt_assistant_frequency_penalty;
	private $chatgpt_assistant_presence_penalty;
	private $chatgpt_assistant_best_of;
	private $chatgpt_assistant_context;
	private $chatgpt_assistant_profession;
	private $chatgpt_assistant_tone;
	private $chatgpt_assistant_language;
	private $chatgpt_assistant_name;
	private $chatbox_position;
	private $chatbox_icon_position;
	private $chatgpt_assistant_chat_icon_size;
	private $chatgpt_assistant_chat_icon_size_front;
	private $chatgpt_assistant_enable_icon_text;
	private $chatgpt_assistant_icon_text;
	private $chatgpt_assistant_icon_bg;
	private $chatgpt_assistant_icon_text_color;
	private $chatgpt_assistant_icon_text_font_size;
	private $chatgpt_assistant_icon_text_border_color;
	private $chatgpt_assistant_icon_text_border_width;
	private $chatgpt_assistant_icon_text_open_delay;
	private $chatgpt_assistant_icon_text_show_once;
	private $chatgpt_assistant_chat_width;
	private $chatgpt_assistant_chat_width_format;
	private $chatgpt_assistant_chat_height;
	private $chatgpt_assistant_chat_height_format;
	private $chatgpt_assistant_auto_opening_chatbox;
	private $chatgpt_assistant_auto_opening_chatbox_delay;
	private $chatgpt_assistant_regenerate_response;
	private $chatgpt_assistant_greeting_message;
	private $chatgpt_assistant_greeting_message_default_text;
	private $chatgpt_assistant_greeting_message_text;
	private $chatgpt_assistant_message_placeholder;
	private $chatgpt_assistant_chatbot_name;
	private $chatgpt_assistant_compliance_text;
	private $chatbox_theme;
	private $chat_icon;
	private $user_profile_picture;
	private $chatbox_mode;
	private $chatgpt_assistant_chatbox_background_color;
	private $chatgpt_assistant_chatbox_header_text_color;
	private $chatgpt_assistant_message_font_size;
	private $chatgpt_assistant_message_spacing;
	private $chatgpt_assistant_message_border_radius;
	private $chatgpt_assistant_chatbot_border_radius;
	private $message_bg_color;
	private $message_text_color;
	private $response_bg_color;
	private $response_text_color;
	private $response_icons_color;
	private $chatbot_global_styles;
	private $chatbox_onoff;
	private $enable_request_limitations;
	private $request_limitations_limit;
	private $request_limitations_interval;
	private $access_for_logged_in;
	private $password_protection;
	private $access_for_guests;
	private $chatgpt_assistant_enable_rate_chat;
	private $chatgpt_assistant_rate_chat_text;
	private $chatgpt_assistant_rate_chat_like;
	private $chatgpt_assistant_rate_chat_dislike;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		global $wpdb;
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->settings_db_name   = $wpdb->prefix . CHATGPT_ASSISTANT_DB_PREFIX . 'settings';
		$this->front_chat_db_name = $wpdb->prefix . CHATGPT_ASSISTANT_DB_PREFIX . 'front_settings';
		$this->data_db_name       = $wpdb->prefix . CHATGPT_ASSISTANT_DB_PREFIX . 'data';

		add_shortcode( 'chatgpt_assistant', array($this, 'ays_generate_chatbox') );
		add_shortcode( 'ays_chatgpt_assistant', array($this, 'ays_generate_chatbox_content') );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Chatgpt_Assistant_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Chatgpt_Assistant_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/chatgpt-assistant-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Chatgpt_Assistant_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Chatgpt_Assistant_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/chatgpt-assistant-public.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name . '-public-window', plugin_dir_url( __FILE__ ) . 'js/chatgpt-assistant-public-window.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name . '-autosize', CHATGPT_ASSISTANT_ASSETS_URL . '/js/chatgpt-assistant-autosize.js',  array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '-main-functions', CHATGPT_ASSISTANT_ASSETS_URL . '/js/chatgpt-assistant-main-functions.js',  array( 'jquery' ), $this->version, false );
	}
	

	public function enqueue_shortcode_styles() {
		wp_enqueue_style( $this->plugin_name . '-shortcode', plugin_dir_url( __FILE__ ) . 'css/chatgpt-assistant-public-shortcode.css', array(), $this->version, 'all' );
	}

	public function enqueue_shortcode_scripts() {		
		wp_enqueue_script( $this->plugin_name . '-shortcode',  plugin_dir_url( __FILE__ )  . 'js/chatgpt-assistant-public-shortcode.js', array( 'jquery' ), $this->version, true );
	}

	public function ays_generate_chatbox_content() {
		global $wpdb;		
		// Set main DB objects
		$this->set_db_objects();
		// Set front settings
		$this->set_global_front_settings_options();
		// Set main settings
		$this->set_global_main_settings_options();
		$this->enqueue_shortcode_styles();
		$this->enqueue_shortcode_scripts();
		return $this->show_chatgpt_assistant_content();
		 
	}
	
	public function ays_generate_chatbox() {
		global $wpdb;		
		// Set main DB objects
		$this->set_db_objects();
		// Set front settings
		$this->set_global_front_settings_options();
		// Set main settings
		$this->set_global_main_settings_options();

		// * LIMITATIONS *
		$show_chat = array();
			// Displaying Limitation
			$show_chat[] = $this->check_chatbot_display_limitations();
			// Users/Guests Limitation
			$show_chat[] = $this->check_chatbot_user_limitations();
			
			$this->check_limitations($show_chat);
		//

		return $this->show_chatgpt_assistant();		 
	}
	
	public function ays_chatgpt_shortcodes_show_all () {
		echo do_shortcode('[chatgpt_assistant]');
	}

	public function set_db_objects(){
		$this->db_obj = new Chatgpt_Assistant_DB_Actions( $this->plugin_name , $this->data_db_name);
		$this->settings_obj = new ChatGPT_Assistant_Settings_DB_Actions( $this->plugin_name , $this->settings_db_name);
		$this->front_settings_obj = new ChatGPT_Assistant_Front_Chat_DB_Actions( $this->plugin_name , $this->front_chat_db_name );
	}

	// Get and set front settings
	public function set_global_front_settings_options(){
		$front_chat_options = (empty($this->front_settings_obj->get_all_data())) ? array() : $this->front_settings_obj->get_all_data();

		$this->chat_icon  = esc_attr(CHATGPT_ASSISTANT_ADMIN_URL) . "/images/icons/chatgpt-icon.png";

		$this->chatgpt_assistant_chat_icon_size_front = isset($front_chat_options['chatgpt_assistant_chat_icon_size_front']) && $front_chat_options['chatgpt_assistant_chat_icon_size_front'] != "" ? absint($front_chat_options['chatgpt_assistant_chat_icon_size_front']) : '';

		$this->chatgpt_assistant_enable_icon_text = ( isset($front_chat_options['chatgpt_assistant_enable_icon_text']) && $front_chat_options['chatgpt_assistant_enable_icon_text'] == 'on' ) ? true : false;

		$this->chatgpt_assistant_icon_text = isset($front_chat_options['chatgpt_assistant_icon_text']) && $front_chat_options['chatgpt_assistant_icon_text'] != "" ? stripslashes(sanitize_text_field($front_chat_options['chatgpt_assistant_icon_text'])) : '';

		$this->chatgpt_assistant_icon_bg = isset($front_chat_options['chatgpt_assistant_icon_bg']) && $front_chat_options['chatgpt_assistant_icon_bg'] != "" ? stripslashes(sanitize_text_field($front_chat_options['chatgpt_assistant_icon_bg'])) : '#3b3b3b';

		$this->chatgpt_assistant_icon_text_color = isset($front_chat_options['chatgpt_assistant_icon_text_color']) && $front_chat_options['chatgpt_assistant_icon_text_color'] != "" ? stripslashes(sanitize_text_field($front_chat_options['chatgpt_assistant_icon_text_color'])) : '#f8f8f8';

		$this->chatgpt_assistant_icon_text_font_size = isset($front_chat_options['chatgpt_assistant_icon_text_font_size']) && $front_chat_options['chatgpt_assistant_icon_text_font_size'] != "" ? stripslashes(sanitize_text_field($front_chat_options['chatgpt_assistant_icon_text_font_size'])) : 14;

		$this->chatgpt_assistant_icon_text_border_color = isset($front_chat_options['chatgpt_assistant_icon_text_border_color']) && $front_chat_options['chatgpt_assistant_icon_text_border_color'] != "" ? stripslashes(sanitize_text_field($front_chat_options['chatgpt_assistant_icon_text_border_color'])) : $this->chatgpt_assistant_icon_bg;

		$this->chatgpt_assistant_icon_text_border_width = isset($front_chat_options['chatgpt_assistant_icon_text_border_width']) && $front_chat_options['chatgpt_assistant_icon_text_border_width'] != "" ? stripslashes(sanitize_text_field($front_chat_options['chatgpt_assistant_icon_text_border_width'])) : 0;

		$this->chatgpt_assistant_icon_text_open_delay = isset($front_chat_options['chatgpt_assistant_icon_text_open_delay']) && $front_chat_options['chatgpt_assistant_icon_text_open_delay'] != "" ? absint(stripslashes(sanitize_text_field($front_chat_options['chatgpt_assistant_icon_text_open_delay']))) : 0;

		$this->chatgpt_assistant_icon_text_show_once = ( isset($front_chat_options['chatgpt_assistant_icon_text_show_once']) && $front_chat_options['chatgpt_assistant_icon_text_show_once'] == 'on' ) ? true : false;

		$this->enable_request_limitations = ( isset($front_chat_options['chatgpt_assistant_enable_request_limitations']) && $front_chat_options['chatgpt_assistant_enable_request_limitations'] == 'on' ) ? true : false;

		$this->request_limitations_limit = isset($front_chat_options['chatgpt_assistant_request_limitations_limit']) && $front_chat_options['chatgpt_assistant_request_limitations_limit'] != "" ? absint($front_chat_options['chatgpt_assistant_request_limitations_limit']) : 0;

		$this->request_limitations_interval = isset($front_chat_options['chatgpt_assistant_request_limitations_interval']) && $front_chat_options['chatgpt_assistant_request_limitations_interval'] != "" ? sanitize_text_field($front_chat_options['chatgpt_assistant_request_limitations_interval']) : 'day';
		
		$this->access_for_logged_in = isset($front_chat_options['chatgpt_assistant_access_for_logged_in']) && $front_chat_options['chatgpt_assistant_access_for_logged_in'] != "" ? $front_chat_options['chatgpt_assistant_access_for_logged_in'] : 'on';

		$this->access_for_guests = isset($front_chat_options['chatgpt_assistant_access_for_guests']) && $front_chat_options['chatgpt_assistant_access_for_guests'] != "" ? $front_chat_options['chatgpt_assistant_access_for_guests'] : 'on';
		
		$this->password_protection = isset($front_chat_options['chatgpt_assistant_password_protection']) && $front_chat_options['chatgpt_assistant_password_protection'] != "" ? $front_chat_options['chatgpt_assistant_password_protection'] : 'off';
	}
	
	// Get and set main db settings (API key, ...)
	public function set_global_main_settings_options(){
		$data = $this->db_obj->get_data();
		$this->api_key = isset( $data['api_key'] ) && $data['api_key'] != '' ? esc_attr( $data['api_key'] ) : '';
	}

	// Get and set global settings
	public function set_global_settings_options(){
		$options = ($this->settings_obj->get_setting('options') === false) ? array() : json_decode($this->settings_obj->get_setting('options'), true);
		
		$this->chatbox_onoff = ( isset( $options['chatbox_onoff'] ) && $options['chatbox_onoff'] != '' ) ? $options['chatbox_onoff'] : 'on';

		$post_id = is_home() ? -2 : (!get_the_ID() ? -1 : get_the_ID());

        // * GENERAL SETTINGS *
            // ===== Shortcode settings =====
            $this->chatgpt_assistant_full_screen_mode = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'full_screen_mode'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'full_screen_mode'] == 'on' ) ? true : false;

            // ===== Chat settings =====			

			$chatbox_color = (isset($options['chatbox_color']) && $options['chatbox_color'] != '' ) ? $options['chatbox_color'] : '#4e426d';
            $chatbox_mode  = ( isset( $options['chatbox_mode'] ) && $options['chatbox_mode'] != '' ) ? $options['chatbox_mode'] : 'light';
			// Auto opening Chatbox
			$this->chatgpt_assistant_auto_opening_chatbox = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'auto_opening_chatbox'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'auto_opening_chatbox'] == 'on' ) ? true : false;
			// Auto opening Chatbox delay
			$this->chatgpt_assistant_auto_opening_chatbox_delay = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'auto_opening_chatbox_delay'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'auto_opening_chatbox_delay'] != '' ) ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'auto_opening_chatbox_delay']) : '0';
            // Regenerate response
			$this->chatgpt_assistant_regenerate_response = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'regenerate_response'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'regenerate_response'] == 'on' ) ? true : false;
            // Model
            $this->chatgpt_assistant_chat_model = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_model'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_model'] != '' ) ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_model']) : 'gpt-3.5-turbo-16k';
            // Temprature
            $this->chatgpt_assistant_chat_temprature = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_temprature'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_temprature'] != '' ) ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_temprature']) : '0.8';
            // Top P
            $this->chatgpt_assistant_chat_top_p = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_top_p'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_top_p'] != '' ) ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_top_p']) : '1';
            // Max tokens
            $this->chatgpt_assistant_max_tokens = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'max_tokens'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'max_tokens'] != '' ) ? intval($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'max_tokens']) : 1500;
            // Frequency penalty
            $this->chatgpt_assistant_frequency_penalty = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'frequency_penalty'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'frequency_penalty'] != '' ) ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'frequency_penalty']) : '0.01';
            // Presence penalty
            $this->chatgpt_assistant_presence_penalty = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'presence_penalty'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'presence_penalty'] != '' ) ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'presence_penalty']) : '0.01';
            // Best of
            $this->chatgpt_assistant_best_of = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'best_of'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'best_of'] != '' ) ? intval($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'best_of']) : 1;       
			// Context
			$this->chatgpt_assistant_context = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'context'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'context'] != '' ) ? stripslashes(esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'context'])) : '';
			// Profession (Act as)
			$this->chatgpt_assistant_profession = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'profession'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'profession'] != '' ) ? stripslashes(esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'profession'])) : '';
			// Tone
			$this->chatgpt_assistant_tone = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'tone'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'tone'] != '' ) ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'tone']) : '';   
			// Language
			$this->chatgpt_assistant_language = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'language'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'language'] != '' ) ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'language']) : 'en';
            // Name
			$this->chatgpt_assistant_name = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'name'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'name'] != '' ) ? stripslashes(esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'name'])) : '';
            // Chatbot position
            $this->chatbox_position = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'position'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'position'] != '' ) ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'position']) : 'right';
			// Chatbot icon position
            $this->chatbox_icon_position = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_position'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_position'] != '' ) ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_position']) : 'bottom-'.$this->chatbox_position;
            // Chat icon size
            $this->chatgpt_assistant_chat_icon_size = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_icon_size'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_icon_size'] != '' ) ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_icon_size']) : 70;
            // Chat width
            $this->chatgpt_assistant_chat_width = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_width'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_width'] != '' ) ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_width']) : 28;
            // Chat width format
            $this->chatgpt_assistant_chat_width_format = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_width_format'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_width_format'] != '' ) ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_width_format']) : '%';
            // Chat height
            $this->chatgpt_assistant_chat_height = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_height'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_height'] != '' ) ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_height']) : 55;
            // Chat height format
            $this->chatgpt_assistant_chat_height_format = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_height_format'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_height_format'] != '' ) ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_height_format']) : '%';

			// Enable rate chat
			$this->chatgpt_assistant_enable_rate_chat = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'enable_rate_chat'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'enable_rate_chat'] == 'on' ) ? true : false;

			// Rate chat text
			$this->chatgpt_assistant_rate_chat_text = isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'rate_chat_text'] ) ? stripslashes(sanitize_text_field($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'rate_chat_text'])) : __('How Satisfied are You?', 'chatgpt-assistant');

			// Rate chat options
			$chatgpt_assistant_rate_chat = (isset($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'rate_chat']) && !empty($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'rate_chat'])) ? $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'rate_chat'] : array();

			$this->chatgpt_assistant_rate_chat_like = isset($chatgpt_assistant_rate_chat['like']) && !empty($chatgpt_assistant_rate_chat['like']) ? $chatgpt_assistant_rate_chat['like'] : array();
			$this->chatgpt_assistant_rate_chat_like['action'] = (isset($this->chatgpt_assistant_rate_chat_like['action']) && ($this->chatgpt_assistant_rate_chat_like['action'] == 'feedback' || $this->chatgpt_assistant_rate_chat_like['action'] == 'help')) ? sanitize_text_field($this->chatgpt_assistant_rate_chat_like['action']) : 'feedback';
			$this->chatgpt_assistant_rate_chat_like['text'] = (isset($this->chatgpt_assistant_rate_chat_like['text']) && $this->chatgpt_assistant_rate_chat_like['text'] != '') ? stripslashes(sanitize_text_field($this->chatgpt_assistant_rate_chat_like['text'])) : '';

			$this->chatgpt_assistant_rate_chat_dislike = isset($chatgpt_assistant_rate_chat['dislike']) && !empty($chatgpt_assistant_rate_chat['dislike']) ? $chatgpt_assistant_rate_chat['dislike'] : array();
			$this->chatgpt_assistant_rate_chat_dislike['action'] = (isset($this->chatgpt_assistant_rate_chat_dislike['action']) && ($this->chatgpt_assistant_rate_chat_dislike['action'] == 'feedback' || $this->chatgpt_assistant_rate_chat_dislike['action'] == 'help')) ? sanitize_text_field($this->chatgpt_assistant_rate_chat_dislike['action']) : 'feedback';
			$this->chatgpt_assistant_rate_chat_dislike['text'] = (isset($this->chatgpt_assistant_rate_chat_dislike['text']) && $this->chatgpt_assistant_rate_chat_dislike['text'] != '') ? stripslashes(sanitize_text_field($this->chatgpt_assistant_rate_chat_dislike['text'])) : '';

            // Greeting message
            $this->chatgpt_assistant_greeting_message = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'greeting_message'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'greeting_message'] == 'on' ) ? true : false;
            // Greeting message text
            $this->chatgpt_assistant_greeting_message_default_text =  'Hello! I\'m an AI Assistant, and I\'m here to assist you with anything you need. How can I help you today?';
			$this->chatgpt_assistant_greeting_message_text = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'greeting_message_text'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'greeting_message_text'] != '' ) ? stripslashes(esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'greeting_message_text'])) : stripslashes($this->chatgpt_assistant_greeting_message_default_text);
			// Message placeholder
			$this->chatgpt_assistant_message_placeholder = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_placeholder'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_placeholder'] != '' ) ? stripslashes(esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_placeholder'])) : 'Enter your message';
			// Message placeholder
			$this->chatgpt_assistant_chatbot_name = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbot_name'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbot_name'] != '' ) ? stripslashes(esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbot_name'])) : 'AI Assistant';
			// compliance text
			$this->chatgpt_assistant_compliance_text = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'compliance_text'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'compliance_text'] != '' ) ? stripslashes(esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'compliance_text'])) : '';
			
        //

        // * STYLE SETTINGS *
			// Chat theme
			$this->chatbox_theme = (isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_theme'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_theme'] != '') ?  $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_theme'] : 'default';
            // Chat dark mode
            $this->chatbox_mode = ( isset( $options['chatbox_mode'] ) && $options['chatbox_mode'] != '' ) ? esc_attr($options['chatbox_mode']) : 'light';
            // Chat Widget background color
            $this->chatgpt_assistant_chatbox_background_color = isset($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_background_color']) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_background_color'] != '' ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_background_color']) : '#ffffff';   
            $this->chatgpt_assistant_chatbox_background_color = !isset($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_background_color']) && $this->chatbox_mode == 'dark' ? '#343541' : $this->chatgpt_assistant_chatbox_background_color;
            // Chat Widget header text color
            $this->chatgpt_assistant_chatbox_header_text_color = isset($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_header_text_color']) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_header_text_color'] != '' ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_header_text_color']) : '#ffffff';   
                        
            // Message font size
            $this->chatgpt_assistant_message_font_size = isset($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_font_size']) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_font_size'] != '' ? $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_font_size'] : 16;

			// Message spacing
            $this->chatgpt_assistant_message_spacing = isset($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_spacing']) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_spacing'] != '' ? $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_spacing'] : 7;

			// Message border radius
            $this->chatgpt_assistant_message_border_radius = isset($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_border_radius']) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_border_radius'] != '' ? absint(esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_border_radius'])) : 10;

			// chatbot border radius
            $this->chatgpt_assistant_chatbot_border_radius = isset($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbot_border_radius']) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbot_border_radius'] != '' ? absint(esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbot_border_radius'])) : 10;

            /* === USER MESSAGE STYLES START === */

                // User message background color
                if(isset($options['message_color']) && $options['message_color'] != ''){
                    $this->message_bg_color = $options['message_color'];
                }else{
                    if ( !(isset($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_bg_color']) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_bg_color'] != '') ) {
                        $this->message_bg_color = '#4e426d';
                        if ($this->chatbox_mode == 'dark') {
                            $this->message_bg_color = '#343541';
                        }
                    } else {
                        $this->message_bg_color = esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_bg_color']);
                    }
                }

                // User message text color
                if ( !(isset($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_text_color']) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_text_color'] != '') ) {
                    $this->message_text_color = '#ffffff';
                    if ($this->chatbox_mode == 'dark') {
                        $this->message_text_color = '#f1f1f1';
                    }
                } else {
                    $this->message_text_color = esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_text_color']);
                }

            /* === USER MESSAGE STYLES END === */

            /* === CHATBOT MESSAGE STYLES START === */

                // Chatbot response background color
                if(isset($options['response_color']) && $options['response_color'] != ''){
                    $this->response_bg_color = $options['response_color'];
                }else{
                    if ( !(isset($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'response_bg_color']) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'response_bg_color'] != '') ) {
                        $this->response_bg_color = '#d3d3d3';
                        if ($this->chatbox_mode == 'dark') {
                            $this->response_bg_color = '#4b4d56';
                        }
                    } else {
                        $this->response_bg_color = esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'response_bg_color']);
                    }

                }

                // Chatbot response text color
                 if ( !(isset($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'response_text_color']) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'response_text_color'] != '') ) {
                    $this->response_text_color = '#000000';
                    if ($this->chatbox_mode == 'dark') {
                        $this->response_text_color = '#ffffff';
                    }
                } else {
                    $this->response_text_color = esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'response_text_color']);
                }

                $this->response_icons_color = isset($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'response_icons_color']) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'response_icons_color'] != '' ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'response_icons_color']) : '#636a84';
            /* === CHATBOT MESSAGE STYLES END === */
			
			$guest_icon_index = rand(1, 10);

			/* === USER PROFILE PICTURE START === */
			$this->user_profile_picture = '<img alt="" src="' . CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/guest_icon_'.$guest_icon_index.'.png"> ';
			if ( is_user_logged_in() ) {
				$current_user = wp_get_current_user();
				$this->user_profile_picture = (get_avatar( $current_user->ID, 32 ));
			}
			/* === USER PROFILE PICTURE END === */

        //

		
		// Set global styles for chatbot
		$this->chatbot_global_styles = array(
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'position' => $this->chatbox_position,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'icon_position' => $this->chatbox_icon_position,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_icon_size' => $this->chatgpt_assistant_chat_icon_size,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_width' => $this->chatgpt_assistant_chat_width,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_width_format' => $this->chatgpt_assistant_chat_width_format,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_height' => $this->chatgpt_assistant_chat_height,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_height_format' => $this->chatgpt_assistant_chat_height_format,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chat_icon_size_front' => $this->chatgpt_assistant_chat_icon_size_front,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_color' => $chatbox_color,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_background_color' => $this->chatgpt_assistant_chatbox_background_color,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_header_text_color' => $this->chatgpt_assistant_chatbox_header_text_color,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbox_mode' => $chatbox_mode,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_font_size' => $this->chatgpt_assistant_message_font_size,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_spacing' => $this->chatgpt_assistant_message_spacing,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_border_radius' => $this->chatgpt_assistant_message_border_radius,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbot_border_radius' => $this->chatgpt_assistant_chatbot_border_radius,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbot_icon_bg' => $this->chatgpt_assistant_icon_bg,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbot_icon_text_color' => $this->chatgpt_assistant_icon_text_color,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbot_icon_text_font_size' => $this->chatgpt_assistant_icon_text_font_size,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbot_icon_text_border_color' => $this->chatgpt_assistant_icon_text_border_color,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbot_icon_text_border_width' => $this->chatgpt_assistant_icon_text_border_width,
			// USER MESSAGE STYLES
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_bg_color' => $this->message_bg_color,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_text_color' => $this->message_text_color,
			// CHATBOT MESSAGE STYLES
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'response_bg_color' => $this->response_bg_color,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'response_text_color' => $this->response_text_color,
			CHATGPT_ASSISTANT_OPTIONS_PREFIX.'response_icons_color' => $this->response_icons_color,
		);

        // Set Chatbot options for JS
		$generate_hush_api =  base64_encode($this->api_key . uniqid(uniqid('chgafr') . 'eds'));
		wp_localize_script( $this->plugin_name, 'AysChatGPTChatSettings', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),  
			'chatModel' => $this->chatgpt_assistant_chat_model,
		    'chatTemprature' => $this->chatgpt_assistant_chat_temprature,
		    'chatTopP' => $this->chatgpt_assistant_chat_top_p,
		    'chatMaxTokents' => $this->chatgpt_assistant_max_tokens,
		    'chatFrequencyPenalty' => $this->chatgpt_assistant_frequency_penalty,
		    'chatPresencePenalty' => $this->chatgpt_assistant_presence_penalty,
		    'chatBestOf' => $this->chatgpt_assistant_best_of,
		    'chatContext' => $this->chatgpt_assistant_context,
		    'chatProfession' => $this->chatgpt_assistant_profession,
		    'chatTone' => $this->chatgpt_assistant_tone,
		    'chatLanguage' => $this->chatgpt_assistant_language,
		    'chatName' => $this->chatgpt_assistant_name,
		    'chatGreetingMessage' => $this->chatgpt_assistant_greeting_message,			
		    'chatAutoOpening' => $this->chatgpt_assistant_auto_opening_chatbox,			
		    'chatAutoOpeningDelay' => $this->chatgpt_assistant_auto_opening_chatbox_delay,			
		    'chatRegenerateResponse' => $this->chatgpt_assistant_regenerate_response,			
		    'chatMessagePlaceholder' => $this->chatgpt_assistant_message_placeholder,			
		    'chatBotName' => $this->chatgpt_assistant_chatbot_name,			
		    'chatboxPosition' => $this->chatbox_position,
		    'chatboxIconPosition' => $this->chatbox_icon_position,
		    'chatboxIconSize' => $this->chatgpt_assistant_chat_icon_size,
			'chatboxTheme' => $this->chatbox_theme,
			'chatIcon' => $this->chat_icon,
			'userProfilePicture' => $this->user_profile_picture,
            'translations' => array(
				'leaveComment' => __( "Leave a Comment", "ays-chatgpt-assistant" ),
				'requestLimitReached' => array(
					'hour' => __( "You have reached the hourly limit. Please come back later.", "ays-chatgpt-assistant" ),
					'day' => __( "You have reached the daily limit. Please come back later.", "ays-chatgpt-assistant" ),
					'week' => __( "You have reached the weekly limit. Please come back later.", "ays-chatgpt-assistant" ),
					'month' => __( "You have reached the monthly limit. Please come back later", "ays-chatgpt-assistant" ),
				),		
                'chatGreetingMessage' => __( $this->chatgpt_assistant_greeting_message_text, "ays-chatgpt-assistant" ),
				'endChat' => array(
					'warningMsg' => __( "Do you really want to leave the current chat?", "ays-chatgpt-assistant" ),
					'buttonMsg' => __( "End chat", "ays-chatgpt-assistant" ),
					'modalIcon' => esc_attr(CHATGPT_ASSISTANT_ADMIN_URL) . "/images/icons/leave-icon.svg"
				),				
				'ka' => $generate_hush_api,
			),
			'enableRequestLimitations' => $this->enable_request_limitations,
			'requestLimitationsLimit' => $this->request_limitations_limit,
			'requestLimitationsInterval' => $this->request_limitations_interval,
			'enableRateChat' => $this->chatgpt_assistant_enable_rate_chat,
			'rateChatText' => $this->chatgpt_assistant_rate_chat_text,
			'postId' => $post_id,
			'rateChatOptions' => array(
				'like' => $this->chatgpt_assistant_rate_chat_like,
				'dislike' => $this->chatgpt_assistant_rate_chat_dislike,
				'images' => array(
					'linkIcon' => esc_attr(CHATGPT_ASSISTANT_ADMIN_URL) . "/images/icons/rate-open-link.svg",
					'like' => esc_attr(CHATGPT_ASSISTANT_ADMIN_URL) . "/images/icons/rate-like-front.svg",
					'dislike' => esc_attr(CHATGPT_ASSISTANT_ADMIN_URL) . "/images/icons/rate-dislike-front.svg",
				)
			),
			'iconTextOpenDelay' => $this->chatgpt_assistant_icon_text_open_delay,
			'iconTextShowOnce' => $this->chatgpt_assistant_icon_text_show_once,
		) );
	}

	// Get chatbox themes
	public function get_chatbox_all_themes(){
		$themes = array(
			'chatgpt' => array(
				'class' => 'ays-assistant-chatbox-chatgpt-theme',
				'css_path' => plugin_dir_url( __FILE__ ) . 'css/themes/chatgpt-assistant-public-chatgpt-theme.css',
			)
		);

		return $themes;
	}

	// Check displaying limitations
	public function check_chatbot_display_limitations(){
		if ($this->password_protection == 'on' && post_password_required()) {
			return false;
		}

		return true;
	}

	// Check user/guest limitations
	public function check_chatbot_user_limitations(){
		$check_displaying = true;
		if (is_user_logged_in()) {
			if ($this->access_for_logged_in === "on") {
				$check_displaying = true;
			} else {
				$check_displaying = false;
			}
		} else {
			if ($this->access_for_guests === "on") {
				$check_displaying = true;
			} else {
				$check_displaying = false;
			}
		}
		
		return $check_displaying;
	}

	// Check limitations
	public function check_limitations($limit_data){
		$this->limitations = false;
		if(array_sum($limit_data) == count($limit_data)){
			$this->limitations = true;
		}
	}

	// Show Main Chatbot
	public function show_chatgpt_assistant(){		
		// if (isset($this->api_key) && $this->api_key != '') :
			$this->set_global_settings_options();
			if ($this->chatbox_onoff == 'on' && $this->limitations) :
				$chatbox_theme_class = '';

				if ($this->chatbox_theme != 'default') {
					$all_themes = $this->get_chatbox_all_themes();
					$current_theme = $all_themes[$this->chatbox_theme];
					$chatbox_theme_class = $current_theme['class'];
					wp_enqueue_style( $this->plugin_name . '-theme-' . $this->chatbox_theme, $current_theme['css_path'], array(), $this->version, 'all' );
				}

				ob_start();
				?>
				<div class="ays-assistant-chatbox" style="display: none;" colmode="<?php echo esc_attr($this->chatbox_mode); ?>">
					<div class="ays-assistant-chatbox-closed-view"> <!-- closed logo -->
						<?php if ($this->chatgpt_assistant_enable_icon_text && $this->chatgpt_assistant_icon_text != ''): ?>
							<div class="ays-assistant-chatbox-closed-view-text" <?php echo $this->chatgpt_assistant_icon_text_open_delay > 0 ? 'style="display:none"' : ''; ?>><?php echo $this->chatgpt_assistant_icon_text; ?></div>
						<?php endif; ?>
						<div class="ays-assistant-closed-icon-container">
							<img class="ays-assistant-chatbox-logo-image" src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL) ?>/images/icons/chatgpt-icon.png" alt="ChatGPT icon">
						</div>
					</div>
					<div class="ays-assistant-chatbox-maximized-bg" style="display: none;"></div>
					<div class="ays-assistant-chatbox-main-container <?php echo $chatbox_theme_class ?>" style="display: none;">
						<div class="ays-assistant-chatbox-main-chat-modal" style="display: none;">
							<div class="ays-assistant-chatbox-main-chat-modal-container">
								<div class="ays-assistant-chatbox-main-chat-modal-header">
									<div class="ays-assistant-chatbox-main-chat-modal-header-close">
										<svg data-modal-action="close" xmlns="http://www.w3.org/2000/svg" fill="#000000" width="25" height="25" viewBox="0 0 320 512">
											<path data-modal-action="close" d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/>
										</svg>
									</div>
									<div class="ays-assistant-chatbox-main-chat-modal-header-text"></div>
								</div>
								<div class="ays-assistant-chatbox-main-chat-modal-body">
									<div class="ays-assistant-chatbox-main-chat-modal-body-image"></div>
									<div class="ays-assistant-chatbox-main-chat-modal-body-text"></div>
								</div>
								<div class="ays-assistant-chatbox-main-chat-modal-footer">
									<div class="ays-assistant-chatbox-main-chat-modal-footer-button"></div>
									<div class="ays-assistant-chatbox-main-chat-modal-footer-text"></div>
								</div>
							</div>
						</div>
						<div class="ays-assistant-chatbox-main-chat-box">
							<div class="ays-assistant-chatbox-header-row"> <!-- header row -->
								<!-- <p class="ays-assistant-chatbox-header-text">ChatGPT Assistant</p> -->
								<div class="ays-assistant-header-row-logo-row">
									<div class="ays-assistant-header-row-logo">
										<img class="ays-assistant-header-row-logo-image" src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL) ?>/images/icons/chatgpt-icon.png" alt="ChatGPT icon">
									</div>
									<p class="ays-assistant-chatbox-header-text"><?php echo $this->chatgpt_assistant_chatbot_name ?></p>
								</div>
								<div class="ays-assistant-chatbox-logo">
									<img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL) ?>/images/icons/close-button.svg" alt="Close" class="ays-assistant-chatbox-close-bttn">
									<img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL) ?>/images/icons/maximize.svg" alt="Maximize" class="ays-assistant-chatbox-resize-bttn">
									<img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL) ?>/images/icons/end-button.svg" alt="End" class="ays-assistant-chatbox-end-bttn">
								</div>
							</div>
							<?php if ($this->chatgpt_assistant_enable_rate_chat) : ?>
								<div class="ays-assistant-chatbox-rate-chat-row">
									<div class="ays-assistant-chatbox-rate-chat-content">
										<?php echo $this->chatgpt_assistant_rate_chat_text; ?>
										<div class="ays-assistant-chatbox-rate-chat-like" data-action="like">
											<svg version="1.2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" overflow="visible" preserveAspectRatio="none" viewBox="0 0 24 24" width="20.999999999999773" height="21"><g><path xmlns:default="http://www.w3.org/2000/svg" id="thumbs-o-up" d="M6.47,17.8c-0.26,0.25-0.67,0.24-0.92-0.02c-0.25-0.26-0.24-0.67,0.02-0.92c0.26-0.25,0.67-0.24,0.92,0.02  C6.6,17,6.67,17.16,6.67,17.33C6.67,17.51,6.6,17.68,6.47,17.8z M18.66,11.33c-0.01,0.29-0.09,0.58-0.22,0.84  c-0.08,0.26-0.29,0.45-0.56,0.49c0.12,0.14,0.21,0.31,0.26,0.49c0.06,0.19,0.1,0.38,0.1,0.58c0.01,0.48-0.18,0.95-0.54,1.27  c0.13,0.22,0.19,0.47,0.19,0.72c0,0.27-0.06,0.53-0.18,0.77c-0.1,0.23-0.27,0.42-0.49,0.55c0.03,0.19,0.05,0.39,0.05,0.58  c0,1.16-0.67,1.74-2,1.74H14c-1.22-0.06-2.42-0.32-3.56-0.76l-0.3-0.11l-0.37-0.13L9.4,18.21L9,18.1L8.66,18H8.34H8v-6.67h0.34  c0.13,0,0.25-0.03,0.37-0.09C8.86,11.18,8.99,11.1,9.12,11l0.4-0.37c0.15-0.15,0.29-0.3,0.42-0.46l0.36-0.44l0.33-0.43l0.24-0.31  c0.38-0.47,0.65-0.79,0.8-0.95c0.3-0.32,0.51-0.71,0.62-1.14c0.13-0.46,0.23-0.9,0.32-1.31c0.04-0.34,0.17-0.66,0.39-0.92  c0.5-0.07,0.99,0.12,1.33,0.49c0.25,0.46,0.37,0.99,0.33,1.51c-0.05,0.59-0.22,1.15-0.5,1.67c-0.28,0.51-0.45,1.08-0.5,1.66h3.66  c0.35,0,0.69,0.14,0.93,0.4C18.51,10.64,18.66,10.98,18.66,11.33L18.66,11.33z M19.99,11.33c-0.03-1.46-1.21-2.63-2.67-2.65H15.5  c0.32-0.62,0.49-1.3,0.5-2c0.03-0.67-0.09-1.33-0.36-1.94c-0.22-0.46-0.6-0.84-1.06-1.06C14.09,3.45,13.55,3.33,13,3.34  c-0.35,0-0.69,0.14-0.94,0.39c-0.31,0.31-0.54,0.7-0.65,1.12c-0.13,0.46-0.24,0.9-0.32,1.32c-0.04,0.33-0.17,0.63-0.37,0.89  c-0.34,0.37-0.71,0.81-1.11,1.33C9.2,8.98,8.72,9.52,8.19,10H5.34C4.61,9.99,4.01,10.58,4,11.32c0,0,0,0.01,0,0.01V18  c0,0.73,0.6,1.33,1.33,1.33h3c0.49,0.09,0.97,0.23,1.44,0.42c0.85,0.3,1.6,0.53,2.25,0.68c0.66,0.16,1.33,0.23,2,0.23h1.34  c0.87,0.04,1.71-0.26,2.36-0.84c0.61-0.6,0.93-1.44,0.88-2.29c0.41-0.53,0.63-1.18,0.62-1.85c0.01-0.15,0.01-0.3,0-0.45  c0.26-0.46,0.4-0.97,0.4-1.5c-0.01-0.25-0.05-0.49-0.13-0.73c0.34-0.5,0.52-1.09,0.51-1.7l0,0L19.99,11.33z" style="fill: rgb(196, 196, 196);" vector-effect="non-scaling-stroke"/></g></svg></div>
										<div class="ays-assistant-chatbox-rate-chat-dislike" data-action="dislike">
											<svg version="1.2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" overflow="visible" preserveAspectRatio="none" viewBox="0 0 24 24" width="20.999999999999545" height="21"><g><path xmlns:default="http://www.w3.org/2000/svg" id="thumbs-o-down" d="M6.47,7.14C6.21,7.39,5.8,7.38,5.55,7.12C5.3,6.86,5.31,6.45,5.57,6.2c0.26-0.25,0.67-0.24,0.92,0.02  C6.6,6.34,6.67,6.5,6.67,6.67C6.67,6.85,6.6,7.02,6.47,7.14z M18.66,12.67c0,0.35-0.15,0.69-0.41,0.93c-0.24,0.26-0.58,0.4-0.93,0.4  h-3.65c0.05,0.58,0.22,1.15,0.5,1.66c0.28,0.52,0.45,1.08,0.5,1.67c0.04,0.52-0.08,1.05-0.33,1.51c-0.34,0.37-0.83,0.56-1.33,0.49  c-0.21-0.25-0.35-0.55-0.4-0.88c-0.08-0.41-0.19-0.84-0.32-1.31c-0.11-0.43-0.32-0.82-0.62-1.14c-0.15-0.16-0.42-0.48-0.8-0.95  l-0.24-0.31l-0.33-0.43l-0.36-0.44c-0.13-0.16-0.27-0.31-0.42-0.46L9.12,13c-0.13-0.11-0.27-0.21-0.42-0.28  c-0.12-0.06-0.24-0.09-0.37-0.09H8V6h0.34h0.32L9,5.9l0.4-0.11l0.36-0.12l0.37-0.13l0.3-0.11c1.14-0.44,2.35-0.7,3.57-0.76h1.33  c0.51-0.03,1.01,0.12,1.42,0.43c0.36,0.34,0.55,0.82,0.51,1.31c0,0.19-0.02,0.39-0.05,0.58c0.22,0.13,0.39,0.32,0.49,0.55  c0.12,0.24,0.18,0.5,0.18,0.77c0,0.24-0.06,0.48-0.18,0.69c0.35,0.32,0.55,0.77,0.55,1.24c0,0.2-0.04,0.39-0.1,0.58  c-0.05,0.18-0.14,0.35-0.26,0.49c0.27,0.04,0.48,0.23,0.56,0.49c0.13,0.26,0.21,0.55,0.22,0.84l0,0L18.66,12.67z M19.99,12.67  c0.01-0.61-0.17-1.2-0.51-1.7c0.06-0.23,0.09-0.48,0.09-0.72c0-0.53-0.14-1.04-0.4-1.5c0.01-0.15,0.01-0.3,0-0.45  c0.01-0.67-0.21-1.32-0.62-1.85V6.41c0.05-0.84-0.28-1.67-0.89-2.25c-0.65-0.57-1.5-0.86-2.36-0.82h-1.12c-0.7,0-1.4,0.07-2.08,0.23  c-0.78,0.19-1.56,0.42-2.32,0.69C9.31,4.45,8.83,4.59,8.34,4.68h-3C4.61,4.68,4.01,5.27,4,6c0,0,0,0,0,0v6.66  C3.99,13.4,4.59,14,5.32,14c0.01,0,0.01,0,0.02,0h2.85c0.54,0.48,1.02,1.02,1.43,1.61c0.35,0.46,0.72,0.9,1.11,1.32  c0.16,0.2,0.27,0.43,0.32,0.67c0.08,0.29,0.14,0.58,0.18,0.88c0.05,0.32,0.14,0.64,0.27,0.94c0.13,0.32,0.32,0.61,0.56,0.85  c0.25,0.25,0.59,0.39,0.94,0.39c0.54,0.01,1.08-0.11,1.57-0.34c0.46-0.22,0.84-0.6,1.06-1.06c0.27-0.61,0.4-1.27,0.37-1.93  c-0.01-0.7-0.18-1.38-0.5-2h1.83c1.46-0.02,2.64-1.19,2.67-2.65l0,0L19.99,12.67z" style="fill: rgb(196, 196, 196);" vector-effect="non-scaling-stroke"/></g></svg></div>
									</div>
								</div>
							<?php endif; ?>
							<div class="ays-assistant-chatbox-messages-box"> <!-- messages container -->
								<div class="ays-assistant-chatbox-loading-box" style="display: none;"> <!-- loader -->
									<div class="ays-assistant-chatbox-loader-ball-2">
										<div></div>
										<div></div>
										<div></div>
									</div>
								</div>
							</div>
							<div class="ays-assistant-chatbox-input-box"> <!-- prompt part -->
								<textarea style="overflow:auto" rows="1" class="ays-assistant-chatbox-prompt-input ays-assistant-chatbox-prompt-inputs-all" name="ays_assistant_chatbox_prompt" id="ays-assistant-chatbox-prompt" placeholder="<?php echo $this->chatgpt_assistant_message_placeholder ?>"></textarea>
								<?php if ($this->chatgpt_assistant_regenerate_response): ?>
									<button class="ays-assistant-chatbox-regenerate-response-button" disabled>
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#f8f8f8" width="18" height="18">
											<path d="M142.9 142.9c62.2-62.2 162.7-62.5 225.3-1L327 183c-6.9 6.9-8.9 17.2-5.2 26.2s12.5 14.8 22.2 14.8H463.5c0 0 0 0 0 0H472c13.3 0 24-10.7 24-24V72c0-9.7-5.8-18.5-14.8-22.2s-19.3-1.7-26.2 5.2L413.4 96.6c-87.6-86.5-228.7-86.2-315.8 1C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5c7.7-21.8 20.2-42.3 37.8-59.8zM16 312v7.6 .7V440c0 9.7 5.8 18.5 14.8 22.2s19.3 1.7 26.2-5.2l41.6-41.6c87.6 86.5 228.7 86.2 315.8-1c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.2 62.2-162.7 62.5-225.3 1L185 329c6.9-6.9 8.9-17.2 5.2-26.2s-12.5-14.8-22.2-14.8H48.4h-.7H40c-13.3 0-24 10.7-24 24z"/>
										</svg>
									</button>
								<?php endif; ?>
								<button class="ays-assistant-chatbox-send-button" disabled>
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#f8f8f8" width="18" height="18">
										<path d="M498.1 5.6c10.1 7 15.4 19.1 13.5 31.2l-64 416c-1.5 9.7-7.4 18.2-16 23s-18.9 5.4-28 1.6L284 427.7l-68.5 74.1c-8.9 9.7-22.9 12.9-35.2 8.1S160 493.2 160 480V396.4c0-4 1.5-7.8 4.2-10.7L331.8 202.8c5.8-6.3 5.6-16-.4-22s-15.7-6.4-22-.7L106 360.8 17.7 316.6C7.1 311.3 .3 300.7 0 288.9s5.9-22.8 16.1-28.7l448-256c10.7-6.1 23.9-5.5 34 1.4z"/>
									</svg>
								</button>
							</div>
							<div class="ays-assistant-chatbox-notice-box" <?php echo $this->chatgpt_assistant_compliance_text === '' ? '' : 'style="padding:4px 20px"'; ?>>
								<span><?php echo $this->chatgpt_assistant_compliance_text; ?></span>
							</div>
						</div>
					</div>
				</div>
				<?php echo ChatGPT_assistant_Data::get_chatbot_styles($this->chatbot_global_styles); ?>
				<?php
			endif;
		// endif;

		$content = ob_get_clean();

		return $content;
	}	

	// Show Main Chatbot shortcode
	public function show_chatgpt_assistant_content(){
			$this->set_global_settings_options();
				$chatbox_theme_class = '';

				if ($this->chatbox_theme != 'default') {
					$all_themes = $this->get_chatbox_all_themes();
					$current_theme = $all_themes[$this->chatbox_theme];
					$chatbox_theme_class = $current_theme['class'];
					wp_enqueue_style( $this->plugin_name . '-theme-' . $this->chatbox_theme, $current_theme['css_path'], array(), $this->version, 'all' );
				}

				ob_start();
				?>
				<div class="ays-assistant-chatbox-shortcode" colmode="<?php echo esc_attr($this->chatbox_mode); ?>">
				<div class="ays-assistant-chatbox-main-container <?php echo esc_attr($chatbox_theme_class); ?>" >
						<div class="ays-assistant-chatbox-main-chat-modal" style="display: none;">
							<div class="ays-assistant-chatbox-main-chat-modal-container">
								<div class="ays-assistant-chatbox-main-chat-modal-header">
									<div class="ays-assistant-chatbox-main-chat-modal-header-close">
										<svg data-modal-action="close" xmlns="http://www.w3.org/2000/svg" fill="#000000" width="25" height="25" viewBox="0 0 320 512">
											<path data-modal-action="close" d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/>
										</svg>
									</div>
									<div class="ays-assistant-chatbox-main-chat-modal-header-text"></div>
								</div>
								<div class="ays-assistant-chatbox-main-chat-modal-body">
									<div class="ays-assistant-chatbox-main-chat-modal-body-image"></div>
									<div class="ays-assistant-chatbox-main-chat-modal-body-text"></div>
								</div>
								<div class="ays-assistant-chatbox-main-chat-modal-footer">
									<div class="ays-assistant-chatbox-main-chat-modal-footer-button"></div>
									<div class="ays-assistant-chatbox-main-chat-modal-footer-text"></div>
								</div>
							</div>
						</div>
						<div class="ays-assistant-chatbox-main-chat-box">
							<div class="ays-assistant-chatbox-header-row"> <!-- header row -->
								<!-- <p class="ays-assistant-chatbox-header-text">ChatGPT Assistant</p> -->
								<div class="ays-assistant-header-row-logo-row">
									<div class="ays-assistant-header-row-logo">
										<img class="ays-assistant-header-row-logo-image" src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL) ?>/images/icons/chatgpt-icon.png" alt="ChatGPT icon">
									</div>
									<p class="ays-assistant-chatbox-header-text"><?php echo $this->chatgpt_assistant_chatbot_name ?></p>
								</div>
								<div class="ays-assistant-chatbox-header-buttons">
									<?php if ( $this->chatgpt_assistant_full_screen_mode ): ?>
										<img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL) ?>/images/icons/maximize.svg" alt="Maximize" class="ays-assistant-chatbox-resize-bttn">
									<?php endif ?>
									<img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL) ?>/images/icons/end-button.svg" alt="End" class="ays-assistant-chatbox-end-bttn">
								</div>
							</div>
							<?php if ($this->chatgpt_assistant_enable_rate_chat) : ?>
								<div class="ays-assistant-chatbox-rate-chat-row">
									<div class="ays-assistant-chatbox-rate-chat-content">
										<?php echo $this->chatgpt_assistant_rate_chat_text; ?>
										<div class="ays-assistant-chatbox-rate-chat-like" data-action="like">
											<svg version="1.2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" overflow="visible" preserveAspectRatio="none" viewBox="0 0 24 24" width="20.999999999999773" height="21"><g><path xmlns:default="http://www.w3.org/2000/svg" id="thumbs-o-up" d="M6.47,17.8c-0.26,0.25-0.67,0.24-0.92-0.02c-0.25-0.26-0.24-0.67,0.02-0.92c0.26-0.25,0.67-0.24,0.92,0.02  C6.6,17,6.67,17.16,6.67,17.33C6.67,17.51,6.6,17.68,6.47,17.8z M18.66,11.33c-0.01,0.29-0.09,0.58-0.22,0.84  c-0.08,0.26-0.29,0.45-0.56,0.49c0.12,0.14,0.21,0.31,0.26,0.49c0.06,0.19,0.1,0.38,0.1,0.58c0.01,0.48-0.18,0.95-0.54,1.27  c0.13,0.22,0.19,0.47,0.19,0.72c0,0.27-0.06,0.53-0.18,0.77c-0.1,0.23-0.27,0.42-0.49,0.55c0.03,0.19,0.05,0.39,0.05,0.58  c0,1.16-0.67,1.74-2,1.74H14c-1.22-0.06-2.42-0.32-3.56-0.76l-0.3-0.11l-0.37-0.13L9.4,18.21L9,18.1L8.66,18H8.34H8v-6.67h0.34  c0.13,0,0.25-0.03,0.37-0.09C8.86,11.18,8.99,11.1,9.12,11l0.4-0.37c0.15-0.15,0.29-0.3,0.42-0.46l0.36-0.44l0.33-0.43l0.24-0.31  c0.38-0.47,0.65-0.79,0.8-0.95c0.3-0.32,0.51-0.71,0.62-1.14c0.13-0.46,0.23-0.9,0.32-1.31c0.04-0.34,0.17-0.66,0.39-0.92  c0.5-0.07,0.99,0.12,1.33,0.49c0.25,0.46,0.37,0.99,0.33,1.51c-0.05,0.59-0.22,1.15-0.5,1.67c-0.28,0.51-0.45,1.08-0.5,1.66h3.66  c0.35,0,0.69,0.14,0.93,0.4C18.51,10.64,18.66,10.98,18.66,11.33L18.66,11.33z M19.99,11.33c-0.03-1.46-1.21-2.63-2.67-2.65H15.5  c0.32-0.62,0.49-1.3,0.5-2c0.03-0.67-0.09-1.33-0.36-1.94c-0.22-0.46-0.6-0.84-1.06-1.06C14.09,3.45,13.55,3.33,13,3.34  c-0.35,0-0.69,0.14-0.94,0.39c-0.31,0.31-0.54,0.7-0.65,1.12c-0.13,0.46-0.24,0.9-0.32,1.32c-0.04,0.33-0.17,0.63-0.37,0.89  c-0.34,0.37-0.71,0.81-1.11,1.33C9.2,8.98,8.72,9.52,8.19,10H5.34C4.61,9.99,4.01,10.58,4,11.32c0,0,0,0.01,0,0.01V18  c0,0.73,0.6,1.33,1.33,1.33h3c0.49,0.09,0.97,0.23,1.44,0.42c0.85,0.3,1.6,0.53,2.25,0.68c0.66,0.16,1.33,0.23,2,0.23h1.34  c0.87,0.04,1.71-0.26,2.36-0.84c0.61-0.6,0.93-1.44,0.88-2.29c0.41-0.53,0.63-1.18,0.62-1.85c0.01-0.15,0.01-0.3,0-0.45  c0.26-0.46,0.4-0.97,0.4-1.5c-0.01-0.25-0.05-0.49-0.13-0.73c0.34-0.5,0.52-1.09,0.51-1.7l0,0L19.99,11.33z" style="fill: rgb(196, 196, 196);" vector-effect="non-scaling-stroke"/></g></svg></div>
										<div class="ays-assistant-chatbox-rate-chat-dislike" data-action="dislike">
											<svg version="1.2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" overflow="visible" preserveAspectRatio="none" viewBox="0 0 24 24" width="20.999999999999545" height="21"><g><path xmlns:default="http://www.w3.org/2000/svg" id="thumbs-o-down" d="M6.47,7.14C6.21,7.39,5.8,7.38,5.55,7.12C5.3,6.86,5.31,6.45,5.57,6.2c0.26-0.25,0.67-0.24,0.92,0.02  C6.6,6.34,6.67,6.5,6.67,6.67C6.67,6.85,6.6,7.02,6.47,7.14z M18.66,12.67c0,0.35-0.15,0.69-0.41,0.93c-0.24,0.26-0.58,0.4-0.93,0.4  h-3.65c0.05,0.58,0.22,1.15,0.5,1.66c0.28,0.52,0.45,1.08,0.5,1.67c0.04,0.52-0.08,1.05-0.33,1.51c-0.34,0.37-0.83,0.56-1.33,0.49  c-0.21-0.25-0.35-0.55-0.4-0.88c-0.08-0.41-0.19-0.84-0.32-1.31c-0.11-0.43-0.32-0.82-0.62-1.14c-0.15-0.16-0.42-0.48-0.8-0.95  l-0.24-0.31l-0.33-0.43l-0.36-0.44c-0.13-0.16-0.27-0.31-0.42-0.46L9.12,13c-0.13-0.11-0.27-0.21-0.42-0.28  c-0.12-0.06-0.24-0.09-0.37-0.09H8V6h0.34h0.32L9,5.9l0.4-0.11l0.36-0.12l0.37-0.13l0.3-0.11c1.14-0.44,2.35-0.7,3.57-0.76h1.33  c0.51-0.03,1.01,0.12,1.42,0.43c0.36,0.34,0.55,0.82,0.51,1.31c0,0.19-0.02,0.39-0.05,0.58c0.22,0.13,0.39,0.32,0.49,0.55  c0.12,0.24,0.18,0.5,0.18,0.77c0,0.24-0.06,0.48-0.18,0.69c0.35,0.32,0.55,0.77,0.55,1.24c0,0.2-0.04,0.39-0.1,0.58  c-0.05,0.18-0.14,0.35-0.26,0.49c0.27,0.04,0.48,0.23,0.56,0.49c0.13,0.26,0.21,0.55,0.22,0.84l0,0L18.66,12.67z M19.99,12.67  c0.01-0.61-0.17-1.2-0.51-1.7c0.06-0.23,0.09-0.48,0.09-0.72c0-0.53-0.14-1.04-0.4-1.5c0.01-0.15,0.01-0.3,0-0.45  c0.01-0.67-0.21-1.32-0.62-1.85V6.41c0.05-0.84-0.28-1.67-0.89-2.25c-0.65-0.57-1.5-0.86-2.36-0.82h-1.12c-0.7,0-1.4,0.07-2.08,0.23  c-0.78,0.19-1.56,0.42-2.32,0.69C9.31,4.45,8.83,4.59,8.34,4.68h-3C4.61,4.68,4.01,5.27,4,6c0,0,0,0,0,0v6.66  C3.99,13.4,4.59,14,5.32,14c0.01,0,0.01,0,0.02,0h2.85c0.54,0.48,1.02,1.02,1.43,1.61c0.35,0.46,0.72,0.9,1.11,1.32  c0.16,0.2,0.27,0.43,0.32,0.67c0.08,0.29,0.14,0.58,0.18,0.88c0.05,0.32,0.14,0.64,0.27,0.94c0.13,0.32,0.32,0.61,0.56,0.85  c0.25,0.25,0.59,0.39,0.94,0.39c0.54,0.01,1.08-0.11,1.57-0.34c0.46-0.22,0.84-0.6,1.06-1.06c0.27-0.61,0.4-1.27,0.37-1.93  c-0.01-0.7-0.18-1.38-0.5-2h1.83c1.46-0.02,2.64-1.19,2.67-2.65l0,0L19.99,12.67z" style="fill: rgb(196, 196, 196);" vector-effect="non-scaling-stroke"/></g></svg></div>
									</div>
								</div>
							<?php endif; ?>
							<div class="ays-assistant-chatbox-messages-box"> <!-- messages container -->
								<?php echo $this->show_chatgpt_greeting_message(); ?>
								<div class="ays-assistant-chatbox-loading-box" style="display: none;"> <!-- loader -->
									<div class="ays-assistant-chatbox-loader-ball-2">
										<div></div>
										<div></div>
										<div></div>
									</div>
								</div>
							</div>
							<div class="ays-assistant-chatbox-input-box"> <!-- prompt part -->
								<textarea rows="1" style="overflow:auto" class="ays-assistant-chatbox-prompt-input ays-assistant-chatbox-prompt-inputs-all" name="ays_assistant_chatbox_prompt" id="ays-assistant-chatbox-prompt-shortcode" placeholder="<?php echo $this->chatgpt_assistant_message_placeholder ?>"></textarea>
								<?php if ($this->chatgpt_assistant_regenerate_response): ?>
									<button class="ays-assistant-chatbox-regenerate-response-button" disabled>
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#f8f8f8" width="18" height="18">
											<path d="M142.9 142.9c62.2-62.2 162.7-62.5 225.3-1L327 183c-6.9 6.9-8.9 17.2-5.2 26.2s12.5 14.8 22.2 14.8H463.5c0 0 0 0 0 0H472c13.3 0 24-10.7 24-24V72c0-9.7-5.8-18.5-14.8-22.2s-19.3-1.7-26.2 5.2L413.4 96.6c-87.6-86.5-228.7-86.2-315.8 1C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5c7.7-21.8 20.2-42.3 37.8-59.8zM16 312v7.6 .7V440c0 9.7 5.8 18.5 14.8 22.2s19.3 1.7 26.2-5.2l41.6-41.6c87.6 86.5 228.7 86.2 315.8-1c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.2 62.2-162.7 62.5-225.3 1L185 329c6.9-6.9 8.9-17.2 5.2-26.2s-12.5-14.8-22.2-14.8H48.4h-.7H40c-13.3 0-24 10.7-24 24z"/>
										</svg>
									</button>
								<?php endif; ?>
								<button class="ays-assistant-chatbox-send-button" disabled>
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#f8f8f8" width="18" height="18">
										<path d="M498.1 5.6c10.1 7 15.4 19.1 13.5 31.2l-64 416c-1.5 9.7-7.4 18.2-16 23s-18.9 5.4-28 1.6L284 427.7l-68.5 74.1c-8.9 9.7-22.9 12.9-35.2 8.1S160 493.2 160 480V396.4c0-4 1.5-7.8 4.2-10.7L331.8 202.8c5.8-6.3 5.6-16-.4-22s-15.7-6.4-22-.7L106 360.8 17.7 316.6C7.1 311.3 .3 300.7 0 288.9s5.9-22.8 16.1-28.7l448-256c10.7-6.1 23.9-5.5 34 1.4z"/>
									</svg>
								</button>
							</div>
							<div class="ays-assistant-chatbox-notice-box" <?php echo $this->chatgpt_assistant_compliance_text === '' ? '' : 'style="padding:4px 20px"'; ?>	>
								<span><?php echo $this->chatgpt_assistant_compliance_text; ?></span>
							</div>
						</div>
					</div>
				</div>
				<?php echo ChatGPT_assistant_Data::get_chatbot_styles($this->chatbot_global_styles); ?>
				<?php
		$content = ob_get_clean();
		return $content;
	}

	public function show_chatgpt_greeting_message(){
		$content = array();
		if($this->chatgpt_assistant_greeting_message){
			$content[] = "<div class='ays-assistant-chatbox-ai-message-box' style='white-space: normal;'>";
				if ($this->chatbox_theme == 'chatgpt') {
					$content[] = "<div class='ays-assistant-chatbox-chatgpt-theme-ai-icon'>";
						$content[] = "<img src='" . esc_attr(CHATGPT_ASSISTANT_ADMIN_URL) . "/images/icons/chatgpt-icon.png'>";
					$content[] = "</div>";
				}

				$content[] = "<span class='ays-assistant-chatbox-ai-response-message'>";
					$content[] = $this->chatgpt_assistant_greeting_message_text;
				$content[] = "</span>";
			$content[] = "</div>";
		}
		
		return implode(' ' , $content);
	}



}
