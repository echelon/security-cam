<?php

// Image thumbnail page. 
// TODO: Organize into MVC, or at least get rid fo this terrible arch.

require_once('../script/File.php');
require_once('../script/Image.php');

$imgdir = new File("./uploads");

// Update all the thumbnails. 
// TODO NOTE: This is very slow for LOTS of ungenerated images...
$thumbPrefix = "./uploads/thumbs/t";
$images = array();
foreach($imgdir as $file) 
{
	if($file->isDir() || $file->extension() != "jpg") {
		continue;
	}
	$img = new Image($file);

	$images[] = array(
		'file'  => './uploads/' . $file->basename(),
		'thumb' => $thumbPrefix . $file->basename()
	);

	$img->makeThumbnail($thumbPrefix . $file->basename(), 150, 150);
}

