<?php
/**
 * The plugin elementor block Initializer.
 *
 * @link       https://shapedplugin.com/
 * @since      2.1.8
 *
 * @package    WP_Team
 * @subpackage WP_Team/Admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\WPTeam\Admin\ElementBlock;

use ShapedPlugin\WPTeam\Frontend\Helper;
use ShapedPlugin\WPTeam\Frontend\Frontend;

/**
 * Elementor wp team shortcode Widget.
 *
 * @since 2.2.1
 */
class Shortcode_Widget extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 2.2.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'team_pro_shortcode';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.2.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'WP Team', 'team-free' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.2.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'spteam-icon-team-pro';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 2.2.1
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
	 * @since 2.2.1
	 * @return array
	 */
	public function sptp_post_list() {
		$post_list  = array();
		$sptp_posts = new \WP_Query(
			array(
				'post_type'      => 'sptp_generator',
				'post_status'    => 'publish',
				'posts_per_page' => 9999,
			)
		);
		$posts      = $sptp_posts->posts;
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
				'label' => __( 'Content', 'team-free' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'sp_team_pro_shortcode',
			array(
				'label'       => __( 'WP Team Shortcode(s)', 'team-free' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'default'     => '',
				'options'     => $this->sptp_post_list(),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render wp team shortcode widget output on the frontend.
	 *
	 * @since 2.2.1
	 * @access protected
	 */
	protected function render() {

		$settings       = $this->get_settings_for_display();
		$sptp_shortcode = $settings['sp_team_pro_shortcode'];

		if ( '' === $sptp_shortcode ) {
			echo '<div style="text-align: center; margin-top: 0; padding: 10px" class="elementor-add-section-drag-title">Select a shortcode</div>';
			return;
		}

		$generator_id = (int) esc_attr( $sptp_shortcode );

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			// Preset Layouts.
			$layout = get_post_meta( $generator_id, '_sptp_generator_layout', true );
			// All the visible options for the Shortcode like – Global, Filter, Display, Popup, Typography etc.
			$settings           = get_post_meta( $generator_id, '_sptp_generator', true );
			$main_section_title = get_the_title( $generator_id );
			// This shortcode id not in page id option. Enqueue stylesheets in shortcode.
			$dynamic_style = Frontend::load_dynamic_style( $generator_id, $layout, $settings );
			echo '<style id="team_free_dynamic_css' . $generator_id . '">' . $dynamic_style['dynamic_css'] . '</style>';//phpcs:ignore

			Helper::sptp_html_show( $generator_id, $layout, $settings, $main_section_title );
			?>
			<script src="<?php echo esc_url( SPT_PLUGIN_ROOT . 'src/Frontend/js/script.js' ); ?>" ></script>
			<?php
		} else {
			echo do_shortcode( '[wpteam id="' . $generator_id . '"]' );
		}

	}

}
