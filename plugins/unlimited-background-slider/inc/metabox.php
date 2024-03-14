<?php
if( !function_exists('ed_bg_appearance')){
  function ed_bg_appearance($post_type) {
    $types = array('ed_bg_slider');

    if (in_array($post_type, $types)) {
      add_meta_box(
        'ed-appearance-settings',
        'Appearance Settings',
        'ed_bg_appearance_call_back',
        $post_type,
        'normal',
        'high'
      );
    }
  }
  add_action('add_meta_boxes', 'ed_bg_appearance');
  function ed_bg_appearance_call_back($post){
	  wp_nonce_field( basename(__FILE__), 'ed_bg_slider_sidebar_nonce' );
	  ?>
      
<table class="form-table">
  <tr>
    <th style="width:20%"><label for="ed_bg_animation">Animation :-</label>
    </th>
    <td>
    <?php $animation = get_post_meta($post->ID, 'ed_bg_animation', true);?>
    <select class="cmb2_select" name="ed_bg_animation" id="ed_bg_animation" style="width:140px;">
        <?php foreach (animationList() as $key => $val){
						if( $key == $animation){
						echo '<option value="'.$key.'" selected="selected">'.$val.'</option>';
						}else{
							echo '<option value="'.$key.'">'.$val.'</option>';
						}
					}?>
      </select><p style="font-size:12px; font-style:italic; color:#aaa;">upgrade to pro and get 20+ more Style ($7)</p></td>
  </tr>
  <tr>
    <th style="width:20%"><label for="ed_bg_duration">Slide Duration :-</label>
    </th>
    <td>
    <?php $duration = get_post_meta($post->ID, 'ed_bg_duration', true);
		$duration = ( isset( $duration ) && $duration != "" ) ? $duration : '3000';?>
    <input name="ed_bg_duration" id="ed_bg_duration" value="<?php esc_attr_e( $duration ); ?>" type="number" style="width:140px;"></td>
  </tr>
  <tr>
    <th style="width:20%"><label for="ed_bg_transition">Transition Speed :-</label>
    </th>
    <td>
     <?php $transition = get_post_meta($post->ID, 'ed_bg_transition', true);
		$transition = ( isset( $transition ) && $transition != "" ) ? $transition : '2000';?>
    <input name="ed_bg_transition" id="ed_bg_transition" value="<?php esc_attr_e( $transition ); ?>" type="number" style="width:140px;"></td>
  </tr>
  <tr>
    <th style="width:20%"><label for="ed_bg_autoplay">Auto Play:-</label>
    </th>
    <td>
    <?php $autoplay = get_post_meta($post->ID, 'ed_bg_autoplay', true);?>
    <select  name="ed_bg_autoplay" id="ed_bg_autoplay" style="width:140px;">
        <option value="true" <?php if($autoplay == 'true'):?>selected="selected" <?php endif;?>>Enable</option>
        <option value="false"  <?php if($autoplay == 'false'):?>selected="selected" <?php endif;?>>Disable</option>
      </select></td>
  </tr>
  <tr>
    <th style="width:20%"><label for="ed_bg_show_nav">Show Navigation:-</label>
    </th>
    <td>
    <?php $show_nav = get_post_meta($post->ID, 'ed_bg_show_nav', true);?>
    <select class="cmb2_select" name="ed_bg_show_nav" id="ed_bg_show_nav">
    	<option value="false"  <?php if($show_nav == 'false'):?>selected="selected" <?php endif;?>>Disable</option>
       <option value="true" <?php if($show_nav == 'true'):?>selected="selected" <?php endif;?>>Enable</option>
        
      </select></td>
  </tr>
  <tr>
    <th style="width:20%"><label for="ed_bg_nav_position">Navigation Position:-</label>
    </th>
    <td>
      <?php $nav_pos = get_post_meta($post->ID, 'ed_bg_nav_position', true);?>
    <select class="cmb2_select" name="ed_bg_nav_position" id="ed_bg_nav_position">
        <option value="topleft" <?php if($nav_pos == 'topleft'):?>selected="selected" <?php endif;?>>TopLeft</option>
        <option value="topright" <?php if($nav_pos == 'topright'):?>selected="selected" <?php endif;?>>TopRight</option>
        <option value="bottomleft" <?php if($nav_pos == 'bottomleft'):?>selected="selected" <?php endif;?>>BottomLeft</option>
        <option value="bottomright" <?php if($nav_pos == 'bottomright'):?>selected="selected" <?php endif;?>>BottomRight</option>
      </select></td>
  </tr>
  <tr>
    <th style="width:20%"><label for="ed_bg_thumbs">Show thumbs:-</label>
    </th>
    <td>
    <?php $thumbs = get_post_meta($post->ID, 'ed_bg_thumbs', true);?>
    <select class="cmb2_select" name="ed_bg_thumbs" id="ed_bg_thumbs" disabled="disabled">
        <option value="false"  <?php if($thumbs == 'false'):?>selected="selected" <?php endif;?>>Disable</option>
        <option value="true" <?php if($thumbs == 'true'):?>selected="selected" <?php endif;?>>Enable</option>
      </select><p style="font-size:12px; font-style:italic; color:#aaa;">PRO version</p></td>
  </tr>
  <tr>
    <th style="width:20%"><label for="ed_bg_thumbs_position">Thumbs Position:-</label>
    </th>
    <td>
     <?php $thumbs_pos = get_post_meta($post->ID, 'ed_bg_thumbs_position', true);?>
    <select class="cmb2_select" name="ed_bg_thumbs_position" id="ed_bg_thumbs_position" disabled="disabled">
      <option value="topleft" <?php if($thumbs_pos == 'topleft'):?>selected="selected" <?php endif;?>>TopLeft</option>
        <option value="topright" <?php if($thumbs_pos == 'topright'):?>selected="selected" <?php endif;?>>TopRight</option>
        <option value="bottomleft" <?php if($thumbs_pos == 'bottomleft'):?>selected="selected" <?php endif;?>>BottomLeft</option>
        <option value="bottomright" <?php if($thumbs_pos == 'bottomright'):?>selected="selected" <?php endif;?>>BottomRight</option>
      </select><p style="font-size:12px; font-style:italic; color:#aaa;">PRO version</p></td>
  </tr>
</table>

      <?php
  }
}

