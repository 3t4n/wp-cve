<?php
/*
Plugin Name: Custom Login Logo
description: Change the default WordPress logo by uploading your site logo for the login page.
Version: 1.1.2
Author: Hakik Zaman
Author URI: https://github.com/hakikz
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/**
*
* Exit if accessed directly
*
**/
if (!defined('ABSPATH')) {
    exit;
}



/*Defining Constant*/
define("IWLLC_VERSION", '1.1.2');

/*Add body class for options page*/
function iwllc_admin_body_class( $classes ) {
	global $pagenow;

	if ( in_array( $pagenow, array( 'options-general.php' ), true ) &&  $_GET['page'] == 'change_login_logo' ) {
		$classes .= ' idllc-option-page';
	}

	return $classes;
}

add_filter( 'admin_body_class', 'iwllc_admin_body_class' );

/*Adding Styles for the option page*/
function iwllc_styles_option_page(){
	global $pagenow;

	if ( in_array( $pagenow, array( 'options-general.php' ), true ) &&  $_GET['page'] == 'change_login_logo' ) {
		?>
			<style type="text/css">
				.idllc-option-page table.form-table tbody {
				    background-color: #fff;
				}

				.idllc-option-page table.form-table tbody tr:not(:last-child) {
				    border-bottom: 1px solid #eee;
				}

				.idllc-option-page table.form-table tbody th {
				    padding: 15px 10px;
				}

			</style>
		<?php
	}
}
add_action( 'admin_head', 'iwllc_styles_option_page' );


/* Settings to manage WP login logo */
function iwllc_register_custom_logo_settings() 
{
   register_setting( 'iwllc_change_login_options_group', 'iwllc_wp_logo_url');
   register_setting( 'iwllc_change_login_options_group', 'iwllc_wp_set_bg');
   register_setting( 'iwllc_change_login_options_group', 'iwllc_wp_bg_color');
   register_setting( 'iwllc_change_login_options_group', 'iwllc_wp_bg_img_url');
   register_setting( 'iwllc_change_login_options_group', 'iwllc_wp_logo_link');
   register_setting( 'iwllc_change_login_options_group', 'iwllc_wp_logo_width');
   register_setting( 'iwllc_change_login_options_group', 'iwllc_wp_logo_height');
}
add_action( 'admin_init', 'iwllc_register_custom_logo_settings' );


function iwllc_register_login_logo_setting_page() {
  add_options_page('Custom Login Logo', 'Custom Login Logo', 'manage_options', 'change_login_logo', 'iwllc_change_wordpress_login_logo');
}
add_action('admin_menu', 'iwllc_register_login_logo_setting_page');

function iwllc_change_wordpress_login_logo()
{
	wp_enqueue_script('jquery');
	wp_enqueue_media();

	$cur_logo = esc_attr( get_option('iwllc_wp_logo_url', '') );
	$cur_bg_img = esc_attr( get_option('iwllc_wp_bg_img_url', '') );

	?>
	<div class="wrap">
		<h1><?php echo __( 'Custom Login Logo Settings' ); ?></h1>
		<p><?php echo __( 'Change the the default WordPress logo and set your own site logo.' ); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'iwllc_change_login_options_group' ); ?>
			<?php do_settings_sections( 'iwllc_change_login_options_group' ); ?>
			<table class="form-table">

				<!-- Logo Section -->
				<?php if( $cur_logo !== "" ): ?>
				<tr valign="top">
					<th>Current Logo</th>
					<td>
						<img src="<?php echo esc_attr( get_option('iwllc_wp_logo_url') ); ?>" alt="Current Logo" width="220">
					</td>
				</tr>
				<?php endif; ?>
				
				<tr valign="top">
					<th scope="row"><?php echo __( 'Set Logo' ); ?> </th>
					<td>
						<input type="text" id="iwllc_wp_logo_url" name="iwllc_wp_logo_url" value="<?php echo esc_attr( get_option('iwllc_wp_logo_url') ); ?>" />
						<input type="button" name="iwllc-upload-btn" id="iwllc-upload-btn" class="button-secondary" value="Upload Logo">
						<p class="description"><i><?php echo __( 'This Image will be displayed in Login Page' ) ?></i></p>
					</td>
				</tr>

				<!-- Choose Background Type Section -->
				<tr valign="top">
					<th scope="row"><?php echo __( 'Set Background Type' ); ?></th>
					<td>
						<select class="iwllc_wp_bg_select" name="iwllc_wp_set_bg">
							<option value="color" <?php echo ( empty( get_option('iwllc_wp_set_bg') ) || get_option('iwllc_wp_set_bg') === 'color' ) ? 'selected' : ''  ?> >Color</option>
							<option value="image" <?php echo ( get_option('iwllc_wp_set_bg') === 'image' ) ? 'selected' : ''  ?>>Image</option>
						</select>
						<p class="description"><i><?php echo __( 'Default type is `Color`' ) ?></i></p>
					</td>
				</tr>

				<!-- Background Color Section -->
				<tr class="type_color" valign="top">
					<th scope="row"><?php echo __( 'Set Background Color' ); ?> </th>
					<td>
						<input type="text" class="iwllc_wp_bg_color" id="iwllc_wp_bg_color" name="iwllc_wp_bg_color" value="<?php echo esc_attr( get_option('iwllc_wp_bg_color', '#f0f0f1') ); ?>" data-default-color="#f0f0f1" />
						<p class="description"><i><?php echo __( 'Set your desired color, to change the login page background color' ) ?></i></p>
					</td>
				</tr>


				<!-- Background Image Section -->
				<?php if( $cur_bg_img !== "" ): ?>
				<tr class="type_image" valign="top">
					<th>Current Background Image</th>
					<td>
						<img src="<?php echo esc_attr( get_option('iwllc_wp_bg_img_url') ); ?>" alt="Current Background Image" width="220">
					</td>
				</tr>
				<?php endif; ?>


				<tr class="type_image" valign="top">
					<th scope="row"><?php echo __( 'Set Background Image' ); ?> </th>
					<td>
						<input type="text" id="iwllc_wp_bg_img_url" name="iwllc_wp_bg_img_url" value="<?php echo esc_attr( get_option('iwllc_wp_bg_img_url') ); ?>" />
						<input type="button" name="iwllc-upload-btn" id="iwllc-upload-btn" class="button-secondary" value="Upload Logo">
						<p class="description"><i><?php echo __( 'This Image will be displayed as background image of Login Page' ) ?></i></p>
					</td>
				</tr>

				<!-- Logo Custom Link Section -->
				<tr valign="top">
					<th scope="row"><?php echo __( 'Set Your Logo Link' ); ?> </th>
					<td>
						<input type="url" id="iwllc_wp_logo_link" name="iwllc_wp_logo_link" value="<?php echo esc_attr( get_option('iwllc_wp_logo_link') ); ?>" />
						<p class="description"><i><?php echo __( 'Set your desired link, to redirect after clicking on your logo' ) ?></i></p>
					</td>
				</tr>

				<!-- Logo Width Settings Section -->
				<tr valign="top">
					<th scope="row"><?php echo __( 'Width' ); ?></th>
					<td>
						<input type="text" name="iwllc_wp_logo_width" value="<?php echo esc_attr( get_option('iwllc_wp_logo_width') ); ?>" />
						<p class="description"><i><?php echo __( 'Default width is 100%' ) ?></i></p>
					</td>
				</tr>

				<!-- Logo Height Settings Section -->
				<tr valign="top">
					<th scope="row"><?php echo __( 'Height' ); ?></th>
					<td>
						<input type="text" name="iwllc_wp_logo_height" value="<?php echo esc_attr( get_option('iwllc_wp_logo_height') ); ?>" />					
						<p class="description"><i><?php echo __( 'Default height is 100px' ) ?></i></p>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/* Adding Backend Scripts */

