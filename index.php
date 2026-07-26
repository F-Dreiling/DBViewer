<?php
    session_start();

    /*
    |--------------------------------------------------------------------------
    | Static file handling for Choreo
    |--------------------------------------------------------------------------
    */

    $uri = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
    $uri = trim($uri, '/');

    if ( str_starts_with($uri, 'css/') || str_starts_with($uri, 'js/') ) {
        $file = __DIR__ . '/' . $uri;

        if (file_exists($file)) {

            $mimeTypes = [
                'css' => 'text/css',
                'js'  => 'application/javascript'
            ];

            $extension = pathinfo($file, PATHINFO_EXTENSION);

            header(
                'Content-Type: ' .
                ($mimeTypes[$extension] ?? 'application/octet-stream')
            );

            readfile($file);
            exit;
        }
    }

    require_once 'includes/functions.php';

    handleSubmit();
    handleRefresh();
    handleReset();

    $tableData = null;

    if ( isset($_SESSION['load']) ) {
        $tableData = fetchTableData();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>let sessionData = <?php echo json_encode($_SESSION); ?>;</script>
    <script src="/js/actions.js"></script>
    <link rel="stylesheet" href="/css/styles.css">
    <title>DBViewer</title>
</head>
<body>
    <div id="content" class="dbv-container">
        <h1 class="dbv-title">DBViewer</h1>

        <?php 
            if (isset($_SESSION['error'])) {
                echo "<p class='dbv-error'>" . htmlentities($_SESSION['error']) . "</p>";
                unset($_SESSION['error']);
            }

            if (isset($_SESSION['success'])) {
                echo "<p class='dbv-success'>" . htmlentities($_SESSION['success']) . "</p>";
                unset($_SESSION['success']);
            }
        ?>

        <?php 
            if (isset($_SESSION['load'])) {
                require 'pages/table.php';
            }
            else {
                require 'pages/connect.php';
            }
        ?>
    </div>
</body>
</html>