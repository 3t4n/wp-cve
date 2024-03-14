<?php
/**
 * Assign the class for Cornerstone Support of ARFormslite
 *
 * @package ARFormslite
 */

/**
 * Class for ARFormslite Cornerstone Support
 *
 * @package ARFormslite
 */
class ARFormslite_CS {

	/**
	 * Function to assign attributes for Cornerstone Control for ARFormslite
	 *
	 * @package ARFormslite
	 */
	public function ui() {
		return array(
			'title' => addslashes( 'ARFORMS LITE' ),
			'autofocus'  => array(
				'heading' => 'h4.arformslite-cs-heading',
				'content' => '.arformslite-cs',
			),
			'icon_group' => 'ARFORMS',
		);
	}

	/**
	 * Function to update the ARFormslite Cornerstone shortcode
	 *
	 * @param array $atts - store array of cornerstone control attributes.
	 *
	 * @package ARFormslite
	 */
	public function update_build_shortcode_atts( $atts ) {

		if ( ! isset( $atts['style'] ) ) {
			$atts['style'] = '';
		}

		if ( isset( $atts['background_color'] ) ) {
			$atts['style'] .= ' background-color: ' . $atts['background_color'] . ';';
			unset( $atts['background_color'] );
		}

		return $atts;
	}

}
