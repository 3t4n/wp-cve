<?php 
/***************************************************************************
Plugin Name:  Lightbox Galleries EWSEL
Plugin URI:   http://www.ewsel.com/
Description:  Makes the WordPress galleries with a lightbox script called <a href="http://colorpowered.com/colorbox/"> ColorBox </ a>, to view the full size pictures.
Version:      1.0.7
Author:       EWSEL
Author URI:   http://www.ewsel.com/
Licensed under the GNU GENERAL PUBLIC LICENSE Free Software Foundation, Inc. <http://fsf.org/>
**************************************************************************/

class LightboxForGalleries {
	var $version = '1.0.7';
	var $themes = array();
	var $settings = array();
	var $defaultsettings = array();

	// Plugin initialization
	function LightboxForGalleries() {
		if ( !function_exists('plugins_url') )
			return;

		load_plugin_textdomain( 'ewsel-jquery-lightbox', false, '/ewsel-lightbox-for-galleries/localization' );

		add_action( 'wp_head',         array(&$this, 'wp_head') );
		add_filter( 'attachment_link', array(&$this, 'attachment_link'), 10, 2 );

		add_action( 'admin_menu',      array(&$this, 'register_settings_page') );
		add_action( 'admin_init',      array(&$this, 'register_setting') );

		if ( !is_admin() ) {
			wp_enqueue_script( 'colorbox', plugins_url( 'colorbox/jquery.colorbox-min.js', __FILE__ ), array( 'jquery' ), '1.3.14' );

			wp_register_style( 'colorbox-theme1', plugins_url( 'colorbox/theme1/colorbox.css', __FILE__ ), array(), '1.3.14', 'screen' );
			wp_register_style( 'colorbox-theme2', plugins_url( 'colorbox/theme2/colorbox.css', __FILE__ ), array(), '1.3.14', 'screen' );
			wp_register_style( 'colorbox-theme3', plugins_url( 'colorbox/theme3/colorbox.css', __FILE__ ), array(), '1.3.14', 'screen' );
			wp_register_style( 'colorbox-theme4', plugins_url( 'colorbox/theme4/colorbox.css', __FILE__ ), array(), '1.3.14', 'screen' );
			wp_register_style( 'colorbox-theme5', plugins_url( 'colorbox/theme5/colorbox.css', __FILE__ ), array(), '1.3.14', 'screen' );
			wp_register_style( 'colorbox-theme6', plugins_url( 'colorbox/theme6/colorbox.css', __FILE__ ), array(), '1.3.14', 'screen' );
			wp_register_style( 'colorbox-theme7', plugins_url( 'colorbox/theme7/colorbox.css', __FILE__ ), array(), '1.3.14', 'screen' );
			wp_register_style( 'colorbox-theme8', plugins_url( 'colorbox/theme8/colorbox.css', __FILE__ ), array(), '1.3.14', 'screen' );
		}

		// Create list of themes and their human readable names
		$this->themes = (array) apply_filters( 'ewsel-jquery-lightbox_themes', array(
			'theme1' => __( 'Theme #1', 'ewsel-jquery-lightbox' ),
			'theme2' => __( 'Theme #2', 'ewsel-jquery-lightbox' ),
			'theme3' => __( 'Theme #3', 'ewsel-jquery-lightbox' ),
			'theme4' => __( 'Theme #4', 'ewsel-jquery-lightbox' ),
			'theme5' => __( 'Theme #5', 'ewsel-jquery-lightbox' ),
			'theme6' => __( 'Theme #6', 'ewsel-jquery-lightbox' ),
			'theme7' => __( 'Theme #7', 'ewsel-jquery-lightbox' ),
			'theme8' => __( 'Theme #8', 'ewsel-jquery-lightbox' ),
		) );

		// Create array of default settings (you can use the filter to modify these)
		$defaulttheme = key( $this->themes );
		$this->defaultsettings = (array) apply_filters( 'ewsel-jquery-lightbox_defaultsettings', array(
			'theme' => $defaulttheme,
		) );

		// Create the settings array by merging the user's settings and the defaults
		$usersettings = (array) get_option('ewsel-jquery-lightbox_settings');
		$this->settings = wp_parse_args( $usersettings, $this->defaultsettings );

		// Enqueue the theme
		if ( empty($this->themes[$this->settings['theme']]) )
			$this->settings['theme'] = $this->defaultsettings['theme'];
		wp_enqueue_style( 'colorbox-' . $this->settings['theme'] );
	}


