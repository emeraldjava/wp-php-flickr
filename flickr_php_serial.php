<?php
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


#
# call the API and decode the response
#

$url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params);
echo $url;

$rsp = file_get_contents($url);

$rsp_obj = unserialize($rsp);
echo $rsp_obj;

#
# display the photo title (or an error if it failed)
#

if ($rsp_obj['stat'] == 'ok'){

	$photo_title = $rsp_obj['photo']['title']['_content'];

	echo "Title is $photo_title!";
}else{

	echo "Call failed!";
}
?>