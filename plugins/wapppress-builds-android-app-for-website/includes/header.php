<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<title>WappPress::Basic</title><meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<script type="text/javascript">
jQuery(document).ready(function($){
	/* prepend menu icon */
	jQuery('#nav-wrap').prepend('<div id="menu-icon">Menu</div>');
	/* toggle nav */
	jQuery("#menu-icon").on("click", function(){
		jQuery("#nav").slideToggle();
		jQuery(this).toggleClass("active");
	});
});
</script>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.tabs .tab-links a').on('click', function(e)  {
        var currentAttrValue = jQuery(this).attr('href');
        // Show/Hide Tabs
        jQuery('.tabs ' + currentAttrValue).show().siblings().hide();
        // Change/remove current tab to active
        jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
        e.preventDefault();
    });
});

jQuery(document).ready(function () {
	jQuery('#toggle-view span,#toggle-view h3').click(function () {
	//$('#toggle-view h3').click(function () {
		var text = jQuery(this).siblings('div.panel');
		if (text.is(':hidden')) {
			text.slideDown('200');
			jQuery(this).siblings('span').html('<img src="<?php echo  plugins_url( '../images/down_arrow.png',  __FILE__ ) ?>" alt="down-arrow"/> ');
		} else {
			text.slideUp('200');	
			jQuery(this).siblings('span').html('<img src="<?php echo plugins_url( '../images/arrow.png',  __FILE__ ) ?>" alt="up-arrow"/> ');			
		}
	});
});
</script>
</head>
<body>
<!------------------------------------------------------------------------------------->
<style>
.preview__header {
    font-size: 12px !important;
    height: 40px !important;
    background-color: #262626 !important;
    z-index: 100 !important;
    line-height: 54px !important;
    margin-bottom: 1px !important;
}
@media (max-width: 568px)
.preview__envato-logo {
    padding: 0 10px !important;
}
.preview__envato-logo {
    float: left !important;
    padding: 0 20px !important;
}
.preview__envato-logo a {
    display: inline-block !important;
    position: absolute !important;
    top: 10px !important;
    text-indent: -9999px !important;
    height: 18px !important;
    width: 152px !important;
    background: url(https://public-assets.envato-static.com/assets/logos/envato_market-a5ace93f8482e885ae008eb481b9451d379599dfed24868e52b6b2d66f5cf633.svg) !important;
    background-size: 152px 18px !important;
}
.preview__actions {
    float: right !important;
}
.preview__action--buy, .preview__action--close {
    display: inline-block !important;
    padding: 0 20px !important;
	padding-top:9px !important;
	
}
.e-btn--3d.-color-primary {
    -webkit-box-shadow: 0 2px 0 #6f9a37 !important;
    box-shadow: 0 2px 0 #6f9a37 !important;
    position: relative !important;
}
.e-btn--3d, .-color-primary.e-btn--outline {
    background-color: #82b440 !important;
	color: white !important;
	border-radius: 15px !important;
}
.e-btn.-size-s, .-size-s.e-btn--3d, .-size-s.e-btn--outline, .e-btn, .e-btn--3d, .e-btn--outline {
    font-size: 14px !important;
    padding: 5px 20px !important;
    line-height: 1.5 !important;
}
</style>
<div id="wpadminbar" class="nojq" style="z-index:999999">
		<!---a href="https://codecanyon.net/item/wapppress-builds-android-mobile-app-for-any-wordpress-website/10250300"><img src="<?php echo plugins_url( '../images/envantobar.png',  __FILE__ ) ?>" style="width: 100%; height: auto;" title="Buy it from envanto Market" alt="Buy it from envanto Market"/></a--->
		<div class="preview__header" data-view="ctaHeader" data-item-id="10250300">
  <div class="preview__envato-logo">
    <a class="header-envato_market" href="https://codecanyon.net/item/wapppress-builds-android-mobile-app-for-any-wordpress-website/10250300">Envato Market</a>
  </div>

  <div id="js-preview__actions" class="preview__actions">
  <div class="preview__action--buy">
    <a class="header-buy-now e-btn--3d -color-primary" href="https://codecanyon.net/item/wapppress-builds-android-mobile-app-for-any-wordpress-website/10250300">Buy now</a>
  </div>
</div>
</div>
</div>
<!------------------------------------------------------------------------------------->
<div class="header">
	<div class="wrapper">
		<div class="inner-header">
			<div class="logo">
				<a href="<?php echo admin_url('admin.php?page=wapppresssettings'); ?>"><img src="<?php echo plugins_url( '../images/logo.png',  __FILE__ ) ?>" title="" alt=""/></a>
			</div>
			
			<div class="right-header">
				<div class="navigation">
					<ul>
						<li <?php if(isset($_GET['page']) && $_GET['page']=='wapppresssettings'){ echo 'class="active"'; } ?>><a href="<?php echo admin_url('admin.php?page=wapppresssettings'); ?>" > Build Android App </a></li>
						<li <?php if(isset($_GET['page']) && $_GET['page']=='advancesettings'){ echo 'class="active"'; } ?>><a  href="<?php echo admin_url('admin.php?page=advancesettings'); ?>" >Advance Settings </a></li>						
						<li <?php if(isset($_GET['page']) && $_GET['page']=='wapppresspush'){ echo 'class="active"'; } ?>><a  href="<?php echo admin_url('admin.php?page=wapppresspush'); ?>">Push Notification </br><em><span class='font_cls'>(Message/Alert)</span></em></a></li>						<li <?php if(isset($_GET['page']) && $_GET['page']=='wapppressupgrade'){ echo 'class="active"'; } ?>><a  href="<?php echo admin_url('admin.php?page=wapppressupgrade'); ?>" >Upgrade </a></li>
						<!--li <?php if(isset($_GET['page']) && $_GET['page']=='wapppresstheme'){ echo 'class="active"'; } ?>><a  href="<?php echo admin_url('admin.php?page=wapppresstheme'); ?>">Themes </a></li-->
						<li><a  href="https://wapppresssupport.freshdesk.com" target="_blank">Help/Support </a></li>
					</ul>
				</div>
			</div>
			<div class="clear">
			</div>
			
		</div>
	</div>
	
</div>
<?php 
//
	function curl_site_url() {
		 $pageURL = 'http';
		 if (isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"];
		 }
		 $subDirURL='';
		 if(!empty($_SERVER['SCRIPT_NAME'])){
			 $subDirURL .= str_replace("wp-admin/admin.php","",$_SERVER['SCRIPT_NAME']);
		 }
		 return $pageURL.$subDirURL;
	}
	function get_domain_name($url)
	{
	  $pieces = parse_url($url);
	  $domain_n='';
	  $domain = isset($pieces['host']) ? $pieces['host'] : '';
	  if(preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,10})$/i', $domain, $regs)) {
		return $regs['domain'];
	  }
	  return false;
	}
	function get_app_url($request_type='complile')
	{
		$compile_id = COMPILE_ID;
							
		$pageURL = curl_site_url();
			
		$dirIncImg  = trailingslashit(plugins_url('wapppress-builds-android-app-for-website'));						
		
		$domain_name = get_domain_name($pageURL); 
		$auth = urlencode(base64_encode($domain_name.'~wapppress~'.$pageURL.'~wapppress~'.time()));
		$compile_connector = '/api';
		if($request_type=='complile'){$compile_params    = '/create-api.php?auth_key=';}else{ $compile_params    = '/create-api-push.php?auth_key=';}
		return $compile_id.$compile_connector.$compile_params.$auth;
	}
	//
?>