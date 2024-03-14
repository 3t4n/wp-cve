<?php

if ( !class_exists( 'APSC_Front_Master' ) ) :

final class APSC_Front_Master
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

		$this->setup_includes();

		$this->ready_do_check();
		
		if( $this->ready_do ) {
			
			$this->hook = 'front_init';

			add_action( $APSC->main_slug . '_before_front_init' , array( $this , 'before_init' ) , 0 );
			add_action( $APSC->main_slug . '_front_init' , array( $this , 'front_screen' ) , 20 );
			
		} else {
			
			$this->hook = 'not_front_init';

			add_action( $APSC->main_slug . '_before_not_front_init' , array( $this , 'before_init' ) , 0 );
			add_action( $APSC->main_slug . '_not_front_init' , array( $this , 'front_screen' ) , 20 );

		}
		
		do_action( $APSC->main_slug . '_before_' . $this->hook );

	}
	
	private function setup_includes()
	{
		
		global $APSC;

		$includes = array(
			'front/_setup.php',
		);
		
		$APSC->Helper->includes( $includes );

	}
	
	private function ready_do_check()
	{
		
		global $APSC;

		$includes = array(
			'front/_ready-do.php',
		);
		
		$APSC->Helper->includes( $includes );
		
		$Ready_Do = new APSC_Front_Ready_Do();
		
		if( $Ready_Do->is_ready_do() ) {

			$this->ready_do = true;
			
		}

	}
	
	public function before_init()
	{
		
		global $APSC;

		$this->front_includes();
		
		add_action( 'wp' , array( $this , 'regist_init_action' ) , 20 );
		
	}
	
	private function front_includes()
	{
		
		global $APSC;

		if( $this->hook == 'front_init' ) {
			
			$includes = array(
				'front/not-user-archives.php',
			);
			
		} elseif( $this->hook == 'not_front_init' ) {
			
			$includes = array();

		}

		$APSC->Helper->includes( $includes );
		
	}
	
	public function regist_init_action()
	{
		
		global $APSC;

		if( $APSC->Env->is_admin ) {

			return false;
			
		}
		
		if( $APSC->Env->is_ajax ) {
			
			return false;
			
		}

		do_action( $APSC->main_slug . '_' . $this->hook );
		
	}
	
	public function front_screen()
	{
		
		global $APSC;
		
		if( ! is_singular() ) {
			
			return false;
			
		}

		do_action( $APSC->main_slug . '_front_singular_screen' );
		
	}

}

new APSC_Front_Master();

endif;
