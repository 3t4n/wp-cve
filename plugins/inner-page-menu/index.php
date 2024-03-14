<?php
/*
  Plugin Name: Inner Page Menu
  Plugin URI: https://wordpress.org/plugins/inner-page-menu
  Description: Add dynamic menus to your inner pages
  Author: Maeve Lander
  Version: 1.1
  Author URI: https://maevelander.net
*/
 
//=========> Hide all Reporting Errors
error_reporting(0);

//========> Custom Meta Box for Dropdown Menu
add_action( 'add_meta_boxes', 'ipm_menu_metaBox' );
function ipm_menu_metaBox($post){
	add_meta_box('ipm_menu_id', 'Page Menu', 'ipm_crt_metaBox_menu', 'page', 'side' , 'low');
}

function ipm_crt_metaBox_menu($post){
	$ipm_nav_menu_show = get_post_meta($post->ID, 'ipm_nav_menu_show', true);
	$ipm_opt_nav_menu = get_post_meta($post->ID, 'ipm_opt_nav_menu', true);
	
	$ipm_get_menus = get_terms('nav_menu', array('hide_empty' => false));
?>
	<p class="ipm_checkbox">
    	<span>Show a menu in a page sidebar</span>
        <input type="checkbox" name="ipm_nav_menu_show" id="ipm_nav_menu_show" value="1" <?php echo ($ipm_nav_menu_show == 1) ? 'checked="checked"' : ''; ?> />
    </p>

	<select name="ipm_opt_nav_menu" id="ipm_opt_nav_menu" class="ipm_opt_select">
		<option value="">Select Menu</option>
<?php
	foreach ( $ipm_get_menus as $ipm_get_menu ) {
		$ipm_selected = $ipm_opt_nav_menu == $ipm_get_menu->name ? ' selected="selected"' : '';
?>
		<option <?php echo $ipm_selected ?> value="<?php echo $ipm_get_menu->name; ?>"><?php echo $ipm_get_menu->name; ?></option>
<?php
	}
?>
	</select>
<?php
}
add_action('save_post', 'ipm_save_menu_metaBox');
function ipm_save_menu_metaBox(){
	global $post;
	
	$ipm_nav_menu_show = $_POST['ipm_nav_menu_show'];
	$ipm_opt_nav_menu = $_POST['ipm_opt_nav_menu'];
	
	update_post_meta($post->ID, 'ipm_nav_menu_show', $ipm_nav_menu_show);
	update_post_meta($post->ID, 'ipm_opt_nav_menu', $ipm_opt_nav_menu);
}

//========> Admin CSS
add_action("admin_head", "ipm_admin_css");
function ipm_admin_css() {
?>
	<style type="text/css">
    .ipm_checkbox {
        font-weight: bold;
		border-bottom: 1px solid #EEE;
		padding-bottom: 8px;
    }
	.ipm_checkbox span {
		padding-right: 30px;
	}
	select.ipm_opt_select {
		border: 1px solid #EEE;
		border-radius: 4px;
		width: 94%;
	}
</style>
<?php
}

function inner_page_menu() {
	global $post;
	
	$ipm_nav_menu_show = get_post_meta($post->ID, 'ipm_nav_menu_show', true);
	$ipm_opt_nav_menu = get_post_meta($post->ID, 'ipm_opt_nav_menu', true);
	
	if($ipm_nav_menu_show == 1) {
		$ipm_menu_return = wp_nav_menu(array('menu' => $ipm_opt_nav_menu, 'menu_class' => 'ipm_page_menu'));
		
		echo $ipm_menu_return;
	}
}

//=========> Create shortcode [inner-page-menu]
add_shortcode('inner-page-menu', 'inner_page_menu');

?>