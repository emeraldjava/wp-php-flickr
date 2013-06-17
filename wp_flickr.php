<?php
/*
 Plugin Name: WP Flickr
Plugin URI: https://github.com/emeraldjava/wp_flickr
Description: Plugin to display flickr photo's via a shortcode
Version: 2013.06.03
Author: paul.t.oconnell@gmail.com
Author URI: https://github.com/emeraldjava/wp_flickr
*/
if(!function_exists('wp_get_current_user')) {
	include(ABSPATH . "wp-includes/pluggable.php");
}
if(!function_exists('add_options_page')) {
	include(ABSPATH . "wp-admin\includes\plugin.php");
}
require_once('wp_php_flickr.php');

class wp_flickr
{
	const WP_FLICKR = 'wp_flickr';
	const WP_FLICKR_USERNAME = 'wp_flickr_username';
	const WP_FLICKR_USER_ID = 'wp_flickr_user_id';
	const WP_FLICKR_API_KEY = 'wp_flickr_api_key';
	const WP_FLICKR_SECRET = 'wp_flickr_secret';

	private $wp_php_flickr;

	function __construct() {
		add_action('admin_init',array(&$this,'wp_flickr_register_settings'));
		add_options_page( 'WP Flickr Options', 'WP Flickr', 'manage_options', 'wp_flickr_options', array(&$this,'wp_flickr_options'));
	}
		
	function wp_flickr_register_settings() {
		register_setting(wp_flickr::WP_FLICKR,wp_flickr::WP_FLICKR_USERNAME);
		register_setting(wp_flickr::WP_FLICKR,wp_flickr::WP_FLICKR_USER_ID);
		register_setting(wp_flickr::WP_FLICKR,wp_flickr::WP_FLICKR_API_KEY);
		register_setting(wp_flickr::WP_FLICKR,wp_flickr::WP_FLICKR_SECRET);
	}
		
	function wp_flickr_options() {
		if ( !current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		?>
	       	<div class="wrap">
	       	<h2>WP Flickr Options</h2>
	       	<form method="post" action="options.php">
	       	    <?php settings_fields( 'wp_flickr' ); ?>
	       	    <?php do_settings_sections( 'wp_flickr' ); ?>
	       	    <table class="form-table">
	       	        <tr valign="top">
	       	        <th scope="row">wp_flickr_username</th>
	       	        <td><input type="text" name="wp_flickr_username" value="<?php echo get_option('wp_flickr_username'); ?>" /></td>
	       	        </tr>
	       	        <tr valign="top">
	       	        <th scope="row">wp_flickr_user_id</th>
	       	        <td><input type="text" name="wp_flickr_user_id" value="<?php echo get_option('wp_flickr_user_id'); ?>" /></td>
	       	        </tr>
	       	        <tr valign="top">
	       	        <th scope="row">wp_flickr_api_key</th>
	       	        <td><input type="text" name="wp_flickr_api_key" size=40 value="<?php echo get_option('wp_flickr_api_key'); ?>" /></td>
	       	        </tr>
	       	        <tr valign="top">
	       	        <th scope="row">wp_flickr_secret</th>
	       	        <td><input type="text" name="wp_flickr_secret" value="<?php echo get_option('wp_flickr_secret'); ?>" /></td>
	       	        </tr>
	       	    </table>
	       	    <?php submit_button(); ?>
	       	</form>
	    	</div>
	   	<?php 		
	}	

	function wp_flickr_list_album($attrs) {
		extract( 
			shortcode_atts( array(
				'foo' => 'something',
				'bar' => 'something else',
			), $atts ) 
		);
		// initialise the php flickr		
		$this->wp_php_flickr = new wp_php_flickr(get_option(wp_flickr::WP_FLICKR_API_KEY),get_option(wp_flickr::WP_FLICKR_SECRET));
		
		// load the default users photo's
		$person = $this->wp_php_flickr->people_findByUsername(get_option(wp_flickr::WP_FLICKR_USERNAME));
		//$person = $wp_php_flickr->people_findByUsername('34896940@N06');
		//$person = $wp_php_flickr->people_findByUsername('bhaa');//eoinfegan');//get_option('bhaa_flickr_username'));
		// $person = $wp_php_flickr->people_findByUsername('tomhealy');//eoinfegan');//get_option('bhaa_flickr_username'));
		
		$photos = $this->wp_php_flickr->people_getPublicPhotos($person['id'], NULL, NULL, 14);
		//var_dump($photos);
		error_log('photos '.$photos);
		
		// Loop through the photos and output the html
		foreach ((array)$photos['photos']['photo'] as $photo) {
				
			//error_log($photo);
			//echo '<hr/>';
			echo '<a href="'.$this->wp_php_flickr->buildPhotoURL($photo,"large").'" rel="prettyPhoto[bhaa]" >';
			echo "<img border='0' alt='$photo[title]' src=".$this->wp_php_flickr->buildPhotoURL($photo, "thumbnail") . ">";
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
	}
	
	function getWpPhpFlickr() {
		return $this->wp_php_flickr;
	}
}
$wp_flickr = new wp_flickr();
add_shortcode('wp_flickr',array($wp_flickr,'wp_flickr_list_album'));
?>
 