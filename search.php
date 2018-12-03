<?php

require_once('data.php');
require_once('functions.php');
require_once('config.php');

$search = trim($_GET['search'] ?? '');

if ($search) {

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

        // экранирование данных в запросе
        $search = mysqli_real_escape_string($link, $search);
        $sql = "SELECT l.title,l.start_price,l.image_url,l.description,date_end,c.name AS category FROM lots l " .
            "JOIN categories c ON c.id = l.category_id " .
            "WHERE MATCH (title,description) AGAINST ('$search') " .
            "ORDER BY l.date_add DESC";


        $result = mysqli_query($link, $sql);

        if ($result) {
            $ads = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $main_content = select_error($link);
        }
    };
};
if (!$main_content) {
    if (count($ads)) {
        $main_content = include_template('search.php', [
            'ads' => $ads,
            'categories' => $categories
        ]);

    } else {
        $main_content = '<p>По вашему запросу ничего не найдено</p>';
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
