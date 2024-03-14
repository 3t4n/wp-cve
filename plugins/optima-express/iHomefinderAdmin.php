<?php

class iHomefinderAdmin {

	private static $instance;
	private $utility;
	private $displayRules;
	
	private function __construct() {
		$this->utility = iHomefinderUtility::getInstance();
		$this->displayRules = iHomefinderDisplayRules::getInstance();
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function checkError() {
		
		$pageName = $this->utility->getRequestVar("page");
		
		//Check for valid plugin registration
		//Do not check for registration on the registration page.
		if($pageName !== iHomefinderConstants::PAGE_ACTIVATE && !$this->isActivated()) {
			?>
			<div id="ihf-main-container">
				<p class="ihf-green-bar">
					<a href="admin.php?page=<?php echo iHomefinderConstants::PAGE_ACTIVATE ?>" class="button button-primary">Activate Your Optima Express Account</a>
					&nbsp;&nbsp;&nbsp;Get an unlimited free trial or paid subscription for your MLS
				</p>
			</div>
			<?php
		}
		
		if(get_option(iHomefinderConstants::COMPATIBILITY_CHECK_ENABLED, true) !== "false") {
			$errors = array();
			//check if permalink structure is set
			$permalinkStructure = get_option("permalink_structure", null);
			if(empty($permalinkStructure)) {
				$errors[] = "<a href=\"" . admin_url("options-permalink.php") . "\">WordPress permalink settings are set as default (Error 404)</a>";
			}
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("requestType", "compatibility-check")
			;
			$remoteRequest->setCacheExpiration(60*60*24);
			$remoteResponse = $remoteRequest->remoteGetRequest();
			if(!empty($remoteResponse)) {
				$content = null;
				$content = (string) $remoteResponse->getJson();
				if(!empty($content)) {
					$compatibility = json_decode($content, true);
					if(!empty($compatibility) && is_array($compatibility)) {
						//check plugins
						if(array_key_exists("Plugin", $compatibility)) {
							$incompatiblePlugins = $compatibility["Plugin"];
							if(is_array($incompatiblePlugins)) {
								$plugins = get_plugins();
								foreach($plugins as $pluginPath => $plugin) {
									if(is_plugin_active($pluginPath)) {
										$pluginName = $plugin["Name"];
										if(array_key_exists($pluginName, $incompatiblePlugins)) {
											$message = $incompatiblePlugins[$pluginName];
											if($message !== null) {
												$errors[] = "<a href=\"" . admin_url("plugins.php") . "?s=" . urlencode($pluginName) . "\">" . $pluginName . "</a> (" . $message . ")";
											}
										}
									}
								}
							}
						}
						//check theme
						if(array_key_exists("Theme", $compatibility)) {
							$theme = wp_get_theme();
							$themeName = $theme->get("Name");
							$incompatibleThemes = $compatibility["Theme"];
							if(is_array($incompatibleThemes) && array_key_exists($themeName, $incompatibleThemes)) {
								$message = $incompatibleThemes[$themeName];
								if($message !== null) {
									$errors[] = "<a href=\"" . admin_url("themes.php") . "\">" . $themeName . "</a> (" . $message . ")";
								}
							}
						}
					}
				}
			}
			if($this->utility->isDatabaseCached()) {
				$errors[] = "Database caching is enabled, which prevents updated IDX results from displaying.";
			}
			//check error count
			if(count($errors) > 0) {
				?>
				<div class="error">
					<h4 style="float: left;">
						<?php echo count($errors) ?> compatibility issue<?php if(count($errors) > 1) { ?>s<?php } ?>
					</h4>
					<form style="float: right; margin-top: 5px; display: none;" method="post" action="options.php">
						<?php settings_fields(iHomefinderConstants::OPTION_GROUP_COMPATIBILITY_CHECK); ?>
						<input type="hidden" value="false" name="<?php echo iHomefinderConstants::COMPATIBILITY_CHECK_ENABLED; ?>" />
						<button class="button-secondary" type="submit">Dismiss compatibility warnings</button>
					</form>
					<div style="clear: both;">
						<?php foreach($errors as $error) { ?>
							<p>
								<?php echo $error; ?>
							</p>
						<?php } ?>
					</div>
				</div>
				<?php
			}
		}
	}

	public function createAdminMenu() {
		$displayRules = iHomefinderDisplayRules::getInstance();
		add_menu_page("Optima Express", "Optima Express", "manage_options", iHomefinderConstants::PAGE_INFORMATION, array(iHomefinderAdminInformation::getInstance(), "getPage"));
		add_submenu_page(iHomefinderConstants::PAGE_INFORMATION, "Information", "Information", "manage_options", iHomefinderConstants::PAGE_INFORMATION, array(iHomefinderAdminInformation::getInstance(), "getPage"));
		add_submenu_page(iHomefinderConstants::PAGE_INFORMATION, "Shortcodes", "Shortcodes", "manage_options", iHomefinderConstants::PAGE_SHORTCODES, array(iHomefinderAdminShortcodes::getInstance(), "getPage"));
		add_submenu_page(iHomefinderConstants::PAGE_INFORMATION, "Register", "Register", "manage_options", iHomefinderConstants::PAGE_ACTIVATE, array(iHomefinderAdminActivate::getInstance(), "getPage"));
		if($this->isActivated()) {
			add_submenu_page(iHomefinderConstants::PAGE_INFORMATION, "IDX Control Panel", "IDX Control Panel", "manage_options", iHomefinderConstants::PAGE_IDX_CONTROL_PANEL, array(iHomefinderAdminControlPanel::getInstance(), "getPage"));
		}
		add_submenu_page(iHomefinderConstants::PAGE_INFORMATION, "IDX Pages", "IDX Pages", "manage_options", iHomefinderConstants::PAGE_IDX_PAGES, array(iHomefinderAdminPageConfig::getInstance(), "getPage"));
		add_submenu_page(iHomefinderConstants::PAGE_INFORMATION, "Configuration", "Configuration", "manage_options", iHomefinderConstants::PAGE_CONFIGURATION, array(iHomefinderAdminConfiguration::getInstance(), "getPage"));
		if($displayRules->isAgentBioWidgetEnabled()) {
			add_submenu_page(iHomefinderConstants::PAGE_INFORMATION, "Bio Widget", "Bio Widget", "manage_options", iHomefinderConstants::PAGE_BIO, array(iHomefinderAdminBio::getInstance(), "getPage"));
		}
		if($displayRules->isSocialEnabled()) {
			add_submenu_page(iHomefinderConstants::PAGE_INFORMATION, "Social Widget", "Social Widget", "manage_options", iHomefinderConstants::PAGE_SOCIAL, array(iHomefinderAdminSocial::getInstance(), "getPage"));
		}
		add_submenu_page(iHomefinderConstants::PAGE_INFORMATION, "Email Branding", "Email Branding", "manage_options", iHomefinderConstants::PAGE_EMAIL_BRANDING, array(iHomefinderAdminEmail::getInstance(), "getPage"));
		if($displayRules->isCommunityPagesEnabled()) {
			add_submenu_page(iHomefinderConstants::PAGE_INFORMATION, "Community Pages", "Community Pages", "manage_options", iHomefinderConstants::PAGE_COMMUNITY_PAGES, array(iHomefinderAdminCommunityPages::getInstance(), "getPage"));
		}
		if($displayRules->isSeoCityLinksEnabled()) {
			add_submenu_page(iHomefinderConstants::PAGE_INFORMATION, "SEO City Links", "SEO City Links", "manage_options", iHomefinderConstants::PAGE_SEO_CITY_LINKS, array(iHomefinderAdminSeoCityLinks::getInstance(), "getPage"));
		}
	}
	
	/**
	 * Create register option groups and associated options.
	 * Later use settings_fields in the forms to populate the options.
	 */
	public function registerSettings() {
		//compatibility check shows on all dashboard pages
		register_setting(iHomefinderConstants::OPTION_GROUP_COMPATIBILITY_CHECK, iHomefinderConstants::COMPATIBILITY_CHECK_ENABLED);
		//admin pages
		iHomefinderAdminActivate::getInstance()->registerSettings();
		iHomefinderAdminPageConfig::getInstance()->registerSettings();
		iHomefinderAdminConfiguration::getInstance()->registerSettings();
		iHomefinderAdminBio::getInstance()->registerSettings();
		iHomefinderAdminSocial::getInstance()->registerSettings();
		iHomefinderAdminEmail::getInstance()->registerSettings();
		iHomefinderAdminSeoCityLinks::getInstance()->registerSettings();
	}
	
	public function addScripts() {
		$pages = array(
			iHomefinderConstants::PAGE_INFORMATION,
			iHomefinderConstants::PAGE_ACTIVATE,
			iHomefinderConstants::PAGE_IDX_CONTROL_PANEL,
			iHomefinderConstants::PAGE_IDX_PAGES,
			iHomefinderConstants::PAGE_CONFIGURATION,
			iHomefinderConstants::PAGE_BIO,
			iHomefinderConstants::PAGE_SOCIAL,
			iHomefinderConstants::PAGE_EMAIL_BRANDING,
			iHomefinderConstants::PAGE_COMMUNITY_PAGES,
			iHomefinderConstants::PAGE_SEO_CITY_LINKS
		);
		if(array_key_exists("page", $_GET)) {
			$page = $_GET["page"];
			$result = array_search($page, $pages);
			if($result !== false && $result >= 0) {
				wp_enqueue_script("jquery");
				wp_enqueue_script("jquery-ui-core");
				wp_enqueue_script("jquery-ui-autocomplete", "", array("jquery-ui-widget", "jquery-ui-position"));
				wp_enqueue_script("jquery-ui-accordion", "", array("jquery-ui-widget", "jquery-ui-position"));
				wp_enqueue_style("thickbox");
				wp_enqueue_script("jquery-textrange", plugins_url("js/jquery-textrange.js", __FILE__), array("jquery"), iHomefinderConstants::VERSION);
			}
		}
		wp_enqueue_script("oe-dashboard", plugins_url("js/dashboard.js", __FILE__), array("jquery", "editor", "media-upload", "thickbox"), iHomefinderConstants::VERSION);
		wp_enqueue_style("oe-dashboard", plugins_url("css/dashboard.css", __FILE__), null, iHomefinderConstants::VERSION);
	}
	
	/**
	 * @deprecated - use activateAuthenticationToken instead
	 */
	public function updateAuthenticationToken() {
		$this->activateAuthenticationToken();
	}

	public function activateAuthenticationToken($activationToken = null) {
		if(!empty($activationToken)) {
			update_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION, $activationToken);
		}
		$authenticationInfo = $this->getAuthenticationInfo();
		if($authenticationInfo !== null) {
			if(property_exists($authenticationInfo, "authenticationToken")) {
				$authenticationToken = (string) $authenticationInfo->authenticationToken;
				$this->setAuthenticationToken($authenticationToken);
			}
			if(property_exists($authenticationInfo, "permissions")) {
				$permissions = $authenticationInfo->permissions;
				iHomefinderDisplayRules::getInstance()->setPermissions($permissions);
			}
			update_option(iHomefinderConstants::IS_ACTIVATED_OPTION, "true");
			//We need to flush the rewrite rules, if any permalinks have been updated.
			//Only flush in the admin screens, because that is the only point where urls patterns may change.
			iHomefinderRewriteRules::getInstance()->flushRules();
			//clear the cache
			iHomefinderCacheUtility::getInstance()->deleteItems();
			//update menu with any new available menu items
			iHomefinderMenu::getInstance()->updateMenu();
		}
	}
	
