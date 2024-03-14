<?php
/**
 * The plugin elementor widget.
 *
 * @link       https://shapedplugin.com/
 * @since      2.6.1
 * @package    woo-product-slider.
 * @subpackage woo-product-slider/Admin.
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\WooProductSlider\Admin\ElementorAddons;

use ShapedPlugin\WooProductSlider\Frontend\Frontend;
use ShapedPlugin\WooProductSlider\Frontend\Helper;

/**
 * Elementor Product Slider ShortCode Widget.
 *
 * @since 2.6.1
 */
class Shortcode_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 2.6.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'product_slider_shortcode';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.6.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Woo Product Slider', 'woo-product-slider' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.6.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'wps-icon-block';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 2.6.1
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'basic' );
	}

	/**
	 * Get all post list.
	 *
	 * @since 2.6.1
	 * @return array
	 */
	public function sp_wpsp_post_list() {
		$post_list     = array();
		$sp_wpsp_posts = new \WP_Query(
			array(
				'post_type'      => 'sp_wps_shortcodes',
				'post_status'    => 'publish',
				'posts_per_page' => 10000,
			)
		);
		$posts         = $sp_wpsp_posts->posts;
		foreach ( $posts as $post ) {
			$post_list[ $post->ID ] = $post->post_title;
		}
		krsort( $post_list );
		return $post_list;
	}

	/**
	 * Controls register.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'woo-product-slider' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'sp_product_slider_pro_shortcode',
			array(
				'label'       => __( 'Woo Product Slider Shortcode(s)', 'woo-product-slider' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'default'     => '',
				'options'     => $this->sp_wpsp_post_list(),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render product slider pro shortcode widget output on the frontend.
	 *
	 * @since 2.6.1
	 * @access protected
	 */
	protected function render() {
		$settings          = $this->get_settings_for_display();
		$sp_wpsp_shortcode = $settings['sp_product_slider_pro_shortcode'];

		if ( '' === $sp_wpsp_shortcode ) {
			echo '<div style="text-align: center; margin-top: 0; padding: 10px" class="elementor-add-section-drag-title">Select a shortcode</div>';
			return;
		}

		$post_id = esc_attr( $sp_wpsp_shortcode );

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$shortcode_data     = get_post_meta( $post_id, 'sp_wps_shortcode_options', true );
			$main_section_title = get_the_title( $post_id );
			// Stylesheet loading problem solving here. Shortcode id to push page id option for getting how many shortcode in the page.
			$dynamic_style = Frontend::load_dynamic_style( $post_id, $shortcode_data );
			// This shortcode id not in page id option. Enqueue stylesheets in shortcode.
			echo '<style id="sp_product_slider_dynamic_css' . esc_attr( $post_id ) . '">' . wp_strip_all_tags( $dynamic_style['dynamic_css'] ) . '</style>'; // phpcs:ignore

			Helper::spwps_html_show( $post_id, $shortcode_data, $main_section_title );
			?>
			<script src="<?php echo esc_url( SP_WPS_URL . 'Frontend/assets/js/scripts.min.js' ); ?>" ></script>
			<?php
		} else {
			echo do_shortcode( '[woo_product_slider id="' . $post_id . '"]' );
		}

	}
}
