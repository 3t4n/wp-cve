<?php
/**
 * Renderer for video layers.
 * 
 * @since 4.0.0
 */
class BQW_SP_Video_Layer_Renderer extends BQW_SP_Layer_Renderer {

	/**
	 * Initialize the video layer renderer.
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
		$video_source = isset( $this->data['video_source'] ) && $this->data['video_source'] !== '' ? $this->data['video_source'] : '';
		$video_id = isset( $this->data['video_id'] ) && $this->data['video_id'] !== '' ? $this->data['video_id'] : '';
		$video_load_mode = isset( $this->data['video_load_mode'] ) && $this->data['video_load_mode'] !== '' ? $this->data['video_load_mode'] : '';
		$video_params = isset( $this->data['video_params'] ) && $this->data['video_params'] !== '' ? $this->data['video_params'] : '';
		$video_poster = isset( $this->data['video_poster'] ) && $this->data['video_poster'] !== '' ? $this->data['video_poster'] : '';
		$video_retina_poster = isset( $this->data['video_retina_poster'] ) && $this->data['video_retina_poster'] !== '' ? ' data-retina="' . esc_attr( $this->data['video_retina_poster'] ) . '"' : '';

		$poster_src = $this->lazy_loading === true ? ' src="' . plugins_url( 'public/assets/css/images/blank.gif', dirname( __FILE__ ) ) . '" data-src="' . esc_attr( $video_poster ) . '"' : ' src="' . esc_attr( $video_poster ) . '"';

		$video_html = '';

		if ( $video_source === 'youtube' ) {
			$params = $video_params !== '' ? '&' . $video_params : '';

			if ( $video_load_mode === 'poster' ) {
				$video_html = '<div class="' . esc_attr( $this->get_classes() ) . '"' . $this->get_attributes() . '><a class="sp-video" href="http://www.youtube.com/watch?v=' . esc_attr( $video_id ) . '&rel=0' . esc_attr( $params ) . '"><img' . $poster_src . $video_retina_poster . ' width="100%" height="100%" /></a></div>';
			} else if ( $video_load_mode === 'video' ) {
				$video_html = '<iframe class="sp-video ' . esc_attr( $this->get_classes() ) . '"' . $this->get_attributes() . ' src="//www.youtube.com/embed/' . esc_attr( $video_id ) . '?enablejsapi=1&wmode=opaque&rel=0' . esc_attr( $params ) . '" frameborder="0" allowfullscreen></iframe>';
			}
		} else if ( $video_source === 'vimeo' ) {
			$params = $video_params !== '' ? '?' . $video_params : '';

			if ( $video_load_mode === 'poster' ) {
				$video_html = '<div class="' . esc_attr( $this->get_classes() ) . '"' . $this->get_attributes() . '><a class="sp-video" href="http://vimeo.com/' . esc_attr( $video_id ) . esc_attr( $params ) . '"><img' . $poster_src . $video_retina_poster . ' width="100%" height="100%" /></a></div>';
			} else if ( $video_load_mode === 'video' ) {
				$video_html = '<iframe class="sp-video ' . esc_attr( $this->get_classes() ) . '"' . $this->get_attributes() . ' src="//player.vimeo.com/video/' . esc_attr( $video_id ). esc_attr( $params ) . '" frameborder="0" allowfullscreen></iframe>';
			}
		}

		$html_output = "\r\n" . '			' . $video_html;

		$html_output = apply_filters( 'sliderpro_layer_markup', $html_output, $this->slider_id, $this->slide_index );

		return $html_output;
	}
}