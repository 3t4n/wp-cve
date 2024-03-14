<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
* Trait For Personal Settings
*/
trait Hmcab_Personal_Settings 
{

    protected $fields, $settings, $options;
    
    protected function set_personal_settings( $post ) {

        $this->fields   = $this->cab_personal_option_fileds();

        $this->options  = $this->cab_build_set_settings_options( $this->fields, $post );

        $this->settings = apply_filters( 'cab_personal_info_settings', $this->options, $post );

        return update_option( 'cab_personal_info_settings', $this->settings );

    }

    public function get_personal_settings() {

        $this->fields   = $this->cab_personal_option_fileds();
		$this->settings = get_option('cab_personal_info_settings');
        
        return $this->cab_build_get_settings_options( $this->fields, $this->settings );
	}

    protected function cab_personal_option_fileds() {

        return [
            [
                'name'      => 'hmcabw_author_name',
                'type'      => 'text',
                'default'   => 'Your Name Here',
            ],
            [
                'name'      => 'hmcabw_author_title',
                'type'      => 'text',
                'default'   => 'Any Title of You!',
            ],
            [
                'name'      => 'hmcabw_author_email',
                'type'      => 'text',
                'default'   => 'your_email@your-domain.me',
            ],
            [
                'name'      => 'hmcabw_author_website',
                'type'      => 'text',
                'default'   => 'Your Website URL',
            ],
            [
                'name'      => 'hmcabw_biographical_info',
                'type'      => 'textarea',
                'default'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            ],
            [
                'name'      => 'hmcabw_biographical_info_font_size',
                'type'      => 'number',
                'default'   => 12,
            ],
            [
                'name'      => 'hmcabw_author_image_selection',
                'type'      => 'string',
                'default'   => 'upload_image',
            ],
            [
                'name'      => 'hmcabw_photograph',
                'type'      => 'text',
                'default'   => '',
            ],
        ];
    }
}