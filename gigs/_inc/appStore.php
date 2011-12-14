<div><?php echo ($app_name); ?></div>
<div id="divAppStoreRating"><?php 
	for ($i=0; $i<5; $i++) {
		if ($app_rate > $i)
			echo ("<img id=\"imgStar_". $i ."\" name=\"imgStar_". $i ."\" src=\"./img/star_filled.png\" width=\"16\" height=\"16\" />");
			
		else
		    echo ("<img id=\"imgStar_". $i ."\" name=\"imgStar_". $i ."\" src=\"./img/star_empty.png\" width=\"16\" height=\"16\" />");
	}
	echo("Average Rating ". $app_rate); 
?></div>
<div id="divAppDetails"><?php echo(substr($appStore_json->results[0]->description, 0, 960)); ?>…</div>     