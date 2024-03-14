<?php

namespace WcMipConnector\Controller;

defined('ABSPATH') || exit;

require __DIR__.'/../../vendor/autoload.php';

use WcMipConnector\Enum\MessageType;
use WcMipConnector\Enum\OperationType;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Factory\OrderFactory;
use WcMipConnector\Factory\ProductImageUrlFactory;
use WcMipConnector\Factory\ProductUrlFactory;
use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Manager\FileLogManager;
use WcMipConnector\Manager\ImportProcessProductManager;
use WcMipConnector\Manager\LanguageReportManager;
use WcMipConnector\Manager\OrderLogManager;
use WcMipConnector\Manager\ProductImageUrlManager;
use WcMipConnector\Service\AccountInfoReportService;
use WcMipConnector\Service\ActionSchedulerCleanerService;
use WcMipConnector\Service\CategoryImageDeleteService;
use WcMipConnector\Service\CategoryService;
use WcMipConnector\Service\DirectoryService;
use WcMipConnector\Service\FileService;
use WcMipConnector\Service\HealthReportService;
use WcMipConnector\Service\LanguageReportService;
use WcMipConnector\Service\LoggerService;
use WcMipConnector\Service\OrderService;
use WcMipConnector\Service\ProductService;
use WcMipConnector\Service\ProductUrlService;
use WcMipConnector\Service\ResponseService;
use WcMipConnector\Service\SecurityService;
use WcMipConnector\Service\StatusReportService;
use WcMipConnector\Service\StockService;
use WcMipConnector\Service\SystemService;
use WcMipConnector\Service\TaxesService;

class ApiController
{
    private const STATUS_SUCCESS = 'SUCCESS';
    private const STATUS_FAIL = 'FAILED';
    private const UPDATE_PRODUCT_URLS_CRON_MINUTE = 15;
    private const UPDATE_PRODUCT_URLS_CRON_HOUR = 00;

    public const MIP_ROUTE = 'mip-route';
    public const CRON_ROUTE = 'cron-route';

    public const ROOT_ENDPOINT = 'wcmipconnector';
    public const API_ENDPOINT = self::ROOT_ENDPOINT.'/api';

    private $messageType;
    private $operationType;
    private $message;
    private $version;

    /** @var OrderLogManager */
    private $orderLogManager;
    /** @var OrderService */
    private $orderService;
    /** @var SystemService */
    private $systemService;
    /** @var LanguageReportService */
    private $languageReportService;
    /** @var LoggerService */
    private $logger;
    /** @var FileService */
    private $fileService;
    /** @var ImportProcessProductManager */
    private $importProcessProduct;
    /** @var ProductService */
    private $productService;
    /** @var FileLogManager */
    private $fileLogManager;
    /** @var TaxesService */
    private $taxesService;
    /** @var CategoryService */
    private $categoryService;
    /** @var DirectoryService */
    private $directoryService;
    /** @var ProductUrlService */
    private $productUrlService;
    /** @var ProductImageUrlManager */
    private $productImageUrlManager;
    /** @var ProductUrlFactory */
    private $productUrlFactory;
    /** @var ProductImageUrlFactory */
    private $productImageUrlFactory;
    /** @var SecurityService */
    private $securityService;
    /** @var ResponseService */
    private $responseService;
    /** @var LoggerController */
    private $loggerController;
    /** @var ActionSchedulerCleanerService  */
    private $actionSchedulerCleanerService;
    /** @var CategoryImageDeleteService  */
    private $categoryImageDeleteService;