	public function deleteAuthenticationToken() {
		delete_option(iHomefinderConstants::AUTHENTICATION_TOKEN_OPTION);
	}
	
	public function getActivationToken() {
		$activationToken = get_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION, null);
		return $activationToken;
	}
	
	public function getAuthenticationToken() {
		$authenticationToken = get_option(iHomefinderConstants::AUTHENTICATION_TOKEN_OPTION, null);
		return $authenticationToken;
	}
	
	public function setAuthenticationToken($authenticationToken) {
		update_option(iHomefinderConstants::AUTHENTICATION_TOKEN_OPTION, $authenticationToken);
	}
	
	public function previouslyActivated() {
		return get_option(iHomefinderConstants::IS_ACTIVATED_OPTION, false);
	}
	
	public function isActivated() {
		$result = false;
		$authenticationToken = $this->getAuthenticationToken();
		if(!empty($authenticationToken)) {
			$result = true;
		}
		return $result;
	}
	
	private function getAuthenticationInfo() {
		$activationToken = $this->getActivationToken();
		if(empty($activationToken)) {
			return null;
		}	
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$ajaxBaseUrl = urlencode($urlFactory->getAjaxBaseUrl());
		$listingsSearchResultsUrl = urlencode($urlFactory->getListingsSearchResultsUrl(true));
		$listingsSearchFormUrl = urlencode($urlFactory->getListingsSearchFormUrl(true));
		$listingDetailUrl = urlencode($urlFactory->getListingDetailUrl(true));
		$featuredSearchResultsUrl = urlencode($urlFactory->getFeaturedSearchResultsUrl(true));
		$hotSheetListingReportUrl = urlencode($urlFactory->getHotSheetListingReportUrl(true));
		$hotSheetOpenHomeReportUrl = urlencode($urlFactory->getHotSheetOpenHomeReportUrl(true));
		$hotSheetMarketReportUrl = urlencode($urlFactory->getHotSheetMarketReportUrl(true));
		$organizerLoginUrl = urlencode($urlFactory->getOrganizerLoginUrl(true));
		$organizerLogoutUrl = urlencode($urlFactory->getOrganizerLogoutUrl(true));
		$organizerLoginSubmitUrl = urlencode($urlFactory->getOrganizerLoginSubmitUrl(true));
		$organizerEditSavedSearchUrl = urlencode($urlFactory->getOrganizerEditSavedSearchUrl(true));
		$organizerEditSavedSearchSubmitUrl = urlencode($urlFactory->getOrganizerEditSavedSearchSubmitUrl(true));
		$organizerDeleteSavedSearchSubmitUrl = urlencode($urlFactory->getOrganizerDeleteSavedSearchSubmitUrl(true));
		$organizerViewSavedSearchUrl = urlencode($urlFactory->getOrganizerViewSavedSearchUrl(true));
		$organizerViewSavedSearchListUrl = urlencode($urlFactory->getOrganizerViewSavedSearchListUrl(true));
		$organizerViewSavedListingListUrl = urlencode($urlFactory->getOrganizerViewSavedListingListUrl(true));
		$organizerDeleteSavedListingUrl = urlencode($urlFactory->getOrganizerDeleteSavedListingUrl(true));
		$organizerResendConfirmationEmailUrl = urlencode($urlFactory->getOrganizerResendConfirmationEmailUrl(true));
		$organizerActivateSubscriberUrl = urlencode($urlFactory->getOrganizerActivateSubscriberUrl(true));
		$organizerSendSubscriberPasswordUrl = urlencode($urlFactory->getOrganizerSendSubscriberPasswordUrl(true));
		$listingsAdvancedSearchFormUrl = urlencode($urlFactory->getListingsAdvancedSearchFormUrl(true));
		$organizerHelpUrl = urlencode($urlFactory->getOrganizerHelpUrl(true));
		$organizerEditSubscriberUrl = urlencode($urlFactory->getOrganizerEditSubscriberUrl(true));
		$contactFormUrl = urlencode($urlFactory->getContactFormUrl(true));
		$valuationFormUrl = urlencode($urlFactory->getValuationFormUrl(true));
		$mortgageCalculatorUrl = urlencode($urlFactory->getMortgageCalculatorUrl(true));
		$listingSoldDetailUrl = urlencode($urlFactory->getListingSoldDetailUrl(true));
		$openHomeSearchFormUrl = urlencode($urlFactory->getOpenHomeSearchFormUrl(true));
		$soldFeaturedListingUrl = urlencode($urlFactory->getSoldFeaturedListingUrl(true));
		$pendingFeaturedListingUrl = urlencode($urlFactory->getPendingFeaturedListingUrl(true));
		$supplementalListingUrl = urlencode($urlFactory->getSupplementalListingUrl(true));
		$officeListUrl = urlencode($urlFactory->getOfficeListUrl(true));
		$officeDetailUrl = urlencode($urlFactory->getOfficeDetailUrl(true));
		$agentBioListUrl = urlencode($urlFactory->getAgentListUrl(true));
		$agentBioDetailUrl = urlencode($urlFactory->getAgentDetailUrl(true));
		$mapSearchUrl = urlencode($urlFactory->getMapSearchFormUrl(true));
		$mlsPortalBoardOfficeListUrl = urlencode($urlFactory->getMlsPortalBoardOfficeListUrl(true));
		$mlsPortalBoardOfficeListNameStartsWithUrl = urlencode($urlFactory->getMlsPortalBoardOfficeListNameStartsWithUrl(true));
		$mlsPortalBoardOfficeDetailUrl = urlencode($urlFactory->getMlsPortalBoardOfficeDetailUrl(true));
		$mlsPortalBoardMemberListUrl = urlencode($urlFactory->getMlsPortalBoardMemberListUrl(true));
		$mlsPortalBoardMemberListLastNameStartsWithUrl = urlencode($urlFactory->getMlsPortalBoardMemberListLastNameStartsWithUrl(true));
		$mlsPortalBoardMemberDetailUrl = urlencode($urlFactory->getMlsPortalBoardMemberDetailUrl(true));
		$mlsPortalBoardMemberSearchUrl = urlencode($urlFactory->getMlsPortalBoardMemberSearchUrl(true));
		$mlsPortalBoardOfficeSearchUrl = urlencode($urlFactory->getMlsPortalBoardOfficeSearchUrl(true));
		$cssOverride = urlencode(get_option(iHomefinderConstants::CSS_OVERRIDE_OPTION, null));
		$shadowDomHtml = urlencode(get_option(iHomefinderConstants::SHADOW_DOM_HTML_OPTION, null));
		$shadowDomCss = urlencode(get_option(iHomefinderConstants::SHADOW_DOM_CSS_OPTION, null));
		$mobileSiteYn = get_option(iHomefinderConstants::OPTION_MOBILE_SITE_YN, null);
		$emailDisplayType = get_option(iHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION, null);
		$emailHeader = urlencode(iHomefinderAdminEmail::getInstance()->getHeader());
		$emailFooter = urlencode(iHomefinderAdminEmail::getInstance()->getFooter());
		$emailPhotoUrl = get_option(iHomefinderConstants::EMAIL_PHOTO_OPTION, null);
		$emailLogoUrl = get_option(iHomefinderConstants::EMAIL_LOGO_OPTION, null);
		$emailName = get_option(iHomefinderConstants::EMAIL_NAME_OPTION, null);
		$emailCompany = get_option(iHomefinderConstants::EMAIL_COMPANY_OPTION, null);
		$emailPhone = get_option(iHomefinderConstants::EMAIL_PHONE_OPTION, null);
		$emailAddressLine1 = get_option(iHomefinderConstants::EMAIL_ADDRESS_LINE1_OPTION, null);
		$emailAddressLine2 = get_option(iHomefinderConstants::EMAIL_ADDRESS_LINE2_OPTION, null);
				
		$emailBrandingType = null;
		switch($emailDisplayType) {
			case iHomefinderAdminEmail::EMAIL_DISPLAY_TYPE_CUSTOM_IMAGES_VALUE;
			case iHomefinderAdminEmail::EMAIL_DISPLAY_TYPE_DEFAULT_VALUE;
			$emailBrandingType = "basic";
				break;
			case iHomefinderAdminEmail::EMAIL_DISPLAY_TYPE_CUSTOM_HTML_VALUE;
				$emailBrandingType = "custom";
				break;
		}
		
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameter("requestType", "activate")
			->addParameter("activationToken", $activationToken)
			->addParameter("ajaxBaseUrl", $ajaxBaseUrl)
			->addParameter("type", "wordpress")
			->addParameter("layoutType", "responsive")
			->addParameter("listingSearchResultsUrl", $listingsSearchResultsUrl)
			->addParameter("listingSearchByAddressResultsUrl", $listingsSearchResultsUrl)
			->addParameter("listingSearchByListingIdResultsUrl", $listingsSearchResultsUrl)
			->addParameter("listingSearchFormUrl", $listingsSearchFormUrl)
			->addParameter("listingDetailUrl", $listingDetailUrl)
			->addParameter("featuredSearchResultsUrl", $featuredSearchResultsUrl)
			->addParameter("hotsheetSearchResultsUrl", $hotSheetListingReportUrl)
			->addParameter("hotSheetOpenHomeReportUrl", $hotSheetOpenHomeReportUrl)
			->addParameter("hotSheetMarketReportUrl", $hotSheetMarketReportUrl)
			->addParameter("organizerLoginUrl", $organizerLoginUrl)
			->addParameter("organizerLogoutUrl", $organizerLogoutUrl)
			->addParameter("organizerLoginSubmitUrl", $organizerLoginSubmitUrl)
			->addParameter("organizerEditSavedSearchUrl", $organizerEditSavedSearchUrl)
			->addParameter("organizerEditSavedSearchSubmitUrl", $organizerEditSavedSearchSubmitUrl)
			->addParameter("organizerDeleteSavedSearchSubmitUrl", $organizerDeleteSavedSearchSubmitUrl)
			->addParameter("organizerViewSavedSearchUrl", $organizerViewSavedSearchUrl)
			->addParameter("organizerViewSavedSearchListUrl", $organizerViewSavedSearchListUrl)
			->addParameter("organizerViewSavedListingListUrl", $organizerViewSavedListingListUrl)
			->addParameter("organizerDeleteSavedListingUrl", $organizerDeleteSavedListingUrl)
			->addParameter("organizerResendConfirmationEmailUrl", $organizerResendConfirmationEmailUrl)
			->addParameter("organizerActivateSubscriberUrl", $organizerActivateSubscriberUrl)
			->addParameter("organizerSendSubscriberPasswordUrl", $organizerSendSubscriberPasswordUrl)
			->addParameter("listingAdvancedSearchFormUrl", $listingsAdvancedSearchFormUrl)
			->addParameter("organizerHelpUrl", $organizerHelpUrl)
			->addParameter("organizerEditSubscriberUrl", $organizerEditSubscriberUrl)
			->addParameter("contactFormUrl", $contactFormUrl)
			->addParameter("valuationFormUrl", $valuationFormUrl)
			->addParameter("mortgageCalculatorUrl", $mortgageCalculatorUrl)
			->addParameter("listingSoldDetailUrl", $listingSoldDetailUrl)
			->addParameter("openHomeSearchFormUrl", $openHomeSearchFormUrl)
			->addParameter("soldFeaturedListingUrl", $soldFeaturedListingUrl)
			->addParameter("pendingFeaturedListingUrl", $pendingFeaturedListingUrl)
			->addParameter("supplementalListingUrl", $supplementalListingUrl)
			->addParameter("officeListUrl", $officeListUrl)
			->addParameter("officeDetailUrl", $officeDetailUrl)
			->addParameter("agentBioListUrl", $agentBioListUrl)
			->addParameter("agentBioDetailUrl", $agentBioDetailUrl)
			->addParameter("mapSearchUrl", $mapSearchUrl)
			->addParameter("cssOverride", $cssOverride)
			->addParameter("shadowDomHtml", $shadowDomHtml)
			->addParameter("shadowDomCss", $shadowDomCss)
			->addParameter("mlsPortalBoardOfficeResultsUrl", $mlsPortalBoardOfficeListUrl)
			->addParameter("mlsPortalBoardOfficeResultsLastNameStartsWithUrl", $mlsPortalBoardOfficeListNameStartsWithUrl)
			->addParameter("mlsPortalBoardOfficeDetailUrl", $mlsPortalBoardOfficeDetailUrl)
			->addParameter("mlsPortalBoardMemberResultsUrl", $mlsPortalBoardMemberListUrl)
			->addParameter("mlsPortalBoardMemberResultsLastNameStartsWithUrl", $mlsPortalBoardMemberListLastNameStartsWithUrl)
			->addParameter("mlsPortalBoardMemberDetailUrl", $mlsPortalBoardMemberDetailUrl)
			->addParameter("mlsPortalBoardMemberSearchUrl", $mlsPortalBoardMemberSearchUrl)
			->addParameter("mlsPortalBoardOfficeSearchUrl", $mlsPortalBoardOfficeSearchUrl)
			->addParameter("mobileSiteYn", $mobileSiteYn)
			->addParameter("emailBrandingType", $emailBrandingType)
			->addParameter("emailHeader", $emailHeader)
			->addParameter("emailFooter", $emailFooter)
			->addParameter("emailPhotoUrl", $emailPhotoUrl)
			->addParameter("emailLogoUrl", $emailLogoUrl)
			->addParameter("emailName", $emailName)
			->addParameter("emailCompany", $emailCompany)
			->addParameter("emailPhone", $emailPhone)
			->addParameter("emailAddressLine1", $emailAddressLine1)
			->addParameter("emailAddressLine2", $emailAddressLine2)
		;
		
		$remoteResponse = $remoteRequest->remotePostRequest();
		return $remoteResponse->getResponse();
	}
	
	private function getSitemap() {
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameter("requestType", "sitemap")
			->setCacheExpiration(60*60)
		;
		$remoteResponse = $remoteRequest->remoteGetRequest();
		return $remoteResponse;
	}
	
	public function addSitemapForGoogleXmlSitemaps() {
		$generatorObject = GoogleSitemapGenerator::GetInstance();
		if($generatorObject != null) {
			$urls = $this->getSitemap()->getResponse()->sitemap->urlset->url;
			foreach($urls as $url) {
				$location = null;
				if(property_exists($url, "loc") && !empty($url->loc)) {
					$location = $url->loc;
				}
				$modified = null;
				if(property_exists($url, "lastmod") && !empty($url->lastmod)) {
					$modified = new DateTime($url->lastmod);
					$modified = $modified->format("U");
				}
				$frequency = null;
				if(property_exists($url, "changefreq") && !empty($url->changefreq)) {
					$frequency = $url->changefreq;
				}
				$priority = null;
				if(property_exists($url, "priority") && !empty($url->priority)) {
					$priority = $url->priority;
				}
				$generatorObject->AddUrl($location, $modified, $frequency, $priority);
			}
		}
	}
	
	public function addSitemapForYoastWordPressSeo($content) {
		global $wpseo_sitemaps;
		if($wpseo_sitemaps != null) {
			$urls = $this->getSitemap()->getResponse()->sitemap->urlset->url;
			foreach($urls as $url) {
				$data = array();
				if(property_exists($url, "loc") && !empty($url->loc)) {
					$data["loc"] = $url->loc;
				}
				if(property_exists($url, "lastmod") && !empty($url->lastmod)) {
					$data["mod"] = new DateTime($url->lastmod);
					$data["mod"] = $data["mod"]->format("U");
				}
				if(property_exists($url, "changefreq") && !empty($url->changefreq)) {
					$data["chf"] = $url->changefreq;
				}
				if(property_exists($url, "priority") && !empty($url->priority)) {
					$data["pri"] = $url->priority;
				}
				if($wpseo_sitemaps->renderer != null) {
					$content .= $wpseo_sitemaps->renderer->sitemap_url($data);
				}
				else {
					$content .= $wpseo_sitemaps->sitemap_url($data);
				}
			}
		}
		return $content;
	}
	
}