<?php
/**
 * Handle custom styles for themes
 *
 * @package   BoldBlocks
 * @author    Phi Phan <mrphipv@gmail.com>
 * @copyright Copyright (c) 2022, Phi Phan
 */

namespace BoldBlocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( Theme::class ) ) :
	/**
	 * The controller class for theme.
	 */
	class Theme extends CoreComponent {
		/**
		 * Supported themes.
		 *
		 * @var array
		 */
		protected $themes = [
			'blockbase'         => [
				'root_padding' => 'var(--wp--custom--gap--horizontal)',
			],
			'twentytwentytwo'   => [
				'root_padding' => 'var(--wp--custom--spacing--outer)',
			],
			'twentytwentythree' => [
				'root_padding' => 'var(--wp--style--root--padding-left)',
			],
			'twentytwentyfour'  => [
				'root_padding' => 'var(--wp--style--root--padding-left)',
			],
		];

		/**
		 * Run main hooks
		 *
		 * @return void
		 */
		public function run() {
			// Enqueue styles for frontend.
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts_for_theme' ], 99999 );

			// Enqueue styles for the iframe editor.
			add_filter( 'block_editor_settings_all', [ $this, 'enqueue_style_for_the_editor' ] );

			// Add transparency to color palette.
			add_filter( 'wp_theme_json_data_default', [ $this, 'add_transparency_to_color_palette_theme_json_data' ] );

			// Bring back some default settings from theme json data deafault.
			add_filter( 'wp_theme_json_data_theme', [ $this, 'alter_the_theme_json_data_from_theme' ] );
		}

		/**
		 * Enqueue custom styles for popular themes.
		 *
		 * @return void
		 */
		public function enqueue_scripts_for_theme() {
			$css_variables_stylesheet = '';

			// Get current theme.
			$theme_slug = get_stylesheet();

			$theme_style_handle = '';
			if ( isset( wp_styles()->registered[ $theme_slug . '-style' ] ) ) {
				$theme_style_handle = $theme_slug . '-style';
			} elseif ( isset( wp_styles()->registered['global-styles'] ) ) {
				$theme_style_handle = 'global-styles';
			} elseif ( isset( wp_styles()->registered['global-styles-css-custom-properties'] ) ) {
				$theme_style_handle = 'global-styles-css-custom-properties';
			}

			// Has the style handle.
			if ( $theme_style_handle ) {
				$css_variables_stylesheet = $this->get_custom_css_variables();

				// Enqueue css variables.
				if ( $css_variables_stylesheet ) {
					wp_add_inline_style( $theme_style_handle, $css_variables_stylesheet );
				}
			}
		}

		/**
		 * Enqueue scripts/styles for the editor
		 *
		 * @param array $editor_settings
		 * @return void
		 */
		public function enqueue_style_for_the_editor( $editor_settings ) {
			// Get css variables.
			$css_variables_stylesheet = $this->get_custom_css_variables();

			if ( $css_variables_stylesheet ) {
				$editor_settings['styles'][] = [ 'css' => $css_variables_stylesheet ];
			}

			return $editor_settings;
		}

		/**
		 * Enqueue custom css variables.
		 *
		 * @return void
		 */
		public function get_custom_css_variables() {
			$stylesheet = '';

			// Get theme settings.
			$theme_settings   = \WP_Theme_JSON_Resolver::get_merged_data()->get_settings();
			$use_root_padding = _wp_array_get( $theme_settings, [ 'useRootPaddingAwareAlignments' ], false );

			// Get layout style.
			$stylesheet .= $this->get_layout_style( $use_root_padding, $theme_settings );

			// Get background style.
			$stylesheet .= $this->get_background_style( $use_root_padding );

			// Get transparent color style.
			$theme_colors = _wp_array_get( $theme_settings, [ 'color', 'palette' ] );
			if ( ! array_search( 'transparent', array_column( $theme_colors, 'color' ), true ) ) {
				$stylesheet .= '.has-transparent-color { color: transparent !important; } .has-transparent-background-color { background-color: transparent !important; } .has-transparent-border-color { border-color: transparent !important; }';
			}

			return $stylesheet;
		}

		/**
		 * Get layout style for custom blocks
		 *
		 * @param boolean $use_root_padding
		 * @param array   $theme_settings
		 * @return string
		 */
		private function get_layout_style( $use_root_padding, $theme_settings ) {
			$css = '';

			// Get current theme.
			$theme_slug = get_stylesheet();

			$spacing_baseline = _wp_array_get( $theme_settings, [ 'custom', 'boldblocks', 'spacing', 'baseline' ], '' );
			if ( empty( $spacing_baseline ) ) {
				if ( \in_array( $theme_slug, array_keys( $this->themes ), true ) ) {
					$spacing_baseline = _wp_array_get( $this->themes, [ $theme_slug, 'root_padding' ], '' );
				} else {
					// Get theme json.
					$theme_json_raw = \WP_Theme_JSON_Resolver::get_merged_data()->get_raw_data();

					// Get root horizonal padding.
					$root_padding = _wp_array_get( $theme_json_raw, [ 'styles', 'spacing', 'padding', 'left' ] );

					// Use new root horizonal padding.
					if ( $root_padding ) {
						$spacing_baseline = $root_padding;
					} else {
						// Most of old block based themes using spacing->outer or gap->horizontal for the main horizontal padding.
						$theme_spacing_baseline = _wp_array_get( $theme_settings, [ 'custom', 'spacing', 'outer' ] );
						if ( $theme_spacing_baseline ) {
							$spacing_baseline = 'var(--wp--custom--spacing--outer)';
						} else {
							$theme_spacing_baseline = _wp_array_get( $theme_settings, [ 'custom', 'gap', 'horizontal' ] );
							if ( $theme_spacing_baseline ) {
								$spacing_baseline = 'var(--wp--custom--gap--horizontal)';
							}
						}
					}
				}
			}

			if ( $spacing_baseline ) {
				$css .= 'body{--wp--custom--boldblocks--spacing--baseline:' . $spacing_baseline . ';}';
			} else {
				$css .= 'body{--wp--custom--boldblocks--spacing--baseline: clamp(1.5rem, 5vw, 2.5rem);}';
			}

			if ( ! $use_root_padding ) {
				// Right and left padding are applied to the the block with alignfull class.
				$css .= '.wp-block-boldblocks-custom.alignfull { padding-right: var(--wp--custom--boldblocks--spacing--baseline); padding-left: var(--wp--custom--boldblocks--spacing--baseline); }';

				// Alignfull descestors of the container with left and right padding have negative margins so they can still be full width.
				$css .= '.wp-site-blocks .wp-block-boldblocks-custom.alignfull .alignfull, .wp-block-boldblocks-custom.alignfull .alignfull { width: unset; margin-right: calc(var(--wp--custom--boldblocks--spacing--baseline) * -1) !important; margin-left: calc(var(--wp--custom--boldblocks--spacing--baseline) * -1) !important; }';
			} else {
				// Force horizontal paddings to alignfull,has-global-padding blocks.
				$css .= '.wp-block-boldblocks-custom.alignfull.has-global-padding { padding-right: var(--wp--custom--boldblocks--spacing--baseline); padding-left: var(--wp--custom--boldblocks--spacing--baseline); }';

				// Make the nested alignfull blocks fullwidth.
				$css .= '.wp-block-boldblocks-custom.alignfull.has-global-padding > .alignfull { margin-right: calc(var(--wp--custom--boldblocks--spacing--baseline) * -1) !important; margin-left: calc(var(--wp--custom--boldblocks--spacing--baseline) * -1) !important; }';
			}

			return $css;
		}

		/**
		 * Get spacing style for has-background custom blocks
		 *
		 * @param boolean $use_root_padding
		 * @return string
		 */
		private function get_background_style( $use_root_padding ) {
			$css = '';

			$background_padding = 'var(--wp--custom--boldblocks--spacing--background, clamp(1.25rem, 2.5vw, 2rem))';

			if ( ! $use_root_padding ) {
				// Apply spacing for child blocks that have background.
				$css .= '.wp-block-boldblocks-custom:not(.alignfull).has-parent.has-background,.wp-block-boldblocks-custom:not(.alignfull).has-parent.bb\:has-background {padding: ' . $background_padding . ';}';

				// Apply spacing for standalone blocks that have background.
				$css .= '.wp-block-boldblocks-custom:not(.alignfull):not(.has-parent).has-background,.wp-block-boldblocks-custom:not(.alignfull):not(.has-parent).bb\:has-background {padding-left: ' . $background_padding . ';padding-right: ' . $background_padding . ';}';
			} else {
				// Apply horizontal spacing for cbb blocks that have background.
				$css .= '.wp-block-boldblocks-custom:not(.alignfull).has-background,.wp-block-boldblocks-custom:not(.alignfull).bb\:has-background {padding-left: ' . $background_padding . ';padding-right: ' . $background_padding . ';}';

				// Apply vertical spacing for cbb child blocks that have background.
				$css .= '.wp-block-boldblocks-custom:not(.alignfull).has-parent.has-background,.wp-block-boldblocks-custom:not(.alignfull).has-parent.bb\:has-background {padding-top: ' . $background_padding . ';padding-bottom: ' . $background_padding . ';}';

			}

			return $css;
		}

		/**
		 * Add transparency to the default color palette
		 *
		 * @param array $theme_json
		 * @return array
		 */
		public function add_transparency_to_color_palette_theme_json_data( $theme_json ) {
			$theme_json_data = $theme_json->get_data();
			$color_palette   = $theme_json_data['settings']['color']['palette']['default'] ?? [];
			if ( ! array_search( 'transparent', array_column( $color_palette, 'color' ), true ) ) {
				$color_palette[] = [
					'slug'  => 'transparent',
					'color' => 'transparent',
					'name'  => __( 'Transparent', 'content-blocks-builder' ),
				];

				$updating_data = array(
					'version'  => 2,
					'settings' => array(
						'color' => array(
							'palette' => $color_palette,
						),
					),
				);

				return $theme_json->update_with( $updating_data );
			}

			return $theme_json;
		}

		/**
		 * Bring back some default settings from theme json data deafault.
		 *
		 * @param array $theme_json
		 * @return array
		 */
		public function alter_the_theme_json_data_from_theme( $theme_json ) {
			// Load default settings.
			$default_settings = [
				'color' => [
					'background'       => true,
					'text'             => true,
					'link'             => true,
					'button'           => true,
					'caption'          => true,
					'custom'           => true,
					'customDuotone'    => true,
					'customGradient'   => true,
					'defaultDuotone'   => true,
					'defaultPalette'   => true,
					'defaultGradients' => true,
				],
			];

			$updating_settings = apply_filters( 'cbb_theme_json_data_default_settings', $default_settings );

			if ( $updating_settings ) {
				return $theme_json->update_with(
					[
						'version'  => 2,
						'settings' => $updating_settings,
					]
				);
			}

			return $theme_json;
		}
	}
endif;
