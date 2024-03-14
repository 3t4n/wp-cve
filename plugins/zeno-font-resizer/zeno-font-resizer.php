<?php
/*
Plugin Name: Zeno Font Resizer
Plugin URI: https://wordpress.org/plugins/zeno-font-resizer/
Description: Zeno Font Resizer allows the visitors of your website to change the font size of your text.
Author: Marcel Pol
Version: 1.8.0
Author URI: https://timelord.nl
Text Domain: zeno-font-resizer
Domain Path: /lang/



Copyright 2010 - 2013  Cubetech GmbH
Copyright 2015 - 2023  Marcel Pol    (marcel@timelord.nl)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// Plugin Version.
define('ZENO_FR_VER', '1.8.0');


/*
 * Add the options to WordPress if they don't exist.
 */
add_option('zeno_font_resizer',             'html');
add_option('zeno_font_resizer_ownid',       ''    );
add_option('zeno_font_resizer_ownelement',  ''    );
add_option('zeno_font_resizer_resizeMax',   '24'  );
add_option('zeno_font_resizer_resizeMin',   '10'  );
add_option('zeno_font_resizer_resizeSteps', '1.6' );
add_option('zeno_font_resizer_letter',      'A'   );
add_option('zeno_font_resizer_cookieTime',  '31'  );


/*
 * Register an administration page.
 */
function zeno_font_resizer_add_admin_page() {
	add_options_page( esc_html__( 'Zeno Font Resizer', 'zeno-font-resizer' ), esc_html__( 'Zeno Font Resizer', 'zeno-font-resizer' ), 'manage_options', 'zeno-font-resizer', 'zeno_font_resizer_admin_page');
}
add_action('admin_menu', 'zeno_font_resizer_add_admin_page');


/*
 * Generates the Settings Page.
 */
function zeno_font_resizer_admin_page() {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Zeno Font Resizer', 'zeno-font-resizer' ); ?></h1>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'zeno_font_resizer' );
			do_settings_sections( 'zeno_font_resizer' );
			submit_button();
			?>
		</form>

		<h2><?php esc_html_e('Documentation', 'zeno-font-resizer'); ?></h2>
		<div class="postbox">
			<div class="widget" style="padding: 10px 20px;">
				<p><?php esc_html_e('Having problems or you need something? Read the FAQ at', 'zeno-font-resizer'); ?>
					<a href="https://wordpress.org/plugins/zeno-font-resizer/faq/" target="_blank" title="<?php esc_attr_e('FAQ at the WordPress Plugin Page', 'zeno-font-resizer'); ?>"><?php esc_html_e('the WordPress Plugin Page', 'zeno-font-resizer'); ?></a>.
				</p>
			</div>
		</div>

		<h2><?php esc_html_e('About Zeno Font Resizer', 'zeno-font-resizer'); ?></h2>
		<div id="poststuff" class="postbox">
			<div class="widget" style="padding: 10px 20px;">

				<h3><?php esc_html_e('Support', 'zeno-font-resizer'); ?></h3>
				<p><?php
					$support = '<a href="https://wordpress.org/support/plugin/zeno-font-resizer" target="_blank">';
					/* translators: %1$s and %2$s is a link */
					echo sprintf( esc_html__( 'If you have a problem or a feature request, please post it on the %1$ssupport forum at wordpress.org%2$s.', 'zeno-font-resizer' ), $support, '</a>' ); ?>
					<?php esc_html_e('I will do my best to respond as soon as possible.', 'zeno-font-resizer'); ?><br />
					<?php esc_html_e('If you send me an email, I will not reply. Please use the support forum.', 'zeno-font-resizer'); ?>
				</p>


				<h3><?php esc_html_e('Review this plugin.', 'zeno-font-resizer'); ?></h3>
				<p><?php
					$review = '<a href="https://wordpress.org/support/view/plugin-reviews/zeno-font-resizer?rate=5#postform" target="_blank">';
					/* translators: %1$s and %2$s is a link */
					echo sprintf( esc_html__( 'If this plugin has any value to you, then please leave a review at %1$sthe plugin page%2$s at wordpress.org.', 'zeno-font-resizer' ), $review, '</a>' ); ?>
				</p>

				<h3><?php esc_html_e('Translations', 'zeno-font-resizer'); ?></h3>
				<p><?php
					$link = '<a href="https://translate.wordpress.org/projects/wp-plugins/zeno-font-resizer" target="_blank">';
					/* translators: %1$s and %2$s is a link */
					echo sprintf( esc_html__( 'Translations can be added very easily through %1$sGlotPress%2$s.', 'zeno-font-resizer' ), $link, '</a>' );
					echo '<br />';
					/* translators: %1$s and %2$s is a link */
					echo sprintf( esc_html__( 'You can start translating strings there for your locale. They need to be validated though, so if there is no validator yet, and you want to apply for being validator (PTE), please post it on the %1$ssupport forum%2$s.', 'zeno-font-resizer' ), $support, '</a>' );
					echo '<br />';
					$make = '<a href="https://make.wordpress.org/polyglots/" target="_blank">';
					/* translators: %1$s and %2$s is a link */
					echo sprintf( esc_html__( 'I will make a request on %1$smake/polyglots%2$s to have you added as validator for this plugin/locale.', 'zeno-font-resizer' ), $make, '</a>' ); ?>
				</p>

			</div>
		</div>

	</div>
	<?php
}


