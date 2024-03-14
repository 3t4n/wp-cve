<?php

namespace WPDesk\ShopMagic\Integration\Mailchimp;

use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Integration\ContactForms\FormEntry;

final class MemberParamsBag {

	/** @var scalar */
	private $double_opt_in;

	/** @var \WC_Order|null */
	private $order;

	/** @var Customer|null */
	private $customer;

	/** @var scalar */
	private $list_id;

	/** @var FormEntry|null */
	private $form;

	public function with_order( \WC_Order $order ): self {
		$self        = clone $this;
		$self->order = $order;

		return $self;
	}

	public function with_customer( Customer $customer ): self {
		$self           = clone $this;
		$self->customer = $customer;

		return $self;
	}

	public function with_form_entry( FormEntry $form ): self {
		$self       = clone $this;
		$self->form = $form;

		return $self;
	}

	public function with_double_opt_in( $value ): self {
		$self                = clone $this;
		$self->double_opt_in = $value;

		return $self;
	}

	public function is_double_opt_in(): string {
		return filter_var( $this->double_opt_in, \FILTER_VALIDATE_BOOLEAN ) ? 'yes' : '';
	}

	public function get_list_id(): string {
		return (string) $this->list_id;
	}

	public function with_list_id( $list_id ): self {
		$self          = clone $this;
		$self->list_id = $list_id;

		return $self;
	}

	public function get_order(): ?\WC_Order {
		return $this->order;
	}

	public function get_customer(): ?Customer {
		return $this->customer;
	}

	public function get_email(): ?string {
		if ( $this->customer ) {
			return $this->customer->get_email();
		}

		if ( $this->form ) {
			return $this->form->get_email();
		}

		return null;
	}

}
