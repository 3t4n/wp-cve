<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * VikAppointments HTML assets loader.
 *
 * @since 1.7
 */
abstract class VAPHtmlAssets
{
	/**
	 * A cache pool to keep track of the loaded resources.
	 *
	 * @var array
	 */
	protected static $cache = [];

	/**
	 * Includes the resources to use Fancybox jQuery add-on.
	 *
	 * @param 	mixed   $script   True to include the helper script too.
	 * 							  If not specified, it will be included
	 * 							  only for the back-end.
	 *
	 * @return 	void
	 *
	 * @link 	https://fancyapps.com/fancybox/3/
	 */
	public static function fancybox($script = null)
	{
		if (is_null($script))
		{
			$script = JFactory::getApplication()->isClient('administrator');
		}

		$vik = VAPApplication::getInstance();
		
		// check if already loaded
		if (!static::isLoaded(__METHOD__))
		{
			// include resources
			$vik->addScript(VAPASSETS_URI . 'js/jquery.fancybox.js', ['version' => '3.5.7']);
			$vik->addStylesheet(VAPASSETS_URI . 'css/jquery.fancybox.css', ['version' => '3.5.7']);
		}

		// check if script is already loaded
		if ($script && !static::isLoaded(__METHOD__, $script))
		{
			// include script too
			$vik->addScript(VAPASSETS_URI . 'js/vikappointments.js');
		}
	}

	/**
	 * Includes the resources to use Select2 jQuery add-on.
	 *
	 * @return 	void
	 *
	 * @link 	http://select2.github.io/select2/
	 */
	public static function select2()
	{
		// check if already loaded
		if (static::isLoaded(__METHOD__))
		{
			return;
		}

		$vik = VAPApplication::getInstance();
		
		// include resources
		$vik->addScript(VAPASSETS_URI . 'js/select2/select2.min.js', ['version' => '3.5.1']);
		$vik->addStylesheet(VAPASSETS_URI . 'js/select2/select2.css', ['version' => '3.5.1']);

		/**
		 * Try to load a specific language to translate native texts.
		 * DO NOT load all the translations as Select2 simply overrides
		 * the methods used to return the texts.
		 *
		 * @since 1.6.5
		 */
		$tag = explode('-', JFactory::getLanguage()->getTag());

		// fetch locale based on current Joomla! language
		$locale = 'select2_locale_' . strtolower($tag[0]) . '.js';

		// make sure the locale exists
		$path = implode(DIRECTORY_SEPARATOR, [VAPBASE, 'assets', 'js', 'select2', 'i18n', $locale]);

		if (is_file($path))
		{
			// load locale script after Select2 Core file
			$vik->addScript(VAPASSETS_URI . 'js/select2/i18n/' . $locale, ['version' => '3.5.1']);
		}
	}

	/**
	 * Includes the resources to use Google Maps APIs.
	 *
	 * @param 	string 	$key  The Google API Key. Use an empty string
	 * 					      to load the script without API Key.
	 *
	 * @return 	void
	 *
	 * @link 	https://developers.google.com/maps/documentation
	 */
	public static function googlemaps($key = null, $lib = null)
	{
		// check if already loaded
		if (static::isLoaded(__METHOD__))
		{
			return;
		}

		$vik = VAPApplication::getInstance();

		if (is_null($key))
		{
			// API Key not specified, recover it from config
			$key = VAPFactory::getConfig()->get('googleapikey');

			if (!$key)
			{
				// The administrator didn't specify a Google API Key.
				// Since the "Missing API Key" error doesn't trigger
				// the gm_authFailure() function, we should use a random
				// string just to be able to catch the error, which
				// would let us to unset the events registered by Google,
				// such as the "Auto-complete" field (Places API).
				$key = md5($_SERVER['REMOTE_ADDR']);
			}
		}

		$args = [];

		if ($key)
		{
			// specify key only if not empty
			$args['key'] = $key;
		}

		if ($lib)
		{
			// loads any additional library
			$args['libraries'] = $lib;
		}

		$uri = 'https://maps.googleapis.com/maps/api/js' . ($args ? '?' . http_build_query($args) : '');
		
		// include script
		$vik->addScript($uri, ['version' => 'auto']);
	}

