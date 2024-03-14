<?php

/**
 * Option names should lowercase snake_case
 */
interface iHomefinderConstants {

	const VERSION = "7.4.0";
	const VERSION_NAME = "Optima Express";
	const EXTERNAL_URL = "www.idxhome.com/service/wordpress";
	const CONTROL_PANEL_EXTERNAL_URL = "secure.idxre.com/idx/guid";
	const IHOMEFINDER_STORE_EXTERNAL_URL = "https://www.ihomefinder.com/account";
	const KESTREL_URL = "https://kestrel.idxhome.com";
	const KESTREL_DEVELOPMENT = false;
	
	/*
	 * menu slugs
	 */
	const PAGE_INFORMATION = "ihf-information";
	const PAGE_SHORTCODES = "ihf-shortcodes";
	const PAGE_ACTIVATE = "ihf-option-activate";
	const PAGE_IDX_CONTROL_PANEL = "ihf-idx-control-panel";
	const PAGE_IDX_PAGES = "ihf-option-pages";
	const PAGE_CONFIGURATION = "ihf-config-page";
	const PAGE_BIO = "ihf-bio-page";
	const PAGE_SOCIAL = "ihf-social-page";
	const PAGE_EMAIL_BRANDING = "ihf-email-branding-page";
	const PAGE_COMMUNITY_PAGES = "ihf-community-pages";
	const PAGE_SEO_CITY_LINKS = "ihf-seo-city-links-page";
	
	/*
	 * activation options
	 */
	const OPTION_GROUP_ACTIVATE = "ihf-option-activate";
	const ACTIVATION_TOKEN_OPTION = "ihf_activation_token"; //key used to register and generate authentication token
	const AUTHENTICATION_TOKEN_OPTION = "ihf_authentication_token"; //token sent with every request
	
	/*
	 * IDX page options
	 */
	const OPTION_VIRTUAL_PAGE_CONFIG = "ihf-virtual-page-config";
	
	//Default Virtual Page options
	const OPTION_VIRTUAL_PAGE_TEMPLATE_DEFAULT = "ihf-virtual-page-template-default";
	
	//Listing Detail Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_DETAIL = "ihf-virtual-page-title-detail";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_DETAIL = "ihf-virtual-page-template-detail";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_DETAIL = "ihf-virtual-page-permalink-text-detail";
	const OPTION_VIRTUAL_PAGE_META_TAGS_DETAIL = "ihf-virtual-page-meta-tags-detail";
	
	//Listing Search Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_SEARCH = "ihf-virtual-page-title-search";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_SEARCH = "ihf-virtual-page-template-search";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SEARCH = "ihf-virtual-page-permalink-text-search";
	const OPTION_VIRTUAL_PAGE_META_TAGS_SEARCH = "ihf-virtual-page-meta-tags-search";
	
	//Map Search Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_MAP_SEARCH = "ihf-virtual-page-title-map-search";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_MAP_SEARCH = "ihf-virtual-page-template-map-search";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MAP_SEARCH = "ihf-virtual-page-permalink-text-map-search";
	const OPTION_VIRTUAL_PAGE_META_TAGS_MAP_SEARCH = "ihf-virtual-page-meta-tags-map-search";
	
	//Advanced Listing Search Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_ADVANCED_SEARCH = "ihf-virtual-page-title-adv-search";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_ADVANCED_SEARCH = "ihf-virtual-page-template-adv-search";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ADVANCED_SEARCH = "ihf-virtual-page-permalink-text-adv-search";
	const OPTION_VIRTUAL_PAGE_META_TAGS_ADVANCED_SEARCH = "ihf-virtual-page-meta-tags-adv-search";
		
	//Organizer Login Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_ORGANIZER_LOGIN = "ihf-virtual-page-title-org-login";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_ORGANIZER_LOGIN = "ihf-virtual-page-template-org-login";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ORGANIZER_LOGIN = "ihf-virtual-page-permalink-text-org-login";
	const OPTION_VIRTUAL_PAGE_META_TAGS_ORGANIZER_LOGIN = "ihf-virtual-page-meta-tags-org-login";
	
