<div class="wrap">
  <div style="position: absolute; right: 20px; margin-top: 5px">
    <a href="http://foliovision.com/wordpress/plugins/fv-antispam" target="_blank" title="Documentation"><img alt="visit foliovision" src="http://foliovision.com/shared/fv-logo.png" /></a>
  </div>

  <?php if ($this->util__is_min_wp('2.7')) { ?>
    <div id="icon-options-general" class="icon32"><br /></div>
  <?php }

  ?>
  <h2>FV Antispam</h2>

  <form id="fv_antispam_options" method="post" action="">
    <div id="dashboard-widgets" class="metabox-holder columns-1">
      <div id='postbox-container-1' class='postbox-container' style="width: 100%;">
        <?php
        do_meta_boxes('fv_antispam_settings', 'normal', false );
        wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
        wp_nonce_field( 'meta-box-order-nonce', 'meta-box-order-nonce', false );
        ?>
        <?php wp_nonce_field('fvantispam') ?>
        <input type="submit" name="fv_antispam_submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
      </div>
    </div>
  </form>

  <script type="text/javascript">
    //<![CDATA[
    jQuery(document).ready( function($) {
      // close postboxes that should be closed
      $('.if-js-closed').removeClass('if-js-closed').addClass('closed');
      // postboxes setup
      postboxes.add_postbox_toggles('fv_antispam_settings');
    });
    //]]>
  </script>
</div>