<?php
/*
 Plugin Name: Zia3-JS-CSS
Plugin URI: http://plugins.zia3.com/plugins/wordpress/zia3-css-javascript/
Description: Zia3 Custom CSS JS Files per page/post.
Version: 1.0
Author: Zia3
Author URI: http://zia3.com
License: GPL3
Contributors: zia3wp
Donate link: http://plugins.zia3.com/donate/
Tags: per page CSS, per page JavaScript, admin, posts, page, style, stylesheet, stylesheets, personal style, html style, javascript, css,, inline, syntax highlighting, pretify
Requires at least: 3.3
Tested up to: 4.5
*/

/*
 Zia3-JS-CSS
Copyright (C) 2013  Serkan Azmi http://zia3.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version GPL3.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/

//TODO: minify CSS/JS

include 'zia3_meta_options.php';

//scripts / css for metaboxes
//scripts for Zia3 CSS JS plugin
function zia3_css_js_scripts_important()
{
	if(is_admin()) { /* loading of your scripts in the admin*/
		wp_register_script( 'jquery_ui_custom', plugins_url( '/js/jquery-ui.min.js', __FILE__ ));
		wp_register_script( 'meta_fields', plugins_url( '/js/meta-fields.js', __FILE__ ));
		wp_register_script( 'syntax-highlighter-core', plugins_url( '/syntax_highlighter/lib/codemirror.js', __FILE__ ) );
		wp_register_script( 'syntax-highlighter-show-hint', plugins_url( '/syntax_highlighter/addon/hint/show-hint.js', __FILE__ ) );
		wp_register_script( 'syntax-highlighter-javascript-hint', plugins_url( '/syntax_highlighter/addon/hint/javascript-hint.js', __FILE__ ) );
		wp_register_script( 'syntax-highlighter-mode-javascript', plugins_url( '/syntax_highlighter/mode/javascript/javascript.js', __FILE__ ) );
		wp_register_script( 'syntax-highlighter-mode-css', plugins_url( '/syntax_highlighter/mode/css/css.js', __FILE__ ) );		
		
		wp_enqueue_script( 'jquery_ui_custom' );
		wp_enqueue_script( 'syntax-highlighter-core' );
		wp_enqueue_script( 'syntax-highlighter-show-hint' );
		wp_enqueue_script( 'syntax-highlighter-javascript-hint' );
		wp_enqueue_script( 'syntax-highlighter-mode-css' );
		wp_enqueue_script( 'syntax-highlighter-mode-javascript' );		
		wp_enqueue_script( 'meta_fields' );
	}	
}


function zia3_css_js_styles_important()
{
	if(is_admin()) {   /* loading of your stylesheet in the admin*/

		wp_register_style( 'syntax-highlighter-style-core', plugins_url( '/syntax_highlighter/lib/codemirror.css', __FILE__ ), array(), '1.0', 'all' );
		wp_register_style( 'syntax-highlighter-style-show-hint', plugins_url( '/syntax_highlighter/addon/hint/show-hint.css', __FILE__ ), array(), '1.0', 'all' );
		wp_register_style( 'syntax-highlighter-theme', plugins_url('/syntax_highlighter/theme/ambiance.css', __FILE__) );

		wp_enqueue_style( 'syntax-highlighter-style-core' );
		wp_enqueue_style( 'syntax-highlighter-style-show-hint' );	
		wp_enqueue_script( 'syntax-highlighter-theme' );
	}
}


function zia3_css_js_highlight_script_important()
{
	wp_register_script( 'syntax_highlighter', plugins_url( '/js/syntax_highlighter.js', __FILE__ ));
	wp_enqueue_script( 'syntax_highlighter' );
}


