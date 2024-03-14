<?php
// if called directly, abort.
if (!defined('WPINC')) { die; }

class raysgrid_Base {

    public $rsgd_sections;

    public function __construct() {
        
        ob_start();
        
        $this->rsgd_sections['rsgd_naming']        = '<i class="dashicons dashicons-megaphone"></i> '.esc_html__( 'Naming' , RSGD_SLUG );
        $this->rsgd_sections['rsgd_source']        = '<i class="dashicons dashicons-admin-network"></i> '.esc_html__( 'Source' , RSGD_SLUG );
        $this->rsgd_sections['rsgd_gnrlsetting']   = '<i class="dashicons dashicons-admin-plugins"></i> '.esc_html__( 'Layout' , RSGD_SLUG );
        $this->rsgd_sections['rsgd_skins']         = '<i class="dashicons dashicons-admin-appearance"></i> '.esc_html__( 'Skins & Styles' , RSGD_SLUG );
        $this->rsgd_sections['rsgd_nav']           = '<i class="dashicons dashicons-admin-generic"></i> '.esc_html__( 'Nav Filter' , RSGD_SLUG );

        add_action('init',                      [ $this, 'rsgd_portfolio_post' ] );
        add_action('admin_menu',                [ $this, 'rsgd_admin_menu' ] );
        add_action( 'admin_enqueue_scripts',    [ $this, 'rsgd_admin_scripts'] );
        add_action( 'wp_enqueue_scripts',       [ $this, 'rsgd_front_styles' ], 56 );
        add_shortcode( RSGD_PFX ,           [ $this, 'rsgd_register_shortcode'] );
        
    }

    public function rsgd_create_settings($id, $args = array()) {
        
        echo '<ul class="rsgd_tabs">';
            foreach ($this->rsgd_sections as $section_slug => $section) {
                echo '<li><a href="#' . esc_attr($section_slug) . '">' . wp_kses($section, true) . '</a></li>';
            }
        echo '</ul>';
        
        echo '<div class="rsgd_tab_content">';
            self::rsgd_all_sections($id);
        echo '</div>';
        
    }

    public function rsgd_all_sections($id) {
        
        foreach ($this->rsgd_sections as $section_slug => $section) {
            $cls = ( $section_slug == 'rsgd_naming') ? ' active' : "";
            
            echo '<div class="tab-pane'.esc_attr($cls).'" id="' . esc_attr($section_slug) . '">';
                $this->rsgd_diplay_section($id, $section_slug);
            echo '</div>';
        }
        
    }

    public function rsgd_diplay_section($id, $section_slug) {
        
        $configs        = new raysgrid_Config();
        $base           = new raysgrid_Tables();
        $fields         = new raysgrid_Field();
        $defult_args    = $base->rsgd_defult_args();
        $cnfg           = $configs->rsgd_configs();
        
        foreach ($cnfg as $sub) {
            
            $section = isset($sub['section']) ? $sub['section'] : $defult_args['section'];

            if ($section == $section_slug) {
                $config_data = self::rsgd_config_names($sub, $defult_args);
                $this->rsgd_wrapperStart($id, $section_slug, $config_data);
                    $fields->rsgd_display_field($id, $section_slug, $config_data);
                $this->rsgd_wrapperEnd($id, $section_slug, $config_data);
                
            }
        }
        
    }
    
