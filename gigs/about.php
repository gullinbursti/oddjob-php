<?php ob_start();

require "./_consts/db_consts.php";
require './_wrk/mailer.php';

require "./_inc/db_open.php";


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<script type="text/javascript">
			var _kmq = _kmq || [];
		
			function _kms(u) {
				setTimeout(function() {
	      			var s = document.createElement('script'); 
					var f = document.getElementsByTagName('script')[0]; 
					s.type = 'text/javascript'; 
					s.async = true;
	      			s.src = u; 
					f.parentNode.insertBefore(s, f);
	    		}, 1);
	  		}

	  		_kms('//i.kissmetrics.com/i.js');
			_kms('//doug1izaerwt3.cloudfront.net/8afc90ad40b3e6b403aaec5e35d8b1343a9822da.1.js');
			_kmq.push(['record', 'View About']);
		</script>
		
	    <title>:: Odd Job :: About ::</title>
		<link href="./css/screen.css" rel="stylesheet" type="text/css" media="screen">
		
		<script type="text/javascript">
		<!--
		   
		-->
		</script> 
	</head>
	
	<body><div id="divMainWrapper">
		<div align="center">			
			<?php include './_inc/notifications.php'; ?>			
			<div id="divAbout">
				<div><strong>About Odd Job</strong></div>
				<p>Ex ea commodo consequat duis autem vel eum iriure dolor? Nulla facilisis at vero eros et accumsan et iusto! Insitam est usus legentis in iis qui facit eorum claritatem. Legunt saepius claritas est etiam processus dynamicus qui sequitur mutationem consuetudium lectorum. Dolore magna aliquam erat volutpat ut wisi enim ad.</p>
				<p>Facilisis at vero eros et accumsan, et iusto odio dignissim qui blandit praesent. Consuetudium lectorum mirum est notare quam littera gothica quam nunc. Claritatem insitam est usus legentis in iis qui facit eorum claritatem Investigationes? Formas humanitatis per seacula, quarta decima et quinta decima.</p>
				<p>Nihil imperdiet doming id quod mazim placerat facer. Dignissim qui blandit praesent, luptatum zzril delenit augue duis dolore te feugait nulla facilisi nam. Decima eodem modo typi qui nunc nobis videntur parum. Liber tempor cum soluta nobis eleifend option congue possim assum typi non habent, claritatem insitam est? Processus dynamicus qui sequitur mutationem consuetudium lectorum mirum est notare quam littera gothica quam nunc putamus.</p>
				<p>Option congue nihil imperdiet doming id quod mazim placerat facer possim assum typi non. Lobortis nisl ut aliquip ex ea commodo consequat duis autem vel eum iriure dolor. Iusto odio dignissim qui blandit praesent luptatum zzril, delenit augue duis. In vulputate velit esse molestie consequat vel illum dolore. Legere me lius quod ii legunt saepius claritas est etiam processus dynamicus qui sequitur mutationem consuetudium.</p>
			</div>
		</div>
	</div></body>
</html>

<?php 

require "./_inc/db_close.php";
ob_flush(); 
?>