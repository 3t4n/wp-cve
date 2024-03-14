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

// this should be already loaded from autoload.php
VAPLoader::import('libraries.adapter.version.listener');

/**
 * Helper class used to adapt the application to the requirements
 * of the installed WordPress version.
 *
 * @see 	VersionListener 	Used to evaluate the current WordPress version.
 *
 * @since  	1.6.3
 * @since 	1.7    Renamed from UIApplicationWordpress
 */
class VAPApplicationWordpress extends VAPApplication
{	
	/**
	 * Backward compatibility for WordPress admin list <table> class.
	 *
	 * @return 	string 	The class selector to use.
	 */
	public function getAdminTableClass()
	{
		return 'wp-list-table widefat striped';
	}
	
	/**
	 * Backward compatibility for WordPress admin list <table> head opening.
	 *
	 * @return 	string 	The <thead> tag to use.
	 */
	public function openTableHead()
	{
		return '<thead>';
	}
	
	/**
	 * Backward compatibility for WordPress admin list <table> head closing.
	 *
	 * @return 	string 	The </thead> tag to use.
	 */
	public function closeTableHead()
	{
		return '</thead>';
	}
	
	/**
	 * Backward compatibility for WordPress admin list <th> class.
	 *
	 * @param 	string 	$align 	The additional class to use for horizontal alignment.
	 *							Accepted rules should be: left, center or right.
	 *
	 * @return 	string 	The class selector to use.
	 */
	public function getAdminThClass($align = 'center')
	{
		return 'manage-column ' . $align;
	}
	
	/**
	 * Backward compatibility for WordPress admin list checkAll JS event.
	 *
	 * @param 	integer  The total count of rows in the table.	
	 *
	 * @return 	string 	 The check all checkbox input to use.
	 */
	public function getAdminToggle($count)
	{
		return '<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle" />';
	}
	
	/**
	 * Backward compatibility for WordPress admin list isChecked JS event.
	 *
	 * @return 	string 	The JS function to use.
	 */
	public function checkboxOnClick()
	{
		return 'Joomla.isChecked(this.checked);';
	}

	/**
	 * Helper method to send e-mails.
	 *
	 * @param 	string 	 $from_address	The e-mail address of the sender.
	 * @param 	string 	 $from_name 		The name of the sender.
	 * @param 	string 	 $to 			The e-mail address of the receiver.
	 * @param 	string 	 $reply_address 	The reply to e-mail address.
	 * @param 	string 	 $subject 		The subject of the e-mail.
	 * @param 	string 	 $hmess 			The body of the e-mail (HTML is supported).
	 * @param 	array 	 $attachments 	The list of the attachments to include.
	 * @param 	boolean  $is_html 		True to support HTML body, otherwise false for plain text.
	 * @param 	string 	 $encoding 		The encoding to use.
	 *
	 * @return 	boolean  True if the e-mail was sent successfully, otherwise false.
	 */
	public function sendMail($from_address, $from_name, $to, $reply_address, $subject, $hmess, $attachments = null, $is_html = true, $encoding = 'base64')
	{
		$mailer = JFactory::getMailer();
		
		if ($is_html)
		{
			$hmess = "<html>\n<head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"></head>\n<body>$hmess</body>\n</html>";
		}

		$sender = array($from_address, $from_name);
		$mailer->setSender($sender);
		$mailer->addRecipient($to);
		
		if ($reply_address)
		{
			$mailer->addReplyTo($reply_address);
		}

		$mailer->setSubject($subject);
		$mailer->setBody($hmess);
		$mailer->isHTML($is_html);

		$mailer->Encoding = $encoding;

		if ($attachments !== null && is_array($attachments))
		{
			foreach ($attachments as $attach)
			{
				if (!empty($attach) && file_exists($attach))
				{
					$mailer->addAttachment($attach);
				}
			}
		}

		return $mailer->Send();
	}
	
	/**
	 * Backward compatibility for WordPress framework loading.
	 *
	 * @param 	string 	$fw 	The framework to load. 
	 */
	public function loadFramework($fw = '')
	{
		JHtml::fetch($fw);
	}

	/**
	 * Backward compatibility for Joomla add stylesheet.
	 *
	 * @param   string  $url      URL to the linked style sheet.
	 * @param   array   $options  Array of options. Example: array('version' => 'auto', 'conditional' => 'lt IE 9').
	 * @param   array   $attribs  Array of attributes. Example: array('id' => 'scriptid', 'async' => 'async', 'data-test' => 1).
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 */
	public function addStyleSheet($url = '', $options = array(), $attribs = array())
	{
		if (empty($url))
		{
			return;
		}

		/**
		 * Add versioning to options array in order
		 * to reset the cache every time a new update is released.
		 * Versioning is used only in case it wasn't specified.
		 */
		if (!isset($options['version']))
		{
			// make sure the constant is defined
			if (defined('VIKAPPOINTMENTS_SOFTWARE_VERSION'))
			{
				$options['version'] = VIKAPPOINTMENTS_SOFTWARE_VERSION;
			}
		}
		else if (empty($options['version']) || $options['version'] == 'auto')
		{
			// unset versioning
			unset($options['version']);
		}
		
		// use JHtml to load native dependencies when needed
		JHtml::fetch('stylesheet', $url, $options, $attribs);
	}

