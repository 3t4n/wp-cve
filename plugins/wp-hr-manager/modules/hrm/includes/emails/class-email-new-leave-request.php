<?php
namespace WPHR\HR_MANAGER\HRM\Emails;

use WPHR\HR_MANAGER\Email;
use WPHR\HR_MANAGER\Framework\Traits\Hooker;

/**
 * New Leave Request
 */
class New_Leave_Request extends Email {

	use Hooker;

	function __construct() {
		$this->id = 'new-leave-request';
		$this->title = __('New Leave Request', 'wphr');
		$this->description = __('New leave request notification to HR Manager.', 'wphr');

		$this->subject = __('New leave request received', 'wphr');
		$this->heading = __('New Leave Request', 'wphr');

		$this->find = [
			'full-name' => '{employee_name}',
			'employee-url' => '{employee_url}',
			'leave_type' => '{leave_type}',
			'date_from' => '{date_from}',
			'date_to' => '{date_to}',
			'no_days' => '{no_days}',
			'reason' => '{reason}',
			'requests_url' => '{requests_url}',
		];

		remove_all_actions('wphr_admin_field_' . $this->id . '_help_texts');
		$this->action('wphr_admin_field_' . $this->id . '_help_texts', 'replace_keys');

		parent::__construct();
	}

	function get_args() {
		return [
			'email_heading' => $this->heading,
			'email_body' => wpautop($this->get_option('body')),
		];
	}

	/**
	 * Trigger sending email
	 *
	 * @since 1.0.0
	 * @since 1.2.0 Send single email to multiple recipients.
	 *              Add `wphr_new_leave_request_notification_recipients` filter
	 *
	 * @param int $request_id
	 *
	 * @return boolean
	 */
	public function trigger($request_id = null) {
		$request = wphr_hr_get_leave_request($request_id);

		if (!$request) {
			return;
		}

		$this->heading = $this->get_option('heading', $this->heading);
		$this->subject = $this->get_option('subject', $this->subject);

		$format = wphr_get_option('date_format', 'wphr_settings_general', 'd-m-Y');
		$days = $request->days;
		$days = $days > 1 ? sprintf(__('%d Days', 'hrm'), $days) : sprintf(__('%d Day', 'hrm'), $days);

		if (wphr_format_date($request->start_date) == wphr_format_date($request->end_date)) {
			$start_time = strtotime($request->start_date);
			$end_time = strtotime($request->end_date);
			$time_difference = $end_time - $start_time;
			$hours = date('G', $time_difference);
			$minutes = date('i', $time_difference);
			//$hours = ( $end_time - $start_time );
			$display_time = '';
			if ($hours < 23) {
				$format = $format . ' H:i:s';
				if ($hours > 0) {
					$display_time .= $hours > 1 ? sprintf(__('%d Hours', 'hrm'), $hours) : sprintf(__('%d Hour', 'hrm'), $hours);
				}
				if ($minutes > 0) {
					$display_time .= ' ' . sprintf(__('%d Minutes', 'hrm'), $minutes);
				}
				$days = $display_time;
			} else {
				$days = $days > 1 ? sprintf(__('%d Days', 'hrm'), $days) : sprintf(__('%d Day', 'hrm'), $days);
			}
		}

		$this->replace = [
			'full-name' => $request->display_name,
			'employee-url' => sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=wphr-hr-employee&action=view&id=' . $request->user_id), $request->display_name),
			'leave_type' => $request->policy_name,
			'date_from' => wphr_format_date($request->start_date, $format),
			'date_to' => wphr_format_date($request->end_date, $format),
			'no_days' => $days,
			'reason' => $request->reason,
			'requests_url' => sprintf('<a class="button green" href="%s">%s</a>', admin_url('admin.php?page=wphr-leave'), __('View Request', 'wphr')),
		];

		$subject = $this->get_subject();
		$content = $this->get_content();
		$headers = $this->get_headers();
		$attachments = $this->get_attachments();
		$recipients = [];

		$managers = get_users(['role' => wphr_hr_get_manager_role()]);

		if (!$managers) {
			return;
		}

		foreach ($managers as $hr) {
			$is_receive_mail_for_leaves = get_user_meta($hr->ID, 'receive_mail_for_leaves', true);
			if ($is_receive_mail_for_leaves == '' || $is_receive_mail_for_leaves == 1) {
				$recipients[] = $hr->user_email;
			}
		}
		// Check line manager is exist or not
		$employee = new \WPHR\HR_MANAGER\HRM\Employee(intval($request->user_id));
		if ($employee->send_mail_to_reporter && $employee->reporting_to) {
			$line_manager = get_user_by('id', $employee->reporting_to);
			$recipients[] = $line_manager->user_email;
		}
		$recipients = apply_filters('wphr_new_leave_request_notification_recipients', $recipients, $request);

		return $this->send($recipients, $subject, $content, $headers, $attachments);
	}

	/**
	 * get_content_html function.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_html() {
		$message = $this->get_template_content(WPHR_INCLUDES . '/email/email-body.php', $this->get_args());
		return $this->format_string($message);
	}

	/**
	 * get_content_plain function.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_plain() {
		$message = $this->get_template_content(WPHR_INCLUDES . '/email/email-body.php', $this->get_args());

		return $message;
	}

	/**
	 * Initialise settings form fields.
	 */
	public function init_form_fields() {
		$this->form_fields = [
			[
				'title' => __('Subject', 'wphr'),
				'id' => 'subject',
				'type' => 'text',
				'description' => sprintf(__('This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', 'wphr'), $this->subject),
				'placeholder' => '',
				'default' => $this->subject,
				'desc_tip' => true,
			],
			[
				'title' => __('Email Heading', 'wphr'),
				'id' => 'heading',
				'type' => 'text',
				'description' => sprintf(__('This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.', 'wphr'), $this->heading),
				'placeholder' => '',
				'default' => $this->heading,
				'desc_tip' => true,
			],
			[
				'title' => __('Email Body', 'wphr'),
				'type' => 'wysiwyg',
				'id' => 'body',
				'description' => sprintf(__('This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.', 'wphr'), $this->heading),
				'placeholder' => '',
				'default' => '',
				'desc_tip' => true,
				'custom_attributes' => [
					'rows' => 5,
					'cols' => 45,
				],
			],
			[
				'type' => $this->id . '_help_texts',
			],
		];
	}

	/**
	 * Template tags
	 *
	 * @return void
	 */
	function replace_keys() {
		?>
        <tr valign="top" class="single_select_page">
            <th scope="row" class="titledesc"><?php _e('Template Tags', 'wphr');?></th>
            <td class="forminp">
                <em><?php _e('You may use these template tags inside subject, heading, body and those will be replaced by original values', 'wphr');?></em>:
                <?php echo '<code>' . implode('</code>, <code>', $this->find) . '</code>'; ?>
            </td>
        </tr>
        <?php
}

}
