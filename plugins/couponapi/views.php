<?php

/*******************************************************************************
 *
 *  Copyrights 2017 to Present - Sellergize Web Technology Services Pvt. Ltd. - ALL RIGHTS RESERVED
 *
 * All information contained herein is, and remains the
 * property of Sellergize Web Technology Services Pvt. Ltd.
 *
 * The intellectual and technical concepts & code contained herein are proprietary
 * to Sellergize Web Technology Services Pvt. Ltd. (India), and are covered and protected
 * by copyright law. Reproduction of this material is strictly forbidden unless prior
 * written permission is obtained from Sellergize Web Technology Services Pvt. Ltd.
 *
 * ******************************************************************************/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

function couponapi_display_settings() {
	//Bootstrap CSS
	wp_register_style('bootstrap.min', plugins_url('css/bootstrap.min.css', __FILE__));
	wp_enqueue_style('bootstrap.min');
	//Custom CSS
	wp_register_style('couponapi_css', plugins_url('css/couponapi_style.css', __FILE__));
	wp_enqueue_style('couponapi_css');
	//Bootstrap JS
	wp_register_script('bootstrap.min', plugins_url('js/bootstrap.min.js', __FILE__), array('jquery'));
	wp_enqueue_script('bootstrap.min');

	set_time_limit(0);

	// Get Messages
	if (!empty($_COOKIE['message'])) {
		$message = stripslashes($_COOKIE['message']);
		echo '<script>document.cookie = "message=; expires=Thu, 01 Jan 1970 00:00:00 UTC;"</script>'; // php works only before html
	}

	$couponapi_config = couponapi_get_config();

	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	// GET CONFIG DETAILS
	$sql = "SELECT
						(SELECT value FROM " . $wp_prefix . "couponapi_config WHERE name = 'autopilot') autopilot,
						(SELECT value FROM " . $wp_prefix . "couponapi_config WHERE name = 'API_KEY') API_KEY,
						(SELECT value FROM " . $wp_prefix . "couponapi_config WHERE name = 'generic_import_image') generic_import_image,
						(SELECT value FROM " . $wp_prefix . "couponapi_config WHERE name = 'set_as_featured_image') set_as_featured_image,
						(SELECT value FROM " . $wp_prefix . "couponapi_config WHERE name = 'last_extract') last_extract,
						(SELECT value FROM " . $wp_prefix . "couponapi_config WHERE name = 'default_end_date') default_end_date,
						(SELECT value FROM " . $wp_prefix . "couponapi_config WHERE name = 'cashback') cashback,
						(SELECT value FROM " . $wp_prefix . "couponapi_config WHERE name = 'last_cron') last_cron,
						(SELECT value FROM " . $wp_prefix . "couponapi_config WHERE name = 'import_images') import_images,
						(SELECT value FROM " . $wp_prefix . "couponapi_config WHERE name = 'use_logos') use_logos,
						(SELECT value FROM " . $wp_prefix . "couponapi_config WHERE name = 'import_locations') import_locations,
						(SELECT value FROM " . $wp_prefix . "couponapi_config WHERE name = 'ctype_code') ctype_code,
						(SELECT value FROM " . $wp_prefix . "couponapi_config WHERE name = 'ctype_deal') ctype_deal,
						(SELECT value FROM " . $wp_prefix . "couponapi_config WHERE name = 'store') store,
						(SELECT value FROM " . $wp_prefix . "couponapi_config WHERE name = 'category') category,
						(SELECT value FROM " . $wp_prefix . "couponapi_config WHERE name = 'code_text') code_text,
						(SELECT value FROM " . $wp_prefix . "couponapi_config WHERE name = 'expiry_text') expiry_text,
						(SELECT value FROM " . $wp_prefix . "couponapi_config WHERE name = 'batch_size') batch_size
					FROM dual";
	$config = $wpdb->get_row($sql);

	$usage = array();
	$config->brandlogos_key = '';
	$config->is_premium = 0;
	if(empty($config->generic_import_image)) {
		$config->generic_import_image = 'off';
	}

	if (!empty($config->API_KEY)) {
		$usage = json_decode(file_get_contents('https://couponapi.org/api/getUsage/?API_KEY=' . $config->API_KEY), true);
		$result = json_decode(file_get_contents('https://couponapi.org/api/getSettings/?API_KEY=' . $config->API_KEY), true);
		if ($result['result'] and $result['is_premium']) {
			$config->brandlogos_key = $result['brandlogos_key'];
			$config->is_premium = $result['is_premium'];
		}
	}

	$sql = "REPLACE INTO {$wpdb->prefix}couponapi_config (name,value) VALUES ('brandlogos_key','{$config->brandlogos_key}')";
	$wpdb->query($sql);

	if (empty($config->batch_size)) $config->batch_size = 500;
?>
	<div class="wrap" style="background:#F1F1F1;">

		<h2>Coupon API</h2>
		<?php if (!empty($message)) {
			echo $message; // some WP js moves this under the <h2> automatically even if you place this somewhere else. so dont bother too much.
		}
		?>
		<h6><?= __("Import Coupons &amp; Deals from Affiliate Networks","couponapi")?></h6>
	
		<script>
			function confirmDelete() {
				var stm = '<?= __("Are you sure you want to delete all offers imported from Coupon API?","couponapi") ?>';
				var cnf = confirm(stm);
				if (cnf == true) {
					document.getElementById("deleteOffersForm").submit();
				}
			}

			function confirmSync() {
				var cnf = confirm('<?=__("This will drop current offers and pull everything again. Do you want to proceed?","couponapi")?>');
				if (cnf == true) {
					document.getElementById("syncOffersForm").submit();
				}
			}
		</script>

		<hr />
		<div class="row mb-5">
			<div class="col-md-4 mb-5">
				<div class="card p-0 mt-0 bg-dark text-white">
					<div class="card-header"><h5><?=__('Autopilot Settings','couponapi')?></h5></div>
						
					<div class="card-body">

						<form name="autoPilot" role="form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">

							<div class="row">

								<div class="col-12">
									<div class="form-group">
										<label for="API_KEY"><?= __("API Key","couponapi")?></label>
										<input type="text" name="API_KEY" id="API_KEY" class="form-control" value="<?php echo $config->API_KEY; ?>" />
										<?php if(empty($config->API_KEY)) { ?>
											<small style="color:#a7a7a7;"><?= __("Don't have an account?",'couponapi') ?> <a target="_blank" href="https://couponapi.org"><?= __("Register Now","couponapi") ?></a></small>
										<?php } ?>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<div class="custom-control custom-switch">
											<input type="checkbox" <?php if($config->autopilot=='On') { echo 'checked'; } ?> name="autopilot" class="custom-control-input" id="auto-pilot" />
											<label class="custom-control-label" for="auto-pilot" style="display:block;"><?= __('Auto Pilot','couponapi')?></label>
											
										</div>
									</div>
								</div>

								<div class="col-12">
									<div class="form-group">
										<label for="last_extract"><? __('Last Extract :',"couponapi")?></label>
										<div class="row">
											<div class="col-lg-6">
												<input type="date" name="last_extract_date" class="form-control" id="last_extract" value="<?php if (!empty($config->last_extract)) {
																																				echo date('Y-m-d', $config->last_extract + get_option('gmt_offset') * 60 * 60);
																																			} ?>" />
											</div>
											<div class="col-lg-6">
												<input type="time" name="last_extract_time" class="form-control" id="last_extract-time" value="<?php if (!empty($config->last_extract)) {
																																					echo date('H:i:s', $config->last_extract + get_option('gmt_offset') * 60 * 60);
																																				} ?>" />
											</div>
										</div>
									</div>
								</div>

								<div class="col-12">
									<div class="form-group">
										<?php wp_nonce_field('couponapi', 'config_nonce'); ?>
										<input type="hidden" name="action" value="capi_save_api_config" />
										<button class="btn btn-primary btn-block mt-3" style="background:#4e54c8;" type="submit" name="submit_config"> <?= __('Save','couponapi')?> <span class="dashicons dashicons-arrow-right" style="margin-top:2px;"></span></button>
									</div>
								</div>

							</div>

						</form>

					</div>

				</div>
			</div>


			<div class="col-md-4 mb-5">
				<div class="card p-0 mt-0 bg-dark text-white">
				<div class="card-header"><h5><?= __('Import Settings','couponapi')?></h5></div>
					<div class="card-body">

						<form name="feedSetting" role="form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
							<div class="row">

								<div class="col-12 <?= (get_template() == 'clipmydeals' ? '' : 'd-none') ?>">
									<div class="form-group">
										<div class="custom-control custom-switch">
											<input type="checkbox" <?php if($config->cashback=='On' and get_template() == 'clipmydeals') { echo 'checked'; } ?> name="cashback" class="custom-control-input" id="cashback">
  											<label class="custom-control-label" for="cashback" style = "display:block;"><?= __('Cashback Mode','couponapi')?></label>
										</div>
									</div>
								</div>
								<div class="col-12 <?= (!couponapi_is_image_supported(get_template(), $config->use_logos) ? 'd-none' : '') ?>">
									<div class="form-group">
										<div class="custom-control custom-switch">
											<input type="checkbox" <?php if($config->import_images=='On' and couponapi_is_image_supported(get_template(), $config->use_logos)) { echo 'checked'; } ?> name="import_images" class="custom-control-input" id="import_images">
											<label class="custom-control-label" for="import_images" style = "display:block;"><?= __('Import Images', 'couponapi') ?></label>
										</div>
									</div>
								</div>
								<div class="col-12 <?= (couponapi_is_location_supported(get_template()) ? '' : 'd-none') ?>">
									<div class="form-group">
										<div class="custom-control custom-switch">
											<input type="checkbox" <?php if($config->import_locations=='On' and couponapi_is_location_supported(get_template())) { echo 'checked'; } ?> name="import_locations" class="custom-control-input" id="import_locations">
											<label class="custom-control-label" for="import_locations" style = "display:block;"><?= __('Import Countries as Locations','couponapi')?></label>
										</div>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<label for="batch_size"><?= __('Batch Size','couponapi') ?></label>
										<input type="number" name="batch_size" min="1" id="batch_size" class="form-control" value="<?= $config->batch_size ?>" />
									</div>
								</div>
								<?php 
								if(!$couponapi_config['is_theme_supported']){
									$taxonomies = get_taxonomies(array('public' => true))
								?>
									<div class="col-12">
										<div class="form-group">
											<label for="generic_import_image"><?= __('Import Image As','couponapi')?></label>
											<select name="generic_import_image" id="generic_import_image" class="form-control">
												<?php if($config->is_premium) { ?>
												<option value="brandlogos_image" <?= $config->generic_import_image=='brandlogos_image'?'selected':'' ?> ><?= __('BrandLogos.org Images','couponapi') ?></option>
												<?php } ?>
												<option value="coupon_image" <?= ($config->generic_import_image=='coupon_image' or ($config->generic_import_image=='branlogos_image' and !$config->is_premium))?'selected':'' ?> ><?= __('Coupon/Network Images','couponapi') ?></option>
												<option value="off" <?= $config->generic_import_image=='off'?'selected':'' ?> ><?= __('Off','couponapi') ?></option>
											</select>
										</div>
									</div>
									<div class="col-12 <?php echo $config->generic_import_image == 'off' ? 'd-none': 'd-block' ?>" id="capi_featured_image_box">
										<div class="form-group">
											<div class="custom-control custom-switch">
												<input type="checkbox" <?php if($config->set_as_featured_image=='On') { echo 'checked'; } ?> name="set_as_featured_image" class="custom-control-input" id="set_as_featured_image" />
												<label class="custom-control-label" for="set_as_featured_image" style="display:block;"><?= __('Set Images As Featured Image','couponapi')?></label>
											</div>
										</div>
									</div>
									<script>
										const importImagesCheckbox = document.getElementById('generic_import_image');
										const featuredImageBox = document.getElementById('capi_featured_image_box');
		
										importImagesCheckbox.addEventListener('change', function () {
											if (importImagesCheckbox.value == 'off') {
												featuredImageBox.classList.remove('d-block');
												featuredImageBox.classList.add('d-none');
											} else {
												featuredImageBox.classList.remove('d-none');
												featuredImageBox.classList.add('d-block');
											}
										});
									</script>
									<div class="col-12">
										<div class="form-group">
											<label for="store"><?= __('Store','couponapi')?></label>
											<select name="store" id="store" class="form-control">
												<?php
													$config->store = ($config->store??'post_tag');
													foreach ($taxonomies as $taxonomy) {
														?>
														<option value="<?= $taxonomy ?>" <?= $config->store==$taxonomy?'selected':'' ?> ><?= $taxonomy ?></option>
														<?php
													}
												?>
												<option value="none" <?= $config->store=='none'?'selected':'' ?> >None</option>
											</select>
										</div>
										<div class="form-group">
											<label for="category"><?= __('Category','couponapi')?></label>
											<select name="category" id="category" class="form-control">
											<?php
												$config->category = ($config->category ?? 'category');
												foreach ($taxonomies as $taxonomy) {
													?>
													<option value="<?= $taxonomy ?>" <?= $config->category==$taxonomy?'selected':'' ?> ><?= $taxonomy ?></option>
													<?php
												}
											?>
											<option value="none" <?= $config->category=='none'?'selected':'' ?> >None</option>
											</select>
										</div>
										<div class="form-group">
											<label for="code_text"><?= __('Default Code Text','couponapi') ?></label>
											<input type="text" class="form-control col-10" id='code_text' name="code_text" value="<?= $config->code_text??'(not required)' ?>">
										</div>
										<div class="form-group">
											<label for="expiry_text"><?= __('Default Expiry Text' , 'couponapi')?></label>
											<input type="text" class="form-control col-10" id='expiry_text' name="expiry_text" value="<?= $config->expiry_text??'Currently Active' ?>">
										</div>
									</div>
								<?php
									}
								?> 

							</div>

							<?php
							$theme = get_template();
							if ($theme == 'CP' or $theme == 'cp' or $theme == 'CPq' or substr($theme, 0, 2) === "CP" or strpos(wp_get_theme()->get('AuthorURI'), "premiumpress") !== false) {
								$taxonomies = get_terms(['taxonomy' => 'ctype', 'hide_empty' => false]);

							?>
								<div class="row">
									<div class="col-12">
										<h5>CType Mapping</h5>
										<div class="col-12">
											<div class="form-group d-flex flex-row">
												<label for="code" class="p-2">Code </label>
												<select class="form-control form-control-lg" name="ctype_code" id="code">
													<option value="">None</option>
													<?php
													foreach ($taxonomies as $taxonomy) {
														echo '<option value="' . $taxonomy->term_id . '" ' . ($config->ctype_code == $taxonomy->term_id ? "selected" : " ") . '>' . $taxonomy->name . '</option>';
													}
													?>
												</select>
											</div>
											<div class="form-group d-flex flex-row">
												<label for="Deal" class="p-2">Deal </label>
												<select class="form-control form-control-lg" name="ctype_deal" id="deal">
													<option value="">None</option>
													<?php
													foreach ($taxonomies as $taxonomy) {
														echo '<option value="' . $taxonomy->term_id . '" ' . ($config->ctype_deal == $taxonomy->term_id ? "selected" : " ") . '>' . $taxonomy->name . '</option>';
													}
													?>
												</select>
											</div>
										</div>
									</div>
								</div>
							<?php
							} ?>

							<div class="row">
								<div class="col-12">
									<div class="form-group">
										<?php wp_nonce_field('couponapi', 'feed_config_nonce'); ?>
										<input type="hidden" name="action" value="capi_save_import_config" />
										<button class="btn btn-primary btn-block" style="background:#4e54c8;" type="submit" name="submit_feed_config"><?= __('Save','couponapi') ?> <span class="dashicons dashicons-arrow-right" style="margin-top:2px;"></span></button>
									</div>
								</div>
							</div>

						</form>

					</div>
				</div>
			</div>


			<div class="col-md-4 mb-5">
				<div class="card p-0 mt-0 bg-dark text-white">
					<div class="card-header"><h5><?= __('Status','couponapi')?></h5></div>
					<div class="card-body">
						<div class="row">

							<?php
							$troubleshootings = couponapi_get_troubleshootings();
							$critical = $warnings = 0;
							foreach ($troubleshootings as $key => $value) {
								if ($value['status'] == 'warning') {
									$warnings++;
								} elseif ($value['status'] == 'no') {
									$critical++;
								}
							}
							if ($critical or $warnings) {
								if ($critical and $warnings) {
									$issue_msg = sprintf(__("You have %s critical issue(s) and %s warning(s) in your configuration.",'couponapi'),$critical,$warnings);
								} elseif ($critical) {
									$issue_msg = sprintf(__("You have %s critical issue(s) in your configuration.",'couponapi'),$critical);
								} elseif ($warnings) {
									$issue_msg = sprintf(__("You have %s warning(s) in your configuration.",'couponapi'),$warnings) ;
								}
							?>
							<div class="col-12 py-3">
								<p><span class="dashicons dashicons-bell text-warning"></span><?= $issue_msg ?> <a class="text-info" href="<?= admin_url('admin.php?page=couponapi-troubleshoot') ?>"><?= __('See details','couponapi') ?></a></p>
							</div>
							<?php
							}
							?>
							
							<?php 
							
							if(isset($usage['limit_used']) and isset($usage['daily_limit'])){
								$limit_reached = ($usage['limit_used'] >= $usage['daily_limit']);
							}else{
								$limit_reached = false;
							}

							if(!empty($config->API_KEY)) { ?>
							<div class="col-12 py-3">
								<form name="pullFeedForm" role="form" method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
									<input type="hidden" name="action" value="capi_pull_incremental_feed" />
									<?php wp_nonce_field( 'couponapi', 'pull_incremental_feed_nonce' ); ?>
									<button class="btn btn-primary btn-block" style="background:#<?= $limit_reached?"6c757d":"4e54c8"?>;" <?= $limit_reached?"disabled":"" ?> type="submit" name="submit_pull_incremental_feed"><?= __('Fetch Feed Now','couponapi') ?><span class="dashicons dashicons-download"></span></button>
								</form>
							</div>
							<?php } ?>

							<div class="col-12 py-3">
								<b><?= __('Feeds extracted Today','couponapi') ?></b> <br> <?php echo ($usage['limit_used']??'0').__(' out of ',"couponapi").($usage['daily_limit']??'') .($limit_reached?("<div class='p-2'  style='color:#ffa11f; border: 1px #ffa11f solid;'> <i class='dashicons dashicons-warning mx-1' aria-hidden='true'></i>". __('You have reached your daily API call limit. You will be able to make API calls tomorrow',"couponapi"))." </div>":"") ; ?>
							</div>
							
							<?php if($config->autopilot=='On') { ?>
							<div class="col-12 py-3">
								<?php
									$nextSchedule = date('g:i a',wp_next_scheduled('couponapi_pull_incremental_feed_event') + get_option('gmt_offset')*60*60);
								?>
								<b><?= __('Next Scheduled Extract','couponapi') ?></b> <br> <?php echo $nextSchedule; ?>
							</div>

							<?php } ?>

						</div>

						<hr />

						<form name="syncOffersForm" id="syncOffersForm" role="form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
							<input type="hidden" name="action" value="capi_sync_offers" />
							<?php wp_nonce_field( 'couponapi', 'sync_offers_nonce' ); ?>
							
							<button class="btn btn-warning btn-block mt-3" type="button" name="button_delete_offers" <?= $limit_reached?"style='background-color:#6c757d;' disabled":"" ?> onclick="confirmSync();"><?= __('Resync offers with CouponAPI DB','couponapi')?> <span class="dashicons dashicons-update"></span></button>
						</form>
						
						<form name="deleteOffersForm" id="deleteOffersForm" role="form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
							<input type="hidden" name="action" value="capi_delete_offers" />
							<?php wp_nonce_field( 'couponapi', 'delete_offers_nonce' ); ?>
							<button class="btn btn-danger btn-block mt-3" type="button" name="button_delete_offers" onclick="confirmDelete();"><?= __('Drop offers fetched from CouponAPI','couponapi') ?> <span class="dashicons dashicons-trash"></span></button>
						</form>

					</div>
				</div>
			</div>

		</div>
	</div>
