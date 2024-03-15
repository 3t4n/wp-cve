<?php

defined('UNITEGALLERY_INC') or die('Restricted access');

class UniteProviderFunctionsUG{
	
	private static $arrScripts = array();
	private static $arrStylesInline = array();
	
	
	/**
	 * init base variables of the globals
	 */
	public static function initGlobalsBase($pluginFolder){
		
		global $wpdb;
		
		GlobalsUG::$isScriptsInFooter = true;
		$tablePrefix = $wpdb->prefix;
		
		GlobalsUG::$table_galleries = $tablePrefix.GlobalsUG::TABLE_GALLERIES_NAME;
		GlobalsUG::$table_categories = $tablePrefix.GlobalsUG::TABLE_CATEGORIES_NAME;
		GlobalsUG::$table_items = $tablePrefix.GlobalsUG::TABLE_ITEMS_NAME;
		
		GlobalsUG::$table_posts = $tablePrefix."posts";
				
		$pluginName = "unitegallery";
		
		GlobalsUG::$pathPlugin = realpath($pluginFolder)."/";
		
		GlobalsUG::$path_media_ug = GlobalsUG::$pathPlugin."unitegallery-plugin/";
		
		GlobalsUG::$path_base = ABSPATH;
		
		$arrUploadDir = wp_upload_dir();
		$pathImages = $arrUploadDir["basedir"];
		
		GlobalsUG::$path_images = realpath($pathImages)."/";
		
		GlobalsUG::$path_cache = GlobalsUG::$pathPlugin."cache/";
		
		GlobalsUG::$urlPlugin = plugin_dir_url( $pluginFolder."/unitegallery.php" );
				
		GlobalsUG::$url_component_client = "";
		GlobalsUG::$url_component_admin = admin_url()."admin.php?page=$pluginName";
		
		GlobalsUG::$url_base = site_url()."/";
				
		GlobalsUG::$url_media_ug = GlobalsUG::$urlPlugin."unitegallery-plugin/";

		GlobalsUG::$url_images = content_url()."/";

		GlobalsUG::$url_ajax = admin_url()."admin-ajax.php";
		
		GlobalsUG::$url_ajax_front = GlobalsUG::$url_ajax;
		
		
		
	}
	
	public static function a_____________SANITIZE___________(){}
	
	
	/**
	 * filter variable
	 */
	public static function sanitizeVar($var, $type = null){
	
		if($type == null)
			$type = UniteFunctionsUG::SANITIZE_TEXT_FIELD;
		
		
		switch($type){
			case UniteFunctionsUG::SANITIZE_ID:
				if(empty($var))
					return("");
		
				$var = (int)$var;
				$var = abs($var);
	
				if($var == 0)
					return("");
			
			break;
			case UniteFunctionsUG::SANITIZE_KEY:
				$var = sanitize_key($var);
			break;
			case UniteFunctionsUG::SANITIZE_TEXT_FIELD:
				$var = sanitize_text_field($var);
			break;
			case UniteFunctionsUG::SANITIZE_NOTHING:
			break;
			default:
				UniteFunctionsUG::throwError("Wrong sanitize type: " . $type);
			break;
		}
	
		return($var);
	}
	
	
	/**
	 * add scripts and styles framework
	 */
	public static function addScriptsFramework(){
		
		UniteFunctionsWPUG::addMediaUploadIncludes();
				
		wp_enqueue_script( 'jquery' );
		
		//add jquery ui
		wp_enqueue_script("jquery-ui");
		wp_enqueue_script("jquery-ui-dialog");
		
		HelperUG::addStyle("jquery-ui.structure.min","jui-smoothness-structure","css/jui/new");
		HelperUG::addStyle("jquery-ui.theme.min","jui-smoothness-theme","css/jui/new");
		
		if(function_exists("wp_enqueue_media"))
			wp_enqueue_media();
		
	}
	
	
	/**
	 *
	 * register script
	 */
	public static function addScript($handle, $url){
	
		if(empty($url))
			UniteFunctionsUG::throwError("empty script url, handle: $handle");
	
		$version = UNITEGALLERY_VERSION;
		
		if(GlobalsUG::$inDev == true)	//add script
			$version = time();
		
		
		wp_register_script($handle , $url, array(), $version);
		wp_enqueue_script($handle);
	}
	
	
	/**
	 *
	 * register script
	 */
	public static function addStyle($handle, $url){
		
		if(empty($url))
			UniteFunctionsUG::throwError("empty style url, handle: $handle");
		
		$version = UNITEGALLERY_VERSION;
		
		if(GlobalsUG::$inDev == true)	//add script
			$version = time();
		
		wp_register_style($handle , $url, array(), $version);
		wp_enqueue_style($handle);
			
	}
	
