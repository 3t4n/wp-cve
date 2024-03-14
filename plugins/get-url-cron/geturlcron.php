<?php
/*
Plugin Name: Get URL Cron
Plugin URI: https://json-content-importer.com/geturlcron
Description: Request http-URLs via Cronjobs and check the result. Retry if needed. Monitor and Check Websites.
Version: 1.4.8
Author: Bernhard Kux
Author URI: http://www.kux.de/
Text Domain: get-url-cron
Domain Path: /languages
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/* block direct requests */

defined('ABSPATH') OR exit;
if ( !function_exists( 'add_action' )) {
	echo 'Hello, this is a plugin: You must not call me directly.';
	exit;
}
define( 'GETURLCRON_VERSION', '1.4.8' );  // current version number


if (!defined('DISABLE_WP_CRON')) {
	define('DISABLE_WP_CRON',false);
}

function geturlcron_i18n_init() {
	$pd = dirname(
		plugin_basename(__FILE__)
	).'/languages/';
	load_plugin_textdomain('get-url-cron', false, $pd);
}
add_action('plugins_loaded', 'geturlcron_i18n_init');


class GetUrlCron {	
	private $urlSettingsArr = array();
	private $nooffields = 15;
	private static $logfile = "";
	private $subaction = "";
	private $action = "";
	public static $fi = array(
			"url" => 1,
			"interval" => 2,
			"startdate" => 3,
			"retries" => 4,
			"requiredformat" => 5,
			"requiredjsonfield" => 6,
			"sendmail" => 8,
			);

	public static $fi_size = array(
			"url" => 100,
			"interval" => 20,
			"startdate" => 25,
			"retries" => 2,
			"requiredformat" => 5,
			"requiredjsonfield" => 10,
			"sendmail" => 1,
			);
			
	public static $reqformatArr = array(
			"any" => "string",
			"json" => "json",
		);



