<?php

namespace WPDesk\GatewayWPPay\BlueMediaApi\Responses;

class RPANResponse {

	private const CONFIRMATION_SUCCESS = 'CONFIRMED';
	public function respond_with_failure( string $message = 'Unknown error', string $status = 'error' ): void {
		http_response_code( 400 );

		header( 'Content-Type: text/xml' );
		die(
			'<?xml version="1.0" encoding="UTF-8"?>
		        <response>
		            <status>' . $status . '</status>
		            <message>' . $message . '</message>
		        </response>'
		);
	}

	public function respond_with_success( string $service_id, string $client_hash, string $hash, string $confirmation = self::CONFIRMATION_SUCCESS ): void {
		http_response_code( 200 );

		header( 'Content-Type: text/xml' );
		die(
			'<?xml version="1.0" encoding="UTF-8"?>
				<confirmationList>
					<serviceID>' . $service_id . '</serviceID>
						<recurringConfirmations>
							<recurringConfirmed>
								<clientHash>' . $client_hash . '</clientHash>
								<confirmation>' . $confirmation . '</confirmation>
							</recurringConfirmed>
						</recurringConfirmations>
					<hash>' . $hash . '</hash>
				</confirmationList>'
		);
	}


}
