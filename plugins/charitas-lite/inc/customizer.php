<?php




function charitas_lite_customize_register( $wp_customize ) {
	/*------------------------------------------------------------
		CPT Projects
	============================================================*/
	$wp_customize->add_section( 'charitas_lite_themes_cpt_settings' , array(
			'title'      => __( 'Custom Post Type Settings [Plugin]', 'charitas-lite' ),
			'description'=> '',
			'priority'   => 160,
		)
	);


	/*------------------------------------------------------------
		CPT Projects
	============================================================*/

	// Project URL Rewrite
	$wp_customize->add_setting( 'charitas_lite_projects_url_rewrite', array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control( 'charitas_lite_projects_url_rewrite', array(
			'label' 		=> __( 'Project URL Rewrite', 'charitas-lite'),
			'description'	=> __( 'URL Rewrite, ex: project', 'charitas-lite'),
			'section' 		=> 'charitas_lite_themes_cpt_settings',
		)
	);

	// Project Singular Name
	$wp_customize->add_setting( 'charitas_lite_projects_singular_name', array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control( 'charitas_lite_projects_singular_name', array(
			'label' 		=> __( 'Project Singular Name', 'charitas-lite'),
			'description'	=> __( 'Singular Name, ex: Project', 'charitas-lite'),
			'section' 		=> 'charitas_lite_themes_cpt_settings',
			'default' => 'Project',
		)
	);

	// Project Plural Name
	$wp_customize->add_setting( 'charitas_lite_projects_plural_name', array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control( 'charitas_lite_projects_plural_name', array(
			'label' 		=> __( 'Project Plural Name', 'charitas-lite'),
			'description'	=> __( 'Plural Name, ex: Projects', 'charitas-lite'),
			'section' 		=> 'charitas_lite_themes_cpt_settings',
			'default' => 'Projects',
		)
	);


	/*------------------------------------------------------------
		CPT Causes
	============================================================*/

	// Causes URL Rewrite
	$wp_customize->add_setting( 'charitas_lite_causes_url_rewrite', array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control( 'charitas_lite_causes_url_rewrite', array(
			'label' 		=> __( 'Cause URL Rewrite', 'charitas-lite'),
			'description'	=> __( 'URL Rewrite, ex: cause', 'charitas-lite'),
			'section' 		=> 'charitas_lite_themes_cpt_settings',
		)
	);

	// Cause Singular Name
	$wp_customize->add_setting( 'charitas_lite_causes_singular_name', array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control( 'charitas_lite_causes_singular_name', array(
			'label' 		=> __( 'Cause Singular Name', 'charitas-lite'),
			'description'	=> __( 'Singular Name, ex: Cause', 'charitas-lite'),
			'section' 		=> 'charitas_lite_themes_cpt_settings',
			'default' => 'Cause',
		)
	);

	// Cause Plural Name
	$wp_customize->add_setting( 'charitas_lite_causes_plural_name', array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control( 'charitas_lite_causes_plural_name', array(
			'label' 		=> __( 'Cause Plural Name', 'charitas-lite'),
			'description'	=> __( 'Plural Name, ex: Causes', 'charitas-lite'),
			'section' 		=> 'charitas_lite_themes_cpt_settings',
			'default' => 'Causes',
		)
	);

	/*------------------------------------------------------------
		CPT Staff
	============================================================*/

	// Staff URL Rewrite
	$wp_customize->add_setting( 'charitas_lite_staff_url_rewrite', array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control( 'charitas_lite_staff_url_rewrite', array(
			'label' 		=> __( 'Staff URL Rewrite', 'charitas-lite'),
			'description'	=> __( 'URL Rewrite, ex: staff', 'charitas-lite'),
			'section' 		=> 'charitas_lite_themes_cpt_settings',
		)
	);

	// Staff Singular Name
	$wp_customize->add_setting( 'charitas_lite_staff_singular_name', array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control( 'charitas_lite_staff_singular_name', array(
			'label' 		=> __( 'Staff Singular Name', 'charitas-lite'),
			'description'	=> __( 'Singular Name, ex: Staff', 'charitas-lite'),
			'section' 		=> 'charitas_lite_themes_cpt_settings',
			'default' => 'Staff',
		)
	);

	// Staff Plural Name
	$wp_customize->add_setting( 'charitas_lite_staff_plural_name', array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control( 'charitas_lite_staff_plural_name', array(
			'label' 		=> __( 'Staff Plural Name', 'charitas-lite'),
			'description'	=> __( 'Plural Name, ex: Staff', 'charitas-lite'),
			'section' 		=> 'charitas_lite_themes_cpt_settings',
			'default' => 'Staff',
		)
	);

}
add_action( 'customize_register', 'charitas_lite_customize_register', 11 );
?>
