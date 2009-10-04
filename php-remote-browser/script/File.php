<?php

/**
 * Represents a file or directory, or a hypothetical file or directory.
 * Can also touch, update timestamps, delete, create links/symlinks, etc.
 * This class serves to wrap PHP's miscellaneous file functions in a much more 
 * structured/organized manner.
 *
 * Also important is that this class wrap's PHP's directory iterator and may
 * be used on directories. The contained files are returned as instances of the 
 * File class too. (TODO)
 * 
 * XXX: Note that I've selectioned not to use 'get*()' naming for accessors.
 */
class File implements Iterator
{
	/**
	 * Path (corrected if possible)
	 */
	private $path;

	/**
	 * Provided path
	 */
	protected $provided;

	/**
	 * Cached pathinfo array.
	 * See genPathinfo()
	 */
	protected $pathinfo = NULL;

	/**
	 * Cached directory iterator handle.
	 */
	protected $dirhandle = NULL;

	/**
	 * Directory iterator position.
	 * Obtained by using key() or PHP iteration facilities.
	 */
	protected $position = 0;

	/**
	 * Cached directory iterator current item
	 * Obtained by using current() or PHP iteration facilities.
	 */
	protected $current = NULL;

	/**
	 * CTOR.
	 */
	public function __construct($path)
	{
		$this->path = $path;
		$this->provided = $path;

		$real = realpath($path);
		if($real) {
			$this->path = $real;
		}
	}

	/**
	 * DTOR.
	 */
	public function __destruct()
	{
		// Close the directory iterator handle
		if($this->dirhandle !== NULL) {
			$this->close();
		}
	}

	/**
	 * toString.
	 * Returns convenient representation.
	 */
	public function __toString()
	{
		return $this->basename();
	}

	/*************** DIRECTORY ITERATION ***************/ 

	/**
	 * Get current item.
	 */
	public function current()
	{
		if($this->current === false) {
			return false;
		}
		if($this->current == "") {
			$this->next(); // We don't want empty.
		}
		// Must supply this File object's path!
		return new File($this->path . '/' . $this->current);
	}

	/**
	 * Get position.
	 */
	public function key()
	{
		return $this->position;
	}

	/**
	 * Iterate to the next item.
	 */
	public function next()
	{
		$this->current = readdir($this->dirhandle);
		if($this->current == '.' || $this->current == '..' ) {
			$this->next(); // Skip current and parent directories.
		}
	}

	/**
	 * Rewind to beginning.
	 * Also serves to open the dirhandle as this is the first method called in
	 * iteration.
	 */
	public function rewind()
	{
		if($this->dirhandle === NULL) {
			$this->dirhandle = opendir($this->path);
		}
		rewinddir($this->dirhandle);
		$this->position = 0;
		$this->current = NULL;
	}

	/**
	 * If the current position is valid.
	 */
	public function valid()
	{
		/*if($this->dirhandle === NULL) {
			$this->dirhandle = readdir($this->path);
			$this->position = 0;
			$this->current = NULL;
		}*/
		if($this->current === false) {
			return false;
		}
		return true;
	}

	/**
	 * Close the directory iterator.
	 * Not a part of the Iterator interface, but useful.
	 */
	public function close()
	{
		closedir($this->dirhandle);
	}

	/*************** FILE INFORMATION ***************/ 

	/**
	 * If the file exists
	 */
	public function exists()
	{
		return file_exists($this->path);
	}

	/**
	 * If the file is a directory
	 */
	public function isDir()
	{
		return is_dir($this->path);
	}

	/**
	 * If the file is a file (vs. a directory)
	 */
	public function isFile()
	{
		return is_file($this->path);
	}

	/**
	 * If the file is a link or symlink
	 */
	public function isLink()
	{
		return is_link($this->path);
	}

	/**
	 * If the file is executable
	 */
	public function isExecutable()
	{
		return is_executable($this->path);
	}

	/**
	 * If the file is readable
	 */
	public function isReadable()
	{
		return is_readable($this->path);
	}

	/**
	 * If the file is writable
	 */
	public function isWritable()
	{
		return is_writable($this->path);
	}

	/**
	 * Returns the corrected filepath (if possible)
	 */
	public function path()
	{
		return $this->path;
	}

	/**
	 * Returns the originally provided filepath
	 */
	public function provided()
	{
		return $this->provided;
	}

	/**
	 * Basename, eg. '/www/htdocs/index.html' returns 'index.html'
	 */
	public function basename()
	{
		$this->genPathinfo();
		return $this->pathinfo['basename'];
	}

	/**
	 * Dirname, eg. '/www/htdocs/index.html' returns '/www/htdocs'
	 */
	public function dirname()
	{
		$this->genPathinfo();
		return $this->pathinfo['dirname'];
	}

	/**
	 * Extension, eg. '/www/htdocs/index.html' returns 'html'
	 */
	public function extension()
	{
		$this->genPathinfo();
		return $this->pathinfo['extension'];
	}

	/**
	 * Gets the target of a link.
	 */
	public function target()
	{
		return readlink($this->path);
	}

	/**
	 * Returns entire pathinfo array.
	 */
	public function pathinfo()
	{
		$this->genPathinfo();
		return $this->pathinfo;
	}

	/*************** FILE CREATION/MODIFICATION ***************/ 

	/**
	 * Update file touch time. Create file if it doesn't exist.
	 * Set $atime to update access time in addition to touch time. 
	 */
	public function touch($time = NULL, $atime = NULL)
	{
		if($time) {
			if($atime) {
				return touch($this->path, $time, $atime);
			}
			return touch($this->path, $time);
		}
		return touch($this->path);
	}

	/**
	 * Delete the file.
	 */
	public function delete()
	{
		// TODO: May need to use rmdir() to remove directories.
		unlink($this->path);
	}

	/**
	 * Created as a link to $other.
	 */
	public function link($other)
	{
		link($other, $this->path);
	}

	/**
	 * Create a symlink to $other.
	 */
	public function symlink($other)
	{
		symlink($other, $this->path);
	}

	/*************** PROTECTED METHODS ***************/ 

	/**
	 * Generate the cached pathinfo.
	 */
	protected function genPathinfo()
	{
		if(empty($this->pathinfo)) {
			$this->pathinfo = pathinfo($this->path);
		}
	}
}
