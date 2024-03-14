<?php
/**
 * ifeelweb.de Wordpress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 *
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Json.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 */
class IfwPsn_Wp_Ajax_Response_Json extends IfwPsn_Wp_Ajax_Response_Abstract
{
    /**
     * @var bool
     */
    protected $_success = true;

    /**
     * @var array
     */
    protected $_data = [];

    /**
     * @var array
     */
    protected $_values = [];

    /**
     * @var null|string
     */
    protected $_message;


    /**
     * @param bool $success
     * @param array $data
     */
    public function __construct($success = true, $data = array(), $message = null)
    {
        if (is_bool($success)) {
            $this->_success = $success;
        }
        if (is_array($data)) {
            $this->_data = $data;
        }
        if (!is_null($message)) {
            $this->_message = $message;
        }
    }

    /**
     * Output response header
     */
    public function header()
    {
        header('Content-Type: application/json; charset=utf-8');
    }

    /**
     * Outputs the response data
     */
    public function output()
    {
        $result = array(
            'success' => $this->_success,
            'data' => $this->_data
        );
        if (!empty($this->_message)) {
            $result['message'] = $this->_message;
        }
        $result = array_merge($result, $this->_values);

        echo json_encode($result);
    }

    /**
     * Adds data as key value pair
     * @param $key
     * @param $value
     */
    public function addData($key, $value)
    {
        if (!isset($this->_data[$key])) {
            $this->_data[$key] = $value;
        }
    }

    public function setData($data)
    {
        if (is_array($data)) {
            $this->_data = $data;
        }
    }

    /**
     * @param $key
     * @param $value
     */
    public function setValue($key, $value)
    {
        $this->_values[$key] = $value;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->_success;
    }

    /**
     * @param boolean $success
     */
    public function setSuccess($success)
    {
        if (is_bool($success)) {
            $this->_success = $success;
        }
    }

    /**
     * @return null|string
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @param null|string $message
     */
    public function setMessage($message)
    {
        $this->_message = $message;
    }
}
