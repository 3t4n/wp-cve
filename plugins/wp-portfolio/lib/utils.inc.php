<?php

/**
 * Get the details for the specified group ID.
 * @param $groupid The ID of the group to get the details for.
 * @return Array An array of the group details.
 */
function WPPortfolio_getGroupDetails($groupid)
{
	global $wpdb;
	$table_name = $wpdb->prefix . TABLE_WEBSITE_GROUPS;

	$SQL = $wpdb->prepare("
			SELECT *
			FROM $table_name 
			WHERE groupid = %d
			LIMIT 1
		", $groupid);
	return $wpdb->get_row($SQL, ARRAY_A);
}


/**
 * Get a list of the groups used in the portfolio.
 * @return Array A list of the groups in the portfolio.
 */
function WPPortfolio_getList_groups()
{
	    global $wpdb;
	    $groups_table = $wpdb->prefix . TABLE_WEBSITE_GROUPS;
	    
	    
        $SQL = "SELECT * FROM $groups_table
	 			ORDER BY groupname";	
	
		$wpdb->show_errors();
		$groups = $wpdb->get_results($SQL, OBJECT);
		return $groups;
}


/**
 * Determine if the specified key is valid, i.e. containing only letters and numbers.
 * @param $key The key to check
 * @return Boolean True if the key is valid, false otherwise.
 */
function WPPortfolio_isValidKey($key) 
{
	// Ensure the key only contains letters and numbers
	return preg_match('/^[a-z0-9A-Z]+$/', $key);
}

function WPPortfolio_isValidSecretKey($key)
{
	// Ensure the key only contains letters and numbers and not than 32 characters length.
	return ((strlen($key) <=32) && preg_match('/^[a-z0-9A-Z\'^£$%&*()}{@#~?><>,|=_+¬-]+$/', $key));
}


/**
 * Recursively delete a directory
 *
 * @param string $dir Directory name
 * @param boolean $deleteRootToo Delete specified top-level directory as well
 */
function WPPortfolio_unlinkRecursive($dir, $deleteRootToo)
{
    if(!$dh = @opendir($dir)) {
        return;
    }
    while (false !== ($obj = readdir($dh)))
    {
        if($obj == '.' || $obj == '..') {
            continue;
        }

        if (!@unlink($dir . '/' . $obj)) {
            WPPortfolio_unlinkRecursive($dir.'/'.$obj, true);
        }
    }

    closedir($dh);
   
    if ($deleteRootToo) {
        @rmdir($dir);
    }
   
    return;
} 


/**
 * A recursive function to copy all subdirectories and their contents.
 * @param $src The source directory
 * @param $dst The target directory
 */ 
function WPPortfolio_fileCopyRecursive($src, $dst)
{
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) )
    {
        if (( $file != '.' ) && ( $file != '..' )) 
        {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    } // end while
    closedir($dir);
} 


/**
 * Replace all occurances of the search string with the replacement. Uses alternative for str_ireplace if not available.
 * @param String $searchstr The string to search for.
 * @param String $replacestr The string to replace the search string with.
 * @param String $haystack The string to search.
 * @return String The text with the replaced string.
 */
function WPPortfolio_replaceString($searchstr, $replacestr, $haystack) {
	
	// Faster, but in PHP5.
	if (function_exists('str_ireplace')) {
		return str_ireplace($searchstr, $replacestr, $haystack);
	}
	// Slower but handles PHP4
	else { 
		return preg_replace("/$searchstr/i", $replacestr, $haystack);
	}
}


/**
 * Remove all slashes from all fields from data retrieved from the database.
 * @param $data The data array from the database.
 * @return Array The cleaned array.
 */
function WPPortfolio_cleanSlashesFromArrayData($data)
{
	if (count($data) > 0) {
		foreach ($data as $datakey => $datavalue) {
			$data[$datakey] = stripslashes($datavalue);
		}
	}
	
	return $data;
}

/**
 * Safe method to get the value from an array using the specified key.
 * @param $array The array to search.
 * @param String $key The key to use to index the array.
 * @param Boolean $returnSpace If true, return a space if there's nothing in the array.
 * @return String The array value.
 */
function WPPortfolio_getArrayValue($array, $key, $returnSpace = false, $convertHTML = false)
{
	if ($array && isset($array[$key])) {
		return $convertHTML ? htmlspecialchars($array[$key]) : $array[$key];
	}
	
	// If returnSpace is true, then return a space rather than nothing at all.
	if ($returnSpace) {
		return '&nbsp;';
	} else {
		return false;
	}
}



/**
 * Function that forces the refresh of a thumbnail.
 * @param Integer $siteid The ID of the site to refresh.
 */
