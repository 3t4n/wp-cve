<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
};

class CLP_Customizer_Settings {

    /**
     * Returns settings fields
     * @since 1.0.0
     * @return array
    **/

    private $settings;


    public function __construct() {
        $this->settings = apply_filters( 'clp_modify_customizer_settings', $this->settings_fields() );
        // $this->get_current_settings();
    }

    public function get_settings_fields() {
        return $this->settings;
    }

    /**
     * Returns CSS fields from settings array for generating CSS
     * @since 1.0.0
    **/
    public function get_css_fields() {

        $css = array();

        foreach ( $this->settings as $section => $controls ) {
            foreach ( $controls['fields'] as $key => $value ) {

                if ( isset($value['css']) ) {
                    array_push( $css, $this->get_css_value($value, 'css', $section));
                }
                
                if ( isset($value['css2']) ) {
                    array_push( $css, $this->get_css_value($value, 'css2', $section));
                }

            }
        }

        return $css;
    }
    /**
     * Helper function to manipulate CSS value array
     * @since 1.4.0
    **/
    private function get_css_value( $value, $id, $section ) {

        $value[$id]['id'] = $value['id'];
        $value[$id]['default'] = $value['default'];

        if ( isset($value[$id]['dependency']) ) {
            $value[$id]['dependency']['default'] = $this->get_css_dependency_default_value( $value[$id]['dependency'][0], $section );
        }

        return $value[$id];
    }


    /**
     * Helper to get CSS dependency on another settings
     * @since 1.0.0
    **/
    private function get_css_dependency_default_value( $control_id, $section) {

        foreach ( $this->settings[$section]['fields'] as $control ) {
            if ( $control['id'] === $control_id ) {
                return $control['default'];
            }
        }

        return false;
    }


    /**
     * Returns array with default settings
     * @since 1.0.0
     * @return array
    **/
    private function get_default_template_settings() {

        $default_settings = apply_filters( 'clp_modify_customizer_default_settings', array(
            'default' => array(
                'clp_layout-width' => '100',
                'clp_layout-content-background-color' => 'rgba(255,255,255,1)',
                'clp_layout-background-blur' => '0',
                'clp_layout-content-skew' => '0',
                'clp_logo' => 'image',
                'clp_logo-text-color' => '#0073aa',
                'clp_logo-image' => '',
                'clp_logo-image-width' => '80',
                'clp_logo-image-height' => '80',
                'clp_logo-spacing-top' => '0',
                'clp_logo-spacing-bottom' => '30',
                'clp_logo-contained' => false,
                'clp_logo-google_fonts' => '{"family":"Roboto","variants":["100","100italic","300","300italic","regular","italic","500","500italic","700","700italic","900","900italic"],"selected":{"variant":"regular"}}',
                'clp_logo-text-font_size' => '35',
                'clp_logo-text-letter_spacing' => '0',
                'clp_background' => 'color',
                'clp_background-color' => '#f1f1f1',
                'clp_background-gradient-color1' => '#f1f1f1',
                'clp_background-gradient-color1-position' => '0',
                'clp_background-gradient-color2' => '#878787',
                'clp_background-gradient-color2-position' => '100',
                'clp_background-gradient-angle' => '30',
                'clp_background-pattern' => 'fabric',
                'clp_background-pattern-custom' => '',
                'clp_background-image' => '',
                'clp_background-unsplash' => '',
                'clp_background-video_local' => '',
                'clp_background-video_yt' => '',
                'clp_background-video_loop' => '1',
                'clp_background-video_thumb' => '',
                'clp_background-overlay-enable' => false,
                'clp_background-overlay-color' => 'rgba(0,0,0,0.4)',
                'clp_background-blur' => '0',
                'clp_form-width' => '340',
                'clp_form-height' => '380',
                'clp_form-padding' => '20',
                'clp_form-background' => 'rgba(255,255,255,1)',
                'clp_form-text_color' => '#32373c',
                'clp_form-blur' => '0',
                'clp_form-border_radius' => '0',
                'clp_form_typography-google_fonts' => '{"family":"Roboto","variants":["100","100italic","300","300italic","regular","italic","500","500italic","700","700italic","900","900italic"],"selected":{"variant":"regular"}}',
                'clp_form-borders' => false,
                'clp_form-border_color' => '#7e8993',
                'clp_form-border_width' => '1',
                'clp_form-shadow' => '1',
                'clp_form-shadow-horizontal_length' => '10',
                'clp_form-shadow-vertical_length' => '10',
                'clp_form-shadow-blur_radius' => '40',
                'clp_form-shadow-spread_radius' => '-10',
                'clp_form-shadow-color' => 'rgba(0,0,0,0.75)',
                'clp_input-background_color' => '#ffffff',
                'clp_input-background_color_focus' => '#ffffff',
                'clp_input-color' => '#32373c',
                'clp_input-color_focus' => '#32373c',
                'clp_input-label_display' => '1',
                'clp_input-label_color' => '#32373c',
                'clp_input-font_size_label' => '14',
                'clp_input-label_font_weight' => '400',
                'clp_input-font_size_input' => '20',
                'clp_input-height' => '40',
                'clp_input-border_width' => '1',
                'clp_input-border_color' => '#7e8993',
                'clp_input-border_color_focus' => '#007cba',
                'clp_input-border_radius' => '3',
                'clp_input-text_indent' => '8',
                'clp_input-showpassword' => get_option('clp_input-showpassword', '1'),
                'clp_input-remember' => get_option('clp_input-remember', '1'),
                'clp_button-background_color' => 'rgba(0,124,186,1)',
                'clp_button-background_color_hover' => 'rgba(0,124,186,1)',
                'clp_button-text_color' => '#ffffff',
                'clp_button-text_color_hover' => '#ffffff',
                'clp_button-font_size' => '14',
                'clp_button-font_weight' => '400',
                'clp_button-width' => '30',
                'clp_button-height' => '40',
                'clp_button-align' => 'right',
                'clp_button-border_radius' => '3',
                'clp_button-border_width' => '1',
                'clp_button-border_color' => '#ffffff',
                'clp_button-border_color_hover' => '#ffffff',
                'clp_form_footer-align' => 'center',
                'clp_form_footer-font_size' => '13',
                'clp_form_footer-display_forgetpassword' => get_option('clp_form_footer-display_forgetpassword', '1'),
                'clp_form_footer-forgetpassword_color' => '#555d66',
                'clp_form_footer-forgetpassword_color_hover' => '#00a0d2',
                'clp_form_footer-display_backtoblog' => get_option('clp_form_footer-display_backtoblog', '1'),
                'clp_form_footer-backtoblog_color' => '#555d66',
                'clp_form_footer-backtoblog_color_hover' => '#00a0d2',
                'clp_form_footer-display_register' => get_option( 'users_can_register' ),
                'clp_form_footer-register_color' => '#555d66',
                'clp_form_footer-register_color_hover' => '#00a0d2',
                'clp_form_footer-display_privacy' => get_option('clp_form_footer-display_privacy', '1'),
                'clp_form_footer-privacy_color' => '#555d66',
                'clp_form_footer-privacy_color_hover' => '#00a0d2',
                'clp_messages-text_color'   => '#32373c',
                'clp_messages-border_color'   => '#00a0d2',
                'clp_messages-border_color_error'   => '#dc3232',
                'clp_messages-border_color_success'   => '#46b450',
                'clp_footer-enable' => get_option('clp_footer-enable', '1'),
                'clp_footer-width' => '100',
                'clp_footer-padding' => '15',
                'clp_footer-background_color' => 'rgba(0,0,0, 0.4)',
                'clp_footer-text_color' => '#fff',
                'clp_footer-niteothemes' => get_option('clp_footer-niteothemes', '1'),
                'clp_footer-niteothemes_pos' => 'right',
                'clp_footer-copyright' => get_option('clp_form_footer-copyright', ''),
                'clp_footer-copyright_pos' => 'left',
                'clp_footer-link_color' => '#0073aa',
                'clp_footer-link_color_hover' => '#006799',
            ),
            'light-theme' => array(
                'clp_logo' => 'none',
                'clp_background-color' => '#ededed',
                'clp_form-width' => '380',
                'clp_form-height' => '480',
                'clp_form-padding' => '30',
                'clp_form-background' => 'rgba(255,255,255,1)',
                'clp_form_typography-google_fonts' => '{"family":"Exo","variants":["100","200","300","regular","500","600","700","800","900","100italic","200italic","300italic","italic","500italic","600italic","700italic","800italic","900italic"],"selected":{"variant":"500"}}',
                'clp_form-borders' => '1',
                'clp_form-border_color' => '#828282',
                'clp_form-border_radius' => '3',
                'clp_form-shadow-spread_radius' => '-14',
                'clp_form-shadow-color' => 'rgba(2,2,2,0.73)',
                'clp_input-background_color_focus' => '#fff',
                'clp_input-color' => '#232323',
                'clp_input-color_focus' => '#232323',
                'clp_input-label_color' => '#232323',
                'clp_input-border_color' => '#a8a8a8',
                'clp_input-border_color_focus' => '#4bb2f9',
                'clp_button-background_color' => '#232323',
                'clp_button-background_color_hover' => 'rgba(255,255,255,1)',
                'clp_button-text_color_hover' => 'rgba(35,35,35,1)',
                'clp_button-width' => '100',
                'clp_button-border_color' => '#000000',
                'clp_button-border_color_hover' => '#000000',
                'clp_form_footer-forgetpassword_color' => '#b7b7b7',
                'clp_form_footer-forgetpassword_color_hover' => '#4bb2f9',
                'clp_form_footer-backtoblog_color' => '#b7b7b7',
                'clp_form_footer-backtoblog_color_hover' => '#4bb2f9',
                'clp_form_footer-register_color' => '#b7b7b7',
                'clp_form_footer-register_color_hover' => '#4bb2f9',            
                'clp_form_footer-privacy_color' => '#b7b7b7',
                'clp_form_footer-privacy_color_hover' => '#4bb2f9',            
            ),
            'modern-theme' => array(
                'clp_layout-width' => '40',
                'clp_layout-content-background-color' => 'rgba(255,255,255,0.56)',
                'clp_layout-background-blur' => '7',
                'clp_layout-content-skew' => '16',
                'clp_background' => 'image',
                'clp_background-unsplash' => '{"urls":{"original":"https://images.unsplash.com/photo-1589652717521-10c0d092dea9?ixid=MXwxNDEwNzN8MHwxfHNlYXJjaHw1fHxsYXB0b3B8ZW58MHx8fA&ixlib=rb-1.2.1","small":"https://images.unsplash.com/photo-1589652717521-10c0d092dea9?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MXwxNDEwNzN8MHwxfHNlYXJjaHw1fHxsYXB0b3B8ZW58MHx8fA&ixlib=rb-1.2.1&q=80&w=400"},"link":"https://unsplash.com/photos/gySMaocSdqs","username":"Cookie the Pom","userlink":"https://unsplash.com/@cookiethepom","download":"https://api.unsplash.com/photos/gySMaocSdqs/download"}',
                'clp_logo' => 'none',
                'clp_form-width' => '380',
                'clp_form-height' => '480',
                'clp_form-padding' => '30',
                'clp_form-background' => '#121212',
                'clp_form_typography-google_fonts' => '{"family":"Exo","variants":["100","200","300","regular","500","600","700","800","900","100italic","200italic","300italic","italic","500italic","600italic","700italic","800italic","900italic"],"selected":{"variant":"500"}}',
                'clp_form-borders' => '0',
                'clp_form-border_radius' => '3',
                'clp_form-shadow-spread_radius' => '-14',
                'clp_form-shadow-color' => 'rgba(2,2,2,0.73)',
                'clp_input-background_color' => '#121212',
                'clp_input-background_color_focus' => '#121212',
                'clp_input-color' => '#ffffff',
                'clp_input-color_focus' => '#ffffff',
                'clp_input-label_color' => '#ffffff',
                'clp_input-border_color' => '#272727',
                'clp_input-border_color_focus' => '#4bb2f9',
                'clp_button-background_color' => 'rgba(255,255,255,1)',
                'clp_button-background_color_hover' => 'rgba(0,0,0,1)',
                'clp_button-text_color' => '#000000',
                'clp_button-width' => '100',
                'clp_form_footer-forgetpassword_color' => '#b7b7b7',
                'clp_form_footer-forgetpassword_color_hover' => '#4bb2f9',
                'clp_form_footer-backtoblog_color' => '#b7b7b7',
                'clp_form_footer-backtoblog_color_hover' => '#4bb2f9',
                'clp_form_footer-register_color' => '#b7b7b7',
                'clp_form_footer-register_color_hover' => '#4bb2f9',                                
                'clp_form_footer-privacy_color' => '#b7b7b7',
                'clp_form_footer-privacy_color_hover' => '#4bb2f9',
                'clp_messages-text_color' => '#ffffff'                          
            ),
            'dark-theme' => array(
                'clp_logo' => 'none',
                'clp_background-color' => '#212121',
                'clp_form-width' => '380',
                'clp_form-height' => '480',
                'clp_form-padding' => '30',
                'clp_form-background' => 'rgba(18,18,18,0.8)',
                'clp_form_typography-google_fonts' => '{"family":"Exo","variants":["100","200","300","regular","500","600","700","800","900","100italic","200italic","300italic","italic","500italic","600italic","700italic","800italic","900italic"],"selected":{"variant":"500"}}',
                'clp_form-borders' => '1',
                'clp_form-border_color' => '#353535',
                'clp_form-border_radius' => '3',
                'clp_form-shadow-spread_radius' => '-14',
                'clp_form-shadow-color' => 'rgba(2,2,2,0.73)',
                'clp_input-background_color' => '#121212',
                'clp_input-background_color_focus' => '#121212',
                'clp_input-color' => '#ffffff',
                'clp_input-color_focus' => '#ffffff',
                'clp_input-label_color' => '#ffffff',
                'clp_input-border_color' => '#272727',
                'clp_input-border_color_focus' => '#4bb2f9',
                'clp_button-background_color' => 'rgba(255,255,255,1)',
                'clp_button-background_color_hover' => 'rgba(0,0,0,1)',
                'clp_button-text_color' => '#000000',
                'clp_button-width' => '100',
                'clp_form_footer-forgetpassword_color' => '#b7b7b7',
                'clp_form_footer-forgetpassword_color_hover' => '#4bb2f9',
                'clp_form_footer-backtoblog_color' => '#b7b7b7',
                'clp_form_footer-backtoblog_color_hover' => '#4bb2f9',
                'clp_form_footer-register_color' => '#b7b7b7',
                'clp_form_footer-register_color_hover' => '#4bb2f9',                                
                'clp_form_footer-privacy_color' => '#b7b7b7',
                'clp_form_footer-privacy_color_hover' => '#4bb2f9',
                'clp_messages-text_color' => '#ffffff'                         
            ),
            'femine-theme' => array(
                'clp_logo' => 'none',
                'clp_background-color' => '#d10073',
                'clp_form-width' => '380',
                'clp_form-height' => '480',
                'clp_form-padding' => '30',
                'clp_form-background' => 'rgba(255,255,255,1)',
                'clp_form-border_radius' => '3',
                'clp_form_typography-google_fonts' => '{"family":"Exo","variants":["100","200","300","regular","500","600","700","800","900","100italic","200italic","300italic","italic","500italic","600italic","700italic","800italic","900italic"],"selected":{"variant":"500"}}',
                'clp_form-borders' => '1',
                'clp_form-border_color' => '#828282',
                'clp_form-shadow-spread_radius' => '-14',
                'clp_form-shadow-color' => 'rgba(2,2,2,0.73)',
                'clp_input-background_color_focus' => '#fff',
                'clp_input-color' => '#232323',
                'clp_input-color_focus' => '#232323',
                'clp_input-label_color' => '#232323',
                'clp_input-border_color' => '#f7a5ca',
                'clp_input-border_color_focus' => '#f72585',
                'clp_button-background_color' => 'rgba(247,37,133,1)',
                'clp_button-background_color_hover' => 'rgba(209,0,115,1)',
                'clp_button-width' => '100',
                'clp_button-height' => '48',
                'clp_button-align' => 'center',
                'clp_button-border_width' => '0',
                'clp_form_footer-forgetpassword_color' => '#b7b7b7',
                'clp_form_footer-forgetpassword_color_hover' => '#f72585',
                'clp_form_footer-backtoblog_color' => '#b7b7b7',
                'clp_form_footer-backtoblog_color_hover' => '#f72585',
                'clp_form_footer-register_color' => '#b7b7b7',
                'clp_form_footer-register_color_hover' => '#f72585',
                'clp_form_footer-privacy_color' => '#b7b7b7',
                'clp_form_footer-privacy_color_hover' => '#f72585',
            ),
            'flat_white-theme' => array(
                'clp_logo' => 'none',
                'clp_background-color' => '#baa898',
                'clp_form-width' => '380',
                'clp_form-height' => '480',
                'clp_form-padding' => '30',
                'clp_form-background' => 'rgba(238,224,203,0.8)',
                'clp_form-border_radius' => '3',
                'clp_form_typography-google_fonts' => '{"family":"Exo","variants":["100","200","300","regular","500","600","700","800","900","100italic","200italic","300italic","italic","500italic","600italic","700italic","800italic","900italic"],"selected":{"variant":"500"}}',
                'clp_form-borders' => '1',
                'clp_form-border_color' => '#baa898',
                'clp_form-shadow' => false,
                'clp_input-background_color_focus' => '#fff',
                'clp_input-color' => '#baa898',
                'clp_input-color_focus' => '#baa898',
                'clp_input-label_color' => '#2a2d34',
                'clp_input-border_color' => '#baa898',
                'clp_input-border_color_focus' => '#ba9575',
                'clp_button-background_color' => 'rgba(9,122,198,1)',
                'clp_button-background_color_hover' => 'rgba(42,45,52,1)',
                'clp_button-border_width' => '0',
                'clp_button-width' => '100',
                'clp_button-height' => '48',
                'clp_button-align' => 'center',
                'clp_button-border_color' => '#000000',
                'clp_button-border_color_hover' => '#000000',
                'clp_form_footer-forgetpassword_color' => '#848586',
                'clp_form_footer-forgetpassword_color_hover' => '#2a2d34',
                'clp_form_footer-backtoblog_color' => '#848586',
                'clp_form_footer-backtoblog_color_hover' => '#2a2d34',
                'clp_form_footer-register_color' => '#848586',
                'clp_form_footer-register_color_hover' => '#2a2d34',
                'clp_form_footer-privacy_color' => '#848586',
                'clp_form_footer-privacy_color_hover' => '#2a2d34',                              
            )
        ));

        return $default_settings;
    }


