<?php

if ( ! class_exists( 'ARM_restriction_Lite' ) ) {
	class ARM_restriction_Lite {

		function __construct() {
			global $wp, $wpdb, $ARMemberLite, $arm_slugs, $arm_global_settings;
			add_action( 'init', array( $this, 'arm_set_current_user' ), 11 );

			add_action( 'init', array( $this, 'arm_restriction_init' ), 12 );

			add_action( 'parse_request', array( $this, 'arm_restrict_all_access' ), 1 );
			add_filter( 'the_content', array( $this, 'arm_the_content_filter' ), 11 );
			add_filter( 'arm_restricted_site_access_allow_pages', array( $this, 'arm_filter_allow_page_ids' ), 10, 1 );

			add_action( 'wp', array( $this, 'arm_wp_head_redirect' ), 6 );
			add_filter( 'rest_pre_echo_response', array( $this, 'arm_rest_api_pre_echo_response_func' ), 10, 3);

			add_action( 'pre_get_posts', array( $this, 'arm_pre_get_posts' ), 11 );
			add_filter( 'get_terms_args', array( $this, 'arm_get_terms_args' ), 20, 2 );
			add_filter( 'wp_get_nav_menu_items', array( $this, 'arm_wp_get_nav_menu_items' ), 50, 3 );
			add_action( 'wp_nav_menu_objects', array( $this, 'arm_wp_nav_menu_items' ), 10, 2 );

			add_filter( 'widget_posts_args', array( $this, 'arm_widget_posts_args' ) );
			add_filter( 'widget_pages_args', array( $this, 'arm_widget_pages_args' ) );

			/*
						 * ******************./Begin Feeds Protection/.******************** */
			// add_filter('feed_link', array($this, 'arm_feed_link'), 99, 2);
			/*             * ******************./End Feeds Protection./******************** */

			add_filter( 'posts_join', array( $this, 'arm_custom_posts_join' ), 11 );
			add_filter( 'posts_where', array( $this, 'arm_filter_where' ), 11, 2 );
			add_filter( 'posts_distinct', array( $this, 'arm_search_distinct' ) );
		}

		function arm_search_distinct() {
			return 'DISTINCT';
		}



		function arm_custom_posts_join( $join ) {
			global $wpdb, $current_user, $wp_query, $arm_access_rules;

			$this->arm_add_required_core_file();
			$arm_default_access_rules = $arm_access_rules->arm_get_default_access_rules();

			if ( isset( $wp_query ) && ! empty( $wp_query ) && $wp_query != null ) {
				if ( ! is_admin() && ! $wp_query->is_singular() && ! current_user_can( 'administrator' ) ) {
					if ( $wp_query->get( 'post_type' ) != 'nav_menu_item' && $wp_query->get( 'post_type' ) != 'page' && $wp_query->get( 'post_type' ) != 'attachment' ) {
						$join .= " LEFT JOIN $wpdb->postmeta AS myarmjoin ON ($wpdb->posts.ID = myarmjoin.post_id AND myarmjoin.meta_key = 'arm_access_plan' ) ";
					}
				}
			}
			return $join;
		}

		function arm_filter_where( $where, $obj ) {
			global $wpdb, $current_user, $wp_query, $arm_access_rules;

			$this->arm_add_required_core_file();
			$arm_default_access_rules = $arm_access_rules->arm_get_default_access_rules();

			if ( isset( $wp_query ) && ! empty( $wp_query ) && $wp_query != null ) {
				if ( ! is_admin() && ! $wp_query->is_singular() && ! current_user_can( 'administrator' ) ) {
					if ( $wp_query->get( 'post_type' ) != 'nav_menu_item' && $wp_query->get( 'post_type' ) != 'page' && $wp_query->get( 'post_type' ) != 'attachment' ) {
						if ( is_user_logged_in() ) {

							$current_user_plan       = $current_user->get( 'arm_user_plan_ids' );
							$current_user_plan_array = ! empty( $current_user_plan ) ? $current_user_plan : array( -2 );

							$suspended_plan_ids = get_user_meta( $current_user->ID, 'arm_user_suspended_plan_ids', true );
							$suspended_plan_ids = ( isset( $suspended_plan_ids ) && ! empty( $suspended_plan_ids ) ) ? $suspended_plan_ids : array();
							if ( ! empty( $current_user_plan_array ) && is_array( $current_user_plan_array ) ) {
								foreach ( $current_user_plan_array as $cp ) {
									if ( in_array( $cp, $suspended_plan_ids ) ) {
										unset( $current_user_plan_array[ array_search( $cp, $current_user_plan_array ) ] );
									}
								}
							}

							$current_user_plan_array = ! empty( $current_user_plan_array ) ? $current_user_plan_array : array( -2 );
							$arm_primary_status      = arm_get_member_status( $current_user->ID );
							if ( $arm_primary_status == 3 ) {
								$current_user_plan_array = array( -5 );
							}

							$current_user_plan = implode( ',', $current_user_plan_array );

							$where .= " AND ( ( myarmjoin.post_id IS NULL ) OR ( myarmjoin.meta_key = 'arm_access_plan' AND myarmjoin.meta_value IN ( " . $current_user_plan . ' ) )';

								$where .= ')';

						} else {
							$where .= ' AND ( myarmjoin.post_id IS NULL )';
						}
					}
				} elseif ( ! is_admin() && ! current_user_can( 'administrator' ) && ! $obj->is_singular() ) {
					$arm_posts = $this->arm_widget_posts_args( array() );
					if ( is_array( $arm_posts ) && count( $arm_posts ) > 0 ) {
						$arm_posts_filter = isset( $arm_posts['post__not_in'] ) ? $arm_posts['post__not_in'] : '';
						if ( ! empty( $arm_posts_filter ) && is_array( $arm_posts_filter ) ) {
							if ( MEMBERSHIPLITE_DEBUG_LOG == true ) {
								if ( MEMBERSHIPLITE_DEBUG_LOG_TYPE == 'ARM_ALL' || MEMBERSHIPLITE_DEBUG_LOG_TYPE == 'ARM_ADMIN_PANEL' ) {
									$arm_case_types['admin_panel']['protected'] = true;
									$arm_case_types['admin_panel']['message']   = esc_html__( 'WordPress Page/Post is restricted by admin', 'armember-membership' );
									$ARMemberLite->arm_debug_response_log( 'arm_filter_where', $arm_case_types, array(), $wpdb->last_query );
								}
							}
							$arm_posts_filter_implode = implode( ',', $arm_posts_filter );

							$where = $where . ' AND ' . $wpdb->posts . '.ID NOT IN (' . $arm_posts_filter_implode . ')  ';
						}
					}
				}
			}
			return $where;
		}

		function arm_set_current_user() {
			global $wp, $wpdb, $current_user, $arm_lite_errors, $ARMemberLite;

			$this->arm_add_required_core_file();
			$current_user_plan = $current_user->get( 'arm_user_plan_ids' );

			$current_user->arm_user_plan_ids = $current_user_plan;
			if ( ! is_admin() && ! empty( $current_user->ID ) && ! current_user_can( 'administrator' ) ) {
				$current_user->arm_primary_status   = arm_get_member_status( $current_user->ID );
				$current_user->arm_secondary_status = arm_get_member_status( $current_user->ID, 'secondary' );
			}

			$this->arm_posts_meta_rules();
		}

		function arm_restriction_init() {

			global $wp, $wpdb, $pagenow, $current_user, $arm_lite_errors, $ARMemberLite, $arm_global_settings, $arm_case_types;
			$this->arm_add_required_core_file();
			$all_settings = $arm_global_settings->global_settings;
			/* Restrict `admin panel` for non-admin users */
			$current_user = wp_get_current_user();
			if ( ! ( 0 == $current_user->ID ) ) {

				if ( ! current_user_can( 'administrator' ) ) {
					/* Get Visitor's IP Address. */
					if ( is_user_logged_in() ) {
						if ( is_super_admin( $current_user->ID ) ) {
							return;
						}
						/**
						 * Logout Member when User is not active
						 */
						$userstatus = arm_get_member_status( $current_user->ID );
						$isInactive = ( $userstatus == '1' ) ? false : true;
						if ( $isInactive ) {
							if ( MEMBERSHIPLITE_DEBUG_LOG == true ) {
								$arm_case_types['admin_panel']['protected'] = true;
								$arm_case_types['admin_panel']['message']   = $current_user->user_login.' '.esc_html__('is inactive', 'armember-membership' );
								$ARMemberLite->arm_debug_response_log( 'arm_restriction_init', $arm_case_types, array(), $wpdb->last_query );
							}
						}
					}/* End `(is_user_logged_in())` */
				}
			}

			$block_settings = $arm_global_settings->block_settings;
			$currentr_ip    = $ARMemberLite->arm_get_ip_address();
			/* Redirect User if requested url is in restricted urls. */

			/* Restrict `wp-login.php` & `wp-signup.php` page. */

			$hide_wp_login = isset( $all_settings['hide_wp_login'] ) ? $all_settings['hide_wp_login'] : 0;

			if ( $hide_wp_login == 1 ) {

				$GLOBALS['pagenow'] = ( isset( $GLOBALS['pagenow'] ) ) ? $GLOBALS['pagenow'] : $pagenow;

				$hide_pages = array( 'wp-login.php', 'wp-signup.php', 'wp-register.php' );

				if ( in_array( $GLOBALS['pagenow'], $hide_pages ) || in_array( $pagenow, $hide_pages ) ) {

					if ( isset( $_GET['arm-key'] ) || isset( $_GET['key'] ) || ( isset( $_GET['action'] ) && in_array( $_GET['action'], array( 'logout', 'armrp', 'resetpass', 'lostpassword', 'retrievepassword', 'postpass' ) ) ) ) {

						return;
					} else {

						$interim_login = ( isset( $_REQUEST['interim-login'] ) && $_REQUEST['interim-login'] == 1 ) ? true : false;

						if ( $interim_login ) {

							return;
						} else {

							$arm_all_global_settings = $arm_global_settings->arm_get_all_global_settings();

							$globalSettings = $arm_global_settings->global_settings;

							$arm_login_page = isset( $globalSettings['login_page_id'] ) ? $globalSettings['login_page_id'] : 0;

							if ( ! empty( $arm_login_page ) && $arm_login_page != 0 ) {

								$redirect_to = $arm_global_settings->arm_get_permalink( '', $arm_login_page );
							} else {

								$redirect_to = ARMLITE_HOME_URL;
							}

							if ( MEMBERSHIPLITE_DEBUG_LOG == true ) {
								if ( MEMBERSHIPLITE_DEBUG_LOG_TYPE == 'ARM_ALL' || MEMBERSHIPLITE_DEBUG_LOG_TYPE == 'ARM_ADMIN_PANEL' ) {
									$arm_case_types['admin_panel']['protected'] = true;
									$arm_case_types['admin_panel']['message']   = esc_html__( 'Admin Login Page is restricted by admin', 'armember-membership' );
									$ARMemberLite->arm_debug_response_log( 'arm_restriction_init', $arm_case_types, array(), $wpdb->last_query );
								}
							}

							wp_redirect( $redirect_to );

							exit;
						}
					}
				}/* End `(in_array($GLOBALS['pagenow'], $hide_pages) || in_array($pagenow, $hide_pages))` */
			}/* End `($hide_wp_login == 1)` */
		}

		function arm_filter_allow_page_ids( $pageIDs = array() ) {
			global $wp, $wpdb, $ARMemberLite, $arm_global_settings, $arm_access_rules;
			$allowIDs = array();
			if ( isset( $pageIDs['register_page_id'] ) ) {
				$allowIDs[] = $pageIDs['register_page_id'];
			}
			if ( isset( $pageIDs['login_page_id'] ) ) {
				$allowIDs[] = $pageIDs['login_page_id'];
			}
			if ( isset( $pageIDs['forgot_password_page_id'] ) ) {
				$allowIDs[] = $pageIDs['forgot_password_page_id'];
			}
			if ( isset( $pageIDs['change_password_page_id'] ) ) {
				$allowIDs[] = $pageIDs['change_password_page_id'];
			}
			if ( isset( $pageIDs['guest_page_id'] ) ) {
				$allowIDs[] = $pageIDs['guest_page_id'];
			}
			if ( isset( $pageIDs['thank_you_page_id'] ) ) {
				$allowIDs[] = $pageIDs['thank_you_page_id'];
			}
			if ( isset( $pageIDs['cancel_payment_page_id'] ) ) {
				$allowIDs[] = $pageIDs['cancel_payment_page_id'];
			}

			$arm_default_redirection_settings = get_option( 'arm_redirection_settings' );
			$arm_default_redirection_settings = maybe_unserialize( $arm_default_redirection_settings );

			$access_rules_options = $arm_default_redirection_settings['default_access_rules'];
			if ( ! empty( $access_rules_options['non_logged_in'] ) ) {
				if ( $access_rules_options['non_logged_in']['type'] == 'specific' && ! empty( $access_rules_options['non_logged_in']['redirect_to'] ) ) {
					$allowIDs[] = $access_rules_options['non_logged_in']['redirect_to'];
				}
			}
			$allowIDs = $ARMemberLite->arm_array_trim( $allowIDs );
			if ( ! empty( $allowIDs ) ) {
				$args = array(
					'post_type' => 'page',
					'include'   => $allowIDs,
				);
				/* Query Monitor Change */
				if ( isset( $GLOBALS['arm_all_posts'] ) && count( $GLOBALS['arm_all_posts'] ) > 0 ) {
					$all_posts = $GLOBALS['arm_all_posts'];
				} else {
					$all_posts                = get_posts( $args );
					$GLOBALS['arm_all_posts'] = $all_posts;
				}
				$post_id_slug_array = array();
				if ( ! empty( $all_posts ) ) {
					foreach ( $all_posts as $posts_array ) {
						$post_id_slug_array[ $posts_array->ID ] = $posts_array->post_name;
					}
				}
				foreach ( $allowIDs as $ID ) {
					if ( ! empty( $ID ) ) {
						$postSlug = isset( $post_id_slug_array[ $ID ] ) ? $post_id_slug_array[ $ID ] : '';
						if ( ! empty( $postSlug ) ) {
							$allowIDs[] = $postSlug;
						}
					}
				}
			}
			return $allowIDs;
		}

		function arm_restrict_all_access( $wp ) {
			global $current_user, $arm_restriction;
			// remove_action('parse_request', array($arm_restriction, 'arm_restrict_all_access'), 1); /* only need it the first time */
			global $wp, $wpdb, $current_user, $arm_lite_errors, $ARMemberLite, $arm_global_settings, $arm_subscription_plans;

			$this->arm_add_required_core_file();

			$current_path = wp_get_current_page_url();
			if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
				$current_path .= '?' . $_SERVER['QUERY_STRING']; //phpcs:ignore
			}

			$page_settings                       = $arm_global_settings->arm_get_single_global_settings( 'page_settings' );
			$page_settings['guest_page_id']      = isset( $page_settings['guest_page_id'] ) ? $page_settings['guest_page_id'] : 0;
			$restrict_site_redirect_guest_pageid = $page_settings['guest_page_id'];

			$allow_page_ids = apply_filters( 'arm_restricted_site_access_allow_pages', $page_settings );

			$arm_access_page_for_restrict_site = $arm_global_settings->arm_get_single_global_settings( 'arm_access_page_for_restrict_site', 0 );
			if ( isset( $arm_access_page_for_restrict_site ) && $arm_access_page_for_restrict_site != '' && $arm_access_page_for_restrict_site != '0' ) {
				if ( ! is_array( $arm_access_page_for_restrict_site ) ) {
					$arm_access_page_for_restrict_site = explode( ',', $arm_access_page_for_restrict_site );
				}
				if ( is_array( $arm_access_page_for_restrict_site ) && ! empty( $arm_access_page_for_restrict_site ) ) {
					foreach ( $arm_access_page_for_restrict_site as $arm_access_page_for_restrict_site ) {
						$allow_page_ids[] = $arm_access_page_for_restrict_site;
						$post             = get_post( $arm_access_page_for_restrict_site );
						if ( isset( $post->post_name ) ) {
							$allow_page_ids[] = $post->post_name;
						}
					}
				}
			}

			/* Check Feed Access Rules */
			if ( ! empty( $wp->query_vars['feed'] ) ) {
				$is_feed_access = $this->arm_check_feed_rules();
				if ( $is_feed_access ) {
					return;
				} else {
					add_filter( 'the_posts', array( $this, 'show_noaccess_feed' ), 1 );
				}
			}
			if ( true === ( is_admin() || is_user_logged_in() || current_user_can( 'administrator' ) || ( defined( 'WP_INSTALLING' ) && isset( $_GET['key'] ) ) ) ) {
				return;
			}
			/* Check if Restrict Site Access is enable. */

		}

		function arm_the_content_filter( $content ) {
			global $arm_global_settings, $wp, $post;
			if ( true === ( is_admin() || is_user_logged_in() || current_user_can( 'administrator' ) || ( defined( 'WP_INSTALLING' ) && isset( $_GET['key'] ) ) ) ) {
				return $content;
			}
			$current_page_id                     = get_the_ID();
			$restrict_site_access                = $arm_global_settings->arm_get_single_global_settings( 'restrict_site_access', 0 );
			$page_settings                       = $arm_global_settings->arm_get_single_global_settings( 'page_settings' );
			$page_settings['guest_page_id']      = isset( $page_settings['guest_page_id'] ) ? $page_settings['guest_page_id'] : 0;
			$restrict_site_redirect_guest_pageid = $page_settings['guest_page_id'];
			$allow_page_ids                      = apply_filters( 'arm_restricted_site_access_allow_pages', $page_settings );
			$arm_access_page_for_restrict_site   = $arm_global_settings->arm_get_single_global_settings( 'arm_access_page_for_restrict_site', 0 );
			if ( isset( $arm_access_page_for_restrict_site ) && $arm_access_page_for_restrict_site != '' && $arm_access_page_for_restrict_site != '0' ) {
				if ( ! is_array( $arm_access_page_for_restrict_site ) ) {
					$arm_access_page_for_restrict_site = explode( ',', $arm_access_page_for_restrict_site );
				}
				if ( is_array( $arm_access_page_for_restrict_site ) && ! empty( $arm_access_page_for_restrict_site ) ) {
					foreach ( $arm_access_page_for_restrict_site as $arm_access_page_for_restrict_site ) {
						$allow_page_ids[] = $arm_access_page_for_restrict_site;
						$post             = get_post( $arm_access_page_for_restrict_site );
						if ( isset( $post->post_name ) ) {
							$allow_page_ids[] = $post->post_name;
						}
					}
				}
			}
			if ( $restrict_site_access == '1' ) {
				$redirect_url  = home_url();
				$redirect_code = 302;
				if ( ( ! empty( $current_page_id ) && in_array( $current_page_id, $allow_page_ids ) ) || ( isset( $wp->query_vars['pagename'] ) && in_array( $wp->query_vars['pagename'], $allow_page_ids ) ) ) {
					return $content;
				}
				if ( ! empty( $restrict_site_redirect_guest_pageid ) && $restrict_site_redirect_guest_pageid != 0 ) {
					if ( $page_id = get_post_field( 'ID', $restrict_site_redirect_guest_pageid ) ) {
						$get_post = get_post( $page_id );
						$content  = $get_post->post_content;
						return $content;
					}
				} else {
					/* Redirect to login page */
					$redirect_url = wp_login_url( $current_path );
				}
				$extraVars = array();
				if ( is_user_logged_in() ) {
					$extraVars = array(
						'current_user_id' => get_current_user_id(),
						'current_plan_id' => get_user_meta( get_current_user_id(), 'arm_user_plan_ids', true ),
					);
				}
				$redirect_url  = apply_filters( 'arm_restricted_site_access_redirect_url', $redirect_url, $wp, $extraVars );
				$redirect_code = apply_filters( 'arm_restricted_site_access_head', $redirect_code, $wp );
				wp_redirect( $redirect_url, $redirect_code );
				die;
			}
			return $content;
		}

		function curPageURL() {
			$pageURL = 'http';
			if ( isset( $_SERVER['HTTPS'] ) ) {
				if ( $_SERVER['HTTPS'] == 'on' ) {
					$pageURL .= 's';
				}
			}
			$pageURL .= '://';
			/*
			if ($_SERVER["SERVER_PORT"] != "80") {
				$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
			} else {
				$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
			}
			*/
			$pageURL .= sanitize_text_field($_SERVER['SERVER_NAME']) . sanitize_text_field( $_SERVER['REQUEST_URI'] ); //phpcs:ignore
			return $pageURL;
		}

		function arm_wp_head_redirect($arm_get_post_arr = array(), $arm_return_data=0) {
			global $wp, $wpdb, $current_user, $arm_lite_errors, $ARMemberLite, $arm_global_settings, $arm_subscription_plans, $arm_access_rules, $arm_case_types,  $arm_member_forms;

			$arm_multisite_restriction = '0';
			$this->arm_add_required_core_file();
			if ( current_user_can( 'administrator' ) ) {
				return;
			}
			$show_on_front = get_option( 'show_on_front' );
			$page_on_front = get_option( 'page_on_front' );
			if ( 'posts' == $show_on_front && is_home() ) {
				return;
			} elseif ( 'page' == $show_on_front && $page_on_front && is_page( $page_on_front ) ) {
				return;
			} else {

				$current_user_plan = $current_user->get( 'arm_user_plan_ids' );
				$current_user_plan = ( ! empty( $current_user_plan ) ) ? $current_user_plan : array( -2 );

				$suspended_plan_ids = get_user_meta( $current_user->ID, 'arm_user_suspended_plan_ids', true );
				$suspended_plan_ids = ( isset( $suspended_plan_ids ) && ! empty( $suspended_plan_ids ) ) ? $suspended_plan_ids : array();
				if ( ! empty( $current_user_plan ) && is_array( $current_user_plan ) ) {
					foreach ( $current_user_plan as $cp ) {
						if ( in_array( $cp, $suspended_plan_ids ) ) {
							unset( $current_user_plan[ array_search( $cp, $current_user_plan ) ] );
						}
					}
				}
				$current_user_plan = ( ! empty( $current_user_plan ) ) ? $current_user_plan : array( -2 );

				$arm_primary_status = arm_get_member_status( $current_user->ID );

				if ( $arm_primary_status == 3 ) {
					$current_user_plan = array();
				}

				$arm_default_redirection_settings = get_option( 'arm_redirection_settings' );
				$arm_default_redirection_settings = maybe_unserialize( $arm_default_redirection_settings );
				$access_rules_options             = $arm_default_redirection_settings['default_access_rules'];

				$page_settings = $arm_global_settings->arm_get_single_global_settings( 'page_settings' );
				/* Remove Member Directory Page */
				unset( $page_settings['member_profile_page_id'] );
				unset( $page_settings['thank_you_page_id'] );
				unset( $page_settings['cancel_payment_page_id'] );
				$page_settings    = array_filter( $page_settings );
				$arm_pages        = apply_filters( 'arm_restricted_site_access_allow_pages', $page_settings );
				$redirect_url     = ARMLITE_HOME_URL;
				$redirect_code    = 302;
				$redirect_page_id = 0;
				if ( ! is_user_logged_in() ) {

					if ( ! empty( $access_rules_options['non_logged_in'] ) ) {
						if ( $access_rules_options['non_logged_in']['type'] == 'specific' && ! empty( $access_rules_options['non_logged_in']['redirect_to'] ) ) {
							$redirect_page_id   = $access_rules_options['non_logged_in']['redirect_to'];
							$redirect_permalink = get_permalink( $access_rules_options['non_logged_in']['redirect_to'] );
							if ( ! empty( $redirect_permalink ) ) {
								$redirect_url = $redirect_permalink;
							}
						}
					}
				} elseif ( is_user_logged_in() ) {

					$user_id     = get_current_user_id();
					$user_status = arm_get_member_status( $user_id );

					if ( $user_status == 3 ) {

									$send_key_email = 0;
						if ( isset( $_GET['arm-key'] ) && ! empty( $_GET['arm-key'] ) ) {
							$chk_key     = stripslashes_deep( sanitize_text_field($_GET['arm-key']) ); //phpcs:ignore
							$user_email  = stripslashes_deep( sanitize_email($_GET['email']) ); //phpcs:ignore
							$arm_message = $arm_member_forms->arm_verify_user_activation_for_front( $user_email, $chk_key );

							if ( $arm_message['status'] == 'success' ) {
								return;
							} elseif ( $arm_message['status'] == 'error' ) {
								$send_key_email = 1;
							}
						}
						if ( ! empty( $access_rules_options['pending'] ) ) {

							$pending_red_type = isset( $access_rules_options['pending']['type'] ) ? $access_rules_options['pending']['type'] : 'home';
							$redirect_page_id = $pending_page_id = isset( $access_rules_options['pending']['redirect_to'] ) ? $access_rules_options['pending']['redirect_to'] : '';

							if ( $pending_red_type == 'specific' && ! empty( $pending_page_id ) ) {
								$redirect_permalink = get_permalink( $pending_page_id );
								if ( ! empty( $redirect_permalink ) ) {
									$current_page_url = get_permalink();
									if ( $redirect_permalink != $current_page_url ) {
										if ( $send_key_email == 1 ) {
											$redirect_permalink = $arm_global_settings->add_query_arg( 'arm-key', urlencode( sanitize_text_field($_GET['arm-key']) ), $redirect_permalink ); //phpcs:ignore
											$redirect_permalink = $arm_global_settings->add_query_arg( 'email', urlencode( sanitize_email($_GET['email']) ), $redirect_permalink ); //phpcs:ignore
										}
										$redirect_url = $redirect_permalink;
									}
								}
							} else {
								if ( ! is_front_page() ) {
									$home_page_url = ARMLITE_HOME_URL;
									if ( $send_key_email == 1 ) {
										$home_page_url = $arm_global_settings->add_query_arg( 'arm-key', urlencode( sanitize_text_field($_GET['arm-key']) ), $home_page_url ); //phpcs:ignore
										$home_page_url = $arm_global_settings->add_query_arg( 'email', urlencode( sanitize_email($_GET['email']) ), $home_page_url ); //phpcs:ignore
									}
									$redirect_url = $home_page_url;
								}
							}
						} else {
							if ( ! is_front_page() ) {
								$home_page_url = ARMLITE_HOME_URL;
								if ( $send_key_email == 1 ) {
									$home_page_url = $arm_global_settings->add_query_arg( 'arm-key', urlencode( sanitize_text_field($_GET['arm-key']) ), $home_page_url ); //phpcs:ignore
									$home_page_url = $arm_global_settings->add_query_arg( 'email', urlencode( sanitize_email($_GET['email']) ), $home_page_url ); //phpcs:ignore
								}
								$redirect_url = $home_page_url;
							}
						}
					} elseif ( ! empty( $access_rules_options['logged_in'] ) ) {

						if ( $access_rules_options['logged_in']['type'] == 'specific' && ! empty( $access_rules_options['logged_in']['redirect_to'] ) ) {
							$redirect_permalink = get_permalink( $access_rules_options['logged_in']['redirect_to'] );
							$redirect_page_id   = $access_rules_options['logged_in']['redirect_to'];
							if ( ! empty( $redirect_permalink ) ) {
								$redirect_url = $redirect_permalink;
							}
						}
					}
				}

				if(empty($arm_return_data))
				{
					$queried_object = get_queried_object();
				}
				else
				{
					$queried_object = $arm_get_post_arr;
				}

				if ( ! empty( $queried_object->ID ) && $redirect_page_id == $queried_object->ID ) {
					$redirect_url = ARMLITE_HOME_URL;
				}

				if ( ! function_exists( 'is_plugin_active' ) ) {
					include_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
					$request_uri = '';
					if ( isset( $queried_object->has_archive ) ) {
						$request_uri = $queried_object->has_archive;
					}

					if ( $request_uri != '' ) {
						$page    = get_page_by_path( $request_uri );
						$page_id = 0;
						if ( is_object( $page ) ) {
							$page_id = $page->ID;
						}

						if ( $page_id > 0 ) {
							$allowed   = true;
							$obj_plans = get_post_meta( $page_id, 'arm_access_plan' );

							$extraVars              = array(
								'current_user_id' => get_current_user_id(),
								'current_plan_id' => $current_user_plan,
							);
							$extraVars['post_type'] = 'page';
							$extraVars['post_id']   = $page_id;
							$redirect_url           = $arm_global_settings->add_query_arg( 'restricted', 'page', $redirect_url );

							$obj_plans = ! empty( $obj_plans ) ? $obj_plans : array();

							if ( count( $obj_plans ) == 0 ) {
								$obj_protection = 0;
							} else {
								$obj_protection = 1;
							}

							if ( ! empty( $obj_protection ) && $obj_protection == '1' ) {
								$allowed      = false;
								$return_array = array_intersect( $current_user_plan, $obj_plans );
								if ( ! empty( $obj_plans ) && ! empty( $return_array ) ) {
									$allowed = true;
								}
							}

							if ( $allowed == false ) {
								$arm_case_types['page']['protected'] = ( in_array( MEMBERSHIPLITE_DEBUG_LOG_TYPE, array( 'ARM_ALL', 'ARM_PAGE' ) ) ) ? true : false;
								$arm_case_types['page']['message']   = esc_html__( 'Page is protected', 'armember-membership' );
							}

							$allowed = apply_filters( 'arm_is_allow_access', $allowed, $extraVars );
							/* Redirect if user has no access */
							if ( ! $allowed ) {
								if ( MEMBERSHIPLITE_DEBUG_LOG == true ) {
									$ARMemberLite->arm_debug_response_log( 'arm_wp_head_redirect', $arm_case_types, (array) $queried_object, $wpdb->last_query );
								}
								do_action( 'arm_before_restricted_site_access_redirect', $redirect_url, $wp );
								$redirect_url  = apply_filters( 'arm_restricted_site_access_redirect_url', $redirect_url, $wp, $extraVars );
								$redirect_code = apply_filters( 'arm_restricted_site_access_head', $redirect_code, $wp );
								if(empty($arm_return_data))
								{
									wp_redirect( $redirect_url, $redirect_code );
									die;
								}
								else {
									return 2;
								}
							}
						}
					}
				}
				/* Allow user to access plugin pages. */
				if ( isset( $queried_object->post_type ) && $queried_object->post_type == 'page' ) {
					if ( in_array( $queried_object->ID, $arm_pages ) ) {

						return;
					}
				}

				$allowed   = true;
				$extraVars = array(
					'current_user_id' => get_current_user_id(),
					'current_plan_id' => $current_user_plan,
				);

				/* Check Special Pages Access. */
				$check_sp_access = $this->arm_current_special_page_access();
				if ( ! $check_sp_access ) {
					$allowed                                = false;
					$extraVars['special-page']              = true;
					$redirect_url                           = $arm_global_settings->add_query_arg( 'restricted', 'special-page', $redirect_url );
					$arm_case_types['special']['protected'] = ( in_array( MEMBERSHIPLITE_DEBUG_LOG_TYPE, array( 'ARM_ALL', 'ARM_SPECIAL_PAGE' ) ) ) ? true : false;
					/* wp_die(esc_html__("Sorry, You Don't have permission to access this page.", 'armember-membership')); */
				} elseif ( ! empty( $queried_object ) ) {

					if ( is_user_logged_in() ) {
						$user_id           = get_current_user_id();
						$current_user_plan = get_user_meta( $user_id, 'arm_user_plan_ids', true );
						$current_user_plan = ! empty( $current_user_plan ) ? $current_user_plan : array( -2 );

						$suspended_plan_ids = get_user_meta( $user_id, 'arm_user_suspended_plan_ids', true );
						$suspended_plan_ids = ( isset( $suspended_plan_ids ) && ! empty( $suspended_plan_ids ) ) ? $suspended_plan_ids : array();
						if ( ! empty( $current_user_plan ) && is_array( $current_user_plan ) ) {
							foreach ( $current_user_plan as $cp ) {
								if ( in_array( $cp, $suspended_plan_ids ) ) {
									unset( $current_user_plan[ array_search( $cp, $current_user_plan ) ] );
								}
							}
						}

						$current_user_plan = ! empty( $current_user_plan ) ? $current_user_plan : array( -2 );

						$arm_primary_status = arm_get_member_status( $user_id );

						if ( $arm_primary_status == 3 ) {
							$current_user_plan = array();
						}
					} else {
						$current_user_plan = array();
					}

					/* Set Param for hook */
					$extraVars['post_type'] = ( isset( $queried_object->post_type ) && ! empty( $queried_object->post_type ) ) ? $queried_object->post_type : '';
					$extraVars['taxonomy']  = ( isset( $queried_object->taxonomy ) && ! empty( $queried_object->taxonomy ) ) ? $queried_object->taxonomy : '';

					$obj_terms            = array();
					$extraVars['post_id'] = 0;
					if ( isset( $queried_object->post_type ) && ! empty( $queried_object->post_type ) ) {

						$extraVars['post_id'] = $queried_object->ID;
						$redirect_url         = $arm_global_settings->add_query_arg( 'restricted', $queried_object->post_type, $redirect_url );

						$obj_plans = get_post_meta( $queried_object->ID, 'arm_access_plan' );
						$obj_plans = ! empty( $obj_plans ) ? $obj_plans : array();
						if ( count( $obj_plans ) == 0 ) {
							$obj_protection = 0;
						} else {
							$obj_protection = 1;
						}
						if ( ! empty( $obj_protection ) && $obj_protection == 1 ) {

							$allowed      = false;
							$return_array = array_intersect( $current_user_plan, $obj_plans );

							if ( ! empty( $obj_plans ) && ! empty( $return_array ) ) {
								$allowed = true;
							}

							// for multisite
							if ( is_multisite() && $arm_multisite_restriction == 1 ) {
								$arm_current_blog_id  = get_current_blog_id();
								$arm_cur_user_blog_id = get_user_meta( $user_id, 'primary_blog', true );
								if ( $arm_current_blog_id != $arm_cur_user_blog_id && ! empty( $obj_plans ) ) {
									$allowed = false;
								}
							}
						}

						if ( $queried_object->post_type == 'page' && $allowed == false ) {
							$arm_case_types['page']['protected'] = ( in_array( MEMBERSHIPLITE_DEBUG_LOG_TYPE, array( 'ARM_ALL', 'ARM_PAGE' ) ) ) ? true : false;
							$arm_case_types['page']['message']   = esc_html__( 'Page is protected', 'armember-membership' );
						}
						if ( $queried_object->post_type == 'post' && $allowed == false ) {
							$arm_case_types['post']['protected'] = ( in_array( MEMBERSHIPLITE_DEBUG_LOG_TYPE, array( 'ARM_ALL', 'ARM_POST' ) ) ) ? true : false;
							$arm_case_types['post']['message']   = esc_html__( 'Post is protected', 'armember-membership' );
						}
						if ( $queried_object->post_type != '' && ! in_array( $extraVars['post_type'], array( 'post', 'page' ) ) && $allowed == false ) {
							$arm_case_types['custom']['protected'] = ( in_array( MEMBERSHIPLITE_DEBUG_LOG_TYPE, array( 'ARM_ALL', 'ARM_CUSTOM' ) ) ) ? true : false;
							$arm_case_types['custom']['message']   = esc_html__( 'Custom Post is protected', 'armember-membership' );
						}
						$obj_terms = $this->arm_get_post_taxonomy_terms( $queried_object->post_type, $queried_object->ID );
					}

					if ( isset( $queried_object->taxonomy ) && ! empty( $queried_object->taxonomy ) ) {
						$redirect_url = $arm_global_settings->add_query_arg( 'restricted', $queried_object->taxonomy, $redirect_url );
						$obj_terms    = $this->arm_get_term_with_parents( $queried_object->term_id, $queried_object->taxonomy );
					}/* END `Taxonomy Checking` */
					/**
					 * Check Patent taxonomy term access rules
					 */
					if ( $allowed && ! empty( $obj_terms ) ) {
						foreach ( $obj_terms as $term ) {

							if ( $allowed ) {
								$obj_protection = get_arm_term_meta( $term->term_id, 'arm_protection', true );
								$obj_plans      = get_arm_term_meta( $term->term_id, 'arm_access_plan' );
								$obj_plans      = ! empty( $obj_plans ) ? $obj_plans : array();

								if ( ! empty( $obj_protection ) && $obj_protection == '1' ) {
									$allowed         = false;
									$redirect_url    = $arm_global_settings->add_query_arg( 'restricted', $term->taxonomy, $redirect_url );
									$obj_plans_array = array_intersect( $current_user_plan, $obj_plans );
									if ( ! empty( $obj_plans ) && ! empty( $obj_plans_array ) ) {
										$allowed = true;
									}
									// for multisite
									if ( is_multisite() && $arm_multisite_restriction == 1 ) {
										$arm_current_blog_id  = get_current_blog_id();
										$arm_cur_user_blog_id = get_user_meta( $user_id, 'primary_blog', true );
										if ( $arm_current_blog_id != $arm_cur_user_blog_id && ! empty( $obj_plans ) ) {
											$allowed = false;
										}
									}
								}
								if ( $allowed == false ) {
									$arm_case_types['taxonomy']['protected'] = ( in_array( MEMBERSHIPLITE_DEBUG_LOG_TYPE, array( 'ARM_ALL', 'ARM_TAXONOMY' ) ) ) ? true : false;
									$arm_case_types['taxonomy']['message']   = esc_html__( 'Taxonomy is protected', 'armember-membership' );
								}
							}
						}
					}
				}/* END `!empty($queried_object)` */

				$allowed = apply_filters( 'arm_is_allow_access', $allowed, $extraVars );

				/* Redirect if user has no access */
				if ( ! $allowed ) {
					$ARMemberLite->arm_session_start();
					if ( MEMBERSHIPLITE_DEBUG_LOG == true ) {
						$ARMemberLite->arm_debug_response_log( 'arm_wp_head_redirect', $arm_case_types, (array) $queried_object, $wpdb->last_query );
					}
					do_action( 'arm_before_restricted_site_access_redirect', $redirect_url, $wp );
					$redirect_url  = apply_filters( 'arm_restricted_site_access_redirect_url', $redirect_url, $wp, $extraVars );
					$redirect_code = apply_filters( 'arm_restricted_site_access_head', $redirect_code, $wp );

					if ( ! isset( $_SESSION['arm_restricted_page_url'] ) ) {
						$_SESSION['arm_restricted_page_url'] = $this->curPageURL();
					}
						if(empty($arm_return_data))
						{
							wp_redirect( $redirect_url, $redirect_code );
							die;
						}
						else {
							return 2;
						}

				}
			}/* END `!is_home()` */
		}

		function arm_get_post_taxonomy_terms( $post_type = 'post', $post_id = 0 ) {
			global $wp, $wpdb, $current_user, $ARMemberLite, $arm_global_settings, $arm_subscription_plans, $arm_access_rules;

			$this->arm_add_required_core_file();

			$terms = array();
			if ( ! empty( $post_type ) && ! empty( $post_id ) && $post_id != 0 ) {
				$taxo_args  = array(
					'show_ui' => true,
					'public'  => true,
				);
				$taxonomies = get_taxonomies( $taxo_args, 'object' );
				$post_terms = array();
				if ( ! empty( $taxonomies ) ) {
					foreach ( $taxonomies as $tax ) {
						$post_terms = $this->arm_get_object_terms( $post_id, $tax->name );
						foreach ( $post_terms as $term ) {
							$terms += $this->arm_get_term_with_parents( $term->term_id, $term->taxonomy );
						}
					}
				}
			}
			return $terms;
		}

		function arm_get_term_with_parents( $term_id = 0, $taxonomy = '' ) {
			global $wp, $wpdb, $current_user, $ARMemberLite, $arm_global_settings, $arm_subscription_plans, $arm_access_rules;
			$terms = array();
			$term  = get_term( $term_id, $taxonomy );
			if ( ! empty( $term ) ) {
				$terms[ $term_id ] = $term;
				if ( ! empty( $term->parent ) && $term->parent != 0 ) {
					$terms += $this->arm_get_term_with_parents( $term->parent, $taxonomy );
				}
			}
			return $terms;
		}

		function arm_posts_meta_rules() {
			global $wp, $wpdb, $current_user, $arm_lite_errors, $ARMemberLite;

			$this->arm_add_required_core_file();

			/* Check Logged In User Has Access */
			$post_meta_rules = $post_drip_rules = $deny_terms = array();
			$denied_terms    = array();
			if ( ! is_admin() && ! current_user_can( 'administrator' ) ) {
				$all_blocked_terms = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM `' . $ARMemberLite->tbl_arm_termmeta . '` WHERE `meta_key` = %s AND `meta_value` = %d', 'arm_protection', 1 ) );//phpcs:ignore --Reason $ARMemberLite->tbl_arm_termmeta is a table name
				// during query monitor
				$arm_blocked_terms = array();
				if ( $all_blocked_terms ) {
					foreach ( $all_blocked_terms as $blocked_term ) {
						$arm_blocked_terms[] = $blocked_term->arm_term_id;
					}
				}

				if ( ! empty( $arm_blocked_terms ) ) {
					$arm_blocked_term_ids = implode( ',', $arm_blocked_terms );

					$where =' WHERE ';
					$admin_placeholders = ' t.term_id IN (';
					$admin_placeholders .= rtrim( str_repeat( '%s,', count( $arm_blocked_terms ) ), ',' );
					$admin_placeholders .= ') ';
					array_unshift( $arm_blocked_terms, $admin_placeholders );

					$where .= call_user_func_array(array( $wpdb, 'prepare' ), $arm_blocked_terms );//phpcs:ignore 

					$arm_terms         = $wpdb->terms;
					$arm_term_taxonomy = $wpdb->term_taxonomy;
					$all_blocked_terms = $wpdb->get_results( 'SELECT t.*, tt.* FROM `' . $arm_terms . '` AS t INNER JOIN `' . $arm_term_taxonomy . '` AS tt ON t.term_id = tt.term_id '.$where );//phpcs:ignore --Reason $arm_terms and $arm_term_taxonomy those are tables
				}
				if ( ! is_user_logged_in() ) {

					if ( count( $all_blocked_terms ) > 0 ) {
						foreach ( $all_blocked_terms as $blocked_terms ) {

							if ( isset( $blocked_terms->taxonomy ) && taxonomy_exists( $blocked_terms->taxonomy ) ) {
								$denied_terms[ $blocked_terms->taxonomy ][] = $blocked_terms->term_id;
								$term_children                              = get_term_children( $blocked_terms->term_id, $blocked_terms->taxonomy );
								if ( ! empty( $term_children ) && ! is_wp_error( $term_children ) ) {
									$denied_terms[ $blocked_terms->taxonomy ] = array_merge( $denied_terms[ $blocked_terms->taxonomy ], $term_children );
								}
							}
						}
					}
				} else {
					$user_id    = $current_user->ID;
					$user_plans = get_user_meta( $user_id, 'arm_user_plan_ids', true );
					$user_plans = ! empty( $user_plans ) ? $user_plans : array( -2 );

					$suspended_plan_ids = get_user_meta( $user_id, 'arm_user_suspended_plan_ids', true );
					$suspended_plan_ids = ( isset( $suspended_plan_ids ) && ! empty( $suspended_plan_ids ) ) ? $suspended_plan_ids : array();
					if ( ! empty( $user_plans ) && is_array( $user_plans ) ) {
						foreach ( $user_plans as $cp ) {
							if ( in_array( $cp, $suspended_plan_ids ) ) {
								unset( $user_plans[ array_search( $cp, $user_plans ) ] );
							}
						}
					}

					$user_plans = ! empty( $user_plans ) ? $user_plans : array( -2 );

					$arm_primary_status = arm_get_member_status( $user_id );

					if ( $arm_primary_status == 3 ) {
						$user_plans = array();
					}
					if ( count( $all_blocked_terms ) > 0 ) {
						// during query monitor
						$arm_term_meta_tbl     = $ARMemberLite->tbl_arm_termmeta;
						$arm_term_plan_ids     = array();
						$admin_placeholders = 'AND arm_term_id IN (';
						$admin_placeholders .= rtrim( str_repeat( '%s,', count( $arm_blocked_terms ) ), ',' );
						$admin_placeholders .= ')';
						array_unshift( $arm_blocked_terms, $admin_placeholders );
						$where = $wpdb->prepare("WHERE meta_key = %s",'arm_access_plan');
						$where .= call_user_func_array(array( $wpdb, 'prepare' ), $arm_blocked_terms );

						$arm_term_access_plans = $wpdb->get_results( 'SELECT `arm_term_id`, `meta_key`, `meta_value` FROM ' . $arm_term_meta_tbl . " ".$where." ORDER BY arm_term_id ASC" );//phpcs:ignore --Reason $arm_term_meta_tbl is a table name
						if ( $arm_term_access_plans ) {
							foreach ( $arm_term_access_plans as $arm_term_access_plan ) {
								$arm_term_plan_ids[ $arm_term_access_plan->arm_term_id ][] = $arm_term_access_plan->meta_value;
							}
						}

						foreach ( $all_blocked_terms as $blocked_terms ) {
							$blocked_term_id = $blocked_terms->term_id;
							$termmeta        = isset( $arm_term_plan_ids[ $blocked_term_id ] ) ? $arm_term_plan_ids[ $blocked_term_id ] : array();
							$termmeta_array  = array_intersect( $user_plans, $termmeta );
							if ( empty( $termmeta_array ) ) {
								if ( isset( $blocked_terms->taxonomy ) && taxonomy_exists( $blocked_terms->taxonomy ) ) {
									$denied_terms[ $blocked_terms->taxonomy ][] = $blocked_terms->term_id;
									$term_children                              = get_term_children( $blocked_terms->term_id, $blocked_terms->taxonomy );
									if ( ! empty( $term_children ) && ! is_wp_error( $term_children ) ) {
										$denied_terms[ $blocked_terms->taxonomy ] = array_merge( $denied_terms[ $blocked_terms->taxonomy ], $term_children );
									}
								}
							}
						}
					}
				}
			}
			$current_user->post_meta_rules = $post_meta_rules;
			$current_user->post_drip_rules = $post_drip_rules;
			$current_user->deny_term       = $denied_terms;
			return $post_meta_rules;
		}

		function arm_get_object_terms( $object_ids, $taxonomies, $args = array() ) {

			global $wpdb;

			if ( empty( $object_ids ) || empty( $taxonomies ) ) {
				return array();
			}

			if ( ! is_array( $taxonomies ) ) {
				$taxonomies = array( $taxonomies );
			}

			foreach ( $taxonomies as $taxonomy ) {
				if ( ! taxonomy_exists( $taxonomy ) ) {
					return new WP_Error( 'invalid_taxonomy', esc_html__( 'Invalid taxonomy.', 'armember-membership' ) );
				}
			}

			if ( ! is_array( $object_ids ) ) {
				$object_ids = array( $object_ids );
			}
			$object_ids = array_map( 'intval', $object_ids );

			$defaults = array(
				'orderby'                => 'name',
				'order'                  => 'ASC',
				'fields'                 => 'all',
				'parent'                 => '',
				'update_term_meta_cache' => true,
				'meta_query'             => '',
			);
			$args     = wp_parse_args( $args, $defaults );

			$terms = array();
			if ( count( $taxonomies ) > 1 ) {
				foreach ( $taxonomies as $index => $taxonomy ) {
					$t = get_taxonomy( $taxonomy );
					if ( isset( $t->args ) && is_array( $t->args ) && $args != array_merge( $args, $t->args ) ) {
						unset( $taxonomies[ $index ] );
						$terms = array_merge( $terms, wp_get_object_terms( $object_ids, $taxonomy, array_merge( $args, $t->args ) ) );
					}
				}
			} else {
				$t = get_taxonomy( $taxonomies[0] );
				if ( isset( $t->args ) && is_array( $t->args ) ) {
					$args = array_merge( $args, $t->args );
				}
			}

			$orderby = $args['orderby'];
			$order   = $args['order'];
			$fields  = $args['fields'];

			if ( in_array( $orderby, array( 'term_id', 'name', 'slug', 'term_group' ) ) ) {
				$orderby = "t.$orderby";
			} elseif ( in_array( $orderby, array( 'count', 'parent', 'taxonomy', 'term_taxonomy_id' ) ) ) {
				$orderby = "tt.$orderby";
			} elseif ( 'term_order' === $orderby ) {
				$orderby = 'tr.term_order';
			} elseif ( 'none' === $orderby ) {
				$orderby = '';
				$order   = '';
			} else {
				$orderby = 't.term_id';
			}

			// tt_ids queries can only be none or tr.term_taxonomy_id
			if ( ( 'tt_ids' == $fields ) && ! empty( $orderby ) ) {
				$orderby = 'tr.term_taxonomy_id';
			}

			if ( ! empty( $orderby ) ) {
				$orderby = "ORDER BY $orderby";
			}

			$order = strtoupper( $order );
			if ( '' !== $order && ! in_array( $order, array( 'ASC', 'DESC' ) ) ) {
				$order = 'ASC';
			}

			$taxonomy_array  = array_map( 'esc_sql', $taxonomies );
			$object_id_array = $object_ids;
			// $taxonomies      = "'" . implode( "', '", array_map( 'esc_sql', $taxonomies ) ) . "'";
			// $object_ids      = implode( ', ', $object_ids );

			$select_this = '';
			if ( 'all' == $fields ) {
				$select_this = 't.*, tt.*';
			} elseif ( 'ids' == $fields ) {
				$select_this = 't.term_id';
			} elseif ( 'names' == $fields ) {
				$select_this = 't.name';
			} elseif ( 'slugs' == $fields ) {
				$select_this = 't.slug';
			} elseif ( 'all_with_object_id' == $fields ) {
				$select_this = 't.*, tt.*, tr.object_id';
			}

			/*$where = array(
				"tt.taxonomy IN ($taxonomies)",
				"tr.object_id IN ($object_ids)",
			);
			*/
			$admin_placeholders = 'tt.taxonomy IN (';
			$admin_placeholders .= rtrim( str_repeat( '%s,', count( $taxonomy_array ) ), ',' );
			$admin_placeholders .= ')';
			array_unshift( $taxonomy_array, $admin_placeholders );
			$where = array();
			$where[] = call_user_func_array(array( $wpdb, 'prepare' ), $taxonomy_array );

			$admin_placeholders = 'tr.object_id IN (';
			$admin_placeholders .= rtrim( str_repeat( '%s,', count( $object_id_array ) ), ',' );
			$admin_placeholders .= ')';
			array_unshift( $object_id_array, $admin_placeholders );
			
			$where[] = call_user_func_array(array( $wpdb, 'prepare' ), $object_id_array );

			if ( '' !== $args['parent'] ) {
				$where[] = $wpdb->prepare( 'tt.parent = %d', $args['parent'] );
			}

			// Meta query support.
			$meta_query_join = '';
			if ( ! empty( $args['meta_query'] ) ) {
				$mquery = new WP_Meta_Query( $args['meta_query'] );
				$mq_sql = $mquery->get_sql( 'term', 't', 'term_id' );

				$meta_query_join .= $mq_sql['join'];

				// Strip leading AND.
				$where[] = preg_replace( '/^\s*AND/', '', $mq_sql['where'] );
			}

			$where = implode( ' AND ', $where );

			$query = "SELECT $select_this FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON tt.term_id = t.term_id INNER JOIN $wpdb->term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id $meta_query_join WHERE $where $orderby $order";

			$objects = false;
			if ( 'all' == $fields || 'all_with_object_id' == $fields ) {
				$_terms          = $wpdb->get_results( $query ); //phpcs:ignore --Reason $wpdb->terms is a table name
				$object_id_index = array();
				foreach ( $_terms as $key => $term ) {
					$term           = sanitize_term( $term, $taxonomy, 'raw' );
					$_terms[ $key ] = $term;

					if ( isset( $term->object_id ) ) {
						$object_id_index[ $key ] = $term->object_id;
					}
				}

				update_term_cache( $_terms );
				$_terms = array_map( 'get_term', $_terms );

				// Re-add the object_id data, which is lost when fetching terms from cache.
				if ( 'all_with_object_id' === $fields ) {
					foreach ( $_terms as $key => $_term ) {
						if ( isset( $object_id_index[ $key ] ) ) {
							$_term->object_id = $object_id_index[ $key ];
						}
					}
				}

				$terms   = array_merge( $terms, $_terms );
				$objects = true;
			} elseif ( 'ids' == $fields || 'names' == $fields || 'slugs' == $fields ) {
				$_terms = $wpdb->get_col( $query );//phpcs:ignore --Reason: $wpdb->terms is table name
				$_field = ( 'ids' == $fields ) ? 'term_id' : 'name';
				foreach ( $_terms as $key => $term ) {
					$_terms[ $key ] = sanitize_term_field( $_field, $term, $term, $taxonomy, 'raw' );
				}
				$terms = array_merge( $terms, $_terms );
			} elseif ( 'tt_ids' == $fields ) {
				$terms = $wpdb->get_col( $wpdb->prepare("SELECT tr.term_taxonomy_id FROM $wpdb->term_relationships AS tr INNER JOIN $wpdb->term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE $where $orderby $order") );//phpcs:ignore --Reason: $wpdb->term is a table name
				foreach ( $terms as $key => $tt_id ) {
					$terms[ $key ] = sanitize_term_field( 'term_taxonomy_id', $tt_id, 0, $taxonomy, 'raw' ); // 0 should be the term id, however is not needed when using raw context.
				}
			}

			// Update termmeta cache, if necessary.
			if ( $args['update_term_meta_cache'] && ( 'all' === $fields || 'all_with_object_id' === $fields || 'ids' === $fields ) ) {
				if ( 'ids' === $fields ) {
					$term_ids = $terms;
				} else {
					$term_ids = wp_list_pluck( $terms, 'term_id' );
				}

				if ( ! function_exists( 'update_termmeta_cache' ) ) {
					include ABSPATH . 'wp-includes/taxonomy.php';
				}

				if ( function_exists( 'update_termmeta_cache' ) ) {
					update_termmeta_cache( $term_ids );
				}
			}

			if ( ! $terms ) {
				$terms = array();
			} elseif ( $objects && 'all_with_object_id' !== $fields ) {
				$_tt_ids = array();
				$_terms  = array();
				foreach ( $terms as $term ) {
					if ( in_array( $term->term_taxonomy_id, $_tt_ids ) ) {
						continue;
					}

					$_tt_ids[] = $term->term_taxonomy_id;
					$_terms[]  = $term;
				}
				$terms = $_terms;
			} elseif ( ! $objects ) {
				$terms = array_values( array_unique( $terms ) );
			}

			/**
			 * Filters the terms for a given object or objects.
			 *
			 * @since 4.2.0
			 *
			 * @param array $terms           An array of terms for the given object or objects.
			 * @param array $object_id_array Array of object IDs for which `$terms` were retrieved.
			 * @param array $taxonomy_array  Array of taxonomies from which `$terms` were retrieved.
			 * @param array $args            An array of arguments for retrieving terms for the given
			 *                               object(s). See wp_get_object_terms() for details.
			 */
			$terms = apply_filters( 'get_object_terms', $terms, $object_id_array, $taxonomy_array, $args );

			/**
			 * Filters the terms for a given object or objects.
			 *
			 * The `$taxonomies` parameter passed to this filter is formatted as a SQL fragment. The
			 * {@see 'get_object_terms'} filter is recommended as an alternative.
			 *
			 * @since 2.8.0
			 *
			 * @param array     $terms      An array of terms for the given object or objects.
			 * @param int|array $object_ids Object ID or array of IDs.
			 * @param string    $taxonomies SQL-formatted (comma-separated and quoted) list of taxonomy names.
			 * @param array     $args       An array of arguments for retrieving terms for the given object(s).
			 *                              See wp_get_object_terms() for details.
			 */
			return apply_filters( 'wp_get_object_terms', $terms, $object_ids, $taxonomies, $args );
		}

		/**
		 * Remove restricted posts from THE-LOOP
		 */
		function arm_pre_get_posts( $query ) {
			global $wp, $wpdb, $current_user, $arm_lite_errors, $ARMemberLite, $arm_lite_is_access_rule_applied, $arm_access_rules;
			$this->arm_add_required_core_file();

			$arm_default_access_rules = $arm_access_rules->arm_get_default_access_rules();

			if ( isset( $query ) && ! empty( $query ) && $query != null ) {
				if ( ! is_admin() && ! $query->is_singular() && ! current_user_can( 'administrator' ) ) {
					if ( $query->get( 'post_type' ) != 'nav_menu_item' && $query->get( 'post_type' ) != 'page' && $query->get( 'post_type' ) != 'attachment' ) {
						$post_meta_rules = $current_user->post_meta_rules;
						if ( isset( $post_meta_rules['meta_key'] ) ) {
							$query->set( 'meta_key', $post_meta_rules['meta_key'] );
							$query->set( 'meta_value', $post_meta_rules['meta_value'] );
							$query->set( 'meta_compare', $post_meta_rules['meta_compare'] );
						}
						/* For Restricted Taxonomy Terms */
						if ( $query->get( 'post_type' ) != 'page' && ! empty( $current_user->deny_term ) ) {
							$tax_query = array( 'relation' => 'AND' );
							foreach ( $current_user->deny_term as $taxonomy => $terms ) {
								$tax_query[] = array(
									'taxonomy' => $taxonomy,
									'field'    => 'id',
									'terms'    => $terms,
									'operator' => 'NOT IN',
								);
							}
							$query->tax_query->queries[]    = $tax_query;
							$query->query_vars['tax_query'] = $query->tax_query->queries;
						}
					}
				}
				if ( MEMBERSHIPLITE_DEBUG_LOG == true ) {
					if ( MEMBERSHIPLITE_DEBUG_LOG_TYPE == 'ARM_ALL' || MEMBERSHIPLITE_DEBUG_LOG_TYPE == 'ARM_SPECIAL_PAGE' ) {
						$ARMemberLite->arm_debug_response_log( 'arm_pre_get_posts', array(), $query, $wpdb->last_query );
					}
				}
			}
		}

		/**
		 * Remove restricted taxonomies from listing
		 */
		function arm_get_terms_args( $args, $taxonomies ) {
			global $wp, $wpdb, $current_user, $arm_lite_errors, $ARMemberLite, $arm_access_rules;
			$this->arm_add_required_core_file();
			$arm_default_access_rules = $arm_access_rules->arm_get_default_access_rules();

			if ( ! is_admin() && ! current_user_can( 'administrator' ) ) {
				if ( ! empty( $current_user->deny_term ) ) {
					$hide_terms = array();
					foreach ( $current_user->deny_term as $term ) {
						$hide_terms = array_merge( $hide_terms, $term );
					}

					$excluded_array = ( isset( $args['exclude'] ) && ! empty( $args['exclude'] ) ) ? $args['exclude'] : array();

					if ( ! empty( $hide_terms ) ) {
						if ( ! is_array( $args['slug'] ) && ! empty( $args['taxonomy'] ) ) {
							$arm_args_texonomies = $args['taxonomy'];
							if ( is_array( $arm_args_texonomies ) && count( $arm_args_texonomies ) > 0 ) {
								foreach ( $arm_args_texonomies as $arm_args_texonomy ) {

									$get_current_page_term_id = $wpdb->get_row($wpdb->prepare('SELECT arm_t.term_id FROM `' . $wpdb->terms . '` as arm_t LEFT JOIN `' . $wpdb->term_taxonomy . '` as arm_tt ON arm_t.term_id=arm_tt.term_id WHERE slug=%s AND taxonomy =%s',$args['slug'],$arm_args_texonomy), ARRAY_A ); //phpcs:ignore --Reason $wpdb->term_taxonomy is a table name

									if ( ! empty( $get_current_page_term_id ) ) {
										if ( ( $arm_check_cat_key = array_search( $get_current_page_term_id['term_id'], $hide_terms ) ) !== false ) {

											if ( MEMBERSHIPLITE_DEBUG_LOG == true ) {
												if ( MEMBERSHIPLITE_DEBUG_LOG_TYPE == 'ARM_ALL' || MEMBERSHIPLITE_DEBUG_LOG_TYPE == 'ARM_ADMIN_PANEL' ) {
													$arm_case_types['admin_panel']['protected'] = true;
													$arm_case_types['admin_panel']['message']   = esc_html__( 'category is restricted by admin', 'armember-membership' );
													$ARMemberLite->arm_debug_response_log( 'arm_get_terms_args', $arm_case_types, $args, $wpdb->last_query );
												}
											}

											unset( $hide_terms[ $arm_check_cat_key ] );
										}
									}
								}
							}
						}
					}

					if ( is_array( $excluded_array ) ) {
						$args['exclude'] = array_merge( $excluded_array, $hide_terms );
					} else {
						if ( empty( $excluded_array ) ) {
							$excluded_array  = array();
							$args['exclude'] = array_merge( $excluded_array, $hide_terms );
						} else {
							$exploded_excluded_terms     = explode( ',', $excluded_array );
							$exploded_new_excluded_terms = array_merge( $exploded_excluded_terms, $hide_terms );
							$args['exclude']             = implode( ',', $exploded_new_excluded_terms );
						}
					}
				}
			}
			if ( MEMBERSHIPLITE_DEBUG_LOG == true ) {
				if ( MEMBERSHIPLITE_DEBUG_LOG_TYPE == 'ARM_ALL' || MEMBERSHIPLITE_DEBUG_LOG_TYPE == 'ARM_TAXONOMY' ) {
					$ARMemberLite->arm_debug_response_log( 'arm_get_terms_args', array(), $args, $wpdb->last_query );
				}
			}
			return $args;
		}

		/**
		 * Remove restricted navigation menus
		 */
		function arm_wp_get_nav_menu_items( $items, $menu, $args ) {
			global $arm_nav_menu, $ARMemberLite;
			$arm_nav_menu = $items;
			return $items;
		}

		function arm_wp_nav_menu_items( $items, $args ) {
			global $wp, $wpdb, $current_user, $arm_lite_errors, $ARMemberLite, $arm_modal_view_in_menu, $arm_access_rules, $arm_nav_menu;
			$this->arm_add_required_core_file();
			$items                    = $arm_nav_menu;
			$arm_default_access_rules = $arm_access_rules->arm_get_default_access_rules();

			if ( ! is_admin() && ! current_user_can( 'administrator' ) ) {
				if ( is_user_logged_in() ) {
					$current_user_plan = get_user_meta( $current_user->ID, 'arm_user_plan_ids', true );
					$current_user_plan = ! empty( $current_user_plan ) ? $current_user_plan : array( -2 );

					$suspended_plan_ids = get_user_meta( $current_user->ID, 'arm_user_suspended_plan_ids', true );
					$suspended_plan_ids = ( isset( $suspended_plan_ids ) && ! empty( $suspended_plan_ids ) ) ? $suspended_plan_ids : array();
					if ( ! empty( $current_user_plan ) && is_array( $current_user_plan ) ) {
						foreach ( $current_user_plan as $cp ) {
							if ( in_array( $cp, $suspended_plan_ids ) ) {
								unset( $current_user_plan[ array_search( $cp, $current_user_plan ) ] );
							}
						}
					}

					$current_user_plan = ! empty( $current_user_plan ) ? $current_user_plan : array( -2 );
				} else {
					$current_user_plan = array();
				}

				$arm_primary_status = arm_get_member_status( $current_user->ID );

				if ( $arm_primary_status == 3 ) {
					$current_user_plan = array();
				}

				$protected_nav_menu = array();
				foreach ( $items as $k => $item ) {
					$item_plans = get_post_meta( $item->ID, 'arm_access_plan' );
					$item_plans = ! empty( $item_plans ) ? $item_plans : array();

					$item_protection = 0;
					if ( count( $item_plans ) == 0 ) {
						$item_protection = 0;
					} else {
						$item_protection = 1;
					}

					if ( $item_protection == 1 ) {
						/* Check Logged In User Has Access */
						$item_plans_array = array_intersect( $current_user_plan, $item_plans );
						if ( empty( $item_plans_array ) ) {
							$protected_nav_menu[ $k ] = $item;
							unset( $items[ $k ] );
						}
					}
				}
				/* Restrict Sub menu */
				foreach ( $protected_nav_menu as $k => $item ) {
					$item_plans = get_post_meta( $item->ID, 'arm_access_plan' );
					$item_plans = ! empty( $item_plans ) ? $item_plans : array();

					$item_protection = 0;
					if ( count( $item_plans ) == 0 ) {
						$item_protection = 0;
					} else {
						$item_protection = 1;
					}

					$children = $arm_modal_view_in_menu->get_nav_menu_item_children( $item->ID, $items, true );
					if ( ! empty( $children ) ) {
						foreach ( $children as $key => $child_nav ) {
							$child_id   = $child_nav->ID;
							$indexChild = $this->arm_get_child_navigation_index_from_menu( $items, $child_id );
							unset( $items[ $indexChild ] );
						}
					}
				}
			}
			return $items;
		}

		function arm_get_child_navigation_index_from_menu( $menu, $child_id ) {
			if ( ! empty( $menu ) ) {
				foreach ( $menu as $index => $menu_item ) {
					if ( $menu_item->ID == $child_id ) {
						return $index;
					}
				}
			}
			return false;
		}

		/**
		 * Remove restricted posts from widgets
		 */
		function arm_widget_posts_args( $args ) {
			global $wp, $wpdb, $current_user, $arm_lite_errors, $ARMemberLite, $arm_drip_rules, $arm_access_rules;
			$this->arm_add_required_core_file();
			$arm_default_access_rules = $arm_access_rules->arm_get_default_access_rules();

			if ( ! is_admin() && ! current_user_can( 'administrator' ) ) {

				$restrict_posts = array();
				$result_pages   = array();

				if ( is_user_logged_in() ) {

					$current_user_plan = get_user_meta( $current_user->ID, 'arm_user_plan_ids', true );
					$current_user_plan = ! empty( $current_user_plan ) ? $current_user_plan : array( -2 );

					$suspended_plan_ids = get_user_meta( $current_user->ID, 'arm_user_suspended_plan_ids', true );
					$suspended_plan_ids = ( isset( $suspended_plan_ids ) && ! empty( $suspended_plan_ids ) ) ? $suspended_plan_ids : array();
					if ( ! empty( $current_user_plan ) && is_array( $current_user_plan ) ) {
						foreach ( $current_user_plan as $cp ) {
							if ( in_array( $cp, $suspended_plan_ids ) ) {
								unset( $current_user_plan[ array_search( $cp, $current_user_plan ) ] );
							}
						}
					}

					$current_user_plan = ! empty( $current_user_plan ) ? $current_user_plan : array( -2 );

					$arm_primary_status = arm_get_member_status( $current_user->ID );

					if ( $arm_primary_status == 3 ) {
						$current_user_plan = array( -5 );
					}
				} else {
					$current_user_plan = array();
				}

				/*
				$rargs = array(
				'post_type' => 'any',
					'meta_key' => 'arm_access_plan',
					'meta_value' => '0',
					'post_status' => 'publish',
					'posts_per_page' => -1
				);*/

				// $result_pages = get_posts($rargs);
				
				$result_pages     = $wpdb->get_results( $wpdb->prepare("SELECT ID from $wpdb->posts arm_wp LEFT JOIN $wpdb->postmeta AS arm_wpm ON arm_wp.ID = arm_wpm.post_id WHERE arm_wpm.meta_key = %s AND arm_wpm.meta_value = %s AND arm_wp.post_status=%s ORDER BY arm_wp.ID DESC",'arm_access_plan',0,'publish') );//phpcs:ignore --Reason $wpdb->posts is a table name

				if ( ! empty( $result_pages ) ) {
					foreach ( $result_pages as $rp ) {
						$obj_plans       = get_post_meta( $rp->ID, 'arm_access_plan' );
						$obj_plans       = ! empty( $obj_plans ) ? $obj_plans : array();
						$obj_plans_array = array_intersect( $current_user_plan, $obj_plans );
						if ( empty( $obj_plans_array ) ) {
							$restrict_posts[] = $rp->ID;
						}
					}
				}

				$args['post__not_in'] = $restrict_posts;
			}

			return $args;
		}

		/**
		 * Remove restricted pages from widgets
		 */
		function arm_widget_pages_args( $args ) {
			global $wp, $wpdb, $current_user, $arm_lite_errors, $ARMemberLite, $arm_access_rules, $arm_drip_rules;
			$this->arm_add_required_core_file();
			$arm_default_access_rules = $arm_access_rules->arm_get_default_access_rules();

			if ( ! is_admin() && ! current_user_can( 'administrator' ) ) {
				$restrict_pages = array();
				$result_pages   = array();
				if ( is_user_logged_in() ) {
					$current_user_plan = get_user_meta( $current_user->ID, 'arm_user_plan_ids', true );
					$current_user_plan = ! empty( $current_user_plan ) ? $current_user_plan : array( -2 );

					$suspended_plan_ids = get_user_meta( $current_user->ID, 'arm_user_suspended_plan_ids', true );
					$suspended_plan_ids = ( isset( $suspended_plan_ids ) && ! empty( $suspended_plan_ids ) ) ? $suspended_plan_ids : array();
					if ( ! empty( $current_user_plan ) && is_array( $current_user_plan ) ) {
						foreach ( $current_user_plan as $cp ) {
							if ( in_array( $cp, $suspended_plan_ids ) ) {
								unset( $current_user_plan[ array_search( $cp, $current_user_plan ) ] );
							}
						}
					}

					$current_user_plan = ! empty( $current_user_plan ) ? $current_user_plan : array( -2 );

					$arm_primary_status = arm_get_member_status( $current_user->ID );

					if ( $arm_primary_status == 3 ) {
						$current_user_plan = array( -5 );
					}
				} else {
					$current_user_plan = array();
				}

				$rargs = array(
					'post_type'  => 'page',
					'meta_key'   => 'arm_access_plan',
					'meta_value' => '0',
				);

				$result_pages = get_pages( $rargs );
				if ( ! empty( $result_pages ) ) {
					foreach ( $result_pages as $rp ) {
						$obj_plans       = get_post_meta( $rp->ID, 'arm_access_plan' );
						$obj_plans       = ! empty( $obj_plans ) ? $obj_plans : array();
						$obj_plans_array = array_intersect( $current_user_plan, $obj_plans );
						if ( empty( $obj_plans_array ) ) {
							$restrict_pages[] = $rp->ID;
						}
					}
				}

				$args['exclude'] = implode( ',', $restrict_pages );
			}

			return $args;
		}

		function arm_current_special_page_access() {
			global $wp, $wpdb, $current_user, $ARMemberLite, $arm_global_settings, $arm_subscription_plans, $arm_access_rules;
			$this->arm_add_required_core_file();
			if ( is_user_logged_in() ) {
				$current_user_plan = get_user_meta( $current_user->ID, 'arm_user_plan_ids', true );
				$current_user_plan = ! empty( $current_user_plan ) ? $current_user_plan : array( -2 );

				$suspended_plan_ids = get_user_meta( $current_user->ID, 'arm_user_suspended_plan_ids', true );
				$suspended_plan_ids = ( isset( $suspended_plan_ids ) && ! empty( $suspended_plan_ids ) ) ? $suspended_plan_ids : array();

				if ( ! empty( $current_user_plan ) && is_array( $current_user_plan ) ) {
					foreach ( $current_user_plan as $cp ) {
						if ( in_array( $cp, $suspended_plan_ids ) ) {
							unset( $current_user_plan[ array_search( $cp, $current_user_plan ) ] );
						}
					}
				}

				$current_user_plan = ! empty( $current_user_plan ) ? $current_user_plan : array( -2 );

				$arm_primary_status = arm_get_member_status( $current_user->ID );

				if ( $arm_primary_status == 3 ) {
					$current_user_plan = array();
				}
			} else {
				$current_user_plan = array();
			}

			$sp_access    = $arm_access_rules->arm_get_custom_access_rules( 'special_pages' );
			$current_page = array();
			if ( ! empty( $sp_access ) ) {
				$sp_slugs        = array(
					'home',
					'notfound',
					'search',
					'attachment',
					'single',
					'archive',
					'author',
					'date',
					'year',
					'month',
					'day',
					'time',
				);
				$page_status     = false;
				$page_status_new = false;
				foreach ( $sp_slugs as $sp_key ) {
					/*
					 * The item order is critical, in case a page has multiple flags
					 * like "Front" and "Home" and "Archive".
					 * In this example "Archive" might be denied but "Front" allowed,
					 * so we have to define a hierarchy which flag is actually used.
					 */
					switch ( $sp_key ) {
						case 'home':
							$page_status = is_home();
							break;
						case 'notfound':
							$page_status = is_404();
							break;
						case 'search':
							$page_status = is_search();
							break;
						case 'attachment':
							$page_status = is_attachment();
							break;
						case 'single':
							$page_status = is_single();
							break;
						case 'archive':
							$page_status = is_archive();
							break;
						case 'author':
							$page_status = is_author();
							break;
						case 'date':
							$page_status = is_date();
							break;
						case 'year':
							$page_status = is_year();
							break;
						case 'month':
							$page_status = is_month();
							break;
						case 'day':
							$page_status = is_day();
							break;
						case 'time':
							$page_status = is_time();
							break;
					}
					if ( $page_status ) {
						$page_status_new = true;
						$current_page[]  = $sp_key;
					}
				}
				if ( $page_status_new && ! empty( $current_page ) ) {
					foreach ( $current_page as $cur_page ) {
						if ( $sp_access[ $cur_page ]['protection'] == '1' ) {
							$page_access_cur_page   = $cur_page;
							$page_access_protection = $sp_access[ $cur_page ]['protection'];
							$page_access_plans      = $sp_access[ $cur_page ]['plans'];
							break;
						}
					}
					if ( ! empty( $page_access_protection ) && $page_access_protection == '1' ) {
						$sp_plans       = ( ! empty( $page_access_plans ) ) ? $page_access_plans : array();
						$sp_plans_array = array_intersect( $current_user_plan, $sp_plans );
						if ( ! empty( $sp_plans_array ) ) {
							return apply_filters( 'arm_special_page_access', true, $current_page, $sp_access );
						}
						return apply_filters( 'arm_special_page_access', false, $current_page, $sp_access );
					}
				}
			}
			return apply_filters( 'arm_special_page_access', true, $current_page, $sp_access );
		}

		/*         * *************************\.Begin Feed Restrictions.\****************************** */

		function arm_feed_link( $feed_link, $feed ) {
			global $current_user, $wp, $wpdb, $ARMemberLite, $arm_global_settings;
			if ( empty( $arm_user ) || ! method_exists( $arm_user, 'has_cap' ) ) {
				$arm_user = wp_get_current_user();
			}

			if ( $current_user->ID > 0 ) {
				$feed_key = get_user_meta( $current_user->ID, '_arm_feed_key', true );
				if ( empty( $feed_key ) ) {
					$feed_key = md5( $current_user->ID . $current_user->user_pass . time() );
					update_user_meta( $current_user->ID, '_arm_feed_key', $feed_key );
				}
				if ( ! empty( $feed_key ) ) {
					$feed_link = $arm_global_settings->add_query_arg( 'k', $feed_key, untrailingslashit( $feed_link ) );
				}
			}
			$feed_link = apply_filters( 'arm_feed_link', $feed_link );
			return $feed_link;
		}

		function find_user_by_feed_key( $key = false ) {
			global $wpdb;
			$user_id = $wpdb->get_var( $wpdb->prepare('SELECT `user_id` FROM `' . $wpdb->usermeta . "` WHERE `meta_key`=%s AND `meta_value`=%s LIMIT 0,1",'_arm_feed_key',$key) ); //phpcs:ignore --Reason $wpdb->usermeta is a table name
			return $user_id;
		}

		function show_noaccess_feed( $wp_query ) {
			$post              = new stdClass();
			$post->post_author = 1;
			$post->post_name   = '';
			add_filter( 'the_permalink', create_function( '$permalink', 'return "' . ARMLITE_HOME_URL . '";' ) );//phpcs:ignore
			$post->guid           = site_url();
			$post->post_title     = esc_html__( 'No Feed Access', 'armember-membership' );
			$post->post_content   = esc_html__( 'Sorry, You Don\'t Have Feed Access', 'armember-membership' );
			$post->ID             = -1;
			$post->post_status    = 'publish';
			$post->post_type      = 'post';
			$post->comment_status = 'closed';
			$post->ping_status    = 'open';
			$post->comment_count  = 0;
			$post->post_date      = current_time( 'mysql' );
			$post->post_date_gmt  = current_time( 'mysql', 1 );
			$posts                = array( $post );
			$posts                = apply_filters( 'arm_restricted_feed_content_posts', $posts );
			return $posts;
		}

		function arm_check_feed_rules() {
			global $wp, $wpdb, $current_user, $arm_lite_errors, $ARMemberLite, $arm_global_settings, $arm_access_rules;

			$this->arm_add_required_core_file();

			$global_settings = $arm_global_settings->global_settings;

			$rules_opts = $arm_access_rules->arm_get_custom_access_rules( 'special_pages' );
			if ( ! empty( $rules_opts ) && ! empty( $rules_opts['feed'] ) ) {
				$feed_protection = $rules_opts['feed']['protection'];
				$feed_plans      = ( ! empty( $rules_opts['feed']['plans'] ) ) ? $rules_opts['feed']['plans'] : array();
				if ( $feed_protection == '1' ) {
					if ( isset( $_GET['k'] ) ) {
						$key     = sanitize_text_field($_GET['k']); //phpcs:ignore
						$user_id = $this->find_user_by_feed_key( $key );
						$user_id = (int) $user_id;
						if ( $user_id > 0 ) {
							$user_plan = get_user_meta( $user_id, 'arm_user_plan_ids', true );
							$user_plan = ( ! empty( $user_plan ) ) ? $user_plan : array( -2 );

							$arm_primary_status = arm_get_member_status( $user_id );

							if ( $arm_primary_status == 3 ) {
								$user_plan = array();
							}

							$feed_plans_array = array_intersect( $user_plan, $feed_plans );

							if ( ! empty( $feed_plans_array ) ) {
								wp_set_current_user( $user_id );
								return true;
							}
						}
					}
					return false;
				}
			}
			return true;
		}

		/*         * ***************************\.End Feed Restrictions.\****************************** */

		function arm_block_access() {
			global $wp, $wpdb, $ARMemberLite, $current_user;
			$url = esc_url( 'http' . ( empty( $_SERVER['HTTPS'] ) ? '' : 's' ) . '://' . sanitize_text_field($_SERVER['SERVER_NAME']) . sanitize_text_field($_SERVER['REQUEST_URI']) ); //phpcs:ignore
			status_header( 404 );
			nocache_headers();
			$headers                 = array( 'X-Pingback' => get_bloginfo( 'pingback_url' ) );
			$headers['Content-Type'] = get_option( 'html_type' ) . '; charset=' . get_option( 'blog_charset' );
			foreach ( (array) $headers as $name => $field_value ) {
				@header( "{$name}: {$field_value}" );
			}
			$pos_php_self     = strpos( sanitize_text_field($_SERVER['PHP_SELF']), '/wp-admin/' ); //phpcs:ignore
			$expectedPosition = strlen( sanitize_text_field($_SERVER['PHP_SELF']) ) - strlen( '.php' ); //phpcs:ignore
			if ( $pos_php_self === false && strrpos( sanitize_text_field($_SERVER['PHP_SELF']), '.php', 0 ) === $expectedPosition ) { //phpcs:ignore
				wp_redirect( home_url( '/404_Not_Found' ) );
			} else {
				if ( get_404_template() ) {
					require_once get_404_template();
				} else {
					require_once get_single_template();
				}
			}
			die();
		}

		function arm_add_required_core_file() {
			if ( ! function_exists( 'wp_get_current_user' ) ) {
				include ABSPATH . 'wp-includes/pluggable.php';
			}
		}
		
		function arm_rest_api_pre_echo_response_func( $response, $object, $request )
		{
            $response_custom_array = array();
            if(is_array($response))
            {
                $response_custom_array['id'] = !empty( $response['id'] ) ? $response['id'] : 0;
                $response_custom_array['type'] = !empty( $response['type'] ) ? $response['type'] : '';
                $response_custom_array['guid'] = !empty( $response['guid'] ) ? $response['guid'] : '';
            }
            else {
                $response_custom_array['id'] = !empty( $response->id ) ? $response->id : 0;
                $response_custom_array['type'] = !empty( $response->type ) ? $response->type : '';
                $response_custom_array['guid'] = !empty( $response->guid ) ? $response->guid : '';
            }
            if( !empty( $response_custom_array['id'] ) && !empty( $response_custom_array['type'] ) && !empty( $response_custom_array['guid'] ) )
			{
				global $post, $arm_restriction;
				if(empty( $post) )
				{
					$arm_get_post_arr = get_post( $response_custom_array['id'] );
				}
				else {
					if( !empty($post->ID) && $post->ID==$response_custom_array['id'] )
					{
						$arm_get_post_arr = $post;
					}
					else {
						$arm_get_post_arr = get_post( $response_custom_array['id'] );
					}
				}
				$arm_return_data = 1;
				$allow_access = $arm_restriction->arm_wp_head_redirect($arm_get_post_arr, $arm_return_data);

				if($allow_access==2){
					$response = new WP_Error( 'rest_restricted', esc_html__('You are not allowed to access the content.','armember-membership'), array( 'status' => 403 ) );
				}
			}
            return $response;
        }
	}

}
global $arm_restriction;
$arm_restriction = new ARM_restriction_Lite();

