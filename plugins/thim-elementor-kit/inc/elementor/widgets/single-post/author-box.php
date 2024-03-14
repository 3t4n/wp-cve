<?php
namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class Thim_Ekit_Widget_Author_Box extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-author-box';
	}

	public function get_title() {
		return esc_html__( 'Author Box', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-person';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY_SINGLE_POST );
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Author Box', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'show_avatar',
			array(
				'label'       => esc_html__( 'Show Avatar', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Show', 'thim-elementor-kit' ),
				'label_off'   => esc_html__( 'Hide', 'thim-elementor-kit' ),
				'default'     => 'yes',
				'separator'   => 'before',
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'show_name',
			array(
				'label'       => esc_html__( 'Display Name', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Show', 'thim-elementor-kit' ),
				'label_off'   => esc_html__( 'Hide', 'thim-elementor-kit' ),
				'default'     => 'yes',
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'author_name_tag',
			array(
				'label'   => esc_html__( 'Author Name Tag', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
				),
				'default' => 'h4',
			)
		);

		$this->add_control(
			'link_to',
			array(
				'label'       => esc_html__( 'Link', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					''              => esc_html__( 'None', 'thim-elementor-kit' ),
					'website'       => esc_html__( 'Website', 'thim-elementor-kit' ),
					'posts_archive' => esc_html__( 'All Posts by Author', 'thim-elementor-kit' ),
					'custom'        => esc_html__( 'Custom', 'thim-elementor-kit' ),
				),
				'description' => esc_html__( 'Link for the Author Name and Image', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'author_link',
			array(
				'label'       => esc_html__( 'Link', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'thim-elementor-kit' ),
				'condition'   => array(
					'link_to' => 'custom',
				),
				'description' => esc_html__( 'Link for the Author Name and Image', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'show_biography',
			array(
				'label'       => esc_html__( 'Biography', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Show', 'thim-elementor-kit' ),
				'label_off'   => esc_html__( 'Hide', 'thim-elementor-kit' ),
				'default'     => 'yes',
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'avatar_position',
			array(
				'label'        => esc_html__( 'Avatar Position', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'  => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-left',
					),
					'top'   => array(
						'title' => esc_html__( 'Top', 'thim-elementor-kit' ),
						'icon'  => 'eicon-v-align-top',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'separator'    => 'before',
				'prefix_class' => 'thim-ekit-single-post__author-box--avatar-position-',
			)
		);

		$this->add_control(
			'alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-post__author-box' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->register_style_controls();
	}

	protected function register_style_controls() {
		$this->start_controls_section(
			'section_style_author_box',
			array(
				'label' => esc_html__( 'Image', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'image_size',
			array(
				'label'     => esc_html__( 'Image Size', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-post__author-box img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'image_gap',
			array(
				'label'     => esc_html__( 'Gap', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}.thim-ekit-single-post__author-box--avatar-position-left .thim-ekit-single-post__author-box,
					{{WRAPPER}}.thim-ekit-single-post__author-box--avatar-position-right .thim-ekit-single-post__author-box' => 'column-gap: {{SIZE}}{{UNIT}};',

					'{{WRAPPER}}.thim-ekit-single-post__author-box--avatar-position-top .thim-ekit-single-post__author-box__avatar' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} .thim-ekit-single-post__author-box__avatar img',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekit-single-post__author-box__avatar img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_box_shadow',
				'exclude'  => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} .thim-ekit-single-post__author-box__avatar img',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_text',
			array(
				'label' => esc_html__( 'Text', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_name_style',
			array(
				'label'     => esc_html__( 'Author Name', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-post__author-box__content__title_inner' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'name_typography',
				'selector' => '{{WRAPPER}} .thim-ekit-single-post__author-box__content__title_inner',
			)
		);

		$this->add_responsive_control(
			'name_gap',
			array(
				'label'     => esc_html__( 'Gap', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-post__author-box__content__title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'heading_bio_style',
			array(
				'label'     => esc_html__( 'Biography', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'bio_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-post__author-box__content__bio' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'bio_typography',
				'selector' => '{{WRAPPER}} .thim-ekit-single-post__author-box__content__bio',
			)
		);

		$this->add_responsive_control(
			'bio_gap',
			array(
				'label'     => esc_html__( 'Gap', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-post__author-box__content__bio' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();
	}

	public function render() {
		do_action( 'thim-ekit/modules/single-post/before-preview-query' );

		$settings = $this->get_settings_for_display();

		global $post;

		$user_id = $post->post_author;

		$avatar_args['size'] = 300;

		$user = get_userdata( $user_id );

		$author['avatar']       = get_avatar_url( $user_id, $avatar_args );
		$author['display_name'] = $user->display_name;
		$author['website']      = $user->user_url;
		$author['bio']          = $user->description;
		$author['posts_url']    = get_author_posts_url( $user_id );

		$show_avatar    = $settings['show_avatar'] === 'yes' && ! empty( $author['avatar'] );
		$show_name      = $settings['show_name'] === 'yes' && ! empty( $author['display_name'] );
		$show_biography = $settings['show_biography'] === 'yes' && ! empty( $author['bio'] );

		$link      = $settings['link_to'];
		$link_tag  = 'span';
		$link_attr = '';

		if ( $link !== '' ) {
			$link_tag = 'a';

			if ( $link === 'website' ) {
				$link_attr = ' href="' . esc_url( $author['website'] ) . '"';
			} elseif ( $link === 'posts_archive' ) {
				$link_attr = ' href="' . esc_url( $author['posts_url'] ) . '"';
			} elseif ( $link === 'custom' ) {
				$link_target = $settings['author_link']['is_external'] ? ' target="_blank" rel="noopener noreferrer"' : '';
				$link_attr   = ' href="' . esc_url( $settings['author_link']['url'] ) . '"' . wp_kses_post( $link_target ) . ' ';
			}
		}
		?>

		<div class="thim-ekit-single-post__author-box">
			<?php if ( $show_avatar ) : ?>
				<div class="thim-ekit-single-post__author-box__avatar">
					<?php echo '<' . esc_html( $link_tag ) . wp_kses_post( $link_attr ) . '>'; ?>
						<img src="<?php echo esc_url( $author['avatar'] ); ?>" alt="<?php echo esc_attr( $author['display_name'] ); ?>">
					<?php echo '</' . esc_html( $link_tag ) . '>'; ?>
				</div>
			<?php endif; ?>

			<div class="thim-ekit-single-post__author-box__content">
				<?php if ( $show_name ) : ?>
					<?php echo '<' . Utils::validate_html_tag( $settings['author_name_tag'] ) . ' class="thim-ekit-single-post__author-box__content__title">'; ?>
						<?php echo '<' . esc_html( $link_tag ) . wp_kses_post( $link_attr ) . ' class="thim-ekit-single-post__author-box__content__title_inner">'; ?>
							<?php echo esc_html( $author['display_name'] ); ?>
						<?php echo '</' . esc_html( $link_tag ) . '>'; ?>
					<?php echo '</' . Utils::validate_html_tag( $settings['author_name_tag'] ) . '>'; ?>
				<?php endif; ?>

				<?php if ( $show_biography ) : ?>
					<div class="thim-ekit-single-post__author-box__content__bio">
						<?php echo esc_html( $author['bio'] ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<?php
		do_action( 'thim-ekit/modules/single-post/after-preview-query' );
	}
}
