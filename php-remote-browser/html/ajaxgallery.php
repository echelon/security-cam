<div id="ajaxgallery">
	<!--<img src="http://<?php echo $config->camDomain; ?>/img/snapshot.cgi?size=2&amp;quality=5" 
		 class="ajaximage" alt="security feed" />-->

<?php
	$i = 0;
	foreach($config->getCameras() as $cam) {
?>
		<img src="<?php echo $cam->snapshotUri(2, 1); ?>" class="ajaximg" 
			 id="ajaximg<?php echo $i; ?>" />
<?php
		$i++;
	}
?>

	<div id="test"></div>
</div>
