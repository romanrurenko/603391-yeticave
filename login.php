<?php
require_once 'functions.php';
require_once 'config/config.php';
require_once 'Database.php';

$back = $_GET['back']??0;

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

    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['login'] )) {
        $form = $_POST['login'];
        $required = ['email', 'password'];
        $errors = test_fields( $required, $form );

        if (!filter_var( $form['email'], FILTER_VALIDATE_EMAIL )) {
            $errors['email'] = 'Введите корректный email';
        }

        $email = mysqli_real_escape_string( $link, $form['email'] );
        $user = null;
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $dbHelper->executeQuery( $sql );
        if (!$dbHelper->getLastError()) {
            $array = $dbHelper->getResultAsArray();
            $user =  $array[0] ?? null;
        } else {
            show_error( $content, $dbHelper->getLastError() );
        }

        $password_is_correct = password_verify( $form['password'], $user['password']);

        if ($user !== null && $password_is_correct) {
            $_SESSION['user'] = $user;
        } else {
            $errors['wrong_password'] = 'Вы ввели неверный email/пароль';
        }

        if (!count( $errors )) {
            $locate = ($back > 0) ? ('/my-bids.php?filter=' . $back) : '/index.php';
            header( 'Location: ' . $locate );
            exit();
        }

        $page_content = include_template( 'login.php', ['form' => $form, 'errors' => $errors] );

    } else {
        $page_content = isset( $_SESSION['user'] ) ?
            include_template( 'index.php', ['username' => $_SESSION['user']['name']] ) :
            include_template( 'login.php', [] );
    }
}

$navigation = include_template( 'navigation.php', ['categories' => $categories] );

if (!isset( $main_content )) {
    $main_content = include_template( 'login.php', [
        'errors' => $errors ?? '',
        'dict' => $dict ?? '',
        'navigation' => $navigation,
        'categories' => $categories,
        'back' => $back
    ] );
}

$layout_content = include_template( 'layout.php', [
    'page_title' => 'Yeticave - Станица входа',
    'content' => $main_content,
    'navigation' => $navigation,
    'categories' => $categories
] );

print($layout_content);
