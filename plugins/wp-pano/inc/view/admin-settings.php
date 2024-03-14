<?php

function add_wppano_settings_page(){
	add_submenu_page( 'options-general.php', 'WP-Pano setup', 'WP-Pano', 'manage_options', 'wp-pano-setup-page', 'wppano_main_options_page' );
}
add_action('admin_menu', 'add_wppano_settings_page');

function wppano_main_options_page(){
	?>
	<script src="<?php echo WPPANO_URL.'/js/settings.js'?>"></script>	
	<div class="wrap settings-container">
		<h2><?php echo get_admin_page_title() ?></h2>
		<p>Use the <code><b>[wp-pano]</b></code> shortcode to insert your vtour into a page.</p>
		<p>Attributes by default: <code><b>width="100%" Height="500px"</b></code>, <code><b>html5="prefer"</b></code>, <code><b>passQueryParameters="false"</b></code>, <code><b>wmode=""</b></code>, <code><b>vars=""</b></code></p>
		<p>For example: <code><b>[wp-pano width="100%" Height="600px"]</b></code> or <code><b>[wp-pano vars="'language':'fr'"]</b></code></p>
		<p>Please, refer to the <a href="http://wp-pano.yuzhakov.org" target="_blank">official page</a> for support.</p>
		<form action="options.php" method="POST">
			<?php settings_fields( 'setup_group' ); ?>
			<?php do_settings_sections( 'path_setup_page' ); ?>
			<?php do_settings_sections( 'user_scripts_page' ); ?>
			<?php do_settings_sections( 'style_setup_page' ); ?>
			<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>"/></p>
			<?php //submit_button(); ?>
		</form>
	</div>
	<div class="donate">
		<h3>Help Support This Plugin!</h3>
		<p>Please donate to the development of WP-Pano:</p>
		<div>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
			<input type="hidden" name="cmd" value="_donations">
			<input type="hidden" name="business" value="tour3d@ya.ru">
			<input type="hidden" name="lc" value="GB">
			<input type="hidden" name="item_name" value="WP-Pano">
			<input type="hidden" name="currency_code" value="USD">
			<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG_global.gif:NonHosted">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG_global.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>
		</div>
	</div>
	<?php
}

add_action('admin_init', 'wppano_plugin_settings');
function wppano_plugin_settings(){ 
	// параметры: $option_group, $option_name, $sanitize_callback
	register_setting( 'setup_group', 'wppano_vtourpath' );
	register_setting( 'setup_group', 'wppano_vtourxml' );
	register_setting( 'setup_group', 'wppano_vtourjs' );
	register_setting( 'setup_group', 'wppano_vtourswf' );
	register_setting( 'setup_group', 'user_script_hotspotloaded' );
	register_setting( 'setup_group', 'user_script_before' );
	register_setting( 'setup_group', 'user_script_after' );
	register_setting( 'setup_group', 'wppano_target_container' );
	register_setting( 'setup_group', 'wppano_posttype' );
	
	// параметры: $id, $title, $callback, $page
	add_settings_section( 'main_setup', 'Vtour path and files', 'wppano_main_setup_callback', 'path_setup_page' ); 
	add_settings_section( 'user_scripts_setup', 'User scripts', '', 'user_scripts_page' ); 
	add_settings_section( 'style_setup', 'Style', '', 'style_setup_page' ); 

	// параметры: $id, $title, $callback, $page, $section, $args
	add_settings_field('wppano_vtourpath', 'path to vtour', 'wppano_fill_vtourpath', 'path_setup_page', 'main_setup' );
	add_settings_field('wppano_vtourxml', 'krpano xml file name', 'wppano_fill_vtourxml', 'path_setup_page', 'main_setup' );
	add_settings_field('wppano_vtourjs', 'krpano js file name', 'wppano_fill_vtourjs', 'path_setup_page', 'main_setup' );
	add_settings_field('wppano_vtourswf', 'krpano swf file name', 'wppano_fill_vtourswf', 'path_setup_page', 'main_setup' );
	add_settings_field('user_script_hotspotloaded', 'Call when hotspots has loaded', 'wppano_user_script_hotspotloaded', 'user_scripts_page', 'user_scripts_setup' );
	add_settings_field('user_script_before', 'Call before open a post', 'wppano_user_script_before', 'user_scripts_page', 'user_scripts_setup' );
	add_settings_field('user_scripts_after', 'Call after close a post', 'wppano_user_script_after', 'user_scripts_page', 'user_scripts_setup' );
	
	add_settings_field('wppano_target_container', 'target container', 'wppano_fill_target_container', 'style_setup_page', 'style_setup' );		
	add_settings_field('wppano_posttype', 'post Types', 'wppano_fill_posttype', 'style_setup_page', 'style_setup' );
}

function render_startxml(){
	$vtourpath = get_option('wppano_vtourpath');

}

