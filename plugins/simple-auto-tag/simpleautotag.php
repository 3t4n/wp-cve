<?php
	/*
		Plugin Name: Simple Auto Tag
		Plugin URI: 
		Description: Simple way to create auto tags from post/page title.
		Version: 1.1
		Author: djjmz
		Author URI: 
	*/
	function SaveTitleAsTag($post_ID) {
		$gpt = get_post($post_ID);
		$posttitle = $gpt->post_title;
		$posttitle = strtolower($posttitle);
		if(get_the_tags($post_ID)){
			foreach(get_the_tags($post_ID) as $tag) {
				$tag_name = $tag->name;
				$tag_name  = strtolower($tag_name);
				$posttitle = str_replace($tag_name, '', $posttitle);
			}                           
		}
		if(get_post_status ( $post_ID ) == 'publish'){
			$filename=plugin_dir_path( __FILE__ ).'/words.txt';
			$lines = array();
			$file = fopen($filename, 'r');
			while(!feof($file)) {
				$lines[] = strtolower(trim(fgets($file, 4096)));
			}
			fclose ($file);
			$splittotags = explode(' ', $posttitle);
			foreach ($splittotags as $atag){
				$atag = str_replace(' ', '', $atag);
				$atag = strtolower(trim(preg_replace('#[^\p{L}\p{N}]+#u', '', $atag)));
				if($atag != NULL && !in_array($atag,$lines)){
					wp_set_object_terms($post_ID, $atag, 'post_tag', true );
				}
			}
		}
	}
	add_action('save_post', 'SaveTitleAsTag');
	add_action('admin_menu', 'sat_add_menu');
	function sat_add_menu() {
		add_options_page('S-A-T Settings', 'S-A-T Settings', 'manage_options', 'sat_settings', 'sat_settings');
	}
	function sat_settings() {
		$message = '';
		if(isset($_POST['words']) && !empty($_POST['words'])){
			if (is_writable(plugin_dir_path( __FILE__ ).'/words.txt')) {	
				file_put_contents(plugin_dir_path( __FILE__ ).'/words.txt',$_POST['words']);
				$message = 'Words list updated<br>';	
			}
			else{
				chmod(plugin_dir_path( __FILE__ ).'/words.txt', 0777);	
				$message = 'Can\'t update words list!<br>';	
			}
		}
		$file_read = file_get_contents(plugin_dir_path( __FILE__ ).'/words.txt');
		echo '<div class="wrap">
		<div class="metabox-holder has-center-sidebar"> 
		<div id="post-body">
		<div id="post-body-content">
		<div class="postbox">
		<div class="inside">
		<div align="center">
		<h2>'.$message.'</h2><br>
		<form method = "post" action = "">
		Words (one word per line):<br>
		<textarea rows="4" cols="50" name="words">'.$file_read.'</textarea><br>
		<input type="submit" value="Submit">
		</form><br><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="5NTQ9DTFUDX5L">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

		</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>';	
	}
?>