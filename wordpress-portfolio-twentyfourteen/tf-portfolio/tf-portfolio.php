<?php
/*
Plugin Name: Twenty Fourteen Portfolio
Version: 1.0
Description: Adds a portfolio to the Twenty Fourteen theme
Author: Gabriele Romanato
Author URI: http://gabrieleromanato.com
*/

$dir = plugin_dir_path(  __FILE__  );
require_once( $dir . 'framework/TFPortfolio.php' );

if( class_exists( 'TFPortfolio' ) ) {
	$tfPortfolio = new TFPortfolio();
}

