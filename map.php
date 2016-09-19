<html>
	<head>
		<link rel="stylesheet" href="./mapStylesheet.css">
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.0-rc.3/dist/leaflet.css" />
		<script src="https://unpkg.com/leaflet@1.0.0-rc.3/dist/leaflet.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	</head>

	<body>
		<div id="info">
			Name:<p id="name"></p></br>
			Sport:<p id="sport"></p></br>
			Time:<p id="time"></p></br>
			People:<p id="guest"></p></br>
			<button onclick="eventInfoClick()" id="eventInfoButton" hidden>View Event Info</button>
			<button onclick="newEventClick()" id="newEventButton">Create New Event</button>
		</div>
		<div id="mapid"></div>
			<script src="main.js"></script>
			<!--<script>
				var mymap = L.map('mapid').locate({setView: true, maxZoom: 13}).setView([43.47198, -80.53854], 13);
				L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/outdoors-v9/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1Ijoibmljb2xlZWxsaXMiLCJhIjoiY2l0NnVqZzNrMDN6cjJ0bXczanVlaWJ1biJ9.qsDOsxqHmBcDe4IHZQqxzg', {
		    		attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
		    		maxZoom: 40,
		    		id: 'your.mapbox.project.id',
		    		accessToken: 'your.mapbox.public.access.token'
				}).addTo(mymap)
			</script>-->
	</body>
</html>