	/**
	 * get image url from image id
	 */
	public static function getImageUrlFromImageID($imageID){
		
		$urlImage = UniteFunctionsWPUG::getUrlAttachmentImage($imageID);
				
		return($urlImage);
	}
	
	/**
	 * get image data from image id
	 */
	public static function getImageDataFromImageID($imageID){
		if(empty($imageID))
			return(null);
		$data = UniteFunctionsWPUG::getAttachmentData($imageID);
		return($data);
	}
	/**
	 * get image url from image id
	 */
	public static function getThumbUrlFromImageID($imageID, $size = null){
		if($size == null)
			$size = UniteFunctionsWPUG::THUMB_MEDIUM;
		
		$urlThumb = UniteFunctionsWPUG::getUrlAttachmentImage($imageID, $size);
		
		
		return($urlThumb);
	}
	
	
	
	/**
	 * strip slashes from ajax input data
	 */
	public static function normalizeAjaxInputData($arrData){
		
		if(!is_array($arrData))
			return($arrData);
		
		foreach($arrData as $key=>$item){
			
			if(is_string($item))
				$arrData[$key] = stripslashes($item);
			
			//second level
			if(is_array($item)){
				
				foreach($item as $subkey=>$subitem){
					if(is_string($subitem))
						$arrData[$key][$subkey] = stripslashes($subitem);
					
					//third level
					if(is_array($subitem)){

						foreach($subitem as $thirdkey=>$thirdItem){
							if(is_string($thirdItem))
								$arrData[$key][$subkey][$thirdkey] = stripslashes($thirdItem);
						}
					
					}
					
				}
			}
			
		}
		
		return($arrData);
	}
	
	
	/**
	 * put footer text line
	 */
	public static function putFooterTextLine(){
		?>
			&copy; <?php _e("All rights reserved","unitegallery")?>, <a href="http://wp.unitegallery.net" target="_blank">Valiano</a>. &nbsp;&nbsp;		
		<?php
	}
	
	
	/**
	 * add jquery include
	 */
	public static function addjQueryInclude($app, $urljQuery = null){
		
		wp_enqueue_script("jquery");
		
	}
	
	
	/**
	 * add position settings (like shortcode) based on the platform
	 */
	public static function addPositionToMainSettings($settingsMain){
	
		$textGenerate = __("Generate Shortcode","unitegallery");
		$descShortcode = __("Copy this shortcode into article text","unitegallery");
		$settingsMain->addTextBox("shortcode", "",__("Gallery Shortcode","unitegallery"),array("description"=>$descShortcode, "readonly"=>true, "class"=>"input-alias input-readonly", "addtext"=>"&nbsp;&nbsp; <a id='button_generate_shortcode' class='unite-button-secondary' >{$textGenerate}</a>"));
	
	
		return($settingsMain);
	}
	
