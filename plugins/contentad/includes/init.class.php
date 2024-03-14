<?php

if ( ! class_exists( 'ContentAd__Includes__Init' ) ) {

	class ContentAd__Includes__Init {

		public static function on_load() {
			// Add our custom post type
			new ContentAd__Includes__Post_Type();

			// Add our custom admin pages
			if( is_admin() ) {
				new ContentAd__Includes__Admin__Admin();
			}

			add_action( 'init', array( __CLASS__, 'init' ) );
			add_action( 'ca_cron', array( __CLASS__, 'run_ca_cron' ) );
			add_action( 'widgets_init', array( __CLASS__, 'widgets_init' ) );
			add_action( 'wp_ajax_edit_contentad_widget', array( __CLASS__, 'ajax_edit_widget' ) );
      add_action( 'contentad', array( __CLASS__, 'plugin_shortcode' ), 10, 2 );
			add_shortcode( 'contentad', array( 'ContentAd__Includes__Shortcode', 'contentad_shortcode' ) );
      add_action( 'contentad_exitpop', array( __CLASS__, 'contentad_exitpop' ), 10, 2 );
			register_deactivation_hook( CONTENTAD_FILE, array( __CLASS__, 'deactivate' ) );
			register_uninstall_hook( CONTENTAD_FILE, array( __CLASS__, 'uninstall' ) );
      
		}

    public static function plugin_shortcode( $atts, $content = '' ) {
        echo ContentAd__Includes__Shortcode::contentad_shortcode( $atts, $content );
    }

    public static function contentad_exitpop( $atts, $content = '' ) {
        echo ContentAd__Includes__Exitpop::contentad_exitpop( $atts, $content );
    }

		public static function init() {
			if ( ! wp_next_scheduled( 'ca_cron' ) ) {
				wp_schedule_event( time(), 'daily', 'ca_cron' );
			}
			load_plugin_textdomain( 'contentad', false, basename( dirname( CONTENTAD_FILE ) ) . '/languages' );
			add_action( 'init', array( 'Contentad__Plugin__Review', 'check_installation_date') );
			add_action( 'wp_ajax_track_registration_clicks', array( 'Contentad__Plugin__Review', 'set_no_bug' ) );
			add_action( 'wp_head', array( __CLASS__, 'wp_head' ) );
			add_filter( 'the_content', array( __CLASS__, 'the_content' ), 0 );
			add_action( 'wp_footer', array( __CLASS__, 'contentad_exitpop' ) );
			add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 2 );
			add_filter( 'plugin_action_links_contentad/contentad.php', array( __CLASS__, 'plugin_action_links' ) );
		}

		public static function plugin_action_links( $actions ) {
			/**
			 * Add a 'Settings' link to the available actions for this plugin on the plugin page.
			 * Add to the beginning of the array of actions.
			 */
			$settings_url = admin_url( 'admin.php?page=contentad-settings' );
			$link_text = __( 'Settings', 'contentad' );
			array_unshift( $actions, "<a href=\"{$settings_url}\">{$link_text}</a>" );
			return $actions;
		}

		public static function plugin_row_meta( $plugin_meta, $plugin_file ) {
			if ( false !== strpos( $plugin_file, '/contentad.php' ) ) {
				$link_text = __( 'Visit on WordPress.org', 'contentad' );
				$plugin_meta[] = "<a href=\"http://wordpress.org/extend/plugins/contentad\" target=\"_blank\">{$link_text}</a>";
				$link_text = __( 'Visit Developer\'s Profile', 'contentad' );
				$plugin_meta[] = "<a href=\"http://profiles.wordpress.org/contentad/\" target=\"_blank\">{$link_text}</a>";
			}
			return $plugin_meta;
		}

		public static function run_ca_cron() {
			self::get_widgets();
		}

		public static function widgets_init() {
			register_widget('ContentAd__Includes__Widget');
		}

		public static function deactivate() {
			$widgets = get_posts( array(
				'post_type'   => 'content_ad_widget',
				'numberposts' => -1,
				'post_status' => 'publish',
			) );
			foreach ( $widgets as $widget ) {
				self::delete_local_widget( $widget->ID );
			}
			Contentad__Plugin__Review::delete_activation_date();
			remove_action( 'ca_cron', array( __CLASS__, 'run_ca_cron' ) );
			wp_clear_scheduled_hook( 'ca_cron' );
      
			contentAd_append_to_log( "Plugin Deactivated" );
		}

		public static function uninstall() {
			delete_option( 'contentad_api_key' );
			delete_option( 'contentad_install_key' );
		}

		public static function the_content( $content ) {
			if ( is_single() ) {
				$before =  array( 'meta_query' => array( 'placement' => array( 'key' => 'placement', 'value' => 'before_post_content' ) ) );
				$before = ContentAd__Includes__API::get_ad_code( $before );
				$after = array( 'meta_query' => array( 'placement' => array( 'key' => 'placement', 'value' => 'after_post_content' ) ) );
				$after = ContentAd__Includes__API::get_ad_code( $after );
				$content = $before . $content . $after;
			}
			return $content;
		}

		public static function get_local_widgets( $args = array() ) {
			contentAd_append_to_log( 'FETCHING LOCAL WIDGETS' );
			$defaults = array(
				'post_type' => 'content_ad_widget',
				'post_status' => 'any',
				'posts_per_page' => -1,
			);
			if( is_array( $args ) ) {
				$args = wp_parse_args( $args, $defaults );
			}
			contentAd_append_to_log( json_encode( $args ) );
			$local_widgets = get_posts( $args );
			contentAd_append_to_log( 'LOCAL WIDGETS FOUND: ' . count( $local_widgets ) );
			$widgets_indexed_by_id = array();
			if ( $local_widgets ) {
				foreach ( $local_widgets as $key => $widget ) {
					$local_widgets[$key]->adunit_id = get_post_meta( $widget->ID, '_widget_id', true );
					$local_widgets[$key]->adunit_name = get_post_meta( $widget->ID, '_widget_type', true );
					$local_widgets[$key]->aw_guid = get_post_meta( $widget->ID, '_widget_guid', true );
					$local_widgets[$key]->exit_pop = get_post_meta( $widget->ID, '_widget_exit_pop', true );
					$local_widgets[$key]->mobile_exit_pop = get_post_meta( $widget->ID, '_widget_mobile_exit_pop', true );
					$local_widgets[$key]->placement = get_post_meta( $widget->ID, 'placement', true );
					contentAd_append_to_log( 'RESULT ' . ($key + 1) . ': LOCAL WIDGET ' . $widget->ID );
					contentAd_append_to_log( '    ADUNIT ID: ' . $local_widgets[$key]->adunit_id );
					contentAd_append_to_log( '    ADUNIT NAME: ' . $local_widgets[$key]->adunit_name );
					contentAd_append_to_log( '    AD GUID: ' . $local_widgets[$key]->aw_guid );
					contentAd_append_to_log( '    EXIT POP: ' . $local_widgets[$key]->exit_pop );
					contentAd_append_to_log( '    EXIT POP MOBILE: ' . $local_widgets[$key]->mobile_exit_pop );
					contentAd_append_to_log( '    AD PLACEMENT: ' . $local_widgets[$key]->placement );
					$widgets_indexed_by_id[$widget->ID] = $local_widgets[$key];
				}
			}
			return $widgets_indexed_by_id;
		}

		public static function get_local_widget_id_by_adunit_id( $adunit_id ) {
			contentAd_append_to_log( 'SEARCHING FOR LOCAL MATCH TO REMOTE AD WIDGET: ' . $adunit_id );
            /**
             * @var wpdb $wpdb
             */
            global $wpdb;
			$sql = sprintf(
				"SELECT ID FROM %s LEFT JOIN %s ON ID = post_id AND meta_key = '_widget_id' WHERE meta_value = '%s' LIMIT 1",
				$wpdb->posts,
				$wpdb->postmeta,
				$adunit_id
			);
			$post_id = $wpdb->get_col( $sql );
			if( isset( $post_id[0] ) ) {
				contentAd_append_to_log( '    FOUND LOCAL WIDGET ('.$post_id[0].') AS A MATCH FOR REMOTE WIDGET ('.$adunit_id.')' );
				return $post_id[0];
			} else {
				contentAd_append_to_log( '    NO MATCH FOR REMOTE WIDGET ('.$adunit_id.')' );
				return false;
			}
		}

		public static function create_local_widget( $title, $adunit_id, $adunit_name, $aw_guid, $exit_pop, $mobile_exit_pop ) {
			contentAd_append_to_log( 'CREATING LOCAL WIDGET: ' . $title . ' ( REMOTE ID: ' . $adunit_id . ')' );
			self::update_local_widget( false, $title, $adunit_id, $adunit_name, $aw_guid, $exit_pop, $mobile_exit_pop );
		}

		public static function update_local_widget( $post_id = false, $title, $adunit_id, $adunit_name, $aw_guid, $exit_pop, $mobile_exit_pop ) {
			$new = true;
			$new_post_title = true ;
			$new_adunit_id = true ;
			$new_adunit_name = true ;
			$new_aw_guid = true ;
			$new_exit_pop = true ;
			$new_mobile_exit_pop = true ;
			$exit_pop = ($exit_pop) ? true : false ;
			$mobile_exit_pop = ($mobile_exit_pop) ? true : false ;
			
			// If updating existing post
			if( $post_id ) {
				$new = false;
				contentAd_append_to_log( 'UPDATING LOCAL WIDGET: ' . $title . ' ( LOCAL ID: ' . $post_id . ' REMOTE ID: '.$adunit_id.' )' );
				$post = get_post( $post_id );
				$new_post_title = ( isset($post->post_title) && $title == $post->post_title ) ? false : true ;
				$new_adunit_id = ( $adunit_id == get_post_meta( $post_id, '_widget_id', true ) ) ? false : true ;
				$new_adunit_name = ( $adunit_name == get_post_meta( $post_id, '_widget_type', true ) ) ? false : true ;
				$new_aw_guid = ( $aw_guid == get_post_meta( $post_id, '_widget_guid', true ) ) ? false : true ;
				$new_exit_pop = ( $exit_pop == get_post_meta( $post_id, '_widget_exit_pop', true ) ) ? false : true ;
				$new_mobile_exit_pop = ( $mobile_exit_pop == get_post_meta( $post_id, '_widget_mobile_exit_pop', true ) ) ? false : true ;
				if( !$new_post_title && !$new_adunit_id && !$new_adunit_name && !$new_aw_guid && !$new_exit_pop && !$new_mobile_exit_pop ) {
					return $post_id;
				}
			}
			
			// If creating a new post
			if($new_post_title) {
				$postdata = array(
					'ID' => $post_id,
					'post_title' => $title,
					'post_status' => 'publish',
					'post_type' => 'content_ad_widget',
					'ping_status' => false,
					'to_ping' => false,
				);
				$post_id = wp_insert_post( $postdata );
			}
			$placement = get_post_meta( $post_id, 'placement', true );
			if ( $post_id ) {

				/**
				 * New Content.Ad widgets should display on the home, category and tag pages by default.
				 * In addition, they should always default to inactive.
				 */
				if( $new ) {
					update_post_meta( $post_id, '_ca_display_home', 1 );
					update_post_meta( $post_id, '_ca_display_cat_tag', 1 );
					update_post_meta( $post_id, '_ca_widget_inactive', 1 );
				} elseif( !$new && $placement == 'popup_or_mobile_slidup' ) {
					// Deactivate widget since previous'popup_or_mobile_slidup' placement did not display, and so was effectively inactive
					update_post_meta( $post_id, '_ca_widget_inactive', 1 );
				}

				if($new_adunit_id) {
					if( update_post_meta( $post_id, '_widget_id', $adunit_id, true ) ) {
						contentAd_append_to_log( '    UPDATED ADUNIT ID FOR LOCAL WIDGET ('.$post_id.') TO: ' . $adunit_id );
					}
				}
				if($new_adunit_name) {
					if( update_post_meta( $post_id, '_widget_type', $adunit_name, true ) ) {
						contentAd_append_to_log( '    UPDATED WIDGET TYPE ID FOR LOCAL WIDGET ('.$post_id.') TO: ' . $adunit_name );
					}
				}
				if($new_aw_guid) {
					if( update_post_meta( $post_id, '_widget_guid', $aw_guid, true ) ) {
						contentAd_append_to_log( '    UPDATED WIDGET GUID FOR LOCAL WIDGET ('.$post_id.') TO: ' . $aw_guid );
					}
				}
				if( update_post_meta( $post_id, '_widget_exit_pop', $exit_pop, true ) ) {
					contentAd_append_to_log( '    UPDATED EXIT POP FOR LOCAL WIDGET ('.$post_id.') TO: ' . $exit_pop );
				}
				if( update_post_meta( $post_id, '_widget_mobile_exit_pop', $mobile_exit_pop, true ) ) {
					contentAd_append_to_log( '    UPDATED MOBILE EXIT POP FOR LOCAL WIDGET ('.$post_id.') TO: ' . $mobile_exit_pop );
				}
				contentAd_append_to_log( '    CHECKING IF "PLACEMENT" POSTMETA VALUE EXISTS' );
				if( $exit_pop & $new_exit_pop )  {
					if( update_post_meta( $post_id, 'placement', 'in_exit_pop' ) ) {
						contentAd_append_to_log( '        UPDATED WIDGET PLACEMENT FOR LOCAL WIDGET ('.$post_id.') TO: ' . 'in_exit_pop' );
					}
				} elseif( $mobile_exit_pop & $new_mobile_exit_pop )  {
					if( update_post_meta( $post_id, 'placement', 'in_mobile_exit_pop' ) ) {
						contentAd_append_to_log( '        UPDATED WIDGET PLACEMENT FOR LOCAL WIDGET ('.$post_id.') TO: ' . 'in_mobile_exit_pop' );
					}
				} elseif( !$placement || $placement == 'in_exit_pop' || $placement == 'in_mobile_exit_pop' || $placement == 'popup_or_mobile_slidup' ) {
					contentAd_append_to_log( '        POSTMETA VALUE "PLACEMENT" IS NOT SET FOR LOCAL WIDGET ('.$post_id.')' );
					if( update_post_meta( $post_id, 'placement', 'after_post_content' ) ) {
						contentAd_append_to_log( '        UPDATED WIDGET PLACEMENT FOR LOCAL WIDGET ('.$post_id.') TO: ' . 'after_post_content' );
					}
				} else {
					contentAd_append_to_log( '        POSTMETA VALUE "PLACEMENT" FOR LOCAL WIDGET ('.$post_id.'): ' . $placement );
				}
			}
			return $post_id;
		}

		public static function delete_local_widget( $post_id ) {
			$post_type = get_post_type( $post_id );
			if( 'content_ad_widget' == $post_type ) {
				contentAd_append_to_log( 'DELETING LOCAL WIDGET: ' . $post_id );
				wp_delete_post( $post_id, true );
				self::delete_associated_widgets( $post_id );
			}
		}

		public static function delete_associated_widgets( $post_id ) {
			$option_name = 'widget_contentad__includes__widget';
			$widget_option = get_option( $option_name );
            if( $widget_option && is_array( $widget_option ) ) {
                foreach( $widget_option as $index => $widget ) {
                    if( is_int( $index ) && isset( $widget['widget_id'] ) && $widget['widget_id'] == $post_id ) {
                        unset( $widget_option[$index] );
                    }
                }
            }
			update_option( $option_name, $widget_option );
		}

		public static function ajax_edit_widget() {
			contentAd_append_to_log( PHP_EOL . 'AJAX CALL - ' . esc_attr( strtoupper( $_POST['task'] ) ) . ' WIDGET' );
			$response = array(
				'status' => 'error',
				'message' => 'Invalid AJAX call',
			);
			if( defined('DOING_AJAX') && DOING_AJAX ){ // This constant ensures that we are doing ajax
				$response['message'] = 'Invalid nonce';
				if( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'edit_contentad_widget' ) ){
					contentAd_append_to_log( '    AJAX NONCE IS VALID' );
					$post_id = ( isset( $_POST['post_id'] ) && is_int( (int) $_POST['post_id'] ) ) ? (int) $_POST['post_id']: 0;
					contentAd_append_to_log( '    POST_ID: ' . $post_id );
					if( $post_id && 'content_ad_widget' == get_post_type( $post_id ) ) {
						$adunit_id = get_post_meta( $post_id, '_widget_id', true );
						contentAd_append_to_log( '    ADUNIT_ID: ' . $adunit_id );
						if( $adunit_id && isset( $_POST['task'] ) ) {
							switch( $_POST['task'] ) {
								case 'delete':
									ContentAd__Includes__API::delete_ad( $adunit_id );
									self::delete_local_widget( $post_id );
									$response['message'] = 'Widget deleted successfully';
									break;
								case 'pause':
									update_post_meta( $post_id, '_ca_widget_inactive', 1 );
									$response['message'] = 'Widget paused successfully';
									break;
								case 'activate':
									delete_post_meta( $post_id, '_ca_widget_inactive' );
									$response['message'] = 'Widget activated successfully';
									break;
							}
							$response['status'] = 'success';
							$response['post_id'] = $post_id;
							$response['adunit_id'] = $adunit_id;
						} else {
							$response['message'] = 'Adunit ID not set';
						}
					} else {
						$response['message'] = 'Post ID not set';
					}
				}
			}
			header( 'Content: application/json' );
			echo json_encode( $response );
			die;
		}

		public static function get_widgets() {
			$ad_units = ContentAd__Includes__API::get_ad_units();
			if ( is_array( $ad_units ) ) {
				$local_widgets = self::get_local_widgets();
				foreach( $ad_units as $widget ) {
					if ( $post_id = self::get_local_widget_id_by_adunit_id( $widget->adunit_id ) ) {
						self::update_local_widget( $post_id, $widget->description, $widget->adunit_id, $widget->adunit_name, $widget->aw_guid, $widget->exit_pop, $widget->mobile_exit_pop );
						unset( $local_widgets[$post_id] );
					} else {
						self::create_local_widget( $widget->description, $widget->adunit_id, $widget->adunit_name, $widget->aw_guid, $widget->exit_pop, $widget->mobile_exit_pop );
					}
				}
				if( ! empty( $local_widgets ) ) {
					foreach( $local_widgets as $widget ) {
						self::delete_local_widget( $widget->ID );
					}
				}
			}
		}

		public static function wp_head() {
			if( is_singular() && ! is_attachment() ) {
				global $post;
				if( $post && is_object( $post ) && property_exists( $post, 'post_title' ) && property_exists( $post, 'ID' ) ) {
					echo '<meta name="ca_title" content="'. esc_attr( strip_tags( $post->post_title ) ).'" />';
					if( $img_src = ContentAd__Includes__Images::get_primary_image_src( $post->ID ) ) {
						echo '<meta name="ca_image" content="'. esc_url( $img_src ).'" />';
					}
				}
			}
		}

	}
}