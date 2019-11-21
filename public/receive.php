<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/13/19
 * Time: 11:42 AM
 */

require_once __DIR__ . '/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
$connection = new AMQPStreamConnection('127.0.0.1', 5672, 'admin', 'admin');
$channel = $connection->channel();

$channel->queue_declare('hello', false, false, false, false);
echo " [*] Waiting for messages. To exit press CTRL+C\n";
//dump($channel->queue_declare('hello', false, false, false, false));exit;

$callback = function($msg) {
    echo " [x] Received ", $msg->body, "\n";
};

$channel->basic_consume('hello', '', false, true, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}



$channel->close();
$connection->close();



?>