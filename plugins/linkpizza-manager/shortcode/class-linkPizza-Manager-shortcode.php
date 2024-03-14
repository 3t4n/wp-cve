<?php
/**
 * LinkPizza Automated Links Widget
 *
 * @link       http://linkpizza.com
 * @since      1.0.0
 *
 * @package    linkPizza_Manager
 * @subpackage shortcode
 */
class LinkPizza_Manager_shortcode {

	/**
	 * Registers shortoce for displaying the widget.
	 *
	 * @return void
	 */
	public function pzz_register_shortcodes() {
		add_shortcode( 'pzzwidget', array( $this, 'pzz_widget_shortcode' ) );
	}

	/**
	 * Widget Shortcode handler.
	 *
	 * @param array $atts Array with attributes.
	 * @return string output of the wdiget.
	 */
	public function pzz_widget_shortcode( $atts ) {
		$pzz_id = get_option( 'pzz_id' );
		// Merge default attributes with user's attributes.
		$atts = shortcode_atts(
			array(
				'id'      => '97121',
				'height'  => '300',
				'width'   => '300',
				'nolinks' => '7',
			),
			$atts,
			'pzzwidget'
		);

		$zeef_url   = '//zeef.io/block/' . $atts['id'];
		$iframe_url = add_query_arg(
			array(
				'lpuid'        => $pzz_id,
				'max_links'    => $atts['nolinks'],
				'show_curator' => '0',
				'show_logo'    => '0',
			),
			$zeef_url
		);
		$widget     = '<!-- ZEEF widget start --><iframe id="widget_pzz" src="' . esc_url( $iframe_url ) . '" width="' . esc_attr( $atts['width'] ) . '" height="' . esc_attr( $atts['height'] ) . '" frameborder="0" scrolling="no"></iframe><!-- ZEEF widget end -->';
		return $widget;
	}
}


