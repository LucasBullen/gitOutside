<html>
	<head>
		<link rel="stylesheet" href="gitoutsideStylesheet.css">
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script type="text/javascript" src="https://www.l2.io/ip.js?var=myip"></script>
	</head>

	<body>
		<script>
			var parkName = "<?php echo $_GET['parkName']; ?>";
			var parkID = "<?php echo $_GET['parkID']; ?>";
			var userID = myip.replace(/\D/g,'');;
			$(document).ready(function() {
				$('input[type=datetime-local]').val(new Date().toJSON().slice(0,19));
			});
			//sport start end parkID userID parkName city
			function createTheEvent(){
				var sport = document.getElementById('sport_i').value;
				var start = document.getElementById('start_i').value;
				var end = document.getElementById('end_i').value;
				$.ajax({                                      
				    url: 'api.php',       
				    type: "GET",
				    data: {'function':'createEvent','sport':sport,"start":start,"end":end,"parkID":parkID,"userID":userID,"parkName":parkName,"city":"Waterloo"} 
				}).done(function( data ) {
					console.log(data);
					document.location.href ='map.php';
				});
			}
		</script>
		<div id="createEventForm">
			<b><?php echo $_GET['parkName']; ?></b></br>
	    	Sport:<input id="sport_i" type="text" name="sport"></br>
	    	Start Time:<input id="start_i" type="datetime-local" name="start_time"></br>
	    	End Time:<input id="end_i" type="datetime-local" name="end_time"></br>
	    	<input type="submit" value="Submit" onclick="createTheEvent()">
	    </div>
	</body>
</html>