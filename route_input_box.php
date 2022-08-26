<!-- Search box reference https://stackoverflow.com/questions/15919227/get-latitude-longitude-as-per-address-given-for-leaflet -->

<meta charset="utf-8">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

<style type="text/css">
    /* html, body { width:100%;padding:0;margin:0; } */
    .container {height:400px; } 
    #route_map {  width:100%;
            height:400px;
            padding:0;
            margin:0; }
    .address { cursor:pointer }
    .address:hover { color:#AA0000;text-decoration:underline }

</style>

<div class="container" style="clear:both;">
  <div id="route_map" style="width:70%; float:right;"></div>
  <p>you can write geocordinate directly or get value from Search and as draging the Marker on the Map. To save post need to be published</p>
  <br>

  <input  id="sad_route" type="text" name="route" size=12 value="123">

  
  <br>
  <div id='delete'>Delete Features</div>
  <a href='#' id='export'>Export Features</a>
  <div id="content_sinn"></div>

</div>


<script type="text/javascript">

// Sinngrund
var startlat = 50.17203438669854;
var startlon = 9.639869965557914;

var options = {
 center: [startlat, startlon],
 zoom: 12
}

var route_map = L.map('route_map', options);
var nzoom = 16;

L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {attribution: 'OSM'}).addTo(route_map);

// var myMarker = L.marker([startlat, startlon], {title: "Coordinates", alt: "Coordinates", draggable: true}).addTo(route_map).on('dragend', function() {
//  var lat = myMarker.getLatLng().lat.toFixed(8);
//  var lon = myMarker.getLatLng().lng.toFixed(8);
// //  var czoom = route_map.getZoom();
// //  if(czoom < 18) { nzoom = czoom + 2; }
// //  if(nzoom > 18) { nzoom = 18; }
// //  if(czoom != 18) { route_map.setView([lat,lon], nzoom); } else { route_map.setView([lat,lon]); }
//  document.getElementById('lat').value = lat;
//  document.getElementById('lon').value = lon;
//  myMarker.bindPopup("Lat " + lat + "<br />Lon " + lon).openPopup();
// });


var drawnItems = new L.FeatureGroup();
route_map.addLayer(drawnItems);

// var drawControl = new L.Control.Draw({
//                   edit: {
//                     featureGroup: drawnItems,  
//                                   edit: {
//                                     selectedPathOptions: { moveMarkers: true}
//                                   }
//                   },
//                   draw: {
//                     rectangle: false,
//                     circle:false,
//                     polygon:false,
//                     marker: false
//     }
//                   });
// route_map.addControl(drawControl);

// route_map.on('draw:created', function (e) {
//             var type = e.layerType,
//                 layer = e.layer;
//             drawnItems.addLayer(layer);
//             //add_LayerInfo(layer._leaflet_id);
//         });




 var drawControl = new L.Control.Draw({
  edit: {
    featureGroup: drawnItems,
    edit: {
      selectedPathOptions: {
        maintainColor: true,
        moveMarkers: true
      }
    }
  }
});
route_map.addControl(drawControl);


route_map.on('draw:created', function(e) {
  var type = e.layerType,
    layer = e.layer;

  if (type === 'marker') {
    layer.bindPopup('A popup!');
  }

  drawnItems.addLayer(layer);
});






document.getElementById('export').onclick = function(e) {
  
  
  // Extract GeoJson from featureGroup
  var data = drawnItems.toGeoJSON();
  var geodata = data.features[0].geometry.coordinates;
  var string = "";
  var geo_array = [];

  geodata.forEach(coordinate=>{
    string = string + coordinate+'<br>';
    geo_array.push('['+coordinate+']');
  })
  // Stringify the GeoJson
  var convertedData = 'text/json;charset=utf-8,' + encodeURIComponent(JSON.stringify(data));

  // Create export
  document.querySelector('#content_sinn').insertAdjacentHTML(
    'afterbegin',
    geo_array
  );

  document.getElementById('sad_route').value = encodeURIComponent(JSON.stringify(data));
  

  //document.getElementById('export').setAttribute('href', 'data:' + convertedData);
  //document.getElementById('export').setAttribute('download','data.geojson');

}


setTimeout(function(){route_map.invalidateSize();
},1000); 




</script>

