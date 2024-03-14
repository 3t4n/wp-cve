<?php

namespace Elementor;

use Elementor\Plugin;
use Thim_EL_Kit\GroupControlTrait;

class Thim_Ekit_Widget_Slider extends Widget_Base {
	use GroupControlTrait;

	public function get_name() {
		return 'thim-ekits-slider';
	}

	public function get_title() {
		return esc_html__( 'Slider', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-slider-3d';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY );
	}

	public function get_keywords() {
		return [
			'thim',
			'tab',
			'tabs',
		];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'setting',
			[
				'label' => esc_html__( 'General', 'thim-elementor-kit' ),
			]
		);

		$this->add_control(
			'slider_id',
			[
				'label'       => __( 'Slider', 'thim-elementor-kit' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'multiple'    => false,
				'options'     => \Thim_EL_Kit\Elementor::get_cat_taxonomy( 'thim_ekits_slider', false, false ),
				'default'     => 'choose',
				'label_block' => true,
			]
		);

		$this->end_controls_section();

		$this->_register_settings_slider( null, false );

		$this->_register_setting_slider_dot_style();

		$this->_register_setting_slider_nav_style();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$slider_settings = [
			'slidesPerView'          => $settings['slidesPerView'],
			'slidesPerGroup'         => $settings['slidesPerGroup'],
			'spaceBetween'           => $settings['spaceBetween'],
			'slider_speed'           => $settings['slider_speed'],
			'autoplay_speed'         => $settings['autoplay_speed'],
			'slider_show_pagination' => $settings['slider_show_pagination'] ?? 'no',
			'slider_autoplay'        => $settings['slider_autoplay'] ?? 'false',
			'slider_show_arrow'      => $settings['slider_show_arrow'] ?? 'no',
			'slider_loop'            => $settings['slider_loop'] ?? 'false',
		];

		if ( isset( $settings['pause_on_interaction'] ) && $settings['pause_on_interaction'] ) {
			$slider_settings ['pause_on_interaction'] = $settings['pause_on_interaction'];
		}

		if ( isset( $settings['pause_on_hover'] ) && $settings['pause_on_hover'] ) {
			$slider_settings ['pause_on_hover'] = $settings['pause_on_hover'];
		}
		$this->add_render_attribute( '_wrapper', 'data-settings', wp_json_encode( $slider_settings ) );


		if ( empty( $settings['slider_id'] ) ) {
			return;
		}

		$query_args = array(
			'post_type'           => 'thim_ekits_slide',
			'posts_per_page'      => - 1,
			'orderby'             => 'menu_order',
			'order'               => 'ASC',
			'ignore_sticky_posts' => true,
		);

		if ( is_numeric( $settings['slider_id'] ) ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'thim_ekits_slider',
					'field'    => 'term_id',
					'terms'    => $settings['slider_id'],
				)
			);
		} else {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'thim_ekits_slider',
					'field'    => 'slug',
					'terms'    => $settings['slider_id'],
				)
			);
		}

		$slides = get_posts( $query_args );

		if ( is_wp_error( $slides ) || empty( $slides ) ) {
			return;
		}

		$this->render_nav_pagination_slider( $settings );
		$swiper_class = \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';
		$class        = 'thim-ekits-sliders ' . $swiper_class;
		?>

		<div class="<?php echo esc_attr( $class ); ?>">
			<div class="swiper-wrapper">
				<?php
				foreach ( $slides as $slide ) :
					echo '<div class="swiper-slide">';
					echo \Thim_EL_Kit\Utilities\Elementor::instance()->render_content( $slide->ID );
					echo '</div>';
				endforeach;
				?>
			</div>
		</div>

		<?php
	}
}
