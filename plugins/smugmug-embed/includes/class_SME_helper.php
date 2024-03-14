<?PHP
/**
 * Handles custom functionality for SME.
 *
 * @author     Tracy Wicklund <twicklund@tampabay.rr.com>
 * @copyright  Copyright (c) 2009 - 2018, Tracy Wicklund
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
/**
 *
 * @since  1.0.0
 * @access public
 */
final class SME_Helper {
	protected $LICENSE_TYPE ;
	protected $LICENSE_KEY;
	protected $LICENSE_EMAIL;
	protected $LICENSE_EXPIRATION;
	protected $LICENSE_MANAGER;
	protected $LICENSE_RENEWAL_LINK;
	public function __construct($license_manager) {		
		$this->LICENSE_MANAGER=$license_manager;
		add_action( 'init', array( $this, 'init_plugin' ) );
		add_action( 'admin_enqueue_scripts',  array( $this, 'init_plugin' ) );
        add_action( 'wp_ajax_build_folder_list', array( $this, 'build_folder_list' ) ); 
        add_action( 'wp_ajax_nopriv_build_folder_list', array( $this, 'build_folder_list' ) );
        add_action( 'wp_ajax_getBreadcrumbs', array( $this, 'getBreadcrumbs' ) ); 
        add_action( 'wp_ajax_nopriv_getBreadcrumbs', array( $this, 'getBreadcrumbs' ) );	
        add_action( 'wp_ajax_saveSelectedAlbums', array( $this, 'saveSelectedAlbums' ) ); 
        add_action( 'wp_ajax_nopriv_saveSelectedAlbums', array( $this, 'saveSelectedAlbums' ) );			
        add_action( 'wp_ajax_SME_loadSelectedAlbums', array( $this, 'SME_loadSelectedAlbums' ) ); 
        add_action( 'wp_ajax_nopriv_SME_loadSelectedAlbums', array( $this, 'SME_loadSelectedAlbums' ) );
		add_action( 'wp_ajax_addAlbumToSelected', array( $this, 'addAlbumToSelected' ) ); 
        add_action( 'wp_ajax_nopriv_addAlbumToSelected', array( $this, 'addAlbumToSelected' ) );
		add_action( 'wp_ajax_SME_LoadSelectedImagesFromGallery', array( $this, 'SME_LoadSelectedImagesFromGallery' ) ); 
        add_action( 'wp_ajax_nopriv_SME_LoadSelectedImagesFromGallery', array( $this, 'SME_LoadSelectedImagesFromGallery' ) );
		add_action( 'wp_ajax_SME_getImageInfo', array( $this, 'SME_getImageInfo' ) ); 
        add_action( 'wp_ajax_nopriv_SME_getImageInfo', array( $this, 'SME_getImageInfo' ) );
		$this->LICENSE_RENEWAL_LINK = "https://www.wicklundphotography.com/smugmug-embed-wordpress-plugin/";
		$this->update_license();
add_filter('plugin_row_meta',array($this,'SME_hide_view_details'),10,4);
		

	}	
function SME_hide_view_details($plugin_meta, $plugin_file, $plugin_data, $status)
{
  if($this->getLicenseType()!="DEMO" && isset($plugin_data['slug'])&& $plugin_data['slug'] == 'smugmug-embed')
    $plugin_meta[2] = '<a href="https://www.wicklundphotography.com/smugmug-embed-wordpress-plugin/">Visit plugin site</a>';
  return $plugin_meta;
}	
	public function update_license($forceUpdate =false) {
		$SME_License = get_option('SME_License');
	if (false === ($license = get_transient('sme_license_expiration')) || $forceUpdate ) {
			$info = $this->LICENSE_MANAGER->get_license_info();
			if ($info &&!property_exists($info,'error') &&is_object($info)){
				set_transient('sme_license_expiration',$info->license, 60 * 60* 24);
				if ($info->license->active) $SME_License['LicenseType']="Active";
				else $SME_License['LicenseType']="Expired";
				$SME_License['LicenseExpiration'] = $info->license->expires;
				$this->LICENSE_ERROR = "";
			}  else if ($info && property_exists($info,'error')) {
				$expiration = $info->error->expires;
				if ($expiration ) {
					$this->LICENSE_ERROR = $info->error;
					$SME_License['LicenseType']="Expired";
					$SME_License['LicenseExpiration'] = $expiration;
				}else {
					$this->LICENSE_ERROR = $info->error;
					$SME_License['LicenseType']="License Error";
					$SME_License['LicenseExpiration'] = "Invalid License";
				}

			}
			update_option('SME_License',$SME_License);
		}		
			//assign license variables

		$this->LICENSE_TYPE = isset($SME_License['LicenseType']) ?$SME_License['LicenseType'] : "DEMO";
		$this->LICENSE_KEY = isset($SME_License['license_key']) ?$SME_License['license_key'] : "";
		$this->LICENSE_EXPIRATION = isset($SME_License['LicenseExpiration']) ?$SME_License['LicenseExpiration'] : "";
		$this->LICENSE_EMAIL = isset($SME_License['license_email']) ?$SME_License['license_email'] : "";
		
	}
	public function getLicenseType() {
		return $this->LICENSE_TYPE ;
	}
	public function getLicenseRenewalLink() {
		return $this->LICENSE_RENEWAL_LINK;
	}
	public function getLicenseKey() {
		return $this->LICENSE_KEY ;
	}
	public function getLicenseEmail() {
		return $this->LICENSE_EMAIL ;
	}
	public function getLicenseExpiration() {
		return $this->LICENSE_EXPIRATION ;
	}	
	private function setLicenseType($license) {
		$this->LICENSE_TYPE = $license;
		return true;
	}
		
