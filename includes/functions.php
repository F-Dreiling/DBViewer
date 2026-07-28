<?php
    function redirect(): void {
        header("Location: index.php");
        exit;
    }

    function clearConnection(): void {
        unset(
            $_SESSION['host'],
            $_SESSION['port'],
            $_SESSION['dbName'],
            $_SESSION['table'],
            $_SESSION['userName'],
            $_SESSION['passWord'],
            $_SESSION['cert'],
            $_SESSION['load']
        );
    }

    function setError(string $message): void {
        $_SESSION['error'] = $message;
        unset($_SESSION['success']);
    }

    function setSuccess(string $message): void {
        $_SESSION['success'] = $message;
        unset($_SESSION['error']);
    }

    function getBackendUrl(string $endpoint): string {
        return
            ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http" )
            . "://" 
            . $_SERVER['HTTP_HOST'] 
            . dirname( $_SERVER['PHP_SELF'] ) 
            . "/server/server.php/" 
            . $endpoint;
    }

    function getConnectionParams(): array {
        return [
            'host'  => $_SESSION['host'],
            'port'  => $_SESSION['port'],
            'db'    => $_SESSION['dbName'],
            'table' => $_SESSION['table'],
            'user'  => $_SESSION['userName'],
            'pass'  => $_SESSION['passWord'],
            'cert'   => $_SESSION['cert']
        ];
    }

    function getCertificates(): array {
        $directory = __DIR__ . '/../certs';

        if ( !is_dir($directory) ) {
            return [];
        }

        $files = array_filter( scandir($directory), function ($file) use ($directory) {
            return is_file($directory . '/' . $file);
        } );

        return array_values(array_filter( $files, fn($file) =>
            str_ends_with($file, ".pem") ||
            str_ends_with($file, ".crt") ||
            str_ends_with($file, ".cer")
        ) );
    }

    function handleSubmit(): void {
        if ( !isset( $_POST['submit'] ) ) {
            return;
        }

        if ( !empty( trim($_POST['host']) ) && !empty( trim($_POST['port']) ) 
                && !empty( trim($_POST['dbName']) ) && !empty( trim($_POST['table']) ) 
                && !empty( trim($_POST['userName']) ) ) {

            $_SESSION['host'] = $_POST['host'];
            $_SESSION['port'] = $_POST['port'];
            $_SESSION['dbName'] = $_POST['dbName'];
            $_SESSION['table'] = $_POST['table'];
            $_SESSION['userName'] = $_POST['userName'];
            $_SESSION['passWord'] = $_POST['passWord'] ?? "";
            $_SESSION['cert'] = $_POST['cert'] ?? "";
            $_SESSION['load'] = true;
        }
        else {
            setError( "Missing host, port, database name, table or username" );
            unset($_SESSION['load']);
        }

        redirect();
    }

    function handleRefresh(): void {
        if ( !isset( $_POST['refresh'] ) ) {
            return;
        }

        if ( !empty( trim($_POST['table']) ) ) {
            $_SESSION['table'] = $_POST['table'];
            $_SESSION['load'] = true;
        }
        else {
            setError( "Missing table name" );
            clearConnection();
        }

        redirect();
    }

    function handleBack(): void {
        if ( !isset($_POST['back']) ) {
            return;
        }

        unset($_SESSION['load']);

        redirect();
    }

    function handleReset(): void {
        if ( !isset($_POST['reset']) ) {
            return;
        }

        $_SESSION = [];
        session_destroy();

        redirect();
    }

    function fetchTableData(): ?array {
        $url = getBackendUrl("getall");
        $url .= '?' . http_build_query(getConnectionParams());

        try {
            $response = file_get_contents($url);

            $tableData = json_decode($response, true);

            if ( $tableData === null ) {
                throw new Exception("Invalid JSON received from server");
            }

            if ( isset($tableData['error']) ) {
                throw new Exception($tableData['error']);
            }

            setSuccess( "Fetched data from the database successfully" );

            return $tableData;
        }
        catch (Exception $e) {
            setError($e->getMessage());
            clearConnection();

            return null;
        }
    }
?>