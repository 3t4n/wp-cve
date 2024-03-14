<?php

/**
 * Stax main loader file.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */
namespace Stax;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Class Plugin
 *
 * @package Stax
 */
class Plugin
{
    /**
     * @var null
     */
    public static  $instance = null ;
    /**
     * @var
     */
    public  $ui ;
    /**
     * @var
     */
    public  $settings ;
    /**
     * @var
     */
    public  $db ;
    /**
     * @var
     */
    public  $route ;
    /**
     * @var
     */
    public  $editor ;
    /**
     * @var null
     */
    public static  $zones_data = array() ;
    /**
     * @var string
     */
    private  $theme_name = '' ;
    /**
     * @var array
     */
    protected  $registered_elements = array() ;
    /**
     * Plugin Instance
     *
     * @return null|Plugin
     */
    public static function instance()
    {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Plugin constructor.
     */
    public function __construct()
    {
        $this->include_models();
        $this->include_components();
        $this->include_helpers();
        $this->include_core_classes();
        $this->init_core();
        Routes::instance();
        register_activation_hook( STAX_FILE, [ $this, 'migrate' ] );
        add_action( 'wp', [ $this, 'wp' ] );
        add_action( 'init', [ $this, 'init' ] );
        add_filter(
            'page_row_actions',
            [ $this, 'add_row_edit' ],
            10,
            2
        );
        add_filter(
            'post_row_actions',
            [ $this, 'add_row_edit' ],
            10,
            2
        );
        $this->theme_name = strtolower( wp_get_theme( get_template() )->display( 'Name' ) );
    }
    
    /**
     *
     */
    public function wp()
    {
        Compatibility::instance()->register();
        if ( $this->isRestUrl() || is_admin() || defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            return;
        }
        
        if ( $this->is_editing() || RenderStatus::instance()->getStatus() ) {
            $zone_data = $this->get_zones_data();
            $add_content_wrapper = false;
            foreach ( $zone_data as $selector => $data ) {
                
                if ( strpos( $selector, 'stax-content' ) !== false ) {
                    $add_content_wrapper = true;
                } elseif ( strpos( $selector, 'stax-before-content' ) !== false ) {
                    $add_content_wrapper = true;
                } elseif ( strpos( $selector, 'stax-after-content' ) !== false ) {
                    $add_content_wrapper = true;
                }
            
            }
            if ( $add_content_wrapper || $this->is_editing() ) {
                add_filter( 'the_content', function ( $content ) {
                    return '<div id="stax-before-content"></div><div id="stax-content">' . $content . '</div><div id="stax-after-content"></div>';
                }, 9999 );
            }
            add_action( 'wp_footer', function () {
                echo  '<div id="stax-footer"></div>' ;
            }, 9999 );
        }
        
        if ( $this->is_editing() ) {
            add_action( 'body_class', [ $this, 'body_class' ] );
        }
    }
    
    /**
     *
     */
    public function init()
    {
        $this->register_elements();
        if ( is_admin() ) {
            add_action( 'admin_menu', [ $this, 'init_admin_dashboard' ] );
        }
        add_shortcode( 'stax-menu', [ $this, 'init_menu_shortcode' ] );
        if ( $this->is_setup() || $this->is_preview() ) {
            add_action( 'wp_enqueue_scripts', [ $this, 'disable_links' ], 10 );
        }
        
        if ( $this->is_front() && RenderStatus::instance()->getStatus() || $this->is_preview() ) {
            add_action( 'wp_head', [ $this, 'front_fonts' ], 50 );
            add_action( 'wp_head', [ $this, 'front_css' ], 50 );
            if ( !$this->is_setup() ) {
                add_action( 'wp_head', [ $this, 'register_replace' ], 0 );
            }
        }
        
        add_action( 'wp_before_admin_bar_render', [ $this, 'admin_bar_render' ] );
        add_action(
            'in_plugin_update_message-stax/index.php',
            [ $this, 'update_message' ],
            10,
            2
        );
        add_action(
            'after_plugin_row_wp-' . STAX_PLUGIN_BASE,
            [ $this, 'ms_plugin_update_message' ],
            10,
            2
        );
        $this->migrate();
    }
    
    public function add_row_edit( $actions, $object )
    {
        
        if ( in_array( $object->post_type, [ 'post', 'page' ] ) ) {
            $url = get_permalink( $object->ID );
            $edit_link = add_query_arg( array(
                'stax-editor' => '',
            ), $url );
            $actions['stax-edit'] = sprintf( '<a target="_blank" href="%1$s">%2$s</a>', esc_url( $edit_link ), esc_html( esc_html__( 'Edit header', 'stax' ) ) );
        }
        
        return $actions;
    }
    
    function ms_plugin_update_message( $file, $plugin )
    {
        
        if ( isset( $plugin['upgrade_notice'] ) && is_multisite() && version_compare( $plugin['Version'], $plugin['new_version'], '<' ) ) {
            $wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );
            printf(
                '<tr class="plugin-update-tr">' . '<td colspan="%s" class="plugin-update update-message notice inline notice-warning notice-alt">' . '<div class="update-message"><h4 style="margin: 0; font-size: 14px;">%s</h4>%s</div></td></tr>',
                $wp_list_table->get_column_count(),
                $plugin['Name'],
                wpautop( $plugin['upgrade_notice'] )
            );
        }
    
    }
    
    public function update_message( $data, $response )
    {
        if ( isset( $data['upgrade_notice'] ) ) {
            printf( '<div class="update-message">%s</div>', wpautop( $data['upgrade_notice'] ) );
        }
    }
    