    public function __construct()
    {
        $this->orderLogManager = new OrderLogManager();
        $this->orderService = new OrderService();
        $this->systemService = new SystemService();
        $this->languageReportService = new LanguageReportService();
        $loggerService = new LoggerService();
        $this->logger = $loggerService->getInstance();
        $this->fileService = new FileService();
        $this->importProcessProduct = new ImportProcessProductManager();
        $this->productService = new ProductService();
        $this->fileLogManager = new FileLogManager();
        $this->categoryService = new CategoryService();
        $this->taxesService = new TaxesService();
        $this->directoryService = new DirectoryService();
        $this->productUrlService = new ProductUrlService();
        $this->productImageUrlManager = new ProductImageUrlManager();
        $this->productUrlFactory = new ProductUrlFactory();
        $this->productImageUrlFactory = new ProductImageUrlFactory();
        $this->securityService = new SecurityService();
        $this->responseService = new ResponseService();
        $this->loggerController = new LoggerController();
        $this->actionSchedulerCleanerService = new ActionSchedulerCleanerService();
        $this->categoryImageDeleteService = new CategoryImageDeleteService();
    }

    public function addActions(): void
    {
        add_filter('query_vars', [$this, 'addQueryVars'], 0);
        add_action('parse_request', [$this, 'processAction'], 0);
    }

    /**
     * @param array $vars
     * @return array
     */
    public function addQueryVars(array $vars): array
    {
        $vars[] = self::MIP_ROUTE;
        $vars[] = self::CRON_ROUTE;

        return $vars;
    }

    public function addEndpoints(): void
    {
        add_rewrite_rule('^'.self::API_ENDPOINT.'/(.*)?', 'index.php?'.self::MIP_ROUTE.'=$matches[1]', 'top');
        add_rewrite_rule('^'.self::API_ENDPOINT.'?', 'index.php', 'top' );
        add_rewrite_rule('^'.self::ROOT_ENDPOINT.'/(.*)?', 'index.php?'.self::CRON_ROUTE.'=$matches[1]', 'top' );
        flush_rewrite_rules(false);
    }

    public function processAction(): void
    {
        global $wp;

        if (!$this->canHandleRequest($wp->request)) {
            return;
        }

        if (OAuthController::canHandleRequest($wp->query_vars)) {
            $oauthController = new OAuthController();
            $oauthController->handleAuthRequests();
        }

        if (OrderController::canHandleRequest($wp->query_vars)) {
            $controller = new OrderController();
            $controller->processAction();
        }

        if (FileController::canHandleRequest($wp->query_vars)) {
            $fileController = new FileController();
            $synchronizationProcessPID = getmypid();

            if ($synchronizationProcessPID !== false) {
                $this->logger->alert('Synchronization process started with PID: '.$synchronizationProcessPID);
            }

            try {
                $this->orderService->handleUnmappedOrders();
            } catch (\Throwable $e) {
                $this->logger->error('Exception error in checkIfOrderHasBeenNotMapped: '. $e->getMessage());
            }

            if (!$fileController->executeSynchronization()) {
                if ($synchronizationProcessPID !== false) {
                    $this->logger->alert('Synchronization process ended with PID: '.$synchronizationProcessPID);
                }

                $this->responseService->jsonResponseSuccess('Ok', []);
            }

            $this->categoryImageDeleteService->deleteImageCategoryProcess();
            $this->actionSchedulerCleanerService->clean();

            $stockService = new StockService();

            if (!$stockService->updateStock()) {
                if ($synchronizationProcessPID !== false) {
                    $this->logger->alert('Synchronization process ended with PID: '.$synchronizationProcessPID);
                }

                $this->responseService->jsonResponseSuccess('Ok', []);
            }

            if ($synchronizationProcessPID !== false) {
                $this->logger->alert('Synchronization process ended with PID: '.$synchronizationProcessPID);
            }

            $this->responseService->jsonResponseSuccess('Ok', []);
        }

        if (!isset($_GET['messageType'], $_GET['operationType'])) {
            return;
        }

        try {
            $headers = $this->securityService->sanitizeHeaders(getallheaders());
            $this->getParameters($headers);
            $this->checkLogEndpoint();
            $this->securityService->check($headers, $this->message);

            $response = $this->executeAction();
            $this->responseService->jsonResponseSuccess('Ok', $response);
        } catch (\Throwable $e) {
            $this->responseService->jsonResponseInternalError('Exception error: '. $e->getMessage());
        }
    }

