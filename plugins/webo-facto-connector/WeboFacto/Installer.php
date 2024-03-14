<?php
/**
 * Gestion de l'installation, de l'activation et de l'activation du webo-facto connector
 *
 * @author Medialibs
 */
class WeboFacto_Installer
{
	private $pluginMainFile;

	/**
	 * Constructeur
	 *
	 * @return null
	 */
	public function __construct()
	{

	}

	/**
	 * Initialisation
	 *
	 * @param  string $pluginMainFile Fichier principal du plugin
	 *
	 * @return null
	 */
	public function init($pluginMainFile)
	{
		register_activation_hook($pluginMainFile, [$this, 'onActivation']);
		register_deactivation_hook($pluginMainFile, [$this, 'onDeactivation']);
	}

	/**
	 * Action réalisée à l'activation du plugin
	 *
	 * @return null
	 */
	public function onActivation()
	{
		if (!WeboFacto_Utils::getInstance()->canActivatePlugin()) {
			return null;
		}
		return (new WeboFacto_Soap())->updateSite(WeboFacto_Utils::getInstance()->getServerName(),
												  WeboFacto_Utils::getInstance()->getSiteType(),
												  get_bloginfo('version'),
												  WeboFacto_Utils::getInstance()->getLoginPage());
	}

	/**
	 * Action réalisée à la désactivation du plugin
	 *
	 * @return null
	 */
	public function onDeactivation()
	{
		if (!WeboFacto_Utils::getInstance()->canDeactivatePlugin()) {
			return null;
		}
		return (new WeboFacto_Soap())->updateSite(WeboFacto_Utils::getInstance()->getServerName(),
												  WeboFacto_Utils::getInstance()->getSiteType(),
												  get_bloginfo('version'),
												  '');
	}
}
