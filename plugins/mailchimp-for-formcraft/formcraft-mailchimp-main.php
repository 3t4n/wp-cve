<?php

	/*
	Plugin Name: FormCraft MailChimp Add-On
	Plugin URI: http://formcraft-wp.com/addons/mailchimp/
	Description: MailChimp Add-On for FormCraft
	Author: nCrafts
	Author URI: http://formcraft-wp.com/
	Version: 1.8
	Text Domain: formcraft-mailchimp
	*/

	use \DrewM\MailChimp\MailChimp;
	global $fc_meta, $fc_forms_table, $fc_submissions_table, $fc_views_table, $fc_files_table, $wpdb;

	add_action('formcraft_after_save', 'formcraft_mailchimp_trigger', 10, 4);
	function formcraft_mailchimp_trigger($content, $meta, $raw_content, $integrations) {
		global $fc_final_response;
		if ( in_array('MailChimp', $integrations['not_triggered']) ) {
			return false;
		}
		$mailchimp_data = formcraft_get_addon_data('MailChimp', $content['Form ID']);
		$double = isset($mailchimp_data['double_opt_in']) && $mailchimp_data['double_opt_in']==true ? 'pending' : 'subscribed';
		$update_existing = isset($mailchimp_data['update_existing']) && $mailchimp_data['update_existing']==true ? 1 : 0;

		if (!$mailchimp_data) {
			return false;
		}
		if (!isset($mailchimp_data['validKey']) || empty($mailchimp_data['validKey']) ) {
			return false;
		}
		if (!isset($mailchimp_data['Map'])) {
			return false;
		}

		if ( !class_exists('DrewM\MailChimp\MailChimp') ) {
			require_once('MailChimpV3.php');
		}
		$mailchimp = new MailChimp($mailchimp_data['validKey']);		

		$update_existing = false;
		if (isset($mailchimp_data['update_existing']) && $mailchimp_data['update_existing']==true) {
			$update_existing = true;
		}

		$submit_data = array();
		foreach ($mailchimp_data['Map'] as $key => $line) {
			$id = $line['listID'];
			$submit_data[$line['listID']]['status'] = $double;
			$submit_data[$line['listID']]['update_existing'] = $update_existing;
			$submit_data[$line['listID']]['interests'] = array();
			if ( substr($line['columnID'], 0, 2) == 'G:' ) {
				$data = formcraft3_template($content, $line['formField']);
				$data = explode(PHP_EOL, $data);
				$interests = $mailchimp->get("lists/$id/interest-categories/".substr($line['columnID'], 2)."/interests");
				foreach ($data as $key2 => $value2) {
					$value2 = strip_tags($value2);
					foreach ($interests['interests'] as $key3 => $value3) {
						if ( $value3['name'] == $value2 ) {
							$submit_data[$line['listID']]['interests'][$value3['id']] = true;
						}
					}
				}
			} else if ($line['columnID']=='EMAIL') {
				$email = formcraft3_template($content, $line['formField']);
				if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
					continue;
				}
				$submit_data[$line['listID']]['email_address'] = $email;
			}
			else
			{
				$submit_data[$line['listID']]['merge_fields'][$line['columnID']] = formcraft3_template($content, $line['formField']);
				$submit_data[$line['listID']]['merge_fields'][$line['columnID']] = trim(preg_replace('/\s*\[[^)]*\]/', '', $submit_data[$line['listID']]['merge_fields'][$line['columnID']]));
				$submit_data[$line['listID']]['merge_fields'][$line['columnID']] = html_entity_decode($submit_data[$line['listID']]['merge_fields'][$line['columnID']], ENT_QUOTES, 'utf-8');
			}
		}

		foreach ($submit_data as $list_id => $list_submit) {

			if ( count($list_submit['interests']) == 0 ) {
				unset($list_submit['interests']);
			}

			$mailchimp->verify_ssl = false;

			if (!isset($list_submit['email_address'])) {
				$fc_final_response['debug']['failed'][] = __('MailChimp: No Email Specified','formcraft-mailchimp');
				continue;
			}
			$mailchimp = new MailChimp($mailchimp_data['validKey']);
			$mailchimp->verify_ssl = false;

			$get = $mailchimp->get("lists/$list_id/members/".md5($list_submit['email_address']));

			if ($get['status'] != '404' && $list_submit['update_existing'] == true) {
				$result = $mailchimp->put("lists/$list_id/members/".md5($list_submit['email_address']), $list_submit);
			} else {
				$result = $mailchimp->post("lists/$list_id/members/", $list_submit);
			}
			if (!$mailchimp->success()) {
				$fc_final_response['debug']['failed'][] = $mailchimp->getLastError();
			} else {
				$fc_final_response['debug']['success'][] = 'MailChimp Added: '.$list_submit['email_address'];
			}
		}
	}

	add_action('formcraft_addon_init', 'formcraft_mailchimp_addon');
	add_action('formcraft_addon_scripts', 'formcraft_mailchimp_scripts');

	function formcraft_mailchimp_addon()
	{
		register_formcraft_addon('MC_printContent',142,'MailChimp','MailChimpController',plugins_url('assets/logo.png', __FILE__ ),plugin_dir_path( __FILE__ ).'templates/',1);
	}
	function formcraft_mailchimp_scripts()
	{
		wp_enqueue_script('fcm-main-js', plugins_url( 'assets/builder.js', __FILE__ ));
		wp_enqueue_style('fcm-main-css', plugins_url( 'assets/builder.css', __FILE__ ));
	}

	add_action( 'wp_ajax_formcraft_mailchimp_test_api', 'formcraft_mailchimp_test_api' );
	function formcraft_mailchimp_test_api()
	{
		$key = $_GET['key'];
		if ( !class_exists('DrewM\MailChimp\MailChimp') ) {
			require_once('MailChimpV3.php');
		}
		$mailchimp = new MailChimp($key);
		$mailchimp->verify_ssl = false;
		$lists = $mailchimp->get('lists');
		if (isset($lists['total_items'])) {
			echo json_encode(array('success'=>'true'));
			die();
		} else {
			echo json_encode(array('failed'=>'true'));
			die();
		}
	}
	add_action( 'wp_ajax_formcraft_mailchimp_get_lists', 'formcraft_mailchimp_get_lists' );
	function formcraft_mailchimp_get_lists()
	{
		$key = $_GET['key'];
		if ( !class_exists('DrewM\MailChimp\MailChimp') ) {
			require_once('MailChimpV3.php');
		}
		$mailchimp = new MailChimp($key);
		$mailchimp->verify_ssl = false;
		$lists = $mailchimp->get('lists', array('count' => 100));
		$lists = $lists['lists'];
		$listsRefined = array();
		foreach ($lists as $key => $value) {
			$listsRefined[$key]['id'] = $value['id'];
			$listsRefined[$key]['name'] = $value['name'];
		}
		if ($listsRefined) {
			echo json_encode(array('success'=>'true','lists'=>$listsRefined));
			die();
		} else {
			echo json_encode(array('failed'=>'true'));
			die();
		}
	}
	add_action( 'wp_ajax_formcraft_mailchimp_get_columns', 'formcraft_mailchimp_get_columns' );
	function formcraft_mailchimp_get_columns()
	{
		$key = $_GET['key'];
		$id = $_GET['id'];
		if ( !class_exists('DrewM\MailChimp\MailChimp') ) {
			require_once('MailChimpV3.php');
		}
		$mailchimp = new MailChimp($key);
		$mailchimp->verify_ssl = false;
		$columns = $mailchimp->get("lists/$id/merge-fields", array('count' => 100));

		$columns = $columns['merge_fields'];
		$columnsRefined = array();

		$columnsRefined[] = array('tag'=>'EMAIL', 'name'=>'Email Address');
		foreach ($columns as $key => $value) {
			$columnsRefined[] = array('tag'=>$value['tag'], 'name'=>$value['name']);
		}

		$groups = $mailchimp->get("lists/$id/interest-categories");
		foreach ($groups['categories'] as $key => $value) {
			$columnsRefined[] = array('tag'=>'G:'.$value['id'], 'name'=>'Group: '.$value['title']);
		}

		if ($columnsRefined) {
			echo json_encode(array('success'=>'true','columns'=>$columnsRefined));
			die();
		} else {
			echo json_encode(array('failed'=>'true'));
			die();
		}
	}

	function MC_printContent() {
		?>
		<div id='mc-cover' id='mc-valid-{{Addons.MailChimp.showOptions}}'>
			<div class='mc-padding'>
				<div class='help-link'>
					<a class='trigger-help' data-post-id='19'><?php _e('how does this work?','formcraft-mailchimp'); ?></a>
				</div>
				<div class='api-key hide-{{Addons.MailChimp.showOptions}}'>	
					<div class='w-2'>
						<input placeholder='<?php _e('Enter API Key','formcraft-mailchimp') ?>' type='text' ng-model='Addons.MailChimp.api_key'>
					</div>
					<div class='w-1'>
						<button ng-click='testKey()' class='formcraft-button medium'>
							<?php _e('Check','formcraft-mailchimp') ?>
							<div class='formcraft-loader'></div>
						</button>
					</div>
				</div>
				<div ng-show='Addons.MailChimp.showOptions'>
					<div id='mc-map-output' class='nos-{{Addons.MailChimp.Map.length}}'>
						<div class='nothing-here w-3'>
							<?php _e('(Add a Field Mapping Below)','formcraft-mailchimp') ?>
						</div>
						<div class='something-here'>
							<div ng-repeat='instance in Addons.MailChimp.Map'>
								<div class='w-25'>
									<span class='is-text arrow-right'>{{instance.listName}}</span>
								</div>
								<div class='w-25'>
									<span class='is-text arrow-right'>{{instance.columnName}}</span>
								</div>
								<div class='w-25'>
									<input type='text' ng-model='instance.formField'/>
								</div>
								<div class='w-25'>
									<button ng-click='removeMap($index)' class='formcraft-button medium red'>
										delete
									</button>
								</div>
							</div>
						</div>
					</div>
					<div id='mc-map-input'>
						<div class='w-25'>
							<select class='select-list' ng-model='SelectedList'><option value='' selected="selected"><?php _e('List','formcraft-mailchimp') ?></option><option ng-repeat='list in MCLists' value='{{list.id}}'>{{list.name}}</option></select>
						</div>
						<div class='w-25'>
							<select class='select-column' ng-model='SelectedColumn'><option value='' selected="selected"><?php _e('Column','formcraft-mailchimp') ?></option><option ng-repeat='col in MCColumns' value='{{col.tag}}'>{{col.name}}</option></select>
						</div>
						<div class='w-25'>
							<input class='select-field' type='text' ng-model='FieldName' placeholder='<?php _e('Form Field','formcraft-mailchimp') ?>'>
						</div>
						<div class='w-25'>
							<button class='formcraft-button medium' ng-click='addMap()'>Add</button>
						</div>
					</div>
				</div>
			</div>
			<div>
				<label class='single-option has-checkbox' style='border-top-width: 1px'>
					<input type='checkbox' ng-model='Addons.MailChimp.double_opt_in'>
					<h3><?php _e('Double Opt-In','formcraft-mailchimp'); ?></h3>
				</label>
				<label class='single-option has-checkbox' style='border-bottom-width: 0px'>
					<input type='checkbox' ng-model='Addons.MailChimp.update_existing'>
					<h3><?php _e('Update Existing','formcraft-mailchimp'); ?></h3>
				</label>
			</div>
		</div>
		<?php
	}


	?>