<?php
session_start();
if(isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    
    // Hardcoded login (Simpel & Cepat)
    if($user == 'admin' && $pass == 'admin') {
        $_SESSION['login'] = true;
        $_SESSION['user'] = 'Executive Manager';
        header('Location: index.php');
        exit();
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - AdventureWorks DW</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .login-card { width: 400px; padding: 40px; border-radius: 10px; background: white; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="login-card">
        <h3 class="text-center mb-4">Executive Login</h3>
        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Sign In</button>
        </form>
    </div>
</body>
</html>