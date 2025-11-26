<?php
session_start();

if(isset($_SESSION['username'])){
    header('Location: profile.php');
    exit;
}

require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, name, password FROM users WHERE username= ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['user_id'] = $user['id']; 
            header('Location: profile.php');
            exit;
        } else {
            echo "<script>alert('Invalid username or password.');</script>";
        }
    } else {
        echo "<script>alert('Invalid username or password.');</script>";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Login - Admin Dashboard</title>
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
            <h3 class="text-center">Sign In</h3>
            <div class="login-form">
                <form action="login.php" method="POST">
                    <div class="form-group">
                        <label for="username"><b>Username</b></label>
                        <input id="username" name="username" type="text" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password"><b>Password</b></label>
                        <a href="forgot.php" class="link float-end">Forget Password ?</a>
                        <div class="position-relative">
                            <input id="password" name="password" type="password" class="form-control" required>
                            <div class="show-password">
                                <i class="icon-eye"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-action-d-flex mb-3">
                        <button type="submit" class="btn btn-primary col-md-12 float-end mt-3 mt-sm-0 fw-bold">Sign In</button>
                    </div>
                </form>

                <div class="login-account">
                    <span class="msg">Don't have an account yet ?</span>
                    
                    <a href="register.php" class="link">Sign Up</a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/kaiadmin.min.js"></script>
</body>
</html>