<?php

$ch = curl_init("https://graph.facebook.com/me/oddjobb:share?pegasus=http://dev.gullinbursti.cc/projs/oddjob/posts/pegasus.htm&access_token=AAABZB4zejkTkBAIeXdYOgnAOhKpswtVcsvVdYX0hbmTGzOUt5Qcuvz7v3wvFcEDSBGMW4WhclPxM00YXH45ZAMZBHmTC0UZD");

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_exec($ch);
curl_close($ch);


echo $ch;
?>