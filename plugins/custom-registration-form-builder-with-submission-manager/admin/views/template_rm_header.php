<?php if(defined('RM_ADDON_PLUGIN_VERSION') && version_compare(RM_ADDON_PLUGIN_VERSION, RM_PLUGIN_VERSION, '<')) { ?>
<div class="notice notice-warning rm-upgrade-issue-notice" style="position: relative;">
    <p>
        <strong>Your RegistrationMagic Premium Is Outdated </strong><br/>
        Please update to the latest version for best user experience and to avoid UI / layout issues. <a href="https://registrationmagic.com/checkout/order-history/" target="blank">Download here</a>.
    </p>
</div>
<?php }