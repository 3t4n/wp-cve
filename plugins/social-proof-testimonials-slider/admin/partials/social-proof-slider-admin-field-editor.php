<?php

/**
 * Provides the markup for any WP Editor field
 *
 * @link       https://thebrandiD.com
 * @since      2.0.0
 *
 * @package    Social_Proof_Slider
 * @subpackage Social_Proof_Slider/admin/partials
 */

// wp_editor( $content, $editor_id, $settings = array() );

if ( ! empty( $atts['label'] ) ) {
	?>
	<h3><label for="<?php echo esc_attr( $atts['id'] ); ?>"><?php esc_html_e( $atts['label'], 'social-proof-slider' ); ?>:</label></h3>
	<?php
}

wp_editor( html_entity_decode( $atts['value'] ), $atts['id'], $atts['settings'] );
?>
