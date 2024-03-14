<?php
/**
 * @package     VikAppointments - Libraries
 * @subpackage  lite
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Helper implementor used to apply the restrictions of the LITE version.
 *
 * @since 1.2.3
 */
class VikAppointmentsLiteHelper
{
	/**
	 * The platform application instance.
	 * 
	 * @var JApplication
	 */
	private $app;

	/**
	 * The platform database instance.
	 * 
	 * @var JDatabase
	 */
	private $db;

	/**
	 * Class constructor.
	 */
	public function __construct()
	{
		$this->app = JFactory::getApplication();
		$this->db  = JFactory::getDbo();
	}

	/**
	 * Helper method used to disable the capabilities according
	 * to the restrictions applied by the LITE version.
	 * 
	 * @param   array   $capabilities  Array of key/value pairs where keys represent a capability name and boolean values
	 *                                 represent whether the role has that capability.
	 * 
	 * @return  array   The resulting capabilities lookup.
	 */
	public function restrictCapabilities(array $capabilities)
	{
		switch ($this->app->input->get('view'))
		{
			case 'customf':
				// disable both CREATE and EDIT capabilities
				$capabilities['com_vikappointments_create'] = false;
				$capabilities['com_vikappointments_edit']   = false;
				break;

			case 'reservations':
				// disable only EDIT capability
				$capabilities['com_vikappointments_edit'] = false;
				break;
		}

		return $capabilities;
	}

	/**
	 * Helper function used to auto-redirect the customers to the creation page of a
	 * new reservation while trying to manually edit an existing one.
	 * 
	 * @return  void
	 */
	public function preventEditReservationAccess()
	{
		// edit disabled, reach add reservation instead
		if ($this->app->input->get('task') == 'reservation.edit')
		{
			$this->app->redirect('index.php?option=com_vikappointments&view=findreservation');
			$this->app->close();
		}
	}

	/**
	 * Helper method used to display an advertsing banner while trying
	 * to reach a page available only in the PRO version.
	 * 
	 * @return  void
	 */
	public function displayBanners()
	{
		$input = $this->app->input;

		// get current view
		$view = $input->get('view');

		// define list of pages not supported by the LITE version
		$lookup = array(
			'acl',
			'customers',
			'editconfigemp',
			'editconfigcron',
			'invoices',
			'locations',
			'options',
			'rates',
			'restrictions',
			'reviews',
		);

		// check whether the view is supported
		if (!$view || !in_array($view, $lookup))
		{
			return;
		}

		// display menu before unsetting the view
		AppointmentsHelper::printMenu();

		// use a missing view to display blank contents
		$input->set('view', 'liteview');

		// display LITE banner
		echo JLayoutHelper::render('html.license.lite', array('view' => $view));
	}

	/**
	 * Helper method used to pre-load the resources needed by the LITE version.
	 * 
	 * @return  void
	 */
	public function includeLiteAssets()
	{
		JFactory::getDocument()->addStyleSheet(
			VIKAPPOINTMENTS_CORE_MEDIA_URI . 'css/lite.css',
			['version' => VIKAPPOINTMENTS_SOFTWARE_VERSION],
			['id' => 'vap-lite-style']
		);
	}

	/**
	 * Helper method used to remove all wizard steps that refer to
	 * a feature that is not supported by the LITE version.
	 * 
	 * @param   boolean    $status  True on success, false otherwise.
	 * @param   VREWizard  $wizard  The wizard instance.
	 * 
	 * @return  void
	 */
	public function removeWizardSteps($status, $wizard)
	{
		// remove steps that refer to the PRO version
		$wizard->removeStep('options');
		$wizard->removeStep('locations');
		$wizard->removeStep('locwdays');
		$wizard->removeStep('payments');
		$wizard->removeStep('syspack');
		$wizard->removeStep('packages');
		$wizard->removeStep('syssubscr');
		$wizard->removeStep('subscriptions');

		return $status;
	}

	/**
	 * Helper method used to disable the possibility to switch group from the
	 * custom fields list view.
	 * 
	 * @param   JView  $view  The view instance.
	 * 
	 * @return  void
	 */
	public function disableCustomFieldsGroupFilter($view)
	{
		// hide group filter
		JFactory::getDocument()->addStyleDeclaration('#vap-group-sel { display: none; }');

		// always manually force the group to "customers"
		$this->app->input->set('group', 0);
	}

