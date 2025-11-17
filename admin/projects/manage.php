<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit;
}

require '../../config/database.php';

if (isset($_GET['delete_id'])) {
    $delete = $_GET['delete_id'];

    $sql = "DELETE FROM projects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete);
    $stmt->execute();
    $stmt->close();
}

$projects = [];

$sql_select = "SELECT id, title, subtitle FROM projects ORDER BY id DESC";
$result = $conn->query($sql_select);

if ($result && $result->num_rows > 0) {
    $projects = $result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Projects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Manage Your Projects</h4>
                        <a href="../dashboard.php" class="btn btn-light btn-sm fw-bold text-primary">Back to Dashboard</a>
                    </div>

                    <div class="card-body p-4">
                        <div class="list-group">
                            <?php foreach ($projects as $project): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1"><?= $project['title']; ?></h5>
                                        <small class="text-muted"><?= $project['subtitle']; ?></small>
                                    </div>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="update.php?edit_id=<?= $project['id']; ?>" class="btn btn-outline-danger btn-sm">Edit</a>
                                        <a href="manage.php?delete_id=<?= $project['id']; ?>" class="btn btn-outline-danger btn-sm">Delete</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>