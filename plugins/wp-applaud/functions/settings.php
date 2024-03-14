<?php

class wpApplaudSettings {
	function __construct() 
    {	
    	add_action('admin_init', array(&$this, 'admin_init'));
        add_action('admin_menu', array(&$this, 'admin_menu'), 99);
	}

	function admin_init()
	{
		register_setting( 'wp-applaud', 'wp_applaud_settings', array(&$this, 'settings_validate') );
		add_settings_section( 'wp-applaud', '', array(&$this, 'section_intro'), 'wp-applaud' );

		add_settings_field( 'show_on', __( 'Automatically show likes on', 'wpapplaud' ), array(&$this, 'setting_show_on'), 'wp-applaud', 'wp-applaud' );
		add_settings_field( 'exclude_from', __( 'Exclude from Post/Page ID', 'wpapplaud' ), array(&$this, 'setting_exclude_from'), 'wp-applaud', 'wp-applaud' );
		add_settings_field( 'disable_css', __( 'Disable CSS', 'wpapplaud' ), array(&$this, 'setting_disable_css'), 'wp-applaud', 'wp-applaud' );
		add_settings_field( 'ajax_likes', __('AJAX Like Counts', 'wpapplaud'), array(&$this, 'setting_ajax_likes'), 'wp-applaud', 'wp-applaud');
		add_settings_field( 'single_user_likes', __('Single User can like', 'wpapplaud'), array(&$this, 'setting_single_user_likes'), 'wp-applaud', 'wp-applaud');
		add_settings_field( 'zero_title', __( '0 Count title', 'wpapplaud' ), array(&$this, 'setting_zero_title'), 'wp-applaud', 'wp-applaud' );
		add_settings_field( 'one_title', __( '1 Count title', 'wpapplaud' ), array(&$this, 'setting_one_title'), 'wp-applaud', 'wp-applaud' );
		add_settings_field( 'more_title', __( 'More than 1 Count title', 'wpapplaud' ), array(&$this, 'setting_more_title'), 'wp-applaud', 'wp-applaud' );
		add_settings_field( 'instructions', __( 'Shortcode and Template Tag', 'wpapplaud' ), array(&$this, 'setting_instructions'), 'wp-applaud', 'wp-applaud' );
	}
	
	function admin_menu() 
	{
		$icon_url = plugins_url( 'assets/images/wp-applaud.png', dirname(__FILE__) );
		$page_hook = add_menu_page( __( 'WPApplaud Settings', 'wpapplaud'), 'WPApplaud', 'update_core', 'wp-applaud', array(&$this, 'settings_page'), $icon_url );
		add_submenu_page( 'wp-applaud', __( 'Settings', 'wpapplaud' ), __( 'WPApplaud Settings', 'wpapplaud' ), 'update_core', 'wp-applaud', array(&$this, 'settings_page') );
	}
	
