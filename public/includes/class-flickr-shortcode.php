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
		$this->wp_php_flickr_core->enableCache(Wp_Php_Flickr_Core::DB,'wp_flickr_cache'); // TODO make table name a setting
	}
	
	function registerShortCodes() {
		//add_shortcode('wp_flickr',array($flickr_shortcode,'wp_flickr_list_album'));
		//add_shortcode('wp_flickr_findByUsername',array($flickr_shortcode,'wp_flickr_findByUsername'));
		
		add_shortcode('wp_flickr_photosets_getphotos',array($this,'wp_flickr_photosets_getphotos'));
		add_shortcode('wp_flickr_photosets_getlist',array($this,'wp_flickr_photosets_getlist'));
		
		add_shortcode('wp_flickr_list_album_core',array($this,'wp_flickr_list_album_core'));
		
	}

	/**
	 * Returns all photos in a specific set
	 * @param unknown $atts
	 */
	function wp_flickr_photosets_getphotos($atts) {
		extract(
			shortcode_atts( array(
				'photoset_id' => '72157639926180483'
			), $atts )
		);
		$photoSet = $this->wp_php_flickr_core->photosets_getPhotos($photoset_id,null,null,500,1,null);
		$photos = $photoSet['photoset']['photo'];
		return $this->listPhotos($photos,$photoset_id);
	}
	
	function wp_flickr_photosets_getlist() {
		
		$resp = $this->wp_php_flickr_core->photosets_getList(get_option(Wp_Php_Flickr::WP_FLICKR_USER_ID));
		//var_dump($resp);
		$photosets = $resp['photoset'];//['photoset'];
		//var_dump($photosets);
		$list = '<div id=sets>';
		$i=0;
		foreach ($photosets as $photoset) {
			
			$isLast = 'no';
			if($i % 4 == 0)
				$isLast = 'yes';
			
			$photoUrl = "http://farm" . $photoset['farm'] . ".static.flickr.com/" . $photoset['server'] . "/" . $photoset['primary'] . "_" . $photoset['secret'] . '_t' . ".jpg";
			
			$listx = '[one_fourth last="'.$isLast.'"]'.$photoset['title']['_content'];
			$listx .= '<a href="http://bhaa.ie/photos/?photoset_id='.$photoset['id'].'">';
			$listx .= '<img border="0" alt="'.$photoset['id'].'" src="'.$photoUrl.'">';
			$listx .= '</a>[/one_fourth]';
			$i++;
			
			$list .= do_shortcode($listx);
		}
		$list.='</div>';
		
		return $list;
	}
	
	private function listPhotos($photos,$photoset_id){
		$list = '<div id=photos-'.$photoset_id.'>';
		foreach ($photos as $photo) {
	
			$list .= '<a href="'.$this->wp_php_flickr_core->buildPhotoURL($photo,"large").'" rel="prettyPhoto['.$photoset_id.']">';
			$list .= '<img border="0" alt="'.$photo['title'].'" src="'.$this->wp_php_flickr_core->buildPhotoURL($photo,"thumbnail").'">';
			$list .= '</a>';
		}
		$list.='</div>';
		return $list;
	}
	
	function wp_flickr_findByUsername($attrs) {
		extract(
			shortcode_atts( array(
				'foo' => 'something',
				'bar' => 'something else',
			), $atts )
		);
		
		$params = array(
				'api_key'	=> '38b77dc294e8ca6671ab35280c8bd2f3',
				'method'	=> 'flickr.people.findByUsername',
				'username'	=> 'bhaa',
				'format'	=> 'php_serial'
		);
		$encoded_params = array();
		foreach ($params as $k => $v){
			$encoded_params[] = urlencode($k).'='.urlencode($v);
		}
		
		# call the API and decode the response
		$url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params);
		$html = '<p>URL :'.$url.'</p>';
		
		$rsp = file_get_contents($url);
		$html .= '<p>$rsp = '.$rsp.'</p>';
		
		$request = wp_remote_post($url);
		$response = wp_remote_retrieve_body( $request );
		$html .= '<p>$response = '.$response.'</p>';
		
		$rsp_obj = unserialize($rsp);
		$html .= '<p>'.print_r($rsp_obj,true).'</p>';
		return $html;
	}
	
	function wp_flickr_list_album() {
		
		//user_id' => $user_id, 'safe_search' => $safe_search, 'extras' => $extras, 'per_page' => $per_page, 'page' => $page
		$params = array(
				'api_key'	=> '38b77dc294e8ca6671ab35280c8bd2f3',
				'method'	=> 'flickr.people.getPublicPhotos',
				'per_page'  => 10,
				'page'		=> 1,
				'user_id'	=> '34896940@N06',
				'format'	=> 'php_serial',
		);
		$encoded_params = array();
		foreach ($params as $k => $v){
			$encoded_params[] = urlencode($k).'='.urlencode($v);
		}
		
		# call the API and decode the response
		$url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params);
		$html = '<p>URL :'.$url.'</p>';
		
		$rsp = file_get_contents($url);
		$html .= '<p>$rsp = '.$rsp.'</p>';
		
		$res2 = wp_remote_get($url);
		$html .= '<p>$res2 = '.$res2.'</p>';
		
		$rsp_obj = unserialize($rsp);
		$html .= '<p>'.print_r($rsp_obj,true).'</p>';
		return $html;
	}
		

	
	function wp_flickr_list_album_core($attrs) {
		extract(
			shortcode_atts( array(
			'foo' => 'something',
			'bar' => 'something else',
			), $attrs )
		);
	
		// load the default users photo's
		$person = $this->wp_php_flickr_core->people_findByUsername(get_option(Wp_Php_Flickr::WP_FLICKR_USERNAME));
		var_dump($person);
		//$person = $this->wp_php_flickr_core->people_findByUsername('bhaa');//34896940@N06');
		//error_log('person '.$person);
		//var_dump($person,true);
		//$person = $this->wp_php_flickr_core->people_findByUsername('bhaa');//eoinfegan');//get_option('bhaa_flickr_username'));
		// $person = $this->wp_php_flickr_core->people_findByUsername('tomhealy');//eoinfegan');//get_option('bhaa_flickr_username'));
	
		$photos = $this->wp_php_flickr_core->people_getPublicPhotos('34896940@N06', NULL, NULL, 500, 1);
		//var_dump($photos);
		//error_log('$photos '.sizeof($photos));
	
		// Loop through the photos and output the html
		$list = '<div>Flickr</div>';
		foreach ((array)$photos['photos']['photo'] as $photo) {
	
			//var_dump($photo);
			//error_log($photo);
			//echo '<hr/>';
			$list .= '<a href="'.$this->wp_php_flickr_core->buildPhotoURL($photo,"large").'" rel="prettyPhoto[bhaa]">';
			$list .= '<img border="0" alt="'.$photo[title].'" src="'.$this->wp_php_flickr_core->buildPhotoURL($photo,"thumbnail").'">';
			// http://www.flickr.com/photos/34896940@N06/8542579566/
			//echo "<img border='0' alt='$photo[title]' src=".$wp_php_flickr->buildPhotoURL($photo, "Square") . ">";
			$list .= '</a>';
			$i++;
			// If it reaches the sixth photo, insert a line break
			if ($i % 6 == 0) {
				//$list .= '<br>\n';
			}
		}
		//$list .= '';
		//error_log('$list '.$list);
		//var_dump($list);
		return $list;
	}
}
?>