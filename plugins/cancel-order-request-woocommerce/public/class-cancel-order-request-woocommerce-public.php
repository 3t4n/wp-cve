<?php


class Cancel_Order_Request_Woocommerce_Public {

	
	private $plugin_name;

	
	private $version;

	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	
	public function enqueue_styles() {


		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cancel-order-request-woocommerce-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'_magnific', plugin_dir_url( __FILE__ ) . 'css/magnific-popup.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-toast', plugin_dir_url( __FILE__ ) . 'css/jquery.toast.min.css', array(), $this->version, 'all' );
		
		$button_bg_color = get_option('pi_corw_reorder_button_background_color','#cccccc');
		$button_text_color = get_option('pi_corw_reorder_button_text_color','#000000');
		$css = "
			.pi-options-button a, .pi-navigation-link a{
				background-color:{$button_bg_color} !important;
				color:{$button_text_color } !important;
			}
		";
		wp_add_inline_style($this->plugin_name, $css);
		

	}

	
	public function enqueue_scripts() {


		wp_enqueue_script( $this->plugin_name.'_magnific', plugin_dir_url( __FILE__ ) . 'js/jquery.magnific-popup.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'-toast', plugin_dir_url( __FILE__ ) . 'js/jquery.toast.min.js', array( 'jquery'), $this->version, false );
		wp_enqueue_script( 'jquery-blockui' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cancel-order-request-woocommerce-public.js', array( 'jquery', $this->plugin_name.'_magnific', $this->plugin_name.'-toast' ), $this->version, false );

		$settings = array(
			'error_toast_bg' => get_option('pi_corw_reorder_error_background_color','#ff4747'),
			'success_toast_bg' => get_option('pi_corw_reorder_success_background_color','#51a564')
		);
		wp_localize_script($this->plugin_name, 'pi_corw_settings',$settings);

	}

}
