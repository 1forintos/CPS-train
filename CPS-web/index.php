<!DOCTYPE html>
<html>
	<head>
		<title>CPS</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" href="style.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
		<script src="cps-web.js"></script>
	</head>
	<body>
		<table>
			<tr>
				<td style='width: 30%;'><img class = 'newappIcon' src='images/newapp-icon.png'>
				</td>
				<td>					
					<?php if(!array_key_exists("mode", $_GET) || $_GET["mode"] != "speed" && $_GET["mode"] != "image"):?>						
						<h1 id = "message"><?php echo "CPS-web service"; ?></h1>
						<span>Invalid mode. Please add a valid HTTP GET argument: mode (speed|image).</span>					
					<?php elseif($_GET["mode"] == "speed"):?>
						<h1 id = "message"><?php echo "CPS-web service - speed"; ?></h1>
						<button id="btnConsumeSpeed">Insert Coin</button>
						<span id="textSpeed"></span>
					<?php elseif($_GET["mode"] == "image"):?>
						<h1 id = "message"><?php echo "CPS-web service - image"; ?></h1>
						<button id="btnConsumeImage">Insert Coin</button>
						<span id="textImage"></span>
					<?php endif;?>
					<br/><br/>
					<span id="textRemaining">0 credit(s) remaining.</span>
				</td>
			</tr>
		</table>
	</body>
</html>
