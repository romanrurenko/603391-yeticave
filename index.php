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
    $page_items_count = 9;
    $cur_filter = (int)($_GET['filter'] ?? 0);
    $cur_filter = ($cur_filter===0)?'not null':$cur_filter;

    $dbHelper->executeQuery( 'SELECT COUNT(*) as cnt FROM lots  WHERE winner_id IS NULL AND date_end > NOW() 
            ORDER BY date_add DESC' );
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
        $pagination = include_template( 'pagination.php', [
            'categories' => $categories,
            'pages' => range( 1, $pages_count ),
            'pages_count' => $pages_count,
            'cur_page' => $cur_page,
            'page_name' => 'index.php',
            'cur_filter' => $cur_filter
        ] );
        $main_content = include_template( 'index.php', [
            'ads' => $ads,
            'categories' => $categories,
            'pagination' => $pagination
        ]);


    } else {
        show_error( $content, $dbHelper->getLastError() );
    }
}


    $navigation = include_template( 'navigation.php', ['categories' => $categories] );

    $layout_content = include_template( 'layout.php', [
        'page_title' => 'Yeticave - Главная страница',
        'content' => $main_content ?? '',
        'categories' => $categories
        ] );

    print($layout_content);