	protected function __construct() {
		$this->geturlcron_getnooffields();

		add_action('admin_menu', array( $this, 'geturlcron_menu'));

		$this->geturlcron_setlogfile();


		add_filter( 'cron_schedules',  array( $this, 'geturlcron_recurrence_interval' ) );
		add_action( 'init', array( $this, 'geturlcron_action_handle' ) );

		register_activation_hook(__FILE__, array($this, 'geturlcron_activatejobs'));
		register_deactivation_hook(__FILE__, array($this, 'geturlcron_unschedulejobs'));
		$this->geturlcron_set_urlSettingsarr();

		for ($no = 1; $no <= count($this->urlSettingsArr); $no++) {
			add_action('geturlcron_event-'.$no, array( $this, 'geturlcron_add_action_cronjob' ) );
		}
	}
	
	
public function geturlcron_detailsettings_page() {
	echo "<h1>".__('Basic Settings', 'get-url-cron')."</h1>";
	echo '<form method="post" action="admin.php?page=geturlcrondetailsettingslug">';
    wp_nonce_field( "geturlcron_nc", "geturlcron_nc" );
	submit_button();
	echo '<table class="widefat striped">';
	echo '<input type="hidden" name="subaction" value="settings">';
	settings_fields( 'geturlcron-options-details' ); 
	do_settings_sections( 'geturlcron-options-details' ); 

	echo "<tr><td>";
	echo "<h2>".__("E-Mailadress for Statusmessages: separate multiple by space or , or ;","get-url-cron")."</h2>";
	$mailadr = trim(get_option('geturlcron-emailadr'));
	echo $this->geturlcron_check_mailadress_list($mailadr);
	echo '<input type=text size=200 name=geturlcron-emailadr value="'.$mailadr.'">';
	echo "</td></tr>";
	echo "<tr><td>";
	echo "<h2>".__("Set timeout","get-url-cron")."</h2>";
	echo __("Set the timeout for the http-requests (default 60 sec):","get-url-cron")."<br>";
	$timeout = trim(get_option('geturlcron-timeout'));
	if (!($timeout>0)) {
		$timeout = "60";
	}
	echo '<input type=text size=5 name=geturlcron-timeout value="'.htmlentities($timeout).'">';
	echo "</td></tr>";
	echo "<tr><td>";
	echo "<h2>".__("Max. age of logentries","get-url-cron")."</h2>";

    echo __("Delete Logfile-Entires older than days:","get-url-cron")."<ul><li>".__("-1 : delete logfile and do not log","get-url-cron").
		"</li><li>".__("0 : do not log but keep existing log","get-url-cron")."</li><li>".__("any number : max. age in days of the logfile-entries, default is 20 days","get-url-cron")."</li></ul>";

	$deldays = trim(get_option('geturlcron-dellog-days'));
	echo '<input type=text size=5 name=geturlcron-dellog-days value="'.htmlentities($deldays).'">';
	echo "</td></tr>";
	echo "<tr><td>";
	
	echo "<h2>".__("Max. number of Cronjobs (default and minimal: 15)","get-url-cron")."</h2>";
	$geturlcronmaxnocronjobs = (int) trim(get_option('geturlcron-maxno-cronjobs'));
	echo '<input type=text size=5 name=geturlcron-maxno-cronjobs value="'.htmlentities($geturlcronmaxnocronjobs).'">';
	echo "</td></tr>";

	echo "<tr><td>";
	
	echo "<h2>".__("Complete delete when uninstalling?","get-url-cron")."</h2>";
    echo __("On default, not all data of this plugin is deleted:","get-url-cron");
    echo __("On default, not all data of this plugin is deleted:","get-url-cron");
    echo __("Only if the following checkbox is activated, also templates and the above option-data are deleted","get-url-cron")."<br>";
	$checkeddelall = "";
	if (get_option('geturlcron-uninstall-deleteall') == 1) {
		$checkeddelall = "checked=checked";
	}
	
    echo '<input type="checkbox" name="geturlcron-uninstall-deleteall" value="1" '.$checkeddelall.' /> delete all, incl. logfiles';

	echo "</td></tr>";
	echo "<tr><td>";

	echo "<h2>".__("Example","get-url-cron")."</h2>";
    echo __("For trying the plugin you might use a URL like this one:","get-url-cron")."<br>";
	$exampleurl = "http://worldtimeapi.org/api/timezone/Europe/Berlin";
	echo '<a href="'.$exampleurl.'" target="_blank">'.$exampleurl.'</a><br>';	
	echo "<ul>";
	echo "<li>1. ".__("Select JSON as requiredformat and 'timezone' as requiredjsonfield","get-url-cron")."</li>";
	echo "<li>2. ".__("Save Settings","get-url-cron")."</li>";
	echo "<li>3. ".__("Then executing the CronJob by clicking 'execute job'","get-url-cron")."</li>";
	echo "<li>4. ".__("Switching to 'Show Logs' should show you the results","get-url-cron")."</li></ul>";
	
	echo "</td></tr>";
	echo "</table>";
	submit_button();
	echo "</form>";
}

public function geturlcron_menu() {
	add_menu_page(__('Get URL Cron','get-url-cron'), 'Get URL Cron', 'administrator', 'unique_geturlcron_menu_slug', array($this, 'geturlcron_settings_page'), 'dashicons-clock');
	add_submenu_page('unique_geturlcron_menu_slug', __('Set CronJobs','get-url-cron'), __('Set CronJobs','get-url-cron'), 'administrator', 'geturlcronsettingspage', array($this, 'geturlcron_settings_page'));
	add_submenu_page('unique_geturlcron_menu_slug', __('Show CronJobs','get-url-cron'), __('Show CronJobs','get-url-cron'), 'administrator', 'geturlcronjobslistdslug', array($this, 'geturlcron_cronjobs_page'));
	add_submenu_page('unique_geturlcron_menu_slug', __('Show Logs','get-url-cron'), __('Show Logs','get-url-cron'), 'administrator', 'geturlcronlogslug', array($this, 'geturlcron_logs_page'));
	add_submenu_page('unique_geturlcron_menu_slug', __('Basic Settings','get-url-cron'), __('Basic Settings','get-url-cron'), 'administrator', 'geturlcrondetailsettingslug', array($this, 'geturlcron_detailsettings_page'));
	remove_submenu_page('unique_geturlcron_menu_slug', 'unique_geturlcron_menu_slug');
	add_action( 'admin_init', array($this, 'register_geturlcronsettings' ));
}	


public function geturlcron_logs_page() {
	$logfile = $this->geturlcron_getlogfile();
	$deldays = trim(get_option('geturlcron-dellog-days'));
	if ($deldays==-1) {
		@unlink($logfile);
		echo '<h1>'.__("Logfile deleted!",'get-url-cron').'</h1>'.__('See settings and check "Delete Logfile-Entires older than": "-1" means delete logfile','get-url-cron');
		return TRUE;
	}
	echo "<h1>".__("Logs",'get-url-cron').", ".__("Current time on this server:",'get-url-cron')." ".date("Y-m-d, H:i:s")."</h1>";

	
	if (!file_exists($logfile)) {
		echo __("emtpy logfile up to now...",'get-url-cron');
		return TRUE;
	}
	$logf = file_get_contents($logfile);
	$separator = " /// ";
	$separator2 = "=";
	
	#echo $logf; return true;
	$logfArr1 = explode("\n",$logf);
	
	#echo count($logfArr1); return true;
	$outhead = '<table class="widefat" border=1>';
		$outhead .= "<tr bgcolor=yellow>";
		$outhead .= "<td>";
		$outhead .= __("id of run",'get-url-cron');
		$outhead .= "</td><td>";
		$outhead .= __("status",'get-url-cron');
		$outhead .= "</td><td>";
		$outhead .= __("log entry",'get-url-cron');
		$outhead .= "</td><td>";
		$outhead .= __("retires",'get-url-cron');
		$outhead .= "</td><td>";
		$outhead .= __("json status",'get-url-cron');
		$outhead .= "</td><td>";
		$outhead .= __("runtime (sec)",'get-url-cron');
		$outhead .= "</td><td>";
		$outhead .= __("URL or WP-Shortcode",'get-url-cron');
		$outhead .= "</td><td>";
		$outhead .= __("response",'get-url-cron');
		$outhead .= "</td>";
		$outhead .= "</tr>";
		$deldays = trim(get_option('geturlcron-dellog-days'));
		$count = 1000;
		$logfArr11 = array();
		$statuscheck = array();
		
	for ($r = count($logfArr1); $r >=0; $r--) {
		if (empty($logfArr1[$r])) {
			continue;
		}
		$logfArr2 = explode($separator , $logfArr1[$r]);
		#echo "<hr>$r  -- ".$logfArr1[$r]."<hr>";
		$id = $logfArr2[0];
		$status = $logfArr2[8];
		$statkey = trim($id)."-".trim($status);
		@$statuscheck[$statkey]++;
		#echo $statkey."-".strlen($statkey)."<br>";
		$tArr = explode("time=", $logfArr1[$r]);
		$tArr1 = explode(" ", $tArr[1]);

		$timestampoflogentry = $tArr1[0];



		$deleteentry = FALSE;
		if ($deldays>0) {
			if ($tArr1[0]<10) {
				$deleteentry = TRUE;
			} else {
				$ageofentryindays = (time()-$tArr1[0])/86400;
				if ($ageofentryindays>$deldays) {
					$deleteentry = TRUE;
				}
			}
			if ($deleteentry) {
				#echo "DEL: ".$ageofentryindays." - $deldays <hr>";
			} else {
				#echo "OK:  ".$ageofentryindays." - $deldays <hr>";
				$logfArr11[$timestampoflogentry."-".$count] = $logfArr1[$r];
				$count++;
			}
		} else {
			$logfArr11[$timestampoflogentry."-".$count] = $logfArr1[$r];
			$count++;
		}
	}
	
	
	ksort($logfArr1);
	
	if ($deleteentry) {
		$newlogfile = join("\n", $logfArr11);
		$fsc = file_put_contents($logfile, $newlogfile."\n");
	}
	
	echo $outhead;
	#for ($r = count($logfArr11); $r >=0; $r--) {
	foreach($logfArr1 as $key => $val) {	
		if (empty($val)) {
			continue;
		}
		$logfArr2 = explode($separator, $val);
		if (empty($logfArr2[1])) {
			continue;
		}
		$lga3 = explode($separator2, $logfArr2[1],2);
		if (($lga3[1]==0) || count($logfArr2)==0) {
			continue;
		}
		
		$id = trim($logfArr2[0]);
		$status = trim($logfArr2[8]);
		$bgcol = "#ffffff"; 
		if ($status=="try" && @$statuscheck[$id."-fail"]>0) {
			$bgcol = "#ffa099"; 
		}
		#echo $id."-OK"." : ".strlen($id."-OK")." : ".$statuscheck[$id."-OK"]."<br>";
		if ($status=="try") {
		#if ($status=="try" && $statuscheck[$id."-OK"]>0) {
			$bgcol = "#9edeaa"; 
		}
		if ($status=="OK" && $statuscheck[$id."-try"]>0) {
			$bgcol = "#9edeaa"; 
		}
		if ($status=="fail") {
			$bgcol = "#ffa099"; 
		}
		if ($status=="schedule") {
			$bgcol = "#ffbb00"; 
		}

		echo "<tr bgcolor=$bgcol>";
		echo "<td>";
		echo $id;
		echo "</td><td>";
		echo $status;
		echo "</td><td>";
		echo date("Y-m-d, H:i:s", $lga3[1]);
		echo "</td><td>";
		$lga43 = explode($separator2, $logfArr2[3],2);
		echo $lga43[1];
		echo "</td><td>";
		echo $logfArr2[4];
		echo "</td><td>";
		$lga43 = explode($separator2, $logfArr2[6],2);
		echo $lga43[1];
		echo "</td><td>";
		$lga43 = explode($separator2, $logfArr2[5],2);
		echo stripslashes($lga43[1]);
		echo "</td><td>";
		$lga43 = explode($separator2, $logfArr2[7],2);
		$lga43[1] = chunk_split($lga43[1], 200, ' '); # insert blank every 200char for linebreaks
		echo esc_html($lga43[1]);
		echo "</td>";
		echo "</tr>";
	}
	echo "</table>";
	return TRUE;
}

public function geturlcron_cronjobs_page() {
	$cjArr = _get_cron_array();
	if ( empty( $cjArr ) ) {
		$cjArr = array();
	}
	#echo var_Dump($cjArr);			echo "<hr>";
	$out = "";
	$outelse = "";
	$plugincronjobs = 0;
	$nonplugincronjobs = 0;
	foreach($cjArr as $k => $v) {
		foreach($v as $k1 => $v1) {
			#echo $k1."<br>";			var_Dump($v1);			echo "<hr>";
			$noofjob = preg_replace("/geturlcron_event-/", "", $k1);
			$showcronjob = TRUE;
			$op = get_option("geturlcron-url-".$noofjob);
			if ($this->is_relative_url($op)) {
				# relative path
				$op = $this->add_domain_to_url($op);
			}
			if (empty($op)) {
				$jobhook = "geturlcron_event-".$noofjob;
				wp_unschedule_event( "", $jobhook );
				wp_clear_scheduled_hook( $jobhook );
				$showcronjob = FALSE;
			}
			if ($noofjob>=1 && $showcronjob) {
				foreach($v1 as $k2 => $v2) {
					$intv = $v2["schedule"];
					if (empty($intv)) {
						$intv = __("run only once",'get-url-cron');
					}
				}
				$plugincronjobs++;
				$out .= "<tr>";
				$out .= "<td>";
				$out .= $k1;
				$out .= "</td>";
				$out .= "<td>";
				$opout = trim(stripslashes($op));
				if (preg_match("/^\[/",$opout)) {
					$out .= __("execute Shortcode",'get-url-cron').": $opout";
				} else {
					$out .= "<a href=$op target=_blank>".$opout."</a>";
				}
				$out .= "</td>";
				$out .= "<td>";
				$cronschedulesArr = wp_get_schedules();
				$out .= $cronschedulesArr[$intv]['display'];# $intv;
				$out .= "</td>";
				$out .= "<td>";
				$std = get_option("geturlcron-startdate-".$noofjob);
				$out .= "$std</td>";
				$nexttime = wp_next_scheduled($k1);
				$out .= "<td>";
				$nextdate = "";
				$nextdist = "";
				if ($nexttime>0) {
					$nextdate = date("Y-m-d, H:i", $nexttime);
					$nextdistVal = $nexttime-time();
					if ($nextdistVal>0) {
						$nextdist_day = floor($nextdistVal/(3600*24));  # from sec to days	
						$remainsec = $nextdistVal-$nextdist_day*(3600*24);			
						$nextdist_hr = floor($remainsec/3600);  # from sec to hrs					
						$remainsec = $remainsec-$nextdist_hr*3600;			
						$nextdist_min = floor($remainsec/60);  # from sec to min					
						$nextdist_sec = $remainsec-$nextdist_min*60;  # remaining sec
						
						$nextdist = "";
						if ($nextdist_day>0) {
							$nextdist .= "$nextdist_day ";			
							if ($nextdist_day>1) {
								$nextdist .= "days ";			
							} else {
								$nextdist .= "day ";			
							}
						}

						if ($nextdist_hr>0) {
							$nextdist .= "$nextdist_hr ";			
							if ($nextdist_hr>1) {
								$nextdist .= "hours ";			
							} else {
								$nextdist .= "hour ";			
							}
						}

						if ($nextdist_min>0) {
							$nextdist .= "$nextdist_min ";			
							if ($nextdist_min>1) {
								$nextdist .= "minutes ";			
							} else {
								$nextdist .= "minute ";			
							}
						}
						$nextdist .= "$nextdist_sec seconds";			
						
						#$nextdist = "$nextdist_day d : $nextdist_hr hrs: $nextdist_min min: $nextdist_sec sec";			
						$out .= "$nextdate<br>$nextdist</td>";
						#$out .= "<td>$nextdist</td>";
					} else {
						$out .= "<b>reload this page please</b></td>";
						$out .= "<td>";
						$out .= "</td>";
					}
				} else {
					$out .= "$nextdate</td>";
					$out .= "<td>";
					$out .= "$nextdist</td>";
				}
				$out .= "</tr>";
			} else {
				# other cronjobs
				
				$recurrence = "";
				$args = "";
				$argArr = array();
				foreach($v1 as $k2 => $v2) {
					$recurrence = $v2["schedule"];
					$args = json_encode($v2["args"]);
					$argArr = $v2["args"];
				}
				if ($recurrence=="") {
					$recurrence = __('Not repeating','get-url-cron');
				}

				$eventdetails = wp_get_scheduled_event($k1, $argArr);
				#echo $k1."<br>";			var_Dump($eventdetails);			echo "<hr>";
				$nexttime = $eventdetails->timestamp;
				
				$nonplugincronjobs++;
				$outelse .= "<tr>";
				$outelse .= "<td>";
				$outelse .= $k1;
				$outelse .= "</td>";
				$outelse .= "<td>";
				
				$nextdate = "";
				$nextdist = "";
				if ($nexttime>0) {
					$nextdate = date("Y-m-d, H:i:s", $nexttime);
					$nextdistVal = $nexttime-time();
					$nextdist_day = floor($nextdistVal/(3600*24));  # from sec to days	
					$remainsec = $nextdistVal-$nextdist_day*(3600*24);			
					$nextdist_hr = floor($remainsec/3600);  # from sec to hrs					
					$remainsec = $remainsec-$nextdist_hr*3600;			
					$nextdist_min = floor($remainsec/60);  # from sec to min					
					$nextdist_sec = $remainsec-$nextdist_min*60;  # remaining sec
					
					#4 days 1 hour
					#23 minutes 25 seconds
					$nextdist = "";
					if ($nextdist_day>0) {
						$nextdist .= "$nextdist_day ";			
						if ($nextdist_day>1) {
							$nextdist .= "days ";			
						} else {
							$nextdist .= "day ";			
						}
						
					}

					if ($nextdist_hr>0) {
						$nextdist .= "$nextdist_hr ";			
						if ($nextdist_hr>1) {
							$nextdist .= "hours ";			
						} else {
							$nextdist .= "hour ";			
						}
						
					}

					if ($nextdist_min>0) {
						$nextdist .= "$nextdist_min ";			
						if ($nextdist_min>1) {
							$nextdist .= "minutes ";			
						} else {
							$nextdist .= "minute ";			
						}
						
					}
					$nextdist .= " $nextdist_sec seconds";			
				}
				$outelse .= $nextdate."<br>$nextdist";
				$outelse .= "</td>";
				$outelse .= "<td>";
				$outelse .= $recurrence;
				$outelse .= "</td>";
				$outelse .= "<td>";
				$outelse .= $args;
				$outelse .= "</td>";
				$outelse .= "</tr>";
			}
		}
	}
	if ($plugincronjobs==0) {
		$out .= "<tr><td colspan=6>".__('No Cronjob defined with this Plugin','get-url-cron')."</td></tr>";
	}
	$out .= "</table>";
	
	$outelse .= "</table>";
	
	$outhead = '<h1>'.$plugincronjobs." ".__('Cronjobs defined by this Plugin:','get-url-cron').'</h1>';
	#if (defined('DISABLE_WP_CRON') && DISABLE_WP_CRON) {
	#	$outhead .= '<h2><font color=red>In case of problems: The Wordpress-Cron is disabled! Check wp_config.php and set <i>define(\'DISABLE_WP_CRON\',false);</i> there, please!</font></h2>';
	#}
	$outhead .= '<table class="widefat striped">';
	$outhead .= "<tr><td bgcolor=yellow>".__("Cronjob",'get-url-cron')."</td><td bgcolor=yellow>".__("URL or WP-Shortcode",'get-url-cron').
		"</td><td bgcolor=yellow>".__("Recurrence",'get-url-cron')."</td><td bgcolor=yellow>".__("First Run",'get-url-cron').
		"</td><td bgcolor=yellow>".__("Next Run",'get-url-cron')."</td>";
		
	#$outhead .= "<td bgcolor=yellow>".__("Distance to next Run (day, hrs, min, sec)",'get-url-cron')."</td>";
	$outhead .= "</tr>";
	$outheadelse = '<h1>'.$nonplugincronjobs.' '.__("other Cronjobs:",'get-url-cron').'</h1><table class="widefat striped">';
	$outheadelse .= "<tr><td bgcolor=yellow>".__("Cronjob",'get-url-cron').
		"</td><td bgcolor=yellow>".__("Next Run",'get-url-cron')."</td>".
		"</td><td bgcolor=yellow>".__("Recurrence",'get-url-cron')."</td>".
		"</td><td bgcolor=yellow>".__("Arguments",'get-url-cron')."</td>".
		"</tr>";
	echo $outhead.$out;
	echo $outheadelse.$outelse;
	echo "<hr><h2>".__("Current time on this server:",'get-url-cron')." ".date("Y-m-d, H:i:s")."</h2>";
	echo __("All times like time and distance of the next run are calculated with this time!",'get-url-cron');
}


public function geturlcron_settings_page() {
	echo '<h1>'.__("Get URL Cron Jobs:",'get-url-cron').'</h1>';
	echo '<form method="post" action="admin.php?page=geturlcronsettingspage">';
    wp_nonce_field( "geturlcron_nc", "geturlcron_nc" );
	submit_button();
		echo '<input type="hidden" name="subaction" value="savecronjobs">';
		settings_fields( 'geturlcron-options' ); 
		do_settings_sections( 'geturlcron-options' ); 
		$fi = self::$fi;
		$fi_size = self::$fi_size;

		$fi_out = array(
			"url" => __("URL", 'get-url-cron'),
			"interval" => __("Recurrence", 'get-url-cron'),
			"startdate" => __("First Run (year-mon-day hr:min:sec)", 'get-url-cron'),
			"retries" => __("Retries", 'get-url-cron'),
			"requiredformat" => __("Required format", 'get-url-cron'),
			"requiredjsonfield" => __("Required JSON field", 'get-url-cron'),
			"sendmail" => __("Sendmail", 'get-url-cron'),
		);
		
		$reqformatArr = self::$reqformatArr;
		
		echo '<table class="widefat striped">';
		echo "<tr>";
		echo "<td bgcolor=yellow>";
		echo __("No",'get-url-cron');
		echo "</td>";
		foreach($fi as $k => $v) {
			echo "<td bgcolor=yellow>";
			if ($k=="url") {
				echo __("URL or WP-Shortcode (if URL starts with \"/\" the Sitedomain is added in the Cronjob-URL)",'get-url-cron');
			} else {
				echo $fi_out[$k];
				if ("startdate"==$k) {
					echo "<br>".__("Current Servertime", 'get-url-cron').": ".date("Y-m-d H:i:s");
				}
			}
			echo "</td>";
		}
		echo "<td bgcolor=yellow>";
		echo __("Execute Job",'get-url-cron');
		echo "</td>";
		echo "</tr>";
		
		for ($r = 1; $r <= $this->nooffields; $r++) {
			echo "<tr>";
			echo "<td>";
			echo $r;
			echo "</td>";
			foreach($fi as $k => $v) {
				echo "<td>";
				$ki = "geturlcron-".$k."-".$r;
				$op = get_option($ki);
				if ($k=="interval") { 
					$cronschedulesArr = wp_get_schedules();
					#print_r($cronschedulesArr);exit;

					if ($op=="") { $op = "daily"; }
					echo "<select name=$ki>";
					$scArr_display = array();
					$scArr_key = array();
					foreach($cronschedulesArr as $csk => $csv) {
						$scArr_display[$csv["interval"]] = $csv["display"];
						$scArr_key[$csv["interval"]] = $csk;
					}
					ksort($scArr_key, SORT_NUMERIC);


					foreach($scArr_key as $csk => $csv) {
						$csel = "";
						if ($op==$csv) {
							$csel = " selected ";
						}
						echo "<option value=".$csv." $csel>".$scArr_display[$csk];
					}
					echo "</select>";
				} else if ($k=="requiredformat") {
					echo "<select name=$ki>";
					foreach($reqformatArr as $csk => $csv) {
						$csel = "";
						if ($op==$csk) {
							$csel = " selected ";
						}
						echo "<option value=".$csk." $csel>$csv ";
					}
					echo "</select>";
				} else if ($k=="retries") {
					echo "<select name=$ki>";
					for ($rr = 1; $rr <= 10; $rr++) {
						$csel = "";
						if ($op==$rr) {
							$csel = " selected ";
						}
						echo "<option value=".$rr." $csel>$rr ";
					}
					echo "</select>";
				} else if ($k=="sendmail") {
					$sel = "";
					if ($op=="yes" || (!isset($op))) {
						$csel = " checked ";
					}
					echo "<input type=checkbox $csel name=$ki value=yes \>";
				} else {
					$placeholder = "";
					$inputtype = "text";
					if ($k=="startdate") {
						$placeholder = date("Y-m-d H:i"); #"YYYY-MM-DD hh:mm:ss";
						$inputtype = "datetime-local";
					}
					if ($k=="url") {
					$placeholder = __("http... OR /path... OR [shortcode id...]",'get-url-cron');
					}
					$opout = stripslashes($op);
					echo ' <input type="'.$inputtype.'" placeholder="'.$placeholder.'" name="'.$ki.'" value="'.esc_html($opout).'" size='.$fi_size[$k].'>';
					#echo '<input type=text placeholder="'.$placeholder.'" name="'.$ki.'" value="'.$op.'" size='.$fi_size[$k].'>';
				}
				echo "</td>";
			}
			echo "<td>";
			$nonce = wp_create_nonce( 'getcronurl' );
			$url = "?page=unique_geturlcron_menu_slug&action=geturlcron&no=$r&hash=$nonce";
			echo "<a href=$url>".__("execute job",'get-url-cron')."</a>";
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
		submit_button(); 
		echo "</form>";
}
	
public function register_geturlcronsettings() {
	register_setting( 'geturlcron-options-details', 'geturlcron-emailadr' );
	register_setting( 'geturlcron-options-details', 'geturlcron-timeout' );
	register_setting( 'geturlcron-options-details', 'geturlcron-uninstall-deleteall' );
	register_setting( 'geturlcron-options-details', 'geturlcron-dellog-days' );
	register_setting( 'geturlcron-options-details', 'geturlcron-maxno-cronjobs' );
	#$this->nooffields = $this->geturlcron_getnooffields();
	for ($r = 1; $r <= $this->nooffields; $r++) {
		register_setting( 'geturlcron-options', 'geturlcron-url-'.$r );
		register_setting( 'geturlcron-options', 'geturlcron-interval-'.$r );
		register_setting( 'geturlcron-options', 'geturlcron-startdate-'.$r );
		register_setting( 'geturlcron-options', 'geturlcron-retries-'.$r );
		register_setting( 'geturlcron-options', 'geturlcron-requiredjsonfield-'.$r );
		register_setting( 'geturlcron-options', 'geturlcron-requiredformat-'.$r );
		register_setting( 'geturlcron-options', 'geturlcron-sendmail-'.$r );
	}
}	

	private function is_relative_url($url) {
		if (preg_match("/^\//", $url)) {
			return TRUE;
		}
		return FALSE;
	}
	
	private function add_domain_to_url($url) {
		return home_url().$url;
	}
	
	private function geturlcron_set_urlSettingsarr() {
		for ($r = 1; $r <= $this->nooffields; $r++) {
			foreach(self::$fi as $k => $v) {
				$ki = "geturlcron-".$k."-".$r;
				$op = get_option($ki);
				if ($k=="url" && $this->is_relative_url($op)) {
					$op = $this->add_domain_to_url($op);
				}
				$this->urlSettingsArr[$r][$k] = $op;
			}
		}
	}

	private function geturlcron_set_cronjoboptions() {
		for ($r = 1; $r <= $this->nooffields; $r++) {
			foreach(self::$fi as $k => $v) {
				$ki = "geturlcron-".$k."-".$r;
				$op = update_option($ki, $this->geturlcron_handlePost_input($ki));
			}
		}
	}

	public function geturlcron_add_action_cronjob() {
					$cfArr = explode("-", current_filter());
					$no = trim($cfArr[1]); 
					if (!empty($this->urlSettingsArr[$no])) {
						$urltouse = $this->urlSettingsArr[$no];
						$this->geturlcron_executejob($urltouse, $no); 
					}
	}

	public function geturlcron_activatejobs() {
		for ($nol = 1; $nol <= count($this->urlSettingsArr); $nol++) {
			if (!empty($this->urlSettingsArr[$nol])) {
				$urltouse = $this->urlSettingsArr[$nol];
				$this->geturlcron_schedule($urltouse, $nol);
			}
		}
	}

	public function geturlcron_check_mailadress_list($mailadress_list) {
		$mailadr = preg_replace("/[, ]/", ";", $mailadress_list);
		$mailadrArr = preg_split("/[,; ]/", $mailadr);
		$out = "";
		foreach( $mailadrArr as $k => $v) {
			if (filter_var($v, FILTER_VALIDATE_EMAIL)) {
					$out .= __("OK:",'get-url-cron')." $v<br>";
			} else {
					if (!empty($v)) {
						$out .= "<font color=red>".__("CHECK E-Mailadress please:",'get-url-cron')." $v</font><br>";
					}
			}
		}
		return $out;
	}
	
	public function geturlcron_action_handle() {
		$this->subaction = $this->geturlcron_handlePost_input("subaction");
		if ("settings"==$this->subaction) {
			# there must be a valid nonce to save 
			$nonceCheck = wp_verify_nonce( $_REQUEST['geturlcron_nc'], "geturlcron_nc" );
			if ($nonceCheck) {
				$input_geturlcron_timeout = $this->geturlcron_input_integer($this->geturlcron_handlePost_input("geturlcron-timeout"), 60, TRUE, TRUE, TRUE);
				update_option("geturlcron-timeout", $input_geturlcron_timeout);
			
				$input_geturlcron_dellog_days = $this->geturlcron_input_integer($this->geturlcron_handlePost_input("geturlcron-dellog-days"), 20, TRUE, FALSE, TRUE);
				update_option("geturlcron-dellog-days", $input_geturlcron_dellog_days);
			
				$input_geturlcron_maxno_cronjobs = $this->geturlcron_input_integer($this->geturlcron_handlePost_input("geturlcron-maxno-cronjobs"), 15, TRUE, FALSE, TRUE);
				update_option("geturlcron-maxno-cronjobs", $input_geturlcron_maxno_cronjobs);
						
				$input_geturlcron_emailadr = sanitize_email($this->geturlcron_handlePost_input("geturlcron-emailadr"));
				update_option("geturlcron-emailadr", $input_geturlcron_emailadr);
			
				$input_geturlcron_uninstall_deleteall = $this->geturlcron_handlePost_input("geturlcron-uninstall-deleteall");
				if (1!=$input_geturlcron_uninstall_deleteall) {
					$input_geturlcron_uninstall_deleteall  = 0;
				}
				update_option("geturlcron-uninstall-deleteall", $input_geturlcron_uninstall_deleteall);
			} else {
				return TRUE;
			}
		}
		if ("savecronjobs"==$this->subaction) {
			# there must be a valid nonce to save 
			$nonceCheck = wp_verify_nonce( $_REQUEST['geturlcron_nc'], "geturlcron_nc" );
			if ($nonceCheck) {
				$this->geturlcron_unschedulejobs();
				# create all jobs
				$this->geturlcron_set_cronjoboptions();
				$this->geturlcron_set_urlSettingsarr();
				$this->geturlcron_activatejobs();
			} else {
				return TRUE;
			}
		}
		
		$this->action = $this->geturlcron_handleGet_input("action");
		if ("geturlcron"==$this->action) {
			$noncecheckok = wp_verify_nonce($_REQUEST["hash"], "getcronurl"); 
			if (!$noncecheckok) {
				return TRUE;
			}
			$noin = $_GET["no"] ?? null;
			if (is_null($noin)) {
				return TRUE;
			}
			$no = sanitize_text_field($noin);
			if (is_numeric($no) && $no>0) {
				$this->geturlcron_singlerun($no);
			}
		}
		return TRUE;
	}

	private function geturlcron_input_integer($input, $defaultvalue, $isnumeric, $ispositive, $round) {
		if ($isnumeric && !is_numeric($input)) {
			$input = $defaultvalue;
		}
		if ($ispositive && $input<=0) {
			$input = $defaultvalue;
		}
		if ($round && $input!=round($input)) {
			$input = round($input+0.5);
		}
		return $input;
	}
	
	private function geturlcron_handlePost_input($postparm) {
		$pp = "";
		$ppin = $_POST[$postparm] ?? null;
		if (is_null($ppin)) {
			return "";
		}		
		if (isset($ppin)) {
			$ppval = $ppin;
			if (preg_match("/^geturlcron-startdate-/", $postparm)) {
				# needed format: 2022-12-14 22:25:38
				$ppval = preg_replace("/T/", " ",  $ppval);
			}
			$pp = sanitize_text_field($ppval);
		}
		return $pp;
	}
	private function geturlcron_handleGet_input($postparm) {
		$ppin = $_GET[$postparm] ?? null;
		if (is_null($ppin)) {
			return "";
		}
		$pp = sanitize_text_field($ppin);
		if ("geturlcron"!=$pp) {
			return "";
		}
		return $pp;
	}


	private function geturlcron_getschedule_interval($urlArr, $no) {
		$autoadd = "";
		$retVal = array();
		$schedurl = trim($urlArr["url"]);
		if (empty($schedurl)) {
			return $retVal;
		}
		$sedeuleofurl = $urlArr["interval"];
		$timefirstexec = strtotime($urlArr["startdate"]);
		if (empty($timefirstexec)) {
			$timefirstexec = current_time( 'timestamp' );
		}
		if (time()>$timefirstexec) {
			# first run in past: set to next
			$schedules = wp_get_schedules();
			$secintv = $schedules[ $sedeuleofurl ][ 'interval' ];  ## interval in seconds
			if ($secintv>0) {
				$numberOfIntervalsTillNextExec = round(((time()-$timefirstexec)/$secintv) + 0.5);
			} else {
				$numberOfIntervalsTillNextExec = 0;
			}
			if ($secintv==-1) {
				return -1;
			}
			$nextExecTime = $timefirstexec + $numberOfIntervalsTillNextExec * $secintv;
			
			$autoadd .= __("interval in sec:",'get-url-cron')." $secintv, ";
			$autoadd .= __("firstexec:",'get-url-cron')." $timefirstexec (".$urlArr["startdate"]."), ";
			$autoadd .= __("numberOfIntervalsTillNextExec:",'get-url-cron')." $numberOfIntervalsTillNextExec, ";
			$autoadd .= __("nextExecTime:",'get-url-cron')." $nextExecTime (".date("Y-m-d, H:i:s", $nextExecTime)."), ";
		} else {
			# first run in the future
			$nextExecTime = $timefirstexec;
		}
		
		$retVal["sedeuleofurl"] = $sedeuleofurl;
		$retVal["autoadd"] = $autoadd;
		$retVal["timenextexec"] = $nextExecTime;
		
		return $retVal;
		
	}

	private function geturlcron_singlerun($no) {
		$logl = $this->geturlcron_log("", $this->urlSettingsArr[$no]["url"], "", "", "", "", "manually started");
		$this->geturlcron_savelog($logl);
		wp_schedule_single_event( time(), 'geturlcron_event-'.$no);
	}	


	private function geturlcron_schedule($urlArr, $no) {
		$retVal = $this->geturlcron_getschedule_interval($urlArr, $no);
		if ($retVal==-1) {
			return TRUE;
		}
		if (!empty($retVal["sedeuleofurl"])) {
			$logl = $this->geturlcron_log("geturlcron-$no", $urlArr["url"], "", "interval: ".$retVal["sedeuleofurl"].", ".$retVal["autoadd"]." Next Run: ".date("Y-m-d, H:i:s", $retVal["timenextexec"]), "", "", "schedule");
			$this->geturlcron_savelog($logl);
			if (($retVal["timenextexec"]>0) && ("geturlcron_disable"!=$retVal["sedeuleofurl"])){
				#echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AC:  $no : ".print_r($retVal, true)."<br>";
				wp_schedule_event( $retVal["timenextexec"], $retVal["sedeuleofurl"], 'geturlcron_event-'.$no);
			} else {
				#echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NOT: $no : ".print_r($retVal, true)."<br>";
			}
		}
	}	

	private function geturlcron_log($idofrun, $url, $done_retries, $returnvalue, $info, $runtime, $status) {
		$separator = "#gucsep#";
		$separator = " /// ";
		$separator2 = "=";
		$logline = $idofrun.$separator."time".$separator2.time().$separator."date".$separator2.date("Y-m-d, H:i:s").$separator.
				"retries".$separator2.$done_retries.$separator.$info.$separator."url".$separator2.$url.$separator."runtime".$separator2.$runtime.$separator."json".$separator2.substr($returnvalue, 0 ,300).$separator.$status;
		$logline = preg_replace("/\n/", "", $logline);
		$logline = preg_replace("/\r/", "", $logline);
		return $logline;
	}


	private static function geturlcron_setlogfile() {
		#$plugincachepath = plugin_dir_path(__FILE__) . "logs";
		$ulp = wp_upload_dir();
		$plugincachepath = $ulp["basedir"]."/geturlcron";
		if (!is_dir($plugincachepath)) {
          $mkdirError = @mkdir($plugincachepath, 0777 , TRUE);
		}
		self::$logfile = $plugincachepath."/geturlcron-log.cgi";
	}
	public static function geturlcron_getlogfile() {
		return self::$logfile;
	}
	public function geturlcron_getnooffields() {
		$geturlcronmaxnocronjobs = (int) trim(get_option('geturlcron-maxno-cronjobs'));
		if ($geturlcronmaxnocronjobs < 15) {
			$this->nooffields = 15;
			return FALSE;
		}
		$this->nooffields = $geturlcronmaxnocronjobs;
		return TRUE;# $geturlcronmaxnocronjobs;
	}


	private function geturlcron_savelog($logline) {
		$logfile = $this->geturlcron_getlogfile();
		$fsc = file_put_contents($logfile, $logline."\n", FILE_APPEND);
	}

	private function geturlcron_getandcheckurl($url, $done_retries, $idofrun, $no) {
		$urlout = trim(stripslashes($url));
		$this->getcronurl_sendmail(__("TRY $done_retries: get",'get-url-cron')." $url, ".__("ID",'get-url-cron')." $idofrun", __("Try",'get-url-cron')." $done_retries:\n".__("get",'get-url-cron')." $urlout\n".__("ID",'get-url-cron')." $idofrun", $no);
		$logl = $this->geturlcron_log($idofrun, $urlout, $done_retries, __("start trying",'get-url-cron'), "", "", "try");
		$this->geturlcron_savelog($logl);

		$timeout = trim(get_option('geturlcron-timeout'));
		if (!($timeout>0)) {
			$timeout = "60";
		}
		$starttime = time();

		if (preg_match("/^\[/",$url)) {
			$sc = trim(stripslashes($url));
			$returnvalue = do_shortcode($sc);
			$resp = "shortcode"; # do_shortcode does not have an errorlevel
		} else {
			$args = array(
				'timeout'     	=> $timeout,
				'user-agent'	=> 'GetURLCron-Plugin',
				'sslverify' 	=> false
				);
			$response = wp_remote_get($url, $args);
			if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
				return $error_message;
			}
			$resp = wp_remote_retrieve_response_code($response); # http code
			$returnvalue = wp_remote_retrieve_body($response);
		}
		
		$endtime = time();
		$runtime = $endtime - $starttime;
		return array(
			"starttime" => $starttime,
			"endtime" => $endtime,
			"runtime" => $runtime,
			"returnvalue" => $returnvalue,
			"resp" => $resp,
		);
	}

