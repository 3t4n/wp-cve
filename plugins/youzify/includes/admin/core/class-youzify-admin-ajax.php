<?php

class Youzify_Admin_Ajax {

	function __construct() {

		// Save Settings
		add_action( 'wp_ajax_youzify_admin_data_save',  array( $this, 'save_settings' ) );

		// Reset Settings
		add_action( 'wp_ajax_youzify_reset_settings',  array( $this, 'reset_settings' ) );

	}

	/**
	 * Save Settings With Ajax.
	 */
	function save_settings() {

		check_ajax_referer( 'youzify-settings-data', 'security' );

		do_action( 'youzify_before_panel_save_settings' );

		unset( $_POST['security'], $_POST['action'] );

		// Include Styles.
        require_once YOUZIFY_CORE . 'class-youzify-styling.php';

	    // Youzify Panel Options
	    $options = isset( $_POST['youzify_options'] ) && is_array( $_POST['youzify_options'] ) ? $this->sanitize_youzify_options( $_POST['youzify_options'] ) : null;

	    // Save Options
	    if ( $options ) {

	    	// Get Default Options.
	    	$default_options = youzify_default_options();

	    	// Get Active Styles.
	    	$active_styles = youzify_option( 'youzify_active_styles', array() );

	    	if ( empty( $active_styles ) ) {
	    		$active_styles = array();
	    	}

	    	// Get All Youzify Styles
	    	$all_styles = youzify_styling()->get_all_styles( 'ids' );

		    foreach ( $options as $option => $value ) {

		    	// Get Option Value
		        if ( ! is_array( $value ) ) {
		        		$the_value = stripslashes( $value );
		        } else {

		        	$the_value = $value;

		        	if ( isset( $value['color'] ) && empty( $value['color'] ) ) {
		        		$the_value = '';
		        	}

		        }

		        // Save Option or Delete Option if Empty
		        if ( ! empty( $the_value ) ) {

		        	if ( isset( $default_options[ $option ] ) && $the_value == $default_options[ $option ] ) {
		        		youzify_delete_option( $option );
		        	} else {
		        		youzify_update_option( $option, $the_value, false );
		        	}

		        } else {
		        	youzify_delete_option( $option );
		        }

		        // Update Active Style.
		        if ( in_array( $option, $all_styles ) ) {

		        	// Get Option Key.
		        	$option_key = array_search( $option, $active_styles );

		        	if ( $option_key !== false ) {

			        	if ( ! empty( $the_value ) && isset( $active_styles[ $option_key ] ) ) {
			        		continue;
			        	}

			        	if ( empty( $the_value ) ) {
			        		if ( isset( $active_styles[ $option_key ] ) ) {
			        			unset( $active_styles[ $option_key ] );
			        		}
			        		continue;
			        	}
			        }

		        	$active_styles[] = $option;
		        }

		    }

		    if ( ! empty( $active_styles ) ) {

		    	// Get Unique Values.
		    	$active_styles = array_filter( array_unique( $active_styles ) );

		    	// Save New Styles.
        		youzify_update_option( 'youzify_active_styles', $active_styles, false );

		    } else {
				youzify_delete_option( BP_ROOT_BLOG, 'youzify_active_styles' );
		    }

	    }

		// Save "Disable Delete Accounts"
        if ( isset( $options['bp-disable-account-deletion'] ) ) {
	    	if ( 'on' == $options['bp-disable-account-deletion'] ) {
	    		youzify_update_option( 'bp-disable-account-deletion', 0 );
	    	} else {
	    		youzify_update_option( 'bp-disable-account-deletion', 1 );
	    	}
	    }

		// Save Registration Value
        if ( isset( $options['users_can_register'] ) ) {
	    	if ( 'on' == $options['users_can_register'] ) {
	    		youzify_update_option( 'users_can_register', 1 );
	    	} else {
	    		youzify_update_option( 'users_can_register', 0 );
	    	}
	    }

	    if ( isset( $_POST['youzify_pages'] ) ) {

	    	// Sanitize Fields.
	    	$youzify_pages = array_map( 'sanitize_text_field', $_POST['youzify_pages'] );

	    	// Save Youzify Pages.
		    $this->save_youzify_pages( $youzify_pages );

	    }

	    if ( isset( $_POST['youzify_membership_pages'] ) ) {

	    	// Sanitize Fields.
	    	$membership_pages = array_map( 'sanitize_text_field', $_POST['youzify_membership_pages'] );

	    	// Save Membership Pages.
		    $this->save_membership_pages( $membership_pages );

	    }

		if ( isset( $_POST['youzify_ads_form'] ) ) {

			// Get Ads Data
			$ads_data = isset( $_POST['youzify_ads'] ) ? $_POST['youzify_ads'] : array();

			// Sanitize Fields.
			$ads = youzify_sanitize_fields( $ads_data, array( 'title' => 'text', 'is_sponsored' => 'text', 'type' => 'text', 'url' => 'url', 'banner' => 'url', 'code' => 'html' ) );

	    	// Save Ads.
			$this->save_ads( $ads );

		}

	    if ( isset( $_POST['youzify_networks_form'] ) ) {

			// Sanitize Fields.
			$networks = youzify_sanitize_fields( $_POST['youzify_networks'], array( 'name' => 'text', 'icon' => 'text', 'color' => 'color' ) );

	   		// Save Social Networks.
	    	$this->save_social_networks( $networks );

	    }

	    if ( isset( $_POST['youzify_custom_widgets_form'] ) ) {

			// Sanitize Fields.
			$custom_widgets = youzify_sanitize_fields( $_POST['youzify_custom_widgets'], array( 'name' => 'text', 'icon' => 'text', 'content' => 'html', 'display_title' => 'text', 'display_padding' => 'true' ) );

		    // Save Custom Widgets.
	    	$this->save_custom_widgets( $custom_widgets );

	    }

	    if ( isset( $_POST['youzify_custom_tabs_form'] ) ) {

	    	// Sanitize Fields.
			$custom_tabs = youzify_sanitize_fields( $_POST['youzify_custom_tabs'], array( 'title' => 'text', 'slug' => 'text', 'link' => 'text', 'type' => 'text', 'content' => 'html', 'display_sidebar' => 'text', 'display_nonloggedin' => 'true' ) );

	    	// Save Custom Tabs.
	    	$this->save_custom_tabs( $custom_tabs );

	    }

	    if ( isset( $_POST['youzify_user_tags_form'] ) ) {

	    	// Sanitize Fields.
			$user_tags = youzify_sanitize_fields( $_POST['youzify_user_tags'], array( 'name' => 'text', 'icon' => 'text', 'field' => 'text', 'description' => 'textarea' ) );

		    // Save User Tags.
	    	$this->save_user_tags( $user_tags );

	    }

	    // Save Profile Structure.
	    if ( isset( $_POST['youzify_profile_stucture'] ) ) {

		    $hidden = array();

	    	// Get Data
	    	$main_widgets = isset( $_POST['youzify_profile_main_widgets'] ) ? array_map( 'sanitize_text_field', $_POST['youzify_profile_main_widgets'] ) : array();
	    	$sidebar_widgets = isset( $_POST['youzify_profile_sidebar_widgets'] ) ? array_map( 'sanitize_text_field', $_POST['youzify_profile_sidebar_widgets'] ) : array();
	    	$left_sidebar_widgets = isset( $_POST['youzify_profile_left_sidebar_widgets'] ) ? array_map( 'sanitize_text_field', $_POST['youzify_profile_left_sidebar_widgets'] ) : array();

	    	// Update Options
	    	youzify_update_option( 'youzify_profile_main_widgets', $main_widgets );
	    	youzify_update_option( 'youzify_profile_sidebar_widgets', $sidebar_widgets );
	    	youzify_update_option( 'youzify_profile_left_sidebar_widgets', $left_sidebar_widgets );

		    $all_widgets = array_merge( $main_widgets, $sidebar_widgets, $left_sidebar_widgets );

		    foreach ( $all_widgets as $widget_name => $visibility ) {
	            if ( $visibility == 'invisible' ) {
	                $hidden[] = $widget_name;
	            }
		    }

		    if ( ! empty( $hidden ) ) {
		        youzify_update_option( 'youzify_profile_hidden_widgets', $hidden );
		    } else {
		        youzify_delete_option( 'youzify_profile_hidden_widgets' );
		    }

	    	// Hook.
	    	do_action( 'youzify_after_saving_profile_structure' );
	    }

	    if ( isset( $_POST['youzify_unallowed_activities'] ) ) {

	    	$unallowed_activities = array();
	    	$activities = array_map( 'sanitize_text_field', $_POST['youzify_unallowed_activities'] );
	    	foreach ( $activities as $activity_type => $activity_visibilty ) {

	    		if ( $activity_visibilty != 'on' ) {

	    			$unallowed_activities[] = $activity_type;

	    			if ( $activity_type == 'activity_status' ) {
	    				$unallowed_activities[] = 'activity_update';
	    			}

	    		}

	    	}

			if ( empty( $unallowed_activities ) ) {
				youzify_delete_option( 'youzify_unallowed_activities' );
			} else {
				youzify_update_option( 'youzify_unallowed_activities', $unallowed_activities );
			}

	    }

	    if ( isset( $_POST['youzify_profile_tabs'] ) ) {

	    	$profile_tabs = youzify_sanitize_array( $_POST['youzify_profile_tabs']);

	    	do_action( 'bp_setup_nav' );
	    	
			$tabs = array();
			$old_tabs = youzify_get_profile_primary_nav();
			$default_tabs = youzify_profile_tabs_default_value();

			foreach ( $old_tabs as $old_tab ) {

				if ( isset( $profile_tabs[ $old_tab['slug'] ] ) ) {

					$new_tab = $profile_tabs[ $old_tab['slug'] ];

					if ( ! empty( $new_tab['position'] ) && $new_tab['position'] != $old_tab['position'] && is_numeric( $new_tab['position'] ) ) {
						$tabs[ $old_tab['slug'] ]['position'] = $new_tab['position'];
					}

					$old_title = _bp_strip_spans_from_title( $old_tab['name'] );

					if ( ! empty( $new_tab['name']) && $new_tab['name'] != $old_title ) {
						$count = strstr( $old_title, '<span' );
						$tabs[ $old_tab['slug'] ]['name'] = ! empty( $count ) ? $new_tab['name'] . $count : $new_tab['name'];
					}

					if ( $new_tab['visibility'] != 'on' ) {
						$tabs[ $old_tab['slug'] ]['visibility'] = 'off';
					}

					if ( $new_tab['icon'] != 'fas fa-globe-asia' ) {
						if ( isset( $default_tabs[ $old_tab['slug'] ]['icon'] ) ) {
							if ( $new_tab['icon'] != $default_tabs[ $old_tab['slug'] ]['icon'] ) {
								$tabs[ $old_tab['slug'] ]['icon'] =  $new_tab['icon'];
							}
						} else {
							$tabs[ $old_tab['slug'] ]['icon'] = $new_tab['icon'];
						}
					}

					if ( isset( $new_tab['deleted']  ) && $new_tab['deleted'] == 'on' ) {
						$tabs[ $old_tab['slug'] ]['deleted'] = 'on';
					}

				}
			}

			if ( empty( $tabs ) ) {
				youzify_delete_option( 'youzify_profile_tabs' );
			} else {
				youzify_update_option( 'youzify_profile_tabs', $tabs );
			}

	    }

	    // Save Poll Options
	    $this->save_polls_settings( $options );

	    // Delect Empty Field
	    $multiselect_inputs = isset( $_POST['youzify_multiselect_inputs'] ) ? $_POST['youzify_multiselect_inputs'] : '';

	    if ( ! empty( $multiselect_inputs ) ) {

	    	foreach ( $multiselect_inputs as $multiselect_input ) {
	    		if ( ! isset( $options[ $multiselect_input ] ) || empty( $options[ $multiselect_input ] ) )  {
	    			youzify_delete_option( $multiselect_input );
	    		}
	    	}
	    }

	    // Actions
	    do_action( 'youzify_panel_save_settings' );

		wp_send_json_success( array( 'result' => 1, 'message' => __( 'Success!', 'youzify' ) ) );
		exit();

	}

