<div>Logged in as <?php echo $auth->getUser()->getUsername(); ?>.
	(<a href="./?logout=1" />Logout</a>)</div>

<div id="camview">
	<img src="http://<?php echo $config->camDomain; ?>/img/video.mjpeg" />
</div>
