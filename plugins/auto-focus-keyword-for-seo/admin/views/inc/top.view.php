<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <a href="admin.php?page=auto-focus-keyword-for-seo" class="afkw-logo">Automatic Focus Keyword Settings</a>
    </div>
    <div class="col-xs-12 col-md-4">
        <div class="nav-tab-wrapper" style="display: flex; justify-content: flex-end;">

            <a href="<?php echo esc_url( 'admin.php?page='.AFKW_NAME.'&tab=settings' ); ?>" class="afkw-tab nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>">Settings</a>

            <a href="<?php echo esc_url( 'admin.php?page='.AFKW_NAME.'&tab=logs' ); ?>" class="afkw-tab nav-tab <?php echo $active_tab == 'logs' ? 'nav-tab-active' : ''; ?>">Sync Logs</a>
            
            <a href="<?php echo esc_url( 'admin.php?page='.AFKW_NAME.'&tab=faq' ); ?>" class="afkw-tab nav-tab <?php echo $active_tab == 'faq' ? 'nav-tab-active' : ''; ?>">FAQ</a>

        </div>
    </div>
</div>

