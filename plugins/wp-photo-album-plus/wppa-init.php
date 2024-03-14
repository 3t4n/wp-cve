<?php
/* wppa-init.php
* Package: wp-photo-album-plus
*
* This file loads required php files and contains all functions used in init actions.
*
* Version: 8.6.01.001
*/

/* LOAD SIDEBAR WIDGETS */
require_once 'wppa-potd-widget.php';
require_once 'wppa-search-widget.php';
require_once 'wppa-topten-widget.php';
require_once 'wppa-featen-widget.php';
require_once 'wppa-slideshow-widget.php';
require_once 'wppa-comment-widget.php';
require_once 'wppa-thumbnail-widget.php';
require_once 'wppa-lasten-widget.php';
require_once 'wppa-album-widget.php';
require_once 'wppa-qr-widget.php';
require_once 'wppa-tagcloud-widget.php';
require_once 'wppa-multitag-widget.php';
require_once 'wppa-upload-widget.php';
require_once 'wppa-super-view-widget.php';
require_once 'wppa-upldr-widget.php';
require_once 'wppa-bestof-widget.php';
require_once 'wppa-album-navigator-widget.php';
require_once 'wppa-stereo-widget.php';
require_once 'wppa-admins-choice-widget.php';
require_once 'wppa-stats-widget.php';
require_once 'wppa-notify-widget.php';
require_once 'wppa-gp-widget.php';
require_once 'wppa-widget-functions.php';

/* COMMON FUNCTIONS */
require_once 'wppa-common-functions.php';
require_once 'wppa-utils.php';
require_once 'wppa-exif-iptc-common.php';
require_once 'wppa-index.php';
require_once 'wppa-statistics.php';
require_once 'wppa-wpdb-insert.php';
require_once 'wppa-wpdb-update.php';
require_once 'wppa-users.php';
require_once 'wppa-watermark.php';
require_once 'wppa-setup.php';
require_once 'wppa-session.php';
require_once 'wppa-source.php';
require_once 'wppa-items.php';
require_once 'wppa-date-time.php';
require_once 'wppa-htaccess.php';
require_once 'wppa-video.php';
require_once 'wppa-audio.php';
require_once 'wppa-mobile.php';
require_once 'wppa-stereo.php';
require_once 'wppa-encrypt.php';
require_once 'wppa-photo-files.php';
require_once 'wppa-cron.php';
require_once 'wppa-maintenance.php';
require_once 'wppa-tinymce-common.php';
require_once 'wppa-local-cdn.php';
require_once 'wppa-wrappers.php';
require_once 'wppa-mailing.php';
require_once 'wppa-upload-common.php';
require_once 'wppa-links.php';
require_once 'wppa-styles.php';
require_once 'wppa-scripts.php';
require_once 'wppa-functions.php';
require_once 'wppa-thumbnails.php';
require_once 'wppa-boxes-html.php';
require_once 'wppa-slideshow.php';
require_once 'wppa-picture.php';
require_once 'wppa-input.php';

// Here because it is required for block widgets
require_once 'wppa-setting-see-also.php';
require_once 'wppa-cache.php';



/* Load cloudinary if configured and php version >= 5.3 */
if ( PHP_VERSION_ID >= 50300 ) require_once 'wppa-cloudinary.php';

/* DO THE ADMIN/NON ADMIN SPECIFIC STUFF */
if ( is_admin() ) {
	require_once 'wppa-admin.php';

	/* Native blocks */
	$wppa_blocks = glob( dirname( __FILE__ ) . '/blocks/*', GLOB_ONLYDIR );
	foreach( $wppa_blocks as $block ) {
		$index = $block . '/index.php';
		if ( is_file( $index ) ) {
			if ( basename( $block ) != 'general' ) { // general is not ready yet
				if ( basename( $block ) != 'photo' || get_option( 'wppa_photo_shortcode_enabled', 'yes' ) == 'yes' ) {
					require_once $index;
				}
			}
		}
	}
}
require_once 'wppa-non-admin.php';

/* ADD AJAX */
require_once 'wppa-ajax.php';

