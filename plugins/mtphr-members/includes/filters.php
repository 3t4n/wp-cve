<?php


/* --------------------------------------------------------- */
/* !Override a Metaphor Social Links widget - 1.1.4 */
/* --------------------------------------------------------- */

function mtphr_widgets_members_social_sites( $sites, $id ) {
	
	if( is_singular() ) {
	
		$widgets = get_post_meta( get_the_ID(), '_mtphr_members_social_override', true );
	
		if( is_array($widgets) ) {
			if( array_key_exists($id, $widgets) ) {
				$member_sites = get_post_meta( get_the_ID(), '_mtphr_members_social', true );
				return mtphr_members_social_update_1_1_0( $member_sites );
			}
		}
	}
	return $sites;
}
add_filter( 'mtphr_widgets_social_sites', 'mtphr_widgets_members_social_sites', 10, 2 );



/* --------------------------------------------------------- */
/* !Override a Metaphor Social Links widget target - 1.1.4 */
/* --------------------------------------------------------- */

function mtphr_widgets_members_social_new_tab( $new_tab, $id ) {
	
	if( is_singular() ) {
	
		$widgets = get_post_meta( get_the_ID(), '_mtphr_members_social_override', true );
	
		if( is_array($widgets) ) {
			if( array_key_exists($id, $widgets) ) {
				$member_new_tab = get_post_meta( get_the_ID(), '_mtphr_members_social_new_tab', true );
				if( $member_new_tab ) {
					return true;
				}
				return false;
			}
		}
	}
	return $new_tab;
}
add_filter( 'mtphr_widgets_social_new_tab', 'mtphr_widgets_members_social_new_tab', 10, 2 );



/* --------------------------------------------------------- */
/* !Override a Metaphor Contact widget - 1.1.4 */
/* --------------------------------------------------------- */

function mtphr_widgets_members_contact_info( $contact_info, $id ) {
	
	if( is_singular() ) {
	
		$widgets = get_post_meta( get_the_ID(), '_mtphr_members_contact_override', true );
	
		if( is_array($widgets) ) {
			if( array_key_exists($id, $widgets) ) {
				$member_info = get_post_meta( get_the_ID(), '_mtphr_members_contact_info', true );
				return $member_info;
			}
		}
	}
	return $contact_info;
}
add_filter( 'mtphr_widgets_contact_info', 'mtphr_widgets_members_contact_info', 10, 2 );



/* --------------------------------------------------------- */
/* !Override a Metaphor Twitter widget - 1.1.4 */
/* --------------------------------------------------------- */

function mtphr_widgets_members_twitter_name( $twitter_name, $id ) {
	
	if( is_singular() ) {
	
		$widgets = get_post_meta( get_the_ID(), '_mtphr_members_twitter_override', true );
	
		if( is_array($widgets) ) {
			if( array_key_exists($id, $widgets) ) {
				$member_twitter = get_post_meta( get_the_ID(), '_mtphr_members_twitter', true );
				return $member_twitter;
			}
		}
	}
	return $twitter_name;
}
add_filter( 'mtphr_widgets_twitter_name', 'mtphr_widgets_members_twitter_name', 10, 2 );



/* --------------------------------------------------------- */
/* !Remove unused widgets - 1.1.7 */
/* --------------------------------------------------------- */

function mtphr_members_remove_widgets( $params ) {

	if( !is_admin() && get_post_type() == 'mtphr_member' ) {

		// Create an array to store disabled widgets
		$disabled_widget_ids = array();

		// Check for disabled contact widgets
		$widgets = get_post_meta( get_the_ID(), '_mtphr_members_contact_override', true );
		if( is_array($widgets) ) {
			$member_info = get_post_meta( get_the_ID(), '_mtphr_members_contact_info', true );
			if( count($member_info) == 1 && $member_info[0]['title'] == '' && $member_info[0]['description'] == '' ) {
				$disabled_widget_ids = array_merge($disabled_widget_ids, $widgets);
			}
		}

		// Check for disabled social widgets
		$widgets = get_post_meta( get_the_ID(), '_mtphr_members_social_override', true );
		if( is_array($widgets) ) {
			$member_sites = get_post_meta( get_the_ID(), '_mtphr_members_social', true );
			$first_item = reset($member_sites);
			if( count($member_sites) == 1 && $first_item == '' ) {
				$disabled_widget_ids = array_merge($disabled_widget_ids, $widgets);
			}
		}

		// Check for disabled twitter handles
		$widgets = get_post_meta( get_the_ID(), '_mtphr_members_twitter_override', true );
		if( is_array($widgets) ) {
			$member_twitter = get_post_meta( get_the_ID(), '_mtphr_members_twitter', true );
			if( $member_twitter == '' ) {
				$disabled_widget_ids = array_merge($disabled_widget_ids, $widgets);
			}
		}

		// Create an array of the disabled widget keys
		$disabled_widgets = array();
		foreach( $params as $i=>$widget ) {
			if( isset($widget['widget_id']) ) {
				if( array_key_exists($widget['widget_id'], $disabled_widget_ids) ) {
					$disabled_widgets[] = $i;
				}
			}
		}

		// Remove the unused widgets
		$disabled_widgets = array_reverse($disabled_widgets);
		foreach( $disabled_widgets as $i ) {
			unset( $params[$i] );
		}
	}

	return $params;
}
add_filter( 'dynamic_sidebar_params', 'mtphr_members_remove_widgets' );
