<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Ays_Chatgpt_Assistant
 * @subpackage Ays_Chatgpt_Assistant/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ays_Chatgpt_Assistant
 * @subpackage Ays_Chatgpt_Assistant/admin
 * @author     Ays_ChatGPT Assistant Team <info@ays-pro.com>
 */
class Chatgpt_Assistant_Admin {

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

	/**
	 * @var Chatgpt_Assistant_DB_Actions
	 */

     
	/**
	 * DB properties of this plugin.
    */
	private $dashboard_chat_db_name;
	private $front_chat_db_name;
	private $settings_db_name;
	private $general_settings_db_name;
	private $data_db_name;
	private $db_obj;

	private $capability;
         
	/**
	 * Set options of this plugin.
    */
    private $api_key;
	private $chatgpt_assistant_full_screen_mode;
	private $chatbox_position;
	private $chatbox_icon_position;
    private $chatgpt_assistant_show_dashboard_chat;
	private $chatgpt_assistant_chat_icon_size;
	private $chatgpt_assistant_chat_width;
	private $chatgpt_assistant_chat_width_format;
	private $chatgpt_assistant_chat_height;
	private $chatgpt_assistant_chat_height_format;
	private $chatgpt_assistant_auto_opening_chatbox;
	private $chatgpt_assistant_auto_opening_chatbox_delay;
	private $chatgpt_assistant_regenerate_response;
	private $chatgpt_assistant_message_placeholder;
	private $chatgpt_assistant_chatbot_name;
	private $chatgpt_assistant_compliance_text;
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
	private $chatgpt_assistant_greeting_message;
	private $chatgpt_assistant_greeting_message_default_text;
	private $chatgpt_assistant_greeting_message_text;
	private $chatbox_mode;
    private $chatbox_theme;
    private $chat_icon;
	private $user_profile_picture;
    private $chatgpt_assistant_enable_rate_chat;
    private $chatgpt_assistant_rate_chat_text;
	private $chatgpt_assistant_rate_chat_like;
	private $chatgpt_assistant_rate_chat_dislike;

	private $chatgpt_assistant_chatbox_background_color;
	private $chatgpt_assistant_chatbox_header_text_color;
	private $chatgpt_assistant_message_font_size;
	private $chatgpt_assistant_message_spacing;
	private $chatgpt_assistant_message_border_radius;
	private $chatgpt_assistant_chatbot_border_radius;
	private $message_bg_color;
	private $message_text_color;
	private $response_text_color;
	private $response_icons_color;
	private $response_bg_color;
    private $rates_table_obj;
    public $settings_obj;
	public $general_settings_obj;
	public $front_settings_obj;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
        global $wpdb;
		$this->plugin_name = $plugin_name;
		$this->version = $version;

        $this->dashboard_chat_db_name = $wpdb->prefix . CHATGPT_ASSISTANT_DB_PREFIX . 'dashboard_settings';
		$this->front_chat_db_name = $wpdb->prefix . CHATGPT_ASSISTANT_DB_PREFIX . 'front_settings';
		$this->settings_db_name = $wpdb->prefix . CHATGPT_ASSISTANT_DB_PREFIX . 'settings';
		$this->general_settings_db_name = $wpdb->prefix . CHATGPT_ASSISTANT_DB_PREFIX . 'general_settings';
		$this->data_db_name = $wpdb->prefix . CHATGPT_ASSISTANT_DB_PREFIX . 'data';

        $this->db_obj = new Chatgpt_Assistant_DB_Actions( $this->plugin_name, $this->data_db_name );

		// $this->db_obj = new Chatgpt_Assistant_DB_Actions( $this->plugin_name );
		// $this->settings_obj = new ChatGPT_Assistant_Settings_DB_Actions( $this->plugin_name );

