<?php
/**
 * Helpers
 *
 * @package Copy the Code
 * @since 1.0.0
 */

namespace CopyTheCode;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

/**
 * Helpers
 *
 * @since 1.0.0
 */
class Helpers {
	public static function get_svg_link_icon() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
        <path fill="#B6BEC6" d="M8.818 8.818l.128-.121a3 3 0 014.115.12l1.06 1.061-1.414 1.415-1.06-1.061-.088-.078a1 1 0 00-1.327.078l-4.95 4.95-.077.087a1 1 0 00.078 1.327l2.12 2.121.088.078a1 1 0 001.327-.078l2.122-2.121 1.414 1.414-2.122 2.121-.128.121a3 3 0 01-4.114-.12L3.868 18.01l-.12-.128a3 3 0 01.12-4.115l4.95-4.95zm4.95-4.95l.128-.121a3 3 0 014.114.12l2.122 2.122.12.128a3 3 0 01-.12 4.115l-4.95 4.95-.128.12a3 3 0 01-4.114-.12L9.879 14.12l1.414-1.414 1.06 1.06.088.078a1 1 0 001.327-.078l4.95-4.95.077-.087a1 1 0 00-.077-1.327l-2.122-2.12-.087-.079a1 1 0 00-1.327.078l-2.121 2.121-1.414-1.414 2.12-2.121z">
        </path>
    </svg>';
	}

	public static function get_svg_pinterest_icon() {
		return '<svg class="gUZ GjR U9O kVc" height="24" width="24" viewBox="0 0 24 24" aria-label="Pinterest" role="img"><path fill="#e60023" d="M0 12c0 5.123 3.211 9.497 7.73 11.218-.11-.937-.227-2.482.025-3.566.217-.932 1.401-5.938 1.401-5.938s-.357-.715-.357-1.774c0-1.66.962-2.9 2.161-2.9 1.02 0 1.512.765 1.512 1.682 0 1.025-.653 2.557-.99 3.978-.281 1.189.597 2.159 1.769 2.159 2.123 0 3.756-2.239 3.756-5.471 0-2.861-2.056-4.86-4.991-4.86-3.398 0-5.393 2.549-5.393 5.184 0 1.027.395 2.127.889 2.726a.36.36 0 0 1 .083.343c-.091.378-.293 1.189-.332 1.355-.053.218-.173.265-.4.159-1.492-.694-2.424-2.875-2.424-4.627 0-3.769 2.737-7.229 7.892-7.229 4.144 0 7.365 2.953 7.365 6.899 0 4.117-2.595 7.431-6.199 7.431-1.211 0-2.348-.63-2.738-1.373 0 0-.599 2.282-.744 2.84-.282 1.084-1.064 2.456-1.549 3.235C9.584 23.815 10.77 24 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0 0 5.373 0 12"></path></svg>';
	}

	public static function get_svg_linkedin_icon() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
        <path fill="#3277D4" fill-rule="evenodd" d="M21 5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5zm-2.5 8.2v5.3h-2.79v-4.93a1.4 1.4 0 00-1.4-1.4c-.77 0-1.39.63-1.39 1.4v4.93h-2.79v-8.37h2.79v1.11c.48-.78 1.47-1.3 2.32-1.3 1.8 0 3.26 1.46 3.26 3.26zM6.88 8.56a1.686 1.686 0 000-3.37 1.69 1.69 0 00-1.69 1.69c0 .93.76 1.68 1.69 1.68zm1.39 1.57v8.37H5.5v-8.37h2.77z" clip-rule="evenodd"></path>
    </svg>';
	}

	public static function get_svg_twitter_icon() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
        <path fill="#4BA8F5" d="M22.92 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.83 4.5 17.72 4 16.46 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98-3.56-.18-6.73-1.89-8.84-4.48-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 01-1.93.07 4.28 4.28 0 004 2.98 8.521 8.521 0 01-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.9 20.29 6.16 21 8.58 21c7.88 0 12.21-6.54 12.21-12.21 0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z">
        </path>
    </svg>';
	}

	public static function get_svg_whatsapp_icon() {
		return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 241.19" class="whatsapp"><path class="cls-1" fill="#25d366" d="M205,35.05A118.61,118.61,0,0,0,120.46,0C54.6,0,1,53.61,1,119.51a119.5,119.5,0,0,0,16,59.74L0,241.19l63.36-16.63a119.43,119.43,0,0,0,57.08,14.57h0A119.54,119.54,0,0,0,205,35.07v0ZM120.5,219A99.18,99.18,0,0,1,69.91,205.1l-3.64-2.17-37.6,9.85,10-36.65-2.35-3.76A99.37,99.37,0,0,1,190.79,49.27,99.43,99.43,0,0,1,120.49,219ZM175,144.54c-3-1.51-17.67-8.71-20.39-9.71s-4.72-1.51-6.75,1.51-7.72,9.71-9.46,11.72-3.49,2.27-6.45.76-12.63-4.66-24-14.84A91.1,91.1,0,0,1,91.25,113.3c-1.75-3-.19-4.61,1.33-6.07s3-3.48,4.47-5.23a19.65,19.65,0,0,0,3-5,5.51,5.51,0,0,0-.24-5.23C99,90.27,93,75.57,90.6,69.58s-4.89-5-6.73-5.14-3.73-.09-5.7-.09a11,11,0,0,0-8,3.73C67.48,71.05,59.75,78.3,59.75,93s10.69,28.88,12.19,30.9S93,156.07,123,169c7.12,3.06,12.68,4.9,17,6.32a41.18,41.18,0,0,0,18.8,1.17c5.74-.84,17.66-7.21,20.17-14.18s2.5-13,1.75-14.19-2.69-2.06-5.7-3.59l0,0Z"></path></svg>';
	}

	public static function get_svg_facebook_icon() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path fill="#425993" fill-rule="evenodd" d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h7v-7h-2v-3h2V8.5A3.5 3.5 0 0115.5 5H18v3h-2a1 1 0 00-1 1v2h3v3h-3v7h4a2 2 0 002-2V5a2 2 0 00-2-2z" clip-rule="evenodd"></path>
            </svg>';
	}

	public static function get_svg_copy_icon() {
		return '<svg aria-hidden="true" focusable="false" role="img" class="copy-icon" viewBox="0 0 16 16" width="16" height="16" fill="currentColor"><path d="M0 6.75C0 5.784.784 5 1.75 5h1.5a.75.75 0 0 1 0 1.5h-1.5a.25.25 0 0 0-.25.25v7.5c0 .138.112.25.25.25h7.5a.25.25 0 0 0 .25-.25v-1.5a.75.75 0 0 1 1.5 0v1.5A1.75 1.75 0 0 1 9.25 16h-7.5A1.75 1.75 0 0 1 0 14.25Z"></path><path d="M5 1.75C5 .784 5.784 0 6.75 0h7.5C15.216 0 16 .784 16 1.75v7.5A1.75 1.75 0 0 1 14.25 11h-7.5A1.75 1.75 0 0 1 5 9.25Zm1.75-.25a.25.25 0 0 0-.25.25v7.5c0 .138.112.25.25.25h7.5a.25.25 0 0 0 .25-.25v-7.5a.25.25 0 0 0-.25-.25Z"></path></svg>';
	}

	public static function get_svg_checked_icon() {
		return '<svg aria-hidden="true" height="16" viewBox="0 0 16 16" version="1.1" width="16" data-view-component="true" class="check-icon" fill="currentColor"><path d="M13.78 4.22a.75.75 0 0 1 0 1.06l-7.25 7.25a.75.75 0 0 1-1.06 0L2.22 9.28a.751.751 0 0 1 .018-1.042.751.751 0 0 1 1.042-.018L6 10.94l6.72-6.72a.75.75 0 0 1 1.06 0Z"></path></svg>';
	}

	public static function get_copy_button( $args = [] ) {
		$show_icon          = isset( $args['show_icon'] ) ? $args['show_icon'] : 'yes';
		$with_icon          = 'yes' === $show_icon ? 'with-icon' : 'without-icon';
		$as_raw             = isset( $args['as_raw'] ) ? 'yes' : '';
		$button_text        = isset( $args['button_text'] ) ? $args['button_text'] : esc_html__( 'Copy', 'copy-the-code' );
		$button_class       = isset( $args['button_class'] ) ? $args['button_class'] : '';
		$button_text_copied = isset( $args['button_text_copied'] ) ? $args['button_text_copied'] : esc_html__( 'Copied!', 'copy-the-code' );
		$icon_direction     = isset( $args['icon_direction'] ) ? $args['icon_direction'] : 'before';

		ob_start();
		?>
		<button class="ctc-block-copy ctc-<?php echo esc_attr( $with_icon ); ?>" copy-as-raw='<?php echo esc_html( $as_raw ); ?>' data-copied="<?php echo esc_html( $button_text_copied ); ?>">
			<?php
			if ( 'before' === $icon_direction && 'yes' === $show_icon ) {
				echo self::get_svg_copy_icon();
				echo self::get_svg_checked_icon();
			}
			echo '<span class="ctc-button-text">' . esc_html( $button_text ) . '</span>';
			if ( 'after' === $icon_direction && 'yes' === $show_icon ) {
				echo self::get_svg_copy_icon();
				echo self::get_svg_checked_icon();
			}
			?>
		</button>
		<?php
		return ob_get_clean();
	}

	/**
	 * Is shortcode used
	 *
	 * @param string $shortcode
	 * @since 3.1.0
	 */
	public static function is_shortcode_used( $shortcode = '' ) {
		global $post;
		if ( ! $post ) {
			return false;
		}

		$found = false;
		if ( has_shortcode( $post->post_content, $shortcode ) ) {
			$found = true;
		}

		return $found;
	}

	/**
	 * Get categories
	 *
	 * @since 3.2.0
	 */
	public static function get_categories() {
		return [ 'copy-the-code', 'basic' ];
	}

	/**
	 * Get common keywords
	 *
	 * @param array $keywords New keywords.
	 * @since 3.2.0
	 */
	public static function get_keywords( $keywords = [] ) {
		$default = [ 'copy', 'paste', 'clipboard', 'copy clipboard', 'copy to clipboard', 'copy anything to clipboard' ];

		return array_merge( $default, $keywords );
	}

	/**
	 * Register "Copy Content" Section
	 *
	 * @param object $self
	 */
	public static function register_copy_content_section( $self, $args = [] ) {
		$self->start_controls_section(
			'copy_content_section',
			[
				'label' => esc_html__( 'Content to Copy', 'copy-the-code' ),
			]
		);

		if ( isset( $args['before_controls'] ) ) {
			foreach ( $args['before_controls'] as $control_id => $options ) {
				$self->add_control( $control_id, $options );
			}
		}

		$self->add_control(
			'have_selector',
			[
				'label'        => esc_html__( 'Have Selector?', 'copy-the-code' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'copy-the-code' ),
				'label_off'    => esc_html__( 'No', 'copy-the-code' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$self->add_control(
			'selector',
			[
				'label'     => esc_html__( 'Selector', 'copy-the-code' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => [
					'have_selector' => 'yes',
				],
			]
		);

		$self->add_control(
			'copy_content',
			[
				'label'     => isset( $args['label'] ) ? $args['label'] : esc_html__( 'Enter Text that Copy to Clipboard', 'copy-the-code' ),
				'type'      => Controls_Manager::TEXTAREA,
				'default'   => isset( $args['default'] ) ? $args['default'] : '',
				'rows'      => 10,
				'condition' => [
					'have_selector' => [ 'no', '' ],
				],
			]
		);

		if ( isset( $args['after_controls'] ) ) {
			foreach ( $args['after_controls'] as $control_id => $options ) {
				$self->add_control( $control_id, $options );
			}
		}

		$self->end_controls_section();
	}

	/**
	 * Register "Copy Button" Section
	 *
	 * @param array  $args
	 * @param object $self
	 */
	public static function register_copy_button_section( $self, $args = [] ) {
		$default = [
			'button_text'        => isset( $args['button_text'] ) ? $args['button_text'] : esc_html__( 'Copy to Clipboard', 'copy-the-code' ),
			'button_text_copied' => isset( $args['button_text_copied'] ) ? $args['button_text_copied'] : esc_html__( 'Copied!', 'copy-the-code' ),
			'show_icon'          => 'yes',
			'icon_direction'     => 'before',
		];

		$self->start_controls_section(
			'copy_button_section',
			[
				'label' => esc_html__( 'Copy Button', 'copy-the-code' ),
			]
		);

		$self->add_control(
			'copy_button_text',
			[
				'label'   => esc_html__( 'Button Text', 'copy-the-code' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $default['button_text'],
			]
		);

		$self->add_control(
			'copy_button_text_copied',
			[
				'label'   => esc_html__( 'After Copy Text', 'copy-the-code' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $default['button_text_copied'],
			]
		);

		// Alignment.
		$self->add_responsive_control(
			'copy_button_align',
			[
				'label'     => esc_html__( 'Alignment', 'copy-the-code' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'copy-the-code' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'copy-the-code' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'copy-the-code' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ctc-block-actions' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Show Icon.
		$self->add_control(
			'copy_show_icon',
			[
				'label'        => esc_html__( 'Show Icon', 'copy-the-code' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'copy-the-code' ),
				'label_off'    => esc_html__( 'Hide', 'copy-the-code' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$self->add_control(
			'copy_icon_direction',
			[
				'label'     => esc_html__( 'Icon Direction', 'copy-the-code' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'before',
				'options'   => [
					'before' => esc_html__( 'Before', 'copy-the-code' ),
					'after'  => esc_html__( 'After', 'copy-the-code' ),
				],
				'condition' => [
					'copy_show_icon' => 'yes',
				],
			]
		);

		$self->add_responsive_control(
			'copy_icon_text_gap',
			[
				'label'      => esc_html__( 'Icon and Text Gap', 'copy-the-code' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ctc-with-icon' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'copy_show_icon' => 'yes',
				],
			]
		);

		$self->end_controls_section();
	}

	/**
	 * Register "Copy Button Style" Section
	 *
	 * @param object $self
	 */
	public static function register_copy_button_style_section( $self ) {
		self::register_style_section(
			$self,
			'Copy Button',
			'.ctc-block-copy',
			[
				'text_align'        => false,
				'normal_text_color' => [
					'selectors' => [
						'{{WRAPPER}} .ctc-block-copy'     => 'color: {{VALUE}};',
						'{{WRAPPER}} .ctc-block-copy svg' => 'fill: {{VALUE}};',
					],
				],
				'hover_text_color'  => [
					'selectors' => [
						'{{WRAPPER}} .ctc-block-copy:hover' => 'color: {{VALUE}};',
						'{{WRAPPER}} .ctc-block-copy:hover svg' => 'fill: {{VALUE}};',
					],
				],
			]
		);
	}

	/**
	 * Render "Copy Button"
	 *
	 * @param object $self
	 */
	public static function render_copy_button( $self ) {
		$button_text        = $self->get_settings_for_display( 'copy_button_text' );
		$button_text_copied = $self->get_settings_for_display( 'copy_button_text_copied' );
		$show_icon          = $self->get_settings_for_display( 'copy_show_icon' );
		$icon_direction     = $self->get_settings_for_display( 'copy_icon_direction' );

		echo self::get_copy_button(
			[
				'as_raw'             => 'yes',
				'button_text'        => $button_text,
				'button_text_copied' => $button_text_copied,
				'icon_direction'     => $icon_direction,
				'show_icon'          => $show_icon,
			]
		);
	}

	/**
	 * Render "Copy Content"
	 *
	 * @param object $self
	 * @param array  $args
	 */
	public static function render_copy_content( $self, $args = [] ) {
		$selection_target = '';
		$content          = isset( $args['content'] ) ? $args['content'] : $self->get_settings_for_display( 'copy_content' );
		$have_selector    = $self->get_settings_for_display( 'have_selector' );
		if ( 'yes' === $have_selector ) {
			$selection_target = $self->get_settings_for_display( 'selector' );
		}
		?>
		<textarea selection-target="<?php echo esc_attr( $selection_target ); ?>" class="ctc-copy-content" style="display: none;"><?php echo wp_kses_post( apply_shortcodes( $content ) ); ?></textarea>
		<?php
	}

	/**
	 * Register "Get Pro" Section
	 *
	 * @param object $self
	 * @param array  $args
	 */
	public static function register_pro_section( $self, $label ) {
		$id = str_replace( ' ', '-', $label );
		$id = strtolower( $id );

		$self->start_controls_section(
			$id . '_box',
			[
				'label' => $label . ' (Pro)',
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Buy pro.
		$self->add_control(
			$id . '_box_content',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => '<div style="text-align: center;"><h2 style="font-weight: 600;margin: 0 0 10px 0;font-size: 14px;">Unlock Premium Features!</h2><p style="margin: 0 0 10px 0;">Upgrade to Clipboard Pro for enhanced functionality and exclusive benefits.</p><p style="margin: 0 0 10px 0;">Ready to level up your experience?</p><a href="https://clipboard.agency/#pricing" class="button button-primary" target="_blank">See Pricing Plans</a></div>',
			]
		);

		$self->end_controls_section();
	}

	/**
	 * Register "Get Pro" Sections
	 *
	 * @param object $self
	 * @param array  $labels
	 */
	public static function register_pro_sections( $self, $labels = [] ) {
		foreach ( $labels as $label ) {
			self::register_pro_section( $self, $label );
		}
	}

	/**
	 * Register normal and hover styles.
	 *
	 * @param object $self
	 * @param string $label
	 * @param array  $args
	 */
	public static function register_style_section( $self, $label = '', $selector = '', $args = [] ) {
		$id = 'reusable-' . sanitize_title_with_dashes( $label );

		$size              = isset( $args['size'] ) ? $args['size'] : false;
		$text_decoration   = isset( $args['text_decoration'] ) ? $args['text_decoration'] : false;
		$margin            = isset( $args['margin'] ) ? $args['margin'] : true;
		$padding           = isset( $args['padding'] ) ? $args['padding'] : true;
		$border            = isset( $args['border'] ) ? $args['border'] : false; // Disabled.
		$border_radius     = isset( $args['border_radius'] ) ? $args['border_radius'] : true;
		$background        = isset( $args['background'] ) ? $args['background'] : false; // Disabled.
		$text_color        = isset( $args['text_color'] ) ? $args['text_color'] : false; // Disabled.
		$typography        = isset( $args['typography'] ) ? $args['typography'] : true;
		$text_align        = isset( $args['text_align'] ) ? $args['text_align'] : true;
		$box_shadow        = isset( $args['box_shadow'] ) ? $args['box_shadow'] : false; // Disabled.
		$tabs              = isset( $args['tabs'] ) ? $args['tabs'] : true;
		$normal_background = isset( $args['normal_background'] ) ? $args['normal_background'] : true;
		$normal_text_color = isset( $args['normal_text_color'] ) ? $args['normal_text_color'] : true;
		$normal_typography = isset( $args['normal_typography'] ) ? $args['normal_typography'] : false; // Disabled.
		$normal_border     = isset( $args['normal_border'] ) ? $args['normal_border'] : true;
		$normal_box_shadow = isset( $args['normal_box_shadow'] ) ? $args['normal_box_shadow'] : true;

		// Section.
		$self->start_controls_section(
			$id . '_style_section',
			[
				'label' => $label,
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		if ( $margin ) :
			$self->add_responsive_control(
				$id . '_margin',
				[
					'label'      => esc_html__( 'Margin', 'copy-the-code' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} ' . $selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
		endif;

		if ( $padding ) :
			$self->add_responsive_control(
				$id . '_padding',
				[
					'label'      => esc_html__( 'Padding', 'copy-the-code' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} ' . $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
		endif;

		if ( $border ) :
			$border_selector = '{{WRAPPER}} ' . $selector;
			if ( isset( $args['border'] ) && is_array( $args['border'] ) ) {
				if ( isset( $args['border']['selector'] ) ) {
					$border_selector = $args['border']['selector'];
				}
			}
			$self->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'     => $id . '_border',
					'selector' => $border_selector,
				]
			);
		endif;

		if ( $border_radius ) :
			$self->add_responsive_control(
				$id . '_border_radius',
				[
					'label'      => esc_html__( 'Border Radius', 'copy-the-code' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} ' . $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
		endif;

		if ( $background ) :
			$self->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'     => $id . '_background',
					'types'    => [ 'classic', 'gradient', 'image', 'video' ],
					'selector' => '{{WRAPPER}} ' . $selector,
				]
			);
		endif;

		if ( $text_color ) :
			$self->add_control(
				$id . '_text_color',
				[
					'label'     => esc_html__( 'Text Color', 'copy-the-code' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} ' . $selector => 'color: {{VALUE}};',
					],
				]
			);
		endif;

		if ( $typography ) :
			$self->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => $id . '_typography',
					'selector' => '{{WRAPPER}} ' . $selector,
				]
			);
		endif;

		if ( $size ) :
			$size_label     = esc_html__( 'Width', 'copy-the-code' );
			$size_selectors = [
				'{{WRAPPER}} ' . $selector => 'width: {{SIZE}}{{UNIT}};',
			];
			if ( isset( $args['size'] ) && is_array( $args['size'] ) ) {
				if ( isset( $args['size']['selectors'] ) ) {
					$size_selectors = $args['size']['selectors'];
				}
				if ( isset( $args['size']['label'] ) ) {
					$size_label = $args['size']['label'];
				}
			}

			$self->add_responsive_control(
				$id . '_size',
				[
					'label'      => $size_label,
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => $size_selectors,
				]
			);
		endif;

		if ( $text_decoration ) :
			$text_decoration_selectors = [
				'{{WRAPPER}} ' . $selector => 'text-decoration: {{VALUE}};',
			];
			if ( isset( $args['text_decoration'] ) && is_array( $args['text_decoration'] ) ) {
				if ( isset( $args['text_decoration']['selectors'] ) ) {
					$text_decoration_selectors = $args['text_decoration']['selectors'];
				}
			}

			$self->add_control(
				$id . '_text_decoration',
				[
					'label'     => esc_html__( 'Text Decoration', 'copy-the-code' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => [
						'none'         => esc_html__( 'None', 'copy-the-code' ),
						'underline'    => esc_html__( 'Underline', 'copy-the-code' ),
						'overline'     => esc_html__( 'Overline', 'copy-the-code' ),
						'line-through' => esc_html__( 'Line Through', 'copy-the-code' ),
					],
					'selectors' => $text_decoration_selectors,
				]
			);
		endif;

		if ( $text_align ) :
			$self->add_responsive_control(
				$id . '_text_align',
				[
					'label'     => esc_html__( 'Alignment', 'copy-the-code' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => [
						'left'   => [
							'title' => esc_html__( 'Left', 'copy-the-code' ),
							'icon'  => 'eicon-text-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'copy-the-code' ),
							'icon'  => 'eicon-text-align-center',
						],
						'right'  => [
							'title' => esc_html__( 'Right', 'copy-the-code' ),
							'icon'  => 'eicon-text-align-right',
						],
					],
					'selectors' => [
						'{{WRAPPER}} ' . $selector => 'text-align: {{VALUE}};',
					],
				]
			);
		endif;

		if ( $box_shadow ) :
			$self->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => $id . '_box_shadow',
					'selector' => '{{WRAPPER}} ' . $selector,
				]
			);
		endif;

		if ( $tabs ) :
			// Tabs.
			$self->start_controls_tabs(
				$id . '_style_tabs'
			);

			// Normal.
			$self->start_controls_tab(
				$id . '_style_normal_tab',
				[
					'label' => esc_html__( 'Normal', 'copy-the-code' ),
				]
			);

			if ( $normal_background ) :
				$self->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name'     => $id . '_normal_background',
						'types'    => [ 'classic', 'gradient', 'image', 'video' ],
						'selector' => '{{WRAPPER}} ' . $selector,
					]
				);
			endif;

			if ( $normal_text_color ) :
				$normal_text_color_label     = esc_html__( 'Text Color', 'copy-the-code' );
				$normal_text_color_selectors = [
					'{{WRAPPER}} ' . $selector => 'color: {{VALUE}};',
				];
				if ( isset( $args['normal_text_color'] ) && is_array( $args['normal_text_color'] ) ) {
					if ( isset( $args['normal_text_color']['selectors'] ) ) {
						$normal_text_color_selectors = $args['normal_text_color']['selectors'];
					}
					if ( isset( $args['normal_text_color']['label'] ) ) {
						$normal_text_color_label = $args['normal_text_color']['label'];
					}
				}
				$self->add_control(
					$id . '_normal_text_color',
					[
						'label'     => $normal_text_color_label,
						'type'      => Controls_Manager::COLOR,
						'selectors' => $normal_text_color_selectors,
					]
				);
			endif;

			if ( $normal_typography ) :
				$self->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name'     => $id . '_normal_typography',
						'selector' => '{{WRAPPER}} ' . $selector,
					]
				);
			endif;

			if ( $normal_border ) :
				$self->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name'     => $id . '_normal_border',
						'default'  => 'red',
						'selector' => '{{WRAPPER}} ' . $selector,
					]
				);
			endif;

			if ( $normal_box_shadow ) :
				$self->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name'     => $id . '_normal_box_shadow',
						'selector' => '{{WRAPPER}} ' . $selector,
					]
				);
			endif;

			$self->end_controls_tab();
			// Normal End.

			// Hover.
			$self->start_controls_tab(
				$id . '_style_hover_tab',
				[
					'label' => esc_html__( 'Hover', 'copy-the-code' ),
				]
			);

			if ( $normal_background ) :
				$self->add_group_control(
					Group_Control_Background::get_type(),
					[
						'name'     => $id . '_hover_background',
						'types'    => [ 'classic', 'gradient', 'image', 'video' ],
						'selector' => '{{WRAPPER}} ' . $selector . ':hover',
					]
				);
			endif;

			if ( $normal_text_color ) :

				$hover_text_color_label     = esc_html__( 'Text Color', 'copy-the-code' );
				$hover_text_color_selectors = [
					'{{WRAPPER}} ' . $selector => 'color: {{VALUE}};',
				];
				if ( isset( $args['hover_text_color'] ) && is_array( $args['hover_text_color'] ) ) {
					if ( isset( $args['hover_text_color']['selectors'] ) ) {
						$hover_text_color_selectors = $args['hover_text_color']['selectors'];
					}
					if ( isset( $args['hover_text_color']['label'] ) ) {
						$hover_text_color_label = $args['hover_text_color']['label'];
					}
				}

				$self->add_control(
					$id . '_hover_text_color',
					[
						'label'     => $hover_text_color_label,
						'type'      => Controls_Manager::COLOR,
						'selectors' => $hover_text_color_selectors,
					]
				);
			endif;

			if ( $normal_border ) :
				$self->add_control(
					$id . '_hover_border',
					[
						'label'     => esc_html__( 'Border Color', 'copy-the-code' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} ' . $selector . ':hover' => 'border-color: {{VALUE}};',
						],
					]
				);
			endif;

			if ( $normal_box_shadow ) :
				$self->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name'     => $id . '_hover_box_shadow',
						'selector' => '{{WRAPPER}} ' . $selector . ':hover',
					]
				);
			endif;

			$self->end_controls_tab();
			// Hover End.

			$self->end_controls_tabs();
			// Tabs.
		endif;

		$self->end_controls_section();
		// Section End.
	}

}
