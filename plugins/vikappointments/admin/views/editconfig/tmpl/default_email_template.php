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

$templates = JHtml::fetch('vaphtml.admin.mailtemplates', '');

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigEmailTemplate". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('EmailTemplate');
?>

<div class="config-fieldset">

	<div class="config-fieldset-body">
	
		<!-- CUSTOMER EMAIL TEMPLATE -->

		<?php echo $vik->openControl(JText::translate("VAPMANAGECONFIG62")); ?>
			<div class="inline-fields">
				<select name="mailtmpl" class="medium-large" id="vap-mailtmpl-sel">
					<?php echo JHtml::fetch('select.options', $templates, 'value', 'text', $params['mailtmpl']); ?>
				</select>

				<div class="btn-group flex-auto">
					<button type="button" class="btn" onclick="vapOpenMailTemplateModal('mailtmpl', null, true); return false;">
						<i class="fas fa-pen"></i>
					</button>

					<button type="button" class="btn" onclick="goToMailPreview('mailtmpl', 'customer');">
						<i class="fas fa-eye"></i>
					</button>
				</div>
			</div>
		<?php echo $vik->closeControl(); ?>

		<!-- ADMINISTRATOR EMAIL TEMPLATE -->

		<?php echo $vik->openControl(JText::translate("VAPMANAGECONFIG91")); ?>
			<div class="inline-fields">
				<select name="adminmailtmpl" class="medium-large" id="vap-adminemailtmpl-sel">
					<?php echo JHtml::fetch('select.options', $templates, 'value', 'text', $params['adminmailtmpl']); ?>
				</select>

				<div class="btn-group flex-auto">
					<button type="button" class="btn" onclick="vapOpenMailTemplateModal('adminmailtmpl', null, true); return false;">
						<i class="fas fa-pen"></i>
					</button>

					<button type="button" class="btn" onclick="goToMailPreview('adminmailtmpl', 'admin');">
						<i class="fas fa-eye"></i>
					</button>
				</div>
			</div>
		<?php echo $vik->closeControl(); ?>

		<!-- EMPLOYEE EMAIL TEMPLATE -->

		<?php echo $vik->openControl(JText::translate("VAPMANAGECONFIG92")); ?>
			<div class="inline-fields">
				<select name="empmailtmpl" class="medium-large" id="vap-empemailtmpl-sel">
					<?php echo JHtml::fetch('select.options', $templates, 'value', 'text', $params['empmailtmpl']); ?>
				</select>

				<div class="btn-group flex-auto">
					<button type="button" class="btn" onclick="vapOpenMailTemplateModal('empmailtmpl', null, true); return false;">
						<i class="fas fa-pen"></i>
					</button>

					<button type="button" class="btn" onclick="goToMailPreview('empmailtmpl', 'employee');">
						<i class="fas fa-eye"></i>
					</button>
				</div>
			</div>
		<?php echo $vik->closeControl(); ?>

		<!-- CANCELLATION EMAIL TEMPLATE -->

		<?php echo $vik->openControl(JText::translate("VAPMANAGECONFIG93")); ?>
			<div class="inline-fields">
				<select name="cancmailtmpl" class="medium-large" id="vap-cancemailtmpl-sel">
					<?php echo JHtml::fetch('select.options', $templates, 'value', 'text', $params['cancmailtmpl']); ?>
				</select>

				<div class="btn-group flex-auto">
					<button type="button" class="btn" onclick="vapOpenMailTemplateModal('cancmailtmpl', null, true); return false;">
						<i class="fas fa-pen"></i>
					</button>

					<button type="button" class="btn" onclick="goToMailPreview('cancmailtmpl', 'cancellation');">
						<i class="fas fa-eye"></i>
					</button>
				</div>
			</div>
		<?php echo $vik->closeControl(); ?>

		<!-- MANAGE CUSTOM TEXTS -->

		<?php echo $vik->openControl('', 'mailtext-setting'); ?>
			<a href="index.php?option=com_vikappointments&view=mailtextcust" class="btn btn-success">
				<?php echo JText::translate('VAPMANAGECONFIG69'); ?>
			</a>
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigEmailTemplate","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the E-mail > Templates > Templates fieldset.
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

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewConfigEmailTemplate","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the E-mail > Templates tab.
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