	/**
	 * Save Poll Settings.
	 **/
	function save_polls_settings( $options ) {

	    if ( empty( $options ) ) {
	    	return;
	    }

	    $new_form_options = array();

	    $new_poll_result_options = array();

	    $new_poll_post_options = array();

	    $form_options = array(
	    	'yzap_poll_options_limit' 	  	 => 'options_limit',
	    	'yzap_poll_multi_options' 	  	 => 'multi_options',
	    	'yzap_poll_options_selection' 	 => 'options_selection',
	    	'yzap_poll_options_image' 	  	 => 'options_image',
	    	'yzap_poll_options_image_enable' => 'options_image_enable',
	    );

	    $poll_result_options = array(
	    	'yzap_poll_list_voters' 		=> 'list_voters',
	    	'yzap_poll_limit_voters' 		=> 'limit_voters',
	    	'yzap_poll_options_image_enable' => 'options_image_enable',
	    );

	    $poll_post_options = array(
	    	'yzap_poll_options_result' 		=> 'options_result',
	    	'yzap_poll_options_redirection' => 'options_redirection',
	    	'yzap_poll_revote' 				=> 'poll_revote'
	    );

	    foreach( $options as $option_id => $value ) {

	    	if ( empty( $value ) ) {
	    		continue;
	    	}

	    	if ( isset( $form_options[ $option_id] ) ) {
	    		$new_form_options[ $form_options[ $option_id ] ] = $value;
	    	}

	    	if ( isset( $poll_result_options[ $option_id ] ) ) {
	    		$new_poll_result_options[ $poll_result_options[ $option_id ] ] = $value;
	    	}

	    	if ( isset( $poll_post_options[ $option_id ] ) ) {
	    		$new_poll_post_options[ $poll_post_options[ $option_id ] ] = $value;
	    	}

	    }

	    if ( ! empty( $new_form_options ) ) {
	    	update_option( 'youzify_poll_form_options',  $new_form_options );
	    } else {
	    	delete_option( 'youzify_poll_form_options' );
	    }

	    if ( ! empty( $new_poll_result_options ) ) {
	    	update_option( 'youzify_poll_result_options',  $new_poll_result_options );
	    } else {
	    	delete_option( 'youzify_poll_result_options' );
	    }

	    if ( ! empty( $new_poll_post_options ) ) {
	    	update_option( 'youzify_poll_post_options',  $new_poll_post_options );
	    } else {
	    	delete_option( 'youzify_poll_post_options' );
	    }

	}

