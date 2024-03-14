<?php
namespace webaware\gf_dpspxpay;

use Exception;
use GFAPI;
use WP_Query;

if (!defined('ABSPATH')) {
	exit;
}

/**
* upgrade from v1.x add-on
*/
class GFDpsPxPayUpdateV1 {

	private $slug;

	private $update_feeds;
	private $update_transactions;

	const POST_TYPE_FEED			= 'gfdpspxpay_feed';
	const OLD_SETTINGS_NAME			= 'gfdpspxpay_plugin';
	const META_CONVERTED			= '_gfdpspxpay_converted';
	const META_VALUE_OLD_GATEWAY	= 'gfdpspxpay';

	/**
	* @param string $slug
	*/
	public function __construct($slug) {
		$this->slug					= $slug;
		$old_settings				= get_option(self::OLD_SETTINGS_NAME);

		// do settings need updating?
		if (empty($old_settings['upgraded_settings'])) {
			$this->updateSettings();
		}

		// only run feed update process if in the admin
		if (!is_admin()) {
			return;
		}

		// only bother admins / plugin installers / option setters with this stuff
		if (!current_user_can('activate_plugins') && !current_user_can('manage_options')) {
			return;
		}

		// do feeds need updating?
		if (empty($old_settings['upgraded_feeds'])) {
			$this->setupUpdateFeeds();
		}

		// do transactions need updating?
		if (empty($old_settings['upgraded_txns'])) {
			$this->setupUpdateTransactions();
		}
	}

	/**
	* upgrade settings from the old add-on
	* @param array $old_settings
	*/
	protected function updateSettings() {
		$old_settings = get_option(self::OLD_SETTINGS_NAME);

		$settings = [
			'userID'	=> rgar($old_settings, 'userID',  ''),
			'userKey'	=> rgar($old_settings, 'userKey', ''),
			'testEnv'	=> rgar($old_settings, 'testEnv', 'UAT'),
			'testID'	=> rgar($old_settings, 'testID',  ''),
			'testKey'	=> rgar($old_settings, 'testKey', ''),
		];

		// if add-on was installed before the test environment setting became available, set default environment to SEC for backwards compatibility
		if (isset($old_settings['userID']) && !isset($old_settings['testEnv'])) {
			$settings['testEnv'] = 'SEC';
		}

		update_option("gravityformsaddon_{$this->slug}_settings", $settings);

		$old_settings['upgraded_settings'] = 1;
		update_option(self::OLD_SETTINGS_NAME, $old_settings);
	}

	/**
	* set up the process for updating feeds
	*/
	protected function setupUpdateFeeds() {
		// check for feed posts that haven't been converted
		$feeds = $this->getFeedsUnconverted();

		if (empty($feeds)) {
			// nothing found, so mark it off and don't check again
			$old_settings = get_option(self::OLD_SETTINGS_NAME);
			$old_settings['upgraded_feeds'] = 1;
			update_option(self::OLD_SETTINGS_NAME, $old_settings);
		}
		else {
			add_action('admin_notices', [$this, 'showUpdate']);
			$this->update_feeds = count($feeds);
		}
	}

	/**
	* set up the process for updating transactions
	*/
	protected function setupUpdateTransactions() {
		// check for transactions that haven't been converted
		$txns = $this->getTxnUnconverted();

		if (empty($txns)) {
			// nothing found, so mark it off and don't check again
			$old_settings = get_option(self::OLD_SETTINGS_NAME);
			$old_settings['upgraded_txns'] = 1;
			update_option(self::OLD_SETTINGS_NAME, $old_settings);
		}
		else {
			add_action('admin_notices', [$this, 'showUpdate']);
			$this->update_transactions = count($txns);
		}
	}

	/**
	* get a list of ids of old feeds that haven't been converted yet
	* @return array
	*/
	public static function getFeedsUnconverted() {
		$args = [
			'post_type'			=> self::POST_TYPE_FEED,
			'posts_per_page'	=> -1,
			'fields'			=> 'ids',
			'meta_query'		=> [
				[
					'key'			=> self::META_CONVERTED,
					'compare'		=> 'NOT EXISTS',
				],
			],
		];

		$query = new WP_Query($args);

		return $query->posts;
	}

	/**
	* get a list of ids of old transactions that haven't been converted yet
	* @return array
	*/
	public static function getTxnUnconverted() {
		global $wpdb;

		$sql = "
			select e.id
			from {$wpdb->prefix}rg_lead e
			inner join {$wpdb->prefix}rg_lead_meta em on em.lead_id = e.id and em.meta_key = 'payment_gateway'
			where em.meta_value = %s
		";

		return $wpdb->get_col($wpdb->prepare($sql, self::META_VALUE_OLD_GATEWAY));
	}