// Add the Meta Box
function zia3_add_custom_meta_box() {
	//only load these scripts when metaboxes are visible
	add_action( 'admin_enqueue_scripts', 'zia3_css_js_scripts_important' );
	add_action( 'admin_enqueue_scripts', 'zia3_css_js_styles_important' );
	add_action( 'admin_footer', 'zia3_css_js_highlight_script_important' );
	
	add_meta_box(
	'zia3_meta_box_css', // $id
	'Zia3 Custom CSS JavaScript Files Plugin', // $title
	'zia3_show_meta_box', // $callback
	'post', // $post
	'normal', // $context
	'high'); // $priority

	add_meta_box(
	'zia3_meta_box_css', // $id
	'Zia3 Custom CSS JavaScript Files Plugin', // $title
	'zia3_show_meta_box', // $callback
	'page', // $page
	'normal', // $context
	'high'); // $priority

}
add_action('add_meta_boxes', 'zia3_add_custom_meta_box');

// Field Array
$prefix = 'custom_';
$custom_meta_fields = array(
		array(
				'label'=> 'Text Input',
				'desc'  => 'A description for the field.',
				'id'    => $prefix.'text',
				'type'  => 'text'
		),
		array(
				'label'=> 'Textarea',
				'desc'  => 'A description for the field.',
				'id'    => $prefix.'textarea',
				'type'  => 'textarea'
		),
		array(
				'label'=> 'Checkbox Input',
				'desc'  => 'A description for the field.',
				'id'    => $prefix.'checkbox',
				'type'  => 'checkbox'
		),
		array(
				'label'=> 'Select Box',
				'desc'  => 'A description for the field.',
				'id'    => $prefix.'select',
				'type'  => 'select',
				'options' => array (
						'one' => array (
								'label' => 'Option One',
								'value' => 'one'
						),
						'two' => array (
								'label' => 'Option Two',
								'value' => 'two'
						),
						'three' => array (
								'label' => 'Option Three',
								'value' => 'three'
						)
				)
		),
		array (
				'label' => 'Checkbox Group',
				'desc'  => 'A description for the field.',
				'id'    => $prefix.'checkbox_group',
				'type'  => 'checkbox_group',
				'options' => array (
						'one' => array (
								'label' => 'Option One',
								'value' => 'one'
						),
						'two' => array (
								'label' => 'Option Two',
								'value' => 'two'
						),
						'three' => array (
								'label' => 'Option Three',
								'value' => 'three'
						)
				)
		),
		array(
				'label' => 'Custom CSS Files',
				'desc'  => 'Select the CSS files to be included.',
				'id'    => $prefix.'checkbox_group_css',
				'type'  => 'checkbox_group_combo_css',
				'options' => array (

				)
		),
		array(
				'label' => 'Custom JavaScript Files',
				'desc'  => 'Select the JavaScript files to be included.',
				'id'    => $prefix.'checkbox_group_js',
				'type'  => 'checkbox_group_combo_js',
				'options' => array (

				)
		),
		array(
				'label' => 'Repeatable',
				'desc'  => 'A description for the field.',
				'id'    => $prefix.'repeatable',
				'type'  => 'repeatable'
		)
);


$zia3_meta_fields = array(
		array(
				'label' => 'Repeatable',
				'desc'  => 'A description for the field.',
				'id'    => $prefix.'repeatable',
				'link'  => '<a href=""></a>',
				'type'  => 'repeatable_combo'
		),
		array (
				'label' => 'Checkbox Group',
				'desc'  => 'A description for the field.',
				'id'    => $prefix.'checkbox_group',
				'type'  => 'checkbox_group_combo',
				'options' => array (
						'one' => array (
								'label' => 'Option One',
								'value' => 'one',
								'link' => 'http://zia3.com'
						),
						'two' => array (
								'label' => 'Option Two',
								'value' => 'two',
								'link' => 'http://zia3.com'
						),
						'three' => array (
								'label' => 'Option Three',
								'value' => 'three',
								'link' => 'http://zia3.com'
						)
				)
		),
		array (
				'label' => 'Checkbox Group',
				'desc'  => 'A description for the field.',
				'id'    => $prefix.'checkbox_group',
				'type'  => 'checkbox_group_combo',
				'options' => array (
						'one' => array (
								'label' => 'Option Two',
								'value' => 'two',
								'link' => 'http://zia3.com'
						)
				)
		)
		 
);

