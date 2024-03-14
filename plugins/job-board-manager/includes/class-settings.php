<?php



if ( ! defined('ABSPATH')) exit;  // if direct access 


class class_job_bm_settings{
	

    public function __construct(){

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 12 );
    }
	

	
	
	public function admin_menu() {

        $job_bm_welcome           = get_option('job_bm_welcome');


        add_submenu_page( 'edit.php?post_type=job', __( 'Settings', 'job-board-manager' ), __( 'Settings', 'job-board-manager' ), 'manage_options', 'job_bm_settings', array( $this, 'settings_page' ) );

        if(empty($job_bm_welcome)):
            add_submenu_page( 'edit.php?post_type=job', __( 'Welcome', 'job-board-manager' ), __( 'Welcome', 'job-board-manager' ), 'manage_options', 'job_bm_welcome', array( $this, 'job_bm_welcome' ) );
        endif;


        add_submenu_page( 'edit.php?post_type=job', __( 'Stats', 'job-board-manager' ), __( 'Stats', 'job-board-manager' ), 'manage_options', 'job_bm_stats', array( $this, 'stats' ) );

        add_submenu_page( 'edit.php?post_type=job', __( 'Add-ons', 'job-board-manager' ), __( 'Add-ons', 'job-board-manager' ), 'manage_options', 'job_bm_addons', array( $this, 'job_bm_addons' ) );



		do_action( 'job_bm_action_admin_menus' );
		
	}


	public function settings_page(){
		
		include( 'menu/settings-new.php' );
		}


	public function job_bm_welcome(){
		
		include( 'menu/welcome.php' );
		}

    public function stats(){

        include( 'menu/stats.php' );
    }

    public function job_bm_addons(){

        include( 'menu/addons.php' );
    }




}


new class_job_bm_settings();

