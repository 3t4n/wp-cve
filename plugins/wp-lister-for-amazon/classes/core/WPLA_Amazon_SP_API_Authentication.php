<?php

use WPLab\GuzzeHttp\Client;
use WPLab\GuzzeHttp\Psr7;
use SellingPartnerApi\Api\TokensV20210301Api as TokensApi;
use SellingPartnerApi\Model\TokensV20210301 as Tokens;
use SellingPartnerApi\AuthorizationSigner;
use SellingPartnerApi\Configuration;
use SellingPartnerApi\Credentials;

class WPLA_Amazon_SP_API_Authentication extends SellingPartnerApi\Authentication {
    private $awsCredentials;

    private $lwaClientId;
    private $lwaClientSecret;
    private $lwaRefreshToken = null;
    private $lwaAuthUrl      = null;
    private $endpoint;

    private $onUpdateCreds;
    private $roleArn;

    private $signingScope = null;

    /** @var \WPLab\GuzzeHttp\ClientInterface */
    private $client = null;

    private $grantlessAwsCredentials = null;
    private $grantlessCredentialsScope = null;
    private $roleCredentials = null;
    private $restrictedDataTokens = [];

    /**
     * @var string
     */
    private $awsAccessKeyId;
    /**
     * @var string
     */
    private $awsSecretAccessKey;

    private $awsToken;

    /** @var \SellingPartnerApi\Api\TokensV20210301Api */
    private $tokensApi = null;

    /** @var AuthorizationSignerContract */
    private $authorizationSigner;

    /**
     * Authentication constructor.
     * @param array $configurationOptions
     * @throws RuntimeException
     */
    public function __construct(array $configurationOptions)
    {
        $this->client = $configurationOptions['authenticationClient'] ?? new Client();

        $this->lwaAuthUrl = $configurationOptions['lwaAuthUrl'] ?? "https://api.amazon.com/auth/o2/token";
        $this->lwaRefreshToken = $configurationOptions['lwaRefreshToken'] ?? null;
        $this->onUpdateCreds = $configurationOptions['onUpdateCredentials'] ?? null;
        $this->lwaClientId = $configurationOptions['lwaClientId'];
        $this->lwaClientSecret = $configurationOptions['lwaClientSecret'];
        $this->endpoint = $configurationOptions['endpoint'];

        $accessToken = $configurationOptions['accessToken'] ?? null;
        $accessTokenExpiration = $configurationOptions['accessTokenExpiration'] ?? null;

        $this->awsAccessKeyId = $configurationOptions['awsAccessKeyId'];
        $this->awsSecretAccessKey = $configurationOptions['awsSecretAccessKey'];

        $this->roleArn = $configurationOptions['roleArn'] ?? null;

        if ($accessToken !== null && $accessTokenExpiration !== null) {
            $this->populateCredentials($this->awsAccessKeyId, $this->awsSecretAccessKey, $accessToken, $accessTokenExpiration);
        }

        $this->tokensApi = $configurationOptions['tokensApi'] ?? null;

        $this->authorizationSigner = $configurationOptions['authorizationSigner'] ?? new AuthorizationSigner($this->endpoint);

        //parent::__construct( $configurationOptions );
    }