	function SME_getImageInfo() {
		global $SME_api;
		$imageId = $_POST['imageId'];
		$imageUri = $_POST['imageUri'];
		$galleryId = $_POST['galleryId'];
		$imageData=$SME_api->get($imageUri.'?_expand=ImageAlbum,ImageSizes.ImageSizeMedium,ImageMetadata,EmbedVideo&_expandmethod=inline&verbosity=1');
		wp_send_json_success($imageData);
	}
	public function addAlbumToSelected() {
		global $SME_api;
		$galleryvalue = $_POST['galleryvalue'];
		wp_send_json_success($this->paintGalleryHTML($SME_api,$galleryvalue,true));
	}
	function saveSelectedAlbums() {
		
		if (isset($_POST['selectedAlbums']))
			$updated = update_option("SME_SelectedAlbums",$_POST['selectedAlbums']);
		else 
			$updated = update_option("SME_SelectedAlbums","");
		wp_send_json_success("Selected albums stored successfully.");
	}
	function SME_loadSelectedAlbums() {
		global $SME_api;
		$mode="admin";
		if (isset($_POST['mode'])) $mode = $_POST['mode'];
		$selectedAlbums = get_option('SME_SelectedAlbums');
		//if (!$selectedAlbums) $this->getAllAlbums($SME_api);
		if (!$selectedAlbums)wp_send_json_error(array ( 'message'=>'No albums in database.'));
		$retstr ="<div style='width:100%;text-align:right;font-size:12px;padding-right:10px;padding-top:2px;' ><span onclick='this.parentNode.parentNode.remove(this);' style='cursor: pointer; border:1px solid #72777c;padding:3px 6px 3px 5px;color:#72777c;'>X</div>";
		if ($mode =="admin") $retstr="";
		foreach ($selectedAlbums as $album) {
			$retstr .= $this->paintGalleryHTML($SME_api,$album,true,$mode);
		}
		wp_send_json_success($retstr);
	}

	public function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
	public function init_plugin()
    {
        wp_enqueue_script('ajax_script', plugins_url( '/SME_SmugMugEmbed.js',__FILE__ ), array('jquery'), TRUE);
        wp_localize_script('ajax_script','SME_Ajax', array('url'   => admin_url( 'admin-ajax.php' ),'nonce' => wp_create_nonce( "build_folder_list_nonce" )));
		
    }
    public function build_folder_list()
    {
		global $SME_api;
        check_ajax_referer( 'build_folder_list_nonce', 'nonce' );
		$folder=null;
		$selectedAlbums= null;
		if (isset($_POST['nodeId'])) $folder = $_POST['nodeId'];
		if (isset($_POST['selectedAlbums'])) $selectedAlbums = $_POST['selectedAlbums'];

        if( true ){
            wp_send_json_success( $this->getGalleryFoldersHTML($SME_api,$folder,$selectedAlbums) );
		}
        else
            wp_send_json_error( array( 'error' => $custom_error ) );
    }

