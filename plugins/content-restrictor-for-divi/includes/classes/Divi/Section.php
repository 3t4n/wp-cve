<?php

namespace WPT\RestrictContent\Divi;

/**
 * Section.
 */
class Section
{
    protected  $container ;
    /**
     * Constructor.
     */
    public function __construct( $container )
    {
        $this->container = $container;
    }
    
    /**
     * Add fields to the section module
     */
    public function add_fields( $fields_unprocessed )
    {
        $fields['wpt_cr_restrict_content'] = [
            'label'           => esc_html__( 'Restrict Content', 'divi-content-restrictor' ),
            'type'            => 'yes_no_button',
            'option_category' => 'configuration',
            'options'         => [
            'off' => esc_html__( 'No', 'divi-content-restrictor' ),
            'on'  => esc_html__( 'Yes', 'divi-content-restrictor' ),
        ],
            'default'         => 'off',
            'tab_slug'        => 'custom_css',
            'toggle_slug'     => 'wpt_cr_general',
            'description'     => 'Select "Yes" to enable content restriction for this section.',
        ];
        $fields['wpt_cr_content_access'] = [
            'label'       => esc_html__( 'Grant Access To', 'et_builder' ),
            'type'        => 'select',
            'options'     => $this->get_restriction_types(),
            'tab_slug'    => 'custom_css',
            'toggle_slug' => 'wpt_cr_general',
            'show_if'     => [
            'wpt_cr_restrict_content' => 'on',
        ],
            'description' => esc_html__( 'Select the type of content access', 'et_builder' ),
            'default'     => 'logged_in_user',
        ];
        $fields += $this->get_access_denied_text_fields();
        return array_merge( $fields_unprocessed, $fields );
    }
    
    public function get_user_roles_fields()
    {
        $fields = [];
        $roles = $this->container['bootstrap']->get_roles();
        $fields['roles_any'] = [
            'label'           => esc_html__( 'Roles', 'et_builder' ),
            'type'            => 'wpt_cr_multi_select',
            'description'     => 'Select one or more WordPress roles. Users belonging to any of these role will be given access',
            'option_category' => 'basic_option',
            'options'         => $roles,
            'tab_slug'        => 'custom_css',
            'toggle_slug'     => 'wpt_cr_general',
            'vb_support'      => 'off',
            'default'         => '[]',
            'show_if'         => [
            'wpt_cr_content_access'   => 'user_roles_any',
            'wpt_cr_restrict_content' => 'on',
        ],
        ];
        $fields['roles_all'] = [
            'label'           => esc_html__( 'Roles', 'et_builder' ),
            'type'            => 'wpt_cr_multi_select',
            'description'     => 'Select one or more WordPress roles. Users belonging to all of these role will be given access',
            'option_category' => 'basic_option',
            'options'         => $roles,
            'tab_slug'        => 'custom_css',
            'toggle_slug'     => 'wpt_cr_general',
            'vb_support'      => 'off',
            'default'         => '[]',
            'show_if'         => [
            'wpt_cr_content_access'   => 'user_roles_all',
            'wpt_cr_restrict_content' => 'on',
        ],
        ];
        return $fields;
    }
    