	/**
	 * modify default values of troubleshooter settings
	 */
	public static function modifyTroubleshooterSettings($settings){
	
	
		return($settings);
	}
	
	
	/**
	 * print some script at some place in the page
	 */
	public static function printCustomScript($script, $hardCoded = false){
		
		if($hardCoded == false)
			self::$arrScripts[] = $script;
		else 
			echo "<script type='text/javascript'>{$script}</script>";
		
	}
	
	
	/**
	 * get all custom scrips
	 */
	public static function getCustomScripts(){
		
		return(self::$arrScripts);
	}
	
	
	/**
	 * get inline styles
	 * init the styles after each get
	 */
	public static function getStylesInline(){
		
		$styles = "";
		
		if(!empty(self::$arrStylesInline))
			$styles = implode("\n", self::$arrStylesInline);
		
		self::$arrStylesInline = array();
		
		return($styles);
	}	
	
	
	/**
	 * add inline style
	 */
	public static function addStyleInline($style){

		//for front end
		wp_add_inline_style("unite-gallery-css", $style);
		
		//for backend
		self::$arrStylesInline[] = $style;
	}
	
	
	/**
	 * print inline styles
	 */
	public static function printInlineStyles(){
		
		$styles = self::getStylesInline();
		
		if(!empty($styles))
			echo "\n<style type='text/css'>{$styles}</style>";
	
	}
	
	
	/**
	 * add tiles size settings
	 */
	public static function addTilesSizeSettings($settings){
		
		$settings->addHr();
		
		$arrItems = UniteFunctionsWPUG::getArrThumbSizes();
		$params = array(
			"description"=>__("Tiles thumbs resolution. If you choose custom resolution like: 'Big', and you use it with existing images, you need to recreate the thumbnails. You can use 'Regenerate Thumbnails' WordPress plugin for that", "unitegallery")
		);
		$settings->addSelect("thumb_resolution", $arrItems, __("Tile Image Resolution","unitegallery"), UniteFunctionsWPUG::THUMB_MEDIUM, $params);
		
		//add mobile thumb resolution
		$params = array(
				"description"=>__("Mobile tiles thumbs image resolution. Will be active in devices less then 480 in width", "unitegallery"),
				"rowclass"=>"ug-setting-mobile"
		);
		$arrItemsMobile = array_merge(array(""=>"No Change"), $arrItems);
		$settings->addSelect("thumb_resolution_mobile", $arrItemsMobile, __("Tile Image Resolution - Mobile","unitegallery"), "", $params);
		return($settings);
	}

	
	/**
	 * 
	 * @param  $settings
	 */
	public static function addBigImageSizeSettings($settings, $isLightbox = false, $addAfter = null){
		
		$arrItems = UniteFunctionsWPUG::getArrThumbSizes("big_only");
		$params = array(
			"description"=>__("Big image resolution. If you choose custom resolution like: 'Big', and you use it with existing images, you need to recreate the thumbnails. You can use 'Regenerate Thumbnails' WordPress plugin for that", "unitegallery")
		);
		
		if(!empty($addAfter)){
			$params[UniteSettingsUG::PARAM_ADD_SETTING_AFTER] = $addAfter;
		}
		
		//for slider, add hr before
		if($isLightbox == false){
			$hrName = "hr_big_image_resolution";
			$hrParams = array();
			$hrParams[UniteSettingsUG::PARAM_ADD_SETTING_AFTER] = $addAfter;
			$params[UniteSettingsUG::PARAM_ADD_SETTING_AFTER] = $hrName;
			$settings->addHr($hrName, $hrParams);
		}
		
		$optionTitle = ($isLightbox == true)? "Lightbox Image Resolution" : "Slider Image Resolution";
		$settings->addSelect("big_image_resolution", $arrItems, $optionTitle, UniteFunctionsWPUG::THUMB_FULL, $params);
		//add mobile settings
		$optionTitleMobile = ($isLightbox == true)? __("Lightbox Image Resolution Mobile", "unitegallery") : __("Slider Image Resolution Mobile", "unitegallery");
		$paramsMobile = array(
				"description"=>__("Big image resolution in Mobile mode. Will be active in devices less then 480 in width", "unitegallery")
		);
		
		$paramsMobile[UniteSettingsUG::PARAM_ADD_SETTING_AFTER] = "big_image_resolution";
		$arrItemsMobile = UniteFunctionsWPUG::getArrThumbSizes();
		$arrItemsMobile = array_merge(array(""=>"No Change"), $arrItemsMobile);
		$settings->addSelect("big_image_resolution_mobile", $arrItemsMobile, $optionTitleMobile, "", $paramsMobile);
		return($settings);
	}
	
	
	
