<?php
/**
 * @package Unite Gallery
 * @author Valiano
 * @copyright (C) 2022 Unite Gallery, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNITEGALLERY_INC') or die('Restricted access');


	class GlobalsUG{
		
		const SHOW_TRACE = false;
		
		const ENABLE_TRANSLATIONS = false;
		
		const PLUGIN_TITLE = "Unite Gallery";
		const PLUGIN_NAME = "unitegallery";
		
		
		const TABLE_GALLERIES_NAME = "unitegallery_galleries";
		const TABLE_ITEMS_NAME = "unitegallery_items";
		const TABLE_CATEGORIES_NAME = "unitegallery_categories";
				
		const DIR_THUMBS = "unitegallery_thumbs";
		const THUMB_WIDTH = 300;
		const THUMB_WIDTH_LARGE = 768;
		const THUMB_WIDTH_BIG = 1024;
		
		const VIEW_DEFAULT = "galleries";
		const VIEW_ITEMS = "items";
		const VIEW_GALLERIES = "galleries";
		const VIEW_GALLERY = "gallery";
        const VIEW_CATEGORY_TABS = "categorytabs";
        const VIEW_ADVANCED = "advanced";
		const VIEW_PREVIEW = "preview";
		const VIEW_MEDIA_SELECT = "mediaselect";
		
		const FIELDS_ITEMS = "published,title,alias,type,ordering,catid,imageid,url_image,url_thumb,contentid,content,params";
		const FIELDS_GALLERY = "type,title,alias,ordering,params";
		const DEFAULT_JPG_QUALITY = 81;
		
		const YOUTUBE_EXAMPLE_ID = "A3PDXmYoF5U";
		const VIMEO_EXAMPLE_ID = "73234449";
		const WISTIA_EXAMPLE_ID = "9oedgxuciv";
		
		public static $inDev = false;
		
		public static $isScriptsInFooter = false;	//tells that scripts are added to footer
		
		public static $table_galleries;
		public static $table_categories;
		public static $table_items;
		public static $table_posts;
		
		public static $pathSettings;
		public static $filepathItemSettings;
		public static $pathPlugin;
		public static $pathGalleries;
		public static $pathTemplates;
		public static $pathFullVersion;
		public static $pathViews;
		public static $pathHelpersViews;
		public static $pathHelpersTemplates;
		public static $pathHelpersClasses;
		public static $pathHelpersSettings;
		public static $pathProvider;
		public static $isLocal;
		
		public static $url_base;
		public static $url_media_ug;
		public static $url_images;
		public static $url_component_client;
		public static $url_component_admin;
		public static $url_ajax;
		public static $url_ajax_front;
		public static $urlPlugin;
		public static $urlGalleries;
		public static $url_elfinder;
		public static $url_provider;
		
		public static $is_admin;
		public static $isFullVersion;
		public static $path_base;
		public static $path_media_ug;		
		public static $path_cache;
		public static $path_images;
		public static $path_elfinder;
		public static $debugOutput = false;
		
		public static $urlNoImage = "";
		
		public static $arrClientSideText = array();
		
		public static $arrFilterPostTypes = array(		//filter post types that will not show
					"elementor_library", 
					"unelements_library", 
					"wpcf7_contact_form",
					"_pods_pod",
					"_pods_field",
					"_pods_template",
					"wp-types-group",
					"wp-types-user-group",
					"wp-types-term-group",
					"elementor_font",
					"elementor_icons"
		);
		
		public static $lastPostsQuery = null;
		public static $lastDebug = null;
		
		
		/**
		 * init globals
		 */
		public static function initGlobals($pluginFolder){
			
			if(defined("UC_DEVMODE"))
				self::$inDev = true;
			
			UniteProviderFunctionsUG::initGlobalsBase($pluginFolder);
			
			self::$is_admin == false;	//this var set from admin object
			
			self::$pathSettings = self::$pathPlugin."settings/";
			self::$pathGalleries = self::$pathPlugin."galleries/";
			self::$path_elfinder = self::$pathPlugin."libraries/elfinder/";
			self::$pathTemplates = self::$pathPlugin."views/templates/";
			self::$pathViews = self::$pathPlugin."views/";
			self::$pathHelpersViews = self::$pathPlugin."helpers/views/";
			self::$pathHelpersTemplates = self::$pathPlugin."helpers/templates/";
			self::$pathHelpersClasses = self::$pathPlugin."helpers/classes/";
			self::$pathHelpersSettings = self::$pathPlugin."helpers/settings/";
			self::$pathProvider = self::$pathPlugin."inc_php/framework/provider/";
			self::$pathFullVersion = self::$pathPlugin."fullversion/";

			self::$isFullVersion = is_dir(self::$pathFullVersion);
			
			UniteFunctionsUG::validateDir(self::$pathGalleries);
			
			self::$filepathItemSettings = self::$pathSettings."item_settings.php";

			self::$urlGalleries = self::$urlPlugin."galleries/";
			self::$url_elfinder = self::$urlPlugin."libraries/elfinder/";
			self::$url_provider = self::$urlPlugin."inc_php/framework/provider/";
			
			self::$isLocal = UniteProviderFunctionsUG::isLocal();
			
			self::$urlNoImage = self::$urlPlugin."images/noimage.png";
			
			
			self::initClientSideText();
		}

		
		/**
		 * init client side text for globals
		 */
		public static function initClientSideText(){
		
			self::$arrClientSideText = array(
					"please_fill_item_title"=>__("Please fill in item title","unitegallery"),
					"updating_item_data"=>__("Updating item data...","unitegallery"),
					"loading_item_data"=>__("Loading item data...","unitegallery"),
					"edit_item"=>__("Edit Item","unitegallery"),
					"edit_media_item"=>__("Edit Media Item","unitegallery"),
					"add_image"=>__("Add image (use shift or ctrl for choosing multiple images)","unitegallery"),
					"adding_category"=>__("Adding Category...","unitegallery"),
					"do_you_sure_remove"=>__("Do you sure to remove this category and it's items?","unitegallery"),
					"removing_category"=>__("Removing Category...","unitegallery"),
					"cancel"=>__("Cancel","unitegallery"),
					"update"=>__("Update","unitegallery"),
					"import"=>__("Import","unitegallery"),
					"restore"=>__("Restore","unitegallery"),
					"updating"=>__("Updating...","unitegallery"),
					"restoring"=>__("Restoring...","unitegallery"),
					"updating_category"=>__("Updating Category...","unitegallery"),
					"loading_preview"=>__("Loading Preview...","unitegallery"),
					"adding_item"=>__("Adding Item...","unitegallery"),
					"updating_categories_order"=>__("Updating Categories Order...","unitegallery"),
					"removing_items"=>__("Removing Items...","unitegallery"),
					"updating_title"=>__("Updating Title...","unitegallery"),
					"duplicating_items"=>__("Duplicating Items...","unitegallery"),
					"updating_items_order"=>__("Updating Items Order...","unitegallery"),
					"copying_items"=>__("Copying Items...","unitegallery"),
					"moving_items"=>__("Moving Items...","unitegallery"),
					"confirm_remove_items"=>__("Are you sure you want to delete these items?","unitegallery"),
					"confirm_remove_gallery"=>__("Are you sure you want to delete this gallery?","unitegallery")
			);
		
		}

		/**
		 * print all globals variables
		 */
		public static function printVars(){
			$methods = get_class_vars( "GlobalsUG" );
			dmp($methods);
			exit();
		}
	}

	//init the globals
	GlobalsUG::initGlobals($currentFolder);
	
?>
