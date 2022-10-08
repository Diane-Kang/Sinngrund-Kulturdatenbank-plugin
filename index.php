<?php

/*
  Plugin Name: Sinngrund kulturebank plugin 
  Description: Es ist für Sinngrund kulturebank project: last updated at 27.Sep 20:00
  Version: 2.10 
  Author: Page-effect 
  Author-email: Diane.kang@page-effect.com

  npm install leaflet
  npm i leaflet.markercluster
  npm i leaflet.markercluster.layersupport
  npm i leaflet-draw
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class SinngrundKultureBank {

  private $orte_array = array("Burgsinn", "Obersinn", "Aura", "Fellen", "Mittelsinn", "Rieneck");
  function get_orte_array(){
    return $this->orte_array;
  }
// Our map page only working with listed Category
// Icon names need to be shortname + png 
  private $category_shortname_array = array(
                                              "Brauchtum und Veranstaltungen" => "brauchtum",
                                              "Gemeinden"                     => "gemeinden", 
                                              "Kulturelle Sehenswürdigkeiten" => "kulturelle",
                                              "Point of Interest"             => "interest", 
                                              "Sagen + Legenden"              => "sagen",
                                              "Sprache und Dialekt"           => "sprache",
                                              "Thementouren"                  => "route"
                                            );

  function get_category_shortname_array(){
    return $this->category_shortname_array;
  }
  function post_valid_check($category_name, $lati, $longi){
    $valid_category = (array_key_exists($category_name,$this->category_shortname_array) )? 1 : 0 ;
    $valid_geocode = ( (50.00 < $lati &&  $lati < 52.00) && (9.5 < $longi && $longi < 9.8))? 1 :0 ;
    return $valid_category * $valid_geocode;
  }


  function __construct() {

    
    ////--------------Admin dashbard page ----------------    

    //////------------Amdin page style ----------------//
    add_action( 'admin_enqueue_scripts', array($this, 'load_admin_styles' ));
    
    //////------------Admin gallery style --------------
    add_action('admin_init', function() {
      if ( isset( $_GET['mode'] ) && $_GET['mode'] !== 'list' ) {
          wp_redirect(admin_url('upload.php?mode=list'));
          exit;
      } else {
          $_GET['mode'] = 'list';
      }
    }, 100);

    //////------------Amdin Post list columns ----------------//
    /////////------- Add custom column, to see if the post has a right Geocode
    add_filter('manage_posts_columns', array($this, 'custom_posts_table_head'));
    add_action('manage_posts_custom_column', array($this, 'plugin_custom_column'), 10, 2);

    add_filter( 'manage_media_columns', function( $columns ){
                                          $columns['for_gallery'] = 'For gallery?';
                                          return $columns;
                                        },10,2);
                                        
    add_action( 'manage_media_custom_column', function ($name, $post_id){
                                                switch ( $name ){
                                                  case 'for_gallery' :
                                                    $ja_nein = get_post_meta($post_id)['media_for_gallery_box'][0];
                                                    echo $ja_nein? $ja_nein:'ja';
                                                    break;
                                                }
    }, 10, 2 );
    
    
    ////---------For Contributor, admin page
    add_action('admin_init', array($this, 'allow_contributor_uploads'));
    add_action('admin_menu', array($this, 'hide_the_dashboard' ));
    add_action('load-index.php',  function () {
                                    if( !array_intersect( array('administrator'), wp_get_current_user()->roles ) ) {
                                      wp_redirect( admin_url( 'edit.php?post_type=post' ) );
                                    }
                                  }
    );




    ////-----------For login page ---- 
    /////// url changed by Plugin: change-wp-admin-login /wp-login -> /zugang
    add_filter( 'login_headerurl', function (){return home_url();} );
    add_action( 'login_enqueue_scripts',      function (){
                                                wp_enqueue_style( 'custom-login',  plugin_dir_url(__FILE__) . 'template/css/login_page.css' );
                                                //wp_enqueue_script( 'custom-login',  plugin_dir_url(__FILE__) . '/style-login.js' );
                                              }
    );
    

    

    
    //////--------------new Post page ----------------    
    //////////------------ Gutenberg modify----------------// 
    //add_action('enqueue_block_editor_assets', array($this, 'adminAssets'));
  
    //////////------------ Sidebar width----------------//
    add_action('admin_enqueue_scripts', array($this,'toast_enqueue_jquery_ui'));
    add_action('admin_head', array($this, 'toast_resizable_sidebar'));

    add_action( 'init', array($this, 'cc_gutenberg_register_files') );


    //////////------------Meta data for new Post page----------------// 
    add_action('add_meta_boxes', array($this, 'basic_info_boxes'));
    add_action( 'save_post', array($this, 'save_basic_info_box' ));

    add_action('add_meta_boxes', array($this, 'route_input_box'));
    add_action( 'save_post', array($this, 'save_route_input_box' ));


    //////////------------Meta data for Media----------------//// Ob es frontend(galley-page) auftauchen  soll. 
    add_action('add_meta_boxes', array($this, 'media_for_gallery_box'));
    add_filter("attachment_fields_to_save", array($this, 'save_media_for_gallery_box' ), null , 2); //https://www.kevinleary.net/add-custom-meta-fields-media-attachments-wordpress/


    //////////------------orte taxonomy : nonhierarchical   ----------------//
    add_action( 'init', array($this, 'create_ort_taxonomy')); // for attachemnt and post 
    add_action('save_post', array($this,'save_orte_taxonomy')); // save Ort taxonomy

    add_action( 'init', array($this, 'create_fotograf_taxonomy')); // for attachemnt
    add_action('save_post', array($this,'save_fotografen_taxonomy')); // save Ort taxonomy
    //add_action('add_meta_boxes', array($this,'add_orte_meta_box')); // for post    
    //add_action( 'init' , array($this, 'add_orte_tax_to_attachemnt'));
    //add_action( 'init', array($this, 'change_tax_object_label' )); //post_tags->orte
  
    //////////------------Gutenberg – only allow specific blocks   ----------------//
    add_filter( 'allowed_block_types', array($this, 'gute_whitelist_blocks'));

    //////////------------ metabox map reload by clicking expand button----------------//
    add_action('admin_head', array($this, 'reload_metaboxes_map'));
    
    //////////------------Geocode searching for new Post page----------------//
    ////////// for Admin page/ backend dependecy admin_enqueue_scripts, for Frontend dependency wp_enqueue_scripts
    add_action( 'admin_enqueue_scripts', array($this,'leaflet_dependency'), 10, 1 );
    add_action( 'admin_enqueue_scripts', array($this,'metabox_javascript'), 10, 1 );
    
    //////------------add Admin Menu----------------//
    /////////------- setting map page, and map ceter 
    add_action('admin_menu', array($this, 'adminPage'));
    add_action('admin_init', array($this, 'settings'));

    
    //---------------Frontend---------------- 
    add_action( 'wp_enqueue_scripts', array($this,'jquery_dependency'), 20, 1 );
    //////-------------- Leaflet map dependecies---------------------//
    add_action( 'wp_enqueue_scripts', array($this,'leaflet_dependency'), 20, 1 );
    //////-------------- Cookie setting---------------------// 
    add_action('init',  function(){
                           $date = new DateTime("now", new DateTimeZone('Europe/Berlin') );
                          setcookie('KDB_visitor_visit_time',$date->format('Y-m-d H:i:s'), 0);
                        }
    );
    //////-------------- Template javascript---------------------// 
    add_action( 'wp_enqueue_scripts', array($this,'template_javascript'), 20, 1 );
    //////-------------- new template for the main map page and post page---------------------//
    add_filter('page_template', array($this, 'loadTemplate'));
    add_filter('single_template', array($this, 'load_post_Template'));
    //////////-------------- delet header space/ otherwise always makes white blank(38px)top of the page---------------------//
    //add_action('get_header',array($this, 'remove_admin_login_header'));
    add_action('get_header',  function(){
                                remove_action('wp_head', '_admin_bar_bump_cb');
                              });
    //-------- add hidden text(author) to conent for search function ----------//
    add_filter('the_content', array($this, 'add_author_in_content'), 1);
    

    //-------------- Rest API ---------------------//
    ////// Rest API /wp-json/Sinngrund-Kulturdatenbank-plugin/geojson
    add_action( 'rest_api_init', array($this, 'geojson_generate_api'));
    ////// Rest API /wp-json/Sinngrund-Kulturdatenbank-plugin/infojson
    add_action( 'rest_api_init', array($this, 'infojson_generate_api'));
    ////// Rest API /wp-json/Sinngrund-Kulturdatenbank-plugin/mediajson
    add_action( 'rest_api_init', array($this, 'galleryjson_generate_api'));
    ////// Add meta data to default media json 
    add_action('rest_api_init', array($this, 'create_api_posts_meta_field'));

  

    //This is only for development purppose 
    // Rest API /wp-json/wp/v2/posts, add meta data 
    add_action('init', array($this, 'custom_meta_to_api'));



    add_shortcode('debugging_help', array($this,'show_this'));

    //add_action( 'init', array( $this, 'setup_taxonomies' ) );
    //add_filter( 'add_attachment', array( $this, 'wpse_55801_attachment_author') );

  
  }////////////////////////////////////////-----------------------------end of contructor 


  function cc_gutenberg_register_files() {
   
    // script file
    wp_register_script(
        'cc-block-script',
        plugin_dir_url(__FILE__) .'/js/block-script.js', // adjust the path to the JS file
        array( 'wp-blocks', 'wp-edit-post' )
    );
    // register block editor script
    register_block_type( 'cc/ma-block-files', array(
        'editor_script' => 'cc-block-script'
    ) );
    

}



  function hide_the_dashboard(){
    $user = wp_get_current_user();
    $allowed_roles = array('editor', 'administrator');
    //if( !current_user_can( 'manage_options' ) ){
    //if(!is_super_admin()){
    //if ( in_array( 'contributor', $user_roles, true ) ) {
    if( !array_intersect($allowed_roles, $user->roles ) ) {
      remove_menu_page( 'tools.php' );  
      remove_menu_page( 'edit-comments.php' );
      //remove_menu_page('upload.php');
      remove_menu_page('index.php');
      remove_menu_page('profile.php');
    }
  }
 function allow_contributor_uploads() {
    $contributor = get_role("contributor");
    $contributor->add_cap("upload_files");
    }

    

  function wpse_55801_attachment_author( $attachment_ID ) 
{
    $attach = get_post( $attachment_ID );
    $parent = get_post( $attach->post_parent );

    $the_post = array();
    $the_post['ID'] = $attachment_ID;
    $the_post['post_author'] = $parent->post_author;
    show_this(get_post_meta( $attachment_ID , 'category' , true ));
    

    wp_update_post( $the_post );
}

  


  function add_entry_to_orte_taxonomy(){

    $orte_list =$this->get_orte_array();
    $string="";
    foreach ($orte_list as $key => $ort) {
      if(!term_exists($ort, 'orte')){
        wp_insert_term($ort, 'orte');
        $string .=  $ort . "   ";
      }
      else{
        $string .= "already exist";
      } 
    }
    return $string;
  }
  


  function show_this($string) {
    return $string;
    }
    


    //Gutenberg, block javascript 
    function adminAssets() {
      wp_enqueue_script('sinngrund_kulture_bank_block_type', plugin_dir_url(__FILE__) . '/build/index.js', array('wp-blocks', 'wp-element', 'wp-block-editor'));
    }

    
  //////////------------ Sidebar width----------------//
  function toast_enqueue_jquery_ui(){
    wp_enqueue_script( 'jquery-ui-resizable');
  }
  function toast_resizable_sidebar(){ ?>
<style>
.interface-interface-skeleton__sidebar .interface-complementary-area {
  width: 100%;
}

.edit-post-layout:not(.is-sidebar-opened) .interface-interface-skeleton__sidebar {
  display: none;
}

.is-sidebar-opened .interface-interface-skeleton__sidebar {
  width: 350px;
}
</style>
<?php }
  //////////end------------ Sidebar width----------------//

  function reload_metaboxes_map($hook) {
    wp_enqueue_script('my_custom_script', plugin_dir_url(__FILE__) . '/meta_boxes/reload_metaboxes_map.js');
}



  

  function jquery_dependency(){
    wp_enqueue_script('jquery');
  }

  function template_javascript(){
    // COOKIE 

    wp_enqueue_style( 'sidewide_css',                    plugin_dir_url( __FILE__ ) . '/template/css/sidewide.css' , false);
    wp_enqueue_script( 'welcome_popup',                    plugin_dir_url( __FILE__ ) . '/template/welcome_popup.js',  array('jquery'), false, false);

    //if has map
    if (is_page(get_option('sad_mainpage_slug')) || is_single()){
      wp_enqueue_style( 'has_map_css',                    plugin_dir_url( __FILE__ ) . '/template/css/has_map.css' , array(), false, false);
    }

     // if has no map
     if (! is_page(get_option('sad_mainpage_slug')) && ! is_single()){
      wp_enqueue_style( 'no_map_css',                    plugin_dir_url( __FILE__ ) . '/template/css/no_map_side.css' , array(), false, false);
    }

    // if has search
    if (is_page(get_option('sad_mainpage_slug')) || is_page('gallery')){
      wp_enqueue_style( 'search_css',                    plugin_dir_url( __FILE__ ) . '/template/css/search.css' , array(), false, false);
    }

    // if has close X Button
    if (is_page(get_option('sad_mainpage_slug')) || is_page('gallery') || is_single()) {
      wp_enqueue_style( 'x_close_css',                    plugin_dir_url( __FILE__ ) . '/template/css/has_x_close_button.css' , array(), false, false);
    }

    //if has lightbox
    if (is_page('gallery')) {
      wp_enqueue_style( 'lightbox_css',                    plugin_dir_url( __FILE__ ) . '/template/css/has_lightbox.css', false, false);
      wp_enqueue_script( 'kdb-lightbox-js',                  plugin_dir_url( __FILE__ ) . '/template/lightbox.js', array('jquery', 'gallery-js'), false, false);
    }

    if (is_page(get_option('sad_mainpage_slug'))){
      wp_enqueue_script( 'main-page-js',                    plugin_dir_url( __FILE__ ) . '/template/main_page.js',  array(), false, false);
      wp_enqueue_style( 'main-page-css',                    plugin_dir_url( __FILE__ ) . '/template/css/main_page.css' , array(), false, false);
    }
    if (is_single()){
      wp_enqueue_script( 'single-post-js',                  plugin_dir_url( __FILE__ ) . '/template/single_post.js', array(), false, false);
      wp_enqueue_style( 'single-post-css',                    plugin_dir_url( __FILE__ ) . '/template/css/single_post.css' , array(), false, false);
    }

    if (is_page('gallery')){
      wp_enqueue_script( 'gallery-js',                  plugin_dir_url( __FILE__ ) . '/template/gallery_page.js', array(), false, true);
      wp_enqueue_style( 'gallery-css',                    plugin_dir_url( __FILE__ ) . '/template/css/gallery.css' , array(), false, false);
    }

  }

  function load_admin_styles() {
    wp_enqueue_style( 'kulturedatenbank_admin_css', plugin_dir_url( __FILE__ ) . '/template/css/KDB-admin-style-all.css', false, '1.0.0' );
    $user = wp_get_current_user();
    if ( in_array( 'contributor', (array) $user->roles ) ) {
      wp_enqueue_style( 'kulturedatenbank_admin_css_contributor', plugin_dir_url( __FILE__ ) . '/template/css/KDB-admin-style-contributor.css', false, '1.0.0' );
    }

    
    
  }


  function leaflet_dependency(){
    
    wp_enqueue_script( 'leaflet-js',                        plugin_dir_url( __FILE__ ) . '/node_modules/leaflet/dist/leaflet.js', array(), false, false );
    wp_enqueue_script( 'leaflet-marker-cluster-js',         plugin_dir_url( __FILE__ ) . '/node_modules/leaflet.markercluster/dist/leaflet.markercluster.js', array('leaflet-js'), false, false);
    wp_enqueue_script( 'leaflet-marker-cluster-group-js',   plugin_dir_url( __FILE__ ) . '/node_modules/leaflet.markercluster.layersupport/dist/leaflet.markercluster.layersupport.js', array('leaflet-marker-cluster-js'), false, false);
    wp_enqueue_script( 'leaflet-draw-js',                   plugin_dir_url( __FILE__ ) . '/node_modules/leaflet-draw/dist/leaflet.draw.js',array(), false, false);

    
    wp_enqueue_style( 'leaflet-main-css',                   plugin_dir_url( __FILE__ ) . '/node_modules/leaflet/dist/leaflet.css' , array(), false, false);
    wp_enqueue_style( 'leaflet-marker-cluster-css',         plugin_dir_url( __FILE__ ) . '/node_modules/leaflet.markercluster/dist/MarkerCluster.css', array(), false, false);
    wp_enqueue_style( 'leaflet-marker-cluster-default-css', plugin_dir_url( __FILE__ ) . '/node_modules/leaflet.markercluster/dist/MarkerCluster.Default.css', array(), false, false);
    wp_enqueue_style( 'leaflet-draw-css',                   plugin_dir_url( __FILE__ ) . '/node_modules/leaflet-draw/dist/leaflet.draw.css', array(), false, false);

  }

  function metabox_javascript($hook_suffix){
    global $post_type;
    // only call the function at admin post-type:post edit page 
    if( 'post.php' == $hook_suffix || 'post-new.php' == $hook_suffix ) {
      if($post_type == 'post'){
      wp_enqueue_script( 'map-in-box-js',                        plugin_dir_url( __FILE__ ) . '/meta_boxes/map_in_box.js', array(), false, true );
    }}
  }

  // function map_page_dependency(){
  //   if (is_page($target_page_name)){
  //     //wp_enqueue_script( 'map_modify-js',                     plugin_dir_url( __FILE__ ) . '/map_modify.js', array('leaflet-js'), '1.3', true);
  //     //wp_enqueue_style( 'map-app-style-css', plugin_dir_url( __FILE__ ) . '/map-app-style.css', array(), '3.2', false);
  //   }
  // }
  



 
  //////////------------Meta data for new Post page----------------// 
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
        'longitude',
    ];
    foreach ( $fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
     }
  }

  //////////end------------Meta data for new Post page----------------// 




    //////////------------route input box new Post page----------------// 
  /**
  * Meta box display callback.
  *
  * @param WP_Post $post Current post object.
  */
  
  function route_input_box_display_callback( $post ) {
    include plugin_dir_path( __FILE__ ) . '/meta_boxes/route_box.php';
  }
  
  function route_input_box(){
    $user = wp_get_current_user();
    $allowed_roles = array('editor', 'administrator');
   // if( array_intersect($allowed_roles, $user->roles )){ 
      add_meta_box(   'route', // name
                      __('Route'), //display text 
                      array($this, 'route_input_box_display_callback'), // call back function  
                      'post' );
   // }
  }
  
  function save_route_input_box( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
        $post_id = $parent_id;
    }
    $fields = [
      'route'
    ];
    foreach ( $fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            // update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );

            update_post_meta( $post_id, 'route', $_POST[$field] );

        }
     }
  }

  //////////end------------Meta data for new Post page----------------// 



      //////////------------route input box new Post page----------------// 
  /**
  * Meta box display callback.
  *
  * @param WP_Post $post Current post object.
  */
  
  function media_for_gallery_box_display_callback( $post ) {
        // Add an nonce field so we can check for it later.
        //wp_nonce_field( 'wdm_meta_box', 'wdm_meta_box_nonce' );

        /*
         * Use get_post_meta() to retrieve an existing value
         * from the database and use the value for the form.
         */
        $attachment_meta = get_post_meta( $post->ID, '_wp_attachment_meta', true );
        $value_ja_nein = $attachment_meta->media_for_gallery_box;
        //echo var_dump(get_post_meta( $post->ID));
        //echo get_post_meta($post->ID)['media_for_gallery_box'][0];
        
        $saved_value = get_post_meta($post->ID)['media_for_gallery_box'][0]; //my_key is a meta_key. Change it to whatever you want
        if(!$saved_value=='ja' && !$saved_value=='nein'){
          $value = 'ja';
        }
        else $value = $saved_value;
        
        //echo $saved_value;

                  ?>
<label class="selectit"><input type="radio" name="media_for_gallery_box_buttons" value="ja"
    <?php echo $value=='ja'? 'checked' : '' ; ?>>
  Ja</label>
</br>
<label class="selectit"><input type="radio" name="media_for_gallery_box_buttons" value="nein"
    <?php echo $value=='nein'? 'checked' : '' ; ?>>
  Nein</label>
<?php
          }
  
  function media_for_gallery_box(){
    $user = wp_get_current_user();
    $allowed_roles = array('editor', 'administrator');
   // if( array_intersect($allowed_roles, $user->roles )){ 
      add_meta_box(   'media_for_gallery_box', // name
                      __('For Gallery?'), //display text 
                      array($this, 'media_for_gallery_box_display_callback'), // call back function  
                      'attachment',
                      'side',         // Context
                      'low',         // Priority
                   );
   // }
  }
  
  function save_media_for_gallery_box( $post, $attachment ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    // if ( $parent_id = wp_is_post_revision( $post_id ) ) {
    //     $post_id = $parent_id;
    // }
    $fields = [
      'media_for_gallery_box_buttons'
    ];
    //foreach ( $fields as $field ) {
       // if ( array_key_exists( $field, $_POST ) ) {

            // update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );

            update_post_meta( $post['ID'], 'media_for_gallery_box', $_POST['media_for_gallery_box_buttons'] );

        //}
     //}
     return $post;
  }

  //////////end------------Meta data for new Post page----------------// 












  function setup_taxonomies($tax_id, $post_type, $tax_display_name, $tax_display_name_pl) {

    $new_taxonomies = array();

    // Tags
    $labels = array(
        'name'              => _x( $tax_display_name_pl, 'taxonomy general name'),
        'singular_name'     => _x( $tax_display_name, 'taxonomy singular name'),
        'search_items'      => __( 'Search' . $tax_display_name_pl),
        'all_items'         => __( 'All' . $tax_display_name_pl),
        'parent_item'       => __( 'Parent' .  $tax_display_name),
        'parent_item_colon' => __( 'Parent' . $tax_display_name  . ':'),
        'edit_item'         => __( 'Edit ' . $tax_display_name .''), 
        'update_item'       => __( 'Update ' . $tax_display_name .''),
        'add_new_item'      => __( 'Add New ' . $tax_display_name .''),
        'new_item_name'     => __( 'New ' . $tax_display_name .' Name'),
        'menu_name'         => __( $tax_display_name_pl),
    );

    $args =  array(
        'hierarchical' => False,
        'labels'       => $labels,
        'show_ui'      => TRUE,
        'show_admin_column' => TRUE,
        'query_var'    => TRUE,
        'rewrite'      => TRUE,
        'show_in_rest' => TRUE, // for meta box side
    );

    register_taxonomy( $tax_id, $post_type, $args);
  }


  function create_fotograf_taxonomy(){
    $this->setup_taxonomies('fotograf','attachment', 'Fotograf', 'Fotografen');
  }

  function save_fotografen_taxonomy($post_id){
    if ( isset( $_REQUEST['fotograf'] ) ) 
      wp_set_object_terms($post_id, $_POST['fotograf'], 'fotograf');
  }

  function create_ort_taxonomy(){
 
    // Labels part for the GUI
     
    $labels = array(
      'name' => _x( 'Orte', 'taxonomy general name' ),
      'singular_name' => _x( 'Ort', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Orte' ),
      'all_items' => __( 'All Orte' ),
      'parent_item' => __( 'Parent Ort' ),
      'parent_item_colon' => __( 'Parent Ort:' ),
      'edit_item' => __( 'Edit Ort' ), 
      'update_item' => __( 'Update Ort' ),
      'add_new_item' => __( 'Add New Ort' ),
      'new_item_name' => __( 'New Ort Name' ),
      'menu_name' => __( 'Orte' ),
    );    
   

    $args = array(
      'hierarchical' => TRUE, // Categorie like 
      'labels'       => $labels,
      'show_ui'      => TRUE,
      'query_var'    => TRUE,
      'rewrite'      => TRUE,
      'show_in_rest' => TRUE, // for meta box side
      //'public' => FALSE, // not showing modal 
    );

    $taxonomies = array(
      'taxonomy'  => 'orte',
      'post_type' => array('attachment','post'),
      'args'      => $args
    );

    // Now register the non-hierarchical taxonomy like tag
     
    register_taxonomy($taxonomies['taxonomy'],$taxonomies['post_type'],$taxonomies['args']);

  }

  function add_orte_meta_box(){
	  add_meta_box( 'taxonomy_box', __('Orte'), array($this,'fill_custom_meta_box_content'), 'post' ,'side');
  }

  function fill_custom_meta_box_content( $post ) {
    $terms = get_terms( array(
      'taxonomy' => 'orte',
      'hide_empty' => false // Retrieve all terms
    ));
  
    // We assume that there is a single category
    $currentTaxonomyValues = get_the_terms($post->ID, 'orte');
    $currentTaxonomytermids = array();
    if($currentTaxonomyValues){foreach($currentTaxonomyValues as $value)  array_push($currentTaxonomytermids, $value->term_id);}
      

  ?>
<p>Choose taxonomy value</p>
<p>
  <?php foreach($terms as $term): ?>
  <input type="checkbox" name="orte[]" value="<?php echo $term->name;?>"
    <?php if(in_array($term->term_id,$currentTaxonomytermids)) echo "checked"; ?>>
  <?php echo $term->name; ?>
  </input><br />
  <?php endforeach; ?>
</p>
<?php
  }

  function save_orte_taxonomy($post_id){
    if ( isset( $_REQUEST['orte'] ) ) 
      wp_set_object_terms($post_id, $_POST['orte'], 'orte');
  }


  function gute_whitelist_blocks( $allowed_block_types) {
    $user = wp_get_current_user();
    $allowed_roles = array('editor', 'administrator');
    $post = get_post();
    
    // if(get_current_user_id != 1){
    //   return TURE;
    // }
    // else 
    if ($post->post_type == 'post'){ 
      return array(
        'core/paragraph',
        'core/heading',
        'core/quote',
        'core/list',
        'core/image',
        'core/audio',
        'core/video',
        'core/embed',  );
    }
    // if( !array_intersect($allowed_roles, $user->roles )){    
    //   return array(
    //       'core/paragraph',
    //       'core/heading',
    //       'core/quote',
    //       'core/list',
    //       'core/image',
    //       'core/audio',
    //       'core/video',  );
    //} 
    else return TRUE;
}






  /////////------- setting map page slug, and map ceter   //sad: Sinngrund Allianz Datenbank
  // 1. make admin menu(visually): function adminPage-add_option_page(name, display name, option/permision, slug, pagehtml)
  // 2. register value: function settings()
  function adminPage() {
    add_options_page('Sinngrund Datenbank Setting', 'Sinngrund Ailianz', 'manage_options', 'sinngrund-datenbank-setting-page', array($this, 'settingHTML'));
  }

  function settingHTML() { ?>
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

  function settings() {
    add_settings_section('sad_first_section', null, null, 'sinngrund-datenbank-setting-page');

    add_settings_field('sad_mainpage_slug', 'Set the main map page', array($this, 'slug_inputHTML'), 'sinngrund-datenbank-setting-page', 'sad_first_section');
    register_setting('singrundallianzplugin', 'sad_mainpage_slug', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'map_page'));

    add_settings_section('sad_second_section', null, null, 'sinngrund-datenbank-setting-page');

    add_settings_field('sad_map_center_point', 'Map Center Point', array($this, 'map_center_point_HTML'), 'sinngrund-datenbank-setting-page', 'sad_second_section');
    register_setting('singrundallianzplugin', 'sad_map_center_point', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'map_page'));
  }

  function slug_inputHTML() { ?>
<p>Current map Page : <?php echo esc_attr(get_option('sad_mainpage_slug')) ?> </p>
<select name="sad_mainpage_slug" id="">
  <?php
      $pages = get_pages();
      foreach($pages as $page) {
        $string = '<option value="' . $page->post_name . '"';
        if ($page->post_name == get_option('sad_mainpage_slug')){
          $string .= 'selected';
        }
        $string .= '>' . $page->post_title; 

        if ($page->post_name == get_post(get_option('page_on_front'))->post_name){
          $string .= '<b>:Startseite</b>';
        }          
        $string .= '</option>';
        echo $string;}
      ?>
</select>
<?php 
  }

  function map_center_point_HTML() { ?>
<p>input need to be seperated by comma(,)</p>
<p>longitude, latitude </p>
<p> default : 50.15489468904496, 9.629545376420513</p>
<input type="text" name="sad_map_center_point" size="50"
  value="<?php echo esc_attr(get_option('sad_map_center_point')) ?>">
<?php }

  function sanitize_slug($input) {
    $default_slug = 'sinngrund-kulturedatenbank-diane';//default slug here
    $input = sanitize_title($input);
    if ($input == esc_attr(get_option('sad_mainpage_slug'))){ // same as existed value, then no changes 
      return $input;
    }
    else if ($this->the_slug_exists($input) && ($input != $default_slug)) { //exist but same as default slug, then set to defult slug
      $message = $input . ': this is already exsited as slug. Map page is now setted with default slug:'. $default_slug;
      add_settings_error('sad_mainpage_slug', 'sad_mainpage_slug_error', $message);
      return $default_slug;
    }
    else if ($input != $default_slug) { // not exist and not default, then make a new page with input slug 
        $my_post = array(
          'post_title'    => $input,
          'post_name'     => sanitize_title($input),
          'post_status'   => 'publish',
          'post_author'   => 1,
          'post_type'     => 'page',
        );
        wp_insert_post( $my_post );
    }
    return $input;
  }//end f sanitize_slug

  function the_slug_exists($post_slug_text) {
    global $wpdb;
    if($wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_name = '" . $post_slug_text . "'", 'ARRAY_A')) {
        return true;
    } else {
        return false;
    }
  }
  /////////end------- setting map page slug, and map ceter   


  /////////------- Add custom column, to see if the post has a right Geocode
  function custom_posts_table_head( $columns ) {
    $columns['geocode'] = 'geocode';
    $columns['valid'] = 'valid';
    return $columns;
  }

  
  function plugin_custom_column($name, $post_id) {
    switch ($name) {
        case 'geocode':
            $geocode .= get_post_meta( $post_id , 'latitude' , true ) .'<br>' . get_post_meta( $post_id , 'longitude' , true );
            echo $geocode;
            break;
        case 'valid':
            //if (!array_key_exists($category,$category_array) || empty(get_post_meta( $post_id , 'latitude' , true )) || empty(get_post_meta( $post_id , 'longitude' , true ) )){
            $lati = get_post_meta( $post_id , 'latitude' , true );
            $longi = get_post_meta( $post_id , 'longitude' , true );
            $category_name = get_the_category( )[0]->name;
            if($this->post_valid_check($category_name , $lati, $longi)){
              echo "O";
            }
            else echo "X: Error mit Geocode oder/and Kategory";
    }
  }
  /////////end------- Add custom column, to see if the post has a right Geocode



  //////-------------- new template for the main map page and post page---------------------//
  function loadTemplate($template) {
    if (is_page(get_option('sad_mainpage_slug'))) {
      return plugin_dir_path(__FILE__) . '/template/main_page.php';
    }
    if (is_page('gallery')) {
      return plugin_dir_path(__FILE__) . '/template/gallery_page.php';
    }
    return $template;
  }

  function load_post_Template($template) {
    if (is_single()) {
      return plugin_dir_path(__FILE__) . '/template/single_post.php';
    }
    return $template;
  }
  //////end-------------- new template for the main map page and post page---------------------//


  function add_author_in_content ($content) {
  //   if ('post' !== get_post_type()) {
  //     return $content;
  // }
    Global $post;
    $author = get_the_author_meta('display_name', $post->post_author); 
    $date = get_the_date('d.m.Y');
    $begincontent = '<p> Eintrag erstellt von ' .  $author . ' am ' . $date . '.</p>';
    $fullcontent = $begincontent . $content;
    return $fullcontent;

}



  // //////////-------------- delet header space/ otherwise always makes white blank(38px)top of the page---------------------//
  // function remove_admin_login_header() {
  //   remove_action('wp_head', '_admin_bar_bump_cb');
  // }




  // function show_list_function(){
    
  //   $the_query = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 400 ) );
  //   $string = ""; // html string
    
  //   $category_icon_array = array(
  //     "Brauchtum und Veranstaltungen" => "brauchtum.png",
  //     "Gemeinden"                     => "gemeinden.png", 
  //     "Kulturelle Sehenswürdigkeiten" => "kulturelle.png",
  //     "Point of Interest"             => "interest.png", 
  //     "Sagen + Legenden"              => "sagen.png",
  //     "Sprache und Dialekt"           => "sprache.png",
  //     "Thementouren"                  => "themen.png"
  //   );

  //   //$string .= '<p>'. $category_icon_array["Gemeinden"] .'</p>';

  //   // Entry List 
  //   $string .= '<div class="datenbank_list_block">';
  //   if  ( $the_query->have_posts() ) {
  //     $string .= '<div class="datenbank_list">';
  //     while ( $the_query->have_posts()) {
  //       $the_query->the_post();
  //       $category_slug = get_the_category( )[0]->slug;
  //       $category_name = get_the_category( )[0]->name;
  //       $category_icon = $category_icon_array[$category_name];
  //       $category_icon_src = '/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/icons/'. $category_icon;
  //       $url = '/wp-content/plugins/Sinngrund-Kulturdatenbank-plugin/icons/star.png';
  //       $string .=' <div class="datenbank_single_entry">
  //                     <div class="entry_title"><h4> this is post title:' . get_the_title() .'</h4></div>
  //                     <div class="entry_category ' .$category_icon. '"><img style="height: 20px; width: 20px; margin-right: 2px;"  src="'.$category_icon_src.'"/>'.$category_name.'</div>
  //                   </div>'; //closing class datenbank_single_entry
    
  //     }
  //     $string .= '</div>'; // closeing class datenbank_list
    
  //   } else $string = '<h3>Aktuell gibt es keine eingetragenen Unternehmen</h3>';   
        
  //   /* Restore original Post Data*/
  //   wp_reset_postdata();
    
  //   $string .= '</div>'; // closeing class datenbank list block 
  //   return $string;   
  // }


  
  function create_api_posts_meta_field() {

    // register_rest_field ( 'name-of-post-type', 'name-of-field-to-return', array-of-callbacks-and-schema() )
    register_rest_field( 'attachment', 'post-meta-fields', array(
           'get_callback'    => array($this,'get_post_meta_for_api'),
           'schema'          => null,
        )
    );
  }

  function get_post_meta_for_api( $object ) {
    
    //get the id of the post object array
    $post_id = $object['id'];
    $terms = get_the_terms($post_id, 'orte');
    // foreach ($terms as $term){
    //   $ortlist .= $term->name;
    // }  
    $ortlist = join(', ', wp_list_pluck($terms, 'name'));
    //return the post meta
    return $ortlist;
  }
  
  //////-------------------------------- Rest API /wp-json/Sinngrund-Kulturdatenbank-plugin/infojson
  function infojson_generate_api() {
    $plugin_folder_name = reset(explode('/', str_replace(WP_PLUGIN_DIR . '/', '', __DIR__)));
    register_rest_route( $plugin_folder_name, '/infojson/', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => array($this,'infojson_generator')
    ) );
  }
    
  public function infojson_generator() {
    //$info_array=array();
    $plugin_folder_name = reset(explode('/', str_replace(WP_PLUGIN_DIR . '/', '', __DIR__)));
    $path_of_icons =  './wp-content/plugins/'.$plugin_folder_name. '/icons'  ;
    $icon_files = array_diff(scandir($path_of_icons), array('.', '..'));

    // if we need to make a custom section for center 
    // $longi = "50.15489468904496";
    // settype ($longi, "float");
    // $lati = "9.629545376420513";
    // settype ($lati, "float");

    $map_center_geo = array_map("floatval", explode(',', esc_attr(get_option('sad_map_center_point'))));
    $myarray = $this->category_shortname_array;
          
    $info_array= array( 'map_center' => $map_center_geo,
                        'icons_directory'=> $path_of_icons,
                        //'icons'=> $icon_files,
                        'icons'=> $myarray
                        //'geo_code'=>$geo_code
                      );
    return $info_array;
  }
  //////end-------------------------------- Rest API /wp-json/Sinngrund-Kulturdatenbank-plugin/infojson

  /////-------------------------------- Rest API /wp-json/Sinngrund-Kulturdatenbank-plugin/galleryjson
  function galleryjson_generate_api() {
    $plugin_folder_name = reset(explode('/', str_replace(WP_PLUGIN_DIR . '/', '', __DIR__)));
    register_rest_route( $plugin_folder_name, '/galleryjson/', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => array($this,'galleryjson_generator')
    ) );
  }

  function galleryjson_generator($data) {
    $post_type_query = new WP_Query(array(
        'post_status' => 'any', 
        'post_type' => 'attachment',
        's' => sanitize_text_field($data['search']) // Search only title 
        //'s' => $query,
    ));

    $post_type_query_galleryjson = array();

    while ($post_type_query->have_posts()) {
      $post_type_query->the_post();
      $post_id = get_the_ID();
      $check_for_gallery = get_post_meta($post_id)['media_for_gallery_box'][0];
      if($check_for_gallery != 'nein'){        
        array_push($post_type_query_galleryjson, array(
          'id'    => get_the_ID(),
          'date'  => get_the_date(),
          'source_url' => wp_get_attachment_url(),
          // caption : this is bildtitel
          'caption' => wp_get_attachment_caption() ? wp_get_attachment_caption() : 'Bildtitel nicht eingegeben',
          'title' =>  get_the_title(), // file titel, name 
          'author' => get_the_author(),
          //'orte' => get_the_terms( $post_id, 'orte'),
          'orte_tags_text'  => join(', ', wp_list_pluck(get_the_terms( get_the_ID(), 'orte'), 'name')),
          //'fotograf' => get_the_terms( $post_id, 'fotograf') ,
          'fotograf_tags_text'  => join(', ', wp_list_pluck(get_the_terms( get_the_ID(), 'fotograf'), 'name')),


          // 'reference'=> get_the_category()
        ));
      }
    }//while end 

    return $post_type_query_galleryjson;
  }


  /////-------------------------------- Rest API /wp-json/Sinngrund-Kulturdatenbank-plugin/geojson
  function geojson_generate_api() {
    $plugin_folder_name = reset(explode('/', str_replace(WP_PLUGIN_DIR . '/', '', __DIR__)));
    register_rest_route( $plugin_folder_name, '/geojson/', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => array($this,'geojson_generator')
    ) );
  }

  function geojson_generator($data) {
    $post_type_query = new WP_Query(array(
        'post_type' => 'post',
        's' => sanitize_text_field($data['term'])
    ));

    $post_type_query_geojson = array();

    while ($post_type_query->have_posts()) {
      $post_type_query->the_post();


      $lati = get_post_meta( get_the_ID(), $key = "latitude", true);
      settype ($lati, "float");
      $longi = get_post_meta( get_the_ID(), $key = "longitude", true);
      settype ($longi, "float");

      $category_shortname_array = $this->category_shortname_array;
      $category_name = get_the_category()[0]->name;
//      console.log(has_excerpt(), the_excerpt());
      // $preview_text = has_excerpt() ? the_excerpt() : "";
      if($this->post_valid_check($category_name, $lati, $longi)){
        array_push($post_type_query_geojson, array(
          'type'=> 'Feature',
          'id' => get_the_ID(),
          'properties'=>array(
              'name'    => get_the_title(),
              'post_id' => get_the_ID(),
              'url'     => get_permalink(), 
              'date'    => get_the_date(),
              'author'  => get_the_author(),
              'thumbnail_url' => get_the_post_thumbnail_url(),
              'excerpt' => has_excerpt() ? get_the_excerpt() : "",
          ),
          'taxonomy'=>array(
              'category'=>array(
                  'term_id'   => get_the_category()[0]->term_id,
                  'name'      => get_the_category()[0]->name,
                  'slug'      => get_the_category()[0]->slug, 
                  'shortname' => $category_shortname_array[get_the_category()[0]->name],
                  'icon_name' => $category_shortname_array[get_the_category()[0]->name].'.svg'
              ) 
          ),
          'geometry'=> array(
            'type'=> 'Point',
            'coordinates' =>  array($longi,$lati)
          ),
          'route'=> get_post_meta( get_the_ID(), $key = "route"),
          'meta_list'=> get_post_meta(get_the_ID()),
          // 'reference'=> get_the_category()
        ));
      }// if end
    }//while end 

    $wrapper_array = array(
        "type" => "FeatureCollection",
        "features" => $post_type_query_geojson
    );
    return $wrapper_array;
  }
  /////end-------------------------------- Rest API /wp-json/Sinngrund-Kulturdatenbank-plugin/geojson



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