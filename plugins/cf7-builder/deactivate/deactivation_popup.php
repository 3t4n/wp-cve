<div class="cf7b-opacity"></div>
<div class="cf7b-deactivate-popup">
  <form method="post" id="cf7b_deactivate_form">
    <div class="cf7b-deactivate-popup-header">
      <?php _e("Please let us know why you are deactivating. Your answer will help us to provide you support. (Optional)", 'cf7b'); ?>:
      <span class="cf7b-deactivate-popup-close-btn"></span>
    </div>

    <div class="cf7b-deactivate-popup-body">
        <?php foreach ( $deactivate_reasons as $deactivate_reason_slug => $deactivate_reason ) { ?>
        <div class="cf7b-reasons">
          <input type="radio" value="<?php echo esc_attr($deactivate_reason["id"]); ?>" id="cf7b-<?php echo sanitize_html_class($deactivate_reason["id"]); ?>" name="cf7b_reasons">
          <label for="cf7b-<?php echo esc_attr($deactivate_reason["id"]); ?>"><?php echo esc_html($deactivate_reason["text"]); ?></label>
        </div>
      <?php } ?>
      <div class="cf7b_additional_details_wrap"></div>
    </div>
    <div class="cf7b-btns">
      <a href="<?php echo esc_url($deactivate_url); ?>" data-val="1" class="button button-secondary button-close" id="cf7b-deactivate"><?php _e("Skip and Deactivate", 'cf7b'); ?></a>
      <a href="<?php echo esc_url($deactivate_url); ?>" data-val="2" class="button button-primary button-close cf7b-deactivate" id="cf7b-submit-and-deactivate"><?php _e("Submit and Deactivate", 'cf7b'); ?></a>
    </div>
    <input type="hidden" name="cf7b_submit_and_deactivate" value="">
    <?php wp_nonce_field('cf7b_save_form', 'cf7b_save_form_fild'); ?>
  </form>
</div>
