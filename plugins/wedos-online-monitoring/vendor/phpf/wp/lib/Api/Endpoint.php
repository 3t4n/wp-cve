<?php
namespace PHPF\WP\Api;

/**
 * Base class for REST API endpoints
 *
 * @author  Petr Stastny <petr@stastny.eu>
 * @license GPLv3
 */
abstract class Endpoint
{
    /**
     * Request object
     * @var \WP_REST_Request
     */
    protected $request;

    /**
     * Input data (parsed JSON object)
     * @var \stdClass
     */
    protected $inputData;

    /**
     * Output data
     * @var \stdClass
     */
    protected $outputData;

    /**
     * HTTP response code
     * @var int
     */
    private $httpResponseCode = 200;


    /**
     * Register endpoint
     *
     * This method must call register_rest_route().
     *
     * @return void
     */
    abstract public static function registerEndpoint();


    /**
     * Constructor
     *
     * @param \WP_REST_Request $request request object
     */
    public function __construct(\WP_REST_Request $request)
    {
        $this->request = $request;
        $this->outputData = new \stdClass();
    }


    /**
     * Process REST API request, called by WP
     *
     * @param \WP_REST_Request $request request object
     * @return mixed
     */
    final public static function executeStatic(\WP_REST_Request $request)
    {
        $endpoint = new static($request);
        return $endpoint->executeInternal();
    }


    /**
     * Process REST API request (inside object)
     *
     * @return \WP_REST_Response
     */
    private function executeInternal()
    {
        try {
            $this->readInputData();
            $this->process();

        } catch (ResponseStopException $ex) {
            // request to stop execution
        }

        // result not set
        if ($this->outputData == new \stdClass()) {
            $this->error('I501', 'Internal error: invalid output data', 500);
        }

        $this->outputData->requestId = $_SERVER['REQUEST_TIME_FLOAT'].'.'.getmypid();

        $response = new \WP_REST_Response($this->outputData, $this->httpResponseCode);

        return $response;
    }


    /**
     * Process REST API request
     *
     * @return void
     */
    abstract protected function process();


    /**
     * Read JSON data from request body
     *
     * @return void
     */
    private function readInputData()
    {
        $body = $this->request->get_body();
        if (!$body) {
            return;
        }

        $this->inputData = json_decode($body);

        if (!is_object($this->inputData)) {
            $this->errorStop('C501', 'Invalid input; not in JSON format or malformed');
        }
    }


    /**
     * Check that there are some input data
     *
     * Throws an client error when no data are present on input.
     *
     * @return void
     */
    protected function requireInputData()
    {
        if (!is_object($this->inputData)) {
            $this->errorStop('C502', 'No input data; required by this method', 400);
        }
    }


    /**
     * Stop processing and send error object
     *
     * @param string $errorCode error code
     * @param string $error error description
     * @param int $httpResponseCode HTTP response code
     * @return void
     */
    protected function errorStop($errorCode, $error, $httpResponseCode = 400)
    {
        $this->error($errorCode, $error, $httpResponseCode);
        throw new ResponseStopException('');
    }


    /**
     * Raise error
     *
     * Error object is set as result
     *
     * @param string $errorCode error code
     * @param string $error error description
     * @param int $httpResponseCode HTTP response code
     * @return void
     */
    protected function error($errorCode, $error, $httpResponseCode = 400)
    {
        if (!is_object($this->outputData)) {
            $this->outputData = new \stdClass();
        }

        $this->outputData->error = new \stdClass();
        $this->outputData->error->code = $errorCode;
        $this->outputData->error->error = $error;

        $this->setHttpResponseCode($httpResponseCode);
    }


    /**
     * Set HTTP response code (default is 200)
     *
     * @param int $code HTTP response code
     * @return void
     */
    protected function setHttpResponseCode($code)
    {
        $this->httpResponseCode = $code;
    }
}
