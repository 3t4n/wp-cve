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

class Brevo extends \FireBox\Core\Form\Actions\Action
{
	protected function prepare()
	{
		$attrs = $this->form_settings['attrs'];

		/**
		 * Backwards Compatibility - Start.
		 * 
		 * Reason: When we migrated from Sendinblue to Brevo, we indeed changed our Javascript keys
		 * from pointing from SendInBlue to Brevo, however, the already saved data in the database
		 * still points to SendInBlue. So, we need to check if the SendInBlue API key is set, and if
		 * so, we need to use the SendInBlue settings.
		 * 
		 * Once this is removed, $prefix should point to "brevo".
		 * 
		 * @since  2.1.1
		 */
		$prefix = isset($attrs['brevoAPIKey']) ? 'brevo' : 'sendinblue';
		/**
		 * Backwards Compatibility - End
		 */
		
		$this->action_settings = [
			'api_key' => isset($attrs[$prefix . 'APIKey']) ? trim($attrs[$prefix . 'APIKey']) : '',
			'list_id' => isset($attrs[$prefix . 'ListID']) ? trim($attrs[$prefix . 'ListID']) : '',
			'updateexisting' => isset($attrs[$prefix . 'UpdateExisting']) ? $attrs[$prefix . 'UpdateExisting'] : true,
			'doubleoptin' => isset($attrs[$prefix . 'DoubleOptin']) ? $attrs[$prefix . 'DoubleOptin'] : false,
			'doubleoptin_redirect_url' => isset($attrs[$prefix . 'DOIRedirectURL']) ? $attrs[$prefix . 'DOIRedirectURL'] : '',
			'doubleoptin_template_id' => isset($attrs[$prefix . 'DOITemplateID']) ? $attrs[$prefix . 'DOITemplateID'] : ''
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
		$api = new \FPFramework\Base\Integrations\Brevo([
			'api' => $this->action_settings['api_key']
		]);

		$api->subscribe(
			$this->submission['prepared_fields']['email']['value'],
			$this->getParsedFieldValues(),
			$this->action_settings['list_id'],
			$this->action_settings['updateexisting'],
			$this->action_settings['doubleoptin'],
			$this->action_settings['doubleoptin_redirect_url'],
			$this->action_settings['doubleoptin_template_id']
		);
		
		if (!$api->success())
		{
			throw new \Exception($api->getLastError());
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
			throw new \Exception('Brevo error: API Key is missing.');
		}

		if (empty($this->action_settings['list_id']))
		{
			throw new \Exception('Brevo error: No Brevo list selected.');
		}

		return true;
	}
}