<?php 
    $certificates = getCertificates();
    $sslEnabled = !empty($_SESSION['cert']);
?>

<div class="dbv-card">
    <p class="dbv-warning">Please enter the database and table name, username and optionally password.</p>

    <form action="index.php" method="POST" class="dbv-form">
        <div class="dbv-grid">
            <div class="dbv-field">
                <label for="host">Host:</label>
                <input type="text" name="host" id="host" class="dbv-input" value="<?= htmlentities($_SESSION['host'] ?? 'localhost') ?>">
            </div>
            <div class="dbv-field">
                <label for="port">Port:</label>
                <input type="text" name="port" id="port" class="dbv-input" value="<?= htmlentities($_SESSION['port'] ?? '3306') ?>">
            </div>
        </div>

        <div class="dbv-grid">
            <div class="dbv-field">
                <label for="userName">User Name:</label>
                <input type="text" name="userName" id="userName" class="dbv-input" value="<?= htmlentities($_SESSION['userName'] ?? '') ?>" placeholder="Enter your username">
            </div>
            <div class="dbv-field">
                <label for="passWord">Password:</label>
                <input type="text" name="passWord" id="passWord" class="dbv-input" value="<?= htmlentities($_SESSION['passWord'] ?? '') ?>" placeholder="Enter your password">
            </div>
        </div>

        <div class="dbv-grid">
            <div class="dbv-field">
                <label for="dbName">DB Name:</label>
                <input type="text" name="dbName" id="dbName" class="dbv-input" value="<?= htmlentities($_SESSION['dbName'] ?? '') ?>" placeholder="Enter the database">
            </div>
            <div class="dbv-field">
                <label for="table">Table:</label>
                <input type="text" name="table" id="table" class="dbv-input" value="<?= htmlentities($_SESSION['table'] ?? '') ?>" placeholder="Enter the table">
            </div>
        </div>

        <div class="dbv-grid">
            <div class="dbv-toggle">
                <label class="dbv-switch">
                    <input type="checkbox" id="sslToggle" <?= $sslEnabled ? 'checked' : '' ?> onchange="toggleSsl()">
                    <span class="dbv-slider"></span>
                </label>

                <span>Use SSL Certificate</span>
            </div>

            <div id="sslContainer" class="<?= $sslEnabled ? '' : 'dbv-hidden' ?>">
                <label for="cert">Certificate:</label>

                <select name="cert" id="cert" class="dbv-select">
                    <option value="">Select certificate...</option>

                    <?php foreach ($certificates as $certificate): ?>

                        <option value="<?= htmlentities($certificate) ?>" <?= ($_SESSION['cert'] ?? '') === $certificate ? 'selected' : '' ?>>
                            <?= htmlentities($certificate) ?>
                        </option>

                    <?php endforeach; ?>

                </select>
            </div>
        </div>

        <div class="dbv-spacer"></div>

        <input type="submit" class="dbv-button dbv-w-100" name="submit" value="Submit">
    </form>
</div>