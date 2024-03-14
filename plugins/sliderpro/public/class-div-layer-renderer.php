<?php
/**
 * Renderer for DIV layers.
 * 
 * @since 4.0.0
 */
class BQW_SP_Div_Layer_Renderer extends BQW_SP_Layer_Renderer {

	/**
	 * Initialize the DIV layer renderer.
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
		global $allowedposttags;

		$content = isset( $this->data['text'] ) ? $this->data['text'] : '';
		$content = apply_filters( 'sliderpro_layer_content', $content );

		$allowed_html = array_merge(
			$allowedposttags,
			array(
				'iframe' => array(
					'src' => true,
					'width' => true,
					'height' => true,
					'allow' => true,
					'allowfullscreen' => true,
					'class' => true,
					'id' => true
				),
				'source' => array(
					'src' => true,
					'type' => true
				)
			)
		);

		$allowed_html = apply_filters( 'sliderpro_allowed_html', $allowed_html );
		
		$html_output = "\r\n" . '			' . '<div class="' .  esc_attr( $this->get_classes() ) . '"' . $this->get_attributes() . '>' . wp_kses( $content, $allowed_html ) . '</div>';

		$html_output = do_shortcode( $html_output );
		$html_output = apply_filters( 'sliderpro_layer_markup', $html_output, $this->slider_id, $this->slide_index );

		return $html_output;
	}
}