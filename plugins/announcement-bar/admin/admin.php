<?php
/**
 * Administration functions for loading and displaying the settings page and saving settings 
 * are handled in this file.
 *
 * @package AnnouncementBar
 */

/* Initialize the theme admin functionality. */
add_action( 'init', 'announcement_bar_admin_init' );

/**
 * Initializes the theme administration functions.
 *
 * @since 0.8
 */
function announcement_bar_admin_init() {
	add_action( 'admin_menu', 'announcement_bar_settings_page_init' );

	add_action( 'announcement_bar_update_settings_page', 'announcement_bar_save_settings' );
	
	add_action( 'announcement_bar_flush', 'announcement_bar_flush_rewrite' );
	
	add_action( 'admin_init', 'announcement_bar_admin_warnings' );
	
	add_action( 'admin_head', 'announcement_bar_admin_css_fix' );
	
	add_action( 'admin_init', 'announcement_bar_scripts' );
	
	add_action( 'admin_init', 'announcement_bar_styles' );
			
	/* Add a settings page to the plugin menu */
	add_filter( 'plugin_action_links', 'announcement_bar_plugin_actions', 10, 2 );
}

/**
 * Register the javascript.
 *
 * @since 0.8
 */
function announcement_bar_scripts() {
	$plugin_data = get_plugin_data( ANNOUNCEMENT_BAR_DIR . 'announcement.php' );
	
	wp_register_script( Announcement_Bar::domain . '-admin', ANNOUNCEMENT_BAR_JS . 'admin.js', array( 'jquery' ), $plugin_data['Version'], false );
	
	wp_register_script( 'jscolor', ANNOUNCEMENT_BAR_JS . 'jscolor.js', false, '1.3.1', false );
}

/**
 * Register the stylesheets.
 *
 * @since 0.8
 */
function announcement_bar_styles() {	
	$plugin_data = get_plugin_data( ANNOUNCEMENT_BAR_DIR . 'announcement.php' );
	
	wp_register_style( Announcement_Bar::domain . '-tabs', ANNOUNCEMENT_BAR_CSS . 'tabs.css', false, $plugin_data['Version'], 'screen' );
	
	wp_register_style( Announcement_Bar::domain . '-admin', ANNOUNCEMENT_BAR_CSS . 'admin.css', false, $plugin_data['Version'], 'screen' );
}

/**
 * Sets up the cleaner gallery settings page and loads the appropriate functions when needed.
 *
 * @since 0.8
 */
function announcement_bar_settings_page_init() {
	global $announcement_bar;

	/* Create the theme settings page. */
	$announcement_bar->settings_page = add_options_page( __( 'Announcement Bar', Announcement_Bar::domain ), __( 'Announcement Bar', Announcement_Bar::domain ), 'manage_options', Announcement_Bar::domain, 'announcement_bar_settings_page' );

	/* Register the default theme settings meta boxes. */
	add_action( "load-{$announcement_bar->settings_page}", 'announcement_bar_create_settings_meta_boxes' );

	/* Make sure the settings are saved. */
	add_action( "load-{$announcement_bar->settings_page}", 'announcement_bar_load_settings_page' );

	/* Load the JavaScript and stylehsheets needed for the theme settings. */
	add_action( "load-{$announcement_bar->settings_page}", 'announcement_bar_settings_page_enqueue_script' );
	add_action( "load-{$announcement_bar->settings_page}", 'announcement_bar_settings_page_enqueue_style' );
	add_action( "admin_head-{$announcement_bar->settings_page}", 'announcement_bar_settings_page_load_scripts' );
}

/**
 * Returns an array with the default plugin settings.
 *
 * @since 0.8
 */
function announcement_bar_settings() {
	$plugin_data = get_plugin_data( ANNOUNCEMENT_BAR_DIR . 'announcement.php' );
	
	$settings = array(
		'version'		=> $plugin_data['Version'],
		'notice'		=> true,
		/* Activate */
		'activate'		=> false,		
		/* Rewrite Slug */	
		'slug'			=> 'announcing',
		
		/* Options */
		'height'		=> '33px',
		'background'	=> '#FFFFE0',
		'color'			=> '#444444',
		'a_color' 		=> '#222222',
		'size'			=> '14px',
		'custom_css'	=> '',
	);
	return apply_filters( 'announcement_bar_settings', $settings );
}

