<?php
declare(strict_types=1);
require __DIR__ . '/data/db.php';
require __DIR__ . '/data/session.php';
db_init();
session_boot();

$s  = settings_all();
$wa = preg_replace('/\D/', '', $s['wa_number'] ?? '6281232100132');

$umum   = "Halo {$s['brand_name']} 👋\nSaya ingin menanyakan produk buah & telur yang tersedia.\nMohon informasi lebih lanjut. Terima kasih.";
$bisnis = "Halo {$s['brand_name']} 👋\nSaya ingin konsultasi kebutuhan supplier buah & telur untuk bisnis.\nMohon info lebih lanjut. Terima kasih.";

$categories = [];
$r = db()->query("SELECT * FROM categories ORDER BY sort ASC");
while ($row = $r->fetchArray(SQLITE3_ASSOC)) $categories[] = $row;

$products = [];
$r = db()->query("SELECT * FROM products WHERE is_active=1 ORDER BY sort ASC");
while ($row = $r->fetchArray(SQLITE3_ASSOC)) $products[] = $row;

$grouped = [];
foreach ($products as $p) $grouped[$p['category']][] = $p;

$testimonials = [];
$r = db()->query("SELECT * FROM testimonials WHERE is_active=1 ORDER BY sort ASC");
while ($row = $r->fetchArray(SQLITE3_ASSOC)) $testimonials[] = $row;

$cp  = e($s['color_primary']   ?? '#14532D');
$cs  = e($s['color_secondary'] ?? '#22C55E');
$brand = e($s['brand_name'] ?? 'Berkah Mandiri Fresh');
$tagline = e($s['tagline'] ?? '');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $brand ?> — Supplier Buah Premium & Telur Berkualitas | Gresik</title>
<meta name="description" content="<?= e($s['hero_desc'] ?? '') ?>">
<meta property="og:title" content="<?= $brand ?> — <?= $tagline ?>">
<meta property="og:description" content="<?= e($s['hero_desc'] ?? '') ?>">
<meta property="og:type" content="website">
<meta property="og:locale" content="id_ID">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/site.css">
<style>:root{--green-900:<?= $cp ?>;--green-500:<?= $cs ?>;}</style>
</head>
<body>

<!-- Topbar -->
<div class="topbar"><div class="wrap">
  <span>🌿 <?= $tagline ?></span>
  <span class="hide-sm">✅ Melayani Retail, Reseller, Catering, Bakery &amp; SPPG</span>
  <span class="hide-sm">🚚 Pengiriman Cepat Area Gresik &amp; Sekitarnya</span>
</div></div>

<!-- Header -->
<header><div class="wrap">
  <a href="#beranda" class="logo">
    <span class="leaf"><svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M5 19c0-7 5-13 14-14-1 9-7 14-14 14Z" fill="currentColor"/><path d="M5 19c2-5 5-8 9-10" stroke="#0f3d22" stroke-width="1.5" stroke-linecap="round"/></svg></span>
    <span><?= $brand ?></span>
  </a>
  <nav id="nav"><ul>
    <li><a href="#beranda">Beranda</a></li>
    <li><a href="#katalog">Katalog</a></li>
    <li><a href="#bisnis">Untuk Bisnis</a></li>
    <li><a href="#testimoni">Testimoni</a></li>
    <li><a href="#kontak">Kontak</a></li>
  </ul></nav>
  <a class="nav-wa" href="<?= wa_link($wa, $umum) ?>" target="_blank" rel="noopener">
    <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15l-1.3 4.7 4.8-1.3A10 10 0 1 0 12 2Zm4.4 12c-.2-.1-1.4-.7-1.6-.8s-.4-.1-.5.1-.6.8-.8 1-.3.2-.5.1a6.6 6.6 0 0 1-3.2-2.8c-.2-.4.2-.4.6-1.2a.5.5 0 0 0 0-.5c0-.1-.5-1.3-.7-1.7s-.4-.4-.5-.4h-.5a1 1 0 0 0-.7.3A2.8 2.8 0 0 0 6 7.9a4.9 4.9 0 0 0 1 2.6 11 11 0 0 0 4.3 3.8c1.6.7 2 .6 2.4.5a2.4 2.4 0 0 0 1.6-1.1 2 2 0 0 0 .1-1.1c0-.1-.2-.2-.4-.3Z"/></svg>
    <span class="lbl">Pesan via WhatsApp</span>
  </a>
  <button class="burger" id="burger" aria-label="Menu">
    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
  </button>
