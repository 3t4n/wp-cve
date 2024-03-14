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
 * called "onDisplayViewConfigEmailMail". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('EmailMail');

?>

<div class="config-fieldset">

	<div class="config-fieldset-body">
		
		<!-- ADMIN MAIL - Text -->

		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG1'),
			'content' => JText::translate('VAPMANAGECONFIG1_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG1') . $help); ?>
			<input type="text" name="adminemail" value="<?php echo $this->escape($params['adminemail']); ?>" size="64" />
		<?php echo $vik->closeControl(); ?>
		
		<!-- SENDER MAIL - Text -->

		<?php echo $vik->openControl(JText::translate('VAPMANAGECONFIG24')); ?>
			<input type="email" name="senderemail" value="<?php echo $this->escape($params['senderemail']); ?>" size="64" />
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigEmailMail","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the E-mail > Mail > Mail fieldset.
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
<!-- {"rule":"customizer","event":"onDisplayViewConfigEmailMail","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the E-mail > Mail tab.
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
