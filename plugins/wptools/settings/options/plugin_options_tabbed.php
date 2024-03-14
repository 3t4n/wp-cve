<?php namespace wptoolsWPSettings;
// $mypage = new Page('WP Tools', array('type' => 'menu'));
$mypage = new Page('Settings WPTools', array('type' => 'submenu', 'wp-tools'));
$settings = array();
if(!isset($wptools_checkversion))
  $wptools_checkversion =  trim(sanitize_text_field(get_option('wptools_checkversion','')));


Global $wptools_checkversion;
require_once (WPTOOLSPATH. "guide/guide.php");
$wpmemory_memory = wptools_check_memory();
$wptools_server_ram = wptools_check_total_ram();
	$time_limit= wptools_current_time_limit();
	// $upload_limit =	ini_get('upload_max_filesize');
	$upload_limit = wptools_current_upload_max_filesize();
	$upload_limit =  get_option('wptools_max_filesize', '');
	if($upload_limit < 1)
	$upload_limit = @ini_get('upload_max_filesize');
$settings[remove_accents(esc_attr__('Startup Guide','wptools'))]['Startup Guide'] = array('info' => $wptools_help );
$fields = array();   
$settings[remove_accents(esc_attr__('Startup Guide','wptools'))]['Startup Guide']['fields'] = $fields;
$msg2 = '<big>';
$msg2 .= esc_attr__('You can increase the memory limit, time limit and max upload filesize without editing any PHP or WordPress files. If your PHP init define a bigger amount, our plugin doesn\'t reduce it. Choose Select to don\'t change it.','wptools');
$msg2 .= '<br />';
$msg2 .= esc_attr__('Then click SAVE CHANGES.', 'wptools');
$msg2 .= '<hr>';
$msg2 .= '<a href="http://wptoolsplugin.com/">Visit our site to learn more.</a>';
$msg2 .= '</big>'; 
$settings[remove_accents(esc_attr__('General Settings','wptools'))][__('Instructions', 'wptools')] = array('info' => $msg2);
$fields = array();



