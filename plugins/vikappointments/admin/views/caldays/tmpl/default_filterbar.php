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

JHtml::fetch('formbehavior.chosen');

$vik 	= VAPApplication::getInstance();
$config = VAPFactory::getConfig();

if ($this->filters['layout'] == 'day')
{
	$_rules = array(
		'-1 week',
		'-1 day',
		'+1 day',
		'+1 week',
	);
}
else
{
	$_rules = array(
		'-1 month',
		'-1 week',
		'+1 week',
		'+1 month',
	);
}

$date_rules = array();

foreach ($_rules as $r)
{
	$dt = new JDate($this->calendar->start);
	$dt->modify($r);

	$date_rules[] = JHtml::fetch('date', $dt, $config->get('dateformat'), 'UTC');
}

?>

<div class="btn-toolbar" style="height: 42px;" id="vapcaldays-bar">

	<div class="btn-group pull-left input-prepend input-append">

		<button type="button" class="btn" onclick="updateCurrentDate('<?php echo $date_rules[0]; ?>');"><i class="fas fa-angle-double-left"></i></button>
		<button type="button" class="btn" onclick="updateCurrentDate('<?php echo $date_rules[1]; ?>');" style="border-top-right-radius: 0;border-bottom-right-radius: 0;"><i class="fas fa-angle-left"></i></button>

		<?php
		$attrs = array();
		$attrs['onChange'] = 'updateCurrentDate(\'\');';
		echo $vik->calendar(new JDate($this->calendar->start), 'dayfrom', null, null, $attrs);
		?>

		<button type="button" class="btn" onclick="updateCurrentDate('<?php echo $date_rules[2]; ?>');"><i class="fas fa-angle-right"></i></button>
		<button type="button" class="btn" onclick="updateCurrentDate('<?php echo $date_rules[3]; ?>');"><i class="fas fa-angle-double-right"></i></button>

	</div>

	<?php
	if ($this->filters['layout'] != 'day')
	{
		?>
		<div class="btn-group pull-left">
			<?php
			// get all employees
			$options = JHtml::fetch('vaphtml.admin.employees', $strict = false, $blank = JText::translate('VAPFINDRESALLEMPLOYEES'));
			?>
			<select name="employee" id="vap-employee-sel" onchange="employeeValueChanged();">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $this->filters['employee']); ?>
			</select>
		</div>
		<?php
	}
	?>

	<div class="btn-group pull-right">
		<a href="<?php echo $vik->addUrlCsrf('index.php?option=com_vikappointments&task=calendar.switch&layout=calendar', $xhtml = true); ?>" class="btn">
			<i class="fas fa-calendar-alt"></i>&nbsp;
			<?php echo JText::translate('VAPFREQUENCYTYPE2'); ?>
		</a>

		<button type="button" class="btn active">
			<i class="fas fa-calendar-week"></i>&nbsp;
			<?php echo JText::translate('VAPFREQUENCYTYPE1'); ?>
		</button>
	</div>

</div>

<input type="hidden" name="date" value="<?php echo JHtml::fetch('date', $this->calendar->start, $config->get('dateformat'), 'UTC'); ?>" />

<script>

	jQuery(function() {
		VikRenderer.chosen('.btn-toolbar');
	});

	// handle calendar inputs

	function updateCurrentDate(rule) {
		if (!rule.length) {
			rule = jQuery('#dayfrom').val();
		}

		document.adminForm.date.value = rule;
		document.adminForm.submit();
	}

	function employeeValueChanged() {
		jQuery('#adminForm').append('<input type="hidden" name="employee_changed" value="1" />');
		document.adminForm.submit();
	}

</script>
