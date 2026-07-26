<?php
    session_start();

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
    <script src="actions.js"></script>
    <link rel="stylesheet" href="styles.css">
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