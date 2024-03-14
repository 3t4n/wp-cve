<?php

if ( !class_exists( 'APSC_Init' ) ) :

final class APSC_Init
{

	private $framework_ver = '1.4';

    public function __construct()
	{

		global $APSC;
		
		add_action( $APSC->main_slug . '_plugins_loaded' , array( $this , 'plugins_loaded' ) , 0 );
		add_action( $APSC->main_slug . '_init' , array( $this , 'init' ) , 0 );
		
    }
	
	public function plugins_loaded()
	{
		
		$this->setup_Plugin();
		$this->setup_Cap();
		$this->setup_Form();
		$this->setup_Site();
		$this->setup_Env();
		
	}

	private function setup_Plugin()
	{
		
		global $APSC;

		$APSC->Plugin->dir_name  = basename( $APSC->plugin_dir );
		$APSC->Plugin->path      = trailingslashit( $APSC->Plugin->dir_name ) . $APSC->plugin_slug . '.php';
		
		$APSC->Plugin->msg_notice = sprintf( '%s_msg' , $APSC->main_slug );

	}
	
	private function setup_Cap()
	{
		
		global $APSC;

		$APSC->Cap->capability = 'manage_options';
		$APSC->Cap->is_manager = false;

	}

	private function setup_Form()
	{
		
		global $APSC;

		$APSC->Form->UPFN  = 'Y';
		$APSC->Form->field = $APSC->main_slug . '_settings';
		$APSC->Form->nonce = $APSC->main_slug . '_';

	}

	private function setup_Site()
	{
		
		global $APSC;

		$APSC->Site->is_multisite = is_multisite();
		$APSC->Site->blog_id = get_current_blog_id();
		$APSC->Site->main_blog = is_main_site();

	}

	private function setup_Env()
	{
		
		global $APSC;

		$APSC->Env->is_admin         = is_admin();
		$APSC->Env->is_network_admin = is_network_admin();
		$APSC->Env->is_ajax          = false;
		$APSC->Env->is_cron          = false;
		$APSC->Env->login_action     = false;
		$APSC->Env->schema           = 'http://';

		if( defined( 'DOING_AJAX' ) ) {

			$APSC->Env->is_ajax = true;
			
		}

		if( defined( 'DOING_CRON' ) ) {

			$APSC->Env->is_cron = true;
			
		}
			
		if( !strpos( $_SERVER['REQUEST_URI'], 'wp-login.php' ) === false ) {

			$APSC->Env->login_action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : 'login';
			
		}
		
		if( is_ssl() ) {
			
			$APSC->Env->schema = 'https://';

		}

	}
	
	public function init()
	{
		
		$this->setup_User();
		
	}
	
	private function setup_User()
	{
		
		global $APSC;

		$APSC->User->user_login = is_user_logged_in();
		$APSC->User->user_role  = false;
		$APSC->User->user_id    = false;
		$APSC->User->superadmin = false;

		if( !$APSC->User->user_login ) {

			return false;
			
		}

		$APSC->User->user_id = get_current_user_id();

		$User = wp_get_current_user();
	
		if( !empty( $User->roles ) ) {
	
			$user_roles = $User->roles;

			foreach( $user_roles as $role ) {
	
				$APSC->User->user_role = $role;
				break;
	
			}
	
		}

		if( $APSC->Site->is_multisite ) {

			$APSC->User->superadmin = is_super_admin();
			
		}

	}

}

new APSC_Init();

endif;
