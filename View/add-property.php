<?php
require_once __DIR__ . '/../Model/init.php';
require_login(BASE_URL . 'View/login.php');

redirect(BASE_URL . 'View/property-create.php');
