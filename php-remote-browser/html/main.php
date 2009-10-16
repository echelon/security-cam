<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
	$d = getPage();
	$error = $d['error'];
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?php echo $d['title']; ?></title>
		<script type="text/javascript" src="/jquery.js"></script>
		<script type="text/javascript" src="/jquery-cookie.js"></script>
		<script type="text/javascript" src="/camera.js"></script>
		<link rel="stylesheet" href="/style.css" type="text/css" />
	</head>
	<body>
		<div id="main">
			<div id="debug"></div>
			<?php 
				if($auth->getUser()) {
					include('usernavbar.php');
					include($d['page']);
				}
			?>
		</div>
	</body>
</html>
