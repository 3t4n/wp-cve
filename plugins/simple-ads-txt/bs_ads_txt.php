<?php
/*
    Plugin Name: Simple Ads.txt  
	Author:      Bensword
	Description: Generates an ads.txt with your ad partners specification. Provides a nice and easy place for adding the needed text.
    Version:     1.0
    Licence:     GPLv3
    Text Domain: bs_ads_txt
*/


if (!class_exists("BS_Ads_txt")) {
	include('bs_ads_txt.class.php');
}
$BS_Ads_txt = new BS_Ads_txt();   
