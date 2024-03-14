<?php

if ( !class_exists( 'APSC_Admin_Setup' ) ) :

final class APSC_Admin_Setup
{

	private $authority = 'setup';
	private $screen    = 'admin';

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

		if( empty( $APSC->Env->is_admin ) ) {

			return false;
			
		}
		
		if( $APSC->Env->is_ajax ) {
			
			return false;
			
		}

		if( $APSC->Site->is_multisite ) {
			
			add_filter( 'network_admin_plugin_action_links_' . $APSC->Plugin->path , array( $this , 'plugin_action_links' ) );
			
		} else {
			
			add_filter( 'plugin_action_links_' . $APSC->Plugin->path , array( $this , 'plugin_action_links' ) );

		}

		add_filter( 'plugin_row_meta' , array( $this , 'plugin_row_meta' ) , 10 , 2 );
		
		//add_action( 'admin_enqueue_scripts' , array( $this , 'admin_enqueue_scripts' ) );

	}
	
	public function plugin_action_links( $links )
	{
		
		global $APSC;

		$setting = sprintf( '<a href="%1$s">%2$s</a>' , $APSC->Link->admin , __( 'Settings' ) );

		array_unshift( $links , $setting );

		return $links;
		
	}
	
	public function plugin_row_meta( $links , $file )
	{
		
		global $APSC;

		if ( strpos( $file , $APSC->Plugin->path ) !== false ) {
			
			$links[] = sprintf( '<a href="%1$s" target="_blank">%2$s</a>' , $APSC->Link->forum , __( 'Support Forums' ) );

		}
		
		return $links;

	}

	public function admin_enqueue_scripts()
	{
		
		global $APSC;
		
		wp_enqueue_style( $this->script_slug ,  $this->assets_url . 'css/' . $this->authority. '.css', array() , $APSC->ver );

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

new APSC_Admin_Setup();

endif;