		// include_once(plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/chatgpt-assistant-chatbox-display.php');
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles($hook_suffix) {
        wp_enqueue_style($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'css/admin.css', array(), $this->version, 'all');
		wp_enqueue_style( $this->plugin_name . '-chatbox', plugin_dir_url( __FILE__ ) . 'css/chatgpt-assistant-chat-bot.css', array(), $this->version . time(), 'all' );
		if (false === strpos($hook_suffix, $this->plugin_name))
            return;

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/chatgpt-assistant-admin.css', array(), $this->version . time(), 'all' );
        wp_enqueue_style( $this->plugin_name . "-pro-features", plugin_dir_url( __FILE__ ) . 'css/chatgpt-assistant-pro-features.css', array(), time(), 'all' );
		
		if ( false !== strpos($hook_suffix, $this->plugin_name) || false !== strpos( $hook_suffix, 'embedding' ) || strpos($hook_suffix, $this->plugin_name.'-front-chat') !== false || false !== strpos( $hook_suffix, 'logs' ) || false !== strpos( $hook_suffix, 'rates' )) {
			wp_enqueue_style( $this->plugin_name . '-select2', plugin_dir_url(__FILE__) .  'css/ays-select2.min.css', array(), $this->version, 'all');
			wp_enqueue_style( $this->plugin_name . '-bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version, 'all');
		}
		
		wp_enqueue_style( $this->plugin_name . '-sale-banner', plugin_dir_url(__FILE__) . 'css/chatgpt-banner.css', array(), $this->version, 'all');
		wp_enqueue_style( $this->plugin_name . '-banner', plugin_dir_url( __FILE__ ) . 'css/banner.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook_suffix) {

        if (false !== strpos($hook_suffix, "plugins.php")){
            wp_enqueue_script( $this->plugin_name . '-sweetalert-js', plugin_dir_url( __FILE__ ) . 'js/ays-chatgpt-assistant-sweetalert2.all.min.js', array('jquery'), $this->version, true );
            wp_enqueue_script( $this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'js/admin.js', array( 'jquery' ), $this->version, true );
            wp_localize_script( $this->plugin_name . '-admin', 'AysChatGptAdmin', array( 
            	'ajaxUrl' => admin_url( 'admin-ajax.php' )
            ) );
        }

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/chatgpt-assistant-admin.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( $this->plugin_name . '-main-functions', CHATGPT_ASSISTANT_ASSETS_URL . '/js/chatgpt-assistant-main-functions.js',  array( 'jquery' ), $this->version, false );
        wp_enqueue_script( $this->plugin_name . '-autosize', plugin_dir_url( __FILE__ ) . '/js/chatgpt-assistant-autosize.js', array( 'jquery' ), $this->version, false );
		if (false === strpos($hook_suffix, $this->plugin_name)) return;
        
        wp_enqueue_script( $this->plugin_name . '-sweetalert-js', plugin_dir_url( __FILE__ ) . 'js/ays-chatgpt-assistant-sweetalert2.all.min.js', array('jquery'), $this->version, true );
		wp_enqueue_script( $this->plugin_name . "-general-js", plugin_dir_url( __FILE__ ) . 'js/chatgpt-assistant-admin-general.js', array( 'jquery' ), $this->version, true );
        wp_localize_script( $this->plugin_name . "-general-js", 'aysChatGptAssistantGeneral', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'addImage'       => __( 'Add Image', $this->plugin_name ),
            'editImage'      => __( 'Edit Image', $this->plugin_name ),
            'removeImage'    => __( 'Remove Image', $this->plugin_name ),
            'translations' => array(
                'chatGreetingMessage' => __( 'Hello! I\'m an AI Assistant, and I\'m here to assist you with anything you need. How can I help you today?', "ays-chatgpt-assistant" ),
            )
		) );

		$chatgpt_banner_date = $this->ays_chatgpt_update_banner_time();
		if ( false !== strpos($hook_suffix, $this->plugin_name) || strpos($hook_suffix, $this->plugin_name.'-front-chat') !== false || false !== strpos( $hook_suffix, 'logs' ) || strpos($hook_suffix, $this->plugin_name.'-rates') !== false ) {
			wp_enqueue_script( $this->plugin_name . "-popper", plugin_dir_url(__FILE__) . 'js/popper.min.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( $this->plugin_name . "-bootstrap", plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( $this->plugin_name . '-select2js', plugin_dir_url( __FILE__ ) . 'js/ays-select2.min.js', array('jquery'), $this->version, true);
			wp_enqueue_script( $this->plugin_name . '-settings', plugin_dir_url( __FILE__ ) . 'js/chatgpt-assistant-admin-settings.js', array( 'jquery' ), $this->version, true );

			wp_localize_script( $this->plugin_name . '-settings', 'aysChatGptAssistantAdminSettings', array(
                'ajaxUrl'                   => admin_url( 'admin-ajax.php' ),
				'selectUserRoles'           => __( 'Select user roles', "ays-chatgpt-assistant" ),
				'delete'                    => __( 'Delete', "ays-chatgpt-assistant" ),
				'selectQuestionDefaultType' => __( 'Select question default type', "ays-chatgpt-assistant" ),
				'yes'                       => __( 'Yes', "ays-chatgpt-assistant" ),
				'cancel'                    => __( 'Cancel', "ays-chatgpt-assistant" ),
				'errorMsg'                  => __( 'Error', "ays-chatgpt-assistant" ),
				'somethingWentWrong'        => __( "Maybe something went wrong.", "ays-chatgpt-assistant" ),
				'failed'                    => __( 'Failed', "ays-chatgpt-assistant" ),
				'selectPage'                => __( 'Select page', "ays-chatgpt-assistant" ),
				'selectPostType'            => __( 'Select post type', "ays-chatgpt-assistant" ),
				'copied'                    => __( 'Copied!', "ays-chatgpt-assistant"),
				'clickForCopy'              => __( 'Click to copy', "ays-chatgpt-assistant"),
				'selectForm'                => __( 'Select form', "ays-chatgpt-assistant"),
				'chatgptBannerDate'         => $chatgpt_banner_date,
			) );
		}
        wp_enqueue_media();
		wp_localize_script( $this->plugin_name, 'aysChatGptAssistantAdminSettings', array(
			'chatgptBannerDate' => $chatgpt_banner_date,
		) );
	}

    /**
	 * De-register JavaScript files for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function disable_scripts($hook_suffix) {
        if (false !== strpos($hook_suffix, $this->plugin_name)) {
            if (is_plugin_active('ai-engine/ai-engine.php')) {
                wp_deregister_script('mwai');
                wp_deregister_script('mwai-vendor');
                wp_dequeue_script('mwai');
                wp_dequeue_script('mwai-vendor');
            }
        }
	}

	/**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu(){
        global $wpdb;

        $this->capability = 'manage_options';

        add_menu_page(
            __('ChatGPT Assistant', $this->plugin_name),
			__('ChatGPT Assistant', $this->plugin_name),
            $this->capability,
            $this->plugin_name,
			array($this, 'display_plugin_main_page'),
            CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/chatgpt-icon-menu-bw.svg',
            '6.224'
        );

        $this->general_settings_obj = new ChatGPT_Assistant_General_Settings_DB_Actions( $this->plugin_name , $this->general_settings_db_name );
        $this->settings_obj = new ChatGPT_Assistant_Settings_DB_Actions( $this->plugin_name , $this->settings_db_name );
    }

	public function add_plugin_settings_submenu(){
		$hook_settings = add_submenu_page( $this->plugin_name,
			__('Settings', $this->plugin_name),
			__('Settings', $this->plugin_name),
			'manage_options',
			$this->plugin_name,
			array($this, 'display_plugin_main_page')
		);

        $this->general_settings_obj = new ChatGPT_Assistant_General_Settings_DB_Actions( $this->plugin_name , $this->general_settings_db_name );
        $this->settings_obj = new ChatGPT_Assistant_Settings_DB_Actions( $this->plugin_name , $this->settings_db_name );
	}

	// Front settings
	public function add_plugin_front_chat_submenu(){
		$this->capability = 'manage_options';

		$hook_settings = add_submenu_page( $this->plugin_name,
			__('Front chat', $this->plugin_name),
			__('Front chat', $this->plugin_name),
			$this->capability,
			$this->plugin_name . '-front-chat',
			array($this, 'display_plugin_front_chat_page')
		);

        $this->general_settings_obj = new ChatGPT_Assistant_General_Settings_DB_Actions( $this->plugin_name , $this->general_settings_db_name );
        $this->settings_obj = new ChatGPT_Assistant_Settings_DB_Actions( $this->plugin_name , $this->settings_db_name );
		$this->front_settings_obj = new ChatGPT_Assistant_Front_Chat_DB_Actions( $this->plugin_name , $this->front_chat_db_name);
	}

    // Embedding settings 
	public function add_plugin_embedding_submenu(){
		$this->capability = 'manage_options';

		$hook_settings = add_submenu_page( $this->plugin_name,
			__('Embeddings', $this->plugin_name),
			__('Embeddings', $this->plugin_name),
			$this->capability,
			$this->plugin_name . '-embedding',
			array($this, 'display_plugin_embedding_page')
		);
	}

    public function add_plugin_logs_submenu(){
		$this->capability = 'manage_options';

		$hook_settings = add_submenu_page( $this->plugin_name,
			__('Logs', $this->plugin_name),
			__('Logs', $this->plugin_name),
			$this->capability,
			$this->plugin_name . '-logs',
			array($this, 'display_plugin_logs_page')
		);
	}

    public function add_plugin_rates_submenu(){
		$this->capability = 'manage_options';

		$hook_settings = add_submenu_page( $this->plugin_name,
			__('Rates', $this->plugin_name),
			__('Rates', $this->plugin_name),
			$this->capability,
			$this->plugin_name . '-rates',
			array($this, 'display_plugin_rates_page')
		);

		$this->rates_table_obj = new ChatGPT_Assistant_Rates_List_Table( $this->plugin_name);
	}

    public function add_plugin_content_generator_submenu(){
		$this->capability = 'manage_options';

        $hook_pro_features = add_submenu_page(
            $this->plugin_name,
            __('Content Generator', $this->plugin_name),
            __('Content Generator', $this->plugin_name),
			$this->capability,
            $this->plugin_name . '-content-generator',
            array($this, 'display_plugin_content_generator_page')
        );
    }
    
    public function add_plugin_image_generator_submenu(){
		$this->capability = 'manage_options';

        $hook_pro_features = add_submenu_page(
            $this->plugin_name,
            __('Image Generator', $this->plugin_name),
            __('Image Generator', $this->plugin_name),
			$this->capability,
            $this->plugin_name . '-image-generator',
            array($this, 'display_plugin_image_generator_page')
        );

		$this->general_settings_obj = new ChatGPT_Assistant_General_Settings_DB_Actions( $this->plugin_name , $this->general_settings_db_name );
    }
    
    public function add_plugin_how_to_use_submenu(){
		$this->capability = 'manage_options';

        $hook_pro_features = add_submenu_page(
            $this->plugin_name,
            __('How to Use', $this->plugin_name),
            __('How to Use', $this->plugin_name),
			$this->capability,
            $this->plugin_name . '-how-to-use',
            array($this, 'display_plugin_how_to_use_page')
        );
    }
    
    public function add_plugin_general_settings_submenu(){
		$this->capability = 'manage_options';
		
        $hook_settings = add_submenu_page( $this->plugin_name,
			__('General Settings', $this->plugin_name),
			__('General Settings', $this->plugin_name),
			'manage_options',
			$this->plugin_name . '-general-settings',
			array($this, 'display_plugin_general_settings_page')
		);

        $this->general_settings_obj = new ChatGPT_Assistant_General_Settings_DB_Actions( $this->plugin_name , $this->general_settings_db_name );
	}
    
    public function add_plugin_features_submenu(){
		$this->capability = 'manage_options';

        $hook_pro_features = add_submenu_page(
            $this->plugin_name,
            __('PRO Features', $this->plugin_name),
            __('PRO Features', $this->plugin_name),
			$this->capability,
            $this->plugin_name . '-features',
            array($this, 'display_plugin_features_page')
        );
    }
    
    public function add_plugin_gift_submenu(){
		$this->capability = 'manage_options';

        $hook_pro_features = add_submenu_page(
            $this->plugin_name,
            __('Grab Your GIFT', $this->plugin_name),
            __('Grab Your GIFT', $this->plugin_name),
			$this->capability,
            $this->plugin_name . '-gift',
            array($this, 'display_plugin_gift_page')
        );
    }

	public function display_plugin_main_page(){
		if (isset( $_POST[CHATGPT_ASSISTANT_NAME_PREFIX.'_save_bttn'] )) {
			$this->db_obj->store_data( $_POST );
		}
		include_once('partials/settings/chatgpt-assistant-settings.php');
    }

	public function display_plugin_settings_page(){
		include_once('partials/settings/chatgpt-assistant-settings.php');
	}

	public function display_plugin_front_chat_page(){
		include_once('partials/front-chat/chatgpt-assistant-front-chat-display.php');
	}

	public function display_plugin_embedding_page(){
		include_once('partials/embeddings/chatgpt-assistant-embedding-display.php');
	}

    public function display_plugin_logs_page(){
		include_once('partials/logs/chatgpt-assistant-logs-display.php');
	}

    public function display_plugin_rates_page(){
		include_once('partials/rates/chatgpt-assistant-rates-display.php');
	}

    public function display_plugin_how_to_use_page(){
        include_once('partials/chatgpt-assistant-data-display.php');
    }

    public function display_plugin_general_settings_page(){
		include_once('partials/general-settings/chatgpt-assistant-general-settings.php');
	}
    
    public function display_plugin_content_generator_page(){
		include_once('partials/content-generator/chatgpt-assistant-content-generator-display.php');
    }

    public function display_plugin_image_generator_page(){
		include_once('partials/image-generator/chatgpt-assistant-image-generator-display.php');
    }
    
    public function display_plugin_features_page(){
        include_once('partials/features/chatgpt-assistant-features-display.php');
    }
    
    public function display_plugin_gift_page(){
        include_once('partials/gifts/chatgpt-assistant-gifts-display.php');
    }

	public function chatgpt_display_chat_icon(){
        $this->settings_obj = new ChatGPT_Assistant_Settings_DB_Actions( $this->plugin_name , $this->settings_db_name );
        include_once(plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/chatgpt-assistant-chatbox-display.php');
    }
    
	public function set_global_settings(){
        $data = $this->db_obj->get_data();
		$this->api_key = isset( $data['api_key'] ) && $data['api_key'] != '' ? esc_attr( $data['api_key'] ) : '';

		$options = ($this->settings_obj->get_setting('options') === false) ? array() : json_decode($this->settings_obj->get_setting('options'), true);
		

        // * GENERAL SETTINGS *
            // ===== Shortcode settings =====
                // Full Screen Mode
                $this->chatgpt_assistant_full_screen_mode = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'full_screen_mode'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'full_screen_mode'] == 'on' ) ? true : false; 
            // ===== General settings =====
                // Chatbot position
                $this->chatbox_position = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'position'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'position'] != '' ) ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'position']) : 'right';
                // Chatbot position
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
                // Auto opening Chatbox
                $this->chatgpt_assistant_auto_opening_chatbox = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'auto_opening_chatbox'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'auto_opening_chatbox'] == 'on' ) ? true : false;
                // Auto opening Chatbox delay
                $this->chatgpt_assistant_auto_opening_chatbox_delay = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'auto_opening_chatbox_delay'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'auto_opening_chatbox_delay'] != '' ) ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'auto_opening_chatbox_delay']) : 0;
                // Show dashboard chat
                $this->chatgpt_assistant_show_dashboard_chat = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'show_dashboard_chat'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'show_dashboard_chat'] == 'off' ) ? false : true;
                // Regenerate Response
                $this->chatgpt_assistant_regenerate_response = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'regenerate_response'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'regenerate_response'] == 'on' ) ? true : false;

                // Enable rate chat
                $this->chatgpt_assistant_enable_rate_chat = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'enable_rate_chat'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'enable_rate_chat'] == 'on' ) ? true : false;

                // Rate chat text
                $this->chatgpt_assistant_rate_chat_text = isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'rate_chat_text'] ) ? stripslashes(sanitize_text_field($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'rate_chat_text'])) : __('How Satisfied are You?', 'chatgpt-assistant');

				// Rate chat options
				$chatgpt_assistant_rate_chat = (isset($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'rate_chat']) && !empty($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'rate_chat'])) ? $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'rate_chat'] : array();

				$this->chatgpt_assistant_rate_chat_like = isset($chatgpt_assistant_rate_chat['like']) && !empty($chatgpt_assistant_rate_chat['like']) ? $chatgpt_assistant_rate_chat['like'] : array();
				$this->chatgpt_assistant_rate_chat_like['action'] = (isset($this->chatgpt_assistant_rate_chat_like['action']) && ($this->chatgpt_assistant_rate_chat_like['action'] == 'feedback')) ? sanitize_text_field($this->chatgpt_assistant_rate_chat_like['action']) : 'feedback';
				$this->chatgpt_assistant_rate_chat_like['text'] = (isset($this->chatgpt_assistant_rate_chat_like['text']) && $this->chatgpt_assistant_rate_chat_like['text'] != '') ? stripslashes(sanitize_text_field($this->chatgpt_assistant_rate_chat_like['text'])) : '';

				$this->chatgpt_assistant_rate_chat_dislike = isset($chatgpt_assistant_rate_chat['dislike']) && !empty($chatgpt_assistant_rate_chat['dislike']) ? $chatgpt_assistant_rate_chat['dislike'] : array();
				$this->chatgpt_assistant_rate_chat_dislike['action'] = (isset($this->chatgpt_assistant_rate_chat_dislike['action']) && ($this->chatgpt_assistant_rate_chat_dislike['action'] == 'feedback')) ? sanitize_text_field($this->chatgpt_assistant_rate_chat_dislike['action']) : 'feedback';
				$this->chatgpt_assistant_rate_chat_dislike['text'] = (isset($this->chatgpt_assistant_rate_chat_dislike['text']) && $this->chatgpt_assistant_rate_chat_dislike['text'] != '') ? stripslashes(sanitize_text_field($this->chatgpt_assistant_rate_chat_dislike['text'])) : '';

                // Greeting message
                $this->chatgpt_assistant_greeting_message = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'greeting_message'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'greeting_message'] == 'on' ) ? true : false;
                // Greeting message text
                $this->chatgpt_assistant_greeting_message_default_text = 'Hello! I\'m an AI Assistant, and I\'m here to assist you with anything you need. How can I help you today?';
                $this->chatgpt_assistant_greeting_message_text = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'greeting_message_text'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'greeting_message_text'] != '' ) ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'greeting_message_text']) : $this->chatgpt_assistant_greeting_message_default_text;
                // Message placeholder
                $this->chatgpt_assistant_message_placeholder = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_placeholder'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_placeholder'] != '' ) ? stripslashes(esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'message_placeholder'])) : __('Enter your message ', 'chatgpt-assistant');
                // Message placeholder
                $this->chatgpt_assistant_chatbot_name = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbot_name'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbot_name'] != '' ) ? stripslashes(esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'chatbot_name'])) : __('AI Assistant', 'chatgpt-assistant');
                // compliance text
                $this->chatgpt_assistant_compliance_text = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'compliance_text'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'compliance_text'] != '' ) ? stripslashes(esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'compliance_text'])) : '';
            // ===== Chat settings =====
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
        //

        // * STYLE SETTINGS *
            // Chat theme
			$this->chatbox_theme = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX . 'chatbox_theme'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX . 'chatbox_theme'] != '' ) ? esc_attr($options[CHATGPT_ASSISTANT_OPTIONS_PREFIX . 'chatbox_theme']) : 'default';
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

                $this->chat_icon = CHATGPT_ASSISTANT_ADMIN_URL . "/images/icons/chatgpt-icon.png";
                /* === USER PROFILE PICTURE START === */
                $this->user_profile_picture = '<img alt="" src="' . CHATGPT_ASSISTANT_ADMIN_URL . '/images/icons/guest_icon_'.$guest_icon_index.'.png">';
                if ( is_user_logged_in() ) {
                    $current_user = wp_get_current_user();
                    $this->user_profile_picture = (get_avatar( $current_user->ID, 32 ));
                }
                /* === USER PROFILE PICTURE END === */

        //

        // Set Chatbot options for JS
        $generate_hush_api =  base64_encode($this->api_key . uniqid(uniqid('chgafr') . 'eds'));
		wp_localize_script( $this->plugin_name , 'AysChatGPTChatSettings', array(
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
		    'chatMessagePlaceholder' => $this->chatgpt_assistant_message_placeholder,
		    'chatBotName' => $this->chatgpt_assistant_chatbot_name,
            'chatboxTheme' => $this->chatbox_theme,
			'chatIcon' => $this->chat_icon,
			'userProfilePicture' => $this->user_profile_picture,
		    'chatAutoOpening' => $this->chatgpt_assistant_auto_opening_chatbox,
		    'chatAutoOpeningDelay' => $this->chatgpt_assistant_auto_opening_chatbox_delay,
		    'chatRegenerateResponse' => $this->chatgpt_assistant_regenerate_response,
		    'chatboxPosition' => $this->chatbox_position,
		    'chatboxIconPosition' => $this->chatbox_icon_position,
		    'chatboxIconSize' => $this->chatgpt_assistant_chat_icon_size,
            'translations' => array(
                'chatGreetingMessage' => __( $this->chatgpt_assistant_greeting_message_text, "ays-chatgpt-assistant" ),
                'endChat' => array(
					'warningMsg' => __( "Do you really want to leave the current chat?", "ays-chatgpt-assistant" ),
					'buttonMsg' => __( "End chat", "ays-chatgpt-assistant" ),
					'modalIcon' => esc_attr(CHATGPT_ASSISTANT_ADMIN_URL) . "/images/icons/leave-icon.svg"
				),
				'ka' => $generate_hush_api,

            )
		) );
	}

	public function ays_chatgpt_update_banner_time(){

        $date = time() + ( 3 * 24 * 60 * 60 ) + (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS);
        // $date = time() + ( 60 ) + (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS); // for testing | 1 min
        $next_3_days = date('M d, Y H:i:s', $date);

        $ays_chatgpt_banner_time = get_option('ays_chatgpt_banner_time');

        if ( !$ays_chatgpt_banner_time || is_null( $ays_chatgpt_banner_time ) ) {
            update_option('ays_chatgpt_banner_time', $next_3_days ); 
        }

        $get_ays_chatgpt_banner_time = get_option('ays_chatgpt_banner_time');

        $val = 60*60*24*0.5; // half day
        // $val = 60; // for testing | 1 min

        $current_date = current_time( 'mysql' );
        $date_diff = strtotime($current_date) - intval(strtotime($get_ays_chatgpt_banner_time));

        $days_diff = $date_diff / $val;
        if(intval($days_diff) > 0 ){
            update_option('ays_chatgpt_banner_time', $next_3_days);
			$get_ays_chatgpt_banner_time = get_option('ays_chatgpt_banner_time');
        }

        return $get_ays_chatgpt_banner_time;
    }
	
	public function ays_chatgpt_sale_baner(){

        
        // if (isset($_POST['ays_chatgpt_sale_btn']) && (isset( $_POST[CHATGPT_ASSISTANT_NAME . '-sale-banner'] ) && wp_verify_nonce( $_POST[CHATGPT_ASSISTANT_NAME . '-sale-banner'], CHATGPT_ASSISTANT_NAME . '-sale-banner' )) && current_user_can( 'manage_options' )) {
        //     update_option('ays_chatgpt_sale_btn', 1);
        //     update_option('ays_chatgpt_sale_date', current_time( 'mysql' ));
        // }
    
        $ays_chatgpt_sale_date = get_option('ays_chatgpt_sale_date');

        $val = 60*60*24*5;

        $current_date = current_time( 'mysql' );
        $date_diff = strtotime($current_date) - intval(strtotime($ays_chatgpt_sale_date)) ;
        
        $days_diff = $date_diff / $val;
    
        if(intval($days_diff) > 0 ){
            update_option('ays_chatgpt_sale_btn', 0);
        }
    
        $ays_chatgpt_maker_flag = intval(get_option('ays_chatgpt_sale_btn'));
        if( $ays_chatgpt_maker_flag == 0 ){
            if (isset($_GET['page']) && strpos($_GET['page'], CHATGPT_ASSISTANT_NAME) !== false) {
				$this->ays_chatgpt_sale_message20($ays_chatgpt_maker_flag);
				// $this->ays_chatgpt_helloween_message($ays_chatgpt_maker_flag);
				// $this->ays_chatgpt_black_friday_message($ays_chatgpt_maker_flag);
            }
        }
    }

    public function ays_chatgpt_dismiss_button(){

        $data = array(
            'status' => false,
        );

        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'ays_chatgpt_dismiss_button') { 
            if ( (isset( $_REQUEST['_ajax_nonce'] ) && wp_verify_nonce( $_REQUEST['_ajax_nonce'], CHATGPT_ASSISTANT_NAME . '-sale-banner' )) && current_user_can( 'manage_options' )){
                update_option('ays_chatgpt_sale_btn', 1);
                update_option('ays_chatgpt_sale_date', current_time( 'mysql' ));
                $data['status'] = true;
            } else if ((isset( $_REQUEST['_ajax_nonce'] ) && wp_verify_nonce( $_REQUEST['_ajax_nonce'], CHATGPT_ASSISTANT_NAME . '-gift-banner' )) && current_user_can( 'manage_options' )) { 
                update_option('ays_chatgpt_gift_btn', 1);
                update_option('ays_chatgpt_gift_date', current_time( 'mysql' ));
                $data['status'] = true;
            }
        }

        ob_end_clean();
        $ob_get_clean = ob_get_clean();
        echo json_encode($data);
        wp_die();

    }

