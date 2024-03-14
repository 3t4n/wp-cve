<?php
/*
Plugin Name: Vertical scroll image slideshow gallery
Plugin URI: http://www.gopiplus.com/work/2010/07/18/vertical-scroll-image-slideshow-gallery/
Description:  This vertical scroll image slideshow gallery is a simple image vertical scroll slideshow plugin for WordPress.
Author: Gopi Ramasamy
Version: 11.1
Author URI: http://www.gopiplus.com/work/
Donate link: http://www.gopiplus.com/work/2010/07/18/vertical-scroll-image-slideshow-gallery/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: vertical-scroll-image-slideshow-gallery
Domain Path: /languages
*/

function VSslideshow_slideshow() 
{
	$atts = array();
	$atts["dir"] = get_option('VSslideshow_dir');
	$atts["imglink"] = get_option('VSslideshow_imglink');
	$atts["width"] = get_option('VSslideshow_width');
	$atts["height"] = get_option('VSslideshow_height');
	$atts["time"] = get_option('VSslideshow_time');
	echo VSslideshow_shortcode($atts);
}

function VSslideshow_shortcode($atts) 
{
	//[vertical-scroll-image-slideshow-gallery dir="wp-content/plugins/vertical-scroll-image-slideshow-gallery/VSslideshow/" imglink="#" width="200px" height="175px" time="3000"]
	if ( ! is_array( $atts ) ) {
		return '';
	}
	$gSlidedir = isset($atts['dir']) ? $atts['dir'] : '';
	$gSlideimglink = isset($atts['imglink']) ? $atts['imglink'] : '#';
	$VSslideshow_width = isset($atts['width']) ? $atts['width'] : '200px';
	$VSslideshow_height = isset($atts['height']) ? $atts['height'] : '175px';
	$VSslideshow_time = isset($atts['time']) ? $atts['time'] : '3000';
	$gSlidesiteurl = get_option('siteurl');
	
	if($gSlideimglink == "") {
		$gSlideimglink = '#';
	}
	
	$VS = "";
	if(is_dir($gSlidedir))
	{
		$gSlidedirHandle = opendir($gSlidedir);
		$vs_count = -1;
		$gSlidereturnstr = "";
	
		while ($gSlidefile = readdir($gSlidedirHandle)) 
		{
		  if(!is_dir($gSlidefile) && (strpos(strtoupper($gSlidefile), '.JPG')>0 or 
		  		strpos(strtoupper($gSlidefile), '.GIF')>0 or 
					strpos(strtoupper($gSlidefile), '.PNG')>0 or 
						strpos(strtoupper($gSlidefile), '.JPEG')>0)) 
		  {
			 $vs_count++;
			 $gSlidereturnstr = $gSlidereturnstr . "vs_slideimages[$vs_count]='<a href=\'$gSlideimglink\'><img src=\'$gSlidesiteurl/$gSlidedir$gSlidefile\' border=\'0\'></a>'; ";
		  }
		}
		closedir($gSlidedirHandle);
		
		$VS .= '<script language="JavaScript1.2">';
			$VS .= "var vs_scrollerwidth='" . $VSslideshow_width . "';";
			$VS .= "var vs_scrollerheight='" . $VSslideshow_height . "';";
			$VS .= "var vs_pausebetweenimages=" . $VSslideshow_time . ";";
			$VS .= "var vs_slideimages=new Array();";
			$VS .= $gSlidereturnstr;
		$VS .= '</script>';
		
		$VS .= '<script type="text/javascript" src="' . plugins_url() . '/vertical-scroll-image-slideshow-gallery/vertical-scroll-image-slideshow-gallery.js"></script>';
		$VS .= '<ilayer id="vs_main" width=&{vs_scrollerwidth}; height=&{vs_scrollerheight}; visibility=hide>';
			$VS .= '<layer id="vs_first" width=&{vs_scrollerwidth};>';
				$VS .= '<script language="JavaScript1.2">';
				$VS .= 'if (document.layers)';
					$VS .= 'document.write(vs_slideimages[0]);';
				$VS .= '</script>';
			$VS .= '</layer>';
			$VS .= '<layer id="vs_second" width=&{vs_scrollerwidth}; visibility=hide>';
				$VS .= '<script language="JavaScript1.2">';
					$VS .= 'if (document.layers)';
					$VS .= 'document.write(vs_slideimages[dyndetermine=(vs_slideimages.length==1)? 0 : 1]);';
				$VS .= '</script>';
			$VS .= '</layer>';
		$VS .= '</ilayer>';
		
		$VS .= '<script language="JavaScript1.2">';
		$VS .= 'if (ie||dom)';
		$VS .= '{';
			$VS .= "document.writeln('<div style=\"padding:8px 0px 8px 0px;\">');";
			$VS .= "document.writeln('<div id=\"vs_main2\" style=\"position:relative;width:'+vs_scrollerwidth+';height:'+vs_scrollerheight+';overflow:hidden;\">');";
			$VS .= "document.writeln('<div style=\"position:absolute;width:'+vs_scrollerwidth+';height:'+vs_scrollerheight+';clip:rect(0 '+vs_scrollerwidth+' '+vs_scrollerheight+' 0);\">');";
			$VS .= "document.writeln('<div id=\"vs_first2\" style=\"position:absolute;width:'+vs_scrollerwidth+';left:0px;top:1px;\">');";
			$VS .= "document.write(vs_slideimages[0]);";
			$VS .= "document.writeln('</div>');";
			$VS .= "document.writeln('<div id=\"vs_second2\" style=\"position:absolute;width:'+vs_scrollerwidth+';visibility:hidden\">');";
			$VS .= "document.write(vs_slideimages[dyndetermine=(vs_slideimages.length==1)? 0 : 1]);";
			$VS .= "document.writeln('</div>');";
			$VS .= "document.writeln('</div>');";
			$VS .= "document.writeln('</div>');";
			$VS .= "document.writeln('</div>');";
		$VS .= '}';
		$VS .= '</script>';
	}
	else
	{
		$VS .= "Folder not found<br />" . $gSlidedir;
	}
	return $VS;
}

