<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  html.system
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Layout variables
 * -----------------
 * @var  string   $text     The text to display within the alert.
 * @var  string   $type     The alert type.
 * @var  string   $id       A unique alert signature.
 * @var  boolean  $dismiss  True if the alert can be dismissed.
 * @var  mixed    $expdate  If specified, indicates the date for
 *                          the cookie expiration.
 * @var  array    $attrs    A list of field attributes.
 */
extract($displayData);

// fetch attributes string
$attrs_str = '';

foreach ($attrs as $k => $v)
{
	if ($k != 'class')
	{
		$attrs_str .= ' ' . $k;

		if (!is_bool($v))
		{
			$attrs_str .= ' = "' . $this->escape($v) . '"';
		}
	}
}

if (!preg_match("/^<(.*?)>/", $text))
{
	// wrap text in a paragraph if doesn't start with a tag
	$text = '<p>' . $text . '</p>';
}

?>

<div class="notice notice-<?php echo $type . (!empty($attrs['class']) ? ' ' . $attrs['class'] : '') . ($dismiss ? ' is-dismissible' : ''); ?>"<?php echo $attrs_str; ?>>
	<?php echo $text; ?>
</div>
