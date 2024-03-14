<?php

use Elementor\Plugin;



trait MegamenuPostType {

    public function register_post_type() {

        $labels = array(
            'name'                  => _x( 'Megamenu Templates', 'Post Type General Name', 'wpsection' ),
            'singular_name'         => _x( 'Megamenu Template', 'Post Type Singular Name', 'wpsection' ),
            'menu_name'             => __( 'Megamenu Templates', 'wpsection' ),
            'name_admin_bar'        => __( 'Megamenu Templates', 'wpsection' ),
            'archives'              => __( 'List Archives', 'wpsection' ),
            'parent_item_colon'     => __( 'Parent List:', 'wpsection' ),
            'all_items'             => __( 'Megamenu Templates', 'wpsection' ),
            'add_new_item'          => __( 'Add New Megamenu Template', 'wpsection' ),
            'add_new'               => __( 'Add New Megamenu', 'wpsection' ),
            'new_item'              => __( 'New Megamenu Template', 'wpsection' ),
            'edit_item'             => __( 'Edit Megamenu Template', 'wpsection' ),
            'update_item'           => __( 'Update Megamenu Template', 'wpsection' ),
            'view_item'             => __( 'View Megamenu Template', 'wpsection' ),
            'search_items'          => __( 'Search Megamenu Template', 'wpsection' ),
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
            'show_in_menu'          => 'wpsection_template', // Corrected menu slug
            'show_in_nav_menus'     => false,
            'exclude_from_search'   => true,
            'capability_type'       => 'post',
            'hierarchical'          => false,
            'menu_icon'             => 'dashicons-image-rotate-right',
            'menu_position'         => 60,
             'register_meta_box_cb' => [ $this, 'add_shortcode_meta_box' ]
        );
        register_post_type( 'megamenu_templates', $args );

        add_post_type_support( 'megamenu_templates', 'elementor' );
    }


      public function add_shortcode_meta_box() {
        add_meta_box(
            'mega_menu_shortcode',
            'Mega Menu Shortcode',
            [ $this, 'render_shortcode_meta_box' ],
            'megamenu_templates',
            'normal',
            'high'
        );
    }

     public function render_shortcode_meta_box( $post ) {
        $shortcode = get_post_meta( $post->ID, 'mega_menu_shortcode', true );
        ?>
        <label for="mega_menu_shortcode">Mega Menu Shortcode:</label>
        <input type="text" id="mega_menu_shortcode" name="mega_menu_shortcode" value="<?php echo esc_attr( $shortcode ); ?>" style="width: 100%;">
        <?php
    }
}

class MegamenuMyPlugin {

    use MegamenuPostType;

    public function __construct() {
        add_action( 'init', [ $this, 'register_post_type' ], 9 );
    }
}

new MegamenuMyPlugin();







class MegamenuMrShortcode{

    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct(){

        add_shortcode('MEGAMENU_SHORTCODE', [$this, 'render_shortcode']);

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
            $post_id = apply_filters( 'wpml_object_id', $post_id, 'megamenu_templates' );
        }

        $response = Plugin::instance()->frontend->get_builder_content_for_display($post_id);
        return $response;
    }



}

MegamenuMrShortcode::instance();





class MegamenuMrMetaBoxes{

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

        add_filter( 'manage_megamenu_templates_posts_columns', [$this, 'add_column'] );

        add_action('manage_megamenu_templates_posts_custom_column', [$this, 'column_data'], 10, 2);

    }

    public function add_meta_boxes(){
        add_meta_box('mr-shortcode-box','Megamenu Shortcode Area',[$this, 'megamenu_shortcode_box'],'megamenu_templates','side','high');  
    }

    function megamenu_shortcode_box($post){
        ?>



  <h4 style="font-size: 16px; font-weight: bold; margin-bottom: 10px;">Dinamic Shortcode</h4>
        <input type='text' class='widefat' style="background-color: #f7f7f7; border: none; color: #555; font-size: 14px; font-weight: normal; padding: 8px; margin-bottom: 20px;" value='[MEGAMENU_SHORTCODE id="<?php echo $post->ID; ?>"]' readonly="">
    


        <?php
    }
	
	

    function add_column($columns){
        $columns['megamenu_post_column'] = __( 'Wpsection Shortcode', 'wpsection' );
        return $columns;
    }

    function column_data($column, $post_id){
        switch ( $column ) {

            case 'megamenu_post_column' :
                echo '<input type="text" class="widefat" value=\'[MEGAMENU_SHORTCODE id="'.$post_id.'"]\' readonly="">';
                break;
        }
    }
	
	

}

MegamenuMrMetaBoxes::instance();



