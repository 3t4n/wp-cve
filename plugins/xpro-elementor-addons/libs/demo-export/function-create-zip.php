<?php
/**
 * Creates a zip file from folder and download.
 *
 * @since    1.0.0
 */

use Elementor\Plugin;

if ( ! function_exists( 'xpro_elementor_demo_export_create_zip' ) ) {
	function xpro_elementor_demo_export_create_zip( $source, $wp_filesystem ) {

		/*Check if Zip Extension Installed*/
		if ( ! class_exists( 'ZipArchive' ) ) {
			die( esc_html__( 'ZIP extension is not installed, please install ZIP extension on your host or contact to your hosting provider and try again!', 'xpro-elementor-addons' ) );
		}

		$zip          = new ZipArchive();
		$zip_filename = esc_attr( get_option( 'template' ) ) . '-data';
		$zip->open( $zip_filename, ZipArchive::CREATE && ZipArchive::OVERWRITE );

		/*Create recursive directory iterator */
		$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $source ),
			RecursiveIteratorIterator::LEAVES_ONLY
		);
		foreach ( $files as $name => $file ) {
			/*Skip directories (they would be added automatically)*/
			if ( ! $file->isDir() ) {
				/*Get real and relative path for current file*/
				$filePath     = $file->getRealPath();
				$relativePath = substr( $filePath, strlen( $source ) );

				/*Add current file/directory to archive*/
				$zip->addFile( $filePath, $relativePath );
			}
		}
		$zip->close();

		header( 'Content-type: application/zip' );
		header( sprintf( 'Content-Disposition: attachment; filename="%s.zip"', $zip_filename ) );
		readfile( $zip_filename );

		/*delete temp zip files*/
		$wp_filesystem->rmdir( $zip_filename, true );
		$wp_filesystem->rmdir( $source, true );
		die();
	}
}

/**
 * Writes json files
 *
 * @since    1.0.0
 */
