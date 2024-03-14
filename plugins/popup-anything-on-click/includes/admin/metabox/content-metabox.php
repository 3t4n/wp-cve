<?php
/**
 * Handles Content Setting metabox HTML
 * 
 * @package Popup Anything on Click
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking some variables
$content			= popupaoc_get_meta( $post->ID, $prefix.'content' );
$popup_content		= ( $post->post_content != '' )				? $post->post_content			: $default_meta['content'];
$main_heading		= isset( $content['main_heading'] )			? $content['main_heading']		: '';
$sub_heading		= isset( $content['sub_heading'] )			? $content['sub_heading']		: '';
$secondary_content	= isset( $content['secondary_content'] )	? $content['secondary_content']	: '';
$cust_close_txt		= isset( $content['cust_close_txt'] )		? $content['cust_close_txt']	: '';
$security_note		= isset( $content['security_note'] )		? $content['security_note']		: '';
?>

<div id="paoc_content_sett" class="paoc-vtab-cnt paoc-content-sett paoc-clearfix">

	<div class="paoc-tab-info-wrap">
		<div class="paoc-tab-title"><?php esc_html_e('Content Settings', 'popup-anything-on-click'); ?></div>
		<span class="paoc-tab-desc"><?php esc_html_e('Choose Popup content settings.', 'popup-anything-on-click'); ?></span>
	</div>

	<table class="form-table paoc-tbl">
		<tbody>
			<tr>
				<th>
					<label for="paoc-main-heading"><?php esc_html_e('Main Heading', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="text" name="<?php echo esc_attr( $prefix ); ?>content[main_heading]" value="<?php echo esc_attr( $main_heading ); ?>" class="large-text paoc-text paoc-main-heading" id="paoc-main-heading" />
					<span class="description"><?php esc_html_e('Enter popup main heading text.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>

			<tr>
				<th>
					<label for="paoc-sub-heading"><?php esc_html_e('Sub Heading', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="text" name="<?php echo esc_attr( $prefix ); ?>content[sub_heading]" value="<?php echo esc_attr( $sub_heading ); ?>" class="large-text paoc-text paoc-sub-heading" id="paoc-sub-heading" />
					<span class="description"><?php esc_html_e('Enter popup sub heading text.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>

			<tr>
				<th>
					<label for="paoc-popup-content"><?php esc_html_e('Popup Content', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<?php wp_editor( $popup_content, 'paoc-popup-content', array('textarea_name' => 'content', 'editor_height' => 300, 'media_buttons' => true) ); ?>
					<span class="description"><?php echo sprintf( esc_html__('Enter popup content which will display after respective `Call to Action` like email form etc. Available template tags are %s.', 'popup-anything-on-click'), '<a href="javascript:void(0)" class="paoc-show-popup-tags" title="'.esc_attr__('Popup Tags', 'popup-anything-on-click').'" data-tags="general">'.esc_html__('here', 'popup-anything-on-click').'</a>' ); ?></span>
				</td>
			</tr>

			<tr>
				<th>
					<label for="paoc-secondary-con"><?php esc_html_e('Secondary Content', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<?php wp_editor( $secondary_content, 'paoc-secondary-con', array('textarea_name' => $prefix.'content[secondary_content]', 'editor_height' => 300, 'media_buttons' => true) ); ?>
					<span class="description"><?php echo sprintf( esc_html__('Enter popup secondary content which will display after respective `Call to Action` like email form etc. Available template tags are %s.', 'popup-anything-on-click'), '<a href="javascript:void(0)" class="paoc-show-popup-tags" title="'.esc_attr__('Popup Tags', 'popup-anything-on-click').'" data-tags="general">'.esc_html__('here', 'popup-anything-on-click').'</a>' ); ?></span>
				</td>
			</tr>

			<tr>
				<th>
					<label for="paoc-cus-close-txt"><?php esc_html_e('Custom Close Text', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="text" name="<?php echo esc_attr( $prefix ); ?>content[cust_close_txt]" value="<?php echo esc_attr( $cust_close_txt ); ?>" class="large-text paoc-text paoc-cus-close-txt" id="paoc-cus-close-txt" />
					<span class="description"><?php esc_html_e('Enter custom close text. e.g No, thank you. I do not want.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>

			<tr>
				<th>
					<label for="paoc-secur-note"><?php esc_html_e('Security Note', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="text" name="<?php echo esc_attr( $prefix ); ?>content[security_note]" value="<?php echo esc_attr( $security_note ); ?>" class="large-text paoc-text paoc-secur-note" id="paoc-secur-note" />
					<span class="description"><?php esc_html_e('Enter security note text.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>
		</tbody>
	</table>
</div><!-- end .paoc-content-sett -->