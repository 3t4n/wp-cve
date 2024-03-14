<?php
/*
* Plugin Name: Geo Content
* Description: Create geo targeted content based on visitor country, state/region, city, IP address and latitude longitude radius zone
* Version: 6.0
* Author: Geo Targetly
* Author URI: https://geotargetly.com
*/



//ADMIN MENU---------------------------------------------------------------------


add_action( 'admin_menu', 'geotargetly_wp_geocontent_add_admin_menu' );
add_action( 'admin_init', 'geotargetly_wp_geocontent_settings_init' );


function geotargetly_wp_geocontent_add_admin_menu(  ) { 
	add_menu_page( 'Geo Content - Geo Targetly', 'Geo Content', 'manage_options', 'geotargetly_wp_geocontent', 'geotargetly_wp_geocontent_options_page', 'dashicons-location');
}

function geotargetly_wp_geocontent_settings_init(  ) { 
	register_setting( 'geotargetly_wp_geocontent_pluginPage', 'geotargetly_wp_geocontent_settings' );
	add_settings_section(
		'geotargetly_wp_geocontent_pluginPage_section', 
		__( '', 'geotargetly_wp_geocontent' ), 
		'geotargetly_wp_geocontent_settings_section_callback', 
		'geotargetly_wp_geocontent_pluginPage'
	);
       add_settings_field( 
		'geotargetly_wp_geocontent_ids', 
		__( 'IDs (comma separated)', 'geotargetly_wp_geocontent' ), 
		'geotargetly_wp_geocontent_ids_render', 
		'geotargetly_wp_geocontent_pluginPage', 
		'geotargetly_wp_geocontent_pluginPage_section' 
	);
}


function geotargetly_wp_geocontent_ids_render(  ) { 
	$options = get_option( 'geotargetly_wp_geocontent_settings' );
	?>
	<input type='text' name='geotargetly_wp_geocontent_settings[geotargetly_wp_geocontent_ids]' value='<?php echo $options['geotargetly_wp_geocontent_ids']; ?>'>
	<?php
}


function geotargetly_wp_geocontent_settings_section_callback(  ) { 
	echo __( '', 'geotargetly_wp_geocontent' );
}



function geotargetly_wp_geocontent_options_page(  ) { 

?>
<form action='options.php' method='post'>
		
<div id="post-body" class="metabox-holder columns-2">
<div id="post-body-content">

<h2>Geo Content - By Geo Targetly</h2>


<div class="postbox" style="width:70%; padding:30px;">
<h2>Getting Started</h2>

<p>Geo Targetly's Geo Content service allows you to easily display content in your website based on visitor geolocation (country, state, city, IP address, latitude-longitude-radius).</p>

<p><strong>Note: </strong>This Wordpress plugin allows you to install Geo Targetly's Geo Content scripts into your Wordpress website and provides shortcodes to show/hide content based on geolocation.</p>

<p style="padding-top:20px;"><strong>Steps</strong></p>	
<p>1. Create a Geo Targetly account at <a href="https://geotargetly.com" target="_blank">Geotargetly.com</a> </p>
<p>2. <a href="https://dashboard.geotargetly.com/login" target="_blank">Log in</a> to your Geotargetly dasboard</p>
<p>3. Create a new <strong>Geo Content</strong> service</p>
<p>4. Follow the procedure to setup your geo content</p>
<p>5. Copy the Geo Content Wordpress ID provided and insert into settings below</p>
<p style="padding-top:20px;">Read more about Geo Content at our <a href="http://help.geotargetly.com/geo-content" target="_blank">docs</a></p>

</div>


<div class="postbox" style="width:70%; padding:30px;">
<h2>Settings</h2>
<?php
settings_fields( 'geotargetly_wp_geocontent_pluginPage' );
do_settings_sections( 'geotargetly_wp_geocontent_pluginPage' );
submit_button();
?>
</div>

</form>

</div>
</div>

<?php

}







//GEO CONTENT-----------------------------------------------------------



//ADD GEO POPUP WP HEAD



add_action('wp_footer', 'geotargetly_wp_geocontent', -1000);

function geotargetly_wp_geocontent() {
	
	$var = "some text";
	$scripts = <<<EOT
EOT;
	
	$geotargetly_geocontent_ids_database        = get_option('geotargetly_wp_geocontent_settings');
    $geotargetly_geocontent_ids_database_string = preg_replace('/\s+/', '', $geotargetly_geocontent_ids_database['geotargetly_wp_geocontent_ids']);
    $geotargetly_geocontent_ids_database_array  = explode(',', $geotargetly_geocontent_ids_database_string);
    $geotargetly_geocontent_ids_database_array  = array_filter($geotargetly_geocontent_ids_database_array);
	
	if (!empty($geotargetly_geocontent_ids_database_array)) {
        for ($i = 0; $i < count($geotargetly_geocontent_ids_database_array); ++$i) {
            
$scripts .= <<<EOT
<script>
(function(g,e,o,t,a,r,ge,tl,y){
t=g.getElementsByTagName(e)[0];y=g.createElement(e);
y.async=true;y.src='https://g1584674683.co/gc?refurl='+g.referrer+'&id=$geotargetly_geocontent_ids_database_array[$i]&winurl='+encodeURIComponent(window.location);
t.parentNode.insertBefore(y,t);
})(document,'script','head');
</script>


EOT;
            
        }
    }
	
	echo $scripts;
}





function geotargetly_wp_geocontent_shortcodes($atts = [], $contents = null)
{
    
 return "<span class='geotargetlygeocontent".$atts['id']."'>".$contents."</span>";
	

}
add_shortcode('geotargetlygeocontent', 'geotargetly_wp_geocontent_shortcodes');


function geotargetly_wp_geocontent_wrap_shortcodes($atts = [], $contents = null)
{
  if($atts['content'] !== "default"){  
  return "<span style='display:none;' class='geotargetlygeocontent".$atts['id']."_content_".$atts['content']."'>".$contents."</span>"; 
  }
  else{
	return "<span style='display:none;' class='geotargetlygeocontent".$atts['id']."_default'>".$contents."</span>";   
	  
  }	

}
add_shortcode('geotargetlygeocontentwrap', 'geotargetly_wp_geocontent_wrap_shortcodes');





?>