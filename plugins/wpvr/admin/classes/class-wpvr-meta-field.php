<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Responsible for managing wpvr meta fields
 *
 * @link       http://rextheme.com/
 * @since      8.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin/classes
 */


class WPVR_Meta_Field {

    
    public function __construct()
    {
        
    }


    /**
     * Initialize primary meta fields for a Tour
     * 
     * @return array
     * @since 8.0.0
     */
    public static function get_primary_meta_fields()
    {
        $meta_fields = array(
            'panoid' => null,
            'autoLoad' => 1,
            'hfov' => null,
            'maxHfov' => null,
            'minHfov' => null,
            'showControls' => 1,
            'customcontrol' => null,
            'preview' => null,
            'defaultscene' => null,
            'scenefadeduration' => null,
            'panodata' => array(
                'scene-list' => array(
                    array(
                        'scene-id' => null,
                        'scene-type' => 'equirectangular',
                        'hotspot-list' => array(
                            array(
                                'hotspot-title' => null,
                                'hotspot-pitch' => null,
                                'hotspot-yaw' => null,
                                'hotspot-customclass' => null,
                                'hotspot-scene' => null,
                                'hotspot-url' => null,
                                'hotspot-content' => null,
                                'hotspot-hover' => null,
                                'hotspot-type' => 'info',
                                'hotspot-scene-list' => 'none',
                                'wpvr_url_open' => array(
                                    0 => 'off'
                                )
                            ),
                        ),
                        'dscene' => 'off',
                        'scene-attachment-url' => null,
                    ),
                ),
            ),
            'previewtext' => 'Click To Load Panorama',
        );
        return apply_filters( 'extend_primary_meta_fields', $meta_fields );
    }


    /**
     * Initialise Tab Navigation Items
     * @return array
     * @since 8.0.0
     */
    public static function get_navigation_fields() {
        $fields = array(
            array(
                'class' => 'scene',
                'screen' => 'scene',
                'href' => 'scenes',
                'r_src' => 'admin/icon/scenes-regular.png',
                'h_src' => 'admin/icon/scenes-hover.png',
                'title' => __('Scenes','wpvr'),
                'active' => 'active'
            ),
            array(
                'class' => 'hotspot',
                'screen' => 'hotspot',
                'href' => 'scenes',
                'r_src' => 'admin/icon/hotspot-regular.png',
                'h_src' => 'admin/icon/hotspot-hover.png',
                'title' => __('Hotspot','wpvr'),
                'active' => ''
            ),
            array(
                'class' => 'general',
                'screen' => 'general',
                'href' => 'general',
                'r_src' => 'admin/icon/general-regular.png',
                'h_src' => 'admin/icon/general-hover.png',
                'title' => __('General','wpvr'),
                'active' => ''
            ),
            array(
                'class' => 'videos',
                'screen' => 'video',
                'href' => 'video',
                'r_src' => 'admin/icon/video-regular.png',
                'h_src' => 'admin/icon/video-hover.png',
                'title' => __('Video','wpvr'),
                'active' => ''
            ),
        );
        return apply_filters( 'extend_rex_pano_nav_menu', $fields );
    }


    /**
     * Initialise Basic Setting Left Field
     * @param mixed $preview
     * @param mixed $previewtext
     * @param mixed $autoload
     * @param mixed $control
     * 
     * @return array
     * @since 8.0.0
     */
    public static function get_basic_setting_left_fields($postdata)
    {
        return array(
            'preview-attachment-url' => array(
                'class' => 'single-settings preview-setting',
                'type' => 'preview_image',
                'value' => $postdata['preview'],
                'title' => __('Set a Tour Preview Image','wpvr')
            ),
            'previewtext' => array(
                'class' => 'single-settings preview-img-message',
                'type' => 'preview_image_msg',
                'value' => $postdata['previewtext'],
                'title' => __('Preview Image Message','wpvr')
            ),
            'autoload' => array(
                'class' => 'single-settings autoload',
                'type' => 'basic_setting_checkbox',
                'title' => __('Tour Autoload','wpvr'),
                'id' => 'wpvr_autoload',
                'have_tooltip' => true,
                'tooltip_text' => __('Tour Preview Image will not appear if this is turned on.','wpvr'),
                'checked' => $postdata['autoLoad'],
            ),
            'controls' => array(
                'class' => 'single-settings controls',
                'type' => 'basic_setting_checkbox',
                'title' => __('Basic Control Buttons','wpvr'),
                'id' => 'wpvr_controls',
                'have_tooltip' => true,
                'tooltip_text' => __('This option will display Zoom In, Zoom Out and Full Screen buttons on the tour.','wpvr'),
                'checked' => $postdata['showControls'],
            ),
        );
    }


    /**
     * Initialize fields render method
     * @param mixed $preview
     * @param mixed $previewtext
     * @param mixed $autoload
     * @param mixed $control
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_basic_settings_left_fields($postdata)
    {   
        $fields = self::get_basic_setting_left_fields($postdata);

        foreach($fields as $name => $val) {
            self::{ 'render_' . $val['type'] }( $name, $val );
        }
    }


    /**
     * Initilize Basic Setting Right Fields
     * @param mixed $scene_fade_duration
     * @param mixed $postdata
     * 
     * @return array
     * @since 8.0.0
     */
    public static function get_basic_setting_right_fields($postdata)
    {
        if(!isset($postdata['autoRotate'])){
            $rotation = 0;
        }else{
            $rotation = 1;
        }

        return array(
            'scene-fade-duration' => array(
                'class' => 'single-settings scene-fade-duration',
                'title' => __('Scene Fade Duration','wpvr'),
                'type' => 'number_field',
                'value' => $postdata['scenefadeduration'],
                'placeholder' => null,
                'have_tooltip' => true,
                'tooltip_text' => __('This will set the scene fade effect and execution time.','wpvr'),
            ),
            'autorotation' => array(
                'class' => 'single-settings autoload',
                'title' => __('Auto Rotation','wpvr'),
                'id' => 'wpvr_autorotation',
                'type' => 'basic_setting_checkbox',
                'checked' => $rotation,
                'have_tooltip' => false,
                'tooltip_text' => '',
            ),
        );
    }

        /**
     * Initilize Basic Setting Right Fields
     * @param mixed $scene_fade_duration
     * @param mixed $postdata
     * 
     * @return array
     * @since 8.0.0
     */
    public static function get_basic_setting_generic_form_fields($postdata)
    {
        if(!isset($postdata['genericform'])){
            $genericform = "off";
        }else{
            $genericform = $postdata['genericform'];
        }

        return array(
            'genericform' => array(
                'class' => 'single-settings genericform',
                'title' => __('Generic Form','wpvr'),
                'id' => 'wpvr_generic_form',
                'type' => 'generic_form_checkbox',
                'checked' => $genericform,
                'have_tooltip' => true,
                'tooltip_text' => __('Disable keyboard control to use the form.','wpvr'),
            )
        );
    }

        /**
     * Initilize Basic Setting Right Fields
     * @param mixed $scene_fade_duration
     * @param mixed $postdata
     *
     * @return array
     * @since 8.0.0
     */
    public static function get_basic_setting_call_to_action_fields($postdata)
    {
        if(!isset($postdata['calltoaction'])){
            $calltoaction = "off";
        }else{
            $calltoaction = $postdata['calltoaction'];
        }

        return array(
            'calltoaction' => array(
                'class' => 'single-settings calltoaction',
                'title' => __('Call To Action ','wpvr'),
                'id' => 'wpvr_cal_to_action_form',
                'type' => 'call_to_form_checkbox',
                'checked' => $calltoaction,
                'have_tooltip' => true,
                'tooltip_text' => __('Enable the call to action, it will render under the tour.','wpvr'),
            )
        );
    }

        /**
     * Initilize Basic Setting Right Fields
     * @param mixed $scene_fade_duration
     * @param mixed $postdata
     *
     * @return array
     * @since 8.0.0
     */
    public static function get_basic_setting_custom_css_fields($postdata)
    {
        if(!isset($postdata['customcss_enable'])){
            $customcss_enable = "off";
        }else{
            $customcss_enable = $postdata['customcss_enable'];
        }

        return array(
            'customcss' => array(
                'class' => 'single-settings customcss-switcher',
                'title' => __('Custom CSS ','wpvr'),
                'id' => 'wpvr_custom_css',
                'type' => 'custom_css_form_checkbox',
                'checked' => $customcss_enable,
                'have_tooltip' => true,
                'tooltip_text' => __('Write your custom css','wpvr'),
            )
        );
    }

    /**
     * Render Basic Setting Right Fields
     * @param mixed $scene_fade_duration
     * @param mixed $postdata
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_basic_setting_generic_form_fields($postdata)
    {
        $fields = self::get_basic_setting_generic_form_fields($postdata);

        foreach($fields as $name => $val) {
            self::{ 'render_' . $val['type'] }( $name, $val );
        }
    }
    /**
     * Render Basic Setting Right Fields
     * @param mixed $scene_fade_duration
     * @param mixed $postdata
     *
     * @return void
     * @since 8.0.0
     */
    public static function render_basic_setting_call_to_action_fields($postdata)
    {
        $fields = self::get_basic_setting_call_to_action_fields($postdata);
        foreach($fields as $name => $val) {
            self::{ 'render_' . $val['type'] }( $name, $val );
        }
    }
    /**
     * Render Basic Setting Right Fields
     * @param mixed $scene_fade_duration
     * @param mixed $postdata
     *
     * @return void
     * @since 8.0.0
     */
    public static function render_basic_setting_custom_css_fields($postdata)
    {
        $fields = self::get_basic_setting_custom_css_fields($postdata);
        foreach($fields as $name => $val) {
            self::{ 'render_' . $val['type'] }( $name, $val );
        }
    }

    /**
     * Render Basic Setting Right Fields
     * @param mixed $scene_fade_duration
     * @param mixed $postdata
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_basic_setting_right_fields($postdata)
    {
        $fields = self::get_basic_setting_right_fields($postdata);

        foreach($fields as $name => $val) {
            self::{ 'render_' . $val['type'] }( $name, $val );
        }
    }


    /**
     * Initialize Autorotation Data Wrapper Fields
     * @param mixed $autorotation
     * @param mixed $autorotationinactivedelay
     * @param mixed $autorotationstopdelay
     * 
     * @return array
     * @since 8.0.0
     */
    public static function get_autorotation_data_wrapper_fields($postdata)
    {
        return array(
            'auto-rotation' => array(
                'class' => 'single-settings autorotationdata',
                'title' => __('Rotation Speed and Direction','wpvr'),
                'type' => 'number_field',
                'value' => isset($postdata['autoRotate']) ? $postdata['autoRotate'] : -5,
                'placeholder' => -5,
                'have_tooltip' => true,
                'tooltip_text' => __('Set a value to determine the speed of rotation. The higher the number, the faster it will rotate. Positive values will make it rotate clockwise and negative values will make it rotate anti clockwise','wpvr'),
            ),
            'auto-rotation-inactive-delay' => array(
                'class' => 'single-settings autorotationdata',
                'title' => __('Resume Auto-rotation after','wpvr'),
                'type' => 'number_field',
                'value' => isset($postdata['autoRotateInactivityDelay']) ? $postdata['autoRotateInactivityDelay'] : null,
                'placeholder' => 2000,
                'have_tooltip' => true,
                'tooltip_text' => __('When someone clicks on the tour, auto-rotation stops. Here, set a time after which auto rotation will start again. Assign in milliseconds, where 1000 milliseconds = 1 second.','wpvr'),
            ),
            'auto-rotation-stop-delay' => array(
                'class' => 'single-settings autorotationdata',
                'title' => __('Stop Auto-rotation after','wpvr'),
                'type' => 'number_field',
                'value' => isset($postdata['autoRotateStopDelay']) ? $postdata['autoRotateStopDelay'] : null,
                'placeholder' => 2000,
                'have_tooltip' => true,
                'tooltip_text' => __('Set a time after which auto rotation will stop. Assign in milliseconds, where 1000 milliseconds = 1 second.','wpvr'),
            ),
        );
    }

