<?php
require __DIR__ . '/../data/session.php';
session_boot();
session_destroy();
header('Location: /admin/login.php');
exit;
