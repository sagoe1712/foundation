<?php

namespace sagoe1712\Foundation;

use Doctrine\DBAL;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use sagoe1712\Foundation\Collection;

/**
 * Class Mapper
 * @package sagoe1712\Foundation
 */
abstract class Mapper
{
    const SQL_INSERT = 0;


    const SQL_UPDATE = 1;


    /**
     * @var Connection $database A database connection.
     */
    protected $databaseConnection;


    /**
     * @var Collection\Factory A data collection factory.
     */
    protected $collectionFactory;


    /**
     * @param Connection $databaseConnection A database connection.
     * @param Collection\Factory $collectionFactory A data collection factory.
     */
    public function __construct(Connection $databaseConnection, Collection\Factory $collectionFactory)
    {
        $this->databaseConnection = $databaseConnection;
        $this->collectionFactory = $collectionFactory;
    }


    /**
     * @return QueryBuilder A query builder.
     */
    public function getQueryBuilder()
    {
        return $this->databaseConnection->createQueryBuilder();
    }


    /**
     * @param array $data An array of data.
     * @return Model A model object.
     */
    protected function instantiateModel(array $data)
    {
        $model = new \ReflectionClass($this->getModel());
        return $model->newInstanceArgs($data);
    }


    /**
     * Binds data to a collection.
     *
     * @param array $data An array of data.
     * @param Collection\Collection $collection The collection to bind to.
     * @return Collection\Collection The bound collection.
     */
    protected function bindToCollection($data, $collection)
    {
        foreach ($data as $row) {
            $collection[] = $this->instantiateModel($row);
        }

        return $collection;
    }


    /**
     * Returns the fields added to the querybuilder in the correct format.
     *
     * @param QueryBuilder $queryBuilder
     * @param array $fields
     * @param array $parameters
     * @param int $queryType
     * @return QueryBuilder
     */
    protected function fields(QueryBuilder $queryBuilder, array $fields, array $parameters, int $queryType): QueryBuilder
    {
        if ($queryType == self::SQL_INSERT) {
            $queryBuilder->values($fields);
        } else {
            foreach ($fields as $key => $value) {
                $queryBuilder->set($key, $value);
            }
        }

        $queryBuilder->setParameters($parameters);

        return $queryBuilder;
    }


    /**
     * Build a save query.
     *
     * @param QueryBuilder $queryBuilder
     * @param string $tableName
     * @param array $fields
     * @param array $parameters
     * @return QueryBuilder
     */
    protected function buildSave(QueryBuilder $queryBuilder, string $tableName, array $fields, array $parameters): QueryBuilder
    {
        $id = key_exists('id', $parameters) ? $parameters['id'] : null;
        $queryType = is_null($id) ? self::SQL_INSERT : self::SQL_UPDATE;

        if ($queryType == self::SQL_INSERT) {
            $queryBuilder->insert($tableName);
        } else {
            $queryBuilder->update($tableName);
        }

        $this->fields($queryBuilder, $fields, $parameters, $queryType);

        if ($queryType == self::SQL_UPDATE && !is_null($id)) {
            $queryBuilder->where('id = :id');
        }

        return $queryBuilder;
    }


    /**
     * @return string The class name to map to.
     */
    abstract protected function getModel();


    /**
     * Get a query builder which includes the required SELECT statements for the object.
     *
     * @return QueryBuilder
     */
    abstract protected function getBaseQuery();
}