    public static function get_generic_form_associate_fields($postdata)
    {
        return array(
            'genericformshortcode' => array(
                'class' => 'single-settings genericformshortcode',
                'title' => __('Add Form Shortcode','wpvr'),
                'type' => 'text_field',
                'value' => isset($postdata['genericformshortcode']) ? $postdata['genericformshortcode'] : "",
                'placeholder' => "",
                'have_tooltip' => true,
                'tooltip_text' => __('Print the forms shortcode you want to show.','wpvr'),
            ),
        );
    }


    public static function get_call_to_action_associate_fields($postdata)
    {
        return array(
            'buttontext' => array(
                'class' => 'single-settings buttontext',
                'title' => __('Button Text','wpvr'),
                'type' => 'text_field',
                'value' => isset($postdata['buttontext']) ? $postdata['buttontext'] : "Click Here",
                'placeholder' => "",
                'have_tooltip' => true,
                'tooltip_text' => __('Call to action button text','wpvr'),
            ),'buttonurl' => array(
                'class' => 'single-settings buttonurl',
                'title' => __('Button URL','wpvr'),
                'type' => 'text_field',
                'value' => isset($postdata['buttonurl']) ? $postdata['buttonurl'] : "",
                'placeholder' => "",
                'have_tooltip' => true,
                'tooltip_text' => 'Call to action URl',
            ),
            'button_configuration' => array(
                'class' => 'single-settings button_configuration',
                'title' => __('Button Style','wpvr'),
                'name' => 'button_configuration_field',
                'type' => 'button_configuration_field',
                'value' => isset($postdata['button_configuration']) ? $postdata['button_configuration'] : array(),
                'placeholder' => "",
                'have_tooltip' => true,
                'tooltip_text' => __('Print the forms shortcode you want to show.','wpvr'),
            ),

        );
    }


    public static function get_custom_css_associate_fields($postdata)
    {
        return array(
            'customcss' => array(
                'class' => 'single-settings customcss',
                'title' => __('Custom CSS','wpvr'),
                'type' => 'text_area_field',
                'code_mirror_id' => 'code-mirror-editor-custom-css',
                'value' => isset($postdata['customcss']) ? $postdata['customcss'] : "",
                'placeholder' => "",
                'have_tooltip' => true,
            )

        );
    }

    /**
     * Render generic form associate fields
     * @param mixed $genericformshortcode
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_generic_form_associate_fields($postdata)
    {
        $fields = self::get_generic_form_associate_fields($postdata);

        foreach($fields as $name => $val) {
            self::{ 'render_' . $val['type'] }( $name, $val );
        }
    }

    /**
     * Render generic form associate fields
     * @param mixed $genericformshortcode
     *
     * @return void
     * @since 8.0.0
     */
    public static function render_call_to_action_associate_fields($postdata)
    {
        $fields = self::get_call_to_action_associate_fields($postdata);

        foreach($fields as $name => $val) {
            self::{ 'render_' . $val['type'] }( $name, $val );
        }
    }

    /**
     * Render generic form associate fields
     * @param mixed $genericformshortcode
     *
     * @return void
     * @since 8.0.0
     */
    public static function render_custom_css_associate_fields($postdata)
    {
        $fields = self::get_custom_css_associate_fields($postdata);

        foreach($fields as $name => $val) {
            self::{ 'render_' . $val['type'] }( $name, $val );
        }
    }


    /**
     * Render Autorotation Data Wrapper Fields
     * @param mixed $autorotation
     * @param mixed $autorotationinactivedelay
     * @param mixed $autorotationstopdelay
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_autorotation_data_wrapper_fields($postdata)
    {
        $fields = self::get_autorotation_data_wrapper_fields($postdata);

        foreach($fields as $name => $val) {
            self::{ 'render_' . $val['type'] }( $name, $val );
        }
    }


    /**
     * Render Tab Navigation
     * @return void
     * @since 8.0.0
     */
    public static function render_pano_tab_nav($postdata) {
        $fields = self::get_navigation_fields();
        ob_start();
        ?>

        <nav class="rex-pano-tab-nav rex-pano-nav-menu main-nav" id="wpvr-main-nav">
            <ul>
                <li class="logo"><img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . 'admin/icon/logo.png'; ?>" alt="logo" /></li>

                <?php foreach($fields as $field) { ?>

                <li class="<?= $field['class']; ?> <?= $field['active']; ?>" data-screen="<?= $field['screen']; ?>">
                    <span data-href="#<?= $field['href']; ?>">
                    <img loading="lazy" src="<?= WPVR_PLUGIN_DIR_URL . $field['r_src']; ?>" alt="icon" class="regular" />
                    <img loading="lazy" src="<?= WPVR_PLUGIN_DIR_URL . $field['h_src']; ?>" alt="icon" class="hover" />
                    <?= __($field['title'], 'wpvr'); ?> </span>
                </li>

                <?php }

//                if(!is_plugin_active('wpvr-pro/wpvr-pro.php')){
//                    ?>
<!---->
<!--                    <li class="floor-plan floor-plan-pro-tag" data-screen="floorPlan">-->
<!--                        <div class="navigator-pro-tag">pro</div>-->
<!--                        <span data-href="#floorPlan">-->
<!--                            <img src="http://localhost:10071/wp-content/plugins/wpvr/admin/icon/map.svg" alt="icon" class="regular">-->
<!--                            <img src="http://localhost:10071/wp-content/plugins/wpvr/admin/icon/map.svg" alt="icon" class="hover">-->
<!--                            Floor Plan-->
<!--                        </span>-->
<!--                    </li>-->
<!--                    <li class="background-tour background-tour-pro-tag" data-screen="backgroundTour">-->
<!--                        <div class="navigator-pro-tag">pro</div>-->
<!--                        <span data-href="#backgroundTour">-->
<!--                            <img src="http://localhost:10071/wp-content/plugins/wpvr/admin/icon/bg-tour-regular.png" alt="icon" class="regular">-->
<!--                            <img src="http://localhost:10071/wp-content/plugins/wpvr/admin/icon/bg-tour-hover.png" alt="icon" class="hover">-->
<!--                            Background Tour-->
<!--                        </span>-->
<!--                    </li>-->
<!--                    <li class="streetview streetview-pro-tag " data-screen="streetview">-->
<!--                        <div class="navigator-pro-tag">pro</div>-->
<!--                        <span data-href="#streetview">-->
<!--                            <img src="http://localhost:10071/wp-content/plugins/wpvr/admin/icon/street-view-regular.png" alt="icon" class="regular">-->
<!--                            <img src="http://localhost:10071/wp-content/plugins/wpvr/admin/icon/street-view-hover.png" alt="icon" class="hover">-->
<!--                            Street View-->
<!--                        </span>-->
<!--                    </li>-->
<!--                --><?php
//                }

                //== Render Export Tab for Tour edit ==//
                if(is_plugin_active( 'wpvr-pro/wpvr-pro.php' )) { if (isset($postdata['panoid'])) { ?>
                <li class="export" data-screen="export">
                    <span data-href="#import">
                    <img loading="lazy" src=" <?= WPVR_PLUGIN_DIR_URL . 'admin/icon/export-regular.png'; ?> " alt="icon" class="regular" />
                    <img loading="lazy" src=" <?= WPVR_PLUGIN_DIR_URL . 'admin/icon/export-hover.png'; ?> " alt="icon" class="hover" />
                    <?php echo  __('Export', 'wpvr'); ?></span>
                </li>
                <?php } } ?>

            </ul>
        </nav>

        <?php
        ob_end_flush();
    }


    /**
     * Initialise Advanced Settings Left Fields
     * @return array
     * @since 8.0.0
     */
    public static function get_advanced_settings_left_fields($postdata)
    {
        $fields = array(
            'diskeyboard' => array(
                'class' => 'single-settings compass',
                'title' => __('Keyboard Movement Control','wpvr'),
                'id'    => 'wpvr_diskeyboard',
                'have_tooltip' => false,
                'tooltip_text' => '',
                'type' => 'checkbox'
            ),
            'keyboardzoom' => array(
                'class' => 'single-settings',
                'title' => __('Keyboard Zoom Control','wpvr'),
                'id'    => 'wpvr_keyboardzoom',
                'have_tooltip' => false,
                'tooltip_text' => '',
                'type' => 'checkbox'
            ),
            'draggable' => array(
                'class' => 'single-settings',
                'title' => __('Mouse Drag Control','wpvr'),
                'id' => 'wpvr_draggable',
                'have_tooltip' => false,
                'tooltip_text' => '',
                'type' => 'checkbox'
            ),
            'mouseZoom' => array(
                'class' => 'single-settings',
                'title' => __('Mouse Zoom Control','wpvr'),
                'id' => 'wpvr_mouseZoom',
                'have_tooltip' => false,
                'tooltip_text' => '',
                'type' => 'checkbox'
            ),
            'gyro' => array(
                'class' => 'single-settings gyro',
                'title' => __('Gyroscope Control','wpvr'),
                'id' => 'wpvr_gyro',
                'have_tooltip' => false,
                'tooltip_text' => '',
                'type' => 'checkbox'
            ),
            'deviceorientationcontrol' => array(
                'class' => 'single-settings orientation',
                'title' => __('Auto Gyroscope Support','wpvr'),
                'id' => 'wpvr_deviceorientationcontrol',
                'have_tooltip' => true,
                'tooltip_text' => __('If set to true, device orientation control will be used when the panorama is loaded, if the device supports it. If false, device orientation control needs to be activated by pressing a button. Defaults to false. Will work if gyroscope is enabled','wpvr'),
                'type' => 'checkbox'
            ),
            'compass' => array(
                'class' => 'single-settings compass',
                'title' => __('Compass','wpvr'),
                'id' => 'wpvr_compass',
                'have_tooltip' => false,
                'tooltip_text' => '',
                'type' => 'checkbox'
            ),
        );
        return apply_filters( 'modify_advanced_control_left_fields', $fields, $postdata );
    }


    /**
     * Initialise Advanced Settings Right Fields
     * @return array
     * @since 8.0.0
     */
    public static function get_advanced_settings_right_fields($postdata)
    {
        $fields = array(
            'vrgallery' => array(
                'class' => 'single-settings gallery',
                'title' => __('Scene Gallery','wpvr'),
                'id' => 'wpvr_vrgallery',
                'type' => 'checkbox',
                'have_tooltip' => true,
                'tooltip_text' => __('Turning it On will display a gallery with all the scenes on your tour. By double clicking on a scene thumbnail on the gallery, you can move to that specific scene. The gallery will only show up on the front end and not on the preview.','wpvr'),
            ),
            'vrgallery_title' => array(
                'class' => 'single-settings',
                'title' => __('Scene Titles on Gallery','wpvr'),
                'id' => 'wpvr_vrgallery_title',
                'type' => 'checkbox',
                'have_tooltip' => true,
                'tooltip_text' => __('Turning it on will display scene titles on each scene thumbnail inside the Scene Gallery. The Scene IDs will be used as the Scene Title.','wpvr')
            ),
            'vrscene_navigation' => array(
                'class' => 'single-settings scene-navigation',
                'title' => __('Scene Navigation Menu','wpvr'),
                'id' => 'wpvr_scene_navigation',
                'type' => 'checkbox',
                'have_tooltip' => true,
                'tooltip_text' => __('Turning it On will display a gallery with all the scenes on your tour. By double clicking on a scene thumbnail on the gallery, you can move to that specific scene. The gallery will only show up on the front end and not on the preview.','wpvr')
            ),
            'bg_music' => array(
                'class' => 'single-settings',
                'title' => __('Tour Background Music','wpvr'),
                'id' => 'wpvr_bg_music',
                'type' => 'checkbox',
                'have_tooltip' => false,
                'tooltip_text' => ''
            ),
            'explainerSwitch'   => array(
                'class' => 'single-settings company-info',
                'title' => __('Enable explainer video','wpvr'),
                'id'    => 'wpvr_explainerSwitch',
                'have_tooltip'  => false,
                'tooltip_text'  => '',
                'type'  => 'checkbox',
            ),
            'globalzoom'  => array(
                'class' => 'single-settings',
                'title' => __('Set Zoom Preferences','wpvr'),
                'id'    => 'wpvr_globalzoom',
                'have_tooltip' => true,
                'tooltip_text' => __('Zoom interval is 50 to 120 degree. You can put any value between 50 to 120. As an example, if you set 100, the scene will display with zoom in 100 degree on each load.','wpvr'),
                'type' => 'checkbox',
            ),
            'cpLogoSwitch' => array(
                'class' => 'single-settings company-info',
                'title' => __('Add Company Information','wpvr'),
                'id' => 'wpvr_cpLogoSwitch',
                'type' => 'checkbox',
                'have_tooltip' => false,
                'tooltip_text' => ''
            )

        );
        return apply_filters( 'modify_advanced_control_right_fields', $fields, $postdata );
    }


