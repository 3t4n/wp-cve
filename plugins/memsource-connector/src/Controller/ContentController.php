<?php

namespace Memsource\Controller;

use Memsource\Dao\MetaDao;
use Memsource\Exception\NotFoundException;
use Memsource\Service\AuthService;
use Memsource\Service\Content\IContentService;
use Memsource\Service\DatabaseService;
use Memsource\Service\OptionsService;
use Memsource\Utils\ArrayUtils;
use Memsource\Utils\LogUtils;
use Throwable;
use WP_Error;
use WP_REST_Server;

final class ContentController extends \WP_REST_Controller
{
    private const IGNORED_CONTENT_TYPES = [
        'acf-field',
    ];

    /** @var \wpdb */
    private $wpdb;

    /** @var OptionsService */
    private $optionsService;

    /** @var AuthService */
    private $authService;

    /** @var array|IContentService[] */
    private $contentServices = [];

    /** @var DatabaseService */
    private $databaseService;

    /** @var MetaDao */
    private $metaDao;

    public function __construct(
        OptionsService $optionsService,
        AuthService $authService,
        DatabaseService $databaseService,
        MetaDao $metaDao
    ) {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->optionsService = $optionsService;
        $this->authService = $authService;
        $this->databaseService = $databaseService;
        $this->metaDao = $metaDao;
    }

