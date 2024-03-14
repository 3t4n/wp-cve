<?php

namespace TotalContestVendors\TotalCore\Contracts\Filesystem;


/**
 * Interface Local
 * @package TotalContestVendors\TotalCore\Contracts\Filesystem
 */
interface Base {
	/**
	 * Reads entire file into a string
	 *
	 *
	 * @param string $file Name of the file to read.
	 *
	 * @return string|bool The function returns the read data or false on failure.
	 */
	public function get_contents( $file );

	/**
	 * Reads entire file into an array
	 *
	 *
	 * @param string $file Path to the file.
	 *
	 * @return array|bool the file contents in an array or false on failure.
	 */
	public function get_contents_array( $file );

	/**
	 * Write a string to a file
	 *
	 *
	 * @param string $file     Remote path to the file where to write the data.
	 * @param string $contents The data to write.
	 * @param int    $mode     Optional. The file permissions as octal number, usually 0644.
	 *                         Default false.
	 *
	 * @return bool False upon failure, true otherwise.
	 */
	public function put_contents( $file, $contents, $mode = false );

	/**
	 * Gets the current working directory
	 *
	 *
	 * @return string|bool the current working directory on success, or false on failure.
	 */
	public function cwd();

	/**
	 * Change directory
	 *
	 *
	 * @param string $dir The new current directory.
	 *
	 * @return bool Returns true on success or false on failure.
	 */
	public function chdir( $dir );

	/**
	 * Changes file group
	 *
	 *
	 * @param string $file      Path to the file.
	 * @param mixed  $group     A group name or number.
	 * @param bool   $recursive Optional. If set True changes file group recursively. Default false.
	 *
	 * @return bool Returns true on success or false on failure.
	 */
	public function chgrp( $file, $group, $recursive = false );

	/**
	 * Changes filesystem permissions
	 *
	 *
	 * @param string $file      Path to the file.
	 * @param int    $mode      Optional. The permissions as octal number, usually 0644 for files,
	 *                          0755 for dirs. Default false.
	 * @param bool   $recursive Optional. If set True changes file group recursively. Default false.
	 *
	 * @return bool Returns true on success or false on failure.
	 */
	public function chmod( $file, $mode = false, $recursive = false );

	/**
	 * Changes file owner
	 *
	 *
	 * @param string $file      Path to the file.
	 * @param mixed  $owner     A user name or number.
	 * @param bool   $recursive Optional. If set True changes file owner recursively.
	 *                          Default false.
	 *
	 * @return bool Returns true on success or false on failure.
	 */
	public function chown( $file, $owner, $recursive = false );

	/**
	 * Gets file owner
	 *
	 *
	 * @param string $file Path to the file.
	 *
	 * @return string|bool Username of the user or false on error.
	 */
	public function owner( $file );

	/**
	 * Gets file permissions
	 *
	 *
	 * @param string $file Path to the file.
	 *
	 * @return string Mode of the file (last 3 digits).
	 */
	public function getchmod( $file );

	/**
	 *
	 * @param string $file
	 *
	 * @return string|false
	 */
	public function group( $file );

	/**
	 *
	 * @param string $source
	 * @param string $destination
	 * @param bool   $overwrite
	 * @param int    $mode
	 *
	 * @return bool
	 */
	public function copy( $source, $destination, $overwrite = false, $mode = false );

	/**
	 *
	 * @param string $source
	 * @param string $destination
	 * @param bool   $overwrite
	 *
	 * @return bool
	 */
	public function move( $source, $destination, $overwrite = false );

	/**
	 *
	 * @param string $file
	 * @param bool   $recursive
	 * @param string $type
	 *
	 * @return bool
	 */
	public function delete( $file, $recursive = false, $type = false );

	/**
	 *
	 * @param string $file
	 *
	 * @return bool
	 */
	public function exists( $file );

	/**
	 *
	 * @param string $file
	 *
	 * @return bool
	 */
	public function is_file( $file );

	/**
	 *
	 * @param string $path
	 *
	 * @return bool
	 */
	public function is_dir( $path );

	/**
	 *
	 * @param string $file
	 *
	 * @return bool
	 */
	public function is_readable( $file );

	/**
	 *
	 * @param string $file
	 *
	 * @return bool
	 */
	public function is_writable( $file );

	/**
	 *
	 * @param string $file
	 *
	 * @return int
	 */
	public function atime( $file );

	/**
	 *
	 * @param string $file
	 *
	 * @return int
	 */
	public function mtime( $file );

	/**
	 *
	 * @param string $file
	 *
	 * @return int
	 */
	public function size( $file );

	/**
	 *
	 * @param string $file
	 * @param int    $time
	 * @param int    $atime
	 *
	 * @return bool
	 */
	public function touch( $file, $time = 0, $atime = 0 );

	/**
	 *
	 * @param string $path
	 * @param mixed  $chmod
	 * @param mixed  $chown
	 * @param mixed  $chgrp
	 *
	 * @return bool
	 */
	public function mkdir( $path, $chmod = false, $chown = false, $chgrp = false );

	/**
	 *
	 * @param string $path
	 * @param bool   $recursive
	 *
	 * @return bool
	 */
	public function rmdir( $path, $recursive = false );

	/**
	 *
	 * @param string $path
	 * @param bool   $include_hidden
	 * @param bool   $recursive
	 *
	 * @return bool|array
	 */
	public function dirlist( $path, $include_hidden = true, $recursive = false );
}