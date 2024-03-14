<?php
/**
 * Elementor Shortcodes List Widget Class.
 *
 * This widget is deprecated and will be removed in some future version.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Widgets\Elementor;

use RT\FoodMenu\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Elementor Shortcodes List Widget Class.
 */
class Shortcodes extends \Elementor\Widget_Base {
	public function get_name() {
		return 'food-menu';
	}

	public function get_title() {
		return esc_html__( 'Food Menu', 'tlp-food-menu' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function get_script_depends() {
		if ( ! $this->isPreview() ) {
			return [];
		}

		return [
			'fmp-image-load',
			'fmp-swiper',
			'fmp-scrollbar',
			'fmp-flex',
			'fmp-modal',
			'fmp-actual-height',
			'fm-frontend',
		];
	}

	public function get_style_depends() {
		if ( ! $this->isPreview() ) {
			return [];
		}

		return [
			'fmp-swiper',
			'fmp-scrollbar',
			'fmp-fontawsome',
			'fmp-modal',
			'fmp-flex',
			'fm-frontend',
		];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Food Menu', 'tlp-food-menu' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'food_menu_id',
			[
				'type'    => \Elementor\Controls_Manager::SELECT2,
				'id'      => 'style',
				'label'   => esc_html__( 'Select ShortCode', 'tlp-food-menu' ),
				'options' => Fns::get_shortCode_list(),
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( isset( $settings['food_menu_id'] ) && ! empty( $settings['food_menu_id'] ) ) {
			echo do_shortcode( '[foodmenu id="' . absint( $settings['food_menu_id'] ) . '"]' );
		} else {
			esc_html_e( 'Please select a food menu shordcode', 'tlp-food-menu' );
		}

		$this->edit_mode_script();
	}

	public function edit_mode_script() {
		if ( ! $this->isPreview() ) {
			return;
		}

		$ajaxurl = admin_url( 'admin-ajax.php' );
		?>

		<script>
			var fmp = {
				ajaxurl    : '<?php echo esc_url( $ajaxurl ); ?>',
				nonceID    : '<?php echo esc_attr( Fns::nonceID() ); ?>',
				nonce      : '<?php echo esc_attr( wp_create_nonce( Fns::nonceText() ) ); ?>',
				wc_cart_url: '<?php echo TLPFoodMenu()->isWcActive() ? wc_get_cart_url() : ''; ?>'
			};

			initFMP();

			var isIsotope     = jQuery('.fmp-isotope-item');
			var isGridIsotope = jQuery('.fmp-wrapper[data-layout*="layout"] .masonry-grid-item');

			if(isIsotope.length > 0) {
				isIsotope.isotope();
			}

			if(isGridIsotope.length > 0) {
				isGridIsotope.isotope();
			}
		</script>

		<?php
	}

	public function isPreview() {
		return \Elementor\Plugin::$instance->preview->is_preview_mode() || \Elementor\Plugin::$instance->editor->is_edit_mode();
	}
}