if ( ! function_exists( 'xpro_elementor_demo_export_create_data_files' ) ) {
	function xpro_elementor_demo_export_create_data_files( $form_args ) {

		$defaults  = array(
			'content'       => 'all',
			'author'        => false,
			'category'      => false,
			'start_date'    => false,
			'end_date'      => false,
			'status'        => false,
			'include_media' => false,
			'widgets_data'  => false,
			'options_data'  => false,
			'settings_data' => false,
		);
		$form_args = wp_parse_args( $form_args, $defaults );

		/**
		 * Export hook
		 */
		do_action( 'xpro_elementor_demo_export_before_create_data_files', $form_args );

		$content_data = array();
		WP_Filesystem();
		global $wp_filesystem;
		if ( ! file_exists( XPRO_ELEMENTOR_ADDONS_TEMP ) ) {
			$wp_filesystem->mkdir( XPRO_ELEMENTOR_ADDONS_TEMP );
		}
		if ( 1 == $form_args['include_media'] ) {
			if ( ! file_exists( XPRO_ELEMENTOR_ADDONS_TEMP_UPLOADS ) ) {
				$wp_filesystem->mkdir( XPRO_ELEMENTOR_ADDONS_TEMP_UPLOADS );
			}
		}

		/*default post types*/
		$post_types = array( 'attachment', 'post', 'page', 'nav_menu_item' );

		if ( 'all' != $form_args['content'] && post_type_exists( $form_args['content'] ) ) {
			$post_type_object = get_post_type_object( $form_args['content'] );
			if ( $post_type_object->can_export ) {
				$post_types = array( $form_args['content'] );
			}
		} else {
			$post_types = get_post_types( array( 'can_export' => true ) );
		}

		$taxonomies = get_taxonomies();

		/*ignore post type*/
		$ignore_post_types = apply_filters( 'xpro_elementor_demo_export_ignore_post_types', array( 'revision' ) );
		foreach ( $post_types as $post_type ) {

			/*ignore post type*/
			if ( in_array( $post_type, $ignore_post_types, true ) ) {
				continue;
			}
			/*default args*/
			$args = array(
				'post_type'      => $post_type,
				'posts_per_page' => - 1,
			);

			/*setting post status*/
			if ( $form_args['status'] && ( 'post' == $post_type || 'page' == $post_type ) ) {
				$args['post_status'] = $form_args['status'];
			} else {
				$args['post_status'] = 'any';
			}

			/*setting category*/
			if ( $form_args['category'] && 'post' == $post_type ) {
				if ( $term = term_exists( $form_args['category'], 'category' ) ) {
					$args['cat'] = $term['term_taxonomy_id'];
				}
			}

			/*setting date and author*/
			if ( 'post' == $post_type || 'page' == $post_type || 'attachment' == $post_type ) {
				if ( $form_args['author'] ) {
					$args['author'] = $form_args['post_author'];
				}
				if ( $form_args['start_date'] && $form_args['end_date'] ) {
					$args['date_query'] = array(
						'after'     => $form_args['start_date'],
						'before'    => $form_args['end_date'],
						'inclusive' => true,
					);
				}
			}

			/*now get posts*/
			$post_type_data = get_posts( $args );
			if ( ! isset( $content_data[ $post_type ] ) ) {
				$content_data[ $post_type ] = array();
			}

			$object = get_post_type_object( $post_type );

			if ( $object && isset( $object->labels->name ) && ! empty( $object->labels->name ) ) {
				$type_title = $object->labels->name;
			} else {
				$type_title = ucwords( $post_type );
			}

			foreach ( $post_type_data as $single_post_data ) {

				/*get all post meta*/
				$post_meta_data = get_post_meta( $single_post_data->ID, '', true );

				foreach ( $post_meta_data as $post_meta_data_key => $post_meta_data_val ) {
					$post_meta_data[ $post_meta_data_key ] = maybe_unserialize( get_post_meta( $single_post_data->ID, $post_meta_data_key, true ) );
				}

				/*copy save images in exported folder if include media*/
				if ( 1 == $form_args['include_media'] && $post_type == 'attachment' ) {
					$file = get_attached_file( $single_post_data->ID );
					if ( is_file( $file ) ) {
						if ( is_dir( XPRO_ELEMENTOR_ADDONS_TEMP_UPLOADS ) ) {
							copy( $file, XPRO_ELEMENTOR_ADDONS_TEMP_UPLOADS . basename( $file ) );
						}
					}
				}

				/*process terms*/
				$terms_data = array();
				foreach ( $taxonomies as $taxonomy ) {
					$terms_data[ $taxonomy ] = wp_get_post_terms( $single_post_data->ID, $taxonomy, array( 'fields' => 'all' ) );
					if ( $terms_data[ $taxonomy ] ) {
						foreach ( $terms_data[ $taxonomy ] as $tax_id => $single_term ) {
							if ( ! empty( $single_term->term_id ) ) {
								$terms_data[ $taxonomy ][ $tax_id ]->meta = get_term_meta( $single_term->term_id );
								if ( ! empty( $terms_data[ $taxonomy ][ $tax_id ]->meta ) ) {
									foreach ( $terms_data[ $taxonomy ][ $tax_id ]->meta as $key => $val ) {
										if ( is_array( $val ) && count( $val ) == 1 && isset( $val[0] ) ) {
											$terms_data[ $taxonomy ][ $tax_id ]->meta[ $key ] = $val[0];
										}
									}
								}
							}
						}
					}
				}

				$content_data[ $post_type ][] = array(
					'type_title'     => $type_title,
					'post_id'        => $single_post_data->ID,
					'post_title'     => $single_post_data->post_title,
					'post_status'    => $single_post_data->post_status,
					'post_name'      => $single_post_data->post_name,
					'post_content'   => $single_post_data->post_content,
					'post_excerpt'   => $single_post_data->post_excerpt,
					'post_parent'    => $single_post_data->post_parent,
					'menu_order'     => $single_post_data->menu_order,
					'post_date'      => $single_post_data->post_date,
					'post_date_gmt'  => $single_post_data->post_date_gmt,
					'guid'           => $single_post_data->guid,
					'post_mime_type' => $single_post_data->post_mime_type,
					'meta'           => $post_meta_data,
					'terms'          => $terms_data,
				);
			}
		}
		/*Array adjustment*/
		/*put attachment at first*/
		$attachment = isset( $content_data['attachment'] ) ? $content_data['attachment'] : array();
		if ( $attachment ) {
			unset( $content_data['attachment'] );
			$content_data = array( 'attachment' => $attachment ) + $content_data;
		}
		/*Put post 3nd last*/
		$post = isset( $content_data['post'] ) ? $content_data['post'] : array();
		if ( $post ) {
			unset( $content_data['post'] );
			$content_data['post'] = $post;
		}
		/*Put page 2nd last*/
		$page = isset( $content_data['page'] ) ? $content_data['page'] : array();
		if ( $page ) {
			unset( $content_data['page'] );
			$content_data['page'] = $page;
		}
		/*Put nav last*/
		$nav = isset( $content_data['nav_menu_item'] ) ? $content_data['nav_menu_item'] : array();
		if ( $nav ) {
			unset( $content_data['nav_menu_item'] );
			$content_data['nav_menu_item'] = $nav;
		}

		/*export widget settings.*/
		if ( 1 == $form_args['widgets_data'] ) {
			$sidebars_widgets = get_option( 'sidebars_widgets' );
			$widget_data      = array();
			foreach ( $sidebars_widgets as $sidebar_name => $widgets ) {
				if ( is_array( $widgets ) ) {
					foreach ( $widgets as $widget_name ) {
						$widget_name_strip                 = preg_replace( '#-\d+$#', '', $widget_name );
						$widget_data[ $widget_name_strip ] = get_option( 'widget_' . $widget_name_strip );
					}
				}
			}
		}

		/*export options and nav*/
		if ( 1 == $form_args['options_data'] ) {
			/*nav menu data*/
			$menus           = get_terms( 'nav_menu' );
			$theme_locations = get_nav_menu_locations();
			$menu_data       = array();
			foreach ( $menus as $menu ) {
				foreach ( $theme_locations as $key => $theme_location ) {
					if ( $menu->term_id == $theme_location ) {
						$menu_data[ $key ] = $menu->term_id;
					}
				}
			}

			/*get all options*/
			$all_options    = wp_load_alloptions();
			$theme_mode     = 'theme_mods_' . get_option( 'template' );
			$options_data   = array();
			$needed_options = array(
				'blogname',
				'blogdescription',
				'posts_per_page',
				'date_format',
				'time_format',
				'show_on_front',
				'thumbnail_size_w',
				'thumbnail_size_h',
				'thumbnail_crop',
				'medium_size_w',
				'medium_size_h',
				'medium_large_size_w',
				'medium_large_size_h',
				'avatar_default',
				'large_size_w',
				'large_size_h',
				'page_for_posts',
				'page_on_front',
				'woocommerce_shop_page_id',
				'woocommerce_cart_page_id',
				'woocommerce_checkout_page_id',
				'woocommerce_myaccount_page_id',
				'page_on_front',
				'show_on_front',
				'page_for_posts',
				$theme_mode,
			);
			if ( is_child_theme() ) {
				$needed_options[] = 'theme_mods_' . get_option( 'stylesheet' );
			}

			$needed_options = apply_filters( 'xpro_elementor_demo_export_include_options', $needed_options );

			foreach ( $all_options as $name => $value ) {
				if ( apply_filters( 'xpro_elementor_demo_export_all_options', false ) ) {
					$options_data[ $name ]            = maybe_unserialize( $value );
					$options_data[ $name . '-child' ] = maybe_unserialize( $value );
				} elseif ( in_array( $name, $needed_options, true ) ) {
					$options_data[ $name ]            = maybe_unserialize( $value );
					$options_data[ $name . '-child' ] = maybe_unserialize( $value );
				}
				if ( $name == $theme_mode ) {
					unset( $options_data[ $name ]['nav_menu_locations'] );
				}
			}
		}

		/*prepare files for zip*/
		if ( is_dir( XPRO_ELEMENTOR_ADDONS_TEMP ) ) {

			/*content*/
			$wp_filesystem->put_contents( XPRO_ELEMENTOR_ADDONS_TEMP . 'content.json', wp_json_encode( $content_data ) );

			/*widgets*/
			if ( 1 == $form_args['widgets_data'] ) {
				$combine_widgets_data = array();
				if ( ! empty( $sidebars_widgets ) ) {
					$combine_widgets_data['widget_positions'] = $sidebars_widgets;
				}
				if ( ! empty( $widget_data ) ) {
					$combine_widgets_data['widget_options'] = $widget_data;
				}
				$wp_filesystem->put_contents( XPRO_ELEMENTOR_ADDONS_TEMP . 'widgets.json', wp_json_encode( $combine_widgets_data ) );
			}

			/*options/customizer*/
			if ( 1 == $form_args['options_data'] ) {
				$combine_options_data = array();
				if ( ! empty( $menu_data ) ) {
					$combine_options_data['menu'] = $menu_data;
				}
				if ( ! empty( $options_data ) ) {
					$combine_options_data['options'] = $options_data;
				}
				$wp_filesystem->put_contents( XPRO_ELEMENTOR_ADDONS_TEMP . 'options.json', wp_json_encode( $combine_options_data ) );
			}

			/*site settings*/
			if ( 1 == $form_args['settings_data'] ) {
				$combine_settings_data = array();
				$kit                   = Plugin::$instance->kits_manager->get_active_kit();
				if ( $kit->get_id() ) {
					$combine_settings_data = $kit->get_export_data();
				}
				$wp_filesystem->put_contents( XPRO_ELEMENTOR_ADDONS_TEMP . 'settings.json', wp_json_encode( $combine_settings_data ) );
			}
		}
		xpro_elementor_demo_export_create_zip( XPRO_ELEMENTOR_ADDONS_TEMP, $wp_filesystem );
	}
}
if ( ! function_exists( 'xpro_elementor_demo_export_ziparchive' ) ) {
	function xpro_elementor_demo_export_ziparchive( $form_args ) {
		xpro_elementor_demo_export_create_data_files( $form_args );
	}
}
