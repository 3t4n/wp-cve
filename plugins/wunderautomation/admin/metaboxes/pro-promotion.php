<?php
$logos       = content_url('plugins/wunderautomation/admin/assets/images/pro-sidebar-promo-1.9.png');
$upgradePage = admin_url('admin.php?page=wunderauto-upgrade');
?>

<p>
    Make your workflows even more powerful!
</p>
<p>
    Integrate with popular 3rd party plugins and services, get access to better re-trigger scheduling,
    additional filters and parameters and better support.
</p>
<p style="text-align: center;">
    <a type="button" class="button button-primary"
       href="<?php esc_attr_e($upgradePage)?>" target="_pro"> Upgrade to Pro </a>
</p>
<p style="text-align: center;margin-top: 30px;">
    <a href="<?php esc_attr_e($upgradePage)?>" target="_pro">
        <img src="<?php esc_attr_e($logos)?>" width="210">
    </a>
</p>
