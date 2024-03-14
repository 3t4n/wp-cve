<?php

namespace WPDesk\GatewayWPPay\BlueMediaApi;


use BlueMedia\OnlinePayments\Model\TransactionInit;
use WPDesk\GatewayWPPay\BlueMediaApi\Dto\AbstractDto;
use WPDesk\GatewayWPPay\BlueMediaApi\Dto\TransactionError;
use WPDesk\GatewayWPPay\BlueMediaApi\Dto\TransactionRefund;
use WPDesk\GatewayWPPay\BlueMediaApi\Dto\TransactionRefundResponse;
use WPDesk\GatewayWPPay\BlueMediaApi\Exception\TransactionErrorException;
use WPPayVendor\BlueMedia\Client;
use WPPayVendor\BlueMedia\HttpClient\ValueObject\Response;
use WPPayVendor\BlueMedia\Itn\ValueObject\Itn;
use WPPayVendor\BlueMedia\Serializer\SerializableInterface;

class BlueMediaClient {
	/** @var Client */
	private $bm_vendor_client;

	/** @var string */
	private $serviceId;

	/** @var string */
	private $sharedKey;

	/** @var string */
	private $hashMode;

	/** @var string */
	private $hashSeparator;

	public function __construct(
		string $serviceId,
		string $sharedKey,
		string $hashMode = \WPPayVendor\BlueMedia\Common\Enum\ClientEnum::HASH_SHA256,
		string $hashSeparator = \WPPayVendor\BlueMedia\Common\Enum\ClientEnum::HASH_SEPARATOR,
		\WPPayVendor\BlueMedia\HttpClient\HttpClientInterface $httpClient = null
	) {
		$this->serviceId     = $serviceId;
		$this->sharedKey     = $sharedKey;
		$this->hashMode      = $hashMode;
		$this->hashSeparator = $hashSeparator;

		$this->bm_vendor_client = new Client( $serviceId, $sharedKey, $hashMode, $hashSeparator );
	}

	protected function get_api_address(): string {
		return 'https://pay.bm.pl';
	}

	/**
	 * Method allows to check if gateway returns with valid data.
	 *
	 * @param array $data
	 *
	 * @return bool
	 * @api
	 */
	public function doConfirmationCheck( array $data ): bool {
		return $this->bm_vendor_client->doConfirmationCheck( $data );
	}

	/**
	 * Perform transaction in background.
	 * Returns payway form or transaction data for user.
	 *
	 * @param array $transactionData
	 *
	 * @return Response
	 * @api
	 */
	public function doTransactionBackground( array $transactionData ): Response {
		return $this->bm_vendor_client->doTransactionBackground( $transactionData );
	}

	private function calculateHash( AbstractDto $dto ): string {
		return $this->hashTransactionParameters( $dto->capitalizedArray() );
	}

	private function sendApiRequest( AbstractDto $dto, string $endpoint, array $additionalHeaders = [] ): array {
		if ( method_exists( $dto, 'set_service_ID' ) ) {
			$dto->set_service_ID( $this->serviceId );
		}

		$transactionData         = $dto->capitalizedArray();
		$transactionData['Hash'] = $this->calculateHash( $dto );
		$fields                  = http_build_query( $transactionData );

		$url  = "{$this->get_api_address()}/{$endpoint}";
		$curl = curl_init( $url );
		curl_setopt( $curl, CURLOPT_POSTFIELDS, $fields );
		curl_setopt( $curl, CURLOPT_POST, 1 );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, true );
		if ( ! empty( $additionalHeaders ) ) {
			curl_setopt( $curl, CURLOPT_HTTPHEADER, $additionalHeaders );
		}

		$curlResponse = curl_exec( $curl );
		$code         = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
		$response     = curl_getinfo( $curl );
		curl_close( $curl );
		$responseArray = json_decode( json_encode( (array) simplexml_load_string( $curlResponse ) ), true );
		if ( ! empty( $responseArray['statusCode'] ) ) {
			throw new TransactionErrorException(
				sprintf( __( "The API responded with an error message: %s", 'pay-wp' ), $responseArray['description'] ),
				new TransactionError(
					$responseArray['statusCode'],
					$responseArray['name'] ?? '',
					$responseArray['description']
				) );
		}

