<!-- How see php file in web browser 


After installation of Lamp system in Ubuntu. Please follow the below two steps to run your php file.

    Place your php file (.php) in /var/www/html/ (default path)
    Please run url as localhost/withfilename.php

Example : I have placed welcome.php file in the /var/www/html/welcome.php then url will be http://localhost/welcome.php


 -->


<html>
<head>
<title>Sinngrund Allianz Kulturdatenbank</title>
<meta charset="utf-8">
<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ) . '/node_modules/leaflet/dist/leaflet.js'?>"></script>
<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) . '/node_modules/leaflet/dist/leaflet.css'?>" />
<style type="text/css">
html, body { width:100%;padding:0;margin:0; }
#map { width:70%;height:100%;padding:0;margin:0; float:left;}
#side_bar { width:25%;height:100%;padding:20px;margin:0; }
.main_block{display:inline-block;}
.container { width:95%;max-width:980px;padding:1% 2%;margin:0 auto }
#lat, #lon { text-align:right }
.address { cursor:pointer }
.address:hover { color:#AA0000;text-decoration:underline }
</style>
</head>
<body>
	<div calss="main_block" id="map"></div>
	<div class="main_block" id="side_bar">

		<h2>Post title</h2>
		<h3>Category</h3>
		<div id="checkboxes">

		<?php  
		
		$category_icon_array = array(
			"Brauchtum und Veranstaltungen" => "brauchtum.png",
			"Gemeinden"                     => "gemeinden.png", 
			"Kulturelle Sehenswürdigkeiten" => "kulturelle.png",
			"Point of Interest"             => "interest.png", 
			"Sagen + Legenden"              => "sagen.png",
			"Sprache und Dialekt"           => "sprache.png",
			"Thementouren"                  => "themen.png"
		  );


		foreach ($category_icon_array as $name => $icon)  {
            echo '<li><input type="checkbox">'. $name . '<br />';
        }
		
		?>
	</div> <!-- closing div id checkboxes  -->


		<?php 
		$the_query = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 400 ) );
		
		$string = ""; // html string
		
		$string .= '<div class="datenbank_list_block">';
		if  ( $the_query->have_posts() ) {
		  $string .= '<div class="datenbank_list">';
		  while ( $the_query->have_posts()) {
			$the_query->the_post();
			$category_slug = get_the_category( )[0]->slug;
			$category_name = get_the_category( )[0]->name;
			$category_icon = $category_icon_array[$category_name];
			$category_icon_src = '/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/icons/'. $category_icon;
			$url = '/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/icons/star.png';
			$string .=' <div class="datenbank_single_entry">
						  <div class="entry_title"><h4> this is post title:' . get_the_title() .'</h4></div>
						  <div class="entry_category ' .$category_icon. '"><img style="height: 20px; width: 20px; margin-right: 2px;"  src="'.$category_icon_src.'"/>'.$category_name.'</div>
						</div>'; //closing class datenbank_single_entry
		
		  }
		  $string .= '</div>'; // closeing class datenbank_list
		
		} else $string = '<h3>Aktuell gibt es keine eingetragenen Unternehmen</h3>';   
			
		/* Restore original Post Data*/
		wp_reset_postdata();
		
		$string .= '</div>'; // closeing class datenbank list block 

		echo $string;
		?>

	</div>
	

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

		

	</div>

<script type="text/javascript">



async function get_geojson($endpoint) {
    let url = $endpoint;
    let response = await fetch(url)
    let json = await response.json();
    return json;
}

async function main() {

    const geojson_endpoint = '/wp-json/Sinngrund-Kulturdatenbank-plugin/geojson';
    const json_w_geocode = await get_geojson(geojson_endpoint);

    const info_json_endpoint = '/wp-json/Sinngrund-Kulturdatenbank-plugin/infojson';
    const info_json = await get_geojson(info_json_endpoint);

    // const map = L.map('map', scrollWheelZoom = false, keyboard = false, zoomControl = false)
    // .setView(info_json.map_center, 12);

	var options = {
 	center: info_json.map_center,
	zoomSnap: 0.1,
 	zoom: 12.5
	}


	const map = L.map('map', options);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        minZoom: 5,
        attribution: 'Map data and Imagery &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

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
  

    json_w_geocode.features.forEach(feature => {

        let popuptext = "<a href ='#' target=\"_blank\">" + feature.properties.name + "</a>";
        // if (feature.filter.abschaltung.slug == "nicht-vorhanden") {
        //     popuptext = popuptext+ "<p class='" + feature.filter.abschaltung.slug + "'>" + "<span>Seit jeher kein Werbelicht vorhanden</span></p>";
        // }
        // else{
        //     popuptext = popuptext+ "<p class='" + feature.filter.abschaltung.slug + "'>" + "<span>Späteste Abschaltung</span> "+feature.filter.abschaltung.name +"!</p>";
        // }

        let category = feature.taxonomy.category[0].name;
        let Icon_name = category_icon_array[category];
        let marker_option = {icon:Icon_name}

        let marker = L.marker([
            feature.geometry.coordinates[1],
            feature.geometry.coordinates[0],
        ], marker_option).addTo(map);
        
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
        // marker.addTo(group_abschaltung_all);



    })

}
main();
</script>

</body>
</html>
