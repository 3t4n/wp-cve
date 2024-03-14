<?php

/**
 * Header component.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */
namespace Stax;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Header extends ElementSpecs implements  ContainerInterface 
{
    public 
        $name,
        $slug,
        $type,
        $uuid,
        $icon,
        $template,
        $builder,
        $frontend,
        $auth,
        $visibilityDesktop,
        $visibilityTablet,
        $visibilityMobile,
        $customDesktop,
        $customTablet,
        $customMobile,
        $editor,
        $desktop,
        $tablet,
        $mobile,
        $generalCSS,
        $desktopCSS,
        $tabletCSS,
        $mobileCSS
    ;
    /**
     * Header constructor.
     */
    public function __construct()
    {
        $this->name = 'Header';
        $this->slug = 'header';
        $this->type = 'header';
        $this->uuid = '';
        $this->icon = (object) [
            'size'  => 'mdi-24px',
            'type'  => 'mdi-page-layout-header',
            'color' => 'sq-headerItem',
        ];
        $this->template = $this->getTemplate( $this->slug );
        $this->builder = '';
        $this->frontend = (object) [
            'desktop' => (object) [
            'auth'     => '',
            'not_auth' => '',
        ],
            'tablet'  => (object) [
            'auth'     => '',
            'not_auth' => '',
        ],
            'mobile'  => (object) [
            'auth'     => '',
            'not_auth' => '',
        ],
        ];
        $this->auth = [
            'logged_in'  => false,
            'logged_out' => false,
            'all'        => true,
        ];
        $this->visibilityDesktop = true;
        $this->visibilityTablet = true;
        $this->visibilityMobile = true;
        $this->customDesktop = false;
        $this->customTablet = true;
        $this->customMobile = true;
        $this->editor = [];
        $this->desktop = new \stdClass();
        $this->tablet = new \stdClass();
        $this->mobile = new \stdClass();
        $this->generalCSS = '';
        $this->desktopCSS = '';
        $this->tabletCSS = '';
        $this->mobileCSS = '';
        $this->editor();
        $this->advancedEditor();
    }
    
    /**
     * @param EditorSection $section
     */
    protected function addSection( EditorSection $section )
    {
        $this->editor[] = $section;
    }
    
