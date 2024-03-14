<?php

class ThemeRain_Custom_CSS {

	protected $customizer;
	protected $meta_boxes;

	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'print_css' ), 20 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'print_css' ) );
	}

	public function init() {
		$this->customizer = $this->get_customizer();
		$this->meta_boxes = $this->get_meta_boxes();
	}

	public function get_customizer() {
		return apply_filters( 'themerain_customizer', array() );
	}

	public function get_meta_boxes() {
		return apply_filters( 'themerain_meta_boxes', array() );
	}

	public function print_css() {
		$customizer = array();
		$meta_boxes = array();

		if ( is_array( $this->customizer ) && $this->customizer ) {
			foreach ( $this->customizer as $section ) {
				foreach( $section['controls'] as $control ) {
					if ( isset( $control['output'] ) ) {
						$output = $control['output'];
						$id     = $control['id'];
						$std    = isset( $control['std'] ) ? $control['std'] : '';
						$val    = get_theme_mod( $id ) ? get_theme_mod( $id ) : $std;

						if ( 'default' !== $val && $val ) {
							if ( strpos( $val, 'cf-' ) !== false ) {
								$val = str_replace( 'cf-', '', $val );
							}

							if ( strpos( $val, 'af-' ) !== false ) {
								$val = str_replace( 'af-', '', $val );
							}

							if ( strpos( $val, 'gf-' ) !== false ) {
								$val = str_replace( ['gf-', '-'], ['', ' '], $val );
							}

							$customizer[$output[0]][$output[1]] = $val;
						}
					}
				}
			}
		}

		if ( is_array( $this->meta_boxes ) && $this->meta_boxes ) {
			foreach ( $this->meta_boxes as $meta_box ) {
				foreach( $meta_box['fields'] as $field ) {
					if ( isset( $field['output'] ) ) {
						$output = $field['output'];
						$id     = ( is_home() && ! is_front_page() ) ? get_option( 'page_for_posts' ) : $this->get_the_id();
						$val    = ( $id ) ? get_post_meta( $id, $field['id'], true ) : '';

						if ( $val || '0' === $val ) {
							$meta_boxes[$output[0]][$output[1]] = $val;
						}
					}
				}
			}
		}

		$css  = '/* Custom CSS */ ';
		$css .= $this->build_css( $customizer );
		$css .= $this->build_css( $meta_boxes );

		wp_add_inline_style( 'themerain-style', $css );
		wp_add_inline_style( 'themerain-style-editor', $css );
	}

	public function build_css( $array ) {
		$output = '';

		if ( ! $array ) {
			return;
		}

		foreach ( $array as $selector => $vars ) {
			$vars_output = '';

			foreach ( $vars as $var => $val ) {
				$vars_output .= sprintf( '%s: %s; ', $var, $val );
			}

			$output .= sprintf( '%s { %s } ', $selector, $vars_output );
		}

		return $output;
	}

	public function get_the_id() {
		if ( in_the_loop() ) {
			$post_id = get_the_ID();
		} else {
			global $wp_query;
			$post_id = $wp_query->get_queried_object_id();
		}

		return $post_id;
	}

}

new ThemeRain_Custom_CSS();
