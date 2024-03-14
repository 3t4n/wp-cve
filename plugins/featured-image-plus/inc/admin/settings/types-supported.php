<?php
/**
 * [Short description]
 *
 * @package    DEVRY\FIP
 * @copyright  Copyright (c) 2024, Developry Ltd.
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since      1.4
 */

namespace DEVRY\FIP;

! defined( ABSPATH ) || exit; // Exit if accessed directly.

/**
 * Display the types supported options.
 */
function fip_types_supported() {
	$fip = new Featured_Image_Plus();

	$types_supported = get_option( 'fip_types_supported', $fip->types_supported );

	$options_html = '';

	$types_available = array(
		'post' => 'post',
		'page' => 'page',
	);

	foreach ( $types_available as $type ) {
		$type_text = ucwords( $type );
		$selected  = '';

		if ( in_array( $type, $types_supported, true ) ) {
			$selected = 'selected';
		}

		$options_html .= "<option value=\"${type}\" ${selected}>${type_text}</option>";
	}

	printf( "<select id=\"fip-types-supported\" name=\"fip_types_supported[]\" multiple>${options_html}</select>" );
	?>
		<p class="description">
			<small>
				<?php echo esc_html__( 'Select supported types for the featured images.', 'featured-image-plus' ); ?>
			</small>
		</p>
	<?php
}

/**
 * Sanitize and update types supported option.
 */
function fip_sanitize_types_supported( $types_supported ) {
	// Verify the nonce.
	if ( empty( $_REQUEST['fip_nonce'] )
		|| ! wp_verify_nonce( $_REQUEST['fip_nonce'], 'fip_security' ) ) {
		return;
	}

	// Nothing selected.
	if ( empty( $types_supported ) ) {
		return;
	}

	// Option changed and updated.
	if ( get_option( 'fip_types_supported' ) != $types_supported ) { // Don't use strict comparsions to check that arrays are equal.
		add_settings_error(
			'fip_settings_errors',
			'fip_types_supported',
			esc_html__( 'Supported types option was updated successfully.', 'featured-image-plus' ),
			'updated'
		);
	}

	return array_map( 'sanitize_text_field', $types_supported );
}
