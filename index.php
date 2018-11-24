<?php
require_once('functions.php');
require_once('data.php');


$main_content = include_template('index.php', [
    'ads' => $ads,
    'categories' => $categories
]);

$layout_content = include_template('layout.php', [
    'page_title' => $page_title,
    'content' => $main_content,
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);