	//Email Updated Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_EMAIL_UPDATES = "ihf-virtual-page-title-email-updates";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_EMAIL_UPDATES = "ihf-virtual-page-template-email-updates";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_EMAIL_UPDATES = "ihf-virtual-page-permalink-text-email-updates";
	const OPTION_VIRTUAL_PAGE_META_TAGS_EMAIL_UPDATES = "ihf-virtual-page-meta-tags-email-updates";
	
	//Featured Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_FEATURED = "ihf-virtual-page-title-featured";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_FEATURED = "ihf-virtual-page-template-featured";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_FEATURED = "ihf-virtual-page-permalink-text-featured";
	const OPTION_VIRTUAL_PAGE_META_TAGS_FEATURED = "ihf-virtual-page-meta-tags-featured";
	
	//Listing Report Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_HOT_SHEET_LISTING_REPORT = "ihf-virtual-page-title-hotsheet";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_HOT_SHEET_LISTING_REPORT = "ihf-virtual-page-template-hotsheet";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_HOT_SHEET_LISTING_REPORT = "ihf-virtual-page-permalink-text-hotsheet";
	const OPTION_VIRTUAL_PAGE_META_TAGS_HOT_SHEET_LISTING_REPORT = "ihf-virtual-page-meta-tags-hotsheet";
	
	//Open Home Report Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_HOT_SHEET_OPEN_HOME_REPORT = "ihf-virtual-page-title-hotsheet-open-home-report";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_HOT_SHEET_OPEN_HOME_REPORT = "ihf-virtual-page-template-hotsheet-open-home-report";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_HOT_SHEET_OPEN_HOME_REPORT = "ihf-virtual-page-permalink-text-hotsheet-open-home-report";
	const OPTION_VIRTUAL_PAGE_META_TAGS_HOT_SHEET_OPEN_HOME_REPORT = "ihf-virtual-page-meta-tags-hotsheet-open-home-report";
	
	//Market Report Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_HOT_SHEET_MARKET_REPORT = "ihf-virtual-page-title-hotsheet-market-report";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_HOT_SHEET_MARKET_REPORT = "ihf-virtual-page-template-hotsheet-market-report";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_HOT_SHEET_MARKET_REPORT = "ihf-virtual-page-permalink-text-hotsheet-market-report";
	const OPTION_VIRTUAL_PAGE_META_TAGS_HOT_SHEET_MARKET_REPORT = "ihf-virtual-page-meta-tags-hotsheet-market-report";
	
	//Hotsheet List Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_HOT_SHEET_LIST = "ihf-virtual-page-title-hotsheet-list";
	const OPTION_VIRTUAL_PAGE_META_TAGS_HOT_SHEET_LIST = "ihf-virtual-page-meta-tags-hotsheet-list";
	
	//Contact Form Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_CONTACT_FORM = "ihf-virtual-page-title-contact-form";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_CONTACT_FORM = "ihf-virtual-page-template-contact-form";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_CONTACT_FORM = "ihf-virtual-page-permalink-text-contact-form";
	const OPTION_VIRTUAL_PAGE_META_TAGS_CONTACT_FORM = "ihf-virtual-page-meta-tags-contact-form";
	
	//Valuation Form Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_VALUATION_FORM = "ihf-virtual-page-title-valuation-form";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_VALUATION_FORM = "ihf-virtual-page-template-valuation-form";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_VALUATION_FORM = "ihf-virtual-page-permalink-text-valuation-form";
	const OPTION_VIRTUAL_PAGE_META_TAGS_VALUATION_FORM = "ihf-virtual-page-meta-tags-valuation-form";

