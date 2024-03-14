<?php

  /*
   *  Settings page : GraphComment Backoffice
   *  Show the GraphComment backoffice in an iframe
   */

require_once(__DIR__.'/../services/gc_params_service.class.php');
require_once(__DIR__.'/../services/gc_iframe_manager.class.php');

/**
* Returns the plugin GraphComment Admin page
*/
function _graphcomment_settings_page_admin() {
?>

  <?php header_template(); ?>

  <div class="gc-wrap no-top-margin">

  <?php

  if (!GcParamsService::getInstance()->graphcommentOAuthIsLogged()) {
    GcParamsService::getInstance()->graphcommentOAuthInitConnection();
    $isDisconnect = get_option('graphcomment-disconnect');
  ?>
    <div class="gc-iframe-container">
      <iframe id="connection-iframe" class="gc-connexion-iframe gc-iframe"></iframe>
      <script>
        const disconnecting = <?php echo boolval($isDisconnect) ? 'true' : 'false' ?>;
        const iframe = document.getElementById("connection-iframe");
        const iframeDisconnectURL = "<?php echo GcIframeManager::getIframeUrl(GcIframeManager::GRAPHCOMMENT_DISCONNEXION_IFRAME); ?>"
        const iframeConnectURL = "<?php echo GcIframeManager::getIframeUrl(GcIframeManager::GRAPHCOMMENT_CONNEXION_IFRAME); ?>"

        /**
         * Hack to disconnect using GraphLogin instead of OAuth
         */
        if (disconnecting) {
          iframe.style.opacity = 0;
          iframe.src = iframeDisconnectURL
          
          setTimeout(() => {
            iframe.onload = () => {
              iframe.style.opacity = 1;
            }
            iframe.src = iframeConnectURL;
          }, 2500)
        } else {
          iframe.src = iframeConnectURL
        }
      </script>
    </div>
    <?php
      if ($isDisconnect === 'true') {
        delete_option('graphcomment-disconnect');
      }
    ?>
  <?php } else { ?>
    <?php if (!GcParamsService::getInstance()->graphcommentHasUser()): ?>
      <iframe class="gc-connexion-iframe gc-iframe"
        src="<?php echo GcIframeManager::getIframeUrl(GcIframeManager::GRAPHCOMMENT_CONNEXION_IFRAME); ?>">
      </iframe>
    <?php else: ?>
      <div class="graphcomment-admin-iframe-container">
        <script>
          var gc_token = '<?php echo GcParamsService::getInstance()->graphcommentGetClientToken(); ?>';
          var wordpressGcUser = '<?php echo GcParamsService::getInstance()->getUserField('_id'); ?>';
          var logoutUrl = '<?php echo constant("ADMIN_URL_LOGOUT"); ?>';
        </script>
        <div class="gc-alert gc-alert-danger alert-wrong-account" style="display: none; margin: 10px;">
          <p>
            Wordpress plugin is connected to a GraphComment account different than the one you are connected
            with on http://graphcomment.com. <a href="#" style="color: #a94442">Click here</a> to logout, and then connect to your account with username
            <b><?php echo GcParamsService::getInstance()->getUserField('username'); ?></b>.
          </p>
        </div>
        <iframe id="gc-iframe"
                class="graphcomment-admin-iframe gc-iframe"
                src="<?php echo GcIframeManager::getIframeUrl(GcIframeManager::GRAPHCOMMENT_ADMIN_IFRAME); ?>">
        </iframe>
      </div>

    <?php endif; ?>

  <?php } ?>

  </div>

<?php }
