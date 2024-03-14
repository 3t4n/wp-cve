<?php

if( !function_exists('ed_bg_slider_sidebar')){
  function ed_bg_slider_sidebar($post_type) {
    $types = array('ed_bg_slider');

    if (in_array($post_type, $types)) {
      add_meta_box(
        'ed-sidebar-metabox',
        'Assign Background Slider ',
        'ed_bg_slider_sidebar_callback',
        $post_type,
        'side',
        'high'
      );
    }
  }
  add_action('add_meta_boxes', 'ed_bg_slider_sidebar');
  function ed_bg_slider_sidebar_callback($post){
	   wp_nonce_field( basename(__FILE__), 'ed_bg_slider_side_assign_section_nonce' );
	   $assign_page = get_post_meta($post->ID, 'ed_bg_assign_page_post', true);
	  ?>
      <div id="ed-bg-slide-sidebar">
     <?php
		echo ed_bg_checkbox( 'Pages','ed_bg_query_pages');
		echo ed_bg_checkbox( 'Posts','ed_bg_query_posts');
		echo ed_bg_checkbox( 'Home Page','ed_bg_query_home');
		echo ed_bg_checkbox( 'Blog Page','ed_bg_query_blog');
		echo ed_bg_checkbox( 'Custom Taxonomies / Taxonomies','ed_bg_query_custom_tax');
		echo ed_bg_checkbox( 'Custom Post-Types','ed_bg_query_custom_post','disabled');
		echo ed_bg_checkbox( 'Tags','ed_bg_query_tags');
		echo ed_bg_checkbox( 'Date Archive','ed_bg_query_date');
		echo ed_bg_checkbox( 'Author Page','ed_bg_query_auth');
		echo ed_bg_checkbox( 'Search Page','ed_bg_query_search');
	 ?>
    <div class="cmb-th" style="margin-top:20px;">
    <label for="ed_bg_assign_page_post">Assign Slider To Specific Page/Post</label>
    </div>
        <div class="cmb-td" >
    <select name="ed_bg_assign_page_post" style="width:100%;"   disabled="disabled"  >	<option value="">None</option>
     	<?php
			$data = get_posts( array( 'post_type' => array('page','post'), 'posts_per_page' => -1, 'orderby' => 'name', 'order' => 'ASC' ) );
			
			foreach ( $data as $item )	{
				if($item->ID == $assign_page){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}
				echo '<option value="'.$item->ID.'"  '.$selected.'>'.$item->post_title.'</option>';
				echo $item->post_title;
			}
		?>
    </select>
    <p style="font-size:12px; font-style:italic; color:#aaa;">PRO version</p>
 
        </div>
    </div>
      
      <?php
	  
  }
}
if( !function_exists('ed_bg_assign_section_meta_save') ){
	  
  function ed_bg_assign_section_meta_save($post_id) {
    if (!isset($_POST['ed_bg_slider_side_assign_section_nonce']) || !wp_verify_nonce($_POST['ed_bg_slider_side_assign_section_nonce'], basename(__FILE__))) return;

    if (!current_user_can('edit_post', $post_id)) return;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if(isset($_POST['ed_bg_query_pages'])) {
      update_post_meta($post_id, 'ed_bg_query_pages', $_POST['ed_bg_query_pages']);
    } else {
      delete_post_meta($post_id, 'ed_bg_query_pages');
    }
	if(isset($_POST['ed_bg_query_posts'])) {
      update_post_meta($post_id, 'ed_bg_query_posts', $_POST['ed_bg_query_posts']);
    } else {
      delete_post_meta($post_id, 'ed_bg_query_posts');
    }
	if(isset($_POST['ed_bg_query_home'])) {
      update_post_meta($post_id, 'ed_bg_query_home', $_POST['ed_bg_query_home']);
    } else {
      delete_post_meta($post_id, 'ed_bg_query_home');
    }
	if(isset($_POST['ed_bg_query_blog'])) {
      update_post_meta($post_id, 'ed_bg_query_blog', $_POST['ed_bg_query_blog']);
    } else {
      delete_post_meta($post_id, 'ed_bg_query_blog');
    }
	if(isset($_POST['ed_bg_query_custom_tax'])) {
      update_post_meta($post_id, 'ed_bg_query_custom_tax', $_POST['ed_bg_query_custom_tax']);
    } else {
      delete_post_meta($post_id, 'ed_bg_query_custom_tax');
    }
	if(isset($_POST['ed_bg_query_tags'])) {
      update_post_meta($post_id, 'ed_bg_query_tags', $_POST['ed_bg_query_tags']);
    } else {
      delete_post_meta($post_id, 'ed_bg_query_tags');
    }
	if(isset($_POST['ed_bg_query_date'])) {
      update_post_meta($post_id, 'ed_bg_query_date', $_POST['ed_bg_query_date']);
    } else {
      delete_post_meta($post_id, 'ed_bg_query_date');
    }
	if(isset($_POST['ed_bg_query_auth'])) {
      update_post_meta($post_id, 'ed_bg_query_auth', $_POST['ed_bg_query_auth']);
    } else {
      delete_post_meta($post_id, 'ed_bg_query_auth');
    }
	if(isset($_POST['ed_bg_query_search'])) {
      update_post_meta($post_id, 'ed_bg_query_search', $_POST['ed_bg_query_search']);
    } else {
      delete_post_meta($post_id, 'ed_bg_query_search');
    }
	if(isset($_POST['ed_bg_assign_page_post'])) {
      update_post_meta($post_id, 'ed_bg_assign_page_post', $_POST['ed_bg_assign_page_post']);
    } else {
      delete_post_meta($post_id, 'ed_bg_assign_page_post');
    }
	
	
  }
  add_action('save_post', 'ed_bg_assign_section_meta_save');

}