	//Mortgage Calculator Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_MORTGAGE_CALCULATOR = "ihf-virtual-page-title-mortgage-calculator";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_MORTGAGE_CALCULATOR = "ihf-virtual-page-template-mortgage-calculator";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MORTGAGE_CALCULATOR = "ihf-virtual-page-permalink-text-mortgage-calculator";
	const OPTION_VIRTUAL_PAGE_META_TAGS_MORTGAGE_CALCULATOR = "ihf-virtual-page-meta-tags-mortgage-calculator";

	//Open Home Search Form Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_OPEN_HOME_SEARCH_FORM = "ihf-virtual-page-title-open-home-search-form";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_OPEN_HOME_SEARCH_FORM = "ihf-virtual-page-template-open-home-search-form";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OPEN_HOME_SEARCH_FORM = "ihf-virtual-page-open-home-search-form";
	const OPTION_VIRTUAL_PAGE_META_TAGS_OPEN_HOME_SEARCH_FORM = "ihf-virtual-page-meta-tags-open-home-search-form";
	
	//Featured Sold Listings Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_SOLD_FEATURED = "ihf-virtual-page-title-sold-featured";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_SOLD_FEATURED = "ihf-virtual-page-template-sold-featured";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SOLD_FEATURED = "ihf-virtual-page-permalink-text-sold-featured";
	const OPTION_VIRTUAL_PAGE_META_TAGS_SOLD_FEATURED = "ihf-virtual-page-meta-tags-sold-featured";

	//Featured Pending Listings Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_PENDING_FEATURED = "ihf-virtual-page-title-pending-featured";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_PENDING_FEATURED = "ihf-virtual-page-template-pending-featured";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_PENDING_FEATURED = "ihf-virtual-page-permalink-text-pending-featured";
	const OPTION_VIRTUAL_PAGE_META_TAGS_PENDING_FEATURED = "ihf-virtual-page-meta-tags-pending-featured";
	
	//Supplemental listings
	const OPTION_VIRTUAL_PAGE_TITLE_SUPPLEMENTAL_LISTING = "ihf-virtual-page-title-supplemental-listing";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_SUPPLEMENTAL_LISTING = "ihf-virtual-page-template-supplemental-listing";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SUPPLEMENTAL_LISTING = "ihf-virtual-page-permalink-text-supplemental-listing";
	const OPTION_VIRTUAL_PAGE_META_TAGS_SUPPLEMENTAL_LISTING = "ihf-virtual-page-meta-tags-supplemental-listing";
	
	//Sold Detail Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_SOLD_DETAIL = "ihf-virtual-page-title-sold-detail";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_SOLD_DETAIL = "ihf-virtual-page-template-sold-detail";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SOLD_DETAIL = "ihf-virtual-page-permalink-text-sold-detail";
	const OPTION_VIRTUAL_PAGE_META_TAGS_SOLD_DETAIL = "ihf-virtual-page-meta-tags-sold-detail";
	
	//Office List Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_OFFICE_LIST = "ihf-virtual-page-title-office-list";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_OFFICE_LIST = "ihf-virtual-page-template-office-list";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OFFICE_LIST = "ihf-virtual-page-permalink-text-office-list";
	const OPTION_VIRTUAL_PAGE_META_TAGS_OFFICE_LIST = "ihf-virtual-page-meta-tags-office-list";
	
	//Listing Office Detail Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_OFFICE_DETAIL = "ihf-virtual-page-title-office-detail";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_OFFICE_DETAIL = "ihf-virtual-page-template-office-detail";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OFFICE_DETAIL = "ihf-virtual-page-permalink-text-office-detail";
	const OPTION_VIRTUAL_PAGE_META_TAGS_OFFICE_DETAIL = "ihf-virtual-page-meta-tags-office-detail";
	
	//Agent List Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_AGENT_LIST = "ihf-virtual-page-title-agent-list";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_AGENT_LIST = "ihf-virtual-page-template-agent-list";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_AGENT_LIST = "ihf-virtual-page-permalink-text-agent-list";
	const OPTION_VIRTUAL_PAGE_META_TAGS_AGENT_LIST = "ihf-virtual-page-meta-tags-agent-list";
	
