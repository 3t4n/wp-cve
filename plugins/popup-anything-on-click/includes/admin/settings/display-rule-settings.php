<?php
/**
 * Display Rule Settings
 *
 * @package Popup Anything on Click
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking some variable
$global_location	= popupaoc_display_locations();
$reg_post_types		= popupaoc_get_post_types( null, array('attachment', 'revision', 'nav_menu_item') );
$welcome_popup		= popupaoc_get_option( 'welcome_popup' );
$welcome_display_in	= popupaoc_get_option( 'welcome_display_in', array() );
$welcome_meta_data	= popupaoc_sugg_meta_data( 'welcome' );

// Getting Some Data
$welcome_popup_post 	= ( ! empty( $welcome_popup ) )					? get_post( $welcome_popup )		: '';
$welcome_popup_title	= ! empty( $welcome_popup_post->post_title )	? $welcome_popup_post->post_title	: __('Post', 'popup-anything-on-click');
?>

<div class="postbox paoc-no-toggle">

	<div class="postbox-header">
		<h3 class="hndle">
			<span><?php esc_html_e( 'Display Rule Settings', 'popup-anything-on-click' ); ?></span>
		</h3>
	</div>

	<div class="inside">
		<table class="form-table paoc-tbl">
			<tbody>
				<!-- Start - Welcome Popup Settings -->
				<tr>
					<th colspan="2"><div class="paoc-sub-sett-title"><i class="dashicons dashicons-admin-generic"></i> <?php esc_html_e('Welcome Popup Settings', 'popup-anything-on-click'); ?></div></th>
				</tr>
				<tr>
					<th>
						<label for="paoc-welcome-popup"><?php esc_html_e('Welcome Popup', 'popup-anything-on-click'); ?></label>
					</th>
					<td>
						<select name="popupaoc_options[welcome_popup]" id="paoc-welcome-popup" class="paoc-select2-medium paoc-post-title-sugg paoc-welcome-popup" data-placeholder="<?php esc_html_e('Select Welcome Popup', 'popup-anything-on-click'); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce('paoc-post-title-sugg') ); ?>" data-post-type="<?php echo esc_attr( POPUPAOC_POST_TYPE ); ?>" data-meta='<?php echo esc_attr( json_encode( $welcome_meta_data ) ); ?>'>
							<option></option>
							<?php if( $welcome_popup_post ) { ?>
							<option value="<?php echo esc_attr( $welcome_popup_post->ID ); ?>" selected="selected"><?php echo esc_html( $welcome_popup_title ." - (#{$welcome_popup_post->ID})" ); ?></option>
							<?php } ?>
						</select><br/>
						<span class="description"><?php esc_html_e('Select welcome popup to display globally. You can search popup by its name or ID.', 'popup-anything-on-click'); ?></span>
					</td>
				</tr>
				<tr>
					<th>
						<label for="paoc-wel-displayin"><?php esc_html_e('Display In', 'popup-anything-on-click'); ?></label>
					</th>
					<td>
						<?php if( ! empty( $global_location ) ) {
							foreach ( $global_location as $global_location_key => $global_location_val ) { ?>
								<div class="paoc-loop-irow">
									<label>
										<input type="checkbox" name="popupaoc_options[welcome_display_in][<?php echo esc_attr( $global_location_key ); ?>]" class="paoc-checkbox paoc-wel-gbl-locs" value="1" <?php checked( array_key_exists( $global_location_key, $welcome_display_in ), true ); ?> />
										<?php echo esc_html( $global_location_val ); ?>
									</label>
								</div>
							<?php }
						} ?>
						<br/>
						<span class="description"><?php esc_html_e('Check this box to display welcome popup globally. You can still choose the popup for single posts and pages.', 'popup-anything-on-click'); ?></span>
					</td>
				</tr>
				<!-- End - Welcome Popup Settings -->
				<tr>
					<td colspan="2" class="paoc-no-padding">
						<!-- Pro Notice -->
						<div class="paoc-pro-notice">
							<i class="dashicons dashicons-money-alt"></i>
							<?php echo sprintf( __( 'Utilize below <a href="%s" target="_blank">Premium Features with Risk-Free 30 days money back guarantee</a> to get best of this plugin  with Annual or Lifetime bundle deal.', 'popup-anything-on-click'), POPUPAOC_PLUGIN_LINK_UNLOCK); ?>
						</div>
					</td>	
				</tr>
				<!-- Pro Features - Start -->
				<tr class="paoc-pro-feature">
					<td colspan="2" class="paoc-no-padding">
						<table class="form-table">
							<tbody>

								<tr>
									<th>
										<label for="paoc-type"><?php esc_html_e('Post Types', 'popup-anything-on-click'); ?></label>
									</th>
									<td>
										<?php if( ! empty( $reg_post_types ) ) {
											foreach ( $reg_post_types as $post_key => $post_label ) {
										?>
											<div class="paoc-loop-irow">
												<label>
													<input type="checkbox" value="<?php echo esc_attr( $post_key ); ?>" name="" class="paoc-checkbox" disabled="disabled" />
													<?php echo esc_html( $post_label ); ?>
												</label>
											</div>
											<?php }
										} ?>
										<br/>
										<span class="description"><?php esc_html_e('Check these boxes if you want to show different popups for individual posts and pages. This will enable the setting box at enabled post types while you add or edit it.', 'popup-anything-on-click'); ?></span>
									</td>
								</tr>

								<!-- Start - Exit Popup Settings -->
								<tr>
									<th colspan="2"><div class="paoc-sub-sett-title"><i class="dashicons dashicons-admin-generic"></i> <?php esc_html_e('Exit Popup Settings', 'popup-anything-on-click'); ?> <span class="paoc-pro-tag"><?php esc_html_e('PRO','popup-anything-on-click');?></span></div></th>
								</tr>
								<tr>
									<th>
										<label for="paoc-exit-popup"><?php esc_html_e('Exit Popup', 'popup-anything-on-click'); ?></label>
									</th>
									<td>
										<select name="" id="paoc-exit-popup" class="paoc-select2-medium paoc-post-title-sugg paoc-exit-popup" data-placeholder="<?php esc_html_e('Select Exit Popup', 'popup-anything-on-click'); ?>" disabled="disabled">
											<option></option>
										</select><br/>
										<span class="description"><?php esc_html_e('Select exit popup to display globally. You can search popup by its name or ID.', 'popup-anything-on-click'); ?></span>
									</td>
								</tr>
								<tr>
									<th>
										<label for="paoc-exit-displayin"><?php esc_html_e('Display In', 'popup-anything-on-click'); ?></label>
									</th>
									<td>
										<?php if( ! empty( $global_location ) ) {
											foreach ( $global_location as $global_location_key => $global_location_val ) { ?>
												<div class="paoc-loop-irow">
												<label>
													<input type="checkbox" name="" class="paoc-checkbox paoc-exit-gbl-locs" value="1" disabled="disabled" />
													<?php echo esc_html( $global_location_val ); ?>
												</label>
												</div>
											<?php }
										} ?>
										<br/>
										<span class="description"><?php esc_html_e('Check this box to display exit popup globally. You can still choose the popup for single posts and pages.', 'popup-anything-on-click'); ?></span>
									</td>
								</tr>
								<!-- End - Exit Popup Settings -->

								<!-- Start - General Popup Settings -->
								<tr>
									<th colspan="2"><div class="paoc-sub-sett-title"><i class="dashicons dashicons-admin-generic"></i> <?php esc_html_e('General Popup Settings (All type of Popup Appear)', 'popup-anything-on-click'); ?> <span class="paoc-pro-tag"><?php esc_html_e('PRO','popup-anything-on-click');?></span></div></th>
								</tr>
								<tr>
									<th>
										<label for="paoc-general-popup"><?php esc_html_e('General Popup', 'popup-anything-on-click'); ?></label>
									</th>
									<td>
										<select name="" id="paoc-general-popup" class="paoc-select2-mul paoc-post-title-sugg paoc-general-popup" data-placeholder="<?php esc_html_e('Select General Popup', 'popup-anything-on-click'); ?>" disabled="disabled">
											<option></option>
										</select><br/>
										<span class="description"><?php esc_html_e('Select general popup to display globally. You can search popup by its name or ID.', 'popup-anything-on-click'); ?></span>
									</td>
								</tr>
								<tr>
									<th>
										<label for="paoc-general-displayin"><?php esc_html_e('Display In', 'popup-anything-on-click'); ?></label>
									</th>
									<td>
										<?php if( ! empty( $global_location ) ) {
											foreach ( $global_location as $global_location_key => $global_location_val ) { ?>
												<div class="paoc-loop-irow">
												<label>
													<input type="checkbox" name="" class="paoc-checkbox paoc-general-gbl-locs" value="1" disabled="disabled" />
													<?php echo esc_html( $global_location_val ); ?>
												</label>
												</div>
											<?php }
										} ?>
										<br/>
										<span class="description"><?php esc_html_e('Check this box to display general popup globally. You can still choose the popup for single posts and pages.', 'popup-anything-on-click'); ?></span>
									</td>
								</tr>
								<!-- End - General Popup Settings -->
							</tbody>
						</table>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<input type="submit" name="paoc_display_rule_sett" class="button button-primary right paoc-btn paoc-sett-submit" value="<?php esc_attr_e('Save Changes', 'popup-anything-on-click'); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
	</div><!-- end .inside -->
</div><!-- end .postbox -->