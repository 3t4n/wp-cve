<?php
//stand alone AJAX processing...
require_once dirname(__DIR__)."/../../wp-load.php";
?>
<html><head><title>Cab Grid Pro Compatibilty Tests</title>
	<style>
		body {padding:2% 10% 0;font-family:Arial,Helvetica;text-align:center;color:#444;text-shadow:1px 1px 0px rgba(255,255,255,0.99);background: #fafafa;
background: -moz-radial-gradient(center, ellipse cover, #fafafa 40%, #d8d8dc 95%, #b5b6b7 100%);
background: -webkit-radial-gradient(center, ellipse cover, #fafafa 40%,#d8d8dc 95%,#b5b6b7 100%);
background: radial-gradient(ellipse at center, #fafafa 40%,#d8d8dc 95%,#b5b6b7 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fafafa', endColorstr='#b5b6b7',GradientType=1 );}
		h1 {margin-bottom: 0;}
		h2 {margin: 3.9% 0 4px;text-transform: uppercase;}
		a,a:link,a:visited {color:#48aaff;text-decoration:none;}
		a:hover {text-decoration:underline;color:#0e87f1;}
		.small {font-size:80%;}
		.fail {font-weight:bold;color:red;font-size:103%;display: block;margin: 0;}
	</style>
</head>
<body>
	<h1><?php _e("Cab Grid Pro Compatibility Tests","cab-grid"); ?></h1>
	<p><?php _e("Test results are shown below","cab-grid"); ?></p>


<?php
$pass=1;
//text cURL
$cabGridUA="";
if(isset($_GET['cabGridUA'])){
	$cabGridUA=$_GET['cabGridUA'];
}
$cabGridProURI="";	//"http://cabgrid.com/tests/";
if(isset($_GET['cabGridProURI'])){
	$cabGridProURI=$_GET['cabGridProURI'];
}
	echo "<h2>".__("Test Basic cURL","cab-grid")."</h2>";
	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://google.com");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		if($cabGridUA!=""){
			curl_setopt($ch, CURLOPT_USERAGENT,"Cab Grid License Bot $cabGridUA");
		}
		$data = curl_exec($ch);
		$errno = curl_errno($ch);
		if($errno) {
			$error_message = curl_strerror($errno);
			echo "<span class='fail'>";
			echo "<br />cURL error ({$errno}): {$error_message}";
			echo "</span>";
			$pass=0;
		} else {
			echo "<br />cURL basic test passed.";
		}

$url="https://cabgrid.com/curltest.php";
	echo "<h2>".__("Test Cab Grid cURL","cab-grid").": $url</h2>";
	$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
			curl_setopt($ch, CURLOPT_TIMEOUT, 80);
			if($cabGridUA!=""){
				curl_setopt($ch, CURLOPT_USERAGENT,"Cab Grid License Bot $cabGridUA");
			}
			if($cabGridProURI!=""){
				curl_setopt($ch, CURLOPT_REFERER, $cabGridProURI);
			}
			$cabGridCurlResponse=curl_exec($ch);
	//print($cabGridCurlResponse);

	$errno = curl_errno($ch);
	if($errno) {
		$error_message = curl_strerror($errno);
		echo "<span class='fail'>";
		echo "<br />cURL error ({$errno}): {$error_message}";
		echo "</span>";
		$pass=0;
	} else {
		echo "<br />cURL test passed. Your server meets this requirement.";
	}
	
	//PHP version test
	echo "<h2>".__("PHP Version Test","cab-grid")."</h2>";
	echo "<!-- ".PHP_OS." -->";
	if (version_compare(PHP_VERSION, '5.4', '<')) {
		$pass=0;
		echo "<span class='fail'>";
		echo "<br />".sprintf(__( 'Cab Grid Pro requires PHP version 5.4 or greater to function properly. You are using version %s on your server. You would need to update to avoid problems.', 'cab-grid' ),$cabGridPHPv);
		echo "</span>";
	} else {
		echo "<br />".sprintf(__( 'PHP version %s is adequate for Cab Grid Pro', 'cab-grid' ),$cabGridPHPv);
	}
	
	//localhost
	if(in_array( $_SERVER['HTTP_HOST'], array( 'localhost', '127.0.0.1' ) )){
		$pass=0;
		echo "<span class='fail'>";
		echo "<br />".sprintf(__( 'You appear to be running this server locally. Cab Grid Pro may behave unexpectedly when hosted locally.', 'cab-grid' ),$cabGridPHPv);
		echo "</span>";
	} else {
		echo "<!-- HTTP_HOST: ".$_SERVER['HTTP_HOST']." -->";
	}
	
	//MAIL test
	echo "<h2>".__("Mail Test","cab-grid")."</h2>";
	$cabGridProSite=$_SERVER['SERVER_NAME'];
	$cabGridTestMessage="Cab Grid on $cabGridProSite is testing compatibility for Cab Grid Pro. cURL returned $cabGridCurlResponse";$m=base64_decode("bWFpbC50ZXN0QGNhYmdyaWQuY29t");
	if(wp_mail( $m, 'CabGrid Compatibility Test', $cabGridTestMessage )){
		echo "<br />".__("Your server seems to support the WP_mail function.","cab-grid");
	} else {
		$pass=0;
		echo "<span class='fail'>";
		echo "<br />".__("Your server DOES NOT seem to support the WP_mail function. You will need to insure this function is available to receive booking request emails from Cab Grid Pro","cab-grid");
		echo "</span>";
	}
	
	//WP version test
	echo "<h2>".__("Wordpress Version","cab-grid")."</h2>";
	$cabGridWP=explode(".",get_bloginfo('version'))[0];
	echo "".sprintf(__("Cab Grid Pro requires Wordpress version 4.0 or higher. You have version %s installed.","cab-grid"),get_bloginfo('version'));
	if($cabGridWP<4){
		$pass=0;
		echo "<span class='fail'>";
		echo "<br />".__("The installed version of Wordpress appears to be too old to support Cab Grid Pro. Please upgrade Wordpress.","cab-grid");
		echo "</span>";
	}
	if(is_multisite()){
		$pass=0;
		echo "<span class='fail'>";
		echo "<br />".__("This appears to be a multisite installation of Wordpress. Multisite is not officially supported and might cause unexpected results.","cab-grid");
		echo "</span>";
	}
	
	//Cache/Plugin Conflicts. See https://cabgrid.com/help-and-support/troubleshooting/wordpress-plugin-not-working/#ki
	$disallowedPlugins=array("autoptimize","cache","zoho","Prevent_Content_Copy_and_Image_Save","wp-minify-fix","team-vcard-generator");
	if(!function_exists("is_plugin_active")){
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
	$installedPlugins = get_plugins();
	echo "<h2>".sprintf(__("Testing %s Installed Plugins","cab-grid"),count($installedPlugins))."</h2>";
	$badActivePlugins=array();
	$cacheCount=0;
	$badCount=0;
	foreach($installedPlugins as $pluginFolder => $pluginData){
		foreach($disallowedPlugins as $disallowedPlugin){
			if(stripos($pluginFolder,$disallowedPlugin)!==false){
				if(is_plugin_active($pluginFolder)){
					array_push($badActivePlugins, $pluginData["Name"]);
					if($disallowedPlugin=="cache" || $disallowedPlugin=="autoptimize"){
						$cacheCount=$cacheCount+1;
					} else {
						$badCount=$badCount+1;
					}
					if($disallowedPlugin=="autoptimize"){
						$badCount=$badCount+1;
					}
				}
			} 
		}
	}
	if(count($badActivePlugins)>0){
		$pass=0;
		$badActivePluginsStr=implode(" & ",$badActivePlugins);
		echo "<span class='fail'>";
		echo "<br />".sprintf(__('You have %1$s active plugins that may cause issues, including %2$s',"cab-grid"),count($badActivePlugins),$badActivePluginsStr);
		if($cacheCount>0){
			echo "<br /><a href='https://cabgrid.com/?p=4625' target='_blank'>".__("You should exclude Cab Grid from any cache/optimisation plugins.","cab-grid")."</a>";
		}
		if($badCount>0){
			echo "<br /><a href='https://cabgrid.com/help-and-support/troubleshooting/wordpress-plugin-not-working/#ki' target='_blank'>".__("More information on known incompatibilities is available on our troubleshooting page.","cab-grid")."</a>";
		}
		echo "</span>";
	} else {
		echo "<br />".__("None of your active plugins are known to conflict with Cab Grid Pro.","cab-grid");
		//It looks like you do not have any suspect plugins active
	}
	
	echo "<h2>".__("Conclusion","cab-grid")."</h2>";
	if($pass==0){
		echo "<span class='fail'>";
		echo "<br />".__("Your web server hosting environment has failed one or more tests. We recommend you rectify any incompatibilities before upgrading.","cab-grid");
		echo "</span>";
	} else {
		echo "<br />".__("Your web server hosting environment has passed all tests. We are confident Cab Grid Pro will function properly.","cab-grid");
	}
?>
</body>
</html>