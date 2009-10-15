<?php
	$error = null;
	if(isset($_GET['id'])) {
		$id = $_GET['id'];
		$cameras = $config->getCameras();
		if(array_key_exists($id, $cameras)) {
			$cam = $cameras[$id];
?>

<div id="camera_section">
		<span class="cam_block cam_block_single" id="cam_block_id<?php echo $id; ?>">
			<span class="cam_info1"><?php echo $cam->getName(); ?></span>
			<span class="cam_info2" 
				  id="ajaximg<?php echo $id; ?>_status">Not updated</span>
			<span class="cam_img_container">
				<img src="<?php echo $cam->jpeg(3, 5); ?>" class="cam_img" 
					 id="ajaximg<?php echo $id; ?>" width="640" height="480"  
					 alt="<?php echo $cam->getModel(); ?>" />
			</span>
		</span>
</div>
<?php
	}
	else {
		$error = "That ID doesn't exist.";
	}
}
else {
	$error = "Must specify an ID.";
}

if($error) {
	echo $error;
}
?>