    /**
     * Access denied text field
     */
    public function get_access_denied_text_fields()
    {
        $fields = [];
        $fields['wpt_cr_access_denied_text'] = [
            'label'           => esc_html__( 'Text Message', 'et_builder' ),
            'type'            => 'tiny_mce',
            'tab_slug'        => 'custom_css',
            'toggle_slug'     => 'wpt_cr_content',
            'option_category' => 'layout',
            'description'     => esc_html__( 'Enter the text to be used when access is denied.', 'et_builder' ),
            'show_if'         => [
            'wpt_cr_restrict_content' => 'on',
        ],
            'default'         => 'This content is restricted',
            'priority'        => 1,
        ];
        $fields['wpt_cr_access_denied_text_font'] = [
            'label'           => esc_html__( 'Text Message', 'et_builder' ),
            'type'            => 'font',
            'tab_slug'        => 'custom_css',
            'toggle_slug'     => 'wpt_cr_content',
            'option_category' => 'layout',
            'show_if'         => [
            'wpt_cr_restrict_content' => 'on',
        ],
            'default'         => '',
            'priority'        => 2,
        ];
        $fields['wpt_cr_access_denied_text_align'] = [
            'label'           => esc_html__( 'Alignment', 'et_builder' ),
            'type'            => 'text_align',
            'tab_slug'        => 'custom_css',
            'toggle_slug'     => 'wpt_cr_content',
            'options'         => et_builder_get_text_orientation_options(),
            'option_category' => 'layout',
            'show_if'         => [
            'wpt_cr_restrict_content' => 'on',
        ],
            'default'         => 'left',
            'vb_support'      => false,
        ];
        $fields['wpt_cr_access_denied_text_font_color'] = [
            'label'           => esc_html__( 'Color', 'et_builder' ),
            'type'            => 'color-alpha',
            'tab_slug'        => 'custom_css',
            'toggle_slug'     => 'wpt_cr_content',
            'option_category' => 'layout',
            'show_if'         => [
            'wpt_cr_restrict_content' => 'on',
        ],
            'default'         => '',
            'priority'        => 2,
            'vb_support'      => false,
        ];
        $fields['wpt_cr_access_denied_text_font_bg_color'] = [
            'label'           => esc_html__( 'Background Color', 'et_builder' ),
            'type'            => 'color-alpha',
            'tab_slug'        => 'custom_css',
            'toggle_slug'     => 'wpt_cr_content',
            'option_category' => 'layout',
            'show_if'         => [
            'wpt_cr_restrict_content' => 'on',
        ],
            'default'         => '',
            'priority'        => 2,
            'vb_support'      => false,
        ];
        $fields['wpt_cr_access_denied_border_top'] = [
            'label'          => esc_html__( 'Border Top', 'et_builder' ),
            'type'           => 'range',
            'range_settings' => [
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
        ],
            'tab_slug'       => 'custom_css',
            'toggle_slug'    => 'wpt_cr_content',
            'show_if'        => [
            'wpt_cr_restrict_content' => 'on',
        ],
            'allowed_units'  => [ 'px' ],
            'default_unit'   => 'px',
            'default'        => '0px',
            'vb_support'     => false,
        ];
        $fields['wpt_cr_access_denied_border_right'] = [
            'label'          => esc_html__( 'Border Right', 'et_builder' ),
            'type'           => 'range',
            'range_settings' => [
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
        ],
            'tab_slug'       => 'custom_css',
            'toggle_slug'    => 'wpt_cr_content',
            'show_if'        => [
            'wpt_cr_restrict_content' => 'on',
        ],
            'allowed_units'  => [ 'px' ],
            'default_unit'   => 'px',
            'default'        => '0px',
            'vb_support'     => false,
        ];
        $fields['wpt_cr_access_denied_border_bottom'] = [
            'label'          => esc_html__( 'Border Bottom', 'et_builder' ),
            'type'           => 'range',
            'range_settings' => [
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
        ],
            'tab_slug'       => 'custom_css',
            'toggle_slug'    => 'wpt_cr_content',
            'show_if'        => [
            'wpt_cr_restrict_content' => 'on',
        ],
            'allowed_units'  => [ 'px' ],
            'default_unit'   => 'px',
            'default'        => '0px',
            'vb_support'     => false,
        ];
        $fields['wpt_cr_access_denied_border_left'] = [
            'label'          => esc_html__( 'Border Left', 'et_builder' ),
            'type'           => 'range',
            'range_settings' => [
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
        ],
            'tab_slug'       => 'custom_css',
            'toggle_slug'    => 'wpt_cr_content',
            'show_if'        => [
            'wpt_cr_restrict_content' => 'on',
        ],
            'allowed_units'  => [ 'px' ],
            'default_unit'   => 'px',
            'default'        => '0px',
            'vb_support'     => false,
        ];
        $fields['wpt_cr_access_denied_border_style'] = [
            'label'       => esc_html__( 'Border Style', 'et_builder' ),
            'type'        => 'select',
            'options'     => [
            'solid'  => 'solid',
            'dotted' => 'dotted',
            'dashed' => 'dashed',
            'double' => 'double',
            'groove' => 'groove',
            'ridge'  => 'ridge',
            'inset'  => 'inset',
            'outset' => 'outset',
            'none'   => 'none',
            'hidden' => 'hidden',
        ],
            'tab_slug'    => 'custom_css',
            'toggle_slug' => 'wpt_cr_content',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [
            'wpt_cr_restrict_content' => 'on',
        ],
            'default'     => 'solid',
            'vb_support'  => false,
        ];
        $fields['wpt_cr_access_denied_border_color'] = [
            'label'       => esc_html__( 'Border Color', 'et_builder' ),
            'type'        => 'color-alpha',
            'tab_slug'    => 'custom_css',
            'toggle_slug' => 'wpt_cr_content',
            'description' => esc_html__( '', 'et_builder' ),
            'show_if'     => [
            'wpt_cr_restrict_content' => 'on',
        ],
            'default'     => '',
            'vb_support'  => false,
        ];
        $fields['wpt_cr_access_denied_custom_margin'] = [
            'label'          => esc_html__( 'Margin', 'et_builder' ),
            'type'           => 'custom_margin',
            'mobile_options' => false,
            'tab_slug'       => 'custom_css',
            'toggle_slug'    => 'wpt_cr_content',
            'show_if'        => [
            'wpt_cr_restrict_content' => 'on',
        ],
            'default'        => '0|0|0|0',
            'vb_support'     => false,
        ];
        $fields['wpt_cr_access_denied_custom_padding'] = [
            'label'          => esc_html__( 'Padding', 'et_builder' ),
            'type'           => 'custom_margin',
            'mobile_options' => false,
            'tab_slug'       => 'custom_css',
            'toggle_slug'    => 'wpt_cr_content',
            'show_if'        => [
            'wpt_cr_restrict_content' => 'on',
        ],
            'default'        => '0|0|0|0',
            'vb_support'     => false,
        ];
        $boxShadow = new BoxShadow();
        $boxShadowFields = $boxShadow->get_fields( [
            'suffix'          => '_wpt_cr_text',
            'tab_slug'        => 'custom_css',
            'toggle_slug'     => 'wpt_cr_content',
            'option_category' => 'layout',
            'show_if'         => [
            'wpt_cr_restrict_content' => 'on',
        ],
            'vb_support'      => false,
        ] );
        $fields += $boxShadowFields;
        return $fields;
    }
    
