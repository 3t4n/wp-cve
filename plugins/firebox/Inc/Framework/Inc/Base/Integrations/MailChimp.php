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

class MailChimp extends Integration
{
	/**
	 *  MailChimp Endpoint URL
	 *
	 *  @var  string
	 */
	protected $endpoint = 'https://<dc>.api.mailchimp.com/3.0';

	/**
	 * Create a new instance
	 * 
	 * @param array $options The service's required options
	 * @throws \Exception
	 */
	public function __construct($options)
	{
		$this->setKey($options);

		if (strpos($this->key, '-') === false)
		{
			return;
		}

		list(, $data_center) = explode('-', $this->key);
		$this->endpoint  = str_replace('<dc>', $data_center, $this->endpoint);

		$this->headers['Authorization'] = 'Basic ' . base64_encode('user:'. $this->key);
	}

	/**
	 * Subscribe user to MailChimp
	 *
	 * @param	string	$list_id			The ID of the MailChimp list
	 * @param	string	$email				The email address of the subscriber
	 * @param 	object	$merge_fields		The custom field that are associated with the subscriber where the keys are the merge tags.
	 * @param 	boolean	$double_optin		If true, the subscriber will be added with status "pending" and a confirmation email will be sent to the user. 
	 * @param 	boolean	$allow_update		If true, the subscriber will be updated if it already exists. Otherwise, an error will be thrown.
	 * @param 	array	$tags				The tags that are associated with the subscriber.
	 * @param 	string	$tags_replace		Determines what changes to make to the subscriber's tags. Values: add_only, replace_all
	 * @param 	array	$interests			The interests that are associated with the subscriber.
	 * @param 	string	$interests_replace	Determines what changes to make to the subscriber's groups/interests. Values: add_only, replace_all
	 * 
	 * @return 	void
	 */
	public function subscribe($list_id, $email, $merge_fields = [], $double_optin = true, $allow_update = true, $tags = [], $tags_replace = 'add_only', $interests = [], $interests_replace = 'add_only')
	{
		$data = [
			'email_address' => $email,
			'status' 		=> $double_optin ? 'pending' : 'subscribed',
			'merge_fields'	=> (object) $merge_fields,
			'tags'			=> ArrayHelper::cleanArray($tags)
		];

		$member = $this->getMemberByEmail($list_id, $email);

		// If $interests contains a "dynamic" key, then we were passed labels of the interests groups and we must validate them
		if (isset($interests['dynamic']))
		{
			$interests = $this->validateInterestCategories($list_id, $interests['dynamic']);
		}
		// Otherwise, we were given a list of interests groups IDs
		else
		{
			// Prepare Interests
			$interests = ArrayHelper::cleanArray($interests);
			$interests = $interests ? array_fill_keys($interests, true) : [];
	
			if ($member && isset($member['interests']) && $interests_replace == 'replace_all')
			{
				// Disable all existing groups
				$memberInterests = array_fill_keys(array_keys($member['interests']), false);

				// Merge new interests with existing interests
				$interests = array_merge($memberInterests, $interests);
			}
		}

		$data['interests'] = (object) $interests;

		if (!$member)
		{
			// API Doc: https://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/#create-post_lists_list_id_members
			$this->post('lists/' . $list_id . '/members', $data);
			return;
		}

		// Member exists
		// Since member exists and we don't allow updating existing member, throw an error.
		if (!$allow_update)
		{
			$error = new \WP_Error('member_already_exists', 'Member already exists');
			$this->throwError($error);
			return;
		}

		// Skip double opt-in if the existing member is already confirmed
		if (isset($member['status']) && $member['status'] == 'subscribed')
		{
			$data['status'] = $member['status'];
		}
		
		// Update existing member 
		// API Doc: https://mailchimp.com/developer/marketing/api/list-members/add-or-update-list-member
		$this->put('lists/' . $list_id . '/members/' . $member['id'], $data);

		// Remove existing member tags not included in the given Tags.
		if ($member['tags'] && $tags_replace == 'replace_all')
		{
			$currentTags = array_map(function($item) { return $item['name']; }, $member['tags']);

			if ($removeTags = array_diff($currentTags, $data['tags']))
			{	
				$rTags = [];

				foreach ($removeTags as $removeTag)
				{
					$rTags[] = [
						'name'   => $removeTag,
						'status' => 'inactive'
					];
				}

				// API Doc: https://mailchimp.com/developer/marketing/api/list-member-tags/add-or-remove-member-tags/
				$this->post('lists/' . $list_id . '/members/' . $member['id'] . '/tags', ['tags' => $rTags]);
			}
		}

		return true;
	}

