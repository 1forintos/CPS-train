<!DOCTYPE html>
<html>
<head>
	<title>CPS</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="style.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script>
		function startConsumingSpeed() {
	    	$("#startConsumingSpeed").load("cps-web.php");
		};
		function startConsumingImages() {
	    	$("#startConsumingImages").load("cps-web.php");
		};
	</script>
</head>
<body>
	<table>
		<tr>
			<td style='width: 30%;'><img class = 'newappIcon' src='images/newapp-icon.png'>
			</td>
			<td>
				<h1 id = "message"><?php echo "CPS-web service"; ?>
				</h1>	
				<button onclick="startConsumingSpeed"></button>
			</td>
		</tr>
	</table>
	<div id="startConsumingSpeed"></div>
	<div id="startConsumingImages"></div>
</body>
</html>
