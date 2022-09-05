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

  const map = L.map("map", options);
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 18,
    minZoom: 5,
    attribution:
      'Map data and Imagery &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
  }).addTo(map);

  L.control
    .zoom({
      position: "bottomright",
    })
    .addTo(map);

  //------------------------ Icon & Layergroup by category initialized --------------------------------------------//
  //php directory url, start with ".", javascript start with nothing
  var icons_loc = info_json.icons_directory.replace(".", "");
  var category_icon_array = {};
  var category_layergroup_array = {};
  for (let [category_name, shortname] of Object.entries(info_json.icons)) {
    let icon_file = shortname + ".svg";
    let option_array = {
      iconUrl: icons_loc + "/" + icon_file,
      iconSize: [20, 20],
    };
    console.log(option_array["iconUrl"]);
    eval("var Icon_" + shortname + "= L.icon(option_array)" + ";");
    eval(
      'category_icon_array["' + category_name + '"] = Icon_' + shortname + ";"
    );
    eval("var group_" + shortname + "= L.layerGroup();");
    eval(
      'category_layergroup_array["' +
        category_name +
        '"] = group_' +
        shortname +
        ";"
    );
  }

  var group_all = L.layerGroup();
  var mcgLayerSupportGroup_auto = L.markerClusterGroup.layerSupport({
    maxClusterRadius: function (mapZoom) {
      if (mapZoom > 13) {
        return 5;
      } else {
        return 20;
      }
    },
  });

  // L.markerClusterGroup({
  //     chunkedLoading: true,
  // maxClusterRadius: function (mapZoom) {
  //     if (mapZoom > 9) {
  //         return 20;
  //     } else {
  //         return 80;
  //     }
  // },
  // });

  //var mcgLayerSupportGroup_auto = L.markerClusterGroup();

  console.log(category_layergroup_array);
  // variables defined
  //Icon_brauchtum, Icon_gemeinden, Icon_interest, Icon_kulturelle, Icon_sagen, Icon_sprache,Icon_themen
  //group_brauchtum, group_gemeinden, group_interest, group_kulturelle, group_sagen, group_sprache,group_themen

  // var category_icon_array ={
  //                     "Brauchtum und Veranstaltungen" :Icon_brauchtum,
  //                     "Gemeinden"                     :Icon_gemeinden,
  //                     "Kulturelle Sehenswürdigkeiten" :Icon_kulturelle,
  //                     "Point of Interest"             :Icon_interest,
  //                     "Sagen + Legenden"              :Icon_sagen,
  //                     "Sprache und Dialekt"           :Icon_sprache,
  //                     "Thementouren"                  :Icon_themen
  //                 };

  //var category_shortname_array = info_json.icons;
  //console.log(category_shortname_array);

  json_w_geocode.features.forEach((feature) => {
    let category = feature.taxonomy.category.name;
    let category_shortname = feature.taxonomy.category.shortname;
    //let Icon_filename = category_shortname_array[category];

    // var tapahtumaTab = '<a href="#tapahtuma-' + feature.properties.name + '" data-toggle="tab"><p>' + feature.properties.name + '</p></a>';
    // jQuery('<p>', {html: tapahtumaTab}).appendTo('#datenbank_list');

    function createListItem({
      post_id,
      title,
      category_name,
      category_shortname,
    }) {
      let htmltext =
        '<div class="datenbank_single_entry map_link_point category_' +
        category_shortname +
        '" id="map_id_' +
        post_id +
        '" category="' +
        category_shortname +
        '">' +
        '<div class="entry_title">' +
        title +
        "</div>" +
        '<div class="entry_category"><img style="height: 20px; width: 20px; margin-right: 2px;" src="/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/icons/' +
        category_shortname +
        '.png"/>' +
        category_name +
        "</div>" +
        "</div>";
      return htmltext;
    }

    let Icon_name = category_icon_array[category];
    let popuptext =
      "<a href ='#' target=\"_blank\">" + feature.properties.name + "</a>";

    let marker_option = {
      icon: Icon_name,
      name: feature.properties.name,
      post_id: feature.properties.post_id,
    };

    let marker = L.marker(
      [feature.geometry.coordinates[1], feature.geometry.coordinates[0]],
      marker_option
    );

    //console.log(marker);
    marker.bindPopup(popuptext);

    // //dynamic
    // let abschaltung_slug = feature.filter.abschaltung.slug;
    // let abschaltung_slug_unter = 'abschaltung_' + abschaltung_slug.replace(/\-/g, "_");
    // let temp_string = 'group_' + abschaltung_slug_unter;
    // let group_abschaltung_uhrzeit = window[temp_string];

    // //console.log('marker.addTo(group_' +  abschaltung_slug_unter + ');');

    // marker.addTo(group_abschaltung_uhrzeit);
    eval("marker.addTo(group_" + category_shortname + ");");
    marker.addTo(group_all);
  });

  mcgLayerSupportGroup_auto.addTo(map);
  mcgLayerSupportGroup_auto.checkIn(group_all);
  group_all.addTo(map);
  //console.log(group_abschaltung_all);
  //save_layerId_in_html(group_abschaltung_all);
  //build_link(map, group_abschaltung_all);

  var current_postid = document
    .getElementById("current_post_id")
    .getAttribute("value");
  console.log(current_postid);
  find_marker_by_post_id(group_all, current_postid);

  var input_json;
  json_w_geocode.features.forEach((feature) => {
    console.log(feature.id + "?" + current_postid);
    console.log(feature.route[0]);
    if (feature.id == current_postid && !(feature.route[0].length == 0)) {
      console.log("here");
      let string_json = decodeURIComponent(JSON.stringify(feature.route[0]));
      console.log(decodeURIComponent(JSON.stringify(feature.route[0])));
      eval("input_json = " + string_json.slice(1, -1) + ";");
      console.log("input_json = " + string_json.slice(1, -1) + ";");
      var drawnroute = L.geoJson(input_json).addTo(map);
      map.fitBounds(drawnroute.getBounds(), { padding: [50, 50] });
    }
  });

  function find_marker_by_post_id(markers, post_id) {
    console.log(markers);
    markers.eachLayer((marker) => {
      if (post_id == marker["options"]["post_id"]) {
        var map_id = markers.getLayerId(marker);
        console.log(map_id);
        console.log(markers.getLayer(map_id));
        var marker = markers.getLayer(map_id);
        var markerBounds = L.latLngBounds([marker.getLatLng()]);
        //console.log(markerBounds);
        map.fitBounds(markerBounds);
        map.setZoom(13.5);
        marker.openPopup();
      }
    });
  }

  //var drawnItems  = L.geoJson(data).addTo(map);
  //drawnItems.addLayer(circle);
  //map.addLayer(drawnItems);
} // Main closing
main();
