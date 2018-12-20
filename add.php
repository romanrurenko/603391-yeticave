<?php
require_once 'functions.php';
require_once 'config/config.php';
require_once 'Database.php';


// загружаем категории
$dbHelper = new Database( ...$db_cfg );
if ($dbHelper->getLastError()) {
    show_error( $content, $dbHelper->getLastError() );
} else {
    $sql = 'SELECT id, name, style_name FROM categories ORDER BY id';
    $dbHelper->executeQuery( $sql );
    if (!$dbHelper->getLastError()) {
        $categories = $dbHelper->getResultAsArray();
    } else {
        show_error( $content, $dbHelper->getLastError() );
    }
}

session_start();

if (!isset( $_SESSION['user'] )) {
    http_response_code( 403 );
    header( 'Location: 403.php' );
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['lot'] )) {

    $lot = $_POST['lot'];
    $required = ['title', 'description', 'start_price', 'bid_step',
        'category', 'date_end'];
    $dict = ['title' => 'Название', 'description' => 'Описание', 'path' => 'Изображение',
        'start_price' => 'Начальная цена', 'bid_step' => 'Шаг ставки', 'category' => 'Категория',
        'date_end' => 'Дата окончания торгов'];

    $errors = test_fields( $required, $lot );

    if ($lot['category'] === 0) {
        $errors['category'] = 'Не выбрана категория';
    }

    $is_file_load = (isset( $_FILES['lot-image']['name'] ) && ($_FILES['lot-image']['tmp_name'] !== ''));

    if ($is_file_load) {
        $tmp_name = $_FILES['lot-image']['tmp_name'];
        $file_name = uniqid( '', false ) . '.jpg';
        $file_info = finfo_open( FILEINFO_MIME_TYPE );
        $file_type = finfo_file( $file_info, $tmp_name );
        $full_path = $lot_image_path . $file_name;
        $is_image = ($file_type === 'image/jpeg' || $file_type === 'image/png');

        if (!$is_image) {
            $errors['path'] = 'Файл должен быть JPG или PNG';
        }

    } else {
        $errors['path'] = 'Вы не загрузили файл';
    }


    if (!isset( $errors['path'] ) && move_uploaded_file( $tmp_name, $full_path )) {
        $lot['path'] = $full_path;
    } else {
        $errors['path'].=   '. Ошибка загрузки файла';
    }

    $is_valid_date = is_valid_time_stump( strtotime( $lot['date_end'] ) );
    if (!$is_valid_date) {
        $errors['date_end'] = ' Некорректная дата';
    }

    // если есть ошибки
    if (count( $errors )) {
        $page_content = include_template( 'add-lot.php', [
            'lot' => $lot,
            'errors' => $errors,
            'dict' => $dict,
            'categories' => $categories] );
    } else {

        // если нет ошибок
        // сохраняем лот в базу
        $sql = 'INSERT INTO lots (date_add,image_url, title, description, start_price, date_end,
                     bid_step, category_id, owner_id) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?);';
        $cur_user = $_SESSION['user']['id']??0;
        $stmt = db_get_prepare_stmt( $link, $sql, [$lot['path'], $lot['title'], $lot['description'],
            $lot['start_price'], $lot['date_end'], $lot['bid_step'], $lot['category'], $cur_user] );

        $res = mysqli_stmt_execute( $stmt );
        if ($res) {
            $lot_id = mysqli_insert_id( $link );
            header( 'Location: lot.php?id=' . $lot_id );
            exit();
        }
        $page_content = include_template( 'error.php', ['error' => mysqli_error( $link )] );
    }
} else {
    // если пришли не c POST запроса
    $page_content = include_template( 'add-lot.php', ['categories' => $categories] );
}

$navigation = include_template( 'navigation.php', ['categories' => $categories] );

$layout_content = include_template( 'layout.php', [
    'page_title' => 'Yeticave - Добавление лота',
    'content' => $page_content,
    'categories' => $categories,
    'navigation' => $navigation
] );

print($layout_content);
