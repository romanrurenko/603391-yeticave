<?php
require_once('config/config.php');
require_once('Database.php');

$dbHelper = new Database( ...$db_cfg );

if ($dbHelper->getLastError()) {
    show_error( $content, $dbHelper->getLastError() );
} else {
    $dbHelper->executeQuery( 'SELECT id FROM lots WHERE winner_id IS NULL and date_end <= NOW()' );
    if (!$dbHelper->getLastError()) {
        $bids_ids = $dbHelper->getResultAsArray();
    } else {
        show_error( $content, $dbHelper->getLastError() );
    }
    if (count( $bids_ids )) {
        foreach ($bids_ids as $value) {
            // получаем данные ставок
            $dbHelper->executeQuery( 'SELECT amount, lot_id, user_id FROM bids WHERE lot_id='
                . $value['id'] . ' ORDER BY amount DESC limit 1' );

            if (!$dbHelper->getLastError()) {
                $bid = $dbHelper->getResultAsArray();
            } else {
                show_error( $content, $dbHelper->getLastError() );
            }
            if (count( $bid )) {
                // вносим значения победителей
                $dbHelper->executeQuery( 'UPDATE `lots` SET winner_id = ' . $bid[0]['user_id'] .
                    ' WHERE id = ' . $bid[0]['lot_id'] . ';' );

                if ($dbHelper->getLastError()) {
                    show_error( $content, $dbHelper->getLastError() );
                }
            }

        }
    }
}
