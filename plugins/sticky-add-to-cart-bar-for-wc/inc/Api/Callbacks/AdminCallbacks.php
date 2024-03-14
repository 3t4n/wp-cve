<?php 
/**
 * @package  WooCart
 */
namespace WscInc\Api\Callbacks;

use WscInc\Base\BaseController;

class AdminCallbacks extends BaseController
{
	// Add template files
	public function adminDashboard(){
		require_once $this->plugin_path.'templates/dashboard.php';
	}
}