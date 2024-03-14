<?php if (get_option('gs-api-key') == ''): ?>
    <div class="notification-bar starting alert-block inline gs-success hidden">
        <div class="alert-info">
            <p class="alert-title">Congratulations!</p>
            <p>You are ready to start in 3..2..1..</p>
        </div>
    </div>
    <div class="notification-bar alert-block inline gs-error hidden">
        <div class="alert-info no-desc">
            <p class="alert-title"></p>
        </div>
    </div>
<?php endif; ?>

<?php if (!$GS->is_pro() && get_option('gs-alert-msg')): ?>
    <div class="alert-block center large plan-one">
        <div class="alert-info">
            <p class="alert-title"><?php echo get_option('gs-alert-msg') ?></p>
            <a href="<?php echo $GS->gs_account() ?>/sites/gs-wordpress/billing/select_tier?api_key=<?php echo $GS->api_key ?>&amp;source=wordpress<?php echo get_option('gs-alert-utm') ?>" target="_blank" class="gs-button"><?php echo get_option('gs-alert-cta') ?></a>
        </div>
    </div>
<?php endif; ?>

<?php if (isset($_GET['update']) || isset($_GET['delete'])): ?>
    <div class="notification-bar alert-block inline gs-success">
        <div class="alert-info no-desc">
            <p class="alert-title">App <?php echo isset($_GET['update']) ? 'updated' : 'deactivated' ?> with success</p>
        </div>
        <a href="javascript:void(0)" class="close"><i class="fa fa-times"></i></a>
    </div>
<?php endif; ?>

<?php if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == true): ?>
    <div class="notification-bar alert-block inline gs-success">
        <div class="alert-info no-desc">
            <p class="alert-title">Preferences updated successfully</p>
        </div>
        <a href="javascript:void(0)" class="close"><i class="fa fa-times"></i></a>
    </div>
<?php endif; ?>