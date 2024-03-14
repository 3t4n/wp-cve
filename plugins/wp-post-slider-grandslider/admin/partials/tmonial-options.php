<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

//
// Metabox of the PAGE.
// Set a unique slug-like ID.
//
$prefix_page_opts = '_wppsgs_tmonial_options';

//
// Create a metabox.
//
WPPSGS::createMetabox(
	$prefix_page_opts,
	array(
		'title'     => '<img src="https://ps.w.org/wp-post-slider-grandslider/assets/icon-128x128.png">GrandSlider',
		'post_type' => 'wppsgs_tmonial',
		'class'     => 'wppsgs--shortcode-postbox',
	)
);

//
// Create a section.
//
WPPSGS::createSection(
	$prefix_page_opts,
	array(
		'fields' => array(

			array(
				'id'      => 'wppsgs-tmonial-slide-number',
				'type'    => 'button_set',
				'title'   => 'Slide Number',
				'options' => array(
					'two'   => 'Two Dimensional',
					'three' => 'Three Dimensional',
				),
				'default' => 'two',
			),
			array(
				'id'      => 'wppsgs-tmonial-slide-speed',
				'type'    => 'number',
				'title'   => 'Slide Speed :',
				'default' => 3000,
			),
			//
			// Group.
			//
			array(
				'id'           => 'wppsgs-testimonial-group',
				'type'         => 'group',
				'button_title' => '+ Add Testimonial',
				'fields'       => array(

					array(
						'id'    => 'wppsgs-tmonial-client-name',
						'type'  => 'text',
						'title' => 'Client Name',
					),
					array(
						'id'    => 'wppsgs-tmonial-client-photo',
						'type'  => 'media',
						'title' => 'Client Photo',
						'url'   => false,
					),
					array(
						'id'    => 'wppsgs-tmonial-client-desig',
						'type'  => 'text',
						'title' => 'Client Designation',
					),
					array(
						'id'    => 'wppsgs-tmonial-client-say',
						'type'  => 'wp_editor',
						'title' => 'Client Says',
					),
				),
				'default'      => array(
					array(
						'wppsgs-tmonial-client-name'  => 'David M. Maddox',
						'wppsgs-tmonial-client-desig' => 'WordPress Developer',
						'wppsgs-tmonial-client-say'   => 'OMG! I cannot believe that I have got a brand new landing page after getting appmax. It was super easy to edit and publish.I have got a brand new landing page.',
						'wppsgs-tmonial-client-photo' => array(
							'url'       => WPPSGS_URL . 'admin/img/person.png',
							'thumbnail' => WPPSGS_URL . 'admin/img/person.png',
						),
					),
					array(
						'wppsgs-tmonial-client-name'  => 'John T. Walker',
						'wppsgs-tmonial-client-desig' => 'UI/UX Expert',
						'wppsgs-tmonial-client-say'   => 'OMG! I cannot believe that I have got a brand new landing page after getting appmax. It was super easy to edit and publish.I have got a brand new landing page.',
						'wppsgs-tmonial-client-photo' => array(
							'url'       => WPPSGS_URL . 'admin/img/person.png',
							'thumbnail' => WPPSGS_URL . 'admin/img/person.png',
						),
					),
				),
			),

		),
	)
);

//
// Metabox of the PAGE.
// Set a unique slug-like ID.
//
$wppsgs_page_opts2 = '_wppsgs_tmonial_shortcode';

//
// Create a metabox
//
WPPSGS::createMetabox(
	$wppsgs_page_opts2,
	array(
		'title'     => 'Custom Page Options',
		'post_type' => 'wppsgs_tmonial',
		'class'     => 'wppsgs-postbox-shortcode-display',
		'context'   => 'side',
	)
);
WPPSGS::createSection(
	$wppsgs_page_opts2,
	array(
		'title'  => '',
		'fields' => array(
			array(
				'type'  => 'shortcode_tmonial',
				'class' => 'wppsgs--shortcode-field',
			),
		),
	)
);