    public function admin_bar_render()
    {
        global  $wp_admin_bar ;
        if ( !is_user_logged_in() || !current_user_can( 'manage_options' ) ) {
            return;
        }
        
        if ( is_admin() ) {
            $link = home_url( '?stax-editor' );
            $title = esc_html__( 'Open STAX Builder', 'stax' );
        } else {
            $link = add_query_arg( array(
                'stax-editor' => '',
            ) );
            $title = esc_html__( 'STAX Builder', 'stax' );
        }
        
        $wp_admin_bar->add_menu( array(
            'parent' => false,
            'id'     => 'stax_edit',
            'title'  => '<img style="vertical-align:sub;" src="' . STAX_BASE_URL . 'assets/images/stax-16.png"> ' . $title,
            'href'   => $link,
            'meta'   => false,
        ) );
    }
    
    public function body_class( $classes = array() )
    {
        $classes[] = 'stax-editor-enabled';
        return $classes;
    }
    
    /**
     *
     */
    public function front_css()
    {
        $data = [];
        $zoneData = $this->get_zones_data();
        foreach ( $zoneData as $stack ) {
            foreach ( $stack as $item ) {
                $data[] = $item['css'];
            }
        }
        $output = '';
        foreach ( $data as $css ) {
            $output .= $this->get_front_css_by_data( $css );
        }
        echo  '<style>' . $output . '</style>' . "\n" ;
    }
    
    /**
     * @param $data
     *
     * @return mixed|string
     */
    private function get_front_css_by_data( $data )
    {
        $output = '';
        if ( !is_array( $data ) || empty($data) ) {
            return '';
        }
        
        if ( !$data['desktop'] && !$data['tablet'] && !$data['mobile'] ) {
            $output = $data['general'];
        } else {
            $output .= '@media (max-width: 767.99px) {' . (( $data['mobile'] ? $data['mobile'] : $data['general'] )) . '}';
            $output .= '@media (min-width: 768px) and (max-width: 991.99px) {' . (( $data['tablet'] ? $data['tablet'] : $data['general'] )) . '}';
            $output .= '@media (min-width: 992px) {' . (( $data['general'] ? $data['general'] : $data['general'] )) . '}';
        }
        
        return $output;
    }
    
    /**
     *
     */
    public function front_fonts()
    {
        $fonts = [];
        $zoneData = $this->get_zones_data();
        foreach ( $zoneData as $item ) {
            foreach ( $item as $itm ) {
                if ( isset( $itm['fonts'] ) && is_array( $itm['fonts'] ) ) {
                    foreach ( $itm['fonts'] as $font_id ) {
                        if ( $font_id && !in_array( $font_id, $fonts ) ) {
                            $fonts[] = $font_id;
                        }
                    }
                }
            }
        }
        $fonts_url = $this->get_front_fonts_by_data( $fonts );
        if ( $fonts_url ) {
            echo  '<link href="' . $fonts_url . '" rel="stylesheet" type="text/css">' ;
        }
    }
    
    /**
     * @param array $fontsStack
     *
     * @return bool|string
     */
    private function get_front_fonts_by_data( array $fontsStack )
    {
        if ( !is_array( $fontsStack ) || empty($fontsStack) ) {
            return false;
        }
        $finalURL = [];
        $fonts = Fonts::instance()->get();
        $list = $fonts->items;
        $baseUrl = '//fonts.googleapis.com/css?family=';
        /* TODO Improve font weights to load just what it uses */
        $fontWeights = ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
        foreach ( $fontsStack as $key => $font ) {
            $font_family = '';
            /* Old format fallback */
            
            if ( is_numeric( $font ) && $font > 0 ) {
                $found_font = array_slice(
                    $list,
                    $font,
                    1,
                    true
                );
                if ( is_array( $found_font ) ) {
                    foreach ( $found_font as $item ) {
                        $font_family = $item->family;
                    }
                }
            } else {
                if ( array_key_exists( $font, $list ) ) {
                    $font_family = $list[$font]->family;
                }
            }
            
            if ( $font_family ) {
                $finalURL[] = urlencode( $font_family ) . $fontWeights;
            }
        }
        if ( !empty($finalURL) ) {
            return $baseUrl . implode( '|', $finalURL );
        }
        return false;
    }
    
