<html>
<?php include 'head.php'; ?>

<body>
  <div class="container">
    <header>
      <div class="logo-text">
        <p>Kulturdatenbank</p>
        <p>Sinngrund</p>
      </div>

      <div class="title-text-box">
        <h1>Mediendatenbank</h1>
      </div>
      <form action="">

      </form>
    </header>

    <nav>
      <?php $terms = get_terms([
    'taxonomy' => 'orte',
    'hide_empty' => false,
]); 
    foreach ($terms as $term){
    echo '<a href="#">'.$term->name.'</a> ';
    }
    
  
?>
    </nav>
    <?php 
$image_ids = get_posts(
    array(
        'post_type'      => 'attachment',
        'post_mime_type' => 'image',
        'post_status'    => 'inherit',
        'posts_per_page' => - 1,
        'fields'         => 'ids',
    ) );

// $images = array_map( "wp_get_attachment_url", $image_ids );
$images = $image_ids;
?>
    <div class="grid-collection">
      <div class="grid-content" style="display: grid; grid-template-columns: repeat(3, 1fr); grid-gap: 15px;">
        <?php

if ($images) {
    foreach ($images as $image) {?>


        <div class="grid-item">
          <div class="grid-main-wrap-image" style="width:300px; height:400px; overflow: hidden; text-align: center;  display: flex;
  justify-content: center;
  align-items: center;">
            <div class="grid-item-wrap">
              <img src="<?php echo wp_get_attachment_url($image); ?>" alt="<?php ?>"
                style="display:inline-block; width:100%; position: relative; ">
            </div>

          </div>
          <div class="media_beschriftung">
            <h5><?php echo wp_get_attachment_caption($image); ?></h5>
          </div>
          <div class="orte_tags"> <?php echo get_the_term_list( $image, 'orte', '', ', ' ); ?></div>
          <div class="media_upload_date">Hochgeladen am <?php echo get_the_date('d.m.Y', $image); ?></div>
          <div class="media_author">von
            <?php echo get_the_author_meta(  'display_name',get_post_field( 'post_author', $image )); ?></div>
        </div>

        <?php    
    }   
}
?>
      </div>
    </div>


  </div>
</body>
<?php wp_footer(  ); ?>

</html>