# Sinngrund kulturdatenbank plugin

## Github repository setting for Sinngrund

- @ github
  
  - account
  
  - repository url: https://github.com/Dinae-Kang/Sinngrund-Kulturdatenbank-plugin
  
  - repository name: Sinngrund-Kulturdatenbank-plugin

- @ Local computer
  
  - wpLocal - make a local website under plugin directory
    
    `git clone https://github.com/Dinae-Kang/Sinngrund-Kulturdatenbank-plugin.git`
  
  - ```
    git config --global user.name "diane-at-Okto"
    git config --global user.email "diane.kang@page-effect.com"
    ```
  
  - To check user name
    
    `git config --list`
  
  - `~/.../Sinngrund-Kulturdatenbank-plugin$ git init`

## Register Custom  Block/ datenbankblock

- Git commit 
  
  "Register kulturedatenbank Block", 2022-08-05)

## ~~Modity Custom Block, define block template~~

- Set default block(let my custom block show up always first)

- ~~Modify the Custom Block with InnerBlock~~~ <mark>Error appears</mark>
  
  Need to check, If i am dealing with JSX oder JS 
  
  What happend her: Using JS code -> Expected to work under JSX enviroment
  
  ```javascript
       return el( InnerBlocks, {
         template: BLOCKS_TEMPLATE,
         templateLock: false,
     } );
  ```

### JSX for my custom Plugin

- npm init -y 
  
  generate file package.json : For tracking the changes of node module

- Install WP script 
  
  ``$ npm install @wordpress/scripts --save-dev``

- Prepare for JSX
  
  make a directory named : **src**
  
  under src directory make a file named: **index.js**
  
  copy all contents from test.js to index.js 
  
  <mark>.js file under src is for JSX.   js.file outside of src directory is for Javascript </mark>
  
  as well the enqueue script loacation update

## Add meta box to NewPOST commited

index.php

```php
function basic_info_boxes(){
  add_meta_box(   'basic_info', // name
                  __('Basic required data'), //display text 
                  'basic_info_boxes_display_callback', // call back function  
                  'post' );
}
add_action('add_meta_boxes', 'basic_info_boxes');

  /**
* Meta box display callback.
*
* @param WP_Post $post Current post object.
*/


function basic_info_boxes_display_callback( $post ) {
  include plugin_dir_path( __FILE__ ) . './basic_info_box.php';
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
add_action( 'save_post', 'save_basic_info_box' );
```

basic_info_box.php 

```php
function basic_info_boxes(){
  add_meta_box(   'basic_info', // name
                  __('Basic required data'), //display text 
                  'basic_info_boxes_display_callback', // call back function  
                  'post' );
}
add_action('add_meta_boxes', 'basic_info_boxes');

  /**
* Meta box display callback.
*
* @param WP_Post $post Current post object.
*/


function basic_info_boxes_display_callback( $post ) {
  include plugin_dir_path( __FILE__ ) . './basic_info_box.php';
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
add_action( 'save_post', 'save_basic_info_box' );
```

## Todo-List

- [x] Add several post with geocode

