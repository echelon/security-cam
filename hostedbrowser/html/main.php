<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?php echo $title; ?></title>
		<style type="text/css">
			.error { color: #f00; }
		</style>
	</head>
	<body>
		<div id="main">
			<?php 
				// Don't want to include($page), but I'm lazy enough to do this:
				switch($page) {
					case 'camview':
						include('camview.php');
						break;
					case 'login':
						include('login.php');
						break;
					case 'error':
					default:
						include('error.php');
				}
			?>
		</div>
	</body>
</html>
