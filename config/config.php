<?php
require_once 'config/db.php';

$db_cfg = [ $db['host'], $db['user'], $db['password'], $db['database']];

$link = mysqli_connect( $db['host'], $db['user'], $db['password'], $db['database'] );
mysqli_set_charset( $link, 'utf8' );

$lot_image_path = 'img/lots/';
$avatar_path = 'img/avatars/';
$categories = [];
$content = '';

date_default_timezone_set( 'Asia/Novosibirsk' );
setlocale( LC_ALL, 'ru_RU' );

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

require_once 'vendor/autoload.php';