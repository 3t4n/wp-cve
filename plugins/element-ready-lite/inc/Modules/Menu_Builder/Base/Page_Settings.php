<?php 

namespace Element_Ready\Modules\Menu_Builder\Base;
use Element_Ready\Base\BaseController;
/**
*  Settings Options
*/
class Page_Settings extends BaseController
{
	public function register() {
        // admin
        add_action( 'wp_nav_menu_item_custom_fields' ,[ $this,'add_custom_fields' ],10, 5);
        add_action( 'wp_update_nav_menu_item', [ $this,'_nav_update' ], 10, 2 );
    }
  
    function _nav_update( $menu_id, $menu_item_db_id ) {
        
        // Verify this came from our screen and with proper authorization.
        if ( ! isset( $_POST['_element_ready_custom_menu_meta_nonce'] ) || ! wp_verify_nonce( $_POST['_element_ready_custom_menu_meta_nonce'], 'element_ready_custom_menu_meta' ) ) {
            return $menu_id;
        }
      
         //Mega menu  
        if ( isset( $_POST[ 'element_ready_mega_menu_post_item_mega_menu_enable' ][ $menu_item_db_id ]  ) ) {
            $sanitized_data = sanitize_text_field( $_POST[ 'element_ready_mega_menu_post_item_mega_menu_enable' ][ $menu_item_db_id ] );
            update_post_meta( $menu_item_db_id, 'element_ready_mega_menu_post_item_mega_menu_enable' , $sanitized_data );
        } else {
            delete_post_meta( $menu_item_db_id, 'element_ready_mega_menu_post_item_mega_menu_enable' );
        }
 
        if ( isset( $_POST[ 'element_ready_mega_menu_post_item_mega_menu_classic' ][ $menu_item_db_id ]  ) ) {
            $sanitized_data = sanitize_text_field( $_POST[ 'element_ready_mega_menu_post_item_mega_menu_classic' ][ $menu_item_db_id ] );
            update_post_meta( $menu_item_db_id, 'element_ready_mega_menu_post_item_mega_menu_classic' , $sanitized_data );
        } else {
            delete_post_meta( $menu_item_db_id, 'element_ready_mega_menu_post_item_mega_menu_classic' );
        }
        // badge
        if ( isset( $_POST['_element_ready_menu_item_badge'][$menu_item_db_id]  ) ) {
            $sanitized_data = sanitize_text_field( $_POST[ '_element_ready_menu_item_badge' ][ $menu_item_db_id ] );
            update_post_meta( $menu_item_db_id, '_element_ready_menu_item_badge', $sanitized_data );
        } else {
           delete_post_meta( $menu_item_db_id, '_element_ready_menu_item_badge' );
        }
        //color 
        if ( isset( $_POST['_element_ready_menu_item_badge_color'][$menu_item_db_id]  ) ) {
            $sanitized_data = sanitize_text_field( $_POST[ '_element_ready_menu_item_badge_color'][ $menu_item_db_id ] );
            update_post_meta( $menu_item_db_id, '_element_ready_menu_item_badge_color' , $sanitized_data );
        } else {
            delete_post_meta( $menu_item_db_id, '_element_ready_menu_item_badge_color' );
        }
        //bgcolor
        if ( isset( $_POST[ '_element_ready_menu_item_badge_bgcolor' ][ $menu_item_db_id ]  ) ) {
            $sanitized_data = sanitize_text_field( $_POST[ '_element_ready_menu_item_badge_bgcolor' ][ $menu_item_db_id ] );
            update_post_meta( $menu_item_db_id, '_element_ready_menu_item_badge_bgcolor', $sanitized_data );
        } else {
            delete_post_meta( $menu_item_db_id, '_element_ready_menu_item_badge_bgcolor' );
        }
        // image
        if ( isset( $_POST['_element_ready_menu_item_image'][$menu_item_db_id]  ) ) {
            $sanitized_data = sanitize_text_field( $_POST[ '_element_ready_menu_item_image'][$menu_item_db_id] );
            update_post_meta( $menu_item_db_id, '_element_ready_menu_item_image', $sanitized_data );
        } else {
            delete_post_meta( $menu_item_db_id, '_element_ready_menu_item_image' );
        }
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
    public function add_custom_fields( $item_id, $item, $depth, $args, $current_object_id ){

            wp_nonce_field( 'element_ready_custom_menu_meta', '_element_ready_custom_menu_meta_nonce' );
            $menu_item_badge         = get_post_meta( $item_id, '_element_ready_menu_item_badge', true );
            $menu_item_badge_color   = get_post_meta( $item_id, '_element_ready_menu_item_badge_color', true );
            
            $menu_item_badge_bgcolor = get_post_meta( $item_id, '_element_ready_menu_item_badge_bgcolor', true );
            $menu_item_badge_img     = get_post_meta( $item_id, '_element_ready_menu_item_image', true );
            $url                     = wp_get_attachment_url(get_post_meta($item_id,'_element_ready_menu_item_image',true));
            if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
                $url = '#';
            }
            if($menu_item_badge_color == ''){
            $menu_item_badge_color = 'inherit';  
            }
            if($menu_item_badge_bgcolor == ''){
                $menu_item_badge_bgcolor = 'inherit';  
            }
            $enable_menu_item    = get_post_meta( $item_id , 'element_ready_mega_menu_post_item_mega_menu_enable',true );
            $enable_menu_classic = get_post_meta( $item_id , 'element_ready_mega_menu_post_item_mega_menu_classic',true );
            $menu_content_id     = get_post_meta( $item_id , 'element_ready_mega_menu_post_item_mega_content_id',true );
            if( !$menu_content_id ){
                $menu_content_id = $this->create_post();
                update_post_meta( $item_id , 'element_ready_mega_menu_post_item_mega_content_id' , $menu_content_id );
            }

        ?>
        <div class="description description-wide element-ready-fields-area" style="margin: 5px 0;">
                <?php if($depth == 0): ?>
                    <div class="element-ready-mega-menu-item">
                        <div class="switch_common">
                            <div class="sm_switch">
                                <h5><strong><?php echo esc_html__('Is MegaMenu?','elements-ready-lite') ?></strong></h5>
                                <input name="element_ready_mega_menu_post_item_mega_menu_enable[<?php echo esc_attr($item_id); ?>]" <?php echo esc_attr($enable_menu_item == 'on'?'checked': ''); ?> class="switch alignright pull-right-input is-mega-menu" id="element-ready-mega-menu-item-is-mega-menu-<?php echo esc_attr($item_id); ?>" type="checkbox">
                                <label for="element-ready-mega-menu-item-is-mega-menu-<?php echo esc_attr($item_id); ?>"></label>
                            </div>
                        </div>
                        <div class="element-ready-mega-menu-elementor-editor-link">
                             <div class="switch_common">
                                <div class="sm_switch">
                                    <h5><strong><?php echo esc_html__('Is Classic Content?','elements-ready-lite') ?></strong></h5>
                                    <input name="element_ready_mega_menu_post_item_mega_menu_classic[<?php echo esc_attr($item_id); ?>]" <?php echo esc_attr($enable_menu_classic == 'on'?'checked': ''); ?> class="switch alignright pull-right-input is-classic-mega-menu" id="element-ready-mega-menu-item-is-mega-menu-classic-<?php echo esc_attr($item_id); ?>" type="checkbox">
                                    <label for="element-ready-mega-menu-item-is-mega-menu-classic-<?php echo esc_attr($item_id); ?>"></label>
                                </div>
                            </div>
                            <!-- Edit Iframe -->
                            <div class="er-elementor-iframe-wrapper" <?php echo esc_attr($enable_menu_classic == 'on'?'hidden' : ''); ?>>
                                <a href="<?php echo esc_url( element_ready_mega_menu_el_edit_link($menu_content_id) ); ?>" class="button button-primary button-large qelementor-edit-link">
                                    <?php echo esc_html__('Edit Content','element-ready-lite'); ?>
                                    <span class='element-ready-mega-menu-spinner'>
                                        <img src="<?php echo esc_url(ELEMENT_READY_MEGA_MENU_MODULE_URL.'/assets/ajax-loader.gif'); ?>" alt="loader" />
                                    </span>
                                </a>
                            </div>
                       </div>
                    </div>
                <?php endif ?>     
                <span class="element-ready-menu-lebel"><?php _e( "Menu Badge", 'element-ready-lite' ); ?></span>
                <br />
                <input type="hidden" class="nav-menu-id" value="<?php echo esc_attr($item_id) ;?>" />
                <div class="logged-input-badge">
                    <input class="element-ready-cfl-item" type="text" name="_element_ready_menu_item_badge[<?php echo esc_attr($item_id); ?>]" id="element-ready-custom-menu-meta-for-<?php echo esc_attr($item_id); ?>" value="<?php echo esc_attr( $menu_item_badge ); ?>" />
                    <span class="dashicons dashicons-no element-ready-nfl-close"></span> 
                </div>
                <span class="element-ready-menu-lebel"><?php _e( "Badge color", 'element-ready-lite' ); ?></span>
                <br />
                <div class="logged-input-badge-color">
                    <input class="element-ready-cfl-item" type="color" name="_element_ready_menu_item_badge_color[<?php echo esc_attr($item_id); ?>]" id="element-ready-custom-menu-meta-for-<?php echo esc_attr($item_id); ?>" value="<?php echo esc_attr( $menu_item_badge_color ); ?>" />
                    <span class="dashicons dashicons-no element-ready-nfl-close"></span> 
                </div>
                <span class="element-ready-menu-lebel"><?php _e( "Badge background", 'element-ready-lite' ); ?></span>
                <br />
                <div class="logged-input-badge-bgcolor">
                    <input class="element-ready-cfl-item" type="color" name="_element_ready_menu_item_badge_bgcolor[<?php echo esc_attr($item_id); ?>]" id="element-ready-custom-menu-meta-for-<?php echo esc_attr($item_id) ;?>" value="<?php echo esc_attr( $menu_item_badge_bgcolor ); ?>" />
                    <span class="dashicons dashicons-no element-ready-nfl-close"></span> 
                </div>
                <span class="element-ready-menu-lebel"><?php _e( "Image", 'element-ready-lite' ); ?></span>
                <br />
                <div class="logged-input-badge-img">
                    <a href="#" class="er_upload_image_button button button-secondary"><?php echo esc_html__('Upload Image','element-ready-lite'); ?></a>
                    <input class="element-ready-cfl-item" type="hidden" name="_element_ready_menu_item_image[<?php echo esc_attr($item_id) ;?>]" id="element-ready-custom-menu-meta-for-<?php echo esc_url($item_id); ?>" value="<?php echo esc_attr( $menu_item_badge_img ); ?>" />
                    <span class="dashicons dashicons-no element-ready-nfl-close"></span> 
                    <img src="<?php echo esc_url($url); ?>" class="element-ready-menu-img" alt="<?php echo esc_attr__('Mega menu Image','element-ready-lite'); ?>" />
                </div>
            </div>
   <?php
    }
  
}