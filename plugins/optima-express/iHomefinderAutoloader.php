<?php

/**
 * autoloads iHomefinder classes
 */
class iHomefinderAutoloader {
	
	private static $instance;
	
	/*
	 * we store an array indexed by class name of class paths instead of using a PSR-4 autoloader
	 * because we want to support versions of PHP that don't support namespacing
	 */
	private $classes = array(
		//core files
		"iHomefinderAdmin" => "iHomefinderAdmin.php",
		"iHomefinderAjaxHandler" => "iHomefinderAjaxHandler.php",
		"iHomefinderConstants" => "iHomefinderConstants.php",
		"iHomefinderInstaller" => "iHomefinderInstaller.php",
		"iHomefinderListingInfo" => "iHomefinderListingInfo.php",
		"iHomefinderLogger" => "iHomefinderLogger.php",
		"iHomefinderMenu" => "iHomefinderMenu.php",
		"iHomefinderEnqueueResource" => "iHomefinderEnqueueResource.php",
		"iHomefinderRequestor" => "iHomefinderRequestor.php",
		"iHomefinderRemoteResponse" => "iHomefinderRemoteResponse.php",
		"iHomefinderRewriteRules" => "iHomefinderRewriteRules.php",
		"iHomefinderSearchLinkInfo" => "iHomefinderSearchLinkInfo.php",
		"iHomefinderFormData" => "iHomefinderFormData.php",
		"iHomefinderShortcodeSelector" => "iHomefinderShortcodeSelector.php",
		"iHomefinderShortcodeSelectorTinyMce" => "iHomefinderShortcodeSelectorTinyMce.php",
		"iHomefinderShortcodeDispatcher" => "iHomefinderShortcodeDispatcher.php",
		"iHomefinderStateManager" => "iHomefinderStateManager.php",
		"iHomefinderUrlFactory" => "iHomefinderUrlFactory.php",
		"iHomefinderUtility" => "iHomefinderUtility.php",
		"iHomefinderVirtualPageDispatcher" => "iHomefinderVirtualPageDispatcher.php",
		"iHomefinderVirtualPageFactory" => "iHomefinderVirtualPageFactory.php",
		"iHomefinderDisplayRules" => "iHomefinderDisplayRules.php",
		"iHomefinderCacheUtility" => "iHomefinderCacheUtility.php",
		"iHomefinderVariable" => "iHomefinderVariable.php",
		"iHomefinderVariableUtility" => "iHomefinderVariableUtility.php",
		"iHomefinderKestrelShortcode" => "iHomefinderKestrelShortcode.php",	
		//widgets
		"iHomefinderKestrelWidget" => "iHomefinderKestrelWidget.php",
		"iHomefinderWidget" => "widget/iHomefinderWidget.php",
		"iHomefinderPropertiesGallery" => "widget/iHomefinderPropertiesGallery.php",
		"iHomefinderQuickSearchWidget" => "widget/iHomefinderQuickSearchWidget.php",
		"iHomefinderLinkWidget" => "widget/iHomefinderLinkWidget.php",
		"iHomefinderSearchByAddressWidget" => "widget/iHomefinderSearchByAddressWidget.php",
		"iHomefinderSearchByListingIdWidget" => "widget/iHomefinderSearchByListingIdWidget.php",
		"iHomefinderContactFormWidget" => "widget/iHomefinderContactFormWidget.php",
		"iHomefinderLoginWidget" => "widget/iHomefinderLoginWidget.php",
		"iHomefinderMoreInfoWidget" => "widget/iHomefinderMoreInfoWidget.php",
		"iHomefinderValuationWidget" => "widget/iHomefinderValuationWidget.php",
		"iHomefinderAgentBioWidget" => "widget/iHomefinderAgentBioWidget.php",
		"iHomefinderSocialWidget" => "widget/iHomefinderSocialWidget.php",
		"iHomefinderHotsheetListWidget" => "widget/iHomefinderHotsheetListWidget.php",
		"iHomefinderEmailSignupFormWidget" => "widget/iHomefinderEmailSignupFormWidget.php",
		//virtual pages
		"iHomefinderKestrelPage" => "iHomefinderKestrelPage.php",
		"iHomefinderVirtualPageInterface" => "virtualPage/iHomefinderVirtualPageInterface.php",
		"iHomefinderAbstractVirtualPage" => "virtualPage/iHomefinderAbstractVirtualPage.php",
		"iHomefinderDefaultVirtualPageImpl" => "virtualPage/iHomefinderDefaultVirtualPageImpl.php",
		"iHomefinderFeaturedSearchVirtualPageImpl" => "virtualPage/iHomefinderFeaturedSearchVirtualPageImpl.php",
		"iHomefinderHotSheetListingReportVirtualPageImpl" => "virtualPage/iHomefinderHotSheetListingReportVirtualPageImpl.php",
		"iHomefinderHotsheetOpenHomeReportVirtualPageImpl" => "virtualPage/iHomefinderHotsheetOpenHomeReportVirtualPageImpl.php",
		"iHomefinderHotsheetMarketReportVirtualPageImpl" => "virtualPage/iHomefinderHotsheetMarketReportVirtualPageImpl.php",
		"iHomefinderHotsheetListVirtualPageImpl" => "virtualPage/iHomefinderHotsheetListVirtualPageImpl.php",
		"iHomefinderAdvancedSearchFormVirtualPageImpl" => "virtualPage/iHomefinderAdvancedSearchFormVirtualPageImpl.php",
		"iHomefinderSearchFormVirtualPageImpl" => "virtualPage/iHomefinderSearchFormVirtualPageImpl.php",
		"iHomefinderMapSearchVirtualPageImpl" => "virtualPage/iHomefinderMapSearchVirtualPageImpl.php",
		"iHomefinderSearchResultsVirtualPageImpl" => "virtualPage/iHomefinderSearchResultsVirtualPageImpl.php",
		"iHomefinderListingDetailVirtualPageImpl" => "virtualPage/iHomefinderListingDetailVirtualPageImpl.php",
		"iHomefinderListingSoldDetailVirtualPageImpl" => "virtualPage/iHomefinderListingSoldDetailVirtualPageImpl.php",
		"iHomefinderOrganizerLoginFormVirtualPageImpl" => "virtualPage/iHomefinderOrganizerLoginFormVirtualPageImpl.php",
		"iHomefinderOrganizerLogoutVirtualPageImpl" => "virtualPage/iHomefinderOrganizerLogoutVirtualPageImpl.php",
		"iHomefinderOrganizerEditSavedSearchVirtualPageImpl" => "virtualPage/iHomefinderOrganizerEditSavedSearchVirtualPageImpl.php",
		"iHomefinderOrganizerEditSavedSearchFormVirtualPageImpl" => "virtualPage/iHomefinderOrganizerEditSavedSearchFormVirtualPageImpl.php",
		"iHomefinderOrganizerEmailUpdatesConfirmationVirtualPageImpl" => "virtualPage/iHomefinderOrganizerEmailUpdatesConfirmationVirtualPageImpl.php",
		"iHomefinderOrganizerDeleteSavedSearchVirtualPageImpl" => "virtualPage/iHomefinderOrganizerDeleteSavedSearchVirtualPageImpl.php",
		"iHomefinderOrganizerViewSavedSearchVirtualPageImpl" => "virtualPage/iHomefinderOrganizerViewSavedSearchVirtualPageImpl.php",
		"iHomefinderOrganizerViewSavedSearchListVirtualPageImpl" => "virtualPage/iHomefinderOrganizerViewSavedSearchListVirtualPageImpl.php",
		"iHomefinderOrganizerViewSavedListingListVirtualPageImpl" => "virtualPage/iHomefinderOrganizerViewSavedListingListVirtualPageImpl.php",
		"iHomefinderOrganizerDeleteSavedListingVirtualPageImpl" => "virtualPage/iHomefinderOrganizerDeleteSavedListingVirtualPageImpl.php",
		"iHomefinderOrganizerResendConfirmationVirtualPageImpl" => "virtualPage/iHomefinderOrganizerResendConfirmationVirtualPageImpl.php",
		"iHomefinderOrganizerActivateSubscriberVirtualPageImpl" => "virtualPage/iHomefinderOrganizerActivateSubscriberVirtualPageImpl.php",
		"iHomefinderOrganizerSendSubscriberPasswordVirtualPageImpl" => "virtualPage/iHomefinderOrganizerSendSubscriberPasswordVirtualPageImpl.php",
		"iHomefinderOrganizerHelpVirtualPageImpl" => "virtualPage/iHomefinderOrganizerHelpVirtualPageImpl.php",
		"iHomefinderOrganizerEditSubscriberVirtualPageImpl" => "virtualPage/iHomefinderOrganizerEditSubscriberVirtualPageImpl.php",
		"iHomefinderContactFormVirtualPageImpl" => "virtualPage/iHomefinderContactFormVirtualPageImpl.php",
		"iHomefinderValuationFormVirtualPageImpl" => "virtualPage/iHomefinderValuationFormVirtualPageImpl.php",
		"iHomefinderMortgageCalculatorVirtualPageImpl" => "virtualPage/iHomefinderMortgageCalculatorVirtualPageImpl.php",
		"iHomefinderOpenHomeSearchFormVirtualPageImpl" => "virtualPage/iHomefinderOpenHomeSearchFormVirtualPageImpl.php",
		"iHomefinderSoldFeaturedListingVirtualPageImpl" => "virtualPage/iHomefinderSoldFeaturedListingVirtualPageImpl.php",
		"iHomefinderPendingFeaturedListingVirtualPageImpl" => "virtualPage/iHomefinderPendingFeaturedListingVirtualPageImpl.php",
		"iHomefinderSupplementalListingVirtualPageImpl" => "virtualPage/iHomefinderSupplementalListingVirtualPageImpl.php",
		"iHomefinderOfficeListVirtualPageImpl" => "virtualPage/iHomefinderOfficeListVirtualPageImpl.php",
		"iHomefinderOfficeDetailVirtualPageImpl" => "virtualPage/iHomefinderOfficeDetailVirtualPageImpl.php",
		"iHomefinderAgentListVirtualPageImpl" => "virtualPage/iHomefinderAgentListVirtualPageImpl.php",
		"iHomefinderAgentDetailVirtualPageImpl" => "virtualPage/iHomefinderAgentDetailVirtualPageImpl.php",
		"iHomefinderAbstractPropertyOrganizerVirtualPage" => "virtualPage/iHomefinderAbstractPropertyOrganizerVirtualPage.php",
		//mls portal pages
		"iHomefinderMlsPortalBoardOfficeSearchVirtualPageImpl" => "virtualPage/iHomefinderMlsPortalBoardOfficeSearchVirtualPageImpl.php",
		"iHomefinderMlsPortalBoardOfficeListVirtualPageImpl" => "virtualPage/iHomefinderMlsPortalBoardOfficeListVirtualPageImpl.php",
		"iHomefinderMlsPortalBoardOfficeListNameStartsWithVirtualPageImpl" => "virtualPage/iHomefinderMlsPortalBoardOfficeListNameStartsWithVirtualPageImpl.php",
		"iHomefinderMlsPortalBoardOfficeDetailVirtualPageImpl" => "virtualPage/iHomefinderMlsPortalBoardOfficeDetailVirtualPageImpl.php",
		"iHomefinderMlsPortalBoardMemberSearchVirtualPageImpl" => "virtualPage/iHomefinderMlsPortalBoardMemberSearchVirtualPageImpl.php",
		"iHomefinderMlsPortalBoardMemberListVirtualPageImpl" => "virtualPage/iHomefinderMlsPortalBoardMemberListVirtualPageImpl.php",
		"iHomefinderMlsPortalBoardMemberListLastNameStartsWithVirtualPageImpl" => "virtualPage/iHomefinderMlsPortalBoardMemberListLastNameStartsWithVirtualPageImpl.php",
		"iHomefinderMlsPortalBoardMemberDetailVirtualPageImpl" => "virtualPage/iHomefinderMlsPortalBoardMemberDetailVirtualPageImpl.php",
		//admin pages
		"iHomefinderAdminAbstractPage" => "adminPage/iHomefinderAdminAbstractPage.php",
		"iHomefinderAdminPageInterface" => "adminPage/iHomefinderAdminPageInterface.php",
		"iHomefinderAdminInformation" => "adminPage/iHomefinderAdminInformation.php",
		"iHomefinderAdminShortcodes" => "adminPage/iHomefinderAdminShortcodes.php",
		"iHomefinderAdminActivate" => "adminPage/iHomefinderAdminActivate.php",
		"iHomefinderAdminControlPanel" => "adminPage/iHomefinderAdminControlPanel.php",
		"iHomefinderAdminPageConfig" => "adminPage/iHomefinderAdminPageConfig.php",
		"iHomefinderAdminConfiguration" => "adminPage/iHomefinderAdminConfiguration.php",
		"iHomefinderAdminBio" => "adminPage/iHomefinderAdminBio.php",
		"iHomefinderAdminSocial" => "adminPage/iHomefinderAdminSocial.php",
		"iHomefinderAdminEmail" => "adminPage/iHomefinderAdminEmail.php",
		"iHomefinderAdminCommunityPages" => "adminPage/iHomefinderAdminCommunityPages.php",
		"iHomefinderAdminSeoCityLinks" => "adminPage/iHomefinderAdminSeoCityLinks.php"
	);
	
	private function __construct() {
		spl_autoload_register(array($this, "load"));
	}
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function load($className) {
		if(array_key_exists($className, $this->classes)) {
			include $this->classes[$className];
		}
	}
	
}