</div></header>
<div id="nav-overlay" class="nav-overlay"></div>

<!-- Hero -->
<section class="hero" id="beranda"><div class="wrap">
  <div class="hero-copy reveal">
    <span class="eyebrow">🌱 Buah Premium, Telur Berkualitas</span>
    <h1><?= $brand ?></h1>
    <p class="tag"><?= $tagline ?></p>
    <p class="desc"><?= e($s['hero_desc'] ?? '') ?></p>
    <div class="pills">
      <div class="pill"><span class="ic">🍃</span> Buah Segar Berkualitas</div>
      <div class="pill"><span class="ic">🛡️</span> Higienis &amp; Terjamin</div>
      <div class="pill"><span class="ic">🚚</span> Pengiriman Cepat &amp; Aman</div>
      <div class="pill"><span class="ic">👑</span> Premium Quality</div>
    </div>
    <div class="hero-cta">
      <a class="btn btn-primary" href="<?= wa_link($wa, $umum) ?>" target="_blank" rel="noopener">Pesan Sekarang</a>
      <a class="btn btn-outline" href="#katalog">Lihat Katalog</a>
    </div>
  </div>
  <div class="hero-art reveal">
    <span class="float f1">🍎</span><span class="float f2">🍊</span>
    <span class="float f3">🍇</span><span class="float f4">🥚</span>
    <span class="basket">🧺</span>
    <div class="delivery"><span class="truck">🚚</span>
      <div><b>Same Day Delivery</b><small>Untuk area Gresik dan sekitarnya</small></div>
    </div>
  </div>
</div></section>

<!-- Kategori -->
<section class="pad" id="kategori"><div class="wrap">
  <div class="sec-head reveal">
    <span class="sec-eyebrow">Kategori Produk</span>
    <h2>Produk Segar Pilihan Kami</h2>
    <p>Dipilih dari kualitas terbaik untuk kesegaran yang bisa kamu rasakan</p>
  </div>
  <div class="cat-grid reveal">
    <?php foreach ($categories as $c): ?>
    <div class="cat">
      <div class="circle"><?= e($c['icon']) ?></div>
      <h3><?= e($c['name']) ?></h3>
      <a class="mini" href="#katalog">Lihat Produk</a>
    </div>
    <?php endforeach; ?>
  </div>
</div></section>

