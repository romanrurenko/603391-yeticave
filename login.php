<?php
require_once 'functions.php';
require_once 'config/config.php';
require_once 'Database.php';


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
        $errors = [];
        $required = ['email', 'password'];
        foreach ($required as $key) {

        if (isset( $form[$key] )) {
            if (empty( $form[$key] )) {
                $errors[$key] = 'Это поле надо заполнить';
            }
        } else {
            $errors[$key] = 'Это поле отсутствует';
        }
    }

        $email = mysqli_real_escape_string( $link, $form['email'] );
        $user = null;
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $dbHelper->executeQuery( $sql );
        if (!$dbHelper->getLastError()) {
            $array = $dbHelper->getResultAsArray();
            $user =  $array[0];
        } else {
            show_error( $content, $dbHelper->getLastError() );
        }

        if ((!count( $errors )) && $user) {
            if (password_verify( $form['password'], $user['password'] )) {
                $_SESSION['user'] = $user;
            } else {
                $errors['password'] = 'Вы ввели неверный пароль';
            }
        } else {
            $errors['email'] = 'Такой пользователь не найден';
        }

        if (count( $errors )) {
            $page_content = include_template( 'login.php', ['form' => $form, 'errors' => $errors] );
        } else {
            header( 'Location: /index.php' );
            exit();
        }
    } else {
        $page_content = isset( $_SESSION['user'] ) ? include_template( 'index.php',
            ['username' => $_SESSION['user']['name']] ) : include_template( 'login.php', [] );
    }

}


if (!isset( $main_content )) {
    $main_content = include_template( 'login.php', [
        'form' => $form ?? '',
        'errors' => $errors ?? '',
        'dict' => $dict ?? '',
        'categories' => $categories
    ] );
}

$layout_content = include_template( 'layout.php', [
    'page_title' => 'Yeticave - Станица входа',
    'content' => $main_content,
    'categories' => $categories,
] );

print($layout_content);