/*
 * Enqueue the dependencies.
 */
function zeno_font_resizer_enqueue() {
	$zeno_font_resizer_path = plugins_url( 'js/', __FILE__ );
	wp_register_script('zeno_font_resizer_cookie',   $zeno_font_resizer_path . 'js.cookie.js', array( 'jquery' ), ZENO_FR_VER, true);
	wp_register_script('zeno_font_resizer_fontsize', $zeno_font_resizer_path . 'jquery.fontsize.js', array( 'jquery' ), ZENO_FR_VER, true);
	wp_enqueue_script('jquery');
	wp_enqueue_script('zeno_font_resizer_cookie');
	wp_enqueue_script('zeno_font_resizer_fontsize');
}
add_action('wp_enqueue_scripts', 'zeno_font_resizer_enqueue');


/*
 * Generate the font-resizer text on the frontend.
 * Used as template function for developers.
 * Parameter: $echo, boolean:
 *            - true: echo the template code (default).
 *            - false: return the template code.
 */
function zeno_font_resizer_place( $echo = true ) {
	$html = '
	<div class="zeno_font_resizer_container">
		<p class="zeno_font_resizer" style="text-align: center; font-weight: bold;">
			<span>
				<a href="#" class="zeno_font_resizer_minus" title="' . esc_attr__( 'Decrease font size', 'zeno-font-resizer' ) . '" style="font-size: 0.7em;">' .
					esc_attr( sanitize_text_field( get_option('zeno_font_resizer_letter') ) ) . '<span class="screen-reader-text"> ' . esc_html__('Decrease font size.', 'zeno-font-resizer') . '</span>' .
				'</a>
				<a href="#" class="zeno_font_resizer_reset" title="' . esc_attr__( 'Reset font size', 'zeno-font-resizer' ) . '">' .
					esc_attr( sanitize_text_field( get_option('zeno_font_resizer_letter') ) ) . '<span class="screen-reader-text"> ' . esc_html__('Reset font size.', 'zeno-font-resizer') . '</span>' .
				'</a>
				<a href="#" class="zeno_font_resizer_add" title="' . esc_attr__( 'Increase font size', 'zeno-font-resizer' ) . '" style="font-size: 1.3em;">' .
					esc_attr( sanitize_text_field( get_option('zeno_font_resizer_letter') ) ) . '<span class="screen-reader-text"> ' . esc_html__('Increase font size.', 'zeno-font-resizer') . '</span>' .
				'</a>
			</span>
			<input type="hidden" id="zeno_font_resizer_value" value="' . esc_attr( sanitize_text_field( get_option('zeno_font_resizer') ) ) . '" />
			<input type="hidden" id="zeno_font_resizer_ownid" value="' . esc_attr( sanitize_text_field( get_option('zeno_font_resizer_ownid') ) ) . '" />
			<input type="hidden" id="zeno_font_resizer_ownelement" value="' . esc_attr( sanitize_text_field( get_option('zeno_font_resizer_ownelement') ) ) . '" />
			<input type="hidden" id="zeno_font_resizer_resizeMax" value="' . esc_attr( sanitize_text_field( get_option('zeno_font_resizer_resizeMax') ) ) . '" />
			<input type="hidden" id="zeno_font_resizer_resizeMin" value="' . esc_attr( sanitize_text_field( get_option('zeno_font_resizer_resizeMin') ) ) . '" />
			<input type="hidden" id="zeno_font_resizer_resizeSteps" value="' . esc_attr( sanitize_text_field( get_option('zeno_font_resizer_resizeSteps') ) ) . '" />
			<input type="hidden" id="zeno_font_resizer_cookieTime" value="' . esc_attr( sanitize_text_field( get_option('zeno_font_resizer_cookieTime') ) ) . '" />
		</p>
	</div>
	';
	if ( $echo === true ) {
		echo $html;
	} else {
		return $html;
	}
}


