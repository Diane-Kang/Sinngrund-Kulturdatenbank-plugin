<html>
<?php if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly   ?>
<?php include 'head.php'; ?>

<!-- <body <?php body_class(); ?> class="kulturdatenbank"> -->

<body
  class="kulturdatenbank <?php echo get_the_category()[0]->slug; ?> <?php echo  (isset($_COOKIE['KDB_visitor_visit_time']) || is_user_logged_in() ) ? 'hide_info': NULL; ?>">
  <div id="current_post_id" value="<?php echo get_the_ID(); ?>"></div>
  <div class="side_wrapper">
    <div class="single_post_main_block map_block" id="single_post_map"></div>
    <?php include 'nav_header.php'; ?>
    <?php include 'nav_footer.php'; ?>

    <div class="single_post_main_block sidebar" id="single_post_side_bar">
      <div class="scrolldown_wrapper"><a href="#content_start" aria-label="scrolldown"><span
            class="scrolldown icon"></span></a></div>
      <div id="content_start" class="post_content_block">
        <div class="post_content">
          <a aria-label="zurück" href="/" class="close"><span class="close close_icon"></span></a>
          <h1><?php echo  get_the_title(); ?></h1>

          <div class="entry_category">

            <?php 
        $name = get_the_category()[0]->name;
        $category_shortname_array = $sinngrundKulturdatenbank->get_category_shortname_array();
        $category_shortname = $category_shortname_array[$name];
			  $category_icon = $category_shortname_array[$name].'.svg';
			  $category_icon_src = '/wp-content/plug	ins/Sinngrund-Kulturdatenbank-plugin/icons/'. $category_icon;
        ?>
            <p><img src="<?php echo $category_icon_src ?>" /><b><?php echo get_the_category()[0]->name; ?></b>
              <span><?php echo get_the_date(); ?></span>
            </p>
          </div>
          <div class="just_checkin">
            <?php
        //echo get_the_content();
        the_content()
        ?>
          </div>
          <div class="content_footer">
            <button>Eintrag teilen</button>
            <p><?php 
          Global $post;
          $author = get_the_author_meta('display_name', $post->post_author); 
          $date = get_the_date('d.m.Y');
          $string = 'Eintrag erstellt von ' .  $author . ' am ' . $date . '.';
          echo $string; 
        ?>
            </p>
          </div>
          <?php
      // echo $sinngrundKultureBank->add_author_in_content(get_the_content()) . 'this';
      wp_reset_postdata();
      ?>
        </div> <!-- // closeing class post_content -->
      </div> <!-- // closeing class datenbank list block -->

    </div>
  </div>

  <?php include 'foot.php'; ?>
</body>

</html>