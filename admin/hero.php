<?php
session_start();

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require '../config/database.php';

$message = "";

// --- 2. HANDLE UPDATE ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get text inputs matching your DB columns
    $name = $_POST['name'];
    $title = $_POST['title'];
    $para = $_POST['para'];

    // A. Check for New Image
    if (!empty($_FILES['image']['name'])) {
        
        // 1. Get Old Image (to delete it later)
        $sql_old = "SELECT image FROM hero_section WHERE id = 1";
        $res_old = $conn->query($sql_old);
        $row_old = $res_old->fetch_assoc();
        $old_image_path = '../assets/img/' . $row_old['image'];

        // 2. Upload New Image
        $image_name = $_FILES['image']['name'];
        $unique_name = time() . '_' . $image_name; // Unique name
        $target = '../assets/img/' . $unique_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            
            // 3. Delete Old File (Cleanup)
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }

            // 4. Update Database WITH Image
            $sql = "UPDATE hero_section SET name=?, title=?, para=?, image=? WHERE id=1";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssss", $name, $title, $para, $unique_name);
                if ($stmt->execute()) {
                    $message = "success";
                } else {
                    $message = "error";
                }
            }
        }
    } else {
        // B. Update WITHOUT Image (Keep old one)
        $sql = "UPDATE hero_section SET name=?, title=?, para=? WHERE id=1";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss", $name, $title, $para);
            if ($stmt->execute()) {
                $message = "success";
            } else {
                $message = "error";
            }
        }
    }
}

// --- 3. FETCH CURRENT DATA ---
$sql_fetch = "SELECT * FROM hero_section WHERE id = 1";
$result = $conn->query($sql_fetch);

// Safety check: if table is empty, insert dummy row
if ($result->num_rows == 0) {
    $conn->query("INSERT INTO hero_section (id, name, title, para, image) VALUES (1, 'My Name', 'My Title', 'My Description', 'default.png')");
    // Refresh to get the new data
    $result = $conn->query($sql_fetch);
}

$hero = $result->fetch_assoc();

include 'includes/header.php'; 
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Edit Hero Section</div>
            </div>
            <div class="card-body">
                
                <?php if ($message == 'success'): ?>
                    <div class="alert alert-success">Hero section updated successfully!</div>
                <?php elseif ($message == 'error'): ?>
                    <div class="alert alert-danger">Something went wrong.</div>
                <?php endif; ?>

                <form action="hero.php" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Small Text (Name)</label>
                                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($hero['name']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Main Headline (Title)</label>
                                <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($hero['title']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Hero Image</label>
                                <input type="file" class="form-control-file" name="image">
                                <br>
                                <small>Current Image:</small><br>
                                <?php if(!empty($hero['image'])): ?>
                                    <img src="../assets/img/<?php echo htmlspecialchars($hero['image']); ?>" width="200" class="mt-2 rounded border">
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Paragraph (Description)</label>
                                <textarea class="form-control" name="para" rows="10" required><?php echo htmlspecialchars($hero['para']); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card-action">
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>