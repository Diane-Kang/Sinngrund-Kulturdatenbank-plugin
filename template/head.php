<?php
/**
 * Header file for Sinngrund-Kulturdatenbank plugin page and post 
 *
 */

?><!DOCTYPE html >

<html style="margin-top: 0 !important;" class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" >
	<link rel="profile" href="https://gmpg.org/xfn/11"> 
	<!-- wp_head() has all our dependency -->
	<?php wp_head(); ?>
    <script type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ) . 'main_page.js'?>"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url( __FILE__ ) . 'main_page.css'?>">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

</head>