/*
 * Add CSS for broken themes.
 * Handbook: https://make.wordpress.org/accessibility/handbook/markup/the-css-class-screen-reader-text/
 *
 * @since 1.7.4
 */
function zeno_font_resizer_head_style() {
	echo '
	<style id="zeno-font-resizer" type="text/css">
		p.zeno_font_resizer .screen-reader-text {
			border: 0;
			clip: rect(1px, 1px, 1px, 1px);
			clip-path: inset(50%);
			height: 1px;
			margin: -1px;
			overflow: hidden;
			padding: 0;
			position: absolute;
			width: 1px;
			word-wrap: normal !important;
		}
	</style>
	';
}
add_action('wp_head', 'zeno_font_resizer_head_style');


/*
 * Add Settings link to the main Plugin page.
 */
function zeno_font_resizer_links( $links, $file ) {
	if ( $file === plugin_basename( dirname(__FILE__) . '/zeno-font-resizer.php' ) ) {
		$links[] = '<a href="' . admin_url( 'options-general.php?page=zeno-font-resizer' ) . '">' . esc_html__( 'Settings', 'zeno-font-resizer' ) . '</a>';
	}
	return $links;
}
add_filter( 'plugin_action_links', 'zeno_font_resizer_links', 10, 2 );


/*
 * Load language files for frontend and backend.
 */
function zeno_font_resizer_load_lang() {
	load_plugin_textdomain( 'zeno-font-resizer', false, plugin_basename(dirname(__FILE__)) . '/lang' );
}
add_action('plugins_loaded', 'zeno_font_resizer_load_lang');


/*
 * Register Settings
 */
