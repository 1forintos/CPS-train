<?php
require('vendor/autoload.php');
define('AMQP_DEBUG', false);
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

$url = parse_url('amqp://qvofpctp:-I8fYvnDLSPDp5Y4DFCpuBmsHEzVxxEY@white-swan.rmq.cloudamqp.com/qvofpctp');
* Create a connection to RabbitM
$conn = new AMQPConnection(
  $url['host'], 	//host - CloudAMQP_URL 
  5672,        		//port - port number of the service, 5672 is the default
  $url['user'], 	//user - username to connect to server
  $url['pass'],		//password - password to connecto to the server
  substr($url['path'], 1) 		//vhost	
);

while(True) {
	consumeSpeed($conn);
	sleep(0.2);
}
$conn->close();

/**
*  Close connection to rabbitMQ
*/

function log_msg($msg) {
	ob_end_clean();
	echo $msg;
	ob_start();
}

function consumeSpeed($conn) {
	$speed_limit = 2.0;

	$queueSpeed = "TRAIN_SPEED";
	$queueLed= "LED";

	$chSpeed = $conn->channel();
	$exchangeSpeed = $chSpeed->exchange_declare($queueSpeed, 'direct', false, false, false);
	//$chSpeed->queue_bind($queueSpeed, $exchangeSpeed);

	/**
	*  Consume message from QUEUE
	*/
	$retrived_msg = NULL;
	while(!is_object($retrived_msg)) {
		$retrived_msg = $chSpeed->basic_get($queueSpeed);
	}
	$chSpeed->basic_ack($retrived_msg->delivery_info['delivery_tag']);

	/**
	*  Process speed data
	*/
	$speed = $retrived_msg->body;
	$chSpeed->close();

	$led_color = NULL;
	if(floatval($speed) > $speed_limit) {
		$led_color = "RED";
	} else {
		$led_color = "GREEN";
	}
	
	echo "<script type='text/javascript'>alert('Speed: '$speed' km/h');</script>";

	$chLed = $conn->channel();
	$exchangeLed = $chLed->exchange_declare($queueLed, 'direct', false, false, false);
	$chLed->queue_bind($queueLed, $queueLed);
	$msg = new AMQPMessage($led_color, array('content_type' => 'text/plain', 'delivery_mode' => 2));
	$chLed->basic_publish($msg, $queueLed);
	$chLed->close();
}

function consumeImages($conn, $exchange) {
	$queueImages = "TRAIN_IMAGES";
	$queueLed = "LED";

	$chImages = $conn->channel();
	
	$exchangeImages = $chImages->exchange_declare($queueImages, 'direct', false, false, false);
	// $chImages->queue_bind($queueImages, $exchange);
	$chLed->queue_bind($queueLed, $exchange);

	/**
	*  Consume message from QUEUE
	*/
	$retrived_msg = $chImages->basic_get($queueImages);

	/**
	*  Decode base64 content to get the image and close channel
	*/
	$outputdata = base64_decode($retrived_msg->body);
	$chImages->basic_ack($retrived_msg->delivery_info['delivery_tag']);
	$chImages->close();

	/**
	*  Save image
	*/
	file_put_contents('image.jpg', $outputdata);

	/**
	*  Perform high-end algorythm
	*/
	$sizeOfImage = getimagesize("image.jpg");

	$width = $sizeOfImage[0];
	$height = $sizeOfImage[1];

	$image = imagecreatefromjpeg("image.jpg");
	$centerColorRGB = imagecolorat($image, floor($width / 2), floor($height / 2));

	$r = ($centerColorRGB >> 16) & 0xFF;
	$g = ($centerColorRGB >> 8) & 0xFF;
	$b = $centerColorRGB & 0xFF;

	$led_color = NULL;
	if($r > 200) {
		$led_color = "RED";
	} else if($b > 200){
		$led_color = "GREEN";
	}

	$chLed = $conn->channel();
	$exchangeLed = $chLed->exchange_declare($queueLed, 'direct', false, false, false);
	$chLed->queue_bind($queueLed, $queueLed);
	$msg = new AMQPMessage($led_color, array('content_type' => 'text/plain', 'delivery_mode' => 2));
	$chLed->basic_publish($msg, $queueLed);
	$chLed->close();
}

?>