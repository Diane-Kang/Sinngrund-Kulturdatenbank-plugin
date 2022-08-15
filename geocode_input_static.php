<!-- How see php file in web browser 


After installation of Lamp system in Ubuntu. Please follow the below two steps to run your php file.

    Place your php file (.php) in /var/www/html/ (default path)
    Please run url as localhost/withfilename.php

Example : I have placed welcome.php file in the /var/www/html/welcome.php then url will be http://localhost/welcome.php


 -->


<html>
<head>
<title>Leaflet Address Lookup and Coordinates</title>
<meta charset="utf-8">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"></script>
<style type="text/css">
html, body { width:100%;padding:0;margin:0; }
.container { width:95%;max-width:980px;padding:1% 2%;margin:0 auto }
#lat, #lon { text-align:right }
#map { width:100%;height:50%;padding:0;margin:0; }
.address { cursor:pointer }
.address:hover { color:#AA0000;text-decoration:underline }
</style>
</head>
<body>
	
	<div class="container">

		<div class="manual_input">
			<p class="meta-options1 basic_info_field">
				<label for="latitude">Breitengrad(latitude)</label>
				<input  id="latitude" type="text" name="latitude"
						value="<?php echo "hello" ?>"
				>
			</p>
			<p class="meta-options2 basic_info_field">
				<label for="longitude">Längengrad(longitude)</label>
				<input id="longitude" type="text" name="longitude"
				value="<?php echo "hello" ?>"
				>
			</p>
			<p class="meta-options3 basic_info_field">
				<label for="popuptext">text for marker</label>
				<input id="popuptext" type="text" name="popuptext"
				value="<?php echo "hello" ?>"
						>
			</p>
		</div>

		<b>Coordinates</b>
		<form>
		Lat <input type="text" name="lat" id="lat" size=12 value="">
		Lon <input type="text" name="lon" id="lon" size=12 value="">
		<button type="button" onclick="save_geocode_metadata();">use it</button>
		</form>

		<b>Address Lookup</b>
		<div id="search">
		<input type="text" name="addr" value="" id="addr" size="58" />
		<button type="button" onclick="addr_search();">Search</button>
		<div id="results"></div>
		</div>

		<br />

		<div id="map"></div>

	</div>

<script type="text/javascript">

// New York
var startlat = 40.75637123;
var startlon = -73.98545321;

var options = {
 center: [startlat, startlon],
 zoom: 9
}

document.getElementById('lat').value = startlat;
document.getElementById('lon').value = startlon;

var map = L.map('map', options);
var nzoom = 12;

L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {attribution: 'OSM'}).addTo(map);

var myMarker = L.marker([startlat, startlon], {title: "Coordinates", alt: "Coordinates", draggable: true}).addTo(map).on('dragend', function() {
 var lat = myMarker.getLatLng().lat.toFixed(8);
 var lon = myMarker.getLatLng().lng.toFixed(8);
 var czoom = map.getZoom();
 if(czoom < 18) { nzoom = czoom + 2; }
 if(nzoom > 18) { nzoom = 18; }
 if(czoom != 18) { map.setView([lat,lon], nzoom); } else { map.setView([lat,lon]); }
 document.getElementById('lat').value = lat;
 document.getElementById('lon').value = lon;
 myMarker.bindPopup("Lat " + lat + "<br />Lon " + lon).openPopup();
});

function chooseAddr(lat1, lng1)
{
 myMarker.closePopup();
 map.setView([lat1, lng1],18);
 myMarker.setLatLng([lat1, lng1]);
 lat = lat1.toFixed(8);
 lon = lng1.toFixed(8);
 document.getElementById('lat').value = lat;
 document.getElementById('lon').value = lon;
 myMarker.bindPopup("Lat " + lat + "<br />Lon " + lon).openPopup();
}

function myFunction(arr)
{
 var out = "<br />";
 var i;

 if(arr.length > 0)
 {
  for(i = 0; i < arr.length; i++)
  {
   out += "<div class='address' title='Show Location and Coordinates' onclick='chooseAddr(" + arr[i].lat + ", " + arr[i].lon + ");return false;'>" + arr[i].display_name + "</div>";
  }
  document.getElementById('results').innerHTML = out;
 }
 else
 {
  document.getElementById('results').innerHTML = "Sorry, no results...";
 }

}

function addr_search()
{
 var inp = document.getElementById("addr");
 var xmlhttp = new XMLHttpRequest();
 var url = "https://nominatim.openstreetmap.org/search?format=json&limit=3&q=" + inp.value;
 xmlhttp.onreadystatechange = function()
 {
   if (this.readyState == 4 && this.status == 200)
   {
    var myArr = JSON.parse(this.responseText);
    myFunction(myArr);
   }
 };
 xmlhttp.open("GET", url, true);
 xmlhttp.send();
}


function save_geocode_metadata(){
    document.getElementById("longitude").value = document.getElementById("lon").value;
    document.getElementById("latitude").value = document.getElementById("lat").value;
}

</script>

</body>
</html>
