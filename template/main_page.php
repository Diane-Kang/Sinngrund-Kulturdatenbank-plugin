<html>
<?php include 'head.php'; ?>

<body>
  <div class="main_block main_page_left side_wrapper">

    <div class="main_map_block" id="main_page_map"></div>
      <nav class="menu top">
      <ul>
    <li><a class="logo" href="/"><h1 class="logo"><span class="d_blue">Kulturdatenbank</span><span class="l_blue">Sinngrund</span></h1></a></li>
    <li><a class="info" href="#">
<svg width="20px" height="20px" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <title>Info-Icon</title>
    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="01-willkommen" transform="translate(-593.000000, -38.000000)">
            <g id="Info-Icon" transform="translate(593.000000, 38.000000)">
                <path d="M19.542,10 C19.542,15.2877778 15.2553333,19.5733333 9.96866667,19.5733333 C4.68088889,19.5733333 0.394222222,15.2877778 0.394222222,10 C0.394222222,4.71222222 4.68088889,0.426666667 9.96866667,0.426666667 C15.2553333,0.426666667 19.542,4.71222222 19.542,10" id="Fill-1" fill="#FFFFFF"></path>
                <path d="M10,0 C4.47666667,0 0,4.47777778 0,10 C0,15.5222222 4.47666667,20 10,20 C15.5222222,20 20,15.5222222 20,10 C20,4.47777778 15.5222222,0 10,0 M10,2.22222222 C14.2888889,2.22222222 17.7777778,5.71111111 17.7777778,10 C17.7777778,14.2888889 14.2888889,17.7777778 10,17.7777778 C5.71111111,17.7777778 2.22222222,14.2888889 2.22222222,10 C2.22222222,5.71111111 5.71111111,2.22222222 10,2.22222222" id="Fill-3" fill="#009CDE"></path>
                <path d="M8.65288889,15.6503333 L8.65288889,7.837 C8.65288889,7.72588889 8.73177778,7.64588889 8.84288889,7.64588889 L11.0928889,7.64588889 C11.204,7.64588889 11.284,7.72588889 11.284,7.837 L11.284,15.6503333 C11.284,15.7614444 11.204,15.8403333 11.0928889,15.8403333 L8.84288889,15.8403333 C8.73177778,15.8403333 8.65288889,15.7614444 8.65288889,15.6503333 M8.60511111,5.53811111 C8.60511111,4.71366667 9.17511111,4.15922222 9.984,4.15922222 C10.7928889,4.15922222 11.3628889,4.71366667 11.3628889,5.53811111 C11.3628889,6.33144444 10.7762222,6.917 9.984,6.917 C9.17511111,6.917 8.60511111,6.33144444 8.60511111,5.53811111" id="Fill-5" fill="#009CDE"></path>
            </g>
        </g>
    </g>
</svg>  
    Info
  </a></li>
    <li>
      <a class="medien" href="/gallery">
<svg width="18px" height="16px" viewBox="0 0 18 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <title>Group 3</title>
    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="01-willkommen" transform="translate(-825.000000, -38.000000)">
            <g id="Group-3" transform="translate(825.000000, 38.000000)">
                <polygon id="Fill-1" fill="#FFFFFF" points="12.5171 5.6486 12.5171 1.7046 1.5221 1.7046 1.5221 11.1686 5.5521 11.1686 5.5521 14.6286 16.2401 14.6286 16.2401 5.6486"></polygon>
                <path d="M0,0 L0,12.001 L4,12.001 L4,16 L18,16 L18,4.001 L14,4.001 L14,0 L0,0 Z M2.014,9.986 L11.986,9.986 L11.986,2.014 L2.014,2.014 L2.014,9.986 Z M6.014,12.001 L14,12.001 L14,6.014 L15.986,6.014 L15.986,13.986 L6.014,13.986 L6.014,12.001 Z" id="Fill-2" fill="#009CDE"></path>
            </g>
        </g>
    </g>
</svg>
 Medien
      </a></li>
  </ul>
      </nav>
    <div class="main_block main_page_right sidebar" id="side_bar">

      <div id="checkboxes" class="category_filter_section">
        <!--div id checkboxes  -->
        <h2 class="category">Kategorie</h2>
        <?php  
			$category_shortname_array  = $sinngrundKultureBank->get_category_shortname_array();

			foreach ($category_shortname_array as $name => $shortname)  {
				$category_shortname = $category_shortname_array[$name];
				$category_icon = $category_shortname_array[$name].'.svg';
				$category_icon_src = '/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/icons/'. $category_icon;
							echo '<div class="single_category_wrapper"><input class="cat_checkbox" type="checkbox" id="'.$category_shortname.'" value="'.$category_shortname.'" category_name="'
              .$name .'" name="kategory_filter" checked="true">
              <label class="cat_label" for="'.$category_shortname.'"><img class="cat_icon" src="'.$category_icon_src.'"/><span class="cat_name">'. $name .'</span></label></div>';
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
    <div class="legal">
    <nav class="footer_nav">
        <ul>
            <li><a href="/impressum/">Impressum</a></li>
            <li><a href="/datenschutz">Datenschutzerklärung</a></li>
        </ul>

    </nav>
</div>
</body>
<?php include 'foot.php'; ?>

</html>