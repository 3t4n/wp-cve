<?php
defined( 'ABSPATH' ) || exit;
/**
 * Admin enqueue
 *
 * @package YAHMAN Add-ons
 */



function yahman_addons_admin_page_scripts(){
	wp_enqueue_style('yahman_addons_admin',YAHMAN_ADDONS_URI . 'assets/css/admin.min.css',array());
	wp_enqueue_style('font-awesome4-mini', YAHMAN_ADDONS_URI . 'assets/fonts/fontawesome/style.min.css', array(), null);
	wp_enqueue_media();
	wp_enqueue_script('yahman_addons_media_uploader',YAHMAN_ADDONS_URI . 'assets/js/customizer/media-uploader.min.js', array('jquery'), null );
	wp_enqueue_style( 'wp-color-picker');
	wp_enqueue_script( 'wp-color-picker');
	wp_enqueue_script('yahman_addons-color-picker-admin',YAHMAN_ADDONS_URI . 'assets/js/customizer/color-picker-admin.min.js', array('wp-color-picker'), null , true );
	wp_enqueue_script('yahman_addons_admin_scripts',YAHMAN_ADDONS_URI . 'assets/js/admin.min.js', array('jquery'), null );

	wp_register_script('wp-color-picker-alpha',YAHMAN_ADDONS_URI . 'assets/js/customizer/wp-color-picker-alpha.min.js', array('wp-color-picker'), null , true );
	wp_add_inline_script(
		'wp-color-picker-alpha',
		'jQuery( function() { jQuery( ".color-picker" ).wpColorPicker(); } );'
	);
	wp_enqueue_script( 'wp-color-picker-alpha' );
	//wp_enqueue_script( 'wp-color-picker-alpha_widget', YAHMAN_ADDONS_URI . 'assets/js/customizer/color_alpha_widget.min.js', array( 'wp-color-picker-alpha' ), null, true );
}


function yahman_addons_enqueue_style_base(){

	wp_enqueue_style('yahman_addons_base',YAHMAN_ADDONS_URI . 'assets/css/base.min.css' );

}

function yahman_addons_enqueue_style_toc(){
	wp_enqueue_style('yahman_addons_toc',YAHMAN_ADDONS_URI . 'assets/css/toc.min.css' );
}

function yahman_addons_enqueue_style_post_list(){
	wp_enqueue_style('yahman_addons_post_list',YAHMAN_ADDONS_URI . 'assets/css/post_list.min.css' );
}

function yahman_addons_enqueue_style_cta(){
	wp_enqueue_style('yahman_addons_cta',YAHMAN_ADDONS_URI . 'assets/css/cta.min.css' );
}



function yahman_addons_enqueue_style_social(){
	wp_enqueue_style('yahman_addons_social',YAHMAN_ADDONS_URI . 'assets/css/sns.min.css' );
}

function yahman_addons_enqueue_style_profile(){
	wp_enqueue_style('yahman_addons_profile',YAHMAN_ADDONS_URI . 'assets/css/profile.min.css' );
}

function yahman_addons_enqueue_style_blog_card(){
	wp_enqueue_style('yahman_addons_blog_card',YAHMAN_ADDONS_URI . 'assets/css/blog_card.min.css' );
}
function yahman_addons_enqueue_style_notice(){
	wp_enqueue_style('yahman_addons_notice',YAHMAN_ADDONS_URI . 'assets/css/notice.min.css' );

	wp_enqueue_style('font-awesome4-mini', YAHMAN_ADDONS_URI . 'assets/fonts/fontawesome/style.min.css', array(), null);
}

function yahman_addons_enqueue_style_dd(){
	wp_enqueue_style('yahman_addons_dd',YAHMAN_ADDONS_URI . 'assets/css/dd.min.css' );
}

function yahman_addons_enqueue_style_cse(){
	wp_enqueue_style('yahman_addons_cse',YAHMAN_ADDONS_URI . 'assets/css/cse.min.css' );
}

function yahman_addons_highlight_load() {
	$option = get_option('yahman_addons') ;
	$highlight_style = isset($option['javascript']['highlight_style']) ? $option['javascript']['highlight_style'] : 'default';
	wp_enqueue_style('highlight_style', YAHMAN_ADDONS_URI . 'assets/js/highlight/styles/'.$highlight_style.'.min.css' );
	wp_enqueue_script('highlight_script',YAHMAN_ADDONS_URI . 'assets/js/highlight/highlight.min.js', array(), null );
	wp_add_inline_script( 'highlight_script', 'hljs.highlightAll();');

}

