<?php
/*
Plugin Name: Twitch TV Embed Suite
Plugin URI: http://www.plumeriawebdesign.com/twitch-tv-embed-suite/
Description: Add Twitch TV Stream to your Site
Author: Plumeria Web Design
Version: 2.1.0
Author URI: http://www.plumeriawebdesign.com
*/

function plumwd_twitch_menu(){
  $file = dirname(__FILE__) . '/index.php';
  $plugin_dir = plugin_dir_url($file);
  
  add_menu_page('Twitch Embed', 'Twitch Embed', 'manage_options', 'twitch-options', 'twitch_settings', $plugin_dir.'images/tv.png');
  add_submenu_page('twitch-options','Help', 'Help', 'manage_options', 'twitch-help', 'plumwd_twitch_help');
}
add_action('admin_menu', 'plumwd_twitch_menu');

function plumwd_add_default_settings() {
	$file = dirname(__FILE__) . '/index.php';
    $plugin_dir = plugin_dir_url($file);
	
	add_option('pte_streamwidth', '620');
	add_option('pte_streamheight', '378');
	add_option('pte_chatwidth', '500');
	add_option('pte_chatheight', '400');
}
register_activation_hook( __FILE__, 'plumwd_add_default_settings' );

function plumwd_remove_default_settings() {
	delete_option('pte_streamwidth');
	delete_option('pte_streamheight');
	delete_option('pte_chatwidth');
	delete_option('pte_chatheight');
}
register_deactivation_hook( __FILE__, 'plumwd_remove_default_settings' );


