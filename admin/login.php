<?php
declare(strict_types=1);
require __DIR__ . '/../data/db.php';
require __DIR__ . '/../data/session.php';
db_init(); session_boot();

if (!empty($_SESSION['admin'])) { redirect('/admin/'); }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $u = post('username'); $p = post('password');
    $row = db()->querySingle("SELECT * FROM users WHERE username='" . db()->escapeString($u) . "'", true);
    if ($row && password_verify($p, $row['password'])) {
        $_SESSION['admin'] = ['id' => $row['id'], 'name' => $row['name']];
        redirect('/admin/');
    }
    $error = 'Username atau password salah.';
}
$brand = setting('brand_name', 'Berkah Mandiri Fresh');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin — <?= e($brand) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/admin.css">
</head>
<body>
<div class="login-wrap">
  <div class="login-card">
    <div class="brand">
      <span class="leaf"><svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M5 19c0-7 5-13 14-14-1 9-7 14-14 14Z" fill="#fff"/></svg></span>
      <?= e($brand) ?>
    </div>
    <p class="sub">Masuk ke panel admin untuk mengelola website</p>
    <?php if ($error): ?><div class="alert alert-error">⚠️ <?= e($error) ?></div><?php endif; ?>
    <form method="post">
      <?= csrf_field() ?>
      <div class="fg"><label>Username</label><input type="text" name="username" required autofocus></div>
      <div class="fg"><label>Password</label><input type="password" name="password" required></div>
      <button class="btn btn-primary" style="width:100%;justify-content:center">Masuk →</button>
    </form>
    <p class="muted-note">Default: <b>admin</b> / <b>admin123</b> — ganti setelah login pertama</p>
  </div>
</div>
</body></html>
