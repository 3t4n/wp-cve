<?php

namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Controls\Absp_Control_Styles;
use Elementor\Controls_Manager;
use AbsoluteAddons\Absp_Widget;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class Absoluteaddons_Style_List extends Absp_Widget {

	protected $current_style;

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
		return 'absolute-list';
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
		return __( 'List', 'absolute-addons' );
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
		return 'absp eicon-editor-list-ul';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [
			'icofont',
			'absolute-addons-core',
			'absp-list',
			'absp-pro-list',
		];
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return [ 'absolute-addons-list' ];
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
		 * @param Absoluteaddons_Style_List $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.3
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ &$this ] );

		$this->start_section( 'section_template', __( 'Template', 'absolute-addons' ) );

		$list = apply_filters( $this->get_prefixed_hook( 'styles' ), [
			'one'          => __( 'Style One', 'absolute-addons' ),
			'two'          => __( 'Style Two', 'absolute-addons' ),
			'three-pro'    => __( 'Style Three (Pro)', 'absolute-addons' ),
			'four-pro'     => __( 'Style Four (Pro)', 'absolute-addons' ),
			'five-pro'     => __( 'Style Five (Pro)', 'absolute-addons' ),
			'six'          => __( 'Style Six', 'absolute-addons' ),
			'seven-pro'    => __( 'Style Seven (Pro)', 'absolute-addons' ),
			'eight-pro'    => __( 'Style Eight (Pro)', 'absolute-addons' ),
			'nine-pro'     => __( 'Style Nine (Pro)', 'absolute-addons' ),
			'ten'          => __( 'Style Ten', 'absolute-addons' ),
			'eleven-pro'   => __( 'Style Eleven (Pro)', 'absolute-addons' ),
			'twelve'       => __( 'Style Twelve', 'absolute-addons' ),
			'thirteen'     => __( 'Style Thirteen', 'absolute-addons' ),
			'fourteen-pro' => __( 'Style Fourteen (Pro)', 'absolute-addons' ),
		] );

		$pro_styles = [
			'three-pro',
			'four-pro',
			'five-pro',
			'seven-pro',
			'eight-pro',
			'nine-pro',
			'eleven-pro',
			'fourteen-pro',
		];

		$this->add_control(
			'absolute_list',
			array(
				'label'       => esc_html__( 'List Style', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Absp_Control_Styles::TYPE,
				'options'     => $list,
				'disabled'    => [
					'fourteen-pro',
					'thirteen',
				],
				'default'     => 'one',
			)
		);

		$this->init_pro_alert( $pro_styles );

		$this->end_section();

		// Content Controllers
		$this->content_section( 'one' );
		$this->content_section( 'two' );
		$this->content_section( 'three' );
		$this->content_section( 'four' );
		$this->content_section( 'five' );
		$this->content_section( 'six' );
		$this->content_section( 'seven' );
		$this->content_section( 'eight' );
		$this->content_section( 'nine' );
		$this->content_section( 'ten' );
		$this->content_section( 'eleven' );
		$this->content_section( 'twelve' );
		$this->content_section( 'thirteen' );
		$this->content_section( 'fourteen' );

		$this->render_controller( 'template-list-style' );

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_list $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ &$this ] );
	}

	/**
	 * Render content section controllers.
	 *
	 * @param string $style
	 *
	 */
	private function content_section( $style = '' ) {
		$this->start_controls_section(
			'list_content_section_' . $style,
			[
				'label'     => __( 'Content Section', 'absolute-addons' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'absolute_list' => $style,
				],
			]
		);

		if ( in_array( $style, [ 'five', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve' ] ) ) {
			$this->add_responsive_control(
				'absp_list_column_' . $style,
				[
					'label'           => __( 'List Column', 'absolute-addons' ),
					'type'            => Controls_Manager::SELECT,
					'options'         => [
						'1' => __( '1 Column', 'absolute-addons' ),
						'2' => __( '2 Column', 'absolute-addons' ),
						'3' => __( '3 Column', 'absolute-addons' ),
						'4' => __( '4 Column', 'absolute-addons' ),
						'5' => __( '5 Column', 'absolute-addons' ),
						'6' => __( '6 Column', 'absolute-addons' ),
					],
					'default'         => '3',
					'devices'         => [ 'desktop', 'tablet', 'mobile' ],
					'desktop_default' => 3,
					'tablet_default'  => 3,
					'mobile_default'  => 2,
					'prefix_class'    => 'absp-list-grid%s-',
					'selectors'       => [
						'(desktop+){{WRAPPER}} .absp-wrapper .absp-list .absp-list-grid-col' => 'grid-template-columns: repeat({{absp_list_column_' . $style . '.VALUE}}, 1fr);',
						'(tablet){{WRAPPER}} .absp-wrapper .absp-list .absp-list-grid-col'   => 'grid-template-columns: repeat({{absp_list_column_' . $style . '_tablet.VALUE}}, 1fr);',
						'(mobile){{WRAPPER}} .absp-wrapper .absp-list .absp-list-grid-col'   => 'grid-template-columns: repeat({{absp_list_column_' . $style . '_mobile.VALUE}}, 1fr);',
					],
				]
			);
		}

		$repeater = new Repeater();

		if ( in_array( $style, [ 'one', 'two', 'three' ] ) ) {
			$repeater->add_control(
				'icon_type',
				[
					'label'          => __( 'Media Type', 'absolute-addons' ),
					'type'           => Controls_Manager::CHOOSE,
					'default'        => 'icon',
					'options'        => [
						'icon'   => [
							'title' => __( 'Icon', 'absolute-addons' ),
							'icon'  => 'eicon-star',
						],
						'number' => [
							'title' => __( 'Number', 'absolute-addons' ),
							'icon'  => 'eicon-number-field',
						],
						'image'  => [
							'title' => __( 'Image', 'absolute-addons' ),
							'icon'  => 'eicon-image',
						],
					],
					'toggle'         => false,
					'style_transfer' => true,
				]
			);

			$repeater->add_control(
				'list_icon',
				[
					'label'     => __( 'Choose a icon', 'absolute-addons' ),
					'type'      => Controls_Manager::ICONS,
					'default'   => [
						'value'   => 'fas fa-check',
						'library' => 'solid',
					],
					'condition' => [
						'icon_type' => 'icon',
					],
				]
			);

			$repeater->add_control(
				'list_number', [
					'label'       => __( 'List Number', 'absolute-addons' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( '1.', 'absolute-addons' ),
					'label_block' => true,
					'condition'   => [
						'icon_type' => 'number',
					],
				]
			);

			$repeater->add_control(
				'image',
				[
					'label'     => __( 'Image', 'absolute-addons' ),
					'type'      => Controls_Manager::MEDIA,
					'default'   => [
						'url' => absp_get_default_placeholder(),
					],
					'condition' => [
						'icon_type' => 'image',
					],
					'dynamic'   => [
						'active' => true,
					],
				]
			);
		}

		if ( in_array( $style, [ 'eight', 'nine' ] ) ) {
			$repeater->add_control(
				'list_number', [
					'label'       => __( 'List Number', 'absolute-addons' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( '1.', 'absolute-addons' ),
					'label_block' => true,
				]
			);
		}

		if ( in_array( $style, [
			'five',
			'six',
			'seven',
			'ten',
			'eleven',
			'twelve',
		] ) ) {
			$repeater->add_control(
				'list_icon',
				[
					'label'   => __( 'Choose a icon', 'absolute-addons' ),
					'type'    => Controls_Manager::ICONS,
					'default' => [
						'value'   => 'fas fa-paint-brush',
						'library' => 'solid',
					],
				]
			);
		}

		if ( [ 'six', 'nine', 'ten' ] !== $style ) {
			$repeater->add_control(
				'list_title', [
					'label'       => __( 'Title', 'absolute-addons' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'List Title', 'absolute-addons' ),
					'label_block' => true,
				]
			);
		}

		if ( in_array( $style, [ 'ten', 'thirteen' ] ) ) {
			$repeater->add_control(
				'list_sub_title',
				[
					'label'   => __( 'Sub Title', 'absolute-addons' ),
					'type'    => Controls_Manager::TEXT,
					'default' => __( 'Service One', 'absolute-addons' ),
				]
			);
		}

		if ( in_array( $style, [ 'four', 'five', 'six', 'seven', 'thirteen' ] ) ) {
			$repeater->add_control(
				'list_description',
				[
					'label'   => __( 'Description', 'absolute-addons' ),
					'type'    => Controls_Manager::TEXTAREA,
					'default' => __( 'Dolor sitamet facer consectetuer adipiscing elitsed diam', 'absolute-addons' ),
				]
			);
		}

		if ( in_array( $style, [ 'eight', 'nine', 'eleven', 'twelve' ] ) ) {
			$repeater->add_control(
				'list_description2',
				[
					'label'   => __( 'Description', 'absolute-addons' ),
					'type'    => Controls_Manager::WYSIWYG,
					'default' => __( 'Dolor sitamet facer consectetuer adipiscing elitsed diam', 'absolute-addons' ),
				]
			);
		}

		if ( 'six' === $style ) {
			$repeater->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'     => 'list_icon_bg_color',
					'label'    => esc_html__( 'Background', 'absolute-addons' ),
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .absp-wrapper .absp-list-widget {{CURRENT_ITEM}} > .absp-list-wrapper-bg',
				]
			);

			$repeater->add_control(
				'list_icon_inline_color',
				[
					'label'     => __( 'Icon Color', 'absolute-addons' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-wrapper .absp-list-widget {{CURRENT_ITEM}} .absp-list-wrapper-bg' => 'color: {{VALUE}}',
					],
				]
			);
		}

		if ( 'ten' === $style ) {
			$repeater->add_control(
				'list_icon_inline_color',
				[
					'label'     => __( 'Icon Color', 'absolute-addons' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-wrapper .absp-list-widget {{CURRENT_ITEM}} .absp-list-wrapper-bg' => 'color: {{VALUE}}',
					],
				]
			);
		}

		if ( 'five' === $style ) {
			$repeater->add_control(
				'list_title_inline_color',
				[
					'label'     => __( 'Title Color', 'absolute-addons' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-wrapper .absp-list-widget {{CURRENT_ITEM}} .list-title' => 'color: {{VALUE}}',
					],
				]
			);
		}

		if ( 'eight' === $style ) {
			$repeater->add_control(
				'list_number_inline_color',
				[
					'label'     => __( 'Number Color', 'absolute-addons' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-wrapper .absp-list-widget {{CURRENT_ITEM}} .absp-list-number ' => 'color: {{VALUE}}',
					],
				]
			);
		}

		if ( 'nine' === $style ) {
			$repeater->add_control(
				'list_item_color',
				[
					'label'     => __( 'Border and Background Color', 'absolute-addons' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-wrapper .absp-list-widget {{CURRENT_ITEM}}'       => 'border-color: {{VALUE}}',
						'{{WRAPPER}} .absp-wrapper .absp-list-widget {{CURRENT_ITEM}}:hover' => 'background: {{VALUE}}',
					],
				]
			);

			$repeater->add_control(
				'list_number_color',
				[
					'label'     => __( 'Number Color', 'absolute-addons' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-wrapper .absp-list-widget {{CURRENT_ITEM}} .absp-list-number ' => 'color: {{VALUE}}',
					],
				]
			);

			$repeater->add_control(
				'list_number_hover_color',
				[
					'label'     => __( 'Number Hover Color', 'absolute-addons' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-wrapper .absp-list-widget {{CURRENT_ITEM}}:hover .absp-list-number ' => 'color: {{VALUE}}',
					],
				]
			);
		}

		if ( in_array( $style, [ 'ten', 'thirteen' ] ) ) {
			$repeater->add_control(
				'list_sub_title_color',
				[
					'label'     => __( 'Sub Title Color', 'absolute-addons' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-wrapper .absp-list-widget {{CURRENT_ITEM}} .absp-sub-title' => 'color: {{VALUE}}',
					],
				]
			);
		}

		if ( 'three' === $style ) {
			$repeater->add_control(
				'list_title_color',
				[
					'label'     => __( 'Color', 'absolute-addons' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-wrapper .absp-list-widget {{CURRENT_ITEM}} .absp-list-wrapper-bg' => 'color: {{VALUE}}',
					],
				]
			);

			$repeater->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'     => 'list_number_icon_bg_color',
					'label'    => __( 'Background', 'absolute-addons' ),
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .absp-wrapper .absp-list-widget {{CURRENT_ITEM}} .absp-list-wrapper-bg',
				]
			);
		}

		if ( in_array( $style, [ 'four', 'thirteen' ] ) ) {
			$repeater->add_control(
				'list_image',
				[
					'label'   => __( 'Add Image', 'absolute-addons' ),
					'type'    => Controls_Manager::MEDIA,
					'default' => [
						'url' => absp_get_default_placeholder(),
					],
				]
			);
		}

		if ( 'eleven' === $style ) {
			$repeater->add_control(
				'absp_list_border',
				[
					'label'   => __( 'Border Bottom', 'absolute-addons' ),
					'type'    => Controls_Manager::SELECT,
					'options' => [
						'yes'  => __( 'Yes', 'absolute-addons' ),
						'none' => __( 'No', 'absolute-addons' ),
					],
					'default' => 'none',
				]
			);

			$repeater->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'      => 'list_inline_border',
					'label'     => __( 'Border', 'absolute-addons' ),
					'selector'  => '{{WRAPPER}} .absp-wrapper .absp-list-widget {{CURRENT_ITEM}}',
					'condition' => [
						'absp_list_border' => 'yes',
					],
				]
			);

			$repeater->add_responsive_control(
				'list_item_padding',
				[
					'label'      => __( 'Padding Left', 'absolute-addons' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'rem', '%' ],
					'range'      => [
						'px'  => [
							'min'  => 0,
							'max'  => 200,
							'step' => 1,
						],
						'rem' => [
							'min'  => 0,
							'max'  => 20,
							'step' => 0.1,
						],
						'%'   => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .absp-wrapper .absp-list-widget {{CURRENT_ITEM}}' => 'padding-left: {{SIZE}}{{UNIT}};',
					],
				]
			);
		}

		$this->add_control(
			'list_' . $style,
			[
				'label'       => __( 'Item List', 'absolute-addons' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'list_title' => __( 'Lorem ipsum dolor', 'absolute-addons' ),
					],
					[
						'list_title' => __( 'Consectetuer adipisc', 'absolute-addons' ),
					],
					[
						'list_title' => __( 'Sed diam nonummy', 'absolute-addons' ),
					],
				],
				'title_field' => '{{{ list_title }}}',
			]
		);

		$this->add_control(
			'list_rtl_' . $style,
			[
				'label'        => __( 'RTL', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'yes'          => __( 'Yes', 'absolute-addons' ),
				'no'           => __( 'No', 'absolute-addons' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'absolute_list' => [
						'one',
						'two',
						'three',
						'four',
						'five',
						'ten',
						'eleven',
						'twelve',
						'thirteen',
						'fourteen',
					],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings            = $this->get_settings_for_display();
		$style               = $settings['absolute_list'];
		$this->current_style = $style;

		$this->add_inline_editing_attributes( 'list_number', 'basic' );
		$this->add_render_attribute( 'list_number', 'class', 'list-number' );

		$this->add_inline_editing_attributes( 'list_title', 'basic' );
		$this->add_render_attribute( 'list_title', 'class', 'list-title' );

		$this->add_inline_editing_attributes( 'list_sub_title', 'basic' );
		$this->add_render_attribute( 'list_sub_title', 'class', 'sub-title' );

		$this->add_inline_editing_attributes( 'list_description', 'basic' );
		$this->add_render_attribute( 'list_description', 'class', 'list-content' );

		?>
		<div class="absp-wrapper absp-widget">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-list -->
					<div class="absp-list element-<?php echo esc_attr( $style ) ?>">
						<div class="absp-list-widget absp-list-content-<?php echo esc_attr( $style );
						if ( 'yes' === $settings[ 'list_rtl_' . $style ] ) { ?>
						list-rtl<?php } ?>">
							<?php
							if ( is_array( $settings[ 'list_' . $style ] ) ) { ?>
								<ul class="absp-list-grid-col">
									<?php foreach ( $settings[ 'list_' . $style ] as $index => $item ) {
										$this->render_template( $style, [ 'item' => $item ] );
									} ?>
								</ul>
							<?php } ?>
						</div>
					</div>
					<!-- absp-list -->
				</div>
			</div>
		</div>
		<?php
	}

	protected function list_icon( $settings ) {
		if ( in_array( $this->current_style, [ 'one', 'two', 'five' ] ) ) {
			Icons_Manager::render_icon( $settings['list_icon'], [ 'aria-hidden' => 'true' ] );
		} else { ?>
			<span class="absp-list-wrapper-bg">
				<?php Icons_Manager::render_icon( $settings['list_icon'], [ 'aria-hidden' => 'true' ] ); ?>
			</span>
		<?php }
	}

	protected function list_title( $items ) {
		if ( in_array( $this->current_style, [ 'one', 'two', 'three' ] ) ) {
			?>
			<span class="absp-list-title"><?php absp_render_title( $items['list_title'] ); ?></span>
			<?php
		} elseif ( in_array( $this->current_style, [ 'eleven', 'twelve' ] ) ) { ?>
			<div class="absp-list-title">
				<?php $this->list_icon( $items ); ?>
				<h3 class="list-title"><?php absp_render_title( $items['list_title'] ); ?></h3>
			</div>
		<?php } else { ?>
			<div class="absp-list-title">
				<h3 class="list-title"><?php absp_render_title( $items['list_title'] ); ?></h3>
			</div>
		<?php }
	}

	protected function list_number( $settings ) {
		$number_class = ( 'three' === $this->current_style ) ? 'absp-list-wrapper-bg' : '';
		?>
		<span class="absp-list-number <?php echo esc_attr( $number_class ); ?>"><?php echo esc_html( $settings['list_number'] ); ?></span>
		<?php
	}

	protected function list_image( $settings ) {
		$image = wp_get_attachment_image_url( $settings['image']['id'], 'thumbnail', false );
		if ( ! $image ) {
			$image = $settings['image']['url'];
		}
		?>
		<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $settings['list_title'] ); ?>"/>
		<?php
	}
}
