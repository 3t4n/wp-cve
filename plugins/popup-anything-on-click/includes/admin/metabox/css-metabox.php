<?php
/**
 * Handles Custom CSS Setting metabox HTML
 * 
 * @package Popup Anything on Click
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking some variable
$custom_css	= get_post_meta( $post->ID, $prefix.'custom_css', true );
?>

<div id="paoc_css_sett" class="paoc-vtab-cnt paoc-css-sett paoc-clearfix">

	<div class="paoc-tab-info-wrap">
		<div class="paoc-tab-title"><?php esc_html_e('Custom CSS Settings', 'popup-anything-on-click'); ?></div>
		<span class="paoc-tab-desc"><?php esc_html_e('Set popup custom css settings.', 'popup-anything-on-click'); ?></span>
	</div>

	<table class="form-table paoc-tbl paoc-sett-tbl">
		<tbody>
			<tr>
				<td class="paoc-no-lr-padding">
					<textarea id="paoc-custom-css" name="<?php echo esc_attr( $prefix ); ?>custom_css" class="wpos-code-editor paoc-code-editor-small paoc-css-editor large-text" data-mode="css"><?php echo esc_textarea( $custom_css ); ?></textarea>
					<span class="description"><?php esc_html_e('Enter custom CSS for popup. Note: Do not include `style` tag.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>
		</tbody>
	</table>
</div>