	/**
	 * Backward compatibility for WordPress add script.
	 *
	 * @param   string  $file     Path to file.
	 * @param   array   $options  Array of options. Example: array('version' => 'auto', 'conditional' => 'lt IE 9').
	 * @param   array   $attribs  Array of attributes. Example: array('id' => 'scriptid', 'async' => 'async', 'data-test' => 1).
	 *
	 * @return 	void
	 */
	public function addScript($file = '', $options = array(), $attribs = array())
	{
		if (empty($file))
		{
			return;
		}

		/**
		 * Add versioning to options array in order
		 * to reset the cache every time a new update is released.
		 * Versioning is used only in case it wasn't specified.
		 *
		 * @since 1.7
		 */
		if (!isset($options['version']))
		{
			// make sure the constant is defined
			if (defined('VIKAPPOINTMENTS_SOFTWARE_VERSION'))
			{
				$options['version'] = VIKAPPOINTMENTS_SOFTWARE_VERSION;
			}
		}
		else if (empty($options['version']) || $options['version'] == 'auto')
		{
			// unset versioning
			unset($options['version']);
		}

		// use JHtml to load native dependencies when needed
		JHtml::fetch('script', $file, $options, $attribs);
	}

	/**
	 * Backward compatibility for card/row opening.
	 *
	 * @param 	string 	$class 	 The class attribute for the fieldset.
	 * @param 	string 	$id 	 The ID attribute for the fieldset.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.7
	 */
	public function openCard($class = '', $id = '')
	{
		return '<div class="row-fluid' . ($class ? ' ' . $class : '') . '"' . ($id ? ' id="' . $id . '' : '') . '>';
	}

	/**
	 * Backward compatibility for card/row closing.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.7
	 */
	public function closeCard()
	{
		return '</div>';
	}
	
	/**
	 * Backward compatibility for WordPress fieldset opening.
	 *
	 * @param 	string 	$legend  The title of the fieldset.
	 * @param 	string 	$class 	 The class attribute for the fieldset.
	 * @param 	string 	$id 	 The ID attribute for the fieldset.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.3
	 */
	public function openFieldset($legend, $class = '', $id = '')
	{
		static $incrementalId = 0;
		static $useScript     = true;

		if (!$id)
		{
			// generate a unique ID if not supplied
			$id = 'vap-postbox-' . (++$incrementalId);
		}

		$input = JFactory::getApplication()->input;

		$data = array();
		$data['name']    = $legend;
		$data['class']   = $class;
		$data['id']      = $id;
		$data['tag']     = preg_replace("/[^a-z0-9_]+/i", '_', implode('.', [$input->get('option'), $input->get('view'), $id]));
		$data['visible'] = $input->cookie->getBool($data['tag'], true);

		if ($useScript)
		{
			// use script only once
			$useScript = false;

			// include script from layout
			$script = JLayoutHelper::render('html.form.fieldset.script');
		}
		else
		{
			$script = '';
		}

		return JLayoutHelper::render('html.form.fieldset.open', $data) . $script;
	}
	
	/**
	 * Backward compatibility for WordPress fieldset closing.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.3
	 */
	public function closeFieldset()
	{
		return JLayoutHelper::render('html.form.fieldset.close');
	}

	/**
	 * Backward compatibility for WordPress empty fieldset opening.
	 *
	 * @param 	string 	$class 	An additional class to use for the fieldset.
	 * @param 	string 	$id 	The ID attribute for the fieldset.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.3
	 */
	public function openEmptyFieldset($class = '', $id = '')
	{
		return $this->openFieldset('', $class, $id);
	}
	
	/**
	 * Backward compatibility for WordPress empty fieldset opening.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.3
	 */
	public function closeEmptyFieldset()
	{
		return $this->closeFieldset();
	}
	
	/**
	 * Backward compatibility for WordPress control opening.
	 *
	 * @param 	string 	$label 	The label of the control field.
	 * @param 	string 	$class 	The class of the control field.
	 * @param 	mixed 	$attr 	The additional attributes to add (string or array).
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.3
	 */
	public function openControl($label, $class = '', $attr = array())
	{
		$data = array();

		if (is_string($attr))
		{
			// string is not supported on WordPress platform
			trigger_error(sprintf('%s() expects parameter 3 to be array, %s given', __METHOD__, gettype($attr)), E_USER_NOTICE);

			// use empty attributes
			$attr = array();
		}

		foreach ($attr as $k => $v)
		{
			$data[$k] = $v;
		}

		$data['label'] = $label;
		$data['class'] = $class;

		return JLayoutHelper::render('html.form.control.open', $data);
	}
	
