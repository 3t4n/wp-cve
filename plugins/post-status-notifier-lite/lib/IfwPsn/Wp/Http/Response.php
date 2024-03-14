<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 *
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Response.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 */
class IfwPsn_Wp_Http_Response
{
    /**
     * @var
     */
    protected $_response;

    /**
     * @var int
     */
    protected $_statusCode;

    /**
     * The response body
     * @var string|null
     */
    protected $_body;

    /**
     * @var string
     */
    protected $_errorMessage;



    /**
     * @param $response
     */
    public function __construct($response = null)
    {
        $this->_response = $response;

        $this->_init();
    }

    protected function _init()
    {
        if (is_array($this->_response) && isset($this->_response['response'])) {

            // response is an array
            if (isset($this->_response['response']['code'])) {
                $this->_statusCode = $this->_response['response']['code'];
            }
            if (isset($this->_response['response']['message'])) {
                $this->_errorMessage = $this->_response['response']['message'];
            }
            if (isset($this->_response['body'])) {
                $this->_body = $this->_response['body'];
            }

        } elseif (is_wp_error($this->_response)) {

            /**
             * is WP_Error
             * @var WP_Error $this->_response
             */
            $this->_errorMessage = $this->_response->get_error_message();
            $this->_statusCode = 404;
            
        } else {

            // unknown response
            // set to error status
//            $this->_errorMessage = 'Invalid response';
//            $this->_statusCode = 404;
        }
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->_statusCode == 200;
    }

    /**
     * @return bool
     */
    public function isError()
    {
        return $this->_statusCode >= 400;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->_errorMessage;
    }

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        if (is_string($errorMessage)) {
            $this->_errorMessage = $errorMessage;
        }
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * @param string|null $body
     */
    public function setBody($body)
    {
        $this->_body = $body;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        if (is_array($this->_response)) {
            return $this->_response;
        }

        return [
            'body' => $this->getBody(),
            'response' => [
                'code' => $this->getStatusCode(),
                'message' => $this->getErrorMessage()
            ]
        ];
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->_response = $response;
    }
    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->_statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->_statusCode = (int)$statusCode;
    }

    /**
     * @return array|mixed
     */
    public function getArray()
    {
        return json_decode($this->getBody(), true);
    }
}
 