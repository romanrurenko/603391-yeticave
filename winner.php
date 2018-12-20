<?php
require_once 'config/config.php';
require_once 'Database.php';

$dbHelper = new Database( ...$db_cfg );
$bids_ids = [];

if (!$dbHelper->getLastError()) {

    $sql = 'SELECT id FROM lots WHERE winner_id IS NULL and date_end <= NOW()';
    $dbHelper->executeQuery( $sql );
    if (!$dbHelper->getLastError()) {
        $bids_ids = $dbHelper->getResultAsArray();
    }
}

if (count( $bids_ids ) > 0) {


    foreach ($bids_ids as $value) {

        // получаем данные ставок
        $sql = 'SELECT id, amount, lot_id, user_id FROM bids WHERE lot_id='
            . $value['id'] . ' ORDER BY amount DESC limit 1';
        $dbHelper->executeQuery($sql);


        if (!$dbHelper->getLastError()) {
            $bid = $dbHelper->getResultAsArray();
        }


        $count = count( $bid );

        if ($count === 0) {
            $sql = 'UPDATE lots SET winner_id = ' . 0 .
                ', win_bid_id = ' . 0 . ' WHERE id = ' . $value['id'];
            $dbHelper->executeQuery( $sql );
        }

        // вносим значения победителей
        if ($count > 0) {
            $sql = 'UPDATE lots SET winner_id = ' . $bid[0]['user_id'] .
                ', win_bid_id = ' . $bid[0]['id'] . ' WHERE id = ' . $value['id'];
            $dbHelper->executeQuery( $sql );
        }
    }


    $ids = [];
    foreach ($bids_ids as $value) {
        array_push( $ids, $value['id'] );
    }
    $ids_implode = implode( ',', $ids );


    $sql = 'select b.id as bid_id, b.user_id, b.lot_id, u.name, l.title, u.email from bids b
 JOIN users u ON u.id = b.user_id
  JOIN lots l ON l.id = b.lot_id
   WHERE b.id IN (' . $ids_implode . ')';
    $dbHelper->executeQuery( $sql );

    if (!$dbHelper->getLastError()) {
        $users = $dbHelper->getResultAsArray();
    } else {
        show_error( $content, $dbHelper->getLastError() );
    }


    $transport = new Swift_SmtpTransport( 'phpdemo.ru', 25 );
    $transport->setUsername( 'keks@phpdemo.ru' );
    $transport->setPassword( 'htmlacademy' );

    $mailer = new Swift_Mailer( $transport );

    $logger = new Swift_Plugins_Loggers_ArrayLogger();
    $mailer->registerPlugin( new Swift_Plugins_LoggerPlugin( $logger ) );

    $recipients = [];

    foreach ($users as $user) {
        $recipients[$user['email']] = $user['name'];
    }

    $message = new Swift_Message();
    $message->setSubject( 'Ваша ставка победила' );
    $message->setFrom( ['keks@phpdemo.ru' => 'GifTube'] );
    $message->setBcc( $recipients );

    $msg_content = include_template( 'email.php', ['user' => $user] );

    $message->setBody( $msg_content, 'text/html' );
    $result = $mailer->send( $message );

}

