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

		$wp_php_flickr = new wp_php_flickr('','');
		
		$person = $wp_php_flickr->people_findByUsername('bhaa');
		//$person = $wp_php_flickr->people_findByUsername('34896940@N06');
		//$person = $wp_php_flickr->people_findByUsername('bhaa');//eoinfegan');//get_option('bhaa_flickr_username'));
		// $person = $wp_php_flickr->people_findByUsername('tomhealy');//eoinfegan');//get_option('bhaa_flickr_username'));
		
		$photos = $wp_php_flickr->people_getPublicPhotos($person['id'], NULL, NULL, 14);
		//var_dump($photos);
		error_log('photos '.$photos);
		
		// Loop through the photos and output the html
		foreach ((array)$photos['photos']['photo'] as $photo) {
				
			//error_log($photo);
			//echo '<hr/>';
			echo '<a href="'.$wp_php_flickr->buildPhotoURL($photo,"large").'" rel="prettyPhoto[bhaa]" >';
			echo "<img border='0' alt='$photo[title]' src=".$wp_php_flickr->buildPhotoURL($photo, "thumbnail") . ">";
			// http://www.flickr.com/photos/34896940@N06/8542579566/
			//echo "<img border='0' alt='$photo[title]' src=".$wp_php_flickr->buildPhotoURL($photo, "Square") . ">";
			echo "</a>";
			$i++;
			// If it reaches the sixth photo, insert a line break
			if ($i % 6 == 0) {
				echo "<br>\n";
			}
		}
		//var_dump($list);
		return $list;
			
		
		//return 'wp_flickr_list_album';//.$wp_php_flickr->people_findByUsername('bhaa');
	}
}
$wp_flickr = new wp_flickr();
add_shortcode('wp_flickr',array($wp_flickr,'wp_flickr_list_album'));
?>
 