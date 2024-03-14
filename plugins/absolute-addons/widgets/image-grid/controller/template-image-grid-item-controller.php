<?php
	/*
	 *  ABSP Image Grid One Controller
	 */

	use Elementor\Controls_Manager;
	use Elementor\Repeater;

	if ( ! defined( 'ABSPATH' ) ) {
		header( 'Status: 403 Forbidden' );
		header( 'HTTP/1.1 403 Forbidden' );
		die();
	}

	$this->start_controls_section(
		'image-grid-section',
		array(
			'label' => __( 'Content', 'absolute-addons' ),
			'tab'   => Controls_Manager::TAB_CONTENT,

		) );

	$image = new Repeater();

	$image->add_control(
		'image',
		[
			'label'   => __( 'Choose Images', 'absolute-addons' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [
				absp_get_placeholder(),
			],
		]
	);

	$image->add_control(
		'image-alt',
		[
			'label' => __( 'Image Alt Text', 'absolute-addons' ),
			'type'  => Controls_Manager::TEXT,

		]
	);

	$image->add_control(
		'image-style',
		[
			'label'   => __( 'Image Style', 'absolute-addons' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'none',
			'options' => [
				'none'      => __( 'None', 'absolute-addons' ),
				'absp-wide' => __( 'Wide', 'absolute-addons' ),
				'absp-tall' => __( 'Tall', 'absolute-addons' ),
				'absp-big'  => __( 'Big', 'absolute-addons' ),
			],
		]
	);

	$this->add_control(
		'image-list',
		[
			'label'   => __( 'Repeater Image', 'absolute-addons' ),
			'type'    => Controls_Manager::REPEATER,
			'fields'  => $image->get_controls(),
			'default' => [
				[
					'image'       => absp_get_placeholder(),
					'image-alt'   => 'Image',
					'image-style' => 'absp-big',
				],
				[
					'image'       => absp_get_placeholder(),
					'image-alt'   => 'Image',
					'image-style' => 'none',
				],
				[
					'image'       => absp_get_placeholder(),
					'image-alt'   => 'Image',
					'image-style' => 'none',
				],
				[
					'image'       => absp_get_placeholder(),
					'image-alt'   => 'Image',
					'image-style' => 'none',
				],
				[
					'image'       => absp_get_placeholder(),
					'image-alt'   => 'Image',
					'image-style' => 'none',
				],
				[
					'image'       => absp_get_placeholder(),
					'image-alt'   => 'Image',
					'image-style' => 'none',
				],
			],
		]
	);

	$this->add_control(
		'enable-lightbox',
		[
			'label'   => __( 'Enable Lightbox', 'absolute-addons' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'yes' => __( 'Yes', 'absolute-addons' ),
				'no'  => __( 'No', 'absolute-addons' ),
			],
			'default' => 'yes',
		]
	);

	$this->end_controls_section();
