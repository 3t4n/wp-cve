<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Animated Widget.
 *
 * Elementor widget that inserts an animated headline content into the page, from any given text.
 *
 * @since 1.0.0
 */
class Animated_Headline_Elementor_Widget extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve Animated headline widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_name() {
		return 'animated-headline-widget';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Animated headline widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_title() {
		return esc_html__( 'Animated Headline', 'animated-headline-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Animated headline widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_icon() {
		return 'eicon-animated-headline';
	}

	/**
	 * Get custom help URL.
	 *
	 * Retrieve a URL where the user can get more information about the widget.
	 *
	 * @return string Widget help URL.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_custom_help_url() {
		return 'https://developers.elementor.com/docs/widgets/';
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_script_depends() {
		return [ 'animated-headline-elementor' ];
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the Animated headline widget belongs to.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_categories() {
		return [ 'animated_headline-category' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the Animated headline widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_keywords() {
		return [ 'elementor','animated', 'headline', 'rotate', 'type', 'zoom', 'push', 'slide', 'scale' ];
	}

	/**
	 * Register Animated headline widget controls.
	 *
	 * Add input fields to allow the user to customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'animated-headline-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'animated_headline_animation_type',
			[
				'label'   => esc_html__( 'Animation Type', 'animated-headline-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'clip',
				'options' => [
					'rotate1'     => esc_html__( 'Rotate 1', 'animated-headline-elementor' ),
					'type'        => esc_html__( 'Type', 'animated-headline-elementor' ),
					'rotate2'     => esc_html__( 'Rotate 2', 'animated-headline-elementor' ),
					'loading_bar' => esc_html__( 'Loading Bar', 'animated-headline-elementor' ),
					'slide'       => esc_html__( 'Slide', 'animated-headline-elementor' ),
					'clip'        => esc_html__( 'Clip', 'animated-headline-elementor' ),
					'zoom'        => esc_html__( 'Zoom', 'animated-headline-elementor' ),
					'rotate3'     => esc_html__( 'Rotate 3', 'animated-headline-elementor' ),
					'scale'       => esc_html__( 'Scale', 'animated-headline-elementor' ),
					'push'        => esc_html__( 'Push', 'animated-headline-elementor' ),
				]
			]
		);

		$this->add_control(
			'animated_headline_before_title',
			[
				'label'       => esc_html__( 'Before Text', 'animated-headline-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Before Text', 'animated-headline-elementor' ),
				'placeholder' => esc_html__( 'Before Text', 'animated-headline-elementor' ),
			]
		);

		$this->add_control(
			'animated_headline_after_title',
			[
				'label'       => esc_html__( 'After Text', 'animated-headline-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'After Text', 'animated-headline-elementor' ),
			]
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'animated_headline_title',
			[
				'label'   => esc_html__( 'Title', 'animated-headline-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Designer', 'animated-headline-elementor' ),
			]
		);


		$this->add_control(
			'animated_headline_list',
			[
				'label'  => esc_html__( 'Animation Text List', 'animated-headline-elementor' ),
				'type'   => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),

				'default'     => [

					[
						'animated_headline_title' => esc_html__( 'Designer', 'animated-headline-elementor' ),
					],
					[
						'animated_headline_title' => esc_html__( 'Developer', 'animated-headline-elementor' ),
					],
					[
						'animated_headline_title' => esc_html__( 'UI/UX', 'animated-headline-elementor' ),
					]

				],
				'title_field' => '{{{ animated_headline_title }}}',
			]
		);

		$this->end_controls_section();
		// Global Style Tab
		$this->start_controls_section(
			'global_style_section',
			[
				'label' => esc_html__( 'Style', 'animated-headline-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'global_margin',
			[
				'label'      => __( 'Margin', 'animated-headline-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .cd-intro' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);
		$this->add_responsive_control(
			'global_padding',
			[
				'label'      => __( 'Padding', 'animated-headline-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .cd-intro' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->end_controls_section();
		// Before Style Tab
		$this->start_controls_section(
			'before_style_section',
			[
				'label' => esc_html__( 'Before Style', 'animated-headline-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'before_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'animated-headline-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .before_title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'before_content_typography',
				'selector' => '{{WRAPPER}} .before_title',
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'before_text_shadow',
				'label'    => esc_html__( 'Text Shadow', 'animated-headline-elementor' ),
				'selector' => '{{WRAPPER}} .before_title',
			]
		);

		$this->end_controls_section();
		// Animated Style Tab
		$this->start_controls_section(
			'animated_style_section',
			[
				'label' => esc_html__( 'Animated Style', 'animated-headline-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'animated_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'animated-headline-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .animated_style' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'animated_content_typography',
				'selector' => '{{WRAPPER}} .animated_style',
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'animated_text_shadow',
				'label'    => esc_html__( 'Text Shadow', 'animated-headline-elementor' ),
				'selector' => '{{WRAPPER}} .animated_style',
			]
		);

		$this->end_controls_section();
		//After Style Tab
		$this->start_controls_section(
			'after_style_section',
			[
				'label' => esc_html__( 'After Style', 'animated-headline-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'after_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'animated-headline-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .after_title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'after_content_typography',
				'selector' => '{{WRAPPER}} .after_title',
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'after_text_shadow',
				'label'    => esc_html__( 'Text Shadow', 'animated-headline-elementor' ),
				'selector' => '{{WRAPPER}} .after_title',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render Animated headline widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings     = $this->get_settings_for_display();
		$before_title = $settings['animated_headline_before_title'];
		$after_title  = $settings['animated_headline_after_title'];
		if ( $settings['animated_headline_animation_type'] == 'rotate1' ) {
			?>
            <section class="cd-intro">
                <h1 class="cd-headline rotate-1">
                    <span class="before_title"><?php echo esc_attr( $before_title ); ?></span>
                    <span class="cd-words-wrapper">
                <?php
                $i = "";
                foreach ( $settings['animated_headline_list'] as $animated_headline_list ):
	                $class = $i == 1 ? 'visible' : 'hidden';
	                $i ++;
	                $clip_title = $animated_headline_list['animated_headline_title'];
	                ?>
                    <b class="animated_style is-<?php echo esc_attr( $class ); ?>"><?php echo esc_attr( $clip_title ); ?></b>
                <?php endforeach; ?>
			</span>
                    <span class="after_title"><?php echo esc_attr( $after_title ); ?></span>
                </h1>
            </section>
			<?php
		} else if ( $settings['animated_headline_animation_type'] == 'type' ) {
			?>
            <section class="cd-intro">
                <h1 class="cd-headline letters type">
                    <span class="before_title"><?php echo esc_attr( $before_title ); ?></span>
                    <span class="cd-words-wrapper">
                <?php
                $i = "";
                foreach ( $settings['animated_headline_list'] as $animated_headline_list ):
	                $class = $i == 1 ? 'visible' : 'hidden';
	                $i ++;
	                $clip_title = $animated_headline_list['animated_headline_title'];
	                ?>
                    <b class="animated_style is-<?php echo esc_attr( $class ); ?>"><?php echo esc_attr( $clip_title ); ?></b>
                <?php endforeach; ?>
			</span>
                    <span class="after_title"><?php echo esc_attr( $after_title ); ?></span>
                </h1>
            </section>
			<?php
		} else if ( $settings['animated_headline_animation_type'] == 'rotate2' ) {
			?>
            <section class="cd-intro">
                <h1 class="cd-headline letters rotate-2">
                    <span class="before_title"><?php echo esc_attr( $before_title ); ?></span>
                    <span class="cd-words-wrapper">
                <?php
                $i = "";
                foreach ( $settings['animated_headline_list'] as $animated_headline_list ):
	                $class = $i == 1 ? 'visible' : 'hidden';
	                $i ++;
	                $clip_title = $animated_headline_list['animated_headline_title'];
	                ?>
                    <b class="animated_style is-<?php echo esc_attr( $class ); ?>"><?php echo esc_attr( $clip_title ); ?></b>
                <?php endforeach; ?>
			</span>
                    <span class="after_title"><?php echo esc_attr( $after_title ); ?></span>
                </h1>
            </section>
			<?php
		} else if ( $settings['animated_headline_animation_type'] == 'loading_bar' ) {
			?>
            <section class="cd-intro">
                <h1 class="cd-headline loading-bar">
                    <span class="before_title"><?php echo esc_attr( $before_title ); ?></span>
                    <span class="cd-words-wrapper">
                <?php
                $i = "";
                foreach ( $settings['animated_headline_list'] as $animated_headline_list ):
	                $class = $i == 1 ? 'visible' : 'hidden';
	                $i ++;
	                $clip_title = $animated_headline_list['animated_headline_title'];
	                ?>
                    <b class="animated_style is-<?php echo esc_attr( $class ); ?>"><?php echo esc_attr( $clip_title ); ?></b>
                <?php endforeach; ?>
			</span>
                    <span class="after_title"><?php echo esc_attr( $after_title ); ?></span>
                </h1>
            </section>
			<?php
		} else if ( $settings['animated_headline_animation_type'] == 'slide' ) {
			?>
            <section class="cd-intro">
                <h1 class="cd-headline slide">
                    <span class="before_title"><?php echo esc_attr( $before_title ); ?></span>
                    <span class="cd-words-wrapper">
                <?php
                $i = "";
                foreach ( $settings['animated_headline_list'] as $animated_headline_list ):
	                $class = $i == 1 ? 'visible' : 'hidden';
	                $i ++;
	                $clip_title = $animated_headline_list['animated_headline_title'];
	                ?>
                    <b class="animated_style is-<?php echo esc_attr( $class ); ?>"><?php echo esc_attr( $clip_title ); ?></b>
                <?php endforeach; ?>
			</span>
                    <span class="after_title"><?php echo esc_attr( $after_title ); ?></span>
                </h1>
            </section>
			<?php
		} else if ( $settings['animated_headline_animation_type'] == 'clip' ) {
			?>
            <section class="cd-intro">
                <h1 class="cd-headline clip is-full-width">
                    <span class="before_title"><?php echo esc_attr( $before_title ); ?></span>
                    <span class="cd-words-wrapper">
                <?php
                $i = "";
                foreach ( $settings['animated_headline_list'] as $animated_headline_list ):
	                $class = $i == 1 ? 'visible' : 'hidden';
	                $i ++;
	                $clip_title = $animated_headline_list['animated_headline_title'];
	                ?>
                    <b class="animated_style is-<?php echo esc_attr( $class ); ?>"><?php echo esc_attr( $clip_title ); ?></b>
                <?php endforeach; ?>
			</span>
                    <span class="after_title"><?php echo esc_attr( $after_title ); ?></span>
                </h1>
            </section>
			<?php
		} else if ( $settings['animated_headline_animation_type'] == 'zoom' ) {
			?>
            <section class="cd-intro">
                <h1 class="cd-headline zoom">
                    <span class="before_title"><?php echo esc_attr( $before_title ); ?></span>
                    <span class="cd-words-wrapper">
                <?php
                $i = "";
                foreach ( $settings['animated_headline_list'] as $animated_headline_list ):
	                $class = $i == 1 ? 'visible' : 'hidden';
	                $i ++;
	                $clip_title = $animated_headline_list['animated_headline_title'];
	                ?>
                    <b class="animated_style is-<?php echo esc_attr( $class ); ?>"><?php echo esc_attr( $clip_title ); ?></b>
                <?php endforeach; ?>
			</span>
                    <span class="after_title"><?php echo esc_attr( $after_title ); ?></span>
                </h1>
            </section>
			<?php
		} else if ( $settings['animated_headline_animation_type'] == 'rotate3' ) {
			?>
            <section class="cd-intro">
                <h1 class="cd-headline letters rotate-3">
                    <span class="before_title"><?php echo esc_attr( $before_title ); ?></span>
                    <span class="cd-words-wrapper">
                <?php
                $i = "";
                foreach ( $settings['animated_headline_list'] as $animated_headline_list ):
	                $class = $i == 1 ? 'visible' : 'hidden';
	                $i ++;
	                $clip_title = $animated_headline_list['animated_headline_title'];
	                ?>
                    <b class="animated_style is-<?php echo esc_attr( $class ); ?>"><?php echo esc_attr( $clip_title ); ?></b>
                <?php endforeach; ?>
			</span>
                    <span class="after_title"><?php echo esc_attr( $after_title ); ?></span>
                </h1>
            </section>
			<?php
		} else if ( $settings['animated_headline_animation_type'] == 'scale' ) {
			?>
            <section class="cd-intro">
                <h1 class="cd-headline letters scale">
                    <span class="before_title"><?php echo esc_attr( $before_title ); ?></span>
                    <span class="cd-words-wrapper">
                <?php
                $i = "";
                foreach ( $settings['animated_headline_list'] as $animated_headline_list ):
	                $class = $i == 1 ? 'visible' : 'hidden';
	                $i ++;
	                $clip_title = $animated_headline_list['animated_headline_title'];
	                ?>
                    <b class="animated_style is-<?php echo esc_attr( $class ); ?>"><?php echo esc_attr( $clip_title ); ?></b>
                <?php endforeach; ?>
			</span>
                    <span class="after_title"><?php echo esc_attr( $after_title ); ?></span>
                </h1>
            </section>
			<?php
		} else {
			?>
            <section class="cd-intro">
                <h1 class="cd-headline push">
                    <span class="before_title"><?php echo esc_attr( $before_title ); ?></span>
                    <span class="cd-words-wrapper">
                <?php
                $i = "";
                foreach ( $settings['animated_headline_list'] as $animated_headline_list ):
	                $class = $i == 1 ? 'visible' : 'hidden';
	                $i ++;
	                $clip_title = $animated_headline_list['animated_headline_title'];
	                ?>
                    <b class="animated_style is-<?php echo esc_attr( $class ); ?>"><?php echo esc_attr( $clip_title ); ?></b>
                <?php endforeach; ?>
			</span>
                    <span class="after_title"><?php echo esc_attr( $after_title ); ?></span>
                </h1>
            </section>
			<?php
		}

	}

}