    public function rsgd_wrapperStart ($id, $section_slug, $config_data){
        
        extract($config_data);
        $dependency = $config_data['dependency'];
        $type = $config_data['type'];

        // dependencies.
        $cm = $dep_element = $dep_value = '';
        foreach ( $dependency as $value ) {
            
            $dp = $dependency['element'];
            $v = $dependency['value'] ?? '';
            $em = $dependency['not_empty'] ?? '';
            
            if( is_array($dp) ){
                $ard = array();
                foreach ($dp as $el){
                    $ard[] .= $cm . $el;
                    $cm = ',';
                }
                $dep_element = " data-dep='".trim(implode('', $ard), ',')."'";
            }else{
                $dep_element = " data-dep='".esc_attr($dp)."'";
            }
            
            if( is_array($v) ){
                $ar = array();
                foreach ($v as $vl){
                    $ar[] = $cm . $vl;
                    $cm = ',';
                }
                $dep_value = " data-vl='".trim(implode('', $ar), ',')."'";
            }else{
                $dep_value = " data-vl='".esc_attr($v)."'";
            }
            
            if ( $em ){
                $dep_element = " data-dep='".esc_attr($dp)."'";
                if($em == true){
                   $dep_value = " data-vl='1'"; 
                }else{
                    $dep_value = " data-vl=''";
                }
                
            }           
        }
        
        if( $type != 'hidden' ){
	        echo '<div class="item form-group"'.wp_kses($dep_element, true).wp_kses($dep_value, true).'>';
	            echo '<div class="lbl"><label class="opt-lbl">' . esc_html($config_data['title']) . '</label><small class="description">' . esc_html($config_data['description']) . '</small></div>';
	            echo '<div class="control-input">';
        }
        
    }
    
    public function rsgd_wrapperEnd ($id, $section_slug, $config_data){
        
        extract($config_data);
        
        if($config_data['type'] != 'hidden'){
                echo '</div>';
	        echo '</div>';
        }
        
    }

    public function rsgd_config_names($sub, $defult_args) {
        
        $config_data = $config_keys = array();

        foreach ($sub as $key => $value) {
            $config_data[$key] = $value;
            $config_keys[$key] = $key;
        }
        
        foreach ($defult_args as $defult_key => $defult_value) {
            if (!in_array($defult_key, $config_keys)) {
                $config_data[$defult_key] = $defult_value;
            }
        }
        
        return $config_data;
        
    }

    public function rsgd_admin_menu() {
        
        $insDB = new raysgrid_Tables();
        add_menu_page( RSGD_NAME, RSGD_NAME, 'administrator', RSGD_PFX, array($this, 'rsgd_main_form'), RSGD_URI.'/assets/admin/images/ico.png' );
        add_submenu_page(RSGD_PFX, esc_html__('Add New Grid', RSGD_SLUG), esc_html__('Add New', RSGD_SLUG), 'manage_options', RSGD_PFX.'&do=create', array($insDB, 'rsgd_insert_update'));
        add_submenu_page(RSGD_PFX, esc_html__('Import/Export Grids', RSGD_SLUG), esc_html__('Import/Export', RSGD_SLUG), 'manage_options', RSGD_PFX.'-exp', array($insDB, 'rsgd_import_export'));
        
    }

    public function rsgd_main_form() {
        
        require_once( RSGD_DIR . '/includes/form.php' );
        $rsgd_frm      = new raysgrid_Form();
        $rsgd_new_form = $rsgd_frm->rsgd_display_form();
        
    }

