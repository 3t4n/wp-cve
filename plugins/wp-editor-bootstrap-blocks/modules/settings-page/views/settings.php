<?php
if ( ! defined( 'ABSPATH' ) || ! class_exists( 'GtbBootstrapSettingsPage', false ) ) exit;
include_once(dirname(__FILE__).'/../formfields.php');
global $gtb_options;
?>
<div class="wrap page-width">
<h3><?php _e('Global options',GUTENBERGBOOTSTRAP_SLUG)?></h3>
<div class="section-part">
<?php
   print_start_table_form();
   $design_package_feature = '';
   if (!defined('GTBBOOTSTRAP_DESIGN_LC')):
      $design_package_feature = $design_package_feature ='<em class="color-premium">'.__('Design Package feature',GUTENBERGBOOTSTRAP_SLUG).'</em>';
   endif;
   print_radiobuttons($gtb_options, 'bootstrap_included', __('Are you using a Bootstrap theme?',GUTENBERGBOOTSTRAP_SLUG), array('N'=> __('No, I\'m using a Wordpress theme without Bootstrap',GUTENBERGBOOTSTRAP_SLUG),'Y'=>__('Yes, I\'m using a Worpress theme with Bootstrap',GUTENBERGBOOTSTRAP_SLUG) ));
   print_checkbox($gtb_options,'bootstrap_on_template', __('',GUTENBERGBOOTSTRAP_SLUG), __('Load Bootstrap grid and colors only on the \'Bootstrap page\' template',GUTENBERGBOOTSTRAP_SLUG));
   print_select($gtb_options,'bootstrap_version', __('Load Bootstrap files version',GUTENBERGBOOTSTRAP_SLUG), array(
      '4.3'=> __('Bootstrap 4.3', GUTENBERGBOOTSTRAP_SLUG), 
      '4.6'=> __('Bootstrap 4.6', GUTENBERGBOOTSTRAP_SLUG), 
      '5.0'=> __('Bootstrap 5.0', GUTENBERGBOOTSTRAP_SLUG),
      '5.3'=> __('Bootstrap 5.3', GUTENBERGBOOTSTRAP_SLUG)
   ),__('Note: when this option is available, the Bootstrap Blocks plug-in loads the Bootstrap files selected here.',GUTENBERGBOOTSTRAP_SLUG));
   print_end_table_form();
   ?>
</div>

   <h2><?php _e('Grid and color options',GUTENBERGBOOTSTRAP_SLUG)?></h2>
   <div class="sections-box">
      <div class="box-half">
         <div class="section-part" style="min-height:320px;">
<?php
   print_start_table_form();
   print_input_number($gtb_options, 'gridsize', __('Bootstrap grid',GUTENBERGBOOTSTRAP_SLUG), __('Total columns per row',GUTENBERGBOOTSTRAP_SLUG).' '.$design_package_feature,__('Note: If you use a Wordpress theme with Bootstrap, make sure that the number of columns here matches the number of columns in your theme.', GUTENBERGBOOTSTRAP_SLUG));
   print_end_table_form();
   ?>
         </div>
      </div>
      <div class="box-half">
         <div class="section-part" style="min-height:320px;">
<?php
         print_start_table_form();
         print_checkbox($gtb_options,'bootstrap_colors_included', __('Bootstrap colors',GUTENBERGBOOTSTRAP_SLUG), __('Enable using your own colors',GUTENBERGBOOTSTRAP_SLUG));
         print_end_table_form();
   ?>
            <div class="colors <?php echo empty($gtb_options['bootstrap_colors_included'])?'closed':''?>">
               <?php print_all_colorfields($gtb_options); ?>
               <div class="spacer"></div>
            </div>
         </div>
      </div>
   </div>
   <?php submit_button() ?>
   <div class="sections-box" style="margin-bottom:40px;position:relative;height:0;top:-70px"><a href="#" onclick="resetToDefaults();return false" class="button reset-button"><?php _e('Reset settings',GUTENBERGBOOTSTRAP_SLUG)?></a></div>
<?php require dirname( __FILE__ ) . '/get-pro-box.php'; ?>
<?php require dirname( __FILE__ ) . '/sidebar.php'; ?>
</div>

<?php 
$admin_page = 'admin.php?page=' . GtbBootstrapSettingsPage::MENU_SLUG . '&gtb_reset=1';
$reset_url  = str_replace( '&amp;', '&', wp_nonce_url( admin_url( $admin_page, 'admin' ), 'gtb_reset_n', 'gtb_nonce' ) ) . '#settings';
?>
<script>
function resetToDefaults(){
   var r = confirm('reset to default settings?');
   if (r == true) document.location='<?=$reset_url;?>';
}
</script>



