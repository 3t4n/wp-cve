<?php
  if ( ! defined( 'ABSPATH' ) ) {
  	exit; // Exit if accessed directly
  }
?>

<div class="wcpt-editor-clear"></div>
<h1 class="wcpt-page-title dashicons-before dashicons-editor-justify">
  <?php _e( "WCPT Global Settings", "wcpt" ); ?>
</h1>

<div class="wcpt-editor-clear"></div>

<!-- settings begin -->
<div class="wcpt-settings" wcpt-model-key="data">

  <?php
    if( defined( 'WCPT_PRO' ) ){
      require_once('settings-partials/pro-license.php');
    }
  ?>
  <?php require_once('settings-partials/archive-override.php'); ?>
  <?php require_once('settings-partials/cart-widget.php'); ?>
  <?php require_once('settings-partials/checkbox-trigger.php'); ?>
  <?php require_once('settings-partials/modals.php'); ?>
  <?php require_once('settings-partials/no-results.php'); ?>
  <?php require_once('settings-partials/search.php'); ?>
  <?php do_action('wcpt_settings_panel_end'); ?>

  <!-- save data -->
  <div class="wcpt-reset-global-settings-container">
    <a class="wcpt-reset-global-settings" href="<?php echo admin_url( 'edit.php?post_type=wc_product_table&page=wcpt-settings&wcpt_reset_global_settings=true&_wp_nonce=' . wp_create_nonce('wcpt_reset_global_settings') ); ?>">Reset settings</a>
  </div>
  <div class="wcpt-editor-save-table-clear"></div>
  <div class="wcpt-editor-save-table" style="margin-top: 30px">
    <form class="wcpt-save-data" action="wcpt_save_global_settings" method="post">
      <!-- hidden fields -->
      <input name="nonce" type="hidden" value="<?php echo wp_create_nonce( "wcpt" ); ?>">
      <button type="submit" class="wcpt-editor-save-button button button-primary button-large"><?php _e( "Save settings", "wcpt" ); ?></button>
      <i class="wcpt-saving-icon">
        <?php wcpt_icon('loader', 'wcpt-rotate'); ?>
      </i>
      <br/>
      <div class="wcpt-save-keys">
      Mac: ⌘ + s | Win: ctrl + s
      </div>
      <div class="wcpt-editor__saving">
        <?php wcpt_icon('loader', 'wcpt-rotate'); ?>
        <span>Saving...</span>
      </div>
    </form>
  </div>

</div>
<!-- /settings end -->

<!-- import export -->
<?php require_once('settings-partials/import-export.php'); ?>

<div class="wcpt-footer">
  <div class="wcpt-support wcpt-footer-note">
    <?php wcpt_icon('alert-circle'); ?>
    <span><?php _e( "Found a bug / Got questions? Please reach out for support here: ", "wc-product-table" ); ?><a href="mailto:support@wcproducttable.com" target="_blank">support@wcproducttable.com</a> | <a href="https://wcproducttable.com/tutorials/" target="_blank">Tutorials</a></span>
  </div>
  <?php if( ! defined( 'WCPT_PRO' ) ): ?>
  <div class="wcpt-support wcpt-footer-note">
    <?php wcpt_icon('zap'); ?>
    <span>
      <?php _e( "WCPT PRO is ready for your shop! Build better tables today!", "wc-product-table" ); ?>
      <a href="https://wcproducttable.com/get-pro/" target="_blank"><?php _e( "View enhancements", "wc-product-table" ); ?></a>
    </span>
  </div>
  <?php endif; ?>

  <div class="wcpt-support wcpt-footer-note">
    <?php wcpt_icon('heart'); ?>
    <span><?php _e( "Do you like this plugin? Please support it with your 5 star rating ", "wc-product-table" ); ?><a href="https://wordpress.org/support/plugin/wc-product-table-lite/reviews/" target="_blank">here</a>. Thanks!</span>
  </div>
</div>

<!-- icon templates -->
<?php
  $icons = array( 'trash', 'sliders', 'copy', 'x', 'check' );
  foreach( $icons as $icon_name ){
    ?>
    <script type="text/template" id="wcpt-icon-<?php echo $icon_name; ?>">
      <?php echo wcpt_icon( $icon_name ); ?>
    </script>
    <?php
  }
?>

<!-- element partials -->
<?php require_once('partials/element-editor/element-partials.php'); ?>

<!-- required js vars -->
<script>var wcpt_icons_url = "<?php echo WCPT_PLUGIN_URL . 'assets/feather'; ?>";</script>