    /**
    * Initialise Control Button Left Fields
    * @return array
    * @since 8.0.0
    */
    public static function get_control_button_left_fields($postdata) {
        $fields = array(
           'panupControl' => array(
                'title' => __('Move Up','wpvr'),
                'icon' => 'move-up.jpg',
                'id' => 'wpvr_panupControl',
                'type' => 'checkbox'
            ),
           'panDownControl' => array(
                'title' => __('Move Down','wpvr'),
                'icon' => 'move-down.jpg',
                'id' => 'wpvr_panDownControl',
                'type' => 'checkbox'
            ),
           'panLeftControl' => array(
                'title' => __('Move Left','wpvr'),
                'icon' => 'move-left.jpg',
                'id' => 'wpvr_panLeftControl',
                'type' => 'checkbox'
            ),
           'panRightControl' => array(
                'title' => __('Move Right','wpvr'),
                'icon' => 'move-right.jpg',
                'id' => 'wpvr_panRightControl',
                'type' => 'checkbox'
            ),
           'panZoomInControl' => array(
                'title' => __('Zoom In','wpvr'),
                'icon' => 'zoom-in.jpg',
                'id' => 'wpvr_panZoomInControl',
                'type' => 'checkbox'
            ),
        );
        return apply_filters( 'modify_control_button_left_fields', $fields, $postdata );
    }


    /**
    * Initialise Control Button Left Fields
    * @return array
    * @since 8.0.0
    */
    public static function get_control_button_right_fields($postdata) 
    {
        $fields = array(
            'panZoomOutControl' => array(
                 'title' => __('Zoom Out','wpvr'),
                 'icon' => 'zoom-out.jpg',
                 'id' => 'wpvr_panZoomOutControl',
                 'type' => 'checkbox'
             ),
            'panFullscreenControl' => array(
                 'title' => __('Full Screen','wpvr'),
                 'icon' => 'full-screen.jpg',
                 'id' => 'wpvr_panFullscreenControl',
                 'type' => 'checkbox'
             ),
            'gyroscope' => array(
                 'title' => __('Gyroscope','wpvr'),
                 'icon' => 'gryscop.jpg',
                 'id' => 'wpvr_gyroscope',
                 'type' => 'checkbox'
             ),
            'backToHome' => array(
                 'title' => __('Home','wpvr'),
                 'icon' => 'home.jpg',
                 'id' => 'wpvr_backToHome',
                 'type' => 'checkbox'
            ),

            'explainer'  => array(
                'title' => __('Explainer','wpvr'),
                'icon'  => 'explainer-vedio.png',
                'id'    => 'wpvr_explainer',
                'type'  => 'checkbox',
            )
        );
        return apply_filters( 'modify_control_button_right_fields', $fields, $postdata );
    }


    /**
     * Initialize Scene setting left fields
     * Panodata is empty
     * @return array
     * @since 8.0.0
     */
    public static function get_scene_left_fields_empty_panodata()
    {
        $fields = array(
            'dscene' => array(
                'class' => 'scene-setting dscene',
                'title' => __('Set as Default','wpvr'),
                'type' => 'select',
                'select_class' => 'dscen',
                'selected' => 'off'
            ),
            'scene-id' => array(
                'label_for' => 'scene-id',
                'title' => __('Scene ID','wpvr'),
                'input_class' => 'sceneid',
                'type' => 'text',
                'value' => '',
                'disabled' => '',
            ),
            'scene-type' => array(
                'label_for' => 'scene-type',
                'title' => __('Scene Type','wpvr'),
                'input_class' => '',
                'type' => 'text',
                'value' => 'equirectangular',
                'disabled' => 'disabled',
            ),
            'scene-attachment-url' => array(
                'title' => __('Scene Upload','wpvr'),
                'type' => 'upload',
                'value' => '',
                'display' => 'none'
            )
        );

        return apply_filters( 'modify_scene_default_left_fields', $fields );
    }


    /**
     * Initilize Scene Settings left Fields
     * Panodata is not empty
     * @param mixed $dscene
     * @param mixed $scene_id
     * @param mixed $scene_photo
     * 
     * @return array
     * @since 8.0.0
     */
    public static function get_scene_left_fields_with_panodata($pano_scene)
    {
        $fields = array(
            'dscene' => array(
                'class' => 'scene-setting dscene',
                'title' => __('Set as Default','wpvr'),
                'type' => 'select',
                'select_class' => 'dscen',
                'selected' => $pano_scene['dscene']
            ),
            'scene-id' => array(
                'label_for' => 'scene-id',
                'title' => __('Scene ID','wpvr'),
                'input_class' => 'sceneid',
                'type' => 'text',
                'value' => $pano_scene['scene-id'],
                'disabled' => '',
            ),
            'scene-type' => array(
                'label_for' => 'scene-type',
                'title' => __('Scene Type','wpvr'),
                'input_class' => '',
                'type' => 'text',
                'value' => 'equirectangular',
                'disabled' => 'disabled',
            ),
            'scene-attachment-url' => array(
                'title' => __('Scene Upload','wpvr'),
                'type' => 'upload',
                'value' => $pano_scene['scene-attachment-url'],
                'display' => 'block'
            )
        );

        return apply_filters( 'modify_scene_left_fields', $fields, $pano_scene );
    }


    /**
     * Initialize Hotspot Settings Left Fields
     * @return array
     * 
     * @since 8.0.0
     */
    public static function get_hotspot_left_fields($pano_hotspot)
    {
        $fields = array(
            'hotspot-title' => array(
                'title' => __('Hotspot ID','wpvr'),
                'value' => $pano_hotspot['hotspot-title'],
                'type' => 'text',
                'input_class' => '',
                'input_id' => 'hotspot-title'
            ),
            'hotspot-pitch' => array(
                'title' => __('Pitch','wpvr'),
                'value' => $pano_hotspot['hotspot-pitch'],
                'type' => 'text',
                'input_class' => 'hotspot-pitch',
                'input_id' => ''
            ),
            'hotspot-yaw' => array(
                'title' => __('Yaw','wpvr'),
                'value' => $pano_hotspot['hotspot-yaw'],
                'type' => 'text',
                'input_class' => 'hotspot-yaw',
                'input_id' => ''
            ),
            'hotspot-customclass' => array(
                'title' => __('Hotspot Custom Icon Class','wpvr'),
                'value' => $pano_hotspot['hotspot-customclass'],
                'type' => 'text',
                'input_class' => '',
                'input_id' => 'hotspot-customclass'
            ),
        );
        return apply_filters( 'modify_hotspot_left_fields', $fields, $pano_hotspot );
    }


    /**
     * Initialize Hotspot Setting Right Fields
     * @return array
     * 
     * @since 8.0.0
     */
    public static function get_hotspot_right_fields()
    {
        $fields = array(
            'hotspot-type' => array(
                'title' => __('Hotspot-Type','wpvr'),
                'type' => 'info_type_select',
            ),
            'hotspot-url' => array(
                'title' => __('URL','wpvr'),
                'type' => 'info_url',
                'value' => '',
                'display' => 'block',
            ),
            'wpvr_url_open' => array(
                'title' => __('Open in same tab','wpvr'),
                'type'  => 'same_tab_checkbox',
                'value' => 'off',
                'display'   => 'flex'
            ),
            'hotspot-content' => array(
                'class' => 'hotspot-content',
                'title' => __('On Click Content','wpvr'),
                'type' => 'textarea',
            ),
            'hotspot-hover' => array(
                'class' => 'hotspot-hover',
                'title' => __('On Hover Content','wpvr'),
                'type' => 'textarea'
            ),
            'hotspot-scene-list' => array(
                'title' => __('Select Target Scene from List','wpvr'),
                'type' => 'scene_select',
            ),
            'hotspot-scene' => array(
                'title' => __('Target Scene ID','wpvr'),
                'display' => 'none',
                'input_class' => 'hotspotsceneinfodata',
                'type' => 'disabled_text',
                'value' => ''
            )
        );
        return apply_filters( 'modify_hotspot_right_fields', $fields );
    }


    /**
     * Initialize Hotspot Setting Info Fields
     * 
     * @param mixed $hotspot_type
     * @param mixed $hotspot_url
     * @param mixed $hotspot_content
     * @param mixed $hotspot_hover
     * 
     * @return array
     * @since 8.0.0
     */
    public static function get_hotspot_setting_info_fields($pano_hotspot)
    {
        $fields = array(
            'hotspot-type' => array(
                'title' => __('Hotspot-Type','wpvr'),
                'type' => 'info_type_select',
            ),
            'hotspot-url' => array(
                'title' => __('URL','wpvr'),
                'type' => 'info_url',
                'value' => $pano_hotspot['hotspot-url'],
                'display' => 'block',
            ),
            'wpvr_url_open' => array(
                'title' => __('Open in same tab','wpvr'),
                'type'  => 'same_tab_checkbox',
                'value' => isset($pano_hotspot['wpvr_url_open'][0]) ? $pano_hotspot['wpvr_url_open'][0] : 'off',
                'display' => 'flex',
            ),
            'hotspot-content' => array(
                'class' => 'hotspot-content',
                'title' => __('On Click Content','wpvr'),
                'type' => 'info_textarea',
                'value' => $pano_hotspot['hotspot-content'],
                'display' => 'block',
            ),
            'hotspot-hover' => array(
                'class' => 'hotspot-hover',
                'title' => __('On Hover Content','wpvr'),
                'type' => 'info_textarea',
                'value' => $pano_hotspot['hotspot-hover'],
                'display' => 'block',
            ),
            'hotspot-scene-list' => array(
                'title' => __('Select Target Scene from List','wpvr'),
                'type' => 'scene_list',
                'display' => 'none',
            ),
            'hotspot-scene' => array(
                'title' => __('Target Scene ID','wpvr'),
                'display' => 'none',
                'input_class' => 'hotspotsceneinfodata',
                'type' => 'disabled_text',
                'value' => ''
            )
        );
        return apply_filters( 'modify_hotspot_right_fields', $fields );
        
    }