    /**
     * Get the types of restrictions.
     */
    public function get_restriction_types()
    {
        $types = [
            'logged_in_user' => 'Logged In User',
        ];
        return $types;
    }
    
    /**
     * Add toggles to the section
     */
    public function pre_process_modules( $parent_modules, $post_type )
    {
        
        if ( isset( $parent_modules['et_pb_section'] ) ) {
            $section = $parent_modules['et_pb_section'];
            $this->add_toggles( $section );
            unset( $section->fields_unprocessed['wpt_cr_restrict_content']['vb_support'] );
            unset( $section->fields_unprocessed['wpt_cr_content_access']['vb_support'] );
            unset( $section->fields_unprocessed['wpt_cr_access_denied_text']['vb_support'] );
            unset( $section->fields_unprocessed['wpt_cr_access_denied_text_font']['vb_support'] );
            unset( $section->fields_unprocessed['wpt_cr_access_denied_text_align']['vb_support'] );
            unset( $section->fields_unprocessed['wpt_cr_access_denied_text_font_color']['vb_support'] );
            unset( $section->fields_unprocessed['wpt_cr_access_denied_text_font_bg_color']['vb_support'] );
            unset( $section->fields_unprocessed['wpt_cr_access_denied_border_top']['vb_support'] );
            unset( $section->fields_unprocessed['wpt_cr_access_denied_border_right']['vb_support'] );
            unset( $section->fields_unprocessed['wpt_cr_access_denied_border_bottom']['vb_support'] );
            unset( $section->fields_unprocessed['wpt_cr_access_denied_border_left']['vb_support'] );
            unset( $section->fields_unprocessed['wpt_cr_access_denied_border_style']['vb_support'] );
            unset( $section->fields_unprocessed['wpt_cr_access_denied_border_color']['vb_support'] );
            unset( $section->fields_unprocessed['wpt_cr_access_denied_custom_margin']['vb_support'] );
            unset( $section->fields_unprocessed['wpt_cr_access_denied_custom_padding']['vb_support'] );
        }
        
        return $parent_modules;
    }
    
