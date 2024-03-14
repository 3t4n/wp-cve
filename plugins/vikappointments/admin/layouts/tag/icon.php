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
 * Layout variables
 * -----------------
 * @var  object  $tag    The object holding the tag details.
 * @var  array   $attrs  A list of field attributes.
 */
extract($displayData);

if (!isset($attrs['style']))
{
	$attrs['style'] = '';
}

// check if the tag owns a color
if ($tag->color)
{
	// overwrite default color of the tag
	$attrs['style'] .= 'color: #' . $tag->color . ';';
}

$attrs['title'] = $tag->name;

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

?>

<i class="<?php echo $tag->icon . (!empty($attrs['class']) ? ' ' . $attrs['class'] : ''); ?> hasTooltip"<?php echo $attrs_str; ?>></i>
