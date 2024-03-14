<?php
    class EL_fmcSearch extends EL_FMC_shortcode{

        protected function integrationWithElementor(){
            $this->settings_fmc = [
                'title',
                'link',
                'buttontext',
                'detailed_search',
                'destination',
                'user_sorting',
                'location_search',
                //'allow_sold_searching',
                //'allow_pending_searching',
                'property_type_enabled',
                'property_type',
                'std_fields',
                'theme',
                'default_view',
                'orientation',
                'width_',
                'title_font',
                'field_font',
                'border_style',
                'widget_drop_shadow',
                'background_color_',
                'title_text_color',
                'field_text_color',
                'detailed_search_text_color',
                'submit_button_shine',
                'submit_button_background',
                'submit_button_text_color',
                'listings_per_page'
            ];
        }

        protected $std_fields;

        protected function render_hook($settings){
            $props = $settings;
            $swichers = [
                'detailed_search',
                'user_sorting',
                'location_search',
                'allow_sold_searching',
                'allow_pending_searching',
                'property_type_enabled',
                'widget_drop_shadow',
            ];

            foreach ($swichers as $value) {
                if($props[$value]=='yes'){
                    $props[$value] = 'on';
                } else {
                    $props[$value] = 'off';
                }
            }

            $props['width'] = $props['width_']['size'];
            $props['background_color'] = $props['background_color_'];
            unset($props['width_']);
            unset($props['background_color_']);

            $return = $props + ['integration' => 'elementor'];
            return $return;
        }

        protected function setControlls() {
            extract($this->module_info['vars']);

            $on_off_options = [
                'label_on' => __( 'yes', 'plugin-name' ),
  'label_off' => __( 'no', 'plugin-name' ),
            ];

            $this->std_fields = $this->modify_array($available_fields);

            $this->add_control(
                'title',
                array(
                    'label'           => __( 'Title', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::TEXT,
                    'input_type' => 'text',
                )
            );

            if ( ! empty($idx_links) ) {
                    $idx_links_use = $this->modify_array($idx_links, 'LinkId', 'Name');
                    $idx_links_default = $idx_links[0]['LinkId'];
            } else {
                    $idx_links_use = ['default' => 'No Links in Flexmls® account'];
                    $idx_links_default = $idx_links_use['default'];
            }

            $this->add_control(
                'link',
                array(
                    'label'           => __( 'IDX Link', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SELECT2,
                    'options'         => $idx_links_use,
                    'default'          => $idx_links_default,
                    'description'     => __( 'Link used when search is executed', 'plugin-name' ),
                )
            );

            $this->add_control(
              'buttontext',
                array(
                    'label'           => __( 'Submit Button Text', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::TEXT,
                    'input_type' => 'text',
                    'description'     => __( '(ex. "Search for Homes")', 'plugin-name' ),
                )
            );

            $this->add_control(
              'detailed_search',
              array_merge(
                  [
                    'label'       => __( 'Detailed Search', 'plugin-name' ),
                    'type'        => \Elementor\Controls_Manager::SWITCHER,
                    'default'     => 'yes',
                    'return_value' => 'yes',
                  ],
                  $on_off_options
              )
            );

            $this->add_control(
              'destination',
                array(
                    'label'           => __( 'Send users to', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SELECT,
                    'options'         => $this->modify_array($destination_options),
                    'default' => 'local'
                )
            );

            $this->add_control(
              'user_sorting',
              array_merge(
                [
                    'label'       => __( 'User Sorting', 'plugin-name' ),
                    'type'        => \Elementor\Controls_Manager::SWITCHER,
                    'default'     => 'yes',
                    'return_value' => 'yes',
                ],
                $on_off_options
              )
            );

            $this->add_control(
              'location_search',
              array_merge(
                  [
                      'label'       => __( 'Location Search', 'plugin-name' ),
                      'type'        => \Elementor\Controls_Manager::SWITCHER,
                      'default'     => 'yes',
                      'return_value' => 'yes',
                  ],
                  $on_off_options
              )
            );

            /* $this->add_control(
              'mls_allows_sold_searching',
                array(
                    'label'           => __( 'Title', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::HIDDEN,
                    'default' => $mls_allows_sold_searching
                )
            );

            $this->add_control(
              'allow_sold_searching',
              array_merge(
                [
                    'label'       => __( 'Allow Sold Searching', 'plugin-name' ),
                    'type'        => \Elementor\Controls_Manager::SWITCHER,
                    'condition'     => array(
                        'mls_allows_sold_searching' => true
                    ),
                    'default'     => 'yes',
                    'return_value' => 'yes',
                ],
                $on_off_options
              )
            );

            $this->add_control(
              'allow_pending_searching',
              array_merge(
                  [
                      'label'       => __( 'Allow Pending Searching', 'plugin-name' ),
                      'type'        => \Elementor\Controls_Manager::SWITCHER,
                      'default'     => 'yes',
                      'return_value' => 'yes',
                      'condition'     => array(
                          'mls_allows_sold_searching' => true
                        ),
                  ],
                  $on_off_options
              )
            ); */

            $this->add_control(
              'property_type_enabled',
              array_merge(
                  [
                      'label'       => __( 'Property Type', 'plugin-name' ),
                      'type'            => \Elementor\Controls_Manager::SWITCHER,
                      'default'     => 'yes',
                      'return_value' => 'yes',
                  ],
                  $on_off_options
              )
            );

           $this->add_control(
              'property_type',
                array(
                    'label'           => __( 'Property Types', 'plugin-name' ),
                    'type'            => 'sortable_list_control',
                    'fields_types' => $property_types,
                    'button_name' => 'Add Type',
                    'condition'     => array(
                        'property_type_enabled' => 'yes'
                    ),
                )
            );

            $this->add_control(
              'std_fields',
                array(
                    'label'           => __( 'Fields', 'plugin-name' ),
                    'type'            => 'sortable_list_control',
                    'fields_types' => $available_fields,
                    'button_name' => 'Add Field'
                )
            );

            $this->add_control(
              'theme',
                array(
                    'label'           => __( 'Select a Theme', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SELECT,
                    'options'         => $this->modify_array($theme_options),
                    'description'     => __( 'Selecting a theme will override your current layout, style and color settings. The default width of a
                    vertical theme is 300px and 730px for horizontal.', 'plugin-name' ),
                    'default' => ''
                )
            );

            $this->add_control(
              'default_view',
                array(
                    'label'           => __( 'Default view', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SELECT,
                    'options'         => $this->modify_array($default_view_options),
                    'default' => 'list'
                )
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

            $this->add_control(
              'orientation',
                array(
                    'label'           => __( 'Orientation', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SELECT,
                    'options'         => $this->modify_array($orientation_options),
                    'default' => 'horizontal'
                )
            );

            $this->add_control(
              'width_',
                array(
                    'label'           => __( 'Widget Width', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SLIDER,
                    'description'     => __( 'Horizontal × Vertical', 'plugin-name' ),
                    'size_units' => [ 'px' ],
                    'range'  => [
                        'px' => [
                            'min'  => 1,
                            'max'  => 900,
                            'step' => 1,
                        ]
                    ],
                    'default'       => [
                        'unit' => 'px',
                        'size' => 650,
                    ],
                )
            );

            $this->add_control(
              'title_font',
                array(
                    'label'           => __( 'Title Font', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SELECT,
                    'options'         => flexmlsConnect::possible_fonts(),
                    'default' => 'Arial'
                )
            );

            $this->add_control(
              'field_font',
                array(
                    'label'           => __( 'Field Font', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SELECT,
                    'options'         => flexmlsConnect::possible_fonts(),
                    'default' => 'Arial'
                )
            );

            $this->add_control(
              'border_style',
                array(
                    'label'           => __( 'Border Style', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SELECT,
                    'options'         => $this->modify_array($border_style_options),
                    'default' => 'squared'
                )
            );

            $this->add_control(
              'widget_drop_shadow',
              array_merge(
                  [
                    'label'       => __( 'Widget Drop Shadow', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SWITCHER,
                    'default'     => 'yes',
                    'return_value' => 'yes',
                ],
                $on_off_options
              )
            );

            $this->add_control(
              'background_color_',
                array(
                    'label'             => __( 'Background', 'plugin-name' ),
                    'type'              => \Elementor\Controls_Manager::COLOR,
                    'default'           => '#ffffff',
                )
            );

            $this->add_control(
              'title_text_color',
                array(
                    'label'             => __( 'Title Text', 'plugin-name' ),
                    'type'              => \Elementor\Controls_Manager::COLOR,
                    'default'           => '#000000',
                )
            );

            $this->add_control(
              'field_text_color',
                array(
                    'label'             => __( 'Field Text', 'plugin-name' ),
                    'type'              => \Elementor\Controls_Manager::COLOR,
                    'default'           => '#000000'
                )
            );

            $this->add_control(
              'detailed_search_text_color',
                array(
                    'label'             => __( 'Detailed Search', 'plugin-name' ),
                    'type'              => \Elementor\Controls_Manager::COLOR,
                    'default'           => '#000000'
                )
            );

            $this->add_control(
              'submit_button_shine',
                array(
                    'label'           => __( 'Submit Button', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SELECT,
                    'options'         => $this->modify_array($submit_button_options),
                    'default' => 'shine'
                )
            );

            $this->add_control(
                'submit_button_background',
                array(
                    'label'             => __( 'Submit Button Background', 'plugin-name' ),
                    'type'              => \Elementor\Controls_Manager::COLOR,
                    'default'           => '#000000'
                )
            );

            $this->add_control(
                'submit_button_text_color',
                array(
                    'label'             => __( 'Submit Button Text', 'plugin-name' ),
                    'type'              => \Elementor\Controls_Manager::COLOR,
                    'default'           => '#ffffff'
                )
            );
        }

  };
