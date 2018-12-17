<?php

/**
 * @param $name
 * @param $data
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

/**
 * @param $value
 * @return string
 */
function format_price($value)
{
    $value = ceil( $value );
    $value = number_format( $value, 0, '', ' ' );
    return $value . ' <b class="rub">р</b>';
}

/**
 * @param $str
 * @return string
 */
function esc($str)
{
    return htmlspecialchars( $str );
}

/**
 * @param $date_start
 * @param $date_end
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


/**
 * @param $content
 * @param $error
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

/**
 * @param $timestamp
 * @return bool
 */
function is_valid_time_stump($timestamp)
{
    return ( (int) $timestamp === $timestamp)
        && ($timestamp <= PHP_INT_MAX)
        && ($timestamp >= ~PHP_INT_MAX);
}
