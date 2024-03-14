<?php
/**
 * Renderer for paragraph layers.
 * 
 * @since 4.0.0
 */
class BQW_SP_Paragraph_Layer_Renderer extends BQW_SP_Layer_Renderer {

	/**
	 * Initialize the paragraph layer renderer.
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
		
		$html_output = "\r\n" . '			' . '<p class="' .  esc_attr( $this->get_classes() ) . '"' . $this->get_attributes() . '>' . wp_kses_post( $content ) . '</p>';

		$html_output = do_shortcode( $html_output );
		$html_output = apply_filters( 'sliderpro_layer_markup', $html_output, $this->slider_id, $this->slide_index );

		return $html_output;
	}
}