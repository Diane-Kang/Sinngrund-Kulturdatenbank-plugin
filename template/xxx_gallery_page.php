<?php if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly   ?>
<html>
<?php include 'head.php'; ?>

<body
  class="kulturdatenbank gallery <?php echo  (isset($_COOKIE['KDB_visitor_visit_time']) || is_user_logged_in() ) ? 'hide_info': NULL; ?>">

  <?php include 'nav_header.php'; ?>
  <div class="gallery_wrapper">
    <div class="head_wrapper">
    <h1>Mediendatenbank</h1>
    <nav class="orte">
      <?php $terms = get_terms([
    'taxonomy' => 'orte',
    'hide_empty' => false,
]); 
    foreach ($terms as $term){
    echo '<a href="#">'.$term->name.'</a> ';
    }
    
  
?>
    </nav>



    <a aria-label="zurück" href="/" class="close"><span class="close close_icon"></span></a>
    <div class="search">
      <input type="search" id="search" name="search" class="searchTerm" placeholder="Einträge durchsuchen">
      <button type="submit" class="searchButton">
        <svg viewBox="0 0 1024 1024">
          <path class="path1"
            d="M848.471 928l-263.059-263.059c-48.941 36.706-110.118 55.059-177.412 55.059-171.294 0-312-140.706-312-312s140.706-312 312-312c171.294 0 312 140.706 312 312 0 67.294-24.471 128.471-55.059 177.412l263.059 263.059-79.529 79.529zM189.623 408.078c0 121.364 97.091 218.455 218.455 218.455s218.455-97.091 218.455-218.455c0-121.364-103.159-218.455-218.455-218.455-121.364 0-218.455 97.091-218.455 218.455z">
          </path>
        </svg>
      </button>
    </div>
    </div>


    <div id="livesearch_media"></div>
    <?php 
$image_ids = get_posts(
    array(
        'post_type'      => 'attachment',
        'post_mime_type' => array( 'video','image','audio'),
        'terms' => $terms,
        'post_status'    => 'inherit',
        'posts_per_page' => - 1,
        'fields'         => 'ids',
    ) );

// $images = array_map( "wp_get_attachment_url", $image_ids );
$images = $image_ids;
?>
    <div class="grid-collection">
      <div class="grid-content" id="media-list">
        <?php

if ($images) {
    foreach ($images as $image) {?>


        <!-- <div class="grid-item">
          <div class="grid-main-wrap-image">
            <div class="grid-item-wrap">
              <img src="<?php echo wp_get_attachment_url($image); ?>" alt="">
            </div>

          </div>
          <div class="media_beschriftung">
            <h5><?php echo wp_get_attachment_caption($image); ?></h5>
          </div>
          <div class="orte_tags"> <?php echo get_the_term_list( $image, 'orte', '', ', ' ); ?></div>
          <div class="media_upload_date">Hochgeladen am <span><?php echo get_the_date('d.m.Y', $image); ?></span></div>
          <div class="media_author">von
            <?php echo get_the_author_meta(  'display_name',get_post_field( 'post_author', $image )); ?></div>
        </div> -->

        <?php    
    }   
}
?>
      </div>
    </div>
  </div>
  <?php include 'nav_footer.php'; ?>

  <?php include 'foot.php'; ?>
</body>
</html>