<?php
namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Modules\NestedElements\Base\Widget_Nested_Base;
use Elementor\Modules\NestedElements\Controls\Control_Nested_Repeater;
use Elementor\Repeater;

if ( ! \Elementor\Plugin::instance()->experiments->is_feature_active( 'nested-elements' ) ) {
	return;
}

class Thim_Ekit_Widget_Course_Item_Section extends Widget_Nested_Base {

	public function get_name() {
		return 'thim-ekits-course-item-section';
	}

	public function get_title() {
		return esc_html__( 'Course Section', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-tabs';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY_SINGLE_COURSE_ITEM );
	}

	public function get_keywords() {
		return [ 'section', 'row', 'column', 'course' ];
	}

	/**
	 * Undocumented function
	 * !Important: When change this function, need change in inc/modules/single-course-item/class-init.php in function create_template.
	 *
	 * @return void
	 */
	protected function get_default_children_elements() {
		return [
			[
				'elType' => 'container',
				'settings' => [
					'_title' => __( 'Header', 'thim-elementor-kit' ),
					'css_classes'=> 'ekit-popup-header'
				],
			],
			[
				'elType' => 'container',
				'settings' => [
					'_title' => __( 'Sidebar', 'thim-elementor-kit' ),
					'css_classes'=> 'ekit-popup-sidebar'
				],
			],
			[
				'elType' => 'container',
				'settings' => [
					'_title' => __( 'Content', 'thim-elementor-kit' ),
					'_element_id'=> 'popup-content',
					'css_classes'=> 'ekit-popup-content'
				],
			],
			[
				'elType' => 'container',
				'settings' => [
					'_title' => __( 'Footer', 'thim-elementor-kit' ),
					'css_classes'=> 'ekit-popup-footer'
				],
			],
		];
	}

	protected function get_default_repeater_title_setting_key() {
		return 'popup_type';
	}

	protected function get_default_children_placeholder_selector() {
		return '.thim-ekit-course-items';
	}


	protected function register_controls() {
		$this->start_controls_section(
			'item_options',
			[
				'label' => esc_html__( 'Options', 'thim-elementor-kit' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'popup_type',
			[
				'label' => esc_html__( 'Type', 'thim-elementor-kit' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'sidebar',
			]
		);
		$repeater->add_responsive_control(
			'width_sidebar',
			[
				'label'      => __( 'Width Sidebar', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .thim-ekit-course-items' => '--thim-width-sidebar-items: {{SIZE}}{{UNIT}};',
				],
				'condition' => array(
					'popup_type' => 'sidebar',
				),
			]
		);
		$this->add_control(
			'popup_items',
			[
				'label' => esc_html__( 'Popup', 'thim-elementor-kit' ),
				'type' => Control_Nested_Repeater::CONTROL_TYPE,
				'fields' => $repeater->get_controls(),
				'item_actions' => [
					'add'       => false,
					'duplicate' => false,
					'remove'    => false,
					'sort'      => false,
				],
				'default' => [
					[
						'popup_type' => 'header',
					],
					[
						'popup_type' => 'sidebar',
					],
					[
						'popup_type' => 'content',
					],
					[
						'popup_type' => 'footer',
					],
				],
//				'frontend_available' => true,
				'title_field' => '<span style="text-transform: capitalize;">{{{ popup_type.replace("_", " ") }}}</span>',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings    = $this->get_settings_for_display();
		$popup_items = $settings['popup_items'];
		?>
 		<div id="popup-course" class="course-summary thim-ekit-course-items">
			<?php
			$this->print_child( 1 );
 			// group popup-header, popup-content, popup-footer
			echo '<div class="wrapper-popup-content-right">';
			foreach ( $popup_items as $index => $item ) {
				if ( $index == 1 ) {
					continue;
				}
				$this->print_child( $index );
			}
			echo '</div>';
			?>
		</div>
 		<?php
	}

	protected function content_template() {
		?>
			<# if ( settings['popup_items'] ) { #>
				<div id="popup-course" class="course-summary thim-ekit-course-items"></div>
			<# } #>
		<?php
	}

}