    /**
     * Initialize Hotspot Setting Info Fields
     *
     * @param mixed $hotspot_type
     * @param mixed $hotspot_url
     * @param mixed $hotspot_content
     * @param mixed $hotspot_hover
     *
     * @return array
     * @since 8.0.0
     */
    public static function get_hotspot_setting_wc_product_fields($pano_hotspot)
    {
        $fields = array(
            'hotspot-type' => array(
                'title' => __('Hotspot-Type','wpvr'),
                'type' => 'wc_product_type',
            ),
            'hotspot-product-id' => array(
                'title' => __('Select your form','wpvr'),
                'type' => 'wc_product_select',
                'class' => 'wpvr-product-search',
                'value' => $pano_hotspot
            ),
            'hotspot-url' => array(
                'title' => __('URL','wpvr'),
                'type' => 'info_url',
                'value' => $pano_hotspot['hotspot-url'],
                'display' => 'none',
            ),
            'wpvr_url_open' => array(
                'title' => __('Open in same tab','wpvr'),
                'type'  => 'same_tab_checkbox',
                'value' => isset($pano_hotspot['wpvr_url_open'][0]) ? $pano_hotspot['wpvr_url_open'][0] : 'off',
                'display' => 'none',
            ),
            'hotspot-content' => array(
                'class' => 'hotspot-content',
                'title' => __('On Click Content','wpvr'),
                'type' => 'info_textarea',
                'value' => $pano_hotspot['hotspot-content'],
                'display' => 'none',
            ),
            'hotspot-hover' => array(
                'class' => 'hotspot-hover',
                'title' => __('On Hover Content','wpvr'),
                'type' => 'info_textarea',
                'value' => $pano_hotspot['hotspot-hover'],
                'display' => 'block',
            ),
            'hotspot-scene-list' => array(
                'title' => __('Select Target Scene from List','wpvr'),
                'type' => 'scene_list',
                'display' => 'none',
            ),
            'hotspot-scene' => array(
                'title' => __('Target Scene ID','wpvr'),
                'display' => 'none',
                'input_class' => 'hotspotsceneinfodata',
                'type' => 'disabled_text',
                'value' => ''
            )
        );
        return apply_filters( 'modify_hotspot_right_fields', $fields );

    }
    /**
     * Initialize Hotspot Setting Fluent form field Fields
     *
     * @param mixed $hotspot_type
     * @param mixed $hotspot_url
     * @param mixed $hotspot_content
     * @param mixed $hotspot_hover
     *
     * @return array
     * @since 8.0.0
     */
    public static function get_hotspot_setting_fluent_form_fields($pano_hotspot)
    {
        $fields = array(
            'hotspot-type' => array(
                'title' => __('Hotspot-Type','wpvr'),
                'type' => 'fluent_form_type',
            ),
            'fluent-form-id' => array(
                'title' => __('Select your form','wpvr'),
                'type' => 'fluent_form_select',
                'class' => 'wpvr-fluent-forms',
                'value' => $pano_hotspot
            ),
            'hotspot-url' => array(
                'title' => __('URL','wpvr'),
                'type' => 'info_url',
                'value' => $pano_hotspot['hotspot-url'],
                'display' => 'none',
            ),
            'wpvr_url_open' => array(
                'title' => __('Open in same tab','wpvr'),
                'type'  => 'same_tab_checkbox',
                'value' => isset($pano_hotspot['wpvr_url_open'][0]) ? $pano_hotspot['wpvr_url_open'][0] : 'off',
                'display' => 'none',
            ),
            'hotspot-content' => array(
                'class' => 'hotspot-content',
                'title' => __('On Click Content','wpvr'),
                'type' => 'info_textarea',
                'value' => $pano_hotspot['hotspot-content'],
                'display' => 'none',
            ),
            'hotspot-hover' => array(
                'class' => 'hotspot-hover',
                'title' => __('On Hover Content','wpvr'),
                'type' => 'info_textarea',
                'value' => $pano_hotspot['hotspot-hover'],
                'display' => 'block',
            ),
            'hotspot-scene-list' => array(
                'title' => __('Select Target Scene from List','wpvr'),
                'type' => 'scene_list',
                'display' => 'none',
            ),
            'hotspot-scene' => array(
                'title' => __('Target Scene ID','wpvr'),
                'display' => 'none',
                'input_class' => 'hotspotsceneinfodata',
                'type' => 'disabled_text',
                'value' => ''
            )
        );
        return apply_filters( 'modify_hotspot_right_fields', $fields );

    }


    /**
     * Initializa Hotspot Setting Scene Fields
     * 
     * @param mixed $hotspot_hover
     * @param mixed $hotspot_target_scene
     * 
     * @return array
     * @since 8.0.0
     */
    public static function get_hotspot_setting_scene_fields($pano_hotspot)
    {
        $fields = array(
            'hotspot-type' => array(
                'title' => __('Hotspot-Type','wpvr'),
                'type' => 'scene_type_select',
            ),
            'hotspot-url' => array(
                'title' => __('URL','wpvr'),
                'type' => 'info_url',
                'display' => 'none',
                'value' => '',
            ),
            'wpvr_url_open' => array(
                'title' => __('Open in same tab','wpvr'),
                'type'  => 'same_tab_checkbox',
                'value' => 'off',
                'display' => 'none',
            ),
            'hotspot-content' => array(
                'class' => 'hotspot-content',
                'title' => __('On Click Content','wpvr'),
                'type' => 'scene_content',
                'display' => 'none',
            ),
            'hotspot-hover' => array(
                'class' => 'hotspot-hover',
                'title' => __('On Hover Content','wpvr'),
                'type' => 'info_textarea',
                'value' => $pano_hotspot['hotspot-hover'],
                'display' => 'block',
            ),
            'hotspot-scene-list' => array(
                'title' => __('Select Target Scene from List','wpvr'),
                'type' => 'scene_list',
                'display' => 'block',
            ),
            'hotspot-scene' => array(
                'title' => __('Target Scene ID','wpvr'),
                'display' => 'block',
                'input_class' => 'hotspotsceneinfodata',
                'type' => 'disabled_text',
                'value' => $pano_hotspot['hotspot-scene']
            ),
        );
        return apply_filters( 'modify_hotspot_setting_scene_fields', $fields, $pano_hotspot );
    }


    /**
     * Return general feature navigation meta fields
     * 
     * @return array
     * @since 8.0.0
     */
    public static function get_general_navigation_meta_fields()
    {
        $fields = array(
            array(
                'class' => 'gen-basic',
                'active' => 'active',
                'href' => 'gen-basic',
                'isPro' => false,
                'regular_icon' => 'admin/icon/basic-settings-regular.png',
                'hover_icon' => 'admin/icon/basic-settings-hover.png',
                'title' => __('Basic Settings','wpvr')
            ),
            array(
                'class' => 'gen-advanced',
                'active' => '',
                'href' => 'gen-advanced',
                'isPro' => true,
                'regular_icon' => 'admin/icon/advance-control-regular.png',
                'hover_icon' => 'admin/icon/advance-control-hover.png',
                'title' => __('Advanced Controls','wpvr')
            ),
            array(
                'class' => 'gen-control',
                'active' => '',
                'href' => 'gen-control',
                'isPro' => true,
                'regular_icon' => 'admin/icon/control-buttons-regular.png',
                'hover_icon' => 'admin/icon/control-buttons-hover.png',
                'title' => __('Control Buttons','wpvr')
            )
        );

        return apply_filters( 'make_is_pro_false', $fields );
    }


    /**
     * Render inner navigation bar for General tab
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_general_inner_navigation()
    {
        ob_start();
        ?>

        <ul class="inner-nav">
            
            <?php 
            $fields = WPVR_Meta_Field::get_general_navigation_meta_fields();
            foreach($fields as $field) { ?>

            <li class="<?php echo $field['class']; ?> <?php echo $field['active']; ?>">
                <span data-href="#<?php echo $field['href']; ?>">
                <?php if($field['isPro'] == true) { ?>
                <span class="pro-tag">pro</span>
                <?php } ?>    
                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL . $field['regular_icon']; ?>" alt="icon" class="regular" />
                <img loading="lazy" src="<?php echo WPVR_PLUGIN_DIR_URL. $field['hover_icon']; ?>" alt="icon" class="hover" />
                <?php echo __($field['title'], 'wpvr');?></span>
            </li>

            <?php  } ?>

            <li class="vr-documentation">
                <a href="https://rextheme.com/docs-category/wp-vr/" target="_blank"><?php echo __('Documentation ', 'wpvr'); ?></a>
            </li>

        </ul>

        <?php
        ob_end_flush();
    }


    /**
     * Render Hotspot Setting Left Fields
     * @return void
     * 
     * @since 8.0.0
     */
    public static function render_hotspot_setting_left_fields($pano_hotspot)
    {
        $fields = self::get_hotspot_left_fields($pano_hotspot);
        foreach($fields as $name => $val) {
            self::{ 'render_hotspot_' . $val['type'] . '_field' }( $name, $val );
        }
    }


    /**
     * Render Hotspot Setting Right Fileds
     * @return void
     * 
     * @since 8.0.0
     */
    public static function render_hotspot_setting_right_fields()
    {
        $fields = self::get_hotspot_right_fields();
        foreach($fields as $name => $val) {
            self::{ 'render_hotspot_' . $val['type'] . '_field' }( $name, $val );
        }
    }


    /**
     * Render Hotspot Setting When Hotspot-Type is Info
     * 
     * @param mixed $hotspot_url
     * @param mixed $hotspot_content
     * @param mixed $hotspot_hover
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_hotspot_setting_info_fields($pano_hotspot)
    {
        $fields = self::get_hotspot_setting_info_fields($pano_hotspot);
        foreach($fields as $name => $val) {
            self::{ 'render_hotspot_' . $val['type'] . '_field' }( $name, $val );
        }
    }

    /**
     * Render Hotspot Setting When Hotspot-Type is fluent form
     *
     * @param mixed $hotspot_hover
     * @param mixed $hotspot_target_scene
     *
     * @return void
     * @since 8.0.0
     */
    public static function render_hotspot_setting_fluent_form_fields($pano_hotspot)
    {
        $fields = self::get_hotspot_setting_fluent_form_fields($pano_hotspot);
        foreach($fields as $name => $val) {
            self::{ 'render_hotspot_' . $val['type'] . '_field' }( $name, $val );
        }
    }
    /**
     * Render Hotspot Setting When Hotspot-Type is Woocommerce Product
     *
     * @param mixed $hotspot_hover
     * @param mixed $hotspot_target_scene
     *
     * @return void
     * @since 8.0.0
     */
    public static function render_hotspot_setting_wc_product_fields($pano_hotspot)
    {
        $fields = self::get_hotspot_setting_wc_product_fields($pano_hotspot);
        foreach($fields as $name => $val) {
            self::{ 'render_hotspot_' . $val['type'] . '_field' }( $name, $val );
        }
    }


    /**
     * Render Hotspot Setting When Hotspot-Type is Scene
     * 
     * @param mixed $hotspot_hover
     * @param mixed $hotspot_target_scene
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_hotspot_setting_scene_fields($pano_hotspot)
    {
        $fields = self::get_hotspot_setting_scene_fields($pano_hotspot);
        foreach($fields as $name => $val) {
            self::{ 'render_hotspot_' . $val['type'] . '_field' }( $name, $val );
        }
    }



    /**
     * Render Scene Setting Left fields 
     * When Panodata is Empty
     * @return void
     * @since 8.0.0
     */
    public static function render_scene_left_fields_empty_panodata()
    {
        $fields = self::get_scene_left_fields_empty_panodata();
        foreach($fields as $name => $val) {
            self::{ 'render_scene_' . $val['type'] . '_field' }( $name, $val );
        }
        
    }


    /**
     * Render Scene Setting Left Fields
     * When Panodata is not Empty
     * @param mixed $dscene
     * @param mixed $scene_id
     * @param mixed $scene_photo
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_scene_left_fields_with_panodata($pano_scene)
    {
        $fields = self::get_scene_left_fields_with_panodata($pano_scene);
        foreach($fields as $name => $val) {
            self::{ 'render_scene_' . $val['type'] . '_field' }( $name, $val );
        }
    }


    /**
     * Initialize fields render method
     * @return void
     * @since 8.0.0
     */
    public static function render_advanced_settings_left_fields($postdata)
    {
        $fields = self::get_advanced_settings_left_fields($postdata);
        foreach($fields as $name => $val) {
            self::{ 'render_' . $val['type'] . '_field' }( $name, $val );
        }
    }


    /**
     * Initialize fields render method
     * @return void
     * @since 8.0.0
     */
    public static function render_advanced_settings_right_fields($postdata)
    {
        $fields = self::get_advanced_settings_right_fields($postdata);
        foreach($fields as $name => $val) {
            self::{ 'render_' . $val['type'] . '_field' }( $name, $val );
        }
    }


    /**
    * Initialize fields render method
    * @return void
    * @since 8.0.0
    */
    public static function render_control_button_left_fields($postdata)
    {
        $fields = self::get_control_button_left_fields($postdata);
        foreach($fields as $name => $val) {
            self::{ 'render_' . $val['type'] . '_with_icon' }( $name, $val );
        }
    }


