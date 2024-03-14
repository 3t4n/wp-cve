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

VAPLoader::import('libraries.mvc.model');

/**
 * VikAppointments configuration model.
 *
 * @since 1.7
 */
class VikAppointmentsModelConfiguration extends JModelVAP
{
	/**
	 * Hook identifier for triggers.
	 *
	 * @var string
	 */
	protected $hook = 'Config';

	/**
	 * Saves the whole configuration.
	 *
	 * @param 	array 	 $args  An associative array.
	 *
	 * @return 	boolean  True in case something has changed, false otherwise.
	 */
	public function saveAll(array $args = array())
	{
		// sanitize configuration
		$this->validate($args);

		try
		{
			/**
			 * Trigger event to allow the plugins to bind the object that
			 * is going to be saved.
			 *
			 * @param 	mixed 	 &$config  The configuration array.
			 * @param 	JModel   $model    The model instance. (@since 1.7)
			 *
			 * @return 	boolean  False to abort saving.
			 *
			 * @throws 	Exception  It is possible to throw an exception to abort
			 *                     the saving process and return a readable message.
			 *
			 * @since 	1.6.6
			 */
			if (VAPFactory::getEventDispatcher()->false('onBeforeSave' . $this->hook, array(&$args, $this)))
			{
				// abort in case a plugin returned false
				return false;
			}
		}
		catch (Exception $e)
		{
			// register the error thrown by the plugin and abort 
			$this->setError($e);

			return false;
		}

		$dbo = JFactory::getDbo();

		$changed = false;

		foreach ($args as $param => $setting)
		{
			$q = $dbo->getQuery(true)
				->update($dbo->qn('#__vikappointments_config'))
				->set($dbo->qn('setting') . ' = ' . $dbo->q($setting))
				->where($dbo->qn('param') . ' = ' . $dbo->q($param));

			$dbo->setQuery($q);
			$dbo->execute();

			$changed = $changed || $dbo->getAffectedRows();
		}

		/**
		 * Trigger event to allow the plugins to make something after saving
		 * a record in the database.
		 *
		 * @param 	array 	 $args     The configuration array.
		 * @param 	boolean  $changed  True in case something has changed. (@since 1.7)
		 * @param 	JModel   $model    The model instance. (@since 1.7)
		 *
		 * @return 	void
		 *
		 * @since 	1.6.6
		 */
		VAPFactory::getEventDispatcher()->trigger('onAfterSave' . $this->hook, array($args, $changed, $this));

		return $changed;
	}

