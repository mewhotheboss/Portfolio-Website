<?php
// 1. LOGIC BLOCK (Must be at the top, before Header)
session_start();

// Check Login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require '../config/database.php'; // Adjusted path

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {    

    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $para = $_POST['para'];

    // Image Upload Logic
    $image = $_FILES['image']['name'];
    // Unique Name to prevent overwriting
    $unique_name = time() . '_' . $image;
    
    $tempname = $_FILES['image']['tmp_name'];
    $folder = '../assets/img/' . $unique_name; // Adjusted path to assets

    if(move_uploaded_file($tempname, $folder)){
        // Save just the filename/path to DB
        $db_image = $unique_name;

        $sql = "INSERT INTO projects (title, subtitle, para, image) VALUES (?,?,?,?)";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("ssss", $title, $subtitle, $para, $db_image);
            $stmt->execute();
            $message = "success";
            $stmt->close();
        } else {
            $message = "db_error";
        }
    } else {
        $message = "upload_error";
    }
}
$conn->close();

// 2. INCLUDE HEADER (Starts the HTML and Sidebar)
include 'includes/header.php'; 
?>

<!-- 3. MAIN CONTENT -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Add New Project</div>
            </div>
            <div class="card-body">
                
                <?php if ($message == 'success'): ?>
                    <div class="alert alert-success">Project Added Successfully!</div>
                <?php elseif ($message == 'upload_error'): ?>
                    <div class="alert alert-danger">Failed to upload image.</div>
                <?php endif; ?>

                <form action="add.php" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="title">Project Title</label>
                                <input type="text" class="form-control" name="title" placeholder="e.g. E-Commerce App" required>
                            </div>
                            <div class="form-group">
                                <label for="subtitle">Subtitle / Tech Stack</label>
                                <input type="text" class="form-control" name="subtitle" placeholder="e.g. PHP & MySQL" required>
                            </div>
                            <div class="form-group">
                                <label for="image">Project Image</label>
                                <input type="file" class="form-control-file" name="image" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-8">
                            <div class="form-group">
                                <label for="para">Description</label>
                                <textarea class="form-control" name="para" rows="9" placeholder="Enter project details..." required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-action">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <a href="manage.php" class="btn btn-danger">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php 
// 4. INCLUDE FOOTER (Closes the tags)
include 'includes/footer.php'; 
?>