<?php
/**
* Handles Behaviour Setting metabox HTML
*
* @package Popup Anything on Click
* @since 2.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking some variable
$popup_appear_data	= popupaoc_when_appear_options();
$popup_goals		= popupaoc_popup_goals();
$popup_types		= popupaoc_popup_types();
$popup_appear		= get_post_meta( $post->ID, $prefix.'popup_appear', true );
$behaviour			= get_post_meta( $post->ID, $prefix.'behaviour', true );
$popup_app_array	= array('page_load', 'simple_link', 'image', 'button');

// Taking some data
$popup_appear		= ! empty( $popup_appear )					? $popup_appear					: 'page_load';
$open_delay			= isset( $behaviour['open_delay'] ) 		? $behaviour['open_delay']		: '';
$disappear			= isset( $behaviour['disappear'] )			? $behaviour['disappear']		: '';
$loader_speed		= isset( $behaviour['loader_speed'] )		? $behaviour['loader_speed']	: '';
$image_url			= isset( $behaviour['image_url'] )			? $behaviour['image_url']		: '';
$btn_class			= isset( $behaviour['btn_class'] )			? $behaviour['btn_class']		: '';
$link_text			= ! empty( $behaviour['link_text'] )		? $behaviour['link_text']		: '';
$btn_text			= ! empty( $behaviour['btn_text'] )			? $behaviour['btn_text']		: '';
$popup_img_id		= ! empty( $behaviour['popup_img_id'] )		? $behaviour['popup_img_id']	: 0;
$image_title		= ! empty( $behaviour['image_title'] )		? 1								: 0;
$image_caption		= ! empty( $behaviour['image_caption'] )	? 1								: 0;
$hide_close			= ! empty( $behaviour['hide_close'] )		? 1								: 0;
$clsonesc			= ! empty( $behaviour['clsonesc'] )			? 1								: 0;
$close_overlay		= ! empty( $behaviour['close_overlay'] )	? 1								: 0;
$hide_overlay		= ! empty( $behaviour['hide_overlay'] )		? 1								: 0;
$enable_loader		= ! empty( $behaviour['enable_loader'] )	? 1								: 0;
$campaign_enable	= ! empty( $campaign_data->enable )			? 1								: 0;
?>
<div id="paoc_behaviour_sett" class="paoc-vtab-cnt paoc-behaviour-sett paoc-clearfix">

	<div class="paoc-tab-info-wrap">
		<div class="paoc-tab-title"><?php esc_html_e('Behaviour Settings', 'popup-anything-on-click'); ?></div>
		<span class="paoc-tab-desc"><?php esc_html_e('Choose Popup behaviour settings.', 'popup-anything-on-click'); ?></span>
	</div>

	<table class="form-table paoc-tbl">
		<tbody>
			<tr>
				<td colspan="3" class="paoc-no-lr-padding">
					<div class="paoc-row paoc-icolumns-wrap paoc-columns-margin">
						<?php
						if( ! empty( $popup_goals ) ) {
							foreach ($popup_goals as $popup_goal_key => $popup_goal_val) {

								$name = isset( $popup_goal_val['name'] ) ? $popup_goal_val['name'] : $popup_goal_key;
								$icon = isset( $popup_goal_val['icon'] ) ? $popup_goal_val['icon'] : 'dashicons dashicons-admin-generic';
						?>
								<div class="paoc-icolumns paoc-medium-4">
									<label class="paoc-behav-box-wrp paoc-goal-wrap <?php if( $popup_goal_key != 'announcement' ) { echo 'paoc-pro-feature'; } ?>">
										<input type="radio" name="<?php echo esc_attr( $prefix ); ?>popup_goal" value="<?php echo esc_attr( $popup_goal_key ); ?>" class="paoc-radio paoc-show-hide paoc-goal-input" <?php checked( $popup_goal, $popup_goal_key ); ?> <?php if( $popup_goal_key != 'announcement' ) { echo 'disabled="disabled"'; } ?> data-prefix="goal" />
										<span class="paoc-behav-block paoc-goal-block">
											<i class="<?php echo esc_attr( $icon ); ?>"></i>
											<span class="paoc-behav-title"><?php echo esc_html( $name ); ?> <?php if( $popup_goal_key != 'announcement' ) { ?> <span class="paoc-pro-tag"><?php esc_html_e('PRO','popup-anything-on-click');?></span><?php } ?></span>
										</span>
									</label>
								</div>
						<?php }
						} ?>
					</div>
				</td>
			</tr>

			<tr>
				<th colspan="3">
					<div class="paoc-sub-sett-title"><i class="dashicons dashicons-admin-generic"></i> <?php esc_html_e('Popup Type', 'popup-anything-on-click'); ?></div>
				</th>
			</tr>
			<tr>
				<td colspan="3" class="paoc-no-lr-padding">
					<div class="paoc-row paoc-icolumns-wrap paoc-columns-margin">
						<?php
						if( ! empty( $popup_types ) ) {
							foreach ($popup_types as $popup_type_key => $popup_type_val) {

								$name = isset( $popup_type_val['name'] ) ? $popup_type_val['name'] : $popup_type_key;
								$icon = isset( $popup_type_val['icon'] ) ? $popup_type_val['icon'] : 'dashicons dashicons-admin-generic';
						?>
								<div class="paoc-icolumns paoc-medium-4">
									<label class="paoc-behav-box-wrp paoc-type-main <?php if( $popup_type_key != 'modal' ) { echo 'paoc-pro-feature'; } ?>">
										<input type="radio" name="<?php echo esc_attr( $prefix ); ?>display_type" value="<?php echo esc_attr( $popup_type_key ); ?>" class="paoc-radio paoc-show-hide paoc-type-input" <?php checked( $display_type, $popup_type_key ); ?> <?php if( $popup_type_key != 'modal' ) { echo 'disabled="disabled"'; } ?> data-prefix="type" />
										<span class="paoc-type-block paoc-behav-block">
											<i class="<?php echo esc_attr( $icon ); ?>"></i>
											<span class="paoc-behav-title"><?php echo esc_html( $name ); ?> <?php if( $popup_type_key != 'modal' ) { ?> <span class="paoc-pro-tag"><?php esc_html_e('PRO','popup-anything-on-click');?></span><?php } ?></span>
										</span>
									</label>
								</div>
						<?php }
						} ?>
					</div>
				</td>
			</tr>

			<tr>
				<th colspan="3">
					<div class="paoc-sub-sett-title"><i class="dashicons dashicons-admin-generic"></i> <?php esc_html_e('Popup Display', 'popup-anything-on-click'); ?></div>
				</th>
			</tr>
			<tr>
				<th>
					<label for="paoc-popup-appear"><?php esc_html_e('How Popup Appear?', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<select name="<?php echo esc_attr( $prefix ); ?>popup_appear" class="paoc-select paoc-show-hide paoc-popup-appear" id="paoc-popup-appear">
						<?php 
						if( ! empty( $popup_appear_data ) ) {
						foreach ( $popup_appear_data as $popup_appear_key => $popup_appear_val ) { ?>
							<option value="<?php echo esc_attr( $popup_appear_key ); ?>" <?php selected( $popup_appear, $popup_appear_key ); ?> <?php if( ! in_array( $popup_appear_key, $popup_app_array ) ) { echo 'disabled="disabled"'; } ?>><?php echo esc_html( $popup_appear_val ); ?></option>
						<?php } } ?>
					</select><br/>
					<span class="description"><?php esc_html_e('Choose how popup should come.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>

			<!-- Start - Simple Link Popup Setting -->
			<tr class="paoc-show-hide-row paoc-show-if-simple_link" style="<?php if( $popup_appear != 'simple_link' ) { echo 'display: none;'; } ?>">
				<td class="paoc-no-padding" colspan="2">
					<table class="form-table">
						<tr>
							<th>
								<label for="paoc-link-text"><?php esc_html_e('Link Text', 'popup-anything-on-click'); ?></label>
							</th>
							<td>
								<input type="text" name="<?php echo esc_attr( $prefix ); ?>behaviour[link_text]" value="<?php echo esc_attr( $link_text ); ?>" id="paoc-link-text" class="paoc-medium-text paoc-text paoc-link-text" /><br/>
								<span class="description"><?php esc_html_e('Enter popup link text.', 'popup-anything-on-click'); ?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="paoc-delay"><?php esc_html_e('Popup Shortcode', 'popup-anything-on-click'); ?></label>
							</th>
							<td>
								<div class="paoc-pro-shortcode-preview paoc-copy-clipboard">[popup_anything id="<?php echo esc_attr( $post->ID ); ?>"]</div><br/>
								<span class="description"><?php esc_html_e('Add this shortcode and popup will display on its click.', 'popup-anything-on-click'); ?></span>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<!-- End - Simple Link Popup Setting -->

			<!-- Start - Image Popup Setting -->
			<tr class="paoc-show-hide-row paoc-show-if-image" style="<?php if( $popup_appear != 'image' ) { echo 'display: none;'; } ?>">
				<td class="paoc-no-padding" colspan="2">
					<table class="form-table">
						<tr>
							<th>
								<label for="paoc-img-url"><?php esc_html_e('Popup Image', 'popup-anything-on-click'); ?></label>
							</th>
							<td>
								<input type="text" name="<?php echo esc_attr( $prefix ); ?>behaviour[image_url]" value="<?php echo esc_url( $image_url ); ?>" class="paoc-url regular-text paoc-img-url paoc-img-upload-input" id="paoc-img-url" />
								<input type="button" name="paoc_image_url" id="paoc_url" class="button-secondary paoc-image-upload" value="<?php esc_attr_e( 'Upload Image', 'popup-anything-on-click'); ?>" /> 
								<input type="button" name="paoc_image_url_clear" class="button button-secondary paoc-image-clear" value="<?php esc_attr_e( 'Clear', 'popup-anything-on-click'); ?>" /><br>
								<span class="description"><?php esc_html_e('Upload popup image.', 'popup-anything-on-click'); ?></span><br />
								<div class="paoc-img-view">
									<?php if( $image_url != '' ) {
										echo '<img src="'.esc_url( $image_url ).'" alt="" />';
									} ?>
								</div>
								<input type="hidden" name="<?php echo esc_attr( $prefix );?>behaviour[popup_img_id]" value="<?php echo esc_attr( $popup_img_id ); ?>" class="popup-image-id" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="paoc-image-title"><?php esc_html_e('Image Title', 'popup-anything-on-click'); ?></label>
							</th>
							<td>
								<input type="checkbox" name="<?php echo esc_attr( $prefix ); ?>behaviour[image_title]" value="1" <?php checked( $image_title, 1 ); ?> class="paoc-checkbox paoc-image-title" id="paoc-image-title" /><br />
								<span class="description"><?php esc_html_e('Check this box if you want to show image title.', 'popup-anything-on-click'); ?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="paoc-image-caption"><?php esc_html_e('Image Caption', 'popup-anything-on-click'); ?></label>
							</th>
							<td>
								<input type="checkbox" name="<?php echo esc_attr( $prefix ); ?>behaviour[image_caption]" value="1" <?php checked( $image_caption, 1 ); ?> class="paoc-checkbox paoc-image-caption" id="paoc-image-caption" /><br />
								<span class="description"><?php esc_html_e('Check this box if you want to show image caption.', 'popup-anything-on-click'); ?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="paoc-delay"><?php esc_html_e('Popup Shortcode', 'popup-anything-on-click'); ?></label>
							</th>
							<td>
								<div class="paoc-pro-shortcode-preview paoc-copy-clipboard">[popup_anything id="<?php echo esc_attr( $post->ID ); ?>"]</div><br/>
								<span class="description"><?php esc_html_e('Add this shortcode and popup will display on its click.', 'popup-anything-on-click'); ?></span>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<!-- End - Image Popup Setting -->

			<!-- Start - Button Popup Setting -->
			<tr class="paoc-show-hide-row paoc-show-if-button" style="<?php if( $popup_appear != 'button' ) { echo 'display: none;'; } ?>">
				<td class="paoc-no-padding" colspan="2">
					<table class="form-table">
						<tr>
							<th>
								<label for="paoc-btn-text"><?php esc_html_e('Button Text', 'popup-anything-on-click'); ?></label>
							</th>
							<td>
								<input type="text" name="<?php echo esc_attr( $prefix ); ?>behaviour[btn_text]" value="<?php echo esc_attr( $btn_text ); ?>" id="paoc-btn-text" class="paoc-medium-text paoc-text paoc-btn-text" /><br/>
								<span class="description"><?php esc_html_e('Enter popup button text.', 'popup-anything-on-click'); ?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="paoc-btn-cls"><?php esc_html_e('Button Custom Class', 'popup-anything-on-click'); ?></label>
							</th>
							<td>
								<input type="text" name="<?php echo esc_attr( $prefix ); ?>behaviour[btn_class]" value="<?php echo esc_attr( $btn_class ); ?>" id="paoc-btn-cls" class="paoc-medium-text paoc-text paoc-btn-cls" /><br/>
								<span class="description"><?php esc_html_e('Enter Popup button custom class if you want to apply your own custom styling. Some predefined classes are', 'popup-anything-on-click') ?></span> popupaoc-black, popupaoc-white, popupaoc-grey, popupaoc-azure, popupaoc-moderate-green, popupaoc-soft-red, popupaoc-red, popupaoc-green, popupaoc-bright-yellow, popupaoc-cyan, popupaoc-orange, popupaoc-moderate-violet, popupaoc-dark-magenta, popupaoc-moderate-blue, popupaoc-blue, .popupaoc-magenta, .popupaoc-lime, popupaoc-pink, popupaoc-vivid-yellow, popupaoc-lime-green, popupaoc-yellow<br/>
							</td>
						</tr>
						<tr>
							<th>
								<label for="paoc-delay"><?php esc_html_e('Popup Shortcode', 'popup-anything-on-click'); ?></label>
							</th>
							<td>
								<div class="paoc-pro-shortcode-preview paoc-copy-clipboard">[popup_anything id="<?php echo esc_attr( $post->ID ); ?>"]</div><br/>
								<span class="description"><?php esc_html_e('Add this shortcode and popup will display on its click.', 'popup-anything-on-click'); ?></span>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<!-- End - Button Popup Setting -->

			<!-- Start - Page load Popup Setting -->
			<tr class="paoc-show-hide-row paoc-show-if-page_load paoc-show-if-exit paoc-show-if-simple_link paoc-show-if-image paoc-show-if-button" style="<?php if( ! ( $popup_appear == 'page_load' || $popup_appear == 'exit' || $popup_appear == 'simple_link' || $popup_appear == 'image' || $popup_appear == 'button' ) ) { echo 'display: none;'; } ?>">
				<th>
					<label for="paoc-delay"><?php esc_html_e('Popup Delay', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="text" name="<?php echo esc_attr( $prefix ); ?>behaviour[open_delay]" value="<?php echo esc_attr( $open_delay ); ?>" class="paoc-medium-text paoc-text paoc-delay" id="paoc-delay" min="0"> <?php esc_html_e('Sec', 'popup-anything-on-click'); ?><br />
					<span class="description"><?php esc_html_e('Enter no of second to open popup. 60 Sec = 1 Min', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>
			<!-- End - Page load Popup Setting -->

			<tr>
				<th>
					<label for="paoc-disappear"><?php esc_html_e('Popup Disappear', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="text" name="<?php echo esc_attr( $prefix ); ?>behaviour[disappear]" value="<?php echo esc_attr( $disappear ); ?>" class="paoc-medium-text paoc-text paoc-disappear" id="paoc-disappear" /> <?php esc_html_e('Sec', 'popup-anything-on-click'); ?><br />
					<span class="description"><?php esc_html_e('Enter no of second to hide popup after open. 60 Sec = 1 Min', 'popup-anything-on-click'); ?></span><br/>
					<div class="paoc-code-tag-wrap">
						<code><?php esc_html_e('Positive Number', 'popup-anything-on-click'); ?></code> - <span class="description"><?php esc_html_e('If you add positive number then popup will be disappear on inactivity only.', 'popup-anything-on-click'); ?></span><br/>
						<code><?php esc_html_e('Negative Number', 'popup-anything-on-click'); ?></code> - <span class="description"><?php esc_html_e('If you add negative number then popup will be disappear forcefully after given time.', 'popup-anything-on-click'); ?></span>
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<label for="paoc-hide-close-btn"><?php esc_html_e('Hide Close Button', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="checkbox" name="<?php echo esc_attr( $prefix ); ?>behaviour[hide_close]" value="1" <?php checked( $hide_close, 1 ); ?> class="paoc-checkbox paoc-hide-close-btn" id="paoc-hide-close-btn" /><br />
					<span class="description"><?php esc_html_e('Check this box if you want to hide the close button of popup.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="paoc-clsonesc"><?php esc_html_e('Close Popup On Esc', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="checkbox" name="<?php echo esc_attr( $prefix ); ?>behaviour[clsonesc]" value="1" <?php checked( $clsonesc, 1 ); ?> class="paoc-checkbox paoc-clsonesc" id="paoc-clsonesc" /><br />
					<span class="description"><?php esc_html_e('Check this box if you want to close the popup on esc key.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="paoc-hide-overlay"><?php esc_html_e('Hide Overlay', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="checkbox" name="<?php echo esc_attr( $prefix ); ?>behaviour[hide_overlay]" value="1" <?php checked( $hide_overlay, 1 ); ?> class="paoc-checkbox paoc-show-hide paoc-hide-overlay" id="paoc-hide-overlay" data-prefix="overlay" /><br />
					<span class="description"><?php esc_html_e('Check this box if you do not want to display popup ovarlay.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>

			<!-- Start - Popup Loader Settings -->
			<tr class="paoc-show-hide-row-overlay paoc-hide-if-overlay-1" style="<?php if( ! empty( $hide_overlay ) ) { echo 'display: none;'; } ?>">
				<td class="paoc-no-padding" colspan="2">
					<table class="form-table">
						<tr>
							<th>
								<label for="paoc-close-overlay"><?php esc_html_e('Close Popup On Overlay', 'popup-anything-on-click'); ?></label>
							</th>
							<td>
								<input type="checkbox" name="<?php echo esc_attr( $prefix ); ?>behaviour[close_overlay]" value="1" <?php checked( $close_overlay, 1 ); ?> class="paoc-checkbox paoc-close-overlay" id="paoc-close-overlay" /><br />
								<span class="description"><?php esc_html_e('Check this box if you want to close the popup on overlay key.', 'popup-anything-on-click'); ?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="paoc-enable-loader"><?php esc_html_e('Enableb Loader', 'popup-anything-on-click'); ?></label>
							</th>
							<td>
								<input type="checkbox" name="<?php echo esc_attr( $prefix ); ?>behaviour[enable_loader]" value="1" <?php checked( $enable_loader, 1 ); ?> id="paoc-enable-loader" class="paoc-checkbox paoc-enable-loader" /><br/>
								<span class="description"><?php esc_html_e('Check this box if you want to display popup loader.', 'popup-anything-on-click'); ?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="paoc-loader-speed"><?php esc_html_e('Loader Speed', 'popup-anything-on-click'); ?></label>
							</th>
							<td>
								<input type="text" name="<?php echo esc_attr( $prefix ); ?>behaviour[loader_speed]" value="<?php echo esc_attr( $loader_speed ); ?>" id="paoc-loader-speed" class="paoc-medium-text paoc-text paoc-loader-speed" /> <?php esc_html_e('Sec', 'popup-anything-on-click'); ?><br/>
								<span class="description"><?php esc_html_e('Set popup loader speed. 60 Sec = 1 Min', 'popup-anything-on-click'); ?></span>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<!-- End - Popup Loader Settings -->
		</tbody>
	</table>
</div><!-- end .paoc-behaviour-sett -->