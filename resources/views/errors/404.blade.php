	<!--
	Author: W3layouts
	Author URL: http://w3layouts.com
	License: Creative Commons Attribution 3.0 Unported
	License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html lang="en">
<head>
<title>GrowthCharters</title>
<!-- Meta tag Keywords -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- Meta tag Keywords -->

<!-- css files -->
<link href="{{ asset('error/css/style.css') }}" rel="stylesheet" type="text/css" media="all">
<!-- //css files -->

<!-- online-fonts -->
<link href="//fonts.googleapis.com/css?family=Ropa+Sans:400,400i&amp;subset=latin-ext" rel="stylesheet">
<!--//online-fonts -->
</head>
<body>
<div class="header">
	<h1>error page</h1>
</div>
<div class="w3-main">
	<div class="agile-info">
		<h3>SORRY</h3>
		<h2>4<img src="{{ asset('error/images/confused.gif') }}" alt="image">4</h2>
		<p>An error Occurred in the Application And Your Page could not be Served.</p>
		
		<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">go back</a>
	</div>
</div>
<div class="footer-w3l">
	<p>
		<?php echo date("Y"); ?>&nbsp;&copy;&nbsp;
		<a href="https://kaizentrainingsolutions.com/" target="_blank" class="kt-link">Kaizen Training Solutions</a>
	</p>
</div>

</body>
</html>