var isMobile = false; //initiate as false
// device detection
if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) isMobile = true;


var events = {};
function getWaterlooReasturants(){
	$.ajax({                                      
	    url: 'api.php',       
	    type: "GET",
	    data: {'function':'getRestaurants','city':'Waterloo'} 
	}).done(function( data ) {
		data = JSON.parse(data);
		console.log(data);
		var rest = data.restaurants;
		var markers = []

		var treeIcon = L.icon({
			iconUrl: 'tree.png',

			iconSize: [55,55], //size of icon
			iconAnchor: [35,95],
			popupAnchor:
			[-8,-86]
		})
		for (var i = 0; i < rest.length; i++) {
			var lat = rest[i].location.coordinate.latitude;
			var lng = rest[i].location.coordinate.longitude;
			var marker = L.marker([lat, lng], {icon:treeIcon}).addTo(mymap);
			marker.bindPopup("<b>" + rest[i].name + "</b><br><img src='" + rest[i].rating_img_url_small + "'><a href='" + rest[i].url + "'>Visit Site</a>");
		}
	});
}

function getWaterlooMaps(){
	$.ajax({                                      
	    url: 'api.php',       
	    type: "GET",
	    data: {'function':'getEvents','city':'Waterloo'} 
	}).done(function( data ) {
		data = JSON.parse(data);
		var theevents = data.events;
		console.log(theevents);
		for (var i = 0; i < theevents.length; i++) {
			if (theevents[i].cadian_id in events) {
				events[theevents[i].cadian_id].push(theevents[i]);
			}else{
				events[theevents[i].cadian_id] = [theevents[i]];
			}
		}
		//get map data
		$.ajax({                                      
		    url: 'https://api.namara.io/v0/data_sets/b4735bb9-d73d-4231-a362-a3760fdf3633/data/en-0',       
		    type: "GET",
		    data: {'api_key':'c14a10c2ab1b27e06487c587cc09c88a84ccada245a5113a4aa271d967a241ee'} 
		}).done(function( data ) {
			var allLayers = [];
			for (var i = 0; i < data.length; i++) {
				data[i].geometry.properties = {
					'name':data[i].park_name,
					'objectid':data[i].objectid,
					'address':data[i].address
				};
				allLayers.push(data[i].geometry);
			}
			geojson = L.geoJson(allLayers, {
				style: style,
				onEachFeature: onEachFeature
			}).addTo(mymap);
		});
	});
}
/************************************
Map stuff
*************************************/
var mymap = L.map('mapid').locate({setView: true, maxZoom: 13}).setView([43.47198, -80.53854], 13);
	L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/outdoors-v9/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1Ijoibmljb2xlZWxsaXMiLCJhIjoiY2l0NnVqZzNrMDN6cjJ0bXczanVlaWJ1biJ9.qsDOsxqHmBcDe4IHZQqxzg', {
		attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
		maxZoom: 40,
		id: 'your.mapbox.project.id',
		accessToken: 'your.mapbox.public.access.token'
	}).addTo(mymap)
var geojson;

function highlightFeature(e) {
	console.log(e);
	var layer = e.target;
	prepButtons(e);
	if(last){
		resetHighlight(last)
	}
	last = e;

    layer.setStyle({
        weight: 5,
        color: '#666',
        dashArray: '',
        fillOpacity: 0.7
    });
    document.getElementById('name').innerHTML = layer.feature.geometry.properties.name;
    if ((layer.feature.geometry.properties.objectid in events)) {
    	document.getElementById('sport').innerHTML = events[layer.feature.geometry.properties.objectid][0].sport;
    	document.getElementById('time').innerHTML = events[layer.feature.geometry.properties.objectid][0].start_time;
    	document.getElementById('guest').innerHTML = events[layer.feature.geometry.properties.objectid][0].users;
    }else{
		document.getElementById('sport').innerHTML = "";
    	document.getElementById('time').innerHTML = "";
    	document.getElementById('guest').innerHTML = 0;
	    }

    if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
        layer.bringToFront();
    }
}
function resetHighlight(e) {
	document.getElementById('name').innerHTML = "";
	document.getElementById('sport').innerHTML = "";
	document.getElementById('time').innerHTML = "";
	document.getElementById('guest').innerHTML = 0;
    geojson.resetStyle(e.target);
}
var last;
var currentEvent;

function prepButtons(e) {
    var layer = e.target;
    //THE PARK WAS CLICKED
    $("#newEventButton").show();
    if (layer.feature.geometry.properties.objectid in events) {
    	$("#eventInfoButton").show();
    	//THE EVENT EXIST
    	console.log(events[layer.feature.geometry.properties.objectid][0]);
    	event = events[layer.feature.geometry.properties.objectid][0];
    	currentEvent = event;
    	//document.getElementById('button1').
		eventInfoURL = "http://ec2-54-165-162-209.compute-1.amazonaws.com/eventInfo.php?&park_name="+layer.feature.geometry.properties.name+"&sport="+event.sport+"&start_time="+event.start_time+"&end_time="+event.end_time+"&eventID="+event.eventID+"&address="+layer.feature.geometry.properties.address+", Waterloo, ON";
    }else{
    	$("#eventInfoButton").hide();
    	eventInfoURL = ""
    }
    createEventURL = "http://ec2-54-165-162-209.compute-1.amazonaws.com/createEvent.php?&parkName="+layer.feature.geometry.properties.name+"&parkID="+layer.feature.geometry.properties.objectid;

}
var eventInfoURL = "";
var createEventURL = "";
function newEventClick(){
	document.location.href = createEventURL;
}
function eventInfoClick(){
	if (eventInfoURL != "") {
		document.location.href = eventInfoURL;
	}
}

function onEachFeature(feature, layer) {
	/*if (!isMobile) {
		layer.on({
	        mouseover: highlightFeature,
	        mouseout: resetHighlight,
	        click: popUpInfo
	    });
	}else{*/
		layer.on({
	        click: highlightFeature
	    });
	//}
    
}
function chooseColour(d){
	colour = '#FF2CF4';
	if ((d in events)) {
		var c = Date.parse(events[d][0].start_time);
	    var d = new Date(c);
		var today = new Date();
	    var y = d.getYear();
	    var m = d.getMonth();
	    var d = d.getDate();
	    var yT = today.getYear();
	    var mT = today.getMonth();
	    var dT = today.getDate();
		if (y==yT && m==mT && d==dT) {
			colour = "#2CFFF7";
		}else{
			colour = "#2C4BFF";
		}
	}
	return colour;
}
function style(feature) {
    return {
        fillColor: chooseColour(feature.geometry.properties.objectid),
        weight: 2,
        opacity: 1,
        color: 'white',
        fillOpacity: 0.7
    };
}


getWaterlooReasturants();
getWaterlooMaps();
