<?php

/*
Plugin Name: Simple Popup Manager
Plugin URI: http://wordpress.org/extend/plugins/simple-popup-manager/
Description: Adds a promotional popup in homepage.
Version: 1.3.5
Author: Benoit Mercusot
Author URI: http://www.mbcreation.net/
License: GPL2
*/


define( 'SPM_PLUGIN_VERSION', '1.3.4' );


if(is_admin()){

add_action('admin_init', 'simple_popup_manager_init' );
add_action('admin_menu', 'simple_popup_manager_add_page');


load_plugin_textdomain('simple-popup-manager', false, basename( dirname( __FILE__ ) ) . '/languages/');


function simple_popup_manager_init(){
	
	register_setting( 'simple_popup_manager_options', 'simple_popup_manager_fields', 'simple_popup_manager_validate' );
	
}


function simple_popup_manager_add_page() {
	add_options_page(__('Simple Pop Manager','simple-popup-manager'), __('Popup Settings','simple-popup-manager'), 'manage_options', 'simple_popup_manager', 'simple_popup_manager_do_page');
}


function simple_popup_manager_do_page() {
	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br></div>
		<h2><?php echo __('Simple Pop Manager','simple-popup-manager');?></h2>
		<p><?php echo __('Simple Pop Manager description','simple-popup-manager');?></p>
		<form method="post" action="options.php">
			<?php settings_fields('simple_popup_manager_options'); ?>
			<?php $options = get_option('simple_popup_manager_fields'); ?>
			<?php // 1.3 default context value  ?>
			<?php if(!isset($options['context']))$options['context']='home';?>
			<?php if(!isset($options['threshold']))$options['threshold']=$options['largeur'];?>
			<?php // 1.3.4 default context value  ?>
			<?php if(!isset($options['resetcss']))$options['resetcss']=false;?>
			<table class="form-table">
				<tr valign="top"><th scope="row"><?php echo __('Enable plugin : ','simple-popup-manager');?></th>
				<td><input name="simple_popup_manager_fields[active]" type="checkbox" value="1" <?php if(!empty($options['active']))checked('1', $options['active']); ?> /></td>
				</tr>
				<tr valign="top">
				<th scope="row"><label for="context"><?php echo __('Where would you like the PopUp window to be visible ?','simple-popup-manager');?></label></th>
				<td>
				<select name="simple_popup_manager_fields[context]" id="context">
					<option value="home" <?php selected( $options['context'], 'home' ); ?>><?php echo __('Only on the home or front page of the site','simple-popup-manager');?></option>
					<option value="all"  <?php selected( $options['context'], 'all' ); ?>><?php echo __('Every where on the site','simple-popup-manager');?></option>
				</select>
				</td>
				</tr>
				<tr valign="top"><th scope="row"><?php echo __('Debug mode : ','simple-popup-manager');?></th>
				<td><input name="simple_popup_manager_fields[debug]" type="checkbox" value="1" <?php if(!empty($options['debug']))checked('1', $options['debug']); ?> /> <?php echo __('No cookie, popup only visible for logged in admin on frontpage','simple-popup-manager');?></td>
				</tr>
				<tr valign="top"><th scope="row"><?php echo __('Display close button : ','simple-popup-manager');?></th>
				<td><input name="simple_popup_manager_fields[bouton]" type="checkbox" value="1" <?php if(!empty($options['bouton']))checked('1', $options['bouton']); ?> /></td>
				</tr>
                <tr valign="top"><th scope="row"><?php echo __('Cookie duration (in days) : ','simple-popup-manager');?></th>
				<td><fieldset><input type="number" step="1" min="0" class="small-text" name="simple_popup_manager_fields[cookie]" value="<?php echo ($options['cookie']!='' ? $options['cookie'] :  $options['cookie'] ); ?>" /> <?php echo __('Possible values','simple-popup-manager');?></fieldset></td>
				</tr>
                <tr valign="top">
                <th scope="row"><?php echo __('Dimensions and threshold :','simple-popup-manager');?></th>
				<td>
				<fieldset>
				<?php echo __('Width of the popup in pixels : ','simple-popup-manager');?>
				<input type="number" step="1" min="0" class="small-text" name="simple_popup_manager_fields[largeur]" value="<?php echo ($options['largeur']!='' ? $options['largeur'] :  300 ); ?>" />
				<?php echo __('Height of the popup in pixels : ','simple-popup-manager');?></th>
				<input type="number" step="1" min="0" class="small-text" name="simple_popup_manager_fields[hauteur]" value="<?php echo ($options['hauteur']!='' ? $options['hauteur'] :  250 ); ?>" />
				<?php echo __('Threshold : ','simple-popup-manager');?></th>
				<input type="number" step="1" min="0" class="small-text" name="simple_popup_manager_fields[threshold]" value="<?php echo ($options['threshold']!='' ? $options['threshold'] :  480 ); ?>" />
				</fieldset>
				<p class="description"><?php echo __('Dimension will set width and height of the popup. Threshold is the limit in pixel under which popup won\'t show up despite all others settings. 480 is the default value to exclude small devices like mobile phone.','simple-popup-manager');?></p>
				</td>
				</tr>
				<tr valign="top">
				<th scope="row"><label for="bgcolor"><?php echo __('Background screen color :','simple-popup-manager');?></label></th>
				<td><input type="text" id="bgcolor" name="simple_popup_manager_fields[bgcolor]" value="<?php echo ($options['bgcolor']!='' ? $options['bgcolor'] :  '#000000' ); ?>"  class="small-text">
				<p class="description"><?php echo __('Should be hexadecimal color like #000000','simple-popup-manager');?></p></td>
				</tr>
				<tr valign="top"><th scope="row"><?php echo __('Opacity : ','simple-popup-manager');?></th>
				<td><input type="number" step="0.1" min="0" max="1" class="small-text"  name="simple_popup_manager_fields[opacite]" value="<?php echo ($options['opacite']!='' ? $options['opacite'] :  0.5 ); ?>" /></td>
				</tr>
				<tr valign="top"><th scope="row"><?php echo __('Reset CSS inside the popup : ','simple-popup-manager');?></th>
				<td><input name="simple_popup_manager_fields[resetcss]" type="checkbox" value="1" <?php if(!empty($options['resetcss']))checked('1', $options['resetcss']); ?> />
				<p class="description"><?php echo __('*{margin:0;padding:0;}, usefull for iframe embeds','simple-popup-manager');?></p></td>
				</tr>
                <tr valign="top"><th scope="row"><?php echo __('Popup Content : ','simple-popup-manager');?></th>
				<td><?php wp_editor($options['contenu'], 'simple_popup_manager_fields_contenu', array('textarea_name'=>'simple_popup_manager_fields[contenu]') ); ?></td></tr>
				<tr valign="top"><th scope="row"><?php echo __('Disable closing popup when clicking outside it  : ','simple-popup-manager');?></th>
				<td><input name="simple_popup_manager_fields[disableOutside]" type="checkbox" value="1" <?php if(!empty($options['disableOutside']))checked('1', $options['disableOutside']); ?> /></td>
				</tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div>
	<?php	
}


function simple_popup_manager_validate($input) {


	$popudata = $input;
	$popudata['bgcolor']='#000000';

	if (preg_match('/^#[A-F0-9]{6}$/i', $input['bgcolor'])) {
   	$popudata['bgcolor']=$input['bgcolor'];
  	}
  	else if (preg_match('/^[A-F0-9]{6}$/i', $input['bgcolor'])) {
	$popudata['bgcolor'] = '#' . $input['bgcolor'];
	}


	return $popudata;
}

function spm_settings_action_links( $links, $file ) {
    
    array_unshift( $links, '<a href="' . admin_url( 'admin.php?page=simple_popup_manager' ) . '">' . __( 'Settings' ) . '</a>' );
 	return $links;

}
add_filter( 'plugin_action_links_'.plugin_basename( __FILE__ ), 'spm_settings_action_links', 10, 2 );

}