	//Agent Detail Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_AGENT_DETAIL = "ihf-virtual-page-title-agent-detail";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_AGENT_DETAIL = "ihf-virtual-page-template-agent-detail";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_AGENT_DETAIL = "ihf-virtual-page-permalink-text-agent-detail";
	const OPTION_VIRTUAL_PAGE_META_TAGS_AGENT_DETAIL = "ihf-virtual-page-meta-tags-agent-detail";
	
	// MLS Portal - Office Search Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_MLS_PORTAL_BOARD_OFFICE_SEARCH = "ihf-virtual-page-title-mls-portal-board-office-search";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_MLS_PORTAL_BOARD_OFFICE_SEARCH = "ihf-virtual-page-template-mls-portal-board-office-search";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MLS_PORTAL_BOARD_OFFICE_SEARCH = "ihf-virtual-page-permalink-text-mls-portal-board-office-search";
	const OPTION_VIRTUAL_PAGE_META_TAGS_MLS_PORTAL_BOARD_OFFICE_SEARCH = "ihf-virtual-page-meta-tags-mls-portal-board-office-search";
	
	// MLS Portal - Office List Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_MLS_PORTAL_BOARD_OFFICE_LIST = "ihf-virtual-page-title-mls-portal-board-office-list";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_MLS_PORTAL_BOARD_OFFICE_LIST = "ihf-virtual-page-template-mls-portal-board-office-list";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MLS_PORTAL_BOARD_OFFICE_LIST = "ihf-virtual-page-permalink-text-mls-portal-board-office-list";
	const OPTION_VIRTUAL_PAGE_META_TAGS_MLS_PORTAL_BOARD_OFFICE_LIST = "ihf-virtual-page-meta-tags-mls-portal-board-office-list";
	
	// MLS Portal - Office List Alpha Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_MLS_PORTAL_BOARD_OFFICE_LIST_NAME_STARTS_WITH = "ihf-virtual-page-title-mls-portal-board-office-list-name-starts-with";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_MLS_PORTAL_BOARD_OFFICE_LIST_NAME_STARTS_WITH = "ihf-virtual-page-template-mls-portal-board-office-list-name-starts-with";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MLS_PORTAL_BOARD_OFFICE_LIST_NAME_STARTS_WITH = "ihf-virtual-page-permalink-text-mls-portal-board-office-list-name-starts-with";
	const OPTION_VIRTUAL_PAGE_META_TAGS_MLS_PORTAL_BOARD_OFFICE_LIST_NAME_STARTS_WITH = "ihf-virtual-page-meta-tags-mls-portal-board-office-list-name-starts-with";
	
	// MLS Portal - Listing Office Detail Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_MLS_PORTAL_BOARD_OFFICE_DETAIL = "ihf-virtual-page-title-mls-portal-board-office-detail";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_MLS_PORTAL_BOARD_OFFICE_DETAIL = "ihf-virtual-page-template-mls-portal-board-office-detail";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MLS_PORTAL_BOARD_OFFICE_DETAIL = "ihf-virtual-page-permalink-text-mls-portal-board-office-detail";
	const OPTION_VIRTUAL_PAGE_META_TAGS_MLS_PORTAL_BOARD_OFFICE_DETAIL = "ihf-virtual-page-meta-tags-mls-portal-board-office-detail";
	
	// MLS Portal - Agent Search Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_MLS_PORTAL_BOARD_MEMBER_SEARCH = "ihf-virtual-page-title-mls-portal-board-member-search";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_MLS_PORTAL_BOARD_MEMBER_SEARCH = "ihf-virtual-page-template-mls-portal-board-member-search";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MLS_PORTAL_BOARD_MEMBER_SEARCH = "ihf-virtual-page-permalink-text-mls-portal-board-member-search";
	const OPTION_VIRTUAL_PAGE_META_TAGS_MLS_PORTAL_BOARD_MEMBER_SEARCH = "ihf-virtual-page-meta-tags-mls-portal-board-member-search";
	