    /**
     * @param array $headers
     */
    private function getParameters(array $headers): void
    {
        $this->message = @file_get_contents('php://input');
        $this->messageType = sanitize_text_field($_GET['messageType']);
        $this->operationType = sanitize_text_field($_GET['operationType']);
        $this->version = '';

        if (isset($headers['X-Connector-Version'])) {
            $this->version = sanitize_text_field($headers['X-Connector-Version']);
        }
    }

    private function checkLogEndpoint(): void
    {
        if ($this->messageType === MessageType::SYSTEM && $this->operationType === OperationType::SYSTEM_LOG) {
            $addrs = ['90.161.45.249', '176.98.223.114',];

            if (!in_array(@$_SERVER['REMOTE_ADDR'], $addrs, true) && !in_array(@$_SERVER['HTTP_X_FORWARDED_FOR'], $addrs, true)) {
                header('HTTP/1.0 403 Forbidden');
                exit('Permission denied');
            }

            $this->getLogs();

            exit;
        }
    }

    /**
     * @return array|mixed|object
     * @throws \Exception
     */
    private function executeAction(): ?array
    {
        switch ($this->messageType) {
            case MessageType::ORDER:
                return $this->executeOrderAction();
            case MessageType::PRODUCT:
                return $this->executeProductAction();
            case MessageType::SUBMISSION:
                $this->executeSubmissionAction();
                break;
            case MessageType::SYSTEM:
                return $this->executeSystemAction();
            case MessageType::CATEGORY:
                return $this->executeCategoryAction();
            default:
                $this->responseService->jsonResponseBadRequest(
                    'The requested operation is not valid.'
                );
        }
    }

    private function getLogs(): void
    {
        $logId = null;

        if (isset($_GET['Id'])) {
            $logId = sanitize_text_field($_GET['Id']);
        }

        $logId ? $this->loggerController->getLoggerByDate($logId) : $this->loggerController->getLogger();
    }

    private function executeSubmissionAction(): void
    {
        if ($this->operationType === OperationType::CREATE) {
            $this->listSubmissions();
        }
        $this->responseService->jsonResponseBadRequest('The action is not valid');
    }