	// Register the settings page
	function register_settings_page() {
		add_options_page( __('Lightbox For Galleries', 'ewsel-jquery-lightbox'), __('EWSEL Lightbox', 'ewsel-jquery-lightbox'), 'manage_options', 'ewsel-jquery-lightbox', array(&$this, 'settings_page') );
	}


	// Register the plugin's setting
	function register_setting() {
		register_setting( 'ewsel-jquery-lightbox_settings', 'ewsel-jquery-lightbox_settings', array(&$this, 'validate_settings') );
	}


	// Output the Javascript to create the Lightbox
	function wp_head() { ?>
<!-- Ewsel Lightbox For Galleries v<?php echo $this->version; ?> | http://www.ewsel.com/plugin/ewsel-lightbox-for-galleries/ -->
<script type="text/javascript">
// <![CDATA[
	jQuery(document).ready(function($){
		$(".gallery").each(function(index, obj){
			var galleryid = Math.floor(Math.random()*10000);
			$(obj).find("a").colorbox({rel:galleryid, maxWidth:"95%", maxHeight:"95%"});
		});
		$("a.lightbox").colorbox({maxWidth:"95%", maxHeight:"95%"});
	});
// ]]>
</script>
<?php
	}


	// Make the thumbnails link to the fullsize image rather than a Page with the medium sized image
	function attachment_link( $link, $id ) {
		// The lightbox doesn't function inside feeds obviously, so don't modify anything
		if ( is_feed() || is_admin() )
			return $link;

		$post = get_post( $id );

		if ( 'image/' == substr( $post->post_mime_type, 0, 6 ) )
			return wp_get_attachment_url( $id );
		else
			return $link;
	}


	// Settings page
	function settings_page() { ?>

<div class="wrap">
<?php screen_icon(); ?>
	<h2><?php _e( 'Lightbox For Galleries Settings', 'ewsel-jquery-lightbox' ); ?></h2>

	<form method="post" action="options.php">

	<?php settings_fields('ewsel-jquery-lightbox_settings'); ?>


	<p><?php _e( 'Sorry if you expected more hate, but thats all. Nothing to configure. :)', 'ewsel-jquery-lightbox' ); ?></p>

	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="ewsel-jquery-lightbox-theme"><?php _e('Theme', 'ewsel-jquery-lightbox'); ?></label></th>
			<td>
				<select name="ewsel-jquery-lightbox_settings[theme]" id="ewsel-jquery-lightbox-theme" class="postform">
<?php
					foreach ( $this->themes as $theme => $name ) {
						echo '					<option value="' . esc_attr($theme) . '"';
						selected( $this->settings['theme'], $theme );
						echo '>' . htmlspecialchars($name) . "</option>\n";
					}
?>
				</select>
			</td>
		</tr>
	</table>

	<p class="submit">
		<input type="submit" name="ewsel-jquery-lightbox-submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>

	</form>
</div>

<?php
	}


	// Validate the settings sent from the settings page
	function validate_settings( $settings ) {
		if ( empty($settings['theme']) || empty($this->themes[$settings['theme']]) )
			$settings['theme'] = $this->defaultsettings['theme'];

		return $settings;
	}
}

// Start the plugin up
add_action( 'init', 'LightboxForGalleries', 7 );
function LightboxForGalleries() {
	global $LightboxForGalleries;
	$LightboxForGalleries = new LightboxForGalleries();
}

?>