if ( ! class_exists( 'ARM_fail_attempts' ) ) {

	class ARM_fail_attempts {

		function __construct() {

		}

		function logFailAttempts( $username = '', $password = '', $user_id = 0 ) {

			global $wp, $wpdb, $current_user, $arm_lite_errors, $ARMemberLite, $arm_global_settings;
			$arm_all_block_settings = $arm_global_settings->block_settings;
			$ip                     = $ARMemberLite->arm_get_ip_address();
			$failed_login_lockdown  = $arm_all_block_settings['failed_login_lockdown'];
			if ( $failed_login_lockdown == 1 ) {
				$user_detail_uns = array(
					'username' => $username,
					'password' => $password,
					'server'   => $_SERVER,
				);
				$user_detail     = maybe_serialize( $user_detail_uns );
				$ins_data        = array(
					'arm_user_id'                => $user_id,
					'arm_fail_attempts_detail'   => $user_detail,
					'arm_fail_attempts_ip'       => $ip,
					'arm_fail_attempts_datetime' => current_time( 'mysql' ),
				);

				$wpdb->insert( $ARMemberLite->tbl_arm_fail_attempts, $ins_data );
			}
		}

		function countFails( $username = '', $lock_duration = 0, $user_id = 0, $is_temporary = true ) {
			global $wp, $wpdb, $current_user, $arm_lite_errors, $ARMemberLite, $arm_global_settings;
			$arm_all_block_settings = $arm_global_settings->block_settings;
			if ( empty( $lock_duration ) ) {
				$lock_duration = $arm_all_block_settings['temporary_lockdown_duration'];
			}
			$ip     = $ARMemberLite->arm_get_ip_address();
			$where = $wpdb->prepare("WHERE `arm_fail_attempts_datetime` + INTERVAL %d MINUTE > %s AND `arm_user_id` = %d AND `arm_fail_attempts_ip` LIKE %s",$lock_duration,current_time( 'mysql' ),$user_id,$ip);
			if ( $is_temporary ) {
				$where .= $wpdb->prepare(" AND `arm_is_block`=%d ",0);
			}
			$numFails = $wpdb->get_var( 'SELECT COUNT(arm_fail_attempts_id) FROM `' . $ARMemberLite->tbl_arm_fail_attempts . "` $where " ); //phpcs:ignore --Reason: $ARMemberLite->tbl_arm_fail_attempts is a table name
			return $numFails;
		}

		function lockDown( $username = '', $lock_minutes = 0, $user_id = 0 ) {
			global $wp, $wpdb, $current_user, $arm_lite_errors, $ARMemberLite, $arm_global_settings;
			$arm_all_block_settings = $arm_global_settings->block_settings;
			$ip                     = $ARMemberLite->arm_get_ip_address();
			if ( empty( $lock_minutes ) ) {
				$lock_minutes = $arm_all_block_settings['temporary_lockdown_duration'];
			}
			$now         = current_time( 'mysql' );
			$relase_lock = date( 'Y-m-d H:i:s', strtotime( $now . ' + ' . $lock_minutes . ' minute' ) );
			$ins_data    = array(
				'arm_user_id'       => $user_id,
				'arm_lockdown_date' => $now,
				'arm_release_date'  => $relase_lock,
				'arm_lockdown_IP'   => $ip,
			);
			$wpdb->insert( $ARMemberLite->tbl_arm_lockdown, $ins_data );
			$updateFails = $wpdb->get_results( $wpdb->prepare('UPDATE `' . $ARMemberLite->tbl_arm_fail_attempts . "` SET `arm_is_block`='1', `arm_fail_attempts_release_datetime`=%s WHERE `arm_fail_attempts_datetime` + INTERVAL %d MINUTE > %s AND `arm_fail_attempts_ip` LIKE %s AND `arm_user_id`=%d AND `arm_is_block`='0'",$relase_lock,$lock_minutes,$now,$ip,$user_id) );//phpcs:ignore --Reason: $ARMemberLite->tbl_arm_fail_attempts is a table name
		}

		function isLockedDown( $user_id = 0 ) {
			global $wp, $wpdb, $current_user, $arm_lite_errors, $ARMemberLite, $arm_global_settings;
			$arm_all_block_settings = $arm_global_settings->block_settings;
			$failed_login_lockdown  = isset( $arm_all_block_settings['failed_login_lockdown'] ) ? $arm_all_block_settings['failed_login_lockdown'] : 0;
			$stillLocked            = false;
			$ip                     = $ARMemberLite->arm_get_ip_address();
			if ( $failed_login_lockdown == 1 ) {
				$lockedResults = $wpdb->get_results( $wpdb->prepare('SELECT `arm_user_id`, `arm_lockdown_date`, `arm_release_date`, `arm_lockdown_IP` FROM `' . $ARMemberLite->tbl_arm_lockdown . "` WHERE `arm_release_date` > %s AND `arm_user_id`=%d AND `arm_lockdown_IP` LIKE %s  ORDER BY `arm_lockdown_ID` DESC ",current_time( 'mysql' ),$user_id,$ip ) );//phpcs:ignore --Reason: $ARMemberLite->tbl_arm_fail_attempts is a table name
				if ( ! empty( $lockedResults ) ) {
					$stillLocked = true;
				}
			}
			return $stillLocked;
		}

	}

}
global $arm_fail_attempts;
$arm_fail_attempts = new ARM_fail_attempts();

