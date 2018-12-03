<?php

require_once('data.php');
require_once('functions.php');
require_once('config.php');


if (!$link) {
    $error = mysqli_connect_error();
    $main_content = include_template('error.php', ['error' => $error]);

} else {

    $sql = "SELECT `id`, `name`, `style_name` FROM categories ORDER BY `id`";
    $result = mysqli_query($link, $sql);

    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $main_content = select_error($link);
    };
};

// проверка наличия параметра id в запросе
$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {

    $search = mysqli_real_escape_string($link, $search);
    $sql = "SELECT l.id, l.title,l.start_price,l.image_url,l.description,date_end,c.name AS category FROM lots l " .
        "JOIN categories c ON c.id = l.category_id " .
        "WHERE l.id=" . $id;
    $result = mysqli_query($link, $sql);

    if ($result) {
        $lot = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $main_content = select_error($link);
    };
};

if (!$main_content) {
    if (count($lot)) {

        $main_content = include_template('lot.php', [
            'lot' => $lot[0],
            'categories' => $categories
        ]);

    } else {
        http_response_code(404);
        $main_content = include_template('404.php', ['categories' => $categories]);
    }
};

$layout_content = include_template('layout.php', [
    'page_title' => $page_title,
    'content' => $main_content,
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);





