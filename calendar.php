<html>
	<head>
		<link rel='stylesheet' href='./fullcalendar.css'>
		<link rel="stylesheet" href="./jquery-ui.1.12.1.custom/jquery-ui.theme.css">
		<script src='./jquery.min.js'></script>
		<script src='./moment.min.js'></script>
		<script src='./fullcalendar.js'></script>
		<script>
			$(document).ready(function() {
			    $('#calendar').fullCalendar({
			        // put your options and callbacks here
			        eventClick: function(event) {
			        	document.location.href = "http://ec2-54-165-162-209.compute-1.amazonaws.com/eventInfo.php?&park_name="+event.park+"&sport="+event.title+"&start_time="+event.start_time+"&end_time="+event.end_time+"&eventID="+event.eventID+"&address=Waterloo";
				    }/*,
				    dayClick: function(date, jsEvent, view) {
				        alert(prompt("Event Name:"));
				        alert(prompt("Park Name:"));
				        alert(prompt("Person's Name:"));
				        alert(prompt("Sport:"));
				        alert(prompt("Time:"));
				    }*/
			    })
				$.ajax({                                      
				    url: 'api.php',       
				    type: "GET",
				    data: {'function':'getEvents','city':'Waterloo'} 
				}).done(function( data ) {
					var data = JSON.parse(data);
					var events = data.events;
					console.log(events);
					for (var i = 0; i < events.length; i++) {
						//console.log(events[i].sport);
						var myevent = {title: events[i].sport, start: events[i].start_time, end: events[i].end_time, park: events[i].parkName, start_time: events[i].start_time, end_time: events[i].end_time, eventID: events[i].id};
						console.log(myevent)
						$('#calendar').fullCalendar( 'renderEvent', myevent, true);
					}
				});

			    // page is now ready, initialize the calendar..

			});

		</script>
	</head>

	<body>
		
		<div id="calendar"></div>


	</body>
</html>