	/**
	 * Helper method used to display the scripts and the HTML needed to
	 * allow the management of the terms-of-service custom field.
	 * 
	 * @param   JView  $view  The view instance.
	 * 
	 * @return  void
	 */
	public function displayTosFieldManagementForm($view)
	{
		// iterate all custom fields
		foreach ($view->rows as $cf)
		{
			// check if we have a checkbox field
			if ($cf['type'] == 'checkbox')
			{
				// use scripts to manage ToS
				echo JLayoutHelper::render('html.managetos.script', array('field' => $cf));
			}
		}
	}

	/**
	 * Helper method used to intercept the custom request used to update
	 * the terms-of-service custom field.
	 * 
	 * @return  void
	 */
	public function listenTosFieldSavingTask()
	{
		$input = $this->app->input;

		// check if we should save the TOS field
		if ($input->get('task') == 'customf.savetosajax')
		{
			$user = JFactory::getUser();

			$args = array();
			$args['name']    = $input->get('name', '', 'string');
			$args['poplink'] = $input->get('poplink', '', 'string');
			$args['id']      = $input->get('id', 0, 'uint');

			// check user permissions
			if (!$user->authorise('core.edit', 'com_vikappointments')
				|| !$user->authorise('core.access.custfields', 'com_vikappointments')
				|| !$args['id'])
			{
				UIErrorFactory::raiseError(403, JText::translate('JERROR_ALERTNOAUTHOR'));
			}

			// get record model
			$field = JModelVAP::getInstance('customf');

			// try to save arguments
			if (!$field->save($args))
			{
				// get string error
				$error = $field->getError(null, true);
				$error = JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error);

				UIErrorFactory::raiseError(403, $error);
			}

			$this->app->setHeader('Content-Type', 'application/json');
			$this->app->sendHeaders();

			echo json_encode($field->getData());

			$this->app->close();
		}
	}

	/**
	 * Helper method used to detach the Save button from the toolbar, since
	 * the edit feature is not supported. Renames also the Save & Close button.
	 * 
	 * @return  void
	 */
	public function adjustToolbarFromReservationManagement()
	{
		$toolbar = JToolbar::getInstance();

		// load the list of registered buttons
		$buttons = $toolbar->getButtons();

		// iterate all buttons
		foreach ($buttons as $btn)
		{
			// access button properties
			$options = $btn->getDisplayData();

			if ($options['id'] === 'jbutton-reservation-save' || $options['id'] === 'jbutton-reservation-saveclose')
			{
				// delete button from toolbar
				$toolbar->removeButton($btn);
			}
		}

		// register at the beginning a new save button that automatically goes back to the list
		$toolbar->prependButton('Standard', 'apply', JText::translate('VAPSAVE'), 'reservation.saveclose', false);
	}

	/**
	 * When accessing the details of an appointment outside from the reservations list, the
	 * popup will display a button to edit the reservation. Since editing is no more allowed,
	 * we should totally remove that button in order to avoid letting it to seem buggy.
	 * 
	 * @return  void
	 */
	public function disableEditFromOrderinfoModal()
	{
		JFactory::getDocument()->addStyleDeclaration('.modal-footer button[data-role="reservation.edit"] { display: none; }');
	}

	/**
	 * The Checkin column within the dashboard page contains a link to access the details of
	 * the reservation. Even if the management page is not accessible, it doesn't make sense
	 * to redirect the customers to the page to create new appointments.
	 * 
	 * @return  void
	 */
	public function disableEditFromOrderinfoDashboardModal()
	{
		$document = JFactory::getDocument();

		$document->addStyleDeclaration(
<<<CSS
/* edit footer from reservation modal */
#jmodal-orderinfo .modal-footer {
	display: none;
}
#jmodal-orderinfo .modal-header + div.has-footer {
	height: calc(100% - 70px) !important;
}
CSS
		);

		// since the widgets of the Dashboard tries to manually access the edit button of the modal,
		// we need to replicate the same button somewhere else to prevent JavaScript errors
		$document->addScriptDeclaration(
<<<JS
(function($) {
	'use strict';

	$(function() {
		$('body').append('<a href="" style="display:none;" id="orderinfo-edit-btn">&nbsp;</a>');
	});
})(jQuery);
JS
		);
	}

	/**
	 * Since the multilingual is not supported by the LITE version, we need to remove the
	 * related setting from the global configuration of the program.
	 * 
	 * @param   JView  $view  The view instance.
	 * 
	 * @return  void
	 */
	public function removeMultilingualSettingFromConfiguration($view)
	{
		$document = JFactory::getDocument();

		// hide via CSS first to avoid weird behaviors due to loading delayes
		$document->addStyleDeclaration('.multilingual-setting { display: none !important; }');
		// then remove the whole block via JS to prevent issues with the search bar
		$document->addScriptDeclaration(
		<<<JS
(function($) {
	'use strict';

	$(function() {
		$('.multilingual-setting').remove();
	});
})(jQuery);
JS
		);
	}

	/**
	 * Since the conversion codes are not supported by the LITE version, we need to remove the
	 * related setting from the global configuration of the program.
	 * 
	 * @param   JView  $view  The view instance.
	 * 
	 * @return  void
	 */
	public function removeConversionsSettingFromConfiguration($view)
	{
		$document = JFactory::getDocument();

		// hide via CSS first to avoid weird behaviors due to loading delayes
		$document->addStyleDeclaration('.conversions-setting { display: none !important; }');
		// then remove the whole block via JS to prevent issues with the search bar
		$document->addScriptDeclaration(
		<<<JS
(function($) {
	'use strict';

	$(function() {
		$('.conversions-setting').remove();
	});
})(jQuery);
JS
		);
	}

	/**
	 * Since the e-mail custom texts are not supported by the LITE version, we need to remove the
	 * related setting from the global configuration of the program.
	 * 
	 * @param   JView  $view  The view instance.
	 * 
	 * @return  void
	 */
	public function removeMailTextSettingFromConfiguration($view)
	{
		$document = JFactory::getDocument();

		// hide via CSS first to avoid weird behaviors due to loading delayes
		$document->addStyleDeclaration('.mailtext-setting { display: none !important; }');
		// then remove the whole block via JS to prevent issues with the search bar
		$document->addScriptDeclaration(
		<<<JS
(function($) {
	'use strict';

	$(function() {
		$('.mailtext-setting').remove();
	});
})(jQuery);
JS
		);
	}

	/**
	 * Since the cart is not supported by the LITE version, we need to remove the
	 * related settings from the global configuration of the program.
	 * 
	 * @param   JView  $view  The view instance.
	 * 
	 * @return  void
	 */
	public function removeShopCartSettingsFromConfiguration($view)
	{
		$document = JFactory::getDocument();

		// hide via CSS first to avoid weird behaviors due to loading delayes
		$document->addStyleDeclaration('.shop-cart-setting, .vapcartchildtr { display: none !important; }');
		// then remove the whole block via JS to prevent issues with the search bar
		$document->addScriptDeclaration(
		<<<JS
(function($) {
	'use strict';

	$(function() {
		$('.shop-cart-setting, .vapcartchildtr').remove();
	});
})(jQuery);
JS
		);
	}

	/**
	 * Since the waiting list is not supported by the LITE version, we need to remove the
	 * related tab from the global configuration of the program.
	 * 
	 * @param   JView  $view  The view instance.
	 * 
	 * @return  void
	 */
	public function removeShopWaitingListTabFromConfiguration($view)
	{
		$document = JFactory::getDocument();

		// hide via CSS first to avoid weird behaviors due to loading delayes
		$document->addStyleDeclaration('#vaptabview4 .config-panel-subnav li[data-id="vapconfigglobtitle14"],
			#vaptabview4 .config-panel-tabview .config-panel-tabview-inner[data-id="vapconfigglobtitle14"] { display: none !important; }');

		// then remove the whole block via JS to prevent issues with the search bar
		$document->addScriptDeclaration(
		<<<JS
(function($) {
	'use strict';

	$(function() {
		$('#vaptabview4').find('.config-panel-subnav li[data-id="vapconfigglobtitle14"]').remove();
		$('#vaptabview4').find('.config-panel-tabview .config-panel-tabview-inner[data-id="vapconfigglobtitle14"]').remove();
	});
})(jQuery);
JS
		);
	}

	/**
	 * Since the recurrence is not supported by the LITE version, we need to remove the
	 * related tab from the global configuration of the program.
	 * 
	 * @param   JView  $view  The view instance.
	 * 
	 * @return  void
	 */
	public function removeShopRecurrenceTabFromConfiguration($view)
	{
		$document = JFactory::getDocument();

		// hide via CSS first to avoid weird behaviors due to loading delayes
		$document->addStyleDeclaration('#vaptabview4 .config-panel-subnav li[data-id="vapconfigglobtitle3"],
			#vaptabview4 .config-panel-tabview .config-panel-tabview-inner[data-id="vapconfigglobtitle3"] { display: none !important; }');
			
		// then remove the whole block via JS to prevent issues with the search bar
		$document->addScriptDeclaration(
		<<<JS
(function($) {
	'use strict';

	$(function() {
		$('#vaptabview4').find('.config-panel-subnav li[data-id="vapconfigglobtitle3"]').remove();
		$('#vaptabview4').find('.config-panel-tabview .config-panel-tabview-inner[data-id="vapconfigglobtitle3"]').remove();
	});
})(jQuery);
JS
		);
	}

	/**
	 * Since the reviews are not supported by the LITE version, we need to remove the
	 * related tab from the global configuration of the program.
	 * 
	 * @param   JView  $view  The view instance.
	 * 
	 * @return  void
	 */
	public function removeShopReviewsTabFromConfiguration($view)
	{
		$document = JFactory::getDocument();

		// hide via CSS first to avoid weird behaviors due to loading delayes
		$document->addStyleDeclaration('#vaptabview4 .config-panel-subnav li[data-id="vapconfigglobtitle12"],
			#vaptabview4 .config-panel-tabview .config-panel-tabview-inner[data-id="vapconfigglobtitle12"] { display: none !important; }');
			
		// then remove the whole block via JS to prevent issues with the search bar
		$document->addScriptDeclaration(
		<<<JS
(function($) {
	'use strict';

	$(function() {
		$('#vaptabview4').find('.config-panel-subnav li[data-id="vapconfigglobtitle12"]').remove();
		$('#vaptabview4').find('.config-panel-tabview .config-panel-tabview-inner[data-id="vapconfigglobtitle12"]').remove();
	});
})(jQuery);
JS
		);
	}

	/**
	 * Since the packages are not supported by the LITE version, we need to remove the
	 * related tab from the global configuration of the program.
	 * 
	 * @param   JView  $view  The view instance.
	 * 
	 * @return  void
	 */
	public function removeShopPackagesTabFromConfiguration($view)
	{
		$document = JFactory::getDocument();

		// hide via CSS first to avoid weird behaviors due to loading delayes
		$document->addStyleDeclaration('#vaptabview4 .config-panel-subnav li[data-id="vapconfigglobtitle16"],
			#vaptabview4 .config-panel-tabview .config-panel-tabview-inner[data-id="vapconfigglobtitle16"] { display: none !important; }');
			
		// then remove the whole block via JS to prevent issues with the search bar
		$document->addScriptDeclaration(
		<<<JS
(function($) {
	'use strict';

	$(function() {
		$('#vaptabview4').find('.config-panel-subnav li[data-id="vapconfigglobtitle16"]').remove();
		$('#vaptabview4').find('.config-panel-tabview .config-panel-tabview-inner[data-id="vapconfigglobtitle16"]').remove();
	});
})(jQuery);
JS
		);
	}

	/**
	 * Since the subscriptions are not supported by the LITE version, we need to remove the
	 * related tab from the global configuration of the program.
	 * 
	 * @param   JView  $view  The view instance.
	 * 
	 * @return  void
	 */
	public function removeShopSubscriptionsTabFromConfiguration($view)
	{
		$document = JFactory::getDocument();

		// hide via CSS first to avoid weird behaviors due to loading delayes
		$document->addStyleDeclaration('#vaptabview4 .config-panel-subnav li[data-id="vapmenusubscriptions"],
			#vaptabview4 .config-panel-tabview .config-panel-tabview-inner[data-id="vapmenusubscriptions"] { display: none !important; }');
			
		// then remove the whole block via JS to prevent issues with the search bar
		$document->addScriptDeclaration(
		<<<JS
(function($) {
	'use strict';

	$(function() {
		$('#vaptabview4').find('.config-panel-subnav li[data-id="vapmenusubscriptions"]').remove();
		$('#vaptabview4').find('.config-panel-tabview .config-panel-tabview-inner[data-id="vapmenusubscriptions"]').remove();
	});
})(jQuery);
JS
		);
	}
}