    /**
     * Get a restricted data token for the operation corresponding to $path and $method.
     *
     * @param string $path The generic or specific path for the restricted operation
     * @param string $method The HTTP method of the restricted operation
     * @param ?array $dataElements The restricted data elements to request access to, if any.
     *      Only applies to getOrder, getOrders, and getOrderItems. Default empty array.
     * @return \SellingPartnerApi\Credentials A Credentials object holding the RDT
     */
    public function getRestrictedDataToken(string $path, string $method, ?array $dataElements = []): Credentials
    {
        $standardCredentials = $this->getAwsCredentials();
        $tokensApi = $this->tokensApi;
        if (is_null($tokensApi)) {
            // Use our own Request Signer since we don't sign requests here
            $config = new Configuration([
                "lwaClientId" => $this->lwaClientId,
                "lwaClientSecret" => $this->lwaClientSecret,
                "lwaRefreshToken" => $this->lwaRefreshToken,
                "lwaAuthUrl" => $this->lwaAuthUrl,
                "awsAccessKeyId" => $this->awsAccessKeyId,
                "awsSecretAccessKey" => $this->awsSecretAccessKey,
                "accessToken" => $standardCredentials->getSecurityToken(),
                "accessTokenExpiration" => $standardCredentials->getExpiration(),
                "roleArn" => $this->roleArn,
                "endpoint" => $this->endpoint,
                'requestSigner' => $this

            ]);
            $tokensApi = new TokensApi($config, $this->client);
        }

        $restrictedResource = new Tokens\RestrictedResource([
            "method" => $method,
            "path" => $path,
        ]);
        if ($dataElements !== []) {
            $restrictedResource->setDataElements($dataElements);
        }

        $body = new Tokens\CreateRestrictedDataTokenRequest([
            "restricted_resources" => [$restrictedResource],
        ]);
        $rdtData = $tokensApi->createRestrictedDataToken($body);

        $rdtCreds = new Credentials(
            $this->awsAccessKeyId,
            $this->awsSecretAccessKey,
            $rdtData->getRestrictedDataToken(),
            time() + intval($rdtData->getExpiresIn())
        );

        return $rdtCreds;
    }

    /**
     * @return array
     * @throws \WPLab\GuzzeHttp\Exception\GuzzleException|\RuntimeException
     */
    public function requestLWAToken(): array
    {
        $jsonData = [
            "grant_type" => $this->signingScope ? "client_credentials" : "refresh_token",
            "client_id" => $this->lwaClientId,
            "client_secret" => $this->lwaClientSecret,
        ];

        // Only pass one of `scope` and `refresh_token`
        // https://github.com/amzn/selling-partner-api-docs/blob/main/guides/developer-guide/SellingPartnerApiDeveloperGuide.md#step-1-request-a-login-with-amazon-access-token
        if ($this->signingScope) {
            $jsonData["scope"] = $this->signingScope;
        } else {
            if ($this->lwaRefreshToken === null) {
                throw new RuntimeException('lwaRefreshToken must be specified when calling non-grantless API operations');
            }
            $jsonData["refresh_token"] = $this->lwaRefreshToken;
        }

        $lwaTokenRequestHeaders = [
            'Content-Type' => 'application/json',
        ];
        $lwaTokenRequestBody = json_encode($jsonData);
        $lwaTokenRequest = new Psr7\Request('POST', $this->lwaAuthUrl, $lwaTokenRequestHeaders, $lwaTokenRequestBody);
        $res = $this->client->send($lwaTokenRequest);

        $body = json_decode($res->getBody(), true);

        if ( !empty( $body['error'] ) ) {
            WPLA()->logger->error( 'Error fetching an LWA token: '. $body['error_description'] .' ('. $body['error'] .')' );
            return [false,false];
        } else {
            $accessToken = $body["access_token"];
            //$awsToken = $body['aws_token'];
            $expirationDate = new \DateTime("now", new \DateTimeZone("UTC"));
            $expirationDate->add(new \DateInterval("PT" . strval($body["expires_in"]) . "S"));
            return [$accessToken, $expirationDate->getTimestamp()];
        }

    }

    public function populateCredentials($key, $secret, ?string $token = null, ?int $expires = null): void
    {
        $creds = null;
        if ($token !== null && $expires !== null) {
            $creds = new Credentials($key, $secret, $token, $expires);
        } else {
            $creds = new Credentials($key, $secret);
        }

        if ($this->signingScope) {
            $this->grantlessAwsCredentials = $creds;
        } else {
            $this->awsCredentials = $creds;
        }
    }

    private function newToken(): void
    {
        [$accessToken, $expirationTimestamp] = $this->requestLWAToken();

        if ( !$accessToken ) {
            if ( !is_ajax() && is_admin() ) {
                wpla_show_message( 'There was an error fetching a token for this account. Please check the error logs for details.', 'error' );
            }

            return;
        }

        $this->populateCredentials($this->awsAccessKeyId, $this->awsSecretAccessKey, $accessToken, $expirationTimestamp);
        if (!$this->signingScope && $this->onUpdateCreds !== null) {
            call_user_func($this->onUpdateCreds, $this->awsCredentials );
        }
    }