		return $responseArray;
	}

	public function doRefund( TransactionRefund $refund ): TransactionRefundResponse {
		$responseArray = $this->sendApiRequest( $refund, 'transactionRefund' );

		return new TransactionRefundResponse(
			$responseArray['serviceID'],
			$responseArray['messageID'],
			$responseArray['remoteOutID'],
			$responseArray['hash']
		);
	}

	/**
	 * Perform standard transaction.
	 *
	 * @param array $transactionData
	 *
	 * @return Response
	 * @api
	 */
	public function getTransactionRedirect( array $transactionData
	): Response {

		return $this->bm_vendor_client->getTransactionRedirect( [
			'gatewayUrl'  => $this->get_api_address(),
			'transaction' => $transactionData,
		] );
	}

	/**
	 * Initialize transaction.
	 * Returns transaction continuation or transaction information.
	 *
	 * @param array $transactionData
	 *
	 * @return Response
	 * @api
	 */
	public function doTransactionInit( array $transactionData ): Response {
		return $this->bm_vendor_client->doTransactionInit( [
			'gatewayUrl'  => $this->get_api_address(),
			'transaction' => $transactionData,
		] );

	}

	public function do_recurring_transaction_init( array $transaction_data ) {

		$transaction_data = array_merge(['ServiceID' => $this->serviceId], $transaction_data);
		$hash = $this->hashTransactionParameters( $transaction_data );
		$transaction_data['Hash'] = $hash;
		$data = http_build_query( $transaction_data );

		$url  = "{$this->get_api_address()}/payment";

		$response = wp_remote_post($url, [
			'body' => $data,
			'headers' => [
				'BmHeader' => 'pay-bm-continue-transaction-url',
			],
			]);

		$responseArray = json_decode( json_encode( (array) simplexml_load_string( $response['body'] ) ), true );

		return new \WPPayVendor\BlueMedia\HttpClient\ValueObject\Response( $responseArray );
	}

	public function doTransctionInitWithDto( TransactionInit $dto ) {

		$dto['ServiceID']              = $this->serviceId;
		$transactionData['CustomerIP'] = $_SERVER['REMOTE_ADDR'];
		$transactionData               = array_merge( $transactionData, [ 'Hash' => $this->hashTransactionParameters( $transactionData ) ] );

		$fields = ( is_array( $transactionData ) ) ? http_build_query( $transactionData ) : $transactionData;

		$url  = "{$this->get_api_address()}/payment";
		$curl = curl_init( $url );
		curl_setopt( $curl, CURLOPT_HTTPHEADER, array( 'BmHeader: pay-bm-continue-transaction-url' ) );
		curl_setopt( $curl, CURLOPT_POSTFIELDS, $fields );
		curl_setopt( $curl, CURLOPT_POST, 1 );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, true );
		$curlResponse = curl_exec( $curl );
		$code         = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
		$response     = curl_getinfo( $curl );
		curl_close( $curl );
		$responseArray = json_decode( json_encode( (array) simplexml_load_string( $curlResponse ) ), true );

		return new \WPPayVendor\BlueMedia\HttpClient\ValueObject( $responseArray );
	}

	/**
	 * Process ITN requests.
	 *
	 * @param string $itn encoded with base64
	 *
	 * @return Response
	 * @api
	 */
	public function doItnIn( string $itn ): Response {
		return $this->bm_vendor_client->doItnIn( $itn );
	}

	/**
	 * Returns response for ITN IN request.
	 *
	 * @param Itn $itn
	 * @param bool $transactionConfirmed
	 *
	 * @return Response
	 * @api
	 *
	 */
	public function doItnInResponse( Itn $itn, bool $transactionConfirmed = \true ) {
		return $this->bm_vendor_client->doItnInResponse( $itn, $transactionConfirmed );
	}

	/**
	 * @param array $params
	 *
	 * @return string
	 */
	public function hashTransactionParameters( array $params ): string {
		$toHash = implode( $this->hashSeparator, $params ) . '|' . $this->sharedKey;

		return hash( $this->hashMode, $toHash );
	}

	/**
	 * Returns payway list.
	 *
	 * @param string $gatewayUrl
	 *
	 * @return Response
	 * @api
	 */
	public function getPaywayList( array $currencyList = [ 'PLN' ] ): Response {
		$gatewayUrl    = $this->get_api_address();
		$transientKey  = 'bluemedia_payway_list';
		$transientTime = 60 * 2;
		$result        = get_transient( $transientKey );
		if ( empty( $result ) ) {
			$params = [
				'ServiceID'  => $this->serviceId,
				'MessageID'  => substr( bin2hex( random_bytes( 32 ) ), 32 ),
				'Currencies' => implode( ',', $currencyList ),
			];

			$params = array_merge( $params, [ 'Hash' => $this->hashTransactionParameters( $params ) ] );

			$result = wp_remote_post(
				"{$gatewayUrl}/gatewayList",
				[
					'headers' => [
						'content-type' => 'application/json',
					],
					'body'    => json_encode( $params ),
				]
			);
			$result = json_decode( wp_remote_retrieve_body( $result ), true );
			if ( ! empty( $result['gatewayList'] ) ) {
				set_transient( $transientKey, $result, $transientTime );
			}
		}

		return new Response( $result['gatewayList'] );
	}

	/**
	 * Returns payment regulations.
	 *
	 * @param string $gatewayUrl
	 *
	 * @return Response
	 * @api
	 */
	public function getRegulationList( string $gatewayUrl ): Response {
		return $this->bm_vendor_client->getRegulationList( $gatewayUrl );
	}

	/**
	 * Checks id hash is valid.
	 *
	 * @param SerializableInterface $data
	 *
	 * @return bool
	 * @api
	 */
	public function checkHash( SerializableInterface $data ): bool {
		return $this->bm_vendor_client->checkHash( $data );
	}

	/**
	 * Method allows to get Itn object from base64
	 *
	 * @param string $itn
	 *
	 * @return Itn
	 */
	public static function getItnObject( string $itn ): Itn {
		return Client::getItnObject( $itn );
	}

	public function get_service_id(): string {
		return $this->serviceId;
	}

	public function get_shared_key(): string {
		return $this->sharedKey;
	}
}
