<?php

if ( !class_exists( 'APSC_Admin_Master' ) ) :

final class APSC_Admin_Master
{

	private $ready_do = false;
	private $hook     = false;

	public function __construct()
	{
		
		global $APSC;
		
		add_action( $APSC->main_slug . '_after_init' , array( $this , 'init' ) , 0 );
		
	}
	
	public function init()
	{
		
		global $APSC;
		
		if( ! $APSC->Env->is_admin ) {

			return false;
			
		}

		$this->setup_includes();

		$this->ready_do_check();
		
		if( $this->ready_do ) {
			
			$this->hook = 'admin_init';

			add_action( $APSC->main_slug . '_before_admin_init' , array( $this , 'before_init' ) , 0 );
			add_action( $APSC->main_slug . '_admin_init' , array( $this , 'admin_screen' ) , 20 );
			
		} else {
			
			$this->hook = 'not_admin_init';

			add_action( $APSC->main_slug . '_before_not_admin_init' , array( $this , 'before_init' ) , 0 );
			add_action( $APSC->main_slug . '_not_admin_init' , array( $this , 'admin_screen' ) , 20 );

		}
		
		do_action( $APSC->main_slug . '_before_' . $this->hook );

	}
	
	private function setup_includes()
	{
		
		global $APSC;

		$includes = array(
			'admin/_setup.php',
		);
		
		$APSC->Helper->includes( $includes );

	}
	
	private function ready_do_check()
	{
		
		global $APSC;

		$includes = array(
			'admin/_ready-do.php',
		);
		
		$APSC->Helper->includes( $includes );
		
		$Ready_Do = new APSC_Admin_Ready_Do();
		
		if( $Ready_Do->is_ready_do() ) {

			$this->ready_do = true;
			
		}

	}
	
	public function before_init()
	{
		
		$this->admin_includes();
		
		add_action( 'admin_init' , array( $this , 'regist_init_action' ) , 20 );
		
	}
	
	private function admin_includes()
	{
		
		global $APSC;
		
		if( $this->hook == 'admin_init' ) {
			
			$includes = array(
				'admin/abstract-manager.php',
				'admin/manager-archive-settings.php',
				'admin/manager-archive-tab-home.php',
				'admin/manager-archive-tab-date.php',
				'admin/manager-archive-tab-search.php',
				'admin/manager-archive-tab-taxonomies.php',
			);
			
		} elseif( $this->hook == 'not_admin_init' ) {
			
			$includes = array();

		}

		$APSC->Helper->includes( $includes );
		
	}
	
	public function regist_init_action()
	{
		
		global $APSC;

		if( ! $APSC->Env->is_admin ) {

			return false;
			
		}
		
		if( $APSC->Env->is_ajax ) {
			
			return false;
			
		}
		
		do_action( $APSC->main_slug . '_' . $this->hook );
		
	}
	
	public function admin_screen()
	{
		
		global $plugin_page;
		global $APSC;
		
		if( empty( $plugin_page ) ) {
			
			return false;
			
		}

		if( strpos( $plugin_page , $APSC->main_slug ) === false ) {

			return false;
			
		}
		
		do_action( $APSC->main_slug . '_admin_plugin_screen' );
		
	}

}

new APSC_Admin_Master();

endif;
