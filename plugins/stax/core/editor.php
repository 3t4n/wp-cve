<?php
/**
 * Stax editor related file.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Editor
 *
 * @package Stax
 */
class Editor {
    /**
     * Editor instance
     *
     * @var null
     */
    public static $instance = null;

    const TYPE_ELEMENT = 'element',
        TYPE_COLUMN = 'column',
        TYPE_HEADER = 'header',
        TYPE_SECTION = 'section';

    /**
     * Editor constructor.
     */
    public function __construct() {
        add_action( 'template_redirect', [ $this, 'init' ] );
        add_action( 'init', [ $this, 'removeAdminBar' ] );
    }

    /**
     * Class instance
     *
     * @return Editor
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Init.
     */
    public function init() {
        if ( $this->edit_mode() ) {
            remove_all_actions( 'wp_head' );
            remove_all_actions( 'wp_print_styles' );
            remove_all_actions( 'wp_print_head_scripts' );
            remove_all_actions( 'wp_footer' );

            add_action( 'wp_head', 'wp_enqueue_scripts', 1 );
            add_action( 'wp_head', 'wp_print_styles', 8 );
            add_action( 'wp_head', 'wp_print_head_scripts', 9 );

            add_action( 'wp_footer', 'wp_print_footer_scripts', 20 );
            add_action( 'wp_footer', 'wp_auth_check_html', 30 );

            remove_all_actions( 'wp_enqueue_scripts' );

            add_filter( 'ajax_query_attachments_args', [ $this, 'filterMedia' ] );

            $this->loadEditor();

            return;
        }

        if ( ( ! Plugin::instance()->is_front() || RenderStatus::instance()->getStatus() ) ) {
            add_action( 'wp_enqueue_scripts', [ $this, 'general_style' ], 999999 );
            add_action( 'wp_enqueue_scripts', [ $this, 'general_script' ], 999999 );
        }

        if ( ! current_user_can( 'administrator' ) ) {
            return;
        }

        if ( ! Plugin::instance()->is_preview() && ! Plugin::instance()->is_front() ) {
            add_action( 'wp_enqueue_scripts', [ $this, 'start_edit_style' ], 999999 );
        }

        if ( Plugin::instance()->is_editor_frame() ) {
            add_action( 'wp_enqueue_scripts', [ $this, 'editor_frame_style' ], 999999 );
            add_action( 'wp_enqueue_scripts', [ $this, 'editor_frame_scripts' ], 999999 );
        }

        if ( Plugin::instance()->is_setup() ) {
            add_action( 'wp_head', [ $this, 'start_inline_script' ], 1 );
            add_action( 'wp_enqueue_scripts', [ $this, 'start_edit_script' ], 999999 );
        }

    }

    /**
     * Are we in the edit panel?
     */
    public function edit_mode() {
        return Plugin::instance()->is_editor_panel();
    }

    /**
     *
     */
    public function loadEditor() {
        add_action( 'wp_enqueue_scripts', [ $this, 'editor_scripts' ], 999 );
        add_action( 'wp_enqueue_scripts', [ $this, 'editor_styles' ], 999 );
        include STAX_CORE_PATH . 'ui/editor.php';
        die();
    }

    /**
     *
     */
    public function removeAdminBar() {
        if ( ( Plugin::instance()->is_editor_frame() ||
               Plugin::instance()->is_preview() ||
               Plugin::instance()->is_editor_panel() ||
               Plugin::instance()->is_setup() ) &&
             current_user_can( 'administrator' ) ) {
            add_filter( 'show_admin_bar', '__return_false' );
        }
    }