    /**
    * Initialize fields render method
    * @return void
    * @since 8.0.0
    */
    public static function render_control_button_right_fields($postdata)
    {
        $fields = self::get_control_button_right_fields($postdata);
        foreach($fields as $name => $val) {
            self::{ 'render_' . $val['type'] . '_with_icon' }( $name, $val );
        }
    }


    /**
     * Initilize Video Meta Fields
     * @param mixed $postdata
     * 
     * @return array
     * @since 8.0.0
     */
    public static function get__video_setting_fields($postdata)
    {
        $vidurl = '';
        if (isset($postdata['vidid'])) {
            $vidurl = $postdata['vidurl']; 
        } 
        $meta_fields = array(
            'panovideo' => array(
                'class' => 'single-settings videosetup',
                'title' => __('Enable Video','wpvr'),
                'type' => 'radio_button',
                'lists' =>  array(
                        array(
                        'input_class' => 'styled-radio video_off',
                        'input_id' => 'styled-radio',
                        'value' => 'off',
                        'checked' => isset($postdata['vidid']),
                        'label_value' => 'Off'
                        ),
                        array(
                            'input_class' => 'styled-radio video_on',
                            'input_id' => 'styled-radio-0',
                            'value' => 'on',
                            'checked' => isset($postdata['vidid']),
                            'label_value' => 'On'
                        )
                ),
                    
            ),
            'video-attachment-url' => array(
                'class' => 'video-setting',
                'title' => __('Upload or Add Link','wpvr'),
                'placeholder' => __('Paste Youtube or Vimeo link or upload','wpvr'),
                'input_class' => 'video-attachment-url',
                'type' => 'video_text_input',
                'value' => $vidurl
            )
        );
        return apply_filters( 'extend_video_meta_fields', $meta_fields, $postdata, $vidurl );
    }


    /**
     * Render Video Settings Meta Fields
     * @param mixed $postdata
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_video_setting_meta_fields($postdata)
    {
        $fields = self::get__video_setting_fields($postdata);

        foreach($fields as $name => $val) {
            self::{ 'render_' . $val['type'] }( $name, $val );
        }
        
    }


    /**
     * Render Scene Settings Select Field
     * @param mixed $name input name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_scene_select_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>
        <div class="<?= $class; ?>">
            <label><?= __($title .': ', 'wpvr'); ?></label>
            <select class="<?= $select_class; ?>" name="<?= $name; ?>">
                <option value="on" <?php selected( $selected, 'on' ); ?> > <?php echo __('Yes','wpvr') ?> </option>
                <option value="off" <?php selected( $selected, 'off' ); ?> > <?php echo __('No','wpvr') ?></option>
            </select>
        </div>
        <?php 
        ob_end_flush();
    }


    /**
     * Render Scene Type selection field on Scene tab content
     * 
     * @param mixed $name
     * @param mixed $val
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_scene_type_select_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>
        <div class="scene-setting">
            <label for="scene-type"><?= __($title .': ', 'wpvr'); ?></label>
            <select class="wpvr-pro-select-scene-type" name="scene-type" id="">
                <option value="equirectangular" <?= $selected == 'equirectangular' ? 'selected' : '' ?> > <?php echo __('Equirectangular','wpvr')  ?></option>
                <option value="cubemap" <?= $selected == 'cubemap' ? 'selected' : '' ?> > <?php echo __('Cubemap','wpvr') ?></option>
            <select>
        </div>
        <?php 
        ob_end_flush();
    }


    /**
     * Render Scene Settings Text Field
     * @param mixed $name input name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_scene_text_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>
        <div class=scene-setting>
            <label for="<?= $label_for; ?>"><?= __($title .': ', 'wpvr'); ?></label>
            <input type="text" class="<?= $input_class; ?>" name="<?= $name; ?>" value="<?= $value; ?>" <?= $disabled; ?> />
        </div>
        <?php 
        ob_end_flush();
    }


    /**
     * Render Scene Settings Upload Field
     * @param mixed $name input name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_scene_upload_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>
        <div class=scene-setting>
            <label for="scene-upload"><?= __($title .': ', 'wpvr'); ?></label>
            <div class="form-group">
                <img loading="lazy" src="<?= $value; ?>" style="display: <?= $display; ?>;"><br>
                <input type="button" class="scene-upload" data-info="" value="Upload"/>
                <input type="hidden" name="scene-attachment-url" class="scene-attachment-url" value="<?= $value; ?>">
            </div>
        </div>
        <?php 
        ob_end_flush();
    }


    /**
     * Render Scene upload wrapper fields for pro version
     * 
     * @param mixed $name
     * @param mixed $val
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_scene_upload_wrapper_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>
        <div class="scene-setting scene-upload-wrapper">
            <?php
            foreach($wrappers as $key => $val) {
                self::{ 'render_scene_' . $key }( $key, $val );
            }
            ?>
        </div>
        <?php 
        ob_end_flush();
    }


    /**
     * Render equirectangular scene upload section for pro version
     * 
     * @param mixed $key
     * @param mixed $val
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_scene_equirectangular_upload($key, $val)
    {
        extract($val);
        ob_start();
        ?>
        <div class="equirectangular-upload" style="display:<?= $display?>;">
            <label for="scene-upload"><?= __('Scene Upload: ', 'wpvr')?></label>
            <div class="form-group">
                <img loading="lazy" src="<?= $value?>" style="display: <?= $img_display?>;"><br>
                <input type="button" class="scene-upload" data-info="" value="Upload"/>
                <input type="hidden" name="scene-attachment-url" class="scene-attachment-url" value="<?= $value?>">
            </div>

            <div class="field-tooltip">
                <img loading="lazy" src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/question.png'?>" alt="icon" />
                <span><?= __('You can use any image size but maximum image upload size recommended to support all device is 4096x2000 px for perfect responsive view. To check 360 view, click on preview button and check tour preview metabox.', 'wpvr') ?></span>
            </div>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Render cubemap scene upload section for pro version
     * 
     * @param mixed $key
     * @param mixed $val
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_scene_cubemap_upload($key, $val)
    {
        extract($val);
        ob_start();
        ?>
        <div class="cubemap-upload" style="display:<?= $display?>;">
            <?php
            foreach($cubemaps as $cubemap){ extract($cubemap) ?>
                <div class="<?= $class ?>">
                <label for="scene-upload"><?= __($title , 'wpvr-pro') ?></label>
                <div class="form-group">
                    <img loading="lazy" src="<?= $value ?>" style="display: block;">
                    <input type="button" class="scene-upload" data-info="" value="Upload"/>
                    <input type="hidden" name="<?= $name ?>" class="scene-attachment-url" value="<?= $value ?>">
                </div>

                <div class="field-tooltip">
                    <img loading="lazy" src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/question.png' ?>" alt="icon" />
                    <span><?= __('You can use any image size but maximum image upload size recommended to support all device is 4096x2000 px for perfect responsive view. To check 360 view, click on preview button and check tour preview metabox.', 'wpvr') ?></span>
                </div>
            </div>
            <?php }
            ?>
        </div>
        <?php
        ob_end_flush();

    }


    /**
     * Render Radio Button Field
     * @param mixed $name input name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_radio_button($name, $val)
    {
        extract( $val );
        ob_start();
        ?>
        <div class="<?= $class; ?>">
            <span><?= __($title .': ', 'wpvr'); ?></span>
            <ul>

                <?php foreach($lists as $list) { extract( $list ); ?>
                <li class="radio-btn">
                    <input class="<?= $input_class; ?>" id="<?= $input_id; ?>" type="radio" name="<?= $name; ?>" value="<?= $value; ?>" <?php if(empty($checked) && $value == 'off') { echo 'checked'; } ;?> <?php if(!empty($checked) && $value == 'on') { echo 'checked'; };?> >
                    <label for="<?= $input_id; ?>"><?= $label_value; ?></label>
                </li>

                <?php } ?>
                
            </ul>
        </div>
        <?php 
        ob_end_flush();
    }


    /**
     * Render Radio Button Field for pro version
     * @param mixed $name input name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_pro_radio_button($name, $val)
    {
        extract( $val );
        ob_start();
        ?>
        <div class="<?= $class; ?>">
            <span><?= __($title .': ', 'wpvr'); ?></span>
            <ul>

                <?php foreach($lists as $list) { extract( $list ); ?>
                <li class="radio-btn">
                    <input class="<?= $input_class; ?>" id="<?= $input_id; ?>" type="radio" name="<?= $name; ?>" value="<?= $value; ?>" <?php if(($checked == 'off' || empty($checked)) && $value == 'off') { echo 'checked'; } ;?> <?php if($checked == 'on' && $value == 'on') { echo 'checked'; };?> >
                    <label for="<?= $input_id; ?>"><?= $label_value; ?></label>
                </li>

                <?php } ?>
                
            </ul>
        </div>
        <?php 
        ob_end_flush();
    }

    
    /**
     * Render Video Text Input Field
     * @param mixed $name input name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_video_text_input($name, $val)
    {
        extract( $val );
        ob_start();
        ?>
        <div class="<?= $class; ?>" style="display:none;">
            <div class="single-settings">
                <span><?= __($title .': ', 'wpvr'); ?></span>
                <div class="form-group">
                    <input type="text" placeholder="<?= $placeholder; ?>" name="<?= $name; ?>" class="<?= $input_class; ?>" value="<?= $value; ?>">
                    <input type="button" class="video-upload" data-info="" value="Upload"/>
                </div>
            </div>
        </div>
        <?php 
        
        ob_end_flush();

    }

    /**
	 * Render Checkbox Field
     * 
	 * @param  string $name input name
     * @param  string $val options  
     * 
	 * @return void     
     * @since 8.0.0
	 */
    public static function render_checkbox_field($name, $val)
    {
        extract( $val );
        ob_start();
        ?>
        <div class="<?= $class;?>">
            <span><?= __($title.': ', 'wpvr'); ?></span>

            <span class="wpvr-switcher">
                <input id="<?= $id;?>" class="vr-switcher-check" value="off" name="<?= $name;?>" type="checkbox" disabled />
                <label for="<?= $id;?>" title="Pro Feature"></label>
            </span>
            <?php if($have_tooltip) {?>
            <div class="field-tooltip">
                <img loading="lazy" src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/question.png' ?>" alt="icon" />
                <span><?= __($tooltip_text, 'wpvr'); ?></span>
            </div>
            <?php } ?>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Render checkbox for advanced control pro version meta fields
     * 
     * @param mixed $name
     * @param mixed $val
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_pro_checkbox_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>
        <div class="<?= $class; ?>">
            <span><?= __($title .': ', 'wpvr'); ?></span>

            <span class="wpvr-switcher">
                <input id="<?= $id; ?>" class="vr-switcher-check" value="<?= $value?>" name="<?= $name; ?>" type="checkbox" <?php if($value == 'on') { echo'checked'; } ?> />
                <label for="<?= $id; ?>"></label>
            </span>
            <?php if($have_tooltip) { ?>
                <div class="field-tooltip">
                    <img loading="lazy" src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/question.png' ?>" alt="icon" />
                    <span><?= __($tooltip_text, 'wpvr'); ?></span>
                </div>
            <?php } ?>
        </div>
        <?php
        ob_end_flush();
    }


    public static function render_explainer_info_wrapper_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>
        <div class="explainer-info-wrapper">
            <div class="single-settings cp-details">
                <span><?= __($title .': ', 'wpvr'); ?></span>
                <textarea rows="5" cols="40" name="explaine-content" id="explaine-content"><?= $value?></textarea>
            </div>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Render checkbox for advanced control pro version meta fields
     * 
     * @param mixed $name
     * @param mixed $val
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_pro_inner_checkbox_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>
        <div class="<?= $root_class; ?>">
            <div class="<?= $class; ?>">
                <span><?= __($title .': ', 'wpvr'); ?></span>

                <span class="wpvr-switcher">
                    <input id="<?= $id; ?>" class="vr-switcher-check" value="<?= $value; ?>" name="<?= $name; ?>" type="checkbox" <?php if($value == 'on') { echo 'checked'; } else { echo ''; } ?> />
                    <label for="<?= $id; ?>"></label>
                </span>

