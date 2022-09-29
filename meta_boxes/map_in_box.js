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
//var drawnItems = new L.FeatureGroup();

var drawnItems =L.featureGroup();
//var drawnItems = new L.FeatureGroup();
route_map.addLayer(drawnItems);


var node = document.getElementById('display_route_encoded');

console.log("??")
console.log(node.innerHTML);

if(node.innerHTML.length > 4){
  //let string_json = decodeURIComponent(JSON.stringify(node.innerHTML));
  //eval("route_json = "+string_json.slice(1,-1)+ ";");
  let route_json = JSON.parse(decodeURIComponent(node.innerHTML));
  //let route_json = string_json.slice(1,-1);
  console.log(route_json);
  drawnItems  = L.geoJson(route_json).addTo(route_map);
  route_map.addLayer(drawnItems);
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
  //   edit: {
  //     selectedPathOptions: {
  //       maintainColor: true,
  //       moveMarkers: true
  //     }
  //   }
  }
});

route_map.addControl(drawControl);

route_map.on(L.Draw.Event.CREATED, function(e) {
  drawnItems.addLayer((e.layer));
});





document.getElementById('export').onclick = function(e) {
  // Extract GeoJson from featureGroup
  var data = drawnItems.toGeoJSON();
  console.log(data.features.length);
  if (data.features.length == 0){
    console.log("here");
    document.querySelector('#content_sinn').innerHTML = "No valid geodata";
    return;
  }
  var geodata = data.features[0].geometry.coordinates;

  // show points 
  let string = "";
  geodata.forEach(coordinate=>{
    string = string +'['+coordinate[1] + ' ' +coordinate[0] +']' +'<br>';
  })
  document.querySelector('#content_sinn').innerHTML= "";
  document.querySelector('#content_sinn').insertAdjacentHTML(
    'afterbegin',
    '[Latitude, Longitude]<br>'+ string
  );

  //save to meta value
  document.getElementById('sad_route').value = encodeURIComponent(JSON.stringify(data));
  
  //document.getElementById('export').setAttribute('href', 'data:' + convertedData);
  //document.getElementById('export').setAttribute('download','data.geojson');

  // This is better way?  How to save a json as metadata https://wordpress.stackexchange.com/a/40417801
}


setTimeout(function(){route_map.invalidateSize();
},5000); 







//end----------------route_map------------------------