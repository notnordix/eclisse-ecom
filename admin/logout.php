<?php
require_once '../config/config.php';
require_once '../models/Admin.php';

$adminModel = new Admin();
$adminModel->logout();

redirect('admin/login.php');
