<?php
    class EL_fmcLocationLinks extends EL_FMC_shortcode{ 
        
        protected function integrationWithElementor(){
            $this->settings_fmc = ['title', 'link', 'property_type', 'locations', 'default_view', 'destination'];
        }
        
        protected function render_hook($settings){
            $return = $settings + ['integration' => 'elementor'];
            return $return;
        }
  
        protected function setControlls() {
            extract($this->module_info['vars']);

            $property_type = array_merge([''=>'All'], $property_type);

            $this->add_control(
                'title',
                    [
                        'label' => __( 'Title', 'plugin-name' ),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'input_type' => 'text',
                    ]
            );

            $this->add_control(
                'link',
                [
                    'label' => __( 'IDX Link', 'plugin-name' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => $this->modify_array($api_links, 'LinkId', 'Name'),
                    'description' => 'Saved Search IDX link these locations are built upon',
                    'default' => 'default'
                ]
            );

            $this->add_control(
                'property_type',
                [
                    'label' => __( 'Property Type', 'plugin-name' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => $property_type,
                    'description' => 'Link used when search is executed',
                    'default' => ''
                ]
            );

            $this->add_control(
                'locations',
                [
                    'label' => __( 'Location', 'plugin-name' ),
                    'type' => 'location_control',
                    'multiple' => true,
                    'field_slug' => $location_slug,
                ]
            );

            $this->add_control(
                'default_view',
                [
                    'label' => __( 'Default view', 'plugin-name' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => $default_view,
                    'default' => 'list'
                ]
            );

            $this->add_control(
                'destination',
                [
                    'label' => __( 'Send users to', 'plugin-name' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => $destination,
                    'default' => 'local'
                ]
            );
        }  
    
  };