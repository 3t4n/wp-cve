<?php

namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Controls\Absp_Control_Styles;
use Elementor\Controls_Manager;
use AbsoluteAddons\Absp_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * @since 1.1.0
 */
class Absoluteaddons_Style_Image_Grid extends Absp_Widget {

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
		return 'absolute-image-grid';
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
		return __( 'Image Grid', 'absolute-addons' );
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
		return 'absp eicon-image-rollover';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'absolute-addons-custom',
			'fontawesome',
			'absolute-icons',
			'absp-image-grid',
			'absp-pro-image-grid',
		);
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'absolute-addons-image-grid',
			'jquery.fancybox',
		);
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
		 * @param Absoluteaddons_Style_Logo_Grid $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ &$this ] );

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Template', 'absolute-addons' ),
			)
		);

		$image_grid_styles = apply_filters( 'absp/widgets/image-grid/styles', [
			'one'          => __( 'Style One', 'absolute-addons' ),
			'two'          => __( 'Style Two', 'absolute-addons' ),
			'three-pro'    => __( 'Style Three Pro', 'absolute-addons' ),
			'four'         => __( 'Style Four', 'absolute-addons' ),
			'five-pro'     => __( 'Style Five Pro', 'absolute-addons' ),
			'six-pro'      => __( 'Style Six Pro', 'absolute-addons' ),
			'seven'        => __( 'Style Seven', 'absolute-addons' ),
			'eight'        => __( 'Style Eight', 'absolute-addons' ),
			'nine'         => __( 'Style Nine', 'absolute-addons' ),
			'ten-pro'      => __( 'Style Ten Pro', 'absolute-addons' ),
			'eleven'       => __( 'Style Eleven', 'absolute-addons' ),
			'twelve'       => __( 'Style Twelve', 'absolute-addons' ),
			'thirteen-pro' => __( 'Style Thirteen Pro', 'absolute-addons' ),
		] );


		$this->add_control(
			'absolute_image_grid',
			array(
				'label'       => __( 'Image Grid Style', 'absolute-addons' ),
				'label_block' => TRUE,
				'type'        => Absp_Control_Styles::TYPE,
				'options'     => $image_grid_styles,
				'default'     => 'one',
				'disabled'    => [
					'two',
					'four',
					'five-pro',
					'six-pro',
					'seven',
					'eight',
					'nine',
					'ten-pro',
					'eleven',
					'twelve',
					'thirteen-pro',
				],
			)
		);

		$pro_styles = [
			'three-pro',
			'five-pro',
			'six-pro',
			'ten-pro',
			'thirteen-pro',
		];

		$this->init_pro_alert( $pro_styles );

		$this->end_controls_section();

		$this->render_controller( 'template-image-grid-item-controller' );

		$this->render_controller( 'template-image-grid-item-style-controller' );


		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Logo_Grid $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ &$this ] );
	}


	protected function render() {

		$settings = $this->get_settings_for_display();

		?>
		<div class="absp-wrapper absp-widget">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-image-grid -->
					<div class="absp-image-grid element-<?php echo esc_attr( $settings['absolute_image_grid'] ); ?>">
						<?php
						if ( $settings['absolute_image_grid'] ) {
							$this->render_template( $settings['absolute_image_grid'] );
						}
						?>
					</div>
					<!-- absp-image-grid -->
				</div><!-- end .absp-wrapper-content -->
			</div><!-- end .absp-wrapper-inside -->
		</div><!-- end .absp-wrapper -->
		<?php


	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function content_template() {

	}
}
