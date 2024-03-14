<?php
/**
 *
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @copyright Copyright (c) 2014 ifeelweb.de
 * @version   $Id: Stats.php 1850578 2018-03-31 19:27:09Z worschtebrot $
 * @package
 */
class IfwPsn_Wp_Plugin_Cron_Stats
{
    const STATUS_GREEN = 1;
    const STATUS_YELLOW = 2;
    const STATUS_RED = 3;


    /**
     * @var string
     */
    protected $_id;

    /**
     * @var string
     */
    protected $_name;

    /**
     * @var bool
     */
    protected $_success = false;

    /**
     * @var
     */
    protected $_status = self::STATUS_RED;

    /**
     * @var string
     */
    protected $_message;

    /**
     * @var
     */
    protected $_timestamp;



    /**
     * IfwPsn_Wp_Cron_Stats constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->_success;
    }

    /**
     * @param bool $success
     * @return $this
     */
    public function setSuccess($success)
    {
        if (is_bool($success)) {
            $this->_success = $success;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->_message = $message;
        return $this;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function addMessage($message)
    {
        $this->_message .= $message;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimestamp($format = null)
    {
        if ($format != null) {
            return IfwPsn_Wp_Date::format($this->getTimestamp(), $format);
        }
        return $this->_timestamp;
    }

    /**
     * @param mixed $timestamp
     * @return $this
     */
    public function setTimestamp($timestamp)
    {
        $this->_timestamp = $timestamp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @param mixed $status
     * @return $this
     */
    public function setStatus($status)
    {
        $status = (int)$status;
        if (in_array($status, array(self::STATUS_GREEN, self::STATUS_YELLOW, self::STATUS_RED))) {
            $this->_status = $status;
        }
        return $this;
    }

    public function isStatusGreen()
    {
        return $this->_status == self::STATUS_GREEN;
    }

    /**
     * @return $this
     */
    public function setStatusGreen()
    {
        $this->_status = self::STATUS_GREEN;
        return $this;
    }

    public function isStatusYellow()
    {
        return $this->_status == self::STATUS_YELLOW;
    }

    /**
     * @return $this
     */
    public function setStatusYellow()
    {
        $this->_status = self::STATUS_YELLOW;
        return $this;
    }

    public function isStatusRed()
    {
        return $this->_status == self::STATUS_RED;
    }

    /**
     * @return $this
     */
    public function setStatusRed()
    {
        $this->_status = self::STATUS_RED;
        return $this;
    }

    /**
     * @param IfwPsn_Wp_Plugin_Manager $pm
     */
    public function save(IfwPsn_Wp_Plugin_Manager $pm)
    {
        try {
            IfwPsn_Wp_Plugin_Cron_Stats_Manager::getInstance($pm)->saveStats($this);
        } catch (Exception $e) {
            // just to prevent script termination caused by excepptions in stats logging
        }
    }

}