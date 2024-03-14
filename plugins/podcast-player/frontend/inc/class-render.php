<?php
/**
 * Podcast player display class.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/public
 */

namespace Podcast_Player\Frontend\Inc;

use Podcast_Player\Helper\Functions\Getters as Get_Fn;
use Podcast_Player\Helper\Functions\Markup as Markup_Fn;
use Podcast_Player\Helper\Functions\Utility as Utility_Fn;
use Podcast_Player\Helper\Functions\Validation as Validation_Fn;

/**
 * Display podcast player instance.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/public
 * @author     vedathemes <contact@vedathemes.com>
 */
class Render {

	/**
	 * Holds current pp instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var int
	 */
	public $instance = null;

	/**
	 * Holds current instance display args.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var array
	 */
	public $args = array();

	/**
	 * Holds current instance scripts data.
	 *
	 * @since  4.6.0
	 * @access public
	 * @var array
	 */
	public $data = array();

	/**
	 * Holds current pp instance items.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var array
	 */
	public $items = array();

	/**
	 * Holds current pp instance header info.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var array
	 */
	public $info = array();

	/**
	 * Constructor method.
	 *
	 * @since  3.3.0
	 *
	 * @param array $props podcast player display props.
	 */
	public function __construct( $props ) {

		// Back Compatibility.
		if ( is_array( $props ) && 6 === count( $props ) ) {
			$props[] = '';
		}

		// Extract all props.
		list( $title, $desc, $link, $items, $instance, $args, $data ) = $props;

		// Define variables.
		$this->instance = $instance;
		$this->args     = $args;
		$this->items    = $items;
		$this->data     = $data;
		$this->info     = array(
			'title'       => $title,
			'description' => $desc,
			'link'        => $link,
		);

		// Add dynamic CSS for current player's instance.
		$inst_class = Instance_Counter::get_instance();
		$styles     = $this->get_dynamic_css();
		$inst_class->add_css( $styles );

		// Get podcast player wrapper classes.
		$classes = $this->get_wrapper_classes();
		$this->display_podcast_player( $classes, $styles );
	}

	/**
	 * Get podcast player wrapper classes.
	 *
	 * @since  1.0.0
	 */
	private function get_wrapper_classes() {

		$style      = $this->args['display-style'];
		$is_basic   = ! $style || 'legacy' === $style || 'modern' === $style;
		$first_item = isset( $this->items[0] ) ? $this->items[0] : '';
		$mediatype  = $first_item ? $first_item['mediatype'] : '';
		$noshare    = $this->args['hide-download'] && $this->args['hide-social'];

		// Initiate with default class.
		$cls = array( 'pp-podcast' );

		// Add user defined custom classes.
		if ( isset( $this->args['classes'] ) && $this->args['classes'] ) {
			$cls[] = $this->args['classes'];
		}

		// Condition where only single episode is displayed.
		$single_display = ( 1 === $this->args['number'] ) && $this->args['hide-loadmore'];

		// Add class for single episode.
		if ( Validation_Fn::is_single_episode_layout( count( $this->items ), $style, $single_display ) ) {
			$cls[] = 'single-episode';
		} elseif ( 1 === count( $this->items ) && 'link' === $this->args['fetch-method'] ) {
			$cls[] = 'single-audio';
		}

		// Add more classes conditionally.
		$cls[] = $this->is_header_available() ? 'has-header' : 'no-header';
		$cls[] = $this->is_header_visible() ? 'header-visible' : 'header-hidden';
		$cls[] = ! $this->args['hide-featured'] ? 'has-featured' : '';
		$cls[] = $style;
		$cls[] = $style ? 'special-style' : '';
		$cls[] = $is_basic ? 'playerview' : '';
		$cls[] = $is_basic && $this->args['list-default'] ? 'list-default' : '';
		$cls[] = $mediatype ? 'media-' . $mediatype : '';
		$cls[] = $noshare ? 'hide-share' : '';
		$cls[] = ! $noshare && $this->args['hide-download'] ? 'hide-download' : '';
		$cls[] = ! $noshare && $this->args['hide-social'] ? 'hide-social' : '';
		$cls[] = $this->args['hide-description'] ? 'hide-description' : '';
		$cls[] = $this->args['hide-content'] ? 'hide-content' : '';

		if ( $this->args['accent-color'] ) {

			// Check if dark color is in cotrast with accent color.
			$is_dark_contrast = Validation_Fn::is_dark_contrast( $this->args['accent-color'] );
			if ( $is_dark_contrast ) {
				$cls[] = 'light-accent';
			}
		}

		/**
		 * Podcast player display wrapper HTML classes.
		 *
		 * @since 3.3.0
		 *
		 * @param array  $cls        Podcast player display wrapper HTML classes.
		 * @param array  $this->args Settigs for Podcast display instance.
		 */
		$cls = apply_filters( 'podcast_player_wrapper_classes', $cls, $this->args );
		$cls = array_filter( array_map( 'esc_attr', $cls ) );
		return join( ' ', $cls );
	}

