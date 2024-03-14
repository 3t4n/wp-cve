<?php

namespace ZPOS\Emails;

class Receipt extends \WC_Email
{
	public $id = 'zpos_receipt';
	public $title = 'Order Receipt';

	public function __construct()
	{
		parent::__construct();

		$this->email_type = 'html';

		$this->heading = 'Order Receipt';
		$this->subject = 'Order Receipt';

		$this->template_html = 'receipt.php';
		$this->template_base = __DIR__ . '/templates/';

		$this->customer_email = true;
		$this->manual = true;

		add_filter('zpos_receipt_email', [$this, 'trigger'], 10, 2);
	}

	public function init_form_fields()
	{
		$this->form_fields = [
			'subject' => [
				'title' => 'Subject',
				'type' => 'text',
				'default' => ''
			],
			'heading' => [
				'title' => 'Email Heading',
				'type' => 'text',
				'default' => ''
			]
		];
	}

	public function get_content_html()
	{
		return wc_get_template_html($this->template_html, array(
			'order' => $this->object,
			'email_heading' => $this->get_heading()
		), '', $this->template_base);
	}


	public function trigger(\WC_Order $order, $email)
	{

		if (!$order)
			return;

		if (isset($email)) {
			$this->recipient = $email;
		}

		// setup order object
		$this->object = $order;

		if (!$this->get_recipient())
			return;

		return (bool) $this->send($this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments());
	}
}