	// MLS Portal - Agent List Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_MLS_PORTAL_BOARD_MEMBER_LIST = "ihf-virtual-page-title-mls-portal-board-member-list";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_MLS_PORTAL_BOARD_MEMBER_LIST = "ihf-virtual-page-template-mls-portal-board-member-list";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MLS_PORTAL_BOARD_MEMBER_LIST = "ihf-virtual-page-permalink-text-mls-portal-board-member-list";
	const OPTION_VIRTUAL_PAGE_META_TAGS_MLS_PORTAL_BOARD_MEMBER_LIST = "ihf-virtual-page-meta-tags-mls-portal-board-member-list";
	
	// MLS Portal - Agent List Alpha Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_MLS_PORTAL_BOARD_MEMBER_LIST_LAST_NAME_STARTS_WITH = "ihf-virtual-page-title-mls-portal-board-member-list-last-name-starts-with";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_MLS_PORTAL_BOARD_MEMBER_LIST_LAST_NAME_STARTS_WITH = "ihf-virtual-page-template-mls-portal-board-member-list-last-name-starts-with";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MLS_PORTAL_BOARD_MEMBER_LIST_LAST_NAME_STARTS_WITH = "ihf-virtual-page-permalink-text-mls-portal-board-member-list-last-name-starts-with";
	const OPTION_VIRTUAL_PAGE_META_TAGS_MLS_PORTAL_BOARD_MEMBER_LIST_LAST_NAME_STARTS_WITH = "ihf-virtual-page-meta-tags-mls-portal-board-member-list-last-name-starts-with";
	
	// MLS Portal - Agent Detail Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_MLS_PORTAL_BOARD_MEMBER_DETAIL = "ihf-virtual-page-title-mls-portal-board-member-detail";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_MLS_PORTAL_BOARD_MEMBER_DETAIL = "ihf-virtual-page-template-mls-portal-board-member-detail";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MLS_PORTAL_BOARD_MEMBER_DETAIL = "ihf-virtual-page-permalink-text-mls-portal-board-member-detail";
	const OPTION_VIRTUAL_PAGE_META_TAGS_MLS_PORTAL_BOARD_MEMBER_DETAIL = "ihf-virtual-page-meta-tags-mls-portal-board-member-detail";
	
	
	/*
	 * configuration options
	 */
	const OPTION_GROUP_CONFIGURATION = "ihf-config-page";
	const CSS_OVERRIDE_OPTION = "ihf-css-override";
	const SHADOW_DOM_HTML_OPTION = "ihf-shadow-dom-html";
	const SHADOW_DOM_CSS_OPTION = "ihf-shadow-dom-css";
	const NO_ID_OPTION = "ihf-no-id";

	/* 
	 * @deprecated 
	 */
	const COLOR_SCHEME_OPTION = "ihf-color-scheme";
	
	/* 
	 * @deprecated 
	 */
	const OPTION_LAYOUT_TYPE = "ihf-option-layout-type";
	
	/* 
	 * @deprecated 
	 */
	const OPTION_LAYOUT_TYPE_RESPONSIVE = "responsive";
	
	/*
	 * bio widget options
	 */
	const OPTION_GROUP_BIO = "ihf-option-bio";
	const AGENT_PHOTO_OPTION = "ihf-bio-agent-photo-option";
	const AGENT_TEXT_OPTION = "ihf-bio-agent-text-option";
	const AGENT_DESIGNATIONS_OPTION = "ihf-bio-agent-designations-option";
	const AGENT_DISPLAY_TITLE_OPTION = "ihf-agent-display-title-option";
	const AGENT_LICENSE_INFO_OPTION = "ihf-agent-license-info-option";
	const CONTACT_PHONE_OPTION = "ihf-bio-contact-phone";
	const CONTACT_EMAIL_OPTION = "ihf-bio-contact-email";
	const OFFICE_LOGO_OPTION = "ihf-bio-office-logo";
	