	/**
	 * Get dynamic css for current podcast player instance.
	 *
	 * @since  1.0.0
	 */
	private function get_dynamic_css() {
		$css  = '';
		$inst = absint( $this->instance );
		$id   = "#pp-podcast-{$inst}";
		$mod  = ".modal-{$inst}";
		$amod = ".aux-modal-{$inst}";

		if ( $this->args['accent-color'] ) {
			$color = sanitize_hex_color( $this->args['accent-color'] );
			$rgb   = Utility_Fn::hex_to_rgb( $color, true );
			$css  .= sprintf(
				'
				%1$s a,
				.pp-modal-window %4$s a,
				.pp-modal-window %5$s a,
				%1$s .ppjs__more {
					color: %2$s;
				}
				%1$s:not(.modern) .ppjs__audio .ppjs__button.ppjs__playpause-button button *,
				%1$s:not(.modern) .ppjs__audio .ppjs__button.ppjs__playpause-button button:hover *,
				%1$s:not(.modern) .ppjs__audio .ppjs__button.ppjs__playpause-button button:focus *,
				.pp-modal-window %4$s .ppjs__audio .ppjs__button.ppjs__playpause-button button *,
				.pp-modal-window %4$s .ppjs__audio .ppjs__button.ppjs__playpause-button button:hover *,
				.pp-modal-window %4$s .ppjs__audio .ppjs__button.ppjs__playpause-button button:focus *,
				.pp-modal-window %5$s .pod-entry__play *,
				.pp-modal-window %5$s .pod-entry__play:hover * {
					color: %2$s !important;
				}

				%1$s.postview .episode-list__load-more,
				.pp-modal-window %5$s .episode-list__load-more,
				%1$s:not(.modern) .ppjs__time-handle-content,
				%4$s .ppjs__time-handle-content {
					border-color: %2$s !important;
				}
				%1$s:not(.modern) .ppjs__audio-time-rail,
				%1$s.lv3 .pod-entry__play,
				%1$s.lv4 .pod-entry__play,
				%1$s.gv2 .pod-entry__play,
				%1$s.modern.wide-player .ppjs__audio .ppjs__button.ppjs__playpause-button button,
				%1$s.modern.wide-player .ppjs__audio .ppjs__button.ppjs__playpause-button button:hover,
				%1$s.modern.wide-player .ppjs__audio .ppjs__button.ppjs__playpause-button button:focus,
				.pp-modal-window %4$s button.episode-list__load-more,
				.pp-modal-window %4$s .ppjs__audio-time-rail,
				.pp-modal-window %4$s button.pp-modal-close {
					background-color: %2$s !important;
				}
				%1$s .hasCover .ppjs__audio .ppjs__button.ppjs__playpause-button button {
					background-color: rgba(0, 0, 0, 0.5) !important;
				}
				.pp-modal-window %4$s button.episode-list__load-more:hover,
				.pp-modal-window %4$s button.episode-list__load-more:focus,
				.pp-modal-window %5$s button.episode-list__load-more:hover,
				.pp-modal-window %5$s button.episode-list__load-more:focus {
					background-color: rgba( %3$s, 0.7 ) !important;
				}
				%1$s .ppjs__button.toggled-on,
				.pp-modal-window %4$s .ppjs__button.toggled-on,
				%1$s.playerview .pod-entry.activeEpisode,
				.pp-modal-window %4$s.playerview .pod-entry.activeEpisode {
					background-color: rgba( %3$s, 0.1 );
				}
				%1$s.postview .episode-list__load-more {
					background-color: transparent !important;
				}
				%1$s.modern:not(.wide-player) .ppjs__audio .ppjs__button.ppjs__playpause-button button *,
				%1$s.modern:not(.wide-player) .ppjs__audio .ppjs__button.ppjs__playpause-button button:hover *,
				%1$s.modern:not(.wide-player) .ppjs__audio .ppjs__button.ppjs__playpause-button button:focus * {
					color: %2$s !important;
				}
				%1$s.modern:not(.wide-player) .ppjs__time-handle-content {
					border-color: %2$s !important;
				}
				%1$s.modern:not(.wide-player) .ppjs__audio-time-rail {
					background-color: %2$s !important;
				}
				%1$s,
				%4$s,
				%5$s {
					--pp-accent-color: %2$s;
				}
				',
				$id,
				$color,
				$rgb,
				$mod,
				$amod
			);
		}

		if ( $this->args['hide-download'] && $this->args['hide-social'] ) {
			$css .= sprintf(
				'
				%1$s .ppjs__share-button,
				%2$s .ppjs__share-button {
					display: none;
				}
				',
				$id,
				$mod
			);
		}

		if ( $this->args['hide-content'] ) {
			$css .= sprintf(
				'
				%1$s .ppjs__script-button {
					display: none;
				}
				',
				$id
			);
		}

		if ( $this->args['hide-author'] ) {
			$css .= sprintf(
				'
				%1$s .pod-entry__author {
					display: none;
				}
				',
				$id
			);
		}

		if ( $this->args['header-default'] ) {
			$css .= sprintf(
				'
				%1$s .pod-info__header {
					display: block;
				}
				',
				$id
			);
		}

		/**
		 * Podcast player get dynamic css for current instance.
		 *
		 * @since 3.3.0
		 *
		 * @param array  $css  Podcast player dynamic css.
		 * @param array  $this Podcast display instance.
		 */
		return apply_filters( 'podcast_player_dynamic_css', $css, $this );
	}