if( !function_exists('ed_bg_appearance_meta_save') ){
  function ed_bg_appearance_meta_save($post_id) {
    if (!isset($_POST['ed_bg_slider_sidebar_nonce']) || !wp_verify_nonce($_POST['ed_bg_slider_sidebar_nonce'], basename(__FILE__))) return;

    if (!current_user_can('edit_post', $post_id)) return;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if(isset($_POST['ed_bg_animation'])) {
      update_post_meta($post_id, 'ed_bg_animation', $_POST['ed_bg_animation']);
    } 
	if(isset($_POST['ed_bg_duration'])) {
      update_post_meta($post_id, 'ed_bg_duration', $_POST['ed_bg_duration']);
    }
	if(isset($_POST['ed_bg_transition'])) {
      update_post_meta($post_id, 'ed_bg_transition', $_POST['ed_bg_transition']);
    }
	if(isset($_POST['ed_bg_autoplay'])) {
      update_post_meta($post_id, 'ed_bg_autoplay', $_POST['ed_bg_autoplay']);
    }
	if(isset($_POST['ed_bg_show_nav'])) {
      update_post_meta($post_id, 'ed_bg_show_nav', $_POST['ed_bg_show_nav']);
    }
	if(isset($_POST['ed_bg_nav_position'])) {
      update_post_meta($post_id, 'ed_bg_nav_position', $_POST['ed_bg_nav_position']);
    }
	if(isset($_POST['ed_bg_thumbs'])) {
      update_post_meta($post_id, 'ed_bg_thumbs', $_POST['ed_bg_thumbs']);
    }
	if(isset($_POST['ed_bg_thumbs_position'])) {
      update_post_meta($post_id, 'ed_bg_thumbs_position', $_POST['ed_bg_thumbs_position']);
    }
	
	
  }
  add_action('save_post', 'ed_bg_appearance_meta_save');

}
function animationList(){
	return array(
		'zoom'   => __( 'Zoom', 'ed_ubs' ),
		'slideLeft'   => __( 'Slide Left', 'ed_ubs' ),
	);
}
?>