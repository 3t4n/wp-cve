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

$reservation = $this->reservation;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewReservation". The event method receives the
 * view instance as argument.
 *
 * @since 1.6.6
 */
$this->forms = $this->onDisplayView();

?>

<form name="adminForm" action="index.php" method="post" id="adminForm">

	<?php echo $vik->bootStartTabSet('reservation', array('active' => $this->getActiveTab('reservation_details'), 'cookie' => $this->getCookieTab()->name)); ?>

		<!-- DETAILS -->
			
		<?php
		echo $vik->bootAddTab('reservation', 'reservation_details', JText::translate('VAPMANAGERESERVATIONTITLE1'));
		echo $this->loadTemplate('details');
		echo $vik->bootEndTab();
		?>

		<!-- ORDER STATUS -->
				
		<?php
		echo $vik->bootAddTab('reservation', 'reservation_orderstatus', JText::translate('VAPORDERSTATUSES'));
		echo $this->loadTemplate('orderstatus');
		echo $vik->bootEndTab();
		?>

		<!-- USER NOTES -->
				
		<?php
		echo $vik->bootAddTab('reservation', 'reservation_usernotes', JText::translate('VAPMANAGECUSTOMERTITLE4'));

		if ($reservation->id)
		{
			// user notes can be created only for existing appointments
			echo $this->loadTemplate('usernotes');	
		}
		else
		{
			// fallback to classic editor to write some notes without having to save the record first
			echo $vik->getEditor()->display('notes', '', '100%', 550, 40, 20);
		}
		
		echo $vik->bootEndTab();
		?>

		<!-- CUSTOM FIELDS -->

		<?php
		if ($this->customFields)
		{
			echo $vik->bootAddTab('reservation', 'reservation_fields', JText::translate('VAPMANAGERESERVATIONTITLE2'));
			echo $this->loadTemplate('fields');
			echo $vik->bootEndTab();
		}
		?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewReservation","type":"tab"} -->

		<?php
		/**
		 * Iterate remaining forms to be displayed within
		 * the nav bar as custom sections.
		 *
		 * @since 1.6.6
		 */
		foreach ($this->forms as $formName => $formHtml)
		{
			$title = JText::translate($formName);

			// fetch form key
			$key = strtolower(preg_replace("/[^a-zA-Z0-9_]/", '', $title));

			if (!preg_match("/^reservation_/", $key))
			{
				// keep same notation for fieldset IDs
				$key = 'reservation_' . $key;
			}

			echo $vik->bootAddTab('reservation', $key, $title);
			echo $formHtml;
			echo $vik->bootEndTab();
		}
		?>

	<?php echo $vik->bootEndTabSet(); ?>

	<?php
	if (!$this->multiOrder)
	{
		$footer = '<button type="button" class="btn btn-success" data-role="save">' . JText::translate('JAPPLY') . '</button>';

		// render inspector to manage appointment details
		echo JHtml::fetch(
			'vaphtml.inspector.render',
			'order-app-inspector',
			array(
				'title'       => JText::translate('VAPAPPOINTMENT'),
				'closeButton' => true,
				'keyboard'    => true,
				'footer'      => $footer,
				'width'       => 400,
			),
			$this->loadTemplate('details_appointments_modal')
		);
	}

	echo JHtml::fetch(
		'bootstrap.renderModal',
		'jmodal-custmail',
		array(
			'title'       => JText::translate('VAPMAINTITLEVIEWMAILTEXTCUST'),
			'closeButton' => true,
			'keyboard'    => false, 
			'bodyHeight'  => 80,
			'modalWidth'  => 90,
			'footer' 	  => '<button type="button" class="btn" id="custmail-cancel">' . JText::translate('JCANCEL') . '</button>'
				. '<button type="button" class="btn btn-success" id="custmail-save">' . JText::translate('JAPPLY') . '</button>'
				. '<div class="pull-left" style="display: flex;align-items: center;">'
				. '<input type="checkbox" name="exclude_default_mail_texts" value="1" id="exclude_custom_mail" />'
				. '<label for="exclude_custom_mail" style="margin: 0 0 0 4px;">' . JText::translate('VAPORDEREXCLUDECUSTMAIL') . '</label>'
				. '</div>',
		),
		$this->loadTemplate('details_order_custmail')
	);
	?>

	<?php echo JHtml::fetch('form.token'); ?>
	
	<input type="hidden" name="id" value="<?php echo $reservation->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
	<input type="hidden" name="from" value="<?php echo $this->from; ?>" />
