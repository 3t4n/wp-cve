<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
* Trait For Template Settings
*/
trait Hmcab_Template_Settings 
{

    protected $fields, $settings, $options;
    
    protected function set_template_settings( $post ) {

        $this->fields   = $this->cab_temp_option_fileds();

        $this->options  = $this->cab_build_set_settings_options( $this->fields, $post );

        $this->settings = apply_filters( 'cab_template_settings', $this->options, $post );

        return update_option( 'cab_template_settings', $this->settings );

    }

    protected function get_template_settings() {

        $this->fields   = $this->cab_temp_option_fileds();
		$this->settings = get_option('cab_template_settings');
        
        return $this->cab_build_get_settings_options( $this->fields, $this->settings );
	}

    protected function cab_temp_option_fileds() {

        return [
            [
                'name'      => 'hmcabw_select_template',
                'type'      => 'string',
                'default'   => 'temp_0',
            ],
            [
                'name'      => 'hmcabw_display_in_post_page',
                'type'      => 'boolean',
                'default'   => false,
            ],
            [
                'name'      => 'hmcabw_display_selection',
                'type'      => 'string',
                'default'   => 'bottom',
            ],
            [
                'name'      => 'hmcabw_display_title',
                'type'      => 'boolean',
                'default'   => false,
            ],
            [
                'name'      => 'hmcabw_display_email',
                'type'      => 'boolean',
                'default'   => false,
            ],
            [
                'name'      => 'hmcabw_display_web',
                'type'      => 'boolean',
                'default'   => false,
            ],
            [
                'name'      => 'hmcabw_icon_shape',
                'type'      => 'string',
                'default'   => 'square',
            ],
            [
                'name'      => 'hmcabw_photo_width',
                'type'      => 'number',
                'default'   => 100,
            ],
            [
                'name'      => 'cab_image_animation',
                'type'      => 'string',
                'default'   => '',
            ],
            [
                'name'      => 'cab_widget_content_alignment',
                'type'      => 'string',
                'default'   => 'center',
            ],
            [
                'name'      => 'cab_hide_banner',
                'type'      => 'boolean',
                'default'   => false,
            ],
            [
                'name'      => 'cab_profile_banner',
                'type'      => 'text',
                'default'   => '',
            ],
        ];
    }
}