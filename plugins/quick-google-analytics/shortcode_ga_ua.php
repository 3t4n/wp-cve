<?php

add_action('wp_head', 'addtohead_quick_google_analytics');
function addtohead_quick_google_analytics(){
	
	$quickgoogleanalytics_ua_code = get_option('quickgoogleanalytics_ua');
	$quickgoogleanalytics_ip = get_option('quickgoogleanalytics_ip');

ECHO "
<!-- Global site tag (gtag.js) - Google Analytics by wordpress plugin quick google analytics -->
<script async src='https://www.googletagmanager.com/gtag/js?id=$quickgoogleanalytics_ua_code'></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
";
  //gtag('config', '$quickgoogleanalytics_ua_code');
  

if ($quickgoogleanalytics_ip == 'an'){
	ECHO "gtag('config', '$quickgoogleanalytics_ua_code', {'anonymize_ip': true});";
}
	else
	{
		ECHO "gtag('config', '$quickgoogleanalytics_ua_code');";
	}


ECHO " 
</script>
<!-- END Global site tag (gtag.js) - Google Analytics by wordpress plugin quick google analytics -->
";

		
};

?>