</form>

<?php
if (!$this->multiOrder)
{
	$checkin = new JDate($reservation->checkin_ts);

	if ($reservation->timezone)
	{
		// adjust to the specified timezone
		$checkin->setTimezone(new DateTimeZone($reservation->timezone));
	}
	else
	{
		// adjust to the system timezone
		$checkin->setTimezone(new DateTimeZone(JFactory::getApplication()->get('offset', 'UTC')));
	}

	$footer = '<button type="button" class="btn btn-success" id="apply-rate-btn">' . JText::translate('VAPAPPLY') . '</button>';

	echo JHtml::fetch(
		'bootstrap.renderModal',
		'jmodal-ratestest',
		array(
			'title'       => JText::translate('VAPMANAGESPECIALRATES'),
			'closeButton' => true,
			'keyboard'    => false, 
			'bodyHeight'  => 60,
			'modalWidth'  => 60,
			'url'		  => '', // it will be filled dinamically
			'footer'      => $footer,
		)
	);

	$footer  = '<button type="button" class="btn btn-success" data-role="save">' . JText::translate('JAPPLY') . '</button>';
	$footer .= '<button type="button" class="btn btn-danger" data-role="delete" style="float:right;">' . JText::translate('VAPDELETE') . '</button>';

	// render inspector to manage appointment options
	echo JHtml::fetch(
		'vaphtml.inspector.render',
		'order-app-opt-inspector',
		array(
			'title'       => JText::translate('VAP_ADD_EXTRA'),
			'closeButton' => true,
			'keyboard'    => true,
			'footer'      => $footer,
			'width'       => 400,
		),
		$this->loadTemplate('details_appointments_option_modal')
	);
}

// customer management modal
echo JHtml::fetch(
	'bootstrap.renderModal',
	'jmodal-managecustomer',
	array(
		'title'       => '<span class="customer-title"></span>',
		'closeButton' => true,
		'keyboard'    => false, 
		'bodyHeight'  => 80,
		'url'		  => '', // it will be filled dinamically
		'footer'      => '<button type="button" class="btn btn-success" data-role="customer.save">' . JText::translate('JAPPLY') . '</button>',
	)
);

// user note management modal
echo JHtml::fetch(
	'bootstrap.renderModal',
	'jmodal-usernote',
	array(
		'title'       => JText::translate('VAPMANAGECUSTOMERTITLE4'),
		'closeButton' => true,
		'keyboard'    => false, 
		'bodyHeight'  => 80,
		'url'		  => '', // it will be filled dinamically
		'footer'      => '<button type="button" class="btn btn-success" data-role="usernote.save">' . JText::translate('JAPPLY') . '</button>',
	)
);
?>

<script>

	// validate

	var validator = new VikFormValidator('#adminForm');

	// register submit callback in a local variable
	const ManageReservationSubmitButtonCallback = function(task) {
		if (task.indexOf('save') !== -1) {
			if (validator.validate()) {
				Joomla.submitform(task, document.adminForm);	
			}
		} else {
			Joomla.submitform(task, document.adminForm);
		}
	};

	Joomla.submitbutton = ManageReservationSubmitButtonCallback;

	// bootstrap modal

	function vapOpenJModal(id, url, jqmodal) {
		<?php
		if (!$this->multiOrder)
		{
			?>
			if (id === 'ratestest') {
				<?php
				$query = array(
					'id_service' => $reservation->id_service,
					'checkin'    => $checkin->format('Y-m-d H:i:s', $local = true),
				);
				?>

				// build URL at runtime to have an higher accuracy
				url = 'index.php?option=com_vikappointments&view=ratestest&tmpl=component&layout=quick&<?php echo http_build_query($query); ?>';
				// append selected employee
				url += '&id_employee=' + jQuery('#app_employee').val();
				// append number of participants
				url += '&people=' + jQuery('#app_people').val();
				// append selected user
				url += '&uid=' + jQuery('#vap-users-select').val();
			}
			<?php
		}
		?>

		<?php echo $vik->bootOpenModalJS(); ?>
	}
	
</script>