    public function rsgd_portfolio_post() {

        $post_type_name = ( get_option( 'rsgd_type_name' ) != '' ) ? get_option( 'rsgd_type_name' ) : 'raysgridpost';
        
        $labels = [
            'name'                  => esc_html__( 'Portfolio Posts' , RSGD_SLUG ),
            'singular_name'         => esc_html__( 'Portfolio Post' , RSGD_SLUG ),
            'add_new'               => esc_html__( 'Add New' , RSGD_SLUG ),
            'add_new_item'          => esc_html__( 'Add New Post' , RSGD_SLUG ),
            'edit'                  => esc_html__( 'Edit' , RSGD_SLUG ),
            'edit_item'             => esc_html__( 'Edit Post' , RSGD_SLUG ),
            'new_item'              => esc_html__( 'New Post' , RSGD_SLUG ),
            'view'                  => esc_html__( 'View' , RSGD_SLUG ),
            'view_item'             => esc_html__( 'View Post' , RSGD_SLUG ),
            'search_items'          => esc_html__( 'Search Post' , RSGD_SLUG ),
            'not_found'             => esc_html__( 'No Posts found' , RSGD_SLUG ),
            'not_found_in_trash'    => esc_html__( 'No Post found in Trash' , RSGD_SLUG ),
            'parent'                => esc_html__( 'Parent Post' , RSGD_SLUG )
        ];
        $args = [
            'labels'                => $labels,
            'public'                => true,
            'has_archive'           => true,
            'publicly_queryable'    => true,
            'rewrite'               => [ 'slug' => $post_type_name ],
            'show_in_rest'          => true,
            'capability_type'       => 'post',
            'hierarchical'          => false,
            'supports'              => [
                'title',
                'editor',
                'excerpt',
                'thumbnail',
                'revisions',
            ],
            'exclude_from_search'   => false,
        ];

        register_post_type( $post_type_name , $args);
        

        register_taxonomy('raysgrid_tags', [$post_type_name], [
            'labels'            => [
                'name' => esc_html__( 'Tags' , RSGD_SLUG )
            ],
            'show_ui'           => true,
            'show_tagcloud'     => false,
            "hierarchical"      => false,
            "singular_label"    => "Tag",
            'rewrite'           => [ 'slug' => 'raysgrid_tags', 'with_front' => false ]
        ]);

        register_taxonomy('raysgrid_categories', [$post_type_name], [
            'labels'            => [
                'name' => esc_html__( 'Categories' , RSGD_SLUG )
            ],
            'show_ui'           => true,
            'show_tagcloud'     => false,
            "hierarchical"      => true,
            "singular_label"    => "Category",
            'rewrite'           => [ 'slug' => 'raysgrid_categories', 'with_front' => false ]
        ]);
    }

    public function rsgd_uninstall() {
        
        global $wpdb;
        $wpdb->query( $wpdb->prepare("DROP TABLE IF EXISTS " . RSGD_TBL ) );
        
    }
    
    public function rsgd_register_shortcode($atts, $content = null){
        
        return raysgrid_Shortcode($atts['alias']);
                
    }

    public function rsgd_admin_scripts() {
        wp_enqueue_style(RSGD_PFX.'-admin-css', RSGD_URI . 'assets/admin/css/admin.css');
        wp_enqueue_style( RSGD_PFX.'-main-font', '//fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,700,700i,900,900i', false );
        
        wp_enqueue_script('wp-color-picker');
        wp_register_script( 'wst-alpha-color', RSGD_URI . 'assets/admin/js/alpha-color.js', ['wp-color-picker'], null, true );

        $color_picker_strings = array(
            'clear'            => __( 'Clear', 'textdomain' ),
            'clearAriaLabel'   => __( 'Clear color', 'textdomain' ),
            'defaultString'    => __( 'Default', 'textdomain' ),
            'defaultAriaLabel' => __( 'Select default color', 'textdomain' ),
            'pick'             => __( 'Select Color', 'textdomain' ),
            'defaultLabel'     => __( 'Color value', 'textdomain' ),
        );
        wp_localize_script( 'wst-alpha-color', 'wpColorPickerL10n', $color_picker_strings );
        wp_enqueue_script( 'wst-alpha-color' );

        wp_enqueue_script(RSGD_PFX.'-assets-js', RSGD_URI . 'assets/admin/js/assets.js', array('jquery'), null, true);
        wp_enqueue_script(RSGD_PFX.'-script-js', RSGD_URI . 'assets/admin/js/script.js', array('jquery'), null, true);
    }