function yahman_addons_lightbox_lity() {
	wp_enqueue_script( 'lightbox_lity', YAHMAN_ADDONS_URI . 'assets/js/lity/lity.min.js', array( 'jquery' ), null , true );
	wp_enqueue_style('lightbox_lity', YAHMAN_ADDONS_URI . 'assets/js/lity/lity.min.css' );
}

function yahman_addons_lightbox_luminous() {
	wp_enqueue_script( 'lightbox_luminous', YAHMAN_ADDONS_URI . 'assets/js/luminous/Luminous.min.js', array(), null , true );
	wp_add_inline_script( 'lightbox_luminous', 'var luminousTrigger = document.querySelectorAll(".luminous");for (var i = 0; i < luminousTrigger.length; i++) {var luminousElem = luminousTrigger[i];new Luminous(luminousElem);}'
);
	wp_enqueue_style('lightbox_luminous', YAHMAN_ADDONS_URI . 'assets/js/luminous/luminous-basic.min.css' );
	wp_add_inline_style( 'lightbox_luminous', '.lum-lightbox{z-index:100;}');

}

function yahman_addons_lazy_lozad() {

	require_once ABSPATH . 'wp-admin/includes/file.php';
	WP_Filesystem();
	global $wp_filesystem;

	
	$ua = '';
	if( isset($_SERVER['HTTP_USER_AGENT']) )
		$ua = $_SERVER['HTTP_USER_AGENT'];

	if (strstr($ua, 'Trident') || strstr($ua, 'MSIE')) {

		wp_enqueue_script( 'polyfill_es6', 'https://polyfill.io/v3/polyfill.min.js?features=es6', array(), null , true );
		//wp_enqueue_script( 'promisejs', 'https://www.promisejs.org/polyfills/promise-7.0.4.min.js', array(), null , true );
	}

	wp_enqueue_script( 'polyfill_IntersectionObserver', 'https://polyfill.io/v3/polyfill.min.js?features=IntersectionObserver', array(), null , true );

	if(!YAHMAN_ADDONS_TEMPLATE){
		wp_enqueue_style('lazy_lozad', YAHMAN_ADDONS_URI . 'assets/css/lozad.min.css' );
	}


	wp_register_script( 'lazy_lozad', '', array('polyfill_IntersectionObserver'), null , true );
	wp_enqueue_script( 'lazy_lozad');
	wp_add_inline_script( 'lazy_lozad', $wp_filesystem->get_contents( YAHMAN_ADDONS_DIR . 'assets/js/lozad/lozad.min.js' ).'const observer = lozad(".ya_lozad",{rootMargin:"10px 0px",threshold:0.1,loaded:function(el){el.classList.add("is_loaded");}});observer.observe();');
}

function yahman_addons_user_timing_api(){
	?>
	<script type="text/javascript">window.performance.mark('mark_fully_loaded');</script>
	<?php
}

function yahman_addons_uploaded_to_this_post(){
	?>
	<script type="text/javascript">jQuery(function( $ ){
		wp.media.view.Modal.prototype.on( 'open', function( ){ $( 'select.attachment-filters' ).find( '[value="uploaded"]').attr( 'selected', true ).parent().trigger('change'); });
	});
</script>
<?php
}


if(!YAHMAN_ADDONS_TEMPLATE){
	add_action( 'wp_footer', 'yahman_addons_enqueue_style_base');
}

function yahman_addons_replace_scripts_type($tag) {

	if(strpos($tag,'jquery.js') === false && strpos($tag,'features=es6') === false ){
		
		return str_replace( ' src', ' async src', $tag );
	}

	return $tag;

}
//add_filter( 'script_loader_tag','yahman_addons_replace_scripts_type',9999999999);


function yahman_addons_google_adsense_script() {

	wp_register_script( 'google-adsense-js', '', array(), null, true );

	//wp_enqueue_script( 'google-adsense-js'  );
	//wp_add_inline_script( 'google-adsense-js', '(function(e,d){function a(){var f=e.createElement("script");f.type="text/javascript";f.async=true;f.src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js";var g=e.getElementsByTagName("script")[0];g.parentNode.insertBefore(f,g);}var c=false;function b(){if(c===false){c=true;d.removeEventListener("scroll",b);d.removeEventListener("mousemove",b);d.removeEventListener("mousedown",b);d.removeEventListener("touchstart",b);a();}}d.addEventListener("scroll",b);d.addEventListener("mousemove",b);d.addEventListener("mousedown",b);d.addEventListener("touchstart",b);d.addEventListener("load",function(){if(e.documentElement.scrollTop!=0||e.body.scrollTop!=0){b();}});})(document,window);' );

}

