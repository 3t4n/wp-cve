<?php
/**
 * @package     VikRentItems
 * @subpackage  com_vikrentitems
 * @author      Alessio Gaggii - e4j - Extensionsforjoomla.com
 * @copyright   Copyright (C) 2018 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

defined('ABSPATH') or die('No script kiddies please!');

/**
 * Extends native application functions.
 *
 * @wponly 	the class extends VikApplication and uses different vars.
 * @since 	1.0
 * @see 	VikApplication
 */
class VriApplication extends VikApplication
{
	/**
	 * Additional commands container for any methods.
	 *
	 * @var array
	 */
	private $commands;

	/**
	 * This method loads an additional CSS file (if available)
	 * for the current CMS, and CMS version.
	 *
	 * @return void
	 **/
	public function normalizeBackendStyles()
	{
		$document = JFactory::getDocument();

		if (file_exists(VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'wp.css')) {
			$document->addStyleSheet(VRI_ADMIN_URI . 'helpers/' . 'wp.css');
		}
	}

	/**
	 * Includes a script URI.
	 *
	 * @param 	string 	$uri  The script URI.
	 *
	 * @return 	void
	 */
	public function addScript($uri)
	{
		JHtml::fetch('script', $uri);
	}

	/**
	* Sets additional commands for any methods. Like raise an error if the recipient email address is empty.
	* Returns this object for chainability.
	*/
	public function setCommand($key, $value)
	{
		if (!empty($key)) {
			$this->commands[$key] = $value;
		}
		return $this;
	}
	
	public function sendMail($from_address, $from_name, $to, $reply_address, $subject, $hmess, $is_html = true, $encoding = 'base64', $attachment = null)
	{
		if (strpos($to, ',') !== false) {
			$all_recipients = explode(',', $to);
			foreach ($all_recipients as $k => $v) {
				if (empty($v)) {
					unset($all_recipients[$k]);
				}
			}
			if (count($all_recipients) > 0) {
				$to = $all_recipients;
			}
		}

		if (empty($to)) {
			//Prevent Joomla Exceptions that would stop the script execution
			if (isset($this->commands['print_errors'])) {
				VikError::raiseWarning('', 'The recipient email address is empty. Email message could not be sent. Please check your configuration.');
			}
			return false;
		}
		
		if ($from_name == $from_address) {
			$mainframe = JFactory::getApplication();
			$attempt_fromn = $mainframe->get('fromname', '');
			if (!empty($attempt_fromn)) {
				$from_name = $attempt_fromn;
			}
		}

		return parent::sendMail($from_address, $from_name, $to, $reply_address, $subject, $hmess, $attachment, $is_html, $encoding);
	}

	/**
	* @param $arr_values array
	* @param $current_key string
	* @param $empty_value string (J3.x only)
	* @param $default
	* @param $input_name string
	* @param $record_id = '' string
	*/
	public function getDropDown($arr_values, $current_key, $empty_value, $default, $input_name, $record_id = '')
	{
		$dropdown = '';
		$dropdown .= '<select name="'.$input_name.'" onchange="document.adminForm.submit();">'."\n";
		$dropdown .= '<option value="">'.$default.'</option>'."\n";
		$list = "\n";
		foreach ($arr_values as $k => $v) {
			$dropdown .= '<option value="'.$k.'"'.($k == $current_key ? ' selected="selected"' : '').'>'.$v.'</option>'."\n";
		}
		$dropdown .= '</select>'."\n";

		return $dropdown;
	}

	public function loadSelect2()
	{
		//load JS + CSS
		$document = JFactory::getDocument();
		$document->addStyleSheet(VRI_ADMIN_URI.'resources/select2.min.css');
		$this->addScript(VRI_ADMIN_URI.'resources/select2.min.js');
	}