if( !function_exists('ed_bg_checkbox') ){
	function ed_bg_checkbox( $name = NULL,$id='',$disabled=''){
		$field_id_checked = '';
		global $post;
  		$field_id_value = get_post_meta($post->ID, trim($id), true);
		if($field_id_value == "on") $field_id_checked = 'checked="checked"';
		if($disabled !=""){$disabled='disabled="disabled"';}
		return '<div >
			<input  name="'.trim($id).'" id="'.trim($id).'" value="on" type="checkbox" '.$field_id_checked.' '.$disabled.'> 
			<label for="'.trim($id).'"><span>'.$name.'</span></label>
			</div>';
			
	}
}


function ed_bg_pro_info($post_type) {
    $types = array('ed_bg_slider');

    if (in_array($post_type, $types)) {
      add_meta_box(
        'ed-bg-pro-inof',
        '<span style="font-weight:400;">Upgrade to <strong>PRO version</strong></span>',
        'ed_bg_pro_info_callback',
        $post_type,
        'side',
        'low'
      );
    }
  }
  add_action('add_meta_boxes', 'ed_bg_pro_info');
  
  function ed_bg_pro_info_callback(){
	  ?>
   <div><span class="dashicons dashicons-yes"></span> 20+ Animation Type<br>
  <span class="dashicons dashicons-yes"></span>Unlock Background Overlay Color<br>
  <span class="dashicons dashicons-yes"></span> Unlock Assign Slider To Specific Page/Post<br>
  <span class="dashicons dashicons-yes"></span> Unlock thumbs Navigation<br>
    <span class="dashicons dashicons-yes"></span> 24x7 Customer Support <br>
  <span class="dashicons dashicons-arrow-right"></span> And more...<br>
  <br>
  <a style="display:inline-block; background:#33b690; padding:8px 25px 8px; border-bottom:3px solid #33a583; border-radius:3px; color:white;" class="wpd_pro_btn" target="_blank" href="https://edatastyle.com/product/unlimited-background-slider/">See all PRO features</a></div>

      
      <?php
	  
  }


?>