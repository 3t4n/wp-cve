<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elementor_EDDMP_Widget extends Widget_Base {

	public function get_name() {
		return 'music-player-for-easy-digital-downloads';
	} // End get_name

	public function get_title() {
		return 'Playlist';
	} // End get_title

	public function get_icon() {
		return 'eicon-video-playlist';
	} // End get_icon

	public function get_categories() {
		return array( 'music-player-for-easy-digital-downloads-cat' );
	} // End get_categories

	public function is_reload_preview_required() {
		return true;
	} // End is_reload_preview_required

	protected function register_controls() {
		global $wpdb;

		$this->start_controls_section(
			'eddmp_section',
			array(
				'label' => esc_html__( 'Music Player For Easy Digital Downloads', 'music-player-for-easy-digital-downloads' ),
			)
		);

		$this->add_control(
			'shortcode',
			array(
				'label'       => esc_html__( 'Music Player For Easy Digital Downloads', 'music-player-for-easy-digital-downloads' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '[eddmp-playlist downloads_ids="*"  controls="track"]',
				'description' => '<p>' . esc_html__( 'To include specific downloads in the playlist enter their IDs in the downloads_ids attributes, separated by comma symbols (,)', 'music-player-for-easy-digital-downloads' ) . '</p><p>' . esc_html__( 'More information visiting the follwing link: ', 'music-player-for-easy-digital-downloads' ) . '<br><a href="https://wordpress.dwbooster.com/content-tools/music-player-for-easy-digital-downloads#eddmp-playlist" target="_blank">' . esc_html__( 'CLICK HERE', 'music-player-for-easy-digital-downloads' ) . '</a></p>',
			)
		);

		$this->end_controls_section();
	} // End register_controls

	private function _get_shortcode() {
		 $settings = $this->get_settings_for_display();
		$shortcode = $settings['shortcode'];
		$shortcode = preg_replace( '/[\r\n]/', ' ', $shortcode );
		return trim( $shortcode );
	} // End _get_shortcode

	protected function render() {
		$shortcode = sanitize_text_field( $this->_get_shortcode() );
		if (
			isset( $_REQUEST['action'] ) &&
			(
				'elementor' == $_REQUEST['action'] ||
				'elementor_ajax' == $_REQUEST['action']
			)
		) {

			$url  = EDDMP_WEBSITE_URL;
			$url .= ( ( strpos( $url, '?' ) === false ) ? '?' : '&' ) . 'eddmp-preview=' . urlencode( $shortcode );
			?>
			<div class="eddmp-iframe-container" style="position:relative;">
				<div class="eddmp-iframe-overlay" style="position:absolute;top:0;right:0;bottom:0;left:0;"></div>
				<iframe height="0" width="100%" src="<?php print esc_attr( $url ); ?>" scrolling="no">
			</div>
			<?php
		} else {
			print do_shortcode( shortcode_unautop( $shortcode ) );
		}

	} // End render

	public function render_plain_content() {
		echo $this->_get_shortcode(); // phpcs:ignore WordPress.Security.EscapeOutput
	} // End render_plain_content

} // End Elementor_EDDMP_Widget


// Register the widgets
Plugin::instance()->widgets_manager->register( new Elementor_EDDMP_Widget() );
