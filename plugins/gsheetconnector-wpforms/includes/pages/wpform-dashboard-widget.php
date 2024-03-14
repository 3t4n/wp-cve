<?php
/*
 * Wpform GS Dashboard Widget
 * @since 1.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
   exit();
}
?>
<div class="dashboard-content">
   <?php
   $gs_connector_service = new WPforms_Googlesheet_Services();

   $forms_list = $gs_connector_service->get_forms_connected_to_sheet();
   ?>
   <div class="main-content">
      <div>
         <h3><?php echo __("WPForms Connected with Google Sheets", "gsheetconnector-wpforms"); ?></h3>
         <ul class="wp-form-list">
            <?php

            if (!empty($forms_list)) {
               foreach ($forms_list as $key => $value) {
                 $meta_value = unserialize($value->meta_value);
                 $sheet_name = $sheet_id = '';
                  if(!empty($meta_value['gs_sheet_manuals_sheet_name'])){
                  $sheet_name = $meta_value['gs_sheet_manuals_sheet_name'];   
                  }
                  if(!empty($meta_value['gs_sheet_manuals_sheet_id'])){
                     $sheet_id = $meta_value['gs_sheet_manuals_sheet_id'];
                  }
                  
                  if ($sheet_name !== "" && $sheet_id !== "") {
                     ?>
                     <a href="<?php echo admin_url('admin.php?page=wpforms-builder&view=fields&form_id=' . $value->ID . ''); ?>" target="_blank">
                        <li style= "list-style:none;"><?php echo $value->post_title; ?></li>
                     </a>
                     <?php } else {
                     ?>
                     <li><span><?php echo __("No WPForms are Connected with Google Sheets.", "gsheetconnector-wpforms"); ?></span></li>
                     <?php
                  }
               }
            } else {
               ?>
               <li><span><?php echo __("No WPForms are Connected with Google Sheets.", "gsheetconnector-wpforms"); ?></span></li>
               <?php
            }
            ?>
         </ul>
      </div>
   </div> <!-- main-content end -->
</div> <!-- dashboard-content end -->