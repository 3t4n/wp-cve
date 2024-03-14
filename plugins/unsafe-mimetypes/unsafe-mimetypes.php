<?php
/*
Plugin Name: Unsafe Mimetypes
Plugin URI: http://redmine.lukedrummond.net/projects/unsafe-mimetypes
Description: Allows users to add file types to the whitelist of allowed media formats.  This is especially useful if you wish to distribute binaries, or specialist media formats (i.e. not just mp3, jpg and pdf)
Version: 0.1.4
Author: Luke Drummond
Author URI: https://lukedrummond.net
License: zlib
*/

require_once('unsafe-mimetypes-mimelist.php');
if(!function_exists('wp_get_current_user')) {
	include(ABSPATH . "wp-includes/pluggable.php");
}

function unsafe_mime_lang(){
    load_plugin_textdomain('unsafe-mimetypes', false, dirname(plugin_basename( __FILE__ )));
}

function unsafe_mime_upload_filters()
{       
        if (get_option('unsafe_mime_settings_allow_builtins') === 'true'){
            $existing_mimes =  wp_get_mime_types();
        }
		$priv = get_option('unsafe_mime_settings_priv');
		$mimes_list = unsafe_mime_known_list();
		if( (($priv === 'all') && current_user_can('upload_files')) || (($priv === 'admin') && current_user_can('manage_options')) ){
			$mimes = explode(' ', get_option('unsafe_mime_settings_list'));
			if(isset($mimes)){
				foreach($mimes as $mime){
					$existing_mimes[$mime] = (array_key_exists($mime, $mimes_list) === true) ? $mimes_list[$mime] :'application/octet-stream';
				}
				return $existing_mimes;
			}
			return array();
		}
		else return array();
}

function unsafe_mime_settings_page()
{
	if(!current_user_can('manage_options')){
		die(__('setting option not allowed for the current user.', 'unsafe-mimetypes'));
	}
	if(isset($_POST)){
	    if (isset($_POST['option_page']) && ($_POST['option_page'] == 'unsafe-mime-group') && ($_POST['action'] == 'update')){
	        if(isset($_POST['mime_list'])){
                update_option('unsafe_mime_settings_list', sanitize_text_field(strtolower($_POST['mime_list'])));
	        }
	        
            if(isset($_POST['mime_priv'])){
		        $mime_priv =$_POST['mime_priv'];
		        if(!(($mime_priv === 'admin') || ($mime_priv === 'all'))){
			        $mime_priv = 'admin';
		        }
		       $ret = update_option('unsafe_mime_settings_priv', $mime_priv);
	        }
            
            if(isset($_POST['allow_predefined_types'])){
                $allow_predefined_types = (($_POST['allow_predefined_types'] === 'true') ? 'true' : 'false');
                update_option('unsafe_mime_settings_allow_builtins',  $allow_predefined_types);
            }
            else{ 
                update_option('unsafe_mime_settings_allow_builtins',  'false');
            }
	    }
	}
	?>
	
	<div class="wrap">
	    <?php screen_icon(); ?>
	    <h2>Configure Custom Mimetypes</h2>
	    <form method="post" action="options-general.php?page=mimetypes-settings">
		<?php
		settings_fields('unsafe-mime-group');
		do_settings_sections('unsafe-mime-setopt');
		submit_button(); 
		?>
		</form>
	</div>
	<?php
}

function unsafe_mime_ui_info()
{
	echo(__('Configure which mimetypes you want your users to be able to upload. ','unsafe-mimetypes').'<br/>');
	echo(__('Choose whether all content editors, or just WordPress Administrators can upload the \'unsafe\' types. ','unsafe-mimetypes') . '<br/><br/>');
	echo(__('The current list of custom mimetypes is as follows: ', 'unsafe-mimetypes') . '<small><em>' . sanitize_text_field(get_option('unsafe_mime_settings_list')) . '</em></small>');
}

function unsafe_mime_ui_list_box()
{
	?><input type="text" id="mime_list" name="mime_list" value="<?=sanitize_text_field(get_option('unsafe_mime_settings_list'));?>" /><?php
}

function unsafe_mime_ui_priv_select()
{
	$opt = sanitize_text_field(get_option('unsafe_mime_settings_priv'));
	$a_friendly = ($opt === 'admin')? __('Admins Only', 'unsafe-mimetypes'): __('All uploaders', 'unsafe-mimetypes');
	$a_val = $opt;
	$b_friendly = ($opt === 'admin')? __('All uploaders', 'unsafe-mimetypes'): __('Admins Only', 'unsafe-mimetypes');
	$b_val = ($opt === 'admin')? 'all':'admin';
	?>
	
	<select name="mime_priv">
		<option value="<?=$a_val?>"><?=$a_friendly?></option>
		<option value="<?=$b_val?>"><?=$b_friendly?></option>
	</select>
	<?php
}

function unsafe_mime_ui_allow_builtins()
{
    $opt = sanitize_text_field(get_option('unsafe_mime_settings_allow_builtins'));
    $checked = (($opt === 'true')?'checked':'');
    ?>
    <input type="checkbox" name="allow_predefined_types" value="true" <?=$checked?>>
    <?php
}

function unsafe_mime_admin_menu()
{
	add_options_page(
		__('Configure custom mime types','unsafe-mimetypes') , 
		__('Allowed Mimetypes','unsafe-mimetypes'), 
		'manage_options', 
		'mimetypes-settings', 
		'unsafe_mime_settings_page');
}

function unsafe_mime_register_ui()
{
	register_setting('unsafe-mime-group', 'custom-mime-setting');
	add_settings_section(
	    'setting_section_id',
	    __('Setting','unsafe-mimetypes'),
	    'unsafe_mime_ui_info',
	    'unsafe-mime-setopt'
	);
	add_settings_field(
	    'mime_list', 
	    __('List of file extensions','unsafe-mimetypes') . '<br> <small>'.__('no dot, space separated', 'unsafe-mimetypes') . '</small>',
	    'unsafe_mime_ui_list_box', 
	    'unsafe-mime-setopt',
	    'setting_section_id'
	);
	add_settings_field(
	    'mime_priv', 
	    __('User level required to upload unsafe mimetypes', 'unsafe-mimetypes'), 
	    'unsafe_mime_ui_priv_select', 
	    'unsafe-mime-setopt',
	    'setting_section_id'
	);
	add_settings_field(
	    'inbuilt_types',
	    __('Allow the default WordPress list of allowed types (all uploaders)', 'unsafe-mimetypes'),
	    'unsafe_mime_ui_allow_builtins',
	    'unsafe-mime-setopt',
	    'setting_section_id'
	);
}

add_action('plugins_loaded', 'unsafe_mime_lang');

if(is_admin()){
	if (current_user_can('manage_options') ){
		add_action('admin_menu', 'unsafe_mime_admin_menu' );
		add_action('admin_init', 'unsafe_mime_register_ui');
	}
	add_filter('upload_mimes', 'unsafe_mime_upload_filters');
}
?>
