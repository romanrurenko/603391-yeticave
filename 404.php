<?php
require_once 'functions.php';
$page_content = include_template( '404.php',[] );

$layout_content = include_template( 'layout.php', [
    'page_title' => 'Yeticave - 403 Доступ запрещен',
    'content' => $page_content
] );

print($layout_content);
