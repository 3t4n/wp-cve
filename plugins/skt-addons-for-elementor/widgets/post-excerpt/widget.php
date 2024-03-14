<?php
/**
 * Post Excerpt widget class
 *
 * @package Skt_Addons
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || die();

class Post_Excerpt extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Post Excerpt', 'skt-addons-elementor' );
	}

	public function get_custom_help_url() {
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'skti skti-tb-post-excerpt';
	}

	public function get_keywords() {
		return [ 'excerpt', 'text' ];
	}

	/**
     * Register widget excerpt controls
     */
	protected function register_content_controls() {
		$this->__post_excerpt_controls();
	}

	protected function __post_excerpt_controls() {
		$this->start_controls_section(
			'_section_post_excerpt',
			[
				'label' => __( 'Post Excerpt', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justify', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};'
				]
			]
		);

        $this->end_controls_section();
	}

	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {
		$this->__excerpt_style_controls();
	}


	protected function __excerpt_style_controls() {

        $this->start_controls_section(
            '_section_style_excerpt',
            [
                'label' => __( 'Excerpt Style', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'excerpt_color',
			[
				'label' => esc_html__( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} p',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

        $this->end_controls_section();
	}

	protected function render() {
        $post = get_post();

		$settings = $this->get_settings_for_display();

		if ( post_password_required( $post->ID ) ) {
			echo get_the_password_form( $post->ID );
			return;
		}
		if (skt_addons_elementor()->editor->is_edit_mode() || is_preview()) {
			echo "<p><strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>";
		}
		else {
			echo apply_filters( 'the_excerpt', get_the_excerpt() );
		}
	}
}