if(empty($wptools_checkversion)) {
$fields[] = array(
	'type' 	=> 'select',
	'name' 	=> 'wptools_memory_limit',
	'label' =>esc_attr__('Add Max Memory Limit','wptools').'<br />Currently: '.$wpmemory_memory['wp_limit'],
	'id' => 'rate_limiting', // (optional, will default to name)
	'value' => 'red', // (optional, will default to '')
	'select_options' => array(
		array('value'=>'', 'label' =>esc_attr__('Select', "wptools")),
		array('value'=>'128', 'label' =>esc_attr__('128 MB', "wptools")),
		array('value'=>'128', 'label' =>esc_attr__('Go Pro for More', "wptools")),
		)
	);
}
else{
	$fields[] = array(
		'type' 	=> 'select',
		'name' 	=> 'wptools_memory_limit',
		'label' =>esc_attr__('Add Max Memory Limit','wptools').'<br />Currently: '.$wpmemory_memory['wp_limit'],
		'id' => 'rate_limiting', // (optional, will default to name)
		'value' => 'red', // (optional, will default to '')
		'select_options' => array(
			array('value'=>'', 'label' =>esc_attr__('Select', "wptools")),
			array('value'=>'128', 'label' =>esc_attr__('128 MB', "wptools")),
			array('value'=>'256', 'label' =>esc_attr__('256 MB', "wptools")),
			array('value'=>'512', 'label' =>esc_attr__('512 MB', "wptools")),
			)
		);
}	
if(empty($wptools_checkversion)) {
	$fields[] = array(
		'type' 	=> 'select',
		'name' 	=> 'wptools_time_limit',
		'label' =>esc_attr__('Add Max Execution Time Limit','wptools').'<br />Currently: '.$time_limit,
		'id' => 'rate_limiting', // (optional, will default to name)
		'value' => 'red', // (optional, will default to '')
		'select_options' => array(
			array('value'=>'' ,'selected', 'label' =>esc_attr__('Select', "wptools")),
			array('value'=>'120', 'label' =>esc_attr__('120 Sec', "wptools")),
			array('value'=>'120', 'label' =>esc_attr__('Go Pro For More', "wptools")),
			)			
		);
}
else{
	$fields[] = array(
		'type' 	=> 'select',
		'name' 	=> 'wptools_time_limit',
		'label' =>esc_attr__('Add Max Execution Time Limit','wptools').'<br />Currently: '.$time_limit,
		'id' => 'rate_limiting', // (optional, will default to name)
		'value' => 'red', // (optional, will default to '')
		'select_options' => array(
			array('value'=>'' ,'selected', 'label' =>esc_attr__('Select', "wptools")),
			array('value'=>'120', 'label' =>esc_attr__('120 Sec', "wptools")),
			array('value'=>'180', 'label' =>esc_attr__('180 Sec', "wptools")),
			array('value'=>'240', 'label' =>esc_attr__('240 Sec', "wptools")),
			array('value'=>'300', 'label' =>esc_attr__('300 Sec', "wptools")),
			array('value'=>'360', 'label' =>esc_attr__('360 Sec', "wptools")),
			)			
		);
}
if(empty($wptools_checkversion)) {
		$fields[] = array(
			'type' 	=> 'select',
			'name' 	=> 'wptools_max_filesize',
			'label' =>esc_attr__('Add Max Upload File size Limit','wptools').'<br />Currently: '.$upload_limit,
			'id' => 'rate_limiting', // (optional, will default to name)
			'value' => 'red', // (optional, will default to '')
			'select_options' => array(
				array('value'=>'', 'label' =>esc_attr__('Select', "wptools")),
				array('value'=>'16', 'label' =>esc_attr__('16 MB', "wptools")),
				array('value'=>'16', 'label' =>esc_attr__('Go Pro For More', "wptools")),
				)			
			);
		}
		else{
			$fields[] = array(
				'type' 	=> 'select',
				'name' 	=> 'wptools_max_filesize',
				'label' =>esc_attr__('Add Max Upload File size Limit','wptools').'<br />Currently: '.$upload_limit,
				'id' => 'rate_limiting', // (optional, will default to name)
				'value' => 'red', // (optional, will default to '')
				'select_options' => array(
					array('value'=>'', 'label' =>esc_attr__('Select', "wptools")),
					array('value'=>'16', 'label' =>esc_attr__('16 MB', "wptools")),
					array('value'=>'32', 'label' =>esc_attr__('32 MB', "wptools")),
					array('value'=>'64', 'label' =>esc_attr__('64 MB', "wptools")),
					array('value'=>'128', 'label' =>esc_attr__('128 MB', "wptools")),
					array('value'=>'256', 'label' =>esc_attr__('256 MB', "wptools")),
					array('value'=>'512', 'label' =>esc_attr__('512 MB', "wptools")),
					array('value'=>'1024', 'label' =>esc_attr__('1 GB', "wptools")),
					)			
				);
		}

			// Add upload Maximum FileSize


/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_reporting(E_WARNING);
*/


            /*
			$fields[] = array(
				'type' 	=> 'radio',
				'name' 	=> 'wptools_error_reporting',
				'label' =>esc_attr__("Show errors, warnings and notices without change any file (neither wp-config.php). Not change WP_DEBUG and not recommendaded for production use.", "wptools"),
				'radio_options' => array(
					array('value' => 'yes', 'label' =>esc_attr__('Yes, show errors, warnings and notices.', "wptools")),
					array('value' => 'no', 'label' =>esc_attr__("No, lets WordPress handle it.", "wptools")),
				)
			);
			*/

			$fields[] = array(
				'type' 	=> 'radio',
				'name' 	=> 'wptools_disable_sitemap',
				'label' =>esc_attr__("Disable WordPress Native Sitemap Automatic Creation.", "wptools"),
				'radio_options' => array(
					array('value' => 'Yes', 'label' =>esc_attr__('Yes, disable all', "wptools")),
					array('value' => 'users', 'label' =>esc_attr__('Disable Only Users Sitemap', "wptools")),
					array('value' => 'No', 'label' =>esc_attr__('No', "wptools")),
				)
			);



			/*
			$fields[] = array(
				'type' 	=> 'radio',
				'name' 	=> 'wptools_disable_gutenberg',
				'label' =>esc_attr__("Disable Gutenberg Block Editor and restore the Classic Editor.", "wptools"),
				'radio_options' => array(
					array('value' => 'Yes', 'label' =>esc_attr__('Yes, disable', "wptools")),
					array('value' => 'No', 'label' =>esc_attr__('No', "wptools")),
				)
			);
			*/
			$fields[] = array(
				'type' 	=> 'radio',
				'name' 	=> 'wptools_auto_updates',
				'label' =>esc_attr__("Enable Auto Update Plugin? (default Yes)", "wptools"),
				'radio_options' => array(
					array('value' => 'Yes', 'label' =>esc_attr__('Yes, enable WP Tools Auto Update', "wptools")),
					// array('value' => 'All', 'label' =>esc_attr__('Yes, enable all plugins', "wptools")),
					array('value' => 'No', 'label' =>esc_attr__('No (unsafe)', "wptools")),
				)
			);
			$fields[] = array(
				'type' 	=> 'radio',
				'name' 	=> 'wptools_disable_updates_notifications',
				'label' =>esc_attr__("Disables the default notification email sent by a site after an automatic core, theme or plugin update.", "wptools"),
				'radio_options' => array(
					array('value' => 'Yes', 'label' =>esc_attr__('Yes, disable', "wptools")),
					array('value' => 'No', 'label' =>esc_attr__('No', "wptools")),
				)
			);

			$fields[] = array(
				'type' 	=> 'textarea',
				'name' 	=> 'wptools_add_bing_webmaster_metaname',
				'label' =>  esc_attr__('Please paste the Meta Name From Bing Page','wptools').'.');

				
			$fields[] = array(
					'type' 	=> 'textarea',
					'name' 	=> 'wptools_add_bing_webmaster_content',
					'label' =>  esc_attr__('Please paste the Meta Name Content from Bing Page','wptools').'.');
				
