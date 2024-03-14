<?php
/*
Plugin Name: Strx Magic Floating Sidebar Maker
Plugin URI: http://www.strx.it
Description: Makes your blog sidebar floatable
Version: 1.4.1
Author: Strx
Author URI: http://www.strx.it
License: GPL2
*/

function strx_floating_sidebar_defaults(){
    return array(
        'content'=>'#content',
        'sidebar'=>'#sidebar',
        'wait'=>3000,
        'debounce'=>500,
        'animate'=>500,
        'offsetTop'=>0,
        'offsetBottom'=>0,
        'debug'=>0,
        'outline'=>0,
		    'findids'=>0,
		    'dynamicTop'=>false,
		    'jsInHead'=>false,
        'minHDiff'=>0
    );
}

function strx_floating_sidebar_get_options(){
    $opts=strx_floating_sidebar_defaults();
    foreach($opts as $o=>$v){
        $opts[$o]=get_option('strx-magic-floating-sidebar-maker-'.$o, $v);
    }
    return $opts;
}

function strx_floating_sidebar_start(){
    $opts=strx_floating_sidebar_get_options();
	if (!is_user_logged_in()){
		$opts['findids']=0;
		$opts['debug']=0;
		$opts['outline']=0;
	}
    echo '<script type="text/javascript">strx.start('.json_encode($opts).');</script>';
}

function strx_floating_sidebar_settings_menu(){
    add_options_page(__('Strx Floating Sidebar','strx'), __('Strx Floating Sidebar','strx'), 'manage_options', 'strx_floating_sidebar_settings', 'strx_floating_sidebar_settings');
}

