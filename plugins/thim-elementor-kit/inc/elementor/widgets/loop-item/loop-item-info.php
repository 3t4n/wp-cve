<?php

namespace Elementor;

use Elementor\Widget_Icon_List;
use Thim_EL_Kit\Utilities\Widget_Loop_Trait;

defined( 'ABSPATH' ) || exit;

class Thim_Ekit_Widget_Loop_Item_Info extends Widget_Icon_List {

	use Widget_Loop_Trait;

	public function get_name() {
		return 'thim-loop-item-info';
	}

	public function get_title() {
		return esc_html__( 'Item Info', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'eicon-post-info';
	}

	public function get_inline_css_depends() {
		return array(
			array(
				'name'               => 'icon-list',
				'is_core_dependency' => true,
			),
		);
	}

	public function get_keywords() {
		return [ 'info', 'date', 'time', 'author', 'taxonomy', 'comments', 'terms', 'avatar' ];
	}

	protected function register_controls_repeater( $repeater ) {
		$repeater->add_control(
			'type',
			array(
				'label'   => esc_html__( 'Type', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'author'   => esc_html__( 'Author', 'thim-elementor-kit' ),
					'date'     => esc_html__( 'Date', 'thim-elementor-kit' ),
					'comments' => esc_html__( 'Comments', 'thim-elementor-kit' ),
					'terms'    => esc_html__( 'Terms', 'thim-elementor-kit' ),
					'custom'   => esc_html__( 'Custom', 'thim-elementor-kit' ),
				),
			)
		);

		$repeater->add_control(
			'date_format',
			array(
				'label'       => esc_html__( 'Date Format', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'ai'          => [
					'active' => false,
				],
				'placeholder' => 'F j, Y',
				'description' => sprintf(
					__( '<a href="%s">Documentation on date and time formatting.</a>', 'thim-elementor-kit' ), 'https://wordpress.org/documentation/article/customize-date-and-time-format/'
				),
				'condition'   => array(
					'type' => 'date',
				),
			)
		);
		$repeater->add_control(
			'text_before_author',
			[
				'label'     => esc_html__( 'Before', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::TEXT,
				'ai'        => [
					'active' => false,
				],
				'condition' => array(
					'type' => 'author',
				),
			]
		);
		$repeater->add_control(
			'show_avatar',
			array(
				'label'     => esc_html__( 'Avatar', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array(
					'type' => 'author',
				),
			)
		);

		$repeater->add_responsive_control(
			'author_avatar_width',
			array(
				'label'      => esc_html__( 'Avatar Size', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
				),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-icon-list-text .author-avatar' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'type'        => 'author',
					'show_avatar' => 'yes',
				),
			)
		);
		$repeater->add_responsive_control(
			'avatar_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-icon-list-text .author-avatar img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'type'        => 'author',
					'show_avatar' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'text_no_comments',
			[
				'label'     => esc_html__( 'No Comments', 'thim-elementor-kit' ),
				'default'   => esc_html__( 'No Responses', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::TEXT,
				'ai'        => [
					'active' => false,
				],
				'condition' => array(
					'type' => 'comments',
				),
			]
		);

		$repeater->add_control(
			'text_one_comments',
			[
				'label'     => esc_html__( 'One Comment', 'thim-elementor-kit' ),
				'default'   => esc_html__( 'One Response', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::TEXT,
				'ai'        => [
					'active' => false,
				],
				'condition' => array(
					'type' => 'comments',
				),
			]
		);

		$repeater->add_control(
			'text_many_comments',
			[
				'label'     => esc_html__( 'Many Comment', 'thim-elementor-kit' ),
				'default'   => esc_html__( '{number} Responses', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::TEXT,
				'ai'        => [
					'active' => false,
				],
				'condition' => array(
					'type' => 'comments',
				),
			]
		);
		$repeater->add_control(
			'taxonomy',
			array(
				'label'       => esc_html__( 'Taxonomy', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'options'     => \Thim_EL_Kit\Elementor::register_option_dynamic_tags_item_terms(),
				'condition'   => array(
					'type' => 'terms',
				),
			)
		);

		$repeater->add_control(
			'term_separator',
			array(
				'label'     => esc_html__( 'Seperate', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::TEXT,
				'ai'        => [
					'active' => false,
				],
				'default'   => ', ',
				'condition' => array(
					'type' => 'terms',
					'show_one!' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'show_one',
			[
				'label'     => esc_html__( 'Show One Term', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'condition' => array(
					'type' => 'terms',
				),
			]
		);

		$repeater->add_control(
			'show_link',
			[
				'label'     => esc_html__( 'Show Link', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					'type!' => [ 'date', 'custom' ],
				),
			]
		);

		$repeater->add_control(
			'text',
			array(
				'label'       => esc_html__( 'Text', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
				'ai'          => [
					'active' => false,
				],
				'condition'   => array(
					'type' => 'custom',
				),
			)
		);
		$repeater->add_control(
			'custom_url',
			array(
				'label'     => esc_html__( 'Custom URL', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::URL,
				'dynamic'   => [
					'active' => true,
				],
				'ai'        => [
					'active' => false,
				],
				'condition' => array(
					'type' => 'custom',
				),
			)
		);

		$repeater->add_control(
			'selected_icon',
			array(
				'label'       => esc_html__( 'Choose Icon', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'condition'   => array(
					'show_avatar!' => 'yes',
				),
			)
		);

	}

	protected function register_controls() {
		parent::register_controls();

		$this->update_control( 'view', [
			'default' => 'inline'
		] );

		$repeater_list = new Repeater();
		$this->register_controls_repeater( $repeater_list );

		$this->update_control(
			'icon_list',
			array(
				'fields'      => $repeater_list->get_controls(),
				'default'     => array(
					array(
						'type'          => 'author',
						'selected_icon' => array(
							'value'   => 'far fa-user-circle',
							'library' => 'fa-regular',
						),
					),
					array(
						'type'          => 'date',
						'selected_icon' => array(
							'value'   => 'fas fa-calendar',
							'library' => 'fa-solid',
						),
					),
					array(
						'type'          => 'comments',
						'selected_icon' => array(
							'value'   => 'far fa-comment-dots',
							'library' => 'fa-regular',
						),
					),
				),
				'title_field' => '{{{ elementor.helpers.renderIcon( this, selected_icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}} <span style="text-transform: capitalize;">{{{ type }}}</span>',
			)
		);
		$this->remove_control( 'link_click' );
		$this->update_control(
			'text_color_hover',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-item .elementor-icon-list-text a:hover,{{WRAPPER}} .elementor-icon-list-item a.elementor-icon-list-text:hover' => 'color: {{VALUE}};',
				],
			]
		);
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'icon_list', 'class', 'elementor-icon-list-items' );
		$this->add_render_attribute( 'list_item', 'class', 'elementor-icon-list-item' );

		if ( 'inline' === $settings['view'] ) {
			$this->add_render_attribute( 'icon_list', 'class', 'elementor-inline-items' );
			$this->add_render_attribute( 'list_item', 'class', 'elementor-inline-item' );
		}

		if ( ! empty( $settings['icon_list'] ) ) {
			?>
			<ul <?php $this->print_render_attribute_string( 'icon_list' ); ?>>
				<?php
				foreach ( $settings['icon_list'] as $repeater_item ) {
					?>
					<li <?php $this->print_render_attribute_string( 'list_item' ); ?>>

						<?php $this->render_item( $repeater_item ); ?>

					</li>
					<?php
				}
				?>
			</ul>
			<?php
		}
	}

	protected function render_item( $repeater_item ) {
		switch ( $repeater_item['type'] ) {
			case 'author':
				$this->render_author( $repeater_item );
				break;
			case 'date':
				$this->render_date( $repeater_item );
				break;
			case 'comments':
				$this->render_comments( $repeater_item );
				break;
			case 'terms':
				$this->render_terms( $repeater_item );
				break;
			case 'custom':
				$this->render_custom( $repeater_item );
				break;
		}
	}

	protected function render_custom( $repeater_item ) {
		if ( empty( $repeater_item['text'] ) ) {
			return;
		}
		// check render icon
		$this->render_icon( $repeater_item );

		if ( ! empty( $repeater_item['custom_url']['url'] ) ) {
			$link_key = 'link_' . $repeater_item['_id'];
			$this->add_link_attributes( $link_key, $repeater_item['custom_url'] );
			?>
			<a <?php $this->print_render_attribute_string( $link_key ); ?> class="elementor-icon-list-text">
				<?php echo wp_kses_post( $repeater_item['text'] ); ?>
			</a>
			<?php
		} else {
			echo wp_kses_post( '<span class="elementor-icon-list-text">'. $repeater_item['text'] .'</span>' );
 		}
	}

	protected function render_terms( $repeater_item ) {
		$taxonomy = $repeater_item['taxonomy'];

		if ( empty( $taxonomy ) ) {
			return false;
		}

		$terms = wp_get_post_terms( get_the_ID(), $taxonomy );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return false;
		}

		$terms_list = [];
		// check render icon
		$this->render_icon( $repeater_item );

		foreach ( $terms as $term ) {
			if ( 'yes' == $repeater_item['show_link'] ) {
				$terms_list[] = '<a href="' . esc_url( get_term_link( $term ) ) . '" class="loop-item-term">' . esc_html( $term->name ) . '</a>';
			} else {
				$terms_list[] = '<span class="loop-item-term">' . esc_html( $term->name ) . '</span>';
			}
		}

		if ( 'yes' == $repeater_item['show_one'] ) {
			$value = $terms_list[0];
		} else {
			$value = implode( $repeater_item['term_separator'], $terms_list );
		}

		echo wp_kses_post( '<span class="elementor-icon-list-text">'. $value .'</span>' );
 	}

	protected function render_comments( $repeater_item ) {
		if ( ! comments_open() ) {
			return;
		}

		$comments_number = get_comments_number();
		if ( ! $comments_number ) {
			$count = $repeater_item['text_no_comments'];
		} elseif ( 1 == $comments_number ) {
			$count = $repeater_item['text_one_comments'];
		} else {
			$count = strtr( $repeater_item['text_many_comments'], [
				'{number}' => number_format_i18n( $comments_number ),
			] );
		}

		if ( 'yes' === $repeater_item['show_link'] ) {
			$count = sprintf( '<a href="%s">%s</a>', get_comments_link(), $count );
		}
		// check render icon
		$this->render_icon( $repeater_item );

		echo wp_kses_post( '<span class="elementor-icon-list-text">'. $count .'</span>' );
	}

	protected function render_author( $repeater_item ) {
		$author_name = '';
		if ( 'yes' === $repeater_item['show_avatar'] ) {
			$author_name .= '<span class="elementor-icon-list-icon author-avatar"><img src="' . get_avatar_url( get_the_author_meta( 'ID' ), 96 ) . '"></span>';
		}

		$author_name .= get_the_author_meta( 'display_name' );

		if ( $repeater_item['text_before_author'] ) {
			echo '<span class="text-before">' . wp_kses_post( $repeater_item['text_before_author'] ) . '</span>';
		}

		if ( 'yes' === $repeater_item['show_link'] ) {
			$author_name = sprintf( '<a href="%s">%s</a>', esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), $author_name );
		}
		// check render icon
		$this->render_icon( $repeater_item );

 		echo wp_kses_post( '<span class="elementor-icon-list-text">'. $author_name .'</span>' );

	}

	protected function render_date( $repeater_item ) {
		$format_date = get_option( 'date_format' );
		if ( $repeater_item['date_format'] ) {
			$format_date = $repeater_item['date_format'];
		}
		// check render icon
		$this->render_icon( $repeater_item );

		echo wp_kses_post( '<span class="elementor-icon-list-text">'. get_the_time( $format_date ) .'</span>' );
 	}

	protected function render_icon( $repeater_item ) {
		// type custom text empty
//		if ( $repeater_item['type'] == 'custom' && empty( $repeater_item['text'] ) ) {
//			return;
//		}

		if ( ! empty( $repeater_item['selected_icon']['value'] ) ) : ?>
			<span class="elementor-icon-list-icon">
				<?php Icons_Manager::render_icon( $repeater_item['selected_icon'], [ 'aria-hidden' => 'true' ] ); ?>
			</span>
		<?php endif;
	}

	protected function content_template() {
	}
}
