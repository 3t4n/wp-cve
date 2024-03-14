<?php

require_once(__DIR__.'/../services/gc_iframe_manager.class.php');

if (!class_exists('GcAuthorizationTemplate')) {
  class GcAuthorizationTemplate
  {
    public static function getTemplate()
    {
      ?>

      <div class="gc-iframe-container">
        <script>
          var gc_authorize_popup = true; // use in iframe
        </script>
        <iframe class="gc-connexion-iframe gc-iframe"
                src="<?php echo GcIframeManager::getIframeUrl(GcIframeManager::GRAPHCOMMENT_CONNEXION_IFRAME); ?>">
        </iframe>
      </div>

      <?php
    }

    public static function getErrorTemplate($oauth_client_key, $oauth_redirect_uri)
    {
      // TODO translate __()
      ?>

      <div>
        <p>An error happened with the Authorization. Please contact support@graphcomment.com. It can be an edition of
          your plugin code.</p>
        <ul>
          <li>client_id : <?php echo $oauth_client_key; ?></li>
          <li>oauth_redirect_uri : <?php echo $oauth_redirect_uri; ?></li>
        </ul>
      </div>

      <?php
    }
  }
}
