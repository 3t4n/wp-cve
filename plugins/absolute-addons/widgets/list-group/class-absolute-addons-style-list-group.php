<?php

namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Controls\Absp_Control_Styles;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use AbsoluteAddons\Absp_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class Absoluteaddons_Style_List_Group extends Absp_Widget {

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
		return 'absolute-list-group';
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
		return __( 'List Group', 'absolute-addons' );
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
		return 'absp eicon-bullet-list';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [
			'absolute-addons-custom',
			'absp-list-group',
			'absp-pro-list-group',
		];
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'absolute-addons-list-group',
		);
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
		 * @param Absoluteaddons_Style_List_Group $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ &$this ] );

		$this->start_controls_section(
			'section_template',
			array(
				'label' => __( 'Template', 'absolute-addons' ),
			)
		);

		$list_group = apply_filters( $this->get_prefixed_hook( 'styles' ), [
			'one'        => esc_html__( 'Style One', 'absolute-addons' ),
			'two'        => esc_html__( 'Style Two', 'absolute-addons' ),
			'three-pro'  => esc_html__( 'Style Three Pro', 'absolute-addons' ),
			'four-pro'   => esc_html__( 'Style Four Pro', 'absolute-addons' ),
			'five-pro'   => esc_html__( 'Style Five Pro', 'absolute-addons' ),
			'six-pro'    => esc_html__( 'Style Six Pro', 'absolute-addons' ),
			'seven-pro'  => esc_html__( 'Style Seven Pro', 'absolute-addons' ),
			'eight-pro'  => esc_html__( 'Style Eight Pro', 'absolute-addons' ),
			'nine-pro'   => esc_html__( 'Style Nine Pro', 'absolute-addons' ),
			'ten'        => esc_html__( 'Style Ten', 'absolute-addons' ),
			'eleven-pro' => esc_html__( 'Style Eleven Pro', 'absolute-addons' ),
			'twelve'     => esc_html__( 'Style Twelve', 'absolute-addons' ),
			'thirteen'   => esc_html__( 'Style Thirteen', 'absolute-addons' ),
			'fourteen'   => esc_html__( 'Style Fourteen', 'absolute-addons' ),
		] );

		$pro_styles = [
			'three-pro',
			'four-pro',
			'five-pro',
			'six-pro',
			'seven-pro',
			'eight-pro',
			'nine-pro',
			'eleven-pro',
		];

		$this->add_control(
			'absolute_list_group',
			array(
				'label'       => esc_html__( 'List Group Style', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Absp_Control_Styles::TYPE,
				'options'     => $list_group,
				'default'     => 'one',
			)
		);

		$this->init_pro_alert( $pro_styles );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			array(
				'label'     => __( 'Content', 'absolute-addons' ),
				'condition' => [
					'absolute_list_group' => [ 'one', 'three', 'four', 'six', 'eight', 'ten', 'thirteen' ],
				],
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title_link_select',
			[
				'label'   => esc_html__( 'Title Select', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'yes'  => esc_html__( 'With Link Title', 'absolute-addons' ),
					'none' => esc_html__( 'Without Title', 'absolute-addons' ),
				],
				'default' => 'yes',
			]
		);

		$repeater->add_control(
			'list_group_title',
			[
				'label'       => esc_html__( 'Title', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Stackworks Solution', 'absolute-addons' ),
			]
		);

		$repeater->add_control(
			'link_url',
			[
				'label'         => esc_html__( 'Link', 'absolute-addons' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'absolute-addons' ),
				'show_external' => true,
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
				'condition'     => [
					'title_link_select' => [ 'yes' ],
				],
			]
		);

		$repeater->add_control(
			'list_group_icon',
			[
				'label'            => esc_html__( 'Icon', 'absolute-addons' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'absolute-addons',
				'default'          => [
					'value'   => 'fas fa-angle-right',
					'library' => 'solid',
				],
			]
		);

		$this->add_control(
			'list_group_repeater',
			[
				'label'       => esc_html__( 'List Group', 'absolute-addons' ),
				'type'        => Controls_Manager::REPEATER,
				'show_label'  => true,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[ 'list_group_title' => esc_html__( 'Stackworks Solution', 'absolute-addons' ) ],
					[ 'list_group_title' => esc_html__( 'Social Media Covering', 'absolute-addons' ) ],
					[ 'list_group_title' => esc_html__( 'Communication Design', 'absolute-addons' ) ],
					[ 'list_group_title' => esc_html__( 'Human Resources', 'absolute-addons' ) ],
				],
				'title_field' => '{{{ list_group_title }}}',
				'condition'   => [
					'absolute_list_group' => [ 'one', 'three', 'four', 'six', 'eight', 'ten', 'thirteen' ],
				],
			]
		);

		$this->end_controls_section();

		$this->render_controller( 'two' );
		$this->render_controller( 'five' );
		$this->render_controller( 'eleven' );
		$this->render_controller( 'fourteen' );
		$this->render_controller( 'template-list-group-style' );

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_list_group $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ &$this ] );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$style    = $settings['absolute_list_group'];

		if ( in_array( $style, [ 'one', 'three', 'four', 'six', 'eight', 'ten', 'thirteen' ] ) ) {
			$repeater = 'list_group_repeater';
		} elseif ( in_array( $style, [ 'two', 'seven', 'twelve' ] ) ) {
			$repeater = 'list_group_repeater_two';
		} elseif ( in_array( $style, [ 'five', 'nine' ] ) ) {
			$repeater = 'list_group_repeater_five';
		} else {
			$repeater = 'list_group_repeater_' . $style;
		}

		?>
		<div class="absp-wrapper absp-widget">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- ABSP List Group -->
					<div class="absp-list-group element-<?php echo esc_attr( $style ); ?>">
						<ul class="list-group list-group-style-<?php echo esc_attr( $style ); ?>">
							<?php foreach ( $settings[ $repeater ] as $index => $list ) { ?>
								<li class="list-group-item elementor-repeater-item-<?php echo esc_attr( $list['_id'] ); ?>">
									<?php
									$this->render_template( [
										'list'  => $list,
										'index' => $index,
									] );
									?>
								</li>
							<?php } ?>
						</ul>
					</div>
					<!-- ABSP List Group -->
				</div>
			</div>
		</div>
		<?php
	}

	protected function get_button( $settings = '' ) {
		if ( ! empty( $settings['link_url']['url'] ) ) {
			$this->add_link_attributes( 'button', $settings['link_url'] );
		}
		?>
		<a <?php $this->print_render_attribute_string( 'button' ); ?>><?php absp_render_title( $settings['list_group_title'] ); ?></a>
		<?php
	}

	protected function render_title( $list ) {
		if ( 'yes' == $list['title_link_select'] ) { ?>
			<h4><?php $this->get_button( $list ); ?></h4>
		<?php } else { ?>
			<h4><?php echo esc_html( $list['list_group_title'] ); ?></h4>
		<?php }
	}

	protected function render_list_icon( $list ) {
		?>
		<div class="list-icon"><?php Icons_Manager::render_icon( $list['list_group_icon_list'], [ 'aria-hidden' => 'true' ] ); ?></div>
		<?php
	}

	protected function render_list_group_icon( $list ) {
		?>
		<div class="list-group-icon"><?php Icons_Manager::render_icon( $list['list_group_icon'], [ 'aria-hidden' => 'true' ] ); ?></div>
		<?php
	}
}