	/**
	 * Validates and prepares the settings to be stored.
	 *
	 * @param 	array 	&$args  The configuration associative array.
	 *
	 * @return 	void
	 */
	protected function validate(&$args)
	{
		if (isset($args['senderemail']) && $args['senderemail'] == '')
		{
			// use the e-mail of the current user
			$args['senderemail'] = JFactory::getUser()->email;
		}

		if (isset($args['mailattach']) && is_array($args['mailattach']))
		{
			// stringify mail attachments
			$args['mailattach'] = json_encode($args['mailattach']);
		}

		if (isset($args['icsattach']) && is_array($args['icsattach']))
		{
			// stringify ICS attachment rules
			$args['icsattach'] = implode(';', $args['icsattach']);
		}

		if (isset($args['csvattach']) && is_array($args['csvattach']))
		{
			// stringify CSV attachment rules
			$args['csvattach'] = implode(';', $args['csvattach']);
		}

		if (isset($args['mailcustwhen']) && is_array($args['mailcustwhen']))
		{
			// stringify list of accepted status codes
			$args['mailcustwhen'] = json_encode($args['mailcustwhen']);
		}

		if (isset($args['mailempwhen']) && is_array($args['mailempwhen']))
		{
			// stringify list of accepted status codes
			$args['mailempwhen'] = json_encode($args['mailempwhen']);
		}

		if (isset($args['mailadminwhen']) && is_array($args['mailadminwhen']))
		{
			// stringify list of accepted status codes
			$args['mailadminwhen'] = json_encode($args['mailadminwhen']);
		}

		// validate customer e-mail template
		if (isset($args['mailtmpl']))
		{
			if (empty($args['mailtmpl']))
			{
				$args['mailtmpl'] = 'email_tmpl.php';
			}
			else
			{
				$args['mailtmpl'] = basename($args['mailtmpl']);
			}
		}

		// validate admin e-mail template
		if (isset($args['adminmailtmpl']))
		{
			if (empty($args['adminmailtmpl']))
			{
				$args['adminmailtmpl'] = 'admin_email_tmpl.php';
			}
			else
			{
				$args['adminmailtmpl'] = basename($args['adminmailtmpl']);
			}
		}

		// validate employee e-mail template
		if (isset($args['empmailtmpl']))
		{
			if (empty($args['empmailtmpl']))
			{
				$args['empmailtmpl'] = 'employee_email_tmpl.php';
			}
			else
			{
				$args['empmailtmpl'] = basename($args['empmailtmpl']);
			}
		}

		// validate cancellation e-mail template
		if (isset($args['cancmailtmpl']))
		{
			if (empty($args['cancmailtmpl']))
			{
				$args['cancmailtmpl'] = 'cancellation_email_tmpl.php';
			}
			else
			{
				$args['cancmailtmpl'] = basename($args['cancmailtmpl']);
			}
		}

		// validate package e-mail template
		if (isset($args['packmailtmpl']))
		{
			if (empty($args['packmailtmpl']))
			{
				$args['packmailtmpl'] = 'packages_email_tmpl.php';
			}
			else
			{
				$args['packmailtmpl'] = basename($args['packmailtmpl']);
			}
		}

		// validate waiting list e-mail template
		if (isset($args['waitlistmailtmpl']))
		{
			if (empty($args['waitlistmailtmpl']))
			{
				$args['waitlistmailtmpl'] = 'waitlist_email_tmpl.php';
			}
			else
			{
				$args['waitlistmailtmpl'] = basename($args['waitlistmailtmpl']);
			}
		}

		if (isset($args['selfconfirm']) && isset($args['defstatus']))
		{
			// in case the default status is auto-approved, turn off the self confirmation
			if (JHtml::fetch('vaphtml.status.isapproved', 'appointments', $args['defstatus']))
			{
				$args['selfconfirm'] = 0;
			}
		}

		if (isset($args['keepapplock']))
		{
			// give at least 5 minutes to complete the payment
			$args['keepapplock'] = max(array(5, $args['keepapplock']));
		}

		if (isset($args['depositvalue']))
		{
			// cannot have negative deposit
			$args['depositvalue'] = abs((float) $args['depositvalue']);

			if (isset($args['deposittype']) == 1)
			{
				// cannot have a deposit higher than 99%
				$args['depositvalue'] = min(array(99, $args['depositvalue']));
			}
		}

		if (isset($args['shoplink']))
		{
			if ($args['shoplink'] != -2)
			{
				// unset custom shop link
				$args['shoplinkcustom'] = '';
			}
			else if (empty($args['shoplinkcustom']))
			{
				// use default one
				$args['shoplinkcustom'] = 'index.php';
			}
		}

		if (isset($args['repeatbyrecur']) && is_array($args['repeatbyrecur']))
		{
			// stringify repeat-by recurrence rules
			$args['repeatbyrecur'] = implode(';', $args['repeatbyrecur']);

			if ($args['repeatbyrecur'] == '0;0;0;0;0')
			{
				$args['repeatbyrecur'] = '0;1;1;0;0';
			}
		}

		if (isset($args['fornextrecur']) && is_array($args['fornextrecur']))
		{
			// stringify for-next recurrence rules
			$args['fornextrecur'] = implode(';', $args['fornextrecur']);

			if ($args['fornextrecur'] == '0;0;0')
			{
				$args['fornextrecur'] = '1;1;1';
			}
		}

		if (isset($args['minamountrecur']) && isset($args['maxamountrecur']) && $args['minamountrecur'] > $args['maxamountrecur'])
		{
			// cannot have min amount higher than max
			$args['maxamountrecur'] = $args['minamountrecur'];
		}

		if (isset($args['zipcodesfrom']))
		{
			$args['zipcodes'] = array();

			// stringify ZIP codes
			foreach ($args['zipcodesfrom'] as $i => $from)
			{
				$to = !empty($args['zipcodesto'][$i]) ? $args['zipcodesto'][$i] : $from;

				if (empty($from) && !empty($to))
				{
					// from empty, to filled-in
					$from = $to;
				}

				if ($from && $to)
				{	
					$args['zipcodes'][] = array(
						'from' => $from,
						'to'   => $to,
					);
				}
			}

			unset($args['zipcodesfrom']);
			unset($args['zipcodesto']);	
		}

		if (isset($args['zipcodes']) && !is_string($args['zipcodes']))
		{
			// stringify ZIP codes
			$args['zipcodes'] = json_encode($args['zipcodes']);
		}

		if (isset($args['listablecols']) && is_array($args['listablecols']))
		{
			$listable_cols = array();

			// stringify reservations list columns
			foreach ($args['listablecols'] as $k => $v)
			{
				$tmp = explode(':', $v);

				if ($tmp[1] == 1)
				{
					$listable_cols[] = $tmp[0];
				} 
			}

			$args['listablecols'] = implode(',', $listable_cols);
		}

		if (isset($args['listablecf']) && is_array($args['listablecf']))
		{
			$listable_cols = array();

			// stringify reservations list custom fields
			foreach ($args['listablecf'] as $k => $v)
			{
				$tmp = explode(':', $v);

				if ($tmp[1] == 1)
				{
					$listable_cols[] = $tmp[0];
				} 
			}

			$args['listablecf'] = implode(',', $listable_cols);
		}

		if (isset($args['waitlistsmscont']) && is_array($args['waitlistsmscont']))
		{
			$languages = VikAppointments::getKnownLanguages();	

			$sms_wl_cont = array();

			// stringify waiting list SMS contents
			for ($i = 0; $i < count($languages); $i++)
			{
				for ($j = 0; $j < 2; $j++)
				{
					$sms_wl_cont[$j][$languages[$i]] = $args['waitlistsmscont'][$j][$i];
				}
			}

			$args['waitlistsmscont'] = json_encode($sms_wl_cont);
		}

		if (isset($args['emplistmode']) && is_array($args['emplistmode']))
		{
			$active = false;

			// make sure at least one is active
			foreach ($args['emplistmode'] as $v)
			{
				$active = $active || $v;
			}

			if (!$active)
			{
				// turn on first one available
				$args['emplistmode']["1"] = 1;
			}

			// stringify employees ordering
			$args['emplistmode'] = json_encode($args['emplistmode']);
		}
	}

	/**
	 * Method to get a table object.
	 *
	 * @param   string  $name     The table name.
	 * @param   string  $prefix   The class prefix.
	 * @param   array   $options  Configuration array for table.
	 *
	 * @return  JTable  A table object.
	 *
	 * @throws  Exception
	 */
	public function getTable($name = '', $prefix = '', $options = array())
	{
		if (!$name)
		{
			// force configuration table
			$name = 'configuration';
		}

		if (!$prefix)
		{
			// use default system prefix
			$prefix = 'VAPTable';
		}

		// invoke parent
		return parent::getTable($name, $prefix, $options);
	}
}
