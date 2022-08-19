<html style="margin-top: 0 !important;" class="no-js" <?php language_attributes(); ?>>
<?php wp_head(); ?>
<script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ) . 'single_post.js'?>"></script>
<style type="text/css">
#map { width:30%;height:100%;padding:0;margin:0; float:left;}

#side_bar { width:67%;height:100%;padding-left:20px;margin:0; }

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


.post_content{
	height: 90vh;
	overflow-y: scroll;
margin: 5% 2% 0% 2%;
}

</style>
<body <?php body_class(); ?> >
<div id="current_post_id" value="<?php echo get_the_ID(); ?>"></div>

	<div calss="main_block" id="map"></div>
 
  <div class="main_block" id="side_bar">

		

		<?php 
		
		$string = ""; // html string
		
		$string .= '<div class="post_content_block">';

		  $string .= '<div class="post_content">';

		  $string .= '<h3>'.get_the_title().'</h3>';
		  $string .= get_the_content();

		  $string .= '</div>'; // closeing class post_content
		
		
		wp_reset_postdata();
		
		$string .= '</div>'; // closeing class datenbank list block 

		echo $string;
		?>

	</div>

</body>
	
</html>
