<?php
require_once 'functions.php';
require_once 'config/config.php';
require_once 'Database.php';
require_once 'winner.php';

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

    $cur_page = $_GET['page'] ?? 1;
    $page_items_count = 6;

    $dbHelper->executeQuery( 'SELECT COUNT(*) as cnt FROM lots' );
    $total_items_count = $dbHelper->getResultAsArray()[0]['cnt'];
    $pages_count = ceil( $total_items_count / $page_items_count );
    $offset = ($cur_page - 1) * $page_items_count;

    $sql = 'SELECT l.id, l.title,l.start_price,l.image_url,date_end,c.name AS category
            FROM lots l
            JOIN categories c ON c.id = l.category_id
            WHERE l.winner_id IS NULL AND date_end > NOW()
            ORDER BY l.date_add DESC  LIMIT ? OFFSET ?';

    $dbHelper->executeQuery( $sql, [$page_items_count, $offset] );

    if (!$dbHelper->getLastError()) {
        $ads = $dbHelper->getResultAsArray();
        $main_content = include_template( 'index.php', [
            'ads' => $ads,
            'categories' => $categories,
            'pages' => range( 1, $pages_count ),
            'pages_count' => $pages_count,
            'cur_page' => $cur_page
        ]);

        $content = include_template( 'main.php', $main_content );
    } else {
        show_error( $content, $dbHelper->getLastError() );
    }
}

    $layout_content = include_template( 'layout.php', [
        'page_title' => 'Yeticave - Главная страница',
        'content' => $main_content ?? '',
        'categories' => $categories ?? null,
    ] );

    print($layout_content);