function WPPortfolio_refresh_forceThumbnailRefresh($siteid)
{
	// Check for valid site ID
	if (!preg_match('%^[0-9]+$%', $siteid)) {
		return false;
	}
	
	$websitedetails = WPPortfolio_getWebsiteDetails($siteid);
	if (!$websitedetails) {
		return false;
	}
	
	// Delete existing thumbnails, then reload image 
	if ($websitedetails['customthumb']) 
	{
		WPPortfolio_removeCachedPhotos($websitedetails['customthumb']);
		$newImageURL = WPPortfolio_getAdjustedCustomThumbnail($websitedetails['customthumb'], 'sm');
	} 
	// Standard thumbnail
	else 
	{
		// Remove cached thumb and errors
		WPPortfolio_removeCachedPhotos($websitedetails['siteurl']);
		WPPortfolio_errors_removeCachedErrors($websitedetails['siteurl']);
		
		$newImageURL = WPPortfolio_getThumbnail($websitedetails['siteurl'], 'sm', true);
	}
	
	return $newImageURL;
}

/**
 * Simple function for reporting the status of the updates.
 */
function WPPortfolio_thumbnails_status($msg, $inner = false, $bottom = 0.25)
{
	printf('<div class="wpp_refresh_status_item" style="margin-left: %dpx; margin-bottom: %dpx">%s</div>', 	
		$inner*20, 	// Margin in px
		$bottom*20,	// Margin in px
		$msg
	);
	flush();
}


/**
 * Force a refresh of thumbnails
 * 
 * @param Integer $count The number of thumbnails to refresh, where 0 means refresh all.
 * @param Boolean $verbose If true, then show detailed output of what's happening with the refresh.
 */
function WPPortfolio_thumbnails_refreshAll($count = 10, $force = true, $verbose = true)
{	
	// If we're doing all of them, don't allow script to time out.
	if ($count == 0) {
		set_time_limit(0);
	}
	
	global $wpdb;
	
	// Need website count that's not using custom thumbs.
	$tableWebsites 	= $wpdb->prefix . TABLE_WEBSITES;
	$totalCount 	= $wpdb->get_var("SELECT COUNT(*) FROM $tableWebsites");
	$totalCountSTW 	= $wpdb->get_var("
		SELECT COUNT(*) 
		FROM $tableWebsites
		WHERE customthumb = ''
	");
	
	// Start
	if ($verbose) 
	{
		WPPortfolio_thumbnails_status(__('Starting thumbnail refresh...', 'wp-portfolio'));	
		WPPortfolio_thumbnails_status(sprintf(__('You have <b>%1$d items in your portfolio</b>, of which <b>%2$d are websites that use STW</b> for thumbnails.', 'wp-portfolio'), $totalCount, $totalCountSTW), 0, 1);
	}
	
	// Limit how many websites we look for.
	$SQL_LIMIT = false;
	if ($count > 0) {	
		$SQL_LIMIT = 'LIMIT ' . $count;
	}
	
	// Check how often we filter for them
	$SQL_DATE_FILTER = false;
	if (!$force)
	{
		$currentTime = current_time('timestamp');
		
		$timeFrequency = get_option(WPP_STW_REFRESH_TIME, 'weekly');
		switch ($timeFrequency)
		{
			case 'daily':
					$currentTime -= 86400; // 1 day
				break;	
				
			case 'monthly':
					$currentTime -= 181440210; // 30 days
				break;
				
			case 'quarterly':
					$currentTime -= 544320630; // 90 days
				break;
				
			default:
					$currentTime -= 604800; // 7 days
				break;
		}		
		
		// Get results older than the specified date.
		$SQL_DATE_FILTER = sprintf('AND (last_updated IS NULL OR last_updated <= \'%s\') ', date('Y-m-d H:i:s', $currentTime));
	}
	
	// Update each thumbnail...
	$websites = $wpdb->get_results("
		SELECT * 
		FROM $tableWebsites
		WHERE customthumb = ''
		$SQL_DATE_FILTER
	");
	
	if (empty($websites))
	{
		if ($verbose) {
			WPPortfolio_thumbnails_status(__('No websites need updating currently.', 'wp-portfolio'), 1, 1);
		}
	}
	
	else 
	{
		$progressCount = 0;
		foreach ($websites as $websiteDetails)
		{
			$progressCount++;
			WPPortfolio_thumbnails_status(sprintf(__('Refreshing <b>%s</b>...', 'wp-portfolio'), $websiteDetails->siteurl), 1);	
			
			// Do the actual refresh
			WPPortfolio_refresh_forceThumbnailRefresh($websiteDetails->siteid);
	
			// Update the thumbnail with details when it was last updated.
			$wpdb->query($wpdb->prepare("
				UPDATE $tableWebsites 
				SET last_updated = %s
				WHERE siteid = %d 
			", current_time('mysql'), $websiteDetails->siteid));
			
			WPPortfolio_thumbnails_status(sprintf(__('Refreshed. (%.1f%% complete)', 'wp-portfolio'), ($progressCount / $totalCountSTW)*100), 1, 1);		
		}
	}
	
	// End.
	if ($verbose) {
		WPPortfolio_thumbnails_status(__('Refresh all done.', 'wp-portfolio'));
	}
}


?>