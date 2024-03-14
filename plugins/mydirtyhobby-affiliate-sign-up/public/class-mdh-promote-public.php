<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.mydirtyhobby.com/registrationplugin
 * @since      1.0.0
 *
 * @package    Mdh_Promote
 * @subpackage Mdh_Promote/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Mdh_Promote
 * @subpackage Mdh_Promote/public
 * @author     Mg <info@mindgeek.com>
 */
class Mdh_Promote_Public {

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

	private $form_action;

	private $redirect_link;

	private $profile_pic_link;

	private $nav_register_btn_txt;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

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
		 * defined in Mdh_Promote_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mdh_Promote_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mdh-promote-public.css', array(), $this->version, 'all' );

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
		 * defined in Mdh_Promote_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mdh_Promote_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mdh-promote-public.js', array( 'jquery' ), $this->version, false );

		if ( ! wp_script_is( 'jquery', 'enqueued' )) {

			//Enqueue
			wp_enqueue_script( 'jquery' );

		}

	}


	public function load_popup(){

	    $lang = get_option('mdh-promo_register_popup_lang');

	    $popup_lang = $lang ? $lang : 'en';

	    $code_type_selected = get_option('mdh-promo_promo_code_type');

        $promo_code = get_option('mdh-promo_code');

        $profile_pic_link = get_option('mdh-promo_profile_pic_link');

        $this->profile_pic_link = $profile_pic_link ? $profile_pic_link : plugins_url( 'img/default_profile.jpg', __FILE__ );

	    $code_type = ($code_type_selected ==! false && $promo_code !== '') ? $code_type_selected : 'default';

	    $this->redirect_link = str_replace ('https://www.mydirtyhobby.com','',get_option('mdh-promo_profile_link'));

	    $base_url = "https://www.mydirtyhobby.com";

        $code_types = [
            'default' => '/n/register',
            'naff'    => "/n/register?naff=",
            'ats'     => "/n/register?ats="
        ];

	    $this->form_action = $base_url.$code_types[$code_type].$promo_code;

        if($code_type !== 'default') {
            $this->form_action .='&skipUserWizard=true';
        }
	    include_once 'partials/mdh-promote-popup-'.$popup_lang.'.php';
    }


    public function add_menu_register_btn($items, $args) {

        $register_btn_txt = get_option('mdh-promo_nav_register_btn_txt');

        $this->nav_register_btn_txt = $register_btn_txt ? $register_btn_txt : 'Register';

			$items .= '<li class="menu-item"><button type="button" class="mdhRegister-btn" data-toggle="modal" style="margin: 5px" data-target="#mdhRegister">'.$this->nav_register_btn_txt.'</button></li>';
        return $items;
    }


    public function register_shortcodes()
    {
        add_shortcode('mdh_register_btn',[$this, 'mdh_register_btn']);
    }


    public function mdh_register_btn($att, $content = null)
    {
        $a = shortcode_atts( array(
            'class' => 'mdhRegister-btn '.get_option('mdh-promo-sc-register-btn'),
        ), $att );

        return '<button class="'.esc_attr($a['class']).'" >' . $content . '</button>';
    }

}
