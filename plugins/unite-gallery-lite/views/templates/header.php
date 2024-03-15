<?php
/**
 * @package Unite Gallery
 * @author Valiano
 * @copyright (C) 2022 Unite Gallery, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNITEGALLERY_INC') or die('Restricted access');

	if(!isset($headerTitle))
		UniteFunctionsUG::throwError("header template error: \$headerTitle variable not defined"); 
	
		
?>
		
		<div class="unite_header_wrapper">
			
			<div class="title_line">
				<div class="title_line_text">
					<?php echo GlobalsUG::PLUGIN_TITLE." - " . esc_html($headerTitle) ?>
				</div>				
			</div>
			<?php HelperUG::$operations->putTopMenu(GlobalsUG::VIEW_GALLERIES)?>
			<div class="unite-clear"></div>
		</div>
		
		<div class="vert_sap10"></div>
		