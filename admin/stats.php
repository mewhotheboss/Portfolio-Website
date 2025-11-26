<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
require '../config/database.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $para = $_POST['para'];

    if ($_POST['form_action'] == 'add') {
        $sql = "INSERT INTO stats (title, subtitle, para) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $title, $subtitle, $para);
        $stmt->execute();
        $message = "Stat added successfully!";
    } elseif ($_POST['form_action'] == 'edit') {
        $id = $_POST['id'];
        $sql = "UPDATE stats SET title=?, subtitle=?, para=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $subtitle, $para, $id);
        $stmt->execute();
        $message = "Stat updated successfully!";
    }
    echo "<script>window.location.href='stats.php';</script>";
    exit;
}

if ($action == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM stats WHERE id=$id");
    header("Location: stats.php");
    exit;
}

include 'includes/header.php'; 
?>

<div class="row">
    <div class="col-md-12">
        
        <!-- SHOW LIST VIEW -->
        <?php if ($action == 'list'): ?>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Stats List</h4>
                <a href="stats.php?action=add" class="btn btn-primary btn-sm">Add New Stat</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Number (Title)</th>
                                <th>Label (Subtitle)</th>
                                <th>Description</th>
                                <th style="width: 15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM stats");
                            if ($result->num_rows > 0):
                                while($row = $result->fetch_assoc()):
                            ?>
                            <tr>
                                <td><h4 class="fw-bold"><?php echo htmlspecialchars($row['title']); ?></h4></td>
                                <td><?php echo htmlspecialchars($row['subtitle']); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['para'], 0, 50)) . '...'; ?></td>
                                <td>
                                    <div class="form-button-action">
                                        <a href="stats.php?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-link btn-primary btn-lg">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="stats.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-link btn-danger" onclick="return confirm('Delete this stat?')">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; 
                            else: ?>
                                <tr><td colspan="4" class="text-center">No stats found</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- SHOW ADD/EDIT -->
        <?php elseif ($action == 'add' || $action == 'edit'): 
            $is_edit = ($action == 'edit');
            $data = ['title'=>'', 'subtitle'=>'', 'para'=>''];
            if ($is_edit) {
                $id = $_GET['id'];
                $result = $conn->query("SELECT * FROM stats WHERE id=$id");
                $data = $result->fetch_assoc();
            }
        ?>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?php echo $is_edit ? 'Edit Stat' : 'Add New Stat'; ?></h4>
            </div>
            <div class="card-body">
                <form action="stats.php" method="POST">
                    <input type="hidden" name="form_action" value="<?php echo $action; ?>">
                    <?php if($is_edit): ?><input type="hidden" name="id" value="<?php echo $id; ?>"><?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Number / Value (Title)</label>
                                <input type="text" class="form-control" name="title" placeholder="e.g. 50+" value="<?php echo htmlspecialchars($data['title']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Label (Subtitle)</label>
                                <input type="text" class="form-control" name="subtitle" placeholder="e.g. Projects Completed" value="<?php echo htmlspecialchars($data['subtitle']); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="para" rows="4" required><?php echo htmlspecialchars($data['para']); ?></textarea>
                    </div>

                    <div class="card-action">
                        <button type="submit" class="btn btn-success">Save Stat</button>
                        <a href="stats.php" class="btn btn-danger">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php include 'includes/footer.php'; ?>