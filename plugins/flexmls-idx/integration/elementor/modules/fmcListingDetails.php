<?php
    class EL_fmcListingDetails extends EL_FMC_shortcode{ 
        
        protected function integrationWithElementor(){
            $this->settings_fmc = ['listing'];
        }
        
        protected function render_hook($settings){
            $return = $settings + ['integration' => 'elementor'];
            return $return;
        }
  
        protected function setControlls() {
            extract($this->module_info['vars']);

            $this->add_control(
                'listing',
                    [
                        'label' => __( $title, 'plugin-name' ),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'input_type' => 'text',
                    ]
            );
        }  
    
  };