<!-- Katalog -->
<section class="pad pad-accent" id="katalog"><div class="wrap">
  <div class="sec-head reveal">
    <span class="sec-eyebrow">Katalog Lengkap</span>
    <h2>Buah Import &amp; Telur Premium</h2>
    <p>Tap <b>"Tanya Harga"</b> untuk cek harga &amp; stok langsung via WhatsApp.</p>
  </div>
  <?php foreach ($grouped as $catName => $items): ?>
  <div class="cat-label reveal"><?= e($catName) ?></div>
  <div class="prod-grid reveal">
    <?php foreach ($items as $p):
      $pm = "Halo {$s['brand_name']} 👋\nSaya tertarik dengan produk:\n{$p['name']}\nMohon informasi harga dan ketersediaan stok.\nTerima kasih.";
      $plink = wa_link($wa, $pm);
      $thumb = $p['image'] ? '<img src="/assets/uploads/' . e($p['image']) . '" alt="' . e($p['name']) . '" style="width:100%;height:100%;object-fit:cover;border-radius:14px">' : '<span>' . e($p['icon']) . '</span>';
    ?>
    <div class="prod">
      <div class="thumb"><?= $thumb ?></div>
      <h4><?= e($p['name']) ?></h4>
      <div class="ptag"><?= e($p['category']) ?></div>
      <div class="actions">
        <a class="ask" href="<?= $plink ?>" target="_blank" rel="noopener">Tanya Harga</a>
        <a class="wa-ic" href="<?= $plink ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15l-1.3 4.7 4.8-1.3A10 10 0 1 0 12 2Zm4.4 12c-.2-.1-1.4-.7-1.6-.8s-.4-.1-.5.1-.6.8-.8 1-.3.2-.5.1a6.6 6.6 0 0 1-3.2-2.8c-.2-.4.2-.4.6-1.2a.5.5 0 0 0 0-.5c0-.1-.5-1.3-.7-1.7s-.4-.4-.5-.4h-.5a1 1 0 0 0-.7.3A2.8 2.8 0 0 0 6 7.9a4.9 4.9 0 0 0 1 2.6 11 11 0 0 0 4.3 3.8c1.6.7 2 .6 2.4.5a2.4 2.4 0 0 0 1.6-1.1 2 2 0 0 0 .1-1.1c0-.1-.2-.2-.4-.3Z"/></svg>
        </a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endforeach; ?>
</div></section>

<!-- Bisnis -->
<section class="pad" id="bisnis"><div class="wrap">
  <div class="bisnis-grid">
    <div class="bisnis-card reveal">
      <h2>Solusi Kebutuhan Buah &amp; Telur untuk Bisnismu</h2>
      <ul class="biz-list">
        <li><span class="bi">🍽️</span> Supplier Restoran</li>
        <li><span class="bi">🥘</span> Supplier Catering</li>
        <li><span class="bi">🥐</span> Supplier Bakery</li>
        <li><span class="bi">🏨</span> Supplier Hotel</li>
        <li><span class="bi">🏫</span> Supplier SPPG</li>
        <li><span class="bi">🤝</span> Reseller</li>
      </ul>
      <div style="margin-top:26px">
        <a class="btn btn-primary" href="<?= wa_link($wa, $bisnis) ?>" target="_blank" rel="noopener">Konsultasikan Kebutuhan Bisnis</a>
      </div>
    </div>
    <div class="why-card reveal">
      <h2>Kualitas Premium, Layanan Terbaik</h2>
      <ul class="why-list">
        <li><span class="ck">✓</span> Produk fresh setiap hari</li>
        <li><span class="ck">✓</span> Kualitas premium &amp; terjamin</li>
        <li><span class="ck">✓</span> Pengiriman cepat &amp; aman</li>
        <li><span class="ck">✓</span> Harga kompetitif untuk semua</li>
        <li><span class="ck">✓</span> Supplier terpercaya</li>
        <li><span class="ck">✓</span> Stok stabil &amp; konsisten</li>
      </ul>
    </div>
  </div>
</div></section>

<!-- Testimoni -->
<section class="pad pad-accent" id="testimoni"><div class="wrap">
  <div class="sec-head reveal">
    <span class="sec-eyebrow">Testimoni Pelanggan</span>
    <h2><?= e($s['testi_headline'] ?? 'Dipercaya 1000+ Pelanggan') ?></h2>
    <p>Cerita dari keluarga, reseller, dan mitra bisnis kami</p>
  </div>
  <div class="testi-grid reveal">
    <?php foreach ($testimonials as $t):
      preg_match_all('/\b\w/u', $t['name'], $m);
      $initials = strtoupper(implode('', array_slice($m[0], 0, 2)));
    ?>
    <div class="testi">
      <div class="stars"><?= str_repeat('★', (int)$t['rating']) ?></div>
      <p>"<?= e($t['message']) ?>"</p>
      <div class="who">
        <span class="av"><?= e($initials) ?></span>
        <div><b><?= e($t['name']) ?></b><small><?= e($t['role']) ?></small></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div></section>