function strx_floating_sidebar_settings_input($name, $value, $label, $cls='regular-text'){
    $rv ='   <tr>';
    $rv.='    <th><label for="strx-magic-floating-sidebar-maker-'.$name.'">'.$label.'</label></th>';
	$rv.='   </tr><tr>';
    $rv.='    <td><input class="'.$cls.'" id="strx-magic-floating-sidebar-maker-'.$name.'" name="strx-magic-floating-sidebar-maker-'.$name.'" value="'.$value.'"></td>';
    $rv.='   </tr>';
    return $rv;
}
function strx_floating_sidebar_settings_checkbox($name, $value, $label, $cls=''){
    $rv ='   <tr>';
    $rv.='    <th>';
	$rv.='		<input class="'.$cls.'" type="checkbox" '.($value?'checked':'').' id="strx-magic-floating-sidebar-maker-'.$name.'" name="strx-magic-floating-sidebar-maker-'.$name.'">';
	$rv.='		<label for="strx-magic-floating-sidebar-maker-'.$name.'">'.$label.'</label>';
	$rv.='	  </th>';
    $rv.='   </tr>';
    return $rv;
}
function strx_floating_sidebar_settings(){
    if (!current_user_can('manage_options')){ wp_die( __('You do not have sufficient permissions to access this page.') ); }

    //Previous Saved Values or Default Ones
    $opts=strx_floating_sidebar_get_options();

    //Update options
    if( isset($_POST[ 'strx-magic-floating-sidebar-maker-update' ]) ) {
        foreach($opts as $o=>$v){
            $opts[$o]=$_POST['strx-magic-floating-sidebar-maker-'.$o];
            update_option( 'strx-magic-floating-sidebar-maker-'.$o, $opts[$o] );
        }
    }

    extract($opts);

	$affiliates=strx_floating_sidebar_affiliates();

    $rv ='<div class="wrap">';
    $rv.=' <h2>Strx Floating Sidebar Settings</h2>';
    $rv.=' <form id="strx-magic-floating-sidebar-maker-update-form" name="strx-magic-floating-sidebar-maker-update-form" method="post" action="">';
    $rv.='  <input id="strx-magic-floating-sidebar-maker-update" name="strx-magic-floating-sidebar-maker-update" type="hidden" value="1">';
    $rv.='  <table class="form-table">';

	$rv.=strx_floating_sidebar_settings_input('content',$content,
			'<b>Content Selector</b>. HTML selector to let the sidebar know where is the content. <br/>'.
			'You can find it using inspect utilities like Firebug for firefox or Chrome console, but '.
			'if you are not familiar with these programming tools, I\'ve developed a little utility that show all the page selectors on blog footer '.
			'and clicking on them changes block background; activate it checking the <b>FINDIDS</b> option below and reload your blog page, '.
			'then click on ids displayed to identify them');
    $rv.=strx_floating_sidebar_settings_input('sidebar',$sidebar,
			'<b>Sidebar Selector</b>');

$rv.='<tr><td><div><script type="text/javascript"><!--
google_ad_client = "pub-8907793348376201";
/* 468x60, per plugin e widget wp */
google_ad_slot = "8331203622";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div></td></tr>';

	$rv.=strx_floating_sidebar_settings_input('wait',$wait,
			'<b>Wait</b> Milliseconds Before Activation, after page has loaded','small-text');
    $rv.=strx_floating_sidebar_settings_input('debounce',$debounce,
			'Milliseconds Of <b>Inactivity Before Every Reposition</b>. '.
			'Sidebar will start moving only after this time from when the user has stopped scrolling up or down','small-text');
    $rv.=strx_floating_sidebar_settings_input('animate',$animate,
			'<b>Animate Speed</b> in Milliseconds; how much time will the sidebar take to go to align itself with the content','small-text');
    $rv.=strx_floating_sidebar_settings_input('offsetTop',$offsetTop,
      '<b>Offset Top</b>; lets you adjust settings for a pixel perfect result; accepts positive and negative values','small-text');
    $rv.=strx_floating_sidebar_settings_input('offsetBottom',$offsetBottom,
      '<b>Offset Bottom</b>','small-text');
    $rv.=strx_floating_sidebar_settings_input('minHDiff',$minHDiff,
			'<b>Minimum Height Difference</b>; if (container height - sidebar height < minHDiff) then the plugin is not activated; if <i>dynamicTop</i> is checked, this option is not considered','small-text');
	  $rv.=strx_floating_sidebar_settings_checkbox('dynamicTop',$dynamicTop,
			  '<b>dynamicTop</b>: Needed if you page length change dynamically, using Ajax, css or other methods','');
	  $rv.=strx_floating_sidebar_settings_checkbox('jsInHead',$jsInHead,
			  '<b>jsInHead</b>: Put javascript in header instead of footer (some wp themes does not support wp_footer action)','');

	$rv.='<tr><td><div>'.$affiliates[array_rand($affiliates)].'</div></td></tr>';

	$rv.=strx_floating_sidebar_settings_checkbox('debug',$debug,
			'<b>DEBUG</b>: If checked and you are loggin in, print useful information on console (not for Internet Explorer)','');
    $rv.=strx_floating_sidebar_settings_checkbox('findids',$findids,
			'<b>FINDIDS</b>: If checked and you are loggin in, will print a list of id<i>s</i> on your blog footer to help you identify '.
			'your content and sidebar sections; click on them and see what happens','');
    $rv.=strx_floating_sidebar_settings_checkbox('outline',$outline,
			'<b>OUTLINE</b>: If checked and you are loggin in, will outline with a dotted red line the sections you have identified as content and sidebar, '.
			'helping you know if they are right','');

	$rv.=   '<tr><td><b>If you like this plugin</b> help me spread and improve it. How? Simple: '.
                    '<a target="_blank" href="http://wordpress.org/extend/plugins/strx-magic-floating-sidebar-maker/">rate it with 5 stars and say it works</a>, '.
                    'subscribe to my feed by <a target="_blank" href="http://feedburner.google.com/fb/a/mailverify?uri=StrxBlog">email</a> or <a href="http://feeds.feedburner.com/StrxBlog" target="blank">any other client</a>, '.
                    '<a target="_blank" href="http://twitter.com/fstraps">follow me on twitter</a>, '.
                    '<a target="_blank" href="http://www.strx.it/donate">make a donation</a>. Thank you.'.
                '</td></tr>';

    $rv.='  <tr><td><p class="submit"><input type="submit" name="Submit" class="button-primary" value="Save Changes" /></p></td></tr>';
    $rv.='  </table>';
    $rv.=' </form>';
    $rv.='</div>';

    echo $rv;
}


//Registering scripts and plugin hooks
if ( !is_admin() ) {
    wp_register_script('debounce', WP_PLUGIN_URL.'/strx-magic-floating-sidebar-maker/js/debounce.js');
    wp_register_script('strx-magic-floating-sidebar-maker', WP_PLUGIN_URL.'/strx-magic-floating-sidebar-maker/js/strx-magic-floating-sidebar-maker.js', array('jquery','debounce'));

    wp_enqueue_script('debounce');
    wp_enqueue_script('strx-magic-floating-sidebar-maker');

    //add_action('wp_footer','strx_floating_sidebar_start');
    $o=strx_floating_sidebar_get_options();
    add_action( ($o['jsInHead']?'wp_head':'wp_footer') , 'strx_floating_sidebar_start');

}else{
    add_action('admin_menu', 'strx_floating_sidebar_settings_menu');
}