    /**
     *
     */
    public function editor_scripts() {
        global $wp_styles, $wp_scripts;

        $wp_styles  = new \WP_Styles();
        $wp_scripts = new \WP_Scripts();

        wp_enqueue_media();

        wp_register_script(
            'stax-wgxpath',
            STAX_ASSETS_URL . 'js/wgxpath.install.js',
            [],
            STAX_VERSION,
            false
        );

        wp_register_script(
            'scripts',
            STAX_ASSETS_URL . 'js/scripts.js',
            [
                'stax-wgxpath'
            ],
            STAX_VERSION,
            true
        );

        wp_register_script(
            'ng-runtime',
            STAX_ASSETS_FW_URL . 'runtime.js',
            [
                'scripts',
            ],
            STAX_VERSION,
            true
        );

        wp_register_script(
            'ng-polyfills',
            STAX_ASSETS_FW_URL . 'polyfills.js',
            [
                'scripts',
                'ng-runtime',
            ],
            STAX_VERSION,
            true
        );

        if ( defined( 'STAX_DEV' ) && STAX_DEV ) {
            wp_register_script(
                'ng-vendor',
                STAX_ASSETS_FW_URL . 'vendor.js',
                [
                    'scripts',
                    'ng-runtime',
                    'ng-polyfills',
                ],
                STAX_VERSION,
                true
            );
        }

        wp_register_script(
            'ng-main',
            STAX_ASSETS_FW_URL . 'main.js',
            ( defined( 'STAX_DEV' ) && STAX_DEV ) ?
                [
                    'scripts',
                    'ng-runtime',
                    'ng-polyfills',
                    'ng-vendor',
                ] :
                [
                    'scripts',
                    'ng-runtime',
                    'ng-polyfills',
                ],
            STAX_VERSION,
            true
        );

        wp_register_script(
            'introjs',
            STAX_ASSETS_URL . 'js/intro.min.js',
            [],
            STAX_VERSION,
            true
        );


        wp_enqueue_script( 'heartbeat' );
        wp_enqueue_script( 'wp-auth-check' );
        wp_enqueue_script( 'ng-main' );
        wp_localize_script( 'ng-runtime', 'editorVars', $this->websiteData() );

        wp_enqueue_script( 'introjs' );

        $this->loadWpEditor();
    }

    /**
     *
     */
    public function editor_styles() {
        wp_register_style(
            'editor',
            STAX_ASSETS_URL . 'css/editor.css',
            []
        );

        wp_register_style(
            'admin-dash',
            includes_url() . 'css/dashicons.min.css',
            []
        );

        wp_register_style(
            'admin-media',
            admin_url() . 'css/media.min.css',
            [
                'admin-dash'
            ]
        );

        wp_register_style(
            'introjs',
            STAX_ASSETS_URL . 'css/introjs.min.css',
            []
        );

        wp_enqueue_style( 'wp_auth_check', '/wp-includes/css/wp-auth-check.css', [], null, 'all' );
        wp_enqueue_style( 'admin-media' );
        wp_enqueue_style( 'editor' );

        wp_enqueue_style( 'introjs' );
    }

    /**
     * Script for initial setup.
     */
    public function start_edit_script() {

        wp_register_script(
            'start-editor',
            STAX_ASSETS_URL . 'js/start-editor.js',
            [
                'jquery',
            ],
            false,
            true
        );

        wp_localize_script( 'start-editor', 'activeZones', [] );
        wp_enqueue_script( 'start-editor' );
    }

    /**
     *
     */
    public function start_inline_script() {

        echo '<script type="text/javascript">' .
             'var staxBody;' .
             'document.addEventListener("DOMContentLoaded", function(event) {' .
             'staxBody = document.querySelector("body").cloneNode(true);' .
             ' });' .
             ' </script>';
    }

    /**
     *
     */
    public function start_edit_style() {
        wp_register_style(
            'start-editor',
            STAX_ASSETS_URL . 'css/start-editor.css',
            []
        );

        wp_enqueue_style( 'start-editor' );
    }

    /**
     *
     */
    public function general_script() {
        wp_register_script(
            'stax-script',
            STAX_ASSETS_URL . 'js/stax.js',
            [
                'jquery',
            ],
            STAX_VERSION,
            true
        );

        wp_enqueue_script( 'stax-script' );
    }

    /**
     *
     */
    public function general_style() {

        wp_register_style(
            'material-icons',
            STAX_ASSETS_URL . 'css/materialdesignicons.css',
            []
        );

        wp_register_style(
            'stax-style',
            STAX_ASSETS_URL . 'css/stax.css',
            [],
            STAX_VERSION
        );

        wp_enqueue_style( 'material-icons' );
        wp_enqueue_style( 'stax-style' );
    }

    /**
     *
     */
    public function editor_frame_style() {
        wp_register_style(
            'context-menu',
            STAX_ASSETS_URL . 'css/contextmenu/jquery.contextMenu.min.css',
            []
        );

        wp_register_style(
            'material-icons',
            STAX_ASSETS_URL . 'css/materialdesignicons.css',
            []
        );

        wp_register_style(
            'stax-overlay',
            STAX_ASSETS_URL . 'css/overlay.css',
            [
                'material-icons',
            ]
        );

        wp_enqueue_style( 'context-menu' );
        wp_enqueue_style( 'stax-overlay' );
    }