	/**
	 * Display podcast player.
	 *
	 * @since  1.0.0
	 *
	 * @param string $classes HTMl classes for pp wrapper.
	 * @param array  $styles  Podcast player dynamic CSS.
	 */
	private function display_podcast_player( $classes, $styles ) {
		$player      = $this->render_tree();
		$launch      = $this->pod_launch();
		$block_data  = $this->data_for_preview_screens();
		$script_data = $this->get_scripts_data();
		$attr        = '';
		if ( $script_data ) {
			$attr = ' data-ppsdata="' . esc_attr( $script_data ) . '"';
		}

		if ( $this->args['menu'] && isset( $this->args['main_menu_items'] ) && $this->args['main_menu_items'] > 0 ) {
			$attr .= ' data-main-items="' . esc_attr( absint( $this->args['main_menu_items'] ) ) . '"';
		}
		$markup = $player . $launch;
		$markup = sprintf(
			'<div id="pp-podcast-%1$s" class="%2$s" %3$s%4$s>%5$s</div>',
			absint( $this->instance ),
			$classes,
			$block_data,
			$attr,
			$markup
		);
		$this->print_inline_css( $styles );
		echo $markup; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Consitionally prints inline CSS for ajax and REST requests.
	 *
	 * @since 3.5.0
	 *
	 * @param string $css css string to be printed.
	 */
	private function print_inline_css( $css ) {
		$ajax = false;
		if ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) {
			$ajax = true;
		}

		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			$ajax = true;
		}

		if ( is_customize_preview() ) {
			$ajax = true;
		}

		if ( $this->is_elementor_editor() ) {
			$ajax = true;
		}

