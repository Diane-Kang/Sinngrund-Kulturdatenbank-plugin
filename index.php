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
    add_action('enqueue_block_editor_assets', array($this, 'adminAssets'));
    add_action('add_meta_boxes', array($this, 'basic_info_boxes'));
    add_action( 'save_post', array($this, 'save_basic_info_box' ));
    
    // for Admin page/ backend dependecy admin_enqueue_scripts
    add_action( 'admin_enqueue_scripts', array($this,'leaflet_dependency'), 10, 1 );
    // for Frontend dependency wp_enqueue_scripts

    $target_page_name = 'sinngrund-kulturedatenbank';


    add_action( 'wp_enqueue_scripts', array($this,'leaflet_dependency'), 10, 1 );
    add_action( 'wp_enqueue_scripts', array($this, 'map_page_dependency'), 20, 1 );

      
    // This is for sinngrund-kulturedatenbank-diane
    add_filter('page_template', array($this, 'loadTemplate'), 99);

    //shortcode for beitrag list 
    add_shortcode('show_list_shortcode', array($this, 'show_list_function'));

    add_action('init', function(){

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
  });
  

    register_rest_field( 'post', 'metadata', array(
      'get_callback' => function ( $data ) {
          return get_post_meta( $data['latitude'], '', '' );
      }, ));
  

    //add_action( 'rest_api_init', 'geojson_generate_api');

  
    //register_activation_hook(__FILE__, array($this, 'insert_main_map_page'));
    //register_deactivation_hook( __FILE__, array($this, 'deactivate_plugin'));
    //add_action('admin_head', array($this,'insert_main_map_page')
    //add_filter( 'page_template', array($this,'main_map_page_from_php') );
  
  }


    function wpse_382314_post_filter_data($response, $post) {
        $response->data['post_title'] = '';
        $response->data['post_content'] = '';
    }


  function show_list_function(){
// Things that you want to do.
$message = 'Hello world!'; 
  
// Output needs to be return
return $message;
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
  




}

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