    /**
     *
     */
    public function editor_frame_scripts() {
        wp_register_script(
            'context-menu',
            STAX_ASSETS_URL . 'js/contextmenu/jquery.contextMenu.min.js',
            [
                'jquery'
            ],
            STAX_VERSION,
            false
        );

        wp_register_script(
            'ui-position',
            STAX_ASSETS_URL . 'js/contextmenu/jquery.ui.position.min.js',
            [
                'context-menu'
            ],
            STAX_VERSION,
            false
        );

        wp_register_script(
            'element-manipulation',
            STAX_ASSETS_URL . 'js/element.manipulation.js',
            [
                'ui-position'
            ],
            STAX_VERSION,
            false
        );

        wp_enqueue_script( 'element-manipulation' );
    }


    public function websiteData() {
        global $wp;

        $page_url = home_url( $wp->request );
        if ( ! $page_url ) {
            $page_url = home_url();
        }

        $type          = 0;
        $zones         = Model_Zones::instance()->getByType( $type );
        $eligibleZones = [];
        $otherZones    = [];

        foreach ( $zones as $zone ) {
            $conditions = @json_decode( $zone->condition );

            if ( ! empty( $conditions ) ) {
                $condition_stack = [];

                foreach ( $conditions as $condition ) {
                    if ( $condition->category === 'general' ) {
                        $condition_stack[] = [
                            'callback' => 'general',
                            'target'   => '',
                            'type'     => ''
                        ];
                    } else if ( $condition->category === 'archive' ) {
                        foreach ( Plugin::instance()->display_conditions_archive() as $archCondition ) {
                            if ( $archCondition['tag'] === $condition->subcategory ) {
                                $condition_stack[] = [
                                    'callback' => $archCondition['callback'],
                                    'target'   => '',
                                    'type'     => $condition->type
                                ];
                            }
                        }

                    } else if ( $condition->category === 'single' ) {
                        foreach ( Plugin::instance()->display_conditions_single() as $sglCondition ) {
                            if ( $sglCondition['tag'] === $condition->subcategory ) {
                                $condition_stack[] = [
                                    'callback' => $sglCondition['callback'],
                                    'target'   => $condition->target,
                                    'type'     => $condition->type
                                ];
                            }
                        }
                    }
                }

                $eligible = false;
                $obj      = get_queried_object();

                foreach ( $condition_stack as $condition_item ) {
                    if ( $condition_item['callback'] !== "general" ) {
                        if ( ! empty( $condition_item['target'] ) && ! is_numeric( $condition_item['target'] ) ) {
                            continue;
                        }

                        if ( $condition_item['type'] === 'exclude' ) {
                            if ( $condition_item['callback']() ) {
                                if ( $condition_item['target'] ) {
                                    if ( ! $obj instanceof \WP_Term ) {
                                        if ( $condition_item['target'] === $obj->ID ) {
                                            $eligible = false;
                                            break;
                                        }
                                    } else {
                                        if ( $condition_item['target'] === $obj->term_id ) {
                                            $eligible = false;
                                            break;
                                        }
                                    }
                                } else {
                                    $eligible = false;
                                    break;
                                }
                            }
                        } else {
                            if ( $condition_item['callback']() ) {
                                if ( $condition_item['target'] ) {
                                    if ( ! $obj instanceof \WP_Term ) {
                                        if ( $condition_item['target'] === $obj->ID ) {
                                            $eligible = true;
                                        }
                                    } else {
                                        if ( $condition_item['target'] === $obj->term_id ) {
                                            $eligible = true;
                                        }
                                    }
                                } else {
                                    $eligible = true;
                                }
                            }
                        }
                    } else {
                        $eligible = true;
                    }
                }

                if ( $eligible ) {
                    $eligibleZones[] = $zone;
                } else {
                    $otherZones[] = $zone;
                }
            }
        }

        $defaultElements = $this->get_default_elements();
        $customElements  = $this->getCustomElements();
        $packData        = $this->getItemsPack( $eligibleZones );
        $baseSection     = new Section();
        $baseHeader      = new Header();
        $baseColumn      = new Column();
        $components      = [];
        $templates       = [];

        $templatesPack = Model_Templates::instance()->get();

        foreach ( $templatesPack as $template ) {
            $pack = json_decode( $template->pack );

            if ( ! $pack ) {
                continue;
            }

            $templateItems             = new \stdClass();
            $templateItems->zone       = new \stdClass();
            $templateItems->containers = new \stdClass();
            $templateItems->columns    = new \stdClass();
            $templateItems->elements   = new \stdClass();
            $templateItems->group      = new \stdClass();
            $templateItems->fonts      = new \stdClass();

            $templateItems->zone  = $pack->zone;
            $templateItems->group = $pack->group;

            if ( isset( $pack->fonts ) ) {
                $templateItems->fonts = $pack->fonts;
            }

            foreach ( $pack->containers as $container ) {
                if ( ! $container instanceof \stdClass ) {
                    continue;
                }
                $templateItems->containers->{$container->uuid} = $this->matchAndMergeFields( $container );
            }

            foreach ( $pack->columns as $column ) {
                if ( ! $column instanceof \stdClass ) {
                    continue;
                }
                $templateItems->columns->{$column->uuid} = $this->matchAndMergeFields( $column );
            }

            if ( isset( $pack->elements ) ) {
                foreach ( $pack->elements as $element ) {
                    if ( ! $element instanceof \stdClass ) {
                        continue;
                    }
                    $templateItems->elements->{$element->uuid} = $this->matchAndMergeFields( $element );
                }
            }

            $templates[] = [
                'id'      => $template->id,
                'name'    => $template->name,
                'changed' => false,
                'pack'    => $templateItems
            ];
        }

        $componentsPack = Model_Components::instance()->get();
        foreach ( $componentsPack as $component ) {
            $properties = @json_decode( $component->properties );

            if ( ! $properties instanceof \stdClass ) {
                continue;
            }

            $properties = $this->matchAndMergeFields( $properties );

            if ( ! $properties ) {
                continue;
            }

            $components[] = [
                'id'         => $component->id,
                'name'       => $component->name,
                'properties' => $properties
            ];
        }

        $theme = get_option( 'stax_editor_theme' );
        if ( ! $theme ) {
            $theme = 'light';
        }

        $themes = (object) [
            'light' => [
                'label'    => 'Light',
                'selected' => ( $theme === 'light' ) ? true : false,
                'value'    => 'light'
            ],
            'dark'  => [
                'label'    => 'Dark',
                'selected' => ( $theme === 'dark' ) ? true : false,
                'value'    => 'dark'
            ]
        ];

        $colors = get_option( 'stax_color_picker' );
        if ( ! $colors ) {
            $colors = [
                '#e91e63',
                '#673ab7',
                '#2196f3',
                '#00bcd4',
                '#4caf50',
                '#cddc39',
                '#ffc107'
            ];
        } else {
            $colors = json_decode( $colors );
        }

        /* Clone zone data */
        /* TODO POST TYPES Conditional logic */
        $clone_headers = [ 'category', 'subcategory', 'target' ];
        $clone_data    = [];
        if ( is_front_page() ) {
            $clone_data = [ 'single', 'front', '' ];
        } elseif ( is_singular() ) {
            $current_page_id = get_the_ID();
            if ( is_page() ) {
                $clone_data = [ 'single', 'page', $current_page_id ];
            } elseif ( is_singular( 'post' ) ) {
                $clone_data = [ 'single', 'posts', $current_page_id ];
            }/* else {
		        $clone_data = [ 'single', get_post_type(), $current_page_id ];
	        }*/

        } elseif ( is_404() ) {
            $clone_data = [ 'single', '404', '' ];
        } elseif ( is_archive() ) {
            if ( is_author() ) {
                $clone_data = [ 'archive', 'author-page', '' ];
            } elseif ( is_date() ) {
                $clone_data = [ 'archive', 'date-page', '' ];
            } elseif ( is_search() ) {
                $clone_data = [ 'archive', 'search-result', '' ];
            } elseif ( is_category() ) {
                $clone_data = [ 'archive', 'categories', get_queried_object()->term_id ];
            } elseif ( is_tag() ) {
                $clone_data = [ 'archive', 'tag', get_queried_object()->term_id ];
            }/* elseif ( is_tax() ) {
		        $term     = get_queried_object();
		        $clone_data = [ 'archive', $term->taxonomy, $term->term_id ];
	        } elseif( is_post_type_archive() ) {
		        $post_type_object = get_post_type_object( get_query_var( 'post_type' ) );
		         $clone_data       = [ 'archive', $post_type_object->name, '' ];
	        }*/
        }

        if ( count( $clone_data ) === 3 ) {
            $clone_data = array_combine( $clone_headers, $clone_data );
        } else {
            $clone_data = $clone_headers;
        }

        $data = [
            'config' => [
                'base_url'   => esc_url( site_url() ),
                'page_url'   => esc_url( $page_url ),
                'rest_url'   => esc_url( get_rest_url( null, STAX_API_NAMESPACE . '/' ) ),
                'admin_url'  => admin_url(),
                'assets_url' => STAX_ASSETS_URL,
                'pro_url'    => stax_fs()->get_upgrade_url(),
                'nonce'      => wp_create_nonce( 'wp_rest' ),
            ],
            'editor' => [
                'base'             => [
                    'section'         => $baseSection,
                    'header'          => $baseHeader,
                    'column'          => $baseColumn,
                    'defaultElements' => $defaultElements,
                    'customElements'  => $customElements,
                    'fonts'           => Fonts::instance()->get()
                ],
                'settings'         => [
                    'theme'      => $theme,
                    'themes'     => $themes,
                    'colors'     => $colors,
                    'status'     => RenderStatus::instance()->getStatus(),
                    'conditions' => [
                        'general' => [],
                        'single'  => Plugin::instance()->display_conditions_single(),
                        'archive' => Plugin::instance()->display_conditions_archive()
                    ]
                ],
                'cloneZoneData'    => $clone_data,
                'viewport'         => '',
                'currentZone'      => '',
                'wpTheme'          => strtolower( wp_get_theme( get_template() )->display( 'Name' ) ),
                'zones'            => $packData->zones,
                'otherZones'       => $otherZones,
                'defaultZones'     => $packData->defaultZones,
                'containers'       => $packData->containers,
                'columns'          => $packData->columns,
                'elements'         => $packData->elements,
                'groups'           => $packData->groups,
                'fonts'            => $packData->fonts,
                'components'       => $components,
                'templates'        => array_merge( $templates, Templates::instance()->get() ),
                'defaultTemplates' => [],
                'deleted'          => new \stdClass(),
                'queue'            => new \stdClass(),
                'drop'             => new \stdClass(),
                'layerDrop'        => new \stdClass(),
                'layers'           => [],
                'icons'            => Icons::instance()->get(),
                'l10n'             => L10n::instance()->strings()
            ]
        ];

        return $data;
    }

