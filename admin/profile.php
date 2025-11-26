<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require '../config/database.php';

$user_id = $_SESSION['user_id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];

    $sql = "UPDATE users SET name = ?, email = ?, username = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssi", $name, $email, $username, $user_id);
        
        if ($stmt->execute()) {
            $message = "success";
            $_SESSION['name'] = $name;
            $_SESSION['username'] = $username;
        } else {
            $message = "error";
        }
        $stmt->close();
    }
}

$user = [];
$sql = "SELECT name, email, username FROM users WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
    }
    $stmt->close();
}

include 'includes/header.php'; 
?>

<h3 class="fw-bold mb-3">User Profile</h3>

<div class="row">
    <!-- Left Column -->
    <div class="col-md-8">
        <div class="card card-with-nav">
            <div class="card-header">
                <div class="row row-nav-line">
                    <ul class="nav nav-tabs nav-line nav-color-secondary w-100 ps-4" role="tablist">
                        <li class="nav-item"> <a class="nav-link active show" data-bs-toggle="tab" href="#home" role="tab" aria-selected="true">Edit Profile</a> </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <?php if ($message == 'success'): ?>
                    <div class="alert alert-success">Profile updated successfully!</div>
                <?php endif; ?>

                <form action="profile.php" method="POST">
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Full Name</label>
                                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group form-group-default">
                                <label>Username</label>
                                <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-end mt-3 mb-3">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="forgot.php" class="btn btn-warning">Change Password</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-md-4">
        <div class="card card-profile">
            <div class="card-header" style="background-image: url('../assets/img/blogpost.jpg')">
                <div class="profile-picture">
                    <div class="avatar avatar-xl">
                        <span class="avatar-title rounded-circle border border-white bg-primary text-white fs-2">
                            <?php echo substr($user['name'], 0, 1); ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="user-profile text-center">
                    <div class="name"><?php echo htmlspecialchars($user['name']); ?></div>
                    <div class="job">Administrator</div>
                    <div class="desc"><?php echo htmlspecialchars($user['email']); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 

include 'includes/footer.php'; 
?>