function wppano_main_setup_callback(){
	$vtourpath = get_option('wppano_vtourpath');
	if($vtourpath[0] == '/') {
		$vtourpath = sanitize_text_field(substr($vtourpath, 1));
		update_option( 'wppano_vtourpath', $vtourpath );
	}
	$xml_url = "";
	if ( get_option('wppano_vtourxml') ) $xml_url = '/' . $vtourpath . '/' . get_option('wppano_vtourxml');
	$startxml = WPPANO_BASEDIR . '/inc/startxml.php';
	ob_start();
	if (file_exists ($startxml)) require($startxml);
	$xml_content = ob_get_clean();	
	$file = get_home_path() . $vtourpath . "/wp-pano-xml-start.xml";
	//if( !file_put_contents($file, $xml_content)) echo "<p>Cannot create file. Please check path and permissions.</p>";
}

function wppano_fill_vtourpath(){
	$val = get_option('wppano_vtourpath');
	?>
	<input type="text" name="wppano_vtourpath" value="<?php echo esc_attr( $val ); ?>" />
	<?php if($val == '') { ?>
		<p class="description">for example: <b>vtour</b></p>
	<?php } else { 
		if(!file_exists( ABSPATH . '/' . $val )) echo "<span class='description' style='color: red;'>Folder not exists</span>"; ?>
		<p class="description">Vtour url: <b><?php echo get_site_url() . '/' . esc_attr( $val ); ?></b></p>
	<?php }
}

function wppano_fill_vtourxml(){
	$val = get_option('wppano_vtourxml');
	?>
	<input type="text" name="wppano_vtourxml" value="<?php echo esc_attr( $val ); ?>" />
	<?php if( $val == '' ) { ?>
		<p class="description">for example: <b>tour.xml</b></p>
	<?php } else { 
		if( get_option('wppano_vtourpath') != '' ) $vtourpath = get_option('wppano_vtourpath'); else $vtourpath = '/';
		if( !file_exists( ABSPATH . '/' . $vtourpath . '/' . $val ) ) 
			echo "<span class='description' style='color: red;'>File not exists</span>";
		else {		
			$xml = simplexml_load_file( ABSPATH . '/' . $vtourpath . '/' . $val ); 
			$exist = false;
			foreach ($xml->include as $xmp_inc)
				if( strpos($xmp_inc['url'], 'wp-pano.xml') ) $exist = true;
			if( !$exist ) {
				//$xml->addAttribute("vtour_name", 'example');
				$child = $xml->addChild('include');
				$child->addAttribute("url", '%$WPPANOPATH%/xml/wp-pano.xml');
				if($xml->asXML(ABSPATH . '/' . $vtourpath . '/' . $val)) 
					echo "<p class='description' style='color: green;'>the node &lt;include url='%\$WPPANOPATH%/xml/wp-pano.xml'/&gt; was not found and was added automatically</p>";
				else
					echo "<p class='description' style='color: red;'>the node &lt;include url='%\$WPPANOPATH%/xml/wp-pano.xml'/&gt; was not found.</p>";
			}
		}?>
		<p class="description">XML file url: <b><?php echo get_site_url() . '/' . $vtourpath . '/' . esc_attr( $val ); ?></b></p>
	<?php }
}

function wppano_fill_vtourjs(){
	$val = get_option('wppano_vtourjs');
	?>
	<input type="text" name="wppano_vtourjs" value="<?php echo esc_attr( $val ); ?>" />
	<?php if($val == '') { ?>
		<p class="description">for example: <b>tour.js</b></p>
	<?php } else { 
		if( get_option('wppano_vtourpath') != '' ) $vtourpath = get_option('wppano_vtourpath'); else $vtourpath = '/';
		if( !file_exists( ABSPATH . '/' . $vtourpath . '/' . $val ) ) echo "<span class='description' style='color: red;'>File not exists</span>"; ?>
		<p class="description">JS file url: <b><?php echo get_site_url() . '/' . $vtourpath . '/' . esc_attr( $val ); ?></b></p>
	<?php }
}

function wppano_fill_vtourswf(){
	$val = get_option('wppano_vtourswf');
	?>
	<input type="text" name="wppano_vtourswf" value="<?php echo esc_attr( $val ); ?>" />
	<?php if($val == '') { ?>
		<p class="description">for example: <b>tour.swf</b></p>
	<?php } else { 
		if( get_option('wppano_vtourpath') != '' ) $vtourpath = get_option('wppano_vtourpath'); else $vtourpath = '/';
		if( !file_exists( ABSPATH . '/' . $vtourpath . '/' . $val ) ) echo "<span class='description' style='color: red;'>File not exists</span>"; ?>
		<p class="description">SWF file url: <b><?php echo get_site_url() . '/' . $vtourpath . '/' . esc_attr( $val ); ?></b></p>
	<?php }
}