function zeno_font_resizer_register_settings() {
	add_settings_section(
		'zeno_font_resizer',
		'',
		'',
		'zeno_font_resizer'
	);

	add_settings_field(
		'zeno_font_resizer',
		esc_html__( 'HTML Element', 'zeno-font-resizer' ),
		'zeno_font_resizer_callback_function',
		'zeno_font_resizer',
		'zeno_font_resizer'
	);
	register_setting( 'zeno_font_resizer', 'zeno_font_resizer', 'strval' ); // 'html'
	register_setting( 'zeno_font_resizer', 'zeno_font_resizer_ownid', 'strval' ); // empty by default
	register_setting( 'zeno_font_resizer', 'zeno_font_resizer_ownelement', 'strval' ); // empty by default

	add_settings_field(
		'zeno_font_resizer_resizeSteps',
		esc_html__( 'Resize Steps', 'zeno-font-resizer' ),
		'zeno_font_resizer_resizesteps_callback_function',
		'zeno_font_resizer',
		'zeno_font_resizer'
	);
	register_setting( 'zeno_font_resizer', 'zeno_font_resizer_resizeSteps', 'floatval' ); // 1.6

	add_settings_field(
		'zeno_font_resizer_resizeMin',
		esc_html__( 'Minimum Size', 'zeno-font-resizer' ),
		'zeno_font_resizer_resizemin_callback_function',
		'zeno_font_resizer',
		'zeno_font_resizer'
	);
	register_setting( 'zeno_font_resizer', 'zeno_font_resizer_resizeMin', 'intval' ); // 10

	add_settings_field(
		'zeno_font_resizer_resizeMax',
		esc_html__( 'Maximum Size', 'zeno-font-resizer' ),
		'zeno_font_resizer_resizemax_callback_function',
		'zeno_font_resizer',
		'zeno_font_resizer'
	);
	register_setting( 'zeno_font_resizer', 'zeno_font_resizer_resizeMax', 'intval' ); // 24

	add_settings_field(
		'zeno_font_resizer_letter',
		esc_html__( 'Resize Character', 'zeno-font-resizer' ),
		'zeno_font_resizer_letter_callback_function',
		'zeno_font_resizer',
		'zeno_font_resizer'
	);
	register_setting( 'zeno_font_resizer', 'zeno_font_resizer_letter', 'strval' ); // A

	add_settings_field(
		'zeno_font_resizer_cookieTime',
		esc_html__( 'Cookie Settings', 'zeno-font-resizer' ),
		'zeno_font_resizer_cookietime_callback_function',
		'zeno_font_resizer',
		'zeno_font_resizer'
	);
	register_setting( 'zeno_font_resizer', 'zeno_font_resizer_cookieTime', 'intval' ); // 31
}
add_action( 'admin_init', 'zeno_font_resizer_register_settings' );


/*
 * Callback functions for option page.
 */
