<?php

if ( !class_exists( 'APSC_Init_Add' ) ) :

final class APSC_Init_Add
{

    public function __construct()
	{

		global $APSC;
		
		$this->include_models();

		add_action( $APSC->main_slug . '_init' , array( $this , 'init' ) , 0 );
		add_action( $APSC->main_slug . '_init' , array( $this , 'after_init' ) , 20 );
		
    }
	
	private function include_models()
	{

		global $APSC;

		$includes = array(
			'model/abstract-record.php',
			'model/abstract-archive.php',
			'model/home.php',
			'model/date.php',
			'model/search.php',
			'model/taxonomies.php',
		);
		
		$APSC->Helper->includes( $includes );

	}
	
	public function init() {}
	
	public function after_init()
	{
		
		$this->setup_manager();
		$this->setup_links();
		
	}
	
	private function setup_manager()
	{
		
		global $APSC;
		
		$APSC->Cap->capability = apply_filters( $APSC->main_slug . '_capability' , $APSC->Cap->capability );
		
		if( current_user_can( $APSC->Cap->capability ) ) {

			$APSC->Cap->is_manager = true;
			
		}
		
	}
	
	private function setup_links()
	{
		
		global $APSC;

		$APSC->Link->admin   = admin_url( 'admin.php?page=' . $APSC->main_slug );

		$APSC->Link->plugin  = 'https://wordpress.org/plugins/' . $APSC->plugin_slug;
		$APSC->Link->forum   = 'http://wordpress.org/support/plugin/' . $APSC->plugin_slug;
		$APSC->Link->review  = 'http://wordpress.org/support/view/plugin-reviews/' . $APSC->plugin_slug;
		$APSC->Link->profile = 'http://profiles.wordpress.org/gqevu6bsiz';

		$APSC->Link->author  = 'http://gqevu6bsiz.chicappa.jp/';
		
	}
	
}

new APSC_Init_Add();

endif;
