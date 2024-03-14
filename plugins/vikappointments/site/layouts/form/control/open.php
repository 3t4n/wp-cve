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

$vik = VAPApplication::getInstance();

$id       = isset($displayData['id'])          ? $displayData['id']          : '';
$required = isset($displayData['required'])    ? $displayData['required']    : false;
$label    = isset($displayData['label'])       ? $displayData['label']       : '';
$desc     = isset($displayData['description']) ? $displayData['description'] : '';

// extract type from custom field parameters and use it as class suffix for individual styling
$type = $displayData['field']['type'];

if ($type == 'checkbox' && $displayData['field']['poplink'])
{
	// use terms-of-service identifier
	$type = 'tos';
}

?>

<div class="cf-control<?php echo $type ? ' ' . $type : ''; ?>">

	<div class="cf-label">

		<!-- START WRAPPER -->

		<?php
		if ($type == 'tos')
		{
			JHtml::fetch('vaphtml.assets.fancybox');

			// in case of a checkbox with popup URL, use a link instead of a label to
			// allow the customers to see the contents inside an iframe modal
			$url = JRoute::rewrite($displayData['field']['poplink']);
			?>
			<a href="javascript:void(0)" onclick="vapOpenPopup('<?php echo $this->escape($url); ?>');" id="<?php echo $id; ?>-label">
			<?php
		}
		else
		{
			// use a label for any other fields
			?>
			<label for="<?php echo $id; ?>" id="<?php echo $id; ?>-label">
			<?php
		}
		?>

		<!-- LABEL CONTENT -->

		<?php
		// display required symbol before the label (skip if TOS field)
		if ($required && $label && $type != 'tos')
		{
			?>
			<span class="vaprequired"><sup>*</sup></span>
			<?php
		}
		?>

		<span><?php echo $label; ?></span>

		<?php
		if ($desc)
		{
			?>
			<i class="fas fa-question-circle hasTooltip" title="<?php echo $this->escape($desc); ?>"></i>
			<?php
		}
		?>

		<?php
		// display required symbol after the label (only if TOS field)
		if ($required && $label && $type == 'tos')
		{
			?>
			<span class="vaprequired"><sup>*</sup></span>
			<?php
		}
		?>

		<!-- END WRAPPER -->

		<?php
		if ($type == 'tos')
		{
			// close link tag
			?>
			</a>
			<?php
		}
		else
		{
			// close label tag
			?>
			</label>
			<?php
		}
		?>

	</div>

	<div class="cf-value">
