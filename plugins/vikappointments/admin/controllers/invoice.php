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

VAPLoader::import('libraries.mvc.controllers.admin');
VAPLoader::import('libraries.invoice.factory');

/**
 * VikAppointments invoice controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerInvoice extends VAPControllerAdmin
{
	/**
	 * Task used to access the creation page of a new record.
	 *
	 * @return 	boolean
	 */
	public function add()
	{
		$app = JFactory::getApplication();

		$data  = array();
		$group = $app->input->getString('group');
		$month = $app->input->getUint('month');
		$year  = $app->input->getUint('year');

		if (!is_null($group))
		{
			$data['group'] = $group;
		}

		if ($month)
		{
			$data['month'] = $month;
		}

		if ($year)
		{
			$data['year'] = $year;
		}

		// unset user state for being recovered again
		$app->setUserState('vap.invoice.data', $data);

		// check user permissions
		if (!JFactory::getUser()->authorise('core.create', 'com_vikappointments'))
		{
			// back to main list, not authorised to create records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=manageinvoice');

		return true;
	}

	/**
	 * Task used to access the management page of an existing record.
	 *
	 * @return 	boolean
	 */
	public function edit()
	{
		$app = JFactory::getApplication();

		// unset user state for being recovered again
		$app->setUserState('vap.invoice.data', array());

		// check user permissions
		if (!JFactory::getUser()->authorise('core.edit', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->getUint('cid', array(0));

		$this->setRedirect('index.php?option=com_vikappointments&view=manageinvoice&cid[]=' . $cid[0]);

		return true;
	}

	/**
	 * Task used to save the record data set in the request.
	 * After saving, the user is redirected to the creation
	 * page of a new record.
	 *
	 * @return 	void
	 */
	public function savenew()
	{
		if ($this->save())
		{
			$input = JFactory::getApplication()->input;

			$url = 'index.php?option=com_vikappointments&task=invoice.add';

			// recover data from request
			$group = $input->getString('group');
			$month = $input->getUint('month');
			$year  = $input->getUint('year');

			if (!is_null($group))
			{
				$url .= '&group=' . $group;
			}

			if ($month)
			{
				$url .= '&month=' . $month;
			}

			if ($year)
			{
				$url .= '&year=' . $year;
			}

			$this->setRedirect($url);
		}
	}

	/**
	 * Task used to save the record data set in the request.
	 * After saving, the user is redirected to the management
	 * page of the record that has been saved.
	 *
	 * @param 	array 	 $data  An array of invoice parameters to be used for the generation.
	 * 						   If not specified, the parameters in the request will be used.
	 *
	 * @return 	boolean
	 */
	public function save(array $data = array())
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$user  = JFactory::getUser();

		/**
		 * Added token validation.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// back to main list, missing CSRF-proof token
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			$this->cancel();

			return false;
		}
		
		$args = array();

		if ($data)
		{
			$args = $data;
		}
		else
		{
			$args['id']        = $input->get('id', 0, 'uint');
			$args['group']     = $input->get('group', 0, 'string');
			$args['overwrite'] = $input->get('overwrite', 0, 'uint');
			$args['notify']    = $input->get('notifycust', 0, 'uint');
			$args['month']     = $input->get('month', 1, 'uint');
			$args['year']      = $input->get('year', 0, 'uint');
			$args['cid']       = $input->get('cid', array(), 'uint');

			$args['params'] = array();
			$args['params']['number']    = $input->get('number', array(), 'string');
			$args['params']['suffix']    = $input->get('suffix', array(), 'string');
			$args['params']['datetype']  = $input->get('datetype', 0, 'uint');
			$args['params']['date']      = $input->get('date', null, 'string');
			$args['params']['legalinfo'] = $input->get('legalinfo', '', 'string');

			if ($args['params']['date'])
			{
				$args['params']['date'] = VAPDateHelper::date2sql($args['params']['date']);
			}

			// settings
			$args['constraints']['pageOrientation'] = $input->get('pageorientation', '', 'string');
			$args['constraints']['pageFormat']      = $input->get('pageformat', '', 'string');
			$args['constraints']['unit']            = $input->get('unit', '', 'string');
			$args['constraints']['imageScaleRatio'] = abs($input->get('scale', 100, 'float')) / 100;

			// layout
			$args['constraints']['font']        = $input->get('font', 'courier', 'string');
			$args['constraints']['fontSizes']   = $input->get('fontsizes', array(), 'array');
			$args['constraints']['headerTitle'] = '';
			$args['constraints']['showFooter']  = $input->get('showfooter', false, 'bool');

			if ($input->getBool('showheader'))
			{
				$args['constraints']['headerTitle'] = $input->get('headertitle', '', 'string');
			}

			// margins
			$args['constraints']['margins'] = $input->get('margins', array(), 'array');
		}

		$rule = 'core.' . ($args['id'] > 0 ? 'edit' : 'create');

		// check user permissions
		if (!$user->authorise($rule, 'com_vikappointments'))
		{
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// get invoice model
		$model = $this->getModel();

		// update existing invoice
		if ($args['id'])
		{
			// try to save arguments
			if (!$model->save($args))
			{
				// get string error
				$error = $model->getError(null, true);

				// display error message
				$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

				$url = 'index.php?option=com_vikappointments&view=manageinvoice&cid[]=' . $args['id'];

				// redirect to edit page
				$this->setRedirect($url);
					
				return false;
			}

			// display generic successful message
			$app->enqueueMessage(JText::plural('VAPINVGENERATEDMSG', 1));

			if ($model->isNotified())
			{
				// invoice notified, display message
				$app->enqueueMessage(JText::plural('VAPINVMAILSENT', 1));
			}
		}
		// invoices mass creation
		else
		{
			// mass-save the matching records
			$result = $model->saveMass($args);

			if ($result['generated'])
			{
				// display number of generated invoices
				$app->enqueueMessage(JText::plural('VAPINVGENERATEDMSG', $result['generated']));

				if ($result['notified'])
				{
					// display number of notified customers
					$app->enqueueMessage(JText::plural('VAPINVMAILSENT', $result['notified']));
				}
			}
			else
			{
				// no generated invoices
				$app->enqueueMessage(JText::translate('VAPNOINVOICESGENERATED'), 'warning');

				// save invoice data to keep changed settings
				$model->createGenerator($args)->save();
			}
		}

		// always redirect to invoices list when generating the invoices
		$this->cancel();

		return true;
	}

	/**
	 * Generates an invoice for the specified reservations.
	 *
	 * @return 	boolean
	 */
	public function generate()
	{
		$input = JFactory::getApplication()->input;

		// create array with required attributes
		$data = array();
		$data['id']     = 0;
		$data['cid']    = $input->get('cid', array(), 'uint');
		$data['group']  = $input->get('group', 'appointments', 'string');
		$data['notify'] = $input->get('notifycust', 0, 'uint');

		// generate invoice
		return $this->save($data);
	}

	/**
	 * Deletes a list of records set in the request.
	 *
	 * @return 	boolean
	 */
	public function delete()
	{
		$app = JFactory::getApplication();
		$cid = $app->input->get('cid', array(), 'uint');

		/**
		 * Added token validation.
		 * Both GET and POST are supported.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken() && !JSession::checkToken('get'))
		{
			// back to main list, missing CSRF-proof token
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			$this->cancel();

			return false;
		}

		// check user permissions
		if (!JFactory::getUser()->authorise('core.delete', 'com_vikappointments'))
		{
			// back to main list, not authorised to delete records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// delete selected records
		$this->getModel()->delete($cid);

		// back to main list
		$this->cancel();

		return true;
	}

	/**
	 * Redirects the users to the main records list.
	 *
	 * @return 	void
	 */
	public function cancel()
	{
		$input = JFactory::getApplication()->input;

		$group = $input->get('group', null, 'string');
		$year  = $input->get('year', 0, 'uint');
		$month = $input->get('month', 0, 'uint');

		$url = 'index.php?option=com_vikappointments&view=invoices';

		if (!is_null($group))
		{
			$url .= '&group=' . $group;
		}

		if ($year)
		{
			$url .= '&year=' . $year;
		}

		if ($month)
		{
			$url .= '&month=' . $month;
		}

		$this->setRedirect($url);
	}

	/**
	 * Downloads one or more selected invoices.
	 * In case of single selection, the invoice will be
	 * directly downloaded in PDF format. Otherwise a
	 * ZIP archive will be given.
	 *
	 * @return 	void
	 */
	public function download()
	{
		$app = JFactory::getApplication();
		$ids = $app->input->get('cid', array(), 'uint');

		/**
		 * Added token validation.
		 * Both GET and POST are supported.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken() && !JSession::checkToken('get'))
		{
			// back to main list, missing CSRF-proof token
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			$this->cancel();

			return false;
		}

		// get invoice model
		$model = $this->getModel();

		// get path to download
		$path = $model->download($ids);

		if (!$path)
		{
			// retrieve fetched error message
			$error = $model->getError();

			if ($error)
			{
				// raise error
				$app->enqueueMessage($error, 'error');
			}
			else
			{
				// no error fetched, probably the list of IDs was empty
				$app->enqueueMessage(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'), 'warning');
			}

			// back to main list
			$this->cancel();

			// do not go ahead
			return true;
		}

		$unlink = false;

		$app->setHeader('Content-Disposition', 'attachment; filename=' . basename($path));
		$app->setHeader('Content-Length', filesize($path));

		// check if we have a PDF file or a ZIP archive
		if (preg_match("/\.pdf$/", $path))
		{
			// download PDF file
			$app->setHeader('Content-Type', 'application/pdf');
		}
		else
		{
			$app->setHeader('Content-Type', 'application/zip');
			$unlink = true;
		}

		$app->sendHeaders();

		// use fopen to properly download large files
		$handle = fopen($path, 'rb');

		// read 1MB per cycle
		$chunk_size = 1024 * 1024;

		while (!feof($handle))
		{
			echo fread($handle, $chunk_size);
			ob_flush();
			flush();
		}

		fclose($handle);

		if ($unlink)
		{
			// delete package once its contents have been buffered
			unlink($path);
		}

		// break process to complete download
		$app->close();
	}

	/**
	 * Loads via AJAX the remaining invoices.
	 *
	 * @return 	void
	 */
	public function ajaxload()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$dbo   = JFactory::getDbo();

		/**
		 * Added token validation.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// missing CSRF-proof token
			UIErrorFactory::raiseError(403, JText::translate('JINVALID_TOKEN'));
		}
	
		$lim0  = $input->getUint('start_limit');
		$lim   = $input->getUint('limit');
		
		$filters = array();
		$filters['year']      = $app->getUserStateFromRequest('vap.invoices.year', 'year', 0, 'uint');
		$filters['month']     = $app->getUserStateFromRequest('vap.invoices.month', 'month', 1, 'uint');	
		$filters['group'] 	  = $input->getString('group', 'appointments');
		$filters['keysearch'] = $input->getString('keysearch');

		// get invoices
		$invoices = array();
		$not_all  = false;
		$max_lim  = 0;

		// create start date, adjusted to local timezone
		$start = new JDate("{$filters['year']}-{$filters['month']}-1 00:00:00", JFactory::getUser()->getTimezone());

		// create end date, cover the whole month
		$end = clone $start;
		$end->modify('+1 month');

		$q = $dbo->getQuery(true)
			->select('SQL_CALC_FOUND_ROWS *')
			->from($dbo->qn('#__vikappointments_invoice'))
			->where($dbo->qn('inv_date') . ' >= ' . $dbo->q($start))
			->where($dbo->qn('inv_date') . ' < ' . $dbo->q($end))
			->where($dbo->qn('group') . ' = ' . $dbo->q($filters['group']))
			->order(array(
				$dbo->qn('inv_date') . ' ASC',
				$dbo->qn('id_order') . ' ASC',
			));

		if ($filters['keysearch'])
		{
			$q->andWhere(array(
				$dbo->qn('file') . ' LIKE ' . $dbo->q("%{$filters['keysearch']}%"),
				$dbo->qn('inv_number') . ' = ' . $dbo->q($filters['keysearch']),
			), 'OR');
		}

		// create hook for query manipulation
		$event = 'onBeforeListQueryInvoices';

		// Create a dummy object to simulate the behavior of a view.
		// Not the best solution but does its job, at least until
		// List models will be implemented...
		$view = new stdClass;
		$view->filters     = $filters;
		$view->ordering    = 'inv_date';
		$view->orderingDir = 'asc';

		/**
		 * Replicate same hook used by the view in order to keep the
		 * custom filters also while loading the records via AJAX.
		 *
		 * @since 1.7
		 */
		VAPFactory::getEventDispatcher()->trigger($event, array(&$q, $view));

		$dbo->setQuery($q, $lim0, $lim);
		$invoices = $dbo->loadAssocList();

		if ($invoices)
		{
			$not_all = true;

			$dbo->setQuery('SELECT FOUND_ROWS();');
			if (($max_lim = $dbo->loadResult()) <= $lim0 + count($invoices))
			{
				$not_all = false;
			}
		}
		
		$invoices_html = array();
		
		$cont = $lim0;

		$invoiceLayout = new JLayoutFile('blocks.invoice');

		// get invoice handler
		$handler = VAPInvoiceFactory::getInvoice(null, $filters['group']);
		// register invoice URI
		$folder = $handler->getInvoiceFolderURI();

		foreach ($invoices as $inv)
		{
			$cont++;
			
			$data = array(
				'id'     => $inv['id'],
				'number' => $inv['inv_number'],
				'file'   => $folder . $inv['file'],
			);
			
			$invoices_html[] = $invoiceLayout->render($data);
		}
		
		if (!$invoices_html)
		{
			$invoices_html[] = VAPApplication::getInstance()->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
		}

		$result = array(
			'count'    => $cont,
			'invoices' => $invoices_html,
			'more'     => $not_all,
			'max'      => $max_lim,
		);
		
		$this->sendJSON($result);
	}
}
