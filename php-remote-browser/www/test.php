<?php

require_once('../script/File.php');
require_once('../script/Image.php');


$imgdir = new File("./uploads");

foreach($imgdir as $file) 
{
	if($file->isDir() || $file->extension() != "jpg") {
		continue;
	}
	$img = new Image($file);

	$img->makeThumbnail("./uploads/thumbs/t" . $file->basename(), 100, 100);

}





