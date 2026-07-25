<?php

require_once 'data.php';

class Backend {
    private $connection;
    private $data;

    function connect($host, $port, $dbName, $user, $pass) {
        try {
            // Create connection
            $this->connection = new PDO( "mysql:host=$host;port=$port;dbname=$dbName", $user, $pass );
            $this->connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

            $this->data = new Data();

            return [
                "success" => true
            ];
        }
        catch (PDOException $e) {
            return [
                "success" => false,
                "error" => "Database connection failed"
            ];
        }
    }

    function fetchOne($table, $key, $id) {
        try {
            // Validate table names
            $table = $this->validateIdentifier($table);
            $key = $this->validateIdentifier($key);

            $this->data->tableName = $table;

            // Check if table exists
            $this->checkTableExists($table);

            // Fetch data from the table
            $stmt = $this->connection->prepare( "SELECT * FROM `$table` WHERE `$key` = :id LIMIT 1" );

            $stmt->bindValue(':id', $id);

            $stmt->execute();

            // Get Data
            $this->populateData($stmt);

            return [
                "success" => true
            ];
        } 
        catch (Exception $e) {
            return [
                "success" => false,
                "error" => $e->getMessage()
            ];
        }
    }

    function fetchAll($table) {
        try {
            // Validate table names
            $table = $this->validateIdentifier($table);

            $this->data->tableName = $table;

            // Check if table exists
            $this->checkTableExists($table);

            // Fetch data from the table
            $stmt = $this->connection->query( "SELECT * FROM `$table`" );

            // Get Data
            $this->populateData($stmt);

            return [
                "success" => true
            ];
        }
        catch (Exception $e) {
            return [
                "success" => false,
                "error" => $e->getMessage()
            ];
        }
    }

    function renderHtml() {
        $result = "<h3>Table: ".$this->data->tableName."</h3>";
        $result .= "<p>Row Count: ".$this->data->rowCount."</p>";
        $result .= "<p>Column Count: ".$this->data->columnCount."</p>";

        $result .= "<table class='table table-bordered'>";
        $result .= "<thead><tr>";
        
        foreach ($this->data->columnNames as $column) {
            $result .= "<th>".htmlentities($column)."</th>";
        }

        $result .= "</tr></thead><tbody>";
        
        $key = $this->data->columnNames[0];

        foreach ($this->data->tableData as $row) {
            $result .= "<tr onclick=\"clickRow('".$key."', ".$row[$key].")\">";

            foreach ($row as $cell) {
                $result .= "<td>".htmlentities($cell)."</td>";
            }

            $result .= "</tr>";
        }

        $result .= "</tbody></table>";

        return $result;
    }

    function renderJson() {
        return json_encode( $this->data->jsonSerialize() );
    }

    private function validateIdentifier($identifier)
    {
        if ( !preg_match('/^[a-zA-Z0-9_]+$/', $identifier) ) {
            throw new Exception(
                "Invalid database identifier"
            );
        }

        return $identifier;
    }

    private function checkTableExists($table)
    {
        $stmt = $this->connection->prepare( "SHOW TABLES LIKE :table" );

        $stmt->bindParam(':table', $table);
        $stmt->execute();

        if ( $stmt->rowCount() === 0 ) {
            throw new Exception(
                "Table $table does not exist in the database"
            );
        }
    }

    private function getColumnNames(PDOStatement $stmt)
    {
        $columnNames = [];

        for ( $i = 0; $i < $stmt->columnCount(); $i++ ) {
            $meta = $stmt->getColumnMeta($i);
            $columnNames[] = $meta['name'];
        }

        return $columnNames;
    }

    private function populateData(PDOStatement $stmt)
    {
        $tableData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->data->columnCount = $stmt->columnCount();
        $this->data->columnNames = $this->getColumnNames($stmt);

        $this->data->tableData = $tableData;
        $this->data->rowCount = count($tableData);
    }
}

?>