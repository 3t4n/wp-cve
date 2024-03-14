<?php
/**
 * Renderer for heading layers.
 * 
 * @since 4.0.0
 */
class BQW_SP_Heading_Layer_Renderer extends BQW_SP_Layer_Renderer {

	/**
	 * Initialize the heading layer renderer.
	 * 
	 * @since 4.0.0
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Return the layer's HTML markup.
	 * 
	 * @since 4.0.0
	 *
	 * @return string The layer HTML.
	 */
	public function render() {
		$content = isset( $this->data['text'] ) ? $this->data['text'] : '';
		$content = apply_filters( 'sliderpro_layer_content', $content );
		
		$type = isset( $this->data['heading_type'] ) ? ( in_array( $this->data['heading_type'], array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) ) ? $this->data['heading_type'] : 'h1' ) : 'h1';
		
		$html_output = "\r\n" . '			' . '<' . $type . ' class="' .  esc_attr( $this->get_classes() ) . '"' . $this->get_attributes() . '>' . wp_kses_post( $content ) . '</' . $type . '>';

		$html_output = apply_filters( 'sliderpro_layer_markup', $html_output, $this->slider_id, $this->slide_index );

		return $html_output;
	}
}