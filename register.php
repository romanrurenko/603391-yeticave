<?php
require_once 'functions.php';
require_once 'config/config.php';
require_once 'Database.php';


$dbHelper = new Database( ...$db_cfg );

if ($dbHelper->getLastError()) {
    show_error( $content, $dbHelper->getLastError() );
    $layout_content = include_template( 'layout.php', [
        'page_title' => 'Yeticave - Регистрация пользователя',
        'content' => $main_content,] );
    print($layout_content);
    exit();
}


$dbHelper->executeQuery( 'SELECT id, name, style_name FROM categories ORDER BY id' );
if (!$dbHelper->getLastError()) {
    $categories = $dbHelper->getResultAsArray();
} else {
    show_error( $content, $dbHelper->getLastError() );
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['signup'] )) {
    $form = $_POST['signup'];
    $errors = [];
    $required = ['email', 'password', 'name', 'contacts'];
    $dict = ['email' => 'E-Mail', 'password' => 'Пароль', 'path' => 'Аватар',
        'name' => 'Имя', 'contacts' => 'Контакты'];


    $errors = test_fields( $required, $form );


    if (!filter_var( $form['email'], FILTER_VALIDATE_EMAIL )) {
        $errors['email'] = 'Введите корректный email';
    }

    $is_file_load = (isset( $_FILES['file']['name'] ) && ($_FILES['file']['tmp_name'] !== ''));

    // проверяем изображение
    if ($is_file_load) {
        $tmp_name = $_FILES['file']['tmp_name'];
        $file_name = uniqid( '', false ) . '.jpg';
        $file_info = finfo_open( FILEINFO_MIME_TYPE );
        $file_type = finfo_file( $file_info, $tmp_name );
        $is_image = ($file_type === 'image/jpeg' || $file_type === 'image/png');
        $full_path = $avatar_path . $file_name;
        if (!$is_image) {
            $errors['path'] = 'Изображение дожно быть в формате JPG или PNG';
        }

        if (!isset( $errors['path'] ) && move_uploaded_file( $tmp_name, $full_path )) {
            $form['path'] = $full_path;
        } else {
            $errors['path'] = 'Ошибка загрузки файла';
        }

    }

    if (isset( $errors ) && !count( $errors )) {

        $email = mysqli_real_escape_string( $link, $form['email'] );
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = mysqli_query( $link, $sql );
        $count = mysqli_num_rows( $res );

        if ($res && $count > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }

        if ($res && ($count === 0)) {
            $password = password_hash( $form['password'], PASSWORD_DEFAULT );
            $sql = 'INSERT INTO users (date_add, email, name, password, contacts, avatar_url)
                    VALUES (NOW(),?, ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt( $link, $sql, [$form['email'], $form['name'],
                $password, $form['contacts'], $form['path'] ?? $default_avatar] );
            $res = mysqli_stmt_execute( $stmt );
        }

        if ($res && empty( $errors )) {
            header( 'Location: /login.php' );
            exit();
        }

        $page_content = include_template( 'error.php', ['error' => mysqli_error( $link )] );
    } else {
        $page_content = include_template( 'error.php', ['error' => mysqli_error( $link )] );
    }
}

    $navigation = include_template( 'navigation.php', [
            'categories' => $categories]
    );

    if (!isset( $main_content )) {
        $main_content = include_template( 'register.php', [
            'form' => $form ?? null,
            'errors' => $errors ?? null,
            'dict' => $dict ?? null,
            'categories' => $categories,
            'navigation' => $navigation ?? ''
        ] );
    }


    $layout_content = include_template( 'layout.php', [
        'page_title' => 'Yeticave - Регистрация пользователя',
        'content' => $main_content,
        'categories' => $categories,
        'navigation' => $navigation ?? ''
    ] );

    print($layout_content);