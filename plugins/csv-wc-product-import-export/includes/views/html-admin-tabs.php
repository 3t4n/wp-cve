<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
	<a href="<?php _e(admin_url('admin.php?page=piecfw_import_export')); ?>" class="nav-tab <?php _e(($tab == 'import') ? 'nav-tab-active' : ''); ?>"><?php _e('Product Import', PIECFW_TRANSLATE_NAME); ?></a><a href="<?php _e(admin_url('admin.php?page=piecfw_import_export&tab=export')); ?>" class="nav-tab <?php _e(($tab == 'export') ? 'nav-tab-active' : ''); ?>"><?php _e('Product Export', PIECFW_TRANSLATE_NAME); ?></a>
	<a href="<?php _e(admin_url('admin.php?page=piecfw_import_export&tab=logs')); ?>" class="nav-tab <?php _e(($tab == 'logs') ? 'nav-tab-active' : ''); ?>"><?php _e('Logs', PIECFW_TRANSLATE_NAME); ?></a>
	<a href="<?php _e(admin_url('admin.php?page=piecfw_import_export&tab=cron')); ?>" class="nav-tab <?php _e(($tab == 'cron') ? 'nav-tab-active' : ''); ?>"><?php _e('CRON Scheduler', PIECFW_TRANSLATE_NAME); ?></a>
</h2>

<div id="page-preloader" style="display:none;" class="loading_wrap preloader-loaded"><div class="page-preloader-spin"></div></div>