    /**
     * Add toggles
     */
    public function add_toggles( &$section )
    {
        $section->settings_modal_toggles['custom_css']['toggles']['wpt_cr_general'] = [
            'title'    => __( 'Restrict Content', 'divi-content-restrictor' ),
            'priority' => 999,
        ];
        $section->settings_modal_toggles['custom_css']['toggles']['wpt_cr_content'] = [
            'title'    => __( 'Un-Authorized Text', 'divi-content-restrictor' ),
            'priority' => 1000,
            'show_if'  => [
            'wpt_cr_restrict_content' => 'on',
        ],
        ];
    }
    
    public function modify_props(
        $props,
        $attrs,
        $render_slug,
        $_address,
        $content
    )
    {
        
        if ( !$this->container['divi_builder']->is_visual_builder_request() && isset( $props['wpt_cr_restrict_content'] ) && $props['wpt_cr_restrict_content'] == 'on' ) {
            if ( !isset( $props['module_class'] ) ) {
                $props['module_class'] = '';
            }
            $props['module_class'] = trim( sprintf( '%s wpt-restrict-content', $props['module_class'] ) );
        }
        
        return $props;
    }
    
    /**
     * Process content for section
     */
    public function process_content(
        $content,
        $props,
        $attrs,
        $render_slug,
        $_address,
        $global_content
    )
    {
        
        if ( !$this->container['divi_builder']->is_visual_builder_request() && isset( $props['wpt_cr_restrict_content'] ) && $props['wpt_cr_restrict_content'] == 'on' ) {
            $type = ( $props['wpt_cr_content_access'] ? $props['wpt_cr_content_access'] : 'logged_in_user' );
            if ( $type == 'logged_in_user' ) {
                return $this->container['restrictor_logged_in_user']->process_content( $content, $_address, $props );
            }
        }
        
        return $content;
    }
    
    public function start_row()
    {
        return '[et_pb_row _builder_version="4.7.6" _module_preset="default" custom_padding="0px|0px|0px|0px|false|false" custom_margin="0px||0px||false|false"][et_pb_column type="4_4" _builder_version="4.7.6" _module_preset="default"]';
    }
    
    public function end_row()
    {
        return '[/et_pb_column][/et_pb_row]';
    }
    
    /**
     * Get content restriction text.
     */
    public function get_content_restriction( $address, $props )
    {
        $styles = $this->get_output_css( $address, $props );
        return sprintf(
            '%s<div class="divi-cr-unauthorized-msg">%s</div>%s%s',
            $this->start_row(),
            ( $props['wpt_cr_access_denied_text'] ? html_entity_decode( $props['wpt_cr_access_denied_text'] ) : 'This content is restricted' ),
            $styles,
            $this->end_row()
        );
    }
    
    public function get_output_css( $address, $props )
    {
        $section_selector = '.et_pb_section_' . $address;
        $text_css_selector = sprintf( '%1$s .divi-cr-unauthorized-msg, %1$s .rcp_restricted, %1$s .mepr_error, %1$s .wc-memberships-content-restricted-message', $section_selector );
        $styles = sprintf( '%s {%s}', $text_css_selector, $this->get_text_styles( $text_css_selector, $props ) );
        return sprintf( '<style>%s</style>', $styles );
    }
    
