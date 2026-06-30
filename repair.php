<?php
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/';
require 'public/index.php';
print_r(\App\Helpers\DatabaseHelper::repair());
