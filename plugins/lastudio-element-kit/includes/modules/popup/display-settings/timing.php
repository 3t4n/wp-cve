<?php

namespace LaStudioKitThemeBuilder\Modules\Popup\DisplaySettings;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Timing extends Base {

	/**
	 * Get element name.
	 *
	 * Retrieve the element name.
	 *
	 * @since  2.4.0
	 * @access public
	 *
	 * @return string The name.
	 */
	public function get_name() {
		return 'popup_timing';
	}

	protected function register_controls() {
		$this->start_controls_section( 'timing' );

		$this->start_settings_group( 'page_views', esc_html__( 'Show after X page views', 'lastudio-kit' ) );

		$this->add_settings_group_control(
			'views',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => esc_html__( 'Page Views', 'lastudio-kit' ),
				'default' => 3,
				'min' => 1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'sessions', esc_html__( 'Show after X sessions', 'lastudio-kit' ) );

		$this->add_settings_group_control(
			'sessions',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => esc_html__( 'Sessions', 'lastudio-kit' ),
				'default' => 2,
				'min' => 1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'times', esc_html__( 'Show up to X times', 'lastudio-kit' ) );

		$this->add_settings_group_control(
			'times',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => esc_html__( 'Times', 'lastudio-kit' ),
				'default' => 3,
				'min' => 1,
			]
		);

        $this->add_settings_group_control(
            'period',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__( 'Per', 'lastudio-kit' ),
                'default' => '', // Backward Compatibility - Persisting is old default value.
                'options' => [
                    '' => esc_html__( 'Persisting', 'lastudio-kit' ),
                    'session' => esc_html__( 'Session', 'lastudio-kit' ),
                    'day' => esc_html__( 'Day', 'lastudio-kit' ),
                    'week' => esc_html__( 'Week', 'lastudio-kit' ),
                    'month' => esc_html__( 'Month', 'lastudio-kit' ),
                ],
            ]
        );

		$this->add_settings_group_control(
			'count',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Count', 'lastudio-kit' ),
				'options' => [
					'' => esc_html__( 'On Open', 'lastudio-kit' ),
					'close' => esc_html__( 'On Close', 'lastudio-kit' ),
				],
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'url', esc_html__( 'When arriving from specific URL', 'lastudio-kit' ) );

		$this->add_settings_group_control(
			'action',
			[
				'type' => Controls_Manager::SELECT,
				'default' => 'show',
				'options' => [
					'show' => esc_html__( 'Show', 'lastudio-kit' ),
					'hide' => esc_html__( 'Hide', 'lastudio-kit' ),
					'regex' => esc_html__( 'Regex', 'lastudio-kit' ),
				],
			]
		);

		$this->add_settings_group_control(
			'url',
			[
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'URL', 'lastudio-kit' ),
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'sources', esc_html__( 'Show when arriving from', 'lastudio-kit' ) );

		$this->add_settings_group_control(
			'sources',
			[
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [ 'search', 'external', 'internal' ],
				'options' => [
					'search' => esc_html__( 'Search Engines', 'lastudio-kit' ),
					'external' => esc_html__( 'External Links', 'lastudio-kit' ),
					'internal' => esc_html__( 'Internal Links', 'lastudio-kit' ),
				],
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'logged_in', esc_html__( 'Hide for logged in users', 'lastudio-kit' ) );

		$this->add_settings_group_control(
			'users',
			[
				'type' => Controls_Manager::SELECT,
				'default' => 'all',
				'options' => [
					'all' => esc_html__( 'All Users', 'lastudio-kit' ),
					'custom' => esc_html__( 'Custom', 'lastudio-kit' ),
				],
			]
		);

		global $wp_roles;

		$roles = array_map( function( $role ) {
			return $role['name'];
		}, $wp_roles->roles );

		$this->add_settings_group_control(
			'roles',
			[
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [],
				'options' => $roles,
				'select2options' => [
					'placeholder' => esc_html__( 'Select Roles', 'lastudio-kit' ),
				],
				'condition' => [
					'users' => 'custom',
				],
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'devices', esc_html__( 'Show on devices', 'lastudio-kit' ) );

		$this->add_settings_group_control(
			'devices',
			[
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [ 'desktop', 'tablet', 'mobile' ],
				'options' => [
					'desktop' => esc_html__( 'Desktop', 'lastudio-kit' ),
					'tablet' => esc_html__( 'Tablet', 'lastudio-kit' ),
					'mobile' => esc_html__( 'Mobile', 'lastudio-kit' ),
				],
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'browsers', esc_html__( 'Show on browsers', 'lastudio-kit' ) );

		$this->add_settings_group_control(
			'browsers',
			[
				'type' => Controls_Manager::SELECT,
				'default' => 'all',
				'options' => [
					'all' => esc_html__( 'All Browsers', 'lastudio-kit' ),
					'custom' => esc_html__( 'Custom', 'lastudio-kit' ),
				],
			]
		);

		$this->add_settings_group_control(
			'browsers_options',
			[
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [],
				'options' => [
					'ie' => esc_html__( 'Internet Explorer', 'lastudio-kit' ),
					'chrome' => esc_html__( 'Chrome', 'lastudio-kit' ),
					'edge' => esc_html__( 'Edge', 'lastudio-kit' ),
					'firefox' => esc_html__( 'Firefox', 'lastudio-kit' ),
					'safari' => esc_html__( 'Safari', 'lastudio-kit' ),
				],
				'condition' => [
					'browsers' => 'custom',
				],
			]
		);

		$this->end_settings_group();

		$this->end_controls_section();
	}
}