// ----
// - analytics
// debug
// hide Bar
// Goggle Webmaster verification code in Settings
// - Google Search Central, formerly Google Webmasters,(HTML TAG)
//- <meta name="google-site-verification" content="tE-E7o24phnmiUJuMGWrE_14ZTdAYj0lKQLSAKkP0TQ" />
$fields[] = array(
	'type' 	=> 'textarea',
	'name' 	=> 'wptools_add_google_webmaster',
	'label' =>  esc_attr__('Please paste only the value in the contents = ','wptools')."YOUR_UNIQUE_ID " .
	esc_attr__("of the supplied meta tag. For example","wptools").', '.htmlentities("<").'meta name="google-site-verification" contents="YOUR_UNIQUE_ID" /'
	 .htmlentities(">").'.'
	);
$fields[] = array(
	'type' 	=> 'text',
	'name' 	=> 'wptools_add_analitics',
	'label' => esc_attr__("Add Google Analytics GA Tracking ID.", "wptools").'<br>'.
    esc_attr__("(format: XX-XXXXXXXX-X)", "wptools").'<br>'.
	esc_attr__("(or format: G-XXXXXXXXXX)", "wptools")
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'wptools_alert_debug',
	'label' =>esc_attr__("Alert on Top Admin Bar if WordPress Debug is active.", "wptools"),
	'radio_options' => array(
		array('value' => 'yes', 'label' =>esc_attr__('Yes', "wptools")),
		array('value' => 'no', 'label' =>esc_attr__('No,', "wptools")),
	)
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'wptools_hide_admin_bar',
	'label' =>esc_attr__("Hide Admin Bar from non Administrators.", "wptools"),
	'radio_options' => array(
		array('value' => 'yes', 'label' =>esc_attr__('Yes', "wptools")),
		array('value' => 'no', 'label' =>esc_attr__('No', "wptools")),
	)
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'wptools_disable_lazy',
	'label' =>esc_attr__("Deactivate Lazy Load functionality (added in WP version 5.5).", "wptools"),
	'radio_options' => array(
		array('value' => 'yes', 'label' =>esc_attr__('Yes', "wptools")),
		array('value' => 'no', 'label' =>esc_attr__('No', "wptools")),
	)
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'wptools_disable_emojis',
	'label' =>esc_attr__("Deactivate Emojis functionality (support for emoji's in older browsers)?", "wptools"),
	'radio_options' => array(
		array('value' => 'yes', 'label' =>esc_attr__('Yes', "wptools")),
		array('value' => 'no', 'label' =>esc_attr__('No', "wptools")),
	)
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'wptools_show_pageload_info',
	'label' =>esc_attr__("Show Page Load Info at footer this page?", "wptools"),
	'radio_options' => array(
		array('value' => 'yes', 'label' =>esc_attr__('Yes', "wptools")),
		array('value' => 'no', 'label' =>esc_attr__('No', "wptools")),
	)
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'wptools_bypass_wpdebug',
	'label' =>esc_attr__("Bypass WordPress debug (if WP_DEBUG = false) and show errors, warnings and notices on screen? (Don't use in production)", "wptools"),
	'radio_options' => array(
		array('value' => 'yes', 'label' =>esc_attr__('Yes, show errors, warnings and notices.', "wptools")),
		array('value' => 'no', 'label' =>esc_attr__('No, lets WordPress handle it.', "wptools")),
	)
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'wptools_show_adminbar',
	'label' =>esc_attr__('Disables the Admin Bar from the frontend only? (it does not affect the dashboard)', "wptools"),
	'radio_options' => array(
		array('value' => 'yes', 'label' =>esc_attr__('Yes', "wptools")),
		array('value' => 'no', 'label' =>esc_attr__('No', "wptools")),
	)
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'wptools_classic_widget',
	'label' =>esc_attr__('Restores the previous ("classic") WordPress widgets settings screens?', "wptools"),
	'radio_options' => array(
		array('value' => 'yes', 'label' =>esc_attr__('Yes', "wptools")),
		array('value' => 'no', 'label' =>esc_attr__('No', "wptools")),
	)
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'wptools_show_errors',
	'label' =>esc_attr__('Show Errors Button (also Javascript errors) on Admin Toolbar?', "wptools"),
	'radio_options' => array(
		array('value' => 'yes', 'label' =>esc_attr__('Yes', "wptools")),
		array('value' => 'no', 'label' =>esc_attr__('No', "wptools")),
	)
);
$fields[] = array(
	'type' 	=> 'text',
	'name' 	=> 'wptools_logo',
	'label' =>esc_attr__("Replace WordPress logo at login form. Just paste a URL of the new logo.", "wptools")
);
$fields[] = array(
	'type' 	=> 'text',
	'name' 	=> 'wptools_logo_width',
	'label' =>esc_attr__("Logo Width in pixels.", "wptools")
);
$fields[] = array(
	'type' 	=> 'text',
	'name' 	=> 'wptools_logo_height',
	'label' =>esc_attr__("Logo Height in pixels.", "wptools")
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'wptools_erase_readme',
	'label' =>esc_attr__('Erase WP readme and license file of root folder?', "wptools"),
	'radio_options' => array(
		array('value' => 'yes', 'label' =>esc_attr__('Yes', "wptools")),
		array('value' => 'no', 'label' =>esc_attr__('No', "wptools")),
	)
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'wptools_remove_icon',
	'label' =>esc_attr__('Remove WP icon on top left position of admin bar?', "wptools"),
	'radio_options' => array(
		array('value' => 'yes', 'label' =>esc_attr__('Yes', "wptools")),
		array('value' => 'no', 'label' =>esc_attr__('No', "wptools")),
	)
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'wptools_disable_console',
	'label' =>esc_attr__('Disable javascript console log for non administrators.', "wptools"),
	'radio_options' => array(
		array('value' => 'yes', 'label' =>esc_attr__('Yes', "wptools")),
		array('value' => 'no', 'label' =>esc_attr__('No', "wptools")),
	)
);


