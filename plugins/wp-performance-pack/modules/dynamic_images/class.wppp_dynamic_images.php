<?php
/**
 * Don't generate intermediate images on upload, but on first access.
 * Image creation is done via serve-dynamic-images.php.
 *
 * @author Björn Ahrens
 * @package WP Performance Pack
 * @since 1.1
 */

class WPPP_Dynamic_Images extends WPPP_Module {
	public static $rewrite_regex = '(.+)-([0-9]+x[0-9]+)\.((?i)jpeg|jpg|png|gif)';

	public $rw_folder = false;
	public $rw_inherit = false;
	public $rw_method = 'use_themes';
	public $rw_enabled = false;

	public function load_renderer () {
		return new WPPP_Dynamic_Images_Advanced ( $this->wppp );
	}

	public function is_available () {
		global $wp_rewrite;
		if ( is_multisite() ) {
			if ( ! function_exists( 'is_plugin_active_for_network' ) )
				require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
			return $wp_rewrite->using_mod_rewrite_permalinks() && $this->wppp->is_network;
		} else {
			return $wp_rewrite->using_mod_rewrite_permalinks();
		}
	}

	public function validate_options( &$input, $output, $default ) {
		$output = parent::validate_options( $input, $output, $default );

		if ( ( $this->wppp->options[ 'dynamic_images' ] !== $output[ 'dynamic_images' ] )
			 || ( ( $this->wppp->options[ 'dynamic_images_thumbfolder' ] !== $output[ 'dynamic_images_thumbfolder' ] ) && !$output[ 'dynamic_images_nosave' ] )
			 || ( ( $this->wppp->options[ 'dynamic_images_nosave' ] !== $output[ 'dynamic_images_nosave' ] ) && $output[ 'dynamic_images_thumbfolder' ] )
			 || ( $this->wppp->options[ 'rewrite_inherit' ] !== $output[ 'rewrite_inherit' ] ) 
			 || ( $this->wppp->options[ 'dynimg_serve_method' ] !== $output[ 'dynimg_serve_method' ] ) ) {
			// save in class variables as changed options won't yet be available in filter mod_rewrite_rules
			$this->rw_enabled = $output[ 'dynamic_images' ];
			$this->rw_inherit = $output[ 'rewrite_inherit' ];
			$this->rw_folder = $output[ 'dynamic_images_thumbfolder' ] && !$output[ 'dynamic_images_nosave' ];
			$this->rw_method = $output[ 'dynimg_serve_method' ];
			$this->flush_rewrite_rules( $output[ 'dynamic_images' ] && $output[ 'dynimg_serve_method' ] !== 'wordpress' );
		}
		return $output;
	}
	
	function early_init () {
		if ( $this->wppp->is_network && $this->wppp->options[ 'dynimg_serve_method' ] !== 'wordpress' ) {
			add_action( 'setup_theme',  array( $this, 'replace_wp_rewrite' ) );
		}
	}

	function init () {
		if ( $this->wppp->options[ 'dynamic_images' ] ) {
			$this->rw_folder = $this->wppp->options[ 'dynamic_images_thumbfolder' ];
			$this->rw_enabled = $this->wppp->options[ 'dynamic_images' ];
			$this->rw_inherit = $this->wppp->options[ 'rewrite_inherit' ];
			$this->rw_method = $this->wppp->options[ 'dynimg_serve_method' ];
			$this->set_rewrite_rules();
			// set to very low priority, so it is hopefully called last as this overrides previously registered editors
			add_filter( 'wp_image_editors', array ( $this, 'filter_wp_image_editor' ), 1000, 1 ); 
			// dynamically create available sizes. needed e.g. to select image size when inserting an image in a post.
			add_filter( 'wp_get_attachment_metadata', array( $this, 'filter_wp_get_attachment_metadata' ) );

			if ( !wp_doing_ajax() ) {
				// sizes aren't changed when doing ajax
				add_action( 'wp_loaded', array( $this, 'save_preset_image_sizes' ) );
			}

			if ( $this->wppp->options[ 'dynamic_images_rthook' ] ) {
				add_filter( 'wp_update_attachment_metadata', array ( $this, 'rebuild_thumbnails_delete_hook' ), 100, 2 );
				add_action( 'admin_notices', array( $this, 'rthook_notice') );
			}
		}
	}

	function replace_wp_rewrite() {
		include( sprintf( "%s/class.wppp_rewrite.php", dirname( __FILE__ ) ) );
		$rewrite = new WPPP_Rewrite();
		$rewrite->wppp = $this->wppp;
		$GLOBALS['wp_rewrite'] = $rewrite;
	}

