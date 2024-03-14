<?php
/**
 * Testimonial Class
 *
 * @package AbsolutePlugins
 * @version 1.0.0
 * @since 1.0.0
 */

namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Absp_Widget;
use AbsoluteAddons\Controls\Absp_Control_Styles;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * Elementor Testimonial widget.
 *
 * Elementor widget that displays Testimonials in different styles.
 *
 * @since 1.0.0
 */
class Absoluteaddons_Style_Testimonial extends Absp_Widget {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'absolute-testimonial';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_title() {
		return esc_html__( 'Testimonial', 'absolute-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'absp eicon-testimonial';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [
			'absolute-addons-custom',
			'absolute-addons-core',
			'font-awesome',
			'font-awesome-5',
			'absp-testimonial',
			'absp-pro-testimonial',
		];
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'absp-widgets' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function register_controls() {
		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Testimonial $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ & $this ] );

		$this->start_controls_section( 'section_content', [ 'label' => esc_html__( 'Template', 'absolute-addons' ) ] );

		$testimonial = apply_filters( $this->get_prefixed_hook( 'styles' ), [
			'one'       => esc_html__( 'Design Style One', 'absolute-addons' ),
			'two'       => esc_html__( 'Design Style Two', 'absolute-addons' ),
			'three'     => esc_html__( 'Design Style Three', 'absolute-addons' ),
			'four-pro'  => esc_html__( 'Design Style Four Pro', 'absolute-addons' ),
			'five'      => esc_html__( 'Design Style Five', 'absolute-addons' ),
			'six-pro'   => esc_html__( 'Design Style Six Pro', 'absolute-addons' ),
			'seven-pro' => esc_html__( 'Design Style Seven Pro', 'absolute-addons' ),
			'eight-pro' => esc_html__( 'Design Style Eight Pro', 'absolute-addons' ),
			'nine-pro'  => esc_html__( 'Design Style Nine Pro', 'absolute-addons' ),
			'ten'       => esc_html__( 'Design Style Ten', 'absolute-addons' ),
		] );

		$this->add_control(
			'absolute_testimonial',
			[
				'label'       => esc_html__( 'Testimonial Style', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Absp_Control_Styles::TYPE,
				'options'     => $testimonial,
				'default'     => 'one',
			]
		);

		$this->init_pro_alert( [
			'four-pro',
			'six-pro',
			'seven-pro',
			'eight-pro',
			'nine-pro',
		] );

		$this->end_controls_section();

		$this->render_controller( 'one' );
		$this->render_controller( 'eight' );
		$this->render_controller( 'nine' );
		$this->render_controller( 'ten' );

		$this->render_controller( 'template-testimonial-style' );

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Testimonial $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ & $this ] );
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		/**
		 * Testimonial Style One
		 */
		$this->add_inline_editing_attributes( 'item_style_one_title' );
		$this->add_render_attribute( 'item_style_one_title', 'class', 'testimonial-title' );

		$this->add_inline_editing_attributes( 'item_style_one_desig' );
		$this->add_render_attribute( 'item_style_one_desig', 'class', 'testimonial-desig' );

		$this->add_inline_editing_attributes( 'item_style_one_content' );
		$this->add_render_attribute( 'item_style_one_content', 'class', 'testimonial-content' );

		?>
		<div class="absp-wrapper absp-widget">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-testimonial -->
					<div class="absp-testimonial element-<?php echo esc_attr( $settings['absolute_testimonial'] ); ?>">
						<?php $this->render_template(); ?>
					</div>
					<!-- absp-testimonial -->
				</div>
			</div>
		</div>
		<?php
	}

}
