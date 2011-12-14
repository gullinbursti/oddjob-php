<script type="text/javascript">

function goMap(address) {
	//http://maps.google.com/maps?q=222+Coral+Road,+Islamorada,+FL&hl=en
	
	address.replace(" ", "+");
	var paramWindow = window.open("http://maps.google.com/maps?q="+address+"&hl=en", "paramWindow", "location=1,status=1,toolbar=1,scrollbars=1,resizable=1,directories=1");
		paramWindow.moveTo(0, 0);
}
</script>

<div><img src="<?php echo ($merchant_img); ?>" width="200" height="200" title="" alt="" /></div>
<div class="tdJobStats">
  		<img src="#" width="16" height="16" alt="" title="" /><?php echo ($slots_tot); ?> left
	<img src="#" width="16" height="16" alt="" title="" /><?php echo (rand(5, 50)); ?> people
</div><p />
<div class="tdMerchantInfo">
	<?php echo ($merchant_name); ?><br />
	<?php echo ($merchant_addr); ?><br />
	<?php echo ($merchant_city .", ". $merchant_state ." ". $merchant_zip); ?><br />
	<?php echo ($merchant_phone); ?>
	<p />
	<input type="button" value="Get Directions" onclick="goMap('<?php echo($merchant_addr ." ". $merchant_city ." ". $merchant_state); ?>');" /><input type="button" value="Fine Print" onclick="" />
</div>      