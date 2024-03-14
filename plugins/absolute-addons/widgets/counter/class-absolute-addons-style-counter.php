<?php
/**
 * Counter Widget
 */

namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Absp_Widget;
use AbsoluteAddons\Controls\Absp_Control_Styles;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Absoluteaddons_Style_Counter
 * @package AbsoluteAddons\Widgets
 */
class Absoluteaddons_Style_Counter extends Absp_Widget {

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
		return 'absolute-counter';
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
		return __( 'Counter', 'absolute-addons' );
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
		return 'absp eicon-counter';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [
			'absolute-addons-custom',
			'font-awesome',
			'fontawesome',
			'ico-font',
			'lineProgressbar',
			'absp-counter',
			'absp-pro-counter',
		];
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return [
			'absolute-addons-counter',
			'waypoints',
			'counterup',
			'lineProgressbar',
			'absp-counter',
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
		 * @param Absoluteaddons_Style_Counter $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ & $this ] );

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Template', 'absolute-addons' ),
			]
		);

		$counter_styles = apply_filters( $this->get_prefixed_hook( 'styles' ), [
			'one'       => esc_html__( 'Style One', 'absolute-addons' ),
			'two'       => esc_html__( 'Style Two', 'absolute-addons' ),
			'three-pro' => esc_html__( 'Style Three (Pro)', 'absolute-addons' ),
			'four-pro'  => esc_html__( 'Style Four (Pro)', 'absolute-addons' ),
			'five'      => esc_html__( 'Style Five', 'absolute-addons' ),
			'six'       => esc_html__( 'Style Six', 'absolute-addons' ),
			'seven'     => esc_html__( 'Style Seven', 'absolute-addons' ),
			'eight-pro' => esc_html__( 'Style Eight (Pro)', 'absolute-addons' ),
			'nine'      => esc_html__( 'Style Nine', 'absolute-addons' ),
		] );

		$this->add_control(
			'absolute_counter',
			[
				'label'       => esc_html__( 'Counter Style', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Absp_Control_Styles::TYPE,
				'options'     => $counter_styles,
				'default'     => 'one',
			]
		);

		$this->init_pro_alert( [
			'three-pro',
			'four-pro',
			'eight-pro',
		] );

		$this->end_controls_section();

		$this->start_controls_section(
			'content_section',
			[
				'label'     => __( 'Content Section', 'absolute-addons' ),
				'condition' => [
					'absolute_counter' => [ 'one', 'two', 'four' ],
				],
			]
		);

		$this->add_control(
			'counter_title',
			[
				'label'       => esc_html__( 'Title', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Loved by', 'absolute-addons' ),
				'condition'   => [
					'absolute_counter' => [ 'one', 'two', 'four' ],
				],
			]
		);

		$this->add_control(
			'counter_sub_title',
			[
				'label'       => esc_html__( 'Secondary Title', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Completed', 'absolute-addons' ),
				'condition'   => [
					'absolute_counter' => [ 'four' ],
				],
			]
		);

		$this->add_control(
			'counter_number',
			[
				'label'     => esc_html__( 'Number', 'absolute-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( '169', 'absolute-addons' ),
				'condition' => [
					'absolute_counter' => [ 'one', 'two', 'four' ],
				],
			]
		);

		$this->add_control(
			'counter_number_speed',
			[
				'label'     => esc_html__( 'Speed', 'absolute-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => esc_html__( '1500', 'absolute-addons' ),
				'condition' => [
					'absolute_counter' => [ 'one', 'two', 'four' ],
				],
			]
		);

		$this->add_control(
			'counter_suffix',
			[
				'label'       => esc_html__( 'Counter Suffix', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Plus', 'absolute-addons' ),
				'default'     => '',
				'condition'   => [
					'absolute_counter' => [ 'one', 'two', 'four' ],
				],
			]
		);

		$this->add_control(
			'counter_icon_select',
			[
				'label'     => esc_html__( 'Show Icon', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'true'  => esc_html__( 'Yes', 'absolute-addons' ),
					'false' => esc_html__( 'No', 'absolute-addons' ),
				],
				'default'   => 'true',
				'condition' => [
					'absolute_counter' => 'one',
				],
			]
		);

		$this->add_control(
			'counter_icon',
			[
				'label'            => esc_html__( 'Icon', 'absolute-addons' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'absolute-addons',
				'default'          => [
					'value'   => 'fa fa-heart',
					'library' => 'solid',
				],
				'condition'        => [
					'counter_icon_select' => 'true',
					'absolute_counter'    => 'one',
				],
			]
		);

		$this->add_control(
			'counter_link_select',
			[
				'label'     => esc_html__( 'Link Select', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'true'  => esc_html__( 'Yes', 'absolute-addons' ),
					'false' => esc_html__( 'No', 'absolute-addons' ),
				],
				'default'   => 'true',
				'condition' => [
					'absolute_counter' => 'two',
				],
			]
		);

		$this->add_control(
			'link_text',
			[
				'label'     => __( 'Link Text', 'absolute-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'READ MORE',
				'condition' => [
					'counter_link_select' => 'true',
					'absolute_counter'    => 'two',
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label'       => __( 'Link', 'absolute-addons' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'absolute-addons' ),
				'condition'   => [
					'counter_link_select' => 'true',
					'absolute_counter'    => 'two',
				],
				'show_label'  => false,
			]
		);

		$this->end_controls_section();

		$this->render_controller( 'three' );
		$this->render_controller( 'five' );
		$this->render_controller( 'six' );
		$this->render_controller( 'seven' );
		$this->render_controller( 'eight' );
		$this->render_controller( 'nine' );
		$this->render_controller( 'template-counter-style' );

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Counter $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ & $this ] );
	}

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
		$style    = $settings['absolute_counter'];
		$this->add_render_attribute( [
			'absp-counterup' => [
				'class'      => 'count count-number',
				'data-delay' => '10',
				'data-time'  => $settings['counter_number_speed'],
			],
		] );
		?>

		<div class="absp-wrapper absp-widget">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-counter -->
					<div class="absp-counter element-<?php echo esc_attr( $style ); ?>">
						<div class="counter-item counter-item-<?php echo esc_attr( $style ); ?>">
							<?php if ( in_array( $style, [ 'one', 'two', 'four' ] ) ) { ?>
								<div class="counter-wrapper">
									<?php $this->render_template(); ?>
								</div>
							<?php } else { ?>
								<div class="<?php echo ( 'three' === $style ) ? 'elementor-grid' : 'counter-grid-col'; ?>">
									<?php
									foreach ( $settings[ 'counter_repeater_' . $style ] as $counter ) {
										$this->add_render_attribute( [
											'absp-counterup' => [
												'data-time' => isset( $counter[ 'counter_number_speed_' . $style ] ) ? $counter[ 'counter_number_speed_' . $style ] : '',
											],
										] );
										if ( 'three' !== $style ) {
											echo '<div class="counter-wrapper">';
										}
										$this->render_template( $style, [ 'counter' => $counter ] );
										if ( 'three' !== $style ) {
											echo '</div>';
										}
									}
									?>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<!-- absp-counter -->
			</div>
		</div>
		<?php
	}
}