	public function geturlcron_executejob($urlArr, $no) {
		$url = $urlArr["url"];
		if (empty($url)) {
			return TRUE;
		}
		$urlout = stripslashes($url);
				
		$retries = $urlArr["retries"];
		$overallok = FALSE;
		$done_retries = 1;

		$idofrun = md5(time().$url.rand());

		####
		$retArr = $this->geturlcron_getandcheckurl($url, $done_retries, $idofrun, $no);
		$returnvalue = $retArr["returnvalue"];
		$resp = $retArr["resp"];
		$runtime = $retArr["runtime"];
		$starttime = date("H:i:s", $retArr["starttime"]);
		$endtime = date("H:i:s", $retArr["endtime"]);

		#$message = " failedcheck \n";

		$checkArray = $this->geturlcron_checkresponse($urlArr, $returnvalue, $resp);
		if ($checkArray["requestok"]) {
		#if ($checkArray[""]) {
			$overallok = TRUE;
		} else { 
			#$message .= " fail detected \n";
			if ($retries>1) { # try again
				#$message .= " retry due to fail: done_retry is $done_retries \n";
				for ($r = 1; $r <= $retries; $r++) {
					#$message .= " retry run $r \n";
					$checkArray = $this->geturlcron_checkresponse($urlArr, $returnvalue, $resp);
					$done_retries++;
					$retArr = $this->geturlcron_getandcheckurl($url, $done_retries, $idofrun, $no);
					if ($checkArray["requestok"]) { # try ok
						#$message .= " retry run $r OK \n";
						$returnvalue = $retArr["returnvalue"];
						$resp = $retArr["resp"];
						$runtime = $retArr["runtime"];
						$starttime = date("H:i:s", $retArr["starttime"]);
						$endtime = date("H:i:s", $retArr["endtime"]);
						$overallok = TRUE;
						break;
					} else {
						#$message .= " retry run $r failed \n";
					}
				}
			} else {
				# no retry
			}
		}
	
		if ($overallok) {
			$status = __("OK",'get-url-cron')." ";
		} else {
			$status = __("FAIL",'get-url-cron');
		}
	
		$info = $status." ".$checkArray["info"];
		$cronname = "$no, ".__("retires:",'get-url-cron')." $done_retries, ".__("url:",'get-url-cron')." $urlout, ".__("result:",'get-url-cron')." ".$logl;

		$logl = $this->geturlcron_log($idofrun, $urlout, $done_retries, $returnvalue, $info, $runtime, $status);
		$this->geturlcron_savelog($logl);

		$subject = $status." $done_retries: ".__("get",'get-url-cron')." $urlout, ".__("ID",'get-url-cron')." $idofrun";

		$message = "\n--- ".__("Status",'get-url-cron').":\n";
		$message .= __("Status",'get-url-cron').": $status\n\n";
		$message .= __("Job",'get-url-cron').": $no\nURL: $urlout\n";
		$message .= __("ID",'get-url-cron').": $idofrun\n";
		$message .= __("retires",'get-url-cron').": $done_retries\n";
		$info4mail = $checkArray["info4mail"];
		$message .= "$info4mail\n";
		$message .= "\n--- ".__("Runtime",'get-url-cron').":\n";
		$message .= __("time for URL-get",'get-url-cron').": \n".$runtime." seconds\n";
		$message .= __("timewindow",'get-url-cron').":\n$starttime to $endtime\n";
		$this->getcronurl_sendmail($subject, $message, $no);
	}

