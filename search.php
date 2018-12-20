<?php
require_once 'functions.php';
require_once 'config/config.php';
require_once 'Database.php';

$search = trim( $_GET['search'] ?? '') ;


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
        if ($search !== '') {
        // экранирование данных в запросе
        $search = mysqli_real_escape_string( $link, $search );
        $sql = 'SELECT l.id,l.title,l.start_price,l.image_url,l.description,date_end,c.name AS category FROM lots l ' .
            'JOIN categories c ON c.id = l.category_id ' .
            'WHERE MATCH (title,description) AGAINST ("' . $search . '" IN BOOLEAN MODE) ' .
            'ORDER BY l.date_add DESC';

        $dbHelper->executeQuery( $sql );
        if (!$dbHelper->getLastError()) {
            $ads = $dbHelper->getResultAsArray();
        } else {
            show_error( $content, $dbHelper->getLastError() );
        }
    }

}

$navigation = include_template( 'navigation.php', ['categories' => $categories] );

if (isset($ads) && (count($ads)>0)) {

        $content = include_template( 'search.php', [
            'ads' => $ads ?? [],
            'search' => $search ?? '',
            'navigation' => $navigation,
            'categories' => $categories,
            'block_title' => 'Результаты поиска по запросу '
        ] );

} else {

    $content = include_template( 'search.php', [
        'ads' => [],
        'search' => $search ?? '',
        'navigation' => $navigation,
        'categories' => $categories,
        'block_title' => 'По вашему запросу ничего не найдено '] );
}

$layout_content = include_template( 'layout.php', [
    'page_title' => 'Yeticave - Поиск лотов',
    'content' => $content,
    'navigation' => $navigation,
    'categories' => $categories
] );

print($layout_content);
