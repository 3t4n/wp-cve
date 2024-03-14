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

namespace FireBox\Core\Form\SmartTags;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Submission extends \FPFramework\Base\SmartTags\SmartTag
{
	/**
	 * Returns the submsission ID
	 * 
	 * @return  string
	 */
	public function getID()
	{
		return isset($this->data['submission']['id']) ? $this->data['submission']['id'] : '';
	}

	/**
	 * Returns the submsission User ID
	 * 
	 * @return  string
	 */
	public function getUser_ID()
	{
		return isset($this->data['submission']['user_id']) ? $this->data['submission']['user_id'] : '';
	}

	/**
	 * Returns the submission created date
	 * 
	 * @return  string
	 */
	public function getCreated()
	{
		return isset($this->data['submission']['created_at']) ? $this->data['submission']['created_at'] : '';
	}

	/**
	 * Returns the submission modified date
	 * 
	 * @return  string
	 */
	public function getModified()
	{
		return isset($this->data['submission']['modified_at']) ? $this->data['submission']['modified_at'] : '';
	}

	/**
	 * Returns the submission created date
	 * 
	 * @return  string
	 */
	public function getDate()
	{
		return isset($this->data['submission']['created_at']) ? $this->data['submission']['created_at'] : '';
	}

	/**
	 * Returns the submission form id
	 * 
	 * @return  string
	 */
	public function getForm_ID()
	{
		return isset($this->data['submission']['form_id']) ? 'form-' . $this->data['submission']['form_id'] : '';
	}

	/**
	 * Returns the submission visitor id
	 * 
	 * @return  string
	 */
	public function getVisitor_ID()
	{
		return isset($this->data['submission']['visitor_id']) ? $this->data['submission']['visitor_id'] : '';
	}

	/**
	 * Returns the submission status
	 * 
	 * @return  string
	 */
	public function getStatus()
	{
		$state = isset($this->data['submission']['state']) ? $this->data['submission']['state'] : false;

		$label = '';

		switch ($state) {
			case 0:
				$label = firebox()->_('FB_SUBMISSION_UNCONFIRMED');
				break;
			case 1:
				$label = firebox()->_('FB_SUBMISSION_CONFIRMED');
				break;
		}

		return $label;
	}
}