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

class Email extends \FireBox\Core\Form\Actions\Action
{
	protected function prepare()
	{
		$this->action_settings = [
			'recipient' => isset($this->form_settings['attrs']['emailSendToEmailAddress']) ? $this->form_settings['attrs']['emailSendToEmailAddress'] : '{fpf field.email}',
			'subject' => isset($this->form_settings['attrs']['emailSubject']) ? $this->form_settings['attrs']['emailSubject'] : 'New Submission #{fpf submission.id}: Contact Form',
			'from_name' => isset($this->form_settings['attrs']['emailFromName']) ? $this->form_settings['attrs']['emailFromName'] : '{fpf site.name}',
			'from_email' => isset($this->form_settings['attrs']['emailFromEmail']) ? $this->form_settings['attrs']['emailFromEmail'] : '{fpf site.email}',
			'reply_to_name' => isset($this->form_settings['attrs']['emailReplyToName']) ? $this->form_settings['attrs']['emailReplyToName'] : '',
			'reply_to_email' => isset($this->form_settings['attrs']['emailReplyToEmail']) ? $this->form_settings['attrs']['emailReplyToEmail'] : '',
			'cc' => isset($this->form_settings['attrs']['emailCC']) ? $this->form_settings['attrs']['emailCC'] : [],
			'bcc' => isset($this->form_settings['attrs']['emailBCC']) ? $this->form_settings['attrs']['emailBCC'] : [],
			'message' => isset($this->form_settings['attrs']['emailMessage']) ? wpautop($this->form_settings['attrs']['emailMessage']) : '{fpf all_fields}',
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
		// Set content type and From Name/Email
		$headers = [
			'Content-Type: text/html; charset=UTF-8',
			'From: ' . $this->action_settings['from_name'] . ' <' . $this->action_settings['from_email'] . '>'
		];

		// Set CC
		if (isset($this->action_settings['cc']) && $this->action_settings['cc'])
		{
			foreach ($this->action_settings['cc'] as $cc)
			{
				$headers[] = 'Cc: ' . $cc;
			}
		}

		// Set BCC
		if (isset($this->action_settings['bcc']) && $this->action_settings['bcc'])
		{
			foreach ($this->action_settings['bcc'] as $bcc)
			{
				$headers[] = 'Bcc: ' . $bcc;
			}
		}

		$this->action_settings = apply_filters('firebox/form/actions/email/settings', $this->action_settings, $this->submission);
		
		foreach ($this->action_settings['recipient'] as $to)
		{
			wp_mail($to, $this->action_settings['subject'], $this->action_settings['message'], $headers);
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
		if (empty($this->action_settings['recipient']))
		{
			throw new \Exception('Form error: Recipient is missing.');
		}

		$recipients = array_filter(array_map('trim', explode(',', $this->action_settings['recipient'])));
		foreach ($recipients as $email)
		{
			if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				throw new \Exception('Form error: Recipient email is invalid: ' . $email . '.');
			}
		}
		$this->action_settings['recipient'] = $recipients;

		if (empty($this->action_settings['subject']))
		{
			throw new \Exception('Form error: Subject is missing.');
		}
		
		if (empty($this->action_settings['from_name']))
		{
			throw new \Exception('Form error: From Name is missing.');
		}

		if (empty($this->action_settings['from_email']))
		{
			throw new \Exception('Form error: From Email is missing.');
		}

		if (!filter_var($this->action_settings['from_email'], FILTER_VALIDATE_EMAIL))
		{
			throw new \Exception('Form error: From Email is invalid: ' . $this->action_settings['from_email'] . '.');
		}

		if (!empty($this->action_settings['cc']))
		{
			$cc = array_filter(array_map('trim', explode(',', $this->action_settings['cc'])));
			foreach ($cc as $email)
			{
				if (!filter_var($email, FILTER_VALIDATE_EMAIL))
				{
					throw new \Exception('Form error: CC email is invalid: ' . $email . '.');
				}
			}
			$this->action_settings['cc'] = $cc;
		}

		if (!empty($this->action_settings['bcc']))
		{
			$bcc = array_filter(array_map('trim', explode(',', $this->action_settings['bcc'])));
			foreach ($bcc as $email)
			{
				if (!filter_var($email, FILTER_VALIDATE_EMAIL))
				{
					throw new \Exception('Form error: BCC email is invalid: ' . $email . '.');
				}
			}
			$this->action_settings['bcc'] = $bcc;
		}

		if (empty($this->action_settings['message']))
		{
			throw new \Exception('Form error: Message is missing.');
		}

		return true;
	}
}