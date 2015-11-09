<?php
require('vendor/autoload.php');
define('AMQP_DEBUG', true);
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

ob_start();

/**
* Create a connection to RabbitMQ
*/

$url = parse_url(getenv('localhost'));
$conn = new AMQPConnection(
          '127.0.0.1', //host - CloudAMQP_URL 
          5672,         //port - port number of the service, 5672 is the default
          'guest', //user - username to connect to server
          'guest', //password - password to connecto to the server
          'cps' //vhost
);
$ch = $conn->channel();

$exchange = 'amq.direct';
$queue = 'TEST_QUEUE';
$ch->queue_bind($queue, $exchange);

$msg_body = 'some message';
$msg = new AMQPMessage($msg_body, array('content_type' => 'text/plain', 'delivery_mode' => 2));
$ch->basic_publish($msg, $exchange);

$ch->close();
$conn->close();
ob_end_clean();
?>