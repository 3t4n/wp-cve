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

namespace FireBox\Core\Form\Actions\Actions;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class MailChimp extends \FireBox\Core\Form\Actions\Action
{
	protected function prepare()
	{
		$this->action_settings = [
			'api_key' => isset($this->form_settings['attrs']['mailchimpAPIKey']) ? trim($this->form_settings['attrs']['mailchimpAPIKey']) : '',
			'list_id' => isset($this->form_settings['attrs']['mailchimpListID']) ? trim($this->form_settings['attrs']['mailchimpListID']) : '',
			'doubleoptin' => isset($this->form_settings['attrs']['mailchimpDoubleOptin']) ? $this->form_settings['attrs']['mailchimpDoubleOptin'] : false,
			'updateexisting' => isset($this->form_settings['attrs']['mailchimpUpdateExisting']) ? $this->form_settings['attrs']['mailchimpUpdateExisting'] : true,
			
		];
	}

	/**
	 * Runs the action.
	 * 
	 * @throws  Exception
	 * 
	 * @return  void
	 */
	public function run()
	{
		$api = new \FPFramework\Base\Integrations\MailChimp([
			'api' => $this->action_settings['api_key']
		]);

		

		$api->subscribe(
			$this->action_settings['list_id'],
			$this->submission['prepared_fields']['email']['value'],
			$this->getParsedFieldValues(),
			$this->action_settings['doubleoptin'],
			$this->action_settings['updateexisting'],
			
		);
		
		if (!$api->success())
		{
			$error = $api->getLastError();
			$error_parts = explode(' ', $error);

			if (function_exists('mb_strpos'))
			{
				// Make MalChimp errors translatable
				if (mb_strpos($error, 'is already a list member') !== false)
				{
					$error = sprintf(fpframework()->_('FPF_ERROR_USER_ALREADY_EXIST'), $error_parts[0]);
				}
	
				if (mb_strpos($error, 'fake or invalid') !== false)
				{
					$error = sprintf(fpframework()->_('FPF_ERROR_INVALID_EMAIL_ADDRESS'), $error_parts[0]);
				}
			}

			throw new \Exception($error);
		}

		return true;
	}

	/**
	 * Validates the action prior to running it.
	 * 
	 * @return  void
	 */
	public function validate()
	{
		if (empty($this->action_settings['api_key']))
		{
			throw new \Exception('MailChimp error: API Key is missing.');
		}

		if (empty($this->action_settings['list_id']))
		{
			throw new \Exception('MailChimp error: No MailChimp list selected.');
		}

		return true;
	}

	
}