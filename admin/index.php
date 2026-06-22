<?php
declare(strict_types=1);
require __DIR__ . '/../data/db.php';
require __DIR__ . '/../data/session.php';
require __DIR__ . '/layout.php';
db_init(); auth_check();

$cProd   = (int) db()->querySingle("SELECT COUNT(*) FROM products");
$cActive = (int) db()->querySingle("SELECT COUNT(*) FROM products WHERE is_active=1");
$cTesti  = (int) db()->querySingle("SELECT COUNT(*) FROM testimonials");
$cCat    = (int) db()->querySingle("SELECT COUNT(*) FROM categories");

ob_start(); ?>
<div class="cards">
  <div class="stat"><div class="ic">🍎</div><b><?= $cProd ?></b><span>Total Produk</span></div>
  <div class="stat"><div class="ic">✅</div><b><?= $cActive ?></b><span>Produk Aktif</span></div>
  <div class="stat"><div class="ic">⭐</div><b><?= $cTesti ?></b><span>Testimoni</span></div>
  <div class="stat"><div class="ic">🏷️</div><b><?= $cCat ?></b><span>Kategori</span></div>
</div>
<div class="panel" style="padding:28px">
  <h2 style="font-size:18px;color:var(--g900);margin-bottom:8px">Selamat datang 👋</h2>
  <p style="color:var(--muted);font-size:14.5px;max-width:640px;line-height:1.75">
    Dari panel ini kamu bisa mengelola seluruh isi website tanpa menyentuh kode.
    Tambah atau ubah <b>Produk</b>, kelola <b>Testimoni</b> pelanggan, dan ubah
    <b>Pengaturan</b> seperti nomor WhatsApp, alamat, teks hero, hingga warna brand.
    Setiap perubahan langsung tampil di website.
  </p>
  <div style="margin-top:22px;display:flex;gap:12px;flex-wrap:wrap">
    <a class="btn btn-primary" href="/admin/products.php?action=new">+ Tambah Produk</a>
    <a class="btn btn-ghost" href="/admin/settings.php">⚙️ Pengaturan Website</a>
    <a class="btn btn-ghost" href="/" target="_blank" rel="noopener">🌐 Lihat Website</a>
  </div>
</div>
<?php
admin_layout('Dashboard', ob_get_clean());
