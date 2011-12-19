<script type="text/javascript">

function goMap(address) {
	//http://maps.google.com/maps?q=222+Coral+Road,+Islamorada,+FL&hl=en
	
	address.replace(" ", "+");
	var paramWindow = window.open("http://maps.google.com/maps?q="+address+"&hl=en", "paramWindow", "location=1,status=1,toolbar=1,scrollbars=1,resizable=1,directories=1");
		paramWindow.moveTo(0, 0);
}
</script>

<div><img src="<?php echo ($merchant_img); ?>" width="200" height="200" title="" alt="" /></div>
<div class="tdJobStats"><p>
  	<img src="./img/iconTime.png" width="15" height="15" alt="" title="" /><?php echo (rand(2, 10)); ?> DAYS LEFT
	<img src="./img/iconVoucher.png" width="15" height="17" alt="" title="" /><?php echo ($slots_tot); ?> LEFT
</p></div><p />
<div><?php switch ($job_row['action_id']) {
	case "9":
   		echo ("<input type=\"button\" value=\"Take Job\" width=\"200\" onclick=\"jobWatch(". $job_id .");\" /></div><p />");
		break;
	
	case "10":
   		echo ("<input type=\"button\" value=\"Take Job\" width=\"200\" onclick=\"jobRecommend(". $job_id .");\" /></div><p />");
		break;
		
    case "11":
   		echo ("<input type=\"button\" value=\"Take Job\" width=\"200\" onclick=\"jobChallenge(". $job_id .");\" /></div><p />");
		break;
} ?></div>	
<div class="divMerchantTerms">Fine Print: <?php echo ($offer_row[5]); ?></div><p />
<div class="tdMerchantInfo">
	<?php echo ($merchant_name); ?><br />
	<?php echo ($merchant_addr); ?><br />
	<?php echo ($merchant_city .", ". $merchant_state ." ". $merchant_zip); ?><br />
	<?php echo ($merchant_phone); ?>
	<!-- <input type="button" value="Get Directions" onclick="goMap('<?php echo($merchant_addr ." ". $merchant_city ." ". $merchant_state); ?>');" /><input type="button" value="Fine Print" onclick="" /> -->
</div>      