    /**
     * ToDo: Find a way to override the need to supply AWS credentials
     * @return \SellingPartnerApi\Credentials
     */
//    public function getAwsCredentials(): \SellingPartnerApi\Credentials {
//        if ($this->needNewCredentials($this->awsCredentials)) {
//            $this->newToken();
//        }
//        return $this->awsCredentials;
//    }

//
//    private function needNewCredentials( $awsCredentials ) {
//        return $this->awsCredentials;
//    }

    public function setRequestTime(?\DateTime $datetime = null): void
    {
        $this->requestTime = $datetime ?? new \DateTime('now', new \DateTimeZone('UTC'));
    }

    /**
     * Requests will be signed by the proxy server
     *
     * @param \Guzzle\Psr7\Request $request The request to sign
     * @param ?string $scope If the request is to a grantless operation endpoint, the scope for the grantless token
     * @param ?string $restrictedPath The absolute (generic) path for the endpoint that the request is using if it's an endpoint that requires
     *      a restricted data token
     * @return Psr7\Request The signed request
     */
    public function signRequest(Psr7\Request $request, ?string $scope = null, ?string $restrictedPath = null, ?string $operation = null): Psr7\Request {
        // This allows us to know if we're signing a grantless operation without passing $scope all over the place
        $this->signingScope = $scope;

        // Check if the relevant AWS creds haven't been fetched or are expiring soon
        $relevantCreds = null;
        $params = [];

        parse_str($request->getUri()->getQuery(), $params);
        $dataElements = [];
        if (isset($params['dataElements'])) {
            $dataElements = explode(',', $params['dataElements']);
        }

        if (!$this->signingScope && ($restrictedPath === null || $dataElements === [])) {
            $relevantCreds = $this->getAwsCredentials();
        } else if ($this->signingScope) {  // There is no overlap between grantless and restricted operations
            $relevantCreds = $this->getGrantlessAwsCredentials($scope);
        } else if ($restrictedPath !== null) {
            $needRdt = true;

            // Not all getReportDocument calls need an RDT
            if ($operation === 'getReportDocument') {
                // We added a reportType query parameter that isn't in the official models, so that we can
                // determine if the getReportDocument call requires an RDT
                $constantPath = isset($params['reportType']) ? 'SellingPartnerApi\ReportType::' . $params['reportType'] : null;

                if ($constantPath === null || !defined($constantPath) || !constant($constantPath)['restricted']) {
                    $needRdt = false;
                    $relevantCreds = $this->getAwsCredentials();
                }

                // Remove the extra 'reportType' query parameter
                $newUri = Psr7\Uri::withoutQueryValue($request->getUri(), 'reportType');
                $request = $request->withUri($newUri);
            } else if (isset($params['dataElements'])) {
                // Remove the extra 'dataElements' query parameter
                $newUri = Psr7\Uri::withoutQueryValue($request->getUri(), 'dataElements');
                $request = $request->withUri($newUri);
            }

            if ($needRdt) {
                $relevantCreds = $this->getRestrictedDataToken($restrictedPath, $request->getMethod(), $dataElements);
            }
        }

        $accessToken = $relevantCreds->getSecurityToken();

//        if ($this->roleArn !== null) {
//            $relevantCreds = $this->getRoleCredentials();
//        }

        $this->authorizationSigner->setRequestTime();
        $signedRequest = $request->withHeader('x-amz-access-token', $accessToken);

        if ($this->roleArn) {
            $signedRequest = $signedRequest->withHeader("x-amz-security-token", $this->awsToken);
        }

        $this->signingScope = null;

        return $signedRequest;
    }

    /**
     * Get credentials for standard API operations.
     *
     * @return \SellingPartnerApi\Credentials A set of access credentials for making calls to the SP API
     */
    public function getAwsCredentials(): Credentials
    {
        if ($this->needNewCredentials($this->awsCredentials)) {
            $this->newToken();
        }
        return $this->awsCredentials;
    }

    /**
     * Check if the given credentials need to be created/renewed.
     *
     * @param ?\SellingPartnerApi\Credentials $creds The credentials to check
     * @return bool True if the credentials need to be updated, false otherwise
     */
    private function needNewCredentials(?Credentials $creds = null): bool
    {
        return $creds === null || $creds->getSecurityToken() === null || $creds->expiresSoon();
    }

}