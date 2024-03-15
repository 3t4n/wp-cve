<?php
/**
 * @package Unite Gallery for Joomla 1.7-3.5
 * @author Valiano
 * @copyright (C) 2022 Unite Gallery, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNITEGALLERY_INC') or die('Restricted access');


$folderIncludes = dirname(__FILE__)."/";

	//include provider classes
	require_once $folderIncludes . 'provider/provider_db.class.php';
	require_once $folderIncludes . 'provider/provider_functions.class.php';
	require_once $folderIncludes . 'provider/helper_provider.class.php';

	require_once $folderIncludes . 'functions.php';
	require_once $folderIncludes . 'functions.class.php';
	require_once $folderIncludes . 'provider/functions_wordpress.class.php';

	require_once $folderIncludes . 'db.class.php';
	require_once $folderIncludes . 'settings.class.php';
	require_once $folderIncludes . 'cssparser.class.php';
	require_once $folderIncludes . 'settings_advances.class.php';
	require_once $folderIncludes . 'settings_output.class.php';
	require_once $folderIncludes . 'settings_product.class.php';
	require_once $folderIncludes . 'settings_product_sidebar.class.php';
	
	require_once $folderIncludes . 'html_output_base.class.php';
	require_once $folderIncludes . 'settings_new.class.php';
	require_once $folderIncludes . 'settings_new_output.class.php';
	require_once $folderIncludes . 'settings_new_output_wide.class.php';
	
	require_once $folderIncludes . 'image_generation.class.php';
	require_once $folderIncludes . 'zip.class.php';
	
	require_once $folderIncludes . 'base_admin.class.php';
	
	require_once $folderIncludes . 'elements_base.class.php';
	require_once $folderIncludes . 'base_output.class.php';
	require_once $folderIncludes . 'helper_base.class.php';
	
?>
