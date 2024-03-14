<?php
if (!defined('ABSPATH'))
{
	exit;
}

function activityRssRestrictFuncFree()
{

	$bpenableaallbprssrestricts = get_option ( 'bpenableaallbprssrestricts' );
	
	if (strtolower ( $bpenableaallbprssrestricts ) == 'yes') 
	{
		return false;
	}
	else
	{
		return true;		
	}
}

add_filter( 'bp_activity_enable_feeds',  'activityRssRestrictFuncFree', 10, 1 );