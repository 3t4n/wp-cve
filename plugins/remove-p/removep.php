<?php
/*
Plugin Name: Remove P
Plugin URI: http://www.sanarena.com/
Description: disable Extra Paragraphs in wordpress render engine
Author: San Arena
Version: 1.2.1
Author URI: http://www.sanarena.com/
*/

add_action('admin_menu', 'removep_meta_box_add');
add_action('edit_post', 'removep_post_meta_tags');
add_action('publish_post', 'removep_post_meta_tags');
add_action('save_post', 'removep_post_meta_tags');
add_action('edit_page_form', 'removep_post_meta_tags');
add_filter('the_content', 'removep_wpautop', 9);

function removep_meta_box_add() {
	if ( function_exists('add_meta_box') ) {
		add_meta_box('removep',__('Remove P', 'removep'),'removep_meta','post');
		add_meta_box('removep',__('Remove P', 'removep'),'removep_meta','page');
	}
}
	function removep_post_meta_tags($id) {
	    $removep_edit = $_POST["removep_edit"];
	    if (isset($removep_edit) && !empty($removep_edit)) {

		    $status = $_POST["removep"];
		    delete_post_meta($id, '_removep');
		    $br = $_POST["removep_br"];
		    delete_post_meta($id, '_removep_br');

		
		    if (isset($status) && !empty($status)) {
			    add_post_meta($id, '_removep', $status);
		    }

		    if (isset($br) && !empty($br)) {
			    add_post_meta($id, '_removep_br', $br);
		    }

	    }
	}



function removep_meta() {

	global $post;
	
	$post_id = $post;
	if (is_object($post_id)){
		$post_id = $post_id->ID;
	}
 	$status = get_post_meta($post_id, '_removep', true);
 	$br = get_post_meta($post_id, '_removep_br', true);
 	
	?>


		<a target="_blank" href="http://www.sanarena.com/payment/">Click here for Support</a><br><br>
		
		<input type="hidden" value="1" name="removep_edit">
		
		<table >
		<tr>
		<td scope="row" style="text-align:left;"><input type="checkbox" name="removep" id="removepp" value="1" <?php if ($status) echo 'checked="checked"'; ?> /><label for="removepp"> Remove Extra Paragraphs in this <?php if($post->post_type=='page'){ ?>page<?php }else{ ?>post<?php } ?>.</label></td>
		</tr>
		<tr>
		<td scope="row" style="text-align:left;"><input type="checkbox" name="removep_br" id="removep_br" value="1" <?php if ($br) echo 'checked="checked"'; ?> /><label for="removep_br"> Also convert line breaks to br tags ( only work if Remove Extra Paragraphs is enabled ).</label></td>
		</tr>

		</table>
	<?php
}

function removep_wpautop($content) {
    global $post; 
    if (is_page() || is_object($post)){
    if (get_post_meta($post->ID, '_removep', true)&&!function_exists('add_meta_box'))
    {
    remove_filter('the_content', 'wpautop');
        if (get_post_meta($post->ID, '_removep_br', true))
		{
			$content=nl2br($content);
		}
    }
    }
    return $content;
}

?>