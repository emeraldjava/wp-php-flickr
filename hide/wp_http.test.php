<?php
// http://planetozh.com/blog/2009/08/how-to-make-http-requests-with-wordpress/
// http://wp.tutsplus.com/tutorials/plugins/a-guide-to-the-wordpress-http-api-the-basics/
define('WP_INSTALL_DIR','C:\oconnellp\wamp\wordpress');
//define('PLUGIN_ROOT_DIR',dirname(__FILE__).'./../');

require_once( WP_INSTALL_DIR.'\wp-config.php');
require_once( WP_INSTALL_DIR.'\wp-load.php');
require_once( WP_INSTALL_DIR.'\wp-settings.php');

if( !class_exists( 'WP_Http' ) )
	require_once( WP_INSTALL_DIR.'\wp-includes\class-http.php');

//$result = dns_get_record("google.com");
//print_r($result);

//echo dns_get_record('localhost');

//echo gethostbyname("host.name.tld");
//var_export (dns_get_record ( "host.name.tld") );

//echo "wp_http";

//var_dump(wp_remote_get('http://flickr.com'));

//$ip = gethostbyname('http://localhost');
//echo 'ip'.$ip;

$request = new WP_Http();
//$result = $request->request('http://api.flickr.com/services/rest/?method=flickr.test.echo&name=value');
$result = wp_remote_get('http://api.flickr.com/services/rest/?method=flickr.test.echo&name=value');

var_dump($result);
var_dump($result['body']);
var_dump($result['response']['code']);

$object = json_decode(json_encode($result), FALSE);
var_dump($object);
?>