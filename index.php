<?php

/*
  Plugin Name: Sinngrund kulturebank plugin 
  Description: Es ist für Sinngrund kulturebank project
  Version: 0.0
  Author: Diane
  npm install leaflet


*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class SinngrundKultureBank {

  function __construct() {

    //Backend 
    //add_action('enqueue_block_editor_assets', array($this, 'adminAssets'));
    
    // Add meta data input box in new post page
    add_action('add_meta_boxes', array($this, 'basic_info_boxes'));
    add_action( 'save_post', array($this, 'save_basic_info_box' ));
    
    // for Admin page/ backend dependecy admin_enqueue_scripts, for Frontend dependency wp_enqueue_scripts
    add_action( 'admin_enqueue_scripts', array($this,'leaflet_dependency'), 10, 1 );



    // Frontend 

    $target_page_name = 'sinngrund-kulturedatenbank';
    
    add_action( 'wp_enqueue_scripts', array($this,'leaflet_dependency'), 10, 1 );
    add_action( 'wp_enqueue_scripts', array($this, 'map_page_dependency'), 20, 1 );

     // This is for sinngrund-kulturedatenbank-diane
    add_filter('page_template', array($this, 'loadTemplate'), 99);

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
  }



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
    //     // Things that you want to do.
    // // Get the relative path to current file from plugin root
    // $file_path_from_plugin_root = str_replace(WP_PLUGIN_DIR . '/', '', __DIR__);

    // // Explode the path into an array
    // $path_array = explode('/', $file_path_from_plugin_root);

    // // Plugin folder is the first element
    // $plugin_folder_name = reset($path_array);
    $plugin_folder_name = reset(explode('/', str_replace(WP_PLUGIN_DIR . '/', '', __DIR__)));
    //return '/wp-json/' . $plugin_folder_name . '/geocode/' ;
    return plugin_dir_url( __FILE__ );
    
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
      $icons = array('a', 'b', 'v');
      $path_of_icons =  './wp-content/plugins/'.$plugin_folder_name. '/icons'  ;
      $icon_files = array_diff(scandir($path_of_icons), array('.', '..'));

      // if we need to make a custom section for center 
      $longi = "50.15489468904496";
      settype ($longi, "float");

      $lati = "9.629545376420513";
      settype ($lati, "float");

      $map_center_geo = array($longi,$lati );

      
      $info_array= array( 'map_center' => $map_center_geo,
                          'icons_directory'=> $path_of_icons,
                          'icons'=> $icon_files
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
                'category'=>  get_the_category()
            )            
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
    if (is_page('sinngrund-kulturedatenbank-diane')) {
      return plugin_dir_path(__FILE__) . 'main_map_page_diane.php';
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