                <?php if($have_tooltip) { ?>
                <div class="field-tooltip">
                    <img loading="lazy" src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/question.png' ?>" alt="icon" />
                    <span><?= __($tooltip_text, 'wpvr'); ?></span>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Render background music field on Advanced Controls section
     * 
     * @param mixed $name
     * @param mixed $val
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_bg_music_content_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>
        <div class="bg-music-content" style="display:none">
            <?php
            foreach($inner_fields as $name => $val) {
                self::{ 'render_' . $val['type'] . '_field' }( $name, $val );
            }
            ?>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Render Upload or Set Audio Link field on Advanced Controls section
     * 
     * @param mixed $name
     * @param mixed $val
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_upload_audio_link_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>
        <div class="single-settings audio-setting">
            <span><?= __($title .': ', 'wpvr'); ?></span>
            <img loading="lazy" class="audio-img" src="<?= $value; ?>" style="display: none;">
            <input type="text" name="<?= $name; ?>" class="audio-attachment-url" value="<?= $value; ?>">
            <button type="button" class="audio-upload" data-info=""><img src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/upload.png' ?>" alt="icon" /></button>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Render Add Company information field on Advanced Controls section
     * 
     * @param mixed $name
     * @param mixed $val
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_company_info_wrapper_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>
        <div class="company-info-wrapper">
            <div class="single-settings cp-logo-area">
                <span class="logo-title"><?= __($title .': ', 'wpvr'); ?>
                <span class="hints"><?= __('You can add any logo size. But recommended size is below 100x100 px for perfect look.', 'wpvr-pro') ?></span>
                </span>

                <div class="form-group">
                    <input type="text" name="cp-logo-attachment-url" class="cp-logo-attachment-url" value="<?= $value ?>">
                    <input type="button" class="cp-logo-upload" id="cp-logo-upload" data-info="" value="Upload"/>

                    <div class="logo-upload-frame" >
                    <label for="cp-logo-upload">
                    <img loading="lazy" class="cp-logo-img" src="<?= $value ?>">
                    <img loading="lazy" src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/uplad-icon.png' ?>" class="placeholder-icon" alt="icon" style="display: <?php if($value != null) { echo 'none'; }  ?>;" />
                    </label>
                    </div>
                </div>
            </div>
            <div class="single-settings cp-details">
                <span><?= __('Company Details : ', 'wpvr-pro') ?></span>
                <textarea rows="5" cols="40" name="cp-logo-content" id="cp-logo-content"><?= $cpLogoContent;?></textarea>
            </div>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Render text input fields while ro version is active
     * 
     * @param mixed $name
     * @param mixed $val
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_pro_input_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>
        <div class="<?= $class ?>" >
            <span><?= __($title .': ', 'wpvr'); ?></span>
            <input type="text" class="<?= $input_class ?>" name="<?= $name ?>" placeholder="<?= $placeholder ?>" value="<?= $value ?>" />

            <div class="field-tooltip">
                <img loading="lazy" src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/question.png' ?>" alt="icon" />
                <span><?= __($tooltip_text, 'wpvr'); ?></span>
            </div>
        </div>
        <?php
        ob_end_flush();
    }



    /**
	 * Render Checkbox Field With Icon
     * 
	 * @param  string $name input name
     * @param  string $val options  
     * 
	 * @return void
     * @since 8.0.0     
	 */
    public static function render_checkbox_with_icon($name, $val)
    {
        extract( $val );
        ob_start();
        ?>
            <div class="single-settings controls custom-data-set">
                <span><?= __($title . ': ', 'wpvr'); ?></span>

                <div class="color-icon">
                    <img loading="lazy" src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/'. $icon; ?>" alt="icon" />
                </div>

                <span class="wpvr-switcher">
                <input id="<?= $id; ?>" class="vr-switcher-check" value="off" name="<?= $name; ?>" type="checkbox" disabled />
                <label for="<?= $id; ?>" title="Pro Feature"></label>
                </span>

            </div>
        <?php
        ob_end_flush();
    }


    /**
     * Render checkbox fields on Control Buttons section while pro version is active
     * 
     * @param mixed $name
     * @param mixed $val
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_pro_checkbox_with_icon($name, $val)
    {
        extract( $val );
        ob_start();
        ?>
        <div class="single-settings controls custom-data-set">
            <span><?= __($title . ': ', 'wpvr'); ?></span>

            <div class="color-icon">
                <div class="colors">
                    <input type="color" class="<?= $color_name; ?>" name="<?= $color_name; ?>" value="<?= $color_value; ?>" />
                    <input type="hidden" class="<?= $icon_name ?> icon-found-value" name="<?= $icon_name ?>" value="<?= $color_value; ?>" />
                </div>

                <div class="icons">
                    <select class="<?= $icon_select_class ?>" name="<?= $icon_select_name ?>">
                        <?php
                        foreach ($custom_icons as $cikey => $civalue) {
                            if ($cikey == $icon) { ?>
                                <option value="<?= $cikey ?>" selected> <?= $civalue ?></option>
                            <?php } else { ?>
                                <option value="<?= $cikey ?>"> <?= $civalue ?></option>
                           <?php }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <span class="wpvr-switcher">
                <input id="<?= $id; ?>" class="vr-switcher-check" value="<?= $value ?>" name="<?= $name; ?>" type="checkbox" <?php if($value == 'on') { echo 'checked'; } ?> />
                <label for="<?= $id; ?>"></label>
            </span>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Render Basic Setting Preview Image
     * @param mixed $name input name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_preview_image($name, $val)
    {
        extract( $val );
        ob_start();
        ?>
        <div class="<?= $class; ?>">
            <span><?= __($title.' : ', 'wpvr'); ?></span>
            <div class="form-group">
                <input type="text" name="<?= $name; ?>" class="preview-attachment-url" value="<?= $value;?>">
                <input type="button" class="preview-upload" id="vr-preview-img" data-info="" value="Upload"/>
                <div class="img-upload-frame <?php if(!empty($value)) { echo 'img-uploaded'; } ?>" style="background-image: url(<?= $value; ?>)">
                    <span class="remove-attachment">x</span>
                    <label for="vr-preview-img">
                        <img loading="lazy" src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/uplad-icon.png'; ?>" alt="preview img" />
                        <span><?= __('Click to Upload an Image ', 'wpvr'); ?></span>
                    </label>
                </div>
            </div>
            <?php if(!empty($value)) { ?>
            <span class="hints"><?= __('This option will not work if the "Tour Autoload" is turned on.', 'wpvr'); ?></span>
            <?php } ?>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Render Basic Setting Preview Message
     * @param mixed $name input name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_preview_image_msg($name, $val)
    {
        extract( $val );
        ob_start();
        ?>
        <div class="<?= $class; ?>">
            <span><?= __($title.': ', 'wpvr'); ?></span>
            <input class="previewtext" type="text" name="<?= $name; ?>" value="<?= $value; ?>"/>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Render Basc Setting Checkbox
     * @param mixed $name input name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_basic_setting_checkbox($name, $val)
    {
        extract( $val );
        ob_start();
        ?>
        <div class="<?= $class; ?>">
            <span><?= __($title.': ', 'wpvr'); ?></span>

            <span class="wpvr-switcher">
                <input id="<?= $id;?>" class="vr-switcher-check" name="<?= $name; ?>" type="checkbox" value="1" <?php checked( $checked, 1 ); ?> />
                <label for="<?= $id;?>"></label>
            </span>

            <?php if($have_tooltip) {?>
            <div class="field-tooltip">
                <img src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/question.png' ?>" alt="icon" />
                <span><?= __($tooltip_text, 'wpvr'); ?></span>
            </div>
            <?php } ?>
        </div>
        <?php
        ob_end_flush();
    }

    public static function render_generic_form_checkbox($name, $val)
    {
        extract( $val );
        ob_start();
        ?>
        <div class="<?= $class; ?>">
            <span><?= __($title.': ', 'wpvr'); ?></span>

            <span class="wpvr-switcher">
                <input id="<?= $id;?>" class="vr-switcher-check" name="<?= $name; ?>" type="checkbox" value="<?= $val['checked']; ?>" <?php echo $val['checked']=='on'? 'checked' : '' ?> />
                <label for="<?= $id;?>"></label>
            </span>

            <?php if($have_tooltip) {?>
            <div class="field-tooltip">
                <img src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/question.png' ?>" alt="icon" />
                <span><?= __($tooltip_text, 'wpvr'); ?></span>
            </div>
            <?php } ?>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Render Numder Input Field
     * @param mixed $name input name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_number_field($name, $val)
    {
        extract( $val );
        ob_start();
        ?>
        <div class="<?= $class; ?>">
            <span><?= __($title.': ', 'wpvr'); ?></span>
            <input type="number" name="<?= $name; ?>" value="<?= $value; ?>" placeholder="<?= $placeholder;?>" />

            <?php if($have_tooltip) {?>
            <div class="field-tooltip">
                <img src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/question.png'; ?>" alt="icon" />
                <span><?= __($tooltip_text, 'wpvr'); ?></span>
            </div>
            <?php } ?>
        </div>
        <?php
        ob_end_flush();
    }

    public static function render_text_field($name, $val)
    {
        extract( $val );
        ob_start();
        ?>
        <div class="<?= $class; ?>">
            <span><?= __($title.': ', 'wpvr'); ?></span>
            <input type="text" name="<?= $name; ?>" value='<?= $value; ?>' placeholder="<?= $placeholder;?>" />

            <?php if($have_tooltip) {?>
            <div class="field-tooltip">
                <img src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/question.png'; ?>" alt="icon" />
                <span><?= __($tooltip_text, 'wpvr'); ?></span>
            </div>
            <?php } ?>
        </div>
        <?php
        ob_end_flush();
    }


    /**
     * Render Hotspot Setting Text Fields
     * @param mixed $name input field name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_hotspot_text_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>

        <div class="hotspot-setting">
            <label for="<?= $input_id;?>"><?= __($title.': ', 'wpvr'); ?></label>
            <input type="text" id="<?= $input_id;?>" value="<?= $value;?>" class="<?= $input_class;?>" name="<?= $name;?>"/>
        </div>

        <?php
        ob_end_flush();
    }


    /**
     * Render checkbox for same tab feature on hotspot
     * 
     * @param mixed $name
     * @param mixed $val
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_hotspot_same_tab_checkbox_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>

        <div class="single-settings s_tab" style="display:<?= $display ?>;">
            <span><?= __($title.': ', 'wpvr'); ?></span> 
            <label class="wpvr-switcher-v2">
                <input type="checkbox" class="wpvr_url_open" name="<?= $name;?>" value="<?= $value;?>" <?php if($value == 'on') { echo 'checked'; } ?> >
                <span class="switcher-box"></span>
            </label>
        </div>

        <?php
        ob_end_flush();
    }


    /**
     * Render Hotspot Setting Pro Version Text Fields
     * @param mixed $name input field name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_hotspot_pro_text_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>

        <div class="hotspot-scene" style="display:none;" >
            <label for="<?= $name;?>"><?= __($title.': ', 'wpvr'); ?></label>
            <input class="<?= $name;?>" type="text" name="<?= $name;?>"/>
        </div>

        <?php
        ob_end_flush();
    }


    /**
     * Render Hotspot Setting Terget Scene related text fields
     * @param mixed $name input field name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_hotspot_terget_scene_pro_text_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>

        <div class="hotspot-scene" style="display:block;" >
            <label for="<?= $name;?>"><?= __($title.': ', 'wpvr'); ?></label>
            <input class="<?= $name;?>" type="text" name="<?= $name;?>" value="<?= $value ?>" />
        </div>

        <?php
        ob_end_flush();
    }


    /**
     * Render Hotspot Info Type Selct Field
     * @param mixed $name input field name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_hotspot_info_type_select_field($name, $val)
    {
        extract($val);
        $default_type = apply_filters('wpvr_hotspot_types', array(
            'info' => __('Info', 'wpvr-pro'),
            'scene' => __('Scene', 'wpvr-pro'),
        ));
        ob_start();
        ?>

        <label for="hotspot-type"><?= __($title .': ', 'wpvr'); ?></label>
        <select name="<?= $name;?>">
        <?php
        $hotspot_type = 'info';
        foreach ($default_type as $key => $value) {
            echo sprintf("<option %s value='%s'>%s</option>\n", selected($key, $hotspot_type, false), esc_attr($key), esc_attr($value));
        } ?>
        </select>

        <?php
        do_action('hotspot_info_before_hover_content', 'info', array());
        ob_end_flush();
    }


    /**
     * Render Hotspot Scene Type Selct Field
     * @param mixed $name input field name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_hotspot_scene_type_select_field($name, $val)
    {
        extract($val);
        $default_type = apply_filters('wpvr_hotspot_types', array(
            'info' => __('Info', 'wpvr-pro'),
            'scene' => __('Scene', 'wpvr-pro'),
        ));
        ob_start();
        ?>

        <label for="hotspot-type"><?= __($title .': ', 'wpvr'); ?></label>
        <select class="trtr" name="<?= $name;?>">
        <?php
        $hotspot_type = 'scene';
        foreach ($default_type as $key => $value) {
            echo sprintf("<option %s value='%s'>%s</option>\n", selected($key, $hotspot_type, false), esc_attr($key), esc_attr($value));
        } ?>
        </select>

        <?php
        do_action('hotspot_info_before_hover_content', 'scene', array());
        ob_end_flush();
    }


    /**
     * Render Hotspot Info Type URL field
     * @param mixed $name input field name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.o
     */
    public static function render_hotspot_info_url_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>