function twitch_settings() {
  $plugin_url = plugins_url();

  if(isset($_POST['formset'])) {
    $formset = $_POST['formset'];
  } else {
	$formset = "";  
  }

  if ($formset == "1") {  //our form has been submitted let's save the values
	update_option('pte_streamwidth', $_POST['streamwidth']);
	update_option('pte_streamheight', $_POST['streamheight']);
	update_option('pte_chatwidth', $_POST['chatwidth']);
	update_option('pte_chatheight', $_POST['chatheight']);
?>
<div class="updated">
  <p><strong><?php _e('Options saved.', 'mt_trans_domain' ); ?></strong></p>
</div>

<?php	
  }
  $autoplay = get_option('pte_autoplay');
  $streamwidth = get_option('pte_streamwidth');
  $streamheight = get_option('pte_streamheight');
  $chatwidth = get_option('pte_chatwidth');
  $chatheight = get_option('pte_chatheight');
?>
<div id="wrap">
<h1 id="twitchh1">Twitch TV Embed Settings</h1>
<div class="twitch-welcome">
<p><?php _e('This is where you can configure Twitch TV Embed settings.  By filling out the information below you can then use our shortcodes to insert your twitch stream and chat into any post, page, or widget on your site.','twitchembed' ) ?></p>
<p><?php _e('To test your stream/chat and to make sure they function properly, you can preview them below. Note: height and width settings are not reflected in the preview.','twitchembed' ) ?></p>
<h2 style="color: rgba(241,26,30,1.00);">NOTE: YOU MUST NOW SET THE CHANNEL NAME IN THE SHORTCODE</h2>
<p><?php _e('Visit <a href="http://www.plumeriawebdesign.com/twitch-tv-embed-suite/" target="_blank"> Twitch TV Embed Suite Help</a> for information and usage.');?></p>
</div>
<div style="width:45%;float:right;">
  <div class="metabox-holder postbox" style="padding-top:0;margin:10px;cursor:auto;width:30%;float:left;min-width:320px">
    <h3 class="hndle" style="cursor: auto;"><span><?php  _e( 'Thank you for using Twitch Embed Suite', 'twitchembed' ); ?></span></h3>
    <div class="inside twitchembed">
      <img src="<?php echo $plugin_url;?>/twitch-tv-embed-suite/images/preview.jpg" alt="Twitch Preview" />
  	  <?php _e( 'Please support Plumeria Web Design so we can continue making rocking plugins for you. If you enjoy this plugin, please consider offering a small donation. We also look forward
	  to your comments and suggestions so that we may further improve our plugins to better serve you.', 'twitchembed' ); ?>
      <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="SLYFNBZU8V87W">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
    </div>
  </div>
</div>
<form method="post" enctype="multipart/form-data" name="twitchform" id="twitchform">
<p><label for="streamwidth" class="longlabel">Default Stream Width:</label><input type="text" name="streamwidth" id="streamwidth" value="<?php echo $streamwidth;?>" class="shortfield"/></p>
<p><label for="streamheight" class="longlabel">Default Stream Height:</label><input type="text" name="streamheight" id="streamheight" value="<?php echo $streamheight;?>" class="shortfield"/></p>

<p><label for="chatwidth" class="longlabel">Chat Width:</label><input type="text" name="chatwidth" id="chatwidth" value="<?php echo $chatwidth;?>" class="shortfield"/></p>
<p><label for="chatheight" class="longlabel">Chat Height:</label><input type="text" name="chatheight" id="chatheight" value="<?php echo $chatheight;?>" class="shortfield"/></p>

<input type="hidden" id="formset" name="formset" value="1"/>
<input type="submit" style="width:123px; height:22px; height:33px;" name="submit" value="Save Settings" class="advadminopt_butt2 button-primary">
</form>
<?php	
echo "</div>\n <!-- wrap -->";
}

function plumwd_twitch_shortcodes() {
	add_shortcode( 'plumwd_twitch_stream', 'display_plumwd_twitch_stream');
	add_shortcode( 'plumwd_twitch_chat', 'display_plumwd_twitch_chat');
	add_shortcode( 'plumwd_twitch_streamlist', 'display_plumwd_twitch_streamlist');
}
add_action('init', 'plumwd_twitch_shortcodes');

function display_plumwd_twitch_streamlist($atts) {
  extract(shortcode_atts(array('videonum' => '', 'channel' => '', 'display' => ''), $atts));
  
  if ($videonum == "") {
	$videonum = 5;  
  }
  

  $videourl = "https://api.twitch.tv/kraken/channels/$channel/videos?limit=$videonum";
  $videos = file_get_contents($videourl);	
  $obj = json_decode($videos, true);
  $streams = $obj['videos'];

  $row = array(); 
  
 foreach($streams as $key => $val) {
   $row[$key]['title'] = $val['title'];
   $row[$key]['url'] = $val['url'];
   $row[$key]['recorded_at'] = $val['recorded_at'];
   $row[$key]['thumbnail'] = $val['preview'];
 }
  
  if ($display == "") {
	$display = "vertical";  
  }
  
  if ($display == "horizontal") {
	$width = (100/$videonum)-1;  
	$liwidth = "style=\"width: ".$width."%;\" ";
  }

  $streamlist .= "<ul id=\"twitch_streamlist\" class=\"".$display."\">\n";
  for ($i = 0; $i < $videonum; $i++) {
   $streamlist .= "<li ".$liwidth.">";
   $streamlist .= "<a href=\"".$row[$i]['url']."\">\n";
   $streamlist .= "<img src=\"".$row[$i]['thumbnail']."\" alt=\"".$row[$i]['title']."\" title=\"".$row[$i]['title']."\"/>";
   $streamlist .= "</a>";
   $streamlist .= "</li>\n";
  }
  $streamlist .= "</ul>\n";
  
  return $streamlist;
}

function plumwd_twitch_help () {
  include('includes/help.php');
}


function display_plumwd_twitch_stream($atts) {
  extract(shortcode_atts(array('channel' => '', 'height' => '378', 'width' => '620'), $atts));
  
  $display_stream = "";
  $file = dirname(__FILE__) . '/index.php';
  $plugin_dir = plugin_dir_url($file);
  
  if ($width == "") {
    $streamwidth = get_option('pte_streamwidth');
  } else {
	$streamwidth = $width;
  }
  
  if ($height == "") {
    $streamheight = get_option('pte_streamheight');
  } else {
	$streamheight = $height;  
  }
  
  $autoplay = get_option('pte_autoplay');
  $startvolume = get_option('pte_startvolume');
  $allowfullscreen = get_option('pte_allowfullscreen');
  $allowscriptaccess = get_option('pte_allowscriptaccess');
  $bgcolor = get_option('pte_bgcolor');
  $wmode = get_option('pte_wmode');
  $showchat = get_option('pte_showchat');
?>
<?php
  $display_stream = '<iframe src="http://www.twitch.tv/'.$channel.'/embed" frameborder="0" scrolling="no" height="'.$height.'" width="'.$width.'"></iframe>';
?>
<?php return $display_stream;
}

function display_plumwd_twitch_chat($atts) {
  extract(shortcode_atts(array('channel' => '', 'height' => '', 'width' => ''), $atts));

  $display_chat = "";
  
  if ($width == "") {
    $chatwidth = get_option('pte_chatwidth');
  } else {
	$chatwidth = $width;
  }
  
  if ($height == "") {
    $chatheight = get_option('pte_chatheight');
  } else {
	$chatheight = $height;  
  }


  $display_chat = " <div id=\"chat\">\n";
  $display_chat .= "   <iframe frameborder=\"0\" scrolling=\"no\" class=\"chat_embed\" src=\"http://twitch.tv/chat/embed?channel=".$channel."&amp;popout_chat=true\" height=\"".$chatheight."\" width=\"".$chatwidth."\"></iframe>\n";
  $display_chat .= " </div>\n";
  
  return $display_chat;
}

include('widget.php');

//let's make the button to add the shortcodeyou 
function register_button_sc_plumwd_twitch($buttons) {
array_push($buttons, "plumwd_twitch_stream", "plumwd_twitch_chat");
return $buttons;  
}

/*function add_plugin_sc_plumwd_twitch($plugin_array) {
$plugin_url = plugins_url();
$script_url = $plugin_url.'/twitch-tv-embed-suite/scripts/shortcode.js';
$plugin_array['plumwd_twitch_stream'] = $script_url; 
return $plugin_array;
}

add_action('admin_enqueue_scripts', 'add_plugin_sc_plumwd_twitch');*/


function plumwd_twitch_enqueue_scripts() {
  $file = dirname(__FILE__) . '/index.php';
  $plugin_dir = plugin_dir_url($file);

  
  wp_register_style('jquery-ui-theme-latest', 'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.min.css', '', '', 'screen');
  wp_enqueue_style('jquery-ui-theme-latest');
  
  wp_register_style('plumwd-twitch-embed', $plugin_dir.'css/admin-style.css', '', '', 'screen');
  wp_enqueue_style('plumwd-twitch-embed');
  
  wp_enqueue_style( 'wp-color-picker' );
  wp_enqueue_script( 'plumwd-twitch-embed-scripts', $plugin_dir.'scripts/scripts.js', array( 'wp-color-picker' ), false, true );
  
}
add_action('admin_enqueue_scripts', 'plumwd_twitch_enqueue_scripts');

//let's add the shortcode buttons
function plumwd_twitch_add_sc_button() {
    global $typenow;
    // check user permissions
    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
   	return;
    }
    // verify the post type
    if( ! in_array( $typenow, array( 'post', 'page' ) ) )
        return;
	// check if WYSIWYG is enabled
	if ( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "plumwd_twitch_add_tinymce_plugin");
		add_filter('mce_buttons', 'plumwd_twitch_register_my_sc_button');
	}
}
add_action('admin_head', 'plumwd_twitch_add_sc_button');

