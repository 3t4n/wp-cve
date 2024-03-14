<?php
function linkedin_master_load_system_wide() {
global $wpdb, $blog_id;
	if(is_multisite()){
		$linkedin_master_system_wide = get_blog_option($blog_id, 'linkedin_master_system_wide');
		if ($linkedin_master_system_wide == "true"){
			$linkedin_master_system_wide_language = get_blog_option($blog_id, 'linkedin_master_system_wide_language');
			if(empty($linkedin_master_system_wide_language)){
				$linkedin_master_system_wide_language = 'en_US';
			}
			$linkedin_master_system_wide_create = '<script src="https://platform.linkedin.com/in.js" type="text/javascript">lang: '.$linkedin_master_system_wide_language.'</script><script type="text/javascript" src="https://platform.linkedin.com/badges/js/badge.js" async defer></script>';
		}
		else{
			$linkedin_master_system_wide_create = '';
		}
		echo $pinterest_master_system_wide_create;
	}
	else{
		$linkedin_master_system_wide = get_option('linkedin_master_system_wide');
		if ($linkedin_master_system_wide == "true"){
			$linkedin_master_system_wide_language = get_option('linkedin_master_system_wide_language');
			if(empty($linkedin_master_system_wide_language)){
				$linkedin_master_system_wide_language = 'en_US';
			}
			$linkedin_master_system_wide_create = '<script src="https://platform.linkedin.com/in.js" type="text/javascript">lang: '.$linkedin_master_system_wide_language.'</script><script type="text/javascript" src="https://platform.linkedin.com/badges/js/badge.js" async defer></script>';
		}
		else{
			$linkedin_master_system_wide_create = '';
		}
		echo $linkedin_master_system_wide_create;
	}
}
add_action( 'wp_footer', 'linkedin_master_load_system_wide' );
//add_action( 'admin_head', 'pinterest_master_load_system_wide' );
