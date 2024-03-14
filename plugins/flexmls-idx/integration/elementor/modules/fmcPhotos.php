<?php
    class EL_fmcPhotos extends EL_FMC_shortcode{
        
        protected function integrationWithElementor(){
            $this->settings_fmc = [
                'title',
                'link',
                'horizontal',
                'vertical',
                'image_size',
                'auto_rotate',
                'source',
                'property_type',
                'location',
                'agent',
                'display',
                'days',
                'sort',
                'additional_fields',
                'destination',
                'send_to',
            ];
        }

        private $additional_fields;
        
        protected function render_hook($settings){
            $props = $settings;
            $props['horizontal'] = $props['horizontal']['size'];
            $props['vertical'] = $props['vertical']['size'];
            $return = $props + ['integration' => 'elementor'];
            return $return;
        }
  
        protected function setControlls() {
            extract($this->module_info['vars']);

            $this->additional_fields = $additional_fields;

            $idx_links_use = array_merge(['default' => '(Use Saved Default)'], $this->modify_array($idx_links, 'LinkId', 'Name'));
     
            $agent_use = array_merge([''=>'  - Select One -  '], $this->modify_array($agent, 'Id', 'Name'));
     
            
            $this->add_control(
                'title',
                array(
                    'label'           => __( 'Title' , 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::TEXT,
                    'description'     => __( $title_description, 'plugin-name' ),
                    'default'         => $title,
                )
            );

             $this->add_control(
                'link',
                array(
                    'label'           => __( 'IDX Link', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SELECT,
                    'options'         => $idx_links_use,
                    'description'     => __( 'Link used when search is executed', 'plugin-name' ),
                    'default' => 'default'
                )
            );

            $this->add_control(
                'horizontal',
                array(
                    'label'           => __( 'Horizontal', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range'  => [
                        'px' => [
                            'min'  => 1,
                            'max'  => count($horizontal),
                            'step' => 1,
                        ]
                    ],
                    'default' => [
                            'unit' => 'px',
                            'size' => 3,
                        ]
                )
            );

            $this->add_control(
                'vertical',
                array(
                    'label'           => __( 'Vertical', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range'  => [
                        'px' => [
                            'min'  => 1,
                            'max'  => count($vertical),
                            'step' => 1,
                        ]
                    ],
                    'default' => [
                            'unit' => 'px',
                            'size' => 3,
                        ]
                )
            );

            $this->add_control(
                'image_size',
                array(
                    'label'           => __( 'Size of Slideshow', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SELECT,
                    'options'         => $image_size,
                    'default' => 0
                )
            );

            $this->add_control(
                'auto_rotate',
                array(
                    'label'           => __( 'Slideshow', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SELECT,
                    'options'         => $auto_rotate,
                    'default' => 0
                )
            );

            $this->add_control(
                'source',
                array(
                    'label'           => __( 'Filter by', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SELECT,
                    'options'         => $source,
                    'description'     => __( 'Which listings to display', 'plugin-name' ),
                    'default' => 'location'
                )
            );

            $this->add_control(
                'property_type',
                array(
                    'label'           => __( 'Property Type', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SELECT,
                    'options'         => $property_type,
                    'condition'     => array(
                        'source' => 'location'
                    ),
                )
            );

            $this->add_control(
                'location',
                array(
                    'label'           => __( 'Location' , 'plugin-name' ),
                    'type' => 'location_control',
                    'multiple' => false,
                    'field_slug' => $location_slug,
                    'description'     => __( $title_description, 'plugin-name' ),
                    'condition'     => array(
                        'source' => 'location'
                    ),
                )
            );

            $this->add_control(
                'agent',
                array(
                    'label'           => __( 'Agent', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SELECT,
                    'options'         => $agent_use,
                    'description'     => __( 'Link used when search is executed', 'plugin-name' ),
                    'condition'     => array(
                        'source' => 'agent'
                    ),
                )
            );

            $this->add_control(
                'display',
                array(
                    'label'           => __( 'Display', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SELECT,
                    'options'         => $display,
                    'default'         => 'all',            
                )
            );

            $this->add_control(
                'days',
                array(
                    'label'           => __( 'Number of Days', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SELECT,
                    'options'         => $this->set_dimentions($days),
                    'description'     => __( 'The number of days in the past for display: new listings, open houses, etc.', 'plugin-name' ),
                    'condition'     => array(
                        'display!' => 'all',
                    ),
                )
            );

            $this->add_control(
                'sort',
                array(
                    'label'           => __( 'Sort by', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SELECT,
                    'options'         => $sort,
                    'default' => 'recently_changed'
                )
            );

            $this->add_control(
                'additional_fields',
                array(
                    'label'           => __( 'Additional Fields to Show', 'plugin-name' ),
                    'type'            => 'checkboxes_control',
                    'options'         => $additional_fields,  
                )
            );

            $this->add_control(
                'destination',
                array(
                    'label'           => __( 'Send users to', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SELECT,
                    'options'         => $destination,
                    'default' => 'local'
                )
            );

            $this->add_control(
                'send_to',
                array(
                    'label'           => __( 'When Slideshow Photo Is Clicked Send Users To', 'plugin-name' ),
                    'type'            => \Elementor\Controls_Manager::SELECT,
                    'options'         => $send_to,
                    'default' => 'photo'
                )
            ); 
        }
        
        private function set_dimentions($arr){
            $return = array();
            foreach ($arr as $key => $value) {
                $return[(string) $key] = (string) $value;
            }
    
            return $return;
        }
  };