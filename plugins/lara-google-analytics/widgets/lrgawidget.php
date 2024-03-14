<?php
namespace Lara\Widgets\GoogleAnalytics;

/**
 * @package    Google Analytics by Lara
 * @author     Amr M. Ibrahim <mailamr@gmail.com>
 * @link       https://www.xtraorbit.com/
 * @copyright  Copyright (c) XtraOrbit Web development SRL 2016 - 2020
 */

if (!defined("ABSPATH"))
    die("This file cannot be accessed directly");
?>

<style type="text/css" id="lrgawidget_loader">
#dashboard-widgets-wrap::before {
  content: "";
  width:99%;
  height:550px;
  margin:8px !important;
  display: inline-block;
  background-color: #ffffff;
}
</style>

<div id="lrgawidget_adblock_error" style="width:100%; height:550px; margin-top:8px !important; margin-bottom:12px !important; background-color: #ffffff; display:none;">
	<div style="width:100%; height:100%; display: flex; justify-content: center; align-items: center;">
		<div style="width:50%; padding:15px; border: 1px solid #444; border-radius:2px;">
		  <p><b><?php _e('Google Analytics', 'lara-google-analytics'); ?> - <?php _e('Fatal Error', 'lara-google-analytics'); ?> : UNABLE_TO_LOAD</b></p>
		  <p><?php _e('The widget was not able to load, which is usually caused by either an active <b>adblocker</b>, or a <b>conflicting plugin</b>. Some adblockers block all URLs containing certain words, like "<b>google-analytics</b>", which stops the widget from loading required files.', 'lara-google-analytics'); ?></p>
		  <p style="background: #e6eaee; padding: 15px;"><?php _e('If you are using an adblocker, please <b>disable</b> it on this page, then <b>clear the cache memory of your browser</b>.', 'lara-google-analytics'); ?></p>
		  <div style="background: #e6eaee; padding: 15px;">
			<p><?php _e('To clear the cache memory of your browser :', 'lara-google-analytics'); ?></p>
			<ol>
				<li><?php _e('<b>Windows:</b> Ctrl + F5 <u>or</u> SHIFT + Reload for firefox', 'lara-google-analytics'); ?></li>
				<li><?php _e('<b>Mac/Apple:</b> Apple + R <u>or</u> Command + R', 'lara-google-analytics'); ?></li>
				<li><?php _e('<b>Linux:</b> F5', 'lara-google-analytics'); ?></li>
			</ol>		  
		  </div>
		  <p><?php _e('If this did not fix the problem, please  <a href="https://clients.xtraorbit.com/submitticket.php?step=2&deptid=2&subject=Plugin-UNABLE-TO-LOAD" target="_blank">open a support ticket</a>, and we will do our best to fix it.', 'lara-google-analytics'); ?></p>
		</div>
	</div>
</div>

<div id="lrgawidget_wrap" style="display:none;"><!-- /.wrap -->
<div class="lrga_bs" ><!-- /.class -->
<div class="lrga_bs lrgawidget"><!-- /.id -->