	private function getcronurl_sendmail($subject, $message, $no) {
		$doSendFlag = $this->urlSettingsArr[$no]["sendmail"];
		if ("yes"==$doSendFlag) {
			$to = trim(get_option('geturlcron-emailadr'));
			$to = preg_replace("/[, ]/", ";", $to);
			if (!empty($to)) {
				$resmail = mail( $to , $subject , $message);
			}
		} else {
			/*
			$to = trim(get_option('geturlcron-emailadr'));
			$to = preg_replace("/[, ]/", ";", $to);
			if (!empty($to)) {
				$resmail = mail( $to , "do not send" , $message);
			}
			*/
		}
	}

	private function geturlcron_checkOnJSONfield($jsonArr, $fieldkeypath) {
		$jsonfieldfound = FALSE;
		$foundvalue = __("JSON-Fieldcheck in detail",'get-url-cron').":\n";

		$reqFieldArr = explode(".", $fieldkeypath);
		$jsonArrTmp = $jsonArr;
		
		foreach( $reqFieldArr as $reqFieldDir) {
			if ($reqFieldDir=="#LASTITEM#") {
				# data.#LASTITEM#.no
				$reqFieldDir = count($jsonArrTmp)-1;
			}
			$jsonArrTmp = $jsonArrTmp[trim($reqFieldDir)];
			$foundvalue .= __("check on",'get-url-cron')." $reqFieldDir : ".count($jsonArrTmp).__(" found items",'get-url-cron')."\n";
		}
		if (!empty($jsonArrTmp)) {
			$jsonfieldfound = TRUE;
			$foundvalue .= __("value found:",'get-url-cron')." ".print_R($jsonArrTmp, true)."\n";
		} else {
			$foundvalue .= __("NO value found",'get-url-cron').": ".__("check failed",'get-url-cron')."\n";
		}
		
		return array(
			"jsonfieldfound" => $jsonfieldfound,
			"foundvalue" => $foundvalue,
		);
	}

