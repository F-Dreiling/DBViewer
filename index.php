<?php
    session_start();

    require_once 'includes/functions.php';

    handleSubmit();
    handleQuery();
    handleRefresh();
    handleBack();
    handleReset();

    $tables = [];
    $tableData = [];
    $tableRows = [];

    if ( isset($_SESSION['load']) ) {
        $tablesAndData = fetchTableData();

        if ( $tablesAndData !== null ) {
            $tables = $tablesAndData['tables'];
            $tableData = $tablesAndData['data'];
            $tableRows = $tableData[array_key_first($tableData)];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php readfile(__DIR__ . '/css/styles.css'); ?>
    </style>
    <script>
        let tableData = <?= json_encode($tableData ?? []) ?>;
        let tableRows = <?= json_encode($tableRows ?? []) ?>;
        <?php readfile(__DIR__ . '/js/actions.js'); ?>
    </script>
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