    public function ays_chatgpt_gift_baner(){
        
        // if (isset($_POST['ays_chatgpt_gift_btn']) && (isset( $_POST[CHATGPT_ASSISTANT_NAME . '-gift-banner'] ) && wp_verify_nonce( $_POST[CHATGPT_ASSISTANT_NAME . '-gift-banner'], CHATGPT_ASSISTANT_NAME . '-gift-banner' )) && current_user_can( 'manage_options' )) {
        //     update_option('ays_chatgpt_gift_btn', 1);
        //     update_option('ays_chatgpt_gift_date', current_time( 'mysql' ));
        // }
    
        $ays_chatgpt_gift_date = get_option('ays_chatgpt_gift_date');

        $val = 60*60*24*5;

        $current_date = current_time( 'mysql' );
        $date_diff = strtotime($current_date) - intval(strtotime($ays_chatgpt_gift_date)) ;
        
        $days_diff = $date_diff / $val;
    
        if(intval($days_diff) > 0 ){
            update_option('ays_chatgpt_gift_btn', 0);
        }
    
        $ays_chatgpt_maker_flag = intval(get_option('ays_chatgpt_gift_btn'));
        if( $ays_chatgpt_maker_flag == 0 ){
            if (isset($_GET['page']) && strpos($_GET['page'], CHATGPT_ASSISTANT_NAME) !== false) {
				$this->ays_chatgpt_gift_message($ays_chatgpt_maker_flag);
            }
        }
    }

