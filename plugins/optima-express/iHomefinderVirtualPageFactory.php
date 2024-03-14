<?php

class iHomefinderVirtualPageFactory {

	private static $instance;

	private function __construct() {
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	//Types used to determine the VirtualPage type in iHomefinderVirtualPageFactory.
	const DEFAULT_PAGE = "idx-default";
	const LISTING_SEARCH_RESULTS = "idx-results";
	const LISTING_DETAIL = "idx-detail";
	const LISTING_SOLD_DETAIL = "idx-sold-detail";
	const LISTING_SEARCH_FORM = "idx-search";
	const MAP_SEARCH_FORM = "idx-map-search";
	const LISTING_ADVANCED_SEARCH_FORM = "idx-advanced-search";
	const FEATURED_SEARCH = "idx-featured-search";
	const HOT_SHEET_LIST = "idx-hotsheets-list";
	const HOT_SHEET_LISTING_REPORT = "idx-hotsheets";
	const HOT_SHEET_OPEN_HOME_REPORT = "idx-hotsheet-open-home-report";
	const HOT_SHEET_MARKET_REPORT = "idx-hotsheet-market-report";
	const ORGANIZER_LOGIN = "idx-property-organizer-login";
	const ORGANIZER_LOGOUT = "idx-property-organizer-logout";
	const ORGANIZER_EDIT_SAVED_SEARCH = "idx-property-organizer-edit-saved-search";
	const ORGANIZER_EDIT_SAVED_SEARCH_SUBMIT = "idx-property-organizer-edit-saved-search-submit";
	const ORGANIZER_EMAIL_UPDATES_CONFIRMATION = "idx-property-organizer-email-updates-success";
	const ORGANIZER_DELETE_SAVED_SEARCH = "idx-property-organizer-delete-saved-search";
	const ORGANIZER_DELETE_SAVED_SEARCH_SUBMIT = "idx-property-organizer-delete-saved-search-submit";
	const ORGANIZER_VIEW_SAVED_SEARCH = "idx-property-organizer-view-saved-search";
	const ORGANIZER_VIEW_SAVED_SEARCH_LIST = "idx-property-organizer-view-saved-searches";
	const ORGANIZER_VIEW_SAVED_LISTING_LIST = "idx-property-organizer-view-saved-listings";
	const ORGANIZER_DELETE_SAVED_LISTING_SUBMIT = "idx-property-organizer-delete-saved-listing";
	const ORGANIZER_RESEND_CONFIRMATION_EMAIL = "idx-property-organizer-resend-confirmation-email";
	const ORGANIZER_ACTIVATE_SUBSCRIBER = "idx-property-organizer-activate-subscriber";
	const ORGANIZER_SEND_SUBSCRIBER_PASSWORD = "idx-property-organizer-send-login";
	const ORGANIZER_HELP = "idx-property-organizer-help";
	const ORGANIZER_EDIT_SUBSCRIBER = "idx-property-organizer-edit-subscriber";
	const CONTACT_FORM = "idx-contact-form";
	const VALUATION_FORM = "idx-valuation-form";
	const MORTGAGE_CALCULATOR = "idx-mortgage-calculator";
	const OPEN_HOME_SEARCH_FORM = "idx-open-home-search-form";
	const SUPPLEMENTAL_LISTING = "idx-supplemental-listing";
	const SOLD_FEATURED_LISTING = "idx-sold-featured-listing";
	const PENDING_FEATURED_LISTING = "idx-pending-featured-listing";
	const OFFICE_LIST = "idx-office-list";
	const OFFICE_DETAIL = "idx-office-detail";
	const AGENT_LIST = "idx-agent-list";
	const AGENT_DETAIL = "idx-agent-detail";
	const MLS_PORTAL_BOARD_OFFICE_SEARCH = "idx-mls-portal-office-search";
	const MLS_PORTAL_BOARD_OFFICE_LIST = "idx-mls-portal-office-list";
	const MLS_PORTAL_BOARD_OFFICE_LIST_NAME_STARTS_WITH = "idx-mls-portal-board-list-name-starts-with";
	const MLS_PORTAL_BOARD_OFFICE_DETAIL = "idx-mls-portal-office";
	const MLS_PORTAL_BOARD_MEMBER_SEARCH = "idx-mls-portal-agent-search";
	const MLS_PORTAL_BOARD_MEMBER_LIST = "idx-mls-portal-agent-list";
	const MLS_PORTAL_BOARD_MEMBER_LIST_LAST_NAME_STARTS_WITH = "idx-mls-portal-agent-list-last-name-starts-with";
	const MLS_PORTAL_BOARD_MEMBER_DETAIL = "idx-mls-portal-agent";
	
	/**
	 * 
	 * @param string $type
	 * @return iHomefinderVirtualPageInterface
	 */
	public function getVirtualPage($virtualPageType) {
		$virtualPage = null;
		switch($virtualPageType) {
			case self::DEFAULT_PAGE:
				$virtualPage = new iHomefinderDefaultVirtualPageImpl();
				break;
			case self::LISTING_SEARCH_RESULTS:
				$virtualPage = new iHomefinderSearchResultsVirtualPageImpl();
				break;
			case self::LISTING_DETAIL:
				$virtualPage = new iHomefinderListingDetailVirtualPageImpl();
				break;
			case self::LISTING_SOLD_DETAIL:
				$virtualPage = new iHomefinderListingSoldDetailVirtualPageImpl();
				break;
			case self::FEATURED_SEARCH:
				$virtualPage = new iHomefinderFeaturedSearchVirtualPageImpl();
				break;
			case self::LISTING_ADVANCED_SEARCH_FORM:
				$virtualPage = new iHomefinderAdvancedSearchFormVirtualPageImpl();
				break;
			case self::LISTING_SEARCH_FORM:
				$virtualPage = new iHomefinderSearchFormVirtualPageImpl();
				break;
			case self::MAP_SEARCH_FORM:
				$virtualPage = new iHomefinderMapSearchVirtualPageImpl();
				break;
			case self::HOT_SHEET_LISTING_REPORT:
				$virtualPage = new iHomefinderHotSheetListingReportVirtualPageImpl();
				break;
			case self::HOT_SHEET_OPEN_HOME_REPORT:
				$virtualPage = new iHomefinderHotsheetOpenHomeReportVirtualPageImpl();
				break;
			case self::HOT_SHEET_MARKET_REPORT:
				$virtualPage = new iHomefinderHotsheetMarketReportVirtualPageImpl();
				break;
			case self::HOT_SHEET_LIST:
				$virtualPage = new iHomefinderHotsheetListVirtualPageImpl();
				break;
			case self::ORGANIZER_LOGIN:
				$virtualPage = new iHomefinderOrganizerLoginFormVirtualPageImpl();
				break;
			case self::ORGANIZER_LOGOUT:
				$virtualPage = new iHomefinderOrganizerLogoutVirtualPageImpl();
				break;
			case self::ORGANIZER_EDIT_SAVED_SEARCH:
				$virtualPage = new iHomefinderOrganizerEditSavedSearchFormVirtualPageImpl();
				break;
			case self::ORGANIZER_EMAIL_UPDATES_CONFIRMATION:
				$virtualPage = new iHomefinderOrganizerEmailUpdatesConfirmationVirtualPageImpl();
				break;
			case self::ORGANIZER_EDIT_SAVED_SEARCH_SUBMIT:
				$virtualPage = new iHomefinderOrganizerEditSavedSearchVirtualPageImpl();
				break;
			case self::ORGANIZER_DELETE_SAVED_SEARCH_SUBMIT:
				$virtualPage = new iHomefinderOrganizerDeleteSavedSearchVirtualPageImpl();
				break;
			case self::ORGANIZER_VIEW_SAVED_SEARCH:
				$virtualPage = new iHomefinderOrganizerViewSavedSearchVirtualPageImpl();
				break;
			case self::ORGANIZER_VIEW_SAVED_SEARCH_LIST:
				$virtualPage = new iHomefinderOrganizerViewSavedSearchListVirtualPageImpl();
				break;
			case self::ORGANIZER_VIEW_SAVED_LISTING_LIST:
				$virtualPage = new iHomefinderOrganizerViewSavedListingListVirtualPageImpl();
				break;
			case self::ORGANIZER_DELETE_SAVED_LISTING_SUBMIT:
				$virtualPage = new iHomefinderOrganizerDeleteSavedListingVirtualPageImpl();
				break;
			case self::ORGANIZER_ACTIVATE_SUBSCRIBER:
				$virtualPage = new iHomefinderOrganizerActivateSubscriberVirtualPageImpl();
				break;
			case self::ORGANIZER_RESEND_CONFIRMATION_EMAIL:
				$virtualPage = new iHomefinderOrganizerResendConfirmationVirtualPageImpl();
				break;
			case self::ORGANIZER_SEND_SUBSCRIBER_PASSWORD:
				$virtualPage = new iHomefinderOrganizerSendSubscriberPasswordVirtualPageImpl();
				break;
			case self::ORGANIZER_HELP:
				$virtualPage = new iHomefinderOrganizerHelpVirtualPageImpl();
				break;
			case self::ORGANIZER_EDIT_SUBSCRIBER:
				$virtualPage = new iHomefinderOrganizerEditSubscriberVirtualPageImpl();
				break;
			case self::CONTACT_FORM:
				$virtualPage = new iHomefinderContactFormVirtualPageImpl();
				break;
			case self::VALUATION_FORM:
				$virtualPage = new iHomefinderValuationFormVirtualPageImpl();
				break;
			case self::MORTGAGE_CALCULATOR:
				$virtualPage = new iHomefinderMortgageCalculatorVirtualPageImpl();
				break;
			case self::OPEN_HOME_SEARCH_FORM:
				$virtualPage = new iHomefinderOpenHomeSearchFormVirtualPageImpl();
				break;
			case self::SUPPLEMENTAL_LISTING:
				$virtualPage = new iHomefinderSupplementalListingVirtualPageImpl();
				break;
			case self::SOLD_FEATURED_LISTING:
				$virtualPage = new iHomefinderSoldFeaturedListingVirtualPageImpl();
				break;
			case self::PENDING_FEATURED_LISTING:
				$virtualPage = new iHomefinderPendingFeaturedListingVirtualPageImpl();
				break;
			case self::OFFICE_LIST:
				$virtualPage = new iHomefinderOfficeListVirtualPageImpl();
				break;
			case self::OFFICE_DETAIL:
				$virtualPage = new iHomefinderOfficeDetailVirtualPageImpl();
				break;
			case self::AGENT_LIST:
				$virtualPage = new iHomefinderAgentListVirtualPageImpl();
				break;
			case self::AGENT_DETAIL:
				$virtualPage = new iHomefinderAgentDetailVirtualPageImpl();
				break;
			case self::MLS_PORTAL_BOARD_OFFICE_SEARCH:
				$virtualPage = new iHomefinderMlsPortalBoardOfficeSearchVirtualPageImpl();
				break;
			case self::MLS_PORTAL_BOARD_OFFICE_LIST:
				$virtualPage = new iHomefinderMlsPortalBoardOfficeListVirtualPageImpl();
				break;
			case self::MLS_PORTAL_BOARD_OFFICE_LIST_NAME_STARTS_WITH:
				$virtualPage = new iHomefinderMlsPortalBoardOfficeListNameStartsWithVirtualPageImpl();
				break;
			case self::MLS_PORTAL_BOARD_OFFICE_DETAIL:
				$virtualPage = new iHomefinderMlsPortalBoardOfficeDetailVirtualPageImpl();
				break;
			case self::MLS_PORTAL_BOARD_MEMBER_SEARCH:
				$virtualPage = new iHomefinderMlsPortalBoardMemberSearchVirtualPageImpl();
				break;
			case self::MLS_PORTAL_BOARD_MEMBER_LIST:
				$virtualPage = new iHomefinderMlsPortalBoardMemberListVirtualPageImpl();
				break;
			case self::MLS_PORTAL_BOARD_MEMBER_LIST_LAST_NAME_STARTS_WITH:
				$virtualPage = new iHomefinderMlsPortalBoardMemberListLastNameStartsWithVirtualPageImpl();
				break;
			case self::MLS_PORTAL_BOARD_MEMBER_DETAIL:
				$virtualPage = new iHomefinderMlsPortalBoardMemberDetailVirtualPageImpl();
				break;
		}
		return $virtualPage;
	}
	
	/**
	 * @param string $name
	 * @return boolean
	 */
	public static function isOrganizerPage($name) {
		$pages = array(
			self::ORGANIZER_LOGIN,
			self::ORGANIZER_LOGOUT,
			self::ORGANIZER_EDIT_SAVED_SEARCH,
			self::ORGANIZER_EDIT_SAVED_SEARCH_SUBMIT,
			self::ORGANIZER_EMAIL_UPDATES_CONFIRMATION,
			self::ORGANIZER_DELETE_SAVED_SEARCH,
			self::ORGANIZER_DELETE_SAVED_SEARCH_SUBMIT,
			self::ORGANIZER_VIEW_SAVED_SEARCH,
			self::ORGANIZER_VIEW_SAVED_SEARCH_LIST,
			self::ORGANIZER_VIEW_SAVED_LISTING_LIST,
			self::ORGANIZER_DELETE_SAVED_LISTING_SUBMIT,
			self::ORGANIZER_RESEND_CONFIRMATION_EMAIL,
			self::ORGANIZER_ACTIVATE_SUBSCRIBER,
			self::ORGANIZER_SEND_SUBSCRIBER_PASSWORD,
			self::ORGANIZER_HELP,
			self::ORGANIZER_EDIT_SUBSCRIBER,
		);
		return array_search($name, $pages) !== false;
	}
	
	public static function isHotSheetPage($name) {
		$pages = array(
			self::HOT_SHEET_LIST,
			self::HOT_SHEET_LISTING_REPORT,
			self::HOT_SHEET_OPEN_HOME_REPORT,
			self::HOT_SHEET_MARKET_REPORT,
		);
		return array_search($name, $pages) !== false;
	}
	
	public static function isEmailAlertsPage($name) {
		$pages = array (
			self::ORGANIZER_EDIT_SAVED_SEARCH,
			self::ORGANIZER_EMAIL_UPDATES_CONFIRMATION,
		);
		return array_search($name, $pages) !== false;
	}
}