<?php
}


function couponapi_display_file_upload() {
	//Bootstrap CSS
	wp_register_style('bootstrap.min', plugins_url('css/bootstrap.min.css', __FILE__));
	wp_enqueue_style('bootstrap.min');
	//Custom CSS
	wp_register_style('couponapi_css', plugins_url('css/couponapi_style.css', __FILE__));
	wp_enqueue_style('couponapi_css');
	//Bootstrap JS
	wp_register_script('bootstrap.min', plugins_url('js/bootstrap.min.js', __FILE__), array('jquery'));
	wp_enqueue_script('bootstrap.min');

	set_time_limit(0);

	// Get Messages
	if (!empty($_COOKIE['message'])) {
		$message = stripslashes($_COOKIE['message']);
		echo '<script>document.cookie = "message=; expires=Thu, 01 Jan 1970 00:00:00 UTC;"</script>'; // php works only before html
	}

	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	if (isset($_POST['submit_upload_feed'])) {

		if (!function_exists('wp_handle_upload')) {
			require_once(ABSPATH . 'wp-admin/includes/file.php');
		}
		$delimiter = ',';
		$file_processed = false;
		$uploadedfile = $_FILES['feed'];
		$upload_overrides = array('test_form' => false, 'mimes' => array('csv' => 'text/csv'));
		$movefile = wp_handle_upload($uploadedfile, $upload_overrides);
		if (!$movefile or isset($movefile['error'])) {
			$error = true;
			$error_msg = 'Error during File Upload :' . $movefile['error'];
		} else {
			$sql = "INSERT INTO " . $wp_prefix . "couponapi_logs (microtime,msg_type,message) VALUES (" . microtime(true) . ",'info','Uploading File')";
			$wpdb->query($sql);
			$feedFile = $movefile['file'];
			include 'saveFileToDb.php';
			$batchSize = '99999'; // process full file
			include 'processBatch.php';
		}
	}

?>
	<div class="wrap" style="background:#F1F1F1;">
		<h2><?= __('CSV Upload','couponapi')?></h2>
		<h6><?= __('Manually Import Coupons &amp; Deals using CSV File.','couponapi') ?></h6>
			
		<?php if (!empty($message)) {
			echo  $message; // some WP js moves this under the <h2> automatically even if you place this somewhere else. so dont bother too much.
		} ?>

		<hr />

		<div class="card p-0 mt-0 col-md-6 bg-dark text-white">
			<div class="card-body">
				<form name="bulkUpload" class="form-inline" role="form" method="post" enctype="multipart/form-data" action="<?php echo admin_url('admin-post.php'); ?>">
					<div class="form-group">
						<input type="file" name="feed" id="feed" />
					</div>
					<input type="hidden" name="action" value="capi_file_upload" />
					<?php wp_nonce_field( 'couponapi', 'file_upload_nonce' ); ?>
					<button class="btn btn-primary" style="background:#4e54c8;" type="submit" name="submit_upload_feed"><?= __('Import','couponapi') ?> <span class="dashicons dashicons-upload"></span></button>
				</form>
			</div>
		</div>
	</div>
<?php
}


