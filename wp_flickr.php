<?php
/*
 Plugin Name: WP Flickr
Plugin URI: https://github.com/emeraldjava/wp_flickr
Description: Plugin to display flickr photo's via a shortcode
Version: 2013.06.03
Author: paul.t.oconnell@gmail.com
Author URI: https://github.com/emeraldjava/wp_flickr
*/
require_once('wp_php_flickr.php');

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

		//$wp_php_flickr = new wp_php_flickr('','');
		return 'wp_flickr_list_album';//.$wp_php_flickr->people_findByUsername('bhaa');
	}
}
$wp_flickr = new wp_flickr();
add_shortcode('wp_flickr',array($wp_flickr,'wp_flickr_list_album'));
?>
 