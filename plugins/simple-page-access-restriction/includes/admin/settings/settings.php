<?php
/**
 * Settings Page
 *
 * @package     Simple_Page_Access_Restriction\Settings
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;

if ( isset( $_POST['ps_simple_par_save_settings'] ) ) {
	// Define the input settings
	$input_settings = array();

	// Check if the input settings does exist
	if ( isset( $_POST['ps_simple_par_'] ) && is_array( $_POST['ps_simple_par_'] ) ) {
		// Get the input settings
		$input_settings = map_deep( $_POST['ps_simple_par_'], 'sanitize_text_field' );
	}

	// Get the setting login page
	$setting_login_page = isset( $input_settings['login_page'] ) ? $input_settings['login_page'] : '';
	$setting_login_page = filter_var( $setting_login_page, FILTER_VALIDATE_INT );

	// Get the setting remove_data
	$setting_remove_data = isset( $input_settings['remove_data'] ) ? $input_settings['remove_data'] : '';
	$setting_remove_data = filter_var( $setting_remove_data, FILTER_VALIDATE_INT );

	// Get the setting remove_data
	$setting_restrict_new_posts = isset( $input_settings['restrict_new_posts'] ) ? $input_settings['restrict_new_posts'] : '';
	$setting_restrict_new_posts = filter_var( $setting_restrict_new_posts, FILTER_VALIDATE_INT );

	$selected_post_types = array();
	if ( isset( $input_settings['post_types'] ) && is_array( $input_settings['post_types'] ) ) {
		$selected_post_types = $input_settings['post_types'];
	}

	/*
	Users can mistakenly Select a Login Protected Page
	which can cause an Redirect Loop, Following Check will
	prevent selection of such page
	*/

	if ( ps_simple_par_is_page_restricted( $setting_login_page ) ) {
		$message = __( 'The page you selected is itself login protected. Please select a page which is unrestricted for guest visitors.', 'simple-page-access-restriction' );

		// Set the login page
		$setting_login_page = '';
	} else {
		$message = __( 'Settings have been successfully updated.', 'simple-page-access-restriction' );
	}

	// Build the sanitized settings
	$sanitized_settings = array(
		'login_page'         => $setting_login_page,
		'remove_data'        => $setting_remove_data,
		'restrict_new_posts' => $setting_restrict_new_posts,
		'post_types'         => $selected_post_types,
		'redirect_type'      => $input_settings['redirect_type'],
		'redirect_parameter' => $input_settings['redirect_parameter'],
		'redirect_url'       => $input_settings['redirect_url'],
	);

	update_option( 'ps_simple_par_settings', $sanitized_settings, false );

	ps_simple_par_show_message( $message );
}

