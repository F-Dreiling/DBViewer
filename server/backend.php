<?php

require_once 'data.php';

class Backend {
    private $connection;
    private $data;

    function connect($host, $port, $dbName, $user, $pass) {
        // Create connection
        $this->connection = new PDO("mysql:host=$host;port=$port;dbname=$dbName", $user, $pass);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check connection
        if ($this->connection->errorCode() > 0) {
            throw new PDOException("Connection failed with error code " . $this->connection->errorCode());   
        }

        $this->data = new Data();
    }

    function fetchOne($table, $key, $id) {
        $this->data->tableName = $table;

        // Check if table exists
        $this->checkTableExists($table);

        // Fetch data from the table
        $stmt = $this->connection->query("SELECT * FROM $table WHERE $key = $id LIMIT 1");

        // Get Data
        $this->populateData($stmt);
    }

    function fetchAll($table) {
        $this->data->tableName = $table;

        // Check if table exists
        $this->checkTableExists($table);

        // Fetch data from the table
        $stmt = $this->connection->query("SELECT * FROM $table");

        // Get Data
        $this->populateData($stmt);
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
        return json_encode($this->data->jsonSerialize());
    }

    private function checkTableExists($table)
    {
        $stmt = $this->connection->prepare(
            "SHOW TABLES LIKE :table"
        );

        $stmt->bindParam(':table', $table);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            throw new PDOException(
                "Table $table does not exist in the database"
            );
        }
    }

    private function getColumnNames(PDOStatement $stmt)
    {
        $columnNames = [];

        for ($i = 0; $i < $stmt->columnCount(); $i++) {
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