$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'wptools_disable_self_pingbacks',
	'label' =>esc_attr__('Disable Pingbacks to Your own Site (Self Pingback).', "wptools"),
	'radio_options' => array(
		array('value' => 'yes', 'label' =>esc_attr__('Yes', "wptools")),
		array('value' => 'no', 'label' =>esc_attr__('No', "wptools")),
	)
);


if (function_exists('_unzip_file_pclzip')) {
    // echo 'PclZip class exists!';
	$fields[] = array(
		'type' 	=> 'radio',
		'name' 	=> 'wptools_disable_ziparchive',
		'label' =>esc_attr__("Resolve 'Incompatible Archive' issue when installing plugins from zip files by using PclZip instead of ZipArchive. Consider using this option only for new plugin installations.", "wptools"),
		'radio_options' => array(
			array('value' => 'yes', 'label' =>esc_attr__('Yes', "wptools")),
			array('value' => 'no', 'label' =>esc_attr__('No', "wptools")),
		)
	);
}





$settings[remove_accents(esc_attr__('General Settings','wptools'))]['']['fields'] = $fields; 
// It contains three values, first one is the load average for last 15 minutes, second one is for 5 minutes, third one is for last 1 minute.
$msg2 = '<br />'; 
try {
	if (!@is_readable('/proc/stat')) {
       $msg2 .= "Plugin requirement: &nbsp; /proc/stat doesn't readable. Talk with your hosting and request to enable it.";
	   $msg2 .= '<br />'; 
	}
}
catch(Exception $e) {
		$msg2 .= "Plugin requirement: &nbsp; /proc/stat doesn't readable. Talk with your hosting and request to enable it.";
		$msg2 .= '<br />'; 
} 
//
$msg2 .= '<br />'; 
$msg2 .= '<b>'.__('Show Server Load Average (CPU Usage) for the last minute at top admin bar.','wptools').'</b>';
$msg2 .= '<br />'; 
$msg2 .=esc_attr__('Use this option only in Linux Servers.','wptools');
$msg2 .= '<br />'; 
$msg2 .=esc_attr__('This information is refreshed each 5 seconds.','wptools');
$msg2 .= '<br />'; 
$msg2 .=esc_attr__('If you can see always 0 (zero) or wait is because we are unable to get this info from your server. Server requirements:','wptools');
$msg2 .= '<br />'; 
$msg2 .=esc_attr__('shell_exec enabled on your PHP (ask for your hosting to enable it if necessary.','wptools');
$msg2 .= '<br />'; 
$msg2 .=esc_attr__('Server Files Readable: /proc/cpuinfo and /proc/stat.','wptools');
$msg2 .= '<br />'; 
$msg2 .= '<br />'; 
$msg2 .=esc_attr__('Lower numbers are better.','wptools').' ';
$msg2 .=esc_attr__('Higher numbers represent a problem or an overloaded machine.','wptools');
$msg2 .= '<br />';
$msg2 .= '<br />';
$settings[remove_accents(esc_attr__('Processor Load','wptools'))][__('Instructions')] = array('info' => $msg2);
$msg2 = '<b>'.__('Show Disk Usage.','wptools').'</b>';
$msg2 .= '<br />'; 
$msg2 .=esc_attr__('Use this option only in Linux Servers.','wptools');
$msg2 .= '<br />'; 
$msg2 .=esc_attr__('We use the PHP function disk_total_space(). Talk with your hosting if doesn\'t work.','wptools');
$msg2 .= '<br />';
$msg2 .= '<br />';
if (function_exists('disk_total_space' ) and  function_exists('disk_free_space' ) ){
	global $wptools_request_url;
	$pos = stripos($wptools_request_url, 'tab=disk_usage');
    if($pos !== false) {
		try {
			$disk_total = round(((disk_total_space('/')/1024)/1024)/1024 ,1); //convert bytes to GB with 1 decimal place.
			$disk_free  = round(((disk_free_space ('/')/1024)/1024)/1024 ,1);
			$disk_used = round($disk_total-$disk_free, 1);
			$msg2 .=esc_attr__('Disk Total: ','wptools').$disk_total.'G';
			$msg2 .= '<br />';
			$msg2 .=esc_attr__('Disk Used: ','wptools').$disk_used.'G';
			$msg2 .= '<br />';
			$msg2 .=esc_attr__('Disk Free: ','wptools').$disk_free.'G';
		}
		catch(Exception $e) {
		   $msg2 = $e->getMessage();
		  } 
	}
}
else{
	$msg2 = '<b>'.esc_attr__('We need to use the PHP functions disk_total_space() and disk_free_space(). Talk with your hosting to enable them.','wptools').'</b>';
}
$path = ABSPATH;
function getDirectorySize($path) {
    try {
			$objects = new \DirectoryIterator($path);
			$size = 0;
			foreach ( $objects as $object ) {
				if ( $object->isFile() ) {
					$size += $object->getSize();
				}
			}
		}
	catch(Exception $e) {
			     $size = 0;
	}
	return $size;
}
$objects = new \RecursiveIteratorIterator(
	new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS),
	\RecursiveIteratorIterator::SELF_FIRST,
	\RecursiveIteratorIterator::CATCH_GET_CHILD
);
$dirlist = [];
foreach ( $objects as $name => $object ) {
	if ( $object->isDir() ) {
		$dirlist[$object->getPathName()] = getDirectorySize($object->getPathName());
	}
}
arsort($dirlist);
ob_start();
if(!empty($wptools_checkversion))
$wptools_top = 100;
else
 $wptools_top = 5;
