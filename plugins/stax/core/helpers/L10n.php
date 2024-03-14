<?php

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class L10n {
    /**
     * @var null
     */
    public static $instance = null;

    /**
     * @return null|L10n
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return object
     */
    public function strings() {
        return (object) [
            'auth'        => [
                'labelBoth'    => __( 'Everyone', 'stax' ),
                'labelAuth'    => __( 'Auth', 'stax' ),
                'labelNotAuth' => __( 'Not auth', 'stax' )
            ],
            'color'       => [
                'save'   => __( 'Add color', 'stax' ),
                'preset' => __( 'Preset colors', 'stax' )
            ],
            'exit'        => [
                'confirmation' => esc_html__( 'There are unsaved changes. Do you want to proceed?', 'stax' )
            ],
            'save'        => [
                'changesDetected' => __( 'You made changes! Don\'t forget to', 'stax' ),
                'save'            => esc_html__( 'save', 'stax' ),
                'success'         => esc_html__( 'Great, all settings are saved now', 'stax' )
            ],
            'frame'       => [
                'loading'       => esc_html__( 'LOADING', 'stax' ),
                'closeEditor'   => esc_html__( 'Close editor', 'stax' ),
                'zonesManager'  => esc_html__( 'Zones Manager', 'stax' ),
                'desktopView'   => esc_html__( 'Desktop View', 'stax' ),
                'tabletView'    => esc_html__( 'Tablet View', 'stax' ),
                'mobileView'    => esc_html__( 'Mobile View', 'stax' ),
                'templatesView' => esc_html__( 'Templates', 'stax' ),
                'layersView'    => esc_html__( 'Layers', 'stax' ),
                'deletedView'   => esc_html__( 'Deleted Items', 'stax' ),
                'settingsView'  => esc_html__( 'Settings', 'stax' ),
                'docs'          => esc_html__( 'Documentation', 'stax' ),
                'enableRender'  => esc_html__( 'Turn ON Stax', 'stax' ),
                'disableRender' => esc_html__( 'Turn OFF Stax', 'stax' ),
                'saveSuccess'   => esc_html__( 'All settings are saved!', 'stax' ),
                'saveFailed'    => esc_html__( 'Settings are NOT saved!', 'stax' )
            ],
            'panel'       => [
                'titleStart'            => esc_html__( 'Editing ', 'stax' ),
                'titleLayers'           => esc_html__( 'Layers', 'stax' ),
                'titleTemplates'        => esc_html__( 'Templates', 'stax' ),
                'titleZones'            => __( 'Zones', 'stax' ),
                'titleOrderZones'       => __( 'Order zones', 'stax' ),
                'titleDefaultZones'     => __( 'Default zones', 'stax' ),
                'titleZonesCurrent'     => __( 'Zones on this page', 'stax' ),
                'titleAddZone'          => __( 'Add zones', 'stax' ),
                'titleZonesOther'       => __( 'Zones on other pages', 'stax' ),
                'titleSaveTemplates'    => __( 'Save current zone as template', 'stax' ),
                'titleSavedTemplates'   => __( 'Saved templates', 'stax' ),
                'titleDefaultTemplates' => __( 'Default templates', 'stax' ),
                'titleDeletedElements'  => __( 'Deleted Elements', 'stax' ),
                'titleLayoutElements'   => __( 'Layout Elements', 'stax' ),
                'titleContentElements'  => __( 'Content Elements', 'stax' ),
                'titleSavedElements'    => __( 'Saved elements', 'stax' ),
                'titleSettings'         => __( 'Settings', 'stax' ),
                'titleSettingsTheme'    => __( 'Editor theme', 'stax' ),
                'titleSettingsColors'   => __( 'Preset colors', 'stax' ),
                'settingsColorsInfo'    => __( 'Useful preset colors that you can use them on the entire builder.', 'stax' ),
                'selectRenderArea'      => __( 'SELECT RENDER AREA', 'stax' ),
                'selectTheme'           => __( 'Select theme', 'stax' ),
                'settingsExport'        => __( 'Export zones', 'stax' ),
                'settingsImport'        => __( 'Import zones', 'stax' ),
                'saveAsTemplate'        => __( 'Save zones as template', 'stax' ),
                'saveAsComponent'       => __( 'Save element as template', 'stax' ),
                'searchNoTemplates'     => __( 'No templates found.', 'stax' ),
                'searchNoElements'      => __( 'No elements found. Please try again.', 'stax' ),
                'resolutionSettings'    => __( 'Apply settings for %s only', 'stax' ),
                'resolutionTooltip'     => __( 'When enabled, below settings are applied only for current resolution. If disabled, your settings will be as on Desktop', 'stax' ),
                'saveTemplateInfo'      => __( 'You can use this layout later if you save it as template.', 'stax' ),
                'saveTemplateSingle'    => __( 'You can save this %s for later use.', 'stax' ),
                'settingsExportInfo'    => __( 'Export zones from this page to file.', 'stax' ),
                'settingsImportInfo'    => __( 'Import zones from file.', 'stax' ),
                'actionExport'          => __( 'Export', 'stax' ),
                'actionImport'          => __( 'Import', 'stax' )
            ],
            'modal'       => [
                'base'  => [
                    'yes'                  => __( 'Yes', 'stax' ),
                    'no'                   => __( 'No', 'stax' ),
                    'save'                 => __( 'Save', 'stax' ),
                    'cancel'               => __( 'Cancel', 'stax' ),
                    'continueConfirmation' => __( 'Are you sure you want to continue?', 'stax' ),
                    'add'                  => __( 'Add', 'stax' )
                ],
                'frame' => [
                    'deleteElement'   => __( 'Are you sure you want to move this element to trash?', 'stax' ),
                    'deleteColumn'    => __( 'Are you sure you want to delete this column and all of it\'s content?', 'stax' ),
                    'deleteContainer' => __( 'Are you sure you want to delete this container and all of it\'s content?', 'stax' )
                ],
                'panel' => [
                    'individualSettings' => [
                        'singleTitle' => __( 'You are switching to individual settings. Don\'t worry, you can reset to general anytime you want.', 'stax' ),
                        'masterTitle' => __( 'You are switching to Desktop settings. Your specific changes for this resolution will be lost.', 'stax' )
                    ],
                    'template'           => [
                        'saveTitle'   => __( 'You are about to save this item as template, but first you have to name your template.', 'stax' ),
                        'useTitle'    => __( 'You are about to append this template to your current zone.', 'stax' ),
                        'deleteTitle' => __( 'This template will be deleted.', 'stax' )
                    ],
                    'element'            => [
                        'deleteTitle' => __( 'This saved element will be deleted.', 'stax' )
                    ],
                    'render'             => [
                        'renderNewTitle'    => __( 'Turning on Stax', 'stax' ),
                        'renderOldTitle'    => __( 'Turning off Stax', 'stax' ),
                        'renderNewSubtitle' => __( 'Your Stax content will be rendered in frontend.', 'stax' ),
                        'renderOldSubtitle' => __( 'Your Stax content won\'t be rendered in frontend. Your website will be back to its original layout.', 'stax' )
                    ],
                    'goPro'              => [
                        'getMore'   => __( 'Get More with', 'stax' ),
                        'title'     => __( 'Instant access to all premium features and new releases!', 'stax' ),
                        'subtitle'  => __( 'More challenges, Unlimited Fun.', 'stax' ),
                        'btnAction' => __( 'Go Pro', 'stax' )
                    ],
                    'newZone'            => [
                        'title'        => __( 'Define Zone', 'stax' ),
                        'subtitle'     => __( 'To select where to render the zone, choose from the options below:', 'stax' ),
                        'selectOption' => __( 'Select a zone using your mouse (recommended)', 'stax' ),
                        'inputOption'  => __( 'Enter a CSS selector', 'stax' )
                    ],
                    'zone'               => [
                        'title'    => __( 'Save zone?', 'stax' ),
                        'subtitle' => __( 'You need to save the current zone before switching to another zone.', 'stax' )
                    ],
                    'duplicateZone'      => [
                        'title'    => __( 'Duplicate this zone?', 'stax' ),
                        'subtitle' => __( 'Duplicating this zone will create a new zone with the same content as the current one and display it only on the current page. The original zone will be excluded from this page.', 'stax' ),
                        'alert'    => __( 'Note: Your page will refresh after this process, but don\'t worry because everything will be saved.' )
                    ]
                ]
            ],
            'conditions'  => [
                'include'    => __( 'Include', 'stax' ),
                'exclude'    => __( 'Exclude', 'stax' ),
                'general'    => __( 'Entire site', 'stax' ),
                'archive'    => __( 'Archives', 'stax' ),
                'single'     => __( 'Single', 'stax' ),
                'all'        => __( 'All', 'stax' ),
                'author'     => __( 'Author page', 'stax' ),
                'date'       => __( 'Date page', 'stax' ),
                'search'     => __( 'Search results', 'stax' ),
                'posts'      => __( 'Posts', 'stax' ),
                'categories' => __( 'Categories', 'stax' ),
                'tags'       => __( 'Tag', 'stax' ),
                'format'     => __( 'Format', 'stax' ),
                'homepage'   => __( 'Front page', 'stax' ),
                'page'       => __( 'Page', 'stax' ),
                'media'      => __( 'Media', 'stax' ),
                'error'      => __( '404 page', 'stax' )

            ],
            'select'      => [
                'menuPlaceholder'  => __( 'Select menu', 'stax' ),
                'fontsPlaceholder' => __( 'Select font', 'stax' )
            ],
            'section'     => [
                'defaultTitle' => __( 'Options', 'stax' )
            ],
            'imageUpload' => [
                'change' => __( 'Change', 'stax' ),
                'add'    => __( 'Add image', 'stax' )
            ]
        ];
    }

}
