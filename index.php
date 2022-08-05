<?php

/*
  Plugin Name: Sinngrund kulturebank plugin 
  Description: Es ist für Sinngrund kulturebank project
  Version: 0.0
  Author: Diane

*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class SinngrundKultureBank {
  function __construct() {
    add_action('enqueue_block_editor_assets', array($this, 'adminAssets'));
  }

  function adminAssets() {
    wp_enqueue_script('sinngrund_kulture_bank_block_type', plugin_dir_url(__FILE__) . 'test.js', array('wp-blocks', 'wp-element', 'wp-block-editor'));
  }
}

$sinngrundKultureBank = new SinngrundKultureBank();


function slug_post_type_template() {
	$page_type_object = get_post_type_object( 'post' );
  $page_type_object->template = [
		[[ 'sinngrund/kulture-datenbank' ], 
    ],
	];
  //$post_type_object->template_lock = 'all'; // lock the template on the UI so that the blocks presented cannot be manipulated
}
add_action( 'init', 'slug_post_type_template' );