?>
<h3> <?php esc_attr__('Top','wptools');?>  <?php esc_attr_e('Bigger Folders','wptools');?> </h3>
<?php
if(empty($wptools_checkversion))
 echo '<h2>('.esc_attr__('100 Folders in Premium Version','wptools').')</h2>';
?>
<table class="wptools_admin_table">
<thead>
<tr>
  <td><?php esc_attr_e('Path','wptools');?></td>
  <td class="text-right"><?php esc_attr_e('Size','wptools');?></td>
</tr>
</thead>
  <?php 
  $ctd = 0;
  foreach ( $dirlist as $dir => $size ) { 
	  $ctd++;
	  if($ctd > $wptools_top)
          break;
?>
	<tr>
	  <td class="text-monospace"><?php echo esc_attr($dir) ?></td>
	  <td class="text-right small text-nowrap"><?php echo esc_attr(number_format($size / 1024, 0, ',', '.')); ?> KB</td>
	</tr>
  <?php } ?>
</table>
<?php
$msg2 .= ob_get_contents();
ob_end_clean();
$settings[remove_accents(esc_attr__('Disk Usage','wptools'))][esc_attr__('Instructions','wptools')] = array('info' => $msg2);
$fields = array();
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'wptools_radio_server_load',
	'label' =>esc_attr__('Enable to show Server Percentage Load at Top Admin Bar?','wptools'),
	'radio_options' => array(
		array('value'=>'yes', 'label' =>esc_attr__('yes', "wptools")),
		array('value'=>'no', 'label' =>esc_attr__('no', "wptools"))
		)			
);
$settings[remove_accents(esc_attr__('Processor Load','wptools'))]['']['fields'] = $fields;
//  Total Php Server Memory: 2147483648MB
// $wpmemory_memory = wptools_check_memory();
ob_start();
try{
	$mb = ' MB';
	echo '<hr>';
	echo '<b>';
	echo  esc_attr__('WordPress Memory Limit','wptools') . ' (*): ' . esc_attr($wpmemory_memory['wp_limit']) . esc_attr($mb) .
		'&nbsp;&nbsp;&nbsp;  |&nbsp;&nbsp;&nbsp;';
	$perc = $wpmemory_memory['usage'] / $wpmemory_memory['wp_limit'];
	if ($perc > .7)
		echo '<span style="color:' . esc_attr($wpmemory_memory['color']) . ';">';
	echo esc_attr__('Your usage now','wptools') .':' . esc_attr($wpmemory_memory['usage']) .
		' MB &nbsp;&nbsp;&nbsp;';
	if ($perc > .7)
		echo '</span>';
	echo '|&nbsp;&nbsp;&nbsp;'. esc_attr__('Total Php Server Memory').': ' . esc_attr($wpmemory_memory['limit']) .
		' MB&nbsp;&nbsp;&nbsp;';
	if($wptools_server_ram > 0) {
	echo '|&nbsp;&nbsp;&nbsp;'.esc_attr__('Total Hardware Memory').': ';
	echo esc_attr(wptools_format_filesize_kB($wptools_server_ram));
	}
	// echo $wptools_server_ram;
	echo '</b>';
	echo '</center>';
	echo '<hr>';
	echo '<br />';

	echo '(*) '.esc_attr__('You can use our complimentary free plugin','wptools').' ';
	echo '<a href="https://wordpress.org/plugins/wp-memory/">wp-memory</a>';
	echo '&nbsp;';
	esc_attr_e('to receive tips about how enhance your website\'s memory performance and address any issues that may arise.','wptools').' ';	
	echo '&nbsp;';
	echo '<a href="http://wpmemory.com/php-memory-limit/">';
	esc_attr_e('Click here to learn more.','wptools');
	echo '</a>';
}
catch(Exception $e) {
	// echo 'Message: ' .$e->getMessage();
}
$msg2 = ob_get_contents();
ob_end_clean ( );
$settings[remove_accents(esc_attr__('Memory Usage','wptools'))][__('Information')] = array('info' => $msg2);