	/**
	 * get "small" thumb sizes (medium / thumbnail)
	 */
	public static function getThumbSizesSmall(){
		
		$arrItems = UniteFunctionsWPUG::getArrThumbSizes("small_only");
		return($arrItems);
	}
	/**
	 * put galleries view text
	 */
	public static function putGalleriesViewText(){
		
		?>
		
		<div class="galleries-view-box">
			
			This is a <b>Lite Version </b> of the gallery that has some limitations like <i>"limited number of items per gallery"</i>. 
			<br> For removing the limitations, get the <b>"Unite Gallery Full Version"</b> and update plugin (button of the bottom). 
			No worry, every gallery you have made will remain.
			<a href="http://wp.unitegallery.net" target="_blank">Get It Now!</a>
			
		</div>
		
		<div class="galleries-view-box" style="">		
			
			<div class="view-box-title">How to use the gallery</div>
			
				<p>
				* From the <b>page and/or post editor</b> insert the shortcode from the gallery view. Example: <b>[unitegallery gallery1]</b>
				</p>
				
				<p>
				* For <b>similar galleries</b> on multiple pages with different item on each you can use "Generate Shortcode" button. Example: <b>[unitegallery gallery1 catid=7]</b>
				</p>	
				
				<p>
				* Also you can use <b>native gallery shortcode</b> for generating galleries. Example: <b>[gallery unitegallery="gallery1" ids="1,2,3"]</b>
				</p>	
				
				<p>
				* From the <b>widgets panel</b> drag the "Unite Gallery" widget to the desired sidebar<br/>
				</p>
				
				<p>
				* From the <b>theme php files</b> use: <code>&lt;?php putUniteGallery("alias", catid) ?&gt;</code> 
				example: <code>putUniteGallery("gallery1")</code> or: <code>putUniteGallery("gallery1", 2)</code>
				
				<a href="javascript:void(0)" onclick="jQuery('#div_phpput_moreinfo').show();jQuery(this).hide()">more info</a>
				<br/>
				
				<div id="div_phpput_moreinfo" style="padding-left:50px;padding-top:10px;display:none">
					For show only on homepage use: <code>&lt;?php putUniteGallery("alias", "", "homepage") ?&gt;</code> <br>
					For show on certain pages use: <code>&lt;?php putUniteGallery("gallery1", "", "4,6,12") ?&gt;</code> 
				</div>
				
				</p>
				
		</div>
		
		<?php
	}
	
	
	/**
	 * put update plugin button
	 */
	public static function putUpdatePluginHtml(){
		?>
		
		<!-- update gallery button -->
		
		<div class="ug-update-plugin-wrapper">
			<a id="ug_button_update_plugin" class="unite-button-primary" href="javascript:void(0)" ><?php _e("Update Plugin", "unitegallery")?></a>
		</div>
		
		<!-- dialog update -->
		
		<div id="dialog_update_plugin" title="<?php _e("Update Gallery Plugin","unitegallery")?>" style="display:none;">	
		
			<div class="unite-dialog-title"><?php _e("Update Unite Gallery Plugin","unitegallery")?>:</div>	
			<div class="unite-dialog-desc">
			<?php _e("To update the gallery please select the gallery install package.","unitegallery") ?>		
		
		<br>
		
		<?php _e("The files will be overwriten", "unitegallery")?>
		
		
		<br> <?php _e("File example: unitegallery1.5.zip","unitegallery")?>	</div>	
		
		<br>	
		
		<form action="<?php echo GlobalsUG::$url_ajax?>" enctype="multipart/form-data" method="post">
		
		<input type="hidden" name="action" value="unitegallery_ajax_action">		
		<input type="hidden" name="client_action" value="update_plugin">		
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce("unitegallery_actions"); ?>">
		<?php _e("Choose the update file:","unitegallery")?>
		<br><br>
		
				<input type="file" name="update_file" class="unite-dialog-fileinput">		
		
		<br><br>
		
				<input type="submit" class='unite-button-primary' value="<?php _e("Update Gallery Plugin","unitegallery")?>">	
		</form>
		
		</div>

		
		<?php 
	}
	
