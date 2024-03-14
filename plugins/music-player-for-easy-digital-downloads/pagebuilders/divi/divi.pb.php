<?php
class EDDMP_DIVI extends ET_Builder_Module {


	public $slug       = 'et_pb_eddmp_divi_module';
	public $vb_support = 'on';

	public function init() {
		$this->name                   = esc_html__( 'Music Player for Easy Digital Downloads', 'music-player-for-easy-digital-downloads' );
		$this->settings_modal_toggles = array(
			'general' => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Playlist', 'music-player-for-easy-digital-downloads' ),
				),
			),
		);
	}

	public function get_fields() {
		global $wpdb;
		return array(
			'eddmp_downloads_ids' => array(
				'label'           => esc_html__( 'Downloads ids', 'music-player-for-easy-digital-downloads' ),
				'type'            => 'text',
				'default'         => '*',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter the downloads ids separated by comma, or the * sign to include all downloads.', 'music-player-for-easy-digital-downloads' ),
				'toggle_slug'     => 'main_content',
			),
			'eddmp_attributes'    => array(
				'label'           => esc_html__( 'Additional attributes', 'music-player-for-easy-digital-downloads' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => 'controls="track" layout="new"',
				'toggle_slug'     => 'main_content',
			),
		);
	}

	public function render( $unprocessed_props, $content = null, $render_slug = null ) {
		$output    = '';
		$downloads = sanitize_text_field( $this->props['eddmp_downloads_ids'] );
		if ( empty( $downloads ) ) {
			$downloads = '*';
		}

		$output = '[eddmp-playlist downloads_ids="' . esc_attr( $downloads ) . '"';

		$attributes = sanitize_text_field( $this->props['eddmp_attributes'] );
		if ( ! empty( $attributes ) ) {
			$output .= ' ' . $attributes;
		}

		$output .= ']';
		return do_shortcode( $output );
	}
}

new EDDMP_DIVI();
