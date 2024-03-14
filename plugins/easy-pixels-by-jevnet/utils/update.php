<?php
/*
switch ($easyPixels->getVersion()) {
	case false: update_to_v200();break;
	case '':
		# code...
		break;
	default:
		# code...
		break;
}
*/

function update_to_v200()
{
	$easyPixels=new jn_easypixels();
	include(JN_EasyPixels_PATH."/utils/classes_v1_90.php");

	$analyticsOld=new jn_Analytics_v1_90();
	$easyPixels->trackingOptions->analytics->setCode($analyticsOld->getCode());
	$easyPixels->trackingOptions->analytics->enable($analyticsOld->is_enabled());

	$bingOld=new jn_easyBingAds_v1_90();
	$easyPixels->trackingOptions->bing->setCode($bingOld->getCode());
	$easyPixels->trackingOptions->bing->enable($bingOld->is_enabled());

	$facebookOld=new jn_Facebook_v1_90();
	$easyPixels->trackingOptions->facebook->setCode($facebookOld->getCode());
	$easyPixels->trackingOptions->facebook->enable($facebookOld->is_enabled());

	$gadsOld=new jn_easyGAds_v1_90();
	$easyPixels->trackingOptions->gads->setCode($gadsOld->getCode());
	$easyPixels->trackingOptions->gads->enable($gadsOld->is_enabled());
	$easyPixels->trackingOptions->gads->setPhoneNumber($gadsOld->getPhoneNumber());
	$easyPixels->trackingOptions->gads->setGFC_conversionLabel($gadsOld->getGFC_conversionLabel());
	$easyPixels->trackingOptions->gads->setRemarketing($gadsOld->getRemarketing());
	$easyPixels->trackingOptions->gads->setLabel($gadsOld->getWCLabel());

	$gtmOld=new jn_easyGTagManager_v1_90();
	$easyPixels->trackingOptions->gtm->setCode($gtmOld->getCode());
	$easyPixels->trackingOptions->gtm->enable($gtmOld->is_enabled());

	$linkedinOld=new jn_easypixels_LinkedIn_v1_90();
	$easyPixels->trackingOptions->linkedin->setCode($linkedinOld->getCode());
	$easyPixels->trackingOptions->linkedin->enable($linkedinOld->is_enabled());

	$twitterOld=new jn_easypixels_Twitter_v1_90();
	$easyPixels->trackingOptions->twitter->setCode($twitterOld->getCode());
	$easyPixels->trackingOptions->twitter->enable($twitterOld->is_enabled());

	$yandexOld=new jn_easypixels_Yandex_v1_90();
	$easyPixels->trackingOptions->yandex->setCode($yandexOld->getCode());
	$easyPixels->trackingOptions->yandex->enable($yandexOld->is_enabled());
	if (function_exists('icl_get_languages')) {	//get list of used languages from WPML
		$langs = icl_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str'); //Set current language for language based variables in theme.
		foreach ($langs as $language) {			
			if(isset($language['language_code']))
			{
				$easyPixels->save('jnEasyPixelsSettings-group',true,$language['language_code']);
			}
			else
			{
				$easyPixels->save('jnEasyPixelsSettings-group',true);
			}
		}
	}
	else
	{
		$easyPixels->save('jnEasyPixelsSettings-group',true);
	}
}
?>