	function settings_page()
	{
		?>
		<div class="wrap">
			<div id="icon-themes" class="icon32"></div>
			<h2><?php _e('WPApplaud Settings', 'wpapplaud'); ?></h2>
			<?php if( isset($_GET['settings-updated']) && $_GET['settings-updated'] ){ ?>
			<div id="setting-error-settings_updated" class="updated settings-error"> 
				<p><strong><?php _e( 'Settings saved.', 'wpapplaud' ); ?></strong></p>
			</div>
			<?php } ?>
			<form action="options.php" method="post">
				<?php settings_fields( 'wp-applaud' ); ?>
				<?php do_settings_sections( 'wp-applaud' ); ?>
				<p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'wpapplaud' ); ?>" /></p>
			</form>
		</div>
		<?php
	}
	
	function section_intro()
	{
	    ?>
		<!-- <p>In case you need help, please reach out community on:</p>
		<ul>
			<li> <a href="">github</a> </li>
			<li> <a href="">website</a> </li>
		</ul> -->
		<?php
		
	}

	function setting_show_on()
	{
		$options = get_option( 'wp_applaud_settings' );
		if( !isset($options['add_to_posts']) ) $options['add_to_posts'] = '0';
		if( !isset($options['add_to_pages']) ) $options['add_to_pages'] = '0';
		if( !isset($options['add_to_other']) ) $options['add_to_other'] = '0';
		
		echo '<input type="hidden" name="wp_applaud_settings[add_to_posts]" value="0" />
		<label><input type="checkbox" name="wp_applaud_settings[add_to_posts]" value="1"'. (($options['add_to_posts']) ? ' checked="checked"' : '') .' />
		'. __('Posts', 'wpapplaud') .'</label><br />
		<input type="hidden" name="wp_applaud_settings[add_to_pages]" value="0" />
		<label><input type="checkbox" name="wp_applaud_settings[add_to_pages]" value="1"'. (($options['add_to_pages']) ? ' checked="checked"' : '') .' />
		'. __('Pages', 'wpapplaud') .'</label><br />
		<input type="hidden" name="wp_applaud_settings[add_to_other]" value="0" />
		<label><input type="checkbox" name="wp_applaud_settings[add_to_other]" value="1"'. (($options['add_to_other']) ? ' checked="checked"' : '') .' />
		'. __('Blog Index Page, Archive Pages, and Search Results', 'wpapplaud') .'</label><br />';
	}
	
	function setting_exclude_from()
	{
		$options = get_option( 'wp_applaud_settings' );
		if( !isset($options['exclude_from']) ) $options['exclude_from'] = '';
		
		echo '<input type="text" name="wp_applaud_settings[exclude_from]" class="regular-text" value="'. $options['exclude_from'] .'" />
		<p class="description">'. __('Comma separated list of post/page ID\'s (e.g. 4,7,87)', 'wpapplaud') . '</p>';
	}
	
	function setting_disable_css()
	{
		$options = get_option( 'wp_applaud_settings' );
		if( !isset($options['disable_css']) ) $options['disable_css'] = '0';
		
		echo '<input type="hidden" name="wp_applaud_settings[disable_css]" value="0" />
		<label><input type="checkbox" name="wp_applaud_settings[disable_css]" value="1"'. (($options['disable_css']) ? ' checked="checked"' : '') .' />' . __('I want to use my own CSS styles', 'wpapplaud') . '</label>';
		
		// Shutterbug conflict warning
		$theme_name = '';
		if(function_exists('wp_get_theme')) $theme_name = wp_get_theme();
		else $theme_name = get_current_theme();
		if(strtolower($theme_name) == 'shutterbug'){
    		echo '<br /><span class="description" style="color:red">'. __('We recommend you check this option when using the Shutterbug theme to avoid conflicts', 'wpapplaud') .'</span>';
		}
	}
	
	function setting_ajax_likes()
	{
	    $options = get_option( 'wp_applaud_settings' );
	    if( !isset($options['ajax_likes']) ) $options['ajax_likes'] = '0';
	    
	    echo '<input type="hidden" name="wp_applaud_settings[ajax_likes]" value="0" />
		<label><input type="checkbox" name="wp_applaud_settings[ajax_likes]" value="1"'. (($options['ajax_likes']) ? ' checked="checked"' : '') .' />
		' . __('AJAX Like Counts on page load', 'wpapplaud') . '</label><br />
		<span class="description">'. __('If you are using a cacheing plugin, you may want to dynamically load the like counts via AJAX.', 'wpapplaud') .'</span>';
	}
	
	function setting_single_user_likes()
	{
	    $options = get_option( 'wp_applaud_settings' );
	    if( !isset($options['single_user_likes']) ) $options['single_user_likes'] = '0';
	    
	    echo '<input type="number" name="wp_applaud_settings[single_user_likes]" value="'.$options['single_user_likes'].'" />
		<label>' . __('Number of likes single user can give', 'wpapplaud') . '</label><br />';
	}

	function setting_zero_title()
	{
		$options = get_option( 'wp_applaud_settings' );
		if( !isset($options['zero_title']) ) $options['zero_title'] = '';
		
		echo '<input type="text" name="wp_applaud_settings[zero_title]" class="regular-text" value="'. $options['zero_title'] .'" /><br />
		<span class="description">'. __('The title on hover when no one has liked a post/page. Leave blank for default text.', 'wpapplaud') .'</span>';
	}
	
	function setting_one_title()
	{
		$options = get_option( 'wp_applaud_settings' );
		if( !isset($options['one_title']) ) $options['one_title'] = '';
		
		echo '<input type="text" name="wp_applaud_settings[one_title]" class="regular-text" value="'. $options['one_title'] .'" /><br />
		<span class="description">'. __('The title on hover when one like on a post/page. Leave blank for default text.', 'wpapplaud') .'</span>';
	}
	
	function setting_more_title()
	{
		$options = get_option( 'wp_applaud_settings' );
		if( !isset($options['more_title']) ) $options['more_title'] = '';
		
		echo '<input type="text" name="wp_applaud_settings[more_title]" class="regular-text" value="'. $options['more_title'] .'" /><br />
		<span class="description">'. __('The title on hover when more than one like on a post/page. Leave blank for default text.', 'wpapplaud') .'</span>';
	}
	
	function setting_instructions()
	{
		echo '<p>'. __('To use WP Applaud in your posts and pages you can use the shortcode:', 'wpapplaud') .'</p>
		<p><code>[wp_applaud]</code></p>
		<p>'. __('To use WP Applaud manually in your theme template use the following PHP code:', 'wpapplaud') .'</p>
		<p><code>&lt;?php if( function_exists(\'wp_applaud\') ) wp_applaud(); ?&gt;</code></p>';
	}
	
	function settings_validate($input)
	{
	    $input['exclude_from'] = str_replace(' ', '', trim(strip_tags($input['exclude_from'])));
		
		return $input;
	}
}

?>