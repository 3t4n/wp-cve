<?php

namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Settings\Dashboard;
use AbsoluteAddons\MailChimp;
use AbsoluteAddons\Absp_Widget;
use AbsoluteAddons\Controls\Absp_Control_Styles;
use Elementor\Controls_Manager;
use Elementor\Utils;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class Absoluteaddons_Style_Countdown extends Absp_Widget {

	private $mailchimp_options;

	/**
	 * @var MailChimp
	 */
	private $mailchimp_client;

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		$this->mailchimp_options = Dashboard::get_tab_section_option( 'integrations', 'mailchimp' );
		if ( isset( $this->mailchimp_options['api_key'] ) && $this->mailchimp_options['api_key'] ) {
			$this->mailchimp_client = new MailChimp( $this->mailchimp_options['api_key'] );
			try {
				$this->mailchimp_client->load();
			} catch ( \InvalidArgumentException $e ) {

			}
		}
	}

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'absolute-countdown';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_title() {
		return esc_html__( 'Countdown', 'absolute-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'absp eicon-countdown';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [
			'sweetalert2',
			'absolute-addons-custom',
			'absolute-addons-datetimepicker',
			'absp-count-down',
			'absp-pro-count-down',
		];
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return [
			'sweetalert2',
			'wp-util',
			'absolute-addons-countdown',
			'absp-count-down',
		];
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'absp-widgets' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function register_controls() {

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Countdown $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ &$this ] );

		$this->start_controls_section( 'template_layout', [ 'label' => esc_html__( 'Template Style', 'absolute-addons' ) ] );

		$styles = apply_filters( 'absp/widgets/countdown/styles', [
			'one'       => esc_html__( 'One', 'absolute-addons' ),
			'two'       => esc_html__( 'Two', 'absolute-addons' ),
			'three'     => esc_html__( 'Three', 'absolute-addons' ),
			'four-pro'  => esc_html__( 'Four (Pro)', 'absolute-addons' ),
			'five-pro'  => esc_html__( 'Five (Pro)', 'absolute-addons' ),
			'six-pro'   => esc_html__( 'Six (Pro)', 'absolute-addons' ),
			'seven-pro' => esc_html__( 'Seven (Pro)', 'absolute-addons' ),
			'eight-pro' => esc_html__( 'Eight (Pro)', 'absolute-addons' ),
			'nine-pro'  => esc_html__( 'Nine (Pro)', 'absolute-addons' ),
			'ten-pro'   => esc_html__( 'Ten (Pro)', 'absolute-addons' ),
		] );

		$this->add_control(
			'absolute_countdown',
			[
				'label'   => esc_html__( 'Count Down Style', 'absolute-addons' ),
				'type'    => Absp_Control_Styles::TYPE,
				'options' => $styles,
				'default' => 'one',
			]
		);

		$this->init_pro_alert( [
			'four-pro',
			'five-pro',
			'six-pro',
			'seven-pro',
			'eight-pro',
			'nine-pro',
			'ten-pro',
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_content', [ 'label' => esc_html__( 'Content', 'absolute-addons' ) ] );

		$this->add_control(
			'countdown_datetime',
			[
				'label'       => esc_html__( 'Due Date', 'absolute-addons' ),
				'type'        => Controls_Manager::DATE_TIME,
				'default'     => gmdate( 'Y-m-d H:i', absp_get_wp_time( '+1 month' ) ),
				/* translators: 1. Current timezone of the website. */
				'description' => sprintf( __( 'Date set according to your timezone: %s, with <code>yyyy/mm/dd hh:mm:ss</code> format.', 'absolute-addons' ), Utils::get_timezone_string() ),
				'label_block' => true,
			]
		);

		if ( ! absp_has_pro() ) {
			$this->add_control( 'absp_alert_timezone', [
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => __( 'You can select custom timezone with the Pro Version', 'absolute-addons' ),
			] );
		} else {
			$this->render_controller( 'timezone' );
		}
		$this->add_control(
			'countdown_show_days',
			[
				'label'        => esc_html__( 'Days', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'absolute-addons' ),
				'label_off'    => esc_html__( 'Hide', 'absolute-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'countdown_show_hours',
			[
				'label'        => esc_html__( 'Hours', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'absolute-addons' ),
				'label_off'    => esc_html__( 'Hide', 'absolute-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'countdown_show_minutes',
			[
				'label'        => esc_html__( 'Minutes', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'absolute-addons' ),
				'label_off'    => esc_html__( 'Hide', 'absolute-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'countdown_show_seconds',
			[
				'label'        => esc_html__( 'Seconds', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'absolute-addons' ),
				'label_off'    => esc_html__( 'Hide', 'absolute-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'countdown_change_labels',
			[
				'label'        => esc_html__( 'Change Labels/Show Labels', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'absolute-addons' ),
				'label_off'    => esc_html__( 'No', 'absolute-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'countdown_label_days',
			[
				'label'       => esc_html__( 'Days', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'DAYS', 'absolute-addons' ),
				'placeholder' => esc_html__( 'Days', 'absolute-addons' ),
				'condition'   => [
					'countdown_change_labels' => 'yes',
					'countdown_show_days'     => 'yes',
				],
			]
		);
		$this->add_control(
			'countdown_label_hours',
			[
				'label'       => esc_html__( 'Hours', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'HOURS', 'absolute-addons' ),
				'placeholder' => esc_html__( 'Hours', 'absolute-addons' ),
				'condition'   => [
					'countdown_change_labels' => 'yes',
					'countdown_show_hours'    => 'yes',
				],
			]
		);
		$this->add_control(
			'countdown_label_minutes',
			[
				'label'       => esc_html__( 'Minutes', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'MINUTES', 'absolute-addons' ),
				'placeholder' => esc_html__( 'Minutes', 'absolute-addons' ),
				'condition'   => [
					'countdown_change_labels' => 'yes',
					'countdown_show_minutes'  => 'yes',
				],
			]
		);
		$this->add_control(
			'countdown_label_seconds',
			[
				'label'       => esc_html__( 'Seconds', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'SECONDS', 'absolute-addons' ),
				'placeholder' => esc_html__( 'Seconds', 'absolute-addons' ),
				'condition'   => [
					'countdown_change_labels' => 'yes',
					'countdown_show_seconds'  => 'yes',
				],
			]
		);

		$this->add_control(
			'countdown_separator',
			[
				'label'        => esc_html__( 'Display Separator', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'countdown_separator_input',
			[
				'label'     => esc_html__( 'Separator Input', 'absolute-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => ':',
				'condition' => [
					'countdown_separator' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'countdown_box_spacing',
			[
				'label'      => __( 'Box Gap', 'absolute-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-flex-wrapper' => 'gap:{{SIZE}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'absolute_countdown',
							'operator' => '!==',
							'value'    => 'four',
						],
					],
				],
			]
		);
		$this->add_responsive_control(
			'countdown_box_width',
			[
				'label'      => __( 'Box Width', 'absolute-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 150,
						'max' => 1000,
					],
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_countdown',
							'operator' => '==',
							'value'    => 'one',
						],
						[
							'name'     => 'absolute_countdown',
							'operator' => '==',
							'value'    => 'two',
						],
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .absp-wrapper .absp-count-down-item .absp-countdown-flex-wrapper .absp-countdown-flex-inner.absp-countdown-enable' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->render_controller( 'pro' );

		// Mailchimp
		if ( $this->mailchimp_client && $this->mailchimp_client->has_key() ) {
			$this->add_control(
				'form_sec',
				[
					'label'      => esc_html__( 'Subscription Form', 'absolute-addons' ),
					'type'       => Controls_Manager::HEADING,
					'separator'  => 'before',
					'conditions' => [
						'relation' => 'and',
						'terms'    => [
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'three',
							],
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'four',
							],
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'eight',
							],
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'nine',
							],
						],
					],
				]
			);
			$this->add_control(
				'enable_subs_form',
				[
					'label'        => esc_html__( 'Enable Subscription Form', 'absolute-addons' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'absolute-addons' ),
					'label_off'    => esc_html__( 'No', 'absolute-addons' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'conditions'   => [
						'relation' => 'and',
						'terms'    => [
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'three',
							],
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'four',
							],
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'eight',
							],
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'nine',
							],
						],
					],
				]
			);
			$this->add_control(
				'list_id',
				[
					'label'      => esc_html__( 'Select Audience List', 'absolute-addons' ),
					'type'       => Controls_Manager::SELECT2,
					'default'    => $this->mailchimp_options['audience_list'],
					'options'    => get_option( 'absp_mc_audience_list', [ '-1' => __( 'No List Available', 'absolute-addons' ) ] ),
					'conditions' => [
						'relation'         => 'and',
						'enable_subs_form' => 'yes',
						'terms'            => [
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'three',
							],
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'four',
							],
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'eight',
							],
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'nine',
							],
						],
					],
				]
			);
			$this->add_control(
				'form_title',
				[
					'label'      => esc_html__( 'Form Title', 'absolute-addons' ),
					'type'       => Controls_Manager::TEXT,
					'default'    => esc_html__( 'Stay update', 'absolute-addons' ),
					'conditions' => [
						'relation'         => 'and',
						'enable_subs_form' => 'yes',
						'terms'            => [
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'three',
							],
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'four',
							],
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'eight',
							],
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'nine',
							],
						],
					],
				]
			);
			$this->add_control(
				'form_success',
				[
					'label'      => esc_html__( 'Success Message', 'absolute-addons' ),
					'type'       => Controls_Manager::TEXT,
					'default'    => esc_html__( 'Thank you for subscribing!', 'absolute-addons' ),
					'conditions' => [
						'relation'         => 'and',
						'enable_subs_form' => 'yes',
						'terms'            => [
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'three',
							],
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'four',
							],
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'eight',
							],
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'nine',
							],
						],
					],
				]
			);
			$this->add_control(
				'form_invalid',
				[
					'label'      => esc_html__( 'Invalid Email Message', 'absolute-addons' ),
					'type'       => Controls_Manager::TEXT,
					'default'    => esc_html__( 'Email address is not valid!', 'absolute-addons' ),
					'conditions' => [
						'relation'         => 'and',
						'enable_subs_form' => 'yes',
						'terms'            => [
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'three',
							],
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'four',
							],
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'eight',
							],
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'nine',
							],
						],
					],
				]
			);
		} else {
			$this->add_control(
				'form_sec',
				[
					'type'       => Controls_Manager::RAW_HTML,
					'separator'  => 'before',
					'raw'        => '<h3 style="text-align: center">' . esc_html__( 'Please configure Mailchimp API Key to use subscription form.', 'absolute-addons' ) . '</h3>',
					'conditions' => [
						'relation' => 'and',
						'terms'    => [
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'three',
							],
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'four',
							],
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'eight',
							],
							[
								'name'     => 'absolute_countdown',
								'operator' => '!==',
								'value'    => 'nine',
							],
						],
					],
				]
			);
		}

		$this->end_controls_section();

		$this->render_controller( 'style-controller-count-down-item-settings' );
		$this->render_controller( 'style-controller-count-down-item-day' );
		$this->render_controller( 'style-controller-count-down-item-hour' );
		$this->render_controller( 'style-controller-count-down-item-minute' );
		$this->render_controller( 'style-controller-count-down-item-second' );
		$this->render_controller( 'style-controller-count-down-item-separator' );
		$this->render_controller( 'style-controller-count-down-item-title' );
		$this->render_controller( 'style-controller-count-down-item-sub-title' );
		$this->render_controller( 'style-controller-count-down-item-content' );
		$this->render_controller( 'style-controller-count-down-subscription-form' );

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Countdown $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ &$this ] );
	}

	protected $current_style;

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		$day      = $settings['countdown_show_days'];
		$hour     = $settings['countdown_show_hours'];
		$minute   = $settings['countdown_show_minutes'];
		$second   = $settings['countdown_show_seconds'];

		// set current style.
		$this->current_style = $settings['absolute_countdown'];

		$this->add_inline_editing_attributes( 'countdown_label_days' );
		$this->add_render_attribute( 'countdown_label_days', 'class', 'absp-countdown-label-day' );

		$this->add_inline_editing_attributes( 'countdown_label_hours' );
		$this->add_render_attribute( 'countdown_label_hours', 'class', 'absp-countdown-label-hour' );

		$this->add_inline_editing_attributes( 'countdown_label_minutes' );
		$this->add_render_attribute( 'countdown_label_minutes', 'class', 'absp-countdown-label-minute' );

		$this->add_inline_editing_attributes( 'countdown_label_seconds' );
		$this->add_render_attribute( 'countdown_label_seconds', 'class', 'absp-countdown-label-second' );

		$this->add_inline_editing_attributes( 'form_title' );
		$this->add_render_attribute( 'form_title', 'class', 'absp-countdown-subscribe-title' );

		$this->add_inline_editing_attributes( 'title' );
		$this->add_render_attribute( 'title', 'class', 'title' );

		$this->add_inline_editing_attributes( 'sub_title' );
		$this->add_render_attribute( 'sub_title', 'class', 'sub-title' );

		// separator
		$separator = '';

		if ( 'yes' === $settings['countdown_separator'] ) {
			$separator = 'countdown-show-separator countdown-separator-' . $settings['absolute_countdown'];
		}

		$date_time = $settings['countdown_datetime'];
		if ( absp_has_pro() && ! empty( $settings['timezone'] ) ) {
			$timezone_offset = str_replace( '.5', '30', (string) $settings['timezone'] );
			if ( 0 == $settings['timezone'] ) {
				$date_time .= ' GMT+0';
			} elseif ( $settings['timezone'] < 0 ) {
				$date_time .= ' GMT-' . $timezone_offset;
			} else {
				$date_time .= ' GMT+' . $timezone_offset;
			}
		}

		$this->add_render_attribute( [
			'absolute_countdown' => [
				'id'        => 'absp-countdown-' . $this->get_id(),
				'class'     => 'absp-count-down-item element-' . $settings['absolute_countdown'],
				'data-time' => $date_time,
			],
		] );
		?>
		<div class="absp-wrapper absp-widget">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-count-down-item -->
					<div <?php $this->print_render_attribute_string( 'absolute_countdown' ); ?>>
						<div class="absp-countdown-wrapper">
							<?php
							$this->render_template( $settings['absolute_countdown'], [
								'day'       => $day,
								'hour'      => $hour,
								'minute'    => $minute,
								'second'    => $second,
								'separator' => $separator,
							] );
							?>
						</div>
						<?php
						if ( in_array( $this->current_style, [ 'one', 'two', 'five', 'six', 'seven' ] ) ) {
							$this->render_subscription_form( $settings );
						}
						?>
					</div>
					<!-- absp-count-down-item -->
				</div>
			</div>
		</div>
		<?php
	}

	protected function render_subscription_form( $settings ) {
		if ( isset( $settings['enable_subs_form'] ) && absp_string_to_bool( $settings['enable_subs_form'] ) ) {
			$uid = wp_unique_id( 'form_' );
			?>
			<div class="absp-countdown-email-subscribe">
				<?php if ( ! empty( $settings['form_title'] ) ) { ?>
					<div class="absp-countdown-email-subscribe-flex-inner">
						<span <?php $this->print_render_attribute_string( 'form_title' ); ?>><?php absp_render_title( $settings['form_title'] ); ?></span>
					</div>
				<?php } ?>
				<div class="absp-countdown-email-subscribe-flex-inner">
					<div class="absp-countdown-subscribe-form">
						<form action="#" method="post" data-list-id="<?php echo esc_attr( $settings['list_id'] ); ?>"
							  data-invalid="<?php echo esc_attr( $settings['form_invalid'] ); ?>"
							  data-success-message="<?php echo esc_attr( $settings['form_success'] ); ?>">
							<div class="absp-countdown-subscribe-inline-form">
								<div
									class="absp-countdown-subscribe-email-input-field-inner absp-countdown-input-wrapper">
									<label class="sr-only"
										   for="<?php echo esc_attr( $uid ); ?>_email"><?php esc_html_e( 'Your email', 'absolute-addons' ); ?></label>
									<input type="email" name="email" id="<?php echo esc_attr( $uid ); ?>_email"
										   class="absp-countdown-subscribe-email-input-field"
										   placeholder="<?php esc_attr_e( 'Your email', 'absolute-addons' ); ?>"
										   required>
								</div>
								<div class="absp-countdown-subscribe-submit-btn-inner absp-countdown-input-wrapper">
									<input type="submit" class="absp-countdown-subscribe-submit-btn" name="subscribe"
										   value="<?php esc_attr_e( 'Submit', 'absolute-addons' ); ?>">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<?php
		}
	}

	protected function get_digit_classes( $which, $settings, $separator = true ) {

		$class = 'absp-countdown-flex-inner absp-countdown-' . $which . '-wrapper';
		if ( 'yes' === $settings[ 'countdown_show_' . $which ] ) {
			$class .= ' absp-countdown-enable';
		}
		if ( $separator ) {
			if ( 'yes' === $settings['countdown_separator'] ) {
				$class .= ' countdown-show-separator countdown-separator-' . $settings['absolute_countdown'];
			}
		}

		return $class;
	}

	protected function render_days( $settings, $separator = true, $id = '' ) {
		?>
		<div class="<?php echo esc_attr( $this->get_digit_classes( 'days', $settings, $separator ) ); ?>">
			<div class="absp-countdown-inner">
				<div class="absp-item">
					<span class="absp-countdown-digits-day days"
						  aria-labelledby="day-countdown-<?php echo esc_html( $id ); ?>">00</span>
					<?php if ( ! empty( $settings['countdown_label_days'] ) ) { ?>
						<span <?php $this->print_render_attribute_string( 'countdown_label_days' ); ?>><?php absp_render_title( $settings['countdown_label_days'] ); ?></span>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php if ( 'yes' === $settings['countdown_separator'] ) { ?>
			<div
				class="absp-countdown-separator"><?php echo esc_html( $settings['countdown_separator_input'] ); ?></div>
			<?php
		}
	}

	protected function render_hours( $settings, $separator = true, $id = '' ) {
		?>
		<div class="<?php echo esc_attr( $this->get_digit_classes( 'hours', $settings, $separator ) ); ?>">
			<div class="absp-countdown-inner">
				<div class="absp-item">
					<span class="absp-countdown-digits-hour hours"
						  aria-labelledby="hour-countdown-<?php echo esc_html( $id ); ?>">00</span>
					<?php if ( ! empty( $settings['countdown_label_hours'] ) ) { ?>
						<span <?php $this->print_render_attribute_string( 'countdown_label_hours' ); ?>><?php absp_render_title( $settings['countdown_label_hours'] ); ?></span>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php if ( 'yes' === $settings['countdown_separator'] ) { ?>
			<div
				class="absp-countdown-separator"><?php echo esc_html( $settings['countdown_separator_input'] ); ?></div>
			<?php
		}
	}

	protected function render_minutes( $settings, $separator = true, $id = '' ) {
		?>
		<div class="<?php echo esc_attr( $this->get_digit_classes( 'minutes', $settings, $separator ) ); ?>">
			<div class="absp-countdown-inner">
				<div class="absp-item">
					<span class="absp-countdown-digits-minute minutes"
						  aria-labelledby="minute-countdown-<?php echo esc_html( $id ); ?>">00</span>
					<?php if ( ! empty( $settings['countdown_label_minutes'] ) ) { ?>
						<span <?php $this->print_render_attribute_string( 'countdown_label_minutes' ); ?>><?php absp_render_title( $settings['countdown_label_minutes'] ); ?></span>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php if ( 'yes' === $settings['countdown_separator'] ) { ?>
			<div
				class="absp-countdown-separator"><?php echo esc_html( $settings['countdown_separator_input'] ); ?></div>
			<?php
		}
	}

	protected function render_seconds( $settings, $separator = true, $id = '' ) {
		?>
		<div class="<?php echo esc_attr( $this->get_digit_classes( 'seconds', $settings, $separator ) ); ?>">
			<div class="absp-countdown-inner">
				<div class="absp-item">
					<span class="absp-countdown-digits-second seconds"
						  aria-labelledby="second-countdown-<?php echo esc_html( $id ); ?>">00</span>
					<?php if ( ! empty( $settings['countdown_label_seconds'] ) ) { ?>
						<span <?php $this->print_render_attribute_string( 'countdown_label_seconds' ); ?>><?php absp_render_title( $settings['countdown_label_seconds'] ); ?></span>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php if ( 'yes' === $settings['countdown_separator'] ) { ?>
			<div
				class="absp-countdown-separator"><?php echo esc_html( $settings['countdown_separator_input'] ); ?></div>
			<?php
		}
	}

	protected function render_digits( $settings, $separator = true, $id = '' ) {
		$this->render_days( $settings, $separator, $id );
		$this->render_hours( $settings, $separator, $id );
		$this->render_minutes( $settings, $separator, $id );
		$this->render_seconds( $settings, $separator, $id );
	}
}
