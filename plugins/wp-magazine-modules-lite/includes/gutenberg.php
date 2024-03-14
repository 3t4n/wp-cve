<?php
/**
 * Carry out the gutenberg actions register blocks, register scripts , styles , dependencies, and hooks.
 * 
 * @package WP Magazine Modules Lite
 * @since 1.0.0
 * 
 */
if ( !class_exists( 'Wpmagazine_Modules_Lite_Blocks' ) ) :

    class Wpmagazine_Modules_Lite_Blocks {
        /**
         * Instance
         *
         * @access private
         * @static
         *
         * @var Wpmagazine_Modules_Lite_Blocks The single instance of the class.
         */
        private static $_instance = null;

        /**
         * Ensures only one instance of the class is loaded or can be loaded.
         *
         * @access public
         * @static
         *
         * @return Wpmagazine_Modules_Lite_Blocks An instance of the class.
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Load the dependencies, functions , register hooks, scripts , styles on function call.
         */
        public function __construct() {
            add_action( 'plugins_loaded', array( $this, 'init' ), 99 );
        }

        /**
         * Initialization of class.
         */
        public function init() {
            if ( !WPMAGAZINE_MODULES_LITE_GUTENBERG ) {
                return;
            }
            //register blocks categories
            if ( version_compare( $GLOBALS['wp_version'], '5.8-alpha-1', '<' ) ) {
                add_filter( 'block_categories', array( $this, 'register_gutenberg_block_category' ) );
            } else {
                add_filter( 'block_categories_all', array( $this, 'register_gutenberg_block_category' ) );
            }

            add_action( 'enqueue_block_editor_assets', array( $this, 'block_editor_enqueue_scripts' ) );
            add_action( 'rest_api_init', array( $this, 'register_featured_image_rest_fields' ) );
            add_action( 'rest_api_init', array( $this, 'register_categories_ids_rest_field' ) );
            add_action( 'rest_api_init', array( $this, 'register_categories_names_rest_field' ) );
            add_action( 'rest_api_init', array( $this, 'register_tags_names_rest_field' ) );
            add_action( 'rest_api_init', array( $this, 'register_comments_num_rest_field' ) );
            //dependencies files
            $this->load_dependencies();
        }

        /**
         * Load external dependencies of this class.
         */
        public function load_dependencies() {
            require plugin_dir_path( __FILE__ ) . '/src/block-base/block-base.php'; // Base Class
            require plugin_dir_path( __FILE__ ) . '/src/banner/block.php'; // Banner Block
            require plugin_dir_path( __FILE__ ) . '/src/post-grid/block.php'; // Post Grid Block
            require plugin_dir_path( __FILE__ ) . '/src/post-list/block.php'; // Post List Block
            require plugin_dir_path( __FILE__ ) . '/src/post-masonry/block.php'; // Post Masonry Block
            require plugin_dir_path( __FILE__ ) . '/src/ticker/block.php'; // Ticker Block
            require plugin_dir_path( __FILE__ ) . '/src/post-tiles/block.php'; // Post Tiles Block
            require plugin_dir_path( __FILE__ ) . '/src/post-carousel/block.php'; // Post Carousel Block
            require plugin_dir_path( __FILE__ ) . '/src/post-slider/block.php'; // Post Slider Block
            require plugin_dir_path( __FILE__ ) . '/src/post-block/block.php'; // "Post Block" Block
            require plugin_dir_path( __FILE__ ) . '/src/category-collection/block.php'; // "Category Collection" Block
            require plugin_dir_path( __FILE__ ) . '/src/timeline/block.php'; // "Timeline Collection" Block
            require plugin_dir_path( __FILE__ ) . '/src/post-filter/block.php'; // "Post filter by categories Collection" Block
            require plugin_dir_path( __FILE__ ) . '/dynamic-css.php';
        }

        /**
         * Enqueue editor scripts 
         */
        public function block_editor_enqueue_scripts() {

            global $pagenow;

            $cvmm_fonts_url = $this->get_fonts_url();

            wp_enqueue_style( 'wpmagazine-modules-lite-editor-fonts', esc_url( $cvmm_fonts_url ), array(), null );

            $wpmagazine_dependencies = array( 'wp-element', 'wp-blocks', 'wp-components', 'wp-i18n' );

            if ( in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) ) {
                array_push( $wpmagazine_dependencies, 'wp-editor', 'wp-edit-post' );
            } elseif ( $pagenow === 'widgets.php' ) {
                array_push( $wpmagazine_dependencies, 'wp-edit-widgets' );
            }

            wp_enqueue_style( 'wpmagazine-modules-lite-frontend', plugins_url( 'assets/css/build.css', __FILE__ ), array(), WPMAGAZINE_MODULES_LITE_VERSION, 'all' );
            
            $dynamic_allcss_class = new Wpmagazine_Modules_Lite_Dynamic_AllCss();
            $dynamic_css = $dynamic_allcss_class->category_parsed_css();
            wp_add_inline_style( 'wpmagazine-modules-lite-frontend', wp_strip_all_tags( $dynamic_css ) );
            
            wp_enqueue_style( 'wpmagazine-modules-lite-block-editor', plugins_url( 'assets/css/build-editor.css', __FILE__ ), array(), WPMAGAZINE_MODULES_LITE_VERSION, 'all' );

            wp_enqueue_style( 'fontawesome', plugins_url( 'assets/library/fontawesome/css/all.min.css', __FILE__ ), array(), '5.12.1', 'all' );

            wp_enqueue_style( 'slick-slider', plugins_url( 'assets/library/slick-slider/css/slick.css', __FILE__ ), array(), '1.8.0', 'all' );

            wp_enqueue_style( 'slick-slider-theme', plugins_url( 'assets/library/slick-slider/css/slick-theme.css', __FILE__ ), array(), '1.8.0', 'all' );
            
            wp_enqueue_script( 'wpmagazine-modules-lite-block-build', plugins_url( 'build/index.js', __FILE__ ), $wpmagazine_dependencies, WPMAGAZINE_MODULES_LITE_VERSION, true );

            wp_set_script_translations( 'wpmagazine-modules-lite-block-build', 'wp-magazine-modules-lite' );

            // define global object for js file.
            wp_localize_script( 'wpmagazine-modules-lite-block-build', 'BlocksBuildObject',
                array(
                    'pluginPage'            => admin_url( 'admin.php?page=wpmagazine-modules-lite#cvmm-options' ),
                    'defaultImage'          => esc_url( WPMAGAZINE_MODULES_LITE_DEFAULT_IMAGE ),
                    'bannerLayoutDefault'   => plugins_url( '/assets/images/banner-layout-default.png', __FILE__ ),
                    'bannerLayoutOne'       => plugins_url( '/assets/images/banner-layout-one.png', __FILE__ ),
                    'bannerLayoutTwo'       => plugins_url( '/assets/images/banner-layout-two.png', __FILE__ ),
                    'gridLayoutDefault'     => plugins_url( '/assets/images/grid-layout-default.png', __FILE__ ),
                    'gridLayoutOne'         => plugins_url( '/assets/images/grid-layout-one.png', __FILE__ ),
                    'gridLayoutTwo'         => plugins_url( '/assets/images/grid-layout-two.png', __FILE__ ),
                    'listLayoutDefault'     => plugins_url( '/assets/images/list-layout-default.png', __FILE__ ),
                    'listLayoutOne'         => plugins_url( '/assets/images/list-layout-one.png', __FILE__ ),
                    'listLayoutTwo'         => plugins_url( '/assets/images/list-layout-two.png', __FILE__ ),
                    'filterLayoutDefault'   => plugins_url( '/assets/images/filter-layout-default.png', __FILE__ ),
                    'filterLayoutOne'       => plugins_url( '/assets/images/filter-layout-one.png', __FILE__ ),
                    'filterLayoutTwo'       => plugins_url( '/assets/images/filter-layout-two.png', __FILE__ ),
                    'blockLayoutDefault'    => plugins_url( '/assets/images/block-layout-default.png', __FILE__ ),
                    'blockLayoutOne'        => plugins_url( '/assets/images/block-layout-one.png', __FILE__ ),
                    'blockLayoutTwo'        => plugins_url( '/assets/images/block-layout-two.png', __FILE__ ),
                    'categoryCollectionLayoutDefault'   => plugins_url( '/assets/images/category-collection-layout-default.png', __FILE__ ),
                    'categoryCollectionLayoutOne'       => plugins_url( '/assets/images/category-collection-layout-one.png', __FILE__ ),
                    'categoryCollectionLayoutTwo'       => plugins_url( '/assets/images/category-collection-layout-two.png', __FILE__ ),
                    'carouselLayoutDefault' => plugins_url( '/assets/images/carousel-layout-default.png', __FILE__ ),
                    'carouselLayoutOne'     => plugins_url( '/assets/images/carousel-layout-one.png', __FILE__ ),
                    'carouselLayoutTwo'     => plugins_url( '/assets/images/carousel-layout-two.png', __FILE__ ),
                    'sliderLayoutDefault'   => plugins_url( '/assets/images/slider-layout-default.png', __FILE__ ),
                    'sliderLayoutOne'       => plugins_url( '/assets/images/slider-layout-one.png', __FILE__ ),
                    'sliderLayoutTwo'       => plugins_url( '/assets/images/slider-layout-two.png', __FILE__ ),
                    'tilesLayoutDefault'    => plugins_url( '/assets/images/tiles-layout-default.png', __FILE__ ),
                    'tilesLayoutOne'        => plugins_url( '/assets/images/tiles-layout-one.png', __FILE__ ),
                    'tilesLayoutTwo'        => plugins_url( '/assets/images/tiles-layout-two.png', __FILE__ ),
                    'tickerLayoutDefault'   => plugins_url( '/assets/images/ticker-layout-default.png', __FILE__ ),
                    'tickerLayoutOne'       => plugins_url( '/assets/images/ticker-layout-one.png', __FILE__ ),
                    'tickerLayoutTwo'       => plugins_url( '/assets/images/ticker-layout-two.png', __FILE__ ),
                    'timelineLayoutDefault' => plugins_url( '/assets/images/timeline-layout-default.png', __FILE__ ),
                    'timelineLayoutOne'     => plugins_url( '/assets/images/timeline-layout-one.png', __FILE__ ),
                    'timelineLayoutTwo'     => plugins_url( '/assets/images/timeline-layout-two.png', __FILE__ ),
                )
            );
        }

        /**
         * Register "wp magazine modules" block collection category
         */
        public function register_gutenberg_block_category( $categories ) {
            return array_merge(
                array(
                    array(
                        'slug'  => 'wpmagazine-modules-lite',
                        'title' => esc_html__( 'WP Magazine Modules Lite', 'wp-magazine-modules-lite' ),
                    ),
                ),
                $categories
            );
        }

        /**
         * Create rest API featured image url field for post types
         */
        public function register_featured_image_rest_fields() {
            $post_types = get_post_types();
            register_rest_field(
                $post_types,
                'wpmagazine_modules_lite_featured_media_urls',
                array(
                    'get_callback' => array( $this, 'get_rest_featured_media' ),
                    'update_callback' => null,
                    'schema' => array(
                        'description' => esc_html__( 'Featured Images', 'wp-magazine-modules-lite'),
                        'type' => 'array'
                    )
                )
            );
        }

        public function get_rest_featured_media($object) {
            if ( !isset( $object['featured_media'] ) ){ return; }
            $featured_media = wp_get_attachment_image_src( $object['featured_media'], 'full', false );
            return array(
                'thumbnail' => is_array($featured_media) ? wp_get_attachment_image_src(
                    $object['featured_media'],
                    'thumbnail',
                    false
                ) : '',
                'cvmm-medium' => is_array($featured_media) ? wp_get_attachment_image_src(
                    $object['featured_media'],
                    'cvmm-medium',
                    false
                ) : '',
                'cvmm-medium-plus' => is_array($featured_media) ? wp_get_attachment_image_src(
                    $object['featured_media'],
                    'cvmm-medium-plus',
                    false
                ) : '',
                'cvmm-portrait' => is_array($featured_media) ? wp_get_attachment_image_src(
                    $object['featured_media'],
                    'cvmm-portrait',
                    false
                ) : '',
                'cvmm-medium-square' => is_array($featured_media) ? wp_get_attachment_image_src(
                    $object['featured_media'],
                    'cvmm-medium-square',
                    false
                ) : '',
                'cvmm-large' => is_array($featured_media) ? wp_get_attachment_image_src(
                    $object['featured_media'],
                    'cvmm-large',
                    false
                ) : '',
                'cvmm-small' => is_array($featured_media) ? wp_get_attachment_image_src(
                    $object['featured_media'],
                    'cvmm-small',
                    false
                ) : '',
                'full' => is_array($featured_media) ? $featured_media : '',
            );
        }

        /**
         * add categories_id field to rest api for custom post type
         * callback function 'get_taxanomy_ids'
         */
        public function register_categories_ids_rest_field() {
            $posttypes = get_post_types( array( '_builtin' => false ) );
            if ( empty( $posttypes ) ){ return; }
            register_rest_field(
                $posttypes,
                'categories',
                array(
                    'get_callback'    => array( $this, 'get_taxanomy_ids' ),
                    'update_callback' => null,
                    'schema'          => null,
                )
            );
        }

        /**
         * called by 'register_categories_ids_rest_field' function
         */
        public function get_taxanomy_ids( $object, $field_name, $request ) {
            $formatted_categories_ids = array();
            if ( $object['type'] == 'post' ) {
                return;
            } else {
                $taxonomies = get_taxonomies( array( 'object_type' => array( $object['type'] ) ) );
                if ( empty( $taxonomies ) ) {  return; }
                foreach( $taxonomies as $taxonomy ) {
                    $categories = get_the_terms( $object['id'], $taxonomy );
                    break;
                }
            }
            if ( empty( $categories ) ) { return; }
            foreach ( $categories as $category ) {
                $formatted_categories_ids[] .= $category->term_id;
            }

            return apply_filters( "wpmagazine_modules_lite_register_term_ids_api", $formatted_categories_ids );
        }

        /**
         * add categories_name field to rest api
         * callback function 'get_categories_names'
         */
        public function register_categories_names_rest_field() {
            $posttypes = get_post_types();
            register_rest_field(
                $posttypes,
                'categories_names',
                array(
                    'get_callback'    => array( $this, 'get_categories_names' ),
                    'update_callback' => null,
                    'schema'          => null,
                )
            );
        }

        /**
         * called by 'register_categories_names_rest_field' function
         */
        public function get_categories_names( $object, $field_name, $request ) {
            $formatted_categories = array();
            if ( $object['type'] == 'post' ) {
                $categories = get_the_category($object['id']);
            } else {
                $taxonomies = get_taxonomies( array( 'object_type' => array( $object['type'] ) ) );
                if ( empty( $taxonomies ) ) {  return; }
                foreach( $taxonomies as $taxonomy ) {
                    $categories = get_the_terms( $object['id'], $taxonomy );
                    break;
                }
            }
            if ( empty( $categories ) ) { return; }
            foreach ( $categories as $category ) {
                $formatted_categories[ $category->term_id ] = array(
                    'name' => $category->name,
                    'link' => get_category_link( $category->term_id )
                );
            }

            return apply_filters( "wpmagazine_modules_lite_register_term_names_api", $formatted_categories );
        }

        /**
         * add categories_name field to rest api
         * callback function 'get_tags_names'
         */
        public function register_tags_names_rest_field() {
            register_rest_field(
                array( 'post' ),
                'tags_names',
                array(
                    'get_callback'    => array( $this, 'get_tags_names' ),
                    'update_callback' => null,
                    'schema'          => null,
                )
            );
        }

        /**
         * called by 'register_tags_names_rest_field' function
         */
        public function get_tags_names( $object, $field_name, $request ) {
            $formatted_tags = array();
            $tags = wp_get_post_tags( $object['id'] );
            foreach ( $tags as $tag ) {
                $formatted_tags[ $tag->term_id ] = array(
                    'name' => $tag->name,
                    'link' => get_tag_link( $tag->term_id )
                );
            }

            return $formatted_tags;
        }

        /**
         * add categories_name field to rest api
         * callback function 'get_comments_num'
         */
        public function register_comments_num_rest_field() {
            $posttypes = get_post_types();
            register_rest_field(
                $posttypes,
                'comments_number',
                array(
                    'get_callback'    => array( $this, 'get_comments_num' ),
                    'update_callback' => null,
                    'schema'          => null,
                )
            );
        }

        /**
         * called by 'register_comments_num_rest_field' function
         */
        public function get_comments_num( $object, $field_name, $request ) {
            $comment_num = get_comments_number( $object['id'] );
            return $comment_num;
        }

        /**
         * Register google fonts for frontend
         */
        public function get_fonts_url() {
            $fonts_url = '';
            $font_families = array();

            /**
             * Translators: If there are characters in your language that are not supported
             * by Muli font, translate this to 'off'. Do not translate into your own language.
             */
            if ( 'off' !== _x( 'on', 'Roboto font: on or off', 'wp-magazine-modules-lite' ) ) {
                $font_families[] = 'Roboto:400';
            }

            /**
             * Translators: If there are characters in your language that are not supported
             * by Rubik font, translate this to 'off'. Do not translate into your own language.
             */
            if ( 'off' !== _x( 'on', 'Yanone Kaffeesatz font: on or off', 'wp-magazine-modules-lite' ) ) {
                $font_families[] = 'Yanone Kaffeesatz:700';
            }

            /**
             * Open Sans
             */
            if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'wp-magazine-modules-lite' ) ) {
                $font_families[] = 'Open Sans:300,400,600,700,800';
            }

            /**
             * Roboto Slab
             */
            if ( 'off' !== _x( 'on', 'Roboto Slab font: on or off', 'wp-magazine-modules-lite' ) ) {
                $font_families[] = 'Roboto Slab:100,200,300,400,500,600,700,800,900';
            }

            /**
             * Poppins
             */
            if ( 'off' !== _x( 'on', 'Poppins font: on or off', 'wp-magazine-modules-lite' ) ) {
                $font_families[] = 'Poppins:100,200,300,400,500,600,700,800,900';
            }

            if ( $font_families ) {
                $query_args = array(
                    'family' => urlencode( implode( '|', $font_families ) ),
                    'subset' => urlencode( 'latin,latin-ext' ),
                );

                $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
            }

            return $fonts_url;
        }
    }

    Wpmagazine_Modules_Lite_Blocks::instance();

endif;