- [x] Add option for adresse search : <u>commited</u> 
  
  [javascript - get latitude &amp; longitude as per address given for leaflet - Stack Overflow](https://stackoverflow.com/a/51375706)
  
  - [x] map display erro fix 
    
    ```javascript
    setTimeout(function(){map.invalidateSize();
    },1000); 
    ```
    
    Following Codes are not helpful
    
    ```javascript
    window.onload = function() { 
    
        map.invalidateSize();
        alert("hello");};
    
    window.addEventListener('load', function() {
        alert("hello");
        map.invalidateSize();
        console.log('All assets are loaded');
    })
    jQuery( document ).ready(function() {
       alert("ready?");
    });
    
    window.addEventListener('load', function () {
        map.invalidateSize();
    }) 
    document.addEventListener("DOMContentLoaded", function(event){
        map.invalidateSize();
    });
    
    document.onreadystatechange = function () {
      if (document.readyState == "complete") {
        map.invalidateSize();
      }
    }
    
    jQuery(window).load(function(){
        map.invalidateSize();
      //your code here
    });  
    ```

- [x] Leaflet dependency properly connect
  
  - npm install leaflet 
  
  - enque
    
    ```php
        // for Admin page/ backend dependecy admin_enqueue_scripts
        add_action( 'admin_enqueue_scripts', array($this,'leaflet_dependency'), 10, 1 );
    
      function leaflet_dependency(){
        wp_enqueue_style( 'leaflet-main-css',               plugin_dir_url( __FILE__ ) . '/node_modules/leaflet/dist/leaflet.css' , array(), false, false);
        wp_enqueue_script( 'leaflet-js',                    plugin_dir_url( __FILE__ ) . '/node_modules/leaflet/dist/leaflet.js', array(), false, false );
    ```

- [x] Make a page with plugin  <u>commited</u>  -> nicht aktuell

- [x] Embed leaflet at main map page 
  
  - **For Map Page**
    
    - Make a Page - add `<div id="my-map"></div>`
  
  - Map Page generating: map_modify.js

- [x] Map setting
  
  - Center Point of Map @ index.php ->? later input section in admin page? 
    
              $longi = "50.15489468904496";
              settype ($longi, "float");
        
              $lati = "9.629545376420513";
              settype ($lati, "float"); 
  
  - Marker Icons 
    
        1. Icons directory  index.php 
    
            `$path_of_icons = './wp-content/plugins/'.$plugin_folder_name. '/icons' ;`
    
    2. save to info_json  
       
       ```php ```
             $info_array= array( 'map_center' => $map_center_geo,
                                 'icons_directory'=> $path_of_icons,
                                 'icons'=> $icon_files
                               );
       ```
    
    3. define L.icon @ map_modify.js
       
       ```js
           for (let [key, value] of Object.entries(info_json.icons)) {
               let icon_file = value;
               let option_array = {
                   iconUrl:icons_loc+ '/' +icon_file,
                   iconSize: [20, 20]
               };
               let icon_name = icon_file.replace(".png", "");
               eval('var Icon_' + icon_name + '= L.icon(option_array)');
           }   // variable all defined Icon_brauchtum, Icon_gemeinden, Icon_interest, Icon_kulturelle, Icon_sagen, Icon_sprache,Icon_themen, Icon_star
       ```
    
    4. Assign marker(w custom marker icon ) by categories
       
       ```js
               let category = feature.taxonomy.category[0].name;
               console.log(category)
               let Icon_name = category_icon_array[category];
               //console.log(Icon_name);
               let marker_option = {icon:Icon_name}
       ```
    
    

- [ ] 

- [ ] 

- [ ] make a list next to the map 

## 

## Rest API

1. Metadata 
   
   ```php
       // Rest API /wp-json/wp/v2/posts, add meta data 
       add_action('init', array($this, 'custom_meta_to_api'));
   
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
   ```

2. geojson 
   
   ```php
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
   ```
   
           //$longi = get_post_meta( get_the_ID(), $key = "2-Laengengrad", true);
           $longi = get_post_meta( get_the_ID(), $key = "longitude", true);
           settype ($lenght, "float");
       
           //$lati = get_post_meta( get_the_ID(), $key = "1-Breitengrad", true);
           $lati = get_post_meta( get_the_ID(), $key = "latitude", true);
           settype ($lati, "float");
       
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
               )
       }
       
       $wrapper_array = array(
           "type" => "FeatureCollection",
           "features" => $post_type_query_geojson
       );
       
       return $wrapper_array;
   
     }

```
3. some info json, that js can use it 

```php
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
      $info_array= array( 'text'=>'simple',
                          'number' => '1'
                        );
      return $info_array;
    }
```

## 

## Attribute setting in custom Block - commited

```jsx
wp.blocks.registerBlockType("sinngrund/kulture-datenbank", {
  title: "Kulture Datenbank Beitrag",
  icon: "paperclip",
  category: "common",
  attributes:{
    longitude: {type: "string"},
    latitude: {type: "string"}
  },
  edit: function (props) {

    function updateLong(event){
        props.setAttributes({longitude: event.target.value})
    }

    function updateLat(){
        props.setAttributes({latitude: event.target.value})
    }

    return (
        <div>
            <input type="text" placeholder="longitude" onChange={updateLong} />
            <input type="text" placeholder="latitude" onChange={updateLat} />
        </div>
    )

  },
  save: function (props) {
    return (
        <div>
            <h3>this is front</h3>
            <p>This is longitude value: {props.attributes.longitude}</p>
        </div>
    )
  }
})
```