<?php if (!empty(DataStore::$RUNTIME["FatalError"])){ foreach(DataStore::$RUNTIME["FatalError"] as $lrgawidget_fatal_error){ ?>
<div class="alert alert-danger" id="lrgawidget_fatal_error"><p><strong><?php _e('Google Analytics', 'lara-google-analytics'); ?> - <?php _e('Fatal Error', 'lara-google-analytics'); ?> :</strong></p><i class="icon fas fa-exclamation-triangle"></i>
<?php echo $lrgawidget_fatal_error["error"]." [".$lrgawidget_fatal_error["code"]."] :: ".$lrgawidget_fatal_error["error_description"]." (".$lrgawidget_fatal_error["debug"]. ")."; ?>
</div>
<?php }}else { ?>

<?php

if (!empty(DataStore::$RUNTIME["askforreview"])){
	$already_rated = DataStore::database_get("global_options", "already_rated");
	if (empty($already_rated)){	
		require(lrgawidget_plugin_dir . '/core/review.notice.php');
		ReviewNotice::show_review_notice();
	}
}
	
?>

<div class="box box-primary" id="lrgawidget">
  <div class="box-header with-border">
    <h3 class="box-title"><i class="far fa-chart-bar fa-fw"></i> <?php _e('Google Analytics', 'lara-google-analytics'); ?></h3>
    <div class="box-tools pull-right">
		<span id="lrgawidget_loading"></span>
		<span id="lrgawidget_mode" class="label label-success"></span>

		<?php if (in_array("graph_options",DataStore::$RUNTIME["permissions"])){  ?>
		<button id="lrghop_button" class="btn btn-box-tool hidden-xs " type="button" style="display: none;"><i class="far fa-chart-bar fa-fw"></i><?php _e('Graph Options', 'lara-google-analytics'); ?></button>
		<div id="lrghop_menu" class="dropdown-menu">
			<div class="lrghop_colorselector_container hidden-xs" style="display: none;"><div class="lrghop_colorselector"></div></div>
			<form id="lrghop_form" name="lrghop_form" role="form">
				<input name="action" type="hidden" value="setGraphData">
				<input name="currentfilter" type="hidden" value="">
				<div class="row">
					<div class="col-sm-8">
						<div id="lrghop_panels">
							<div data-lrgh-panel="settings" id="lrghop_settings"></div>
							<div id="lrgfilters_panels"><i class="fas fa-spinner fa-pulse fa-fw"></i> <?php _e('Loading data, please wait !', 'lara-google-analytics'); ?></div>
						</div>
					</div>
					
					<div class="col-sm-4">
						<div id="lrghop_buttons">
							<button class="btn btn-primary btn-sm btn-block" data-lrghop-button="settings" type="button"><i class="fas fa-tools fa-fw"></i><?php _e('Settings', 'lara-google-analytics'); ?></button>
							<hr/>
							<div id="lrgfilters_buttons"><i class="fas fa-spinner fa-pulse fa-fw"></i> <?php _e('Loading data, please wait !', 'lara-google-analytics'); ?></div>
							<hr/>
							<button class="btn btn-success btn-sm btn-block" type="submit"><i class="far fa-save fa-lg fa-fw"></i> <?php _e('Save', 'lara-google-analytics'); ?></button>
							<button class="btn btn-default btn-sm btn-block" id="lrghop_cancel" type="button"><i class="far fa-times-circle fa-lg fa-fw"></i> <?php _e('Close', 'lara-google-analytics'); ?></button>
						</div>
					</div>
				</div>
			</form>  
		</div>
		<?php } ?>
		
		<button type="button" class="btn btn-box-tool" id="lrgawidget_daterange_label">
		    <i class="fas fa-calendar-alt fa-fw"></i>
			<span id="lrgawidget_reportrange"></span>
		</button>
		<span id="lrgawidget_remove" data-lrwidgetools="remove"><i class="fas fa-times"></i></span>
    </div>
  </div>
  <div id="lrgawidget_body" class="box-body">
	<div class="nav-tabs-custom" id="lrgawidget_main">
		<ul class="nav nav-tabs">
		<?php if (in_array("admin",DataStore::$RUNTIME["permissions"])){  ?>
			
        <?php } if (in_array("sessions",DataStore::$RUNTIME["permissions"])){  $actLrgaTabs[] = "lrgawidget_sessions_tab"; ?>
			<li><a data-toggle="tab" data-target="#lrgawidget_sessions_tab" href="#lrgawidget_sessions_tab"><i class="fas fa-chart-line fa-fw"></i><span class="hidden-xs hidden-sm"> <?php _e('Graph', 'lara-google-analytics'); ?></span></a></li>
			
		<?php } if (in_array("pages",DataStore::$RUNTIME["permissions"])){  $actLrgaTabs[] = "lrgawidget_pages_tab"; ?>
			<li><a data-toggle="tab" data-target="#lrgawidget_pages_tab" href="#lrgawidget_pages_tab"><i class="far fa-file fa-fw"></i><span class="hidden-xs hidden-sm"> <?php _e('Pages', 'lara-google-analytics'); ?></span></a></li>			

		<?php } if (in_array("realtime",DataStore::$RUNTIME["permissions"])){  $actLrgaTabs[] = "lrgawidget_realtime_tab"; ?>
			<li><a data-toggle="tab" data-target="#lrgawidget_realtime_tab" href="#lrgawidget_realtime_tab"><i class="far fa-clock fa-fw"></i><span class="hidden-xs hidden-sm"> <?php _e('Real Time', 'lara-google-analytics'); ?></span></a></li>

		<?php } if (in_array("countries",DataStore::$RUNTIME["permissions"])){  $actLrgaTabs[] = "lrgawidget_countries_tab"; ?>
			<li><a data-toggle="tab" data-target="#lrgawidget_countries_tab" href="#lrgawidget_countries_tab"><i class="fas fa-globe fa-fw"></i><span class="hidden-xs hidden-sm"> <?php _e('Countries', 'lara-google-analytics'); ?></span></a></li>

		<?php } if (in_array("browsers",DataStore::$RUNTIME["permissions"])){  $actLrgaTabs[] = "lrgawidget_browsers_tab"; ?>
			<li><a data-toggle="tab" data-target="#lrgawidget_browsers_tab" href="#lrgawidget_browsers_tab"><i class="far fa-list-alt fa-fw"></i><span class="hidden-xs hidden-sm"> <?php _e('Browsers', 'lara-google-analytics'); ?></span></a></li>

		<?php } if (in_array("languages",DataStore::$RUNTIME["permissions"])){  $actLrgaTabs[] = "lrgawidget_languages_tab"; ?>
			<li><a data-toggle="tab" data-target="#lrgawidget_languages_tab" href="#lrgawidget_languages_tab"><i class="fas fa-font fa-fw"></i><span class="hidden-xs hidden-sm"> <?php _e('Languages', 'lara-google-analytics'); ?></span></a></li>

		<?php } if (in_array("os",DataStore::$RUNTIME["permissions"])){  $actLrgaTabs[] = "lrgawidget_os_tab"; ?>
			<li><a data-toggle="tab" data-target="#lrgawidget_os_tab" href="#lrgawidget_os_tab"><i class="fas fa-desktop fa-fw"></i><span class="hidden-xs hidden-sm"> <?php _e('Operating Systems', 'lara-google-analytics'); ?></span></a></li>

		<?php } if (in_array("devices",DataStore::$RUNTIME["permissions"])){  $actLrgaTabs[] = "lrgawidget_devices_tab"; ?>
			<li><a data-toggle="tab" data-target="#lrgawidget_devices_tab" href="#lrgawidget_devices_tab"><i class="fas fa-tablet-alt fa-fw"></i><span class="hidden-xs hidden-sm"> <?php _e('Devices', 'lara-google-analytics'); ?></span></a></li>

		<?php } if (in_array("screenres",DataStore::$RUNTIME["permissions"])){  $actLrgaTabs[] = "lrgawidget_screenres_tab"; ?>
			<li><a data-toggle="tab" data-target="#lrgawidget_screenres_tab" href="#lrgawidget_screenres_tab"><i class="fas fa-expand-arrows-alt fa-fw"></i><span class="hidden-xs hidden-sm"> <?php _e('Screen Resolution', 'lara-google-analytics'); ?></span></a></li>

		<?php } if (in_array("keywords",DataStore::$RUNTIME["permissions"])){  $actLrgaTabs[] = "lrgawidget_keywords_tab"; ?>
			<li><a data-toggle="tab" data-target="#lrgawidget_keywords_tab" href="#lrgawidget_keywords_tab"><i class="fas fa-search fa-fw"></i><span class="hidden-xs hidden-sm"> <?php _e('Keywords', 'lara-google-analytics'); ?></span></a></li>

		<?php } if (in_array("sources",DataStore::$RUNTIME["permissions"])){  $actLrgaTabs[] = "lrgawidget_sources_tab"; ?>
			<li><a data-toggle="tab" data-target="#lrgawidget_sources_tab" href="#lrgawidget_sources_tab"><i class="fas fa-external-link-square-alt fa-fw"></i><span class="hidden-xs hidden-sm"> <?php _e('Source', 'lara-google-analytics'); ?></span></a></li>

		<?php } ?>
           <li><a data-toggle="tab" data-target="#lrgawidget_gopro_tab" href="#lrgawidget_gopro_tab"><i class="fas fa-unlock-alt fa-fw"></i><span class="hidden-xs hidden-sm"> <?php _e('Go Premium !', 'lara-google-analytics'); ?> </span></a></li> 		    

		<?php if (in_array("admin",DataStore::$RUNTIME["permissions"])){  ?>
			<li class="pull-right"><a data-toggle="tab" data-target="#lrgawidget_settings_tab" href="#lrgawidget_settings_tab"><i class="fas fa-cog fa-fw"></i></a></li>
		<?php } ?>
		
        <?php if (in_array("perm",DataStore::$RUNTIME["permissions"])){  ?>
			<li class="pull-right"><a data-toggle="tab" data-target="#lrgawidget_permissions_tab" href="#lrgawidget_permissions_tab"><i class="fas fa-user-lock fa-fw"></i></a></li>
		<?php } ?>
		
		<?php if (!empty(DataStore::$RUNTIME["showhelp"])){ ?>
			<li class="pull-right"><a href="https://clients.xtraorbit.com/submitticket.php?step=2&deptid=2&subject=Shopify_Widget_Help" target="_blank"><i class="fas fa-question-circle fa-fw"></i></a></li>
		<?php } ?>
		</ul>
		
		<div class="tab-content">
			<div class="alert alert-danger hidden" id="lrgawidget_error"></div>
		
			<?php if (empty($actLrgaTabs[0])){ ?>
				<div class="callout callout-danger">
					<h4><?php _e('You do not have permission to view any tab!', 'lara-google-analytics'); ?></h4>
					<?php _e('Make sure that your group has proper permissions to access the widget.', 'lara-google-analytics'); ?>
				  </div>
			<?php } ?>

			<?php if (in_array("admin",DataStore::$RUNTIME["permissions"])){  ?>
			<div class="tab-pane " id="lrgawidget_settings_tab">
				<div class="fuelux">
					<div class="wizard" data-initialize="wizard" id="lrga-wizard" style="background-color: #FFF;">
						<div class="steps-container">
							<ul class="steps">
								<li class="active" data-name="lrga-createApp" data-step="1"><span class="badge"><?php _e('1', 'lara-google-analytics'); ?></span><?php _e('Create Google APP', 'lara-google-analytics'); ?> <span class="chevron"></span></li>
								<li data-step="2" data-name="lrga-getCode"><span class="badge"><?php _e('2', 'lara-google-analytics'); ?></span><?php _e('Authorize APP', 'lara-google-analytics'); ?> <span class="chevron"></span></li>
								<li data-step="3" data-name="lrga-datastream"><span class="badge"><?php _e('3', 'lara-google-analytics'); ?></span><?php _e('Select Analytics Data Stream', 'lara-google-analytics'); ?> <span class="chevron"></span></li>
							</ul>
						</div>
						
						
						<div class="actions">
							<button type="button" class="btn btn-danger" data-lrgawidget-reset="reset" style="display: none;">
								<i class="fas fa-sync-alt fa-fw"></i> <?php _e('Reset all data and start over', 'lara-google-analytics'); ?>
							</button>
							<button type="button" class="btn btn-primary" data-reload="lrgawidget_go_express" style="display: none;">
							<i class="far fa-arrow-alt-circle-left fa-fw"></i> <i class="fas fa-magic fa-fw"></i> <?php _e('Go Back to Express Setup', 'lara-google-analytics'); ?>
							</button>
						</div>						

						<div class="step-content">
							<div class="step-pane active sample-pane bg-info alert" data-step="1">
								<div class="row">
									 <div id="lrgawidget_express_setup"> 
										<div class="col-md-6">
											<div class="lrgawidget_ex_left">
												<div class="box">
												  <div class="box-header with-border">
												  <i class="fas fa-magic fa-fw"></i>												  
													<h3 class="box-title"><?php _e('Express Setup', 'lara-google-analytics'); ?></h3>
												  </div>
												  <div class="box-body">
													<p><?php _e('Click on <b>Sign in with Google</b> button below, and a pop-up window will open, asking you to allow <b>Lara Analytics Widget</b> to access the following:', 'lara-google-analytics'); ?>
														<ul>
															<li><b><?php _e('View your Google Analytics data', 'lara-google-analytics'); ?></b></li>
															<li><b><?php _e('View Search Console data for your verified sites', 'lara-google-analytics'); ?></b></li>
														</ul>
													<?php _e('Click <b>Allow</b>, then copy and paste the access code here, and click <b>Submit</b>.', 'lara-google-analytics'); ?>
													<br><br><?php _e('If you were asked to login, be sure to use the same email account that is linked to your <b>Google Analytics</b>.', 'lara-google-analytics'); ?>
													<br><?php _e('If you are using the <b>premium</b> version of the plugin, that email account should also be linked to your <b>Google Search Console</b>.', 'lara-google-analytics'); ?>

													<br><br><a href="javascript:gauthWindow('https://accounts.google.com/o/oauth2/auth?response_type=code&client_id=<?php echo lrgawidget_plugin_client_id;?>&redirect_uri=<?php echo lrgawidget_plugin_redirect_uri;?>&scope=https://www.googleapis.com/auth/analytics.readonly&access_type=offline&approval_prompt=force');" ><img src="<?php echo lrgawidget_plugin_dist_url.'/img/signin_dark_normal_web.png'; ?>"></a>
													
													</p>
													
													<form id="express-lrgawidget-code" name="express-lrgawidget-code" role="form">
														<input name="action" type="hidden" value="getAccessToken">
														<input name="client_id" type="hidden" value="<?php echo lrgawidget_plugin_client_id;?>">
														<input name="client_secret" type="hidden" value="">
														<div class="form-group">
															<label> <?php _e('Access Code', 'lara-google-analytics'); ?></label>
															<div class="input-group">
																<div class="input-group-addon">
																	<i class="fas fa-user fa-fw"></i>
																</div>
																<input class="form-control" name="code" required="" type="text">
																<span class="input-group-btn">
																	   <button type="submit" class="btn btn-primary btn-flat" ><?php _e('Submit', 'lara-google-analytics'); ?></button>
																</span>
															</div><!-- /.input group -->
														</div>
													</form>
												  </div>
												</div>
											</div>
										</div>
										
										<div  class="col-md-6">
											<div class="lrgawidget_ex_right">
												<div class="box">
												  <div class="box-header with-border">
												  <i class="fas fa-cogs fa-fw"></i>												  
													<h3 class="box-title"><?php _e('Advanced Setup', 'lara-google-analytics'); ?></h3>
												  </div>
												  <div class="box-body">
													<p><?php _e('By clicking on <b>Start Advanced Setup</b> button below, The setup wizard will guide you through creating and/or configuring your own Google Application.', 'lara-google-analytics'); ?>
													<?php _e('If you want a quick start, or just trying the widget, use the <b>Express Setup</b> on the left.', 'lara-google-analytics'); ?>
													<br><br><a class="btn btn-primary btn-block" href="#" data-reload="lrgawidget_go_advanced"><?php _e('Start Advanced Setup', 'lara-google-analytics'); ?></a>
												  </div>
												</div>											
											</div>
										</div>
									 </div>
								 
									 <div id="lrgawidget_advanced_setup" style="display: none;">
										<div class="col-md-6">
											<form id="lrgawidget-credentials" name="lrgawidget-credentials" role="form">
												<input name="action" type="hidden" value="getAuthURL">
												<div class="form-group">
													<label><?php _e('Client ID', 'lara-google-analytics'); ?></label>
													<div class="input-group">
														<div class="input-group-addon">
															<i class="fas fa-user fa-fw"></i>
														</div><input class="form-control" name="client_id" required="" type="text" value="">
													</div><!-- /.input group -->
												</div>
												<div class="form-group">
													<label><?php _e('Client Secret', 'lara-google-analytics'); ?></label>
													<div class="input-group">
														<div class="input-group-addon">
															<i class="fas fa-lock fa-fw"></i>
														</div><input class="form-control" name="client_secret" required="" type="text" value="">
													</div><!-- /.input group -->
												</div>
												<div>
													<button class="btn btn-primary" type="submit"><?php _e('Submit', 'lara-google-analytics'); ?></button>
												</div>
											</form>
										</div>
										<div class="col-md-6">
											<h2 id="enable-oauth-20-api-access"><?php _e('Create Google APP', 'lara-google-analytics'); ?></h2>
											<p><?php _e('To use the <b>Google Analytics</b> widget, you will need to create a <b>Google App</b> as follows :', 'lara-google-analytics'); ?></p>

											<ol>
												<li><?php _e('Open the <a target="_blank" href="//console.developers.google.com/apis/credentials?project=_">Google Developers Console</a>.', 'lara-google-analytics'); ?></li>
												<li><?php _e('Click on <b>CREATE PROJECT</b>, choose a <b>Project name</b>, then click <b>CREATE</b>.', 'lara-google-analytics'); ?></li>
												<li><?php _e('From the top <b>Search for APIs and Services</b> box, search for <b>Google Analytics Admin API</b>, select it, then click <b>ENABLE</b>.', 'lara-google-analytics'); ?></li>
												<li><?php _e('Repeat the above step to enable <b>Google Analytics Data API</b> and <b>Google Search Console API</b>.', 'lara-google-analytics'); ?></li>
												<li><?php _e('Open <b>Google Developers Console</b> menu, by clicking on <i class="fas fa-bars fa-fw"></i> and select <b>APIs & Services</b> <i class="fas fa-caret-right fa-fw"></i> <b>OAuth consent screen</b>.', 'lara-google-analytics'); ?></li>
												<li><?php _e('Select <b>External</b> as the <b>User Type</b>, then click <b>CREATE</b>.', 'lara-google-analytics'); ?></li>
												<li><?php _e('On the next screen, choose an <b>Application name</b>, then click <b>Save</b>.', 'lara-google-analytics'); ?></li>
												<li><?php _e('From the side menu, click on <b>Credentials</b>, then click on <b>CREATE CREDENTIALS</b>, and select <b>OAuth client ID</b>.', 'lara-google-analytics'); ?></li>
												<li><?php _e('Under <b>Application type</b>, select <b>Web application</b>, choose a name.', 'lara-google-analytics'); ?></li>
												<li><?php _e('Under <b>Authorized redirect URIs</b>, click <b>+ ADD URI</b>, and enter <b>https://auth.xtraorbit.com</b>, then click <b>CREATE</b>.', 'lara-google-analytics'); ?></li>
												<li><?php _e('Take note of the <b>client ID</b> & <b>client secret</b> then click <b>OK</b>.', 'lara-google-analytics'); ?></li>
											</ol>
											<p><?php _e('When done, paste the <b>client ID</b> & <b>client secret</b> here and click <b>Submit</b>.', 'lara-google-analytics'); ?></p>
											
										</div>
									</div>
								</div>	
							</div>
							<div class="step-pane sample-pane bg-info alert" data-step="2">
								<div class="row">
									<div class="col-md-6">
										<form id="lrgawidget-code" name="lrgawidget-code" role="form">
											<input name="action" type="hidden" value="getAccessToken">
											<input name="client_id" type="hidden" value="">
											<input name="client_secret" type="hidden" value="">
											<div class="form-group">
												<label><?php _e('Access Code', 'lara-google-analytics'); ?></label>
												<div class="input-group">
													<div class="input-group-addon">
														<i class="fas fa-user fa-fw"></i>
													</div><input class="form-control" name="code" required="" type="text">
												</div><!-- /.input group -->
											</div>
											<div>
												<button class="btn btn-primary" type="submit"><?php _e('Submit', 'lara-google-analytics'); ?></button>
											</div>
										</form>
									</div>
									<div class="col-md-6">
										<h2 id="enable-oauth-20-api-access"><?php _e('Authorize App', 'lara-google-analytics'); ?></h2>
										<p><?php _e('Click on <b>Sign in with Google</b> button below, and a pop-up window will open, asking you to allow the <b>app you just created</b> to :', 'lara-google-analytics'); ?>
										<ul>
											<li><b><?php _e('View your Google Analytics data', 'lara-google-analytics'); ?></b></li>
										    <li><b><?php _e('View Search Console data for your verified sites', 'lara-google-analytics'); ?></b></li>
										</ul>
										<br><?php _e('Be sure to use the same email account that is linked to your <b>Google Analytics</b> and <b>Google Search Console</b> (formerly, Google Webmaster tools).', 'lara-google-analytics'); ?>
										<br><br><?php _e('Click <b>Allow</b>, then copy and paste the access code here, and click <b>Submit</b>.', 'lara-google-analytics'); ?>
										<div style="display:flex; margin: 10px 0px 20px 0px;">
											<div style="margin:5px;"><i class="fas fa-exclamation-triangle fa-2x" style="color:#db4437;"></i></div>
											<div><?php _e('Since your newly created app was not verified by Google, you will see <b>This app is not verified</b> warning. To continue, click on <b>Advanced</b>, then <u>Go to <b>Your APP Name</b></u>.', 'lara-google-analytics'); ?></div>
										</div>
										</p>
										
										<a href="#" id="code-btn"><img src="<?php echo lrgawidget_plugin_dist_url.'/img/signin_dark_normal_web.png'; ?>"></a>
									</div>
								</div>
							</div>
							<div class="step-pane sample-pane bg-info alert" data-step="3">
								<div class="row">
									<div class="col-md-6">
									
									<form id="lrgawidget-setMeasurementID" name="lrgawidget-setMeasurementID" role="form">
										<input name="action" type="hidden" value="setMeasurementID">
										<div class="form-group">
											<label><?php _e('Account', 'lara-google-analytics'); ?></label> 
											<select class="form-control" style="width: 100%;" id="lrgawidget-accounts" name="account_id">
											</select>
										</div>
										<div class="form-group">
											<label><?php _e('Property', 'lara-google-analytics'); ?></label> 
											<select class="form-control" style="width: 100%;" id="lrgawidget-properties" name="property_id">
											</select>
										</div>									
										<div class="form-group">
											<label><?php _e('Data Stream', 'lara-google-analytics'); ?></label> 
											<select class="form-control" style="width: 100%;" id="lrgawidget-dataStream" name="datastream_id">
											</select>
										</div>
										
<?php
$lock_settings = DataStore::database_get("settings","lock_settings");

$tracking_enabled = "";
$enable_ga4_tracking = DataStore::database_get("settings","enable_ga4_tracking");
if ($enable_ga4_tracking !== "off"){
	$tracking_enabled = "checked";
}

$ecommerce_graph_enabled = "";
$enable_ecommerce_graph = DataStore::database_get("settings","enable_ecommerce_graph");
if ($enable_ecommerce_graph !== "off"){
	$ecommerce_graph_enabled = "checked";
}

?>

										<div class="lrgawidget-settings-checkbox">
											 	<input name="enable_ga4_tracking" id="enable_ga4_tracking" <?php echo $tracking_enabled ?> type="checkbox" value="on">
												<label for="enable_ga4_tracking"><?php _e('Add <b>Google Analytics GA4</b> tracking code to all pages.', 'lara-google-analytics'); ?></label>
										</div>
										<div class="lrgawidget-settings-checkbox">
											 	<input name="enable_ecommerce_graph" id="enable_ecommerce_graph" <?php echo $ecommerce_graph_enabled ?> type="checkbox" value="on">
												<label for="enable_ecommerce_graph"><?php _e('Enable eCommerce graphs.', 'lara-google-analytics'); ?></label>
										</div>
										<div class="lrgawidget-settings-pro">
											<div><b><?php _e('Premium Settings', 'lara-google-analytics'); ?> </b> [ <?php _e('Works in the Pro version only', 'lara-google-analytics'); ?> ] :</div>
											<div class="lrgawidget-settings-checkbox lrgawidget-lock-settings">
												<input type="checkbox" id="lrgawidget-lock-settings" name="lock_settings" value="on" />
												<label for="lrgawidget-lock-settings"><?php _e('Lock settings', 'lara-google-analytics'); ?> - [<?php _e('To unlock after saving, you will need to <b>reset all data</b> and re-authorize with Google Analytics', 'lara-google-analytics'); ?>].</label>
											</div>
										</div>			
										<div>
											<button class="btn btn-primary" type="submit" id="lrgawidget-save-settings"><?php _e('Save', 'lara-google-analytics'); ?></button>
										</div>
										</form>
									</div>
									<div class="col-md-6">
									    <div>
											<h2 ><?php _e('Profile Details', 'lara-google-analytics'); ?></h2>
											 <label><?php _e('Account Name', 'lara-google-analytics'); ?> :</label> <i id="lrgawidget-accname"></i>
											 <br><label><?php _e('Property Name', 'lara-google-analytics'); ?> :</label> <i id="lrgawidget-propname"></i>  
											 <br><label><?php _e('Data Stream Name', 'lara-google-analytics'); ?> :</label> <i id="lrgawidget-dsname"></i>
											 <br><label><?php _e('Data Stream Url', 'lara-google-analytics'); ?> :</label> <i id="lrgawidget-dsrl"></i>
											 <br><label><?php _e('Data Stream Type', 'lara-google-analytics'); ?> :</label> <i id="lrgawidget-dstype"></i>
											 <br><label><?php _e('Property Time Zone', 'lara-google-analytics'); ?> :</label> <i id="lrgawidget-ptimezone"></i> <i id="lrgawidget-timezone-show-error" class="icon fas fa-exclamation-triangle" style="display:none; color: #f39c12;margin-left: 5px;cursor: pointer;"></i>
											 <div style="display:none; margin-top: 15px;" id="lrgawidget-timezone-error">
												 <div class="alert alert-warning">
													<i class="icon fas fa-exclamation-triangle"></i><?php _e('The selected property is using a different timezone than your server\'s time zone, which <u>may</u> cause inaccurate dates/values.', 'lara-google-analytics'); ?>
													    <div style="margin-left: 28px;margin-top: 10px;"> 
															<?php _e('Property Time Zone', 'lara-google-analytics'); ?> : <b id="lrgawidget-tz-error-vtimezone"></b>
															<br> WordPress <?php _e('Time Zone', 'lara-google-analytics'); ?> : <b id="lrgawidget-tz-error-stimezone"></b>
														</div>
												 </div>
											 </div>											 
										</div> 
										
										<div>
											<div id="ga4-notfound-answer" class="alert" style="background-color: #eef7fb; margin-top:5px;">
												<p><b><?php _e('Cannot find your Google Universal Analytics property ?', 'lara-google-analytics'); ?></b></p>
												<p><?php _e('Google will stop processing data for Universal Analytics properties on July 1, 2023, so it is time to upgrade to the new GA4 properties.', 'lara-google-analytics'); ?></p>
												<p><b><?php _e('Upgrading to <b>Google Analytics 4</b> :', 'lara-google-analytics'); ?></b></p>
												<span style="margin-top:10px;">
													<ol>
														<li><?php _e('In <a target="_blank" href="//analytics.google.com/analytics/web/"> Google Analytics</a>, click <i class="fas fa-cog fa-fw"></i> Admin.', 'lara-google-analytics'); ?></li>
														<li><?php _e('In the <b>Account</b> column, make sure that your desired account is selected.', 'lara-google-analytics'); ?></li>
														<li><?php _e('In the <b>Property</b> column, select the Universal Analytics property that currently collects data for your website.', 'lara-google-analytics'); ?></li>
														<li><?php _e('In the Property column, click <b>GA4 Setup Assistant</b>, then click <b>Get Started</b> under <u>I want to create a new Google Analytics 4 property</u>, then click on <b>Create and Continue</b>.', 'lara-google-analytics'); ?></li>
														<li><?php _e('On the <b>Set up a Google tag</b> page, select <b>Install a Google tag </b> and click <b>Next</b>, then click <b>Done</b> on the next page.', 'lara-google-analytics'); ?></li>
<?php if ($lock_settings !== "on"){ ?>
														<li> <?php _e('When done, click <b>Reload</b>, to reload your Google Analytics properties.', 'lara-google-analytics'); ?></li>
													</ol>
													<span class="pull-right"><a class="btn btn-primary" href="#" data-reload="lrgawidget_reload_tab"><?php _e('Reload', 'lara-google-analytics'); ?></a></span>
<?php }else{ ?>
														<li> <?php _e('When done, contact your WordPress administrator, to unlock your widget settings.', 'lara-google-analytics'); ?></li>
													</ol>
<?php } ?>
												<p> - <?php _e('After setting up your new Google Analytics GA4 property, it will take around <b>24-48 hours</b> for the data to be shown.', 'lara-google-analytics'); ?><br>
												    - <?php _e('Old Universal Analytics data <b>will not</b> be migrated to your new GA4 property.', 'lara-google-analytics'); ?></p>
												</span>
											</div>
										</div>
										
									    <div class="hidden" id="lrgawidget-scpurl-message">
											<h2><?php _e('Search Console Property', 'lara-google-analytics'); ?></h2>
											 <label><?php _e('Property Url', 'lara-google-analytics'); ?> :</label> <i id="lrgawidget-scpurl"></i> 
											 <div class="hidden" id="lrgawidget-scpurl-error">
												 <div class="alert alert-warning">
													<i class="icon fas fa-exclamation-triangle"></i><?php _e('Please choose a valid <b>Search Console Property URL</b>, or the widget will not be able to get keywords data for your website.', 'lara-google-analytics'); ?>
													<br><br>
													  <ol>
													    <li> <?php _e('If you cannot find your website, please go to <a href="https://www.google.com/webmasters/tools/" target="_blank">Google Search Console</a> and click on <b>Add a property</b>, to add your website.', 'lara-google-analytics'); ?></li>
													    <li> <?php _e('After adding your website to <b>Google Search Console</b> and verifying ownership, click <b>Reload</b>, to reload the <b>Search Console Property URL</b> menu.', 'lara-google-analytics'); ?></li>
													  </ol>												 
													<span class="pull-right"><a class="btn btn-primary" href="#" data-reload="lrgawidget_reload_tab"><?php _e('Reload', 'lara-google-analytics'); ?></a></span>
												 </div>
											 </div>
											 
										</div> 
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- /.tab-pane -->
			<?php } ?>

			<?php if (in_array("perm",DataStore::$RUNTIME["permissions"])){  ?>
			<div class="tab-pane" id="lrgawidget_permissions_tab">
				<div class="row lrgawidget_permissions">
					<div class="col-md-3">
						<ul class="nav nav-pills nav-stacked" id="lrgawidget_permissions_roles">
						</ul>
					</div>
					<div class="col-md-9">
						<form  id="lrgawidget_permissions_form" role="form">
							<input name="action" type="hidden" value="setPermissions">
							<div class="tab-content" id="lrgawidget_permissions_list">
							</div>
							<div>
								<button class="btn btn-primary pull-right" type="submit"><i class="far fa-save fa-fw"></i> <?php _e('Save', 'lara-google-analytics'); ?></button>
							</div>
						</form>
					</div>
				</div>
			</div>			
			<?php } ?>
			
			<?php if (in_array("sessions",DataStore::$RUNTIME["permissions"])){  ?>
			<div class="tab-pane" id="lrgawidget_sessions_tab">
				<div id="lrgawidget_sessions_chartDiv" style="height: 350px; width: 100%;">
					<div class="overlay" id="lrgawidget_loading_big">
					  <i class="fas fa-sync-alt fa-2x fa-spin" ></i>
					</div>
				</div>
				<div id="lrga-legendholder"></div>
				<div id="lrga-xologoholder" class="hidden-xs"><a href="https://www.xtraorbit.com/wordpress-google-analytics-dashboard-widget/?utm_source=InApp&utm_medium=Main_Screen" target="_blank"><img src="<?php echo lrgawidget_plugin_dist_url.'/img/xo_small_transp.png'; ?>"></a></div>
				<div class="box-footer hidden-xs hidden-sm" id="lrgawidget_sb-main">
					<div class="row">
					</div>
				</div>
			</div>			<!-- /.tab-pane -->
			<?php } ?>

			<?php if (in_array("pages",DataStore::$RUNTIME["permissions"])){  ?>
			<div class="tab-pane" id="lrgawidget_pages_tab">
				<div class="row" >
					<div class="col-md-6">
						<div>
							<table id="lrgawidget_pages_dataTable" class="table table-bordered table-hover" cellspacing="0" width="100%" >
								<thead><tr><th><?php _e('ID', 'lara-google-analytics'); ?></th><th><?php _e('Page', 'lara-google-analytics'); ?></th><th><?php _e('Pageviews', 'lara-google-analytics'); ?></th><th><?php _e('Percentage', 'lara-google-analytics'); ?></th></tr></thead>
								<tbody></tbody>						
							</table>					
						</div>
					</div>
					<div class="col-md-6 hidden-xs hidden-sm" >
						<canvas id="lrgawidget_pages_chartDiv" width="350px" height="350px"></canvas>
						<div  id='lrgawidget_pages_legendDiv'></div>
					</div>
				</div>
			</div>
            <?php } ?>			

			<?php if (in_array("realtime",DataStore::$RUNTIME["permissions"])){  ?>
			<div class="tab-pane" id="lrgawidget_realtime_tab">
				<div class="row" >
					<div class="col-md-6">
						<div id="lrgawidget_realtime_activenow">
							<div class="lrgawidget_realtime_rn"><?php _e('Realtime Overview', 'lara-google-analytics'); ?></div>
							<div id="lrgawidget_rttotal"><i class="fas fa-sync-alt fa-spin" style="margin-top: 25px;"></i></div>
							<div class="lrgawidget_realtime_an"><?php _e('Users in last 30 minutes', 'lara-google-analytics'); ?></div>
						</div>
						<div id="lrgawidget_realtime_dimensions_cn">
							<div id="lrgawidget_realtime_dimensions"></div>
						</div>				
					</div>
					<div class="col-md-6 hidden-xs hidden-sm" >
						<div>
							<div><h2><b><?php _e('Top Active Pages', 'lara-google-analytics'); ?></b></h2></div>
							<table id="lrgawidget_realtime_dataTable" class="table table-bordered table-hover" cellspacing="0" width="100%" >
								<thead><tr><th><?php _e('ID', 'lara-google-analytics'); ?></th><th><?php _e('Active Page', 'lara-google-analytics'); ?></th><th><?php _e('Views', 'lara-google-analytics'); ?></th><th><?php _e('Percentage', 'lara-google-analytics'); ?></th></tr></thead>
								<tbody></tbody>						
							</table>					
						</div>
					</div>
				</div>
			</div>
			<?php } ?>

			<?php if (in_array("countries",DataStore::$RUNTIME["permissions"])){  ?>
			<div class="tab-pane" id="lrgawidget_countries_tab">
				<div class="row">
					<div class="col-md-6">
						<div>
							<table id="lrgawidget_countries_dataTable" class="table table-bordered table-hover" cellspacing="0" width="100%">
								<thead><tr><th><?php _e('Country', 'lara-google-analytics'); ?></th><th><?php _e('Active Users', 'lara-google-analytics'); ?></th><th><?php _e('Percentage', 'lara-google-analytics'); ?></th></tr></thead>
								<tbody>
								</tbody>
							</table>					
						</div>
					</div>
					<div class="col-md-6 hidden-xs hidden-sm" >
						<div id='lrgawidget_countries_chartDiv' style="height: 350px; width: 100%;"></div>
					</div>
				</div>
			</div>
			<?php } ?>

			<?php if (in_array("browsers",DataStore::$RUNTIME["permissions"])){  ?>
			<div class="tab-pane" id="lrgawidget_browsers_tab">
				<div class="row">
					<div class="col-md-6">
						<div>
							<table id="lrgawidget_browsers_dataTable" class="table table-bordered table-hover" cellspacing="0" width="100%">
								<thead><tr><th><?php _e('ID', 'lara-google-analytics'); ?></th><th><?php _e('Browser', 'lara-google-analytics'); ?></th><th><?php _e('Active Users', 'lara-google-analytics'); ?></th><th><?php _e('Percentage', 'lara-google-analytics'); ?></th></tr></thead>
								<tbody></tbody>
							</table>					
						</div>
					</div>
					<div class="col-md-6 hidden-xs hidden-sm" >
							<canvas id="lrgawidget_browsers_chartDiv" width="350px" height="350px"></canvas>
							<div  id='lrgawidget_browsers_legendDiv'></div>
					</div>
				</div>
			</div>
			<?php } ?>
			

			<?php if (in_array("languages",DataStore::$RUNTIME["permissions"])){  ?>
			<div class="tab-pane" id="lrgawidget_languages_tab">
				<div class="row" >
					<div class="col-md-6">
						<div>
							<table id="lrgawidget_languages_dataTable" class="table table-bordered table-hover" cellspacing="0" width="100%" >
								<thead><tr><th><?php _e('ID', 'lara-google-analytics'); ?></th><th><?php _e('Language', 'lara-google-analytics'); ?></th><th><?php _e('Active Users', 'lara-google-analytics'); ?></th><th><?php _e('Percentage', 'lara-google-analytics'); ?></th></tr></thead>
								<tbody></tbody>						
							</table>					
						</div>
					</div>
					<div class="col-md-6 hidden-xs hidden-sm" >
						<canvas id="lrgawidget_languages_chartDiv" width="350px" height="350px"></canvas>
						<div  id='lrgawidget_languages_legendDiv'></div>
					</div>
				</div>
			</div>
			<?php } ?>
			

			<?php if (in_array("os",DataStore::$RUNTIME["permissions"])){  ?>
			<div class="tab-pane" id="lrgawidget_os_tab">
				<div class="row" >
					<div class="col-md-6">
						<div>
							<table id="lrgawidget_os_dataTable" class="table table-bordered table-hover" cellspacing="0" width="100%" style="cursor:pointer">
								<thead><tr><th><?php _e('ID', 'lara-google-analytics'); ?></th><th><?php _e('Operating System', 'lara-google-analytics'); ?></th><th><?php _e('Active Users', 'lara-google-analytics'); ?></th><th><?php _e('Percentage', 'lara-google-analytics'); ?></th></tr></thead>
								<tbody></tbody>						
							</table>					
						</div>
					</div>
					<div class="col-md-6 hidden-xs hidden-sm" >
						<canvas id="lrgawidget_os_chartDiv" width="350px" height="350px"></canvas>
						<div  id='lrgawidget_os_legendDiv'></div>
					</div>
				</div>
			</div>
			<?php } ?>
			
			<?php if (in_array("devices",DataStore::$RUNTIME["permissions"])){  ?>
			<div class="tab-pane" id="lrgawidget_devices_tab">
				<div class="row" >
					<div class="col-md-6">
						<div>
							<table id="lrgawidget_devices_dataTable" class="table table-bordered table-hover" cellspacing="0" width="100%" style="cursor:pointer">
								<thead><tr><th><?php _e('ID', 'lara-google-analytics'); ?></th><th><?php _e('Device Type', 'lara-google-analytics'); ?></th><th><?php _e('Active Users', 'lara-google-analytics'); ?></th><th><?php _e('Percentage', 'lara-google-analytics'); ?></th></tr></thead>
								<tbody></tbody>						
							</table>					
						</div>
					</div>
					<div class="col-md-6 hidden-xs hidden-sm" >
						<canvas id="lrgawidget_devices_chartDiv" width="350px" height="350px"></canvas>
						<div  id='lrgawidget_devices_legendDiv'></div>
					</div>
				</div>
			</div>
			<?php } ?>			

			<?php if (in_array("screenres",DataStore::$RUNTIME["permissions"])){  ?>
			<div class="tab-pane" id="lrgawidget_screenres_tab">
				<div class="row" >
					<div class="col-md-6">
						<div>
							<table id="lrgawidget_screenres_dataTable" class="table table-bordered table-hover" cellspacing="0" width="100%" >
								<thead><tr><th><?php _e('ID', 'lara-google-analytics'); ?></th><th><?php _e('Screen Resolution', 'lara-google-analytics'); ?></th><th><?php _e('Active Users', 'lara-google-analytics'); ?></th><th><?php _e('Percentage', 'lara-google-analytics'); ?></th></tr></thead>
								<tbody></tbody>	
							</table>					
						</div>
					</div>
					<div class="col-md-6 hidden-xs hidden-sm" >
						<canvas id="lrgawidget_screenres_chartDiv" width="350px" height="350px"></canvas>
						<div  id='lrgawidget_screenres_legendDiv'></div>
					</div>
				</div>
			</div>
			<?php } ?>

			<?php if (in_array("keywords",DataStore::$RUNTIME["permissions"])){  ?>
			<div class="tab-pane" id="lrgawidget_keywords_tab">
				<div class="row" >
					<div class="col-md-6">
						<div>
							<table id="lrgawidget_keywords_dataTable" class="table table-bordered table-hover" cellspacing="0" width="100%" >
								<thead><tr><th><?php _e('ID', 'lara-google-analytics'); ?></th><th><?php _e('Keyword', 'lara-google-analytics'); ?></th><th><?php _e('Clicks', 'lara-google-analytics'); ?></th><th><?php _e('Impressions', 'lara-google-analytics'); ?></th><th><?php _e('CTR', 'lara-google-analytics'); ?></th><th><?php _e('Position', 'lara-google-analytics'); ?></th></tr></thead>
								<tbody></tbody>							
							</table>
						</div>
						
						<div class="callout" style="margin: 5px 0 0 0; padding: 5px;">
							* <?php _e('<b>Position</b> is the average ranking of your website URLs for that query or keyword, on Google search results.', 'lara-google-analytics'); ?>
						</div>						
						
					</div>
					<div class="col-md-6 hidden-xs hidden-sm" >
						<canvas id="lrgawidget_keywords_chartDiv" width="350px" height="350px"></canvas>
						<div  id='lrgawidget_keywords_legendDiv'></div>
						<div class="pull-right"><img src="<?php echo lrgawidget_plugin_dist_url.'/img/google-search-console.png'; ?>"></div>
					</div>
				</div>
			</div>
			<?php } ?>			

			<?php if (in_array("sources",DataStore::$RUNTIME["permissions"])){  ?>
			<div class="tab-pane" id="lrgawidget_sources_tab">
				<div class="row" >
					<div class="col-md-6">
						<div>
							<table id="lrgawidget_sources_dataTable" class="table table-bordered table-hover" cellspacing="0" width="100%" >
								<thead><tr><th><?php _e('ID', 'lara-google-analytics'); ?></th><th><?php _e('Source', 'lara-google-analytics'); ?></th><th><?php _e('Active Users', 'lara-google-analytics'); ?></th><th><?php _e('Percentage', 'lara-google-analytics'); ?></th></tr></thead>
								<tbody></tbody>							
							</table>					
						</div>
					</div>
					<div class="col-md-6 hidden-xs hidden-sm" >
						<canvas id="lrgawidget_sources_chartDiv" width="350px" height="350px"></canvas>
						<div  id='lrgawidget_sources_legendDiv'></div>
					</div>
				</div>
			</div>
			<?php } ?>
<style>

#lrgawidget_gopro_tab .lrga_gpro_clr1 {	color: #eaeaea }
#lrgawidget_gopro_tab .lrga_gpro_clr2 {	color: #3987b4; }
#lrgawidget_gopro_tab .lrga_gpro_clr3 {	color: #FFF; }
#lrgawidget_gopro_tab .lrga_gpro_clr4 {	color: #fe2200 !important; }
#lrgawidget_gopro_tab .lrga_gpro_clr5 {	color: #282e4f }
#lrgawidget_gopro_tab .lrga_gpro_clr6 {	color: #7A7A7A }


#lrgawidget_gopro_tab .lrga_gpro_boxtopb {
	border-top: 3px solid #dd4b39;
}

