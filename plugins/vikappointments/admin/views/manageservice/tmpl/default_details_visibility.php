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

$service = $this->service;

$vik = VAPApplication::getInstance();

?>

<!-- GROUP - Select -->

<?php
$options = array(
	JHtml::fetch('select.option', '', ''),
	JHtml::fetch('select.option', 0, JText::translate('VAPFILTERCREATENEW')),
);

$options = array_merge($options, JHtml::fetch('vaphtml.admin.groups', 1));

echo $vik->openControl(JText::translate('VAPMANAGESERVICE10')); ?>
	<select name="id_group" id="vap-group-sel">
		<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $service->id_group ? $service->id_group : ''); ?>
	</select>
<?php echo $vik->closeControl(); ?>

<!-- CREATE NEW GROUP - Text -->

<?php echo $vik->openControl('', 'create-group-control', array('style' => 'display:none;')); ?>
	<input type="text" name="group_name" placeholder="<?php echo $this->escape(JText::translate('VAPMANAGEGROUP2')); ?>" size="40" />
<?php echo $vik->closeControl(); ?>

<!-- ACCESS - Select -->

<?php
$help = $vik->createPopover(array(
	'title' 	=> JText::translate('JFIELD_ACCESS_LABEL'),
	'content' 	=> JText::translate('JFIELD_ACCESS_DESC'),
));

echo $vik->openControl(JText::translate('JFIELD_ACCESS_LABEL') . $help);
echo JHtml::fetch('access.level', 'level', $service->level, '', false, 'vap-level-sel');
echo $vik->closeControl();
?>

<script>

	jQuery(function($) {
		
		$('#vap-group-sel').select2({
			placeholder: '--',
			allowClear: true,
			width: '90%',
		});

		$('#vap-level-sel').select2({
			allowClear: false,
			width: '90%',
		});

		$('#vap-group-sel').on('change', function() {
			var group = $('input[name="group_name"]');

			if (parseInt($(this).val()) === 0) {
				$('.create-group-control').show();
				group.focus();
				validator.registerFields(group);
			} else {
				$('.create-group-control').hide();
				validator.unregisterFields(group);
				group.val('');
			}
		});

	});

</script>