/**
 * Function run at load time of the settings page, which is useful for hooking save functions into.
 *
 * @since 0.8
 */
function announcement_bar_load_settings_page() {

	/* Get theme settings from the database. */
	$settings = get_option( 'announcement_bar_settings' );

	/* If no settings are available, add the default settings to the database. */
	if ( empty( $settings ) ) {
		add_option( 'announcement_bar_settings', announcement_bar_settings(), '', 'yes' );

		/* Redirect the page so that the settings are reflected on the settings page. */
		wp_redirect( admin_url( 'options-general.php?page=announcement-bar' ) );
		exit;
	}

	/* If the form has been submitted, check the referer and execute available actions. */
	elseif ( isset( $_POST['announcement-bar-settings-submit'] ) ) {

		/* Make sure the form is valid. */
		check_admin_referer( 'announcement-bar-settings-page' );

		/* Available hook for saving settings. */
		do_action( 'announcement_bar_update_settings_page' );
		
		/* Get the current theme settings. */
		$settings = get_option( 'announcement_bar_settings' );
		
		if ( isset( $_POST['slug'] ) && $_POST['slug'] != $settings['slug'] )
			do_action( 'announcement_bar_flush' );

		/* Redirect the page so that the new settings are reflected on the settings page. */
		wp_redirect( admin_url( 'options-general.php?page=announcement-bar&updated=true' ) );
		exit;
	} 
	
	/* If the form has been submitted, check the referer and execute available actions. */
	elseif ( isset( $_GET['notice'] ) ) {

		/* Make sure the form is valid. */
		check_admin_referer( 'announcement-bar-notice' );
		
		/* Get the current theme settings. */
		$settings = get_option( 'announcement_bar_settings' );
		
		$settings['notice'] = ( ( isset( $_GET['notice'] ) ) ? false : true );

		/* Available hook for saving settings. */
		update_option( 'announcement_bar_settings', $settings );

		/* Redirect the page so that the new settings are reflected on the settings page. */
		//wp_redirect( admin_url( 'options-general.php?page=announcement-bar&updated=true' ) );
		//exit;
	}
}

/**
 * Validates the plugin settings.
 *
 * @since 0.8
 */
function announcement_bar_save_settings() {

	/* Get the current theme settings. */
	$settings = get_option( 'announcement_bar_settings' );

	$settings['version'] = ( isset( $_POST['version'] ) ) ? esc_html( $_POST['version'] ) : '';
	$settings['activate'] = ( ( isset( $_POST['activate'] ) ) ? true : false );
	$settings['slug'] = esc_html( $_POST['slug'] );
	
	$settings['height'] = esc_html( $_POST['height'] );
	$settings['background'] = esc_html( $_POST['background'] );
	$settings['color'] = esc_html( $_POST['color'] );
	$settings['a_color'] = esc_html( $_POST['a_color'] );
	$settings['size'] = esc_html( $_POST['size'] );
	$settings['custom_css'] = ( isset( $_POST['custom_css'] ) ) ? esc_html( $_POST['custom_css'] ) : '';

	/* Update the theme settings. */
	$updated = update_option( 'announcement_bar_settings', $settings );
}
	/**
	 * Save and rewrite the rules if the $slug is changed
	 */
	function announcement_bar_flush_rewrite() {
		flush_rewrite_rules();
	}
	
/**
 * Registers the plugin meta boxes for use on the settings page.
 *
 * @since 0.8
 */
