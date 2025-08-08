<?php
session_start();
require_once 'config.php';

// ======= LOGIN SYSTEM =======
$valid_username = '777';
$valid_password = '777';

if (isset($_POST['login'])) {
    $input_username = $_POST['username'] ?? '';
    $input_password = $_POST['password'] ?? '';

    if ($input_username === $valid_username && $input_password === $valid_password) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $login_error = "Invalid username or password.";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// ======= REDIRECT IF NOT LOGGED IN =======
if (!isset($_SESSION['admin_logged_in'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .login-container { max-width: 400px; margin: 100px auto; }
    </style>
</head>
<body>
<div class="login-container card p-4 shadow-sm">
    <h4 class="text-center mb-3">Admin Login</h4>
    <?php if (!empty($login_error)) : ?>
        <div class="alert alert-danger"><?= $login_error ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button name="login" type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>
</body>
</html>
<?php
    exit;
}

// ======= DASHBOARD QUERIES =======
$userStats = $conn->query("SELECT COUNT(*) as total_users, SUM(credit) as total_credits FROM users")->fetch_assoc();
$ccStats = $conn->query("SELECT SUM(total_cc) as total_cc FROM users")->fetch_assoc();
$usersList = $conn->query("SELECT * FROM users ORDER BY id DESC");

// ======= REDEEM CODE GENERATION =======
$genSuccess = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_code'])) {
    $code = strtoupper(bin2hex(random_bytes(4))); // 8 chars
    $credits = intval($_POST['credits']);
    $stmt = $conn->prepare("INSERT INTO redeem_codes (code, credits) VALUES (?, ?)");
    $stmt->bind_param("si", $code, $credits);
    if ($stmt->execute()) {
        $genSuccess = "Redeem Code Generated: <strong>$code</strong> with $credits credits.";
    } else {
        $genSuccess = "Error generating redeem code.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .card { border-radius: 1rem; }
        .table-wrapper { max-height: 500px; overflow-y: auto; }
        th { position: sticky; top: 0; background: #fff; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Admin Dashboard</h2>
        <a href="?logout=true" class="btn btn-outline-danger">Logout</a>
    </div>

    <!-- Dashboard Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text fs-4"><?= $userStats['total_users'] ?? 0 ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Credits</h5>
                    <p class="card-text fs-4"><?= $userStats['total_credits'] ?? 0 ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-dark shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total CC Checked</h5>
                    <p class="card-text fs-4"><?= $ccStats['total_cc'] ?? 0 ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Redeem Code Generator -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">Generate Redeem Code</div>
        <div class="card-body">
            <?php if ($genSuccess): ?>
                <div class="alert alert-info"><?= $genSuccess ?></div>
            <?php endif; ?>
            <form method="POST" class="row g-3">
                <div class="col-md-6">
                    <label for="credits" class="form-label">Credits</label>
                    <input type="number" class="form-control" name="credits" id="credits" min="1" required>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" name="generate_code" class="btn btn-primary w-100">Generate</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Users List -->
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">All Users</div>
        <div class="card-body table-wrapper">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Credit</th>
                        <th>CC Checked</th>
                        <th>Reg Date</th>
                        <th>Name</th>
                        <th>Telegram ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $usersList->fetch_assoc()): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= $user['credit'] ?></td>
                            <td><?= $user['total_cc'] ?></td>
                            <td><?= $user['reg_data'] ?></td>
                            <td><?= htmlspecialchars($user['name'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($user['tg_id'] ?? '-') ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</body>
</html>