function plumwd_twitch_add_tinymce_plugin($plugin_array) {
    $file = dirname(__FILE__) . '/index.php';
    $plugin_dir = plugin_dir_url($file);

   	$plugin_array['plumwd_twitch_stream'] = $plugin_dir.'scripts/shortcode.js';
   	return $plugin_array;
}

function plumwd_twitch_register_my_sc_button($buttons) {
   array_push($buttons, "plumwd_twitch_stream", "plumwd_twitch_chat");
   return $buttons;
}


function plumwd_twitch_admin_footer_text($my_footer_text) {
  $plugin_url = plugins_url();
  $script_url = $plugin_url.'/twitch-tv-embed-suite/scripts/shortcode.js';
  $my_footer_text = "<span class=\"credit\"><img src=\"$plugin_url/twitch-tv-embed-suite/images/plumeria.png\" alt=\"Plumeria Web Design Logo\"/><a href=\"http://www.plumeriawebdesign.com/twitch-tv-embed-suite\">Twitch TV Embed Suite</a>. Developed by <a href=\"http://www.plumeriawebdesign.com\">Plumeria Web Design</a></span>";
	return $my_footer_text;
}
if(isset($_GET['page'])) {
if ($_GET['page'] == "twitch-options") {
  add_filter('admin_footer_text', 'plumwd_twitch_admin_footer_text');
}
}

?>