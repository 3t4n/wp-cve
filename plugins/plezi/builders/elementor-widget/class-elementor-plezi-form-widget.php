<?php
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

class Elementor_Plezi_Form_Widget extends \Elementor\Widget_Base {
	public function get_name() {
		return 'plezi-form';
	}

	public function get_title() {
		return __( 'Plezi form', 'plezi-for-wordpress' );
	}

	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	public function get_custom_help_url() {
		$setup = admin_url( 'admin.php?page=plezi-for-wordpress-faq.php' );

		return $setup;
	}

	public function get_categories() {
		return array( 'plezi' );
	}

	public function get_keywords() {
		return array( 'plezi', 'tracking', 'form' );
	}

	protected function register_controls() {
		$options = array(
			'body' => array(
				'_wpnonce' => wp_create_nonce( 'wp_rest' ),
				'args' => 'sort_by=created_at&sort_dir=desc&page=1&per_page=20',
				'filters' => array('sort_by' => 'created_at', 'sort_dir' => 'desc', 'page' => '1', 'per_page' => '20' )
			),
			'headers' => array(
				'Cache-Control' => 'no-cache',
			),
			'cookies' => plz_get_user_cookies()
		);

		$result = wp_remote_post( get_rest_url( null, 'plz/v2/configuration/get-forms-list' ), $options );
		$forms = json_decode( wp_remote_retrieve_body( $result ) );
		$options = array( 'default' => __( 'Choose a Plezi form', 'plezi-for-wordpress' ) );

		if ( $forms && ! isset( $forms->error ) && isset( $forms->list ) ) :
			foreach ( $forms->list as $form ) :
				$options[ $form->id ] = $form->attributes->custom_title;
			endforeach;
		endif;

		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'plezi-for-wordpress' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'plezi_form',
			array(
				'label' => __( 'Plezi form', 'plezi-for-wordpress' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $options,
				'default' => 'default',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( $settings['plezi_form'] && ! empty( $settings['plezi_form'] ) && 'default' !== $settings['plezi_form'] ) :
			echo '<form id="plz-form-' . esc_attr( $settings['plezi_form'] ) . '"></form><script async src="https://brain.plezi.co/api/v1/web_forms/scripts?content_web_form_id=' . esc_attr( $settings['plezi_form'] ) . '"></script>';
		else :
			echo '<p>' . esc_html( __( 'Choose a Plezi form inside the sidebar.', 'plezi-for-wordpress' ) ) . '</p>';
		endif;
	}
}
