<?php

namespace MailOptin\Core\Connections;

class ConnectionFactory
{
    /**
     * Return instance of a connection object.
     *
     * @param string $connection
     *
     * @return ConnectionInterface|false
     */
    public static function make($connection)
    {
        /** @var ConnectionInterface $connectClass */
        $connectClass = self::get_fqn_class($connection);

        if (method_exists($connectClass, 'get_instance')) {
            return $connectClass::get_instance();
        }

        return false;
    }

    /**
     * @param $connection
     *
     * @return ConnectionInterface|string
     */
    public static function get_fqn_class($connection)
    {
        return "MailOptin\\$connection\\Connect";
    }

}