	/**
	 * check that inner zip exists, and unpack it if do
	 */
	private static function updatePlugin_checkUnpackInnerZip($pathUpdate, $zipFilename){
		$arrFiles = UniteFunctionsUG::getFileList($pathUpdate);
		if(empty($arrFiles))
			return(false);
		//get inner file
		$filenameInner = null;
		foreach($arrFiles as $innerFile){
			if($innerFile != $zipFilename)
				$filenameInner = $innerFile;
		}
		if(empty($filenameInner))
			return(false);
		//check if internal file is zip
		$info = pathinfo($filenameInner);
		$ext = UniteFunctionsUG::getVal($info, "extension");
		if($ext != "zip")
			return(false);
		$filepathInner = $pathUpdate.$filenameInner;
		if(file_exists($filepathInner) == false)
			return(false);
		dmp("detected inner zip file. unpacking...");
		//check if zip exists
		$zip = new UniteZipUG();
		if(function_exists("unzip_file") == true){
			WP_Filesystem();
			$response = unzip_file($filepathInner, $pathUpdate);
		}
		else
			$zip->extract($filepathInner, $pathUpdate);
	}
	
	
	/**
	 *
	 * Update Plugin
	 */
	public static function updatePlugin(){
		
		try{
		
			//verify nonce:
			$nonce = UniteFunctionsUG::getPostVariable("nonce");
			$isVerified = wp_verify_nonce($nonce, "unitegallery_actions");
			
			if($isVerified == false)
				UniteFunctionsUG::throwError("Security error");
			
		
			$linkBack = HelperUG::getGalleriesView();
			$htmlLinkBack = UniteFunctionsUG::getHtmlLink($linkBack, "Go Back");
		
			//check if zip exists
			$zip = new UniteZipUG();
			
			if(function_exists("unzip_file") == false){
				
				if( UniteZipUG::isZipExists() == false)
					UniteFunctionsUG::throwError("The ZipArchive php extension not exists, can't extract the update file. Please turn it on in php ini.");
			}
		
			dmp("Update in progress...");
			
			$arrFiles = UniteFunctionsUG::getVal($_FILES, "update_file");
			
			if(empty($arrFiles))
				UniteFunctionsUG::throwError("Update file don't found.");
			
			$filename = UniteFunctionsUG::getVal($arrFiles, "name");
			
			if(empty($filename))
				UniteFunctionsIG::throwError("Update filename not found.");			
		
			$fileType = UniteFunctionsUG::getVal($arrFiles, "type");
			
			$fileType = strtolower($fileType);
			
			$arrMimeTypes = array();
			$arrMimeTypes[] = "application/zip";
			$arrMimeTypes[] = "application/x-zip";
			$arrMimeTypes[] = "application/x-zip-compressed";
			$arrMimeTypes[] = "application/octet-stream";
			$arrMimeTypes[] = "application/x-compress";
			$arrMimeTypes[] = "application/x-compressed";
			$arrMimeTypes[] = "multipart/x-zip";
			
			if(in_array($fileType, $arrMimeTypes) == false)
				UniteFunctionsUG::throwError("The file uploaded is not zip.");
					
			$filepathTemp = UniteFunctionsUG::getVal($arrFiles, "tmp_name");
			if(file_exists($filepathTemp) == false)
				UniteFunctionsUG::throwError("Can't find the uploaded file.");
		
			//crate temp folder
			$pathTemp = GlobalsUG::$pathPlugin."temp/";
			UniteFunctionsUG::checkCreateDir($pathTemp);
			
			//create the update folder
			$pathUpdate = $pathTemp."update_extract/";
			UniteFunctionsUG::checkCreateDir($pathUpdate);
			
			if(!is_dir($pathUpdate))
				UniteFunctionsUG::throwError("Could not create temp extract path");
			
			//remove all files in the update folder
			$arrNotDeleted = UniteFunctionsUG::deleteDir($pathUpdate, false);
						
			if(!empty($arrNotDeleted)){
				$strNotDeleted = print_r($arrNotDeleted,true);
				UniteFunctionsUG::throwError("Could not delete those files from the update folder: $strNotDeleted");
			}
			
			//copy the zip file.
			$filepathZip = $pathUpdate.$filename;
			
			$success = move_uploaded_file($filepathTemp, $filepathZip);
			if($success == false)
				UniteFunctionsUG::throwError("Can't move the uploaded file here: ".$filepathZip.".");
			
			//extract files:
			if(function_exists("unzip_file") == true){
				WP_Filesystem();
				$response = unzip_file($filepathZip, $pathUpdate);
			}
			else
				$zip->extract($filepathZip, $pathUpdate);
			
			//check for internal zip in case that cocecanyon original zip was uploaded
			self::updatePlugin_checkUnpackInnerZip($pathUpdate, $filename);
			//get extracted folder
			$arrFolders = UniteFunctionsUG::getDirList($pathUpdate);
			if(empty($arrFolders))
				UniteFunctionsUG::throwError("The update folder is not extracted");
			
			
			//get product folder
			$productFolder = null;
			if(count($arrFolders) == 1)
			$productFolder = $arrFolders[0];
			else{
				foreach($arrFolders as $folder){
					if($folder != "documentation")
						$productFolder = $folder;
				}
			}
						
			if(empty($productFolder))
				UniteFunctionsUG::throwError("Wrong product folder.");
									
			$pathUpdateProduct = $pathUpdate.$productFolder."/";
						
			//check some file in folder to validate it's the real one:
			$checkFilepath = $pathUpdateProduct."unitegallery.php";
						
			if(file_exists($checkFilepath) == false)
				UniteFunctionsUG::throwError("Wrong update extracted folder. The file: ".$checkFilepath." not found.");
						
			//copy the plugin without the captions file.
			$pathOriginalPlugin = GlobalsUG::$pathPlugin;
						
			$arrBlackList = array();
			UniteFunctionsUG::copyDir($pathUpdateProduct, $pathOriginalPlugin,"",$arrBlackList);
			
			//delete the update
			UniteFunctionsUG::deleteDir($pathUpdate);
	
			//change folder to original (if updated to full version)
			if($productFolder == "unitegallery"){
				$pathRename = str_replace("unite-gallery-lite", "unitegallery", $pathOriginalPlugin);
				if($pathRename != $pathOriginalPlugin){
					$success = @rename($pathOriginalPlugin, $pathRename);
					if($success == true){	//activate plugin
						$pluginFile = $pathRename."unitegallery.php";
						if(file_exists($pluginFile)){
							$activateSuccess = UniteFunctionsWPUG::activatePlugin($pluginFile);
							if ( $activateSuccess == false ) 
								$linkBack = admin_url("plugins.php");	//link to plugin activate
						}
					}
				}
			}
			dmp("Updated Successfully, redirecting...");
					echo "<script>location.href='$linkBack'</script>";
	
			}catch(Exception $e){
				//remove all files in the update folder
				UniteFunctionsUG::deleteDir($pathUpdate);
			$message = $e->getMessage();
			$message .= " <br> Please update the plugin manually via the ftp";
			echo "<div style='color:#B80A0A;font-size:18px;'><b>Update Error: </b> $message</div><br>";
			echo $htmlLinkBack;
			exit();
		}
		
	}

	
	/**
	 * get nonce (for protection)
	 */
	public static function getNonce(){
		
		$nonce = wp_create_nonce("unitegallery_actions");
		
		return($nonce);
	}
	
	
	/**
	 * get js not included message
	 */
	public static function getJsNotIncludedErrorMessage(){
		$message = "Unite Gallery Error - gallery js and css files not included in the footer. Please make sure that wp_footer() function is added to your theme.";
		return($message);
	}
	
	/**
	 * get option
	 */
	public static function getOption($option, $default = false, $supportMultisite = false){
		if($supportMultisite == true && is_multisite())
			return(get_site_option($option, $default));
		else
			return get_option($option, $default);
	}
	
	/**
	 * update option
	 */
	public static function updateOption($option, $value, $supportMultisite = false){
		if($supportMultisite == true && is_multisite()){
			update_site_option($option, $value);
		}else
			update_option($option, $value);
	}
	
	
	/**
	 * return if the url coming from localhost
	 */
	public static function isLocal(){
		
		if(isset($_SERVER["HTTP_HOST"]) && $_SERVER["HTTP_HOST"] == "localhost")
			return(true);
		
		return(false);
	}
	
	
	
}
?>