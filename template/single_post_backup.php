<html>
<?php include 'head.php'; ?>
<style type="text/css">
.main_block{display:inline-block; height:100%;}
#map { width:35%;height:100%;padding:0;margin:0; float:left;}
#entry_display{ max-width 650px; margin-left:38%; margin-right:5%;}
.post_content_scroll{height: 60vh;
overflow-y: scroll;
margin-top: 10px;}
.entry-header {
    display: none;
}
</style>
<body style="">
	<div calss="main_block" id="map"></div>
	<div calss="main_block" id="entry_display" style="">
		
			<div class="some_div"></div>
			<p>hello no padding</p>		
			<div class="post_contentbox">
			<div class="post_content_scroll">
				

		<?php

if ( have_posts() ) {

	while ( have_posts() ) {
		the_post();
?>
<?php 
		
			if ( is_singular() ) {
				the_title( '<h4 class="entry-title">', '</h4>' );
				?>
				<p>Icon, Kategory, update date, Contributor</p>
	
				<?php
				the_content();
				
			}?>

			</div>
			</div>

				
	</div>
	<?php	
		}
	}
	?>
		
</body>
	
</html>
