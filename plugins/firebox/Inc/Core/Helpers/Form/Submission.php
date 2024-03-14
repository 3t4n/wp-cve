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

namespace FireBox\Core\Helpers\Form;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Submission
{
	/**
	 * Create submission.
	 * 
	 * @param   string	$form_id
	 * @param   int		$state
	 * @param   bool	$save
	 * 
	 * @return  array
	 */
	public static function create($form_id = null, $state = 1, $save = true)
	{
		if (!$form_id)
		{
			return;
		}
		
		$factory = new \FPFramework\Base\Factory();
		
		$submission_payload = [
			'form_id' => str_replace('form-', '', $form_id),
			'visitor_id' => $factory->getVisitorID(),
			'user_id' => get_current_user_id(),
			'state' => $state,
			'created_at' => gmdate('Y-m-d H:i:s'),
			'modified_at' => null
		];

		if ($save)
		{
			if (!$submission_id = firebox()->tables->submission->insert($submission_payload))
			{
				return;
			}
		}
		else
		{
			$submission_id = 0;
		}

		return array_merge($submission_payload, [
			'id' => $submission_id,
		]);
	}

	/**
	 * Updates the submission state.
	 * 
	 * @param   int   $submission_id
	 * @param   int   $state
	 * 
	 * @return  bool
	 */
	public static function updateState($submission_id = null, $state = null)
	{
		if (!$submission_id || !in_array($state, [0, 1]))
		{
			return;
		}

		$data = [
			'state' => $state
		];

		$where = [
			'id' => $submission_id
		];
		
		return firebox()->tables->submission->update($data, $where);
	}

	/**
	 * Returns the submission details given its ID.
	 * 
	 * @param   int   $id
	 * 
	 * @return  bool
	 */
	public static function get($id = null)
	{
		if (!$id)
		{
			return;
		}
		
		$submission = firebox()->tables->submission->getResults([
			'where' => [
				'id = ' => "'" . esc_sql($id) . "'"
			]
		], true);

		if (!$submission)
		{
			return;
		}

		$submission = $submission[0];

		$submission->form = Form::getFormByID($submission->form_id);

		$submission->meta = SubmissionMeta::getMeta($id);
		
		return $submission;
	}
}