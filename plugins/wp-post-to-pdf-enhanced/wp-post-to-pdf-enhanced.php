<?php
/**
 * Plugin Name: WP Post to PDF Enhanced
 * Plugin URI: http://www.2rosenthals.net/wordpress/help/general-help/wp-post-to-pdf-enhanced/
 * Description: WP Post to PDF Enhanced, based on the original WP Post to PDF, renders posts & pages as downloadable PDFs for archiving and/or printing.
 * Version: 1.1.1
 * License: GPLv2
 * Author: Lewis Rosenthal
 * Author URI: http://www.2rosenthals.net/wordpress/help/general-help/wp-post-to-pdf-enhanced/
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

//avoid direct calls to this file, because now WP core and framework has been used
if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}
// Define certain terms which may be required throughout the plugin
global $blog_id;
define( 'WPPTOPDFENH_NAME', 'WP Post to PDF Enhanced' );
define( 'WPPTOPDFENH_SNAME', 'wpptopdfenh' );
if ( ! defined( 'WPPTOPDFENH_PATH' ) )
	define( 'WPPTOPDFENH_PATH', WP_PLUGIN_DIR . '/wp-post-to-pdf-enhanced' );
define( 'WPPTOPDFENH_URL', WP_PLUGIN_URL . '/wp-post-to-pdf-enhanced' );
define( 'WPPTOPDFENH_BASENAME', plugin_basename( __FILE__ ) );
define( 'WPPTOPDFENH_CACHE_DIR', WP_CONTENT_DIR . '/uploads/wp-post-to-pdf-enhanced-cache/' . $blog_id );
if ( ! defined( 'WPPTOPDFENH_VERSION_NUM' ) ) {
	define( 'WPPTOPDFENH_VERSION_NUM', '1.1.0' );
}
if ( ! class_exists( 'wpptopdfenh' ) ) {
	class wpptopdfenh {
		private $options;
		function wpptopdfenh() {
			$this->options = get_option( 'wpptopdfenh' );
			if ( is_admin() ) {
				add_action( 'admin_init', array( &$this, 'on_admin_init' ) );
				add_action( 'admin_menu', array( &$this, 'on_admin_menu' ) );
				add_filter( "plugin_action_links_" . WPPTOPDFENH_BASENAME, array( &$this, 'action_links' ) );
				register_activation_hook( WPPTOPDFENH_BASENAME, array( &$this, 'on_activate' ) );
				$post_types = get_post_types( array( 'public'   => true ), 'names' );
				foreach ( $post_types as $post_type ) {
					if ( array( isset( $this->options[$post_type] ) ) == get_post_type() ) {
						add_action( 'pre_post_update', array( &$this, 'on_pre_post_update' ) );
						add_action( 'post_updated', array( &$this, 'generate_pdf_file' ) );
					}
				}
			} else {
				add_action( 'wp', array( &$this, 'generate_pdf' ) );
				add_filter( 'the_content', array( &$this, 'add_button' ) );
			}
		}
		function on_admin_init() {
			register_setting( 'wpptopdfenh_options', 'wpptopdfenh', array( &$this, 'on_update_options' ) );

			// First, we need to load $wpdb to access the database
			global $wpdb;
			if ( ! isset ( $this->options['pluginVer'] ) )
				update_option( $this->options['pluginVer'], '1.0.5' );
			if ( version_compare( WPPTOPDFENH_VERSION_NUM, $this->options['pluginVer'], '>' ) )
				$this->on_upgrade();
		}
		function on_update_options( $post ) {
			if ( isset( $post['submit'] ) and 'Save and Reset PDF Cache' == $post['submit'] ) {
				$this->delete_cache( WPPTOPDFENH_CACHE_DIR );
			}
			return $post;
		}
		function delete_cache( $path ) {
			if ( is_dir( $path ) === true ) {
				$files = array_diff( scandir( $path ), array( '.', '..' ) );
				foreach ( $files as $file ) {
					$this->delete_cache( realpath( $path ) . '/' . $file );
				}
				return true;
			}
			else if ( is_file( $path ) === true ) {
					return unlink( $path );
				}
			return false;
		}
		function on_admin_menu() {
			$option_page = add_options_page( 'WP Post to PDF Enhanced Options', 'WP Post to PDF Enhanced', 'administrator', WPPTOPDFENH_BASENAME, array( &$this, 'options_page' ) );
			//add_action("admin_print_scripts-$option_page", array(&$this, 'on_admin_print_scripts'));
			add_action( "admin_print_styles-$option_page", array( &$this, 'on_admin_print_styles' ) );
		}
		function options_page() {
			include WPPTOPDFENH_PATH . '/wpptopdfenh_options.php';
		}
		function on_admin_print_styles() {
			wp_enqueue_style( 'wpptopdfenhadminstyle', WPPTOPDFENH_URL . '/asset/css/admin.css', false, '1.0', 'all' );
		}
		/*function on_admin_print_scripts() {
          	 *	wp_enqueue_script('wpptopdfenhadminstyle', WPPTOPDFENH_URL . '/asset/css/admin.css',false, '1.0', 'all');
        	 *}
        	 */
		function action_links( $links ) {
			$settings_link = '<a href="options-general.php?page=' . WPPTOPDFENH_BASENAME . '">Settings</a>';
			array_unshift( $links, $settings_link );
			return $links;
		}
		function generate_pdf() {
			if ( 'pdf' == ( isset( $_GET['format'] ) ? $_GET['format'] : null ) ) {
				if ( isset( $this->options['nonPublic'] ) and ! is_user_logged_in() )
					return false;
				global $post;
				$post = get_post();
				$content = $post->the_content;{
					if ( ! function_exists( 'has_shortcode' ) ) {
						function has_shortcode( $content, $short_code = '' ) {
							$is_short_code = false;
							if ( stripos( $content, '[' . $short_code ) !== false ) {
								$is_short_code = true;
							}
							return $is_short_code;
						}
					}
					if ( has_shortcode( $content, 'wpptopdfenh' ) ) {
						$include = $this->options['include'];
						$excludeThis = explode( ',', $this->options['excludeThis'] );
						if ( $include and ! in_array( $post->ID, $excludeThis ) )
							return false;
						if ( ! $include and in_array( $post->ID, $excludeThis ) )
							return false;
					}
				}
				$filePath = WPPTOPDFENH_CACHE_DIR . '/' . $post->post_name . '.pdf';
				$fileMime = 'pdf';
				$fileName = $post->post_name . '.pdf';
				$includeCache = $this->options['includeCache'];
				$excludeThisCache = explode( ',', $this->options['excludeThisCache'] );
				if ( $includeCache and ! in_array( $post->ID, $excludeThisCache ) ) {
					$this->generate_pdf_file( $post->ID );
				} elseif ( ! $includeCache and in_array( $post->ID, $excludeThisCache ) ) {
					$this->generate_pdf_file( $post->ID );
				} else {
					if ( ! file_exists( $filePath ) ) {
						$this->generate_pdf_file( $post->ID );
					}
				}
				$output = $this->output_pdf_file( $filePath, $fileName, $fileMime );
			}
		}
		function output_pdf_file( $file, $name, $mime_type = '' ) {
			if ( ! is_readable( $file ) )
				return false;
			$size = filesize( $file );
			$name = rawurldecode( $name );
			/* Figure out the MIME type (if not specified) */
			$known_mime_types = array(
				"pdf" => "application/pdf",
				"txt" => "text/plain",
				"html" => "text/html",
				"htm" => "text/html",
				"exe" => "application/octet-stream",
				"zip" => "application/zip",
				"doc" => "application/msword",
				"xls" => "application/vnd.ms-excel",
				"ppt" => "application/vnd.ms-powerpoint",
				"gif" => "image/gif",
				"png" => "image/png",
				"jpeg" => "image/jpg",
				"jpg" => "image/jpg",
				"php" => "text/plain"
			);
			if ( $mime_type == '' ) {
				$file_extension = strtolower( substr( strrchr( $file, "." ), 1 ) );
				if ( array_key_exists( $file_extension, $known_mime_types ) ) {
					$mime_type = $known_mime_types[$file_extension];
				} else {
					$mime_type = "application/force-download";
				}
			}
			@ob_end_clean(); //turn off output buffering to decrease cpu usage
			// required for IE, otherwise Content-Disposition may be ignored
			if ( ini_get( 'zlib.output_compression' ) )
				ini_set( 'zlib.output_compression', 'Off' );
			header( 'Content-Type: ' . $mime_type );
			header( 'Content-Disposition: attachment; filename="' . $name . '"' );
			header( "Content-Transfer-Encoding: binary" );
			header( 'Accept-Ranges: bytes' );
			// The three lines below basically make the download non-cacheable
			header( "Cache-control: private" );
			header( 'Pragma: private' );
			header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
			// multipart-download and download resuming support
			if ( isset( $_SERVER['HTTP_RANGE'] ) ) {
				list( $a, $range ) = explode( "=", $_SERVER['HTTP_RANGE'], 2 );
				list( $range ) = explode( ",", $range, 2 );
				list( $range, $range_end ) = explode( "-", $range );
				$range = intval( $range );
				if ( ! $range_end ) {
					$range_end = $size - 1;
				} else {
					$range_end = intval( $range_end );
				}
				$new_length = $range_end - $range + 1;
				header( "HTTP/1.1 206 Partial Content" );
				header( "Content-Length: $new_length" );
				header( "Content-Range: bytes $range-$range_end/$size" );
			} else {
				$new_length = $size;
				header( "Content-Length: " . $size );
			}
			/* output the file itself */
			$chunksize = 1 * ( 1024 * 1024 ); //you may want to change this
			$bytes_send = 0;
			if ( $file = fopen( $file, 'r' ) ) {
				if ( isset( $_SERVER['HTTP_RANGE'] ) )
					fseek( $file, $range );
				while ( ! feof( $file ) &&
					( ! connection_aborted() ) &&
					( $bytes_send < $new_length )
				) {
					$buffer = fread( $file, $chunksize );
					print( $buffer ); //echo($buffer); // is also possible
					flush();
					$bytes_send += strlen( $buffer );
				}
				fclose( $file );
			} else
				return false;
			return true;
		}
		function generate_pdf_file( $id, $forceDownload = false ) {
			$post = get_post();
			$content = $post->the_content;{
				if ( has_shortcode( $content, 'wpptopdfenh' ) ) {
					if ( ! $this->options[$post->post_type] )
						return false;
				}
			}
			// require_once(WPPTOPDFENH_PATH . '/tcpdf/config/lang/eng.php');
			// to avoid duplicate function error
			if ( ! class_exists( 'TCPDF' ) )
				require_once WPPTOPDFENH_PATH . '/tcpdf/tcpdf.php';
			if ( ! class_exists( 'MYPDF' ) )
				require_once WPPTOPDFENH_PATH . '/wpptopdfenh_header.php';
			// to avoid duplicate function error ( conflict with Lightbox Plus v2.4.6 )
			if ( ! class_exists( 'simple_html_dom' ) )
				require_once WPPTOPDFENH_PATH . '/simplehtmldom/simple-html-dom.php';
			$filePath = WPPTOPDFENH_CACHE_DIR . '/' . $post->post_name . '.pdf';
			// create new PDF document
			if ( isset( $this->options['pageSize'] ) ) {
				$pagesize = ( $this->options['pageSize'] );
			} else {
				$pagesize = PDF_PAGE_FORMAT;
			}
			if ( isset( $this->options['unitMeasure'] ) ) {
				$unit = ( $this->options['unitMeasure'] );
			} else {
				$unit = PDF_UNIT;
			}
			if ( isset( $this->options['orientation'] ) ) {
				$orientation = ( $this->options['orientation'] );
			} else {
				$unit = PDF_PAGE_ORIENTATION;
			}
			$pdf = new MYPDF( $orientation, $unit, $pagesize, true, 'UTF-8', false );
			// Let other filter modify content if selected
			if ( isset( $this->options['otherPlugin'] ) )
				$post->post_content = apply_filters( 'the_content', $post->post_content );
			else
				$post->post_content = wpautop( $post->post_content );
			// Process shortcodes if selected
			if ( isset( $this->options['processShortcodes'] ) ) {
				$post->post_content = do_shortcode( $post->post_content );
			} else {
				$post->post_content = strip_shortcodes( $post->post_content );
			}
			// set document information
			$pdf->SetCreator( 'WP Post to PDF Enhanced plugin by Lewis Rosenthal (http://www.2rosenthals.net/wordpress/help/general-help/wp-post-to-pdf-enhanced/) with ' . PDF_CREATOR );
			$pdf->SetAuthor( get_bloginfo( 'name' ) );
			$pdf->SetTitle( apply_filters( 'the_title', $post->post_title ) );
			// Count width of logo for better presentation
			if ( isset( $this->options['headerlogoImage'] ) ) {
				$logo = ( PDF_HEADER_LOGO );
				$logodata = getimagesize( PDF_HEADER_LOGO );
				if ( isset( $this->options['headerlogoImageFactor'] ) ) {
					$logowidth = (int)( ( ( $this->options['headerlogoImageFactor'] ) * $logodata[0] ) / $logodata[1] );
				} else {
					$logowidth = (int)( ( 14 * $logodata[0] ) / $logodata[1] );
				}
			}
			// new feature under development: specify header/footer text/separator color
			// some addtional header data which should be set in the admin UI; for testing, we're hiding the separator line (note the RGB array)
			//$header_text_color = array( 0,0,0 );
			//$header_line_color = array( 255,255,255 );
			
			// some addtional footer data which should be set in the admin UI; for testing, we're hiding the separator line (note the RGB array)
			//$footer_text_color = array( 0,0,0 );
			//$footer_line_color = array( 255,255,255 );
			
			//$pdf->SetSubject('TCPDF Tutorial');
			//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
			// set default header data, as appropriate for PHP 5.4 or below
			if ( version_compare( phpversion(), '5.4.0', '<' ) ) {
				$pdf->SetHeaderData( $logo, $logowidth, html_entity_decode( get_bloginfo( 'name' ), ENT_COMPAT | ENT_QUOTES ), html_entity_decode( get_bloginfo( 'description' ) . "\n" . home_url(), ENT_COMPAT | ENT_QUOTES ), $header_text_color, $header_line_color );
			} else {
				$pdf->SetHeaderData( $logo, $logowidth, html_entity_decode( get_bloginfo( 'name' ), ENT_COMPAT | ENT_HTML401 | ENT_QUOTES ), html_entity_decode( get_bloginfo( 'description' ) . "\n" . home_url(), ENT_COMPAT | ENT_HTML401 | ENT_QUOTES ), $header_text_color, $header_line_color );
			}
			// set header and footer fonts
			$pdf->setHeaderFont( array( $this->options['headerFont'], '', $this->options['headerFontSize'] ) );
			$pdf->setFooterFont( array( $this->options['footerFont'], '', $this->options['footerFontSize'] ) );
			// set default monospaced font
			$pdf->SetDefaultMonospacedFont( PDF_FONT_MONOSPACED );
			//set margins
			if ( $this->options['marginLeft'] > 0 ) {
				$pdf->SetLeftMargin( $this->options['marginLeft'] );
			} else {
				$pdf->SetLeftMargin( PDF_MARGIN_LEFT );
			}
			if ( $this->options['marginRight'] > 0 ) {
				$pdf->SetRightMargin( $this->options['marginRight'] );
			} else {
				$pdf->SetRightMargin( PDF_MARGIN_RIGHT );
			}
			if ( $this->options['marginTop'] > 0 ) {
				$pdf->SetTopMargin( $this->options['marginTop'] );
			} else {
				$pdf->SetTopMargin( PDF_MARGIN_TOP );
			}
			if ( $this->options['marginHeader'] > 0 ) {
				$pdf->SetHeaderMargin( $this->options['marginHeader'] );
			} else {
				$pdf->SetHeaderMargin( PDF_MARGIN_HEADER );
			}
			if ( $this->options['marginFooter'] > 0 ) {
				$pdf->SetFooterMargin( $this->options['marginFooter'] );
			} else {
				$pdf->SetFooterMargin( PDF_MARGIN_FOOTER );
			}
			//set auto page breaks
			$pdf->SetAutoPageBreak( TRUE, PDF_MARGIN_BOTTOM );
			//set image scale factor
			if ( $this->options['imageScale'] > 0 ) {
				$pdf->setImageScale( $this->options['imageScale'] );
			} else {
				$pdf->setImageScale( PDF_IMAGE_SCALE_RATIO );
			}
			// ---------------------------------------------------------
			// Set the default LI image, if specified
			if ( isset( $this->options['liSymbol'] ) ) {
				$lisymbol = 'img|' . $this->options['liSymbolType'] . '|' . $this->options['liSymbolWidth'] . '|' . $this->options['liSymbolHeight'] . '|' . WP_CONTENT_DIR . '/uploads/' . $this->options['liSymbolFile'];
				$pdf->setLIsymbol( $lisymbol );
			}
			// set default font subsetting mode
			$pdf->setFontSubsetting( true );
			// Set font
			// dejavusans is a UTF-8 Unicode font, if you only need to
			// print standard ASCII chars, you can use core fonts like
			// helvetica or times to reduce file size.
			$pdf->SetFont( $this->options['contentFont'], '', $this->options['contentFontSize'], '', true );
			// Add a page
			// This method has several options, check the source code documentation for more information.
			$pdf->AddPage();
			// Apply global css, if set in config
			if ( $this->options['applyCSS'] ) {
				$html .= '<style>'.$this->options['customCss'].'</style>';
			}
			// Set some content to print
			$html .= '<h1>' . html_entity_decode( $post->post_title, ENT_QUOTES ) . '</h1>';
			// Display author name is set in config
			if ( isset( $this->options['authorDetail'] ) and ! $this->options['authorDetail'] == '' ) {
				$author = get_the_author_meta( $this->options['authorDetail'], $post->post_author );
				$html .= '<p><strong>Author : </strong>'.$author.'</p>';
			}
			// Display category list is set in config
			if ( isset( $this->options['postCategories'] ) ) {
				$categories = get_the_category_list( ', ', '', $post );
				if ( $categories ) {
					$html .= '<p><strong>Categories : </strong>'.$categories.'</p>';
				}
			}
			// Display tag list is set in config
			if ( isset( $this->options['postTags'] ) ) {
				$tags = get_the_tags( $post->the_tags );
				if ( $tags ) {
					$html .= '<p><strong>Tagged as : </strong>';
					foreach ( $tags as $tag ) {
						$tag_link = get_tag_link( $tag->term_id );
						$html .= '<a href="'.$tag_link.'">'.$tag->name.'</a>';
						if ( next( $tags ) ) {
							$html .= ', ';
						}
					}
					$html .= '</p>';
				}
			}
			// Display date if set in config
			if ( isset( $this->options['postDate'] ) ) {
				$date = get_the_date( $post->the_date );
				$html .= '<p><strong>Date : </strong>'.$date.'</p>';
			}
			// Display featured image if set in config and post/page
			if ( isset( $this->options['featuredImage'] ) ) {
				if ( has_post_thumbnail( $post->ID ) ) {
					$html .= get_the_post_thumbnail( $post->ID );
				}
			}
			$html .= htmlspecialchars_decode( htmlentities( $post->post_content, ENT_NOQUOTES, 'UTF-8', false ), ENT_NOQUOTES );
			$dom = new simple_html_dom();
			$dom->load( $html );
			foreach ( $dom->find( 'img' ) as $e ) {
				// Try to respect alignment of images
				// This code is under heavy development, so well-commented
				// First, try to determine the desired alignment from the class attribute inserted by WP.
				// Note that as we're still working with HTML vs CSS, and HTML uses "middle" for center, we
				// have two variables to fill for that possibility.
				if ( preg_match( '/alignleft/i', $e->class ) ) {
					$imgalign = 'left';
				} elseif ( preg_match( '/alignright/i', $e->class ) ) {
					$imgalign = 'right';
				} elseif ( preg_match( '/aligncenter/i', $e->class ) ) {
					$imgalign = 'center';
					$htmlimgalign = 'middle';
				} else {
					$imgalign = 'none';
				}
				// These options apply to all images. Remove any embedded class, which is ignored by TCPDF, anyway;
				// then set an align attribute inside the img tag (for HTML), and finally, a style tag (for CSS).
				$e->class = null;
				$e->align = $imgalign;
				if ( isset ( $htmlimgalign ) ) {
					$e->style= 'float:' . $htmlimgalign;
				} else {
					$e->style= 'float:' . $imgalign;
				}
				// Try to identify SVG images vs JPG or PNG, so that we treat them correctly. Currently, we don't
				// handle these well, so we'll just swap them with placeholder links.
				// Note that we're still using div tags to (harshly) force images into some semblance of horizontal
				// position. This precludes text wrap, and ultimately (if we can get the above working) should be
				// replaced (unless we need the text link) with the CSS in the img tag (if TCPDF will respect it).
				if ( strtolower( substr( $e->src, -4 ) ) == '.svg' ) {
					$e->src = null;
					$e->outertext = '<div style="text-align:' . $imgalign . '">[ SVG: ' . $e->alt . ' ]</div><br/>';
				} else {
					$e->outertext = '<div style="text-align:' . $imgalign . '">' . $e->outertext . '</div>';
				}
			}
			$html = $dom->save();
			$dom->clear();
			// Test TCPDF functions to include here.
			// Presently, we're working with trying to get PDF forms working. These options should go into the admin UI.
			// set default form properties
			$pdf->setFormDefaultProp( array( 'lineWidth'=>1, 'borderStyle'=>'solid', 'fillColor'=>array( 255, 255, 200 ), 'strokeColor'=>array( 255, 128, 128 ) ) );
			// Print text using writeHTML
			$pdf->writeHTML( $html, true, 0, true, 0 );
			// ---------------------------------------------------------
			// Close and output PDF document
			// This method has several options, check the source code documentation for more information.
			// Create directory if not exist
			if ( ! is_dir( WPPTOPDFENH_CACHE_DIR ) ) {
				mkdir( WPPTOPDFENH_CACHE_DIR, 0777, true );
			}
			if ( $forceDownload ) {
				$pdf->Output( $filePath, 'FI' );
			} else {
				$pdf->Output( $filePath, 'F' );
			}
		}
		function add_button( $content ) {
			// If manual is selected, let user decide where to add button; note that this is irrespective of shortcode
			if ( 'manual' == $this->options['iconPosition'] ) {
				return $content;
			}
			// get button html
			$button = $this->display_icon();
			// Set button position
			if ( 'beforeandafter' == $this->options['iconPosition'] ) {
				$content = '<div style=text-align:' . $this->options['iconLeftRight'] . ';>' . $button . '</div>' . $content . '<div style=text-align:' . $this->options['iconLeftRight'] . ';>' . $button . '</div>';
			} elseif ( 'after' == $this->options['iconPosition'] ) {
				$content = $content . '<div style=text-align:' . $this->options['iconLeftRight'] . ';>' . $button . '</div>';
			} else {
				$content = '<div style=text-align:' . $this->options['iconLeftRight'] . ';>' . $button . '</div>' . $content;
			}
			return $content;
		}
		function display_icon() {
			// return nothing if no permission
			if ( isset( $this->options['nonPublic'] ) and ! is_user_logged_in() ) {
				return;
			}
			if ( isset( $this->options['onSingle'] ) and ! ( is_single() or is_page() ) ) {
				return;
			}
			// remove icon from PDF file
			if ( 'pdf' == ( isset( $_GET['format'] ) ? $_GET['format'] : null ) ) {
				return;
			}
			global $post;
			if ( ! isset( $this->options[$post->post_type] ) ) {
				return false;
			}
			// return nothing if post in exclude list
			$include = $this->options['include'];
			$excludeThis = explode( ',', $this->options['excludeThis'] );
			if ( $include and ! in_array( $post->ID, $excludeThis ) ) {
				return;
			}
			if ( ! $include and in_array( $post->ID, $excludeThis ) ) {
				return;
			}
			// Create link
			if ( ! is_singular() ) {
				return '<a class="wpptopdfenh" target="_blank" rel="noindex,nofollow" href="' . esc_url( add_query_arg( 'format', 'pdf', get_permalink( $post->ID ) ) ) . '" title="Download PDF">' . $this->options['imageIcon'] . '</a>';
			} else {
				return '<a class="wpptopdfenh" target="_blank" rel="noindex,nofollow" href="' . esc_url( add_query_arg( 'format', 'pdf' ) ) . '" title="Download PDF">' . $this->options['imageIcon'] . '</a>';
			}
		}
		// If the icon shortcode is used, render the icon where positioned in the body (the icon is invisible in the resulting PDF).
		function display_shortcode_icon() {
			// return nothing if no permission
			if ( isset( $this->options['nonPublic'] ) and ! is_user_logged_in() ) {
				return;
			}
			if ( isset( $this->options['onSingle'] ) and ! ( is_single() or is_page() ) ) {
				return;
			}
			// remove icon from PDF file
			if ( 'pdf' == ( isset( $_GET['format'] ) ? $_GET['format'] : null ) ) {
				return;
			}
			global $post;
			// Create link
			if ( ! is_singular() ) {
				return '<a class="wpptopdfenh" target="_blank" rel="noindex,nofollow" href="' . add_query_arg( 'format', 'pdf', get_permalink( $post->ID ) ) . '" title="Download PDF">' . $this->options['imageIcon'] . '</a>';
			} else {
				return '<a class="wpptopdfenh" target="_blank" rel="noindex,nofollow" href="' . add_query_arg( 'format', 'pdf' ) . '" title="Download PDF">' . $this->options['imageIcon'] . '</a>';
			}
		}
		function on_activate() {
			// set default options on activate
			$default = array(
				'post'                  => 1,
				'page'                  => 1,
				'include'               => 0,
				'includeCache'          => 0,
				'iconPosition'          => 'before',
				'iconLeftRight'         => 'left',
				'imageIcon'             => '<img alt="Download PDF" src="' . WPPTOPDFENH_URL . '/asset/images/pdf.png">',
				'headerlogoImageFactor' => 14,
				'imageScale'            => 1.25,
				'headerAllPages'        => 'all',
				'headerFont'            => 'helvetica',
				'headerFontSize'        => 10,
				'footerFont'            => 'helvetica',
				'footerFontSize'        => 10,
				'contentFont'           => 'helvetica',
				'contentFontSize'       => 12,
				'marginHeader'          => 5,
				'marginTop'             => 27,
				'marginLeft'            => 15,
				'marginRight'           => 15,
				'marginFooter'          => 10,
				'footerMinHeight'       => 0,
				'footerWidth'           => 0,
				'footerX'               => 10,
				'footerY'               => 260,
				'footerFill'            => 0,
				'footerPad'             => 1,
				'pageSize'              => 'LETTER',
				'unitMeasure'           => 'mm',
				'orientation'           => 'P',
				'liSymbolType'          => 'jpg',
				'liSymbolWidth'         => 3,
				'liSymbolHeight'        => 2,
				'pluginVer' 		=> WPPTOPDFENH_VERSION_NUM,
			);
			if ( ! get_option( 'wpptopdfenh' ) ) {
				add_option( 'wpptopdfenh', $default );
			}
			// create directory and move logo to upload directory
			if ( ! is_dir( WP_CONTENT_DIR . '/uploads' ) ) {
				mkdir( WP_CONTENT_DIR . '/uploads', 0777, true );
			}
			if ( ! file_exists( WP_CONTENT_DIR . '/uploads/wp-post-to-pdf-enhanced-logo.png' ) ) {
				copy( WPPTOPDFENH_PATH . '/asset/images/logo.png', WP_CONTENT_DIR . '/uploads/wp-post-to-pdf-enhanced-logo.png' );
			}
			if ( ! is_dir( WP_CONTENT_DIR . '/uploads/wp-post-to-pdf-enhanced-cache' ) ) {
				mkdir( WP_CONTENT_DIR . '/uploads/wp-post-to-pdf-enhanced-cache', 0777, true );
			}
		}
		function on_upgrade() {
			// Check if we are doing an upgrade. If the version option is not set, set it to the last version before this
			// was stored in the db (it should fail gracefully, if already set). Then add options from the array above which
			// have been added in versions later than what we are upgrading. Later, we can use this to migrate our options
			// to our own table, too.

			if ( !isset ( $this->options['marginHeader'] ) )
				update_option( $this->options['marginHeader'], 5 );
			if ( !isset ( $this->options['marginTop'] ) )
				update_option( $this->options['marginTop'], 27 );
			if ( !isset ( $this->options['marginLeft'] ) )
				update_option( $this->options['marginLeft'], 15 );
			if ( !isset ( $this->options['marginRight'] ) )
				update_option( $this->options['marginRight'], 15 );
			if ( !isset ( $this->options['marginFooter'] ) )
				update_option( $this->options['marginFooter'], 10 );
			if ( !isset ( $this->options['footerMinHeight'] ) )
				update_option( $this->options['footerMinHeight'], 0 );
			if ( !isset ( $this->options['footerWidth'] ) )
				update_option( $this->options['footerWidth'], 0 );
			if ( !isset ( $this->options['footerX'] ) )
				update_option( $this->options['footerX'], 10 );
			if ( !isset ( $this->options['footerY'] ) )
				update_option( $this->options['footerY'], 260 );
			if ( !isset ( $this->options['footerFill'] ) )
				update_option( $this->options['footerFill'], 0 );
			if ( !isset ( $this->options['footerPad'] ) )
				update_option( $this->options['footerPad'], 1 );
			if ( !isset ( $this->options['pageSize'] ) )
				update_option( $this->options['pageSize'], 'LETTER' );
			if ( !isset ( $this->options['unitMeasure'] ) )
				update_option( $this->options['unitMeasure'], 'mm' );
			if ( !isset ( $this->options['orientation'] ) )
				update_option( $this->options['orientation'], 'P' );
			if ( !isset ( $this->options['liSymbolType'] ) )
				update_option( $this->options['liSymbolType'], 'jpg' );
			if ( !isset ( $this->options['liSymbolWidth'] ) )
				update_option( $this->options['liSymbolWidth'], 3 );
			if ( !isset ( $this->options['liSymbolHeight'] ) )
				update_option( $this->options['liSymbolHeight'], 2 );
			// Finally, set the new version in the db
			update_option( $this->options['pluginVer'], WPPTOPDFENH_VERSION_NUM );
		}
		function on_pre_post_update( $id ) {
			$post = get_post();
			$filePath = WPPTOPDFENH_CACHE_DIR . '/' . $post->post_name . '.pdf';
			if ( file_exists( $filePath ) ) {
				unlink( $filePath );
			}
		}
	}
	$wpptopdfenh = new wpptopdfenh();
	/**
	 * Display PDF download icon if applicable
	 *
	 * @return void
	 */
	if ( ! function_exists( 'wpptopdfenh_display_icon' ) ) {
		function wpptopdfenh_display_icon() {
			global $wpptopdfenh;
			//$wpptopdfenh = new wpptopdfenh();
			return $wpptopdfenh->display_icon();
		}
	}
	// Regardless the setting in config, if the shortcode is present, we want to render the icon.
	if ( ! function_exists( 'wpptopdfenh_display_shortcode_icon' ) ) {
		function wpptopdfenh_display_shortcode_icon() {
			global $wpptopdfenh;
			return $wpptopdfenh->display_shortcode_icon();
		}
		/**
		 * Include shortcode file
		 *
		 */
		include WPPTOPDFENH_PATH . '/wpptopdfenh_shortcodes.php';
	}
}
