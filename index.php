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
<link rel="stylesheet" href="/assets/site.css">
<style>:root{--green-900:<?= $cp ?>;--green-500:<?= $cs ?>;}</style>
</head>
<body>

<!-- Topbar -->
<div class="topbar"><div class="wrap">
  <span><?= $tagline ?></span>
  <span class="hide-sm">Melayani Retail, Reseller, Catering, Bakery &amp; SPPG</span>
  <span class="hide-sm">Pengiriman Cepat Area Gresik &amp; Sekitarnya</span>
</div></div>

<!-- Header -->
<header><div class="wrap">
  <a href="#beranda" class="logo">
    <span class="leaf"><svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M5 19c0-7 5-13 14-14-1 9-7 14-14 14Z" fill="currentColor"/><path d="M5 19c2-5 5-8 9-10" stroke="#0f3d22" stroke-width="1.5" stroke-linecap="round"/></svg></span>
    <span><?= $brand ?></span>
  </a>
  <nav id="nav">
    <div class="nav-brand">
      <span class="leaf"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 19c0-7 5-13 14-14-1 9-7 14-14 14Z" fill="currentColor"/></svg></span>
      <span><?= $brand ?></span>
    </div>
    <ul>
      <li><a href="#beranda">Beranda</a></li>
      <li><a href="#katalog">Katalog</a></li>
      <li><a href="#bisnis">Untuk Bisnis</a></li>
      <li><a href="#testimoni">Testimoni</a></li>
      <li><a href="#kontak">Kontak</a></li>
    </ul>
    <a class="nav-wa" href="<?= wa_link($wa, $umum) ?>" target="_blank" rel="noopener">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15l-1.3 4.7 4.8-1.3A10 10 0 1 0 12 2Zm4.4 12c-.2-.1-1.4-.7-1.6-.8s-.4-.1-.5.1-.6.8-.8 1-.3.2-.5.1a6.6 6.6 0 0 1-3.2-2.8c-.2-.4.2-.4.6-1.2a.5.5 0 0 0 0-.5c0-.1-.5-1.3-.7-1.7s-.4-.4-.5-.4h-.5a1 1 0 0 0-.7.3A2.8 2.8 0 0 0 6 7.9a4.9 4.9 0 0 0 1 2.6 11 11 0 0 0 4.3 3.8c1.6.7 2 .6 2.4.5a2.4 2.4 0 0 0 1.6-1.1 2 2 0 0 0 .1-1.1c0-.1-.2-.2-.4-.3Z"/></svg>
      <span class="lbl">Pesan via WhatsApp</span>
    </a>
  </nav>
  <a class="nav-wa hdr-wa" href="<?= wa_link($wa, $umum) ?>" target="_blank" rel="noopener">
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
    <span class="eyebrow">Supplier Premium — Gresik, Jawa Timur</span>
    <h1><?= $brand ?></h1>
    <p class="tag"><?= $tagline ?></p>
    <p class="desc"><?= e($s['hero_desc'] ?? '') ?></p>
    <div class="pills">
      <div class="pill"><span class="ic">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M5 19c0-7 5-13 14-14-1 9-7 14-14 14Z" fill="currentColor"/></svg>
      </span> Segar Setiap Hari</div>
      <div class="pill"><span class="ic">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M12 22c5.52 0 10-4.48 10-10S17.52 2 12 2 2 6.48 2 12s4.48 10 10 10zm-1-7l-3-3 1.41-1.41L11 12.17l4.59-4.58L17 9l-6 6z" fill="currentColor"/></svg>
      </span> Higienis &amp; Terjamin</div>
      <div class="pill"><span class="ic">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z" fill="currentColor"/></svg>
      </span> Pengiriman Cepat</div>
      <div class="pill"><span class="ic">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="currentColor"/></svg>
      </span> Kualitas Premium</div>
    </div>
    <div class="hero-cta">
      <a class="btn btn-primary" href="<?= wa_link($wa, $umum) ?>" target="_blank" rel="noopener">Pesan Sekarang</a>
      <a class="btn btn-outline" href="#katalog">Lihat Katalog</a>
    </div>
  </div>
  <div class="hero-art reveal">
    <div class="basket">🌿</div>
    <div class="delivery">
      <svg class="truck" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18 18.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm1.5-9.5H17V7H4a1 1 0 0 0-1 1v9h2a3 3 0 0 0 6 0h4a3 3 0 0 0 6 0h1v-3.5L19.5 9zM7 18.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/></svg>
      <div><b>Same Day Delivery</b><small>Area Gresik &amp; sekitarnya</small></div>
    </div>
    <div class="hero-art-body">
      <div class="hero-art-label">Dipercaya Ratusan Pelanggan</div>
      <div class="hero-art-stats">
        <div class="hero-art-stat"><span data-count="1000" data-suffix="+">0</span><small>Pelanggan</small></div>
        <div class="hero-art-stat"><span data-count="5" data-suffix="+">0</span><small>Tahun Berdiri</small></div>
        <div class="hero-art-stat"><span data-count="10" data-suffix="+">0</span><small>Jenis Produk</small></div>
      </div>
      <div class="hero-art-tags">
        <span class="hero-art-tag">Buah Import</span>
        <span class="hero-art-tag">Buah Lokal</span>
        <span class="hero-art-tag">Telur Premium</span>
        <span class="hero-art-tag">B2B &amp; Retail</span>
      </div>
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
  <div class="cat-grid stagger">
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
  <div class="prod-grid stagger">
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
        <li><span class="bi"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.06 22.99h1.66c.84 0 1.53-.64 1.63-1.46L23 5.05h-5V1h-1.97v4.05h-4.97l.3 2.34c1.71.47 3.31 1.32 4.27 2.26 1.44 1.42 2.43 2.89 2.43 5.29v8.05zM1 21.99V21h15.03v.99c0 .55-.45 1-1.01 1H2.01c-.56 0-1.01-.45-1.01-1zm15.03-7c0-3.5-2.25-5.11-3.86-6.27-.92-.66-1.6-1.14-1.6-1.73 0-.59.68-1.07 1.6-1.73C13.78 4.11 16.03 2.5 16.03 0H1c0 2.5 2.25 4.11 3.86 5.27.92.66 1.6 1.14 1.6 1.73 0 .59-.68 1.07-1.6 1.73C3.25 9.89 1 11.5 1 14.99v1.02h15.03v-1.02z"/></svg></span> Supplier Restoran</li>
        <li><span class="bi"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3L2 12h3v8h6v-5h2v5h6v-8h3L12 3z"/></svg></span> Supplier Catering</li>
        <li><span class="bi"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20 3H4v10c0 2.21 1.79 4 4 4h6c2.21 0 4-1.79 4-4v-3h2c1.11 0 2-.89 2-2V5c0-1.11-.89-2-2-2zm0 5h-2V5h2v3zM4 19h16v2H4z"/></svg></span> Supplier Bakery</li>
        <li><span class="bi"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M7 13c1.66 0 3-1.34 3-3S8.66 7 7 7s-3 1.34-3 3 1.34 3 3 3zm12-6h-8v7H3V5H1v15h2v-3h18v3h2v-9c0-2.21-1.79-4-4-4z"/></svg></span> Supplier Hotel</li>
        <li><span class="bi"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg></span> Supplier SPPG</li>
        <li><span class="bi"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg></span> Reseller</li>
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
  <div class="testi-grid stagger">
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
      <span class="chip"><svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg> <?= e($a) ?></span>
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
        <li><a href="<?= wa_link($wa, $umum) ?>" target="_blank" rel="noopener"><?= e($s['wa_number'] ?? '') ?></a></li>
        <li><a href="https://instagram.com/<?= e($s['instagram'] ?? '') ?>" target="_blank" rel="noopener">@<?= e($s['instagram'] ?? '') ?></a></li>
        <li><?= e($s['address'] ?? '') ?></li>
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
<div class="watermark">Crafted by <span>faithdeveloper</span> &middot; <a href="mailto:choirrozikin17@gmail.com">choirrozikin17@gmail.com</a></div>

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
document.addEventListener('keydown', e => { if (e.key === 'Escape' && nav.classList.contains('open')) closeNav(); });

