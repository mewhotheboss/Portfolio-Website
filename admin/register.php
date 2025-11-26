<?php
session_start();

if(isset($_SESSION['username'])){
    header('Location: dashboard.php');
    exit;
}

require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['passwordsignin'];
    $confirm_password = $_POST['confirmpassword'];

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        
        $check = $conn->prepare("SELECT username FROM users WHERE username = ? OR email = ?");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            echo "<script>alert('Username or Email already exists!');</script>";
        } else {
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, username, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $fullname, $email, $username, $hashed_password);

            if ($stmt->execute()) {
                
                echo "<script>alert('Registration successful! Please Login.'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Error registering user.');</script>";
            }
            $stmt->close();
        }
        $check->close();
    }
}
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Register - Admin Dashboard</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />

    <script src="../assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {"families": ["Public Sans:300,400,500,600,700"]},
            custom: {"families": ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['../assets/css/fonts.min.css']},
            active: function() { sessionStorage.fonts = true; }
        });
    </script>

    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/plugins.min.css">
    <link rel="stylesheet" href="../assets/css/kaiadmin.min.css">
</head>

<body class="login bg-primary">
    <div class="wrapper wrapper-login">
        <div class="container container-login animated fadeIn">
            <h3 class="text-center">Sign Up</h3>
            <div class="login-form">
                <form action="register.php" method="POST">
                    <div class="form-group">
                        <label for="fullname"><b>Fullname</b></label>
                        <input id="fullname" name="fullname" type="text" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email"><b>Email</b></label>
                        <input id="email" name="email" type="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="username"><b>Username</b></label>
                        <input id="username" name="username" type="text" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="passwordsignin"><b>Password</b></label>
                        <div class="position-relative">
                            <input id="passwordsignin" name="passwordsignin" type="password" class="form-control" required>
                            <div class="show-password">
                                <i class="icon-eye"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirmpassword"><b>Confirm Password</b></label>
                        <div class="position-relative">
                            <input id="confirmpassword" name="confirmpassword" type="password" class="form-control" required>
                            <div class="show-password">
                                <i class="icon-eye"></i>
                            </div>
                        </div>
                    </div>
                    <div class="row form-sub m-0">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="agree" id="agree" required>
                            <label class="form-check-label" for="agree">I Agree the terms and conditions.</label>
                        </div>
                    </div>
                    <div class="row form-action">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100 fw-bold">Sign Up</button>
                        </div>
                        <div class="login-account">
                            <span class="msg">Have an account?</span>
                            
                            <a href="login.php" class="link">LogIn</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/kaiadmin.min.js"></script>
</body>
</html>