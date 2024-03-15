<?php
/**
 * @package Unite Gallery
 * @author Valiano
 * @copyright (C) 2022 Unite Gallery, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNITEGALLERY_INC') or die('Restricted access');

?>
<?php require HelperGalleryUG::getPathHelperTemplate("header"); ?>

<?php
$objOutput->draw("ug_general_settings", true);
?>
			
			<div class="vert_sap10"></div>

			<div class="ug-button-action-wrapper">
				<a id="ug_button_save_settings" class="unite-button-primary" href="javascript:void(0)"><?php _e("Save Settings", "unitegallery");?></a>
				
				<div style="padding-top:6px;">
					
					<span id="ug_loader_save" class="loader_text" style="display:none"><?php _e("Saving...", "unitegallery")?></span>
					<span id="ug_message_saved" class="unite-color-green" style="display:none"></span>
					
				</div>
			</div>
			
			<div class="unite-clear"></div>
			
			<div id="ug_save_settings_error" class="unite_error_message" style="display:none"></div>
			
	<script type="text/javascript">

		jQuery(document).ready(function(){

			var objAdmin = new UGAdmin();
			objAdmin.initGeneralSettingsView();
			
		});
		
		
	</script>

