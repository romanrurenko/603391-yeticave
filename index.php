<?php
require_once('data.php');
require_once('functions.php');
require_once('config.php');

session_start();
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


    $sql = 'SELECT l.id, l.title,l.start_price,l.image_url,date_end,c.name AS category
FROM lots l
JOIN categories c ON c.id = l.category_id
WHERE l.winner_id IS NULL AND date_end > now()
ORDER BY l.date_add DESC';
    $search = mysqli_real_escape_string($link, $sql);
    $result = mysqli_query($link, $sql);

    if ($result) {
        $ads = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($link);
        show_error($main_content, $error);
    }
}

if (!$main_content) {
    $main_content = include_template('index.php', [
        'ads' => $ads,
        'categories' => $categories
    ]);
}

$layout_content = include_template('layout.php', [
    'page_title' => $page_title,
    'content' => $main_content,
    'categories' => $categories,
]);

print($layout_content);