<?php
if ( isset( $_POST['security'] ) ) {
	$wl_rcsm_options 	 = weblizar_rcsm_get_options();
	$rcsm_message_unable = esc_html__('Unable to create report file!', 'RCSM_TEXT_DOMAIN');
	if ( wp_verify_nonce( $_POST['security'], 'comsoon_security_action' ) ) {

		/*
		 * General settings save
		 */
		if ( isset( $_POST['weblizar_rcsm_settings_save_general_option'] ) ) {
			if ( sanitize_text_field($_POST['weblizar_rcsm_settings_save_general_option']) == 1 ) {
				foreach ( $_POST as  $key => $value ) {
					$wl_rcsm_options[ $key ] = sanitize_text_field( $_POST[ $key ] );
				}
				if ( $_POST['search_robots'] ) {
					echo esc_html( $wl_rcsm_options['search_robots'] = sanitize_text_field( $_POST['search_robots'] ) );
				} else {
					echo esc_html( $wl_rcsm_options['search_robots'] = 'off' );
				}
				update_option( 'weblizar_rcsm_options', stripslashes_deep( $wl_rcsm_options ) );
			}

			if ( $_POST['weblizar_rcsm_settings_save_general_option'] == 2 ) {
				rcsm_general_setting();
			}
		}

		/*
		 * Appearance settings save
		 */
		if ( isset( $_POST['weblizar_rcsm_settings_save_appearance_option'] ) ) {

			if ( sanitize_text_field($_POST['weblizar_rcsm_settings_save_appearance_option']) == 1 ) {
				foreach ( $_POST as  $key => $value ) {
					$wl_rcsm_options[ $key ] = sanitize_text_field( $_POST[ $key ] );
				}

				if ( sanitize_text_field($_POST['layout_status']) ) {
					echo esc_html( $wl_rcsm_options['layout_status'] = sanitize_text_field( $_POST['layout_status'] ) );
				} else {
					echo esc_html( $wl_rcsm_options['layout_status'] = 'deactivate' );
				}

				if ( isset( $_POST['button_onoff'] ) ) {
					echo esc_html( $wl_rcsm_options['button_onoff'] = sanitize_text_field( $_POST['button_onoff'] ) );
				} else {
					echo esc_html( $wl_rcsm_options['button_onoff'] = 'off' );
				}
				if ( $_POST['link_admin'] ) {
					echo esc_html( $wl_rcsm_options['link_admin'] = sanitize_text_field( $_POST['link_admin'] ) );
				} else {
					echo esc_html( $wl_rcsm_options['link_admin'] = 'off' );
				}
				update_option( 'weblizar_rcsm_options', stripslashes_deep( $wl_rcsm_options ) );
			}
			if ( sanitize_text_field($_POST['weblizar_rcsm_settings_save_appearance_option']) == 2 ) {
				rcsm_appearance_setting();
			}
		}

		/*
		* Access Control setting
		*/
		if ( isset( $_POST['weblizar_rcsm_settings_save_access_control_option'] ) ) {
			if ( $_POST['weblizar_rcsm_settings_save_access_control_option'] == 1 ) {
				foreach ( $_POST as  $key => $value ) {
					$wl_rcsm_options[ $key ] = sanitize_text_field( $_POST[ $key ] );
				}
				$i = 0;
				foreach ( $_POST['user_value'] as $user_value ) {
					if ( $user_value != '' ) {
						$value_get[ $i ] = $user_value;}
					$i++;
				}
				$wl_rcsm_options['user_value'] = $value_get;
				update_option( 'weblizar_rcsm_options', stripslashes_deep( $wl_rcsm_options ) );
			}
			if ( $_POST['weblizar_rcsm_settings_save_access_control_option'] == 2 ) {
				rcsm_access_control_setting();
			}
		}

		/*
		 * Layout Swapping Settings
		 */
		if ( isset( $_POST['weblizar_rcsm_settings_save_pagelayoutmanger'] ) ) {
			if ( $_POST['weblizar_rcsm_settings_save_pagelayoutmanger'] == 2 ) {
				rcsm_page_layout_swap_setting();
			}
		}

		if ( isset( $_POST['rcsm_layout_data'] ) ) {
			if ( $_POST['rcsm_layout_data'] ) {
				/*send data hold*/
				$datashowredify = sanitize_text_field( $_POST['rcsm_layout_data'] );
				$hold           = strstr( $datashowredify, '|' );
				$datashowredify = str_replace( '|', '', $hold );
				$data           = explode( ',', $datashowredify );
				/*data save*/
				$wl_rcsm_options['page_layout_swap'] = $data;
				/*update all field*/
				update_option( 'weblizar_rcsm_options', $wl_rcsm_options );
			}
		}

		/*
		 * Layout Settings
		 */
		if ( isset( $_POST['weblizar_rcsm_settings_save_layout_option'] ) ) {
			if ( sanitize_text_field($_POST['weblizar_rcsm_settings_save_layout_option']) == 1 ) {
				foreach ( $_POST as  $key => $value ) {
					$wl_rcsm_options[ $key ] = sanitize_text_field( $_POST[ $key ] );
				}
				update_option( 'weblizar_rcsm_options', stripslashes_deep( $wl_rcsm_options ) );
			}
			if ( $_POST['weblizar_rcsm_settings_save_layout_option'] == 2 ) {
				rcsm_layout_setting();
			}
		}

		/**
		 * social media link Settings
		 */ 
		if ( isset( $_POST['weblizar_rcsm_settings_save_social_option'] ) ) {
			if ( sanitize_text_field($_POST['weblizar_rcsm_settings_save_social_option']) == 1 ) {	
				foreach ( $_POST as  $key => $value ) {	
					$wl_rcsm_options[ $key ] = sanitize_text_field($_POST[ $key ]);
				}
				update_option( 'weblizar_rcsm_options', stripslashes_deep( $wl_rcsm_options ) );
			}
			if ( sanitize_text_field($_POST['weblizar_rcsm_settings_save_social_option']) == 2 ) {
				rcsm_social_setting();
			}
		}

		/*
		* Subscriber Form Setting
		*/
		if ( isset( $_POST['weblizar_rcsm_settings_save_subscriber_option'] ) ) {
			$option_action = intval( $_POST['weblizar_rcsm_settings_save_subscriber_option'] );

			if ( $option_action === 1 ) {
				$wl_rcsm_options = array();

				foreach ( $_POST as $key => $value ) {
					$wl_rcsm_options[ $key ] = sanitize_text_field( $value );
				}

				$subscriber_form = isset( $_POST['subscriber_form'] ) ? sanitize_text_field( $_POST['subscriber_form'] ) : 'off';
				$wl_rcsm_options['subscriber_form'] = $subscriber_form;

				update_option( 'weblizar_rcsm_options', stripslashes_deep( $wl_rcsm_options ) );
			} elseif ( $option_action === 2 ) {
				rcsm_subscriber_form_setting();
			}
		}

		if ( isset( $_POST['weblizar_rcsm_settings_save_subscriber_provider_option'] ) ) {
			if ( $_POST['weblizar_rcsm_settings_save_subscriber_provider_option'] == 1 ) {
				foreach ( $_POST as  $key => $value ) {
					$wl_rcsm_options[ $key ] = sanitize_text_field( $_POST[ $key ] );
				}
				if ( isset( $_POST['confirm_email_subscribe'] ) ) {
					$wl_rcsm_options['confirm_email_subscribe'] = sanitize_text_field( $_POST['confirm_email_subscribe'] );
				} else {
					$wl_rcsm_options['confirm_email_subscribe'] = 'off';
				}
				update_option( 'weblizar_rcsm_options', stripslashes_deep( $wl_rcsm_options ) );
			}
			if ( $_POST['weblizar_rcsm_settings_save_subscriber_provider_option'] == 2 ) {
				rcsm_subscriber_provider_setting();
			}
		}

		if ( isset( $_POST['weblizar_rcsm_settings_save_subscriber_list_option'] ) ) {
			if ( $_POST['weblizar_rcsm_settings_save_subscriber_list_option'] == 1 ) {
				foreach ( $_POST as  $key => $value ) {
					$wl_rcsm_options[ $key ] = sanitize_text_field( $_POST[ $key ] );
				}
				update_option( 'weblizar_rcsm_options', stripslashes_deep( $wl_rcsm_options ) );
			}
			if ( $_POST['weblizar_rcsm_settings_save_subscriber_list_option'] == 2 ) {
				rcsm_subscriber_list_setting();
			}
		}

		/*
		 * Subscriber Form table Data setting
		 */
		if ( isset( $_POST['weblizar_rcsm_subscriber_users_action'] ) ) {
			if ( sanitize_text_field($_POST['weblizar_rcsm_subscriber_users_action'] )== 1 ) {
				global $wpdb;
				header( 'Content-Type: text/csv' );
				header( 'Content-Disposition: inline; filename="all-subscriber-list-' . date( 'YmdHis' ) . '.csv"' );
				$results = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'rcsm_subscribers' );
				echo "Email, Date, Activate-code, Status\r\n";
				if ( count( $results ) ) {
					foreach ( $results as $row ) {
						if ( $row->flag == '1' ) {
							$flags = 'Subscribed';
						} else {
							$flags = 'Pending';}
						echo esc_html( $row->email . ', ' . $row->date . ', ' . $row->act_code . ', ' . $flags . "\r\n" );
					}
				}
			}
			if ( sanitize_text_field($_POST['weblizar_rcsm_subscriber_users_action']) == 3 ) {
				global $wpdb;
				$filename = "pending-subscriber-list-'.date('YmdHis').'.csv";
				header( 'Content-Description: File Transfer' );
				header( 'Content-Disposition: attachment; filename=' . $filename );
				header( 'Content-Type: text/csv; charset=' . get_option( 'blog_charset' ), true );
				$results = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . "rcsm_subscribers WHERE flag = '0'" );
				echo "Email, Date, Activate-code\r\n";
				if ( count( $results ) ) {
					foreach ( $results as $row ) {
						echo esc_html( $row->email . ', ' . $row->date . ', ' . $row->act_code . "\r\n" );
					}
				}
			}
			if ( sanitize_text_field($_POST['weblizar_rcsm_subscriber_users_action']) == 2 ) {
				require_once 'export-subscribed-csv.php';
			}
		}

		/*m_subsc
		* Subscriber Form Data save setting
		*/
		if ( isset( $_POST['weblizar_rcsm_settings_save_subscribe_form'] ) ) {
			if ( sanitize_text_field($_POST['weblizar_rcsm_settings_save_subscribe_form']) == 1 ) {
				foreach ( $_POST as  $key => $value ) {
					$wl_rcsm_options[ $key ] = sanitize_text_field( $_POST[ $key ] );
				}
				if ( $_POST['auto_sentto_activeusers'] ) {
					echo esc_html( $wl_rcsm_options['auto_sentto_activeusers'] = sanitize_text_field( $_POST['auto_sentto_activeusers'] ) );
				} else {
					echo esc_html( $wl_rcsm_options['auto_sentto_activeusers'] = 'off' );
				}
				update_option( 'weblizar_rcsm_options', stripslashes_deep( $wl_rcsm_options ) );
			}
			if ( sanitize_text_field($_POST['weblizar_rcsm_settings_save_subscribe_form']) == 2 ) {
				rcsm_subscriber_list_setting();
			}
		}

