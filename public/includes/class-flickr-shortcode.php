<?php

class Flickr_Shortcode {
	
	// the singleton
	protected static $instance = null;
	
	private $wp_php_flickr_core = null;
	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	private function __construct() {
		require_once( plugin_dir_path( __FILE__ ) . '//wp-php-flickr-core.php' );
		$this->wp_php_flickr_core = new Wp_Php_Flickr_Core(
			get_option(wp_flickr::WP_FLICKR_API_KEY),get_option(wp_flickr::WP_FLICKR_SECRET));
		$this->wp_php_flickr_core->enableCache(Wp_Php_Flickr::DB,'wp_flickr_cache'); // TODO make table name a setting
	}
	
	function wp_flickr_list_album($attrs) {
		extract(
			shortcode_atts( array(
				'foo' => 'something',
				'bar' => 'something else',
			), $atts )
		);
		return sprintf('%s plugin settings : %s %s',
			wp_flickr::get_instance()->get_plugin_slug(),
			//get_plugin_data('wp_flickr'),
			get_option(wp_flickr::WP_FLICKR_API_KEY),
			get_option(wp_flickr::WP_FLICKR_SECRET));	
	}
		
	function wp_flickr_list_albumx($attrs) {
		extract(
			shortcode_atts( array(
			'foo' => 'something',
			'bar' => 'something else',
			), $atts )
		);
	
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
}
?>