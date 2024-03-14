<?php
/*
 * Ninja Forms GS Dashboard Widget
 * @since 1.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
   exit();
}
?>
<div class="dashboard-content">
   <?php
   $gs_connector_service = new njforms_Googlesheet_Services();

   $forms_list = $gs_connector_service->get_forms_connected_to_sheet();
   ?>
   <div class="main-content">
      <div>
         <h3><?php echo __("Ninja Forms connected with Google Sheets", "gsheetconnector-ninjaforms"); ?></h3>
         <ul class="nj-form-list">
            <?php
            if (!empty($forms_list)) {
               foreach ($forms_list as $key => $value) {
                  //print_r($value->title);
                  //$meta_value = unserialize($value->title);
                  //$sheet_name = $meta_value['sheet-name'];
                  //$sheet_name = $value->title;
                  if ($value->title !== "") {
                     ?>
                     <a href="<?php echo admin_url('admin.php?page=ninja-forms&form_id=' . $value->ID . ''); ?>" target="_blank">
                        <li style= "list-style:none;"><?php echo esc_html($value->title); ?></li>
                     </a>
                     <?php } else {
                     ?>
                     <li><span><?php echo __("No Ninja Forms are connected with Google Sheets.", "gsheetconnector-ninjaforms"); ?></span></li>
                     <?php
                  }
               }
            } else {
               ?>
               <li><span><?php echo __("No Ninja Forms are connected with Google Sheets.", "gsheetconnector-ninjaforms"); ?></span></li>
               <?php
            }
            ?>
         </ul>
      </div>
   </div> <!-- main-content end -->
</div> <!-- dashboard-content end -->