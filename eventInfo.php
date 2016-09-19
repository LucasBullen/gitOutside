<html>
	<head>
		<link rel="stylesheet" href="gitoutsideStylesheet.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script type="text/javascript" src="https://www.l2.io/ip.js?var=myip"></script>
	</head>

	<body>
		<script>
			var park_name = "<?php echo $_GET['park_name']; ?>";
			var sport = "<?php echo $_GET['sport']; ?>";
			var start_time = "<?php echo $_GET['start_time']; ?>";
			var end_time = "<?php echo $_GET['end_time']; ?>";
			var eventID = "<?php echo $_GET['eventID']; ?>";
			var address = "<?php echo $_GET['address']; ?>";
			var userID = myip.replace(/\D/g,'');
			$(document).ready(function() {
				document.getElementById('park_name').innerHTML = park_name;
				document.getElementById('sport').innerHTML = sport;
				document.getElementById('start_time').innerHTML = start_time;
				document.getElementById('end_time').innerHTML = end_time;
				getNearReasturants(address);
			});

			function attendEvent(){
				$.ajax({                                      
				    url: 'api.php',       
				    type: "GET",
				    data: {'function':'setAttendance','userID':userID,"status":1,"eventID":eventID} 
				}).done(function( data ) {
					console.log(data);
					document.location.href ='map.php';
				});
			}

			function getNearReasturants(address){
				$.ajax({                                      
				    url: 'api.php',       
				    type: "GET",
				    data: {'function':'getRestaurants','city':address} 
				}).done(function( data ) {
					data = JSON.parse(data);
					console.log(data);
					var rest = data.restaurants;
					var markers = []
					for (var i = 0; i < rest.length; i++) {
						document.getElementById('yelp').innerHTML = document.getElementById('yelp').innerHTML + "</br> <b>" + rest[i].name + "</b><br><img class='yelpImage' src='" + rest[i].rating_img_url_small + "'><a href='" + rest[i].url + "'>Visit Site</a>";
					}
				});
			}
		</script>
		<div id="eventInfo">
			<b>At:</b><p id="park_name"></p><br>
			<b>We are playing:</b><p id="sport"></p><br>
			<b>From:</b><p id="start_time"></p><br>
			<b>To:</b><p id="end_time"></p><br>
			<input type="submit" value="JOIN!" onclick="attendEvent()"></br>
			<br>
		</div>
		<h1>Why not make the game more interesting? Losers buy lunch!
		<div id="yelp">
			
		</div>
	</body>
</html>