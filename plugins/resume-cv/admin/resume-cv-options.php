<?php

function resumecv_cmb2_init_custom_field() {
	require_once ( dirname( __FILE__ ) . '/cmb2custom/skillbar-field-type/class-cmb2-render-skillbar-field.php');
	CMB2_Render_Skillbar_Field::init();
}
add_action( 'cmb2_init', 'resumecv_cmb2_init_custom_field' );


/** 
* Create Option Panels using CMB2
*/
function resumecv_register_options_page() {
	$main_options = new_cmb2_box( array(
		'id'           => 'resumecv_option_page',
		'title'        => esc_html__( 'Resume CV', 'resume-cv' ),
		'object_types' => array( 'options-page' ),
		'option_key'      => 'resumecv_options')
	);
	
	//
	$main_options->add_field( array(
		'name' => 'How To Use',
		'desc' =>  '<div>To use this plugin : <br />
* Create a <strong>Page</strong> and choose <strong>Template</strong> : <strong>Resume CV Template</strong> <br />
* In the admin area . Click Resume CV and do modification than save <br />
* Just go to page url to see the result </div>',
		'type' => 'title',
		'id'   => 'wiki_test_title'
	) );
	
	
	
	
	$main_options->add_field( array(
		'name'    => esc_html__( 'Choose Theme', 'resume-cv' ),
		'id'	=> 'theme_dir',
		'default'          => 'themes/shark',
		'type'             => 'select',
		'show_option_none' => false,
		'options' => resumecv_theme_get(),
	) );
	
	// === Profile Start === //
	$profile_options = new_cmb2_box( array(
		'id'           => 'resumecv_about_options_page',
		'title'        => esc_html__( 'Profile', 'resume-cv' ),
		'object_types' => array( 'options-page' ),
		'option_key'   => 'resumecv_profile_options',
		'parent_slug'  => 'resumecv_options',
	) );
	$profile_options->add_field( array(
		'name'    => esc_html__( 'Show Profile', 'resume-cv' ),
		'id'	=> 'show',
		'default'          => 'enable',
		'type'             => 'select',
		'show_option_none' => false,
		'options' => array(
			'enable' => esc_html__( 'Enable', 'resume-cv' ),
			'disable' => esc_html__( 'Disable', 'resume-cv' ),
		),
	) );
	$profile_options->add_field( array(
		'name'    => esc_html__( 'Your Name', 'resume-cv' ),
		'desc'    => esc_html__( 'Fill Your Name', 'resume-cv' ),
		'default' => 'John Doe',
		'id'      => 'name',
		'type'    => 'text'
	) );
	$profile_options->add_field( array(
		'name'    => esc_html__( 'Your Profession', 'resume-cv' ),
		'desc'    => esc_html__( 'Fill Your Profession', 'resume-cv' ),
		'default' => 'Web Designer',
		'id'      => 'profession',
		'type'    => 'text'
	) );
	$profile_options->add_field( array(
		'name'    => esc_html__( 'Profile photo', 'resume-cv' ),
		'desc'    => esc_html__( 'Upload an image or enter an URL of Your Photo. (Size 290x290)', 'resume-cv' ),
		'id'      => 'photo',
		'type'    => 'file',
		'options' => array(
			'url' => false, // Hide the text input for the url
		),
		'text'    => array(
			'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
		),
		'query_args' => array(
			'type' => array(
			 	'image/gif',
			 	'image/jpeg',
			 	'image/png',
			 ),
		),
		'preview_size' => 'large', // Image size to use when previewing in the admin.
	) );
	$profile_options->add_field( array(
		'name' => esc_html__( 'Profile Title', 'resume-cv' ),
		'desc'    => esc_html__( 'Example : Profile', 'resume-cv' ),
		'id'   => 'title',
		'type' => 'text'
	) );
	$profile_options->add_field( array(
		'name'    => esc_html__( 'Profile Description', 'resume-cv' ),
		'desc'    => esc_html__( 'Your Profile Description', 'resume-cv' ),
		'id'      => 'description',
		'type'    => 'wysiwyg',
		'options' => array(),
	) );
	$profile_options->add_field( array(
		'name' => esc_html__( 'Personal Details Title', 'resume-cv' ),
		'id'   => 'personal_title',
		'type' => 'text'
	) );
	$profile_group_id = $profile_options->add_field( array(
		'id'			=> 'personal_items',
		'type'			=> 'group',
		'repeatable'	=> true,
		'options'		=> array(
			'group_title'   => esc_html__( 'Personal Item {#}', 'resume-cv' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => esc_html__( 'Add Another Personal Item', 'resume-cv' ),
			'remove_button' => esc_html__( 'Remove Personal Item', 'resume-cv' ),
			'sortable'      => true, // beta
			'closed'     => true, // true to have the groups closed by default
		),
	) );
	$profile_options->add_group_field( $profile_group_id, array(
		'name' => esc_html__( 'Item Text', 'resume-cv' ),
		'id'   => 'text',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : Birth Date / Address ', 'resume-cv' ),
	) );
	$profile_options->add_group_field( $profile_group_id, array(
		'name' => esc_html__( 'Item Value', 'resume-cv' ),
		'id'   => 'value',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : 01 June 1990', 'resume-cv' ),
	) );
	// ---- Profile ends ---- //
	
	
	
	// === Contact start	=== //
	$contact_options = new_cmb2_box( array(
		'id'           => 'resumecv_contact_options_page',
		'title'        => esc_html__( 'Contact', 'resume-cv' ),
		'object_types' => array( 'options-page' ),
		'option_key'   => 'resumecv_contact_options',
		'parent_slug'  => 'resumecv_options',
	) );
	$contact_options->add_field( array(
		'name'    => esc_html__( 'Show Contact', 'resume-cv' ),
		'id'	=> 'show',
		'default'          => 'enable',
		'type'             => 'select',
		'show_option_none' => false,
		'options' => array(
			'enable' => esc_html__( 'Enable', 'resume-cv' ),
			'disable' => esc_html__( 'Disable', 'resume-cv' ),
		),
	) );
	$contact_options->add_field( array(
		'name'    => esc_html__( 'Title', 'resume-cv' ),
		'desc'    => esc_html__( 'Fill Contact Title. Example : Contact', 'resume-cv' ),
		'default' => 'Contact',
		'id'      => 'title',
		'type'    => 'text'
	) );
	$contact_group_id = $contact_options->add_field( array(
		'id'			=> 'contact_items',
		'type'			=> 'group',
		'repeatable'	=> true,
		'options'		=> array(
			'group_title'   => esc_html__( 'Contact {#}', 'resume-cv' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => esc_html__( 'Add Another Contact', 'resume-cv' ),
			'remove_button' => esc_html__( 'Remove Contaction', 'resume-cv' ),
			'sortable'      => true, // beta
			'closed'     => true, // true to have the groups closed by default
		),
	) );
	$contact_options->add_group_field( $contact_group_id, array(
		'name'    => esc_html__( 'Icon', 'resume-cv' ),
		'id'	=> 'icon',
		'default'          => 'fa fa-facebook',
		'type'             => 'select',
		'show_option_none' => false,
		'options' => array(
			'fa fa-home' => esc_html__( 'Address', 'resume-cv' ),
			'fa fa-phone' => esc_html__( 'Phone', 'resume-cv' ),
			'fa fa-mobile' => esc_html__( 'Mobile Phone', 'resume-cv' ),
			
			'fa fa-facebook' => esc_html__( 'Facebook', 'resume-cv' ),
			'fa fa-twitter' => esc_html__( 'Twitter', 'resume-cv' ),
			'fa fa-instagram' => esc_html__( 'Instagram', 'resume-cv' ),
			'fa fa-linkedin' => esc_html__( 'Linkedin', 'resume-cv' ),
			'fa fa-github' => esc_html__( 'Github', 'resume-cv' ),
			'fa fa-skype' => esc_html__( 'Skype', 'resume-cv' ),
			
			
		),
	) );
	$contact_options->add_group_field( $contact_group_id, array(
		'name' => esc_html__( 'Value', 'resume-cv' ),
		'id'   => 'value',
		'description' => esc_html__( 'Example : twitter.com/wpamanuke', 'resume-cv' ),
		'type' => 'text'
	) );
	$contact_options->add_group_field( $contact_group_id, array(
		'name' => esc_html__( 'Value URL', 'resume-cv' ),
		'id'   => 'value_url',
		'description' => esc_html__( 'Example : http://twitter.com/wpamanuke . Or just don\'t fill anything , if you don\'t want to link', 'resume-cv' ),
		'type' => 'text'
	) );
	// --- Contact ends  --- //
	
	
	
	// === Qualification start === //
	$qualification_options = new_cmb2_box( array(
		'id'           => 'resumecv_qualification_options_page',
		'title'        => esc_html__( 'Qualification', 'resume-cv' ),
		'object_types' => array( 'options-page' ),
		'option_key'   => 'resumecv_qualification_options',
		'parent_slug'  => 'resumecv_options',
	) );
	$qualification_options->add_field( array(
		'name'    => esc_html__( 'Show Qualification', 'resume-cv' ),
		'id'	=> 'show',
		'default'          => 'enable',
		'type'             => 'select',
		'show_option_none' => false,
		'options' => array(
			'enable' => esc_html__( 'Enable', 'resume-cv' ),
			'disable' => esc_html__( 'Disable', 'resume-cv' ),
		),
	) );
	$qualification_options->add_field( array(
		'name'    => esc_html__( 'Title', 'resume-cv' ),
		'id'      => 'title',
		'type'    => 'text',
		'desc'    => esc_html__( 'Example : Qualification', 'resume-cv' )
	) );
	$qualification_group_id = $qualification_options->add_field( array(
		'id'			=> 'qualification_items',
		'type'			=> 'group',
		'description'	=> esc_html__( 'Generates reusable form entries', 'resume-cv' ),
		'repeatable'	=> true,
		'options'		=> array(
			'group_title'   => esc_html__( 'Qualification {#}', 'resume-cv' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => esc_html__( 'Add Another Qualification', 'resume-cv' ),
			'remove_button' => esc_html__( 'Remove Qualification', 'resume-cv' ),
			'sortable'      => true, // beta
			'closed'     => true, // true to have the groups closed by default
		),
	) );
	// Id's for group's fields only need to be unique for the group. Prefix is not needed.
	$qualification_options->add_group_field( $qualification_group_id, array(
		'name' => esc_html__( 'Qualification Title', 'resume-cv' ),
		'id'   => 'title',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : Leadership', 'resume-cv' )
	) );
	$qualification_options->add_group_field( $qualification_group_id, array(
		'name' => esc_html__( 'Qualification List', 'resume-cv' ),
		'id'   => 'value',
		'type' => 'textarea',
		'repeatable' => true,
		'desc'    => esc_html__( 'Example : Lead 10 people and make 1 million USD a month for company', 'resume-cv' )
	) );
	// --- Qualification nds --- //
	
	
	// === Experience start	=== //
	$experience_options = new_cmb2_box( array(
		'id'           => 'resumecv_experience_options_page',
		'title'        => esc_html__( 'Experience', 'resume-cv' ),
		'object_types' => array( 'options-page' ),
		'option_key'   => 'resumecv_experience_options',
		'parent_slug'  => 'resumecv_options',
	) );
	$experience_options->add_field( array(
		'name'    => esc_html__( 'Show Experience', 'resume-cv' ),
		'desc'    => esc_html__( 'field description (optional)', 'resume-cv' ),
		'id'	=> 'show',
		'default'          => 'enable',
		'type'             => 'select',
		'show_option_none' => false,
		'options' => array(
			'enable' => esc_html__( 'Enable', 'resume-cv' ),
			'disable' => esc_html__( 'Disable', 'resume-cv' ),
		),
	) );
	$experience_options->add_field( array(
		'name' => esc_html__( 'Title', 'resume-cv' ),
		'id'   => 'title',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : Experience', 'resume-cv' )
	) );
	$experience_group_id = $experience_options->add_field( array(
		'id'			=> 'experience_items',
		'type'			=> 'group',
		'repeatable'	=> true,
		'options'		=> array(
			'group_title'   => esc_html__( 'Experience {#}', 'resume-cv' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => esc_html__( 'Add Another Experience', 'resume-cv' ),
			'remove_button' => esc_html__( 'Remove Experience' , 'resume-cv' ),
			'sortable'      => true, // beta
			'closed'     => true, // true to have the groups closed by default
		),
	) );
	$experience_options->add_group_field( $experience_group_id, array(
		'name' => esc_html__( 'Position', 'resume-cv' ),
		'id'   => 'position',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : Bussiness Analyst', 'resume-cv' )
	) );
	$experience_options->add_group_field( $experience_group_id, array(
		'name' => esc_html__( 'Company Name', 'resume-cv' ),
		'id'   => 'company_name',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : WPAmaNuke Inc', 'resume-cv' )
	) );
	$experience_options->add_group_field( $experience_group_id, array(
		'name' => esc_html__( 'Company Address', 'resume-cv' ),
		'id'   => 'company_address',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : USA', 'resume-cv' )
	) );
	$experience_options->add_group_field( $experience_group_id, array(
		'name' => esc_html__( 'Start Year', 'resume-cv' ),
		'id'   => 'start_year',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : 2014', 'resume-cv' )
	) );
	$experience_options->add_group_field( $experience_group_id, array(
		'name' => esc_html__( 'End Year', 'resume-cv' ),
		'id'   => 'end_year',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : Present', 'resume-cv' )
	) );
	$experience_options->add_group_field( $experience_group_id, array(
		'name' => esc_html__( 'Description', 'resume-cv' ),
		'id'   => 'position_description',
		'type' => 'textarea'
	) );
	$experience_options->add_group_field( $experience_group_id, array(
		'name' => esc_html__( 'Accomplishment Title', 'resume-cv' ),
		'id'   => 'accomplishment_title',
		'type' => 'text',		
		'desc'    => esc_html__( 'Example : Key Achievement', 'resume-cv' )
	) );
	$experience_options->add_group_field( $experience_group_id, array(
		'name' => esc_html__( 'Accomplishment List', 'resume-cv' ),
		'id'   => 'accomplishment_list',
		'type' => 'text',
		'repeatable' => true,
		'desc'    => esc_html__( 'Example : Make Company CRM using Ruby On Rails', 'resume-cv' )
	) );
	// --- Experience  ends  --- //
	
	
	
	// === Education start	=== //
	$education_options = new_cmb2_box( array(
		'id'           => 'resumecv_education_options_page',
		'title'        => esc_html__( 'Education', 'resume-cv' ),
		'object_types' => array( 'options-page' ),
		'option_key'   => 'resumecv_education_options',
		'parent_slug'  => 'resumecv_options',
	) );
	$education_options->add_field( array(
		'name'    => esc_html__( 'Show Education', 'resume-cv' ),
		'id'	=> 'show',
		'default'          => 'enable',
		'type'             => 'select',
		'show_option_none' => false,
		'options' => array(
			'enable' => esc_html__( 'Enable', 'resume-cv' ),
			'disable' => esc_html__( 'Disable', 'resume-cv' ),
		),
	) );
	$education_options->add_field( array(
		'name' => esc_html__( 'Title', 'resume-cv' ),
		'id'   => 'title',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : Education', 'resume-cv' )
	) );
	$education_group_id = $education_options->add_field( array(
		'id'			=> 'education_items',
		'type'			=> 'group',
		'repeatable'	=> true,
		'options'		=> array(
			'group_title'   => esc_html__( 'Education {#}', 'resume-cv' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => esc_html__( 'Add Another Education', 'resume-cv' ),
			'remove_button' => esc_html__( 'Remove Education' , 'resume-cv' ),
			'sortable'      => true, // beta
			'closed'     => true, // true to have the groups closed by default
		),
	) );
	$education_options->add_group_field( $education_group_id, array(
		'name' => esc_html__( 'Program', 'resume-cv' ),
		'id'   => 'program',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : Information Technology or Elementary School', 'resume-cv' )
	) );
	$education_options->add_group_field( $education_group_id, array(
		'name' => esc_html__( 'School Name', 'resume-cv' ),
		'id'   => 'school_name',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : MIT', 'resume-cv' )
	) );
	$education_options->add_group_field( $education_group_id, array(
		'name' => esc_html__( 'School Address', 'resume-cv' ),
		'id'   => 'school_address',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : USA', 'resume-cv' )
	) );
	$education_options->add_group_field( $education_group_id, array(
		'name' => esc_html__( 'Start Year', 'resume-cv' ),
		'id'   => 'start_year',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : 2014', 'resume-cv' )
	) );
	$education_options->add_group_field( $education_group_id, array(
		'name' => esc_html__( 'End Year', 'resume-cv' ),
		'id'   => 'end_year',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : Present', 'resume-cv' )
	) );
	
	$education_options->add_group_field( $education_group_id, array(
		'name' => esc_html__( 'Description', 'resume-cv' ),
		'id'   => 'position_description',
		'type' => 'textarea',
		'desc'    => esc_html__( 'Example : Learn OOP and Functional Programming using C++', 'resume-cv' )
	) );
	
	// --- Education ends  --- //
	
	
	
	// === Skill Bar start	=== //
	$skillbar_options = new_cmb2_box( array(
		'id'           => 'resumecv_skillbar_options_page',
		'title'        => esc_html__( 'Skill Bar', 'resume-cv' ),
		'object_types' => array( 'options-page' ),
		'option_key'   => 'resumecv_skillbar_options',
		'parent_slug'  => 'resumecv_options',
	) );
	$skillbar_options->add_field( array(
		'name'    => esc_html__( 'Show Skill', 'resume-cv' ),
		'id'	=> 'show',
		'default'          => 'enable',
		'type'             => 'select',
		'show_option_none' => false,
		'options' => array(
			'enable' => esc_html__( 'Enable', 'resume-cv' ),
			'disable' => esc_html__( 'Disable', 'resume-cv' ),
		),
	) );
	$skillbar_group_id = $skillbar_options->add_field( array(
		'id'			=> 'skillbar_items',
		'type'			=> 'group',
		'repeatable'	=> true,
		'options'		=> array(
			'group_title'   => esc_html__( 'Skill Bar {#}', 'resume-cv' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => esc_html__( 'Add Another Skill Bar', 'resume-cv' ),
			'remove_button' => esc_html__( 'Remove Skill Bar' , 'resume-cv' ),
			'sortable'      => true, // beta
			'closed'     => true, // true to have the groups closed by default
		),
	) );
	$skillbar_options->add_group_field( $skillbar_group_id, array(
		'name' => esc_html__( 'Title', 'resume-cv' ),
		'id'   => 'title',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : Software Skills', 'resume-cv' )
	) );
	$skillbar_options->add_group_field( $skillbar_group_id, array(
		'name' => esc_html__( 'Skill List', 'resume-cv' ),
		'id'   => 'skillbar',
		'type' =>  'skillbar',
		'repeatable'=> true
	) );
	// --- Skill Bar ends  --- //
	
	
	
	// === Hobbies start	=== //
	$hobbies_options = new_cmb2_box( array(
		'id'           => 'resumecv_hobbies_options_page',
		'title'        => esc_html__( 'Hobbies', 'resume-cv' ),
		'object_types' => array( 'options-page' ),
		'option_key'   => 'resumecv_hobby_options',
		'parent_slug'  => 'resumecv_options',
	) );
	$hobbies_options->add_field( array(
		'name'    => esc_html__( 'Show Hobbies', 'resume-cv' ),
		'id'	=> 'show',
		'default'          => 'enable',
		'type'             => 'select',
		'show_option_none' => false,
		'options' => array(
			'enable' => esc_html__( 'Enable', 'resume-cv' ),
			'disable' => esc_html__( 'Disable', 'resume-cv' ),
		),
	) );
	$hobbies_options->add_field( array(
		'name' => esc_html__( 'Title', 'resume-cv' ),
		'id'   => 'title',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : Hobby', 'resume-cv' )
	) );
	$hobbies_group_id = $hobbies_options->add_field( array(
		'id'			=> 'hobby_items',
		'type'			=> 'group',
		'repeatable'	=> true,
		'options'		=> array(
			'group_title'   => esc_html__( 'Hobby {#}', 'resume-cv' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => esc_html__( 'Add Another Hobby', 'resume-cv' ),
			'remove_button' => esc_html__( 'Remove Hobby', 'resume-cv' ),
			'sortable'      => true, // beta
			'closed'     => true, // true to have the groups closed by default
		),
	) );
	$hobbies_options->add_group_field( $hobbies_group_id, array(
		'name'    => esc_html__( 'Icon', 'resume-cv' ),
		'id'	=> 'icon',
		'default'          => 'running.svg',
		'type'             => 'select',
		'show_option_none' => false,
		'options' => array(
			'archer.svg' => esc_html__( 'archer', 'resume-cv' ),
			'basket.svg' => esc_html__( 'basket', 'resume-cv' ),
			'bike.svg' => esc_html__( 'bike', 'resume-cv' ),
			'dive.svg' => esc_html__( 'dive', 'resume-cv' ),
			'fishing.svg' => esc_html__( 'fishing', 'resume-cv' ),
			'golf.svg' => esc_html__( 'golf', 'resume-cv' ),
			'horse.svg' => esc_html__( 'horse', 'resume-cv' ),
			'karate.svg' => esc_html__( 'karate', 'resume-cv' ),
			'piano.svg' => esc_html__( 'piano', 'resume-cv' ),
			'programming.svg' => esc_html__( 'programming', 'resume-cv' ),
			'reading.svg' => esc_html__( 'reading', 'resume-cv' ),
			'running.svg' => esc_html__( 'running', 'resume-cv' ),
			'squash.svg' => esc_html__( 'squash', 'resume-cv' ),
			'swim.svg' => esc_html__( 'swim', 'resume-cv' ),
			'tennis.svg' => esc_html__( 'tennis', 'resume-cv' ),
			'yoga.svg' => esc_html__( 'yoga', 'resume-cv' )
		),
	) );
	$hobbies_options->add_group_field( $hobbies_group_id,  array(
		'name'    => esc_html__( 'Hobby Image', 'resume-cv' ),
		'desc'    => esc_html__( 'Upload an image or enter an URL of Your Hobby (If you did not want to use icon). (Size 200x200)', 'resume-cv' ),
		'id'      => 'image',
		'type'    => 'file',
		'options' => array(
			'url' => false, // Hide the text input for the url
		),
		'text'    => array(
			'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
		),
		'query_args' => array(
			'type' => array(
			 	'image/gif',
			 	'image/jpeg',
			 	'image/png',
			 ),
		),
		'preview_size' => 'thumbnail', // Image size to use when previewing in the admin.
	) );	
	$hobbies_options->add_group_field( $hobbies_group_id, array(
		'name' => esc_html__( 'Hobby Title', 'resume-cv' ),
		'id'   => 'title',
		'type' => 'text'
	) );
	// --- Hobbies ends  --- //
	
	
	
	// === Reference start	=== //
	$reference_options = new_cmb2_box( array(
		'id'           => 'resumecv_reference_options_page',
		'title'        => esc_html__( 'Reference ', 'resume-cv' ),
		'object_types' => array( 'options-page' ),
		'option_key'   => 'resumecv_reference_options',
		'parent_slug'  => 'resumecv_options',
	) );
	$reference_options->add_field( array(
		'name'    => esc_html__( 'Show Reference', 'resume-cv' ),
		'id'	=> 'show',
		'default'          => 'enable',
		'type'             => 'select',
		'show_option_none' => false,
		'options' => array(
			'enable' => esc_html__( 'Enable', 'resume-cv' ),
			'disable' => esc_html__( 'Disable', 'resume-cv' ),
		),
	) );
	$reference_options->add_field( array(
		'name' => esc_html__( 'Title', 'resume-cv' ),
		'id'   => 'title',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : Reference', 'resume-cv' )
	) );
	$reference_group_id = $reference_options->add_field( array(
		'id'			=> 'reference_items',
		'type'			=> 'group',
		'repeatable'	=> true,
		'options'		=> array(
			'group_title'   => esc_html__( 'Reference {#}', 'resume-cv' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => esc_html__( 'Add Another Reference', 'resume-cv' ),
			'remove_button' => esc_html__( 'Remove Reference', 'resume-cv' ),
			'sortable'      => true, // beta
			'closed'     => true, // true to have the groups closed by default
		),
	) );
	
	$reference_options->add_group_field( $reference_group_id, array(
		'name' => esc_html__( 'Name', 'resume-cv' ),
		'id'   => 'name',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : John Doe', 'resume-cv' )
	) );
	$reference_options->add_group_field( $reference_group_id, array(
		'name' => esc_html__( 'Position', 'resume-cv' ),
		'id'   => 'position',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : IT Manager', 'resume-cv' )
	) );
	$reference_options->add_group_field( $reference_group_id, array(
		'name' => esc_html__( 'Company Name', 'resume-cv' ),
		'id'   => 'company_name',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : WPAmaNuke Inc', 'resume-cv' )
	) );
	$reference_options->add_group_field( $reference_group_id, array(
		'name' => esc_html__( 'Phone', 'resume-cv' ),
		'id'   => 'phone',
		'type' => 'text'
	) );
	
	$reference_options->add_group_field( $reference_group_id, array(
		'name' => esc_html__( 'Email', 'resume-cv' ),
		'id'   => 'email',
		'type' => 'text'
	) );
	$reference_options->add_group_field( $reference_group_id, array(
		'name' => esc_html__( 'Description', 'resume-cv' ),
		'id'   => 'description',
		'type' => 'textarea'
	) );
	// --- Reference ends  --- //
	
	
	// === Award start	=== //
	$award_options = new_cmb2_box( array(
		'id'           => 'resumecv_award_options_page',
		'title'        => esc_html__( 'Award', 'resume-cv' ),
		'object_types' => array( 'options-page' ),
		'option_key'   => 'resumecv_award_options',
		'parent_slug'  => 'resumecv_options',
	) );
	$award_options->add_field( array(
		'name'    => esc_html__( 'Show Award', 'resume-cv' ),
		'id'	=> 'show',
		'default'          => 'enable',
		'type'             => 'select',
		'show_option_none' => false,
		'options' => array(
			'enable' => esc_html__( 'Enable', 'resume-cv' ),
			'disable' => esc_html__( 'Disable', 'resume-cv' ),
		),
	) );
	$award_options->add_field( array(
		'name' => esc_html__( 'Title', 'resume-cv' ),
		'id'   => 'title',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : Award', 'resume-cv' )
	) );
	$award_group_id = $award_options->add_field( array(
		'id'			=> 'award_items',
		'type'			=> 'group',
		'repeatable'	=> true,
		'options'		=> array(
			'group_title'   => esc_html__( 'Award {#}', 'resume-cv' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => esc_html__( 'Add Another Award', 'resume-cv' ),
			'remove_button' => esc_html__( 'Remove Award', 'resume-cv' ),
			'sortable'      => true, // beta
			'closed'     => true, // true to have the groups closed by default
		),
	) );
	
	$award_options->add_group_field( $award_group_id, array(
		'name' => esc_html__( 'Title', 'resume-cv' ),
		'id'   => 'title',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : Marathon Finisher', 'resume-cv' )
	) );
	$award_options->add_group_field( $award_group_id, array(
		'name' => esc_html__( 'Event', 'resume-cv' ),
		'id'   => 'event',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : Marathon Berlin 2018', 'resume-cv' )
	) );
	$award_options->add_group_field( $award_group_id, array(
		'name' => esc_html__( 'Description', 'resume-cv' ),
		'id'   => 'description',
		'type' => 'textarea'
	) );
	
	// --- Award ends  --- //
	
	// === Service start	=== //
	$service_options = new_cmb2_box( array(
		'id'           => 'resumecv_service_options_page',
		'title'        => esc_html__( 'Service', 'resume-cv' ),
		'object_types' => array( 'options-page' ),
		'option_key'   => 'resumecv_service_options',
		'parent_slug'  => 'resumecv_options',
	) );
	$service_options->add_field( array(
		'name'    => esc_html__( 'Show Service', 'resume-cv' ),
		'id'	=> 'show',
		'default'          => 'enable',
		'type'             => 'select',
		'show_option_none' => false,
		'options' => array(
			'enable' => esc_html__( 'Enable', 'resume-cv' ),
			'disable' => esc_html__( 'Disable', 'resume-cv' ),
		),
	) );
	$service_options->add_field( array(
		'name' => esc_html__( 'Title', 'resume-cv' ),
		'id'   => 'title',
		'type' => 'text',
		'desc'    => esc_html__( 'Example : Services', 'resume-cv' )
	) );
	$service_group_id = $service_options->add_field( array(
		'id'			=> 'service_items',
		'type'			=> 'group',
		'repeatable'	=> true,
		'options'		=> array(
			'group_title'   => esc_html__( 'Service {#}', 'resume-cv' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => esc_html__( 'Add Another Service', 'resume-cv' ),
			'remove_button' => esc_html__( 'Remove Service', 'resume-cv' ),
			'sortable'      => true, // beta
			'closed'     => true, // true to have the groups closed by default
		),
	) );
	$service_options->add_group_field( $service_group_id, array(
		'name'    => esc_html__( 'Icon', 'resume-cv' ),
		'id'	=> 'icon',
		'default'          => 'fa fa-camera-retro',
		'type'             => 'select',
		'show_option_none' => false,
		'options' => array(
			'fa fa-birthday-cake' => esc_html__( 'Birthday', 'resume-cv' ),
			'fa fa-book' => esc_html__( 'Book', 'resume-cv' ),
			'fa fa-cab' => esc_html__( 'Cab', 'resume-cv' ),
			'fa fa-bus' => esc_html__( 'Bus', 'resume-cv' ),
			'fa fa-camera-retro' => esc_html__( 'Camera', 'resume-cv' ),
			'fa fa-calendar' => esc_html__( 'Calendar', 'resume-cv' ),
			'fa fa-cloud' => esc_html__( 'Cloud', 'resume-cv' ),
			'fa fa-coffee' => esc_html__( 'Coffee', 'resume-cv' ),
			'fa fa-cog' => esc_html__( 'Cog', 'resume-cv' ),
			'fa fa-database' => esc_html__( 'Database', 'resume-cv' ),
			'fa fa-diamond' => esc_html__( 'Diamond', 'resume-cv' ),
			'fa fa-drivers-license' => esc_html__( 'Driver License', 'resume-cv' ),
			'fa fa-gear' => esc_html__( 'Gear', 'resume-cv' ),
			'fa fa-gears' => esc_html__( 'Gears', 'resume-cv' ),			
			'fa fa-graduation-cap' => esc_html__( 'Graduation Cap', 'resume-cv' ),
			'fa fa-handshake-o' => esc_html__( 'Handshake', 'resume-cv' ),
			'fa fa-institution' => esc_html__( 'Institution', 'resume-cv' ),
			'fa fa-legal' => esc_html__( 'Legal', 'resume-cv' ),
			'fa fa-life-bouy' => esc_html__( 'Life Buoy', 'resume-cv' ),
			'fa fa-line-chart' => esc_html__( 'Line Chart', 'resume-cv' ),
			'fa fa-microphone' => esc_html__( 'Microphone', 'resume-cv' ),			
			'fa fa-object-group' => esc_html__( 'Object Group', 'resume-cv' ),			
			'fa fa-paint-brush' => esc_html__( 'Paint Brush', 'resume-cv' ),			
			'fa fa-truck' => esc_html__( 'Truck', 'resume-cv' ),
			'fa fa-space-shuttle' => esc_html__( 'Space Shuttle', 'resume-cv' ),
			'fa fa-wifi' => esc_html__( 'Wifi', 'resume-cv' ),
			'fa fa-wrench' => esc_html__( 'Wrench', 'resume-cv' )
		),
	) );
	$service_options->add_group_field( $service_group_id, array(
		'name' => esc_html__( 'Service Title', 'resume-cv' ),
		'id'   => 'title',
		'type' => 'text'
	) );
	$service_options->add_group_field( $service_group_id, array(
		'name' => esc_html__( 'Description', 'resume-cv' ),
		'id'   => 'description',
		'type' => 'textarea'
	) );
	// --- Service ends  --- //
	
	
	
	
}
add_action( 'cmb2_admin_init' , 'resumecv_register_options_page' );