	/**
	 *  Gets the values accepted for the particular Interest Category
	 *
	 *  @param   string  $listID              The List ID
	 *  @param   string  $interestCategoryID  The Interest Category ID
	 *  
	 *  @deprecated Use subscribeV2()
	 * 
	 *  @return  array                       
	 */
	public function getInterestCategoryValues($listID, $interestCategoryID)
	{
		if (!$interestCategoryID || !$listID) 
		{
			return array();
		}

		$data = $this->get('/lists/' . $listID . '/interest-categories/' . $interestCategoryID . '/interests');

		if (isset($data['total_items']) && $data['total_items'] == 0) 
		{
			return array();
		}

		return $data['interests'];
	}

	/**
	 *  Gets the Interest Categories from MailChimp
	 *
	 *  @param   string  $listID  The List ID
	 * 
	 *  @deprecated Use subscribeV2()
	 *
	 *  @return  array           
	 */
	public function getInterestCategories($listID)
	{
		if (!$listID) 
		{
			return;
		}

		$data = $this->get('/lists/' . $listID . '/interest-categories');

		if (!$this->success())
		{
			return;
		}

		if (isset($data['total_items']) && $data['total_items'] == 0) 
		{
			return;
		}

		return $data['categories'];
	}

	/**
	 *  Filters the interests categories through the form fields
	 *  and constructs the interests array for the subscribe method
	 *
	 *  @param   string  $listID  The List ID
	 *  @param   array   $params  The Form fields
	 * 
	 *  @deprecated Use subscribeV2()
	 *
	 *  @return  array            
	 */
	public function validateInterestCategories($listID, $params)
	{
		if (!$params || !$listID) 
		{
			return array();
		}

		$interestCategories = $this->getInterestCategories($listID);

		if (!$interestCategories) 
		{
			return array();
		}

		$categories = array();

		foreach ($interestCategories as $category) 
		{
			if (array_key_exists($category['title'], $params)) 
			{
				$categories[] = array('id' => $category['id'], 'title' => $category['title']);
			}
		}

		if (empty($categories)) 
		{
			return array();
		}

		$interests = array();

		foreach ($categories as $category) 
		{
			$data = $this->getInterestCategoryValues($listID, $category['id']);

			if (isset($data['total_items']) && $data['total_items'] == 0) 
			{
				continue;
			}

			foreach ($data as $interest) 
			{
				if (in_array($interest['name'], (array) $params[$category['title']]))
				{
					$interests[$interest['id']] = true;
				}
				else 
				{
					$interests[$interest['id']] = false;
				}
			}
		}

		return $interests;
	}

	/**
	 *  Returns all available MailChimp lists
	 *
	 *  https://developer.mailchimp.com/documentation/mailchimp/reference/lists/#read-get_lists
	 *
	 *  @return  array
	 */
	public function getLists()
	{
		$data = $this->get('/lists');

		if (!$this->success())
		{
			return;
		}

		if (!isset($data['lists']) || !is_array($data['lists']))
		{
			return;
		}

		$lists = [];

		foreach ($data['lists'] as $key => $list)
		{
			$lists[] = array(
				'id'   => $list['id'],
				'name' => $list['name']
			);
		}

		return $lists;
	}

	/**
	 * Find a subscriber in a list by email address 
	 *
	 * @param string $list_id	The MailChimp list ID
	 * @param string $email		The email address
	 * 
	 * @return mixed Object on success, false on failure
	 */
	public function getMemberByEmail($list_id, $email)
	{
		$subscriberHash = md5(strtolower($email));
		$result = $this->get('lists/' . $list_id . '/members/' . $subscriberHash);

		return $this->success() ? $result : false;
	}

	/**
	 *  Get the last error returned by either the network transport, or by the API.
	 *
	 *  @return  string
	 */
	public function getLastError()
	{
		if ($error =parent::getLastError())
		{
			return $error;
		}
		
		$body = $this->last_response['body'];

		if (isset($body['errors']))
		{
			$error = $body['errors'][0];
			return $error['field'] . ': ' . $error['message'];
		}

		if (isset($body['detail']))
		{
			return $body['detail'];
		}
	}

	/**
	 *  The get() method overridden so that it handles
	 *  the default item paging of MailChimp which is 10
	 *
	 *  @param   string          $method URL of the API request method
	 *  @param   array $args     Assoc array of arguments (usually your data)
	 *  @return  array|false     Assoc array of API response, decoded from JSON
	 */
	public function get($method, $args = [])
	{
		$data = $this->makeRequest('get', $method, $args);

		if ($data && isset($data['total_items']) && (int) $data['total_items'] > 10)
		{
			$args['count'] = $data['total_items'];
			return $this->makeRequest('get', $method, $args);
		}

		return $data;
	}
}