#lrgawidget_gopro_tab .lrga_gpro_feature {
	background-color: #F8F8F8;
	border-bottom: 2px solid #fff;
	padding-top:10px;
	padding-bottom:10px;

}

#lrgawidget_gopro_tab .lrga_gpro_features{
	background-color: #F8F8F8;
	border-bottom: 2px solid #fff;
	padding: 25px;
	margin: 0px 1px 5px 1px;
}

#lrgawidget_gopro_tab .lrga_gpro_demo {
	background-color: #f8f8f8;
	padding-top:20px;
	padding-bottom:5px;

}

#lrgawidget_gopro_tab .lrga_gpro_header {
	color: #FFFFFF;
	background-color: #282C31;
	padding-top:20px;
	padding-bottom:5px;
	margin: 1px 1px 0px 1px;

}

#lrgawidget_gopro_tab .lrga_gpro_headerh {
	font-size: 25px;
	font-weight: bold;

}

#lrgawidget_gopro_tab .lrga_gpro_support {
	background-color: #F8F8F8;
	border: 2px solid #ededed;
	padding: 10px;
	border-radius: 15px;
	min-height: 270px;
	margin-bottom: 15px;  
}

#lrgawidget_gopro_tab .lrga_gpro_support p{
  font-size: 14px;
	text-align: center;
	padding: 5px;
}

