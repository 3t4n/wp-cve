<?php
/*
Plugin Name: Skip to Timestamp
Plugin URI: http://github.com/doytch/SkipToTimestamp
Description: Adds clickable timestamps via shortcode or search-and-replace that skip to a time in a media player.
Version: 1.4.4
Author: Mark Deutsch
Author URI: http://github.com/doytch
License: GPLv2
*/

/* --- Logging --- */
// function qed_stt_logger( $msg, $name = '' )
// {
//     // Print the name of the calling function if $name is left empty
//     $trace = debug_backtrace();
//     $name = ( '' == $name ) ? $trace[1]['function'] : $name;

//     $error_dir = '/Applications/MAMP/logs/php_error.log';
//     $msg = print_r( $msg, true );
//     $log = $name . "  |  " . $msg . "\n";
//     error_log( $log, 3, $error_dir );
// }

/* --- Installation --- */
register_activation_hook(__FILE__, 'qed_stt_install');
function qed_stt_install() {
	$default_settings = array(
		'only_link_singular' 	=> 1,
		'link_audio'			=> 1,
		'link_video' 			=> 1,
		'link_youtube' 			=> 1,
		'auto_replace_ts'		=> 0
	);
	update_option('qed_stt_settings', $default_settings);
}

/* --- Uninstallation --- */
// register_deactivation_hook(__FILE__, 'qed_stt_uninstall')

/* --- Youtube --- */
// Enable Youtube's Javascript API for videos embedded with Wordpress' builtin oembeds
add_filter('oembed_result', 'qed_stt_enable_yt_jsapi');
function qed_stt_enable_yt_jsapi($html) {
	if (strstr($html, 'youtube.com/embed/')) {
		$html = str_replace('?feature=oembed', '?feature=oembed&enablejsapi=1', $html);
	}
	return $html;
}

/* --- Shortcode --- */
add_shortcode('skipto', 'qed_stt_shortcode');

function qed_stt_shortcode($attr, $content) {
	$options = get_option('qed_stt_settings');
	// Only link singular posts if the option is set
	if (!is_singular() && $options['only_link_singular']) {
		return $content;
	}

	// Cover a few attributes for ease of use
	$time = -1;
	if (isset($attr['time'])) {
		$time = $attr['time'];
	} else if (isset($attr['timestamp'])) {
		$time = $attr['timestamp'];
	} else if (isset($attr['ts'])) {
		$time = $attr['ts'];
	}

	if ($time == -1) {
		return $content;
	} else {
		return "<a href=\"javascript:void(0)\" class=\"qed_stt_tslink\" data-stt-time=\"{$time}\">{$content}</a>";
	}
}

function qed_stt_post_has_shortcode() {
	$the_post = get_post(get_the_ID());
	if (stripos($the_post->post_content, '[skipto ') !== false) {
		return true;
	} else {
		return false;
	}
}

/* --- Admin Page --- */
// Create the settings submenu
add_action('admin_menu', 'qed_stt_create_menu');
function qed_stt_create_menu() {
	add_options_page(
		'Skip to Timestamp',
		'Skip to Timestamp',
		'manage_options',
		'skiptotimestamp',
		'qed_stt_create_settings_page'
	);
}

// Draw the settings page
function qed_stt_create_settings_page() {
?>
	<div class="wrap">
	<?php screen_icon(); ?>
	<h2>Skip to Timestamp</h2>
	<form action="options.php" method="post">
	</div>
	<?php settings_fields('qed_stt_settings'); ?>
	<?php do_settings_sections('skiptotimestamp'); ?>
	<input name="Submit" type="submit" value="Save Changes" />
	</form></div>
	<?php
}

// Register and define settings
add_action('admin_init', 'qed_stt_admin_init');
function qed_stt_admin_init() {
	register_setting(
		'qed_stt_settings',
		'qed_stt_settings',
		'qed_stt_validate_settings'
	);
	add_settings_section(
		'qed_stt_main',
		'Skip to Timestamp Settings',
		'qed_stt_settings_text',
		'skiptotimestamp'
	);
	add_settings_field(
		'qed_stt_only_link_singular',
		'Link only on singular posts/pages',
		'qed_stt_only_link_singular_select_create',
		'skiptotimestamp',
		'qed_stt_main'
	);
	add_settings_field(
		'qed_stt_link_audio',
		'Skip in embedded audio',
		'qed_stt_link_audio_create',
		'skiptotimestamp',
		'qed_stt_main'
	);
	add_settings_field(
		'qed_stt_link_video',
		'Skip in embedded video',
		'qed_stt_link_video_create',
		'skiptotimestamp',
		'qed_stt_main'
	);
	add_settings_field(
		'qed_stt_link_youtube',
		'Skip in embedded Youtube videos',
		'qed_stt_link_youtube_create',
		'skiptotimestamp',
		'qed_stt_main'
	);
	add_settings_field(
		'qed_stt_auto_replace_ts',
		'Replace Timestamps Automatically',
		'qed_stt_auto_replace_ts_create',
		'skiptotimestamp',
		'qed_stt_main'
	);
}

function qed_stt_settings_text() {}

function qed_stt_only_link_singular_select_create() {
	$options = get_option('qed_stt_settings');
	$only_link_singular = $options['only_link_singular'];
	echo "<input name='qed_stt_settings[only_link_singular]' type='checkbox'";
	if ($only_link_singular) echo ' checked ';
	echo "/>Only generate links on singular posts/pages.";
}

function qed_stt_link_audio_create() {
	$options = get_option('qed_stt_settings');
	$link_audio = $options['link_audio'];
	echo "<input name='qed_stt_settings[link_audio]' type='checkbox'";
	if ($link_audio) echo ' checked ';
	echo "/>Skip to timestamp in audio embedded with the [audio] shortcode or &lt;audio&gt; HTML5 tag.";
}

