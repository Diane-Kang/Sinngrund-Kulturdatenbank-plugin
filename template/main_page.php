<html>

<?php include 'head.php'; ?>
<body>
	<div calss="main_block" id="map"></div>
 
  <div class="main_block" id="side_bar">

		<p>Kategory</p>
		<div id="checkboxes"><!--div id checkboxes  -->

		<?php  
		$category_shortname_array  = $sinngrundKultureBank->category_shortname_array();
		//$category_shortname_array = $sinngrundKultureBank::category_shortname_array();
		// $category_shortname_array = array(
		// 		"Brauchtum und Veranstaltungen" => "brauchtum",
		// 		"Gemeinden"                     => "gemeinden", 
		// 		"Kulturelle Sehenswürdigkeiten" => "kulturelle",
		// 		"Point of Interest"             => "interest", 
		// 		"Sagen + Legenden"              => "sagen",
		// 		"Sprache und Dialekt"           => "sprache",
		// 		"Thementouren"                  => "themen"
		// 	  );

		foreach ($category_shortname_array as $name => $shortname)  {
			$category_shortname = $category_shortname_array[$name];
			$category_icon = $category_shortname_array[$name].'.png';
			$category_icon_src = '/wp-content/plug	ins/Sinngrund-Kulturdatenbank-plugin/icons/'. $category_icon;
            echo '<input type="checkbox" value="'.$category_shortname.'" name="kategory_filter" checked="true"><img style="height: 20px; width: 20px; margin-right: 2px;"  src="'.$category_icon_src.'"/>'. $name . '<br />';
        }
		
		?>
		</div> <!-- closing div id checkboxes  -->

		<div class="sort_options">
			such nach 
			<select  name="uhrzeit" id="abschaltung_uhrzeit">
			<option value="0" selected >Aktuellst zuerst</option>
			<option value="1" >Title:Alpabet?</option>
			<option value="2" >Author:Alphabet</option>
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
		$string .= '	<div class="datenbank_list" id="datenbank_list">';
	  	$string .= '	</div>'; // closeing class datenbank_list
			
		/* Restore original Post Data*/
		wp_reset_postdata();
		
		$string .= '</div>'; // closeing class datenbank list block 

		echo $string;
		?>

	</div>

</body>
<?php wp_footer(  ); ?>
</html>
