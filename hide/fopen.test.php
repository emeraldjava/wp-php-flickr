<?php 
echo "fopen";
//echo fopen("http://google.com","r");
echo file_get_contents("http://google.com");
echo "done";
?>