	public static function getAvailableSizes() {
		return array (
		'Ti' =>'Tiny (100x100)',
		'Th' =>'Thumbnail (150x150)',
		'S' =>'Small (400x300)',
		'M' =>'Medium (600x450) ',
		'L' =>'Large (800x600)',
		'XL' =>'XLarge (1024x768)',
		'X2' =>'X2Large (1280x960)',
		'X3' =>'X3Large (1600x1200)',
		'X4' =>'X4Large (2048x2048)',
		'X5' =>'X5Large (2560x2560)',
		'4k' =>'4k (3840x3840)'		,
		'5k' =>'5k (5120x5120)'		,
		'O' =>'Original (Huge x Huge)'		
		);
	}
	

	public static function getAvailableClickResponses() {
		return array (
			'none' =>'No Link',
			'image' =>'Image File',
			'smugmug_cart' =>'SmugMug Shopping Cart',
			'smugmug_gallery' =>'SmugMug Gallery',
			'smugmug_lightbox' =>'SmugMug Lightbox'
		);
	}   
	function getGalleryPaginationHTML($arr,$SME_api) {
		$username = $SME_api->get('!authuser')->User->NickName;
		$baseURI = "user/{$username}!albums";
		$total = $arr->Pages->Total;
		$requestedCount = $arr->Pages->RequestedCount;
		$start = $arr->Pages->Start;
		$numPages = intval( $total / $requestedCount);
		$numPages = ($total % $requestedCount > 0) ? $numPages +1 : $numPages;
		$currentPage = intval($start / $requestedCount);
		$currentPage = ($start % $requestedCount > 0) ? $currentPage +1 : $currentPage;
		$firstURL = $arr->Pages->FirstPage;
		$nextURL = $arr->Pages->NextPage;
		$prevURL = $arr->Pages->PrevPage;
		$lastURL = $arr->Pages->LastPage;
		$retVal = "<div class='SME_Pagination_Holder' id='SME_pagination_Holder'>";
		if (isset($firstURL)) $retVal.="<a href='javascript:SME_CallSmugMug(\"".$firstURL."\")' class='SME_Pagination_First'> << </a>";
		if (isset($prevURL)) $retVal.="<a href='javascript:SME_CallSmugMug(\"".$prevURL."\")' class='SME_Pagination_Prev'> < Previous</a>";
		for ($i=1;$i<=$numPages;$i++) {
			$thisURI = $baseURI."&start=". strval( (($i-1) * $requestedCount) +1) ."&count=".$requestedCount;
			$retVal.= "<span class='SME_PaginationNumber". (($currentPage  ==$i) ? " SME_PaginationCurrentNumber'>": "'>")."<a href='javascript:SME_CallSmugMug(\"".$thisURI."\")'>".$i."</a></span>";
		}
		if (isset($nextURL)) $retVal.="<a href='javascript:SME_CallSmugMug(\"".$nextURL."\")' class='SME_Pagination_Next'> Next> </a>";
		if (isset($lastURL)) $retVal.="<a href='javascript:SME_CallSmugMug(\"".$lastURL."\")' class='SME_Pagination_Last'> >> </a>";

		$retVal.= "</div>";
		return $retVal;
	}

