<?php

?>

<!-- <script> var iframeObj = window.frameElement; iframeObj.classList.add('finished'); </script> -->
<script language="JavaScript" type="text/javascript">
  window.onload = function() {

    if (parent && typeof parent.oauthPopupClose === 'function') {
      parent.oauthPopupClose(true);
    }

    document.getElementById('oauth-reload-button').addEventListener('click', function(e) {
      e.preventDefault();
      parent.oauthPopupClose(false);
    });

    // close the window
    self.close();
  };
</script>

<!-- In case of the precedent strategy failure -->
<div id="connection_success_wrap">
  <div style="text-align: center">
    <p>
      <img src="<?php echo plugins_url('../../theme/images/ajax-loader.gif', __FILE__); ?>">
    </p>
    <small>
      (<?php _e('Redirect Fail', 'graphcomment-comment-system'); ?>
      <a id="oauth-reload-button" href="#"><?php _e('Here', 'graphcomment-comment-system'); ?></a>)
    </small>
  </div>
</div>

<?php
