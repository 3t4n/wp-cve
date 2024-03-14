<?php
namespace PWRMagicButtons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *
 * @since 1.0.0
 */
class PWR_Magic_Buttons_Widget extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'magic_button';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Magic Button', 'elpug' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-button';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'elpug-elements' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'elpug' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( ' Magic Button Settings', 'elpug' ),
			]
		);

		$this->add_control(
		  'text',
		  [
		     'label'   => __( 'Text', 'elpug' ),
		     'type'    => Controls_Manager::TEXT,
		     'default'     => __( 'Button Text', 'elpug' ),
     		 //'placeholder' => __( 'Type your title text here', 'elpug' ),
		  ]
		);	
		
		$this->add_control(
			'link',
			[
			   'label'   => __( 'Link', 'elpug' ),
			   'type'    => Controls_Manager::URL,
			   'default' => [
					'url' => '#',
					'is_external' => false,
					'nofollow' => false,
				],
				'placeholder' => __( 'https://your-link.com', 'elpug' ),
			]
		);

		$this->add_control(
			'text_align',
			[
				'label' => __( 'Alignment', 'elpug' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,				
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elpug' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elpug' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elpug' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
			]
		);

		//Pending
		$this->add_control(
			'size',
			[
				'label' => __( 'Size', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'md',
				'options' => [
					'sm'  => __( 'Small', 'elpug' ),
					'md' => __( 'Medium', 'elpug' ),
					'lg' => __( 'Large', 'elpug' ),				  
				],
				'style_transfer' => true,
				'description' => __("It's also possible to customize the size using the Styles tab.", 'elpug'),
			]
		);

		$this->add_control(
			'style',
			[
			   'label'       => __( 'Button Style', 'elpug' ),
			   'type' => Controls_Manager::SELECT,
			   'default' => 'winona',
			   'options' => [
				   'winona'  => __( 'Winona', 'elpug' ),
				   'ujarak' => __( 'Ujarak', 'elpug' ),
				   'wayra' => __( 'Wayra', 'elpug' ),
				   //'tamaya'  => __( 'Tamaya', 'elpug' ),
				   'rayen' => __( 'Rayen', 'elpug' ),
				   //'pipaluk' => __( 'Pipaluk', 'elpug' ),
				  'nuka'  => __( 'Nuka', 'elpug' ),
				   'moema' => __( 'Moema', 'elpug' ),
				   'isi' => __( 'Isi', 'elpug' ),
				  'aylen'  => __( 'Aylen', 'elpug' ),
				   'saqui' => __( 'Saqui', 'elpug' ),
				   'wapasha' => __( 'Wapasha', 'elpug' ),
				  'nina' => __( 'Nina', 'elpug' ),
				   'nanuk' => __( 'Nanuk', 'elpug' ),
				  'antiman'  => __( 'Antiman', 'elpug' ),
				   'itzel' => __( 'Itzel', 'elpug' ),
				   'naira' => __( 'Naira', 'elpug' ),
				  //'quidel' => __( 'Quidel', 'elpug' ),
				   //'sacnite'  => __( 'Sacnite', 'elpug' ),
				   'shikoba' => __( 'Shikoba', 'elpug' )
			   ],
			]
		  );
		
		$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'elpug' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'solid',
				],
				'description' => __("Soem style does not support icons.", 'elpug'),				
			]
		);

		//Pending
		$this->add_control(
			'icon_align',
			[
				'label' => __( 'Icon Position', 'elpug' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'before',				
				'options' => [
					'before' => __( 'Before', 'elpug' ),
					'after' => __( 'After', 'elpug' ),
				]
			]
		);

		

		$this->end_controls_section();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Button', 'elpug' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'label' => __( 'Typography', 'elpug' ),
				//'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .magic-button *',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'label' => __( 'Text Shadow', 'elpug' ),
				'selector' => '{{WRAPPER}} .magic-button',
			]
		);		

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color 1', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f4f4f4',
				'selectors' => [
					'{{WRAPPER}} .magic-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_text_color2',
			[
				'label' => __( 'Text Color 2', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f4f4f4',
				'selectors' => [
					'{{WRAPPER}} .magic-button::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .magic-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => __( 'Background Color 1', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E7416A',
				'selectors' => [
					'{{WRAPPER}} .magic-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color2',
			[
				'label' => __( 'Background Color 2', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_2,
				],
				/*'global' => [
					'default' => \Elementor\Scheme_Color::COLOR_1,
				],*/
				'selectors' => [
					'{{WRAPPER}} .magic-button::before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .magic-button::after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .magic-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_color2',
			[
				'label' => __( 'Color 2', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_2,
				],
				/*'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],*/
				'selectors' => [
					//'{{WRAPPER}} .magic-button' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .magic-button::before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .magic-button::after' => 'border-color: {{VALUE}};',
				],
			]
		);

		

		$this->add_control(
			'border_size',
			[
				'label' => __( 'Border Size', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				//'default' => '0',
				'selectors' => [
					'{{WRAPPER}} .magic-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				
				],
			]
		);



		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .magic-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					//'{{WRAPPER}} .magic-button::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					//'{{WRAPPER}} .magic-button::after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .magic-button',
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => __( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .magic-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();

		$style = $this->get_settings( 'style' );
		$text = $this->get_settings( 'text' );
		$link = $this->get_settings( 'link' );
		$icon = $this->get_settings( 'icon' );
		$align = $this->get_settings( 'text_align' );
		$size = $this->get_settings( 'size' );
		$icon_align = $this->get_settings( 'icon_align' );
		

		//Link
		$linkurl = $link['url'];
		$target = $link['is_external'];
		$nofollow = $link['nofollow'];

		//Icon
		$iconvalue = $icon['value'];
		//$iconlibrary  = $icon['library '];

		//var_dump($iconvalue);
		//var_dump($iconlibrary);

		//Ugly workaround to use icon inside shortcode
		echo '<span style="display: none;">';
		\Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
		echo '</span>';

		echo '<div class="magic-button-wrapper" style="text-align: '.$align.'">';		
			echo do_shortcode('[magic-button link="'.$linkurl.'" target="'.$target.'" nofollow="'.$nofollow.'" align="'.$align.'" style="'.$style.'" text="'.$text.'" icon="'.$iconvalue.'" icon_position="'.$icon_align.'" size="'.$size.'"]');
		echo '</div>';
		
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	/*protected function _content_template() {
		$sliderheight = $settings['slider_height'];
		?>
		
		<div class="pando-slideshow">
			<?php echo do_shortcode('[pando-slider heightstyle="'.$sliderheight.'"]'); ?>
		</div>


		<?php
	}*/
}