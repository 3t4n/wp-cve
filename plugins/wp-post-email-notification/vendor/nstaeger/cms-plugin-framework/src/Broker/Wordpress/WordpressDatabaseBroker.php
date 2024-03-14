<?php

namespace Nstaeger\CmsPluginFramework\Broker\Wordpress;

use Nstaeger\CmsPluginFramework\Broker\DatabaseBroker;
use Nstaeger\CmsPluginFramework\Database\DatabaseException;
use wpdb;

class WordpressDatabaseBroker implements DatabaseBroker
{
    /**
     * @var wpdb
     */
    private $databaseConnection;

    public function __construct()
    {
        $this->databaseConnection = $GLOBALS['wpdb'];
    }

    public function delete($table, array $where)
    {
        $result = $this->databaseConnection->delete($this->parsePrefix($table), $where);
        $this->wasGood($result);

        return $result;
    }

    public function executePreparedQuery($query, $args)
    {
        $prepared = $this->databaseConnection->prepare($query, $args);
        $parsed = $this->parsePrefix($prepared);
        $result = $this->databaseConnection->query($parsed);
        $this->wasGood($result);

        return $result;
    }

    public function executeQuery($query)
    {
        $parsed = $this->parsePrefix($query);
        $result =  $this->databaseConnection->query($parsed);
        $this->wasGood($result);

        return $result;
    }

    public function fetchAll($query)
    {
        return $this->databaseConnection->get_results($this->parsePrefix($query), ARRAY_A);
    }

    function getLastError()
    {
        return $this->databaseConnection->last_error;
    }

    function getLastInsertedId()
    {
        return $this->databaseConnection->insert_id;
    }

    function getLastQuery()
    {
        return $this->databaseConnection->last_query;
    }

    public function insert($table, array $data, $format = null)
    {
        $result = $this->databaseConnection->insert($this->parsePrefix($table), $data, $format);
        $this->wasGood($result);

        return $result;
    }

    public function update($table, array $data, array $where, $format = null, $where_format = null)
    {
        $result = $this->databaseConnection->update($this->parsePrefix($table), $data, $where, $format, $where_format);
        $this->wasGood($result);

        return $result;
    }

    private function parsePrefix($query)
    {
        return str_replace("@@", $this->databaseConnection->prefix, $query);
    }

    private function wasGood($result)
    {
        if ($result === false) {
            throw new DatabaseException($this->getLastError(), $this->getLastQuery());
        }
    }
}