function yahman_addons_twitter_widgets_script() {

	wp_register_script( 'twitter-widgets', '', array(), null, true );

	//wp_enqueue_script( 'twitter-widgets'  );
	//wp_add_inline_script( 'twitter-widgets', '(function(e,d){function a(){var f=e.createElement("script");f.type="text/javascript";f.async=true;f.src="//platform.twitter.com/widgets.js";var g=e.getElementsByTagName("script")[0];g.parentNode.insertBefore(f,g);}var c=false;function b(){if(c===false){c=true;d.removeEventListener("scroll",b);d.removeEventListener("mousemove",b);d.removeEventListener("mousedown",b);d.removeEventListener("touchstart",b);a();}}d.addEventListener("scroll",b);d.addEventListener("mousemove",b);d.addEventListener("mousedown",b);d.addEventListener("touchstart",b);d.addEventListener("load",function(){if(e.documentElement.scrollTop!=0||e.body.scrollTop!=0){b();}});})(document,window);' );

}
//add_filter('script_loader_tag', 'yahman_addons_add_async', 10, 2);

function yahman_addons_add_async($tag, $handle) {
	if($handle !== 'polyfill_IntersectionObserver' && $handle !== 'lightbox_luminous') {
		return $tag;
	}

	return str_replace(' src=', ' async="async" src=', $tag);
}

//add_filter('script_loader_tag', 'yahman_addons_add_defer', 10, 2);

function yahman_addons_add_defer($tag, $handle) {
	if($handle !== 'fontawesome5') {
		return $tag;
	}

	return str_replace(' src=', ' defer src=', $tag);
}

function yahman_addons_lazy_javascript(){
	//google-adsense-js
	//twitter-widgets
	$script = '';
	if(isset( $GLOBALS['wp_scripts']->registered[ 'googletagmanager-js' ] )){
		$script .= '{src:"https://www.googletagmanager.com/gtag/js?id='. esc_attr(YA_GA_GTAG) .'",async:true,defer:false,nonce:false},';
	}
	if(isset( $GLOBALS['wp_scripts']->registered[ 'google-adsense-js' ] )){
		$script .= '{src:"https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js",async:true,defer:false,nonce:false},';
	}
	if(isset( $GLOBALS['wp_scripts']->registered[ 'yahman_twitter-widgets' ] )){
		$script .= '{src:"//platform.twitter.com/widgets.js",async:true,defer:false,nonce:false},';
	}
	if(isset( $GLOBALS['wp_scripts']->registered[ 'yahman_addons_facebook_script' ] )){
		$option = get_option('yahman_addons');
		$script .= '{src:"https://connect.facebook.net/'.yahman_addons_facebook_lang(get_locale()).'/sdk.js#xfbml=1&version=v10.0&appId='.(isset($option['sns_account']['facebook_app_id']) ? $option['sns_account']['facebook_app_id'] : '').'&autoLogAppEvents=1",async:true,defer:true,nonce:"'.yahman_addons_rand_nonce(8).'"},';
	}
	if( $script === '') return;

	$script = substr($script, 0, -1);

	echo '<script>!function(window,document){function a(){var array=['.$script.'];for(var i=0;i<array.length;i++){var s=document.createElement("script"),p=document.getElementsByTagName("script")[0];s.type="text/javascript",array[i].async&&(s.async=array[i].async),array[i].defer&&(s.defer=array[i].defer),array[i].nonce&&(s.nonce=array[i].nonce),s.src=array[i].src,p.parentNode.insertBefore(s,p)}}var lazyLoad=!1;function b(){!1===lazyLoad&&(lazyLoad=!0,window.removeEventListener("scroll",b),window.removeEventListener("mousemove",b),window.removeEventListener("mousedown",b),window.removeEventListener("touchstart",b),window.removeEventListener("keydown",b),a())}window.addEventListener("scroll",b),window.addEventListener("mousemove",b),window.addEventListener("mousedown",b),window.addEventListener("touchstart",b),window.addEventListener("keydown",b),window.addEventListener("load",(function(){window.pageYOffset&&b(),window.setTimeout(b,3000)}))}(window,document);</script>';
}

add_action( 'wp_footer', 'yahman_addons_lazy_javascript');