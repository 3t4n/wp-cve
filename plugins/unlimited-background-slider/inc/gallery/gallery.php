<?php

if( !function_exists('ed_gallery_metabox_enqueue') ){
  function ed_gallery_metabox_enqueue($hook) {
  //  if ( 'post.php' == $hook || 'post-new.php' == $hook ) {
      wp_enqueue_script('gallery-metabox-js', ED_BG_SLIDE_URL . '/inc/gallery/js/gallery-metabox.js', array('jquery', 'jquery-ui-sortable'));
      wp_enqueue_style('gallery-metabox', ED_BG_SLIDE_URL . '/inc/gallery/css/gallery-metabox.css');
  //  }
  }
  add_action('admin_enqueue_scripts', 'ed_gallery_metabox_enqueue');
}
  
  
if( !function_exists('ed_add_gallery_metabox') ){
  function ed_add_gallery_metabox($post_type) {
    $types = array('ed_bg_slider');

    if (in_array($post_type, $types)) {
      add_meta_box(
        'gallery-metabox',
        '<span style="font-weight:400;">'.__( 'Slider Settings ', 'ed_ubs' ).'</span> <a target="_blank" class="wpd_free_pro" title="'.__( 'Unlock more features with Team Members PRO!', 'ed_ubs' ).'" href="https://edatastyle.com/product/unlimited-background-slider/"><span style="color:#f05f40;font-size:15px; font-weight:400; float:right; padding-right:14px;"><span class="dashicons dashicons-lock"></span> '.__( 'Free version', 'ed_ubs' ).'</span></a>',
        'ed_gallery_meta_callback',
        $post_type,
        'normal',
        'high'
      );
    }
  }
  add_action('add_meta_boxes', 'ed_add_gallery_metabox');
}



if( !function_exists('ed_gallery_meta_callback') ){
  function ed_gallery_meta_callback($post) {
    wp_nonce_field( basename(__FILE__), 'gallery_meta_nonce' );
    $ids = get_post_meta($post->ID, 'vdw_gallery_id', true);
	$bg = get_post_meta($post->ID, 'bg_overlay_color', true);
	$overlay = get_post_meta($post->ID, 'bg_overlay_opacity', true);
	
	$bg_overlay_color = ( isset( $bg ) ) ? $bg : '';
	$bg_overlay_opacity = ( isset( $overlay ) ) ? $overlay : '0.5';
    ?>
    <table class="form-table">
      <tr>
       <th style="width:20%"><label for="sample_text">Slide Images</label>
       <p style="font-size:12px; font-style:italic; color:#aaa;">Add multiple Image ( CTRL + SELECT )</p>
       </th>
      <td>
     
        <a class="gallery-add button" href="#" data-uploader-title="Add image(s) to gallery" data-uploader-button-text="Add image(s)">Add image(s)</a>

        <ul id="gallery-metabox-list">
        <?php if ($ids) : foreach ($ids as $key => $value) : $image = wp_get_attachment_image_src($value); ?>

          <li>
            <input type="hidden" name="vdw_gallery_id[<?php echo $key; ?>]" value="<?php echo $value; ?>">
            <img class="image-preview" src="<?php echo $image[0]; ?>">
            <a class="change-image button button-small" href="#" data-uploader-title="Change image" data-uploader-button-text="Change image">Change image</a><br>
            <small><a class="remove-image" href="#">Remove image</a></small>
          </li>

        <?php endforeach; endif; ?>
        </ul>

      </td>
        <tr>
        <th style="width:20%"><label for="bg_overlay_color">Background Overlay Color</label></th>
        <td>
        <input class="color_field" disabled="disabled"  type="hidden" name="bg_overlay_color" id="bg_overlay_color" value="<?php esc_attr_e( $bg_overlay_color ); ?>"/>
         <p style="font-size:12px; font-style:italic; color:#aaa;">PRO version</p>
        </td><td>
        </td></tr>
        <th style="width:20%"><label for="bg_overlay_opacity">Overlay Opacity</label></th>
        <td>
      		<select disabled="disabled"  name="bg_overlay_opacity" id="bg_overlay_opacity" style="max-width:120px;">
            	<option value="0.1" <?php if($bg_overlay_opacity == '0.1'):?>selected="selected"<?php endif;?>>0.1</option>
                <option value="0.2" <?php if($bg_overlay_opacity == '0.2'):?>selected="selected"<?php endif;?>>0.2</option>
                <option value="0.3" <?php if($bg_overlay_opacity == '0.3'):?>selected="selected"<?php endif;?>>0.3</option>
                <option value="0.4" <?php if($bg_overlay_opacity == '0.4'):?>selected="selected"<?php endif;?>>0.4</option>
                <option value="0.5" <?php if($bg_overlay_opacity == '0.5'):?>selected="selected"<?php endif;?>>0.5</option>
                <option value="0.6" <?php if($bg_overlay_opacity == '0.6'):?>selected="selected"<?php endif;?>>0.6</option>
                <option value="0.7" <?php if($bg_overlay_opacity == '0.7'):?>selected="selected"<?php endif;?>>0.7</option>
                <option value="0.8" <?php if($bg_overlay_opacity == '0.8'):?>selected="selected"<?php endif;?>>0.8</option>
                <option value="0.9" <?php if($bg_overlay_opacity == '0.9'):?>selected="selected"<?php endif;?>>0.9</option>
            </select>
              <p style="font-size:12px; font-style:italic; color:#aaa;">PRO version</p>
        </td><td>
        </td></tr>
        
        
    </table>
    
  <?php }
}


if( !function_exists('ed_gallery_meta_save') ){
  function ed_gallery_meta_save($post_id) {
    if (!isset($_POST['gallery_meta_nonce']) || !wp_verify_nonce($_POST['gallery_meta_nonce'], basename(__FILE__))) return;

    if (!current_user_can('edit_post', $post_id)) return;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if(isset($_POST['vdw_gallery_id'])) {
		$img = array_map( 'sanitize_text_field', wp_unslash( $_POST['vdw_gallery_id'] ) );
      update_post_meta($post_id, 'vdw_gallery_id', $img);
    } else {
      delete_post_meta($post_id, 'vdw_gallery_id');
    }
	
	if(isset($_POST['bg_overlay_color'])) {
		$color = array_map( 'sanitize_text_field', wp_unslash( $_POST['bg_overlay_color'] ) );
      update_post_meta($post_id, 'bg_overlay_color', $color);
    } else {
      delete_post_meta($post_id, 'bg_overlay_color');
    }
	if(isset($_POST['bg_overlay_opacity'])) {
		$opacity = array_map( 'sanitize_text_field', wp_unslash( $_POST['bg_overlay_opacity'] ) );
      update_post_meta($post_id, 'bg_overlay_opacity', $opacity);
    } else {
      delete_post_meta($post_id, 'bg_overlay_opacity');
    }
	
	
	
  }
  add_action('save_post', 'ed_gallery_meta_save');

}

?>