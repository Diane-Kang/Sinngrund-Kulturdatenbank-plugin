// For single_post.php //

async function get_geojson($endpoint) {
  let url = $endpoint;
  let response = await fetch(url);
  let json = await response.json();
  return json;
}

async function main() {
  const geojson_endpoint = "/wp-json/Sinngrund-Kulturdatenbank-plugin/geojson";
  const json_w_geocode = await get_geojson(geojson_endpoint);

  const info_json_endpoint =
    "/wp-json/Sinngrund-Kulturdatenbank-plugin/infojson";
  const info_json = await get_geojson(info_json_endpoint);

  //var datenbank_single_entry = document.getElementsByClassName("datenbank_single_entry");
  //for (i = 0; i < all_unternehmen.length; i++) all_unternehmen[i].style.display = 'flex';

  //------------------------Map initialized --------------------------------------------//

  var options = {
    center: info_json.map_center,
    zoomSnap: 0.1,
    zoom: 12.5,
    zoomControl: false,
  };

  const map = L.map("single_post_map", options);
  //L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/256/{z}/{x}/{y}?access_token={accessToken}', {
      maxZoom: 18,
      minZoom: 5,
      attribution:
      '© <a href="https://www.mapbox.com/about/maps/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> <strong><a href="https://www.mapbox.com/map-feedback/" target="_blank">Improve this map</a></strong>',
      //id: 'mapbox/streets-v11',
      id:'pondelek/cl9fbuboj000e14o2xcxw3oom',
      accessToken: 'pk.eyJ1IjoicG9uZGVsZWsiLCJhIjoiY2w5Zm1tc3h4MGphODNvbzBkM29jdWRlaCJ9.j64kLJQP_RmwAccN1jGKrw'
    }).addTo(map);

  L.control
    .zoom({
      position: "bottomright",
    })
    .addTo(map);

//Definde a cluster Radius : smaller number only take close markers to make a cluster. 
  //// lager number: take more markers around in the long range of radius 
  var mcgLayerSupportGroup_auto = L.markerClusterGroup.layerSupport({
    maxClusterRadius: function (mapZoom) {
      if (mapZoom > 15) {
        return 5;
      } else {
        return 40;
      }
    },
  });
  mcgLayerSupportGroup_auto.addTo(map);



  //------------------------ Array of Icon & Layergroup by category initialized --------------------------------------------//
  //php directory url, start with ".", javascript start with nothing
  var icons_loc = info_json.icons_directory.replace(".", "");
  var category_icon_array = {};
  var category_layergroup_array = {};
  
  for (let [category_name, shortname] of Object.entries(info_json.icons)) {
    let icon_file = shortname + ".svg";
    let option_array = {
      iconUrl: icons_loc + "/" + icon_file,
      iconSize: [40, 40],
    };
    category_icon_array[category_name] = L.icon(option_array);
    category_layergroup_array[category_name]=L.layerGroup();
  }
  
  var group_all = L.layerGroup();

  //make marker and add to map 
  json_w_geocode.features.forEach((feature) => {
    let category = feature.taxonomy.category.name;
    let Icon_name = category_icon_array[category];
    let popuptext, popupimage, popupexcerpt;
  
    popuptext =   '<div class="popup_title"><strong>'+ feature.properties.name + '</strong></div>';
    popupimage =  feature.properties.thumbnail_url ? '<img src="' + feature.properties.thumbnail_url + '" alt="'+ feature.properties.title +' thumbnail image" width="50px" height="50px"></img>' : '';  
    popupexcerpt = feature.properties.excerpt ? '<p>' + feature.properties.excerpt + '</p>' : '' ;
  
    let popuptext2 =   popupimage +
                '<div class="text_wrapper">' +
                  '<div class="popup_title">' + popuptext + '</div>' +
                  '<div class="popupcategory">'+category + '</div>' + 
                  '<p>' + popupexcerpt + '</p>' +
                  '<a class="popup_button button" href="' +  feature.properties.url + '">Eintrag ansehen' +
                  //  '<button class="popup_button">Eintrag ansehen</button>' +
                  '</a>' +
                '</div>';
                
    let marker_option = {
      icon: Icon_name,
      name: feature.properties.name,
      post_id: feature.properties.post_id,
    };

    let marker = L.marker(
      [feature.geometry.coordinates[1], feature.geometry.coordinates[0]],
      marker_option
    ).bindPopup(popuptext2);

    marker.addTo(category_layergroup_array[category]);
    marker.addTo(group_all);
  });

  //apply the cluster Properties to the markers in group_all 
  mcgLayerSupportGroup_auto.checkIn(group_all);
  // all markers on Map, this one need to be after the checkIn
  group_all.addTo(map);


  // Get current post id 
  var current_postid = document
    .getElementById("current_post_id")
    .getAttribute("value");

  // Check if current post is for route oder point 

  function has_route_json(current_postid){

    for (let feature of json_w_geocode.features){
      if (feature.id == current_postid && !(feature.route[0].length == 0)) {
        let route_json = JSON.parse(decodeURIComponent(feature.route[0]));
        return route_json;
        break;
      }
    };
  }
  
  let route_json = has_route_json(current_postid);
  let this_marker = find_marker_by_post_id(group_all, current_postid);

  if(route_json){
    let drawnroute = L.geoJson(route_json).addTo(map);
    map.fitBounds(drawnroute.getBounds(), { padding: [100, 100] });
  }
  else{ 
    zoom_in_to_marker(this_marker);
  }
  this_marker.openPopup();


  function find_marker_by_post_id(markers, post_id) {
    var this_post_marker;
    markers.eachLayer((marker) => {
      if (post_id == marker["options"]["post_id"]) {
        marker["options"]["zIndexOffset"] = 99;
        var map_id = markers.getLayerId(marker);
        var marker = markers.getLayer(map_id);
        let popuptext = '<div class="hier_bin_ich"><div class="popup_title">'+ marker["options"]["name"] + '</div></div>';
        let bigIcon = L.icon({
          iconUrl : marker["options"]["icon"]["options"]["iconUrl"],
          iconSize: [60, 60],
        })
        marker.setIcon(bigIcon);
        marker.bindPopup(popuptext);
        this_post_marker = marker;
      }
    });
    return this_post_marker;
  }

  function zoom_in_to_marker(marker){
    var markerBounds = L.latLngBounds([marker.getLatLng()]);
    map.fitBounds(markerBounds);
    map.setZoom(16);
  }

  map.on('zoomend',function(e) {
    console.log(e.target.getZoom());
 })
  
} // Main closing
main();