    public function register_replace()
    {
        wp_register_script(
            'stax-wgxpath',
            STAX_ASSETS_URL . 'js/wgxpath.install.js',
            [],
            false,
            false
        );
        wp_register_script(
            'stax-replace',
            STAX_ASSETS_URL . 'js/replace.js',
            [ 'stax-wgxpath' ],
            false,
            false
        );
        wp_enqueue_script( 'stax-replace' );
        $zones_data = $this->get_zones_data();
        $responsive = [];
        $output = '';
        $css_output = '';
        foreach ( $zones_data as $selector => $items ) {
            $responsive[$selector] = [
                'desktop' => '',
                'tablet'  => '',
                'mobile'  => '',
            ];
            $sub_containers = [];
            foreach ( $items as $item ) {
                foreach ( $item['html'] as $resolution => $data ) {
                    foreach ( $data as $content ) {
                        
                        if ( !$content['belongs_to'] ) {
                            $responsive[$selector][$resolution] .= $content['content'];
                        } else {
                            $sub_containers[$resolution][] = [
                                'belongs_to' => $content['belongs_to'],
                                'content'    => $content['content'],
                            ];
                        }
                    
                    }
                }
                $zone_id = $item['zone_id'];
                $responsive[$selector]['zoneId'] = $zone_id;
                $responsive[$selector]['isXpath'] = $item['xpath'];
                $css_output .= '@keyframes staxZoneDetected' . $zone_id . ' {from {opacity:0.99;} to {opacity:1;}}' . "\n";
                if ( !$item['xpath'] ) {
                    $css_output .= $selector . '{opacity: 0;' . 'animation-duration: 0.001s;' . '-webkit-animation-duration: 0.001s;' . 'animation-name: staxZoneDetected' . $zone_id . ';' . '-webkit-animation-name: staxZoneDetected' . $zone_id . ';' . '}';
                }
            }
            if ( !empty($sub_containers) ) {
                foreach ( $responsive[$selector] as $rez => $content ) {
                    if ( !in_array( $rez, [ 'desktop', 'tablet', 'mobile' ] ) ) {
                        continue;
                    }
                    
                    if ( isset( $sub_containers[$rez] ) && is_array( $sub_containers[$rez] ) ) {
                        preg_match_all( "'{{elementDropzone-(.*?)}}'si", $responsive[$selector][$rez], $matches );
                        foreach ( $matches[1] as $uuid ) {
                            $responsive[$selector][$rez] = $this->add_subcontainer( $sub_containers[$rez], $content, $uuid );
                        }
                    }
                
                }
            }
            foreach ( $responsive[$selector] as $rez => $content ) {
                if ( !in_array( $rez, [ 'desktop', 'tablet', 'mobile' ] ) ) {
                    continue;
                }
                preg_match_all( "'{{elementDropzone-(.*?)}}'si", $responsive[$selector][$rez], $matches );
                foreach ( $matches[1] as $uuid ) {
                    $responsive[$selector][$rez] = str_replace( '{{elementDropzone-' . $uuid . '}}', '', $responsive[$selector][$rez] );
                }
            }
        }
        $output .= 'var staxResponsive = ' . json_encode( $responsive ) . ';' . 'staxWriteCss();';
        if ( $css_output ) {
            echo  '<style>' . $css_output . '</style>' ;
        }
        $output .= 'document.addEventListener("animationstart", staxListener, false);' . "\n" . 'document.addEventListener("MSAnimationStart", staxListener, false);' . "\n" . 'document.addEventListener("webkitAnimationStart", staxListener, false);' . "\n";
        $output .= 'window.addEventListener("resize", function(){ staxReplaceZone(); });';
        wp_add_inline_script( 'stax-replace', $output );
    }
    
    /**
     * @return bool
     */
    public function isRestUrl()
    {
        $isRest = false;
        
        if ( function_exists( 'rest_url' ) && !empty($_SERVER['REQUEST_URI']) ) {
            $sRestUrlBase = get_rest_url( get_current_blog_id(), '/' );
            $sRestPath = trim( parse_url( $sRestUrlBase, PHP_URL_PATH ), '/' );
            $sRequestPath = trim( $_SERVER['REQUEST_URI'], '/' );
            $isRest = strpos( $sRequestPath, $sRestPath ) === 0;
        }
        
        return $isRest;
    }
    
    /**
     * Include our models
     *
     * @return void
     */
    public function include_models()
    {
        include STAX_CORE_PATH . 'models/Base_Model.php';
        include STAX_CORE_PATH . 'models/Model_Zones.php';
        include STAX_CORE_PATH . 'models/Model_Container.php';
        include STAX_CORE_PATH . 'models/Model_ContainerViewport.php';
        include STAX_CORE_PATH . 'models/Model_ContainerItems.php';
        include STAX_CORE_PATH . 'models/Model_Columns.php';
        include STAX_CORE_PATH . 'models/Model_Elements.php';
        include STAX_CORE_PATH . 'models/Model_Templates.php';
        include STAX_CORE_PATH . 'models/Model_Components.php';
        include STAX_CORE_PATH . 'models/Model_Settings.php';
    }
    
    /**
     * Include components
     *
     * @return void
     */
    public function include_components()
    {
        include STAX_CORE_PATH . 'components/base/ContainerInterface.php';
        include STAX_CORE_PATH . 'components/base/ColumnInterface.php';
        include STAX_CORE_PATH . 'components/base/ElementInterface.php';
        include STAX_CORE_PATH . 'components/helpers/ElementSpecs.php';
        include STAX_CORE_PATH . 'components/helpers/EditorSection.php';
        include STAX_CORE_PATH . 'components/helpers/EditorSectionField.php';
        include STAX_CORE_PATH . 'components/helpers/Composer.php';
        include STAX_CORE_PATH . 'components/Section.php';
        include STAX_CORE_PATH . 'components/Header.php';
        include STAX_CORE_PATH . 'components/Column.php';
        include STAX_CORE_PATH . 'components/Element.php';
    }
    
    public function get_registered_elements()
    {
        return $this->registered_elements;
    }
    
    public function register_elements()
    {
        $elements = [
            'ElementLogo',
            'ElementMenu',
            'ElementSearch',
            'ElementButton',
            'ElementText',
            'ElementImage',
            'ElementLink',
            'ElementSeparator',
            'ElementIcon',
            'ElementAccordion',
            'ElementDivider',
            'ElementGoogleMaps',
            'ElementHeading',
            'ElementSpacer',
            'ElementTabs'
        ];
        foreach ( $elements as $element ) {
            include_once STAX_CORE_PATH . 'components/' . $element . '.php';
            $class_name = __NAMESPACE__ . '\\' . $element;
            $element_instance = new $class_name();
            $this->registered_elements[$element_instance->slug] = $element_instance;
        }
        do_action( 'stax_elements_registered', $this );
        return $this->registered_elements;
    }
    
