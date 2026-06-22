<?php
// Utility: render the shared admin shell
// Usage: include with $title and $content set, or use ob_start pattern
function admin_layout(string $title, string $content): void {
    $seg = basename($_SERVER['PHP_SELF'], '.php');
    $brand = setting('brand_name', 'Berkah Mandiri Fresh');
    $user  = auth_user();
    $flash_ok  = flash_get('success');
    $flash_err = flash_get('error');
    echo <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$title} — Admin {$brand}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/admin.css">
</head>
<body>
<div class="shell">
<aside class="side">
  <div class="brand">
    <span class="leaf"><svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M5 19c0-7 5-13 14-14-1 9-7 14-14 14Z" fill="#fff"/></svg></span>
    <span>Berkah Mandiri<br>Fresh</span>
  </div>
  <nav>
    <a href="/admin/" class="{$seg}==="index" || {$seg}==="dashboard" ? "active":""' . '" ><span class="ic">📊</span> Dashboard</a>
HTML;
    // active link logic
    $links = [
        ['href'=>'/admin/','segs'=>['index','dashboard'],'ic'=>'📊','label'=>'Dashboard'],
        ['href'=>'/admin/products.php','segs'=>['products'],'ic'=>'🍎','label'=>'Produk'],
        ['href'=>'/admin/testimonials.php','segs'=>['testimonials'],'ic'=>'⭐','label'=>'Testimoni'],
        ['href'=>'/admin/settings.php','segs'=>['settings'],'ic'=>'⚙️','label'=>'Pengaturan'],
        ['href'=>'/','segs'=>[],'ic'=>'🌐','label'=>'Lihat Website','target'=>'_blank'],
    ];
    foreach ($links as $l) {
        $active = in_array($seg, $l['segs']) ? ' active' : '';
        $tgt    = isset($l['target']) ? ' target="_blank" rel="noopener"' : '';
        echo "    <a href=\"{$l['href']}\"{$tgt} class=\"{$active}\"><span class=\"ic\">{$l['ic']}</span> {$l['label']}</a>\n";
    }
    echo <<<HTML
  </nav>
  <div class="logout"><a href="/admin/logout.php"><span class="ic">🚪</span> Keluar</a></div>
</aside>
<main class="main">
  <div class="toprow">
    <h1>{$title}</h1>
    <div class="who">👤 {$user['name']}</div>
  </div>
HTML;
    if ($flash_ok)  echo "<div class=\"alert alert-success\">✅ {$flash_ok}</div>";
    if ($flash_err) echo "<div class=\"alert alert-error\">⚠️ {$flash_err}</div>";
    echo $content;
    echo "</main></div></body></html>";
}
