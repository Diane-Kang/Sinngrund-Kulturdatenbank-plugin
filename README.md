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

- [x] Little bit organize, for index php

- [x] Make a page with plugin  <u>commited</u>

- [ ] Embed Leaflet a page with shortcode
  
  - [ ] shortcode
  
  - [ ] leaflet
  
  - [ ]  

- [ ] Using geojson generate marker 

- [ ] make a list next to the map 

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