// Load textdomain conditionally
function wppa_load_plugin_textdomain() {
global $wppa_lang;
global $wppa_locale;
global $wp_version;
global $locale;

	// 'Our' usefull language info
	if ( wppa_get( 'wppalocale' ) ) {
		$wppa_locale = wppa_get( 'wppalocale' );
		$wppa_lang = substr( $wppa_locale, 0, 2 );
		$locale = $wppa_locale;
	}
	else {
		$wppa_locale = get_locale() ? get_locale() : 'en_US';
		$wppa_lang = substr( $wppa_locale, 0, 2 );
	}

	$bret = load_plugin_textdomain( 'wp-photo-album-plus', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

// Compute all non-trivial constants and create required directories
function wppa_init_path_and_url_constants() {
global $blog_id;

	// Upload ( .../wp-content/uploads ) is always relative to ABSPATH,
	// see http://codex.wordpress.org/Editing_wp-config.php#Moving_wp-content_folder
	//
	// Assumption: site_url() corresponds with ABSPATH
	// Our version ( WPPA_UPLOAD ) of the relative part of the path/url to the uploads dir
	// is calculated form wp_upload_dir() by substracting ABSPATH from the uploads basedir.
	$wp_uploaddir = wp_upload_dir();

	// Unfortunately $wp_uploaddir['basedir'] does very often not contain the data promised
	// by the docuentation, so it is unreliable.
	$rel_uploads_path = defined( 'WPPA_REL_UPLOADS_PATH') ?
		wppa_trims( WPPA_REL_UPLOADS_PATH ) :
		'wp-content/uploads';

	// The depot dir is also relative to ABSPATH but on the same level as uploads,
	// but without '/wppa-depot'.
	// If you want to change the name of wp-content, you have also to define WPPA_REL_DEPOT_PATH
	// as being the relative path to the parent of wppa-depot.
	$rel_depot_path = defined( 'WPPA_REL_DEPOT_PATH' ) ?
		wppa_trims( WPPA_REL_DEPOT_PATH ) :
		'wp-content';

	// For multisite the uploads are in /wp-content/blogs.dir/<blogid>/,
	// so we hope still below ABSPATH
	$wp_content_multi = wppa_trims( str_replace( WPPA_ABSPATH, '', WPPA_CONTENT_PATH ) );

	// To test the multisite paths and urls, set $debug_multi = true
	$debug_multi = false;

	// Define paths and urls
	if ( $debug_multi || ( is_multisite() && ! WPPA_MULTISITE_GLOBAL ) ) {
		if ( WPPA_MULTISITE_BLOGSDIR ) {	// Old multisite individual
			define( 'WPPA_UPLOAD', wppa_trims( $wp_content_multi . '/blogs.dir/' . $blog_id ) );
			define( 'WPPA_UPLOAD_PATH', WPPA_ABSPATH.WPPA_UPLOAD . '/wppa' );
			define( 'WPPA_UPLOAD_URL', site_url() . '/' . WPPA_UPLOAD . '/wppa' );
			define( 'WPPA_DEPOT',
				wppa_trims( $wp_content_multi . '/blogs.dir/' . $blog_id . '/wppa-depot' ) );
			define( 'WPPA_DEPOT_PATH', WPPA_ABSPATH.WPPA_DEPOT );
			define( 'WPPA_DEPOT_URL', site_url() . '/' . WPPA_DEPOT );
		}
		elseif ( WPPA_MULTISITE_INDIVIDUAL ) {	// New multisite individual
			define( 'WPPA_UPLOAD', $rel_uploads_path . '/sites/'.$blog_id);
			define( 'WPPA_UPLOAD_PATH', WPPA_ABSPATH.WPPA_UPLOAD.'/wppa');
			define( 'WPPA_UPLOAD_URL', get_bloginfo('wpurl').'/'.WPPA_UPLOAD.'/wppa');
			define( 'WPPA_DEPOT', $rel_uploads_path . '/sites/'.$blog_id.'/wppa-depot' );
			define( 'WPPA_DEPOT_PATH', WPPA_ABSPATH.WPPA_DEPOT );
			define( 'WPPA_DEPOT_URL', get_bloginfo('wpurl').'/'.WPPA_DEPOT );
		}
		else { 	// Not working default multisite
			$user = is_user_logged_in() ? '/' . wppa_get_user() : '';
			define( 'WPPA_UPLOAD', $rel_uploads_path );
			define( 'WPPA_UPLOAD_PATH', WPPA_ABSPATH . WPPA_UPLOAD . $user . '/wppa' );
			define( 'WPPA_UPLOAD_URL', site_url() . '/' . WPPA_UPLOAD . $user . '/wppa' );
			define( 'WPPA_DEPOT', wppa_trims( $rel_depot_path . '/wppa-depot' . $user ) );
			define( 'WPPA_DEPOT_PATH', WPPA_ABSPATH . WPPA_DEPOT );
			define( 'WPPA_DEPOT_URL', site_url() . '/' . WPPA_DEPOT );
		}
	}
	// This is for sites where wp_upload_dir() returns valid output and having non standard file locations
	elseif ( wppa_get_option( 'wppa_use_wp_upload_dir_locations', 'no' ) == 'yes' ) {
		$user 		= is_user_logged_in() ? '/' . wppa_get_user() : '';
		$dir 		= wp_upload_dir();
		$basedir 	= $dir['basedir'];
		$baseurl 	= $dir['baseurl'];
		define( 'WPPA_UPLOAD_PATH', $basedir . '/wppa' );
		define( 'WPPA_UPLOAD_URL', $baseurl . '/wppa' );
		define( 'WPPA_UPLOAD', str_replace( ABSPATH, '', $basedir ) );
		define( 'WPPA_DEPOT_PATH', dirname( $basedir ) . '/wppa-depot' . $user );
		define( 'WPPA_DEPOT_URL', dirname( $baseurl ) . '/wppa-depot' . $user );
		define( 'WPPA_DEPOT', str_replace( ABSPATH, '', WPPA_DEPOT_PATH ) );
	}
	else {	// Single site or multisite global
		define( 'WPPA_UPLOAD', $rel_uploads_path );
		if ( ! defined( 'WPPA_UPLOAD_PATH' ) ) {
			define( 'WPPA_UPLOAD_PATH', WPPA_ABSPATH . WPPA_UPLOAD . '/wppa' );
		}
		if ( ! defined( 'WPPA_UPLOAD_URL' ) ) {
			define( 'WPPA_UPLOAD_URL', site_url() . '/' . WPPA_UPLOAD . '/wppa' );
		}
		$user = is_user_logged_in() ? '/' . wppa_get_user() : '';
		define( 'WPPA_DEPOT', wppa_trims( $rel_depot_path . '/wppa-depot' . $user ) );
		if ( ! defined( '_WPPA_DEPOT_PATH' ) ) {
			define( 'WPPA_DEPOT_PATH', WPPA_ABSPATH . WPPA_DEPOT );
		}
		else {
			define( 'WPPA_DEPOT_PATH', _WPPA_DEPOT_PATH . WPPA_DEPOT );
		}
		if ( ! defined( '_WPPA_DEPOT_URL' ) ) {
			define( 'WPPA_DEPOT_URL', site_url() . '/' . WPPA_DEPOT );
		}
		else {
			define( 'WPPA_DEPOT_URL', _WPPA_DEPOT_URL . WPPA_DEPOT );
		}
	}

	global $wppa_log_file;
	$wppa_log_file = WPPA_UPLOAD_PATH . '/wppa-log.txt';

	define ( 'WPPA_LOCKDIR', WPPA_UPLOAD_PATH . '/locks' );
}

function wppa_verify_multisite_config() {

	if ( ! is_admin() ) return;
	if ( ! is_multisite() ) return;
	if ( wppa( 'ajax' ) ) return;

	if ( WPPA_MULTISITE_GLOBAL ) return;
	if ( WPPA_MULTISITE_BLOGSDIR ) return;
	if ( WPPA_MULTISITE_INDIVIDUAL ) return;

	$errtxt = __('</strong><h3>WP Photo ALbum Plus Error message</h3>This is a multi site installation. One of the following 3 lines must be entered in wp-config.php:', 'wp-photo-album-plus' );
	$errtxt .= __('<br><br><b>define( \'WPPA_MULTISITE_INDIVIDUAL\', true );</b> <small>// Multisite WP 3.5 or later with every site its own albums and photos</small>', 'wp-photo-album-plus' );
	$errtxt .= __('<br><b>define( \'WPPA_MULTISITE_BLOGSDIR\', true );</b> <small>// Multisite prior to WP 3.5 with every site its own albums and photos</small>', 'wp-photo-album-plus' );
	$errtxt .= __('<br><b>define( \'WPPA_MULTISITE_GLOBAL\', true );</b> <small>// Multisite with one common set of albums and photos</small>', 'wp-photo-album-plus' );
	$errtxt .= __('<br><br>Make sure to add this in wp-config.php prior to the line "require_once ABSPATH . \'wp-settings.php\';"', 'wp-photo-album-plus' );
	$errtxt .= __('<br><br>For more information see: <a href="https://wordpress.org/plugins/wp-photo-album-plus/faq/">the faq</a>', 'wp-photo-album-plus' );
	$errtxt .= __('<br><br><em>If you upload photos, they will be placed in the wrong location and will not be visible for visitors!</em><strong>', 'wp-photo-album-plus' );

	wppa_error_message( $errtxt );
}

function wppa_admin_bar_init() {

	if ( ( is_admin() && wppa_switch( 'adminbarmenu_admin' ) ) ||
		( ! is_admin() && wppa_switch( 'adminbarmenu_frontend' ) ) ) {

		if ( current_user_can('wppa_admin') ||
			 current_user_can('wppa_upload') ||
			 current_user_can('wppa_import') ||
			 current_user_can('wppa_moderate') ||
			 current_user_can('wppa_export') ||
			 current_user_can('wppa_settings') ||
			 current_user_can('wppa_comments') ||
			 current_user_can('wppa_help') ) {
				require_once 'wppa-adminbar.php';
		}
	}
}

function wppa_maintenance_messages() {

	if ( ! current_user_can( 'wppa_settings' ) ) {
		return;
	}

	// Cron jobs postponed?
	if ( wppa_get_option( 'wppa_maint_ignore_cron' ) == 'yes' ) {
		wppa_warning_message( __( 'Please do not forget to re-enable cron jobs for wppa when you are ready doing your bulk actions', 'wp-photo-album-plus' ) . wppa_see_also( 'maintenance', '1', '0', '', '', true ) );
	}

	// Check for pending actions
	if ( wppa_get_option( 'wppa_remove_empty_albums_status'	) 		&& wppa_get_option( 'wppa_remove_empty_albums_user' ) == wppa_get_user() ) {
		wppa_warning_message( __( 'Remove empty albums needs completion.', 'wp-photo-album-plus' ) . wppa_see_also( 'maintenance', '2', '9', '', '', true ) );
	}
	if ( wppa_get_option( 'wppa_apply_new_photodesc_all_status' ) 	&& wppa_get_option( 'wppa_apply_new_photodesc_all_user' ) == wppa_get_user() ) {
		wppa_warning_message( __( 'Applying new photo description needs completion.', 'wp-photo-album-plus' ) . wppa_see_also( 'maintenance', '2', '6', '', '', true ) );
	}
	if ( wppa_get_option( 'wppa_append_to_photodesc_status' ) 		&& wppa_get_option( 'wppa_append_to_photodesc_user' ) == wppa_get_user() ) {
		wppa_warning_message( __( 'Appending to photo description needs completion.' , 'wp-photo-album-plus' ) . wppa_see_also( 'maintenance', '2', '7', '', '', true ) );
	}
	if ( wppa_get_option( 'wppa_remove_from_photodesc_status' ) 		&& wppa_get_option( 'wppa_remove_from_photodesc_user' ) == wppa_get_user() ) {
		wppa_warning_message( __( 'Removing from photo description needs completion.' , 'wp-photo-album-plus' ) . wppa_see_also( 'maintenance', '2', '8', '', '', true ) );
	}
	if ( wppa_get_option( 'wppa_remove_file_extensions_status' ) 	&& wppa_get_option( 'wppa_remove_file_extensions_user' ) == wppa_get_user() ) {
		wppa_warning_message( __( 'Removing file extensions needs completion.' , 'wp-photo-album-plus' ) . wppa_see_also( 'maintenance', '2', '10', '', '', true ) );
	}
	if ( wppa_get_option( 'wppa_regen_thumbs_status' ) 				&& wppa_get_option( 'wppa_regen_thumbs_user' ) == wppa_get_user() ) {
		wppa_warning_message( __( 'Regenerating the Thumbnails needs completion.' , 'wp-photo-album-plus' ) . wppa_see_also( 'maintenance', '1', '4', '', '', true ) );
	}
	if ( wppa_get_option( 'wppa_rerate_status' ) 					&& wppa_get_option( 'wppa_rerate_user' ) == wppa_get_user() ) {
		wppa_warning_message( __( 'Rerating needs completion.' , 'wp-photo-album-plus' ) . wppa_see_also( 'maintenance', '1', '5', '', '', true ) );
	}
}

function wppa_check_tag_system() {
global $wpdb;

	if ( current_user_can( 'wppa_settings' ) ) {
		if ( wppa_get_option( 'wppa_tags_ok' ) != '1' ) {
			$tag = $wpdb->get_var( "SELECT tags FROM $wpdb->wppa_photos WHERE tags <> '' ORDER BY id DESC LIMIT 1" );
			if ( $tag ) {
				if ( substr( $tag, 0, 1 ) != ',' ) {
					add_action('admin_notices', 'wppa_tag_message');
					wppa_update_option( 'wppa_sanitize_tags_status', 'required' );
				}
				else {
					wppa_update_option( 'wppa_tags_ok', '1' );
				}
			}
		}
	}
}

function wppa_tag_message() {
	wppa_error_message( sprintf( __('The tags system needs to be converted. Please run <i>Photo Albums -> Settings -> %s</i>' , 'wp-photo-album-plus' ), wppa_setting_path( 'a', 'maintenance', '2', '22' ) ) );
}

function wppa_check_cat_system() {
global $wpdb;

	if ( current_user_can( 'wppa_settings' ) ) {
		if ( wppa_get_option( 'wppa_cats_ok' ) != '1' ) {
			$tag = $wpdb->get_var( "SELECT cats FROM $wpdb->wppa_albums WHERE cats <> '' ORDER BY id DESC LIMIT 1" );
			if ( $tag ) {
				if ( substr( $tag, 0, 1 ) != ',' ) {
					add_action('admin_notices', 'wppa_cat_message');
					wppa_update_option( 'wppa_sanitize_cats_status', 'required' );
				}
				else {
					wppa_update_option( 'wppa_cats_ok', '1' );
				}
			}
		}
	}
}

function wppa_cat_message() {
	wppa_error_message( sprintf( __('The cats system needs to be converted. Please run <i>Photo Albums -> Settings -> %s</i>' , 'wp-photo-album-plus' ), wppa_setting_path( 'a', 'maintenance', '2', '23' ) ) );
}

// Print admin messages on config conflicts
function wppa_check_config_conflicts() {

	$any 	= false;
	$text 	= __( 'WPPA detected the following configuration conflict(s)', 'wp-photo-album-plus' ) . '<br>';

	// Output
	if ( $any ) {
		wppa_error_message( $text );
	}
}

/* This function will add "donate" link to main plugins page */
function wppa_donate_link($links, $file) {
	if ( $file == plugin_basename(__FILE__) ) {
		$donate_link_usd = '<a target="_blank" title="Paypal" href="https://' .
			'www.paypal.com/cgi-bin/webscr?cmd=_donations&business=OpaJaap@OpaJaap.nl&item_name=' .
			'WP-Photo-Album-Plus&item_number=Support-Open-Source&currency_code=USD&lc=US">' .
			'Donate USD</a>';
		$donate_link_eur = '<a target="_blank" title="Paypal" href="https://' .
			'www.paypal.com/cgi-bin/webscr?cmd=_donations&business=OpaJaap@OpaJaap.nl&item_name=' .
			'WP-Photo-Album-Plus&item_number=Support-Open-Source&currency_code=EUR&lc=US">' .
			'Donate EUR</a>';
		$docs_link = '<a target="_blank" href="http://wppa.opajaap.nl/" title=' .
			'"Docs & Demos" >Documentation and examples</a>';

		$links[] = $donate_link_usd . ' | ' . $donate_link_eur . ' | ' . $docs_link;
	}
	return $links;
}

function wppa_check_scabn_compatibility() {
	if ( wppa_switch( 'use_scabn' ) ) {
		$msg = '';
		if ( ! function_exists( 'scabn_check_wppa' ) ) {
			$msg = __( 'You must install plugin <a href="https://wppa.nl/wp-content/uploads/simple-cart-buy-now-for-wppa.zip" ><b>simple-cart-buy-now-for-wppa</b></a> for the shopping cart functionality in wppa', 'wp-photo-album-plus' );
			if ( class_exists( 'wfCart' ) ) {
				$msg .= '<br>' . __( 'Plugin <b>simple-cart-buy-now</b> is no longer compatible with wppa', 'wp-photo-album-plus' );
				$msg .= '<br>' . __( 'Note: you can not have both shopping plugins active at the same time.', 'wp-photo-album-plus' );
				$msg .= '<br>' . __( 'The new version will work outside wppa as long as wppa is activated.', 'wp-photo-album-plus' );
			}
		}
		if ( $msg ) {
			wppa_warning_message( $msg );
		}
	}
}
add_action( 'admin_notices', 'wppa_check_scabn_compatibility' );

// Do translation for wpGlobus and native wppa translator for qTranslate syntax
function wppa_translate( $text = '' ) {
global $wppa_lang;

	// Get default language
	if ( ! $wppa_lang ) {
		$wppa_lang = 'en';
	}
	$ln = $wppa_lang;

	// WP Globus
	if ( class_exists( 'WPGlobus_Core' ) && strpos( $text, '{:' ) !== false ) {
		$text = WPGlobus_Core::extract_text( $text, $wppa_lang );
	}

	// WPPA Native
	if ( wppa_get_option( 'wppa_translate', 'no' ) == 'yes' && strpos( $text, '[:' ) !== false ) {

		// Mark the one(s) we want to save
		$text = str_replace( "[:$ln]", "[:save]", $text );

		// Remove other languages
		$text = preg_replace( '/\[:..]((?!\[:).)*/', '', $text );

		// Remove helpers
		$text = str_replace( '[:]', '', $text );
		$text = str_replace( '[:save]', '', $text );

	}

	return $text;
}

// Prepare translations for using Galery rather than Album
function wppa_filter_translate() {
global $wppa_album_gallery_texts_albums;
global $wppa_album_gallery_texts_gallery;
global $_gallery;

	// Keep it album?
	if ( wppa_get_option( 'wppa_album_use_gallery', 'no' ) == 'no' ) {
		return;
	}

	// Been here before?
	if ( !empty( $wppa_album_gallery_texts_albums ) ) return;

	$wppa_album_gallery_texts_albums = array(
									__( 'of the album', 'wp-photo-album-plus' ),
									__( 'Renew album', 'wp-photo-album-plus' ),
									__( 'renew album', 'wp-photo-album-plus' ),
									__( 'A new album', 'wp-photo-album-plus' ),
									__( 'This album', 'wp-photo-album-plus' ),
									__( 'this album', 'wp-photo-album-plus' ),
									__( 'New albums', 'wp-photo-album-plus' ),
									__( 'new albums', 'wp-photo-album-plus' ),
									__( 'New album', 'wp-photo-album-plus' ),
									__( 'new album', 'wp-photo-album-plus' ),
									__( 'The albums', 'wp-photo-album-plus' ),
									__( 'the albums', 'wp-photo-album-plus' ),
									__( 'The album', 'wp-photo-album-plus' ),
									__( 'the album', 'wp-photo-album-plus' ),
									__( 'An album', 'wp-photo-album-plus' ),
									__( 'an album', 'wp-photo-album-plus' ),
									__( 'Albums', 'wp-photo-album-plus' ),
									__( 'Album', 'wp-photo-album-plus' ),
									__( 'albums', 'wp-photo-album-plus' ),
									__( 'album', 'wp-photo-album-plus' ),
									);

	$wppa_album_gallery_texts_gallery = array(
									__( 'of the gallery', 'wp-photo-album-plus' ),
									__( 'Renew gallery', 'wp-photo-album-plus' ),
									__( 'renew gallery', 'wp-photo-album-plus' ),
									__( 'A new gallery', 'wp-photo-album-plus' ),
									__( 'This gallery', 'wp-photo-album-plus' ),
									__( 'this gallery', 'wp-photo-album-plus' ),
									__( 'New galleries', 'wp-photo-album-plus' ),
									__( 'new galleries', 'wp-photo-album-plus' ),
									__( 'New gallery', 'wp-photo-album-plus' ),
									__( 'new gallery', 'wp-photo-album-plus' ),
									__( 'The galleries', 'wp-photo-album-plus' ),
									__( 'the galleries', 'wp-photo-album-plus' ),
									__( 'The gallery', 'wp-photo-album-plus' ),
									__( 'the gallery', 'wp-photo-album-plus' ),
									__( 'A gallery', 'wp-photo-album-plus' ),
									__( 'a gallery', 'wp-photo-album-plus' ),
									__( 'Galleries', 'wp-photo-album-plus' ),
									__( 'Gallery', 'wp-photo-album-plus' ),
									__( 'galleries', 'wp-photo-album-plus' ),
									__( 'gallery', 'wp-photo-album-plus' ),
									);

	$_gallery = __( 'gallery', 'wp-photo-album-plus' );
}
add_action( 'plugins_loaded', 'wppa_filter_translate', 1 );

// Activate album to gallery conversion
// This must be done after wppa_filter_translate() has completed to avoid endless recursion
function wppa_activate_albtogal_conversion() {

	// Keep it album?
	if ( wppa_get_option( 'wppa_album_use_gallery', 'no' ) == 'no' ) {
		return;
	}

	add_filter( 'gettext', 'wppa_album_to_gallery', 100 );
}
add_action( 'plugins_loaded', 'wppa_activate_albtogal_conversion', 20 );

// Do the actual conversion from album to gallery
function wppa_album_to_gallery( $text = '' ) {
global $wppa_skip_alb_to_gal;
global $wppa_album_gallery_texts_albums;
global $wppa_album_gallery_texts_gallery;
global $_gallery;

	if ( $wppa_skip_alb_to_gal ) {
		$wppa_skip_alb_to_gal = false;
		return $text;
	}

	$text = str_replace( $wppa_album_gallery_texts_albums,
						 $wppa_album_gallery_texts_gallery,
						 $text );
	$text = str_replace( '-' . $_gallery . '-', '-album-', $text ); // Repair refs to wp (i.e. 'wp-photo-album-plus')

	return $text;
}

// Prepare translations using qTranslate
function wppa_filter_qtranslate() {
	add_filter( 'gettext', 'wppa_translate' );
	add_filter( 'widget_title', 'wppa_translate', 1 );
	add_filter( 'translate_text', 'wppa_translate', 1 );
}
add_action( 'plugins_loaded', 'wppa_filter_qtranslate', 100 );

// Fix All in one SEO tampers occur counter
function wppa_fix_aioseo() {
global $wppa;

	if ( $wppa['mocc'] ) {
		$wppa['mocc'] = '0';
	}
}
add_action( 'wp_head', 'wppa_fix_aioseo', '99' );
add_action( 'admin_head', 'wppa_fix_aioseo', '99' );