	/**
	 * Save Pages.
	 */
	function save_membership_pages( $membership_pages ) {

		// Get How much time page is repeated.
		$page_counts = array_count_values( $membership_pages );

		// if page is already used show error messsage.
		foreach ( $page_counts as $id => $nbr ) {
			if ( $nbr > 1 ) {
				die( __( 'You are using the same page more than once.', 'youzify' ) );
			}
		}

		// Update Pages in Database.
		$update_pages = youzify_update_option( 'youzify_membership_pages', $membership_pages, false );

		if ( $update_pages ) {
			foreach ( $membership_pages as $page => $id ) {
				// Update Option ID
				youzify_update_option( $page, $id );
			}
		}
	}

	/**
	 * Save Social Networks.
	 */
	function save_social_networks( $networks ) {

		if ( empty( $networks ) ) {
			youzify_delete_option( 'youzify_social_networks' );
			return false;
		}

		// Update Next Network ID
    	if ( youzify_update_option( 'youzify_social_networks', $networks, false ) ) {
			youzify_update_option( 'youzify_next_snetwork_nbr', $this->get_next_ID( $networks, 'snetwork' ), false );
    	}

	}

	/**
	 * Save Custom Tabs.
	 */
	function save_custom_tabs( $tabs ) {

		if ( empty( $tabs ) ) {
			youzify_delete_option( 'youzify_custom_tabs' );
			return false;
		}

		// Update Next ID
    	if ( youzify_update_option( 'youzify_custom_tabs', $tabs, false ) ) {
			youzify_update_option( 'youzify_next_custom_tab_nbr', $this->get_next_ID( $tabs, 'custom_tab' ), false );
    	}

	}

