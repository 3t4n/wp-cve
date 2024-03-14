<?php

namespace HQRentalsPlugin\HQRentalsDb;

class HQRentalsDbManager
{
    protected $db;
    protected $charset;
    protected $dbPrefix;
    private $dbName;

    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->charset = $wpdb->get_charset_collate();
        $this->dbPrefix = $wpdb->get_blog_prefix();
        $this->dbName = $wpdb->__get('dbname');
    }
    public function createTable($tableName, $tableContent): \stdClass
    {
        $sqlQuery = $this->resolveCreateStatementString($tableName, $tableContent);
        return $this->query($sqlQuery);
    }

    /*
     * Changes on First DB -> Updates
     * */
    public function updateTableOnChanges($tableName, $tableContent): array
    {
        $results = [];
        foreach ($tableContent as $column) {
            // check if exists
            $exitsSQL = $this->resolveColumnCheckStatementString($tableName, $column['column_name']);
            $resultExists = $this->query($exitsSQL);
            if (empty($resultExists->data)) {
                $sqlAlterTable = $this->resolveAlterTableStatementString($tableName, $column['column_name'], $column['column_data_type']);
                $results[] = $this->db->query($sqlAlterTable);
            } else {
                $results[] = $this->query("");
            }
        }
        return $results;
    }

    public function resolveAlterTableStatementString($table, $columnName, $columnType)
    {
        return $this->db->prepare(
            "ALTER TABLE {$this->dbPrefix}{$table} ADD {$columnName} {$columnType} NULL"
        );
    }


    public function checkColumnOnDB($tableName, $column): \stdClass
    {
        $sqlQuery = $this->resolveColumnCheckStatementString($tableName, $column);
        return $this->query($sqlQuery);
    }

    public function selectFromTable($tableName, $columns, $where = null, $order = null): \stdClass
    {
        $sqlQuery = $this->resolveSelectStatementString($tableName, $columns, $where, $order);
        return $this->getResults($sqlQuery);
    }

    public function innerJoinTable($tableOne, $tableTwo, $tableOneComparison, $tableTwoComparison, $order): \stdClass
    {
        $sqlQuery = $this->resolveJoinStatement($tableOne, $tableTwo, $tableOneComparison, $tableTwoComparison, $order);
        return $this->getResults($sqlQuery);
    }

    public function insertIntoTable($tableName, $columnData): \stdClass
    {
        return $this->insert($tableName, $columnData);
    }

    public function alterTable($tableName, $columns): \stdClass
    {
        $sqlQuery = $this->resolveAlterStatementString($tableName, $columns);
        return $this->query($sqlQuery);
    }

    public function updateIntoTable($tableName, $columnData, $where): \stdClass
    {
        return $this->update($tableName, $columnData, $where);
    }

    private function resolveCreateStatementString($tableName, $tableContent): string
    {
        $columns = $this->resolveTableContentForQuery($tableContent);
        return $this->db->prepare(
            'CREATE TABLE IF NOT EXISTS ' . $this->dbPrefix . $tableName . ' (
                  ' . $columns . '
                ) ' . $this->charset . ';'
        );
    }
    private function resolveJoinStatement($tableOne, $tableTwo, $tableOneComparison, $tableTwoComparison, $order = []): string
    {
        $orderSQL = "";
        foreach ($order as $index => $orderItem) {
            $table = $this->resolveTableName($orderItem['table']);
            $end = ((count($order) - 1) === $index) ? "" : ",";
            $orderSQL .= $table . "." . $orderItem['column'] . " " . $orderItem['direction'] . $end;
        }
        return $this->db->prepare(
            "select * from {$this->resolveTableName($tableOne)} 
                    inner join {$this->resolveTableName($tableTwo)} on {$this->resolveTableName($tableOne)}.{$tableOneComparison} = {$this->resolveTableName($tableTwo)}.{$tableTwoComparison} 
                    order by ${orderSQL};"
        );
    }

    private function resolveSelectStatementString($tableName, $tableColumns, $where, $order): string
    {
        $whereClause = ((!empty($where)) ? ' WHERE ' . $where : '');
        if (is_array($tableColumns)) {
            return $this->db->prepare(
                'SELECT ' . join(',', $tableColumns) . ' FROM ' . $this->dbPrefix . $tableName . $whereClause . ' ' . $order . ';'
            );
        } else {
            // no need to prep
            return 'SELECT ' . $tableColumns . ' FROM ' . $this->dbPrefix . $tableName . $whereClause . ' ' . $order . ';';
        }
    }

    private function resolveAlterStatementString($tableName, $tableColumns): string
    {
        $columns = '';
        if (is_array($tableColumns)) {
            foreach ($tableColumns as $key => $value) {
                $columns .= $value . ' VARCHAR(255) NULL DEFAULT ""';
                $columns .= ((int)($key + 1) == count($tableColumns)) ? "" : ",";
            }
        } else {
            $columns = $tableColumns;
        }
        return $this->db->prepare(
            'ALTER TABLE ' . $this->dbPrefix . $tableName . ' ADD COLUMN ' . $columns . ' VARCHAR(255) NULL DEFAULT "";'
        );
    }

    private function resolveColumnCheckStatementString($table, $column)
    {
        return $this->db->prepare(
            'SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = "' . $this->dbPrefix . $table . '" AND TABLE_SCHEMA="' . $this->dbName . '" AND column_name = "' . $column . '";'
        );
    }

    private function resolveTableContentForQuery($arrayWithColumns): string
    {
        $sqlString = '';
        if (is_array($arrayWithColumns)) {
            foreach ($arrayWithColumns as $key => $column) {
                $sqlString .= $column['column_name'] . ' ' . $column['column_data_type'] . ((count($arrayWithColumns) - 1 === $key) ? '' : ',');
            }
        }
        return $sqlString;
    }

    public function dropTable($tableName)
    {
        $sql = `DROP TABLE {$tableName}`;
        return $this->db->query($sql);
    }

    public function insert($tableName, $data): \stdClass
    {
        $results = $this->db->insert($this->resolveTableName($tableName), $data);
        if ($results) {
            return $this->resolveQuery(true, $results, null);
        } else {
            return $this->resolveQuery(false, null, $results);
        }
    }

    public function update($tableName, $data, $where): \stdClass
    {
        $results = $this->db->update($this->resolveTableName($tableName), $data, $where);
        if ($results) {
            return $this->resolveQuery(true, $results, null, $data);
        } else {
            return $this->resolveQuery(false, $results, 'ERROR', $data);
        }
    }

    public function delete($tableName, $data, $overrideIdStatement = null): \stdClass
    {
        $results = $this->db->delete($this->resolveTableName($tableName), empty($overrideIdStatement) ? array('id' => $data) : $overrideIdStatement);
        if ($results) {
            return $this->resolveQuery(true, $results, null, $data);
        } else {
            return $this->resolveQuery(false, $results, 'ERROR', $data);
        }
    }


    public function getTablesPrefix(): string
    {
        return $this->dbPrefix;
    }

    public function resolveTableName($table): string
    {
        return $this->dbPrefix . $table;
    }

    public function getResults($query): \stdClass
    {
        $result = $this->db->get_results($query, OBJECT);
        if ($result) {
            $data = $this->resolveQuery(true, $result, null, $query);
        } else {
            $data = $this->resolveQuery(false, $result, 'ERROR', $query);
        }
        return $data;
    }

    private function query($query)
    {
        $queryResult = $this->db->query($query);
        if ($queryResult) {
            $data = $this->resolveQuery(
                true,
                $queryResult,
                null,
                $query
            );
        } else {
            $data = $this->resolveQuery(
                false,
                null,
                'Error on Query',
                $query
            );
        }
        return $data;
    }

    private function resolveQuery($success, $dataToReturn, $errors, $query = null): \stdClass
    {
        $data = new \stdClass();
        $data->success = $success;
        $data->data = $dataToReturn;
        $data->errors = $errors;
        $data->query = $query;
        return $data;
    }
}
