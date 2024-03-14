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

use FPFramework\Helpers\ArrayHelper;

class AcyMailing extends Integration
{
	/**
	 * Subscribe user to AcyMailing.
	 * 
	 * @param   string  $email
	 * @param   array	$params
	 * @param   array	$list_ids
	 * @param	bool	$doubleOptin
	 */
	public function subscribe($email = null, $params = [], $list_ids = [], $doubleOptin = false)
	{
		if (!$this->checkValidVersion())
		{
			return;
		}

		if (!is_array($list_ids))
		{
			$list_ids = [$list_ids];
		}
		
		// Ensure List ID is correct
		$list = acym_get('class.list');
		if (count($list_ids))
		{
			foreach ($list_ids as $list_id)
			{
				if ($list->getOneById($list_id))
				{
					continue;
				}
				
				$error = new \WP_Error('acym_list_invalid', 'AcyMailing list does not exist.');
				$this->throwError($error);
				return;
			}
		}

		// Create user object
		$user = new \stdClass();
		$user->cms_id	 = acym_currentUserId();
		$user->email 	 = $email;
		$user->source 	 = $this->getSource();
		$user->confirmed = $doubleOptin ? 0 : 1;
		
		$user_fields = array_change_key_case($params);

		$user->name = isset($user_fields['name']) ? $user_fields['name'] : '';

		// Create user
		$acym = acym_get('class.user');

		// Check if exists
		$existing_user = $acym->getOneByEmail($email);

		if ($existing_user)
		{
			$user->id = $existing_user->id;
		}
		else
		{
			// Save user to database only if it's a new user.
			if (!$user->id = $acym->save($user))
			{
				$error = new \WP_Error('acym_user_creation_fail', 'AcyMailing cannot create user.');
				$this->throwError($error);
				return;
			}
		}

		// Send confirmation (double opt-in)
		if (!$user->confirmed)
		{
			$mailerHelper = new \AcyMailing\Helpers\MailerHelper();
			$mailerHelper->checkConfirmField = false;
			$mailerHelper->checkEnabled = false;
			$mailerHelper->report = $acym->config->get('confirm_message', 0);
	
			$mailerHelper->sendOne('acy_confirm', $user);
		}

		// Add custom fields to user
		$fieldClass = acym_get('class.field');

		$acym_fields = $fieldClass->getAllFieldsForModuleFront();

		unset($user_fields['name']); // Name is already used during user creation.

		$fields_to_store = [];

		foreach ($user_fields as $paramKey => $paramValue)
		{
			// Check if paramKey it's a custom field
			$field_found = array_filter($acym_fields, function($field) use($paramKey) {
				return (strtolower($field->name) == $paramKey || $field->id == $paramKey);
			});

			if ($field_found)
			{
				// Get the 1st occurence
				$field = array_shift($field_found);

				// AcyMailing 6 needs field's ID to recognize a field.
				$fields_to_store[$field->id] = $paramValue;

				// $paramValue output: array(1) { [0]=> string(2) "gr" }
				// AcyMailing will get the key as the value instead of "gr"
				// We combine to remove the keys in order to keep the values
				if (is_array($paramValue))
				{
					$fields_to_store[$field->id] = array_combine($fields_to_store[$field->id], $fields_to_store[$field->id]);
				}
			}
		}

		if ($fields_to_store)
		{
			$fieldClass->store($user->id, $fields_to_store);
		}

		// Subscribe user to the list
		$acym->subscribe($user->id, $list_ids, true, !$doubleOptin);

		// Set that the request was successful.
		$this->setSuccessful(true);

		return true;
	}

	private function getSource()
	{
		$metadata = $this->getMetadata();

		$default = 'FirePlugins AcyMailing Integration';
		
		return isset($metadata['source']) && !empty($metadata['source']) ? $metadata['source'] : $default;
	}

	/**
	 *  Returns all available lists
	 *
	 *  @return  array
	 */
	public function getLists()
	{
		if (!$this->checkValidVersion())
		{
			return;
		}

		// Get all lists
        $sql = 'SELECT id, name FROM #__acym_list AS list ORDER BY id DESC';

        return json_decode(wp_json_encode(acym_loadObjectList($sql)), true);
	}

	/**
	 * Check whether we have a valid version, else throw error.
	 * 
	 * @return  void
	 */
	private function checkValidVersion()
	{
		if (!function_exists('acym_config'))
		{
			$error = new \WP_Error('acym_missing', 'AcyMailing 6 or 7 does not exist.');
			$this->throwError($error);
			return;
		}

		$config = acym_config();

		$version = $config->get('version');

		if (version_compare($version, '6.0.0', 'lt'))
		{
			$error = new \WP_Error('acym_old_version', 'AcyMailing 6 or newer is required.');
			$this->throwError($error);
			return;
		}

		return true;
	}
}