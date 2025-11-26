<?php
session_start();
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $renew_pass = $_POST['renew_password'];

    
    if ($new_pass !== $renew_pass) {
        echo "<script>alert('New passwords do not match!');</script>";
    } else {
        
        $sql = "SELECT id, password FROM users WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                
                
                if (password_verify($current_pass, $user['password'])) {
                    
                    
                    $new_hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);
                    
                    
                    $update_sql = "UPDATE users SET password = ? WHERE id = ?";
                    if ($update_stmt = $conn->prepare($update_sql)) {
                        $update_stmt->bind_param("si", $new_hashed_password, $user['id']);
                        
                        if ($update_stmt->execute()) {
                            
                            echo "<script>alert('Password changed successfully!'); window.location.href='login.php';</script>";
                            exit;
                        } else {
                            echo "<script>alert('Database Error.');</script>";
                        }
                        $update_stmt->close();
                    }
                } else {
                    echo "<script>alert('Current password is incorrect.');</script>";
                }
            } else {
                echo "<script>alert('Username not found.');</script>";
            }
            $stmt->close();
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Change Password</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    
    
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/kaiadmin.min.css">
</head>

<body class="login bg-primary">
    <div class="wrapper wrapper-login">
        <div class="container container-login animated fadeIn">
            <h3 class="text-center">Change Password</h3>
            <div class="login-form">
                
                <form action="forgot.php" method="POST">
                    
                    
                    <div class="form-group">
                        <label for="username"><b>Username</b></label>
                        <input id="username" name="username" type="text" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="current_password"><b>Current Password</b></label>
                        <input id="current_password" name="current_password" type="password" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="new_password"><b>New Password</b></label>
                        <input id="new_password" name="new_password" type="password" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="renew_password"><b>Re-enter New Password</b></label>
                        <input id="renew_password" name="renew_password" type="password" class="form-control" required>
                    </div>

                    <div class="form-group form-action-d-flex mb-3">
                        <button type="submit" class="btn btn-primary col-md-12 float-end mt-3 mt-sm-0 fw-bold">Change Password</button>
                    </div>
                    
                    <div class="login-account">
                        <a href="login.php" class="link">Back to Login</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
    <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
</body>
</html>