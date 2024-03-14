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
JHtml::fetch('vaphtml.assets.fontawesome');
JHtml::fetch('vaphtml.assets.toast', 'bottom-right');

$customer = $this->customer;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewCustomerinfo". The event method receives
 * the view instance as argument.
 *
 * @since 1.7
 */
$this->addons = $this->onDisplayView();

?>

<div class="customer-info-modal" style="padding:10px;">

	<form action="index.php?option=com_vikappointments" method="post" name="adminForm" id="adminForm">

		<?php echo $vik->bootStartTabSet('customerinfo', array('active' => $this->getActiveTab('customerinfo_billing', $customer->id), 'cookie' => $this->getCookieTab($customer->id)->name)); ?>

			<!-- BILLING -->
				
			<?php
			echo $vik->bootAddTab('customerinfo', 'customerinfo_billing', JText::translate('VAPMANAGECUSTOMERTITLE2'));
			echo $this->loadTemplate('billing');
			echo $vik->bootEndTab();
			?>

			<!-- APPOINTMENTS -->

			<?php
			// add badge counter to tab
			$options = array(
				'badge' => array(
					'count' => $this->appCount,
					'class' => 'badge-info',
				),
			);

			echo $vik->bootAddTab('customerinfo', 'customerinfo_appointments', JText::translate('VAPMANAGECUSTOMERTITLE5'), $options);

			if ($this->appointments)
			{
				echo $this->loadTemplate('appointments');
			}
			else
			{
				echo $vik->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
			}

			echo $vik->bootEndTab();
			?>

			<!-- PACKAGES -->

			<?php
			if (VikAppointments::isPackagesEnabled())
			{
				// add badge counter to tab
				$options = array(
					'badge' => array(
						'count' => $this->packCount,
						'class' => 'badge-success',
					),
				);

				echo $vik->bootAddTab('customerinfo', 'customerinfo_packages', JText::translate('VAPMENUPACKAGES'), $options);

				if ($this->packages)
				{
					echo $this->loadTemplate('packages');
				}
				else
				{
					echo $vik->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
				}

				echo $vik->bootEndTab();
			}
			?>

			<!-- NOTES -->
				
			<?php
			// add badge counter to tab
			$options = array(
				'badge' => array(
					'count' => $this->notesCount,
					'class' => 'badge-warning',
				),
			);

			echo $vik->bootAddTab('customerinfo', 'customerinfo_notes', JText::translate('VAPMANAGECUSTOMERTITLE4'), $options);

			if ($this->notes)
			{
				echo $this->loadTemplate('notes');
			}
			else
			{
				echo $vik->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
			}

			echo $vik->bootEndTab();
			?>

			<!-- Define role to detect the supported hook -->
			<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","type":"tab"} -->

			<?php
			/**
			 * Iterate remaining forms to be displayed within
			 * the nav bar as custom sections.
			 *
			 * @since 1.7
			 */
			foreach ($this->addons as $formName => $formHtml)
			{
				$title = JText::translate($formName);

				// fetch form key
				$key = strtolower(preg_replace("/[^a-zA-Z0-9_]/", '', $title));

				if (!preg_match("/^customerinfo_/", $key))
				{
					// keep same notation for fieldset IDs
					$key = 'customerinfo_' . $key;
				}

				echo $vik->bootAddTab('customerinfo', $key, $title);
				echo $formHtml;
				echo $vik->bootEndTab();
			}
			?>

		<?php echo $vik->bootEndTabSet(); ?>

		<input type="hidden" name="tmpl" value="component" />
		<input type="hidden" name="view" value="customerinfo" />
		<input type="hidden" name="cid[]" value="<?php echo $customer->id; ?>" />

	</form>

</div>
