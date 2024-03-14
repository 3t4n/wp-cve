<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia;

use WPPayVendor\BlueMedia\Confirmation\Builder\ConfirmationVOBuilder;
use WPPayVendor\BlueMedia\Hash\HashableInterface;
use WPPayVendor\BlueMedia\HttpClient\HttpClientInterface;
use WPPayVendor\BlueMedia\Hash\HashChecker;
use WPPayVendor\BlueMedia\Itn\Builder\ItnVOBuilder;
use WPPayVendor\BlueMedia\Itn\Decoder\ItnDecoder;
use WPPayVendor\BlueMedia\Serializer\SerializableInterface;
use WPPayVendor\BlueMedia\Transaction\View;
use WPPayVendor\BlueMedia\Itn\ValueObject\Itn;
use WPPayVendor\BlueMedia\HttpClient\HttpClient;
use WPPayVendor\BlueMedia\Common\Enum\ClientEnum;
use WPPayVendor\BlueMedia\Itn\Builder\ItnDtoBuilder;
use WPPayVendor\BlueMedia\PaywayList\Dto\PaywayListDto;
use WPPayVendor\BlueMedia\Itn\Builder\ItnResponseBuilder;
use WPPayVendor\BlueMedia\HttpClient\ValueObject\Request;
use WPPayVendor\BlueMedia\HttpClient\ValueObject\Response;
use WPPayVendor\BlueMedia\Common\Builder\ServiceDtoBuilder;
use WPPayVendor\BlueMedia\Common\Parser\ServiceResponseParser;
use WPPayVendor\BlueMedia\Common\Util\EnvironmentRequirements;
use WPPayVendor\BlueMedia\RegulationList\Dto\RegulationListDto;
use WPPayVendor\BlueMedia\Transaction\Builder\TransactionDtoBuilder;
use WPPayVendor\BlueMedia\Transaction\Parser\TransactionResponseParser;
use WPPayVendor\BlueMedia\PaywayList\ValueObject\PaywayListResponse\PaywayListResponse;
use WPPayVendor\BlueMedia\RegulationList\ValueObject\RegulationListResponse\RegulationListResponse;
final class Client
{
    private const HEADER = 'BmHeader';
    private const PAY_HEADER = 'pay-bm';
    private const CONTINUE_HEADER = 'pay-bm-continue-transaction-url';
    /**
     * @var Configuration
     */
    private $configuration;
    /**
     * @var HttpClientInterface|null
     */
    private $httpClient;
    public function __construct(string $serviceId, string $sharedKey, string $hashMode = \WPPayVendor\BlueMedia\Common\Enum\ClientEnum::HASH_SHA256, string $hashSeparator = \WPPayVendor\BlueMedia\Common\Enum\ClientEnum::HASH_SEPARATOR, \WPPayVendor\BlueMedia\HttpClient\HttpClientInterface $httpClient = null)
    {
        \WPPayVendor\BlueMedia\Common\Util\EnvironmentRequirements::checkPhpEnvironment();
        $this->configuration = new \WPPayVendor\BlueMedia\Configuration($serviceId, $sharedKey, $hashMode, $hashSeparator);
        $this->httpClient = $httpClient ?? new \WPPayVendor\BlueMedia\HttpClient\HttpClient();
    }
    /**
     * Perform standard transaction.
     *
     * @param array $transactionData
     *
     * @return Response
     * @api
     */
    public function getTransactionRedirect(array $transactionData) : \WPPayVendor\BlueMedia\HttpClient\ValueObject\Response
    {
        return new \WPPayVendor\BlueMedia\HttpClient\ValueObject\Response(\WPPayVendor\BlueMedia\Transaction\View::createRedirectHtml(\WPPayVendor\BlueMedia\Transaction\Builder\TransactionDtoBuilder::build($transactionData, $this->configuration)));
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
    public function doTransactionBackground(array $transactionData) : \WPPayVendor\BlueMedia\HttpClient\ValueObject\Response
    {
        $transactionDto = \WPPayVendor\BlueMedia\Transaction\Builder\TransactionDtoBuilder::build($transactionData, $this->configuration);
        $transactionDto->setRequest(new \WPPayVendor\BlueMedia\HttpClient\ValueObject\Request($transactionDto->getGatewayUrl() . \WPPayVendor\BlueMedia\Common\Enum\ClientEnum::PAYMENT_ROUTE, [self::HEADER => self::PAY_HEADER]));
        $parser = new \WPPayVendor\BlueMedia\Transaction\Parser\TransactionResponseParser($this->httpClient->post($transactionDto), $this->configuration);
        return $parser->parse();
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
    public function doTransactionInit(array $transactionData) : \WPPayVendor\BlueMedia\HttpClient\ValueObject\Response
    {
        $transactionDto = \WPPayVendor\BlueMedia\Transaction\Builder\TransactionDtoBuilder::build($transactionData, $this->configuration);
        $transactionDto->setRequest(new \WPPayVendor\BlueMedia\HttpClient\ValueObject\Request($transactionDto->getGatewayUrl() . \WPPayVendor\BlueMedia\Common\Enum\ClientEnum::PAYMENT_ROUTE, [self::HEADER => self::CONTINUE_HEADER]));
        $parser = new \WPPayVendor\BlueMedia\Transaction\Parser\TransactionResponseParser($this->httpClient->post($transactionDto), $this->configuration);
        return $parser->parse(\true);
    }
    /**
     * Process ITN requests.
     *
     * @param string $itn encoded with base64
     * @return Response
     * @api
     */
    public function doItnIn(string $itn) : \WPPayVendor\BlueMedia\HttpClient\ValueObject\Response
    {
        $itnDto = \WPPayVendor\BlueMedia\Itn\Builder\ItnDtoBuilder::build(\WPPayVendor\BlueMedia\Itn\Decoder\ItnDecoder::decode($itn));
        return new \WPPayVendor\BlueMedia\HttpClient\ValueObject\Response($itnDto->getItn());
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
    public function doItnInResponse(\WPPayVendor\BlueMedia\Itn\ValueObject\Itn $itn, bool $transactionConfirmed = \true)
    {
        return new \WPPayVendor\BlueMedia\HttpClient\ValueObject\Response(\WPPayVendor\BlueMedia\Itn\Builder\ItnResponseBuilder::build($itn, $transactionConfirmed, $this->configuration));
    }
    /**
     * Returns payway list.
     *
     * @param string $gatewayUrl
     * @return Response
     * @api
     */
    public function getPaywayList(string $gatewayUrl) : \WPPayVendor\BlueMedia\HttpClient\ValueObject\Response
    {
        $paywayListDto = \WPPayVendor\BlueMedia\Common\Builder\ServiceDtoBuilder::build(['gatewayUrl' => $gatewayUrl, 'paywayList' => ['serviceID' => $this->configuration->getServiceId(), 'messageID' => \bin2hex(\random_bytes(\WPPayVendor\BlueMedia\Common\Enum\ClientEnum::MESSAGE_ID_LENGTH / 2))]], \WPPayVendor\BlueMedia\PaywayList\Dto\PaywayListDto::class, $this->configuration);
        $paywayListDto->setRequest(new \WPPayVendor\BlueMedia\HttpClient\ValueObject\Request($paywayListDto->getGatewayUrl() . \WPPayVendor\BlueMedia\Common\Enum\ClientEnum::PAYWAY_LIST_ROUTE));
        $parser = new \WPPayVendor\BlueMedia\Common\Parser\ServiceResponseParser($this->httpClient->post($paywayListDto), $this->configuration);
        return new \WPPayVendor\BlueMedia\HttpClient\ValueObject\Response($parser->parseListResponse(\WPPayVendor\BlueMedia\PaywayList\ValueObject\PaywayListResponse\PaywayListResponse::class));
    }
    /**
     * Returns payment regulations.
     *
     * @param string $gatewayUrl
     * @return Response
     * @api
     */
    public function getRegulationList(string $gatewayUrl) : \WPPayVendor\BlueMedia\HttpClient\ValueObject\Response
    {
        $regulationListDto = \WPPayVendor\BlueMedia\Common\Builder\ServiceDtoBuilder::build(['gatewayUrl' => $gatewayUrl, 'regulationList' => ['serviceID' => $this->configuration->getServiceId(), 'messageID' => \bin2hex(\random_bytes(\WPPayVendor\BlueMedia\Common\Enum\ClientEnum::MESSAGE_ID_LENGTH / 2))]], \WPPayVendor\BlueMedia\RegulationList\Dto\RegulationListDto::class, $this->configuration);
        $regulationListDto->setRequest(new \WPPayVendor\BlueMedia\HttpClient\ValueObject\Request($regulationListDto->getGatewayUrl() . \WPPayVendor\BlueMedia\Common\Enum\ClientEnum::GET_REGULATIONS_ROUTE));
        $parser = new \WPPayVendor\BlueMedia\Common\Parser\ServiceResponseParser($this->httpClient->post($regulationListDto), $this->configuration);
        return new \WPPayVendor\BlueMedia\HttpClient\ValueObject\Response($parser->parseListResponse(\WPPayVendor\BlueMedia\RegulationList\ValueObject\RegulationListResponse\RegulationListResponse::class));
    }
    /**
     * Checks id hash is valid.
     *
     * @param HashableInterface $data
     * @return bool
     * @api
     */
    public function checkHash(\WPPayVendor\BlueMedia\Hash\HashableInterface $data) : bool
    {
        return \WPPayVendor\BlueMedia\Hash\HashChecker::checkHash($data, $this->configuration);
    }
    /**
     * Method allows to check if gateway returns with valid data.
     *
     * @param array $data
     * @return bool
     * @api
     */
    public function doConfirmationCheck(array $data) : bool
    {
        return $this->checkHash(\WPPayVendor\BlueMedia\Confirmation\Builder\ConfirmationVOBuilder::build($data));
    }
    /**
     * Method allows to get Itn object from base64
     *
     * @param string $itn
     * @return Itn
     */
    public static function getItnObject(string $itn) : \WPPayVendor\BlueMedia\Itn\ValueObject\Itn
    {
        return \WPPayVendor\BlueMedia\Itn\Builder\ItnVOBuilder::build(\WPPayVendor\BlueMedia\Itn\Decoder\ItnDecoder::decode($itn));
    }
}