$temp = array(
		array(
				'label' => 'Custom CSS Files',
				'desc'  => 'Select the CSS files to be included.',
				'id'    => $prefix.'checkbox_group_css',
				'type'  => 'checkbox_group_combo_css',
				'options' => array (

				)
		),
		array(
				'label' => 'Custom Javascript Files',
				'desc'  => 'Select the JavaScript files to be included.',
				'id'    => $prefix.'checkbox_group_js',
				'type'  => 'checkbox_group_combo_js',
				'options' => array (

				)
		)
);

function zia3_populate_meta_fields(){

	global $zia3_meta_fields, $post, $temp, $zia3meta_css_dir, $zia3meta_js_dir, $zia3meta_css_url, $zia3meta_js_url, $zia3meta_selected_dir_css_label, $zia3meta_selected_js_css_label;

	$options = get_option( 'zia3meta_plugin_options' );

	$zia3meta_css_dir = esc_url($options['selected_dir_css']);
	$zia3meta_js_dir = esc_url($options['selected_dir_js']);
	$zia3meta_selected_dir_css_label = $options['selected_dir_css_label'];
	$zia3meta_selected_js_css_label = $options['selected_js_css_label'];

	$base_custom_theme_dir = get_stylesheet_directory();
	$base_theme_url = get_stylesheet_directory_uri();
	$custom_base_css_dir = "/css/";
	$custom_base_js_dir = "/js/";
	$css_dir_path = $base_custom_theme_dir.$custom_base_css_dir;
	$js_dir_path = $base_custom_theme_dir.$custom_base_js_dir;
	$css_dir_path = $base_custom_theme_dir.$zia3meta_css_dir;
	$js_dir_path = $base_custom_theme_dir.$zia3meta_js_dir;
	$css_files = zia3_display_files_in_dir("$css_dir_path");
	$js_files = zia3_display_files_in_dir("$js_dir_path");
	$css_file_count =  count($css_files);
	$js_file_count =  count($js_files);
	$current_file_counter = 0;
	$category_counter = 0;

	foreach ($temp as $field => $value) {
		switch($value['type']) {
			// checkbox_group_combo_css
			case 'checkbox_group_combo_css':
				if($css_files) {
					foreach($css_files as $entry) {
						if(is_file($css_dir_path.$entry)) {
							$temp[$category_counter]['options'][$current_file_counter]['label'] = $entry;
							$temp[$category_counter]['options'][$current_file_counter]['value'] = $base_theme_url.$zia3meta_css_dir.$entry;
							$temp[$category_counter]['options'][$current_file_counter]['link'] = $base_theme_url.$zia3meta_css_dir.$entry;
							$current_file_counter++;
						}
					}
				}
				$current_file_counter = 0;
				break;
				// checkbox_group_combo_js
			case 'checkbox_group_combo_js':
				if($js_files) {
					foreach($js_files as $entry) {
						if(is_file($js_dir_path.$entry)) {
							$temp[$category_counter]['options'][$current_file_counter]['label'] = $entry;
							$temp[$category_counter]['options'][$current_file_counter]['value'] = $base_theme_url.$zia3meta_js_dir.$entry;
							$temp[$category_counter]['options'][$current_file_counter]['link'] = $base_theme_url.$zia3meta_js_dir.$entry;
							$current_file_counter++;
						}
					}
				}
				$current_file_counter = 0;
				break;
		}
		$category_counter++;
	}
	return $temp;
}

