<?php
namespace WPHR\HR_MANAGER;

use \WPHR\HR_MANAGER\Framework\WPHR_Settings_Page;

/**
 * Email Class
 */
class Email extends WPHR_Settings_Page {

	/**
	 * Email method ID.
	 *
	 * @var String
	 */
	public $id;

	/**
	 * Email method title.
	 *
	 * @var string
	 */
	public $title;

	/**
	 * Description for the email.
	 *
	 * @var string
	 */
	public $description;

	/**
	 * Plain text template path.
	 *
	 * @var string
	 */
	public $template_plain;

	/**
	 * HTML template path.
	 *
	 * @var string
	 */
	public $template_html;

	/**
	 * Recipients for the email.
	 *
	 * @var string
	 */
	public $recipient;

	/**
	 * Heading for the email content.
	 *
	 * @var string
	 */
	public $heading;

	/**
	 * Subject for the email.
	 *
	 * @var string
	 */
	public $subject;

	/**
	 * Object this email is for, for example a customer, product, or email.
	 *
	 * @var object
	 */
	public $object;

	/**
	 * Strings to find in subjects/headings.
	 *
	 * @var array
	 */
	public $find;

	/**
	 * Strings to replace in subjects/headings.
	 *
	 * @var array
	 */
	public $replace;

	/**
	 * Mime boundary (for multipart emails).
	 *
	 * @var string
	 */
	public $mime_boundary;

	/**
	 * Mime boundary header (for multipart emails).
	 *
	 * @var string
	 */
	public $mime_boundary_header;

	/**
	 * Form option fields.
	 *
	 * @var array
	 */
	public $form_fields = array();

	/**
	 * Email type
	 *
	 * @var string
	 */
	public $email_type = 'html';

	/**
	 * List of preg* regular expression patterns to search for,
	 * used in conjunction with $replace.
	 * https://raw.github.com/ushahidi/wp-silcc/master/class.html2text.inc
	 *
	 * @var array $search
	 * @see $replace
	 */
	public $plain_search = array(
		"/\r/", // Non-legal carriage return
		'/&(nbsp|#160);/i', // Non-breaking space
		'/&(quot|rdquo|ldquo|#8220|#8221|#147|#148);/i', // Double quotes
		'/&(apos|rsquo|lsquo|#8216|#8217);/i', // Single quotes
		'/&gt;/i', // Greater-than
		'/&lt;/i', // Less-than
		'/&#38;/i', // Ampersand
		'/&#038;/i', // Ampersand
		'/&amp;/i', // Ampersand
		'/&(copy|#169);/i', // Copyright
		'/&(trade|#8482|#153);/i', // Trademark
		'/&(reg|#174);/i', // Registered
		'/&(mdash|#151|#8212);/i', // mdash
		'/&(ndash|minus|#8211|#8722);/i', // ndash
		'/&(bull|#149|#8226);/i', // Bullet
		'/&(pound|#163);/i', // Pound sign
		'/&(euro|#8364);/i', // Euro sign
		'/&#36;/', // Dollar sign
		'/&[^&\s;]+;/i', // Unknown/unhandled entities
		'/[ ]{2,}/', // Runs of spaces, post-handling
	);

	/**
	 * List of pattern replacements corresponding to patterns searched.
	 *
	 * @var array $replace
	 * @see $search
	 */
	public $plain_replace = array(
		'', // Non-legal carriage return
		' ', // Non-breaking space
		'"', // Double quotes
		"'", // Single quotes
		'>', // Greater-than
		'<', // Less-than
		'&', // Ampersand
		'&', // Ampersand
		'&', // Ampersand
		'(c)', // Copyright
		'(tm)', // Trademark
		'(R)', // Registered
		'--', // mdash
		'-', // ndash
		'*', // Bullet
		'£', // Pound sign
		'EUR', // Euro sign. € ?
		'$', // Dollar sign
		'', // Unknown/unhandled entities
		' ', // Runs of spaces, post-handling
	);

	public function __construct() {
		$this->init_form_fields();
	}

	public function get_title() {
		return $this->title;
	}

	public function get_description() {
		return $this->description;
	}

	/**
	 * Get saved option id
	 *
	 * @return string
	 */
	public function get_option_id() {
		return 'wphr_email_settings_' . $this->id;
	}

	/**
	 * format_string function.
	 *
	 * @param mixed $string
	 * @return string
	 */
	public function format_string($string) {
		return str_replace($this->find, $this->replace, $string);
	}

	/**
	 * get_subject function.
	 *
	 * @return string
	 */
	public function get_subject() {
		return apply_filters('wphr_email_subject_' . $this->id, $this->format_string($this->subject), $this->object);
	}

