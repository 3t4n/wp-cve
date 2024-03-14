<?php
/**
 * The plugin elementor shortcode Widget.
 *
 * @link       https://shapedplugin.com/
 * @since      2.1.10
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor List Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 2.1.10
 */
class WP_Tabs_Free_Eelementor_Shortcode_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve list widget name.
	 *
	 * @since 2.1.10
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'sp-wp-tabs-shortcode-ew';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve list widget title.
	 *
	 * @since 2.1.10
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'WP Tabs', 'wp-expand-tabs-free' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve list widget icon.
	 *
	 * @since 2.1.10
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'sp-wp-tabs-ew-icon';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the list widget belongs to.
	 *
	 * @since 2.1.10
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'basic' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the list widget belongs to.
	 *
	 * @since 2.1.10
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'shortcode', 'wp tabs', 'tabs' );
	}

	/**
	 * Get all post list.
	 *
	 * @since 2.1.10
	 * @return array
	 */
	public function sp_wp_tabs_post_list() {
		$post_list    = array();
		$sp_eap_posts = new \WP_Query(
			array(
				'post_type'      => 'sp_wp_tabs',
				'post_status'    => 'publish',
				'posts_per_page' => 10000,
			)
		);
		$posts        = $sp_eap_posts->posts;
		foreach ( $posts as $post ) {
			$post_list[ $post->ID ] = $post->post_title ? $post->post_title : '#' . $post->ID;
		}
		krsort( $post_list );
		return $post_list;
	}

	/**
	 * Register list widget controls.
	 *
	 * Add input fields to allow the user to customize the widget settings.
	 *
	 * @since 2.1.10
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'wp-expand-tabs-free' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'sp_wp_tabs_shortcode',
			array(
				'label'       => __( 'WP Tabs Shortcode(s)', 'wp-expand-tabs-free' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'default'     => '',
				'options'     => $this->sp_wp_tabs_post_list(),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render list widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 2.1.10
	 * @access protected
	 */
	protected function render() {
		$settings             = $this->get_settings_for_display();
		$sp_wp_tabs_shortcode = $settings['sp_wp_tabs_shortcode'];

		if ( '' === $sp_wp_tabs_shortcode ) {
			echo '<div style="text-align: center; margin-top: 0; padding: 10px" class="elementor-add-section-drag-title">Select a shortcode</div>';
			return;
		}

		$generator_id = esc_attr( $sp_wp_tabs_shortcode );

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$post_id = esc_attr( $generator_id );
			// Content Accordion.
			$sptpro_data_src          = get_post_meta( $post_id, 'sp_tab_source_options', true );
			$sptpro_shortcode_options = get_post_meta( $post_id, 'sp_tab_shortcode_options', true );
			$main_section_title       = get_the_title( $post_id );

			// Get the existing shortcode id from the current page.
			$get_page_data      = WP_Tabs_Public::get_page_data();
			$found_shortcode_id = $get_page_data['generator_id'];

			// Check if shortcode and page ids are not exist in the current page then enqueue the stylesheet.
			if ( ! is_array( $found_shortcode_id ) || ! $found_shortcode_id || ! in_array( $post_id, $found_shortcode_id ) ) {
				// Load dynamic style for the existing shortcode.
				$dynamic_style = WP_Tabs_Public::load_dynamic_style( $post_id, $sptpro_shortcode_options );
				echo '<style id="sp_tab_dynamic_style' . esc_attr( $post_id ) . '">' . $dynamic_style['dynamic_css'] . '</style>'; // phpcs:ignore
			}

			WP_Tabs_Shortcode::sp_tabs_html_show( $post_id, $sptpro_data_src, $sptpro_shortcode_options, $main_section_title );
			?>
			<script src="<?php echo esc_url( WP_TABS_URL . 'public/js/collapse.min.js' ); ?>" ></script>
			<script src="<?php echo esc_url( WP_TABS_URL . 'public/js/tab.min.js' ); ?>" ></script>
			<script src="<?php echo esc_url( WP_TABS_URL . 'public/js/wp-tabs-public.min.js' ); ?>" ></script>
			<?php
		} else {
			echo do_shortcode( '[wptabs id="' . $generator_id . '"]' );
		}
	}

}