function zia3_show_meta_box() {
	global $custom_meta_fields, $post;
	$custom_meta_fields = zia3_populate_meta_fields();
	// Use nonce for verification
	echo '<input type="hidden" name="zia3_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

	// Begin the field table and loop
	echo '<table class="form-table">';
	foreach ($custom_meta_fields as $field) {
		// get value of this field if it exists for this post
		$meta = get_post_meta($post->ID, $field['id'], true);
		// begin a table row with
		echo '<tr>
				<th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
						<td>';
		switch($field['type']) {
			// case items will go here
			// text
			case 'text':
				echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" /> <br /><span class="description">'.$field['desc'].'</span>';
				break;
				// textarea
			case 'textarea':
				echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea> <br /><span class="description">'.$field['desc'].'</span>';
				break;
				// checkbox
			case 'checkbox':
				echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/> <label for="'.$field['id'].'">'.$field['desc'].'</label>';
				break;
				// checkbox_group
			case 'checkbox_group':
				foreach ($field['options'] as $option) {
					echo '
							<input type="checkbox" value="'.$option['value'].'" name="'.$field['id'].'[]" id="'.$option['value'].'"',$meta && in_array($option['value'], $meta) ? ' checked="checked"' : '',' />
									<label for="'.$option['value'].'">'.$option['label'].'</label><br />';
				}
				echo '<span class="description">'.$field['desc'].'</span>';
				break;
				// checkbox_group_combo
			case 'checkbox_group_combo':
				foreach ($field['options'] as $option) {
					echo '<input type="checkbox" value="'.$option['value'].'" name="'.$field['id'].'[]" id="'.$option['value'].'"',$meta && in_array($option['value'], $meta) ? ' checked="checked"' : '',' />
							<label for="'.$option['value'].'">'.$option['label'].'</label>
									<a href="'.$option['link'].'">'.$option['link'].'</a><br />';
				}
				echo '<span class="description">'.$field['desc'].'</span>';
				break;
				// checkbox_group_combo_css
			case 'checkbox_group_combo_css':
				foreach ($field['options'] as $option) {
					echo '<input type="checkbox" value="'.$option['value'].'" name="'.$field['id'].'[]" id="'.$option['value'].'"',$meta && in_array($option['value'], $meta) ? ' checked="checked"' : '',' />
							<label for="'.$option['value'].'">'.$option['label'].'</label>
									<a href="'.$option['link'].'">'.$option['link'].'</a><br />';
				}
				echo '<span class="description">'.$field['desc'].'</span>';
				break;
				// checkbox_group_combo_js
			case 'checkbox_group_combo_js':
				foreach ($field['options'] as $option) {
					echo '<input type="checkbox" value="'.$option['value'].'" name="'.$field['id'].'[]" id="'.$option['value'].'"',$meta && in_array($option['value'], $meta) ? ' checked="checked"' : '',' />
							<label for="'.$option['value'].'">'.$option['label'].'</label>
									<a href="'.$option['link'].'">'.$option['link'].'</a><br />';
				}
				echo '<span class="description">'.$field['desc'].'</span>';
				break;
				// select
			case 'select':
				echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
				foreach ($field['options'] as $option) {
					echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
				}
				echo '</select><br /><span class="description">'.$field['desc'].'</span>';
				break;
				// radio
			case 'radio':
				foreach ( $field['options'] as $option ) {
					echo '<input type="radio" name="'.$field['id'].'" id="'.$option['value'].'" value="'.$option['value'].'" ',$meta == $option['value'] ? ' checked="checked"' : '',' />
							<label for="'.$option['value'].'">'.$option['label'].'</label><br />';
				}
				break;
				// radio_group
			case 'radio_group':
				foreach ( $field['options'] as $option ) {
					echo '<input type="radio" name="'.$field['id'].'" id="'.$option['value'].'" value="'.$option['value'].'" ',$meta == $option['value'] ? ' checked="checked"' : '',' />
							<label for="'.$option['value'].'">'.$option['label'].'</label><br />';
				}
				break;
				// repeatable
			case 'repeatable':
				echo '<a class="repeatable-add button" href="#">+</a>
						<ul id="'.$field['id'].'-repeatable" class="custom_repeatable">';
				$i = 0;
				if ($meta) {
					foreach($meta as $row) {
						echo '<li>
								<span class="sort hndle">|||</span>
								<input type="text" name="'.$field['id'].'['.$i.']" id="'.$field['id'].'" value="'.$row.'" size="50" />
										<a class="repeatable-remove button" href="#">-</a></li>';
						$i++;
					}
				} else {
					echo '<li><span class="sort hndle">|||</span>
							<input type="text" name="'.$field['id'].'['.$i.']" id="'.$field['id'].'" value="" size="30" />
									<a class="repeatable-remove button" href="#">-</a></li>';
				}
				echo '</ul>
						<span class="description">'.$field['desc'].'</span>';
				break;
				// repeatable
			case 'repeatable_combo':
				echo '<a class="repeatable-add button" href="#">+</a>
						<ul id="'.$field['id'].'-repeatable" class="custom_repeatable">';
				$i = 0;
				if ($meta) {
					foreach($meta as $row) {
						echo '<li><span>'.$field['link'].'</span>
								<span class="sort hndle">|||</span>
								<input type="text" name="'.$field['id'].'['.$i.']" id="'.$field['id'].'" value="'.$row.'" size="30" />
										<a class="repeatable-remove button" href="#">-</a></li>';
						$i++;
					}
				} else {
					echo '<li><span>'.$field['link'].'</span>
							<span class="sort hndle">|||</span>
							<input type="text" name="'.$field['id'].'['.$i.']" id="'.$field['id'].'" value="" size="30" />
									<a class="repeatable-remove button" href="#">-</a></li>';
				}
				echo '</ul>
						<span class="description">'.$field['desc'].'</span>';
				break;
		} //end switch
		echo '</td></tr>';
	} // end foreach
	echo '</table>'; // end table
}


