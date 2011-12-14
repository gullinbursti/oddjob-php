<?php

$ch = curl_init("http://graph.facebook.com/660042243");

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_exec($ch);
curl_close($ch);


echo $ch;
?>