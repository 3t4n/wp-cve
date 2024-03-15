<?php
/**
 * @package Unite Gallery Lite
 * @author Valiano
 * @copyright (C) 2022 Unite Gallery, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNITEGALLERY_INC') or die('Restricted access');


	class UniteSettingsOutputWideUGNEW extends UniteSettingsOutputUGNEW{
		
		/**
		 * constuct function
		 */
		public function __construct(){
			$this->isParent = true;
			self::$serial++;
			$this->wrapperID = "unite_settings_wide_output_".self::$serial;
			$this->settingsMainClass = "unite_settings_wide";
		}
		
		
		/**
		 * draw settings row
		 * @param $setting
		 * modes: single_editor (only 1 setting, editor type)
		 */
		protected function drawSettingRow($setting, $mode = ""){
						
			//set cellstyle:
			$cellStyle = "";
			if(isset($setting[UniteSettingsUGNEW::PARAM_CELLSTYLE])){
				$cellStyle .= $setting[UniteSettingsUGNEW::PARAM_CELLSTYLE];
			}
			
			if($cellStyle != "")
				 $cellStyle = "style='".$cellStyle."'";
			
			$textStyle = $this->drawSettingRow_getTextStyle($setting);
						
			$rowClass = $this->drawSettingRow_getRowClass($setting);
						
			$text = $this->drawSettingRow_getText($setting);
			
			$description = UniteFunctionsUG::getVal($setting,"description");

			
			//set settings text width:
			$textWidth = "";
			if(isset($setting["textWidth"])) 
				$textWidth = 'width="'.$setting["textWidth"].'"';
			
			$addField = UniteFunctionsUG::getVal($setting, UniteSettingsUGNEW::PARAM_ADDFIELD);
			
			$drawTh = true;
			$tdHtmlAdd = "";			
			if($mode == "single_editor")
				$drawTh = false;
			
			if(empty($text))
				$drawTh = false;
				
			if($drawTh == false)
				$tdHtmlAdd = " colspan=2";
				
			?>
						
			<?php
			if(!empty($addField)):
				
				$addSetting = $this->settings->getSettingByName($addField);
				UniteFunctionsUG::validateNotEmpty($addSetting,"AddSetting {$addField}");
			
				$addSettingText = UniteFunctionsUG::getVal($addSetting,"text","");
				$addSettingText = str_replace(" ","&nbsp;", $addSettingText);
				$tdSettingAdd = "";
				if(!empty($addSetting)){
					$tdSettingAdd = ' class="unite-settings-onecell" colspan="2"';
				}
				
				?>
				<tr <?php echo $rowClass?> valign="top">
				
				<?php if(empty($addSettingText)):?>
					
					<th <?php echo $textStyle?> scope="row" <?php echo $textWidth ?>>
						<?php if($this->showDescAsTips == true): ?>
					    	<span class='setting_text' title="<?php echo esc_attr($description)?>"><?php echo $text?></span>
					    <?php else:?>
					    	<?php echo $text?>
					    <?php endif?>
					</th>
					
				<?php endif?>
				
				<td <?php echo $cellStyle?> <?php echo $tdSettingAdd?>>
					
					<span id="<?php echo $setting["id_row"]?>">
						
						<?php if(!empty($addSettingText)):?>
						<span class='setting_onecell_text'><?php echo esc_html($text)?></span>
						<?php endif?>
						
							<?php 
								$this->drawInputs($setting);
								$this->drawInputAdditions($setting);
							?>
							
						<?php if(!empty($addSettingText)):?>
							<span class="setting_onecell_horsap"></span>
						<?php endif?>
					</span>
					
					<span id="<?php echo esc_attr($addSetting["id_row"])?>">
						<span class='setting_onecell_text'><?php echo esc_html($addSettingText)?></span>				
						<?php
							$this->drawInputs($addSetting);
							$this->drawInputAdditions($addSetting);
						?>
					</span>
				</td>
				</tr>
				<?php
			?>
			<?php else:	?>
				<tr id="<?php echo esc_attr($setting["id_row"])?>"  <?php echo $rowClass?> valign="top">
					
					<?php if($drawTh == true):?>
					
					<th <?php echo $textStyle?> scope="row" <?php echo $textWidth ?>>
						<?php if($this->showDescAsTips == true): ?>
					    	<span class='setting_text' title="<?php echo esc_attr($description)?>"><?php echo $text?></span>
					    <?php else:?>
					    	<?php echo $text?>
					    <?php endif?>
					</th>
					
					<?php endif?>
					
					<td <?php echo $cellStyle?> <?php echo $tdHtmlAdd?>>
						<?php 
							$this->drawInputs($setting);
							$this->drawInputAdditions($setting);
						?>
					</td>
				</tr>
			<?php
			endif;
		}

		/**
		 * draw hr row
		 * @param $setting
		 */
		protected function drawHrRow($setting){

			//set hidden
					
			$name = UniteFunctionsUG::getVal($setting, "name");
			
			if(empty($name))
				UniteFunctionsUG::throwError("No name in HR setting found");
			
			$class = UniteFunctionsUG::getVal($setting, "class");
			
			$classHidden = $this->drawSettingRow_getRowHiddenClass($setting);
			if(!empty($classHidden)){
				
				if(!empty($class))
					$class .= " ";
				
				$class .= $classHidden;
			}
			
			if(!empty($class)){
				$class = esc_attr($class);
				$class = "class='$class'";
			}
			
			?>
			<tr id="<?php echo esc_attr($setting["id_row"])?>" <?php echo $class; ?>>
				<td colspan="4" align="left" style="text-align:left;">
					 <hr name="<?php echo $name?>" /> 
				</td>
			</tr>
			<?php 
		}
		
		
		
		/**
		 * draw text row
		 * @param unknown_type $setting
		 */
		protected function drawTextRow($setting){
		
			//set cell style
			$cellStyle = "";
			if(isset($setting["padding"]))
				$cellStyle .= "padding-left:".$setting["padding"].";";
		
			if(!empty($cellStyle))
				$cellStyle="style='$cellStyle'";
		
			//set style
			
			$tdHtmlAdd = 'colspan="2"'; 
			
			$label = UniteFunctionsUG::getVal($setting, "label");
			if(!empty($label))
				$tdHtmlAdd = "";
			
			$rowClass = $this->drawSettingRow_getRowClass($setting);
			
			$classAdd = UniteFunctionsUG::getVal($setting, UniteSettingsUGNEW::PARAM_CLASSADD);
			
			if(!empty($classAdd))
				$classAdd = " ".$classAdd;
			
			?>
				<tr id="<?php echo esc_attr($setting["id_row"])?>" <?php echo $rowClass?>  valign="top">
					<?php if(!empty($label)):?>
					<th>
						<?php echo $label?>
					</th>
					<?php endif?>
					<td <?php echo $tdHtmlAdd?> <?php echo $cellStyle?>>
						<span class="unite-settings-static-text<?php echo esc_attr($classAdd)?>"><?php echo $setting["text"]?></span>
					</td>
				</tr>
			<?php 
		}
		
		
		/**
		 * draw wrapper before settings
		 */
		protected function drawSettings_before(){
			?><table class='unite_table_settings_wide'><?php
		}
		
		
		/**
		 * draw wrapper end after settings
		 */
		protected function drawSettingsAfter(){
			
			?></table><?php
		}
		
		
	
	}
?>