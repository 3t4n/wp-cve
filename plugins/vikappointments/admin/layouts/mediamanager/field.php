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
 * @var  string   $name     The field name.
 * @var  string   $value    The selected media file.
 * @var  string   $id       The field ID attribute.
 * @var  array    $attrs    A list of field attributes.
 * @var  string   $modal    The media manager modal. In case the string is empty,
 *                          the modal has been already rendered by a different field.
 * @var  boolean  $preview  True to display the preview button.
 * @var  boolean  $filter   True to accept only images, false to accept text documents too.
 * @var  string   $icon     An icon to use for the upload/choose button.
 */
extract($displayData);

// fetch attributes string
$attrs_str = '';

foreach ($attrs as $k => $v)
{
	$attrs_str .= ' ' . $k;

	if (!is_bool($v))
	{
		$attrs_str .= ' = "' . $this->escape($v) . '"';
	}
}

?>
	
<div class="input-append vap-media-manager-field">

	<?php
	if (!empty($attrs['multiple']))
	{
		if (!is_array($value))
		{
			$value = $value ? (array) $value : array();
		}

		foreach ($value as $file)
		{
			?><input type="hidden" name="<?php echo $name; ?>" value="<?php echo $file; ?>" /><?php
		}

		$count = count($value);

		if ($count > 1)
		{
			$value = JText::plural('VAP_DEF_N_SELECTED', $count);
		}
		else
		{
			$value = (string) array_shift($value);
		}
	}
	?>

	<input
		type="text"
		readonly="readonly"
		name="<?php echo empty($attrs['multiple']) ? $name : ''; ?>"
		data-name="<?php echo $name; ?>"
		value="<?php echo (string) $value; ?>"
		id="<?php echo $id; ?>"
		data-filter="<?php echo (int) $filter; ?>"
		<?php echo $attrs_str; ?>
	/>

	<?php
	if ($preview)
	{
		?>
		<button type="button" class="btn media-preview" onclick="vapMediaStartPreview('#<?php echo $id; ?>', <?php echo $path ? '\'' . addslashes($path) . '\'' : 'null'; ?>);">
			<i class="fas fa-eye"></i>
		</button>
		<?php
	}
	?>

	<button type="button" class="btn media-select" onclick="vapMediaOpenJModal('#<?php echo $id; ?>', <?php echo $path ? '\'' . base64_encode($path) . '\'' : 'null'; ?>);">
		<i class="<?php echo $icon ? $icon : 'fas fa-image'; ?>"></i>
	</button>

</div>

<?php
if ($modal)
{
	echo $modal;
}
?>
