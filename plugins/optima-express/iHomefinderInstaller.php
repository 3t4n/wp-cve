<?php

class iHomefinderInstaller{
	
	private static $instance;
	private $utility;
	private $rewriteRules;
	private $admin;
	private $displayRules;
	
	private function __construct() {
		$this->utility = iHomefinderUtility::getInstance();
		$this->rewriteRules = iHomefinderRewriteRules::getInstance();
		$this->admin = iHomefinderAdmin::getInstance();
		$this->displayRules = iHomefinderDisplayRules::getInstance();
	}
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * installs the Optima Express plugin and initializes rewrite rules.
	 */
	public function install() {
		$this->deleteOldOptions();
		$this->rewriteRules->initialize();
		$this->rewriteRules->flushRules();
	}
	
	/**
	 * removes Optima Express plugin related information.
	 */
	public function remove() {
		//Clear out any rewrite rules associated with the plugin
		$this->rewriteRules->flushRules();
	}
	
	/**
	 * Update authentication and rewrite information after upgrade
	 */
	public function upgrade() {
		$currentVersion = get_option(iHomefinderConstants::VERSION_OPTION, null);
		if($currentVersion !== iHomefinderConstants::VERSION) {
			update_option(iHomefinderConstants::VERSION_OPTION, iHomefinderConstants::VERSION);
			$this->deleteOldOptions();
			$this->addStyleTagsToCssOverride();
			if(!$this->utility->isDatabaseCached() && $this->admin->previouslyActivated()) {
				$this->admin->activateAuthenticationToken();
				$this->rewriteRules->initialize();
				$this->rewriteRules->flushRules();
			}
		}
	}
	
	private function deleteOldOptions() {
		$options = array(
			"ihf_email_updates_enabled",
			"ihf_save_listing_enabled",
			"ihf_hotsheet_enabled",
			"ihf_featured_properties_enabled",
			"ihf_organizer_enabled",
			"ihf_gallery_shortcodes_enabled",
			"ihf_office_enabled",
			"ihf_agent_bio_enabled",
			"ihf_sold_pending_enabled",
			"ihf_valuation_enabled",
			"ihf_contact_form_enabled",
			"ihf_supplemental_listings_enabled",
			"ihf_map_search_enabled",
			"ihf_seo_city_links_enabled",
			"ihf_community_pages_enabled",
			"ihf_pending_account",
			"ihf_active_trial_account",
			iHomefinderConstants::COMPATIBILITY_CHECK_ENABLED,
		);
		foreach($options as $option) {
			delete_option($option);
		}
	}
	
	private function addStyleTagsToCssOverride() {
		$migrated = get_option(iHomefinderConstants::CSS_OVERRIDE_MIGRATED, false);
		if(!$migrated) {
			$cssOverride = get_option(iHomefinderConstants::CSS_OVERRIDE_OPTION, null);
			update_option(iHomefinderConstants::CSS_OVERRIDE_OPTION, "<style type=\"text/css\">\n" . $cssOverride . "\n</style>");
			update_option(iHomefinderConstants::CSS_OVERRIDE_MIGRATED, true);
		}
	}
	
}