	/**
	 * Backward compatibility for WordPress control closing.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.3
	 */
	public function closeControl()
	{
		return JLayoutHelper::render('html.form.control.close');
	}

	/**
	 * Prepares the editor scripts for being used.
	 * Useful in case the editor is initialized via JavaScript/AJAX.
	 *
	 * @param 	string 	$name 	The name of the editor.
	 *
	 * @return 	void
	 *
	 * @since 	1.6.3
	 */
	public function prepareEditor($name)
	{
		switch (strtolower($name))
		{
			case 'tinymce':
				JHtml::fetch('behavior.tinyMCE');
				break;

			case 'codemirror':
				JHtml::fetch('behavior.codeMirror');
				break;  
		}
	}

	/**
	 * Returns the specified editor.
	 *
	 * @param 	mixed	 $editor  The editor to load.
	 * 							  The default one if not specified.
	 *
	 * @return 	JEditor  The editor instance.
	 *
	 * @since 	1.7
	 */
	public function getEditor($editor = null)
	{
		return JFactory::getEditor($editor);
	}
	
	/**
	 * Returns the codemirror editor in WordPress 4.9+, otherwise a simple textarea.
	 *
	 * @param 	string 	$name 	The name of the textarea.
	 * @param 	string 	$value 	The value of the textarea.
	 * @param 	array   $params  An array of options (@since 1.7).
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.4
	 */
	public function getCodeMirror($name, $value, array $params = array())
	{
		if (VersionListener::isLowerThan('4.9'))
		{
			// CodeMirror is not supported on WP lower than 4.9, use a plain textarea
			$editorName = 'none';
		}
		else
		{
			// use CoreMirror editor
			$editorName = 'codemirror';
		}

		// display editor
		return JEditor::getInstance($editorName)->display($name, $value, '100%', 600, 30, 30, $buttons = false, $id = null, $params);
	}
	
	/**
	 * Backward compatibility for WordPress Bootstrap tabset opening.
	 *
	 * @param 	string 	$group 	The group of the tabset.
	 * @param 	string 	$attr 	The attributes to use.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.4
	 */
	public function bootStartTabSet($group, $attr = array())
	{
		/**
		 * In case the cookie attribute was included within the,
		 * array, we can register the script that will be used to 
		 * handle the tab changes. The last selected tab will
		 * be stored in a cookie in order to be pre-selected
		 * when refreshing the page.
		 *
		 * @since 1.7
		 */
		if (isset($attr['cookie']))
		{
			$this->bootstrapTabSetCookie = '<script>' . JHtml::fetch('vaphtml.scripts.tabhandler', $group, $attr['cookie']) . '</script>';
		}
		else
		{
			$this->bootstrapTabSetCookie = '';
		}

		return JHtml::fetch('bootstrap.startTabSet', $group, $attr);
	}
	
	/**
	 * Backward compatibility for WordPress Bootstrap tabset closing.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.4
	 */
	public function bootEndTabSet()
	{
		/**
		 * Append the 'cookie' script after creating the tabset.
		 *
		 * @since 1.7
		 */

		return JHtml::fetch('bootstrap.endTabSet') . $this->bootstrapTabSetCookie;
	}
	
	/**
	 * Backward compatibility for WordPress Bootstrap add tab.
	 *
	 * @param 	string 	$group    The tabset parent group.
	 * @param 	string 	$id       The id of the tab.
	 * @param 	string 	$label    The title of the tab.
	 * @param 	array 	$options  A list of options.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.4
	 */
	public function bootAddTab($group, $id, $label, array $options = array())
	{
		/**
		 * In case the badge option is specified, append a badge
		 * to the tab label, displaying the count of records.
		 *
		 * @since 1.7
		 */
		if (isset($options['badge']))
		{
			$badge_class = isset($options['badge']['class']) ? $options['badge']['class'] : 'badge-info';
			$badge_id    = isset($options['badge']['id'])    ? $options['badge']['id']    : $id . '_tab_badge';
			$badge_count = isset($options['badge']['count']) ? $options['badge']['count'] : (int) $options['badge'];

			$label .= "<span class=\"badge {$badge_class} tab-badge-count\" id=\"{$badge_id}\" data-count=\"{$badge_count}\"> </span>";
		}

		return JHtml::fetch('bootstrap.addTab', $group, $id, $label);
	}
	
	/**
	 * Backward compatibility for WordPress Bootstrap end tab.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.4
	 */
	public function bootEndTab()
	{
		return JHtml::fetch('bootstrap.endTab');
	}
	
	/**
	 * Backward compatibility for WordPress Bootstrap open modal JS event.
	 *
	 * @param 	string 	$onclose 	The javascript function to call on close event.
	 *
	 * @return 	string 	The javascript function.
	 *
	 * @since 	1.5
	 */
	public function bootOpenModalJS($onclose = '')
	{
		if ($onclose)
		{
			$onclose .= '();';
		}

		return 
<<<JS
		var on_hide = null;

		if ("$onclose") {
			on_hide = function() {
				$onclose
			}
		}
		
		wpOpenJModal(id, url, null, on_hide);

		return false;
JS
		;
	}
	
