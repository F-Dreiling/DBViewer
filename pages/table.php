<?php 
    /** @var array $tableData */
    $tableName = array_key_first($tableData);
    $rows = $tableData[$tableName];
?>

<div class="w-100">
    <h3>Table: <?= htmlentities($tableName) ?></h3>

<?php if (empty($rows)): ?>

    <p>No data available.</p>

<?php else: ?>

    <?php
        $columnNames = array_keys($rows[0]);
        $rowCount = count($rows);
        $columnCount = count($columnNames);
        $key = $columnNames[0];
    ?>

    <p>Row Count: <?= $rowCount ?></p>
    <p>Column Count: <?= $columnCount ?></p>

    <table class="table table-bordered">
        <thead>
        <tr>
        <?php foreach ($columnNames as $column): ?>
            <th><?= htmlentities($column) ?></th>
        <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>

        <tr onclick="clickRow('<?= htmlentities($key) ?>', <?= json_encode($row[$key]) ?>)">

        <?php foreach ($columnNames as $column): ?>
            <td><?= htmlentities( (string)$row[$column] ) ?></td>
        <?php endforeach; ?>

        </tr>

        <?php endforeach; ?>
        </tbody>
    </table>

<?php endif; ?>

</div>

<div class="d-flex justify-content-between align-items-center gap-2 w-100">
    <form action="index.php" method="POST">
        <button type="submit" name="reset" class="btn btn-secondary">&lt;&lt; Reset</button>
    </form>

    <form action="index.php" method="POST">
        <label for="table">Table:</label>
        <input type="text" name="table" id="table" value="<?= htmlentities($_SESSION['table']); ?>" style="width: 33%">
        <input type="submit" class="btn btn-secondary" name="refresh" value="Refresh">
    </form>

    <input onclick='printJson()' type='button' class='btn btn-secondary' value='Print JSON'>
</div>