	/**
	 * Returns the HTML code to render a regular dropdown
	 * menu styled through the jQuery plugin Select2.
	 *
	 * @param 	$arr_values 	array
	 * @param 	$current_key 	string
	 * @param 	$input_name 	string
	 * @param 	$placeholder 	string 		used when the select has no selected option (it's empty)
	 * @param 	$empty_name 	[string] 	the name of the option to set an empty value to the field (<option>$empty_name</option>)
	 * @param 	$empty_val 		[string]	the value of the option to set an empty value to the field (<option>$empty_val</option>)
	 * @param 	$onchange 		[string] 	javascript code for the onchange attribute
	 * @param 	$idattr 		[string] 	the identifier attribute of the select
	 *
	 * @return 	string
	 */
	public function getNiceSelect($arr_values, $current_key, $input_name, $placeholder, $empty_name = '', $empty_val = '', $onchange = 'document.adminForm.submit();', $idattr = '')
	{
		//load JS + CSS
		$this->loadSelect2();

		//attribute
		$idattr = empty($idattr) ? rand(1, 999) : $idattr;

		//select
		$dropdown = '<select id="'.$idattr.'" name="'.$input_name.'"'.(!empty($onchange) ? ' onchange="'.$onchange.'"' : '').'>'."\n";
		if (!empty($placeholder) && empty($current_key)) {
			//in order for the placeholder value to appear, there must be a blank <option> as the first option in the select
			$dropdown .= '<option></option>'."\n";
		} else {
			//unset the placeholder to not pass it to the select2 object, or the empty value will not be displayed
			$placeholder = '';
		}
		if (strlen($empty_name) || strlen($empty_val)) {
			$dropdown .= '<option value="'.$empty_val.'">'.$empty_name.'</option>'."\n";
		}
		foreach ($arr_values as $k => $v) {
			$dropdown .= '<option value="'.$k.'"'.($k == $current_key ? ' selected="selected"' : '').'>'.$v.'</option>'."\n";
		}
		$dropdown .= '</select>'."\n";

		//js code
		$dropdown .= '<script type="text/javascript">'."\n";
		$dropdown .= 'jQuery(document).ready(function() {'."\n";
		$dropdown .= '	jQuery("#'.$idattr.'").select2('.(!empty($placeholder) ? '{placeholder: "'.addslashes($placeholder).'"}' : '').');'."\n";
		$dropdown .= '});'."\n";
		$dropdown .= '</script>'."\n";

		return $dropdown;
	}

	/**
	 * Returns the Script tag to render the Bootstrap JModal window.
	 * The suffix can be passed to generate other JS functions.
	 * Optionally pass JavaScript code for the 'show' and 'hide' events.
	 * Only compatible with Joomla > 3.x. jQuery must be defined.
	 *
	 * @param 	$suffix 	string
	 * @param 	$hide_js 	string
	 * @param 	$show_js 	string
	 *
	 * @return 	string
	 */
	public function getJmodalScript($suffix = '', $hide_js = '', $show_js = '')
	{
		static $loaded = array();

		$doc = JFactory::getDocument();

		if (!isset($loaded[$suffix]))
		{
			$doc->addScriptDeclaration(
<<<JS
function vriOpenJModal$suffix(id, modal_url, new_title) {

	var on_hide = null;

	if ("$hide_js") {
		on_hide = function() {
			$hide_js
		}
	}

	var on_show = null;

	if ("$show_js") {
		on_show = function() {
			$show_js
		}
	}
	
	wpOpenJModal(id, modal_url, on_show, on_hide);

	if (new_title) {
		jQuery('#jmodal-' + id + ' .modal-header h3').text(new_title);
	}

	return false;
}
JS
			);

			$loaded[$suffix] = 1;
		}

	}

	/**
	 * Loads the necessary JS, CSS, Script for the jQuery UI Datepicker.
	 * NOTE: the main VikRentItems Class must be defined, and this method is only for the back-end definitions.
	 *
	 * @since 	1.10
	 */
	public function loadDatePicker()
	{
		$document = JFactory::getDocument();
		$document->addStyleSheet(VRI_SITE_URI.'resources/jquery-ui.min.css');
		$this->addScript(VRI_SITE_URI.'resources/jquery-ui.min.js');
		$vri_df = VikRentItems::getDateFormat();
		$juidf = $vri_df == "%d/%m/%Y" ? 'dd/mm/yy' : ($vri_df == "%m/%d/%Y" ? 'mm/dd/yy' : 'yy/mm/dd');
		$ldecl = '
jQuery(function($){'."\n".'
	$.datepicker.regional["vikrentitems"] = {'."\n".'
		closeText: "'.JText::translate('VRIJQCALDONE').'",'."\n".'
		prevText: "'.JText::translate('VRIJQCALPREV').'",'."\n".'
		nextText: "'.JText::translate('VRIJQCALNEXT').'",'."\n".'
		currentText: "'.JText::translate('VRIJQCALTODAY').'",'."\n".'
		monthNames: ["'.JText::translate('VRMONTHONE').'","'.JText::translate('VRMONTHTWO').'","'.JText::translate('VRMONTHTHREE').'","'.JText::translate('VRMONTHFOUR').'","'.JText::translate('VRMONTHFIVE').'","'.JText::translate('VRMONTHSIX').'","'.JText::translate('VRMONTHSEVEN').'","'.JText::translate('VRMONTHEIGHT').'","'.JText::translate('VRMONTHNINE').'","'.JText::translate('VRMONTHTEN').'","'.JText::translate('VRMONTHELEVEN').'","'.JText::translate('VRMONTHTWELVE').'"],'."\n".'
		monthNamesShort: ["'.mb_substr(JText::translate('VRMONTHONE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHTWO'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHTHREE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHFOUR'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHFIVE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHSIX'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHSEVEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHEIGHT'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHNINE'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHTEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHELEVEN'), 0, 3, 'UTF-8').'","'.mb_substr(JText::translate('VRMONTHTWELVE'), 0, 3, 'UTF-8').'"],'."\n".'
		dayNames: ["'.JText::translate('VRISUNDAY').'", "'.JText::translate('VRIMONDAY').'", "'.JText::translate('VRITUESDAY').'", "'.JText::translate('VRIWEDNESDAY').'", "'.JText::translate('VRITHURSDAY').'", "'.JText::translate('VRIFRIDAY').'", "'.JText::translate('VRISATURDAY').'"],'."\n".'
		dayNamesShort: ["'.mb_substr(JText::translate('VRISUNDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRIMONDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRITUESDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRIWEDNESDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRITHURSDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRIFRIDAY'), 0, 3, 'UTF-8').'", "'.mb_substr(JText::translate('VRISATURDAY'), 0, 3, 'UTF-8').'"],'."\n".'
		dayNamesMin: ["'.mb_substr(JText::translate('VRISUNDAY'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRIMONDAY'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRITUESDAY'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRIWEDNESDAY'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRITHURSDAY'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRIFRIDAY'), 0, 2, 'UTF-8').'", "'.mb_substr(JText::translate('VRISATURDAY'), 0, 2, 'UTF-8').'"],'."\n".'
		weekHeader: "'.JText::translate('VRIJQCALWKHEADER').'",'."\n".'
		dateFormat: "'.$juidf.'",'."\n".'
		firstDay: '.VikRentItems::getFirstWeekDay().','."\n".'
		isRTL: false,'."\n".'
		showMonthAfterYear: false,'."\n".'
		yearSuffix: ""'."\n".'
	};'."\n".'
	$.datepicker.setDefaults($.datepicker.regional["vikrentitems"]);'."\n".'
});';
		$document->addScriptDeclaration($ldecl);
	}

