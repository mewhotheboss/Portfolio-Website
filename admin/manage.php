<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require '../config/database.php';

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $sql_img = "SELECT image FROM projects WHERE id = ?";
    $stmt_img = $conn->prepare($sql_img);
    $stmt_img->bind_param("i", $delete_id);
    $stmt_img->execute();
    $res_img = $stmt_img->get_result();
    
    if ($row = $res_img->fetch_assoc()) {
        $img_path = '../assets/img/' . $row['image'];
        if (file_exists($img_path)) {
            unlink($img_path);
        }
    }
    $stmt_img->close();

    $sql = "DELETE FROM projects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();

    header('Location: manage.php'); 
    exit;
}

$projects = [];
$result = $conn->query("SELECT * FROM projects ORDER BY id DESC");
if ($result) {
    $projects = $result->fetch_all(MYSQLI_ASSOC);
}

include 'includes/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Project List</h4>
                <a href="add.php" class="btn btn-primary btn-sm">Add New</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Subtitle</th>
                                <th style="width: 15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($projects)): ?>
                                <tr><td colspan="5" class="text-center">No projects found</td></tr>
                            <?php else: ?>
                                <?php foreach ($projects as $index => $row): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td>
                                        <div class="avatar avatar-lg">
                                            <img src="../assets/img/<?php echo htmlspecialchars($row['image']); ?>" alt="..." class="avatar-img rounded">
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['subtitle']); ?></td>
                                    <td>
                                        <div class="form-button-action">
                                            <a href="update.php?edit_id=<?php echo $row['id']; ?>" class="btn btn-link btn-primary btn-lg" data-bs-toggle="tooltip" title="Edit Task">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="manage.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-link btn-danger" data-bs-toggle="tooltip" title="Remove" onclick="return confirm('Are you sure?');">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>