    // Gift banner
    public function ays_chatgpt_gift_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-chatgpt-dicount-month-main" class="notice notice-success is-dismissible ays_chatgpt_dicount_info" style="padding-top:10px;padding-bottom:10px">';
                $content[] = '<div id="ays-chatgpt-dicount-month" class="ays_chatgpt_dicount_month">';
                    $content[] = '<a href="' . admin_url('admin.php?page='.$this->plugin_name.'-gift') . '" target="_blank" class="ays-chatgpt-sale-banner-link" ><img src="' . esc_attr(CHATGPT_ASSISTANT_ADMIN_URL) . '/images/icons/chatgpt-icon.png"></a>';

                    $content[] = '<div class="ays-chatgpt-dicount-wrap-box">';

                        $content[] = '<strong style="font-weight:bold;width:100%;font-size:20px">';
                            $content[] = 'Get the <a style="color:#1FAB90;" href="' . admin_url('admin.php?page='.$this->plugin_name.'-gift') . '" target="_blank">PRO Version</a> for free.';
                        $content[] = '</strong>';
                            
                    $content[] = '</div>';

                    $content[] = '<div class="ays-chatgpt-dicount-wrap-box ays-buy-now-button-box" style="width:20%">';
                        $content[] = '<a href="' . admin_url('admin.php?page='.$this->plugin_name.'-gift') . '" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank">' . __( 'Learn more!', "chatgpt-assistant" ) . '</a>';
                    $content[] = '</div>';