function announcement_bar_create_settings_meta_boxes() {
	global $announcement_bar;

	add_meta_box( 'announcement-bar-activate-meta-box', __( 'Custom Activation &mdash; <em>to infinity and beyond</em>', Announcement_Bar::domain ), 'announcement_bar_activate_meta_box', $announcement_bar->settings_page, 'normal', 'high' );

	add_meta_box( 'announcement-bar-announcement-meta-box', __( 'Announcement!', Announcement_Bar::domain ), 'announcement_bar_announcement_meta_box', $announcement_bar->settings_page, 'normal', 'high' );

	add_meta_box( 'announcement-bar-general-meta-box', __( 'General Settings', Announcement_Bar::domain ), 'announcement_bar_general_meta_box', $announcement_bar->settings_page, 'normal', 'high' );

	add_meta_box( 'announcement-bar-about-meta-box', __( 'About Announcement Bar', Announcement_Bar::domain ), 'announcement_bar_about_meta_box', $announcement_bar->settings_page, 'advanced', 'high' );
	
	add_meta_box( 'announcement-bar-support-meta-box', __( 'Support Announcement Bar', Announcement_Bar::domain ), 'announcement_bar_support_meta_box', $announcement_bar->settings_page, 'advanced', 'high' );
	
	add_meta_box( 'announcement-bar-tabs-meta-box', __( 'TheFrosty Network', Announcement_Bar::domain ), 'announcement_bar_tabs_meta_box', $announcement_bar->settings_page, 'side', 'low' );
}

/**
 * Displays activation meta box.
 *
 * @since 0.8
 */
function announcement_bar_activate_meta_box() { 
	$num_posts = wp_count_posts( ANNOUNCEMENT_BAR_POST_TYPE );
	$num = number_format_i18n( $num_posts->publish );
	?>
	
    <?php if ( $num >= '1' ) { ?>
	<script type="text/javascript">
	jQuery(document).ready(
	function($) { 
		$('#announcement-bar-activate-meta-box').css({'background-color':'#FFEBE8','border-color':'#CC0000'});
		$('h3.hndle em').append('<br /><small><?php _e( 'You can not change this after you publish one announcement post.', Announcement_Bar::domain ); ?></small>');
	});
	</script><?php } ?>
	<table class="form-table">
		<tr>
			<th>
            	<label for="activate"><?php _e( 'Activate:', Announcement_Bar::domain ); ?></label> 
            </th>
            <td>
            	<span id="slide">
                <input id="activate" name="activate" type="checkbox" <?php checked( Announcement_Bar::get_setting( 'activate' ), true ); ?> value="true" />                
				<label for="activate" class="check"></label>
                </span>
                <a class="question" title="Help &amp; Examples">[?]</a><br />
                <div class="hide">Check this box to show or hide the announcement bar.</div>
            </td>
		</tr>
		<tr class="slug">
			<th>
            	<label for="slug"><?php _e( 'Slug:', Announcement_Bar::domain ); ?></label> 
            </th>
            <td>
				<?php echo home_url( '/' ); ?><input id="slug" name="slug" type="input" value="<?php echo Announcement_Bar::get_setting( 'slug' ); ?>" size="21" maxlength="21"<?php if ( $num >= '1' || ( $num >= '1' && Announcement_Bar::get_setting( 'slug' ) != '' ) ) echo ' readonly="readonly"'; ?> />
                <a class="question" title="Help &amp; Examples">[?]</a><br />
                <div class="hide">Input your desired slug here.</div>
            </td>
		</tr>
	</table><!-- .form-table --><?php
}

/**
 * Display an announcement meta box.
 *
 * @since 0.8
 */
function announcement_bar_announcement_meta_box() { ?>

	<iframe allowtransparency="true" src="http://austin.passy.co/custom-login.php" scrolling="no" style="height:50px;width:100%;">
	</iframe><!-- .form-table --><?php
}

/**
 * Displays the about meta box.
 *
 * @since 0.8
 */
function announcement_bar_about_meta_box() {
	$plugin_data = get_plugin_data( ANNOUNCEMENT_BAR_DIR . 'announcement.php' ); ?>

	<table class="form-table">
		<tr>
			<th><?php _e( 'Plugin:', Announcement_Bar::domain ); ?></th>
			<td><?php echo $plugin_data['Title']; ?> <?php echo $plugin_data['Version']; ?></td>
		</tr>
		<tr>
			<th><?php _e( 'Author:', Announcement_Bar::domain ); ?></th>
			<td><?php echo $plugin_data['Author']; ?> &ndash; @<a href="http://twitter.com/TheFrosty" title="Follow me on Twitter">TheFrosty</a></td>
		</tr>
		<tr style="display: none;">
			<th><?php _e( 'Description:', Announcement_Bar::domain ); ?></th>
			<td><?php echo $plugin_data['Description']; ?></td>
		</tr>
	</table><!-- .form-table --><?php
}

