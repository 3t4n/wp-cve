<?php

if ( !class_exists( 'APSC_Third_Party' ) ) :

final class APSC_Third_Party
{

	private $ThirdParty;

    public function __construct()
	{

		global $APSC;
		
		$this->ThirdParty = new stdClass;
		
		add_action( $APSC->main_slug . '_init' , array( $this , 'init' ) , 9 );
		
    }
	
	public function init()
	{
		
		$this->define_constants();
		$this->includes();
		
	}
	
	private function define_constants()
	{
		
		global $APSC;

		if( ! function_exists( 'is_plugin_active' ) ) {

			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		}
		
		$check_plugins = array(
			'debug_bar' => 'debug-bar/debug-bar.php',
		);
		
		if( empty( $check_plugins ) ) {

			return false;
			
		}
			
		$plugins = array();

		foreach( $check_plugins as $name => $base_name ) {
			
			if( is_plugin_active( $base_name ) ) {
				
				$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $base_name );
				$plugins[$name] = (object) array( 'ver' => $plugin_data['Version'] );
				
			}

		}
		
		if( !empty( $plugins ) ) {

			$this->ThirdParty = (object) $plugins;
			$APSC->ThirdParty = $this->ThirdParty;
			
		}
		
	}

	private function includes()
	{

		global $APSC;

	}

}

new APSC_Third_Party();

endif;