	public function set_rewrite_rules() {
		if ( $this->rw_method === 'wordpress' ) {
			add_filter( 'pre_handle_404', array( $this, 'handle_404' ), 10, 2 );
		} else {
			if ( $this->rw_method === 'use_themes' ) {
				$file = 'serve-dynamic-images-ut.php';
			} else {
				$file = 'serve-dynamic-images.php';
			}
			$path = substr( plugins_url( $file, __FILE__ ), strlen( site_url() ) + 1 ); // cut wp-content including trailing slash
			add_rewrite_rule( WPPP_Dynamic_Images::$rewrite_regex, $path, 'top' );
			add_filter ( 'mod_rewrite_rules', array ( $this, 'mod_rewrite_rules' ) );
		}
	}

	public function handle_404( $bool, $wp_query ) {
		global $wp;
		$serve = new WPPP_Serve_Image();
		if ( $serve->serve_image( trailingslashit( parse_url( get_site_url() )[ 'path' ] ) . $wp->request ) )
			exit();	// Image got served, so exit at this point
		else
			return false; // continue 404 handling
	}

	public function flush_rewrite_rules( $enabled ) {
		// init is called prior to options update
		// so add or remove rules before flushing
		if ( $enabled ) {
			$this->set_rewrite_rules();
			flush_rewrite_rules();
		} else {
			WPPP_Dynamic_Images::static_disable_rewrite_rules();
		}
	}

	public static function static_disable_rewrite_rules() {
		// init is called prior to options update
		// so add or remove rules before flushing
		global $wp_rewrite;
		if ( $wp_rewrite && isset( $wp_rewrite->non_wp_rules[ WPPP_Dynamic_Images::$rewrite_regex ] ) ) {
			unset( $wp_rewrite->non_wp_rules[ WPPP_Dynamic_Images::$rewrite_regex ] );
		}
		flush_rewrite_rules();
	}

	public function mod_rewrite_rules ( $rules ) {
		$lines = explode( "\n", $rules );
		$rules = '';
		if ( $this->rw_method === 'use_themes' ) {
			$file = 'serve-dynamic-images-ut.php';
		} else {
			$file = 'serve-dynamic-images.php';
		}
		for ($i = 0, $max = count($lines); $i<$max; $i++ ) {
			if ( strpos( $lines[$i], $file ) !== false ){
				$rules .= "# WPPP Start *****\n";
				// add InheritDownBefore if enabled
				if ( $this->rw_inherit )
					$rules .= "RewriteOptions InheritDownBefore\n\n";
				// extend rewrite rule by conditionals, so if the requested file exist it gets served directly
				if ( $this->rw_folder ) {
					$content = parse_url( content_url(), PHP_URL_PATH );
					$upbase = parse_url( wp_upload_dir()[ 'baseurl' ], PHP_URL_PATH );
					$rules .= "RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_URI} {$upbase}(.*)$
RewriteCond %{DOCUMENT_ROOT}{$content}/wppp/images/%1 -f
RewriteRule .* {$content}/wppp/images/%1 [L]\n";
				}
				$rules .= "RewriteCond %{REQUEST_FILENAME} !-f \n";
				$rules .= $lines[$i] . "\n";
				$rules .= "# WPPP End *****\n";
			} else {
				$rules .= $lines[$i] . "\n";
			}
		}
		return $rules;
	}

