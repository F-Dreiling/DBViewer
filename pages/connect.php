<?php ?>

<div class="dbv-card">
    <p class="dbv-warning">Please enter the database and table name, username and optionally password.</p>

    <form action="index.php" method="POST" class="dbv-form">
        <div class="dbv-grid">
            <div class="dbv-field">
                <label for="host">Host:</label>
                <input type="text" name="host" id="host" class="dbv-input" value="localhost">
            </div>
            <div class="dbv-field">
                <label for="port">Port:</label>
                <input type="text" name="port" id="port" class="dbv-input" value="3306">
            </div>
        </div>

        <div class="dbv-grid">
            <div class="dbv-field">
                <label for="userName">User Name:</label>
                <input type="text" name="userName" id="userName" class="dbv-input" placeholder="Enter your username">
            </div>
            <div class="dbv-field">
                <label for="passWord">Password:</label>
                <input type="text" name="passWord" id="passWord" class="dbv-input" placeholder="Enter your password">
            </div>
        </div>

        <div class="dbv-grid">
            <div class="dbv-field">
                <label for="dbName">DB Name:</label>
                <input type="text" name="dbName" id="dbName" class="dbv-input" placeholder="Enter the database">
            </div>
            <div class="dbv-field">
                <label for="table">Table:</label>
                <input type="text" name="table" id="table" class="dbv-input" placeholder="Enter the table">
            </div>
        </div>

        <input type="submit" class="dbv-button dbv-w-100" name="submit" value="Submit">
    </form>
</div>