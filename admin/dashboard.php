<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$name = $_SESSION['name'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">My Portfolio Admin</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">Welcome, <?php echo $name; ?>..!</span>
                <a href="logout.php" class="btn btn-warning">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0 text-center">Dashboard Controls</h4>
            </div>

            <div class="card-body">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action">Add New Projects</a>
                    <a href="#" class="list-group-item list-group-item-action">Edit Existing Projects</a>
                    <a href="#" class="list-group-item list-group-item-action">Delete Projects</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>