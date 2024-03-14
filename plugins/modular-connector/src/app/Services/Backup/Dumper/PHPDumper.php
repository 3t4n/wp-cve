<?php

namespace Modular\Connector\Services\Backup\Dumper;

use Modular\Connector\Services\Dumper\Provider;
use Modular\ConnectorDependencies\Ifsnop\Mysqldump\Mysqldump;

class PHPDumper
{
    /**
     * @var \mysqli|mixed
     */
    protected $connection;

    /**
     * @var string
     */
    protected string $charset = Mysqldump::UTF8MB4;

    /**
     * @var string
     */
    protected string $host = 'localhost';

    /**
     * @var int
     */
    protected ?int $port = null;

    /**
     * @var string
     */
    protected string $database;

    /**
     * @var string
     */
    protected string $username = '';

    /**
     * @var string
     */
    protected string $password = '';

    /**
     * @var object
     */
    protected object $provider;

    /**
     * @var bool
     */
    protected bool $conditionSomeTables = false;

    /**
     * @var array
     */
    protected array $conditionTables = [];

    /**
     * @var bool
     */
    protected bool $includeSomeTableLimits = false;

    /**
     * @var array
     */
    protected array $limitTables = [];

    /**
     * @var string
     */
    protected string $contents = '';

    /**
     * @var bool
     */
    protected bool $includeSomeTables = false;

    /**
     * @var array
     */
    protected array $includeTables = [];

    /**
     * @var bool
     */
    protected bool $excludeSomeTables = false;

    /**
     * @var array
     */
    protected array $excludeTables = [];

    /**
     * @var array
     */
    protected array $tables = [];

    /**
     * Get the instance of the class
     *
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param string $host
     * @return $this
     */
    public function setHost(string $host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @param int $port
     * @return $this
     */
    public function setPort(int $port = 3306)
    {
        if($port === 3306) {
            $port = null;
        }

        $this->port = $port;

        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setDatabase(string $name)
    {
        $this->database = $name;

        return $this;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get provider instance
     *
     * @return Provider
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @return Mysqldump
     * @throws \Exception
     */
    protected function dumper()
    {
        $host = !is_null($this->port) ? "{$this->host}:{$this->port}" : $this->host;

        return new Mysqldump(
            "mysql:host={$host};dbname={$this->database}",
            $this->username,
            $this->password,
            [
                'add-drop-table' => true,
                'skip-comments' => true,
                'default-character-set' => $this->charset,
                'include-tables' => $this->tables,
            ]
        );
    }

    /**
     * Connect to database
     *
     * @return \mysqli
     */
    protected function connectToDatabase()
    {
        if (!empty($this->connection)) {
            return $this->connection;
        }

        $this->connection = new \mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->database,
            $this->port
        );

        if ($this->connection->connect_error) {
            throw new \RuntimeException('Failed to connect to database: ' . $this->connection->connect_error);
        }

        $charset = $this->connection->get_charset();

        if (!empty($charset->charset)) {
            $this->charset = $charset->charset;
        }

        return $this->connection;
    }

    /**
     * Get the database tables
     * @return array
     */
    protected function getTargetTables()
    {
        /**
         * @var \mysqli
         */
        $mysqli = $this->connection;
        $mysqli->query("SET NAMES 'utf8'");

        $tables = [];

        $query = $mysqli->query('SHOW TABLES');

        while ($row = $query->fetch_row()) {
            $tables[] = $row[0];
        }

        return $tables;
    }

    /**
     * Include only the tables mentioned in $tables
     *
     * @return $this
     * @throws \Exception
     * @var array $tables
     */
    public function includeTables(array $tables = [])
    {
        if (!empty($this->excludeSomeTables)) {
            throw new \Exception('Cannot set `includeTables` because it conflicts with parameter `excludeTables`.');
        }

        $this->includeSomeTables = true;
        $this->includeTables = $tables;

        return $this;
    }

    /**
     * Exclude only the tables mentioned in @param array $tables
     *
     * @return $this
     * @throws \Exception
     * @var $tables
     */
    public function excludeTables(array $tables = [])
    {
        if (!empty($this->includeSomeTables)) {
            throw new \Exception('Cannot set `excludeTables` because it conflicts with parameter `includeTables`.');
        }

        $this->excludeSomeTables = true;
        $this->excludeTables = $tables;

        return $this;
    }

    /**
     * Sets SQL like where clauses on tables before export
     *
     * @param array $tables
     *
     * @return $this
     */
    public function conditionTables(array $tables = [])
    {
        $this->conditionSomeTables = true;
        $this->conditionTables = $tables;

        return $this;
    }

    /**
     * Sets limits on tables before export
     *
     * @param array $tables
     *
     * @return $this
     */
    public function limitTables(array $tables = [])
    {
        $this->includeSomeTableLimits = true;
        $this->limitTables = $tables;

        return $this;
    }

    /**
     * @return void
     */
    protected function guardAgainstIncompleteCredentials()
    {
        foreach (['username', 'host', 'database'] as $requiredProperty) {
            if (\strlen($this->{$requiredProperty}) === 0) {
                throw \Exception("Parameter `{$requiredProperty}` cannot be empty.");
            }
        }
    }


    /**
     * Guard against empty tables
     *
     * @return $this
     * @throws \Exception
     */
    protected function guardAgainstEmptyTables()
    {
        $tables = $this->getTargetTables();

        if ($this->includeSomeTables) {
            $tables = array_values(array_filter($this->includeTables, fn($table) => in_array($table, $tables)));
        } else if ($this->excludeSomeTables) {
            $tables = array_values(array_filter($tables, fn($table) => !in_array($table, $this->excludeTables)));
        }

        $this->tables = $tables;

        if (empty($this->tables)) {
            throw new \Exception("No tables found on `{$this->database}");
        }

        return $this;
    }

    /**
     * Build the sql pre_insert_statements to export
     * @return $this
     * @throws \Exception
     */
    protected function prepareExportContentsFrom($path)
    {
        try {
            $this->provider = $this->dumper();

            if ($this->conditionSomeTables && !empty($this->conditionTables)) {
                $this->provider->setTableWheres($this->conditionTables);
            }

            if ($this->includeSomeTableLimits && !empty($this->limitTables)) {
                $this->provider->setTableLimits($this->limitTables);
            }

            $this->provider->start($path);
        } catch (\Exception $e) {
            if (file_exists($path)) {
                unlink($path);
            }

            throw $e;
        }

        return $this;
    }

    /**
     * This method allows you store the exported db to a directory
     *
     * @param $path
     *
     * @return $this
     * @throws \Exception
     */
    public function dumpToFile($path)
    {
        $this->guardAgainstIncompleteCredentials();

        $this->connectToDatabase();

        $this->guardAgainstEmptyTables();

        $this->prepareExportContentsFrom($path);

        return $this;
    }

    /**
     * Create dump
     *
     * @param string $path
     * @param string $host
     * @param int $port
     * @param string $database
     * @param string $username
     * @param string $password
     * @param array $excluded
     * @return void
     * @throws \Exception
     */
    public static function dump(string $path, array $connection, array $excluded)
    {
        $database = $connection['database'];
        $username = $connection['username'];
        $password = $connection['password'];

        $host = $connection['host'];
        $port = $connection['port'];

        static::create()
            ->setHost($host)
            ->setPort($port)
            ->setDatabase($database)
            ->setUsername($username)
            ->setPassword($password)
            ->excludeTables($excluded)
            ->dumpToFile($path);
    }
}