$settings = ps_simple_par_get_settings();
$pages    = $wpdb->get_results( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type='page' AND post_status='publish'" );
?>
<div class="wrap">
	<h1><?php _e( 'Simple Page Access Restriction', 'simple-page-access-restriction' ); ?></h1>
	<?php do_action( 'ps_simple_par_after_settings_title' ); ?>
	<form method="post" action="">
		<div id="ps_plugin_template_settings_tabs">
			<div id="simple-par-settings-tabs-header">
				<a href="#ps_simple_par_settings_tab_1" class="simple-par-tab-active"><?php _e( 'Settings', 'simple-page-access-restriction' ); ?></a>
			</div>
			
			<div id="ps_simple_par_settings_tab_1" class="simple-par-tab-content simple-par-tab-active">
				
				<h2 style="margin:0;"><?php _e( 'Settings', 'simple-page-access-restriction' ); ?></h2>
				<hr />
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row">
								<strong><?php esc_html_e( 'Login Redirect Type', 'simple-page-access-restriction' ); ?></strong>
							</th>
							<td class="simple-par-redirect-type-choices">
								<label>
									<input type="radio" name="ps_simple_par_[redirect_type]" value="page" <?php echo ( 'page' === $settings['redirect_type'] ? 'checked' : '' ); ?>>
									<span><?php esc_html_e( 'Page', 'simple-page-access-restriction' ); ?></span>
								</label><br>
								<label>
									<input type="radio" name="ps_simple_par_[redirect_type]" value="url" <?php echo ( 'url' === $settings['redirect_type'] ? 'checked' : '' ); ?>>
									<span><?php esc_html_e( 'URL', 'simple-page-access-restriction' ); ?></span>
								</label>
							</td>
						</tr>

						<tr valign="top" data-simple-par-redirect-type="page" class="<?php echo ( 'page' !== $settings['redirect_type'] ? 'ps_simple_par_setting_hidden' : '' ); ?>">
							<th scope="row">
								<label for="selectbox"><?php _e( 'Login Redirect Page', 'simple-page-access-restriction' ); ?></label>
							</th>
							<td>
								<select id="selectbox" name="ps_simple_par_[login_page]" class="regular-text">
									<option value=""><?php _e( 'Select Page', 'simple-page-access-restriction' ); ?></option>
									<?php foreach ( $pages as $page ): ?>
										<option value="<?php esc_attr_e( $page->ID ); ?>" <?php selected( $settings['login_page'], $page->ID ); ?>><?php esc_html_e( $page->post_title ); ?></option>
									<?php endforeach; ?>
								</select>
								<p class="description"><?php
									_e( 'This is the page where guest users will be redirected to. Please note that by selecting this page the checkbox "For Logged-in Users only" will be automatically disabled.', 'simple-page-access-restriction' );
									
									if ( ! empty( $settings['login_page'] ) ) {
										$edit_url = admin_url( 'post.php?post=' . $settings['login_page'] . '&action=edit' );
										
										echo '<br>';
										_e( 'For more information, please refer to the page\'s settings.', 'simple-page-access-restriction' );
										echo '<br>';
										echo '<a href="' . $edit_url . '">' . $edit_url . '</a>';
									}
								?></p>
							</td>
						</tr>

						<tr valign="top" data-simple-par-redirect-type="url" class="<?php echo ( 'page' === $settings['redirect_type'] ? 'ps_simple_par_setting_hidden' : '' ); ?>">
							<th scope="row">
								<label for="simple-par-url-field"><?php esc_html_e( 'Login Redirect URL', 'simple-page-access-restriction' ); ?></label>
							</th>
							<td>
								<input type="text" id="simple-par-url-field" name="ps_simple_par_[redirect_url]" value="<?php echo esc_attr( $settings['redirect_url'] ); ?>" />
								<p class="description"><?php esc_html_e( 'This is the URL where guest users will be redirected to.', 'simple-page-access-restriction' ); ?></p>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<label for="simple-par-redirct-parameter-field"><?php esc_html_e( 'Redirect Parameter', 'simple-page-access-restriction' ); ?></label>
							</th>
							<td>
								<input type="text" id="simple-par-redirct-parameter-field" name="ps_simple_par_[redirect_parameter]" value="<?php echo esc_attr( $settings['redirect_parameter'] ); ?>" />
								<p class="description"><?php esc_html_e( 'Redirect page will be appended with this parameter having the value of page from where the redirection happened. Typically, this value is redirect_to', 'simple-page-access-restriction' ); ?></p>
								<p class="description"><em><?php esc_html_e( 'To disable, just leave it empty', 'simple-page-access-restriction' ); ?></em></p>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<label for="restrict_new_posts"><?php _e( 'New Posts/Pages', 'simple-page-access-restriction', 'simple-page-access-restriction' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="restrict_new_posts" name="ps_simple_par_[restrict_new_posts]" value="1" <?php checked( $settings['restrict_new_posts'], '1' ); ?> />
								<p class="description"><?php _e( 'For Logged-In Users Only (New Posts/Pages will be created by using this setting)', 'simple-page-access-restriction'); ?></p>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<label for="remove_data"><?php _e( 'Remove Plugin Data on Uninstall', 'simple-page-access-restriction', 'simple-page-access-restriction' ); ?></label>
							</th>
							<td>
								<input type="checkbox" id="remove_data" name="ps_simple_par_[remove_data]" value="1" <?php checked( $settings['remove_data'], '1' ); ?> />
								<p class="description"><?php _e( 'If checked then on plugin uninstallation plugin data will be removed from database.', 'simple-page-access-restriction'); ?></p>
							</td>
						</tr>

						<tr valign="top">
							<th>
								<strong><?php esc_html_e( 'Supported Post Type(s)', 'simple-page-access-restriction' ); ?></strong>
							</th>
							<td>
								<?php
									$post_types = get_post_types( array(), 'objects' );
									$skip_posts = apply_filters( 'ps_simple_par_prevent_post_types', array( 'attachment' ) );
								?>
								<?php foreach ( $post_types as $post_type ): ?>
									<?php
										if ( ! $post_type->public || in_array( $post_type->name, $skip_posts ) ) {
											continue;
										}
									?>
									<label>
										<input type="checkbox" name="ps_simple_par_[post_types][]" value="<?php echo $post_type->name; ?>" <?php echo ( in_array( $post_type->name, $settings['post_types'] ) ? 'checked' : '' ); ?>>
										<span><?php echo $post_type->labels->name ?></span>
									</label><br>
								<?php endforeach; ?>
								
							</td>
						</tr>

					</tbody>
				</table>
				
			</div>
			
		</div>                
		<div style="display: flex; margin-top: 1.5em; height: 2em; align-items: center;">
			<input type="submit" name="ps_simple_par_save_settings" id="submit" class="button button-primary" value="Save Changes">
		</div>
	</form>

	<?php if ( isset( $promos ) && ! empty( $promos ) ): ?>
		<div class="simple-par-other-plugins">
			<?php foreach ( $promos as $promo ): ?>
				<div class="simple-par-other-plugin">
					<div class="simple-par-other-plugin-title">
						<a href="<?php echo esc_url( $promo['url'] ); ?>" target="_blank"><?php esc_html_e( $promo['title'] ); ?></a>
					</div>
					<div class="simple-par-other-plugin-links">
						<div><a href="<?php echo esc_url( $promo['url'] ); ?>" target="_blank"><?php _e( 'View', 'simple-page-access-restriction' ); ?></a></div>
						<?php if ( isset( $promo['documentation'] ) ): ?>
							<div><a href="<?php echo esc_url( $promo['documentation'] ); ?>" target="_blank"><?php _e( 'Documentation', 'simple-page-access-restriction' ); ?></a></div>
						<?php endif; ?>
						<?php if ( isset( $promo['support'] ) ): ?>
							<div><a href="<?php echo esc_url( $promo['support'] ); ?>" target="_blank"><?php _e( 'Support', 'simple-page-access-restriction' ); ?></a></div>
						<?php endif; ?>
					</div>
					<div class="simple-par-other-plugin-image"><a href="<?php echo esc_url( $promo['url'] ); ?>" target="_blank"><img src="<?php echo esc_url( $promo['image'] ); ?>" /></a></div>
					<div class="simple-par-other-plugin-desc">
						<?php if ( $promo['initial_link'] ) : ?>
							<a href="<?php echo esc_url( $promo['url'] ); ?>" target="_blank"><?php esc_html_e( $promo['title'] ); ?></a> 
						<?php endif; ?>

						<?php echo wp_kses_post( $promo['description'] ); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

</div>