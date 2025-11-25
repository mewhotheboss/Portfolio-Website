<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
require '../config/database.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$message = "";

// --- HANDLE UPDATE ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['form_action'] == 'edit') {
    $id = $_POST['id'];
    $title = $_POST['title'];       // Client Name
    $subtitle = $_POST['subtitle']; // Position/Job
    $para = $_POST['para'];         // Review Text

    // Check for New Image
    if (!empty($_FILES['image']['name'])) {
        // 1. Get Old Image Name
        $res_old = $conn->query("SELECT image FROM testimonial WHERE id=$id");
        $row_old = $res_old->fetch_assoc();
        $old_path = '../assets/img/' . $row_old['image'];

        // 2. Upload New Image
        $image_name = $_FILES['image']['name'];
        $unique_name = time() . '_' . $image_name;
        $target = '../assets/img/' . $unique_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // 3. Delete Old File
            if (file_exists($old_path) && !empty($row_old['image'])) {
                unlink($old_path);
            }
            // 4. Update DB with Image
            $sql = "UPDATE testimonial SET title=?, subtitle=?, para=?, image=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $title, $subtitle, $para, $unique_name, $id);
            $stmt->execute();
        }
    } else {
        // Update WITHOUT Image
        $sql = "UPDATE testimonial SET title=?, subtitle=?, para=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $subtitle, $para, $id);
        $stmt->execute();
    }
    echo "<script>window.location.href='testimonial.php';</script>";
    exit;
}

include 'includes/header.php'; 
?>

<div class="row">
    <div class="col-md-12">
        
        <!-- SHOW LIST VIEW -->
        <?php if ($action == 'list'): ?>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Testimonials List</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Client Name (Title)</th>
                                <th>Position (Subtitle)</th>
                                <th>Review</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM testimonial");
                            if ($result->num_rows > 0):
                                while($row = $result->fetch_assoc()):
                            ?>
                            <tr>
                                <td>
                                    <div class="avatar avatar-lg">
                                        <img src="../assets/img/<?php echo htmlspecialchars($row['image']); ?>" alt="..." class="avatar-img rounded-circle">
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['subtitle']); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['para'], 0, 50)) . '...'; ?></td>
                                <td>
                                    <a href="testimonial.php?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-link btn-primary btn-lg">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; 
                            else: ?>
                                <tr><td colspan="5" class="text-center">No testimonials found</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- SHOW EDIT FORM -->
        <?php elseif ($action == 'edit'): 
            $id = $_GET['id'];
            $result = $conn->query("SELECT * FROM testimonial WHERE id=$id");
            $data = $result->fetch_assoc();
        ?>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Edit Testimonial</h4>
                <a href="testimonial.php" class="btn btn-light btn-sm fw-bold">Back to List</a>
            </div>
            <div class="card-body">
                <form action="testimonial.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="form_action" value="edit">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Client Name (Title)</label>
                                <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($data['title']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Position / Company (Subtitle)</label>
                                <input type="text" class="form-control" name="subtitle" value="<?php echo htmlspecialchars($data['subtitle']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Client Photo</label>
                                <input type="file" class="form-control-file" name="image">
                                <br>
                                <small>Current:</small>
                                <img src="../assets/img/<?php echo htmlspecialchars($data['image']); ?>" width="80" class="rounded-circle mt-2 border">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Review Text (Paragraph)</label>
                                <textarea class="form-control" name="para" rows="8" required><?php echo htmlspecialchars($data['para']); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card-action">
                        <button type="submit" class="btn btn-success">Save Changes</button>
                        <a href="testimonial.php" class="btn btn-danger">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php include 'includes/footer.php'; ?>