function couponapi_display_logs() {
	//Bootstrap CSS
	wp_register_style('bootstrap.min', plugins_url('css/bootstrap.min.css', __FILE__));
	wp_enqueue_style('bootstrap.min');
	//Custom CSS
	wp_register_style('couponapi_css', plugins_url('css/couponapi_style.css', __FILE__));
	wp_enqueue_style('couponapi_css');
	//Bootstrap JS
	wp_register_script('bootstrap.min', plugins_url('js/bootstrap.min.js', __FILE__), array('jquery'));
	wp_enqueue_script('bootstrap.min');

	set_time_limit(0);

	// Get Messages
	if (!empty($_COOKIE['message'])) {
		$message = stripslashes($_COOKIE['message']);
		echo '<script>document.cookie = "message=; expires=Thu, 01 Jan 1970 00:00:00 UTC;"</script>'; // php works only before html
	}

	global $wpdb;
	$wp_prefix = $wpdb->prefix;

	// Get Messages
	if (!empty($_COOKIE['message'])) {
		$message = stripslashes($_COOKIE['message']);
		echo '<script>document.cookie = "message=; expires=Thu, 01 Jan 1970 00:00:00 UTC;"</script>'; // php works only before html
	}

	// Get Logs
	if (!empty($_POST['log_duration'])) {
		$log_duration = $_POST['log_duration'];
	} else {
		$log_duration = '1 HOUR';
	}
	if (!isset($_POST['log_debug'])) {
		$log_debug = "msg_type != 'debug'";
	} else {
		$log_debug = "TRUE";
	}

	$gmt_offset = get_option('gmt_offset');
	$offset_sign = ($gmt_offset < 0) ? '-' : '+';
	$positive_offset = ($gmt_offset < 0) ? $gmt_offset * -1 : $gmt_offset;
	$hours = floor($positive_offset);
	$minutes = round(($positive_offset - $hours) * 60);
	$tz = $offset_sign . $hours . ':' . $minutes;

	$sql_logs = "SELECT
									CONVERT_TZ(logtime,@@session.time_zone,'" . $tz . "') logtime,
									msg_type,
									message,
									CASE
										WHEN msg_type = 'success' then 'green'
										WHEN msg_type = 'error' then 'red'
										WHEN msg_type = 'debug' then '#4a92bf'
									END as color
								FROM  " . $wp_prefix . "couponapi_logs
								WHERE logtime > NOW() - INTERVAL $log_duration
								AND $log_debug
								ORDER BY microtime";
	$logs = $wpdb->get_results($sql_logs);

?>
	<div class="wrap" style="background:#F1F1F1;">

		<h2><?= __('Logs','couponapi')?></h2>
			
		<?php echo (!empty($message) ? $message : ''); // some WP js moves this under the <h2> automatically even if you place this somewhere else. so dont bother too much.
		?>

		<hr />


		<div class="card p-0 mt-0 col-12">
		<div class="card-header d-flex bg-dark text-white">
					<form name="refreshLogs" role="form" class="form-inline w-100" method="post" action="<?php echo str_replace('&tab=','&oldtab=',str_replace( '%7E', '~', $_SERVER['REQUEST_URI'])); ?>&tab=logs">
						<button class="btn btn-primary btn-sm px-2" type="submit" style="background:#4e54c8;" name="submit_fetch_logs"><?= __('Refresh','couponapi')?> <span class="dashicons dashicons-update"></span></button>
						<div class="form-group px-2">
							<label><?= __('Duration','couponapi')?> : </label> 
							<select name="log_duration">
								<option value="1 HOUR" <?php if($log_duration == '1 HOUR') echo 'selected'; ?>><?= __("1 HOUR",'couponapi') ?></option>
								<option value="1 DAY" <?php if($log_duration == '1 DAY') echo 'selected'; ?>><?= __("24 HOUR",'couponapi') ?></option>
								<option value="1 WEEK" <?php if($log_duration == '1 WEEK') echo 'selected'; ?>><?= __("This Week",'couponapi') ?></option>
							</select>
						</div>
						<div class="checkbox px-2">
							<label class="d-inline small"><?= __("Show Debug Logs",'couponapi') ?></label> <input name="log_debug" type="checkbox" <?php if(isset($_POST['log_debug'])) echo 'checked'; ?>>
						</div>
						<a href="<?php echo wp_nonce_url( admin_url( 'admin-post.php?action=capi_download_logs' ), 'couponapi', 'log_nonce' ); ?>" class="btn btn-sm btn-outline-light ml-auto px-2" style="margin-right:10px;"><?= __("Download Logs ",'couponapi') ?><span class="dashicons dashicons-download"></span></a>
					</form>
			</div>

			<div class="card-body">
				<?php if (sizeof($logs) >= 1) { ?>
					<table>
						<tr><th style="white-space: nowrap;"><?= __("Time",'couponapi') ?></th><th style="padding-left:20px;"><?= __("Message",'couponapi') ?></th></tr>
						<?php
						foreach ($logs as $log) {
							if ($log->message)
								echo '<tr style="font-size:0.85em;"><td >' . $log->logtime . '</td><td style="padding-left:20px;color:' . $log->color . ';">' . $log->message . '</td></tr>';
						}
						?>
					</table>
				<?php } else { ?>
					<i><?= __("No Data to display",'couponapi') ?></i>
				<?php } ?>
			</div>

		</div>
	</div>
<?php
}

