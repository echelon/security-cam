<div id="camera_section">
<?php
	$i = 0;
	foreach($config->getCameras() as $cam) {
?>
		<span class="cam_block cam_block_multi" id="cam_block_id<?php echo $i; ?>">
			<span class="cam_info cam_info1"><?php echo $cam->getName(); ?></span>
			<span class="cam_info cam_info2" 
				  id="ajaximg<?php echo $i; ?>_status">Not updated</span>
			<span class="cam_img_container">
				<a href="/?viewcam&id=<?php echo $i; ?>"
				><img src="<?php echo $cam->jpeg(2, 5); ?>" class="cam_img" 
					 id="ajaximg<?php echo $i; ?>" width="320" height="240" 
					 alt="<?php echo $cam->getModel(); ?>" /></a>
			</span>
		</span>
<?php
		$i++;
	}
?>
</div>
