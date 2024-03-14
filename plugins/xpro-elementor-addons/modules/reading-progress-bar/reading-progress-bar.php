<?php
/**
 * Reading Progress Bar extension class.
 *
 * @package XproELementorAddons
 */

namespace XproElementorAddons\Module;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Plugin;

defined( 'ABSPATH' ) || die();

class Xpro_Elementor_Reading_Progress_Bar {

	static $should_script_enqueue = false;

	public static function init() {

		add_action( 'elementor/documents/register_controls', array( __CLASS__, 'register' ), 10 );
		add_action( 'elementor/editor/after_save', array( __CLASS__, 'save_global_values' ), 10, 2 );
		add_action( 'wp', array( __CLASS__, 'should_script_enqueue' ) );

	}

	/**
	 * Set should_script_enqueue based extension settings
	 *
	 * @param Element_Base $section
	 *
	 * @return void
	 */

	public static function should_script_enqueue( $document ) {

		if ( is_admin() || self::$should_script_enqueue || Plugin::$instance->editor->is_edit_mode() ) {
			return;
		}

		$global_setting = get_option( 'xpro_elementor_global_settings' );

		if ( ( isset( $global_setting['reading_progress_bar_global'] ) && array_values( $global_setting['reading_progress_bar_global'] )[0]['post_id'] ) || ( isset( $global_setting['reading_progress_bar'] ) && array_key_exists( (int) get_the_ID(), $global_setting['reading_progress_bar'] ) ) ) {

			if ( isset( $global_setting['reading_progress_bar_global'] ) ) {
				$global_values = array_values( get_option( 'xpro_elementor_global_settings' )['reading_progress_bar_global'] )[0];
				$show_on       = $global_values['display_on'];
				if ( ( 'page' !== get_post_type() && 'all-pages' === $show_on ) || ( 'post' !== get_post_type() && 'all-posts' === $show_on ) || ( ( 'page' !== get_post_type() && 'all-pages-posts' === $show_on ) && ( 'post' !== get_post_type() && 'all-pages-posts' === $show_on ) ) ) {
					return;
				}
			}

			self::enqueue_scripts();
			self::$should_script_enqueue = true;
			remove_action( 'wp', array( __CLASS__, 'should_script_enqueue' ) );
			add_action( 'wp_footer', array( __CLASS__, 'add_html_to_body' ) );
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'add_inline_CSS' ), 99 );

		}
	}

	public static function enqueue_scripts() {
		wp_enqueue_script( 'xpro-reading-progress-bar', XPRO_ELEMENTOR_ADDONS_DIR_URL . 'modules/reading-progress-bar/js/reading-progress-bar.min.js', null, XPRO_ELEMENTOR_ADDONS_VERSION, true );
	}

	public static function register( $element ) {

		if ( get_post_type() === 'xpro-themer' || get_post_type() === 'xpro_content' ) {
			return;
		}

		$global_settings = get_option( 'xpro_elementor_global_settings' );

		$element->start_controls_section(
			'section_xpro_elementor_reading_progress_bar_dfx',
			array(
				'label' => __( 'Reading Bar', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			)
		);

		$active_page_settings = isset( $global_settings['reading_progress_bar_global'] ) ? array_values( $global_settings['reading_progress_bar_global'] )[0] : false;

		if ( isset( $active_page_settings ) && false !== $active_page_settings && get_the_ID() !== $active_page_settings['post_id'] && 'publish' === get_post_status( $active_page_settings['post_id'] ) ) {
			$element->add_control(
				'xpro_elementor_reading_progress_bar_dfx_warning_text',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf(
					/* translators: 1$s: Title */
						__( 'You can modify the Global Reading Progress Bar by %1$s', 'xpro-elementor-addons' ),
						'<strong><a href="' . get_bloginfo( 'url' ) . '/wp-admin/post.php?post=' . $active_page_settings['post_id'] . '&action=elementor">Clicking Here</a></strong>'
					),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
					'separator'       => 'before',
				)
			);
		} else {

			$element->add_control(
				'xpro_elementor_reading_progress_bar_dfx',
				array(
					'label'        => __( 'Enable', 'xpro-elementor-addons' ),
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'render_type'  => 'template',
				)
			);

			$element->add_control(
				'xpro_elementor_reading_progress_bar_dfx_global',
				array(
					'label'        => __( 'Progress Bar Globally', 'xpro-elementor-addons' ),
					'description'  => __( 'Enabling this option will effect on entire site.', 'xpro-elementor-addons' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'no',
					'label_on'     => __( 'Yes', 'xpro-elementor-addons' ),
					'label_off'    => __( 'No', 'xpro-elementor-addons' ),
					'return_value' => 'yes',
					'condition'    => array(
						'xpro_elementor_reading_progress_bar_dfx' => 'yes',
					),
				)
			);

			$element->add_control(
				'xpro_elementor_reading_progress_bar_dfx_display_on',
				array(
					'label'     => __( 'Display On', 'xpro-elementor-addons' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'all-pages-posts',
					'options'   => array(
						'all-pages'       => __( 'All Pages', 'xpro-elementor-addons' ),
						'all-posts'       => __( 'All Posts', 'xpro-elementor-addons' ),
						'all-pages-posts' => __( 'All Pages & Posts', 'xpro-elementor-addons' ),
					),
					'condition' => array(
						'xpro_elementor_reading_progress_bar_dfx'        => 'yes',
						'xpro_elementor_reading_progress_bar_dfx_global' => 'yes',
					),
				)
			);
		}

		$element->add_control(
			'xpro_elementor_reading_progress_bar_dfx_position',
			array(
				'label'       => esc_html__( 'Position', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'top',
				'label_block' => false,
				'options'     => array(
					'top'    => esc_html__( 'Top', 'xpro-elementor-addons' ),
					'bottom' => esc_html__( 'Bottom', 'xpro-elementor-addons' ),
				),
				'separator'   => 'before',
				'condition'   => array(
					'xpro_elementor_reading_progress_bar_dfx' => 'yes',
				),
			)
		);

		$element->add_control(
			'xpro_elementor_reading_progress_bar_dfx_height',
			array(
				'label'      => __( 'Height', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 5,
				),
				'condition'  => array(
					'xpro_elementor_reading_progress_bar_dfx' => 'yes',
				),
			)
		);

		$element->add_control(
			'xpro_elementor_reading_progress_bar_dfx_animation_speed',
			array(
				'label'      => __( 'Animation Speed', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'size' => 0.3,
				),
				'condition'  => array(
					'xpro_elementor_reading_progress_bar_dfx' => 'yes',
				),
			)
		);

		$element->add_control(
			'xpro_elementor_reading_progress_bar_dfx_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'condition' => array(
					'xpro_elementor_reading_progress_bar_dfx' => 'yes',
				),
			)
		);

		$element->add_control(
			'xpro_elementor_reading_progress_bar_dfx_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'xpro_elementor_reading_progress_bar_dfx' => 'yes',
				),
			)
		);

		$element->end_controls_section();

	}

	public static function save_global_values( $post_id ) {

		$document = Plugin::$instance->documents->get( $post_id, false );
		$settings = $document->get_settings();

		$global_settings = get_option( 'xpro_elementor_global_settings' );
		$options         = $global_settings ? $global_settings : array();

		$active_page_settings = isset( $global_settings['reading_progress_bar_global'] ) ? array_values( $global_settings['reading_progress_bar_global'] )[0] : false;

		if ( isset( $active_page_settings ) && false !== $active_page_settings && get_the_ID() !== $active_page_settings['post_id'] ) {
			return;
		}

		if ( 'yes' === $settings['xpro_elementor_reading_progress_bar_dfx'] ) {

			// Global Settings
			if ( 'yes' === $settings['xpro_elementor_reading_progress_bar_dfx_global'] ) {
				$options['reading_progress_bar_global'][ $post_id ]               = self::save_options( $settings );
				$options['reading_progress_bar_global'][ $post_id ]['post_id']    = get_the_ID();
				$options['reading_progress_bar_global'][ $post_id ]['display_on'] = $settings['xpro_elementor_reading_progress_bar_dfx_display_on'];

				// Updating old settings if present
				if ( $options['reading_progress_bar'] ) {
					unset( $options['reading_progress_bar'] );
				}
			} else {

				$options['reading_progress_bar'][ $post_id ] = self::save_options( $settings );

				// Removing global values if disabled
				if ( isset( get_option( 'xpro_elementor_global_settings' )['reading_progress_bar_global'] ) && array_key_exists( $post_id, get_option( 'xpro_elementor_global_settings' )['reading_progress_bar_global'] ) ) {
					unset( $options['reading_progress_bar_global'] );
				}
			}
		} else {
			if ( isset( get_option( 'xpro_elementor_global_settings' )['reading_progress_bar'] ) && array_key_exists( $post_id, get_option( 'xpro_elementor_global_settings' )['reading_progress_bar'] ) ) {
				// removing the disabled Mouse Effect
				unset( $options['reading_progress_bar'][ $post_id ] );
			}
			if ( isset( get_option( 'xpro_elementor_global_settings' )['reading_progress_bar_global'] ) && array_key_exists( $post_id, get_option( 'xpro_elementor_global_settings' )['reading_progress_bar_global'] ) ) {
				unset( $options['reading_progress_bar_global'] );
			}
		}

		update_option( 'xpro_elementor_global_settings', $options );

	}

	public static function save_options( $settings ) {
		$fields                     = array();
		$fields['position']         = $settings['xpro_elementor_reading_progress_bar_dfx_position'];
		$fields['height']           = $settings['xpro_elementor_reading_progress_bar_dfx_height']['size'];
		$fields['speed']            = $settings['xpro_elementor_reading_progress_bar_dfx_animation_speed']['size'];
		$fields['color']            = $settings['xpro_elementor_reading_progress_bar_dfx_color'];
		$fields['background_color'] = $settings['xpro_elementor_reading_progress_bar_dfx_bg_color'];

		return $fields;
	}

	public static function add_html_to_body() {

		$global_setting = get_option( 'xpro_elementor_global_settings' );

		$settings = isset( $global_setting['reading_progress_bar_global'] ) ? array_values( $global_setting['reading_progress_bar_global'] )[0] : $global_setting['reading_progress_bar'][ get_the_ID() ];

		$html  = '<div class="xpro-reading-progress-bar" data-speed="' . esc_attr( $settings['speed'] ) . '">';
		$html .= '<div class="xpro-reading-progress-bar-fill"></div>';
		$html .= '</div>';

		$allowed_tags = array(
			'div' => array(
				'class'      => array(),
				'data-speed' => array(),
			)
		);

		echo wp_kses( $html, $allowed_tags );

	}

	public static function add_inline_CSS() {

		$global_setting = get_option( 'xpro_elementor_global_settings' );
		$settings       = isset( $global_setting['reading_progress_bar_global'] ) ? array_values( $global_setting['reading_progress_bar_global'] )[0] : $global_setting['reading_progress_bar'][ get_the_ID() ];

		$custom_css = '';

		if ( 'bottom' === $settings['position'] ) {
			$custom_css .= '.xpro-reading-progress-bar{top:auto;bottom:0;}';
		}

		if ( $settings['height'] ) {
			$custom_css .= '.xpro-reading-progress-bar-fill{height:' . $settings['height'] . 'px;}';
		}

		if ( $settings['speed'] ) {
			$custom_css .= '.xpro-reading-progress-bar-fill{transition: width ' . $settings['speed'] . 'ms ease;}';
		}

		if ( $settings['color'] ) {
			$custom_css .= '.xpro-reading-progress-bar-fill{background-color:' . $settings['color'] . ';}';
		}

		if ( $settings['background_color'] ) {
			$custom_css .= '.xpro-reading-progress-bar{background-color:' . $settings['background_color'] . ';}';
		}

		// 1. Remove comments.
		$custom_css = preg_replace( '#/\*.*?\*/#s', '', $custom_css );
		// 2. Remove whitespace.
		$custom_css = preg_replace( '/\s*([{}|:;,])\s+/', '$1', $custom_css );
		// 3. Remove starting whitespace.
		$custom_css = preg_replace( '/\s\s+(.*)/', '$1', $custom_css );

		wp_add_inline_style( 'xpro-elementor-addons-widgets', esc_attr( $custom_css ) );
	}

}

Xpro_Elementor_Reading_Progress_Bar::init();