	/**
	 * Backward compatibility for WordPress Bootstrap dismiss modal JS event.
	 *
	 * @param 	string 	$selector 	The selector to identify the modal box.
	 *
	 * @return 	string 	The javascript function.
	 *
	 * @since 	1.5
	 */
	public function bootDismissModalJS($selector = null)
	{
		if (!is_null($selector))
		{
			/**
			 * The static selector is no more supported due
			 * to the possibility of conflicts between 2 or more
			 * modals loaded within the same page.
			 *
			 * @deprecated 1.8
			 */
			JFactory::getDocument()->addScriptDeclaration('console.warn("The selector is deprecated in ' . __METHOD__ . '()");');
		}

		return 
<<<JS
		if ('$selector') {
			id = '$selector';
		}

		wpCloseJModal(id);
JS
		;

		return 
<<<JS
		wpCloseJModal('$selector');
JS
		;
	}

	/**
	 * Adds javascript support for Bootstrap popovers.
	 *
	 * @param 	string 	$selector   Selector for the popover.
	 * @param 	array 	$options    An array of options for the popover.
	 * 					Options for the popover can be:
	 * 						animation  boolean          apply a css fade transition to the popover
	 *                      html       boolean          Insert HTML into the popover. If false, jQuery's text method will be used to insert
	 *                                                  content into the dom.
	 *                      placement  string|function  how to position the popover - top | bottom | left | right
	 *                      selector   string           If a selector is provided, popover objects will be delegated to the specified targets.
	 *                      trigger    string           how popover is triggered - hover | focus | manual
	 *                      title      string|function  default title value if `title` tag isn't present
	 *                      content    string|function  default content value if `data-content` attribute isn't present
	 *                      delay      number|object    delay showing and hiding the popover (ms) - does not apply to manual trigger type
	 *                                                  If a number is supplied, delay is applied to both hide/show
	 *                                                  Object structure is: delay: { show: 500, hide: 100 }
	 *                      container  string|boolean   Appends the popover to a specific element: { container: 'body' }
	 */
	public function attachPopover($selector = '.wpPopover', array $options = array())
	{
		static $loaded = array();

		$sign = serialize(array($selector, $options));

		if (!isset($loaded[$sign]))
		{
			// do not sanitize HTML contents
			$options['sanitize'] = false;

			/**
			 * In case the "container" attribute is not set,
			 * always place the popover within the body.
			 *
			 * @since 1.6.5
			 */
			if (!isset($options['container']))
			{
				$options['container'] = 'body';
			}

			$data = $options ? json_encode($options) : '{}';
			JFactory::getDocument()->addScriptDeclaration(
<<<JS
jQuery(document).ready(function() {
	jQuery('$selector').popover($data);
});
JS
			);

			$loaded[$sign] = 1;
		}
	}

	/**
	 * Create a standard tag and attach a popover event.
	 * NOTE. FontAwesome framework MUST be loaded in order to work.
	 *
	 * @param 	array 	$options     An array of options for the popover.
	 *
	 * @see 	VAPApplication::attachPopover() for further details about options keys.
	 *
	 * @since 	1.6
	 */
	public function createPopover(array $options = array())
	{
		$icon = isset($options['icon_class']) ? $options['icon_class'] : 'fas fa-question-circle';

		$icon = isset($options['icon']) ? 'fas fa-' . $options['icon'] : $icon;

		$template = "<i class=\"{$icon} vap-quest-popover\" {popover}></i>";

		return $this->_popover($template, $options);
	}

	/**
	 * Create a text span and attach a popover event.
	 *
	 * @param 	array 	$options    An array of options for the popover.
	 *
	 * @see 	VAPApplication::attachPopover() for further details about options keys.
	 *
	 * @since 	1.6
	 */
	public function textPopover(array $options = array())
	{
		$title 		= isset($options['title']) ? $options['title'] : '[MISSING TITLE]';
		$template 	= "<span class=\"inline-popover vap-quest-popover\" {popover}>{$title}</span>";

		return $this->_popover($template, $options);
	}

	/**
	 * Creates a popover using the provided template.
	 *
	 * @param 	string 	$template 	The popover template.
	 * @param 	array 	$options    An array of options for the popover.
	 *
	 * @return 	string 	The popover HTML.
	 *
	 * @see 	VAPApplication::attachPopover() for further details about options keys.
	 */
	protected function _popover($template, array $options)
	{
		$layout = new JLayoutFile('html.plugins.popover', null, array('component' => 'com_vikappointments'));

		$options['html'] 		= true;
		$options['title']		= isset($options['title'])	 	? $options['title']		: '';
		$options['content'] 	= isset($options['content']) 	? $options['content']  	: '';
		$options['trigger'] 	= isset($options['trigger']) 	? $options['trigger']  	: 'hover focus';
		$options['placement'] 	= isset($options['placement']) 	? $options['placement'] : 'right';
		$options['template']	= isset($options['template'])	? $options['template']	: $layout->render();

		// attach an empty array option so that the data will be recovered 
		// directly from the tag during the runtime
		$this->attachPopover(".vap-quest-popover", array());

		$attr = '';
		foreach ($options as $k => $v)
		{
			$attr .= "data-{$k}=\"" . esc_attr($v) . "\" ";
		}

		return str_replace('{popover}', $attr, $template);
	}

