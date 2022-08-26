// Metabox map initialize 
function map_init(div_id){
  // Sinngrund
  var startlat = 50.17203438669854;
  var startlon = 9.639869965557914;

  var options = {
  center: [startlat, startlon],
  zoom: 12
  }


  var route_map = L.map( div_id, options);


  L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {attribution: 'OSM'}).addTo(route_map);


  return route_map;
}

//----------------route_map------------------------
var route_map = map_init('route_map');

var drawnItems = new L.FeatureGroup();

route_map.addLayer(drawnItems);

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

  // This is better way?  How to save a json as metadata https://wordpress.stackexchange.com/a/40417801
}


setTimeout(function(){route_map.invalidateSize();
},1000); 


//end----------------route_map------------------------