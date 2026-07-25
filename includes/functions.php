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
            'pass'  => $_SESSION['passWord']
        ];
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

    function handleReset(): void {
        if ( !isset($_POST['reset']) ) {
            return;
        }

        $_SESSION = [];
        session_destroy();

        redirect();
    }

    function fetchTableHtml(): string {
        $url = getBackendUrl("gethtml");

        $url .= '?' . http_build_query( getConnectionParams() );

        try {
            $response = file_get_contents($url);

            if ( $response === false || $response === "" ) {
                throw new Exception("Error fetching data from the server");
            }

            if ( substr($response, 0, 5) === "Error" ) {
                throw new Exception($response);
            }

            setSuccess("Fetched data from the database successfully");

            return $response;
        }
        catch (Exception $e) {
            setError( $e->getMessage() );

            clearConnection();

            return "";
        }
    }
?>