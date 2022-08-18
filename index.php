<?php

/*
  Plugin Name: Sinngrund kulturebank plugin 
  Description: Es ist für Sinngrund kulturebank project
  Version: 0.0
  Author: Page-effect 
  Author-email: Diane.kang@page-effect.com


  npm install leaflet


*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class SinngrundKultureBank {

  function __construct() {

    //--------------Backend---------------- 
    //////--------------new Post page ----------------    
    //////////------------Meta data for new Post page----------------// 
    add_action('add_meta_boxes', array($this, 'basic_info_boxes'));
    add_action( 'save_post', array($this, 'save_basic_info_box' ));

    //////////------------Geocode searching for new Post page----------------//
    ////////// for Admin page/ backend dependecy admin_enqueue_scripts, for Frontend dependency wp_enqueue_scripts
    add_action( 'admin_enqueue_scripts', array($this,'leaflet_dependency'), 10, 1 );

    //////------------add Admin Menu----------------//
    /////////------- setting map page, and map ceter 
    add_action('admin_menu', array($this, 'adminPage'));
    add_action('admin_init', array($this, 'settings'));



    //--------------Frontend---------------- 
    //////-------------- Leaflet map dependecies---------------------//
    add_action( 'wp_enqueue_scripts', array($this,'leaflet_dependency'), 10, 1 );
    add_action( 'wp_enqueue_scripts', array($this, 'map_page_dependency'), 20, 1 );

     // This is for sinngrund-kulturedatenbank-diane
    add_filter('page_template', array($this, 'loadTemplate'), 99);
    add_filter('single_template', array($this, 'load_post_Template'), 99);

    add_action('get_header',array($this, 'remove_admin_login_header'));
    

    //shortcode for beitrag list 
    add_shortcode('show_list_shortcode', array($this, 'show_list_function'));
    
    // Rest API /wp-json/wp/v2/posts, add meta data 
    add_action('init', array($this, 'custom_meta_to_api'));
  
    // Rest API /wp-json/Sinngrund-Kulturdatenbank-plugin/geojson
    add_action( 'rest_api_init', array($this, 'geojson_generate_api'));

    // Rest API /wp-json/Sinngrund-Kulturdatenbank-plugin/geojson
    add_action( 'rest_api_init', array($this, 'infojson_generate_api'));
  
    //register_activation_hook(__FILE__, array($this, 'insert_main_map_page'));
    //register_deactivation_hook( __FILE__, array($this, 'deactivate_plugin'));
    //add_action('admin_head', array($this,'insert_main_map_page')
    //add_filter( 'page_template', array($this,'main_map_page_from_php') );

  
    add_filter('manage_posts_columns', array($this, 'custom_posts_table_head'));
    add_action( 'manage_posts_custom_column', array($this, 'bs_projects_table_content'), 10, 2);

  }

  function custom_posts_table_head( $columns ) {
    $columns['geocode'] = 'geocode';
    return $columns;
  }

  function bs_projects_table_content($name, $post_id) {
    switch ($name) {
        case 'geocode':
            $geocode .= get_post_meta( $post_id , 'latitude' , true ) .'<br>' . get_post_meta( $post_id , 'longitude' , true );
            echo $geocode;
            break;
    }
}


  function remove_admin_login_header() {
    remove_action('wp_head', '_admin_bar_bump_cb');
}
  //sad: Sinngrund Allianz Datenbank
  function settings() {
    add_settings_section('sad_first_section', null, null, 'sinngrund-datenbank-setting-page');

    add_settings_field('sad_mainpage_slug', 'Page Slug', array($this, 'slug_inputHTML'), 'sinngrund-datenbank-setting-page', 'sad_first_section');
    register_setting('singrundallianzplugin', 'sad_mainpage_slug', array('sanitize_callback' => array($this, 'sanitize_slug'), 'default' => 'map_page'));

    add_settings_section('sad_second_section', null, null, 'sinngrund-datenbank-setting-page');

    add_settings_field('sad_map_center_point', 'Map Center Point', array($this, 'map_center_point_HTML'), 'sinngrund-datenbank-setting-page', 'sad_second_section');
    register_setting('singrundallianzplugin', 'sad_map_center_point', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'map_page'));
  }

  function sanitize_slug($input) {

    $default_slug = 'sinngrund-kulturedatenbank-diane';
    
    $input = sanitize_title($input);
    
    if ($input == esc_attr(get_option('sad_mainpage_slug'))){
      return $input;
    }
    else if ($this->the_slug_exists($input) && ($input != $default_slug)) {
      $message = $input . ': this is already exsited as slug. Map page is now setted with default slug:'. $default_slug;
      add_settings_error('sad_mainpage_slug', 'sad_mainpage_slug_error', $message);
      return $default_slug;
    }

    else if ($input != $default_slug) {
           // Create post object
        $my_post = array(
          'post_title'    => $input,
          'post_name'     => sanitize_title($input),
          'post_status'   => 'publish',
          'post_author'   => 1,
          'post_type'     => 'page',
        );
    
        // Insert the post into the database
        wp_insert_post( $my_post );
    }
    return $input;
  }

  function map_center_point_HTML() { ?>
    <p>input need to be seperated by comma(,)</p>
    <p>longitude, latitude </p>
    <p> default : 50.15489468904496, 9.629545376420513</p>
    <input type="text" name="sad_map_center_point" size="50" value="<?php echo esc_attr(get_option('sad_map_center_point')) ?>">
  <?php }

  function slug_inputHTML() { ?>
      <p>Current main Page : <?php echo esc_attr(get_option('sad_mainpage_slug')) ?> </p>
      new page slug
      <input type="text" name="sad_mainpage_slug" value="<?php echo esc_attr(get_option('sad_mainpage_slug')) ?>">
  <?php }

  function adminPage() {
    add_options_page('Sinngrund Datenbank Setting', 'Sinngrund Ailianz', 'manage_options', 'sinngrund-datenbank-setting-page', array($this, 'ourHTML'));
  }

  function the_slug_exists($post_slug_text) {
    global $wpdb;
    if($wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_name = '" . $post_slug_text . "'", 'ARRAY_A')) {
        return true;
    } else {
        return false;
    }
}

  function ourHTML() { ?>
    <div class="wrap">
      <h1>Sinngrund Allianz Datenbank Setting</h1>
      <form action="options.php" method="POST">
      <?php
        settings_fields('singrundallianzplugin');
        do_settings_sections('sinngrund-datenbank-setting-page');
        submit_button();
      ?>
      </form>
    </div>
  <?php }

  function custom_meta_to_api(){
    register_post_meta(
      'post',
      'latitude',
      array(
          'single'       => true,
          'type'         => 'string',
          'show_in_rest' => true,
      )
  );

    register_post_meta(
      'post',
      'longitude',
      array(
          'single'       => true,
          'type'         => 'string',
          'show_in_rest' => true,
      )
    );
  }


  function show_list_function(){
    
    $the_query = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 400 ) );
    $string = ""; // html string
    
    $category_icon_array = array(
      "Brauchtum und Veranstaltungen" => "brauchtum.png",
      "Gemeinden"                     => "gemeinden.png", 
      "Kulturelle Sehenswürdigkeiten" => "kulturelle.png",
      "Point of Interest"             => "interest.png", 
      "Sagen + Legenden"              => "sagen.png",
      "Sprache und Dialekt"           => "sprache.png",
      "Thementouren"                  => "themen.png"
    );

    //$string .= '<p>'. $category_icon_array["Gemeinden"] .'</p>';

    // Entry List 
    $string .= '<div class="datenbank_list_block">';
    if  ( $the_query->have_posts() ) {
      $string .= '<div class="datenbank_list">';
      while ( $the_query->have_posts()) {
        $the_query->the_post();
        $category_slug = get_the_category( )[0]->slug;
        $category_name = get_the_category( )[0]->name;
        $category_icon = $category_icon_array[$category_name];
        $category_icon_src = '/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/icons/'. $category_icon;
        $url = '/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/icons/star.png';
        $string .=' <div class="datenbank_single_entry">
                      <div class="entry_title"><h4> this is post title:' . get_the_title() .'</h4></div>
                      <div class="entry_category ' .$category_icon. '"><img style="height: 20px; width: 20px; margin-right: 2px;"  src="'.$category_icon_src.'"/>'.$category_name.'</div>
                    </div>'; //closing class datenbank_single_entry
    
      }
      $string .= '</div>'; // closeing class datenbank_list
    
    } else $string = '<h3>Aktuell gibt es keine eingetragenen Unternehmen</h3>';   
        
    /* Restore original Post Data*/
    wp_reset_postdata();
    
    $string .= '</div>'; // closeing class datenbank list block 
    return $string;   
  }

    ///wp-json/Sinngrund-Kulturdatenbank-plugin/infojson
    function infojson_generate_api() {
      $plugin_folder_name = reset(explode('/', str_replace(WP_PLUGIN_DIR . '/', '', __DIR__)));
      register_rest_route( $plugin_folder_name, '/infojson/', array(
          'methods' => WP_REST_SERVER::READABLE,
          'callback' => array($this,'infojson_generator')
      ) );
    }
    
    function infojson_generator() {
      //$info_array=array();
      $plugin_folder_name = reset(explode('/', str_replace(WP_PLUGIN_DIR . '/', '', __DIR__)));
      $path_of_icons =  './wp-content/plugins/'.$plugin_folder_name. '/icons'  ;
      $icon_files = array_diff(scandir($path_of_icons), array('.', '..'));

      // if we need to make a custom section for center 
      $longi = "50.15489468904496";
      settype ($longi, "float");

      $lati = "9.629545376420513";
      settype ($lati, "float");

      //$map_center_geo = array($longi,$lati );
      $map_center_geo = array_map("floatval", explode(',', esc_attr(get_option('sad_map_center_point'))));
            
      $info_array= array( 'map_center' => $map_center_geo,
                          'icons_directory'=> $path_of_icons,
                          'icons'=> $icon_files,
                          //'geo_code'=>$geo_code
                        );
      return $info_array;
    }



  // Register own Endpoint for API - /wp-json/Sinngrund-Kulturdatenbank-plugin/geojson

  function geojson_generate_api() {
    $plugin_folder_name = reset(explode('/', str_replace(WP_PLUGIN_DIR . '/', '', __DIR__)));
    register_rest_route( $plugin_folder_name, '/geojson/', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => array($this,'geojson_generator')
    ) );
  }

  


  function geojson_generator() {
    $post_type_query = new WP_Query(array(
        'post_type' => 'post'
    ));

    $post_type_query_geojson = array();

    $category_shortname_array = array(
      "Brauchtum und Veranstaltungen" => "brauchtum",
      "Gemeinden"                     => "gemeinden", 
      "Kulturelle Sehenswürdigkeiten" => "kulturelle",
      "Point of Interest"             => "interest", 
      "Sagen + Legenden"              => "sagen",
      "Sprache und Dialekt"           => "sprache",
      "Thementouren"                  => "themen"
    );

    while ($post_type_query->have_posts()) {
        $post_type_query->the_post();


        //$longi = get_post_meta( get_the_ID(), $key = "2-Laengengrad", true);
        $longi = get_post_meta( get_the_ID(), $key = "longitude", true);
        settype ($lenght, "float");

        //$lati = get_post_meta( get_the_ID(), $key = "1-Breitengrad", true);
        $lati = get_post_meta( get_the_ID(), $key = "latitude", true);
        settype ($lati, "float");



        // //variable type string
        // $werbebeleuchtung_jn = get_post_meta( get_the_ID(), $key = "Werbebeleuchtung wurde im Projektrahmen angepasst (j/n)", true);

        // $abschaltung = get_the_terms( get_the_ID(), 'abschaltung' );
        // if (!empty($abschaltung)) {
        //     foreach ($abschaltung as $tag) {
        //         $uhrzeit = $tag;
        //     }
        // }
        

        array_push($post_type_query_geojson, array(
            'type'=> 'Feature',
            'id' => get_the_ID(),
            'geometry'=> array(
                'type'=> 'Point',
                'coordinates' =>  array($longi,$lati)
            ),
            'properties'=>array(
                'name' => get_the_title(),
                'post_id' => get_the_ID(),
                'url' => get_permalink()
            ),
            'taxonomy'=>array(
                'category'=>array(
                    'term_id'   => get_the_category()[0]->term_id,
                    'name'      => get_the_category()[0]->name,
                    'slug'      => get_the_category()[0]->slug, 
                    'shortname' => $category_shortname_array[get_the_category()[0]->name],
                    'icon_name' => $category_shortname_array[get_the_category()[0]->name].'.png'
                ) 
            ),
            'reference'=> get_the_category()

            // 'filter'=> array(
            //     'werbebeleuchtung' => $werbebeleuchtung_jn,
            //     'abschaltung' => $uhrzeit
            // )
        ));
    }

    $wrapper_array = array(
        "type" => "FeatureCollection",
        "features" => $post_type_query_geojson
    );

    return $wrapper_array;
  }


  function leaflet_dependency(){
    wp_enqueue_style( 'leaflet-main-css',                   plugin_dir_url( __FILE__ ) . '/node_modules/leaflet/dist/leaflet.css' , array(), false, false);
    wp_enqueue_script( 'leaflet-js',                        plugin_dir_url( __FILE__ ) . '/node_modules/leaflet/dist/leaflet.js', array(), false, false );
  }

  function map_page_dependency(){
    if (is_page($target_page_name)){
      wp_enqueue_script( 'map_modify-js',                     plugin_dir_url( __FILE__ ) . '/map_modify.js', array('leaflet-js'), '1.3', true);
    
      // map-app-style.css, controled by .page-id-227!!!!!
      wp_enqueue_style( 'map-app-style-css', plugin_dir_url( __FILE__ ) . '/map-app-style.css', array(), '3.2', false);
    }
  }
  
  // Gutenberg, block javascript 
  function adminAssets() {
    wp_enqueue_script('sinngrund_kulture_bank_block_type', plugin_dir_url(__FILE__) . '/build/index.js', array('wp-blocks', 'wp-element', 'wp-block-editor'));
    
  }


  // This is for sinngrund-kulturedatenbank-diane
  function loadTemplate($template) {
    if (is_page(esc_attr(get_option('sad_mainpage_slug')))) {
      return plugin_dir_path(__FILE__) . '/template/main_page.php';
    }
    return $template;
  }


  function load_post_Template($template) {
    if (is_single()) {
      return plugin_dir_path(__FILE__) . '/template/single_post.php';
    }
    return $template;
  }

  


  /**
  * Meta box display callback.
  *
  * @param WP_Post $post Current post object.
  */
  
  function basic_info_boxes_display_callback( $post ) {
    include plugin_dir_path( __FILE__ ) . '/basic_info_box.php';
  }
  

  function basic_info_boxes(){
    add_meta_box(   'basic_info', // name
                    __('Basic required data'), //display text 
                    array($this, 'basic_info_boxes_display_callback'), // call back function  
                    'post' );
  }
  

  function save_basic_info_box( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
        $post_id = $parent_id;
    }
    $fields = [
        'latitude',
        'longitude'
    ];
    foreach ( $fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
     }
  }
  




} // end of class 

