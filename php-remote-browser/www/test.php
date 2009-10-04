<?php

require_once('../script/File.php');

function indent($indent = 0)
{
	for($i = 0; $i < $indent; $i++) {
		print "...";
	}
}

function dirPrint($dir, $indent = 0)
{
	foreach($dir as $file) {
		indent($indent);
		if(!$file->isDir()) {
			echo $file . "<br />";
			continue;
		}
		echo "<u>" . $file . "</u><br />";
		dirPrint($file, $indent+1);
	}
}

//echo new File(".");
//exit;
dirPrint(new File(".."));