function VSslideshow_install() 
{
	add_option('VSslideshow_title', "Slide Show");
	add_option('VSslideshow_width', "200px");
	add_option('VSslideshow_height', "175px");
	add_option('VSslideshow_time', "3000");
	add_option('VSslideshow_dir', "wp-content/plugins/vertical-scroll-image-slideshow-gallery/VSslideshow/");
	add_option('VSslideshow_imglink', "#");
}

function VSslideshow_widget($args) 
{
	extract($args);
	echo $before_widget . $before_title;
	echo get_option('VSslideshow_title');
	echo $after_title;
	VSslideshow_slideshow();
	echo $after_widget;
}

function VSslideshow_control() 
{
	$VSslideshow_title 		= get_option('VSslideshow_title');
	$VSslideshow_width 		= get_option('VSslideshow_width');
	$VSslideshow_height 	= get_option('VSslideshow_height');
	$VSslideshow_time 		= get_option('VSslideshow_time');
	$VSslideshow_dir 		= get_option('VSslideshow_dir');
	$VSslideshow_imglink 	= get_option('VSslideshow_imglink');
	
	if (isset($_POST['VSslideshow_submit'])) 
	{
		$VSslideshow_title 		= stripslashes(sanitize_text_field($_POST['VSslideshow_title']));
		$VSslideshow_width 		= stripslashes(sanitize_text_field($_POST['VSslideshow_width']));
		$VSslideshow_height 	= stripslashes(sanitize_text_field($_POST['VSslideshow_height']));
		$VSslideshow_time 		= stripslashes(intval($_POST['VSslideshow_time']));
		$VSslideshow_dir 		= stripslashes(sanitize_text_field($_POST['VSslideshow_dir']));
		$VSslideshow_imglink 	= stripslashes(sanitize_text_field($_POST['VSslideshow_imglink']));
		
		update_option('VSslideshow_title', $VSslideshow_title );
		update_option('VSslideshow_width', $VSslideshow_width );
		update_option('VSslideshow_height', $VSslideshow_height );
		update_option('VSslideshow_time', $VSslideshow_time );
		update_option('VSslideshow_dir', $VSslideshow_dir );
		update_option('VSslideshow_imglink', $VSslideshow_imglink );
	}
	
	echo '<p>'.__('Title:', 'vertical-scroll-image-slideshow-gallery').' <input  style="width: 400px;" maxlength="100" type="text" value="';
	echo $VSslideshow_title . '" name="VSslideshow_title" id="VSslideshow_title" /></p>';
	
	echo '<p>'.__('Set the scrollerwidth and scrollerheight to the width/height of the LARGEST image in your slideshow!', 'vertical-scroll-image-slideshow-gallery').'</p>';
	
	echo '<p>'.__('Width:', 'vertical-scroll-image-slideshow-gallery').' <input  style="width: 100px;" maxlength="5" type="text" value="';
	echo $VSslideshow_width . '" name="VSslideshow_width" id="VSslideshow_width" />';
	
	echo '&nbsp;&nbsp;&nbsp;'.__('Height:', 'vertical-scroll-image-slideshow-gallery').' <input  style="width: 100px;" maxlength="5" type="text" value="';
	echo $VSslideshow_height . '" name="VSslideshow_height" id="VSslideshow_height" /></p>';
	
	echo '<p>'.__('Slide timeout:', 'vertical-scroll-image-slideshow-gallery').' <input  style="width: 200px;" maxlength="6" type="text" value="';
	echo $VSslideshow_time . '" name="VSslideshow_time" id="VSslideshow_time" /> (3000 = 3 seconds)</p>';
	
	echo '<p>'.__('Images Link:', 'vertical-scroll-image-slideshow-gallery').'<br><input  style="width: 570px;" type="text" value="';
	echo $VSslideshow_imglink . '" name="VSslideshow_imglink" id="VSslideshow_imglink" /></p>';
	
	echo '<p>'.__('Image directory: (Upload all your images in this directory)', 'vertical-scroll-image-slideshow-gallery').'<br><input  style="width: 570px;" type="text" value="';
	echo $VSslideshow_dir . '" name="VSslideshow_dir" id="VSslideshow_dir" />';
	echo '<br />Default: wp-content/plugins/vertical-scroll-image-slideshow-gallery/VSslideshow/';
	echo '<br /><br />'.__('Note: Dont upload your original images into plugin folder. if you upload the images into plugin folder, you may lose the images when you update the plugin to next version. Thus upload your images in "wp-content/uploads/your-folder/" folder and use the folder path as per the example in "Image Directory" text box.', 'vertical-scroll-image-slideshow-gallery').'</p>';
	
	echo '<input type="hidden" id="VSslideshow_submit" name="VSslideshow_submit" value="1" />';
	
	?>
	<?php _e('Check official website for more info', 'vertical-scroll-image-slideshow-gallery'); ?> 
	<a target="_blank" href="http://www.gopiplus.com/work/2010/07/18/vertical-scroll-image-slideshow-gallery/"><?php _e('Click here', 'vertical-scroll-image-slideshow-gallery'); ?></a><br><br> 
	<?php
}

function VSslideshow_widget_init() 
{
	if(function_exists('wp_register_sidebar_widget')) 	
	{
		wp_register_sidebar_widget('vs-slideshow', __('VS slideshow', 'vertical-scroll-image-slideshow-gallery'), 'VSslideshow_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 	
	{
		wp_register_widget_control('vs-slideshow', array(__('VS slideshow', 'vertical-scroll-image-slideshow-gallery'), 'widgets'), 'VSslideshow_control', 'width=650');
	} 
}

function VSslideshow_deactivation() 
{
	// No required.
}

function VSslideshow_textdomain() 
{
	load_plugin_textdomain( 'vertical-scroll-image-slideshow-gallery', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_shortcode('vertical-scroll-image-slideshow-gallery', 'VSslideshow_shortcode');
add_action('plugins_loaded', 'VSslideshow_textdomain');
add_action("plugins_loaded", "VSslideshow_widget_init");
register_activation_hook(__FILE__, 'VSslideshow_install');
register_deactivation_hook(__FILE__, 'VSslideshow_deactivation');
add_action('init', 'VSslideshow_widget_init');
?>