<?php
class Conditional_fees_Rule_Woocommerce_Public {

	
	private $plugin_name;

	
	private $version;

	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		Pi_cefw_Apply_fees::get_instance( );
	}

	
	public function enqueue_styles() {


		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/conditional-fees-rule-woocommerce-public.css', array(), $this->version, 'all' );

	}

	
	public function enqueue_scripts() {


		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/conditional-fees-rule-woocommerce-public.js', array( 'jquery' ), $this->version, false );

		if(defined( 'ET_CORE_VERSION' ) && apply_filters('pi_cefw_enable_divi_compatibility_js', true)){
            wp_enqueue_script( $this->plugin_name.'-divi-compatible', plugin_dir_url( __FILE__ ) . 'js/divi-fix.js', array( 'jquery'),$this->version);
        }

	}

}
