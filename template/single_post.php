
<html>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" >
	<link rel="profile" href="https://gmpg.org/xfn/11"> 
	<!-- wp_head() has all our dependency -->
	<?php wp_head(); ?>

</head>

<!-- <html style="margin-top: 0 !important;" class="no-js" <?php language_attributes(); ?>> -->

<!-- <script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ) . 'single_post.js'?>"></script> -->
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
    <div class="post_content_block">
      <div class="post_content">  
      <h3><?php echo  get_the_title(); ?></h3>
      
      <div class="entry_category">

        <?php 
        $name = get_the_category()[0]->name;
        $category_shortname_array = $sinngrundKultureBank->category_shortname_array();
        $category_shortname = $category_shortname_array[$name];
			  $category_icon = $category_shortname_array[$name].'.png';
			  $category_icon_src = '/wp-content/plug	ins/Sinngrund-Kulturdatenbank-plugin/icons/'. $category_icon;
        ?>
        <p><img style="height: 20px; width: 20px; margin-right: 2px;"  src="<?php echo $category_icon_src ?>"/><b><?php echo get_the_category()[0]->name; ?></b> <?php echo get_the_date(); ?> </p>
      </div>
      <?php
      echo get_the_content();
      wp_reset_postdata();
      ?>
      </div> <!-- // closeing class post_content -->
    </div> <!-- // closeing class datenbank list block -->

	</div>

</body>
<?php wp_footer(  ); ?>	
</html>