<!-- Area & Kontak -->
<section class="pad" id="kontak"><div class="wrap">
  <div class="area reveal">
    <h2>Area Pengiriman</h2>
    <p class="sub">Melayani pengiriman cepat ke seluruh Gresik &amp; sekitarnya</p>
    <div class="chips">
      <?php foreach (array_filter(array_map('trim', explode(',', $s['delivery_area'] ?? ''))) as $a): ?>
      <span class="chip">📍 <?= e($a) ?></span>
      <?php endforeach; ?>
    </div>
    <div style="margin-top:30px">
      <a class="btn btn-primary" style="background:var(--green-500)" href="<?= wa_link($wa, $umum) ?>" target="_blank" rel="noopener">Hubungi Kami via WhatsApp</a>
    </div>
  </div>
</div></section>

<!-- Footer -->
<footer><div class="wrap">
  <div class="foot-grid">
    <div>
      <div class="logo"><span class="leaf"><svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M5 19c0-7 5-13 14-14-1 9-7 14-14 14Z" fill="currentColor"/></svg></span><span><?= $brand ?></span></div>
      <p class="fdesc"><?= $tagline ?>. Supplier buah premium &amp; telur berkualitas untuk keluarga dan bisnis.</p>
    </div>
    <div>
      <h5>Kontak</h5>
      <ul>
        <li><a href="<?= wa_link($wa, $umum) ?>" target="_blank" rel="noopener">💬 <?= e($s['wa_number'] ?? '') ?></a></li>
        <li><a href="https://instagram.com/<?= e($s['instagram'] ?? '') ?>" target="_blank" rel="noopener">📷 @<?= e($s['instagram'] ?? '') ?></a></li>
        <li>📍 <?= e($s['address'] ?? '') ?></li>
      </ul>
    </div>
    <div>
      <h5>Navigasi</h5>
      <ul>
        <li><a href="#beranda">Beranda</a></li>
        <li><a href="#katalog">Katalog</a></li>
        <li><a href="#bisnis">Untuk Bisnis</a></li>
        <li><a href="#testimoni">Testimoni</a></li>
      </ul>
    </div>
  </div>
  <div class="foot-bottom">© <?= date('Y') ?> <?= $brand ?>. <?= $tagline ?>.</div>
</div></footer>

<!-- Floating WA button -->
<a class="fab" href="<?= wa_link($wa, $umum) ?>" target="_blank" rel="noopener">
  <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15l-1.3 4.7 4.8-1.3A10 10 0 1 0 12 2Zm4.4 12c-.2-.1-1.4-.7-1.6-.8s-.4-.1-.5.1-.6.8-.8 1-.3.2-.5.1a6.6 6.6 0 0 1-3.2-2.8c-.2-.4.2-.4.6-1.2a.5.5 0 0 0 0-.5c0-.1-.5-1.3-.7-1.7s-.4-.4-.5-.4h-.5a1 1 0 0 0-.7.3A2.8 2.8 0 0 0 6 7.9a4.9 4.9 0 0 0 1 2.6 11 11 0 0 0 4.3 3.8c1.6.7 2 .6 2.4.5a2.4 2.4 0 0 0 1.6-1.1 2 2 0 0 0 .1-1.1c0-.1-.2-.2-.4-.3Z"/></svg>
  <span>Chat WhatsApp</span>
</a>

<script>
const burger  = document.getElementById('burger');
const nav     = document.getElementById('nav');
const overlay = document.getElementById('nav-overlay');
function openNav()  { nav.classList.add('open'); overlay.classList.add('open'); document.body.style.overflow='hidden'; }
function closeNav() { nav.classList.remove('open'); overlay.classList.remove('open'); document.body.style.overflow=''; }
burger.addEventListener('click', () => nav.classList.contains('open') ? closeNav() : openNav());
overlay.addEventListener('click', closeNav);
nav.querySelectorAll('a').forEach(a => a.addEventListener('click', closeNav));

const io = new IntersectionObserver(es => {
  es.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
}, { threshold: .12 });
document.querySelectorAll('.reveal').forEach(el => io.observe(el));
</script>
</body>
</html>
