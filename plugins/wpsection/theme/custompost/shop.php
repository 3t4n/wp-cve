<?php
// Place the 'use Elementor\Plugin;' statement at the top of the file
use Elementor\Plugin;



trait ShopPostType {

    public function register_post_type() {

        $labels = array(
            'name'                  => _x( 'Shop Templates', 'Post Type General Name', 'wpsection' ),
            'singular_name'         => _x( 'Shop Templates', 'Post Type Singular Name', 'wpsection' ),
            'menu_name'             => __( 'Shop Templates', 'wpsection' ),
            'name_admin_bar'        => __( 'Shop Templates', 'wpsection' ),
            'archives'              => __( 'List Archives', 'wpsection' ),
            'parent_item_colon'     => __( 'Parent List:', 'wpsection' ),
            'all_items'             => __( 'Shop Templates', 'wpsection' ),
            'add_new_item'          => __( 'Add New Shop Template', 'wpsection' ),
            'add_new'               => __( 'Add New Shop', 'wpsection' ),
            'new_item'              => __( 'New Shop Template', 'wpsection' ),
            'edit_item'             => __( 'Edit Shop Template', 'wpsection' ),
            'update_item'           => __( 'Update Shop Template', 'wpsection' ),
            'view_item'             => __( 'View Shop Template', 'wpsection' ),
            'search_items'          => __( 'Search Shop Template', 'wpsection' ),
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
        register_post_type( 'shop_templates', $args );

        add_post_type_support( 'shop_templates', 'elementor' );
    }
}

class ShopMyPlugin {

    use ShopPostType;

    public function __construct() {
        add_action( 'init', [ $this, 'register_post_type' ], 9);
    }
}

new ShopMyPlugin();

class ShopMrShortcode{

    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct(){

        add_shortcode('SHOP_SHORTCODE', [$this, 'render_shortcode']);

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
            $post_id = apply_filters( 'wpml_object_id', $post_id, 'shop_templates' );
        }

        $response = Plugin::instance()->frontend->get_builder_content_for_display($post_id);
        return $response;
    }
}

ShopMrShortcode::instance();

class ShopMrMetaBoxes{

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

        add_filter( 'manage_shop_templates_posts_columns', [$this, 'add_column'] );

        add_action('manage_shop_templates_posts_custom_column', [$this, 'column_data'], 10, 2);

    }

    public function add_meta_boxes(){
        add_meta_box('mr-shortcode-box','Shop Shortcode Area',[$this, 'shop_shortcode_box'],'shop_templates','side','high');  
    }

    function shop_shortcode_box($post){
        ?>
        <h4 style="font-size: 16px; font-weight: bold; margin-bottom: 10px;">Dinamic Shortcode</h4>
        <input type='text' class='widefat' style="background-color: #f7f7f7; border: none; color: #555; font-size: 14px; font-weight: normal; padding: 8px; margin-bottom: 20px;" value='[SHOP_SHORTCODE id="<?php echo $post->ID; ?>"]' readonly="">
    


        <?php
    }

    function add_column($columns){
        $columns['shop_post_column'] = __( 'Wpsection Shortcode', 'wpsection' );
        return $columns;
    }

    function column_data($column, $post_id){
        switch ( $column ) {
            case 'shop_post_column' :
                echo '<input type="text" class="widefat" value=\'[SHOP_SHORTCODE id="'.$post_id.'"]\' readonly="">';
                break;
        }
    }
}

ShopMrMetaBoxes::instance();


