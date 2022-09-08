<html>
<?php include 'head.php'; ?>

<body>
  <div calss="main_block main_page_left">

    <div class="main_map_block" id="main_page_map"></div>
    <div class="map_top_banner" id="map_top_banner" style="width:70%; border: 1px solid red; z-index: 999; position:absolute;
		top:0; left:0;">
      map top banner</div>
    <div class="main_block main_page_right" id="side_bar">

      <div id="checkboxes">
        <!--div id checkboxes  -->
        <p>Kategory</p>
        <?php  
			$category_shortname_array  = $sinngrundKultureBank->get_category_shortname_array();

			foreach ($category_shortname_array as $name => $shortname)  {
				$category_shortname = $category_shortname_array[$name];
				$category_icon = $category_shortname_array[$name].'.svg';
				$category_icon_src = '/wp-content/plug	ins/Sinngrund-Kulturdatenbank-plugin/icons/'. $category_icon;
							echo '<input type="checkbox" value="'.$category_shortname.'" category_name="'.$name .'" name="kategory_filter" checked="true"><img style="height: 20px; width: 20px; margin-right: 2px;"  src="'.$category_icon_src.'"/>'. $name . '<br />';
					}
			
			?>
      </div> <!-- closing div id checkboxes  -->

      <div class="sort_options_block">
        such nach
        <select name="sort_options" id="main_page_list_sort_options">
          <option value="0" selected>Aktuellst zuerst</option>
          <option value="1">Title:Alpabet</option>
          <option value="2">Author:Alphabet</option>
        </select>
      </div>


      <div class="search">
        <input type="search" id="search" name="search" class="searchTerm" placeholder="What are you looking for?">
        <button type="submit" class="searchButton">
          <svg viewBox="0 0 1024 1024">
            <path class="path1"
              d="M848.471 928l-263.059-263.059c-48.941 36.706-110.118 55.059-177.412 55.059-171.294 0-312-140.706-312-312s140.706-312 312-312c171.294 0 312 140.706 312 312 0 67.294-24.471 128.471-55.059 177.412l263.059 263.059-79.529 79.529zM189.623 408.078c0 121.364 97.091 218.455 218.455 218.455s218.455-97.091 218.455-218.455c0-121.364-103.159-218.455-218.455-218.455-121.364 0-218.455 97.091-218.455 218.455z">
            </path>
          </svg>
        </button>
      </div>
      <div id="livesearch"></div>

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
<?php include 'foot.php'; ?>

</html>