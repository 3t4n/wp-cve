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

$rows = $this->rows;

$filters = $this->filters;

$vik = VAPApplication::getInstance();

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_vikappointments');

?>

<form action="index.php?option=com_vikappointments" method="post" name="adminForm" id="adminForm">

<?php 
if (count($rows) == 0)
{
	echo $vik->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
}
else
{
	?>
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>

				<th width="1%">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>

				<!-- ID -->

				<th class="<?php echo $vik->getAdminThClass('left hidden-phone nowrap'); ?>" width="1%" style="text-align: left;">
					<?php echo JText::translate('VAPMANAGEPACKGROUP1');?>
				</th>

				<!-- ALT -->
				
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="15%" style="text-align: left;">
					<?php echo JText::translate('VAPMANAGEMEDIA20');?>
				</th>

				<!-- TITLE -->
				
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="15%" style="text-align: left;">
					<?php echo JText::translate('VAPMANAGEMEDIA21');?>
				</th>

				<!-- CAPTION -->
				
				<th class="<?php echo $vik->getAdminThClass('left hidden-phone'); ?>" width="25%" style="text-align: left;">
					<?php echo JText::translate('VAPMANAGEMEDIA22');?>
				</th>

				<!-- LANGUAGE -->
				
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="5%" style="text-align: center;">
					<?php echo JText::translate('VAPLANGUAGE');?>
				</th>

			</tr>
		<?php echo $vik->closeTableHead(); ?>

		<?php
		for ($i = 0; $i < count($rows); $i++)
		{
			$row = $rows[$i];

			$alt     = $row['alt'] ? $row['alt'] : JText::translate('JOPTION_USE_DEFAULT');
			$title   = $row['title'] ? $row['title'] : JText::translate('JOPTION_USE_DEFAULT');
			$caption = $row['caption'] ? $row['caption'] : JText::translate('JOPTION_USE_DEFAULT');
			?>
			<tr class="row<?php echo $i % 2; ?>">

				<td>
					<input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>">
				</td>

				<td class="hidden-phone"><?php echo $row['id']; ?></td>

				<td>
					<?php
					if ($canEdit)
					{
						?>
						<a href="index.php?option=com_vikappointments&amp;task=langmedia.edit&amp;cid[]=<?php echo $row['id']; ?>">
							<?php echo $alt; ?>
						</a>
						<?php
					}
					else
					{
						echo $alt;
					}
					?>
				</td>

				<td>
					<?php echo $title; ?>
				</td>

				<td class="hidden-phone">
					<?php echo $caption; ?>
				</td>

				<td style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.site.flag', $row['tag']); ?>
				</td>

			</tr>
			<?php
		}		
		?>
	</table>
	<?php
}
?>

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="langmedia" />
	<input type="hidden" name="image" value="<?php echo $this->escape($filters['image']); ?>" />

	<?php echo JHtml::fetch('form.token'); ?>
	<?php echo $this->navbut; ?>
</form>
