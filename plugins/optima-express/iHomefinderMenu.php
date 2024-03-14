<?php

class iHomefinderMenu {
	
	private static $instance;
	
	private $communityPagesMenuItemName = "Communities";
	private $defaultMenuName = "Optima Express";
	
	private function __construct() {
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}		
	
	public function getMenu() {
		$menuName = $this->defaultMenuName;
		$menu = wp_get_nav_menu_object($menuName);
		return $menu;
	}
	
	public function getMenuId() {
		$menuName = $this->defaultMenuName;
		$menu = wp_get_nav_menu_object($menuName);
		return $menu->term_id;
	}		
	
	/**
	 * Creates or retrieves the Optima Express menu. If the menu has
	 * not been created, it creates the menu with default values.
	 * 
	 * If the menu already exists, then we do not populate with the default urls,
	 * because the end user may have customized this menu.
	 * 
	 * @return Optima Express menu
	 */		
	public function updateMenu() {
		$menu = $this->getMenu();
		if(!$menu) {
			$menuName = $this->defaultMenuName;
			$menuArgs = array(
				"description" => $menuName . " default menu",
				"menu-name" => $menuName
			);
			$menuId = wp_update_nav_menu_object(0, $menuArgs);
			$menu = wp_get_nav_menu_object($menuId);
			$this->addMenuItems($menu->term_id);
		}
		return $menu;
	}
	
	/**
	 * 
	 * Get the menuItem object for Community Pages
	 * 
	 * This is a container menu item that is the parent for
	 * all Community Pages menu items
	 */
	private function getCommunityPagesContainer() {
		$menu = $this->getMenu();
		$args = array(
			"title" => $this->communityPagesMenuItemName
		);
		$menuItems = wp_get_nav_menu_items($menu->term_id, $args);
		foreach($menuItems as $oneMenuItem) {
			if($oneMenuItem->title == $this->communityPagesMenuItemName) {
				return $oneMenuItem;
			}
		}
		return false;
	}
	
	public function addCommunityPageMenuItem() {
		$menu = $this->getMenu();
		$communityPagesMenuItemId = $this->addMenuItem($menu->term_id, $this->communityPagesMenuItemName, "");
		return $communityPagesMenuItemId;
	}
	
	public function addPageToCommunityPages($postId) {
		 $menuID = iHomefinderMenu::getInstance()->getMenuId();
		 $communityPagesMenuItem = $this->getCommunityPagesContainer();
		 $communityPagesMenuItemId = $communityPagesMenuItem->ID;
		 $itemData = array(
			"menu-item-object-id" => $postId,
			"menu-item-parent-id" => $communityPagesMenuItemId,
			"menu-item-position" => 2,
			"menu-item-object" => "page",
			"menu-item-type" => "post_type",
			"menu-item-status" => "publish"
		 );
		 wp_update_nav_menu_item($menuID, 0, $itemData);
	}
	
