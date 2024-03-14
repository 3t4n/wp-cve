<?php


namespace As247\CloudStorages\Cache\Stores;


use As247\CloudStorages\Contracts\Cache\PathStore;

class NullStore implements PathStore
{

	public function forgetBranch($path)
	{
		// TODO: Implement forgetBranch() method.
	}

	public function delete($path)
	{
		// TODO: Implement delete() method.
	}

	public function deleteBranch($path)
	{
		// TODO: Implement deleteBranch() method.
	}

	public function forgetDir($path)
	{
		// TODO: Implement forgetDir() method.
	}

	public function deleteDir($path)
	{
		// TODO: Implement deleteDir() method.
	}

	public function move($source, $destination)
	{
		// TODO: Implement move() method.
	}

	public function query($path, $match = '*')
	{
		// TODO: Implement query() method.
	}

	public function complete($path, $isCompleted = true)
	{
		// TODO: Implement complete() method.
	}

	public function isCompleted($path)
	{
		// TODO: Implement isCompleted() method.
	}

	public function getCompleted($path)
	{
		// TODO: Implement getCompleted() method.
	}

	public function put($path, $data, $seconds = 3600)
	{
		// TODO: Implement put() method.
	}

	public function forever($path, $value)
	{
		// TODO: Implement forever() method.
	}

	public function get($path)
	{
		// TODO: Implement get() method.
	}

	public function forget($path)
	{
		// TODO: Implement forget() method.
	}

	public function flush()
	{
		// TODO: Implement flush() method.
	}
}