    public function rsgd_front_styles() {
        
        wp_enqueue_style( 'rsgd-assets',        RSGD_URI . 'assets/public/css/assets.css');
        wp_enqueue_style( RSGD_PFX,             RSGD_URI . 'assets/public/css/style.css');        
        wp_enqueue_style( 'slick-slider',       RSGD_URI . 'assets/public/css/vendor/slick.slider.css' );
        wp_enqueue_style( 'magnific-popup',     RSGD_URI . 'assets/public/css/vendor/magnific-popup.css');
        wp_enqueue_style( 'font-awesome',       RSGD_URI . 'assets/public/css/vendor/font.awesome.css' );
        
        wp_register_script( 'modernizr',        RSGD_URI . 'assets/public/js/vendor/modernizr.js',      array('jquery'), null, true);        
        wp_register_script( 'jquery-isotope',   RSGD_URI . 'assets/public/js/vendor/isotope.js',        array('jquery'), null, true);
        wp_register_script( 'imagesloaded',     RSGD_URI . 'assets/public/js/vendor/imagesloaded.js',   array('jquery'), null, true);
        wp_register_script( 'slick-slider',     RSGD_URI . 'assets/public/js/vendor/slick.slider.js',   array('jquery'), null, true);
        wp_register_script( 'magnific-popup',   RSGD_URI . 'assets/public/js/vendor/magnific.popup.js', array('jquery'), null, true);
        wp_register_script( 'colorbox',         RSGD_URI . 'assets/public/js/vendor/colorbox.js',       array('jquery'), null, true);
        wp_register_script( 'hoverdir',         RSGD_URI . 'assets/public/js/vendor/hoverdir.js',       array('jquery'), null, true);
        wp_register_script( RSGD_PFX.'_script', RSGD_URI . 'assets/public/js/script.js',                array('jquery'), null, true);
        
        wp_enqueue_script( 'modernizr' );
        wp_enqueue_script( 'jquery-isotope' );
        wp_enqueue_script( 'imagesloaded' );
        wp_enqueue_script( 'slick-slider' );
        wp_enqueue_script( 'magnific-popup' );
        wp_enqueue_script( 'colorbox' );
        wp_enqueue_script( 'hoverdir' );
        wp_enqueue_script( RSGD_PFX.'_script' );
        
    }
    
    public function rsgd_colors( $main_color ){

        $rsgd_col   = ( $main_color != '' ) ? esc_html($main_color) : '#7da600';
        $rgbacolor  = rsgd_hex2RGB($rsgd_col, true, ',');
        
        $CSS = "
        .raysgrid.gemini .portfolio-item h4 a,.filter-by.style1 li.selected a,.raysgrid.solo .portfolio-item h4 a,.raysgrid.sublime .port-captions h4 a,.raysgrid.focus .port-captions p.description a,.filter-by.style5 ul li.selected a{
            color: {$rsgd_col};
        }
        
        .portfolio-item .rsgd_main-bg,.raysgrid.slick-slider .slick-dots li.slick-active button,.raysgrid.onair .port-captions p,.filter-by.style2 ul li.selected a span,.filter-by.style3 ul li.selected a span,.filter-by.style4 ul li.selected a span,
        .raysgrid.onair .port-captions p,.raysgrid.rotato .port-captions,.raysgrid.mass .port-captions,.raysgrid.mass .icon-links a,.raysgrid.marbele .port-captions:before,.raysgrid.astro .port-captions{
            background-color: {$rsgd_col};
            color: #fff;
        }
        
        .raysgrid.mass .port-img:before,.filter-by.style1 li.selected a:before,.raysgrid.sublime .port-captions,.raysgrid.resort .portfolio-item:hover .port-container{
            border-color: {$rsgd_col};
        }
        
        .raysgrid.ivy .icon-links a:after{
            border-color: {$rsgd_col} transparent transparent transparent;
        }
        
        .raysgrid.ivy .icon-links a.rsgd_zoom:after{
            border-color: transparent transparent {$rsgd_col} transparent;
        }
        
        .raysgrid.kara .port-captions:after{
            background-color:rgba({$rgbacolor},0.75);
        }
        
        .filter-by.style2,.filter-by.style3 ul{
            border-bottom-color: {$rsgd_col};
        }";
        
        $CSS = str_replace(': ', ':', str_replace(';}', '}', str_replace('; ',';',str_replace(' }','}',str_replace(' {', '{', str_replace('{ ','{',str_replace(array("\r\n","\r","\n","\t",'  ','    ','    '),"",preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!','',$CSS))))))));
        
        //return $CSS;
        
        wp_enqueue_style (RSGD_PFX.'-custom-short', RSGD_URI . 'assets/public/css/custom-style.css', array() );
        wp_add_inline_style (RSGD_PFX.'-custom-short', $CSS);
        
    }

}
new raysgrid_Base();