	/**
	* show feed update prompt
	*/
	public function showUpdate() {
		$min = SCRIPT_DEBUG ? '' : '.min';
		$ver = SCRIPT_DEBUG ? time() : GFDPSPXPAY_PLUGIN_VERSION;

		wp_enqueue_style('gfdpspxpay_admin');
		wp_enqueue_script('gfdpspxpay_updatev1', plugins_url("static/js/admin-update-v1$min.js", GFDPSPXPAY_PLUGIN_FILE), ['jquery'], $ver, true);

		$steps = [];
		if ($this->update_feeds)			$steps[] = 'feeds';
		if ($this->update_transactions)		$steps[] = 'transactions';
		$steps[] = 'end';
		wp_localize_script('gfdpspxpay_updatev1', 'gfdpspxpay_updatev1', $steps);

		include GFDPSPXPAY_PLUGIN_ROOT . 'views/admin-update-v1.php';
	}

	/**
	* process the upgrad AJAX request
	*/
	public static function ajaxUpgrade() {
		try {

			switch (rgget('step')) {

				case 'feeds_list':
					$ids = self::getFeedsUnconverted();
					$response = ['step' => 'feeds', 'ids' => $ids];
					break;

				case 'feeds':
					$id = rgget('id');
					self::upgradeFeed($id);
					$response = ['step' => 'feeds', 'next' => absint(rgget('next'))];
					break;

				case 'transactions_list':
					$ids = self::getTxnUnconverted();
					$response = ['step' => 'transactions', 'ids' => $ids];
					break;

				case 'transactions':
					$ids = self::getTxnUnconverted();
					self::upgradeTransactions();
					$response = ['step' => 'transactions', 'next' => count($ids) + 1];
					break;

				default:
					throw new Exception(__('Unknown upgrade step passed.', 'gravity-forms-dps-pxpay'));

			}

			wp_send_json_success($response);
		}
		catch (Exception $e) {
			wp_send_json_error(['error' => $e->getMessage()]);
		}

		exit;
	}

	/**
	* upgrade a feed for a form
	* @param int $id id of old feed post
	* @throws Exception
	*/
	protected static function upgradeFeed($id) {
		$addon			= AddOn::get_instance();
		$old_settings	= get_option(self::OLD_SETTINGS_NAME);
		$post			= get_post($id);
		$form_id		= get_post_meta($id, '_gfdpspxpay_form', true);

		if (empty($post) || empty($form_id)) {
			$addon->log_error("old feed $id not found or not linked to form");
			throw new Exception(__('Error upgrading feed from version 1 of add-on', 'gravity-forms-dps-pxpay'));
		}

		$meta = [
			'feedName'									=> $post->post_title,
			'useTest'									=> empty($old_settings['useTest']) ? '0' : '1',
			'transactionType'							=> 'product',
			'billingInformation_description'			=> self::upgradeFeedFieldReference(get_post_meta($id, '_gfdpspxpay_merchant_ref', true)),
			'billingInformation_txn_data1'				=> self::upgradeFeedFieldReference(get_post_meta($id, '_gfdpspxpay_txndata1', true)),
			'billingInformation_txn_data2'				=> self::upgradeFeedFieldReference(get_post_meta($id, '_gfdpspxpay_txndata2', true)),
			'billingInformation_txn_data3'				=> self::upgradeFeedFieldReference(get_post_meta($id, '_gfdpspxpay_txndata3', true)),
			'billingInformation_email'					=> self::upgradeFeedFieldReference(get_post_meta($id, '_gfdpspxpay_email', true)),
			'cancelURL'									=> get_post_meta($id, '_gfdpspxpay_url_fail', true),
			'delayPost'									=> get_post_meta($id, '_gfdpspxpay_delay_post', true),
			'delay_gravityformsmailchimp'				=> '',
			'delay_gravity-forms-salesforce'			=> '',
			'delay_gravityformsuserregistration'		=> get_post_meta($id, '_gfdpspxpay_delay_userrego', true),
			'delay_gravityformszapier'					=> '',
			'execDelayed'								=> self::upgradeFeedExecDelayed($id),
			'feed_condition_conditional_logic'			=> '',
			'feed_condition_conditional_logic_object'	=> [],
		];

		$feed_id = GFAPI::add_feed($form_id, $meta, $addon->get_slug());

		if (empty($feed_id) || is_wp_error($feed_id)) {
			if (is_wp_error($feed_id)) {
				$addon->log_error("old feed $id cannot be upgraded to add-on feed: " . $feed_id->get_error_message());
			}
			else {
				$addon->log_error("old feed $id cannot be upgraded to add-on feed; null feed id.");
			}
			throw new Exception(__('Error upgrading feed from version 1 of add-on', 'gravity-forms-dps-pxpay'));
		}

		self::upgradeFeedNotifications($id, $form_id);

		$addon->log_debug("old feed $id on form $form_id upgraded to add-on feed $feed_id `{$meta['feedName']}'.");
		add_post_meta($id, self::META_CONVERTED, '1');
	}

