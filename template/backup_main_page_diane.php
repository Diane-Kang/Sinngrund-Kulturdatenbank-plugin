<html>
<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" >

	<!-- <link rel="profile" href="https://gmpg.org/xfn/11"> -->

	<!-- wp_head() has all our dependency -->
	<?php wp_head(); ?>

</head>
<title>Sinngrund Allianz Kulturdatenbank</title>
<style type="text/css">
html, body { width:100%;padding:0;margin:0; }

#map { width:70%;height:100%;padding:0;margin:0; float:left;}

#side_bar { width:27%;height:100%;padding-left:20px;margin:0; }

.main_block{display:inline-block;}

.search {
  width: 100%;
  position: relative;
  display: flex;
}

.searchTerm {
  width: 100%;
  border: 3px solid #00B4CC;
  border-right: none;
  padding: 5px;
  height: 36px;
  border-radius: 5px 0 0 5px;
  outline: none;
  color: #9DBFAF;
}

.searchTerm:focus{
  color: #00B4CC;
}

.searchButton {
  width: 40px;
  height: 36px;
  border: 1px solid #00B4CC;
  background: #00B4CC;
  text-align: center;
  color: black;
  border-radius: 0 5px 5px 0;
  cursor: pointer;
  font-size: 20px;
}


.datenbank_list{
	height: 60vh;
	overflow-y: scroll;
}

.datenbank_single_entry{
	
  background-color:  #ccebff ;

  padding-left: 10px;
  padding-bottom: 10px;
  margin-top: 0px;
  padding-top: 5px;
  border-top-width: 1px;
  border-top-style: solid;
  border-left-width: 1px;
  border-left-style: solid;
  border-bottom-width: 1px;
  border-bottom-style: solid;
  border-right-width: 1px;
  border-right-style: solid;
  padding-right: 10px;
}


.container { width:95%;max-width:980px;padding:1% 2%;margin:0 auto }

#lat, #lon { text-align:right }
.address { cursor:pointer }
.address:hover { color:#AA0000;text-decoration:underline }
</style>
</head>
<body>
	<div calss="main_block" id="map"></div>
	<div calss="main_block" id="entry_display" style="display:none;">
		<h3>Post title</h3>
		<p>category author datum location</p>
		<div class="entry_content"> </div>
		<p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin non purus non elit convallis porttitor. Nulla auctor augue vel augue pretium fermentum at at orci. Aliquam sagittis suscipit justo a eleifend. Vestibulum nec lectus felis. Suspendisse eget arcu leo. Pellentesque in mauris facilisis, rutrum nisl at, luctus odio. Mauris euismod odio sit amet aliquet pharetra. Nullam eu cursus dui. Nam et nisl gravida purus laoreet blandit non in est. Donec in est vel urna commodo fringilla a ac libero. Duis tristique purus non laoreet tincidunt. Vivamus commodo dolor non ipsum bibendum ultrices. Donec posuere nibh ac volutpat vehicula. Proin nec tellus consequat, venenatis felis id, facilisis dolor. Sed rutrum tortor eget nulla varius tristique. Suspendisse non tortor tincidunt, cursus ex non, ultrices felis.

		Donec tempor sed lacus vel molestie. Nunc id orci vel nunc rutrum tempor. Nunc augue tortor, scelerisque ac commodo id, interdum vel metus. Nunc euismod ligula neque, sit amet tincidunt purus venenatis eu. Donec vitae volutpat risus, eu efficitur massa. Curabitur non ullamcorper ex. Morbi nec sodales lorem, quis consequat nibh. In id sapien at velit congue suscipit. Mauris id sodales nisi, in fringilla ligula. Fusce eget interdum elit. Fusce est mauris, fermentum ut volutpat sit amet, accumsan at risus. Vivamus at nibh ipsum.

		</p>

	</div>
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
			$category_icon = $category_icon_array[$name];
			$category_icon_src = '/wp-content/plug	ins/Sinngrund-Kulturdatenbank-plugin/icons/'. $category_icon;
            echo '<input type="checkbox"><img style="height: 20px; width: 20px; margin-right: 2px;"  src="'.$category_icon_src.'"/>'. $name . '<br />';
        }
		
		?>
		</div> <!-- closing div id checkboxes  -->

		<div class="sort_options">
			such nach 
			<select  name="uhrzeit" id="abschaltung_uhrzeit">
			<option value="0" selected >Aktuellst zuerst</option>
			<option value="1" selected >Title:Alpabet?</option>
			<option value="2" selected >Author:Alphabet</option>
			</select>
		</div>


		<div class="search">
			<input type="text" class="searchTerm" placeholder="What are you looking for?">
			<button type="submit" class="searchButton">
			<svg viewBox="0 0 1024 1024"><path class="path1" d="M848.471 928l-263.059-263.059c-48.941 36.706-110.118 55.059-177.412 55.059-171.294 0-312-140.706-312-312s140.706-312 312-312c171.294 0 312 140.706 312 312 0 67.294-24.471 128.471-55.059 177.412l263.059 263.059-79.529 79.529zM189.623 408.078c0 121.364 97.091 218.455 218.455 218.455s218.455-97.091 218.455-218.455c0-121.364-103.159-218.455-218.455-218.455-121.364 0-218.455 97.091-218.455 218.455z"></path></svg>
			</button>
		</div>


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
			$string .=' <div class="datenbank_single_entry" id="post_'. get_the_ID() .'">
						  <div class="entry_title"><h4> this is post title:' . get_the_title() .'</h4></div>
						  <div class="entry_category' .$category_icon. '"><img style="height: 20px; width: 20px; margin-right: 2px;"  src="'.$category_icon_src.'"/>'.$category_name.'</div>
						  <div class="eingrag_ansehen_button" id="button_to_post_'. get_the_ID() .'" >Eingrag ansehen</div>
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