	/**
	 * Includes the resources to use FontAwesome.
	 *
	 * @return 	void
	 *
	 * @link 	http://fontawesome.com/
	 */
	public static function fontawesome()
	{
		// check if already loaded
		if (static::isLoaded(__METHOD__))
		{
			return;
		}

		if (JFactory::getApplication()->isClient('administrator') && VersionListener::isJoomla50())
		{
			// do not load FontAwesome if we are in the administrator of a Joomla! 5.0 instance
			return;
		}

		$vik = VAPApplication::getInstance();

		// include resources
		$vik->addStylesheet(VAPASSETS_URI . 'css/fontawesome/css/font-awesome.min.css', ['version' => '5.15.3']);
		$vik->addStylesheet(VAPASSETS_URI . 'css/fontawesome/css/solid.min.css', ['version' => '5.15.3']);
		$vik->addStylesheet(VAPASSETS_URI . 'css/fontawesome/css/regular.min.css', ['version' => '5.15.3']);
		$vik->addStylesheet(VAPASSETS_URI . 'css/fontawesome/css/brands.min.css', ['version' => '5.15.3']);
	}

	/**
	 * Includes the resources to use Chart JS.
	 *
	 * @return 	void
	 *
	 * @link 	https://www.chartjs.org
	 */
	public static function chartjs()
	{
		// check if already loaded
		if (static::isLoaded(__METHOD__))
		{
			return;
		}

		$vik = VAPApplication::getInstance();

		// include resources
		$vik->addScript(VAPASSETS_URI . 'js/charts-framework/Chart.min.js', ['version' => '2.9.4']);
	}

	/**
	 * Loads the scripts needed to use Colorpicker jQuery plugin.
	 *
	 * @return 	void
	 *
	 * @link 	https://www.eyecon.ro/colorpicker/
	 */
	public static function colorpicker()
	{
		// check if already loaded
		if (static::isLoaded(__METHOD__))
		{
			return;
		}

		$vik = VAPApplication::getInstance();
		
		$vik->addScript(VAPASSETS_URI . 'js/colorpicker/colorpicker.js');
		$vik->addScript(VAPASSETS_URI . 'js/colorpicker/eye.js');
		$vik->addScript(VAPASSETS_URI . 'js/colorpicker/utils.js');

		$vik->addStyleSheet(VAPASSETS_URI . 'css/colorpicker/colorpicker.css');
	}

	/**
	 * Includes utils resources of the framework.
	 *
	 * @return 	void
	 */
	public static function utils()
	{
		// check if already loaded
		if (static::isLoaded(__METHOD__))
		{
			return;
		}

		$vik = VAPApplication::getInstance();

		// include resources
		$vik->addScript(VAPASSETS_URI . 'js/utils.js');
	}

	/**
	 * Includes the resources to support a jQuery context menu.
	 *
	 * @return 	void
	 */
	public static function contextmenu()
	{
		// check if already loaded
		if (static::isLoaded(__METHOD__))
		{
			return;
		}

		$vik = VAPApplication::getInstance();

		// include resources
		$vik->addScript(VAPASSETS_URI . 'js/contextmenu.js');
		$vik->addStylesheet(VAPASSETS_URI . 'css/contextmenu.css');
	}

	/**
	 * Includes the context menu that allows the
	 * possibility of selecting a status code.
	 *
	 * @return 	void
	 */
	public static function statuscodes()
	{
		// check if already loaded
		if (static::isLoaded(__METHOD__))
		{
			return;
		}

		// load jQuery context menu plugin
		static::contextmenu();

		$vik = VAPApplication::getInstance();

		// include resources
		$vik->addScript(VAPASSETS_URI . 'js/statuscodes.js');
	}

	/**
	 * Configures the Currency JS object.
	 *
	 * @return 	void
	 */
	public static function currency()
	{
		// check if already loaded
		if (static::isLoaded(__METHOD__))
		{
			return;
		}

		// load utils first
		self::utils();

		// get currency instance
		$currency = VAPFactory::getCurrency();

		$options = new stdClass;
		$options->position  = $currency->getPosition();
		$options->separator = $currency->getDecimalMark();
		$options->thousands = $currency->getThousandsMark();
		$options->digits 	= $currency->getDecimalDigits();

		$symbol  = $currency->getSymbol();
		$options = json_encode($options);

		JFactory::getDocument()->addScriptDeclaration(
<<<JS
(function($) {
	'use strict';

	$(function() {
		onInstanceReady(() => {
			return typeof Currency !== 'undefined';
		}).then(() => {
			Currency.getInstance('{$symbol}', {$options});
		});
	});
})(jQuery);
JS
		);
	}

