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

class Action
{
	/**
	 * Form settings.
	 * 
	 * @var  array
	 */
	protected $form_settings = [];

	/**
	 * Fields values.
	 * 
	 * @var  array
	 */
	protected $field_values = [];

	/**
	 * Submission.
	 * 
	 * @var  array
	 */
	protected $submission = [];

	/**
	 * Action settings.
	 * 
	 * @var  array
	 */
	protected $action_settings = [];
	
	/**
	 * Error message.
	 * 
	 * @var  string
	 */
	private $error_message = '';

	public function __construct($form_settings = [], $field_values = [], $submission = [])
	{
		$this->form_settings = $form_settings;
		$this->field_values = $field_values;
		$this->submission = $submission;

		$this->prepare();

		$this->replaceSmartTags();
	}

	/**
	 * Runs once the action has been initialized.
	 * 
	 * @return  void
	 */
	protected function prepare()
	{}

	/**
	 * Validates the action prior to running it.
	 * 
	 * @return  void
	 */
	public function validate()
	{}

	/**
	 * Runs the action.
	 * 
	 * @throws  Exception
	 * 
	 * @return  void
	 */
	public function run()
	{}

	/**
	 * Returns the fields values in a key,value array pair.
	 * 
	 * @return  array
	 */
	protected function getParsedFieldValues()
	{
		$parsed = [];

		foreach ($this->field_values as $key => $value)
		{
			$parsed[$key] = $value['value'];
		}
		
		return $parsed;
	}

	/**
	 * Replaces the Smart Tags within the email payload.
	 * 
	 * @return  void
	 */
	protected function replaceSmartTags()
	{
		// Replace Smart Tags
		$tags = new \FPFramework\Base\SmartTags\SmartTags();
		
		// register FB Smart Tags
		$tags->register('\FireBox\Core\Form\SmartTags', FBOX_BASE_FOLDER . '/Inc/Core/Form/SmartTags', [
			'field_values' => $this->field_values,
			'submission' => $this->submission
		]);

		$this->action_settings = $tags->replace($this->action_settings);
	}
}