<?php
require_once 'functions.php';
require_once 'config/config.php';
require_once 'Database.php';

session_start();

$dbHelper = new Database(...$db_cfg);

if ($dbHelper->getLastError()) {
    show_error( $content, $dbHelper->getLastError() );
} else {

    $dbHelper->executeQuery( 'SELECT id, name, style_name FROM categories ORDER BY id' );

    if (!$dbHelper->getLastError()) {
        $categories = $dbHelper->getResultAsArray();
    } else {
        show_error( $content, $dbHelper->getLastError() );
    }

    $cur_page = (int)($_GET['page'] ?? 1);

    $cur_filter = (int)($_GET['filter'] ?? 0);
    $cur_filter = ($cur_filter===0)?'not null':$cur_filter;
    $page_items_count = 9;

    $dbHelper->executeQuery( 'SELECT COUNT(*) as cnt FROM lots  WHERE category_id = ?
            ORDER BY date_add DESC', [$cur_filter] );
    $total_items_count = $dbHelper->getResultAsArray()[0]['cnt'];
    $pages_count = ceil( $total_items_count / $page_items_count );
    $offset = ($cur_page - 1) * $page_items_count;

    $sql = 'SELECT l.id, l.title,l.start_price,l.image_url,date_end,c.name AS category
            FROM lots l
            JOIN categories c ON c.id = l.category_id
            WHERE l.category_id = ?
            ORDER BY l.date_add DESC  LIMIT ? OFFSET ?';

    $dbHelper->executeQuery( $sql, [$cur_filter, $page_items_count, $offset] );

    if (!$dbHelper->getLastError()) {
        $ads = $dbHelper->getResultAsArray();
        $navigation = include_template( 'navigation.php', [
            'categories' => $categories,
            'cur_filter' => $cur_filter
        ] );
        $pagination = include_template( 'pagination.php', [
            'categories' => $categories,
            'pages' => range( 1, $pages_count ),
            'pages_count' => $pages_count,
            'cur_page' => $cur_page,
            'page_name' => 'all-lots.php',
            'cur_filter' => $cur_filter
        ] );
        $main_content = include_template( 'all-lots.php', [
            'ads' => $ads,
            'categories' => $categories,
            'pagination' => $pagination,
            'cur_filter' => $cur_filter,
            'navigation' => $navigation,
        ]);

    } else {
        show_error( $content, $dbHelper->getLastError() );
    }
}
$navigation = include_template( 'navigation.php', [
    'categories' => $categories,
    'cur_filter' => $cur_filter
] );

$layout_content = include_template( 'layout.php', [
    'page_title' => 'Yeticave - Лоты по категории',
    'content' => $main_content ?? '',
    'navigation' => $navigation,
    'categories' => $categories,
] );

print($layout_content);