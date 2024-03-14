<?php

if (!defined('ABSPATH')) exit;
if (!class_exists('CWSInfo')) :
	class CWSInfo {
		public $settings;
		public $config;
		public $plugname = 'cloudways';
		public $brandname = 'Cloudways Migrate';
		public $badgeinfo = 'cwsbadge';
		public $ip_header_option = 'cwsipheader';
		public $brand_option = 'cwsbrand';
		public $version = '5.25';
		public $webpage = 'https://www.cloudways.com';
		public $appurl = 'https://migrate.blogvault.net';
		public $slug = 'bv-cloudways-automated-migration/cloudways.php';
		public $plug_redirect = 'cwsredirect';
		public $logo = '/aseets/img/clouwdways-logo.png';
		public $brand_icon = '/assets/img/favicon.ico';
		public $services_option_name = 'BVSERVICESOPTIONNAME';
		public $author = 'Cloudways';
		public $title = 'Cloudways WordPress Migrator';

		const DB_VERSION = '4';

		public function __construct($settings) {
			$this->settings = $settings;
			$this->config = $this->settings->getOption($this->services_option_name);
		}

		public function getCurrentDBVersion() {
			$bvconfig = $this->config;
			if ($bvconfig && array_key_exists('db_version', $bvconfig)) {
				return $bvconfig['db_version'];
			}
			return false;
		}

		public function hasValidDBVersion() {
			return CWSInfo::DB_VERSION === $this->getCurrentDBVersion();
		}

		public static function getRequestID() {
			if (!defined("BV_REQUEST_ID")) {
				define("BV_REQUEST_ID", uniqid(mt_rand()));
			}
			return BV_REQUEST_ID;
		}

		public function canSetCWBranding() {
			if (CWSWPSiteInfo::isCWServer()) {

				$bot_protect_accounts = CWSAccount::accountsByType($this->settings, 'botprotect');
				if (sizeof($bot_protect_accounts) >= 1)
					return true;

				$bot_protect_accounts = CWSAccount::accountsByPattern($this->settings, 'email', '/@cw_user\.com$/');
				if (sizeof($bot_protect_accounts) >= 1)
					return true;
			}

			return false;
		}

		public function getBrandInfo() {
			return $this->settings->getOption($this->brand_option);
		}

		public function getBrandName() {
			$brand = $this->getBrandInfo();
			if (is_array($brand) && array_key_exists('menuname', $brand)) {
				return $brand['menuname'];
			}
		  
			return $this->brandname;
		}

		public function getBrandIcon() {
			$brand = $this->getBrandInfo();
			if (is_array($brand) && array_key_exists('brand_icon', $brand)) {
				return $brand['brand_icon'];
			}
			return $this->brand_icon;
		}

		public function getWatchTime() {
			$time = $this->settings->getOption('bvwatchtime');
			return ($time ? $time : 0);
		}

		public function appUrl() {
			if (defined('BV_APP_URL')) {
				return BV_APP_URL;
			} else {
				$brand = $this->getBrandInfo();
				if (is_array($brand) && array_key_exists('appurl', $brand)) {
					return $brand['appurl'];
				}
				return $this->appurl;
			}
		}

		public function isActivePlugin() {
			$expiry_time = time() - (3 * 24 * 3600);
			return ($this->getWatchTime() > $expiry_time);
		}

		public function isValidEnvironment(){
			$bvsiteinfo = new CWSWPSiteInfo();
			$bvconfig = $this->config;

			if (is_multisite()) {
				return true;
			} elseif ($bvconfig && array_key_exists("siteurl_scheme", $bvconfig)) {
				$siteurl = $bvsiteinfo->siteurl('', $bvconfig["siteurl_scheme"]);
				if (array_key_exists("abspath", $bvconfig) &&
						array_key_exists("siteurl", $bvconfig) && !empty($siteurl)) {
					return ($bvconfig["abspath"] == ABSPATH && $bvconfig["siteurl"] == $siteurl);
				}
			}
			return true;
		}

		public function isProtectModuleEnabled() {
			return $this->isServiceActive("protect") && $this->isValidEnvironment();
		}

		public function isDynSyncModuleEnabled() {
			if ($this->isServiceActive("dynsync")) {
				$dynconfig = $this->config['dynsync'];
				if (array_key_exists('dynplug', $dynconfig) && ($dynconfig['dynplug'] === $this->plugname)) {
					return true;
				}
			}
			return false;
		}

		public function isServiceActive($service) {
			$bvconfig = $this->config;
			if ($bvconfig && array_key_exists('services', $bvconfig)) {
				return in_array($service, $bvconfig['services']) && $this->isActivePlugin();
			}
			return false;
		}

		public function isActivateRedirectSet() {
			return ($this->settings->getOption($this->plug_redirect) === 'yes') ? true : false;
		}

		public function isMalcare() {
			return $this->getBrandName() === 'MalCare';
		}

		public function isBlogvault() {
			return $this->getBrandName() === 'BlogVault';
		}

		public function info() {
			return array(
				"bvversion" => $this->version,
				"sha1" => "true",
				"plugname" => $this->plugname
			);
		}
	}
endif;