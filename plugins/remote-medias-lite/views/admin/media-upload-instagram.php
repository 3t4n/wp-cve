<?php
use WPRemoteMediaExt\RemoteMediaExt\FRemoteMediaExt;

$feature = FRemoteMediaExt::getInstance();?>
<script type="text/html" id="tmpl-media-upload-instagram-upgrade">
  <div class="uploader-inline rm-upload-wrap">
    <div class="uploader-inline-content no-upload-message">
      <div class="upload-ui">
        <h3 class="upload-instructions drop-instructions"><?php _e('Get unlimited number of pictures with RML Instagram Pro');?></h3>
        <br>
        <a href="https://www.onecodeshop.com/instagram-pro-remote-media-libraries/?utm_source=wp&utm_medium=user_upload&utm_campaign=rml&utm_content=dailymotion" target="_blank" class="browser button button-hero" id="instagram-pro-link" style="position: relative; z-index: 0; display: inline;"><?php _e('Get the extension and unlock premium features &raquo;');?></a>
      </div>
      <p class="no-mg-top"><strong><?php _e('Add our RML Instagram Pro extension to unlock premium features.', 'remote-medias-lite'); ?></strong></p>
      <p class="ocs-logo"><img width="165px" src="<?php echo $feature->getAssetsUrl().'img';?>/logo-dev.png" alt="One Code Shop" /></p>
    </div>
  </div>
</script>