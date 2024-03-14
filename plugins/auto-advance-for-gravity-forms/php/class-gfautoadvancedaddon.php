<?php

GFForms::include_addon_framework();
class GFAutoAdvancedAddOn extends GFAddOn
{
    protected  $_version = AUTO_ADVANCED_ZZD ;
    protected  $_min_gravityforms_version = '1.9' ;
    protected  $_slug = 'gfaa' ;
    protected  $_path = 'auto-advance-for-gravity-forms/auto-advance-for-gravity-forms.php' ;
    protected  $_full_path = __FILE__ ;
    protected  $_title = 'Gravity Forms Auto Advanced Add-On' ;
    protected  $_short_title = 'Auto Advance' ;
    private static  $_instance = null ;
    /**
     * Get an instance of this class.
     *
     * @return GFAutoAdvancedAddOn
     */
    public static function get_instance()
    {
        if ( self::$_instance == null ) {
            self::$_instance = new GFAutoAdvancedAddOn();
        }
        return self::$_instance;
    }
    
    /**
     * Handles hooks and loading of language files.
     */
    public function init()
    {
        parent::init();
        // add_action( 'gform_field_advanced_settings', array( $this, 'auto_advanced_field_settings' ), 10, 2 );
        // add_action("gform_editor_js", array($this, "editor_script_main"), 10);
        // add_action("gform_editor_js", array($this, "editor_script"), 12);
        add_filter(
            "gform_tooltips",
            array( $this, "gform_tooltips" ),
            12,
            1
        );
        add_filter( 'gform_pre_render', array( $this, "addon_pre_render" ), 9999 );
        add_filter( 'admin_enqueue_scripts', array( $this, 'backend_scripts' ), 1 );
        add_filter( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ), 10 );
        //ANIMATION
        add_filter(
            'gform_form_post_get_meta',
            array( $this, 'modify_gform_field_data' ),
            500,
            1
        );
        add_filter(
            'gform_form_settings_menu',
            array( $this, 'gform_form_settings_menu' ),
            100,
            2
        );
        // CONVERSATIONAL
        add_filter(
            'gform_field_settings_tabs',
            array( $this, 'add_gfaa_settings_tab' ),
            10,
            2
        );
        add_filter(
            'gform_field_settings_tab_content',
            array( $this, 'gfaa_settings_tab_content' ),
            10,
            2
        );
        if ( !is_admin() ) {
            // var_dump ( aafgf_fs()->is__premium_only() );
            // var_dump ( aafgf_fs()->is_plan( 'autoadvanceforgravityformsplus', true ) );
        }
    }
    
    public function gform_tooltips()
    {
        $tooltips['gfa_side_image'] = __( 'Display side image while using conversational form', 'gf-autoadvanced' );
        $tooltips['gfa_autoadvanced'] = __( 'Auto Submit or Send to Next page when this input is changed.', 'gf-autoadvanced' );
        $tooltips['gfa_autoadvanced_number'] = __( 'Number of key inputs to auto advance', 'gf-autoadvanced' );
        $tooltips['gfa_autoadvanced_next'] = __( 'Hide Next Button of the page when this field is on the page', 'gf-autoadvanced' );
        $tooltips['gfa_autoadvanced_previous'] = __( 'Hide Previous Button of the page when this field is on the page', 'gf-autoadvanced' );
        return $tooltips;
    }
    
    public function addon_pre_render( $form )
    {
        
        if ( isset( $form['is_conversational_form'] ) && $form['is_conversational_form'] == 1 ) {
            $proceed = false;
            if ( !$proceed ) {
                return $form;
            }
        }
        
        $supported_fields = [
            'radio',
            'select',
            'quiz',
            'poll',
            'survey'
        ];
        $fields = $form['fields'];
        foreach ( $fields as $field ) {
            if ( in_array( $field->type, $supported_fields ) ) {
                if ( isset( $field->autoAdvancedField ) && $field->autoAdvancedField != "" && $field->autoAdvancedField != 0 ) {
                    $field->cssClass .= " trigger-next-zzd";
                }
            }
            
            if ( isset( $field->hideNextButton ) && $field->hideNextButton != "" && $field->hideNextButton != 0 ) {
                $field->cssClass .= " hide-next-button";
                $field->cssClass .= " hide-submit-button";
            }
            
            if ( isset( $field->hidePreviousButton ) && $field->hidePreviousButton != "" && $field->hidePreviousButton != 0 ) {
                $field->cssClass .= " hide-previous-button";
            }
            $input_type = $field->get_input_type();
            
            if ( is_array( $field->choices ) && $input_type != 'list' ) {
                $field_val = RGFormsModel::get_parameter_value( $field->inputName, [], $field );
                $field_val = $field->get_value_default_if_empty( $field_val );
                $choice_index = ( $input_type == 'radio' ? 0 : 1 );
                foreach ( $field->choices as $choice ) {
                    if ( $input_type == 'checkbox' && $choice_index % 10 == 0 ) {
                        $choice_index++;
                    }
                    $is_prepopulated = ( is_array( $field_val ) ? in_array( $choice['value'], $field_val ) : $choice['value'] == $field_val );
                    $is_choice_selected = rgar( $choice, 'isSelected' ) || $is_prepopulated;
                    if ( $is_prepopulated ) {
                        break;
                    }
                }
                $field->cssClass .= ( $is_prepopulated ? " has-input-name populated " : " has-input-name" );
            }
            
            // echo "<pre>"; print_r($field); echo "</pre>";
        }
        if ( !is_admin() && !wp_doing_ajax() ) {
            if ( isset( $form['gfaa'] ) && isset( $form['gfaa']['enable_step_colors'] ) && $form['gfaa']['enable_step_colors'] == 1 ) {
                
                if ( isset( $form['gfaa']['gfaa_type'] ) || $form['gfaa']['gfaa_type'] == 'animationed' ) {
                    extract( $form['gfaa'] );
                    $form_id = '#gform_' . $form['id'];
                    echo  '<style>' ;
                    include ZZD_AAGF_DIR . 'css/dynamic-css.php';
                    echo  '</style>' ;
                }
            
            }
        }
        return $form;
    }
    
    public function get_menu_icon()
    {
        return 'gform-icon--format-quote1';
        return 'gform-icon--smart-button';
        return 'gform-icon--embed';
        return 'gform-icon--style';
    }
    
    public function gform_form_settings_menu( $setting_tabs, $form_id )
    {
        $new_tabs = array();
        foreach ( $setting_tabs as $priority => $tab ) {
            if ( $tab['name'] == 'gfaa' ) {
                $priority = 12;
            }
            $new_tabs[$priority] = $tab;
        }
        return $new_tabs;
    }
    
    public static function localize_scripts( $form, $is_ajax, $handler = 'gfaa-admin' )
    {
        $vars = array();
        $vars['ajaxurl'] = admin_url( 'admin-ajax.php' );
        $vars['inputNumberKeys_selection_string'] = __( "Number of Selections to Auto Advanced", "gf-autoadvanced" );
        $vars['inputNumberKeys_inputs_string'] = __( "Number of Input Characters to Auto Advanced", "gf-autoadvanced" );
        wp_localize_script( $handler, 'aafg', $vars );
    }
    
    public function scripts()
    {
        $scripts = array( array(
            'handle'   => 'gfaa-admin',
            'src'      => ZZD_AAGF_URL . 'js/gfaa-admin.js',
            'version'  => AUTO_ADVANCED_ASSETS,
            'deps'     => array( 'jquery', 'wp-color-picker' ),
            'enqueue'  => array( array(
            'tab' => array( 'form_editor', 'gfaa' ),
        ) ),
            'callback' => array( 'GFAutoAdvancedAddOn', 'localize_scripts' ),
        ) );
        $scripts = apply_filters( "gfaa_scripts", $scripts );
        return array_merge( parent::scripts(), $scripts );
    }
    
    public function styles()
    {
        $styles = array( array(
            'handle'  => 'gfaa-admin',
            'src'     => ZZD_AAGF_URL . 'css/gfaa-admin.css',
            'version' => AUTO_ADVANCED_ASSETS,
            'deps'    => array( 'wp-color-picker' ),
            'enqueue' => array( array(
            'tab' => array( 'form_editor', 'gfaa' ),
        ) ),
        ) );
        $styles = apply_filters( "gfaa_styles", $styles );
        return array_merge( parent::styles(), $styles );
    }
    
    public function frontend_scripts()
    {
        wp_enqueue_style(
            'gfaa-animate',
            ZZD_AAGF_URL . 'css/animate.min.css',
            array(),
            AUTO_ADVANCED_ASSETS
        );
        wp_enqueue_style(
            'gfaa-conversational',
            ZZD_AAGF_URL . 'css/conversational.css',
            array(),
            AUTO_ADVANCED_ASSETS
        );
        wp_enqueue_script(
            "gfaa-basic",
            ZZD_AAGF_URL . "js/aafg_script.js",
            array( 'jquery' ),
            AUTO_ADVANCED_ASSETS,
            true
        );
        wp_enqueue_style(
            "gfaa-main",
            ZZD_AAGF_URL . "css/aafg_styles.css",
            array(),
            AUTO_ADVANCED_ASSETS
        );
    }
    
    public function backend_scripts()
    {
        wp_enqueue_media();
    }
    
    public function modify_gform_field_data( $form )
    {
        if ( !isset( $form['gfaa'] ) || !isset( $form['gfaa']['enable_animation'] ) || !$form['gfaa']['enable_animation'] ) {
            return $form;
        }
        if ( !isset( $form['gfaa'] ) || !isset( $form['gfaa']['gfaa_type'] ) || $form['gfaa']['gfaa_type'] == 'basic' ) {
            return $form;
        }
        if ( is_admin() ) {
            return $form;
        }
        if ( !isset( $form['cssClass'] ) || !$form['cssClass'] ) {
            $form['cssClass'] = '';
        }
        $form['cssClass'] .= ' has_animation ';
        
        if ( isset( $form['gfaa']['animation'] ) ) {
            $form['cssClass'] .= $form['gfaa']['animation'];
        } else {
            $form['cssClass'] .= 'fade';
        }
        
        return $form;
    }
    
    public function add_pagination_to_the_form( $form )
    {
        if ( !isset( $form['gfaa'] ) || !isset( $form['gfaa']['enable_conversational'] ) || !$form['gfaa']['enable_conversational'] ) {
            return $form;
        }
        if ( !isset( $form['gfaa'] ) || !isset( $form['gfaa']['gfaa_type'] ) || $form['gfaa']['gfaa_type'] != 'conversational' ) {
            return $form;
        }
        if ( is_admin() ) {
            return $form;
        }
        if ( !isset( $form['gfaa']['page'] ) || $form['gfaa']['page'] != get_the_ID() ) {
            return $form;
        }
        $custom_form = $form;
        $fields_without_page_break = array();
        foreach ( $custom_form['fields'] as $field ) {
            if ( true || $field->type !== 'page' ) {
                $fields_without_page_break[] = $field;
            }
        }
        $page_number = 1;
        $form_fields_with_page_break = array();
        $next_button = array(
            'type'             => 'text',
            'text'             => apply_filters( 'gfa_next_button_text', esc_html__( 'Next', 'gf-autoadvanced' ) ),
            'imageUrl'         => '',
            'conditionalLogic' => array(),
        );
        $previous_button = array(
            'type'             => 'text',
            'text'             => apply_filters( 'gfa_previous_button_text', esc_html__( 'Previous', 'gf-autoadvanced' ) ),
            'imageUrl'         => '',
            'conditionalLogic' => array(),
        );
        $temporary_page_object = new GF_Field_Page();
        $temporary_page_object->nextButton = $next_button;
        $temporary_page_object->previousButton = $previous_button;
        foreach ( $fields_without_page_break as $fieldIndex => $field ) {
            if ( $field->type == 'page' ) {
                continue;
            }
            $conditionalLogic = '';
            
            if ( count( $form_fields_with_page_break ) > 0 ) {
                $last_field = $form_fields_with_page_break[count( $form_fields_with_page_break ) - 1];
                
                if ( $last_field->type === 'page' && !empty($field->conditionalLogic) ) {
                    $conditionalLogic = $field->conditionalLogic;
                    $field->conditionalLogic = '';
                    $last_field->conditionalLogic = $conditionalLogic;
                }
            
            }
            
            $field->pageNumber = $page_number;
            $form_fields_with_page_break[] = $field;
            $field->cssClass .= ' conv_enabled ';
            if ( empty($field->description) ) {
                $field->cssClass .= ' gfa_no_description ';
            }
            if ( empty($field->placeholder) ) {
                $field->placeholder = $field->label;
            }
            if ( $fieldIndex === count( $fields_without_page_break ) - 1 ) {
                break;
            }
            // Check if next field is page.
            $next_field = ( isset( $fields_without_page_break[1 + $fieldIndex] ) ? $fields_without_page_break[1 + $fieldIndex] : false );
            
            if ( $next_field && $next_field->type == 'page' ) {
                $new_custom_next_field = clone $next_field;
                $new_custom_next_field->pageNumber = $page_number;
                $form_fields_with_page_break[] = $new_custom_next_field;
            } else {
                if ( $field->type === 'hidden' || $field->visibility === 'hidden' || $field->visibility === 'administrative' || $field->inputType === 'hidden' ) {
                    continue;
                }
                $temporary_page_object->pageNumber = $page_number;
                $form_fields_with_page_break[] = $temporary_page_object;
            }
            
            $page_number++;
        }
        // echo "<pre>"; print_r($fields_without_page_break); echo "</pre>";
        // echo "<pre>"; print_r($form_fields_with_page_break); echo "</pre>";
        // exit;
        $custom_form['fields'] = $form_fields_with_page_break;
        $custom_form = GFFormsModel::convert_field_objects( $custom_form );
        $next_field_id = GFFormsModel::get_next_field_id( $custom_form['fields'] );
        $custom_form['fields'] = $this->add_id_to_pages( $custom_form['fields'], $next_field_id );
        $custom_form['pagination']['type'] = 'percentage';
        $custom_form['pagination']['style'] = 'blue';
        $custom_form['lastPageButton'] = array(
            'type' => 'text',
            'text' => apply_filters( 'gfa_previous_button_text', esc_html__( 'prev', 'gf-autoadvanced' ) ),
        );
        return $custom_form;
    }
    
    public function add_id_to_pages( $fields, $id )
    {
        foreach ( $fields as &$field ) {
            if ( empty($field->id) ) {
                $field->id = $id++;
            }
            if ( is_array( $field->fields ) ) {
                $field->fields = $this->add_id_to_pages( $field->fields, $id );
            }
        }
        return $fields;
    }
    
    public function form_settings_fields( $form )
    {
        $pages = new WP_Query( array(
            'post_type'      => 'page',
            'posts_per_page' => -1,
        ) );
        $choices = array();
        $choices[] = array(
            'label' => 'Select a Page',
            'value' => '',
        );
        if ( $pages->posts ) {
            foreach ( $pages->posts as $post ) {
                $choices[] = array(
                    'label' => $post->post_title,
                    'value' => $post->ID,
                );
            }
        }
        if ( true ) {
            // Animation Settings
            $main_settings = array(
                'title'  => esc_html__( 'Auto Advanced Base', 'gf-autoadvanced' ),
                'fields' => array( array(
                'label'   => esc_html__( 'Auto Advanced Type', 'gf-autoadvanced' ),
                'type'    => 'select',
                'name'    => 'gfaa_type',
                'tooltip' => esc_html__( 'Select what type of auto advanced you want to set.', 'gf-autoadvanced' ),
                'choices' => array( array(
                'label' => esc_html__( 'Basic Auto Advance', 'gf-autoadvanced' ),
                'value' => 'basic',
            ), array(
                'label' => esc_html__( 'Auto Advance with Animations', 'gf-autoadvanced' ),
                'value' => 'animationed',
            ), array(
                'label' => esc_html__( 'Conversational Auto Advance', 'gf-autoadvanced' ),
                'value' => 'conversational',
            ) ),
            ) ),
            );
        }
        if ( true ) {
            // Animation Settings
            $animation_settings = array(
                'title'  => esc_html__( 'Animation Settings', 'gf-autoadvanced' ),
                'fields' => array( array(
                'label'   => esc_html__( 'Enable Animation', 'gf-autoadvanced' ),
                'type'    => 'checkbox',
                'name'    => 'enable_animation',
                'tooltip' => esc_html__( 'Select how each question should transition into view.', 'gf-autoadvanced' ),
                'choices' => array( array(
                'label' => esc_html__( 'Enabled', 'gf-autoadvanced' ),
                'name'  => 'enable_animation',
            ) ),
            ), array(
                'label'   => esc_html__( 'Animation', 'gf-autoadvanced' ),
                'type'    => 'select',
                'name'    => 'animation',
                'tooltip' => esc_html__( 'Select how each question should transition into view.', 'gf-autoadvanced' ),
                'choices' => array( array(
                'label' => esc_html__( 'Fade', 'gf-autoadvanced' ),
                'value' => 'fade',
            ), array(
                'label' => esc_html__( 'Slide Left', 'gf-autoadvanced' ),
                'value' => 'slidehorizontal',
            ), array(
                'label' => esc_html__( 'Slide Up', 'gf-autoadvanced' ),
                'value' => 'slidevertical',
            ) ),
            ) ),
            );
        }
        if ( true ) {
            // Step Colors Settings
            $colors_settings = array(
                'title'  => esc_html__( 'Step Colors Settings', 'gf-autoadvanced' ),
                'fields' => array(
                array(
                'label'   => esc_html__( 'Enable Steps Colors (Pagination type must be set to steps)', 'gf-autoadvanced' ),
                'type'    => 'checkbox',
                'name'    => 'enable_step_colors',
                'tooltip' => esc_html__( 'Adjust step colors with ease', 'gf-autoadvanced' ),
                'choices' => array( array(
                'label' => esc_html__( 'Enabled', 'gf-autoadvanced' ),
                'name'  => 'enable_step_colors',
            ) ),
            ),
                array(
                'title'     => '<h3>' . __( 'Inactive Step Colors', 'gf-autoadvanced' ) . '</h3>',
                'type'      => 'color_group',
                'name'      => 'inactive-step-color-fields',
                'subfields' => array(
                array(
                'label'         => esc_html__( 'Background', 'gf-autoadvanced' ),
                'name'          => 'inactive_number_bg',
                'type'          => 'text',
                'default_value' => '#ffffff',
                'value'         => '',
            ),
                array(
                'label'         => esc_html__( 'Border', 'gf-autoadvanced' ),
                'name'          => 'inactive_border',
                'type'          => 'text',
                'default_value' => '#cfd3d9',
                'value'         => '',
            ),
                array(
                'label'         => esc_html__( 'Number Color', 'gf-autoadvanced' ),
                'name'          => 'inactive_number_color',
                'type'          => 'text',
                'default_value' => '#51585f',
                'value'         => '',
            ),
                array(
                'label'         => esc_html__( 'Text Color', 'gf-autoadvanced' ),
                'name'          => 'inactive_text_color',
                'type'          => 'text',
                'default_value' => '#51585f',
                'value'         => '',
            )
            ),
            ),
                array(
                'title'     => '<h3>' . __( 'Active Step Colors', 'gf-autoadvanced' ) . '</h3>',
                'type'      => 'color_group',
                'name'      => 'active-step-color-fields',
                'subfields' => array(
                array(
                'label'         => esc_html__( 'Background', 'gf-autoadvanced' ),
                'type'          => 'text',
                'value'         => '',
                'name'          => 'active_number_bg',
                'default_value' => '#cfd3d9',
            ),
                array(
                'label'         => esc_html__( 'Border', 'gf-autoadvanced' ),
                'type'          => 'text',
                'value'         => '',
                'name'          => 'active_border',
                'default_value' => '#cfd3d9',
            ),
                array(
                'label'         => esc_html__( 'Number Color', 'gf-autoadvanced' ),
                'type'          => 'text',
                'value'         => '',
                'name'          => 'active_number_color',
                'default_value' => '#607382',
            ),
                array(
                'label'         => esc_html__( 'Text Color', 'gf-autoadvanced' ),
                'type'          => 'text',
                'value'         => '',
                'name'          => 'active_text_color',
                'default_value' => '#51585f',
            )
            ),
            ),
                array(
                'title'     => '<h3>' . __( 'Completed Step Colors', 'gf-autoadvanced' ) . '</h3>',
                'type'      => 'color_group',
                'name'      => 'completed-step-color-fields',
                'subfields' => array(
                array(
                'label'         => esc_html__( 'Background', 'gf-autoadvanced' ),
                'type'          => 'text',
                'value'         => '',
                'name'          => 'completed_number_bg',
                'default_value' => '#607382',
            ),
                array(
                'label'         => esc_html__( 'Border', 'gf-autoadvanced' ),
                'type'          => 'text',
                'value'         => '',
                'name'          => 'completed_border',
                'default_value' => '#607382',
            ),
                array(
                'label'         => esc_html__( 'Number Color', 'gf-autoadvanced' ),
                'type'          => 'text',
                'value'         => '',
                'name'          => 'completed_number_color',
                'default_value' => '#ffffff',
            ),
                array(
                'label'         => esc_html__( 'Text Color', 'gf-autoadvanced' ),
                'type'          => 'text',
                'value'         => '',
                'name'          => 'completed_text_color',
                'default_value' => '#51585f',
            )
            ),
            )
            ),
            );
        }
        if ( true ) {
            // Conversational Form Settings
            $conversational_settings = array(
                'title'  => esc_html__( 'Conversational Form Settings', 'gf-autoadvanced' ),
                'fields' => array(
                array(
                'label'   => esc_html__( 'Enable Conversational Form', 'gf-autoadvanced' ),
                'type'    => 'checkbox',
                'class'   => 'zzdtest',
                'name'    => 'enable_conversational',
                'tooltip' => esc_html__( 'Increase your form completion rate by making it conversational', 'gf-autoadvanced' ),
                'choices' => array( array(
                'label' => esc_html__( 'Enabled', 'gf-autoadvanced' ),
                'name'  => 'enable_conversational',
            ) ),
            ),
                array(
                'label'   => esc_html__( 'Page', 'gf-autoadvanced' ),
                'type'    => 'select',
                'name'    => 'page',
                'tooltip' => esc_html__( 'Which page should show conversational form?', 'gf-autoadvanced' ),
                'choices' => $choices,
            ),
                array(
                'label'   => esc_html__( 'Form Layout', 'gf-autoadvanced' ),
                'type'    => 'select',
                'name'    => 'conversational-layout',
                'choices' => array(
                array(
                    'label' => esc_html__( 'No Image', 'gf-autoadvanced' ),
                    'value' => 'noimage',
                ),
                /*array(
                			'label' => esc_html__( 'Background Image', 'gf-autoadvanced' ),
                			'value' => 'bgimage',
                		),*/
                array(
                    'label' => esc_html__( 'Image Left', 'gf-autoadvanced' ),
                    'value' => 'leftimage',
                ),
                array(
                    'label' => esc_html__( 'Image Right', 'gf-autoadvanced' ),
                    'value' => 'rightimage',
                ),
            ),
            ),
                array(
                'label' => esc_html__( 'Default Cover Image', 'gf-autoadvanced' ),
                'type'  => 'gfaa_image',
                'name'  => 'cover-image',
            ),
                array(
                'label' => esc_html__( 'Default Side Image', 'gf-autoadvanced' ),
                'type'  => 'gfaa_image',
                'name'  => 'side-image',
            ),
                array(
                'label'   => esc_html__( 'Turn Off Intro Page', 'gf-autoadvanced' ),
                'type'    => 'checkbox',
                'class'   => '',
                'name'    => 'turn_off_intro',
                'tooltip' => esc_html__( 'Turn off Intro page and show form directly.', 'gf-autoadvanced' ),
                'choices' => array( array(
                'label' => esc_html__( 'Yes', 'gf-autoadvanced' ),
                'name'  => 'turn_off_intro',
            ) ),
            ),
                array(
                'label' => esc_html__( 'Intro Image (Optional)', 'gf-autoadvanced' ),
                'type'  => 'gfaa_image',
                'name'  => 'logo-image',
            ),
                array(
                'label'   => esc_html__( 'Intro Heading', 'gf-autoadvanced' ),
                'type'    => 'text',
                'name'    => 'intro_heading',
                'tooltip' => esc_html__( 'Intro Heading', 'gf-autoadvanced' ),
            ),
                array(
                'label'   => esc_html__( 'Intro Description', 'gf-autoadvanced' ),
                'type'    => 'textarea',
                'name'    => 'intro_description',
                'tooltip' => esc_html__( 'Intro Description', 'gf-autoadvanced' ),
            )
            ),
            );
        }
        
        if ( true ) {
            // Conversational Form Styling Settings
            if ( true ) {
                // General Style
                $conversational_settings['fields'][] = array(
                    'title'     => '<h3>' . __( 'Form Style Options', 'gf-autoadvanced' ) . '</h3>',
                    'type'      => 'element_style',
                    'name'      => 'form_style',
                    'subfields' => array(
                    array(
                    'label'         => esc_html__( 'Form Theme', 'gf-autoadvanced' ),
                    'name'          => 'bg-color',
                    'type'          => 'color',
                    'default_value' => '#bcd6ec',
                ),
                    array(
                    'label'         => esc_html__( 'Primary Color', 'gf-autoadvanced' ),
                    'name'          => 'primary-color',
                    'type'          => 'color',
                    'default_value' => '#1a3d5c',
                ),
                    array(
                    'label'         => esc_html__( 'Secondary Colors', 'gf-autoadvanced' ),
                    'name'          => 'secondary-color',
                    'type'          => 'color',
                    'default_value' => '#e4eef7',
                ),
                    array(
                    'label'         => esc_html__( 'Footer Background', 'gf-autoadvanced' ),
                    'name'          => 'progressbar-color',
                    'type'          => 'color',
                    'default_value' => '#448ccb',
                ),
                    array(
                    'label'         => esc_html__( 'Buttons', 'gf-autoadvanced' ),
                    'name'          => 'button-bg',
                    'type'          => 'text',
                    'default_value' => '#448ccb',
                ),
                    array(
                    'label'         => esc_html__( 'Buttons Text', 'gf-autoadvanced' ),
                    'name'          => 'button-text',
                    'type'          => 'color',
                    'default_value' => '#f1f1f1',
                ),
                    array(
                    'label'         => esc_html__( 'Button Hover', 'gf-autoadvanced' ),
                    'name'          => 'button-hover-bg',
                    'type'          => 'text',
                    'default_value' => '#357fc0',
                ),
                    array(
                    'label'         => esc_html__( 'Buttons Text Hover', 'gf-autoadvanced' ),
                    'name'          => 'button-hover-text',
                    'type'          => 'text',
                    'default_value' => '#f1f1f1',
                ),
                    array(
                    'label'         => esc_html__( 'Confirmation Message Text Color', 'gf-autoadvanced' ),
                    'name'          => 'confirmation-text',
                    'type'          => 'text',
                    'default_value' => '#f1f1f1',
                )
                ),
                );
            }
            if ( false ) {
                // Introduction Style
                $conversational_settings['fields'][] = array(
                    'title'     => '<h3>' . __( 'Introduction Style', 'gf-autoadvanced' ) . '</h3>',
                    'type'      => 'element_style',
                    'name'      => 'intro',
                    'subfields' => array(
                    array(
                    'label'         => esc_html__( 'Intro Background', 'gf-autoadvanced' ),
                    'name'          => 'bg',
                    'type'          => 'color',
                    'default_value' => '#251E53',
                    'value'         => '',
                ),
                    array(
                    'label'         => esc_html__( 'Intro Padding', 'gf-autoadvanced' ),
                    'name'          => 'intro_padding',
                    'type'          => 'text',
                    'default_value' => '40',
                    'value'         => '',
                ),
                    array(
                    'label'         => esc_html__( 'Intro Max Width', 'gf-autoadvanced' ),
                    'name'          => 'intro_max_width',
                    'type'          => 'text',
                    'default_value' => '800',
                    'value'         => '',
                ),
                    array(
                    'label'         => esc_html__( 'Logo Width', 'gf-autoadvanced' ),
                    'name'          => 'logo_width',
                    'type'          => 'text',
                    'default_value' => '100',
                    'value'         => '',
                ),
                    array(
                    'label'         => esc_html__( 'Title Color', 'gf-autoadvanced' ),
                    'name'          => 'title_color',
                    'type'          => 'color',
                    'default_value' => '#FFC520',
                    'value'         => '',
                ),
                    array(
                    'label'         => esc_html__( 'Font Size', 'gf-autoadvanced' ),
                    'name'          => 'font_size',
                    'type'          => 'text',
                    'default_value' => '36',
                    'value'         => '',
                ),
                    array(
                    'label'         => esc_html__( 'Description Color', 'gf-autoadvanced' ),
                    'name'          => 'description_color',
                    'type'          => 'color',
                    'default_value' => '#ffffff',
                    'value'         => '',
                ),
                    array(
                    'label'         => esc_html__( 'Description Color', 'gf-autoadvanced' ),
                    'name'          => 'description_color',
                    'type'          => 'color',
                    'default_value' => '#ffffff',
                    'value'         => '',
                )
                ),
                );
            }
            if ( true ) {
                // Top Header
                $conversational_settings['fields'][] = array(
                    'title'     => '<h3>' . __( 'Introduction Style', 'gf-autoadvanced' ) . '</h3>',
                    'type'      => 'header_style',
                    'name'      => 'header',
                    'subfields' => array( array(
                    'label'         => esc_html__( 'Header Background', 'gf-autoadvanced' ),
                    'name'          => 'bg',
                    'type'          => 'color',
                    'default_value' => '#251E53',
                    'value'         => '',
                ) ),
                );
            }
        }
        
        $settings = array();
        $settings[] = $main_settings;
        $settings[] = $animation_settings;
        $settings[] = $conversational_settings;
        $settings[] = $colors_settings;
        return $settings;
    }
    
    public function settings_element_style( $field )
    {
        echo  '<div class="gfaa-cg-wrap">
				<div class="gfaa-cg-heading">' . $field['title'] . '</div>
				
				<div class="gfaa-cg-fields" style="display: none;">' ;
        foreach ( $field['subfields'] as $field ) {
            // print_r($field);
            $text_field = $field;
            $create_field = $this->settings_text( $text_field, false );
            // echo $create_image_field;
            echo  '<div class="gfaa-cg-field">
								<div class="gfaa-field-label">' . $field['label'] . '</div>
								<div class="gfaa-field-text">' . $create_field . '</div>
							</div>
						' ;
        }
        echo  '</div>
			</div>
		' ;
    }
    
    public function settings_message( $field )
    {
        $message = '';
        echo  '<div class="gfaa-cg-wrap">
				<h3></h3>
			</div>
		' ;
    }
    
    public function settings_color_group( $field )
    {
        echo  '<div class="gfaa-cg-wrap">
				<div class="gfaa-cg-heading">' . $field['title'] . '</div>
				
				<div class="gfaa-cg-fields" style="display: none;">' ;
        foreach ( $field['subfields'] as $field ) {
            // print_r($field);
            $text_field = $field;
            $create_field = $this->settings_text( $text_field, false );
            // echo $create_image_field;
            echo  '<div class="gfaa-cg-field">
								<div class="gfaa-field-label">' . $field['label'] . '</div>
								<div class="gfaa-field-text">' . $create_field . '</div>
							</div>
						' ;
        }
        echo  '</div>
			</div>
		' ;
    }
    
    public function settings_gfaa_image( $field )
    {
        $field_name = $field['name'];
        $text_field = array(
            'name'  => $field_name,
            'label' => esc_html__( 'Image URL', 'gf-autoadvanced' ),
            'type'  => 'text',
            'value' => '',
        );
        $create_image_field = $this->settings_text( $text_field, false );
        echo  '<div class="gfaa-field-wrap">
				<div class="gfaa-field-text">' . $create_image_field . '</div>
				<div class="gfaa-field-upload"><a href="#" class="custom-button-field gform-button gform-button--white">Choose Image</a></div>
			</div>
		' ;
    }
    
    public function add_gfaa_settings_tab( $tabs, $form )
    {
        $tabs[] = array(
            'id'             => 'gfaa_tab',
            'title'          => esc_html__( 'Auto Advance', 'gf-autoadvanced' ),
            'toggle_classes' => array( 'gfa_toggle' ),
            'body_classes'   => array( 'gfa_toggle_body' ),
        );
        return $tabs;
    }
    
    public function gfaa_settings_tab_content( $form, $tab_id )
    {
        
        if ( $tab_id == 'gfaa_tab' ) {
            // 'text', 'textarea', 'checkbox', 'number', 'address', 'product'
            // echo "<pre>"; print_r($form); echo "</pre>";
            ?>
			
			
			<li class="gfaa_field_list_value field_setting">
				<input type="checkbox" id="field_list_value" onclick="SetFieldProperty('autoAdvancedField', this.checked); 
					if(this.checked && ( field.type == 'text' || field.type == 'textarea' || field.type == 'checkbox' || field.type == 'number' || field.type == 'address' ) ) { $('.gfaa_inputNumberKeys').show();} else { $('.gfaa_inputNumberKeys').hide(); }	 " 
				/>
				<label class="inline" for="field_list_value">
					<?php 
            _e( "Auto advance form", "gf-autoadvanced" );
            gform_tooltip( 'gfa_autoadvanced' );
            ?>
				</label>
			</li>
			
			<li class="gfaa_hide_next_button field_setting">
				<input type="checkbox" id="hide_next_button" onclick="SetFieldProperty('hideNextButton', this.checked);" />
				<label class="inline" for="hide_next_button">
					<?php 
            _e( "Hide Next / Submit Button", "gf-autoadvanced" );
            gform_tooltip( 'gfa_autoadvanced_next' );
            ?>
				</label>
			</li>
			
			<li class="gfaa_hidePreviousButton field_setting">
				<input type="checkbox" id="hidePreviousButton" onclick="SetFieldProperty('hidePreviousButton', this.checked);" />
				<label class="inline" for="hidePreviousButton">
					<?php 
            _e( "Hide Previous Button", "gf-autoadvanced" );
            gform_tooltip( 'gfa_autoadvanced_previous' );
            ?>
				</label>
			</li>
			
			<li class="gfaa_inputNumberKeys field_setting">
				<label class="section_label" for="inputNumberKeys">
					<?php 
            _e( "Number of selections to Auto Advanced", "gf-autoadvanced" );
            gform_tooltip( 'gfa_autoadvanced_number' );
            ?>
				</label>
				<input type="number" id="inputNumberKeys" onkeyup="SetFieldProperty('inputNumberKeys', this.value);" />
			</li>
			
			
			<?php 
            $has_conversational = false;
            if ( isset( $form['gfaa'] ) && isset( $form['gfaa']['gfaa_type'] ) && $form['gfaa']['gfaa_type'] == 'conversational' ) {
                if ( isset( $form['gfaa']['enable_conversational'] ) && $form['gfaa']['enable_conversational'] == 1 ) {
                    $has_conversational = true;
                }
            }
            ?>
			<li class="gfaa_image_setting field_setting <?php 
            echo  ( $has_conversational ? 'has_conversational' : 'forcehide' ) ;
            ?>">
				<label for="gfac_image_url" class="section_label">
					<?php 
            esc_html_e( 'Side Image', 'gravityforms' );
            gform_tooltip( 'gfa_side_image' );
            ?>
				</label>
				<input type="text" id="gfac_image_url" class="gfac_image_url" style="margin-bottom: 10px;"/>
				<a href="#" class="custom-button gform-button gform-button--white">Choose Image</a>
			</li>
			
			<?php 
        }
    
    }
    
    public function gform_field_container(
        $field_container,
        $field,
        $form,
        $css_class,
        $style,
        $field_content
    )
    {
        $image_url = ( isset( $field->gfac_image_url ) ? $field->gfac_image_url : '' );
        $field_container = str_replace( ">", " data-image='{$image_url}' >", $field_container );
        return $field_container;
    }
    
    public function gform_field_add_gfa_data(
        $field_container,
        $field,
        $form,
        $css_class,
        $style,
        $field_content
    )
    {
        $gfaa_data = '';
        if ( isset( $field->inputNumberKeys ) && $field->inputNumberKeys ) {
            $gfaa_data .= ' data-inputNumberKeys=' . $field->inputNumberKeys;
        }
        $field_container = str_replace( ">", $gfaa_data . " >", $field_container );
        return $field_container;
    }
    
    public function gform_form_tag( $form_tag, $form )
    {
        return $form_tag;
        $current_page_id = get_the_ID();
        if ( !isset( $form['gfaa'] ) || !isset( $form['gfaa']['enable_conversational'] ) || !isset( $form['gfaa']['page'] ) ) {
            return $form_tag;
        }
        if ( !isset( $form['gfaa'] ) || !isset( $form['gfaa']['gfaa_type'] ) || $form['gfaa']['gfaa_type'] != 'conversational' ) {
            return $form_tag;
        }
        if ( $form['gfaa']['enable_conversational'] != 1 || $form['gfaa']['page'] != $current_page_id ) {
            return $form_tag;
        }
        $asset_fields = array(
            'cover-image',
            'logo-image',
            'side-image',
            'conversational-layout'
        );
        $style_fields = array(
            'bg-color',
            'primary-color',
            'secondary-color',
            'progressbar-color',
            'button-bg',
            'button-text',
            'button-hover-bg',
            'button-hover-text',
            'confirmation-text',
            'border-color'
        );
        $style_data = '';
        $gfaa = ( isset( $form['gfaa'] ) ? $form['gfaa'] : array() );
        if ( $gfaa ) {
            foreach ( $style_fields as $field_id ) {
                if ( isset( $gfaa[$field_id] ) && $gfaa[$field_id] ) {
                    $style_data .= '--cf-' . $field_id . ': ' . $gfaa[$field_id] . ';';
                }
            }
        }
        $style_data .= '';
        $form_tag = str_replace( ">", " style='{$style_data}' >", $form_tag );
        // echo "<pre>"; print_r($form); echo "</pre>";
        return $form_tag;
    }
    
    public function theme_page_templates(
        $post_templates,
        $wp_theme,
        $post,
        $post_type
    )
    {
        $post_templates['conversational-template.php'] = __( 'Conversational Template' );
        return $post_templates;
    }
    
    public function page_template( $page_template )
    {
        $current_page_id = get_the_ID();
        $forms = GFAPI::get_forms();
        
        if ( is_page() ) {
            $custom_template = false;
            foreach ( $forms as $form ) {
                if ( isset( $form['gfaa'] ) && isset( $form['gfaa']['enable_conversational'] ) && isset( $form['gfaa']['page'] ) && isset( $form['gfaa']['gfaa_type'] ) ) {
                    
                    if ( $form['gfaa']['gfaa_type'] == 'conversational' && $form['gfaa']['enable_conversational'] == 1 && $form['gfaa']['page'] == $current_page_id ) {
                        $page_template = ZZD_AAGF_DIR . 'php/conversational-template.php';
                        
                        if ( isset( $_GET['muz'] ) ) {
                            echo  "<pre>" ;
                            print_r( $form['gfaa'] );
                            echo  "</pre>" ;
                            echo  "<pre>" ;
                            print_r( $form );
                            echo  "</pre>" ;
                            exit;
                        }
                        
                        show_admin_bar( false );
                        break;
                    }
                
                }
            }
        }
        
        return $page_template;
    }
    
    public static function get_body_style( $form )
    {
        $style_fields = array(
            'bg-color',
            'primary-color',
            'secondary-color',
            'progressbar-color',
            'button-bg',
            'button-text',
            'button-hover-bg',
            'button-hover-text',
            'border-color',
            'conversational-layout',
            'confirmation-text'
        );
        $image_fields = array( 'cover-image', 'logo-image', 'side-image' );
        $style_data = '';
        $gfaa = ( isset( $form['gfaa'] ) ? $form['gfaa'] : array() );
        
        if ( $gfaa ) {
            foreach ( $style_fields as $field_id ) {
                if ( isset( $gfaa[$field_id] ) && $gfaa[$field_id] ) {
                    $style_data .= '--cf-' . $field_id . ': ' . $gfaa[$field_id] . ';';
                }
            }
            foreach ( $image_fields as $field_id ) {
                if ( isset( $gfaa[$field_id] ) && $gfaa[$field_id] ) {
                    $style_data .= '--cf-' . $field_id . ': url(' . $gfaa[$field_id] . ');';
                }
            }
        }
        
        $style_data .= '';
        return $style_data;
    }
    
    public static function get_body_attrs( $form )
    {
        // return '';
        $asset_fields = array(
            'cover-image',
            'logo-image',
            'side-image',
            'conversational-layout'
        );
        $attrs = [];
        $gfaa = ( isset( $form['gfaa'] ) ? $form['gfaa'] : array() );
        if ( $gfaa ) {
            foreach ( $asset_fields as $field_id ) {
                if ( isset( $gfaa[$field_id] ) && $gfaa[$field_id] ) {
                    $attrs[$field_id] = 'data-' . $field_id . ' = "' . $gfaa[$field_id] . '"';
                }
            }
        }
        return implode( ' ', $attrs );
    }
    
    public static function get_body_classes( $form )
    {
        $classes = array( 'cleanpage', 'conv-form' );
        $class_fields = array( 'conversational-layout' );
        if ( is_user_logged_in() ) {
            $classes[] = 'logged-in';
        }
        if ( is_admin_bar_showing() ) {
            $classes[] = 'admin-bar';
        }
        $gfaa = ( isset( $form['gfaa'] ) ? $form['gfaa'] : array() );
        if ( $gfaa ) {
            foreach ( $class_fields as $field_id ) {
                if ( isset( $gfaa[$field_id] ) && $gfaa[$field_id] ) {
                    $classes[] = $gfaa[$field_id];
                }
            }
        }
        return implode( ' ', $classes );
    }
    
    public static function add_color_customizer( $form )
    {
        include ZZD_AAGF_DIR . 'php/color-customizer.php';
    }
    
    public function aafg_save_form_colors()
    {
        $form_data = $_POST['form_data'];
        // Example: Perform some logic based on the submitted data
        $response = array();
        
        if ( $form_data ) {
            // Process the serialized form data
            parse_str( $form_data, $form_fields );
            $form = GFAPI::get_form( $form_fields['form_id'] );
            $gfaa = ( isset( $form['gfaa'] ) ? $form['gfaa'] : array() );
            // Example: Get the value of a specific form field
            
            if ( $gfaa ) {
                foreach ( $form_fields as $key => $value ) {
                    if ( isset( $gfaa[$key] ) ) {
                        $gfaa[$key] = $value;
                    }
                }
                $form['gfaa'] = $gfaa;
                GFAPI::update_form( $form );
                $response['status'] = 'success';
                $response['message'] = esc_html__( 'Form data received and processed successfully.', 'gf-autoadvanced' );
            } else {
                // echo "<pre>"; print_r($gfaa); echo "</pre>";
                $response['status'] = 'error';
                $response['message'] = esc_html__( 'Auto Advanced Form Settings Not Found.', 'gf-autoadvanced' );
            }
        
        } else {
            $response['status'] = 'error';
            $response['message'] = esc_html__( 'No form data received.', 'gf-autoadvanced' );
        }
        
        // Send the JSON response back to the client
        wp_send_json( $response );
    }

}