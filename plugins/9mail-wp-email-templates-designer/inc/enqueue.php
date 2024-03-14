<?php

namespace EmTmplF\Inc;

defined( 'ABSPATH' ) || exit;

class Enqueue {
	protected static $instance = null;
	protected $slug;
	protected $cache_posts;

	public function __construct() {
		$this->slug = EMTMPL_CONST['assets_slug'];
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_run_script' ], PHP_INT_MAX );
	}

	public static function instance() {
		return self::$instance == null ? self::$instance = new self() : self::$instance;
	}

	public function register_scripts() {
		$suffix = WP_DEBUG ? '' : '.min';

		$lib_styles = [
			'button',
			'tab',
			'input',
			'icon',
			'segment',
			'image',
			'modal',
			'dimmer',
			'transition',
			'menu',
			'grid',
			'search',
			'message',
			'loader',
			'label',
			'select2',
			'header',
			'accordion',
			'dropdown',
			'checkbox',
			'form',
			'table',
		];

		foreach ( $lib_styles as $style ) {
			wp_register_style( $this->slug . $style, EMTMPL_CONST['libs_url'] . $style . '.min.css', '', EMTMPL_CONST['version'] );
		}

		//*************************************//
		$styles = [ 'admin', 'email-builder' ];

		foreach ( $styles as $style ) {
			wp_register_style( $this->slug . $style, EMTMPL_CONST['dist_url'] . $style . $suffix . '.css', '', EMTMPL_CONST['version'] );
		}

		//*************************************//

		$lib_scripts = [ 'select2', 'transition', 'dimmer', 'accordion', 'tab', 'modal' ];

		foreach ( $lib_scripts as $script ) {
			wp_register_script( $this->slug . $script, EMTMPL_CONST['libs_url'] . $script . '.min.js', [ 'jquery' ], EMTMPL_CONST['version'] );
		}

		//*************************************//

		$scripts = [ 'email-builder' => [ 'jquery', 'jquery-ui-sortable', 'jquery-ui-draggable' ], 'components' => [], 'properties' => [], 'inputs' => [], 'run' => [] ];
		foreach ( $scripts as $script => $depend ) {
			wp_register_script( $this->slug . $script, EMTMPL_CONST['dist_url'] . $script . $suffix . '.js', $depend, EMTMPL_CONST['version'] );
		}
	}

	public function enqueue_scripts() {
		$screen_id = get_current_screen()->id;

		if ( ! in_array( $screen_id, [ 'wp_email_tmpl', 'edit-wp_email_tmpl' ] ) ) {
			return;
		}

		global $post;

		$this->register_scripts();

		$enqueue_scripts = $enqueue_styles = [];
		$localize_script = $inline_handle = $css = '';
		$params          = [ 'ajaxUrl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'emtmpl_nonce' ) ];

		switch ( $screen_id ) {
			case 'wp_email_tmpl':
				wp_enqueue_editor();
				wp_enqueue_media();
				wp_enqueue_script( 'iris' );

				$enqueue_styles  = [ 'tab', 'menu', 'accordion', 'select2', 'dimmer', 'transition', 'modal', 'button', 'email-builder' ];
				$enqueue_scripts = [ 'select2', 'transition', 'dimmer', 'accordion', 'tab', 'modal', 'jqColorPicker', 'inputs', 'email-builder', 'properties', 'components' ];
				$localize_script = $inline_handle = 'email-builder';

				$params = array_merge( $params, [
					'post'                         => EMTMPL_CONST['img_url'] . 'post.png',
					'placeholder'                  => EMTMPL_CONST['img_url'] . 'placeholder.jpg',
					'adminBarStt'                  => Utils::get_admin_bar_stt(),
					'homeUrl'                      => home_url(),
					'siteUrl'                      => site_url(),
					'adminEmail'                   => get_bloginfo( 'admin_email' ),
					'shortcode'                    => array_keys( Utils::shortcodes() ),
					'shortcode_for_replace'        => array_merge( Utils::shortcodes(), Utils::get_register_shortcode_for_replace() ),
					'sc_3rd_party'                 => Utils::get_register_shortcode_for_builder(),
					'sc_3rd_party_for_text_editor' => Utils::get_register_shortcode_for_text_editor(),
					'samples'                      => Email_Samples::sample_templates(),
					'subjects'                     => Email_Samples::default_subject(),
					'i18n'                         => I18n::init(),
//					'hide_rule'                    => Utils::get_hide_rules_data(),
//					'accept_elements'              => Utils::get_accept_elements_data(),
				] );

				$images_map = include_once plugin_dir_path( __FILE__ ) . 'images.php';

				foreach ( $images_map['social_icons'] as $type => $data ) {
					foreach ( $data as $key => $text ) {
						$url = $key ? EMTMPL_CONST['img_url'] . $key . '.png' : '';

						$params['social_icons'][ $type ][] = [ 'id' => $url, 'text' => $text, 'slug' => $key ];
						$css                               .= ".mce-i-{$key}{background: url('{$url}') !important; background-size: cover !important;}";

					}
				}

				foreach ( $images_map['infor_icons'] as $type => $data ) {
					foreach ( $data as $key => $text ) {
						$url                              = $key ? EMTMPL_CONST['img_url'] . $key . '.png' : '';
						$params['infor_icons'][ $type ][] = [ 'id' => $url, 'text' => $text, 'slug' => $key ];
						$css                              .= ".mce-i-{$key}{background: url('{$url}') !important; background-size: cover !important;}";
					}
				}

				$email_structure = get_post_meta( $post->ID, 'emtmpl_email_structure', true );

				if ( ! empty( $email_structure ) ) {
					wp_localize_script( $this->slug . $localize_script, 'viWecLoadTemplate', [ $email_structure ] );
				}

				break;

			case 'edit-wp_email_tmpl':
				$enqueue_styles = [ 'form', 'segment', 'button', 'icon' ];
				wp_add_inline_style( 'villatheme-support', '#emtmpl-in-all-email-page{margin-left:160px;padding:0 20px 40px}.folded #emtmpl-in-all-email-page{margin-left:35px}' );
				break;
		}

		foreach ( $enqueue_scripts as $script ) {
			wp_enqueue_script( $this->slug . $script );
		}

		foreach ( $enqueue_styles as $style ) {
			wp_enqueue_style( $this->slug . $style );
		}

		if ( $localize_script ) {
			wp_localize_script( $this->slug . $localize_script, 'viWecParams', $params );
		}
		if ( $inline_handle ) {
			wp_add_inline_style( $this->slug . $inline_handle, $css );
		}
	}

	public function enqueue_run_script() {
		if ( get_current_screen()->id === 'wp_email_tmpl' ) {
			wp_enqueue_script( $this->slug . 'run' );
		}
	}
}