	/**
	 * Save User Tags.
	 */
	function save_user_tags( $tags ) {

		if ( empty( $tags ) ) {
			youzify_delete_option( 'youzify_user_tags' );
			return false;
		}

		// Update Next ID
    	if ( youzify_update_option( 'youzify_user_tags', $tags, false ) ) {
			youzify_update_option( 'youzify_next_user_tag_nbr', $this->get_next_ID( $tags, 'user_tag' ) );
    	}

	}

	/**
	 * Save Ads.
	 */
	function save_ads( $ads = array() ) {

		$youzify_ads = array();

		if ( ! empty( $ads ) ) {
			foreach ( $ads as $ad => $data ) {
				$youzify_ads[ $ad ] = $data;
			}
		}

    	// If ADS not updated stop function right here.
		if ( ! youzify_update_option( 'youzify_ads', $youzify_ads, false ) ) {
			return false;
		} else {

			// Update Next Ad ID
			if ( ! empty( $youzify_ads ) ) {
				youzify_update_option( 'youzify_next_ad_nbr', $this->get_next_ID( $youzify_ads, 'ad' ), false );
			}

    	}

	    // Get Overview and Sidebar Widgets
	    $overview_wgs = (array) youzify_options( 'youzify_profile_main_widgets' );
	    $sidebar_wgs = (array) youzify_options( 'youzify_profile_sidebar_widgets' );
	    $left_sidebar_wgs = (array) youzify_options( 'youzify_profile_left_sidebar_widgets' );

	    // Merge Overview & Sidebar widgets
	    $all_widgets = array_merge( $overview_wgs, $sidebar_wgs, $left_sidebar_wgs );

	    // Get Ads Widgets
	    $ads_widgets = $this->get_ads_widgets( $all_widgets );

	    if ( ! empty( $ads_widgets ) ) {

		    // Delete Removed ADS.
		    foreach ( $ads_widgets as $widget_name => $visibility ) {

		        // if widget name is not found.
		        if ( ! isset( $youzify_ads[ $widget_name ] ) ) {

		            // if the removed widget in the sidebar remove it.
		            if ( isset( $sidebar_wgs[ $widget_name ] ) ) {
		                unset( $sidebar_wgs[ $widget_name ]  );
		            }

		            // if the removed widget in the sidebar remove it.
		            if ( isset( $left_sidebar_wgs[ $widget_name ] ) ) {
		                unset( $left_sidebar_wgs[ $widget_name ]  );
		            }

	                // if the removed widget in the overview remove it.
		            if ( isset( $overview_wgs[ $widget_name ] ) ) {
		                unset( $overview_wgs[ $widget_name ]  );
		            }

		        }

		    }

	    }

	    foreach ( $youzify_ads as $ad_id => $data ) {
	        if ( ! isset( $all_widgets[ $ad_id ] ) ) {
	        	$sidebar_wgs[ $ad_id ] = 'visible';
	        }
	    }

		// Update Overview & Sidebar Widgets.
		youzify_update_option( 'youzify_profile_main_widgets', $overview_wgs );
		youzify_update_option( 'youzify_profile_sidebar_widgets', $sidebar_wgs );
		youzify_update_option( 'youzify_profile_left_sidebar_widgets', $left_sidebar_wgs );

	}

