<?php
/**
 * Define the Settings functionality
 *
 * Loads and defines the Settings Fields for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wooninjas.com/
 * @since      1.0.0
 *
 * @package    Wn_Plugin_Boilerplate
 * @subpackage Wn_Plugin_Boilerplate/includes
 */

/**
 * Define the Settings functionality.
 *
 * Loads and defines the Settings Fields for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wn_Plugin_Boilerplate
 * @subpackage Wn_Plugin_Boilerplate/includes
 * @author     WooNinjas <info@wooninjas.com>
 */
if ( ! class_exists( 'Wn_Plugin_Settings_API' ) ):
class Wn_Plugin_Settings_API {

    /**
     * settings sections array
     *
     * @var array
     */
    protected $settings_sections = array();

    /**
     * Settings fields array
     *
     * @var array
     */
    protected $settings_fields = array();

    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

        $this->settings_sections = apply_filters( 'wn_dashboard_setting_sections', [] );
        $this->settings_fields = apply_filters( 'wn_dashboard_setting_fields', [] );
    }

    /**
     * Enqueue scripts and styles
     */
    function admin_enqueue_scripts() {
        wp_enqueue_style( 'wp-color-picker' );

        wp_enqueue_media();
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script( 'jquery' ); 
    }

    /**
     * Set settings sections
     *
     * @param array   $sections setting sections array
     */
    function set_sections( $sections ) {
        $this->settings_sections = apply_filters( 'wn_dashboard_set_setting_sections', $sections );

        return $this;
    }

    /**
     * Get settings sections
     *
     * @return $sections
     */
    function get_sections() {
        return $this->settings_sections;
    }
 
    /**
     * Add a single section
     *
     * @param array   $section
     */
    function add_section( $section ) {
        $this->settings_sections[] = $section;

        return $this;
    }

    /**
     * Set settings fields
     *
     * @param array   $fields settings fields array
     */
    function set_fields( $fields ) {
        $this->settings_fields = apply_filters( 'wn_dashboard_set_setting_fields', $fields );

        return $this;
    }

    /**
     * Get settings fields
     *
     * @return $fields
     */
    function get_fields() {
        return $this->settings_fields;
    }

    function add_field( $section, $field ) {
        $defaults = array(
            'name'  => '',
            'label' => '',
            'desc'  => '',
            'type'  => 'text'
        );

        $arg = wp_parse_args( $field, $defaults );
        $this->settings_fields[$section][] = $arg;

        return $this;
    }

    /**
     * Initialize and registers the settings sections and fileds to WordPress
     *
     * Usually this should be called at `admin_init` hook.
     *
     * This function gets the initiated settings sections and fields. Then
     * registers them to WordPress and ready for use.
     */
    function admin_init() {
        //register settings sections
        foreach ( $this->settings_sections as $section ) {
            if ( false == get_option( $section['id'] ) ) {
                add_option( $section['id'] );
            }

            if ( isset($section['desc']) && !empty($section['desc']) ) {
                $section['desc'] = '<div class="inside">' . $section['desc'] . '</div>';
                $callback = function() use ( $section ) {
                    echo str_replace( '"', '\"', $section['desc'] );
                };
            } else if ( isset( $section['callback'] ) ) {
                $callback = $section['callback'];
            } else {
                $callback = null;
            }

            $is_main = true;
            if( isset( $section['has_subtab'] ) && $section['has_subtab'] == 'Yes' ) {
                $stabs = $section['subtabs'];
                if( isset( $stabs ) && count( $stabs ) > 0 ) {
                    $is_main = false;
                    foreach( $stabs as $stab ) {
                        add_settings_section( $stab['id'], $stab['title'], $callback, $stab['id'] );
                    }
                }
            }
            
            if( $is_main == true )
                add_settings_section( $section['id'], $section['title'], $callback, $section['id'] );
            add_settings_section( $section['id'], $section['title'], $callback, $section['id'] );
        }

        //register settings fields
        foreach ( $this->settings_fields as $section => $field ) {
            foreach ( $field as $option ) {

                $name = $option['name'];
                $type = isset( $option['type'] ) ? $option['type'] : 'text';
                $label = isset( $option['label'] ) ? $option['label'] : '';
                $callback = isset( $option['callback'] ) ? $option['callback'] : array( $this, 'callback_' . $type );

                $args = array(
                    'id'                => $name,
                    'class'             => isset( $option['class'] ) ? $option['class'] : $name,
                    'label_for'         => "{$section}[{$name}]",
                    'desc'              => isset( $option['desc'] ) ? $option['desc'] : '',
                    'name'              => $label,
                    'section'           => $section,
                    'size'              => isset( $option['size'] ) ? $option['size'] : null,
                    'options'           => isset( $option['options'] ) ? $option['options'] : '',
                    'std'               => isset( $option['default'] ) ? $option['default'] : '',
                    'sanitize_callback' => isset( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : '',
                    'type'              => $type,
                    'placeholder'       => isset( $option['placeholder'] ) ? $option['placeholder'] : '',
                    'min'               => isset( $option['min'] ) ? $option['min'] : '',
                    'max'               => isset( $option['max'] ) ? $option['max'] : '',
                    'step'              => isset( $option['step'] ) ? $option['step'] : '',
                );

                add_settings_field( "{$section}[{$name}]", $label, $callback, $section, $section, $args );
            }
        }

        // creates our settings in the options table
        foreach ( $this->settings_sections as $section ) {
            
            $is_main = true;
            if( isset( $section['has_subtab'] ) && $section['has_subtab'] == 'Yes' ) {
                $stabs = $section['subtabs'];
                if( isset( $stabs ) && count( $stabs ) > 0 ) {
                    $is_main = false;
                    foreach( $stabs as $stab ) {
                        register_setting( $stab['id'], $stab['id'], array( $this, 'sanitize_options' ) );
                    }
                }
            }
            
            if( $is_main == true )
                register_setting( $section['id'], $section['id'], array( $this, 'sanitize_options' ) );
        }
    }

    /**
     * Get field description for display
     *
     * @param array   $args settings field args
     */
    public function get_field_description( $args ) {
        if ( ! empty( $args['desc'] ) ) {
            $desc = sprintf( '</span><p class="description">%s</p>', $args['desc'] );
        } else {
            $desc = '';
        }

        return $desc;
    }

    /**
     * Displays a text field for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_text( $args ) {

        $value       = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $size        = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
        $type        = isset( $args['type'] ) ? $args['type'] : 'text';
        $placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="' . $args['placeholder'] . '"';

        $html        = sprintf( '<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"%6$s/>', $type, $size, $args['section'], $args['id'], $value, $placeholder );
        
        $desc = $this->get_field_description( $args );
        if( $desc ) {
            $html       .= '<span class="wn-dashboard-help"></span>';
            $html       .= $desc;
        }

        echo $html;
    }

    /**
     * Displays a url field for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_url( $args ) {
        $this->callback_text( $args );
    }

    /**
     * Displays a number field for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_number( $args ) {
        $value       = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $size        = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
        $type        = isset( $args['type'] ) ? $args['type'] : 'number';
        $placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="' . $args['placeholder'] . '"';
        $min         = ( $args['min'] == '' ) ? '' : ' min="' . $args['min'] . '"';
        $max         = ( $args['max'] == '' ) ? '' : ' max="' . $args['max'] . '"';
        $step        = ( $args['step'] == '' ) ? '' : ' step="' . $args['step'] . '"';

        $html        = sprintf( '<input type="%1$s" class="%2$s-number" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"%6$s%7$s%8$s%9$s/>', $type, $size, $args['section'], $args['id'], $value, $placeholder, $min, $max, $step );
        $desc = $this->get_field_description( $args );
        if( $desc ) {
            $html       .= '<span class="wn-dashboard-help"></span>';
            $html       .= $desc;
        }
        
        echo $html;
    }

    /**
     * Displays a checkbox for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_checkbox( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );

        $html  = '<fieldset>';
        $html  .= sprintf( '<label for="wpuf-%1$s[%2$s]">', $args['section'], $args['id'] );
        $html  .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', $args['section'], $args['id'] );
        $html  .= sprintf( '<input type="checkbox" class="checkbox" id="wpuf-%1$s[%2$s]" name="%1$s[%2$s]" value="on" %3$s />', $args['section'], $args['id'], checked( $value, 'on', false ) );
        $html  .= sprintf( '%1$s</label>', $args['desc'] );
        $html  .= '</fieldset>';

        echo $html;
    }

    /**
     * Displays a multicheckbox for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_multicheck( $args ) {

        $value = $this->get_option( $args['id'], $args['section'], $args['std'] );
        $html  = '<fieldset>';
        $html .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="" />', $args['section'], $args['id'] );
        foreach ( $args['options'] as $key => $label ) {
            $checked = isset( $value[$key] ) ? $value[$key] : '0';
            $html    .= sprintf( '<label for="wpuf-%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key );
            $html    .= sprintf( '<input type="checkbox" class="checkbox" id="wpuf-%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $checked, $key, false ) );
            $html    .= sprintf( '%1$s</label><br>',  $label );
        }

        $html .= $this->get_field_description( $args );
        $html .= '</fieldset>';

        echo $html;
    }

    /**
     * Displays a radio button for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_radio( $args ) {

        $value = $this->get_option( $args['id'], $args['section'], $args['std'] );
        $html  = '<fieldset>';
        foreach ( $args['options'] as $key => $label ) {
            $desc  = $args['desc'];
            $help_icon = '';
            if ( is_array( $desc ) && array_key_exists( $key, $desc ) && ! empty( $desc[$key] ) ) {
                $help_icon = '<span class="wn-dashboard-radio-help"></span>';
            }
            $html .=  '<div>';
            $html .= sprintf( '<label for="wpuf-%1$s[%2$s][%3$s]">',  $args['section'], $args['id'], $key );
            $html .= sprintf( '<input type="radio" class="radio" id="wpuf-%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $value, $key, false ) );
            $html .= sprintf( '%1$s</label>%2$s<br>', $label, $help_icon );
            
            
            if ( is_array( $desc ) && array_key_exists( $key, $desc ) && ! empty( $desc[$key] ) ) {
                $html .=  '<p class="description">'.$desc[$key].'</p>';
            }
            $html .=  '</div>';
        }
        $html .= '</fieldset>';

        echo $html;
    }

    /**
     * Displays a selectbox for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_select( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $size  = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
        $html  = sprintf( '<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]">', $size, $args['section'], $args['id'] );

        foreach ( $args['options'] as $key => $label ) {
            $html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
        }

        $html .= sprintf( '</select>' );
        $desc = $this->get_field_description( $args );
        if( $desc ) {
            $html       .= '<span class="wn-dashboard-help"></span>';
            $html       .= $desc;
        }

        echo $html;
    }

    /**
     * Displays a textarea for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_textarea( $args ) {

        $value       = esc_textarea( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $size        = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
        $placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="'.$args['placeholder'].'"';

        $html        = sprintf( '<textarea rows="5" cols="55" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]"%4$s>%5$s</textarea>', $size, $args['section'], $args['id'], $placeholder, $value );
        $desc = $this->get_field_description( $args );
        if( $desc ) {
            $html       .= '<span class="wn-dashboard-help"></span>';
            $html       .= $desc;
        }

        echo $html;
    }

    /**
     * Displays the html for a settings field
     *
     * @param array   $args settings field args
     * @return string
     */
    function callback_html( $args ) {
        echo $this->get_field_description( $args );
    }

    /**
     * Displays a rich text textarea for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_wysiwyg( $args ) {

        $value = $this->get_option( $args['id'], $args['section'], $args['std'] );
        $size  = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : '500px';

        echo '<div style="max-width: ' . $size . ';">';

        $editor_settings = array(
            'teeny'         => true,
            'textarea_name' => $args['section'] . '[' . $args['id'] . ']',
            'textarea_rows' => 10
        );

        if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {
            $editor_settings = array_merge( $editor_settings, $args['options'] );
        }

        wp_editor( $value, $args['section'] . '-' . $args['id'], $editor_settings );
        echo '<p class="wn-dashboard-description-note">'.$args['desc'].'</p>';
        echo '</div>';
        
       // echo  $this->get_field_description( $args );
    }

    /**
     * Displays a file upload field for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_file( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $size  = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
        $id    = $args['section']  . '[' . $args['id'] . ']';
        $label = isset( $args['options']['button_label'] ) ? $args['options']['button_label'] : __( 'Choose File' );

        $html  = sprintf( '<input type="text" class="%1$s-text wpsa-url" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
        $html  .= '<input type="button" class="button wpsa-browse" value="' . $label . '" />';
        
        $desc = $this->get_field_description( $args );
        if( $desc ) {
            $html  .= '<span class="wn-dashboard-help"></span>';
            $html  .= $desc;
        }

        echo $html;
    }

    /**
     * Displays a password field for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_password( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $size  = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

        $html  = sprintf( '<input type="password" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
        $desc = $this->get_field_description( $args );
        if( $desc ) {
            $html  .= '<span class="wn-dashboard-help"></span>';
            $html  .= $desc;
        }

        echo $html;
    }

    /**
     * Displays a color picker field for a settings field
     *
     * @param array   $args settings field args
     */
    function callback_color( $args ) {

        $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
        $size  = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

        $html  = sprintf( '<input type="text" class="%1$s-text wp-color-picker-field" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" data-default-color="%5$s" />', $size, $args['section'], $args['id'], $value, $args['std'] );
        $desc = $this->get_field_description( $args );
        if( $desc ) {
            $html  .= '<span class="wn-dashboard-help"></span>';
            $html  .= $desc;
        }

        echo $html;
    }


    /**
     * Displays a select box for creating the pages select box
     *
     * @param array   $args settings field args
     */
    function callback_pages( $args ) {

        $dropdown_args = array(
            'selected' => esc_attr($this->get_option($args['id'], $args['section'], $args['std'] ) ),
            'name'     => $args['section'] . '[' . $args['id'] . ']',
            'id'       => $args['section'] . '[' . $args['id'] . ']',
            'echo'     => 0
        );
        $html = wp_dropdown_pages( $dropdown_args );
        echo $html;
    }

    /**
     * Sanitize callback for Settings API
     *
     * @return mixed
     */
    function sanitize_options( $options ) {

        if ( !$options ) {
            return $options;
        }

        foreach( $options as $option_slug => $option_value ) {
            $sanitize_callback = $this->get_sanitize_callback( $option_slug );

            // If callback is set, call it
            if ( $sanitize_callback ) {
                $options[ $option_slug ] = call_user_func( $sanitize_callback, $option_value );
                continue;
            }
        }

        return $options;
    }

    /**
     * Get sanitization callback for given option slug
     *
     * @param string $slug option slug
     *
     * @return mixed string or bool false
     */
    function get_sanitize_callback( $slug = '' ) {
        if ( empty( $slug ) ) {
            return false;
        }

        // Iterate over registered fields and see if we can find proper callback
        foreach( $this->settings_fields as $section => $options ) {
            foreach ( $options as $option ) {
                if ( $option['name'] != $slug ) {
                    continue;
                }

                // Return the callback name
                return isset( $option['sanitize_callback'] ) && is_callable( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : false;
            }
        }

        return false;
    }

    /**
     * Get the value of a settings field
     *
     * @param string  $option  settings field name
     * @param string  $section the section name this field belongs to
     * @param string  $default default text if it's not found
     * @return string
     */
    function get_option( $option, $section, $default = '' ) {

        $options = get_option( $section );

        if ( isset( $options[$option] ) ) {
            return $options[$option];
        }

        return $default;
    }

    /**
     * Show navigations as tab
     *
     * Shows all the settings section labels as tab
     */
    function show_navigation() {
        $html = '<h2 class="nav-tab-wrapper">';
        
        $s_tab = '';
        if( isset( $_REQUEST['stab'] ) ) {
            $s_tab = sanitize_text_field( $_REQUEST['stab'] );
        }

        $current_tab = '';
        if( isset( $_REQUEST['tab'] ) ) {
            $current_tab = sanitize_text_field( $_REQUEST['tab'] );
        }

        $count = count( $this->settings_sections );

        // don't show the navigation if only one section exists
        if ( $count === 0 ) {
            return;
        }
        
        $is_first = false;
        if( empty( $current_tab ) ) {
            $is_first = true;
        }
        foreach ( $this->settings_sections as $tab ) {
            
            $active_tab = '';
            if( $current_tab == $tab['id'] || $is_first ) {
                $active_tab = 'nav-tab-active';
                $sub_tabs_html = '';
                if( isset( $tab['has_subtab'] ) && $tab['has_subtab'] == 'Yes' ) {
                    $sub_tabs_html = '<div id="wn-dashboard-settings-stabs">';
                    $sub_tabs_html .= '<ul class="subsubsub">';
                        $stabs = $tab['subtabs'];
                        if( isset( $stabs ) && count( $stabs ) > 0 ) {
                            $index = 0;
                            
                            $is_first_sub = false;
                            if( empty( $s_tab ) ) {
                                $is_first_sub = true;
                            }
                            foreach( $stabs as $stab ) {
                                if( $index > 0 )
                                    $sub_tabs_html .= '&nbsp;|&nbsp;';
                                
                                $active_classes = '';
                                if( ( $s_tab==$stab['id'] ) || ( $is_first_sub ) ) {
                                    $active_classes = 'wn_dashboard_active_tab wn_dashboard_active_tab_'.$stab['id'];
                                }
                                $is_first_sub = false;
                                $sub_tabs_html .= '<li class="'.$active_classes.'">';
                                $sub_tabs_html .= sprintf( '<a href="admin.php?page=wooninjas-dashboard-setting&tab=%1$s&stab=%4$s" class="wn_dashboard_side_tab_link" id="%1$s-tab">%3$s</a>', $tab['id'], $active_tab, $stab['title'], $stab['id'] );
                                $sub_tabs_html .= '</li>';

                                $index++;
                            }
                        }
                    $sub_tabs_html .= '</ul></div>';
                }
            }
            $is_first = false;
            $html .= sprintf( '<a href="admin.php?page=wooninjas-dashboard-setting&tab=%1$s" class="nav-tab %2$s" id="%1$s-tab">%3$s</a>', $tab['id'], $active_tab, $tab['title'] );
            
        }

        $html .= '</h2>';

        echo $html.$sub_tabs_html;
    }

    /**
     * Show the section settings forms
     *
     * This function displays every sections in a different form
     */
    function show_forms() {
        $tab = '';
        if( isset( $_REQUEST['tab'] ) ) {
            $tab = sanitize_text_field( $_REQUEST['tab'] );
        }
        
        $s_tab = '';
        if( isset( $_REQUEST['stab'] ) ) {
            $s_tab = sanitize_text_field( $_REQUEST['stab'] );
        }

        $is_first = false;
        if( empty( $tab ) ) {
            $is_first = true;
        }
        ?>
        <div class="metabox-holder">
            <?php foreach ( $this->settings_sections as $form ) { 
                $form_id = $form['id'];
                if( $tab == $form_id || $is_first ) {
                    $is_first = false;

                    if( isset( $form['has_subtab'] ) && $form['has_subtab'] == 'Yes' ) {
                        $stabs = $form['subtabs'];
                        if( isset( $stabs ) && count( $stabs ) > 0 ) {
                            $is_first_sub = false;
                            if( empty( $s_tab ) ) {
                                $is_first_sub = true;
                            }
                            foreach( $stabs as $stab ) {
                                if( ( $s_tab == $stab['id'] ) || ( $is_first_sub ) ) {
                                    $form_id = $stab['id'];
                                }
                                $is_first_sub = false;
                            }
                        }
                    }
                ?>
                <div id="<?php echo $form['id']; ?>" class="group">
                    <form method="post" action="options.php">
                        <?php
                        do_action( 'wn_settings_form_top_' . $form_id, $form );
                        settings_fields( $form_id );
                        do_settings_sections( $form_id );
                        do_action( 'wn_settings_form_bottom_' . $form_id, $form );
                        if ( isset( $this->settings_fields[ $form_id ] ) ):
                        ?>
                        <div style="padding-left: 10px">
                            <?php submit_button(); ?>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>
            <?php } 
            }
            ?>
        </div>
        <?php
        $this->script();
    }

    /**
     * Tabbable JavaScript codes & Initiate Color Picker
     *
     * This code uses localstorage for displaying active tabs
     */
    function script() {
        $this->_style_fix();
    }

    function _style_fix() {
        global $wp_version;

        if (version_compare($wp_version, '3.8', '<=')):
        ?>
        <style type="text/css">
            /** WordPress 3.8 Fix **/
            .form-table th { padding: 20px 10px; }
            #wpbody-content .metabox-holder { padding-top: 5px; }
        </style>
        <?php
        endif;
    }

}

endif;