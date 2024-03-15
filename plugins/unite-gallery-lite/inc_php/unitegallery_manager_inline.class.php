<?php
/**
 * @package Unite Gallery
 * @author Valiano
 * @copyright (C) 2022 Unite Gallery, All Rights Reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

class UniteGalleryManagerInline extends UniteGalleryManager{

	private $startAddon;
	
	
	/**
	 * construct the manager
	 */
	public function __construct(){
		
		$this->type = self::TYPE_ITEMS_INLINE;
		
		$this->init();
	}
	
	/**
	 * validate that the start addon exists
	 */
	private function validateStartAddon(){
		
		if(empty($this->startAddon))
			UniteFunctionsUG::throwError("The start addon not given");
		
	}
	
	
	/**
	 * init the data from start addon
	 */
	private function initStartAddonData(){
		
		//set init data
		$arrItems = $this->startAddon->getArrItems();
		
		$strItems = "";
		if(!empty($arrItems)){
			$strItems = json_encode($arrItems);
			$strItems = htmlspecialchars($strItems);
		}
		
		$addHtml = " data-init-items=\"{$strItems}\" ";
		
		$this->setManagerAddHtml($addHtml);
		
	}
	
	
	/**
	 * set start addon
	 */
	public function setStartAddon($addon){
		$this->startAddon = new UniteCreatorAddon();	//just for code completion
		$this->startAddon = $addon;
		
		$this->initStartAddonData();
	}
	
	
	/**
	 * get single item menu
	 */
	protected function getMenuSingleItem(){
		
		$arrMenuItem = array();
		$arrMenuItem["edit_item"] = __("Edit Item","unitegallery");
		$arrMenuItem["remove_items"] = __("Delete","unitegallery");
		$arrMenuItem["duplicate_items"] = __("Duplicate","unitegallery");
		
		return($arrMenuItem);
	}

	/**
	 * get multiple items menu
	 */
	protected function getMenuMulitipleItems(){
		$arrMenuItemMultiple = array();
		$arrMenuItemMultiple["remove_items"] = __("Delete","unitegallery");
		$arrMenuItemMultiple["duplicate_items"] = __("Duplicate","unitegallery");
		return($arrMenuItemMultiple);
	}
	
	
	/**
	 * get item field menu
	 */
	protected function getMenuField(){
		$arrMenuField = array();
		$arrMenuField["add_item"] = __("Add Item","unitegallery");
		$arrMenuField["select_all"] = __("Select All","unitegallery");
		
		return($arrMenuField);
	}
	
	
	/**
	 * put items buttons
	 */
	protected function putItemsButtons(){
		
		$this->validateStartAddon();
		
		$itemType = $this->startAddon->getItemsType();
		
		//put add item button according the type
		switch($itemType){
			default:
			case UniteCreatorAddon::ITEMS_TYPE_DEFAULT:
			?>
 			<a data-action="add_item" type="button" class="unite-button-primary button-disabled ug-button-item ug-button-add"><?php _e("Add Item","unitegallery")?></a>
			<?php 
			break;
			case UniteCreatorAddon::ITEMS_TYPE_IMAGE:
			?>
 			<a data-action="add_images" type="button" class="unite-button-primary button-disabled ug-button-item ug-button-add"><?php _e("Add Images","unitegallery")?></a>
			<?php 
			break;
		}
		
		?>
	 		<a data-action="select_all_items" type="button" class="unite-button-secondary button-disabled ug-button-item ug-button-select" data-textselect="<?php _e("Select All","unitegallery")?>" data-textunselect="<?php _e("Unselect All","unitegallery")?>"><?php _e("Select All","unitegallery")?></a>
	 		<a data-action="duplicate_items" type="button" class="unite-button-secondary button-disabled ug-button-item"><?php _e("Duplicate","unitegallery")?></a>
	 		<a data-action="remove_items" type="button" class="unite-button-secondary button-disabled ug-button-item"><?php _e("Delete","unitegallery")?></a>
	 		<a data-action="edit_item" type="button" class="unite-button-secondary button-disabled ug-button-item ug-single-item"><?php _e("Edit Item","unitegallery")?> </a>
		<?php 
	}
	
	
	/**
	 * put add edit item dialog
	 */
	private function putAddEditDialog(){
		
		?>
			<div title="<?php _e("Edit Item","unitegallery")?>" class="ug-dialog-edit-item" style="display:none;">
				<div class="ug-item-config-settings">
					<?php 
						if($this->startAddon)
							$this->startAddon->putHtmlItemConfig()
					 ?>
				</div>
			</div>
		<?php 
	}
	
	
	/**
	 * put additional html here
	 */
	protected function putAddHtml(){
			
		$this->putAddEditDialog();
	
	}
	
	
	/**
	 * init the addons manager
	 */
	protected function init(){
		
		$this->hasCats = false;
		
		parent::init();
	}
	
	
}