/**
 * Displays the support meta box.
 *
 * @since 0.8
 */
function announcement_bar_support_meta_box() { ?>

	<table class="form-table">
        <tr>
            <th><?php _e( 'Donate:', Announcement_Bar::domain ); ?></th>
            <td><?php _e( '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VDD3EDC28RAWS">PayPal</a>.', Announcement_Bar::domain ); ?></td>
        </tr>
        <tr>
            <th><?php _e( 'Rate:', Announcement_Bar::domain ); ?></th>
            <td><?php _e( '<a href="http://wordpress.org/extend/plugins/announcement-bar/">This plugin on WordPress.org</a>.', Announcement_Bar::domain ); ?></td>
        </tr>
        <tr>
            <th><?php _e( 'Share:', Announcement_Bar::domain ); ?></th>
            <td><?php _e( '<a href="http://twitter.com/home?status=Check+out+Announcement+Bar+by+@TheFrosty+for+#WordPress!">On Twitter</a>', Announcement_Bar::domain ); ?></td>
        </tr>
		<tr>
			<th><?php _e( 'Support:', Announcement_Bar::domain ); ?></th>
			<td><?php _e( '<a href="http://wordpress.org/tags/announcement-bar">WordPress support forums</a>.', Announcement_Bar::domain ); ?></td>
		</tr>
	</table><!-- .form-table --><?php
}

/**
 * Displays the general meta box.
 *
 * @since 0.8
 */
