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
		
		//add_shortcode('wp_flickr_photosets_getphotos',array($this,'wp_flickr_photosets_getphotos'));
		add_shortcode('wp_flickr_photosets_getlist',array($this,'wp_flickr_photosets_getlist'));
		
		//add_shortcode('wp_flickr_list_album_core',array($this,'wp_flickr_list_album_core'));
		
	}

	/**
	 * Returns all photos in a specific set
	 * @param unknown $atts
	 */
	function xwp_flickr_photosets_getphotos($atts) {
		extract(
			shortcode_atts( array(
				'photoset_id' => '72157639926180483'
			), $atts )
		);
		$photoSet = $this->wp_php_flickr_core->photosets_getPhotos($photoset_id,null,null,500,1,null);
		$photos = $photoSet['photoset']['photo'];
		return $this->listPhotos($photos,$photoset_id);
	}
	
	function wp_flickr_photosets_getlist($attrs) {
		
		$a = shortcode_atts( array(
			'photosetid' => get_query_var( 'photosetid' )
		), $attrs );
		//error_log('shortcode attr :'.$a['photosetid'].':');
		
		$photosetid = $a['photosetid'];
		//$photosetid = get_query_var( 'photosetid' );
		error_log('$photosetid :'.$photosetid.':');
		
		if(empty($photosetid)){
		
			$resp = $this->wp_php_flickr_core->photosets_getList(get_option(Wp_Php_Flickr::WP_FLICKR_USER_ID));
			$photosets = $resp['photoset'];
			$list = '<div id=sets>';
			$i=3;
			foreach ($photosets as $photoset) {
				
				$isFirst = '';
				if($i % 3 == 0)
					$isFirst = 'first';
				
				$photoUrl = "http://farm" . $photoset['farm'] . ".static.flickr.com/" . $photoset['server'] . "/" . $photoset['primary'] . "_" . $photoset['secret'] . '_m' . ".jpg";
				
				$listx .= '[av_one_third '.$isFirst.' ]';
				$listx .= '[av_notification title="" color="custom" border="solid" custom_bg="#012c52" custom_font="#ffffff" size="normal"]';
				$listx .= $photoset['title']['_content'].'[/av_notification]';

				$link = home_url().'/photos/?photosetid='.$photoset['id'];

				$listx .= sprintf("[av_image src='%s' attachment_size='full' align='center' 
					animation='no-animation' link='manually,%s' target='' styling='' caption='' font_size='' appearance=''][/av_image]",
					$photoUrl,$link
				);

				$listx .= '[/av_one_third]';
				$i++;
			}

			$list .= do_shortcode($listx);
			$list.='</div>';		
			return $list;
		}
		else
		{
			// handle a specific photoset
			return $this->wp_flickr_photosets_getphotos($photosetid);
		}
	}
	
	function wp_flickr_photosets_getphotos($photosetid) {
		$photoSet = $this->wp_php_flickr_core->photosets_getPhotos($photosetid,null,null,500,1,null);
		$photos = $photoSet['photoset']['photo'];
		return $this->listPhotos($photos,$photoset_id);
	}
	
	private function listPhotos($photos,$photoset_id) {

		$list = do_shortcode(sprintf('[av_notification title="" color="custom" border="solid" custom_bg="#012c52" 
			custom_font="#ffffff" size="normal"]%s[/av_notification]','Photos'));

		$list .= do_shortcode(sprintf('[av_textblock ]<p style="text-align: center;"><span style="color: #0000ff;">%s</span></p>[/av_textblock]',
			'Click on the image below to list throught the full set of photographs from the race.'));

		$list .= '<div id=photos-'.$photoset_id.'>';

		$list .= '<a href="'.$this->wp_php_flickr_core->buildPhotoURL($photos[5],"large").'" rel="prettyPhoto['.$photoset_id.']">';
		$list .= '<img border="0" alt="'.$photo['title'].'" src="'.$this->wp_php_flickr_core->buildPhotoURL($photos[0],"large").'">';
		$list .= '</a>';


		foreach ($photos as $photo) {
	
			$list .= '<a href="'.$this->wp_php_flickr_core->buildPhotoURL($photo,"large").'" rel="prettyPhoto['.$photoset_id.']">';
			//$list .= '<img border="0" alt="'.$photo['title'].'" src="'.$this->wp_php_flickr_core->buildPhotoURL($photo,"thumbnail").'">';
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