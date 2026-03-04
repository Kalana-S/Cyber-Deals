<?php
require_once '../app/bootstrap.php';

Session::start();      
Session::destroy();   

header('Location: index.php');
exit;
