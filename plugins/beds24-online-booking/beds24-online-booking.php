<?php
/*
Plugin Name: Beds24 Online Booking
Plugin URI: https://beds24.com
Description: Beds24.com is a full featured online booking engine. The system is very flexible with many options for customization. The Beds24.com online booking system and channel manager is suitable for any type of accommodation such as hotels, motels, B&B's, hostels, vacation rentals, holiday homes, campgrounds and property management companies selling multiple properties as well as selling extras like tickets or tours. The plugin is free to use but you do need an account with Beds24.com. A free trial account is available <a href="https://beds24.com/join.html" target="_blank">here</a>
Version: 2.0.25
Author: Mark Kinchin
Author URI: https://beds24.com
License: GPL2 or later
*/
register_activation_hook(__FILE__,'beds24_booking_install');
register_deactivation_hook( __FILE__, 'beds24_booking_remove' );

add_filter('widget_text', 'do_shortcode', 11);
add_filter( 'query_vars', 'add_query_vars_filter' );

// INCLUDES
include_once('inc/widgets/beds24_widget.php');
include_once('inc/shortcodes/b24_jquery_widget_shortcode.php');

function add_query_vars_filter( $vars ){
  $vars[] = "propid";
  $vars[] = "roomid";
  return $vars;
}

function save_output_buffer_to_file()
{
    file_put_contents(
      ABSPATH. 'wp-content/plugins/activation_output_buffer.html'
    , ob_get_contents()
    );
}
add_action('activated_plugin','save_output_buffer_to_file');


function beds24_booking_install()
{
$all_options=beds24_all_options();
$default_values=beds24_all_options('default_values');
	foreach($all_options as $opt){
		if(NULL === get_option( $opt, NULL )){
			update_option( $opt, $default_values[$opt]);
		}
	}
}

function beds24_booking_remove()
{
	$all_options=beds24_all_options();
	foreach($all_options as $opt){
		delete_option($opt);
	}
}