	/**
	 * Includes the resources needed to handle a TOAST message.
	 *
	 * @param 	string   $position   The position in which the toast will be displayed.
	 * @param 	string 	 $container  The container to which append the toast.
	 *
	 * @return 	void
	 */
	public static function toast($position = null, $container = 'body')
	{
		$document = JFactory::getDocument();

		// a string was passed
		$config = '\'' . addslashes($position) . '\'';

		if (empty($position))
		{
			// no specified position
			$config = 'null';
		}
		else if (preg_match("/^[a-zA-Z0-9._]+$/i", $position))
		{
			// a variable was passed
			$config = "typeof {$position} !== 'undefined' ? {$position} : $config";
		}

		$container = '\'' . addslashes($container) . '\'';

		// check if already loaded
		if (static::isLoaded(__METHOD__))
		{
			// always overwrite position
			$document->addScriptDeclaration('jQuery(document).ready(function() { VAPToast.changePosition(' . $config . '); });');
			return;
		}

		$vik = VAPApplication::getInstance();

		// include resources
		$vik->addScript(VAPASSETS_URI . 'js/toast.js');
		$vik->addStylesheet(VAPASSETS_URI . 'css/toast.css');

		// create toast
		$document->addScriptDeclaration('jQuery(document).ready(function() { VAPToast.create(' . $config . ', ' . $container . '); });');
	}

	/**
	 * Includes the resources to use Intl-Tel jQuery add-on.
	 *
	 * @param 	string  $selector   All the elements matching the specified
	 * 								selector will be initialized using this
	 * 								jQuery add-on. Leave empty just to include
	 * 								the resources.
	 * @param 	array 	$config 	A configuration array.
	 *
	 * @return 	void
	 *
	 * @link 	https://github.com/jackocnr/intl-tel-input
	 */
	public static function intltel($selector = null, array $config = [])
	{
		$document = JFactory::getDocument();

		// check if already loaded
		if (!static::isLoaded(__METHOD__))
		{
			$vik = VAPApplication::getInstance();

			// include resources
			$vik->addScript(VAPASSETS_URI . 'js/tel/jquery.intlTelInput.min.js');
			$vik->addScript(VAPASSETS_URI . 'js/tel/utils.js');

			$vik->addStylesheet(VAPASSETS_URI . 'css/tel/css/intlTelInput.min.css');

			// decide whether to save the phone as plain number
			// or human-readable
			$save_plain = !empty($config['savePlain']) ? 1 : 0;

			// fetch validator instance (use 'validator' variable if not specified)
			$validator = !empty($config['validator']) ? $config['validator'] : 'validator';

			// include global script
			$document->addScriptDeclaration(
<<<JS
var vapIntlTelFormSubmit;
var vapIntlTelValidatorCallback;

jQuery(document).ready(function() {
	if (!vapIntlTelFormSubmit) {
		// before submit the form, include the dial code within the input
		vapIntlTelFormSubmit = function() {
			// get all form input
			jQuery(this).find('.iti input').filter('[type="text"],[type="tel"]').each(function() {
				var input = jQuery(this);

				if (input.val().length) {
					if ({$save_plain}) {
						input.val(input.intlTelInput('getNumber'));
					} else if (!input.val().match(/^\+/)){
						// prepend dial code only once
						var country = input.intlTelInput('getSelectedCountryData');
						input.val('+' + country.dialCode + ' ' + input.val());
					}
				}
			});
		};
	}

	// deregister event if already attached
	jQuery('form').off('submit', vapIntlTelFormSubmit);
	jQuery('form').on('submit', vapIntlTelFormSubmit);

	// check if the specified validator variable exists and it is an instance of VikFormValidator
	if (typeof {$validator} === 'object' && {$validator}.constructor.name === 'VikFormValidator') {
		if (!vapIntlTelValidatorCallback) {
			vapIntlTelValidatorCallback = function() {
				var ok = true;

				// get all form input
				jQuery({$validator}.form).find('.iti input.required').filter('[type="text"],[type="tel"]').each(function() {
					var input = jQuery(this);

					// always return false in case the input is required and empty
					if (input.val().length == 0 && input.hasClass('required')) {
						{$validator}.setInvalid(input);
						ok = false;

						// return true to continue
						return true;
					}

					// in case the input is not empty, check if the specified number is valid
					if (input.val().length && !input.intlTelInput('isValidNumber')) {
						// invalid number
						{$validator}.setInvalid(input);
						ok = false;

						// return true to continue
						return true;
					}

					// number is valid (or empty and optional)
					{$validator}.unsetInvalid(input);
				});

				return ok;
			};
		}

		// register validation callback
		{$validator}.removeCallback(vapIntlTelValidatorCallback);
		{$validator}.addCallback(vapIntlTelValidatorCallback);
	}
});
JS
			);
		}

		// check if already initialized
		if (!$selector || static::isLoaded(__METHOD__, $selector))
		{
			return;
		}

		$data = isset($config['data']) ? (array) $config['data'] : [];

		if (!isset($data['preferredCountries']))
		{
			// fetch preferred countries
			$data['preferredCountries'] = [];
			// inject default country code
			$data['preferredCountries'][] = strtolower(VAPCustomFields::getDefaultCountryCode());
			// iterate all supported languages
			foreach (VikAppointments::getKnownLanguages() as $lang)
			{
				if (preg_match("/-/", $lang))
				{
					// split language tag
					list($reg, $country) = explode('-', $lang);
				}
				else
				{
					// we have a language tag without country code, such as "el"
					$country = $lang;
				}

				// use as preferred country
				$data['preferredCountries'][] = strtolower($country);
			}

			// make list unique
			$data['preferredCountries'] = array_values(array_unique($data['preferredCountries']));
		}

		if (!isset($data['autoPlaceholder']))
		{
			// turn off default placeholder
			$data['autoPlaceholder'] = 'off';
		}

		// encode JSON data to initialize the input
		$json = json_encode(array_merge($data, $config));

		// initialize selectors
		$document->addScriptDeclaration(
<<<JS
jQuery(document).ready(function() {
	// create private function to handle auto-format
	function updatePhoneInput(event) {
		// Unbind this method from the input to avoid infinite loops.
		// When typing a prefix the plugin changes the country and trigger
		// the "countrychange" event, which re-call this method recursively.
		jQuery(this).off('change countrychange', updatePhoneInput);

		// format the phone number when the value or the country change
		var number = jQuery(this).intlTelInput('getNumber');
		jQuery(this).intlTelInput('setNumber', number);

		// re-activate event once the "countrychange" event has been already triggered
		jQuery(this).on('change countrychange', updatePhoneInput);
	}

	// always format phone when the number or the country change
	jQuery('{$selector}').intlTelInput({$json}).on('change countrychange', updatePhoneInput);

	// set maximum width of country dropdown
	jQuery('{$selector}').on('open:countrydropdown', function() {
		var dialog = jQuery(this).closest('.iti').find('.iti__country-list');

		// reset max width
		dialog.css('max-width', 'initial');

		// make sure the dialog doesn't exceed the screen bounds
		if (dialog.offset().left + dialog.outerWidth() > jQuery(window).width()) {
			dialog.css('max-width', jQuery(this).outerWidth());
		}
	});
});
JS
		);
	}

