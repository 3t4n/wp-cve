<?php
/**
 *
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @copyright Copyright (c) 2014 ifeelweb.de
 * @version   $Id: LogCollector.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package
 */
class IfwPsn_Wp_LogCollector
{
    /**
     * @var array
     */
    protected static $collection = array();


    /**
     * @param $identifier
     */
    public static function register($identifier)
    {
        add_action($identifier, array('IfwPsn_Wp_LogCollector', 'log'), 10, 2);
    }

    /**
     * @param $identifier
     * @param $message
     */
    public static function add($identifier, $message)
    {
        do_action($identifier, $message, $identifier);
    }

    /**
     * @param $message
     * @param $identifier
     */
    public static function log($message, $identifier)
    {
        if (!isset(self::$collection[$identifier])) {
            self::$collection[$identifier] = array();
        }
        array_push(self::$collection[$identifier], $message);
    }

    /**
     * @param $identifier
     */
    public static function flush($identifier)
    {
        if (isset(self::$collection[$identifier])) {
            unset(self::$collection[$identifier]);
        }
    }

    /**
     * @param $identifier
     * @param bool $raw
     * @return mixed|null|string
     */
    public static function get($identifier, $raw = false)
    {
        if (isset(self::$collection[$identifier])) {
            if ($raw) {
                return self::$collection[$identifier];
            }
            return implode('', self::$collection[$identifier]);
        }
        return null;
    }

    /**
     * @param $identifier
     * @param bool $raw
     * @return mixed|null|string
     */
    public static function end($identifier, $raw = false)
    {
        $result = self::get($identifier, $raw);
        self::flush($identifier);
        return $result;
    }
}
