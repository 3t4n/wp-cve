<?php
/**
 * Class: Soft_Template_Post_Content
 * Name: Post Content
 * Slug: soft-template-post-content
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Soft_Template_Post_Content extends SoftTemplate_Base {
    public function get_name() {
		return 'soft-template-post-content';
	}

	public function get_title() {
		return esc_html__( 'Post Content', 'soft-template-core' );
	}

    public function get_icon() {
		return 'eicon-post-content';
	}

    public function get_jet_help_url() {
		return '#';
	}

    public function get_categories() {
		return array( 'soft-template-core' );
	}

    protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'soft-template-core' ),
			]
		);

        $this->add_control(
			'content_type',
			[
				'label' => __( 'Content Type', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dynamic',
				'options' => [
					'dynamic'  => __( 'Dynamic', 'soft-template-core' ),
					'custom' => __( 'Custom', 'soft-template-core' ),
				],
			]
		);

		$this->add_control(
			'contents',
			[
				'type' => Controls_Manager::WYSIWYG,
				'label_block' => true,
                'condition' => array(
					'content_type' => 'custom',
				),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'soft-template-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'soft-template-core' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'soft-template-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'soft-template-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'soft-template-core' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'soft-template-core' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-soft-template-post-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'soft-template-core' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-soft-template-post-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .elementor-soft-template-post-content',
			]
		);

		$this->end_controls_section();
    }

    protected function render() {
        $this->__context = 'render';
        $settings = $this->get_settings_for_display();

        $this->__open_wrap();

        if( $settings['content_type'] == 'custom' ) {
            echo $this->get_settings_for_display( 'contents' );
        } else {
            $content = apply_filters( 'the_content', get_the_content() );
            $current_queried_post_type = get_post_type( get_queried_object_id() );
            if( $current_queried_post_type == 'soft-template-core' ) {
                if( soft_template_core()->elementor_editor_preview() ) {
                    if ( !empty($content) ) {
                        echo sprintf( '%1$s', $content );
                    }
                } else {
                    echo '<p>Lorem ipsum, or lipsum as it is sometimes known, is dummy text used in laying out print, graphic or web designs. The passage is attributed to an unknown typesetter in the 15th century who is thought to have scrambled parts of Cicero\'s De Finibus Bonorum et Malorum for use in a type specimen book. It usually begins with</p>';
    
                    echo'<blockquote>“Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.”</blockquote>';
                    
                    echo '<p>The purpose of lorem ipsum is to create a natural looking block of text (sentence, paragraph, page, etc.) that doesn\'t distract from the layout. A practice not without controversy, laying out pages with meaningless filler text can be very useful when the focus is meant to be on design, not content.</p>';
                        
                    echo '<p>The passage experienced a surge in popularity during the 1960s when Letraset used it on their dry-transfer sheets, and again during the 90s as desktop publishers bundled the text with their software. Today it\'s seen all around the web; on templates, websites, and stock designs. Use our generator to get your own, or read on for the authoritative history of lorem ipsum.</p>';
                }
            } elseif ( !empty($content) ) {
                echo sprintf( '%1$s', $content );
            } else {
                the_content();
            }
        }

        $this->__close_wrap();
    }
}

