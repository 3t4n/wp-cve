<?php
/*
	Plugin Name: Compact MCE
	Version: 19.05
	Plugin URI: https://linesh.com/projects/compact-mce/
	Description: A simple plugin that reorganize your WordPress editor TinyMCE controls.
	Author: Linesh Jose
	Author URI: https://linesh.com/
	License: GPLv2 or later
	Text Domain: compact-mce
*/

// Adding plugin meta links  -------------------->
	function lj_cmce_plugin_actions( $links, $file )	
	{
		$plugin = plugin_basename(plugin_dir_path(__FILE__).'index.php');
		if ($file == $plugin) {
		$links[] = '<a href="https://linesh.com/make-a-donation/" target="_blank">
						<span class="dashicons dashicons-heart"></span>' . __('Donate','compact-mce'  ) . '</a>';
		$links[] = '<a href="https://linesh.com/forums/forum/plugins/compact-mce/" target="_blank">
						<span class="dashicons dashicons-sos"></span>' . __('Support', 'compact-mce'  ) . '</a>';
		}
		return $links;
	}
	add_filter( 'plugin_row_meta', 'lj_cmce_plugin_actions', 10, 2 ); 

// Including custom external tinymce plugiins -------------------->
	function lj_cmce_load_extra_plugins( $plugins ) {
		foreach(array('searchreplace','contextmenu','codesample','table','visualblocks') as $item){
			$plugins[$item] = plugins_url('tinymce/',__FILE__ ) .$item.'/plugin.min.js';
		}
		return $plugins;
	}
	add_filter( 'mce_external_plugins', 'lj_cmce_load_extra_plugins' );
	
	
// Adding first row controls -------------------->
	function lj_cmce_first_row_controls($buttons)
	{
			$remove= array(	0=>'bold','italic','strikethrough','alignleft','aligncenter','alignright',
							'link','unlink','wp_more','bullist' ,'numlist','blockquote','hr','spellchecker','dfw' ,'wp_adv'
						);
			$new_buttons=array(	'styleselect','bold','italic','underline','strikethrough','alignleft','aligncenter','alignright','alignjustify',
								'bullist','numlist','outdent','indent','link','unlink','table','blockquote','hr','charmap','codesample','forecolor','backcolor',
								'pastetext','wp_more','visualblocks','searchreplace','removeformat'
						);			
			foreach($buttons as $index=>$item){
				if(in_array($item,$remove)){
					unset($buttons[$index]);
				}else{
					$new_buttons[]=$item;
				}
			}
			$new_buttons[] ='wp_help';
			$new_buttons[] ='fullscreen';
			return $new_buttons;
		}
	add_filter("mce_buttons", "lj_cmce_first_row_controls");
	
// adding second row controls  -------------------->
	function lj_cmce_second_row_controls($buttons){
		$new_buttons=array();
		$remove= array(	0=>'formatselect','underline','alignjustify','forecolor','pastetext',
			'removeformat','charmap','outdent','indent','undo' ,'redo','wp_help'
		);
		foreach($buttons as $index=>$item){
			if(in_array($item,$remove)){
				unset($buttons[$index]);
			}else{
				$new_buttons[]=$item;
			}
		}
		return $new_buttons;
	}
	add_filter("mce_buttons_2", "lj_cmce_second_row_controls");
?>