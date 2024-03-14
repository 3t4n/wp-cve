<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Controller;

use WPDesk\ShopMagic\Components\Collections\ArrayCollection;
use WPDesk\ShopMagic\Exception\AutomationNotFound;
use WPDesk\ShopMagic\Marketing\MailTracking\EmailStatsCounter;
use WPDesk\ShopMagic\Marketing\MailTracking\TrackedClickRepository;
use WPDesk\ShopMagic\Marketing\MailTracking\TrackedEmail;
use WPDesk\ShopMagic\Marketing\MailTracking\TrackedEmailHydrator;
use WPDesk\ShopMagic\Marketing\MailTracking\TrackedEmailRepository;
use WPDesk\ShopMagic\Workflow\Automation\AutomationRepository;

class MailTrackingController {

	/** @var TrackedEmailRepository */
	private $repository;

	/** @var TrackedClickRepository */
	private $click_repository;

	public function __construct(
		TrackedEmailRepository $repository,
		TrackedClickRepository $click_repository
	) {
		$this->repository       = $repository;
		$this->click_repository = $click_repository;
	}

	public function index( TrackedEmailHydrator $normalizer ): \WP_REST_Response {
		$result = $this->repository
			->find_all()
			->map( function ( TrackedEmail $tracked_email ) {
				$click_collection = $this->click_repository->find_by( [ 'message_id' => $tracked_email->get_message_id() ] );
				foreach ( $click_collection as $click ) {
					$tracked_email->append_click( $click );
				}

				return $tracked_email;
			} )
			->map( \Closure::fromCallable( [ $normalizer, 'normalize' ] ) )
			->to_array();

		return new \WP_REST_Response( $result );
	}

	public function per_automation(
		AutomationRepository $repository,
		\WP_REST_Request $request
	): \WP_REST_Response {
		$page      = $request->get_param( 'page' );
		$page_size = $request->get_param( 'pageSize' );

		return new \WP_REST_Response(
			$this->repository->find_all()
			                 ->filter( function ( TrackedEmail $email ) {
				                 return $email->get_automation_id() !== 0;
			                 } )
			                 ->reduce( function ( ArrayCollection $carry, TrackedEmail $email ): ArrayCollection {
				                 if ( ! isset( $carry[ $email->get_automation_id() ] ) ) {
					                 $carry[ $email->get_automation_id() ] = new EmailStatsCounter();
				                 }

				                 $per_automation = $carry[ $email->get_automation_id() ];
				                 $per_automation->increase_sent();
				                 if ( $email->get_opened_at() !== null ) {
					                 $per_automation->increase_open();
				                 }

				                 if ( $email->get_clicked_at() !== null ) {
					                 $per_automation->increase_click();
				                 }

				                 $carry[ $email->get_automation_id() ] = $per_automation;

				                 return $carry;
			                 }, new ArrayCollection() )
			                 ->slice( ( $page - 1 ) * $page_size, $page_size )
			                 ->map( function ( EmailStatsCounter $counter, $automation_id ) use ( $repository ) {
				                 try {
					                 $automation_object = $repository->find( $automation_id );
					                 $automation_name   = $automation_object->get_name();
				                 } catch ( AutomationNotFound $e ) {
					                 $automation_name = null;
				                 }

				                 return [
					                 "automation" => [
						                 "id"   => $automation_id,
						                 'name' => $automation_name,
					                 ],
					                 "count"      => $counter->get_sent(),
					                 "openRate"   => $counter->get_open_rate(),
					                 "clickRate"  => $counter->get_click_rate(),
				                 ];
			                 } )
			                 ->to_array()
		);
	}

	public function per_customer( \WP_REST_Request $request ): \WP_REST_Response {
		$page      = $request->get_param( 'page' );
		$page_size = $request->get_param( 'pageSize' );

		return new \WP_REST_Response(
			$this->repository->find_all()
			                 ->reduce( function ( ArrayCollection $carry, TrackedEmail $email ) {
				                 if ( ! isset( $carry[ $email->get_recipient_email() ] ) ) {
					                 $carry[ $email->get_recipient_email() ] = new EmailStatsCounter();
				                 }

				                 $per_customer = $carry[ $email->get_recipient_email() ];
				                 $per_customer->increase_sent();
				                 if ( $email->get_opened_at() !== null ) {
					                 $per_customer->increase_open();
				                 }

				                 if ( $email->get_clicked_at() !== null ) {
					                 $per_customer->increase_click();
				                 }

				                 $carry[ $email->get_recipient_email() ] = $per_customer;

				                 return $carry;
			                 }, new ArrayCollection() )
			                 ->slice( ( $page - 1 ) * $page_size, $page_size )
			                 ->map( function ( EmailStatsCounter $counter, string $customer_email ) {
				                 return [
					                 "customer"  => [
						                 "email" => $customer_email,
					                 ],
					                 "count"     => $counter->get_sent(),
					                 "openRate"  => $counter->get_open_rate(),
					                 "clickRate" => $counter->get_click_rate(),
				                 ];
			                 } )
			                 ->to_array()
		);
	}
}
