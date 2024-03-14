<?php

/**
 * Yoast Seo class
 *
 * @since 1.9.4
 *
 */
class DMS_Seo_Yoast extends Abstract_Seo
{
    /**
     * Sitemap index path
     */
    const  SITEMAP_FILENAME = 'sitemap_index.xml' ;
    /**
     * Pattern for sitemap objects. Ex: post-sitemap.xml, page-sitemap2.xml, etc ...
     */
    const  SITEMAP_OBJECT_PATTERN = '/([^\\/]+?)-sitemap([0-9]+)?\\.xml$/' ;
    /**
     * DMS instance to be used here
     *
     * @var DMS
     */
    protected  $dms_instance ;
    /**
     * Flag for seo options
     *
     * @var string
     */
    private  $options_per_domain ;
    /**
     * Flag for sitemap per domain
     *
     * @var string
     */
    private  $sitemap_per_domain ;
    /**
     * DMS metas to override yoast same ones
     *
     * @var array
     */
    public static  $dms_metas = array(
        'title',
        'description',
        'keywords',
        'opengraph-title',
        'opengraph-description',
        'opengraph-image-id',
        'opengraph-image',
        'twitter-title',
        'twitter-description',
        'twitter-image-id',
        'twitter-image'
    ) ;
    /**
     * post_meta table meta_key prefix
     *
     * @var string
     */
    public static  $meta_prefix = '_dms_yoast_wpseo_' ;
    /**
     * Yoast's settings form input name prefix
     *
     * @var string
     */
    public static  $form_prefix = 'dms_yoast_wpseo_' ;
    /**
     * Separator to apply from the end
     *
     * @var string
     */
    public static  $domain_separator = '-' ;
    /**
     * Holds the base path ( sitemap's part excluded )
     *
     * @var string
     */
    protected  $path_without_sitemap ;
    /**
     * Holds requested mapping
     *
     * @var object
     */
    protected  $mapping ;
    /**
     * Holds main mapping
     *
     * @var object
     */
    protected  $main_mapping ;
    /**
     * Holds the information that sitemap will be overridden
     *
     * @var bool
     */
    private  $interacting_with_sitemap = false ;
    /**
     * Constructor
     */
    private function __construct()
    {
        $this->dms_instance = DMS::getInstance();
        $this->setFlags();
    }
    
    /**
     * Setup flags to know how to work with yoast
     *
     * @return void
     */
    private function setFlags()
    {
        $this->options_per_domain = get_option( 'dms_seo_options_per_domain' );
        $this->sitemap_per_domain = get_option( 'dms_seo_sitemap_per_domain' );
    }
    
