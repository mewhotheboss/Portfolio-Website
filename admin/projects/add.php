<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit;
}

require '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $para = $_POST['para'];

    $sql = "INSERT INTO projects (title, subtitle, para) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $title, $subtitle, $para);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Add New Project</h4>
                        <a href="../dashboard.php" class="btn btn-light btn-sm fw-bold text-primary">Back to Dashboard</a>
                    </div>

                    <div class="card-body p-4">

                        <form action="add.php" method="POST">

                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold">Project Title</label>
                                <input type="text" class="form-control" name="title" placeholder="Title" required>
                            </div>

                            <div class="mb-3">
                                <label for="subtitle" class="form-label fw-bold">Subtitle</label>
                                <input type="text" class="form-control" name="subtitle" placeholder="Subtitle" required>
                            </div>

                            <div class="mb-3">
                                <label for="para" class="form-label fw-bold">Description</label>
                                <textarea class="form-control" name="para" rows="6" placeholder="Description" required></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Save Project</button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>