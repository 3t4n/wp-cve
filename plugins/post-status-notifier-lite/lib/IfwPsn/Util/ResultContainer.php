<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 * Result container
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @version   $Id: ResultContainer.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package
 */
class IfwPsn_Util_ResultContainer
{
    /**
     * @var bool
     */
    protected $_success = true;

    /**
     * @var null|string
     */
    protected $_message;

    /**
     * @var array
     */
    protected $_data = array();

    /**
     * @param bool $success
     * @param string $message
     * @param array $data
     */
    public function __construct($success = true, $message = '', $data = array())
    {
        if (is_bool($success)) {
            $this->_success = $success;
        }
        if (is_string($message)) {
            $this->_message = $message;
        }
        if (is_array($data)) {
            $this->_data = $data;
        }
    }

    /**
     * Adds data as key value pair
     * @param $key
     * @param $value
     * @param bool $overwrite
     */
    public function setData($key, $value, $overwrite = true)
    {
        if ($overwrite || (!$overwrite && !isset($this->_data[$key]))) {
            $this->_data[$key] = $value;
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasData($key)
    {
        return isset($this->_data[$key]);
    }


    /**
     * @param null|string $key
     * @return array|mixed
     */
    public function getData($key = null)
    {
        if (!empty($key) && isset($this->_data[$key])) {
            return $this->_data[$key];
        }
        return $this->_data;
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
     * @param string $message
     */
    public function setMessage($message)
    {
        if (is_string($message)) {
            $this->_message = $message;
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'success' => $this->isSuccess(),
            'message' => $this->getMessage(),
            'data' => $this->getData()
        ];
    }

    /**
     * @return false|string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
}
