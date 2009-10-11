<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
	$d = getPage();
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?php echo $d['title']; ?></title>
		<script type="text/javascript" src="/jquery.js"></script>
		<script type="text/javascript" src="/jquery-ui.js"></script>
		<script type="text/javascript" src="/ajaxcam.js"></script>
		<?php
			/*if($d['title'] == 'ajaxgallery') {
				echo "<script type=\"text/javascript\" src=\"/ajaxcam.js\"></script>";
			}*/
		?>
		<link rel="stylesheet" href="/style.css" type="text/css" />
	</head>
	<body>
		<div id="main">
			<?php 

				if($auth->getUser()) {
					include('usernavbar.php');
				}

				// Don't want to include($page), but I'm lazy enough to do this:
				/*switch($page) {
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
				}*/

				include($d['page']);

			?>
		</div>
	</body>
</html>
