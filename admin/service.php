<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
require '../config/database.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $para = $_POST['para'];
    $icon = $_POST['icon']; 

    if ($_POST['form_action'] == 'add') {
        $sql = "INSERT INTO service (title, para, icon) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $title, $para, $icon);
        $stmt->execute();
        $message = "Service added successfully!";
    } elseif ($_POST['form_action'] == 'edit') {
        $id = $_POST['id'];
        $sql = "UPDATE service SET title=?, para=?, icon=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $para, $icon, $id);
        $stmt->execute();
        $message = "Service updated successfully!";
    }
    echo "<script>window.location.href='service.php';</script>";
    exit;
}

if ($action == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM service WHERE id=$id");
    header("Location: service.php");
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
                <h4 class="card-title">Services List</h4>
                <a href="service.php?action=add" class="btn btn-primary btn-sm">Add New Service</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th style="width: 15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM service");
                            while($row = $result->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['para'], 0, 60)) . '...'; ?></td>
                                <td>
                                    <div class="form-button-action">
                                        <a href="service.php?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-link btn-primary btn-lg">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="service.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-link btn-danger" onclick="return confirm('Delete this service?')">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- SHOW ADD/EDIT FORM -->
        <?php elseif ($action == 'add' || $action == 'edit'): 
            $is_edit = ($action == 'edit');
            $data = ['title'=>'', 'para'=>'', 'icon'=>''];
            if ($is_edit) {
                $id = $_GET['id'];
                $result = $conn->query("SELECT * FROM service WHERE id=$id");
                $data = $result->fetch_assoc();
            }
        ?>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?php echo $is_edit ? 'Edit Service' : 'Add New Service'; ?></h4>
            </div>
            <div class="card-body">
                <form action="service.php" method="POST">
                    <input type="hidden" name="form_action" value="<?php echo $action; ?>">
                    <?php if($is_edit): ?><input type="hidden" name="id" value="<?php echo $id; ?>"><?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Service Title</label>
                                <input type="text" class="form-control" name="title" placeholder="e.g. Web Design" value="<?php echo htmlspecialchars($data['title']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Icon Class (FontAwesome)</label>
                                <input type="text" class="form-control" name="icon" placeholder="e.g. fa-solid fa-code" value="<?php echo htmlspecialchars($data['icon']); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="para" rows="5" required><?php echo htmlspecialchars($data['para']); ?></textarea>
                    </div>

                    <div class="card-action">
                        <button type="submit" class="btn btn-success">Save Service</button>
                        <a href="service.php" class="btn btn-danger">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php include 'includes/footer.php'; ?>