	/**
	 * Save Custom Widgets.
	 */
	function save_custom_widgets( $widgets ) {

		$youzify_cw = array();

		if ( ! empty( $widgets ) ) {
			foreach ( $widgets as $widget => $data ) {
				$youzify_cw[ $widget ] = $data;
			}
		}

    	// If widgets not updated stop function right here.
		if ( ! youzify_update_option( 'youzify_custom_widgets', $youzify_cw, false ) ) {
			return false;
		} else {
			// Update Next ID
			youzify_update_option( 'youzify_next_custom_widget_nbr', $this->get_next_ID( $youzify_cw, 'custom_widget' ) );
    	}

	    // Get Overview and Sidebar Widgets
	    $overview_wgs = (array) youzify_options( 'youzify_profile_main_widgets' );
	    $sidebar_wgs  = (array)youzify_options( 'youzify_profile_sidebar_widgets' );
	    $left_sidebar_wgs = (array) youzify_options( 'youzify_profile_left_sidebar_widgets' );

	    // Merge Overview & Sidebar widgets
	    $all_widgets = array_merge( $overview_wgs, $sidebar_wgs, $left_sidebar_wgs );

	    // Get Custom Widgets.
	    $custom_widgets = $this->get_custom_widgets( $all_widgets );

	    if ( ! empty( $custom_widgets ) ) {

		    // Delete Removed widgets.
		    foreach ( $custom_widgets as $widget_name => $visibility ) {

		        // if widget name is not found.
		        if ( ! isset( $youzify_cw[ $widget_name ] ) ) {

		            // if the removed widget in the sidebar remove it.
		            if ( isset( $sidebar_wgs[ $widget_name ] ) ) {
		                unset( $sidebar_wgs[ $widget_name ]  );
		            }

		            // if the removed widget in the sidebar remove it.
		            if ( isset( $left_sidebar_wgs[ $widget_name ] ) ) {
		                unset( $left_sidebar_wgs[ $widget_name ]  );
		            }

	                // if the removed widget in the overview remove it.
		            if ( isset( $overview_wgs[ $widget_name ] ) ) {
		                unset( $overview_wgs[ $widget_name ]  );
		            }

		        }

		    }

	    }

	    foreach ( $youzify_cw as $widget_key => $data ) {
	        if ( ! isset( $all_widgets[ $widget_key ] ) ) {
	        	$sidebar_wgs[ $widget_key ] = 'visible';
	        }
	    }

		// Update Overview & Sidebar Widgets.
		youzify_update_option( 'youzify_profile_main_widgets', $overview_wgs );
		youzify_update_option( 'youzify_profile_sidebar_widgets', $sidebar_wgs );
		youzify_update_option( 'youzify_profile_left_sidebar_widgets', $left_sidebar_wgs );
	}