	/**
	 * Loads the CMS's native datepicker calendar.
	 *
	 * @since 	1.10
	 */
	public function getCalendar($val, $name, $id = null, $df = null, array $attributes = array())
	{
		if ($df === null)
		{
			$df = VikRentItems::getDateFormat();
		}

		return parent::calendar($val, $name, $id, $df, $attributes);
	}

	/**
	 * Returns a masked e-mail address. The e-mail are masked using 
	 * a technique to encode the bytes in hexadecimal representation.
	 * The chunk of the masked e-mail will be also encoded to be HTML readable.
	 *
	 * @param 	string 	 $email 	The e-mail to mask.
	 * @param 	boolean  $reverse 	True to reverse the e-mail address.
	 * 								Only if the e-mail is not contained into an attribute.
	 *
	 * @return 	string 	 The masked e-mail address.
	 */
	public function maskMail($email, $reverse = false)
	{
		if ($reverse)
		{
			// reverse the e-mail address
			$email = strrev($email);
		}

		// converts the e-mail address from bin to hex
		$email = bin2hex($email);
		// append ;&#x sequence after every chunk of the masked e-mail
		$email = chunk_split($email, 2, ";&#x");
		// prepend &#x sequence before the address and trim the ending sequence
		$email = "&#x" . substr($email, 0, -3);

		return $email;
	}

	/**
	 * Returns a safemail tag to avoid the bots spoof a plain address.
	 *
	 * @param 	string 	 $email 	The e-mail address to mask.
	 * @param 	boolean  $mail_to 	True if the address should be wrapped
	 * 								within a "mailto" link.
	 *
	 * @return 	string 	 The HTML tag containing the masked address.
	 *
	 * @uses 	maskMail()
	 */
	public function safeMailTag($email, $mail_to = false)
	{
		// include the CSS declaration to reverse the text contained in the <safemail> tags
		JFactory::getDocument()->addStyleDeclaration('safemail {direction: rtl;unicode-bidi: bidi-override;}');

		// mask the reversed e-mail address
		$masked = $this->maskMail($email, true);

		// include the address into a custom <safemail> tag
		$tag = "<safemail>$masked</safemail>";

		if ($mail_to)
		{
			// mask the address for mailto command (do not use reverse)
			$mailto = $this->maskMail($email);

			// wrap the safemail tag within a mailto link
			$tag = "<a href=\"mailto:$mailto\" class=\"mailto\">$tag</a>";
		}

		return $tag;
	}