function announcement_bar_general_meta_box() { 
	$heights = array( 
		'' => '',
		'28px' => '28px',
		'33px' => '33px',
		'40px' => '40px',
		'50px' => '50px',
		'55px' => '55px',
		'60px' => '60px',
	);
	$sizes = array( 
		'' => '', 
		'12px' => '12px',
		'14px' => '14px',
		'16px' => '16px',

		'18px' => '18px',
		'20px' => '20px',
		'22px' => '22px',
		'24px' => '24px',
		'26px' => '26px',
	);
	?>
	<table class="form-table">
        
        <tr style="display:none">
            <th>
            	<label for="height"><?php _e( 'bar height:', Announcement_Bar::domain ); ?></label> 
            </th>
            <td>
                <input id="height" name="height" value="<?php echo Announcement_Bar::get_setting( 'height' ); ?>" size="10" maxlength="4" />
                <a class="question" title="Help &amp; Examples">[?]</a><br />
                <span class="hide"><?php _e( 'Use two diget code with <code>px</code>', Announcement_Bar::domain ); ?></span>
            </td>
   		</tr>
        
        <tr>
            <th>
            	<label for="height"><?php _e( 'bar height:', Announcement_Bar::domain ); ?></label> 
            </th>
            <td><select name="height" id="height" style="width:88px;">
					<?php foreach ( $heights as $option => $option_name ) { ?>
                        <option value="<?php echo $option; ?>" <?php selected( $option, Announcement_Bar::get_setting( 'height' ) ); ?>><?php echo $option_name; ?></option>
                    <?php } ?>
                </select>
                <a class="question" title="Help &amp; Examples">[?]</a><br />
                <span class="hide"><?php _e( 'Select the bar size.', Announcement_Bar::domain ); ?></span>
            </td>
   		</tr>
        
        <tr>
        
            <th>
            	<label for="background"><?php _e( 'background color:', Announcement_Bar::domain ); ?></label> 
            </th>
            <td>
                <input class="color {hash:true,required:false,adjust:false}" id="background" name="background" value="<?php echo Announcement_Bar::get_setting( 'background' ); ?>" size="10" maxlength="21" />
                <a class="question" title="Help &amp; Examples">[?]</a><br />
                <span class="hide"><?php _e( 'Use HEX color <strong>with</strong> &ldquo;#&rdquo; <strong>or</strong> RGB/A format.<br />
				Example: &sup1;<code>#121212</code> &sup2;<code>rgba(255,255,255,0.4)</code>', Announcement_Bar::domain ); ?>
                </span>
            </td>
   		</tr>
        
        <tr>
            <th>
            	<label for="size"><?php _e( 'font size:', Announcement_Bar::domain ); ?></label> 
            </th>
            <td><select name="size" id="size" style="width:88px;">
					<?php foreach ( $sizes as $option => $option_name ) { ?>
                        <option value="<?php echo $option; ?>" <?php selected( $option, Announcement_Bar::get_setting( 'size' ) ); ?>><?php echo $option_name; ?></option>
                    <?php } ?>
                </select>
                <a class="question" title="Help &amp; Examples">[?]</a><br />
                <span class="hide"><?php _e( 'Select the font size.</span>', Announcement_Bar::domain ); ?>
            </td>
   		</tr>
        
        <tr>
        
            <th>
            	<label for="color"><?php _e( 'text color:', Announcement_Bar::domain ); ?></label> 
            </th>
            <td>
                <input class="color {hash:true,required:false,adjust:false}" id="color" name="color" value="<?php echo Announcement_Bar::get_setting( 'color' ); ?>" size="10" maxlength="21" />
                <a class="question" title="Help &amp; Examples">[?]</a><br />
                <span class="hide"><?php _e( 'Use HEX color <strong>with</strong> &ldquo;#&rdquo; <strong>or</strong> RGB/A format.<br />
				Example: &sup1;<code>#121212</code> &sup2;<code>rgba(255,255,255,0.4)</code>', Announcement_Bar::domain ); ?>
                </span>
            </td>
   		</tr>
        
        <tr>
        
            <th>
            	<label for="a_color"><?php _e( 'Anchor Color:', Announcement_Bar::domain ); ?></label> 
            </th>
            <td>
                <input class="color {hash:true,required:false,adjust:false}" id="a_color" name="a_color" value="<?php echo Announcement_Bar::get_setting( 'a_color' ); ?>" size="10" maxlength="21" />
                <a class="question" title="Help &amp; Examples">[?]</a><br />
                <span class="hide"><?php _e( 'Use HEX color <strong>with</strong> &ldquo;#&rdquo; <strong>or</strong> RGB/A format.<br />
				Example: &sup1;<code>#121212</code> &sup2;<code>rgba(255,255,255,0.4)</code>', Announcement_Bar::domain ); ?>
                </span>
            </td>
   		</tr>
        
        <tr>
			<th>
            	<label for="custom_css"><?php _e( 'Custom CSS:', Announcement_Bar::domain ); ?></label> 
            </th>
            <td>             
                <textarea id="custom_css" name="custom_css" cols="50" rows="3" class="large-text code"><?php echo wp_specialchars_decode( stripslashes( Announcement_Bar::get_setting( 'custom_css' ) ), 1, 0, 1 ); ?></textarea>
                <a class="question" title="Help &amp; Examples">[?]</a><br />
                <span class="hide"><?php _e( 'Use this box to enter any custom CSS code that may not be shown above.<br />
                <strong>Some CSS attributes:</strong> <code>#announcementbar-container</code>, <code>#announcementbar</code>, <code>.announcement</code>, <code>.toggle a</code>, ', Announcement_Bar::domain ); ?>
                </span>
            </td>
   		</tr>
        
	</table><!-- .form-table --><?php
}

/**
 * Displays the support meta box.
 *
 * @since 0.8
 */
function announcement_bar_tabs_meta_box() { ?>
	<table class="form-table">
        <div id="tab" class="tabbed inside">
    	
        <ul class="tabs">        
            <li class="t1 t"><a class="t1 tab"><?php _e( 'Austin Passy', Announcement_Bar::domain ); ?></a></li>
            <li class="t2 t"><a class="t2 tab"><?php _e( 'Frosty Media', Announcement_Bar::domain ); ?></a></li>
            <li class="t3 t"><a class="t3 tab"><?php _e( 'Frosty Media Plugins', Announcement_Bar::domain ); ?></a></li>             
        </ul>
        
		<?php 
		if ( function_exists( 'thefrosty_network_feed' ) ) {
        	thefrosty_network_feed( 'http://feeds.feedburner.com/AustinPassy', '1' );
       		thefrosty_network_feed( 'http://frosty.media/feed', '2' );
       		thefrosty_network_feed( 'http://frosty.media/feed?post_type=plugin', '3' );
		} ?>
        
    	</div>
	</table><!-- .form-table --><?php
}

/**
 * Displays a settings saved message.
 *
 * @since 0.8
 */
function announcement_bar_settings_update_message() { ?>
	<div class="updated fade">
		<p><strong><?php _e( 'Don&prime;t you feel good. You just saved me.', Announcement_Bar::domain ); ?></strong></p>
	</div><?php
}

/**
 * Outputs the HTML and calls the meta boxes for the settings page.
 *
 * @since 0.8
 */
function announcement_bar_settings_page() {
	global $announcement_bar;

	$plugin_data = get_plugin_data( ANNOUNCEMENT_BAR_DIR . 'announcement.php' ); ?>

	<div class="wrap">
		
        <?php if ( function_exists( 'screen_icon' ) ) screen_icon(); ?>
        
		<h2><?php _e( 'Announcement Bar Settings', Announcement_Bar::domain ); ?></h2>

		<?php if ( isset( $_GET['updated'] ) && 'true' == esc_attr( $_GET['updated'] ) ) Announcement_Bar_settings_update_message(); ?>

		<div id="poststuff">

			<form method="post" action="<?php admin_url( 'options-general.php?page=announcement-bar' ); ?>">

				<?php wp_nonce_field( 'announcement-bar-settings-page' ); ?>
				<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
				<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>

				<div class="metabox-holder">
					<div class="post-box-container column-1 normal"><?php do_meta_boxes( $announcement_bar->settings_page, 'normal', $plugin_data ); ?></div>
					<div class="post-box-container column-2 advanced"><?php do_meta_boxes( $announcement_bar->settings_page, 'advanced', $plugin_data ); ?></div>
					<div class="post-box-container column-3 side" style="clear:both;"><?php do_meta_boxes( $announcement_bar->settings_page, 'side', $plugin_data ); ?></div>
				</div>

				<p class="submit" style="clear: both;">
					<input type="submit" name="Submit"  class="button-primary" value="<?php _e( 'Update Settings', Announcement_Bar::domain ); ?>" />
					<input type="hidden" name="version" id="version" value="<?php echo Announcement_Bar::get_setting( 'version' ); ?>" />
					<input type="hidden" name="announcement-bar-settings-submit" value="true" />
				</p><!-- .submit -->

			</form>

		</div><!-- #poststuff -->

	</div><!-- .wrap --><?php
}

/**
 * Loads the scripts needed for the settings page.
 *
 * @since 0.8
 */
function announcement_bar_settings_page_enqueue_script() {	
	wp_enqueue_script( 'common' );
	wp_enqueue_script( 'wp-lists' );
	wp_enqueue_script( 'postbox' );
	wp_enqueue_script( Announcement_Bar::domain . '-admin' );
	wp_enqueue_script( 'jscolor' );
}

/**
 * Loads the stylesheets needed for the settings page.
 *
 * @since 0.8
 */
function announcement_bar_settings_page_enqueue_style() {
	wp_enqueue_style( Announcement_Bar::domain . '-tabs' );
	wp_enqueue_style( Announcement_Bar::domain . '-admin' );
}

/**
 * Loads the metabox toggle JavaScript in the settings page head.
 *
 * @since 0.8
 */
function announcement_bar_settings_page_load_scripts() {
	global $announcement_bar; ?>
<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready( function($) {
		$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
		postboxes.add_postbox_toggles( '<?php echo $announcement_bar->settings_page; ?>' );
	});
	//]]>
</script><?php
}

function announcement_bar_admin_css_fix() { ?>
<style type="text/css">
#menu-posts-announcement .wp-menu-image a {overflow:hidden}
#menu-posts-announcement .wp-menu-image img {position: relative;top:-24px}
#menu-posts-announcement.wp-has-current-submenu .wp-menu-image img {top:0}
</style>
<?php }

/**
 * Plugin Action /Settings on plugins page
 * @since 0.4.2
 * @package plugin
 */
function announcement_bar_plugin_actions( $links, $file ) {
 	if( $file == 'announcement-bar/announcement.php' && function_exists( "admin_url" ) ) {
		$settings_link = '<a href="' . admin_url( 'options-general.php?page=announcement-bar' ) . '">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link ); // before other links
	}
	return $links;
}

/**
 * Warnings
 * @since 0.5
 * @package admin
 */
function announcement_bar_admin_warnings() {
	global $announcement_bar;
		
		function announcement_bar_warning_slug() {
			global $announcement_bar;
			$num_posts = wp_count_posts( 'AnnouncementBar' );
			$num = isset( $num_posts->publish ) ? number_format_i18n( $num_posts->publish ) : '0';
			$slug = Announcement_Bar::get_setting( 'slug' );

			if ( Announcement_Bar::get_setting( 'notice' ) == true ) { ?>
                <div id="announcement-bar-warning" class="updated">
                    <p><?php _e( sprintf( 'You can set up a custom slug for <strong>Announcement Bar</strong>, just visit the %1$s page before adding a post to the %2$s.<br />Current slug set as <code>%3$s</code> %4$s', '<a href="' . admin_url( 'options-general.php?page=announcement-bar' ) . '">options</a>', '<a href="' . admin_url( 'edit.php?post_type=' . ANNOUNCEMENT_BAR_POST_TYPE  ) . '">announcement bar</a>', home_url( '/' ).'<big><strong>'.$slug.'</strong></big>/', '<a href="' . admin_url( 'options-general.php?page=announcement-bar&notice=false&_wpnonce=' . wp_create_nonce( 'announcement-bar-notice' ) ) . '" class="right alignright">hide</a>' ), Announcement_Bar::domain ); ?>
                    </p>
                </div><?php 
			}
		}
		
		function announcement_bar_warning_version() {
			global $announcement_bar, $wp_version; ?>
                <div id="announcement-bar-warning" class="error">
                    <p><?php _e( sprintf( '<strong>Announcement Bar</strong>, will inly work with WordPress 3.0.x and greater. You\'ve got version %1$s', $wp_version ), Announcement_Bar::domain ); ?>
                    </p>
                </div><?php
		}
		
		if ( Announcement_Bar::is_version() )
			add_action( 'admin_notices', 'announcement_bar_warning_slug' );
		else
			add_action( 'admin_notices', 'announcement_bar_warning_version' );

	return;
}

/**
 * RSS Feed
 * @since 0.3
 * @package Admin
 */
if ( !function_exists( 'thefrosty_network_feed' ) ) {
	function thefrosty_network_feed( $attr, $count ) {		
		global $wpdb;
		
		$domain = preg_replace( '|https?://([^/]+)|', '$1', get_option( 'siteurl' ) );
			
		include_once( ABSPATH . WPINC . '/class-simplepie.php' );
		$feed = new SimplePie();
		
		$feed->set_feed_url( $attr );
		
		if ( false !== strpos( $domain, '/' ) || 'localhost' == $domain || preg_match( '|[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+|', $domain ) ) {
			$feed->enable_cache( false );
		} else {
			$feed->enable_cache( true );
			$feed->set_cache_location( plugin_dir_path( __FILE__ ) . 'cache' );
		}
			
		$feed->init();
		$feed->handle_content_type();
		$items = $feed->get_item();
		echo '<div class="t' . $count . ' tab-content postbox open feed">';		
		echo '<ul>';		
		if ( empty( $items ) ) { 
			echo '<li>No items</li>';		
		} else {
			foreach( $feed->get_items( 0, 3 ) as $item ) : ?>		
				<li>		
					<a href='<?php echo $item->get_permalink(); ?>' title='<?php echo $item->get_description(); ?>'><?php echo $item->get_title(); ?></a><br /> 		
					<span style="font-size:10px; color:#aaa;"><?php echo $item->get_date('F, jS Y | g:i a'); ?></span>		
				</li>		
			<?php endforeach;
		}
		echo '</ul>';		
		echo '</div>';
	}
}

?>