	/**
	 * Return the WordPress date format specs.
	 *
	 * @param 	string 	$format 	  The format to use.
	 * @param 	array 	&$attributes  Some attributes to use.
	 *
	 * @return 	string 	The adapted date format.
	 *
	 * @since 	1.6
	 */
	public function jdateFormat($format = null, array &$attributes = array())
	{
		if ($format === null)
		{
			$format = VAPFactory::getConfig()->getString('dateformat');

			if (!empty($attributes['showTime']))
			{
				// concat the time format (24 hours format only)
				$format .= ' H:i';
			}
		}

		// strip % from date format string, which was required in Joomla
		return str_replace('%', '', $format);
	}

	/**
	 * Provides support to handle the WordPress calendar across different frameworks.
	 *
	 * @param 	mixed 	$value 		 The date or the timestamp to fill.
	 * @param 	string 	$name 		 The input name.
	 * @param 	string 	$id 		 The input id attribute.
	 * @param 	string 	$format 	 The date format.
	 * @param 	array 	$attributes  Some attributes to use.
	 * 
	 * @return 	string 	The calendar field.
	 *
	 * @since 	1.6
	 */
	public function calendar($value, $name, $id = null, $format = null, array $attributes = array())
	{
		$format = $this->jdateFormat($format, $attributes);

		JHtml::fetch('behavior.calendar');

		return JHtml::fetch('calendar', $value, $name, $id, $format, $attributes);
	}

