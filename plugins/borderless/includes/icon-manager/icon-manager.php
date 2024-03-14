<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'Borderless_IF' ) ) {
	class Borderless_IF {
		var $paths = array();
		var $svg_file;
		var $json_file;
		var $ip_name = 'unknown';
		var $svg_config = array();
		var $json_config = array();
		static $ip_list = array();
		
		function __construct() {
			$this->paths            = wp_upload_dir();
			$this->paths['fonts']   = 'borderless_icon_fonts';
			$this->paths['temp']    = trailingslashit( $this->paths['fonts'] ) . 'borderless_temp';
			$this->paths['fontdir'] = trailingslashit( $this->paths['basedir'] ) . $this->paths['fonts'];
			$this->paths['tempdir'] = trailingslashit( $this->paths['basedir'] ) . $this->paths['temp'];
			$this->paths['fonturl'] = set_url_scheme( trailingslashit( $this->paths['baseurl'] ) . $this->paths['fonts'] );
			$this->paths['tempurl'] = trailingslashit( $this->paths['baseurl'] ) . trailingslashit( $this->paths['temp'] );
			$this->paths['config']  = 'charmap.php';
			add_action( 'wp_ajax_borderless_ajax_add_zipped_font', array( $this, 'add_zipped_font' ) );
			add_action( 'wp_ajax_borderless_ajax_remove_zipped_font', array( $this, 'remove_zipped_font' ) );
			
		}
		
		function admin_scripts() {
			$upload_paths = wp_upload_dir();
			wp_enqueue_script( 'borderless-admin-media', plugin_dir_url( __FILE__ ) . 'assets/js/icon-manager.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_media();
			wp_enqueue_style( 'borderless-wpbakery-icon-manager-admin', plugin_dir_url( __FILE__ ) . 'assets/css/icon-manager.css' );
			$custom_fonts = get_option( 'borderless_icon_fonts' );
			if ( is_array( $custom_fonts ) ) {
				foreach ( $custom_fonts as $font => $info ) {
					if ( strpos( $info['style'], 'http://' ) !== false ) {
						wp_enqueue_style( 'borderless-' . $font, $info['style'], null, '1.0', 'all' );
					} else {
						wp_enqueue_style( 'borderless-' . $font, trailingslashit( $upload_paths['baseurl'] . '/borderless_icon_fonts/' ) . $info['style'], null, '1.0', 'all' );
					}
				}
			}
		}
		
		/*-----------------------------------------------------------------------------------*/
		/*  *.  Icon Pack Manager On Page Edit
		/*-----------------------------------------------------------------------------------*/
		
		public function get_icon_manager( $input_name, $icon ) {
			$font_manager = self::get_font_manager( $id );
			$output       = '<div class="my_param_block">';
			$output .= '<input name="' . $input_name . '" class="textinput ' . $input_name . ' text_field" type="text" value="' . $icon . '"/>';
			$output .= '</div>';
			$output .= '<script type="text/javascript">
			jQuery(document).ready(function(){
				
				//debugger;
				//alert("' . $id . '");
				jQuery(".icon-manager-preview-icon-' . $id . '").html("<i class=\'' . $icon . '\'></i>");
				jQuery(".icons-list-' . $id . ' li[data-icons=\'' . $icon . '\']").addClass("selected");
			});
			jQuery(".icons-list-' . $id . ' li").click(function() {
				jQuery(this).attr("class","selected").siblings().removeAttr("class");
				var icon = jQuery(this).attr("data-icons");
				jQuery("input[name=\'' . $input_name . '\']").val(icon);
				jQuery(".icon-manager-preview-icon-' . $id . '").html("<i class=\'"+icon+"\'></i>");
			});
			</script>';
			$output .= $font_manager;
			
			return $output;
		}
		
		public function get_font_manager( $id ) {
			
			$fonts  = get_option( 'borderless_icon_fonts' );
			$output = '<div class="icon-manager-preview"><div class="icon-manager-preview-icon preview-icon-' . $id . '"><i class=""></i></div><input class="icon-manager-search-icon" type="text" placeholder="Search for a suitable icon.." /></div>';
			$output .= '<div id="icon-manager-search-icon-inner">';
			$output .= '<ul class="icons-list borderless_icon icon-list-' . $id . '">';
			foreach ( $fonts as $font => $info ) {
				$icon_set   = array();
				$icons      = array();
				$upload_dir = wp_upload_dir();
				$path       = trailingslashit( $upload_dir['basedir'] );
				$file       = $path . $info['include'] . '/' . $info['config'];
				include( $file );
				if ( ! empty( $icons ) ) {
					$icon_set = array_merge( $icon_set, $icons );
				}
				
				$set_name = ucfirst( $font );
				
				if ( ! empty( $icon_set ) ) {
					$output .= '<p><strong>' . $set_name . '</strong></p>';
					$output .= '<li title="no-icon" data-icons="none" data-icons-tag="none,blank" style="cursor: pointer;" id="' . $id . '"></li>';
					foreach ( $icon_set as $icons ) {
						foreach ( $icons as $icon ) {
							$output .= '<li title="' . $icon['class'] . '" data-icons="' . $font . '-' . $icon['class'] . '" data-icons-tag="' . $icon['tags'] . '" id="' . $id . '">';
							$output .= '<i class="icon-manager-icon ' . $font . '-' . $icon['class'] . '"></i><label class="icon">' . $icon['class'] . '</label></li>';
						}
					}
				}
			}
			$output . '</ul>';
			$output .= '<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery(".icon-manager-search-icon").keyup(function(){
					// Retrieve the input field text and reset the count to zero
					var filter = jQuery(this).val(), count = 0;
					// Loop through the icon list
					jQuery(".icons-list li").each(function(){
						// If the list item does not contain the text phrase fade it out
						if (jQuery(this).attr("data-icons-tag").search(new RegExp(filter, "i")) < 0) {
							jQuery(this).fadeOut();
						} else {
							jQuery(this).show();
							count++;
						}
					});
				});
			});
			</script>';
			$output .= '</div>';
			
			return $output;
		}
		
		/*-----------------------------------------------------------------------------------*/
		/*  *.  Icon Pack Manager
		/*-----------------------------------------------------------------------------------*/
		
		function icon_pack_manager() {
			?>
			<div class="wrap">
			<h2>
			<?php esc_html_e( 'Icon Pack Manager', 'borderless' ); ?>
			<a href="#borderless_upload_icon" class="button button-primary borderless-upload-icon" data-target="iconfont_upload"
			data-title="<?php echo esc_html__( "Upload/Select Fontello Font Zip", "borderless" ) ?>"
			data-type="application/octet-stream, application/zip"
			data-button="<?php echo esc_html__( "Insert Fonts Zip File", "borderless" ); ?>" data-trigger="borderless_insert_zip"
			data-class="media-frame ">
			<?php esc_html_e( 'Add New Icon Pack', 'borderless' ); ?>
			</a> &nbsp;<span class="spinner"></span></h2>
			<div id="msg"></div>
			<?php
			$fonts = get_option( 'borderless_icon_fonts' );
			if ( is_array( $fonts ) ) :
				?>
				<div class="metabox-holder meta-search">
				<div class="postbox borderless-postbox-search">
				<h2 class="hndle ui-sortable-handle"><span>Search for Icons</h2>
				<div class="inside">
				<div class="search-area">
				<input class="search-icon" type="text" placeholder="<?php esc_attr_e( 'Start typing to search', 'borderless' ); ?>"/>
				<span class="search-count"></span>
				</div>
				</div>
				</div>
				</div>
				<?php self::get_ipm(); ?>
				</div>
				<?php else: ?>
				<div class="error">
				<p>
				<?php esc_html_e( 'No icon pack uploaded. Upload some icon pack to display here.', 'borderless' ); ?>
				</p>
				</div>
				<?php
			endif;
		}
		
		// Generate Icon Pack Preview and settings page
		static function get_ipm() {
			$fonts = get_option( 'borderless_icon_fonts' );
			$n     = count( $fonts );
			foreach ( $fonts as $font => $info ) {
				$icon_set   = array();
				$icons      = array();
				$upload_dir = wp_upload_dir();
				$path       = trailingslashit( $upload_dir['basedir'] );
				$file       = $path . $info['include'] . '/' . $info['config'];
				$output     = '<div class="icon_set-' . $font . ' metabox-holder">';
				$output .= '<div class="postbox borderless-postbox-icon-manager">';
				include( $file );
				if ( ! empty( $icons ) ) {
					$icon_set = array_merge( $icon_set, $icons );
				}
				if ( ! empty( $icon_set ) ) {
					foreach ( $icon_set as $icons ) {
						$count = count( $icons );
					}
					
					$output .= '<h3 class="ip_name"><strong>' . esc_html( ucfirst( $font ) ) . '</strong>';
					
					$output .= '<span class="fonts-count wp-core-ui wp-ui-notification count-' . esc_attr( $font ) . '">' . esc_html( $count ) . '</span>';
					if ( $n != 1 ) {
						$output .= '<button class="button button-primary button-small borderless_del_icon" data-delete=' . esc_attr( $font ) . ' data-title="' . esc_attr__( 'Remove Pack', 'borderless' ) . '">' . esc_html__( 'Remove Pack', 'borderless' ) . '</button>';
					}
					$output .= '</h3>';
					$output .= '<div class="inside"><div class="icon_actions">';
					$output .= '</div>';
					$output .= '<div class="icon_search"><ul class="icons-list borderless_icon">';
					foreach ( $icon_set as $icons ) {
						foreach ( $icons as $icon ) {
							$output .= '<li " data-icons="' . esc_attr( $icon['class'] ) . '" data-icons-tag="' . esc_attr( $icon['tags'] ) . '">';
							$output .= '<i class="' . esc_attr( $font ) . '-' . esc_attr( $icon['class'] ) . '"></i><a class="tooltips" href="#"><span>' . esc_html( $icon['class'] ) . '</span></a></li>';
						}
					}
					$output . '</ul>';
					$output .= '</div><!-- .icon_search-->';
					$output .= '</div><!-- .inside-->';
					$output .= '</div><!-- .postbox-->';
					$output .= '</div><!-- .icon_set-' . esc_html( $font ) . ' -->';
					echo $output;
				}
			}
			$script = '<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery(".search-icon").keyup(function(){
					jQuery(".fonts-count").hide();
					// Retrieve the input field text and reset the count to zero
					var filter = jQuery(this).val(), count = 0;
					// Loop through the icon list
					jQuery(".icons-list li").each(function(){
						// If the list item does not contain the text phrase fade it out
						if (jQuery(this).attr("data-icons-tag").search(new RegExp(filter, "i")) < 0) {
							jQuery(this).fadeOut();
						} else {
							jQuery(this).show();
							count++;
						}
						if(count == 0)
						jQuery(".search-count").html(" ' . esc_js( __( 'Can\'t find the icon?', 'borderless' ) ) . ' <a href=\'#borderless_upload_icon\' class=\'button button-primary borderless_upload_icon\' data-target=\'iconfont_upload\' data-title=\' ' . esc_js( __( 'Upload/Select Fontello Font Zip', 'borderless' ) ) . ' \' data-type=\'application/octet-stream, application/zip\' data-button=\' ' . esc_js( __( 'Insert Fonts Zip File', 'borderless' ) ) . ' \' data-trigger=\'borderless_insert_zip\' data-class=\'media-frame\'>' . esc_js( __( 'Upload New Font Pack', 'borderless' ) ) . '</a>");
						else
						jQuery(".search-count").html(count+" ' . esc_js( __( 'Icons found.', 'borderless' ) ) . '");
						jQuery( ".search-count" ).addClass( "search-count-open" );
						if(filter == "")
						jQuery(".fonts-count").show();
					});
				});
			});
			</script>';
			echo $script;
		}
		
		function add_zipped_font() {
			$cap = apply_filters( 'avf_file_upload_capability', 'update_plugins' );
			if ( ! current_user_can( $cap ) ) {
				die( esc_html__( "Using this feature is reserved for Super Admins. You unfortunately don't have the necessary permissions.", "borderless" ) );
			}
			//get the file path of the zip file
			$attachment = $_POST['values'];
			$path       = realpath( get_attached_file( sanitize_text_field( $attachment['id'] ) ) );
			$unzipped   = $this->zip_flatten( $path, array( '\.eot', '\.svg', '\.ttf', '\.woff', '\.json', '\.css' ) );
			// if we were able to unzip the file and save it to our temp folder extract the svg file
			if ( $unzipped ) {
				$this->create_config();
			}
			//if we got no name for the font dont add it and delete the temp folder
			if ( $this->ip_name == 'unknown' ) {
				$this->delete_folder( $this->paths['tempdir'] );
				die( esc_html__( 'Was not able to retrieve the Icon Pack name from your Uploaded Folder', 'borderless' ) );
			}
			die( esc_html__( 'borderless_font_added:', 'borderless' ) . $this->ip_name );
		}
		
		function remove_zipped_font() {
			//get the file path of the zip file
			$font   = sanitize_text_field( $_POST['del_font'] );
			$list   = self::load_iconfont_list();
			$delete = isset( $list[ $font ] ) ? $list[ $font ] : false;
			if ( $delete ) {
				$this->delete_folder( $delete['include'] );
				$this->remove_font( $font );
				die( esc_html__( 'borderless_font_removed', 'borderless' ) );
			}
			die( esc_html__( 'Was not able to remove Icon Pack', 'borderless' ) );
		}
		
		//extract the zip file to a flat folder and remove the files that are not needed
		function zip_flatten( $zipfile, $filter ) {
			if ( is_dir( $this->paths['tempdir'] ) ) {
				$this->delete_folder( $this->paths['tempdir'] );
				$tempdir = borderless_backend_create_folder( $this->paths['tempdir'], false );
			} else {
				$tempdir = borderless_backend_create_folder( $this->paths['tempdir'], false );
			}
			//$fontdir = borderless_backend_create_folder($this->paths['fontdir'], false);
			if ( ! $tempdir ) {
				die( esc_html__( 'Could not create temporary folder.', 'borderless' ) );
			}
			$zip = new ZipArchive;
			if ( $zip->open( $zipfile ) ) {
				for ( $i = 0; $i < $zip->numFiles; $i ++ ) {
					$entry = $zip->getNameIndex( $i );
					if ( ! empty( $filter ) ) {
						$delete  = true;
						$matches = array();
						foreach ( $filter as $regex ) {
							preg_match( "!" . $regex . "!", $entry, $matches );
							if ( ! empty( $matches ) ) {
								$delete = false;
								break;
							}
						}
					}
					if ( substr( $entry, - 1 ) == '/' || ! empty( $delete ) ) {
						continue;
					} // skip directories and non matching files
					$fp  = $zip->getStream( $entry );
					$ofp = fopen( $this->paths['tempdir'] . '/' . basename( $entry ), 'w' );
					if ( ! $fp ) {
						die( esc_html__( 'Unable to unpack Icon Pack.', 'borderless' ) );
					}
					while ( ! feof( $fp ) ) {
						fwrite( $ofp, fread( $fp, 8192 ) );
					}
					fclose( $fp );
					fclose( $ofp );
				}
				$zip->close();
			} else {
				die( esc_html__( "Icon Pack Zip file is corrupted.", 'borderless' ) );
			}
			
			return true;
		}
		
		//iterate over xml file and extract the glyphs for the font
		function create_config() {
			$this->json_file = $this->find_json();
			$this->svg_file  = $this->find_svg();
			if ( empty( $this->json_file ) || empty( $this->svg_file ) ) {
				$this->delete_folder( $this->paths['tempdir'] );
				die( esc_html__( 'SVG file or selection.json not found. Please check the integrity of the Icon Pack files.', 'borderless' ) );
			}
			//$response 	= wp_remote_get( $this->paths['tempurl'].$this->svg_file );
			$response = wp_remote_fopen( trailingslashit( $this->paths['tempurl'] ) . $this->svg_file );
			//if wordpress wasnt able to get the file which is unlikely try to fetch it old school
			$json = file_get_contents( trailingslashit( $this->paths['tempdir'] ) . $this->json_file );
			if ( empty( $response ) ) {
				$response = file_get_contents( trailingslashit( $this->paths['tempdir'] ) . $this->svg_file );
			}
			if ( ! is_wp_error( $json ) && ! empty( $json ) ) {
				$xml             = simplexml_load_string( $response );
				$font_attr       = $xml->defs->font->attributes();
				$glyphs          = $xml->defs->font->children();
				$this->ip_name = (string) $font_attr['id'];
				$font_folder = trailingslashit( $this->paths['fontdir'] ) . $this->ip_name;
				if ( is_dir( $font_folder ) ) {
					$this->delete_folder( $this->paths['tempdir'] );
					die( esc_html__( "There is already an Icon Pack with this name, add an icon with another name.", "borderless" ) );
				}
				$file_contents = json_decode( $json );
				if ( ! isset( $file_contents->IcoMoonType ) ) {
					$this->delete_folder( $this->paths['tempdir'] );
					die( esc_html__( "This Icon Pack does not belong to Icomoon. Use only Icomoon's Icon Pack.", 'borderless' ) );
				}
				$icons = $file_contents->icons;
				$n = 1;
				foreach ( $icons as $icon ) {
					$icon_name                                           = $icon->properties->name;
					$icon_class                                          = str_replace( ' ', '', $icon_name );
					$icon_class                                          = str_replace( ',', ' ', $icon_class );
					$tags                                                = implode( ",", $icon->icon->tags );
					$this->json_config[ $this->ip_name ][ $icon_name ] = array(
						"class"   => $icon_class,
						"tags"    => $tags
					);
					$n ++;
				}
				if ( ! empty( $this->json_config ) && $this->ip_name != 'unknown' ) {
					$this->write_config();
					$this->re_write_css();
					$this->rename_files();
					$this->rename_folder();
					$this->add_font();
				}
			}
			
			return false;
		}
		
		//writes the php config file for the font
		function write_config() {
			$charmap = $this->paths['tempdir'] . '/' . $this->paths['config'];
			$handle  = @fopen( $charmap, 'w' );
			if ( $handle ) {
				fwrite( $handle, '<?php $icons = array();' );
				foreach ( $this->json_config[ $this->ip_name ] as $icon => $info ) {
					if ( ! empty( $info ) ) {
						$delimiter = "'";
						fwrite( $handle, "\r\n" . '$icons[\'' . $this->ip_name . '\'][' . $delimiter . $icon . $delimiter . '] = array("class"=>' . $delimiter . $info["class"] . $delimiter . ',"tags"=>' . $delimiter . $info["tags"] . $delimiter . ');' );
					} else {
						$this->delete_folder( $this->paths['tempdir'] );
						die( esc_html__( 'Error generating configuration file.', 'borderless' ) );
					}
				}
				fclose( $handle );
			} else {
				$this->delete_folder( $this->paths['tempdir'] );
				die( esc_html__( 'Error generating configuration file.', 'borderless' ) );
			}
		}
		
		//re-writes the php config file for the font
		function re_write_css() {
			$style = $this->paths['tempdir'] . '/style.css';
			$file  = @file_get_contents( $style );
			if ( $file ) {
				$str = str_replace( 'fonts/', '', $file );
				$str = str_replace( 'icon-', $this->ip_name . '-', $str );
				$str = str_replace( '.icon {', '[class^="' . $this->ip_name . '-"], [class*=" ' . $this->ip_name . '-"] {', $str );
					$str = str_replace( 'i {', '[class^="' . $this->ip_name . '-"], [class*=" ' . $this->ip_name . '-"] {', $str );
						
						/* remove comments */
						$str = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $str );
						
						/* remove tabs, spaces, newlines, etc. */
						$str = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $str );
						
						@file_put_contents( $style, $str );
					} else {
						die( esc_html__( "Error generating CSS file. Use only Icomoon's Icon Pack.", 'borderless' ) );
					}
				}
				
				function rename_files() {
					$extensions = array( 'eot', 'svg', 'ttf', 'woff', 'css' );
					$folder     = trailingslashit( $this->paths['tempdir'] );
					foreach ( glob( $folder . '*' ) as $file ) {
						$path_parts = pathinfo( $file );
						if ( strpos( $path_parts['filename'], '.dev' ) === false && in_array( $path_parts['extension'], $extensions ) ) {
							if ( $path_parts['filename'] !== $this->ip_name ) {
								rename( $file, trailingslashit( $path_parts['dirname'] ) . $this->ip_name . '.' . $path_parts['extension'] );
							}
						}
					}
				}
				
				//rename the temp folder and all its font files
				function rename_folder() {
					$new_name = trailingslashit( $this->paths['fontdir'] ) . $this->ip_name;
					//delete folder and contents if they already exist
					$this->delete_folder( $new_name );
					if ( rename( $this->paths['tempdir'], $new_name ) ) {
						return true;
					} else {
						$this->delete_folder( $this->paths['tempdir'] );
						die( esc_html__( "This Icon Pack could not be added.", "borderless" ) );
					}
				}
				
				//delete a folder
				function delete_folder( $new_name ) {
					//delete folder and contents if they already exist
					if ( is_dir( $new_name ) ) {
						$objects = scandir( $new_name );
						foreach ( $objects as $object ) {
							if ( $object != "." && $object != ".." ) {
								unlink( $new_name . "/" . $object );
							}
						}
						reset( $objects );
						rmdir( $new_name );
					} else {
						echo $new_name . ' no found<br/>';
					}
				}
				
				function add_font() {
					$fonts = get_option( 'borderless_icon_fonts' );
					if ( empty( $fonts ) ) {
						$fonts = array();
					}
					$fonts[ $this->ip_name ] = array(
						'include' => sanitize_text_field( trailingslashit( $this->paths['fonts'] ) ) . $this->ip_name,
						'folder'  => sanitize_text_field( trailingslashit( $this->paths['fonts'] ) ) . $this->ip_name,
						'style'   => $this->ip_name . '/' . $this->ip_name . '.css',
						'config'  => sanitize_text_field( $this->paths['config'] )
					);
					update_option( 'borderless_icon_fonts', $fonts );
				}
				
				function remove_font( $font ) {
					$fonts = get_option( 'borderless_icon_fonts' );
					if ( isset( $fonts[ $font ] ) ) {
						unset( $fonts[ $font ] );
						update_option( 'borderless_icon_fonts', $fonts );
					}
				}
				
				//finds the json file we need to create the config
				function find_json() {
					$files = scandir( $this->paths['tempdir'] );
					foreach ( $files as $file ) {
						if ( strpos( strtolower( $file ), '.json' ) !== false && $file[0] != '.' ) {
							return $file;
						}
					}
				}
				
				//finds the svg file we need to create the config
				function find_svg() {
					$files = scandir( $this->paths['tempdir'] );
					foreach ( $files as $file ) {
						if ( strpos( strtolower( $file ), '.svg' ) !== false && $file[0] != '.' ) {
							return $file;
						}
					}
				}
				
				static function load_iconfont_list() {
					if ( ! empty( self::$ip_list ) ) {
						return self::$ip_list;
					}
					$extra_fonts = get_option( 'borderless_icon_fonts' );
					if ( empty( $extra_fonts ) ) {
						$extra_fonts = array();
					}
					$font_configs = $extra_fonts;//array_merge(borderlessBuilder::$default_iconfont, $extra_fonts);
					//if we got any include the charmaps and add the chars to an array
					$upload_dir = wp_upload_dir();
					$path       = trailingslashit( $upload_dir['basedir'] );
					$url        = trailingslashit( $upload_dir['baseurl'] );
					foreach ( $font_configs as $key => $config ) {
						if ( empty( $config['full_path'] ) ) {
							$font_configs[ $key ]['include'] = $path . $font_configs[ $key ]['include'];
							$font_configs[ $key ]['folder']  = $url . $font_configs[ $key ]['folder'];
						}
					}
					//cache the result
					self::$ip_list = $font_configs;
					
					return $font_configs;
				}
			}// End class
			
			/*
			* creates a folder for the theme framework
			*/
			
			if ( ! function_exists( 'borderless_backend_create_folder' ) ) {
				function borderless_backend_create_folder( &$folder, $addindex = true ) {
					if ( is_dir( $folder ) && $addindex == false ) {
						return true;
					}
					$created = wp_mkdir_p( trailingslashit( $folder ) );
					@chmod( $folder, 0777 );
					if ( $addindex == false ) {
						return $created;
					}
					$index_file = trailingslashit( $folder ) . 'index.php';
					if ( file_exists( $index_file ) ) {
						return $created;
					}
					$handle = @fopen( $index_file, 'w' );
					if ( $handle ) {
						fwrite( $handle, "<?php\r\necho 'We're sorry for the inconvenience, but you're not allowed to browse the directory.';\r\n?>
						" );
						fclose( $handle );
					}
					
					return $created;
				}
			}
			// Instantiate the Icon Fonts
			new Borderless_IF;
		}
		
		function borderless_custom_icons_menu() {
			$Borderless_IF = new Borderless_IF;
			$Borderless_IF->icon_pack_manager();
		}
		
		function borderless_custom_icons() {
			$upload_paths = wp_upload_dir();
			$custom_fonts = get_option( 'borderless_icon_fonts' );
			if ( is_array( $custom_fonts ) ) {
				foreach ( $custom_fonts as $font => $info ) {
					if ( strpos( $info['style'], 'http://' ) !== false ) {
						wp_enqueue_style( 'borderless-' . $font, $info['style'], null, '1.0', 'all' );
					} else {
						wp_enqueue_style( 'borderless-' . $font, trailingslashit( $upload_paths['baseurl'] . '/borderless_icon_fonts/' ) . $info['style'], null, '1.0', 'all' );
					}
				}
			}
		}
		
		add_action( 'wp_enqueue_scripts', 'borderless_custom_icons' );