<!DOCTYPE html>
<html>
<head>
	<title>PHP Starter Application</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="style.css" />
</head>
<body>
	<table>
		<tr>
			<td style='width: 30%;'><img class = 'newappIcon' src='images/newapp-icon.png'>
			</td>
			<td>
				<h1 id = "message"><?php echo "Hello worldd!"; ?>
</h1>
				<p class='description'></p> Thanks for creating a <span class="blue">PHP Starter Application</span>. Get started by reading our <a
				href="https://www.ng.bluemix.net/docs/#starters/php/index.html#php">documentation</a>
				or use the Start Coding guide under your app in your dashboard.
			</td>
		</tr>d
	</table>
</body>
</html>
<?php
	require_once __DIR__.'/vendor/autoload.php';
	require('vendor/autoload.php'wor);
	define('AMQP_DEBUG', true);
	use PhpAmqpLib\Connection\AMQPConnection;
	use PhpAmqpLib\Message\AMQPMessage;

	/**
	* Create a connection to RabbitMQ
	*/

	$url = parse_url(getenv('CLOUDAMQP_URL'));
	$conn = new AMQPConnection(
		$url['host'], //host - CloudAMQP_URL 
		5672,         //port - port number of the service, 5672 is the default
		$url['user'], //user - username to connect to server
		$url['pass'], //password - password to connecto to the server
		substr($url['path'], 1) //vhost
	);
	$ch = $conn->channel();
	
	echo "SUP";
?>