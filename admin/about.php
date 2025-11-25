<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
require '../config/database.php';

$message = "";

// --- HANDLE UPDATE ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $card_title = $_POST['card_title'];
    $card_para = $_POST['card_para'];
    $title = $_POST['title'];
    $para = $_POST['para'];

    $sql = "UPDATE about SET card_title=?, card_para=?, title=?, para=? WHERE id=1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $card_title, $card_para, $title, $para);
    
    if ($stmt->execute()) {
        $message = "success";
    } else {
        $message = "error";
    }
}

// --- FETCH DATA ---
$result = $conn->query("SELECT * FROM about WHERE id=1");
if ($result->num_rows == 0) {
    // Insert dummy row if empty
    $conn->query("INSERT INTO about (id, card_title, card_para, title, para) VALUES (1, 'My Role', 'Short intro...', 'Main Title', 'Long bio...')");
    $result = $conn->query("SELECT * FROM about WHERE id=1");
}
$about = $result->fetch_assoc();

include 'includes/header.php'; 
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit About Section</h4>
            </div>
            <div class="card-body">
                
                <?php if ($message == 'success'): ?>
                    <div class="alert alert-success">About section updated successfully!</div>
                <?php endif; ?>

                <form action="about.php" method="POST">
                    
                    <!-- Left Card Area -->
                    <h5 class="text-primary fw-bold mt-3 mb-3">Left Card Content</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Card Title (e.g. WEB Developer)</label>
                                <input type="text" class="form-control" name="card_title" value="<?php echo htmlspecialchars($about['card_title']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Card Intro Text (Short)</label>
                                <textarea class="form-control" name="card_para" rows="3" required><?php echo htmlspecialchars($about['card_para']); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Right Content Area -->
                    <h5 class="text-primary fw-bold mt-3 mb-3">Main Content Area</h5>
                    <div class="form-group">
                        <label>Main Title (e.g. Freelance Web Designer)</label>
                        <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($about['title']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Main Biography (Long Text)</label>
                        <textarea class="form-control" name="para" rows="8" required><?php echo htmlspecialchars($about['para']); ?></textarea>
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