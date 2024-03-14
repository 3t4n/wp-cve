<?php
defined( 'ABSPATH' ) || exit;

add_action('template_redirect','yahman_addons_template_setup',1);
//template_loaded
function yahman_addons_template_setup(){


	$option =  get_option('yahman_addons') ;

	$profile['user_profile'] = isset($option['profile']['user_profile']) ? true: false;

	if($profile['user_profile']){
		require_once YAHMAN_ADDONS_DIR . 'inc/user_profile_output.php';

		if(!YAHMAN_ADDONS_TEMPLATE){
			add_action('wp_footer','yahman_addons_enqueue_style_notice');
		}
	}


	define( 'YA_USER_PROFILE', $profile['user_profile'] );


	$ga_gtag = '';

	if(isset($option['ga']['enable'])){
		if(isset($option['ga']['id']))  $ga_gtag = $option['ga']['id'];

		if (!defined('YA_GA_GTAG')) {
			define( 'YA_GA_GTAG', $ga_gtag );
		}


		if ( !is_user_logged_in() )
			require_once YAHMAN_ADDONS_DIR . 'inc/ga_gtag.php';

	}else{
    //else of $option['ga']['enable']

		if (!defined('YA_GA_GTAG')) {
			define( 'YA_GA_GTAG', $ga_gtag );
		}

    }  //end of $option['ga']['enable']




    if( isset($option['javascript']['lazy']) && $option['javascript']['lazy'] === 'lozad'){

    	
    	add_filter( 'wp_lazy_loading_enabled', '__return_false' );

    	add_action( 'wp_footer', 'yahman_addons_lazy_lozad');

    }

    if( isset($option['other']['user_timing_api']) ){

    	add_action( 'wp_print_footer_scripts', 'yahman_addons_user_timing_api');

    }

    /*
        if( isset($option['faster']['async_scripts']) ){

          		add_action( 'script_loader_tag', 'yahman_addons_replace_scripts_type',9999);

        }
    */






    }