    /**
     * Get singleton instance.
     *
     * @return DMS_Seo_Yoast
     */
    public static function getInstance()
    {
        if ( !isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * For initialize admin, and main part.
     *
     * @return void
     */
    public static function run()
    {
        $yoast = DMS_Seo_Yoast::getInstance();
        add_action( 'init', array( $yoast, 'init' ) );
        add_action( 'admin_init', array( $yoast, 'adminInit' ) );
    }
    
    /**
     * For running sitemap part,
     * and for running head changing part.
     *
     * @return void
     */
    public function init()
    {
        if ( $this->dms_instance->getDomainPathMatch() && !empty($this->options_per_domain) && !is_admin() ) {
            add_action( 'wp', array( $this, 'overrideHead' ), 11 );
        }
        if ( !empty($this->sitemap_per_domain) ) {
            $this->runSitemap();
        }
    }
    
    /**
     * Adding an actions for saving a meta and for yoast tab generating  .
     *
     * @return void
     */
    public function adminInit()
    {
        // Showing tabs always no matter option is active or no
        add_filter( 'yoast_free_additional_metabox_sections', function () {
            return $this->addTab( 'Post' );
        } );
        add_filter( 'yoast_free_additional_taxonomy_metabox_sections', function ( $arg1 ) {
            return $this->addTab( 'Taxonomy', $arg1 );
        }, 2 );
        // Avoid enqueueing in dms page
        
        if ( !isset( $_GET['page'] ) || $_GET['page'] !== $this->dms_instance->plugin_name ) {
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        }
        
        // Save actions
        add_action( 'save_post', array( $this, 'saveMetaForPost' ) );
        add_action( 'edit_term_taxonomy', array( $this, 'saveMetaForTaxonomy' ) );
    }
    
    /**
     * Register and queue admin styles
     *
     * @return void
     */
    public function admin_styles()
    {
        wp_register_style(
            'dms-yoast-min-css',
            $this->dms_instance->plugin_url . 'assets/css/dms-yoast.min.css',
            array(),
            $this->dms_instance->version,
            'all'
        );
        wp_enqueue_style( 'dms-yoast-min-css' );
    }
    
    /**
     * Register and queue admin scripts
     *
     * @return void
     */
    public function admin_scripts()
    {
        /**
         * Collect data to localize
         * translations for JS
         * premium flag
         */
        $DMS = $this->dms_instance;
        $dms_fs = $DMS->dms_fs;
        $localize_data = array(
            'nonce'        => wp_create_nonce( 'dms_nonce' ),
            'scheme'       => DMS_Helper::getScheme(),
            'ajax_url'     => admin_url( 'admin-ajax.php' ),
            'translations' => include_once $this->dms_instance->plugin_dir . 'assets/js/localizations/js-translations.php',
            'is_premium'   => (int) $dms_fs->can_use_premium_code__premium_only(),
            'upgrade_url'  => $dms_fs->get_upgrade_url(),
        );
        // Register main js dependencies
        wp_register_script(
            'dms-yoast-js',
            $DMS->plugin_url . 'assets/js/dms-yoast.js',
            array( 'jquery' ),
            $DMS->version
        );
        wp_enqueue_script( 'dms-yoast-js' );
        // Include js data into dms-js
        wp_localize_script( 'dms-yoast-js', 'dms_yoast_fs', $localize_data );
    }
    
    /**
     * Adding an actions for changing a head content. Options per domain should be enabled.
     *
     * @return void
     */
    public function overrideHead()
    {
        $dms_instance = $this->dms_instance;
        
        if ( !empty($dms_instance->active_mapping) && !empty($dms_instance->real_requested_object_id) && $dms_instance->dms_fs->can_use_premium_code__premium_only() ) {
            $host_and_path = DMS_Helper::getHostPlusPath( $dms_instance->active_mapping );
            // Collect override data
            $this->data = $this->getData( $host_and_path, $dms_instance->real_requested_object_id );
            // Part for changing a <head> meta values.
            add_filter( 'wpseo_title', array( $this, 'changeTitle' ) );
            add_filter( 'wpseo_metadesc', array( $this, 'changeDesc' ) );
            add_filter( 'wpseo_metakeywords', array( $this, 'changeKeyword' ) );
            add_filter( 'wpseo_opengraph_title', array( $this, 'changeOgTitle' ) );
            add_filter( 'wpseo_opengraph_desc', array( $this, 'changeOgDesc' ) );
            add_filter( 'wpseo_opengraph_image', array( $this, 'changeOgImage' ) );
            add_filter( 'wpseo_opengraph_url', array( $this, 'changeOgUrl' ) );
            add_filter( 'wpseo_twitter_title', array( $this, 'changeTwitTitle' ) );
            add_filter( 'wpseo_twitter_description', array( $this, 'changeTwitDesc' ) );
            add_filter( 'wpseo_twitter_image', array( $this, 'changeTwitImage' ) );
            add_filter( 'wpseo_canonical', array( $this, 'changeCanonical' ) );
        }
    
    }
    
    /**
     * Checks if
     *
     * @return void
     */
    public function runSitemap()
    {
        
        if ( !empty($this->sitemap_per_domain) ) {
            // Path for sitemap_index.xml
            $DMS = $this->dms_instance;
            $path = $DMS->getPath();
            if ( !empty($path) ) {
                
                if ( $DMS->is_not_base_host ) {
                    // Not required to mean that the host is mapped in our end.
                    // Now check if path ends by sitemap
                    
                    if ( $DMS->getPath() === self::SITEMAP_FILENAME ) {
                        // Main sitemap requested without extra path.Seems to do nothing
                        $this->path_without_sitemap = '';
                    } elseif ( preg_replace( self::SITEMAP_OBJECT_PATTERN, '', $DMS->getPath() ) === '' ) {
                        // Object sitemap requested without extra path.Seems to do nothing
                        $this->path_without_sitemap = '';
                    } elseif ( DMS_Helper::endsWith( $DMS->getPath(), '/' . self::SITEMAP_FILENAME ) ) {
                        // Maybe our mapping [ host + path + 'sitemap_index.xml' ] requested.
                        $this->path_without_sitemap = str_replace( self::SITEMAP_FILENAME, '', $DMS->getPath() );
                    } elseif ( preg_match( self::SITEMAP_OBJECT_PATTERN, $DMS->getPath() ) ) {
                        $this->path_without_sitemap = preg_replace( self::SITEMAP_OBJECT_PATTERN, '', $DMS->getPath() );
                    }
                    
                    // If we have any mapping with requested params, then move further
                    
                    if ( isset( $this->path_without_sitemap ) ) {
                        $this->path_without_sitemap = trim( $this->path_without_sitemap, '/' );
                        $mapping = DMS_Helper::getMappingByHostAndPath( $this->dms_instance->wpdb, $this->dms_instance->getDomain(), $this->path_without_sitemap );
                    }
                    
                    
                    if ( !empty($mapping) ) {
                        $this->interacting_with_sitemap = true;
                        $this->mapping = $mapping;
                        $this->main_mapping = $DMS->getMainMapping();
                        /**
                         * We have mapping connected with domain+path, then organize sitemap for it.
                         * 1. add_rewrite_rules
                         * 2. setup filters
                         */
                        $this->setupSitemapFilters();
                    }
                
                } elseif ( !empty($DMS->force_site_visitors) ) {
                    $this->interacting_with_sitemap = true;
                    $this->main_mapping = $DMS->getMainMapping();
                    // Rewrite each object entry in the sitemap
                    add_filter(
                        'wpseo_sitemap_entry',
                        function ( $url, $type, $object ) {
                        return $this->rewriteEntry( $url, $type, $object );
                    },
                        10,
                        3
                    );
                    /**
                     * First links. For post_type=page it is page_on_front.
                     * For any other post_type it is archive page.
                     */
                    add_filter(
                        'wpseo_sitemap_post_type_first_links',
                        function ( $links, $post_type ) {
                        // TODO, later we could check if that certain links are mapped in our side and allow them.
                        return array();
                    },
                        10,
                        2
                    );
                }
            
            }
        }
    
    }
    
    /**
     * This function for changing a stylesheet href,
     * and for adding a filter for excluding a terms/posts by id.
     *
     * @return void
     */
    public function setupSitemapFilters()
    {
        $DMS = $this->dms_instance;
        // Overriding the xsl stylesheet url.
        add_filter( 'wpseo_stylesheet_url', function ( $stylesheet ) use( $DMS ) {
            // TODO include path. along side putting xsl add_rewrite_rule
            // TODO more proper replace will be good. Regex including href="
            return str_replace( DMS_Helper::getBaseHost(), $DMS->getDomain(), $stylesheet );
        }, 90 );
        // Rewrite index links -> post-sitemap.xml, etc ...
        add_filter( 'wpseo_sitemap_index_links', array( $this, 'rewriteIndexLinks' ) );
        // Rewrite each object entry in the sitemap
        add_filter(
            'wpseo_sitemap_entry',
            function ( $url, $type, $object ) {
            return $this->rewriteEntry(
                $url,
                $type,
                $object,
                true
            );
        },
            10,
            3
        );
        // Exclude objects without any mapping. Only if global current mapping is not main mapping and global mapping is disabled.
        
        if ( !($this->mapping->id == $this->main_mapping->id && !empty($DMS->global_mapping)) ) {
            add_filter( 'wpseo_exclude_from_sitemap_by_post_ids', array( $this, 'excludeSitemapPosts' ) );
            add_filter( 'wpseo_exclude_from_sitemap_by_term_ids', array( $this, 'excludeSitemapTerms' ) );
        }
        
        /**
         * First links. For post_type=page it is page_on_front.
         * For any other post_type it is archive page.
         */
        add_filter(
            'wpseo_sitemap_post_type_first_links',
            function ( $links, $post_type ) {
            // TODO, later we could check if that certain links are mapped in our side and allow them.
            return array();
        },
            10,
            2
        );
    }
    
    /**
     * Rewrite entry
     *
     * @param  array  $url
     * @param  string  $type
     * @param  WP_Post|WP_Term|WP_User  $object
     * @param  bool  $removeExisting
     *
     * @return array
     */
    public function rewriteEntry(
        $url,
        $type,
        $object,
        $removeExisting = false
    )
    {
        $DMS = $this->dms_instance;
        
        if ( $object instanceof WP_Post ) {
            $key = $object->ID;
        } elseif ( $object instanceof WP_Term ) {
            
            if ( $object->taxonomy == 'category' ) {
                $key = $object->taxonomy . '-' . $object->slug;
            } else {
                $key = implode( '#', [ $object->taxonomy, $object->slug, get_taxonomy( $object->taxonomy )->object_type[0] ] );
            }
        
        }
        
        // $key non-emptiness is must for proceeding further
        
        if ( !empty($key) ) {
            $mapping = DMS_Helper::getMatchingHostByValue( $DMS->wpdb, $key );
            
            if ( $DMS->is_not_base_host ) {
                
                if ( empty($mapping) && !empty($DMS->global_mapping) && !empty($this->main_mapping) && $this->main_mapping->id == $this->mapping->id ) {
                    $mapping = $this->main_mapping;
                } elseif ( !empty($mapping) && !empty($DMS->global_mapping) && !empty($this->main_mapping) && $this->main_mapping->id == $this->mapping->id && $this->main_mapping->id != $mapping->id ) {
                    $mapping = null;
                }
            
            } elseif ( !empty($DMS->force_site_visitors) ) {
                if ( empty($mapping) && !empty($DMS->global_mapping) && !empty($this->main_mapping) ) {
                    $mapping = $this->main_mapping;
                }
            }
            
            
            if ( !empty($mapping->host) ) {
                $replace_with = $mapping->host . (( !empty($mapping->path) ? '/' . $mapping->path : '' ));
                $url_loc_without_scheme = preg_replace( "~^(https?://)~i", '', $url['loc'] );
                
                if ( strpos( $url_loc_without_scheme, $replace_with ) !== 0 ) {
                    $replaced = true;
                    $url['loc'] = str_ireplace( DMS_Helper::getBaseHost(), $replace_with, $url['loc'] );
                    foreach ( $url['images'] as $key => &$value ) {
                        $value['src'] = str_ireplace( DMS_Helper::getBaseHost(), $replace_with, $value['src'] );
                    }
                }
            
            }
        
        }
        
        /**
         * Designed to remove rows which has no connection with mapping.
         * In case the page viewed with our mapping.
         */
        if ( empty($replaced) && $removeExisting ) {
            return null;
        }
        return $url;
    }
    
    /**
     * Function for changing an index_sitemap links.
     * Changing a main url to current mapped host url.
     *
     * @param $links
     *
     * @return mixed
     */
    public function rewriteIndexLinks( $links )
    {
        $DMS = $this->dms_instance;
        foreach ( $links as &$val ) {
            $val['loc'] = str_ireplace( DMS_Helper::getBaseHost(), rtrim( $DMS->getDomain() . '/' . (( !empty($this->path_without_sitemap) ? $this->path_without_sitemap : '' )), '/' ), $val['loc'] );
        }
        return $links;
    }
    
    /**
     * This function for excluding a post types from sitemap which is not mapped.
     *
     * @return array
     */
    public function excludeSitemapPosts()
    {
        $wpdb = $this->dms_instance->wpdb;
        
        if ( !empty($this->mapping->id) ) {
            $post_ids = $wpdb->get_col( $wpdb->prepare( "SELECT `id` FROM `" . $wpdb->prefix . "posts` WHERE `id` NOT IN\n\t\t                                    (SELECT `value` FROM `" . $wpdb->prefix . "dms_mapping_values` WHERE `host_id`=%d)\n\t\t                                     AND `post_status` = 'publish' ", $this->mapping->id ) );
            return ( !empty($post_ids) ? $post_ids : [] );
        }
        
        return [];
    }
    
    /**
     * This function for excluding a terms from sitemap which is not mapped.
     *
     * @return array
     */
    public function excludeSitemapTerms()
    {
        $wpdb = $this->dms_instance->wpdb;
        
        if ( !empty($this->mapping->id) ) {
            $values = $wpdb->get_col( $wpdb->prepare( "SELECT `value` FROM `" . $wpdb->prefix . "dms_mapping_values` WHERE `host_id`=%d AND `value` NOT REGEXP '^[0-9]+\$';", $this->mapping->id ) );
            foreach ( $values as $value ) {
                $term_taxonomy = DMS::getTaxonomyTermFromValue( $value );
                $term = get_term_by( 'slug', $term_taxonomy[1], $term_taxonomy[0] );
                $ids[] = $term->term_id;
            }
            // If ids is empty, we should exclude all terms
            $where = ( empty($ids) || !is_array( $ids ) ? "1" : "`term_id` NOT IN (" . esc_sql( implode( ',', $ids ) ) . ")" );
            $term_ids = $wpdb->get_col( "SELECT `term_id` FROM `" . $wpdb->prefix . "terms` WHERE {$where}" );
            return ( !empty($term_ids) ? array_map( 'intval', $term_ids ) : [] );
        }
        
        return [];
    }
    
    /**
     * @return array[]
     *
     * This function for adding a tab in yoast seo, for domains.
     */
    public function addTab( $type, $arg1 = null )
    {
        return [ [
            'name'         => '_dms_yoast_wpseo_tab',
            'link_content' => '<span class="dashicons-before dashicons-admin-site-alt3" style="margin-right: 8px"></span>' . __( 'Domain Mapping', $this->dms_instance->plugin_name ),
            'content'      => $this->getTabContent( $type, $arg1 = null ),
        ] ];
    }
    
    /**
     * Get tab content
     *
     * @param $type
     * @param $arg1
     *
     * @return false|string
     */
    public function getTabContent( $type, $arg1 = null )
    {
        
        if ( method_exists( $this, 'get' . $type . 'TabContent' ) ) {
            return $this->{'get' . $type . 'TabContent'}( $arg1 );
        } else {
            ob_start();
            require_once $this->dms_instance->plugin_dir . '/templates/seo/yoast/settings.php';
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    
    }
    
    /**
     * Get post tab content
     *
     * @param $arg1
     *
     * @return false|string
     */
    public function getPostTabContent( $arg1 )
    {
        global  $post ;
        
        if ( !empty($post->ID) ) {
            $DMS = $this->dms_instance;
            $mappings = DMS_Helper::getMappingsByValue( $DMS->wpdb, $post->ID, ARRAY_N );
            $data = [];
            foreach ( $mappings as $key => $item ) {
                // Get host + path as an url part. Only if $item is numeric array
                $host_path = DMS_Helper::getHostPlusPath( $item );
                // Get meta data
                $data[] = [
                    'host_path' => $host_path,
                    'meta_data' => $this->getDataPost( $host_path, $post->ID ),
                ];
            }
            ob_start();
            require_once $DMS->plugin_dir . '/templates/seo/yoast/settings.php';
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
        
        return '';
    }
    
    /**
     * Get taxonomy tab content
     *
     * @param $arg1
     *
     * @return false|string
     */
    public function getTaxonomyTabContent( $arg1 )
    {
        $current_screen = get_current_screen();
        global  $tag_ID ;
        
        if ( !empty($current_screen) && is_a( $current_screen, 'WP_Screen' ) && !empty($current_screen->taxonomy) && !empty($tag_ID) ) {
            $DMS = $this->dms_instance;
            $term = get_term( $tag_ID, $current_screen->taxonomy );
            // Check if posts category or cpt taxonomy
            
            if ( $current_screen->taxonomy === 'category' ) {
                $value = 'category-' . $term->slug;
            } else {
                $value = $current_screen->taxonomy . '#' . $term->slug . '#' . $current_screen->post_type;
            }
            
            $mappings = DMS_Helper::getMappingsByValue( $DMS->wpdb, $value, ARRAY_N );
            $data = [];
            foreach ( $mappings as $key => $item ) {
                // Get host + path as an url part. Only if $item is numeric array
                $host_path = DMS_Helper::getHostPlusPath( $item );
                // Get meta data
                $data[] = [
                    'host_path' => $host_path,
                    'meta_data' => $this->getDataTaxonomy( $host_path, $tag_ID ),
                ];
            }
            ob_start();
            require_once $DMS->plugin_dir . '/templates/seo/yoast/settings.php';
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
        
        return '';
    }
    
    /**
     * Designed to save data from post editor
     *
     * @param  int  $post_id
     *
     * @return void
     */
    public function saveMetaForPost( $post_id )
    {
    }
    
    /**
     * Designed to save data from term editor
     *
     * @param $term_id
     * @param $term_taxonomy
     *
     * @return void
     */
    public function saveMetaForTaxonomy( $term_id, $term_taxonomy = null )
    {
    }
    
    /**
     *
     * This function is filtering meta_data by ( domain + path ). $id is a value in dms_mapping_values, it can be a string. If $id is null all data will be a false.
     *
     * @param  string  $url
     * @param  string|int|null  $id
     * @param  string  $type
     *
     * @return array
     */
    public function getData( $url, $id, $type = null )
    {
        if ( empty($type) ) {
            $type = ( is_tax() ? 'Taxonomy' : 'Post' );
        }
        $data = [];
        if ( method_exists( $this, 'getData' . $type ) ) {
            $data = $this->{'getData' . $type}( $url, $id );
        }
        return $data;
    }
    
    /**
     *
     * This function for taking a dms-data if data object is post
     *
     * @param $url
     * @param $id
     *
     * @return array
     */
    public function getDataPost( $url, $id )
    {
        $data = [];
        $url = str_replace( '.', '_', $url );
        foreach ( self::$dms_metas as $value ) {
            $identifier = self::$meta_prefix . $value . self::$domain_separator . $url;
            $data[$value] = get_post_meta( $id, $identifier, true );
        }
        return $data;
    }
    
    /**
     *
     * This function for taking a dms-data if data object is taxonomy
     *
     * @param  string  $url
     * @param  string  $id
     *
     * @return array
     */
    public function getDataTaxonomy( $url, $id )
    {
        $data = [];
        $url = str_replace( '.', '_', $url );
        foreach ( self::$dms_metas as $value ) {
            $identifier = self::$meta_prefix . $value . self::$domain_separator . $url;
            $data[$value] = get_term_meta( $id, $identifier, true );
        }
        return $data;
    }
    
    /**
     *
     * Function for changing title in <head>
     *
     * @param  string  $title
     *
     * @return mixed
     */
    public function changeTitle( $title )
    {
        return ( !empty($this->data['title']) ? $this->data['title'] : $title );
    }
    
    /**
     *
     * Function for changing opengraph url
     *
     * @return string
     */
    public function changeOgUrl( $url, $presentation = null )
    {
        $DMS = $this->dms_instance;
        $host_and_path = DMS_Helper::getHostPlusPath( $DMS->active_mapping );
        // Replacing only host, cause canonical contains our mapping path
        return str_ireplace( DMS_Helper::getBaseHost(), $host_and_path, $url );
    }
    
    /**
     *
     * Function for changing canonical url
     *
     * @return string
     */
    public function changeCanonical( $canonical, $presentation = null )
    {
        $DMS = $this->dms_instance;
        $host_and_path = DMS_Helper::getHostPlusPath( $DMS->active_mapping );
        // Replacing only host, cause canonical contains the mapping path
        return str_ireplace( DMS_Helper::getBaseHost(), $host_and_path, $canonical );
    }
    
    /**
     * Function for changing meta description
     *
     * @param $desc
     *
     * @return mixed
     */
    public function changeDesc( $desc )
    {
        return ( !empty($this->data['description']) ? $this->data['description'] : $desc );
    }
    
    /**
     * Function for changing focusKeyword
     *
     * @param  string  $keyword
     *
     * @return mixed
     */
    public function changeKeyword( $keyword )
    {
        return ( !empty($this->data['keywords']) ? $this->data['keywords'] : $keyword );
    }
    
    /**
     * Function for changing facebook title.
     *
     * @param  string  $ogTitile
     *
     * @return mixed
     */
    public function changeOgTitle( $ogTitile )
    {
        return ( !empty($this->data['opengraph-title']) ? $this->data['opengraph-title'] : $ogTitile );
    }
    
    /**
     *
     * Function for changing facebook title.
     *
     * @param $ogDesc
     *
     * @return mixed
     */
    public function changeOgDesc( $ogDesc )
    {
        return ( !empty($this->data['opengraph-description']) ? $this->data['opengraph-description'] : $ogDesc );
    }
    
    /**
     *
     * Function for changing opengraph image.
     *
     * @param  string  $image
     *
     * @return mixed
     */
    public function changeOgImage( $image )
    {
        return ( !empty($this->data['opengraph-image']) ? $this->data['opengraph-image'] : $image );
    }
    
    /**
     * Function for changing twitter title.
     *
     * @param $twit_title
     *
     * @return mixed
     */
    public function changeTwitTitle( $twit_title )
    {
        return ( !empty($this->data['twitter-title']) ? $this->data['twitter-title'] : $twit_title );
    }
    
    /**
     * Function for changing twitter desc.
     *
     * @param $twit_desc
     *
     * @return mixed
     */
    public function changeTwitDesc( $twit_desc )
    {
        return ( !empty($this->data['twitter-description']) ? $this->data['twitter-description'] : $twit_desc );
    }
    
    /**
     *
     * Function for changing twitter image.
     *
     * @param  string  $image
     *
     * @return mixed
     */
    public function changeTwitImage( $image )
    {
        return ( !empty($this->data['twitter-image']) ? $this->data['twitter-image'] : $image );
    }
    
    /**
     * Get flag for options per domain activity
     *
     * @return string
     */
    public function getOptionsPerDomain()
    {
        return $this->options_per_domain;
    }
    
    /**
     * Get flag for sitemap per domain activity
     *
     * @return string
     */
    public function getSitemapPerDomain()
    {
        return $this->sitemap_per_domain;
    }
    
    /**
     * @return bool
     */
    public function isInteractingWithSitemap()
    {
        return $this->interacting_with_sitemap;
    }
    
    /**
     * Checks weather sitemap requested
     *
     * @return bool
     */
    public function isSitemapRequested()
    {
        $DMS = $this->dms_instance;
        return $this->isInteractingWithSitemap() || DMS_Helper::endsWith( $DMS->getPath(), '/' . self::SITEMAP_FILENAME ) || preg_match( self::SITEMAP_OBJECT_PATTERN, $DMS->getPath() );
    }

}