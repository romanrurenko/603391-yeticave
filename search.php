<?php

require_once('data.php');
require_once('functions.php');
require_once('config.php');

$search = trim($_GET['search'] ?? '');

if ($search) {

    if (!$link) {
        $error = mysqli_connect_error();
        show_error($content, $error);

    } else {

        $sql = 'SELECT `id`, `name`, `style_name` FROM categories ORDER BY `id`';
        $result = mysqli_query($link, $sql);

        if ($result) {
            $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $error = mysqli_error($link);
            show_error($content, $error);
        }

        // экранирование данных в запросе
        $search = mysqli_real_escape_string($link, $search);
        $sql = 'SELECT l.title,l.start_price,l.image_url,l.description,date_end,c.name AS category FROM lots l ' .
            'JOIN categories c ON c.id = l.category_id ' .
            'WHERE MATCH (title,description) AGAINST ("' . $search . '" IN BOOLEAN MODE) ' .
            'ORDER BY l.date_add DESC';


        $result = mysqli_query($link, $sql);

        if ($result) {
            $ads = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $error = mysqli_error($link);
            show_error($content, $error);
        }
    }
}

if (!$content) {
    if (count($ads)) {
        $content = include_template('search.php', [
            'ads' => $ads,
            'categories' => $categories
        ]);

    } else {
        $content = '<p>По вашему запросу ничего не найдено</p>';
    }
};

$layout_content = include_template('layout.php', [
    'page_title' => 'Yeticave - Поиск лотов',
    'content' => $content,
    'categories' => $categories,
]);

print($layout_content);
