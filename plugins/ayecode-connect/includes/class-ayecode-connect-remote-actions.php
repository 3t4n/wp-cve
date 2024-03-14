<?php
/**
 * A class to carryout authenticated remote actions for AyeCode Connect.
 */

/**
 * Bail if we are not in WP.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'AyeCode_Connect_Remote_Actions' ) ) {

	/**
	 * The remote actions for AyeCode Connect
	 */
	class AyeCode_Connect_Remote_Actions {
		/**
		 * The title.
		 *
		 * @var string
		 */
		public $name = 'AyeCode Connect';

		public $prefix = 'ayecode_connect';

		/**
		 * The relative url to the assets.
		 *
		 * @var string
		 */
		public $url = '';

		public $client;
		public $base_url;

		/**
		 * If debuggin is enabled.
		 *
		 * @var
		 */
		public $debug;

		/**
		 * Holds the settings values.
		 *
		 * @var array
		 */
		private $settings;

		/**
		 * AyeCode_UI_Settings instance.
		 *
		 * @access private
		 * @since  1.0.0
		 * @var    AyeCode_Connect_Remote_Actions There can be only one!
		 */
		private static $instance = null;

		/**
		 * Main AyeCode_Connect_Remote_Actions Instance.
		 *
		 * Ensures only one instance of AyeCode_Connect_Remote_Actions is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @static
		 * @return AyeCode_Connect_Remote_Actions - Main instance.
		 */
		public static function instance( $prefix = '', $client = '' ) {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof AyeCode_Connect_Remote_Actions ) ) {
				self::$instance = new AyeCode_Connect_Remote_Actions;

				if ( $prefix ) {
					self::$instance->prefix = $prefix;
				}

				if ( $client ) {
					self::$instance->client = $client;
				}

				self::$instance->debug = defined('AC_DEBUG') && AC_DEBUG ? true : false;

				$remote_actions = array(
					'install_plugin'  => 'install_plugin',
					'update_licences' => 'update_licences',
					'install_theme'   => 'install_theme',
					'update_options'  => 'update_options',
					'import_menus'    => 'import_menus',
					'import_content'  => 'import_content',
				);

				// set php limits
				self::set_php_limits();

				/*
				 * Add any actions in the style of "{$prefix}_remote_action_{$action}"
				 */
				foreach ( $remote_actions as $action => $call ) {
					if ( ! has_action( $prefix . '_remote_action_' . $action, array(
						self::$instance,
						$call
					) )
					) {
						add_action( $prefix . '_remote_action_' . $action, array(
							self::$instance,
							$call
						) ); // set settings
					}

				}

			}

			return self::$instance;
		}

		public function debug_log( $call, $type, $args = array() ){
			$error_str = "AC Debug: $call: $type : ".memory_get_usage()." ";
			if ( ! empty( $args ) ) {
				$error_str .= print_r($args,true);
			}

			if ( $error_str ) {
				error_log($error_str);
			}
		}

		/**
		 * Delete the old categories.
		 *
		 * @param $cpt
		 */
		public function delete_gd_categories($cpt){

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'start' ); }

			$taxonomy = $cpt.'category';
			$terms = get_terms( array(
				'taxonomy' => $taxonomy,
				'hide_empty' => false,
			) );

			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {

					// maybe delete default image and logo
					$attachment_data = get_term_meta( $term->term_id, 'ct_cat_icon', true );
					if ( is_array( $attachment_data ) && ! empty( $attachment_data['id'] ) ) {
						wp_delete_attachment($attachment_data['id'], true);
					}
					$attachment_data = get_term_meta( $term->term_id, 'ct_cat_default_img', true );
					if ( is_array( $attachment_data ) && ! empty( $attachment_data['id'] ) ) {
						wp_delete_attachment($attachment_data['id'], true);
					}

					wp_delete_term( $term->term_id, $taxonomy );
				}

			}

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'end' ); }
		}

		/**
		 * Fully sanitize the category API return.
		 *
		 * @param $categories
		 * @since 1.
		 * @return array
		 */
		public function sanitize_categories( $categories ) {
			$sanitized = array();
			if ( ! empty( $categories ) ) {
				foreach ( $categories as $cpt => $cats ) {
					$cpt = sanitize_title_with_dashes($cpt);
					if ( ! empty( $cats ) ) {
						foreach ( $cats as $key => $cat ) {
							$key = sanitize_title_with_dashes( $key );
							if ( ! empty( $cat['name'] ) ) {
								$sanitized[ $cpt ][ $key ]['name'] = sanitize_title( $cat['name'] );
							}
							if ( ! empty( $cat['icon'] ) ) {
								$sanitized[ $cpt ][ $key ]['icon'] = esc_url_raw( $cat['icon'] );
							}
							if ( ! empty( $cat['default_img'] ) ) {
								$sanitized[ $cpt ][ $key ]['default_img'] = esc_url_raw( $cat['default_img'] );
							}
							if ( ! empty( $cat['font_icon'] ) ) {
								$sanitized[ $cpt ][ $key ]['font_icon'] = sanitize_text_field( $cat['font_icon'] );
							}
							if ( ! empty( $cat['color'] ) ) {
								$sanitized[ $cpt ][ $key ]['color'] = sanitize_hex_color( $cat['color'] );
							}
							if ( ! empty( $cat['demo_post_id'] ) ) {
								$sanitized[ $cpt ][ $key ]['demo_post_id'] = absint( $cat['demo_post_id'] );
							}
						}
					}
				}
			}

			return $sanitized;
		}

		/**
		 * Import content into site.
		 *
		 * @return array
		 */
		public function import_content() {

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'start' ); }

			$result = array( "success" => false );

			// validate
			if ( $this->validate_request() ) {

				// de-sanitize for mod-security
				if ( ! empty( $_REQUEST['categories'] ) ) {
					$_REQUEST['categories'] = str_replace( $this->str_replace_args( true ), $this->str_replace_args( false ), $_REQUEST['categories'] );
				}
				if ( ! empty( $_REQUEST['posts'] ) ) {
					$_REQUEST['posts'] = str_replace( $this->str_replace_args( true ), $this->str_replace_args( false ), $_REQUEST['posts'] );
				}
				if ( ! empty( $_REQUEST['pages'] ) ) {
					$_REQUEST['pages'] = str_replace( $this->str_replace_args( true ), $this->str_replace_args( false ), $_REQUEST['pages'] );
				}

				// categories
				$categories = ! empty( $_REQUEST['categories'] ) ? $this->sanitize_categories( json_decode( stripslashes( $_REQUEST['categories'] ), true ) ) : array();
				$cat_old_and_new = array();

				if ( ! empty( $categories ) && class_exists( 'GeoDir_Admin_Dummy_Data' ) ) {
					foreach ( $categories as $cpt => $cats ) {

						// delete cats
						self::delete_gd_categories($cpt);

						GeoDir_Admin_Dummy_Data::create_taxonomies( $cpt, $cats );
						$tax = new GeoDir_Admin_Taxonomies();
						// set the replacements ids
						foreach ( $cats as $cat ) {
							$term = get_term_by('name', $cat['name'], $cpt.'category');
							if ( isset( $term->term_id ) ) {
								$old_cat_id = absint( $cat['demo_post_id'] );
								$cat_old_and_new[ $old_cat_id ] = absint( $term->term_id );
							}

							// regenerate term icons
							if(method_exists($tax,'regenerate_term_icon'))
							$tax->regenerate_term_icon( $term->term_id );
						}

					}

					update_option('_acdi_replacement_cat_ids',$cat_old_and_new);
				}


				// maybe remove dummy data
				if ( ! empty( $_REQUEST['remove_dummy_data'] ) ) {

					$post_types = geodir_get_posttypes( 'names' );

					if ( ! empty( $post_types ) ) {
						foreach ( $post_types as $post_type ) {
							$table = geodir_db_cpt_table( $post_type );
							if ( $table ) {
								geodir_add_column_if_not_exist( $table, 'post_dummy', "TINYINT(1) NULL DEFAULT '0'" );
							}

							GeoDir_Admin_Dummy_Data::delete_dummy_posts( $post_type );
						}
					}

					// delete any previous posts
					self::delete_demo_posts( 'post' );
					self::delete_demo_posts( 'attachment' );

					// maybe set page featured images
					$fi = get_option('_acdi_page_featured_images');
					if ( ! empty( $fi ) ) {

						foreach($fi as $p => $i){
							$image = (array) GeoDir_Media::get_external_media( $i, '',array('image/jpg', 'image/jpeg', 'image/gif', 'image/png', 'image/webp'),array('ext'=>'png','type'=>'image/png') );

							if(!empty($image['url'])){
								$attachment_id = GeoDir_Media::set_uploaded_image_as_attachment($image);
								if( $attachment_id ){
									set_post_thumbnail($p,$attachment_id );// this will not set if there are dummy posts
									update_post_meta($attachment_id,'_ayecode_demo',1);
								}
							}
						}

						delete_option('_acdi_page_featured_images');
					}

				}


				// posts, note that everything is sanitised further down, wp_insert_post passes everything through sanitize_post()
				$posts = ! empty( $_REQUEST['posts'] ) ? json_decode( stripslashes( $_REQUEST['posts'] ), true ) : array();

				if ( ! empty( $posts ) && class_exists( 'GeoDir_Admin_Dummy_Data' ) ) {

					$hello_world_trashed = false;
					foreach ( $posts as $post_info ) {

						unset( $post_info['ID'] );

						$post_info['post_title'] = wp_strip_all_tags( $post_info['post_title'] ); // WP does not automatically do this
						$post_info['post_status'] = 'publish';
						$post_info['post_dummy']  = '1';
						$post_info['post_author']   = 1;
						// set post data
						$insert_result = wp_insert_post( $post_info, true ); // we hook into the save_post hook

						// maybe insert attachments
						if ( ! is_wp_error( $insert_result ) && $insert_result && ! empty( $post_info['_raw_post_images'] ) ) {
							$this->set_external_media( $insert_result, $post_info['_raw_post_images'] );
						}

						// post stuff
						if($post_info['post_type']=='post' && $insert_result){

							// maybe soft delete original hello world post
							if ( ! $hello_world_trashed ) {
								wp_delete_post(1,false);
								$hello_world_trashed = true;
							}

							// set cats
							$terms = isset($post_info['_cats']) ? $post_info['_cats'] : array();
							$post_terms = array();
							if ( ! empty( $terms ) ) {
								require_once( ABSPATH . '/wp-admin/includes/taxonomy.php');
								foreach($terms as $term_name){
									$term = get_term_by('name', $term_name, 'category');
									if(!empty($term->term_id)){
										$post_terms[] = absint($term->term_id);
									}else{
										$term_name = sanitize_title( $term_name );
										$term_id = wp_create_category($term_name);
										if ( $term_id ) {
											$post_terms[] = absint($term_id);
										}
									}
								}

								if ( ! empty( $post_terms ) ) {
									wp_set_post_categories($insert_result, $post_terms, false);
								}
							}

							// featured image
							$image_url = !empty($post_info['_featured_image_url']) ? esc_url_raw($post_info['_featured_image_url']) : '';

							if ( $image_url ) {
								$image = (array) GeoDir_Media::get_external_media( $image_url, '',array('image/jpg', 'image/jpeg', 'image/gif', 'image/png', 'image/webp'),array('ext'=>'png','type'=>'image/png') );

								if(!empty($image['url'])){
									$attachment_id = GeoDir_Media::set_uploaded_image_as_attachment($image);
									if( $attachment_id ){
										set_post_thumbnail($insert_result,$attachment_id );
										update_post_meta($attachment_id,'_ayecode_demo',1);
									}
								}
							}



						}

					}

				}


				// page templates, note that everything is sanitised further down, wp_insert_post passes everything through sanitize_post()
				$pages = ! empty( $_REQUEST['pages'] ) ? json_decode( stripslashes( $_REQUEST['pages'] ), true ) : array();

				$featured_images_assign = array();
				$old_and_new = array();

				if ( ! empty( $pages ) && function_exists( 'geodir_get_settings' ) ) {

					// remove pages
					self::delete_demo_posts( 'page' );

					// GD page templates
					if ( ! empty( $pages['gd'] ) ) {
						foreach ( $pages['gd'] as $cpt => $page_templates ) {
							if ( ! empty( $page_templates ) ) {
								foreach ( $page_templates as $type => $page ) {
									$post_id = $this->import_page_template( $page, $type, $cpt );
									$old_id = isset($page['demo_post_id']) ? absint( $page['demo_post_id'] ) : '';
									if ( $post_id && $old_id ) {
										$old_and_new[ $old_id ] = $post_id;
									}
								}
							}
						}
					}

					// UWP page templates
					if ( ! empty( $pages['uwp'] ) ) {
						foreach ( $pages['uwp'] as $cpt => $page_templates ) {
							if ( ! empty( $page_templates ) ) {
								foreach ( $page_templates as $type => $page ) {
									$post_id = $this->import_page_template( $page, $type, $cpt );
									$old_id = isset($page['demo_post_id']) ? absint( $page['demo_post_id'] ) : '';
									if ( $post_id && $old_id ) {
										$old_and_new[ $old_id ] = $post_id;
									}
								}
							}
						}
					}


					// WP
					if ( ! empty( $pages['wp'] ) ) {
						foreach ( $pages['wp'] as $type => $page ) {
							$post_id = $this->import_page_template( $page, $type );
							$old_id = isset($page['demo_post_id']) ? absint( $page['demo_post_id'] ) : '';
							if ( $post_id && $old_id ) {
								$old_and_new[ $old_id ] = $post_id;
							}

							// featured image
							$image_url = !empty($page['_featured_image_url']) ? esc_url_raw($page['_featured_image_url']) : '';

							if ( $image_url ) {
								$featured_images_assign[$post_id] = $image_url;
							}


						}

						if ( ! empty( $featured_images_assign ) ) {
							update_option('_acdi_page_featured_images', $featured_images_assign);
						}

					}

					// Elementor @todo add check for elementor pro
					if ( ! empty( $pages['elementor'] ) ) {

						$default_kit_id = get_option( 'elementor_active_kit' );
						$new_kit_id = 0;
						delete_option( 'elementor_active_kit' );
						foreach ( $pages['elementor'] as $cpt => $page_templates ) {

							// remove old demos
							$this->delete_demo_posts( $cpt );

							$archives    = array();
							$items       = array();
							if ( ! empty( $page_templates ) ) {

								foreach ( $page_templates as $page ) {

									$post_id = $this->import_page_template( $page, 'elementor', $cpt );
									if ( $post_id && $page['demo_post_id'] ) {
										$old_id                 = absint( $page['demo_post_id'] );
										$old_and_new[ $old_id ] = $post_id;

										// archives
										if ( ! empty( $page['meta_input']['_elementor_template_type'] ) && $page['meta_input']['_elementor_template_type'] == 'geodirectory-archive' ) {
											$archives[ $old_id ] = absint( $post_id );
										}

										// items
										if ( ! empty( $page['meta_input']['_elementor_template_type'] ) && $page['meta_input']['_elementor_template_type'] == 'geodirectory-archive-item' ) {
											$items[ $old_id ] = absint( $post_id );
										}

										// kit
										if ( ! empty( $page['meta_input']['_elementor_template_type'] ) && $page['meta_input']['_elementor_template_type'] == 'kit' ) {
											$new_kit_id = absint( $post_id );
										}
									}
								}
							}

							if ( $new_kit_id ) {
								update_option( 'elementor_active_kit', $new_kit_id);
							}


							// temp save replace ids
							update_option('_acdi_replacement_post_ids',$old_and_new);
							update_option('_acdi_replacement_archive_item_ids',$items);
							update_option( '_acdi_original_elementor_active_kit', $default_kit_id);

							// extras
							if ( ! empty( $old_and_new ) ) {

								// update the elementor display conditions
								$display_conditions     = get_option( 'elementor_pro_theme_builder_conditions' );
								$new_display_conditions = $display_conditions;
								if ( ! empty( $display_conditions ) ) {
									foreach ( $display_conditions as $type => $condition ) {
										if ( ! empty( $condition ) ) {

											foreach ( $condition as $id => $rule ) {

												if ( isset( $old_and_new[ $id ] ) ) {
													unset( $new_display_conditions[ $type ][ $id ] );
													$new_id                                     = absint( $old_and_new[ $id ] );
													$new_display_conditions[ $type ][ $new_id ] = $rule;
												}
											}
										}
									}
								}
								update_option( 'elementor_pro_theme_builder_conditions', $new_display_conditions );


								// check pages for replaceable data
								if ( ! empty( $old_and_new ) ) {
									foreach ( $old_and_new  as $id ) {
										$this->parse_elementor_data( $id );
									}
								}

							}

						}

						// clear elementor cache after changes
						if ( defined( 'ELEMENTOR_VERSION' ) ) {
							\Elementor\Plugin::$instance->files_manager->clear_cache();
						}

					}


				}


				// set as success
				$result = array( "success" => true );
			}

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'end' ); }

			return $result;
		}

		public function parse_elementor_data($post_id){

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'start' ); }

			$_elementor_data = get_post_meta( $post_id, '_elementor_data', true );
			if ( ! empty( $_elementor_data ) ) {

				$old_and_new = get_option('_acdi_replacement_post_ids');
				$cat_old_and_new = get_option('_acdi_replacement_cat_ids');
				$items = get_option('_acdi_replacement_archive_item_ids');
				$demo_url = get_option('_acdi_demo_url');

				// replace archive item ids
				$original = $_elementor_data;
				if ( ! empty( $items ) ) {
					foreach ( $items as $old_item => $new_item ) {
						$_elementor_data = str_replace(
							array('"gd_archive_custom_skin_template":"' . $old_item . '"',
								'\"gd_archive_custom_skin_template\":\"' . $old_item . '\"',
								'"gd_custom_skin_template":"' . $old_item . '"',
								'\"gd_custom_skin_template\":\"' . $old_item . '\"',
							),
							array('"gd_archive_custom_skin_template":"' . $new_item . '"',
								'\"gd_archive_custom_skin_template\":\"' . $new_item . '\"',
								'"gd_custom_skin_template":"' . $new_item . '"',
								'\"gd_custom_skin_template\":\"' . $new_item . '\"'
							),
							$_elementor_data
						);
					}
				}


				// replace cat ids
				if ( ! empty( $cat_old_and_new ) ) {
					foreach ( $cat_old_and_new as $old_item => $new_item ) {
						$_elementor_data = str_replace(
							array(
								'taxonomy_id%22%3A%22'.$old_item.'%22',
								'taxonomy_id":"'.$old_item.'"'
							),
							array(
								'taxonomy_id%22%3A%22'.$new_item.'%22',
								'taxonomy_id":"'.$new_item.'"'
							),
							$_elementor_data
						);
					}
				}

				// replace URL
				if ( $demo_url ) {
					$_elementor_data = str_replace(
						array(
							$demo_url,
							str_replace('/','\/', $demo_url ),
						),
						array(
							get_home_url(),
							str_replace('/','\/', get_home_url() ),
						),
						$_elementor_data
					);
				}


				if ( $original !== $_elementor_data ) {
					update_post_meta( $post_id, '_elementor_data', wp_slash( $_elementor_data ) );
				}
			}

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'end' ); }

		}

		/**
		 * Delete all dummy posts.
		 *
		 * @param $cpt
		 */
		public function delete_demo_posts( $cpt ) {

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'start' ); }

			// Elementor allow delete kit (without this it throws a confirmation page and blocks import)
			$_GET['force_delete_kit'] = 1;

			$posts = get_posts(
				array(
					'post_type'   => esc_attr( $cpt ),
					'meta_key'    => '_ayecode_demo',
					'meta_value'  => '1',
					'numberposts' => - 1
				)
			);
			if ( ! empty( $posts ) ) {
				foreach ( $posts as $p ) {
					if($p->post_name != 'default-kit'){
						wp_delete_post( $p->ID, true );
					}

				}
			}

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'end' ); }

		}

		/**
		 * Set external attachments.
		 *
		 * @param $post_id
		 * @param $files
		 */
		public function set_external_media( $post_id, $files ) {

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'start' ); }

			if ( ! empty( $files ) && class_exists( 'GeoDir_Media' ) ) {
				$field = !empty($file['type']) ? esc_attr($file['type'] ) : 'post_images';
				foreach ( $files as $file ) {
					$file_url     = ! empty( $file['file'] ) ? esc_url_raw( $file['file'] ) : '';
					$file_title   = ! empty( $file['title'] ) ? esc_attr( $file['title'] ) : '';
					$file_caption = ! empty( $file['caption'] ) ? esc_url_raw( $file['caption'] ) : '';
					$order        = ! empty( $file['menu_order'] ) ? absint( $file['menu_order'] ) : '';
					$other_id     = '';
					$approved     = ! empty( $file['is_approved'] ) ? absint( $file['is_approved'] ) : '';
					$placeholder  = true;
					$metadata     = ! empty( $file['metadata'] ) ? maybe_unserialize( $file['metadata'] ) : '';

					GeoDir_Media::insert_attachment( $post_id, $field, $file_url, $file_title, $file_caption, $order, $approved, $placeholder, $other_id, $metadata );
				}
			}

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'end' ); }

		}


		/**
		 * Import page templates.
		 *
		 * @param $page_template
		 * @param string $type
		 * @param string $cpt
		 *
		 * @return int|WP_Error
		 */
		public function import_page_template( $page_template, $type = '', $cpt = '' ) {

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'start' ); }

			/*
			 * The API can't insert unfiltered HTML which is needed for some page builders, so we allow this here and add the filters back at the end.
			 */
			kses_remove_filters();

			$settings = geodir_get_settings();

			// some meta data may need to be unserialized
			$page_template = (array) $page_template;
			if ( ! empty( $page_template['meta_input'] ) ) {
				foreach ( $page_template['meta_input'] as $key => $val ) {
					// elementor json needs slashed
					if ( $key != '_elementor_data' ) {
						$val = wp_unslash( $val );
					}
					$page_template['meta_input'][$key] = maybe_unserialize( $val );

				}
			}


			$post_id  = 0;
			if ( $type == 'elementor' ) {

				// skip Default kit (maybe we want to update this in future?
				//if( isset($page_template['meta_input']['_elementor_template_type']) && $page_template['meta_input']['_elementor_template_type'] == 'kit' ){return 0;}

				$page_template['post_title']   = wp_strip_all_tags( $page_template['post_title'] );
				$page_template['post_author']   = 1;
				$page_template['post_type']   = $cpt;
				$page_template['post_status'] = 'publish';

				$post_id = wp_insert_post( $page_template, true );

				if ( is_wp_error( $post_id ) ) {
					$error_string = $post_id->get_error_message();
					if ( $this->debug ) { $this->debug_log( __METHOD__, 'post insert error', $error_string ); }
				}else{
					if ( $this->debug ) { $this->debug_log( __METHOD__, 'post inserted', $post_id ); }
				}

				// maybe set tax (not working from wp_insert_post)
				if ( $post_id && ! empty( $page_template['tax_input'] ) ) {

					// default kit
					if(!empty($page_template['meta_input']['active_kit'])){
						update_option( 'elementor_active_kit', $post_id);
					}

					if ( ! function_exists( 'wp_create_term' ) ) {
						include_once ABSPATH . 'wp-admin/includes/taxonomy.php';
					}

					foreach ( $page_template['tax_input'] as $tax => $slug ) {
						$tax  = sanitize_title_with_dashes( $tax );
						$slug = sanitize_title_with_dashes( $slug );
						wp_set_object_terms( $post_id, $slug, $tax );
					}
				}


			} elseif ( $type && $cpt ) {
				$type           = sanitize_title_with_dashes( $type );
				$cpt            = sanitize_title_with_dashes( $cpt );

				// GD
				$page_templates = array(
					'page_add',
					'page_search',
					'page_terms_conditions',
					'page_location',
					'page_archive',
					'page_archive_item',
					'page_details',
				);
				if ( in_array( $type, $page_templates ) ) {
					$page_template = (array) $page_template;

					$current_page_id = 0;
					if ( $cpt == 'core' ) {
						$current_page_id = ! empty( $settings[ $type ] ) ? absint( $settings[ $type ] ) : 0;
					} else {
						$current_page_id = ! empty( $settings['post_types'][ $cpt ][ $type ] ) ? absint( $settings['post_types'][ $cpt ][ $type ] ) : 0;
					}

					if ( false === get_post_status( $current_page_id ) ) {
						// we create a new page
					} else {
//						$page_template['ID'] = absint( $current_page_id );
						// send to trash
						wp_delete_post( absint( $current_page_id ), false );
					}

					$page_template['post_title']   = wp_strip_all_tags( $page_template['post_title'] );
					$page_template['post_type']   = 'page';
					$page_template['post_status'] = 'publish';
					$page_template['post_author'] = 1;
					$post_id                      = wp_insert_post( $page_template, true );


					if ( ! is_wp_error( $post_id ) && $post_id ) {

						if ( $cpt == 'core' ) {
							geodir_update_option( $type, $post_id );
						} else {
							$settings['post_types'][ $cpt ][ $type ] = $post_id;
							geodir_update_option( 'post_types', $settings['post_types'] );
						}

					}

				}


				// UWP
				$page_templates = array(
					'register_page',
					'login_page',
					'account_page',
					'forgot_page',
					'reset_page',
					'change_page',
					'profile_page',
					'users_page',
					'user_list_item_page',
				);
				if ( function_exists('uwp_get_settings') && in_array( $type, $page_templates ) ) {
					$settings = uwp_get_settings();
					$page_template = (array) $page_template;

					$current_page_id = 0;
					if ( $cpt == 'core' ) {
						$current_page_id = ! empty( $settings[ $type ] ) ? absint( $settings[ $type ] ) : 0;
					}

					if ( false === get_post_status( $current_page_id ) ) {
						// we create a new page
					} else {
						//$page_template['ID'] = absint( $current_page_id );
						// send to trash
						wp_delete_post( absint( $current_page_id ), false );
					}

					$page_template['post_title']   = wp_strip_all_tags( $page_template['post_title'] );
					$page_template['post_type']   = 'page';
					$page_template['post_status'] = 'publish';
					$page_template['post_author'] = 1;
					$post_id                      = wp_insert_post( $page_template, true );


					if ( ! is_wp_error( $post_id ) && $post_id ) {

						if ( $cpt == 'core' ) {
							uwp_update_option( $type, $post_id );
						}

					}

				}


			} elseif ( $type == 'page_on_front' ) {

				$current_page_id = get_option( 'page_on_front' );
				if ( false === get_post_status( $current_page_id ) ) {
					// we create a new page
				} else {
//					$page_template['ID'] = absint( $current_page_id );
					// send to trash
					wp_delete_post( absint( $current_page_id ), false );
				}
				$page_template['post_title']   = wp_strip_all_tags( $page_template['post_title'] );
				$page_template['post_type']   = 'page';
				$page_template['post_status'] = 'publish';
				$page_template['post_author'] = 1;

				$post_id = wp_insert_post( $page_template, true );

				if ( ! is_wp_error( $post_id ) && $post_id ) {
					update_option( 'show_on_front', 'page' );
					update_option( 'page_on_front', $post_id );
				}
			}elseif($type && $cpt==''){

				$page_template['post_title']   = wp_strip_all_tags( $page_template['post_title'] );
				$page_template['post_type']   = 'page';
				$page_template['post_status'] = 'publish';
				$page_template['post_author'] = 1;
				$post_id = wp_insert_post( $page_template, true );

				if(!empty($page_template['meta_input']['_page_for_posts'])){
					update_option( 'page_for_posts', $post_id );
				}

			}

			// We add back the filters for security
			kses_init_filters();

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'end' ); }

			return $post_id;
		}


		/**
		 * Import menus.
		 *
		 * @return array
		 */
		public function import_menus() {

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'start' ); }

			$result = array( "success" => false );

			// validate
			if ( $this->validate_request() ) {

				// note, everything is sanitized in import_menu()
				$menus = ! empty( $_REQUEST['menus'] ) ? wp_unslash( $_REQUEST['menus'] ) : array();

				if ( ! empty( $menus ) ) {
					foreach ( $menus as $location => $menu ) {
						$import = $this->import_menu( $location, $menu );
					}
				}


				// set as success
				$result = array( "success" => true );
			}

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'end' ); }

			return $result;
		}

		/**
		 * Import menu.
		 *
		 * @param $location
		 * @param $menu
		 *
		 * @return bool
		 */
		public function import_menu( $location, $menu ) {

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'start' ); }

			$result = false;

			if ( ! empty( $menu ) ) {
				$name = sanitize_title( $menu['name'] );

				// Does the menu exist already?
				$menu_exists = wp_get_nav_menu_object( $name );

				// if it exists with the exact name then lets delete it
				if ( $menu_exists ) {
					wp_delete_nav_menu( $name );
					$menu_exists = false;
				}

				// If it doesn't exist, let's create it.
				if ( ! $menu_exists ) {

					$old_and_new = get_option('_acdi_replacement_post_ids');

					$menu_id = wp_create_nav_menu( $name );

					$locations = get_theme_mod( 'nav_menu_locations' );

					if ( $menu_id ) {
						$locations[ $location ] = $menu_id;
						set_theme_mod( 'nav_menu_locations', $locations );

						if ( ! empty( $menu['items'] ) ) {
							$menu_ids   = array();
							$parent_ids = array();
							foreach ( $menu['items'] as $item ) {
								// unset some things
								$p           = $item['post'];
								$metas       = $item['post_metas'];
								$original_id = absint( $p['ID'] );
								$p['post_author']   = 1;
								unset( $p['ID'] );
								$db_id = wp_insert_post( $p );

								// set id relations
								$menu_ids[ $original_id ] = $db_id;

								if ( $menu_id ) {
									// Associate the menu item with the menu term.
									wp_set_object_terms( $db_id, array( $menu_id ), 'nav_menu' );

									// set meta items
									if ( ! empty( $metas ) ) {
										foreach ( $metas as $key => $meta ) {
											$meta = maybe_unserialize( $meta[0] );
											if ( is_array( $meta ) ) {
												$meta = implode( " ", $meta );
											}

											// set the correct id
											if ( $key == '_menu_item_object_id' ) {
												if($original_id == $meta){
													$meta = absint( $db_id );
												}

												// maybe replace page ids
												if ( ! empty( $old_and_new ) ) {
													foreach ( $old_and_new as $old => $new ) {
														if($meta == $old){
															$meta = $new;
														}
													}
												}

											}

											// set correct parent id
											if ( $key == '_menu_item_menu_item_parent' && ! empty( $meta ) ) {
												$parent_ids[ $db_id ] = absint( $meta );
											}

											// set the correct url for add listing pages
											if ( $key == '_menu_item_url' && ! empty( $meta ) && strpos( $meta, 'listing_type=gd_' ) !== false && function_exists( 'geodir_add_listing_page_url' ) ) {
												$url_parts = explode( "=", $meta );
												if ( ! empty( $url_parts[1] ) ) {
													$meta = geodir_add_listing_page_url( esc_attr( $url_parts[1] ) );
												}

											}

											update_post_meta( $db_id, sanitize_title_with_dashes( $key ), wp_strip_all_tags( $meta ) );
										}
									}
								}

							}

							// set parent ids after insert
							if ( ! empty( $parent_ids ) ) {
								foreach ( $parent_ids as $id => $p_id ) {
									$n_id = ! empty( $menu_ids[ $p_id ] ) ? absint( $menu_ids[ $p_id ] ) : 0;
									if ( $n_id ) {
										update_post_meta( $id, '_menu_item_menu_item_parent', $n_id );
									}
								}
							}
						}

					}

				}

			}

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'end' ); }

			return $result;
		}

		/**
		 * Update site options.
		 *
		 * @return array
		 */
		public function update_options() {

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'start' ); }

			$result = array( "success" => false );

			// validate
			if ( $this->validate_request() ) {

				// de-sanitize for mod-security
				if ( ! empty( $_REQUEST['update'] ) ) {
					$_REQUEST['update'] = str_replace( $this->str_replace_args( true ), $this->str_replace_args( false ), $_REQUEST['update'] );
				}
				if ( ! empty( $_REQUEST['merge'] ) ) {
					$_REQUEST['merge'] = str_replace( $this->str_replace_args( true ), $this->str_replace_args( false ), $_REQUEST['merge'] );
				}
				if ( ! empty( $_REQUEST['delete'] ) ) {
					$_REQUEST['delete'] = str_replace( $this->str_replace_args( true ), $this->str_replace_args( false ), $_REQUEST['delete'] );
				}
				if ( ! empty( $_REQUEST['geodirectory_settings'] ) ) {
					$_REQUEST['geodirectory_settings'] = str_replace( $this->str_replace_args( true ), $this->str_replace_args( false ), $_REQUEST['geodirectory_settings'] );
				}


				// update
				$options = ! empty( $_REQUEST['update'] ) ? json_decode( stripslashes( $_REQUEST['update'] ), true ) : array();

				if ( ! empty( $options ) ) {
					foreach ( $options as $key => $option ) {

						if($key=='custom_css'){
							$option = wp_strip_all_tags( $option );
							$post_css = wp_update_custom_css_post($option);
							if(isset($post_css->ID)){
								set_theme_mod( 'custom_css_post_id', $post_css->ID );
							}
						}


						// theme logo
						if(isset($option['custom_logo_src'])){
							$image = (array) GeoDir_Media::get_external_media( esc_url_raw( $option['custom_logo_src'] ), '',array('image/jpg', 'image/jpeg', 'image/gif', 'image/png', 'image/webp'),array('ext'=>'png','type'=>'image/png') );

							if(!empty($image['url'])){
								$attachment_id = GeoDir_Media::set_uploaded_image_as_attachment($image);
								if( $attachment_id ){
									update_post_meta($attachment_id,'_ayecode_demo_img',1);
									$option['custom_logo'] = $attachment_id;
								}
							}

						}

						if( $this->can_modify_option( $key ) ) {
							update_option( sanitize_title_with_dashes( $key ), $option );
						}
					}

				}

				// merge
				$options = ! empty( $_REQUEST['merge'] ) ? json_decode( stripslashes( $_REQUEST['merge'] ), true ) : array();
				if ( ! empty( $options ) ) {
					foreach ( $options as $key => $option ) {

						$key     = sanitize_title_with_dashes( $key );
						$current = get_option( $key );

						if( $this->can_modify_option( $key ) ) {
							if ( ! empty( $current ) && is_array( $current ) ) {
								update_option( sanitize_title_with_dashes( $key ), array_merge( $current, $option ) );
							} else {
								update_option( sanitize_title_with_dashes( $key ), $option );
							}
						}

					}
				}

				// delete
				$options = ! empty( $_REQUEST['delete'] ) ? json_decode( stripslashes( $_REQUEST['delete'] ), true ) : array();
				if ( ! empty( $options ) ) {
					foreach ( $options as $key => $option ) {
						$key = sanitize_title_with_dashes( $key );
						if( $this->can_modify_option( $key ) ){
							delete_option( $key );
						}
					}
				}


				// GD Settings. Sanitized in save functions
				$settings = ! empty( $_REQUEST['geodirectory_settings'] ) ? json_decode( stripslashes( $_REQUEST['geodirectory_settings'] ), true ) : array();

				if ( ! empty( $settings ) ) {

					// run the create tables function to add our new columns.
					if ( class_exists( 'GeoDir_Admin_Install' ) ) {
						global $geodir_options;
						$geodir_options = geodir_get_settings(); // we need to update the global settings values with the new values.
						GeoDir_Admin_Install::create_tables();

					}

					$this->import_geodirectory_settings( $settings );
				}

				// set as success
				$result = array( "success" => true );
			}

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'end' ); }

			return $result;
		}

		/**
		 * Check if a options key is allowed to be modified.
		 *
		 * @param $key
		 * @since 1.2.6
		 * @return bool
		 */
		public function can_modify_option( $key ){
			$can_modify = false;

			$white_list = array(
				'elementor_pro_theme_builder_conditions',
				'ayecode-ui-settings',
				'aui_options',
				'custom_css',
				'geodir_settings',
				'widget_block',
				'sidebars_widgets',
				'elementor_disable_color_schemes',
				'elementor_disable_typography_schemes',
			);

			if( in_array($key,$white_list) || substr( $key, 0, 11 ) === "theme_mods_" || substr( $key, 0, 7 ) === "widget_" ){
				$can_modify = true;
			}

			return $can_modify;
		}

		/**
		 * Import GeoDirectory custom table settings.
		 *
		 * @param $settings
		 */
		public function import_geodirectory_settings( $settings ) {
			global $wpdb;

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'start' ); }

			// custom_fields
			if ( ! empty( $settings['custom_fields'] ) && defined( 'GEODIR_CUSTOM_FIELDS_TABLE' ) ) {
				// empty the table first
				$wpdb->query( "TRUNCATE TABLE " . GEODIR_CUSTOM_FIELDS_TABLE );

				// insert
				foreach ( $settings['custom_fields'] as $custom_field ) {
					// maybe unserialize and change name
					if ( ! empty( $custom_field['extra_fields'] ) ) {
						$custom_field['extra'] = maybe_unserialize( $custom_field['extra_fields'] );
					}

					// packaged key change
					if ( ! empty( $custom_field['packages'] ) ) {
						$custom_field['show_on_pkg'] = $custom_field['packages'];
					}

					unset( $custom_field['id'] );
					$r = geodir_custom_field_save( $custom_field );

				}

			}

			// sort_fields
			if ( ! empty( $settings['sort_fields'] ) && defined( 'GEODIR_CUSTOM_SORT_FIELDS_TABLE' ) ) {
				// empty the table first
				$wpdb->query( "TRUNCATE TABLE " . GEODIR_CUSTOM_SORT_FIELDS_TABLE );

				// insert
				foreach ( $settings['sort_fields'] as $sort_fields ) {
					GeoDir_Settings_Cpt_Sorting::save_custom_field( $sort_fields );
				}

			}

			// tabs
			if ( ! empty( $settings['tabs'] ) && defined( 'GEODIR_TABS_LAYOUT_TABLE' ) ) {
				// empty the table first
				$wpdb->query( "TRUNCATE TABLE " . GEODIR_TABS_LAYOUT_TABLE );

				// insert
				foreach ( $settings['tabs'] as $tab ) {
					unset( $tab['id'] );// we need insert not update
					GeoDir_Settings_Cpt_Tabs::save_tab_item( $tab );
				}

			}

			// Advanced Search
			if ( ! empty( $settings['search_fields'] ) && defined( 'GEODIR_ADVANCE_SEARCH_TABLE' ) ) {
				// empty the table first
				$wpdb->query( "TRUNCATE TABLE " . GEODIR_ADVANCE_SEARCH_TABLE );

				// insert
				foreach ( $settings['search_fields'] as $search_field ) {

					GeoDir_Adv_Search_Settings_Cpt_Search::save_field( $search_field );
				}

			}

			// price_packages
			if ( ! empty( $settings['price_packages'] ) && defined( 'GEODIR_ADVANCE_SEARCH_TABLE' ) ) {
				// not implemented yet
			}

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'end' ); }

		}

		/**
		 * Update licence info.
		 *
		 * @return array
		 */
		public function update_licences() {
			$result = array( "success" => false );

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'start' ); }

			// validate
			if ( $this->validate_request() ) {
				$result    = array( "success" => true );
				$installed = ! empty( $_REQUEST['installed'] ) ? $this->sanitize_licences( $_REQUEST['installed'] ) : array();
				$all       = ! empty( $_REQUEST['all'] ) ? $this->sanitize_licences( $_REQUEST['all'], true ) : array();
				$site_id   = ! empty( $_REQUEST['site_id'] ) ? absint( $_REQUEST['site_id'] ) : '';
				$site_url  = ! empty( $_REQUEST['site_url'] ) ? esc_url_raw( $_REQUEST['site_url'] ) : '';


				// verify site_id
				if ( $site_id != get_option( $this->prefix . '_blog_id', false ) ) {
					return array( "success" => false );
				}

				// verify site_url
				if ( $site_url && get_option( $this->prefix . "_url" ) ) {
					$changed = $this->client->check_for_url_change( $site_url );
					if ( $changed ) {
						return array( "success" => false );
					}
				}

				// Update licence keys for installed addons
				if ( ! empty( $installed ) && defined( 'WP_EASY_UPDATES_ACTIVE' ) ) {
					$wpeu_admin = new External_Updates_Admin( 'ayecode-connect', AYECODE_CONNECT_VERSION );
					$wpeu_admin->update_keys( $installed );
					$result = array( "success" => true );
				}

				// add all licence keys so new addons can be installed with one click.
				if ( ! empty( $all ) && defined( 'WP_EASY_UPDATES_ACTIVE' ) ) {
					update_option( $this->prefix . "_licences", $all );
				} elseif ( isset( $_REQUEST['all'] ) ) {
					update_option( $this->prefix . "_licences", array() );
				}
			}

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'end' ); }

			return $result;
		}

		/**
		 * Get an array of our valid domains.
		 *
		 * @return array
		 */
		public function get_valid_domains() {
			return array(
				'ayecode.io',
				'wpgeodirectory.com',
				'wpinvoicing.com',
				'userswp.io',
			);
		}

		/**
		 * Sanitize the array of licences.
		 *
		 * @param $licences
		 * @param bool $has_domain This indicates if the licences have another level of array key.
		 *
		 * @return array
		 */
		private function sanitize_licences( $licences, $has_domain = false ) {
			$valid_licences = array();

			if ( ! empty( $licences ) ) {

				// maybe json_decode
				if ( ! is_array( $licences ) ) {
					$licences = stripslashes_deep($licences);
					$licences = json_decode($licences,true);
				}

				if ( $has_domain ) {
					// get the array of valid domains
					$valid_domains = $this->get_valid_domains();

					foreach ( $licences as $domain => $domain_licences ) {
						// Check we have licences and the domain is valid.
						if ( ! empty( $domain_licences ) && in_array( $domain, $valid_domains ) ) {
							foreach ( $domain_licences as $plugin => $licence ) {
								$maybe_valid = (object) $this->validate_licence( $licence );
								if ( ! empty( $maybe_valid ) ) {
									$plugin                               = absint( $plugin ); // this is the plugin product id.
									$valid_licences[ $domain ][ $plugin ] = $maybe_valid;
								}
							}
						}
					}
				} else {
					foreach ( $licences as $plugin => $licence ) {
						$maybe_valid = (object) $this->validate_licence( $licence );
						if ( ! empty( $maybe_valid ) ) {
							$plugin                    = sanitize_text_field( $plugin ); // non domain this is a string
							$valid_licences[ $plugin ] = $maybe_valid;
						}
					}
				}
			}

			return $valid_licences;
		}

		/**
		 * Validate and sanitize licence info.
		 *
		 * @param $licence
		 *
		 * @return array
		 */
		private function validate_licence( $licence ) {
			$valid = array();

			if ( ! empty( $licence ) && is_array( $licence ) && ! empty( $licence['license_key'] ) ) {
				// key
				if ( isset( $licence['license_key'] ) ) {
					$valid['key'] = sanitize_title_with_dashes( $licence['license_key'] );
				}
				// status
				if ( isset( $licence['status'] ) ) {
					$valid['status'] = $this->validate_licence_status( $licence['status'] );
				}
				// download_id
				if ( isset( $licence['download_id'] ) ) {
					$valid['download_id'] = absint( $licence['download_id'] );
				}
				// price_id
				if ( isset( $licence['price_id'] ) ) {
					$valid['price_id'] = absint( $licence['price_id'] );
				}
				// payment_id
				if ( isset( $licence['payment_id'] ) ) {
					$valid['payment_id'] = absint( $licence['payment_id'] );
				}
				// expires
				if ( isset( $licence['expiration'] ) ) {
					$valid['expires'] = absint( $licence['expiration'] );
				}
				// parent
				if ( isset( $licence['parent'] ) ) {
					$valid['parent'] = absint( $licence['parent'] );
				}
				// user_id
				if ( isset( $licence['user_id'] ) ) {
					$valid['user_id'] = absint( $licence['user_id'] );
				}
			}

			return $valid;
		}

		/**
		 * Validate the licence status.
		 *
		 * @param $status
		 *
		 * @return string
		 */
		public function validate_licence_status( $status ) {

			// possible statuses
			$valid_statuses = array(
				'active',
				'inactive',
				'expired',
				'disabled',
			);

			// set empty if not a valid status
			if ( ! in_array( $status, $valid_statuses ) ) {
				$status = '';
			}

			return $status;
		}

		/**
		 * Validate the request origin.
		 *
		 * This file is not even loaded unless it passes JWT validation.
		 *
		 * @return bool
		 */
		private function validate_request() {
			$result = false;

			if ( $this->get_server_ip() === "173.208.153.114" ) {
				$result = true;
			}

			return $result;
		}

		/**
		 * Get the request has come from our server.
		 *
		 * @return string
		 */
		private function get_server_ip() {

			if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
				//check ip from share internet
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
				//to check ip is pass from proxy
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}

			// Cloudflare can provide a comma separated ip list
			if ( strpos( $ip, ',' ) !== false ) {
				$ip = reset( explode( ",", $ip ) );
			}

			return $ip;
		}


		/**
		 * Validate a download url is from our own server: 173.208.153.114
		 *
		 * @param $url
		 *
		 * @return bool
		 */
		private function validate_download_url( $url ) {
			$result = false;

			if ( $url ) {
				$parse = parse_url( $url );
				if ( ! empty( $parse['host'] ) ) {
					$ip = gethostbyname( $parse['host'] );
					if ( $ip === "173.208.153.114" ) { // AyeCode.io Server
						$result = true;
					} elseif ( $ip === "198.143.164.252" ) { // wordpress.org server
						$result = true;
					}
				}
			}

			return $result;
		}

		/**
		 * Install plugin.
		 *
		 * @param $result
		 *
		 * @return mixed
		 */
		public function install_plugin( $result ) {

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'start' ); }

			// validate
			if ( ! $this->validate_request() ) {
				return array( "success" => false );
			}

			include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' ); //for plugins_api..

			$plugin_slug = isset( $_REQUEST['slug'] ) ? sanitize_title_for_query( $_REQUEST['slug'] ) : '';
			$plugin      = array(
				'name'             => isset( $_REQUEST['name'] ) ? esc_attr( $_REQUEST['name'] ) : '',
				'repo-slug'        => $plugin_slug,
				'file-slug'        => isset( $_REQUEST['file-slug'] ) ? sanitize_title_for_query( $_REQUEST['file-slug'] ) : '',
				'download_link'    => isset( $_REQUEST['download_link'] ) ? esc_url_raw( $_REQUEST['download_link'] ) : '',
				'activate'         => isset( $_REQUEST['activate'] ) && $_REQUEST['activate'] ? true : false,
				'network_activate' => isset( $_REQUEST['network_activate'] ) && $_REQUEST['network_activate'] ? true : false,
			);

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'plugin', $plugin ); }
			if ( $this->debug ) { $this->debug_log( __METHOD__, 'plugin_request', $_REQUEST ); }

			$install = $this->background_installer( $plugin_slug, $plugin );

			if ( $install ) {
				$result = array( "success" => true );
			}

			if ( $this->debug ) { $this->debug_log( __METHOD__, 'end' ); }

			return $result;
		}


		/**
		 * Get slug from path
		 *
		 * @param  string $key
		 *
		 * @return string
		 */
		private function format_plugin_slug( $key ) {
			$slug = explode( '/', $key );
			$slug = explode( '.', end( $slug ) );

			return $slug[0];
		}

		/**
		 * Install a plugin from .org in the background via a cron job (used by
		 * installer - opt in).
		 *
		 * @param string $plugin_to_install_id
		 * @param array $plugin_to_install
		 *
		 * @since 2.6.0
		 *
		 * @return bool
		 */
		public function background_installer( $plugin_to_install_id, $plugin_to_install ) {


			if ( $this->debug ) { $this->debug_log( __METHOD__, 'start' ); }
			if ( $this->debug ) { $this->debug_log( __METHOD__, 'args', $plugin_to_install ); }

			$task_result = false;
			if ( ! empty( $plugin_to_install['repo-slug'] ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
				require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

				WP_Filesystem();

				$skin              = new Automatic_Upgrader_Skin;
				$upgrader          = new WP_Upgrader( $skin );
				$installed_plugins = array_map( array( $this, 'format_plugin_slug' ), array_keys( get_plugins() ) );
				$plugin_slug       = $plugin_to_install['repo-slug'];
				$plugin_file_slug  = ! empty( $plugin_to_install['file-slug'] ) ? $plugin_to_install['file-slug'] : $plugin_slug;
				$plugin            = $plugin_slug . '/' . $plugin_file_slug . '.php';
				$installed         = false;
				$activate          = isset( $plugin_to_install['activate'] ) && $plugin_to_install['activate'] ? true : false;
				$network_activate  = isset( $plugin_to_install['network_activate'] ) && $plugin_to_install['network_activate'] ? true : false;

				// See if the plugin is installed already
				if ( in_array( $plugin_to_install['repo-slug'], $installed_plugins ) ) {
					$installed = true;
				}

				// Install this thing!
				if ( ! $installed ) {
					// Suppress feedback
					ob_start();

					try {

						// if a download link is provided then validate it.
						if ( ! empty( $plugin_to_install['download_link'] ) ) {

							if ( ! $this->validate_download_url( $plugin_to_install['download_link'] ) ) {
								return new WP_Error( 'download_invalid', __( "Download source not valid.", "ayecode-connect" ) );
							}

							$plugin_information = (object) array(
								'name'          => esc_attr( $plugin_to_install['name'] ),
								'slug'          => esc_attr( $plugin_to_install['repo-slug'] ),
								'download_link' => esc_url( $plugin_to_install['download_link'] ),
							);
						} else {
							if ( $this->debug ) { $this->debug_log( __METHOD__, 'plugin-slug',$plugin_to_install['repo-slug'] ); }
							$plugin_information = plugins_api( 'plugin_information', array(
								'slug'   => $plugin_to_install['repo-slug'],
								'fields' => array(
									'short_description' => false,
									'sections'          => false,
									'requires'          => false,
									'rating'            => false,
									'ratings'           => false,
									'downloaded'        => false,
									'last_updated'      => false,
									'added'             => false,
									'tags'              => false,
									'homepage'          => false,
									'donate_link'       => false,
									'author_profile'    => false,
									'author'            => false,
								),
							) );
						}

						if ( is_wp_error( $plugin_information ) ) {
							throw new Exception( $plugin_information->get_error_message() );
						}

						if ( $this->debug ) { $this->debug_log( __METHOD__, 'plugin-info' ); }

						$package  = $plugin_information->download_link;
						$download = $upgrader->download_package( $package );

						if ( is_wp_error( $download ) ) {
							throw new Exception( $download->get_error_message() );
						}
						if ( $this->debug ) { $this->debug_log( __METHOD__, 'plugin-downloaded' ); }

						$working_dir = $upgrader->unpack_package( $download, true );

						if ( is_wp_error( $working_dir ) ) {
							throw new Exception( $working_dir->get_error_message() );
						}


						$result = $upgrader->install_package( array(
							'source'                      => $working_dir,
							'destination'                 => WP_PLUGIN_DIR,
							'clear_destination'           => false,
							'abort_if_destination_exists' => false,
							'clear_working'               => true,
							'hook_extra'                  => array(
								'type'   => 'plugin',
								'action' => 'install',
							),
						) );
						if ( $this->debug ) { $this->debug_log( __METHOD__, 'plugin-install', print_r($result, true) ); }

						if ( ! is_wp_error( $result ) ) {
							$task_result = true;
						}

//						$activate = true;

					} catch ( Exception $e ) {
//
					}

					// Discard feedback
					ob_end_clean();
				}

				wp_clean_plugins_cache();

				// Activate this thing
				if ( $activate ) {
					try {
						if ( $this->debug ) { $this->debug_log( __METHOD__, 'activate_plugin', $plugin ); }


						$result = activate_plugin( $plugin, "", $network_activate );

						if ( $this->debug ) { $this->debug_log( __METHOD__, 'plugin-activate', print_r($result, true) ); }

						if ( ! is_wp_error( $result ) ) {
							$task_result = true;
						}
					} catch ( Exception $e ) {
						$task_result = false;
					}
				}
			}

			return $task_result;
		}

		/**
		 * Install theme.
		 *
		 * @param $result
		 *
		 * @return mixed
		 */
		public function install_theme( $result ) {
			// validate
			if ( ! $this->validate_request() ) {
				return array( "success" => false );
			}

			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			include_once ABSPATH . 'wp-admin/includes/theme.php';

			$slug          = isset( $_REQUEST['slug'] ) ? sanitize_title_for_query( $_REQUEST['slug'] ) : '';
			$download_link = ! empty( $_REQUEST['download_link'] ) ? esc_url_raw( $_REQUEST['download_link'] ) : '';


			if ( empty( $download_link ) ) {
				$api = themes_api(
					'theme_information',
					array(
						'slug'   => $slug,
						'fields' => array( 'sections' => false ),
					)
				);

				if ( is_wp_error( $api ) ) {
					array( "success" => false );
				}

				$download_link = $api->download_link;

			}


			$skin     = new WP_Ajax_Upgrader_Skin();
			$upgrader = new Theme_Upgrader( $skin );
			$install  = $upgrader->install( $download_link );

			if ( $install ) {
				$result = array( "success" => true );
			}

			return $result;
		}

		/**
		 * Try to set higher limits on the fly
		 */
		public static function set_php_limits() {
//			if ( ! ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ) {
//				error_reporting( 0 );
//			}
//			@ini_set( 'display_errors', 0 );

			// try to set higher limits for import
			$max_input_time     = ini_get( 'max_input_time' );
			$max_execution_time = ini_get( 'max_execution_time' );
			$memory_limit       = ini_get( 'memory_limit' );

			if ( $max_input_time !== 0 && $max_input_time != -1 && ( ! $max_input_time || $max_input_time < 3000 ) ) {
				ini_set( 'max_input_time', 3000 );
			}

			if ( $max_execution_time !== 0 && ( ! $max_execution_time || $max_execution_time < 3000 ) ) {
				ini_set( 'max_execution_time', 3000 );
			}

			if ( $memory_limit && str_replace( 'M', '', $memory_limit ) ) {
				if ( str_replace( 'M', '', $memory_limit ) < 256 ) {
					ini_set( 'memory_limit', '256M' );
				}
			}

			ini_set( 'auto_detect_line_endings', true );
		}

		/**
		 * Arguments for replacing mod-security triggers.
		 *
		 * @todo This is mirrored in the AyeCode Connect plugin and changes should be added there also if updating.
		 *
		 * @param $values
		 *
		 * @return array
		 */
		public function str_replace_args( $values = false ) {
			$salt = 'ZXY';
			$args = array(
				'VARCHAR' => 'VARCHAR' . $salt,
				'TEXT'    => 'TEXT' . $salt,
				'SELECT'  => 'SELECT' . $salt,
				'Select'  => 'Select' . $salt,
				'select'  => 'select' . $salt,
				'FROM'    => 'FROM' . $salt,
				'TINYINT' => 'TINYINT' . $salt,
				'FLOAT'   => 'FLOAT' . $salt,
				'INT'     => 'INT' . $salt,
				'-->'     => '--' . $salt . '>',
				'<!--'     => '<' . $salt . '!--',
				'javascript'     => 'javsrpt' . $salt,
			);

			return $values ? array_values( $args ) : array_keys( $args );
		}


	}

}