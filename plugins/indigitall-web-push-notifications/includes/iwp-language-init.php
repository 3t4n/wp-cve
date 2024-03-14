<?php

	add_action('plugins_loaded', 'iwp_plugin_init');

	function iwp_plugin_init() {
		load_plugin_textdomain('iwp-text-domain', false, IWP_PLUGIN_REL_PATH . 'languages/');
		initLang();
	}

	/**
	 * Revisa si el archivo de traducciones se ha cargado. En caso afirmativo, no se hace nada. De lo contrario,
	 * 		se recoge el idioma de la web, se comprueba si el archivo mo existe y si existe, se carga.
	 *		Si no existe el archivo mo del idioma, se carga la versión inglesa.
	 * @return void
	 */
	function initLang() {
		if (!is_textdomain_loaded('iwp-text-domain')) {
			// El archivo de traducciones no se ha cargado
			$langPath = IWP_PLUGIN_PATH . 'languages';
			$domain = 'iwp-text-domain';
			$moExtension = '.mo';

			$locale = get_locale();
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
	}