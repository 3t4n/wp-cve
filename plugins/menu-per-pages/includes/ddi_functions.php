<?php  
function meta_box_menu_per_page()
{
    add_meta_box( 'custom_menu', 'Custom Menu', 'meta_selected_callback','', 'normal', 'high' );
}
function meta_selected_callback( $post )
{
	if($post->post_type=='page' || $post->post_type=='post'){
   	$selected_menu_id = get_post_meta( $post->ID ,'selected_menu');   
	$menus = wp_get_nav_menus();
	
    ?>
<div class="wp_custom_metaboxes">
<h4>Selected Menu</h4>
<p>
  <label for="selected_menu">Select Menu for this <?php echo  $post->post_type?> </label>
  <select name="selected_menu" id="selected_menu">
    <?php foreach($menus as $menu){
			?>
    <option value="<?php echo $menu->term_id; ?>" <?php if($selected_menu_id[0]==$menu->term_id){echo 'selected="selected"';}?>><?php echo $menu->name; ?></option>
    <?php
		}?>
  </select>
  </p>
  
<?php  
	}
	
}
function meta_selected_menu_save( $post_id ){
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; 
	  if( isset( $_POST['selected_menu'] ) )
        update_post_meta( $post_id, 'selected_menu', $_POST['selected_menu'] );	

}

function my_wp_nav_menu_args( $args = '' )
{

	global $post;
	$selected_menu_id = get_post_meta( $post->ID ,'selected_menu');
 	$new_menu_id = $selected_menu_id[0];
	
    $args['theme_location'] = false;
	$args['menu'] = $new_menu_id;
    return $args;
} // function
?>