	/**
	 * Loads and echoes the script necessary to render the Fancybox
	 * plugin for jQuery to open images or iframes within a modal box.
	 * This resolves conflicts with some Bootstrap or Joomla (4) versions
	 * that do not support the old-native CSS class .modal with "behavior.modal".
	 * Mainly made to open pictures in a modal box, so the default "type" is set to "image".
	 * By passing a custom $opts string, the "type" property could be set to "iframe", but
	 * in this case it's better to use the other method of this class (Jmodal).
	 * The base jQuery library should be already loaded when using this method.
	 *
	 * @param 	string 	 	$selector 	The jQuery selector to trigger Fancybox.
	 * @param 	string  	$opts 		The options object for the Fancybox setup.
	 * @param 	boolean  	$reloadfunc If true, an additional function is included in the script
	 *									to apply again Fancybox to newly added images to the DOM (via Ajax).
	 *
	 * @return 	void
	 *
	 * @uses 	addScript()
	 */
	public function prepareModalBox($selector = '.vrimodal', $opts = '', $reloadfunc = false)
	{
		if (empty($opts)) {
			$opts = '';
		}
		$document = JFactory::getDocument();
		$document->addStyleSheet(VRI_SITE_URI.'resources/jquery.fancybox.css');
		$this->addScript(VRI_SITE_URI.'resources/jquery.fancybox.js');

		$reloadjs = '
		function reloadFancybox() {
			jQuery("'.$selector.'").fancybox('.$opts.');
		}
		';
		$js = '
		<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery("'.$selector.'").fancybox' . (!empty($opts) ? '('.$opts.')' : '()') . ';
		});'.($reloadfunc ? $reloadjs : '').'
		</script>';

		echo $js;
	}

	/**
	 * Method used to handle the reCAPTCHA events.
	 *
	 * @param 	string 	$event 		The reCAPTCHA event to trigger.
	 * 								Here's the list of the accepted events:
	 * 								- display 	Returns the HTML used to 
	 *											display the reCAPTCHA input.
	 *								- check 	Validates the POST data to make sure
	 * 											the reCAPTCHA input was checked.
	 * @param 	array  	$options 	A configuration array.
	 *
	 * @return 	mixed 	The event response.
	 *
	 * @since 	1.2.3
	 * @wponly 	the Joomla integration differs
	 */
	public function reCaptcha($event = 'display', array $options = array())
	{
		$response = null;
		// an optional configuration array (just leave empty)
		$options = array();
		// trigger reCAPTCHA display event to fill $response var
		do_action_ref_array('vik_recaptcha_' . $event, array(&$response, $options));
		// display reCAPTCHA by echoing it (empty in case reCAPTCHA is not available)
		return $response;
	}

	/**
	 * Checks if the com_user captcha is configured.
	 * In case the parameter is set to global, the default one
	 * will be retrieved.
	 * 
	 * @param 	string 	 $plugin  The plugin name to check ('recaptcha' by default).
	 *
	 * @return 	boolean  True if configured, otherwise false.
	 *
	 * @since 	1.2.3
	 * @wponly 	the Joomla integration differs
	 */
	public function isCaptcha($plugin = 'recaptcha')
	{
		return apply_filters('vik_' . $plugin . '_on', false);
	}

	/**
	 * Checks if the global captcha is configured.
	 * 
	 * @param 	string 	 $plugin  The plugin name to check ('recaptcha' by default).
	 *
	 * @return 	boolean  True if configured, otherwise false.
	 *
	 * @since 	1.2.3
	 */
	public function isGlobalCaptcha($plugin = 'recaptcha')
	{
		return $this->isCaptcha($plugin);
	}

	/**
	 * Method used to obtain a WordPress media form field.
	 *
	 * @return 	string 	The media in HTML.
	 *
	 * @since 	1.1.0
	 */
	public function getMediaField($name, $value = null, array $data = array())
	{
		// check if WordPress is installed
		if (defined('ABSPATH'))
		{
			add_action('admin_enqueue_scripts', function() {
				wp_enqueue_media();
			});

			// import form field class
			JLoader::import('adapter.form.field');

			// create XML field manifest
			$xml = "<field name=\"$name\" type=\"media\" modowner=\"vikrentitems\" />";

			// instantiate field
			$field = JFormField::getInstance(simplexml_load_string($xml));

			// overwrite name and value within data
			$data['name']  = $name;
			$data['value'] = $value;

			// inject display data within field instance
			foreach ($data as $k => $v)
			{
				$field->bind($v, $k);
			}

			// render field
			return $field->render();
		}

		// fallback to Joomla

		// init media field
		$field = new JFormFieldMedia(null, $value);
		// setup an empty form as placeholder
		$field->setForm(new JForm('vikrentitems.media'));

		// force field attributes
		$data['name']  = $name;
		$data['value'] = $value;

		if (empty($data['previewWidth']))
		{
			// there is no preview width, set a defualt value
			// to make the image visible within the popover
			$data['previewWidth'] = 480;	
		}

		// render the field	
		return $field->render('joomla.form.field.media', $data);
	}

}