        <div class="hotspot-url" style="display:<?= $display;?>;">
            <label for="hotspot-url"><?= __($title .': ', 'wpvr'); ?></label>
            <input type="url" name="<?= $name;?>" value="<?= $value;?>" />
        </div>

        <?php
        ob_end_flush();
    }


    /**
     * Render Hotspot Textarea Field
     * @param mixed $name input field name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_hotspot_textarea_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>

        <div class="<?= $class;?>">
            <label for="hotspot-content"><?= __($title .': ', 'wpvr'); ?></label>
            <textarea name="<?= $name;?>"></textarea>
        </div>

        <?php
        ob_end_flush();
    }


    /**
     * Render Hotspot Info Type Textarea Field
     * @param mixed $name input field name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_hotspot_info_textarea_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>

        <div class="<?= $class;?>" style="display:<?= $display;?>;">
            <label for="hotspot-content"><?= __($title .': ', 'wpvr'); ?></label>
            <textarea name="<?= $name;?>"><?= $value;?></textarea>
        </div>

        <?php
        ob_end_flush();
    }


    /**
     * Render Hotspot Scene Type Textarea Field
     * @param mixed $name input field name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_hotspot_scene_content_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>

        <div class="<?= $class;?>" style="display:<?= $display;?>;">
            <label for="hotspot-content"><?= __($title .': ', 'wpvr'); ?></label>
            <textarea name="<?= $name;?>"></textarea>
        </div>

        <?php
        ob_end_flush();
    }


    /**
     * Render Hotspot Scene Select Field
     * @param mixed $name input field name
     * @param mixed $val options
     * 
     * @return void
     */
    public static function render_hotspot_scene_select_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>

        <div class="hotspot-scene" style="display:none;" >
            <label for="hotspot-scene"><?= __($title .': ', 'wpvr'); ?></label>
            <select class="hotspotscene" name="<?= $name;?>">
                <option value="none" selected> None</option>
            </select>
        </div>

        <?php
        ob_end_flush();
    }


    /**
     * Render Hotspot Scene List Select Field
     * @param mixed $name input field name
     * @param mixed $val options
     * 
     * @return void
     */
    public static function render_hotspot_scene_list_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>

        <div class="hotspot-scene" style="display:<?= $display;?>;" >
            <label for="hotspot-scene"><?= __($title .': ', 'wpvr'); ?></label>
            <select class="hotspotscene" name="<?= $name;?>">
                <option value="none" selected> None</option>
            </select>
        </div>

        <?php
        ob_end_flush();
    }


    /**
     * Render Hotspot Disabled Select Field
     * @param mixed $name input field name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_hotspot_disabled_text_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>

        <div class="hotspot-scene" style="display:<?= $display;?>;" >
            <label for="hotspot-scene"><?= __($title .': ', 'wpvr'); ?></label>
            <input class="<?= $input_class;?>" type="text" value="<?= $value;?>" name="<?= $name;?>" disabled/>
        </div>

        <?php
        ob_end_flush();
    }


    /**
     * Render Hotspot Custom Icon Field
     * @param mixed $name input field name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_hotspot_custom_icon_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>

        <div class="hotspot-setting custom-icon">
            <label for="hotspot-customclass-pro"><?= __($title .': ', 'wpvr'); ?></label>
            <select class="hotspot-customclass-pro-select" name="<?= $name;?>">
                <?php  
                foreach ($custom_icons as $cikey => $civalue) {
                    if ($cikey == $hotspot_custom_class_pro) { ?>
                        <option value="<?= $cikey ?>" selected> <?= $civalue ?></option>
                    <?php } else { ?>
                        <option value="<?= $cikey ?>"> <?= $civalue ?></option>
                    <?php }
                }
                ?>
            </select>
            <span class="change-icon"><i class="<?= $hotspot_custom_class_pro ?>"></i></span>
            <div class="field-tooltip">
                <img src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/question.png' ?>" alt="icon" />
                <span><?= __('Custom icons will only show on frontend. Hotspot custom icons only works with fontawesome 5. If you are using any different version of fontawesome under theme or any plugin, you may deactivate fontawesome from WP VR. Go to "Get Started menu" and select "Role" and check fontawesome disable switch. Now put your desired any icon class under "Hotspot custom icon class" field. It will appear on the frontend.', 'wpvr') ?></span>
            </div>
        </div>

        <?php
        ob_end_flush();
    }


    /**
     * Render Hotspot Custom Icon Color Field
     * @param mixed $name input field name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_hotspot_custom_icon_color_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>

        <div class="hotspot-setting hotspot-icon">
            <label for="hotspot-customclass-color"><?= __($title .': ', 'wpvr'); ?></label>
            <input type="color" class="hotspot-customclass-color" name="hotspot-customclass-color" value="<?= $value;?>" />
            <input type="hidden" class="hotspot-customclass-color-icon-value" name="<?= $name;?>" value="<?= $value;?>" />
        </div>

        <?php
        ob_end_flush();
    }


    /**
     * Render Hotspot Animation Field
     * @param mixed $name input field name
     * @param mixed $val options
     * 
     * @return void
     * @since 8.0.0
     */
    public static function render_hotspot_animation_field($name, $val)
    {
        extract($val);
        ob_start();
        ?>

        <div class="hotspot-setting">
            <label for="hotspot-blink"><?= __($title .': ', 'wpvr'); ?></label>
            <select name="<?= $name;?>" class="hotspot-blink" >
                <option value="on" <?php if($selected == 'on') { echo 'selected'; } ?> > On</option>
                <option value="off" <?php if($selected == 'off') { echo 'selected'; } ?> > Off</option>
            </select>
        </div>

        <?php
        ob_end_flush();
    }

    /**
     * Render Fluent form Field
     * @param mixed $name input field name
     * @param mixed $val options
     *
     * @return void
     * @since 8.0.0
     */
    public  static function render_hotspot_fluent_form_type_field($name , $val){
        extract($val);
        $default_type = apply_filters('wpvr_hotspot_types', array(
            'info' => __('Info', 'wpvr-pro'),
            'scene' => __('Scene', 'wpvr-pro'),
        ));
        ob_start();
        ?>

        <label for="hotspot-type"><?= __($title .': ', 'wpvr'); ?></label>
        <select name="<?= $name;?>">
            <?php
            $hotspot_type = 'fluent_form';
            foreach ($default_type as $key => $value) {
                echo sprintf("<option %s value='%s'>%s</option>\n", selected($key, $hotspot_type, false), esc_attr($key), esc_attr($value));
            } ?>
        </select>

        <?php
        ob_end_flush();
    }

    /**
     * Render Hotspot Fluent form id
     * @param mixed $name input field name
     * @param mixed $val options
     *
     * @return void
     * @since 8.0.0
     */
    public static function render_hotspot_fluent_form_select_field($name , $val){
        extract($val);
        do_action("hotspot_info_before_hover_content","fluent_form",$value);
    }
    /**
     * Render woocommerce Field
     * @param mixed $name input field name
     * @param mixed $val options
     *
     * @return void
     * @since 8.0.0
     */
    public  static function render_hotspot_wc_product_type_field($name , $val){
        extract($val);
        $default_type = apply_filters('wpvr_hotspot_types', array(
            'info' => __('Info', 'wpvr-pro'),
            'scene' => __('Scene', 'wpvr-pro'),
        ));
        ob_start();
        ?>

        <label for="hotspot-type"><?= __($title .': ', 'wpvr'); ?></label>
        <select name="<?= $name;?>">
            <?php
            $hotspot_type = 'wc_product';
            foreach ($default_type as $key => $value) {
                echo sprintf("<option %s value='%s'>%s</option>\n", selected($key, $hotspot_type, false), esc_attr($key), esc_attr($value));
            } ?>
        </select>

        <?php
        ob_end_flush();
    }

    /**
     * Render Hotspot Woocommerce Product
     * @param mixed $name input field name
     * @param mixed $val options
     *
     * @return void
     * @since 8.0.0
     */
    public static function render_hotspot_wc_product_select_field($name , $val){
        extract($val);
        do_action("hotspot_info_before_hover_content","wc_product",$value);
    }

    public static function render_call_to_form_checkbox($name, $val)
    {
        extract( $val );
        ob_start();
        ?>
        <div class="<?= $class; ?>">
            <span><?= __($title.': ', 'wpvr'); ?></span>

            <span class="wpvr-switcher">
                <input id="<?= $id;?>" class="vr-switcher-check" name="<?= $name; ?>" type="checkbox" value="<?= $val['checked']; ?>" <?php echo $val['checked']=='on'? 'checked' : '' ?> />
                <label for="<?= $id;?>"></label>
            </span>

            <?php if($have_tooltip) {?>
                <div class="field-tooltip">
                    <img src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/question.png' ?>" alt="icon" />
                    <span><?= __($tooltip_text, 'wpvr'); ?></span>
                </div>
            <?php } ?>
        </div>
        <?php
        ob_end_flush();
    }

    public static function render_text_area_field($name, $val)
    {
        extract( $val );
        ob_start();
        ?>
        <div class="<?= $class; ?>">
            <div id="<?= $code_mirror_id ?>" ></div>
<!--            --><?php //if($have_tooltip) {?>
<!--                <div class="field-tooltip">-->
<!--                    <img src="--><?php //= WPVR_PLUGIN_DIR_URL . 'admin/icon/question.png'; ?><!--" alt="icon" />-->
<!--                    <span>--><?php //= __($tooltip_text, 'wpvr'); ?><!--</span>-->
<!--                </div>-->
<!--            --><?php //} ?>
        </div>
        <style>
            /* Adjust the styling of line numbers */
            .CodeMirror-linenumber {
                padding: 0 5px; /* Adjust padding as needed */
                color: #999;   /* Adjust color as needed */
            }

        </style>
        <?php
        ob_end_flush();
    }


    public static function render_custom_css_form_checkbox($name, $val)
    {
        extract( $val );
        ob_start();
        ?>
        <div class="<?= $class; ?>">
            <span><?= __($title.': ', 'wpvr'); ?></span>

            <span class="wpvr-switcher">
                <input id="<?= $id;?>" class="vr-switcher-check" name="<?= $name; ?>" type="checkbox" value="<?= $val['checked']; ?>" <?php echo $val['checked']=='on'? 'checked' : '' ?> />
                <label for="<?= $id;?>"></label>
            </span>

            <?php if($have_tooltip) {?>
                <div class="field-tooltip">
                    <img src="<?= WPVR_PLUGIN_DIR_URL . 'admin/icon/question.png' ?>" alt="icon" />
                    <span><?= __($tooltip_text, 'wpvr'); ?></span>
                </div>
            <?php } ?>
        </div>
        <?php
        ob_end_flush();
    }

    public static function render_button_configuration_field($name, $val)
    {
        extract( $val );
        ob_start();
        $button_style =$val['value'];
        $selectedBgColor = isset($button_style['button_background_color']) ? $button_style['button_background_color'] : '#201cfe';
        $selectedColor = isset($button_style['button_font_color']) ? $button_style['button_font_color'] : '#ffffff';
        $selectedFontSize = isset($button_style['button_font_size']) ? $button_style['button_font_size'] : 14;
        $selectedFontWeight = isset($button_style['button_font_weight']) ? $button_style['button_font_weight'] : 400;
        $selectedLineHeight = isset($button_style['button_line_height']) ? $button_style['button_line_height'] : 1;
        $selectedTextDecoration = isset($button_style['button_text_decoration']) ? $button_style['button_text_decoration'] : 'none';
        $selectedTransform = isset($button_style['button_transform']) ? $button_style['button_transform'] : 'none';
        $selectedAlignment = isset($button_style['button_alignment']) ? $button_style['button_alignment'] : 'left';
        $selectedFontStyle = isset($button_style['button_text_style']) ? $button_style['button_text_style'] : 'normal';
        $selectedLetterSpacing = isset($button_style['button_letter_spacing']) ? $button_style['button_letter_spacing'] : 1;
        $selectedWordSpacing = isset($button_style['button_word_spacing']) ? $button_style['button_word_spacing'] : 0;
        $selectedBorderWidth = isset($button_style['button_border_width']) ? $button_style['button_border_width'] : 1;
        $selectedBorderStyle = isset($button_style['button_border_style']) ? $button_style['button_border_style'] : 'solid';
        $selectedBorderColor = isset($button_style['button_border_style']) ? $button_style['button_border_color'] : '#201cfe';
        $selectedBorderRadius = isset($button_style['button_border_radius']) ? $button_style['button_border_radius'] : 6;
        
        $selectedPaddingTop = isset($button_style['button_pt']) ? $button_style['button_pt'] : 10;
        $selectedPaddingRight = isset($button_style['button_pr']) ? $button_style['button_pr'] : 15;
        $selectedPaddingBottom = isset($button_style['button_pb']) ? $button_style['button_pb'] : 10;
        $selectedPaddingLeft = isset($button_style['button_pl']) ? $button_style['button_pl'] : 15;

        $selectedNewTab = isset($button_style['button_open_new_tab']) ? $button_style['button_open_new_tab'] : 'off';
        ?>
        <div class="<?= $class; ?>">

            <span>Button Style</span>
            <div class="button-style-configaration">
                <div class="single-cta-control new-tab">
                    <span class="wpvr-switcher">
                        <span class="control-title"><?php echo __('Open in new tab','wpvr') ?></span>
                        <input id="button_open_new_tab" class="vr-switcher-check" name="button_open_new_tab" type="checkbox" value="<?php echo $selectedNewTab ; ?>" <?php echo  $selectedNewTab == 'on' ? 'checked' : '' ?> />
                        <label for="button_open_new_tab"></label>
                    </span>
                </div>
                
                <div class="single-cta-control bg-color color-box">
                    <label class="control-title"><?php echo __('Background Color','wpvr') ?></label>
                    <input type="color" name="button_background_color" value="<?php echo $selectedBgColor; ?>">
                </div>
                
                <div class="single-cta-control font-color color-box">
                    <label class="control-title"><?php echo __('Font color','wpvr') ?></label>
                    <input type="color" name="button_font_color" value="<?php echo $selectedColor; ?>">
                </div>

                <div class="single-cta-control font-size">
                    <label class="control-title"><?php echo __('Font Size (px)','wpvr') ?></label>
                    <input type="number" name="button_font_size" value="<?php echo $selectedFontSize; ?>" min="0">
                </div>

                <div class="single-cta-control font-weight">
                    <label class="control-title"><?php echo __('Font Weight','wpvr') ?></label>
                    <select name="button_font_weight" id="button_font_weight">
                        <option value="400" <?php echo ($selectedFontWeight == '400') ? 'selected' : ''; ?>>400</option>
                        <option value="500" <?php echo ($selectedFontWeight == '500') ? 'selected' : ''; ?>>500</option>
                        <option value="600" <?php echo ($selectedFontWeight == '600') ? 'selected' : ''; ?>>600</option>
                        <option value="700" <?php echo ($selectedFontWeight == '700') ? 'selected' : ''; ?>>700</option>
                        <option value="800" <?php echo ($selectedFontWeight == '800') ? 'selected' : ''; ?>>800</option>
                        <option value="900" <?php echo ($selectedFontWeight == '900') ? 'selected' : ''; ?>>900</option>
                    </select>
                </div>

                <div class="single-cta-control line-height">
                    <label class="control-title"><?php echo __('Line Height','wpvr') ?></label>
                    <input type="number" name="button_line_height" value="<?php echo $selectedLineHeight; ?>" min="0">
                </div>

                <div class="single-cta-control text-decoration">
                    <label class="control-title"><?php echo __('Text Decoration','wpvr') ?></label>
                    <select name="button_text_decoration" id="button_text_decoration">
                        <option value="none" <?php echo ($selectedTextDecoration == 'none') ? 'selected' : ''; ?>><?php echo __('None','wpvr') ?></option>
                        <option value="underline" <?php echo ($selectedTextDecoration == 'underline') ? 'selected' : ''; ?>> <?php echo __('Underline','wpvr') ?></option>
                        <option value="overline" <?php echo ($selectedTextDecoration == 'overline') ? 'selected' : ''; ?>><?php echo __('Overline','wpvr') ?></option>
                        <option value="line-through" <?php echo ($selectedTextDecoration == 'line-through') ? 'selected' : ''; ?>><?php echo __('Line Through','wpvr') ?></option>
                    </select>
                </div>

                <div class="single-cta-control text-transform">
                    <label class="control-title"><?php echo __('Text Transform','wpvr') ?></label>
                    <select name="button_transform" id="button_transform">
                        <option value="none" <?php echo ($selectedTransform == 'none') ? 'selected' : ''; ?>><?php echo __('None','wpvr') ?></option>
                        <option value="uppercase" <?php echo ($selectedTransform == 'uppercase') ? 'selected' : ''; ?>><?php echo __('Uppercase','wpvr') ?></option>
                        <option value="lowercase" <?php echo ($selectedTransform == 'lowercase') ? 'selected' : ''; ?>><?php echo __('Lowercase','wpvr') ?></option>
                        <option value="capitalize" <?php echo ($selectedTransform == 'capitalize') ? 'selected' : ''; ?>><?php echo __('Capitalize','wpvr') ?></option>
                    </select>
                </div>

                <div class="single-cta-control text-align">
                    <label class="control-title"> <?php echo __('Button Alignment','wpvr') ?></label>
                    <select name="button_alignment" id="button_alignment">
                        <option value="left" <?php echo ($selectedAlignment == 'left') ? 'selected' : ''; ?>> <?php echo __('Left','wpvr') ?></option>
                        <option value="right" <?php echo ($selectedAlignment == 'right') ? 'selected' : ''; ?>> <?php echo __('Right','wpvr') ?></option>
                        <option value="center" <?php echo ($selectedAlignment == 'center') ? 'selected' : ''; ?>> <?php echo __('Center','wpvr') ?></option>
                        <option value="justified" <?php echo ($selectedAlignment == 'justified') ? 'selected' : ''; ?>> <?php echo __('Justified','wpvr') ?></option>
                    </select>
                </div>

                <div class="single-cta-control font-style">
                    <label class="control-title"> <?php echo __('Font Style','wpvr') ?></label>
                    <select name="button_text_style" id="button_text_style">
                        <option value="normal" <?php echo ($selectedFontStyle == 'normal') ? 'selected' : ''; ?>> <?php echo __('Normal','wpvr') ?></option>
                        <option value="italic" <?php echo ($selectedFontStyle == 'italic') ? 'selected' : ''; ?>> <?php echo __('Italic','wpvr') ?></option>
                        <option value="oblique" <?php echo ($selectedFontStyle == 'oblique') ? 'selected' : ''; ?>> <?php echo __('Oblique','wpvr') ?></option>
                    </select>
                </div>

                <div class="single-cta-control letter-spacing">
                    <label class="control-title"> <?php echo __('Letter Spacing (px)','wpvr') ?></label>
                    <input type="number" name="button_letter_spacing" value="<?php echo $selectedLetterSpacing; ?>" min="0">
                </div>
                
                <div class="single-cta-control word-spacing">
                    <label class="control-title"> <?php echo __('Word Spacing (px)','wpvr') ?></label>
                    <input type="number" name="button_word_spacing" value="<?php echo $selectedWordSpacing; ?>" min="0">
                </div>

                <div class="single-cta-control border-radius">
                    <label class="control-title"> <?php echo __('Border Radius (px)','wpvr') ?></label>
                    <input type="number" name="button_border_radius" value="<?php echo $selectedBorderRadius; ?>" min="0">
                </div>

                <div class="single-cta-control control-group border">
                    <label class="control-title"> <?php echo __('Border','wpvr') ?></label>
                    
                    <div class="border-property border-width">
                        <label class="control-inner-title"> <?php echo __('Width (px)','wpvr') ?></label>
                        <input type="number" name="button_border_width" value="<?php echo $selectedBorderWidth; ?>" min="0">
                    </div>
                    
                    <div class="border-property border-style">
                        <label class="control-inner-title"> <?php echo __('Style','wpvr') ?></label>
                        <select name="button_border_style" id="button_border_style">
                            <option value="solid" <?php echo ($selectedBorderStyle == 'solid') ? 'selected' : ''; ?>> <?php echo __('','wpvr') ?>Solid</option>
                            <option value="dashed" <?php echo ($selectedBorderStyle == 'dashed') ? 'selected' : ''; ?>> <?php echo __('','wpvr') ?>Dashed</option>
                            <option value="dotted" <?php echo ($selectedBorderStyle == 'dotted') ? 'selected' : ''; ?>> <?php echo __('','wpvr') ?>Dotted</option>
                            <option value="double" <?php echo ($selectedBorderStyle == 'double') ? 'selected' : ''; ?>> <?php echo __('','wpvr') ?>Double</option>
                            <option value="none" <?php echo ($selectedBorderStyle == 'none') ? 'selected' : ''; ?>> <?php echo __('','wpvr') ?>None</option>
                        </select>
                    </div>
                    
                    <div class="border-property border-color color-box">
                        <label class="control-inner-title"> <?php echo __('Color','wpvr') ?></label>
                        <input type="color" name="button_border_color" value="<?php echo $selectedBorderColor; ?>">
                    </div>
                </div>

                <div class="single-cta-control control-group padding">
                    <label class="control-title"> <?php echo __('Padding (px)','wpvr') ?></label>
                    
                    <div class="padding-property padding-top">
                        <label class="control-inner-title"> <?php echo __('Top','wpvr') ?></label>
                        <input type="number" name="button_pt" value="<?php echo $selectedPaddingTop; ?>" min="0">
                    </div>
                    
                    <div class="padding-property padding-right">
                        <label class="control-inner-title"> <?php echo __('Right','wpvr') ?></label>
                        <input type="number" name="button_pr" value="<?php echo $selectedPaddingRight; ?>">
                    </div>
                    
                    <div class="padding-property padding-bottom">
                        <label class="control-inner-title"> <?php echo __('Bottom','wpvr') ?></label>
                        <input type="number" name="button_pb" value="<?php echo $selectedPaddingBottom; ?>">
                    </div>
                    
                    <div class="padding-property padding-left">
                        <label class="control-inner-title"> <?php echo __('Left','wpvr') ?></label>
                        <input type="number" name="button_pl" value="<?php echo $selectedPaddingLeft; ?>">
                    </div>
                </div>
                
            </div>
        </div>
        <?php
        ob_end_flush();
    }
    

}