function beds24_scripts()
{
if (!session_id() && !headers_sent())
		session_start();

wp_enqueue_script('jquery');
wp_enqueue_style('beds24', plugins_url( '/theme-files/beds24.css', __FILE__ ));
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
wp_enqueue_script( 'beds24-datepicker', plugins_url('/js/beds24-datepicker.js', __FILE__ ), array( 'jquery-ui-datepicker' ));
wp_localize_script('beds24-datepicker', 'WPURLS', array( 'siteurl' => get_option('siteurl') ));

wp_register_script( 'bed24-widget-script', '//media.xmlcal.com/widget/1.00/js/bookWidget.min.js', array('jquery'), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'beds24_scripts' );

add_shortcode("beds24", "beds24_booking_page");
add_shortcode("beds24-link", "beds24_booking_page_link");
add_shortcode("beds24-button", "beds24_booking_page_button");
add_shortcode("beds24-box", "beds24_booking_page_box");
add_shortcode("beds24-strip", "beds24_booking_page_strip");
add_shortcode("beds24-searchbox", "beds24_booking_page_searchbox");
add_shortcode("beds24-searchresult", "beds24_booking_page_searchresult");
add_shortcode("beds24-embed", "beds24_booking_page_embed");
add_shortcode("beds24-landing", "beds24_booking_page_landing");


add_action( 'admin_enqueue_scripts', 'beds24_admin_scripts' );

function beds24_admin_scripts( $hook_suffix ) {
		// first check that $hook_suffix is appropriate for your admin page
		wp_enqueue_style('beds24-admin-css', plugins_url( '/css/beds24-admin.css', __FILE__ ));
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'beds24-admin', plugins_url('/js/beds24-admin.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}


function beds24_booking_page_link($atts)
{
if (!is_array($atts))
  $atts = array();
if (!isset($atts['type']))
	$atts['type'] = 'link';
if (!isset($atts['padding']))
	$atts['padding'] = 0;
if (!isset($atts['text']))
	$atts['text'] = 'Book Now';
return beds24_booking_page($atts);
}

function beds24_booking_page_button($atts)
{
if (!is_array($atts))
  $atts = array();
if (!isset($atts['type']))
	$atts['type'] = 'button';
if (!isset($atts['text']))
	$atts['text'] = 'Book Now';
if (!isset($atts['class']))
	$atts['class'] = 'beds24_bookbutton';
return beds24_booking_page($atts);
}

function beds24_booking_page_box($atts)
{
if (!is_array($atts))
  $atts = array();
if (!isset($atts['type']))
	$atts['type'] = 'box';
if (!isset($atts['fontsize']))
	$atts['fontsize'] = '20';
return beds24_booking_page($atts);
}

function beds24_booking_page_strip($atts)
{
if (!is_array($atts))
  $atts = array();
if (!isset($atts['type']))
	$atts['type'] = 'strip';
if (!isset($atts['fontsize']))
	$atts['fontsize'] = '20';
return beds24_booking_page($atts);
}

function beds24_booking_page_searchbox($atts)
{
if (!is_array($atts))
  $atts = array();
if (!isset($atts['type']))
	$atts['type'] = 'searchbox';
if (!isset($atts['fontsize']))
	$atts['fontsize'] = '20';
return beds24_booking_page($atts);
}

function beds24_booking_page_searchresult($atts)
{
if (!is_array($atts))
  $atts = array();
if (!isset($atts['type']))
	$atts['type'] = 'searchresult';
if (!isset($atts['fontsize']))
	$atts['fontsize'] = '20';
return beds24_booking_page($atts);
}

function beds24_booking_page_embed($atts)
{
if (!is_array($atts))
  $atts = array();
if (!isset($atts['type']))
	$atts['type'] = 'embed';
if (!isset($atts['advancedays']))
	$atts['advancedays'] = '-1';
return beds24_booking_page($atts);
}

function beds24_booking_page_landing($atts)
{
if (!is_array($atts))
  $atts = array();
if (!isset($atts['type']))
	$atts['type'] = 'embed';
if (!isset($atts['noselection']))
	$atts['noselection'] = true;
return beds24_booking_page($atts);
}

function beds24_booking_page($atts)
{
$postid = get_the_ID();

//ownerid
if (isset($atts['ownerid']))
	$ownerid = $atts['ownerid'];
else if (get_post_meta($postid, 'ownerid', true)>0)
	$ownerid = get_post_meta($postid, 'ownerid', true);
else
	$ownerid = get_option('beds24_ownerid');

if ($ownerid > 0)
	$owner = '&amp;ownerid='.intval($ownerid);
else if (isset($atts['ownerid']))
	$owner = '&amp;ownerid='.intval($atts['ownerid']);
else
	$owner = '';

//propid
$propid = false;
if (isset($atts['propid']))
	$propid = $atts['propid'];
else if (get_post_meta($postid, 'propid', true)>0 && !isset($atts['ownerid']))
	$propid = get_post_meta($postid, 'propid', true);
else if (get_query_var('propid')>0 && !isset($atts['ownerid']))
  $propid = get_query_var('propid');
else if (!isset($atts['ownerid']))
	$propid = get_option('beds24_propid');

if ($propid > 0)
	$prop = '&amp;propid='.intval($propid);
else if (isset($atts['propid']))
	$prop = '&amp;propid='.intval($atts['propid']);
else
	$prop = '';

//roomid
if (isset($atts['roomid']))
	$roomid = $atts['roomid'];
else if (get_post_meta($postid, 'roomid', true)>0)
	$roomid = get_post_meta($postid, 'roomid', true);
else if (get_query_var('roomid')>0)
  $roomid = get_query_var('roomid');
else
	$roomid = get_option('beds24_roomid');

if ($roomid > 0)
	$room = '&amp;roomid='.intval($roomid);
else if (isset($atts['roomid']))
	$room = '&amp;roomid='.intval($atts['roomid']);
else
	$room = '';

//number of dates displayed
if (isset($atts['numdisplayed']))
	$numdisplayed = $atts['numdisplayed'];
else
	$numdisplayed = get_option('beds24_numdisplayed');
if (isset($numdisplayed) && $numdisplayed!=-1)
	$urlnumdisplayed = "&amp;numdisplayed=".urlencode(intval($numdisplayed));
else
	$urlnumdisplayed = '';

//show calendar
if (isset($atts['hidecalendar']))
	$hidecalendar = $atts['hidecalendar'];
else
	$hidecalendar = get_option('beds24_hidecalendar');
if (isset($hidecalendar) && $hidecalendar!=-1)
	$urlhidecalendar = "&amp;hidecalendar=".urlencode($hidecalendar);
else
	$urlhidecalendar = '';

//show header
if (isset($atts['hideheader']))
	$hideheader = $atts['hideheader'];
else
	$hideheader = get_option('beds24_hideheader');
if (isset($hideheader) && $hideheader!=-1)
	$urlhideheader = "&amp;hideheader=".urlencode($hideheader);
else
	$urlhideheader = '';

//show footer
if (isset($atts['hidefooter']))
	$hidefooter = $atts['hidefooter'];
else
	$hidefooter = get_option('beds24_hidefooter');
if (isset($hidefooter) && $hidefooter!=-1)
	$urlhidefooter = "&amp;hidefooter=".urlencode($hidefooter);
else
	$urlhidefooter = '';

//lang
if (isset($atts['lang']))
	$lang = strtolower($atts['lang']);
else if (get_post_meta($postid, 'lang', true))
	$lang = strtolower(get_post_meta($postid, 'lang', true));
else
	$lang = '';

if ($lang)
	$urllang = '&amp;lang='.$lang;
else
	$urllang = '';

//referer
if (isset($atts['referer']))
	$referer = strtolower($atts['referer']);
else if (get_post_meta($postid, 'referer', true))
	$referer = get_post_meta($postid, 'referer', true);
else
	$referer = get_option('beds24_referer');

if ($referer)
	$urlreferer = '&amp;referer='.urlencode($referer);
else
	$urlreferer = '';

//domain
if (isset($atts['domain']))
	$domain = strtolower($atts['domain']);
else if (get_post_meta($postid, 'domain', true))
	$domain = get_post_meta($postid, 'domain', true);
else
	$domain = get_option('beds24_domain');

if (!$domain)
	$domain = 'https://beds24.com';

//scrolltop (for iframe)
if (isset($atts['scrolltop']))
	$scrolltop = strtolower($atts['scrolltop']);
else if (get_post_meta($postid, 'scrolltop', true))
	$scrolltop = strtolower(get_post_meta($postid, 'scrolltop', true));
else
	$scrolltop = false;


//checkin or show this many days from now
$checkin = false;
if (isset($_REQUEST['checkin']))
	$checkin = $_REQUEST['checkin'];
else if (isset($_REQUEST['fdate_date']) && isset($_REQUEST['fdate_monthyear']))
	$checkin = date('Y-m-d', strtotime($_REQUEST['fdate_monthyear'].'-'.$_REQUEST['fdate_date']));
else if (isset($_SESSION['beds24-checkin']))
	{
	$checkin = $_SESSION['beds24-checkin'];
	}
else if (isset($atts['advancedays']))
	{
	$advancedays = $atts['advancedays'];
	if ($advancedays>=0)
		$checkin = date('Y-m-d', strtotime('+'.$advancedays.' days'));
	}
else
	{
	$advancedays = get_option('beds24_advancedays');
	if ($advancedays>=0)
		$checkin = date('Y-m-d', strtotime('+'.$advancedays.' days'));
	}

$urlcheckin = '';
if ($checkin)
	{
	$checkin = date('Y-m-d', strtotime($checkin));
	if ($checkin < date('Y-m-d'))
		$checkin = date('Y-m-d');
	$_SESSION['beds24-checkin'] = $checkin;
	if (!isset($atts['noselection']))
		$urlcheckin = "&amp;checkin=".urlencode($checkin);
	}

//default number of nights
if (isset($_REQUEST['numnight']))
	$numnight = $_REQUEST['numnight'];
else if (isset($_SESSION['beds24-numnight']))
	$numnight = $_SESSION['beds24-numnight'];
else if (isset($atts['numnight']))
	$numnight = $atts['numnight'];
else
	$numnight = get_option('beds24_numnight');
$_SESSION['beds24-numnight'] = $numnight;
if (isset($numnight) && !isset($atts['noselection']))
	$urlnumnight = "&amp;numnight=".urlencode(intval($numnight));
else
	$urlnumnight = '';

//number of guests
if (isset($_REQUEST['numadult']))
	$numadult = $_REQUEST['numadult'];
else if (isset($_SESSION['beds24-numadult']))
	$numadult = $_SESSION['beds24-numadult'];
else if (isset($atts['numadult']))
	$numadult = $atts['numadult'];
else
	$numadult = get_option('beds24_numadult');
$_SESSION['beds24-numadult'] = $numadult;
if (isset($numadult) && !isset($atts['noselection']))
	$urlnumadult = "&amp;numadult=".urlencode(intval($numadult));
else
	$urlnumadult = '';

if (isset($_REQUEST['numchild']))
	$numchild = $_REQUEST['numchild'];
else if (isset($_SESSION['beds24-numchild']))
	$numchild = $_SESSION['beds24-numchild'];
else if (isset($atts['numchild']))
	$numchild = $atts['numchild'];
else
	$numchild = get_option('beds24_numchild');
$_SESSION['beds24-numchild'] = $numchild;
if (isset($numchild) && !isset($atts['noselection']))
	$urlnumchild = "&amp;numchild=".urlencode(intval($numchild));
else
	$urlnumchild = '';



//default layout
if (isset($_REQUEST['layout']))
	$layout = $_REQUEST['layout'];
else if (isset($atts['layout']))
	$layout = $atts['layout'];
else
	$layout = get_option('beds24_layout');
if (isset($layout) && $layout>0)
	$urllayout = "&amp;layout=".urlencode(intval($layout));
else
	$urllayout = '';

//width of target
$width = false;
if (isset($atts['width']))
	{
	$width = $atts['width'];
	}
else
	$width = get_option('beds24_width');

if ($width<100)
	$width = 800;

//height of target
if (isset($atts['height']))
	$height = $atts['height'];
else
	$height = get_option('beds24_height');

if ($height<100)
	$height = 1600;

//type=link
//type=button
//type=box
//type=strip
//type=searchbox
//type=searchresult
//type=embed
if (isset($atts['type']))
	$type = $atts['type'];
else
	$type = 'embed';
//	$type = get_option('beds24_type');

//target=iframe
//target=window
//target=new
if (isset($atts['target']))
	$target = $atts['target'];
else if (get_option('beds24_target'))
	$target = get_option('beds24_target');
else
	$target = 'window';



if (isset($atts['display']))
	$display = $atts['display'];
else
	$display = '';

$suffix = '_'.$ownerid.'_'.$propid.'_'.$roomid;
if (isset($atts['targetid']))
	{
	$targetid = $atts['targetid'];
//	$target = 'none';
	}
else if (isset($atts['id']))
	{
	$targetid = $atts['id'];
	}
else
	$targetid = 'beds24target'.$suffix;

//widget text
if (isset($atts['text']))
	$text = htmlspecialchars($atts['text']);
else
	$text = 'Book Now';

//widget class
if (isset($atts['class']))
	$class = htmlspecialchars($atts['class']);
else
	$class = '';

//target url custom parameters
if (isset($atts['custom']))
	$custom = $atts['custom'];
else
	$custom = get_option('beds24_custom');
if (substr($custom,0,1) != '&')
	$custom = '&amp;'.$custom;

$style = 'cursor: pointer;';

//widget font size
if (isset($atts['fontsize']))
	$style .= 'font-size: '.$atts['fontsize'].';';

//widget color
if (isset($atts['color']))
	$style .= 'color: '.$atts['color'].';';
elseif (strlen(get_option('beds24_color')) >=3)
	$style .= 'color: '.get_option('beds24_color').';';;

//padding
if (isset($atts['padding']))
	$style .= 'padding: '.$atts['padding'].'px;';
else
	$style .= 'padding: '.get_option('beds24_padding').'px;';

$linkstyle = $style;

//widget background color
if (isset($atts['bgcolor']))
	$style .= 'background-color: '.$atts['bgcolor'].';';
elseif (strlen(get_option('beds24_bgcolor')) >=3)
	$style .= 'background-color: '.get_option('beds24_bgcolor').';';

$boxstyle = $style;
$buttonstyle = $style;

if (isset($atts['width']))
	$boxstyle .= 'max-width: '.$atts['width'].'px;';

$defaulthref = $domain.'/booking2.php';

//href target
if (isset($atts['href']))
	$href = $atts['href'];
else
	$href = $defaulthref;

$formurl = $href;
$url = $href.'?1'.$owner.$prop.$room.$urlnumdisplayed.$urlhideheader.$urlhidefooter.$urlhidecalendar.$urlcheckin.$urlnumnight.$urlnumadult.$urlnumchild.$urllayout.$urllang.$urlreferer.$custom;

include ('beds24-translations.php');

$output = '';
$thistarget = '';
$onclick = '';
$linkclass = '';

if ($target == 'window')
	{
	if ($type != 'box' && $type != 'strip')
		{
		if ($formurl == $defaulthref) //stay on same page
		$formurl = '';
		}
	}
else if ($target == 'new')
	{
	$thistarget = ' target="_blank" ';
	}
else //iframe
	{
	if ($target != 'none')
		$target = 'iframe';
	$onclick = 'onclick="jQuery(\'#'.$targetid.'\').show();jQuery(\'#beds24book'.$suffix.'\').hide();return false;"';
	if ($type != 'embed')
		$display = 'none';
	}


if ($type == 'link')
	{
	$output .= '<a '.$thistarget.' class="'.$linkclass.$class.'" id="beds24book'.$suffix.'" style="'.$linkstyle.'" href="'.esc_url($url).'" '.$onclick.'>';
	$output .= htmlspecialchars($text);
	$output .= '</a>';
	}
else if ($type == 'button')
	{
	$output .= '<a '.$thistarget.' class="'.$linkclass.'" id="beds24book'.$suffix.'" style="text-decoration:none;" href="'.esc_url($url).'" '.$onclick.'>';
	$output .= '<button class="'.$class.'" style="'.$buttonstyle.'">'.htmlspecialchars($text).'</button>';
	$output .= '</a>';
	}
else if ($type == 'box' || $type == 'strip' || $type == 'searchbox' || $type == 'searchresult')
	{
	$searchbox = false;
	if ($type == 'box' || $type == 'strip' || $type == 'searchbox')
	{
	if ($type == 'box')
		{
		if ($lang)
			{
			ob_start();
			get_template_part('beds24-box-'.$lang);
			$searchbox = ob_get_clean();
			}
		if (!$searchbox)
			{
			ob_start();
			get_template_part('beds24-box');
			$searchbox = ob_get_clean();
			}
		$file =  plugin_dir_path( __FILE__ ) . 'theme-files/beds24-box-'.$lang.'.php';
		if (!$searchbox && $lang && file_exists($file))
			{
			ob_start();
			include($file);
			$searchbox .= ob_get_clean();
			}
		if (!$searchbox)
			{
			ob_start();
			include( plugin_dir_path( __FILE__ ) . 'theme-files/beds24-box.php');
			$searchbox .= ob_get_clean();
			}
		}
	else if ($type == 'strip')
		{
		if ($lang)
			{
			ob_start();
			get_template_part('beds24-strip-'.$lang);
			$searchbox = ob_get_clean();
			}
		if (!$searchbox)
			{
			ob_start();
			get_template_part('beds24-strip');
			$searchbox = ob_get_clean();
			}
		$file =  plugin_dir_path( __FILE__ ) . 'theme-files/beds24-strip-'.$lang.'.php';
		if (!$searchbox && $lang && file_exists($file))
			{
			ob_start();
			include($file);
			$searchbox .= ob_get_clean();
			}
		if (!$searchbox)
			{
			ob_start();
			include( plugin_dir_path( __FILE__ ) . 'theme-files/beds24-strip.php');
			$searchbox .= ob_get_clean();
			}
		}
	else if($type == 'searchbox')
		{
			if ($lang)
			{
			ob_start();
			get_template_part('beds24-searchbox-'.$lang);
			$searchbox = ob_get_clean();
			}
		if (!$searchbox)
			{
			ob_start();
			get_template_part('beds24-searchbox');
			$searchbox = ob_get_clean();
			}
		$file = plugin_dir_path( __FILE__ ) . 'theme-files/beds24-searchbox-'.$lang.'.php';
		if (!$searchbox && $lang && file_exists($file))
		{
		ob_start();
		include($file);
		$searchbox .= ob_get_clean();
		}
		if (!$searchbox)
		{
		ob_start();
		include( plugin_dir_path( __FILE__ ) . 'theme-files/beds24-searchbox.php');
		$searchbox .= ob_get_clean();
		}
		}


	$output .= '<div id="beds24'.$type.$suffix.'" style="'.$boxstyle.'" class="beds24'.$type.'">';
	$output .= '<form '.$thistarget.' id="beds24book'.$suffix.'" method="post" action="'.$formurl.'">';
	if ($ownerid > 0)
		$output .= '<input type="hidden" name="ownerid" value="'.$ownerid.'">';
	if ($propid > 0)
		$output .= '<input type="hidden" name="propid" value="'.$propid.'">';
	if ($roomid > 0)
		$output .= '<input type="hidden" name="roomid" value="'.$roomid.'">';
	if (isset($numdisplayed) && $numdisplayed!=-1)
		$output .= '<input type="hidden" name="numdisplayed" value="'.$numdisplayed.'">';
	if (isset($hidecalendar) && $hidecalendar!=-1)
		$output .= '<input type="hidden" name="hidecalendar" value="'.$hidecalendar.'">';
	if (isset($hideheader) && $hideheader!=-1)
		$output .= '<input type="hidden" name="hideheader" value="'.$hideheader.'">';
	if (isset($hidefooter) && $hidefooter!=-1)
		$output .= '<input type="hidden" name="hidefooter" value="'.$hidefooter.'">';
	if ($lang)
		$output .= '<input type="hidden" name="lang" value="'.$lang.'">';
	if ($referer)
		$output .= '<input type="hidden" name="referer" value="'.$referer.'">';


	$output .= $searchbox;
	$output .= '</form>';
	$output .= '</div>';

	$output .= '<script>jQuery(document).ready(function($) {';

	if (isset($_REQUEST['showmoredetails']) && $_REQUEST['showmoredetails']>0)
		$output .= '';
	else
		$output .= '$("#B24advancedsearch").hide();';

	$output .= '});</script>';
	}


	if($type == 'box')
	{

	}
	if($type == 'searchresult')
	{
	if (isset($_REQUEST['fdate_date']))
		{
		$xmlurl = 'https://api.beds24.com/getavailabilities.xml';
		$postarray = array( 'ownerid' => $ownerid, 'checkin' => $checkin, 'numnight' => $numnight, 'numadult' => $numadult , 'numchild' => $numchild );

		$category = array();
		foreach ($_REQUEST as $key => $val)
			{
			if (substr($key,0,8) == 'category')
				{
				$cat = substr($key,8,1);
				if ($cat>=1 && $cat<=4)
					{
					if (strlen($key)>9)
						$val = substr($key,10);
					$val = intval($val);
					if ($val > 0)
						{
						if (isset($category[$cat]))
							$category[$cat] .= ',';
						$category[$cat] .= $val;
						}
					}
				}
			}

		foreach ($category as $key => $val)
			{
			$postarray['category'.$key] = $val;
			}

		$args = array(
			'method' => 'POST',
			'timeout' => 15,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'body' => $postarray,
			'cookies' => array()
			);
		$response = wp_remote_post($xmlurl, $args);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			$output .=  "Something went wrong: $error_message";
			return $output;
		} else {
			$result = new SimpleXMLElement($response['body']);
			$xmlowner = $result->owner;
            if (!isset($xmlowner->property) || !$xmlowner->property || !is_object($xmlowner) || count($xmlowner->property)==0)
                {
                $output .= '<p class="b24_message">No results found</p>';
                }
			else
			{
			foreach ($xmlowner->property as $xmlproperty)
				{
				$propid = intval($xmlproperty['id']);
				$name = $xmlproperty['name'];
				$bestprice = $xmlproperty['bestPrice'];
				$bookurl = $domain.'/booking2.php?propid='.$propid.'&amp;checkin='.$checkin.'&amp;numadult='.$numadult.'&amp;numchild='.$numchild.'&amp;numnight='.$numnight.$urlnumdisplayed.$urlhideheader.$urlhidefooter.$urlhidecalendar.$urlcheckin.$urllang.$urlreferer.$custom;
				$propoutput = false;

				$args = array('meta_key' => 'propid', 'meta_value'=> $propid);
				$mypropposts = get_posts( $args );
				foreach ($mypropposts as $post)
					{
					$postoutput = false;
					setup_postdata($post);
					if ($lang)
						{
						ob_start();
						include(locate_template('beds24-prop-post-'.$lang));
						$postoutput = ob_get_clean();
						}
					if (!$postoutput)
						{
						ob_start();
						include(locate_template('beds24-prop-post'));
						$postoutput = ob_get_clean();
						}
					$file = plugin_dir_path( __FILE__ ) . 'theme-files/beds24-prop-post-'.$lang.'.php';
					if (!$postoutput && $lang && file_exists($file))
						{
						ob_start();
						include($file);
						$postoutput = ob_get_clean();
						}
					if (!$postoutput)
						{
						ob_start();
						include( plugin_dir_path( __FILE__ ) . 'theme-files/beds24-prop-post.php');
						$postoutput = ob_get_clean();
						}
					$propoutput .= $postoutput;
					}

				if (!$propoutput)
					{
					if ($lang)
						{
						ob_start();
						get_template_part('beds24-prop-xml-'.$lang);
						$postoutput = ob_get_clean();
						}
					if (!$postoutput)
						{
						ob_start();
						get_template_part('beds24-prop-xml');
						$postoutput = ob_get_clean();
						}
					$file =  plugin_dir_path( __FILE__ ) . 'theme-files/beds24-prop-xml-'.$lang.'.php';
					if (!$postoutput && $lang && file_exists($file))
						{
						ob_start();
						include($file);
						$postoutput = ob_get_clean();
						}
					if (!$postoutput)
						{
						ob_start();
						include( plugin_dir_path( __FILE__ ) . 'theme-files/beds24-prop-xml.php');
						$postoutput = ob_get_clean();
						}
					$propoutput .= $postoutput;
					}
				$output .= $propoutput;
				}
			}
			}
		}
	}
	}//end box

if ($type=='embed' || $target=='iframe') //iframe
	{
	$output .= '<div id="'.$targetid.'">';
	if ($scrolltop == 'no')
		$output .= '<iframe src ="'.$url.'" width="'.$width.'" height="'.$height.'" style="max-width:100%;border:none;overflow:auto;"><p><a href="'.esc_url($url).'">'.htmlspecialchars($text).'</a></p></iframe>';
	else
		$output .= '<iframe onload="window.parent.parent.scrollTo(0,0)" src ="'.$url.'" width="'.$width.'" height="'.$height.'" style="max-width:100%;border:none;overflow:auto;"><p><a href="'.$url.'">'.htmlspecialchars($text).'</a></p></iframe>';
	$output .= '</div>';
	if ($display == 'none')
		{
		$output .= '<script>jQuery(document).ready(function($) {jQuery("#'.$targetid.'").hide(); });</script>';
		}
	}

return $output;
}

include_once('inc/plugin-options/beds24-options-page.php');