function couponapi_display_troubleshoot() {

	//Bootstrap CSS
	wp_register_style('bootstrap.min', plugins_url('css/bootstrap.min.css', __FILE__));
	wp_enqueue_style('bootstrap.min');
	//Custom CSS
	wp_register_style('couponapi_css', plugins_url('css/couponapi_style.css', __FILE__));
	wp_enqueue_style('couponapi_css');
	//Bootstrap JS
	wp_register_script('bootstrap.min', plugins_url('js/bootstrap.min.js', __FILE__), array('jquery'));
	wp_enqueue_script('bootstrap.min');

	set_time_limit(0);

	$troubleshooting = couponapi_get_troubleshootings();

?>
	<div class="wrap" style="background:#F1F1F1;">
		<h2><?= __("Troubleshoot",'couponapi') ?></h2>
		<h6><?= __("Checks for common issues related to server & setup configurations.",'couponapi') ?></h6>
			
		<hr/>

		<div class="card p-0 mt-0 col-12">
			<div class="card-body p-0">
				<table class="table m-0 table-striped">
					<thead class="thead-dark">
						<tr>
							<th><?= __("Check",'couponapi') ?></th>
							<th><?= __("Status",'couponapi') ?></th>
							<th><?= __("Message",'couponapi') ?></th>
						</tr>
					</thead>
					<tbody>

						<?php foreach ($troubleshooting as $name => $value) { ?>
							<tr>
								<td><strong><?= $name ?></strong></td>
								<td><span class="capi_troubleshoot dashicons dashicons-<?= $value['status'] ?>"></span></td>
								<td style="font-size: small;"><?= $value['message'] ?></td>
							</tr>
						<?php } ?>

					</tbody>
				</table>
			</div>
		</div>

		<a class="btn btn-primary btn-sm px-2 ml-2 mt-3" style="background:#4e54c8;" href="<?= admin_url('admin.php?page=couponapi-troubleshoot') ?>"><?= __("Refresh",'couponapi') ?> <span class="dashicons dashicons-update"></span></a>

	</div>
<?php
}

