<?php

/* --------------------------------------------------------- */
/* !Add the member settings metabox - 1.1.0 */
/* --------------------------------------------------------- */

function mtphr_members_rotator_metabox() {

	$settings = mtphr_members_settings();
	add_meta_box( 'mtphr_member_settings_metabox', sprintf(__('%s Settings', 'mtphr-members'), $settings['singular_label']), 'mtphr_member_settings_render_metabox', 'mtphr_member', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'mtphr_members_rotator_metabox' );



/* --------------------------------------------------------- */
/* !Render the gallery settings metabox - 1.1.1 */
/* --------------------------------------------------------- */

function mtphr_member_settings_render_metabox() {

	global $post;
	$settings = mtphr_members_settings();
	$parent = isset($_GET['post']) ? $_GET['post'] : '';

	// Info
	$title = get_post_meta( $post->ID, '_mtphr_members_title', true );
	$contact_info = get_post_meta( $post->ID, '_mtphr_members_contact_info', true );
	if( empty($contact_info) ) {
		$contact_info = array(
			array(
				'title' => __('Email', 'mtphr-members'),
				'description' => '',
			),
			array(
				'title' => __('Tel', 'mtphr-members'),
				'description' => '',
			),
			array(
				'title' => __('Fax', 'mtphr-members'),
				'description' => '',
			)
		);
	}
	
	$social_new = get_post_meta( $post->ID, '_mtphr_members_social_new_tab', true );
	$social = get_post_meta( $post->ID, '_mtphr_members_social', true );
	$social = mtphr_members_social_update_1_1_0( $social );
	$twitter = get_post_meta( $post->ID, '_mtphr_members_twitter', true );
	
	// Widgets
	$contact_override = get_post_meta( $post->ID, '_mtphr_members_contact_override', true );
	$social_override = get_post_meta( $post->ID, '_mtphr_members_social_override', true );
	$twitter_override = get_post_meta( $post->ID, '_mtphr_members_twitter_override', true );
	
	// Make sure these are arrays
	$contact_override = is_array($contact_override) ? $contact_override : array();
	$social_override = is_array($social_override) ? $social_override : array();
	$twitter_override = is_array($twitter_override) ? $twitter_override : array();
		
	// Add and filter the tabs
	$tabs = array(
		'info' => sprintf(__('%s Info', 'mtphr-members'), $settings['singular_label']),
	);	
	if( is_plugin_active('mtphr-widgets/mtphr-widgets.php') ) {
		$tabs['widgets'] = __('Widget Overrides', 'mtphr-members');
	}
	$tabs = apply_filters( 'mtphr_members_tabs', $tabs );
	
	// Filter the info meta
	$info_meta = apply_filters( 'mtphr_members_info_meta', array(
		'title' => 'title',
		'contact_info' => 'contact_info',
		'filter' => 'filter',
		'social_sites' => 'social_sites',
		'twitter' => 'twitter'
	));	

	echo '<input type="hidden" name="mtphr_members_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

	echo '<div id="mtphr-members-page-tabs">';
  	echo '<ul>';
  		do_action('mtphr_members_metabox_tabs_before');
			if( is_array($tabs) && count($tabs) > 0 ) {
				foreach( $tabs as $type=>$button ) {
					echo '<li class="nav-tab"><a href="#mtphr-members-page-tabs-'.$type.'">'.$button.'</a></li>';
				}
			}
			do_action('mtphr_members_metabox_tabs_after');
		echo '</ul>';

		do_action('mtphr_members_metabox_before');


		/* --------------------------------------------------------- */
		/* !Member Info - 1.1.1 */
		/* --------------------------------------------------------- */
		
		if( isset($tabs['info']) ) {
			
			echo '<div id="mtphr-members-page-tabs-info" class="mtphr-members-page-tabs-page">';
	
				do_action('mtphr_members_info_metabox_before');
				echo '<table class="mtphr-members-table">';
					do_action('mtphr_members_info_metabox_top');

					// Display the info meta
					if( is_array($info_meta) && count($info_meta) > 0 ) {
						foreach( $info_meta as $i=>$meta ) {

							switch( $meta ) {
							
								case 'title':
								
									echo '<tr>';
										echo '<td class="mtphr-members-label">';
											echo '<label>'.sprintf(__('%s title', 'mtphr-members'), $settings['singular_label']).'</label>';
											echo '<small>'.sprintf(__('Add a title for the %s', 'mtphr-members'), strtolower($settings['singular_label'])).'</small>';
										echo '</td>';
										echo '<td>';
											echo '<input type="text" name="_mtphr_members_title" value="'.$title.'" />';
										echo '</td>';
									echo '</tr>';
									
									break;
									
								case 'contact_info':
									
									echo '<tr>';
										echo '<td class="mtphr-members-label">';
											echo '<label>'.__('Contact info', 'mtphr-members').'</label>';
											echo '<small>'.sprintf(__('Add info associated with the %s', 'mtphr-members'), strtolower($settings['singular_label'])).'</small>';
										echo '</td>';
										echo '<td>';
											if( function_exists('metaphor_widgets_contact_setup') ) {
												echo metaphor_widgets_contact_setup( '_mtphr_members_contact_info', $contact_info );
											} else {
												$url = get_bloginfo('wpurl').'/wp-admin/plugin-install.php?tab=plugin-information&plugin=mtphr-widgets&TB_iframe=true&width=640&height=500';
												printf(__('<a class="thickbox" href="%s"><strong>Metaphor Widgets</strong></a> must be installed & activated to setup Contact Info for %s.','mtphr-members'), $url, $settings['plural_label']);
											}
										echo '</td>';
									echo '</tr>';
							
									break;
									
								case 'social_sites':
								
									echo '<tr>';
										echo '<td class="mtphr-members-label">';
											echo '<label>'.__('Social sites', 'mtphr-members').'</label>';
											echo '<small>'.sprintf(__('Add social sites associated with the %s', 'mtphr-members'), strtolower($settings['singular_label'])).'</small>';
										echo '</td>';
										echo '<td>';
											if( function_exists('metaphor_widgets_social_setup') ) {
												echo '<div class="metaphor-widgets-social-icon-container" style="padding:0">';
													echo metaphor_widgets_social_target( '_mtphr_members_social_new_tab', $social_new );
												echo '</div>';
												echo metaphor_widgets_social_setup( '_mtphr_members_social', $social );
											} else {
												$url = get_bloginfo('wpurl').'/wp-admin/plugin-install.php?tab=plugin-information&plugin=mtphr-widgets&TB_iframe=true&width=640&height=500';
												printf(__('<a class="thickbox" href="%s"><strong>Metaphor Widgets</strong></a> must be installed & activated to setup Social Sites for %s.','mtphr-members'), $url, $settings['plural_label']);
											}
										echo '</td>';
									echo '</tr>';
							
									break;
									
								case 'twitter':
								
									echo '<tr>';
										echo '<td class="mtphr-members-label">';
											echo '<label>'.__('Twitter handle', 'mtphr-members').'</label>';
											echo '<small>'.sprintf(__('Add a Twitter handle for the %s', 'mtphr-members'), strtolower($settings['singular_label'])).'</small>';
										echo '</td>';
										echo '<td>';
											if( function_exists('metaphor_widgets_social_setup') ) {
												echo '<input type="text" name="_mtphr_members_twitter" value="'.$twitter.'" />';
											} else {
												$url = get_bloginfo('wpurl').'/wp-admin/plugin-install.php?tab=plugin-information&plugin=mtphr-widgets&TB_iframe=true&width=640&height=500';
												printf(__('<a class="thickbox" href="%s"><strong>Metaphor Widgets</strong></a> must be installed & activated to setup Tweets for %s.','mtphr-members'), $url, $settings['plural_label']);
											}
										echo '</td>';
									echo '</tr>';
							
									break;
									
								case 'filter':
									do_action('mtphr_members_info_metabox_middle');
									break;
									
								default:
									break;
							}
						}
					}
	
					do_action('mtphr_members_info_metabox_bottom');
				echo '</table>';
				do_action('mtphr_members_info_metabox_after');
	
			echo '</div>';
		}


		/* --------------------------------------------------------- */
		/* !Member Widgets - 1.1.1 */
		/* --------------------------------------------------------- */
		
		if( isset($tabs['widgets']) ) {
		
			global $wp_registered_sidebars;

			$sidebars = get_option('sidebars_widgets');
			$contact_widgets = array();
			$social_widgets = array();
			$twitter_widgets = array();
			foreach( $sidebars as $key=>$sidebar ) {
				if( $key != 'wp_inactive_widgets' ) {
					if( is_array($sidebar) ) {
						foreach( $sidebar as $widget ) {
							if( strstr($widget,'mtphr-contact') ) {
								$contact_widgets[$widget] = '<strong>'.$widget.'</strong> <em>('.$wp_registered_sidebars[$key]['name'].')</em>';
							}
							if( strstr($widget,'mtphr-social') ) {
								$social_widgets[$widget] = '<strong>'.$widget.'</strong> <em>('.$wp_registered_sidebars[$key]['name'].')</em>';
							}
							if( strstr($widget,'mtphr-twitter') ) {
								$twitter_widgets[$widget] = '<strong>'.$widget.'</strong> <em>('.$wp_registered_sidebars[$key]['name'].')</em>';
							}
						}	
					}
				}
			}
			
			echo '<div id="mtphr-members-page-tabs-widgets" class="mtphr-members-page-tabs-page">';
	
				do_action('mtphr_members_widgets_metabox_before');
				echo '<table class="mtphr-members-table">';
					do_action('mtphr_members_widgets_metabox_top');
					
					// Display the widget overrides meta
					if( is_array($info_meta) && count($info_meta) > 0 ) {
						foreach( $info_meta as $i=>$meta ) {
						
							switch( $meta ) {
							
								case 'contact_info':
								
									echo '<tr>';
										echo '<td class="mtphr-members-label">';
											echo '<label>'.__('Contact widget override', 'mtphr-members').'</label>';
											echo '<small>'.sprintf(__('Override the following contact widgets for the %s, if they are active', 'mtphr-members'), strtolower($settings['singular_label'])).'</small>';
										echo '</td>';
										echo '<td>';
											if( is_array($contact_widgets) && count($contact_widgets) > 0 ) {
												foreach( $contact_widgets as $key=>$widget ) {
													$checked = array_key_exists( $key, $contact_override ) ? 'checked="checked"' : '';
													echo '<label><input type="checkbox" name="_mtphr_members_contact_override['.$key.']" value="1" '.$checked.' /> '.$widget.'</label><br/>';
												}
											}
										echo '</td>';
									echo '</tr>';
							
									break;
									
								case 'social_sites':
								
									echo '<tr>';
										echo '<td class="mtphr-members-label">';
											echo '<label>'.__('Social widget override', 'mtphr-members').'</label>';
											echo '<small>'.sprintf(__('Override the following social widgets for the %s, if they are active', 'mtphr-members'), strtolower($settings['singular_label'])).'</small>';
										echo '</td>';
										echo '<td>';
											if( is_array($social_widgets) && count($social_widgets) > 0 ) {
												foreach( $social_widgets as $key=>$widget ) {
													$checked = array_key_exists( $key, $social_override ) ? 'checked="checked"' : '';
													echo '<label><input type="checkbox" name="_mtphr_members_social_override['.$key.']" value="1" '.$checked.' /> '.$widget.'</label><br/>';
												}
											}
										echo '</td>';
									echo '</tr>';
							
									break;
									
								case 'twitter':
								
									echo '<tr>';
										echo '<td class="mtphr-members-label">';
											echo '<label>'.__('Twitter widget override', 'mtphr-members').'</label>';
											echo '<small>'.sprintf(__('Override the following twitter widgets for the %s, if they are active', 'mtphr-members'), strtolower($settings['singular_label'])).'</small>';
										echo '</td>';
										echo '<td>';
											if( is_array($twitter_widgets) && count($twitter_widgets) > 0 ) {
												foreach( $twitter_widgets as $key=>$widget ) {
													$checked = array_key_exists( $key, $twitter_override ) ? 'checked="checked"' : '';
													echo '<label><input type="checkbox" name="_mtphr_members_twitter_override['.$key.']" value="1" '.$checked.' /> '.$widget.'</label><br/>';
												}
											}
										echo '</td>';
									echo '</tr>';
							
									break;
									
								case 'filter':
									do_action('mtphr_members_widgets_metabox_middle');
									break;
									
								default:
									break;			
							}
						}
					}
		
					do_action('mtphr_members_widgets_metabox_bottom');
				echo '</table>';
				do_action('mtphr_members_widgets_metabox_after');
	
			echo '</div>';
		}

		do_action('mtphr_members_metabox_after');

	echo '</div>';
}



/* --------------------------------------------------------- */
/* !Save the custom meta - 1.1.0 */
/* --------------------------------------------------------- */

function mtphr_members_metabox_save( $post_id ) {

	global $post;

	// verify nonce
	if (!isset($_POST['mtphr_members_nonce']) || !wp_verify_nonce($_POST['mtphr_members_nonce'], basename(__FILE__))) {
		return $post_id;
	}

	// check autosave
	if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) return $post_id;

	// don't save if only a revision
	if ( isset($post->post_type) && $post->post_type == 'revision' ) return $post_id;

	// check permissions
	if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}

	if( isset($_POST['_mtphr_members_title']) ) {

		$title = isset($_POST['_mtphr_members_title']) ? sanitize_text_field($_POST['_mtphr_members_title']) : '';
		$contact_info = $sanitize_contact_info = isset($_POST['_mtphr_members_contact_info']) ? $_POST['_mtphr_members_contact_info'] : '';
		$sanitize_contact_info = array();
		if( is_array($contact_info) && count($contact_info) > 0 ) {
			foreach( $contact_info as $i=>$info ) {
				$sanitize_contact_info[] = array(
					'title' => wp_kses_post($info['title']),
					'description' => wp_kses_post($info['description'])
				);
			}
		}
		
		$social_new = isset($_POST['_mtphr_members_social_new_tab']) ? $_POST['_mtphr_members_social_new_tab'] : '';
		$social = $sanitized_social = isset($_POST['_mtphr_members_social']) ? $_POST['_mtphr_members_social'] : '';
		$sanitized_social = array();
		if( is_array($social) && count($social) > 0 ) {
			foreach( $social as $i=>$site ) {
				$sanitized_social[$i] = esc_url( $site );
			}
		}
		
		$twitter = isset($_POST['_mtphr_members_twitter']) ? sanitize_text_field($_POST['_mtphr_members_twitter']) : '';
		$contact_override = isset($_POST['_mtphr_members_contact_override']) ? $_POST['_mtphr_members_contact_override'] : array();
		$social_override = isset($_POST['_mtphr_members_social_override']) ? $_POST['_mtphr_members_social_override'] : array();
		$twitter_override = isset($_POST['_mtphr_members_twitter_override']) ? $_POST['_mtphr_members_twitter_override'] : array();
		
		update_post_meta( $post_id, '_mtphr_members_title', $title );
		update_post_meta( $post_id, '_mtphr_members_contact_info', $sanitize_contact_info );
		update_post_meta( $post_id, '_mtphr_members_social_new_tab', $social_new );
		update_post_meta( $post_id, '_mtphr_members_social', $sanitized_social );
		update_post_meta( $post_id, '_mtphr_members_twitter', $twitter );
		update_post_meta( $post_id, '_mtphr_members_contact_override', $contact_override );
		update_post_meta( $post_id, '_mtphr_members_social_override', $social_override );
		update_post_meta( $post_id, '_mtphr_members_twitter_override', $twitter_override );
	}
}
add_action( 'save_post', 'mtphr_members_metabox_save' );