	/**
	 * Method used to obtain a WordPress media form field.
	 *
	 * @return 	string 	The media in HTML.
	 *
	 * @since 	1.6
	 */
	public function getMediaField($name, $value = null, array $data = array())
	{
		// import form field class
		JLoader::import('adapter.form.field');

		// create XML field manifest
		$xml = "<field name=\"$name\" type=\"media\" />";

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

	/**
	 * Method used to handle the reCAPTCHA events.
	 *
	 * @param 	string 	$event 		The reCAPTCHA event to trigger.
	 * 								Here's the list of the accepted events:
	 * 								- display 	Returns the HTML used to display the ReCAPTCHA input;
	 *								- check 	Validates the POST data to make sure the ReCAPTCHA input was checked.
	 * @param 	array  	$options 	A configuration array.
	 *
	 * @return 	mixed 	The event response.
	 *
	 * @since 	1.6
	 */
	public function reCaptcha($event = 'display', array $options = array())
	{
		$response = null;

		/**
		 * Trigger action to perform the specified ReCAPTCHA event.
		 *
		 * @param 	mixed 	$response  The response to return.
		 * @param	array	$options   A configuration array.
		 *
		 * @since 	1.0
		 */
		do_action_ref_array('vik_recaptcha_' . strtolower($event), array(&$response, $options));

		return $response;
	}

	/**
	 * Checks if the com_user captcha is configured.
	 * In case the parameter is set to global, the default one
	 * will be retrieved.
	 * 
	 * @param 	string 	 $plugin  The plugin name to check. Leave empty
	 * 							  to use any type of captcha.
	 *
	 * @return 	boolean  True if configured, otherwise false.
	 *
	 * @since 	1.6
	 */
	public function isCaptcha($plugin = null)
	{
		/**
		 * Trigger action to check whether the ReCAPTCHA plugin is supported.
		 *
		 * @param 	boolean  $active  True if active, false otherwise.
		 *
		 * @since 	1.0
		 */
		return apply_filters('vik_' . $plugin . '_on', false);
	}

	/**
	 * Checks if the global captcha is configured.
	 * 
	 * @param 	string 	 $plugin  The plugin name to check. Leave empty
	 * 							  to use any type of captcha.
	 *
	 * @return 	boolean  True if configured, otherwise false.
	 *
	 * @since 	1.6
	 */
	public function isGlobalCaptcha($plugin = null)
	{
		return $this->isCaptcha($plugin);
	}

	/**
	 * Rewrites an internal URI that needs to be used outside of the website.
	 * This means that the routed URI MUST start with the base path of the site.
	 *
	 * @param 	mixed    $query   The query string or an associative array of data.
	 * @param 	boolean  $xhtml   Replace & by &amp; for XML compliance.
	 * @param 	mixed    $itemid  The itemid to use. If null, the current one will be used.
	 *
	 * @return 	string   The complete routed URI.
	 *
	 * @since 	1.6
	 */
	public function routeForExternalUse($query = '', $xhtml = true, $itemid = null)
	{
		// check if the query already specifies the Itemid
		if (!preg_match("/&Itemid=[\d]*/", $query))
		{
			// try to extract view from query
			if (preg_match("/&view=([a-z0-9_]+)(?:&|$)/", $query, $match))
			{
				$view = end($match);
			}
			else
			{
				$view = null;
			}

			// import shortcodes model
			$model 	= JModel::getInstance('vikappointments', 'shortcodes', 'admin');
			$itemid = $model->best($view);

			if ($itemid)
			{
				// update query with Itemid found
				$query .= (strpos($query, '?') === false ? '?' : '&') . 'Itemid=' . $itemid;
			}
		}

		// route URL
		return JRoute::rewrite($query, false);
	}

	/**
	 * Routes an admin URL for being used outside from the website (complete URI).
	 *
	 * @param 	mixed    $query  The query string or an associative array of data.
	 * @param 	boolean  $xhtml  Replace & by &amp; for XML compliance.
	 *
	 * @return 	string   The complete routed URI. 
	 *
	 * @since 	1.6.3
	 */
	public function adminUrl($query = '', $xhtml = true)
	{
		$app = JFactory::getApplication();

		if (is_array($query))
		{
			// make sure the array is not empty
			if ($query)
			{
				$query = '?' . http_build_query($query);
			}
			else
			{
				$query = '';
			}

			// the query is an array, build the query string
			$query = 'index.php' . $query;
		}

		// replace initial index.php with admin.php
		$query = preg_replace("/^index\.php/", 'admin.php', $query);
		// replace option=com_vikappointments with page=vikappointments
		$query = preg_replace("/(&|\?)option=com_vikappointments/", '$1page=vikappointments', $query);

		// finalise admin URI
		$uri = JUri::root() . 'wp-admin/' . $query;

		if ($xhtml)
		{
			$uri = str_replace('&', '&amp;', $uri);
		}

		return $uri;
	}

	/**
	 * Prepares a plain/routed URL to be used for an AJAX request.
	 *
	 * @param 	mixed    $query  The query string or a routed URL.
	 * @param 	boolean  $xhtml  Replace & by &amp; for XML compliance.
	 *
	 * @return 	string   The AJAX end-point URI.
	 *
	 * @since 	1.7
	 */
	public function ajaxUrl($query = '', $xhtml = false)
	{
		// instantiate path based on specified query
		$path = new JUri($query);

		// delete option var from query
		$path->delVar('option');

		// force action in query
		$path->setVar('action', 'vikappointments');

		// force application client in case of front-end
		if (JFactory::getApplication()->isClient('site'))
		{
			$path->setVar('vik_ajax_client', 'site');
		}

		// create AJAX URI
		$uri = admin_url('admin-ajax.php') . '?' . $path->getQuery();

		if ($xhtml)
		{
			// try to make "&" XML safe
			$uri = preg_replace("/&(?!amp;)/", '&amp;', $uri);
		}

		return $uri;
	}

	/**
	 * Includes the CSRF-proof token within the specified query string/URL.
	 *
	 * @param 	mixed 	 $query  The query string or a routed URL.
	 * @param 	boolean  $xhtml  Replace & by &amp; for XML compliance.
	 *
	 * @return 	string 	 The resulting path.
	 *
	 * @since 	1.7
	 */
	public function addUrlCSRF($query = '', $xhtml = false)
	{
		JLoader::import('adapter.session.session');

		// safely append the CSRF token within the query string
		$uri = JUri::getInstance($query);
		$uri->setVar(JSession::getFormTokenName(), JSession::getFormToken());

		if ($xhtml)
		{
			// try to make "&" XML safe
			$uri = preg_replace("/&(?!amp;)/", '&amp;', (string) $uri);
		}

		return (string) $uri;
	}

	/**
	 * Returns the platform base path.
	 *
	 * @return 	string
	 *
	 * @since 	1.7.1
	 */
	public function getAbsolutePath()
	{
		return ABSPATH;
	}

	/**
	 * Helper method to pre-load the assets needed for the Employee Area.
	 *
	 * @return 	void
	 *
	 * @since 	1.6
	 */
	public function loadEmployeeAreaAssets()
	{
		parent::loadEmployeeAreaAssets();

		// load bootstrap too
		$doc = JFactory::getDocument();

		$internalFilesOptions = array('version' => VIKAPPOINTMENTS_SOFTWARE_VERSION);

		$doc->addStyleSheet(VIKAPPOINTMENTS_CORE_MEDIA_URI . 'css/bootstrap.lite.css', $internalFilesOptions, array('id' => 'bootstrap-lite-style'));
		$doc->addScript(VIKAPPOINTMENTS_CORE_MEDIA_URI . 'js/bootstrap.min.js', $internalFilesOptions, array('id' => 'bootstrap-script'));
	}

	/**
	 * Returns a list of users that are currently logged-in.
	 *
	 * @param 	mixed 	 $limit   The query limit, if specified.
	 * @param 	integer  $offset  The query offset, if specified.
	 *
	 * @return 	array 	 A list of users.
	 *
	 * @since 	1.6.3
	 */
	public function getLoggedUsers($limit = null, $offset = 0)
	{
		// get record of currently logged-in users (get_users() returns always an array)
		$users = get_users([
		    'meta_key'     => 'session_tokens',
		    'meta_compare' => 'EXISTS',
		]);

		// splice the users list following the specified limits
		$users = array_splice($users, $offset, $limit);

		if (!$users)
		{
			// do not proceed in case of no users
			return array();
		}

		$dbo = JFactory::getDbo();

		$query = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'country_code')))
			->from($dbo->qn('#__vikappointments_users', 'c'));

		/**
		 * Here's a list of columns that have to be supported:
		 *
		 * @property  string   session_time  The session last time.
		 * @property  integer  jid 			 The user ID.
		 * @property  integer  id            The customer ID.
		 * @property  string   billing_name  The user nominative.
		 * @property  string   billing_mail  The user e-mail address.
		 * @property  string   country_code  The user 2-letters country code.
		 */

		// map array to extract a valid response
		return array_map(function($user) use ($query, $dbo)
		{
			$sessions = get_user_meta($user->ID, 'session_tokens', true);

			if (is_array($sessions) && $sessions)
			{
				// extract last session token
				$session    = end($sessions);
				$last_login = $session['login'];
			}
			else
			{
				// impossible to fetch last login
				$last_login = null;
			}

			$tmp = array();
			$tmp['jid']          = $user->ID;
			$tmp['session_time'] = $last_login;
			$tmp['billing_name'] = $user->display_name;
			$tmp['billing_mail'] = $user->user_email;
			$tmp['country_code'] = null;
			$tmp['id']           = null;

			// complete query
			$query->clear('where')->where(array(
				$dbo->qn('c.jid') . ' = ' . (int) $user->ID,
				$dbo->qn('c.billing_mail') . ' = ' . $dbo->q($user->user_email),
			), 'OR');

			$dbo->setQuery($query, 0, 1);
			
			// merge columns found
			$tmp = array_merge($tmp, (array) $dbo->loadAssoc());

			return $tmp;
		}, $users); 
	}

	/**
	 * Prepares the specified content before being displayed.
	 *
	 * @param 	mixed  &$content  The table content instance or a string to fetch.
	 * @param 	mixed  $params 	  True to apply the full description, false to apply 
	 * 							  the short description, if any. Any other non scalar 
	 * 							  value to pass a configuration for plugins.
	 *
	 * @return 	void
	 *
	 * @since 	1.6.3
	 */
	public function onContentPrepare(&$content, $params = array())
	{
		$pattern = "/(?:<p>)?<!--\s*more\s*-->(?:<\/p>)?/i";

		if (is_null($content))
		{
			$content = '';
		}

		// create Content table in case a string was passed
		if (is_string($content))
		{
			$text = $content;

			$content = JTable::getInstance('content');
			$content->text = $text;
		}

		if (is_bool($params))
		{
			// BC: take the specified type of text (full or short)
			$full = $params;

			// use an empty array
			$params = array();
		}
		else
		{
			/**
			 * Extract type of text from parameters.
			 * If not specified, the full text will be used.
			 *
			 * @since 1.7
			 */
			$full = isset($params['fulltext']) ? (bool) $params['fulltext'] : true;
		}

		// always replaces double line-breaks with paragraph elements
		$content->text = wpautop($content->text);

		/**
		 * Interprets shortcodes contained within the full text.
		 *
		 * @since 1.7
		 */
		$content->text = do_shortcode($content->text);

		// check if the description owns a readmore separator
		if (preg_match($pattern, $content->text))
		{
			// split the description in 2 chunks
			$chunks = preg_split($pattern, $content->text, 2);

			// overwrite text with short (0) or full (1) description
			$content->text = $chunks[$full ? 1 : 0];

			/**
			 * Register intro and full texts too.
			 *
			 * @since 1.7
			 */
			$content->introtext = $chunks[0];
			$content->fulltext  = $chunks[1];
		}
	}

	/**
	 * Returns a list of supported payment gateways.
	 *
	 * @return 	array 	A list of paths.
	 *
	 * @since 	1.6.3
	 */
	public function getPaymentDrivers()
	{
		// import payment dispatcher
		JLoader::import('adapter.payment.dispatcher');

		// get paths list of supported drivers
		return JPaymentDispatcher::getSupportedDrivers('vikappointments');
	}

	/**
	 * Returns the configuration form of a payment.
	 *
	 * @param 	string 	$payment  The name of the payment.
	 *
	 * @return 	mixed 	The configuration array/object.
	 *
	 * @since 	1.6.3
	 *
	 * @uses 	getPaymentInstance()
	 */
	public function getPaymentConfig($payment)
	{
		// instantiate payment and access its configuration form
		return $this->getPaymentInstance($payment)->getAdminParameters();
	}

	/**
	 * Provides a new payment instance for the specified arguments.
	 *
	 * @param 	string 	  $payment 	The name of the payment that should be instantiated.
	 * @param 	mixed 	  $order 	The details of the order that has to be paid.
	 * @param 	mixed 	  $config 	The payment configuration array or a JSON string.
	 *
	 * @return 	mixed 	  The payment instance.
	 *
	 * @throws 	RuntimeException
	 *
	 * @since 	1.6.3
	 */
	public function getPaymentInstance($payment, $order = array(), $config = array())
	{
		// import payment dispatcher
		JLoader::import('adapter.payment.dispatcher');

		// instantiate payment
		return JPaymentDispatcher::getInstance('vikappointments', $payment, $order, $config);
	}

	/**
	 * Returns the component manufacturer name or link.
	 *
	 * @param 	array   $options  An array of options:
	 * 							  - link (boolean) True to return a link, false to return the
	 * 								name only (false by default);
	 *							  - short (boolean) True to display the short manufacturer name,
	 * 								false otherwise (false by default);
	 * 							  - long (boolean) True to display the long manufacturer name,
	 * 								false otherwise (true by default);
	 * 							  - separator (string) A separator string to insert between the
	 * 								names fetched ('-' by default).
	 *
	 * @return  string  The manufacturer name or link.
	 *
	 * @since   1.6.3
	 */
	public function getManufacturer(array $options = array())
	{
		// add support for manufacturer default options
		$options['manufacturer'] = array(
			// specify a default URI
			'link'  => 'https://vikwp.com',
			// specify the manufacturer short name
			'short' => 'VikWP',
			// specify the manufacturer long name
			'long'  => 'vikwp.com',
		);

		// invoke parent to complete name building
		return parent::getManufacturer($options);
	}

	/**
	 * Checks whether the reservation can be completed.
	 *
	 * @return  boolean
	 *
	 * @since   1.6.3
	 */
	public function checkAvailability()
	{
		// always return true in WordPress
		return true;
	}

	/**
	 * Displays the platform alert.
	 *
	 * @param 	array 	$options  The alert display data.
	 *
	 * @return 	string  The HTML of the alert.
	 *
	 * @see 	alert()
	 *
	 * @since 	1.7
	 */
	protected function displayAlert(array $data)
	{
		// register script to handle cookie alert
		JHtml::fetch('vaphtml.scripts.cookiealert');

		// include INLINE class to keep the notice stuck on its position
		if (empty($data['attrs']['class']))
		{
			$data['attrs']['class'] = 'inline';
		}
		else
		{
			$data['attrs']['class'] .= ' inline';
		}

		// instantiate layout file
		$layout = new JLayoutFile('html.system.notice');

		// display layout
		return $layout->render($data);
	}

	/**
	 * Returns a list of supported SMS providers.
	 *
	 * @return 	array 	A list of paths.
	 *
	 * @since 	1.7
	 */
	public function getSmsDrivers()
	{
		// import sms dispatcher
		JLoader::import('adapter.sms.dispatcher');

		// get paths list of supported drivers
		return JSmsDispatcher::getSupportedDrivers('vikappointments');
	}

	/**
	 * Returns the configuration form of a SMS provider.
	 *
	 * @param 	string 	$driver  The name of the driver.
	 *
	 * @return 	mixed 	The configuration array/object.
	 *
	 * @since 	1.7
	 */
	public function getSmsConfig($driver)
	{
		// instantiate driver and access its configuration form
		return $this->getSmsInstance($driver)->getAdminParameters();
	}

	/**
	 * Provides a new SMS driver instance for the specified arguments.
	 *
	 * @param 	string 	  $driver 	The name of the provider that should be instantiated.
	 * 								If not specified, the default one will be used.
	 * @param 	mixed 	  $config 	The SMS configuration array or a JSON string.
	 * @param 	mixed 	  $order 	The details of the order that has to be notified.
	 *
	 * @return 	mixed 	  The driver instance.
	 *
	 * @throws 	RuntimeException
	 *
	 * @since 	1.7
	 */
	public function getSmsInstance($driver = null, $config = null, $order = array())
	{
		if (is_null($driver))
		{
			// get default driver if not specified
			$driver = VAPFactory::getConfig()->get('smsapi');
		}

		if (empty($driver))
		{
			// SMS API not configured
			throw new RuntimeException('SMS API framework not configured', 500);
		}

		if (is_null($config))
		{
			// get default configuration if not specified
			$config = VAPFactory::getConfig()->get('smsapifields');
		}

		// import sms dispatcher
		JLoader::import('adapter.sms.dispatcher');

		// instantiate sms
		return JSmsDispatcher::getInstance('vikappointments', $driver, $order, $config);
	}
}
