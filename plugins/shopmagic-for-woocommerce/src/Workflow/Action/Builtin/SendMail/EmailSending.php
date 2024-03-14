<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Action\Builtin\SendMail;

use WPDesk\ShopMagic\Components\Mailer\Email;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Workflow\Automation\Automation;

class EmailSending {

	/** @var Email */
	public $email;

	/** @var Automation */
	private $automation;

	/** @var Customer|null */
	private $customer;

	public function __construct( Email $email, Automation $automation, ?Customer $customer = null ) {
		$this->email      = $email;
		$this->automation = $automation;
		$this->customer   = $customer;
	}

	public function get_email(): Email {
		return $this->email;
	}

	public function get_automation(): Automation {
		return $this->automation;
	}

	public function get_customer(): ?Customer {
		return $this->customer;
	}

	public function with_email( Email $email ): self {
		$self        = clone $this;
		$self->email = $email;

		return $self;
	}


}