function couponapi_display_brandlogos_settings() {

	$couponapi_config = couponapi_get_config();
	//Bootstrap CSS
	wp_register_style('bootstrap.min', plugins_url('css/bootstrap.min.css', __FILE__));
	wp_enqueue_style('bootstrap.min');
	//Custom CSS
	wp_register_style('couponapi_css', plugins_url('css/couponapi_style.css', __FILE__));
	wp_enqueue_style('couponapi_css');
	//Bootstrap JS
	wp_register_script('bootstrap.min', plugins_url('js/bootstrap.min.js', __FILE__), array('jquery'));
	wp_enqueue_script('bootstrap.min');

	set_time_limit(0);
	// Get Messages
	if (!empty($_COOKIE['message'])) {
		$message = stripslashes($_COOKIE['message']);
		echo '<script>document.cookie = "message=; expires=Thu, 01 Jan 1970 00:00:00 UTC;"</script>'; // php works only before html
	}
	global $wpdb;
	$sql = "SELECT
				(SELECT value FROM {$wpdb->prefix}couponapi_config WHERE name = 'API_KEY') API_KEY,
				(SELECT value FROM {$wpdb->prefix}couponapi_config WHERE name = 'generic_import_image') generic_import_image,
				(SELECT value FROM {$wpdb->prefix}couponapi_config WHERE name = 'set_as_featured_image') set_as_featured_image,
				(SELECT value FROM {$wpdb->prefix}couponapi_config WHERE name = 'use_logos') use_logos,
				(SELECT value FROM {$wpdb->prefix}couponapi_config WHERE name = 'use_grey_image') use_grey_image,
				(SELECT value FROM {$wpdb->prefix}couponapi_config WHERE name = 'size') size
			FROM dual";
	$config = $wpdb->get_row($sql);
	$config->brandlogos_key = '';
	$config->is_premium = 0;

	if (!empty($config->API_KEY)) {
		$result =  json_decode(file_get_contents('https://couponapi.org/api/getSettings/?API_KEY=' . $config->API_KEY), true);
		if ($result['result'] and $result['is_premium']) {
			$config->brandlogos_key = $result['brandlogos_key'];
			$config->is_premium = $result['is_premium'];
		}
	}

	$sql = "REPLACE INTO {$wpdb->prefix}couponapi_config (name,value) VALUES ('brandlogos_key','{$config->brandlogos_key}')";
	$wpdb->query($sql);

	$use_logos_options = array(
		'on'  => __('On','couponapi'),
		'off' => __('Off','couponapi'),
	);
	$theme = get_template();
	if($theme == 'clipmydeals' and $config->use_logos == 'if_empty')  $config->use_logos = 'off';
	if (couponapi_is_image_supported($theme) and $theme != 'couponhut' and $theme != 'clipmydeals') {
		$use_logos_options = array('if_empty' =>__('For Offers without Images','couponapi')) + $use_logos_options;
	}
?>
	<div>
		<h2 style="margin-top:15px;"><?= __('Auto-Import Store Logos','couponapi')?></h2>
		<?= (!empty($message) ? $message: '') ?>
		<hr/>
		<?php if(!$config->is_premium) { ?>
			<div class="notice notice-warning"><p><?= __("This feature is only available with <strong>CouponAPI Premium plan</strong>",'couponapi') ?>. <a target="_blank" href="https://couponapi.org/account/subscription_plans.php"><?= __("Click here",'couponapi') ?></a> <?= __("to upgrade.",'couponapi') ?> </p></div>
		<?php } elseif($theme == 'mts_coupon') { ?>
			<div class="notice notice-error"><p><?= __('"MyThemeShop Coupons" theme doesn\'t support Store Images.','couponapi') ?></p></div>
		<?php } else { ?>
			<div class="row">

				<div class="col-md-4">
					<div class="card px-0 mt-0 bg-dark text-white">
						<div class="card-header">
							<h5><?= sprintf(__("Get store logos from %s",'couponapi'),'<a style="color:#59d3ef;" target="_blank" href="https://brandlogos.org">BrandLogos.org</a>') ?></h5>
						</div>
						<div class="card-body">
							<form name="feedSetting" role="form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
								<div class="row">
									
									<div class="col-12">
										<div class="form-group">
											<label for="brandlogos_key"><?= __("BrandLogos.org API Key",'couponapi') ?></label>
											<input type="text" name="brandlogos_key" id="brandlogos_key" class="form-control" readonly value="<?= $config->brandlogos_key; ?>" />
										</div>
									</div>
									<?php 
										if($couponapi_config['is_theme_supported']){
									?>
									<div class="col-12">
										<div class="form-group">
											<label for="use_logos"><?= __("Use Logos from BrandLogos.org",'couponapi') ?></label>
											<select class="form-control" name="use_logos" id="use_logos" onchange ="toggleFields(this.value)">
												<?php foreach ($use_logos_options as $key => $option) {
													echo "<option value='$key' " . ($key == $config->use_logos ? 'selected' : '') . ">$option</option>";
												} ?>
											</select>
										</div>
									</div>
									<script>
										function toggleFields(val) {
											let elems = document.querySelectorAll('.cmd_hide_logos');
											elems.forEach((a)=>{
												if(val == 'off') {
													a.classList.add('d-none');
												} else {
													a.classList.remove('d-none');
												}
											});				
										}
									</script>
									<?php
										}
									?>
									
									<?php if ($config->use_logos != 'off' or !$couponapi_config['is_theme_supported']) { ?>
										<div class="col-12 cmd_hide_logos">
											<div class="form-group">
												<label for="size"><?= __("Logo Style",'couponapi') ?></label>
												<select class="form-control" name="size" id="size">
													<option value='horizontal' <?= $config->size == 'horizontal' ? 'selected' : '' ?>><?= __("Horizontal",'couponapi') ?></option>
													<option value='square' <?= $config->size == 'square' ? 'selected' : '' ?>><?= __("Square",'couponapi') ?></option>
												</select>
											</div>
										</div>
									<?php }
										if($couponapi_config['is_theme_supported'] and $config->use_logos != 'off'){
									?>
									
										<div class="col-12 cmd_hide_logos">
											<div class="form-group">
												<label for="use_grey_image"><?= __("For Stores without logos",'couponapi') ?></label>
												<select class="form-control" name="use_grey_image" id="use_grey_image">
													<option value='on' <?= $config->use_grey_image == 'on' ? 'selected' : '' ?>><?= __("Use Grey Images as default placeholder",'couponapi') ?></option>
													<option value='off' <?= $config->use_grey_image == 'off' ? 'selected' : '' ?>><?= __("Do not use Grey Images as default placeholder",'couponapi') ?></option>
												</select>
											</div>
										</div>
									<?php } ?>
									<div class="col-12">
										<div class="form-group">
											<?php wp_nonce_field('couponapi', 'brandlogos_config_nonce'); ?>
											<input type="hidden" name="action" value="capi_save_brandlogos_config" />
											<button class="btn btn-primary btn-block" style="background:#4e54c8;" type="submit" name="submit_feed_config"><?= __("Save",'couponapi') ?> <span class="dashicons dashicons-arrow-right" style="margin-top:2px;"></span></button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>

				<?php 
				if (($couponapi_config['is_theme_supported'] and $config->use_logos != 'off') or (!$couponapi_config['is_theme_supported'] and $config->generic_import_image == 'brandlogos_image')) { ?>
					<div class="col-md-4">
						<div class="card px-0 mt-0 bg-dark text-white">
							<div class="card-header">
								<h5><?= __("Resync store logos from <a style=\"color:#59d3ef;\" target=\"_blank\" href=\"https://brandlogos.org\">BrandLogos.org</a></h5>",'couponapi') ?> 
							</div>
							<div class="card-body">
								<form name="resyncBrandlogos" role="form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
									<div class="form-group">
										<div class="custom-control custom-switch">
											<input type="checkbox" name="empty_logos" class="custom-control-input" id="empty_logos" />
											<label class="custom-control-label" for="empty_logos" style="display:block;"><?= __("Stores without Logo",'couponapi') ?></label>
										</div>
									</div>
									<?php if($couponapi_config['is_theme_supported']){?>
									<div class="form-group">
										<div class="custom-control custom-switch">
											<input type="checkbox" name="grey_logos" class="custom-control-input" id="grey_logos" />
											<label class="custom-control-label" for="grey_logos" style="display:block;"><?= __("Stores with Grey Logo",'couponapi') ?></label>
										</div>
									</div>
									<div class="form-group">
										<div class="custom-control custom-switch">
											<input type="checkbox" name="custom_logos" class="custom-control-input" id="custom_logos" />
											<label class="custom-control-label" for="custom_logos" style="display:block;"><?= __("Stores with Custom Logo",'couponapi') ?></label>
										</div>
									</div>
									<?php }?>
									<style>
										h1:before,
										h1:after {
											content: "";
											flex: 1 1;
											border-bottom: 1px solid;
											margin: auto;
										}

										h1:before {
											margin-right: 10px
										}

										h1:after {
											margin-left: 10px
										}
									</style>
									<h1 class="d-flex flex-row text-light mx-2"><span style="font-size:xx-large; font-weight:400"><?= __("OR",'couponapi') ?></span></h1>

									<div class="form-group">
										<label for="store_slugs"><?= __("Stores with Slugs",'couponapi') ?></label>
										<input type="text" name="store_slugs" id="store_slugs" class="form-control" autocomplete="off" value="" />
										<div class="form-text"><small style="color:#a7a7a7;"><?= __("Please enter comma seperated list of slug of stores for which you want to resync.",'couponapi') ?><br><?= __("Example","couponapi")?>: <code>amazon-com,aliexpress-vn</code></small></div>
									</div>
									<div class="form-group">
										<?php wp_nonce_field('couponapi', 'brandlogos_resync_nonce'); ?>
										<input type="hidden" name="action" value="capi_brandlogos_resync" />
										<button class="btn btn-warning btn-block" type="submit" name="submit_brandlogos_resync"><?= __("Resync ",'couponapi') ?><span class="dashicons dashicons-update mt-1"></button>
									</div>
								</form>
							</div>
						</div>
					</div>
				<?php } ?>

			</div>
		<?php } ?>
	</div>
<?php }

