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

JHtml::fetch('bootstrap.tooltip', '.hasTooltip');
JHtml::fetch('vaphtml.assets.select2');
JHtml::fetch('vaphtml.assets.fontawesome');

$vik = VAPApplication::getInstance();

?>

<form name="adminForm" action="index.php" method="post" id="adminForm">

	<?php echo $vik->openCard(); ?>

		<!-- SERVICES -->

		<div class="span12">
			<?php
			echo $vik->openEmptyFieldset();
			echo $this->loadTemplate('services');
			echo $vik->closeEmptyFieldset();
			?>
		</div>

	<?php echo $vik->closeCard(); ?>

	<?php echo JHtml::fetch('form.token'); ?>
	
	<input type="hidden" name="id" value="<?php echo $this->idEmployee; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
</form>

<?php
$footer  = '<button type="button" class="btn btn-success" data-role="save">' . JText::translate('JAPPLY') . '</button>';
$footer .= '<button type="button" class="btn btn-danger" data-role="delete" style="float:right;">' . JText::translate('VAPDELETE') . '</button>';

// render inspector to manage service employees
echo JHtml::fetch(
	'vaphtml.inspector.render',
	'service-employee-inspector',
	array(
		'title'       => JText::translate('VAPMANAGERESERVATION4'),
		'closeButton' => true,
		'keyboard'    => true,
		'footer'      => $footer,
		'width'       => 600,
	),
	$this->loadTemplate('services_modal')
);
?>
