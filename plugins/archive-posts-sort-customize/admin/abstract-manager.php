<?php

if ( !class_exists( 'APSC_Admin_Abstract_Manager' ) ) :

abstract class APSC_Admin_Abstract_Manager
{

	protected $authority = 'manager';
	protected $screen    = 'admin';
	
	protected $id;
	protected $do_screen_slug;
	protected $menu_title;
	protected $page_title;
	protected $menu_hook;

	protected $MainModel;

	protected $name;
	protected $nonce;
	protected $action;

	protected $view_dir;
	protected $elements_dir;
	protected $assets_url;
	protected $script_slug;
	protected $errors;

	public function __construct()
	{
		
		global $APSC;
		
		$this->name   = $this->screen . '_' . $this->authority . '_' . $this->id;
		$this->nonce  = $APSC->Form->nonce . $this->name;
		$this->action = $APSC->main_slug . '_' . $this->name;
		
		$admin_dir = $APSC->plugin_dir . trailingslashit( $this->screen );
		$admin_url = $APSC->plugin_url . trailingslashit( $this->screen );

		$this->view_dir     = $admin_dir . trailingslashit( 'view' );
		$this->elements_dir = $this->view_dir . trailingslashit( 'elements' );
		$this->assets_url   = $admin_url . trailingslashit( 'assets' );
		$this->script_slug  = $APSC->main_slug . '_' . $this->authority;
		$this->errors       = new WP_Error();
		
		$this->do_action();
			
	}
	
	private function do_action()
	{
		
		global $APSC;

		if( !empty( $APSC->Cap->is_manager ) ) {
			
			if( empty( $APSC->Env->is_ajax ) ) {
			
				add_action( $APSC->main_slug . '_before_admin_init' , array( $this , 'before_init' ) );
				add_action( $APSC->main_slug . '_admin_init' , array( $this , 'init' ) );
				add_action( $APSC->main_slug . '_admin_plugin_screen' , array( $this , 'admin_plugin_screen' ) );
				
			} else {

				add_action( $APSC->main_slug . '_ajax' , array( $this , 'do_ajax' ) );
				
			}
			
		}

	}
	
	public function before_init()
	{
		
		global $APSC;
		
		add_action( 'admin_menu' , array( $this , 'admin_menu' ) );
		
	}

	public function admin_menu() {}
	
	public function init()
	{
		
		$this->check_post_data();

		add_action( 'admin_notices' , array( $this , 'admin_notices' ) );

	}

	private function check_post_data()
	{
		
		global $APSC;

		if( empty( $_POST ) ) {

			return false;
			
		}
		
		if( !$APSC->Helper->is_correctly_form( $_POST ) ) {
			
			return false;
			
		}

		if( !$APSC->Cap->is_manager ) {
			
			return false;
			
		}
		
		$this->post_data();

	}
	
	protected function post_data() {}

	public function admin_notices()
	{
		
		global $APSC;
		
		$APSC->Helper->print_notices();

	}
	
	public function admin_plugin_screen()
	{
		
		global $plugin_page;
		
		if( empty( $this->menu_hook ) ) {
			
			return false;
			
		}

		add_action( 'load-' . $this->menu_hook , array( $this , 'current_plugin_screen' ) );

	}
	
	public function current_plugin_screen()
	{

		add_action( 'admin_enqueue_scripts' , array( $this , 'admin_enqueue_scripts' ) );
		add_action( 'admin_print_styles-' . $this->menu_hook , array( $this , 'admin_print_styles' ) );
		add_action( 'admin_print_scripts-' . $this->menu_hook , array( $this , 'admin_print_scripts' ) );
		add_action( $this->menu_hook , array( $this , 'after_current_plugin_view' ) );

	}
	
	public function admin_enqueue_scripts()
	{
		
		global $APSC;
		
		$include_files = array( 'jquery' );
		
		//wp_enqueue_script( $this->script_slug ,  $this->assets_url . 'js/' . $this->authority . '.js', $include_files , $APSC->ver );
		wp_enqueue_style( $this->script_slug ,  $this->assets_url . 'css/' . $this->authority . '.css', array() , $APSC->ver );

	}
	
	public function admin_print_styles() {}
	
	public function admin_print_scripts() {}
	
	public function after_current_plugin_view() {}
	
	public function do_ajax() {}
	
}

endif;