function couponapi_custom_template(){
	//Bootstrap CSS
	wp_register_style('bootstrap.min', plugins_url('css/bootstrap.min.css', __FILE__));
	wp_enqueue_style('bootstrap.min');
	//Custom CSS
	wp_register_style('couponapi_css', plugins_url('css/couponapi_style.css', __FILE__));
	wp_enqueue_style('couponapi_css');
	//Bootstrap JS
	wp_register_script('bootstrap.min', plugins_url('js/bootstrap.min.js', __FILE__), array('jquery'));
	wp_enqueue_script('bootstrap.min');

	$default_template = '<p class="has-medium-font-size">{{label}}</p>

		<hr/>
		
		<table style="border: none;border-collapse: collapse;">
		<tr>
		
			<td style="width: 64%;border: none;">
				<strong>Store</strong>: {{store}}<br>
				<strong>Coupon Code</strong>: {{code}}<br>
				<strong>Expiry</strong>: {{expiry}}
			</td>
			<td style="width: 36%;border: none;"> 
				
					<figure>{{image}}</figure><br>
					<div class="wp-block-buttons">
					<div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-fill" style="text-align: center;"><a class="wp-block-button__link wp-element-button" href="{{link}}">Visit Website</a></div>
					</div>
				
			</td>
		
		</tr>
		
		</table>
		{{description}}
		';
?>
<div class="wrap" style="background:#F1F1F1;">
	<h2><?= __('Custom HTML Template', 'couponapi') ?></h2>
	
	<hr>
	<div class="row">
		<div class="card p-0 mt-0 col-10">
		<div class="card-header card-header d-flex bg-dark text-white"><?= __("Custom HTML Template For Coupons", 'couponapi') ?></div>
			<form role="form" method="post" action="<?php echo admin_url('admin-post.php'); ?>">
				<div class="card-body row">
					<div class="col-10">
						<?php
						$settings = array( 'textarea_name' => 'custom_coupon_template' ); 
						wp_editor( stripslashes(get_theme_mod('custom_coupon_template', $default_template)), 'custom_coupon_template' , $settings );
						?>
					</div>
					<div class="col-2">
					<div class="couponapi_variables btn btn-sm text-light mt-1" style="background:#4e54c8;">{{description}}</div><br>
					<div class="couponapi_variables btn btn-sm text-light mt-1" style="background:#4e54c8;">{{link}}</div><br>
					<div class="couponapi_variables btn btn-sm text-light mt-1" style="background:#4e54c8;">{{label}}</div><br>
					<div class="couponapi_variables btn btn-sm text-light mt-1" style="background:#4e54c8;">{{store}}</div><br>
					<div class="couponapi_variables btn btn-sm text-light mt-1" style="background:#4e54c8;">{{code}}</div><br>
					<div class="couponapi_variables btn btn-sm text-light mt-1" style="background:#4e54c8;">{{start_date}}</div><br>
					<div class="couponapi_variables btn btn-sm text-light mt-1" style="background:#4e54c8;">{{expiry}}</div><br>
					<div class="couponapi_variables btn btn-sm text-light mt-1" style="background:#4e54c8;">{{image}}</div><br>
					<div class="couponapi_variables btn btn-sm text-light mt-1 align-text-bottom" style="background:#4e54c8;">{{image_url}}</div><br>

					<div class="btn btn-sm text-light mt-1 align-text-bottom btn-secondary position-absolute fixed-bottom w-75" onclick='reset_template()'><?= __('Reset Template','couponapi') ?></div><br>

					</div>

					<div class="col-10 d-flex justify-content-center  pt-2">
							<?php wp_nonce_field('couponapi', 'custom_template_nonce'); ?>
							<input type="hidden" name="action" value="capi_custom_template" />
							<button class="btn btn-primary btn-block" style="background:#4e54c8; width:fit-content;" type="submit" name="submit_feed_config"><?= __('Save', 'couponapi') ?> <span class="dashicons dashicons-arrow-right" style="margin-top:2px;"></span></button>
					</div>
				</div>
			</form>
			
			<div class="card-footer bg-dark text-light">
				<h4> <?= __('Instructions','couponapi')?> </h4>
				<ol>
					<li><?= __("This HTML Template will be used by plugin to replace the coupon's design while importing",'couponapi')?></li>
					<li><?= __('Use the following placeholders to make your coupons more informative: {{description}}, {{link}}, {{label}}, {{store}}, {{code}}, {{start_date}}, {{expiry}}, {{image}}, and {{image_url}}. These placeholders allow you to include details such as the coupon\'s description, affiliate link, label, store name, coupon code (if applicable), start date, end date, image, and image link from BrandLogos.org, respectively.You may effortlessly manage the coupon information as per your requirements by arranging these variables accordingly.','couponapi')?></li>
					<li><?= __('You can add HTML and style around the variables','couponapi')?></li>
				</ol>
			</div>
		</div>
	</div>

</div>

<script>

	function reset_template(){
		default_template = `<?= $default_template ?>`
		document.getElementById('custom_coupon_template').value = default_template 
		tinyMCE.get('custom_coupon_template').setContent(default_template);

	}
	
	document.querySelectorAll(".couponapi_variables").forEach(ele => 
	  ele.addEventListener("click", () => {
		var editor = tinymce.get('custom_coupon_template');
		variable = ele.innerHTML
		if (editor) {
		  editor.execCommand('mceInsertContent', false, variable);
		} 
			inputField = document.getElementById('custom_coupon_template')
			const start = inputField.selectionStart;
			const end = inputField.selectionEnd;
			const currentValue = inputField.value;
			const newValue = currentValue.slice(0, start) + variable + currentValue.slice(end);
			inputField.value = newValue;
			const newCursorPosition = start + variable.length;
            inputField.setSelectionRange(newCursorPosition, newCursorPosition);
			inputField.focus()
		}

	)
	)
	
</script>
<?php
} 
?>