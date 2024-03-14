<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://wenthemes.com
 * @since      1.0.0
 *
 * @package    Wen_Featured_Image
 * @subpackage Wen_Featured_Image/admin/partials
 */
?>
<div class="wrap">

  <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

  <div id="poststuff">

    <div id="post-body" class="metabox-holder columns-2">

      <!-- main content -->
      <div id="post-body-content">

      <form action="options.php" method="post">

        <?php settings_fields( 'wfi-plugin-options-group' ); ?>

          <div class="meta-box-sortables ui-sortable">

            <div class="postbox">

              <div class="inside">
               <?php do_settings_sections( 'wen-featured-image-column' ); ?>
             </div> <!-- .inside -->

            </div> <!-- .postbox -->

            <div class="postbox">

              <div class="inside">
               <?php do_settings_sections( 'wen-featured-image-required' ); ?>
             </div> <!-- .inside -->

            </div> <!-- .postbox -->

            <div class="postbox">

              <div class="inside">
               <?php do_settings_sections( 'wen-featured-image-message' ); ?>
             </div> <!-- .inside -->

            </div> <!-- .postbox -->

          </div> <!-- .meta-box-sortables .ui-sortable -->

          <?php submit_button( __( 'Save Changes', 'wen-featured-image' ) ); ?>

          </form>

      </div> <!-- post-body-content -->

      <!-- sidebar -->
      <div id="postbox-container-1" class="postbox-container">

        <?php require_once( WEN_FEATURED_IMAGE_DIR . '/admin/partials/wen-featured-image-admin-right-display.php' ); ?>

      </div> <!-- #postbox-container-1 .postbox-container -->

    </div> <!-- #post-body .metabox-holder .columns-2 -->

    <br class="clear">
  </div> <!-- #poststuff -->

</div> <!-- .wrap -->
