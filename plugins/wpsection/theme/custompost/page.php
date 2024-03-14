<?php

use Elementor\Plugin;



trait PagePostType {

    public function register_post_type() {

        $labels = array(
            'name'                  => _x( 'Page Templates', 'Post Type General Name', 'wpsection' ),
            'singular_name'         => _x( 'Page Template', 'Post Type Singular Name', 'wpsection' ),
            'menu_name'             => __( 'Page Templates', 'wpsection' ),
            'name_admin_bar'        => __( 'Page Templates', 'wpsection' ),
            'archives'              => __( 'List Archives', 'wpsection' ),
            'parent_item_colon'     => __( 'Parent List:', 'wpsection' ),
            'all_items'             => __( 'Page Templates', 'wpsection' ),
            'add_new_item'          => __( 'Add New Page Template', 'wpsection' ),
            'add_new'               => __( 'Add New Page', 'wpsection' ),
            'new_item'              => __( 'New Page Template', 'wpsection' ),
            'edit_item'             => __( 'Edit Page Template', 'wpsection' ),
            'update_item'           => __( 'Update Page Template', 'wpsection' ),
            'view_item'             => __( 'View Page Template', 'wpsection' ),
            'search_items'          => __( 'Search Page Template', 'wpsection' ),
            'not_found'             => __( 'Not found', 'wpsection' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'wpsection' )
        );
        $args = array(
            'label'                 => __( 'Post List', 'wpsection' ),
            'labels'                => $labels,
            'supports'              => array( 'title','editor' ),
            'public'                => true,
            'rewrite'               => false,
            'show_ui'               => true,
            'show_in_menu'          => 'wpsection_template', 
            'show_in_nav_menus'     => false,
            'exclude_from_search'   => true,
            'capability_type'       => 'post',
            'hierarchical'          => false,
              'menu_icon' => 'dashicons-image-rotate-right',
		
			'menu_position' => 60
        );
        register_post_type( 'page_templates', $args );

        add_post_type_support( 'page_templates', 'elementor' );
    }
}

class PageMyPlugin {

    use PagePostType;

    public function __construct() {
        add_action( 'init', [ $this, 'register_post_type' ], 9);
    }
}

new PageMyPlugin();







class PageMrShortcode{

    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct(){

        add_shortcode('PAGE_SHORTCODE', [$this, 'render_shortcode']);

        add_filter( 'widget_text', 'do_shortcode' );
    }

    public function render_shortcode($atts){
        
        $language_support = apply_filters('mr_multilingual_support', false);

        if(!class_exists('Elementor\Plugin')){
            return '';
        }
        if(!isset($atts['id']) || empty($atts['id'])){
            return '';
        }

        $post_id = $atts['id'];

        if($language_support){
            $post_id = apply_filters( 'wpml_object_id', $post_id, 'page_templates' );
        }

        $response = Plugin::instance()->frontend->get_builder_content_for_display($post_id);
        return $response;
    }

}

PageMrShortcode::instance();





class PageMrMetaBoxes{

    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct(){

        add_action("add_meta_boxes", [$this, 'add_meta_boxes']);

        add_filter( 'manage_page_templates_posts_columns', [$this, 'add_column'] );

        add_action('manage_page_templates_posts_custom_column', [$this, 'column_data'], 10, 2);

    }

    public function add_meta_boxes(){
        add_meta_box('mr-shortcode-box','Page Shortcode Area',[$this, 'page_shortcode_box'],'page_templates','side','high');  
    }

    function page_shortcode_box($post){
        ?>



  <h4 style="font-size: 16px; font-weight: bold; margin-bottom: 10px;">Dinamic Shortcode</h4>
        <input type='text' class='widefat' style="background-color: #f7f7f7; border: none; color: #555; font-size: 14px; font-weight: normal; padding: 8px; margin-bottom: 20px;" value='[PAGE_SHORTCODE id="<?php echo $post->ID; ?>"]' readonly="">
    


        <?php
    }
	
	

    function add_column($columns){
        $columns['page_post_column'] = __( 'Wpsection Shortcode', 'wpsection' );
        return $columns;
    }

    function column_data($column, $post_id){
        switch ( $column ) {

            case 'page_post_column' :
                echo '<input type="text" class="widefat" value=\'[PAGE_SHORTCODE id="'.$post_id.'"]\' readonly="">';
                break;
        }
    }
	
	

}

PageMrMetaBoxes::instance();