	/**
	 * Save Youzify Pages.
	 */
	function save_youzify_pages( $youzify_pages ) {

		// Get How much time page is repeated.
		$page_counts = array_count_values( $youzify_pages );

		// if page is already used show error messsage.
		foreach ( $page_counts as $id => $nbr ) {
			if ( $nbr > 1 ) {
				die( __( 'You are using the same page more than once.', 'youzify' ) );
			}
		}

		// Update Youzify Pages in Database.
		$update_pages = update_option( 'youzify_pages', $youzify_pages, false );

		if ( $update_pages ) {
			foreach ( $youzify_pages as $page => $id ) {
				// Update Option ID
				update_option( $page, $id );
			}
		}
	}

	/**
	 * Reset Settings
	 */
	function reset_settings() {

		do_action( 'youzify_before_reset_tab_settings' );

		// Get Reset Type.
		$reset_type = sanitize_text_field( $_POST['reset_type'] );

	    if ( 'tab' == $reset_type ) {

			check_ajax_referer( 'youzify-settings-data', 'security' );

			$options = youzify_sanitize_array( $_POST['youzify_options'] );

	    	$result = $this->reset_tab_settings( $options );

	    } elseif ( 'all' == $reset_type ) {
	    	$result = $this->reset_all_settings();
	    }

	}

