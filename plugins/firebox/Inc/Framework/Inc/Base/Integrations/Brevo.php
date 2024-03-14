<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Base\Integrations;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Brevo extends Integration
{
	/**
	 * Create a new instance
	 * @param array $options The service's required options
	 * @throws \Exception
	 */
	public function __construct($options)
	{
		$this->setKey($options['api']);
		$this->setEndpoint('https://api.brevo.com/v3');
		
		$this->headers = array_merge($this->headers, [
			'Accept' => 'application/json',
			'Content-Type' => 'application/json',
			'API-Key' => $this->key
		]);
	}

	/**
	 *  Subscribes a user to a SendinBlue Account
	 *
	 *  API Reference:
	 *  https://developers.brevo.com/reference/createcontact
	 *
	 *  @param   string   $email   The user's email
	 *  @param   array    $params  All the form fields
	 *  @param   string   $listid  The List ID.
	 *  @param   boolean  $update_existing  Whether to update the existing contact.
	 *  @param   boolean  $double_optin  Whether to subscribe the user via double opt in.
	 *  @param   boolean  $double_optin_redirect_url  Enter the URL to redirect the user upon confirming their double opt-in.
	 *  @param   boolean  $double_optin_template_id  Enter the template ID of the template to use for the double opt-in confirmation email.
	 *
	 *  @return  boolean
	 */
	public function subscribe($email, $params, $listid = false, $update_existing = true, $double_optin = false, $double_optin_redirect_url = '', $double_optin_template_id = '')
	{
		$data = [
			'email'      => $email,
			'attributes' => (object) $params,
			'updateEnabled' => $update_existing
		];

		if ($listid)
		{
			if (!$double_optin)
			{
				$data['listIds'] = [(int) $listid];
			}
			else
			{
				$data['includeListIds'] = [(int) $listid];
				$data['redirectionUrl'] = (string) $double_optin_redirect_url;
				$data['templateId'] = (int) $double_optin_template_id;
			}
		}

		// Speciffy whether we are subscribing via double optin
		$suffix = $double_optin ? '/doubleOptinConfirmation' : '';

		$this->post('contacts' . $suffix, $data);

		return true;
	}

	/**
	 *  Returns all Campaign  lists
	 *
	 *  API Reference:
	 *  https://developers.brevo.com/reference/getlists-1
	 *
	 *  @return  array
	 */
	public function getLists()
	{
		$data = [
			'page' => 1,
			'page_limit' => 250
		];

		$lists = [];

		$data = $this->get('contacts/lists', $data);

		// sanity check
		if (!isset($data['lists']) || !is_array($data['lists']) || $data['count'] == 0)
		{
			return $lists;
		}

		foreach ($data['lists'] as $key => $list)
		{
			$lists[] = [
				'id'   => $list['id'],
				'name' => $list['name']
			];
		}

		return $lists;
		
	}

	/**
	 *  Get the last error returned by either the network transport, or by the API.
	 *
	 *  API Reference:
	 *  https://developers.brevo.com/docs/how-it-works#error-codes
	 *
	 *  @return  string
	 */
	public function getLastError()
	{
		if ($error =parent::getLastError())
		{
			return $error;
		}
		
		$body    = $this->last_response['body'];
		$message = '';

		if (!isset($body['code']))
		{
			return $message;
		}

		return $body['message'];
	}
}