// Save the Data
function zia3_save_custom_meta($post_id) {
	global $custom_meta_fields;

	// verify nonce
	if (!wp_verify_nonce($_POST['zia3_meta_box_nonce'], basename(__FILE__)))
		return $post_id;
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return $post_id;
	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id))
			return $post_id;
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}

	// loop through fields and save the data
	foreach ($custom_meta_fields as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	} // end foreach
}
add_action('save_post', 'zia3_save_custom_meta');


function zia3_insert_css_js_file_posts() {
	global $custom_meta_fields;
	$css_key = 'custom_checkbox_group_css';
	$js_key = 'custom_checkbox_group_js';
	$file_counter = 0;
	$my_css_meta = get_post_meta(get_the_ID(), $css_key, true);
	$my_js_meta = get_post_meta(get_the_ID(), $js_key, true);

	//inset css
	if (is_page() || is_single()) {
		if (have_posts()) : while (have_posts()) : the_post();
		// loop through fields and save the data
		if($my_css_meta) {
			foreach ($my_css_meta as $field) {
				echo "<link rel='stylesheet' id='zia3-css_file_".$file_counter."'  href='".$field."' type='text/css' media='all'/>\n";
				$file_counter++;
			}
		}
		endwhile; endif;
		rewind_posts();
	}

	//rest counter to 0 for js files
	$file_counter = 0;

	//insert js
	if (is_page() || is_single()) {
		if (have_posts()) : while (have_posts()) : the_post();
		// loop through fields and save the data
		if($my_js_meta) {
			foreach ($my_js_meta as $field) {
				echo "<script type='text/javascript' src='".$field."'></script>\n";
				$file_counter++;
			}
		}
		endwhile; endif;
		rewind_posts();
	}
}
add_action('wp_head','zia3_insert_css_js_file_posts', 40);

