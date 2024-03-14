<?php

if ( ! defined( 'ABSPATH' ) ) {
        exit;
}

error_reporting(0);
/*
Plugin Name: Shoutcast Icecast HTML5 Radio Player 
Plugin URI: https://www.svnlabs.com/store/product/html5-radio-stream-player/
Description: HTML5 MP3 Radio FM MP3 Stream Player can grab "Now Playing Song Information" on player as StreamTitle for Shoutcast and Icecast Streams. 
Version: 2.1.6
Author: Sandeep Verma
Author URI: https://www.svnlabs.com/store/product/html5-radio-stream-player/
*/ 


 
// Some Defaults


$radiolink		= 'http://server:port/';
$radiotype					= 'shoutcast';
$bcolor					= '000000';

$image					= '';
$facebook					= 'http://www.facebook.com/radioforgecom';
$twitter					= 'http://twitter.com/radioforgecom';


$titlez					= 'Stream Title';
$artist					= 'Stream Artist';


// Put our defaults in the "wp-options" table



add_option("shoutcast-icecast-html5-player-radiolink", $radiolink);
add_option("shoutcast-icecast-html5-player-radiotype", $radiotype);

add_option("shoutcast-icecast-html5-player-bcolor", $bcolor);

add_option("shoutcast-icecast-html5-player-image", $image);
add_option("shoutcast-icecast-html5-player-facebook", $facebook);
add_option("shoutcast-icecast-html5-player-twitter", $twitter); 



add_option("shoutcast-icecast-html5-player-title", $titlez);
add_option("shoutcast-icecast-html5-player-artist", $artist); 


//grab options from the database





//AWS access info