/* Scroll reveal (reveal + stagger groups) */
const io = new IntersectionObserver(es => {
  es.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
}, { threshold: .12, rootMargin: '0px 0px -8% 0px' });
document.querySelectorAll('.reveal, .stagger').forEach(el => io.observe(el));

/* Header shadow on scroll */
const header = document.querySelector('header');
const onScroll = () => header.classList.toggle('scrolled', window.scrollY > 8);
onScroll();
window.addEventListener('scroll', onScroll, { passive: true });

/* Animated stat counters */
const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const animateCount = el => {
  const raw = el.dataset.count, num = parseInt(raw, 10);
  if (reduce || isNaN(num)) { el.textContent = raw + (el.dataset.suffix || ''); return; }
  const dur = 1400, t0 = performance.now(), suf = el.dataset.suffix || '';
  const tick = now => {
    const p = Math.min((now - t0) / dur, 1);
    const eased = 1 - Math.pow(1 - p, 3);
    el.textContent = Math.round(num * eased).toLocaleString('id-ID') + (p < 1 ? '' : suf);
    if (p < 1) requestAnimationFrame(tick);
  };
  requestAnimationFrame(tick);
};
const statIo = new IntersectionObserver(es => {
  es.forEach(e => { if (e.isIntersecting) { animateCount(e.target); statIo.unobserve(e.target); } });
}, { threshold: .6 });
document.querySelectorAll('[data-count]').forEach(el => statIo.observe(el));
</script>
</body>
</html>
