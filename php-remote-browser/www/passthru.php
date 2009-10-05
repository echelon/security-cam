<?php

if(!isset($_GET['u'])) {
        exit();
}
//echo $_GET['u'];
//exit();

$mode = 'r';
if(isset($_GET['b'])) {
        $mode = 'rb';
}

$f = fopen($_GET['u'], $mode);
$data = stream_get_contents($f);
fclose($f);

if(isset($_GET['m'])) {
        header("Content-Type: " . $_GET['m']);
}

echo $data;

