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

$params = $this->params;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigGlobalColumns". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('GlobalColumns');

?>

<div class="config-fieldset">

	<div class="config-fieldset-head">
		<h3><?php echo JText::translate('VAPCONFIGGLOBTITLE4'); ?></h3>
	</div>

	<div class="config-fieldset-body">

		<?php 
		echo $vik->alert(JText::translate('VAPCONFIGGLOBTITLE4_HELP'), 'info');
		
		$all_list_fields = array(
			'1'  => 'id',
			'2'  => 'sid',
			'26' => 'checkin_ts',
			'42' => 'checkout',
			'3'  => 'employee',
			'4'  => 'service',
			'25' => 'people',
			'20' => 'info',
			'32' => 'nominative',
			'8'  => 'mail',
			'27' => 'phone',
			'9'  => 'total',
			'13' => 'payment',
			'21' => 'coupon',
			'35' => 'invoice',
			'12' => 'status',
		);

		$listable_fields = array();

		if (!empty($params['listablecols']))
		{
			$listable_fields = explode(',', $params['listablecols']);
		}
		
		foreach ($all_list_fields as $k => $f)
		{
			$selected = (int) in_array($f, $listable_fields); 

			$yes = $vik->initRadioElement('', '',  $selected, 'onClick="toggleListField(\'' . $f . '\', 1);"');
			$no  = $vik->initRadioElement('', '', !$selected, 'onClick="toggleListField(\'' . $f . '\', 0);"');

			echo $vik->openControl(JText::translate('VAPMANAGERESERVATION' . $k));
			echo $vik->radioYesNo($f . 'listcol', $yes, $no);
			?>
			<input type="hidden" name="listablecols[]" value="<?php echo $f . ':' . $selected; ?>" id="vaphidden<?php echo $f; ?>" />
			<?php
			echo $vik->closeControl();
		} 
		?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigGlobalColumns","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Global > Columns > Reservations List Columns fieldset.
		 *
		 * @since 1.7
		 */
		if (isset($forms['basic']))
		{
			echo $forms['basic'];

			// unset details form to avoid displaying it twice
			unset($forms['basic']);
		}
		?>

	</div>

</div>

<?php
// do not allow the selection of some rules, which are already 
// supported by the system as default columns (name, email, phone)
$fields = array_filter($this->customFields, function($field)
{
	return !in_array($field['rule'], array('nominative', 'email', 'phone'));
});

if ($fields)
{
	?>
	<div class="config-fieldset">

		<div class="config-fieldset-head">
			<h3><?php echo JText::translate('VAPMENUCUSTOMF'); ?></h3>
		</div>

		<div class="config-fieldset-body">

			<?php
			echo $vik->alert(JText::translate('VAPCONFIGGLOBTITLE4_CF_HELP'), 'info');

			$listable_fields = explode(',', $params['listablecf']);
			
			foreach ($fields as $field)
			{
				$selected = (int) in_array($field['name'], $listable_fields);

				$yes = $vik->initRadioElement('', '', $selected, 'onClick="toggleListFieldCF(\'' . addslashes($field['name']) . '\', ' . $field['id'] . ', 1);"');
				$no  = $vik->initRadioElement('', '', !$selected, 'onClick="toggleListFieldCF(\'' . addslashes($field['name']) . '\', ' . $field['id'] . ', 0);"');
				
				echo $vik->openControl($field['langname']);
				echo $vik->radioYesNo('listcf' . $field['id'], $yes, $no);
				?>
				<input type="hidden" name="listablecf[]" value="<?php echo $field['name'] . ':' . $selected; ?>" id="vapcfhidden<?php echo $field['id']; ?>" />
				<?php
				echo $vik->closeControl();
			}
			?>

		</div>

	</div>
	<?php
}
?>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewConfigGlobalColumns","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the Global > Columns tab.
 *
 * @since 1.7
 */
foreach ($forms as $formTitle => $formHtml)
{
	?>
	<div class="config-fieldset">
		
		<div class="config-fieldset-head">
			<h3><?php echo JText::translate($formTitle); ?></h3>
		</div>

		<div class="config-fieldset-body">
			<?php echo $formHtml; ?>
		</div>
		
	</div>
	<?php
}
?>

<script>

	function toggleListField(id, value) {
		jQuery('#vaphidden' + id).val(id + ':' + value);
	}

	function toggleListFieldCF(cf, id, value) {
		jQuery('#vapcfhidden' + id).val(cf + ':' + value);
	}

</script>