	/*
	 * social widget options
	 */
	const OPTION_GROUP_SOCIAL = "ihf-option-social";
	const SOCIAL_FACEBOOK_URL_OPTION = "ihf-social-facebook-url-option";
	const SOCIAL_LINKEDIN_URL_OPTION = "ihf-social-linkedin-url-option";
	const SOCIAL_TWITTER_URL_OPTION = "ihf-social-twitter-url-option";
	const SOCIAL_PINTEREST_URL_OPTION = "ihf-social-pinterest-url";
	const SOCIAL_INSTAGRAM_URL_OPTION = "ihf-social-instagram-url";
	const SOCIAL_GOOGLE_PLUS_URL_OPTION = "ihf-social-google-plus-url";
	const SOCIAL_YOUTUBE_URL_OPTION = "ihf-social-youtube-url";
	const SOCIAL_YELP_URL_OPTION = "ihf-social-yelp-url";
	
	/*
	 * email branding options
	 */
	const OPTION_GROUP_EMAIL_DISPLAY = "ihf-option-email-display";
	const EMAIL_HEADER_OPTION = "ihf-email-display-header-option";
	const EMAIL_FOOTER_OPTION = "ihf-email-display-footer-option";
	const EMAIL_PHOTO_OPTION = "ihf-email-photo-option";
	const EMAIL_LOGO_OPTION = "ihf-email-logo-option";
	const EMAIL_NAME_OPTION = "ihf-email-name-option";
	const EMAIL_COMPANY_OPTION = "ihf-email-company-option";
	const EMAIL_ADDRESS_LINE1_OPTION = "ihf-email-address-line1-option";
	const EMAIL_ADDRESS_LINE2_OPTION = "ihf-email-address-line2-option";
	const EMAIL_PHONE_OPTION = "ihf-email-phone-option";
	const EMAIL_DISPLAY_TYPE_OPTION = "ihf-email-display-type-option";
	
	/*
	 * community pages options
	 */
	const OPTION_GROUP_COMMUNITY_PAGES = "ihf-community-pages";
	
	/*
	 * SEO city links options
	 */
	const OPTION_GROUP_SEO_CITY_LINKS = "ihf-option-seo-city-links";
	const SEO_CITY_LINKS_SETTINGS = "ihf-seo-city-links-settings";
	const SEO_CITY_LINKS_CITY_ZIP = "ihf-seo-city-links-city-zip";
	const SEO_CITY_LINKS_TEXT = "ihf-seo-city-links-text";
	const SEO_CITY_LINKS_MIN_PRICE = "ihf-seo-city-links-min-price";
	const SEO_CITY_LINKS_MAX_PRICE = "ihf-seo-city-links-max-price";
	const SEO_CITY_LINKS_PROPERTY_TYPE = "ihf-seo-city-links-property-type";
	const SEO_CITY_LINK_WIDTH = "ihf-seo-city-link-width";
	
	/*
	 * compatibility check options
	 */
	const OPTION_GROUP_COMPATIBILITY_CHECK = "ihf-option-compatibility-check";
	const COMPATIBILITY_CHECK_ENABLED = "ihf-compatibility-check-enabled";
	
	//
	const OPTION_MOBILE_SITE_YN = "ihf-mobile-site-yn";
	
	//
	const VERSION_OPTION = "ihf_version_option";

	//Remember if this plugin has ever been activated on this site. This affects things like link creation, when the plugin is activated.
	const IS_ACTIVATED_OPTION = "ihf_links_created";
	const CSS_OVERRIDE_MIGRATED = "ihf_css_override_migrated";

	//Used throughout the application to discover iHomefinder requests and used to determine the proper filter to execute.
	const IHF_TYPE_URL_VAR = "ihf-type";
	
	/**
	 * 
	 */
	const DATABASE_CACHE_TEST = "ihf_database_cache_test";
	
	const DEBUG = false;
	
}
