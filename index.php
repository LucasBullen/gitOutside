<html>
	<head>
		<link rel="stylesheet" href="gitoutsideStylesheet.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	</head>

	<body>
		<header>
			<a href="http://ec2-54-165-162-209.compute-1.amazonaws.com/"><img src="gitactive.png"></a>
			<br>
			<input type="button" value="map" onclick="setURL('map.php')">
			<input type="button" value="calendar" onclick="setURL('calendar.php')">
		</header>
		
		<script>
			function setURL(url){
			    document.getElementById('iframe').src = url;
			}
		</script>
		<iframe id="iframe" src="map.php" />
	</body>
</html>