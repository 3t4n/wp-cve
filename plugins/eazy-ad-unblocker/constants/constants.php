<?php

@session_start();

if(!isset($_SESSION["eazy_ad_rand_words"]))
{ 
	$eazy_ad_words_path = plugins_url('file/words.txt', dirname(__FILE__));
	
	$eazy_ad_fileWords = @file_get_contents($eazy_ad_words_path, FILE_USE_INCLUDE_PATH);
	
	if($eazy_ad_fileWords === false) //if file not read for some reason
	{
		$eazy_ad_wordsArray = array("word1","word2","word3","word4","word5","word6","word7","word8");
		
		$_SESSION["eazy_ad_rand_words"] = $eazy_ad_wordsArray;
	}
	else{
		
		$eazy_ad_wordsArray = explode("\n", $eazy_ad_fileWords);
		
		$eazy_ad_randWordsKeys = array_rand($eazy_ad_wordsArray, 8);
		
		$eazy_ad_randWordElems = array();
		
		foreach($eazy_ad_randWordsKeys as $key)
		{
			$eazy_ad_randWordElems[] = $eazy_ad_wordsArray[$key];
		}
		
		$_SESSION["eazy_ad_rand_words"] = $eazy_ad_randWordElems;
	
	}

}



$prefix_array = array("a","b","e", "u");

shuffle($prefix_array);

$eazy_ad_unblocker_str1 = implode("", $prefix_array)."_";

$eazy_ad_unblocker_holder_id = '';

if(isset($_SESSION["eazy_ad_rand_words"]))
{
	$eazy_ad_unblocker_holder_id = $_SESSION["eazy_ad_rand_words"][0].'-'.md5(microtime());
}
else
{
	$eazy_ad_unblocker_holder_id = $eazy_ad_unblocker_str1.md5(microtime());
}

if(!isset($_SESSION['EAZY_AD_UNBLOCKER_HOLDER_ID']))
{
	//
	$_SESSION['EAZY_AD_UNBLOCKER_HOLDER_ID'] =  $eazy_ad_unblocker_holder_id;
}

shuffle($prefix_array);

$eazy_ad_unblocker_holder_class_name = '';

$eazy_ad_unblocker_str2 = implode("", $prefix_array)."_";

if(isset($_SESSION["eazy_ad_rand_words"]))
{
	$eazy_ad_unblocker_holder_class_name = $_SESSION["eazy_ad_rand_words"][1].'-'.md5(microtime());
}
else
{
	$eazy_ad_unblocker_holder_class_name = $eazy_ad_unblocker_str2.md5(microtime());
}

if(!isset($_SESSION['EAZY_AD_UNBLOCKER_HOLDER_CLASS_NAME']))
{
	
	$_SESSION['EAZY_AD_UNBLOCKER_HOLDER_CLASS_NAME'] =  $eazy_ad_unblocker_holder_class_name;
}

shuffle($prefix_array);

$eazy_ad_unblocker_dialog_overlay_id = '';

$eazy_ad_unblocker_str3 = implode("", $prefix_array)."_";

if(isset($_SESSION["eazy_ad_rand_words"]))
{

	$eazy_ad_unblocker_dialog_overlay_id = $_SESSION["eazy_ad_rand_words"][2].'-'.md5(microtime());

}
else{
	$eazy_ad_unblocker_dialog_overlay_id = $eazy_ad_unblocker_str3.md5(microtime());
}
if(!isset($_SESSION['EAZY_AD_UNBLOCKER_DIALOG_OVERLAY_ID']))
{
	//
	$_SESSION['EAZY_AD_UNBLOCKER_DIALOG_OVERLAY_ID'] =  $eazy_ad_unblocker_dialog_overlay_id;
}

shuffle($prefix_array);

$eazy_ad_unblocker_dialog_parent_id = '';

$eazy_ad_unblocker_str4 = implode("", $prefix_array)."_";

if(isset($_SESSION["eazy_ad_rand_words"]))
{
	$eazy_ad_unblocker_dialog_parent_id = $_SESSION["eazy_ad_rand_words"][3].'-'.md5(microtime());
}
else
{
	$eazy_ad_unblocker_dialog_parent_id = $eazy_ad_unblocker_str4.md5(microtime());
}


if(!isset($_SESSION['EAZY_AD_UNBLOCKER_DIALOG_PARENT_ID']))
{	
	$_SESSION['EAZY_AD_UNBLOCKER_DIALOG_PARENT_ID'] =  $eazy_ad_unblocker_dialog_parent_id;
}

shuffle($prefix_array);

$eazy_ad_unblocker_dialog_message_id = '';

$eazy_ad_unblocker_str5 = implode("", $prefix_array)."_";

if(isset($_SESSION["eazy_ad_rand_words"]))
{
	$eazy_ad_unblocker_dialog_message_id = $_SESSION["eazy_ad_rand_words"][4].'-'.md5(microtime());
}
else{
	$eazy_ad_unblocker_dialog_message_id = $eazy_ad_unblocker_str5.md5(microtime());
}



if(!isset($_SESSION['EAZY_AD_UNBLOCKER_DIALOG_MESSAGE_ID']))
{
	
	$_SESSION['EAZY_AD_UNBLOCKER_DIALOG_MESSAGE_ID'] =  $eazy_ad_unblocker_dialog_message_id;
	
}

shuffle($prefix_array);

$eazy_ad_unblocker_refresh_btn_class = '';


$eazy_ad_unblocker_str6 = implode("", $prefix_array)."_";

if(isset($_SESSION["eazy_ad_rand_words"]))
{
	$eazy_ad_unblocker_refresh_btn_class = $_SESSION["eazy_ad_rand_words"][5].'-'.md5(microtime());
}
else{
	$eazy_ad_unblocker_refresh_btn_class = $eazy_ad_unblocker_str6.md5(microtime());
}



if(!isset($_SESSION['EAZY_AD_UNBLOCKER_REFRESH_BTN_CLASS']))
{
	
	$_SESSION['EAZY_AD_UNBLOCKER_REFRESH_BTN_CLASS'] =  $eazy_ad_unblocker_refresh_btn_class;
	
}

//April 10 2021

session_write_close();

//End April 10 2021

$eazy_ad_unblocker_popup_params_array = array("eazy_ad_unblocker_holder"=> $_SESSION['EAZY_AD_UNBLOCKER_HOLDER_ID'],
	"eazy_ad_unblocker_holder_class_name" => $_SESSION['EAZY_AD_UNBLOCKER_HOLDER_CLASS_NAME'],
	"eazy_ad_unblocker_dialog_overlay"=> $_SESSION['EAZY_AD_UNBLOCKER_DIALOG_OVERLAY_ID'],
	"eazy_ad_unblocker_dialog_parent" => $_SESSION['EAZY_AD_UNBLOCKER_DIALOG_PARENT_ID'],
	"eazy_ad_unblocker_dialog_message" => $_SESSION['EAZY_AD_UNBLOCKER_DIALOG_MESSAGE_ID'],
	"eazy_ad_unblocker_refresh_btn_class" => $_SESSION['EAZY_AD_UNBLOCKER_REFRESH_BTN_CLASS']);