else{
/* Files loader */

function simple_popup_manager_js_css(){

$options = get_option('simple_popup_manager_fields');
if(!$options['active']) return;

// 1.3 default context value 
if(!isset($options['context']))$options['context']='home';
if(!isset($options['threshold']))$options['threshold']=$options['largeur'];
if(!isset($options['bgcolor']))$options['bgcolor']='#000000';
if(!isset($options['debug']))$options['debug']=false;

$options['contenu'] = fake_content_spm($options['contenu']);


if($options['context']==='home' && (!is_home() && !is_front_page())) return;

if( $options['debug'] && current_user_can( 'manage_options' ) || !$options['debug'] ){

		//jQuery Cookie
		wp_deregister_script( 'jquery-cookie' );
		wp_register_script( 'jquery-cookie', plugins_url('js/jquery.cookie.js', __FILE__), array( 'jquery' ),SPM_PLUGIN_VERSION);
		wp_enqueue_script( 'jquery-cookie' );
		

		//Simple Popup Manager Javascript
		wp_deregister_script( 'simple-popup-manager' );
		wp_register_script( 'simple-popup-manager', plugins_url('js/simple-popup-manager.js', __FILE__), array( 'jquery', 'jquery-cookie' ),SPM_PLUGIN_VERSION);
		wp_enqueue_script( 'simple-popup-manager' );
		
		//options to Javascript
		wp_localize_script( 'simple-popup-manager', 'servername', site_url());
		wp_localize_script( 'simple-popup-manager', 'options', $options );

		//stylesheets
		wp_register_style( 'simple_popup_manager-style', plugins_url('css/style.css', __FILE__),SPM_PLUGIN_VERSION);
		wp_enqueue_style( 'simple_popup_manager-style' );
		
		//since 1.3.4
		if( isset($options['resetcss']) ){
			wp_register_style( 'simple_popup_manager-reset-style', plugins_url('css/reset.css', __FILE__),SPM_PLUGIN_VERSION);
			wp_enqueue_style( 'simple_popup_manager-reset-style' );	
		}
	
	}
}

add_action('wp_enqueue_scripts', 'simple_popup_manager_js_css'); 


/*
*	Formate un post_content avec les fonctions coeur de the_content, mais n'est pas accroché par les
*	add_filter('the_content') des plugins
*/
function fake_content_spm($content)
{
	
	$content=wptexturize($content);
	$content=wpautop($content);
	$content=convert_chars($content);
	$content=shortcode_unautop($content);
	$content=do_shortcode($content);
	return $content;
}

}

?>