	private function geturlcron_checkresponse($urlArr, $returnvalue, $resp) {
		$reqok = FALSE;
		$jsonok = FALSE;
		$requestok = FALSE;
		$info = "";
		$info4mail = "\n--- ".__("Request",'get-url-cron').":\n";
		if (
			(200==$resp) ||  
			("shortcode"==$resp) 
			){
			# server answer: ok
			$reqok = TRUE;
			$info .= __("request ok-",'get-url-cron');
			$info4mail .= __("request ok",'get-url-cron').": $resp\n";
		} else {
			$info .= __("request: $resp",'get-url-cron');
			$info4mail .= __("request failed",'get-url-cron').": $resp\n";
		}
		if ($reqok) {
			$requiredformat = $urlArr["requiredformat"];
			if ($requiredformat=="json") { # check on json
				$info4mail .= "check on valid json\n";
				# json ok?
				$jsonArr = json_decode($returnvalue, TRUE);
				if (is_null($jsonArr)) {
					$info .= __("json invalid-",'get-url-cron');
					$info4mail .= __("json is invalid",'get-url-cron')."\n";
				} else {
					$info .= __("json valid-",'get-url-cron');
					$info4mail .= __("json is valid",'get-url-cron')."\n";
					$jsonok = TRUE;
					$requiredjsonfield = trim($urlArr["requiredjsonfield"]);
					if (empty($requiredjsonfield)) { 
						$requestok = TRUE;
						$info .= __("no check for required jsonfield-",'get-url-cron');
						$info4mail .= __("no check for required jsonfield",'get-url-cron')."\n";
					} else {
						$checkOnJSONArr = $this->geturlcron_checkOnJSONfield($jsonArr, $requiredjsonfield);
						if ($checkOnJSONArr["jsonfieldfound"]) { 
						#if (empty($jsonArr[$requiredjsonfield])) {
							$info .= __("required jsonfield ok-",'get-url-cron');
							$info4mail .= __("required jsonfield ok",'get-url-cron').": $requiredjsonfield\n";
							$info4mail .= __("found jsonvalue:",'get-url-cron')." ".$checkOnJSONArr["foundvalue"]."\n";
							$requestok = TRUE;
						} else {
							$info .= __("required jsonfield missing: $requiredjsonfield-",'get-url-cron');
							$info4mail .= __("required jsonfield missing",'get-url-cron').": $requiredjsonfield\n";
							$info4mail .= __("failed jsonvalue detection",'get-url-cron').":\n".$checkOnJSONArr["foundvalue"]."\n";
						}
					}
				}
			} else {
				# any format welcome, check on requiredfield
				$requiredjsonfield = trim($urlArr["requiredjsonfield"]);
				$info4mail .= __("check on string: requiredjsonfield",'get-url-cron')."\n";
				if (empty($requiredjsonfield)) { 
					$requestok = TRUE;
					$info .= __("no check for required string-",'get-url-cron');
					$info4mail .= __("no check for required string",'get-url-cron')."\n";
				} else {
					if (preg_match("/$requiredjsonfield/", $returnvalue)) {
						$info .= __("required string ok-",'get-url-cron');
						$info4mail .= __("required string ok",'get-url-cron').": $requiredjsonfield\n";
						$requestok = TRUE;
					} else {
						$info .= __("required string missing: $requiredjsonfield-",'get-url-cron');
						$info4mail .= __("required string missing",'get-url-cron').": $requiredjsonfield\n";
					}
				}
			}
		}
		return array(
			"info" => $info,
			"info4mail" => $info4mail,
			"reqok" => $reqok,
			"jsonok" => $jsonok,
			"requestok" => $requestok,
		);
}

