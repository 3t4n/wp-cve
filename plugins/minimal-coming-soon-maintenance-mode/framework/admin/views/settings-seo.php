<?php

if (!defined('WPINC')) {
	die;
}

?>

<div class="signals-tile" id="seo">
  <div class="signals-tile-body">
    <div class="signals-tile-title">SEO</div>
    <p>Carefully craft your content in order to rank your site as best as possible from day one. Use the SEO Analysis tool to improve weak areas.</p>

    <div class="signals-section-content">
    <div class="signals-double-group signals-clearfix" id="seotitle">
      <div class="signals-form-group">
        <label for="signals_csmm_title" class="signals-strong">SEO Title</label>
        <input type="text" name="signals_csmm_title" id="signals_csmm_title" data-site-title="<?php echo get_bloginfo('name'); ?>" value="<?php echo esc_attr_e( stripslashes( $signals_csmm_options['title'] ) ); ?>" placeholder="%sitetitle% is coming soon" class="signals-form-control">
        <div class="mm-seo-progress " id="mm-seo-progress-title"><div class="mm-seo-progress-bar"></div></div>
        <p class="signals-form-help-block">Recommended format: <i>Primary Keyword - Secondary Keyword - Brand Name</i> with length up to 60 characters.</p>
      </div>

      <div class="signals-form-group">
        <label for="signals_csmm_description" class="signals-strong">Meta Description</label>
        <textarea type="text" name="signals_csmm_description" id="signals_csmm_description" data-site-description="<?php echo get_bloginfo('description'); ?>" rows="3" class="signals-form-control"><?php echo esc_attr_e( stripslashes( $signals_csmm_options['description'] ) ); ?></textarea>
        <div class="mm-seo-progress " id="mm-seo-progress-description"><div class="mm-seo-progress-bar"></div></div>
        <p class="signals-form-help-block">Write for humans, not search engines! This text will incite people to click on your site on Google. Length should be 50 - 300 characters.</p>
      </div>
    </div>

    <div class="signals-double-group signals-clearfix" id="blockse">
      <div class="signals-form-group">
        <label for="signals_csmm_excludese" class="signals-strong">Exclude Search Engines</label>
        <input type="checkbox" class="signals-form-ios" name="signals_csmm_excludese" id="signals_csmm_excludese" value="1"<?php checked( '1', $signals_csmm_options['exclude_se'] ); ?>>
        <p class="signals-form-help-block">If enabled, search engines will always see your normal page, never the coming soon one. We do not recommend enabling this feature.</p>
      </div>

      <div class="signals-form-group">
        <label for="signals_csmm_blockse" class="signals-strong pro-option">Temporarily Pause Search Engines <sup>PRO</sup></label>
        <input type="checkbox" class="signals-form-ios skip-save pro-option" disabled="disabled" name="signals_csmm_blockse" id="signals_csmm_blockse" value="1"<?php checked( '1', $signals_csmm_options['block_se'] ); ?>>

        <p class="signals-form-help-block">If your site is already indexed and you're just taking it down for a while, enable this option. It temporarily discourages search engines from crawling the site by telling them it's under construction by sending a <i>503 Service Unavailable</i> response. Described method is <a href="https://webmasters.googleblog.com/2011/01/how-to-deal-with-planned-site-downtime.html" target="_blank">recommended by Google</a>. This is a <a href="#pro" class="csmm-change-tab">PRO feature</a>.</p>

      </div>
    </div>


    <div class="signals-double-group signals-clearfix">
      <div class="signals-form-group">
        <label for="signals_csmm_analytics" class="signals-strong">Google Analytics Tracking ID</label>
        <input name="signals_csmm_analytics" id="signals_csmm_analytics" placeholder="UA-123456-99" value="<?php echo esc_attr( csmm_convert_ga($signals_csmm_options['analytics'])); ?>">

        <p class="signals-form-help-block">Enter only the Google Analytics Profile ID, ie: UA-123456-99. You'll find it in the GA tracking code.</p>
      </div>

      <div class="signals-form-group">
        <label for="tracking_pixel" class="signals-strong pro-option">Tracking Pixel &amp; 3rd Party Analytics Code <sup>PRO</sup></label>
        <textarea disabled="disabled" class="pro-option skip-save" name="tracking_pixel" id="tracking_pixel" placeholder="Tracking pixel code or any 3rd party tracking code, including <script> tags"><?php echo esc_textarea($signals_csmm_options['tracking_pixel']); ?></textarea>
        <p class="signals-form-help-block">Copy&amp;paste the complete code, including the opening and closing <i>&lt;script&gt;</i> tags. Code is outputted in the page's header section. This is a <a href="#pro" class="csmm-change-tab">PRO feature</a>.</p>
      </div>
    </div>


    <div class="signals-upload-group signals-clearfix">

      <div class="signals-form-group border-fix">
        <div class="signals-upload-element">
          <label class="signals-strong pro-option">Social Networks Preview Image <sup>PRO</sup></label>
          <?php if ( ! empty( $signals_csmm_options['social_preview'] ) ) : ?>
            <span class="signals-preview-area"><img src="<?php echo esc_attr( $signals_csmm_options['social_preview'] ); ?>" /></span>
            <?php else : ?>
            <?php endif; ?>
          <input type="hidden" name="signals_csmm_social_preview" class="skip-save" id="signals_csmm_social_preview" value="<?php esc_attr_e( $signals_csmm_options['social_preview'] ); ?>">
          <button type="button" name="signals_social_preview_upload" id="signals_social_preview_upload" class="signals-btn signals-upload pro-option" style="margin-top: 4px">Upload / select image</button>

          <p class="signals-form-help-block" style="padding: 0 10px;">Site preview image displayed when sharing on Facebook, Twitter and other social networks. Image ratio should be 1:2. Facebook recommends 1200x630px or not smaller than 600x315px. This is a <a href="#pro" class="csmm-change-tab">PRO feature</a>.</p>
        </div>
      </div>
    </div>

  </div>
  </div>
</div><!-- #seo -->
