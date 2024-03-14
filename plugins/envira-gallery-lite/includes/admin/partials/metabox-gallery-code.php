<?php
/**
 * Outputs the Gallery Code Metabox Content.
 *
 * @since   1.5.0
 *
 * @var array $data Array of data to pass to the view.
 *
 * @package Envira_Gallery
 * @author  Envira Team
 */

?>
<p><?php esc_html_e( 'You can place this gallery anywhere into your posts, pages, custom post types or widgets by using one of the shortcode(s) below:', 'envira-gallery-lite' ); ?></p>
<div class="envira-code">
	<code id="envira_shortcode_id_<?php echo intval( $data['post']->ID ); ?>"><?php echo '[envira-gallery id="' . intval( $data['post']->ID ) . '"]'; ?></code>
	<a href="#" title="<?php esc_attr_e( 'Copy Shortcode to Clipboard', 'envira-gallery-lite' ); ?>" data-clipboard-target="#envira_shortcode_id_<?php echo intval( $data['post']->ID ); ?>" class="dashicons dashicons-clipboard envira-clipboard">
		<span><?php esc_html_e( 'Copy to Clipboard', 'envira-gallery-lite' ); ?></span>
	</a>
</div>

<?php
if ( ! empty( $data['gallery_data']['config']['slug'] ) ) {
	?>
	<div class="envira-code">
		<code id="envira_shortcode_slug_<?php echo intval( $data['post']->ID ); ?>"><?php echo '[envira-gallery slug="' . esc_attr( $data['gallery_data']['config']['slug'] ) . '"]'; ?></code>
		<a href="#" title="<?php esc_attr_e( 'Copy Shortcode to Clipboard', 'envira-gallery-lite' ); ?>" data-clipboard-target="#envira_shortcode_slug_<?php echo intval( $data['post']->ID ); ?>" class="dashicons dashicons-clipboard envira-clipboard">
			<span><?php esc_html_e( 'Copy to Clipboard', 'envira-gallery-lite' ); ?></span>
		</a>
	</div>
	<?php
}
?>

<p><?php esc_html_e( 'You can also place this gallery into your template files by using <strong>one</strong> the template tag(s) below:', 'envira-gallery-lite' ); ?></p>
<div class="envira-code">
	<code id="envira_template_tag_id_<?php echo intval( $data['post']->ID ); ?>"><?php echo 'if ( function_exists( \'envira_gallery\' ) ) { envira_gallery( \'' . intval( $data['post']->ID ) . '\' ); }'; ?></code>
	<a href="#" title="<?php esc_attr_e( 'Copy Template Tag to Clipboard', 'envira-gallery-lite' ); ?>" data-clipboard-target="#envira_template_tag_id_<?php echo intval( $data['post']->ID ); ?>" class="dashicons dashicons-clipboard envira-clipboard">
		<span><?php esc_html_e( 'Copy to Clipboard', 'envira-gallery-lite' ); ?></span>
	</a>
</div>

<?php
if ( ! empty( $data['gallery_data']['config']['slug'] ) ) {
	?>
	<div class="envira-code">
		<code id="envira_template_tag_slug_<?php echo intval( $data['post']->ID ); ?>"><?php echo 'if ( function_exists( \'envira_gallery\' ) ) { envira_gallery( \'' . esc_html( $data['gallery_data']['config']['slug'] ) . '\', \'slug\' ); }'; ?></code>
		<a href="#" title="<?php esc_attr_e( 'Copy Template Tag to Clipboard', 'envira-gallery-lite' ); ?>" data-clipboard-target="#envira_template_tag_slug_<?php echo intval( $data['post']->ID ); ?>" class="dashicons dashicons-clipboard envira-clipboard">
			<span><?php esc_html_e( 'Copy to Clipboard', 'envira-gallery-lite' ); ?></span>
		</a>
	</div>
	<?php
}