// Start the plugin
if ( ! class_exists( 'Shoutcast_Icecast_HTML5_Player' ) ) {
	class Shoutcast_Icecast_HTML5_Player {
// prep options page insertion
		function add_config_page() {
			if ( function_exists('add_submenu_page') ) {
				add_options_page('Shoutcast Icecast Player Options', 'Shoutcast Icecast HTML5 Radio Player Options', 10, basename(__FILE__), array('Shoutcast_Icecast_HTML5_Player','config_page'));
			}	
	}
// Options/Settings page in WP-Admin
		function config_page() {
			if ( isset($_POST['submit']) ) {
				$nonce = $_REQUEST['_wpnonce'];
				if (! wp_verify_nonce($nonce, 'shoutcast-icecast-html5-player-updatesettings') ) die('Security check failed'); 
				if (!current_user_can('manage_options')) die(__('You cannot edit the search-by-category options.'));
				check_admin_referer('shoutcast-icecast-html5-player-updatesettings');	
			// Get our new option values
			
			$radiolink	= $_POST['radiolink'];
			$radiotype	= $_POST['radiotype'];
			
			$bcolor	= $_POST['bcolor'];
			
			$image	= $_POST['image'];
			$facebook	= $_POST['facebook'];
			$twitter	= $_POST['twitter'];
			
			
			$titlez	= $_POST['title'];
			$artist	= $_POST['artist'];
			
		    // Update the DB with the new option values
			
			update_option("shoutcast-icecast-html5-player-radiolink", ($radiolink));
			update_option("shoutcast-icecast-html5-player-radiotype", ($radiotype));
			
			update_option("shoutcast-icecast-html5-player-bcolor", ($bcolor));
			
			update_option("shoutcast-icecast-html5-player-image", ($image));
			update_option("shoutcast-icecast-html5-player-facebook", ($facebook));
			update_option("shoutcast-icecast-html5-player-twitter", ($twitter));
			
			
			update_option("shoutcast-icecast-html5-player-title", ($titlez));
			update_option("shoutcast-icecast-html5-player-artist", ($artist));
			
			}
			
			
			$radiolink	= get_option("shoutcast-icecast-html5-player-radiolink");
			$radiotype	= get_option("shoutcast-icecast-html5-player-radiotype");	
			
			$bcolor	= get_option("shoutcast-icecast-html5-player-bcolor");	
			
			$image	= get_option("shoutcast-icecast-html5-player-image");	
			$facebook	= get_option("shoutcast-icecast-html5-player-facebook");	
			$twitter	= get_option("shoutcast-icecast-html5-player-twitter");	
			
			
			$titlez	= get_option("shoutcast-icecast-html5-player-title");	
			$artist	= get_option("shoutcast-icecast-html5-player-artist");	
			
?>

<div class="wrap">
  <h2>Shoutcast Icecast HTML5 Radio Player Options</h2>
  
  
  <?php
  
  // Check for CURL
//if (!extension_loaded('curl') && !@dl(PHP_SHLIB_SUFFIX == 'so' ? 'curl.so' : 'php_curl.dll')) die("\nERROR: CURL extension not loaded\n\n");
  
  
  ?>


  <div style="color: #ff0000">Note: Please make sure Protocols of Radio Live Stream and Website must be same. If your website running in HTTPS (Secure) and your radio stream is HTTP (Unsecure) then browsers will not play that stream and throw Mixed Content Error in browser console log. <br> <a href="https://www.radioforge.com/https-secure-radio-streams/" target="_blank">[Subscribe Now and get your HTTPS Secure Radio Stream URL]</a> </div>
  
  
  <form action="" method="post" id="shoutcast-icecast-html5-player-config">
    <table class="form-table">
      <?php if (function_exists('wp_nonce_field')) { wp_nonce_field('shoutcast-icecast-html5-player-updatesettings'); } ?>
       
      
      
            
      <tr>
        <th scope="row" valign="top"><label for="radiolink">Radio Stream Link:</label></th>
        <td><input type="text" name="radiolink" id="radiolink" class="regular-text" value="<?php echo $radiolink; ?>"/><br />
        
        <em>Note: Make sure you have valid MP3 Radio Stream, Don't include listen.pls in URL <br />

Shoutcast V1 (http://shoutcast-server-ip:port/) <br />
Shoutcast V2 (http://shoutcast-server-ip:port/streamname) <br />
Icecast (http://icecast-server-ip:port/streamname)<br />
Podcast MP3 (http://www.domain.com/directory/filename.mp3)</em>

</td>
      </tr>
      
      <tr>
        <th scope="row" valign="top"><label for="radiotype">Radio Type:</label></th>
        
        <td>
        <select name="radiotype" id="radiotype">
        <option value="shoutcast1" <?php if($radiotype=="shoutcast1") echo ' selected="selected"'; ?>>Shoutcast1</option>
        <option value="shoutcast2" <?php if($radiotype=="shoutcast2") echo ' selected="selected"'; ?>>Shoutcast2</option>
        <option value="icecast" <?php if($radiotype=="icecast") echo ' selected="selected"'; ?>>Icecast</option>
        <option value="podcast" <?php if($radiotype=="podcast") echo ' selected="selected"'; ?>>MP3 Podcast</option>
        </select>
        </td>
      </tr>
      
      
     
      
      
      <tr>
        <th scope="row" valign="top"><label for="player">Player BG Color:</label></th>
        
        <td>
        #<input class="color" id="bcolor" name="bcolor" type="text" value="<?php echo $bcolor; ?>" />
        </td>
      </tr>
      
      
       <tr>
        <th scope="row" valign="top"><label for="player">Player Artwork:</label></th>
        
        <td>
        <input name="image" id="image" type="text" class="regular-text" value="<?php echo $image; ?>" />
        </td>
      </tr>
      
      

      
      
      
      <tr>
        <th scope="row" valign="top"><label for="player">Radio Title:</label></th>
        
        <td>
        <input name="title" id="title" type="text" class="regular-text" value="<?php echo $titlez; ?>" />
        </td>
      </tr>
      
      <tr>
        <th scope="row" valign="top"><label for="player">Radio Artist:</label></th>
        
        <td>
        <input name="artist" id="artist" type="text" class="regular-text" value="<?php echo $artist; ?>" />
        </td>
      </tr>
      
      
      
       <tr>
        <th scope="row" valign="top"><label for="player">Facebook Link:</label></th>
        
        <td>
        <input name="facebook" id="facebook" type="text" class="regular-text" value="<?php echo $facebook; ?>" />
        </td>
      </tr>
      
       <tr>
        <th scope="row" valign="top"><label for="player">Twitter Link:</label></th>
        
        <td>
        <input name="twitter" id="twitter" type="text" class="regular-text" value="<?php echo $twitter; ?>" />
        </td>
      </tr>
      
      
      
      
      

    </table>
    <br/>
    <span class="submit" style="border: 0;">
    <input type="button" name="submit" onclick="generatePlayerCode();" value="Generate Player Code" />
    </span>
  </form>
  
  <script type="text/javascript">
  
  function generatePlayerCode()
  {
  
    //alert("sv");
	
	var radiolink = document.getElementById("radiolink").value;
	var radiotype = document.getElementById("radiotype").value;
	var bcolor = document.getElementById("bcolor").value;
	var image = document.getElementById("image").value;
	var title = document.getElementById("title").value;
	var artist = document.getElementById("artist").value;
	var facebook = document.getElementById("facebook").value;
	var twitter = document.getElementById("twitter").value;
	
	radiolink = radiolink.trim();
	image = image.trim();
	facebook = facebook.trim();
	twitter = twitter.trim();
	
	
	if(radiotype=="shoutcast1") radiotype = "shoutcast";
	if(radiotype=="shoutcast2") radiotype = "shoutcast";  
	
	
	document.getElementById("shortcode").innerHTML = '[html5radio radiolink="'+radiolink+'" radiotype="'+radiotype+'" bcolor="'+bcolor+'" image="'+image+'" title="'+title+'" artist="'+artist+'" facebook="'+facebook+'" twitter="'+twitter+'"]';
	
	
	document.getElementById("iframe").innerHTML = '<iframe src="//player.radioforge.com/v2/'+radiotype+'.html?radiolink='+encodeURIComponent(radiolink)+'&radiotype='+radiotype+'&bcolor='+bcolor+'&image='+image+'&facebook='+facebook+'&twitter='+twitter+'&title='+title+'&artist='+artist+'" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" width="367" height="227"></iframe>';


    document.getElementById("embed").innerHTML = '<textarea cols="60" rows="10" onFocus="this.select();" style="border:1px dotted #343434" ><iframe src="//player.radioforge.com/v2/'+radiotype+'.html?radiolink='+encodeURIComponent(radiolink)+'&radiotype='+radiotype+'&bcolor='+bcolor+'&image='+image+'&facebook='+facebook+'&twitter='+twitter+'&title='+title+'&artist='+artist+'" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" width="367" height="227"></iframe></textarea>';	
	
	
	document.getElementById("embed").select();
	

  
  }
  
  </script>
  
  
 <?php shoutcast_icecast_html5_player(); ?>
<br />
<?php /*?><h3>PHP Code for template php files</h3>
<code>&lt;?php shoutcast_icecast_html5_player(); ?&gt;</code><?php */?>

<h3>Shortcode for Page or Post</h3>

<code>
<span id="shortcode">
[html5radio radiolink="<?php echo $radiolink; ?>" radiotype="<?php echo $radiotype; ?>" title="<?php echo $titlez; ?>" artist="<?php echo $artist; ?>"]
</span>
</code>

<br /><br />


<?php //echo $scode; ?>

<?php

$pluginurl	=	plugin_dir_url( __FILE__ );

if($radiotype=="icecast")
  $radiotype="icecast";
else if($radiotype=="podcast")
  $radiotype="icecast";
else
  $radiotype="shoutcast";   

$iframe = '<iframe src="//player.radioforge.com/v2/'.$radiotype.'.html?radiolink='.urlencode($radiolink).'&radiotype='.$radiotype.'&bcolor='.$bcolor.'&image='.$image.'&facebook='.$facebook.'&twitter='.$twitter.'&title='.$titlez.'&artist='.$artist.'" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" width="367" height="227"></iframe>';


?>
<br />

<span id="iframe">
<?php //echo $iframe; ?>
</span>

<hr />
<br />


<h3>Embed Anywhere &nbsp;&nbsp;  [<a href="https://www.radioforge.com/" target="_blank">Get More Radio Players from RadioForge.com</a>]</h3>

<span id="embed">
<?php /*?><textarea cols="60" rows="10" onFocus="this.select();" style="border:1px dotted #343434" ><?php echo $iframe; ?></textarea><?php */?>
</span>

<!-- Paypal etc.  --><br />

<br />



<strong><a href="https://www.svnlabs.com/store/product/html5-radio-stream-player/" target="_blank">HTML5 MP3 Radio FM MP3 Stream Player</a></strong>

<br />

<br />

<a href="https://twitter.com/HTML5MP3Player" class="twitter-follow-button" data-show-count="false">Follow @HTML5MP3Player</a><script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>


<br />
<br />


<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=181968385196620";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="fb-like" data-href="https://www.facebook.com/Html5Mp3Player" data-send="true" data-width="450" data-show-faces="true"></div>  

 </div>
<?php		}
	}
} 
  
// Base function 
function shoutcast_icecast_html5_player($atts = null, $content = null) {

// Plugin Url 


$radiolink	= get_option("shoutcast-icecast-html5-player-radiolink");	
$radiotype	= get_option("shoutcast-icecast-html5-player-radiotype");

$bcolor	= get_option("shoutcast-icecast-html5-player-bcolor");

$image	= get_option("shoutcast-icecast-html5-player-image");
$facebook	= get_option("shoutcast-icecast-html5-player-facebook");
$twitter	= get_option("shoutcast-icecast-html5-player-twitter");


$titlez	= get_option("shoutcast-icecast-html5-player-title");
$artist	= get_option("shoutcast-icecast-html5-player-artist");



$pluginurl	=	plugin_dir_url( __FILE__ );


extract( shortcode_atts( array(
		'radiolink' => $radiolink,
		'radiotype' => $radiotype,
		'bcolor' => $bcolor,
		'image' => $image,
		'facebook' => $facebook,
		'twitter' => $twitter,
		'title' => $titlez,
		'artist' => $artist,
	), $atts ) );
 
 
$titlez = $title;

//echo '<br />';

if($radiotype=="icecast")
  $radiotype="icecast";
else if($radiotype=="podcast")
  $radiotype="icecast";  
else
  $radiotype="shoutcast";


/*echo '<iframe src="'.$pluginurl.'html5/html5'.$radiotype.'.php?radiotype='.$radiotype.'&radiolink='.$radiolink.'&rand='.rand().'&bcolor='.$bcolor.'&image='.$image.'&facebook='.$facebook.'&twitter='.$twitter.'&title='.$titlez.'&artist='.$artist.'" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" width="367" height="227"></iframe>';*/

$echo = '<iframe src="//player.radioforge.com/v2/'.$radiotype.'.html?radiotype='.$radiotype.'&radiolink='.urlencode($radiolink).'&rand='.rand().'&bcolor='.$bcolor.'&image='.$image.'&facebook='.$facebook.'&twitter='.$twitter.'&title='.$titlez.'&artist='.$artist.'" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" width="367" height="227"></iframe>';

return $echo;


}

// insert into admin panel
add_action('admin_menu', array('Shoutcast_Icecast_HTML5_Player','add_config_page'));
add_shortcode( 'html5radio', 'shoutcast_icecast_html5_player' );


function  shoutcast_icecast_html5_player_scripts_method() {
	
    //wp_register_script( 'shoutcast_icecast_html5_player_scripts1', plugins_url( '/html5/jscolor.js', __FILE__ ) );
    //wp_enqueue_script( 'shoutcast_icecast_html5_player_scripts1' );
	
/*	wp_register_script( 'custom-script1', plugins_url( '/html5lyrics/js/jscolor.js', __FILE__ ) );
    wp_enqueue_script( 'custom-script1' );
	
	wp_register_script( 'custom-script2', plugins_url( '/html5lyrics/js/core.js', __FILE__ ) );
    wp_enqueue_script( 'custom-script2' );
*/	
	
	
}    
 
add_action('wp_enqueue_scripts', 'shoutcast_icecast_html5_player_scripts_method');


$plugin = plugin_basename(__FILE__);

add_filter("plugin_action_links_{$plugin}", 'upgrade_to_pro_html5_radio_player');


function upgrade_to_pro_html5_radio_player($links) { 

	if (function_exists('is_plugin_active') && !is_plugin_active('shoutcast-icecast-html5-radio-playlist/list.php')) {

		$links[] = '<a href="https://www.svnlabs.com/store/product/html5-radio-stream-player/" target="_blank">' . __("Go Pro", "metaslider") . '</a>'; 

	}

	return $links; 

}


?>