<?php

namespace sagoe1712\Foundation;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Logging\DebugStack;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Connection;

class DatabaseConnection
{
    /**
     * @var array $connectionParams An array of connection parameters.
     */
    private $connectionParams;


    /**
     * @var Connection $connection A database connection.
     */
    private $connection;


    /**
     * @var bool Whether or not database profiling should be enabled.
     */
    private $profiling_enabled = false;


    /**
     * @var array $databaseAliases An array of database aliases, in the format of: 'alias' => 'name'.
     */
    protected $databaseAliases;


    /**
     * @var string $databaseName The database name.
     */
    protected $databaseName;


    /**
     * Constructor to collect required database credentials.
     *
     * @param string $host The hostname.
     * @param string $username The database username.
     * @param string $password The database password.
     * @param string $database The name of the database.
     * @param string $driver The database driver.
     */
    public function __construct($host, $username, $password, $database, $driver = 'pdo_mysql')
    {
        $this->connectionParams = array(
            'dbname' => $database,
            'user' => $username,
            'password' => $password,
            'host' => $host,
            'driver' => $driver
        );
    }


    /**
     * Switch off profiling on the database connection.
     */
    public function disableProfiling()
    {
        $this->profiling_enabled = false;
    }


    /**
     * Switch on profiling on the database connection.
     */
    public function enableProfiling()
    {
        $this->profiling_enabled = true;
    }


    /**
     * Returns the connection object.
     *
     * @return Connection
     */
    public function getConnection()
    {
        if (!isset($this->connection)) {
            $config = new Configuration();

            $logger = new DebugStack();

            $logger->enabled = $this->profiling_enabled;

            $config->setSQLLogger($logger);

            $this->connection = DriverManager::getConnection($this->connectionParams, $config);
        }

        return $this->connection;
    }


    /**
     * Return information about the last exectuted query, if logging is enabled.
     *
     * @return SQLLogger|null output from the SQLLogger
     */
    public function getProfilingData()
    {
        return $this->getConnection()->getConfiguration()->getSQLLogger();
    }


    /**
     * Returns a Doctrine query builder object.
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        $connection = $this->getConnection();

        return $connection->createQueryBuilder();
    }


    /**
     * @param array $aliases An array of database aliases, in the format of: 'alias' => 'name'.
     */
    public function setDatabaseAliases(array $aliases)
    {
        $this->databaseAliases = $aliases;
    }


    /**
     * @param string $alias The database alias.
     * @return string The database name.
     */
    public function getDatabaseName($alias)
    {
        if (array_key_exists($alias, $this->databaseAliases)) {
            return $this->databaseAliases[$alias];
        } else {
            return $alias;
        }
    }
}
