<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Session.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/Order.php';
require_once __DIR__ . '/models/Cart.php';
require_once __DIR__ . '/models/Feedback.php';

$database = new Database();
$db = $database->getConnection();