	/**
	 * Loads the environment CSS.
	 * 
	 * @return 	void
	 * 
	 * @since 	1.7.2
	 */
	public static function environment()
	{
		// check if already loaded
		if (static::isLoaded(__METHOD__))
		{
			return;
		}

		$vik = VAPApplication::getInstance();

		// create customizer model
		$customizer = JModelVAP::getInstance('customizer');

		// include the CSS file containing the environment variables
		$vik->addStyleSheet($customizer->getEnvironmentUrl(), [
			'version' => filemtime($customizer->getEnvironmentFile()),
		]);
	}

	/**
	 * Loads the custom CSS file.
	 * 
	 * @return 	void
	 * 
	 * @since 	1.7.2
	 */
	public static function customcss()
	{
		// check if already loaded
		if (static::isLoaded(__METHOD__))
		{
			return;
		}

		$vik = VAPApplication::getInstance();

		// build path of custom CSS file
		$custom_css_path = VAPBASE . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'vap-custom.css';

		if (is_file($custom_css_path))
		{
			// include the CSS file containing the custom rules
			$vik->addStyleSheet(VAPASSETS_URI . 'css/vap-custom.css', [
				'version' => filemtime($custom_css_path),
			]);
		}
	}

	/**
	 * Helper method used to check whether a resource was loaded or not.
	 * This method accepts any number of arguments, which will be used
	 * as signature for the cache.
	 *
	 * @return 	boolean  True if already loaded, false otherwise.
	 */
	protected static function isLoaded()
	{
		// create signature
		$sign = md5(serialize(func_get_args()));

		if (isset(static::$cache[$sign]))
		{
			// already loaded
			return true;
		}

		// cache because it will be used after returning to the caller
		static::$cache[$sign] = 1;

		// not loaded yet
		return false;
	}
}
