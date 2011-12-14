<table cellspacing="0" cellpadding="0" border="0" id="tblHeaderTout"><tr>
	<td width="36"><a href="./profile.php"><img id="imgAvatar" src="http://graph.facebook.com/<?php echo($fb_id); ?>/picture" title="<?php echo ($fb_name); ?>" alt="<?php echo ($fb_name); ?>" width="32" height="32" /></a></td>
	<td align="left"><a href="./profile.php"><span id="spnFBName"><?php echo($fb_name); ?></span></a></td>
	<td align="right"><span id="spnLocation"><select id="selLocation" name="selLocation"><?php 
		echo ("<option value=\"1\" selected>". $fb_location ."</option>");
		echo ("<option value=\"2\">New York, New York</option>");
		echo ("<option value=\"3\">Miami, Florida</option>");
		echo ("<option value=\"4\">Austin, Texas</option>");
	?></select></span></td>
</tr></table>