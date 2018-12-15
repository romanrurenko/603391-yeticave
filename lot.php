<?php

require_once('data.php');
require_once('functions.php');
require_once('config.php');


session_start();
$bid_errors = [];

if (!$link) {
    $error = mysqli_connect_error();
    show_error( $content, $error );

} else {
    $sql = 'SELECT `id`, `name`, `style_name` FROM categories ORDER BY `id`';
    $result = mysqli_query( $link, $sql );

    if ($result) {
        $categories = mysqli_fetch_all( $result, MYSQLI_ASSOC );
    } else {
        $error = mysqli_error( $link );
        show_error( $content, $error );
    }
}

// определяем текущий id лота
if (isset( $_GET['id'] ) && ($_GET['id'] > 0)) {
    $id = $_GET['id'];
} else {
    if (isset( $_POST['lot_id'] ) && ($_POST['lot_id'] > 0)) {
        $id = $_POST['lot_id'];
    } else {
        $id = 0;
    }
}


if ($id > 0) {

    // получаем данные лота
    $sql = 'SELECT l.id,l.bid_step,l.title,l.start_price,l.image_url,l.description,date_end,c.name AS category FROM lots l ' .
        'JOIN categories c ON c.id = l.category_id ' .
        'WHERE l.id=' . $id;
    $result = mysqli_query( $link, $sql );
    if ($result) {
        $lot = mysqli_fetch_array( $result, MYSQLI_ASSOC );
    } else {
        $error = mysqli_error( $link );
        show_error( $content, $error );
    }


    // получаем текущую ставоку
    $sql = 'SELECT max(amount) as max FROM bids WHERE lot_id = ' . $id;
    $result = mysqli_query( $link, $sql );
    if ($result) {
        $bid = mysqli_fetch_array( $result, MYSQLI_ASSOC );
    } else {
        $error = mysqli_error( $link );
        show_error( $content, $error );
    }

    // вычисляем минимальную и текущую ставки
    $min_bid = ($bid['max'] > $lot['start_price']) ? ($bid['max'] + $lot['bid_step']) : $lot['start_price'];
    $current_bid = $bid['max'];


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
            $sql = 'INSERT INTO `bids` (`start_date`,`user_id`, `lot_id`, `amount`) VALUES (NOW(), ?, ?, ?);';
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
    $result = mysqli_query( $link, $sql );
    if ($result) {
        $bids = mysqli_fetch_all( $result, MYSQLI_ASSOC );
    } else {
        $error = mysqli_error( $link );
        show_error( $content, $error );
    }


} else {
    http_response_code( 404 );
    $content = include_template( '404.php', ['categories' => $categories] );
}


$content = include_template( 'lot.php', [
    'lot' => $lot,
    'categories' => $categories,
    'bids' => $bids,
    'min_bid' => $min_bid,
    'current_bid' => $current_bid,
    'cost' => $cost,
    'bid_errors' => $bid_errors
] );


$layout_content = include_template( 'layout.php', [
    'page_title' => $page_title,
    'content' => $content,
    'categories' => $categories,
] );

print($layout_content);
