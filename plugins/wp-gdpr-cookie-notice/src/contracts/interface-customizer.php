<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Customizer interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts;

/**
 * Interface for a simplified Customizer class.
 *
 * @since 1.0.0
 */
interface Customizer {

	/**
	 * Adds a control to the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param Customizer_Control $control Control instance.
	 */
	public function add_control( Customizer_Control $control );

	/**
	 * Adds a partial to the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param Customizer_Partial $partial Control instance.
	 */
	public function add_partial( Customizer_Partial $partial );
}
