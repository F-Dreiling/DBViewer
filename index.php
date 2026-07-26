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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <title>DBViewer</title>
</head>
<body>
    <div id="content" class="container d-flex flex-column align-items-center text-center mt-4 mb-2">
        <h1>Welcome to DBViewer</h1>

        <?php 
            if (isset($_SESSION['error'])) {
                echo "<p class='text-danger'>" . htmlentities($_SESSION['error']) . "</p>";
                unset($_SESSION['error']);
            }

            if (isset($_SESSION['success'])) {
                echo "<p class='text-success'>" . htmlentities($_SESSION['success']) . "</p>";
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