<?php
declare(strict_types=1);
require __DIR__ . '/../data/db.php';
require __DIR__ . '/../data/session.php';
require __DIR__ . '/layout.php';
db_init(); auth_check();

$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $pA = post('_action');
    $name      = post('name');
    $role      = post('role');
    $message   = post('message');
    $rating    = max(1, min(5, (int)post('rating', '5')));
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort      = (int)post('sort', '0');

    if (empty($name) || empty($message)) {
        flash_set('error', 'Nama dan pesan wajib diisi.');
        redirect('/admin/testimonials.php?action=' . ($pA==='update'?'edit&id='.$id:'new'));
    }

    if ($pA === 'store') {
        $st = db()->prepare("INSERT INTO testimonials(name,role,message,rating,is_active,sort) VALUES(:n,:r,:m,:ra,:a,:s)");
        $st->bindValue(':n',$name);$st->bindValue(':r',$role);$st->bindValue(':m',$message);
        $st->bindValue(':ra',$rating,SQLITE3_INTEGER);$st->bindValue(':a',$is_active,SQLITE3_INTEGER);$st->bindValue(':s',$sort,SQLITE3_INTEGER);
        $st->execute();
        flash_set('success', "Testimoni dari \"{$name}\" berhasil ditambahkan.");
    } elseif ($pA === 'update') {
        $st = db()->prepare("UPDATE testimonials SET name=:n,role=:r,message=:m,rating=:ra,is_active=:a,sort=:s WHERE id=:id");
        $st->bindValue(':n',$name);$st->bindValue(':r',$role);$st->bindValue(':m',$message);
        $st->bindValue(':ra',$rating,SQLITE3_INTEGER);$st->bindValue(':a',$is_active,SQLITE3_INTEGER);$st->bindValue(':s',$sort,SQLITE3_INTEGER);
        $st->bindValue(':id',$id,SQLITE3_INTEGER);
        $st->execute();
        flash_set('success', "Testimoni diperbarui.");
    } elseif ($pA === 'delete') {
        db()->exec("DELETE FROM testimonials WHERE id=$id");
        flash_set('success', 'Testimoni dihapus.');
    }
    redirect('/admin/testimonials.php');
}

ob_start();
if ($action === 'list') {
    $items = [];
    $r = db()->query("SELECT * FROM testimonials ORDER BY sort ASC");
    while ($row = $r->fetchArray(SQLITE3_ASSOC)) $items[] = $row;
    ?>
    <div style="display:flex;justify-content:flex-end;margin-bottom:18px">
      <a class="btn btn-primary" href="/admin/testimonials.php?action=new">+ Tambah Testimoni</a>
    </div>
    <div class="panel">
      <table>
        <thead><tr><th>Nama</th><th>Peran</th><th>Pesan</th><th>Rating</th><th>Status</th><th style="text-align:right">Aksi</th></tr></thead>
        <tbody>
        <?php if (empty($items)): ?>
          <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:36px">Belum ada testimoni.</td></tr>
        <?php else: foreach ($items as $t): ?>
          <tr>
            <td><b><?= e($t['name']) ?></b></td>
            <td style="color:var(--muted)"><?= e($t['role']) ?></td>
            <td style="max-width:280px;color:var(--muted)"><?= e(mb_strimwidth($t['message'],0,70,'…')) ?></td>
            <td style="color:#f5b301;letter-spacing:2px"><?= str_repeat('★',(int)$t['rating']) ?></td>
            <td><?= $t['is_active']?'<span class="badge badge-on">Aktif</span>':'<span class="badge badge-off">Nonaktif</span>' ?></td>
            <td><div class="actions" style="justify-content:flex-end">
              <a class="btn btn-ghost btn-sm" href="/admin/testimonials.php?action=edit&id=<?= $t['id'] ?>">Edit</a>
              <form method="post" style="display:inline" onsubmit="return confirm('Hapus testimoni ini?')">
                <?= csrf_field() ?>
                <input type="hidden" name="_action" value="delete">
                <input type="hidden" name="id" value="<?= $t['id'] ?>">
                <button class="btn btn-danger btn-sm">Hapus</button>
              </form>
            </div></td>
          </tr>
        <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
    <?php
} else {
    $t = ['name'=>'','role'=>'','message'=>'','rating'=>5,'is_active'=>1,'sort'=>0];
    if ($action === 'edit' && $id) {
        $row = db()->querySingle("SELECT * FROM testimonials WHERE id=$id", true);
        if ($row) $t = $row;
    }
    $pA = $action === 'edit' ? 'update' : 'store';
    ?>
    <div class="form-card">
      <form method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="_action" value="<?= $pA ?>">
        <?php if ($action==='edit'): ?><input type="hidden" name="id" value="<?= $id ?>"><?php endif; ?>
        <div class="row2">
          <div class="fg"><label>Nama Pelanggan <span style="color:red">*</span></label><input type="text" name="name" value="<?= e($t['name']) ?>" required></div>
          <div class="fg"><label>Peran <span class="hint">(cth: Reseller, Catering)</span></label><input type="text" name="role" value="<?= e($t['role']) ?>"></div>
        </div>
        <div class="fg"><label>Pesan Testimoni <span style="color:red">*</span></label><textarea name="message" required><?= e($t['message']) ?></textarea></div>
        <div class="row2">
          <div class="fg">
            <label>Rating Bintang (1–5)</label>
            <div style="display:flex;gap:8px;flex-wrap:wrap">
              <?php for($i=1;$i<=5;$i++): ?>
              <label style="display:flex;align-items:center;gap:4px;cursor:pointer;font-weight:600">
                <input type="radio" name="rating" value="<?= $i ?>" <?= $t['rating']==$i?'checked':'' ?> style="accent-color:var(--g500)">
                <?= str_repeat('★',$i) ?>
              </label>
              <?php endfor; ?>
            </div>
          </div>
          <div class="fg" style="display:flex;align-items:flex-end;padding-bottom:4px">
            <label class="check"><input type="checkbox" name="is_active" value="1" <?= $t['is_active']?'checked':'' ?>> Tampilkan di website</label>
          </div>
        </div>
        <div class="fg"><label>Urutan</label><input type="number" name="sort" value="<?= (int)$t['sort'] ?>"></div>
        <div class="form-actions">
          <button class="btn btn-primary"><?= $action==='edit'?'💾 Simpan Perubahan':'+ Tambah Testimoni' ?></button>
          <a class="btn btn-ghost" href="/admin/testimonials.php">Batal</a>
        </div>
      </form>
    </div>
    <?php
}
admin_layout($action==='new'?'Tambah Testimoni':($action==='edit'?'Edit Testimoni':'Kelola Testimoni'), ob_get_clean());
