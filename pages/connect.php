<?php ?>

<p class="text-warning">Please enter the database and table name, username and optionally password.</p>

<div class="w-75">
    <form action="index.php" method="POST" class="d-flex flex-column gap-3">
        <div class="row">
            <div class="col-md-6">
                <label for="host">Host:</label>
                <input type="text" name="host" id="host" class="form-control" value="localhost">
            </div>
            <div class="col-md-6">
                <label for="port">Port:</label>
                <input type="text" name="port" id="port" class="form-control" value="3306">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label for="userName">User Name:</label>
                <input type="text" name="userName" id="userName" class="form-control" placeholder="Enter your username">
            </div>
            <div class="col-md-6">
                <label for="passWord">Password:</label>
                <input type="text" name="passWord" id="passWord" class="form-control" placeholder="Enter your password">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label for="dbName">DB Name:</label>
                <input type="text" name="dbName" id="dbName" class="form-control" placeholder="Enter the database">
            </div>
            <div class="col-md-6">
                <label for="table">Table:</label>
                <input type="text" name="table" id="table" class="form-control" placeholder="Enter the table">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <input type="submit" class="btn btn-secondary w-100" name="submit" value="Submit">
            </div>
        </div>
    </form>
</div>