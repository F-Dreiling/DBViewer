<?php 
    /** @var array $tables */
    /** @var array $tableData */
    /** @var array $tableRows */    
?>

<div class="dbv-card">

<?php if ( !empty($_SESSION['query']) ): ?>

    <h3>Query:</h3>
    <pre class="dbv-query"><?= htmlentities($_SESSION['query']) ?></pre>

<?php else: ?>

    <h3>Table: <?= htmlentities(array_key_first($tableData)) ?></h3>

<?php endif; ?>

<?php if (empty($tableRows)): ?>

    <p>No data available.</p>

<?php else: ?>

    <?php
        $columnNames = array_keys($tableRows[0]);
        $rowCount = count($tableRows);
        $columnCount = count($columnNames);
        $key = $columnNames[0];
    ?>

    <div class="dbv-meta">
        <div class="dbv-meta-item">
            <span class="dbv-meta-label">Rows</span>
            <span class="dbv-meta-value"><?= $rowCount ?></span>
        </div>

        <div class="dbv-meta-item">
            <span class="dbv-meta-label">Columns</span>
            <span class="dbv-meta-value"><?= $columnCount ?></span>
        </div>
    </div>

    <div class="dbv-table-wrapper">
        <table class="dbv-table">
            <thead>
                <tr>

                <?php foreach ($columnNames as $column): ?>
                    <th><?= htmlentities($column) ?></th>
                <?php endforeach; ?>
                
                </tr>
            </thead>

            <tbody>
            <?php foreach ($tableRows as $row): ?>
                <tr onclick="clickRow('<?= htmlentities($key) ?>', <?= json_encode($row[$key]) ?>)">

                <?php foreach ($columnNames as $column): ?>
                    <td><?= htmlentities((string)$row[$column]) ?></td>
                <?php endforeach; ?>

                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
    </div>

<?php endif; ?>

</div>

<div class="dbv-toolbar">
    <div class="dbv-toolbar-left">
        <form action="index.php" method="POST">
            <button type="submit" name="reset" class="dbv-button dbv-button-secondary">&lt;&lt; Reset</button>
        </form>

        <form action="index.php" method="POST">
            <button type="submit" name="back" class="dbv-button dbv-button-secondary">&lt; Back</button>
        </form>
    </div>

    <div class="dbv-toolbar-right">
        <form action="index.php" method="POST">
            <label for="table">Table:</label>
            <select name="table" id="table" class="dbv-select">
                <option></option>

            <?php foreach ($tables as $table): ?>

                <option value="<?= htmlentities($table) ?>"
                    <?= $_SESSION['table'] === $table ? 'selected' : '' ?>>
                    <?= htmlentities($table) ?>
                </option>

            <?php endforeach; ?>

            </select>
            <input type="submit" name="refresh" class="dbv-button" value="< Load">
        </form>

        <input onclick="openQuery()" type="button" class="dbv-button" value="SQL Query">

        <input onclick="printJson()" type="button" class="dbv-button" value="Print JSON">
    </div>

</div>