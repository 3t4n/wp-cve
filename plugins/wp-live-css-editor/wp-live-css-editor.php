<?php
/*
Plugin Name: WP Live CSS Editor
Plugin URI: http://www.flashdance.es/dontpanic/doku.php?id=wp-live-css-editor
Description: A live CSS Editor for productivity, from Drupal module Live CSS http://drupal.org/project/live_css de guybedford http://drupal.org/user/746802
Version: 13.09
Author: Sergio Daroca Fernández with a lot of help from pingram3541 and based on guybedford's drupal plugin http://drupal.org/project/live_css
Author URI: http://www.flashdance.es/dontpanic
License: MIT / GPL2
*/

/*  Copyright 2011 Sergio Daroca Fernández

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
//add_cap("editor", "edit_css_live", true);
//add_cap("administrator", "edit_css_live", true);
//if( true  ) //current_user_can('manage_options')

add_action('wp_loaded', 'wp_live_css_editor_init');
function wp_live_css_editor_init(){
	if( current_user_can('delete_themes')  ) //current_user_can('manage_options')
    	{
    		if(is_admin()) {
    			//do nothing
    		} else {
    			add_action('wp_print_styles', 'wp_live_css_editor_enqueue_styles');
    			add_action('wp_print_scripts', 'wp_live_css_editor_enqueue_scripts');
    		}
    		add_action('wp_ajax_live_css_editor_SAVE', 'wp_live_css_editor_SAVE');
    	}
}
function wp_live_css_editor_enqueue_styles(){
	wp_register_style('wp_live_css_editor_styleSheet-1', plugins_url('wp-live-css-editor-css.css', __FILE__));
	wp_enqueue_style( 'wp_live_css_editor_styleSheet-1');
}
function wp_live_css_editor_enqueue_scripts(){
	// embed the main ace javascript
	wp_enqueue_script('wp-live-css-editor-ace',plugins_url('/ace/src/ace.js', __FILE__),array('jquery'));
	// embed the javascript file mode-css.js
	wp_enqueue_script('wp-live-css-editor-mode-css',plugins_url('/ace/src/mode-css.js', __FILE__),array('jquery'));
	// embed the javascript file for ACE theme: theme-twilight.js
	wp_enqueue_script('wp-live-css-editor-ace-thme',plugins_url('/ace/src/theme-twilight.js', __FILE__),array('jquery'));
	// embed the javascript file that makes the AJAX request
	wp_enqueue_script('wp-live-css-editor-ajax',plugins_url('/wp-live-css-editor.js', __FILE__),array('jquery'));
	// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
	wp_localize_script( 'wp-live-css-editor-ajax', 'LiveCSSEditor', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}

function wp_live_css_editor_SAVE (){
	$EOL='\n\r';
	if(function_exists('json_encode')) {
		$feedback.='no json help needed'.$EOL;
	} else {
		$feedback.='json help needed !!'.$EOL;
		/* Untested: if your server has no json support
		if ( ! class_exists('Services_JSON'))
		{
		require_once('../wp-content/plugins/wp-live-css-editor/JSON.php');
		}
		$json = new Services_JSON();

		function json_encode($data = null)
		{
		if($data == null) return false;
		return $json->encode($data);
		}

		function json_decode($data = null)
		{
		if($data == null) return false;
		return $json->decode($data);
		}*/
	}
	if (get_magic_quotes_gpc()) {
		$css = stripslashes($_POST['css']);
	} else {
		$css = $_POST['css'];
	}
	$path=$_POST['href'];
	// Figure out relative path to file and compose backup uri
	if(strpos($path, '?') > 0)
	$path = substr($path, 0, strpos($path, '?'));
	$feedback.='$href: '.$_POST['href'].$EOL;
	$feedback.='$path: '.$path.$EOL;
	$feedback.='$path: '.$path.$EOL;
	$file = str_replace(get_bloginfo('siteurl').'/','',$path);
	$feedback.='get_bloginfo("siteurl"): '.get_bloginfo('siteurl').$EOL;
	$feedback.='$file: '.$file.$EOL;
	$file='../'.$file;
	$feedback.='$file: '.$file.$EOL;
	$path_parts = pathinfo($file);
	$date=date("Y-m-d-H-i-s");
	$fileBackup = $path_parts['dirname'].'/'.$path_parts['basename'].'.'.$date.'.bak';
	$feedback.='$file: '.$file.$EOL;
	$feedback.='$fileBackup: '.$fileBackup.$EOL;
	$feedback.='****.*****: '.realpath('.').$EOL;
	$feedback.='****../wp-content*****: '.realpath('../wp-content').$EOL;
	//copy existing file to dated backup file
	if (!copy($file, $fileBackup)) {
		$jsonEncodedResult = json_encode(array(
			'result' => "failed to copy $file...",
			'feedback' => $feedback
		));
		header( "Content-Type: application/json" );
		die($jsonEncodedResult);
	}
	//save file
	$fh = fopen($file, 'w') or die(json_encode(array(
		'result' => 'Couldnt open file '.$file.' for writing',
		'feedback' => feedback
	)));
	//$search = array('\\\'','\"');
	//$replace = array('\'','"');
	//$css = str_replace($search, $replace, $css);
	fwrite($fh, $css);
	fclose($fh);

	$jsonEncodedResult = json_encode(array(
		'result' => 'success',
		'feedback' => $feedback
	));
	header( "Content-Type: application/json" );
	echo $jsonEncodedResult;
	exit;
}
?>
