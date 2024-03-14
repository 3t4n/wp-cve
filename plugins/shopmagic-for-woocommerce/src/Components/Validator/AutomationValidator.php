<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Validator;

use WPDesk\ShopMagic\Components\Form\Form;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Event\EventsList;

class AutomationValidator {

	/** @var EventsList */
	private $events;

	public function __construct( EventsList $events ) {
		$this->events = $events;
	}

	public function validate( Automation $automation ): bool {
		$event_slug = $automation->get_event()->get_id();
		$event      = $this->events->offsetGet( $event_slug );
		$form       = new Form( $event->get_fields() );
		$form->set_data( $event->get_parameters() );

		return $form->is_valid();
	}

}
