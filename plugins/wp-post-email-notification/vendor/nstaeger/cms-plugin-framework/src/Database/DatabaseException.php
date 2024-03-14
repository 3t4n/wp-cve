<?php

namespace Nstaeger\CmsPluginFramework\Database;

use Exception;
use RuntimeException;

class DatabaseException extends RuntimeException
{
    /**
     * @var mixed
     */
    private $query;

    /**
     * DatabaseErrorException constructor.
     *
     * @param string         $errorMessage
     * @param int            $query
     * @param Exception|null $previous
     * @param int            $code
     */
    public function __construct($errorMessage, $query, Exception $previous = null, $code = 0)
    {
        parent::__construct($errorMessage, $code, $previous);

        $this->query = $query;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }
}