	/**
	 * get_heading function.
	 *
	 * @return string
	 */
	public function get_heading() {
		return apply_filters('wphr_email_heading_' . $this->id, $this->format_string($this->heading), $this->object);
	}

	/**
	 * get_recipient function.
	 *
	 * @return string
	 */
	public function get_recipient() {
		return apply_filters('wphr_email_recipient_' . $this->id, $this->recipient, $this->object);
	}

	/**
	 * get_headers function.
	 *
	 * @return string
	 */
	public function get_headers() {
		return apply_filters('wphr_email_headers', "Content-Type: " . $this->get_content_type() . "\r\n", $this->id, $this->object);
	}

	/**
	 * get_attachments function.
	 *
	 * @return string|array
	 */
	public function get_attachments() {
		return apply_filters('wphr_email_attachments', array(), $this->id, $this->object);
	}

	/**
	 * get_type function.
	 *
	 * @return string
	 */
	public function get_email_type() {
		return $this->email_type ? $this->email_type : 'plain';
	}

	/**
	 * get_content_type function.
	 *
	 * @return string
	 */
	public function get_content_type() {
		switch ($this->get_email_type()) {
		case 'html':
			return 'text/html';
		case 'multipart':
			return 'multipart/alternative';
		default:
			return 'text/plain';
		}
	}

	/**
	 * get_blogname function.
	 *
	 * @return string
	 */
	public function get_blogname() {
		return wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	}

	/**
	 * get_content function.
	 *
	 * @return string
	 */
	public function get_content() {

		$this->sending = true;

		if ($this->get_email_type() == 'plain') {
			$email_content = preg_replace($this->plain_search, $this->plain_replace, strip_tags($this->get_content_plain()));
		} else {
			$email_content = $this->get_content_html();
		}

		return wordwrap($email_content, 70);
	}

	/**
	 * Apply inline styles to dynamic content.
	 *
	 * @param string|null $content
	 * @return string
	 */
	public function style_inline($content) {
		// make sure we only inline CSS for html emails
		if (in_array($this->get_content_type(), array('text/html', 'multipart/alternative')) && class_exists('DOMDocument')) {

			// get CSS styles
			ob_start();
			include WPHR_INCLUDES . '/email/email-css.php';
			$css = apply_filters('wphr_email_styles', ob_get_clean());

			try {

				// apply CSS styles inline for picky email clients
				$emogrifier = new \WPHR\HR_MANAGER\Lib\Emogrifier($content, $css);
				$content = $emogrifier->emogrify();

			} catch (Exception $e) {

				echo $e->getMessage();
			}
		}

		return $content;
	}

	/**
	 * Get the form fields after they are initialized.
	 * @return array of options
	 */
	public function get_form_fields() {
		return apply_filters('wphr_settings_email_form_fields_' . $this->id, $this->form_fields);
	}

	/**
	 * Send the email.
	 *
	 * @param string $to
	 * @param string $subject
	 * @param string $message
	 * @param string $headers
	 * @param string $attachments
	 * @return bool
	 */
	public function send($to, $subject, $message, $headers, $attachments) {
		$message = apply_filters('wphr_mail_content', $this->style_inline($message));
		$return = wphr_mail($to, $subject, $message, $headers, $attachments);

		return $return;
	}

	function generate_settings_html() {
		$settings = $this->get_form_fields();
		$this->output_fields($settings);
	}

	public function get_template_content($file_path, $args = []) {
		extract($args);

		ob_start();
		include $file_path;
		return ob_get_clean();
	}

	/**
	 * Get email setting by key
	 *
	 * @param  string  $option
	 * @param  string  $default
	 *
	 * @return string
	 */
	public function get_setting($option, $default = '') {
		$settings = get_option('wphr_settings_wphr-email_general', []);

		if (array_key_exists($option, $settings)) {
			return $settings[$option];
		}

		return $default;
	}

	public function admin_options() {
		?>
        <h3><?php echo esc_html($this->get_title()); ?></h3>
        <?php echo wpautop(wp_kses_post($this->get_description())); ?>

        <?php
/**
		 * wphr_email_settings_before action hook.
		 *
		 * @param string $email The email object
		 */
		do_action('wphr_email_settings_before', $this);
		?>

        <table class="form-table">
            <?php $this->generate_settings_html();?>
        </table>

        <?php
/**
		 * wphr_email_settings_after action hook.
		 *
		 * @param string $email The email object
		 */
		do_action('wphr_email_settings_after', $this);
		?>
        <?php
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
