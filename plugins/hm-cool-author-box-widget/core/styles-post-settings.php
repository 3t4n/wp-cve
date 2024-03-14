<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
* Trait For Styles Post Settings
*/
trait Hmcab_Styles_Post_Settings 
{

    protected $fields, $settings, $options;
    
    protected function set_styles_post_settings( $post ) {

        $this->fields   = $this->cab_styles_post_fileds();

        $this->options  = $this->cab_build_set_settings_options( $this->fields, $post );

        $this->settings = apply_filters( 'cab_styles_post_settings', $this->options, $post );

        return update_option( 'cab_styles_post_settings', $this->settings );

    }

    protected function get_styles_post_settings() {

        $this->fields   = $this->cab_styles_post_fileds();
		$this->settings = get_option('cab_styles_post_settings');
        
        return $this->cab_build_get_settings_options( $this->fields, $this->settings );
	}

    protected function cab_styles_post_fileds() {

        return [
            [
                'name'      => 'cab_container_border_color',
                'type'      => 'text',
                'default'   => '#DDD',
            ],
            [
                'name'      => 'cab_container_border_width',
                'type'      => 'text',
                'default'   => '1',
            ],
            [
                'name'      => 'cab_container_bg_color',
                'type'      => 'text',
                'default'   => '',
            ],
            [
                'name'      => 'cab_container_border_radius',
                'type'      => 'text',
                'default'   => '0',
            ],
            [
                'name'      => 'cab_img_border_color',
                'type'      => 'text',
                'default'   => '#242424',
            ],
            [
                'name'      => 'cab_img_border_width',
                'type'      => 'number',
                'default'   => 0,
            ],
            [
                'name'      => 'cab_post_name_font_color',
                'type'      => 'text',
                'default'   => '#242424',
            ],
            [
                'name'      => 'cab_post_name_font_size',
                'type'      => 'text',
                'default'   => 22,
            ],
            [
                'name'      => 'cab_post_title_font_color',
                'type'      => 'text',
                'default'   => '#242424',
            ],
            [
                'name'      => 'cab_post_title_font_size',
                'type'      => 'text',
                'default'   => 16,
            ],
            [
                'name'      => 'cab_post_desc_font_color',
                'type'      => 'text',
                'default'   => '#242424',
            ],
            [
                'name'      => 'cab_post_desc_font_size',
                'type'      => 'text',
                'default'   => 12,
            ],
            [
                'name'      => 'cab_post_email_font_color',
                'type'      => 'text',
                'default'   => '#242424',
            ],
            [
                'name'      => 'cab_post_email_font_size',
                'type'      => 'text',
                'default'   => 12,
            ],
        ];
    }
}