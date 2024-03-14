<?php

defined('ABSPATH') or die();

class wl_set_home_page {

	public static function wl_companion_install_function() {
		$ThemeFrontPage = get_option('nineteen_theme_front_page');
		if (!$ThemeFrontPage) {
			//post status and options
			$post = array(
				'comment_status' => 'closed',
				'ping_status'    =>  'closed',
				'post_author'    => 1,
				'post_date'      => date('Y-m-d H:i:s'),
				'post_name'      => 'Home',
				'post_status'    => 'publish',
				'post_title'     => 'Home',
				'post_type'      => 'page',
			);
			//insert page and save the id
			$newvalue = wp_insert_post($post, false);
			if ($newvalue && !is_wp_error($newvalue)) {
				update_post_meta($newvalue, '_wp_page_template', 'template-home.php');

				// Use a static front page
				$page = get_page_by_title('Home');
				update_option('show_on_front', 'page');
				update_option('page_on_front', $page->ID);
			}
			//save the id in the database
			update_option('nineteen_theme_front_page', $newvalue);
		}
	}
}
