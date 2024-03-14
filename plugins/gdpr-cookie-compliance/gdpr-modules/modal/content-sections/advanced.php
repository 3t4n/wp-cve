<?php 
  if ( ! defined( 'ABSPATH' ) ) {
    exit;
  } // Exit if accessed directly
?>

<?php if ( $content->show ) : ?>
  <div id="advanced-cookies" class="moove-gdpr-tab-main" <?php echo $content->visibility; ?>>
    <span class="tab-title"><?php echo $content->tab_title; ?></span>
    <div class="moove-gdpr-tab-main-content">
      <?php echo $content->tab_content; ?>
      <div class="moove-gdpr-status-bar">
        <div class="gdpr-cc-form-wrap">
          <div class="gdpr-cc-form-fieldset">
            <label class="cookie-switch" for="moove_gdpr_advanced_cookies">    
              <span class="gdpr-sr-only"><?php esc_html_e( 'Enable or Disable Cookies', 'gdpr-cookie-compliance' ); ?></span>
              <input type="checkbox" aria-label="<?php echo $content->tab_title; ?>" value="check" name="moove_gdpr_advanced_cookies" id="moove_gdpr_advanced_cookies" <?php echo $content->is_checked; ?>>
              <span class="cookie-slider cookie-round" data-text-enable="<?php echo $content->text_enable; ?>" data-text-disabled="<?php echo $content->text_disable; ?>"></span>
            </label>
          </div>
          <!-- .gdpr-cc-form-fieldset -->
        </div>
        <!-- .gdpr-cc-form-wrap -->
      </div>
      <!-- .moove-gdpr-status-bar -->
    <?php if ( $content->warning_message  ) : ?>
      <div class="moove-gdpr-strict-secondary-warning-message" style="margin-top: 10px; display: none;">
        <?php echo $content->warning_message; ?>
      </div>
      <!--  .moove-gdpr-strict-secondary-warning-message -->
    <?php endif; ?>
    <?php do_action( 'gdpr_modules_content_extension', $content, 'advanced' ); ?> 
    </div>
    <!--  .moove-gdpr-tab-main-content -->
  </div>
  <!-- #advanced-cookies -->
<?php endif; ?>