    /**
     * @return void
     */
    public function registerRestRoutes()
    {
        $namespace = $this->getNamespace();
        register_rest_route($namespace, '/list', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => function (\WP_REST_Request $request) {
                return $this->processRequest($request, 'getItems');
            },
            'args' => $this->getInitArgs(),
            'permission_callback' => '__return_true',
        ]);
        register_rest_route($namespace, '/get/(?P<id>\d+)', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => function (\WP_REST_Request $request) {
                return $this->processRequest($request, 'getItem');
            },
            'args' => $this->getInitArgs(),
            'permission_callback' => '__return_true',
        ]);
        register_rest_route($namespace, '/translate/(?P<id>\d+)', [
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => function (\WP_REST_Request $request) {
                return $this->processRequest($request, 'saveTranslation');
            },
            'args' => $this->getInitArgs(),
            'permission_callback' => '__return_true',
        ]);
        register_rest_route($namespace, '/last', [
            'methods' => WP_REST_Server::EDITABLE,
            'callback' => function (\WP_REST_Request $request) {
                return $this->processRequest($request, 'storeLastProcessedId');
            },
            'args' => $this->getInitArgsWithTypeOptional(),
            'permission_callback' => '__return_true',
        ]);
    }

    /**
     * @param IContentService[] $services
     */
    public function addContentServices(array $services)
    {
        foreach ($services as $service) {
            $this->addContentService($service);
        }
    }

    /**
     * Add a content service for endpoints.
     * @param $content IContentService
     * @return void
     * @throws \Exception
     */
    public function addContentService(IContentService $content)
    {
        if (in_array($content->getType(), self::IGNORED_CONTENT_TYPES, true)) {
            return;
        }

        if (!isset($this->contentServices[$content->getType()])) {
            $this->contentServices[$content->getType()] = $content;
            return;
        }

        throw new \Exception(sprintf('Content type \'%s\' is registered already.', $content->getType()));
    }

    /**
     * Get content service.
     * @param $type string
     * @return IContentService
     * @throws \InvalidArgumentException
     */
    public function getContentService($type)
    {
        if (!isset($this->contentServices[$type])) {
            throw new \InvalidArgumentException(sprintf('Unknown type of content \'%s\'.', $type));
        }
        return $this->contentServices[$type];
    }

    /**
     * @return IContentService[]|array
     */
    public function getContentServices()
    {
        return $this->contentServices;
    }

    /**
     * Check if token is valid.
     * @param $token string
     * @return bool
     */
    public function checkAuth($token)
    {
        $response = $this->authService->checkAuth($token);
        return !isset($response['error']);
    }

    /**
     * Check if type of content exists.
     * @param $type string
     * @return bool
     */
    public function checkContentType($type)
    {
        try {
            $this->getContentService($type);
            return true;
        } catch (\InvalidArgumentException $exception) {
            $error = $this->createWPError($exception->getMessage());
            $this->logError($error, null, $exception);
            return false;
        }
    }

    /**
     * Process request.
     *
     * @param $request \WP_REST_Request
     * @param $method string name of method which
     *
     * @return \WP_REST_Response|WP_Error
     */
    private function processRequest(\WP_REST_Request $request, $method)
    {
        try {
            $this->logRequest($request);

            if (!method_exists($this, $method)) { //check if the method exists
                throw new \Exception(sprintf('Method \'%s\' does not exist in the \'%s\' class.', $method, ContentController::class));
            }

            $response = new \WP_REST_Response();
            $this->wpdb->query('START TRANSACTION');
            $this->{$method}($request, $response);
            $this->wpdb->query('COMMIT');
            $this->logResponse($response);

            return $response;
        } catch (Throwable $exception) {
            $this->wpdb->query('ROLLBACK');
            $code = $exception instanceof NotFoundException ? 404 : ($exception instanceof \InvalidArgumentException ? 400 : 500);
            $message = $code === 500 ? 'An error has occurred: ' . $exception->getMessage() : $exception->getMessage();
            $error = $this->createWPError($message, $code);
            $this->logError($error, $code === 500 ? $exception->getMessage() : null, $exception);

            if ($request->get_param('raw') === '1') {
                throw $exception;
            }

            return $error;
        }
    }

    /**
     * @param $request \WP_REST_Request
     * @param $response \WP_REST_Response
     * @return void
     */
    private function getItems(\WP_REST_Request $request, \WP_REST_Response $response)
    {
        $contentType = $request->get_param('type');
        if ($contentType === '/') {
            $types = $this->getTypes();
            $response->set_data($types);
            return;
        }
        $data = $this->getContentService($contentType)->getItems($request->get_params());
        $responseData = $this->createResponseData($data ?: []);
        $response->set_data($responseData);
    }

    /**
     * @return array
     */
    private function getTypes()
    {
        $response = [
            'types' => [],
        ];
        foreach ($this->getContentServices() ?: [] as $service) {
            $response['types'][] = [
                'type' => $service->getType(),
                'label' => $service->getLabel(),
                'folder' => $service->isFolder(),
            ];
        }

        return $response;
    }

    /**
     * @param $request \WP_REST_Request
     * @param $response \WP_REST_Response
     * @return void
     */
    private function getItem(\WP_REST_Request $request, \WP_REST_Response $response)
    {
        $contentType = $request->get_param('type');
        $data = $this->getContentService($contentType)->getItem($request->get_params());
        $response->set_data($data ?: []);

        if ($request->get_param('raw') === '1') {
            $customFields = $this->metaDao->findMetaKeysByType($this->getContentService($contentType)->getBaseType(), $data['id']);
            echo "=> transformedSourceId: " . ($data['transformedSourceId'] ?? '(null)') . "\n" .
                 "=> title: " . $data['title'] . "\n" .
                 "=> content:\n" . $data['content'] . "\n" .
                 "=> custom fields:\n" . print_r($customFields, true) .
                 "\n\n-------------\n\n";
        }
    }

    /**
     * @param $request \WP_REST_Request
     * @param $response \WP_REST_Response
     * @return void
     */
    private function saveTranslation(\WP_REST_Request $request, \WP_REST_Response $response)
    {
        $params = $request->get_params();
        //the language code may have been changed to the memsource code
        ArrayUtils::checkKeyExists($params, ['lang']);
        $mapping = $this->databaseService->findOneLanguageMappingByMemsourceCode($params['lang']);
        $params['lang'] = $mapping !== null ? $mapping['code'] : $params['lang'];
        $contentType = $request->get_param('type');
        $id = $this->getContentService($contentType)->saveTranslation($params);
        $response->set_data($id ? ['id' => $id] : []);
    }

    /**
     * @deprecated This endpoint will be present for a while due to backward compatibility.
     * @param $request \WP_REST_Request
     * @param $response \WP_REST_Response
     * @return void
     */
    private function storeLastProcessedId(\WP_REST_Request $request, \WP_REST_Response $response)
    {
        $response->set_data(['status' => 'OK']);
    }

    /**
     * Namespace of api.
     * @return string
     */
    private function getNamespace()
    {
        return $this->optionsService->getRestNamespace();
    }

    /**
     * Init parameters for each api endpoint.
     * @return array
     */
    private function getInitArgs()
    {
        return [
            'token' => [
                'required' => true,
                'validate_callback' => [$this, 'checkAuth'],
            ],
            'type' => [
                'required' => true,
                'validate_callback' => function ($type) {
                    return $type !== '/' ? $this->checkContentType($type) : true;
                },
            ],
        ];
    }

    private function getInitArgsWithTypeOptional(): array
    {
        return [
            'token' => [
                'required' => true,
                'validate_callback' => [$this, 'checkAuth'],
            ],
            'type' => [
                'required' => false,
                'default' => 'post',
                'validate_callback' => function ($type) {
                    return $type !== '/' ? $this->checkContentType($type) : true;
                },
            ],
        ];
    }

    /**
     * Create array with response data.
     * @param $data array
     * @return array
     */
    private function createResponseData(array $data)
    {
        return [
            'posts' => $data, // the response contains key 'posts', because TMS reads data under this key.
        ];
    }

    /**
     * @param $message string
     * @param $code int
     * @return WP_Error
     */
    private function createWPError($message, $code = 500)
    {
        return new WP_Error('error', $message, ['status' => $code]);
    }

    /**
     * Log error.
     *
     * @param WP_Error $error
     * @param string|null $additionalMessage
     * @param Throwable $exception
     *
     * @return WP_Error
     */
    private function logError(WP_Error $error, $additionalMessage = null, $exception = null)
    {
        if ($this->optionsService->isDebugMode()) {
            $data = [
                'message' => $error->get_error_message(),
                'additional_message' => $additionalMessage,
                'params' => $error->get_error_data(),
            ];
            LogUtils::error(LogUtils::toStr($data), $exception);
        }

        return $error;
    }

    /**
     * Log API request.
     * @param $request \WP_REST_Request
     * @return \WP_REST_Request
     */
    private function logRequest(\WP_REST_Request $request)
    {
        if ($this->optionsService->isDebugMode()) {
            $data = [
                'route' => $request->get_route(),
                'params' => $request->get_params(),
            ];
            $method = $_SERVER['REQUEST_METHOD'] ?? '';
            LogUtils::info("$method request:\n" . LogUtils::toStr($data));
        }
        return $request;
    }

    /**
     * Log API response.
     * @param \WP_REST_Response $response
     */
    private function logResponse(\WP_REST_Response $response)
    {
        if ($this->optionsService->isDebugMode()) {
            LogUtils::info("Response:\n" . LogUtils::toStr($response->get_data()));
        }
    }
}
