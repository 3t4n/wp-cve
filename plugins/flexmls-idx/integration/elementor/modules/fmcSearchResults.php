<?php
class EL_fmcSearchResults extends EL_FMC_shortcode{

    protected function integrationWithElementor(){
        $this->settings_fmc = [
            'title',
            'link',
            'source',
            'property_type',
            'agent',
            'property_sub_type',
            'location',
            'display',
            'days',
            'default_view',
            'sort',
            'listings_per_page'
        ];
    }

    protected function render_hook($settings){
        $return = $settings + ['integration' => 'elementor'];
        return $return;
    }

    protected function setControlls() {
        extract($this->module_info['vars']);

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'plugin-name' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'description' => $special_neighborhood_title_ability,
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __( 'Saved Search', 'plugin-name' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->modify_array($api_links, 'LinkId', 'Name'),
                'description' => 'flexmls Saved Search to apply',
                'default' => ''
            ]
        );

        $this->add_control(
            'source',
            [
                'label' => __( 'Filter by', 'plugin-name' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $source_options,
                'description' => 'Which listings to display',
                'default' => 'location',
            ]
        );

        $this->add_control(
            'property_type',
            [
                'label' => __( 'Property Type', 'plugin-name' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $api_property_type_options,
                'default' => '',
                'condition' => [
					'source' => 'location',
				],
            ]
        );
        if(isset($agent)){
            $this->add_control(
                'agent',
                [
                    'label' => __( 'Agent', 'plugin-name' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => $agent,
                    'condition' => [
                        'source' => 'agent',
                    ],
                ]
            );
        };

        $this->add_control(
            'property_sub_type',
            [
                'label' => __( 'Property Sub Type', 'plugin-name' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => 'All Sub Types'
                ],
                'disable' => true,
            ]
        );
        $this->add_control(
            'location',
            [
                'label' => __( 'Location', 'plugin-name' ),
                'type' => 'location_control',
                'multiple' => true,
                'field_slug' => $portal_slug,
                'condition' => [
                    'source' => 'location'
                ]
            ]
        );
        $this->add_control(
            'display',
            [
                'label' => __( 'Display', 'plugin-name' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $display_options,
                'default' => 'all'
            ]
        );
        $this->add_control(
            'days',
            [
                'label' => __( 'Number of Days', 'plugin-name' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $display_day_options,
                'condition' => [
					'display!' => 'all',
				],
            ]
        );
        $this->add_control(
            'default_view',
            [
                'label' => __( 'Default view', 'plugin-name' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'list' => 'List view',
                    'map' => 'Map view'
                ],
                'default' => 'list',
            ]
        );
        $this->add_control(
            'sort',
            [
                'label' => __( 'Sort by', 'plugin-name' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $sort_options,
                'default' => 'recently_changed'
            ]
        );
        $this->add_control(
            'listings_per_page',
            [
                'label' => __( 'Listings per page', 'plugin-name' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $listings_per_page_options,
                'default' => '10'
            ]
        );
    }
  };