    /**
     * Define CSS Customizer Settings fields
     * @since 1.0.0
     * @return array
    **/
    public function settings_fields() {
        $default = $this->get_default_template_settings();

        $settings['templates'] = array(
            'title'       => esc_html__( 'Predefined Templates', 'clp-custom-login-page' ),
            'description' => '',
            'fields'      => array(
                array(
                    'id'            => 'clp_templates-separator',
                    'label'         => esc_html__( 'Select Template', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'          => 'clp_templates',
                    'description' => '',
                    'type'        => 'clp-template',
                    'default'     => 'default',
                    'choices'	=> array(
                        'default' => array(
                            'name'      => 'Default',
                            'thumbnail' => CLP_PLUGIN_PATH . 'assets/img/templates-thumbnail/default.jpg',
                            'options' => $default['default'],
                        ),
                        'light-theme' => array(
                            'name' => 'Light UI',
                            'thumbnail' => CLP_PLUGIN_PATH . 'assets/img/templates-thumbnail/light-theme.jpg',
                            'options' => $default['light-theme']
                        ),
                        'modern-theme' => array(
                            'name' => 'Modern UI',
                            'thumbnail' => CLP_PLUGIN_PATH . 'assets/img/templates-thumbnail/modern-theme.jpg',
                            'options' => $default['modern-theme']
                        ),
                        'dark-theme' => array(
                            'name' => 'Dark UI',
                            'thumbnail' => CLP_PLUGIN_PATH . 'assets/img/templates-thumbnail/dark-theme.jpg',
                            'options' => $default['dark-theme']
                        ),
                        'femine-theme' => array(
                            'name' => 'Feminine',
                            'thumbnail' => CLP_PLUGIN_PATH . 'assets/img/templates-thumbnail/femine-theme.jpg',
                            'options' => $default['femine-theme']                           
                        ),
                        'flat_white-theme' => array(
                            'name' => 'Flat White',
                            'thumbnail' => CLP_PLUGIN_PATH . 'assets/img/templates-thumbnail/flat_white-theme.jpg',
                            'options' => $default['flat_white-theme']  
                        ),
                    ),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
            )
        );

        $default = $default['default'];
        
        $settings['layout'] = array(
            'title'       => esc_html__( 'Layout', 'clp-custom-login-page' ),
            'description' => '',
            'fields'      => array(
                array(
                    'id'            => 'clp_layout-separator',
                    'label'         => esc_html__( 'Layout', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'          => 'clp_layout-width',
                    'label'       => esc_html__( 'Content Width', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_layout-width'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 25,
                        'max'    => 100,
                        'step'   => 5,
                        'suffix' => '%', 
                    ),
                    'sanitize_callback' => 'absint',
                    'css'			=> array(
                        'selector'	=> '.clp-content',
                        'property'	=> 'width',
                        'suffix'	=> '%',
                    ),
                    'css2'			=> array(
                        'selector'	=> 'body.clp-content-half:not(.clp-content-skew):not(.clp-content-opaque) .clp-background-wrapper',
                        'property'	=> 'left',
                        'suffix'	=> '%',
                    )
                ),
                array(
                    'id'          => 'clp_layout-content-background-color',
                    'label'       => esc_html__( 'Content Background Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'alpha-color',
                    'default'     => $default['clp_layout-content-background-color'],
                    'transport'		=> 'postMessage',
                    'active_callback' => array( $this, 'is_content_half_width'),
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_alpha_color'),
                    'css'			=> array(
                        'selector'	=> '.clp-content-half .clp-content::after',
                        'property'	=> 'background-color',
                        'dependency'=>  array('clp_layout-width', '100', '!=' ),
                    )
                ),
                array(
                    'id'          => 'clp_layout-background-blur',
                    'label'       => esc_html__( 'Backdrop Blur', 'clp-custom-login-page' ),
                    'description' => 'Set Background Color opacity to see this effect.',
                    'type'        => 'range',
                    'default'     => $default['clp_layout-background-blur'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 0,
                        'max'    => 40,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_negative_int'),
                    'active_callback' => array( $this, 'is_content_half_width'),
                    'css'			=> array(
                        'selector'	=> '.clp-content-half .clp-content::after',
                        'dependency'=>  array('clp_layout-width', '100', '!=' ),
                        'property'	=> '-webkit-backdrop-filter, backdrop-filter',
                        'css_value'    => 'blur(%VALUE%px)'
                    )
                ),
                array(
                    'id'          => 'clp_layout-content-skew',
                    'label'       => esc_html__( 'Skew', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_layout-content-skew'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => -30,
                        'max'    => 30,
                        'step'   => 1,
                        'suffix' => '%', 
                    ),
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_negative_int'),
                    'active_callback' => array( $this, 'is_content_half_width'),
                    'css'			=> array(
                        'selector'	=> '.clp-content-half .clp-content::after',
                        'dependency'=>  array('clp_layout-width', '100', '!=' ),
                        'property'	=> 'transform',
                        'css_value'    => 'skewX(%VALUE%deg)'
                    )
                ),
            ),
        );

        $settings['logo'] = array(
            'title'       => esc_html__( 'Logo', 'clp-custom-login-page' ),
            'description' => '',
            'fields'      => array(
                array(
                    'id'            => 'clp_logo-separator',
                    'label'         => esc_html__( 'Logo Type', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'          => 'clp_logo',
                    'label'       => esc_html__( 'Choose Logo Type', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'radio',
                    'default'     => $default['clp_logo'],
                    'choices'	=> array (
                        'none' 	    => esc_html__( 'Disabled', 'clp-custom-login-page' ),
                        'text' 	    => esc_html__( 'Text Logo', 'clp-custom-login-page' ),
                        'image' 	=> esc_html__( 'Image Logo', 'clp-custom-login-page' ),
                    ),
                    'transport'		=> 'refresh',
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_select' ),
                ),
                array(
                    'id'            => 'clp_logo-settings-separator',
                    'label'         => esc_html__( 'Logo Settings', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                    'active_callback' => array( $this, 'is_logo_enabled'),
                ),
                array(
                    'id'            => 'clp_logo-text',
                    'label'         => esc_html__( 'Logo Text', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'text',
                    'default'       => get_bloginfo('name'),
                    'transport'		=> 'postMessage',
                    'active_callback' => array( $this, 'is_logo_text'),
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                array(
                    'id'          => 'clp_logo-text-color',
                    'label'       => esc_html__( 'Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_logo-text-color'],
                    'transport'		=> 'postMessage',
                    'active_callback' => array( $this, 'is_logo_text'),
                    'sanitize_callback' => 'sanitize_hex_color',
                    'css'			=> array(
                        'selector'	=> '.clp-login-logo a, .clp-login-logo a:hover, .clp-login-logo a:focus',
                        'property'	=> 'color',
                        'suffix'    => '!important',
                        'dependency'=>  array('clp_logo', 'text'),
                    )
                ),

                array(
                    'id'            => 'clp_logo-image',
                    'label'         => esc_html__( 'Upload Custom Logo', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'media',
                    'mime_type'     => 'image',
                    'default'       => $default['clp_logo-image'],
                    'transport'		=> 'refresh',
                    'active_callback' => array( $this, 'is_logo_image'),
                    'sanitize_callback' => 'absint',
                ),
                array(
                    'id'          => 'clp_logo-image-width',
                    'label'       => esc_html__( 'Max Width', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_logo-image-width'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 0,
                        'max'    => 500,
                        'step'   => 5,
                        'suffix' => 'px', 
                    ),
                    'active_callback' => array( $this, 'is_logo_image'),
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_negative_int'),
                    'css'			=> array(
                        'selector'	=> '.clp-login-logo img',
                        'property'	=> 'max-width',
                        'suffix'	=> 'px',
                        'dependency'=>  array('clp_logo', 'image'),
                    )
                ),
                array(
                    'id'          => 'clp_logo-image-height',
                    'label'       => esc_html__( 'Max Height', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_logo-image-height'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 0,
                        'max'    => 500,
                        'step'   => 5,
                        'suffix' => 'px', 
                    ),
                    'active_callback' => array( $this, 'is_logo_image'),
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_negative_int'),
                    'css'			=> array(
                        'selector'	=> '.clp-login-logo img',
                        'property'	=> 'max-height',
                        'suffix'	=> 'px',
                        'dependency'=>  array('clp_logo', 'image'),
                    )
                ),
                array(
                    'id'          => 'clp_logo-spacing-top',
                    'label'       => esc_html__( 'Top Spacing', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_logo-spacing-top'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 0,
                        'max'    => 200,
                        'step'   => 5,
                        'suffix' => 'px', 
                    ),
                    'active_callback' => array( $this, 'is_logo_enabled'),
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_negative_int'),
                    'css'			=> array(
                        'selector'	=> '.login .clp-login-logo',
                        'property'	=> 'margin-top',
                        'suffix'    => 'px'
                    )
                ),
                array(
                    'id'          => 'clp_logo-spacing-bottom',
                    'label'       => esc_html__( 'Bottom Spacing', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_logo-spacing-bottom'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 0,
                        'max'    => 200,
                        'step'   => 5,
                        'suffix' => 'px', 
                    ),
                    'active_callback' => array( $this, 'is_logo_enabled'),
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_negative_int'),
                    'css'			=> array(
                        'selector'	=> '.login .clp-login-logo',
                        'property'	=> 'margin-bottom',
                        'suffix'    => 'px'
                    )
                ),
                array(
                    'id'            => 'clp_logo-url',
                    'label'         => esc_html__( 'Change Logo Link', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'text',
                    'default'       => get_bloginfo('url'),
                    'transport'		=> 'postMessage',
                    'active_callback' => array( $this, 'is_logo_enabled'),
                    'sanitize_callback' => 'esc_url',
                ),
                array(
                    'id'            => 'clp_logo-contained',
                    'label'         => esc_html__( 'Logo inside Form Container?', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'toggle',
                    'transport'		=> 'postMessage',
                    'default'       => $default['clp_logo-contained'],
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_toggle' ),
                    'active_callback' => array( $this, 'is_logo_enabled'),
                ),
                array(
                    'id'            => 'clp_logo-typography-separator',
                    'label'         => esc_html__( 'Logo Typography', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                    'active_callback' => array( $this, 'is_logo_text'),
                ),
                array(
                    'id'          => 'clp_logo-google_fonts',
                    'description' => '',
                    'type'        => 'google-fonts',
                    'transport'	    => 'postMessage',
                    'default'       => $default['clp_logo-google_fonts'],
                    'active_callback' => array( $this, 'is_logo_text'),
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                array(
                    'id'          => 'clp_logo-text-font_size',
                    'label'       => esc_html__( 'Logo Font Size', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_logo-text-font_size'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 10,
                        'max'    => 150,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'active_callback' => array( $this, 'is_logo_text'),
                    'sanitize_callback' => 'absint',
                    'css'			=> array(
                        'selector'	=> '.login .clp-login-logo',
                        'property'	=> 'font-size',
                        'suffix'	=> 'px',
                        'dependency'=>  array('clp_logo', 'text'),
                    )
                ),
                array(
                    'id'          => 'clp_logo-text-letter_spacing',
                    'label'       => esc_html__( 'Letter Spacing', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_logo-text-letter_spacing'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => -50,
                        'max'    => 50,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'active_callback' => array( $this, 'is_logo_text'),
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_negative_int'),
                    'css'			=> array(
                        'selector'	=> '.clp-login-logo',
                        'property'	=> 'letter-spacing',
                        'suffix'    => 'px'
                    )
                ),
            ),
        );

        $settings['background'] = array(
            'title'       => esc_html__( 'Background', 'clp-custom-login-page' ),
            'description' => '',
            'fields'      => array(
                array(
                    'id'            => 'clp_background-separator',
                    'label'         => esc_html__( 'Background', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'          => 'clp_background',
                    'label'       => esc_html__( 'Select Background', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'radio',
                    'default'     => $default['clp_background'],
                    'choices'	=> array (
                        'color' 	=> esc_html__( 'Color', 'clp-custom-login-page' ),
                        'gradient' 	=> esc_html__( 'Gradient', 'clp-custom-login-page' ),
                        'pattern' 	=> esc_html__( 'Pattern', 'clp-custom-login-page' ),
                        'image' 	=> esc_html__( 'Image', 'clp-custom-login-page' ),
                        'video' 	=> esc_html__( 'Video', 'clp-custom-login-page' ),
                    ),
                    'transport'		=> 'refresh',
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_select' ),
                ),
                array(
                    'id'            => 'clp_background-settings-separator',
                    'label'         => esc_html__( 'Background Settings', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'          => 'clp_background-color',
                    'label'       => esc_html__( 'Background Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_background-color'],
                    'transport'		=> 'postMessage',
                    'active_callback' =>  array( $this, 'is_background_color'),
                    'sanitize_callback' => 'sanitize_hex_color',
                    'css'			=> array(
                        'selector'	=> '.login-background',
                        'property'	=> 'background-color',
                        'dependency'=>  array('clp_background', 'color')
                    )
                ),
                array(
                    'id'          => 'clp_background-gradient-color1',
                    'label'       => esc_html__( 'Gradient Start Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_background-gradient-color1'],
                    'transport'		=> 'postMessage',
                    'active_callback' =>  array( $this, 'is_background_gradient'),
                    'sanitize_callback' => 'sanitize_hex_color',
                    'css'			=> array(
                        'selector'	=> '.login-background',
                        'property'	=> 'background',
                        'dependency'=>  array('clp_background', 'gradient'),
                        'css_value'     => sprintf('linear-gradient(%sdeg, %s %s%%, %%VALUE%% %s%%)', get_option('clp_background-gradient-angle', '30'), get_option('clp_background-gradient-color2', '#f1f1f1'), get_option('clp_background-gradient-color1-position', '0'), get_option('clp_background-gradient-color2-position', '100'))
                    )
                ),
                array(
                    'id'          => 'clp_background-gradient-color1-position',
                    'label'       => esc_html__( 'Gradient Start Position', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_background-gradient-color1-position'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 0,
                        'max'    => 100,
                        'step'   => 1,
                        'suffix' => '%', 
                    ),
                    'active_callback' =>  array( $this, 'is_background_gradient'),
                    'sanitize_callback' => 'absint',
                ),
                array(
                    'id'          => 'clp_background-gradient-color2',
                    'label'       => esc_html__( 'Gradient End Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_background-gradient-color2'],
                    'transport'		=> 'postMessage',
                    'active_callback' =>  array( $this, 'is_background_gradient'),
                    'sanitize_callback' => 'sanitize_hex_color',
                ),
                array(
                    'id'          => 'clp_background-gradient-color2-position',
                    'label'       => esc_html__( 'Gradient End Position', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_background-gradient-color2-position'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 0,
                        'max'    => 100,
                        'step'   => 1,
                        'suffix' => '%', 
                    ),
                    'active_callback' =>  array( $this, 'is_background_gradient'),
                    'sanitize_callback' => 'absint',
                ),
                array(
                    'id'          => 'clp_background-gradient-angle',
                    'label'       => esc_html__( 'Gradient Angle', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_background-gradient-angle'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 1,
                        'max'    => 360,
                        'step'   => 1,
                        'suffix' => 'deg', 
                    ),
                    'active_callback' =>  array( $this, 'is_background_gradient'),
                    'sanitize_callback' => 'absint',
                ),
                array(
                    'id'          => 'clp_background-pattern',
                    'label'       => esc_html__( 'Select Pattern', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'select',
                    'default'     => $default['clp_background-pattern'],
                    'choices'	=> array (
                        'fabric' 				=> esc_html__( 'Fabric', 'clp-custom-login-page' ),
                        'gray_sand' 			=> esc_html__( 'Gray Sand', 'clp-custom-login-page' ),
                        'green_dust_scratch' 	=> esc_html__( 'Green Dust Scratch', 'clp-custom-login-page' ),
                        'mirrored_squares' 		=> esc_html__( 'Mirrored Squares', 'clp-custom-login-page' ),
                        'noisy' 				=> esc_html__( 'Noisy', 'clp-custom-login-page' ),
                        'photography' 			=> esc_html__( 'Photography', 'clp-custom-login-page' ),
                        'playstation' 			=> esc_html__( 'Playstation', 'clp-custom-login-page' ),
                        'sakura' 				=> esc_html__( 'Sakura', 'clp-custom-login-page' ),
                        'white_sand' 			=> esc_html__( 'White Sand', 'clp-custom-login-page' ),
                        'white_texture' 		=> esc_html__( 'White Texture', 'clp-custom-login-page' ),
                        'custom' 		        => esc_html__( 'Custom', 'clp-custom-login-page' ),
                    ),
                    'transport'		=> 'postMessage',
                    'active_callback' =>  array( $this, 'is_background_pattern'),
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_select' ),
                    'css'			=> array(
                        'selector'	=> '.login-background',
                        'property'	=> 'background-image',
                        'dependency'=>  array('clp_background', 'pattern'),
                        'css_value'     => 'url("'.CLP_PLUGIN_PATH.'assets/img/patterns/%VALUE%.png")',
                    )
                ),
                array(
                    'id'          => 'clp_background-pattern-custom',
                    'label'       => esc_html__( 'Upload Custom Pattern', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'media',
                    'mime_type'    => 'image',
                    'default'     => $default['clp_background-pattern-custom'],
                    'transport'		=> 'postMessage',
                    'active_callback' =>  array( $this, 'is_custom_pattern'),
                    'sanitize_callback' =>  'absint',
                    'css'			=> array(
                        'selector'	=> '.clp-pattern-custom .login-background',
                        'property'	=> 'background-image',
                        'dependency'=>  array('clp_background', 'pattern'),
                        'css_value'     => 'url("%VALUE%")',
                        'get_value' => array('wp_get_attachment_url', get_option( 'clp_background-pattern-custom', '' ) ),
        
                    )
                ),
                array(
                    'id'          => 'clp_background-unsplash',
                    'label'       => esc_html__( 'Unsplash Gallery', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'unsplash',
                    'default'     => $default['clp_background-unsplash'],
                    'transport'		=> 'postMessage',
                    'active_callback' =>  array( $this, 'is_background_image'),
                    'sanitize_callback' =>  'sanitize_text_field',
                    'css'			=> array(
                        'selector'	=> '.login-background',
                        'property'	=> 'background-image',
                        'dependency'=>  array('clp_background', 'image'),
                        'css_value'     => 'url("%VALUE%")',
                        'get_value' => array('CLP_Unsplash_Api::get_unsplash_image', get_option( 'clp_background-unsplash', '' ) ),
                    )
                ),
                array(
                    'id'          => 'clp_background-image',
                    'label'       => esc_html__( 'Upload Custom Image', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'media',
                    'mime_type'    => 'image',
                    'default'     => $default['clp_background-image'],
                    'transport'		=> 'postMessage',
                    'active_callback' =>  array( $this, 'is_background_image'),
                    'sanitize_callback' =>  'absint',
                    'css'			=> array(
                        'selector'	=> '.login-background',
                        'property'	=> 'background-image',
                        'dependency'=>  array('clp_background', 'image'),
                        'css_value'     => 'url("%VALUE%")',
                        'get_value' => array('wp_get_attachment_image_src', get_option( 'clp_background-image', '' ), CLP_Helper_Functions::get_image_size(), 1),
                        'return_value' => array('array', 0)
                    )
                ),
                array(
                    'id'          => 'clp_background-video_local',
                    'label'       => esc_html__( 'Upload Custom Video', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'media',
                    'mime_type'    => 'video',
                    'default'     => $default['clp_background-video_local'],
                    'transport'		=> 'refresh',
                    'active_callback' =>  array( $this, 'is_background_video'),
                    'sanitize_callback' =>  'absint',
                ),
                array(
                    'id'          => 'clp_background-video_yt',
                    'label'       => esc_html__( 'Insert YouTube URL', 'clp-custom-login-page' ),
                    'description' => esc_html__( 'YouTube background video is not supported on mobile devices. Image placeholder will be used instead.', 'clp-custom-login-page' ),
                    'type'        => 'text',
                    'default'     => $default['clp_background-video_yt'],
                    'transport'		=> 'refresh',
                    'active_callback' =>  array( $this, 'is_background_video'),
                    'sanitize_callback' =>  'esc_url',
                ),
                array(
                    'id'          => 'clp_background-video_loop',
                    'label'       => esc_html__( 'Loop video', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'toggle',
                    'default'     => $default['clp_background-video_loop'],
                    'transport'		=> 'refresh',
                    'active_callback' =>  array( $this, 'is_background_video'),
                    'sanitize_callback' =>  'absint',
                ),
                array(
                    'id'          => 'clp_background-video_thumb',
                    'label'       => esc_html__( 'Upload Video Thumbnail', 'clp-custom-login-page' ),
                    'description' => esc_html__( 'This image will be used as image placeholder before the video loads and also displayed on mobile devices.', 'clp-custom-login-page' ),
                    'type'        => 'media',
                    'mime_type'    => 'image',
                    'default'     => $default['clp_background-video_thumb'],
                    'transport'		=> 'postMessage',
                    'active_callback' =>  array( $this, 'is_background_video'),
                    'sanitize_callback' =>  'absint',
                ),
                array(
                    'id'            => 'clp_background-overlay-separator',
                    'label'         => esc_html__( 'Background Overlay', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'            => 'clp_background-overlay-enable',
                    'label'         => esc_html__( 'Enable Overlay', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'toggle',
                    'transport'		=> 'postMessage',
                    'default'       => $default['clp_background-overlay-enable'],
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_toggle' ),
                ),
                array(
                    'id'          => 'clp_background-overlay-color',
                    'label'       => esc_html__( 'Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'alpha-color',
                    'default'     => $default['clp_background-overlay-color'],
                    'transport'		=> 'postMessage',
                    'active_callback' => array( $this, 'is_overlay_enabled' ),
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_alpha_color'),
                    'css'			=> array(
                        'selector'	=> '.login-overlay',
                        'property'	=> 'background-color',
                        'dependency'=>  array('clp_background-overlay-enable', '1'),
                    )
                ),

                array(
                    'id'            => 'clp_background-effects-separator',
                    'label'         => esc_html__( 'Background Effects', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'          => 'clp_background-blur',
                    'label'       => esc_html__( 'Blur', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_background-blur'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 0,
                        'max'    => 10,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_negative_int' ),
                    'css'			=> array(
                        'selector'	=> '.login-background',
                        'property'	=> 'filter',
                        'css_value'     => 'blur(%VALUE%px)',
                    )
                ),
            )
        );

        $settings['form'] = array(
            'title'       => esc_html__( 'Form', 'clp-custom-login-page' ),
            'description' => '',
            'fields'      => array(
                array(
                    'id'            => 'clp_form-separator',
                    'label'         => esc_html__( 'Customize Form', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'          => 'clp_form-width',
                    'label'       => esc_html__( 'Width', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_form-width'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 300,
                        'max'    => 1000,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => 'absint',
                    'css'			=> array(
                        'selector'	=> '.clp-login form, .clp-form-footer, .clp-login .message, .clp-login #login_error',
                        'property'	=> 'width',
                        'suffix'    => 'px'
                    ),
                    'css2'			=> array(
                        'selector'	=> '.has-register-content #registerform',
                        'property'  => 'flex',
                        'css_value'    => '0 0 %VALUE%px'
                    )
                ),
                array(
                    'id'          => 'clp_form-height',
                    'label'       => esc_html__( 'Minimum Height', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_form-height'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 200,
                        'max'    => 1000,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => 'absint',
                    'css'			=> array(
                        'selector'	=> '.login #login',
                        'property'	=> 'min-height',
                        'suffix'    => 'px'
                    )
                ),
                array(
                    'id'          => 'clp_form-padding',
                    'label'       => esc_html__( 'Form Spacing', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_form-padding'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 0,
                        'max'    => 150,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => 'absint',
                    'css'			=> array(
                        'selector'	=> '.login #login',
                        'property'	=> 'padding',
                        'suffix'    => 'px'
                    )
                ),
                array(
                    'id'          => 'clp_form-background',
                    'label'       => esc_html__( 'Form Background Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'alpha-color',
                    'default'     => $default['clp_form-background'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => array ( 'CLP_Sanitize', 'sanitize_alpha_color'),
                    'css'			=> array(
                        'selector'	=> '.login #login',
                        'property'	=> 'background-color',
                    )
                ),
                array(
                    'id'          => 'clp_form-text_color',
                    'label'       => esc_html__( 'Form Text Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_form-text_color'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => array ( 'CLP_Sanitize', 'sanitize_alpha_color'),
                    'css'			=> array(
                        'selector'	=> '.clp-form-container, .clp-form-container a',
                        'property'	=> 'color',
                    )
                ),
                array(
                    'id'          => 'clp_form-blur',
                    'label'       => esc_html__( 'Backdrop Blur', 'clp-custom-login-page' ),
                    'description' => 'Too see this effect, make sure Form Background Color is opaque.',
                    'type'        => 'range',
                    'default'     => $default['clp_form-blur'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 0,
                        'max'    => 40,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_negative_int'),
                    'css'			=> array(
                        'selector'	=> '.login #login',
                        'property'	=> '-webkit-backdrop-filter, backdrop-filter',
                        'css_value'    => 'blur(%VALUE%px)'
                    )
                ),
                array(
                    'id'          => 'clp_form-border_radius',
                    'label'       => esc_html__( 'Border Radius', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_form-border_radius'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 0,
                        'max'    => 100,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_negative_int'),
                    'css'			=> array(
                        'selector'	=> '.login #login',
                        'property'	=> 'border-radius',
                        'suffix'    => 'px'
                    )
                ),
                array(
                    'id'            => 'clp_form_typography-separator',
                    'label'         => esc_html__( 'Typography', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'          => 'clp_form_typography-google_fonts',
                    'description' => '',
                    'type'        => 'google-fonts',
                    'transport'	    => 'postMessage',
                    'default'       => $default['clp_form_typography-google_fonts'],
                    'sanitize_callback' => 'sanitize_text_field',
                ),

                array(
                    'id'            => 'clp_form_borders-separator',
                    'label'         => esc_html__( 'Form Borders', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'            => 'clp_form-borders',
                    'label'         => esc_html__( 'Enable Borders', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'toggle',
                    'transport'		=> 'postMessage',
                    'default'       => $default['clp_form-borders'],
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_toggle' ),
                ),
                array(
                    'id'          => 'clp_form-border_color',
                    'label'       => esc_html__( 'Border Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_form-border_color'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'active_callback'=>  array( $this, 'is_form_borders' ),
                ),
                array(
                    'id'          => 'clp_form-border_width',
                    'label'       => esc_html__( 'Border Width', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_form-border_width'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 1,
                        'max'    => 10,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => 'absint',
                    'active_callback'=>  array( $this, 'is_form_borders' ),
                    'css'			=> array(
                        'selector'	=> '.login #login',
                        'property'	=> 'border',
                        'css_value'    => '%VALUE%px solid '.get_option('clp_form-border_color', '#7e8993'),
                        'dependency'=>  array('clp_form-borders', '1'),
                    )
                ),
                array(
                    'id'            => 'clp_form_shadow-separator',
                    'label'         => esc_html__( 'Form Shadow', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'            => 'clp_form-shadow',
                    'label'         => esc_html__( 'Enable Shadow', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'toggle',
                    'transport'		=> 'postMessage',
                    'default'       => $default['clp_form-shadow'],
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_toggle' ),
                ),
                array(
                    'id'          => 'clp_form-shadow-horizontal_length',
                    'label'       => esc_html__( 'Horizontal Length', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_form-shadow-horizontal_length'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => -200,
                        'max'    => 200,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_negative_int'),
                    'active_callback'=>  array( $this, 'is_form_shadow' ),
                ),
                array(
                    'id'          => 'clp_form-shadow-vertical_length',
                    'label'       => esc_html__( 'Vertical Length', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_form-shadow-vertical_length'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => -200,
                        'max'    => 200,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    // 'sanitize_callback' => 'absint',
                    'active_callback'=>  array( $this, 'is_form_shadow' ),
                ),
                array(
                    'id'          => 'clp_form-shadow-blur_radius',
                    'label'       => esc_html__( 'Blur Radius', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_form-shadow-blur_radius'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 0,
                        'max'    => 200,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_negative_int'),
                    'active_callback'=>  array( $this, 'is_form_shadow' ),
                ),
                array(
                    'id'          => 'clp_form-shadow-spread_radius',
                    'label'       => esc_html__( 'Spread Radius', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_form-shadow-spread_radius'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => -200,
                        'max'    => 200,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_negative_int'),
                    'active_callback'=>  array( $this, 'is_form_shadow' ),
                ),
                array(
                    'id'          => 'clp_form-shadow-color',
                    'label'       => esc_html__( 'Shadow Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'alpha-color',
                    'default'     => $default['clp_form-shadow-color'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_alpha_color' ),
                    'active_callback'=>  array( $this, 'is_form_shadow' ),
                    'css'			=> array(
                        'selector'	=> '.login #login',
                        'property'	=> 'box-shadow',
                        'css_value'    => get_option('clp_form-shadow-horizontal_length', '10').'px '.get_option('clp_form-shadow-vertical_length', '10').'px '.get_option('clp_form-shadow-blur_radius', '40').'px '.get_option('clp_form-shadow-spread_radius', '-10').'px %VALUE%',
                        'dependency'=>  array('clp_form-shadow', '1'),
                    )
                ),
            ),
        );

        $settings['inputs'] = array(
            'title'       => esc_html__( 'Inputs', 'clp-custom-login-page' ),
            'description' => '',
            'fields'      => array(
                array(
                    'id'            => 'clp_input-separator',
                    'label'         => esc_html__( 'Customize Inputs', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'          => 'clp_input-background_color',
                    'label'       => esc_html__( 'Background Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'alpha-color',
                    'default'     => $default['clp_input-background_color'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_alpha_color' ),
                    'css'			=> array(
                        'selector'	=> '.login .clp-login-form-container form .input,  .checkmark',
                        'property'	=> 'background',
                    ),
                    // 'css2'			=> array(
                    //     'selector'	=> '.login input:-webkit-autofill',
                    //     'property'	=> '-webkit-box-shadow',
                    //     'css_value' => '0 0 0px 1000px %VALUE% inset'
                    // )
                ),
                array(
                    'id'          => 'clp_input-background_color_focus',
                    'label'       => esc_html__( 'Focus Background Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'alpha-color',
                    'default'     => $default['clp_input-background_color_focus'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_alpha_color' ),
                    'css'			=> array(
                        'selector'	=> '.login .clp-login-form-container form .input:focus, .login form input[type="checkbox"]:focus"',
                        'property'	=> 'background',
                    ),
                    'css2'			=> array(
                        'selector'	=> '.login input:-webkit-autofill:focus',
                        'property'	=> '-webkit-box-shadow',
                        'css_value' => '0 0 0px 1000px %VALUE% inset'
                    )
                ),
                array(
                    'id'          => 'clp_input-color',
                    'label'       => esc_html__( 'Text Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_input-color'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'css'			=> array(
                        'selector'	=> '.login .clp-login-form-container form .input, .login .button.wp-hide-pw .dashicons, .input::placeholder',
                        'property'	=> 'color',
                    ),
                    'css2'			=> array(
                        'selector'	=> '.login input:-webkit-autofill',
                        'property'	=> '-webkit-text-fill-color',
                    )
                ),
                array(
                    'id'          => 'clp_input-color_focus',
                    'label'       => esc_html__( 'Focus Text Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_input-color_focus'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'css'			=> array(
                        'selector'	=> '.login .clp-login-form-container form .input:focus',
                        'property'	=> 'color',
                    ),
                    'css2'			=> array(
                        'selector'	=> '.login input:-webkit-autofill:focus',
                        'property'	=> '-webkit-text-fill-color',
                    )
                ),
                array(
                    'id'          => 'clp_input-font_size_input',
                    'label'       => esc_html__( 'Input Font Size', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_input-font_size_input'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 10,
                        'max'    => 50,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'css'			=> array(
                        'selector'	=> '.login .clp-login-form-container form .input',
                        'property'	=> 'font-size',
                        'suffix'    => 'px'
                    )
                ),
                array(
                    'id'          => 'clp_input-height',
                    'label'       => esc_html__( 'Minimum Height', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_input-height'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 1,
                        'max'    => 100,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'css'			=> array(
                        'selector'	=> '.login .clp-login-form-container form .input, .login .clp-form-container .button.wp-hide-pw',
                        'property'	=> 'min-height',
                        'suffix'    => 'px'
                    )
                ),
                array(
                    'id'          => 'clp_input-border_width',
                    'label'       => esc_html__( 'Border Width', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_input-border_width'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 0,
                        'max'    => 10,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_negative_int'),
                    'css'			=> array(
                        'selector'	=> '.login .clp-login-form-container form .input,  .checkmark',
                        'property'	=> 'border',
                        'css_value'    => '%VALUE%px solid '.get_option('clp_input-border_color', '#7e8993'),
                    )
                ),
                array(
                    'id'          => 'clp_input-border_color',
                    'label'       => esc_html__( 'Border Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_input-border_color'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                ),
                array(
                    'id'          => 'clp_input-border_color_focus',
                    'label'       => esc_html__( 'Focus Border Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => '#007cba',
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'css'			=> array(
                        'selector'	=> '.login .clp-login-form-container form .input:focus',
                        'property'	=> 'border-color',
                    )
                ),
                array(
                    'id'          => 'clp_input-border_radius',
                    'label'       => esc_html__( 'Border Radius', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_input-border_radius'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 0,
                        'max'    => 50,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_negative_int'),
                    'css'			=> array(
                        'selector'	=> '.login .clp-login-form-container form .input, .checkmark',
                        'property'	=> 'border-radius',
                        'suffix'	=> 'px',
                    )
                ),
                array(
                    'id'          => 'clp_input-text_indent',
                    'label'       => esc_html__( 'Text Indentation', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_input-text_indent'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 0,
                        'max'    => 30,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_negative_int'),
                    'css'			=> array(
                        'selector'	=> '.login .clp-login-form-container form .input',
                        'property'	=> 'text-indent',
                        'suffix'	=> 'px',
                    )
                ),
                array(
                    'id'            => 'clp_input-showpassword',
                    'label'         => esc_html__( 'Display "Show Password" Icon?', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'toggle',
                    'transport'		=> 'postMessage',
                    'default'       => $default['clp_input-showpassword'],
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_toggle' ),
                ),
                array(
                    'id'            => 'clp_input_label-separator',
                    'label'         => esc_html__( 'Customize Labels', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'            => 'clp_input-label_display',
                    'label'         => esc_html__( 'Display Labels?', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'toggle',
                    'transport'		=> 'postMessage',
                    'default'       => $default['clp_input-label_display'],
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_toggle' ),
                ),
                array(
                    'id'          => 'clp_input-font_size_label',
                    'label'       => esc_html__( 'Label Font Size', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_input-font_size_label'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 10,
                        'max'    => 50,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'active_callback'=>  array( $this, 'is_label' ),
                    'css'			=> array(
                        'selector'	=> '.login label',
                        'property'	=> 'font-size',
                        'suffix'    => 'px'
                    )
                ),
                array(
                    'id'          => 'clp_input-label_color',
                    'label'       => esc_html__( 'Labels Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_input-label_color'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'active_callback'=>  array( $this, 'is_label' ),
                    'css'			=> array(
                        'selector'	=> '.login label',
                        'property'	=> 'color',
                    ),
                    'css2'			=> array(
                        'property'	=> 'border',
                        'selector'	=> '.forgetmenot .checkmark:after',
                        'css_value'    => 'solid %VALUE%'
                    )
                ),
                array(
                    'id'          => 'clp_input-label_font_weight',
                    'label'       => esc_html__( 'Label Font Weight', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'select',
                    'default'     => $default['clp_input-label_font_weight'],
                    'choices'	=> array (
                        '400' 		=> esc_html__( 'Regular', 'clp-custom-login-page' ),
                        '700' 	=> esc_html__( 'Bold', 'clp-custom-login-page' ),
                    ),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_select' ),
                    'active_callback'=>  array( $this, 'is_label' ),
                    'css'			=> array(
                        'selector'	=> '.wp-core-ui .clp-form-container label',
                        'property'	=> 'font-weight',
                    )
                ),
                array(
                    'id'            => 'clp_input-login_input_text',
                    'label'         => esc_html__( '"Username or Email Address"', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'text',
                    'default'       => __('Username or Email Address', 'clp-custom-login-page'),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                array(
                    'id'            => 'clp_input-login_input_text_username',
                    'label'         => esc_html__( '"Username"', 'clp-custom-login-page' ),
                    'description'   => esc_html__( 'Visible on Registration Form', 'clp-custom-login-page' ),
                    'type'          => 'text',
                    'default'       => __('Username', 'clp-custom-login-page'),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_text_field',
                    'active_callback'=>  array( $this, 'is_register' ),
                ),
                array(
                    'id'            => 'clp_input-login_input_text_email',
                    'label'         => esc_html__( '"Email" Text', 'clp-custom-login-page' ),
                    'description'   => esc_html__( 'Visible on Registration Form', 'clp-custom-login-page' ),
                    'type'          => 'text',
                    'default'       => __('Email', 'clp-custom-login-page'),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_text_field',
                    'active_callback'=>  array( $this, 'is_register' ),
                ),
                array(
                    'id'            => 'clp_input-password_input_text',
                    'label'         => esc_html__( '"Password" Text', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'text',
                    'default'       => __('Password', 'clp-custom-login-page'),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                array(
                    'id'            => 'clp_input-remember-separator',
                    'label'         => esc_html__( 'Remember Me', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'            => 'clp_input-remember',
                    'label'         => esc_html__( 'Display "Remember Me" Checkbox?', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'toggle',
                    'transport'		=> 'postMessage',
                    'default'       => $default['clp_input-remember'],
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_toggle' ),
                ),
                array(
                    'id'            => 'clp_input-remember_text',
                    'label'         => esc_html__( 'Custom "Remember Me" Text', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'text',
                    'default'       => __('Remember Me', 'clp-custom-login-page'),
                    'transport'		=> 'postMessage',
                    'active_callback' => array( $this, 'is_rememberme'),
                    'sanitize_callback' => 'sanitize_text_field',
                ),

            ),
        );

        $settings['button'] = array(
            'title'       => esc_html__( 'Button', 'clp-custom-login-page' ),
            'description' => '',
            'fields'      => array(
                array(
                    'id'            => 'clp_button-separator',
                    'label'         => esc_html__( 'Customize Buttons', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'            => 'clp_button-text',
                    'label'         => esc_html__( 'Log In Button Text', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'text',
                    'default'       => __('Log In', 'clp-custom-login-page'),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                array(
                    'id'            => 'clp_button-get_new_password_text',
                    'label'         => esc_html__( 'Get New Password Button Text', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'text',
                    'default'       => __('Get New Password', 'clp-custom-login-page'),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                array(
                    'id'          => 'clp_button-background_color',
                    'label'       => esc_html__( 'Background Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'alpha-color',
                    'default'     => $default['clp_button-background_color'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => array('CLP_Sanitize', 'sanitize_alpha_color'),
                    'css'			=> array(
                        'selector'	=> '.wp-core-ui .clp-form-container .button-primary',
                        'property'	=> 'background-color',
                    )
                ),
                array(
                    'id'          => 'clp_button-background_color_hover',
                    'label'       => esc_html__( 'Background Hover Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'alpha-color',
                    'default'     => $default['clp_button-background_color_hover'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => array('CLP_Sanitize', 'sanitize_alpha_color'),
                    'css'			=> array(
                        'selector'	=> '.wp-core-ui .clp-form-container .button-primary:hover, .wp-core-ui .clp-form-container .button-primary:active, .wp-core-ui .clp-form-container .button-primary:focus',
                        'property'	=> 'background-color',
                    ),
                    // 'css2'			=> array(
                    //     'selector'	=> '.clp-login-form-container a:hover, .clp-login-form-container a:focus',
                    //     'property'	=> 'color',
                    // )
                ),
                array(
                    'id'          => 'clp_button-text_color',
                    'label'       => esc_html__( 'Text Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_button-text_color'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'css'			=> array(
                        'selector'	=> '.wp-core-ui .clp-form-container .button-primary',
                        'property'	=> 'color',
                    )
                ),
                array(
                    'id'          => 'clp_button-text_color_hover',
                    'label'       => esc_html__( 'Text Hover Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_button-text_color_hover'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'css'			=> array(
                        'selector'	=> '.wp-core-ui .clp-form-container .button-primary:hover, .wp-core-ui .clp-form-container .button-primary:active, .wp-core-ui .clp-form-container .button-primary:focus',
                        'property'	=> 'color',
                    )
                ),
                array(
                    'id'          => 'clp_button-font_weight',
                    'label'       => esc_html__( 'Font Weight', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'select',
                    'default'     => $default['clp_button-font_weight'],
                    'choices'	=> array (
                        '400' 		=> esc_html__( 'Regular', 'clp-custom-login-page' ),
                        '700' 	=> esc_html__( 'Bold', 'clp-custom-login-page' ),
                    ),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_select' ),
                    'css'			=> array(
                        'selector'	=> '.wp-core-ui .clp-form-container .button-primary',
                        'property'	=> 'font-weight',
                    )
                ),
                array(
                    'id'          => 'clp_button-font_size',
                    'label'       => esc_html__( 'Font Size', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_button-font_size'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 10,
                        'max'    => 50,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => 'absint',
                    'css'			=> array(
                        'selector'	=> '.wp-core-ui .clp-form-container .button-primary',
                        'property'	=> 'font-size',
                        'suffix'    => 'px'
                    )
                ),
                array(
                    'id'          => 'clp_button-width',
                    'label'       => esc_html__( 'Width', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_button-width'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 1,
                        'max'    => 100,
                        'step'   => 1,
                        'suffix' => '%', 
                    ),
                    'sanitize_callback' => 'absint',
                    'css'			=> array(
                        'selector'	=> '.wp-core-ui .clp-form-container .button-primary',
                        'property'	=> 'min-width',
                        'suffix'    => '%'
                    )
                ),
                array(
                    'id'          => 'clp_button-height',
                    'label'       => esc_html__( 'Minimum Height', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_button-height'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 1,
                        'max'    => 100,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => 'absint',
                    'css'			=> array(
                        'selector'	=> '.wp-core-ui .clp-form-container .button-primary.button-large',
                        'property'	=> 'min-height',
                        'suffix'    => 'px'
                    )
                ),
                array(
                    'id'          => 'clp_button-align',
                    'label'       => esc_html__( 'Alignment', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'select',
                    'default'     => $default['clp_button-align'],
                    'choices'	=> array (
                        'left' 		=> esc_html__( 'Left', 'clp-custom-login-page' ),
                        'center' 	=> esc_html__( 'Center', 'clp-custom-login-page' ),
                        'right' 	=> esc_html__( 'Right', 'clp-custom-login-page' ),
                    ),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_select' ),
                    'css'			=> array(
                        'selector'	=> '#login form p.submit',
                        'property'	=> 'text-align',
                    )
                ),
                array(
                    'id'          => 'clp_button-border_radius',
                    'label'       => esc_html__( 'Border Radius', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_button-border_radius'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 0,
                        'max'    => 50,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_negative_int'),
                    'css'			=> array(
                        'selector'	=> '.wp-core-ui .clp-form-container .button-primary',
                        'property'	=> 'border-radius',
                        'suffix'	=> 'px',
                    )
                ),
                array(
                    'id'          => 'clp_button-border_width',
                    'label'       => esc_html__( 'Border Width', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_button-border_width'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 0,
                        'max'    => 10,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_negative_int'),
                    'css'			=> array(
                        'selector'	=> '.wp-core-ui .clp-form-container .button-primary',
                        'property'	=> 'border',
                        'css_value'    => '%VALUE%px solid '.get_option('clp_button-border_color', '#fff'),
                    )
                ),
                array(
                    'id'          => 'clp_button-border_color',
                    'label'       => esc_html__( 'Border Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_button-border_color'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                ),
                array(
                    'id'          => 'clp_button-border_color_hover',
                    'label'       => esc_html__( 'Border Hover Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_button-border_color_hover'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'css'			=> array(
                        'selector'	=> '.wp-core-ui .clp-form-container .button-primary:hover, .wp-core-ui .clp-form-container .button-primary:active, .wp-core-ui .clp-form-container .button-primary:focus',
                        'property'	=> 'border-color',
                    )
                ),
            ),
        );

        $settings['form_footer'] = array(
            'title'       => esc_html__( 'Form Footer', 'clp-custom-login-page' ),
            'description' => '',
            'fields'      => array(
                array(
                    'id'            => 'clp_form_footer-separator',
                    'label'         => esc_html__( 'Customize Form Footer', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'          => 'clp_form_footer-align',
                    'label'       => esc_html__( 'Text Align', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'select',
                    'default'     => $default['clp_form_footer-align'],
                    'choices'	=> array (
                        'left' 		=> esc_html__( 'Left', 'clp-custom-login-page' ),
                        'center' 	=> esc_html__( 'Center', 'clp-custom-login-page' ),
                        'right' 	=> esc_html__( 'Right', 'clp-custom-login-page' ),
                    ),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_select' ),
                ),
                array(
                    'id'          => 'clp_form_footer-font_size',
                    'label'       => esc_html__( 'Font Size', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_form_footer-font_size'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 10,
                        'max'    => 50,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => 'sanitize_text_field',
                    'css'			=> array(
                        'selector'	=> '.clp-login.login #nav, .clp-login.login #backtoblog, .privacy-policy-link',
                        'property'	=> 'font-size',
                        'suffix'    => 'px'
                    )
                ),
                array(
                    'id'            => 'clp_form_footer-forget_password-separator',
                    'label'         => esc_html__( 'Forget Password', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'            => 'clp_form_footer-display_forgetpassword',
                    'label'         => esc_html__( 'Display "Forget Password"?', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'toggle',
                    'transport'		=> 'postMessage',
                    'default'       => $default['clp_form_footer-display_forgetpassword'],
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_toggle' ),
                ),
                array(
                    'id'          => 'clp_form_footer-forgetpassword_color',
                    'label'       => esc_html__( 'Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_form_footer-forgetpassword_color'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'active_callback' => array( $this, 'is_forgetpassword'),
                    'css'			=> array(
                        'selector'	=> '.login #nav a:last-of-type',
                        'property'	=> 'color',
                        // 'suffix'    => '!important'
                    )
                ),
                array(
                    'id'          => 'clp_form_footer-forgetpassword_color_hover',
                    'label'       => esc_html__( 'Hover Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_form_footer-forgetpassword_color_hover'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'active_callback' => array( $this, 'is_forgetpassword'),
                    'css'			=> array(
                        'selector'	=> '.login #nav a:last-of-type:hover',
                        'property'	=> 'color',
                        // 'suffix'    => '!important'
                    )
                ),
                array(
                    'id'            => 'clp_form_footer-forgetpassword_text',
                    'label'         => esc_html__( 'Custom "Forget Password" Text', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'text',
                    'default'       => __( 'Lost your password?', 'clp-custom-login-page' ),
                    'transport'		=> 'postMessage',
                    'active_callback' => array( $this, 'is_forgetpassword'),
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                
                array(
                    'id'            => 'clp_form_footer-login_text',
                    'label'         => esc_html__( 'Custom "Log In" Text', 'clp-custom-login-page' ),
                    'description'   => 'Visible on Password Reset Form',
                    'type'          => 'text',
                    'default'       => __( 'Log In', 'clp-custom-login-page' ),
                    'transport'		=> 'postMessage',
                    'active_callback' => array( $this, 'is_forgetpassword'),
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                array(
                    'id'            => 'clp_form_footer-backtoblog-separator',
                    'label'         => esc_html__( 'Back to Site', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'            => 'clp_form_footer-display_backtoblog',
                    'label'         => esc_html__( 'Display "Back to Site"?', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'toggle',
                    'transport'		=> 'postMessage',
                    'default'       => $default['clp_form_footer-display_backtoblog'],
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_toggle' ),
                ),
                array(
                    'id'          => 'clp_form_footer-backtoblog_color',
                    'label'       => esc_html__( 'Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_form_footer-backtoblog_color'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'active_callback' => array( $this, 'is_backtoblog'),
                    'css'			=> array(
                        'selector'	=> '.login #backtoblog a',
                        'property'	=> 'color',
                    )
                ),
                array(
                    'id'          => 'clp_form_footer-backtoblog_color_hover',
                    'label'       => esc_html__( 'Hover Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_form_footer-backtoblog_color_hover'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'active_callback' => array( $this, 'is_backtoblog'),
                    'css'			=> array(
                        'selector'	=> '.login #backtoblog a:hover',
                        'property'	=> 'color',
                    )
                ),
                array(
                    'id'            => 'clp_form_footer-backtoblog_text',
                    'label'         => esc_html__( 'Custom "Back to Site" Text', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'text',
                    'default'       => sprintf( _x( '&larr; Back to %s', 'site' ), get_bloginfo( 'title', 'display' ) ),
                    'transport'		=> 'postMessage',
                    'active_callback' => array( $this, 'is_backtoblog'),
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                array(
                    'id'            => 'clp_form_footer-privacy-separator',
                    'label'         => esc_html__( 'Privacy Link', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                    'active_callback' => array( $this, 'is_privacy_link_enabled'),
                ),
                array(
                    'id'            => 'clp_form_footer-display_privacy',
                    'label'         => esc_html__( 'Display "Privacy Link"?', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'toggle',
                    'transport'		=> 'postMessage',
                    'default'       => $default['clp_form_footer-display_privacy'],
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_toggle' ),
                    'active_callback' => array( $this, 'is_privacy_link_enabled'),
                ),
                array(
                    'id'          => 'clp_form_footer-privacy_color',
                    'label'       => esc_html__( 'Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_form_footer-privacy_color'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'active_callback' => array( $this, 'is_privacy_link'),
                    'css'			=> array(
                        'selector'	=> '.privacy-policy-link',
                        'property'	=> 'color',
                    )
                ),
                array(
                    'id'          => 'clp_form_footer-privacy_color_hover',
                    'label'       => esc_html__( 'Hover Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_form_footer-privacy_color_hover'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'active_callback' => array( $this, 'is_privacy_link'),
                    'css'			=> array(
                        'selector'	=> '.privacy-policy-link:hover',
                        'property'	=> 'color',
                    )
                ),
                array(
                    'id'            => 'clp_form_footer-register-separator',
                    'label'         => esc_html__( 'Register', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                    'active_callback' => array( $this, 'can_register'),
                ),
                array(
                    'id'            => 'clp_form_footer-display_register',
                    'label'         => esc_html__( 'Display "Register"?', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'toggle',
                    'transport'		=> 'postMessage',
                    'default'       => $default['clp_form_footer-display_register'],
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_toggle' ),
                    'active_callback' => array( $this, 'can_register' ),
                ),
                array(
                    'id'          => 'clp_form_footer-register_color',
                    'label'       => esc_html__( 'Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_form_footer-register_color'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'active_callback' => array( $this, 'is_register'),
                    'css'			=> array(
                        'selector'	=> '.login.clp-show-register #nav a:first-of-type, .login.can-register-1 #nav a:first-of-type, .can-register-1 #nav,  .clp-show-register #nav',
                        'property'	=> 'color',
                    )
                ),
                array(
                    'id'          => 'clp_form_footer-register_color_hover',
                    'label'       => esc_html__( 'Hover Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_form_footer-register_color_hover'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'active_callback' => array( $this, 'is_register'),
                    'css'			=> array(
                        'selector'	=> '.login #nav a:first-of-type:hover',
                        'property'	=> 'color',
                    )
                ),
                array(
                    'id'            => 'clp_form_footer-register_text',
                    'label'         => esc_html__( 'Custom "Register" Text', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'text',
                    'default'       => __( 'Register', 'clp-custom-login-page' ),
                    'transport'		=> 'postMessage',
                    'active_callback' => array( $this, 'is_register'),
                    'sanitize_callback' => 'sanitize_text_field',
                ),
                array(
                    'id'            => 'clp_form_footer-login_link_separator',
                    'label'         => esc_html__( 'Login Link Separator', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'text',
                    'default'       => '|',
                    'transport'		=> 'postMessage',
                    'active_callback' => array( $this, 'is_register'),
                    'sanitize_callback' => 'sanitize_text_field',
                ),

            ),
        );

        $settings['page_footer'] = array(
            'title'       => esc_html__( 'Page Footer', 'clp-custom-login-page' ),
            'description' => '',
            'fields'      => array(
                array(
                    'id'            => 'clp_footer-separator',
                    'label'         => esc_html__( 'Customize Page Footer', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'            => 'clp_footer-enable',
                    'label'         => esc_html__( 'Enable Page Footer', 'clp-custom-login-page' ),
                    'type'          => 'toggle',
                    'transport'		=> 'postMessage',
                    'default'       => $default['clp_footer-enable'],
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_toggle' ),
                ),
                array(
                    'id'          => 'clp_footer-width',
                    'label'       => esc_html__( 'Width', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_footer-width'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 25,
                        'max'    => 100,
                        'step'   => 5,
                        'suffix' => '%', 
                    ),
                    'sanitize_callback' => 'absint',
                    'active_callback'=>  array( $this, 'is_page_footer' ),
                    'css'			=> array(
                        'selector'	=> '.clp-page-footer',
                        'property'	=> 'width',
                        'suffix'    => '%'
                    )
                ),
                array(
                    'id'          => 'clp_footer-padding',
                    'label'       => esc_html__( 'Padding', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'range',
                    'default'     => $default['clp_footer-padding'],
                    'transport'		=> 'postMessage',
                    'input_attrs' => array(
                        'min'    => 5,
                        'max'    => 50,
                        'step'   => 1,
                        'suffix' => 'px', 
                    ),
                    'sanitize_callback' => 'absint',
                    'active_callback'=>  array( $this, 'is_page_footer' ),
                    'css'			=> array(
                        'selector'	=> '.clp-page-footer',
                        'property'	=> 'padding',
                        'suffix'    => 'px'
                    )
                ),
                array(
                    'id'          => 'clp_footer-background_color',
                    'label'       => esc_html__( 'Background Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'alpha-color',
                    'default'     => $default['clp_footer-background_color'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => array('CLP_Sanitize', 'sanitize_alpha_color'),
                    'active_callback'=>  array( $this, 'is_page_footer' ),
                    'css'			=> array(
                        'selector'	=> '.clp-page-footer',
                        'property'	=> 'background-color',
                    )
                ),
                array(
                    'id'          => 'clp_footer-text_color',
                    'label'       => esc_html__( 'Text Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_footer-text_color'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'active_callback'=>  array( $this, 'is_page_footer' ),
                    'css'			=> array(
                        'selector'	=> '.clp-page-footer',
                        'property'	=> 'color',
                    )
                ),
                array(
                    'id'          => 'clp_footer-link_color',
                    'label'       => esc_html__( 'Link Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_footer-link_color'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'active_callback'=>  array( $this, 'is_page_footer' ),
                    'css'			=> array(
                        'selector'	=> '.clp-page-footer a',
                        'property'	=> 'color',
                    )
                ),
                array(
                    'id'          => 'clp_footer-link_color_hover',
                    'label'       => esc_html__( 'Link Color Hover', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_footer-link_color_hover'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'active_callback'=>  array( $this, 'is_page_footer' ),
                    'css'			=> array(
                        'selector'	=> '.clp-page-footer a:hover, .clp-page-footer a:active',
                        'property'	=> 'color',
                    )
                ),
                array(
                    'id'            => 'clp_footer-copyright',
                    'label'         => esc_html__( 'Copyright', 'clp-custom-login-page' ),
                    'type'          => 'editor',
                    'transport'		=> 'postMessage',
                    'default'       => $default['clp_footer-copyright'],
                    'sanitize_callback' => 'wp_kses_post',
                    'active_callback'=>  array( $this, 'is_page_footer' ),
                ),
                array(
                    'id'          => 'clp_footer-copyright_pos',
                    'label'       => esc_html__( 'Copyright Alignment', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'select',
                    'default'     => $default['clp_footer-copyright_pos'],
                    'choices'	=> array (
                        'left' 		=> esc_html__( 'Left', 'clp-custom-login-page' ),
                        'center' 	=> esc_html__( 'Center', 'clp-custom-login-page' ),
                        'right' 	=> esc_html__( 'Right', 'clp-custom-login-page' ),
                    ),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_select' ),
                    'active_callback'=>  array( $this, 'is_page_footer' ),
                ),
                array(
                    'id'            => 'clp_footer-niteothemes',
                    'label'         => esc_html__( 'Show some love', 'clp-custom-login-page' ),
                    'type'          => 'toggle',
                    'transport'		=> 'postMessage',
                    'default'       => $default['clp_footer-niteothemes'],
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_toggle' ),
                    'active_callback'=>  array( $this, 'is_page_footer' ),
                ),
                array(
                    'id'          => 'clp_footer-niteothemes_pos',
                    'label'       => esc_html__( 'Love Alignment', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'select',
                    'default'     => $default['clp_footer-niteothemes_pos'],
                    'choices'	=> array (
                        'left' 		=> esc_html__( 'Left', 'clp-custom-login-page' ),
                        'center' 	=> esc_html__( 'Center', 'clp-custom-login-page' ),
                        'right' 	=> esc_html__( 'Right', 'clp-custom-login-page' ),
                    ),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => array( 'CLP_Sanitize', 'sanitize_select' ),
                    'active_callback'=>  array( $this, 'is_page_footer' ),

                ),
            ),
        );

        $settings['messages'] = array(
            'title'       => esc_html__( 'Messages', 'clp-custom-login-page' ),
            'description' => '',
            'fields'      => array(
                array(
                    'id'            => 'clp_messages-colors-separator',
                    'label'         => esc_html__( 'Message Colors', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'          => 'clp_messages-text_color',
                    'label'       => esc_html__( 'Text Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_messages-text_color'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'css'			=> array(
                        'selector'	=> '.login .message, .login .success, .login #login_error, #reg_passmail',
                        'property'	=> 'color',
                    )
                ),
                array(
                    'id'          => 'clp_messages-border_color',
                    'label'       => esc_html__( 'Information Label Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_messages-border_color'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'css'			=> array(
                        'selector'	=> '.login .message',
                        'property'	=> 'border-left-color',
                    )
                ),
                array(
                    'id'          => 'clp_messages-border_color_error',
                    'label'       => esc_html__( 'Error Label Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_messages-border_color_error'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'css'			=> array(
                        'selector'	=> '.login #login_error',
                        'property'	=> 'border-left-color',
                    )
                ),
                array(
                    'id'          => 'clp_messages-border_color_success',
                    'label'       => esc_html__( 'Success Label Color', 'clp-custom-login-page' ),
                    'description' => '',
                    'type'        => 'color',
                    'default'     => $default['clp_messages-border_color_success'],
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'sanitize_hex_color',
                    'css'			=> array(
                        'selector'	=> '.login .success',
                        'property'	=> 'border-left-color',
                    )
                ),
                array(
                    'id'            => 'clp_messages-login_messages-separator',
                    'label'         => esc_html__( 'Login Messages', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'            => 'clp_messages-invalid_username',
                    'label'         => esc_html__( 'Error: Invalid Username', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'textarea',
                    'default'       => __('Unknown username. Check again or try your email address.'),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'wp_filter_post_kses',
                ),
                array(
                    'id'            => 'clp_messages-invalid_email',
                    'label'         => esc_html__( 'Error: Invalid Email', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'textarea',
                    'default'       => __('Unknown email address. Check again or try your username.'),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'wp_filter_post_kses',
                ),
                array(
                    'id'            => 'clp_messages-incorrect_password',
                    'label'         => esc_html__( 'Error: Incorrect Password', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'textarea',
                    'default'       => sprintf(__('%1$sError:%2$s The password you entered is incorrect.'), '<strong>', '</strong>'),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'wp_filter_post_kses',
                ),
                array(
                    'id'            => 'clp_messages-empty_username',
                    'label'         => esc_html__( 'Error: Empty Username', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'textarea',
                    'default'       => sprintf(__('%1$sError:%2$s Please enter a username.'), '<strong>', '</strong>'),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'wp_filter_post_kses',
                ),
                array(
                    'id'            => 'clp_messages-empty_password',
                    'label'         => esc_html__( 'Error: Empty Password', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'textarea',
                    'default'       => sprintf(__('%1$sError:%2$s The password field is empty'), '<strong>', '</strong>'),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'wp_filter_post_kses',
                ),
                array(
                    'id'            => 'clp_messages-authentication_failed',
                    'label'         => esc_html__( 'Error: Invalid username, email address or incorrect password.', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'textarea',
                    'default'       => sprintf(__('%1$sError:%2$s Invalid username, email address or incorrect password.'), '<strong>', '</strong>'),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'wp_filter_post_kses',
                ),
                array(
                    'id'            => 'clp_messages-register-separator',
                    'label'         => esc_html__( 'Register Messages', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'            => 'clp_messages-email_exists',
                    'label'         => esc_html__( 'Error: Email is Already Registered', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'textarea',
                    'default'       => sprintf(__('%1$sError:%2$s This email is already registered, please choose another one.'), '<strong>', '</strong>'),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'wp_filter_post_kses',
                ),
                array(
                    'id'            => 'clp_messages-username_exists',
                    'label'         => esc_html__( 'Error: Username is Already Registered', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'textarea',
                    'default'       => sprintf(__('%1$sError:%2$s This username is already registered. Please choose another one.'), '<strong>', '</strong>'),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'wp_filter_post_kses',
                ),
                array(
                    'id'            => 'clp_messages-empty_email',
                    'label'         => esc_html__( 'Error: Empty Email', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'textarea',
                    'default'       => sprintf(__('%1$sError:%2$s Please type your email address.'), '<strong>', '</strong>'),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'wp_filter_post_kses',
                ),
                array(
                    'id'            => 'clp_messages-register_message',
                    'label'         => esc_html__( 'Info: Register Message', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'textarea',
                    'default'       => __('Register For This Site'),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'wp_filter_post_kses',
                ),
                array(
                    'id'            => 'clp_messages-register_message2',
                    'label'         => esc_html__( 'Info: Register Message 2', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'textarea',
                    'default'       => __('Registration confirmation will be emailed to you.'),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'wp_filter_post_kses',
                ),
                array(
                    'id'            => 'clp_messages-lostpassword-separator',
                    'label'         => esc_html__( 'Lost Password Messages', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'            => 'clp_messages-invalidcombo',
                    'label'         => esc_html__( 'Error: Account not found', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'textarea',
                    'default'       => sprintf(__('%1$sError:%2$s There is no account with that username or email address.'), '<strong>', '</strong>'),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'wp_filter_post_kses',
                ),
                array(
                    'id'            => 'clp_messages-forgetpassword_message',
                    'label'         => esc_html__( 'Info: Lost Password Message', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'textarea',
                    'default'       => __('Please enter your username or email address. You will receive an email message with instructions on how to reset your password.'),
                    'transport'		=> 'postMessage',
                    'sanitize_callback' => 'wp_filter_post_kses',
                ),
            ),
        );

        $settings['css'] = array(
            'title'       => esc_html__( 'Custom CSS', 'clp-custom-login-page' ),
            'description' => '',
            'fields'      => array(
                array(
                    'id'          => 'clp_css',
                    'label'       => esc_html__( 'Custom CSS', 'clp-custom-login-page' ),
                    'type'        => 'code',
                    'code_type'   => 'text/css',
                    'transport'		=> 'postMessage',
                ),

            ),
        );

        $settings['import_export'] = array(
            'title'       => esc_html__( 'Import / Export / Reset Settings', 'clp-custom-login-page' ),
            'description' => '',
            'fields'      => array(
                array(
                    'id'            => 'clp_messages-import_export-separator',
                    'label'         => esc_html__( 'Import/Export/Reset', 'clp-custom-login-page' ),
                    'description'   => '',
                    'type'          => 'separator',
                    'sanitize_callback' => '',
                ),
                array(
                    'id'          => 'clp_import_export',
                    'description' => '',
                    'type'        => 'clp_import_export',
                    'default'     => '',
                    'transport'		=> 'postMessage',
                ),

            ),
        );

        return $settings;
    }


    /**
     * Callback functions
     * @since 1.0.0
    **/
	public function is_background_color( $control ) {
		return 'color' === $control->manager->get_setting( 'clp_background' )->value();
	}

	public function is_background_gradient( $control ) {
		return 'gradient' === $control->manager->get_setting( 'clp_background' )->value();
    }
    
	public function is_background_pattern( $control ) {
		return 'pattern' === $control->manager->get_setting( 'clp_background' )->value();
    }
    
	public function is_custom_pattern( $control ) {
		return 'pattern' === $control->manager->get_setting( 'clp_background' )->value() && 'custom' === $control->manager->get_setting( 'clp_background-pattern' )->value();
    } 
    
	public function is_background_image( $control ) {
		return 'image' === $control->manager->get_setting( 'clp_background' )->value();
    }
    
	public function is_background_video( $control ) {
		return 'video' === $control->manager->get_setting( 'clp_background' )->value();
    }
    
	public function is_logo_text( $control ) {
		return 'text' === $control->manager->get_setting( 'clp_logo' )->value();
	}
	public function is_logo_image( $control ) {
		return 'image' === $control->manager->get_setting( 'clp_logo' )->value();
	}
	public function is_logo_enabled( $control ) {
		return 'image' === $control->manager->get_setting( 'clp_logo' )->value() || 'text' === $control->manager->get_setting( 'clp_logo' )->value();
    }
    
	public function is_overlay_enabled( $control ) {
		return '1' === $control->manager->get_setting( 'clp_background-overlay-enable' )->value();
    }
    
	public function is_form_borders( $control ) {
		return '1' === $control->manager->get_setting( 'clp_form-borders' )->value();
    }

	public function is_form_shadow( $control ) {
		return '1' === $control->manager->get_setting( 'clp_form-shadow' )->value();
    }
    
	public function is_backtoblog( $control ) {
		return '1' === $control->manager->get_setting( 'clp_form_footer-display_backtoblog' )->value();
    }
    
	public function is_forgetpassword( $control ) {
		return '1' === $control->manager->get_setting( 'clp_form_footer-display_forgetpassword' )->value();
    }

    public function is_register( $control ) {
        if ( $control->manager->get_setting( 'clp_form_footer-display_register' )->value() === '1' && get_option( 'users_can_register' ) ) {
            return true;
        } else {
            return false;
        }
    }

	public function is_rememberme( $control ) {
		return '1' === $control->manager->get_setting( 'clp_input-remember' )->value();
    }
    
	public function can_register( $control ) {
		return true == get_option( 'users_can_register' );
    }

	public function is_privacy_link_enabled( $control ) {
		return !empty( get_privacy_policy_url() );
    }
    
	public function is_privacy_link( $control ) {
		return !empty( get_privacy_policy_url() ) && '1' === $control->manager->get_setting( 'clp_form_footer-display_privacy' )->value();
    }

	public function is_content_half_width( $control ) {
		return '100' !== $control->manager->get_setting( 'clp_layout-width' )->value();
    }
    
	public function is_page_footer( $control ) {
		return '1' === $control->manager->get_setting( 'clp_footer-enable' )->value();
    }
	public function is_page_footer_niteothemes( $control ) {
		return '1' === $control->manager->get_setting( 'clp_footer-niteothemes' )->value();
    }
    
	public function is_label( $control ) {
		return '1' === $control->manager->get_setting( 'clp_input-label_display' )->value();
    }

    /**
     * Private helper function to print out current settings values, not used in production
     * @since 1.0.0
    **/
    private function get_current_settings() {
        $default = $this->get_default_template_settings();
        foreach ( $default['default'] as $id => $val) {
            $value = get_option($id);
            if ( !empty($value) && $value !== $val) {
                echo "'". $id  . "' => '" . $value . "',". "<br>";
            }
        }
    }
}