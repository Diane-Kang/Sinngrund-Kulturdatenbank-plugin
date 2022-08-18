

async function get_geojson($endpoint) {
    let url = $endpoint;
    let response = await fetch(url);
    let json = await response.json();
    return json;
}


async function main() {

    const geojson_endpoint = '/wp-json/Sinngrund-Kulturdatenbank-plugin/geojson';
    const json_w_geocode = await get_geojson(geojson_endpoint);

    const info_json_endpoint = '/wp-json/Sinngrund-Kulturdatenbank-plugin/infojson';
    const info_json = await get_geojson(info_json_endpoint);

	var options = {
 	center: info_json.map_center,
	zoomSnap: 0.1,
 	zoom: 12.5,
	zoomControl: false
	}

	const map = L.map('map', options);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        minZoom: 5,
        attribution: 'Map data and Imagery &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

	L.control.zoom({
    	position: 'bottomright'
	}).addTo(map);


    var group_abschaltung_all= L.layerGroup();

    //------------------------ Marker and List initialized --------------------------------------------//
    //php directory url, start with ".", javascript start with nothing
    var icons_loc = info_json.icons_directory.replace(".", "");

    for (let [key, value] of Object.entries(info_json.icons)) {
        let icon_file = value;
        let option_array = {
            iconUrl:icons_loc+ '/' +icon_file,
            iconSize: [20, 20]
        };
        let icon_name = icon_file.replace(".png", "");
        eval('var Icon_' + icon_name + '= L.icon(option_array)');
    }   // variable all defined Icon_brauchtum, Icon_gemeinden, Icon_interest, Icon_kulturelle, Icon_sagen, Icon_sprache,Icon_themen, Icon_star


    var marker_option = {name: 'center', icon: Icon_star, title: 'T Center'};
    var centmarker = L.marker(info_json.map_center, marker_option).addTo(map)

    var category_icon_array ={
                        "Brauchtum und Veranstaltungen" :Icon_brauchtum,
                        "Gemeinden"                     :Icon_gemeinden, 
                        "Kulturelle Sehenswürdigkeiten" :Icon_kulturelle,
                        "Point of Interest"             :Icon_interest, 
                        "Sagen + Legenden"              :Icon_sagen,
                        "Sprache und Dialekt"           :Icon_sprache,
                        "Thementouren"                  :Icon_themen
                    };

    var category_icon_array2 ={
                        "Brauchtum und Veranstaltungen" :"brauchtum",
                        "Gemeinden"                     :"gemeinden", 
                        "Kulturelle Sehenswürdigkeiten" :"kulturelle",
                        "Point of Interest"             :"interest", 
                        "Sagen + Legenden"              :"sagen",
                        "Sprache und Dialekt"           :"sprache",
                        "Thementouren"                  :"themen"
                    };
  

    json_w_geocode.features.forEach(feature => {

        let category = feature.taxonomy.category.name;
        let category_shortname = feature.taxonomy.category.shortname
        let Icon_filename = category_icon_array2[category];

        let Icon_name = category_icon_array[category];
        let popuptext = "<a href ='#' target=\"_blank\">" + feature.properties.name + "</a>";
        
        let marker_option = {
            icon:Icon_name,
            name: feature.properties.name,
            post_id: feature.properties.post_id}

        let marker = L.marker([
            feature.geometry.coordinates[1],
            feature.geometry.coordinates[0],
        ], marker_option);
        
        //console.log(marker);
        marker.bindPopup(popuptext);
        

        // //dynamic
        // let abschaltung_slug = feature.filter.abschaltung.slug;
        // let abschaltung_slug_unter = 'abschaltung_' + abschaltung_slug.replace(/\-/g, "_");
        // let temp_string = 'group_' + abschaltung_slug_unter;
        // let group_abschaltung_uhrzeit = window[temp_string];

        // //console.log('marker.addTo(group_' +  abschaltung_slug_unter + ');');
        // //eval('marker.addTo(group_' +  abschaltung_slug_unter + ');');
        // marker.addTo(group_abschaltung_uhrzeit);
        marker.addTo(group_abschaltung_all);



    })
    group_abschaltung_all.addTo(map);
    //console.log(group_abschaltung_all);
    //save_layerId_in_html(group_abschaltung_all);
    //build_link(map, group_abschaltung_all);


    function save_layerId_in_html(markers, option_name='post_id'){
        markers.eachLayer(marker => {
            var post_id = marker['options'][option_name];
            var map_id = markers.getLayerId(marker);
            document.getElementById('map_id_'+post_id).setAttribute('value',map_id)
        })
    }
    
    function build_link (map, markers){
        const divs = document.querySelectorAll('.map_link_point');
    
        divs.forEach(el => el.addEventListener('click', event => {
    
            let map_id = parseInt(event.target.getAttribute("value"));
            console.log(map_id);
            var marker = markers.getLayer(map_id);
            console.log(marker.getLatLng());
            var markerBounds = L.latLngBounds([marker.getLatLng()]);
            //console.log(markerBounds);
            map.fitBounds(markerBounds);
            map.setZoom(13.5);
            marker.openPopup();
            //console.log(event);
            //centerLeafletMapOnMarker(map, marker);
        }))
    }
        






}
main();