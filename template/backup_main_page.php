<html>

<?php include 'head.php'; ?>
<body>
	<div calss="main_block" id="map"></div>
 
  <div class="main_block" id="side_bar">

		<p>Kategory</p>
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
			// $longi = get_post_meta( get_the_ID(), $key = "longitude", true);
			// 	//settype ($lenght, "float");
				
			// 	$lati = get_post_meta( get_the_ID(), $key = "latitude", true);
			// 	//settype ($lati, "float");
			// if ($longi>0 and $lati>0){
				$category_slug = get_the_category( )[0]->slug;
				$category_name = get_the_category( )[0]->name;
				$category_icon = $category_icon_array[$category_name];
				$category_icon_src = '/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/icons/'. $category_icon;
				$url = '/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/icons/star.png';
							
				$string .=' <div class="datenbank_single_entry map_link_point" id="map_id_'. get_the_ID() .'">
							<div class="entry_title">' . get_the_title() .'</div>
							<p>'.$longi. $lati .'</p>
							<div class="entry_category' .$category_icon. '"><img style="height: 20px; width: 20px; margin-right: 2px;"  src="'.$category_icon_src.'"/>'.$category_name.'</div>
							<div class="eingrag_ansehen_button" id="button_to_post_'. get_the_ID() .'" >Eingrag ansehen</div>
							</div>'; //closing class datenbank_single_entry
			//}
		  }
		  $string .= '</div>'; // closeing class datenbank_list
		
		} else $string = '<h3>Aktuell gibt es keine eingetragenen Unternehmen</h3>';   
			
		/* Restore original Post Data*/
		wp_reset_postdata();
		
		$string .= '</div>'; // closeing class datenbank list block 

		echo $string;
		?>

	</div>

</body>
</html>
