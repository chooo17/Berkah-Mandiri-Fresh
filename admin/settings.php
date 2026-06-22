<?php
declare(strict_types=1);
require __DIR__ . '/../data/db.php';
require __DIR__ . '/../data/session.php';
require __DIR__ . '/layout.php';
db_init(); auth_check();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $fields = ['brand_name','tagline','hero_desc','wa_number','instagram','address','delivery_area','color_primary','color_secondary','testi_headline'];
    foreach ($fields as $f) {
        setting_set($f, post($f));
    }
    flash_set('success', 'Pengaturan website berhasil disimpan.');
    redirect('/admin/settings.php');
}

$s = settings_all();
ob_start(); ?>
<div class="form-card" style="max-width:800px">
  <form method="post">
    <?= csrf_field() ?>

    <div class="settings-section">
      <div class="settings-head">🏷️ Identitas Brand</div>
      <div class="row2">
        <div class="fg"><label>Nama Brand</label><input type="text" name="brand_name" value="<?= e($s['brand_name']??'') ?>" required></div>
        <div class="fg"><label>Tagline</label><input type="text" name="tagline" value="<?= e($s['tagline']??'') ?>"></div>
      </div>
      <div class="fg">
        <label>Deskripsi Hero <span class="hint">(teks di bawah judul di beranda)</span></label>
        <textarea name="hero_desc"><?= e($s['hero_desc']??'') ?></textarea>
      </div>
      <div class="fg"><label>Judul Testimoni <span class="hint">(cth: Dipercaya 1000+ Pelanggan)</span></label><input type="text" name="testi_headline" value="<?= e($s['testi_headline']??'') ?>"></div>
    </div>

    <div class="settings-section">
      <div class="settings-head">📞 Kontak & Media Sosial</div>
      <div class="row2">
        <div class="fg">
          <label>Nomor WhatsApp <span class="hint">(format: 62…)</span></label>
          <input type="text" name="wa_number" value="<?= e($s['wa_number']??'') ?>" placeholder="6281232100132">
        </div>
        <div class="fg">
          <label>Instagram <span class="hint">(tanpa @)</span></label>
          <input type="text" name="instagram" value="<?= e($s['instagram']??'') ?>" placeholder="berkahmandiri.fresh">
        </div>
      </div>
      <div class="fg"><label>Alamat Gudang / Toko</label><textarea name="address"><?= e($s['address']??'') ?></textarea></div>
      <div class="fg">
        <label>Area Pengiriman <span class="hint">(pisahkan dengan koma)</span></label>
        <input type="text" name="delivery_area" value="<?= e($s['delivery_area']??'') ?>" placeholder="Gresik, Surabaya, Sidoarjo">
      </div>
    </div>

    <div class="settings-section">
      <div class="settings-head">🎨 Warna Brand</div>
      <div class="row2">
        <div class="fg">
          <label>Warna Primary <span class="hint">(header, tombol utama)</span></label>
          <div class="color-in">
            <input type="color" id="cp" value="<?= e($s['color_primary']??'#14532D') ?>" oninput="cpt.value=this.value">
            <input type="text" id="cpt" name="color_primary" value="<?= e($s['color_primary']??'#14532D') ?>" oninput="if(/^#[0-9a-f]{6}$/i.test(this.value))cp.value=this.value">
          </div>
        </div>
        <div class="fg">
          <label>Warna Secondary <span class="hint">(aksen, tombol WhatsApp)</span></label>
          <div class="color-in">
            <input type="color" id="cs" value="<?= e($s['color_secondary']??'#22C55E') ?>" oninput="cst.value=this.value">
            <input type="text" id="cst" name="color_secondary" value="<?= e($s['color_secondary']??'#22C55E') ?>" oninput="if(/^#[0-9a-f]{6}$/i.test(this.value))cs.value=this.value">
          </div>
        </div>
      </div>
      <div class="color-preview" id="cprev">
        <span class="cprev-btn cprev-primary" id="cprev-p">Warna Primary</span>
        <span class="cprev-btn cprev-secondary" id="cprev-s">Warna Secondary</span>
        <span style="font-size:13px;color:var(--muted)">← Preview langsung</span>
      </div>
    </div>

    <div class="form-actions">
      <button class="btn btn-primary" type="submit">💾 Simpan Semua Pengaturan</button>
      <a class="btn btn-ghost" href="/" target="_blank">🌐 Lihat Website</a>
    </div>
  </form>
</div>

<style>
.settings-section{margin-bottom:30px}
.settings-head{font-weight:800;font-size:14px;color:var(--g900);padding:10px 16px;background:var(--bg);border-radius:10px;margin-bottom:18px;border-left:3px solid var(--g500)}
.color-preview{display:flex;gap:12px;align-items:center;margin-top:14px;flex-wrap:wrap}
.cprev-btn{padding:10px 20px;border-radius:10px;font-weight:700;font-size:14px;color:#fff}
</style>
<script>
const cp=document.getElementById('cp'),cpt=document.getElementById('cpt');
const cs=document.getElementById('cs'),cst=document.getElementById('cst');
const pp=document.getElementById('cprev-p'),ps=document.getElementById('cprev-s');
function updatePreview(){pp.style.background=cp.value;ps.style.background=cs.value;}
[cp,cpt,cs,cst].forEach(el=>el.addEventListener('input',updatePreview));
updatePreview();
</script>
<?php
admin_layout('Pengaturan Website', ob_get_clean());