    /**
     * @param $zones
     *
     * @return \stdClass
     */
    protected function getItemsPack( $zones ) {
        $theme_name = strtolower( wp_get_theme( get_template() )->display( 'Name' ) );

        $result               = new \stdClass();
        $result->zones        = new \stdClass();
        $result->defaultZones = new \stdClass();
        $result->containers   = new \stdClass();
        $result->columns      = new \stdClass();
        $result->elements     = new \stdClass();
        $result->groups       = new \stdClass();
        $result->fonts        = new \stdClass();

        $missingZones = $defaultZones = [
            'header',
            'content',
            'footer'
        ];

        foreach ( $zones as $zone ) {
            if ( ( $key = array_search( $zone->slug, $missingZones ) ) !== false ) {
                unset( $missingZones[ $key ] );
            }

            $result->zones->{$zone->uuid} = (object) [
                'name'      => $zone->name,
                'uuid'      => $zone->uuid,
                'slug'      => $zone->slug,
                'builder'   => '',
                'condition' => json_decode( $zone->condition ),
                'selector'  => json_decode( $zone->selector ),
                'enabled'   => ( $zone->enabled == 0 ) ? false : true
            ];

            $selector = @json_decode( $zone->selector );

            if ( isset( $selector->{$theme_name} ) ) {
                $result->groups->{$zone->uuid} = (object) [
                    'containers' => (object) [],
                    'position'   => $selector->{$theme_name}->position,
                    'visibility' => intval( $selector->{$theme_name}->visibility )
                ];
            } else {
                $result->groups->{$zone->uuid} = (object) [
                    'containers' => (object) [],
                    'position'   => 1,
                    'visibility' => 1
                ];
            }

            $pack = @json_decode( $zone->pack );

            foreach ( $pack as $uuid ) {
                $container = Model_Container::instance()->get( $uuid );
                if ( $container ) {
                    if ( ! @json_decode( $container->properties ) instanceof \stdClass ) {
                        continue;
                    }

                    $result->containers->{$uuid} = $this->matchAndMergeFields( @json_decode( $container->properties ) );

                    $viewports = Model_ContainerViewport::instance()->get( $container->uuid );

                    $result->groups->{$zone->uuid}->containers->{$container->uuid} = (object) [
                        'viewport' => (object) []
                    ];

                    foreach ( $viewports as $containerItem ) {
                        $result->groups->{$zone->uuid}->containers->{$containerItem->container_uuid}->viewport->{$containerItem->viewport} = (object) [
                            'columns'    => (object) [],
                            'position'   => $containerItem->position,
                            'visibility' => intval( $containerItem->visibility ),
                            'belongsTo'  => $containerItem->belongs_to
                        ];

                        $cols = Model_ContainerItems::instance()->getByContainerUuid( $containerItem->container_uuid, $containerItem->viewport );

                        foreach ( $cols as $colItem ) {
                            $storedColumn = Model_Columns::instance()->get( $colItem->column_uuid );

                            if ( $storedColumn ) {
                                $result->groups->{$zone->uuid}->containers->{$containerItem->container_uuid}->viewport->{$containerItem->viewport}->columns->{$colItem->column_uuid} = (object) [
                                    'elements'   => (object) [],
                                    'position'   => $colItem->position,
                                    'visibility' => intval( $colItem->visibility )
                                ];

                                if ( ! @json_decode( $storedColumn->properties ) instanceof \stdClass ) {
                                    continue;
                                }

                                $result->columns->{$colItem->column_uuid} = $this->matchAndMergeFields( @json_decode( $storedColumn->properties ) );

                                $storedElements = json_decode( $colItem->elements );
                                foreach ( $storedElements as $elementUuid => $elItem ) {
                                    $storedElement = Model_Elements::instance()->get( $elementUuid );
                                    if ( $storedElement ) {
                                        $result->groups->{$zone->uuid}->containers->{$containerItem->container_uuid}->viewport->{$containerItem->viewport}->columns->{$colItem->column_uuid}->elements->{$elementUuid} = $elItem;

                                        if ( ! @json_decode( $storedElement->properties ) instanceof \stdClass ) {
                                            continue;
                                        }

                                        $result->elements->{$elementUuid} = $this->matchAndMergeFields( @json_decode( $storedElement->properties ) );
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        foreach ( $missingZones as $type ) {
            if ( ! is_single() && $type === 'content' ) {
                continue;
            }

            $path    = '';
            $xpath   = true;
            $enabled = true;

            if ( $type === 'content' ) {
                $xpath = false;
                $path  = '#stax-content';
            } elseif ( $type === 'footer' ) {
                $xpath   = false;
                $path    = '#stax-footer';
                $enabled = false;
            } elseif ( $type === 'header' ) {
                $path = Compatibility::instance()->get_tag( 'header' );
                if ( $path !== '' ) {
                    $xpath = false;
                }
            }

            $selectors                = [];
            $selectors[ $theme_name ] = [
                'xpath'      => $xpath,
                'position'   => 1,
                'visibility' => 1,
                'path'       => $path
            ];

            $result->defaultZones->{$type} = (object) [
                'name'      => ucfirst( $type ),
                'uuid'      => '',
                'slug'      => $type,
                'builder'   => '',
                'condition' => [],
                'selector'  => (object) $selectors,
                'enabled'   => $enabled
            ];
        }

        foreach ( $result->zones as $zone ) {
            if ( ! in_array( $zone->slug, $defaultZones ) ) {
                continue;
            }

            if ( ! is_single() && $zone->slug === 'content' ) {
                continue;
            }

            if ( $zone->slug === 'header' ) {
                continue;
            }

            if ( ! isset( $zone->selector->{$theme_name} ) ) {
                $path  = '';
                $xpath = true;

                if ( $zone->slug === 'content' ) {
                    $xpath = false;
                    $path  = '#stax-content';
                } else if ( $zone->slug === 'footer' ) {
                    $xpath = false;
                    $path  = '#stax-footer';
                }

                $zone->selector->{$theme_name} = [
                    'xpath'      => $xpath,
                    'position'   => 1,
                    'visibility' => 1,
                    'path'       => $path
                ];
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function getCustomElements() {
        return [];
    }

    /**
     * Get available elements
     *
     * @param bool $cloned Clone element objects
     *
     * @return array
     */
    protected function get_default_elements( $cloned = false ) {

        if ( $cloned ) {
            return Plugin::instance()->register_elements();
        }

        return Plugin::instance()->get_registered_elements();
    }

    /**
     * @param $save
     *
     * @return mixed
     */
    public function matchAndMergeFields( $save ) {
        $native = null;

        switch ( $save->type ) {
            case self::TYPE_ELEMENT:
                $defaultElements = $this->get_default_elements( true );
                $customElements  = $this->getCustomElements();

                if ( isset( $defaultElements[ $save->slug ] ) ) {
                    $native = $defaultElements[ $save->slug ];
                } elseif ( isset( $customElements[ $save->slug ] ) ) {
                    $native = $customElements[ $save->slug ];
                }
                break;
            case self::TYPE_COLUMN:
                $native = new Column();
                if ( ! isset( $save->parent ) ) {
                    $save->parent = self::TYPE_HEADER;
                }

                if ( $save->parent === self::TYPE_HEADER ) {
                    $native->editor = $native->editor_header;
                } elseif ( $save->parent === self::TYPE_SECTION ) {
                    $native->editor = $native->editor_section;
                }
                break;
            case self::TYPE_HEADER:
                $native = new Header();
                break;
            case self::TYPE_SECTION:
                $native = new Section();
                break;
            default:
        }

        if ( ! $native ) {
            return $save;
        }

        $existing_pro_fields = [ 'resize_field', 'slide_up_field', 'transparent_field' ];

        foreach ( $save->editor as $saveEditor ) {
            foreach ( $saveEditor->fields as $saveField ) {
                foreach ( $native->editor as $k => $nativeEditor ) {
                    if ( $saveEditor->name === $nativeEditor->name ) {
                        $native->editor[ $k ]->visibility = $saveEditor->visibility;
                    }

                    foreach ( $nativeEditor->fields as $i => $nativeField ) {
                        if ( $saveField->name === $nativeField->name ) {

                            // Dirty trick for existing saved fields
                            if ( in_array( $saveField->name, $existing_pro_fields ) && empty( $saveField->value ) ) {
                                $native->editor[ $k ]->fields[ $i ]->visibility = $saveField->visibility;
                                continue;
                            }

                            if ( is_array( $saveField->value ) && is_array( $nativeField->value ) ) {
                                foreach ( $saveField->value as $sKey => $savedFieldVal ) {
                                    foreach ( $nativeField->value as $nKey => $nativeFieldVal ) {
                                        if ( $nKey === $sKey ) {
                                            $saved_value = isset( $savedFieldVal->value ) ? $savedFieldVal->value : null;

                                            $native->editor[ $k ]->fields[ $i ]->value[ $nKey ]['value'] = $saved_value;

                                            if ( isset( $savedFieldVal->extra ) && isset( $nativeFieldVal['extra'] ) ) {
                                                $native->editor[ $k ]->fields[ $i ]->value[ $nKey ]['extra'] = $savedFieldVal->extra;
                                            }

                                            if ( isset( $savedFieldVal->checked ) && isset( $nativeFieldVal['checked'] ) ) {
                                                $native->editor[ $k ]->fields[ $i ]->value[ $nKey ]['checked'] = $savedFieldVal->checked;
                                            }

                                            if ( isset( $savedFieldVal->selected ) && isset( $nativeFieldVal['selected'] ) ) {
                                                $native->editor[ $k ]->fields[ $i ]->value[ $nKey ]['selected'] = $savedFieldVal->selected;
                                            }
                                        }
                                    }
                                }
                            } else {
                                $native->editor[ $k ]->fields[ $i ]->value = $saveField->value;
                            }
                            $native->editor[ $k ]->fields[ $i ]->visibility = $saveField->visibility;
                            $native->editor[ $k ]->fields[ $i ]->units      = $saveField->units;
                        }
                    }
                }
            }
        }

        if ( ! empty( $save->editor ) ) {
            $save->editor = $native->editor;
        }

        return $save;
    }

    /**
     * @return string
     */
    public function loadWpEditor() {
        ob_start();

        wp_editor(
            '%%EDITORCONTENT%%',
            'sqwpeditor',
            [
                'editor_class'     => 'sq-wp-editor',
                'editor_height'    => 300,
                'drag_drop_upload' => true,
            ]
        );

        return ob_get_clean();
    }

}
