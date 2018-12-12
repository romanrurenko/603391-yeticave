<?php
require_once('data.php');
require_once('functions.php');
require_once('config.php');

if (!$link) {
    $error = mysqli_connect_error();
    $main_content = include_template('error.php', ['error' => $error]);
} else {
    $sql = 'SELECT id, name, style_name FROM categories ORDER BY id';
    $result = mysqli_query($link, $sql);

    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($link);
        show_error($main_content, $error);
    }
}

if (!$main_content) {
    $main_content = include_template('enter.php', [
        'form' => $form,
        'errors' => $errors,
        'dict' => $dict,
        'categories' => $categories
    ]);
}

$layout_content = include_template('layout.php', [
    'page_title' => 'Yeticave - Регистрация пользователя',
    'content' => $main_content,
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
