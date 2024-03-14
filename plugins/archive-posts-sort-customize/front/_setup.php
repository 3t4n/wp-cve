<?php

if ( !class_exists( 'APSC_Front_Setup' ) ) :

final class APSC_Front_Setup
{

	private $authority = 'setup';
	private $screen    = 'front';

	private $assets_url;
	private $script_slug;

	public function __construct()
	{
		
		global $APSC;
		
		$this->assets_url  = $APSC->plugin_url . trailingslashit( $this->screen ) . trailingslashit( 'assets' );
		$this->script_slug = $APSC->main_slug . '_' . $this->authority;
		
		add_action( $APSC->main_slug . '_screen' , array( $this , 'init' ) , 20 );

		add_action( $APSC->main_slug . '_before_' . $this->screen . '_init' , array( $this , 'before_init' ) , 20 );
		add_action( $APSC->main_slug . '_before_not_' . $this->screen . '_init' , array( $this , 'before_not_init' ) , 20 );

	}
	
	public function init()
	{
		
		global $APSC;
		
		if( ! empty( $APSC->Env->is_admin ) ) {

			return false;
			
		}
		
		if( $APSC->Env->is_ajax ) {
			
			return false;
			
		}

		//add_action( 'wp_enqueue_scripts' , array( $this , 'wp_enqueue_scripts' ) );

	}
	
	public function wp_enqueue_scripts()
	{
		
		global $APSC;
		
		wp_enqueue_style( $this->script_slug ,  $this->assets_url . 'css/' . $this->authority . '.css', array() , $APSC->ver );

	}
	
	public function before_front_init()
	{
		
		global $APSC;

		if( !empty( $APSC->Env->is_admin ) ) {

			return false;
			
		}
		
		if( !$APSC->Env->is_ajax ) {
			
			$this->do_before_front_init();
			
		}

	}
	
	public function before_init()
	{
		
		global $APSC;

	}
	
	public function before_not_init()
	{
		
		global $APSC;

	}
	
}

new APSC_Front_Setup();

endif;
