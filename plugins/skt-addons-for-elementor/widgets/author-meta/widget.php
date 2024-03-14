<?php
/**
 * Author Meta widget class
 *
 * @package Skt_Addons
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

defined( 'ABSPATH' ) || die();

class Author_Meta extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Author Meta', 'skt-addons-elementor' );
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
		return 'skti skti-tb-author-meta';
	}

	public function get_keywords() {
		return [ 'author', 'author_meta', 'author info' ];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__author_content_controls();

	}

	protected function __author_content_controls() {
		$this->start_controls_section(
			'_section_author_meta',
			[
				'label' => __( 'Author Meta', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_author',
			[
				'label'        => __( 'Show Author Name', 'skt-addons-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);


		$this->add_control(
			'author_meta_tag',
			[
				'label' => __( 'Author Name Tag', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'h1'  => [
						'title' => __( 'H1', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h1'
					],
					'h2'  => [
						'title' => __( 'H2', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h2'
					],
					'h3'  => [
						'title' => __( 'H3', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h3'
					],
					'h4'  => [
						'title' => __( 'H4', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h4'
					],
					'h5'  => [
						'title' => __( 'H5', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h5'
					],
					'h6'  => [
						'title' => __( 'H6', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h6'
					]
				],
				'default' => 'h4',
				'toggle' => false,
				'condition'=>[
					'show_author' => 'yes',
				]
			]
		);

		$this->add_control(
			'show_avatar',
			[
				'label'        => __( 'Show Avatar', 'skt-addons-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'show_bio',
			[
				'label'        => __( 'Show Short Bio', 'skt-addons-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			]
		);
		
		$this->add_control(
			'show_archive_btn',
			[
				'label'        => __( 'Show Archive Button', 'skt-addons-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'author_link_to',
			[
				'label' => __( 'Link', 'skt-addons-elementor' ),
				'type'  => Controls_Manager::SELECT,
				'options' => [
					''              => __( 'None', 'skt-addons-elementor' ),
					'website'       => __( 'Website', 'skt-addons-elementor' ),
					'admin_archive' => __( 'Admin Posts', 'skt-addons-elementor' ),
				],
				'description'       => __( 'Link for the Author Name and Image', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'avatar_size',
			[
				'label' => __( 'Avatar Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 500,
				'step' => 1,
				'default' => 96,
			]
		);

		$this->add_control(
			'avatar_image_position',
			[
				'label'   => __( 'Avatar Image Position', 'skt-addons-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
					'top' => [
						'title' => __( 'Top', 'skt-addons-elementor' ),
						'icon'  => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'skt-addons-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'     => 'left',
			]
		);

        $this->end_controls_section();
	}

	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {
		$this->__author_style_controls();
		$this->__avatar_style_controls();
		$this->__author_short_bio_controls();
		$this->__author_button_style_controls();
	}


	protected function __author_style_controls() {

        $this->start_controls_section(
            '_section_style_text',
            [
                'label' => __( 'Author Name', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'author_color',
			[
				'label' => esc_html__( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-author-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'author_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-author-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'author_text_shadow',
				'selector' => '{{WRAPPER}} .skt-author-title',
			]
		);


        $this->end_controls_section();
	}

	protected function __author_short_bio_controls() {

        $this->start_controls_section(
            '_section_style_short_bio',
            [
                'label' => __( 'Short Bio', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'bio_color',
			[
				'label' => esc_html__( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-desc p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'bio_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-desc p',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);


        $this->end_controls_section();
	}

	protected function __avatar_style_controls() {

        $this->start_controls_section(
            '_section_avatar_style',
            [
                'label' => __( 'Avatar', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'avatar_vertical_lign',
			[
				'label'   => __( 'Vertical Align', 'skt-addons-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => __( 'Top', 'skt-addons-elementor' ),
						'icon'  => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __( 'Middle', 'skt-addons-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-avatar' => 'align-self:{{UNIT}};',
				],
			]
		);
        
		$this->add_responsive_control(
			'avatar_width',
			[
				'label' => __( 'Wdth', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 250,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 96,
				],
				'selectors' => [
					'{{WRAPPER}} .skt-avatar img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
		// $this->add_responsive_control(
		// 	'avatar_margin',
		// 	[
		// 		'label' => __( 'Avatar Margin', 'skt-addons-elementor' ),
		// 		'type' => Controls_Manager::DIMENSIONS,
		// 		'size_units' => [ 'px', '%' ],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .skt-avatar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		// 		],
		// 	]
		// );

        $this->add_responsive_control(
			'avatar_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-avatar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'avatar_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-avatar img',
			]
		);

		$this->add_control(
			'avatar_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => '50',
					'right' => '50',
					'bottom' => '50',
					'left' => '50',
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-avatar img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();
	}

	protected function __author_button_style_controls() {

        $this->start_controls_section(
            '_section_style_button',
            [
                'label' => __( 'Button', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_responsive_control(
			'author_info_button_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => '7',
					'right' => '15',
					'bottom' => '7',
					'left' => '15',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-author-posts' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'author_info_button_hover_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-author-posts',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'author_info_button_border',
				'selector' => '{{WRAPPER}} .skt-desc .skt-author-posts',
			]
		);

		$this->add_control(
			'author_info_button_border_radius',
			[
				'label' => __('Border Radius', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .skt-desc .skt-author-posts' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
            'author_info_button_active_tabs'
        );

		$this->start_controls_tab(
            'author_info_button_normal_tab',
            [
                'label'    => __('Normal', 'skt-addons-elementor')
            ]
        );

        $this->add_control(
			'author_info_button_text_color',
			[
				'label' => esc_html__( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#555555',
				'selectors' => [
					'{{WRAPPER}} .skt-author-posts-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'author_info_button_background',
                'label' => __('Background', 'skt-addons-elementor'),
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .skt-author-posts-btn',
            ]
        );

		$this->end_controls_tab();

		$this->start_controls_tab(
            'author_info_button_hover_tab',
            [
                'label'    => __('Hover', 'skt-addons-elementor')
            ]
        );

		$this->add_control(
			'author_info_button_hover_text_color',
			[
				'label' => esc_html__( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#555555',
				'selectors' => [
					'{{WRAPPER}} .skt-author-posts-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'author_info_button_hover_background',
                'label' => __('Background', 'skt-addons-elementor'),
                'types' => ['classic', 'gradient'],
                'exclude' => ['image'],
                'selector' => '{{WRAPPER}} .skt-author-posts-btn:hover',
            ]
        );

		$this->add_control(
			'author_info_button_border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#555555',
				'selectors' => [
					'{{WRAPPER}} .skt-author-posts-btn:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

        $this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		// $user_id = get_the_author_meta( 'ID' );
		// $avatar = get_avatar($user_id, $settings['avatar_size']);
		// $display_name = get_the_author_meta( 'display_name' );
		// $bio = get_the_author_meta( 'description' );
		$user_id = get_post_field( 'post_author', get_the_ID() );
		$avatar = get_avatar($user_id, $settings['avatar_size']);
		$display_name = get_the_author_meta( 'display_name', $user_id );
		$bio = get_the_author_meta( 'description', $user_id );

		$post_url = get_author_posts_url( $user_id );
		$user_url =  get_the_author_meta( 'user_url', $user_id );

		$this->add_render_attribute('author', 'class', 'skt-author');
		$this->add_render_attribute('avatar', 'class', 'skt-avatar');
		if( $settings['avatar_image_position'] && 'yes' === $settings['show_avatar']){
			$this->add_render_attribute('author', 'class', 'avatar-position-' . $settings['avatar_image_position']);
		}

		if( $settings['show_author'] ){
			$this->add_render_attribute('author-title', 'class', 'skt-author-title');
		}

		?>

		<div <?php $this->print_render_attribute_string('author'); ?>>
			<?php if('yes' === $settings['show_avatar']) : ?>
				<div <?php $this->print_render_attribute_string('avatar'); ?>>
					<?php echo $avatar; ?>
				</div>
			<?php endif; ?>

			<div class="skt-desc">
				<?php
				if('yes' === $settings['show_author']){
					printf('<%1$s %2$s>%3$s</%1$s>', esc_attr($settings['author_meta_tag']), $this->get_render_attribute_string('author-title'), esc_html($display_name));
				}
				if('yes' === $settings['show_bio']){
					printf('<p>%1$s</p>', esc_html($bio));
				}

				if( 'yes' == $settings['show_archive_btn'] ) { ?>
					<a class="skt-author-posts skt-author-posts-btn" href="<?php echo esc_url( $post_url ); ?>">All Posts</a>
				<?php }
				?>
			</div>
		</div>


		<?php
	}
}
