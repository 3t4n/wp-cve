<?php 
/**
 * @package  LeadloversPlugin
 */
namespace LeadloversInc\Pages;

use LeadloversInc\Api\SettingsApi;
use LeadloversInc\Base\BaseController;
use LeadloversInc\Api\Callbacks\AdminCallbacks;

/**
* 
*/
class Admin extends BaseController
{
	public $settings;

	public $callbacks;

	public $pages = array();

	public $subpages = array();

	public function register() 
	{
		$this->settings = new SettingsApi();

		$this->callbacks = new AdminCallbacks();

		$this->setPages();

		$this->setSubpages();

		$this->setSettings();
		$this->setSections();
		$this->setFields();

		$this->settings->addPages( $this->pages )->withSubPage( 'Configurações' )->addSubPages( $this->subpages )->register();
	}

	public function setPages() 
	{
		$this->pages = array(
			array(
				'page_title' => 'Leadlovers Plugin', 
				'menu_title' => 'Leadlovers', 
				'capability' => 'manage_options', 
				'menu_slug' => 'leadlovers_plugin', 
				'callback' => array( $this->callbacks, 'adminSettings' ), 
				'icon_url' => 'dashicons-text', 
				'position' => 110
			)
		);
	}

	public function setSubpages()
	{
		$this->subpages = array(
			array(
				'parent_slug' => 'leadlovers_plugin', 
				'page_title' => 'Minhas integrações', 
				'menu_title' => 'Minhas integrações', 
				'capability' => 'manage_options', 
				'menu_slug' => 'edit.php?post_type=ll-integrations', 
				'callback' => null
			),
			array(
				'parent_slug' => 'leadlovers_plugin', 
				'page_title' => 'Log de capturas', 
				'menu_title' => 'Log de capturas', 
				'capability' => 'manage_options', 
				'menu_slug' => 'edit.php?post_type=ll-capture-logs', 
				'callback' => null
			),
			array(
				'parent_slug' => 'leadlovers_plugin', 
				'page_title' => 'Log de erros', 
				'menu_title' => 'Log de erros', 
				'capability' => 'manage_options', 
				'menu_slug' => 'edit.php?post_type=ll-error-logs', 
				'callback' => null
			)
		);
	}

	public function setSettings()
	{
		$args = array(
			array(
				'option_group' => 'leadlovers_options_group',
				'option_name' => 'leadlovers_api_key'
			)
		);

		$this->settings->setSettings( $args );
	}

	public function setSections()
	{
		$args = array(
			array(
				'id' => 'leadlovers_admin_index',
				'title' => 'Configurações',
				'page' => 'leadlovers_plugin'
			)
		);

		$this->settings->setSections( $args );
	}

	public function setFields()
	{
		$args = array(
			array(
				'id' => 'leadlovers_api_key',
				'title' => 'Token Pessoal',
				'callback' => array( $this->callbacks, 'leadloversApiKey' ),
				'page' => 'leadlovers_plugin',
				'section' => 'leadlovers_admin_index',
				'args' => array(
					'label_for' => 'leadlovers_api_key',
				)
			)
		);

		$this->settings->setFields( $args );
	}
}