/////////////////////////////////////////////////////////////////////
//Custom CSS Widget
add_action('admin_menu', 'zia3meta_custom_css_hooks');
add_action('save_post', 'zia3meta_save_custom_css');
add_action('wp_head','zia3meta_insert_custom_css', 50);
function zia3meta_custom_css_hooks() {
	add_meta_box('zia3meta_custom_css_container', 'Zia3 Inline Custom CSS', 'zia3meta_custom_css_input', 'post', 'normal', 'high');
	add_meta_box('zia3meta_custom_css_container', 'Zia3 Inline Custom CSS', 'zia3meta_custom_css_input', 'page', 'normal', 'high');
}
function zia3meta_custom_css_input() {
	global $post;
	echo '<input type="hidden" name="zia3meta_custom_css_noncename" id="zia3meta_custom_css_noncename" value="'.wp_create_nonce('zia3meta_custom-css').'" />';
	echo '<textarea name="zia3meta_custom_css" id="zia3meta_custom_css" rows="5" cols="30" style="width:100%;">'.get_post_meta($post->ID,'_zia3meta_custom_css',true).'</textarea>';
}
function zia3meta_save_custom_css($post_id) {
	if (!wp_verify_nonce($_POST['zia3meta_custom_css_noncename'], 'zia3meta_custom-css')) return $post_id;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	$custom_css = $_POST['zia3meta_custom_css'];
	update_post_meta($post_id, '_zia3meta_custom_css', $custom_css);
}
function zia3meta_insert_custom_css() {
	if (is_page() || is_single()) {
		$inline_css = get_post_meta(get_the_ID(), '_zia3meta_custom_css', true);
		if($inline_css) {
			if (have_posts()) : while (have_posts()) : the_post();
			echo "\n<style type='text/css' id='zia3_meta_inline_css'>\n".$inline_css."\n</style>\n";
			endwhile; endif;
			rewind_posts();
		}
	}
}
/////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////
// Custom JavaScript in Pages/Posts  (...lots of repeated code, could be better)
add_action('admin_menu', 'zia3meta_custom_js_hooks');
add_action('save_post', 'zia3meta_save_custom_js');
add_action('wp_head','zia3meta_insert_custom_js', 50);
function zia3meta_custom_js_hooks() {
	add_meta_box('zia3meta_custom_js_container', 'Zia3 Inline Custom JavaScript', 'zia3meta_custom_js_input', 'post', 'normal', 'high');
	add_meta_box('zia3meta_custom_js_container', 'Zia3 Inline Custom JavaScript', 'zia3meta_custom_js_input', 'page', 'normal', 'high');
}
function zia3meta_custom_js_input() {
	global $post;
	echo '<input type="hidden" name="zia3meta_custom_js_noncename" id="zia3meta_custom_js_noncename" value="'.wp_create_nonce('zia3meta_custom-js').'" />';
	echo '<textarea name="zia3meta_custom_js" id="zia3meta_custom_js" rows="5" cols="30" style="width:100%;">'.get_post_meta($post->ID,'_zia3meta_custom_js',true).'</textarea>';
}
function zia3meta_save_custom_js($post_id) {
	if (!wp_verify_nonce($_POST['zia3meta_custom_js_noncename'], 'zia3meta_custom-js')) return $post_id;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	$custom_js = $_POST['zia3meta_custom_js'];
	update_post_meta($post_id, '_zia3meta_custom_js', $custom_js);
}
function zia3meta_insert_custom_js() {
	if (is_page() || is_single()) {
		if (have_posts()) : while (have_posts()) : the_post();
		$inline_js = get_post_meta(get_the_ID(), '_zia3meta_custom_js', true);
		if ($inline_js) {
			echo "\n<script type='text/javascript'>\n".$inline_js."\n</script>\n";
		}
		endwhile; endif;
		rewind_posts();
	}
}

//return array of files in path
function zia3_display_files_in_dir($path) {
	$files[] = array();
	if(is_dir($path)) {
		if ($handle = opendir($path)) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") {
					//echo "$entry\n";
					$files[] = $entry;
				}
			}
			closedir($handle);
		}
	}
	return $files;
}

function zia3_in_multiarray($elem, $array)
{
	$top = sizeof($array) - 1;
	$bottom = 0;
	while($bottom <= $top)
	{
		if($array[$bottom] == $elem)
			return true;
		else
		if(is_array($array[$bottom]))
		if(in_multiarray($elem, ($array[$bottom])))
			return true;
		 
		$bottom++;
	}
	return false;
}
?>