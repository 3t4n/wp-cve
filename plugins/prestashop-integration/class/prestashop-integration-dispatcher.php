<?php
/*  Copyright 2010-2023  François Pons  (email : fpons@aytechnet.fr)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

global $prestashop_integration;
if ( $prestashop_integration->psabspath != '' &&
     file_exists( $prestashop_integration->psabspath . 'config/config.inc.php' ) &&
     file_exists( $prestashop_integration->psabspath . 'classes/Dispatcher.php' ) &&
     $prestashop_integration->pspath == '' || $prestashop_integration->pspath == '.' ) {
	require_once( $prestashop_integration->psabspath . 'config/config.inc.php' );

	class PrestaShopIntegration_Dispatcher extends Dispatcher {

		/**
		 * Get current instance of dispatcher (singleton)
		 *
		 * @return Dispatcher
		 */
		public static function getInstance()
		{
			if (!self::$instance)
				self::$instance = new PrestaShopIntegration_Dispatcher();
			return self::$instance;
		}

		/**
		 * Find the controller and instantiate it
		 */
		public function dispatch()
		{
			$controller_class = '';

			// Get current controller
			$this->getController();
			if (!$this->controller)
				if (method_exists($this, 'useDefaultController'))
					$this->controller = $this->useDefaultController();
				else
					$this->controller = $this->default_controller;

			if (version_compare(_PS_VERSION_, '1.7', '>')) {
				// Execute hook dispatcher before
				Hook::exec('actionDispatcherBefore', array('controller_type' => $this->front_controller));
			}

			// Dispatch with right front controller
			switch ($this->front_controller)
			{
				// Dispatch front office controller
				case self::FC_FRONT :
					$controllers = Dispatcher::getControllers(array(_PS_FRONT_CONTROLLER_DIR_, _PS_OVERRIDE_DIR_.'controllers/front/'));

					$controllers['index'] = 'IndexController';
					if (isset($controllers['auth']))
						$controllers['authentication'] = $controllers['auth'];
					if (isset($controllers['compare']))
						$controllers['productscomparison'] = $controllers['compare'];
					if (isset($controllers['contact']))
						$controllers['contactform'] = $controllers['contact'];

					if (!isset($controllers[strtolower($this->controller)]))
							$this->controller = $this->controller_not_found;
					$controller_class = $controllers[strtolower($this->controller)];
					$params_hook_action_dispatcher = array('controller_type' => self::FC_FRONT, 'controller_class' => $controller_class, 'is_module' => 0);
				break;

				// Dispatch module controller for front office
				case self::FC_MODULE :
					$module_name = Validate::isModuleName(Tools::getValue('module')) ? Tools::getValue('module') : '';
					$module = Module::getInstanceByName($module_name);
					$controller_class = 'PageNotFoundController';
					if (Validate::isLoadedObject($module) && $module->active)
					{
						$controllers = Dispatcher::getControllers(_PS_MODULE_DIR_.$module_name.'/controllers/front/');
						if (isset($controllers[strtolower($this->controller)]))
						{
							include_once(_PS_MODULE_DIR_.$module_name.'/controllers/front/'.$this->controller.'.php');
							if (file_exists(_PS_OVERRIDE_DIR_ . "modules/$module_name/controllers/front/{$this->controller}.php")) {
								include_once(_PS_OVERRIDE_DIR_ . "modules/$module_name/controllers/front/{$this->controller}.php");
								$controller_class = $module_name . $this->controller . 'ModuleFrontControllerOverride';
							} else {
								$controller_class = $module_name . $this->controller . 'ModuleFrontController';
							}
						}
					}
					$params_hook_action_dispatcher = array('controller_type' => self::FC_FRONT, 'controller_class' => $controller_class, 'is_module' => 1);
				break;

				// Dispatch back office controller + module back office controller
				case self::FC_ADMIN :
					if ($this->use_default_controller && !Tools::getValue('token') && Validate::isLoadedObject(Context::getContext()->employee) && Context::getContext()->employee->isLoggedBack()) {
						Tools::redirectAdmin("index.php?controller={$this->controller}&token=" . Tools::getAdminTokenLite($this->controller));
					}

					$tab = Tab::getInstanceFromClassName($this->controller);
					$retrocompatibility_admin_tab = null;

					if ($tab->module)
					{
						if (file_exists(_PS_MODULE_DIR_.$tab->module.'/'.$tab->class_name.'.php'))
							$retrocompatibility_admin_tab = _PS_MODULE_DIR_.$tab->module.'/'.$tab->class_name.'.php';
						else
						{
							$controllers = Dispatcher::getControllers(_PS_MODULE_DIR_.$tab->module.'/controllers/admin/');
							if (!isset($controllers[strtolower($this->controller)]))
							{
								$this->controller = $this->controller_not_found;
								$controller_class = 'AdminNotFoundController';
							}
							else
							{
								$controller_name = $controllers[strtolower($this->controller)];
								// Controllers in modules can be named AdminXXX.php or AdminXXXController.php
								include_once(_PS_MODULE_DIR_.$tab->module.'/controllers/admin/'.$controllers[strtolower($this->controller)].'.php');
								if (file_exists(_PS_OVERRIDE_DIR_ . "modules/{$tab->module}/controllers/admin/$controller_name.php")) {
									include_once(_PS_OVERRIDE_DIR_ . "modules/{$tab->module}/controllers/admin/$controller_name.php");
									$controller_class = $controller_name . ( strpos($controller_name,'Controller') ? 'Override' : 'ControllerOverride' );
								} else {
								    $controller_class = $controller_name . ( strpos($controller_name, 'Controller') ? '' : 'Controller' );
								}
							}
						}
						$params_hook_action_dispatcher = array('controller_type' => self::FC_ADMIN, 'controller_class' => $controller_class, 'is_module' => 1);
					}
					else
					{
						$controllers = Dispatcher::getControllers(array(_PS_ADMIN_DIR_.'/tabs/', _PS_ADMIN_CONTROLLER_DIR_, _PS_OVERRIDE_DIR_.'controllers/admin/'));

						if (!isset($controllers[strtolower($this->controller)]))
							$this->controller = $this->controller_not_found;
						$controller_class = $controllers[strtolower($this->controller)];
						$params_hook_action_dispatcher = array('controller_type' => self::FC_ADMIN, 'controller_class' => $controller_class, 'is_module' => 0);

						if (file_exists(_PS_ADMIN_DIR_.'/tabs/'.$controller_class.'.php'))
							$retrocompatibility_admin_tab = _PS_ADMIN_DIR_.'/tabs/'.$controller_class.'.php';
					}

					// @retrocompatibility with admin/tabs/ old system
					if ($retrocompatibility_admin_tab)
					{
						include_once($retrocompatibility_admin_tab);
						include_once(_PS_ADMIN_DIR_.'/functions.php');
						runAdminTab($this->controller, !empty($_REQUEST['ajaxMode']));
						return;
					}
				break;

				default :
					throw new PrestaShopException('Bad front controller chosen');
			}

			// PrestaShop Integration : if the page is not found
			// return false in order to allow WordPress to continue
			global $prestashop_integration;
			error_log("PrestaShop Integration: uri=[".$_SERVER[REQUEST_URI].", controller_class=$controller_class");
			if ($controller_class == 'PageNotFoundController' ||
			    $controller_class == 'IndexController' && $prestashop_integration->wordpress_homepage)
				return false;

			// Instantiate controller
			try {
				// Loading controller
				$controller = Controller::getController($controller_class);

				// Execute hook dispatcher
				if (isset($params_hook_action_dispatcher))
					Hook::exec('actionDispatcher', $params_hook_action_dispatcher);

				// Running controller
				$controller->run();

				error_log("PrestaShop Integration: controller_class=$controller_class has been used!");

				// Execute hook dispatcher after
				if (version_compare(_PS_VERSION_, '1.7', '>'))
					if (isset($params_hook_action_dispatcher)) {
						Hook::exec('actionDispatcherAfter', $params_hook_action_dispatcher);
				}

				// PrestaShop Integration: return controller
				return $controller;
			}
			catch (PrestaShopException $e)
			{
				$e->displayMessage();
			}

			return false;
		}
	}
}