//$ip_server = sanitize_text_field($_SERVER['SERVER_ADDR']);

// Check if 'SERVER_ADDR' key exists in $_SERVER array
if (isset($_SERVER['SERVER_ADDR'])) {
    // Access the 'SERVER_ADDR' key
    $ip_server = sanitize_text_field($_SERVER['SERVER_ADDR']);
} else {
    // Handle the case where 'SERVER_ADDR' key is not set
    $ip_server = 'Unknown';
}


if (filter_var($ip_server, FILTER_VALIDATE_IP)) {
	$msg2 = '<br />'; 
	$msg2 .= '<b>'.__('Your Server IP Address:').'&nbsp;&nbsp;'.$ip_server.'</b>';
	$msg2 .= '<br />';
} else {
	$msg2 = '<br />'; 
	$msg2 .= '<b>'.__('Unable to get your server Ip. Probably blocked by your hosting company.').'</b>';
	$msg2 .= '<br />';
}

$msg2 = '<br />'; 
$msg2 .= '<b>'.__('WordPress will send email only when PHP fatal errors happens.','wptools').'</b>';
$msg2 .= '<br />'; 
$msg2 .= '<b>'.__('Do you want receive email alerts when notices/warnings and javascript errors happens? ','wptools').'</b>';
$msg2 .= '<br />'; 
$msg2 .= '<b>'.__('For the weekly email, the default setting is "Yes." Choose "No" if you prefer not to receive it.','wptools').'</b>';
$msg2 .= '<br />'; 
$msg2 .= '<b>'.__('Left Blank to use your default Wordpress email.Then, click save changes.','wptools');
$msg2 .= '<br />';
$msg2 .= '<br />';
$settings[remove_accents(esc_attr__('Notifications','wptools'))][__('Instructions', 'wptools')] = array('info' => $msg2);
$fields = array();
$fields[] = array(
	'type' 	=> 'text',
	'name' 	=> 'wptools_email_to',
	'label' => 'email'
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'wptools_radio_email_error_notification',
	'label' =>esc_attr__('Send email notification when notices/warnings or erros occurs? (limited at 10 emails each 5 minutes)','wptools'),
	'radio_options' => array(
		array('value'=>'yes', 'label' =>esc_attr__('yes', "wptools")),
		array('value'=>'no', 'label' =>esc_attr__('no', "wptools"))
		)			
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'wptools_radio_email_weekly_error_notification',
	'label' => esc_attr__('Receive a weekly email notification providing a summary of the number of notices, warnings, or errors that occurred?', 'wptools'),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('yes', 'wptools')),
		array('value' => 'no', 'label' => esc_attr__('no', 'wptools')),
	),
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'wptools_radio_email_weekly_load_notification',
	'label' => esc_attr__("Receive a weekly email with a summary of my website's page load times?  (Alert only if average exceeds 3 seconds.)", 'wptools'),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('yes', 'wptools')),
		array('value' => 'no', 'label' => esc_attr__('no', 'wptools')),
	),
);