    /**
     *
     */
    private function editor()
    {
        $section_1 = new EditorSection( [
            'title' => 'Options',
            'name'  => 'default-options',
        ] );
        $fields_1 = [];
        $fields_1[] = new EditorSectionField( [
            'label'   => 'Type',
            'name'    => 'type_field',
            'type'    => self::FIELD_DROPDOWN,
            'value'   => [ [
            'label'    => 'Default',
            'value'    => 'header-default',
            'selected' => true,
            'trigger'  => [
            'section' => [],
            'field'   => [ 'width_field' ],
        ],
        ], [
            'label'    => 'Boxed',
            'value'    => 'header-boxed',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [ 'width_field' ],
        ],
        ], [
            'label'    => 'Full width',
            'value'    => 'header-fullwidth',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ] ],
            'tooltip' => 'Choose from different container widths.',
        ] );
        $fields_1[] = new EditorSectionField( [
            'label'    => 'Width',
            'name'     => 'width_field',
            'type'     => self::FIELD_INPUT_NUMBER,
            'value'    => '1200',
            'units'    => [ [
            'type'   => 'px',
            'active' => true,
        ] ],
            'selector' => [
            '{{SELECTOR}} .header-content, {{SELECTOR}}.header-boxed' => 'max-width: {{VALUE}}{{UNIT}}',
        ],
        ] );
        $fields_1[] = new EditorSectionField( [
            'label'    => 'Height',
            'name'     => 'height_field',
            'type'     => self::FIELD_INPUT_NUMBER,
            'value'    => '80',
            'units'    => [ [
            'type'   => 'px',
            'active' => true,
        ] ],
            'selector' => [
            '{{SELECTOR}}'                    => 'height: {{VALUE}}{{UNIT}}',
            '.header-section{{SELECTOR}} img' => 'max-height: {{VALUE}}{{UNIT}}',
        ],
            'tooltip'  => 'Set header height.',
        ] );
        $fields_1[] = new EditorSectionField( [
            'label' => 'Background image',
            'name'  => 'bg_image_field',
            'type'  => self::FIELD_SWITCH,
            'value' => [ [
            'label'   => '',
            'value'   => '',
            'checked' => false,
            'trigger' => [
            'section' => [],
            'field'   => [
            'bg_image_upload_field',
            'bg_image_repeat_field',
            'bg_image_position_field',
            'bg_image_size_field'
        ],
        ],
        ] ],
        ] );
        $fields_1[] = new EditorSectionField( [
            'label'      => 'Image',
            'name'       => 'bg_image_upload_field',
            'visibility' => false,
            'type'       => self::FIELD_IMAGE,
            'value'      => '',
            'selector'   => [
            '{{SELECTOR}}' => 'background-image: url("{{VALUE}}")',
        ],
        ] );
        $fields_1[] = new EditorSectionField( [
            'label'      => 'Image repeat',
            'name'       => 'bg_image_repeat_field',
            'visibility' => false,
            'type'       => self::FIELD_DROPDOWN,
            'value'      => [
            [
            'label'    => 'No repeat',
            'value'    => 'no-repeat',
            'selected' => true,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => 'Repeat X & Y',
            'value'    => 'repeat',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => 'Repeat X',
            'value'    => 'repeat-x',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => 'Repeat Y',
            'value'    => 'repeat-y',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ]
        ],
            'selector'   => [
            '{{SELECTOR}}' => 'background-repeat: {{VALUE}}',
        ],
        ] );
        $fields_1[] = new EditorSectionField( [
            'label'      => 'Image position',
            'name'       => 'bg_image_position_field',
            'visibility' => false,
            'type'       => self::FIELD_DROPDOWN,
            'value'      => [
            [
            'label'    => 'Custom',
            'value'    => '',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [ 'bg_image_position_custom_field' ],
        ],
        ],
            [
            'label'    => 'Center top',
            'value'    => 'center top',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => 'Center center',
            'value'    => 'center center',
            'selected' => true,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => 'Center bottom',
            'value'    => 'center bottom',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => 'Left top',
            'value'    => 'left top',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => 'Left center',
            'value'    => 'left center',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => 'Left bottom',
            'value'    => 'left bottom',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => 'Right top',
            'value'    => 'right top',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => 'Right center',
            'value'    => 'right center',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => 'Right bottom',
            'value'    => 'right bottom',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ]
        ],
            'selector'   => [
            '{{SELECTOR}}' => 'background-position: {{VALUE}}',
        ],
        ] );
        $fields_1[] = new EditorSectionField( [
            'label'      => 'Left / Top offset',
            'name'       => 'bg_image_position_custom_field',
            'visibility' => false,
            'type'       => self::FIELD_DOUBLE_INPUT,
            'value'      => [ [
            'position' => 'Left',
            'value'    => '0',
        ], [
            'position' => 'Top',
            'value'    => '0',
        ] ],
            'units'      => [ [
            'type'   => 'px',
            'active' => true,
        ], [
            'type'   => '%',
            'active' => false,
        ] ],
            'selector'   => [
            '{{SELECTOR}}' => 'background-position: {{VALUE_1}}{{UNIT}} {{VALUE_2}}{{UNIT}}',
        ],
        ] );
        $fields_1[] = new EditorSectionField( [
            'label'      => 'Image size',
            'name'       => 'bg_image_size_field',
            'visibility' => false,
            'type'       => self::FIELD_DROPDOWN,
            'value'      => [
            [
            'label'    => 'Custom',
            'value'    => '',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [ 'bg_image_custom_size_field' ],
        ],
        ],
            [
            'label'    => 'Auto',
            'value'    => 'auto',
            'selected' => true,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => 'Cover',
            'value'    => 'cover',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => 'Contain',
            'value'    => 'contain',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ]
        ],
            'selector'   => [
            '{{SELECTOR}}' => 'background-size: {{VALUE}}',
        ],
        ] );
        $fields_1[] = new EditorSectionField( [
            'label'      => 'Custom size',
            'name'       => 'bg_image_custom_size_field',
            'visibility' => false,
            'type'       => self::FIELD_INPUT_NUMBER,
            'value'      => '',
            'units'      => [ [
            'type'   => 'px',
            'active' => true,
        ], [
            'type'   => '%',
            'active' => false,
        ] ],
            'selector'   => [
            '{{SELECTOR}}' => 'background-size: {{VALUE}}{{UNIT}}',
        ],
        ] );
        $section_2 = new EditorSection( [
            'title' => 'Sticky Header',
            'name'  => 'sticky-options',
        ] );
        $fields_2 = [];
        $fields_2[] = new EditorSectionField( [
            'label' => 'Enable Sticky',
            'name'  => 'sticky_field',
            'type'  => self::FIELD_SWITCH,
            'value' => [ [
            'label'   => '',
            'value'   => 'header-sticky',
            'checked' => false,
            'trigger' => [
            'section' => [],
            'field'   => [
            'sticky_bg',
            'resize_separator',
            'resize_field',
            'resize_field_go_pro',
            'slide_separator',
            'slide_up_field',
            'slide_up_field_go_pro',
            'transparent_separator',
            'transparent_field',
            'transparent_field_go_pro',
            'shadow_separator',
            'shadow_sticky_field'
        ],
        ],
        ] ],
        ] );
        $fields_2[] = new EditorSectionField( [
            'label'      => 'Sticky Background',
            'name'       => 'sticky_bg',
            'visibility' => false,
            'type'       => self::FIELD_COLOR_PICKER,
            'value'      => '',
            'selector'   => [
            '{{SELECTOR}}.header-sticky.is-sticky' => 'background-color: {{VALUE}}',
        ],
        ] );
        $fields_2[] = new EditorSectionField( [
            'label'       => 'Shadow',
            'name'        => 'shadow_separator',
            'visibility'  => false,
            'type'        => self::FIELD_SEPARATOR,
            'value'       => '',
            'editorClass' => [ 'padding-m' ],
        ] );
        $fields_2[] = new EditorSectionField( [
            'label'      => '',
            'name'       => 'shadow_sticky_field',
            'visibility' => false,
            'type'       => self::FIELD_SHADOW,
            'value'      => [
            [
            'position' => 'Color',
            'value'    => '',
        ],
            [
            'position' => 'X',
            'value'    => '',
        ],
            [
            'position' => 'Y',
            'value'    => '',
        ],
            [
            'position' => 'Blur',
            'value'    => '',
        ],
            [
            'position' => 'Spread',
            'value'    => '',
        ]
        ],
            'selector'   => [
            '{{SELECTOR}}.is-sticky:not(.is-transparent)' => [ '-webkit-box-shadow: {{X}}{{UNIT}} {{Y}}{{UNIT}} {{BLUR}}{{UNIT}} {{SPREAD}}{{UNIT}} {{COLOR}}', '-moz-box-shadow: {{X}}{{UNIT}} {{Y}}{{UNIT}} {{BLUR}}{{UNIT}} {{SPREAD}}{{UNIT}} {{COLOR}}', 'box-shadow: {{X}}{{UNIT}} {{Y}}{{UNIT}} {{BLUR}}{{UNIT}} {{SPREAD}}{{UNIT}} {{COLOR}}' ],
        ],
        ] );
        $fields_2[] = new EditorSectionField( [
            'label'       => '',
            'name'        => 'resize_separator',
            'visibility'  => false,
            'type'        => self::FIELD_SEPARATOR,
            'value'       => '',
            'editorClass' => [ 'padding-xs' ],
        ] );
        if ( stax_fs()->is_not_paying() || !stax_fs()->is_premium() ) {
            $fields_2[] = new EditorSectionField( [
                'label' => 'Enable Resize',
                'name'  => 'resize_field_go_pro',
                'type'  => self::FIELD_GO_PRO,
                'value' => '',
            ] );
        }
        $fields_2[] = new EditorSectionField( [
            'label'       => '',
            'name'        => 'slide_separator',
            'visibility'  => false,
            'type'        => self::FIELD_SEPARATOR,
            'value'       => '',
            'editorClass' => [ 'padding-xs' ],
        ] );
        if ( stax_fs()->is_not_paying() || !stax_fs()->is_premium() ) {
            $fields_2[] = new EditorSectionField( [
                'label'      => 'Enable Hide & Reveal',
                'name'       => 'slide_up_field_go_pro',
                'visibility' => true,
                'type'       => self::FIELD_GO_PRO,
                'value'      => '',
            ] );
        }
        $fields_2[] = new EditorSectionField( [
            'label'       => '',
            'name'        => 'transparent_separator',
            'visibility'  => false,
            'type'        => self::FIELD_SEPARATOR,
            'value'       => '',
            'editorClass' => [ 'padding-xs' ],
        ] );
        if ( stax_fs()->is_not_paying() || !stax_fs()->is_premium() ) {
            $fields_2[] = new EditorSectionField( [
                'label' => esc_html__( 'Enable Transparent', 'stax' ),
                'name'  => 'transparent_field_go_pro',
                'type'  => self::FIELD_GO_PRO,
                'value' => '',
            ] );
        }
        foreach ( $fields_1 as $field ) {
            $section_1->addField( $field );
        }
        foreach ( $fields_2 as $field ) {
            $section_2->addField( $field );
        }
        $this->editor[] = $section_1;
        $this->editor[] = $section_2;
    }
    
    /**
     *
     */
    private function advancedEditor()
    {
        $section_1 = new EditorSection( [
            'title' => 'Layout',
            'name'  => 'layout-advanced',
        ] );
        $fields_1 = [];
        $fields_1[] = new EditorSectionField( [
            'label'    => 'Padding',
            'name'     => self::ADVANCED_PADDING,
            'type'     => self::FIELD_SPACING,
            'value'    => [
            [
            'position' => 'Top',
            'value'    => '',
        ],
            [
            'position' => 'Right',
            'value'    => '',
        ],
            [
            'position' => 'Bottom',
            'value'    => '',
        ],
            [
            'position' => 'Left',
            'value'    => '',
        ]
        ],
            'units'    => [ [
            'type'   => 'px',
            'active' => true,
        ], [
            'type'   => 'em',
            'active' => false,
        ], [
            'type'   => '%',
            'active' => false,
        ] ],
            'selector' => [
            '{{SELECTOR}}' => [
            'padding-top: {{VALUE_1}}{{UNIT}} !important',
            'padding-right: {{VALUE_2}}{{UNIT}} !important',
            'padding-bottom: {{VALUE_3}}{{UNIT}} !important',
            'padding-left: {{VALUE_4}}{{UNIT}} !important'
        ],
        ],
        ] );
        $fields_1[] = new EditorSectionField( [
            'label'    => 'Margin',
            'name'     => self::ADVANCED_MARGIN,
            'type'     => self::FIELD_SPACING,
            'value'    => [
            [
            'position' => 'Top',
            'value'    => '',
        ],
            [
            'position' => 'Right',
            'value'    => '',
        ],
            [
            'position' => 'Bottom',
            'value'    => '',
        ],
            [
            'position' => 'Left',
            'value'    => '',
        ]
        ],
            'units'    => [ [
            'type'   => 'px',
            'active' => true,
        ], [
            'type'   => 'em',
            'active' => false,
        ], [
            'type'   => '%',
            'active' => false,
        ] ],
            'selector' => [
            '{{SELECTOR}}' => [
            'margin-top: {{VALUE_1}}{{UNIT}} !important',
            'margin-right: {{VALUE_2}}{{UNIT}} !important',
            'margin-bottom: {{VALUE_3}}{{UNIT}} !important',
            'margin-left: {{VALUE_4}}{{UNIT}} !important'
        ],
        ],
        ] );
        $fields_1[] = new EditorSectionField( [
            'label'    => 'Border radius',
            'name'     => self::ADVANCED_BORDER_RADIUS,
            'type'     => self::FIELD_BORDER_RADIUS,
            'value'    => [
            [
            'value'    => '',
            'position' => 'Top left',
        ],
            [
            'value'    => '',
            'position' => 'Top right',
        ],
            [
            'value'    => '',
            'position' => 'Bottom right',
        ],
            [
            'value'    => '',
            'position' => 'Bottom left',
        ]
        ],
            'units'    => [ [
            'type'   => 'px',
            'active' => true,
        ], [
            'type'   => 'em',
            'active' => false,
        ], [
            'type'   => '%',
            'active' => false,
        ] ],
            'selector' => [
            '{{SELECTOR}}' => [
            'border-top-left-radius: {{VALUE_1}}{{UNIT}}',
            'border-top-right-radius: {{VALUE_2}}{{UNIT}}',
            'border-bottom-right-radius: {{VALUE_3}}{{UNIT}}',
            'border-bottom-left-radius: {{VALUE_4}}{{UNIT}}'
        ],
        ],
        ] );
        $section_2 = new EditorSection( [
            'title' => 'Style',
            'name'  => 'normal-style-advanced',
        ] );
        $fields_2 = [];
        $fields_2[] = new EditorSectionField( [
            'label'    => 'Background',
            'name'     => self::ADVANCED_BG_NORMAL,
            'type'     => self::FIELD_COLOR_PICKER,
            'value'    => 'rgb(255, 255, 255)',
            'selector' => [
            '{{SELECTOR}}' => 'background-color: {{VALUE}}',
        ],
        ] );
        $fields_2[] = new EditorSectionField( [
            'label'    => 'Border type',
            'name'     => self::ADVANCED_BORDER_TYPE_NORMAL,
            'type'     => self::FIELD_DROPDOWN,
            'value'    => [
            [
            'label'    => 'None',
            'value'    => '',
            'selected' => true,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => 'Solid',
            'value'    => 'solid',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [ self::ADVANCED_BORDER_WIDTH_NORMAL, self::ADVANCED_BORDER_COLOR_NORMAL ],
        ],
        ],
            [
            'label'    => 'Double',
            'value'    => 'double',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [ self::ADVANCED_BORDER_WIDTH_NORMAL, self::ADVANCED_BORDER_COLOR_NORMAL ],
        ],
        ],
            [
            'label'    => 'Dotted',
            'value'    => 'dotted',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [ self::ADVANCED_BORDER_WIDTH_NORMAL, self::ADVANCED_BORDER_COLOR_NORMAL ],
        ],
        ],
            [
            'label'    => 'Dashed',
            'value'    => 'dashed',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [ self::ADVANCED_BORDER_WIDTH_NORMAL, self::ADVANCED_BORDER_COLOR_NORMAL ],
        ],
        ]
        ],
            'selector' => [
            '{{SELECTOR}}' => 'border-style: {{VALUE}} !important',
        ],
        ] );
        $fields_2[] = new EditorSectionField( [
            'label'      => 'Border width',
            'name'       => self::ADVANCED_BORDER_WIDTH_NORMAL,
            'visibility' => false,
            'type'       => self::FIELD_SPACING,
            'value'      => [
            [
            'position' => 'Top',
            'value'    => '0',
        ],
            [
            'position' => 'Right',
            'value'    => '0',
        ],
            [
            'position' => 'Bottom',
            'value'    => '1',
        ],
            [
            'position' => 'Left',
            'value'    => '0',
        ]
        ],
            'units'      => [ [
            'type'   => 'px',
            'active' => true,
        ] ],
            'selector'   => [
            '{{SELECTOR}}' => [
            'border-top-width: {{VALUE_1}}{{UNIT}} !important',
            'border-right-width: {{VALUE_2}}{{UNIT}} !important',
            'border-bottom-width: {{VALUE_3}}{{UNIT}} !important',
            'border-left-width: {{VALUE_4}}{{UNIT}} !important'
        ],
        ],
        ] );
        $fields_2[] = new EditorSectionField( [
            'label'      => 'Border color',
            'name'       => self::ADVANCED_BORDER_COLOR_NORMAL,
            'visibility' => false,
            'type'       => self::FIELD_COLOR_PICKER,
            'value'      => '',
            'selector'   => [
            '{{SELECTOR}}' => 'border-color: {{VALUE}}',
        ],
        ] );
        $fields_2[] = new EditorSectionField( [
            'label'    => 'Shadow',
            'name'     => self::ADVANCED_SHADOW,
            'type'     => self::FIELD_SHADOW,
            'value'    => [
            [
            'position' => 'Color',
            'value'    => '',
        ],
            [
            'position' => 'X',
            'value'    => '',
        ],
            [
            'position' => 'Y',
            'value'    => '',
        ],
            [
            'position' => 'Blur',
            'value'    => '',
        ],
            [
            'position' => 'Spread',
            'value'    => '',
        ]
        ],
            'selector' => [
            '{{SELECTOR}}' => [ '-webkit-box-shadow: {{X}}{{UNIT}} {{Y}}{{UNIT}} {{BLUR}}{{UNIT}} {{SPREAD}}{{UNIT}} {{COLOR}}', '-moz-box-shadow: {{X}}{{UNIT}} {{Y}}{{UNIT}} {{BLUR}}{{UNIT}} {{SPREAD}}{{UNIT}} {{COLOR}}', 'box-shadow: {{X}}{{UNIT}} {{Y}}{{UNIT}} {{BLUR}}{{UNIT}} {{SPREAD}}{{UNIT}} {{COLOR}}' ],
        ],
        ] );
        $section_4 = new EditorSection( [
            'title' => 'Customization',
            'name'  => 'customization-advanced',
        ] );
        $fields_4 = [];
        $fields_4[] = new EditorSectionField( [
            'label' => 'ID',
            'name'  => self::ADVANCED_CSS_ID,
            'type'  => self::FIELD_INPUT_TEXT,
            'value' => '',
        ] );
        $fields_4[] = new EditorSectionField( [
            'label' => 'Class',
            'name'  => self::ADVANCED_CSS_CLASS,
            'type'  => self::FIELD_INPUT_TEXT,
            'value' => '',
        ] );
        foreach ( $fields_1 as $field ) {
            $section_1->addField( $field );
        }
        foreach ( $fields_2 as $field ) {
            $section_2->addField( $field );
        }
        foreach ( $fields_4 as $field ) {
            $section_4->addField( $field );
        }
        $this->editor['tab_advanced_layout'] = $section_1;
        $this->editor['tab_advanced_normal_style'] = $section_2;
        $this->editor['tab_advanced_customization'] = $section_4;
    }

}