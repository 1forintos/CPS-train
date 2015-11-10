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
  '127.0.0.1', 	//host - CloudAMQP_URL 
  5672,         //port - port number of the service, 5672 is the default
  'guest', 		//user - username to connect to server
  'guest',		//password - password to connecto to the server
  'cps' 		//vhost
);

$exchange = 'amq.direct';
$queue = 'TEST_QUEUE';

$ch = $conn->channel();
$ch->queue_bind($queue, $exchange);

// $imgdata = base64_encode(file_get_contents('res/Color-blue.jpg'));
// $msg = new AMQPMessage($imgdata, array('content_type' => 'text/plain', 'delivery_mode' => 2));
// $ch->basic_publish($msg, $exchange);

$retrived_msg = $ch->basic_get($queue);
$outputdata = base64_decode($retrived_msg->body);

// $ch->basic_ack($retrived_msg->delivery_info['delivery_tag']);

file_put_contents('output/image.jpg', $outputdata); 


$sizeOfImage = getimagesize("output/image.jpg");

$width = $sizeOfImage[0];
$height = $sizeOfImage[1];

$image = imagecreatefromjpeg("output/image.jpg");
$centerColorRGB = imagecolorat($image, floor($width / 2), floor($height / 2));

$r = ($centerColorRGB >> 16) & 0xFF;
$g = ($centerColorRGB >> 8) & 0xFF;
$b = $centerColorRGB & 0xFF;

$ch->close();
$conn->close();
ob_end_clean();

echo $r . " " . $g . " " . $b;

?>