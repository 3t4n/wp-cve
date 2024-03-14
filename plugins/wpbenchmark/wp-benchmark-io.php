<?php
/**
 * @package wp benchmark io
 */
/*
Plugin Name: WordPress Hosting Benchmark tool
Plugin URI: https://wordpress.org/plugins/wpbenchmark/
Description: Utility to benchmark and stresstest your Wordpress hosting server, its capabilities, speed and compare with other hosts.
Text Domain: 
Version: 1.4.1
Requires PHP: 5.6
Network: true
Author: Anton Aleksandrov
Author URI: https://wpbenchmark.io
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/



defined('ABSPATH') or die("No script kiddies please!");



class wp_benchmark_io {

	private static $settings;
	private static $plugin_option_name = "wp-benchmark-io-settings";

	private static $anonymize_after_options = array("at_once", "hour", "day", "week", "month", "never");
	private static $anonymize_after_options_titles = array("at_once"=>"At once, always anonymous", "hour"=>"After 1 hour", "day"=>"In one day", "week"=>"In one week", "month"=>"In one month", "never"=>"Never, keep it always");

	function __construct() {
		# self::$visiting_domain = $_SERVER["SERVER_NAME"];
	}

	public static function add_action_links ( $links ) {
		 $mylinks = array(
		 '<a href="' . admin_url( 'tools.php?page=wp_benchmark_io' ) . '" style="font-weight:bold;">Run benchmark</a>',
		 );
		return array_merge( $links, $mylinks );
	}

	public static function admin_init() {
		#$hook = is_multisite() ? 'network_' : '';

		# only run for administrator
		if (current_user_can("manage_options")) {
			register_activation_hook( __FILE__, array( 'wp_benchmark_io', 'plugin_activation' ) );
			register_deactivation_hook( __FILE__, array( 'wp_benchmark_io', 'plugin_deactivation' ) );
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array('wp_benchmark_io', 'add_action_links') );


			$hook="";
			add_action( "{$hook}admin_menu", array("wp_benchmark_io", "add_admin_menu" ) );
			add_action("admin_init", array("wp_benchmark_io", "execute_plugin"));
		}
	}
	public static function add_admin_menu() {
		#add_options_page( 'WP Benchmark tool', 'WP Benchmark tool', 'manage_options', 'wp_benchmark_io', array("wp_benchmark_io", "admin_menu_options") );
		add_management_page( 'WP Benchmark tool', 'WP Benchmark tool', 'manage_options', 'wp_benchmark_io', array("wp_benchmark_io", "admin_menu_options") );
	}

	public static function plugin_activation() {
		self::$settings = array(
			"show_on_board"=>0,
			"accept_terms"=>0,
			"gdpr_consent"=>0,
			"run_lite_tests"=>0,
			"skip_object_cache_tests"=>0,
			"anonymize_after"=>"day"			
		);
		update_option(self::$plugin_option_name, self::$settings);
	}
	public static function plugin_deactivation() {}



	public static function admin_menu_options() {
		# if ( !is_super_admin() )  {
		if (!current_user_can( 'administrator' )) {
			#global $current_user;
			#print("<pre>".print_r($current_user,true)."</pre>");

			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		# check permissions
		# if (current_user_can("install_plugins")) {

		if (current_user_can( 'administrator' )) {
			self::$settings = get_option(self::$plugin_option_name);


			if (isset($_POST["doa"]) && $_POST["doa"]=="save_settings" && wp_verify_nonce($_POST["_wpnonce"], "wp-benchmark-io-save-settings")) {
				# check_admin_referer("wp-benchmark-io-save-settings");

				self::$settings = array(
					"show_on_board"=>0,
					"accept_terms"=>0,
					"gdpr_consent"=>0,
					"run_lite_tests"=>0,
					"skip_object_cache_tests"=>0,
					"anonymize_after"=>"day"
				);

				if (isset($_POST["show_on_board"]))
					if ($_POST["show_on_board"]==1) self::$settings["show_on_board"] = 1;

				if (isset($_POST["accept_terms"]))
					if ($_POST["accept_terms"]==1) self::$settings["accept_terms"] = 1;

				if (isset($_POST["gdpr_consent"]))
					if ($_POST["gdpr_consent"]==1) self::$settings["gdpr_consent"] = 1;

				if (isset($_POST["run_lite_tests"]))
					if ($_POST["run_lite_tests"]==1) self::$settings["run_lite_tests"] = 1;

				if (isset($_POST["skip_object_cache_tests"]))
					if ($_POST["skip_object_cache_tests"]==1) self::$settings["skip_object_cache_tests"] = 1;

				if (isset($_POST["anonymize_after"])) {
					if (in_array($_POST["anonymize_after"], self::$anonymize_after_options))
						self::$settings["anonymize_after"] = $_POST["anonymize_after"];
				}

				
				update_option(self::$plugin_option_name, self::$settings);
			}


			if (!isset(self::$settings["skip_object_cache_tests"]))
				self::$settings["skip_object_cache_tests"] = 0;

			if (!isset(self::$settings["anonymize_after"]))
				self::$settings["anonymize_after"] = "day";

			# build select html
			$anonymize_select_html = "";
			foreach(self::$anonymize_after_options as $k) {
				$anonymize_select_html .= "<option value='".$k."'" . (($k==self::$settings["anonymize_after"])?" selected":"") . ">".self::$anonymize_after_options_titles[$k] . "</option>";
			}


			print("
			<style>
				h1 {
					line-height:25px;
				}
				.wpio-panel {
					border: 1px solid #e7e7e7;
					border-radius: 5px;
					border: 1px solid black;
					background: white;

					margin-right: 10px;
					margin-left: 10px;

					float:left;

				}

				.wpio-panel-result {
					width:420px;
					box-shadow: 0 0 20px rgba(60,60,60,0.1);
					margin-bottom: 20px;
				}

				.wpio-panel-title {
					background: lightgray;
					border-radius: 5px 5px 0px 0px;
					padding: 10px 15px;
					font-weight: bold;
					font-size: 1.3em;
					border-bottom: 1px solid #807c7c;
					text-align:center;
				}

				.wpio-panel-body {
					margin: 15px;
				}

				input.wpio-form-control {
					border-radius:5px;
				}

				.wpio-btn {
					border-width: 1px;
					padding: 5px 15px;
					font-size: 1.2em;
					border-radius: 5px;
					cursor:pointer;
					font-size:1em;
				}
				.wpio-btn-lg {
					font-size:1.5em;
					padding-top:10px;
					padding-bottom:10px;
					padding-right:30px;
					padding-left:30px;
				}

				.wpio-btn-success {
					border-color: #6db76d;					
					color: white;
					background: green;					
				}
				.wpio-btn-success:hover {
					background:forestgreen;
				}

				.wpio-btn-primary {
					border-color: darkblue;					
					color: white;
					background: royalblue;					
				}
				.wpio-btn-primary:hover {
					background:skyblue;
				}


				.wpio-btn-primary:disabled,
				.wpio-btn-primary[disabled] {
					background: lightblue;
					border-color: darkcyan;
					cursor:wait;
				}


				.wpio-btn-block {
					width:100%;
				}


				.wpio-table td {
					padding-bottom:3px;
					font-size:1.1em;
				}

				.wpio-table .title-cell {
					text-align:right;
					padding-right:10px;
					padding-top:6px;
					padding-bottom:6px;
					
				}
				


				.wpio-btn-success:disabled,
				.wpio-btn-success[disabled] {
					background: lightblue;
					border-color: darkcyan;
					cursor:wait;
				}

				.wpio-text-warning {
					color:darkyellow;
				}
				.wpio-text-success {
					color:darkgreen;
				}
				.wpio-text-danger {
					color:darkred;
				}




				.wpio-col-score02 {
					background:#f35448;
					color:white;
				}
				.wpio-col-score25 {
					background:#f88d36;
					color:white;
				}
				.wpio-col-score56 {
					background:#e7c62b;
					color:white;
				}
				.wpio-col-score67 {
					background:#c3d519;
					color:white;
				}
				.wpio-col-score78 {
					background:#81d519;
					color:white;
				}
				.wpio-col-score89 {
					background:#7dc516;
					color:white;
				}
				.wpio-col-score910 {
					background:#299c29;
					color:white;
				}



				span.wpio-progress {
					font-size: 0.9em;
					font-weight: lighter;
				}


				.wpio-progress-container {
					height: 20px;					
					border: 1px solid #6db76d;
					border-radius: 5px;
					background: #EEE;					
				}

				.wpio-progress-done {
					height: 100%;
					width: 0%;
					background: lightgreen;
					border-radius: 5px;
					text-align: center;
					color: darkgreen;

					transition-property: width;
					transition-duration: 2s;

				}

				.wpio-panel, .wpio-panel-body {
					transition-property:height,width,opacity;
					transition-duration: 2s;
				}


				.wpio-row * { box-sizing: border-box; }
				.wpio-row { min-width:300px; }
				.wpio-text-right { text-align:right; }

				.wpio-row:before, .wpio-row:after {
					content:\"\";
					display:table;
					clear:both;
				}

				[class*='wpio-col-'] {
					float:left;
					min-height:1px;
					width:10%;
					margin-top:5px;
					/*-- gutter --*/
					padding:5px;
				}
				
				.wpio-col-1 { width:10%; }
				.wpio-col-2 { width:20%; }
				.wpio-col-3 { width:30%; }
				.wpio-col-4 { width:40%; }
				.wpio-col-5 { width:50%; }
				.wpio-col-6 { width:60%; }
				.wpio-col-7 { width:70%; }
				.wpio-col-8 { width:80%; }
				.wpio-col-9 { width:90%; }


				.wpio-benchmark-title { 
					text-align:center; 
					font-size:1.2em; 
					border-bottom: 1px dashed lightgray;
					padding-bottom: 10px;
					margin-bottom: 10px;
				}

				.result-table td {
					text-align:right;
				}

				.col-read-more {
					flex:2 !important;
					padding-top: 6px;
				    padding-bottom: 2px;
				    padding-right: 10px;
				}
				.total-score {
					font-size: 16px;
					font-weight: bold;
					text-align:right;
					color:#40503e;
					flex: 6 !important;
				    padding-top: 6px;
				    padding-bottom: 2px;
				    padding-right: 10px;
				}
				.total-score-markcol {
					font-size: 20px;
					font-weight: 600;
					text-align: center;
					text-align: center;
					border-radius: 5px;
					padding:6px 20px;
					width:unset;
					flex:unset !important;
				}				


				.wpio-flex-row {
					display:flex;
					text-align:left;
					margin-bottom: 3px;
					align-items:center;
					line-height: 20px;
				}
				.wpio-flex-col {
					flex:1;
				}

				.wpio-fntype-row {
					border-bottom: 1px dashed lightgray;
					padding-bottom: 10px;
					margin-bottom:10px;
				}

				
				.wpio-type-col {
					width:85px;
					flex:unset;
					text-align: left;
					font-weight: bold;
					color:#413c3c;
				}
				.wpio-text-black {
					color:#413c3c;
				}
				.wpio-score-col {
					flex:unset;
					width:35px;
					text-align:center;
					border-radius:5px;
					color:white;
					padding-top:2px;
					padding-bottom:2px;
					margin-top:0px;
				}
				.wpio-flex-function {
					flex:10;
				}

				.wpio-warning {
					font-size: 2em;
					line-height: 2.5em;
					font-weight: bold;
					color: red;
				}
				.wpio-center {
					text-align:center;
				}
				.wpio-justified {
					text-align: justify;
				}

			</style>


			<script language='javascript'>
				function clear_all_local_results() {
					jQuery('#clear_all_results_btn').attr('disabled',true);

					jQuery.ajax({
						url:'',
						type:'POST',
						data: {
							clear_local_results:1,
							_wpnonce:'".wp_create_nonce("wp-benchmark-io-clear-local-results")."'
						},
						dataType:'json',
						success:function(data) {
							jQuery('#previous_results_panel').remove();
						},
						error:function(data) {
							jQuery('#clear_all_results_btn').attr('disabled',false);							
						}
					});
				}


				function start_benchmark() {

					// jQuery('#wpio-start-btn').attr('disabled', true).html('Starting benchmark, please wait...');
					jQuery('#wpio-start-btn').attr('disabled', true);
					jQuery('#wpio-save-setting-btn').attr('disabled', true);

					jQuery('#wpio-start-btn').data('output-num', (get_output_num()+1));
					jQuery('#wpio-output-container').prepend('\
						<div class=\"wpio-panel wpio-panel-result\">\
							<div class=\"wpio-panel-title\">Test number ' + get_output_num() + ' (<span class=\"wpio-progress\" id=\"wpio-progresstxt-' + get_output_num() + '\">0% initializing</span>)</div>\
							<div class=\"wpio-panel-body\" id=\"result-panel-' + get_output_num() + '\" data-bench-code=\"\"></div>\
						</div>\
					');

					jQuery.ajax({
						url:'',
						type:'POST',
						data: {
							start_benchmark:1,
							_wpnonce:'".wp_create_nonce("wp-benchmark-io-start-new-bench")."'
						},
						dataType:'json',
						success:function(data) {

							if (data.status>0) {
								jQuery('#wpio-start-btn').html('Running...');
								set_bench_code(data.bench_code);								
							} else
								jQuery('#wpio-start-btn').attr('disabled', false).html('Error occured, try again');


							//if (data.status<0)
							show_output(data.description, data.status);

							create_progress_info(data.group_progress, data.skip_object_cache_tests);


							if (data.progress<100 && data.status>0)
								setTimeout(run_benchmark, 1000);
						},
						error:function(data) {
							show_output('Unknown error occured during connecting to plugin backend!', -1);
							console.log(data);
							jQuery('#wpio-start-btn').html('Ready to run again!');
							jQuery('#wpio-start-btn').attr('disabled',false);
							jQuery('#wpio-save-setting-btn').attr('disabled', false);
						}
					});
				}


				function run_benchmark() {
					// jQuery('#wpio-start-btn').html('Executing next step...');

					jQuery.ajax({
						url:'',
						type:'POST',
						data: {
							run_next_step:1,
							bench_code:get_bench_code()
						},
						dataType:'json',
						success:function(data) {

							//jQuery('#wpio-start-btn').html(data.progress+'% is completed');
							jQuery('#wpio-progresstxt-' + get_output_num()).html(data.progress+'% running...');


							if (data.status<0)
								show_output(data.description, data.status);
							else
								update_progress_info(data.group_progress);

							if (data.progress<100 && data.status>0)
								setTimeout(run_benchmark, 1000);
							else {
								show_output('<hr>',0);
								setTimeout(get_finals,200);
								// jQuery('#wpio-start-btn').attr('disabled',false);
							}
						},
						error:function(data) {
							show_output('Error during test - skipping failed benchmark...', -1);

							setTimeout(skip_failed_test, 1000);

							// console.log(data);
							// jQuery('#wpio-start-btn').html('Ready to run again!');
							// jQuery('#wpio-start-btn').attr('disabled',false);
							// jQuery('#wpio-save-setting-btn').attr('disabled', false);
						}
					});
				}


				function skip_failed_test() {
					jQuery.ajax({
						url:  '',
						type: 'POST',
						data: {
							skip_failed_test:1,
							bench_code:get_bench_code()
						},
						dataType: 'json',
						success: function(data) {
							

							show_output('Executing next function, please wait...');

							//jQuery('#wpio-start-btn').html(data.progress+'% is completed');
							jQuery('#wpio-progresstxt-' + get_output_num()).html(data.progress+'% running...');


							if (data.status<0)
								show_output(data.description, data.status);
							else
								update_progress_info(data.group_progress);

							if (data.progress<100 && data.status>0)
								setTimeout(run_benchmark, 1000);
							else {
								show_output('<hr>',0);
								setTimeout(get_finals,200);
								// jQuery('#wpio-start-btn').attr('disabled',false);
							}
						},
						error: function(data) {
							show_output('Failed to continue with next benchmark, stopped.');

							jQuery('#wpio-start-btn').html('Ready to run again!');
							jQuery('#wpio-start-btn').attr('disabled',false);
							jQuery('#wpio-save-setting-btn').attr('disabled', false);
						}
					});
				}


				function get_finals() {
					// jQuery('#wpio-start-btn').html('Calculating final numbers...');
					jQuery('#wpio-progresstxt-' + get_output_num()).html('100% finalizing..');

					jQuery.ajax({
						url:'',
						type:'POST',
						data: {
							get_finals:1,
							bench_code:get_bench_code()
						},
						dataType:'json',
						success:function(data) {

							update_progress_info(data.group_progress);

							jQuery('#wpio-start-btn').html('Ready to run again!');
							show_output(data.description, data.status);
							
							jQuery('#wpio-start-btn').attr('disabled',false);
							jQuery('#wpio-save-setting-btn').attr('disabled', false);
							jQuery('#wpio-progresstxt-' + get_output_num()).html(data.progress+'% FINISHED!');
						},
						error:function(data) {
							show_output('Posting final results failed, retrying...', -1);
							setTimeout(get_finals_retry, 1000);
						}
					});
				}


				function get_finals_retry() {
					// jQuery('#wpio-start-btn').html('Calculating final numbers...');
					jQuery('#wpio-progresstxt-' + get_output_num()).html('100% finalizing..');

					jQuery.ajax({
						url:'',
						type:'POST',
						data: {
							get_finals:1,
							bench_code:get_bench_code()
						},
						dataType:'json',
						success:function(data) {

							update_progress_info(data.group_progress);

							jQuery('#wpio-start-btn').html('Ready to run again!');
							show_output(data.description, data.status);
							
							jQuery('#wpio-start-btn').attr('disabled',false);
							jQuery('#wpio-save-setting-btn').attr('disabled', false);
							jQuery('#wpio-progresstxt-' + get_output_num()).html(data.progress+'% FINISHED!');
						},
						error: function(data) {
							show_output('Fatal error: Posting final results failed!', -1);
						}
					});
				}

				function get_output_num() {
					return(jQuery('#wpio-start-btn').data('output-num'));
				}
				function get_result_panel_html_id() {
					return('#result-panel-' + get_output_num());
				}

				function set_bench_code(bench_code) {
					jQuery('#result-panel-' + get_output_num()).data('bench-code', bench_code);					
				}
				function get_bench_code() {
					return(jQuery('#result-panel-' + get_output_num()).data('bench-code'));
				}

				function show_output(msg, status) {
					// wpio-result-container

					var msg_class = '';

					if (status>0)
						msg_class = 'wpio-text-success';
					else if (status<0)
						msg_class = 'wpio-text-danger';

					// jQuery('#wpio-result-container').append('<div class=\"' + msg_class + '\">' + msg + '</div>');
					jQuery(get_result_panel_html_id()).append('<div class=\"' + msg_class + '\">' + msg + '</div>');
				}


				function create_progress_info(group_progress, skip_object_cache_tests) {
					var panel_element = get_result_panel_html_id();

					jQuery.each(group_progress, function(group_key, group_data) {

						var add_this_panel = 1;
						if (skip_object_cache_tests==1 && group_key=='object_cache')
							add_this_panel = 0;


						if (add_this_panel==1) {
							jQuery(get_result_panel_html_id()).append('\
								<div class=\"wpio-row\">\
									<div class=\"wpio-col-3 wpio-text-right\">' + group_data.name + '</div>\
									<div class=\"wpio-col-7\">\
										<div class=\"wpio-progress-container\" id=\"pgbar-'+group_key+'-'+get_output_num()+'\"><div class=\"wpio-progress-done\">0%</div></div>\
									</div>\
								</div>\
							');
						}
						
					});
				}

				function update_progress_info(group_progress) {
					var panel_element = get_result_panel_html_id();

					jQuery.each(group_progress, function(group_key, group_data) {
						jQuery('#pgbar-'+group_key+'-'+get_output_num()+' .wpio-progress-done').css('width', group_data.group_progress+'%').html(group_data.group_progress+'%');
					});
				}
			</script>
			");

			print("<h1><small><i>Greetings Wizard!</i> Welcome to the</small><br>Wordpress hosting Benchmarking Tool</h1><hr>

				<div class='wpio-row'>
					<div class='wpio-col-7'>

						<div id='wpio-output-container'>
						
						</div>
					</div>
					<div class='wpio-col-3'>

				");


			
			print("
				
				<div class='wpio-row'>
				<div class='wpio-panel' style='width:80%;'>
					<div class='wpio-panel-title'>
						Plugin settings
					</div>
					<div class='wpio-panel-body'>
						<form action='' method='post' class='form-vertical'>
							<input type='hidden' name='doa' value='save_settings'>
							<input type='hidden' name='_wpnonce' value='".wp_create_nonce("wp-benchmark-io-save-settings")."'>

						<table class='wpio-table' width='100%'>
							<tbody>
								<tr><td class='title-cell'>" . __("Run each test only once, instead of five times.")."<br>" . __("May decrease accuracy") . "</td><td><input type='checkbox' value=1 name='run_lite_tests' class='wpio-form-control'" . ((self::$settings["run_lite_tests"]==1)?" checked":"") . "></td></tr>
								<tr><td class='title-cell'><a href='//wpbenchmark.io/when-skip-persistent-object-cache/' target=_blank>" . __("Skip persistent object cache tests") . "</a></td><td><input type='checkbox' value=1 name='skip_object_cache_tests' class='wpio-form-control'" . ((self::$settings["skip_object_cache_tests"]==1)?" checked":"") . "></td></tr>
								<tr><td class='title-cell'>" . __("Publish result on public board") . "</td><td><input type='checkbox' value=1 name='show_on_board' class='wpio-form-control'" . ((self::$settings["show_on_board"]==1)?" checked":"") . "></td></tr>

								<tr><td class='title-cell' colspan='2'>
									".__("Anonymize benchmark results after")."<br>
									<select name='anonymize_after' style='width:100%; margin-top:4px; margin-bottom:8px; text-align:right;'>" . $anonymize_select_html . "</select>
								</td></tr>


								<tr><td class='title-cell'><a href='//wpbenchmark.io/terms-of-use/' target=_blank>" . __("Accept terms of usage") . "</a></td><td><input type='checkbox' value=1 name='accept_terms' class='wpio-form-control'" . ((self::$settings["accept_terms"]==1)?" checked":"") . "></td><tr>
								<tr><td class='title-cell'><a href='//wpbenchmark.io/gdpr/' target=_blank>" . __("Consent about GDPR") . "</a></td><td><input type='checkbox' value=1 name='gdpr_consent' class='wpio-form-control'" . ((self::$settings["gdpr_consent"]==1)?" checked":"") . "></td></tr>
								<tr><td colspan='2' align='center' style='padding-top:5px;'><button id='wpio-save-setting-btn' type='submit' class='wpio-btn wpio-btn-primary wpio-btn-block'>" . __("Save settings") . "</button></td></tr>
							</tbody>
						</table>

						</form>

			");


			# check if query-monitor plugin is active. Known for performance degradation
			if (is_plugin_active("query-monitor/query-monitor.php")) {
				# print("<hr>is active!");

				print("
					<hr>

					<div class='wpio-warning wpio-center'>" . __("WARNING!") ."</div>
					<div class='wpio-justified'><i>Query Monitor</i> plugin can affect reported timings in a negative way and lead to very high memory usage and possible PHP process crash during database tests. You can still run benchmark, but keep in mind, that measured score will be inaccurate.</div>
				");
			}



			if (self::$settings["accept_terms"]==1 && self::$settings["gdpr_consent"]==1) {
				print("


					<hr>
					<button class='wpio-btn wpio-btn-success wpio-btn-block wpio-btn-lg' id='wpio-start-btn' onClick='start_benchmark();' data-output-num=0 style='margin-top:5px;'>" . __("Start benchmark!") . "</button>

					<div class='wpio-row' id='wpio-result-container'>
					</div>
				");
			} else {
				print("
					<hr>
					<div style='text-align:center; color:darkred;'>Please spend a minute to read and accept usage terms and become consent about GDPR policy.<br>Happy benchmarking afterwards!</div>");
			}

			print("
					</div>
				</div> <!-- end of panel --> 

				</div>

			");

			$history = get_option("wp-benchmark-io-history");

			if ($history===false || !isset($history)) {
				$history = array();
				update_option("wp-benchmark-io-history", $history);
			}

			if (count($history)>0) {

				$htbl = "<table width='100%'><thead>
					<tr>
					<th align='left'>Date</th>
					<th align='left'>Code</th>
					<th align='center'>Score</th>
					<th align='right'>PHP</th>
					</tr>
					</thead>
					<tbody>";
				foreach($history as $h) {
					if ($h["total_score"]<2)
						$row_class = "wpio-col-score02";
					else if ($h["total_score"]<5)
						$row_class = "wpio-col-score25";
					else if ($h["total_score"]<6)
						$row_class = "wpio-col-score56";
					else if ($h["total_score"]<7)
						$row_class = "wpio-col-score67";
					else if ($h["total_score"]<8)
						$row_class = "wpio-col-score78";
					else if ($h["total_score"]<9)
						$row_class = "wpio-col-score89";
					else
						$row_class = "wpio-col-score910";


					$htbl .= "<tr>
						<td>".$h["completed_datetime"]."</td>
						<td><a href='https://report.wpbenchmark.io/".$h["bench_code"]."/' target=_blank>".$h["bench_code"]."</a></td>
						<td align='center' class='".$row_class."' style='width:100%; border-radius:5px;'>".$h["total_score"]."</td>
						<td align='right'>".$h["php_version"]."</td>
						</tr>";
				}
				$htbl .= "</tbody></table>";

				print("
					<div class='wpio-row' style='margin-top:20px;'>

					<div class='wpio-panel' style='width:80%;' id='previous_results_panel'>
						<div class='wpio-panel-title' style='text-align:left;'>
							" . __("Previous benchmark results") . " <small style='float:right; font-weight:normal; font-size:0.7em;'><button type='button' onClick='clear_all_local_results();' id='clear_all_results_btn'>".__("Clear all")."</button></small>
						</div>
						<div class='wpio-panel-body'>
							".$htbl."
						</div>
					</div> <!-- end of panel -->

					<div style='float:left; font-size:0.9em; width:100%;'>
						<div style='width:80%; margin-left:10px; margin-right:10px; padding:10px; color:#888;'><i>Adding <a href='https://wordpress.org/support/plugin/wpbenchmark/reviews/' target=_blank>rating</a> this plugin will help others and encourage its development.</i></div>
					</div>

					</div> <!-- end of row -->
				");
			}

			print("
				</div></div>

			");


			## NOT SURE WHAT IT IS?print("</div>");

		}
	}

	function custom_add_google_fonts() {
	 wp_enqueue_style( 'custom-google-fonts', 'https://fonts.googleapis.com/css?family=Merriweather:300,300i,400,400i,700,700i&amp;subset=cyrillic', false );
	}




	static function execute_plugin() {


		if (!isset($_POST["start_benchmark"])) $post_start_benchmark=0;
		else $post_start_benchmark=$_POST["start_benchmark"];

		if (!isset($_POST["run_next_step"])) $post_run_next_step=0;
		else $post_run_next_step = $_POST["run_next_step"];

		if (!isset($_POST["get_finals"])) $post_get_finals=0;
		else $post_get_finals = $_POST["get_finals"];

		if (!isset($_POST["skip_failed_test"])) $post_skip_failed_test=0;
		else $post_skip_failed_test = $_POST["skip_failed_test"];

		if (!isset($_POST["clear_local_results"])) $post_clear_local_results=0;
		else $post_clear_local_results = $_POST["clear_local_results"];


		if ($post_start_benchmark==1 || $post_run_next_step==1 || $post_get_finals==1 || $post_skip_failed_test==1 || $post_clear_local_results==1) {




			if ($post_clear_local_results==1) {

				if (wp_verify_nonce($_POST["_wpnonce"], "wp-benchmark-io-clear-local-results")) {
					$history = array();
					update_option("wp-benchmark-io-history", $history);

					die(json_encode(array("success"=>1)));
				} else {
					die(json_encode(array("success"=>0, "description"=>"Invalid WP nonce value")));
				}
			}


			// Only on start benchmark
			#if ($_POST["start_benchmark"])
			#check_admin_referer("wp-benchmark-io-start-new-bench");

			require_once(dirname(__FILE__)."/class.wpbenchmarkio.php");

			$dsc = "";
			$group_progress = array();
			$global_averages = array();

			$settings = get_option("wp-benchmark-io-settings");


			if ($settings["accept_terms"]==1 && $settings["gdpr_consent"]==1) {
				try {
					$b = new wpbenchmarkio();


					if ($post_start_benchmark==1 && wp_verify_nonce($_POST["_wpnonce"], "wp-benchmark-io-start-new-bench")) {
						$running_benchmark = $b->request_new(array("run_lite_tests"=>$settings["run_lite_tests"], "show_on_board"=>$settings["show_on_board"], "skip_object_cache_tests"=>$settings["skip_object_cache_tests"]));
					
						$status=1;
						#$dsc = "<div class='wpio-benchmark-title'>Started benchmark <button class='btn btn-bench-code'>#".$running_benchmark["bench_code"]."</button></div>";
						$dsc = "";
						$progress  = $running_benchmark["progress"];
						$group_progress = $running_benchmark["group_progress"];

						# $group_progress = $b->get_test_progress($running_benchmark);

						update_option("wp-benchmark-io-running", $running_benchmark);
					} else if ($post_run_next_step==1 || $post_skip_failed_test==1) {

						#error_reporting( E_ALL );

						$running_benchmark = get_option("wp-benchmark-io-running");

						if (!isset($running_benchmark["bench_code"]))
							throw new Exception("Stored variable is corrupt, please start new benchmark!");

						if ($running_benchmark["bench_code"]!=$_POST["bench_code"])
							throw new Exception("Wrong benchmark code, do not run several benchmarks at once!");

						if ($running_benchmark["progress"]<100) {

							if ($post_skip_failed_test==1)
								$skip_next_benchmark = true;
							else
								$skip_next_benchmark = false;

							$running_benchmark = $b->run_next($running_benchmark, $skip_next_benchmark);

							$status=1;
							$progress=$running_benchmark["progress"];
							$dsc = $running_benchmark["executed_description"];
							$group_progress = $running_benchmark["group_progress"];

							update_option("wp-benchmark-io-running", $running_benchmark);
						}

					} else if ($post_get_finals==1) {
						$running_benchmark = get_option("wp-benchmark-io-running");

						if (!isset($running_benchmark["bench_code"]))
							throw new Exception("Stored variable is corrupt, please start new benchmark!");

						if ($running_benchmark["bench_code"]!=$_POST["bench_code"])
							throw new Exception("Wrong benchmark code, do not run several benchmarks at once!");

						if ($running_benchmark["progress"]<100) 
							throw new Exception("This benchmarks has not been completed!");

						$running_benchmark["anonymize_after"] = $settings["anonymize_after"];

						$running_benchmark = $b->calculate_finals($running_benchmark);

						$status=1;
						$progress=$running_benchmark["progress"];
						$dsc = $running_benchmark["executed_description"];
						$group_progress = $running_benchmark["group_progress"];
						#$global_averages = $running_benchmark["global_averages"];

						update_option("wp-benchmark-io-running", $running_benchmark);

						$benchmark_history = get_option("wp-benchmark-io-history");
						if ($benchmark_history===false || !isset($benchmark_history))
							$benchmark_history=array();


						$benchmark_history[time()] = array(
							"completed_datetime"=>date("H:i d.M.Y"),
							"bench_code"=>$running_benchmark["bench_code"],
							"total_score"=>$running_benchmark["total_score"],
							"php_version"=>phpversion(),
							"show_on_board"=>$running_benchmark["show_on_board"],
							"run_lite_tests"=>$running_benchmark["run_lite_tests"],
							"skip_object_cache_tests"=>$running_benchmark["skip_object_cache_tests"]						
						);

						update_option("wp-benchmark-io-history", $benchmark_history);


						# cleanup object cache 
						$b->local_wp_cache_flush();
					} else {
						die("Invalid request or WP nonce value");
					}

				} catch (Exception $e) {
					$bench_code="";
					$status=-1;
					$dsc = $e->getMessage();

					$report_exception = array("a"=>"report_exception", "exception_message"=>$dsc, "running_benchmark"=>print_r($running_benchmark,true), "wp_post_data"=>print_r($_POST,true));
					$b->talk($report_exception);
					#$m["a"] = "register_new";
					#$data = $this->talk($m);
				}
			} else {
				$status = -1;
				$dsc = "You must agree our service terms and conditions and be consent about GDPR.";
				$bench_code = "";
			}

			die(json_encode(array("bench_code"=>$running_benchmark["bench_code"], "progress"=>$progress, "status"=>$status, "description"=>$dsc, "group_progress"=>$group_progress, "skip_object_cache_tests"=>$running_benchmark["skip_object_cache_tests"])));
		}
	} # end function execute_plugin
} # end of class


if ( is_admin() ) {
	add_action( 'init', array( 'wp_benchmark_io', 'admin_init' ) );
}