    /**
     * Include helpers
     *
     * @return void
     */
    public function include_helpers()
    {
        include STAX_CORE_PATH . 'helpers/Underscore/Underscore.php';
        include STAX_CORE_PATH . 'helpers/Underscore/Bridge.php';
        include STAX_CORE_PATH . 'helpers/Icons.php';
        include STAX_CORE_PATH . 'helpers/Fonts.php';
        include STAX_CORE_PATH . 'helpers/Menus.php';
        include STAX_CORE_PATH . 'helpers/ShortCode.php';
        include STAX_CORE_PATH . 'helpers/MenuWalker.php';
        include STAX_CORE_PATH . 'helpers/CompatibleTheme.php';
        include STAX_CORE_PATH . 'helpers/Compatibility.php';
        include STAX_CORE_PATH . 'helpers/Import.php';
        include STAX_CORE_PATH . 'helpers/Export.php';
        include STAX_CORE_PATH . 'helpers/OptionsWP.php';
        include STAX_CORE_PATH . 'helpers/RenderStatus.php';
        include STAX_CORE_PATH . 'helpers/Templates.php';
        include STAX_CORE_PATH . 'helpers/L10n.php';
        include STAX_CORE_PATH . 'helpers/PageSeeker.php';
    }
    
    /**
     * Include core classes
     *
     * @return void
     */
    public function include_core_classes()
    {
        include STAX_CORE_PATH . 'routes.php';
        include STAX_CORE_PATH . 'db/db.php';
        include STAX_CORE_PATH . 'db/upgrade_db.php';
        include STAX_CORE_PATH . 'editor.php';
    }
    
    /**
     * Init core
     *
     * @return void
     */
    public function init_core()
    {
        $this->db = new DB();
        $this->editor = Editor::instance();
    }
    
    /**
     * Is the editor opened?
     *
     * @return bool
     */
    public function is_editing()
    {
        if ( ($this->is_editor_frame() || $this->is_editor_panel()) && !is_admin() ) {
            return true;
        }
        return false;
    }
    
    /**
     * Are we in the editing panel
     *
     * @return bool
     */
    public function is_editor_panel()
    {
        if ( isset( $_GET['stax-editor'] ) && current_user_can( 'administrator' ) ) {
            return true;
        }
        return false;
    }
    
    /**
     * Are we in the editing frame
     *
     * @return bool
     */
    public function is_editor_frame()
    {
        if ( isset( $_GET['is-editing'] ) ) {
            return true;
        }
        return false;
    }
    
    /**
     * Check if we are previewing a zone
     *
     * @return bool
     */
    public function is_preview()
    {
        if ( isset( $_GET['is-preview'] ) && isset( $_GET['section'] ) && (is_numeric( $_GET['section'] ) || strpos( $_GET['section'], 'stax_tpl' ) === 0) && !is_admin() && current_user_can( 'administrator' ) ) {
            return true;
        }
        return false;
    }
    
    /**
     * Check if we are on initial setup
     *
     * @return bool
     */
    public function is_setup()
    {
        if ( isset( $_GET['initial-setup'] ) ) {
            return true;
        }
        return false;
    }
    
    /**
     * If we are in front area and not editing
     *
     * @return bool
     */
    public function is_front()
    {
        if ( !$this->is_editing() && !$this->is_setup() && !$this->is_preview() ) {
            return true;
        }
        return false;
    }
    
    /**
     *
     */
    public function migrate()
    {
        
        if ( !get_option( 'stax-version' ) ) {
            $this->db->migrate();
        } else {
            UpgradeDB::instance()->run();
        }
    
    }
    
    /**
     * @param array $stack
     * @param $props
     *
     * @return array
     */
    private function addCss( array $stack, $props )
    {
        foreach ( $stack as $viewport => $string ) {
            
            if ( $props->{$viewport . 'CSS'} ) {
                $stack[$viewport] .= $props->{$viewport . 'CSS'};
            } else {
                $stack[$viewport] .= $props->generalCSS;
            }
        
        }
        return $stack;
    }
    
    /**
     * @param array $stack
     * @param $props
     * @param $group
     *
     * @return array
     */
    private function addHtml( array $stack, $props, $group )
    {
        foreach ( $props->frontend as $viewport => $items ) {
            $position = 0;
            $belongs_to = null;
            $addHtml = [
                'desktop' => true,
                'tablet'  => true,
                'mobile'  => true,
            ];
            foreach ( $group as $item ) {
                
                if ( !$this->is_preview() ) {
                    
                    if ( $item->viewport === $viewport ) {
                        $position = intval( $item->position );
                        $belongs_to = ( isset( $item->belongs_to ) ? $item->belongs_to : null );
                    }
                    
                    if ( !(int) $item->visibility ) {
                        $addHtml[$item->viewport] = false;
                    }
                } else {
                    $position = intval( $item->{$viewport}->position );
                    $belongs_to = ( isset( $item->{$viewport}->belongs_to ) ? $item->belongs_to : null );
                }
            
            }
            if ( $position && $addHtml[$viewport] ) {
                
                if ( is_user_logged_in() ) {
                    $stack[$viewport][] = [
                        'content'    => do_shortcode( $items->auth ),
                        'position'   => $position,
                        'belongs_to' => $belongs_to,
                    ];
                } else {
                    $stack[$viewport][] = [
                        'content'    => do_shortcode( $items->not_auth ),
                        'position'   => $position,
                        'belongs_to' => $belongs_to,
                    ];
                }
            
            }
        }
        return $stack;
    }
    
