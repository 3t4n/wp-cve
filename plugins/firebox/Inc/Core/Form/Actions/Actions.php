<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\Form\Actions;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Actions
{
	private $form_settings = [];

	private $field_values = [];

	private $submission = [];

	private $error_message = '';

	public function __construct($form_settings = [], $field_values = [], $submission = [])
	{
		$this->form_settings = $form_settings;
		$this->field_values = $field_values;
		$this->submission = $submission;
	}
	
	/**
	 * Runs all enabled actions.
	 * 
	 * @return  void
	 */
	public function run()
	{
		if (!$this->form_settings || !$this->field_values || !$this->submission)
		{
			return true;
		}

		if (!$this->checkEmailField())
		{
			return false;
		}

		$actions = $this->form_settings['attrs']['actions'];

		foreach ($actions as $key => $enabled)
		{
			if (!$enabled)
			{
				continue;
			}

			/**
			 * Backwards Compatibility - Start
			 * 
			 * Use the updated Brevo service, for any actions that are already saved and point to SendInBlue
			 */
			if ($key === 'SendInBlue')
			{
				$key = 'Brevo';
			}
			/**
			 * Backwards Compatibility - End
			 */
			
			$class = '\FireBox\Core\Form\Actions\Actions\\' . $key;

			if (!class_exists($class))
			{
				continue;
			}

			$class = new $class($this->form_settings, $this->field_values, $this->submission);

			try {
				if ($class->validate())
				{
					$class->run();
				}
			}
			catch (\Exception $e)
			{
				$this->error_message = $e->getMessage();
				return;
			}
		}
		
		return true;
	}

	/**
	 * If we have enabled any action then we require an email field
	 * with Field Name set to email.
	 * 
	 * @return  bool
	 */
	private function checkEmailField()
	{
		$actions = isset($this->form_settings['attrs']['actions']) ? $this->form_settings['attrs']['actions'] : false;
		if (!$actions)
		{
			return true;
		}

		$email_required = false;
		foreach ($actions as $key => $enabled)
		{
			if (!$enabled)
			{
				continue;
			}

			$email_required = true;
		}

		if (!$email_required)
		{
			return true;
		}
		
		// Ensure we have an Email field with "email" Field Name
		if (!isset($this->field_values['email']))
		{
			$this->error_message = 'Missing Email field from the form.';
			return false;
		}

		// The Email field is required
		if (empty($this->field_values['email']['value']))
		{
			$this->error_message = 'Missing Email field value.';
			return false;
		}

		return true;
	}

	/**
	 * Returns the error message.
	 * 
	 * @return  string
	 */
	public function getErrorMessage()
	{
		return $this->error_message;
	}
}