<?php
/*
 Plugin Name: WP Flickr Shortcode
Plugin URI: https://github.com/emeraldjava/wp_flickr
Description: Plugin to display flickr photo's via a shortcode
Version: 2013.06.03
Author: paul.t.oconnell@gmail.com
Author URI: https://github.com/emeraldjava/wp_flickr
*/

define( 'PLUGIN_ROOT_DIR' , dirname(__FILE__) );
require_once(PLUGIN_ROOT_DIR.'/wp_php_flickr.php');

class wp_flickr
{
	private $phpFlickr;

	function __construct() {
	}

	function wp_flickr_list_album($attrs) {
		extract( 
			shortcode_atts( array(
				'foo' => 'something',
				'bar' => 'something else',
			), $atts ) 
		);

		return 'wp_flickr_list_album';
	}
}
$wp_flickr = new wp_flickr();
add_shortcode('wp_flickr',array($wp_flickr,'wp_flickr_list_album'));
?>
 