		// Custom Support for Ajax powered WordPress themes & plugins.
		$is_ajax = Get_Fn::get_plugin_option( 'is_ajax' );
		if ( 'yes' === $is_ajax ) {
			$ajax = true;
		}

		if ( $ajax ) {
			?>
			<style type="text/css"><?php echo wp_strip_all_tags( $css, true ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
			<?php
		}
	}

	/**
	 * Consitionally prints scripts data for ajax and REST requests.
	 *
	 * @since 4.6.0
	 */
	private function get_scripts_data() {
		$ajax = false;
		if ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) {
			$ajax = true;
		}

		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			$ajax = true;
		}

		if ( is_customize_preview() ) {
			$ajax = true;
		}

		if ( $this->is_elementor_editor() ) {
			$ajax = true;
		}

		// Custom Support for Ajax powered WordPress themes & plugins.
		$is_ajax = Get_Fn::get_plugin_option( 'is_ajax' );
		if ( 'yes' === $is_ajax ) {
			$ajax = true;
		}

		if ( $ajax ) {
			return wp_json_encode( $this->data[ 'pp-podcast-' . $this->instance ] );
		}

		return '';
	}

	/**
	 * Display tree for podcast player.
	 *
	 * @since  1.0.0
	 */
	private function render_tree() {
		$markup = $this->markup(
			'pp-podcast__wrapper',
			array(
				'pod-info'    => array(
					'pod-header' => array(
						'pod-header__image' => 'header_image',
						'pod-header__items' => array(
							'pod-items__title' => 'podcast_title',
							'pod-items__desc'  => 'podcast_desc',
							'pod-items__menu'  => 'podcast_menu',
						),
					),
				),
				'pod-content' => array(
					'pp-podcast__single' => array(
						'pp-podcast__player' => 'podcast_player',
						'episode-single'     => array(
							'episode-single__close'   => 'single_close_btn',
							'episode-single__wrapper' => 'single_wrapper',
							'ppjs__img-wrapper'       => 'ppjs_img_wrapper',
						),
					),
					'episode-list'       => array(
						'episode-list__search'  => 'search_field',
						'episode-list__wrapper' => array(
							'pod-entry'         => 'episodes_list',
							'lm-button-wrapper' => 'load_more_btn',
							'episode-search'    => 'search_results_wrapper',
						),
					),
					'ppjs__list-reveal'  => 'list_reveal_btns',
				),
			)
		);
		return $markup;
	}

	/**
	 * Display tree for podcast player header launcher.
	 *
	 * @since  1.0.0
	 */
	private function pod_launch() {
		$style = $this->args['display-style'];

		// Return if header is not available.
		if ( ! $this->is_header_available() ) {
			return '';
		}

		// Return if not on playerview.
		if ( '' !== $style && 'legacy' !== $style && 'modern' !== $style ) {
			return '';
		}

		return $this->markup(
			'pod-launch',
			array( 'pod-launcher__default' => 'launch_btn' )
		);
	}

	/**
	 * Data to be included for blocks to display properly on editor screen.
	 *
	 * @since 3.3.0
	 */
	private function data_for_preview_screens() {
		$data = array(
			'teaser'  => esc_html( $this->args['teaser-text'] ),
			'elength' => absint( $this->args['excerpt-length'] ),
			'eunit'   => esc_html( $this->args['excerpt-unit'] ),
		);

		$output = '';
		array_walk(
			$data,
			function( $val, $key ) use ( &$output ) {
				$output .= sprintf( ' data-%s="%s"', esc_html( $key ), esc_attr( $val ) );
			}
		);
		return $output;
	}

	/**
	 * Render podcast tree.
	 *
	 * @since  1.0.0
	 *
	 * @param string $classname Identifier unique classname.
	 * @param array  $args      Array of tree elements.
	 */
	public function markup( $classname, $args ) {

		$hook = str_replace( array( '-', '__' ), '_', $classname );

		/**
		 * Modify podcast render tree.
		 *
		 * @since 3.3.0
		 *
		 * @param array $args Tree elements.
		 * @param array $this Podcast display instance.
		 */
		$args   = apply_filters( "pp_render_tree_{$hook}", $args, $this );
		$markup = '';

		foreach ( $args as $key => $func ) {
			$key_classes = $this->get_classlist( $key );
			if ( is_array( $func ) ) {
				if ( is_callable( $func ) ) {
					$markup .= call_user_func( $func, $key_classes, $this );
				} else {
					$markup .= $this->markup( $key, $func );
				}
			} elseif ( is_callable( array( $this, $func ) ) ) {
				$markup .= call_user_func( array( $this, $func ), $key_classes );
			}
		}

		if ( '' !== $markup ) {
			$classes = $this->get_classlist( $classname );
			$markup  = sprintf(
				'<div class="%1$s">%2$s</div>',
				$classes,
				$markup
			);
		}

		return $markup;
	}

	/**
	 * Get HTML classlist for elements.
	 *
	 * @since  1.0.0
	 *
	 * @param string $cname Identifier unique classname.
	 */
	private function get_classlist( $cname ) {
		$classes = array(
			'pod-info'          => array( 'pp-podcast__info', 'pod-info' ),
			'pod-header'        => array( 'pod-info__header', 'pod-header' ),
			'pod-header__items' => array( 'pod-header__items', 'pod-items' ),
			'pod-content'       => array( 'pp-podcast__content', 'pod-content' ),
			'episode-single'    => array( 'pod-content__episode', 'episode-single' ),
			'episode-list'      => array( 'pod-content__list', 'episode-list' ),
			'episode-search'    => array( 'episode-list__search-results', 'episode-search' ),
			'pod-entry'         => array( 'episode-list__entry', 'pod-entry' ),
			'pod-launch'        => array( 'pod-content__launcher', 'pod-launch' ),
		);

		$cls_arr = isset( $classes[ $cname ] ) ? $classes[ $cname ] : array( $cname );

		/**
		 * Modify elements classlists.
		 *
		 * @since 3.3.0
		 *
		 * @param array $cls_arr    Element's classlist array.
		 * @param array $classname  Identifier unique classname.
		 * @param array $this->args Podcast display instance.
		 */
		$cls_arr = apply_filters( 'podcast_player_classlist', $cls_arr, $cname, $this );
		return implode( ' ', array_map( 'esc_attr', $cls_arr ) );
	}

	/**
	 * Render podcast header image.
	 *
	 * @since  1.0.0
	 *
	 * @param string $classname Identifier unique classname.
	 */
	public function header_image( $classname ) {

		// Check if header and header image is to be displayed.
		if ( $this->args['hide-header'] || $this->args['hide-cover-img'] ) {
			return '';
		}

		// Return if header image url is not available.
		if ( ! $this->args['imgurl'] ) {
			return;
		}

		if ( isset( $this->args['imgratio'] ) && is_numeric( $this->args['imgratio'] ) ) {
			$ratio = floatval( $this->args['imgratio'] ) * 100;
			$ratio = $ratio . '%';
		} else {
			$ratio = '100%';
		}
		$this->args['imgratio'] = $ratio;

		$markup = $this->get_template( 'header', 'image' );
		return sprintf( '<div class="%s">%s</div>', esc_attr( $classname ), $markup );
	}

	/**
	 * Render podcast title.
	 *
	 * @since  1.0.0
	 *
	 * @param string $classname Identifier unique classname.
	 */
	public function podcast_title( $classname ) {

		// Check if header and header image is to be displayed.
		if ( $this->args['hide-header'] || $this->args['hide-title'] ) {
			return '';
		}

		// Return if podcast title is not available.
		if ( ! $this->info['title'] ) {
			return '';
		}

		return sprintf( '<div class="%s">%s</div>', esc_attr( $classname ), esc_html( $this->info['title'] ) );
	}

	/**
	 * Render podcast description.
	 *
	 * @since  1.0.0
	 *
	 * @param string $classname Identifier unique classname.
	 */
	public function podcast_desc( $classname ) {

		// Check if header and header image is to be displayed.
		if ( $this->args['hide-header'] || $this->args['hide-description'] ) {
			return '';
		}

		// Return if podcast title is not available.
		if ( ! $this->info['description'] ) {
			return '';
		}

		// Allow HTML.
		$desc = wpautop( wptexturize( str_replace( '&quot;', '&#8221;', $this->info['description'] ) ) );

		return sprintf( '<div class="%s">%s</div>', esc_attr( $classname ), $desc );
	}

	/**
	 * Render podcast menu.
	 *
	 * @since  1.0.0
	 */
	public function podcast_menu() {

		// Return if header is not to be displayed.
		if ( $this->args['hide-header'] ) {
			return '';
		}

		if ( $this->args['menu'] && isset( $this->args['main_menu_items'] ) && $this->args['main_menu_items'] > 0 ) {
			$menu_markup = '';
		} else {
			$menu_markup = $this->get_subscription_buttons();
		}

		$menu_markup .= $this->get_podcast_menu( true );

		if ( $menu_markup ) {
			return sprintf( '<div class="pod-items__navi-menu">%s</div>', $menu_markup );
		}

		return '';
	}

	/**
	 * Render launcher menu.
	 *
	 * @since  1.0.0
	 *
	 * @param string $classname Identifier unique classname.
	 */
	public function launcher_menu( $classname ) {

		// Return if header is not to be displayed.
		if ( $this->args['hide-header'] ) {
			return '';
		}

		if ( $this->args['menu'] && isset( $this->args['main_menu_items'] ) && $this->args['main_menu_items'] > 0 ) {
			$menu_markup = '';
		} else {
			$menu_markup = $this->get_subscription_buttons();
		}

		$menu_markup .= $this->get_podcast_menu( true );

		if ( $menu_markup ) {
			return sprintf( '<div class="pod-items__navi-menu">%s</div>', $menu_markup );
		}

		return '';
	}

	/**
	 * Render podcast subscription buttons.
	 *
	 * @since  1.0.0
	 */
	public function get_subscription_buttons() {
		return $this->get_template( 'header', 'subscribe-buttons' );
	}

	/**
	 * Render podcast menu.
	 *
	 * @since  1.0.0
	 *
	 * @param bool $has_sub Are Subscribe buttons available.
	 */
	public function get_podcast_menu( $has_sub ) {

		// Return if podcast menu is not to be shown.
		if ( $this->args['hide-subscribe'] ) {
			return '';
		}

		// Get nav-menu markup.
		$nav_menu = '';
		if ( ! empty( $this->args['menu'] ) ) {
			$nav_menu = $this->get_nav_menu_markup();
		}

		// Conditionally Add podcast menu toggle button.
		if ( $nav_menu && $has_sub ) {
			$toggle_btn = $this->get_template( 'misc/buttons', 'pod-menu' );
			$nav_menu   = $toggle_btn . $nav_menu;
		}

		if ( $nav_menu ) {
			return sprintf(
				'<div class="pod-items__menu">%s</div>',
				$nav_menu
			);
		}

		return '';
	}

	/**
	 * Get podcast player's navigation menu.
	 *
	 * @since 3.3.0
	 */
	public function get_nav_menu_markup() {
		return $this->get_template( 'header/menu', 'podcast-menu' );
	}

	/**
	 * Get default feed links as nav menu.
	 *
	 * @since  1.0.0
	 */
	public function get_default_feed_links() {
		return $this->get_template( 'header/menu', 'default-links' );
	}

	/**
	 * Render podcast audio/video player markup.
	 *
	 * @since  1.0.0
	 *
	 * @param string $classname Identifier unique classname.
	 */
	public function podcast_player( $classname ) {
		$first_item = isset( $this->items[0] ) ? $this->items[0] : '';
		$player     = '';
		if ( $first_item ) {
			$src    = isset( $first_item['src'] ) ? $first_item['src'] : '';
			$player = Markup_Fn::get_player_markup( $src, $this->instance );
		}
		if ( $player ) {
			return sprintf(
				'<div class="%1$s"><div class="pp-player-episode">%2$s</div></div>',
				esc_attr( $classname ),
				$player
			);
		}
		return '';
	}

	/**
	 * Render close button for single episode description.
	 *
	 * @since  1.0.0
	 */
	public function single_close_btn() {
		return $this->get_template( 'misc/buttons', 'single-close' );
	}

	/**
	 * Render podcast header launch button.
	 *
	 * @since  1.0.0
	 */
	public function launch_btn() {
		$is_cover = $this->args['hide-cover-img'] || ! $this->args['imgurl'] ? false : true;
		$is_title = $this->args['hide-title'] || ! $this->info['title'] ? false : true;
		$is_desc  = $this->args['hide-description'] || ! $this->info['description'] ? false : true;
		$is_sub   = (bool) $this->podcast_menu();

		$display_btn = $is_cover || $is_title || $is_desc || $is_sub;
		if ( $display_btn ) {
			return $this->get_template( 'misc/buttons', 'launch' );
		}
		return '';
	}

	/**
	 * Display single episode wrapper.
	 *
	 * @since  1.0.0
	 */
	public function single_wrapper() {
		return $this->get_template( 'episode', 'single' );
	}

	/**
	 * Episode's featured image wrapper markup.
	 *
	 * @since  1.0.0
	 *
	 * @param string $classname Identifier unique classname.
	 */
	public function ppjs_img_wrapper( $classname ) {
		// Return if featured images are not to be displayed.
		if ( ! $this->args['hide-featured'] ) {
			return $this->get_template( 'episode', 'featured' );
		}
		return '';
	}

	/**
	 * Episode list search field.
	 *
	 * @since  1.0.0
	 *
	 * @param string $classname Identifier unique classname.
	 */
	public function search_field( $classname ) {

		// Return if search field not to be displayed.
		if ( $this->args['hide-search'] ) {
			return '';
		}

		// Condition where only single episode is displayed.
		$single_display = ( 1 === $this->args['number'] ) && $this->args['hide-loadmore'];

		// No need to display search field if only a single episode is to be displayed.
		if ( $single_display || 1 >= count( $this->items ) ) {
			return '';
		}

		$search_field = $this->get_template( 'list', 'search-field' );
		$filter = apply_filters( 'podcast_player_episodes_filter', '', $this );
		$clear_search = $this->get_template( 'misc/buttons', 'clear-search' );
		return '<div class="episode-list__filters">' . $search_field . $filter . $clear_search . '</div>';
	}

	/**
	 * Display single episode wrapper.
	 *
	 * @since  1.0.0
	 */
	public function episodes_list() {
		$markup = '';
		$style  = $this->args['display-style'];

		// Condition where only a single episode is displayed.
		$single_display = ( 1 === $this->args['number'] ) && $this->args['hide-loadmore'];

		// Return if there is only one episode in the list.
		if ( Validation_Fn::is_single_episode_layout( count( $this->items ), $style, $single_display ) ) {
			return $markup;
		}

		$items = array_slice( $this->items, 0, $this->args['number'] );
		foreach ( $items as $key => $item ) {
			$ppe_id = $this->instance . '-' . ( $key + 1 );

			if ( 'modern' === $style ) {
				$template = Markup_Fn::locate_template( 'list', 'entry-modern' );
			} else {
				$template = Markup_Fn::locate_template( 'list', 'entry' );
			}

			if ( $template ) {
				ob_start();
				require $template;
				$markup .= ob_get_clean();
			}
		}
		return $markup;
	}

	/**
	 * Render load more episodes button.
	 *
	 * @since  1.0.0
	 *
	 * @param string $classname Identifier unique classname.
	 */
	public function load_more_btn( $classname ) {

		// If load more button is not to be displayed.
		if ( $this->args['hide-loadmore'] ) {
			return '';
		}

		// Return if there is only one episode in the list.
		if ( 1 >= count( $this->items ) ) {
			return '';
		}

		// Get total episodes to be displayed.
		$total = isset( $this->args['total'] ) ? $this->args['total'] : false;
		$elist = array_filter( $this->args['elist'] );
		$etype = isset( $this->args['edisplay'] ) && 'hide' === $this->args['edisplay'] ? true : false;
		if ( $etype ) {
			$total = ! empty( $elist ) ? $total - count( $elist ) : $total;
		} else {
			$total = ! empty( $elist ) ? count( $elist ) : $total;
		}

		// Return if all episodes are already displayed.
		if ( $total && $this->args['number'] >= $total ) {
			return '';
		}

		$btn = $this->get_template( 'misc/buttons', 'load-more' );
		return sprintf( '<div class="%s">%s</div>', esc_attr( $classname ), $btn );
	}

	/**
	 * Wrapper to display episodes search results.
	 *
	 * @since  1.0.0
	 *
	 * @param string $classname Identifier unique classname.
	 */
	public function search_results_wrapper( $classname ) {

		// Return if there is only one episode in the list.
		if ( 1 >= count( $this->items ) ) {
			return '';
		}

		$markup = sprintf(
			'<span class="ppjs__offscreen">%s</span>',
			esc_html__( 'Search Results placeholder', 'podcast-player' )
		);
		return sprintf(
			'<div class="%s">%s</div>',
			esc_attr( $classname ),
			$markup
		);
	}

	/**
	 * Render close button for single episode description.
	 *
	 * @since  1.0.0
	 *
	 * @param string $classname Identifier unique classname.
	 */
	public function list_reveal_btns( $classname ) {
		$markup = '';

		// Return if there is only one episode in the player.
		if ( 1 >= count( $this->items ) ) {
			return $markup;
		}

		$markup .= $this->get_template( 'misc/buttons', 'previous' );
		$markup .= $this->get_template( 'misc/buttons', 'list' );
		$markup .= $this->get_template( 'misc/buttons', 'next' );
		return sprintf(
			'<div class="%s">%s</div>',
			esc_attr( $classname ),
			$markup
		);
	}

	/**
	 * Check if podcast player header is displayed by default.
	 *
	 * @since  1.0.0
	 */
	public function is_header_available() {

		// Check if user selected to hide the header.
		if ( $this->args['hide-header'] ) {
			return false;
		}

		$is_cover = $this->args['hide-cover-img'] || ! $this->args['imgurl'] ? false : true;
		$is_title = $this->args['hide-title'] || ! $this->info['title'] ? false : true;
		$is_desc  = $this->args['hide-description'] || ! $this->info['description'] ? false : true;
		$is_sub   = (bool) $this->podcast_menu();

		return $is_cover || $is_title || $is_desc || $is_sub;
	}

	/**
	 * Check if podcast player header is displayed by default.
	 *
	 * @since  1.0.0
	 */
	public function is_header_visible() {
		if ( ! $this->is_header_available() ) {
			return false;
		}

		if ( ( '' === $this->args['display-style'] || 'legacy' === $this->args['display-style'] || 'modern' === $this->args['display-style'] ) && ! $this->args['header-default'] ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Get podcast player template parts.
	 *
	 * @since  1.0.0
	 *
	 * @param string $path Template relative path.
	 * @param string $name Template file name without .php suffix.
	 */
	public function get_template( $path, $name ) {
		$markup   = '';
		$template = Markup_Fn::locate_template( $path, $name );
		if ( $template ) {
			ob_start();
			require $template;
			$markup .= ob_get_clean();
		}

		$markup = Markup_Fn::remove_breaks( $markup );
		return $markup;
	}

	/**
	 * Check if we are on elementor editor screen.
	 *
	 * @since 5.0.1
	 */
	private function is_elementor_editor() {
		if (
			in_array(
				'elementor/elementor.php',
				apply_filters( 'active_plugins', get_option( 'active_plugins' ) ),
				true
			)
		) {
			if ( class_exists( '\Elementor\Plugin' ) ) {
				$inst   = \Elementor\Plugin::$instance;
				$editor = $inst ? $inst->editor : false;
				if (
					$editor
					&&
					method_exists( $editor, 'is_edit_mode' )
					&&
					$editor->is_edit_mode()
				) {
					return true;
				}
			}
		}
		return false;
	}
}