function zeno_font_resizer_callback_function() {
	?>
	<label>
		<input type="radio" name="zeno_font_resizer" value="html" <?php if ( get_option('zeno_font_resizer') === 'html') echo 'checked'; ?> />
		<?php esc_html_e( 'Default setting, resize whole content in html element (&lt;html&gt;All content of your site&lt;/html&gt;).', 'zeno-font-resizer' ); ?>
	</label><br />
	<label>
		<input type="radio" name="zeno_font_resizer" value="body" <?php if ( get_option('zeno_font_resizer') === 'body' ) echo 'checked'; ?> />
		<?php esc_html_e( 'Resize whole content in body element (&lt;body&gt;All content of your site&lt;/body&gt;).', 'zeno-font-resizer' ); ?>
	</label><br />
	<label>
		<input type="radio" name="zeno_font_resizer" value="innerbody" <?php if ( get_option('zeno_font_resizer') === 'innerbody' ) echo 'checked'; ?> />
		<?php esc_html_e( 'Use div with id innerbody (&lt;div id="innerbody"&gt;Resizable text&lt;/div&gt;).', 'zeno-font-resizer' ); ?>
	</label><br />
	<label>
		<input type="radio" name="zeno_font_resizer" value="ownid" <?php if ( get_option('zeno_font_resizer') === 'ownid' ) echo 'checked'; ?> />
		<input type="text" name="zeno_font_resizer_ownid" value="<?php echo esc_attr( sanitize_text_field( get_option('zeno_font_resizer_ownid') ) ); ?>" /><br />
		<?php esc_html_e( 'Use your own div id (&lt;div id="yourid"&gt;Resizable text&lt;/div&gt;).', 'zeno-font-resizer' ); ?>
	</label><br />
	<label>
		<input type="radio" name="zeno_font_resizer" value="ownelement" <?php if ( get_option('zeno_font_resizer') === 'ownelement' ) echo 'checked'; ?> />
		<input type="text" name="zeno_font_resizer_ownelement" value="<?php echo esc_attr( sanitize_text_field( get_option('zeno_font_resizer_ownelement') ) ); ?>" /><br />
		<?php esc_html_e( 'Use your own element (For example: for a span with class "bla" (&lt;span class="bla"&gt;Resizable text&lt;/span&gt;), enter the css definition, "span.bla" (without quotes)).', 'zeno-font-resizer' ); ?>
	</label><?php
}
function zeno_font_resizer_resizesteps_callback_function() {
	?>
	<label for="zeno_font_resizer_resizeSteps">
		<input type="text" name="zeno_font_resizer_resizeSteps" value="<?php echo esc_attr( sanitize_text_field( get_option('zeno_font_resizer_resizeSteps') ) ); ?>" style="width: 3em"> <b><?php esc_html_e( 'px.', 'zeno-font-resizer' ); ?></b><br />
		<?php esc_html_e( 'Set the resize steps in pixel (default: 1.6px).', 'zeno-font-resizer' ); ?>
	</label><?php
}
function zeno_font_resizer_resizemin_callback_function() {
	?>
	<label for="zeno_font_resizer_resizeMin">
		<input type="text" name="zeno_font_resizer_resizeMin" value="<?php echo esc_attr( sanitize_text_field( get_option('zeno_font_resizer_resizeMin') ) ); ?>" style="width: 3em"> <b><?php esc_html_e( 'px.', 'zeno-font-resizer' ); ?></b><br />
		<?php esc_html_e( 'Set the minimum font size in pixels (default: 10px).', 'zeno-font-resizer' ); ?>
	</label><?php
}
function zeno_font_resizer_resizemax_callback_function() {
	?>
	<label for="zeno_font_resizer_resizeMax">
		<input type="text" name="zeno_font_resizer_resizeMax" value="<?php echo esc_attr( sanitize_text_field( get_option('zeno_font_resizer_resizeMax') ) ); ?>" style="width: 3em"> <b><?php esc_html_e( 'px.', 'zeno-font-resizer' ); ?></b><br />
		<?php esc_html_e( 'Set the maximum font size in pixels (default: 24px).', 'zeno-font-resizer' ); ?>
	</label><?php
}
function zeno_font_resizer_letter_callback_function() {
	?>
	<label for="zeno_font_resizer_letter">
		<input type="text" name="zeno_font_resizer_letter" value="<?php echo esc_attr( sanitize_text_field( get_option('zeno_font_resizer_letter') ) ); ?>" maxlength="1" style="width: 3em"><br />
		<?php esc_html_e( 'Sets the letter to be displayed in the resizer in the website.', 'zeno-font-resizer' ); ?>
	</label><?php
}
function zeno_font_resizer_cookietime_callback_function() {
	?>
	<label for="zeno_font_resizer_cookieTime">
		<input type="text" name="zeno_font_resizer_cookieTime" value="<?php echo esc_attr( sanitize_text_field( get_option('zeno_font_resizer_cookieTime') ) ); ?>" style="width: 3em"> <b><?php esc_html_e( 'days.', 'zeno-font-resizer' ); ?></b><br />
		<?php esc_html_e( 'Set the cookie store time (default: 31 days).', 'zeno-font-resizer' ); ?>
	</label><?php
}


/*
 * Delete the options when you uninstall the plugin.
 */
function zeno_font_resizer_uninstaller() {
	delete_option('zeno_font_resizer');
	delete_option('zeno_font_resizer_ownid');
	delete_option('zeno_font_resizer_ownelement');
	delete_option('zeno_font_resizer_resizeMax');
	delete_option('zeno_font_resizer_resizeMin');
	delete_option('zeno_font_resizer_resizeSteps');
	delete_option('zeno_font_resizer_letter');
	delete_option('zeno_font_resizer_cookieTime');
}
register_uninstall_hook( __FILE__, 'zeno_font_resizer_uninstaller' );


/* Load the widget */
require_once 'widget.php';
