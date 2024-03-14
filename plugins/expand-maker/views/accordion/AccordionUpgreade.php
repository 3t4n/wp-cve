<?php if (yrm_is_free()): ?>
<div class="panel panel-default">
    <div class="panel-heading"><?php _e('Upgrade', YRM_LANG);?></div>
    <div class="panel-body yrm-upgrade-pro-wrapper">
        <p class="yrm-upgrade-pro">
            Do you want to <br><b>HAVE AN ADVANCED VERSION</b>?<br>
        </p>
        <?php echo ReadMoreAdminHelper::upgradeButton('<b class="h2">Upgrade Now</b>'); ?>
    </div>
</div>
<?php endif; ?>