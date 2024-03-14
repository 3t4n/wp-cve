<div class="wrap sharelink">
    <h2>Invalid Domain</h2>
    <p>It appears that you have a valid Share Link subscription however the domain you are using the plugin from is not listed in your account.</p>
    <p> Domain detected: <code><?php echo $_SERVER['SERVER_NAME']; ?></code></p>
    <a class="button button-secondary button-hero install-now" href="<?php echo SHARELINK_APP_BASE_URL; ?>" target="_blank">Login to Share Link</a>
    <p>From the Share Link menu choose Settings > Configuration then click the “Domains” tab at the top of the page.</p>
    <p><img src="<?php echo SHARELINK_URL; ?>/images/domain-invalid.png" /></p>
    <p> Copy and paste the domain <code><?php echo $_SERVER['SERVER_NAME']; ?></code> into one of the available domain boxes on this page and click “Update domains”. </p>
    <p> Once done click the button below to test your domain. </p>
    <a class="button button-secondary button-hero install-now" id="check_domain">Check domains</a>
    <p class="description error" style="display:none;" id="invalid-domain">This domain does not exist, please contact Share Link support via your Share Link dashboard.</p>
</div>