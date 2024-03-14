<?php

namespace DF_SCC\ElementorIntegration;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;


class SCC_Elementor_Widget extends Widget_Base {

	public function get_name() {
		return 'scc-calculator';
	}

	public function get_title() {
		return __( 'Stylish Cost Calculator' );
	}
	public function get_icon() {
		return 'eicon-product-price';
	}
	public function get_categories() {
		return array( 'general' );
	}
	protected function _register_controls() {
		$calculators = $this->get_calculator_list();
		$this->start_controls_section(
			'section_content',
			array(
				'label' => 'Settings',
			)
		);
		$this->add_control(
			'scc-calculator',
			array(
				'label'   => 'Stylish Cost Calculator',
				'type'    => \Elementor\Controls_Manager::HEADING,
				'default' => 'Display projects',
			)
		);
		$this->add_control(
			'form_id',
			array(
				'label'       => 'Select Calculator',
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => $calculators,
				'default'     => '0',
			)
		);
		$this->end_controls_section();
	}

	public function get_keywords() {

		return array(
			'calculator',
			'stylish cost calculator',
			'cost calculator',
			'calculator form',
		);
	}

	protected function render() {
		if ( Plugin::$instance->editor->is_edit_mode() ) {
			$this->render_edit_mode();
		} else {
			$this->render_frontend();
		}
	}

	protected function render_edit_mode() {
		$settings      = $this->get_settings_for_display();
		$calculator_id = $settings['form_id'];
		echo do_shortcode( "[scc_calculator type='text' idvalue=$calculator_id]" );
	}

	protected function render_frontend() {
		$settings      = $this->get_settings_for_display();
		$calculator_id = $settings['form_id'];
		echo do_shortcode( "[scc_calculator type='text' idvalue=$calculator_id]" );
	}

	protected function get_calculator_list() {
		require SCC_DIR . '/admin/controllers/formController.php';
		$form_controller = new \formController();
		$forms           = $form_controller->read();
		$calculators     = array();
		for ( $i = 0; $i < count( $forms ); $i++ ) {
			$calculators[ $forms[ $i ]->id ] = $forms[ $i ]->formname;
		}
		return $calculators;
	}

}
