<?php

add_action('admin_menu', 'playerjs_com_admin');

function playerjs_com_admin(){
	add_options_page( 'PlayerJS', 'PlayerJS', 'manage_options', 'playerjs_com_admin', 'playerjs_com_admin_html');
    if(stripos($_SERVER['REQUEST_URI'],'post.php')!==false || stripos($_SERVER['REQUEST_URI'], 'post-new.php') !==false) {
        add_action('media_buttons', 'playerjs_com_editor_button', 10);
    }
}

function playerjs_com_editor_button() {
    global $post;
    wp_enqueue_script('playerjs-editor-js', plugins_url('',__FILE__).'/playerjs_com_admin.js');
    wp_enqueue_style('playerjs-editor-css', plugins_url('',__FILE__).'/playerjs_com_admin.css');
    echo '<span id="playerjs_com_editor_button" class="button">PlayerJS</span> ';
}

function playerjs_com_admin_html(){
	?>
	<div class="wrap"><a href="https://playerjs.com" target="_blank"><div style="float:right;width:33px;margin:20px 20px 0 0;height:50px;background:url('<?php echo(plugins_url('',__FILE__));?>/playerjs.png') no-repeat 0 0;background-size:cover;"></div></a>
		<h1>PlayerJS</h1>
		<form action="options.php" method="POST">
			<?php
				settings_fields('settings');
				do_settings_sections('page');
				submit_button();
                
                echo('<p>PlayerJS support HTML5 Video, HTML5 Audio, HLS, DASH, YouTube and Vimeo.<br>Please look our  <a href="https://playerjs.com/docs/q=wordpress" target="_blank">plugin page</a> and <a href="https://playerjs.com/docs" target="_blank">docs</a> if you have any questions.</p>');
			?>
		</form>
	</div>
	<?php
}

add_action('admin_init', 'playerjs_com_settings_page');

function playerjs_com_settings_page(){

	register_setting('settings', 'playerjs_com_script_path', 'playerjs_com_sanitize_callback');
    register_setting('settings', 'playerjs_com_width', 'playerjs_com_sanitize_callback');
    register_setting('settings', 'playerjs_com_customheight', 'playerjs_com_sanitize_callback');
    register_setting('settings', 'playerjs_com_height', 'playerjs_com_sanitize_callback');
    register_setting('settings', 'playerjs_com_align', 'playerjs_com_sanitize_callback');

	add_settings_section('section', 'Create a custom player for free &rarr; <a href="https://playerjs.com" target="_blank">playerjs.com</a>', '', 'page'); 

	add_settings_field('path', "Path to created player", 'pjs_settings_path', 'page', 'section');
	add_settings_field('size', 'Options', 'pjs_settings_size', 'page', 'section');
}

function pjs_settings_path(){
	$path = get_option('playerjs_com_script_path');
	$path = $path ? $path['input'] : '';
    if(trim($path)==''){
        $path = plugins_url('').'/playerjs/playerjs_default.js';
    }
    
    $check_path = '';
    /*if(strpos(file_get_contents($path),'Player')===false){
        $check_path = '<p style="color:#f00">File not found</p>';
    }*/
	echo('<input type="text" name="playerjs_com_script_path[input]" style="width:100%;font-size:90%;max-width:800px;font-family:Courier New,monotype"  value="'.esc_attr($path).'" />'.$check_path);
}

function pjs_settings_size(){
    
	$width = playerjs_com_get_option('width',0,'100%');
    
    $height = playerjs_com_get_option('height',0,'280px');
    
    $customheight = playerjs_com_get_option('customheight',0,'');
    
    $align = playerjs_com_get_option('align',0,'');

    echo('<span style="display:inline-block;width:70px">Width</span> <input type="text" name="playerjs_com_width[input]" style="width:60px" value="'.esc_attr($width).'" />');
    
    echo('<p><span style="display:inline-block;width:70px">Height</span> <select name="playerjs_com_customheight[input]"><option value="">auto</option><option value="custom" '.($customheight=="custom"?"selected":"").'>custom</option></select> '.($customheight=="custom"?'<input type="text" name="playerjs_com_height[input]" style="width:60px" value="'.esc_attr($height).'"/>':'').'</p>');
    
    echo('<p><span style="display:inline-block;width:70px">Align</span> <select name="playerjs_com_align[input]"><option value="">left</option><option value="center" '.($align=="center"?"selected":"").'>center</option></select></p>');
    
    //<input type="checkbox" name="playerjs_com_customheight[checkbox]" value="1" '.($customheight==1?"":"checked").'/> custom
}

function playerjs_com_sanitize_callback( $options ){
	if (is_array($options) || is_object($options)){
		foreach( $options as $name => & $val ){
			if( $name == 'input' )
				$val = strip_tags( $val );

			if( $name == 'checkbox' )
				$val = intval( $val );
		}
	}
	return $options;
}