<?php
namespace mtreherne\WC_AC_Hook;
/**
 * This class is used to add/update (sync) contact details on ActiveCampaign
 * It uses the official PHP wrapper for the ActiveCampaign API to make it easy
 *
 */
if (!defined('ABSPATH')) exit();

if (!class_exists(__NAMESPACE__ . '\WC_AC_Hook_Sync')) :

class WC_AC_Hook_Sync {

	private $api_valid = false;
	private $activecampaign_connection;
	private $list_id;
	private $form_id;
	public $log_message = array();
	
	public function __construct($options) {
		if (!is_numeric ($options['ac_list_id'] ?? null)) {
			$this->log_message[] = sprintf( __( 'Error: List ID is not numeric = %s', 'wc-ac-hook' ), $options['ac_list_id'] ?? 'undefined');
		}
		else {
			$this->form_id = $options['wc_ac_marketing_form_id'] ?? null;
			$this->list_id = $options['ac_list_id'];
			if (!class_exists(__NAMESPACE__ . '\ActiveCampaign')) require_once dirname(__FILE__).'/../activecampaign-api-php/includes/ActiveCampaign.class.php';
			// Validate the ActiveCampaign credentials
			$this->activecampaign_connection = new ActiveCampaign($options['ac_url'] ?? null, $options['ac_api_key'] ?? null);
			if (!(int)$this->activecampaign_connection->credentials_test()) {
				$this->log_message[] = sprintf( __( 'Error: Invalid Active Campaign credentials, URL = %1$s, API Key = %2$s', 'wc-ac-hook' ), $options['ac_url'] ?? 'undefined', $options['ac_api_key'] ?? 'undefined');
			}
			else $this->api_valid = true;
		}
	}
	
	public function sync_contact($contact) {
		if ($this->api_valid) {
			$list_id = $this->list_id;
			// Check (and preserve) the status of a contact if already on the list
			$contact_view = $this->activecampaign_connection->api('contact/view?email='.$contact['email']);
			if ((int)$contact_view->success && isset($contact_view->lists->$list_id))
				$list_status = $contact_view->lists->$list_id->status;
			$contact["p[{$list_id}]"] = $list_id;
			$contact["status[{$list_id}]"] = isset($list_status) ? $list_status : 1;
			$contact_sync = $this->activecampaign_connection->api('contact/sync', $contact);	
			if ((int)$contact_sync->success) {
				$contact_id = (int)$contact_sync->subscriber_id;
				$this->log_message[] = sprintf( __( 'Contact synced successfully (ActiveCampaign ID = %s). Tags added: %s', 'wc-ac-hook' ), $contact_id, $contact['tags']);
			}
			else $this->log_message[] = sprintf( __( 'Syncing contact failed. Error returned: %s)', 'wc-ac-hook' ), $contact_sync->error);
		}
	}
	
	public function form_subscribe($contact) {
		if (!is_numeric ($this->form_id)) {
			$this->log_message[] = sprintf( __( 'Error: Form ID is not numeric = %s', 'wc-ac-hook' ), $this->form_id ?? 'undefined');
		}
		elseif ($this->api_valid) {
			$contact['form'] = $this->form_id;
			$contact_sync = $this->activecampaign_connection->api('contact/sync', $contact);
			if ((int)$contact_sync->success) {
				$contact_id = (int)$contact_sync->subscriber_id;
				$this->log_message[] = sprintf( __( 'Contact subscribed to form %s (ActiveCampaign ID = %s)', 'wc-ac-hook' ), $this->form_id, $contact_id);
			}
			else $this->log_message[] = sprintf( __( 'Syncing contact failed. Error returned: %s)', 'wc-ac-hook' ), $contact_sync->error);
		}
	}

	public function remove_tags($contact) {
		if ($this->api_valid) {
			$tag_remove = $this->activecampaign_connection->api('contact/tag_remove', $contact);
			if (!(int)$tag_remove->success) 
				$this->log_message[] = sprintf( __( 'Remove tags on contact failed. Error returned: %s)', 'wc-ac-hook' ), $tag_remove->error);
		}
	}
	
}

endif;
?>