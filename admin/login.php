<?php
session_start();
require_once '../koneksi.php';

if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit();
}

$sql_logo_login = "SELECT favicon FROM profil WHERE id = 1";
$res_logo_login = $conn->query($sql_logo_login);
$data_logo_login = $res_logo_login ? $res_logo_login->fetch_assoc() : null;
$favicon_img_login = (!empty($data_logo_login['favicon'])) ? '../uploads/' . $data_logo_login['favicon'] : '../images/favicon.ico';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $row['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - PAUDQU Al-Ikhlas</title>
    <link rel="icon" href="<?= $favicon_img_login ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #f4f6f9; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .login-card { width: 100%; max-width: 400px; border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .login-header { background-color: #28a745; color: white; border-radius: 15px 15px 0 0; padding: 30px 20px; text-align: center; }
        .btn-login { background-color: #28a745; color: white; border-radius: 50px; font-weight: 600; padding: 10px; }
        .btn-login:hover { background-color: #218838; color: white; }
    </style>
</head>
<body>

<div class="card login-card">
    <div class="login-header">
        <h3 class="mb-0 fw-bold">Admin Panel</h3>
        <p class="mb-0 small opacity-75">PAUDQU Al-Ikhlas</p>
    </div>
    <div class="card-body p-4">
        <?php if($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label text-muted fw-bold">Username</label>
                <input type="text" class="form-control form-control-lg bg-light" id="username" name="username" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label text-muted fw-bold">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control form-control-lg bg-light border-end-0" id="password" name="password" required>
                    <button class="btn btn-light border border-start-0 bg-light" type="button" id="togglePassword">
                        <i class="fas fa-eye text-muted" id="toggleIcon"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-login w-100">Login</button>
            <div class="text-center mt-3">
                <a href="../index.php" class="text-success text-decoration-none small"><i class="fas fa-arrow-left"></i> Kembali ke Website</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<script>
    document.getElementById('togglePassword').addEventListener('click', function (e) {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        
        // Toggle type attribute
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle eye icon
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
</script>
</body>
</html>
