<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$xyz_lbx_cache_enable=get_option("xyz_lbx_cache_enable");
if($xyz_lbx_cache_enable==1)
{	
     add_action ( 'get_footer', 'xyz_lbx_container');//, [priority], [accepted_args] );
}
else 
{
	add_action ( 'get_footer', 'xyz_lbx_action_callback');
}
function xyz_lbx_container()
{
	echo "<span id='xyz_lbx_container'></span>";
}

	add_action( 'wp', 'xyz_lbx_create' );


function xyz_lbx_create()
{ 
  	global $xyz_lbx_cache_enable;
	$ispage=is_page()?1:0;
	$ispost=is_single()?1:0;
	$ishome=is_home()?1:0;
	wp_enqueue_script('jquery');
	if($xyz_lbx_cache_enable==1)
	{
	wp_enqueue_script( 'xyz_lbx_ajax_script', plugins_url( 'lbx_request.js', __FILE__ ), array('jquery') );
	wp_localize_script( 'xyz_lbx_ajax_script', 'xyz_lbx_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ),'ispage'=>$ispage,'ispost'=>$ispost,'ishome'=>$ishome) );
	}
}
	
add_action( 'wp_ajax_xyz_lbx_action', 'xyz_lbx_action_callback' );
add_action( 'wp_ajax_nopriv_xyz_lbx_action', 'xyz_lbx_action_callback' );
	
function xyz_lbx_action_callback()
{
	global $xyz_lbx_cache_enable;
	
	$page_option=get_option('xyz_lbx_page_option');
	$xyz_lbx_enable=get_option('xyz_lbx_enable');
	$xyz_lbx_showing_option=get_option('xyz_lbx_showing_option');
	
	if($page_option==2)
	{
		if($xyz_lbx_cache_enable==1)
		{
		    $page=$_POST['xyz_lbx_pg'];
		    $post=$_POST['xyz_lbx_ps'];
		    $home=$_POST['xyz_lbx_hm'];
		}
		else 
		{
			$page=is_page()?1:0;
			$post=is_single()?1:0;
			$home=is_home()?1:0;
		}	
		$xyz_lbx_sh_arr=explode(",", $xyz_lbx_showing_option);
		if (!(($xyz_lbx_sh_arr[0]==1 && $page==1) || ($xyz_lbx_sh_arr[1]==1 && $post==1 ) || ($xyz_lbx_sh_arr[2]==1 && $home==1)))
			return;
	}
	if($page_option==3)
	{
		if($xyz_lbx_cache_enable==1)
		{
        $shortcode=$_POST['xyz_lbx_shortcd'];
        if($shortcode!=1)
		    return;
		}
		else 
			return;
	}
	
    if($xyz_lbx_enable==1)
	  echo xyz_lbx_display();
    if($xyz_lbx_cache_enable==1)
    {
      die();
    }  
}

function xyz_lbx_display()
{
       
    $xyz_lbx_display_user=get_option('xyz_lbx_display_user');
    if($xyz_lbx_display_user==1 && is_user_logged_in()==1)
        return;
	$imgpath=plugins_url()."/lightbox-pop/images/";
	$closeimage=$imgpath."close.png";
	$dbcloseimage=$imgpath."dbclose.png";
	$color=get_option('xyz_lbx_color');
	$html=get_option('xyz_lbx_html');
	$top=get_option('xyz_lbx_top');
	$width=get_option('xyz_lbx_width');
	$height=get_option('xyz_lbx_height');
	$left=get_option('xyz_lbx_left');
	$bottom=get_option('xyz_lbx_bottom');
	$right=get_option('xyz_lbx_right');
	$delay=get_option('xyz_lbx_delay');
	$page_count=get_option('xyz_lbx_page_count');
	if($page_count==0) $page_count=1;
	$mode=get_option('xyz_lbx_mode');
	$repeat_interval=get_option('xyz_lbx_repeat_interval');
	$repeat_interval_timing=get_option('xyz_lbx_repeat_interval_timing');
	if($repeat_interval_timing==1)
	{
		$repeat_interval=$repeat_interval*60;
	}
$z_index=get_option('xyz_lbx_z_index');
$corner_radius=get_option('xyz_lbx_corner_radius');
$top_dim=get_option('xyz_lbx_top_dim');
$left_dim=get_option('xyz_lbx_left_dim');
$bottom_dim=get_option('xyz_lbx_bottom_dim');
$right_dim=get_option('xyz_lbx_right_dim');
$height_dim=get_option('xyz_lbx_height_dim');
$width_dim=get_option('xyz_lbx_width_dim');
$border_color=get_option('xyz_lbx_border_color');
$bg_color=get_option('xyz_lbx_bg_color');
$bg_opacity=get_option('xyz_lbx_bg_opacity');
$opacity=get_option('xyz_lbx_opacity');
$border_width=get_option('xyz_lbx_border_width');
$close_button_option=get_option('xyz_lbx_close_button_option');
$iframe_option=get_option('xyz_lbx_iframe');
$position_option=get_option("xyz_lbx_display_position");
$positioning=get_option('xyz_lbx_positioning');
$position_predefined=get_option('xyz_lbx_position_predefined');
$referar_message_show_option=get_option('xyz_lbx_referar_message_show_option');

$tmp=ob_get_contents();
ob_clean();
ob_start();

?>
	
<style type="text/css">
.lbx_overlay{
	display: none;
	position: fixed;
	_position: fixed;
	top: 0%;
	left: 0%;
	width: 100%;
	height: 100%;
	background-color:<?php echo $color?>;
	z-index:<?php echo $z_index;?>;
	-moz-opacity: <?php echo ($opacity/100);?>;
	opacity:<?php echo ($opacity/100);?>;
	filter: alpha(opacity=<?php echo $opacity;?>);
}
.lbx_content {
display: none;
position: fixed;
_position: fixed; 
<?php  if($positioning==1){if($position_option==1){?>;
top: <?php echo $top; echo $top_dim;?>;
left: <?php echo $left; echo $left_dim;?>;
<?php }?>
<?php  if($position_option==2){?>
top: <?php echo $top; echo $top_dim;?>;
right: <?php echo $right; echo $right_dim;?>;
<?php }?>
<?php  if($position_option==3){?>
bottom: <?php echo $bottom; echo $bottom_dim;?>;
left: <?php echo $left; echo $left_dim;?>;
<?php }?>
<?php  if($position_option==4){?>
bottom: <?php echo $bottom; echo $bottom_dim;?>;
right: <?php echo $right; echo $right_dim;?>;
<?php }}?>
width: <?php echo $width; echo $width_dim;?>;
height: <?php echo $height; echo $height_dim;?>;
padding: 0;
margin:0;
border: <?php echo $border_width; ?>px solid <?php echo $border_color;?>;
background-color: <?php echo $bg_color;?>;
opacity: <?php echo $bg_opacity>0?($bg_opacity/100):1;?>;
z-index:<?php echo $z_index+1;?>;
overflow: hidden;
border-radius:<?php echo $corner_radius;?>px;

box-sizing: content-box;
-moz-box-sizing: content-box;
-webkit-box-sizing: content-box;
}
.lbx_iframe{
width:100%;
height:100%;
border:0;
}
#closediv{
position:absolute;
cursor:pointer;
top: 0px;
right: 0px;
}
</style>
<div id="lbx_fade" class="lbx_overlay"  <?php if($close_button_option==0) { ?> onclick = "javascript:lbx_hide_lightbox()"<?php }?>></div>
<div id="lbx_light" class="lbx_content"><?php if(!isset($_COOKIE['_xyz_lbx_until'])) {
 if($close_button_option==1) {?><img id="closediv"   src="<?php  echo $closeimage;?>" onclick = "javascript:lbx_hide_lightbox()"><?php }?>
<!-- <div width="100%" height="20px" style="text-align:right;padding:0px;margin:0px;"><a href = "javascript:void(0)" onclick = "javascript:lbx_hide_lightbox()">CLOSE</a></div> -->
<?php if($iframe_option==1) { ?><iframe  src="<?php echo  get_bloginfo('wpurl') ;?>/index.php?xyz_lbx=iframe" class="lbx_iframe" scrolling="no"></iframe><?php }else{ 
echo do_shortcode($html);}
}?></div>
<script type="text/javascript">

function xyz_lbx_settings()
{
var hadjust;
var wiadjust;
var posit=<?php echo $positioning;?> 
var def_disp=<?php echo $position_predefined;?>;
var lbxwid=<?php echo $width; ?>;
var lbxwiddim="<?php echo $width_dim;?>";
var lbxhe=<?php echo $height; ?>;
var lbxhedim="<?php echo $height_dim;?>";
var screenheight=jQuery(window).height(); 
/*var screenheight=window.innerHeight;*/
var screenwidth=jQuery(window).width(); 
var lbxbordwidth=<?php echo $border_width;?>;



if(lbxhedim=="px")
{
hadjust=(screenheight-lbxhe)/2;
}
else
{
	hadjust=(100-lbxhe)/2;
}
if(lbxwiddim=="px")
{
wiadjust=(screenwidth-lbxwid)/2;
}
else
{
	wiadjust=(100-lbxwid)/2;
}

if(posit==2)
{
if(def_disp==2)
{
	document.getElementById("lbx_light").style.top=hadjust+lbxhedim;
	document.getElementById("lbx_light").style.left="0px";
}
if(def_disp==1)
{
	document.getElementById("lbx_light").style.top="0px";
	document.getElementById("lbx_light").style.left="0px";
}
if(def_disp==3)
{
	document.getElementById("lbx_light").style.bottom="0px";
	document.getElementById("lbx_light").style.left="0px";
}
if(def_disp==4)
{
	document.getElementById("lbx_light").style.bottom="0px";
	document.getElementById("lbx_light").style.left=wiadjust+lbxwiddim;
}
if(def_disp==5)
{
	document.getElementById("lbx_light").style.bottom="0px";
	document.getElementById("lbx_light").style.right="0px";
}
if(def_disp==6)
{
	document.getElementById("lbx_light").style.top=hadjust+lbxhedim;
	document.getElementById("lbx_light").style.right="0px";
}
if(def_disp==7)
{
	document.getElementById("lbx_light").style.top="0px";
	document.getElementById("lbx_light").style.right="0px";
}
if(def_disp==8)
{
	document.getElementById("lbx_light").style.top="0px";
	document.getElementById("lbx_light").style.left=wiadjust+lbxwiddim;
}
if(def_disp==9)
{
	document.getElementById("lbx_light").style.top=hadjust+lbxhedim;
	document.getElementById("lbx_light").style.left=wiadjust+lbxwiddim;
}
}
var bordwidth=<?php echo $border_width;?>;
	var newheight;
	var newwidth;
	if(lbxhedim=="%")
	{
		var hadnjust=(screenheight*lbxhe)/100;
		 newheight=hadnjust-(2*bordwidth);

		if(newheight<0)
			 newheight=0;
		   
		 document.getElementById("lbx_light").style.height=newheight+'px';
	}	
	if(lbxwiddim=="%")
	{
				var wiadnjust=(screenwidth*lbxwid)/100;
		 newwidth=wiadnjust-(2*bordwidth);

		 if(newwidth<0)
			 newwidth=0;
		 
			document.getElementById("lbx_light").style.width=newwidth+'px';
	}	


	/*if(lbxhedim=="px")
	{
	hadjust=(screenheight-lbxhe)/2;
	document.getElementById("lbx_light").style.height=hadjust;
	}
	
	if(lbxwiddim=="px")
	{
	wiadjust=(screenwidth-lbxwid)/2;
	document.getElementById("lbx_light").style.width=wiadjust;
	}*/
}
     	
	
var xyz_lbx_tracking_cookie_name="_xyz_lbx_until";
var xyz_lbx_pc_cookie_name="_xyz_lbx_pc";
var xyz_lbx_tracking_cookie_val=xyz_lbx_get_cookie(xyz_lbx_tracking_cookie_name);
var xyz_lbx_pc_cookie_val=xyz_lbx_get_cookie(xyz_lbx_pc_cookie_name);
var xyz_lbx_today = new Date();
if(xyz_lbx_pc_cookie_val==null)
xyz_lbx_pc_cookie_val = 1;
else
xyz_lbx_pc_cookie_val = (xyz_lbx_pc_cookie_val % <?php echo $page_count;?> ) +1;
expires_date = new Date( xyz_lbx_today.getTime() + (24 * 60 * 60 * 1000) );
document.cookie = xyz_lbx_pc_cookie_name + "=" + xyz_lbx_pc_cookie_val + ";expires=" + expires_date.toGMTString() + ";path=/";
function xyz_lbx_get_cookie( name )
{
var start = document.cookie.indexOf( name + "=" );
//alert(document.cookie);
var len = start + name.length + 1;
if ( ( !start ) && ( name != document.cookie.substring( 0, name.length ) ) )
{
return null;
}
if ( start == -1 ) return null;
var end = document.cookie.indexOf( ";", len );
if ( end == -1 ) end = document.cookie.length;
return unescape( document.cookie.substring( len, end ) );
}
function lbx_hide_lightbox()
{
document.getElementById("lbx_light").style.display="none";
document.getElementById("lbx_light").innerHTML="";
document.getElementById("lbx_fade").style.display="none";
}
function lbx_show_lightbox()
{
	xyz_lbx_settings();

	jQuery(window).resize(function(){
		xyz_lbx_settings();

 });
	
//alert(lbx_tracking_cookie_val);
if(xyz_lbx_tracking_cookie_val==1)
return;
if( "<?php echo $mode;?>" == "page_count_only"  || "<?php echo $mode;?>" == "both" )
{
if(xyz_lbx_pc_cookie_val != <?php echo $page_count;?>)
return;
}
document.getElementById("lbx_light").style.display="block";
document.getElementById("lbx_fade").style.display="block";
//expires_date = new Date( xyz_lbx_today.getTime() + (24 * 60 * 60 * 1000) );
//alert(xyz_lbx_today.toGMTString());
	expires_date = new Date(xyz_lbx_today.getTime() + (<?php echo $repeat_interval;?> * 60 * 1000));
document.cookie = xyz_lbx_tracking_cookie_name + "=1;expires=" + expires_date.toGMTString() + ";path=/";
}
if("<?php echo $mode;?>" == "page_count_only")
lbx_show_lightbox();
else
setTimeout("lbx_show_lightbox()",<?php echo $delay*1000;?>);
</script>
<?php 


$lbc = ob_get_contents();
ob_clean();
echo $tmp;
return $lbc;

}

?>