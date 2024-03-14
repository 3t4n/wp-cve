<?php

defined('LAYOUTS_EDITOR_INC') or die('Restricted access');

   class UniteLayoutEditorAdmin{
		
   		const POST_TYPE = "uc_layout";
   	
	   	private static $arrMenuPages = array();
	   	private static $arrSubMenuPages = array();
	   	private static $capability = "manage_options";
	   	private static $isBlox;
	   	
   		private $mainFilepath;
   		
	   	private static $t;
	   	
	   	const ACTION_ADMIN_MENU = "admin_menu";
	   	const ACTION_ADMIN_INIT = "admin_init";
	   	const ACTION_ADD_SCRIPTS = "admin_enqueue_scripts";
	   	const ACTION_WP_LOADED = "wp_loaded";
   		const ACTION_ADMIN_FOOTER = "admin_footer";
   		
   		
		/**
		 *
		 * the constructor
		 */
		public function __construct($mainFilepath){
			self::$t = $this;
			
			$this->mainFilepath = $mainFilepath;
			
			$this->init();
						
		}		

		
		/**
		 *
		 * add menu page
		 */
		protected static function addMenuPage($title,$pageFunctionName,$icon=null,$link=null){
			self::$arrMenuPages[] = array("title"=>$title,"pageFunction"=>$pageFunctionName,"icon"=>$icon,"link"=>$link);
		}
		
		
		/**
		 *
		 * add sub menu page
		 */
		protected static function addSubMenuPage($slug,$title,$pageFunctionName,$realLink = false,$parentSlug = null){
			self::$arrSubMenuPages[] = array("slug"=>$slug,"title"=>$title,"pageFunction"=>$pageFunctionName,"realLink"=>$realLink,"parentSlug"=>$parentSlug);
		}
		
		
		/**
		 * add admin menus from the list.
		 */
		public static function addAdminMenu(){

			global $menu, $submenu;
			
			$cleanTitle = false;
			$mainMenuSlug = null;
			
			//return(false);
			foreach(self::$arrMenuPages as $mainMenu){
				$title = $mainMenu["title"];
				$pageFunctionName = $mainMenu["pageFunction"];
				$icon = "";
				if(isset($mainMenu["icon"])) 
					$icon = $mainMenu["icon"];
				
				add_menu_page( $title, $title, self::$capability, LayoutEditorGlobals::PLUGIN_NAME, array(self::$t, $pageFunctionName), $icon );
				
				//$link = $mainMenu["link"];

				$cleanTitle = $title;
				$mainMenuSlug = LayoutEditorGlobals::PLUGIN_NAME;
				
				
				/*
				if(!empty($link)){
					$cleanTitle = $title;
					$mainMenuSlug = $link;
					
					$keys = array_keys($menu);
					$lastMainMenuKey = $keys[count($keys)-1];
					$menu[$lastMainMenuKey][2] = $link;
				}
				*/
				
			}
			
			foreach(self::$arrSubMenuPages as $key=>$submenuMenu){
								
				$title = $submenuMenu["title"];
				$pageFunctionName = $submenuMenu["pageFunction"];
		
				$slug = LayoutEditorGlobals::PLUGIN_NAME."_".$submenuMenu["slug"];
				
				$isRealLink = $submenuMenu["realLink"];
				$parentSlug = $submenuMenu["parentSlug"];
				
				//if(empty($parentSlug))
					$parentSlug = LayoutEditorGlobals::PLUGIN_NAME;
				
				if($key == 0 && $isRealLink == false)
					$slug = LayoutEditorGlobals::PLUGIN_NAME;
								
				add_submenu_page($parentSlug, $title, $title, 'manage_options', $slug, array(self::$t, $pageFunctionName) );
				
				
				//switch the link for real link
				if($isRealLink === true && isset($submenu[$parentSlug])){
					$arrMain = $submenu[$parentSlug];
					$keys = array_keys($arrMain);
					$lastKey = $keys[count($keys)-1];
					$arrMain[$lastKey][2] = $submenuMenu["slug"];
					$submenu[$parentSlug] = $arrMain;
				}
				
			}
			
			//clean double submenus
			if(!empty($cleanTitle) && isset($submenu[$mainMenuSlug])){
				$arrSubMenu = $submenu[$mainMenuSlug];
				if($arrSubMenu[0][0] == $cleanTitle)
					unset($submenu[$mainMenuSlug][0]);
			}
			
			
		}

		
		/**
		 *
		 * add some wordpress action
		 */
		protected static function addAction($action,$eventFunction, $numArgs=1){
		
			add_action( $action, array(self::$t, $eventFunction) ,10, $numArgs);
		}
		
		/**
		 *
		 * add some wordpress action
		 */
		protected static function addFilter($action,$eventFunction,$numArgs){
			
			add_action( $action, array(self::$t, $eventFunction), 10, $numArgs);
		}
		
		
		/**
		 * stand alone plugin
		 */
		private function a______AL_FUNCTIONS____________(){}
		
		
		/**
		 * is blox exists
		 */
		public static function isBloxExists(){
			
			$arrPlugins = get_plugins();
			
			$pluginName = "blox-page-builder/blox_builder.php";
			
			if(isset($arrPlugins[$pluginName]) == true){
				$isActive = is_plugin_active($pluginName);
				
				return($isActive);
			}
			
			return(false);
		}
		
		
		/**
		 * return if creator plugin exists
		 */
		public static function isAddonLibraryExists(){
			
			if(function_exists("get_plugins") == false)
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
			
			if(function_exists("get_plugins") == false)
				return(false);
			
			$arrPlugins = get_plugins();
			
			//check addon library
			$pluginName = "addon-library/addonlibrary.php";
			
			if(isset($arrPlugins[$pluginName]) == true){
				$isActive = is_plugin_active($pluginName);
				if($isActive == true)
					return(true);
			}
			
			//check blox builder
			$pluginName = "blox-page-builder/blox_builder.php";
			
			if(isset($arrPlugins[$pluginName]) == true){
				$isActive = is_plugin_active($pluginName);
				
				return($isActive);
			}
			
			
			return(false);
		}
		
		
		/**
		 * admin pages
		 */
		public static function adminPages(){
			
			
			//$objAdmin = new UniteProviderAdminUC();
			//$objAdmin->adminPages();
			
			UniteProviderAdminUC::adminPages();
			
		}
		
		/**
		 * add the additional post type
		 */
		public static function addPostType(){
			register_post_type( self::POST_TYPE,
			
					array(
							'labels' => array(
									'name' => __( 'Layouts Builder' ),
									'singular_name' => __( 'Addon Layout' ),
									'add_new_item' => __( 'Add New Layout' ),
									'edit_item' => __( 'Edit Layout' ),
									'new_item' => __( 'New Layout' ),
									'view_item' => __( 'View Layout' ),
									'view_items' => __( 'View Layouts' ),
									'search_items' => __( 'Search Layouts' ),
									'not_found' => __( 'No Layouts Found' ),
									'not_found_in_trash' => __( 'No Layouts found in trash' ),
									'all_items' => __( 'All Layouts' )
							),
							'public' => false,
							'show_ui' => true,
							'show_in_nav_menus' => false,
							'show_in_menu'=>false,
							'has_archive' => true,
							'hierarchical' => false,
							'exclude_from_search' => true
					)
			);
			
		}

		
		/**
		 * check if the page is layouts edit
		 */
		public static function isLayoutsEditPage(){
			
			$screen = get_current_screen();
			UniteFunctionsUC::validateNotEmpty($screen, "current screen");
			
			$id = $screen->id;
			if($id == "edit-".self::POST_TYPE)
				return(true);
			
			return(false);
		}
		
		
		/**
		 * define the extra column
		 */
		public static function addExtraColumn_define($columns){
			
			$isLayoutsPage = self::isLayoutsEditPage();
			if($isLayoutsPage == false)
				return($columns);
			
			$arrColsNew = array();
			foreach($columns as $key=>$value){
								
				$arrColsNew[$key] = $value;
				
				if($key == "title")
					$arrColsNew["shortcode"] = __("Shortcode",UNITEGALLERY_TEXTDOMAIN);
			}
			
			return($arrColsNew);
		}
		
		
		/**
		 * output the custom column
		 */
		public static function addExtraColumn_output($column, $postID){

			$isLayoutsPage = self::isLayoutsEditPage();
			if($isLayoutsPage == false)
				return(false);

			switch($column){
				case "shortcode":
					$objLayout = new UniteCreatorLayout();
					$objLayout->initByID($postID);
					$shortcode = $objLayout->getShortcode("uc_layout");
					$shortcode = esc_attr($shortcode);
					
					?>
					<input type="text" readonly onclick="this.select()" value="<?php echo $shortcode?>">
					<?php 
				break;
			}
			
		}

		
		/**
		 * register the taxanomy
		 */
		public static function addExtraColumn(){
			
			self::addFilter('manage_posts_columns', "addExtraColumn_define", 1);
			
			self::addAction("manage_posts_custom_column", "addExtraColumn_output", 2);
			
		}
		
		
				
		
		/**
		 * add layout post url
		 */
		public static function addLayoutPostUrl($url, $path, $blog_id){
			
			switch($path){
				case 'post-new.php?post_type='.self::POST_TYPE:
					$urlLayout = HelperUC::getViewUrl_Layout();
					return($urlLayout);
				break;
			}
			
			return $url;
		}
		
		
		/**
		 * modify edit post link
		 * change links on the layout editor only
		 */
		public static function modifyEditPostLink($link, $postID, $context){
			
			//change links on the layout 
			$post = get_post($postID);
			if(empty($post))
				return($link);
			
			$postType = get_post_type($post);
			
			switch($postType){
				case self::POST_TYPE:
					$link = HelperUC::getViewUrl_Layout($postID);
				break;
			}
			
			return($link);
		}
		
		
		/**
		 * add layouts list actions
		 */
		public static function addLayoutsListActions($actions, $post){
		
			$postType = get_post_type($post);
			
			if($postType != self::POST_TYPE)
				return($actions);
			
			$postID = $post->ID;
			
			//add preview
			$text = __("Preview", ADDONLIBRARY_TEXTDOMAIN);
			$link = helperuc::getViewUrl_LayoutPreview($postID);
			$htmlLink = HelperHtmlUC::getHtmlLink($link, $text);
			
			$actions["layout_preview"] = $htmlLink;
			
			//add export
			$textExport = __("Export", ADDONLIBRARY_TEXTDOMAIN);
			$htmlLinkExport = "<a href='javascript:void(0)' data-layoutid='{$postID}' class='uc_button_export'>{$textExport}</a>";
			$actions["layout_export"] = $htmlLinkExport;
			
			
			return($actions);
		}
		
		
		/**
		 *
		 * tells if the the current plugin opened is this plugin or not
		 * in the admin side.
		 */
		public static function isInsidePlugin(){
			$page = UniteFunctionsUC::getGetVar("page","",UniteFunctionsUC::SANITIZE_KEY);
			
			if($page == LayoutEditorGlobals::PLUGIN_NAME || strpos($page, LayoutEditorGlobals::PLUGIN_NAME."_") !== false)
				return(true);
			
			return(false);
		}
		
		
		/**
		 * on add scripts
		 */
		public static function onAddScripts(){
			
			UniteCreatorAdmin::onAddScripts();
						
		}
		
		/**
		 * add scripts on layouts list page
		 */
		public static function onAddScriptsLayoutsListPage(){
			
			$globalJsOutput = HelperHtmlUC::getGlobalJsOutput();
			UniteProviderFunctionsUC::printCustomScript($globalJsOutput);
			
			UniteCreatorAdmin::setView(GlobalsUC::VIEW_LAYOUTS_LIST);
			
			UniteCreatorAdmin::onAddScripts();
			
			$urlViewAdmin = LayoutEditorGlobals::$urlPlugin."assets/layouts_postlists_admin.js";
			HelperUC::addScriptAbsoluteUrl($urlViewAdmin, "layouts_postlists_admin");
			
		}
		
		
		/**
		 * on add external scripts
		 */
		public static function onAddScriptsExternal(){
			
			if(self::isLayoutsEditPage())
				self::onAddScriptsLayoutsListPage();
			
		}
		
		
		/**
		 * add admin menu
		 */
		public static function initLayoutsEditor_addAdminMenu(){
			
			//add menu
			$urlIcon = GlobalsUC::$url_provider."assets/images/icon_menu.png";
			
			$slugLayouts = "edit.php?post_type=".self::POST_TYPE;
			
			if(self::$isBlox == false)
				$slugAddLayout = "layout";
			else
				$slugAddLayout = "post-new.php?post_type=uc_layout";
			
			self::addMenuPage(__('Layouts Builder',ADDONLIBRARY_TEXTDOMAIN), "adminPages", $urlIcon, $slugLayouts);
			
			self::addSubMenuPage($slugLayouts, __("All Layouts",ADDONLIBRARY_TEXTDOMAIN), "adminPages", true, $slugLayouts);
			self::addSubMenuPage($slugAddLayout, __('Add Layout',ADDONLIBRARY_TEXTDOMAIN), "adminPages",false,$slugLayouts);
			
			if(self::$isBlox == false)
				self::addSubMenuPage("layouts_settings", __('Layouts Settings',ADDONLIBRARY_TEXTDOMAIN), "adminPages", false, $slugLayouts);
			
		}
		
		
		/**
		 * put layouts page html
		 */
		public static function putLayoutsPageHtml(){
			
			//put general debug divs
			
			$debugDivs = HelperHtmlUC::getGlobalDebugDivs();
			echo $debugDivs;
			
			
			require HelperUC::getPathViewObject("layouts_view.class");
			$objView = new UniteCreatorLayoutsView();
			$objView->putDialogImportLayout();
			
			?>
			
			<script type="text/javascript">
				jQuery(document).ready(function(){

					var objAdmin = new LayoutsPostsListPageAdmin();
	
					objAdmin.init();
					
				});
			</script>
			<?php 
		}
		
		
		/**
		 * on admin footer
		 */
		public static function onAdminFooter(){
			
			$isLayoutsPage = self::isLayoutsEditPage();
			if($isLayoutsPage == true)
				self::putLayoutsPageHtml();
			
		}
		
		
		/**
		 * init the layouts editor version
		 */
		public static function initLayoutsEditor(){
						
			self::$isBlox = self::isBloxExists();
			
			self::addPostType();
			
			self::initLayoutsEditor_addAdminMenu();
			
			if(self::$isBlox == false){
				self::addFilter('admin_url', "addLayoutPostUrl", 3);
				self::addFilter("get_edit_post_link", "modifyEditPostLink", 3);
				
				if(self::isInsidePlugin() == true){
					
					self::addAction(self::ACTION_ADD_SCRIPTS, "onAddScripts");
					
				}else{
					self::addAction(self::ACTION_ADD_SCRIPTS, "onAddScriptsExternal");
				}
				
				//add footer html
				self::addAction(self::ACTION_ADMIN_FOOTER, "onAdminFooter");
				
				self::addFilter("post_row_actions", "addLayoutsListActions", 2);
				
			}
			
			//extra column actions, to blox as well
			
						
			self::addExtraColumn();
						
			
		}
		
		
		/**
		 * stand alone plugin
		 */
		private function a______STAND_ALONE____________(){}
		
		
		/**
		 *
		 * admin main page function.
		 * load stand lone view
		 */
		public static function adminPagesStandalone(){
			
			$view = LayoutEditorGlobals::$pathViews."standalone.php";
			require $view;
		}
		
		
		/**
		 * stand alone admin notice
		 */
		public static function standAloneAdminNotice(){
			
			$screen = @get_current_screen();
			if(!isset($screen->id) || $screen->id != "plugins")
				return(false);
			
			$link = admin_url("plugin-install.php?tab=search&s=addon+library");
			$message = __("<strong>Addon Library Layouts Builder</strong> requires <a href='{$link}'>Addon Library</a> plugin installed and activated.");
			echo "<div class='updated'><p>".$message."</p></div>";
		}
		
		
		/**
		 * stand alone init
		 */
		public static function initStandAlone(){
			
			self::addMenuPage('Layouts Builder', "adminPagesStandalone");
			
			self::addAction("admin_notices", "standAloneAdminNotice");
		}
		
		
		/**
		 * on plugins loaded
		 */
		public static function onAllLoaded(){
			
			$isAddonLibraryExists = self::isAddonLibraryExists();
			if($isAddonLibraryExists == false)
				self::initStandAlone();
			else
				self::initLayoutsEditor();
		}
		
		
		/**
		 * on regiester plugin
		 */
		public function onRegisterPlugin(){
		
			$pathPlugin = dirname(__FILE__)."/plugin.class.php";
			
			require_once($pathPlugin);
		}
		
		
		
		
		
		/**
		 * 
		 * init function
		 */
		protected function init(){
			
			self::addAction(self::ACTION_ADMIN_MENU, "addAdminMenu");
			
			self::addAction("init", "onAllLoaded");
			
			add_action('addon_library_register_plugins', array($this, "onRegisterPlugin"));
			
												
		}
		
		
	}

?>