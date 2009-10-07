<div id="cameras">
<?php
	$i = 0;
	foreach($config->getCameras() as $cam) {
?>
		<span class="cam_block">
			<span class="cam_info1"><?php echo $cam->getName(); ?></span>
			<span class="cam_info2"><?php echo $i; ?></span>
			<span class="cam_img_container">
				<img src="<?php echo $cam->jpeg(2, 5); ?>" class="cam_img" 
					 id="ajaximg<?php echo $i; ?>" width="320" height="240" 
					 alt="<?php echo $cam->getModel(); ?>" />
			</span>
		</span>
<?php
		$i++;
	}
?>
</div>