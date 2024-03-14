<?php
namespace ElementorARFELEMENT\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class arf_element_shortcode extends Widget_Base {

	public function get_name() {
		return 'arf-element-shortcode';
	}


	public function get_title() {
		return __( 'ARForms Lite', 'arforms-form-builder' ) . '<style>
		.arf_element_icon{
			display: inline-block;
		    width: 35px;
		    height: 24px;
		    background-image: url(' . ARFLITEIMAGESURL . '/logo_el.svg);
		    background-repeat: no-repeat;
		    background-position: bottom;
		}
		.arf_frm_type_el .elementor-choices-label .elementor-screen-only{
			position: relative;
			top: 0;
		}
		.arf_click_type_el .elementor-choices-label .elementor-screen-only{
			position: relative;
			top: 0;
		}	
		.arf_show_cl_elbtn .elementor-choices-label .elementor-screen-only{
			position: relative;
			top: 0;
		}
		.arf_show_full_screen_popup .elementor-choices-label .elementor-screen-only{
			position: relative;
			top: 0;
		}
		</style>';
	}


	public function get_icon() {
		return 'arf_element_icon';
	}


	public function get_categories() {
		return array( 'basic' );
	}


	public function get_script_depends() {
		return array( 'elementor-arf-element' );
	}

	protected function _register_controls() {
		global $arfliteform, $arflitemainhelper;
		$where                                   = apply_filters( 'arfliteformsdropdowm', "is_template=0 AND (status is NULL OR status = '' OR status = 'published') AND arf_is_lite_form = 1", 'arf_select' );
		$forms                                   = $arfliteform->arflitegetAll( $where, ' ORDER BY name' );
		$arf_forms                               = array();
		$arf_forms['Please select a valid form'] = __( 'Please select form', 'arforms-form-builder' );
		if ( $forms ) {
			foreach ( $forms as $form ) {
				/* $arf_forms[ 'id=' . $form->id ] = $arflitemainhelper->arflitetruncate( $form->name, 33 ); */
				$arf_forms[ 'id=' . $form->id ] = $arflitemainhelper->arflitetruncate( html_entity_decode( stripslashes_deep( $form->name ) ), 33 );
			}
		}
		$this->start_controls_section(
			'arformslite_form',
			array(
				'label' => __( 'ARForms Lite Shortcode', 'arforms-form-builder' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => __( 'Title', 'arforms-form-builder' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);
		$this->add_control(
			'arf_select',
			array(
				'label'       => __( 'Forms :', 'arforms-form-builder' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'Please select a valid form',
				'options'     => $arf_forms,
				'label_block' => true,

			)
		);
		$this->end_controls_section();

	}


	protected function render() {
		$settings = $this->get_settings_for_display();

		echo '<h5 class="title">';
		echo esc_html( $settings['title'] );
		echo '</h5>';
		echo '<div class="arf_select">';
			$arf_shortcode = '';
		if ( isset( $settings['arf_select'] ) && $settings['arf_select'] == 'Please select a valid form' ) {
			echo esc_html($settings['arf_select']);
		} else {
			echo do_shortcode( '[ARForms ' . $settings['arf_select'] . ' is_elementor="yes"]' );
		}
		echo '</div>';

	}


	protected function content_template() {

	}
}
