<table cellspacing="0" cellpadding="0" border="0" id="tblHeaderTout"><tr><td>
	<span id="spnHeaderWrapper">
		<a href="./"><img id="imgOddJob" src="./img/logo.png" title="Odd Job" alt="Odd Job" width="90" height="24" /></a>
		<a href="./howitworks.php">How it works?</a> | <a href="./download.php">Download App</a> | <a href="./support.php">Support</a>
	</span>
</td></tr></table> 

<table cellspacing="0" cellpadding="0" border="0" id="tblSubHeaderTout"><tr><td>
	<span id="spnSubHeaderWrapper">
		<a href="./profile.php"><img id="imgAvatar" src="http://graph.facebook.com/<?php echo($fb_id); ?>/picture" title="<?php echo ($fb_name); ?>" alt="<?php echo ($fb_name); ?>" width="39" height="39" /></a>
		<span class="spnSubHeaderLinks"><a href="#"><?php 
			$loc_arr = explode(", ", $fb_location);
			echo($loc_arr[0]); 
		?> (<?php echo(rand(4, 6)); ?>)</a></span>
		<span class="spnSubHeaderLinks"><a href="#">My Jobs (<?php echo(rand(0, 5)); ?>)</a></span>
		<span class="spnSubHeaderLinks"><a href="#">Invites (<?php echo(rand(0, 4)); ?>)</a></span>
		<span id="spnSubHeaderSettings"><a href="#">Settings</a></span>
	</span>
</td></tr></table> 