                $content[] = '</div>';

                $content[] = '<div style="position: absolute;right: 0;bottom: 1px;" class="ays-chatgpt-dismiss-buttons-container-for-form">';
                    $content[] = '<form action="" method="POST">';
                        $content[] = '<div id="ays-chatgpt-dismiss-buttons-content">';
                            if (current_user_can( 'manage_options' )) {
                                $content[] = '<button class="btn btn-link ays-button" name="ays_chatgpt_gift_btn" style="height: 32px; margin-left: 0;padding-left: 0; color: #979797;">Dismiss ad</button>';
                                $content[] = wp_nonce_field( CHATGPT_ASSISTANT_NAME . '-gift-banner' ,  CHATGPT_ASSISTANT_NAME . '-gift-banner' );
                            }
                        $content[] = '</div>';
                    $content[] = '</form>';
                $content[] = '</div>';

            $content[] = '</div>';

            $content = implode( '', $content );
            // echo $content;
            echo html_entity_decode(esc_html( $content ));
        }
    }

	// Self 20% sale
    public static function ays_chatgpt_sale_message20 ($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-chatgpt-dicount-month-main" class="notice notice-success is-dismissible ays_chatgpt_dicount_info">';
                $content[] = '<div id="ays-chatgpt-dicount-month" class="ays_chatgpt_dicount_month">';
                    // $content[] = '<a href="https://ays-pro.com/wordpress/chatgpt-assistant" target="_blank" class="ays-chatgpt-sale-banner-link"><img src="' . CHATGPT_ASSISTANT_ADMIN_URL . '/images/ays_chatgpt_logo.png"></a>';

                    $content[] = '<div class="ays-chatgpt-dicount-wrap-box ays-chatgpt-dicount-wrap-text-box">';

                        $content[] = '<div class="ays-chatgpt-dicount-sale-name-discount-box">';
							$content[] = '<span class="ays-chatgpt-new-chatgpt-pro-title">';
								$content[] = __( "<span><a href='https://ays-pro.com/wordpress/chatgpt-assistant?utm_source=dashboard&utm_medium=gpt-free&utm_campaign=sale-banner' target='_blank' style='color:#ffffff; text-decoration: underline;'>ChatGPT Assistant</a></span>", CHATGPT_ASSISTANT_NAME );
							$content[] = '</span>';
							$content[] = '<div>';
								$content[] = '<img src="' . CHATGPT_ASSISTANT_ADMIN_URL . '/images/ays-chatgpt-banner-sale-20.svg" style="width: 70px;">';
							$content[] = '</div>';
						$content[] = '</div>';

                        $content[] = '<span class="ays-chatgpt-new-chatgpt-pro-desc">';
							$content[] = '<img class="ays-chatgpt-new-chatgpt-pro-guaranteeicon" src="' . CHATGPT_ASSISTANT_ADMIN_URL . '/images/chatgpt-assistant-guaranteeicon.webp" style="width: 30px;">';
							$content[] = __( "30 Days Money Back Guarantee", CHATGPT_ASSISTANT_NAME );
						$content[] = '</span>';
     
                        $content[] = '<div style="position: absolute;right: 10px;bottom: 1px;" class="ays-chatgpt-dismiss-buttons-container-for-chatgpt">';
                            $content[] = '<form action="" method="POST">';
                                $content[] = '<div id="ays-chatgpt-dismiss-buttons-content">';
                                    if (current_user_can( 'manage_options' )) {
                                        $content[] = '<button class="btn btn-link ays-button" name="ays_chatgpt_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0">Dismiss ad</button>';
                                        $content[] = wp_nonce_field( CHATGPT_ASSISTANT_NAME . '-sale-banner' ,  CHATGPT_ASSISTANT_NAME . '-sale-banner' );
                                    }
                                $content[] = '</div>';
                            $content[] = '</form>';
                        $content[] = '</div>';

                    $content[] = '</div>';

                    $content[] = '<div class="ays-chatgpt-dicount-wrap-box ays-chatgpt-dicount-wrap-countdown-box">';

                        $content[] = '<div id="ays-chatgpt-countdown-main-container">';
                            $content[] = '<div class="ays-chatgpt-countdown-container">';
                                $content[] = '<div id="ays-chatgpt-countdown" style="display: block;">';
                                    $content[] = __( "Offer ends in:", CHATGPT_ASSISTANT_NAME );
                                    
                                    $content[] = '<ul style="padding: 0">';
                                        $content[] = '<li><span id="ays-chatgpt-countdown-days">0</span>' . __( 'Days', CHATGPT_ASSISTANT_NAME ) . '</li>';
                                        $content[] = '<li><span id="ays-chatgpt-countdown-hours">0</span>' . __( 'Hours', CHATGPT_ASSISTANT_NAME ) . '</li>';
                                        $content[] = '<li><span id="ays-chatgpt-countdown-minutes">0</span>' . __( 'Minutes', CHATGPT_ASSISTANT_NAME ) . '</li>';
                                        $content[] = '<li><span id="ays-chatgpt-countdown-seconds">0</span>' . __( 'Seconds', CHATGPT_ASSISTANT_NAME ) . '</li>';
                                    $content[] = '</ul>';
                                $content[] = '</div>';

                                $content[] = '<div id="ays-chatgpt-countdown-content" class="emoji" style="display: none;">';
                                    $content[] = '<span>🚀</span>';
                                    $content[] = '<span>⌛</span>';
                                    $content[] = '<span>🔥</span>';
                                    $content[] = '<span>💣</span>';
                                $content[] = '</div>';
                            $content[] = '</div>';
                        $content[] = '</div>';
                            
                    $content[] = '</div>';

                    $content[] = '<div class="ays-chatgpt-dicount-wrap-box ays-chatgpt-dicount-wrap-button-box">';
                        $content[] = '<a href="https://ays-pro.com/wordpress/chatgpt-assistant?utm_source=dashboard&utm_medium=gpt-free&utm_campaign=sale-banner" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank" style="" >' . __( 'Buy Now', CHATGPT_ASSISTANT_NAME ) . '</a>';
                        $content[] = '<span class="ays-chatgpt-dicount-one-time-text">';
                            $content[] = __( "One-time payment", CHATGPT_ASSISTANT_NAME );
                        $content[] = '</span>';
                    $content[] = '</div>';

                $content[] = '</div>';

            $content[] = '</div>';

            $content = implode( '', $content );
            echo $content;
        }
    }

    // Helloween banner
    public static function ays_chatgpt_helloween_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-chatgpt-dicount-month-main-helloween" class="notice notice-success is-dismissible ays_chatgpt_dicount_info">';
                $content[] = '<div id="ays-chatgpt-dicount-month-helloween" class="ays_chatgpt_dicount_month_helloween">';
                    $content[] = '<div class="ays-chatgpt-dicount-wrap-box-helloween-limited">';

                        $content[] = '<p>';
                            $content[] = __( "Limited Time 
                            <span class='ays-chatgpt-dicount-wrap-color-helloween' style='color:#b2ff00;'>30%</span> 
                            <span>
                                SALE on
                            </span> 
                            <br>
                            <span style='' class='ays-chatgpt-helloween-bundle'>
                                <a href='https://ays-pro.com/wordpress/chatgpt-assistant?utm_source=dashboard&utm_medium=gpt-free&utm_campaign=halloween-sale-banner' target='_blank' class='ays-chatgpt-dicount-wrap-color-helloween ays-chatgpt-dicount-wrap-text-decoration-helloween' style='display:block; color:#b2ff00;margin-right:6px;'>
                                    ChatGPT Assistant
                                </a>
                            </span>", CHATGPT_ASSISTANT_NAME );
                        $content[] = '</p>';
                        $content[] = '<p>';
                                $content[] = __( "Hurry up! 
                                                <a href='https://ays-pro.com/wordpress/chatgpt-assistant?utm_source=dashboard&utm_medium=gpt-free&utm_campaign=halloween-sale-banner' target='_blank' style='color:#ffc700;'>
                                                    Check it out!
                                                </a>", CHATGPT_ASSISTANT_NAME );
                        $content[] = '</p>';
                            
                    $content[] = '</div>';

                    
                    $content[] = '<div class="ays-chatgpt-helloween-bundle-buy-now-timer">';
                        $content[] = '<div class="ays-chatgpt-dicount-wrap-box-helloween-timer">';
                            $content[] = '<div id="ays-chatgpt-countdown-main-container" class="ays-chatgpt-countdown-main-container-helloween">';
                                $content[] = '<div class="ays-chatgpt-countdown-container-helloween">';
                                    $content[] = '<div id="ays-chatgpt-countdown">';
                                        $content[] = '<ul>';
                                            $content[] = '<li><p><span id="ays-chatgpt-countdown-days"></span><span>days</span></p></li>';
                                            $content[] = '<li><p><span id="ays-chatgpt-countdown-hours"></span><span>Hours</span></p></li>';
                                            $content[] = '<li><p><span id="ays-chatgpt-countdown-minutes"></span><span>Mins</span></p></li>';
                                            $content[] = '<li><p><span id="ays-chatgpt-countdown-seconds"></span><span>Secs</span></p></li>';
                                        $content[] = '</ul>';
                                    $content[] = '</div>';

                                    $content[] = '<div id="ays-chatgpt-countdown-content" class="emoji">';
                                        $content[] = '<span>🚀</span>';
                                        $content[] = '<span>⌛</span>';
                                        $content[] = '<span>🔥</span>';
                                        $content[] = '<span>💣</span>';
                                    $content[] = '</div>';

                                $content[] = '</div>';

                            $content[] = '</div>';
                                
                        $content[] = '</div>';
                        $content[] = '<div class="ays-chatgpt-dicount-wrap-box ays-buy-now-button-box-helloween">';
                            $content[] = '<a href="https://ays-pro.com/wordpress/chatgpt-assistant?utm_source=dashboard&utm_medium=gpt-free&utm_campaign=halloween-sale-banner" class="button button-primary ays-buy-now-button-helloween" id="ays-button-top-buy-now-helloween" target="_blank" style="" >' . __( 'Buy Now !', CHATGPT_ASSISTANT_NAME ) . '</a>';
                        $content[] = '</div>';
                    $content[] = '</div>';

                $content[] = '</div>';

                $content[] = '<div style="position: absolute;right: 0;bottom: 1px;"  class="ays-chatgpt-dismiss-buttons-container-for-form-helloween">';
                    $content[] = '<form action="" method="POST">';
                        $content[] = '<div id="ays-chatgpt-dismiss-buttons-content-helloween">';
                        if( current_user_can( 'manage_options' ) ){
                            $content[] = '<button class="btn btn-link ays-button-helloween" name="ays_chatgpt_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0">Dismiss ad</button>';
                            $content[] = wp_nonce_field( CHATGPT_ASSISTANT_NAME . '-sale-banner' ,  CHATGPT_ASSISTANT_NAME . '-sale-banner' );
                        }
                        $content[] = '</div>';
                    $content[] = '</form>';

                $content[] = '</div>';
                // $content[] = '<button type="button" class="notice-dismiss">';
                // $content[] = '</button>';
            $content[] = '</div>';

            $content = implode( '', $content );

            echo $content;
        }
    }

    // Black Friday banner
    public static function ays_chatgpt_black_friday_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-chatgpt-dicount-black-friday-month-main" class="notice notice-success is-dismissible ays_chatgpt_dicount_info">';
                $content[] = '<div id="ays-chatgpt-dicount-black-friday-month" class="ays_chatgpt_dicount_month">';
                    $content[] = '<div class="ays-chatgpt-dicount-black-friday-box">';
                        $content[] = '<div class="ays-chatgpt-dicount-black-friday-wrap-box ays-chatgpt-dicount-black-friday-wrap-box-80" style="width: 70%;">';
                            $content[] = '<div class="ays-chatgpt-dicount-black-friday-title-row">' . __( 'Limited Time', "chatgpt-assistant" ) .' '. '<a href="https://ays-pro.com/wordpress/chatgpt-assistant?utm_medium=gpt-free&utm_campaign=black-friday-sale-banner" class="ays-chatgpt-dicount-black-friday-button-sale" target="_blank">' . __( 'Sale', "chatgpt-assistant" ) . '</a>' . '</div>';
                            $content[] = '<div class="ays-chatgpt-dicount-black-friday-title-row">' . __( 'ChatGPT Assistant plugin', "chatgpt-assistant" ) . '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-chatgpt-dicount-black-friday-wrap-box ays-chatgpt-dicount-black-friday-wrap-text-box">';
                            $content[] = '<div class="ays-chatgpt-dicount-black-friday-text-row">' . __( '20% off', "chatgpt-assistant" ) . '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-chatgpt-dicount-black-friday-wrap-box" style="width: 25%;">';
                            $content[] = '<div id="ays-chatgpt-countdown-main-container">';
                                $content[] = '<div class="ays-chatgpt-countdown-container">';
                                    $content[] = '<div id="ays-chatgpt-countdown" style="display: block;">';
                                        $content[] = '<ul>';
                                            $content[] = '<li><span id="ays-chatgpt-countdown-days">0</span>' . __( 'Days', "chatgpt-assistant" ) . '</li>';
                                            $content[] = '<li><span id="ays-chatgpt-countdown-hours">0</span>' . __( 'Hours', "chatgpt-assistant" ) . '</li>';
                                            $content[] = '<li><span id="ays-chatgpt-countdown-minutes">0</span>' . __( 'Minutes', "chatgpt-assistant" ) . '</li>';
                                            $content[] = '<li><span id="ays-chatgpt-countdown-seconds">0</span>' . __( 'Seconds', "chatgpt-assistant" ) . '</li>';
                                        $content[] = '</ul>';
                                    $content[] = '</div>';
                                    $content[] = '<div id="ays-chatgpt-countdown-content" class="emoji" style="display: none;">';
                                        $content[] = '<span>🚀</span>';
                                        $content[] = '<span>⌛</span>';
                                        $content[] = '<span>🔥</span>';
                                        $content[] = '<span>💣</span>';
                                    $content[] = '</div>';
                                $content[] = '</div>';
                            $content[] = '</div>';
                        $content[] = '</div>';

                        $content[] = '<div class="ays-chatgpt-dicount-black-friday-wrap-box" style="width: 25%;">';
                            $content[] = '<a href="https://ays-pro.com/wordpress/chatgpt-assistant?utm_medium=gpt-free&utm_campaign=black-friday-sale-banner" class="ays-chatgpt-dicount-black-friday-button-buy-now" target="_blank">' . __( 'Get Your Deal', "chatgpt-assistant" ) . '</a>';
                        $content[] = '</div>';
                    $content[] = '</div>';
                $content[] = '</div>';

                $content[] = '<div style="position: absolute;right: 0;bottom: 1px;"  class="ays-chatgpt-dismiss-buttons-container-for-form-black-friday">';
                    $content[] = '<form action="" method="POST">';
                        $content[] = '<div id="ays-chatgpt-dismiss-buttons-content-black-friday">';
                            if( current_user_can( 'manage_options' ) ){
                                $content[] = '<button class="btn btn-link ays-button-black-friday" name="ays_chatgpt_sale_btn" style="">' . __( 'Dismiss ad', "chatgpt-assistant" ) . '</button>';
                                $content[] = wp_nonce_field( CHATGPT_ASSISTANT_NAME . '-sale-banner' ,  CHATGPT_ASSISTANT_NAME . '-sale-banner' );
                            }
                        $content[] = '</div>';
                    $content[] = '</form>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );

            echo $content;
        }
    }

    public function add_action_links($links){
        /*
        *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
        */
        $settings_link = array(
            '<a href="' . admin_url('admin.php?page=' . $this->plugin_name) . '">' . __('Settings', "ays-chatgpt-assistant") . '</a>',
            '<a href="https://plugins.ays-demo.com/wordpress-chatgpt-plugin-demo/" target="_blank">' . __('Demo', "ays-chatgpt-assistant") . '</a>',
            '<a href="https://ays-pro.com/wordpress/chatgpt-assistant?utm_source=dashboard&utm_medium=gpt-free&utm_campaign=plugins-buy-now-button" target="_blank" id="ays-chatgpt-plugins-buy-now-button">' . __('Upgrade 20% sale', "ays-chatgpt-assistant") . '</a>',
        );
        return array_merge($settings_link, $links);

    }

    public function deactivate_plugin_option(){
        $request_value = $_REQUEST['upgrade_plugin'];
        $upgrade_option = get_option( 'ays_chatgpt_assistant_upgrade_plugin', '' );
        if($upgrade_option === ''){
            add_option( 'ays_chatgpt_assistant_upgrade_plugin', $request_value );
        }else{
            update_option( 'ays_chatgpt_assistant_upgrade_plugin', $request_value );
        }
        return json_encode( array( 'option' => get_option( 'ays_chatgpt_assistant_upgrade_plugin', '' ) ) );
    }

    public function ays_chatgpt_admin_ajax(){
		global $wpdb;

		$response = array(
			"status" => false
		);

		$function = isset($_REQUEST['function']) ? sanitize_text_field( $_REQUEST['function'] ) : null;

		if($function !== null){
			$response = array();
			if( is_callable( array( $this, $function ) ) ){
				$response = $this->$function();

	            ob_end_clean();
	            $ob_get_clean = ob_get_clean();
				echo json_encode( $response );
				wp_die();
			}
        }
        ob_end_clean();
        $ob_get_clean = ob_get_clean();
		echo json_encode( $response );
		wp_die();
	}

    public function ays_chatgpt_connect(){
        $settings_obj = new ChatGPT_Assistant_Settings_DB_Actions( $this->plugin_name , $this->settings_db_name );
        $api_key      = isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_api_key']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_api_key'] != '' ? esc_attr($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_api_key']) : '';
		$method       = isset($_REQUEST['rMethod']) && $_REQUEST['rMethod'] != '' ? esc_attr($_REQUEST['rMethod']) : '';
		$request_type = isset($_REQUEST['request_type']) && $_REQUEST['request_type'] != '' ? esc_attr($_REQUEST['request_type']) : '';
		$check_openai_connection_code = false;
		$check_openai_connection = ChatGPT_assistant_Data::makeRequest($api_key, 'GET', 'models');
        $response_data = array('status' => false);
        
        if(is_array($check_openai_connection)){
            $check_openai_connection_code = isset($check_openai_connection['openai_response_code']) && $check_openai_connection['openai_response_code'] == 200 ? true : false; 
		}

		if($check_openai_connection_code){
			$this->db_obj->store_data();
            $response_data['status'] = true;
            $options = ($settings_obj->get_setting('options') === false) ? array() : json_decode($settings_obj->get_setting('options'), true);
			$chatbox_mode = ( isset( $options['chatbox_mode'] ) && $options['chatbox_mode'] != '' ) ? $options['chatbox_mode'] : 'light';
			$chatgpt_assistant_show_dashboard_chat = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'show_dashboard_chat'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'show_dashboard_chat'] == 'off' ) ? false : true;
            $chatgpt_assistant_show_dashboard_chat = ( isset( $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'show_dashboard_chat'] ) && $options[CHATGPT_ASSISTANT_OPTIONS_PREFIX.'show_dashboard_chat'] == 'off' ) ? false : true;

			if ($chatgpt_assistant_show_dashboard_chat) {
				$response_data['chatbot_html'] = ChatGPT_assistant_Data::get_chatbot_main_box($chatbox_mode, $api_key, $options);
			} else {
				$response_data['chatbot_html'] = '';
			}
		}
        $response_data['openai_connection'] = $check_openai_connection;


		return $response_data;
	}

	public function ays_chatgpt_disconnect(){
        $id = isset($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_id']) && $_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_id'] != '' ? esc_attr($_REQUEST[CHATGPT_ASSISTANT_NAME_PREFIX.'_id']) : '';

		$status = $this->db_obj->update_setting($id , "" , 'api_key' , 'id');		

		return $status;
	}

    public function ays_chatgpt_save_feedback(){
		global $wpdb;
		$rates_table = $wpdb->prefix . CHATGPT_ASSISTANT_DB_PREFIX . 'rates';

		$data = isset($_REQUEST['feedback_data']) && $_REQUEST['feedback_data'] != '' ? json_decode(stripslashes($_REQUEST['feedback_data']), true) : array();

		global $current_user;
		$user_name = isset($data['user_name']) && $data['user_name'] != '' ? sanitize_text_field($data['user_name']) : (isset($current_user->user_login) && $current_user->user_login != '' ? sanitize_text_field($current_user->user_login) : '');
		$user_email = isset($data['user_email']) && $data['user_email'] != '' ? sanitize_text_field($data['user_email']) : (isset($current_user->user_email) && $current_user->user_email != '' ? sanitize_text_field($current_user->user_email) : '');
		
		$user_id = get_current_user_id();
		$date = current_time('mysql');

		$post_id = isset($data['post_id']) && $data['post_id'] != '' ? intval($data['post_id']) : 0;
		
		$source = isset($data['source']) && $data['source'] != '' ? sanitize_text_field($data['source']) : '';
		$type = isset($data['type']) && $data['type'] != '' ? sanitize_text_field($data['type']) : '';
		$feedback_action = isset($data['feedback_action']) && $data['feedback_action'] != '' ? sanitize_text_field($data['feedback_action']) : '';
		$feedback = isset($data['feedback']) && $data['feedback'] != '' ? esc_attr(stripslashes(sanitize_text_field($data['feedback']))) : '';

		if (trim($feedback) == '') {
			return false;
		}

		$result = $wpdb->insert(
			$rates_table,
			array(
                'post_id' => $post_id,
                'user_id' => $user_id,
                'user_name' => $user_name,
                'user_email' => $user_email,
                'date' => $date,
                'chat_source' => $source,
                'chat_type' => $type,
                'feedback' => $feedback,
                'action' => $feedback_action,
			),
			array( '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
		);

		if ($result && $result > 0) {
			return true;
		}

		return false;
	}
}
