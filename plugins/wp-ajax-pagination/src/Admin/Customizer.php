<?php namespace AjaxPagination\Admin;
use Premmerce\SDK\V2\FileManager\FileManager;
use  AjaxPagination\Admin\Customizer\CustomizeRange;
use  AjaxPagination\Admin\Customizer\CustomizeDonate;
use  AjaxPagination\Admin\Customizer\CustomizeLabel;
/**
 * Class Customizer
 *
 * @package ResponsiveTable\Admin
 */



class Customizer
{

    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
        $this->registerHooks();
    }

    public function registerHooks(){
        add_action('customize_register', array($this, 'addSection'));
        add_action('customize_register', array($this, 'addSettings'));
    }

    public function addSection($wp_customize){


        $wp_customize->add_panel( 'ajax_pagination_settings', array(
            'title'      => __('Ajax Pagination Settings', 'wp-ajax-pagination'),
            'priority'   => 200,
        ) );
        $wp_customize->add_section( 'wpap_loading_effects' , array(
            'title'      => __('Loading effects', 'wp-ajax-pagination'),
            'panel' => 'ajax_pagination_settings',
        ) );
        $wp_customize->add_section( 'wpap_button_settings' , array(
            'title'      => __('Button settings', 'wp-ajax-pagination'),
            'panel' => 'ajax_pagination_settings',
        ) );



    }

    public function addSettings($wp_customize){


        //loading effects
        $checkboxOptions = array(
            'label' => __('Enable loading effects', 'wp-ajax-pagination'),
            'default' => 1,
            'section' => 'wpap_loading_effects'
        );
        $this->addSetting('wpap_loading_effects_enable', 'checkbox', $wp_customize, $checkboxOptions);

        $imageOptions = array(
            'label' => __('Spinner image', 'wp-ajax-pagination'),
            'default' => $this->fileManager->locateAsset('frontend/img/loader.gif'),
            'active_callback' => array( $this, 'isLoadingEffects' )
        );
        $this->addSetting('wpap_spinner_image', 'image', $wp_customize,  $imageOptions);

        $rangeOptions = array(
            'label' => __('Spinner size (px)', 'wp-ajax-pagination'),
            'min' => 20,
            'max' => 200,
            'step' => 1,
            'default' => 60,
            'section' => 'wpap_loading_effects',
            'active_callback' => array( $this, 'isLoadingEffects' )
        );
        $this->addSetting('wpap_spinner_size', 'range', $wp_customize, $rangeOptions);


        $colorOptions = array(
            'label' => __('Background color', 'wp-ajax-pagination'),
            'choices' => array(
                'none' => __('None', 'wp-ajax-pagination'),
                'black' => __('Black', 'wp-ajax-pagination'),
                'white' => __('White', 'wp-ajax-pagination'),
            ),
            'default' => 'black',
            'section' => 'wpap_loading_effects',
            'active_callback' => array( $this, 'isLoadingEffects' )
        );
        $this->addSetting('wpap_background_color', 'radio', $wp_customize, $colorOptions);


        $rangeOptions = array(
            'label' => __('Opacity', 'wp-ajax-pagination'),
            'min' => 0,
            'max' => 1,
            'step' => 0.1,
            'default' => 0.4,
            'section' => 'wpap_loading_effects',
            'active_callback' => array( $this, 'isLoadingEffects' )
        );
        $this->addSetting('wpap_opacity', 'range', $wp_customize, $rangeOptions);


        //button settings
        $colorOptions = array(
            'label' => __('Background color', 'wp-ajax-pagination'),
            'default' => '#ffffff',
            'section' => 'wpap_button_settings'
        );
        $this->addSetting('wpap_button_background_color', 'color', $wp_customize, $colorOptions);

        $colorOptions = array(
            'label' => __('Text color', 'wp-ajax-pagination'),
            'default' => '',
            'section' => 'wpap_button_settings'
        );
        $this->addSetting('wpap_button_text_color', 'color', $wp_customize, $colorOptions);


        $checkboxOptions = array(
            'label' => __('Button text', 'wp-ajax-pagination'),
            'default' => __('Load more', 'wp-ajax-pagination'),
            'section' => 'wpap_button_settings'
        );
        $this->addSetting('wpap_button_text', 'text', $wp_customize, $checkboxOptions);

        $checkboxOptions = array(
            'label' => __('Bold text', 'wp-ajax-pagination'),
            'default' => 0,
            'section' => 'wpap_button_settings'
        );
        $this->addSetting('wpap_button_bold_text', 'checkbox', $wp_customize, $checkboxOptions);



        $rangeOptions = array(
            'label' => __('Border radius (px)', 'wp-ajax-pagination'),
            'min' => 0,
            'max' => 100,
            'step' => 1,
            'default' => 0,
            'section' => 'wpap_button_settings'

        );
        $this->addSetting('wpap_border_radius', 'range', $wp_customize, $rangeOptions);

        $rangeOptions = array(
            'label' => __('Button width (px)', 'wp-ajax-pagination'),
            'min' => 0,
            'max' => 500,
            'step' => 1,
            'default' => 150,
            'section' => 'wpap_button_settings'

        );
        $this->addSetting('wpap_button_width', 'range', $wp_customize, $rangeOptions);

        $rangeOptions = array(
            'label' => __('Button height (px)', 'wp-ajax-pagination'),
            'min' => 0,
            'max' => 500,
            'step' => 1,
            'default' => 50,
            'section' => 'wpap_button_settings'

        );
        $this->addSetting('wpap_button_height', 'range', $wp_customize, $rangeOptions);

        $checkboxOptions = array(
            'label' => __('Enable shadows', 'wp-ajax-pagination'),
            'default' => 1,
            'section' => 'wpap_button_settings'
        );
        $this->addSetting('wpap_button_shadows', 'checkbox', $wp_customize, $checkboxOptions);



    }


    private function addSetting($name, $type, $wp_customize, $args = array())
    {

        $default = array(
            'default' => '',
            'description' => '',
            'active_callback' => '',
            'label' => '',
            'section' => 'wpap_loading_effects'
        );
        $args = array_merge($default, $args);

        switch ($type) {
            case 'text':
            case 'textarea':
            case 'checkbox':
                $wp_customize->add_setting($name, array(
                    'default' => $args['default']
                ));
                $wp_customize->selective_refresh->add_partial($name, array(
                    'selector' => '.' . $name
                ));
                $wp_customize->add_control($name, array(
                        'label' => $args['label'],
                        'section' => $args['section'],
                        'type' => $type,
                        'active_callback'	=> $args['active_callback']
                    )
                );
                break;

            case 'select':
                $wp_customize->add_setting($name, array(
                    'capability' => 'edit_theme_options',
                    'default' => $args['default'],
                ));
                $wp_customize->selective_refresh->add_partial($name, array(
                    'selector' => '.' . $name
                ));

                $wp_customize->add_control($name, array(
                    'type' => 'select',
                    'section' => $args['section'],
                    'label' => $args['label'],
                    'description' => __($args['description'], 'wp-ajax-pagination'),
                    'choices' => $args['choices'],
                    'active_callback'	=> $args['active_callback']
                ));
                break;

            case 'radio':
                $wp_customize->add_setting($name, array(
                    'capability' => 'edit_theme_options',
                    'default' => $args['default'],
                ));
                $wp_customize->selective_refresh->add_partial($name, array(
                    'selector' => '.'.$name
                ));

                $wp_customize->add_control($name, array(
                    'type' => 'radio',
                    'section' => $args['section'],
                    'label' => $args['label'],
                    'description' => __($args['description'], 'wp-ajax-pagination'),
                    'choices' => $args['choices'],
                    'active_callback'	=> $args['active_callback']
                ));
                break;

            case 'range':
                $wp_customize->add_setting($name, array(
                    'default' => $args['default']
                ));
                $wp_customize->selective_refresh->add_partial($name, array(
                    'selector' => '.'.$name
                ));

                $wp_customize->add_control(new CustomizeRange($wp_customize, $name, array(
                    'label' => $args['label'],
                    'min' => $args['min'],
                    'max' => $args['max'],
                    'step' => $args['step'],
                    'section' => $args['section'],
                    'active_callback'	=> $args['active_callback']
                )));

                break;

            case 'color':
                $wp_customize->add_setting($name, array(
                    'default' => $args['default'],
                ));
                $wp_customize->selective_refresh->add_partial($name, array(
                    'selector' => '.'.$name
                ));

                $wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, $name, array(
                    'label' => $args['label'],
                    'section' => $args['section'],
                    'settings' => $name,
                    'active_callback'	=> $args['active_callback'],
                )));


                break;

            case 'image':
                $wp_customize->add_setting($name, array(
                    'capability' => 'edit_theme_options',
                    'default' => $args['default'],
                ));


                $wp_customize->add_control(new \WP_Customize_Image_Control($wp_customize, $name, array(
                    'label' => $args['label'],
                    'section' => $args['section'],
                    'settings' => $name,
                    'type' => 'image',
                    'active_callback'	=> $args['active_callback'],
                )));

                break;
        }



    }


     public function isLoadingEffects($control){
         $loading_effects_enable = $control->manager->get_setting( 'wpap_loading_effects_enable' )->value();

        if($loading_effects_enable) {
            return true;
        } else {
            return false;
        }
    }


}