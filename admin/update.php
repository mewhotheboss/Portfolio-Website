<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require '../config/database.php';

if (!isset($_GET['edit_id'])) {
    header('Location: manage.php');
    exit;
}
$id = $_GET['edit_id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $para = $_POST['para'];

    if (!empty($_FILES['image']['name'])) {

        $old_sql = "SELECT image FROM projects WHERE id = ?";
        $stmt_old = $conn->prepare($old_sql);
        $stmt_old->bind_param("i", $id);
        $stmt_old->execute();
        $res_old = $stmt_old->get_result();
        $row_old = $res_old->fetch_assoc();
        $old_path = '../assets/img/' . $row_old['image'];
        if (file_exists($old_path)) unlink($old_path);

        $image = $_FILES['image']['name'];
        $unique_name = time() . '_' . $image;
        $target = '../assets/img/' . $unique_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);

        $sql = "UPDATE projects SET title=?, subtitle=?, para=?, image=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $title, $subtitle, $para, $unique_name, $id);
    
    } else {
        $sql = "UPDATE projects SET title=?, subtitle=?, para=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $subtitle, $para, $id);
    }

    if ($stmt->execute()) {
        header('Location: manage.php');
        exit;
    } else {
        $message = "error";
    }
}

$sql = "SELECT * FROM projects WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$project = $result->fetch_assoc();

include 'includes/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Edit Project</div>
            </div>
            <div class="card-body">
                
                <form action="update.php?edit_id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label>Project Title</label>
                                <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($project['title']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Subtitle</label>
                                <input type="text" class="form-control" name="subtitle" value="<?php echo htmlspecialchars($project['subtitle']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Change Image (Optional)</label>
                                <input type="file" class="form-control-file" name="image">
                                <br>
                                <small>Current:</small><br>
                                <img src="../assets/img/<?php echo htmlspecialchars($project['image']); ?>" width="100" class="rounded mt-2">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-8">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="para" rows="9" required><?php echo htmlspecialchars($project['para']); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-action">
                        <button type="submit" class="btn btn-success">Save Changes</button>
                        <a href="manage.php" class="btn btn-danger">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>