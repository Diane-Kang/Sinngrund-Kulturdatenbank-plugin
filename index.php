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


    //register_activation_hook(__FILE__, array($this, 'insert_main_map_page'));
    //register_deactivation_hook( __FILE__, array($this, 'deactivate_plugin'));
    //add_action('admin_head', array($this,'insert_main_map_page')
    //add_filter( 'page_template', array($this,'main_map_page_from_php') );
  
    $target_page_name = 'sinngrund-kulturedatenbank';


      add_action( 'wp_enqueue_scripts', array($this,'leaflet_dependency'), 10, 1 );
      add_action( 'wp_enqueue_scripts', array($this, 'map_page_dependency'), 20, 1 );

      add_filter('page_template', array($this, 'loadTemplate'), 99);

      //add_shortcode('loadTemplate', 'kulturedatenbank_map_page_shortcode');



  
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
  
  function adminAssets() {
    wp_enqueue_script('sinngrund_kulture_bank_block_type', plugin_dir_url(__FILE__) . '/build/index.js', array('wp-blocks', 'wp-element', 'wp-block-editor'));
    
  }


  

  // function load_kulturedatenbank_map_page(){
  //   add_filter('template_include', array($this, 'loadTemplate'), 99);
  // }

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
        'latitude_longitude',
        'latitude',
        'longitude',
        'popuptext'
    ];
    foreach ( $fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
     }
  }

  

  function insert_main_map_page() {
    $check_map_page = $this->get_page_by_slug('sinngrund_Kulturedatenbank'); 
    echo $check_map_page;
      if (!$check_map_page){
        $main_map_page = array(
          'post_type' => 'page',
          'post_name' => 'sinngrund_Kulturedatenbank',
          'post_title' => 'Sinngrund Kulturedatenbank',
          'post_status' => 'publish',
        );

        wp_insert_post($main_map_page);
      }
    }
  
  function get_page_by_slug($slug) {
      if ($pages = get_pages())
          foreach ($pages as $page)
              if ($slug === $page->post_name) return $page;
      return false;
  } // function get_page_by_slug




  function main_map_page_from_php( $page_template ){
      if ( is_page( 'Sinngrund_Kulturedatenbank' ) ) {

          $page_template = plugin_dir_path( __FILE__ ) . '/kulturedatenbank.php';
      }
      return $page_template;
  }
  
  function deactivate_plugin() {
    $page = get_page_by_path('sinngrund_Kulturedatenbank', '', 'page' );
    wp_delete_post($page->ID);
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