$settings[remove_accents(esc_attr__('Notifications','wptools'))]['']['fields'] = $fields;
$fields = array();
if(is_multisite()){
	$url = esc_url(WPTOOLSHOMEURL)."plugin-install.php?s=sminozzi&tab=search&type=author";
	$msg = '<script>';
	$msg .= 'window.location.replace("'.$url.'");';
	$msg .= '</script>';
}
else {
	$msg =  '<script>';
	$msg .= 'window.location.replace("'.esc_url(WPTOOLSHOMEURL).'/admin.php?page=wptools_options39");';
	// $msg .= 'window.location.replace("'.esc_url(STOPBADBOTSHOMEURL).'plugin-install.php?s=sminozzi&tab=search&type=author");';
	$msg .= '</script>';
}
$settings[remove_accents(esc_attr__('More Useful Tools','wptools'))][esc_attr__('More Useful Tools','wptools')] = array('info' => $msg);
	/*
$settings['Stop Bad Bots Tools']['Stop Bad Bots Tools'] = array('info' => $msg );
$fields = array();
$settings['Stop Bad Bots Tools']['Stop Bad Bots Tools']['fields'] = $fields;
*/
$fields = array();   
//$settings['Memory Checkup'][__('Memory Checkup')]['fields'] = $fields;
//

