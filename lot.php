<?php

require_once 'functions.php';
require_once 'config/config.php';
require_once 'Database.php';

session_start();
$bid_errors = [];
$lot = [];

$dbHelper = new Database( ...$db_cfg );

if ($dbHelper->getLastError()) {
    show_error( $content, $dbHelper->getLastError() );
} else {

    $dbHelper->executeQuery( 'SELECT id, name, style_name FROM categories ORDER BY id' );

    if (!$dbHelper->getLastError()) {
        $categories = $dbHelper->getResultAsArray();
    } else {
        show_error( $content, $dbHelper->getLastError() );
    }
}

// определяем текущий id лота
if (isset( $_GET['id'] ) && ($_GET['id'] > 0)) {
    $id = (int)$_GET['id'];
} else {
    if (isset( $_POST['lot_id'] ) && ($_POST['lot_id'] > 0)) {
        $id = (int)$_POST['lot_id'];
    } else {
        $id = 0;
    }
}


if ($id === 0) {
    http_response_code( 404 );
    header( 'Location: 404.php' );
    exit();
}

// получаем данные лота
$sql = 'SELECT l.id,l.bid_step,l.title,l.start_price,l.image_url,l.description,date_end,l.winner_id, l.owner_id,
            c.name AS category FROM lots l JOIN categories c ON c.id = l.category_id WHERE l.id=' . $id;
$dbHelper->executeQuery( $sql );
if (!$dbHelper->getLastError()) {
    $lot = $dbHelper->getResultAsArray();
    $lot = $lot [0] ?? null;
} else {
    show_error( $content, $dbHelper->getLastError() );
}

if (count( $lot ) === 0) {
    http_response_code( 404 );
    header( 'Location: 404.php' );
    exit();

}


// получаем текущую ставку
$sql = 'SELECT max(amount) as max_amount FROM bids WHERE lot_id = ' . $id;
$dbHelper->executeQuery( $sql );

if (!$dbHelper->getLastError()) {
    $bid = $dbHelper->getResultAsArray();
    $bid = $bid[0] ?? 0;
} else {
    show_error( $content, $dbHelper->getLastError() );
}

// вычисляем минимальную и текущую ставки
$min_bid = ($bid['max_amount'] > $lot['start_price']) ? ($bid['max_amount'] + $lot['bid_step']) : $lot['start_price'];
$current_bid = $bid['max_amount'];

// если есть сессия и введена сумма ставки
if (isset( $_SESSION['user'], $_POST['cost'] )) {
    $cost = $_POST['cost'];


    // проверяем значние на ошибки
    if (empty( $cost )) {
        $bid_errors['cost'] = 'Поле ставки не заполнено.';
    }
    if (is_float( $cost )) {
        $bid_errors['cost'] = 'Ставка должна быть целым числом.';
    }

    if ($min_bid > $cost || $lot['start_price'] > $cost) {
        $bid_errors['cost'] = 'Ставка не может быть меньше минимальной.';
    }

    //если нет ошибок вносим ставку в БД
    if (empty( $bid_errors )) {
        $sql = 'INSERT INTO bids (start_date,user_id, lot_id, amount) VALUES (NOW(), ?, ?, ?);';
        $stmt = db_get_prepare_stmt( $link, $sql, [$_SESSION['user']['id'], $id, $cost] );
        $res = mysqli_stmt_execute( $stmt );
        if ($res) {
            $lot_id = mysqli_insert_id( $link );

        } else {
            $page_content = include_template( 'error.php', ['error' => mysqli_error( $link )] );
        }
    }
}


// получаем данные ставок
$sql = 'SELECT u.name, amount, start_date, (now()-start_date) as time_after_bid  FROM bids b
            JOIN  users u on u.id = user_id WHERE lot_id = ' . $id . ' ORDER BY b.start_date DESC LIMIT 6;';
$dbHelper->executeQuery( $sql );

if (!$dbHelper->getLastError()) {
    $bids = $dbHelper->getResultAsArray();

} else {
    show_error( $content, $dbHelper->getLastError() );
}

if (empty( $content )) {
    $content = include_template( 'lot.php', [
        'lot' => $lot,
        'categories' => $categories,
        'bids' => $bids,
        'min_bid' => $min_bid,
        'current_bid' => $current_bid ?? '',
        'cost' => $cost ?? '',
        'bid_errors' => $bid_errors
    ] );
}


$layout_content = include_template( 'layout.php', [
    'page_title' => 'Yeticave - Лот',
    'content' => $content,
    'categories' => $categories
] );

print($layout_content);
