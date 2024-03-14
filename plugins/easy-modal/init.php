<?php

// General Functions
require EMCORE_DIR.'/includes/functions.php';
require EMCORE_DIR.'/includes/shortcodes.php';
require EMCORE_DIR.'/includes/deprecated.php';


require EMCORE_DIR.'/classes/controller.php';
require EMCORE_DIR.'/classes/model.php';
require EMCORE_DIR.'/classes/view.php';

// General Models
require EMCORE_DIR.'/classes/model/modal.php';
require EMCORE_DIR.'/classes/model/modal/meta.php';
require EMCORE_DIR.'/classes/model/theme.php';
require EMCORE_DIR.'/classes/model/theme/meta.php';

// Core Addons
require EMCORE_DIR.'/addons/gravityforms.php';

require EMCORE_DIR.'/classes/migrate.php';

if(is_admin())
{
	
	// General Functions and Content
	require EMCORE_DIR.'/includes/admin/sidebar.php';
	require EMCORE_DIR.'/includes/admin/footer.php';
	require EMCORE_DIR.'/includes/admin/content.php';
	require EMCORE_DIR.'/includes/admin/functions.php';

	// API
	require EMCORE_DIR.'/classes/license.php';
	require EMCORE_DIR.'/includes/updates/plugin-update-checker.php';
	require EMCORE_DIR.'/includes/plugin-updater.php';


	// Selectbox and Other Default Options
	require EMCORE_DIR.'/includes/admin/options.php';

	// Form Tabs
	require EMCORE_DIR.'/includes/admin/modal-form-general-tab.php';
	require EMCORE_DIR.'/includes/admin/modal-form-display-tab.php';
	require EMCORE_DIR.'/includes/admin/modal-form-close-tab.php';
	require EMCORE_DIR.'/includes/admin/modal-form-example-tab.php';

	// Theme Tabs
	require EMCORE_DIR.'/includes/admin/theme-form-general-tab.php';
	require EMCORE_DIR.'/includes/admin/theme-form-overlay-tab.php';
	require EMCORE_DIR.'/includes/admin/theme-form-close-tab.php';
	require EMCORE_DIR.'/includes/admin/theme-form-container-tab.php';
	require EMCORE_DIR.'/includes/admin/theme-form-content-tab.php';
	require EMCORE_DIR.'/includes/admin/theme-form-title-tab.php';

	// Settings Tabs
	require EMCORE_DIR.'/includes/admin/settings-form-general-tab.php';
	require EMCORE_DIR.'/includes/admin/settings-form-licenses-tab.php';

	// Help Tabs
	require EMCORE_DIR.'/includes/admin/help-general-tab.php';


	// Post Meta Boxes
	require EMCORE_DIR.'/includes/admin/postmeta.php';

	// 
	require EMCORE_DIR.'/classes/admin.php';
	require EMCORE_DIR.'/classes/admin/menu.php';
	require EMCORE_DIR.'/classes/admin/notice.php';
	require EMCORE_DIR.'/classes/admin/postmeta.php';
	require EMCORE_DIR.'/classes/admin/editor.php';

	// Controllers
	require EMCORE_DIR.'/classes/controller/admin/modals.php';
	require EMCORE_DIR.'/classes/controller/admin/theme.php';
	require EMCORE_DIR.'/classes/controller/admin/settings.php';
	require EMCORE_DIR.'/classes/controller/admin/addons.php';
	require EMCORE_DIR.'/classes/controller/admin/help.php';

	// Views
	require EMCORE_DIR.'/classes/view/admin/modal/form.php';
	require EMCORE_DIR.'/classes/view/admin/modal/index.php';
	require EMCORE_DIR.'/classes/view/admin/theme/form.php';
	require EMCORE_DIR.'/classes/view/admin/settings/form.php';
	require EMCORE_DIR.'/classes/view/admin/addons.php';
	require EMCORE_DIR.'/classes/view/admin/help.php';
}
else
{
	require EMCORE_DIR.'/classes/modals.php';
	require EMCORE_DIR.'/classes/site.php';
	require EMCORE_DIR.'/classes/view/modal.php';
}