if(empty($wptools_checkversion)) {

		$gopro = '<span style="font-size: 24pt; color: #CC3300;">'.esc_attr__('Premium Features', 'wptools').'</span>';
		$gopro .= '<span style="font-size: 14pt; color: #000000;">';
		$gopro .= '<br />';
		$gopro .= '<font size="14pt">';
		$gopro .= esc_attr__('Visit our Premium Page for more details.', 'wptools');
		$gopro .= '<br />';
		$gopro .= '<a href="http://wptoolsplugin.com/premium" class="button button-primary">'.esc_attr__("Premium Page","wptools").'</a>';
		$gopro .= '<br />';
		$gopro .= '<br />';


	if(WPTOOLSVERSION > 10) {
		$gopro .=  esc_attr__('Paste below the Item Purchase Code received by email from us when you bought the premium version.', 'wptools');
		$gopro .= '<br />';
		//$gopro .=  esc_attr__("You don't need reinstall the plugin.", "wptools");
		$gopro .=  '  '.esc_attr__("After that, click SAVE CHANGES Button.", "wptools");
		// Form
	}
}
else
{
	$gopro = '<h2> ';
    $gopro .=  esc_attr__("Premium Version activated!","wpmemory");
    $gopro .=  '</h2>';
}
$settings[remove_accents(esc_attr__("Go Pro", "wptools"))]['Go Pro'] = array('info' => $gopro );
// $fields = array();

if(WPTOOLSVERSION > 10) {

	if(empty(trim($wptools_checkversion))){
		$fields[] = array(
			'type' 	=> 'text',
			'name' 	=> 'wptools_checkversion',
			'label' => esc_attr__('Purchase Code:','wptools'),
			);
	}  
} 

//die(var_export(WPTOOLSVERSION));


$settings[remove_accents(esc_attr__('Go Pro','wptools'))]['Go Pro']['fields'] = $fields;
new OptionPageBuilderTabbed($mypage, $settings);
function wptools_findip2()
{
    $wptools_ip = '';
		$headers = array(
            'HTTP_CLIENT_IP',        // Bill
            'HTTP_X_REAL_IP',        // Bill
            'HTTP_X_FORWARDED',      // Bill
            'HTTP_FORWARDED_FOR',    // Bill 
            'HTTP_FORWARDED',        // Bill
            'HTTP_X_CLUSTER_CLIENT_IP', //Bill
			'HTTP_CF_CONNECTING_IP', // CloudFlare
			'HTTP_X_FORWARDED_FOR',  // Squid and most other forward and reverse proxies
			'REMOTE_ADDR',           // Default source of remote IP
		);
		for ( $x = 0; $x < 8; $x++ ) {
			foreach ( $headers as $header ) {
               if( !isset($_SERVER[$header]))
                   continue;
				$ip = trim( sanitize_text_field($_SERVER[$header]) );
				if ( empty( $ip ) ) {
					continue;
				}
				if ( false !== ( $comma_index = strpos( $_SERVER[$header], ',' ) ) ) {
					$ip = substr( $ip, 0, $comma_index );
				}
    			// First run through. Only accept an IP not in the reserved or private range.
				if($ip == '127.0.0.1')
                       {
                        $ip='';
                         continue;
                       }
				if ( 0 === $x ) {
					$ip = filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE | FILTER_FLAG_NO_PRIV_RANGE );
				} else {
					$ip = filter_var( $ip, FILTER_VALIDATE_IP );
				}
				if ( ! empty( $ip ) ) {
					break;
				}
			}
			if ( ! empty( $ip ) ) {
				break;
			}
		}
    if (!empty($ip))
        return $ip;
    else
        return 'unknow';
}
function wptools_validate_ip2($wptools_ip)
{
    if (filter_var($wptools_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
        return false;
    }
    return true;
}
 function wptools_check_total_ram()
{
	try {
		if (wptools_isShellEnabled()) {
			$total_ram = shell_exec("grep -w 'MemTotal' /proc/meminfo | grep -o -E '[0-9]+'");
		} else {
			$total_ram = '0';
		}
    	return trim($total_ram);
    }
	catch(Exception $e) {
		// echo 'Message: ' .$e->getMessage();
		return '0';
	}
}
function wptools_isShellEnabled()
{
	try {
		if (function_exists('shell_exec') && !in_array('shell_exec', array_map('trim', explode(', ', ini_get('disable_functions'))))) {
			$returnVal = shell_exec('cat /proc/cpuinfo');
			if (!empty($returnVal)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
    }
	catch(Exception $e) {
		// echo 'Message: ' .$e->getMessage();
		return false;
	}
}