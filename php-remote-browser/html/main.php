<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?php echo $title; ?></title>
		<script type="text/javascript" src="/jquery.js"></script>
		<script type="text/javascript">

			function reload2() {
				var time = new Date().getTime();
				var	img = document.getElementById("ajaximg0");
				img.src = img.src + '#' + time;
			}

			/* Reload images on Ajax page when called. */
			function reload2b() {
				var time = new Date().getTime();
				var tags = document.getElementsByTagName("img");
				for(var i = 0; i < tags.length; i++) {
					if(tags[i].className != "ajaximg") {
						continue;
					}
					tags[i].src = tags[i].src + '#' + time;
				}
			}
			/* Document Ready Function */
			$(document).ready(function() {
				setInterval("reload2b()", 1500);
			});
		</script>

		<link rel="stylesheet" href="/style.css" type="text/css" />
	</head>
	<body>
		<div id="main">
			<?php 

				if($auth->getUser()) {
					include('usernavbar.php');
				}

				// Don't want to include($page), but I'm lazy enough to do this:
				switch($page) {
					case 'camview':
						include('camview.php');
						break;
					case 'gallery':
						include('gallery.php');
						break;
					case 'ajaxgallery':
						include('ajaxgallery.php');
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