function wppano_user_script_hotspotloaded(){
	$val = get_option('user_script_hotspotloaded');
	?>
	<textarea name="user_script_hotspotloaded" cols="48" rows="5"><?php echo esc_attr( $val ); ?></textarea>
	<p class="description">For example: <b>console.log(response);</b> or <b>krpano.call('autorotate.pause()');</b></p>
<?php }

function wppano_user_script_before(){
	$val = get_option('user_script_before');
	?>
	<textarea name="user_script_before" cols="48" rows="5"><?php echo esc_attr( $val ); ?></textarea>
	<p class="description">For example: <b>alert('Before');</b> or <b>krpano.call('autorotate.pause()');</b></p>
<?php }

function wppano_user_script_after(){
	$val = get_option('user_script_after');
	?>
	<textarea name="user_script_after" cols="48" rows="5"><?php echo esc_attr( $val ); ?></textarea>
	<p class="description">For example: <b>alert('After');</b> or <b>krpano.call('autorotate.resume()');</b></p>
<?php }

function wppano_fill_target_container(){
	$val = get_option('wppano_target_container');
	?>
	<input type="text" name="wppano_target_container" size="48" value="<?php echo esc_attr( $val ); ?>" />
	<p class="description">For example: <b><i>body</i></b> or <b><i>#sidebar</i></b>. Keep empty for use default container.</p>
<?php }

function wppano_get_filenames($e){ 
	return pathinfo($e, PATHINFO_FILENAME); 
	}

function wppano_fill_posttype(){
	$val = get_option('wppano_posttype');
	$args = array( 'public' => true);
	$post_types = get_post_types($args, 'names', 'and'); 
	if ( !file_exists( WPPANO_BASEDIR . '/xml/wp-pano.xml' ) ) { 
		echo 'error: wp-pano.xml is lost'; 
		return false; 
	}
	if( !isset($val['window']) ) { 
		$val = array(
			'window' => array()
		);		
		foreach ( $post_types as $post_type ) $val['window'][$post_type] = 'standard';
	}
	?>
	<table>	
		<tr>
			<td style="padding: 5px;">
				<b>Post Type</b>
			</td>
			<td style="padding: 5px;">
				<b>Hotspot Style</b>
			</td>
			<td style="padding: 5px;">
				<b>Window Style</b>
			</td>
		</tr>	
	<?php 
	if( file_exists(addslashes(get_template_directory() . '/wp-pano/xml/wp-pano.xml')) )
		$xml = simplexml_load_file( addslashes(get_template_directory() . '/wp-pano/xml/wp-pano.xml') ); 
	else
		$xml = simplexml_load_file( WPPANO_BASEDIR . '/xml/wp-pano.xml' ); 
	foreach ( $post_types as $post_type ) {
		$postTypeObject = get_post_type_object( $post_type );?> 
		<tr>
			<td style="padding: 5px;">
				<label style="margin-right: 5pt;"><input type="checkbox" class="input-posttype" name="wppano_posttype[type][<?php echo $post_type;?>]" value="<?php echo $post_type;?>"<?php if( isset($val['type'][$post_type]) ) checked( true );?>/>
				<?php echo __($postTypeObject->labels->menu_name);?></label>
			</td>
			<td style="padding: 5px;">
				<select class="posttype_select posttype_<?php echo $post_type; ?>" disabled name="wppano_posttype[hs_style][<?php echo $post_type;?>]" val="<?php if( isset($val['hs_style'][$post_type]) ) echo $val['hs_style'][$post_type]; ?>">
				<?php 
					foreach ($xml->style as $style)
						echo '<option value="' . $style['name'] . '">' . $style['name'] . '</option>';
				?>
				</select>
			</td>
			<td style="padding: 5px;">
				<select class="posttype_select posttype_<?php echo $post_type; ?>" disabled name="wppano_posttype[window][<?php echo $post_type;?>]" val="<?php if( isset($val['window'][$post_type]) ) echo $val['window'][$post_type]; ?>">
				<?php 
				$plugin_templates = glob( WPPANO_BASEDIR . '/templates/*.php' ); 
				$theme_templates = glob( get_template_directory() . '/wp-pano/templates/*.php' );
				$files = array_merge($plugin_templates, $theme_templates);
				$files = array_unique(array_map("wppano_get_filenames", $files));
				foreach( $files as $file ) { ?>
					<option value="<?php echo $file; ?>"><?php echo $file; ?></option>
				<?php } ?>		
				</select>
			</td>			
		</tr>
	<?php } ?>
	</table>
	<p>Refer to the <a href="http://wp-pano.com/styles-and-templates/" target="_blank">Styles and templates</a> docs for support to setup.</p>
<?php }

function wppano_fill_hs_styles(){
	$val = get_option('wppano_hs_styles');
} ?>