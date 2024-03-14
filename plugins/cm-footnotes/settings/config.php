<?php

namespace com\cminds\footnotes\settings;

use CMF_Free;

const TYPE_BOOL = 'bool';
const TYPE_INT = 'int';
const TYPE_STRING = 'string';
const TYPE_TEXTAREA = 'textarea';
const TYPE_RICH_TEXT = 'rich_text';
const TYPE_RADIO = 'radio';
const TYPE_SELECT = 'select';
const TYPE_MULTISELECT = 'multiselect';
const TYPE_MULTICHECKBOX = 'multicheckbox';
const TYPE_LABEL = 'label';
const TYPE_CUSTOM = 'custom';
const TYPE_COLOR = 'color';

$config = [
    'abbrev'   => 'cmf',
    'tabs'     => [
        0 => [
            'tab_name' => 'General Settings',
            'section'  => [
                0 => 'Footnotes display options',
                1 => 'Footnotes content links styling',
                2 => 'Footnotes bottom styling',
                3 => 'Tooltips display style',
            ]
        ],
    ],
    'value'    => [
    ],
    'settings' => [
        'cmf_footnoteOnPosttypes'                => [
            'type'        => TYPE_MULTISELECT,
            'value'       => ['post'],
            'tab'         => 0,
            'section'     => 0,
            'options'     => \CMF_Free::outputCustomPostTypesList(1),
            'label'       => __('Display footnotes on', 'cmf'),
            'description' => __('Select the custom post types where you\'d like the Footnote Terms to be highlighted.', 'cmf'),
        ],
        'cmf_footnoteInOneLine'                  => [
            'type'        => TYPE_BOOL,
            'value'       => 1,
            'tab'         => 0,
            'section'     => 0,
            'label'       => __('Show each bottom footnote on separate line', 'cmf'),
            'description' => __('Select if you want to display each bottom footnote on a separate line.', 'cmf'),
        ],
        'cmf_footnoteOpenLinkInNewTab'           => [
            'type'        => TYPE_BOOL,
            'value'       => 0,
            'tab'         => 0,
            'section'     => 0,
            'label'       => __('Open external link in the new tab', 'cmf'),
            'description' => __('Select if you want to open footnote\'s external link in the new tab.', 'cmf'),
            'onlyin'      => 'Pro'
        ],
        'cmf_footnoteSymbol_separator'           => [
            'type'        => TYPE_CUSTOM,
            'value'       => '',
            'tab'         => 0,
            'section'     => 1,
            'html'        => '',
            'label'       => __('Footnote link symbol:', 'cmf'),
            'description' => __('Set the styles of footnote link symbol - color, font-size and font-style.', 'cmf'),
        ],
        'cmf_footnoteSymbolSize'                 => [
            'type'        => TYPE_STRING,
            'value'       => '',
            'tab'         => 0,
            'section'     => 1,
            'options'     => ['session' => 'Duration of session', 'view' => 'Single view'],
            'label'       => __('Size', 'cmf'),
            'description' => __('', 'cmf'),
        ],
        'cmf_footnoteSymbolColor'                => [
            'type'        => TYPE_COLOR,
            'value'       => '#ff990a',
            'tab'         => 0,
            'section'     => 1,
            'label'       => __('Color', 'cmf'),
            'description' => __('', 'cmf'),
        ],
        'cmf_footnoteFormat'                     => [
            'type'        => TYPE_SELECT,
            'value'       => 'none',
            'tab'         => 0,
            'section'     => 1,
            'options'     => ['none' => 'None', 'bold' => 'Bold', 'italic' => 'Italic'],
            'label'       => __('Font Style', 'cmf'),
            'description' => __('', 'cmf'),
        ],
        'cmf_footnoteAestheticsType_separator'   => [
            'type'        => TYPE_CUSTOM,
            'value'       => '',
            'tab'         => 0,
            'section'     => 1,
            'html'        => '',
            'label'       => __('Display style :', 'cmf'),
            'description' => __('How the reference link is displayed in the Front-End', 'cmf'),
            'onlyin'      => 'Pro'
        ],
        'cmf_footnoteAestheticsType'             => [
            'type'        => TYPE_SELECT,
            'value'       => 'type1',
            'tab'         => 0,
            'section'     => 1,
            'options'     => ['type1' => 'Square brackets', 'type2' => 'Curly brackets', 'type3' => 'Rectangle', 'type4' => 'Message bubble'],
            'label'       => __('Format', 'cmf'),
            'description' => __('', 'cmf'),
            'onlyin'      => 'Pro'
        ],
        'cmf_footnoteSymbolLinkAnchor_separator' => [
            'type'        => TYPE_CUSTOM,
            'value'       => '',
            'tab'         => 0,
            'section'     => 2,
            'html'        => '',
            'label'       => __('Footnote link anchor style:', 'cmf'),
            'description' => __('Set the style of footnote link anchor', 'cmf'),
        ],
        'cmf_footnoteSymbolLinkAnchorSize'       => [
            'type'        => TYPE_STRING,
            'value'       => '',
            'tab'         => 0,
            'section'     => 2,
            'label'       => __('Size', 'cmf'),
            'description' => __('', 'cmf'),
        ],
        'cmf_footnoteSymbolLinkAnchorColor'      => [
            'type'        => TYPE_COLOR,
            'value'       => '#000000',
            'tab'         => 0,
            'section'     => 2,
            'label'       => __('Color', 'cmf'),
            'description' => __('', 'cmf'),
        ],
        'cmf_footnoteDescription_separator'      => [
            'type'        => TYPE_CUSTOM,
            'value'       => '',
            'tab'         => 0,
            'section'     => 2,
            'html'        => '',
            'label'       => __('Footnote description style:', 'cmf'),
            'description' => __('Set the style of footnote description', 'cmf'),
        ],
        'cmf_footnoteDescriptionSize'            => [
            'type'        => TYPE_STRING,
            'value'       => '',
            'tab'         => 0,
            'section'     => 2,
            'label'       => __('Size', 'cmf'),
            'description' => __('', 'cmf'),
            'onlyin'      => 'Pro'
        ],
        'cmf_footnoteDescriptionColor'           => [
            'type'        => TYPE_COLOR,
            'value'       => '#000000',
            'tab'         => 0,
            'section'     => 2,
            'label'       => __('Color', 'cmf'),
            'description' => __('', 'cmf'),
            'onlyin'      => 'Pro'
        ],
        'cmf_footnoteDescriptionWeight'          => [
            'type'        => TYPE_SELECT,
            'value'       => 'normal',
            'tab'         => 0,
            'section'     => 2,
            'options'     => ['normal' => 'Normal', 'bold' => 'Bold', 'lighter' => 'Lighter', 'bolder' => 'Bolder'],
            'label'       => __('Weight', 'cmf'),
            'description' => __('', 'cmf'),
            'onlyin'      => 'Pro'
        ],
        'cmf_footnoteDescriptionDecoration'      => [
            'type'        => TYPE_SELECT,
            'value'       => 'none',
            'tab'         => 0,
            'section'     => 2,
            'options'     => ['none' => 'None', 'underline' => 'Underline', 'overline' => 'Overline', 'line-through' => 'Line-through'],
            'label'       => __('Decoration', 'cmf'),
            'description' => __('', 'cmf'),
            'onlyin'      => 'Pro'
        ],
        'cmf_footnoteDescriptionStyle'           => [
            'type'        => TYPE_SELECT,
            'value'       => 'normal',
            'tab'         => 0,
            'section'     => 2,
            'options'     => ['normal' => 'Normal', 'italic' => 'Italic'],
            'label'       => __('Style', 'cmf'),
            'description' => __('', 'cmf'),
            'onlyin'      => 'Pro'
        ],
        'cmf_footnoteDescriptionAlignment'       => [
            'type'        => TYPE_SELECT,
            'value'       => 'left',
            'tab'         => 0,
            'section'     => 2,
            'options'     => ['left' => 'Left', 'right' => 'Right', 'justify' => 'Justify'],
            'label'       => __('Alignment', 'cmf'),
            'description' => __('', 'cmf'),
            'onlyin'      => 'Pro'
        ],
        'cmf_footnotedesgnsep'                   => [
            'type'        => TYPE_BOOL,
            'value'       => 0,
            'tab'         => 0,
            'section'     => 2,
            'label'       => __('Include a separator before the footnotes?', 'cmf'),
            'description' => __('Select this option if you want display a custom separator between footnote and content.', 'cmf'),
            'onlyin'      => 'Pro'
        ],
        'cmf_footnotesep_separator'              => [
            'type'        => TYPE_CUSTOM,
            'value'       => '',
            'tab'         => 0,
            'section'     => 2,
            'html'        => '',
            'label'       => __('Separator style', 'cmf'),
            'description' => __('Select this options to display a custom separator styles.', 'cmf'),
            'onlyin'      => 'Pro'
        ],
        'cmf_footnoteseptitle'                   => [
            'type'        => TYPE_STRING,
            'value'       => '',
            'tab'         => 0,
            'section'     => 2,
            'label'       => __('Title', 'cmf'),
            'description' => __('', 'cmf'),
            'onlyin'      => 'Pro'
        ],
        'cmf_footnotesepsize'                    => [
            'type'        => TYPE_SELECT,
            'value'       => 'small',
            'tab'         => 0,
            'section'     => 2,
            'options'     => ['small' => 'Small (25% width)', 'medium' => 'Medium (50% width)', 'large' => 'Large (75% width)', 'full' => 'Full (100% width)'],
            'label'       => __('Line width', 'cmf'),
            'description' => __('', 'cmf'),
            'onlyin'      => 'Pro'
        ],
        'cmf_footnotesepthickness'               => [
            'type'        => TYPE_SELECT,
            'value'       => 'thin',
            'tab'         => 0,
            'section'     => 2,
            'options'     => ['thin' => 'Thin', 'medium' => 'Medium', 'thick' => 'Thick'],
            'label'       => __('Line thickness', 'cmf'),
            'description' => __('', 'cmf'),
            'onlyin'      => 'Pro'
        ],
        'cmf_footnotesepstyle'                   => [
            'type'        => TYPE_SELECT,
            'value'       => 'solid',
            'tab'         => 0,
            'section'     => 2,
            'options'     => ['solid' => 'Solid', 'dotted' => 'Dotted', 'dashed' => 'Dashed'],
            'label'       => __('Line style', 'cmf'),
            'description' => __('', 'cmf'),
            'onlyin'      => 'Pro'
        ],
        'cmf_footnotetooltip'                    => [
            'type'        => TYPE_BOOL,
            'value'       => 0,
            'tab'         => 0,
            'section'     => 3,
            'label'       => __('Display Footnotes as Tooltips', 'cmf'),
            'description' => __('Displays a tooltip with the footnote content.', 'cmf'),
            'onlyin'      => 'Pro'
        ],
        'cmf_footnoteTooltip_separator'          => [
            'type'        => TYPE_CUSTOM,
            'value'       => '',
            'tab'         => 0,
            'section'     => 3,
            'html'        => '',
            'label'       => __('Tooltip display style', 'cmf'),
            'description' => __('Choose a tooltip style settings.', 'cmf'),
            'onlyin'      => 'Pro'
        ],
        'cmf_footnoteTooltipBackColor'           => [
            'type'        => TYPE_COLOR,
            'value'       => '#d29d9d',
            'tab'         => 0,
            'section'     => 3,
            'label'       => __('Background color', 'cmf'),
            'description' => __('', 'cmf'),
            'onlyin'      => 'Pro'
        ],
        'cmf_footnoteTooltipDescColor'           => [
            'type'        => TYPE_COLOR,
            'value'       => '#b30000',
            'tab'         => 0,
            'section'     => 3,
            'label'       => __('Description color', 'cmf'),
            'description' => __('', 'cmf'),
            'onlyin'      => 'Pro'
        ],
        'cmf_footnoteTooltipExtLinkColor'        => [
            'type'        => TYPE_COLOR,
            'value'       => '#5b2f6f',
            'tab'         => 0,
            'section'     => 3,
            'label'       => __('External link color', 'cmf'),
            'description' => __('', 'cmf'),
            'onlyin'      => 'Pro'
        ],
    ],
    'presets'  => [
        'default' => [
            0 => [
                'generic' => [
                    'label'    => '',
                    'before'   => '',
                    'settings' => [
                    ]
                ],
            ],
        ]
    ]
];
