<?php

// We have support for Image(File("image.jpg")) !!
require_once("File.php");

/**
 * Image class wraps PHP's image and GD functions that I use into a more useful
 * and logical system. Thumbnail generation is added.
 */
class Image
{
	/**
	 * Filepath of the image.
	 */
	protected $imagePath;

	/**
	 * Cached getimagesize() data.
	 */
	protected $imagesize = NULL;

	/**
	 * Cached file wrapper.
	 */
	protected $file = NULL;

	/**
	 * CTOR.
	 */
	public function __construct($path)
	{
		if($path instanceof File) {
			$this->file = $path;
			$path = $path->path();
		}
		$this->imagePath = $path;
	}

	/**
	 * toString.
	 */
	public function __toString()
	{
		$this->wrapFile();
		return "Image: " . $this->file->basename();
	}

	/*************** PROJECT-SPECIFIC METHODS ***************/ 

	/**
	 * Corresponds to Linksys WVC54GCA file naming scheme.
	 */
	public function linksysDate()
	{
		$regex = "/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})(\d{3})\.jpg/";
		// TODO
	}

	/*************** IMAGE INFORMATION ***************/ 

	/**
	 * Gets the width and height as an array of two items respectively.
	 */
	public function size()
	{
		$this->genImageData();
		return array($this->imagesize[0], $this->imagesize[1]);
	}

	/**
	 * Gets the width
	 */
	public function width()
	{
		$this->genImageData();
		return $this->imagesize[0];
	}

	/**
	 * Gets the height
	 */
	public function height()
	{
		$this->genImageData();
		return $this->imagesize[1];
	}

	/**
	 * Gets the MIME type
	 */
	public function mimetype()
	{
		$this->genImageData();
		return $this->imagesize['mime'];
	}

	/**
	 * Gets the number of channels
	 */
	public function channels()
	{
		$this->genImageData();
		return $this->imagesize['channels'];
	}

	/**
	 * Gets the number of bits per color
	 */
	public function bits()
	{
		$this->genImageData();
		return $this->imagesize['bits'];
	}

	/*************** IMAGE PROCESSING METHODS ***************/ 

	/**
	 * Make a thumbnail of the image. (Requires GD)
	 * TODO: Make imagemagick an acceptable alternative.
	 */
	public function makeThumbnail($outputFile, $maxWidth, $maxHeight)
	{
		$f = new File($outputFile);
		if($f->exists()) {
			return;
		}

		$img = NULL;
		switch($this->mimetype()) {
			case 'image/jpeg':
				$img = imagecreatefromjpeg($this->imagePath);
				break;
			case 'image/gif':
				$img = imagecreatefromgif($this->imagePath);
				break;
			case 'image/png':
				$img = imagecreatefrompng($this->imagePath);
				break;
			default:
				echo "Err: Cannot make thumbnail from type: \"" . 
					$this->mimetype() . "\".";
				return;
		}

		$size = $this->size();
		$width = $size[0];
		$height = $size[1];

		// TODO: Improve resize algorithm
		$newWidth = 0;
		$newHeight = 0;
		if($width > $height) {
			$newWidth = $maxWidth;
			$newHeight = $height/$width * $maxHeight;
			if($newWidth < $maxHeight) {
				$newHeight = $maxHeight;
				$newWidth = $width/$height * $maxWidth;
			}
		}
		else {
			$newHeight = $maxHeight;
			$newWidth = $width/$height * $maxWidth;
			if($newWidth < $maxHeight) {
				$newWidth = $maxWidth;
				$newHeight = $height/$width * $maxHeight;
			}
		}

		$new = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresampled($new, $img, 0, 0, 0, 0, $newWidth, $newHeight, 
						   $width, $height);

		// Must create file
		$o = new File($outputFile);
		$o->touch();

		// Save encoding based on extension
		$ext = substr($outputFile, -4);
		switch($ext) {
			case '.jpg':
			case 'jpeg':
				imagejpeg($new, $outputFile); // TODO: Quality
				break;
			case '.png':
				imagepng($new, $outputFile);
				break;
			case '.gif':
				imagegif($new, $outputFile);
				break;
		}
		imagedestroy($new);
		imagedestroy($img);
	}


	/*************** PROTECTED METHODS ***************/ 

	/**
	 * Get and cache various image data in PHP's poorly named getimagesize().
	 */
	protected function genImageData()
	{
		if($this->imagesize === NULL) {
			$this->wrapFile();
			if(!$this->file->exists()) {
				echo "Err: Image doesn't exist!";
			}
			$this->imagesize = getimagesize($this->imagePath);
		}
		return $this->imagesize;
	}

	/**
	 * Make a file wrapper.
	 */
	protected function wrapFile()
	{
		if($this->file === NULL) {
			$this->file = new File($this->imagePath);
		}
	}
}