if ( ! function_exists( 'get_post_slug' ) ) {

	function get_post_slug( $pid = null ) {
		$pslug = '';
		if ( ! empty( $pid ) && $pid != 0 ) {
			$post = get_post( $pid );
			if ( ! empty( $post ) ) {
				$pslug = $post->post_name;
			}
		}
		return $pslug;
	}
}
if ( ! function_exists( 'wp_get_current_page_url' ) ) {

	/**
	 * Get Current Page URL
	 */
	function wp_get_current_page_url() {
		global $wp, $wpdb, $current_user, $arm_lite_errors, $ARMemberLite, $arm_member_forms, $arm_global_settings, $arm_email_settings;
		/* get requested url */
		$requested_url = home_url( $wp->request . '/' );
		/* Add query string in requested url */
		$ARM_SERVER_QUERY_STRING = !empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : ''; //phpcs:ignore
		$current_url = $arm_global_settings->add_query_arg( $ARM_SERVER_QUERY_STRING, '', $requested_url ); //phpcs:ignore
		return $current_url;
	}
}
if ( ! function_exists( 'the_current_page_url' ) ) {

	/**
	 * Print Current Page URL
	 */
	function the_current_page_url() {
		echo wp_get_current_page_url(); //phpcs:ignore
	}
}

