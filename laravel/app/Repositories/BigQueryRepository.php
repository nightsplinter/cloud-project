<?php

namespace App\Repositories;

use Google\Cloud\BigQuery\BigQueryClient;
use Google\Cloud\BigQuery\QueryResults;
use RuntimeException;

class BigQueryRepository
{
    protected string $table;
    protected BigQueryClient $bigQuery;
    protected string $dataset;

    public function __construct(string $tableName)
    {
        $this->table = $tableName;
        $dataset = config('bigquery.dataset');
        if (!is_string($dataset)) {
            throw new RuntimeException('BigQuery dataset must be a string');
        }
        $this->dataset = $dataset;

        $this->bigQuery = new BigQueryClient([
            'keyFilePath' => storage_path('../recipe-hub-448420-5e86d58492de.json'),
            'projectId' => config('bigquery.project_id'),
            'location' => config('bigquery.location')
        ]);
    }

    public function findWithPantryMatches(): QueryResults
    {
        // This is a placeholder for the actual query
        $query = $this->bigQuery->query(sprintf(
            "SELECT * FROM `%s.%s` LIMIT 1",
            $this->dataset,
            $this->table
        ));

        return $this->bigQuery->runQuery($query);
    }

    public function findRecipeById(String $id): QueryResults
    {
        // This is a placeholder for the actual query
        $query = $this->bigQuery->query(sprintf(
            "SELECT * FROM `%s.%s` WHERE id = %d LIMIT 1",
            $this->dataset,
            $this->table,
            $id
        ));

        return $this->bigQuery->runQuery($query);
    }

    /**
     * Find pantry item by id with ingredient matches
     *
     * @param int $id
     * @return QueryResults
     */
    public function findPantryItem(int $id): QueryResults
    {
        // This is a placeholder for the actual query
        $query = $this->bigQuery->query(sprintf(
            "SELECT * FROM `%s.%s` WHERE id = %d LIMIT 1",
            $this->dataset,
            $this->table,
            $id
        ));

        return $this->bigQuery->runQuery($query);
    }
}
