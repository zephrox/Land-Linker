<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
auth_start_session();

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db_queries.php';

// IMPORTANT: your project folder is Land-Linker
define('BASE_URL', '/Land-Linker/');
?>