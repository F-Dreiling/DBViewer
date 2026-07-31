<?php

require_once 'data.php';

class Backend {
    private $connection;
    private $data;
    private $tables = [];

    function connect($host, $port, $dbName, $user, $pass, $cert = ''): array {
        try {
            // Create connection string
            $connectionString = "mysql:host=$host;port=$port;dbname=$dbName";
            
            // Enable SSL if a certificate is provided
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];

            if ( !empty($cert) ) {
                $options[PDO::MYSQL_ATTR_SSL_CA] = __DIR__ . "/../certs/" . basename($cert);
            }

            // Create PDO connection
            $this->connection = new PDO( $connectionString, $user, $pass, $options );

            $this->data = new Data();

            $result = $this->fetchTables();

            if ( !$result["success"] ) {
                return $result;
            }

            return [
                "success" => true
            ];
        }
        catch (PDOException $e) {
            return [
                "success" => false,
                "error" => $e->getMessage()
            ];
        }
    }

    function fetchAll($table): array {
        try {
            // Validate table names
            $table = $this->validateIdentifier($table);

            // Check if table exists
            $this->checkTableExists($table);

            // Fetch data from the table
            $stmt = $this->connection->query( "SELECT * FROM `$table` LIMIT 500" );

            $this->populateData($stmt, $table);

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

    function fetchOne($table, $key, $id): array {
        try {
            // Validate table names
            $table = $this->validateIdentifier($table);
            $key = $this->validateIdentifier($key);

            // Check if table exists
            $this->checkTableExists($table);

            // Fetch data from the table
            $stmt = $this->connection->prepare( "SELECT * FROM `$table` WHERE `$key` = :id LIMIT 1" );

            $stmt->bindValue(':id', $id);

            $stmt->execute();

            $this->populateData($stmt, $table);

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

    function fetchQuery($query): array {
        try {            
            // Fetch data from the table
            $stmt = $this->connection->query( $query );

            $this->populateData($stmt, "Query Result");

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

    function fetchTables(): array {
        try {
            // Fetch all table names
            $stmt = $this->connection->query( "SHOW TABLES" );
            $this->tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

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

    function getTables(): array {
        return $this->tables;
    }

    function getFirstTable(): string {
        return $this->tables[0] ?? '';
    }

    function getData(): array {
        return [
            "tables" => $this->tables,
            "data"   => $this->data->jsonSerialize()
        ];
    }

    private function validateIdentifier($identifier): string {
        if ( !preg_match('/^[a-zA-Z0-9_]+$/', $identifier) ) {
            throw new Exception(
                "Invalid database/table identifier"
            );
        }

        return $identifier;
    }

    private function checkTableExists($table): void {
        $stmt = $this->connection->prepare( "SHOW TABLES LIKE :table" );

        $stmt->bindParam(':table', $table);
        $stmt->execute();

        if ( $stmt->rowCount() === 0 ) {
            throw new Exception(
                "Table $table does not exist in the database"
            );
        }
    }

    private function populateData(PDOStatement $stmt, string $tableName): void {
        $this->data->tableName = $tableName;
        $this->data->tableData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>