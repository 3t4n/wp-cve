<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function wp_rfi_settings() {
  if(!current_user_can('manage_options')){
    return;
  }else{
    if(isset($_POST['update_rfi_noncename'])){
      if ( !wp_verify_nonce( @$_POST['update_rfi_noncename'], plugin_basename( __FILE__ ) ) )
        return;
      
      $rfi_pa_image_val = (isset($_POST['rfi_pa_image']) && $_POST['rfi_pa_image']==1)?'1':'0';
      $rfi_po_image_val = (isset($_POST['rfi_po_image']) && $_POST['rfi_po_image']==1)?'1':'0';
      update_option('rfi_pa_image',$rfi_pa_image_val);
      update_option('rfi_po_image',$rfi_po_image_val);
    }?> 
    <div class="wrap">
      <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>           
      <div id="poststuff">
        <div id="post-body">
          <div id="post-body-content">
            <form method="post" action="" name="">
              <?php wp_nonce_field( plugin_basename( __FILE__ ), 'update_rfi_noncename' ); ?>
              <?php 
              $rfi_po_image = get_option('rfi_po_image');
              $select_po    = (isset($rfi_po_image) && $rfi_po_image==1)?'1':'0'; 
              $rfi_pa_image = get_option('rfi_pa_image');
              $select_pa    = (isset($rfi_pa_image) && $rfi_pa_image==1)?'1':'0';  
              ?>
              <table class="form-table">
                <tr valign="top">
                  <th scope="row"><?php _e('From all Posts?');?></th>
                  <td>
                    <label><input type="radio" name="rfi_po_image" value="1" <?php checked( $select_po, 1 ); ?>> <?php _e('Yes');?></label>&nbsp;&nbsp;
                    <label><input type="radio" name="rfi_po_image" value="0" <?php checked( $select_po, 0 ); ?>> <?php _e('No');?></label>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><?php _e('From all Pages?');?></th>
                  <td>
                    <label><input type="radio" name="rfi_pa_image" value="1" <?php checked( $select_pa, 1 ); ?>> <?php _e('Yes');?></label>&nbsp;&nbsp;
                    <label><input type="radio" name="rfi_pa_image" value="0" <?php checked( $select_pa, 0 ); ?>> <?php _e('No');?></label>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"></th>
                  <td>
                    <input type="submit" class="button button-primary" value="Save" >
                  </td>
                </tr>
              </table>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php
  }  
}
function rfi_post_types_add_box() {
  global $rfi_post_types;
  $rfi_post_types = get_post_types( '', 'names' );
  unset( $rfi_post_types['attachment'], $rfi_post_types['revision'], $rfi_post_types['nav_menu_item'] );

  if ( current_user_can( 'edit_page') || current_user_can( 'edit_post')  ) {
    foreach ($rfi_post_types as $post_type) {
      add_meta_box( 'remove_featured_image', __('Remove Featured Image?') , 'rfi_sidebar_box', $post_type, 'side', 'default' );
    }  
  }       
}
function rfi_sidebar_box($post){
  wp_nonce_field( plugin_basename( __FILE__ ), $post->post_type . '_noncename' );
  $remove_featured_image = get_post_meta( $post->ID, '_remove_featured_image', true ); ?>
  <label><input type="radio" name="_remove_featured_image" value="1" <?php checked( $remove_featured_image, 1 ); ?>> <?php _e('Yes');?></label> &nbsp;&nbsp;
  <label><input type="radio" name="_remove_featured_image" value="2" <?php checked( $remove_featured_image, 2 ); ?>> <?php _e('No');?></label> <?php                                      
}
function rfi_post_types_save_data( $post_id ) {
  global $rfi_post_types;

  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    return;

  if ( !wp_verify_nonce( @$_POST[$_POST['post_type'] . '_noncename'], plugin_basename( __FILE__ ) ) )
    return;

  if( in_array($_POST['post_type'], $rfi_post_types) ) {
    if ( !current_user_can( 'edit_page', $post_id ) ) {
      return;
    } else {
      $remove_featured_image = ( isset( $_POST['_remove_featured_image'] )) ? $_POST['_remove_featured_image'] : '';
      if($remove_featured_image=='1' || $remove_featured_image=='2' )
      update_post_meta( $post_id, '_remove_featured_image', $remove_featured_image );     
    }
  }
}
function rfi_featured_image_header() {
  if( is_single() || is_page() ){
    $remove_flag    = false;
    $rfi_po_image   = get_option('rfi_po_image');
    $rfi_pa_image   = get_option('rfi_pa_image');

    $remove_image   = get_post_meta( get_the_ID(), '_remove_featured_image', true );
    $remove_flag    = ( is_page() && $remove_image != 2 &&  $rfi_pa_image == 1) ? true : $remove_flag ; 
    $remove_flag    = ( is_singular( 'post' ) && $remove_image != 2  && $rfi_po_image == 1) ? true : $remove_flag ; 
    $remove_flag    = ( isset( $remove_image ) && $remove_image != 2 && $remove_image != '' )? true : $remove_flag;
    
    if( $remove_flag ){ ?>
      <style>
      img.wp-post-image{ display: none !important; }
      </style>
      <?php
    }
  }
}