<?php

add_action('wp_head', 'addtohead_quick_google_analytics_g');
function addtohead_quick_google_analytics_g(){
	
	$quickgoogleanalytics_g_code = get_option('quickgoogleanalytics_g');
	$quickgoogleanalytics_ip = get_option('quickgoogleanalytics_ip');

ECHO "
<!-- Global site tag (gtag.js) - Google Analytics 4 Code by wordpress plugin quick google analytics -->
<script async src='https://www.googletagmanager.com/gtag/js?id=$quickgoogleanalytics_g_code'></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
";
  //gtag('config', '$quickgoogleanalytics_ua_code');
  

if ($quickgoogleanalytics_ip == 'an'){
	ECHO "gtag('config', '$quickgoogleanalytics_g_code', {'anonymize_ip': true});";
}


else
	{
		ECHO "gtag('config', '$quickgoogleanalytics_g_code');";
	}


ECHO " 
</script>
<!-- END Global site tag (gtag.js) - Google Analytics 4 by wordpress plugin quick google analytics -->
";
	
	
};

?>