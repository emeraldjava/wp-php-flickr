<?php

define('WP_INSTALL_DIR','C:\oconnellp\wamp\wordpress');
define('PLUGIN_ROOT_DIR',dirname(__FILE__).'./../');

require_once(PLUGIN_ROOT_DIR.'/flickr/BhaaFlickr.php');
require_once(PLUGIN_ROOT_DIR.'/flickr/phpFlickr.php');

/*
require_once( WP_INSTALL_DIR.'\wp-config.php');
require_once( WP_INSTALL_DIR.'\wp-load.php');
require_once( WP_INSTALL_DIR.'\wp-includes\wp-db.php');
require_once( WP_INSTALL_DIR . '/wp-content/plugins/posts-to-posts/core/api.php' );
require_once( PLUGIN_ROOT_DIR.'/bootstrap.php');
*/


/**
 * 
 * Key: 38b77dc294e8ca6671ab35280c8bd2f3
Secret: ecd5f3ac20e6b7b7


 * "13212","bhaa_flickr_username","bhaa","yes"
"13213","bhaa_flickr_user_id","34896940@N06","yes"
"13214","bhaa_flickr_api_key","38b77dc294e8ca6671a","yes"
"13215","bhaa_flickr_secret","ecd5f3ac20e6b7b7","yes"
"688","widget_flickr_footer-widget","a:1:{s:12:""_multiwidget"";i:1;}","yes"
"1777","widget_flickr-widget","a:1:{s:12:""_multiwidget"";i:1;}","yes"

 */
echo 'Flickr Test';
//$phpFlickr = new phpFlickr('38b77dc294e8ca6671ab35280c8bd2f3');//,"ecd5f3ac20e6b7b7",false);
$phpFlickr = new phpFlickr('38b77dc294e8ca6671ab35280c8bd2f3',"ecd5f3ac20e6b7b7");//,false);
$phpFlickr->setProxy("dub-proxy", "8080");

$DB = "mysqli://root@localhost:3306/bhaaie_wp";
error_log('$DB '.$DB);
//$phpFlickr->enableCache("db",$DB);

//echo $phpFlickr->panda_getList();
//echo $phpFlickr->auth_getFrob();

$person = $phpFlickr->people_findByUsername('bhaa');
var_dump($person);

//$person_getInfo = $phpFlickr->people_getInfo('34896940@N06');
//var_dump($person_getInfo);

$photosets_getList = $phpFlickr->photosets_getList($person['id']);
var_dump($photosets_getList);

$people_getPublicPhotos = $phpFlickr->people_getPublicPhotos($person['id'], NULL, NULL, 14);
var_dump($people_getPublicPhotos);

foreach ((array)$people_getPublicPhotos['photos']['photo'] as $photo) {
		
	//var_dump($photo);
	var_dump($phpFlickr->buildPhotoURL($photo,"large"));
}

//$photosets_getPhotos = $phpFlickr->photosets_getPhotos('72157632557211817');
//var_dump($photosets_getPhotos);

//$person = $this->phpFlickr->people_findByUsername('tomhealy');//eoinfegan');//get_option('bhaa_flickr_username'));
//var_dump($person);




//echo $phpFlickr->getErrorCode();
//echo $phpFlickr->getErrorMsg();

?>