function strx_floating_sidebar_affiliates(){
	return array(
		//Elegant themes affiliate program http://www.elegantthemes.com/affiliates/
		'<a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=6321_0_1_7" target="_blank"><img border="0" src="http://www.elegantthemes.com/affiliates/banners/468x60.gif" width="468" height="60"></a>',
		'<a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=6321_0_1_7" target="_blank"><img border="0" src="http://www.elegantthemes.com/affiliates/banners/468x60.gif" width="468" height="60"></a>',
		//Envato refer program http://themeforest.net/wiki/referral/basics-referral/referral-program/
		//logos http://themeforest.net/wiki/referral/basics-referral/banners-and-logos/
		//Themeforest
		'<a href="http://themeforest.net?ref=straps" target="_blank"><img border="0" src="http://envato.s3.amazonaws.com/referrer_adverts/tf_468x60_v2.gif" width="468" height="60"></a>',
		'<a href="http://themeforest.net?ref=straps" target="_blank"><img border="0" src="http://envato.s3.amazonaws.com/referrer_adverts/tf_468x60_v1.gif" width="468" height="60"></a>',
		//Videohive
		'<a href="http://videohive.net?ref=straps" target="_blank"><img border="0" src="http://envato.s3.amazonaws.com/referrer_adverts/vh_468x60_v4.gif" width="468" height="60"></a>',
		//Graphicriver
		'<a href="http://graphicriver.net?ref=straps" target="_blank"><img border="0" src="http://envato.s3.amazonaws.com/referrer_adverts/gr_468x60_v1.gif" width="468" height="60"></a>',
		//Activeden
		'<a href="http://activeden.net?ref=straps" target="_blank"><img border="0" src="http://envato.s3.amazonaws.com/referrer_adverts/ad_468x60_v4.gif" width="468" height="60"></a>',
		//Audiojungle
		'<a href="http://audiojungle.net?ref=straps" target="_blank"><img border="0" src="http://envato.s3.amazonaws.com/referrer_adverts/aj_468x60_v3.gif" width="468" height="60"></a>',
		//3docean
		'<a href="http://3docean.net?ref=straps" target="_blank"><img border="0" src="http://envato.s3.amazonaws.com/referrer_adverts/3d_468x60_v3.gif" width="468" height="60"></a>',
		//Codecanyon
		'<a href="http://codecanyon.net?ref=straps" target="_blank"><img border="0" src="http://envato.s3.amazonaws.com/referrer_adverts/cc_468x60_v3.gif" width="468" height="60"></a>',
		//Tutsplus
		//'<a href="http://tutsplus.com?ref=straps" target="_blank"><img border="0" src="http://envato.s3.amazonaws.com/referrer_adverts/tutorials_468x60_v1.gif" width="468" height="60"></a>',
		//Woothemes
		'<a href="http://www.woothemes.com/amember/go.php?r=38627&i=b43" target="_blank"><img src="http://woothemes.com/ads/468x60b.jpg" border=0 alt="WooThemes - Quality Themes, Great Support" width=468 height=60></a>',
		'<a href="http://www.woothemes.com/amember/go.php?r=38627&i=b44" target="_blank"><img src="http://woothemes.com/ads/468x60c.jpg" border=0 alt="WooThemes - WordPress themes for everyone" width=468 height=60></a>',
		'<a href="http://www.woothemes.com/amember/go.php?r=38627&i=b43" target="_blank"><img src="http://woothemes.com/ads/468x60b.jpg" border=0 alt="WooThemes - Quality Themes, Great Support" width=468 height=60></a>',
		'<a href="http://www.woothemes.com/amember/go.php?r=38627&i=b44" target="_blank"><img src="http://woothemes.com/ads/468x60c.jpg" border=0 alt="WooThemes - WordPress themes for everyone" width=468 height=60></a>',
		//Mojothemes
		'<a href="http://www.mojo-themes.com/?r=straps" target="_blank"><img src="http://www.mojo-themes.com/wp-content/uploads/2010/05/MOJO_THEMES_468_60_banner.jpg" border=0 alt="Mojo Themes" width=468 height=60></a>',
		'<a href="http://www.mojo-themes.com/?r=straps" target="_blank"><img src="http://www.mojo-themes.com/wp-content/uploads/2010/05/mojo-468x60.jpg" border=0 alt="Mojo Themes" width=468 height=60></a>'
	);
}
