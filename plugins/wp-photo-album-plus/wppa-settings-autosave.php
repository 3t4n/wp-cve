<?php
/* wppa-settings-autosave.php
* Package: wp-photo-album-plus
*
* manage all options
* Version: 8.6.04.008
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

function _wppa_page_options() {
global $wpdb;
global $wppa;
global $wppa_opt;
global $blog_id;
global $opts_error;
global $wppa_version;
global $wp_roles;
global $wppa_revno;
global $no_default;
global $wp_version;
global $wppa_supported_camara_brands;
global $wppa_cur_tab;
global $wppa_cur_mtab;
global $wppa_requested_subtab;
global $wppa_requested_items;
global $wppa_tab_names;
global $wppa_subtab_names;
global $wppa_hide_this;

	// Start test area

	if ( is_file( dirname( __FILE__ ) . '/wppatestcode.php' ) ) include 'wppatestcode.php';

	// End test area

	// Initialize
	wppa_initialize_runtime( true );
	$opts_error = false;

	// Re-animate crashec cron jobs
	wppa_re_animate_cron();

	// If watermark all is going to be run, make sure the current user has no private overrule settings
	delete_option( 'wppa_watermark_file_'.wppa_get_user() );
	delete_option( 'wppa_watermark_pos_'.wppa_get_user() );

	$key = '';
	// Someone hit a submit button or the like?
	if ( wppa_get( 'settings-submit' ) ) {	// Yep!

		if ( ! wp_verify_nonce( wppa_get( 'nonce' ), 'wppa-nonce' ) ) {
			wp_die( 'Security check failuere' );
		}

		$key = wppa_get( 'key' );
		$sub = wppa_get( 'sub' );

		// Switch on action key
		switch ( $key ) {

			// Must be here
			case 'wppa_moveup':
				if ( wppa_switch( 'split_namedesc') ) {
					$sequence = wppa_opt( 'slide_order_split' );
					$indices = explode(',', $sequence);
					$temp = $indices[$sub];
					$indices[$sub] = $indices[$sub - '1'];
					$indices[$sub - '1'] = $temp;
					wppa_update_option('wppa_slide_order_split', implode(',', $indices));
				}
				else {
					$sequence = wppa_opt( 'slide_order' );
					$indices = explode(',', $sequence);
					$temp = $indices[$sub];
					$indices[$sub] = $indices[$sub - '1'];
					$indices[$sub - '1'] = $temp;
					wppa_update_option('wppa_slide_order', implode(',', $indices));
				}
				break;

			// Should better be here
			case 'wppa_setup':
				wppa_setup(true); // Message on success or fail is in the routine
				wppa_ok_message( __( 'Plugin successfully set up' , 'wp-photo-album-plus' ) );
				break;

			// Must be here
			case 'wppa_backup':
				wppa_backup_settings();	// Message on success or fail is in the routine
				break;

			// Must be here
			case 'wppa_load_skin':

				$fname = wppa_opt( 'skinfile' );

				if ( $fname == '' ) {
					wppa_error_message( __( 'Please select a valid option first', 'wp-photo-album-plus' ) );
				}
				elseif ( $fname == 'default' ) {
					if ( wppa_set_defaults( true ) ) {
						wppa_ok_message( __( 'Reset to default settings', 'wp-photo-album-plus' ) );
					}
					else {
						wppa_error_message( __( 'Unable to set defaults', 'wp-photo-album-plus' ) );
						$opts_error = true;
					}
				}
				else {
					$filename = $fname;//wppa_opt( 'backup_filename' );
					$ext = wppa_get_ext( basename( $filename ) );
					$type = ( $ext == 'skin' ? 'skin' : 'backup' );

					if ( wppa_restore_settings( $filename, $type ) ) {
						if ( $type == 'skin' ) {
							wppa_ok_message( sprintf( __( 'Skinfile %s loaded', 'wp-photo-album-plus' ), basename( $filename ) ) );
						}
						else {
							wppa_ok_message( sprintf( __( 'Saved settings restored from %s', 'wp-photo-album-plus' ), basename( $filename ) ) );
						}
					}
					else {
						wppa_error_message( __( 'Unable to restore saved settings', 'wp-photo-album-plus' ) );
						$opts_error = true;
					}
				}
				delete_option( 'wppa_skinfile' );
				break;

			// Must be here
			case 'wppa_watermark_file_upload':
				if ( isset($_FILES['file_1']) && $_FILES['file_1']['error'] != 4 ) { // Expected a fileupload for a watermark
					$file = $_FILES['file_1'];
					if ( $file['error'] ) {
						wppa_error_message(sprintf(__('Upload error %s', 'wp-photo-album-plus' ), $file['error']));
					}
					else {
						$imgsize = getimagesize($file['tmp_name']);
						if ( !is_array($imgsize) || !isset($imgsize[2]) || $imgsize[2] != 3 ) {
							wppa_error_message(sprintf(__('Uploaded file %s is not a .png file', 'wp-photo-album-plus' ), sanitize_file_name( $file['name'] ) ) . ' (Type='.$file['type'].').');
						}
						else {
							wppa_copy( $file['tmp_name'], WPPA_UPLOAD_PATH . '/watermarks/' . strtolower(sanitize_file_name(basename($file['name']))));
							wppa_alert(sprintf(__('Upload of %s done', 'wp-photo-album-plus' ), strtolower(sanitize_file_name(basename($file['name'])))));
						}
					}
				}
				else {
					wppa_error_message(__('No file selected or error on upload', 'wp-photo-album-plus' ));
				}
				break;

			case 'wppa_watermark_font_upload':
				if ( isset($_FILES['file_1']) && $_FILES['file_1']['error'] != 4 ) { // Expected a fileupload for a watermark font file
					$file = $_FILES['file_1'];
					if ( $file['error'] ) {
						wppa_error_message(sprintf(__('Upload error %s', 'wp-photo-album-plus' ), $file['error']));
					}
					else {
						if ( substr(sanitize_file_name($file['name']), -4) != '.ttf' ) {
							wppa_error_message(sprintf(__('Uploaded file %s is not a .ttf file', 'wp-photo-album-plus' ), sanitize_file_name($file['name']) ).' (Type='.$file['type'].').');
						}
						else {
							wppa_copy($file['tmp_name'], WPPA_UPLOAD_PATH . '/fonts/' . sanitize_file_name(basename($file['name'])));
							wppa_alert(sprintf(__('Upload of %s done', 'wp-photo-album-plus' ), sanitize_file_name(basename($file['name']))));
						}
					}
				}
				else {
					wppa_error_message(__('No file selected or error on upload', 'wp-photo-album-plus' ));
				}
				break;

			case 'wppa_audiostub_upload':
				if ( isset( $_FILES['file_1'] ) && $_FILES['file_1']['error'] != 4 ) {
					$file = $_FILES['file_1'];
					if ( $file['error'] ) {
						wppa_error_message(sprintf(__('Upload error %s', 'wp-photo-album-plus' ), $file['error']));
					}
					elseif ( wppa_get_ext( $file['name'] ) != 'jpg' ) {
						wppa_error_message( __('File MUST be a .jpg image file', 'wp-photo-album-plus' ));
					}
					else {
						$imgsize = getimagesize( $file['tmp_name'] );
						if ( ! is_array( $imgsize ) ) {
							wppa_error_message(sprintf(__('Uploaded file %s is not a valid image file', 'wp-photo-album-plus' ), sanitize_file_name($file['name'])).' (Type='.$file['type'].').');
						}
						else {
							wppa_copy( $file['tmp_name'], WPPA_UPLOAD_PATH . '/audiostub.jpg' );

							// Thumbx, thumby, phtox and photoy must be cleared for the new stub
							$wpdb->query( "UPDATE $wpdb->wppa_photos
										   SET thumbx = 0, thumby = 0, photox = 0, photoy = 0
										   WHERE ext = 'xxx'"
										  );
							wppa_alert( sprintf( __( 'Upload of %s done', 'wp-photo-album-plus' ), basename( sanitize_file_name( $file['name'] ) ) ) );

							wppa_bump_photo_rev();
							wppa_bump_thumb_rev();
						}
					}
				}
				else {
					wppa_error_message(__('No file selected or error on upload', 'wp-photo-album-plus' ));
				}
				break;

			case 'wppa_documentstub_upload':
				if ( isset( $_FILES['file_1'] ) && $_FILES['file_1']['error'] != 4 ) {
					$file = $_FILES['file_1'];
					if ( $file['error'] ) {
						wppa_error_message(sprintf(__('Upload error %s', 'wp-photo-album-plus' ), $file['error']));
					}
					elseif ( wppa_get_ext( $file['name'] ) != 'png' ) {
						wppa_error_message( __('File MUST be a .png image file', 'wp-photo-album-plus' ));
					}
					else {
						$imgsize = getimagesize( $file['tmp_name'] );
						if ( ! is_array( $imgsize ) ) {
							wppa_error_message(sprintf(__('Uploaded file %s is not a valid image file', 'wp-photo-album-plus' ), sanitize_file_name($file['name'])).' (Type='.$file['type'].').');
						}
						else {
							wppa_copy( $file['tmp_name'], WPPA_UPLOAD_PATH . '/documentstub.png' );

							// Thumbx, thumby, phtox and photoy must be cleared for the new stub
							$wpdb->query( "UPDATE $wpdb->wppa_photos
										   SET thumbx = 0, thumby = 0, photox = 0, photoy = 0
										   WHERE ext = 'pdf'"
										  );
							wppa_alert( sprintf( __( 'Upload of %s done', 'wp-photo-album-plus' ), basename( sanitize_file_name( $file['name'] ) ) ) );

							wppa_bump_photo_rev();
							wppa_bump_thumb_rev();
						}
					}
				}
				else {
					wppa_error_message(__('No file selected or error on upload', 'wp-photo-album-plus' ));
				}
				break;

			case 'wppa_multimedia_icon_upload':
				if ( isset( $_FILES['file_1'] ) && $_FILES['file_1']['error'] != 4 ) {
					$file = $_FILES['file_1'];
					if ( $file['error'] ) {
						wppa_error_message(sprintf(__('Upload error %s', 'wp-photo-album-plus' ), $file['error']));
					}
					else {
						$ext = strtolower( wppa_get_ext( $file['name'] ) );
						if ( ! in_array( $ext, array( 'jpeg', 'jpg', 'png', 'svg', 'gif', 'bmp', 'ico' ) ) ) {
							wppa_error_message(sprintf(__('Uploaded file %s is not a valid image file', 'wp-photo-album-plus' ), sanitize_file_name($file['name'])).' (Type='.$file['type'].').');
						}
						else {
							wppa_copy( $file['tmp_name'], WPPA_UPLOAD_PATH . '/icons/' . ucfirst( strtolower( sanitize_file_name( $file['name'] ) ) ) );
							wppa_alert( sprintf( __( 'Upload of %s done', 'wp-photo-album-plus' ), basename( sanitize_file_name( $file['name'] ) ) ) );
						}
					}
				}
				else {
					wppa_error_message(__('No file selected or error on upload', 'wp-photo-album-plus' ));
				}
				break;

			case 'wppa_delete_all_from_cloudinary':
				$bret = wppa_delete_all_from_cloudinary();
				if ( $bret ) {
					wppa_ok_message('Done! wppa_delete_all_from_cloudinary');
				}
				else {
					wppa_ok_message( 'Not yet Done! wppa_delete_all_from_cloudinary' .
									'<br>Please restart' );
				}
				break;

			case 'wppa_delete_derived_from_cloudinary':
				$bret = wppa_delete_derived_from_cloudinary();
				if ( $bret ) {
					wppa_ok_message('Done! wppa_delete_derived_from_cloudinary');
				}
				else {
					wppa_ok_message( 'Not yet Done! wppa_delete_derived_from_cloudinary' .
									'<br>Please restart' );
				}
				break;

			case 'dummy':
				break;

			default: wppa_error_message( 'Unimplemnted action key: ' . htmlentities( $key ) );
		}

		// Make sure we are uptodate
		wppa_update_option( 'wppa_backup_filename', '' );
		wppa_initialize_runtime( true );

	} // wppa-settings-submit

	// Fix invalid source path
	wppa_fix_source_path();

	// Spinner image
	wppa_admin_spinner();

	// Open page
	wppa_echo( '
	<div class="wrap">
		<h1 class="wp-heading-inline">' .
			get_admin_page_title() . '
		</h1>' );

		if ( is_multisite() ) {
			if ( WPPA_MULTISITE_GLOBAL ) {
				wppa_echo( __( 'Multisite in singlesite mode.', 'wp-photo-album-plus' ) );
			}
			else {
				wppa_echo(
				__( 'Multisite enabled.', 'wp-photo-album-plus' ) .
				' ' .
				__( 'Blogid =', 'wp-photo-album-plus' ) .
				' ' . $blog_id );
			}
		}

		// Blacklist
		$blacklist_plugins = array(
			'wp-fluid-images/plugin.php',
			'performance-optimization-order-styles-and-javascript/order-styles-js.php',
			'wp-ultra-simple-paypal-shopping-cart/wp_ultra_simple_shopping_cart.php',
			'cachify/cachify.php',
			'wp-deferred-javascripts/wp-deferred-javascripts.php',
			'frndzk-photo-lightbox-gallery/frndzk_photo_gallery.php',
			'simple-lightbox/main.php',
			'amp/amp.php',
			'meow-lightbox/meow-lightbox.php',
			'disable-gutenberg/disable-gutenberg.php',
			);
		$plugins = wppa_get_option('active_plugins');

		$matches = array_intersect($blacklist_plugins, $plugins);
		foreach ( $matches as $bad ) {
			wppa_error_message(__('Please de-activate plugin <i style="font-size:14px;">', 'wp-photo-album-plus' ).substr($bad, 0, strpos($bad, '/')).__('. </i>This plugin will cause wppa+ to function not properly.', 'wp-photo-album-plus' ));
		}

		// Graylist
		$graylist_plugins = array(
			'shortcodes-ultimate/shortcodes-ultimate.php',
			'tablepress/tablepress.php',
			'responsive-lightbox/responsive-lightbox.php',
			);
		$matches = array_intersect($graylist_plugins, $plugins);
		foreach ( $matches as $bad ) {
			wppa_warning_message(__('Please note that plugin <i style="font-size:14px;">', 'wp-photo-album-plus' ).substr($bad, 0, strpos($bad, '/')).__('</i> can cause wppa+ to function not properly if it is misconfigured.', 'wp-photo-album-plus' ));
		}

		// Check for trivial requirements
		if ( ! function_exists('imagecreatefromjpeg') ) {
			wppa_error_message(__('There is a serious misconfiguration in your servers PHP config. Function imagecreatefromjpeg() does not exist. You will encounter problems when uploading photos and not be able to generate thumbnail images. Ask your hosting provider to add GD support with a minimal version 1.8.', 'wp-photo-album-plus' ));
		}

		// Cron disabled?
		if ( wppa_get( 'dismisscronmsg', 0, 'int' ) ) {

			// Disable it now
			update_option( 'wppa_cronmsg_is_dismissed', true );
		}
		elseif ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON == true && ! get_option('wppa_cronmsg_is_dismissed', false) ) {
			$url = admin_url( 'admin.php?page=wppa_options&wppa-dismisscronmsg=1' );
			$msg = __("Please remove 'define( 'DISABLE_WP_CRON', true );' from wp-config.php, or make sure WP-Cron runs at a regular basis! It is essential for wppa", 'wp-photo-album-plus' );
			$msg .= '&nbsp;<span style="float:right"><a href="'.$url.'"> ' . __('Dismiss permanently', 'wp-photo-album-plus') . '</a></span><div style="clear:both;margin-top:-8px;"></div>';
			wppa_error_message($msg);
		}

		// Check for inconsistencies in thumbnails
		if ( ( wppa_opt( 'thumbtype' ) == 'default' ) && (
			wppa_opt( 'tf_width' ) < wppa_opt( 'thumbsize' ) ||
			wppa_opt( 'tf_width_alt') < wppa_opt( 'thumbsize_alt' ) ||
			wppa_opt( 'tf_height' ) < wppa_opt( 'thumbsize' ) ||
			wppa_opt( 'tf_height_alt') < wppa_opt( 'thumbsize_alt' ) ) ) {
				wppa_warning_message( __( 'A thumbframe width or height should not be smaller than a thumbnail size.' , 'wp-photo-album-plus' ) . wppa_see_also( 'thumbs', '1', '1.2.5..8', '', '', true ) );
			}

		// Check for 'many' albums
		if ( wppa_has_many_albums() ) {
			wppa_warning_message( 	__( 'This system contains more albums than the maximum configured.', 'wp-photo-album-plus' ) . wppa_see_also( 'admin', '6', '6' ) . '<br>' .
									__( 'No problem, but some widgets may not work and some album selectionboxes will revert to a simple input field asking for an album id.', 'wp-photo-album-plus' ) . ' ' .
									__( 'If you do not have pageload performance problems, you may increase the number number configured.', 'wp-photo-album-plus' ) . '<br>' .
									__( 'If there are many empty albums, you can simply remove them by running the appropriate maintenance routine.', 'wp-photo-album-plus' ) . wppa_see_also( 'maintenance', '2', '9' ) );
		}

		// Check for availability of hires urls in case panorama is on
		if ( wppa_switch( 'enable_panorama' ) ) {
			if ( ! wppa_switch( 'keep_source' ) ) {
				wppa_warning_message( 	__( 'You enabled the display of complex panoramic photos', 'wp-photo-album-plus' ) . wppa_see_also( 'photos', '1', '3' ) . '<br>' .
										__( 'It is strongly recommended that you save sourcefiles during upload in order to preserve resolution.', 'wp-photo-album-plus' ) . wppa_see_also( 'files', '1', '1', '', '', true ) );
			}
		}

		// Check on configuration when Grid covertype has been selected.
		if ( wppa_opt( 'cover_type' ) == 'grid' ) {
			$stdmsg = 	__( 'You selected covertype "Grid with images only".', 'wp-photo-album-plus' ) . wppa_see_also( 'covers', '3', '4' ) . '<br>' .
						__( 'To assure proper layout, please correct the following configuration issues.', 'wp-photo-album-plus' ) . '<br>';
			$message = $stdmsg;
			$doit = false;
			if ( wppa_opt( 'max_cover_width' ) > wppa_opt( 'smallsize' ) ) {
				$msg = __( 'Max Cover width may not be larger than Coverphoto size', 'wp-photo-album-plus' ) . wppa_see_also( 'covers', '1', '1.7', '', '', true ) . '<br>';
				$message .= $msg;
				$doit = true;
			}
			if ( wppa_opt( 'thumb_aspect' ) == '0:0:none' && ! ( wppa_switch( 'cover_use_thumb' ) ) ) {
				$msg = 	__( 'Thumbnail Aspect may not be set to "--- same as fullsize ---"','wp-photo-album-plus' ) . wppa_see_also( 'thumbs', '1', '3', '', '', true ) . '<br>' .
						__( 'Alternatively you can activate Use thumb on cover', 'wp-photo-album-plus' ) . wppa_see_also( 'covers', '3', '8' );
				$message .= $msg;
				$doit = true;
			}
			if ( $doit ) {
				wppa_warning_message( $message );
			}
		}

		// Check for ImageMagick
		if ( ! wppa_opt( 'image_magick' ) ) {
			if ( class_exists( 'Imagick' ) && function_exists( 'exec' ) ) {

				$result = wppa_search_magick();
				$mes =  __('Image Magick is detected on your server', 'wp-photo-album-plus' ) . '. ';
				$mes .= __('To be able to use the most advanced features of WPPA, the Imagic shellcommand <b>convert</b> must be available', 'wp-photo-album-plus' ) . '.<br>';

				// Shell command found
				if ( ! empty( $result ) ) {
					$mes .= __('This command is found on the following file system locations', 'wp-photo-album-plus' ) . ':<br>';
					foreach ( $result as $item ) {
						$mes .= $item[0] . '<br>';
					}
					wppa_update_option( 'wppa_image_magick', $result[0][0] );
					$wppa_opt['wppa_image_magick'] = $result[0][0];
					$mes .= sprintf( __('The location <b>%s</b> has been activated.', 'wp-photo-album-plus' ), $result[0][0] ) . wppa_see_also( 'miscadv', '3', '13' ) . '<br>';
					$mes .= __('You may change it into a different path that contains the Imagick <b>convert</b> command at any time', 'wp-photo-album-plus' ) . '.<br>';
					$mes .= __('To disable ImageMagick, enter <b>none</b>', 'wp-photo-album-plus' );
				}

				// Shell command not found
				else {
					$mes .= __('The Imagick shellcommand <b>convert</b> could not be detected.', 'wp-photo-album-plus' ) . '<br >';
					$mes .= __('Ask your hosting provider for the absolute path to this command if it is available', 'wp-photo-album-plus' ) . '<br>';
				}

				wppa_ok_message($mes);
			}
		}

		// Check for ttf support
		if ( ! function_exists( 'imagettfbbox' ) ) {
			wppa_error_message( __('Your PHP version does not support TrueType fonts. This means that you can not apply textual watermarks', 'wp-photo-album-plus' ) );
		}

		// The nonce field
		wp_nonce_field( 'wppa-nonce', 'wppa-nonce' );

		// Any tab set? else default general
		$tab = wppa_get( 'tab', 'general' );
		$subtab = wppa_get( 'subtab', '0' );

		// Get the linkpages dependant of tab (if we need them)
		if ( $tab == 'share' || $tab == 'links' ) {

			// Linkpages
			$opts_page = array();
			$opts_page_post = array();
			$vals_page = array();
			$vals_page_post = array();

			// First
			$opts_page_post[] = __('--- the same page or post ---', 'wp-photo-album-plus' );
			$vals_page_post[] = '0';
			$opts_page[] = __('--- please select a page ---', 'wp-photo-album-plus' );
			$vals_page[] = '0';

			// Pages if any
			$pages = $wpdb->get_results( "SELECT ID, post_title, post_content, post_parent FROM $wpdb->posts
										  WHERE post_type = 'page'
										  AND post_status = 'publish'
										  ORDER BY post_title", ARRAY_A );
			if ( $pages ) {

				// Translate
				foreach ( array_keys($pages) as $index ) {
					$pages[$index]['post_title'] = __(stripslashes($pages[$index]['post_title']), 'wp-photo-album-plus' );
				}

				$pages = wppa_array_sort($pages, 'post_title');
				foreach ($pages as $page) {
					if (strpos($page['post_content'], '%%wppa%%') !== false || strpos($page['post_content'], '[wppa') !== false) {
						$opts_page[] = __($page['post_title'], 'wp-photo-album-plus' );
						$opts_page_post[] = __($page['post_title'], 'wp-photo-album-plus' );
						$vals_page[] = $page['ID'];
						$vals_page_post[] = $page['ID'];
					}
					else {
						$opts_page[] = '|'.__($page['post_title'], 'wp-photo-album-plus' ).'|';
						$opts_page_post[] = '|'.__($page['post_title'], 'wp-photo-album-plus' ).'|';
						$vals_page[] = $page['ID'];
						$vals_page_post[] = $page['ID'];
					}
				}
			}
			else {
				$opts_page[] = __( '--- No page to link to (yet) ---', 'wp-photo-album-plus' );
				$vals_page[] = '0';
			}

			$opts_page_auto = $opts_page;
			$opts_page_auto[0] = __( '--- Will be auto created ---', 'wp-photo-album-plus' );
		}

		// Find matching master tab
		$wppa_cur_tab = $tab;
		if ( in_array( $tab, array(	'general',
									'layout',
									'covers',
									'photos',
									'thumbs',
									'slide',
									'lightbox',
									'comments',
									'rating',
									'search',
									'widget',
									'links',
									'misc',
									) ) ) {
			$wppa_cur_mtab = 'basic';
			$basic = true;
			$advan = false;
		}
		else {
			$wppa_cur_mtab = 'advanced';
			$basic = false;
			$advan = true;
		}

		// See if specific item is requested
		$wppa_requested_subtab = $subtab;
		$wppa_requested_items = wppa_get( 'item', array( '0' ) );
		if ( ! is_array( $wppa_requested_items ) ) {
			$wppa_requested_items = explode( '.', wppa_expand_enum( $wppa_requested_items ) );
		}

		// Init security message
		$security_no_disable = '<input type="checkbox" checked disabled><span style="margin-left:20px; color:darkred">' . __( 'Due to security reasons this setting can no longer be switched off', 'wp-photo-album-plus' ) . '</span>';
		$security_no_enable  = '<input type="checkbox" disabled><span style="margin-left:20px; color:darkred">' . __( 'Due to security reasons this setting can no longer be switched on', 'wp-photo-album-plus' ) . '</span>';

		// The master header selectors
		wppa_echo( '<ul class="widefat wppa-master-tabs">' );
			wppa_master_tab( 'basic', 		'general',		__( 'Basic settings', 'wp-photo-album-plus' ), 		$basic );
			wppa_master_tab( 'advanced',	'generaladv', 	__( 'Advanced settings', 'wp-photo-album-plus' ), 	$advan );
		wppa_echo( '</ul>' );

		// The tabs
		{
		wppa_echo( '<ul class="widefat wppa-setting-tabs">' );
			wppa_setting_tab( 'general', 		$wppa_tab_names['general'], 	$basic );
			wppa_setting_tab( 'generaladv', 	$wppa_tab_names['generaladv'], 	$advan );
			wppa_setting_tab( 'layout', 		$wppa_tab_names['layout'], 		$basic );
			wppa_setting_tab( 'covers', 		$wppa_tab_names['covers'], 		$basic );
			wppa_setting_tab( 'photos', 		$wppa_tab_names['photos'], 		$basic );
			wppa_setting_tab( 'thumbs', 		$wppa_tab_names['thumbs'], 		$basic );
			wppa_setting_tab( 'slide', 			$wppa_tab_names['slide'], 		$basic );
			wppa_setting_tab( 'lightbox', 		$wppa_tab_names['lightbox'], 	$basic );
			wppa_setting_tab( 'comments', 		$wppa_tab_names['comments'], 	$basic && wppa_switch( 'show_comments' ) );
			wppa_setting_tab( 'rating', 		$wppa_tab_names['rating'], 		$basic && wppa_switch( 'rating_on' ) );
			wppa_setting_tab( 'search', 		$wppa_tab_names['search'], 		$basic );
			wppa_setting_tab( 'widget', 		$wppa_tab_names['widget'], 		$basic );
			wppa_setting_tab( 'links', 			$wppa_tab_names['links'], 		$basic );
			wppa_setting_tab( 'users', 			$wppa_tab_names['users'], 		$advan && wppa_switch( 'user_upload_on' ) );
			wppa_setting_tab( 'email', 			$wppa_tab_names['email'], 		$advan && wppa_switch( 'email_on' ) );
			wppa_setting_tab( 'share', 			$wppa_tab_names['share'], 		$advan && ( wppa_switch( 'share_on' ) || wppa_switch( 'share_on_lightbox' ) ) );
			wppa_setting_tab( 'system', 		$wppa_tab_names['system'], 		$advan );
			wppa_setting_tab( 'files',			$wppa_tab_names['files'], 		$advan );
			wppa_setting_tab( 'new', 			$wppa_tab_names['new'], 		$advan );
			wppa_setting_tab( 'admin', 			$wppa_tab_names['admin'], 		$advan );
			wppa_setting_tab( 'maintenance', 	$wppa_tab_names['maintenance'], $advan );
			wppa_setting_tab( 'exif', 			$wppa_tab_names['exif'], 		$advan && wppa_switch( 'save_exif' ) && function_exists('exif_read_data') );
			wppa_setting_tab( 'iptc', 			$wppa_tab_names['iptc'],		$advan && wppa_switch( 'save_iptc' ) && function_exists('exif_read_data') );
			wppa_setting_tab( 'gpx', 			$wppa_tab_names['gpx'],			$advan && wppa_switch( 'save_gpx' ) && wppa_switch( 'save_exif' ) && function_exists('exif_read_data') );
			wppa_setting_tab( 'watermark', 		$wppa_tab_names['watermark'], 	$advan && wppa_switch( 'watermark_on' ) );
			wppa_setting_tab( 'custom', 		$wppa_tab_names['custom'], 		$advan && ( wppa_switch( 'album_custom_fields' ) || wppa_switch( 'custom_fields' ) ) );
			wppa_setting_tab( 'constants', 		$wppa_tab_names['constants'], 	$advan );
			wppa_setting_tab( 'misc', 			$wppa_tab_names['misc'], 		$basic );
			wppa_setting_tab( 'miscadv', 		$wppa_tab_names['miscadv'], 	$advan );
		wppa_echo( '</ul>' );
		}

		// For layout we need a clear
		wppa_echo( '<div class="clear"></div>' );

		// The local js
		wppa_add_local_js( '_wppa_page_options', $tab, $subtab );

		// Open the content area
		wppa_echo( '<div id="wppa-setting-content" style="display:none;clear:both">' );

			// Dispatch on tab
			switch($tab) {

				case 'general':
				case 'generaladv': {
					// On this tab you can select the features you want to use
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Enable Photo', 'wp-photo-album-plus' );
						$desc = __('Enables photo support', 'wp-photo-album-plus' );
						$help = __('This item can not be unchecked, this is the core feature of the plugin', 'wp-photo-album-plus' );
						$slug = '';
						$html = '<input type="checkbox" style="float:left" checked disabled>' . wppa_see_also( 'photos', '1' );
						wppa_setting_new($slug, '0', $name, $desc, $html, $help);

						$name = __('Enable Video', 'wp-photo-album-plus' );
						$desc = __('Enables video support.', 'wp-photo-album-plus' );
						$help = __('Check this box to enable the upload and display of video files', 'wp-photo-album-plus' );
						$slug = 'wppa_enable_video';
						$onch = "wppaSlaveChecked(this,'enable_video');";
						$html = wppa_checkbox($slug, $onch) . wppa_see_also( 'misc', '1', '7.8', 'enable_video' ) . wppa_see_also( 'users', '1', '2', 'enable_video' );
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Enable pdf', 'wp-photo-album-plus' );
						$desc = __('Enables the support of pdf files', 'wp-photo-album-plus' );
						$help = __('Check this box to enable the upload and display of pdf document files', 'wp-photo-album-plus' );
						$slug = 'wppa_enable_pdf';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Enable Audio', 'wp-photo-album-plus' );
						$desc = __('Enables audio support.', 'wp-photo-album-plus' );
						$help = __('Check this box to enable the upload and playing of audio files', 'wp-photo-album-plus' );
						$slug = 'wppa_enable_audio';
						$onch = "wppaSlaveChecked(this,'enable_audio');";
						$html = wppa_checkbox($slug, $onch) . wppa_see_also( 'users', '1', '3', 'enable_audio' );
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Enable Comments', 'wp-photo-album-plus' );
						$desc = __('Enables the comments system.', 'wp-photo-album-plus' );
						$help = __('Display the comments box under the slideshow images and let users enter their comments on individual photos.', 'wp-photo-album-plus' );
						$slug = 'wppa_show_comments';
						$onch = "wppaSlaveChecked(this,'show_comments');wppaSlaveChecked(this,'comments');";
						$html = wppa_checkbox($slug, $onch) . wppa_see_also( 'comments', '1', '', 'show_comments' );
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Enable Ratings', 'wp-photo-album-plus' );
						$desc = __('Enables the rating system.', 'wp-photo-album-plus' );
						$help = __('If checked, the photo rating system will be enabled.', 'wp-photo-album-plus' );
						$slug = 'wppa_rating_on';
						$onch = "wppaSlaveChecked(this,'rating_on');wppaSlaveChecked(this,'rating');";
						$html = wppa_checkbox($slug, $onch) . wppa_see_also( 'rating', '1', '', 'rating_on' );
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Enable User uploads', 'wp-photo-album-plus' );
						$desc = __('Enables frontend upload.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_user_upload_on';
						$onch = "wppaSlaveChecked(this,'user_upload_on');wppaSlaveChecked(this,'users');";
						$html = wppa_checkbox($slug, $onch) . wppa_see_also( 'users', '1', '', 'user_upload_on' ) . wppa_see_also( 'new', '1', '18..26', 'user_upload_on' );
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Enable Email', 'wp-photo-album-plus' );
						$desc = __('Enables sending emails when albums, photos or comments are entered.', 'wp-photo-album-plus' );
						$help = __('See Tab Emails for detailed settings', 'wp-photo-album-plus' );
						$slug = 'wppa_email_on';
						$onch = "wppaSlaveChecked(this,'email_on');wppaSlaveChecked(this,'email');";
						$html = wppa_checkbox($slug, $onch) . wppa_see_also( 'email', '1', '', 'email_on' );
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('Enable EXIF', 'wp-photo-album-plus' );
						$desc = __('Store the exif data from the photo into the exif db table', 'wp-photo-album-plus' );
						$help = __('You will need this if you enabled the display of exif data in the photo descriptions.', 'wp-photo-album-plus' );
						$slug = 'wppa_save_exif';
						$onch = "wppaSlaveChecked(this,'save_exif');wppaSlaveChecked(this,'exif');";
						$html = wppa_checkbox($slug, $onch) . wppa_see_also( 'exif', '1', '', 'save_exif' );
						wppa_setting_new($slug, '8', $name, $desc, $html, $help, function_exists('exif_read_data') );

						$name = __('Enable IPTC', 'wp-photo-album-plus' );
						$desc = __('Store the iptc data from the photo into the iptc db table', 'wp-photo-album-plus' );
						$help = __('You will need this if you enabled the display of iptc data in the photo descriptions.', 'wp-photo-album-plus' );
						$slug = 'wppa_save_iptc';
						$onch = "wppaSlaveChecked(this,'save_iptc');wppaSlaveChecked(this,'iptc');";
						$html = wppa_checkbox($slug, $onch) . wppa_see_also( 'iptc', '1', '', 'save_iptc' );
						wppa_setting_new($slug, '9', $name, $desc, $html, $help, function_exists('exif_read_data') );

						$name = __('Enable GPX', 'wp-photo-album-plus' );
						$desc = __('Store the gpx data from the photo into the exif db table', 'wp-photo-album-plus' );
						$help = __('You will need this if you enabled the display of gpx data in the photo descriptions.', 'wp-photo-album-plus' );
						$slug = 'wppa_save_gpx';
						$onch = "wppaSlaveChecked(this,'save_gpx');wppaSlaveChecked(this,'gpx');";
						$html = wppa_checkbox($slug, $onch) . wppa_see_also( 'gpx', '1', '', 'save_gpx' );
						wppa_setting_new($slug, '10', $name, $desc, $html, $help, function_exists('exif_read_data') && wppa_switch( 'save_exif' ) );

						$name = __('Enable Custom data albums', 'wp-photo-album-plus' );
						$desc = __('Define up to 10 custom data fields for albums.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_album_custom_fields';
						$onch = "wppaSlaveChecked(this,'album_custom_fields');";
						$html = wppa_checkbox($slug, $onch) . wppa_see_also( 'custom', '1', '', 'album_custom_fields' );
						wppa_setting_new($slug, '11', $name, $desc, $html, $help);

						$name = __('Enable Custom data photos', 'wp-photo-album-plus' );
						$desc = __('Define up to 10 custom data fields for photos.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_custom_fields';
						$onch = "wppaSlaveChecked(this,'custom_fields');";
						$html = wppa_checkbox($slug, $onch) . wppa_see_also( 'custom', '2', '', 'custom_fields' );
						wppa_setting_new($slug, '12', $name, $desc, $html, $help);

						$name = __('Enable Watermark', 'wp-photo-album-plus' );
						$desc = __('Enable the application of watermarks.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_watermark_on';
						$onch = "wppaSlaveChecked(this,'watermark_on');wppaSlaveChecked(this,'watermark');";
						$html = wppa_checkbox($slug, $onch) . wppa_see_also( 'watermark', '1', '', 'watermark_on' );
						wppa_setting_new($slug, '13', $name, $desc, $html, $help);

						$name = __('Enable shortcode [photo ..]', 'wp-photo-album-plus' );
						$desc = __('Make the use of shortcode [photo ..] possible', 'wp-photo-album-plus' );
						$help = __('Only disable this when there is a conflict with another plugin', 'wp-photo-album-plus' );
						$slug = 'wppa_photo_shortcode_enabled';
						$onch = "wppaSlaveChecked(this,'photo_shortcode_enabled');";
						$html = wppa_checkbox($slug, $onch) . wppa_see_also( 'photos', '2', '', 'photo_shortcode_enabled' );
						wppa_setting_new($slug, '14', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
				}
				break;

				case 'layout': {
					// General layout settings
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('WPPA display boxes', 'wp-photo-album-plus' );
						$desc = __('Background and border colors.', 'wp-photo-album-plus' );
						$help = __('Enter valid CSS colors for backgrounds and borders. E.g. #cccccc, gray, lightblue, transparent', 'wp-photo-album-plus' );
						$slug1 = 'wppa_bgcolor';
						$slug2 = 'wppa_bcolor';
						$slug = array($slug1, $slug2);
						$html = '<span style="float:left;padding-top:5px">' . __('Background:', 'wp-photo-album-plus' ) . '&nbsp;</span>' . wppa_input($slug1, '100px', '', '', "checkColor('".$slug1."')") . wppa_color_box($slug1);
						$html .= '<span style="float:left;padding-top:5px;padding-left:12px">' . __('Border:', 'wp-photo-album-plus' ) . '&nbsp;</span>' . wppa_input($slug2, '100px', '', '', "checkColor('".$slug2."')") . wppa_color_box($slug2);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Border thickness', 'wp-photo-album-plus' );
						$desc = __('Thickness of wppa+ box borders.', 'wp-photo-album-plus' );
						$help = __('Enter the thickness for the border of the WPPA+ boxes. A number of 0 means: no border.', 'wp-photo-album-plus' );
						$slug = 'wppa_bwidth';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Border radius', 'wp-photo-album-plus' );
						$desc = __('Radius of wppa+ box borders.', 'wp-photo-album-plus' );
						$help = __('Enter the corner radius for the border of the WPPA+ boxes. A number of 0 means: no rounded corners.', 'wp-photo-album-plus' );
						$slug = 'wppa_bradius';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Box spacing', 'wp-photo-album-plus' );
						$desc = __('Distance between wppa+ boxes.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_box_spacing';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Initial Width', 'wp-photo-album-plus' );
						$desc = __('The starting width of the wppa display boxes', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_initial_colwidth';
						$html = wppa_input($slug, '40px', '', __('pixels wide', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Sticky header size', 'wp-photo-album-plus' );
						$desc = __('The height of your sticky header.', 'wp-photo-album-plus' );
						$help = __('If your theme has a sticky header, enter its height here.', 'wp-photo-album-plus' );
						$slug = 'wppa_sticky_header_size';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Area max size', 'wp-photo-album-plus' );
						$desc = __('The max height of the thumbnail and album cover areas', 'wp-photo-album-plus' );
						$help = __('A number > 1 is pixelsize, a number < 1 is fraction of the viewport height, 0 is no limit', 'wp-photo-album-plus' );
						$slug = 'wppa_area_size';
						$html = wppa_input($slug, '40px', '', __('pixels / fraction', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('Use nicescroller', 'wp-photo-album-plus' );
						$desc = __('Use nice scrollbars on thumbnail and album cover areas', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_nicescroll';
						$html = wppa_checkbox($slug) . wppa_see_also( 'system', 1, '34.35' );
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('Max Pagelinks', 'wp-photo-album-plus' );
						$desc = __('The maximum number of pagelinks to be displayed.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_pagelinks_max';
						$html = wppa_input($slug, '40px', '', __('pages', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						$name = __('Cover Photo and popups', 'wp-photo-album-plus' );
						$desc = __('Background and Border colors.', 'wp-photo-album-plus' );
						$help = __('Enter valid CSS colors for Cover photo and popup backgrounds and borders.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_bgcolor_img';
						$slug2 = 'wppa_bcolor_img';
						$slug = array($slug1, $slug2);
						$html = '<span style="float:left;padding-top:5px">' . __('Background:', 'wp-photo-album-plus' ) . '&nbsp;</span>' . wppa_input($slug1, '100px', '', '', "checkColor('".$slug1."')") . wppa_color_box($slug1);
						$html .= '<span style="float:left;padding-top:5px;padding-left:12px">' . __('Border:', 'wp-photo-album-plus' ) . '&nbsp;</span>' . wppa_input($slug2, '100px', '', '', "checkColor('".$slug2."')") . wppa_color_box($slug2);
						wppa_setting_new($slug, '10', $name, $desc, $html, $help);

						$name = __('Modal render box', 'wp-photo-album-plus' );
						$desc = __('The background for the Ajax modal rendering box.', 'wp-photo-album-plus' );
						$help = __('Recommended color: your theme background color.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_bgcolor_modal';
						$slug2 = 'wppa_bcolor_modal';
						$slug = array($slug1, $slug2);
						$html = '<span style="float:left;padding-top:5px">' . __('Background:', 'wp-photo-album-plus' ) . '&nbsp;</span>' . wppa_input($slug1, '100px', '', '', "checkColor('".$slug1."')") . wppa_color_box($slug1);
						wppa_setting_new($slug, '11', $name, $desc, $html, $help);

						$name = __('Ignore size/align on mobile', 'wp-photo-album-plus' );
						$desc = __('On mobile devices, all boxes will occupy the full available width', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_mobile_ignore_sa';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '12', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// Breadcrumb specifications
					{
						$desc = $wppa_subtab_names[$tab]['2'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Breadcrumb on posts', 'wp-photo-album-plus' );
						$desc = __('Show breadcrumb navigation bars.', 'wp-photo-album-plus' );
						$help = __('Indicate whether a breadcrumb navigation should be displayed', 'wp-photo-album-plus' );
						$slug = 'wppa_show_bread_posts';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Breadcrumb on pages', 'wp-photo-album-plus' );
						$desc = __('Show breadcrumb navigation bars.', 'wp-photo-album-plus' );
						$help = __('Indicate whether a breadcrumb navigation should be displayed', 'wp-photo-album-plus' );
						$slug = 'wppa_show_bread_pages';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Breadcrumb on search results', 'wp-photo-album-plus' );
						$desc = __('Show breadcrumb navigation bars on the search results page.', 'wp-photo-album-plus' );
						$help = __('Indicate whether a breadcrumb navigation should be displayed above the search results.', 'wp-photo-album-plus' );
						$slug = 'wppa_bc_on_search';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Breadcrumb on topten displays', 'wp-photo-album-plus' );
						$desc = __('Show breadcrumb navigation bars on topten displays.', 'wp-photo-album-plus' );
						$help = __('Indicate whether a breadcrumb navigation should be displayed above the topten displays.', 'wp-photo-album-plus' );
						$slug = 'wppa_bc_on_topten';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Breadcrumb on last ten displays', 'wp-photo-album-plus' );
						$desc = __('Show breadcrumb navigation bars on last ten displays.', 'wp-photo-album-plus' );
						$help = __('Indicate whether a breadcrumb navigation should be displayed above the last ten displays.', 'wp-photo-album-plus' );
						$slug = 'wppa_bc_on_lasten';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Breadcrumb on comment ten displays', 'wp-photo-album-plus' );
						$desc = __('Show breadcrumb navigation bars on comment ten displays.', 'wp-photo-album-plus' );
						$help = __('Indicate whether a breadcrumb navigation should be displayed above the comment ten displays.', 'wp-photo-album-plus' );
						$slug = 'wppa_bc_on_comten';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Breadcrumb on tag result displays', 'wp-photo-album-plus' );
						$desc = __('Show breadcrumb navigation bars on tag result displays.', 'wp-photo-album-plus' );
						$help = __('Indicate whether a breadcrumb navigation should be displayed above the tag result displays.', 'wp-photo-album-plus' );
						$slug = 'wppa_bc_on_tag';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('Breadcrumb on featured ten displays', 'wp-photo-album-plus' );
						$desc = __('Show breadcrumb navigation bars on featured ten displays.', 'wp-photo-album-plus' );
						$help = __('Indicate whether a breadcrumb navigation should be displayed above the featured ten displays.', 'wp-photo-album-plus' );
						$slug = 'wppa_bc_on_featen';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('Breadcrumb on related photos displays', 'wp-photo-album-plus' );
						$desc = __('Show breadcrumb navigation bars on related photos displays.', 'wp-photo-album-plus' );
						$help = __('Indicate whether a breadcrumb navigation should be displayed above the related photos displays.', 'wp-photo-album-plus' );
						$slug = 'wppa_bc_on_related';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						$name = __('Home', 'wp-photo-album-plus' );
						$desc = __('Show "Home" in breadcrumb.', 'wp-photo-album-plus' );
						$help = __('Indicate whether the breadcrumb navigation should start with a "Home"-link', 'wp-photo-album-plus' );
						$slug = 'wppa_show_home';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '10', $name, $desc, $html, $help);

						$name = __('Home text', 'wp-photo-album-plus' );
						$desc = __('The text to use as "Home"', 'wp-photo-album-plus' );
						$help = ' ';
						$slug = 'wppa_home_text';
						$html = wppa_input($slug, '100px;');
						wppa_setting_new($slug, '11', $name, $desc, $html, $help);

						$name = __('Page', 'wp-photo-album-plus' );
						$desc = __('Show the page(s) in breadcrumb.', 'wp-photo-album-plus' );
						$help = __('Indicate whether the breadcrumb navigation should show the page(hierarchy)', 'wp-photo-album-plus' );
						$slug = 'wppa_show_page';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '12', $name, $desc, $html, $help);

						$name = __('Photo name', 'wp-photo-album-plus' );
						$desc = __('Show name of photo above slideshow.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_show_pname';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '13', $name, $desc, $html, $help);

						$name = __('Separator', 'wp-photo-album-plus' );
						$desc = __('Breadcrumb separator symbol.', 'wp-photo-album-plus' );
						$help = __('Select the desired breadcrumb separator element.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('A text string may contain valid html.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('An image will be scaled automatically if you set the navigation font size.', 'wp-photo-album-plus' );
						$slug = 'wppa_bc_separator';
						$opts = array('&amp;raquo', '&amp;rsaquo', '&amp;gt', '&amp;bull', __('Text (html):', 'wp-photo-album-plus' ), __('Image (url):', 'wp-photo-album-plus' ));
						$vals = array('raquo', 'rsaquo', 'gt', 'bull', 'txt', 'url');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '14', $name, $desc, $html, $help);

						$name = __('Html', 'wp-photo-album-plus' );
						$desc = __('Breadcrumb separator text.', 'wp-photo-album-plus' );
						$help = __('Enter the HTML code that produces the separator symbol you want.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('It may be as simple as \'-\' (without the quotes) or as complex as a tag like <div>..</div>.', 'wp-photo-album-plus' );
						$slug = 'wppa_bc_txt';
						$html = wppa_input($slug, '90%', '300px');
						wppa_setting_new($slug, '15', $name, $desc, $html, $help);

						$name = __('Image Url', 'wp-photo-album-plus' );
						$desc = __('Full url to separator image.', 'wp-photo-album-plus' );
						$help = __('Enter the full url to the image you want to use for the separator symbol.', 'wp-photo-album-plus' );
						$slug = 'wppa_bc_url';
						$html = wppa_input($slug, '90%', '300px');
						wppa_setting_new($slug, '16', $name, $desc, $html, $help);

						$name = __('Pagelink position', 'wp-photo-album-plus' );
						$desc = __('The location for the pagelinks bar.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_pagelink_pos';
						$opts = array(__('Top', 'wp-photo-album-plus' ), __('Bottom', 'wp-photo-album-plus' ), __('Both', 'wp-photo-album-plus' ));
						$vals = array('top', 'bottom', 'both');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '17', $name, $desc, $html, $help);

						$name = __('Thumblink on slideshow', 'wp-photo-album-plus' );
						$desc = __('Show a thumb link on slideshow bc.', 'wp-photo-album-plus' );
						$help = __('Show a link to thumbnail display on an breadcrumb above a slideshow', 'wp-photo-album-plus' );
						$slug = 'wppa_bc_slide_thumblink';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '18', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// Navigation symbol specifications
					{
						$desc = $wppa_subtab_names[$tab]['3'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Navigation icon size', 'wp-photo-album-plus' );
						$desc = __('The size of navigation icons', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_nav_icon_size';
						$opts = array(	'1.5em',
										'16px',
										'20px',
										'24px',
										'32px',
										);
						$vals = array(	'default',
										'16',
										'20',
										'24',
										'32',
										);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Navigation icon size slideshow', 'wp-photo-album-plus' );
						$desc = __('The size of navigation icons on the slide', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_nav_icon_size_slide';
						$opts = array(	'16px',
										'20px',
										'24px',
										'32px',
										'48px',
										);
						$vals = array(	'16',
										'20',
										'24',
										'32',
										'default',
										);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Icon size rating', 'wp-photo-album-plus' );
						$desc = __('The size of rating stars', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_icon_size_rating';
						$opts = array(	'1em+3px',
										'16px',
										'18px',
										'20px',
										'24px',
										'32px',
										);
						$vals = array(	'default',
										'16',
										'18',
										'20',
										'24',
										'32',
										);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Navigation icon size panorama', 'wp-photo-album-plus' );
						$desc = __('The size of navigation icons on panorama photos', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_nav_icon_size_panorama';
						$opts = array(	'16px',
										'20px',
										'24px',
										'32px',
										'40px',
										'48px',
										);
						$vals = array(	'16',
										'20',
										'24',
										'32',
										'40',
										'48',
										);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Icon size fullsize page', 'wp-photo-album-plus' );
						$desc = __('The size of navigation icons for the fullsize page', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_nav_icon_size_global_fs';
						$opts = array(	'16px',
										'20px',
										'24px',
										'32px',
										'40px',
										'48px',
										);
						$vals = array(	'16',
										'20',
										'24',
										'32',
										'40',
										'48',
										);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Navigation symbols style', 'wp-photo-album-plus' );
						$desc = __('The corner rounding size of navigation icons.', 'wp-photo-album-plus' );
						$help = __('Use gif/png if you have excessive pageload times due to many slideshows on a page', 'wp-photo-album-plus' );
						$slug = 'wppa_icon_corner_style';
						$opts = array(__('none', 'wp-photo-album-plus' ), __('light', 'wp-photo-album-plus' ), __('medium', 'wp-photo-album-plus' ), __('heavy', 'wp-photo-album-plus' ), __('use gif/png, no svg', 'wp-photo-album-plus' ));
						$vals = array('none', 'light', 'medium', 'heavy', 'gif');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Spinner design', 'wp-photo-album-plus' );
						$desc = __('Shape of the loader symbol', 'wp-photo-album-plus' );
						$help = __('This works only when the previous item is set to any svg style', 'wp-photo-album-plus' );
						$slug = 'wppa_spinner_shape';
						$opts = array(	__('default', 'wp-photo-album-plus' ),
										'puff',
										'rings',
										'tail-spin',
										'three-dots',
										'ball-triangle',
										'spinning-circles',
										'oval',
										'hearts',
										'grid',
										'circles',
										'bars',
										'audio',
										);
						$vals = array(	'default',
										'puff',
										'rings',
										'tail-spin',
										'three-dots',
										'ball-triangle',
										'spinning-circles',
										'oval',
										'hearts',
										'grid',
										'circles',
										'bars',
										'audio',
										);
						$html = wppa_select($slug, $opts, $vals) .
								__('Frontend', 'wp-photo-album-plus' ) . ':&nbsp;<span id="wppa-spin-pre-1" >&nbsp;</span>' .
								__('Lightbox', 'wp-photo-album-plus' ) . ':&nbsp;<span id="wppa-spin-pre-2" >&nbsp;</span>';
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Navigation symbols', 'wp-photo-album-plus' );
						$desc = __('Navigation symbol background and fill colors.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_svg_bg_color';
						$slug2 = 'wppa_svg_color';
						$slug = array($slug1, $slug2);
						$html = '<span style="float:left;padding-top:5px">' . __('Background:', 'wp-photo-album-plus' ) . '&nbsp;</span>' . wppa_input($slug1, '100px', '', '', "checkColor('".$slug1."')") . wppa_color_box($slug1);
						$html .= '<span style="float:left;padding-top:5px;padding-left:12px">' . __('Foreground:', 'wp-photo-album-plus' ) . '&nbsp;</span>' . wppa_input($slug2, '100px', '', '', "checkColor('".$slug2."')") . wppa_color_box($slug2);
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('Navigation symbols Lightbox', 'wp-photo-album-plus' );
						$desc = __('Navigation symbol background and fill colors Lightbox.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_ovl_svg_bg_color';
						$slug2 = 'wppa_ovl_svg_color';
						$slug = array($slug1, $slug2);
						$html = '<span style="float:left;padding-top:5px">' . __('Background:', 'wp-photo-album-plus' ) . '&nbsp;</span>' . wppa_input($slug1, '100px', '', '', "checkColor('".$slug1."')") . wppa_color_box($slug1);
						$html .= '<span style="float:left;padding-top:5px;padding-left:12px">' . __('Foreground:', 'wp-photo-album-plus' ) . '&nbsp;</span>' . wppa_input($slug2, '100px', '', '', "checkColor('".$slug2."')") . wppa_color_box($slug2);
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('Fullscreen button', 'wp-photo-album-plus' );
						$desc = __('The upper right corner fullscreen button.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_fs_svg_bg_color';
						$slug2 = 'wppa_fs_svg_color';
						$slug = array($slug1, $slug2);
						$html = '<span style="float:left;padding-top:5px">' . __('Background:', 'wp-photo-album-plus' ) . '&nbsp;</span>' . wppa_input($slug1, '100px', '', '', "checkColor('".$slug1."')") . wppa_color_box($slug1);
						$html .= '<span style="float:left;padding-top:5px;padding-left:12px">' . __('Foreground:', 'wp-photo-album-plus' ) . '&nbsp;</span>' . wppa_input($slug2, '100px', '', '', "checkColor('".$slug2."')") . wppa_color_box($slug2);
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
						wppa_add_inline_script( 'wppa-admin', 'wppaAjaxGetSpinnerHtml("normal","wppa-spin-pre-1");wppaAjaxGetSpinnerHtml("lightbox","wppa-spin-pre-2");', false );
					}
					// Multimedia icon and stubfile specifications
					if ( wppa_switch( 'enable_audio' ) || wppa_switch( 'enable_video' ) || wppa_switch( 'enable_pdf' ) )
					{
						$desc = $wppa_subtab_names[$tab]['4'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$temp = wppa_glob( WPPA_UPLOAD_PATH . '/icons/*.*' );
						$icons = array();
						foreach( $temp as $item ) {
							$t = basename( $item );
							if ( $t != '.' && $t != '..' ) {
								$icons[] = $t;
							}
						}

						$name = __( 'Audio icon', 'wp-photo-album-plus' );
						$desc = __( 'Select the icon to use as audio indicator', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_audio_icon';
						$opts = $icons;
						$vals = $icons;
						$onch = "wppaUpdateIcon('audio_icon','audioicon')";
						$html = wppa_select( $slug, $opts, $vals, $onch ) . ' <img id="audioicon" src="' . esc_attr( WPPA_UPLOAD_URL . '/icons/' . wppa_opt( 'audio_icon' ) ) . '" style="height:24px;" />';
						wppa_setting_new( $slug, '1', $name, $desc, $html, $help, wppa_switch( 'enable_audio' ) );

						$name = __( 'Video icon', 'wp-photo-album-plus' );
						$desc = __( 'Select the icon to use as video indicator', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_video_icon';
						$opts = $icons;
						$vals = $icons;
						$onch = "wppaUpdateIcon('video_icon','videoicon')";
						$html = wppa_select( $slug, $opts, $vals, $onch ) . ' <img id="videoicon" src="' . esc_attr( WPPA_UPLOAD_URL . '/icons/' . wppa_opt( 'video_icon' ) ) . '" style="height:24px;" />';
						wppa_setting_new( $slug, '2', $name, $desc, $html, $help, wppa_switch( 'enable_video' ) );

						$name = __( 'Document icon', 'wp-photo-album-plus' );
						$desc = __( 'Select the icon to use as document indicator', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_document_icon';
						$opts = $icons;
						$vals = $icons;
						$onch = "wppaUpdateIcon('document_icon','documenticon')";
						$html = wppa_select( $slug, $opts, $vals, $onch ) . ' <img id="documenticon" src="' . esc_attr( WPPA_UPLOAD_URL . '/icons/' . wppa_opt( 'document_icon' ) ) . '" style="height:24px;" />';
						wppa_setting_new( $slug, '3', $name, $desc, $html, $help, wppa_switch( 'enable_pdf' ) );

						$name = __( 'Upload custom multimedia icon', 'wp-photo-album-plus' );
						$desc = __( 'You can upload alternative icons here', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_multimedia_icon_upload';
						$html = wppa_upload_form( $slug, $wppa_cur_tab, '.jpeg,.jpg,.png,.svg,.gif,.bmp,.ico' );
						wppa_setting_new( $slug, '4', $name, $desc, $html, $help );

						$name = __('Use audiostub', 'wp-photo-album-plus' );
						$desc = __('Show a dummy image on audio items that have no photo', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_use_audiostub';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '5', $name, $desc, $html, $help, wppa_switch('enable_audio'));

						$name = __('Upload audiostub', 'wp-photo-album-plus' );
						$desc = __('Upload a new audio stub file', 'wp-photo-album-plus' );
						$help = __('This MUST be a .jpg image file', 'wp-photo-album-plus' );
						$slug = 'wppa_audiostub_upload';
						$html = wppa_upload_form( $slug, $wppa_cur_tab, '.jpg' );
						wppa_setting_new($slug, '6', $name, $desc, $html, $help, wppa_switch('enable_audio'));

						$name = __('Upload documentstub', 'wp-photo-album-plus' );
						$desc = __('Upload a new document stub file', 'wp-photo-album-plus' );
						$help = __('This MUST be a .png image file', 'wp-photo-album-plus' );
						$slug = 'wppa_documentstub_upload';
						$html = wppa_upload_form( $slug, $wppa_cur_tab, '.png' );
						wppa_setting_new($slug, '7', $name, $desc, $html, $help, wppa_switch('enable_pdf'));

						wppa_setting_box_footer_new();
					}
					else {
						wppa_bump_subtab_id();
					}
					// Fonts
					$desc = $wppa_subtab_names[$tab]['5'];
					{
						$coldef = array();
						wppa_setting_tab_description($desc);
						$coldef = array( 	__('#', 'wp-photo-album-plus' ) => '24px;',
											__('Name', 'wp-photo-album-plus' ) => 'auto;',
											__('Description', 'wp-photo-album-plus' ) => 'auto;',
											__('Font family', 'wp-photo-album-plus' ) => 'auto;',
											__('Font size', 'wp-photo-album-plus' ) => 'auto;',
											__('Font color', 'wp-photo-album-plus' ) => 'auto;',
											__('Font weight', 'wp-photo-album-plus' ) => 'auto;',
											__('Help', 'wp-photo-album-plus' ) => '24px;',
											);
						wppa_setting_box_header_new($tab, $coldef);

						$opts = array(__('normal', 'wp-photo-album-plus' ), __('bold', 'wp-photo-album-plus' ), __('bolder', 'wp-photo-album-plus' ), __('lighter', 'wp-photo-album-plus' ), '100', '200', '300', '400', '500', '600', '700', '800', '900');
						$vals = array('normal', 'bold', 'bolder', 'lighter', '100', '200', '300', '400', '500', '600', '700', '800', '900');

						$name = __('Album titles', 'wp-photo-album-plus' );
						$desc = __('Font used for album titles.', 'wp-photo-album-plus' );
						$help = __('Enter font name, size, color and weight for album cover titles.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_fontfamily_title';
						$slug2 = 'wppa_fontsize_title';
						$slug3 = 'wppa_fontcolor_title';
						$slug4 = 'wppa_fontweight_title';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$html1 = wppa_input($slug1, '90%', '200px', '');
						$html2 = wppa_input($slug2, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						$html3 = wppa_input($slug3, '120px', '', '');
						$html4 = wppa_select($slug4, $opts, $vals);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Slideshow desc', 'wp-photo-album-plus' );
						$desc = __('Font for slideshow photo descriptions.', 'wp-photo-album-plus' );
						$help = __('Enter font name, size, color and weight for slideshow photo descriptions.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_fontfamily_fulldesc';
						$slug2 = 'wppa_fontsize_fulldesc';
						$slug3 = 'wppa_fontcolor_fulldesc';
						$slug4 = 'wppa_fontweight_fulldesc';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$html1 = wppa_input($slug1, '90%', '200px', '');
						$html2 = wppa_input($slug2, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						$html3 = wppa_input($slug3, '120px', '', '');
						$html4 = wppa_select($slug4, $opts, $vals);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Slideshow name', 'wp-photo-album-plus' );
						$desc = __('Font for slideshow photo names.', 'wp-photo-album-plus' );
						$help = __('Enter font name, size, color and weight for slideshow photo names.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_fontfamily_fulltitle';
						$slug2 = 'wppa_fontsize_fulltitle';
						$slug3 = 'wppa_fontcolor_fulltitle';
						$slug4 = 'wppa_fontweight_fulltitle';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$html1 = wppa_input($slug1, '90%', '200px', '');
						$html2 = wppa_input($slug2, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						$html3 = wppa_input($slug3, '120px', '', '');
						$html4 = wppa_select($slug4, $opts, $vals);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Navigations', 'wp-photo-album-plus' );
						$desc = __('Font for navigations.', 'wp-photo-album-plus' );
						$help = __('Enter font name, size, color and weight for navigation items.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_fontfamily_nav';
						$slug2 = 'wppa_fontsize_nav';
						$slug3 = 'wppa_fontcolor_nav';
						$slug4 = 'wppa_fontweight_nav';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$html1 = wppa_input($slug1, '90%', '200px', '');
						$html2 = wppa_input($slug2, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						$html3 = wppa_input($slug3, '120px', '', '');
						$html4 = wppa_select($slug4, $opts, $vals);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Thumbnails', 'wp-photo-album-plus' );
						$desc = __('Font for text under thumbnails.', 'wp-photo-album-plus' );
						$help = __('Enter font name, size, color and weight for text under thumbnail images.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_fontfamily_thumb';
						$slug2 = 'wppa_fontsize_thumb';
						$slug3 = 'wppa_fontcolor_thumb';
						$slug4 = 'wppa_fontweight_thumb';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$html1 = wppa_input($slug1, '90%', '200px', '');
						$html2 = wppa_input($slug2, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						$html3 = wppa_input($slug3, '120px', '', '');
						$html4 = wppa_select($slug4, $opts, $vals);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Other', 'wp-photo-album-plus' );
						$desc = __('General font in wppa boxes.', 'wp-photo-album-plus' );
						$help = __('Enter font name, size, color and weight for all other items.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_fontfamily_box';
						$slug2 = 'wppa_fontsize_box';
						$slug3 = 'wppa_fontcolor_box';
						$slug4 = 'wppa_fontweight_box';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$html1 = wppa_input($slug1, '90%', '200px', '');
						$html2 = wppa_input($slug2, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						$html3 = wppa_input($slug3, '120px', '', '');
						$html4 = wppa_select($slug4, $opts, $vals);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Numbar', 'wp-photo-album-plus' );
						$desc = __('Font in wppa number bars.', 'wp-photo-album-plus' );
						$help = __('Enter font name, size, color and weight for numberbar navigation.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_fontfamily_numbar';
						$slug2 = 'wppa_fontsize_numbar';
						$slug3 = 'wppa_fontcolor_numbar';
						$slug4 = 'wppa_fontweight_numbar';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$html1 = wppa_input($slug1, '90%', '200px', '');
						$html2 = wppa_input($slug2, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						$html3 = wppa_input($slug3, '120px', '', '');
						$html4 = wppa_select($slug4, $opts, $vals);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('Numbar Active', 'wp-photo-album-plus' );
						$desc = __('Font in wppa number bars, active item.', 'wp-photo-album-plus' );
						$help = __('Enter font name, size, color and weight for numberbar navigation.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_fontfamily_numbar_active';
						$slug2 = 'wppa_fontsize_numbar_active';
						$slug3 = 'wppa_fontcolor_numbar_active';
						$slug4 = 'wppa_fontweight_numbar_active';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$html1 = wppa_input($slug1, '90%', '200px', '');
						$html2 = wppa_input($slug2, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						$html3 = wppa_input($slug3, '120px', '', '');
						$html4 = wppa_select($slug4, $opts, $vals);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('Lightbox', 'wp-photo-album-plus' );
						$desc = __('Font in wppa lightbox overlays.', 'wp-photo-album-plus' );
						$help = __('Enter font name, size, color and weight for wppa lightbox overlays.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_fontfamily_lightbox';
						$slug2 = 'wppa_fontsize_lightbox';
						$slug3 = 'wppa_fontcolor_lightbox';
						$slug4 = 'wppa_fontweight_lightbox';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$html1 = wppa_input($slug1, '90%', '200px', '');
						$html2 = wppa_input($slug2, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						$html3 = wppa_input($slug3, '120px', '', '');
						$html4 = wppa_select($slug4, $opts, $vals);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						$name = __('Widget thumbs fontsize', 'wp-photo-album-plus' );
						$desc = __('Font size for thumbnail subtext in widgets.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = '';
						$slug2 = 'wppa_fontsize_widget_thumb';
						$slug3 = '';
						$slug4 = '';
						$slug = $slug2;
						$html1 = '';
						$html2 = wppa_input($slug2, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						$html3 = '';
						$html4 = '';
						$html = '</td><td>' . $html2 . '</td><td></td><td>';
						wppa_setting_new($slug, '10', $name, $desc, $html, $help);

						$name = __('Calendar fontsize', 'wp-photo-album-plus' );
						$desc = __('Old style calendar fontstyle', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = '';
						$slug2 = 'wppa_font_calendar_by';
						$slug3 = '';
						$slug4 = 'wppa_font_calendar_by_bold';
						$slug = $slug2;
						$html1 = '';
						$opts = array( __('Small', 'wp-photo-album-plus' ),
									   __('Medium', 'wp-photo-album-plus' ),
									   __('Large', 'wp-photo-album-plus' ),
									   __('Extra large', 'wp-photo-album-plus' ),
									   );
						$vals = array( 'small', 'medium', 'large', 'xlarge');
						$html2 = wppa_select($slug2, $opts, $vals);
						$html3 = '';
						$html4 = wppa_checkbox($slug4) .  ' ' . __('Bold', 'wp-photo-album-plus' );
						$html = '</td><td>' . $html2 . '</td><td></td><td>' . $html4;
						wppa_setting_new($slug, '11', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// Audio only layout settings
					{
						$desc = $wppa_subtab_names[$tab]['6'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Area max size', 'wp-photo-album-plus' );
						$desc = __('The max height of the audio only album area', 'wp-photo-album-plus' );
						$help = __('A number > 1 is pixelsize, a number < 1 is fraction of the viewport height, 0 is no limit', 'wp-photo-album-plus' );
						$slug = 'wppa_area_size_audio';
						$html = wppa_input($slug, '40px', '', __('pixels / fraction', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Show album name', 'wp-photo-album-plus');
						$desc = __('Show album name as title above the album display', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_audioonly_name';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Show album desc', 'wp-photo-album-plus');
						$desc = __('Show album description as subtitle above the album display', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_audioonly_desc';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Show duration', 'wp-photo-album-plus');
						$desc = __('Display duration of each item', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_audioonly_duration';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Poster image location', 'wp-photo-album-plus');
						$desc = __('If poster specified, place it left or right', 'wp-photo-album-plus');
						$help = __('You can specify a poster image in the shortcode: [wppa type="audio" album="id" poster="id"]', 'wp-photo-album-plus');
						$slug = 'wppa_audioonly_posterpos';
						$opts = [__('left', 'wp-photo-album-plus'), __('right', 'wp-photo-album-plus')];
						$vals = ['left','right'];
						$html = wppa_select( $slug, $opts, $vals );
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Show item description', 'wp-photo-album-plus');
						$desc = __('Show the items description on hoovering and when playing', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_audioonly_itemdesc';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();

					}
				}
				break;

				case 'covers': {
					// Album cover size specifications
					$desc = $wppa_subtab_names[$tab]['1'];
					{
					wppa_setting_tab_description($desc);
					wppa_setting_box_header_new($tab);

					$name = __('Max Cover width', 'wp-photo-album-plus' );
					$desc = __('Maximum width for a album cover display.', 'wp-photo-album-plus' );
					$help = __('Display covers in 2 or more columns if the display area is wider than the given width.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('This also applies for \'thumbnails as covers\', and will NOT apply to single items.', 'wp-photo-album-plus' );
					$slug = 'wppa_max_cover_width';
					$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
					wppa_setting_new($slug, '1', $name, $desc, $html, $help);

					$name = __('Min Cover height', 'wp-photo-album-plus' );
					$desc = __('Minimal height of an album cover.', 'wp-photo-album-plus' );
					$help = __('If you use this setting to make the albums the same height and you are not satisfied about the lay-out, try increasing the value in the next setting', 'wp-photo-album-plus' );
					$slug = 'wppa_cover_minheight';
					$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
					wppa_setting_new($slug, '2', $name, $desc, $html, $help);

					$name = __('Min Text frame height', 'wp-photo-album-plus' );
					$desc = __('The minimal cover text frame height incl header.', 'wp-photo-album-plus' );
					$help = __('The height starting with the album title up to and including the view- and the slideshow- links.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('This setting enables you to give the album covers the same height while the title does not need to fit on one line.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('This is the recommended setting to line-up your covers!', 'wp-photo-album-plus' );
					$slug = 'wppa_head_and_text_frame_height';
					$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
					wppa_setting_new($slug, '3', $name, $desc, $html, $help);

					$name = __('Min Description height', 'wp-photo-album-plus' );
					$desc = __('The minimal height of the album description text frame.', 'wp-photo-album-plus' );
					$help = __('The minimal height of the description field in an album cover display.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('This setting enables you to give the album covers the same height provided that the cover images are equally sized and the titles fit on one line.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('To force the coverphotos have equal heights, tick the box in Albums -> I -> Size is height', 'wp-photo-album-plus' );
					$help .= '<br>'.__('You may need this setting if changing the previous setting is not sufficient to line-up the covers.', 'wp-photo-album-plus' );
					$slug = 'wppa_text_frame_height';
					$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
					wppa_setting_new($slug, '4', $name, $desc, $html, $help);

					$name = __('Coverphoto responsive', 'wp-photo-album-plus' );
					$desc = __('Check this box if you want a responsive coverphoto.', 'wp-photo-album-plus' );
					$help = '';
					$slug = 'wppa_coverphoto_responsive';
					$onch = "wppaSlaveChecked(this,'wppa_smallsize_percentage');" .
							"wppaSlaveChecked(this,'wppa_smallsize_multi_percentage');" .
							"wppaUnSlaveChecked(this,'wppa_smallsize');" .
							"wppaUnSlaveChecked(this,'wppa_smallsize_multi');";
					$html = wppa_checkbox($slug, $onch);
					wppa_setting_new($slug, '5', $name, $desc, $html, $help);

					$name = __('Coverphoto size', 'wp-photo-album-plus' );
					$desc = __('The size of the coverphoto.', 'wp-photo-album-plus' );
					$help = __('This size applies to the width or height, whichever is the largest.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('Changing the coverphoto size may result in all thumbnails being regenerated. this may take a while.', 'wp-photo-album-plus' );
					$slug = 'wppa_smallsize';
					$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
					wppa_setting_new($slug, '6', $name, $desc, $html, $help, ! wppa_switch( 'coverphoto_responsive' ));

					$name = __('Coverphoto size', 'wp-photo-album-plus' );
					$desc = __('The size of the coverphoto.', 'wp-photo-album-plus' );
					$help = __('This size applies to the width or height, whichever is the largest.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('Changing the coverphoto size may result in all thumbnails being regenerated. this may take a while.', 'wp-photo-album-plus' );
					$slug = 'wppa_smallsize_percentage';
					$html = wppa_input($slug, '40px', '', __('percent', 'wp-photo-album-plus' ));
					wppa_setting_new($slug, '7', $name, $desc, $html, $help, wppa_switch( 'coverphoto_responsive' ));

					$name = __('Coverphoto size multi', 'wp-photo-album-plus' );
					$desc = __('The size of coverphotos if more than one.', 'wp-photo-album-plus' );
					$help = __('This size applies to the width or height, whichever is the largest.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('Changing the coverphoto size may result in all thumbnails being regenerated. this may take a while.', 'wp-photo-album-plus' );
					$slug = 'wppa_smallsize_multi';
					$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
					wppa_setting_new($slug, '8', $name, $desc, $html, $help, ! wppa_switch( 'coverphoto_responsive' ));

					$name = __('Coverphoto size multi', 'wp-photo-album-plus' );
					$desc = __('The size of coverphotos if more than one.', 'wp-photo-album-plus' );
					$help = __('This size applies to the width or height, whichever is the largest.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('Changing the coverphoto size may result in all thumbnails being regenerated. this may take a while.', 'wp-photo-album-plus' );
					$slug = 'wppa_smallsize_multi_percentage';
					$html = wppa_input($slug, '40px', '', __('percent', 'wp-photo-album-plus' ));
					wppa_setting_new($slug, '9', $name, $desc, $html, $help, wppa_switch( 'coverphoto_responsive' ));

					$name = __('Size is height', 'wp-photo-album-plus' );
					$desc = __('The size of the coverphoto is the height of it.', 'wp-photo-album-plus' );
					$help = __('If set: the previous setting is the height, if unset: the largest of width and height.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('This setting applies for coverphoto position top or bottom only (Albums -> III -> Placement).', 'wp-photo-album-plus' );
					$help .= '<br>'.__('This makes it easyer to make the covers of equal height.', 'wp-photo-album-plus' );
					$slug = 'wppa_coversize_is_height';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '10', $name, $desc, $html, $help);

					$name = __('Page size', 'wp-photo-album-plus' );
					$desc = __('Max number of covers per page.', 'wp-photo-album-plus' );
					$help = __('Enter the maximum number of album covers per page. A value of 0 indicates no pagination.', 'wp-photo-album-plus' );
					$slug = 'wppa_album_page_size';
					$html = wppa_input($slug, '40px', '', __('covers', 'wp-photo-album-plus' ));
					wppa_setting_new($slug, '11', $name, $desc, $html, $help);

					$name = __('Cover spacing', 'wp-photo-album-plus' );
					$desc = __('The space between album covers', 'wp-photo-album-plus' );
					$help = '';
					$slug = 'wppa_cover_spacing';
					$html = wppa_input( $slug, '40px', '', __( 'pixels', 'wp-photo-album-plus' ));
					wppa_setting_new( $slug, '12', $name, $desc, $html, $help );

					wppa_setting_box_footer_new();
					}
					// Album cover options
					$desc = $wppa_subtab_names[$tab]['2'];
					{
					wppa_setting_tab_description($desc);
					wppa_setting_box_header_new($tab);

					$name = __('Covertext', 'wp-photo-album-plus' );
					$desc = __('Show the text on the album cover.', 'wp-photo-album-plus' );
					$help = __('Display the album decription on the album cover', 'wp-photo-album-plus' );
					$slug = 'wppa_show_cover_text';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '1', $name, $desc, $html, $help);

					$name = __('Slideshow', 'wp-photo-album-plus' );
					$desc = __('Enable the slideshow.', 'wp-photo-album-plus' );
					$help = __('If you do not want slideshows: uncheck this box. Browsing full size images will remain possible.', 'wp-photo-album-plus' );
					$slug = 'wppa_enable_slideshow';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '2', $name, $desc, $html, $help);

					$name = __('Slideshow/Browse', 'wp-photo-album-plus' );
					$desc = __('Display the Slideshow / Browse photos link on album covers', 'wp-photo-album-plus' );
					$help = __('This setting causes the Slideshow link to be displayed on the album cover.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('If slideshows are disabled in item 2 in this table, you will see a browse link to fullsize images.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('If you do not want the browse link either, uncheck this item.', 'wp-photo-album-plus' );
					$slug = 'wppa_show_slideshowbrowselink';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '3', $name, $desc, $html, $help);

					$name = __('View ...', 'wp-photo-album-plus' );
					$desc = __('Display the View xx albums and yy photos link on album covers', 'wp-photo-album-plus' );
					$help = '';
					$slug = 'wppa_show_viewlink';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '4', $name, $desc, $html, $help);

					$name = __('Treecount', 'wp-photo-album-plus' );
					$desc = __('Display the total number of (sub)albums and photos in sub albums', 'wp-photo-album-plus' );
					$help = __('Displays the total number of sub albums and photos in the entire album tree in parenthesis if the numbers differ from the direct content of the album.', 'wp-photo-album-plus' );
					$slug = 'wppa_show_treecount';
					$opts = array( __('none', 'wp-photo-album-plus' ), __('detailed', 'wp-photo-album-plus' ), __('totals only', 'wp-photo-album-plus' ));
					$vals = array( '-none-', 'detail', 'total' );
					$html = wppa_select($slug, $opts, $vals);
					wppa_setting_new($slug, '5', $name, $desc, $html, $help);

					$name = __('Show categories', 'wp-photo-album-plus' );
					$desc = __('Display the album categories on the covers.', 'wp-photo-album-plus' );
					$slug = 'wppa_show_cats';
					$help = '';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '6', $name, $desc, $html, $help);

					$name = __('Skip empty albums', 'wp-photo-album-plus' );
					$desc = __('Do not show empty albums, except for admin and owner.', 'wp-photo-album-plus' );
					$help = '';
					$slug = 'wppa_skip_empty_albums';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '7', $name, $desc, $html, $help);

					$name = __('Count on title', 'wp-photo-album-plus' );
					$desc = __('Show photocount along with album title. ', 'wp-photo-album-plus' );
					$help = '';
					$slug = 'wppa_count_on_title';
					$opts = array( __('none', 'wp-photo-album-plus' ), __('top album only', 'wp-photo-album-plus' ), __('total tree', 'wp-photo-album-plus' ));
					$vals = array( '-none-', 'self', 'total' );
					$html = wppa_select($slug, $opts, $vals);
					wppa_setting_new($slug, '8', $name, $desc, $html, $help);

					$name = __('Viewcount on cover', 'wp-photo-album-plus' );
					$desc = __('Show total photo viewcount on album covers.', 'wp-photo-album-plus' );
					$help = __('Works on albums with one coverphoto only', 'wp-photo-album-plus');
					$slug = 'wppa_viewcount_on_cover';
					$opts = array( __('none', 'wp-photo-album-plus' ), __('on each album', 'wp-photo-album-plus' ), __('total views of tree', 'wp-photo-album-plus' ));
					$vals = array( '-none-', 'self', 'total' );
					$html = wppa_select($slug, $opts, $vals);
					wppa_setting_new($slug, '9', $name, $desc, $html, $help);

					$name = __('Album id on cover', 'wp-photo-album-plus' );
					$desc = __('Show album id on album cover next to name', 'wp-photo-album-plus' );
					$help = '';
					$slug = 'wppa_albumid_on_cover';
					$opts = array( __('none', 'wp-photo-album-plus' ), __('If user has edit access', 'wp-photo-album-plus' ), __('Always', 'wp-photo-album-plus' ) );
					$vals = array( '-none-', 'access', 'all' );
					$html = wppa_select($slug, $opts, $vals);
					wppa_setting_new($slug, '10', $name, $desc, $html, $help);

					wppa_setting_box_footer_new();
					}
					// Album cover layout settings
					$desc = $wppa_subtab_names[$tab]['3'];
					{
					wppa_setting_tab_description($desc);
					wppa_setting_box_header_new($tab);

					$name = __('Placement', 'wp-photo-album-plus' );
					$desc = __('Cover image position.', 'wp-photo-album-plus' );
					$help = __('Enter the position that you want to be used for the default album cover selected in Albums -> III -> Cover type', 'wp-photo-album-plus' );
					$help .= '<br>'.__('For covertype Image Factory: left will be treated as top and right will be treted as bottom.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('For covertype Long Descriptions: top will be treated as left and bottom will be treted as right.', 'wp-photo-album-plus' );
					$slug = 'wppa_coverphoto_pos';
					$opts = array(__('Left', 'wp-photo-album-plus' ), __('Right', 'wp-photo-album-plus' ), __('Top', 'wp-photo-album-plus' ), __('Bottom', 'wp-photo-album-plus' ));
					$vals = array('left', 'right', 'top', 'bottom');
					$html = wppa_select($slug, $opts, $vals);
					wppa_setting_new($slug, '1', $name, $desc, $html, $help);

					$name = __('Cover mouseover', 'wp-photo-album-plus' );
					$desc = __('Apply coverphoto mouseover effect.', 'wp-photo-album-plus' );
					$help = (__('Check this box to use mouseover effect on cover images.', 'wp-photo-album-plus' ));
					$slug = 'wppa_use_cover_opacity';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '2', $name, $desc, $html, $help);

					$name = __('Cover opacity', 'wp-photo-album-plus' );
					$desc = __('Initial opacity value.', 'wp-photo-album-plus' );
					$help = __('Enter percentage of opacity. 100% is opaque, 0% is transparant', 'wp-photo-album-plus' );
					$slug = 'wppa_cover_opacity';
					$html = wppa_input($slug, '50px', '', '%');
					wppa_setting_new($slug, '3', $name, $desc, $html, $help);

					$name = __('Cover type', 'wp-photo-album-plus' );
					$desc = __('Select the default cover type.', 'wp-photo-album-plus' );
					$help = __('Types with the addition mcr are suitable for Multi Column in a Responsive theme', 'wp-photo-album-plus' );
					$help .= '<br>'.__("Type 'Grid with images only' is always responsive and requires a fixed aspect ratio selected for thumbnails in Thumbnails -> I -> Thumbnail Aspect", 'wp-photo-album-plus' );
					$slug = 'wppa_cover_type';
					$opts = array(	__('Standard', 'wp-photo-album-plus' ),
									__('Long Descriptions', 'wp-photo-album-plus' ),
									__('Image Factory', 'wp-photo-album-plus' ),
									__('Standard mcr', 'wp-photo-album-plus' ),
									__('Long Descriptions mcr', 'wp-photo-album-plus' ),
									__('Image Factory mcr', 'wp-photo-album-plus' ),
									__('Grid with images only', 'wp-photo-album-plus' ),
								);
					$vals = array(	'default',
									'longdesc',
									'imagefactory',
									'default-mcr',
									'longdesc-mcr',
									'imagefactory-mcr',
									'grid',
								);
					$html = wppa_select($slug, $opts, $vals);
					wppa_setting_new($slug, '4', $name, $desc, $html, $help);

					$name = __('Number of coverphotos', 'wp-photo-album-plus' );
					$desc = __('The number of coverphotos. Must be > 1 and < 25.', 'wp-photo-album-plus' );
					$help = __('This works on cover type Image Factory (mcr) only', 'wp-photo-album-plus' );
					$slug = 'wppa_imgfact_count';
					$html = wppa_input($slug, '50px', '', __('photos', 'wp-photo-album-plus' ));
					wppa_setting_new($slug, '5', $name, $desc, $html, $help);

					$name = __('Cats include subs', 'wp-photo-album-plus' );
					$desc = __('Sub albums are included in Category based shortcodes.', 'wp-photo-album-plus' );
					$help = __('When you use album="#cat,...", in a shortcode, the sub albums will be included.', 'wp-photo-album-plus' );
					$slug = 'wppa_cats_inherit';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '6', $name, $desc, $html, $help);

					$name = __('Run nl2br or wpautop on description', 'wp-photo-album-plus' );
					$desc = __('Adds &lt;br> or &lt;p> and &lt;br> tags in album descriptions.', 'wp-photo-album-plus' );
					$help = '';
					$slug = 'wppa_wpautop_on_album_desc';
					$opts = array(	__('--- none ---', 'wp-photo-album-plus' ),
									__('Linebreaks only', 'wp-photo-album-plus' ),
									__('Linebreaks and paragraphs', 'wp-photo-album-plus' ),
								);
					$vals = array('nil', 'nl2br', 'wpautop');
					$html = wppa_select($slug, $opts, $vals);
					wppa_setting_new($slug, '7', $name, $desc, $html, $help);

					$name = __('Use thumb on cover', 'wp-photo-album-plus' );
					$desc = __('Always use thumbnail file for cover image', 'wp-photo-album-plus' );
					$help = __('If you crop thumbnails for cover images, and you have a CDN, tick this box', 'wp-photo-album-plus' );
					$slug = 'wppa_cover_use_thumb';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '8', $name, $desc, $html, $help);

					wppa_setting_box_footer_new();
					}
				}
				break;

				case 'photos': {
					// Photo specifications
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Resize during upload', 'wp-photo-album-plus' );
						$desc = __('Resize photos to fit within a given area.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_resize_to';
						$px = __('pixels', 'wp-photo-album-plus' );
						$opts = array(	__('Do not resize', 'wp-photo-album-plus' ),
										__('Fit within rectangle as set in Tab Slideshow I', 'wp-photo-album-plus' ),
										'640 x 480 '.$px,
										'800 x 600 '.$px,
										'1024 x 768 '.$px,
										'1200 x 900 '.$px,
										'1280 x 960 '.$px,
										'1366 x 768 '.$px,
										'1920 x 1080 '.$px,
										'2400 x 1200 '.$px,
										'3600 x 1800 '.$px,
										'4800 x 2400 '.$px,
										'6000 x 3000 '.$px,
										);
						$vals = array( 	'-1',
										'0',
										'640x480',
										'800x600',
										'1024x768',
										'1200x900',
										'1280x960',
										'1366x768',
										'1920x1080',
										'2400x1200',
										'3600x1800',
										'4800x2400',
										'6000x3000',
										);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Enable 3D Stereo', 'wp-photo-album-plus' );
						$desc = __('Enables 3D stereo photo support.', 'wp-photo-album-plus' );
						$help = __('Check this box to enable the upload and display of 3D stereo image files', 'wp-photo-album-plus' );
						$slug = 'wppa_enable_stereo';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Enable Panorama', 'wp-photo-album-plus' );
						$desc = __('Enables panorama photo support.', 'wp-photo-album-plus' );
						$help = __('Check this box to enable the upload and display of flat and 360&deg; spheric panorama image files', 'wp-photo-album-plus' );
						$slug = 'wppa_enable_panorama';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Enable zooming', 'wp-photo-album-plus' );
						$desc = __('Enable zooming and panning', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_zoom_on';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// Shortcode [photo ... ] specifications
					if ( wppa_switch( 'photo_shortcode_enabled' ) ) {
						$desc = $wppa_subtab_names[$tab]['2'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Single image type', 'wp-photo-album-plus' );
						$desc = __('Specify the single image type the shortcode [photo ..] should show.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_photo_shortcode_type';
						$opts = array( 	__('A plain single photo', 'wp-photo-album-plus' ),
										__('A single photo with caption', 'wp-photo-album-plus' ),
										__('A single photo with extended caption', 'wp-photo-album-plus' ),
										__('A single photo in the style of a slideshow', 'wp-photo-album-plus' ),
										);
						$vals = array( 	'photo',
										'mphoto',
										'xphoto',
										'slphoto',
										);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Size', 'wp-photo-album-plus' );
						$desc = __('Specify the size (width) of the image.', 'wp-photo-album-plus' );
						$help = __('Use the same syntax as in the [wppa size=".."] shortcode', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Examples: 350 for a fixed width of 350 pixels, or: 0.75 for a responsive display of 75% width, or: auto,350 for responsive with a maximum of 350 pixels.', 'wp-photo-album-plus' );
						$slug = 'wppa_photo_shortcode_size';
						$html = wppa_input($slug, '300px');
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Align', 'wp-photo-album-plus' );
						$desc = __('Specify the alignment of the image.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_photo_shortcode_align';
						$opts = array( 	__('--- none ---', 'wp-photo-album-plus' ),
										__('left', 'wp-photo-album-plus' ),
										__('center', 'wp-photo-album-plus' ),
										__('right', 'wp-photo-album-plus' ),
										);
						$vals = array( 	'',
										'left',
										'center',
										'right',
										);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Fe type', 'wp-photo-album-plus' );
						$desc = __('Frontend editor shortcode generator output type', 'wp-photo-album-plus' );
						$help = __( 'If you want to use the shortcode generator in frontend tinymce editors, select if you want the shortcode or the html to be entered in the post', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Select \'html\' if the inserted shortcode not is converted to the photo', 'wp-photo-album-plus' );
						$slug = 'wppa_photo_shortcode_fe_type';
						$opts = array( 	__('--- none ---', 'wp-photo-album-plus' ),
										__('shortcode', 'wp-photo-album-plus' ),
										__('html', 'wp-photo-album-plus' ),
										__('img tag', 'wp-photo-album-plus' ),
										);
						$vals = array(	'-none-',
										'shortcode',
										'html',
										'img',
										);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Albums', 'wp-photo-album-plus' );
						$desc = __('Select album(s) for random photo', 'wp-photo-album-plus' );
						$help = __( 'The albums to be used for the selection of a random photo for shortcode: [photo random]', 'wp-photo-album-plus' );
						$slug = 'wppa_photo_shortcode_random_albums';
						if ( wppa_has_many_albums() ) {
							$html = wppa_input( $slug, '220', __('Enter album ids separated by commas','wp-photo-album-plus' ) );
						}
						else {
							$albums = $wpdb->get_results( "SELECT id, name FROM $wpdb->wppa_albums", ARRAY_A );
							$albums = wppa_add_paths( $albums );
							$albums = wppa_array_sort( $albums, 'name' );
							$opts = array();
							$vals = array();
							$opts[] = __( '--- all ---', 'wp-photo-album-plus' );
							$vals[] = '-2';
							foreach( $albums as $album ) {
								$opts[] = $album['name'];
								$vals[] = $album['id'];
							}
							$html = wppa_select_m($slug, $opts, $vals, '', '', false, '', $max_width = '400' );
						}
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Select photo once', 'wp-photo-album-plus' );
						$desc = __('The same random photo on every pageload', 'wp-photo-album-plus' );
						$help = __('If ticked: the random photo is determined once at page/post creation time', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If unticked: every pageload a different photo', 'wp-photo-album-plus' );
						$slug = 'wppa_photo_shortcode_random_fixed';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Render photo once', 'wp-photo-album-plus' );
						$desc = __('Replace shortcode by html', 'wp-photo-album-plus' );
						$help = __('Do not replace \'random\' by a number, but by the corresponding html', 'wp-photo-album-plus' ) . '<br>';
						$help .= __('WARNING: changes in [photo] shortcode afterwards do no longer have any effect!', 'wp-photo-album-plus' );
						$slug = 'wppa_photo_shortcode_random_fixed_html';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					else {
						wppa_bump_subtab_id();
					}
					// Photo of the day settings
					{
						$desc = $wppa_subtab_names[$tab]['3'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$linktype = wppa_opt( 'potd_linktype' );
						if ( $linktype == 'custom' ) {

							$name = __( 'Link to', 'wp-photo-album-plus' );
							$desc = __( 'Enter the url. Do\'nt forget the HTTP://', 'wp-photo-album-plus' );
							$slug = 'wppa_potd_linkurl';
							$html = wppa_input( $slug, '85%', '', '', '', __( 'Type your custom url here', 'wp-photo-album-plus' ) );
							wppa_setting_new( $slug, '1', $name, $desc, $html, '' );

							$name = __( 'Link Title', 'wp-photo-album-plus' );
							$desc = __( 'The balloon text when hovering over the photo.', 'wp-photo-album-plus' );
							$slug = 'wppa_potd_linktitle';
							$html = wppa_input( $slug, '85%', '', '', '', __( 'Type the title here', 'wp-photo-album-plus' ) );
							wppa_setting_new($slug, '2', $name, $desc, $html, '' );
						}
						else {
							$name = __( 'Links', 'wp-photo-album-plus' );
							$desc = __( 'Links are set on the Links tab.', 'wp-photo-album-plus' ) . wppa_see_also( 'links', '3', '1' );
							$slug = 'wppa_potd_linkurl';
							$html = '';
							wppa_setting_new( $slug, '3', $name, $desc, $html, '' );
						}

						$name = __( 'Subtitle', 'wp-photo-album-plus' );
						$desc = __( 'Select the content of the subtitle.', 'wp-photo-album-plus' );
						$slug = 'wppa_potd_subtitle';
						$opts = array( 	__( '--- none ---', 'wp-photo-album-plus' ),
										__( 'Photo Name', 'wp-photo-album-plus' ),
										__( 'Description', 'wp-photo-album-plus' ),
										__( 'Owner', 'wp-photo-album-plus' ),
										__( 'Extended', 'wp-photo-album-plus' ),
									);
						$vals = array( 	'none',
										'name',
										'desc',
										'owner',
										'extended',
										);
						$html = wppa_select( $slug, $opts, $vals );
						wppa_setting_new( $slug, '4', $name, $desc, $html, '' );

						$name = __( 'Counter', 'wp-photo-album-plus' );
						$desc = __( 'Display a counter of other photos in the album.', 'wp-photo-album-plus' );
						$slug = 'wppa_potd_counter';
						$onch = "wppaSlaveChecked(this,'wppa_potd_counter_link');";
						$html = wppa_checkbox( $slug, $onch );
						wppa_setting_new( $slug, '5', $name, $desc, $html, '' );

						$name = __( 'Link to', 'wp-photo-album-plus' );
						$desc = __( 'The counter links to.', 'wp-photo-album-plus' );
						$slug = 'wppa_potd_counter_link';
						$opts = array(	__( 'thumbnails', 'wp-photo-album-plus' ),
										__( 'slideshow', 'wp-photo-album-plus' ),
										__( 'single image', 'wp-photo-album-plus' ),
										);
						$vals = array( 	'thumbs',
										'slide',
										'single',
										);
						$html = wppa_select( $slug, $opts, $vals );
						wppa_setting_new( $slug, '5a', $name, $desc, $html, '', wppa_switch( 'potd_counter' ) );

						$name = __( 'Type of album(s) to use', 'wp-photo-album-plus' );
						$desc = __( 'Select physical or virtual.', 'wp-photo-album-plus' );
						$slug = 'wppa_potd_album_type';
						$opts = array(	__( 'physical albums', 'wp-photo-album-plus' ),
										__( 'virtual albums', 'wp-photo-album-plus' ),
										);
						$vals = array(	'physical',
										'virtual',
										);
						$onch = "wppaSlaveSelected('wppa_potd_album_type-physical', 'physical');" .
								"wppaSlaveSelected('wppa_potd_album_type-virtual', 'virtual');" .
								"wppaPlanPotdUpdate();";
						$html = wppa_select( $slug, $opts, $vals, $onch );
						wppa_setting_new( $slug, '6', $name, $desc, $html, '' );

						$name = __( 'Albums to use', 'wp-photo-album-plus' );
						$desc = __( 'Select the albums to use for the photo of the day.', 'wp-photo-album-plus' );
						$slug = 'wppa_potd_album';

						if ( wppa_has_many_albums() ) {
							$html = wppa_input( $slug, '220', __('Enter album ids separated by commas','wp-photo-album-plus' ) );
						}
						else {
							$html = '
							<select
								id="wppa_potd_album"
								name="wppa_potd_album"
								style="float:left;max-width:400px;height:auto!important;"
								multiple
								onchange="wppaAjaxUpdateOptionValue(\'potd_album\', this, true);wppaPlanPotdUpdate();"
								size="10"
								>' .
								wppa_album_select_a( array ( 	'path' 			=> true,
																'optionclass' 	=> 'potd_album',
																'selected' 		=> wppa_get_option( 'wppa_potd_album' ),
																'multiple' 		=> true,
																'sort' 			=> true,
													) ) . '
							</select>
							<img
								id="img_potd_album"
								src="' . esc_url( wppa_get_imgdir() ) . 'star.ico"
								title="' . esc_attr( __( 'Setting unmodified', 'wp-photo-album-plus' ) ) . '"
								style="padding:0 4px; float:left; height:16px; width:16px;"
							/>';
						}
						$is_phys = wppa_opt( 'potd_album_type' ) == 'physical';
						wppa_setting_new( $slug, '7', $name, $desc, $html, '', $is_phys, 'physical' );

						$name = __( 'Include (sub-)sub albums', 'wp-photo-album-plus' );
						$desc = __( 'Include the photos of all sub albums?', 'wp-photo-album-plus' );
						$slug = 'wppa_potd_include_subs';
						$html = wppa_checkbox( $slug, 'wppaPlanPotdUpdate();' );
						wppa_setting_new( $slug, '9', $name, $desc, $html, '', $is_phys, 'physical' );

						$name = __( 'Inverse selection', 'wp-photo-album-plus' );
						$desc = __( 'Use any album, except the selection made above.', 'wp-photo-album-plus' );
						$slug = 'wppa_potd_inverse';
						$html = wppa_checkbox( $slug, 'wppaPlanPotdUpdate();' );
						wppa_setting_new( $slug, '10', $name, $desc, $html, '', $is_phys, 'physical' );

						// Virtual albums
						$name = __( 'Albums to use', 'wp-photo-album-plus' );
						$desc = __( 'Select the albums to use for the photo of the day.', 'wp-photo-album-plus' );
						$slug = 'wppa_potd_album';
						$opts = array( 	__( '- all albums -' , 'wp-photo-album-plus' ),
										__( '- all -separate- albums -' , 'wp-photo-album-plus' ),
										__( '- all albums except -separate-' , 'wp-photo-album-plus' ),
										__( '- top rated photos -' , 'wp-photo-album-plus' ),
									);
						$vals =	array( 	'all',
										'sep',
										'all-sep',
										'topten',
										);
						$html = wppa_select( $slug, $opts, $vals, 'wppaPlanPotdUpdate();' );
						$is_virt = wppa_opt( 'potd_album_type' ) == 'virtual';
						wppa_setting_new( $slug, '8', $name, $desc, $html, '', $is_virt, 'virtual' );

						// Common settings
						$name = __( 'Status filter', 'wp-photo-album-plus' );
						$desc = __( 'Use only photos with a certain status.', 'wp-photo-album-plus' );
						$slug = 'wppa_potd_status_filter';
						$opts = array(	__( '- none -', 'wp-photo-album-plus' ),
										__( 'Publish' , 'wp-photo-album-plus' ),
										__( 'Featured' , 'wp-photo-album-plus' ),
										__( 'Gold' , 'wp-photo-album-plus' ),
										__( 'Silver' , 'wp-photo-album-plus' ),
										__( 'Bronze' , 'wp-photo-album-plus' ),
										__( 'Any medal' , 'wp-photo-album-plus' ),
										);
						$vals = array(	'none',
										'publish',
										'featured',
										'gold',
										'silver',
										'bronze',
										'anymedal',
										);
						$html = wppa_select( $slug, $opts, $vals, 'wppaPlanPotdUpdate();' );
						wppa_setting_new( $slug, '11', $name, $desc, $html, '' );

						$name = __( 'Selection method', 'wp-photo-album-plus' );
						$desc = __( 'Select the way a photo will be selected.', 'wp-photo-album-plus' );
						$slug = 'wppa_potd_method';
						$opts = array(	__( 'Fixed photo', 'wp-photo-album-plus' ),
										__( 'Random', 'wp-photo-album-plus' ),
										__( 'Last upload', 'wp-photo-album-plus' ),
										__( 'Change every', 'wp-photo-album-plus' ),
										);
						$vals = array( 	'1',
										'2',
										'3',
										'4',
										);
						$onch = "wppaSlaveSelected('wppa_potd_method-4','wppa_potd_period');wppaPotdCheckPom4andDof();wppaPlanPotdUpdate();";
						$html = wppa_select( $slug, $opts, $vals, $onch );
						wppa_setting_new( $slug, '12', $name, $desc, $html, '' );

						$pom4 = wppa_opt( 'potd_method' ) == '4';

						$name = __( 'Change every period', 'wp-photo-album-plus' );
						$desc = __( 'The time period a certain photo is used.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_potd_period';
						$opts = array( 	__( 'pageview.', 'wp-photo-album-plus' ),
										__( 'hour.', 'wp-photo-album-plus' ),
										__( 'day.', 'wp-photo-album-plus' ),
										__( 'week.', 'wp-photo-album-plus' ),
										__( 'month.', 'wp-photo-album-plus' ),
										__( 'day of week is sequence #', 'wp-photo-album-plus' ),
										__( 'day of month is sequence #', 'wp-photo-album-plus' ),
										__( 'day of year is sequence #', 'wp-photo-album-plus' ),
										__( 'week number is sequence #', 'wp-photo-album-plus' ),
								);
						$vals = array( 	'0',
										'1',
										'24',
										'168',
										'736',
										'day-of-week',
										'day-of-month',
										'day-of-year',
										'week',
										);
						$html = wppa_select( $slug, $opts, $vals, 'wppaPotdCheckPom4andDof();wppaPlanPotdUpdate();' );
						wppa_setting_new( $slug, '13', $name, $desc, $html, $help, $pom4 );

						$wppa_widget_period = wppa_opt( 'potd_period' );
						$dof = ! wppa_is_int( $wppa_widget_period ) && $wppa_widget_period != 'week';

						$name = __( 'Day offset', 'wp-photo-album-plus' );
						$desc = __( 'The difference between daynumber and photo sequence number.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_potd_offset';
						$html = '
								<div style="float:left">' .
									sprintf( __('Current day of the week = %s, day of the month = %s, day of the year = %s, offset = ', 'wp-photo-album-plus' ),
												wppa_local_date( 'w' ), wppa_local_date( 'd' ), wppa_local_date( 'z' ) ) . '
								</div>' .
								wppa_number( $slug, 0, 365, '', 'wppaPlanPotdUpdate();' ) . '
								<div style="float:left">' .
									sprintf( __( 'Todays photo sequence # = %s.', 'wp-photo-album-plus' ), '<span id="potdseqno" ><img src="'.wppa_get_imgdir().'spinner.gif"></span>' ) . '
								</div>';
						wppa_setting_new( $slug, '14', $name, $desc, $html, $help, $pom4 && $dof );

						$name = __( 'Preview', 'wp-photo-album-plus' );
						$desc = __( 'Current "photo of the day":', 'wp-photo-album-plus' );
						$slug = 'wppa_potd_photo';
						$html = '<div id="potdpreview"><img src="' . wppa_get_imgdir() . 'spinner.gif"></div>';
						wppa_setting_new( $slug, '15', $name, $desc, $html );

						$name = __( 'Show selection', 'wp-photo-album-plus' );
						$desc = __( 'Show the photos in the current selection.', 'wp-photo-album-plus' );
						$slug = 'wppa_potd_preview';
						$onch = "wppaSlaveChecked(this,'potd-pool');";
						$html = wppa_checkbox( $slug, $onch );
						wppa_setting_new( $slug, '16', $name, $desc, $html );

						$name = __( 'Log potd', 'wp-photo-album-plus' );
						$desc = __( 'Keep track of the potd history', 'wp-photo-album-plus' );
						$slug = 'wppa_potd_log';
						$html = wppa_checkbox( $slug );
						wppa_setting_new( $slug, '17', $name, $desc, $html );

						$name = __( 'Log potd max', 'wp-photo-album-plus' );
						$desc = __( 'Max length of the potd history (items)', 'wp-photo-album-plus' );
						$slug = 'wppa_potd_log_max';
						$opts = array( '5', '10', '15', '20', '30', '50', '100' );
						$html = wppa_select( $slug, $opts, $opts );
						wppa_setting_new( $slug, '18', $name, $desc, $html );

						// The potd photo pool
						wppa_echo( '</tbody></table><div id="potd-pool"></div></div>' );
					}
				}
				break;

				case 'thumbs': {
					// Thumbnail size specifications
					$desc = $wppa_subtab_names[$tab]['1'];
					{
					wppa_setting_tab_description($desc);
					wppa_setting_box_header_new($tab);

					$name = __('Thumbnail Size', 'wp-photo-album-plus' );
					$desc = __('The size of the thumbnail images.', 'wp-photo-album-plus' );
					$help = __('This size applies to the width or height, whichever is the largest.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('Changing the thumbnail size may result in all thumbnails being regenerated. this may take a while.', 'wp-photo-album-plus' );
					$slug = 'wppa_thumbsize';
					$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
					wppa_setting_new($slug, '1', $name, $desc, $html, $help);

					$name = __('Thumbnail Size Alt', 'wp-photo-album-plus' );
					$desc = __('The alternative size of the thumbnail images.', 'wp-photo-album-plus' );
					$help = __('This size applies to the width or height, whichever is the largest.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('Changing the thumbnail size may result in all thumbnails being regenerated. this may take a while.', 'wp-photo-album-plus' );
					$slug = 'wppa_thumbsize_alt';
					$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
					wppa_setting_new($slug, '2', $name, $desc, $html, $help);

					$name = __('Thumbnail Aspect', 'wp-photo-album-plus' );
					$desc = __('Aspect ration of thumbnail image', 'wp-photo-album-plus' );
					$help = '';
					$slug = 'wppa_thumb_aspect';
					$opts = array(
						__('--- same as fullsize ---', 'wp-photo-album-plus' ),
						__('--- square clipped ---', 'wp-photo-album-plus' ),
						__('4:5 landscape clipped', 'wp-photo-album-plus' ),
						__('3:4 landscape clipped', 'wp-photo-album-plus' ),
						__('2:3 landscape clipped', 'wp-photo-album-plus' ),
						__('5:8 landscape clipped', 'wp-photo-album-plus' ),
						__('9:16 landscape clipped', 'wp-photo-album-plus' ),
						__('1:2 landscape clipped', 'wp-photo-album-plus' ),
						__('--- square padded ---', 'wp-photo-album-plus' ),
						__('4:5 landscape padded', 'wp-photo-album-plus' ),
						__('3:4 landscape padded', 'wp-photo-album-plus' ),
						__('2:3 landscape padded', 'wp-photo-album-plus' ),
						__('5:8 landscape padded', 'wp-photo-album-plus' ),
						__('9:16 landscape padded', 'wp-photo-album-plus' ),
						__('1:2 landscape padded', 'wp-photo-album-plus' )
						);
					$vals = array(
						'0:0:none',
						'1:1:clip',
						'4:5:clip',
						'3:4:clip',
						'2:3:clip',
						'5:8:clip',
						'9:16:clip',
						'1:2:clip',
						'1:1:padd',
						'4:5:padd',
						'3:4:padd',
						'2:3:padd',
						'5:8:padd',
						'9:16:padd',
						'1:2:padd'
						);
					$html = wppa_select($slug, $opts, $vals);
					wppa_setting_new($slug, '3', $name, $desc, $html, $help);

					$name = __('Thumbnail padding', 'wp-photo-album-plus' );
					$desc = __('Thumbnail padding color if thumbnail aspect is a padded setting.', 'wp-photo-album-plus' );
					$help = __('Enter valid CSS color hexadecimal like #000000 for black or #ffffff for white for the padded thumbnails.', 'wp-photo-album-plus' );
					$slug = 'wppa_bgcolor_thumbnail';
					$html = wppa_input($slug, '100px', '', '', "checkColor('".$slug."')") . wppa_color_box($slug);
					wppa_setting_new($slug, '4', $name, $desc, $html, $help);

					$name = __('Thumbframe width', 'wp-photo-album-plus' );
					$desc = __('The width of the thumbnail frame.', 'wp-photo-album-plus' );
					$help = __('Set the width of the thumbnail frame.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('Set width, height and spacing for the thumbnail frames.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('These sizes should be large enough for a thumbnail image and - optionally - the text under it.', 'wp-photo-album-plus' );
					$slug = 'wppa_tf_width';
					$html = wppa_input($slug, '40px', '', __('pixels wide', 'wp-photo-album-plus' ));
					wppa_setting_new($slug, '5', $name, $desc, $html, $help);

					$name = __('Thumbframe width Alt', 'wp-photo-album-plus' );
					$desc = __('The width of the alternative thumbnail frame.', 'wp-photo-album-plus' );
					$help = __('Set the width of the thumbnail frame.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('Set width, height and spacing for the thumbnail frames.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('These sizes should be large enough for a thumbnail image and - optionally - the text under it.', 'wp-photo-album-plus' );
					$slug = 'wppa_tf_width_alt';
					$html = wppa_input($slug, '40px', '', __('pixels wide', 'wp-photo-album-plus' ));
					wppa_setting_new($slug, '6', $name, $desc, $html, $help);

					$name = __('Thumbframe height', 'wp-photo-album-plus' );
					$desc = __('The height of the thumbnail frame.', 'wp-photo-album-plus' );
					$help = __('Set the height of the thumbnail frame.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('Set width, height and spacing for the thumbnail frames.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('These sizes should be large enough for a thumbnail image and - optionally - the text under it.', 'wp-photo-album-plus' );
					$slug = 'wppa_tf_height';
					$html = wppa_input($slug, '40px', '', __('pixels high', 'wp-photo-album-plus' ));
					wppa_setting_new($slug, '7', $name, $desc, $html, $help);

					$name = __('Thumbframe height Alt', 'wp-photo-album-plus' );
					$desc = __('The height of the alternative thumbnail frame.', 'wp-photo-album-plus' );
					$help = __('Set the height of the thumbnail frame.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('Set width, height and spacing for the thumbnail frames.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('These sizes should be large enough for a thumbnail image and - optionally - the text under it.', 'wp-photo-album-plus' );
					$slug = 'wppa_tf_height_alt';
					$html = wppa_input($slug, '40px', '', __('pixels high', 'wp-photo-album-plus' ));
					wppa_setting_new($slug, '8', $name, $desc, $html, $help);

					$name = __('Thumbnail spacing', 'wp-photo-album-plus' );
					$desc = __('The spacing between adjacent thumbnail frames.', 'wp-photo-album-plus' );
					$help = __('Set the minimal spacing between the adjacent thumbnail frames', 'wp-photo-album-plus' );
					$help .= '<br>'.__('Set width, height and spacing for the thumbnail frames.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('These sizes should be large enough for a thumbnail image and - optionally - the text under it.', 'wp-photo-album-plus' );
					$slug = 'wppa_tn_margin';
					$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
					wppa_setting_new($slug, '9', $name, $desc, $html, $help);

					$name = __('Auto spacing', 'wp-photo-album-plus' );
					$desc = __('Space the thumbnail frames automatic.', 'wp-photo-album-plus' );
					$help = __('If you check this box, the thumbnail images will be evenly distributed over the available width.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('In this case, the thumbnail spacing value (setting I-9) will be regarded as a minimum value.', 'wp-photo-album-plus' );
					$slug = 'wppa_thumb_auto';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '10', $name, $desc, $html, $help);

					$name = __('Page size', 'wp-photo-album-plus' );
					$desc = __('Max number of thumbnails per page.', 'wp-photo-album-plus' );
					$help = __('Enter the maximum number of thumbnail images per page. A value of 0 indicates no pagination.', 'wp-photo-album-plus' );
					$slug = 'wppa_thumb_page_size';
					$html = wppa_input($slug, '40px', '', __('thumbnails', 'wp-photo-album-plus' ));
					wppa_setting_new($slug, '11', $name, $desc, $html, $help);

					$name = __('Popup size', 'wp-photo-album-plus' );
					$desc = __('The size of the thumbnail popup images.', 'wp-photo-album-plus' );
					$help = __('Enter the size of the popup images. This size should be larger than the thumbnail size.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('This size should also be at least the cover image size.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('Changing the popup size may result in all thumbnails being regenerated. this may take a while.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('Although this setting has only visual effect if "Thumb popup" (Thumbnails -> III -> Thumb popup) is checked,', 'wp-photo-album-plus' );
					$help .= ' '.__('the value must be right as it is the physical size of the thumbnail and coverphoto images.', 'wp-photo-album-plus' );
					$slug = 'wppa_popupsize';
					$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
					wppa_setting_new($slug, '12', $name, $desc, $html, $help);

					$name = __('Use thumbs if fit', 'wp-photo-album-plus' );
					$desc = __('Use the thumbnail image files if they are large enough.', 'wp-photo-album-plus' );
					$help = __('This setting speeds up page loading for small photos.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('Do NOT use this when your thumbnails have a forced aspect ratio (when Thumbnails -> I -> Thumbnail Aspect is set to anything different from --- same as fullsize ---)', 'wp-photo-album-plus' );
					$slug = 'wppa_use_thumbs_if_fit';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '13', $name, $desc, $html, $help);

					$name = __( 'Icon size', 'wp-photo-album-plus' );
					$desc = __( 'The size of the medals and multimedia icons on thumbnails', 'wp-photo-album-plus' );
					$help = '';
					$slug = 'wppa_icon_size_multimedia';
					$opts = array( 'Small', 'Medium', 'Large', 'Extra large' );
					$vals = array( 'S', 'M', 'L', 'XL' );
					$html = wppa_select( $slug, $opts, $vals );
					wppa_setting_new( $slug, '14', $name, $desc, $html, $help );

					wppa_setting_box_footer_new();
					}
					// Thumbnail display options
					$desc = $wppa_subtab_names[$tab]['2'];
					{
					wppa_setting_tab_description($desc);
					wppa_setting_box_header_new($tab);

					$name = __('Thumbnail name', 'wp-photo-album-plus' );
					$desc = __('Display Thumbnail name.', 'wp-photo-album-plus' );
					$help = __('Display photo name under thumbnail images.', 'wp-photo-album-plus' );
					$slug = 'wppa_thumb_text_name';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '1', $name, $desc, $html, $help);

					$name = __('Add (Owner)', 'wp-photo-album-plus' );
					$desc = __('Add the uploaders display name in parenthesis to the name.', 'wp-photo-album-plus' );
					$help = '';
					$slug = 'wppa_thumb_text_owner';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '2', $name, $desc, $html, $help);

					$name = __('Thumbnail desc', 'wp-photo-album-plus' );
					$desc = __('Display Thumbnail description.', 'wp-photo-album-plus' );
					$help = __('Display description of the photo under thumbnail images.', 'wp-photo-album-plus' );
					$slug = 'wppa_thumb_text_desc';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '3', $name, $desc, $html, $help);

					$name = __('Thumbnail comcount', 'wp-photo-album-plus' );
					$desc = __('Display Thumbnail Comment count.', 'wp-photo-album-plus' );
					$help = __('Display the number of comments to the photo under the thumbnail image.', 'wp-photo-album-plus' );
					$slug = 'wppa_thumb_text_comcount';
					$slug2 = 'wppa_thumb_text_comcount_note_role';
					$roles = $wp_roles->roles;
					$opts = array();
					$vals = array();
					$opts[] = '-- '.__('Select a role', 'wp-photo-album-plus' ).' --';
					$vals[] = '';
					foreach (array_keys($roles) as $key) {
						$role = $roles[$key];
						$rolename = translate_user_role( $role['name'] );
						$opts[] = $rolename;
						$vals[] = $key;
					}
					$html = wppa_checkbox($slug) .
							'<span style="float:left">&nbsp;' . __( 'For userrole users indicate comments given by userrole', 'wp-photo-album-plus' ) . '&nbsp;</span>' .
							wppa_select($slug2, $opts, $vals);
					wppa_setting_new($slug, '4', $name, $desc, $html, $help);

					$name = __('Thumbnail viewcount', 'wp-photo-album-plus' );
					$desc = __('Display the number of views.', 'wp-photo-album-plus' );
					$help = __('Display the number of views under the thumbnail image.', 'wp-photo-album-plus' );
					$slug = 'wppa_thumb_text_viewcount';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '5', $name, $desc, $html, $help);

					$name = __('Thumbnail virt album', 'wp-photo-album-plus' );
					$desc = __('Display the real album name on virtual album display.', 'wp-photo-album-plus' );
					$help = __('Display the album name of the photo in parenthesis under the thumbnail on virtual album displays like search results etc.', 'wp-photo-album-plus' );
					$slug = 'wppa_thumb_text_virt_album';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '6', $name, $desc, $html, $help);

					$name = __('Thumbnail video', 'wp-photo-album-plus' );
					$desc = __('Show video controls on thumbnail displays.', 'wp-photo-album-plus' );
					$help = __('Works on default thumbnail type only. You can play the video only when the link is set to no link at all.', 'wp-photo-album-plus' );
					$slug = 'wppa_thumb_video';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '7', $name, $desc, $html, $help, wppa_switch('enable_video'));

					$name = __('Thumbnail audio', 'wp-photo-album-plus' );
					$desc = __('Show audio controls on thumbnail displays.', 'wp-photo-album-plus' );
					$help = '';
					$slug = 'wppa_thumb_audio';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '8', $name, $desc, $html, $help, wppa_switch('enable_audio'));

//					if ( wppa_opt( 'thumb_popup' ) !== 'none' ) {
					$has_popup = wppa_opt( 'thumb_popup' ) !== 'none';

						$name = __('Popup name', 'wp-photo-album-plus' );
						$desc = __('Display Thumbnail name on popup.', 'wp-photo-album-plus' );
						$help = __('Display photo name under thumbnail images on the popup.', 'wp-photo-album-plus' );
						$slug = 'wppa_popup_text_name';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '9', $name, $desc, $html, $help, $has_popup, 'popup');

						$name = __('Popup (owner)', 'wp-photo-album-plus' );
						$desc = __('Display owner on popup.', 'wp-photo-album-plus' );
						$help = __('Display photo owner under thumbnail images on the popup.', 'wp-photo-album-plus' );
						$slug = 'wppa_popup_text_owner';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '10', $name, $desc, $html, $help, $has_popup, 'popup');

						$name = __('Popup desc', 'wp-photo-album-plus' );
						$desc = __('Display Thumbnail description on popup.', 'wp-photo-album-plus' );
						$help = __('Display description of the photo under thumbnail images on the popup.', 'wp-photo-album-plus' );
						$slug = 'wppa_popup_text_desc';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '11', $name, $desc, $html, $help, $has_popup, 'popup');

						$name = __('Popup desc no links', 'wp-photo-album-plus' );
						$desc = __('Strip html anchor tags from descriptions on popups', 'wp-photo-album-plus' );
						$help = __('Use this option to prevent the display of links that cannot be activated.', 'wp-photo-album-plus' );
						$slug = 'wppa_popup_text_desc_strip';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '12', $name, $desc, $html, $help, $has_popup, 'popup');

						$name = __('Popup rating', 'wp-photo-album-plus' );
						$desc = __('Display Thumbnail Rating on popup.', 'wp-photo-album-plus' );
						$help = __('Display the rating of the photo under the thumbnail image on the popup.', 'wp-photo-album-plus' );
						$slug = 'wppa_popup_text_rating';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '13', $name, $desc, $html, $help, wppa_switch('rating_on') && $has_popup, 'popup');

						$name = __('Popup comcount', 'wp-photo-album-plus' );
						$desc = __('Display Thumbnail Comment count on popup.', 'wp-photo-album-plus' );
						$help = __('Display the number of comments of the photo under the thumbnail image on the popup.', 'wp-photo-album-plus' );
						$slug = 'wppa_popup_text_ncomments';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '14', $name, $desc, $html, $help, wppa_switch('show_comments') && $has_popup, 'popup');
//					}

					$name = __('Show album name on thumb area', 'wp-photo-album-plus' );
					$desc = __('Select if and where to display the album name on the thumbnail display.', 'wp-photo-album-plus' );
					$help = '';
					$slug = 'wppa_albname_on_thumbarea';
					$opts = array(__('None', 'wp-photo-album-plus' ), __('At the top', 'wp-photo-album-plus' ), __('At the bottom', 'wp-photo-album-plus' ));
					$vals = array('none', 'top', 'bottom');
					$html = wppa_select($slug, $opts, $vals);
					wppa_setting_new($slug, '15', $name, $desc, $html, $help);

					$name = __('Show album desc on thumb area', 'wp-photo-album-plus' );
					$desc = __('Select if and where to display the album description on the thumbnail display.', 'wp-photo-album-plus' );
					$help = '';
					$slug = 'wppa_albdesc_on_thumbarea';
					$opts = array(__('None', 'wp-photo-album-plus' ), __('At the top', 'wp-photo-album-plus' ), __('At the bottom', 'wp-photo-album-plus' ));
					$vals = array('none', 'top', 'bottom');
					$html = wppa_select($slug, $opts, $vals);
					wppa_setting_new($slug, '16', $name, $desc, $html, $help);

					$name = __('Show Edit/Delete links', 'wp-photo-album-plus' );
					$desc = __('Show these links under default thumbnails for owner and admin.', 'wp-photo-album-plus' );
					$help = '';
					$slug = 'wppa_edit_thumb';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '17', $name, $desc, $html, $help);

					$name = __('Show empty thumbnail area', 'wp-photo-album-plus' );
					$desc = __('Display thumbnail areas with upload link only for empty albums.', 'wp-photo-album-plus' );
					$help = '';
					$slug = 'wppa_show_empty_thumblist';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '18', $name, $desc, $html, $help);

					$name = __('Upload/create link on thumbnail area', 'wp-photo-album-plus' );
					$desc = __('Select the location of the upload and crete links.', 'wp-photo-album-plus' );
					$help = '';
					$slug = 'wppa_upload_link_thumbs';
					$opts = array(__('None', 'wp-photo-album-plus' ), __('At the top', 'wp-photo-album-plus' ), __('At the bottom', 'wp-photo-album-plus' ));
					$vals = array('none', 'top', 'bottom');
					$html = wppa_select($slug, $opts, $vals);
					wppa_setting_new($slug, '19', $name, $desc, $html, $help);

					wppa_setting_box_footer_new();
					}
					// Thumbnail layout settings
					$desc = $wppa_subtab_names[$tab]['3'];
					{
					wppa_setting_tab_description($desc);
					wppa_setting_box_header_new($tab);

					$name = __('Thumbnail type', 'wp-photo-album-plus' );
					$desc = __('The way the thumbnail images are displayed.', 'wp-photo-album-plus' );
					$help = __('You may select an altenative display method for thumbnails. Note that some of the thumbnail settings do not apply to all available display methods.', 'wp-photo-album-plus' );
					$slug = 'wppa_thumbtype';
					$opts = array( 	__('--- default ---', 'wp-photo-album-plus' ),
									__('like album covers', 'wp-photo-album-plus' ),
									__('like album covers mcr', 'wp-photo-album-plus' ),
									__('masonry style columns', 'wp-photo-album-plus' ),
									__('masonry style rows', 'wp-photo-album-plus' ),
									__('masonry style plus', 'wp-photo-album-plus' ),
									__('masonry style mixed', 'wp-photo-album-plus' ),
									  );
					$vals = array(	'default',
									'ascovers',
									'ascovers-mcr',
									'masonry-v',
									'masonry-h',
									'masonry-plus',
									'masonry-mix',
									);
					$html = wppa_select($slug, $opts, $vals);
					wppa_setting_new($slug, '1', $name, $desc, $html, $help);

					$name = __('Placement', 'wp-photo-album-plus' );
					$desc = __('Thumbnail image left or right.', 'wp-photo-album-plus' );
					$help = __('Indicate the placement position of the thumbnailphoto you wish.', 'wp-photo-album-plus' );
					$slug = 'wppa_thumbphoto_left';
					$opts = array(__('Left', 'wp-photo-album-plus' ), __('Right', 'wp-photo-album-plus' ));
					$vals = array('yes', 'no');
					$html = wppa_select($slug, $opts, $vals);
					wppa_setting_new($slug, '2', $name, $desc, $html, $help);

					$name = __('Vertical alignment', 'wp-photo-album-plus' );
					$desc = __('Vertical alignment of thumbnails.', 'wp-photo-album-plus' );
					$help = __('Specify the vertical alignment of thumbnail images. Use this setting when albums contain both portrait and landscape photos.', 'wp-photo-album-plus' );
					$help .= '<br>'.__('It is NOT recommended to use the value --- default ---; it will affect the horizontal alignment also and is meant to be used with custom css.', 'wp-photo-album-plus' );
					$slug = 'wppa_valign';
					$opts = array( __('--- default ---', 'wp-photo-album-plus' ), __('top', 'wp-photo-album-plus' ), __('center', 'wp-photo-album-plus' ), __('bottom', 'wp-photo-album-plus' ));
					$vals = array('default', 'top', 'center', 'bottom');
					$html = wppa_select($slug, $opts, $vals);
					wppa_setting_new($slug, '3', $name, $desc, $html, $help);

					$name = __('Thumb mouseover', 'wp-photo-album-plus' );
					$desc = __('Apply thumbnail mouseover effect.', 'wp-photo-album-plus' );
					$help = __('Check this box to use mouseover effect on thumbnail images.', 'wp-photo-album-plus' );
					$slug = 'wppa_use_thumb_opacity';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '4', $name, $desc, $html, $help);

					$name = __('Thumb opacity', 'wp-photo-album-plus' );
					$desc = __('Initial opacity value.', 'wp-photo-album-plus' );
					$help = __('Enter percentage of opacity. 100% is opaque, 0% is transparant', 'wp-photo-album-plus' );
					$slug = 'wppa_thumb_opacity';
					$html = wppa_input($slug, '50px', '', '%');
					wppa_setting_new($slug, '5', $name, $desc, $html, $help);

					$name = __('Thumb popup', 'wp-photo-album-plus' );
					$desc = __('Use popup effect on thumbnail images.', 'wp-photo-album-plus' );
					$help = __('Thumbnails pop-up to a larger image when hovered.', 'wp-photo-album-plus' );
					$slug = 'wppa_thumb_popup';
					$opts = array( 	__('Both pc and mobile', 'wp-photo-album-plus'),
									__('On pc only', 'wp-photo-album-plus'),
									__('Never', 'wp-photo-album-plus') );
					$vals = array( 'all', 'pc', 'none' );
					$onch = "wppaUnSlaveSelected('wppa_thumb_popup-none','popup');";
					$html = wppa_select($slug, $opts, $vals, $onch);
					wppa_setting_new($slug, '6', $name, $desc, $html, $help);

					$name = __('Align subtext', 'wp-photo-album-plus' );
					$desc = __('Set thumbnail subtext on equal height.', 'wp-photo-album-plus' );
					$help = '';
					$slug = 'wppa_align_thumbtext';
					$html = wppa_checkbox($slug);
					wppa_setting_new($slug, '7', $name, $desc, $html, $help);

					$name = __('Run nl2br or wpautop on description', 'wp-photo-album-plus' );
					$desc = __('Adds &lt;br> or &lt;p> and &lt;br> tags in thumbnail descriptions.', 'wp-photo-album-plus' );
					$help = '';
					$slug = 'wppa_wpautop_on_thumb_desc';
					$opts = array(	__('--- none ---', 'wp-photo-album-plus' ),
									__('Linebreaks only', 'wp-photo-album-plus' ),
									__('Linebreaks and paragraphs', 'wp-photo-album-plus' ),
								);
					$vals = array('nil', 'nl2br', 'wpautop');
					$html = wppa_select($slug, $opts, $vals);
					wppa_setting_new($slug, '8', $name, $desc, $html, $help);

					$name = __('Popup easing formula', 'wp-photo-album-plus' );
					$desc = __('The animation method', 'wp-photo-album-plus' );
					$help = '';
					$slug = 'wppa_easing_popup';
					$opts = array(	'swing', 'linear', 'easeInSine', 'easeOutSine', 'easeInOutSine', 'easeInQuad',
									'easeOutQuad', 'easeInOutQuad', 'easeInCubic', 'easeOutCubic', 'easeInOutCubic',
									'easeInQuart', 'easeOutQuart', 'easeInOutQuart', 'easeInQuint', 'easeOutQuint',
									'easeInOutQuint', 'easeInExpo', 'easeOutExpo', 'easeInOutExpo', 'easeInCirc',
									'easeOutCirc', 'easeInOutCirc', 'easeInBack', 'easeOutBack', 'easeInOutBack',
									'easeInElastic', 'easeOutElastic', 'easeInOutElastic', 'easeInBounce',
									'easeOutBounce', 'easeInOutBounce' );
					$vals = $opts;
					$html = wppa_select($slug, $opts, $vals);
					wppa_setting_new($slug, '9', $name, $desc, $html, $help, wppa_opt( 'thumb_popup' ) != 'none' );

					wppa_setting_box_footer_new();
					}
				}
				break;

				case 'slide': {
					// Sllideshow component specifications
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Maximum Width', 'wp-photo-album-plus' );
						$desc = __('The maximum width photos will be displayed in slideshows.', 'wp-photo-album-plus' );
						$help = __('Enter the largest size in pixels as how you want your photos to be displayed.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('This is usually the same as the Column Width, but it may differ.', 'wp-photo-album-plus' );
						$help .= '<br>'.wppa_see_also( 'layout', '1', '5' );
						$slug = 'wppa_fullsize';
						$html = wppa_input($slug, '40px', '', __('pixels wide', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Maximum Height', 'wp-photo-album-plus' );
						$desc = __('The maximum height photos will be displayed in slideshows.', 'wp-photo-album-plus' );
						$help = __('Enter the largest size in pixels as how you want your photos to be displayed.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('This setting defines the height of the space reserved for photos in slideshows.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If you change the width of a display by the size=".." shortcode attribute, this value changes proportionally to match the aspect ratio as defined by this and the previous setting.', 'wp-photo-album-plus' );
						$slug = 'wppa_maxheight';
						$html = wppa_input($slug, '40px', '', __('pixels high', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Stretch to fit', 'wp-photo-album-plus' );
						$desc = __('Stretch photos that are too small.', 'wp-photo-album-plus' );
						$help = __('Images will be stretched to the Maximum Size at display time if they are smaller. Leaving unchecked is recommended. It is better to upload photos that fit well the sizes you use!', 'wp-photo-album-plus' );
						$slug = 'wppa_enlarge';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Slideshow borderwidth', 'wp-photo-album-plus' );
						$desc = __('The width of the border around slideshow images.', 'wp-photo-album-plus' );
						$help = __('The border is made by the image background being larger than the image itsself (padding).', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Additionally there may be a one pixel outline of a different color.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('The number you enter here is exclusive the one pixel outline.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If you leave this entry empty, there will be no outline either.', 'wp-photo-album-plus' );
						$help .= '<br>'.wppa_see_also( 'slide', '1', '35' );
						$slug = 'wppa_fullimage_border_width';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						$clas = '';
						$tags = 'size,slide,layout';
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Share button size', 'wp-photo-album-plus' );
						$desc = __('The size of the social media icons in the Share box', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_share_size';
						$opts = array('16 x 16', '20 x 20', '32 x 32');
						$vals = array('16', '20', '32');
						$html = wppa_select($slug, $opts, $vals) . __('pixels', 'wp-photo-album-plus' );
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Mini Threshold', 'wp-photo-album-plus' );
						$desc = __('Show mini text at slideshow smaller than.', 'wp-photo-album-plus' );
						$help = __('Display Next and Prev. as opposed to Next photo and Previous photo when the cotainer is smaller than this size.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Special use in responsive themes.', 'wp-photo-album-plus' );
						$slug = 'wppa_mini_treshold';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Slideshow pagesize', 'wp-photo-album-plus' );
						$desc = __('The maximum number of slides in a certain view. 0 means no pagination', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_slideshow_pagesize';
						$html = wppa_input($slug, '40px', '', __('slides', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('Slideonly max', 'wp-photo-album-plus' );
						$desc = __('The max number of slides in a slideonly or filmonly display', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_slideonly_max';
						$html = wppa_input($slug, '40px', '', __('slides', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						$name = __('Show Share Box', 'wp-photo-album-plus' );
						$desc = __('Display the share social media buttons box.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_share_on';
						$onch = '';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '10', $name, $desc, $html, $help);

						$name = __('Start/stop', 'wp-photo-album-plus' );
						$desc = __('Show the Start/Stop slideshow bar.', 'wp-photo-album-plus' );
						$help = __('If checked: display the start/stop slideshow navigation bar above the full-size images and slideshow', 'wp-photo-album-plus' );
						$slug = 'wppa_show_startstop_navigation';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '11', $name, $desc, $html, $help);

						$name = __('Browse bar', 'wp-photo-album-plus' );
						$desc = __('Show Browse photos bar.', 'wp-photo-album-plus' );
						$help = __('If checked: display the preveous/next navigation bar under the full-size images and slideshow', 'wp-photo-album-plus' );
						$slug = 'wppa_show_browse_navigation';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '14', $name, $desc, $html, $help);

						$name = __('Photo name', 'wp-photo-album-plus' );
						$desc = __('Display photo name.', 'wp-photo-album-plus' );
						$help = __('If checked: display the name of the photo under the slideshow image.', 'wp-photo-album-plus' );
						$slug = 'wppa_show_full_name';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '17', $name, $desc, $html, $help);

						$name = __('Add (Owner)', 'wp-photo-album-plus' );
						$desc = __('Add the uploaders display name in parenthesis to the name.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_show_full_owner';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '18', $name, $desc, $html, $help);

						$name = __('Owner on new line', 'wp-photo-album-plus' );
						$desc = __('Place the (owner) text on a new line.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_owner_on_new_line';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '19', $name, $desc, $html, $help);

						$name = __('Photo desc', 'wp-photo-album-plus' );
						$desc = __('Display Photo description.', 'wp-photo-album-plus' );
						$help = __('If checked: display the description of the photo under the slideshow image.', 'wp-photo-album-plus' );
						$slug = 'wppa_show_full_desc';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '20', $name, $desc, $html, $help);

						$name = __('Hide when empty', 'wp-photo-album-plus' );
						$desc = __('Hide the descriptionbox when empty.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_hide_when_empty';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '21', $name, $desc, $html, $help);

						$name = __('Big Browse Buttons', 'wp-photo-album-plus' );
						$desc = __('Enable invisible browsing buttons.', 'wp-photo-album-plus' );
						$help = __('If checked, the fullsize image is covered by two invisible areas that act as browse buttons.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Make sure the Maximum height is properly configured to prevent these areas to overlap unwanted space.', 'wp-photo-album-plus' );
						$help .= '<br>'.wppa_see_also( 'slide', '1', '2' );
						$slug = 'wppa_show_bbb';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '22', $name, $desc, $html, $help);

						$name = __('Ugly Browse Buttons', 'wp-photo-album-plus' );
						$desc = __('Enable the ugly browsing buttons.', 'wp-photo-album-plus' );
						$help = __('If checked, the fullsize image is covered by two browse buttons.', 'wp-photo-album-plus' );
						$slug = 'wppa_show_ubb';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '23', $name, $desc, $html, $help);

						$name = __('Start/stop icons', 'wp-photo-album-plus' );
						$desc = __('Show start and stop icons at the center of the slide', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_show_start_stop_icons';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '24', $name, $desc, $html, $help);

						$name = __('Show custom box', 'wp-photo-album-plus' );
						$desc = __('Display the custom box in the slideshow', 'wp-photo-album-plus' );
						$help = __('You can fill the custom box with any html you like. It will not be checked, so it is your own responsibility to close tags properly.', 'wp-photo-album-plus' );
						$help .= '<br>'.wppa_see_also( 'slide', '2' );
						$slug = 'wppa_custom_on';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '25', $name, $desc, $html, $help);

						$name = __('Custom content', 'wp-photo-album-plus' );
						$desc = __('The content (html) of the custom box.', 'wp-photo-album-plus' );
						$help = __('You can fill the custom box with any html you like. It will not be checked, so it is your own responsibility to close tags properly.', 'wp-photo-album-plus' );
						$help .= '<br>'.wppa_see_also( 'slide', '2' );
						$slug = 'wppa_custom_content';
						$html = wppa_textarea($slug, $name);
						wppa_setting_new(false, '26', $name, $desc, $html, $help);

						$name = __('Slideshow/Number bar', 'wp-photo-album-plus' );
						$desc = __('Display the Slideshow / Number bar.', 'wp-photo-album-plus' );
						$help = __('If checked: display the number boxes on slideshow', 'wp-photo-album-plus' );
						$slug = 'wppa_show_slideshownumbar';
						$onch = "wppaSlaveChecked(this,'numbar');";
						$html = wppa_checkbox($slug, $onch);
						wppa_setting_new($slug, '27', $name, $desc, $html, $help);

						$name = __('Numbar Max', 'wp-photo-album-plus' );
						$desc = __('Maximum numbers to display.', 'wp-photo-album-plus' );
						$help = __('In order to attempt to fit on one line, the numbers will be replaced by dots - except the current - when there are more than this number of photos in a slideshow.', 'wp-photo-album-plus' );
						$slug = 'wppa_numbar_max';
						$html = wppa_input($slug, '40px', '', __('numbers', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '28', $name, $desc, $html, $help, wppa_switch( 'show_slideshownumbar' ), 'numbar' );

						$name = __('Numbar', 'wp-photo-album-plus' );
						$desc = __('Number bar box background.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_bgcolor_numbar';
						$slug2 = 'wppa_bcolor_numbar';
						$slug = array($slug1, $slug2);
						$html = '<span style="float:left;position:relative;top:5px">' . __('Background', 'wp-photo-album-plus' ) . ':&nbsp;</span>' .
								wppa_input($slug1, '100px', '', '', "checkColor('".$slug1."')") . wppa_color_box($slug1) .
								'<span style="float:left;position:relative;top:5px;padding-left:5px">' . __('Border', 'wp-photo-album-plus' ) . ':&nbsp;</span>' .
								wppa_input($slug2, '100px', '', '', "checkColor('".$slug2."')") . wppa_color_box($slug2);
						wppa_setting_new($slug, '29', $name, $desc, $html, $help, wppa_switch('show_slideshownumbar'), 'numbar' );

						$name = __('Numbar active', 'wp-photo-album-plus' );
						$desc = __('Number bar active box background.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_bgcolor_numbar_active';
						$slug2 = 'wppa_bcolor_numbar_active';
						$slug = array($slug1, $slug2);
						$html = '<span style="float:left;position:relative;top:5px">' . __('Background', 'wp-photo-album-plus' ) . ':&nbsp;</span>' .
								wppa_input($slug1, '100px', '', '', "checkColor('".$slug1."')") . wppa_color_box($slug1) .
								'<span style="float:left;position:relative;top:5px;padding-left:5px">' . __('Border', 'wp-photo-album-plus' ) . ':&nbsp;</span>' .
								wppa_input($slug2, '100px', '', '', "checkColor('".$slug2."')") . wppa_color_box($slug2);
						wppa_setting_new($slug, '30', $name, $desc, $html, $help, wppa_switch('show_slideshownumbar'), 'numbar' );

						$name = __('IPTC system', 'wp-photo-album-plus' );
						$desc = __('Enable the iptc system.', 'wp-photo-album-plus' );
						$help = __('Display the iptc box under the fullsize images.', 'wp-photo-album-plus' );
						$slug = 'wppa_show_iptc';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '31', $name, $desc, $html, $help, wppa_switch('save_iptc'));

						$name = __('IPTC open', 'wp-photo-album-plus' );
						$desc = __('Display the iptc box initially opened.', 'wp-photo-album-plus' );
						$help = __('Display the iptc box under the fullsize images initially open.', 'wp-photo-album-plus' );
						$slug = 'wppa_show_iptc_open';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '32', $name, $desc, $html, $help, wppa_switch('save_iptc'));

						$name = __('EXIF system', 'wp-photo-album-plus' );
						$desc = __('Enable the exif system.', 'wp-photo-album-plus' );
						$help = __('Display the exif box under the fullsize images.', 'wp-photo-album-plus' );
						$slug = 'wppa_show_exif';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '33', $name, $desc, $html, $help, wppa_switch('save_exif'));

						$name = __('EXIF open', 'wp-photo-album-plus' );
						$desc = __('Display the exif box initially opened.', 'wp-photo-album-plus' );
						$help = __('Display the exif box under the fullsize images initially open.', 'wp-photo-album-plus' );
						$slug = 'wppa_show_exif_open';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '34', $name, $desc, $html, $help, wppa_switch('save_exif'));

						$name = __('Slide Image border', 'wp-photo-album-plus' );
						$desc = __('Fullsize Slideshow Photos background and border.', 'wp-photo-album-plus' );
						$help = '';
						$help .= '<br>'.__('The colors may be equal or "transparent"', 'wp-photo-album-plus' );
						$slug1 = 'wppa_bgcolor_fullimg';
						$slug2 = 'wppa_bcolor_fullimg';
						$slug = array($slug1, $slug2);
						$html = '<span style="float:left;position:relative;top:5px">' . __('Background', 'wp-photo-album-plus' ) . ':&nbsp;</span>' .
								wppa_input($slug1, '100px', '', '', "checkColor('".$slug1."')") . wppa_color_box($slug1) .
								'<span style="float:left;position:relative;top:5px;padding-left:5px">' . __('Border', 'wp-photo-album-plus' ) . ':&nbsp;</span>' .
								wppa_input($slug2, '100px', '', '', "checkColor('".$slug2."')") . wppa_color_box($slug2);
						wppa_setting_new($slug, '35', $name, $desc, $html, $help);

						$name = __('Navigation type', 'wp-photo-album-plus' );
						$desc = __('Select the type of navigation you want.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_navigation_type';
						$opts = array( 	__('Icons', 'wp-photo-album-plus' ),
										__('Icons on mobile, text on pc', 'wp-photo-album-plus' ),
										__('Text', 'wp-photo-album-plus' ),
									);
						$vals = array( 	'icons',
										'iconsmobile',
										'text',
									);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '36', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// Slideshow component sequence
					{
						$desc = $wppa_subtab_names[$tab]['2'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

		//				if ( wppa_switch( 'split_namedesc') ) {
							$indexopt = wppa_opt( 'slide_order_split' );
							$indexes  = explode(',', $indexopt);
							$names    = array(
								__('StartStop', 'wp-photo-album-plus' ),
								__('SlideFrame', 'wp-photo-album-plus' ),
								__('Name', 'wp-photo-album-plus' ),
								__('Description', 'wp-photo-album-plus' ),
								__('Custom', 'wp-photo-album-plus' ),
								__('Rating', 'wp-photo-album-plus' ),
								__('FilmStrip', 'wp-photo-album-plus' ),
								__('Browsebar', 'wp-photo-album-plus' ),
								__('Comments', 'wp-photo-album-plus' ),
								__('IPTC data', 'wp-photo-album-plus' ),
								__('EXIF data', 'wp-photo-album-plus' ),
								__('Share box', 'wp-photo-album-plus' )
								);
							$enabled  = '<span style="color:green; float:right;"> ('.__('Enabled', 'wp-photo-album-plus').')</span>';
							$disabled = '<span style="color:orange; float:right;"> ('.__('Disabled', 'wp-photo-album-plus').')</span>';
							$descs = array(
								__('Start/Stop & Slower/Faster navigation bar', 'wp-photo-album-plus' ) . ( wppa_switch( 'show_startstop_navigation') ? $enabled : $disabled ),
								__('The Slide Frame', 'wp-photo-album-plus' ) . '<span style="float:right;">'.__('( Always )', 'wp-photo-album-plus' ).'</span>',
								__('Photo Name Box', 'wp-photo-album-plus' ) . ( wppa_switch( 'show_full_name') ? $enabled : $disabled ),
								__('Photo Description Box', 'wp-photo-album-plus' ) . ( wppa_switch( 'show_full_desc') ? $enabled : $disabled ),
								__('Custom Box', 'wp-photo-album-plus' ) . ( wppa_switch( 'custom_on') ? $enabled : $disabled ),
								__('Rating Bar', 'wp-photo-album-plus' ) . ( wppa_switch( 'rating_on') ? $enabled : $disabled ),
								__('Film Strip with embedded Start/Stop and Goto functionality', 'wp-photo-album-plus' ) . ( wppa_switch( 'filmstrip') ? $enabled : $disabled ),
								__('Browse Bar with Photo X of Y counter', 'wp-photo-album-plus' ) . ( wppa_switch( 'show_browse_navigation') ? $enabled : $disabled ),
								__('Comments Box', 'wp-photo-album-plus' ) . ( wppa_switch( 'show_comments') ? $enabled : $disabled ),
								__('IPTC box', 'wp-photo-album-plus' ) . ( wppa_switch( 'show_iptc') ? $enabled : $disabled ),
								__('EXIF box', 'wp-photo-album-plus' ) . ( wppa_switch( 'show_exif') ? $enabled : $disabled ),
								__('Social media share box', 'wp-photo-album-plus' ) . ( wppa_switch( 'share_on') ? $enabled : $disabled )
								);
							$i = '0';
							while ( $i < '12' ) {
								if ( $indexes[$i] ) {
									$name = $names[$indexes[$i]];
									$desc = $descs[$indexes[$i]];
									$html = $i == '0' ? '&nbsp;' : wppa_moveup_button( 'wppa_moveup', $i );
									$help = '';
									$slug = 'wppa_slide_order';
									wppa_setting_new($slug, is_numeric( $indexes[$i] ) ? $indexes[$i]+1 : 99 , $name, $desc, $html, $help, wppa_switch( 'split_namedesc'), 'slide_split');
								}
								$i++;
							}
				//		}
				//		else {
							$indexopt = wppa_opt( 'slide_order' );
							$indexes  = explode(',', $indexopt);
							$names    = array(
								__('StartStop', 'wp-photo-album-plus' ),
								__('SlideFrame', 'wp-photo-album-plus' ),
								__('NameDesc', 'wp-photo-album-plus' ),
								__('Custom', 'wp-photo-album-plus' ),
								__('Rating', 'wp-photo-album-plus' ),
								__('FilmStrip', 'wp-photo-album-plus' ),
								__('Browsebar', 'wp-photo-album-plus' ),
								__('Comments', 'wp-photo-album-plus' ),
								__('IPTC data', 'wp-photo-album-plus' ),
								__('EXIF data', 'wp-photo-album-plus' ),
								__('Share box', 'wp-photo-album-plus' )
								);
							$enabled  = '<span style="color:green; float:right;">( '.__('Enabled', 'wp-photo-album-plus' );
							$disabled = '<span style="color:orange; float:right;">( '.__('Disabled', 'wp-photo-album-plus' );
							$descs = array(
								__('Start/Stop & Slower/Faster navigation bar', 'wp-photo-album-plus' ) . ( wppa_switch( 'show_startstop_navigation') ? $enabled : $disabled ) . ' )</span>',
								__('The Slide Frame', 'wp-photo-album-plus' ) . '<span style="float:right;">'.__('( Always )', 'wp-photo-album-plus' ).'</span>',
								__('Photo Name & Description Box', 'wp-photo-album-plus' ) . ( ( wppa_switch( 'show_full_name') || wppa_switch( 'show_full_desc') ) ? $enabled : $disabled ) .' )</span>',
								__('Custom Box', 'wp-photo-album-plus' ) . ( wppa_switch( 'custom_on') ? $enabled : $disabled ).' )</span>',
								__('Rating Bar', 'wp-photo-album-plus' ) . ( wppa_switch( 'rating_on') ? $enabled : $disabled ).' )</span>',
								__('Film Strip with embedded Start/Stop and Goto functionality', 'wp-photo-album-plus' ) . ( wppa_switch( 'filmstrip') ? $enabled : $disabled ).' )</span>',
								__('Browse Bar with Photo X of Y counter', 'wp-photo-album-plus' ) . ( wppa_switch( 'show_browse_navigation') ? $enabled : $disabled ).' )</span>',
								__('Comments Box', 'wp-photo-album-plus' ) . ( wppa_switch( 'show_comments') ? $enabled : $disabled ).' )</span>',
								__('IPTC box', 'wp-photo-album-plus' ) . ( wppa_switch( 'show_iptc') ? $enabled : $disabled ).' )</span>',
								__('EXIF box', 'wp-photo-album-plus' ) . ( wppa_switch( 'show_exif') ? $enabled : $disabled ).' )</span>',
								__('Social media share box', 'wp-photo-album-plus' ) . ( wppa_switch( 'share_on') ? $enabled : $disabled ).' )</span>'
								);
							$i = '0';
							while ( $i < '11' ) {
								if ( $indexes[$i] ) {
									$name = $names[$indexes[$i]];
									$desc = $descs[$indexes[$i]];
									$html = $i == '0' ? '&nbsp;' : wppa_moveup_button( 'wppa_moveup', $i );
									$help = '';
									$slug = 'wppa_slide_order';
									wppa_setting_new($slug, is_numeric( $indexes[$i] ) ? $indexes[$i]+1 : 99, $name, $desc, $html, $help, ! wppa_switch( 'split_namedesc'), 'slide_unsplit');
								}
								$i++;
							}
			//			}

						$name = __('Swap Namedesc', 'wp-photo-album-plus' );
						$desc = __('Swap the sequence of name and description', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_swap_namedesc';
						$onch = "wppaUnSlaveChecked(this,'wppa_swap_namedesc');";
						$html = wppa_checkbox($slug,$onch);
						wppa_setting_new($slug, '13', $name, $desc, $html, $help, ! wppa_switch( 'split_namedesc' ) );

						$name = __('Split Name and Desc', 'wp-photo-album-plus' );
						$desc = __('Put Name and Description in separate boxes', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_split_namedesc';
						$onch = "wppaSlaveChecked(this,'slide_split');wppaUnSlaveChecked(this,'slide_unsplit');";
						$html = wppa_checkbox($slug, $onch);
						wppa_setting_new($slug, '14', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// Slideshow layout settings
					{
						$desc = $wppa_subtab_names[$tab]['3'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('V align', 'wp-photo-album-plus' );
						$desc = __('Vertical alignment of slideshow images.', 'wp-photo-album-plus' );
						$help = __('Specify the vertical alignment of slideshow images.', 'wp-photo-album-plus' );
						$slug = 'wppa_fullvalign';
						$opts = array(__('--- none ---', 'wp-photo-album-plus' ), __('top', 'wp-photo-album-plus' ), __('center', 'wp-photo-album-plus' ), __('bottom', 'wp-photo-album-plus' ), __('fit', 'wp-photo-album-plus' ));
						$vals = array('default', 'top', 'center', 'bottom', 'fit');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('V align', 'wp-photo-album-plus' );
						$desc = __('Vertical alignment of slideonly slidshow images.', 'wp-photo-album-plus' );
						$help = __('Specify the vertical alignment of slideonly slideshow images.', 'wp-photo-album-plus' );
						$slug = 'wppa_fullvalign_slideonly';
						$opts = array(__('--- none ---', 'wp-photo-album-plus' ), __('top', 'wp-photo-album-plus' ), __('center', 'wp-photo-album-plus' ), __('bottom', 'wp-photo-album-plus' ), __('fit', 'wp-photo-album-plus' ));
						$vals = array('default', 'top', 'center', 'bottom', 'fit');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('H align', 'wp-photo-album-plus' );
						$desc = __('Horizontal alignment of slideshow images.', 'wp-photo-album-plus' );
						$help = __('Specify the horizontal alignment of slideshow images. If you specify --- none --- , no horizontal alignment will take place.', 'wp-photo-album-plus' );
						$help .= '<br>'.(__('This setting is only usefull when the Column Width differs from the Maximum Width.', 'wp-photo-album-plus' ));
						$help .= '<br>'.(__('(Settings I-A1 and I-B1)', 'wp-photo-album-plus' ));
						$slug = 'wppa_fullhalign';
						$opts = array(__('left', 'wp-photo-album-plus' ), __('center', 'wp-photo-album-plus' ), __('right', 'wp-photo-album-plus' ));
						$vals = array('left', 'center', 'right');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Full desc align', 'wp-photo-album-plus' );
						$desc = __('The alignment of the descriptions under fullsize images and slideshows.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_fulldesc_align';
						$opts = array(__('Left', 'wp-photo-album-plus' ), __('Center', 'wp-photo-album-plus' ), __('Right', 'wp-photo-album-plus' ));
						$vals = array('left', 'center', 'right');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Remove redundant space', 'wp-photo-album-plus' );
						$desc = __('Removes unwanted &lt;p> and &lt;br> tags in fullsize descriptions.', 'wp-photo-album-plus' );
						$help = __('This setting has only effect when Foreign Shortcodes is checked.', 'wp-photo-album-plus' );
						$help .= '<br>'.wppa_see_also( 'miscadv', '4', '1' );
						$slug = 'wppa_clean_pbr';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Run nl2br or wpautop on description', 'wp-photo-album-plus' );
						$desc = __('Adds &lt;br> or &lt;p> and &lt;br> tags in fullsize descriptions.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_wpautop_on_desc';
						$opts = array(	__('--- none ---', 'wp-photo-album-plus' ),
										__('Linebreaks only', 'wp-photo-album-plus' ),
										__('Linebreaks and paragraphs', 'wp-photo-album-plus' ),
									);
						$vals = array('nil', 'nl2br', 'wpautop');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Auto open comments', 'wp-photo-album-plus' );
						$desc = __('Automatic opens comments box when slideshow does not run.', 'wp-photo-album-plus' );
						$help = __('Works also on type="xphoto"', 'wp-photo-album-plus' );
						$slug = 'wppa_auto_open_comments';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '7', $name, $desc, $html, $help, wppa_switch('show_comments'));

						$name = __('Slide area max size', 'wp-photo-album-plus' );
						$desc = __('The max height of the slideshow areas', 'wp-photo-album-plus' );
						$help = __('A number > 1 is pixelsize, a number < 1 is fraction of the viewport height, 0 is no limit', 'wp-photo-album-plus' );
						$slug = 'wppa_area_size_slide';
						$html = wppa_input($slug, '40px', '', __('pixels / fraction', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('Portrait only', 'wp-photo-album-plus');
						$desc = __('All slides will fill the available width', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_slide_portrait_only';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// Slideshow dynamic behaviour
					{
						$desc = $wppa_subtab_names[$tab]['4'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Start', 'wp-photo-album-plus' );
						$desc = __('Start slideshow running.', 'wp-photo-album-plus' );
						$help = __('If you select "running", the slideshow will start running immediately, if you select "still at first photo", the first photo will be displayed in browse mode.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If you select "still at first norated", the first photo that the visitor did not gave a rating will be displayed in browse mode.', 'wp-photo-album-plus' );
						$slug = 'wppa_start_slide';
						$opts = array(	__('running', 'wp-photo-album-plus' ),
										__('still at first photo', 'wp-photo-album-plus' ),
										__('still at first norated', 'wp-photo-album-plus' )
									);
						$vals = array(	'run',
										'still',
										'norate'
									);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Start slideonly', 'wp-photo-album-plus' );
						$desc = __('Start slideonly slideshow running.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_start_slideonly';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Video autostart', 'wp-photo-album-plus' );
						$desc = __('Autoplay videos in slideshows.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_start_slide_video';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help, wppa_switch('enable_video'));

						$name = __('Audio autostart', 'wp-photo-album-plus' );
						$desc = __('Autoplay audios in slideshows.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_start_slide_audio';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help, wppa_switch('enable_audio'));

						$name = __('Animation type', 'wp-photo-album-plus' );
						$desc = __('The way successive slides appear.', 'wp-photo-album-plus' );
						$help = __('Select the way the old slide is to be replaced by the new one in the slideshow/browse fullsize display.', 'wp-photo-album-plus' );
						$slug = 'wppa_animation_type';
						$opts = array(	__('Fade out and in simultaneous', 'wp-photo-album-plus' ),
										__('Fade in after fade out', 'wp-photo-album-plus' ),
										__('Shift adjacent', 'wp-photo-album-plus' ),
									//		__('Stack on', 'wp-photo-album-plus' ),
									//		__('Stack off', 'wp-photo-album-plus' ),
									//		__('Turn over', 'wp-photo-album-plus' )
									);
						$vals = array(	'fadeover',
										'fadeafter',
										'swipe',
									//		'stackon',
									//		'stackoff',
									//		'turnover'
									);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Timeout', 'wp-photo-album-plus' );
						$desc = __('Slideshow timeout.', 'wp-photo-album-plus' );
						$help = __('Select the time a single slide will be visible when the slideshow is started.', 'wp-photo-album-plus' );
						$slug = 'wppa_slideshow_timeout';
						$opts = array( '1 s.', '1.5 s.', '2.5 s.', '3 s.', '4 s.', '5 s.', '6 s.', '8 s.', '10 s.', '12 s.', '15 s.', '20 s.' );
						$vals = array('1000', '1500', '2500', '3000', '4000', '5000', '6000', '8000', '10000', '12000', '15000', '20000' );
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '5a', $name, $desc, $html, $help);

						$name = __('Speed', 'wp-photo-album-plus' );
						$desc = __('Slideshow animation speed.', 'wp-photo-album-plus' );
						$help = __('Specify the animation speed to be used in slideshows.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('This is the time it takes a photo to fade in or out.', 'wp-photo-album-plus' );
						$slug = 'wppa_animation_speed';
						$opts = array(__('--- off ---', 'wp-photo-album-plus' ), '200 ms.', '400 ms.', '800 ms.', '1.2 s.', '2 s.', '4 s.', '6 s.', '8 s.', '10 s.');
						$vals = array('10', '200', '400', '800', '1200', '2000', '4000', '6000', '8000', '10000');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Slide hover pause', 'wp-photo-album-plus' );
						$desc = __('Running Slideshow suspends during mouse hover.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_slide_pause';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('Slideshow wrap around', 'wp-photo-album-plus' );
						$desc = __('The slideshow wraps around the start and end', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_slide_wrap';
						$html = wppa_checkbox($slug) . wppa_see_also('slide', '5', '2');
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('Slide swipe', 'wp-photo-album-plus' );
						$desc = __('Enable touch events swipe left-right on slides on touch screens.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_slide_swipe';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '10', $name, $desc, $html, $help);

						$name = __('No animate on mobile', 'wp-photo-album-plus' );
						$desc = __('Suppress slideshow animations on mobile devices.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_no_animate_on_mobile';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '13', $name, $desc, $html, $help);

						$name = __('Easing formula', 'wp-photo-album-plus' );
						$desc = __('The animation method', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_easing_slide';
						$opts = array(	'swing', 'linear', 'easeInSine', 'easeOutSine', 'easeInOutSine', 'easeInQuad',
										'easeOutQuad', 'easeInOutQuad', 'easeInCubic', 'easeOutCubic', 'easeInOutCubic',
										'easeInQuart', 'easeOutQuart', 'easeInOutQuart', 'easeInQuint', 'easeOutQuint',
										'easeInOutQuint', 'easeInExpo', 'easeOutExpo', 'easeInOutExpo', 'easeInCirc',
										'easeOutCirc', 'easeInOutCirc', 'easeInBack', 'easeOutBack', 'easeInOutBack',
										'easeInElastic', 'easeOutElastic', 'easeInOutElastic', 'easeInBounce',
										'easeOutBounce', 'easeInOutBounce' );
						$vals = $opts;
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '14', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// Filmstrip setings
					{
						$desc = $wppa_subtab_names[$tab]['5'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Filmstrip', 'wp-photo-album-plus' );
						$desc = __('Show Filmstrip navigation bar.', 'wp-photo-album-plus' );
						$help = __('If checked: display the filmstrip navigation bar under the slideshow', 'wp-photo-album-plus' );
						$slug = 'wppa_filmstrip';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Film seam', 'wp-photo-album-plus' );
						$desc = __('Show seam between end and start of film.', 'wp-photo-album-plus' );
						$help = __('If checked: display the wrap-around point in the filmstrip', 'wp-photo-album-plus' );
						$slug = 'wppa_film_show_glue';
						$html = wppa_checkbox($slug) . wppa_see_also( 'slide', '4', '8' );
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Filmstrip type', 'wp-photo-album-plus');
						$desc = __('Select the type of thumbnails you want to use', 'wp-photo-album-plus');
						$help = __('Select "Classic" for the original image aspect ratios, "Equal ratio" for equally shapen clipped images', 'wp-photo-album-plus');
						$slug = 'wppa_film_type';
						$opts = [__('Classic', 'wp-photo-album-plus'), __('Equalratio', 'wp-photo-album-plus')];
						$vals = ['normal', 'canvas'];
						$onch = "wppaSlaveSelected('wppa_film_type-canvas','slide-5-4');";
						$html = wppa_select($slug, $opts, $vals, $onch);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Aspect ratio', 'wp-photo-album-plus');
						$desc = __('Select the aspect ratio to use', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_film_aspect';
						$opts = [	__( 'square', 'wp-photo-album-plus' ),
									'4:5 ' .  __( 'landscape', 'wp-photo-album-plus' ),
									'3:4 ' .  __( 'landscape', 'wp-photo-album-plus' ),
									'2:3 ' .  __( 'landscape', 'wp-photo-album-plus' ),
									'5:8 ' .  __( 'landscape', 'wp-photo-album-plus' ),
									'9:16 ' . __( 'landscape', 'wp-photo-album-plus' ),
									'1:2 ' .  __( 'landscape', 'wp-photo-album-plus' ),
									'4:5 ' .  __( 'portrait', 'wp-photo-album-plus' ),
									'3:4 ' .  __( 'portrait', 'wp-photo-album-plus' ),
									'2:3 ' .  __( 'portrait', 'wp-photo-album-plus' ),
									'5:8 ' .  __( 'portrait', 'wp-photo-album-plus' ),
									'9:16 ' . __( 'portrait', 'wp-photo-album-plus' ),
									'1:2 ' .  __( 'portrait', 'wp-photo-album-plus' ),
								];
						$vals = ['1', '1.25', '1.33333', '1.5', '1.6', '1.77777', '2', '0.8', '0.75', '0.66667', '0.625', '0.5625', '0.5', ];
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help, wppa_switch('film_type')=='canvas');

						$name = __('Filmstrip Thumbnail Size', 'wp-photo-album-plus' );
						$desc = __('The size of the filmstrip images.', 'wp-photo-album-plus' );
						$help = __('This size applies to the width or height, whichever is the largest.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Changing the thumbnail size may result in all thumbnails being regenerated. this may take a while.', 'wp-photo-album-plus' );
						$slug = 'wppa_film_thumbsize';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Display arrows', 'wp-photo-album-plus');
						$desc = __('Display navigation arrows left and right of the filmstrip', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_film_arrows';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Start/stop on Filmonly');
						$desc = __('Show the Start/Stop slideshow bar on filmonly displays.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_show_startstop_filmonly';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('Renew on Filmonly', 'wp-photo-album-plus' );
						$desc = __('Show renew link on filmonly displays.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_show_renew_filmonly';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('Film hover goto', 'wp-photo-album-plus' );
						$desc = __('Go to slide when hovering filmstrip thumbnail.', 'wp-photo-album-plus' );
						$help = __('Do not use this setting when slides have different aspect ratios!', 'wp-photo-album-plus' );
						$slug = 'wppa_film_hover_goto';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						$name = __('Filmonly continu', 'wp-photo-album-plus' );
						$desc = __('The filmstrip will move almost continously', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_filmonly_continuous';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '10', $name, $desc, $html, $help);

						$name = __('Filmonly random', 'wp-photo-album-plus' );
						$desc = __('Set sequence in filmonly to random', 'wp-photo-album-plus' );
						$help = __('Every pageload the sequence will be different', 'wp-photo-album-plus' );
						$slug = 'wppa_filmonly_random';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '11', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
				}
				break;

				case 'lightbox': {
					// Lightbox overlay configuration settings
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Theme color', 'wp-photo-album-plus' );
						$desc = __('The color of the image border and text background.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_ovl_theme';
						$opts = array(__('Black', 'wp-photo-album-plus' ), __('White', 'wp-photo-album-plus' ));
						$vals = array('black', 'white');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Theme background color', 'wp-photo-album-plus' );
						$desc = __('The color of the outer background.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_ovl_bgcolor';
						$opts = array(__('Black', 'wp-photo-album-plus' ), __('White', 'wp-photo-album-plus' ));
						$vals = array('black', 'white');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Overlay name', 'wp-photo-album-plus' );
						$desc = __('Show the items name.', 'wp-photo-album-plus' );
						$help = __('Shows the photos name on a lightbox display.', 'wp-photo-album-plus' );
						$slug = 'wppa_ovl_name';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Overlay desc', 'wp-photo-album-plus' );
						$desc = __('Show description.', 'wp-photo-album-plus' );
						$help = __('Shows the photos description on a lightbox display.', 'wp-photo-album-plus' );
						$slug = 'wppa_ovl_desc';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Overlay rating', 'wp-photo-album-plus' );
						$desc = __('Shows and enables rating on lightbox display.', 'wp-photo-album-plus' );
						$help = __('This works for 5 and 10 stars only, not for single votes or numerical display', 'wp-photo-album-plus' );
						$slug = 'wppa_ovl_rating';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Overlay add owner', 'wp-photo-album-plus' );
						$desc = __('Add the owner to the photo name on lightbox displays.', 'wp-photo-album-plus' );
						$help = __('This setting is independant of the show name switches and is a global setting.', 'wp-photo-album-plus' );
						$slug = 'wppa_ovl_add_owner';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Overlay show start/stop', 'wp-photo-album-plus' );
						$desc = __('Show Start and Stop for running slideshow on lightbox.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_ovl_show_startstop';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('Overlay show counter', 'wp-photo-album-plus' );
						$desc = __('Show the x/y counter below the image.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_ovl_show_counter';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('WPPA+ Lightbox global', 'wp-photo-album-plus' );
						$desc = __('Use the wppa+ lightbox also for non-wppa images.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_lightbox_global';
						$onch = "wppaSlaveChecked(this, 'wppa_lightbox_global_set')";
						$html = wppa_checkbox($slug, $onch);
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						$name = __('WPPA+ Lightbox global is a set', 'wp-photo-album-plus' );
						$desc = __('Treat the other images as a set.', 'wp-photo-album-plus' );
						$help = __('If checked, you can scroll through the non-WPPA images in the lightbox view.', 'wp-photo-album-plus' );
						$slug = 'wppa_lightbox_global_set';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '10', $name, $desc, $html, $help, wppa_switch( 'lightbox_global' ) );

						$name = __('Navigation icon size lightbox', 'wp-photo-album-plus' );
						$desc = __('The size of navigation icons on lightbox', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_nav_icon_size_lightbox';
						$opts = array(	'16px',
										'20px',
										'24px',
										'32px',
										'40px',
										'48px',
										);
						$vals = array(	'16',
										'20',
										'24',
										'32',
										'40',
										'48',
										);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '11', $name, $desc, $html, $help);

						$name = __('Show Share Buttons Lightbox', 'wp-photo-album-plus' );
						$desc = __('Display the share social media buttons on lightbox displays.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_share_on_lightbox';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '12', $name, $desc, $html, $help);

						$name = __('Number of text lines', 'wp-photo-album-plus' );
						$desc = __('Number of lines on the lightbox description area, exclusive the n/m line.', 'wp-photo-album-plus' );
						$help = __('Enter a number in the range from 0 to 24 or auto', 'wp-photo-album-plus' );
						$slug = 'wppa_ovl_txt_lines';
						$html = wppa_input($slug, '40px', '', __('lines', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '13', $name, $desc, $html, $help);

						$name = __('Magnifier cursor size', 'wp-photo-album-plus' );
						$desc = __('Select the size of the magnifier cursor.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_magnifier';
						$opts = array(	__('small', 'wp-photo-album-plus' ),
											__('medium', 'wp-photo-album-plus' ),
											__('large', 'wp-photo-album-plus' ),
											__('pointer (hand)', 'wp-photo-album-plus' ),
											__('--- none ---', 'wp-photo-album-plus' )
											);
						$vals  = array(	'magnifier-small.png',
											'magnifier-medium.png',
											'magnifier-large.png',
											'pointer',
											''
											);
						$onchange = 'jQuery(\'#wppa-cursor\').attr(\'alt\', jQuery(\'#magnifier\').val() );
									 document.getElementById(\'wppa-cursor\').src=wppaImageDirectory+document.getElementById(\'magnifier\').value;';
						$html = wppa_select( $slug, $opts, $vals, $onchange ) .
								'&nbsp;&nbsp;<img id="wppa-cursor" src="'.wppa_get_imgdir().wppa_opt( substr( $slug, 5 ) ).'" />';
						wppa_setting_new($slug, '14', $name, $desc, $html, $help);
						wppa_add_inline_script( 'wppa-admin', $onchange, false );

						$name = __('Border width', 'wp-photo-album-plus' );
						$desc = __('Border width for lightbox display.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_ovl_border_width';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '15', $name, $desc, $html, $help);

						$name = __('Border radius', 'wp-photo-album-plus' );
						$desc = __('Border radius for lightbox display.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_ovl_border_radius';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '16', $name, $desc, $html, $help);

						$name = __('Show Zoom in', 'wp-photo-album-plus' );
						$desc = __('Display tooltip "Zoom in" along with the magnifier cursor.', 'wp-photo-album-plus' );
						$help = __('If you select ---none--- in item 14 for magnifier size, the tooltop contains the photo name.', 'wp-photo-album-plus' );
						$slug = 'wppa_show_zoomin';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '17', $name, $desc, $html, $help);

						$name = __('Overlay opacity', 'wp-photo-album-plus' );
						$desc = __('The opacity of the lightbox overlay background.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_ovl_opacity';
						$html = wppa_input($slug, '50px', '', __('%', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '18', $name, $desc, $html, $help);

						$name = __('Click on background', 'wp-photo-album-plus' );
						$desc = __('Select the action to be taken on click on background.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_ovl_onclick';
						$opts = array(__('Nothing', 'wp-photo-album-plus' ), __('Exit (close)', 'wp-photo-album-plus' ), __('Browse (left/right)', 'wp-photo-album-plus' ));
						$vals = array('none', 'close', 'browse');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '19', $name, $desc, $html, $help);

						$name = __('Click on image', 'wp-photo-album-plus' );
						$desc = __('Clicking the image (left or right half) will browse the images', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_ovl_browse_on_click';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '20', $name, $desc, $html, $help);

						$name = __('Overlay animation speed', 'wp-photo-album-plus' );
						$desc = __('The fade-in time of the lightbox images', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_ovl_anim';
						$opts = array(__('--- off ---', 'wp-photo-album-plus' ), '200 ms.', '300 ms.', '400 ms.', '800 ms.', '1.2 s.', '2 s.', '4 s.');
						$vals = array('10', '200', '300', '400', '800', '1200', '2000', '4000');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '21', $name, $desc, $html, $help);

						$name = __('Overlay slideshow speed', 'wp-photo-album-plus' );
						$desc = __('The time the lightbox images stay', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_ovl_slide';
						$opts = array( '1 s.', '1.5 s.', '2.5 s.', '3 s.', '4 s.', '5 s.', '6 s.', '8 s.', '10 s.', '12 s.', '15 s.', '20 s.' );
						$vals = array('1000', '1500', '2500', '3000', '4000', '5000', '6000', '8000', '10000', '12000', '15000', '20000' );
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '22', $name, $desc, $html, $help);

						$name = __('Use hires files', 'wp-photo-album-plus' );
						$desc = __('Use the highest resolution available for lightbox.', 'wp-photo-album-plus' );
						$help = __('Ticking this box is recommended for lightbox fullscreen modes.', 'wp-photo-album-plus' );
						$slug = 'wppa_lb_hres';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '23', $name, $desc, $html, $help);

						$name = __('Video autostart', 'wp-photo-album-plus' );
						$desc = __('Videos on lightbox start automatically.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_ovl_video_start';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '24', $name, $desc, $html, $help);

						$name = __('Audio autostart', 'wp-photo-album-plus' );
						$desc = __('Audio on lightbox start automatically.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_ovl_audio_start';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '25', $name, $desc, $html, $help);
/*
						$name = __('Lightbox start mode', 'wp-photo-album-plus' );
						$desc = __('The mode lightbox starts in.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_ovl_mode_initial';
						$opts = array(	__('Normal', 'wp-photo-album-plus' ),
										__('Fullscreen', 'wp-photo-album-plus' ),
										);
						$vals = array( 	'normal',
										'padded',
										);
						$html = wppa_select($slug,$opts,$vals);
						wppa_setting_new($slug, '26', $name, $desc, $html, $help);

						$name = __('Lightbox start mode mobile', 'wp-photo-album-plus' );
						$desc = __('The mode lightbox starts in on mobile devices.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_ovl_mode_initial_mob';
						$opts = array(	__('Normal', 'wp-photo-album-plus' ),
										__('Fullscreen', 'wp-photo-album-plus' ),
										);
						$vals = array( 	'normal',
										'padded',
										);
						$html = wppa_select($slug,$opts,$vals);
						wppa_setting_new($slug, '27', $name, $desc, $html, $help);
*/
						$name = __('Easing formula', 'wp-photo-album-plus' );
						$desc = __('The animation method', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_easing_lightbox';
						$opts = array(	'swing', 'linear', 'easeInSine', 'easeOutSine', 'easeInOutSine', 'easeInQuad',
										'easeOutQuad', 'easeInOutQuad', 'easeInCubic', 'easeOutCubic', 'easeInOutCubic',
										'easeInQuart', 'easeOutQuart', 'easeInOutQuart', 'easeInQuint', 'easeOutQuint',
										'easeInOutQuint', 'easeInExpo', 'easeOutExpo', 'easeInOutExpo', 'easeInCirc',
										'easeOutCirc', 'easeInOutCirc', 'easeInBack', 'easeOutBack', 'easeInOutBack',
										'easeInElastic', 'easeOutElastic', 'easeInOutElastic', 'easeInBounce',
										'easeOutBounce', 'easeInOutBounce' );
						$vals = $opts;
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '28', $name, $desc, $html, $help);

						$name = __('Big Browse Buttons', 'wp-photo-album-plus');
						$desc = __('Use big browse buttons', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_ovl_big_browse';
						$onch = "wppaSlaveChecked(this,'wppa_ovl_small_browse');";
						$html = wppa_checkbox($slug, $onch);
						wppa_setting_new($slug, '29', $name, $desc, $html, $help);

						$name = __('Keep Small Buttons', 'wp-photo-album-plus');
						$desc = __('Show small browse buttons also', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_ovl_small_browse';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '30', $name, $desc, $html, $help, wppa_switch( 'ovl_big_browse' ));


						wppa_setting_box_footer_new();
					}
				}
				break;

				case 'comments': {
					// Comments system related settings
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Commenting login', 'wp-photo-album-plus' );
						$desc = __('Users must be logged in to comment on photos.', 'wp-photo-album-plus' );
						$help = '';
						$slug = '';
						$html = $security_no_disable;
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Comments view login', 'wp-photo-album-plus' );
						$desc = __('Users must be logged in to see comments on photos.', 'wp-photo-album-plus' );
						$help = __('Check this box if you want users to be logged in to be able to see existing comments on individual photos.', 'wp-photo-album-plus' );
						$slug = 'wppa_comment_view_login';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Last comment first', 'wp-photo-album-plus' );
						$desc = __('Display the newest comment on top.', 'wp-photo-album-plus' );
						$help = __('If checked: Display the newest comment on top.', 'wp-photo-album-plus' );
						$help .= '<br>'.(__('If unchecked, the comments are listed in the sequence they were entered.', 'wp-photo-album-plus' ));
						$slug = 'wppa_comments_desc';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Comment moderation', 'wp-photo-album-plus' );
						$desc = __('Comments from what users need approval.', 'wp-photo-album-plus' );
						$help = __('Select the desired users of which the comments need approval.', 'wp-photo-album-plus' );
						$slug = 'wppa_moderate_comment';
						$opts = array( 	__('All users', 'wp-photo-album-plus' ),
											__('Logged out users', 'wp-photo-album-plus' ),
											__('No users', 'wp-photo-album-plus' ),
											__('Use WP Discussion rules', 'wp-photo-album-plus' ),
											);
						$vals = array(	'all',
											'logout',
											'-none-',
											'wprules',
											);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Comment email required', 'wp-photo-album-plus' );
						$desc = __('Commenting users must enter their email addresses.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_comment_email_required';
						$opts = array( 	__('None', 'wp-photo-album-plus' ),
										__('Optional', 'wp-photo-album-plus' ),
										__('Required', 'wp-photo-album-plus' ),
										);
						$vals = array( 	'none',
										'optional',
										'required',
										);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Comment ntfy added', 'wp-photo-album-plus' );
						$desc = __('Show "Comment added" after successfull adding a comment.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_commentnotify_added';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('ComTen alt display', 'wp-photo-album-plus' );
						$desc = __('Display comments at comten thumbnails.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_comten_alt_display';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('Comten Thumbnail width', 'wp-photo-album-plus' );
						$desc = __('The width of the thumbnail in the alt comment display.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_comten_alt_thumbsize';
						$html = wppa_input($slug, '50px', '', __('Pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('Show smiley picker', 'wp-photo-album-plus' );
						$desc = __('Display a clickable row of smileys.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_comment_smiley_picker';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						$name = __('Allow clickable links', 'wp-photo-album-plus' );
						$desc = __('Make links in comments clickable', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_comment_clickable';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '10', $name, $desc, $html, $help);

						$name = __('Comment needs vote', 'wp-photo-album-plus' );
						$desc = __('User needs to give a rating to get his comment published', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_comment_need_vote';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '12', $name, $desc, $html, $help);

						$name = __('Comment Avatar default', 'wp-photo-album-plus' );
						$desc = __('Show Avatars with the comments if not --- none ---', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_comment_gravatar';
						$onch = '';
						$opts = array(	__('--- none ---', 'wp-photo-album-plus' ),
											__('mystery man', 'wp-photo-album-plus' ),
											__('identicon', 'wp-photo-album-plus' ),
											__('monsterid', 'wp-photo-album-plus' ),
											__('wavatar', 'wp-photo-album-plus' ),
											__('retro', 'wp-photo-album-plus' ),
											__('--- url ---', 'wp-photo-album-plus' )
										);
						$vals = array(	'none',
											'mm',
											'identicon',
											'monsterid',
											'wavatar',
											'retro',
											'url'
										);
						$html = wppa_select($slug, $opts, $vals, $onch);
						wppa_setting_new($slug, '13', $name, $desc, $html, $help);

						$name = __('Comment Avatar url', 'wp-photo-album-plus' );
						$desc = __('Comment Avatar default url.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_comment_gravatar_url';
						$html = wppa_input($slug, '90%', '300px');
						wppa_setting_new($slug, '14', $name, $desc, $html, $help);

						$name = __('Avatar size', 'wp-photo-album-plus' );
						$desc = __('Size of Avatar images.', 'wp-photo-album-plus' );
						$help = __('The size of the square avatar must be > 0 and < 256', 'wp-photo-album-plus' );
						$slug = 'wppa_gravatar_size';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '15', $name, $desc, $html, $help);

						$name = __('List comments', 'wp-photo-album-plus' );
						$desc = __('Show the content of the comments table.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_list_comments_by';
						$slug2 = 'wppa_list_comments';
						$opts = array( 'Email', 'Name', 'Timestamp' );
						$vals = array( 'email', 'name', 'timestamp' );
						$html1 = '<small style="float:left">'.__('Sequence method:', 'wp-photo-album-plus' ).'</small>'.wppa_select($slug1, $opts, $vals);
						$html2 = wppa_popup_button( $slug2, '30' );
						$html = $html1 . '<span style="float:left">&nbsp;</span>' .$html2;
						wppa_setting_new($slug1, '16', $name, $desc, $html, $help);

						$name = __('User comment roles', 'wp-photo-album-plus' );
						$desc = __('Optionally limit access to selected userroles', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_user_comment_roles';
						$roles = $wp_roles->roles;
						$opts = array();
						$vals = array();
						$opts[] = '-- '.__('Not limited', 'wp-photo-album-plus' ).' --';
						$vals[] = '';
						foreach (array_keys($roles) as $key) {
							$role = $roles[$key];
							$rolename = translate_user_role( $role['name'] );
							$opts[] = $rolename;
							$vals[] = $key;
						}
						$onch = '';
						$html = wppa_select_m($slug, $opts, $vals, $onch, '', false, '', '220' );
						wppa_setting_new($slug, '17', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
				}
				break;

				case 'rating': {
					// Rating system related settings
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Slideshow rating', 'wp-photo-album-plus' );
						$desc = __('Display Slideshow Rating.', 'wp-photo-album-plus' );
						$help = __('Display the rating of the photo under the slideshow image.', 'wp-photo-album-plus' );
						$slug = '';
						$html = '<input type="checkbox" checked disabled >';
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Thumbnail rating', 'wp-photo-album-plus' );
						$desc = __('Display Thumbnail Rating.', 'wp-photo-album-plus' );
						$help = __('Display the rating of the photo under the thumbnail image.', 'wp-photo-album-plus' );
						$slug = 'wppa_thumb_text_rating';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Lightbox rating', 'wp-photo-album-plus' );
						$desc = __('Shows and enables rating on lightbox.', 'wp-photo-album-plus' );
						$help = __('This works for 5 and 10 stars only, not for single votes or numerical display', 'wp-photo-album-plus' );
						$slug = 'wppa_ovl_rating';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Rating display type', 'wp-photo-album-plus' );
						$desc = __('Specify the type of the rating display.', 'wp-photo-album-plus' );
						$help = __('If you select "Likes" you must also select "One button vote"', 'wp-photo-album-plus' );
						$slug = 'wppa_rating_display_type';
						$opts = array(__('Graphic', 'wp-photo-album-plus' ), __('Numeric', 'wp-photo-album-plus' ), __('Likes', 'wp-photo-album-plus' ));
						$vals = array('graphic', 'numeric', 'likes');
						$postaction = 'setTimeout(\'document.location.reload(true)\', 2000)';
						$html = wppa_select($slug, $opts, $vals, '', '', false, $postaction);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Rating size', 'wp-photo-album-plus' );
						$desc = __('Select the number of voting stars.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_rating_max';
						$opts = array(__('Standard: 5 stars', 'wp-photo-album-plus' ), __('Extended: 10 stars', 'wp-photo-album-plus' ), __('One button vote', 'wp-photo-album-plus' ));
						$vals = array('5', '10', '1');
						$onch = "wppaSlaveSelected('wppa_rating_max-1','rating-1-26');wppaUnSlaveSelected('wppa_rating_max-1','rating_1');";
						$html = wppa_select($slug, $opts, $vals, $onch) . wppa_see_also( 'maintenance', '1', '5' );
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Display precision', 'wp-photo-album-plus' );
						$desc = __('Select the desired rating display precision.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_rating_prec';
						$opts = array('1 '.__('decimal places', 'wp-photo-album-plus' ), '2 '.__('decimal places', 'wp-photo-album-plus' ), '3 '.__('decimal places', 'wp-photo-album-plus' ), '4 '.__('decimal places', 'wp-photo-album-plus' ));
						$vals = array('1', '2', '3', '4');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Rating space', 'wp-photo-album-plus' );
						$desc = __('Space between avg and my rating stars', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_ratspacing';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('Show rating count', 'wp-photo-album-plus' );
						$desc = __('Display the number of votes along with average ratings.', 'wp-photo-album-plus' );
						$help = __('If checked, the number of votes is displayed along with average rating displays.', 'wp-photo-album-plus' );
						$slug = 'wppa_show_rating_count';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('Rating login', 'wp-photo-album-plus' );
						$desc = __('Users must login to rate photos.', 'wp-photo-album-plus' );
						$help = __('If users want to vote for a photo (rating 1..5 stars) the must login first. The avarage rating will always be displayed as long as the rating system is enabled.', 'wp-photo-album-plus' );
						$slug = '';
						$html = $security_no_disable;
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						$name = __('Rating change', 'wp-photo-album-plus' );
						$desc = __('Users may change their ratings.', 'wp-photo-album-plus' );
						$help = __('If "One button vote" is selected in item 5, this setting has no meaning', 'wp-photo-album-plus' );
						$slug = 'wppa_rating_change';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '10', $name, $desc, $html, $help, wppa_opt( 'rating_max' ) != '1', 'rating_1' );

						$name = __('Rating multi', 'wp-photo-album-plus' );
						$desc = __('Users may give multiple votes.', 'wp-photo-album-plus' );
						$help = __('Users may give multiple votes. (This has no effect when users may change their votes.)', 'wp-photo-album-plus' );
						$slug = 'wppa_rating_multi';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '11', $name, $desc, $html, $help, wppa_opt( 'rating_display_type' ) != 'likes' );

						$name = __('Rating daily', 'wp-photo-album-plus' );
						$desc = __('Users may rate only once per period', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_rating_dayly';
						$opts = array(__('--- off ---', 'wp-photo-album-plus' ), __('Week', 'wp-photo-album-plus' ), __('Day', 'wp-photo-album-plus' ), __('Hour', 'wp-photo-album-plus' ) );
						$vals = array(0, 7*24*60*60, 24*60*60, 60*60);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '12', $name, $desc, $html, $help, wppa_opt( 'rating_display_type' ) != 'likes' );

						$name = __('Rate own photos', 'wp-photo-album-plus' );
						$desc = __('It is allowed to rate photos by the uploader himself.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_allow_owner_votes';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '13', $name, $desc, $html, $help);

						$name = __('Rating requires comment', 'wp-photo-album-plus' );
						$desc = __('Users must clarify their vote in a comment.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_vote_needs_comment';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '14', $name, $desc, $html, $help, wppa_opt( 'rating_display_type' ) != 'likes' );

						$name = __('Next after vote', 'wp-photo-album-plus' );
						$desc = __('Goto next slide after voting', 'wp-photo-album-plus' );
						$help = __('If checked, the visitor goes straight to the slide following the slide he voted. This will speed up mass voting.', 'wp-photo-album-plus' );
						$slug = 'wppa_next_on_callback';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '15', $name, $desc, $html, $help);

						$name = __('Star off opacity', 'wp-photo-album-plus' );
						$desc = __('Rating star off state opacity value.', 'wp-photo-album-plus' );
						$help = __('Enter percentage of opacity. 100% is opaque, 0% is transparant', 'wp-photo-album-plus' );
						$help .= '<br>'.(__('If "One button vote" is selected in item 5, this setting has no meaning', 'wp-photo-album-plus' ));
						$slug = 'wppa_star_opacity';
						$html = wppa_input($slug, '50px', '', __('%', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '16', $name, $desc, $html, $help, wppa_opt( 'rating_max' ) != '1', 'rating_1' );

						$name = __('Notify inappropriate', 'wp-photo-album-plus' );
						$desc = __('Notify admin every x times.', 'wp-photo-album-plus' );
						$help = __('If this number is positive, there will be a thumb down icon in the rating bar.', 'wp-photo-album-plus' );
						$help .= '<br>'.(__('Clicking the thumbdown icon indicates a user dislikes a photo.', 'wp-photo-album-plus' ));
						$help .= '<br>'.(__('Admin will be notified by email after every x dislikes.', 'wp-photo-album-plus' ));
						$help .= '<br>'.(__('A value of 0 disables this feature.', 'wp-photo-album-plus' ));
						$help .= '<br>'.(__('If "One button vote" is selected in item 5, this setting has no meaning', 'wp-photo-album-plus' ));
						$slug = 'wppa_dislike_mail_every';
						$html = wppa_input($slug, '40px', '', __('reports', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '17', $name, $desc, $html, $help, wppa_opt( 'rating_max' ) != '1', 'rating_1' );

						$name = __('Dislike value', 'wp-photo-album-plus' );
						$desc = __('This value counts dislike rating.', 'wp-photo-album-plus' );
						$help = __('This value will be used for a dislike rating on calculation of avarage ratings.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If "One button vote" is selected in item 5, this setting has no meaning', 'wp-photo-album-plus' );
						$slug = 'wppa_dislike_value';
						$html = wppa_input($slug, '50px', '', __('points', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '18', $name, $desc, $html, $help, wppa_opt( 'rating_max' ) != '1', 'rating_1' );

						$name = __('Pending after', 'wp-photo-album-plus' );
						$desc = __('Set status to pending after xx dislike votes.', 'wp-photo-album-plus' );
						$help = __('A value of 0 disables this feature.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If "One button vote" is selected in item 5, this setting has no meaning', 'wp-photo-album-plus' );
						$slug = 'wppa_dislike_set_pending';
						$html = wppa_input($slug, '40px', '', __('reports', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '19', $name, $desc, $html, $help, wppa_opt( 'rating_max' ) != '1', 'rating_1' );

						$name = __('Delete after', 'wp-photo-album-plus' );
						$desc = __('Delete photo after xx dislike votes.', 'wp-photo-album-plus' );
						$help = __('A value of 0 disables this feature.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If "One button vote" is selected in item 5, this setting has no meaning', 'wp-photo-album-plus' );
						$slug = 'wppa_dislike_delete';
						$html = wppa_input($slug, '40px', '', __('reports', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '20', $name, $desc, $html, $help, wppa_opt( 'rating_max' ) != '1', 'rating_1' );

						$name = __('Show dislike count', 'wp-photo-album-plus' );
						$desc = __('Show the number of dislikes in the rating bar.', 'wp-photo-album-plus' );
						$help = __('Displayes the total number of dislike votes for the current photo.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If "One button vote" is selected in item 5, this setting has no meaning', 'wp-photo-album-plus' );
						$slug = 'wppa_dislike_show_count';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '21', $name, $desc, $html, $help, wppa_opt( 'rating_max' ) != '1', 'rating_1' );

						$name = __('Show average rating', 'wp-photo-album-plus' );
						$desc = __('Display the avarage rating and/or vote count on the rating bar', 'wp-photo-album-plus' );
						$help = __('If checked, the average rating as well as the current users rating is displayed in max 5 or 10 stars.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If unchecked, only the current users rating is displayed (if any).', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If "One button vote" is selected in item 5, this box checked will display the vote count.', 'wp-photo-album-plus' );
						$slug = 'wppa_show_avg_rating';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '22', $name, $desc, $html, $help);

						$name = __('Avg and Mine on 2 lines', 'wp-photo-album-plus' );
						$desc = __('Display avarage and my rating on different lines', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_show_avg_mine_2';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '23', $name, $desc, $html, $help);

						$name = __('Single vote button text', 'wp-photo-album-plus' );
						$desc = __('The text on the voting button.', 'wp-photo-album-plus' );
						$help = __('This text may contain qTranslate compatible language tags.', 'wp-photo-album-plus' );
						$slug = 'wppa_vote_button_text';
						$html = wppa_input($slug, '100');
						wppa_setting_new($slug, '24', $name, $desc, $html, $help);

						$name = __('Single vote button text voted', 'wp-photo-album-plus' );
						$desc = __('The text on the voting button when voted.', 'wp-photo-album-plus' );
						$help = __('This text may contain qTranslate compatible language tags.', 'wp-photo-album-plus' );
						$slug = 'wppa_voted_button_text';
						$html = wppa_input($slug, '100');
						wppa_setting_new($slug, '25', $name, $desc, $html, $help);

						$name = __('Single vote button thumbnail', 'wp-photo-album-plus' );
						$desc = __('Display single vote button below thumbnails.', 'wp-photo-album-plus' );
						$help = __('This works only in single vote mode: item 5 set to "one button vote"', 'wp-photo-album-plus' );
						$slug = 'wppa_vote_thumb';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '26', $name, $desc, $html, $help, wppa_opt( 'rating_max' ) == '1' );

						$name = __('Medal bronze when', 'wp-photo-album-plus' );
						$desc = __('Photo gets medal bronze when number of top-scores ( 5 or 10 ).', 'wp-photo-album-plus' );
						$help = __('When the photo has this number of topscores ( 5 or 10 stars ), it will get a medal. A value of 0 indicates that you do not want this feature.', 'wp-photo-album-plus' );
						$slug = 'wppa_medal_bronze_when';
						$html = wppa_input($slug, '50px', '', __('Topscores', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '27', $name, $desc, $html, $help);

						$name = __('Medal silver when', 'wp-photo-album-plus' );
						$desc = __('Photo gets medal silver when number of top-scores ( 5 or 10 ).', 'wp-photo-album-plus' );
						$help = (__('When the photo has this number of topscores ( 5 or 10 stars ), it will get a medal. A value of 0 indicates that you do not want this feature.', 'wp-photo-album-plus' ));
						$slug = 'wppa_medal_silver_when';
						$html = wppa_input($slug, '50px', '', __('Topscores', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '28', $name, $desc, $html, $help);

						$name = __('Medal gold when', 'wp-photo-album-plus' );
						$desc = __('Photo gets medal gold when number of top-scores ( 5 or 10 ).', 'wp-photo-album-plus' );
						$help = (__('When the photo has this number of topscores ( 5 or 10 stars ), it will get a medal. A value of 0 indicates that you do not want this feature.', 'wp-photo-album-plus' ));
						$slug = 'wppa_medal_gold_when';
						$html = wppa_input($slug, '50px', '', __('Topscores', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '29', $name, $desc, $html, $help);

						$name = __('Medal tag color', 'wp-photo-album-plus' );
						$desc = __('The color of the tag on the medal.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_medal_color';
						$opts = array( __('Red', 'wp-photo-album-plus' ), __('Green', 'wp-photo-album-plus' ), __('Blue', 'wp-photo-album-plus' ) );
						$vals = array( '1', '2', '3' );
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '30', $name, $desc, $html, $help);

						$name = __('Medal position', 'wp-photo-album-plus' );
						$desc = __('The position of the medal on the image.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_medal_position';
						$opts = array( __('Top left', 'wp-photo-album-plus' ), __('Top right', 'wp-photo-album-plus' ), __('Bottom left', 'wp-photo-album-plus' ), __('Bottom right', 'wp-photo-album-plus' ) );
						$vals = array( 'topleft', 'topright', 'botleft', 'botright' );
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '31', $name, $desc, $html, $help);

						$name = __('Top criterium', 'wp-photo-album-plus' );
						$desc = __('The top sort item used for topten results from shortcodes.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_topten_sortby';
						$opts = array( __('Mean rating', 'wp-photo-album-plus' ),
									   __('Rating count', 'wp-photo-album-plus' ),
									   __('Viewcount', 'wp-photo-album-plus' ),
									   __('Downloads', 'wp-photo-album-plus' ));
						$vals = array( 'mean_rating', 'rating_count', 'views', 'dlcount' );
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '32', $name, $desc, $html, $help);

						$name = __('Contest top criterium', 'wp-photo-album-plus' );
						$desc = __('The top sort criterium used for the contest.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_contest_sortby';
						$opts = array( 	__('Mean rating', 'wp-photo-album-plus' ),
										__('Total rating', 'wp-photo-album-plus' ));
						$vals = array( 'average', 'total' );
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '33', $name, $desc, $html, $help);

						$name = __('Contest photo numbering', 'wp-photo-album-plus' );
						$desc = __('The number to be placed near the preview.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_contest_number';
						$opts = array(	__('--- none ---', 'wp-photo-album-plus' ),
										__('Photo id', 'wp-photo-album-plus' ),
										__('Sequence number', 'wp-photo-album-plus' ));
						$vals = array( 'none', 'id', 'seqno' );
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '34', $name, $desc, $html, $help);

						$name = __('Contest max ranking', 'wp-photo-album-plus' );
						$desc = __('The number of photos in the contest results display.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_contest_max';
						$html = wppa_number($slug, '3', '100');
						wppa_setting_new($slug, '35', $name, $desc, $html, $help);

						$name = __('Contest comment visibility', 'wp-photo-album-plus' );
						$desc = __('The policy to display comments on the contest display', 'wp-photo-album-plus' );
						$help = __('Admin always sees the comments', 'wp-photo-album-plus' );
						$slug = 'wppa_contest_comment_policy';
						$opts = array( 	__('None', 'wp-photo-album-plus' ),
										__('Commenter', 'wp-photo-album-plus' ),
										__('Commenter and photo owner', 'wp-photo-album-plus' ),
										__('Every visitor', 'wp-photo-album-plus' ));
						$vals = array( 'none', 'comowner', 'owners', 'all' );
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '36', $name, $desc, $html, $help);

						$name = __('List Ratings', 'wp-photo-album-plus' );
						$desc = __('Show the most recent ratings.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_list_rating';
						$html = wppa_popup_button( $slug, '30' );
						wppa_setting_new($slug, '37', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
				}
				break;

				case 'search': {
					// Search albums and photos features related settings
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Search landing page', 'wp-photo-album-plus' );
						$desc = __('Display the search results on page.', 'wp-photo-album-plus' );
						$help = __('Select the page to be used to display search results. The page MUST contain [wppa].', 'wp-photo-album-plus' );
						$help .= '<br>'.__('You may give it the title "Search results" or something alike.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Or you may use the standard page on which you display the generic album.', 'wp-photo-album-plus' );
						$slug = 'wppa_search_linkpage';
						wppa_verify_page($slug);
						$pages = $wpdb->get_results( "SELECT ID, post_title, post_content FROM $wpdb->posts
													  WHERE post_type = 'page'
													  AND post_status = 'publish'
													  ORDER BY post_title", ARRAY_A );
						$opts = array();
						$vals = array();
						$opts[] = __('--- Please select a page ---', 'wp-photo-album-plus' );
						$vals[] = '0';
						if ($pages) {

							// Translate
							foreach ( array_keys($pages) as $index ) {
								$pages[$index]['post_title'] = __(stripslashes($pages[$index]['post_title']), 'wp-photo-album-plus' );
							}
							$pages = wppa_array_sort($pages, 'post_title');
							foreach ($pages as $page) {
								if ( strpos($page['post_content'], '%%wppa%%') !== false || strpos($page['post_content'], '[wppa') !== false ) {
									$opts[] = __($page['post_title'], 'wp-photo-album-plus' );
									$vals[] = $page['ID'];
								}
								else {
									$opts[] = '|'.__($page['post_title'], 'wp-photo-album-plus' ).'|';
									$vals[] = $page['ID'];
								}
							}
						}
						$html1 = wppa_select($slug, $opts, $vals, '', '', true);

						$slug2 = 'wppa_search_oc';
						$opts2 = array('1','2','3','4','5');
						$vals2 = array('1','2','3','4','5');
						$html2 = '<div style="float:right"><div style="font-size:9px;float:left;" class="" >'.__('Occur', 'wp-photo-album-plus' ).'</div>'.wppa_select($slug2, $opts2, $vals2).'</div>';

						$html = $html1 . $html2;
						wppa_setting_new(false, '1', $name, $desc, $html, $help);

						$name = __('Exclude separate', 'wp-photo-album-plus' );
						$desc = __('Do not search \'separate\' albums.', 'wp-photo-album-plus' );
						$help = __('When checked, albums (and photos in them) that have the parent set to --- separate --- will be excluded from being searched.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Except when you start searching in a \'saparate\' album, with the "search in current section" box ticked.', 'wp-photo-album-plus' );
						$slug = 'wppa_excl_sep';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Include description', 'wp-photo-album-plus' );
						$desc = __('Do also search the album and photo description.', 'wp-photo-album-plus' );
						$help = __('When checked, the description of the photo will also be searched.', 'wp-photo-album-plus' );
						$slug = 'wppa_search_desc';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Include tags', 'wp-photo-album-plus' );
						$desc = __('Do also search the photo tags.', 'wp-photo-album-plus' );
						$help = __('When checked, the tags of the photo will also be searched.', 'wp-photo-album-plus' );
						$slug = 'wppa_search_tags';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Include categories', 'wp-photo-album-plus' );
						$desc = __('Do also search the album categories.', 'wp-photo-album-plus' );
						$help = __('When checked, the categories of the album will also be searched.', 'wp-photo-album-plus' );
						$slug = 'wppa_search_cats';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Include comments', 'wp-photo-album-plus' );
						$desc = __('Do also search the comments on photos.', 'wp-photo-album-plus' );
						$help = __('When checked, the comments of the photos will also be searched.', 'wp-photo-album-plus' );
						$slug = 'wppa_search_comments' ;
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Photos only', 'wp-photo-album-plus' );
						$desc = __('Search for photos only.', 'wp-photo-album-plus' );
						$help = __('When checked, only photos will be searched for.', 'wp-photo-album-plus' );
						$slug = 'wppa_photos_only';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('Max albums found', 'wp-photo-album-plus' );
						$desc = __('The maximum number of albums to be displayed.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_max_search_albums';
						$html = wppa_input($slug, '50px');
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('Max photos found', 'wp-photo-album-plus' );
						$desc = __('The maximum number of photos to be displayed.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_max_search_photos';
						$html = wppa_input($slug, '50px');
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						$name = __('Tags OR only', 'wp-photo-album-plus' );
						$desc = __('No and / or buttons', 'wp-photo-album-plus' );
						$help = __('Hide the and/or radiobuttons and do the or method in the multitag widget and shortcode.', 'wp-photo-album-plus' );
						$slug = 'wppa_tags_or_only';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '10', $name, $desc, $html, $help);

						$name = __('Tags add Inverse', 'wp-photo-album-plus' );
						$desc = __('Add a checkbox to invert the selection.', 'wp-photo-album-plus' );
						$help = __('Adds an Invert (NOT) checkbox on the multitag widget and shortcode.', 'wp-photo-album-plus' );
						$slug = 'wppa_tags_not_on';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '11', $name, $desc, $html, $help);

						$name = __('Floating searchtoken', 'wp-photo-album-plus' );
						$desc = __('A match need not start at the first char.', 'wp-photo-album-plus' );
						$help = __('A match is found while searching also when the entered token is somewhere in the middle of a word.', 'wp-photo-album-plus' );
						$slug = 'wppa_wild_front';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '12', $name, $desc, $html, $help);

						$name = __('Search results display', 'wp-photo-album-plus' );
						$desc = __('Select the way the search results should be displayed.', 'wp-photo-album-plus' );
						$help = __('If you select anything different from "Albums and thumbnails", "Photos only" is assumed (item 6).', 'wp-photo-album-plus' );
						$slug = 'wppa_search_display_type';
						$opts = array( 	__('Albums and thumbnails', 'wp-photo-album-plus' ),
										__('Slideshow', 'wp-photo-album-plus' ),
										__('Slideonly slideshow', 'wp-photo-album-plus' ),
										__('Albums only', 'wp-photo-album-plus' )
										);
						$vals = array( 'content', 'slide', 'slideonly', 'albums' );
						$html = wppa_select( $slug, $opts, $vals);
						wppa_setting_new($slug, '13', $name, $desc, $html, $help);

						$name = __('Name max length', 'wp-photo-album-plus' );
						$desc = __('Max length of displayed photonames in supersearch selectionlist', 'wp-photo-album-plus' );
						$help = __('To limit the length of the selectionlist, enter the number of characters to show.', 'wp-photo-album-plus' );
						$slug = 'wppa_ss_name_max';
						$html = $html = wppa_input($slug, '50px');
						wppa_setting_new($slug, '14', $name, $desc, $html, $help);

						$name = __('Text max length', 'wp-photo-album-plus' );
						$desc = __('Max length of displayed photo text in supersearch selectionlist', 'wp-photo-album-plus' );
						$help = __('To limit the length of the selectionlist, enter the number of characters to show.', 'wp-photo-album-plus' );
						$slug = 'wppa_ss_text_max';
						$html = $html = wppa_input($slug, '50px');
						wppa_setting_new($slug, '15', $name, $desc, $html, $help);

						$name = __('Search toptext', 'wp-photo-album-plus' );
						$desc = __('The text at the top of the search box.', 'wp-photo-album-plus' );
						$help = __('This is the equivalence of the text you can enter in the widget activation screen to show above the input box, but now for the search shortcode display.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('May contain unfiltered HTML.', 'wp-photo-album-plus' );
						$slug = 'wppa_search_toptext';
						$html = wppa_textarea($slug, $name);
						wppa_setting_new($slug, '16', $name, $desc, $html, $help);

						$name = __('Section search text', 'wp-photo-album-plus' );
						$desc = __('The labeltext at the checkbox for the \'Search in current section\' checkbox.', 'wp-photo-album-plus' );
						$help = ' ';
						$slug = 'wppa_search_in_section';
						$html = wppa_input($slug, '300px;');
						wppa_setting_new($slug, '17', $name, $desc, $html, $help);

						$name = __('Results search text', 'wp-photo-album-plus' );
						$desc = __('The labeltext at the checkbox for the \'Search in current results\' checkbox.', 'wp-photo-album-plus' );
						$help = ' ';
						$slug = 'wppa_search_in_results';
						$html = wppa_input($slug, '300px;');
						wppa_setting_new($slug, '18', $name, $desc, $html, $help);

						$name = __('Minimum search token length', 'wp-photo-album-plus' );
						$desc = __('The minmum number of chars in a search request.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_search_min_length';
						$html = wppa_number($slug, '1', '6');
						wppa_setting_new($slug, '19', $name, $desc, $html, $help);

						$name = __('Exclude from search', 'wp-photo-album-plus' );
						$desc = __('Exclude these words from search index.', 'wp-photo-album-plus' );
						$help = __('Enter words separated by commas (,)', 'wp-photo-album-plus' );
						$slug = 'wppa_search_user_void';
						$html = wppa_input($slug, '60%;');
						wppa_setting_new($slug, '20', $name, $desc, $html, $help);

						$name = __('Exclude numbers', 'wp-photo-album-plus' );
						$desc = __('Exclude numbers from search index.', 'wp-photo-album-plus' );
						$help = __('If ticked, photos and albums are not searchable by numbers.', 'wp-photo-album-plus' );
						$slug = 'wppa_search_numbers_void';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '21', $name, $desc, $html, $help);

						$name = __('Ignore slash', 'wp-photo-album-plus' );
						$desc = __('Ignore slash chracter (/).', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_index_ignore_slash';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '22', $name, $desc, $html, $help);

						$name = __('Search category box', 'wp-photo-album-plus' );
						$desc = __('Add a category selection box', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_search_catbox';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '23', $name, $desc, $html, $help);

						$name = __('Search selection boxes', 'wp-photo-album-plus' );
						$desc = __('Enter number of search selection boxes.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_search_selboxes';
						$opts = array( '0', '1', '2', '3' );
						$vals = $opts;
						$html = wppa_select( $slug, $opts, $vals );
						wppa_setting_new($slug, '24', $name, $desc, $html, $help);

						$name = sprintf(__('Box %s caption', 'wp-photo-album-plus' ), '1');
						$desc = __('Enter caption text', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_search_caption_0';
						$html = wppa_input($slug, '150px;');
						wppa_setting_new($slug, '25', $name, $desc, $html, $help);

						$name = sprintf(__('Box %s content', 'wp-photo-album-plus' ), '1');
						$desc = __('Enter search tokens, one per line.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_search_selbox_0';
						$html = wppa_textarea($slug);
						wppa_setting_new($slug, '26', $name, $desc, $html, $help);

						$name = sprintf(__('Box %s caption', 'wp-photo-album-plus' ), '2');
						$desc = __('Enter caption text', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_search_caption_1';
						$html = wppa_input($slug, '150px;');
						wppa_setting_new($slug, '27', $name, $desc, $html, $help);

						$name = sprintf(__('Box %s content', 'wp-photo-album-plus' ), '2');
						$desc = __('Enter search tokens, one per line.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_search_selbox_1';
						$html = wppa_textarea($slug);
						wppa_setting_new($slug, '28', $name, $desc, $html, $help);

						$name = sprintf(__('Box %s caption', 'wp-photo-album-plus' ), '3');
						$desc = __('Enter caption text', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_search_caption_2';
						$html = wppa_input($slug, '150px;');
						wppa_setting_new($slug, '29', $name, $desc, $html, $help);

						$name = sprintf(__('Box %s content', 'wp-photo-album-plus' ), '3');
						$desc = __('Enter search tokens, one per line.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_search_selbox_2';
						$html = wppa_textarea($slug);
						wppa_setting_new($slug, '30', $name, $desc, $html, $help);

						$name = __('Extended duplicate removal', 'wp-photo-album-plus' );
						$desc = __('Remove found items from search when name, description and image are identical', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_extended_duplicate_remove';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '31', $name, $desc, $html, $help);

						$name = __('Search field placeholder', 'wp-photo-album-plus' );
						$desc = __('The text of the placeholder', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_search_placeholder';
						$html = wppa_input($slug, '150px;');
						wppa_setting_new($slug, '32', $name, $desc, $html, $help);

						$name = __('Search form method', 'wp-photo-album-plus' );
						$desc = __('Either "get" or "post"', 'wp-photo-album-plus' );
						$help = __('May be set to "get" to avoid conflicts with other plugins or certain php versions', 'wp-photo-album-plus' );
						$slug = 'wppa_search_form_method';
						$html = wppa_select($slug, array('post', 'get'), array('post', 'get'));
						wppa_setting_new($slug, '33', $name, $desc, $html, $help);

						$name = __('Use wppa search form', 'wp-photo-album-plus' );
						$desc = __('Uses wppa specific form', 'wp-photo-album-plus' );
						$help = __('You may need to enable this when other (search) plugins break the wppa search mechanism');
						$slug = 'wppa_use_wppa_search_form';
						$onch = '';
						$html = wppa_checkbox($slug, $onch);
						wppa_setting_new($slug, '34', $name, $desc, $html, $help);

						$name = __('List Index', 'wp-photo-album-plus' );
						$desc = __('Show the content of the index table.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_list_index_display_start';
						$slug2 = 'wppa_list_index';
						$html1 = '<small style="float:left">'.__('Start at text:', 'wp-photo-album-plus' ).'&nbsp;</small>'.wppa_input( $slug1, '150px' );
						$html2 = wppa_popup_button( $slug2, '30' );
						$html = $html1 . '<span style="float:left">&nbsp;</span>' . $html2;
						wppa_setting_new(false, '35', $name, $desc, $html, $help);

						$name = __('Show empty search', 'wp-photo-album-plus' );
						$desc = __('Display empty search message', 'wp-photo-album-plus' );
						$help = __('Display a message when a search operation has an empty result', 'wp-photo-album-plus' );
						$slug = 'wppa_show_empty_search';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '36', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
				}
				break;

				case 'widget': {
					// General widget size settings
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Widget width', 'wp-photo-album-plus' );
						$desc = __('The useable width within widgets.', 'wp-photo-album-plus' );
						$help = __('Widget width for photo of the day, general purpose (default), slideshow (default) and upload widgets.', 'wp-photo-album-plus' );
						$slug = 'wppa_widget_width';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('TopTen count', 'wp-photo-album-plus' );
						$desc = __('Number of photos in TopTen widget.', 'wp-photo-album-plus' );
						$help = __('Enter the maximum number of rated photos in the TopTen widget.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_topten_count';
						$html1 = wppa_input($slug1, '40px', '', __('photos', 'wp-photo-album-plus' ));
						$slug2 = 'wppa_topten_non_zero';
						$html2 = wppa_checkbox($slug2).__('Non zero only', 'wp-photo-album-plus' );
						wppa_setting_new($slug1, '2', $name, $desc, $html1.$html2, $help);

						$name = __('TopTen size', 'wp-photo-album-plus' );
						$desc = __('Size of thumbnails in TopTen widget.', 'wp-photo-album-plus' );
						$help = __('Enter the size for the mini photos in the TopTen widget.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('The size applies to the width or height, whatever is the largest.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Recommended values: 86 for a two column and 56 for a three column display.', 'wp-photo-album-plus' );
						$slug = 'wppa_topten_size';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Comment count', 'wp-photo-album-plus' );
						$desc = __('Number of entries in Comment widget.', 'wp-photo-album-plus' );
						$help = __('Enter the maximum number of entries in the Comment widget.', 'wp-photo-album-plus' );
						$slug = 'wppa_comten_count';
						$html = wppa_input($slug, '40px', '', __('entries', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Comment size', 'wp-photo-album-plus' );
						$desc = __('Size of thumbnails in Comment widget.', 'wp-photo-album-plus' );
						$help = __('Enter the size for the mini photos in the Comment widget.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('The size applies to the width or height, whatever is the largest.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Recommended values: 86 for a two column and 56 for a three column display.', 'wp-photo-album-plus' );
						$slug = 'wppa_comten_size';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Thumbnail count', 'wp-photo-album-plus' );
						$desc = __('Number of photos in Thumbnail widget.', 'wp-photo-album-plus' );
						$help = __('Enter the maximum number of rated photos in the Thumbnail widget.', 'wp-photo-album-plus' );
						$slug = 'wppa_thumbnail_widget_count';
						$html = wppa_input($slug, '40px', '', __('photos', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Thumbnail widget size', 'wp-photo-album-plus' );
						$desc = __('Size of thumbnails in Thumbnail widget.', 'wp-photo-album-plus' );
						$help = __('Enter the size for the mini photos in the Thumbnail widget.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('The size applies to the width or height, whatever is the largest.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Recommended values: 86 for a two column and 56 for a three column display.', 'wp-photo-album-plus' );
						$slug = 'wppa_thumbnail_widget_size';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('LasTen count', 'wp-photo-album-plus' );
						$desc = __('Number of photos in Last Ten widget.', 'wp-photo-album-plus' );
						$help = __('Enter the maximum number of photos in the LasTen widget.', 'wp-photo-album-plus' );
						$slug = 'wppa_lasten_count';
						$html = wppa_input($slug, '40px', '', __('photos', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('LasTen size', 'wp-photo-album-plus' );
						$desc = __('Size of thumbnails in Last Ten widget.', 'wp-photo-album-plus' );
						$help = __('Enter the size for the mini photos in the LasTen widget.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('The size applies to the width or height, whatever is the largest.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Recommended values: 86 for a two column and 56 for a three column display.', 'wp-photo-album-plus' );
						$slug = 'wppa_lasten_size';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						$name = __('Album widget count', 'wp-photo-album-plus' );
						$desc = __('Number of albums in Album widget.', 'wp-photo-album-plus' );
						$help = __('Enter the maximum number of thumbnail photos of albums in the Album widget.', 'wp-photo-album-plus' );
						$slug = 'wppa_album_widget_count';
						$html = wppa_input($slug, '40px', '', __('albums', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '10', $name, $desc, $html, $help);

						$name = __('Album widget size', 'wp-photo-album-plus' );
						$desc = __('Size of thumbnails in Album widget.', 'wp-photo-album-plus' );
						$help = __('Enter the size for the mini photos in the Album widget.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('The size applies to the width or height, whatever is the largest.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Recommended values: 86 for a two column and 56 for a three column display.', 'wp-photo-album-plus' );
						$slug = 'wppa_album_widget_size';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '11', $name, $desc, $html, $help);

						$name = __('FeaTen count', 'wp-photo-album-plus' );
						$desc = __('Number of photos in Featured Ten widget.', 'wp-photo-album-plus' );
						$help = __('Enter the maximum number of photos in the FeaTen widget.', 'wp-photo-album-plus' );
						$slug = 'wppa_featen_count';
						$html = wppa_input($slug, '40px', '', __('photos', 'wp-photo-album-plus' ));
						$clas = '';
						$tags = 'count,widget';
						wppa_setting_new($slug, '12', $name, $desc, $html, $help);

						$name = __('FeaTen size', 'wp-photo-album-plus' );
						$desc = __('Size of thumbnails in Featured Ten widget.', 'wp-photo-album-plus' );
						$help = __('Enter the size for the mini photos in the FeaTen widget.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('The size applies to the width or height, whatever is the largest.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Recommended values: 86 for a two column and 56 for a three column display.', 'wp-photo-album-plus' );
						$slug = 'wppa_featen_size';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '13', $name, $desc, $html, $help);

						$name = __('Tagcloud min size', 'wp-photo-album-plus' );
						$desc = __('Minimal fontsize in tagclouds', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_tagcloud_min';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '14', $name, $desc, $html, $help);

						$name = __('Tagcloud max size', 'wp-photo-album-plus' );
						$desc = __('Maximal fontsize in tagclouds', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_tagcloud_max';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '15', $name, $desc, $html, $help);

						$name = __('Tagcloud character sizing', 'wp-photo-album-plus' );
						$desc = __('Formula to decide fontsizes', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_tagcloud_formula';
						$opts = array('linear', 'quadratic', 'cubic');
						$vals = array('linear', 'quadratic', 'cubic');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '16', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// Visibility settings
					{
						$desc = $wppa_subtab_names[$tab]['2'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Big Browse Buttons in widget', 'wp-photo-album-plus' );
						$desc = __('Enable invisible browsing buttons in widget slideshows.', 'wp-photo-album-plus' );
						$help = __('If checked, the fullsize image is covered by two invisible areas that act as browse buttons.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Make sure the Maximum height is properly configured to prevent these areas to overlap unwanted space.', 'wp-photo-album-plus' );
						$help .= '<br>'.wppa_see_also( 'slide', '1', '2' );
						$slug = 'wppa_show_bbb_widget';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Ugly Browse Buttons in widget', 'wp-photo-album-plus' );
						$desc = __('Enable ugly browsing buttons in widget slideshows.', 'wp-photo-album-plus' );
						$help = __('If checked, the fullsize image is covered by browse buttons.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Make sure the Maximum height is properly configured to prevent these areas to overlap unwanted space.', 'wp-photo-album-plus' );
						$help .= '<br>'.wppa_see_also( 'slide', '1', '2' );
						$slug = 'wppa_show_ubb_widget';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Album widget tooltip', 'wp-photo-album-plus' );
						$desc = __('Show the album description on hoovering thumbnail in album widget', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_show_albwidget_tooltip';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// QR Code widget settings
					{
						$desc = $wppa_subtab_names[$tab]['3'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('QR Code widget size', 'wp-photo-album-plus' );
						$desc = __('The size of the QR code display.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_qr_size';
						$html = wppa_input($slug, '50px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('QR color', 'wp-photo-album-plus' );
						$desc = __('The display color of the qr code (dark)', 'wp-photo-album-plus' );
						$help = __('This color MUST be given in hexadecimal format!', 'wp-photo-album-plus' );
						$slug = 'wppa_qr_color';
						$html = wppa_input($slug, '100px', '', '', "checkColor('".$slug."')") . wppa_color_box($slug);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('QR background color', 'wp-photo-album-plus' );
						$desc = __('The background color of the qr code (light)', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_qr_bgcolor';
						$html = wppa_input($slug, '100px', '', '', "checkColor('".$slug."')") . wppa_color_box($slug);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('QR cache', 'wp-photo-album-plus' );
						$desc = __('Enable caching QR codes', 'wp-photo-album-plus' ) . ' ' . sprintf( __('So far %d cache hits, %d miss with current settings', 'wp-photo-album-plus' ), wppa_get_option('wppa_qr_cache_hits', '0'), wppa_get_option('wppa_qr_cache_miss', '0'));
						$help = __('Enable this to avoid DoS on heavy loads on the qrserver', 'wp-photo-album-plus' );
						$slug = 'wppa_qr_cache';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('QR cache max files', 'wp-photo-album-plus');
						$desc = __('Limit the max number of cache files', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_qr_max';
						$opts = [__('Unlimited', 'wp-photo-album-plus'),10,20,50,100,200,500];
						$vals = [0,10,20,50,100,200,500];
						$html = wppa_select($slug, $opts, $vals, '');
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
				}
				break;

				case 'links': {
					// System Links configuration
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Photo names in urls', 'wp-photo-album-plus' );
						$desc = __('Display photo names in urls.', 'wp-photo-album-plus' );
						$help = '';
						$slug = '';
						$html = $security_no_enable;
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Album names in urls', 'wp-photo-album-plus' );
						$desc = __('Display album names in urls.', 'wp-photo-album-plus' );
						$help = '';
						$slug = '';
						$html = $security_no_enable;
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Use short query args', 'wp-photo-album-plus' );
						$desc = __('Use &album=... &photo=...', 'wp-photo-album-plus' );
						$help = __('Urls to wppa+ displays will contain &album=... &photo=... instead of &wppa-album=... &wppa-photo=...', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Use this setting only when there are no conflicts with other plugins that may interprete arguments like &album= etc.', 'wp-photo-album-plus' );
						$slug = 'wppa_use_short_qargs';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Enable pretty links', 'wp-photo-album-plus' );
						$desc = __('Enable the generation and understanding of pretty links.', 'wp-photo-album-plus' );
						$help = __('If checked, links to social media and the qr code will have "/token1/token2/" etc instead of "&arg1=..&arg2=.." etc.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('These types of links will be interpreted and cause a redirection on entering.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('It is recommended to check this box. It shortens links dramatically and simplifies qr codes.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('However, you may encounter conflicts with themes and/or other plugins, so test it throughly!', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Photo names in urls must be UNchecked for this setting to work!', 'wp-photo-album-plus' );
						$help .= '<br>'.wppa_see_also( 'links', '1', '1' );
						$slug = 'wppa_use_pretty_links';
						$opts = array( 	__( 'None', 'wp-photo-album-plus' ),
										__( 'Classic', 'wp-photo-album-plus' ),
										);
						$vals = array( '-none-', 'classic' );
						$html = wppa_select($slug, $opts, $vals );
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Enable encrypted links', 'wp-photo-album-plus' );
						$desc = __('Encrypt album and photo ids in links.', 'wp-photo-album-plus' );
						$help = '';
						$slug = '';
						$html = $security_no_disable;
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Refuse unencrypted', 'wp-photo-album-plus' );
						$desc = __('When encrypted is enabled, refuse unencrypted urls.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_refuse_unencrypted';
						$html = $security_no_disable;
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// Links from standard images
					{
						$desc = $wppa_subtab_names[$tab]['2'];
						wppa_setting_tab_description($desc);
						$coldef = array( 	__('#', 'wp-photo-album-plus' ) => '24px;',
											__('Name', 'wp-photo-album-plus' ) => 'auto;',
											__('Description', 'wp-photo-album-plus' ) => 'auto;',
											__('Link type', 'wp-photo-album-plus' ) => 'auto;',
											__('Landing page', 'wp-photo-album-plus' ) => 'auto;',
											__('New tab', 'wp-photo-album-plus' ) => '80px;',
											__('PSO', 'wp-photo-album-plus' ) => '80px;',
											__('Help', 'wp-photo-album-plus' ) => '24px;',
											);
						wppa_setting_box_header_new($tab, $coldef);

						$name = __('Cover Image', 'wp-photo-album-plus' );
						$desc = __('The link from the cover image of an album.', 'wp-photo-album-plus' );
						$help = __('Select the type of link the coverphoto points to.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('The link from the album title can be configured on the Edit Album page.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('This link will be used for the photo also if you select: same as title.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If you specify New Tab on this line, all links from the cover will open a new tab,', 'wp-photo-album-plus' );
						$slug1 = 'wppa_coverimg_linktype';
						$slug2 = 'wppa_coverimg_linkpage';
						wppa_verify_page($slug2);
						$slug3 = 'wppa_coverimg_blank';
						$slug4 = 'wppa_coverimg_overrule';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$opts = array(
							__('no link at all.', 'wp-photo-album-plus' ),
							__('the plain photo (file).', 'wp-photo-album-plus' ),
							__('same as title.', 'wp-photo-album-plus' ),
							__('lightbox.', 'wp-photo-album-plus' ),
							__('a slideshow starting at the photo', 'wp-photo-album-plus' )
						);
						$vals = array(
							'none',
							'file',
							'same',
							'lightbox',
							'slideshowstartatimage'
						);
						$onch = "wppaSlaveNeedPage(this,'$slug2');";
						$html1 = wppa_select($slug1, $opts, $vals, $onch);
						$wppa_hide_this = ! wppa_need_page($slug1);
						$html2 = wppa_select($slug2, $opts_page_post, $vals_page);
						$wppa_hide_this = false;
						$html3 = wppa_checkbox($slug3);
						$html4 = wppa_checkbox($slug4);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Thumbnail', 'wp-photo-album-plus' );
						$desc = __('Thumbnail link.', 'wp-photo-album-plus' );
						$help = __('Select the type of link you want, or no link at all.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If you select the fullsize photo on its own, it will be stretched to fit, regardless of that setting.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Note that a page must have at least [wppa] in its content to show up the photo(s).', 'wp-photo-album-plus' );
						$slug1 = 'wppa_thumb_linktype';
						$slug2 = 'wppa_thumb_linkpage';
						wppa_verify_page($slug2);
						$slug3 = 'wppa_thumb_blank';
						$slug4 = 'wppa_thumb_overrule';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$opts = array(
							__('no link at all.', 'wp-photo-album-plus' ),
							__('the plain photo (file).', 'wp-photo-album-plus' ),
							__('the full size photo in a slideshow.', 'wp-photo-album-plus' ),
							__('the thumbnails album in a slideshow.', 'wp-photo-album-plus' ),
							__('the fullsize photo on its own.', 'wp-photo-album-plus' ),
							__('the single photo in the style of a slideshow.', 'wp-photo-album-plus' ),
							__('the fs photo with download and print buttons.', 'wp-photo-album-plus' ),
							__('a plain page without a querystring.', 'wp-photo-album-plus' ),
							__('lightbox.', 'wp-photo-album-plus' )
						);
						$vals = array(
							'none',
							'file',
							'photo',
							'slidealbum',
							'single',
							'slphoto',
							'fullpopup',
							'plainpage',
							'lightbox'
						);
						if ( wppa_switch( 'auto_page') ) {
							$opts[] = __('Auto Page', 'wp-photo-album-plus' );
							$vals[] = 'autopage';
						}
						$onch = "wppaSlaveNeedPage(this,'$slug2');";
						$html1 = wppa_select($slug1, $opts, $vals, $onch);
						$wppa_hide_this = ! wppa_need_page($slug1);
						$html2 = wppa_select($slug2, $opts_page_post, $vals_page_post);
						$wppa_hide_this = false;
						$html3 = wppa_checkbox($slug3);
						$html4 = wppa_checkbox($slug4);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Sphoto', 'wp-photo-album-plus' );
						$desc = __('Single photo link.', 'wp-photo-album-plus' );
						$help = __('Select the type of link you want, or no link at all.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If you select the fullsize photo on its own, it will be stretched to fit, regardless of that setting.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Note that a page must have at least [wppa] in its content to show up the photo(s).', 'wp-photo-album-plus' );
						$slug1 = 'wppa_sphoto_linktype';
						$slug2 = 'wppa_sphoto_linkpage';
						wppa_verify_page($slug2);
						$slug3 = 'wppa_sphoto_blank';
						$slug4 = 'wppa_sphoto_overrule';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$opts = array(
							__('no link at all.', 'wp-photo-album-plus' ),
							__('the plain photo (file).', 'wp-photo-album-plus' ),
							__('the content of the album.', 'wp-photo-album-plus' ),
							__('the full size photo in a slideshow.', 'wp-photo-album-plus' ),
							__('the fullsize photo on its own.', 'wp-photo-album-plus' ),
							__('lightbox.', 'wp-photo-album-plus' )
						);
						$vals = array(
							'none',
							'file',
							'album',
							'photo',
							'single',
							'lightbox'
						);
						$onch = "wppaSlaveNeedPage(this,'$slug2');";
						$html1 = wppa_select($slug1, $opts, $vals, $onch);
						$wppa_hide_this = ! wppa_need_page($slug1);
						$html2 = wppa_select($slug2, $opts_page, $vals_page);
						$wppa_hide_this = false;
						$html3 = wppa_checkbox($slug3);
						$html4 = wppa_checkbox($slug4);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Mphoto', 'wp-photo-album-plus' );
						$desc = __('Media-like (like WP photo with caption) photo link.', 'wp-photo-album-plus' );
						$help = __('Select the type of link you want, or no link at all.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If you select the fullsize photo on its own, it will be stretched to fit, regardless of that setting.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Note that a page must have at least [wppa] in its content to show up the photo(s).', 'wp-photo-album-plus' );
						$slug1 = 'wppa_mphoto_linktype';
						$slug2 = 'wppa_mphoto_linkpage';
						wppa_verify_page($slug2);
						$slug3 = 'wppa_mphoto_blank';
						$slug4 = 'wppa_mphoto_overrule';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$onch = "wppaSlaveNeedPage(this,'$slug2');";
						$html1 = wppa_select($slug1, $opts, $vals, $onch);
						$wppa_hide_this = ! wppa_need_page($slug1);
						$html2 = wppa_select($slug2, $opts_page, $vals_page);
						$wppa_hide_this = false;
						$html3 = wppa_checkbox($slug3);
						$html4 = wppa_checkbox($slug4);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Xphoto', 'wp-photo-album-plus' );
						$desc = __('Media-like (like WP photo with - extended - caption) photo link.', 'wp-photo-album-plus' );
						$help = __('Select the type of link you want, or no link at all, to act on a photo in the style of a wp photo with - an extended - caption.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If you select the fullsize photo on its own, it will be stretched to fit, regardless of that setting.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Note that a page must have at least [wppa] in its content to show up the photo(s).', 'wp-photo-album-plus' );
						$slug1 = 'wppa_xphoto_linktype';
						$slug2 = 'wppa_xphoto_linkpage';
						wppa_verify_page($slug2);
						$slug3 = 'wppa_xphoto_blank';
						$slug4 = 'wppa_xphoto_overrule';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$onch = "wppaSlaveNeedPage(this,'$slug2');";
						$html1 = wppa_select($slug1, $opts, $vals, $onch);
						$wppa_hide_this = ! wppa_need_page($slug1);
						$html2 = wppa_select($slug2, $opts_page, $vals_page);
						$wppa_hide_this = false;
						$html3 = wppa_checkbox($slug3);
						$html4 = wppa_checkbox($slug4);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Slideshow', 'wp-photo-album-plus' );
						$desc = __('Slideshow fullsize link', 'wp-photo-album-plus' );
						$help = __('You can overrule lightbox but not big browse buttons with the photo specifc link.', 'wp-photo-album-plus' );
						$help .= '\n\n* '.__('fullsize slideshow can only be set by the WPPA_SET shortcode.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_slideshow_linktype';
						$slug2 = 'wppa_slideshow_linkpage';
						wppa_verify_page($slug2);
						$slug3 = 'wppa_slideshow_blank';
						$slug4 = 'wppa_slideshow_overrule';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$opts = array(
							__('no link at all.', 'wp-photo-album-plus' ),
							__('the plain photo (file).', 'wp-photo-album-plus' ),
							__('the fullsize photo on its own.', 'wp-photo-album-plus' ),
							__('lightbox.', 'wp-photo-album-plus' ),
							__('lightbox single photos.', 'wp-photo-album-plus' ),
							__('the fs photo with download and print buttons.', 'wp-photo-album-plus' ),
							__('the thumbnails.', 'wp-photo-album-plus' ),
							__('fullsize slideshow', 'wp-photo-album-plus' ) . '*|',
						);
						$vals = array(
							'none',
							'file',
							'single',
							'lightbox',
							'lightboxsingle',
							'fullpopup',
							'thumbs',
							'slide',
						);
						$onch = "wppaSlaveNeedPage(this,'$slug2');";
						$html1 = wppa_select($slug1, $opts, $vals, $onch);
						$wppa_hide_this = ! wppa_need_page($slug1);
						$html2 = wppa_select($slug2, $opts_page_post, $vals_page_post);
						$wppa_hide_this = false;
						$html3 = wppa_checkbox($slug3);
						$html4 = wppa_checkbox($slug4);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Film linktype', 'wp-photo-album-plus' );
						$desc = __('Direct access goto image in:', 'wp-photo-album-plus' );
						$help = __('Select the action to be taken when the user clicks on a filmstrip image.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_film_linktype';
						$slug3 = 'wppa_film_blank';
						$slug4 = 'wppa_film_overrule';
						$opts = array(
							__('slideshow window', 'wp-photo-album-plus' ),
							__('lightbox overlay', 'wp-photo-album-plus' )
						);
						$vals = array(
							'slideshow',
							'lightbox'
						);
						$html1 = wppa_select($slug1, $opts, $vals);
						$html2 = '';
						$html3 = wppa_checkbox($slug3);
						$wppa_hide_this = false;
						$html4 = wppa_checkbox($slug4);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('Grid', 'wp-photo-album-plus' );
						$desc = __('Grid photo link.', 'wp-photo-album-plus' );
						$help = __('Select the type of link you want, or no link at all.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If you select the fullsize photo on its own, it will be stretched to fit, regardless of that setting.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Note that a page must have at least [wppa] in its content to show up the photo(s).', 'wp-photo-album-plus' );
						$slug1 = 'wppa_grid_linktype';
						$slug2 = 'wppa_grid_linkpage';
						wppa_verify_page($slug2);
						$slug3 = 'wppa_grid_blank';
						$slug4 = 'wppa_grid_overrule';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$opts = array(
							__('no link at all.', 'wp-photo-album-plus' ),
							__('the plain photo (file).', 'wp-photo-album-plus' ),
							__('the full size photo in a slideshow.', 'wp-photo-album-plus' ),
							__('the fullsize photo on its own.', 'wp-photo-album-plus' ),
							__('lightbox.', 'wp-photo-album-plus' )
						);
						$vals = array(
							'none',
							'file',
							'photo',
							'single',
							'lightbox'
						);
						$onch = "wppaSlaveNeedPage(this,'$slug2');";
						$html1 = wppa_select($slug1, $opts, $vals, $onch);
						$wppa_hide_this = ! wppa_need_page($slug1);
						$html2 = wppa_select($slug2, $opts_page_post, $vals_page);
						$wppa_hide_this = false;
						$html3 = wppa_checkbox($slug3);
						$html4 = wppa_checkbox($slug4);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// Links from items and images in widgets
					{
						$desc = $wppa_subtab_names[$tab]['3'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab, $coldef);

						$name = __('PotdWidget', 'wp-photo-album-plus' );
						$desc = __('Photo Of The Day widget link.', 'wp-photo-album-plus' );
						$help = __('Select the type of link the photo of the day points to.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If you select \'defined on widget admin page\' you can manually enter a link and title on the Photo of the day Widget Admin page.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_potd_linktype';
						$slug2 = 'wppa_potd_linkpage';
						wppa_verify_page($slug2);
						$slug3 = 'wppa_potd_blank';
						$slug4 = 'wppa_potdwidget_overrule';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$opts = array(
							__('no link at all.', 'wp-photo-album-plus' ),
							__('the plain photo (file).', 'wp-photo-album-plus' ),
							__('defined on widget admin page.', 'wp-photo-album-plus' ),
							__('the content of the album.', 'wp-photo-album-plus' ),
							__('the full size photo in a slideshow.', 'wp-photo-album-plus' ),
							__('the fullsize photo on its own.', 'wp-photo-album-plus' ),
							__('a plain page without a querystring.', 'wp-photo-album-plus' ),
							__('lightbox.', 'wp-photo-album-plus' )
						);
						$vals = array(
							'none',
							'file',
							'custom',
							'album',
							'photo',
							'single',
							'plainpage',
							'lightbox'
						);
						$onch = "wppaSlaveNeedPage(this,'$slug2');";
						$html1 = wppa_select($slug1, $opts, $vals, $onch );
						$wppa_hide_this = ! wppa_need_page($slug1);
						$html2 = wppa_select($slug2, $opts_page_auto, $vals_page);
						$wppa_hide_this = false;
						$html3 = wppa_checkbox($slug3);
						$html4 = wppa_checkbox($slug4);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('SlideWidget', 'wp-photo-album-plus' );
						$desc = __('Slideshow widget photo link.', 'wp-photo-album-plus' );
						$help = __('Select the type of link the slideshow photos point to.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_slideonly_widget_linktype';
						$slug2 = 'wppa_slideonly_widget_linkpage';
						wppa_verify_page($slug2);
						$slug3 = 'wppa_sswidget_blank';
						$slug4 = 'wppa_sswidget_overrule';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$opts = array(
							__('no link at all.', 'wp-photo-album-plus' ),
							__('the plain photo (file).', 'wp-photo-album-plus' ),
							__('defined at widget activation.', 'wp-photo-album-plus' ),
							__('the content of the album.', 'wp-photo-album-plus' ),
							__('the full size photo in a slideshow.', 'wp-photo-album-plus' ),
							__('the fullsize photo on its own.', 'wp-photo-album-plus' ),
							__('a plain page without a querystring.', 'wp-photo-album-plus' ),
							__('lightbox.', 'wp-photo-album-plus' )
						);
						$vals = array(
							'none',
							'file',
							'widget',
							'album',
							'photo',
							'single',
							'plainpage',
							'lightbox'
						);
						$onch = "wppaSlaveNeedPage(this,'$slug2');";
						$html1 = wppa_select($slug1, $opts, $vals, $onch);
						$wppa_hide_this = ! wppa_need_page($slug1);
						$html2 = wppa_select($slug2, $opts_page_auto, $vals_page);
						$wppa_hide_this = false;
						$html3 = wppa_checkbox($slug3);
						$html4 = wppa_checkbox($slug4);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Album widget', 'wp-photo-album-plus' );
						$desc = __('Album widget thumbnail link', 'wp-photo-album-plus' );
						$help = __('Select the type of link the album widget photos point to.', 'wp-photo-album-plus' ) .
								'<br>' .
								__('If you tick the ASO box, the album title link settings overrule these settings, but only when the album link page or post is not set to --- the same page or post ---', 'wp-photo-album-plus' );
						$slug1 = 'wppa_album_widget_linktype';
						$slug2 = 'wppa_album_widget_linkpage';
						wppa_verify_page($slug2);
						$slug3 = 'wppa_album_widget_blank';
						$slug4 = 'wppa_album_widget_overrule';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$opts = array(
							__('sub albums and thumbnails.', 'wp-photo-album-plus' ),
							__('slideshow', 'wp-photo-album-plus' ),
							__('a plain page without a querystring.', 'wp-photo-album-plus' ),
							__('lightbox', 'wp-photo-album-plus' ),
						);
						$vals = array(
							'content',
							'slide',
							'plainpage',
							'lightbox',
						);
						$onch = "wppaSlaveNeedPage(this,'$slug2');";
						$html1 = wppa_select($slug1, $opts, $vals, $onch);
						$wppa_hide_this = ! wppa_need_page($slug1);
						$html2 = wppa_select($slug2, $opts_page_auto, $vals_page);
						$wppa_hide_this = false;
						$html3 = wppa_checkbox($slug3);
						$html4 = '<span title="'.esc_attr(__('Album specific link overrules', 'wp-photo-album-plus' )).'" style="float:left; cursor:pointer">ASO&nbsp;</span>'.wppa_checkbox($slug4);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Album navigator widget', 'wp-photo-album-plus' );
						$desc = __('Album navigator widget link', 'wp-photo-album-plus' );
						$help = __('Select the type of link the album widget photos point to.', 'wp-photo-album-plus' ) .
								'<br>' .
								__('If you tick the ASO box, the album title link settings overrule these settings, but only when the album link page or post is not set to --- the same page or post ---', 'wp-photo-album-plus' );
						$slug1 = 'wppa_album_navigator_widget_linktype';
						$slug2 = 'wppa_album_navigator_widget_linkpage';
						wppa_verify_page($slug2);
						$slug3 = 'wppa_album_navigator_widget_blank';
						$slug4 = 'wppa_album_navigator_widget_overrule';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$opts = array(
							__('thumbnails', 'wp-photo-album-plus' ),
							__('slideshow', 'wp-photo-album-plus' ),
							__('sub albums and thumbnails', 'wp-photo-album-plus' ),
							__('a plain page without a querystring.', 'wp-photo-album-plus' ),
							__('lightbox', 'wp-photo-album-plus' ),
						);
						$vals = array(
							'thumbs',
							'slide',
							'content',
							'plainpage',
							'lightbox',
						);
						$onch = "wppaSlaveNeedPage(this,'$slug2');";
						$html1 = wppa_select($slug1, $opts, $vals, $onch);
						$wppa_hide_this = ! wppa_need_page($slug1);
						$html2 = wppa_select($slug2, $opts_page_auto, $vals_page);
						$wppa_hide_this = false;
						$html3 = wppa_checkbox($slug3);
						$html4 = '<span title="'.esc_attr(__('Album specific link overrules', 'wp-photo-album-plus' )).'" style="float:left; cursor:pointer">ASO&nbsp;</span>'.wppa_checkbox($slug4);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('ThumbnailWidget', 'wp-photo-album-plus' );
						$desc = __('Thumbnail widget photo link.', 'wp-photo-album-plus' );
						$help = __('Select the type of link the thumbnail photos point to.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_thumbnail_widget_linktype';
						$slug2 = 'wppa_thumbnail_widget_linkpage';
						wppa_verify_page($slug2);
						$slug3 = 'wppa_thumbnail_widget_blank';
						$slug4 = 'wppa_thumbnail_widget_overrule';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$opts = array(
							__('no link at all.', 'wp-photo-album-plus' ),
							__('the plain photo (file).', 'wp-photo-album-plus' ),
							__('the full size photo in a slideshow.', 'wp-photo-album-plus' ),
							__('the fullsize photo on its own.', 'wp-photo-album-plus' ),
							__('the single photo in the style of a slideshow.', 'wp-photo-album-plus' ),
							__('the fs photo with download and print buttons.', 'wp-photo-album-plus' ),
							__('a plain page without a querystring.', 'wp-photo-album-plus' ),
							__('lightbox.', 'wp-photo-album-plus' )
						);
						$vals = array(
							'none',
							'file',
							'photo',
							'single',
							'slphoto',
							'fullpopup',
							'plainpage',
							'lightbox'
						);
						$onch = "wppaSlaveNeedPage(this,'$slug2');";
						$html1 = wppa_select($slug1, $opts, $vals, $onch);
						$wppa_hide_this = ! wppa_need_page($slug1);
						$html2 = wppa_select($slug2, $opts_page_auto, $vals_page);
						$wppa_hide_this = false;
						$html3 = wppa_checkbox($slug3);
						$html4 = wppa_checkbox($slug4);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('TopTenWidget', 'wp-photo-album-plus' );
						$desc = __('TopTen widget photo link.', 'wp-photo-album-plus' );
						$help = __('Select the type of link the top ten photos point to.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_topten_widget_linktype';
						$slug2 = 'wppa_topten_widget_linkpage';
						wppa_verify_page($slug2);
						$slug3 = 'wppa_topten_blank';
						$slug4 = 'wppa_topten_overrule';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$opts = array(
							__('no link at all.', 'wp-photo-album-plus' ),
							__('the plain photo (file).', 'wp-photo-album-plus' ),
							__('the content of the virtual topten album.', 'wp-photo-album-plus' ),
							__('the content of the thumbnails album.', 'wp-photo-album-plus' ),
							__('the full size photo in a slideshow.', 'wp-photo-album-plus' ),
							__('the thumbnails album in a slideshow.', 'wp-photo-album-plus' ),
							__('the fullsize photo on its own.', 'wp-photo-album-plus' ),
							__('the single photo in the style of a slideshow.', 'wp-photo-album-plus' ),
							__('the fs photo with download and print buttons.', 'wp-photo-album-plus' ),
							__('a plain page without a querystring.', 'wp-photo-album-plus' ),
							__('lightbox.', 'wp-photo-album-plus' )
						);
						$vals = array(
							'none',
							'file',
							'album',
							'thumbalbum',
							'photo',
							'slidealbum',
							'single',
							'slphoto',
							'fullpopup',
							'plainpage',
							'lightbox'
						);
						$onch = "wppaSlaveNeedPage(this,'$slug2');";
						$html1 = wppa_select($slug1, $opts, $vals, $onch);
						$wppa_hide_this = ! wppa_need_page($slug1);
						$html2 = wppa_select($slug2, $opts_page_auto, $vals_page);
						$wppa_hide_this = false;
						$html3 = wppa_checkbox($slug3);
						$html4 = wppa_checkbox($slug4);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('TopTenWidget', 'wp-photo-album-plus' );
						$desc = __('TopTen widget album linkpage.', 'wp-photo-album-plus' );
						$help = __('Select the linkpage the top ten albums point to.', 'wp-photo-album-plus' );
						$slug1 = '';
						$slug2 = 'wppa_topten_widget_album_linkpage';
						wppa_verify_page($slug2);
						$slug3 = ''; // 'wppa_topten_blank';
						$slug4 = ''; // 'wppa_topten_overrule';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$html1 = '';
						$html2 = wppa_select($slug2, $opts_page_auto, $vals_page);
						$html3 = '';
						$html4 = '';
						$html = array($html1, $html2, $html3, $html4);
						$clas = 'wppa_rating';
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('LasTenWidget', 'wp-photo-album-plus' );
						$desc = __('Last Ten widget photo link.', 'wp-photo-album-plus' );
						$help = __('Select the type of link the last ten photos point to.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_lasten_widget_linktype';
						$slug2 = 'wppa_lasten_widget_linkpage';
						wppa_verify_page($slug2);
						$slug3 = 'wppa_lasten_blank';
						$slug4 = 'wppa_lasten_overrule';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$onch = "wppaSlaveNeedPage(this,'$slug2');";
						$html1 = wppa_select($slug1, $opts, $vals, $onch);
						$wppa_hide_this = ! wppa_need_page($slug1);
						$html2 = wppa_select($slug2, $opts_page_auto, $vals_page);
						$wppa_hide_this = false;
						$html3 = wppa_checkbox($slug3);
						$html4 = wppa_checkbox($slug4);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('CommentWidget', 'wp-photo-album-plus' );
						$desc = __('Comment widget photo link.', 'wp-photo-album-plus' );
						$help = __('Select the type of link the comment widget photos point to.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_comment_widget_linktype';
						$slug2 = 'wppa_comment_widget_linkpage';
						wppa_verify_page($slug2);
						$slug3 = 'wppa_comment_blank';
						$slug4 = 'wppa_comment_overrule';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$onch = "wppaSlaveNeedPage(this,'$slug2');";
						$html1 = wppa_select($slug1, $opts, $vals, $onch);
						$wppa_hide_this = ! wppa_need_page($slug1);
						$html2 = wppa_select($slug2, $opts_page_auto, $vals_page);
						$wppa_hide_this = false;
						$html3 = wppa_checkbox($slug3);
						$html4 = wppa_checkbox($slug4);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						$name = __('FeaTenWidget', 'wp-photo-album-plus' );
						$desc = __('FeaTen widget photo link.', 'wp-photo-album-plus' );
						$help = __('Select the type of link the featured ten photos point to.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_featen_widget_linktype';
						$slug2 = 'wppa_featen_widget_linkpage';
						wppa_verify_page($slug2);
						$slug3 = 'wppa_featen_blank';
						$slug4 = 'wppa_featen_overrule';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$onch = "wppaSlaveNeedPage(this,'$slug2');";
						$html1 = wppa_select($slug1, $opts, $vals, $onch);
						$wppa_hide_this = ! wppa_need_page($slug1);
						$html2 = wppa_select($slug2, $opts_page_auto, $vals_page);
						$wppa_hide_this = false;
						$html3 = wppa_checkbox($slug3);
						$html4 = wppa_checkbox($slug4);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '10', $name, $desc, $html, $help);

						$name = __('Tagcloud Link', 'wp-photo-album-plus' );
						$desc = __('Configure the link from the tags in the tag cloud.', 'wp-photo-album-plus' );
						$help = __('Link the tag words to either the thumbnails or the slideshow.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('The Occur(rance) indicates the sequence number of the [wppa] shortcode on the landing page to be used.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_tagcloud_linktype';
						$slug2 = 'wppa_tagcloud_linkpage';
						wppa_verify_page($slug2);
						$slug3 = 'wppa_tagcloud_blank';
						$slug4 = 'wppa_tagcloud_linkpage_oc';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$opts1 = array(
							__('album (thumbnails only)', 'wp-photo-album-plus' ),
							__('slideshow', 'wp-photo-album-plus' )
						);
						$vals1 = array(
							'album',
							'slide'
						);
						$opts4 = array('1','2','3','4','5');
						$vals4 = array('1','2','3','4','5');
						$onch = "wppaSlaveNeedPage(this,'$slug2');";
						$html1 = wppa_select($slug1, $opts1, $vals1, $onch);
						$wppa_hide_this = ! wppa_need_page($slug1);
						$opts = $opts_page_auto;
						$opts[] = __('--- the same page ---', 'wp-photo-album-plus' );
						$vals = $vals_page;
						$vals[] = '-1';
						$html2 = wppa_select($slug2, $opts, $vals);
						$wppa_hide_this = false;
						$html3 = wppa_checkbox($slug3);
						$html4 = '<div style="font-size:9px;float:left;" class="'.$clas.'" >'.__('Occur', 'wp-photo-album-plus' ).'</div>'.wppa_select($slug4, $opts4, $vals4);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '11', $name, $desc, $html, $help);

						$name = __('Multitag Link', 'wp-photo-album-plus' );
						$desc = __('Configure the link from the multitag selection.', 'wp-photo-album-plus' );
						$help = __('Link to either the thumbnails or the slideshow.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('The Occur(rance) indicates the sequence number of the [wppa] shortcode on the landing page to be used.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_multitag_linktype';
						$slug2 = 'wppa_multitag_linkpage';
						wppa_verify_page($slug2);
						$slug3 = 'wppa_multitag_blank';
						$slug4 = 'wppa_multitag_linkpage_oc';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$opts1 = array(
							__('album (thumbnails only)', 'wp-photo-album-plus' ),
							__('slideshow', 'wp-photo-album-plus' )
						);
						$vals1 = array(
							'album',
							'slide'
						);
						$opts4 = array('1','2','3','4','5');
						$vals4 = array('1','2','3','4','5');
						$onch = "wppaSlaveNeedPage(this,'$slug2');";
						$html1 = wppa_select($slug1, $opts1, $vals1, $onch);
						$wppa_hide_this = ! wppa_need_page($slug1);
						$opts = $opts_page_auto;
						$opts[] = __('--- the same page ---', 'wp-photo-album-plus' );
						$vals = $vals_page;
						$vals[] = '-1';
						$html2 = wppa_select($slug2, $opts, $vals);
						$wppa_hide_this = false;
						$html3 = wppa_checkbox($slug3);
						$html4 = '<div style="font-size:9px;float:left;" class="" >'.__('Occur', 'wp-photo-album-plus' ).'</div>'.wppa_select($slug4, $opts4, $vals4);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '12', $name, $desc, $html, $help);

						$name = __('Bestof Landing', 'wp-photo-album-plus' );
						$desc = __('Select the landing page for the BestOf Widget / Box', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = '';
						$slug2 = 'wppa_bestof_widget_linkpage';
						wppa_verify_page($slug2);
						$slug3 = '';
						$slug4 = '';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$html1 = '';
						$html2 = wppa_select($slug2, $opts_page_auto, $vals_page);
						$html3 = '';
						$html4 = '';
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '13', $name, $desc, $html, $help);

						$name = __('SM widget return', 'wp-photo-album-plus' );
						$desc = __('Select the return link for social media from widgets', 'wp-photo-album-plus' );
						$help = __('If you select Landing page, and it wont work, it may be required to set the Occur to the sequence number of the landing shortcode on the page.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Normally it is 1, but you can try 2 etc. Always create a new shared link to test a setting.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('The Occur(rance) indicates the sequence number of the [wppa] shortcode on the landing page to be used.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_widget_sm_linktype';
						$slug2 = 'wppa_widget_sm_linkpage';
						wppa_verify_page($slug2);
						$slug3 = '';
						$slug4 = 'wppa_widget_sm_linkpage_oc';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$opts = array(
							__('Home page', 'wp-photo-album-plus' ),
							__('Landing page', 'wp-photo-album-plus' )
						);
						$vals = array(
							'home',
							'landing'
						);
						$onch = "wppaSlaveNeedPage(this,'$slug2');";
						$html1 = wppa_select($slug1, $opts, $vals, $onch);
						$wppa_hide_this = ! wppa_need_page($slug1);
						$html2 = wppa_select($slug2, $opts_page_auto, $vals_page);
						$wppa_hide_this = false;
						$html3 = '';
						$opts4 = array('1','2','3','4','5');
						$vals4 = array('1','2','3','4','5');
						$html4 = '<div style="font-size:9px;float:left;" class="" >'.__('Occur', 'wp-photo-album-plus' ).'</div>'.wppa_select($slug4, $opts4, $vals4);
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '14', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// Art monkey
					{
						$desc = $wppa_subtab_names[$tab]['4'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Download Link (aka Art Monkey link)', 'wp-photo-album-plus' );
						$desc = __('Enable frontend downloads', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_art_monkey_on';
						$onch = "wppaSlaveChecked(this,'amo');";
						$html = wppa_checkbox($slug, $onch);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$amo = wppa_switch( 'art_monkey_on' );

						$name = __('Downloadable filetypes', 'wp-photo-album-plus' );
						$desc = __('Select filetypes to be downloadable.', 'wp-photo-album-plus' );
						$help = '';
						$slug ='wppa_art_monkey_types';
						$opts = [__('Photo', 'wp-photo-album-plus'),__('Video', 'wp-photo-album-plus'),__('Audio', 'wp-photo-album-plus'),__('Document', 'wp-photo-album-plus')];
						$vals = ['photo', 'video', 'audio', 'document'];
						$onch = "wppaSlaveSelected('wppa_art_monkey_types-photo','amo')";
						$html = wppa_select_m($slug, $opts, $vals, $onch);	// wppa_art_monkey_types-photo
						wppa_setting_new($slug, '2', $name, $desc, $html, $help, $amo, 'amo');

						$name = __('Display method', 'wp-photo-album-plus' );
						$desc = __('Select button or textlink.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_art_monkey_display';
						$opts = array(
							__('Button', 'wp-photo-album-plus' ),
							__('Textlink', 'wp-photo-album-plus' )
						);
						$vals = array(
							'button',
							'text'
						);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help, $amo, 'amo');

						$name = __('Download Sources', 'wp-photo-album-plus' );
						$desc = __('Use photo source file for download link if available.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_art_monkey_source';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help, $amo && strpos( wppa_opt( 'art_monkey_types' ), 'photo' ) !== false, 'amo' );

						$name = __('Download link on single items', 'wp-photo-album-plus');
						$desc = __('Works on shortcode type "photo".', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_art_monkey_single';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '5', $name, $desc, $html, $help, $amo, 'amo');

						$name = __('Download link on single items with caption', 'wp-photo-album-plus');
						$desc = __('Works on shortcode types "mphoto" and "xphoto".', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_art_monkey_mxsingle';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help, $amo, 'amo');

						$name = __('Downloadlink in slideshows', 'wp-photo-album-plus');
						$desc = __('Makes the photo name a download link or adds the link to the description.', 'wp-photo-album-plus');
						$help = __('In slide widgets only the name will be used for download links', 'wp-photo-album-plus');
						$slug = 'wppa_art_monkey_slide';
						$opts = array(
							__('--- none ---', 'wp-photo-album-plus' ),
							__('Link the name', 'wp-photo-album-plus' ),
							__('In the description', 'wp-photo-album-plus' ),
						);
						$vals = array(
							'none',
							'name',
							'desc',
						);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '7', $name, $desc, $html, $help, $amo, 'amo');

						$name = __('Download link on thumbnails', 'wp-photo-album-plus');
						$desc = __('Only on item names', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_art_monkey_thumb';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '8', $name, $desc, $html, $help, $amo, 'amo');

						$name = __('Download link on lightbox', 'wp-photo-album-plus' );
						$desc = __('Only on item names.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_art_monkey_lightbox';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '9', $name, $desc, $html, $help, $amo, 'amo');

						wppa_setting_box_footer_new();
					}
					// Other links
					{
						$desc = $wppa_subtab_names[$tab]['5'];
						wppa_setting_tab_description($desc);
						$coldef = array( 	__('#', 'wp-photo-album-plus' ) => '24px;',
											__('Name', 'wp-photo-album-plus' ) => 'auto;',
											__('Description', 'wp-photo-album-plus' ) => 'auto;',
											__('Link type', 'wp-photo-album-plus' ) => 'auto;',
											__('Landing page', 'wp-photo-album-plus' ) => 'auto;',
									'' => '80px;', //		__('New tab', 'wp-photo-album-plus' ) => '80px;',
									'' => '80px;', //		__('PSO', 'wp-photo-album-plus' ) => '80px;',
											__('Help', 'wp-photo-album-plus' ) => '24px;',
											);
						wppa_setting_box_header_new($tab, $coldef);

						$name = __('Album download link', 'wp-photo-album-plus' );
						$desc = __('Place an album download link on the album covers and the edit album info page', 'wp-photo-album-plus' );
						$help = __('Creates a download zipfile containing the photos of the album', 'wp-photo-album-plus' );
						$slug = 'wppa_allow_download_album';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '7', $name, $desc, $html.'</td><td></td><td></td><td>', $help);

						$name = __('Album download Source', 'wp-photo-album-plus' );
						$desc = __('Use Source file for album download link if available.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_download_album_source';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '8', $name, $desc, $html.'</td><td></td><td></td><td>', $help);

						$name = __('Super View Landing', 'wp-photo-album-plus' );
						$desc = __('The landing page for the Super View widget.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = '';
						$slug2 = 'wppa_super_view_linkpage';
						wppa_verify_page($slug2);
						$slug3 = '';
						$slug4 = '';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$html1 = __('Defined by the visitor: slideshow or thumbnails', 'wp-photo-album-plus' );
						$html2 = wppa_select($slug2, $opts_page_auto, $vals_page);
						$html3 = '';
						$html4 = '';
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						$name = __('Uploader Landing', 'wp-photo-album-plus' );
						$desc = __('Select the landing page for the Uploader Widget', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = '';
						$slug2 = 'wppa_upldr_widget_linkpage';
						wppa_verify_page($slug2);
						$slug3 = '';
						$slug4 = '';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$html1 = '';
						$html2 = wppa_select($slug2, $opts_page_auto, $vals_page);
						$html3 = '';
						$html4 = '';
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '10', $name, $desc, $html, $help);

						$name = __('Supersearch Landing', 'wp-photo-album-plus' );
						$desc = __('Select the landing page for the Supersearch Box', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = '';
						$slug2 = 'wppa_supersearch_linkpage';
						wppa_verify_page($slug2);
						$slug3 = '';
						$slug4 = '';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$html1 = '';
						$html2 = wppa_select($slug2, $opts_page_auto, $vals_page);
						$html3 = '';
						$html4 = '';
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '11', $name, $desc, $html, $help);

						$name = __('Album cover sub albums link', 'wp-photo-album-plus' );
						$desc = __('Select the linktype and display type for sub albums on parent album covers.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_cover_sublinks';
						$slug2 = 'wppa_cover_sublinks_display';
						$slug3 = '';
						$slug4 = '';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$opts = array(
							__('No link at all', 'wp-photo-album-plus' ),
							__('Thumbnails and covers', 'wp-photo-album-plus' ),
							__('Slideshow or covers', 'wp-photo-album-plus' ),
							__('Sub album title link', 'wp-photo-album-plus' ),
						);
						$vals = array(
							'none',
							'content',
							'slide',
							'title',
						);
						$html1 = wppa_select($slug1, $opts, $vals);
						$opts = array(
							__('No display at all', 'wp-photo-album-plus' ),
							__('A list with sub-(sub) albums', 'wp-photo-album-plus' ),
							__('A list of sub albums', 'wp-photo-album-plus' ),
							__('An enumeration of names', 'wp-photo-album-plus' ),
							__('Micro thumbnails', 'wp-photo-album-plus' ),
						);
						$vals = array(
							'none',
							'recursivelist',
							'list',
							'enum',
							'microthumbs',
						);
						$html2 = wppa_select($slug2, $opts, $vals);
						$html3 = '';
						$html4 = '';
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '12', $name, $desc, $html, $help);

						$name = __('Real calendar link', 'wp-photo-album-plus' );
						$desc = __('Select the linktype the real calendar day image should link to', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_real_calendar_linktype';
						$slug2 = '';
						$slug3 = '';
						$slug4 = '';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$opts = array(
							__('Slideshow', 'wp-photo-album-plus' ),
							__('Lightbox', 'wp-photo-album-plus' ),
						);
						$vals = array(
							'slide',
							'lightbox',
						);
						$onchange = '';
						$html1 = wppa_select($slug1, $opts, $vals);
						$html2 = '';
						$slug3 = '';
						$slug4 = '';
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '13', $name, $desc, $html, $help);

						$name = __('Album id on cover is link', 'wp-photo-album-plus' );
						$desc = __('The album number on the cover links to the album admin page of the album', 'wp-photo-album-plus' );
						$help = __('Album id on cover must be enabled. The ids are only links when the user has edit album access to the album', 'wp-photo-album-plus' );
						$slug1 = 'wppa_fe_albid_edit';
						$slug2 = '';
						$slug3 = '';
						$slug4 = '';
						$slug = array($slug1, $slug2, $slug3, $slug4);
						$html1 = wppa_checkbox($slug1);
						$html2 = wppa_see_also( 'covers', '2', '10' );;
						$slug3 = '';
						$slug4 = '';
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new($slug, '14', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
				}
				break;

				case 'users': {
					// Frontend (user) upload related settings
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('User upload Photos', 'wp-photo-album-plus' );
						$desc = __('Enable frontend upload.', 'wp-photo-album-plus' );
						$help = '';
						$slug = '';
						$html = '<input type="checkbox" checked disabled >' . wppa_see_also( 'new', '1', '18..26' );
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('User upload Video', 'wp-photo-album-plus' );
						$desc = __('Enable frontend upload of video.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_user_upload_video_on';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help, wppa_switch( 'enable_video' ));

						$name = __('User upload Audio', 'wp-photo-album-plus' );
						$desc = __('Enable frontend upload of audio.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_user_upload_audio_on';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help, wppa_switch( 'enable_audio' ));

						$name = __('User upload roles', 'wp-photo-album-plus' );
						$desc = __('Optionally limit access to selected userroles', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_user_opload_roles';
						$roles = $wp_roles->roles;
						$opts = array();
						$vals = array();
						$opts[] = '-- '.__('Not limited', 'wp-photo-album-plus' ).' --';
						$vals[] = '';
						foreach (array_keys($roles) as $key) {
							$role = $roles[$key];
							$rolename = translate_user_role( $role['name'] );
							$opts[] = $rolename;
							$vals[] = $key;
						}
						$onch = '';
						$html = wppa_select_m($slug, $opts, $vals, $onch, '', false, '', '220' );
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Show Copyright', 'wp-photo-album-plus' );
						$desc = __('Show a copyright warning on frontend upload form.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_copyright_on';
						$onch = "wppaSlaveChecked(this,'wppa_copyright_notice');";
						$html = wppa_checkbox($slug, $onch);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Copyright notice', 'wp-photo-album-plus' );
						$desc = __('The message to be displayed.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_copyright_notice';
						$html = wppa_textarea($slug, $name);
						wppa_setting_new($slug, '7', $name, $desc, $html, $help, wppa_switch( 'copyright_on' ));

						$name = __('User Watermark', 'wp-photo-album-plus' );
						$desc = __('Uploading users may select watermark settings', 'wp-photo-album-plus' );
						$help = __('If checked, anyone who can upload and/or import photos can overrule the default watermark settings.', 'wp-photo-album-plus' );
						$slug = 'wppa_watermark_user';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '8', $name, $desc, $html, $help, wppa_switch('watermark_on'));

						$name = __('User name', 'wp-photo-album-plus' );
						$desc = __('Uploading users may overrule the default name.', 'wp-photo-album-plus' );
						$help = __('If checked, the default photo name may be overruled by the user.', 'wp-photo-album-plus' );
						$help .= '<br>'.wppa_see_also( 'new', '1', '29' );
						$slug1 = 'wppa_name_user';
						$slug2 = 'wppa_name_user_mandatory';
						$html = wppa_checkbox( $slug1 ) . '<span style="float:left">' . __( 'Mandatory', 'wp-photo-album-plus' ) . ':</span>' . wppa_checkbox( $slug2 );
						wppa_setting_new($slug1, '9', $name, $desc, $html, $help);

						$name = __('Apply Newphoto desc user', 'wp-photo-album-plus' );
						$desc = __('Give each new frontend uploaded photo a standard description.', 'wp-photo-album-plus' );
						$help = __('If checked, each new photo will get the default New photo description.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Note: If the next item is checked, the user can overwrite this', 'wp-photo-album-plus' );
						$help .= '<br>'.wppa_see_also( 'new', '1', '13' );
						$slug = 'wppa_apply_newphoto_desc_user';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '10', $name, $desc, $html, $help);

						$name = __('User desc', 'wp-photo-album-plus' );
						$desc = __('Uploading users may overrule the default description.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_desc_user';
						$slug2 = 'wppa_desc_user_mandatory';
						$html = wppa_checkbox( $slug1 ) . '<span style="float:left">' . __( 'Mandatory', 'wp-photo-album-plus' ) . ':</span>' . wppa_checkbox( $slug2 );
						wppa_setting_new($slug, '11', $name, $desc, $html, $help);

						$name = __('User upload custom', 'wp-photo-album-plus' );
						$desc = __('Frontend upload can fill in custom data fields.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_fe_custom_fields';
						$html = wppa_checkbox($slug).wppa_see_also( 'custom', '2' );;
						wppa_setting_new($slug, '12', $name, $desc, $html, $help);

						$name = __('User upload tags', 'wp-photo-album-plus' );
						$desc = __('Frontend upload can add tags.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_fe_upload_tags';
						$onch = "wppaSlaveChecked(this,'user-tags');";
						$html = wppa_checkbox($slug, $onch);
						wppa_setting_new($slug, '13', $name, $desc, $html, $help);

						$name = __('Tag selection box', 'wp-photo-album-plus' ).' 1';
						$desc = __('Front-end upload tags selecion box.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_up_tagselbox_on_1';
						$slug2 = 'wppa_up_tagselbox_multi_1';
						$html = '<span style="float:left" >'.__('On:', 'wp-photo-album-plus' ).'</span>'.wppa_checkbox($slug1).'<span style="float:left" >'.__('Multi:', 'wp-photo-album-plus' ).'</span>'.wppa_checkbox($slug2);
						wppa_setting_new($slug, '14', $name, $desc, $html, $help, wppa_switch( 'fe_upload_tags' ),'user-tags' );

						$name = __('Caption box', 'wp-photo-album-plus' ).' 1';
						$desc = __('The title of the tag selection box.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_up_tagselbox_title_1';
						$html = wppa_edit( $slug, wppa_get_option( $slug ), '300px' );
						wppa_setting_new($slug, '15', $name, $desc, $html, $help, wppa_switch( 'fe_upload_tags' ),'user-tags' );

						$name = __('Tags box', 'wp-photo-album-plus' ).' 1';
						$desc = __('The tags in the selection box.', 'wp-photo-album-plus' );
						$help = __('Enter the tags you want to appear in the selection box. Empty means: all existing tags', 'wp-photo-album-plus' );
						$slug = 'wppa_up_tagselbox_content_1';
						$html = wppa_edit( $slug, wppa_get_option( $slug ), '300px' );
						wppa_setting_new($slug, '16', $name, $desc, $html, $help, wppa_switch( 'fe_upload_tags' ),'user-tags' );

						$name = __('Tag selection box', 'wp-photo-album-plus' ).' 2';
						$desc = __('Front-end upload tags selecion box.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_up_tagselbox_on_2';
						$slug2 = 'wppa_up_tagselbox_multi_2';
						$html = '<span style="float:left" >'.__('On:', 'wp-photo-album-plus' ).'</span>'.wppa_checkbox($slug1).'<span style="float:left" >'.__('Multi:', 'wp-photo-album-plus' ).'</span>'.wppa_checkbox($slug2);
						wppa_setting_new($slug, '17', $name, $desc, $html, $help, wppa_switch( 'fe_upload_tags' ),'user-tags' );

						$name = __('Caption box', 'wp-photo-album-plus' ).' 2';
						$desc = __('The title of the tag selection box.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_up_tagselbox_title_2';
						$html = wppa_edit( $slug, wppa_get_option( $slug ), '300px' );
						wppa_setting_new($slug, '18', $name, $desc, $html, $help, wppa_switch( 'fe_upload_tags' ),'user-tags' );

						$name = __('Tags box', 'wp-photo-album-plus' ).' 2';
						$desc = __('The tags in the selection box.', 'wp-photo-album-plus' );
						$help = __('Enter the tags you want to appear in the selection box. Empty means: all existing tags', 'wp-photo-album-plus' );
						$slug = 'wppa_up_tagselbox_content_2';
						$html = wppa_edit( $slug, wppa_get_option( $slug ), '300px' );
						wppa_setting_new($slug, '19', $name, $desc, $html, $help, wppa_switch( 'fe_upload_tags' ),'user-tags' );

						$name = __('Tag selection box', 'wp-photo-album-plus' ).' 3';
						$desc = __('Front-end upload tags selecion box.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_up_tagselbox_on_3';
						$slug2 = 'wppa_up_tagselbox_multi_3';
						$html = '<span style="float:left" >'.__('On:', 'wp-photo-album-plus' ).'</span>'.wppa_checkbox($slug1).'<span style="float:left" >'.__('Multi:', 'wp-photo-album-plus' ).'</span>'.wppa_checkbox($slug2);
						wppa_setting_new($slug, '20', $name, $desc, $html, $help, wppa_switch( 'fe_upload_tags' ),'user-tags' );

						$name = __('Caption box', 'wp-photo-album-plus' ).' 3';
						$desc = __('The title of the tag selection box.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_up_tagselbox_title_3';
						$html = wppa_edit( $slug, wppa_get_option( $slug ), '300px' );
						wppa_setting_new($slug, '21', $name, $desc, $html, $help, wppa_switch( 'fe_upload_tags' ),'user-tags' );

						$name = __('Tags box', 'wp-photo-album-plus' ).' 3';
						$desc = __('The tags in the selection box.', 'wp-photo-album-plus' );
						$help = __('Enter the tags you want to appear in the selection box. Empty means: all existing tags', 'wp-photo-album-plus' );
						$slug = 'wppa_up_tagselbox_content_3';
						$html = wppa_edit( $slug, wppa_get_option( $slug ), '300px' );
						wppa_setting_new($slug, '22', $name, $desc, $html, $help, wppa_switch( 'fe_upload_tags' ),'user-tags' );

						$name = __('New tags', 'wp-photo-album-plus' );
						$desc = __('Input field for any user defined tags.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_up_tag_input_on';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '23', $name, $desc, $html, $help, wppa_switch( 'fe_upload_tags' ),'user-tags' );

						$name = __('New tags caption', 'wp-photo-album-plus' );
						$desc = __('The caption above the tags input field.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_up_tag_input_title';
						$html = wppa_edit( $slug, wppa_get_option( $slug ), '300px' );
						wppa_setting_new($slug, '24', $name, $desc, $html, $help, wppa_switch( 'fe_upload_tags' ),'user-tags' );

						$name = __('Tags box New', 'wp-photo-album-plus' );
						$desc = __('The tags in the New tags input box.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_up_tagbox_new';
						$html = wppa_edit( $slug, wppa_get_option( $slug ), '300px' );
						wppa_setting_new($slug, '25', $name, $desc, $html, $help, wppa_switch( 'fe_upload_tags' ),'user-tags' );

						$name = __('Preview tags', 'wp-photo-album-plus' );
						$desc = __('Show a preview of all tags that will be added to the photo info.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_up_tag_preview';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '26', $name, $desc, $html, $help, wppa_switch( 'fe_upload_tags' ),'user-tags' );

						$name = __('Camera connect', 'wp-photo-album-plus' );
						$desc = __('Connect frontend upload to camara on mobile devices with camera', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_camera_connect';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '27', $name, $desc, $html, $help);

						$name = __('Blog It!', 'wp-photo-album-plus' );
						$desc = __('Enable blogging photos.', 'wp-photo-album-plus' );
						$help = __('Users need the capability edit_posts to directly blog photos.', 'wp-photo-album-plus' );
						$slug = 'wppa_blog_it';
						$opts = array( 	__('disabled', 'wp-photo-album-plus' ),
										__('optional', 'wp-photo-album-plus' ),
										__('always', 'wp-photo-album-plus' ),
									);
						$vals = array( 	'-none-',
										'optional',
										'always',
									);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '28', $name, $desc, $html, $help);

						$name = __('Blog It need moderation', 'wp-photo-album-plus' );
						$desc = __('Posts with blogged photos need moderation.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_blog_it_moderate';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '29', $name, $desc, $html, $help);

						$name = __('Blog It shortcode', 'wp-photo-album-plus' );
						$desc = __('Shortcode to be used on the blog post', 'wp-photo-album-plus' );
						$help = __('Make sure it contains photo="#id"', 'wp-photo-album-plus' );
						$slug = 'wppa_blog_it_shortcode';
						$html = wppa_input($slug, '85%');
						wppa_setting_new($slug, '30', $name, $desc, $html, $help);

						$name = __('Frontend ending label', 'wp-photo-album-plus' );
						$desc = __('Frontend upload / create / edit dialog closing label text.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_close_text';
						$opts = array( __('Abort', 'wp-photo-album-plus' ), __('Cancel', 'wp-photo-album-plus' ), __('Close', 'wp-photo-album-plus' ), __('Exit', 'wp-photo-album-plus' ), __('Quit', 'wp-photo-album-plus' ) );
						$vals = array( 'Abort', 'Cancel', 'Close', 'Exit', 'Quit' );
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '31', $name, $desc, $html, $help);

						$name = __('Upload logout', 'wp-photo-album-plus' );
						$desc = __('Allow non logged-in to upload photos', 'wp-photo-album-plus' );
						$help = '';
						$slug = '';
						$html = $security_no_enable;
						wppa_setting_new($slug, '32', $name, $desc, $html, $help);

						$name = __('Del vanished user', 'wp-photo-album-plus');
						$desc = __('Remove a users items and albums when the user is deleted', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_clear_vanished_user';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '33', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
				}
				break;

				case 'email': {
					// Email configuration settings
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Mail on new album', 'wp-photo-album-plus' );
						$desc = __('Enable mailing users when a new album is created', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_newalbumnotify';
						$subs = count( wppa_index_string_to_array( wppa_get_option( 'wppa_mailinglist_newalbumnotify', '' ) ) );
						$html = wppa_checkbox($slug) . '&nbsp;' . sprintf( __( '%d subscribers', 'wp-photo-album-plus' ), $subs );
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Mail on upload', 'wp-photo-album-plus' );
						$desc = __('Enable mailing users when a frontend upload has been done', 'wp-photo-album-plus' );
						$help = __('When moderation is required, the mails will be sent after approval', 'wp-photo-album-plus' );
						$slug1 = 'wppa_feuploadnotify';
						$slug2 = 'wppa_beuploadnotify';
						$slug3 = 'wppa_show_email_thumbs';
						$subs = count( wppa_index_string_to_array( wppa_get_option( 'wppa_mailinglist_feuploadnotify', '' ) ) );
						$html = wppa_checkbox($slug1, "wppaSlaveChecked(this,'backendalso')" ) . '
								<span
									style="float:left" >&nbsp;' .
									sprintf( __( '%d subscribers', 'wp-photo-album-plus' ), $subs ) . '&nbsp;
								</span>
								<span
									class="backendalso"
									style="' . ( wppa_switch( 'feuploadnotify' ) ? '' : 'display:none;' ) . '"
									>' .
									wppa_checkbox($slug2) . '
									<span
										style="float:left" >&nbsp;' .
										__( 'backend also', 'wp-photo-album-plus' ) . '&nbsp;
									</span>' .
									wppa_checkbox($slug3) . '
									<span style="float:left" >&nbsp;' .
										__( 'show thumbnails', 'wp-photo-album-plus' ) . '
									</span>
								</span>';
						wppa_setting_new($slug1, '2', $name, $desc, $html, $help);

						$name = __('Mail on comment', 'wp-photo-album-plus' );
						$desc = __('Enable mailing users when a new comment has been added', 'wp-photo-album-plus' );
						$help = __('When moderation is required, the mails will be sent after approval', 'wp-photo-album-plus' );
						$help .= '<br>' . __('All subscribers will get the email, unless you tick the \'to owner and admin only\' box', 'wp-photo-album-plus' );
						$slug1 = 'wppa_commentnotify';
						$slug2 = 'wppa_commentnotify_limit';
						$subs = count( wppa_index_string_to_array( wppa_get_option( 'wppa_mailinglist_commentnotify', '' ) ) );
						$html = wppa_checkbox($slug1, "wppaSlaveChecked(this,'owneradminonly')") . '
								<span
									style="float:left" >&nbsp;' .
									sprintf( __( '%d subscribers', 'wp-photo-album-plus' ), $subs ) . '&nbsp;
								</span>
								<span
									class="owneradminonly"
									style="' . ( wppa_switch( 'commentnotify' ) ? '' : 'siaplay:none;' ) . '"
									>' .
									wppa_checkbox($slug2) . '
									<span
										style="float:left" >&nbsp;' .
										__( 'to owner and admin only', 'wp-photo-album-plus' ) . '
									</span>
								<span>';
						wppa_setting_new($slug1, '3', $name, $desc, $html, $help);

						$name = __('Mail on previous comment', 'wp-photo-album-plus' );
						$desc = __('Notify users who have commented this photo earlier', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_commentprevious';
						$subs = count( wppa_index_string_to_array( wppa_get_option( 'wppa_mailinglist_commentprevious', '' ) ) );
						$html = wppa_checkbox($slug) . '&nbsp;' . sprintf( __( '%d subscribers', 'wp-photo-album-plus' ), $subs );
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Mail on photo needs moderation', 'wp-photo-album-plus' );
						$desc = __('Notify moderators when a photo needs moderation', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_moderatephoto';
						$subs = count( wppa_index_string_to_array( wppa_get_option( 'wppa_mailinglist_moderatephoto', '' ) ) );
						$html = wppa_checkbox($slug) . '&nbsp;' . sprintf( __( '%d subscribers', 'wp-photo-album-plus' ), $subs );
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Mail on comment needs moderation', 'wp-photo-album-plus' );
						$desc = __('Notify moderators when a comment needs moderation', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_moderatecomment';
						$subs = count( wppa_index_string_to_array( wppa_get_option( 'wppa_mailinglist_moderatecomment', '' ) ) );
						$html = wppa_checkbox($slug) . '&nbsp;' . sprintf( __( '%d subscribers', 'wp-photo-album-plus' ), $subs );
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Mail on approve photo', 'wp-photo-album-plus' );
						$desc = __('Send an email to the owner when a photo is approved', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_photoapproved';
						$subs = count( wppa_index_string_to_array( wppa_get_option( 'wppa_mailinglist_photoapproved', '' ) ) );
						$html = wppa_checkbox($slug) . '&nbsp;' . sprintf( __( '%d subscribers', 'wp-photo-album-plus' ), $subs );
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('Mail on approve comment', 'wp-photo-album-plus' );
						$desc = __('Notify photo owner and commenter of approved comment', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_commentapproved';
						$subs = count( wppa_index_string_to_array( wppa_get_option( 'wppa_mailinglist_commentapproved', '' ) ) );
						$html = wppa_checkbox($slug) . '&nbsp;' . sprintf( __( '%d subscribers', 'wp-photo-album-plus' ), $subs );
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('Mail on mailing (un)subscription', 'wp-photo-album-plus' );
						$desc = __('Notyfy administrators when a user (un)subscribes to a mailinglist', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_subscribenotify';
						$subs = count( wppa_index_string_to_array( wppa_get_option( 'wppa_mailinglist_subscribenotify', '' ) ) );
						$html = wppa_checkbox($slug) . '&nbsp;' . sprintf( __( '%d subscribers', 'wp-photo-album-plus' ), $subs );
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						$name = __('From site', 'wp-photo-album-plus' );
						$desc = __('Enter the subject header', 'wp-photo-album-plus' );
						$help = sprintf( __('This text will be placed between brackets like: %s', 'wp-photo-album-plus' ), '['.str_replace('&#039;', '', get_bloginfo('name') ).']');
						$slug = 'wppa_email_from_site';
						$html = wppa_input($slug,'90%');
						wppa_setting_new($slug, '10', $name, $desc, $html, $help);

						$name = __('From email', 'wp-photo-album-plus' );
						$desc = __('Enter the from email address you want to be used', 'wp-photo-album-plus' );
						$help = __('Be aware of the fact that an email plugin may overrule this setting.', 'wp-photo-album-plus' ) . '<br>' .
								__('Make sure this email address exists.', 'wp-photo-album-plus' );
						$slug = 'wppa_email_from_email';
						$html = wppa_input($slug,'90%');
						wppa_setting_new($slug, '11', $name, $desc, $html, $help);

						$name = __( 'No admin email', 'wp-photo-album-plus' );
						$desc = __( 'Do not send emails on adminbistrator actions', 'wp-photo-album-plus' );
						$help = __( 'When admin adds an album, a photo or a comment, no notification emails will be sent', 'wp-photo-album-plus' );
						$slug = 'wppa_void_admin_email';
						$html = wppa_checkbox( $slug );
						wppa_setting_new($slug, '12', $name, $desc, $html, $help);

						$name = __( 'Email policy', 'wp-photo-album-plus' );
						$desc = __( 'Select either "opt-in" or "opt-out"', 'wp-photo-album-plus' );
						$help = __( 'If you select "opt-in", use the "Notify Me" widget to enable the users to subscribe to emails', 'wp-photo-album-plus' );
						$slug = 'wppa_mailinglist_policy';
						$opts = array( 'opt-in', 'opt-out' );
						$vals = array( 'opt-in', 'opt-out' );
						$html = wppa_select( $slug, $opts, $vals );
						wppa_setting_new($slug, '13', $name, $desc, $html, $help);

						$name = __( 'Email callback url', 'wp-photo-album-plus' );
						$desc = __( 'The link in emails will point to', 'wp-photo-album-plus' );
						$help = __( 'Recommendation', 'wp-photo-album-plus' ) . ': ' .
								__( 'Create a page with shortcode', 'wp-photo-album-plus' ) .
								'<b>[wppa type="landing"]</b>. ';
						$slug = 'wppa_mailinglist_callback_url';
						$html = wppa_input($slug, '90%');
						wppa_setting_new($slug, '14', $name, $desc, $html, $help);

						$name = __('Retry failed mails', 'wp-photo-album-plus' );
						$desc = __('Select number of retries for failed mails', 'wp-photo-album-plus' );
						$help = __('Retries occur at the background every hour', 'wp-photo-album-plus' );
						$slug = 'wppa_retry_mails';
						$html = wppa_number($slug, '1', '24');
						wppa_setting_new($slug, '15', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// Failed mails
					{
						$desc = $wppa_subtab_names[$tab]['2'];

						$coldef = array( 	__('#', 'wp-photo-album-plus' ) => '24px;',
											__('To', 'wp-photo-album-plus' ) => 'auto;',
											__('Subject', 'wp-photo-album-plus' ) => 'auto;',
											__('Message', 'wp-photo-album-plus' ) => 'auto;',
											__('Retry', 'wp-photo-album-plus' ) => '24px;',
											);

						$mails = wppa_get_option( 'wppa_failed_mails', array() );
						if ( count( $mails ) ) {

							wppa_setting_tab_description($desc);
							wppa_setting_box_header_new($tab, $coldef);

							$i = 0;
							foreach( $mails as $mail ) {
								$i++;
								wppa_echo( '
								<tr class="wppa-setting wppa-IX-X" >
									<td>' . $i . '</td>
									<td>' . $mail['to'] . '</td>
									<td>' . $mail['subj'] . '</td>
									<td style="max-width:40%" >' . strip_tags( $mail['message'] ) . '</td>
									<td>' . $mail['retry'] . '</td>
								</tr>' );
							}

							wppa_setting_box_footer_new();
						}
					}
					// Permanently failed mails
					{
						$desc = $wppa_subtab_names[$tab]['3'];

						$mails = wppa_get_option( 'wppa_perm_failed_mails', array() );
						if ( count( $mails ) ) {

							wppa_setting_tab_description($desc);
							wppa_setting_box_header_new($tab, $coldef);

							$i = 0;
							foreach( $mails as $mail ) {
								$i++;
								wppa_echo( '
								<tr class="wppa-setting wppa-IX-Y" >
									<td>' . $i . '</td>
									<td>' . $mail['to'] . '</td>
									<td>' . $mail['subj'] . '</td>
									<td style="max-width:40%" >' . strip_tags( $mail['message'] ) . '</td>
									<td>' . $mail['retry'] . '</td>
								</tr>' );
							}

							wppa_setting_box_footer_new();
						}
					}
				}
				break;

				case 'share': {
					// Social media related settings
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Hide when running', 'wp-photo-album-plus' );
						$desc = __('Hide the SM box when slideshow runs.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_share_hide_when_running';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Show Share Box Widget', 'wp-photo-album-plus' );
						$desc = __('Display the share social media buttons box in widgets.', 'wp-photo-album-plus' );
						$help = __('This setting applies to normal slideshows in widgets, not to the slideshowwidget as that is a slideonly display.', 'wp-photo-album-plus' );
						$slug = 'wppa_share_on_widget';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Show Share Buttons Thumbs', 'wp-photo-album-plus' );
						$desc = __('Display the share social media buttons under thumbnails.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_share_on_thumbs';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Show Share Buttons Mphoto', 'wp-photo-album-plus' );
						$desc = __('Display the share social media buttons on mphoto displays.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_share_on_mphoto';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Void pages share', 'wp-photo-album-plus' );
						$desc = __('Do not show share on these pages', 'wp-photo-album-plus' );
						$help = __('Use this for pages that require the user is logged in', 'wp-photo-album-plus' );
						$slug = 'wppa_sm_void_pages';
						$opts = $opts_page_post;
						$opts[0] = __('--- Select one or more pages ---', 'wp-photo-album-plus' );
						$opts[] = __('--- none ---', 'wp-photo-album-plus' );
						$vals = $vals_page_post;
						$vals[] = '0';
						$html = wppa_select_m($slug, $opts, $vals, '', '', true);
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Show QR Code', 'wp-photo-album-plus' );
						$desc = __('Display the QR code in the share box.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_share_qr';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Show Twitter button', 'wp-photo-album-plus' );
						$desc = __('Display the Twitter button in the share box.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_share_twitter';
						$slug2 = 'wppa_twitter_black';
						$html = wppa_checkbox($slug1) . '<span style="float-left">' . __('Black circle icon', 'wp-photo-album-plus' ) . '</span>' . wppa_checkbox($slug2);
						wppa_setting_new($slug1, '7', $name, $desc, $html, $help);

						$name = __('The creator\'s Twitter account', 'wp-photo-album-plus' );
						$desc = __('The Twitter @username a twitter card should be attributed to.', 'wp-photo-album-plus' );
						$help = __('If you want to share the image directly - by a so called twitter card - you must enter your twitter account name here', 'wp-photo-album-plus' );
						$slug = 'wppa_twitter_account';
						$html = wppa_input($slug, '150px' );
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('Show Pinterest button', 'wp-photo-album-plus' );
						$desc = __('Display the Pintrest button in the share box.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_share_pinterest';
						$slug2 = 'wppa_pinterest_black';
						$html = wppa_checkbox($slug1) . '<span style="float-left">' . __('Black circle icon', 'wp-photo-album-plus' ) . '</span>' . wppa_checkbox($slug2);
						wppa_setting_new($slug1, '10', $name, $desc, $html, $help);

						$name = __('Show LinkedIn button', 'wp-photo-album-plus' );
						$desc = __('Display the LinkedIn button in the share box.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_share_linkedin';
						$slug2 = 'wppa_linkedin_black';
						$html = wppa_checkbox($slug1) . '<span style="float-left">' . __('Black circle icon', 'wp-photo-album-plus' ) . '</span>' . wppa_checkbox($slug2);
						wppa_setting_new($slug1, '11', $name, $desc, $html, $help);

						$name = __('Show Facebook share button', 'wp-photo-album-plus' );
						$desc = __('Display the Facebook button in the share box.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_share_facebook';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '12', $name, $desc, $html, $help);

						$name = __('Show Facebook like button', 'wp-photo-album-plus' );
						$desc = __('Display the Facebook button in the share box.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_facebook_like';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '13', $name, $desc, $html, $help);

						$name = __('Display type', 'wp-photo-album-plus' );
						$desc = __('Select the Facebook button display type.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_fb_display';
						$opts = array( __('Standard', 'wp-photo-album-plus' ), __('Button', 'wp-photo-album-plus' ), __('Button with counter', 'wp-photo-album-plus' ), __('Box with counter', 'wp-photo-album-plus' ) );
						$vals = array( 'standard', 'button', 'button_count', 'box_count' );
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '14', $name, $desc, $html, $help);

						$name = __('Show Facebook comment box', 'wp-photo-album-plus' );
						$desc = __('Display the Facebook comment dialog box in the share box.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_facebook_comments';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '15', $name, $desc, $html, $help);

						$name = __('Facebook User Id', 'wp-photo-album-plus' );
						$desc = __('Enter your facebook user id to be able to moderate comments and sends', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_facebook_admin_id';
						$html = wppa_input($slug, '200px');
						wppa_setting_new($slug, '16', $name, $desc, $html, $help);

						$name = __('Facebook App Id', 'wp-photo-album-plus' );
						$desc = __('Enter your facebook app id to be able to moderate comments and sends', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_facebook_app_id';
						$html = wppa_input($slug, '200px');
						wppa_setting_new($slug, '17', $name, $desc, $html, $help);

						$name = __('Facebook js SDK', 'wp-photo-album-plus' );
						$desc = __('Load Facebook js SDK', 'wp-photo-album-plus' );
						$help = __('Uncheck this box only when there is a conflict with an other plugin that also loads the Facebook js SDK.', 'wp-photo-album-plus' );
						$slug = 'wppa_load_facebook_sdk';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '18', $name, $desc, $html, $help);

						$name = __('Share single image', 'wp-photo-album-plus' );
						$desc = __('Share a link to a single image, not the slideshow.', 'wp-photo-album-plus' );
						$help = __('The sharelink points to a page with a single image rather than to the page with the photo in the slideshow.', 'wp-photo-album-plus' );
						$slug = 'wppa_share_single_image';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '19', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// Search Engine Optimalisation settings
					{
						$desc = $wppa_subtab_names[$tab]['2'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Meta on page', 'wp-photo-album-plus' );
						$desc = __('Meta tags for photos on the page.', 'wp-photo-album-plus' );
						$help = __('If checked, the header of the page will contain metatags that refer to featured photos on the page in the page context.', 'wp-photo-album-plus' );
						$slug = 'wppa_meta_page';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Meta all', 'wp-photo-album-plus' );
						$desc = __('Meta tags for all featured photos.', 'wp-photo-album-plus' );
						$help = __('If checked, the header of the page will contain metatags that refer to all featured photo files.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If you have many featured photos, you might wish to uncheck this item to reduce the size of the page header.', 'wp-photo-album-plus' );
						$slug = 'wppa_meta_all';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Add og meta tags', 'wp-photo-album-plus' );
						$desc = __('Add og meta tags to the page header.', 'wp-photo-album-plus' );
						$help = __('Turning this off may affect the functionality of social media items in the share box that rely on open graph tags information.', 'wp-photo-album-plus' );
						$slug = 'wppa_og_tags_on';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Image Alt attribute type', 'wp-photo-album-plus' );
						$desc = __('Select kind of HTML alt="" content for images.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_alt_type';
						$opts = array( __('--- none ---', 'wp-photo-album-plus' ), __('photo name', 'wp-photo-album-plus' ), __('name without file-ext', 'wp-photo-album-plus' ), __('set in album admin', 'wp-photo-album-plus' ) );
						$vals = array( 'none', 'fullname', 'namenoext', 'custom');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
				}
				break;

				case 'system': {
					// System behaviour related settings
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Modal boxes', 'wp-photo-album-plus' );
						$desc = __('Place Ajax rendered content in modal boxes', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_ajax_render_modal';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Ajax scroll', 'wp-photo-album-plus' );
						$desc = __('Scroll into position after an ajax call changed the page content', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_ajax_scroll';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Non Ajax scroll', 'wp-photo-album-plus' );
						$desc = __('Scroll into position after a wppa link changed the page content', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_non_ajax_scroll';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Update addressline', 'wp-photo-album-plus' );
						$desc = __('Update the addressline after an ajax action or next slide.', 'wp-photo-album-plus' );
						$help = __('If checked, refreshing the page will show the current content and the browsers back and forth arrows will browse the history on the page.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If unchecked, refreshing the page will re-display the content of the original page.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('This will only work on browsers that support history.pushState() and therefor NOT in IE', 'wp-photo-album-plus' );
						$slug = 'wppa_update_addressline';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Ajax method', 'wp-photo-album-plus' );
						$desc = __('The method Ajax will use', 'wp-photo-album-plus' );
						$help = __('Only change this setting when there are links that do not work', 'wp-photo-album-plus' );
						$slug1 = 'wppa_ajax_method';
						$slug2 = 'wppa_ajax_home';
						$opts = array( 	__('Normal', 'wp-photo-album-plus' ),
										__('Frontend and Backend: Backend method', 'wp-photo-album-plus' ),
										__('Frontend: none, Backend: Backend method', 'wp-photo-album-plus' ),
										);
								if ( wppa_is_file( dirname( __FILE__ ) . '/wppa-ajax-front.php' ) ) $opts[] = __('Classic (deprecated)', 'wp-photo-album-plus' );

						$vals = array( 	'normal',
										'admin',
										'none',
										);
								if ( wppa_is_file( dirname( __FILE__ ) . '/wppa-ajax-front.php' ) ) $vals[] = 'extern';

						$html1 = wppa_select($slug1, $opts, $vals);
						$html2 = '<span style="float:left">' . __('Use home url rather than site url', 'wp-photo-album-plus' ) . wppa_checkbox($slug2);
						wppa_setting_new($slug, '5', $name, $desc, $html1.$html2, $help);

						$name = __('Track viewcounts', 'wp-photo-album-plus' );
						$desc = __('Register number of views of albums and photos.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_track_viewcounts';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Track clickcounts', 'wp-photo-album-plus' );
						$desc = __('Register number of clicks on photos that link to an url.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_track_clickcounts';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('Auto page', 'wp-photo-album-plus' );
						$desc = __('Create a wp page for every fullsize image.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_auto_page';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('Auto page display', 'wp-photo-album-plus' );
						$desc = __('The type of display on the autopage pages.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_auto_page_type';
						$opts = array(__('Single photo', 'wp-photo-album-plus' ), __('Media type photo', 'wp-photo-album-plus' ), __('In the style of a slideshow', 'wp-photo-album-plus' ) );
						$vals = array('photo', 'mphoto', 'slphoto');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						$name = __('Auto page links', 'wp-photo-album-plus' );
						$desc = __('The location for the pagelinks.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_auto_page_links';
						$opts = array(__('none', 'wp-photo-album-plus' ), __('At the top', 'wp-photo-album-plus' ), __('At the bottom', 'wp-photo-album-plus' ), __('At top and bottom', 'wp-photo-album-plus' ));
						$vals = array('none', 'top', 'bottom', 'both');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '10', $name, $desc, $html, $help);

						$name = __('Use customized style file', 'wp-photo-album-plus' );
						$desc = __('This feature is highly discouraged.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_use_custom_style_file';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '20', $name, $desc, $html, $help);

						$name = __('Use customized theme file', 'wp-photo-album-plus' );
						$desc = __('This feature is highly discouraged.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_use_custom_theme_file';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '21', $name, $desc, $html, $help);

						$name = __('Enable photo html access', 'wp-photo-album-plus' );
						$desc = __('Creates .htaccess files in .../uploads/wppa/ and .../uploads/wppa/thumbs/', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_cre_uploads_htaccess';
						$opts = array(	__('create \'all access\' .htaccess files', 'wp-photo-album-plus' ),
										__('remove .htaccess files', 'wp-photo-album-plus' ),
										__('create \'no hotlinking\' .htaccess files', 'wp-photo-album-plus' ),
										__('do not change existing .htaccess file(s)', 'wp-photo-album-plus' ),
										);
						$vals = array(	'grant',
										'remove',
										'nohot',
										'custom',
										);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '22', $name, $desc, $html, $help);

						$name = __('Lazy load', 'wp-photo-album-plus' );
						$desc = __('Load photos from the server at the moment they will show up.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_lazy';
						$opts = array(__('Off', 'wp-photo-album-plus' ),__('On pc only', 'wp-photo-album-plus' ),__('On mobile only', 'wp-photo-album-plus' ),__('On both', 'wp-photo-album-plus' ));
						$vals = array('none','pc','mob','all');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '23', $name, $desc, $html, $help);

						$name = __('Thumbs first', 'wp-photo-album-plus' );
						$desc = __('When displaying album content: thumbnails before sub albums.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_thumbs_first';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '24', $name, $desc, $html, $help);

						$name = __('Login links', 'wp-photo-album-plus' );
						$desc = __('You must login to... links to login page.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_login_links';
						$html = wppa_checkbox($slug) . wppa_see_also( 'miscadv', '1', '6', 'login_links' );
						wppa_setting_new($slug, '25', $name, $desc, $html, $help);

						$name = __('Relative urls', 'wp-photo-album-plus' );
						$desc = __('Use relative urls only.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_relative_urls';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '26', $name, $desc, $html, $help);

						$name = __('Capitalize tags and cats', 'wp-photo-album-plus' );
						$desc = __('Format tags and cats to start with one capital character', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_capitalize_tags';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '27', $name, $desc, $html, $help);

						$name = __('Enable Admins Choice', 'wp-photo-album-plus' );
						$desc = __('Enable the creation of zipfiles with selected photos.', 'wp-photo-album-plus' );
						$help = __('Activate the Admins Choice widget to make the zipfiles downloadable.', 'wp-photo-album-plus' );
						$slug = 'wppa_admins_choice';
						$opts = array( __( '--- none ---', 'wp-photo-album-plus' ),
									   __( 'Admins and superusers', 'wp-photo-album-plus' ),
									   __( 'All loggedin users', 'wp-photo-album-plus' )
									   );
						$vals = array( 'none', 'admin', 'login' );
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '28', $name, $desc, $html, $help);

						$name = __('Tag Admins Choice', 'wp-photo-album-plus' );
						$desc = __('Tag photos with Admins Choice user', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_choice_is_tag';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '29', $name, $desc, $html, $help);

						$name = __('Admins choice me only', 'wp-photo-album-plus' );
						$desc = __('Shows the link to the current users zipfile only', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_admins_choice_meonly';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '30', $name, $desc, $html, $help);

						$name = __('Admins choice action', 'wp-photo-album-plus' );
						$desc = __('Select the action to be taken after clicking the "My Choice" link', 'wp-photo-album-plus' );
						$help = __('If set to album, the link is only shown to users who have album admin rights', 'wp-photo-album-plus' );
						$slug = 'wppa_admins_choice_action';
						$opts = array(__('To zipfile', 'wp-photo-album-plus' ), __('To album', 'wp-photo-album-plus' ), __('To album and zip', 'wp-photo-album-plus' ));
						$vals = array('zip', 'album', 'both');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '31', $name, $desc, $html, $help);

						$name = __('Make owner like photoname', 'wp-photo-album-plus' );
						$desc = __('Change the owner to the user who\'s display name equals photoname.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_owner_to_name';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '32', $name, $desc, $html, $help);

						$name = __('No rightclick', 'wp-photo-album-plus' );
						$desc = __('Disable right mouseclick on all images', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_no_rightclick';
						$html = wppa_checkbox( $slug );
						wppa_setting_new($slug, '33', $name, $desc, $html, $help);

						$name = __('Nice scroll on window', 'wp-photo-album-plus' );
						$desc = __('Apply the nice scroller on the browserwindow', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_nicescroll_window';
						$html = wppa_checkbox( $slug ) . wppa_see_also( 'layout', 1, 8 );
						wppa_setting_new($slug, '34', $name, $desc, $html, $help);

						$name = __('Nice scroller options', 'wp-photo-album-plus' );
						$desc = __('The nice scroller configuration options', 'wp-photo-album-plus' );
						$help = __('Enter options, one per line, seperated by commas(,).', 'wp-photo-album-plus' );
						$help .= '<br>' .
									sprintf( __('Click %s here %s for documentation and a full list of available options', 'wp-photo-album-plus' ),
										'<a href="' . WPPA_URL . '/vendor/nicescroll/README.txt" target="_blank" >',
										'</a>'
										) .
								'<br>';
						$slug = 'wppa_nicescroll_opts';
						$html = wppa_textarea( $slug ) . wppa_see_also( 'miscadv', 1, 11 );
						wppa_setting_new($slug, '35', $name, $desc, $html, $help);

						$name = __('Response speed', 'wp-photo-album-plus' );
						$desc = __('The speed of responsive size adjustments', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_response_speed';
						$opts = array( 	__( 'very slow', 'wp-photo-album-plus' ),
										__( 'slow', 'wp-photo-album-plus' ),
										__( 'normal', 'wp-photo-album-plus' ),
										__( 'fast', 'wp-photo-album-plus' ),
										__( 'very fast', 'wp-photo-album-plus' ),
										__( 'off', 'wp-photo-album-plus' ),
										);
						$vals = array( '750', '500', '350', '200', '100', '0' );
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new( $slug, '36', $name, $desc, $html, $help );

						$name = __('Enable request info', 'wp-photo-album-plus' );
						$desc = __('Shows a button under the slideshow image to request info by email', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_request_info';
						$html = wppa_checkbox( $slug );
						wppa_setting_new($slug, '37', $name, $desc, $html, $help);

						$name = __('Dialog text', 'wp-photo-album-plus' );
						$desc = __('The text to display in the dialog box', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_request_info_text';
						$html = wppa_input($slug, '90%');
						wppa_setting_new($slug, '38', $name, $desc, $html, $help);

						/* translators: don't change &#108; into l, to prevent 'Gallery not Gallery' */
						$name = __('Gallery not A&#108;bum', 'wp-photo-album-plus' );
						$desc = __('Use the name Gallery rather than A&#108;bum', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_album_use_gallery';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '39', $name, $desc, $html, $help);

						$name = __('Fullscreen policy', 'wp-photo-album-plus' );
						$desc = __('Select the desired fullscreen policy', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_fs_policy';
						$opts = array(__('--- none ---', 'wp-photo-album-plus' ), __('On lightbox only', 'wp-photo-album-plus' ), __('The entire page', 'wp-photo-album-plus' ) );
						$vals = array('none', 'lightbox', 'global');
						$html = wppa_select($slug, $opts, $vals, '');
						wppa_setting_new($slug, '40', $name, $desc, $html, $help);

						$name = __('Caching overrule', 'wp-photo-album-plus' );
						$desc = __('Overrule caching settings in shortcodes and widgets', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_cache_overrule';
						$opts = array(__('Do not overrule', 'wp-photo-album-plus' ),
									  __('Cache whenever possible', 'wp-photo-album-plus' ),
									  __('Never cache', 'wp-photo-album-plus' ),
									  );
						$vals = array('default', 'always', 'never');
						$html = wppa_select($slug, $opts, $vals, '');
						wppa_setting_new($slug, '41', $name, $desc, $html, $help);

						$name = __('Smart caching max files', 'wp-photo-album-plus');
						$desc = __('Limit the max number of cache files', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_cache_maxfiles';
						$opts = [__('Unlimited', 'wp-photo-album-plus'),10,20,50,100,200,500];
						$vals = [0,10,20,50,100,200,500];
						$html = wppa_select($slug, $opts, $vals, '');
						wppa_setting_new($slug, '41a', $name, $desc, $html, $help);

						$name = __('Delay overrule', 'wp-photo-album-plus' );
						$desc = __('Overrule delay settings in shortcodes', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_delay_overrule';
						$opts = array(__('Do not overrule', 'wp-photo-album-plus' ),
									  __('Delay whenever possible', 'wp-photo-album-plus' ),
									  __('Never delay', 'wp-photo-album-plus' ),
									  );
						$vals = array('default', 'always', 'never');
						$html = wppa_select($slug, $opts, $vals, '');
						wppa_setting_new($slug, '42', $name, $desc, $html, $help);

						$name = __('Use wp_upload_dir()', 'wp-photo-album-plus' );
						$desc = __('Rely upon the information supplied by wp_upload_dir() for wppa file locations', 'wp-photo-album-plus' );
						$help = __('Switch this on only when you are using non-standard locations for uploads etc', 'wp-photo-album-plus' );
						$slug = 'wppa_use_wp_upload_dir_locations';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '43', $name, $desc, $html, $help);

						$opts = array( 1, 2, 5, 10, 20, 50, 100, 200, 500, 1000, 2000, 5000);
						$vals = $opts;
						$name = __('Scroll end delay', 'wp-photo-album-plus');
						$desc = __('The time in milliseconds to wait for the scroll end event to be fired', 'wp-photo-album-plus');
						$help = __('Only change if you have response problems after scrolling', 'wp-photo-album-plus');
						$slug1 = 'wppa_scrollend_delay';
						$slug2 = 'wppa_scrollend_delay_mob';
						$html1 = '<span style="float:left">' . __('on PC', 'wp-photo-album-plus') . ':&nbsp;</span>' . wppa_select($slug1, $opts, $vals) . '&nbsp;';
						$html2 = '<span style="float:left">' . __('on mobile', 'wp-photo-album-plus') . ':&nbsp;</span>' . wppa_select($slug2, $opts, $vals);
						$html = $html1 . $html2;
						wppa_setting_new(array($slug1,$slug2), '44', $name, $desc, $html, $help);

						$name = __('Resize end delay', 'wp-photo-album-plus');
						$desc = __('The time in milliseconds to wait for the resize end event to be fired', 'wp-photo-album-plus');
						$help = __('Only change if you have response problems after resizing the window', 'wp-photo-album-plus');
						$slug1 = 'wppa_resizeend_delay';
						$slug2 = 'wppa_resizeend_delay_mob';
						$html1 = '<span style="float:left">' . __('on PC', 'wp-photo-album-plus') . ':&nbsp;</span>' . wppa_select($slug1, $opts, $vals) . '&nbsp;';
						$html2 = '<span style="float:left">' . __('on mobile', 'wp-photo-album-plus') . ':&nbsp;</span>' . wppa_select($slug2, $opts, $vals);
						$html = $html1 . $html2;
						wppa_setting_new(array($slug1,$slug2), '45', $name, $desc, $html, $help);

						$name = __('Pre-cache albums', 'wp-photo-album-plus');
						$desc = __('Initially loads album data in internal cache to reduce db queries', 'wp-photo-album-plus');
						$help = __('The higher the better, but be carefull to avoid out of memeory errors', 'wp-photo-album-plus');
						$slug = 'wppa_pre_cache_albums';
						$opts = array('10', '20', '50', '100', '200', '500', '1000');
						$html = wppa_select($slug, $opts, $opts);
						wppa_setting_new($slug, '46', $name, $desc, $html, $help);

						$name = __('Pre-cache photos', 'wp-photo-album-plus');
						$desc = __('Initially loads photo data in internal cache to reduce db queries', 'wp-photo-album-plus');
						$help = __('The higher the better, but be carefull to avoid out of memeory errors', 'wp-photo-album-plus');
						$slug = 'wppa_pre_cache_photos';
						$opts = array('100', '200', '500', '1000', '2000', '5000', '10000');
						$html = wppa_select($slug, $opts, $opts);
						wppa_setting_new($slug, '47', $name, $desc, $html, $help);

						$name = __('Show shortcode generators', 'wp-photo-album-plus');
						$desc = __('User roles where to display shortcode generators in editors', 'wp-photo-album-plus');
						$help = __('If you want to limit the display of the shortcode genertor icons, select the appropriate userroles here.', 'wp-photo-album-plus');
						$slug = 'wppa_show_scgens';
						$roles = $wp_roles->roles;
						$opts = array();
						$vals = array();
						$opts[] = '-- '.__('Not limited', 'wp-photo-album-plus' ).' --';
						$vals[] = '';
						foreach (array_keys($roles) as $key) {
							$role = $roles[$key];
							$rolename = translate_user_role( $role['name'] );
							$opts[] = $rolename;
							$vals[] = $key;
						}
						$onch = '';
						$html = wppa_select_m($slug, $opts, $vals, $onch, '', false, '', '220' );
						wppa_setting_new($slug, '48', $name, $desc, $html, $help);
/*
						if ( wppa_get( 'wppa_force_local_js', '' , 'text' ) == 'yes' ) {
							update_option( 'wppa_force_local_js', 'yes' );
						}
						if ( wppa_get( 'wppa_force_local_js', '' , 'text' ) == 'no' ) {
							update_option( 'wppa_force_local_js', 'no' );
						}
						if ( get_option( 'wppa_force_local_js' ) == 'yes' ) {
							$onch = 'alert(\'' . __('Switching force local off', 'wp-photo-album-plus') . '\'); var url=document.location.href; document.location.href=url+\'&wppa_force_local_js=no\';';
						}
						else {
							$onch = 'if (confirm(\''.__('Are you sure?', 'wp-photo-album-plus').'\')) {var url=document.location.href; document.location.href=url+\'&wppa_force_local_js=yes\'}';
						}
						$name = __('Force local js', 'wp-photo-album-plus');
						$desc = __('Use this when you have serious problems with loading js files', 'wp-photo-album-plus');
						$help = __('When this setting is on, you may be sure all javascript is loaded, but it does not obey the wp plugin guidelines', 'wp-photo-album-plus');
						$slug = 'wppa_force_local_js';
						$onch =
						$html = '<input
									type="checkbox" ' .
									( get_option( 'wppa_force_local_js' ) == 'yes' ? 'checked' : '' ) . '
									onchange="' . $onch . '" >';
						wppa_setting_new($slug, '99', $name, $desc, $html, $help);
*/
						wppa_setting_box_footer_new();
					}
				}
				break;

				case 'files': {
					// Original source file related settings
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Keep source files', 'wp-photo-album-plus' );
						$desc = __('Keep the original uploaded and imported photo files.', 'wp-photo-album-plus' );
						$help = __('The files will be kept in a separate directory with subdirectories for each album', 'wp-photo-album-plus' );
						$help .= '<br>'.__('These files can be used to update the photos used in displaying in wppa+ and optionally for downloading original, un-downsized images.', 'wp-photo-album-plus' );
						$slug = 'wppa_keep_source';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Source directory', 'wp-photo-album-plus' );
						$desc = __('The path to the directory where the original photofiles will be saved.', 'wp-photo-album-plus' );
						$help = __('You may change the directory path, but it can not be an url.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('The parent of the directory that you enter here must exist and be writable.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('The directory itsself will be created if it does not exist yet.', 'wp-photo-album-plus' );
						$slug = 'wppa_source_dir';
						$html = wppa_input($slug, '90%');
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Keep sync', 'wp-photo-album-plus' );
						$desc = __('Keep source synchronously with wppa system.', 'wp-photo-album-plus' );
						$help = __('If checked, photos that are deleted from wppa, will also be removed from the source files.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Also, copying or moving photos to different albums, will also copy/move the source files.', 'wp-photo-album-plus' );
						$slug = 'wppa_keep_sync';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
				}
				break;

				case 'new': {
					// New albums / photos related settings
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$opts = array( 	__('--- off ---', 'wp-photo-album-plus' ),
											sprintf( _n('%d hour', '%d hours', '1', 'wp-photo-album-plus' ), '1'),
											sprintf( _n('%d day', '%d days', '1', 'wp-photo-album-plus' ), '1'),
											sprintf( _n('%d day', '%d days', '2', 'wp-photo-album-plus' ), '2'),
											sprintf( _n('%d day', '%d days', '3', 'wp-photo-album-plus' ), '3'),
											sprintf( _n('%d day', '%d days', '4', 'wp-photo-album-plus' ), '4'),
											sprintf( _n('%d day', '%d days', '5', 'wp-photo-album-plus' ), '5'),
											sprintf( _n('%d day', '%d days', '6', 'wp-photo-album-plus' ), '6'),
											sprintf( _n('%d week', '%d weeks', '1', 'wp-photo-album-plus' ), '1'),
											sprintf( _n('%d day', '%d days', '8', 'wp-photo-album-plus' ), '8'),
											sprintf( _n('%d day', '%d days', '9', 'wp-photo-album-plus' ), '9'),
											sprintf( _n('%d day', '%d days', '10', 'wp-photo-album-plus' ), '10'),
											sprintf( _n('%d week', '%d weeks', '2', 'wp-photo-album-plus' ), '2'),
											sprintf( _n('%d week', '%d weeks', '3', 'wp-photo-album-plus' ), '3'),
											sprintf( _n('%d week', '%d weeks', '4', 'wp-photo-album-plus' ), '4'),
											sprintf( _n('%d month', '%d months', '1', 'wp-photo-album-plus' ), '1'),
										);
						$vals = array( 	0,
											60*60,
											60*60*24,
											60*60*24*2,
											60*60*24*3,
											60*60*24*4,
											60*60*24*5,
											60*60*24*6,
											60*60*24*7,
											60*60*24*8,
											60*60*24*9,
											60*60*24*10,
											60*60*24*7*2,
											60*60*24*7*3,
											60*60*24*7*4,
											60*60*24*30,
										);

						$name = __('New Album', 'wp-photo-album-plus' );
						$desc = __('Maximum time an album is indicated as New', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_max_album_newtime';
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('New Photo', 'wp-photo-album-plus' );
						$desc = __('Maximum time a photo is indicated as New', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_max_photo_newtime';
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Modified Album', 'wp-photo-album-plus' );
						$desc = __('Maximum time an album is indicated as Modified', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_max_album_modtime';
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Modified Photo', 'wp-photo-album-plus' );
						$desc = __('Maximum time a photo is indicated as Modified', 'wp-photo-album-plus' );
						$help = __('If you tick the checkbox, a modified photo will also set the album as modified', 'wp-photo-album-plus' );
						$slug1 = 'wppa_max_photo_modtime';
						$html1 = wppa_select($slug1, $opts, $vals);
						$slug2 = 'wppa_pup_is_aup';
						$html2 = '<span style="float:left">'.__( 'Mod album also' ).'</span>'.wppa_checkbox($slug2);
						wppa_setting_new($slug, '4', $name, $desc, $html1.$html2, $help);

						$name = __('First photo', 'wp-photo-album-plus' );
						$desc = __('Indicate the users very first upload', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_show_first';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '4a', $name, $desc, $html, $help);

						$name = __('Use text labels', 'wp-photo-album-plus' );
						$desc = __('Use editable text for the New and Modified labels', 'wp-photo-album-plus' );
						$help = __('If UNticked, you can specify the urls for custom images to be used.', 'wp-photo-album-plus' );
						$slug = 'wppa_new_mod_label_is_text';
						$onch = "wppaSlaveChecked(this,'labelistext');wppaUnSlaveChecked(this,'labelisnottext');";
						$html = wppa_checkbox($slug,$onch);
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$opts = array(
										__('Red', 'wp-photo-album-plus' ),
										__('Orange', 'wp-photo-album-plus' ),
										__('Yellow', 'wp-photo-album-plus' ),
										__('Green', 'wp-photo-album-plus' ),
										__('Blue', 'wp-photo-album-plus' ),
										__('Purple', 'wp-photo-album-plus' ),
										__('Black/white', 'wp-photo-album-plus' ),
									);
						$vals = array(
										'red',
										'orange',
										'yellow',
										'green',
										'blue',
										'purple',
										'black',
									);

						$show = wppa_switch( 'new_mod_label_is_text' );
						$clas = 'labelistext';

						$name = __('New label', 'wp-photo-album-plus' );
						$desc = __('Specify the "New" indicator details.', 'wp-photo-album-plus' );
						$help = __('If you use qTranslate, the text may be multilingual.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_new_label_text';
						$slug2 = 'wppa_new_label_color';
						$html1 = '<span style="float:left">'.__('Text', 'wp-photo-album-plus' ).': </span>'.wppa_input($slug1, '150px');
						$html2 = '<span style="float:left">'.__('Color', 'wp-photo-album-plus' ).': </span>'.wppa_select($slug2, $opts, $vals);
						wppa_setting_new($slug1, '6', $name, $desc, $html1.' '.$html2, $help, $show, $clas);

						$name = __('Modified label', 'wp-photo-album-plus' );
						$desc = __('Specify the "Modified" indicator details.', 'wp-photo-album-plus' );
						$help = __('If you use qTranslate, the text may be multilingual.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_mod_label_text';
						$slug2 = 'wppa_mod_label_color';
						$html1 = '<span style="float:left">'.__('Text', 'wp-photo-album-plus' ).': </span>'.wppa_input($slug1, '150px');
						$html2 = '<span style="float:left">'.__('Color', 'wp-photo-album-plus' ).': </span>'.wppa_select($slug2, $opts, $vals);
						wppa_setting_new($slug1, '7', $name, $desc, $html1.' '.$html2, $help, $show, $clas);

						$name = __('First label', 'wp-photo-album-plus' );
						$desc = __('Specify the "First" indicator details.', 'wp-photo-album-plus' );
						$help = __('If you use qTranslate, the text may be multilingual.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_first_label_text';
						$slug2 = 'wppa_first_label_color';
						$html1 = '<span style="float:left">'.__('Text', 'wp-photo-album-plus' ).': </span>'.wppa_input($slug1, '150px');
						$html2 = '<span style="float:left">'.__('Color', 'wp-photo-album-plus' ).': </span>'.wppa_select($slug2, $opts, $vals);
						wppa_setting_new($slug1, '7a', $name, $desc, $html1.' '.$html2, $help, $show, $clas);

						$show = ! wppa_switch( 'new_mod_label_is_text' );
						$clas = 'labelisnottext';

						$name = __('New label', 'wp-photo-album-plus' );
						$desc = __('Specify the "New" indicator url.', 'wp-photo-album-plus' );
						$help = ' ';
						$slug = 'wppa_new_label_url';
						$html = wppa_input($slug, '300px');
						wppa_setting_new($slug, '8', $name, $desc, $html, $help, $show, $clas);

						$name = __('Modified label', 'wp-photo-album-plus' );
						$desc = __('Specify the "Modified" indicator url.', 'wp-photo-album-plus' );
						$help = ' ';
						$slug = 'wppa_mod_label_url';
						$html = wppa_input($slug, '300px');
						wppa_setting_new($slug, '9', $name, $desc, $html, $help, $show, $clas);

						$name = __('First label', 'wp-photo-album-plus' );
						$desc = __('Specify the "First" indicator url.', 'wp-photo-album-plus' );
						$help = ' ';
						$slug = 'wppa_first_label_url';
						$html = wppa_input($slug, '300px');
						wppa_setting_new($slug, '9a', $name, $desc, $html, $help, $show, $clas);

						$name = __('Limit LasTen New', 'wp-photo-album-plus' );
						$desc = __('Limits the LasTen photos to those that are \'New\', or newly modified.', 'wp-photo-album-plus' );
						$help = __('If you tick this box and configured the new photo time, you can even limit the number in LasTen Count, or set that number to an unlikely high value.', 'wp-photo-album-plus' );
						$slug = 'wppa_lasten_limit_new';
						$html = wppa_checkbox($slug) . wppa_see_also( 'widget', '1', '8' );
						wppa_setting_new($slug, '10', $name, $desc, $html, $help);

						$name = __('LasTen use Modified', 'wp-photo-album-plus' );
						$desc = __('Use the time modified rather than time upload for LasTen widget/shortcode.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_lasten_use_modified';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '11', $name, $desc, $html, $help);

						$name = __('Apply Newphoto desc', 'wp-photo-album-plus' );
						$desc = __('Give each new photo a standard description.', 'wp-photo-album-plus' );
						$help = __('If checked, each new photo will get the description (template) as specified in the next item.', 'wp-photo-album-plus' );
						$slug = 'wppa_apply_newphoto_desc';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '12', $name, $desc, $html, $help);

						$name = __('New photo desc', 'wp-photo-album-plus' );
						$desc = __('The description (template) to add to a new photo.', 'wp-photo-album-plus' );
						$help = __('Enter the default description.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If you use html, please check item B-1 of this table.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If you tick the checkbox, linebreaks and redundand spaces will be removed.', 'wp-photo-album-plus' );
						$slug = 'wppa_newphoto_description';
						$slug2 = 'wppa_compress_newdesc';
						$html = wppa_textarea($slug, $name) .
								'<br>' .
								__('Compress', 'wp-photo-album-plus' ) .
								wppa_checkbox($slug2);
						wppa_setting_new($slug, '13', $name, $desc, $html, $help);

						$name = __('New photo owner', 'wp-photo-album-plus' );
						$desc = __('The owner of a new uploaded photo.', 'wp-photo-album-plus' );
						$help = __('If you leave this blank, the uploader will be set as the owner', 'wp-photo-album-plus' );
						$slug = 'wppa_newphoto_owner';
						$html = wppa_input($slug, '50px', '', __('leave blank or enter login name', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '14', $name, $desc, $html, $help);

						$name = __('Upload limit', 'wp-photo-album-plus' );
						$desc = __('New albums are created with this upload limit.', 'wp-photo-album-plus' );
						$help = __('Administrators can change the limit settings in the "Edit Album Information" admin page.', 'wp-photo-album-plus' );
						$help .= '<br>'.(__('A value of 0 means: no limit.', 'wp-photo-album-plus' ));
						$slug = 'wppa_upload_limit_count';
						$html = wppa_input($slug, '50px', '', __('photos', 'wp-photo-album-plus' ));
						$slug = 'wppa_upload_limit_time';
						$opts = array( 	__('for ever', 'wp-photo-album-plus' ),
											__('per hour', 'wp-photo-album-plus' ),
											__('per day', 'wp-photo-album-plus' ),
											__('per week', 'wp-photo-album-plus' ),
											__('per month', 'wp-photo-album-plus' ), 	// 30 days
											__('per year', 'wp-photo-album-plus' ));	// 364 days
						$vals = array( '0', '3600', '86400', '604800', '2592000', '31449600');
						$html .= wppa_select($slug, $opts, $vals);
						wppa_setting_new(false, '15', $name, $desc, $html, $help);

						$name = __('Default parent', 'wp-photo-album-plus' );
						$desc = __('The parent album of new albums.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_default_parent';
						$opts = array( __('--- none ---', 'wp-photo-album-plus' ), __('--- separate ---', 'wp-photo-album-plus' ) );
						$vals = array( '0', '-1');
						$albs = $wpdb->get_results( "SELECT id, name FROM $wpdb->wppa_albums ORDER BY name", ARRAY_A );
						if ( $albs ) {
							foreach ( $albs as $alb ) {
								$opts[] = __(stripslashes($alb['name']), 'wp-photo-album-plus' );
								$vals[] = $alb['id'];
							}
						}
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '16', $name, $desc, $html, $help);

						$name = __('Default parent always', 'wp-photo-album-plus' );
						$desc = __('The parent album of new albums is always the default, except for administrators.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_default_parent_always';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '17', $name, $desc, $html, $help);

						$name = __('Grant an album', 'wp-photo-album-plus' );
						$desc = __('Create an album for each user logging in.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_grant_an_album';
						$onch = "wppaSlaveChecked(this,'granton');";
						$onch .= "wppaSlaveSelectedAndSwitch('wppa_grant_parent_sel_method-selectionbox','grant_an_album','selectionbox');" .
								"wppaSlaveSelectedAndSwitch('wppa_grant_parent_sel_method-category','grant_an_album','category');" .
								"wppaSlaveSelectedAndSwitch('wppa_grant_parent_sel_method-indexsearch','grant_an_album','indexsearch');";
						$html = wppa_checkbox($slug, $onch);
						wppa_setting_new($slug, '18', $name, $desc, $html, $help);

						$show = wppa_switch( 'grant_an_album' );
						$clas = 'granton';

						$name = __('Grant album name', 'wp-photo-album-plus' );
						$desc = __('The name to be used for the album.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_grant_name';
						$opts = array(__('Login name', 'wp-photo-album-plus' ), __('Display name', 'wp-photo-album-plus' ), __('Id', 'wp-photo-album-plus' ), __('Firstname Lastname', 'wp-photo-album-plus' ));
						$vals = array('login', 'display', 'id', 'firstlast');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '19', $name, $desc, $html, $help, $show, $clas);

						$name = __('Grant album description', 'wp-photo-album-plus' );
						$desc = __('The description to be used for the album.', 'wp-photo-album-plus' );
						$help = __('You can use "$user" as placeholder for the name; it will be replaced by the name of the album', 'wp-photo-album-plus' );
						$slug = 'wppa_grant_desc';
						$html = wppa_input($slug, '400px;');
						wppa_setting_new($slug, '19a', $name, $desc, $html, $help, $show, $clas);

						$name = __('Grant parent selection method', 'wp-photo-album-plus' );
						$desc = __('The way the grant parents are defined.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_grant_parent_sel_method';
						$opts = array(	__('An album (multi)selectionbox', 'wp-photo-album-plus' ),
										__('An album category', 'wp-photo-album-plus' ),
										__('An index search token', 'wp-photo-album-plus' ),
										);
						$vals = array(	'selectionbox',
										'category',
										'indexsearch'
										);
						$onch = "wppaSlaveSelectedAndSwitch('wppa_grant_parent_sel_method-selectionbox','grant_an_album','selectionbox');" .
								"wppaSlaveSelectedAndSwitch('wppa_grant_parent_sel_method-category','grant_an_album','category');" .
								"wppaSlaveSelectedAndSwitch('wppa_grant_parent_sel_method-indexsearch','grant_an_album','indexsearch');";

						$html = wppa_select($slug, $opts, $vals, $onch);
						wppa_setting_new($slug, '20', $name, $desc, $html, $help, $show, $clas);

						$name = __('Grant parent', 'wp-photo-album-plus' );
						$desc = __('The parent album(s) of the auto created albums.', 'wp-photo-album-plus' );
						$help = (__('You may select multiple albums. All logged in visitors will get their own sub album in each granted parent.', 'wp-photo-album-plus' ));
						$slug = 'wppa_grant_parent';
						$opts = array( __('--- none ---', 'wp-photo-album-plus' ), __('--- separate ---', 'wp-photo-album-plus' ) );
						$vals = array( 'zero', '-1');
						$albs = $wpdb->get_results( "SELECT id, name FROM $wpdb->wppa_albums ORDER BY name", ARRAY_A );
						if ( $albs ) {
							foreach ( $albs as $alb ) {
								$opts[] = __(stripslashes($alb['name']), 'wp-photo-album-plus' );
								$vals[] = $alb['id'];
							}
						}
						$html = wppa_select_m($slug, $opts, $vals);//, '', '', true);
						wppa_setting_new($slug, '21', $name, $desc, $html, $help, wppa_switch( 'grant_an_album' ) && wppa_opt( 'grant_parent_sel_method' ) == 'selectionbox','selectionbox granton' );

						$name = __('Grant parent category', 'wp-photo-album-plus' );
						$desc = __('The category of the parent album(s) of the auto created albums.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_grant_parent';
						$catlist = wppa_get_catlist();
						$opts = array();
						foreach( $catlist as $cat ) {
							$opts[] = $cat['cat'];
						}
						$vals = $opts;
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '22', $name, $desc, $html, $help, wppa_switch( 'grant_an_album' ) && wppa_opt( 'grant_parent_sel_method' ) == 'category','category granton' );

						$name = __('Grant parent index token', 'wp-photo-album-plus' );
						$desc = __('The index token that defines the parent album(s) of the auto created albums.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_grant_parent';
						$html = wppa_input($slug, '150px');
						wppa_setting_new($slug, '23', $name, $desc, $html, $help, wppa_switch( 'grant_an_album' ) && wppa_opt( 'grant_parent_sel_method' ) == 'indexsearch','indexsearch granton' );

						$name = __('Grant categories', 'wp-photo-album-plus' );
						$desc = __('The categories a new granted album will get.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_grant_cats';
						$html = wppa_input($slug, '150px');
						wppa_setting_new($slug, '24', $name, $desc, $html, $help, $show, $clas);

						$name = __('Grant tags', 'wp-photo-album-plus' );
						$desc = __('The default tags the photos in a new granted album will get.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_grant_tags';
						$html = wppa_input($slug, '150px');
						wppa_setting_new($slug, '25', $name, $desc, $html, $help, $show, $clas);

						$name = __('Grant restrict', 'wp-photo-album-plus' );
						$desc = __('Only create albums for users with Album Admin rights', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_grant_restrict';
						$html = wppa_checkbox($slug) . wppa_see_also( 'admin', '1' );
						wppa_setting_new($slug, '26', $name, $desc, $html, $help, $show, $clas);

						$name = __('Iptc 025 keywords to tags', 'wp-photo-album-plus' );
						$desc = __('Convert IPTC025 keywords to tags during upload.', 'wp-photo-album-plus' );
						$help = __('Saving IPTC data must be on for this feature', 'wp-photo-album-plus' );
						$slug = 'wppa_ipc025_to_tags';
						$html = wppa_checkbox( $slug );
						wppa_setting_new($slug, '27', $name, $desc, $html, $help);

						$name = __('Default photo name', 'wp-photo-album-plus' );
						$desc = __('Select the way the name of a new uploaded photo should be determined.', 'wp-photo-album-plus' );
						$help = __('If you select an IPTC Tag and it is not found, the filename will be used instead.', 'wp-photo-album-plus' );
						$slug = 'wppa_newphoto_name_method';
						$opts = array( 	__('Filename', 'wp-photo-album-plus' ),
										__('Filename without extension', 'wp-photo-album-plus' ),
										__('Filename without extension, spaces for hyphens', 'wp-photo-album-plus' ),
										__('IPTC Tag 2#005 (Graphic name)', 'wp-photo-album-plus' ),
										__('IPTC Tag 2#120 (Caption)', 'wp-photo-album-plus' ),
										__('No name at all', 'wp-photo-album-plus' ),
										__('Photo w#id (literally)', 'wp-photo-album-plus' ),
									);
						$vals = array( 	'filename',
										'noext',
										'noextspace',
										'2#005',
										'2#120',
										'none',
										'Photo w#id'
									);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '29', $name, $desc, $html, $help);

						$name = __('Default coverphoto', 'wp-photo-album-plus' );
						$desc = __('Name of photofile to become cover image', 'wp-photo-album-plus' );
						$help = __('If you name a photofile like this setting before upload, it will become the coverimage automatically.', 'wp-photo-album-plus' );
						$slug = 'wppa_default_coverimage_name';
						$html = wppa_input($slug, '150px');
						wppa_setting_new($slug, '30', $name, $desc, $html, $help);

						$name = __('Copy Timestamp', 'wp-photo-album-plus' );
						$desc = __('Copy timestamp when copying photo.', 'wp-photo-album-plus' );
						$help = __('If checked, the copied photo is not "new"', 'wp-photo-album-plus' );
						$slug = 'wppa_copy_timestamp';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '31', $name, $desc, $html, $help);

						$name = __('Copy Owner', 'wp-photo-album-plus' );
						$desc = __('Copy the owner when copying photo.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_copy_owner';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '32', $name, $desc, $html, $help);

						$name = __('Copy Custom', 'wp-photo-album-plus' );
						$desc = __('Copy the custom fields when copying photo.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_copy_custom';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '33', $name, $desc, $html, $help);

						$name = __('FE Albums public', 'wp-photo-album-plus' );
						$desc = __('Frontend created albums are --- public ---', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_frontend_album_public';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '34', $name, $desc, $html, $help);

						$name = __('Default album linktype', 'wp-photo-album-plus' );
						$desc = __('The album linktype for new albums', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_default_album_linktype';
						$opts = array( 	__('the sub albums and thumbnails', 'wp-photo-album-plus' ),
										__('the sub albums', 'wp-photo-album-plus' ),
										__('the thumbnails', 'wp-photo-album-plus' ),
										__('the album photos as slideshow', 'wp-photo-album-plus' ),
										__('no link at all', 'wp-photo-album-plus' )
									);

						$vals = array( 	'content',
										'albums',
										'thumbs',
										'slide',
										'none'
									);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '35', $name, $desc, $html, $help);

						$name = __('Sanitize files', 'wp-photo-album-plus' );
						$desc = __('Sanitize filenames during import/upload', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_sanitize_import';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '36', $name, $desc, $html, $help);

						$name = __('Remove accents', 'wp-photo-album-plus' );
						$desc = __('Remove accents from filenames during import/upload', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_remove_accents';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '37', $name, $desc, $html, $help);

						$name = __('Default photo status', 'wp-photo-album-plus' );
						$desc = __('The status new photos will have', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_status_new';

						$opts = array(	__( 'Publish', 'wp-photo-album-plus' ),
										__( 'Pending', 'wp-photo-album-plus' ),
										__( 'Featured', 'wp-photo-album-plus' ),
										__( 'Private', 'wp-photo-album-plus' ),
									);
						$vals = array( 'publish',
									   'pending',
									   'featured',
									   'private',
									);
						$html = wppa_select( $slug, $opts, $vals);
						wppa_setting_new($slug, '38', $name, $desc, $html, $help);

						$name = __('Owner of #posttitle album', 'wp-photo-album-plus' );
						$desc = __('The owner of a new album created by shortcode attribute album="#posttitle"', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_posttitle_owner';
						$opts = array(  __('The owner of the post', 'wp-photo-album-plus'),
										__('Public', 'wp-photo-album-plus'),
									);
						$vals = array( '--- postauthor ---',
									   '--- public ---',
									);
						$html = wppa_select( $slug, $opts, $vals);
						wppa_setting_new($slug, '39', $name, $desc, $html, $help);

						$name = __('BE Albums public', 'wp-photo-album-plus' );
						$desc = __('Backemd created albums are --- public ---', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_backend_album_public';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '40', $name, $desc, $html, $help);

						if ( function_exists( 'ewww_image_optimizer') ) {
							$name = __('Optimize new images', 'wp-photo-album-plus');
							$desc = __('Use EWWW image optimizer on upload/import', 'wp-photo-album-plus' );
							$help = wppa_see_also('maintenance', '2', '19');
							$slug = '';
							$html = '<input type="checkbox" checked disabled>';
							wppa_setting_new($slug, '41', $name, $desc, $html, $help);
						}

						wppa_setting_box_footer_new();
					}
				}
				break;

				case 'admin': {
					// WPPA+ related roles and capabilities
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);

						$coldef = array(	__('Role', 'wp-photo-album-plus' ) => 'auto;',
											__('Album Admin', 'wp-photo-album-plus' ) => 'auto;',
											__('Upload', 'wp-photo-album-plus' ) => 'auto;',
											__('Import', 'wp-photo-album-plus' ) => 'auto;',
											__('Moderate', 'wp-photo-album-plus' ) => 'auto;',
											__('Export', 'wp-photo-album-plus' ) => 'auto;',
											__('Settings', 'wp-photo-album-plus' ) => 'auto;',
											__('Comments', 'wp-photo-album-plus' ) => 'auto;',
											__('Documentation', 'wp-photo-album-plus' ) => 'auto;',
											__('Tag edit', 'wp-photo-album-plus' ) => 'auto;',
											__('Sequence edit', 'wp-photo-album-plus' ) => 'auto',
											__('Email admin', 'wp-photo-album-plus' ) => 'auto',
											__('Membership', 'wp-photo-album-plus' ) => 'auto;',
											);


						wppa_setting_box_header_new($tab, $coldef);

						$wppacaps = array(	'wppa_admin',
											'wppa_upload',
											'wppa_import',
											'wppa_moderate',
											'wppa_export',
											'wppa_settings',
											'wppa_comments',
											'wppa_help',
											'wppa_edit_tags',
											'wppa_edit_sequence',
											'wppa_edit_email',
											'wppa_medal',
											);
						$opts = array( '',
										__('bronze', 'wp-photo-album-plus' ),
										__('silver', 'wp-photo-album-plus' ),
										__('gold', 'wp-photo-album-plus' ),
										__('plus', 'wp-photo-album-plus' ),
										__('basic', 'wp-photo-album-plus' ),
										);
						$vals = array( 	'none',
										'bronze',
										'silver',
										'gold',
										'plus',
										'basic'
										);

						$roles = $wp_roles->roles;

						foreach (array_keys($roles) as $key) {
							$role = $roles[$key];
							$rolename = translate_user_role( $role['name'] );

							wppa_echo( '
							<tr class="wppa-setting-new" >
								<td>' . $rolename . '</td>' );
								$caps = $role['capabilities'];

								for ($i = 0; $i < count($wppacaps) - 1; $i++) {
									if (isset($caps[$wppacaps[$i]])) {
										$yn = $caps[$wppacaps[$i]] ? true : false;
									}
									else $yn = false;
									$enabled = ( $key != 'administrator' );
									wppa_echo( '
									<td>' . wppa_checkbox_e('caps-'.$wppacaps[$i].'-'.$key, $yn, '', '', $enabled) . '</td>' );
								};

								wppa_echo( '<td>' . wppa_select($wppacaps[count($wppacaps) - 1].'-'.$key, $opts, $vals) . '</td>' );

							wppa_echo( '</tr>' );
						}

						wppa_setting_box_footer_new();
					}
					// Frontend create albums and upload Photos enabling and limiting settings
					{
						$desc = $wppa_subtab_names[$tab]['2'];
						wppa_setting_tab_description($desc);
						$coldef = array(	'#' => 'auto;',
											__('Name', 'wp-photo-album-plus' ) => 'auto;',
											__('Description', 'wp-photo-album-plus' ) => 'auto;',
											__('Setting', 'wp-photo-album-plus' ) => 'auto;',
											__('Period', 'wp-photo-album-plus' ) => 'auto;',
											__('Help', 'wp-photo-album-plus' ) => '24px;',
										);
						wppa_setting_box_header_new($tab, $coldef);

						$name = __('User create albums', 'wp-photo-album-plus' );
						$desc = __('Enable frontend album creation.', 'wp-photo-album-plus' );
						$help = __('If you check this item, frontend album creation will be enabled.', 'wp-photo-album-plus' );
						$slug = 'wppa_user_create_on';
						$onch = "wppaSlaveChecked(this,'userroles');";
						$html1 = wppa_checkbox($slug, $onch);
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('User create roles', 'wp-photo-album-plus' );
						$desc = __('Optionally limit access to selected userroles', 'wp-photo-album-plus' );
						$help = __('Adminstrators and superusers are automatically included', 'wp-photo-album-plus' );
						$slug = 'wppa_user_create_roles';
						$roles = $wp_roles->roles;
						$opts = array();
						$vals = array();
						$opts[] = '-- '.__('Not limited', 'wp-photo-album-plus' ).' --';
						$vals[] = '';
						foreach (array_keys($roles) as $key) {
							$role = $roles[$key];
							if ( $key != 'administrator' ) {
								$rolename = translate_user_role( $role['name'] );
								$opts[] = $rolename;
								$vals[] = $key;
							}
						}
						$onch = '';
						$html1 = wppa_select_m($slug, $opts, $vals, $onch, '', false, '', '220' );
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new($slug, '2', $name, $desc, $html, $help, wppa_switch( 'user_create_on' ), 'userroles');

						$name = __('Max user albums', 'wp-photo-album-plus' );
						$desc = __('The max number of albums a user can create.', 'wp-photo-album-plus' );
						$help = __('The maximum number of albums a user can create when he is not admin', 'wp-photo-album-plus' );
						$help .= '<br>'.__('A number of 0 means No limit', 'wp-photo-album-plus' );
						$slug = 'wppa_max_albums';
						$html1 = wppa_input( $slug, '50px', '', __( 'albums', 'wp-photo-album-plus' ) );
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new($slug, '3', $name, $desc, $html, $help, wppa_switch( 'user_create_on' ), 'userroles');

						$name = __('Max nesting level', 'wp-photo-album-plus' );
						$desc = __('Limits the max nesting level for frontend created albums', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_user_create_max_level';
						$onch = '';
						$html1 = wppa_number($slug, '0', '99');
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new($slug, '4', $name, $desc, $html, $help, wppa_switch( 'user_create_on' ), 'userroles');

						$name = __('User edit album', 'wp-photo-album-plus' );
						$desc = __('Enable frontend edit album name and description.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_user_album_edit_on';
						$html1 = wppa_checkbox($slug);
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('User delete albums', 'wp-photo-album-plus' );
						$desc = __('Enable frontend album deletion', 'wp-photo-album-plus' );
						$help = __('If you check this item, frontend album deletion will be enabled.', 'wp-photo-album-plus' );
						$slug = 'wppa_user_destroy_on';
						$onchange = '';
						$html1 = wppa_checkbox($slug, $onchange);
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('User create albums captcha', 'wp-photo-album-plus' );
						$desc = __('User must answer security question.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_user_create_captcha';
						$html1 = wppa_checkbox($slug);
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('Fe limts per album', 'wp-photo-album-plus' );
						$desc = __('The following limits apply to individual albums', 'wp-photo-album-plus' );
						$help = __('If this box is ticked, users can upload the limit on every album they have fe upload rights to', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If this box is unticked, the limits apply to all the users uploads in the system regardless of the album(s)', 'wp-photo-album-plus' );
						$slug = 'wppa_role_limit_per_album';
						$html1 = wppa_checkbox($slug);
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						// User upload limits
						$opts = array( 	__('for ever', 'wp-photo-album-plus' ),
											__('per hour', 'wp-photo-album-plus' ),
											__('per day', 'wp-photo-album-plus' ),
											__('per week', 'wp-photo-album-plus' ),
											__('per month', 'wp-photo-album-plus' ), 	// 30 days
											__('per year', 'wp-photo-album-plus' ));	// 364 days
						$vals = array( '0', '3600', '86400', '604800', '2592000', '31449600');

						$roles = $wp_roles->roles;
						unset ( $roles['administrator'] );
						foreach (array_keys($roles) as $role) {
							$t_role = isset( $roles[$role]['name'] ) ? translate_user_role( $roles[$role]['name'] ) : $role;
							if ( wppa_get_option('wppa_'.$role.'_upload_limit_count', 'nil') == 'nil') wppa_update_option('wppa_'.$role.'_upload_limit_count', '0');
							if ( wppa_get_option('wppa_'.$role.'_upload_limit_time', 'nil') == 'nil') wppa_update_option('wppa_'.$role.'_upload_limit_time', '0');
							$name = sprintf(__('Upload limit %s', 'wp-photo-album-plus' ), $t_role);
							$desc = sprintf(__('Limit upload capacity for the user role %s.', 'wp-photo-album-plus' ), $t_role);
							$help = __('This limitation only applies to frontend uploads when the same userrole does not have the Upload checkbox checked.', 'wp-photo-album-plus' );
							$help .= '<br>'.__('A value of 0 means: no limit.', 'wp-photo-album-plus' ) . wppa_see_also( 'admin', '1' );
							$slug1 = 'wppa_'.$role.'_upload_limit_count';
							$html1 = wppa_input($slug1, '50px', '', __('photos', 'wp-photo-album-plus' ));
							$slug2 = 'wppa_'.$role.'_upload_limit_time';
							$html2 = wppa_select($slug2, $opts, $vals);
							$html = array( $html1, $html2 );
							wppa_setting_new(false, '9.'.$t_role, $name, $desc, $html, $help);
						}

						foreach (array_keys($roles) as $role) {
							$t_role = isset( $roles[$role]['name'] ) ? translate_user_role( $roles[$role]['name'] ) : $role;
							if ( wppa_get_option('wppa_'.$role.'_album_limit_count', 'nil') == 'nil') wppa_update_option('wppa_'.$role.'_album_limit_count', '0');
							$name = sprintf(__('Album limit %s', 'wp-photo-album-plus' ), $t_role);
							$desc = sprintf(__('Limit number of albums for the user role %s.', 'wp-photo-album-plus' ), $t_role);
							$help = __('This limitation only applies to frontend create albums when the same userrole does not have the Album admin checkbox checked.', 'wp-photo-album-plus' );
							$help .= '<br>'.__('A value of 0 means: no limit.', 'wp-photo-album-plus' ) . wppa_see_also( 'admin', '1' );
							$slug1 = 'wppa_'.$role.'_album_limit_count';
							$html1 = wppa_input($slug1, '50px', '', __('albums', 'wp-photo-album-plus' ));
							$slug2 = '';
							$html2 = '';
							$html = array( $html1, $html2 );
							wppa_setting_new(false, '10.'.$t_role, $name, $desc, $html, $help);
						}

						$name = __('Upload one only', 'wp-photo-album-plus' );
						$desc = __('Non admin users can upload only one photo at a time.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_upload_one_only';
						$html1 = wppa_checkbox($slug);
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new($slug, '11', $name, $desc, $html, $help);

						$name = __('Upload moderation', 'wp-photo-album-plus' );
						$desc = __('Uploaded photos need moderation.', 'wp-photo-album-plus' );
						$help = __('If checked, photos uploaded by users who do not have photo album admin access rights need moderation.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Users who have photo album admin access rights can change the photo status to publish or featured.', 'wp-photo-album-plus' );
						$help .= '<br>'.wppa_see_also( 'admin', '1' );
						$slug = 'wppa_upload_moderate';
						$html1 = wppa_checkbox($slug);
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new($slug, '12', $name, $desc, $html, $help);

						$name = __('FE Upload private', 'wp-photo-album-plus' );
						$desc = __('Front-end uploaded photos status is set to private.', 'wp-photo-album-plus' );
						$help = __('This setting overrules Upload moderation.', 'wp-photo-album-plus' );
						$slug = 'wppa_fe_upload_private';
						$html1 = wppa_checkbox($slug);
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new($slug, '13', $name, $desc, $html, $help);

						$name = __('Min size in pixels', 'wp-photo-album-plus' );
						$desc = __('Min size for height and width for front-end uploads.', 'wp-photo-album-plus' );
						$help = __('Enter the minimum size.', 'wp-photo-album-plus' );
						$slug = 'wppa_upload_frontend_minsize';
						$html1 = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new($slug, '14', $name, $desc, $html, $help);

						$name = __('Max size in pixels', 'wp-photo-album-plus' );
						$desc = __('Max size for height and width for front-end uploads.', 'wp-photo-album-plus' );
						$help = __('Enter the maximum size. 0 is unlimited', 'wp-photo-album-plus' );
						$slug = 'wppa_upload_frontend_maxsize';
						$html1 = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new($slug, '15', $name, $desc, $html, $help);

						$name = __('Fe alert', 'wp-photo-album-plus' );
						$desc = __('Show alertbox on front-end.', 'wp-photo-album-plus' );
						$help = __('Errors are always reported, credit points only when --- none --- is not selected', 'wp-photo-album-plus' );
						$slug = 'wppa_fe_alert';
						$opts = array(	__('--- none ---', 'wp-photo-album-plus' ),
										__('uploads and create albums', 'wp-photo-album-plus' ),
										__('blog it', 'wp-photo-album-plus' ),
										__('all', 'wp-photo-album-plus' ),
										);
						$vals = array(	'-none-',
										'upcre',
										'blog',
										'all',
										);
						$html1 = wppa_select($slug, $opts, $vals);
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new($slug, '16', $name, $desc, $html, $help);

						$name = __('Max fe upload albums', 'wp-photo-album-plus' );
						$desc = __('Max number of albums in frontend upload selection box.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_fe_upload_max_albums';
						$opts = array('0', '10', '20', '50', '100', '200', '500', '1000');
						$vals = $opts;
						$html1 = wppa_select($slug, $opts, $vals).__('albums', 'wp-photo-album-plus' );
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new($slug, '17', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// Import related settings
					{
						$desc = $wppa_subtab_names[$tab]['3'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Import Create page', 'wp-photo-album-plus' );
						$desc = __('Create wp page that shows the album when a directory to album is imported.', 'wp-photo-album-plus' );
						$help = __('As soon as an album is created when a directory is imported, a wp page is made that displays the album content.', 'wp-photo-album-plus' );
						$slug = 'wppa_newpag_create';
						$onch = '';
						$html = wppa_checkbox($slug, $onch);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Page content', 'wp-photo-album-plus' );
						$desc = __('The content of the page. Must contain <b>w#album</b>', 'wp-photo-album-plus' );
						$help = __('The content of the page. Note: it must contain w#album. This will be replaced by the album number in the generated shortcode.', 'wp-photo-album-plus' );
						$slug = 'wppa_newpag_content';
						$clas = 'wppa_newpag';
						$html = wppa_input($slug, '90%');
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Page type', 'wp-photo-album-plus' );
						$desc = __('Select the type of page to create.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_newpag_type';
						$clas = 'wppa_newpag';
						$opts = array(__('Page', 'wp-photo-album-plus' ), __('Post', 'wp-photo-album-plus' ));
						$vals = array('page', 'post');
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Page status', 'wp-photo-album-plus' );
						$desc = __('Select the initial status of the page.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_newpag_status';
						$clas = 'wppa_newpag';
						$opts = array(__('Published', 'wp-photo-album-plus' ), __('Draft', 'wp-photo-album-plus' ));
						$vals = array('publish', 'draft');	// 'draft' | 'publish' | 'pending'| 'future' | 'private'
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						if ( ! is_multisite() || WPPA_MULTISITE_GLOBAL ) {
							$name = __('Permalink root', 'wp-photo-album-plus' );
							$desc = __('The name of the root for the photofile permalink structure.', 'wp-photo-album-plus' );
							$help = __('Choose a convenient name like "albums" or so; this will be the name of a folder inside .../wp-content/. Make sure you choose a unique name', 'wp-photo-album-plus' );
							$help .= '<br>'.__('If you make this field empty, the feature is disabled.', 'wp-photo-album-plus' );
							$slug = 'wppa_pl_dirname';
							$html = wppa_input($slug, '150px');
							wppa_setting_new($slug, '5', $name, $desc, $html, $help);
						}

						$name = __('Import parent check', 'wp-photo-album-plus' );
						$desc = __('Makes the album tree like the directory tree on Import Dirs to albums.', 'wp-photo-album-plus' );
						$help = __('Untick only if all your albums have unique names. In this case additional photos may be ftp\'d to toplevel depot subdirs.', 'wp-photo-album-plus' );
						$slug = 'wppa_import_parent_check';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Keep dir to album files', 'wp-photo-album-plus' );
						$desc = __('Keep imported files after dir to album import', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_keep_import_files';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('Import page previews', 'wp-photo-album-plus' );
						$desc = __('Show thumbnail previews in import admin page.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_import_preview';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('Import source root', 'wp-photo-album-plus' );
						$desc = __('Specify the highest level in the filesystem where to import from', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_import_root';
						$opts = array();
						$prev = '';
						$curr = ABSPATH . 'wp-content';
						while ( $prev != $curr ) {
							$opts[] = $curr;
							$prev = $curr;
							$curr = dirname($prev);
						}
						$vals = $opts;
						$html = wppa_select($slug,$opts,$vals,'','',false,'','500');
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						$name = __('Allow import from WPPA+ source folders', 'wp-photo-album-plus' );
						$desc = __('Only switch this on if you know what you are doing!', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_allow_import_source';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '10', $name, $desc, $html, $help);

						$name = __('Import all', 'wp-photo-album-plus' );
						$desc = __('Ticks all item checkboxes on the import page upon pageload', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_import_all';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '11', $name, $desc, $html, $help);

						$name = __('Import auto', 'wp-photo-album-plus');
						$desc = __('Automatically resumes importing after reload due to upload or unzip', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_import_auto';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '12', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// Admin Functionality restrictions for non administrators
					{
						$desc = $wppa_subtab_names[$tab]['4'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Alt thumb is restricted', 'wp-photo-album-plus' );
						$desc = __('Using <b>alt thumbsize</b> is a restricted action.', 'wp-photo-album-plus' );
						$help = __('If checked: alt thumbsize can not be set in album admin by users not having admin rights.', 'wp-photo-album-plus' );
						$slug = 'wppa_alt_is_restricted';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Link is restricted', 'wp-photo-album-plus' );
						$desc = __('Using <b>Link to</b> is a restricted action.', 'wp-photo-album-plus' );
						$help = __('If checked: Link to: can not be set in album admin by users not having admin rights.', 'wp-photo-album-plus' );
						$slug = 'wppa_link_is_restricted';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('CoverType is restricted', 'wp-photo-album-plus' );
						$desc = __('Changing <b>Cover Type</b> is a restricted action.', 'wp-photo-album-plus' );
						$help = __('If checked: Cover Type: can not be set in album admin by users not having admin rights.', 'wp-photo-album-plus' );
						$slug = 'wppa_covertype_is_restricted';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Photo sequence is restricted', 'wp-photo-album-plus' );
						$desc = __('Changing <b>Photo sequence</b> is a restricted action.', 'wp-photo-album-plus' );
						$help = __('If checked: Photo sequence method and sequence # can not be set in album and photo admin by users not having admin rights.', 'wp-photo-album-plus' );
						$slug = 'wppa_porder_restricted';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Change source restricted', 'wp-photo-album-plus' );
						$desc = __('Changing the import source dir requires admin rights.', 'wp-photo-album-plus' );
						$help = __('If checked, the imput source for importing photos and albums is restricted to user role administrator.', 'wp-photo-album-plus' );
						$slug = 'wppa_chgsrc_is_restricted';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Extended status restricted', 'wp-photo-album-plus' );
						$desc = __('Setting status other than pending or publish requires admin rights.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_ext_status_restricted';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Photo description restricted', 'wp-photo-album-plus' );
						$desc = __('Edit photo description requires admin rights.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_desc_is_restricted';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('Update photofiles restricted', 'wp-photo-album-plus' );
						$desc = __('Re-upload files requires admin rights', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_reup_is_restricted';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('New tags restricted', 'wp-photo-album-plus' );
						$desc = __('Creating new tags requires admin rights', 'wp-photo-album-plus' );
						$help = __('If ticked, users can ony use existing tags', 'wp-photo-album-plus' );
						$slug = 'wppa_newtags_is_restricted';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						$name = __('Album Admin separate', 'wp-photo-album-plus' );
						$desc = __('Restrict album admin to separate albums for non administrators', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_admin_separate';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '10', $name, $desc, $html, $help);

						$name = __('Album download link restricted', 'wp-photo-album-plus' );
						$desc = __('The album download link on covers shows only to admin', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_download_album_is_restricted';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '11', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// Miscellaneous limiting settings
					{
						$desc = $wppa_subtab_names[$tab]['5'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Frontend Edit', 'wp-photo-album-plus' );
						$desc = __('Allow the uploader to edit the photo info', 'wp-photo-album-plus' );
						$help = __('If selected, any logged in user who meets the criteria has the capability to edit the photo information.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Note: This may be AFTER moderation!!', 'wp-photo-album-plus' );
						$slug = 'wppa_upload_edit';
						$opts = array( __('--- none ---', 'wp-photo-album-plus' ), __('New style', 'wp-photo-album-plus' ) );
						$vals = array( '-none-', 'new' );
						$clas = 'uploadedit';
						$html = wppa_select($slug, $opts, $vals, "wppaSlaveSelected('wppa_upload_edit-new','" . $clas . "');" );
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$show = wppa_opt( 'upload_edit') == 'new';

						$name = __('Fe Edit users', 'wp-photo-album-plus' );
						$desc = __('The criteria the user must meet to edit photo info', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_upload_edit_users';
						$opts = array( __('Admin and superuser', 'wp-photo-album-plus' ), __('Owner, admin and superuser', 'wp-photo-album-plus' ) );
						$vals = array( 'admin', 'owner' );
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help, $show, $clas);

						$name = __('Fe Edit period', 'wp-photo-album-plus' );
						$desc = __('The time since upload the user can edit photo info', 'wp-photo-album-plus' );
						$help = __('Frontend Edit should be set in order to have effect', 'wp-photo-album-plus' );
						$help .= '. ' . __('This limit does not apply for administrators and superusers', 'wp-photo-album-plus' );
						$slug = 'wppa_upload_edit_period';
						$opts = array(	__('15 minutes', 'wp-photo-album-plus' ),
										__('one hour', 'wp-photo-album-plus' ),
										__('three hours', 'wp-photo-album-plus' ),
										__('one day', 'wp-photo-album-plus' ),
										__('for ever', 'wp-photo-album-plus' ),
										);
						$vals = array(	900, 3600, 10800, 86400, 0 );
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help, $show, $clas);

						$name = __('Fe Edit Theme CSS', 'wp-photo-album-plus' );
						$desc = __('The front-end edit photo dialog uses the theme CSS.', 'wp-photo-album-plus' );
						$help = __('This setting has effect when Frontend Edit is set to \'Classic\' only.', 'wp-photo-album-plus' );
						$help .= ' ' . __('See item 1 in this table', 'wp-photo-album-plus');
						$slug = 'wppa_upload_edit_theme_css';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help, $show, $clas);

						$name = __('Fe Edit New Items', 'wp-photo-album-plus' );
						$desc = __('The items that are fe editable', 'wp-photo-album-plus' );
						$help = wppa_see_also( 'custom', '2' );
						$slug1 = 'wppa_fe_edit_name';
						$slug2 = 'wppa_fe_edit_desc';
						$slug3 = 'wppa_fe_edit_tags';
						$html1 = ' <span style="float:left" >'.__('Name', 'wp-photo-album-plus' ).':</span>'.wppa_checkbox($slug1);
						$html2 = ' <span style="float:left" >'.__('Description', 'wp-photo-album-plus' ).':</span>'.wppa_checkbox($slug2);
						$html3 = ' <span style="float:left" >'.__('Tags', 'wp-photo-album-plus' ).':</span>'.wppa_checkbox($slug3);
						$html = array($html1.$html2.$html3);
						wppa_setting_new($slug1, '5', $name, $desc, $html, $help, $show, $clas);

						$name = __('Fe Edit Button text', 'wp-photo-album-plus' );
						$desc = __('The text on the Edit button.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_fe_edit_button';
						$html = wppa_edit($slug, wppa_get_option( $slug ), '300px');
						wppa_setting_new($slug, '6', $name, $desc, $html, $help, $show, $clas);

						$name = __('Fe Edit Dialog caption', 'wp-photo-album-plus' );
						$desc = __('The text on the header of the popup.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_fe_edit_caption';
						$html = wppa_edit($slug, wppa_get_option( $slug ), '300px');
						wppa_setting_new($slug, '7', $name, $desc, $html, $help, $show, $clas);

						$name = __('Frontend Delete', 'wp-photo-album-plus' );
						$desc = __('Allow the uploader to delete the photo', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_upload_delete';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('Fe Delete period', 'wp-photo-album-plus' );
						$desc = __('The time since upload the user can delete the photo', 'wp-photo-album-plus' );
						$help = __('Frontend Delete should be set in order to have effect', 'wp-photo-album-plus' );
						$help .= '. ' . __('This limit does not apply for administrators and superusers', 'wp-photo-album-plus' );
						$slug = 'wppa_upload_delete_period';
						$opts = array(	__('15 minutes', 'wp-photo-album-plus' ),
										__('one hour', 'wp-photo-album-plus' ),
										__('three hours', 'wp-photo-album-plus' ),
										__('one day', 'wp-photo-album-plus' ),
										__('for ever', 'wp-photo-album-plus' ),
										);
						$vals = array(	900, 3600, 10800, 86400, 0 );
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						$name = __('Uploader Moderate Comment', 'wp-photo-album-plus' );
						$desc = __('The owner of the photo can moderate the photos comments.', 'wp-photo-album-plus' );
						$help = __('This setting requires "Uploader edit" to be enabled also.', 'wp-photo-album-plus' );
						$slug = 'wppa_owner_moderate_comment';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '10', $name, $desc, $html, $help);

						$name = __('Upload memory check', 'wp-photo-album-plus' );
						$desc = __('Disable uploading photos that are too large.', 'wp-photo-album-plus' );
						$help = __('To prevent out of memory crashes during upload and possible database inconsistencies, uploads can be prevented if the photos are too big.', 'wp-photo-album-plus' );
						$slug = 'wppa_memcheck';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '11', $name, $desc, $html, $help);

						$name = __('Upload memory check copy', 'wp-photo-album-plus' );
						$desc = __('Copy photos that are too large.', 'wp-photo-album-plus' );
						$help = __('To prevent out of memory crashes during upload and possible database inconsistencies, photos are not resized but copied if the photos are too big.', 'wp-photo-album-plus' );
						$slug = 'wppa_memcheck_copy';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '12', $name, $desc, $html, $help);

						$name = __('Comment captcha', 'wp-photo-album-plus' );
						$desc = __('Use a simple calculate captcha on comments form.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_comment_captcha';
						$opts = array(__('All users', 'wp-photo-album-plus' ), __('Logged out users', 'wp-photo-album-plus' ), __('No users', 'wp-photo-album-plus' ));
						$vals = array('all', 'logout', 'none');
						$html = wppa_select($slug, $opts, $vals);
						$clas = 'wppa_comment_';
						wppa_setting_new($slug, '13', $name, $desc, $html, $help);

						$name = __('Spam lifetime', 'wp-photo-album-plus' );
						$desc = __('Delete spam comments when older than.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_spam_maxage';
						$opts = array(	__('--- off ---', 'wp-photo-album-plus' ),
											sprintf( _n('%d minute', '%d minutes', '10', 'wp-photo-album-plus' ), '10'),
											sprintf( _n('%d minute', '%d minutes', '30', 'wp-photo-album-plus' ), '30'),
											sprintf( _n('%d hour', '%d hours', '1', 'wp-photo-album-plus' ), '1'),
											sprintf( _n('%d day', '%d days', '1', 'wp-photo-album-plus' ), '1'),
											sprintf( _n('%d week', '%d weeks', '1', 'wp-photo-album-plus' ), '1'),
										);

						$vals = array(	'none',
											'600',
											'1800',
											'3600',
											'86400',
											'604800',
										);

						$html = wppa_select($slug, $opts, $vals);
						$clas = 'wppa_comment_';
						wppa_setting_new($slug, '14', $name, $desc, $html, $help);

						$name = __('Avoid duplicates', 'wp-photo-album-plus' );
						$desc = __('Prevent the creation of duplicate photos.', 'wp-photo-album-plus' );
						$help = __('If checked: uploading, importing, copying or moving photos to other albums will be prevented when the destination album already contains a photo with the same filename.', 'wp-photo-album-plus' );
						$slug = 'wppa_void_dups';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '15', $name, $desc, $html, $help);

						$name = __('Blacklist user', 'wp-photo-album-plus' );
						$desc = __('Set the status of all the users photos to \'pending\'.', 'wp-photo-album-plus' );
						$help = __('Also inhibits further uploads.', 'wp-photo-album-plus' );
						$slug = 'wppa_blacklist_user';
						$blacklist = wppa_get_option( 'wppa_black_listed_users', array() );

						if ( wppa_get_user_count() <= wppa_opt( 'max_users' ) ) {
							$users = wppa_get_users();
							$opts = array( __('--- select a user to blacklist ---', 'wp-photo-album-plus' ) );
							$vals = array( '0' );
							foreach ( $users as $usr ) {
								if ( ! in_array( $usr['user_login'], $blacklist ) ) {	// skip already on blacklist
									$opts[] = htmlspecialchars( $usr['display_name'] ).' ('.$usr['user_login'].')';
									$vals[]  = $usr['user_login'];
								}
							}
							$onchange = 'alert(\''.__('The page will be reloaded after the action has taken place.', 'wp-photo-album-plus' ).'\');wppaRefreshAfter();';
							$html = wppa_select($slug, $opts, $vals, $onchange);
							}
						else { // over 1000 users
							$onchange = 'alert(\''.__('The page will be reloaded after the action has taken place.', 'wp-photo-album-plus' ).'\');wppaRefreshAfter();';
							$html = '<span style="float:left" >'.__( 'User login name <b>( case sensitive! )</b>:', 'wp-photo-album-plus' ).'</span>';
							$html .= wppa_input ( $slug, '150px', '', '', $onchange );
						}
						wppa_setting_new(false, '16', $name, $desc, $html, $help);

						$name = __('Unblacklist user', 'wp-photo-album-plus' );
						$desc = __('Set the status of all the users photos to \'publish\'.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_un_blacklist_user';
						$blacklist = wppa_get_option( 'wppa_black_listed_users', array() );
						$opts = array( __('--- select a user to unblacklist ---', 'wp-photo-album-plus' ) );
						$vals = array( '0' );
						foreach ( $blacklist as $usr ) {
							$u = wppa_get_user_by( 'login', $usr );
							$opts[] = htmlspecialchars( $u->display_name ).' ('.$u->user_login.')';
							$vals[]  = $u->user_login;
						}
						$onchange = 'alert(\''.__('The page will be reloaded after the action has taken place.', 'wp-photo-album-plus' ).'\');wppaRefreshAfter();';
						$html = wppa_select($slug, $opts, $vals, $onchange);
						wppa_setting_new(false, '17', $name, $desc, $html, $help);

						$name = __('Photo owner change', 'wp-photo-album-plus' );
						$desc = __('Administrators can change photo owner', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_photo_owner_change';
						$html = wppa_checkbox( $slug );
						wppa_setting_new($slug, '18', $name, $desc, $html, $help);

						$name = __('Super user', 'wp-photo-album-plus' );
						$desc = __('Give these users all rights in wppa.', 'wp-photo-album-plus' );
						$help = __('This gives the user all the administrator privileges within wppa.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Make sure the user also has a role that has all the capability boxes ticked', 'wp-photo-album-plus' );
						$help .= '<br>'.wppa_see_also( 'admin', '1' );
						$slug = 'wppa_superuser_user';
						$superlist = wppa_get_option( 'wppa_super_users', array() );

						$onchange = 'alert(\''.__('The page will be reloaded after the action has taken place.', 'wp-photo-album-plus' ).'\');wppaRefreshAfter();';
						if ( wppa_get_user_count() <= wppa_opt( 'max_users' ) ) {
							$users = wppa_get_users();
							$opts = array( __('--- select a user to make superuser ---', 'wp-photo-album-plus' ) );
							$vals = array( '0' );
							foreach ( $users as $usr ) {
								if ( ! in_array( $usr['user_login'], $superlist ) ) {	// skip already on superlist
									$opts[] = htmlspecialchars( $usr['display_name'] ).' ('.$usr['user_login'].')';
									$vals[]  = $usr['user_login'];
								}
							}
							$html = wppa_select($slug, $opts, $vals, $onchange);
							}
						else { // over 1000 users
							$html = '<span style="float:left" >'.__( 'User login name <b>( case sensitive! )</b>:', 'wp-photo-album-plus' ).'</span>';
							$html .= wppa_input( $slug, '150px', '', '', $onchange );
						}
						wppa_setting_new(false, '19', $name, $desc, $html, $help);

						$name = __('Unsuper user', 'wp-photo-album-plus' );
						$desc = __('Remove user from super user list.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_un_superuser_user';
						$superlist = wppa_get_option( 'wppa_super_users', array() );
						$opts = array( __('--- select a user to unmake superuser ---', 'wp-photo-album-plus' ) );
						$vals = array( '0' );
						foreach ( $superlist as $usr ) {
							$u = wppa_get_user_by( 'login', $usr );
							$opts[] = htmlspecialchars( $u->display_name ).' ('.$u->user_login.')';
							$vals[]  = $u->user_login;
						}
						$onchange = 'alert(\''.__('The page will be reloaded after the action has taken place.', 'wp-photo-album-plus' ).'\');wppaRefreshAfter();';
						$html = wppa_select($slug, $opts, $vals, $onchange);
						wppa_setting_new(false, '20', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// Miscellaneous admin related settings
					{
						$desc = $wppa_subtab_names[$tab]['6'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Show dashboard widget', 'wp-photo-album-plus' );
						$desc = __('Select when the dashboard widget should show up', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_show_dashboard_widgets';
						$opts = array( 	__('Never', 'wp-photo-album-plus' ),
										__('All loggedin users', 'wp-photo-album-plus' ),
										__('Administartors only', 'wp-photo-album-plus' ),
										);
						$vals = array( 	'none',
										'all',
										'admin',
										);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Admin bar menu admin', 'wp-photo-album-plus' );
						$desc = __('Show menu on admin bar on admin pages.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_adminbarmenu_admin';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Admin bar menu frontend', 'wp-photo-album-plus' );
						$desc = __('Show menu on admin bar on frontend pages.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_adminbarmenu_frontend';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Photo admin max albums', 'wp-photo-album-plus' );
						$desc = __('Max albums to show in album selectionbox.', 'wp-photo-album-plus' );
						$help = __('If there are more albums in the system, display an input box asking for album id#', 'wp-photo-album-plus' );
						$slug = 'wppa_photo_admin_max_albums';
						$opts = array( __( '--- off ---', 'wp-photo-album-plus' ), '10', '20', '50', '100', '200', '500', '1000', '2000', '3000', '4000', '5000' );
						$vals = array( '0', '10', '20', '50', '100', '200', '500', '1000', '2000', '3000', '4000', '5000' );
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Geo info edit', 'wp-photo-album-plus' );
						$desc = __('Lattitude and longitude may be edited in photo admin.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_geo_edit';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('Add shortcode to posts', 'wp-photo-album-plus' );
						$desc = __('Add a shortcode to the end of all posts.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_add_shortcode_to_post';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						$name = __('Shortcode to add', 'wp-photo-album-plus' );
						$desc = __('The shortcode to be added to the posts.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_shortcode_to_add';
						$html = wppa_input($slug, '300px');
						wppa_setting_new($slug, '10', $name, $desc, $html, $help);

						$name = __('Enable shortcode generator', 'wp-photo-album-plus' );
						$desc = __('Show album icon above page/post edit window', 'wp-photo-album-plus' );
						$help = __('Administrators and wppa super users will always have the shortcode generator available.', 'wp-photo-album-plus' );
						$slug = 'wppa_enable_generator';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '11', $name, $desc, $html, $help);

						$name = __('Bulk photo moderation', 'wp-photo-album-plus' );
						$desc = __('Use bulk edit for photo moderation', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_moderate_bulk';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '12', $name, $desc, $html, $help);

						$name = __('Max in shortcode generator', 'wp-photo-album-plus' );
						$desc = __('Maximum number of selectable photos in the shortcode generators', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_generator_max';
						$opts = array( '5', '10', '20', '50', '100', '200', '500', '1000', '2000', '5000' );
						$vals = $opts;
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '13', $name, $desc, $html, $help);

						$name = __('Enable TinyMCE editor', 'wp-photo-album-plus');
						$desc = __('Use classic WP editor for album and photo descriptions', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_use_wp_editor';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '14', $name, $desc, $html, $help);

						$name = __('Load theme css at the backend', 'wp-photo-album-plus');
						$desc = __('This may make previews more realistic', 'wp-photo-album-plus');
						$help = __('Use with care, it can damage the admin layout, depending of the theme used. It does only load the standard and child theme css, no inline css', 'wp-photo-album-plus');
						$slug = 'wppa_admin_theme_css';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '15', $name, $desc, $html, $help);

						$name = __('Backend inline styles', 'wp-photo-album-plus');
						$desc = __('Here you can add inline styles to fix the damage caused by the previous setting', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_admin_inline_css';
						$html = wppa_textarea($slug);
						wppa_setting_new($slug, '16', $name, $desc, $html, $help);

						$name = __('Extra backend stylesheet', 'wp-photo-album-plus');
						$desc = __('Enter the full url to the extra stylesheet to load at the backend', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_admin_extra_css';
						$html = wppa_input($slug, '500px', '', '', '', site_url() . '/wp-content/example-extra-style.css');
						wppa_setting_new($slug, '17', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// Optional menu items
					{
						$desc = $wppa_subtab_names[$tab]['7'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Search', 'wp-photo-album-plus' );
						$desc = __('Search bar like on album table page', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_opt_menu_search';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Logfile', 'wp-photo-album-plus' );
						$desc = __('List logfile', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_logfile_on_menu';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Documentation', 'wp-photo-album-plus' );
						$desc = __('Link to documentation site', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_opt_menu_doc';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Edit tags', 'wp-photo-album-plus' );
						$desc = __('Easy way to global edit tags. Requires capability "wppa_edit_tags"', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_opt_menu_edit_tags';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Photo sequence', 'wp-photo-album-plus' );
						$desc = __('Rearrange photos of an album. Requires capability "wppa_edit_sequence"', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_opt_menu_edit_sequence';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Manage emails', 'wp-photo-album-plus' );
						$desc = __('Manage email subscriptions', 'wp-photo-album-plus');
						$help = '';
						$slug = 'wppa_opt_menu_edit_email';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
				}
				break;

				case 'maintenance': {
					// Regular maintenance procedures
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);

						$coldef = array( 	'#' => '24px;',
											__('Name', 'wp-photo-album-plus' ) => 'auto;',
											__('Description', 'wp-photo-album-plus' ) => 'auto;',
											__('Specification', 'wp-photo-album-plus' ) => 'auto;',
											__('Do it!', 'wp-photo-album-plus' ) => 'auto;',
											__('Status', 'wp-photo-album-plus' ) => 'auto;',
											__('To Go', 'wp-photo-album-plus' ) => 'auto;',
											__('Help', 'wp-photo-album-plus' ) => '24px;',
											);

						wppa_setting_box_header_new($tab, $coldef);

						$name = __('Postpone cron', 'wp-photo-album-plus' );
						$desc = __('Temporary do no background processes.', 'wp-photo-album-plus' );
						$help = __('This setting is meant to be used a.o. during bulk import/upload. Use with care!', 'wp-photo-album-plus' );
						$slug = 'wppa_maint_ignore_cron';
						$html1 = wppa_checkbox( $slug );
						$html2 = '';
						$html3 = '';
						$html4 = '';
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '0', $name, $desc, $html, $help);

						$name = __('Setup', 'wp-photo-album-plus' );
						$desc = __('Re-initialize plugin.', 'wp-photo-album-plus' );
						$help = __('Re-initilizes the plugin, (re)creates database tables and sets up default settings and directories if required.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('This action may be required to setup blogs in a multiblog (network) site as well as in rare cases to correct initilization errors.', 'wp-photo-album-plus' );
						$slug = 'wppa_setup';
						$html1 = '';
						$html2 = wppa_doit_button_new($slug);
						$html3 = '';
						$html4 = '';
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '1', $name, $desc, $html, $help);

						$name = __('Backup settings', 'wp-photo-album-plus' );
						$desc = __('Save all settings into a backup file.', 'wp-photo-album-plus' );
						$help = __('Saves all the settings into a backup file', 'wp-photo-album-plus' );
						$slug1 = 'wppa_backup_filename';
						$slug2 = 'wppa_backup';
						$html1 = wppa_input( $slug1, '200px;', '', '', '', 'settings.bak' );
						$html2 = wppa_doit_button_new($slug2);
						$html3 = '';
						$html4 = '';
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '2', $name, $desc, $html, $help);

						$name = __( 'Load settings', 'wp-photo-album-plus' );
						$desc = __( 'Restore all settings from defaults, a backup or skin file.', 'wp-photo-album-plus' );
						$help = __( 'Restores all the settings from the factory supplied defaults, the backup you created or from a skin file.', 'wp-photo-album-plus' );
						$help .= ' ' . __( 'Restoring a .skin-file will not overwrite linkpage settings.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_skinfile';
						$slug2 = 'wppa_load_skin';
						$files1 = wppa_glob(WPPA_PATH.'/theme/*.skin');
						$files2 = wppa_glob(WPPA_DEPOT_PATH.'/*.bak');
						$files3 = wppa_glob(WPPA_DEPOT_PATH.'/*.skin');
						$files = array_merge( $files1, $files2, $files3 );
						$opts = array();
						$vals = array();
						$opts[] = __( 'Please select an item', 'wp-photo-album-plus' );
						$opts[] = __( '--- set to defaults ---', 'wp-photo-album-plus' );
						$vals[] = '';
						$vals[] = 'default';
						if ( count( $files ) ) {
							foreach ( $files as $file ) {
								$fname = basename( $file );
								$ext = strrchr( $fname, '.' );
								if ( $ext == '.skin' || $ext == '.bak' )  {
									$opts[] = $fname;
									$vals[] = $file;
								}
							}
						}
						$html1 = wppa_select($slug1, $opts, $vals);
						$html2 = wppa_doit_button_new($slug2);
						$html3 = '';
						$html4 = '';
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '3', $name, $desc, $html, $help);

						$name = __('Regenerate', 'wp-photo-album-plus' );
						$desc = __('Regenerate all thumbnails.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_regen_thumbs_skip_one';
						$slug2 = 'wppa_regen_thumbs';
						$html1 = wppa_cronjob_button( $slug2 ) . wppa_ajax_button(__('Skip one', 'wp-photo-album-plus' ), 'regen_thumbs_skip_one', '0', true );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '4', $name, $desc, $html, $help);

						$name = __('Rerate', 'wp-photo-album-plus' );
						$desc = __('Recalculate ratings.', 'wp-photo-album-plus' );
						$help = __('This function will recalculate all mean photo ratings from the ratings table.', 'wp-photo-album-plus' );
						$slug2 = 'wppa_rerate';
						$html1 = wppa_cronjob_button( $slug2 );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '5', $name, $desc, $html, $help, wppa_switch( 'rating_on' ) );

						$name = __('Lost and found', 'wp-photo-album-plus' );
						$desc = __('Find "lost" photos.', 'wp-photo-album-plus' );
						$help = __('This function will attempt to find lost photos.', 'wp-photo-album-plus' );
						$slug2 = 'wppa_cleanup';
						$html1 = '';
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '6', $name, $desc, $html, $help);

						$name = __('Recuperate', 'wp-photo-album-plus' );
						$desc = __('Recuperate IPTC and EXIF data from photos in WPPA+.', 'wp-photo-album-plus' );
						$help = __('This action will attempt to find and register IPTC and EXIF data from photos in the WPPA+ system.', 'wp-photo-album-plus' );
						$slug2 = 'wppa_recup';
						$html1 = wppa_cronjob_button( $slug2 );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '7', $name, $desc, $html, $help, wppa_switch( 'save_exif' ) || wppa_switch( 'save_iptc' ) );

						$name = __('Format exif', 'wp-photo-album-plus' );
						$desc = __('Format EXIF data', 'wp-photo-album-plus' );
						$help = '';
						$slug2 = 'wppa_format_exif';
						$html1 = wppa_cronjob_button( $slug2 );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '8', $name, $desc, $html, $help, wppa_switch( 'save_exif' ) );

						$name = __('Remake Index Albums', 'wp-photo-album-plus' );
						$desc = __('Remakes the index database table for albums.', 'wp-photo-album-plus' );
						$help = '';
						$slug2 = 'wppa_remake_index_albums';
						$html1 = wppa_cronjob_button( $slug2 );// . __('ad inf', 'wp-photo-album-plus' ) . wppa_checkbox( $slug2.'_ad_inf' );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '9', $name, $desc, $html, $help);

						$name = __('Remake Index Photos', 'wp-photo-album-plus' );
						$desc = __('Remakes the index database table for photos.', 'wp-photo-album-plus' );
						$help = '';
						$slug2 = 'wppa_remake_index_photos';
						$html1 = wppa_cronjob_button( $slug2 );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '10', $name, $desc, $html, $help);

						$name = __('Clean Index', 'wp-photo-album-plus' );
						$desc = __('Remove obsolete entries from index db table.', 'wp-photo-album-plus' );
						$help = '';
						$slug2 = 'wppa_cleanup_index';
						$html1 = wppa_cronjob_button( $slug2 );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '11', $name, $desc, $html, $help);

						$fs = wppa_get_option('wppa_file_system');
						if ( ! $fs ) {
							$fs = 'flat';
							wppa_update_option('wppa_file_system', 'flat');
						}
						if ( $fs == 'flat' || $fs == 'to-tree' ) {
							$name = __('Convert to tree', 'wp-photo-album-plus' );
							$desc = __('Convert filesystem to tree structure.', 'wp-photo-album-plus' );
						}
						if ( $fs == 'tree' || $fs == 'to-flat' ) {
							$name = __('Convert to flat', 'wp-photo-album-plus' );
							$desc = __('Convert filesystem to flat structure.', 'wp-photo-album-plus' );
						}
						$help = __('If you want to go back to a wppa+ version prior to 5.0.16, you MUST convert to flat first.', 'wp-photo-album-plus' );
						$slug2 = 'wppa_file_system';
						$html1 = '';
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '12', $name, $desc, $html, $help);

						$name = __('Remake add', 'wp-photo-album-plus' );
						$desc = __('Photos will be added from the source pool. See next item', 'wp-photo-album-plus' );
						$help = __('If checked: If photo files are found in the source directory that do not exist in the corresponding album, they will be added to the album.', 'wp-photo-album-plus' );
						$slug = 'wppa_remake_add';
						$html1 = wppa_checkbox($slug);
						$html0 = '';
						$html = array($html1, $html0, $html0, $html0);
						wppa_setting_new($slug, '13', $name, $desc, $html, $help);

						$name = __('Remake', 'wp-photo-album-plus' );
						$desc = __('Remake the photofiles from photo sourcefiles.', 'wp-photo-album-plus' );
						$help = __('This action will remake the fullsize images, thumbnail images, and will refresh the iptc and exif data for all photos where the source is found in the corresponding album sub-directory of the source directory.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_remake_skip_one';
						$slug2 = 'wppa_remake';
						$html1 = wppa_cronjob_button( $slug2 ) . wppa_ajax_button(__('Skip one', 'wp-photo-album-plus' ), 'remake_skip_one', '0', true );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '14', $name, $desc, $html, $help);

						$name = __('Orientation only', 'wp-photo-album-plus' );
						$desc = __('Remake non standard orientated photos only.',  'wp-photo-album-plus' );
						$help = '';
						$slug1 = '';
						$slug2 = 'wppa_remake_orientation_only';
						$html1 = '';
						$html2 = wppa_checkbox( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '15', $name, $desc, $html, $help);

						$name = __('Missing only', 'wp-photo-album-plus' );
						$desc = __('Remake missing photofiles only.',  'wp-photo-album-plus' );
						$help = '';
						$slug1 = '';
						$slug2 = 'wppa_remake_missing_only';
						$html1 = '';
						$html2 = wppa_checkbox( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '16', $name, $desc, $html, $help);

						$name = __('Recalc sizes', 'wp-photo-album-plus' );
						$desc = __('Recalculate photosizes and save to db.', 'wp-photo-album-plus' );
						$help = '';
						$slug2 = 'wppa_comp_sizes';
						$html1 = wppa_cronjob_button( $slug2 );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '17', $name, $desc, $html, $help);

						$name = __('Renew album crypt', 'wp-photo-album-plus' );
						$desc = __('Renew album encrcryption codes.', 'wp-photo-album-plus' );
						$help = '';
						$slug2 = 'wppa_crypt_albums';
						$html1 = wppa_cronjob_button( $slug2 );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '18', $name, $desc, $html, $help);

						$name = __('Renew album crypt every', 'wp-photo-album-plus' );
						$desc = __('Renew cryptic codes periodically', 'wp-photo-album-plus' );
						$help = __('The periodic times are an approximation', 'wp-photo-album-plus' );
						$slug = 'wppa_crypt_albums_every';
						$opts = array( 	__('--- off ---', 'wp-photo-album-plus' ),
										__('hour', 'wp-photo-album-plus' ),
										__('day', 'wp-photo-album-plus' ),
										__('week', 'wp-photo-album-plus' ),
										__('month', 'wp-photo-album-plus' ),
										);
						$vals = array( '0', '1', '24', '168', '720' );
						$html = wppa_select( $slug, $opts, $vals ) . '<td></td><td></td><td></td>';
						wppa_setting_new(false, '19', $name, $desc, $html, $help);

						$name = __('Renew photo crypt', 'wp-photo-album-plus' );
						$desc = __('Renew photo encrcryption codes.', 'wp-photo-album-plus' );
						$help = '';
						$slug2 = 'wppa_crypt_photos';
						$html1 = wppa_cronjob_button( $slug2 );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '20', $name, $desc, $html, $help);

						$name = __('Renew photo crypt every', 'wp-photo-album-plus' );
						$desc = __('Renew cryptic codes periodically', 'wp-photo-album-plus' );
						$help = __('The periodic times are an approximation', 'wp-photo-album-plus' );
						$slug = 'wppa_crypt_photos_every';
						$opts = array( 	__('--- off ---', 'wp-photo-album-plus' ),
										__('hour', 'wp-photo-album-plus' ),
										__('day', 'wp-photo-album-plus' ),
										__('week', 'wp-photo-album-plus' ),
										__('month', 'wp-photo-album-plus' ),
										);
						$vals = array( '0', '1', '24', '168', '720' );
						$html = wppa_select( $slug, $opts, $vals ) . '<td></td><td></td><td></td>';
						wppa_setting_new(false, '21', $name, $desc, $html, $help);

						$name = __('Create orietation sources', 'wp-photo-album-plus' );
						$desc = __('Creates correctly oriented pseudo source file.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_create_o1_files_skip_one';
						$slug2 = 'wppa_create_o1_files';
						$html1 = wppa_ajax_button(__('Skip one', 'wp-photo-album-plus' ), 'create_o1_files_skip_one', '0', true );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '22', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// Clearing and other irreversable maintenance procedures
					{
						$desc = $wppa_subtab_names[$tab]['2'];
						wppa_setting_tab_description($desc);

						$coldef = array( 	'#' => '24px;',
											__('Name', 'wp-photo-album-plus' ) => 'auto;',
											__('Description', 'wp-photo-album-plus' ) => 'auto;',
											__('Specification', 'wp-photo-album-plus' ) => 'auto;',
											__('Do it!', 'wp-photo-album-plus' ) => 'auto;',
											__('Status', 'wp-photo-album-plus' ) => 'auto;',
											__('To Go', 'wp-photo-album-plus' ) => 'auto;',
											__('Help', 'wp-photo-album-plus' ) => '24px;',
											);

						wppa_setting_box_header_new($tab, $coldef);

						$name = __('Clear ratings', 'wp-photo-album-plus' );
						$desc = __('Reset all ratings.', 'wp-photo-album-plus' );
						$help = __('WARNING: If checked, this will clear all ratings in the system!', 'wp-photo-album-plus' );
						$slug = 'wppa_rating_clear';
						$html1 = '';
						$html2 = wppa_ajax_button('', 'rating_clear');
						$html3 = '';
						$html4 = '';
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '1', $name, $desc, $html, $help, wppa_switch( 'rating_on' ) );

						$name = __('Clear viewcounts', 'wp-photo-album-plus' );
						$desc = __('Reset all viewcounts.', 'wp-photo-album-plus' );
						$help = __('WARNING: If checked, this will clear all viewcounts in the system!', 'wp-photo-album-plus' );
						$slug = 'wppa_viewcount_clear';
						$html1 = '';
						$html2 = wppa_ajax_button('', 'viewcount_clear');
						$html3 = '';
						$html4 = '';
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '2', $name, $desc, $html, $help);

						$name = __('Reset IPTC', 'wp-photo-album-plus' );
						$desc = __('Clear all IPTC data.', 'wp-photo-album-plus' );
						$help = __('WARNING: If checked, this will clear all IPTC data in the system!', 'wp-photo-album-plus' );
						$slug = 'wppa_iptc_clear';
						$html1 = '';
						$html2 = wppa_ajax_button('', 'iptc_clear');
						$html3 = '';
						$html4 = '';
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '3', $name, $desc, $html, $help, wppa_switch( 'rating_on' ) );

						$name = __('Reset EXIF', 'wp-photo-album-plus' );
						$desc = __('Clear all EXIF data.', 'wp-photo-album-plus' );
						$help = __('WARNING: If checked, this will clear all EXIF data in the system!', 'wp-photo-album-plus' );
						$slug = 'wppa_exif_clear';
						$html1 = '';
						$html2 = wppa_ajax_button('', 'exif_clear');
						$html3 = '';
						$html4 = '';
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '4', $name, $desc, $html, $help, wppa_switch( 'save_exif' ) );

						$name = __('Apply Default Photoname', 'wp-photo-album-plus' );
						$desc = __('Apply Default photo name on all photos in the system.', 'wp-photo-album-plus' );
						$help = __('Puts the content of Default photo name in all photo name.', 'wp-photo-album-plus' );
						$help .= '<br>'.wppa_see_also( 'new', '1', '29' );
						$slug2 = 'wppa_apply_default_photoname_all';
						$html1 = '';
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '5', $name, $desc, $html, $help);

						$name = __('Apply New Photodesc', 'wp-photo-album-plus' );
						$desc = __('Apply New photo description on all photos in the system.', 'wp-photo-album-plus' );
						$help = __('Puts the content of New photo desc in all photo descriptions.', 'wp-photo-album-plus' );
						$help .= '<br>'.wppa_see_also( 'new', '1', '13' );
						$slug2 = 'wppa_apply_new_photodesc_all';
						$html1 = '';
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '6', $name, $desc, $html, $help);

						$name = __('Append to photodesc', 'wp-photo-album-plus' );
						$desc = __('Append this text to all photo descriptions.', 'wp-photo-album-plus' );
						$help = __('Appends a space character and the given text to the description of all photos.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('First edit the text to append, click outside the edit window and wait for the green checkmark to appear. Then click the Start! button.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_append_text';
						$slug2 = 'wppa_append_to_photodesc';
						$html1 = wppa_input( $slug1, '200px' );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '7', $name, $desc, $html, $help);

						$name = __('Remove from photodesc', 'wp-photo-album-plus' );
						$desc = __('Remove this text from all photo descriptions.', 'wp-photo-album-plus' );
						$help = __('Removes all occurrencies of the given text from the description of all photos.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('First edit the text to remove, click outside the edit window and wait for the green checkmark to appear. Then click the Start! button.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_remove_text';
						$slug2 = 'wppa_remove_from_photodesc';
						$html1 = wppa_input( $slug1, '200px' );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '8', $name, $desc, $html, $help);

						$name = __('Remove empty albums', 'wp-photo-album-plus' );
						$desc = __('Removes albums that are not used.', 'wp-photo-album-plus' );
						$help = __('Removes all albums that have no photos and no sub albums in it.', 'wp-photo-album-plus' );
						$slug2 = 'wppa_remove_empty_albums';
						$html1 = '';
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '9', $name, $desc, $html, $help);

						$name = __('Remove file-ext', 'wp-photo-album-plus' );
						$desc = __('Remove possible file extension from photo name.', 'wp-photo-album-plus' );
						$help = __('This may be required for old photos, uploaded when the option to set the name to the filename without extension was not yet available/selected.', 'wp-photo-album-plus' );
						$slug2 = 'wppa_remove_file_extensions';
						$html1 = '';
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '10', $name, $desc, $html, $help);

						$name = __('Re-add file-ext', 'wp-photo-album-plus' );
						$desc = __('Revert the <b>Remove file-ext</b> action.', 'wp-photo-album-plus' );
						$help = '';
						$slug2 = 'wppa_readd_file_extensions';
						$html1 = '';
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '11', $name, $desc, $html, $help);

						$name = __('Watermark all', 'wp-photo-album-plus' );
						$desc = __('Apply watermark according to current settings to all photos.', 'wp-photo-album-plus' );
						$help = __('See Tab Watermark for the current watermark settings', 'wp-photo-album-plus' );
						$slug2 = 'wppa_watermark_all';
						$html1 = '';
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '13', $name, $desc, $html, $help, wppa_switch( 'watermark_on' ));

						$name = __('Create all autopages', 'wp-photo-album-plus' );
						$desc = __('Create all the pages to display slides individually.', 'wp-photo-album-plus' );
						$help = '<br>'.__('Make sure you have a custom menu and the "Automatically add new top-level pages to this menu" box UNticked!!', 'wp-photo-album-plus' );
						$help .= wppa_see_also( 'system', '1', '8' );
						$slug2 = 'wppa_create_all_autopages';
						$html1 = '';
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '14', $name, $desc, $html, $help);

						$name = __('Delete all autopages', 'wp-photo-album-plus' );
						$desc = __('Delete all the pages to display slides individually.', 'wp-photo-album-plus' );
						$help = wppa_see_also( 'system', '1', '8' );
						$slug2 = 'wppa_delete_all_autopages';
						$html1 = '';
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '15', $name, $desc, $html, $help);

						$name = __('Leading zeroes', 'wp-photo-album-plus' );
						$desc = __('If photoname numeric, add leading zeros', 'wp-photo-album-plus' );
						$help = __('You can extend the name with leading zeros, so alphabetic sort becomes equal to numeric sort sequence.', 'wp-photo-album-plus' );
						$slug1 = 'wppa_zero_numbers';
						$slug2 = 'wppa_leading_zeros';
						$html1 = wppa_input( $slug1, '50px' ).__('Total chars', 'wp-photo-album-plus' );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '16', $name, $desc, $html, $help);

						$name = __('Add GPX tag', 'wp-photo-album-plus' );
						$desc = __('Make sure photos with gpx data have a Gpx tag', 'wp-photo-album-plus' );
						$help = '';
						$slug2 = 'wppa_add_gpx_tag';
						$html1 = wppa_cronjob_button( $slug2 );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '17', $name, $desc, $html, $help);

						$name = __('Add HD tag', 'wp-photo-album-plus' );
						$desc = __('Make sure photos >= 1920 x 1080 have a HD tag', 'wp-photo-album-plus' );
						$help = '';
						$slug2 = 'wppa_add_hd_tag';
						$html1 = wppa_cronjob_button( $slug2 );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '18', $name, $desc, $html, $help);

						if ( function_exists( 'ewww_image_optimizer') ) {
							$name = __('Optimize files', 'wp-photo-album-plus' );
							$desc = __('Optimize with EWWW image optimizer', 'wp-photo-album-plus' );
							$help = wppa_see_also('new', '1', '41');
							$slug2 = 'wppa_optimize_ewww';
							$html1 = wppa_ajax_button(__('Skip one', 'wp-photo-album-plus' ), 'optimize_ewww_skip_one', '0', true );
							$html2 = wppa_maintenance_button( $slug2 );
							$html3 = wppa_status_field( $slug2 );
							$html4 = wppa_togo_field( $slug2 );
							$html = array($html1, $html2, $html3, $html4);
							wppa_setting_new(false, '19', $name, $desc, $html, $help);
						}

						$name = __('Edit tag', 'wp-photo-album-plus' );
						$desc = __('Globally change a tagname.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_tag_to_edit';
						$slug2 = 'wppa_new_tag_value';
						$slug3 = 'wppa_edit_tag';
						$tags = wppa_get_taglist();
						$opts = array(__('-select a tag-', 'wp-photo-album-plus' ));
						$vals = array( '' );
						if ( $tags ) foreach( array_keys( $tags ) as $tag ) {
							$opts[] = $tag;
							$vals[] = $tag;
						}
						$html1 = '<div><small style="float:left;margin-right:5px">'.__('Tag:', 'wp-photo-album-plus' ).'</small>'.wppa_select( $slug1, $opts, $vals, '', '', false, '', '600').'</div>';
						$html2 = '<div style="clear:both" ><small style="float:left;margin-right:5px">'.__('Change to:', 'wp-photo-album-plus' ).'</small>'.wppa_edit( $slug2, trim( wppa_get_option( $slug2 ), ',' ), '75%' ).'</div>';
						$html3 = wppa_maintenance_button( $slug3 );
						$html4 = wppa_status_field( $slug3 );
						$html5 = wppa_togo_field( $slug3 );
						$html = array( $html1 . '<br>' . $html2, $html3, $html4, $html5 );
						wppa_setting_new( false, '20', $name, $desc, $html, $help);

						$name = __('Synchronize Cloudinary', 'wp-photo-album-plus' );
						$desc = __('Removes/adds images in the cloud.', 'wp-photo-album-plus' );
						$help = __('Removes old images and verifies/adds new images to Cloudinary.', 'wp-photo-album-plus' );
						$help .= '<br>'.wppa_see_also( 'miscadv', '4', '7' );
						$slug2 = 'wppa_sync_cloud';
						$html1 = '';
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '21', $name, $desc, $html, $help);

						$name = __('Set owner to name', 'wp-photo-album-plus' );
						$desc = __('If photoname equals user display name, set him owner.', 'wp-photo-album-plus' );
						$help = '';
						$slug2 = 'wppa_owner_to_name_proc';
						$html1 = '';
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '24', $name, $desc, $html, $help);

						$name = __('Move all photos', 'wp-photo-album-plus' );
						$desc = __('Move all photos from one album to another album.', 'wp-photo-album-plus' );
						$help = '';
						$slug2 = 'wppa_move_all_photos';
						$html1 = '';
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '25', $name, $desc, $html, $help);

						if ( wppa_get_total_album_count() > 200 ) {	// Many albums: input id

							$name = __('From', 'wp-photo-album-plus' );
							$desc = __('Move from album number', 'wp-photo-album-plus' );
							$help = '';
							$slug = 'wppa_move_all_photos_from';
							$html = wppa_input($slug, '100px' );
							$html = array($html, '', '', '');
							wppa_setting_new(false, '26', $name, $desc, $html, $help);

							$name = __('To', 'wp-photo-album-plus' );
							$desc = __('Move to album number', 'wp-photo-album-plus' );
							$help = '';
							$slug = 'wppa_move_all_photos_to';
							$html = wppa_input($slug, '100px' );
							$html = array($html, '', '', '');
							wppa_setting_new(false, '27', $name, $desc, $html, $help);

						}
						else {										// Few albums: selectionbox

							$name = __('From', 'wp-photo-album-plus' );
							$desc = __('Move from album', 'wp-photo-album-plus' );
							$help = '';
							$slug = 'wppa_move_all_photos_from';
							$html = '<select' .
										' id=""' .
										' onchange="wppaAjaxUpdateOptionValue(\'move_all_photos_from\',this)"' .
										' name="move_all_photos_to"' .
										' style="float:left;max-width:220px;"' .
										' >'.
										wppa_album_select_a(array( 	'addpleaseselect'=>true,
																	'path'=>true,
																	'selected'=>wppa_get_option('wppa_move_all_photos_from')
																	)).
									'</select>' .
									'<img' .
										' id="img_move_all_photos_from"' .
										' class=""' .
										' src="'.wppa_get_imgdir().'star.ico"' .
										' title="'.__('Setting unmodified', 'wp-photo-album-plus' ).'"' .
										' style="padding-left:4px; float:left; height:16px; width:16px;"' .
									' />';
							$html = array($html, '', '', '');
							wppa_setting_new(false, '28', $name, $desc, $html, $help);

							$name = __('To', 'wp-photo-album-plus' );
							$desc = __('Move to album', 'wp-photo-album-plus' );
							$help = '';
							$slug = 'wppa_move_all_photos_to';
							$html = '<select' .
										' id=""' .
										' onchange="wppaAjaxUpdateOptionValue(\'move_all_photos_to\',this)"' .
										' name="move_all_photos_to"' .
										' style="float:left;max-width:220px;"' .
										' >'.
										wppa_album_select_a(array(	'addpleaseselect'=>true,
																	'path'=>true,
																	'selected'=>wppa_get_option('wppa_move_all_photos_to')
																	)).
									'</select>' .
									'<img' .
										' id="img_move_all_photos_to"' .
										' class=""' .
										' src="'.wppa_get_imgdir().'star.ico"' .
										' title="'.__('Setting unmodified', 'wp-photo-album-plus' ).'"' .
										' style="padding-left:4px; float:left; height:16px; width:16px;"' .
									' />';
							$html = array($html, '', '', '');
							wppa_setting_new(false, '29', $name, $desc, $html, $help);
						}

						$name = __('Remove hypens from photonames', 'wp-photo-album-plus' );
						$desc = __('Remove all hyphens from all photo names and replace them by spaces', 'wp-photo-album-plus' );
						$help = '';
						$slug2 = 'wppa_photos_hyphens_to_spaces';
						$html1 = wppa_cronjob_button( $slug2 );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '30', $name, $desc, $html, $help);

						$name = __('PNG to JPG', 'wp-photo-album-plus' );
						$desc = __('Convert all .png files to .jpg files', 'wp-photo-album-plus' );
						$help = '';
						$slug2 = 'wppa_png_to_jpg';
						$html1 = '';
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '31', $name, $desc, $html, $help);

						$name = __('Fix mp3 and mp4 meta data', 'wp-photo-album-plus' );
						$desc = __('Import framesize, creationdate, orientation and duration from mp4 video files, duration from mp3 audio files', 'wp-photo-album-plus' );
						$help = '';
						$slug2 = 'wppa_fix_mp4_meta';
						$html1 = '';
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '32', $name, $desc, $html, $help);

						$name = __('Fix user ids', 'wp-photo-album-plus' );
						$desc = __('Fill in missing user ids to ratings and comments', 'wp-photo-album-plus' );
						$help = __('Only used to update ratings and comments entered before version 7.3 to meet the new standards', 'wp-photo-album-plus' );
						$slug2 = 'wppa_fix_userids';
						$html1 = wppa_cronjob_button( $slug2 );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '33', $name, $desc, $html, $help);

						$name = __('Re-init custom and tags', 'wp-photo-album-plus' );
						$desc = __('Re-initialize custom photo fields and default tags', 'wp-photo-album-plus' );
						$help = '';
						$slug2 = 'wppa_fix_custom_tags';
						$html1 = '';
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '34', $name, $desc, $html, $help);

						if ( current_user_can( 'administrator' ) ) {
							$name = __('Custom album proc', 'wp-photo-album-plus' );
							$desc = __('The php code to execute on all albums', 'wp-photo-album-plus' );
							$help = __('Only run this if you know what you are doing!', 'wp-photo-album-plus' );
							$slug2 = 'wppa_custom_album_proc';
							$html1 = wppa_textarea( $slug2, '', true );
							$html2 = wppa_maintenance_button( $slug2 );
							$html3 = wppa_status_field( $slug2 );
							$html4 = wppa_togo_field( $slug2 );
							$html = array($html1, $html2, $html3, $html4);
							wppa_setting_new(false, '35', $name, $desc, $html, $help);

							$name = __('Custom photo proc', 'wp-photo-album-plus' );
							$desc = __('The php code to execute on all photos', 'wp-photo-album-plus' );
							$help = __('Only run this if you know what you are doing!', 'wp-photo-album-plus' );
							$slug2 = 'wppa_custom_photo_proc';
							$html1 = '<div>' . wppa_textarea( $slug2, '', true ) . '</div><div style="clear:left;"></div>' . wppa_cronjob_button( $slug2 );
							$html2 = wppa_maintenance_button( $slug2 );
							$html3 = wppa_status_field( $slug2 );
							$html4 = wppa_togo_field( $slug2 );
							$html = array($html1, $html2, $html3, $html4);
							wppa_setting_new(false, '36', $name, $desc, $html, $help);

							$name = __('Keep last info for custom photoproc', 'wp-photo-album-plus' );
							$desc = __('Keep the info of last item processed when ready', 'wp-photo-album-plus');
							$help = '';
							$slug2 = 'wppa_custom_photo_proc_keep_last';
							$html1 = wppa_checkbox( $slug2 );
							$html2 = '';
							$html3 = '';
							$html4 = '';
							$html = array($html1, $html2, $html3, $html4);
							wppa_setting_new(false, '36a', $name, $desc, $html, $help);
						}

						$name = __('List active sessions', 'wp-photo-album-plus' );
						$desc = __('Show the content of the sessions table.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = '';
						$slug2 = 'wppa_list_session';
						$html1 = '';
						$html2 = wppa_popup_button( $slug2 );
						$html3 = '';
						$html4 = '';
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '37', $name, $desc, $html, $help);

						$name = __( 'Del vanished users items', 'wp-photo-album-plus' );
						$desc = __( 'Remove items owned by users that have been deleted', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_clear_vanished_user_photos';
						$html1 = wppa_cronjob_button( $slug );
						$html2 = wppa_maintenance_button( $slug );
						$html3 = wppa_status_field( $slug );
						$html4 = wppa_togo_field( $slug );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '38', $name, $desc, $html, $help);

						$name = __( 'Del vanished users albums', 'wp-photo-album-plus' );
						$desc = __( 'Remove albums owned by users that have been deleted', 'wp-photo-album-plus' );
						$help = __( 'This will run immediately after the items have been removed', 'wp-photo-album-plus');
						$slug = 'wppa_clear_vanished_user_albums';
						$html1 = ''; //wppa_cronjob_button( $slug );
						$html2 = ''; //wppa_maintenance_button( $slug );
						$html3 = wppa_status_field( $slug );
						$html4 = wppa_togo_field( $slug );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '39', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// One time conversions
					{
						$desc = $wppa_subtab_names[$tab]['3'];
						wppa_setting_tab_description($desc);

						$coldef = array( 	'#' => '24px;',
											__('Name', 'wp-photo-album-plus' ) => 'auto;',
											__('Description', 'wp-photo-album-plus' ) => 'auto;',
											__('Specification', 'wp-photo-album-plus' ) => 'auto;',
											__('Do it!', 'wp-photo-album-plus' ) => 'auto;',
											__('Status', 'wp-photo-album-plus' ) => 'auto;',
											__('To Go', 'wp-photo-album-plus' ) => 'auto;',
											__('Help', 'wp-photo-album-plus' ) => '24px;',
											);

						wppa_setting_box_header_new($tab, $coldef);

						$name = __('Fix tags', 'wp-photo-album-plus' );
						$desc = __('Make sure photo tags format is uptodate', 'wp-photo-album-plus' );
						$help = __('Fixes tags to be conform current database rules.', 'wp-photo-album-plus' );
						$slug2 = 'wppa_sanitize_tags';
						$html1 = wppa_cronjob_button( $slug2 );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '1', $name, $desc, $html, $help);

						$name = __('Fix cats', 'wp-photo-album-plus' );
						$desc = __('Make sure album cats format is uptodate', 'wp-photo-album-plus' );
						$help = __('Fixes cats to be conform current database rules.', 'wp-photo-album-plus' );
						$slug2 = 'wppa_sanitize_cats';
						$html1 = wppa_cronjob_button( $slug2 );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '2', $name, $desc, $html, $help);

						$name = htmlspecialchars(__('Convert user-<id> tags', 'wp-photo-album-plus' ));
						$desc = __('Convert old style usertags to new style.', 'wp-photo-album-plus' );
						$help = htmlspecialchars(__('Converts user-<id> tags - created by the Choice feature - to user displaynames.', 'wp-photo-album-plus' ));
						$slug2 = 'wppa_covert_usertags';
						$html1 = wppa_cronjob_button( $slug2 );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '3', $name, $desc, $html, $help);

						$name = __('All to lower', 'wp-photo-album-plus' );
						$desc = __('Convert all file-extensions to lowercase.', 'wp-photo-album-plus' );
						$help = __('Affects display files, thumbnail files, and saved extensions in database table. Leaves sourcefiles untouched', 'wp-photo-album-plus' );
						$help .= '<br>'.__('If both upper and lowercase files exist, the file with the uppercase extension will be removed.', 'wp-photo-album-plus' );
						$slug2 = 'wppa_all_ext_to_lower';
						$html1 = wppa_cronjob_button( $slug2 );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '4', $name, $desc, $html, $help);

						$name = __('Renew album nameslugs', 'wp-photo-album-plus');
						$desc = __('Run this proc when there are problems with supersearch', 'wp-photo-album-plus');
						$help = '';
						$slug2 = 'wppa_renew_slugs_albums';
						$html1 = wppa_cronjob_button( $slug2 );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '5', $name, $desc, $html, $help);

						$name = __('Renew photo nameslugs', 'wp-photo-album-plus');
						$desc = __('Run this proc when there are problems with supersearch', 'wp-photo-album-plus');
						$help = '';
						$slug2 = 'wppa_renew_slugs_photos';
						$html1 = wppa_cronjob_button( $slug2 );
						$html2 = wppa_maintenance_button( $slug2 );
						$html3 = wppa_status_field( $slug2 );
						$html4 = wppa_togo_field( $slug2 );
						$html = array($html1, $html2, $html3, $html4);
						wppa_setting_new(false, '6', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
				}
				break;

				case 'exif': {
					// EXIF tags and their labels as found in the uploaded photos
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);

						$coldef = array( 	__('#', 'wp-photo-album-plus' ) => '24px;',
											__('Tag', 'wp-photo-album-plus' ) => 'auto;',
											__('Brand', 'wp-photo-album-plus' ) => 'auto;',
											__('Description', 'wp-photo-album-plus' ) => 'auto;',
											__('Status', 'wp-photo-album-plus' ) => 'auto;',
											__('Help', 'wp-photo-album-plus' ) => '24px;',
											);
						wppa_setting_box_header_new($tab, $coldef);

						$labels = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_exif
													   WHERE photo = '0'
													   ORDER BY tag", ARRAY_A );

						if ( is_array( $labels ) ) {
							$i = '1';
							foreach ( $labels as $label ) {
								$name = htmlspecialchars( $label['tag'] );

								$desc = '';
								foreach ( $wppa_supported_camara_brands as $brand ) {
									$lbl = wppa_exif_tagname( $label['tag'], $brand, 'brandonly' );
									if ( $lbl ) {
										$desc .= '<br>' . $brand;
									}
								}

								$help = '';
								$slug1 = 'wppa_exif_label_'.$name;
								$slug2 = 'wppa_exif_status_'.$name;

								$html1 = wppa_edit( $slug1, htmlspecialchars( $label['description'] ) );
								foreach ( $wppa_supported_camara_brands as $brand ) {
									$lbl = wppa_exif_tagname( $label['tag'], $brand, 'brandonly' );
									if ( $lbl ) {
										$html1 .= '<br><span style="clear:left;float:left">' . $lbl . ':</span>';
									}
								}

								$opts = array(__('Display', 'wp-photo-album-plus' ), __('Hide', 'wp-photo-album-plus' ), __('Optional', 'wp-photo-album-plus' ));
								$vals = array('display', 'hide', 'option');
								$html2 = wppa_select_e($slug2, htmlspecialchars( $label['status'] ), $opts, $vals);
								$html = array($html1, $html2);
								wppa_setting_new(false, $i, $name, $desc, $html, $help);
								$i++;
							}
						}

						wppa_setting_box_footer_new();
					}
				}
				break;

				case 'iptc': {
					// IPTC tags and their labels as found in the uploaded photos
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);

						$coldef = array( 	__('#', 'wp-photo-album-plus' ) => '24px;',
											__('Tag', 'wp-photo-album-plus' ) => 'auto;',
											__('Description', 'wp-photo-album-plus' ) => 'auto;',
											__('Status', 'wp-photo-album-plus' ) => 'auto;',
											__('Help', 'wp-photo-album-plus' ) => '24px;',
											);
						wppa_setting_box_header_new($tab, $coldef);

						$labels = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_iptc
																	   WHERE photo = '0'
																	   ORDER BY tag", ARRAY_A );

						if ( is_array( $labels ) ) {
							$i = '1';
							foreach ( $labels as $label ) {
								$name = htmlspecialchars( $label['tag'] );
								$desc = '';
								$help = '';
								$slug1 = 'wppa_iptc_label_'.$name;
								$slug2 = 'wppa_iptc_status_'.$name;
								$html1 = wppa_edit($slug1, htmlspecialchars( $label['description'] ));
								$opts = array(__('Display', 'wp-photo-album-plus' ), __('Hide', 'wp-photo-album-plus' ), __('Optional', 'wp-photo-album-plus' ));
								$vals = array('display', 'hide', 'option');
								$html2 = wppa_select_e($slug2, $label['status'], $opts, $vals);
								wppa_setting_new(false, $i, $name, $html1, $html2, $help);
								$i++;
							}
						}

						wppa_setting_box_footer_new();
					}
				}
				break;

				case 'gpx': {
					// GPX configuration
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('GPX Implementation', 'wp-photo-album-plus' );
						$desc = __('The way the maps are produced.', 'wp-photo-album-plus' );
						$help = __('Select the way the maps are produced.', 'wp-photo-album-plus' );
						$slug = 'wppa_gpx_implementation';
						$opts = array( __('WPPA+ Embedded code', 'wp-photo-album-plus' ), __('External plugin', 'wp-photo-album-plus' ) );
						$vals = array( 'wppa-plus-embedded', 'external-plugin' );
						$onch = "wppaSlaveSelected('wppa_gpx_implementation-wppa-plus-embedded','wppa_map_height');";
						$html = wppa_select($slug, $opts, $vals, $onch);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Map height', 'wp-photo-album-plus' );
						$desc = __('The height of the map display.', 'wp-photo-album-plus' );
						$help = __('This setting is for embedded implementation only.', 'wp-photo-album-plus' );
						$slug = 'wppa_map_height';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '2', $name, $desc, $html, $help, wppa_opt( 'gpx_implementation' ) == 'wppa-plus-embedded' );

						$name = __('Google maps API key', 'wp-photo-album-plus' );
						$desc = __('Enter your Google maps api key here if you have one.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_map_apikey';
						$html = wppa_input($slug, '300px', '');
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('GPX Shortcode', 'wp-photo-album-plus' );
						$desc = __('The shortcode to be used for the gpx feature.', 'wp-photo-album-plus' );
						$help = __('Enter / modify the shortcode to be generated for the gpx plugin. It must contain w#lat and w#lon as placeholders for the latitude and longitude.', 'wp-photo-album-plus' );
						$help .= '<br>' . __('This item is required for using an external Google maps viewer plugin only', 'wp-photo-album-plus' );
						$slug = 'wppa_gpx_shortcode';
						$html = wppa_input($slug, '500px');
						wppa_setting_new($slug, '4', $name, $desc, $html, $help, wppa_opt( 'gpx_implementation' ) == 'external-plugin' );

						$name = __('Zoom level', 'wp-photo-album-plus' );
						$desc = __('The zoomlevel for GPX maps', 'wp-photo-album-plus' );
						$help = __('This setting is for embedded implementation only.', 'wp-photo-album-plus' );
						$slug = 'wppa_geo_zoom';
						$opts = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25',);
						$vals = $opts;
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '5', $name, $desc, $html, $help, wppa_opt( 'gpx_implementation' ) == 'wppa-plus-embedded' );

						wppa_setting_box_footer_new();
					}
				}
				break;

				case 'custom': {
					// Album custom data fields configuration
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						if ( wppa_switch( 'album_custom_fields' ) ) {
							$coldef = array( 	'#' => '24px;',
												__('Name', 'wp-photo-album-plus' ) => 'auto;',
												__('Description', 'wp-photo-album-plus' ) => 'auto;',
												__('Custom caption', 'wp-photo-album-plus' ) => 'auto;',
												__('Visible', 'wp-photo-album-plus' ) => 'auto;',
												__('Editable', 'wp-photo-album-plus' ) => 'auto;',
												__('Help', 'wp-photo-album-plus' ) => '24px;',
												);


							wppa_setting_tab_description($desc);
							wppa_setting_box_header_new($tab, $coldef);

							for ( $i = '0'; $i < '10'; $i++ ) {
								$name = sprintf(__('Name, vis, edit %s', 'wp-photo-album-plus' ), $i);
								$desc = sprintf(__('The caption for field %s, visibility and editability at frontend.', 'wp-photo-album-plus' ), $i);
								$help = sprintf(__('If you check the first box, the value of this field is displayable in photo descriptions at the frontend with keyword w#c%s', 'wp-photo-album-plus' ), $i);
								$help .= '<br>'.__('If you check the second box, the value of this field is editable at the frontend new style dialog.', 'wp-photo-album-plus' );
								$slug1 = 'wppa_album_custom_caption_'.$i;
								$html1 = wppa_input($slug1, '300px');
								$slug2 = 'wppa_album_custom_visible_'.$i;
								$html2 = wppa_checkbox($slug2);
								$slug3 = 'wppa_album_custom_edit_'.$i;
								$html3 = wppa_checkbox($slug3);
								$html = array($html1, $html2, $html3);
								wppa_setting_new(array($slug1,$slug2,$slug3), $i, $name, $desc, $html, $help);
							}

							wppa_setting_box_footer_new();
						}
					}
					// Photo custom data fields configuration
					{
						$desc = $wppa_subtab_names[$tab]['2'];
						if ( wppa_switch( 'custom_fields' ) ) {
							$coldef = array( 	'#' => '24px;',
												__('Name', 'wp-photo-album-plus' ) => 'auto;',
												__('Description', 'wp-photo-album-plus' ) => 'auto;',
												__('Custom caption', 'wp-photo-album-plus' ) => 'auto;',
												__('Visible', 'wp-photo-album-plus' ) => 'auto;',
												__('Editable', 'wp-photo-album-plus' ) => 'auto;',
												__('Default', 'wp-photo-album-plus' ) => 'auto;',
												__('Help', 'wp-photo-album-plus' ) => '24px;',
												);


							wppa_setting_tab_description($desc);
							wppa_setting_box_header_new($tab, $coldef);

							$opts = array( '',
										   'Graphic name',
										   'Creation date',
										   'Photographer',
										   'City',
										   'State (Provinve)',
										   'Country',
										   'Sublocation',
										   'Subloc, City, State, Country',
										   'Source',
										   'Copyright',
										   'Caption',
										   '--- multiline ---',
										   );

							$vals = array( '',
										   '2#005',
										   '2#055',
										   '2#080',
										   '2#090',
										   '2#095',
										   '2#101',
										   '2#092',
										   '2#092, 2#090, 2#095, 2#101',
										   '2#115',
										   '2#116',
										   '2#120',
										   'multi',
										   );

							/* This seems to be it
							const OBJECT_NAME                     = '005';
							const EDIT_STATUS                     = '007';
							const PRIORITY                        = '010';
							const CATEGORY                        = '015';
							const SUPPLEMENTAL_CATEGORY           = '020';
							const FIXTURE_IDENTIFIER              = '022';
							const KEYWORDS                        = '025';
							const RELEASE_DATE                    = '030';
							const RELEASE_TIME                    = '035';
							const SPECIAL_INSTRUCTIONS            = '040';
							const REFERENCE_SERVICE               = '045';
							const REFERENCE_DATE                  = '047';
							const REFERENCE_NUMBER                = '050';
							const CREATED_DATE                    = '055';
							const CREATED_TIME                    = '060';
							const ORIGINATING_PROGRAM             = '065';
							const PROGRAM_VERSION                 = '070';
							const OBJECT_CYCLE                    = '075';
							const CREATOR                         = '080';
							const CITY                            = '090';
							const PROVINCE_STATE                  = '095';
							const COUNTRY_CODE                    = '100';
							const COUNTRY                         = '101';
							const ORIGINAL_TRANSMISSION_REFERENCE = '103';
							const HEADLINE                        = '105';
							const CREDIT                          = '110';
							const SOURCE                          = '115';
							const COPYRIGHT_STRING                = '116';
							const CAPTION                         = '120';
							const LOCAL_CAPTION                   = '121';
							const CAPTION_WRITER                  = '122';
							*/

							for ( $i = '0'; $i < '10'; $i++ ) {
								$name = sprintf(__('Name, vis, edit %s dflt', 'wp-photo-album-plus' ), $i);
								$desc = sprintf(__('The caption for field %s, visibility and editability at frontend.', 'wp-photo-album-plus' ), $i);
								$help = sprintf(__('If you check the first box, the value of this field is displayable in photo descriptions at the frontend with keyword w#c%s', 'wp-photo-album-plus' ), $i);
								$help .= '<br>'.__('If you check the second box, the value of this field is editable at the frontend new style dialog.', 'wp-photo-album-plus' );
								$slug1 = 'wppa_custom_caption_'.$i;
								$html1 = wppa_input($slug1, '300px');
								$slug2 = 'wppa_custom_visible_'.$i;
								$html2 = wppa_checkbox($slug2);
								$slug3 = 'wppa_custom_edit_'.$i;
								$html3 = wppa_checkbox($slug3);
								$slug4 = 'wppa_custom_default_'.$i;
								$html4 = wppa_select($slug4, $opts, $vals);
								$html = array($html1, $html2, $html3, $html4);
								wppa_setting_new(array($slug1,$slug2,$slug3), $i, $name, $desc, $html, $help);
							}

							wppa_setting_box_footer_new();
						}
					}
				}
				break;

				case 'watermark': {
					// Watermark related settings
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Watermark file', 'wp-photo-album-plus' );
						$desc = __('The default watermarkfile to be used.', 'wp-photo-album-plus' );
						$help = __('Watermark files are of type png and reside in', 'wp-photo-album-plus' ) . ' ' . WPPA_UPLOAD_URL . '/watermarks/';
						$help .= '<br>'.__('A suitable watermarkfile typically consists of a transparent background and a black text or drawing.', 'wp-photo-album-plus' );
						$help .= '<br>'.sprintf(__('The watermark image will be overlaying the photo with %s%% transparency.', 'wp-photo-album-plus' ), (100-wppa_opt( 'watermark_opacity' )));
						$help .= '<br>'.__('You may also select one of the textual watermark types at the bottom of the selection list.', 'wp-photo-album-plus' );
						$slug = 'wppa_watermark_file';
						$html = '<select style="float:left; font-size:11px; height:20px; margin:0 4px 0 0; padding:0; " id="wppa_watermark_file" onchange="wppaAjaxUpdateOptionValue(\'watermark_file\', this)" >' . wppa_watermark_file_select( 'system' ) . '</select>';
						$html .= '<img id="img_watermark_file" src="'.wppa_get_imgdir().'star.ico" title="'.__('Setting unmodified', 'wp-photo-album-plus' ).'" style="padding-left:4px; float:left; height:16px; width:16px;" />';
						$html .= '<span style="float:left; margin-left:12px">'.__('position:', 'wp-photo-album-plus' ).'</span><select style="float:left; font-size:11px; height:20px; margin:0 0 0 20px; padding:0; "  id="wppa_watermark_pos" onchange="wppaAjaxUpdateOptionValue(\'watermark_pos\', this)" >' . wppa_watermark_pos_select( 'system' ) . '</select>';
						$html .= '<img id="img_watermark_pos" src="'.wppa_get_imgdir().'star.ico" title="'.__('Setting unmodified', 'wp-photo-album-plus' ).'" style="padding-left:4px; float:left; height:16px; width:16px;" />';
						wppa_setting_new(false, '1', $name, $desc, $html, $help);

						$name = __('Upload watermark', 'wp-photo-album-plus' );
						$desc = __('Upload a new watermark file', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_watermark_file_upload';
						$html = wppa_upload_form( $slug, $wppa_cur_tab, '.png' );
						wppa_setting_new(false, '2', $name, $desc, $html, $help);

						$name = __('Watermark opacity image', 'wp-photo-album-plus' );
						$desc = __('You can set the intensity of image watermarks here.', 'wp-photo-album-plus' );
						$help = __('The higher the number, the intenser the watermark. Value must be > 0 and <= 100.', 'wp-photo-album-plus' );
						$slug = 'wppa_watermark_opacity';
						$html = wppa_input($slug, '50px', '', '%');
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Textual watermark style', 'wp-photo-album-plus' );
						$desc = __('The way the textual watermarks look like', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_textual_watermark_type';
						$clas = 'wppa_watermark';
						$sopts = array( __('TV subtitle style', 'wp-photo-album-plus' ), __('White text on black background', 'wp-photo-album-plus' ), __('Black text on white background', 'wp-photo-album-plus' ), __('Reverse TV style (Utopia)', 'wp-photo-album-plus' ), __('White on transparent background', 'wp-photo-album-plus' ), __('Black on transparent background', 'wp-photo-album-plus' ) );
						$svals = array( 'tvstyle', 'whiteonblack', 'blackonwhite', 'utopia', 'white', 'black' );
						$font = wppa_opt( 'textual_watermark_font' );
						$onch = "wppaPlanUpdateWatermarkPreview();";
						$html = wppa_select($slug, $sopts, $svals, $onch);
						$preview = '<img './*style="background-color:#777;"*/' id="wm-type-preview" src="" />';
						wppa_setting_new($slug, '4', $name, $desc, $html.' '.$preview, $help, $clas);

						$name = __('Predefined watermark text', 'wp-photo-album-plus' );
						$desc = __('The text to use when --- pre-defined --- is selected.', 'wp-photo-album-plus' );
						$help = __('You may use the following keywords:', 'wp-photo-album-plus' );
						$help .= '<br>'.__('w#site, w#displayname, all standard photo keywords, iptc and exif keywords', 'wp-photo-album-plus' );
						$slug = 'wppa_textual_watermark_text';
						$html = wppa_textarea($slug, $name);
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						if ( function_exists( 'imagettfbbox' ) ) {

							$name = __('Textual watermark font', 'wp-photo-album-plus' );
							$desc = __('The font to use with textual watermarks.', 'wp-photo-album-plus' );
							$help = __('Except for the system font, are font files of type ttf and reside in', 'wp-photo-album-plus' ) . ' ' . WPPA_UPLOAD_URL . '/fonts/';
							$slug = 'wppa_textual_watermark_font';
							$fopts = array( 'System' );
							$fvals = array( 'system' );
							$style = wppa_opt( 'textual_watermark_type' );
							$fonts = wppa_glob( WPPA_UPLOAD_PATH . '/fonts/*.ttf' );
							sort($fonts);
							foreach ( $fonts as $font ) {
								$f = basename($font);
								$f = preg_replace('/\.[^.]*$/', '', $f);
								$F = strtoupper(substr($f,0,1)).substr($f,1);
								$fopts[] = $F;
								$fvals[] = $f;
							}
							$onch = "wppaPlanUpdateWatermarkPreview();";
							$html = wppa_select($slug, $fopts, $fvals, $onch);
							$preview = '<img id="wm-font-preview" src="" />';
							wppa_setting_new($slug, '6', $name, $desc, $html.' '.$preview, $help, $clas);

							$name = __('Textual watermark font size', 'wp-photo-album-plus' );
							$desc = __('You can set the size of the truetype fonts only.', 'wp-photo-album-plus' );
							$help = __('System font can have size 1,2,3,4 or 5, in some stoneage fontsize units. Any value > 5 will be treated as 5.', 'wp-photo-album-plus' );
							$help .= '<br>'.__('Truetype fonts can have any positive integer size, if your PHPs GD version is 1, in pixels, in GD2 in points.', 'wp-photo-album-plus' );
							$help .= '<br>'.__('It is unclear how many pixels a point is...', 'wp-photo-album-plus' );
							$slug = 'wppa_textual_watermark_size';
							$html = wppa_input($slug, '50px', '', 'points');
							wppa_setting_new($slug, '7', $name, $desc, $html, $help);

							$name = __('Foreground color', 'wp-photo-album-plus' );
							$desc = __('Textual watermark foreground color (black).', 'wp-photo-album-plus' );
							$help = '';
							$slug = 'wppa_watermark_fgcol_text';
							$onch = "wppaPlanUpdateWatermarkPreview();";
							$html = wppa_input_color($slug, '100px;', '', '', $onch );
							wppa_setting_new($slug, '8', $name, $desc, $html, $help);

							$name = __('Background color', 'wp-photo-album-plus' );
							$desc = __('Textual watermark background color (white).', 'wp-photo-album-plus' );
							$help = '';
							$slug = 'wppa_watermark_bgcol_text';
							$onch = "wppaPlanUpdateWatermarkPreview();";
							$html = wppa_input_color($slug, '100px;', '', '', $onch );
							wppa_setting_new($slug, '9', $name, $desc, $html, $help);

							$name = __('Upload watermark font', 'wp-photo-album-plus' );
							$desc = __('Upload a new watermark font file', 'wp-photo-album-plus' );
							$help = __('Upload truetype fonts (.ttf) only, and test if they work on your server platform.', 'wp-photo-album-plus' );
							$slug = 'wppa_watermark_font_upload';
							$html = wppa_upload_form( $slug, $wppa_cur_tab, '.ttf' );
							wppa_setting_new(false, '10', $name, $desc, $html, $help);

							$name = __('Watermark opacity text', 'wp-photo-album-plus' );
							$desc = __('You can set the intensity of a text watermarks here.', 'wp-photo-album-plus' );
							$help = __('The higher the number, the intenser the watermark. Value must be > 0 and <= 100.', 'wp-photo-album-plus' );
							$slug = 'wppa_watermark_opacity_text';
							$html = wppa_input($slug, '50px', '', '%');
							wppa_setting_new($slug, '11', $name, $desc, $html, $help);

							$name = __('Preview', 'wp-photo-album-plus' );
							$desc = __('A preview. Keywords in descriptions or predefined text are not translated.', 'wp-photo-album-plus' );
							$help = __('To see the changes: refresh the page', 'wp-photo-album-plus' );
							$slug = 'wppa_watermark_preview';
							$tr = floor( 127 * ( 100 - wppa_opt( 'watermark_opacity_text' ) ) / 100 );
							$args = array( 'id' => '0', 'url' => true, 'width' => '1000', 'height' => '400', 'transp' => $tr );
							$html = '
								<div
									style="float:left; text-align:center; max-width:400px; overflow:hidden; background-image:url('.WPPA_UPLOAD_URL.'/fonts/turkije.jpg);"
									>
									<img id="wppa-watermark-preview"
										src="'.wppa_create_textual_watermark_file( $args ).'?ver='.rand(0, 4711).'"
									/>
								</div>
								<div style="clear:both;"></div>';
							wppa_setting_new($slug, '12', $name, $desc, $html, $help);
						}

						$name = __('Watermark thumbnails', 'wp-photo-album-plus' );
						$desc = __('Watermark also the thumbnail image files.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_watermark_thumbs';
						$html = wppa_checkbox($slug);
						$clas = 'wppa_watermark';
						$tags = 'water,thumb';
						wppa_setting_new($slug, '13', $name, $desc, $html, $help);

						$name = __('Watermark size', 'wp-photo-album-plus' );
						$desc = __('The size of the image based watermark in percents of the image width', 'wp-photo-album-plus' );
						$help = __('Select a value, --- off --- means: use the watermark image as is', 'wp-photo-album-plus' );
						$slug = 'wppa_watermark_size';
						$opts = array( __('--- off ---', 'wp-photo-album-plus' ), '10%', '20%', '25%', '30%', '40%', '50%', '60%', '70%', '80%', '90%' );
						$vals = array( '0', '10', '20', '25', '30', '40', '50', '60', '70', '80', '90' );
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '14', $name, $desc, $html, $help);

						$name = __('Watermark margin', 'wp-photo-album-plus' );
						$desc = __('The margin for the watermark from the edge of the image', 'wp-photo-album-plus' );
						$help = __('A value > 1 means pixels, a value < 1 means fraction. E.g enter 0.12 for 12%', 'wp-photo-album-plus' );
						$slug = 'wppa_watermark_margin';
						$html = wppa_input($slug, '40px;');
						wppa_setting_new($slug, '15', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
				}
				break;

				case 'constants': {
					// System constants (read only)
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);

						$coldef = array( 	__('#', 'wp-photo-album-plus' ) => '24px;',
											__('Name', 'wp-photo-album-plus' ) => 'auto;',
											__('Description', 'wp-photo-album-plus' ) => 'auto;',
											__('Value', 'wp-photo-album-plus' ) => 'auto;',
											__('Download', 'wp-photo-album-plus' ) => 'auto;',
											__('Help', 'wp-photo-album-plus' ) => '24px;',
											);
						wppa_setting_box_header_new($tab, $coldef);

						$name = 'WPPA_ALBUMS';
						$desc = __('Albums db table name.', 'wp-photo-album-plus' );
						$help = '';
						$html1 = WPPA_ALBUMS . wppa_see_also( 'miscadv', '1', '13' );
						$html2 = 	'<a onclick="wppaExportDbTable(\'' . WPPA_ALBUMS . '\')" >' .
										__('Download', 'wp-photo-album-plus' ) . ' ' . WPPA_ALBUMS . '.csv' .
									'</a> ' .
									'<img id="' . WPPA_ALBUMS . '-spin" src="' . wppa_get_imgdir( 'spinner.gif' ) . '" style="display:none;" />';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '1', $name, $desc, $html, $help);

						$name = 'WPPA_PHOTOS';
						$desc = __('Photos db table name.', 'wp-photo-album-plus' );
						$help = '';
						$html1 = WPPA_PHOTOS;
						$html2 =  	'<a onclick="wppaExportDbTable(\'' . WPPA_PHOTOS . '\')" >' .
										__('Download', 'wp-photo-album-plus' ) . ' ' . WPPA_PHOTOS . '.csv' .
									'</a> ' .
									'<img id="' . WPPA_PHOTOS . '-spin" src="' . wppa_get_imgdir( 'spinner.gif' ) . '" style="display:none;" />';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '2', $name, $desc, $html, $help);

						$name = 'WPPA_RATING';
						$desc = __('Rating db table name.', 'wp-photo-album-plus' );
						$help = '';
						$html1 = WPPA_RATING;
						$html2 = 	'<a onclick="wppaExportDbTable(\'' . WPPA_RATING . '\')" >' .
										__('Download', 'wp-photo-album-plus' ) . ' ' . WPPA_RATING . '.csv' .
									'</a> ' .
									'<img id="' . WPPA_RATING . '-spin" src="' . wppa_get_imgdir( 'spinner.gif' ) . '" style="display:none;" />';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '3', $name, $desc, $html, $help);

						$name = 'WPPA_COMMENTS';
						$desc = __('Comments db table name.', 'wp-photo-album-plus' );
						$help = '';
						$html1 = WPPA_COMMENTS;
						$html2 = 	'<a onclick="wppaExportDbTable(\'' . WPPA_COMMENTS . '\')" >' .
										__('Download', 'wp-photo-album-plus' ) . ' ' . WPPA_COMMENTS . '.csv' .
									'</a> ' .
									'<img id="' . WPPA_COMMENTS . '-spin" src="' . wppa_get_imgdir( 'spinner.gif' ) . '" style="display:none;" />';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '4', $name, $desc, $html, $help);

						$name = 'WPPA_IPTC';
						$desc = __('IPTC db table name.', 'wp-photo-album-plus' );
						$help = '';
						$html1 = WPPA_IPTC;
						$html2 = 	'<a onclick="wppaExportDbTable(\'' . WPPA_IPTC . '\')" >' .
										__('Download', 'wp-photo-album-plus' ) . ' ' . WPPA_IPTC . '.csv' .
									'</a> ' .
									'<img id="' . WPPA_IPTC . '-spin" src="' . wppa_get_imgdir( 'spinner.gif' ) . '" style="display:none;" />';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '5', $name, $desc, $html, $help);

						$name = 'WPPA_EXIF';
						$desc = __('EXIF db table name.', 'wp-photo-album-plus' );
						$help = '';
						$html1 = WPPA_EXIF;
						$html2 =  	'<a onclick="wppaExportDbTable(\'' . WPPA_EXIF . '\')" >' .
										__('Download', 'wp-photo-album-plus' ) . ' ' . WPPA_EXIF . '.csv' .
									'</a> ' .
									'<img id="' . WPPA_EXIF . '-spin" src="' . wppa_get_imgdir( 'spinner.gif' ) . '" style="display:none;" />';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '6', $name, $desc, $html, $help);

						$name = 'WPPA_INDEX';
						$desc = __('Index db table name.', 'wp-photo-album-plus' );
						$help = '';
						$html1 = WPPA_INDEX;
						$html2 = 	'<a onclick="wppaExportDbTable(\'' . WPPA_INDEX . '\')" >' .
										__('Download', 'wp-photo-album-plus' ) . ' ' . WPPA_INDEX . '.csv' .
									'</a> ' .
									'<img id="' . WPPA_INDEX . '-spin" src="' . wppa_get_imgdir( 'spinner.gif' ) . '" style="display:none;" />';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '7', $name, $desc, $html, $help);

						$name = 'WPPA_SESSION';
						$desc = __('Session db table name.', 'wp-photo-album-plus' );
						$help = '';
						$html1 = WPPA_SESSION;
						$html2 = sprintf( __('Download %s is useless', 'wp-photo-album-plus' ), WPPA_SESSION );
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '8', $name, $desc, $html, $help);

						$name = 'WPPA_FILE';
						$desc = __('Plugins main file name.', 'wp-photo-album-plus' );
						$help = '';
						$html1 = WPPA_FILE;
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '9', $name, $desc, $html, $help);

						$name = 'ABSPATH';
						$desc = __('WP absolute path.', 'wp-photo-album-plus' );
						$help = '';
						$html1 = ABSPATH;
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '10', $name, $desc, $html, $help);

						$name = 'WPPA_ABSPATH';
						$desc = __('ABSPATH windows proof', 'wp-photo-album-plus' );
						$help = '';
						$html1 =  WPPA_ABSPATH;
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '11', $name, $desc, $html, $help);

						$name = 'WPPA_PATH';
						$desc = __('Path to plugins directory.', 'wp-photo-album-plus' );
						$help = '';
						$html1 = WPPA_PATH;
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '12', $name, $desc, $html, $help);

						$name = 'WPPA_NAME';
						$desc = __('Plugins directory name.', 'wp-photo-album-plus' );
						$help = '';
						$html1 = WPPA_NAME;
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '13', $name, $desc, $html, $help);

						$name = 'WPPA_URL';
						$desc = __('Plugins directory url.', 'wp-photo-album-plus' );
						$help = '';
						$html1 = WPPA_URL;
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '14', $name, $desc, $html, $help);

						$name = 'WPPA_UPLOAD';
						$desc = __('The relative upload directory.', 'wp-photo-album-plus' );
						$help = '';
						$html1 = WPPA_UPLOAD;
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '15', $name, $desc, $html, $help);

						$name = 'WPPA_UPLOAD_PATH';
						$desc = __('The upload directory path.', 'wp-photo-album-plus' );
						$help = '';
						$html1 = WPPA_UPLOAD_PATH;
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '16', $name, $desc, $html, $help);

						$name = 'WPPA_UPLOAD_URL';
						$desc = __('The upload directory url.', 'wp-photo-album-plus' );
						$help = '';
						$html1 = WPPA_UPLOAD_URL;
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '17', $name, $desc, $html, $help);

						$name = 'WPPA_DEPOT';
						$desc = __('The relative depot directory.', 'wp-photo-album-plus' );
						$help = '';
						$html1 = WPPA_DEPOT;
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '18', $name, $desc, $html, $help);

						$name = 'WPPA_DEPOT_PATH';
						$desc = __('The depot directory path.', 'wp-photo-album-plus' );
						$help = '';
						$html1 = WPPA_DEPOT_PATH;
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '19', $name, $desc, $html, $help);

						$name = 'WPPA_DEPOT_URL';
						$desc = __('The depot directory url.', 'wp-photo-album-plus' );
						$help = '';
						$html1 = WPPA_DEPOT_URL;
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '20', $name, $desc, $html, $help);

						$name = 'WPPA_CONTENT_PATH';
						$desc = __('The path to wp-content.', 'wp-photo-album-plus' );
						$help = '';
						$html1 = WPPA_CONTENT_PATH;
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '21', $name, $desc, $html, $help);

						$name = 'WPPA_CONTENT_URL';
						$desc = __('WP Content url.', 'wp-photo-album-plus' );
						$help = '';
						$html1 = WPPA_CONTENT_URL;
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '22', $name, $desc, $html, $help);

						$name = 'wp_upload_dir() : [\'basedir\']';
						$desc = __('WP Base upload dir.', 'wp-photo-album-plus' );
						$help = '';
						$wp_uploaddir = wp_upload_dir();
						$html1 = $wp_uploaddir['basedir'];
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '23', $name, $desc, $html, $help);

						$name = '$_SERVER[\'HTTP_HOST\']';
						$desc = '';
						$help = '';
						$html1 = $_SERVER['HTTP_HOST'];
						$html2 = '';
						$html = array( $html1, $html2 );
						wppa_setting_new(false, '24', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
				}
				break;

				case 'misc': {
					// Miscellaneous settings
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Album sequence default', 'wp-photo-album-plus' );
						$desc = __('Album sequence method.', 'wp-photo-album-plus' );
						$help = __('Specify the way the albums should be sequenced.', 'wp-photo-album-plus' );
						$slug = 'wppa_list_albums_by';
						$opts = array(	__('--- none ---', 'wp-photo-album-plus' ),
										__('Sequence #', 'wp-photo-album-plus' ),
										__('Name', 'wp-photo-album-plus' ),
										__('Random', 'wp-photo-album-plus' ),
										__('Timestamp', 'wp-photo-album-plus' ),
										__('Sequence # descending', 'wp-photo-album-plus' ),
										__('Name descending', 'wp-photo-album-plus' ),
										__('Timestamp descending', 'wp-photo-album-plus' ),
										);
						$vals = array(	'0',
										'1',
										'2',
										'3',
										'5',
										'-1',
										'-2',
										'-5'
										);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Photo sequence default', 'wp-photo-album-plus' );
						$desc = __('Photo sequence method.', 'wp-photo-album-plus' );
						$help = __('Specify the way the photos should be ordered. This is the default setting. You can overrule the default sorting order on a per album basis.', 'wp-photo-album-plus' );
						$slug = 'wppa_list_photos_by';
						$opts = array(	__('--- none ---', 'wp-photo-album-plus' ),
										__('Sequence #', 'wp-photo-album-plus' ),
										__('Name', 'wp-photo-album-plus' ),
										__('Random', 'wp-photo-album-plus' ),
										__('Rating mean value', 'wp-photo-album-plus' ),
										__('Number of votes', 'wp-photo-album-plus' ),
										__('Timestamp', 'wp-photo-album-plus' ),
										__('EXIF Date', 'wp-photo-album-plus' ),
										__('Sequence # descending', 'wp-photo-album-plus' ),
										__('Name descending', 'wp-photo-album-plus' ),
										__('Rating mean value descending', 'wp-photo-album-plus' ),
										__('Number of votes descending', 'wp-photo-album-plus' ),
										__('Timestamp descending', 'wp-photo-album-plus' ),
										__('EXIF Date descending', 'wp-photo-album-plus' )
										);
						$vals = array(	'0',
										'1',
										'2',
										'3',
										'4',
										'6',
										'5',
										'7',
										'-1',
										'-2',
										'-4',
										'-6',
										'-5',
										'-7'
										);
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Default coverphoto selection', 'wp-photo-album-plus' );
						$desc = __('Default select cover photo method.', 'wp-photo-album-plus' );
						$help = __('The coverphoto slection method can be overruled on the edit album page.', 'wp-photo-album-plus' );
						$help .= '<br>' . __('Alternatively an individual photo can be selected on the edit album page.', 'wp-photo-album-plus' );
						$opts = array(	__('--- random ---', 'wp-photo-album-plus' ),
										__('--- random featured ---', 'wp-photo-album-plus' ),
										__('--- most recent added ---', 'wp-photo-album-plus' ),
										__('--- random from (sub-)sub albums ---', 'wp-photo-album-plus' ),
										__('--- most recent from (sub-)sub albums ---', 'wp-photo-album-plus' ),
										__('--- according to albums photo sequence ---', 'wp-photo-album-plus' ),
										);
						$vals = array('-9', '-1', '-2', '-3', '-4', '-5');
						$slug1 = 'wppa_main_photo';
						$slug2 = 'wppa_main_photo_random_once';
						$slug3 = 'wppa_main_photo_reset';
						$onch = "wppaSlaveSelectedOr('wppa_main_photo--3','wppa_main_photo--9','main-fix')";
						$html = wppa_select($slug1, $opts, $vals, $onch);
						if ( wppa_opt('main_photo') == '-3' || wppa_opt('main_photo') == '-9' ) {
							$html .=
								'<span class="main-fix" style="float:left;margin:0 6px">' . __('Fix first found', 'wp-photo-album-plus' ) . wppa_checkbox($slug2) . '</span>' .
								'<span class="main-fix" style="float:left;margin:0 6px">' . __('Reset all', 'wp-photo-album-plus' ) . wppa_checkbox($slug3) . '</span>';
						}
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Related count', 'wp-photo-album-plus' );
						$desc = __('The default maximum number of related photos to find.', 'wp-photo-album-plus' );
						$help = __('When using shortcodes like [wppa type="album" album="#related,desc,23"], the maximum number is 23. Omitting the number gives the maximum of this setting.', 'wp-photo-album-plus' );
						$slug = 'wppa_related_count';
						$html = wppa_input($slug, '40px', '', __('photos', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Max file name length', 'wp-photo-album-plus' );
						$desc = __('The max length of a photo file name excluding the extension.', 'wp-photo-album-plus' );
						$help = __('A setting of 0 means: unlimited.', 'wp-photo-album-plus' );
						$slug = 'wppa_max_filename_length';
						$html = wppa_input($slug, '40px', '', __('chars', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Max photo name length', 'wp-photo-album-plus' );
						$desc = __('The max length of a photo name.', 'wp-photo-album-plus' );
						$help = __('A setting of 0 means: unlimited.', 'wp-photo-album-plus' );
						$slug = 'wppa_max_photoname_length';
						$html = wppa_input($slug, '40px', '', __('chars', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Default Video width', 'wp-photo-album-plus' );
						$desc = __('The width of most videos', 'wp-photo-album-plus' );
						$help = __('This setting can be overruled for individual videos on the photo admin pages.', 'wp-photo-album-plus' );
						$slug = 'wppa_video_width';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '7', $name, $desc, $html, $help, wppa_switch('enable_video'));

						$name = __('Default Video height', 'wp-photo-album-plus' );
						$desc = __('The height of most videos', 'wp-photo-album-plus' );
						$help = __('This setting can be overruled for individual videos on the photo admin pages.', 'wp-photo-album-plus' );
						$slug = 'wppa_video_height';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '8', $name, $desc, $html, $help, wppa_switch('enable_video'));

						$name = __('Grid video controls', 'wp-photo-album-plus' );
						$desc = __('Show the video controls on a video in a grid display', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_grid_video';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '9', $name, $desc, $html, $help, wppa_switch('enable_video'));

						wppa_setting_box_footer_new();
					}
					// Panorama related settings
					{
						$desc = $wppa_subtab_names[$tab]['2'];
						if ( wppa_switch( 'enable_panorama' ) ) {

							wppa_setting_tab_description($desc);
							wppa_setting_box_header_new($tab);

							$name = __( 'Control bar', 'wp-photo-album-plus' );
							$desc = __( 'Select when the control bar must be displayed', 'wp-photo-album-plus' );
							$help = '';
							$slug = 'wppa_panorama_control';
							$opts = array( 	__( 'Always', 'wp-photo-album-plus' ),
											__( 'On mobile only', 'wp-photo-album-plus' ),
											__( 'None', 'wp-photo-album-plus' ),
											);
							$vals = array(	'all',
											'mobile',
											'none',
											);
							$html = wppa_select($slug, $opts, $vals);
							wppa_setting_new( $slug, '1', $name, $desc, $html, $help );

							$name = __( 'Manual movement', 'wp-photo-album-plus' );
							$desc = __( 'Select if movement touch move is allowed', 'wp-photo-album-plus' );
							$help = '';
							$slug = 'wppa_panorama_manual';
							$opts = array(  __( 'Yes', 'wp-photo-album-plus' ),
											__( 'No', 'wp-photo-album-plus' ),
											);
							$vals = array( 	'all',
											'none',
											);
							$html = wppa_select($slug, $opts, $vals);
							wppa_setting_new( $slug, '2', $name, $desc, $html, $help );

							$name = __( 'Auto panning', 'wp-photo-album-plus' );
							$desc = __( 'Start the display panning', 'wp-photo-album-plus' );
							$help = '';
							$slug = 'wppa_panorama_autorun';
							$opts = array( 	__( 'no', 'wp-photo-album-plus' ),
											__( 'left', 'wp-photo-album-plus' ),
											__( 'right', 'wp-photo-album-plus' ),
											);
							$vals = array( 	'none',
											'left',
											'right',
											);
							$html = wppa_select($slug, $opts, $vals);
							wppa_setting_new( $slug, '3', $name, $desc, $html, $help );

							$name = __( 'Auto panning speed', 'wp-photo-album-plus' );
							$desc = __( 'The speed of the auto panning movement', 'wp-photo-album-plus' );
							$help = '';
							$slug = 'wppa_panorama_autorun_speed';
							$opts = array( 	__( 'very slow', 'wp-photo-album-plus' ),
											__( 'slow', 'wp-photo-album-plus' ),
											__( 'normal', 'wp-photo-album-plus' ),
											__( 'fast', 'wp-photo-album-plus' ),
											__( 'very fast', 'wp-photo-album-plus' ),
											);
							$vals = array( '1', '2', '3', '5', '8' );
							$html = wppa_select($slug, $opts, $vals);
							wppa_setting_new( $slug, '4', $name, $desc, $html, $help );

							$name = __( 'Zoom sensitivity', 'wp-photo-album-plus' );
							$desc = __( 'The speed of zooming by mouse wheel', 'wp-photo-album-plus' );
							$help = '';
							$slug = 'wppa_panorama_wheel_sensitivity';
							$opts = array( 	__( 'very low', 'wp-photo-album-plus' ),
											__( 'low', 'wp-photo-album-plus' ),
											__( 'normal', 'wp-photo-album-plus' ),
											__( 'high', 'wp-photo-album-plus' ),
											__( 'very high', 'wp-photo-album-plus' ),
											);
							$vals = array( '1', '2', '3', '5', '8' );
							$html = wppa_select($slug, $opts, $vals);
							wppa_setting_new( $slug, '5', $name, $desc, $html, $help );

							$name = __( 'Initial zoom spheric panorama', 'wp-photo-album-plus' );
							$desc = __( 'Select initial viewing angle for spheric panoramas', 'wp-photo-album-plus' );
							$help = '';
							$slug = 'wppa_panorama_fov';
							$opts = array( '45&deg;', '50&deg;', '55&deg;', '60&deg;', '65&deg;', '70&deg;', '75&deg;', '80&deg;', '85&deg;', '90&deg;' );
							$vals = array( '45', '50', '55', '60', '65', '70', '75', '80', '85', '90' );
							$html = wppa_select($slug, $opts, $vals);
							wppa_setting_new( $slug, '6', $name, $desc, $html, $help );

							$name = __( 'Panorama max width spheric', 'wp-photo-album-plus' );
							$desc = __( 'The max size when a spheric panorama is converted to 360&deg;', 'wp-photo-album-plus' );
							$help = '';
							$slug = 'wppa_panorama_max';
							$opts = array( '4000', '5000', '6000', '8000', '10000' );
							$vals = $opts;
							$html = wppa_select($slug, $opts, $vals);
							wppa_setting_new( $slug, '7', $name, $desc, $html, $help );

							wppa_setting_box_footer_new();
						}
						else {
							wppa_bump_subtab_id();
						}
					}
				}
				break;

				case 'miscadv': {
					// Advanced miscellaneous settings
					{
						$desc = $wppa_subtab_names[$tab]['1'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('JPG image quality', 'wp-photo-album-plus' );
						$desc = __('The jpg quality when photos are downsized', 'wp-photo-album-plus' );
						$help = __('The higher the number the better the quality but the larger the file', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Possible values 20..100', 'wp-photo-album-plus' );
						$slug = 'wppa_jpeg_quality';
						$html = wppa_input($slug, '50px');
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Enable <b>in-line</b> settings', 'wp-photo-album-plus' );
						$desc = __('Activates shortcode [wppa_set].', 'wp-photo-album-plus' );
						$help = __('Syntax: [wppa_set name="any wppa setting" value="new value"]', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Example: [wppa_set name="wppa_thumbtype" value="masonry-v"] sets the thumbnail type to vertical masonry style', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Do not forget to reset with [wppa_set]', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Use with great care! There is no check on validity of values!', 'wp-photo-album-plus' );
						$slug = 'wppa_enable_shortcode_wppa_set';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help);

						$name = __('Minimum tags', 'wp-photo-album-plus' );
						$desc = __('These tags exist even when they do not occur in any photo.', 'wp-photo-album-plus' );
						$help = __('Enter tags, separated by comma\'s (,)', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Tags exist when they appear on any photo, and vanish when they do no longer appear. Except the tags you list here; they exist always.', 'wp-photo-album-plus' );
						$slug = 'wppa_minimum_tags';
						$html = wppa_input($slug, '300px');
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('Show minimum tags only', 'wp-photo-album-plus' );
						$desc = __('Shows only the minimum tags in the photo admin tags seclection box', 'wp-photo-album-plus' );
						$help = __('To limit the use of tags to these tags only, also tick New tags restricted', 'wp-photo-album-plus' );
						$help .= wppa_see_also( 'admin', '4', '9' );
						$slug = 'wppa_predef_tags_only';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('Login link', 'wp-photo-album-plus' );
						$desc = __('Modify this link if you have a custom login page.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_login_url';
						$html = wppa_input($slug, '300px');
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Cache root', 'wp-photo-album-plus' );
						$desc = __('The root folder of your caching plugin', 'wp-photo-album-plus' ) . ': <span style="float:right">' . WPPA_CONTENT_PATH . '/</span>';
						$help = __('If you have a caching plugin, make sure this setting points to the root folder where the cache files are stored', 'wp-photo-album-plus' );
						$slug = 'wppa_cache_root';
						$html = wppa_input($slug, '300px');
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('Direct comment', 'wp-photo-album-plus' );
						$desc = __('Enable direct commenting and rating from remote source', 'wp-photo-album-plus' );
						$help = __('This setting has only effect when Encrypted links is mandatory', 'wp-photo-album-plus' );
						$help .= '<br>' . __('Use with care, and only in special situations!', 'wp-photo-album-plus' );
						$slug = 'wppa_direct_comment';
						$html = wppa_checkbox($slug) . wppa_see_also( 'links', '1', '5.6' );
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('Extended resize count', 'wp-photo-album-plus' );
						$desc = __('Number of extra resize handler actions', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_extended_resize_count';
						$opts = array('0','1','2','3','4','5','6','10','15','20',__( 'infinite', 'wp-photo-album-plus' ) );
						$vals = array('0','1','2','3','4','5','6','10','15','20','-1');
						$html = wppa_select($slug, $opts, $vals).' '.__('times', 'wp-photo-album-plus' );
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						$name = __('Extended resize delay', 'wp-photo-album-plus' );
						$desc = __('Delay time of extra resize handler actions', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_extended_resize_delay';
						$opts = array('5','10','20','50','100','150','200','300','500','700','1000');
						$vals = $opts;
						$html = wppa_select($slug, $opts, $vals).' ms.';
						wppa_setting_new($slug, '10', $name, $desc, $html, $help);

						$name = __('Load nicescroller always', 'wp-photo-album-plus' );
						$desc = __('Loads nicescroller js on all pages', 'wp-photo-album-plus' );
						$help = __('Tick this if you use nicescroller anywhere and it is not being loaded', 'wp-photo-album-plus' );
						$slug = 'wppa_load_nicescroller';
						$html = wppa_checkbox($slug) . wppa_see_also( 'layout', 1, 8 ) . wppa_see_also( 'system', 1, '34.35' );
						wppa_setting_new($slug, '11', $name, $desc, $html, $help);

						$name = __('Nicescroller on mobile', 'wp-photo-album-plus' );
						$desc = __('Use nicescroller also on mobile', 'wp-photo-album-plus');
						$help = __('Use with care, mobile scrollbars are nice by themselves', 'wp-photo-album-plus' );
						$slug = 'wppa_nice_mobile';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '12', $name, $desc, $html, $help);

						$name = __('CSV file separator', 'wp-photo-album-plus' );
						$desc = __('Select the separator to be used for csv file data', 'wp-photo-album-plus' );
						$help = __('This separator is used both during import and export', 'wp-photo-album-plus' );
						$slug = 'wppa_csv_sep';
						$opts = array( 'comma (,)', 'semicolon (;)' );
						$vals = array( ',', ';' );
						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '13', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
					// Logging
					{
						$desc = $wppa_subtab_names[$tab]['2'];
						wppa_setting_tab_description($desc);

						$coldef = array(
										__( '#', 'wp-photo-album-plus' ) => '24px',
										__( 'Name', 'wp-photo-album-plus' ) => 'auto',
										__( 'Description', 'wp-photo-album-plus' ) => 'auto',
										__( 'Enable', 'wp-photo-album-plus' ) => 'auto',
										__( 'Stack', 'wp-photo-album-plus' ) => 'auto',
										__( 'Url', 'wp-photo-album-plus' ) => 'auto',
										__( 'Help', 'wp-photo-album-plus' ) => '24px',
									);
						wppa_setting_box_header_new($tab, $coldef);

						$name = __('Enable extended logging', 'wp-photo-album-plus');
						$desc = __('Enable the logging of non-error events', 'wp-photo-album-plus');
						$help = __('This can help you troubleshoot behaviour and performance issues', 'wp-photo-album-plus');
						$slug = 'wppa_enable_ext_logging';
						$onch = "wppaSlaveChecked(this,'extlog')";
						$html = array(wppa_checkbox($slug, $onch), '', '');
						wppa_setting_new($slug, '0', $name, $desc, $html, $help);

						$name = __('Log Errors', 'wp-photo-album-plus' );
						$desc = __('Keep track of wppa internal errors.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_log_errors';
						$slug2 = 'wppa_log_errors_stack';
						$slug3 = 'wppa_log_errors_url';
						$html1 = wppa_checkbox($slug1);
						$html2 = wppa_checkbox($slug2);
						$html3 = wppa_checkbox($slug3);
						$html = array($html1, $html2, $html3);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Log Warnings', 'wp-photo-album-plus' );
						$desc = __('Keep track of wppa internal warnings.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_log_warnings';
						$slug2 = 'wppa_log_warnings_stack';
						$slug3 = 'wppa_log_warnings_url';
						$html1 = wppa_checkbox($slug1);
						$html2 = wppa_checkbox($slug2);
						$html3 = wppa_checkbox($slug3);
						$html = array($html1, $html2, $html3);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('Log Cron', 'wp-photo-album-plus' );
						$desc = __('Keep track of cron activity in the wppa logfile.', 'wp-photo-album-plus' );
						$help = '';
						$help = '';
						$slug1 = 'wppa_log_cron';
						$slug2 = 'wppa_log_cron_stack';
						$slug3 = 'wppa_log_cron_url';
						$html1 = wppa_checkbox($slug1);
						$html2 = wppa_checkbox($slug2);
						$html3 = wppa_checkbox($slug3);
						$html = array($html1, $html2, $html3);
						wppa_setting_new($slug, '3', $name, $desc, $html, $help, wppa_switch('enable_ext_logging'), 'extlog');

						$name = __('Log Ajax', 'wp-photo-album-plus' );
						$desc = __('Keep track of ajax activity in the wppa logfile.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_log_ajax';
						$slug2 = 'wppa_log_ajax_stack';
						$slug3 = 'wppa_log_ajax_url';
						$html1 = wppa_checkbox($slug1);
						$html2 = wppa_checkbox($slug2);
						$html3 = wppa_checkbox($slug3);
						$html = array($html1, $html2, $html3);
						wppa_setting_new($slug, '4', $name, $desc, $html, $help, wppa_switch('enable_ext_logging'), 'extlog');

						$name = __('Log Comments', 'wp-photo-album-plus' );
						$desc = __('Keep track of commenting activity in the wppa logfile.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_log_comments';
						$slug2 = 'wppa_log_comments_stack';
						$slug3 = 'wppa_log_comments_url';
						$html1 = wppa_checkbox($slug1);
						$html2 = wppa_checkbox($slug2);
						$html3 = wppa_checkbox($slug3);
						$html = array($html1, $html2, $html3);
						wppa_setting_new($slug, '5', $name, $desc, $html, $help, wppa_switch('enable_ext_logging'), 'extlog');

						$name = __('Log File events', 'wp-photo-album-plus' );
						$desc = __('Keep track of dir/file creations.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_log_fso';
						$slug2 = 'wppa_log_fso_stack';
						$slug3 = 'wppa_log_fso_url';
						$html1 = wppa_checkbox($slug1);
						$html2 = wppa_checkbox($slug2);
						$html3 = wppa_checkbox($slug3);
						$html = array($html1, $html2, $html3);
						wppa_setting_new($slug, '6', $name, $desc, $html, $help, wppa_switch('enable_ext_logging'), 'extlog');

						$name = __('Log Debug messages', 'wp-photo-album-plus' );
						$desc = __('Keep track of debug messages.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_log_debug';
						$slug2 = 'wppa_log_debug_stack';
						$slug3 = 'wppa_log_debug_url';
						$html1 = wppa_checkbox($slug1);
						$html2 = wppa_checkbox($slug2);
						$html3 = wppa_checkbox($slug3);
						$html = array($html1, $html2, $html3);
						wppa_setting_new($slug, '7', $name, $desc, $html, $help, wppa_switch('enable_ext_logging'), 'extlog');

						$name = __('Log Database queries', 'wp-photo-album-plus' );
						$desc = __('Keep track of database messages.', 'wp-photo-album-plus' );
						$help = __('Only partially implemented', 'wp-photo-album-plus');
						$slug1 = 'wppa_log_database';
						$slug2 = 'wppa_log_database_stack';
						$slug3 = 'wppa_log_database_url';
						$html1 = wppa_checkbox($slug1);
						$html2 = wppa_checkbox($slug2);
						$html3 = wppa_checkbox($slug3);
						$html = array($html1, $html2, $html3);
						wppa_setting_new($slug, '8', $name, $desc, $html, $help, wppa_switch('enable_ext_logging'), 'extlog');

						$name = __('Log Emails sent', 'wp-photo-album-plus' );
						$desc = __('Keep track of sending emails.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_log_email';
						$slug2 = 'wppa_log_email_stack';
						$slug3 = 'wppa_log_email_url';
						$html1 = wppa_checkbox($slug1);
						$html2 = wppa_checkbox($slug2);
						$html3 = wppa_checkbox($slug3);
						$html = array($html1, $html2, $html3);
						wppa_setting_new($slug, '9', $name, $desc, $html, $help, wppa_switch('enable_ext_logging'), 'extlog');

						$name = __('Log Timings', 'wp-photo-album-plus' );
						$desc = __('Keep track of various timings.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_log_tim';
						$slug2 = 'wppa_log_tim_stack';
						$slug3 = 'wppa_log_tim_url';
						$html1 = wppa_checkbox($slug1);
						$html2 = wppa_checkbox($slug2);
						$html3 = wppa_checkbox($slug3);
						$html = array($html1, $html2, $html3);
						wppa_setting_new($slug, '10', $name, $desc, $html, $help, wppa_switch('enable_ext_logging'), 'extlog');

						$name = __('Log Index', 'wp-photo-album-plus' );
						$desc = __('Keep track of index changes.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_log_idx';
						$slug2 = 'wppa_log_idx_stack';
						$slug3 = 'wppa_log_idx_url';
						$html1 = wppa_checkbox($slug1);
						$html2 = wppa_checkbox($slug2);
						$html3 = wppa_checkbox($slug3);
						$html = array($html1, $html2, $html3);
						wppa_setting_new($slug, '11', $name, $desc, $html, $help, wppa_switch('enable_ext_logging'), 'extlog');

						$name = __('Log Observations', 'wp-photo-album-plus' );
						$desc = __('Keep track of observations.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_log_obs';
						$slug2 = 'wppa_log_obs_stack';
						$slug3 = 'wppa_log_obs_url';
						$html1 = wppa_checkbox($slug1);
						$html2 = wppa_checkbox($slug2);
						$html3 = wppa_checkbox($slug3);
						$html = array($html1, $html2, $html3);
						wppa_setting_new($slug, '12', $name, $desc, $html, $help, wppa_switch('enable_ext_logging'), 'extlog');

						$name = __('Log Client', 'wp-photo-album-plus' );
						$desc = __('Keep track of client messages.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_log_cli';
						$slug2 = 'wppa_log_cli_stack';
						$slug3 = 'wppa_log_cli_url';
						$html1 = wppa_checkbox($slug1);
						$html2 = wppa_checkbox($slug2);
						$html3 = wppa_checkbox($slug3);
						$html = array($html1, $html2, $html3);
						wppa_setting_new($slug, '12', $name, $desc, $html, $help, wppa_switch('enable_ext_logging'), 'extlog');

						$name = __('Log Uploads', 'wp-photo-album-plus' );
						$desc = __('Keep track of uploads.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_log_upl';
						$slug2 = 'wppa_log_upl_stack';
						$slug3 = 'wppa_log_upl_url';
						$html1 = wppa_checkbox($slug1);
						$html2 = wppa_checkbox($slug2);
						$html3 = wppa_checkbox($slug3);
						$html = array($html1, $html2, $html3);
						wppa_setting_new($slug, '12', $name, $desc, $html, $help, wppa_switch('enable_ext_logging'), 'extlog');

						$name = __('Log Miscalleneous', 'wp-photo-album-plus' );
						$desc = __('Keep track of miscaleneous events.', 'wp-photo-album-plus' );
						$help = '';
						$slug1 = 'wppa_log_misc';
						$slug2 = 'wppa_log_misc_stack';
						$slug3 = 'wppa_log_misc_url';
						$html1 = wppa_checkbox($slug1);
						$html2 = wppa_checkbox($slug2);
						$html3 = wppa_checkbox($slug3);
						$html = array($html1, $html2, $html3);
						wppa_setting_new($slug, '15', $name, $desc, $html, $help, wppa_switch('enable_ext_logging'), 'extlog');

						$name = __('List WPPA Logfile', 'wp-photo-album-plus' );
						$desc = __('Show the content of wppa+ (error) log.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_list_errorlog';
						$html = array( wppa_popup_button( $slug ), '', '');
						wppa_setting_new($slug, '16', $name, $desc, $html, $help);

						$name = __('Purge WPPA logfile', 'wp-photo-album-plus' );
						$desc = __('Deletes the logfile', 'wp-photo-album-plus' );
						$help = __('The logfile will not grow forever, the max number of entries is 1000', 'wp-photo-album-plus' );
						$slug = 'wppa_errorlog_purge';
						$html = array( wppa_ajax_button(__('Do it!', 'wp-photo-album-plus' ), 'errorlog_purge', '0', true ), '', '');
						wppa_setting_new($slug, '17', $name, $desc, $html, $help);

						$name = __('WPPA Logfile on menu', 'wp-photo-album-plus' );
						$desc = __('Make list logfile a menu item', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_logfile_on_menu';
						$html = array( wppa_checkbox($slug), '', '' );
						wppa_setting_new($slug, '18', $name, $desc, $html, $help);

						$debug_log = WP_CONTENT_DIR . '/debug.log';
						if ( is_readable( $debug_log ) ) {
							$size = wppa_filesize( $debug_log );
							$name = __('List WP debug.log', 'wp-photo-album-plus');
							$desc = __('List the wp debug logfile', 'wp-photo-album-plus') . ' ' . __( 'Size=', 'wp-photo-album-plus' ) . $size;
							$help = '';
							$slug = 'wppa_list_debuglog';
							$html = array( wppa_popup_button( $slug ), '', '' );
							wppa_setting_new($slug, '19', $name, $desc, $html, $help);

							if ( is_writable( $debug_log ) ) {
								$name = __('Purge WP debuglog', 'wp-photo-album-plus' );
								$desc = __('Deletes the debuglogfile', 'wp-photo-album-plus' );
								$help = '';
								$slug = 'wppa_debuglog_purge';
								$html = array( wppa_ajax_button(__('Do it!', 'wp-photo-album-plus' ), 'debuglog_purge', '0', true ), '', '' );
								wppa_setting_new($slug, '20', $name, $desc, $html, $help);
							}
						}

						wppa_setting_box_footer_new();
					}
					// External services related settings and actions
					{
						$desc = $wppa_subtab_names[$tab]['3'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('CDN Service', 'wp-photo-album-plus' );
						$desc = __('Select a CDN Service you want to use.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_cdn_service';
						if ( PHP_VERSION_ID >= 50300 ) {
							$opts = array(	__('--- none ---', 'wp-photo-album-plus' ),
											__('Local', 'wp-photo-album-plus' ),
											'Cloudinary',
											__('Cloudinary in maintenance mode', 'wp-photo-album-plus' ),
											);
							$vals = array(	'',
											'local',
											'cloudinary',
											'cloudinarymaintenance',
											);
						}
						else {
							$opts = array(	__('--- none ---', 'wp-photo-album-plus' ),
											__('Local', 'wp-photo-album-plus' ),
											);
							$vals = array(	'',
											'local',
											);
						}
						$clas = 'iscloudinary';
						$show = wppa_opt( 'cdn_service' ) == 'cloudinary';
						$onch = "wppaSlaveSelected('wppa_cdn_service-cloudinary','".$clas."');";
						$html = wppa_select($slug, $opts, $vals, $onch);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Cloud name', 'wp-photo-album-plus' );
						$desc = '';
						$help = '';
						$slug = 'wppa_cdn_cloud_name';
						$html = wppa_input($slug, '500px');
						wppa_setting_new($slug, '2', $name, $desc, $html, $help, $show, $clas );

						$name = __('API key', 'wp-photo-album-plus' );
						$desc = '';
						$help = '';
						$slug = 'wppa_cdn_api_key';
						$html = wppa_input($slug, '500px');
						wppa_setting_new($slug, '3', $name, $desc, $html, $help, $show, $clas);

						$name = __('API secret', 'wp-photo-album-plus' );
						$desc = '';
						$help = '';
						$slug = 'wppa_cdn_api_secret';
						$html = wppa_input($slug, '500px');
						wppa_setting_new($slug, '4', $name, $desc, $html, $help, $show, $clas);

						$name = __('Delete all', 'wp-photo-album-plus' );
						$desc = '<span style="color:red">'.__('Deletes them all !!!', 'wp-photo-album-plus' ).'</span>';
						$help = '';
						$slug = 'wppa_delete_all_from_cloudinary';
						$html = wppa_doit_button_new($slug);
						wppa_setting_new(false, '5', $name, $desc, $html, $help, $show, $clas);

						$name = __('Delete derived images', 'wp-photo-album-plus' );
						$desc = '<span style="color:red">'.__('Deletes all derived images !!!', 'wp-photo-album-plus' ).'</span>';
						$help = '';
						$slug = 'wppa_delete_derived_from_cloudinary';
						$html = wppa_doit_button_new($slug);
						wppa_setting_new(false, '6', $name, $desc, $html, $help, $show, $clas);

						$name = __('Max lifetime', 'wp-photo-album-plus' );
						$desc = __('Old images from local server, new images from Cloudinary.', 'wp-photo-album-plus' );
						$help = __('If NOT set to Forever (0): You need to Synchronize Cloudinary on a regular basis.', 'wp-photo-album-plus' );
						$slug = 'wppa_max_cloud_life';
						$opts = array( 	__('Forever', 'wp-photo-album-plus' ),
										sprintf( _n('%d day', '%d days', '1', 'wp-photo-album-plus' ), '1'),
										sprintf( _n('%d week', '%d weeks', '1', 'wp-photo-album-plus' ), '1'),
										sprintf( _n('%d month', '%d months', '1', 'wp-photo-album-plus' ), '1'),
										sprintf( _n('%d month', '%d months', '2', 'wp-photo-album-plus' ), '2'),
										sprintf( _n('%d month', '%d months', '3', 'wp-photo-album-plus' ), '3'),
										sprintf( _n('%d month', '%d months', '6', 'wp-photo-album-plus' ), '6'),
										sprintf( _n('%d month', '%d months', '9', 'wp-photo-album-plus' ), '9'),
										sprintf( _n('%d year', '%d years', '1', 'wp-photo-album-plus' ), '1'),
										sprintf( _n('%d month', '%d months', '18', 'wp-photo-album-plus' ), '18'),
										sprintf( _n('%d year', '%d years', '2', 'wp-photo-album-plus' ), '2'),
										);
						$vals = array(	0,
										24*60*60,
										7*24*60*60,
										31*24*60*60,
										61*24*60*60,
										92*24*60*60,
										183*24*60*60,
										274*24*60*60,
										365*24*60*60,
										548*24*60*60,
										730*24*60*60,
										);

						$html = wppa_select($slug, $opts, $vals) . wppa_see_also( 'maintenance', '2', '21' );
						wppa_setting_new($slug, '7', $name, $desc, $html, $help, $show, $clas);

						$name = __('Cloudinary usage', 'wp-photo-album-plus' );
						if ( function_exists( 'wppa_get_cloudinary_usage' ) && wppa_opt( 'cdn_cloud_name' ) ) {
							$data = wppa_get_cloudinary_usage();
							if ( is_array( $data ) ) {
								$desc .= '<table id="wppa-cloudinary-table" ><tbody>';
								foreach ( array_keys( $data ) as $i ) {
									$item = $data[$i];
									if ( is_array( $item ) ) {
										$desc .= 	'<tr>' .
														'<td>' . $i . '</td>';
														foreach ( array_keys( $item ) as $j ) {
															if ( $j == 'used_percent' ) {
																$color = 'green';
																if ( $item[$j] > 80.0 ) $color = 'orange';
																if ( $item[$j] > 95.0 ) $color = 'red';
										$desc .= 				'<td>' . $j . ': <span style="color:' . $color . '">' . $item[$j] . '</span></td>';
															}
															else {
										$desc .= 				'<td>' . $j . ': ' . $item[$j] . '</td>';
															}
														}
										$desc .= 	'</tr>';
									}
									else {
										$desc .= 	'<tr>' .
														'<td>' . $i . '</td>' .
														'<td>' . $item . '</td>' .
														'<td></td>' .
														'<td></td>' .
													'</tr>';
									}
								}
								$desc .= '</tbody></table>';
							}
							else {
								$desc = __('Cloudinary usage data not available', 'wp-photo-album-plus' );
							}
						}
						else {
							$desc = __('Cloudinary usage data not available', 'wp-photo-album-plus' );
						}
						$help = '';
						$html = array();
						wppa_setting_new($slug, '8', $name, $desc, $html, $help, $show, $clas);

						$name = __('Fotomoto', 'wp-photo-album-plus' );
						$desc = __('Yes, we use Fotomoto on this site. Read the help text!', 'wp-photo-album-plus' );
						$help = __('In order to function properly:', 'wp-photo-album-plus' );
						$help .= '<br>'.__('1. Get yourself a Fotomoto account.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('2. Install the Fotomoto plugin, enter the "Fotomoto Site Key:" and check the "Use API Mode:" checkbox.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Note: Do NOT Disable the Custom box in the Slideshow component specification.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Do NOT remove the text w#fotomoto from the Custombox content.', 'wp-photo-album-plus' );
						$slug = 'wppa_fotomoto_on';
						$onch = "wppaSlaveChecked(this,'fmon');";
						$html = wppa_checkbox($slug, $onch) . wppa_see_also( 'slide', '1', '25.26' );
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						$name = __('Hide when running', 'wp-photo-album-plus' );
						$desc = __('Hide toolbar on running slideshows', 'wp-photo-album-plus' );
						$help = __('The Fotomoto toolbar will re-appear when the slideshow stops.', 'wp-photo-album-plus' );
						$slug = 'wppa_fotomoto_hide_when_running';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '11', $name, $desc, $html, $help, wppa_switch( 'fotomoto_on' ), 'fmon');

						$name = __('Fotomoto minwidth', 'wp-photo-album-plus' );
						$desc = __('Minimum width to display Fotomoto toolbar.', 'wp-photo-album-plus' );
						$help = __('The display of the Fotomoto Toolbar will be suppressed on smaller slideshows.', 'wp-photo-album-plus' );
						$slug = 'wppa_fotomoto_min_width';
						$html = wppa_input($slug, '40px', '', __('pixels', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '12', $name, $desc, $html, $help, wppa_switch( 'fotomoto_on' ), 'fmon');

						$name = __('Image Magick', 'wp-photo-album-plus' );
						$desc = __('Absolute path to the ImageMagick commands', 'wp-photo-album-plus' );// . ' <span style="color:red">' . __('experimental', 'wp-photo-album-plus' ) . '</span>';
						$help = __('If you want to use ImageMagick, enter the absolute path to the ImageMagick commands', 'wp-photo-album-plus' );
						$slug = 'wppa_image_magick';
						$onch = "wppaSlaveNotNone(this,'image_magick_ratio');";
						$html = wppa_input($slug, '300px', '', '', $onch);
						wppa_setting_new($slug, '13', $name, $desc, $html, $help);

						$name = __('Image Magick cropping', 'wp-photo-album-plus' );
						$desc = __('Select default aspect for cropping', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_image_magick_ratio';
						$opts = array( 	__( 'free', 'wp-photo-album-plus' ),
										__( 'original', 'wp-photo-album-plus' ),
										__( 'square', 'wp-photo-album-plus' ),
										'4:5 ' . __( 'landscape', 'wp-photo-album-plus' ),
										'3:4 ' . __( 'landscape', 'wp-photo-album-plus' ),
										'2:3 ' . __( 'landscape', 'wp-photo-album-plus' ),
										'5:8 ' . __( 'landscape', 'wp-photo-album-plus' ),
										'9:16 ' . __( 'landscape', 'wp-photo-album-plus' ),
										'1:2 ' . __( 'landscape', 'wp-photo-album-plus' ),
										'4:5 ' . __( 'portrait', 'wp-photo-album-plus' ),
										'3:4 ' . __( 'portrait', 'wp-photo-album-plus' ),
										'2:3 ' . __( 'portrait', 'wp-photo-album-plus' ),
										'5:8 ' . __( 'portrait', 'wp-photo-album-plus' ),
										'9:16 ' . __( 'portrait', 'wp-photo-album-plus' ),
										'1:2 ' . __( 'portrait', 'wp-photo-album-plus' )
									);
						$vals = array('NaN', 'ratio', '1', '1.25', '1.33333', '1.5', '1.6', '1.77777', '2', '0.8', '0.75', '0.66667', '0.625', '0.5625', '0.5');

						$html = wppa_select($slug, $opts, $vals);
						wppa_setting_new($slug, '14', $name, $desc, $html, $help, wppa_opt( 'image_magick' ) != 'none', 'image_magick_ratio');

						wppa_setting_box_footer_new();
					}
					// Other plugins related settings
					{
						$desc = $wppa_subtab_names[$tab]['4'];
						wppa_setting_tab_description($desc);
						wppa_setting_box_header_new($tab);

						$name = __('Foreign shortcodes general', 'wp-photo-album-plus' );
						$desc = __('Enable foreign shortcodes in album names, albums desc and photo names', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_allow_foreign_shortcodes_general';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '0', $name, $desc, $html, $help);

						$name = __('Foreign shortcodes fullsize', 'wp-photo-album-plus' );
						$desc = __('Enable the use of non-wppa+ shortcodes in fullsize photo descriptions.', 'wp-photo-album-plus' );
						$help = __('When checked, you can use shortcodes from other plugins in the description of photos.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('The shortcodes will be expanded in the descriptions of fullsize images.', 'wp-photo-album-plus' );
						$slug = 'wppa_allow_foreign_shortcodes';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '1', $name, $desc, $html, $help);

						$name = __('Foreign shortcodes thumbnails', 'wp-photo-album-plus' );
						$desc = __('Enable the use of non-wppa+ shortcodes in thumbnail photo descriptions.', 'wp-photo-album-plus' );
						$help = __('When checked, you can use shortcodes from other plugins in the description of photos.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('The shortcodes will be expanded in the descriptions of thumbnail images.', 'wp-photo-album-plus' );
						$slug = 'wppa_allow_foreign_shortcodes_thumbs';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '2', $name, $desc, $html, $help);

						$name = __('myCRED / Cube Points: Comment', 'wp-photo-album-plus' );
						$desc = __('Number of points for giving a comment', 'wp-photo-album-plus' );
						$help = __('This setting requires the plugin myCRED or Cube Points', 'wp-photo-album-plus' );
						$slug = 'wppa_cp_points_comment';
						$html = wppa_input($slug, '50px', '', __('points per comment', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '4', $name, $desc, $html, $help);

						$name = __('myCRED / Cube Points: Appr Comment', 'wp-photo-album-plus' );
						$desc = __('Number of points for receiving an approved comment', 'wp-photo-album-plus' );
						$help = __('This setting requires the plugin myCRED or Cube Points', 'wp-photo-album-plus' );
						$slug = 'wppa_cp_points_comment_appr';
						$html = wppa_input($slug, '50px', '', __('points per comment', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '4.1', $name, $desc, $html, $help);

						$name = __('myCRED / Cube Points: Rating', 'wp-photo-album-plus' );
						$desc = __('Number of points for a rating vote', 'wp-photo-album-plus' );
						$help = __('This setting requires the plugin myCRED or Cube Points', 'wp-photo-album-plus' );
						$slug = 'wppa_cp_points_rating';
						$html = wppa_input($slug, '50px', '', __('points per vote', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '5', $name, $desc, $html, $help);

						$name = __('myCRED / Cube Points: Upload', 'wp-photo-album-plus' );
						$desc = __('Number of points for a successfull frontend upload', 'wp-photo-album-plus' );
						$help = __('This setting requires the plugin myCRED or Cube Points', 'wp-photo-album-plus' );
						$slug = 'wppa_cp_points_upload';
						$html = wppa_input($slug, '50px', '', __('points per upload', 'wp-photo-album-plus' ));
						wppa_setting_new($slug, '6', $name, $desc, $html, $help);

						$name = __('Use SCABN', 'wp-photo-album-plus' );
						$desc = __('Use the wppa interface to Simple Cart & Buy Now plugin.', 'wp-photo-album-plus' );
						$help = __('If checked, the shortcode to use for the "add to cart" button in photo descriptions is [cart ...]', 'wp-photo-album-plus' );
						$help .= '<br>'.__('as opposed to [scabn ...] for the original scabn "add to cart" button.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('The shortcode for the check-out page is still [scabn]', 'wp-photo-album-plus' );
						$help .= '<br>'.__('The arguments are the same, the defaults are: name = photoname, price = 0.01.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Supplying the price should be sufficient; supply a name only when it differs from the photo name.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('This shortcode handler will also work with Ajax enabled.', 'wp-photo-album-plus' );
						$help .= '<br>'.__('Using this interface makes sure that the item urls and callback action urls are correct.', 'wp-photo-album-plus' );
						$slug = 'wppa_use_scabn';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '7', $name, $desc, $html, $help);

						$name = __('Use CM Tooltip Glossary', 'wp-photo-album-plus' );
						$desc = __('Use plugin CM Tooltip Glossary on photo and album descriptions.', 'wp-photo-album-plus' );
						$help = __('You MUST set Defer javascript, also if you do not want this plugin to act on album and photo descriptions!', 'wp-photo-album-plus' );
						$slug = 'wppa_use_CMTooltipGlossary';
						$html = wppa_checkbox($slug) . wppa_see_also( 'system', '1', '11' );
						wppa_setting_new($slug, '8', $name, $desc, $html, $help);

						$name = __('Shortcode [photo nnn] on bbPress', 'wp-photo-album-plus' );
						$desc = __('Enable the [photo] shortcode generator on bbPress frontend editors', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_photo_on_bbpress';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '9', $name, $desc, $html, $help);

						$name = __('Domain links BuddyPress', 'wp-photo-album-plus' );
						$desc = __('Convert usernames in photo names to domain links.', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_domain_link_buddypress';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '10', $name, $desc, $html, $help);

						$name = __('This site uses plugin Page Load Ajax', 'wp-photo-album-plus' );
						$desc = __('Tick this box if you use pla', 'wp-photo-album-plus' );
						$help = '';
						$slug = 'wppa_uses_pla';
						$html = wppa_checkbox($slug);
						wppa_setting_new($slug, '11', $name, $desc, $html, $help);

						wppa_setting_box_footer_new();
					}
				}
				break;

				default:

				break;
			}

		// Close the content area
		wppa_echo( '</div>' );

		// The popup window
		wppa_echo( '
		<div
			id="wppa-modal-container"
			style="width:100%;padding:0">
		</div>' );

	// close wrapper
	wppa_echo( '</div>' );

	// Report resource used and other environmental data
	wppa_echo( '
	<div style="clear:both;margin-top:12px">
		<br>' .
		sprintf( __( 'Memory used on this page: %6.2f Mb', 'wp-photo-album-plus' ), memory_get_peak_usage( true ) / ( 1024 * 1024 ) ) . '<br>' .
		sprintf( __( 'There are %d settings and %d runtime parameters', 'wp-photo-album-plus' ), count( $wppa_opt ), count( $wppa ) ) . '<br>' .
		__( 'Database revision:', 'wp-photo-album-plus' ) . ' ' . wppa_get_option( 'wppa_revision', '100' ) . '<br>' .
		__( 'WP Charset:', 'wp-photo-album-plus' ) . ' ' . get_bloginfo( 'charset' ) . '<br>' .
		__( 'Current PHP version:', 'wp-photo-album-plus' ) . ' ' . phpversion() . '<br>' .
		__( 'WPPA+ API Version:', 'wp-photo-album-plus' ) . ' ' . $wppa_version . '<br>' .
		__( 'Filesystem method:', 'wp-photo-album-plus' ) . ' ' . get_filesystem_method() .
	'<br>' );

	wppa_initialize_runtime( true );
}

