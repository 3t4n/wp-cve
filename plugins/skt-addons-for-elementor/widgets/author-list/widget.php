<?php
/**
 * Autor List widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Icons_Manager;

defined( 'ABSPATH' ) || die();

class Author_List extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Author List', 'skt-addons-elementor' );
    }

    /**
     * Get widget icon.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'skti skti-user-male';
    }

    public function get_keywords() {
        return [ 'author', 'list', 'post' ];
    }

	/**
     * Register widget content controls
     */
    protected function register_content_controls() {

		$this->start_controls_section(
            '_author_list',
            [
                'label' => __( 'Author List', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
			'post_type',
			[
				'label' => __( 'Post Type', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_post_type_list(),
				'default' => key( $this->get_post_type_list() ),
			]
        );

        $this->add_control(
			'author_name',
			[
				'label' => __( 'Name', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
                    'display_name' => __( 'Display Name', 'skt-addons-elementor' ),
                    'first_name' => __( 'First Name', 'skt-addons-elementor' ),
                    'last_name' => __( 'Last Name', 'skt-addons-elementor' ),
                    'nickname' => __( 'Nick Name', 'skt-addons-elementor' ),
                    'user_nicename' => __( 'User Nice Name', 'skt-addons-elementor' )
                ],
				'default' => 'display_name',
			]
        );

        $this->add_control(
			'author_avatar',
			[
				'label' => __( 'Avatar', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
        );

        $this->add_control(
			'author_avatar_size',
			[
				'label' => __( 'Avatar Size', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'author_avatar' => 'yes'
                ],
				'options' => [
                    '25' => __( '25 x 25', 'skt-addons-elementor' ),
                    '35' => __( '35 x 35', 'skt-addons-elementor' ),
                    '45' => __( '45 x 45', 'skt-addons-elementor' ),
                    '60' => __( '60 x 60', 'skt-addons-elementor' ),
                    '80' => __( '80 x 80', 'skt-addons-elementor' ),
                    '150' => __( '150 x 150', 'skt-addons-elementor' )
                ],
				'default' => '45',
			]
        );

        $this->add_control(
			'author_icon',
			[
				'label' => __( 'Icon', 'skt-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => true,
				'default' => [
					'value' => 'skti skti-avatar-man',
					'library' => 'skt-icons'
				],
				'condition' => [
					'author_avatar!' => 'yes'
				]
			]
        );

        $this->add_control(
			'author_post_count',
			[
				'label' => __( 'Post Count', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
        );

        $this->add_control(
            'author_post_count_text',
            [
                'label' => __( 'Post Count Text', 'skt-addons-elementor' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => false,
                'default' => __( 'Post Count ', 'skt-addons-elementor' ),
                'placeholder' => __( 'Post Count Text', 'skt-addons-elementor' ),
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'author_post_count' => 'yes'
                ]
            ]
        );

        $this->add_control(
			'author_email',
			[
				'label' => __( 'Email', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => '',
			]
        );

        $this->add_control(
			'author_description',
			[
				'label' => __( 'Author Bio', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => '',
			]
        );

        $this->add_control(
			'author_archive_link_name',
			[
				'label' => __( 'Archive Link in Name', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => '',
			]
        );

        $this->add_control(
			'author_website_link_image',
			[
				'label' => __( 'Website Link in Image/Icon', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => '',
			]
        );

        $this->add_responsive_control(
            'list_position',
            [
                'label' => __( 'List Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'separator' => 'before',
                'options' => [
                    'inline' => [
                        'title' => __( 'Inline', 'skt-addons-elementor' ),
                        'icon' => 'eicon-ellipsis-h',
                    ],
                    'block' => [
                        'title' => __( 'Block', 'skt-addons-elementor' ),
                        'icon' => 'eicon-ellipsis-v',
                    ],
                ],
                'prefix_class' => 'skt-list-',
                'default' => 'block',
                'toggle' => false,
                'selectors_dictionary' => [
                    'inline' => 'flex-direction: row',
                    'block' => 'flex-direction: column',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-autor-list-wrapper' => '{{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'avatar_position',
            [
                'label' => __( 'Avatar Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'inline' => [
                        'title' => __( 'Inline', 'skt-addons-elementor' ),
                        'icon' => 'eicon-ellipsis-h',
                    ],
                    'block' => [
                        'title' => __( 'Block', 'skt-addons-elementor' ),
                        'icon' => 'eicon-ellipsis-v',
                    ],
                ],
                'toggle' => false,
                'prefix_class' => 'skt-avatar-',
                'default' => 'inline',
                'selectors_dictionary' => [
                    'inline' => 'flex-direction: row',
                    'block' => 'flex-direction: column',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-author-list-head' => '{{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'post_count_position',
            [
                'label' => __( 'Post Count Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'inline' => [
                        'title' => __( 'Inline', 'skt-addons-elementor' ),
                        'icon' => 'eicon-ellipsis-h',
                    ],
                    'block' => [
                        'title' => __( 'Block', 'skt-addons-elementor' ),
                        'icon' => 'eicon-ellipsis-v',
                    ],
                ],
                'toggle' => false,
                'prefix_class' => 'skt-post-count-',
                'default' => 'block',
                'condition' => [
                    'author_post_count' => 'yes'
                ],
                'selectors_dictionary' => [
                    'inline' => 'flex-direction: row',
                    'block' => 'flex-direction: column',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-author-list-name' => '{{VALUE}};',
                ],
            ]
        );

        $this->add_control(
			'list_alignment',
			[
				'label' => __( 'Alignment', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
					]
				],
                'toggle' => false,
                'prefix_class' => 'skt-alignment-',
				'selectors' => [
					'{{WRAPPER}} .skt-text'  => 'text-align: {{VALUE}};'
				],
			]
		);

        $this->end_controls_section();
    }

	/**
     * Register widget style controls
     */
    protected function register_style_controls() {
		$this->__common_style_controls();
		$this->__author_style_controls();
	}

    protected function __common_style_controls() {

        $this->start_controls_section(
            '_section_common_style',
            [
                'label' => __( 'Common', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
			'list_spacing',
			[
				'label' => __( 'Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
                    '{{WRAPPER}}.skt-list-inline .skt-author-list:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.skt-list-block .skt-author-list:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};'
				],
			]
        );

        $this->add_responsive_control(
            'list_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-author-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
			'list_boder_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-author-list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'list_boder',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-author-list'
			]
        );

        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'list_box_shadow',
				'label' => __( 'Box Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-author-list',
			]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'list_background',
                'types' => [ 'classic', 'gradient' ],
                'exclude' => [ 'image' ],
                'selector' => '{{WRAPPER}} .skt-author-list',
            ]
        );

        $this->end_controls_section();
	}

    protected function __author_style_controls() {

        $this->start_controls_section(
            'author_style',
            [
                'label' => __( 'Author', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            '_heading_avater',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Avatar', 'skt-addons-elementor' ),
            ]
        );

        $this->add_responsive_control(
            'avatar_spacing',
            [
                'label' => __( 'Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-author-list-avater' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'avatar_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'condition' => [
                    'author_avatar' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-author-list-avater img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'avatar_icon_size',
            [
                'label' => __( 'Icon Size', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'condition' => [
                    'author_avatar!' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-author-list-avater i' => 'font-size: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'avatar_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'condition' => [
                    'author_avatar' => 'yes'
                ],
                'selector' => '{{WRAPPER}} .skt-author-list-avater img, {{WRAPPER}} .skt-author-list-avater i'
            ]
        );

        $this->add_control(
            'avatar_icon_color',
            [
                'label' => __( 'Icon Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'author_avatar!' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-author-list-avater i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'avatar_icon_hover_color',
            [
                'label' => __( 'Icon Hover Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'author_avatar!' => 'yes',
                    'author_website_link_image' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-author-list-avater i:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_heading_name',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Name', 'skt-addons-elementor' ),
                'separator' => 'before'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'label' => __( 'Typography', 'skt-addons-elementor' ),
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
                'exclude' => [
                    'line_height'
                ],
                'selector' => '{{WRAPPER}} .skt-author-list-name-text, {{WRAPPER}} .skt-author-list-name-text a',
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-author-list-name-text' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .skt-author-list-name-text a' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'name_hover_color',
            [
                'label' => __( 'Hover Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'author_archive_link_name' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-author-list-name-text a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_heading_post_count',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Post Count', 'skt-addons-elementor' ),
                'separator' => 'before'
            ]
        );

        $this->add_control(
			'note',
			[
				'show_label' => false,
				'type' => Controls_Manager::RAW_HTML,
				'condition' => [
					'author_post_count!' => 'yes',
				],
				'raw' => __( '<strong>Post Count</strong> is Switched off from "Author List" content section', 'skt-addons-elementor' ),
			]
		);

        $this->add_responsive_control(
            'post_count_spacing',
            [
                'label' => __( 'Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'condition' => [
                    'author_post_count' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-author-list-post-count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'post_count_typography',
                'label' => __( 'Typography', 'skt-addons-elementor' ),
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
                'exclude' => [
                    'line_height'
                ],
                'condition' => [
                    'author_post_count' => 'yes'
                ],
                'selector' => '{{WRAPPER}} .skt-author-list-post-count',
            ]
        );

        $this->add_control(
            'post_count_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'author_post_count' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-author-list-post-count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_heading_email',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Email', 'skt-addons-elementor' ),
                'separator' => 'before'
            ]
        );

        $this->add_control(
			'note_email',
			[
				'show_label' => false,
				'type' => Controls_Manager::RAW_HTML,
				'condition' => [
					'author_email!' => 'yes',
				],
				'raw' => __( '<strong>Email</strong> is Switched off from "Author List" content section', 'skt-addons-elementor' ),
			]
		);

        $this->add_responsive_control(
            'email_spacing',
            [
                'label' => __( 'Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'condition' => [
                    'author_email' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-author-list-email' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'email_typography',
                'label' => __( 'Typography', 'skt-addons-elementor' ),
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
                'exclude' => [
                    'line_height'
                ],
                'condition' => [
                    'author_email' => 'yes'
                ],
                'selector' => '{{WRAPPER}} .skt-author-list-email',
            ]
        );

        $this->add_control(
            'email_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'author_email' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-author-list-email' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            '_heading_description',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Author Bio', 'skt-addons-elementor' ),
                'separator' => 'before'
            ]
        );

        $this->add_control(
			'note_description',
			[
				'show_label' => false,
				'type' => Controls_Manager::RAW_HTML,
				'condition' => [
					'author_description!' => 'yes',
				],
				'raw' => __( '<strong>Author Bio</strong> is Switched off from "Author List" content section', 'skt-addons-elementor' ),
			]
		);

        $this->add_responsive_control(
            'description_spacing',
            [
                'label' => __( 'Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'condition' => [
                    'author_description' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-author-list-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => __( 'Typography', 'skt-addons-elementor' ),
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
                'exclude' => [
                    'line_height'
                ],
                'condition' => [
                    'author_description' => 'yes'
                ],
                'selector' => '{{WRAPPER}} .skt-author-list-description',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'author_description' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-author-list-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $author_ids = [];
        $users = get_users();

        foreach ( $users as $user ) {
            $user_post_count = count_user_posts( $user->ID, $settings['post_type'], true );
            if ( $user_post_count > 0 ) {
                $author_ids[] = ['autor_id' => $user->ID, 'post_count' => $user_post_count ];
            }
        }

        if ( empty( $author_ids ) ) {
            printf( '<div class="skt-author-list-error"><strong>%s</strong> %s</div>', $settings['post_type'], __( ' post type don\'t have any post.', 'skt-addons-elementor' ) );
            return;
        }

        // print_r( $author_ids );
        ?>

        <div class="skt-autor-list-wrapper">
            <?php foreach ( $author_ids as $author_id ) : ?>
                <div class="skt-author-list">
                    <div class="skt-author-list-head">
                        <?php if ( $settings['author_avatar'] == 'yes' ) : ?>
                            <div class="skt-author-list-avater">
                                <?php
                                if ( $settings['author_website_link_image'] == 'yes' ) {
                                    printf( '<a href="%s">%s</a>',
                                        esc_url( get_the_author_meta( 'user_url', $author_id['autor_id'] ) ),
                                        get_avatar( $author_id['autor_id'], $settings['author_avatar_size'] )
                                    );
                                } else {
                                    echo wp_kses_post(get_avatar( $author_id['autor_id'], $settings['author_avatar_size'] ));
                                }
                                ?>
                            </div>
                        <?php elseif ( $settings['author_icon']['value'] ) : ?>
                            <div class="skt-author-list-avater">
                                <?php
                                if ( $settings['author_website_link_image'] == 'yes' ) { ?>
                                    <a href="<?php echo esc_url( get_the_author_meta( 'user_url', $author_id['autor_id'] ) ) ?>">
                                        <?php Icons_Manager::render_icon( $settings['author_icon'], [ 'aria-hidden' => 'true' ] ) ?>
                                    </a>
                                <?php
                                } else {
                                    Icons_Manager::render_icon( $settings['author_icon'], [ 'aria-hidden' => 'true' ] );
                                }
                                ?>
                            </div>
                        <?php endif; ?>

                        <div class="skt-author-list-meta">
                            <div class="skt-author-list-name">
                               <div class="skt-author-list-name-text">
                                    <?php
                                    if ( $settings['author_archive_link_name'] == 'yes' ) {
                                        printf( '<a href="%s">%s</a>',
                                            esc_url( get_author_posts_url( $author_id['autor_id'] ) ),
                                            esc_html( get_the_author_meta( $settings['author_name'], $author_id['autor_id'] ) )
                                        );
                                    } else {
                                        echo esc_html( get_the_author_meta( $settings['author_name'], $author_id['autor_id'] ) );
                                    }
                                    ?>
                                </div>

                                <?php if ( $settings['author_post_count'] == 'yes' ) : ?>
                                    <div class="skt-author-list-post-count">
                                        <?php
                                        echo ! empty( $settings['author_post_count_text'] ) ? esc_html( $settings['author_post_count_text'] ) : '';
                                        echo esc_html( $author_id['post_count'], 'skt-addons-elementor' );
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if ( $settings['author_email'] == 'yes' ) : ?>
                                <div class="skt-author-list-email"><?php echo esc_html( get_the_author_meta( 'user_email', $author_id['autor_id'] ) ); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ( $settings['author_description'] == 'yes' ) : ?>
                        <div class="skt-author-list-description"><?php echo esc_html( get_the_author_meta( 'description', $author_id['autor_id'] ) ); ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

    <?php
    }

    protected function get_post_type_list() {
        $args = [
            'public'   => true,
            'show_in_nav_menus' => true
        ];
        $post_types = get_post_types( $args, 'objects' );
        $post_types = wp_list_pluck( $post_types, 'label', 'name' );
        return $post_types;
    }
}