#lrgawidget_gopro_tab .lrga_gpro_who {
	background-color: #F8F8F8;
	border: 2px solid #ededed;
	padding: 10px;
	margin-bottom:15px;
	border-radius: 15px;
	background-image: url(<?php echo lrgawidget_plugin_dist_url.'/img/xo_footer.png'; ?>);
	background-position: center bottom;
	background-repeat: no-repeat;
}

#lrgawidget_gopro_tab .lrga_gpro_featured {
	background-color: #F8F8F8;
	border: 2px solid #ededed;
	padding: 10px;
	margin-bottom:15px;
}

#lrgawidget_gopro_tab .lrga_gpro_who p{
  font-size: 14px;
	text-align: center;
	padding: 5px;
}

#lrgawidget_gopro_tab .lrga_gpro_demo p{
  font-size: 14px;
	text-align: center;
	padding: 5px;
}

#lrgawidget_gopro_tab .lrga_gpro_btn_dark {
    background-color: #272e38;
    color: #FFFFFF;
}

#lrgawidget_gopro_tab button i{
	margin-right:5px;
	
}

#lrgawidget_gopro_tab button#rating i{
	margin-right: -4px;
	color: #FDDC00;
}

#lrgawidget_gopro_tab .lrga_gpro_features i{
	margin-bottom:10px;
	
}


