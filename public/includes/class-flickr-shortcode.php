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
			get_option(Wp_Php_Flickr::WP_FLICKR_API_KEY),get_option(Wp_Php_Flickr::WP_FLICKR_SECRET));
		//$this->wp_php_flickr_core->enableCache(Wp_Php_Flickr_Core::DB,'wp_flickr_cache'); // TODO make table name a setting
	}
	
	function wp_flickr_list_albumx($attrs) {
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
	
	function wp_flickr_list_album() {
		$params = array(
				'api_key'	=> '38b77dc294e8ca6671ab35280c8bd2f3',
				'method'	=> 'flickr.people.findByUsername',
				'username'	=> 'bhaa',
				'format'	=> 'php_serial',
		);
		$encoded_params = array();
		foreach ($params as $k => $v){
			$encoded_params[] = urlencode($k).'='.urlencode($v);
		}
		
		# call the API and decode the response
		$url = "http://api.flickr.com/services/rest/?".implode('&', $encoded_params);
		echo $url;
		
		$rsp = file_get_contents($url);
		
		$rsp_obj = unserialize($rsp);
		echo '<div>'.$rsp_obj.'</div>';
	}
		
	function wp_flickr_list_album_core($attrs) {
		extract(
			shortcode_atts( array(
			'foo' => 'something',
			'bar' => 'something else',
			), $attrs )
		);
	
		// load the default users photo's
		//$person = $this->wp_php_flickr_core->people_findByUsername(get_option(Wp_Php_Flickr::WP_FLICKR_USERNAME));
		//$person = $this->wp_php_flickr_core->people_findByUsername('bhaa');//34896940@N06');
		//error_log('person '.$person);
		//var_dump($person,true);
		//$person = $this->wp_php_flickr_core->people_findByUsername('bhaa');//eoinfegan');//get_option('bhaa_flickr_username'));
		// $person = $this->wp_php_flickr_core->people_findByUsername('tomhealy');//eoinfegan');//get_option('bhaa_flickr_username'));
	
		$photos = $this->wp_php_flickr_core->people_getPublicPhotos('34896940@N06', NULL, NULL, 14, 10);
		var_dump($photos);
		error_log('$photos '.print_r($photos,true));
	
		// Loop through the photos and output the html
		$list = '<div>Flickr</div>';
		foreach ((array)$photos['photos']['photo'] as $photo) {
	
			//error_log($photo);
			//echo '<hr/>';
			$list .= '<a href="'.$this->wp_php_flickr_core->buildPhotoURL($photo,"large").'" rel="prettyPhoto[bhaa]" >';
			$list .= '<img border="0" alt="'.$photo[title].'" src="'.$this->wp_php_flickr->buildPhotoURL($photo, "thumbnail") .'">';
			// http://www.flickr.com/photos/34896940@N06/8542579566/
			//echo "<img border='0' alt='$photo[title]' src=".$wp_php_flickr->buildPhotoURL($photo, "Square") . ">";
			$list .= '</a>';
			$i++;
			// If it reaches the sixth photo, insert a line break
			if ($i % 6 == 0) {
				$list .= '<br>\n';
			}
		}
		error_log('$list '.$list);
		//var_dump($list);
		return $list;
	}
}
?>