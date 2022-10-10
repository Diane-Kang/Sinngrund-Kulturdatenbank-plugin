// Metabox map initialize 
function map_init(div_id){
  // Sinngrund
  let startlat = 50.17203438669854;
  let startlon = 9.639869965557914;

  let options = {
  center: [startlat, startlon],
  zoom: 12
  }
  let map = L.map(div_id, options);
  L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {attribution: 'OSM'}).addTo(map);
  return map;
}

//----------------route_map------------------------
var route_map = map_init('route_map');

var drawnItems =L.featureGroup();
route_map.addLayer(drawnItems);

//get saved route geodata
var node = document.getElementById('sad_route'); 

if(node.value){  //check if saved data is valid 
  let route_json = JSON.parse(decodeURIComponent(node.value));
  drawnItems  = L.geoJson(route_json).addTo(route_map);
  route_map.addLayer(drawnItems);
  show_geojson(route_json); // show current geojson 
}

var drawControl = new L.Control.Draw({
  position: 'topright',
  draw:{
    circle: false,
    circlemarker:false, 
    polygon:false,
    rectangle:false,
    marker:false
  },
  edit: {
   featureGroup: drawnItems
  }
});

route_map.addControl(drawControl);

route_map.on(L.Draw.Event.CREATED, function(e) {
  drawnItems.addLayer((e.layer));
  save_and_show_json();

});
route_map.on(L.Draw.Event.EDITED, function(e) {
  save_and_show_json();
});


function show_geojson(geojson_data){
  var geodata = geojson_data.features[0].geometry.coordinates;
  let string = "";
  geodata.forEach(coordinate=>{
    string = string +'['+coordinate[1] + ' ' +coordinate[0] +']' +'<br>';
  })
  document.querySelector('#content_sinn').innerHTML= "";
  document.querySelector('#content_sinn').insertAdjacentHTML(
    'afterbegin',
    '[Latitude, Longitude]<br>'+ string
  );
}

function save_and_show_json() {
  // Extract GeoJson from featureGroup
  var geojson_data = drawnItems.toGeoJSON();
  console.log(geojson_data.features.length);
  show_geojson(geojson_data);
  //save to meta value
  document.getElementById('sad_route').value = encodeURIComponent(JSON.stringify(geojson_data));
  
}


setTimeout(function(){route_map.invalidateSize();
},1000); 




//end----------------route_map------------------------