</style>
<div class="tab-pane" id="lrgawidget_gopro_tab">
	<div class="row">
		<div class="col-lg-7 text-center">
			<div class="row">
				<div class="col-md-12">
					<div class="lrga_gpro_header">
						<p class='lrga_gpro_headerh'><i class="fas fa-unlock-alt fa-fw"></i> <?php _e('Go Premium !', 'lara-google-analytics'); ?></p>
						<p><?php _e('Buy the Premium version and get access to these amazing features', 'lara-google-analytics'); ?></p>
					</div>
				</div>
			</div>		

			<div class="row text-left lrga_gpro_features lrga_gpro_boxtopb">

				<div class="col-md-12 lrga_gpro_featured">
					<ul class="fa-ul" style="margin-top: 5px;margin-bottom: 5px;">
						<li><i class="fa-li fas fa-network-wired fa-lg fa-fw"></i><b><?php _e('Multisite Multi-Network enabled : </b>Every blog/site in your network can has its own analytics tracking code and dashboard widget.', 'lara-google-analytics'); ?></li>
						<li><i class="fa-li fas fa-user-lock fa-lg fa-fw"></i><b><?php _e('Permissions : </b>Easily control which data is viwed by your blog admins and users (also compatible with Multisite Multi-Network).', 'lara-google-analytics'); ?></li>
						<li><i class="fa-li fas fa-lock fa-lg fa-fw"></i><b><?php _e('Lock Settings : </b>Prevent users from changing the widget settings or viewing other Google analytics profiles.', 'lara-google-analytics'); ?></li>
						<li><i class="fa-li fas fa-store fa-lg fa-fw"></i><b><?php _e('eCommerce graphs : </b>Highly customized earnings graphs, for popular WordPress eCommerce plugins, like WooCommerce.', 'lara-google-analytics'); ?></li>
					</ul>
				</div>				
			
				<div class="col-md-6">
					<ul class="fa-ul">
						<li><i class="fa-li fas fa-search fa-lg fa-fw"></i><?php _e('Keywords ( provided by Google Search Console).', 'lara-google-analytics'); ?></li>
						<li><i class="fa-li far fa-clock fa-lg fa-fw"></i><?php _e('Real Time site visitors', 'lara-google-analytics'); ?> </li>
						<li><i class="fa-li fas fa-external-link-square-alt fa-lg fa-fw"></i><?php _e('Traffic sources.', 'lara-google-analytics'); ?></li>
						<li><i class="fa-li fas fa-globe fa-lg fa-fw"></i><?php _e('Visitors Countries', 'lara-google-analytics'); ?>.</li>
					</ul>
				</div>
				<div class="col-md-6">
					<ul class="fa-ul">
						<li><i class="fa-li fas fa-desktop fa-lg fa-fw"></i><?php _e('Operating Systems versions (Windows 7, Windows 8 .. etc.).', 'lara-google-analytics'); ?></li>
						<li><i class="fa-li fas fa-tablet-alt fa-lg fa-fw"></i><?php _e('Device Types and brands (Samsung, Apple .. etc.).', 'lara-google-analytics'); ?></li>
					</ul>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 text-center">
					<div class="lrga_gpro_feature lrga_gpro_boxtopb">
						<span class="fa-stack fa-2x"><i class="fas fa-circle fa-stack-2x lrga_gpro_clr3"></i> <i class="far fa-circle lrga_gpro_clr1"></i> <i class="fas fa-calendar-alt fa-stack-1x "></i></span>
						<h4><?php _e('Check any Date Range, not just the last 30 days', 'lara-google-analytics'); ?></h4>
					</div>
				</div>
				<div class="col-md-6 text-center">
					<div class="lrga_gpro_feature lrga_gpro_boxtopb">
						<span class="fa-stack fa-2x"><i class="fas fa-circle fa-stack-2x lrga_gpro_clr3"></i> <i class="far fa-circle lrga_gpro_clr1"></i> <i class="fas fa-sync-alt fa-stack-1x "></i></span>
						<h4><?php _e('12 Months of Free Updates and Support', 'lara-google-analytics'); ?></h4>
					</div>
				</div>
			</div><br>
			<div class="row">
				<div class="col-md-12">
					<div class="lrga_gpro_demo lrga_gpro_boxtopb">
						<p class="lrga_gpro_clr6"><?php _e('Check the<strong> Demo</strong> to see exactly how the premium version looks like, and what you will get from it without leaving your wordpress dashboard', 'lara-google-analytics'); ?> &nbsp; 
							<div>	
								<a class="lrgawidget_view_demo" href="https://wpdemo.whmcsadmintheme.com/demo.php?utm_source=InApp&utm_medium=Launch_Demo" title="Fully working Demo .. When done, press ESC to close this window.">
									<button class="btn btn-warning" type="button"><i  class="fas fa-chevron-right"></i> <strong><?php _e('Launch Demo', 'lara-google-analytics'); ?></strong></button>
								</a>
							</div>
						</p>
					</div>
				</div>
			</div><br>
			<div class="row" style="margin-bottom:15px;">
				<div class="col-md-12 text-center">
					<a href="https://clients.xtraorbit.com/cart.php?a=add&pid=3&utm_source=InApp&utm_medium=Buy_Now" target="_blank">
						<button class="btn btn-danger" type="button"><i  class="fas fa-shopping-cart"></i> <strong><?php _e('Buy Now', 'lara-google-analytics'); ?></strong></button>
					</a>
					<a href="https://www.xtraorbit.com/wordpress-google-analytics-dashboard-widget/?utm_source=InApp&utm_medium=Premium_Features" target="_blank">
						<button class="btn lrga_gpro_btn_dark" type="button"><i  class="fas fa-external-link-square-alt"></i> <strong><?php _e('View all premuim features details', 'lara-google-analytics'); ?></strong></button>
					</a>
				<img style="margin-top:35px;" alt="" class=" img-responsive center-block" src="<?php echo lrgawidget_plugin_dist_url.'/img/xo_payments.png'; ?>"></div>
			</div>
		</div>
		<div class="col-lg-5">
			<div class="row">
				<div class="col-lg-6 text-center">
					<div class="lrga_gpro_who" style="height: 390px;">
						<h4><strong><?php _e('Want to help translating the plugin ?', 'lara-google-analytics'); ?></strong></h4>
						<p><?php _e('You can help translate our free plugin by going to <a href="https://translate.wordpress.org/projects/wp-plugins/lara-google-analytics" target="_blank">WordPress.org</a>. There you can see how many strings are already translated into your language, and translate the rest.', 'lara-google-analytics'); ?></p>
						<br>
					</div>
				</div>
				<div class="col-lg-6 text-center">
					<div class="lrga_gpro_who">
						<h4><strong><?php _e('Who we are & What we do', 'lara-google-analytics'); ?></strong></h4><br><img alt="" class=" img-responsive center-block" src="<?php echo lrgawidget_plugin_dist_url.'/img/xtraorbit_logo.png'; ?>"><br>
						<p><?php _e('<b>XtraOrbit</b> has been providing a wide range of services <b>since 2002</b>, including <b>Web Hosting</b>, <b>Domain Names</b> & <b>Web Development</b>.', 'lara-google-analytics'); ?></p>
						<p><?php _e('We mix creativity with imagination, responsibility with passion, and resourcefulness with fun. That is what we do everyday within our company.', 'lara-google-analytics'); ?></p>
						<a href="https://www.xtraorbit.com/?utm_source=InApp&utm_medium=Check_Us" target="_blank">
							<button class="btn lrga_gpro_btn_dark" type="button"><i class="fas fa-external-link-square-alt"></i> <strong><?php _e('Come, check us out!', 'lara-google-analytics'); ?></strong></button>
						</a>
						<br><br>
					</div>
				</div>
			</div>		
			<div class="row">
				<div class="col-lg-6 text-center">
					<div class="lrga_gpro_support">
						<span class="fa-stack fa-lg"><i class="fas fa-circle fa-stack-2x lrga_gpro_clr3"></i> <i class="far fa-circle fa-stack-2x lrga_gpro_clr4"></i> <i class="far fa-thumbs-up fa-stack-1x lrga_gpro_clr5"></i></span>
						<h4><strong><?php _e('Rate Us', 'lara-google-analytics'); ?></strong></h4>
						<p><?php _e('If you have a free moment, and want to help us spread the word and boost our motivation, please do us a BIG favour and give us 5 Stars rating on wordpress .. The more reviews we get, the more cool features we will add to the plugin :)', 'lara-google-analytics'); ?></p>
						<a href="https://wordpress.org/support/plugin/lara-google-analytics/reviews/" target="_blank">
							<button class="btn btn-default" id="rating" type="button"><?php _e('Let\'s do it, You deserve it', 'lara-google-analytics'); ?> <div><i  class="fas fa-star"></i> <i  class="fas fa-star"></i> <i  class="fas fa-star"></i> <i  class="fas fa-star"></i> <i  class="fas fa-star"></i></div></button>
						</a>
					</div>
				</div>
				<div class="col-lg-6 text-center">
					<div class="lrga_gpro_support">
						<span class="fa-stack fa-lg"><i class="fas fa-circle fa-stack-2x lrga_gpro_clr3"></i> <i class="far fa-circle fa-stack-2x lrga_gpro_clr4"></i> <i class="far fa-question-circle fa-stack-1x lrga_gpro_clr5"></i></span>
						<h4><strong><?php _e('Help & Support', 'lara-google-analytics'); ?></strong></h4><small></small>
						<p><?php _e('If you are facing any issues, need support or have a new feature request, visit the official plugin support forum, where you will be able to submit a support ticket.', 'lara-google-analytics'); ?></p>
							<a href="https://wordpress.org/support/plugin/lara-google-analytics/" target="_blank">
								<button class="btn btn-default" type="button"><i  class="far fa-question-circle lrga_gpro_clr4"></i> <?php _e('Support Center', 'lara-google-analytics'); ?></button>
							</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>	

		</div><!-- /.tab-content -->
	</div>  
  </div>
