<?php
namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Controls\Absp_Control_Styles;
use AbsoluteAddons\Absp_Widget;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class Absoluteaddons_Style_Team extends Absp_Widget {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'absolute-team';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Team', 'absolute-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'absp eicon-person';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'icofont',
			'font-awesome',
			'absolute-addons-core',
			'absolute-addons-custom',
			'absp-team',
			'absp-pro-team',
		);
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'absolute-addons-core',
			'absolute-addons-team',

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
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
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
		 * @param Absoluteaddons_Style_Team $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */

		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ & $this ] );
		$this->start_controls_section( 'section_content', [ 'label' => esc_html__( 'Template', 'absolute-addons' ) ] );

		$team = apply_filters( 'absp/widgets/team/styles' , [
			'one'              => esc_html__( 'Style One', 'absolute-addons' ),
			'two'              => esc_html__( 'Style Two', 'absolute-addons' ),
			'three'            => esc_html__( 'Style Three', 'absolute-addons' ),
			'four'             => esc_html__( 'Style Four', 'absolute-addons' ),
			'five-pro'         => esc_html__( 'Style Five Pro', 'absolute-addons' ),
			'six'              => esc_html__( 'Style Six', 'absolute-addons' ),
			'seven'            => esc_html__( 'Style Seven', 'absolute-addons' ),
			'eight'            => esc_html__( 'Style Eight', 'absolute-addons' ),
			'nine'             => esc_html__( 'Style Nine', 'absolute-addons' ),
			'ten-pro'          => esc_html__( 'Style Ten Pro', 'absolute-addons' ),
			'eleven-pro'       => esc_html__( 'Style Eleven Pro', 'absolute-addons' ),
			'twelve-pro'       => esc_html__( 'Style Twelve Pro', 'absolute-addons' ),
			'thirteen'         => esc_html__( 'Style Thirteen', 'absolute-addons' ),
			'fourteen'         => esc_html__( 'Style Fourteen', 'absolute-addons' ),
			'fifteen'          => esc_html__( 'Style Fifteen', 'absolute-addons' ),
			'sixteen'          => esc_html__( 'Style Sixteen', 'absolute-addons' ),
			'seventeen'        => esc_html__( 'Style Seventeen', 'absolute-addons' ),
			'eighteen-pro'     => esc_html__( 'Style Eighteen Pro', 'absolute-addons' ),
			'nineteen-pro'     => esc_html__( 'Style Nineteen Pro', 'absolute-addons' ),
			'twenty'           => esc_html__( 'Style Twenty', 'absolute-addons' ),
			'twenty-one'       => esc_html__( 'Style Twenty One', 'absolute-addons' ),
			'twenty-two'       => esc_html__( 'Style Twenty Two', 'absolute-addons' ),
			'twenty-three'     => esc_html__( 'Style Twenty Three', 'absolute-addons' ),
			'twenty-four'      => esc_html__( 'Style Twenty Four', 'absolute-addons' ),
			'twenty-five'      => esc_html__( 'Style Twenty Five', 'absolute-addons' ),
			'twenty-six-pro'   => esc_html__( 'Style Twenty Six Pro', 'absolute-addons' ),
			'twenty-seven-pro' => esc_html__( 'Style Twenty Seven Pro', 'absolute-addons' ),
			'twenty-eight-pro' => esc_html__( 'Style Twenty Eight Pro', 'absolute-addons' ),
			'twenty-nine-pro'  => esc_html__( 'Style Twenty Nine Pro', 'absolute-addons' ),
			'thirty-pro'       => esc_html__( 'Style Thirty Pro', 'absolute-addons' ),
		]);

		$this->add_control(
			'team_style_variation',
			array(
				'label'       => esc_html__( 'Team Style', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Absp_Control_Styles::TYPE,
				'options'     => $team,
				'default'     => 'one',
				'disabled'    => [
					'six',
					'seven',
					'eight',
					'nine',
					'six',
					'seven',
					'eight',
					'ten-pro',
					'eleven-pro',
					'twelve-pro',
					'fourteen',
					'fifteen',
					'sixteen',
					'seventeen',
					'eighteen-pro',
					'nineteen-pro',
					'twenty',
					'twenty-two',
					'twenty-three',
					'twenty-four',
					'twenty-five',
					'twenty-six-pro',
					'twenty-seven-pro',
					'twenty-eight-pro',
					'twenty-nine-pro',
					'thirty-pro',
				],
			)
		);

		$this->init_pro_alert( [
			'five-pro',
			'ten-pro',
			'eleven-pro',
			'twelve-pro',
			'eighteen-pro',
			'nineteen-pro',
			'twenty-six-pro',
			'twenty-seven-pro',
			'twenty-eight-pro',
			'twenty-nine-pro',
			'thirty-pro',
		] );


		$this->end_controls_section();

		/**
		 * Team Control
		 */

		$this->render_controller('team-item-controller');

		/**
		 * Team Style
		 */

		$this->render_controller('/style/team-item-style-one-controller');
		$this->render_controller('/style/team-item-style-two-controller');
		$this->render_controller('/style/team-item-style-thirteen-controller');
		$this->render_controller('/style/team-item-style-twenty-one-controller');
		$this->render_controller('/style/team-item-style-three-controller');
		$this->render_controller('/style/team-item-style-four-controller');
		$this->render_controller('/style/team-item-style-five-controller');
		$this->render_controller('/style/team-item-style-nine-controller');

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Team $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ & $this ] );


	}


	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes('team_member_name','basic');
		$this->add_render_attribute('team_member_name','class','title');

		$this->add_inline_editing_attributes('team_member_first_name','basic');
		$this->add_render_attribute('team_member_first_name','class','title');

		$this->add_inline_editing_attributes('team_member_last_name','advanced');
		$this->add_render_attribute('team_member_last_name','class','title');

		$this->add_inline_editing_attributes('team_member_designation','basic');
		$this->add_render_attribute('team_member_designation','class','designation');

		$this->add_inline_editing_attributes('team_member_about','basic');
		$this->add_render_attribute('team_member_about','class','about');

		$this->add_inline_editing_attributes('team_member_button_text','basic');
		$this->add_render_attribute('team_member_button_text','class','team-btn');


		?>
		<div class="absp-wrapper absp-widget">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-team -->
					<div class="absp-team element-<?php echo esc_attr( $settings['team_style_variation'] ); ?>">
						<?php $this->render_template( $settings['team_style_variation'] ); ?>
					</div>
					<!-- absp-team -->
				</div><!-- end .absp-wrapper-content -->
			</div><!-- end .absp-wrapper-inside -->
		</div><!-- end .absp-wrapper -->
		<?php

	}
}