$sinngrundKultureBank = new SinngrundKultureBank();


// // This for post template (new post page) with  Blocks
// function slug_post_type_template() {
// 	$page_type_object = get_post_type_object( 'post' );
//   $page_type_object->template = [
// 		['sinngrund/kulture-datenbank' 
//     ],
// 	];
//   //$post_type_object->template_lock = 'all'; // lock the template on the UI so that the blocks presented cannot be manipulated
// }
// add_action( 'init', 'slug_post_type_template' );


// function basic_info_boxes(){
//   add_meta_box(   'basic_info', // name
//                   __('Basic required data'), //display text 
//                   'basic_info_boxes_display_callback', // call back function  
//                   'post' );
// }
// add_action('add_meta_boxes', 'basic_info_boxes');

//   /**
// * Meta box display callback.
// *
// * @param WP_Post $post Current post object.
// */


// function basic_info_boxes_display_callback( $post ) {
//   include plugin_dir_path( __FILE__ ) . '/basic_info_box.php';
// }

// function save_basic_info_box( $post_id ) {
//   if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
//   if ( $parent_id = wp_is_post_revision( $post_id ) ) {
//       $post_id = $parent_id;
//   }
//   $fields = [
//       'latitude_longitude',
//       'latitude',
//       'longitude',
//       'popuptext'
//   ];
//   foreach ( $fields as $field ) {
//       if ( array_key_exists( $field, $_POST ) ) {
//           update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
//       }
//    }
// }
// add_action( 'save_post', 'save_basic_info_box' );