	/**
	 * Reset All Settings.
	 */
	function reset_all_settings() {

		do_action( 'youzify_before_reset_all_settings' );

		// Delete Active Styles.
	    youzify_delete_option( 'youzify_active_styles' );

		// Reset Membership Settings.
		if ( youzify_is_membership_system_active() ) {
			$this->membership_reset_settings();
		}

		// Get Default Options.
		$default_options = youzify_default_options();

		// Reset Options
		foreach ( $default_options as $option => $value ) {
			if ( youzify_option( $option ) ) {
				youzify_update_option( $option, $value, false );
			}
		}

		// Reset Styling Input's
        foreach ( youzify_styling()->get_all_styles() as $key ) {
			if ( youzify_option( $key['id'] ) ) {
				youzify_delete_option( $key['id'] );
			}
        }

        // Reset Gradient Elements
        foreach ( youzify_styling()->get_gradient_elements() as $key ) {

			if ( youzify_option( $key['left_color'] ) ) {
				youzify_delete_option( $key['left_color'] );
			}

			if ( youzify_option( $key['right_color'] ) ) {
				youzify_delete_option( $key['right_color'] );
			}

        }

		// Specific Options
		$specific_options = array(
			'youzify_profile_404_photo',
			'youzify_profile_404_cover',
			'youzify_default_groups_cover',
			'youzify_default_groups_avatar',
			'youzify_default_profiles_cover',
			'youzify_default_profiles_avatar',
			'youzify_profile_custom_scheme_color'
		);

		// Reset Specific Options
		foreach ( $specific_options as $option ) {
			if ( youzify_option( $option ) ) {
				youzify_delete_option( $option );
			}
		}

		wp_send_json_success( array( 'result' => 1, 'message' => __( 'Success!', 'youzify' ) ) );
		exit();

	}

	/**
	 * Reset Current Tab Settings.
	 */
	function reset_tab_settings( $tab_options ) {

		if ( empty( $tab_options ) ) {
			return false;
		}

    	// Get Active Styles.
    	$active_styles = youzify_option( 'youzify_active_styles' );

		// Reset Tab Options
		foreach ( $tab_options as $option => $value ) {

			// Rest Options.
			if ( youzify_option( $option ) ) {
				youzify_delete_option( $option );
			}

			// Delete Reseted Active Styles.
			if ( ! empty( $active_styles ) && isset( $value['color'] ) ) {

				// Get Option Key.
				$style_key = array_search( $option, $active_styles );

				// Remove Style from the list.
				if ( $style_key !== false ) {
					unset( $active_styles[ $style_key ] );
				}

			}

		}

		// Save Active Styles
		if ( ! empty( $active_styles ) ) {
			youzify_update_option( 'youzify_active_styles', $active_styles, false );
		} else {
			youzify_delete_option( 'youzify_active_styles' );
		}

		wp_send_json_success( array( 'result' => 1, 'message' => __( 'Success!', 'youzify' ) ) );
		exit();

	}


	/**
	 * Get Fields Next ID.
	 */
	function get_next_ID( $items, $item ) {

		// Set Up Variables.
		$keys = array_keys( $items );

		// Get Keys Numbers.
		foreach ( $keys as $key ) {
			$key_number = preg_match_all( '/\d+/', $key, $matches );
			$new_keys[] = $matches[0][0];
		}

		// Get ID's Data.
		$new_ID = max( $new_keys );
		$old_ID = youzify_option( 'youzify_next_' . $item . '_nbr' );
		$max_ID = ( $new_ID < $old_ID ) ? $old_ID : $new_ID;

		// Return Biggest Key.
		return $max_ID + 1;
	}

