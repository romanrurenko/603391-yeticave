<?php
$db = require_once 'config/db.php';

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, "utf8");

$file_path = 'img/';
$categories = [];
$content = '';

date_default_timezone_set ('Asia/Novosibirsk');
setlocale(LC_ALL, 'ru_RU');

