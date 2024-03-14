<?php 
/**
 * @package  LeadloversPlugin
 */
namespace LeadloversInc\Api\Callbacks;

use LeadloversInc\Base\BaseController;

class AdminCallbacks extends BaseController
{
	public function adminSettings()
	{
		return require_once( "$this->plugin_path/templates/settings.php" );
	}

	public function adminLog()
	{
		return require_once( "$this->plugin_path/templates/log.php" );
	}

	public function adminErrorLog()
	{
		return require_once( "$this->plugin_path/templates/error-log.php" );
	}

	public function leadloversApiKey()
	{
		$value = get_option( 'leadlovers_api_key' );
		echo '<input type="text" class="regular-text" name="leadlovers_api_key" value="' . esc_attr($value) . '" placeholder="Informe seu token pessoal" required>';
	}
}