function iwllc_backend_scripts(){
	$screen = get_current_screen(); 
	if ($screen->id === 'settings_page_change_login_logo') {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker');
		wp_enqueue_script( 'iwllc-backend', plugins_url( '/admin/backend.js', __FILE__ ), array('jquery'), IWLLC_VERSION, 'true' );
		$values = array(
			'bg_type' => get_option('iwllc_wp_set_bg', 'color')
		);
		wp_localize_script( 'iwllc-backend', 'iwllc_admin', $values );
	}
}
add_action('admin_enqueue_scripts', 'iwllc_backend_scripts');



/* Custom WordPress admin login header logo */
function iwllc_wordpress_custom_login_logo() {
    $logo_url=get_option('iwllc_wp_logo_url');

    $bg_type = get_option('iwllc_wp_set_bg', 'color');

    $bg_color=get_option('iwllc_wp_bg_color', '#f0f0f1');

    $bg_img_url=get_option('iwllc_wp_bg_img_url');

    $iwllc_wp_logo_height=get_option('iwllc_wp_logo_height');

    $iwllc_wp_logo_width=get_option('iwllc_wp_logo_width');

	if(empty($iwllc_wp_logo_height))
	{
		$iwllc_wp_logo_height='100px';
	}
	else
	{
		$iwllc_wp_logo_height.='px';
	}
	if(empty($iwllc_wp_logo_width))
	{
		$iwllc_wp_logo_width='100%';
	}	
	else
	{
		$iwllc_wp_logo_width.='px';
	}

	$style = '<style type="text/css">';
		if(!empty($logo_url)){
			$style .=    'h1 a { 
					background-image:url('.$logo_url.') !important;
					height:'.$iwllc_wp_logo_height.' !important;
					width:'.$iwllc_wp_logo_width.' !important;
					background-size:100% !important;
					line-height:inherit !important;
					}';
		}

		if(!empty($bg_img_url) && $bg_type === "image"){
			$style .=    'body.login.login-action-login{
					background-image:url('.$bg_img_url.') !important;
					background-repeat: no-repeat;
					background-size: 100%;
					background-position: center center;
				}';
		}


		$style .= ' body.login.login-action-login{

				background-color: '.$bg_color.';

			}';

	$style .= '</style>';

    echo $style;
}
add_action( 'login_head', 'iwllc_wordpress_custom_login_logo' );

/* Add action links to plugin list*/
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'iwllc_add_change_wordpress_login_logo_action_links' );
function iwllc_add_change_wordpress_login_logo_action_links ( $links ) {
	$settings_link = array(
		 '<a href="' . admin_url( 'options-general.php?page=change_login_logo' ) . '">Logo Settings</a>'
	);
	return array_merge( $links, $settings_link );
}

/* changing the logo link from wordpress.org to the site */
function iwllc_login_url() {  

	$link = esc_attr( get_option('iwllc_wp_logo_link') );

	return $link ? $link : home_url(); 
}
add_filter( 'login_headerurl', 'iwllc_login_url' );

?>