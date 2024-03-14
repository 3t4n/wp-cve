<?php

function stm_lms_settings_certificates_section() {
	$certificate_settings_fields = array(
		'name'   => esc_html__( 'Certificates', 'masterstudy-lms-learning-management-system' ),
		'label'  => esc_html__( 'Certificates Settings', 'masterstudy-lms-learning-management-system' ),
		'icon'   => 'fas fa-certificate',
		'fields' => array(
			'certificate_threshold'       => array(
				'type'    => 'number',
				'pro'     => true,
				'pro_url' => admin_url( 'admin.php?page=stm-lms-go-pro' ),
				'label'   => esc_html__( 'Certificate threshold (%)', 'masterstudy-lms-learning-management-system' ),
				'value'   => 70,
			),
			'certificate_banner'          => array(
				'type'        => 'notification_message',
				'image'       => STM_LMS_URL . 'assets/addons/certtificate_builder.png',
				'description' => sprintf( '<h1>Certificate Builder</h1> <p>Сreate and design your own certificates to award them to students after the course completion.</p>' ),
				'buttons'     => array(
					array(
						'url'      => ( class_exists( 'STM_LMS_Certificate_Builder' ) ) ? esc_url( admin_url( 'admin.php?page=certificate_builder' ) ) : ( admin_url( 'admin-ajax.php?action=stm_lms_enable_addon&addon=certificate_builder' ) ),
						'text'     => esc_html__( 'GET STARTED', 'masterstudy-lms-learning-management-system' ),
						'data-url' => esc_url( admin_url( 'admin.php?page=certificate_builder' ) ),
						'class'    => ( class_exists( 'STM_LMS_Certificate_Builder' ) ) ? 'enabled' : 'disabled ',
					),
					array(
						'url'   => admin_url( 'admin.php?page=certificate_builder' ),
						'class' => 'data-url-certificate hide',
					),
				),
				'pro'         => true,
				'pro_url'     => admin_url( 'admin.php?page=stm-lms-go-pro' ),
			),
			'certificate_settings_title'  => array(
				'group'   => 'started',
				'type'    => 'notice',
				'pro'     => true,
				'pro_url' => admin_url( 'admin.php?page=stm-lms-go-pro' ),
				'label'   => esc_html__( 'Certificate Settings', 'masterstudy-lms-learning-management-system' ),
			),
			'certificate_image'           => array(
				'pro'   => true,
				'type'  => 'image',
				'label' => esc_html__( 'Certificate Image', 'masterstudy-lms-learning-management-system' ),
			),
			/*TITLE*/
			'certificate_title_notice'    => array(
				'type'  => 'notice',
				'label' => esc_html__( 'Certificate Title Settings', 'masterstudy-lms-learning-management-system' ),
			),
			'certificate_title'           => array(
				'pro'   => true,
				'type'  => 'text',
				'label' => esc_html__( 'Certificate Title', 'masterstudy-lms-learning-management-system' ),
			),
			'certificate_title_color'     => array(
				'type'  => 'color',
				'pro'   => true,
				'label' => esc_html__( 'Certificate title color', 'masterstudy-lms-learning-management-system' ),
			),
			'certificate_title_fsz'       => array(
				'type'  => 'number',
				'pro'   => true,
				'label' => esc_html__( 'Certificate title font size (px)', 'masterstudy-lms-learning-management-system' ),
				'value' => 60,
			),
			/*SUBTITLE*/
			'certificate_subtitle_notice' => array(
				'type'  => 'notice',
				'label' => esc_html__( 'Certificate Subtitle Settings', 'masterstudy-lms-learning-management-system' ),
			),
			'certificate_subtitle'        => array(
				'pro'   => true,
				'type'  => 'text',
				'label' => esc_html__( 'Certificate subtitle', 'masterstudy-lms-learning-management-system' ),
			),
			'certificate_subtitle_color'  => array(
				'type'  => 'color',
				'pro'   => true,
				'label' => esc_html__( 'Certificate subtitle color', 'masterstudy-lms-learning-management-system' ),
			),
			'certificate_subtitle_fsz'    => array(
				'type'  => 'number',
				'pro'   => true,
				'label' => esc_html__( 'Certificate subtitle font size (px)', 'masterstudy-lms-learning-management-system' ),
				'value' => 40,
			),
			/*TEXT*/
			'certificate_text_notice'     => array(
				'type'  => 'notice',
				'label' => esc_html__( 'Certificate Text Settings', 'masterstudy-lms-learning-management-system' ),
			),
			'certificate_text'            => array(
				'pro'   => true,
				'type'  => 'textarea',
				'label' => esc_html__( 'Certificate Text', 'masterstudy-lms-learning-management-system' ),
				'hint'  => esc_html__( 'Use these shortcodes to build certificates: Username - {username}; Course name - {course}; User First name - {user_first_name}; User Last name - {user_last_name}', 'masterstudy-lms-learning-management-system' ),
			),
			'certificate_text_color'      => array(
				'type'  => 'color',
				'pro'   => true,
				'label' => esc_html__( 'Certificate text color', 'masterstudy-lms-learning-management-system' ),
			),
			'certificate_text_fsz'        => array(
				'group' => 'ended',
				'type'  => 'number',
				'pro'   => true,
				'label' => esc_html__( 'Certificate text font size (px)', 'masterstudy-lms-learning-management-system' ),
				'value' => 17,
			),
		),
	);

	if ( ! STM_LMS_Helpers::is_pro() ) {
		$certificate_settings_fields = array(
			'name'   => esc_html__( 'Certificates', 'masterstudy-lms-learning-management-system' ),
			'label'  => esc_html__( 'Certificates Settings', 'masterstudy-lms-learning-management-system' ),
			'icon'   => 'fas fa-certificate',
			'fields' => array(
				'pro_banner' => array(
					'type'  => 'pro_banner',
					'label' => esc_html__( 'Certificate Builder', 'masterstudy-lms-learning-management-system' ),
					'img'   => STM_LMS_URL . 'assets/img/pro-features/certificate.png',
					'desc'  => esc_html__( 'Upgrade to Pro and craft certificates that truly grab attention and leave a lasting impression.', 'masterstudy-lms-learning-management-system' ),
					'hint'  => esc_html__( 'Unlock', 'masterstudy-lms-learning-management-system' ),
				),
			),
		);
	}

	return $certificate_settings_fields;
}