	/**
	 * Returns an array of menu items that are children of
	 * the Community Pages menu item.
	 * 
	 * Used in admin to display a list of Community Pages.
	 */
	public function getCommunityPagesMenuItems() {
		$communityPages = array();
		$menu = $this->getMenu();
		$communityPagesMenuItem = $this->getCommunityPagesContainer();
		$communityPagesMenuItemId = $communityPagesMenuItem->ID;
		$menu_items = (array) wp_get_nav_menu_items($menu->term_id);
		foreach ($menu_items as $key => $menu_item) {
			if($menu_item->menu_item_parent == $communityPagesMenuItemId) {
				$communityPages[] = $menu_item;
			}
		}
		return $communityPages;
	}
	
	
	private function addMenuItems($menuId) {
		
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$displayRules = iHomefinderDisplayRules::getInstance();
		
		$homeMenuItemId = $this->addMenuItem($menuId, "Home", $urlFactory->getBaseUrl());
		
		if($displayRules->isFeaturedPropertiesEnabled()) {
			$featuredMenuItemId = $this->addMenuItem($menuId, "Featured Listings", $urlFactory->getFeaturedSearchResultsUrl(true));
		}
		
		$findHomeMenuItemId = $this->addMenuItem($menuId, "Property Search");
		
		if($displayRules->isBasicSearchEnabled()) {
			$searchMenuItemId = $this->addMenuItem($menuId, "Search", $urlFactory->getListingsSearchFormUrl(true), $findHomeMenuItemId);
		}
		
		if($displayRules->isMapSearchEnabled()) {
			$mapSearchMenuItemId = $this->addMenuItem($menuId, "Map Search", $urlFactory->getMapSearchFormUrl(true), $findHomeMenuItemId);
		}
		
		if($displayRules->isOpenHomeSearchEnabled()) {
			$openHomesMenuItemId = $this->addMenuItem($menuId, "Open Homes", $urlFactory->getOpenHomeSearchFormUrl(true), $findHomeMenuItemId);
		}
		
		if($displayRules->isAdvancedSearchEnabled()) {
			$advancedSearchMenuItemId = $this->addMenuItem($menuId, "Advanced Search", $urlFactory->getListingsAdvancedSearchFormUrl(true), $findHomeMenuItemId);
		}
		
		if($displayRules->isSaveSearchEnabled()) {
			if($displayRules->isOfficeEnabled()) {
				$mapSearchMenuItemId = $this->addMenuItem($menuId, "Email Alerts", $urlFactory->getOrganizerEditSavedSearchUrl(true), $findHomeMenuItemId);
			} else {
				$advancedSearchMenuItemId = $this->addMenuItem($menuId, "Email Alerts", $urlFactory->getOrganizerEditSavedSearchUrl(true));
			}
		}
		
		if($displayRules->isCommunityPagesEnabled()) {		
			$communityPagesMenuItemId = $this->addMenuItem($menuId, $this->communityPagesMenuItemName);
		}
		
		if($displayRules->isOrganizerEnabled() || $displayRules->isValuationEnabled()) {
			
			$buyersAndSellersMenuItemId = $this->addMenuItem($menuId, "Buyers & Sellers");
			
			if($displayRules->isOrganizerEnabled()) {
				$valuationMenuItemId = $this->addMenuItem($menuId, "Property Organizer", $urlFactory->getOrganizerLoginUrl(true), $buyersAndSellersMenuItemId);
			}
			
			if($displayRules->isValuationEnabled()) {
				$valuationMenuItemId = $this->addMenuItem($menuId, "Valuation Request", $urlFactory->getValuationFormUrl(true), $buyersAndSellersMenuItemId);
			}
			
		}
		
		if($displayRules->isContactFormEnabled()) {
			$contactMenuItemId = $this->addMenuItem($menuId, "Contact", $urlFactory->getContactFormUrl(true));
		}
		
		if($displayRules->isOfficeEnabled()) {
			$officeListMenuItemId = $this->addMenuItem($menuId, "Our Team", $urlFactory->getOfficeListUrl(true));
		}		

		$mortgageCalculatorMenuItemId = $this->addMenuItem($menuId, "Mortgage Calculator", $urlFactory->getMortgageCalculatorUrl(true));
	}

	private function addMenuItem($menuId, $name, $url = null, $parentId = 0) {
		//We build relative URLs that start with a slash.
		if($url === null) {
			$url = "#";
		} else {
			$url = $this->makeRelativeUrl($url);
		}
		$menuItem = $this->buildMenuItem($name, $url, $parentId);
		$menuItemId = wp_update_nav_menu_item($menuId, 0, $menuItem);
		return $menuItemId;
	}

	private function buildMenuItem($name, $url, $parentId = 0) {
		$menuItem = array(
			"menu-item-parent-id" => $parentId,
			"menu-item-type" => "custom",
			"menu-item-title" => $name,
			"menu-item-url" => $url,
			"menu-item-attr-title" => $name,
			"menu-item-description" => $name,
			"menu-item-status" => "publish"
		);
		return $menuItem;
	}
	
	private function makeRelativeUrl($url) {
		$urlParts = parse_url($url);
		$result = null;
		if(array_key_exists("path", $urlParts)) {
			$result = $urlParts["path"];
		}
		$query = null;
		if(array_key_exists("query", $urlParts)) {
			$query = $urlParts["query"];
		}
		if($query !== null) {
			$result .= "?" . $query;
		}
		if($result === null || $result === "") {
			$result = "/";
		}
		return $result;
	}
}