    /**
     * Get text style
     */
    public function get_text_styles( $selector, $props )
    {
        $styles = $this->get_font_styles( $props['wpt_cr_access_denied_text_font'] );
        if ( isset( $props['wpt_cr_access_denied_text_align'] ) && $props['wpt_cr_access_denied_text_align'] ) {
            $styles[] = sprintf( 'text-align:%s !important;', $props['wpt_cr_access_denied_text_align'] );
        }
        if ( isset( $props['wpt_cr_access_denied_text_font_bg_color'] ) && $props['wpt_cr_access_denied_text_font_bg_color'] ) {
            $styles[] = sprintf( 'background:%s !important;', $props['wpt_cr_access_denied_text_font_bg_color'] );
        }
        if ( isset( $props['wpt_cr_access_denied_text_font_color'] ) && $props['wpt_cr_access_denied_text_font_color'] ) {
            $styles[] = sprintf( 'color:%s !important;', $props['wpt_cr_access_denied_text_font_color'] );
        }
        // border
        if ( isset( $props['wpt_cr_access_denied_border_top'] ) && $props['wpt_cr_access_denied_border_top'] ) {
            $styles[] = sprintf( 'border-top:%s !important;', $props['wpt_cr_access_denied_border_top'] );
        }
        if ( isset( $props['wpt_cr_access_denied_border_right'] ) && $props['wpt_cr_access_denied_border_right'] ) {
            $styles[] = sprintf( 'border-right:%s !important;', $props['wpt_cr_access_denied_border_right'] );
        }
        if ( isset( $props['wpt_cr_access_denied_border_bottom'] ) && $props['wpt_cr_access_denied_border_bottom'] ) {
            $styles[] = sprintf( 'border-bottom:%s !important;', $props['wpt_cr_access_denied_border_bottom'] );
        }
        if ( isset( $props['wpt_cr_access_denied_border_left'] ) && $props['wpt_cr_access_denied_border_left'] ) {
            $styles[] = sprintf( 'border-left:%s !important;', $props['wpt_cr_access_denied_border_left'] );
        }
        if ( isset( $props['wpt_cr_access_denied_border_style'] ) && $props['wpt_cr_access_denied_border_style'] ) {
            $styles[] = sprintf( 'border-style:%s !important;', $props['wpt_cr_access_denied_border_style'] );
        }
        if ( isset( $props['wpt_cr_access_denied_border_color'] ) && $props['wpt_cr_access_denied_border_color'] ) {
            $styles[] = sprintf( 'border-color:%s !important;', $props['wpt_cr_access_denied_border_color'] );
        }
        // margin and padding
        $styles[] = et_builder_get_element_style_css( $props['wpt_cr_access_denied_custom_margin'], 'margin', true );
        $styles[] = et_builder_get_element_style_css( $props['wpt_cr_access_denied_custom_padding'], 'padding', true );
        // box shadow
        $boxShadow = new BoxShadow();
        $styles[] = $boxShadow->get_value( $props, [
            'suffix'    => '_wpt_cr_text',
            'important' => true,
        ] );
        return implode( '', $styles );
    }
    
    /**
     * Font styles
     */
    public function get_font_styles( $font_style )
    {
        $styles = [];
        $parts = explode( '|', $font_style );
        
        if ( isset( $parts[0] ) && $parts[0] ) {
            et_gf_enqueue_fonts( [ $parts[0] ] );
            $google_fonts = et_get_google_fonts();
            $styles[] = sprintf( 'font-family: %s;', esc_html( $parts[0] ) );
        }
        
        if ( isset( $parts[1] ) && $parts[1] ) {
            $styles[] = sprintf( 'font-weight: %s;', $parts[1] );
        }
        if ( isset( $parts[2] ) && $parts[2] && $parts[2] == 'on' ) {
            $styles[] = 'font-style: oblique;';
        }
        if ( isset( $parts[3] ) && $parts[3] && $parts[3] == 'on' ) {
            $styles[] = 'text-transform: uppercase;';
        }
        if ( isset( $parts[4] ) && $parts[4] && $parts[4] == 'on' ) {
            $styles[] = 'text-decoration: underline;';
        }
        if ( isset( $parts[5] ) && $parts[5] && $parts[5] == 'on' ) {
            $styles[] = 'text-transform: capitalize;';
        }
        if ( isset( $parts[6] ) && $parts[6] && $parts[6] == 'on' ) {
            $styles[] = 'text-decoration: line-through;';
        }
        return $styles;
    }

}