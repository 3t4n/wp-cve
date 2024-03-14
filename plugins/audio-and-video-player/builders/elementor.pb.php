<?php
namespace Elementor;

use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elementor_AVP_AUDIO_Widget extends Widget_Base {

	public function get_name() {
		return 'avp-audio';
	} // End get_name

	public function get_title() {
		return 'New Audio Player';
	} // End get_title

	public function get_icon() {
		return 'eicon-play';
	} // End get_icon

	public function get_categories() {
		return array( 'audio-and-video-player-cat' );
	} // End get_categories

	public function is_reload_preview_required() {
		return true;
	} // End is_reload_preview_required

	protected function register_controls() {
		$this->start_controls_section(
			'avp_section',
			array(
				'label' => __( 'Audio Player', 'codepeople-media-player' ),
			)
		);

		// Skins
		$skins    = array();
		$skin_dir = CPMP_PLUGIN_DIR . '/skins';
		if ( file_exists( $skin_dir ) ) {
			$d = dir( $skin_dir );
			while ( false !== ( $entry = $d->read() ) ) {
				if ( '.' != $entry && '..' != $entry && is_dir( $skin_dir . '/' . $entry ) ) {
					$this_skin = $skin_dir . '/' . $entry . '/';
					if ( file_exists( $this_skin ) ) {
						$skin_data                 = parse_ini_file( $this_skin . 'config.ini', true );
						$skins[ $skin_data['id'] ] = esc_html( $skin_data['name'] );
					}
				}
			}
			$d->close();
		}

		$this->add_control(
			'cpm_player_skin',
			array(
				'label'   => __( "Player's skin", 'codepeople-media-player' ),
				'type'    => 'cpmskinselect',
				'default' => 'device-player-skin',
				'options' => $skins,
				'event'   => esc_attr( 'cpm_select_skin("cpm_audio_player_shortcode");' ),
			)
		);

		$this->add_control(
			'cpm_audio_player_shortcode',
			array(
				'label'   => __( "Player's shortcode", 'codepeople-media-player' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 5,
				'default' => '',
			)
		);

		$this->add_control(
			'button',
			array(
				'show_label' => false,
				'text'       => __( 'Select files', 'codepeople-media-player' ),
				'type'       => 'cpmmediabutton',
				'event'      => esc_attr( 'cpm_get_media("audio", "cpm_audio_player_shortcode");' ),
			)
		);

		$this->add_control(
			'cpm_dir',
			array(
				'show_label' => true,
				'label'      => __( '- or - Enter a subdir of "Uploads"', 'codepeople-media-player' ),
				'type'       => Controls_Manager::TEXT,
				'classes'    => 'cpm-widefat cpm-audio',
			)
		);
		$this->end_controls_section();
	} // End register_controls

	private function _get_shortcode() {
		$settings = $this->get_settings_for_display();
		$shortcode = sanitize_text_field( $settings['cpm_audio_player_shortcode'] );
		if ( empty( $shortcode ) ) {
			$shortcode = '[cpm-player type="audio" /]';
		}

		return $shortcode;
	} // End _get_shortcode

	protected function render() {
		$shortcode = $this->_get_shortcode();
		if (
			isset( $_REQUEST['action'] ) &&
			(
				'elementor' == $_REQUEST['action'] ||
				'elementor_ajax' == $_REQUEST['action']
			)
		) {
			$url  = get_home_url( get_current_blog_id(), '', is_ssl() ? 'https' : 'http' );
			$url .= ( ( strpos( $url, '?' ) === false ) ? '?' : '&' ) . 'cpmp-avp-preview=' . urlencode( $shortcode );
			?>
			<div class="cpmp-iframe-container" style="position:relative;">
				<div class="cpmp-iframe-overlay" style="position:absolute;top:0;right:0;bottom:0;left:0;"></div>
				<iframe height="0" width="100%" src="<?php print esc_attr( $url ); ?>" scrolling="no">
			</div>
			<?php
		} else {
			print do_shortcode( shortcode_unautop( $shortcode ) );
		}

	} // End render

	public function render_plain_content() {
		echo wp_kses_data( $this->_get_shortcode() );
	} // End render_plain_content

} // End Elementor_AVP_AUDIO_Widget

class Elementor_AVP_VIDEO_Widget extends Widget_Base {

	public function get_name() {
		return 'avp-video';
	} // End get_name

	public function get_title() {
		return 'New Video Player';
	} // End get_title

	public function get_icon() {
		return 'eicon-video-camera';
	} // End get_icon

	public function get_categories() {
		return array( 'audio-and-video-player-cat' );
	} // End get_categories

	public function is_reload_preview_required() {
		return true;
	} // End is_reload_preview_required

	protected function register_controls() {
		$this->start_controls_section(
			'avp_section',
			array(
				'label' => __( 'Video Player', 'codepeople-media-player' ),
			)
		);

		// Skins
		$skins    = array();
		$skin_dir = CPMP_PLUGIN_DIR . '/skins';
		if ( file_exists( $skin_dir ) ) {
			$d = dir( $skin_dir );
			while ( false !== ( $entry = $d->read() ) ) {
				if ( '.' != $entry && '..' != $entry && is_dir( $skin_dir . '/' . $entry ) ) {
					$this_skin = $skin_dir . '/' . $entry . '/';
					if ( file_exists( $this_skin ) ) {
						$skin_data                 = parse_ini_file( $this_skin . 'config.ini', true );
						$skins[ $skin_data['id'] ] = esc_html( $skin_data['name'] );
					}
				}
			}
			$d->close();
		}

		$this->add_control(
			'cpm_player_skin',
			array(
				'label'   => __( "Player's skin", 'codepeople-media-player' ),
				'type'    => 'cpmskinselect',
				'default' => 'classic-skin',
				'options' => $skins,
				'event'   => esc_attr( 'cpm_select_skin("cpm_video_player_shortcode");' ),
			)
		);

		$this->add_control(
			'cpm_video_player_shortcode',
			array(
				'label'   => __( "Player's shortcode", 'codepeople-media-player' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 5,
				'default' => '',
			)
		);

		$this->add_control(
			'button',
			array(
				'show_label' => false,
				'text'       => __( 'Select files', 'codepeople-media-player' ),
				'type'       => 'cpmmediabutton',
				'event'      => esc_attr( 'cpm_get_media("video", "cpm_video_player_shortcode");' ),
			)
		);

		$this->add_control(
			'cpm_dir',
			array(
				'show_label' => true,
				'label'      => __( '- or - Enter a subdir of "Uploads"', 'codepeople-media-player' ),
				'type'       => Controls_Manager::TEXT,
				'classes'    => 'cpm-widefat cpm-video',
			)
		);

		$this->end_controls_section();
	} // End register_controls

	private function _get_shortcode() {
		 $settings = $this->get_settings_for_display();
		$shortcode = sanitize_text_field( $settings['cpm_video_player_shortcode'] );
		if ( empty( $shortcode ) ) {
			$shortcode = '[cpm-player type="video" /]';
		}

		return $shortcode;
	} // End _get_shortcode

	protected function render() {
		$shortcode = $this->_get_shortcode();
		if (
			isset( $_REQUEST['action'] ) &&
			(
				'elementor' == $_REQUEST['action'] ||
				'elementor_ajax' == $_REQUEST['action']
			)
		) {
			$url  = get_home_url( get_current_blog_id(), '', is_ssl() ? 'https' : 'http' );
			$url .= ( ( strpos( $url, '?' ) === false ) ? '?' : '&' ) . 'cpmp-avp-preview=' . urlencode( $shortcode );
			?>
			<div class="cpmp-iframe-container" style="position:relative;">
				<div class="cpmp-iframe-overlay" style="position:absolute;top:0;right:0;bottom:0;left:0;"></div>
				<iframe height="0" width="100%" src="<?php print esc_attr( $url ); ?>" scrolling="no">
			</div>
			<?php
		} else {
			print do_shortcode( shortcode_unautop( $shortcode ) );
		}

	} // End render

	public function render_plain_content() {
		echo wp_kses_data( $this->_get_shortcode() );
	} // End render_plain_content

} // End Elementor_AVP_VIDEO_Widget

class Elementor_AVP_GALLERY_Widget extends Widget_Base {

	public function get_name() {
		return 'avp-gallery';
	} // End get_name

	public function get_title() {
		return 'Insert Player From Gallery';
	} // End get_title

	public function get_icon() {
		return 'eicon-media-carousel';
	} // End get_icon

	public function get_categories() {
		return array( 'audio-and-video-player-cat' );
	} // End get_categories

	public function is_reload_preview_required() {
		return true;
	} // End is_reload_preview_required

	protected function register_controls() {
		global $wpdb;

		$this->start_controls_section(
			'avp_section',
			array(
				'label' => __( 'Player From Gallery', 'codepeople-media-player' ),
			)
		);

		$players        = array();
		$default_player = '';

		$sql    = 'SELECT id, player_name FROM ' . $wpdb->prefix . CPMP_PLAYER . ';';
		$result = $wpdb->get_results( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if ( count( $result ) ) {
			foreach ( $result as $player ) {
				$players[ $player->id ] = stripslashes( $player->player_name );
				if ( empty( $default_player ) ) {
					$default_player = $player->id;
				}
			}
		}

		$this->add_control(
			'cpm_player_list',
			array(
				'label'       => __( 'Select the player', 'codepeople-media-player' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => $default_player,
				'options'     => $players,
				'description' => '<a href="options-general.php?page=codepeople-media-player.php">' . __( 'Create or edit players', 'codepeople-media-player' ) . '</a>',
			)
		);

		$this->end_controls_section();
	} // End register_controls

	private function _get_shortcode() {
		 $shortcode = '';
		$settings   = $this->get_settings_for_display();
		$player_id  = sanitize_text_field( $settings['cpm_player_list'] );
		if ( ! empty( $player_id ) ) {
			$shortcode = '[cpm-player id="' . esc_attr( $player_id ) . '" /]';
		}
		return $shortcode;
	} // End _get_shortcode

	protected function render() {
		$shortcode = $this->_get_shortcode();
		if (
			isset( $_REQUEST['action'] ) &&
			(
				'elementor' == $_REQUEST['action'] ||
				'elementor_ajax' == $_REQUEST['action']
			)
		) {
			$url  = get_home_url( get_current_blog_id(), '', is_ssl() ? 'https' : 'http' );
			$url .= ( ( strpos( $url, '?' ) === false ) ? '?' : '&' ) . 'cpmp-avp-preview=' . urlencode( $shortcode );
			?>
			<div class="cpmp-iframe-container" style="position:relative;">
				<div class="cpmp-iframe-overlay" style="position:absolute;top:0;right:0;bottom:0;left:0;"></div>
				<iframe height="0" width="100%" src="<?php print esc_attr( $url ); ?>" scrolling="no">
			</div>
			<?php
		} else {
			print do_shortcode( shortcode_unautop( $shortcode ) );
		}

	} // End render

	public function render_plain_content() {
		echo wp_kses_data( $this->_get_shortcode() );
	} // End render_plain_content

} // End Elementor_AVP_GALLERY_Widget

// Register the widgets
Plugin::instance()->widgets_manager->register( new Elementor_AVP_AUDIO_Widget() );
Plugin::instance()->widgets_manager->register( new Elementor_AVP_VIDEO_Widget() );
Plugin::instance()->widgets_manager->register( new Elementor_AVP_GALLERY_Widget() );
