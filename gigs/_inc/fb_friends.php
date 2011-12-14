<div id="divFBUsers"><?php 
	$friendID_arr = array_rand($friend_arr, 8);
	for ($i=0; $i<8; $i++)
		echo ("<a href=\"http://facebook.com/profile.php?id=". $friendID_arr[$i] ."\" target=\"_blank\"><img id=\"imgFBAvatar\" src=\"http://graph.facebook.com/". $friendID_arr[$i] ."/picture\" width=\"53\" height=\"53\" border=\"0\" title=\"". $friend_arr[$friendID_arr[$i]] ."\" alt=\"". $friend_arr[$friendID_arr[$i]] ."\" /></a>\n\t\t\t\t");
?></div>