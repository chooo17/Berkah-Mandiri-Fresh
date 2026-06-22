<?php
declare(strict_types=1);
require __DIR__ . '/../data/db.php';
require __DIR__ . '/../data/session.php';
require __DIR__ . '/layout.php';
db_init(); auth_check();

$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);

// --- categories for dropdown ---
$cats = [];
$r = db()->query("SELECT name FROM categories ORDER BY sort");
while ($row = $r->fetchArray(SQLITE3_NUM)) $cats[] = $row[0];

// ---------- HANDLE POST ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $postAction = post('_action');

    $name      = post('name');
    $category  = post('category');
    $icon      = post('icon') ?: '🍎';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort      = (int)post('sort', '0');

    // Upload image
    $image = post('existing_image');
    if (!empty($_FILES['image']['name'])) {
        $ext  = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp'])) {
            $fname = uniqid('prod_') . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../assets/uploads/' . $fname);
            $image = $fname;
        }
    }

    if (empty($name)) {
        flash_set('error', 'Nama produk tidak boleh kosong.');
        redirect('/admin/products.php?action=' . ($postAction === 'update' ? 'edit&id='.$id : 'new'));
    }

    if ($postAction === 'store') {
        $st = db()->prepare("INSERT INTO products(name,category,icon,image,is_active,sort) VALUES(:n,:c,:i,:img,:a,:s)");
        $st->bindValue(':n',$name); $st->bindValue(':c',$category); $st->bindValue(':i',$icon);
        $st->bindValue(':img',$image?:null); $st->bindValue(':a',$is_active,SQLITE3_INTEGER); $st->bindValue(':s',$sort,SQLITE3_INTEGER);
        $st->execute();
        flash_set('success', "Produk \"{$name}\" berhasil ditambahkan.");
        redirect('/admin/products.php');
    } elseif ($postAction === 'update') {
        $st = db()->prepare("UPDATE products SET name=:n,category=:c,icon=:i,image=:img,is_active=:a,sort=:s WHERE id=:id");
        $st->bindValue(':n',$name); $st->bindValue(':c',$category); $st->bindValue(':i',$icon);
        $st->bindValue(':img',$image?:null); $st->bindValue(':a',$is_active,SQLITE3_INTEGER); $st->bindValue(':s',$sort,SQLITE3_INTEGER);
        $st->bindValue(':id',$id,SQLITE3_INTEGER);
        $st->execute();
        flash_set('success', "Produk \"{$name}\" berhasil diperbarui.");
        redirect('/admin/products.php');
    } elseif ($postAction === 'delete') {
        db()->exec("DELETE FROM products WHERE id=$id");
        flash_set('success', 'Produk dihapus.');
        redirect('/admin/products.php');
    }
}

// ---------- VIEWS ----------
ob_start();
if ($action === 'list') {
    $products = [];
    $r = db()->query("SELECT * FROM products ORDER BY sort ASC");
    while ($row = $r->fetchArray(SQLITE3_ASSOC)) $products[] = $row;
    ?>
    <div style="display:flex;justify-content:flex-end;margin-bottom:18px">
      <a class="btn btn-primary" href="/admin/products.php?action=new">+ Tambah Produk</a>
    </div>
    <div class="panel">
      <table>
        <thead><tr><th width="50">#</th><th>Produk</th><th>Kategori</th><th>Status</th><th style="text-align:right">Aksi</th></tr></thead>
        <tbody>
        <?php if (empty($products)): ?>
          <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:36px">Belum ada produk.</td></tr>
        <?php else: foreach ($products as $p): ?>
          <tr>
            <td><?php if ($p['image']): ?><img src="/assets/uploads/<?= e($p['image']) ?>" style="width:38px;height:38px;border-radius:8px;object-fit:cover"><?php else: ?><span style="font-size:24px"><?= e($p['icon']) ?></span><?php endif; ?></td>
            <td><b><?= e($p['name']) ?></b></td>
            <td><?= e($p['category']) ?></td>
            <td><?= $p['is_active'] ? '<span class="badge badge-on">Aktif</span>' : '<span class="badge badge-off">Nonaktif</span>' ?></td>
            <td><div class="actions" style="justify-content:flex-end">
              <a class="btn btn-ghost btn-sm" href="/admin/products.php?action=edit&id=<?= $p['id'] ?>">Edit</a>
              <form method="post" style="display:inline" onsubmit="return confirm('Hapus produk ini?')">
                <?= csrf_field() ?>
                <input type="hidden" name="_action" value="delete">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
              </form>
            </div></td>
          </tr>
        <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
    <?php
} elseif ($action === 'new' || $action === 'edit') {
    $p = ['name'=>'','category'=>$cats[0]??'','icon'=>'🍎','image'=>'','is_active'=>1,'sort'=>0];
    if ($action === 'edit' && $id) {
        $row = db()->querySingle("SELECT * FROM products WHERE id=$id", true);
        if ($row) $p = $row;
    }
    $formAction = $action === 'edit' ? 'update' : 'store';
    $title2 = $action === 'edit' ? 'Edit Produk' : 'Tambah Produk';
    ?>
    <div class="form-card">
      <form method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="_action" value="<?= $formAction ?>">
        <?php if ($action === 'edit'): ?><input type="hidden" name="id" value="<?= $id ?>"><?php endif; ?>
        <input type="hidden" name="existing_image" value="<?= e($p['image'] ?? '') ?>">

        <div class="fg">
          <label>Nama Produk <span style="color:red">*</span></label>
          <input type="text" name="name" value="<?= e($p['name']) ?>" required placeholder="cth: Apel Fuji Premium">
        </div>
        <div class="row2">
          <div class="fg">
            <label>Kategori</label>
            <select name="category">
              <?php foreach ($cats as $c): ?>
              <option value="<?= e($c) ?>" <?= $p['category']===$c?'selected':'' ?>><?= e($c) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="fg">
            <label>Ikon Emoji <span class="hint">(jika tidak ada foto)</span></label>
            <input type="text" name="icon" value="<?= e($p['icon']) ?>" maxlength="4" placeholder="🍎">
          </div>
        </div>
        <div class="fg">
          <label>Foto Produk <span class="hint">(jpg/png/webp, opsional)</span></label>
          <?php if (!empty($p['image'])): ?>
            <div style="margin-bottom:10px"><img src="/assets/uploads/<?= e($p['image']) ?>" style="height:80px;border-radius:10px;object-fit:cover"> <small style="color:var(--muted)">foto saat ini</small></div>
          <?php endif; ?>
          <input type="file" name="image" accept="image/*">
        </div>
        <div class="row2">
          <div class="fg">
            <label>Urutan <span class="hint">(angka kecil tampil duluan)</span></label>
            <input type="number" name="sort" value="<?= (int)$p['sort'] ?>">
          </div>
          <div class="fg" style="display:flex;align-items:flex-end;padding-bottom:4px">
            <label class="check">
              <input type="checkbox" name="is_active" value="1" <?= $p['is_active'] ? 'checked' : '' ?>>
              Tampilkan di website
            </label>
          </div>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><?= $action==='edit' ? '💾 Simpan Perubahan' : '+ Tambah Produk' ?></button>
          <a class="btn btn-ghost" href="/admin/products.php">Batal</a>
        </div>
      </form>
    </div>
    <?php
}
admin_layout($action === 'new' ? 'Tambah Produk' : ($action === 'edit' ? 'Edit Produk' : 'Kelola Produk'), ob_get_clean());
