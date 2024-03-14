<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\HookProviders;

use WPDesk\ShopMagic\Admin\Settings\GeneralSettings;
use WPDesk\ShopMagic\Components\HookProvider\Conditional;
use WPDesk\ShopMagic\Components\HookProvider\HookProvider;
use WPDesk\ShopMagic\Marketing\MailTracking\TrackedEmailHydrator;
use WPDesk\ShopMagic\Marketing\MailTracking\TrackedEmailObjectManager;
use WPDesk\ShopMagic\Marketing\MailTracking\TrackingInjection;
use WPDesk\ShopMagic\Workflow\Action\Builtin\SendMail\EmailSending;

class RecordEmailSending implements HookProvider, Conditional {

	/** @var TrackedEmailHydrator */
	private $factory;

	/** @var TrackedEmailObjectManager */
	private $manager;
	/**
	 * @var TrackingInjection
	 */
	private $tracking_injection;

	public function __construct(
		TrackedEmailHydrator $factory,
		TrackedEmailObjectManager $manager,
		TrackingInjection $tracking_injection
	) {
		$this->factory            = $factory;
		$this->manager            = $manager;
		$this->tracking_injection = $tracking_injection;
	}

	public function hooks(): void {
		add_filter(
			'shopmagic/core/action/send_mail/sending',
			function ( EmailSending $event ): EmailSending {
				return $this->record_email_sending( $event );
			}
		);
	}

	private function record_email_sending( EmailSending $event ): EmailSending {
		$tracked_email = $this->factory->fresh(
			$event->get_email(),
			$event->get_automation(),
			$event->get_customer()
		);

		$this->manager->save( $tracked_email );

		$email = $event->get_email();
		if ( $email->is_html() ) {
			$email = $this->tracking_injection->inject_tracking_pixel(
				$tracked_email,
				$event->get_email()
			);
		}
		$email = $this->tracking_injection->inject_link_tracker( $tracked_email, $email );

		return $event->with_email( $email );
	}

	/** This extension is enabled by default, thus we check if it's not disabled. */
	public static function is_needed(): bool {
		return ! in_array( GeneralSettings::get_option( 'enable_email_tracking' ), [ '', false ] );
	}
}