    /**
     * Get Exist ADS widgets
     */
    function get_custom_widgets( $widgets ) {

        // Set Up new array
        $custom_widgets = array();

        foreach ( $widgets as $widget_name => $visibility ) {
            // If key contains 'youzify_custom_widget_'.
            if ( false !== strpos( $widget_name, 'youzify_custom_widget_' ) ) {
                $custom_widgets[ $widget_name ] = $visibility;
            }
        }

        return $custom_widgets;
    }


    /**
     * Get Exist ADS widgets
     */
    function get_ads_widgets( $widgets ) {

        // Set Up new array
        $ads_widgets = array();

        foreach ( $widgets as $widget_name => $data ) {
            // If key contains 'youzify_ad_'.
            if ( false !== strpos( $widget_name, 'youzify_ad_' ) ) {
                $ads_widgets[ $widget_name ] = $data;
            }
        }

        return $ads_widgets;
    }

	/**
	 * Reset Settings.
	 */
	function membership_reset_settings() {

		if ( defined( 'YOUZIFY_MEMBERSHIP_CORE' ) ) {

			// Include Styling.
			include YOUZIFY_MEMBERSHIP_CORE . 'class-youzify-styling.php';

			// Init Class.
            $styling = new Youzify_Membership_Styling();

			// Reset Styling Input's
	        foreach ( $styling->styles_data() as $key ) {
				if ( youzify_option( $key['id'] ) ) {
					delete_option( $key['id'] );
				}
	        }

		}

		// Specific Options.
		$specific_options = array(
			'youzify_login_cover',
			'youzify_signup_cover',
			'youzify_lostpswd_cover'
		);

		// Reset Specific Options.
		foreach ( $specific_options as $option ) {
			if ( youzify_option( $option ) ) {
				youzify_delete_option( $option );
			}
		}

		// Get Providers.
		$providers = youzify_get_social_login_providers();

		// Reset Social Provider Input's.
        foreach ( $providers as $provider ) {

        	// Transform Provider Name to lower case.
        	$provider = strtolower( $provider );

        	// Reset Provider Status's
			if ( youzify_option( 'youzify_' . $provider . '_app_status' ) ) {
				youzify_delete_option( 'youzify_' . $provider . '_app_status' );
			}

        	// Reset Provider Keys.
			if ( youzify_option( 'youzify_' . $provider . '_app_key' ) ) {
				youzify_delete_option( 'youzify_' . $provider . '_app_key' );
			}

        	// Reset Provider Secret Keys.
			if ( youzify_option( 'youzify_' . $provider . '_app_secret' ) ) {
				youzify_delete_option( 'youzify_' . $provider . '_app_secret' );
			}

        	// Reset Provider Notes.
			if ( youzify_option( 'youzify_' . $provider .'_setup_steps' ) ) {
				youzify_delete_option( 'youzify_' . $provider .'_setup_steps' );
			}

        }
	}

	/**
	 * Sanitize Youzify Options
	 **/
	function sanitize_youzify_options( $options ) {

    	array_walk_recursive( $options, function( &$value, $key ) {
    		if ( in_array( $key, array( 'youzify_global_custom_styling', 'youzify_profile_custom_styling', 'youzify_account_custom_styling', 'youzify_groups_custom_styling', 'youzify_members_directory_custom_styling', 'youzify_groups_directory_custom_styling', 'youzify_activity_custom_styling', 'youzify_profile_404_desc' ) ) ) {
	            $value = sanitize_textarea_field( $value );
    		} else {
	            $value = sanitize_text_field( $value );
    		}
        });

        return $options;
	}

}

new Youzify_Admin_Ajax();