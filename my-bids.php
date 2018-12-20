<?php
require_once 'functions.php';
require_once 'config/config.php';
require_once 'Database.php';

session_start();
if (!isset($_SESSION['user'])) {
    $check_user = $_GET['filter'] ?? 0;
    header( 'Location: login.php?back=' . $check_user);
    exit();
}


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

    $cur_page = (int)($_GET['page'] ?? 1);
    $cur_user_id = (int)($_SESSION['user']['id'] ?? 0);
    $cur_filter = $cur_user_id;
    $page_items_count = 9;

    $dbHelper->executeQuery( 'SELECT COUNT(*) as cnt FROM bids  WHERE user_id = ?
            ORDER BY start_date DESC', [$cur_user_id] );
    $total_items_count = $dbHelper->getResultAsArray()[0]['cnt'];
    $pages_count = ceil( $total_items_count / $page_items_count );
    $offset = ($cur_page - 1) * $page_items_count;

    $sql = 'SELECT b.id, b.start_date, b.amount, b.user_id, l.image_url, l.winner_id, l.title as lot_title,
            l.id as lot_id, l.win_bid_id, l.owner_id, u.contacts            
            FROM bids b
            JOIN lots l ON l.id = b.lot_id
            JOIN users u ON u.id = l.owner_id
            WHERE b.user_id = ?
            ORDER BY b.start_date DESC  LIMIT ? OFFSET ?';

    $dbHelper->executeQuery( $sql, [$cur_user_id, $page_items_count, $offset] );

    if (!$dbHelper->getLastError()) {
        $bids = $dbHelper->getResultAsArray();
        $pagination = include_template( 'pagination.php', [
            'categories' => $categories,
            'pages' => range( 1, $pages_count ),
            'pages_count' => $pages_count,
            'cur_page' => $cur_page,
            'page_name' => 'my-bids.php',
            'cur_filter' => $cur_filter
        ] );

        $main_content = include_template( 'my-bids.php', [
            'bids' => $bids,
            'categories' => $categories,
            'pagination' => $pagination
        ]);

    } else {
        show_error( $content, $dbHelper->getLastError() );
    }
}
$navigation = include_template( 'navigation.php', ['categories' => $categories] );

$layout_content = include_template( 'layout.php', [
    'page_title' => 'Yeticave - Мои ставки',
    'content' => $main_content ?? '',
    'navigation' => $navigation,
    'categories' => $categories,

] );

print($layout_content);