	public function geturlcron_unschedulejobs() {
		for ($no = 1; $no <= count($this->urlSettingsArr); $no++) {
			$this->geturlcron_unschedulejob($no);
		}
	}
	
	public function geturlcron_unschedulejob($no) {
		$jobhook = "geturlcron_event-".$no;
		wp_unschedule_event( "", $jobhook );
		wp_clear_scheduled_hook( $jobhook );
		#echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; unsch:  $no  $jobhook<br>";
	}


	## scheduling intervals
	public function geturlcron_recurrence_interval( $schedules ) {
		$schedules['geturlcron_02_minutes'] = array(
            'interval'  => 60*2,
            'display'   => __('2 Minutes','get-url-cron')
		);
		$schedules['geturlcron_05_minutes'] = array(
            'interval'  => 60*5,
            'display'   => __('5 Minutes','get-url-cron')
		);
		$schedules['geturlcron_10_minutes'] = array(
            'interval'  => 60*10,
            'display'   => __('10 Minutes','get-url-cron')
		);
		$schedules['geturlcron_15_minutes'] = array(
            'interval'  => 60*15,
            'display'   => __('15 Minutes','get-url-cron')
		);
		$schedules['geturlcron_30_minutes'] = array(
            'interval'  => 60*30,
            'display'   => __('30 Minutes','get-url-cron')
		);
		$schedules['geturlcron_6_hours'] = array(
            'interval'  => 60*60*6,
            'display'   => __('6 Hours','get-url-cron')
		);
		$schedules['geturlcron_7_days'] = array(
            'interval'  => 60*60*24*7,
            'display'   => __('7 Days','get-url-cron')
		);
		$schedules['geturlcron_disable'] = array(
            'interval'  => -1,
            'display'   => __('Disable','get-url-cron')
		);
		return $schedules;
	}


	public static function initclass() {
		static $inst = null;
		if ( ! $inst ) {
			$inst = new GetUrlCron();
		}
		return $inst;

	}
}
GetUrlCron::initclass();
?>