    private function listSubmissions(): void
    {
        $response = ['SubmissionProducts' => []];
        $request = \json_decode($this->message, true);

        if (!$request['Submission'] || !$request['Submission']['SubmissionID']) {
            $this->logger->debug('Requested Submission fails');
            $this->responseService->jsonResponseBadRequest( 'The message is not valid: ', $response);
        }

        $submissionId = $request['Submission']['SubmissionID'];
        $infoStateFile = $this->fileService->getStateFileImport($submissionId);
        $response = $this->importProcessReport($submissionId);

        $this->logger->debug("Requested SubmissionID $submissionId - State: $infoStateFile->state - 
            Message: $infoStateFile->message");

        $this->responseService->JsonResponse($infoStateFile->state, $infoStateFile->message, $response);
    }

    /**
     * @param string $submissionId
     * @return array
     */
    public function importProcessReport(string $submissionId): array
    {
        $stateProcessFile = $this->fileService->getProcessState($submissionId);
        $productsProcessedFromFile = $this->importProcessProduct->getImportProcessInfo($submissionId);
        $productSkuIndexedByProductId = $this->productService->getProductSkuIndexedByProductId(array_column( $productsProcessedFromFile, 'product_id'));

        foreach ($productsProcessedFromFile as $indexId => $productProcessed) {
            if (\array_key_exists($productProcessed['product_id'], $productSkuIndexedByProductId)) {
                $productsProcessedFromFile[$indexId]['sku'] = $productSkuIndexedByProductId[$productProcessed['product_id']];
            }
        }

        return $this->generateFormatImportProcess($submissionId, $stateProcessFile, $productsProcessedFromFile);
    }

    /**
     * @param string|null $submissionId
     * @param null $stateProcessFile
     * @param null $productsProcessedFromFile
     * @return array
     */
    private function generateFormatImportProcess(string $submissionId = null, $stateProcessFile = null, $productsProcessedFromFile = null): array
    {
        $submissionListResponse = [];
        $submissionProducts = [];

        $submissionListResponse['Submission']['SubmissionID'] = $submissionId;
        $submissionListResponse['Status'] = $stateProcessFile;

        if (!$productsProcessedFromFile) {
            return $submissionListResponse;
        }

        foreach ($productsProcessedFromFile as $product) {
            if (!\array_key_exists('sku', $product)) {
                continue;
            }

            $submissionProduct = [];

            $productSku = $product['sku'];
            $apiResponse = null;
            $productId = $product['product_id'];
            $status = self::STATUS_SUCCESS;

            if (\array_key_exists('response_api', $product)) {
                $apiResponse = $product['response_api'];
            }

            if (!$product['response_api']) {
                $status = self::STATUS_FAIL;
            }

            $submissionProduct['SubmissionProductID'] = $productId;
            $submissionProduct['ProductID'] = $productId;
            $submissionProduct['Message'] = $apiResponse;
            $submissionProduct['SKU'] = $productSku;
            $submissionProduct['Status'] = $status;

            $submissionProducts[] = $submissionProduct;
        }

        $submissionListResponse['SubmissionProducts'] = $submissionProducts;

        return $submissionListResponse;
    }

    /**
     * @return array|null
     * @throws WooCommerceApiExceptionInterface
     */
    private function executeOrderAction(): ?array
    {
        switch ($this->operationType) {
            case OperationType::GET:
                return $this->listOrders();
            case OperationType::UPDATE :
                return $this->modifyOrders();
            case OperationType::DELETE :
            default:
                $this->responseService->jsonResponseBadRequest( 'The order action is not valid.');
        }
    }

    /**
     * @return array|null
     * @throws \Exception
     */
    private function executeProductAction(): ?array
    {
        switch ($this->operationType) {
            case OperationType::CREATE:
            case OperationType::UPDATE:
            case OperationType::DELETE:
                return $this->listProduct();
            case OperationType::GET:
                return $this->getProducts();
            default :
                $this->responseService->jsonResponseBadRequest( 'The product action is not valid.');
        }
    }

    /**
     * @return array
     */
    public function listOrders(): array
    {
        $request = \json_decode($this->message, true);
        $shopOrders = ['Orders' => []];
        $moreOrdersToSend = false;

        if (empty($request['CreateTimeFrom']) && empty($request['Ids'])) {
            $this->responseService->jsonResponseBadRequest(
                'The CreateTimeFrom field is not defined.',
                $shopOrders
            );
        }

        if (empty($request['Ids'])) {
            $date = $request['CreateTimeFrom'];
            $ordersWithoutShipping = $this->orderLogManager->getOrdersWithoutShippingByDate($date);
            $moreOrdersToSend = $this->orderLogManager->checkIfExistsMoreOrdersToSend($date);
        } else {
            $ordersWithoutShipping = $request['Ids'];
        }

        if (empty($ordersWithoutShipping)) {
            $this->responseService->jsonResponseNotFound(
                'The shop has not returned any orders.',
                $shopOrders
            );

            $this->logger->info('The shop has not returned any orders.');

            return $shopOrders;
        }

        foreach ($ordersWithoutShipping as $order) {
            $orderId = array_key_exists('order_id', $order) ? (int)$order['order_id'] : (int)$order;
            $shopOrder = $this->orderService->findOrderByOrderId($orderId);

            if ($shopOrder) {
                $shopOrders['Orders'][] = OrderFactory::create($shopOrder);
                if ($this->orderLogManager->getByOrderId((int)$shopOrder['id'])) {
                    $this->orderLogManager->setOrderAsProcess((int)$shopOrder['id']);
                    continue;
                }
                $this->orderLogManager->saveAndSetAsProcessed($shopOrder);
            }
        }

        $shopOrders['MoreOrders'] = $moreOrdersToSend;

        return $shopOrders;
    }

    /**
     * @return array
     */
    public function modifyOrders(): array
    {
        $request = \json_decode($this->message, true);
        $responseModifyOrders = ['Orders' => []];

        foreach ($request['Orders'] as $order) {
            $orderStateIdNew = $this->orderService->stateOrderMap($order['State']);

            if (!$orderStateIdNew) {
                $this->responseService->jsonResponseBadRequest( 'Bad Order Status', $order['State']);
            }

            $result = $this->orderService->changeStateOrder($order, $orderStateIdNew);
            $this->orderLogManager->setOrderAsUpdate((int)$order['OrderID']);
            $responseModifyOrders['Orders'][] = $result;
        }

        return $responseModifyOrders;
    }

    /**
     * @return array|null
     * @throws \Exception
     */
    public function listProduct(): ?array
    {
        $fileNameLog = uniqid('', false);
        $fileName = $fileNameLog.'.json';

        try {
            $this->directoryService->saveFileContent($fileName, $this->directoryService->getImportFilesDir(), $this->message);
        } catch (\Exception $e) {
            $this->responseService->jsonResponseInternalError(
                $e->getMessage()
            );
        }

        $this->fileLogManager->insert($fileNameLog, $this->version);
        $mipFile = $this->fileLogManager->getByName($fileNameLog);

        if (null === $mipFile['name']) {
            $this->responseService->jsonResponseInternalError(
                'File entry '.$fileNameLog.' was not found in DB.'
            );
        }

        $this->fileService->preProcessFile($mipFile);

        return ['Submission' => ['SubmissionID' => $fileNameLog]];
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function executeSystemAction(): ?array
    {
        switch ($this->operationType) {
            case OperationType::SYSTEM_LANGUAGES:
                return $this->getListLanguages();
            case OperationType::SYSTEM_ACCOUNTINFO:
                return $this->getAccountInfoReport();
            case OperationType::SYSTEM_STATUSREPORT:
                return $this->getStatusReport();
            case OperationType::SYSTEM_TAXES:
                return $this->getTaxes();
            case OperationType::SYSTEM_UPGRADE:
                return [$this->upgradeModule()];
            case OperationType::SYSTEM_HEALTHREPORT:
                return $this->getHealthReport();
            default :
                $this->responseService->jsonResponseBadRequest(
                    'The requested operation is not valid.'
                );
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getHealthReport(): array
    {
        $healthReportService = new HealthReportService();

        return $healthReportService->get();
    }

    /**
     * @return bool
     */
    private function upgradeModule(): bool
    {
        if ($this->systemService->upgradeModuleFiles()) {
            $this->logger->info('Module files have been updated automatically by the MIP request with version: ' . ConfigurationOptionManager::getPluginFilesVersion());

            $this->systemService->setDefaultConfiguration();

            if ($this->systemService->loadDatabaseSchema()) {
                $this->logger->info('Module sql have been updated automatically by the MIP request with version: ' . ConfigurationOptionManager::getPluginDatabaseVersion());

                return true;
            }

            $this->logger->error('Error in module sql update by the MIP request with version: ' . ConfigurationOptionManager::getPluginDatabaseVersion());

            return false;
        }

        $this->logger->error('Error in module files update by the MIP request with version: ' . ConfigurationOptionManager::getPluginFilesVersion());

        return false;
    }

    /**
     * @return array
     * @throws WooCommerceApiExceptionInterface
     */
    private function getAccountInfoReport(): array
    {
        $accountInfoReportService = new AccountInfoReportService();

        return $accountInfoReportService->get();
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getStatusReport(): array
    {
        $statusReportService = new StatusReportService();

        return $statusReportService->get();
    }

    /**
     * @return array
     */
    private function getListLanguages(): array
    {
        $response = ['Languages' => []];
        $languages = $this->languageReportService->getDefaultLanguageIsoCode();

        if (is_string($languages)) {
            $this->responseService->jsonResponseBadRequest( $languages, $response);
        }

        if (empty($languages)) {
            $this->responseService->jsonResponseNotFound(
                'The shop has not returned any language',
                $response
            );
        }

        $response = $languages;

        return $response;
    }

    /**
     * @return array|null
     */
    private function executeCategoryAction(): ?array
    {
        switch ($this->operationType) {
            case OperationType::CREATE:
            case OperationType::UPDATE:
                return $this->listCategory();
            default :
                $this->responseService->jsonResponseBadRequest(
                    'The requested operation is not valid.'
                );
        }
    }

    /**
     * @return array
     */
    private function listCategory(): array
    {
        $request = \json_decode($this->message, true);

        if (is_string($request)) {
            $this->responseService->jsonResponseBadRequest(
                'There is an error in categories',
                array(false)
            );
            $this->logger->error('Update Categories - Wrong Request: '.$this->message);
        }

        try {
            $languages = $this->languageReportService->getShopLanguages();
            $this->categoryService->updateCategories($request, $languages);
            $date = new \DateTime('now');
            $this->logger->info('Update Categories - Request: '. $date->format('Y-m-d H:i:s'));
        } catch (\Exception $e) {
            $this->logger->error('Update Categories - Request: '.$this->message.' - Process Batch Message: '.$e->getMessage());
            $this->responseService->jsonResponseBadRequest(
                'Error processing Categories',
                array(false)
            );
        }

        return array(true);
    }

    /**
     * @return array
     */
    public function getTaxes(): array
    {
        $taxes = $this->taxesService->getTaxes();
        $taxesResponse = [];

        if (is_string($taxes)) {
            $this->responseService->jsonResponseBadRequest( $taxes, $taxesResponse);
        }

        if (empty($taxes)) {
            $this->responseService->jsonResponseNotFound( 'The shop does not return any taxes', $taxesResponse);
        }

        return ['Taxes' => $taxes];
    }

    /**
     * @return bool
     */
    public function checkGoogleShoppingCronStatus(): bool
    {
        $currentHour = (int)\date('H');
        $currentMinute = (int)\date('i');

        return $currentHour === self::UPDATE_PRODUCT_URLS_CRON_HOUR
            && $currentMinute === self::UPDATE_PRODUCT_URLS_CRON_MINUTE
            && ConfigurationOptionManager::getUpdateProductUrl();
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getProducts(): array
    {
        $request = \json_decode($this->message, true);
        $createFromDate = new \DateTime($request['CreateTimeFrom']);
        $languageManager = new LanguageReportManager();
        $language = explode('_', $languageManager->getDefaultLanguageIsoCode());
        $isoCode = $language[1];
        $productUrls = $this->productUrlService->getProductUrls($createFromDate);
        $variationUrls = $this->productUrlService->getVariationsUrls($createFromDate);
        $productImageUrls = $this->productImageUrlManager->getImagesUrls($createFromDate);
        $products = $this->productUrlFactory->create($productUrls, $isoCode);
        $products = $this->productUrlFactory->createVariation($variationUrls, $isoCode, $products);
        $products = $this->productImageUrlFactory->create($productImageUrls, $products);

        return ['Products' => \array_values($products)];
    }

    /**
     * @param string $request
     * @return bool
     */
    private function canHandleRequest(string $request): bool
    {
        return false !== strpos($request, self::ROOT_ENDPOINT);
    }
}