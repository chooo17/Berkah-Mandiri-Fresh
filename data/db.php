<?php
declare(strict_types=1);

function db(): SQLite3 {
    static $db = null;
    if ($db === null) {
        $path = getenv('RAILWAY_ENVIRONMENT') ? '/tmp/bmf.db' : __DIR__ . '/bmf.db';
        $db = new SQLite3($path);
        $db->enableExceptions(true);
        $db->exec('PRAGMA journal_mode=WAL; PRAGMA foreign_keys=ON;');
    }
    return $db;
}

function db_init(): void {
    $d = db();
    $d->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL
        );
        CREATE TABLE IF NOT EXISTS settings (
            key TEXT PRIMARY KEY,
            value TEXT
        );
        CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            icon TEXT,
            sort INTEGER DEFAULT 0
        );
        CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            category TEXT NOT NULL,
            icon TEXT DEFAULT '🍎',
            image TEXT,
            is_active INTEGER DEFAULT 1,
            sort INTEGER DEFAULT 0,
            created_at TEXT DEFAULT (datetime('now'))
        );
        CREATE TABLE IF NOT EXISTS testimonials (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            role TEXT,
            message TEXT NOT NULL,
            rating INTEGER DEFAULT 5,
            is_active INTEGER DEFAULT 1,
            sort INTEGER DEFAULT 0
        );
    ");

    // Seed if empty
    if ($d->querySingle("SELECT COUNT(*) FROM users") == 0) {
        $pw = password_hash('admin123', PASSWORD_DEFAULT);
        $d->exec("INSERT INTO users(name,username,password) VALUES('Administrator','admin','$pw')");

        $settings = [
            'brand_name'      => 'Berkah Mandiri Fresh',
            'tagline'         => 'Fresh Every Day, Quality All The Way',
            'hero_desc'       => 'Menyediakan buah segar pilihan, buah import premium, dan telur berkualitas dengan kesegaran terbaik untuk keluarga dan bisnis Anda.',
            'wa_number'       => '6281232100132',
            'instagram'       => 'berkahmandiri.fresh',
            'address'         => 'Jl. Tambang No. 49 GKA, Randuagung, Yosowilangun, Gresik, Jawa Timur',
            'delivery_area'   => 'Gresik,Surabaya,Sidoarjo,Lamongan,Area Jawa Timur lainnya',
            'color_primary'   => '#14532D',
            'color_secondary' => '#22C55E',
            'testi_headline'  => 'Dipercaya 1000+ Pelanggan',
        ];
        $st = $d->prepare("INSERT OR IGNORE INTO settings(key,value) VALUES(:k,:v)");
        foreach ($settings as $k => $v) {
            $st->bindValue(':k', $k); $st->bindValue(':v', $v); $st->execute(); $st->reset();
        }

        $cats = [['Apel Premium','🍎',1],['Anggur Import','🍇',2],['Buah Import & Lokal','🥝',3],['Buah Musiman','🍊',4],['Telur Premium','🥚',5]];
        $sc = $d->prepare("INSERT INTO categories(name,icon,sort) VALUES(:n,:i,:s)");
        foreach ($cats as $c) { $sc->bindValue(':n',$c[0]); $sc->bindValue(':i',$c[1]); $sc->bindValue(':s',$c[2]); $sc->execute(); $sc->reset(); }

        $prods = [
            ['Apel Fuji Premium','Buah Import','🍎'],['Anggur Autumn Black','Buah Import','🍇'],
            ['Anggur Aussie','Buah Import','🍇'],['Kiwi Green','Buah Import','🥝'],
            ['Kiwi Gold','Buah Import','🥝'],['Jeruk Mandarin','Buah Import','🍊'],
            ['Leci Premium','Buah Import','🍒'],
            ['Telur Omega','Telur Premium','🥚'],['Telur Ayam Premium','Telur Premium','🥚'],['Telur Puyuh','Telur Premium','🥚'],
        ];
        $sp = $d->prepare("INSERT INTO products(name,category,icon,is_active,sort) VALUES(:n,:c,:i,1,:s)");
        foreach ($prods as $i => $p) { $sp->bindValue(':n',$p[0]); $sp->bindValue(':c',$p[1]); $sp->bindValue(':i',$p[2]); $sp->bindValue(':s',$i+1); $sp->execute(); $sp->reset(); }

        $tt = [
            ['Dewi S.','Reseller Buah','Buahnya selalu segar, pengiriman cepat, pelayanan ramah. Langganan terus!',5],
            ['Rizki A.','Owner Bakery','Telur premium kualitas konsisten, cocok buat kebutuhan bakery kami setiap hari.',5],
            ['Fitri N.','Catering','Buah import lengkap dan harga bersaing. Stok stabil untuk kebutuhan catering.',5],
        ];
        $st2 = $d->prepare("INSERT INTO testimonials(name,role,message,rating,is_active,sort) VALUES(:n,:r,:m,:ra,1,:s)");
        foreach ($tt as $i => $t) { $st2->bindValue(':n',$t[0]); $st2->bindValue(':r',$t[1]); $st2->bindValue(':m',$t[2]); $st2->bindValue(':ra',$t[3]); $st2->bindValue(':s',$i+1); $st2->execute(); $st2->reset(); }
    }
}

function setting(string $key, string $default = ''): string {
    $v = db()->querySingle("SELECT value FROM settings WHERE key='" . db()->escapeString($key) . "'");
    return ($v !== null && $v !== false) ? (string)$v : $default;
}

function settings_all(): array {
    $out = [];
    $r = db()->query("SELECT key,value FROM settings");
    while ($row = $r->fetchArray(SQLITE3_ASSOC)) $out[$row['key']] = $row['value'];
    return $out;
}

function setting_set(string $key, string $value): void {
    $st = db()->prepare("INSERT INTO settings(key,value) VALUES(:k,:v) ON CONFLICT(key) DO UPDATE SET value=:v");
    $st->bindValue(':k', $key); $st->bindValue(':v', $value); $st->execute();
}

function wa_link(string $number, string $msg): string {
    return 'https://wa.me/' . preg_replace('/\D/', '', $number) . '?text=' . rawurlencode($msg);
}

function h(mixed $v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function e(mixed $v): string { return h($v); }
