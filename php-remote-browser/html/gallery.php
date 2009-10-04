<div id="gallery">
<?php
// Gallery of thumbnails...
$i = 0;
foreach($images as $img) {
	if($i != 0 && $i % 5 == 0) {
		echo "<br />";
	}
?>

<a href="<?php echo $img['file']; ?>"><img 
   src="<?php echo $img['thumb']; ?>" /></a>

<?php
	$i++;
}
?>
</div>
