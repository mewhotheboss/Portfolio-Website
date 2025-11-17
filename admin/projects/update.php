<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit;
}

require '../../config/database.php';

if (!isset($_GET['edit_id'])) {
    header('Location: delete.php');
    exit;
}
$project_id = $_GET['edit_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $para = $_POST['para'];

    $sql_update = "UPDATE projects SET title = ?, subtitle = ?, para = ? WHERE id = ?";

    if ($stmt = $conn->prepare($sql_update)) {

        $stmt->bind_param("sssi", $title, $subtitle, $para, $project_id);

        if ($stmt->execute()) {

            header('Location: delete.php');
            exit;
        }
        $stmt->close();
    }
}

$project = null;
$sql_fetch = "SELECT title, subtitle, para FROM projects WHERE id = ?";

if ($stmt = $conn->prepare($sql_fetch)) {
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $project = $result->fetch_assoc();
    } else {

        header('Location: delete.php');
        exit;
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items.center">
                        <h4 class="mb-0">Edit Project</h4>
                        <a href="delete.php" class="btn btn-light btn-sm fw-bold">Back to List</a>
                    </div>

                    <div class="card-body p-4">
                        <form action="update.php?edit_id=<?php echo $project_id; ?>" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Project Title</label>
                                <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($project['title']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Subtitle</label>
                                <input type="text" class="form-control" name="subtitle" value="<?php echo htmlspecialchars($project['subtitle']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <textarea class="form-control" name="para" rows="6" required><?php echo htmlspecialchars($project['para']); ?></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>