    /**
     * @return array
     */
    public function get_zones_data()
    {
        $packs = [];
        
        if ( $this->is_preview() ) {
            $templateData = $this->get_preview_template();
            
            if ( !$templateData['default'] ) {
                $pack = @json_decode( $templateData['template']->pack );
            } else {
                $pack = $templateData['template']->pack;
            }
            
            if ( !$pack ) {
                return null;
            }
            
            if ( !$templateData['default'] ) {
                $group = $pack->group;
            } else {
                $group = new \stdClass();
                $group->containers = new \stdClass();
                $group->position = 1;
                $group->visibility = 1;
                foreach ( $pack->groups as $zoneUuid => $item ) {
                    foreach ( $item->containers as $key => $data ) {
                        $group->containers->{$key} = $data;
                    }
                }
            }
            
            $packs['body'][] = [
                'xpath'      => false,
                'selector'   => '.stax-editor-enabled',
                'groups'     => $group,
                'containers' => ( isset( $pack->containers ) ? $pack->containers : new \stdClass() ),
                'columns'    => ( isset( $pack->columns ) ? $pack->columns : new \stdClass() ),
                'elements'   => ( isset( $pack->elements ) ? $pack->elements : new \stdClass() ),
                'fonts'      => [],
                'zone_id'    => ( isset( $pack->zone ) && isset( $pack->zone->uuid ) ? $pack->zone->uuid : rand() ),
            ];
        } else {
            $zones = Model_Zones::instance()->getAllEnabled();
            foreach ( $zones as $zone ) {
                $conditions = $zone['condition'];
                
                if ( !empty($conditions) ) {
                    $condition_stack = [];
                    foreach ( $conditions as $condition ) {
                        
                        if ( $condition->category === 'general' ) {
                            $condition_stack[] = [
                                'callback' => 'general',
                                'target'   => '',
                                'type'     => '',
                            ];
                        } else {
                            
                            if ( $condition->category === 'archive' ) {
                                foreach ( $this->display_conditions_archive() as $archCondition ) {
                                    if ( $archCondition['tag'] === $condition->subcategory ) {
                                        $condition_stack[] = [
                                            'callback' => $archCondition['callback'],
                                            'target'   => '',
                                            'type'     => $condition->type,
                                        ];
                                    }
                                }
                            } else {
                                if ( $condition->category === 'single' ) {
                                    foreach ( $this->display_conditions_single() as $sglCondition ) {
                                        if ( $sglCondition['tag'] === $condition->subcategory ) {
                                            $condition_stack[] = [
                                                'callback' => $sglCondition['callback'],
                                                'target'   => $condition->target,
                                                'type'     => $condition->type,
                                            ];
                                        }
                                    }
                                }
                            }
                        
                        }
                    
                    }
                    $eligible = false;
                    $obj = get_queried_object();
                    foreach ( $condition_stack as $condition_item ) {
                        
                        if ( $condition_item['callback'] !== "general" ) {
                            if ( !empty($condition_item['target']) && !is_numeric( $condition_item['target'] ) ) {
                                continue;
                            }
                            
                            if ( $condition_item['type'] === 'exclude' ) {
                                if ( $condition_item['callback']() ) {
                                    
                                    if ( $condition_item['target'] ) {
                                        
                                        if ( !$obj instanceof \WP_Term ) {
                                            
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
                                        
                                        if ( !$obj instanceof \WP_Term ) {
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
                        $pack = @json_decode( $zone['pack'] );
                        if ( $pack ) {
                            if ( isset( $zone['selector']->{$this->theme_name} ) ) {
                                if ( (int) $zone['selector']->{$this->theme_name}->visibility ) {
                                    $packs[$zone['selector']->{$this->theme_name}->path][] = [
                                        'xpath'      => ( isset( $zone['selector']->{$this->theme_name}->xpath ) ? $zone['selector']->{$this->theme_name}->xpath : false ),
                                        'selector'   => $zone['selector']->{$this->theme_name}->path,
                                        'position'   => $zone['selector']->{$this->theme_name}->position,
                                        'containers' => $pack,
                                        'fonts'      => json_decode( $zone['fonts'] ),
                                        'zone_id'    => ( isset( $zone['uuid'] ) ? $zone['uuid'] : rand() ),
                                        'slug'       => ( isset( $zone['slug'] ) ? $zone['slug'] : '' ),
                                    ];
                                }
                            }
                        }
                    }
                
                }
            
            }
            foreach ( $packs as $key => $pack ) {
                usort( $packs[$key], function ( $a, $b ) {
                    return $a["position"] - $b["position"];
                } );
            }
        }
        
        return $this->buildZonesStack( $packs );
    }
    
    /**
     * @param array $pack
     *
     * @return array
     */
    private function buildZonesStack( array $pack )
    {
        $containers = [];
        foreach ( $pack as $selector => $stack ) {
            foreach ( $stack as $item ) {
                $has_content = false;
                $css = [
                    'general' => '',
                    'desktop' => '',
                    'tablet'  => '',
                    'mobile'  => '',
                ];
                $html = [
                    'desktop' => [],
                    'tablet'  => [],
                    'mobile'  => [],
                ];
                $existingColumns = [];
                $existingElements = [];
                
                if ( $this->is_preview() ) {
                    foreach ( $item['groups']->containers as $containerUuid => $container ) {
                        $prContainer = $item['containers']->{$containerUuid};
                        if ( !$prContainer ) {
                            continue;
                        }
                        $css = $this->addCss( $css, $prContainer );
                        $html = $this->addHtml( $html, $prContainer, $container );
                        foreach ( $container->viewport as $v => $viewport ) {
                            foreach ( $viewport->columns as $columnUuid => $column ) {
                                $prColumn = $item['columns']->{$columnUuid};
                                if ( !$prColumn ) {
                                    continue;
                                }
                                if ( !in_array( $columnUuid, $existingColumns ) ) {
                                    $css = $this->addCss( $css, $prColumn );
                                }
                                foreach ( $column->elements as $elementUuid => $element ) {
                                    $prElement = $item['elements']->{$elementUuid};
                                    if ( !$prElement ) {
                                        continue;
                                    }
                                    if ( !in_array( $elementUuid, $existingElements ) ) {
                                        $css = $this->addCss( $css, $prElement );
                                    }
                                    $existingElements[] = $elementUuid;
                                }
                                $existingColumns[] = $columnUuid;
                            }
                        }
                    }
                } else {
                    foreach ( $item['containers'] as $uuid ) {
                        $container = Model_Container::instance()->get( $uuid );
                        
                        if ( $container ) {
                            $containerGroup = Model_ContainerViewport::instance()->get( $container->uuid );
                            if ( empty($containerGroup) ) {
                                continue;
                            }
                            $containerProps = json_decode( $container->properties );
                            $css = $this->addCss( $css, $containerProps );
                            $html = $this->addHtml( $html, $containerProps, $containerGroup );
                            $columnsGroup = Model_ContainerItems::instance()->getByContainerUuid( $container->uuid );
                            foreach ( $columnsGroup as $colGroup ) {
                                if ( !(int) $colGroup->visibility ) {
                                    continue;
                                }
                                $colProps = Model_Columns::instance()->get( $colGroup->column_uuid );
                                if ( !$colProps ) {
                                    continue;
                                }
                                if ( !in_array( $colGroup->column_uuid, $existingColumns ) ) {
                                    $css = $this->addCss( $css, @json_decode( $colProps->properties ) );
                                }
                                $elementsGroup = @json_decode( $colGroup->elements );
                                if ( !empty((array) $elementsGroup) ) {
                                    $has_content = true;
                                }
                                foreach ( $elementsGroup as $elUuid => $elGroup ) {
                                    if ( !(int) $elGroup->visibility ) {
                                        continue;
                                    }
                                    $elementProps = Model_Elements::instance()->get( $elUuid );
                                    if ( !$elementProps ) {
                                        continue;
                                    }
                                    if ( !in_array( $elUuid, $existingElements ) ) {
                                        $css = $this->addCss( $css, @json_decode( $elementProps->properties ) );
                                    }
                                    $existingElements[] = $elUuid;
                                }
                                $existingColumns[] = $colGroup->column_uuid;
                            }
                        }
                    
                    }
                }
                
                foreach ( $html as $key => $value ) {
                    usort( $html[$key], function ( $a, $b ) {
                        return $a["position"] - $b["position"];
                    } );
                }
                foreach ( $html as $i => $value ) {
                    foreach ( $value as $j => $content_data ) {
                        
                        if ( $content_data['belongs_to'] ) {
                            unset( $html[$i][$j] );
                            $html[$i][] = $content_data;
                        }
                    
                    }
                }
                
                if ( !$has_content && is_super_admin() ) {
                    $replace_data = sprintf( '<div class="stax-no-content">' . '<div class="container"><div class="row"><div class="col">' . '<a href="%s">' . esc_html__( 'This Zone is empty. Start building with Stax', 'stax' ) . '</a>' . '</div></div></div>' . '</div>', add_query_arg( 'stax-editor', '' ) );
                    foreach ( $html as $resolution => $data ) {
                        foreach ( $data as $k => $resolution_data ) {
                            $html[$resolution][$k]['content'] = $replace_data;
                        }
                    }
                }
                
                $containers[$selector][] = [
                    'xpath'    => $item['xpath'],
                    'selector' => $item['selector'],
                    'css'      => $css,
                    'fonts'    => @json_decode( $item['fonts'] ),
                    'html'     => $html,
                    'zone_id'  => $item['zone_id'],
                    'slug'     => ( isset( $item['slug'] ) ? $item['slug'] : '' ),
                ];
            }
        }
        return $containers;
    }
    
    /**
     * Get Zone HTML.
     *
     * @param string $zone_slug
     *
     * @return void
     */
    public function the_zone_html( $zone_slug = '' )
    {
        if ( !$zone_slug ) {
            $zone_slug = 'header';
        }
        $output = '';
        $zones_data = $this->get_zones_data();
        $resolution = ( wp_is_mobile() ? 'mobile' : 'desktop' );
        foreach ( $zones_data as $places ) {
            foreach ( $places as $zone ) {
                
                if ( $zone['slug'] === $zone_slug ) {
                    $sub_containers = [];
                    foreach ( $zone['html'][$resolution] as $content ) {
                        
                        if ( !$content['belongs_to'] ) {
                            $output .= $content['content'];
                        } else {
                            $sub_containers[] = [
                                'belongs_to' => $content['belongs_to'],
                                'content'    => $content['content'],
                            ];
                        }
                    
                    }
                    
                    if ( !empty($sub_containers) ) {
                        preg_match_all( "'{{elementDropzone-(.*?)}}'si", $output, $matches );
                        foreach ( $matches[1] as $uuid ) {
                            $output = $this->add_subcontainer( $sub_containers, $output, $uuid );
                        }
                    }
                
                }
            
            }
        }
        $output = apply_filters( 'stax_the_zone_html', $output, $zone_slug );
        echo  $output ;
    }
    
    /**
     * @return array|bool|mixed|null|object|string
     */
    private function get_preview_template()
    {
        if ( !$this->is_preview() ) {
            return null;
        }
        $template = null;
        $default = false;
        
        if ( strpos( $_GET['section'], 'stax_tpl_' ) === 0 ) {
            $id = str_replace( 'stax_tpl_', '', $_GET['section'] );
            $template = Templates::instance()->get_by_id( $id );
            $default = true;
        } else {
            $template = Model_Templates::instance()->getById( (int) $_GET['section'] );
        }
        
        return [
            'template' => $template,
            'default'  => $default,
        ];
    }
    
    /**
     * @return array
     */
    public function display_conditions_archive()
    {
        $conditions = [];
        foreach ( $this->display_conditions() as $condition ) {
            if ( in_array( 'archive', $condition['category'] ) ) {
                $conditions[] = $condition;
            }
        }
        return $conditions;
    }
    
    /**
     * @return array
     */
    public function display_conditions_single()
    {
        $conditions = [];
        foreach ( $this->display_conditions() as $condition ) {
            if ( in_array( 'single', $condition['category'] ) ) {
                $conditions[] = $condition;
            }
        }
        return $conditions;
    }
    
    /**
     * @return array
     */
    public function display_conditions()
    {
        $data = [
            [
            'tag'      => 'front',
            'callback' => 'is_front_page',
            'fetch'    => 'get_page_front',
            'category' => [ 'single' ],
        ],
            [
            'tag'      => 'author-page',
            'callback' => 'is_author',
            'fetch'    => 'get_page_author',
            'category' => [ 'archive' ],
        ],
            [
            'tag'      => 'date-page',
            'callback' => 'is_date',
            'fetch'    => 'get_page_date',
            'category' => [ 'archive' ],
        ],
            [
            'tag'      => 'search-result',
            'callback' => 'is_search',
            'fetch'    => 'get_page_search',
            'category' => [ 'archive' ],
        ],
            [
            'tag'      => 'page',
            'callback' => 'is_page',
            'fetch'    => 'get_page_single',
            'category' => [ 'single' ],
        ],
            [
            'tag'      => 'posts',
            'callback' => 'is_single',
            'fetch'    => 'get_page_post',
            'category' => [ 'single' ],
        ],
            [
            'tag'      => 'categories',
            'callback' => 'is_category',
            'fetch'    => 'get_page_category',
            'category' => [ 'archive' ],
        ],
            [
            'tag'      => 'tag',
            'callback' => 'is_tag',
            'fetch'    => 'get_page_tag',
            'category' => [ 'archive' ],
        ],
            [
            'tag'      => '404',
            'callback' => 'is_404',
            'fetch'    => 'get_page_notfound',
            'category' => [ 'single' ],
        ]
        ];
        /* TODO Add post types & tax support */
        /*$args = array(
        			'public'   => true,
        			'_builtin' => false
        		);
        		$post_types = get_post_types( $args, 'objects', 'and' );
        
        		foreach ( $post_types  as $post_type ) {
        
        			// Post type single page.
        			$data[] =             [
        				'label'      => $post_type->label,
        				'tag'      => $post_type->name,
        				'callback' => 'is_singular("'. $post_type->name .'")',
        				'fetch'    => 'get_post_type',
        				'category' => [ 'single' ]
        			];
        
        			// Post type archive page.
        			$data[] =             [
        				'label'      => $post_type->label,
        				'tag'      => $post_type->name,
        				'callback' => 'is_post_type_archive("'. $post_type->name .'")',
        				'fetch'    => 'get_post_type_archive',
        				'category' => [ 'archive' ]
        			];
        		}
        
        		$args = array(
        			'public'   => true,
        			'_builtin' => false
        
        		);
        		$taxonomies = get_taxonomies( $args, 'objects', 'and' );
        		if ( $taxonomies ) {
        			foreach ( $taxonomies  as $taxonomy ) {
        				$data[] =             [
        					'label'      => $taxonomy->label,
        					'tag'      => $taxonomy->name,
        					'callback' => 'is_tax("'. $taxonomy->name .'")',
        					'fetch'    => 'get_taxonomy',
        					'category' => [ 'archive' ]
        				];
        			}
        		}*/
        return $data;
    }
    
    /**
     * Convert $selector into an XPath string.
     *
     * @param $selector
     *
     * @return mixed
     */
    public function selector_to_xpath( $selector )
    {
        // remove spaces around operators
        $selector = preg_replace( '/\\s*>\\s*/', '>', $selector );
        $selector = preg_replace( '/\\s*~\\s*/', '~', $selector );
        $selector = preg_replace( '/\\s*\\+\\s*/', '+', $selector );
        $selector = preg_replace( '/\\s*,\\s*/', ',', $selector );
        $selectors = preg_split( '/\\s+(?![^\\[]+\\])/', $selector );
        foreach ( $selectors as &$selector ) {
            // ,
            $selector = preg_replace( '/,/', '|descendant-or-self::', $selector );
            // input:checked, :disabled, etc.
            $selector = preg_replace( '/(.+)?:(checked|disabled|required|autofocus)/', '\\1[@\\2="\\2"]', $selector );
            // input:autocomplete, :autocomplete
            $selector = preg_replace( '/(.+)?:(autocomplete)/', '\\1[@\\2="on"]', $selector );
            // input:button, input:submit, etc.
            $selector = preg_replace( '/:(text|password|checkbox|radio|button|submit|reset|file|hidden|image|datetime|datetime-local|date|month|time|week|number|range|email|url|search|tel|color)/', 'input[@type="\\1"]', $selector );
            // foo[id]
            $selector = preg_replace( '/(\\w+)\\[([_\\w-]+[_\\w\\d-]*)\\]/', '\\1[@\\2]', $selector );
            // [id]
            $selector = preg_replace( '/\\[([_\\w-]+[_\\w\\d-]*)\\]/', '*[@\\1]', $selector );
            // foo[id=foo]
            $selector = preg_replace( '/\\[([_\\w-]+[_\\w\\d-]*)=[\'"]?(.*?)[\'"]?\\]/', '[@\\1="\\2"]', $selector );
            // [id=foo]
            $selector = preg_replace( '/^\\[/', '*[', $selector );
            // div#foo
            $selector = preg_replace( '/([_\\w-]+[_\\w\\d-]*)\\#([_\\w-]+[_\\w\\d-]*)/', '\\1[@id="\\2"]', $selector );
            // #foo
            $selector = preg_replace( '/\\#([_\\w-]+[_\\w\\d-]*)/', '*[@id="\\1"]', $selector );
            // div.foo
            $selector = preg_replace( '/([_\\w-]+[_\\w\\d-]*)\\.([_\\w-]+[_\\w\\d-]*)/', '\\1[contains(concat(" ",@class," ")," \\2 ")]', $selector );
            // .foo
            $selector = preg_replace( '/\\.([_\\w-]+[_\\w\\d-]*)/', '*[contains(concat(" ",@class," ")," \\1 ")]', $selector );
            // div:first-child
            $selector = preg_replace( '/([_\\w-]+[_\\w\\d-]*):first-child/', '*/\\1[position()=1]', $selector );
            // div:last-child
            $selector = preg_replace( '/([_\\w-]+[_\\w\\d-]*):last-child/', '*/\\1[position()=last()]', $selector );
            // :first-child
            $selector = str_replace( ':first-child', '*/*[position()=1]', $selector );
            // :last-child
            $selector = str_replace( ':last-child', '*/*[position()=last()]', $selector );
            // :nth-last-child
            $selector = preg_replace( '/:nth-last-child\\((\\d+)\\)/', '[position()=(last() - (\\1 - 1))]', $selector );
            // div:nth-child
            $selector = preg_replace( '/([_\\w-]+[_\\w\\d-]*):nth-child\\((\\d+)\\)/', '*/*[position()=\\2 and self::\\1]', $selector );
            // :nth-child
            $selector = preg_replace( '/:nth-child\\((\\d+)\\)/', '*/*[position()=\\1]', $selector );
            // :contains(Foo)
            $selector = preg_replace( '/([_\\w-]+[_\\w\\d-]*):contains\\((.*?)\\)/', '\\1[contains(string(.),"\\2")]', $selector );
            // >
            $selector = preg_replace( '/>/', '/', $selector );
            // ~
            $selector = preg_replace( '/~/', '/following-sibling::', $selector );
            // +
            $selector = preg_replace( '/\\+([_\\w-]+[_\\w\\d-]*)/', '/following-sibling::\\1[position()=1]', $selector );
            $selector = str_replace( ']*', ']', $selector );
            $selector = str_replace( ']/*', ']', $selector );
        }
        // ' '
        $selector = implode( '/descendant::', $selectors );
        $selector = 'descendant-or-self::' . $selector;
        // :scope
        $selector = preg_replace( '/(((\\|)?descendant-or-self::):scope)/', '.\\3', $selector );
        // $element
        $sub_selectors = explode( ',', $selector );
        foreach ( $sub_selectors as $key => $sub_selector ) {
            $parts = explode( '$', $sub_selector );
            $sub_selector = array_shift( $parts );
            
            if ( count( $parts ) && preg_match_all( '/((?:[^\\/]*\\/?\\/?)|$)/', $parts[0], $matches ) ) {
                $results = $matches[0];
                $results[] = str_repeat( '/..', count( $results ) - 2 );
                $sub_selector .= implode( '', $results );
            }
            
            $sub_selectors[$key] = $sub_selector;
        }
        $selector = implode( ',', $sub_selectors );
        return $selector;
    }
    
    /**
     * @param $vars
     *
     * @return false|string
     */
    public function init_menu_shortcode( $vars )
    {
        if ( !isset( $vars['slug'] ) ) {
            return esc_html__( 'No menu found', 'stax' );
        }
        $menu = Menus::instance()->getBySlug( [
            'slug' => $vars['slug'],
        ], false );
        return $menu;
    }
    
    /**
     *
     */
    public function init_admin_dashboard()
    {
        add_menu_page(
            'STAX Builder',
            'Stax Builder',
            'manage_options',
            'stax',
            [ $this, 'admin_template' ],
            STAX_ASSETS_URL . 'images/stax-16.png',
            99
        );
    }
    
    /**
     *
     */
    public function admin_template()
    {
        if ( !current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        require 'admin/index.php';
    }
    
    /**
     * Add script to disable click actions
     *
     * @return void
     */
    public function disable_links()
    {
        wp_register_script(
            'link-disabled',
            STAX_ASSETS_URL . 'js/link-disabled.js',
            [ 'jquery' ],
            false
        );
        wp_enqueue_script( 'link-disabled' );
    }
    
    public function add_subcontainer( $sub_containers, $content, $uuid )
    {
        foreach ( $sub_containers as $item ) {
            
            if ( $item['belongs_to'] === $uuid ) {
                $content = str_replace( '{{elementDropzone-' . $uuid . '}}', '<div class="container-items">' . $item['content'] . '</div>', $content );
                preg_match_all( "'{{elementDropzone-(.*?)}}'si", $content, $matches );
                foreach ( $matches[1] as $uuid ) {
                    $content = $this->add_subcontainer( $sub_containers, $content, $uuid );
                }
            }
        
        }
        return $content;
    }

}
Plugin::instance();