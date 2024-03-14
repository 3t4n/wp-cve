<?php
	add_action('wp_ajax_iwp_login', 'iwpLogin');
	add_action('wp_ajax_iwp_signup', 'iwpSignUp');
	add_action('wp_ajax_iwp_recover_pass', 'iwpRecoverPass');
	add_action('wp_ajax_iwp_send_event','iwp_send_event');
	add_action('wp_ajax_iwp_get_applications','iwpGetApplications');
	add_action('wp_ajax_iwp_submit_2fa','iwpSubmit2Fa');
	add_action('wp_ajax_iwp_refresh_2fa','iwpRefresh2Fa');
	add_action('wp_ajax_iwp_finish_onBoarding','iwpFinishLogin');
	add_action('wp_ajax_iwp_disconnect','iwpDisconnect');

	add_action('wp_ajax_iwp_toggle_wp_status','iwpWebPushToggleStatus');
	add_action('wp_ajax_iwp_toggle_wp_location','iwpWebPushToggleLocation');

	add_action('wp_ajax_iwp_wp_create_web_push','iwpWebPushCreate');
	add_action('wp_ajax_iwp_wp_update_web_push','iwpWebPushUpdate');
	add_action('wp_ajax_iwp_wp_web_push_enable','iwpWebPushStatusEnable');
	add_action('wp_ajax_iwp_wp_web_push_disable','iwpWebPushStatusDisable');

	add_action('wp_ajax_iwp_wp_toggle_topics','iwpWebPushTopicsToggleStatus');
	add_action('wp_ajax_iwp_wp_toggle_topics_color','iwpWebPushTopicsToggleColor');
	add_action('wp_ajax_iwp_wp_create_topic','iwpCreateTopicAjax');
	add_action('wp_ajax_iwp_wp_update_topic','iwpUpdateTopicAjax');
	add_action('wp_ajax_iwp_wp_delete_topic','iwpDeleteTopicAjax');

	add_action('wp_ajax_iwp_toggle_wh_status','iwpWhatsAppChatToggleStatus');
	add_action('wp_ajax_iwp_wh_save','iwpWhatsAppChatSave');

	add_action('wp_ajax_iwp_toggle_developer_mode','iwpToggleDeveloperMode');

	function iwpToggleDeveloperMode() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/controllers/iwpFooterController.php';
		$ret = iwpFooterController::toggleDeveloperMode();
		echo($ret);
		die;
	}

	function iwpLogin() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/includes/iwpLoginUtils.php';
		$ret = iwpLoginUtils::iwpLogin();
		echo($ret);
		die;
	}

	function iwpSignUp() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/includes/iwpLoginUtils.php';
		$ret = iwpLoginUtils::iwpSignUp();
		echo($ret);
		die;
	}

	function iwpRecoverPass() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/includes/iwpLoginUtils.php';
		$ret = iwpLoginUtils::iwpRecoverPass();
		echo($ret);
		die;
	}

	function iwp_send_event() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/includes/iwpCustomEvents.php';
		$event = $_POST['event'];
		$eventData = json_decode($_POST['eventData'], true);
		$ret = iwpCustomEvents::sendCustomEvent($event, $eventData);
		echo($ret);
		die;
	}

	function iwpGetApplications() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/includes/iwpLoginUtils.php';
		$ret = iwpLoginUtils::iwpGetApplications();
		echo($ret);
		die;
	}

	function iwpSubmit2Fa() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/includes/iwpLoginUtils.php';
		$ret = iwpLoginUtils::iwpSubmit2Fa();
		echo($ret);
		die;
	}

	function iwpRefresh2Fa() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/includes/iwpLoginUtils.php';
		$ret = iwpLoginUtils::iwpRefresh2Fa();
		echo($ret);
		die;
	}

	function iwpFinishLogin() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/includes/iwpLoginUtils.php';
		$ret = iwpLoginUtils::iwpFinishLogin();
		echo($ret);
		die;
	}

	function iwpDisconnect() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/controllers/iwpHeaderController.php';
		$ret = iwpHeaderController::iwpDisconnect();
		echo($ret);
		die;
	}

	function iwpWebPushToggleStatus() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';
		require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
		require_once IWP_PLUGIN_PATH . 'admin/includes/iwpCustomEvents.php';

		$status = (int)iwpAdminUtils::getPOSTParam('status', 0);
		update_option(iwpPluginOptions::WEB_PUSH_STATUS, $status);

		$event = ($status === 1) ? iwpCustomEvents::MACRO_WP_ACTIVAR : iwpCustomEvents::MACRO_WP_DESACTIVAR;
		$ret = iwpCustomEvents::sendCustomEvent($event);
		echo($ret);
		die;
	}

	function iwpWebPushToggleLocation() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';
		require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
		require_once IWP_PLUGIN_PATH . 'admin/includes/iwpCustomEvents.php';

		$location = iwpAdminUtils::getPOSTParam('location', 'false');
		$location = filter_var($location, FILTER_VALIDATE_BOOLEAN);

		update_option(iwpPluginOptions::WEB_PUSH_LOCATION_ACCESS, $location);

		$event = ($location) ? iwpCustomEvents::MICRO_WP_LOCATION_ACTIVAR : iwpCustomEvents::MICRO_WP_LOCATION_DESACTIVAR;
		$ret = iwpCustomEvents::sendCustomEvent($event);
		echo($ret);
		die;
	}

	function iwpWebPushCreate() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/controllers/iwpWebPushController.php';
		$ret = iwpWebPushController::createWebPushAjax();
		echo($ret);
		die;
	}

	function iwpWebPushUpdate() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/controllers/iwpWebPushController.php';
		$ret = iwpWebPushController::updateWebPushAjax();
		echo($ret);
		die;
	}

	function iwpWebPushStatusEnable() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/controllers/iwpWebPushController.php';
		$ret = iwpWebPushController::changeWebPushStatusAjax(true);
		echo($ret);
		die;
	}
	function iwpWebPushStatusDisable() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/controllers/iwpWebPushController.php';
		$ret = iwpWebPushController::changeWebPushStatusAjax(false);
		echo($ret);
		die;
	}
	function iwpWebPushTopicsToggleStatus() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';
		require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
		require_once IWP_PLUGIN_PATH . 'admin/includes/iwpCustomEvents.php';

		$status = iwpAdminUtils::getPOSTParam('status', 'false');
		$status = filter_var($status, FILTER_VALIDATE_BOOLEAN);

		update_option(iwpPluginOptions::TOPICS_STATUS, $status);

		$event = ($status) ? iwpCustomEvents::MICRO_WP_TOPICS_ACTIVAR : iwpCustomEvents::MICRO_WP_TOPICS_DESACTIVAR;
		$ret = iwpCustomEvents::sendCustomEvent($event);
		echo($ret);
		die;
	}
	function iwpWebPushTopicsToggleColor() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';
		require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';

		$defaultColor = '#8db8ff'; // Definimos un color predeterminado por si acaso
		$color = iwpAdminUtils::getPOSTParam('color', $defaultColor);

		update_option(iwpPluginOptions::TOPICS_COLOR, $color);

		echo(true);
		die;
	}
	function iwpCreateTopicAjax() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/controllers/iwpWebPushController.php';
		$ret = iwpWebPushController::createTopicAjax();
		echo($ret);
		die;
	}
	function iwpUpdateTopicAjax() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/controllers/iwpWebPushController.php';
		$ret = iwpWebPushController::updateTopicAjax();
		echo($ret);
		die;
	}
	function iwpDeleteTopicAjax() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/controllers/iwpWebPushController.php';
		$ret = iwpWebPushController::deleteTopicAjax();
		echo($ret);
		die;
	}
	function iwpWhatsAppChatToggleStatus() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';
		require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
		require_once IWP_PLUGIN_PATH . 'admin/includes/iwpCustomEvents.php';

		$status = (int)iwpAdminUtils::getPOSTParam('status', 0);
		update_option(iwpPluginOptions::WH_STATUS, $status);

		$event = ($status === 1) ? iwpCustomEvents::MACRO_WA_ACTIVAR : iwpCustomEvents::MACRO_WA_DESACTIVAR;
		$ret = iwpCustomEvents::sendCustomEvent($event);
		echo($ret);
		die;
	}
	function iwpWhatsAppChatSave() {
		initLangAjax();
		require_once IWP_PLUGIN_PATH . 'admin/controllers/iwpWhatsAppChatController.php';
		$ret = iwpWhatsAppChatController::saveWhatsAppChatAjax();
		echo($ret);
		die;
	}


	/***** *****/
	/**
	 * Revisa si el archivo de traducciones se ha cargado. En caso afirmativo, no se hace nada. De lo contrario,
	 * 		se recoge el idioma de la web, se comprueba si el archivo mo existe y si existe, se carga.
	 *		Si no existe el archivo mo del idioma, se carga la versión inglesa.
	 * @return void
	 */
	function initLangAjax() {
		require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';
		// El archivo de traducciones no se ha cargado
		$langPath = IWP_PLUGIN_PATH . 'languages';
		$domain = 'iwp-text-domain';
		$moExtension = '.mo';
		$lang = iwpAdminUtils::getGETParam('lang', false);
		$locale = $lang ?: get_locale();
		$moFile = "{$langPath}/{$domain}-{$locale}{$moExtension}";
		if (file_exists($moFile)) {
			// El archivo con el idioma completo, sí existe: en_US, en_GB, es_ES, es_MX...
			goto loadFile;
		}

		$miniLocale = substr($locale, 0, 2);
		$moFile = "{$langPath}/{$domain}-{$miniLocale}{$moExtension}";
		if (file_exists($moFile)) {
			// El archivo con el idioma resumido, sí existe: es, en...
			goto loadFile;
		}

		// Cargamos el archivo predeterminado del idioma, la versión en inglés
		$defaultLocale = 'en';
		$moFile = "{$langPath}/{$domain}-{$defaultLocale}{$moExtension}";

		loadFile:
		load_textdomain( 'iwp-text-domain', $moFile);
	}