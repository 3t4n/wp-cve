<?php
/*
Widget Name: Music Player for Easy Digital Downloads
Description: Insert a playlist with the downloads players.
Documentation: https://wordpress.dwbooster.com/content-tools/music-player-for-easy-digital-downloads#eddmp-playlist
*/

class SiteOrigin_EDDMP_Shortcode extends SiteOrigin_Widget {

	public function __construct() {
		global $wpdb;
		$options = array();
		$default = '';
		parent::__construct(
			'siteorigin-eddmp-shortcode',
			esc_html__( 'Music Player for Easy Digital Downloads', 'music-player-for-easy-digital-downloads' ),
			array(
				'description'   => esc_html__( 'Insert a playlist with the downloads players', 'music-player-for-easy-digital-downloads' ),
				'panels_groups' => array( 'music-player-for-easy-digital-downloads' ),
				'help'          => 'https://wordpress.dwbooster.com/content-tools/music-player-for-easy-digital-downloads#eddmp-playlist',
			),
			array(),
			array(
				'shortcode' => array(
					'type'    => 'text',
					'label'   => esc_html__( 'To include specific downloads in the playlist enter their IDs in the downloads_ids attributes, separated by comma symbols (,)', 'music-player-for-easy-digital-downloads' ),
					'default' => '[eddmp-playlist downloads_ids="*"  controls="track"]',
				),
			),
			plugin_dir_path( __FILE__ )
		);
	} // End __construct

	public function get_template_name( $instance ) {
		return 'siteorigin-eddmp-shortcode';
	} // End get_template_name

	public function get_style_name( $instance ) {
		return '';
	} // End get_style_name

} // End Class SiteOrigin_EDDMP_Shortcode

// Registering the widget
siteorigin_widget_register( 'siteorigin-eddmp-shortcode', __FILE__, 'SiteOrigin_EDDMP_Shortcode' );