function qed_stt_link_video_create() {
	$options = get_option('qed_stt_settings');
	$link_video = $options['link_video'];
	echo "<input name='qed_stt_settings[link_video]' type='checkbox'";
	if ($link_video) echo ' checked ';
	echo "/>Skip to timestamp in video embedded with the [video] shortcode or &lt;video&gt; HTML5 tag.";

}

function qed_stt_link_youtube_create() {
	$options = get_option('qed_stt_settings');
	$link_youtube = $options['link_youtube'];
	echo "<input name='qed_stt_settings[link_youtube]' type='checkbox'";
	if ($link_youtube) echo ' checked ';
	echo "/>Skip to timestamp in embedded Youtube videos.";
}

function qed_stt_auto_replace_ts_create() {
	$options = get_option('qed_stt_settings');
	$auto_replace_ts = $options['auto_replace_ts'];
	echo "<input name='qed_stt_settings[auto_replace_ts]' type='checkbox'";
	if ($auto_replace_ts) echo ' checked ';
	echo "/><br><i>Normally you create hyperlinks using the [skipto] shortcode. Check this and text formatted like";
	echo " '3:45' in your posts will get automatically replaced with a hyperlink.</i>";
}

function qed_stt_validate_settings($input) {
	$valid = array(
		'only_link_singular' 	=> isset($input['only_link_singular']) && true == $input['only_link_singular'] ? true : false,
		'link_audio'		 	=> isset($input['link_audio']) && true == $input['link_audio'] ? true : false,
		'link_video' 			=> isset($input['link_video']) && true == $input['link_video'] ? true : false,
		'link_youtube' 			=> isset($input['link_youtube']) && true == $input['link_youtube'] ? true : false,
		'auto_replace_ts' 		=> isset($input['auto_replace_ts']) && true == $input['auto_replace_ts'] ? true : false
	);

	return $valid;
}

/* --- Metaboxes --- */
add_action( 'add_meta_boxes', 'qed_stt_add_metabox' );
add_action( 'save_post', 'qed_stt_save_metabox');
add_action( 'wp_ajax_qed_stt_ajax_get_page_settings', 'qed_stt_get_page_settings');

/**
 * Register the metabox
 *
 */
function qed_stt_add_metabox($post_type) {
	$screens = array( 'post', 'page');

	foreach ($screens as $screen) {
		add_meta_box(
			'stt_post_mb',
			__('Skip to Timestamp Configuration', 'stt'),
			'qed_stt_create_metabox',
			$screen,
			'normal',
			'high'
		);
	}
}

function qed_stt_create_metabox($post) {
	wp_nonce_field('qed_stt_post_mb', 'qed_stt_post_mb_nonce');

	?>
	<table id='qed-stt-mb-table' class='form-table'>
		<tr valign='top'>
			<th scope='row'><?php _e('Disable Automatic Links'); ?></th>
			<td>
				<input type='checkbox' id='qed-stt-disable-auto-link' name='qed-stt-disable-auto-link' valu
					<?php echo get_post_meta(get_the_ID(), 'qed-stt-disable-auto-link', true) ? 'checked' : '' ?>
				/>
			</td>
		</tr>
	</table>
	<?php
}

function qed_stt_save_metabox($post_id) {
	// Verify the nonce to ensure we're getting called from the right spot.
	if (!isset($_POST['qed_stt_post_mb_nonce'])) {
		return $post_id;
	}
	$nonce = $_POST['qed_stt_post_mb_nonce'];
	if (!wp_verify_nonce($nonce, 'qed_stt_post_mb')) {
		return $post_id;
	}

	update_post_meta($post_id, 'qed-stt-disable-auto-link', isset($_POST['qed-stt-disable-auto-link']));
}

/* --- Automatic Hyperlinking --- */
add_action('plugins_loaded', 'qed_stt_loaded');
function qed_stt_loaded() {
	add_filter('the_content', 'qed_stt_hyperlink_timestamps');
}

add_action('wp_enqueue_scripts', 'qed_stt_enqueue_scripts');
function qed_stt_enqueue_scripts() {
	wp_enqueue_style( 'thickbox');
	wp_enqueue_script('qed-stt-youtube', 'https://www.youtube.com/iframe_api');
	wp_enqueue_script('qed-stt-js', plugin_dir_url(__FILE__).'/js/skiptotimestamp.js');
	// Expose our settings to our Javascript
	wp_localize_script('qed-stt-js', 'STTSettings', get_option('qed_stt_settings'));
}

function qed_stt_hyperlink_timestamps($content) {
	// Don't autolink if it's turned off.
	$options = get_option('qed_stt_settings');
	if (!$options['auto_replace_ts']) {
		return $content;
	}
	// Don't autolink if singular page and only linking on singular pages is turned on.
	if (!is_singular() && $options['only_link_singular']) {
		return $content;
	}
	// Don't autolink if they've turned off autolinking for this page.
	if (get_post_meta(get_the_ID(), 'qed-stt-disable-auto-link', true)) {
		return $content;
	}

	// Don't allow shortcodes and autolinks in the same post
	if (qed_stt_post_has_shortcode()) {
		return $content;
	}

	$content = preg_replace(
		"/(?:(?:(?<hh>\d{1,2})[:.])?(?<mm>\d{1,2})[:.])(?<ss>\d{1,2})/",
		'<a href="javascript:void()" class="qed_stt_tslink" data-stt-time="$0">$0</a>',
		$content
	);

	return $content;
}

add_action('wp_footer', 'qed_stt_custom_css');
function qed_stt_custom_css() {
?>
    <style>
    .qed_stt_tslink {
        cursor: pointer;
    }
    </style>
<?php
}
?>
