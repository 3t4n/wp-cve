<?php
/**
 * @package Unite Gallery
 * @author Valiano
 * @copyright (C) 2022 Unite Gallery, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNITEGALLERY_INC') or die('Restricted access');


	class UniteGalleryAdmin extends UniteBaseAdminClassUG{
		
		const DEFAULT_VIEW = "galleries";

		public static $currentGalleryType;
		
		
		/**
		 * 
		 * the constructor
		 */
		public function __construct(){
			
			parent::__construct();

		}
		
		/**
		 * init the gallery framework by type name
		 */
		protected static function initGalleryFramework($galleryTypeName, $galleryID=""){
			
			$objGallery = "";
			if(!empty($galleryID)){
				$objGallery = new UniteGalleryGallery();
				$objGallery->initByID($galleryID);
				$galleryTypeName = $objGallery->getTypeName();
			}			
			
			UniteFunctionsUG::validateNotEmpty($galleryTypeName,"Gallery Type Name");
			
			$galleries = new UniteGalleryGalleries();
			
			self::$currentGalleryType = new UniteGalleryGalleryType();
			self::$currentGalleryType = $galleries->getGalleryTypeByName($galleryTypeName);
			
			GlobalsUGGallery::init(self::$currentGalleryType, $objGallery, $galleryID);
		}
		
		
		/**
		 * 
		 * init current gallery
		 * for gallery view only
		 */
		protected function initCurrentGallery(){
			
			switch(self::$view){
				case GlobalsUG::VIEW_GALLERY:
				case GlobalsUG::VIEW_PREVIEW:
				case GlobalsUG::VIEW_CATEGORY_TABS:
				case GlobalsUG::VIEW_ADVANCED:
					$galleryID = UniteFunctionsUG::getPostGetVariable("id","");
					
					UniteFunctionsUG::validateVar($galleryID, "gallery id", UniteFunctionsUG::VALIDATE_NUMERIC_OR_EMPTY);
				break;
				case GlobalsUG::VIEW_ITEMS:
					$galleryID = UniteFunctionsUG::getPostGetVariable("galleryid", "");
					UniteFunctionsUG::validateVar($galleryID, "gallery id", UniteFunctionsUG::VALIDATE_NUMERIC_OR_EMPTY);
					
					if(empty($galleryID))
						return(false);
				break;
				default:
					return(false);
				break;
			}
						
			$objGallery = "";
			if(!empty($galleryID)){
				$objGallery = new UniteGalleryGallery();
				$objGallery->initByID($galleryID);
				$galleryTypeName = $objGallery->getTypeName();
			}else{
				$galleryTypeName = UniteFunctionsUG::getPostGetVariable("type");				
			}
			
			self::initGalleryFramework($galleryTypeName, $galleryID);
		}

		
		/**
		 * 
		 * validate that current gallery inited
		 */
		protected static function validateCurrentGalleryInited(){
			if(empty(self::$currentGalleryType))
				UniteFunctionsUG::throwError("Curent galery don't inited");
		}
		
		
		/**
		 * 
		 * init all actions
		 */
		public function init(){
			
			GlobalsUG::$is_admin = true;
			
			$this->initCurrentGallery();
			
		}
		
		
		/**
		 * add provider scripts
		 */
		public static function addProviderScripts(){
			HelperUG::addStyleAbsoluteUrl(GlobalsUG::$url_provider."assets/provider_admin.css", "provider_admin_css");
			HelperUG::addScriptAbsoluteUrl(GlobalsUG::$url_provider."assets/provider_admin.js", "provider_admin_js");
		}

		
		/**
		 * add scripts to normal pages
		 */
		public static function addScriptsNormal(){
			
			parent::addCommonScripts();
			
			HelperUG::addScript("unitegallery_admin");
			UniteGalleryManager::putScriptsIncludes(UniteGalleryManager::TYPE_MAIN);
			HelperUG::addStyle("unitegallery_styles","unitegallery_css","css");
						
			if(!empty(self::$currentGalleryType)){
				$pathGalleryScripts = self::$currentGalleryType->getPathScriptsIncludes();
				if(file_exists($pathGalleryScripts))
					require $pathGalleryScripts;
			}
			
			//provider admin always comes to end
			self::addProviderScripts();
		}

		
		/**
		 * add manager only scripts for outside pages
		 */
		public static function addScriptsManagerOnly(){
			parent::addCommonScripts();
			UniteGalleryManager::putScriptsIncludes(UniteGalleryManager::TYPE_MAIN);
			
			//provider admin always comes to end
			self::addProviderScripts();
		}
						
		
		/**
		 * 
		 * a must function. adds scripts on the page
		 * add all page scripts and styles here.
		 * pelase don't remove this function
		 * common scripts even if the plugin not load, use this function only if no choise.
		 */
		public static function onAddScripts(){
			
			if(self::$view != GlobalsUG::VIEW_MEDIA_SELECT)
				self::addScriptsNormal();	
					
		}
		
		
		/**
		 * 
		 * admin main page function.
		 */
		public static function adminPages(){
							
			if(self::$view != GlobalsUG::VIEW_MEDIA_SELECT)
				self::setMasterView("master_view");

			self::requireView(self::$view);
			
		}
		
		
		/**
		 * call gallery action, include gallery framework first
		 */
		public static function onGalleryAjaxAction($typeName, $action, $data, $galleryID){
			
			if(empty($data))
				$data = array();
			
			self::initGalleryFramework($typeName, $galleryID);
			
			$filepathAjax = GlobalsUGGallery::$pathBase."ajax_actions.php";
			UniteFunctionsUG::validateFilepath($filepathAjax, "Ajax request error: ");
			
			require $filepathAjax;
			
			UniteFunctionsUG::throwError("No ajax response from gallery: <b>{$typeName} </b> to action <b>{$action}</b>");
		}
		
		
		/**
		 * 
		 * onAjax action handler
		 */
		public static function onAjaxAction(){
			
			$actionType = UniteFunctionsUG::getPostGetVariable("action");
			
			if($actionType != "unitegallery_ajax_action")
				return(false);
			
			
			if(GlobalsUG::$isLocal == true){
				ini_set("display_errors", 1);
				error_reporting(E_ALL);
			}
			
			$gallery = new UniteGalleryGallery();
			$galleries = new UniteGalleryGalleries();
			$categories = new UniteGalleryCategories();
			$items = new UniteGalleryItems();
			
			$operations = new UGOperations();

			$action = UniteFunctionsUG::getPostGetVariable("client_action"); 
						
			$data = UniteFunctionsUG::getPostVariable("data", "", UniteFunctionsUG::SANITIZE_NOTHING); 
						
			if(empty($data))
				$data = $_REQUEST;
			
			if(is_string($data)){
				$arrData = (array)json_decode($data);
				
				if(empty($arrData)){
					$arrData = stripslashes(trim($data));
					$arrData = (array)json_decode($arrData);
				}
				
				$data = $arrData;
			}
						
			$data = UniteProviderFunctionsUG::normalizeAjaxInputData($data);
			
			$galleryType = UniteFunctionsUG::getPostVariable("gallery_type");
			
			$urlGalleriesView = HelperUG::getGalleriesView();
			
			try{
				
				switch($action){
					case "gallery_actions":
						$galleryID = UniteFunctionsUG::getVal($data, "galleryID");
						$galleryAction = UniteFunctionsUG::getVal($data, "gallery_action");
						$galleryData = UniteFunctionsUG::getVal($data, "gallery_data", array());
						self::onGalleryAjaxAction($galleryType, $galleryAction, $galleryData, $galleryID);
					break;
					case "get_thumb_url":
												
						$urlImage = UniteFunctionsUG::getVal($data, "urlImage");
						$imageID = UniteFunctionsUG::getVal($data, "imageID");
						
						$urlThumb = $operations->getThumbURLFromImageUrl($urlImage, $imageID);
						$arrData = array("urlThumb"=>$urlThumb);
						HelperUG::ajaxResponseData($arrData);
					break;
					case "add_category":
						$catData = $categories->addFromData();
						HelperUG::ajaxResponseData($catData);
					break;
					case "remove_category":
						$response = $categories->removeFromData($data);
						
						HelperUG::ajaxResponseSuccess(__("The category deleted successfully.","unitegallery"),$response);
					break;
					case "update_category":
						
						$categories->updateFromData($data);
						HelperUG::ajaxResponseSuccess(__("Category updated.","unitegallery"));
					break;
					case "update_cat_order":
						$categories->updateOrderFromData($data);
						HelperUG::ajaxResponseSuccess(__("Order updated.","unitegallery"));
					break;
					case "change_cat_sortby":
						$response = $categories->changeCatSortbyFromData($data);
						HelperUG::ajaxResponseData($response);
					break;
					case "update_cat_params":
						
						$response = $categories->updateParamsFromData($data);
						HelperUG::ajaxResponseSuccess(__("Params Updated", "unitegallery"));
						
					break;
					case "update_cat_posts_data":
						
						$categories->updatePostsDataFromData($data);
						
						//return preview from data
						$response = HelperHTMLUG::getPreviewPaneHTMLFromData($data);
						
						HelperUG::ajaxResponseData($response);
					break;
					case "add_item":
						$itemData = $items->addFromData($data);						
						HelperUG::ajaxResponseData($itemData);
					break;
					case "get_item_data":			
						$response = $items->getItemData($data);
						HelperUG::ajaxResponseData($response);
					break;
					case "update_item_data":
						
						$response = $items->updateItemData($data);
												
						HelperUG::ajaxResponseSuccess(__("Item data updated!","unitegallery"), $response);
					break;
					case "remove_items":
						$response = $items->removeItemsFromData($data);
						HelperUG::ajaxResponseSuccess(__("Items Removed","unitegallery"),$response);						
					break;					
					case "get_cat_items":
												
						$responeData = $items->getCatItemsHtmlFromData($data);
												
						//update category param if inside gallery						
						$gallery->updateItemsCategoryFromData($data);
						
						HelperUG::ajaxResponseData($responeData);
					break;
					case "update_item_title":
						$items->updateItemTitleFromData($data);
						HelperUG::ajaxResponseSuccess(__("Item Title Updated","unitegallery"));
					break;
					case "duplicate_items":
						$response = $items->duplicateItemsFromData($data);
						HelperUG::ajaxResponseSuccess(__("Items Duplicated","unitegallery"),$response);
					break;
					case "update_items_order":
						$items->saveOrderFromData($data);
						HelperUG::ajaxResponseSuccess(__("Order Saved","unitegallery"));
					break;
					case "copy_move_items":
						$response = $items->copyMoveItemsFromData($data);
						HelperUG::ajaxResponseSuccess(__("Done Operation","unitegallery"),$response);
					break;
					case "create_gallery":
						$galleryID = $galleries->addGaleryFromData($galleryType, $data);
						$urlView = HelperUG::getGalleryView($galleryID);
						HelperUG::ajaxResponseSuccessRedirect(__("Gallery Created","unitegallery"),$urlView);
					break;
					case "delete_gallery":
						$galleries->deleteGalleryFromData($data);
						HelperUG::ajaxResponseSuccessRedirect(__("Gallery deleted","unitegallery"), $urlGalleriesView);
					break;
					case "update_gallery":
						
						$galleries->updateGalleryFromData($data);
						HelperUG::ajaxResponseSuccess(__("Gallery Updated"));
					break;
					case "change_gallery_theme":
						$urlRedirect = $galleries->changeGalleryThemeFromData($data);
						HelperUG::ajaxResponseSuccessRedirect(__("Gallery Theme Updated, reloading..."), $urlRedirect);
					break;
					case "duplicate_gallery":
						$galleries->duplicateGalleryFromData($data);
						HelperUG::ajaxResponseSuccessRedirect(__("Gallery duplicated","unitegallery"), $urlGalleriesView);
					break;
					case "update_plugin":
						
						if(method_exists("UniteProviderFunctionsUG", "updatePlugin"))
							UniteProviderFunctionsUG::updatePlugin();
						else{
							echo "Functionality Don't Exists";
						}
						
					break;
					case "export_gallery_settings":
						$galleryID = UniteFunctionsUG::getPostGetVariable("galleryid");
						$galleries->exportGallerySettings($galleryID);
						
					break;
					case "import_gallery_settings":
						$galleryID = UniteFunctionsUG::getPostGetVariable("galleryid");
						$galleries->importGallerySettingsFromUploadFile($galleryID);
					break;
					case "export_cat_items":
						$items->exportCatItemsFromData($data);
					break;
					case "import_cat_items":
						$response = $items->importCatItemsFromData($data);
						HelperUG::ajaxResponseSuccess(__("Items Imported", "unitegallery"), $response);
					break;
					case "update_general_settings":
						$operations->updateGeneralSettingsFromData($data);
						HelperUG::ajaxResponseSuccess(__("Settings Saved","unitegallery"));
					break;
					case "get_posts_list_forselect":
						
						$objPostsSelect = new UniteGalleryPostsSelect();
						$arrPostList = $objPostsSelect->getPostListForSelectFromData($data);
					
						HelperUG::ajaxResponseData($arrPostList);
						
					break;
					case "get_select2_post_titles":
						
						$arrData = $operations->getSelect2PostTitles($data);
						
						HelperUG::ajaxResponseData(array("select2_data"=>$arrData));
						
					break;
					case "get_preview_pane_html":
						
						$response = HelperHTMLUG::getPreviewPaneHTMLFromData($data);
						
						HelperUG::ajaxResponseData($response);
						
					break;
					
					default:
						HelperUG::ajaxResponseError("wrong ajax action: <b>$action</b> ");
					break;
				}
				
			}
			catch(Exception $e){
				$message = $e->getMessage();
				
				$errorMessage = $message;
				if(GlobalsUG::SHOW_TRACE == true){
					$trace = $e->getTraceAsString();
					$errorMessage = $message."<pre>".$trace."</pre>";					
				}
				
				HelperUG::ajaxResponseError($errorMessage);
			}
			
			//it's an ajax action, so exit
			HelperUG::ajaxResponseError("No response output on <b> $action </b> action. please check with the developer.");
			exit();
		}
		
		
	}
	
	
?>