/*
if (!function_exists('wp_new_user_notification')) {

	function wp_new_user_notification() {
		return;
	}

}*/



if ( ! function_exists( 'arm_new_user_notification' ) ) {

	/**
	 * New User Notification Mail.
	 *
	 * @param integer $user_id Registered User's ID
	 * @param string  $plaintext_pass User's Password
	 * @return type
	 */
	function arm_new_user_notification( $user_id, $plaintext_pass = '' ) {
		global $wp, $wpdb, $current_user, $arm_lite_errors, $ARMemberLite, $arm_member_forms, $arm_global_settings, $arm_email_settings;
		$user = new WP_User( $user_id );
		do_action( 'arm_before_new_user_notification', $user );
		/* Send Activation link if `user_register_verification` option is set to `email`. */
		$user_register_verification = $arm_global_settings->arm_get_single_global_settings( 'user_register_verification', 'auto' );
		if ( $user_register_verification == 'email' ) {
			/* New Member Signup Verification Mail */
			armEmailVerificationMail( $user );
		} else {
			/* New Member Signup Complete Notification */
			armMemberSignUpCompleteMail( $user, $plaintext_pass );
		}
		do_action( 'arm_after_new_user_notification', $user );
	}
}

if ( ! function_exists( 'armEmailVerificationMail' ) ) {

	function armEmailVerificationMail( $user = null ) {
		global $wp, $wpdb, $current_user, $arm_lite_errors, $ARMemberLite, $arm_member_forms, $arm_global_settings, $arm_email_settings;
		$user_verify_send_mail = false;
		if ( ! empty( $user ) ) {
			$user_id            = $user->ID;
			$user_login         = stripslashes( $user->user_login );
			$user_email         = $user->user_email;
			$activation_key     = get_user_meta( $user->ID, 'arm_user_activation_key', true );
			$temp_detail_verify = $arm_email_settings->arm_get_email_template( $arm_email_settings->templates->email_verify_user );
			/* New Member Signup Verification Mail */
			if ( $temp_detail_verify->arm_template_status == '1' ) {
				$subject_verify        = $arm_global_settings->arm_filter_email_with_user_detail( $temp_detail_verify->arm_template_subject, $user_id, 0 );
				$subject_verify        = apply_filters( 'arm_user_verify_message_subject', $subject_verify, $user_login, $user_email, '', $activation_key );
				$msg_verify            = $arm_global_settings->arm_filter_email_with_user_detail( $temp_detail_verify->arm_template_content, $user_id, 0 );
				$msg_verify            = apply_filters( 'arm_change_verification_email_notification', $msg_verify, $user_login, $user_email, '', $activation_key );
				$user_verify_send_mail = $arm_global_settings->arm_wp_mail( '', $user_email, $subject_verify, $msg_verify );
			}
		}
		return $user_verify_send_mail;
	}
}
if ( ! function_exists( 'armMemberSignUpCompleteMail' ) ) {

	/**
	 * Admin & User notification when successful registration
	 *
	 * @param type $user User Object
	 * @param type $plaintext_pass User's Password
	 */
	function armMemberSignUpCompleteMail( $user, $plaintext_pass = '' ) {
		global $wp, $wpdb, $arm_lite_errors, $ARMemberLite, $arm_global_settings, $arm_email_settings, $wp_hasher;
		$user_id = $user->ID;
		do_action( 'arm_before_signup_complete_notification', $user );
		$user_login         = stripslashes( $user->user_login );
		$user_email         = $user->user_email;
		$activation_key     = get_user_meta( $user->ID, 'arm_user_activation_key', true );
		$arm_last_user_plan = get_user_meta( $user_id, 'arm_user_last_plan', true );
		$planID             = ! empty( $arm_last_user_plan ) ? $arm_last_user_plan : 0;
		$plan_detail        = array();
		if ( ! empty( $planID ) ) {
			$planData    = get_user_meta( $user_id, 'arm_user_plan_' . $planID, true );
			$plan_detail = $planData['arm_current_plan_detail'];
		}
		if ( ! empty( $plan_detail ) ) {
			$planObj = new ARM_Plan_Lite( 0 );
			$planObj->init( (object) $plan_detail );
		} else {
			$planObj = new ARM_Plan_Lite( $planID );
		}
		$temp_detail = $arm_email_settings->arm_get_email_template( $arm_email_settings->templates->new_reg_user_admin );
		if ( $temp_detail->arm_template_status == '1' ) {
			$subject_admin = $arm_global_settings->arm_filter_email_with_user_detail( $temp_detail->arm_template_subject, $user_id, $planID );
			$message_admin = $arm_global_settings->arm_filter_email_with_user_detail( $temp_detail->arm_template_content, $user_id, $planID );

			$subject_admin   = apply_filters( 'arm_admin_message_subject', $subject_admin, $user_login, $user_email, $plaintext_pass, $activation_key );
			$message_admin   = apply_filters( 'arm_change_registration_email_notification_to_admin', $message_admin, $user_login, $user_email, $plaintext_pass, $activation_key );
			$admin_send_mail = $arm_global_settings->arm_send_message_to_armember_admin_users( $user_email, $subject_admin, $message_admin );
		}

		if ( $planObj->is_paid() ) {

			$temp_detail_user = $arm_email_settings->arm_get_email_template( $arm_email_settings->templates->new_reg_user_with_payment );
		} else {

			$temp_detail_user = $arm_email_settings->arm_get_email_template( $arm_email_settings->templates->new_reg_user_without_payment );
		}
		if ( $temp_detail_user->arm_template_status == '1' ) {

			if ( function_exists( 'get_password_reset_key' ) ) {
				remove_all_filters( 'allow_password_reset' );
				$key = get_password_reset_key( $user );
			} else {
				do_action( 'retreive_password', $user_login );  /* Misspelled and deprecated */
				do_action( 'retrieve_password', $user_login );

				$allow = apply_filters( 'allow_password_reset', true, $user_id );

				if ( ! $allow ) {
					$key = '';
				} elseif ( is_wp_error( $allow ) ) {
					$key = '';
				}
				/* Generate something random for a key... */
				$key = wp_generate_password( 20, false );
				do_action( 'retrieve_password_key', $user_login, $key );
				/* Now insert the new md5 key into the db */
				if ( empty( $wp_hasher ) ) {
					require_once ABSPATH . WPINC . '/class-phpass.php';
					$wp_hasher = new PasswordHash( 8, true );
				}
				$hashed    = $wp_hasher->HashPassword( $key );
				$key_saved = $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );
				if ( false === $key_saved ) {
					$key = '';
				}
			}

			$subject = $arm_global_settings->arm_filter_email_with_user_detail( $temp_detail_user->arm_template_subject, $user_id, $planID );
			$message = $arm_global_settings->arm_filter_email_with_user_detail( $temp_detail_user->arm_template_content, $user_id, $planID, 0, $key );

			$message        = apply_filters( 'arm_change_registration_email_notification_to_user', $message, $user_login, $user_email, $plaintext_pass, $activation_key );
			$subject        = apply_filters( 'arm_user_message_subject', $subject, $user_login, $user_email, $plaintext_pass, $activation_key );
			$user_send_mail = $arm_global_settings->arm_wp_mail( '', $user_email, $subject, $message );
		}
		return;
	}
}
if ( ! function_exists( 'armMemberAccountVerifyMail' ) ) {

	function armMemberAccountVerifyMail( $user ) {
		global $wp, $wpdb, $arm_lite_errors, $ARMemberLite, $arm_global_settings, $arm_email_settings;
		$user_register_verification = $arm_global_settings->arm_get_single_global_settings( 'user_register_verification', 'auto' );
		if ( $user_register_verification == 'email' && ! empty( $user ) ) {
			$user_id          = $user->ID;
			$user_email       = $user->user_email;
			$temp_detail_user = $arm_email_settings->arm_get_email_template( $arm_email_settings->templates->account_verified_user );
			if ( $temp_detail_user->arm_template_status == '1' ) {
				$subject        = $arm_global_settings->arm_filter_email_with_user_detail( $temp_detail_user->arm_template_subject, $user_id, 0 );
				$message        = $arm_global_settings->arm_filter_email_with_user_detail( $temp_detail_user->arm_template_content, $user_id, 0 );
				$user_send_mail = $arm_global_settings->arm_wp_mail( '', $user_email, $subject, $message );
			}
		}
		return;
	}
}
if ( ! function_exists( 'wp_update_user_notification' ) ) {

	function wp_update_user_notification( $user_id, $posted_data ) {
		global $wp, $wpdb, $current_user, $arm_lite_errors, $ARMemberLite, $arm_global_settings, $arm_email_settings;
		$user = new WP_User( $user_id );
		if ( $user->exists() && 0 !== strcasecmp( $user->user_email, get_option( 'admin_email' ) ) ) {

			$arm_get_email_option     = get_option( 'arm_email_settings' );
			$arm_email_settings_array = maybe_unserialize( $arm_get_email_option );
			$user_email               = $user->user_email;
			$user_template            = $arm_email_settings->arm_get_email_template( $arm_email_settings->templates->profile_updated_user );
			if ( $user_template->arm_template_status == '1' ) {
				$user_message   = $arm_global_settings->arm_filter_email_with_user_detail( $user_template->arm_template_content, $user_id, 0 );
				$user_subject   = $arm_global_settings->arm_filter_email_with_user_detail( $user_template->arm_template_subject, $user_id, 0 );
				$user_send_mail = $arm_global_settings->arm_wp_mail( '', $user_email, $user_subject, $user_message );
			}

			$admin_template = $arm_email_settings->arm_get_email_template( $arm_email_settings->templates->profile_updated_notification_to_admin );
			if ( $admin_template->arm_template_status == '1' ) {

				$admin_message   = $arm_global_settings->arm_filter_email_with_user_detail( $admin_template->arm_template_content, $user_id, 0 );
				$admin_subject   = $arm_global_settings->arm_filter_email_with_user_detail( $admin_template->arm_template_subject, $user_id, 0 );
				$admin_send_mail = $arm_global_settings->arm_send_message_to_armember_admin_users( '', $admin_subject, $admin_message );
			}
		}
	}
}
if ( ! function_exists( 'wp_password_change_notification' ) ) {

	/**
	 * Notify the blog admin of a user changing password, normally via email.
	 *
	 * @param object $user User Object
	 */
	function wp_password_change_notification( $user ) {
		global $arm_global_settings, $arm_email_settings;
		if ( empty( $user ) ) {
			return;
		}
		/**
		 * send a copy of password change notification to the admin
		 * but check to see if it's the admin whose password we're changing, and skip this
		 */
		if ( 0 !== strcasecmp( $user->user_email, get_option( 'admin_email' ) ) ) {
			$message = esc_html__( 'Password Lost and Changed for user', 'armember-membership' ) . ': ' . $user->user_login . "\r\n";
			$message = esc_html__( 'Password Lost and Changed for user', 'armember-membership' ) . ': ' . $user->user_login . "\r\n";
			/**
			 * The blogname option is escaped with esc_html on the way into the database in sanitize_option
			 * we want to reverse this for the plain text arena of emails.
			 */
			$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

			$all_email_settings = $arm_email_settings->arm_get_all_email_settings();
			$admin_email        = ( ! empty( $all_email_settings['arm_email_admin_email'] ) ) ? $all_email_settings['arm_email_admin_email'] : get_option( 'admin_email' );

			$exploded_admin_email = array();
			if ( strpos( $admin_email, ',' ) !== false ) {
				$exploded_admin_email = explode( ',', trim( $admin_email ) );
			}

			if ( isset( $exploded_admin_email ) && ! empty( $exploded_admin_email ) ) {
				foreach ( $exploded_admin_email as $admin_email ) {
					if ( $admin_email != '' ) {
						$admin_email = trim( $admin_email );
						$send_mail   = $arm_global_settings->arm_wp_mail( '', $admin_email, $blogname . ' ' . esc_html__( 'Password Lost/Changed', 'armember-membership' ), $message );
					}
				}
			} else {
				if ( $admin_email ) {

					$send_mail = $arm_global_settings->arm_wp_mail( '', $admin_email, $blogname . ' ' . esc_html__( 'Password Lost/Changed', 'armember-membership' ), $message );
				}
			}
		}
	}
}
if ( ! function_exists( 'wp_authenticate' ) ) {

	/**
	 * Checks a user's login information and logs them in if it checks out.
	 *
	 * @param string $username User's username
	 * @param string $password User's password
	 * @return WP_User|WP_Error WP_User object if login successful, otherwise WP_Error object.
	 */
	function wp_authenticate( $username, $password ) {

		global $arm_global_settings, $arm_lite_errors, $ARMemberLite, $wpdb;
		$username = sanitize_user( $username );
		$password = trim( $password );

		$arm_all_block_settings      = isset( $arm_global_settings->block_settings ) ? $arm_global_settings->block_settings : array();
		$temporary_lockdown_duration = isset( $arm_all_block_settings['temporary_lockdown_duration'] ) ? $arm_all_block_settings['temporary_lockdown_duration'] : '';

		$permanent_lockdown_duration = isset( $arm_all_block_settings['permanent_lockdown_duration'] ) ? $arm_all_block_settings['permanent_lockdown_duration'] : '';

		$login_failed_msg = ( ! empty( $arm_global_settings->common_message['arm_attempts_many_login_failed'] ) ) ? $arm_global_settings->common_message['arm_attempts_many_login_failed'] : esc_html__( 'Your Account is locked for', 'armember-membership' ) . ' [LOCKDURATION] ' . esc_html__( 'minutes.', 'armember-membership' );

		$permanent_locked_msg = ( ! empty( $arm_global_settings->common_message['arm_permanent_locked_message'] ) ) ? $arm_global_settings->common_message['arm_permanent_locked_message'] : esc_html__( 'Your Account is locked for', 'armember-membership' ) . ' [LOCKDURATION] ' . esc_html__( 'hours.', 'armember-membership' );

		$login_failed_msg     = str_replace( '[LOCKDURATION]', $temporary_lockdown_duration, $login_failed_msg );
		$permanent_locked_msg = str_replace( '[LOCKDURATION]', $permanent_lockdown_duration, $permanent_locked_msg );

		$invalid_login_deatil_msg = ( ! empty( $arm_global_settings->common_message['arm_no_registered_email'] ) ) ? $arm_global_settings->common_message['arm_no_registered_email'] : '<strong>' . esc_html__( 'ERROR', 'armember-membership' ) . ': </strong>' . esc_html__( 'Invalid username or incorrect password.', 'armember-membership' );

		$user_info = get_user_by( 'login', $username );
		if ( $user_info === false ) {
			/* Allow User to login with Email Address */
			$user_info = get_user_by( 'email', $username );
			$username  = ( $user_info === false ) ? $username : $user_info->user_login;
		}
		$user_id = ( $user_info === false ) ? 0 : $user_info->ID;

		$max_retries             = $arm_all_block_settings['max_login_retries'];
		$temp_lock_duration      = $arm_all_block_settings['temporary_lockdown_duration'];
		$max_permenent_retries   = $arm_all_block_settings['permanent_login_retries'];
		$permanent_lock_duration = $arm_all_block_settings['permanent_lockdown_duration'];

		$ARM_fail_attempts = new ARM_fail_attempts();
		if ( $ARM_fail_attempts->isLockedDown( $user_id ) ) {
			if ( $max_permenent_retries <= $ARM_fail_attempts->countFails( $username, $permanent_lock_duration * 60, $user_id, false ) ) {
				$arm_lite_errors->add( 'incorrect_password', $permanent_locked_msg );
			} else {
				$arm_lite_errors->add( 'incorrect_password', $login_failed_msg );
			}

			if ( ! isset( $_POST['isAdmin'] ) ) {  //phpcs:ignore
				if ( function_exists( 'login_header' ) ) {
					login_header( '', '', $arm_lite_errors );
					echo '</div>';
					do_action( 'login_footer' );
					echo '</body></html>';
				} else {
					return $arm_lite_errors;
				}
			} else {
				return $arm_lite_errors;
			}
			exit;
		}
		/**
		 * Filter the user to authenticate.
		 *
		 * If a non-null value is passed, the filter will effectively short-circuit authentication, returning an error instead.
		 *
		 * @param null|WP_User $user     User to authenticate.
		 * @param string       $username User login.
		 * @param string       $password User password
		 */
		$user = apply_filters( 'authenticate', null, $username, $password );

		if ( $user == null ) {
			/**
			 * TODO what should the error message be? (Or would these even happen?)
			 * Only needed if all authentication handlers fail to return anything.
			 */
			$user = new WP_Error( 'authentication_failed', $invalid_login_deatil_msg );
		}
		$ignore_codes = array( 'empty_username', 'empty_password' );
		if ( is_wp_error( $user ) && ! in_array( $user->get_error_code(), $ignore_codes ) ) {

			$global_settings        = $arm_global_settings->arm_get_all_global_settings();
			$general_settings       = $global_settings['general_settings'];
			$arm_all_block_settings = $arm_global_settings->block_settings;
			$failed_login_lockdown  = $arm_all_block_settings['failed_login_lockdown'];
			if ( $failed_login_lockdown == 1 ) {

				$ARM_fail_attempts->logFailAttempts( $username, $password, $user_id );
				if ( $max_permenent_retries <= $ARM_fail_attempts->countFails( $username, $permanent_lock_duration * 60, $user_id, false ) ) {
					$ARM_fail_attempts->lockDown( $username, $permanent_lock_duration * 60, $user_id );
					return new WP_Error( 'incorrect_password', $login_failed_msg );
				} elseif ( $max_retries <= $ARM_fail_attempts->countFails( $username, $temp_lock_duration, $user_id ) ) {
					$ARM_fail_attempts->lockDown( $username, $temp_lock_duration, $user_id );
					return new WP_Error( 'incorrect_password', $login_failed_msg );
				}
			}
			/**
			 * Display Error Messages For Incorrect Details.
			 */
			if ( $user->get_error_code() == 'incorrect_password' ) {

				$arm_all_block_settings = $arm_global_settings->block_settings;
				$incorrect_password_msg = ( ! empty( $arm_global_settings->common_message['arm_invalid_password_login'] ) ) ? $arm_global_settings->common_message['arm_invalid_password_login'] : '<strong>' . esc_html__( 'ERROR', 'armember-membership' ) . ': </strong>' . esc_html__( 'The password you entered is invalid.', 'armember-membership' );

				if ( $arm_all_block_settings['remained_login_attempts'] == 1 && $arm_all_block_settings['failed_login_lockdown'] == 1 ) {
					$failed_login_lockdown   = $arm_all_block_settings['failed_login_lockdown'];
					$max_retries             = $arm_all_block_settings['max_login_retries'];
					$temp_lock_duration      = $arm_all_block_settings['temporary_lockdown_duration'];
					$total_fail_attempts     = $ARM_fail_attempts->countFails( $username, $temp_lock_duration, $user_id );
					$max_permenent_retries   = $arm_all_block_settings['permanent_login_retries'];
					$permanent_lock_duration = $arm_all_block_settings['permanent_lockdown_duration'];
					$total_attempts          = $ARM_fail_attempts->countFails( $username, $permanent_lock_duration * 60, $user_id, false );
					if ( ( $max_permenent_retries - $total_attempts ) < ( $max_retries - $total_fail_attempts ) ) {
						$total_remained_attempts = ( $max_permenent_retries - $total_attempts );
					} else {
						$total_remained_attempts = $max_retries - $total_fail_attempts;
					}
					if ( ! empty( $arm_global_settings->common_message['arm_attempts_login_failed'] ) ) {
						$arm_attempts_login_failed = str_replace( '[ATTEMPTS]', $total_remained_attempts, $arm_global_settings->common_message['arm_attempts_login_failed'] );
					} else {
						$arm_attempts_login_failed = esc_html__( 'Remaining Login Attempts :', 'armember-membership' ) . '&nbsp;' . $total_remained_attempts;
					}
					$incorrect_password_msg .= '<br/>' . $arm_attempts_login_failed;
					$user                    = new WP_Error( 'incorrect_password', $incorrect_password_msg );
				} else {
					$user = new WP_Error( 'incorrect_password', $incorrect_password_msg );
				}
			} elseif ( $user->get_error_code() == 'invalid_username' || $user->get_error_code() == 'invalid_email' ) {
				$invalid_username_msg = ( ! empty( $arm_global_settings->common_message['arm_user_not_exist'] ) ) ? $arm_global_settings->common_message['arm_user_not_exist'] : '<strong>' . esc_html__( 'ERROR', 'armember-membership' ) . ': </strong>' . esc_html__( 'Invalid username or e-mail.', 'armember-membership' );

				$arm_all_block_settings = $arm_global_settings->block_settings;

				if ( $arm_all_block_settings['remained_login_attempts'] == 1 && $arm_all_block_settings['failed_login_lockdown'] == 1 ) {
					$failed_login_lockdown   = $arm_all_block_settings['failed_login_lockdown'];
					$max_retries             = $arm_all_block_settings['max_login_retries'];
					$temp_lock_duration      = $arm_all_block_settings['temporary_lockdown_duration'];
					$total_fail_attempts     = $ARM_fail_attempts->countFails( $username, $temp_lock_duration, $user_id );
					$permanent_lock_duration = $arm_all_block_settings['permanent_lockdown_duration'];
					$total_attempts          = $ARM_fail_attempts->countFails( $username, $permanent_lock_duration * 60, $user_id, false );
					$max_permenent_retries   = $arm_all_block_settings['permanent_login_retries'];
					if ( ( $max_permenent_retries - $total_attempts ) < ( $max_retries - $total_fail_attempts ) ) {
						$total_remained_attempts = ( $max_permenent_retries - $total_attempts );
					} else {
						$total_remained_attempts = $max_retries - $total_fail_attempts;
					}
					if ( ! empty( $arm_global_settings->common_message['arm_attempts_login_failed'] ) ) {
						$arm_attempts_login_failed = str_replace( '[ATTEMPTS]', $total_remained_attempts, $arm_global_settings->common_message['arm_attempts_login_failed'] );
					} else {
						$arm_attempts_login_failed = esc_html__( 'Remaining Login Attempts :', 'armember-membership' ) . '&nbsp;' . $total_remained_attempts;
					}
					$invalid_username_msg .= '<br/>' . $arm_attempts_login_failed;
					$user                  = new WP_Error( 'incorrect_password', $invalid_username_msg );
				} else {
					$user = new WP_Error( 'incorrect_password', $invalid_username_msg );
				}
			}
			/**
			 * Fires after a user login has failed.
			 *
			 * @param string $username User login.
			 */
			do_action( 'wp_login_failed', $username );
		} else {

			if ( ! is_wp_error( $user ) ) {
				$statuses = $wpdb->get_row( $wpdb->prepare('SELECT `arm_primary_status`, `arm_secondary_status` FROM `' . $ARMemberLite->tbl_arm_members . "` WHERE `arm_user_id`=%d ",$user->ID) );//phpcs:ignore --Reason $ARMemberLite->tbl_arm_members is a table name
				if ( $statuses != null ) {
					$userstatus       = $statuses->arm_primary_status;
					$secondary_status = $statuses->arm_secondary_status;
				}
				if ( ( $userstatus == '2' && in_array( $secondary_status, array( 0, 1 ) ) ) || $userstatus == 4 ) {
					$invalid_username_msg = ( ! empty( $arm_global_settings->common_message['arm_account_inactive'] ) ) ? $arm_global_settings->common_message['arm_account_inactive'] : '<strong>' . esc_html__( 'Account Inactive', 'armember-membership' ) . '</strong>: ' . esc_html__( 'Your account is currently not active. Please contact the system administrator.', 'armember-membership' );
					$user                 = new WP_Error( 'inactive_user', $invalid_username_msg );
				} elseif ( $userstatus == '3' ) {
					$invalid_username_msg = ( ! empty( $arm_global_settings->common_message['arm_account_pending'] ) ) ? $arm_global_settings->common_message['arm_account_pending'] : '<strong>' . esc_html__( 'Account Pending', 'armember-membership' ) . '</strong>: ' . esc_html__( 'Your account is currently not active. An administrator needs to activate your account before you can login.', 'armember-membership' );
					$user                 = new WP_Error( 'pending_user', $invalid_username_msg );
				} else {
					/*                     * ******************Update Last Login Info For Successfull Login******************** */
					update_user_meta( $user->ID, 'arm_last_login_date', current_time( 'mysql' ) );
					$ip_address = $ARMemberLite->arm_get_ip_address();
					update_user_meta( $user->ID, 'arm_last_login_ip', $ip_address );
				}
			}
		}
		return $user;
	}
}
