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

$fields = !empty($displayData['fields']) ? $displayData['fields'] : array();
$params = !empty($displayData['params']) ? $displayData['params'] : array();
$prefix = !empty($displayData['prefix']) ? $displayData['prefix'] : '';

if (JFactory::getApplication()->isClient('administrator'))
{
	$base = null;
}
else
{
	// site client, take layouts from admin folder
	$base = VAPADMIN . DIRECTORY_SEPARATOR . 'layouts';
}

// create control layouts
$openControl  = new JLayoutFile('form.control.open', $base);
$closeControl = new JLayoutFile('form.control.close', $base);

if (count($fields))
{
	foreach ($fields as $key => $f)
	{
		if (is_string($f))
		{
			/**
			 * String provided, directly echo the HTML.
			 * 
			 * @since 1.7.4
			 */
			echo $f;
			continue;
		}

		if (isset($params[$key]))
		{
			// always overwrite value with the given one
			$f['value'] = $params[$key];
		}

		if (!isset($f['value']) || (is_string($f['value']) && strlen($f['value']) === 0 && $f['type'] !== 'checkbox'))
		{
			if (!empty($f['default']))
			{
				// use default value
				$f['value'] = $f['default'];
			}
		}

		if (!empty($f['help']))
		{
			// register help within description
			$f['description'] = $f['help'];
		}

		if (!empty($f['label']) && strpos($f['label'], '//') !== false)
		{
			// extract help string from label
			$_label_arr = explode('//', $f['label']);
			// trim trailing colon
			$f['label'] = str_replace(':', '', $_label_arr[0]);
			// overwrite field description
			$f['description'] = $_label_arr[1];
		}

		if (empty($f['name']))
		{
			$f['name'] = $key;
		}

		if (empty($f['id']))
		{
			// build ID from name
			$f['id'] = preg_replace("/[^a-zA-Z0-9_\-]+/", '-', $f['name']);
		}

		if ($prefix)
		{
			// add prefix before name
			$f['name'] = $prefix . $f['name'];
			// add prefix before ID
			$f['id'] = $prefix . $f['id'];
		}

		if ($f['type'] == 'custom')
		{
			$f['type'] = 'html';
		}
		else if ($f['type'] == 'checkbox' && !empty($f['value']))
		{
			$f['checked'] = true;
		}

		// check if we have a required field
		if (!empty($f['required']))
		{
			// add "required" class
			$f['class'] = empty($f['class']) ? 'required' : $f['class'] . ' required';
		}

		if (!empty($f['multiple']) && !preg_match("/\[\]$/", $f['name']))
		{
			// append array notation in case the field accepts multiples values
			$f['name'] .= '[]';
		}

		if ($f['type'] == 'password' && $base && !isset($f['toggle']))
		{
			// disable password/text toggle button if we are in the front-end
			$f['toggle'] = false;
		}

		// do not display control and label in case of hidden field
		if ($f['type'] != 'hidden' && empty($f['hidden']))
		{
			// open control
			echo $openControl->render($f);
		}

		// render field
		echo $this->sublayout($f['type'], $f);

		// do not display control in case of hidden field
		if ($f['type'] != 'hidden' && empty($f['hidden']))
		{
			// close control
			echo $closeControl->render();
		}
	}
}
else
{
	// no parameters found
	echo VAPApplication::getInstance()->alert(JText::translate('VAPMANAGEPAYMENT9'));
}
