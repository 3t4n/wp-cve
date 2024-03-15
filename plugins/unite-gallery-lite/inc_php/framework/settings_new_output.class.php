<?php
/**
 * @package Unite Gallery Lite
 * @author Valiano
 * @copyright (C) 2022 Unite Gallery, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNITEGALLERY_INC') or die('Restricted access');


	class UniteSettingsOutputUGNEW extends HtmlOutputBaseUG{
		
		protected static $arrIDs = array();
		
		protected $arrSettings = array(); 
		protected $settings;
		protected $formID;
		
		protected static $serial = 0;
		
		protected $showDescAsTips = false;
		protected $wrapperID = "";
		protected $addCss = "";
		protected $settingsMainClass = "";
		protected $isParent = false;		//variable that this class is parent
		protected $isSidebar = false;
		
		const INPUT_CLASS_NORMAL = "unite-input-regular";
		const INPUT_CLASS_NUMBER = "unite-input-number";
		const INPUT_CLASS_ALIAS = "unite-input-alias";
		const INPUT_CLASS_LONG = "unite-input-long";
		const INPUT_CLASS_SMALL = "unite-input-small";
		
		//saps related variables
		
		protected $showSaps = false;
		protected $sapsType = null;
		protected $activeSap = 0;		
		
		const SAPS_TYPE_INLINE = "saps_type_inline";	//inline sapts type
		const SAPS_TYPE_CUSTOM = "saps_type_custom";	//custom saps tyle
	    const SAPS_TYPE_ACCORDION = "saps_type_accordion";
		
	    
		/**
		 * 
		 * init the output settings
		 */
		public function init(UniteSettingsUGNEW $settings){
			
			if($this->isParent == false)
				UniteFunctionsUG::throwError("The output class must be parent of some other class.");
			
			$this->settings = new UniteSettingsUGNEW();
			$this->settings = $settings;
		}
		
		
		/**
		 * validate that the output class is inited with settings
		 */
		protected function validateInited(){
			if(empty($this->settings))
				UniteFunctionsUG::throwError("The output class not inited. Please call init() function with some settings class");
		}
		
		
		/**
		 * set add css. work with placeholder
		 * [wrapperid]
		 */
		public function setAddCss($css){
		
			$replace = "#".$this->wrapperID;
			$this->addCss = str_replace("[wrapperid]", $replace, $css);
		}
		
		/**
		 *
		 * set show descriptions as tips true / false
		 */
		public function setShowDescAsTips($show){
			$this->showDescAsTips = $show;
		}
		
		
		/**
		 *
		 * show saps true / false
		 */
		public function setShowSaps($show = true, $type = null){
		        
			if($type === null)
				$type = self::SAPS_TYPE_INLINE;
			
			$this->showSaps = $show;
						
			
			switch($type){
				case self::SAPS_TYPE_CUSTOM:
				case self::SAPS_TYPE_INLINE:
				case self::SAPS_TYPE_ACCORDION:
				break;
				default:
					UniteFunctionsUG::throwError("Wrong saps type: $type ");
				break;
			}
			
			$this->sapsType = $type;
			
		}
		
		
		/**
		 * get default value add html
		 * @param $setting
		 */
		protected function getDefaultAddHtml($setting, $implodeArray = false){
			
			$defaultValue = UniteFunctionsUG::getVal($setting, "default_value");
			if(is_array($defaultValue))
				$defaultValue = json_encode($defaultValue);
			
			$defaultValue = htmlspecialchars($defaultValue);
			
			//UniteFunctionsUG::showTrace();exit();
			
			$value = UniteFunctionsUG::getVal($setting, "value");
			if(is_array($value) || is_object($value)){
				if($implodeArray == false)
					return("");
				else
					$value = implode(",", $value);
			}
						
			$value = htmlspecialchars($value);
			
			$addHtml = " data-default=\"{$defaultValue}\" data-initval=\"{$value}\" ";
			
			$addParams = UniteFunctionsUG::getVal($setting, UniteSettingsUGNEW::PARAM_ADDPARAMS);
			if(!empty($addParams))
				$addHtml .= " ".$addParams;
			
			return($addHtml);
		}
		
		
		/**
		 * prepare draw setting text
		 */
		protected function drawSettingRow_getText($setting){
		
			//modify text:
			$text = UniteFunctionsUG::getVal($setting, "text", "");
			
			if(empty($text))
				return("");
				
			// prevent line break (convert spaces to nbsp)
			$text = str_replace(" ","&nbsp;",$text);
		
			switch($setting["type"]){
				case UniteSettingsUGNEW::TYPE_CHECKBOX:
					$text = "<label for='".$setting["id"]."' style='cursor:pointer;'>$text</label>";
					break;
			}
		
			return($text);
		}
		
		
		/**
		 *
		 * get text style
		 */
		protected function drawSettingRow_getTextStyle($setting){
		
			//set text style:
			$textStyle = UniteFunctionsUG::getVal($setting, UniteSettingsUGNEW::PARAM_TEXTSTYLE);
		
			if($textStyle != "")
				$textStyle = "style='".$textStyle."'";
		
			return($textStyle);
		}
		
		
		/**
		 * get row style
		 */
		protected function drawSettingRow_getRowHiddenClass($setting){
			
			//set hidden			
			$isHidden = isset($setting["hidden"]);
			
			if($isHidden == true && $setting["hidden"] === "false")
				$isHidden = false;
			
			//operate saps
			if($this->showSaps == true && $this->sapsType == self::SAPS_TYPE_INLINE){
				
				$sap = UniteFunctionsUG::getVal($setting, "sap");
				$sap = (int)$sap;
				
				if($sap != $this->activeSap)
					$isHidden = true;
			}

			$class = "";
			if($isHidden == true)
				$class = "unite-setting-hidden";
			
			return($class);
		}
		
		
		/**
		 *
		 * get row class
		 */
		protected function drawSettingRow_getRowClass($setting, $basClass = ""){
			
			//set text class:
			$class = $basClass;
			
			if(isset($setting["disabled"])){
				if(!empty($class))
					$class .= " ";
				
				$class .= "setting-disabled";
			}
			
			//add saps class
			if($this->showSaps && $this->sapsType == self::SAPS_TYPE_INLINE){
				
				$sap = UniteFunctionsUG::getVal($setting, "sap");
				$sap = (int)$sap;
				$sapClass = "unite-sap-element unite-sap-".$sap;
				
				if(!empty($class))
					$class .= " ";
				
				$class .= $sapClass;
			}
			
			$showin = UniteFunctionsUG::getVal($setting, "showin");
			if(!empty($showin)){
				if(!empty($class))
					$class .= " ";
				
				$class .= "uc-showin-{$showin}";
			}
				
			$classHidden = $this->drawSettingRow_getRowHiddenClass($setting);
			if(!empty($classHidden)){
				
				if(!empty($class))
					$class .= " ";
				
				$class .= $classHidden;
			}
			
			if(!empty($class))
				$class = "class='{$class}'";
			
				
			return($class);
		}
		
		
		
		
		/**
		* draw after body additional settings accesories
		*/
		public function drawAfterBody(){
			$arrTypes = $this->settings->getArrTypes();
			foreach($arrTypes as $type){
				switch($type){
					case self::TYPE_COLOR:
						?>
							<div id='divPickerWrapper' style='position:absolute;display:none;'><div id='divColorPicker'></div></div>
						<?php
					break;
				}
			}
		}
				
		
		/**
		 * 
		 * do some operation before drawing the settings.
		 */
		protected function prepareToDraw(){
			
			$this->settings->setSettingsStateByControls();
			$this->settings->setPairedSettings();
		}


		/**
		 * get setting class attribute
		 */
		protected function getInputClassAttr($setting, $defaultClass="", $addClassParam="", $wrapClass = true){
			
			$class = UniteFunctionsUG::getVal($setting, "class", $defaultClass);
			$classAdd = UniteFunctionsUG::getVal($setting, UniteSettingsUGNEW::PARAM_CLASSADD);
			
			switch($class){
				case "alias":
					$class = self::INPUT_CLASS_ALIAS;
				break;
				case "long":
					$class = self::INPUT_CLASS_LONG;
				break;
				case "normal":
					$class = self::INPUT_CLASS_NORMAL;
				break;
				case "number":
					$class = self::INPUT_CLASS_NUMBER;
				break;
				case "small":
					$class = self::INPUT_CLASS_SMALL;
				break;
				case "nothing":
					$class = "";
				break;
			}
			
			if(!empty($classAdd)){
				if(!empty($class))
					$class .= " ";
				$class .= $classAdd;
			}
			
			if(!empty($addClassParam)){
				if(!empty($class))
					$class .= " ";
				$class .= $addClassParam;
			}
			
			$isTransparent = UniteFunctionsUG::getVal($setting, UniteSettingsUGNEW::PARAM_MODE_TRANSPARENT);
			if(!empty($isTransparent)){
				if(!empty($class))
					$class .= " ";
				$class .= "unite-setting-transparent";
			}
			
			if(!empty($class) && $wrapClass == true)
				$class = "class='$class'";
			
			return($class);
		}
		
		
		
		
		/**
		 * modify image setting values
		 */
		protected function modifyImageSetting($setting){
			
			$value = UniteFunctionsUG::getVal($setting, "value");
			$value = trim($value);
			
			$urlBase = UniteFunctionsUG::getVal($setting, "url_base", null);
			
			if(!empty($value) && is_numeric($value) == false)
				$value = HelperUG::URLtoFull($value, $urlBase);
			
			$defaultValue = UniteFunctionsUG::getVal($setting, "default_value");
			$defaultValue = trim($defaultValue);
			
			if(!empty($defaultValue) && is_numeric($defaultValue) == false)
				$defaultValue = HelperUG::URLtoFull($defaultValue, $urlBase);
			
			$setting["value"] = $value;
			$setting["default_value"] = $defaultValue;
			
			
			return($setting);
		}
	
		
		/**
		 * 
		 * draw imaeg input:
		 * @param $setting
		 */
		protected function drawImageInput($setting){
			
			dmp("no image input for now");
		}

		
		/**
		 *
		 * draw image input:
		 * @param $setting
		 */
		protected function drawMp3Input($setting){
			
			$previewStyle = "display:none";
		
			$setting = $this->modifyImageSetting($setting);
			
			$value = UniteFunctionsUG::getVal($setting, "value");
		
			$class = $this->getInputClassAttr($setting, "", "unite-setting-mp3-input unite-input-image");
			
			$addHtml = $this->getDefaultAddHtml($setting);
		
			//add source param
			$source = UniteFunctionsUG::getVal($setting, "source");
			if(!empty($source))
				$addHtml .= " data-source='{$source}'";
		
			?>
				<div class="unite-setting-mp3">
					<input type="text" id="<?php echo esc_attr($setting["id"])?>" name="<?php echo esc_attr($setting["name"])?>" <?php echo $class?> value="<?php echo esc_attr($value)?>" <?php echo $addHtml?> />
					<a href="javascript:void(0)" class="unite-button-secondary unite-button-choose"><?php esc_html_e("Choose", "unitegallery")?></a>
				</div>
			<?php
		}
		
		/**
		 *
		 * draw icon picker input:
		 * @param $setting
		 */
		protected function drawIconPickerInput($setting){
			
			dmp("no icon picker");
			
		}
		
		
		/**
		 * special inputs
		 */
		private function a______SPECIAL_INPUTS_____(){}
		
		
		/**
		 * draw icon picker input:
		 * @param $setting
		 */
		protected function drawMapPickerInput($setting){
			dmp("no map picker");
		}
		
		
		/**
		 * draw icon picker input:
		 * @param $setting
		 */
		protected function drawPostPickerInput($setting){
			dmp("drawPostPickerInput: function for override");
			exit();
		}
		
				
		/**
		 * draw module picker input:
		 * @param $setting
		 */
		protected function drawModulePickerInput($setting){
			dmp("drawModulePickerInput: function for override");
			exit();
		}
		
		
		/**
		 * draw color picker
		 * @param $setting
		 */
		protected function drawColorPickerInput($setting){	
			dmp("no color picker");
		}
		
		
		/**
		 * draw the editor by provider
		 */
		protected function drawEditorInput($setting){
			
			dmp("provider settings output - function to override");
			exit();
		}
		
		/**
		 * draw fonts panel - function for override
		 */
		protected function drawFontsPanel($setting){
			
			dmp("draw fonts panel - function for override");
			exit();
		}
		
		/**
		 * draw fonts panel - function for override
		 */
		protected function drawItemsPanel($setting){
			
			dmp("draw items panel - function for override");
			exit();
		}
		
		
		/**
		 * draw setting input by type
		 */
		protected function drawInputs($setting){
			
			switch($setting["type"]){
				case UniteSettingsUGNEW::TYPE_TEXT:
					$this->drawTextInput($setting);
				break;
				case UniteSettingsUGNEW::TYPE_COLOR:
					$this->drawColorPickerInput($setting);
				break;
				case UniteSettingsUGNEW::TYPE_SELECT:
					$this->drawSelectInput($setting);
				break;
				case UniteSettingsUGNEW::TYPE_MULTISELECT:
					$this->drawMultiSelectInput($setting);
				break;
				case UniteSettingsUGNEW::TYPE_CHECKBOX:
					$this->drawCheckboxInput($setting);
				break;
				case UniteSettingsUGNEW::TYPE_RADIO:
					$this->drawRadioInput($setting);
				break;
				case UniteSettingsUGNEW::TYPE_TEXTAREA:
					$this->drawTextAreaInput($setting);
				break;
				case UniteSettingsUGNEW::TYPE_IMAGE:
					$this->drawImageInput($setting);
				break;
				case UniteSettingsUGNEW::TYPE_MP3:
					$this->drawMp3Input($setting);
				break;
				case UniteSettingsUGNEW::TYPE_ICON:
					$this->drawIconPickerInput($setting);
				break;
				case UniteSettingsUGNEW::TYPE_ADDON:
					$this->drawAddonPickerInput($setting);
				break;
				case UniteSettingsUGNEW::TYPE_MAP:
					$this->drawMapPickerInput($setting);
				break;
				case UniteSettingsUGNEW::TYPE_POST:
					$this->drawPostPickerInput($setting);
				break;
				case UniteSettingsUGNEW::TYPE_EDITOR:
					$this->drawEditorInput($setting);
				break;
				case UniteSettingsUGNEW::TYPE_FONT_PANEL:
					$this->drawFontsPanel($setting);
				break;
				case UniteSettingsUGNEW::TYPE_ITEMS:
					$this->drawItemsPanel($setting);
				break;
				case UniteSettingsUGNEW::TYPE_BUTTON:
					$this->drawButtonInput($setting);
				break;
				case UniteSettingsUGNEW::TYPE_RANGE:
					$this->drawRangeSliderInput($setting);
				break;
				case UniteSettingsUGNEW::TYPE_HIDDEN:
					$this->drawHiddenInput($setting);
				break;
				case UniteSettingsUGNEW::TYPE_REPEATER:
					
					$this->drawRepeaterInput($setting);
					
				break;
				case UniteSettingsUGNEW::TYPE_TYPOGRAPHY:
					
					$this->drawTypographySetting($setting);
					
				break;
				case UniteSettingsUGNEW::TYPE_DIMENTIONS:
					
					$this->drawDimentionsSetting($setting);
					
				break;
				case UniteSettingsUGNEW::TYPE_POST_SELECT:
					
					$this->drawPostSelectInput($setting);
					
				break;
				case UniteSettingsUGNEW::TYPE_CUSTOM:
					if(method_exists($this,"drawCustomInputs") == false){
						UniteFunctionsUG::throwError("Method don't exists: drawCustomInputs, please override the class");
					}
					$this->drawCustomInputs($setting);
				break;
				default:
					throw new Exception("drawInputs error: wrong setting type - ".$setting["type"]);
				break;
			}
			
		}		
		
		
		/**
		 * draw text input
		 * @param $setting
		 */
		protected function drawRangeSliderInput($setting) {
			
			
			$setting[UniteSettingsUGNEW::PARAM_CLASSADD] = "unite-setting-range";
			$setting["class"] = "nothing";
			$setting["type_number"] = true;
			
			$value = UniteFunctionsUG::getVal($setting, "value");
			
			$min = UniteFunctionsUG::getVal($setting, "min");
			$max = UniteFunctionsUG::getVal($setting, "max");
			$step = UniteFunctionsUG::getVal($setting, "step");
			
			if(empty($step))
				$step = 1;
			
			if($min === "" || is_numeric($min) == false)
				UniteFunctionsUG::throwError("range error: should be min value");
			
			if($max === "" || is_numeric($max) == false)
				UniteFunctionsUG::throwError("range error: should be max value");
			
			$defaultValue = UniteFunctionsUG::getVal($setting, "default_value");
			
			$unit = UniteFunctionsUG::getVal($setting, "range_unit");
			
			if($unit == "__hide__")
				$unit = null;
			
			?>
			<div class="unite-setting-range-wrapper">
				
				<input type="range" min="<?php echo esc_attr($min)?>" max="<?php echo esc_attr($max)?>" step="<?php echo esc_attr($step)?>" value="<?php echo esc_attr($value)?>" >
			<?php 
					
				$this->drawTextInput($setting);
				
				if(!empty($unit)):
				?>
				<span class="setting_unit"><?php echo esc_html($unit)?></span>
				<?php 
				endif;
			?>
				
			</div>
			<?php
		}
		
		
		/**
		 * draw repeater input
		 */
		protected function drawRepeaterInput($setting){
			
			$itemsValues = UniteFunctionsUG::getVal($setting, "items_values");
						
			$strData = UniteFunctionsUG::jsonEncodeForHtmlData($itemsValues, "itemvalues");
			
			$addItemText = UniteFunctionsUG::getVal($setting, "add_button_text");
			if(empty($addItemText))
				$addItemText = esc_html__("Add Item", "unitegallery");
			
			//get empty text
			$emptyText = UniteFunctionsUG::getVal($setting, "empty_text");
			
			if(empty($emptyText))
				$emptyText = esc_html__("No Items Found", "unitegallery");
			
			$objSettingsItems = UniteFunctionsUG::getVal($setting, "settings_items");
			UniteFunctionsUG::validateNotEmpty($objSettingsItems, "settings items");
			
			$emptyTextAddHtml = "";
			if(!empty($value))
				$emptyTextAddHtml = "style='display:none'";
			
			$output = new UniteSettingsOutputWideUGNEW();
			
			$output->init($objSettingsItems);
			
			//get item title
			$itemTitle = UniteFunctionsUG::getVal($setting, "item_title");
			if(empty($itemTitle))
				$itemTitle = esc_html__("Item", "unitegallery");
				
			$itemTitle = htmlspecialchars($itemTitle);
			
			//delete button text
			$deleteButtonText = UniteFunctionsUG::getVal($setting, "delete_button_text");
			if(empty($deleteButtonText))
				$deleteButtonText = esc_html__("Delete Item","unitegallery");
			
			$duplicateButtonText = UniteFunctionsUG::getVal($setting, "duplicate_button_text");
			if(empty($duplicateButtonText))
				$duplicateButtonText = esc_html__("Duplicate Item","unitegallery");
			
			$deleteButtonText = htmlspecialchars($deleteButtonText);
			$duplicateButtonText = htmlspecialchars($duplicateButtonText);
			
			
			?>
		      <div id="<?php echo esc_attr($setting["id"])?>" data-settingtype="repeater" <?php echo $strData?> class="unite-settings-repeater unite-setting-input-object" data-name="<?php echo esc_attr($setting["name"])?>" data-itemtitle='<?php echo esc_attr($itemTitle)?>' data-deletetext="<?php echo esc_attr($deleteButtonText)?>" data-duplicatext="<?php echo esc_attr($duplicateButtonText)?>" >
		      	 
		      	 <div class="unite-repeater-emptytext" <?php echo $emptyTextAddHtml?>>
		      	 	<?php echo esc_html($emptyText)?>
		      	 </div>
		      	 
		      	 <div class="unite-repeater-template" style="display:none">
		      	 	
		      	 		<?php $output->draw("settings_item_repeater", false); ?>
		      	 		
		      	 </div>
		      	 
		      	 <div class="unite-repeater-items"></div>
		      	 
		      	 <a class="unite-button-secondary unite-repeater-buttonadd" ><?php echo $addItemText?></a>
		      	 
			  </div>
			  			  
			<?php
			
		}
		
		
		/**
		 * special inputs
		 */
		private function a______REGULAR_INPUTS______(){}
		
		
		/**
		 * draw text input
		 * @param $setting
		 */
		protected function drawTextInput($setting) {
						
			$disabled = "";
			$style="";
			$readonly = "";
			
			if(isset($setting["style"])) 
				$style = "style='".$setting["style"]."'";
			if(isset($setting["disabled"])) 
				$disabled = 'disabled="disabled"';
				
			if(isset($setting["readonly"])){
				$readonly = "readonly='readonly'";
			}
			
			$defaultClass = self::INPUT_CLASS_NORMAL;
			
			$typeNumber = UniteFunctionsUG::getVal($setting, "type_number");
			$typeNumber = UniteFunctionsUG::strToBool($typeNumber);
			
			$unit = UniteFunctionsUG::getVal($setting, "unit");
			if(!empty($unit)){
				$defaultClass = self::INPUT_CLASS_NUMBER;
				if($unit == "px")
					$typeNumber = true;
			}
			
			$class = $this->getInputClassAttr($setting, $defaultClass);
			
			$addHtml = $this->getDefaultAddHtml($setting);
						
			$placeholder = UniteFunctionsUG::getVal($setting, "placeholder", null);
			
			if($placeholder !== null){
				$placeholder = htmlspecialchars($placeholder);
				$addHtml .= " placeholder=\"$placeholder\"";
			}
			
			$value = $setting["value"];
			$value = htmlspecialchars($value);
						
			$typePass = UniteFunctionsUG::getVal($setting, "ispassword");
			$typePass = UniteFunctionsUG::strToBool($typePass);
			
			//set input type
			
			$inputType = "text";
			if($typeNumber == true){
				$inputType = "number";
				$step = UniteFunctionsUG::getVal($setting, "step");
				if(!empty($step) && is_numeric($step))
					$addHtml .= " step=\"{$step}\"";
			}
			
			if($typePass === true){
				$inputType = "password";
			}
			
			?>
				<input type="<?php echo esc_attr($inputType)?>" <?php echo $class?> <?php echo $style?> <?php echo $disabled?><?php echo $readonly?> id="<?php echo esc_attr($setting["id"])?>" name="<?php echo esc_attr($setting["name"])?>" value="<?php echo esc_attr($value)?>" <?php echo $addHtml?> />
			<?php
		}
		
		
		/**
		 * draw hidden input
		 */
		protected function drawHiddenInput($setting){
			
			$value = UniteFunctionsUG::getVal($setting, "value");
			$value = htmlspecialchars($value);
			$addHtml = $this->getDefaultAddHtml($setting);
			
			?>
				<input type="hidden" id="<?php echo esc_attr($setting["id"])?>" name="<?php echo esc_attr($setting["name"])?>" value="<?php echo esc_attr($value)?>" <?php echo $addHtml?> />
			<?php 
		}
		
		
		
		/**
		 * draw button input
		 */
		protected function drawButtonInput($setting){
			
			$name = $setting["name"];
			$id = $setting["id"];
			$value = $setting["value"];
			$href = "javascript:void(0)";
			$gotoView = UniteFunctionsUG::getVal($setting, "gotoview");
			
			if(!empty($gotoView))
				$href = HelperUG::getViewUrl($gotoView);
			
			?>
			<a id="<?php echo esc_attr($id)?>" href="<?php echo esc_attr($href)?>" name="<?php echo esc_attr($name)?>" class="unite-button-secondary"><?php echo esc_html($value)?></a>
			<?php 
			
		}
		
		
		/**
		 * draw text area input
		 */
		protected function drawTextAreaInput($setting){
			
			$disabled = "";
			if (isset($setting["disabled"])) 
				$disabled = 'disabled="disabled"';
			
			$style = "";
			if(isset($setting["style"]))
				$style = "style='".$setting["style"]."'";
			
			$rows = UniteFunctionsUG::getVal($setting, "rows");
			if(!empty($rows))
				$rows = "rows='$rows'";
			
			$cols = UniteFunctionsUG::getVal($setting, "cols");
			if(!empty($cols))
				$cols = "cols='$cols'";
			
			$addHtml = $this->getDefaultAddHtml($setting);
			
			$class = $this->getInputClassAttr($setting);
			
			$value = $setting["value"];
			$value = htmlspecialchars($value);
			
			?>
				<textarea id="<?php echo esc_attr($setting["id"])?>" <?php echo $class?> name="<?php echo esc_attr($setting["name"])?>" <?php echo $style?> <?php echo $disabled?> <?php echo $rows?> <?php echo $cols?> <?php echo $addHtml?> ><?php echo $value?></textarea>
			<?php
			if(!empty($cols))
				echo "<br>";	//break line on big textareas.
		}		
		
		
		/**
		 * draw radio input
		 */
		protected function drawRadioInput($setting){
			
			$items = $setting["items"];
			$counter = 0;
			$settingID = $setting["id"];
			$isDisabled = UniteFunctionsUG::getVal($setting, "disabled");
			$isDisabled = UniteFunctionsUG::strToBool($isDisabled);
			$settingName = $setting["name"];
			$defaultValue = UniteFunctionsUG::getVal($setting, "default_value");
			$settingValue = UniteFunctionsUG::getVal($setting, "value");
			
			$class = $this->getInputClassAttr($setting);
			
			$specialDesign = UniteFunctionsUG::getVal($setting, "special_design");
			$specialDesign = UniteFunctionsUG::strToBool($specialDesign);
			
			if($this->isSidebar == false)
				$specialDesign = false;
			
			$addClass = "";
			if($specialDesign == true){
				$addClass = " unite-radio-special";
				$numItems = count($items);
				switch($numItems){
					case 2:
						$addClass .= " split-two-columns";
					break;
					case 3:
						$addClass .= " split-three-columns";
					break;
					case 4:
						$addClass .= " split-four-columns";
					break;
					default:
						$addClass = "";
					break;
				}
				
				$designColor = UniteFunctionsUG::getVal($setting, "special_design_color");
				if(!empty($designColor))
					$addClass .= " unite-radio-color-$designColor";
			
			}
			
			?>
			<span id="<?php echo esc_attr($settingID) ?>" class="radio_wrapper<?php echo esc_attr($addClass)?>">
			
			<?php 
			
			foreach($items as $text=>$value):
				$counter++;
				$radioID = $settingID."_".$counter;
				
				$classLabel = "unite-radio-item-label-$counter";
				
				$strChecked = "";				
				if($value == $settingValue) 
					$strChecked = " checked";
				
				$strDisabled = "";
				if($isDisabled)
					$strDisabled = 'disabled = "disabled"';
				
				$addHtml = "";
				if($value == $defaultValue)
					$addHtml .= " data-defaultchecked=\"true\"";
				
				if($value == $settingValue){
					$addHtml .= " data-initchecked=\"true\"";
				}
				
				$props = "style=\"cursor:pointer;\" {$strChecked} {$strDisabled} {$addHtml} {$class}";
				
				?>					
					<input type="radio" id="<?php echo esc_attr($radioID)?>" value="<?php echo esc_attr($value)?>" name="<?php echo esc_attr($settingName)?>" <?php echo $props?>/>
					<label class="<?php echo esc_attr($classLabel)?>" for="<?php echo esc_attr($radioID)?>" ><?php echo $text?></label>
					
					<?php if($specialDesign == false):?>
					&nbsp; &nbsp;
					<?php endif?>
				<?php				
			endforeach;
			
			?>
			</span>
			<?php 
		}
		
		
		/**
		 * draw checkbox
		 */
		protected function drawCheckboxInput($setting){
			
			$checked = "";
			
			$value = UniteFunctionsUG::getVal($setting, "value");
			$value = UniteFunctionsUG::strToBool($value);
			
			if($value == true) 
				$checked = 'checked="checked"';
			
				$textNear = UniteFunctionsUG::getVal($setting, "text_near");
			
			$settingID = $setting["id"];
			
			if(!empty($textNear)){
				$textNearAddHtml = "";
				if($this->showDescAsTips == true){
					$description = UniteFunctionsUG::getVal($setting, "description");
					$description = htmlspecialchars($description);
					$textNearAddHtml = " title='$description' class='uc-tip'";
				}
				
				$textNear = "<label for=\"{$settingID}\"{$textNearAddHtml}>$textNear</label>";
			}
			
			$defaultValue = UniteFunctionsUG::getVal($setting, "default_value");
			$defaultValue = UniteFunctionsUG::strToBool($defaultValue);
			
			$addHtml = "";
			if($defaultValue == true)
				$addHtml .= " data-defaultchecked=\"true\"";
			
			if($value)
				$addHtml .= " data-initchecked=\"true\"";
			
			$class = $this->getInputClassAttr($setting);
			
			?>
				<input type="checkbox" id="<?php echo esc_attr($settingID)?>" <?php echo $class?> name="<?php echo esc_attr($setting["name"])?>" <?php echo $checked?> <?php echo $addHtml?>/>
			<?php
			if(!empty($textNear))
				echo $textNear;
		}
		
		
		/**
		 * draw select input
		 */
		protected function drawSelectInput($setting){
			
			$type = UniteFunctionsUG::getVal($setting, "type");
			
			$name = UniteFunctionsUG::getVal($setting, "name");
			
			$select2 = UniteFunctionsUG::getVal($setting, "select2");
			$select2 = UniteFunctionsUG::strToBool($select2);
			
			$isMultiple = false;
			if($type == "multiselect")
				$isMultiple = true;
			
			$disabled = "";
			if(isset($setting["disabled"])) 
				$disabled = 'disabled="disabled"';
			
			$args = UniteFunctionsUG::getVal($setting, "args");
			
			$settingValue = $setting["value"];
						
			if(is_array($settingValue) == false && strpos($settingValue,",") !== false)
				$settingValue = explode(",", $settingValue);
						
			$addHtml = $this->getDefaultAddHtml($setting, true);
						
			if($isMultiple == true){
				$addHtml .= " multiple";
			}
						
			$addClass = "";
			if($select2 == true){
				$addClass = "select2";
			}
			
			$class = $this->getInputClassAttr($setting,"",$addClass);
			
			$placeholder = UniteFunctionsUG::getVal($setting, "placeholder");
			
			if(!empty($placeholder)){
				$addHtml .= " placeholder=\"$placeholder\"";				
			}

			//$addHtml .= "style='width:400px;'";
			
			$arrItems = UniteFunctionsUG::getVal($setting, "items",array());
			if(empty($arrItems))
				$arrItems = array();
			
			?>
			<select id="<?php echo esc_attr($setting["id"])?>" name="<?php echo esc_attr($setting["name"])?>" <?php echo $disabled?> <?php echo $class?> <?php echo $args?> <?php echo $addHtml?>>
			<?php
			foreach($arrItems as $text=>$value):
				
				//set selected
				$selected = "";
				$addition = "";
								
				if(is_array($settingValue)){
					if(array_search($value, $settingValue) !== false) 
						$selected = 'selected="selected"';
				}else{
					if($value == $settingValue) 
						$selected = 'selected="selected"';
				}
				
				?>
					<option <?php echo $addition?> value="<?php echo esc_attr($value)?>" <?php echo $selected?>><?php echo $text?></option>
				<?php
			endforeach
			?>
			</select>
			<?php
			
			
		}

		
		/**
		 * draw post select input
		 */
		private function drawPostSelectInput($setting){
						
			$disabled = "";
			if(isset($setting["disabled"])) 
				$disabled = 'disabled="disabled"';
			
			$args = UniteFunctionsUG::getVal($setting, "args");
			
			$addHtml = "";
			
			$addClass = "select2 uc-posts-select";
			
			$class = $this->getInputClassAttr($setting,"",$addClass);
			
			$placeholder = UniteFunctionsUG::getVal($setting, "placeholder");
			
			
			
			if(empty($placeholder))
				$placeholder = __("Select Post","unitegallery");
			
			if(!empty($placeholder)){
				$placeholder = htmlspecialchars($placeholder);
				$addHtml .= " data-placeholder='$placeholder'";
			}
						
			$isSingle = UniteFunctionsUG::getVal($setting, "single");
			$isSingle = UniteFunctionsUG::strToBool($isSingle);
			
			if($isSingle == false)
				$addHtml .= " multiple";
			
			
			?>
			<select id="<?php echo esc_attr($setting["id"])?>"  name="<?php echo esc_attr($setting["name"])?>" data-settingtype="post" <?php echo $disabled?> <?php echo $class?> <?php echo $args?> <?php echo $addHtml?>></select>
			<div class="unite-setting-loader" style='display:none;color:red;'><?php _e("Loading...")?></div>
			<?php 
			
		}
		
		
		/**
		 * draw select input
		 */
		protected function drawMultiSelectInput($setting){
			
			$this->drawSelectInput($setting);
			
		}
		
		/**
		 * draw text row
		 * @param unknown_type $setting
		 */
		protected function drawTextRow($setting){
			echo "draw text row - override this function";
		}

		
		/**
		 * draw hr row - override
		 */
		protected function drawHrRow($setting){
			echo "draw hr row - override this function";
		}
		
		
		/**
		 * draw typography setting
		 */
		protected function drawTypographySetting($setting){
			?>
			<?php _e("The typography setting will be visible in Elementor Page Builder","unitegallery");?>
			<?php 
		}
		
		/**
		 * draw dimentions setting
		 */
		protected function drawDimentionsSetting($setting){
			
			dmp("draw dimentions setting - function for override");
			// function for override
			
		}
		
		
		/**
		 * draw input additinos like unit / description etc
		 */
		protected function drawInputAdditions($setting,$showDescription = true){
			
			$description = UniteFunctionsUG::getVal($setting, "description");
			if($showDescription === false)
				$description = "";
			$unit = UniteFunctionsUG::getVal($setting, "unit");
			$required = UniteFunctionsUG::getVal($setting, "required");
			$addHtml = UniteFunctionsUG::getVal($setting, UniteSettingsUGNEW::PARAM_ADDTEXT);
			
			?>
			
			<?php if(!empty($unit)):?>
			<span class='setting_unit'><?php echo esc_html($unit)?></span>
			<?php endif?>
			<?php if(!empty($required)):?>
			<span class='setting_required'>*</span>
			<?php endif?>
			<?php if(!empty($addHtml)):?>
			<span class="settings_addhtml"><?php echo esc_html($addHtml)?></span>
			<?php endif?>					
			<?php if(!empty($description) && $this->showDescAsTips == false):?>
			<span class="description"><?php echo $description?></span>
			<?php endif?>
			
			<?php 
		}
		
				
		
		/**
		 * get options
		 */
		protected function getOptions(){
			
			$idPrefix = $this->settings->getIDPrefix();
			
			$options = array();
			$options["show_saps"] = $this->showSaps;
			$options["saps_type"] = $this->sapsType;
			$options["id_prefix"] = $idPrefix;
			
			return($options);
		}
		
		
		/**
		* set form id
		 */
		public function setFormID($formID){
			
			if(isset(self::$arrIDs[$formID]))
				UniteFunctionsUG::throwError("Can't output settings with the same ID: $formID");
			
			self::$arrIDs[$formID] = true;
			
			UniteFunctionsUG::validateNotEmpty($formID, "formID");
			
			$this->formID = $formID;
			
		}
		
		
		/**
		 *
		 * insert settings into saps array
		 */
		private function groupSettingsIntoSaps(){
		    
		    $arrSaps = $this->settings->getArrSaps();
		    $arrSettings = $this->settings->getArrSettings();
		    
		    //group settings by saps
		    foreach($arrSettings as $key=>$setting){
		        
		        $sapID = $setting["sap"];
		        
		        if(isset($arrSaps[$sapID]["settings"]))
		            $arrSaps[$sapID]["settings"][] = $setting;
		            else
		                $arrSaps[$sapID]["settings"] = array($setting);
		    }
		    		    
		    return($arrSaps);
		}
		
		
		private function a______DRAW_GENENRAL_____(){}
		
		
		/**
		 * get controls for client side
		 * eliminate only one setting in children
		 */
		private function getControlsForJS(){
			
			$controls = $this->settings->getArrControls(true);
			$arrChildren = $controls["children"];
			
			if(empty($arrChildren))
				return($controls);
			
			$arrChildrenNew = array();
			
			foreach($arrChildren as $name=>$arrChild){
				if(count($arrChild)>1)
					$arrChildrenNew[$name] = $arrChild;
			}
			
			$controls["children"] = $arrChildrenNew;
			
			return($controls);
		}
		
		
		/**
		 * draw wrapper start
		 */
		public function drawWrapperStart(){
			
			UniteFunctionsUG::validateNotEmpty($this->settingsMainClass, "settings main class not found, please use wide, inline or sidebar output");
			
			//get options
			$options = $this->getOptions();
			$strOptions = UniteFunctionsUG::jsonEncodeForHtmlData($options);
			
			//get controls
			$controls = $this->getControlsForJS();
			
			/*
			if(!empty($controls["children"])){
				dmp($controls);exit();
			}
			*/
			
			$addHtml = "";
			if(!empty($controls)){
				$strControls = UniteFunctionsUG::jsonEncodeForHtmlData($controls);
				$addHtml = " data-controls=\"{$strControls}\"";
			}
			
			
			if(!empty($this->addCss)):
			?>
				<!-- settings add css -->
				<style type="text/css">
					<?php echo $this->addCss?>
				</style>
			<?php
			endif;
			
			?>
			<div id="<?php echo esc_attr($this->wrapperID)?>" data-options="<?php echo esc_attr($strOptions)?>" <?php echo $addHtml?> autofocus="true" class="unite_settings_wrapper <?php echo $this->settingsMainClass?> unite-settings unite-inputs">
			
			<?php
		}
		
		
		/**
		 * draw wrapper end
		 */
		public function drawWrapperEnd(){
			
			?>
			
			</div>
			<?php 
		}
		
		
		/**
		 * function for override
		 */
		protected function setDrawOptions(){}
		
		/**
		 * 
		 * draw settings function
		 * @param $drawForm draw the form yes / no
		 * if filter sapid present, will be printed only current sap settings
		 */
		public function draw($formID, $drawForm = false){
			
			if(empty($this->settings))
				UniteFunctionsUG::throwError("No settings are inited. Please init the settings in output class");
			
			$this->setDrawOptions();
				
			$this->setFormID($formID);
			
			$this->drawWrapperStart();
			
			
			if($this->showSaps == true){
			     
			     switch($this->sapsType){
			         case self::SAPS_TYPE_INLINE:
			             $this->drawSapsTabs();
			         break;
			         case self::SAPS_TYPE_CUSTOM:
			             $this->drawSaps();
			         break;
			     }  
			     
			}
			
			
			if($drawForm == true){
				
				if(empty($formID))
					UniteFunctionsUG::throwError("The form ID can't be empty. you must provide it");
				
				?>
				<form name="<?php echo esc_attr($formID)?>" id="<?php echo esc_attr($formID)?>">
					<?php $this->drawSettings() ?>
				</form>
				<?php 				
			}else
				$this->drawSettings();
			
			?>
			
			<?php 
			
			$this->drawWrapperEnd();
			
		}

		
		/**
		 * draw wrapper before settings
		 */
		protected function drawSettings_before(){
		}
		
		
		/**
		* draw wrapper end after settings
		*/
		protected function drawSettingsAfter(){
		}
		

		/**
		 * draw single setting
		 */
		public function drawSingleSetting($name){
			
			$arrSetting = $this->settings->getSettingByName($name);
			
			$this->drawInputs($arrSetting);
			$this->drawInputAdditions($arrSetting);
		}
		
		
		/**
		 * function for override
		 */
		protected function drawSaps(){}
		
		
		/**
		 * draw saps tabs
		 */
		protected function drawSapsTabs(){
			
			$arrSaps = $this->settings->getArrSaps();
			
			?>
			<div class="unite-settings-tabs">
				
				<?php foreach($arrSaps as $key=>$sap){
					$text = $sap["text"];
					UniteFunctionsUG::validateNotEmpty($text,"sap $key text");
					
					$class = "";
					if($key == $this->activeSap)
						$class = "class='unite-tab-selected'";
					
					?>
					<a href="javascript:void(0)" <?php echo $class?> data-sapnum="<?php echo esc_attr($key)?>" onfocus="this.blur()"><?php echo esc_html($text)?></a>
					<?php 
					
				}
				?>
				
			</div>
			<?php 
			
		}
		
		/**
		 * draw setting row by type
		 *
		 */
		private function drawSettingsRowByType($setting, $mode){
		    		    
		    switch($setting["type"]){
		        case UniteSettingsUGNEW::TYPE_HR:
		            $this->drawHrRow($setting);
		            break;
		        case UniteSettingsUGNEW::TYPE_STATIC_TEXT:
		            $this->drawTextRow($setting);
		            break;
		        default:
		            $this->drawSettingRow($setting, $mode);
		        break;
		    }
		    
		}
		
		
		/**
		 * draw settings - all together
		 */
		private function drawSettings_settings($filterSapID = null, $mode=null, $arrSettings = null){
		    
			if(is_null($arrSettings))
				$arrSettings = $this->arrSettings;
			
		    $this->drawSettings_before();
		    
		    foreach($arrSettings as $key=>$setting){
		            
		            if(isset($setting[UniteSettingsUGNEW::PARAM_NODRAW]))
		                continue;
		                
		                if($filterSapID !== null){
		                    $sapID = UniteFunctionsUG::getVal($setting, "sap");
		                    if($sapID != $filterSapID)
		                        continue;
		                }
		                
		                $this->drawSettingsRowByType($setting, $mode);
		                
		        }
		        
		        $this->drawSettingsAfter();
		     
		}
		
		
		/**
		 * draw sap before override
		 * @param unknown $sap
		 */
		protected function drawSapBefore($sap, $key){
		    dmp("function for override");
		    
		}
		
		protected function drawSapAfter(){
		    dmp("function for override");
		}
		
		
		/**
		 * draw settings - all together
		 */
		private function drawSettings_saps($filterSapID = null, $mode=null){
		    
		    $arrSaps = $this->groupSettingsIntoSaps();
		    
		        //draw settings - advanced - with sections
		        foreach($arrSaps as $key=>$sap):
		        		
		        		$arrSettings = $sap["settings"];
		        		
		        		$nodraw = UniteFunctionsUG::getVal($sap, "nodraw");
		        		if($nodraw === true)
		        			continue;
		        		
		                $this->drawSapBefore($sap, $key);
						
						$this->drawSettings_settings($filterSapID, $mode, $arrSettings);
						
						$this->drawSapAfter();
						
		        
		        endforeach;
		    
		}
		
		
		
		/**
		 * draw all settings
		 */
		public function drawSettings($filterSapID = null){
			
			$this->prepareToDraw();
			
			$arrSettings = $this->settings->getArrSettings();
			if(empty($arrSettings))
			    $arrSettings = array();
			    
			$this->arrSettings = $arrSettings;

			//set special mode
			$mode = "";
			if(count($arrSettings) == 1 && $arrSettings[0]["type"] == UniteSettingsUGNEW::TYPE_EDITOR)
			    $mode = "single_editor";
			
			
			if($this->showSaps == true && $this->sapsType == self::SAPS_TYPE_ACCORDION)
			    $this->drawSettings_saps($filterSapID, $mode);
			else			     
			    $this->drawSettings_settings($filterSapID, $mode);
			
		  
		}
		
		
		
	}

?>