	/**
	* upgrade field reference special values to add-on framework values
	* @param string $field
	* @return string
	*/
	protected static function upgradeFeedFieldReference($field) {
		switch ($field) {

			case 'form':
			case 'title':
				$field = 'form_title';
				break;

		}

		return $field;
	}

	/**
	* upgrade exec delayed options
	* @param int $id
	* @return string
	*/
	protected static function upgradeFeedExecDelayed($id) {
		$execAlways		= get_post_meta($id, '_gfdpspxpay_delay_exec_always', true);
		$ignoreNofeed	= get_post_meta($id, '_gfdpspxpay_delay_ignore_nofeed', true);

		if ($execAlways) {
			$execDelayed = 'always';
		}
		elseif ($ignoreNofeed) {
			$execDelayed = 'success';
		}
		else {
			$execDelayed = 'success_only';
		}

		return $execDelayed;
	}

	/**
	* upgrade notifications to use notification events
	* @param int $id
	* @param int $form_id
	*/
	protected static function upgradeFeedNotifications($id, $form_id) {
		$modified			= false;
		$delayNotify		= get_post_meta($id, '_gfdpspxpay_delay_notify', true);
		$delayAutorespond	= get_post_meta($id, '_gfdpspxpay_delay_autorespond', true);

		if ($delayNotify || $delayAutorespond) {
			$form = GFAPI::get_form($form_id);

			foreach ($form['notifications'] as $key => $notification) {
				if (trim($notification['to']) === '{admin_email}') {
					if ($delayNotify) {
						$form['notifications'][$key]['event'] = 'complete_payment';
						$modified = true;
					}
				}
				else {
					if ($delayAutorespond) {
						$form['notifications'][$key]['event'] = 'complete_payment';
						$modified = true;
					}
				}
			}
		}

		if ($modified) {
			GFAPI::update_form($form);
		}
	}

	/**
	* upgrade transactions to have the new slug, and a transaction record
	*/
	protected static function upgradeTransactions() {
		global $wpdb;

		$addon = AddOn::get_instance();

		$sql = "
			insert into {$wpdb->prefix}gf_addon_payment_transaction (lead_id, transaction_type, transaction_id, is_recurring, amount, date_created)
				select e.id, 'payment', e.transaction_id, 0, e.payment_amount, e.payment_date
				from {$wpdb->prefix}rg_lead e
				inner join {$wpdb->prefix}rg_lead_meta em on em.lead_id = e.id and em.meta_key = 'payment_gateway'
				where em.meta_value = %s
		";
		$success = $wpdb->query($wpdb->prepare($sql, self::META_VALUE_OLD_GATEWAY));

		if ($success) {
			$sql = "
				update {$wpdb->prefix}rg_lead e
				inner join {$wpdb->prefix}rg_lead_meta em on em.lead_id = e.id and em.meta_key = 'payment_gateway'
				set em.meta_value = %s
				where em.meta_value = %s
			";
			$success = $wpdb->query($wpdb->prepare($sql, $addon->get_slug(), self::META_VALUE_OLD_GATEWAY));
		}

		if (!$success) {
			$addon->log_error('Error upgrading transactions from version 1 of add-on: ' . $wpdb->last_error);
			throw new Exception(__('Error upgrading transactions from version 1 of add-on', 'gravity-forms-dps-pxpay'));
		}

		// update old payment status from Approved to Paid
		$sql = "
			update {$wpdb->prefix}rg_lead e
			inner join {$wpdb->prefix}rg_lead_meta em on em.lead_id = e.id and em.meta_key = 'payment_gateway'
			set e.payment_status = 'Paid'
			where e.payment_status = 'Approved'
			and em.meta_value = %s
		";
		$wpdb->query($wpdb->prepare($sql, $addon->get_slug()));

		$addon->log_debug('Old v1 transactions upgraded to add-on transactions.');
	}

}
