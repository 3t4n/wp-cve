<?php
/**
 * @package  Mega menu
 *  Mega menu Settings
 */
namespace Element_Ready\Modules\Menu_Builder\Base;

class Menu_Settings {

    public function register() {
        add_action( 'admin_init', [ $this, 'init' ] );
    }

    function init(){

        if( is_admin() ) {
            
            // If the user can manage options, let the fun begin!
            if ( current_user_can( 'manage_options' ) ) {
                add_action( 'admin_head-nav-menus.php', [ $this, 'register_nav_meta_box' ], 9 );
            }

            add_action("wp_ajax_element_ready_mega_menu_activation_option", [$this,'ajax_megamenu_activate']);
            add_action("wp_ajax_element_ready_mega_menu_activation_check", [$this,'ajax_megamenu_activation_check']);
            add_action("wp_ajax_element_ready_mega_menu_current_item_id_data", [$this,'ajax_current_item_id_data']);
            add_action("wp_ajax_element_ready_mega_menu_current_item_settings_update", [$this,'ajax_current_item_setting_update']);
            add_action( 'admin_footer', [ $this, 'option_popup_views'] );

        }
   
       
    }
    
    public function option_popup_views(){
        $screen = get_current_screen();
        if($screen->base != 'nav-menus'){
            return;
        }
        include 'templates/menu-item-settings.php';
    }
     // Meta Box Field render
     public function register_nav_meta_box() {
        global $pagenow;
        
        if ( 'nav-menus.php' == $pagenow ) {
          add_meta_box( 'element-ready-mg-menu-metabox',  esc_html__( 'Mega Menu', 'element-ready-lite' ), array( $this, 'metabox_contents' ), 'nav-menus', 'side', 'default');
        }
    }
    public function ajax_megamenu_activate(){
       
         if ( !wp_verify_nonce( $_REQUEST['nonce'], 'element_ready_mega_menu_metabox_nonce') ) {
            exit("No naughty business please");
         }

         $activation_status = sanitize_text_field( $_REQUEST['activation_status'] );
         $menu_id           = sanitize_text_field ($_REQUEST['menu_id'] );
      
         if( $activation_status === 'true' ){
             update_option( 'element_ready_mega_menu_options_enable_menu'.$menu_id, 'on' );
         }else{
            update_option( 'element_ready_mega_menu_options_enable_menu'.$menu_id, '' );
         }
         
         wp_die();
    }
    public function ajax_current_item_setting_update(){
      
       
        if(!isset($_REQUEST['item_id'])){

            return wp_send_json_error(false);
        }
      
        $item_id       = sanitize_text_field( isset( $_REQUEST['item_id'] ) ? $_REQUEST[ 'item_id' ] : null );
        $is_mega_menu  = sanitize_text_field( isset( $_REQUEST['is_mega_menu'] )? $_REQUEST[ 'is_mega_menu' ] : null );
    
        if( $is_mega_menu == 'true' ){
            update_post_meta( $item_id ,'element_ready_mega_menu_post_item_mega_menu_enable', 'yes' );
        }else{
            update_post_meta( $item_id ,'element_ready_mega_menu_post_item_mega_menu_enable', 'no' );
        }

        return wp_send_json_success(true);
        wp_die();
    }
    public function ajax_megamenu_activation_check(){

        $menu_id = isset($_REQUEST[ 'menu_id' ])?sanitize_text_field($_REQUEST[ 'menu_id' ]):sanitize_text_field($_REQUEST[ 'menu_cur_id' ]);
        
        if ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ){
            $enable_menu = get_option( "element_ready_mega_menu_options_enable_menu" . $menu_id, true );
            if($enable_menu == 'on'){
                return wp_send_json_success(true);
            }
            return wp_send_json_error($enable_menu);
        }

        wp_die();

    }
  
    public function ajax_current_item_id_data(){
       
        $return_data = [];
        $item_id     = sanitize_text_field($_REQUEST['menu_item_id']);
        if ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ){

            $enable_menu_item   = get_post_meta( $item_id , 'element_ready_mega_menu_post_item_mega_menu_enable',true );
            $enable_offcanvas   = get_post_meta( $item_id , 'element_ready_mega_menu_post_item_mega_offcanvas_enable',true );
            $enable_mobile_menu = get_post_meta( $item_id , 'element_ready_mega_menu_post_item_mega_is_mobile_menu',true );
            $menu_content_id    = get_post_meta( $item_id , 'element_ready_mega_menu_post_item_mega_content_id',true );
            $width_type         = get_post_meta( $item_id , 'element_ready_megamenu_width_type',true );
            
            if( !$menu_content_id ){

                $menu_content_id = $this->create_post();
                update_post_meta( $item_id , 'element_ready_mega_menu_post_item_mega_content_id' , $menu_content_id );
               
            }

            if( $enable_menu_item == 'yes' ){

                $return_data['mega_menu_enable'] = true;
            }else{

                $return_data['mega_menu_enable'] = false; 
            }

           
            $return_data['edit_url'] = element_ready_mega_menu_el_edit_link($menu_content_id);

            return wp_send_json_success($return_data);
          
        }
        
        return wp_send_json_error(false);
        wp_die();

    }

    function create_post(){

        $mega_menu_post = array(
            'post_title'   => esc_html__('Element Ready menu item','element-ready-lite'),
            'post_content' => '',
            'post_status'  => 'draft',
            'post_type'    => 'er-mg-menu',
          );
         
         $post_id = wp_insert_post( $mega_menu_post );
         return $post_id;
    }
    public function metabox_contents(){
        // Get recently edited nav menu.
        $recently_edited      = absint( get_user_option( 'nav_menu_recently_edited' ) );
        $nav_menu_selected_id = sanitize_text_field(isset( $_REQUEST['menu'] ) ? absint( $_REQUEST['menu'] ) : 0);
        if ( empty( $recently_edited ) && is_nav_menu( $nav_menu_selected_id ) )
            $recently_edited = $nav_menu_selected_id;
        
        // Use $recently_edited if none are selected.
        if ( empty( $nav_menu_selected_id ) && ! isset( $_GET['menu'] ) && is_nav_menu( $recently_edited ) )
            $nav_menu_selected_id = $recently_edited;
        
        $enable_menu = get_option( "element_ready_mega_menu_options_enable_menu" . $nav_menu_selected_id, true );
       

    ?>
        <div id="element-ready-mega-menu-metabox">

            <input type="hidden" value="<?php echo esc_attr( $nav_menu_selected_id ); ?>" id="element-ready-mega-metabox-input-menu-id" />
               
            <div class="switch_common">
                <div class="sm_switch">
                    <label><strong><?php esc_html_e( "Enable megamenu?", 'element-ready-lite' ); ?></strong></label>
                      <input <?php echo isset($enable_menu) && $enable_menu == 'on' ? 'checked="true"' : '' ?> class="switch alignright pull-right-input" id="element-ready-mega-menu-metabox-input-is-enabled" type="checkbox">
                    <label for="element-ready-mega-menu-metabox-input-is-enabled"></label>
                </div>
            </div>

            <p> 
                <?php echo get_submit_button( esc_html__('Save', 'element-ready-lite'), 'element-ready-mega-menu-settings-save button-primary alignright','', false); ?>
                <span class='element-ready-mega-menu-spinner'>
                    <img src="<?php echo esc_url(ELEMENT_READY_MEGA_MENU_MODULE_URL.'/assets/ajax-loader.gif'); ?>" alt="loader" />
                </span>
            </p>

        </div>

    <?php
    }

   



    
}