	function getBreadcrumbs($SME_api=null,$folder=null) {
		if (!$SME_api) global $SME_api;
		if (isset($_POST['nodeId'])) $folder = $_POST['nodeId'];
		$retstr ='<li><a class="SME_button"  onclick="SME_updateAdminFolders(this)"  id="">Home</a></li>';
		if ($folder) {
			$userFolder="/api/v2/node/".$folder;
			$parents = $SME_api->get($userFolder ."!parents");
			$pages = $parents->Pages;
			$arr = $parents->Node;
			$par = array_reverse($arr);
			foreach  ( $par as $parentObject ) {
				$nodeId =$parentObject->NodeID;
				$parentName = $parentObject->Name;
				$isRoot = $parentObject->IsRoot;
				if ($isRoot ) continue;
				$retstr.= '<li class="SME_breadcrumb-item SME_breadcrumb-separator"><span class="SME_fonticon SME_fonticon-small SME_fonticon-AngleBracketRight" id="yui_3_8_0_1_1582297994914_12614"></span></li><li class="SME_breadcrumb-item"><a  class="SME_button" onclick="SME_updateAdminFolders(this)" id="'.$nodeId.'" >'.$parentName.'</a></li>';
			}
		}
		if (isset($_POST['refresh'])) wp_send_json_success($retstr);
		return $retstr;
	}	
	  function getGalleryAlbums($SME_api, $folder = null) {
		$selectedCount =get_option('SME_SelectedAlbums',"");
		if (is_array($selectedCount)) $selectedCount= count($selectedCount);
		else $selectedCount=0;
		$retstr= '<div id="SME_GalleryFolderChooser" style="background-color: #1c1d1f;border-top: 1px solid #3c3e43;"><div class="SME_GalleryChooserLabel"><div style="width:66.667%;"><div id="SME_spinner" class="spinner " style="float:none;width:auto;height:auto;padding:10px 0 10px 20px;background-position:00px 0;background-color:white;"></div>';
		$retstr.= '<div class="SME_picker-breadcrumb"> <ul class="SME_breadcrumb" id="SME_breadcrumb">'.$this->getBreadcrumbs($SME_api,$folder).'</ul></div></div>';
		$retstr.= '<div style="text-align:right;width:33%;"><button type="button" onclick="SME_showSelectedGalleries()" class="SME_GalleriesSelected" style="">SELECTED</button><button type="button" onclick="SME_showSelectedGalleries()" class="SME_GalleriesSelectedAccent" style=""><span id="SME_NumberGalleriesSelected">'.$selectedCount.'</span></button></div></div>';
		$retstr.= '<div style="display:inline-block;overflow-x: hidden;position: relative;overflow-y: hidden;width:100%;"><div class="SME_hiddenGalleriesSelected" id="SME_hiddenGalleriesSelected"><div  class="SME_hiddenGalleriesSelectedTogle" onclick="SME_showSelectedGalleries()"><div><span class="SME_fonticon-sml SME_fonticon-ArrowTriangleRight"</span></div></div><div id="SME_selectedGalleries" class="SME_GalleryChooser"></div></div>';
		$retstr.= '<div class=SME_GalleryChooser id="SME_GalleryChooser">';
		$retstr .= $this->getGalleryFoldersHTML($SME_api,$folder);	
		$retstr.= "</div>";
		$retstr.= "</div></div></div>";

	return $retstr;
	}	
	function getAllAlbums($SME_api) {
		$username = $SME_api->get('!authuser')->User->NickName;
		$userFolder ="user/{$username}";	
		$retAlbums = [];
		$albums =  $SME_api->get($userFolder."!albums?count=100&_verbosity=1&_filter=AlbumKey");
		foreach ($albums->Album as $album) {
				array_push($retAlbums,$album->AlbumKey);
		}
		if ($albums->Pages->NextPage)
			$nextAlbumURI = $albums->Pages->NextPage;
		while ($nextAlbumURI) {
			$albums =  $SME_api->get($nextAlbumURI);
			foreach ($albums->Album as $album) {
				array_push($retAlbums,$album->AlbumKey);
			}
			if (array_key_exists('NextPage',$albums->Pages))
				$nextAlbumURI = $albums->Pages->NextPage;
			else 
				break;
		}
		return $retAlbums;
	}
public function getGalleryFoldersHTML($SME_api,$folder,$selectedAlbums = null) {
		//$selected_gallery_nodeIds = array(get_option("SME_SmugMugEmbed_options_Galleries"));
		//$galleries_selected = 0;
		//if ($selected_gallery_nodeIds) $galleries_selected =count($selected_gallery_nodeIds);
		$username = $SME_api->get('!authuser')->User->NickName;
		$userFolder ="folder/user/{$username}";
		if (!$selectedAlbums) $selectedAlbums = get_option("SME_SelectedAlbums");
		if ($folder) {
			$userFolder="/api/v2/folder/id/".$folder;
		}
		$folders = $SME_api->get($userFolder."?_expandmethod=inline&_verbosity=1&_expand=Folders.HighlightImage,FolderAlbums.HighlightImage");	
		$retstr= '<div class="SME_AlbumPicker SME_AlbumPicker-view-large-thumbs" style="height: 385px;" ><div class="SME_picker_list" style="height: 385px;"><div id="SME_picker_list" class="SME_picker_list_content SME_common_ui_picker">';
		if ( property_exists($folders->Folder->Uris->Folders,"Folder")) {
			foreach ( $folders->Folder->Uris->Folders->Folder as $foldervalue ) {
				$folderName = html_entity_decode($foldervalue->Name);
				$albumImageURL = "";
				if ( property_exists($foldervalue->Uris->HighlightImage,"Image"))
					$albumImageURL = $foldervalue->Uris->HighlightImage->Image->ThumbnailUrl;
				//$albumImageURL = $albumImage->Image->ThumbnailUrl;
				$modifiedDate = date_format(new DateTime($foldervalue->DateModified),"Y-m-d");
				$retstr .='<div onclick="doubleclick(this)" class="yui3-widget SME_thumbnail sm-thumbnail-content sm-thumbnail-size-ti SME_common_ui_picker_u" id="'.html_entity_decode($foldervalue->NodeID).'"  data-id="'.html_entity_decode($foldervalue->NodeID).'" data-type="Folder"><a  class="SME_thumbnail_content" title="'.$foldervalue->Name.'"> <div class="SME_thumbnail-thumb">';
				$retstr .= '<span class="SME_fonticon SME_fonticon-Folder" ></span>';
				$retstr .= '<div class="SME_thumbnail-thumb-image" ><img  src="'. plugins_url( "/images/spacer.gif",__FILE__ ).'" class="SME_image-img" style="background: rgba(0, 0, 0, 0) url('.$SME_api->signResource($albumImageURL).') no-repeat scroll center center / cover; width: 75px; height: 75px;"/></div></div>';
				$retstr .= '<div class="SME_thumbnail_title" >'.$foldervalue->Name.'</div>';
				$retstr .= '<div class="SME_thumbnail_date" >Modified: '.$modifiedDate.'</div>';
				$retstr .= '</a></div>';
			}
		}
		//foreach ( $folders->Album as $galleryvalue ) {
		if ( property_exists($folders->Folder->Uris->FolderAlbums,"Album")) {
			foreach ( $folders->Folder->Uris->FolderAlbums->Album as $galleryvalue ) {
				$key=false;
				if ($selectedAlbums)
					$key = array_search($galleryvalue->NodeID, array_column($selectedAlbums, 'nodeID'));
				$retstr .= $this->paintGalleryHTML($SME_api,$galleryvalue,is_integer($key)?true:false);
			}
		}
		return $retstr;
	}

function paintGalleryHTML($SME_api,$galleryvalue,$selected = false,$mode=null) {
		$scriptName = "SME_ChangeState";
		if ($mode == "frontend") {
			$selected=false;
			$scriptName="SME_LoadAlbum";
		}
		if (is_array($galleryvalue)) {
			$nodeid=$galleryvalue["nodeID"];
			$albumkey=$galleryvalue["datakey"];
			$title=$galleryvalue["title"];
			$albumImageURL= "";
			$modifiedDate = $galleryvalue["modifieddate"];
			$albumImageURL = $galleryvalue["imgUrl"];
		} else {
			$nodeid=$galleryvalue->NodeID;
			$albumkey=$galleryvalue->AlbumKey;
			$title=$galleryvalue->Title;
			$albumImageURL= "";
			$modifiedDate = date_format(new DateTime($galleryvalue->LastUpdated),"Y-m-d");
			if ( property_exists($galleryvalue->Uris->HighlightImage,"Image"))
				$albumImageURL = $galleryvalue->Uris->HighlightImage->Image->ThumbnailUrl;
		}
		$highlightClassName = "";
		if ($selected) $highlightClassName = "SME_thumbnail_album_active";
		$retstr ='<div id="'.html_entity_decode($nodeid).'" onclick="'.$scriptName.'(this)" class="yui3-widget SME_thumbnail SME_thumbnail-album  SME_common_ui_picker_u '.$highlightClassName .'"  data-key="'.$albumkey .'" data-nodeid="'.html_entity_decode($nodeid).'" data-type="Album" data-modified="'.$modifiedDate .'"><a  class="SME_thumbnail_content" title="'.$title.'"> <div class="SME_thumbnail-thumb">';
		$retstr .='<div class="SME_thumbnail-thumb-ghost1" style="margin-top: -8px; height: 110px; margin-left: 8px; width: 110px;"></div>';
		$retstr .='<div class="SME_thumbnail-thumb-ghost2" style="margin-top: -4px; height: 110px; margin-left: 4px; width: 110px;"></div>';
		$retstr .= '<div class="SME_thumbnail-thumb-image" id="yui_3_8_0_1_1582139634775_12198" style="margin-top: 0px; height: 110px; margin-left: 0px; width: 110px;"><img id="imgid-'.html_entity_decode($nodeid).'" src="'. plugins_url( "/images/spacer.gif",__FILE__ ).'" class="SME_image-img" style="background: rgba(0, 0, 0, 0)  no-repeat scroll center center / cover; width: 100px; height: 100px;background-image:url('.$SME_api->signResource($albumImageURL).');"></div></div>';
		$retstr .= '<div class="SME_thumbnail-title">';
		$retstr .= '<div class="SME_thumbnail_title" >'.$title.'</div>';
		$retstr .= '<div class="SME_thumbnail_date" >Modified: '.$modifiedDate.'</div>';			
		$retstr .= '</div></a></div>';
		
		return $retstr;
	}	
function SME_LoadSelectedImagesFromGallery() {
		global $SME_api;
		if (isset($_POST['galleryNode']))$galleryNode = $_POST['galleryNode'];
		$images = $SME_api->get("/api/v2/album/".$galleryNode."!images");
		$retstr ="<div style='width:100%;text-align:right;font-size:12px;padding-right:10px;padding-top:2px;' ><span onclick='this.parentNode.parentNode.remove(this);' style='cursor: pointer; border:1px solid #72777c;padding:3px 6px 3px 5px;color:#72777c;'>X</div>";		foreach ($images->AlbumImage as $imageValue) {
			$retstr.= $this->paintImageHTML($SME_api,$imageValue,$galleryNode);
		}
		wp_send_json_success($retstr);
}	
function paintImageHTML($SME_api,$imageValue,$galleryNode =null) {
		$scriptName="SME_loadImage";
		$nodeid=$imageValue->ImageKey;
		$nodeuri=$imageValue->Uris->Image;
		$fileName = $imageValue->FileName;
		$albumImageURL= "";
		$modifiedDate = date_format(new DateTime($imageValue->LastUpdated),"Y-m-d");
		$albumImageURL = $imageValue->ThumbnailUrl;
		$highlightClassName = "";
		$selected="";
		if ($selected) $highlightClassName = "SME_thumbnail_album_active";
		$retstr ='<div id="'.html_entity_decode($nodeid).'" onclick="'.$scriptName.'(this)" class="yui3-widget SME_thumbnail SME_thumbnail-album  SME_common_ui_picker_u '.$highlightClassName .'" data-galleryid="'.$galleryNode.'" data-nodeid="'.html_entity_decode($nodeid).'" data-type="Image" data-modified="'.$modifiedDate .'" data-uri="'.$nodeuri.'" ><a  class="SME_thumbnail_content" > <div class="SME_thumbnail-thumb">';
		$retstr .= '<div class="SME_thumbnail-thumb-image" id="yui_3_8_0_1_1582139634775_12198" style="margin-top: 0px; height: 110px; margin-left: 0px; width: 110px;"><img id="imgid-'.html_entity_decode($nodeid).'" src="'. plugins_url( "/images/spacer.gif",__FILE__ ).'" class="SME_image-img" style="background: rgba(0, 0, 0, 0)  no-repeat scroll center center / cover; width: 100px; height: 100px;background-image:url('.$SME_api->signResource($albumImageURL).');"></div></div>';
		$retstr .= '<div class="SME_thumbnail-title">';
		$retstr .= '<div class="SME_thumbnail_title" >'.$fileName.'</div>';
		$retstr .= '<div class="SME_thumbnail_date" >Modified: '.$modifiedDate.'</div>';			
		$retstr .= '</div></a></div>';
		
		return $retstr;
	}	
		
	// add the tab

}
?>