/*
* Remove ids
*/

// if( $_POST['ids'] ){
// 	global $wpdb;
// 	print_r($_POST['ids']);
// 	die();
// 	$subs_ids = is_array( $_POST['ids'] );	
// 	$table_name = $wpdb->prefix . 'rcsm_subscribers';
// 	$j          = 0;
// 	if ( is_array( $_POST['ids'] ) ) {					
// 		// foreach ( $_POST['remove_id'] as $subs_id ) {
// 			$countIds = count($_POST['ids']);

// 			for( $i=0; $i< $countIds; $i++ ) {
// 				$wpdb->delete( $table_name, array( 'id' => $subs_id[$i] ), array( '%d' ) );
// 		}
// 	}
// } 
		/**
		 * Subscriber Form Mailed to Subscribers Users as selected action and Subscriber Form Data Removed setting
		 */
		if ( isset( $_POST['weblizar_rcsm_submit_subscriber'] ) ) {
			global $wpdb;
			$table_name = $wpdb->prefix . 'rcsm_subscribers';
			if ( sanitize_text_field($_POST['weblizar_rcsm_submit_subscriber']) == 1 ) {
				$email_check = $wpdb->get_results( "SELECT * FROM $table_name WHERE id !=0" );
			} elseif ( sanitize_text_field($_POST['weblizar_rcsm_submit_subscriber']) == 2 ) {
				$z = 0;
				if ( is_array( $_POST['rem'] ) ) {
					foreach ( $_POST['rem'] as $subscribe_id ) {
						$subscribe_id = intval( $subscribe_id ); // Ensure $subscribe_id is an integer
						if ( $subscribe_id !== 0 ) {
							$email_check = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $subscribe_id ) );
						}
						$z++;
					}
				}
				}elseif ( sanitize_text_field($_POST['weblizar_rcsm_submit_subscriber']) == 3 ) {
				$email_check = $wpdb->get_results( "SELECT * FROM $table_name WHERE flag = '0'" );
			} elseif ( sanitize_text_field($_POST['weblizar_rcsm_submit_subscriber']) == 4 ) {
				$email_check = $wpdb->get_results( "SELECT * FROM $table_name WHERE flag = '1'" );
			} elseif ( sanitize_text_field($_POST['weblizar_rcsm_submit_subscriber']) == 5 ) {
				$email_check = $wpdb->get_results( "SELECT * FROM $table_name WHERE flag = '2'" );
			} elseif ( sanitize_text_field($_POST['weblizar_rcsm_submit_subscriber']) == 6 ) {
				global $wpdb;
				$table_name = $wpdb->prefix . 'rcsm_subscribers';
				$j          = 0;
				if ( is_array( $_POST['rem'] ) ) {					
					foreach ( ($_POST['rem']) as $subscribe_ids ) {
						if ( sanitize_text_field($subscribe_ids) != '' ) {
							$wpdb->delete( $table_name, array( 'id' => sanitize_text_field($subscribe_ids) ), array( '%d' ) );
						}
						$j++;
					}
				}
			} elseif ( sanitize_text_field($_POST['weblizar_rcsm_submit_subscriber']) == 7 ) {
				global $wpdb;
				$table_name = $wpdb->prefix . 'rcsm_subscribers';
				$wpdb->query( $wpdb->prepare( "DELETE FROM $table_name WHERE flag != 30" ) );
			}
			if ( $email_check ) {
				foreach ( $email_check as $all_emails ) {
					$subscriber_email = $all_emails->email;
					$f_name           = $all_emails->f_name;
					$l_name           = $all_emails->l_name;
					$flag_act         = $all_emails->flag;
					$current_time     = current_time( 'Y-m-d h:i:s' );
					$adminemail       = $wl_rcsm_options['wp_mail_email_id'];
					$plugin_url       = site_url();
					$headers          = 'Content-type: text/html' . "\r\n" . "From:$plugin_url <$adminemail>" . "\r\n" . 'Reply-To: ' . $adminemail . "\r\n" . 'X-Mailer: PHP/' . phpversion();
					$subject          = sanitize_text_field($_POST['subscriber_mail_subject']) . ': Confirmation Subscription';
					$message          = 'Hi ' . $f_name . ' ' . $l_name . ', <br/>';
					global $current_user;
					wp_get_current_user();
					$plugin_site_url = site_url();
					$message        .= sanitize_text_field($_POST['subscriber_mail_message']);
					$wp_mails        = wp_mail( $subscriber_email, $subject, $message, $headers );
					// if($wp_mails){
						// $user_search_result = $wpdb->get_row("SELECT * FROM `$table_name` WHERE `email` LIKE '$subscriber_email' AND `flag` LIKE '$flag_act'");
						// if(count($user_search_result)) {
							// check user is already subscribed
							// if($user_search_result->flag != 2) {
							// $wpdb->query("UPDATE `$table_name` SET `flag` = '2' WHERE `email` = '$subscriber_email'");
							// }
						// }
					// }
				}
			}
		}

		/*
		* Counter Clock Settings
		*/
		if ( isset( $_POST['weblizar_rcsm_settings_save_counter_clock_option'] ) ) {
			if ( sanitize_text_field($_POST['weblizar_rcsm_settings_save_counter_clock_option']) == 1 ) {
				foreach ( $_POST as  $key => $value ) {
					$wl_rcsm_options[ $key ] = sanitize_text_field( $_POST[ $key ] );
				}
				// google map on conatact page
				if ( isset( $_POST['disable_the_plugin'] ) ) {
					$wl_rcsm_options['disable_the_plugin'] = $_POST['disable_the_plugin'];
				} else {
					$wl_rcsm_options['disable_the_plugin'] = 'off';
				}
				update_option( 'weblizar_rcsm_options', stripslashes_deep( $wl_rcsm_options ) );
			}
			if ( sanitize_text_field($_POST['weblizar_rcsm_settings_save_counter_clock_option']) == 2 ) {
				rcsm_counter_clock_setting();
			}
		}

		if ( isset( $_POST['weblizar_rcsm_settings_save_counter_clock_layout_option'] ) ) {
			if ( sanitize_text_field($_POST['weblizar_rcsm_settings_save_counter_clock_layout_option']) == 1 ) {
				foreach ( $_POST as  $key => $value ) {
					$wl_rcsm_options[ $key ] = sanitize_text_field( $_POST[ $key ] );
				}
				update_option( 'weblizar_rcsm_options', stripslashes_deep( $wl_rcsm_options ) );
			}
			// if ( $_POST['weblizar_rcsm_settings_save_counter_clock_layout_option'] == 2 ) {
			// 	rcsm_counter_clock_layout_setting();
			// }
		}

		/*
		* footer area setting
		*/
		if ( isset( $_POST['weblizar_rcsm_settings_save_footer_option'] ) ) {
			if ( $_POST['weblizar_rcsm_settings_save_footer_option'] == 1 ) {
				foreach ( $_POST as  $key => $value ) {
					$wl_rcsm_options[ $key ] = sanitize_text_field( $_POST[ $key ] );
				}
				update_option( 'weblizar_rcsm_options', stripslashes_deep( $wl_rcsm_options ) );
			}
			if ( $_POST['weblizar_rcsm_settings_save_footer_option'] == 2 ) {
				rcsm_footer_setting();
			}
		}

		/*
		* Advance Settings
		*/
		if ( isset( $_POST['weblizar_rcsm_settings_save_advance_settings_option'] ) ) {
			if ( $_POST['weblizar_rcsm_settings_save_advance_settings_option'] == 1 ) {
				foreach ( $_POST as  $key => $value ) {
					$wl_rcsm_options[ $key ] = sanitize_text_field( $_POST[ $key ] );
				}
				// Social Icons section yes or on
				if ( isset( $_POST['show_notice_bar'] ) ) {
					$wl_rcsm_options['show_notice_bar'] = sanitize_text_field( $_POST['show_notice_bar'] );
				} else {
					$wl_rcsm_options['show_notice_bar'] = 'off';
				}

				// Social Icons section yes or on
				if ( isset( $_POST['show_admin_link'] ) ) {
					$wl_rcsm_options['show_admin_link'] = sanitize_text_field( $_POST['show_admin_link'] );
				} else {
					$wl_rcsm_options['show_admin_link'] = 'off';
				}
				update_option( 'weblizar_rcsm_options', stripslashes_deep( $wl_rcsm_options ) );
			}
			if ( $_POST['weblizar_rcsm_settings_save_advance_settings_option'] == 2 ) {
				rcsm_advance_option_setting();
			}
		}

		/**
		 * feedback area setting
		 */
		if ( isset( $_POST['weblizar_rcsm_settings_all_restored_settings_option'] ) ) {
			if ( $_POST['weblizar_rcsm_settings_all_restored_settings_option'] == 2 ) {
				rcsm_general_setting();
				rcsm_appearance_setting();
				rcsm_access_control_setting();
				rcsm_page_layout_swap_setting();
				//rcsm_skin_layout_setting();
				rcsm_social_setting();
				rcsm_subscriber_form_setting();
				rcsm_subscriber_provider_setting();
				rcsm_subscriber_list_setting();
				rcsm_counter_clock_setting();
				//rcsm_counter_clock_layout_setting();
				rcsm_footer_setting();
				rcsm_advance_option_setting();
			}
		}

		if ( isset( $_POST['weblizar_rcsm_settings_save_feedback_form_option'] ) ) {
			if ( $_POST['weblizar_rcsm_settings_save_feedback_form_option'] == 1 ) {
				foreach ( $_POST as  $key => $value ) {
					$wl_rcsm_options[ $key ] = sanitize_text_field( $_POST[ $key ] );
				}
				update_option( 'weblizar_rcsm_options', stripslashes_deep( $wl_rcsm_options ) );
			}
			if ( $_POST['weblizar_rcsm_settings_save_feedback_form_option'] == 2 ) {
				rcsm_feedback_setting();
			}
		}

		/**
		 * subscriber list file generate
		 */
		if ( isset( $_POST['list_type'] ) && isset( $_POST['file_date_time'] ) ) {
			global $wpdb;
			$list_type = sanitize_text_field( $_POST['list_type'] );
			// set file parameter
			$upload_dir_all  = wp_upload_dir();
			$upload_dir_path = $upload_dir_all['basedir'];
			$upload_dir_url  = $upload_dir_all['baseurl'];
			$file_date_time  = sanitize_text_field( $_POST['file_date_time'] );

			// all subscribers list
			if ( $list_type == 'subscribers' ) {

				// create a file & write header
				$file_name      = 'all-subscribers-list-' . $file_date_time . '.csv';
				$report_file    = fopen( $upload_dir_path . '/' . $file_name, 'w' ) or die( $rcsm_message_unable );
				$csv_headertext = "Name, Email, Date, Subscription Status, Activate-code \r \n";
				fwrite( $report_file, $csv_headertext );

				// fetch all data
				$results = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'rcsm_subscribers' );
				if ( count( $results ) ) {
					foreach ( $results as $row ) {
						if ( $row->flag == '1' ) {
							 $flags = 'Subscribed';
						} else {
							$flags = 'Pending';
						}
						$txt = $row->f_name . ' ' . $row->l_name . ', ' . $row->email . ', ' . $row->date . ', ' . $flags . ',' . $row->act_code . "\r \n";
						fwrite( $report_file, $txt );
					}
				}
			}

			// active subscribers list
			if ( $list_type == 'active' ) {
				// create a file & write header
				$file_name      = 'all-active-list-' . $file_date_time . '.csv';
				$report_file    = fopen( $upload_dir_path . '/' . $file_name, 'w' ) or die( $rcsm_message_unable );
				$csv_headertext = "Name, Email, Date, Subscription Status, Activate-code \r \n";
				fwrite( $report_file, $csv_headertext );

				// fetch all data
				$results = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . "rcsm_subscribers WHERE flag = '1'" );
				if ( count( $results ) ) {
					foreach ( $results as $row ) {
						if ( $row->flag == '1' ) {
							 $flags = 'Subscribed';
						} else {
							$flags = 'Pending';
						}
						$txt = $row->f_name . ' ' . $row->l_name . ', ' . $row->email . ', ' . $row->date . ', ' . $flags . ',' . $row->act_code . "\r \n";
						fwrite( $report_file, $txt );
					}
				}
			}

			// pending subscribers list
			if ( $list_type == 'pending' ) {
				// create a file & write header
				$file_name      = 'all-pending-list-' . $file_date_time . '.csv';
				$report_file    = fopen( $upload_dir_path . '/' . $file_name, 'w' ) or die( $rcsm_message_unable );
				$csv_headertext = "Name, Email, Date, Subscription Status, Activate-code \r \n";
				fwrite( $report_file, $csv_headertext );

				// fetch all data
				$results = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . "rcsm_subscribers WHERE flag = '0'" );
				if ( count( $results ) ) {
					foreach ( $results as $row ) {
						if ( $row->flag == '1' ) {
							 $flags = 'Subscribed';
						} else {
							$flags = 'Pending';
						}
						$txt = $row->f_name . ' ' . $row->l_name . ', ' . $row->email . ', ' . $row->date . ', ' . $flags . ',' . $row->act_code . "\r \n";
						fwrite( $report_file, $txt );
					}
				}
			}
		}
	} // end of nonce verify
} // end of security isset check
