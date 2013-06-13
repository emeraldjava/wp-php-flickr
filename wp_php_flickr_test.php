<?php

define('WP_INSTALL_DIR','C:\oconnellp\wamp\wordpress');

// http://theme.fm/2011/09/wordpress-internals-how-wordpress-boots-up-2315/
// http://theme.fm/wp-content/uploads/2011/09/wordpress-boot-chart.png
require_once( WP_INSTALL_DIR.'\wp-load.php');
require_once( WP_INSTALL_DIR.'\wp-config.php');
require_once( WP_INSTALL_DIR.'\wp-settings.php');

require_once('wp_php_flickr.php');

echo 'wp_php_flickr_test'.PHP_EOL;
$wp_php_flickr = new wp_php_flickr('xx','xx');

$person = $wp_php_flickr->people_findByUsername('bhaa');
var_dump($person);

//echo $phpFlickr->getErrorCode();
//echo $phpFlickr->getErrorMsg();

?>