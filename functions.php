<?php

/** Помещает шаблон из файла в переменную
 * @param $name имя файла
 * @param $data массив данных
 * @return false|string
 */
function include_template($name, $data)
{
    $name = 'templates/' . $name;
    $result = '';

    if (!file_exists( $name )) {
        return $result;
    }

    ob_start();
    extract( $data );
    require $name;

    $result = ob_get_clean();

    return $result;
}


/** Возвращает массив с ошибками
 * @param $required массив с требуемыми полями
 * @param $array проверяемый массив
 * @return array массив с ошибками
 */
function test_fields($required, $array)
{
    $errors = [];
    foreach ($required as $key) {

        if (isset( $array[$key] )) {
            $errors[$key] = empty( $array[$key] )? 'Это поле надо заполнить':'';
        } else {
            $errors[$key] = 'Это поле отсутствует';
        }
    }
    $errors = array_filter($errors, function($item){
        return $item;
    });

 return $errors;
}

/** Оформление суммы в виде денег со знаком рубля
 * @param $value
 * @return string
 */
function format_price($value)
{
    $value = ceil( $value );
    $value = number_format( $value, 0, '', ' ' );
    return $value . ' <b class="rub">р</b>';
}

/** Защита вывод от инъекций
 * @param $str
 * @return string
 */
function esc($str)
{
    return htmlspecialchars( $str );
}

/** Вывод оставшегося времени между датами в специальном формате
 * @param $date_start начальная дата
 * @param $date_end конечная дата
 * @return string
 */
function get_time_until_date_end($date_start, $date_end)
{
    $next_day = strtotime( $date_end );
    $diff = $next_day - $date_start;
    if ($diff < 0) {
        $result = 'закрыт';
    } else {
        $days = floor( ($diff / 3600) / 24 );
        $hours = floor( ($diff % 3600) / 60 );
        $minutes = floor( $diff % 60 );
        $result = ($days >= 1) ? sprintf( '%02d', $days ) . ' дн.' : sprintf( '%02d:%02d', $hours, $minutes );
    }
    return $result;
}

/**
 * @param $date_start
 * @param $date_end
 * @return string
 */
function time_is_finish($date_start, $date_end)
{
    $next_day = strtotime( $date_end );
    $diff = $next_day - $date_start;
    if ($diff < 3600 && $diff > 0) {
        $result = 'timer--finishing';
    }
    return $result ?? '';
}


/**  Вывод пройденого времени между датами в специальном формате
 * @param $date_start начальная дата
 * @param $date_end конечная дата
 * @return false|string
 */
function get_time_after_date($date_start, $date_end)
{
    $diff = $date_end - strtotime( $date_start );
    if ($diff < (3600 * 24)) {
        $hours = floor( $diff / 3600 );
        $minutes = floor( ($diff / 60) % 3600 );
        $result = ($diff > 3600) ? sprintf( '%2d', $hours ) . ' час. назад' : sprintf( '%2d', $minutes ) . ' мин. назад';
    } else {
        $result = date( 'd-m-Y в H:i:s', strtotime( $date_start ) );
    }
    return $result;
}


/** Внесение ошибки в переменную в параметре1
 * @param $content переменная с шаблоном
 * @param $error текст ошибки
 */
function show_error(&$content, $error)
{
    $content = include_template( 'error.php', ['error' => $error] );
}


/**
 * @param $link
 * @param $sql
 * @param array $data
 * @return bool|mysqli_stmt
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare( $link, $sql );
    if ($data) {
        $types = '';
        $stmt_data = [];
        foreach ($data as $value) {
            $type = null;

            if (is_int( $value )) {
                $type = 'i';
            } else if (is_string( $value )) {
                $type = 's';
            } else if (is_float( $value )) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }
        $values = array_merge( [$stmt, $types], $stmt_data );
        $func = 'mysqli_stmt_bind_param';
        $func( ...$values );
    }

    return $stmt;
}

/** Проверка даты в формате таймштамп
 * дата должна быть не менее на сутки вперед и
 * не более возможной 2038 г. по максимальному значению PHP INT
 * @param $timestamp
 * @return bool
 */
function is_valid_time_stump($timestamp)
{
    return ($timestamp <= PHP_INT_MAX) && ($timestamp >= time() + (3600 * 24));
}