	function filter_wp_image_editor( $editors ) {
		$new_editors = array();
		// extend each registered editor and override its multi_resize function - found no better (i.e. flexible) way than to use eval
		foreach ( $editors as $editor ) {
			if ( !class_exists( "WPPP_$editor" ) ) {
				if ( version_compare( get_bloginfo( 'version' ), '5.3', '>=' ) ) {
					eval ("
						class WPPP_$editor extends $editor {

							public function make_subsize( \$size_data ) {
								if ( ! isset( \$size_data['width'] ) && ! isset( \$size_data['height'] ) ) {
									return new WP_Error( 'image_subsize_create_error', __( 'Cannot resize the image. Both width and height are not set.' ) );
								}

								\$orig_size = \$this->size;
						
								if ( ! isset( \$size_data['width'] ) ) {
									\$size_data['width'] = null;
								}
								if ( ! isset( \$size_data['height'] ) ) {
									\$size_data['height'] = null;
								}
								if ( ! isset( \$size_data['crop'] ) ) {
									\$size_data['crop'] = false;
								}

								\$dims = image_resize_dimensions( \$this->size['width'], \$this->size['height'], \$size_data['width'], \$size_data['height'], \$size_data['crop'] );
								if ( \$dims ) {
									list( \$dst_x, \$dst_y, \$src_x, \$src_y, \$dst_w, \$dst_h, \$src_w, \$src_h ) = \$dims;
									\$this->update_size( \$dst_w, \$dst_h );

									list( \$filename, \$extension, \$mime_type ) = \$this->get_output_format( null, null );

									if ( ! \$filename )
										\$filename = \$this->generate_filename( null, null, \$extension );

									\$metadata = array(
										'file'      => wp_basename( apply_filters( 'image_make_intermediate_size', \$filename ) ),
										'width'     => \$this->size['width'],
										'height'    => \$this->size['height'],
										'mime-type' => \$mime_type,
									);
									\$this->size = \$orig_size;
									return \$metadata;
								} else {
									return new WP_Error( 'image_subsize_create_error', __( 'Cannot resize the image. Both width and height are not set.' ) );
								}
							}
						}
					");
				} else {
					eval (" 
						class WPPP_$editor extends $editor {
							public function multi_resize( \$sizes ) {
								\$metadata = array();
								/*\$orig_size = \$this->size;

								foreach ( \$sizes as \$size => \$size_data ) {
									if ( ! isset( \$size_data['width'] ) && ! isset( \$size_data['height'] ) ) {
										continue;
									}

									if ( ! isset( \$size_data['width'] ) ) {
										\$size_data['width'] = null;
									}
									if ( ! isset( \$size_data['height'] ) ) {
										\$size_data['height'] = null;
									}

									if ( ! isset( \$size_data['crop'] ) ) {
										\$size_data['crop'] = false;
									}

									\$dims = image_resize_dimensions( \$this->size['width'], \$this->size['height'], \$size_data['width'], \$size_data['height'], \$size_data['crop'] );
									if ( \$dims ) {
										list( \$dst_x, \$dst_y, \$src_x, \$src_y, \$dst_w, \$dst_h, \$src_w, \$src_h ) = \$dims;
										\$this->update_size( \$dst_w, \$dst_h );

										list( \$filename, \$extension, \$mime_type ) = \$this->get_output_format( null, null );

										if ( ! \$filename )
											\$filename = \$this->generate_filename( null, null, \$extension );

										\$metadata[\$size] = array(
											'file'      => wp_basename( apply_filters( 'image_make_intermediate_size', \$filename ) ),
											'width'     => \$this->size['width'],
											'height'    => \$this->size['height'],
											'mime-type' => \$mime_type,
										);
										\$this->size = \$orig_size;
									}
								}*/
								return \$metadata;
							}
						} 
					");
				}
			}
			$new_editors[] = 'WPPP_' . $editor;
		}
		return $new_editors;
	}

	function save_preset_image_sizes() {
		global $_wp_additional_image_sizes;
 
		$sizes = array();
		$intersizes = get_intermediate_image_sizes();
		foreach ( $intersizes as $s ) {
			$sizes[ $s ] = array( 'width' => 0, 'height' => 0, 'crop' => false );
			if ( in_array( $s, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
				$sizes[ $s ][ 'width' ] = intval( get_option( $s . '_size_w' ) );
				$sizes[ $s ][ 'height' ] = intval( get_option( $s . '_size_h' ) );
				$sizes[ $s ][ 'crop' ] = get_option( $s . '_crop' ) ? true : false;
			} elseif ( isset( $_wp_additional_image_sizes[ $s ] ) ) {
				$sizes[ $s ][ 'width' ] = intval( $_wp_additional_image_sizes[ $s ][ 'width' ] );
				$sizes[ $s ][ 'height' ] = intval( $_wp_additional_image_sizes[ $s ][ 'height' ] );
				$sizes[ $s ][ 'crop' ] = $_wp_additional_image_sizes[$s]['crop'] ? true : false;
			}
			if ( ( $sizes[ $s ][ 'width' ] == 0 ) && ( $sizes[ $s ][ 'height' ] == 0 ) ) {
				// unset size if both width and height are 0. This is due to special sizes like "medium_large"
				// "medium_large" can have a fixed width but has no height limit.
				unset( $sizes[ $s ] );
			}
		}
		update_option( 'wppp_dynimg_sizes', $sizes );
	}

	function rebuild_thumbnails_delete_hook ($data, $postID) {
		// "old" regeneration via AJAX
		global $wp_current_filter;
		$ajax_match = wp_doing_ajax() 
					  && is_array( $wp_current_filter ) 
					  && ( in_array( 'wp_ajax_regeneratethumbnail', $wp_current_filter ) 
						   || in_array( 'wp_ajax_ajax_thumbnail_rebuild', $wp_current_filter )
						   || in_array( 'wp_ajax_sis_rebuild_images', $wp_current_filter ) );
		// "new" REST API usage
		$route = $route = untrailingslashit( $GLOBALS[ 'wp' ]->query_vars['rest_route'] );
		$rest_match = defined( 'REST_REQUEST' ) 
					  && REST_REQUEST
					  && ( strpos( $route, 'regenerate-thumbnails/v1/', 0 ) !== false );

		if ( $ajax_match || $rest_match ) {
			if ( $attach_meta = wp_get_attachment_metadata( $postID ) ) {
				global $wp_performance_pack;
				if ( $wp_performance_pack->options['dynamic_images_rthook_force'] ) {
					// delete all potential thumbnail files (filname.ext ~ filename-*x*.ext)
					$upload_dir = wp_upload_dir();
					$filename = $upload_dir['basedir'] . '/' . $attach_meta['file'];
					$info = pathinfo($filename);
					$ext = $info['extension'];
					$pattern = str_replace(".$ext", "-*x*.$ext", $filename);
					foreach (glob($pattern) as $thumbname) {
						@unlink($thumbname);
					}
				} else {
					if ( isset( $attach_meta[ 'sizes' ] ) ) {
						$upload_dir = wp_upload_dir();
						$filepath = $upload_dir['basedir'] . '/' . dirname( $attach_meta['file'] ) . '/';
						$filename = wp_basename( $attach_meta['file'] );
						foreach ( $attach_meta['sizes'] as $size => $size_data ) {
							$file = $filepath . $size_data['file'];
							if ( file_exists( $file ) && ( $size_data['file'] != $filename ) ) {
								@unlink( $file );
							}
						}
					}
				}
			}
		}
		return $data;
	}

	function rthook_notice () { 
		// display message on Rebuild Thumbnails page
		$screen = get_current_screen(); 
		if ( $screen->id == 'tools_page_regenerate-thumbnails' 
			|| $screen->id == 'tools_page_ajax-thumbnail-rebuild' 
			|| ( $screen->id == 'options-media' && is_plugin_active( 'simple-image-sizes/simple_image_sizes.php' ) ) ) : ?>
			<div class="update-nag"> 
				<p>
					<?php _e( 'WPPP Regenerate Thumbnails integration active.', 'wp-performance-pack' ); ?> <br/>
					<?php _e( 'Existing intermediate images will be deleted while regenerating thumbnails.', 'wp-performance-pack' ); ?>
					<?php
						global $wp_performance_pack;
						if ( $wp_performance_pack->options['dynamic_images_rthook_force'] ) : 
							?>
							<br/><strong><?php _e( 'Force delete option is active!', 'wp-performance-pack' ); ?></strong>
							<?php 
						endif;
					?>
					<br/>
					<a href="options-general.php?page=wppp_options_page"><?php _e( 'Change WPPP settings', 'wp-performance-pack' ); ?></a>
				</p> 
			</div>
		<?php endif; 
	}

	function filter_wp_get_attachment_metadata( $data ) {
		if ( !isset( $data[ 'file' ] ) )
			return $data;
		$ext  = strtolower( pathinfo( $data[ 'file' ], PATHINFO_EXTENSION ) );
		if ( ( $ext === 'jpg' ) || ( $ext === 'gif' ) || ( $ext === 'png' ) ) {
			$name = wp_basename( $data[ 'file' ], ".$ext" );

			$sizes = get_option( 'wppp_dynimg_sizes' );
			foreach ( $sizes as $size => $sizeinfo ) {
				if ( !isset( $data[ 'sizes' ][ $size ] ) ) {
					if ( isset( $sizeinfo[ 'crop' ] ) )
						$newsize = image_resize_dimensions( $data[ 'width' ], $data[ 'height' ], $sizeinfo['width'], $sizeinfo['height'], $sizeinfo['crop'] );
					else
						$newsize = image_resize_dimensions( $data[ 'width' ], $data[ 'height' ], $sizeinfo['width'], $sizeinfo['height'], false );
					if ( $newsize !== false ) {
						$data[ 'sizes' ][ $size ] = array (
							'width' => $newsize[ 4 ],
							'height' => $newsize[ 5 ],
							'file' => $name . '-' . $newsize[ 4 ] . 'x' . $newsize[ 5 ] . '.' . $ext,
						);
					}
				}
			}
		}
		return $data;
	}
}