</div>
<?php } ?>
</div><!-- /.id -->
</div><!-- /.class -->
</div><!-- /.wrap -->

<?php 
$activeTab = "";
if(!empty($actLrgaTabs[0])){
	$activeTab = $actLrgaTabs[0];
}
?>

<!-- /.revise -->
<script type="text/javascript">
	(function($) {
		$(document).ready(function(){
			if (typeof lrgafinishedloading !== 'undefined'){
				$("#lrgawidget_wrap").detach().insertBefore('#dashboard-widgets-wrap');
				$("#lrgawidget_loader").remove();
				$("#lrgawidget_wrap").show();
			}else{
				$("#lrgawidget_adblock_error").detach().insertBefore('#dashboard-widgets-wrap');
				$("#lrgawidget_loader").remove();
				$("#lrgawidget_adblock_error").show();
			}
		});
	})(jQuery);	

	var actLrgaTabs = '<?php echo $activeTab; ?>';
	var lrdataTableLang = {	"sEmptyTable":     <?php echo '"'.__('No data available in table', 'lara-google-analytics').'"'; ?>,
							"sInfo":           <?php echo '"'.__('Showing _START_ to _END_ of _TOTAL_ entries', 'lara-google-analytics').'"'; ?>,
							"sInfoEmpty":      <?php echo '"'.__('Showing 0 to 0 of 0 entries', 'lara-google-analytics').'"'; ?>,
							"sInfoFiltered":   <?php echo '"'.__('(filtered from _MAX_ total entries)', 'lara-google-analytics').'"'; ?>,
							"sInfoPostFix":    "",
							"sInfoThousands":  ",",
							"sLengthMenu":     <?php echo '"'.__('Show _MENU_ entries', 'lara-google-analytics').'"'; ?>,
							"sLoadingRecords": <?php echo '"'.__('Loading...', 'lara-google-analytics').'"'; ?>,
							"sProcessing":     <?php echo '"'.__('Processing...', 'lara-google-analytics').'"'; ?>,
							"sSearch":         <?php echo '"'.__('Search:', 'lara-google-analytics').'"'; ?>,
							"sZeroRecords":    <?php echo '"'.__('No matching records found', 'lara-google-analytics').'"'; ?>,
							"oPaginate": {
								"sFirst":    <?php echo '"'.__('First', 'lara-google-analytics').'"'; ?>,
								"sLast":     <?php echo '"'.__('Last', 'lara-google-analytics').'"'; ?>,
								"sNext":     <?php echo '"'.__('Next', 'lara-google-analytics').'"'; ?>,
								"sPrevious": <?php echo '"'.__('Previous', 'lara-google-analytics').'"'; ?>
							},
							"oAria": {
								"sSortAscending":  <?php echo '"'.__(': activate to sort column ascending', 'lara-google-analytics').'"'; ?>,
								"sSortDescending": <?php echo '"'.__(': activate to sort column descending', 'lara-google-analytics').'"'; ?>
							}
						};
	var lrwidgetenLang =  { resetmsg       : <?php echo '"'.__('All saved authentication data will be removed. Do you want to continue ?!', 'lara-google-analytics').'"'; ?>,
							setuprequired  : <?php echo '"'.__('Initial Setup Required! - Please contact an administratior to complete the widget setup.', 'lara-google-analytics').'"'; ?>,
							selectproperty : <?php echo '"'.__('Select Property', 'lara-google-analytics').'"'; ?>,
							selectpropertyurl : <?php echo '"'.__('Select Property URL', 'lara-google-analytics').'"'; ?>,
							emptygaconsole : <?php echo '"'.__('No data available in table. <span class=\'pull-left\'><ul><li>Did you <a href=\'https://support.google.com/webmasters/answer/34592?hl=en\' target=\'_blank\'>add your website to Google Search Console</a> ?</li><li>After adding your website to Google Search Console, did you save it as the <b>Search Console Property URL</b> in the widget <b>Settings</b> tab ?</li><li>If you have added your website recently, keywords may take some time to appear.</li></ul></span>', 'lara-google-analytics').'"'; ?>,
							lastsevendays  : <?php echo '"'.__('Last 7 Days', 'lara-google-analytics').'"'; ?>,
							lastthirtydays : <?php echo '"'.__('Last 30 Days', 'lara-google-analytics').'"'; ?>,
							thismonth      : <?php echo '"'.__('This Month', 'lara-google-analytics').'"'; ?>,
							lastmonth      : <?php echo '"'.__('Last Month', 'lara-google-analytics').'"'; ?>,
							cached         : <?php echo '"'.__('cached', 'lara-google-analytics').'"'; ?>,
							realtime       : <?php echo '"'.__('Real Time', 'lara-google-analytics').'"'; ?>,
							inactive       : <?php echo '"'.__('inactive', 'lara-google-analytics').'"'; ?>,
							total          : <?php echo '"'.__('Total', 'lara-google-analytics').'"'; ?>,
							noactiveusers  : <?php echo '"'.__('No active users', 'lara-google-analytics').'"'; ?>,
							activeusers  : <?php echo '"'.__('Active Users', 'lara-google-analytics').'"'; ?>
						  };  

	var lrwidgetDateLang =	{	"format": "MM/DD/YYYY",
								"separator": " - ",
								"applyLabel": <?php echo '"'.__('Apply', 'lara-google-analytics').'"'; ?>,
								"cancelLabel": <?php echo '"'.__('Cancel', 'lara-google-analytics').'"'; ?>,
								"fromLabel": <?php echo '"'.__('From', 'lara-google-analytics').'"'; ?>,
								"toLabel": <?php echo '"'.__('To', 'lara-google-analytics').'"'; ?>,
								"customRangeLabel": <?php echo '"'.__('Custom Range', 'lara-google-analytics').'"'; ?>,
								"weekLabel": <?php echo '"'.__('W', 'lara-google-analytics').'"'; ?>,
								"daysOfWeek": [
									<?php echo '"'.__('Su', 'lara-google-analytics').'"'; ?>,
									<?php echo '"'.__('Mo', 'lara-google-analytics').'"'; ?>,
									<?php echo '"'.__('Tu', 'lara-google-analytics').'"'; ?>,
									<?php echo '"'.__('We', 'lara-google-analytics').'"'; ?>,
									<?php echo '"'.__('Th', 'lara-google-analytics').'"'; ?>,
									<?php echo '"'.__('Fr', 'lara-google-analytics').'"'; ?>,
									<?php echo '"'.__('Sa', 'lara-google-analytics').'"'; ?>
								],
								"monthNames": [
									<?php echo '"'.__('January', 'lara-google-analytics').'"'; ?>,
									<?php echo '"'.__('February', 'lara-google-analytics').'"'; ?>,
									<?php echo '"'.__('March', 'lara-google-analytics').'"'; ?>,
									<?php echo '"'.__('April', 'lara-google-analytics').'"'; ?>,
									<?php echo '"'.__('May', 'lara-google-analytics').'"'; ?>,
									<?php echo '"'.__('June', 'lara-google-analytics').'"'; ?>,
									<?php echo '"'.__('July', 'lara-google-analytics').'"'; ?>,
									<?php echo '"'.__('August', 'lara-google-analytics').'"'; ?>,
									<?php echo '"'.__('September', 'lara-google-analytics').'"'; ?>,
									<?php echo '"'.__('October', 'lara-google-analytics').'"'; ?>,
									<?php echo '"'.__('November', 'lara-google-analytics').'"'; ?>,
									<?php echo '"'.__('December', 'lara-google-analytics').'"'; ?>
								],
								"firstDay": 1
							};
</script>