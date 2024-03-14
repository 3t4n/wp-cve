<?php


namespace As247\CloudStorages\Cache\Stores;

use As247\CloudStorages\Contracts\Cache\PathStore;
use As247\CloudStorages\Support\Path;
use RuntimeException;

class ArrayStore implements PathStore
{
	protected $files = [];
	protected $completed = [];

	public function query($path, $deep = 1)
	{
		$directory = Path::clean($path);
		$results = [];
		$dirSegCount = Path::countSegments($directory);
		foreach ($this->files as $path => $file) {
			if (strpos($path, $directory) === 0) {
				if ($deep>0) {
					if ($path!==$directory && (Path::countSegments($path) - $dirSegCount <= $deep)) {
						$results[$path] = $file;
					}
				}else{
					$results[$path] = $file;
				}
			}
		}
		return $results;
	}
	public function get($key)
	{
		$key = Path::clean($key);
		return $this->files[$key] ?? null;
	}
	public function put($key, $data, $seconds = 3600)
	{
		$key = Path::clean($key);
		if($data===null){
			unset($this->files[$key]);
			$this->complete($key,false);
		}else {
			$this->files[$key] = $data;
		}
	}

	public function flush()
	{
		$root = $this->get('/');
		$this->files = [];
		$this->put('/', $root);
		$this->completed = [];
	}

	public function forget($path)
	{
		$this->put($path,null,-1);
	}

	public function forever($key, $value)
	{
		$this->put($key,$value,315360000);
	}

	public function forgetBranch($path)
	{
		$tmpPath = Path::clean($path);
		do  {
			if(!$this->get($tmpPath)){
				$this->forget($tmpPath);//Forget false item only
			}
			$this->complete($tmpPath,false);
		}while(($tmpPath = Path::clean(dirname($tmpPath))) && $tmpPath !== '/');
	}




	public function delete($path)
	{
		$this->put($path,false);
	}
	public function deleteBranch($path)
	{
		$tmpPath = Path::clean($path);
		do{
			$this->put($tmpPath, false);
		}
		while (($tmpPath = Path::clean(dirname($tmpPath))) && $tmpPath !== '/');
	}

	public function forgetDir($path)
	{
		$path=Path::clean($path);
		foreach ($this->query($path,0) as $key => $file) {
			$this->forget($key);
		}
		$this->complete($path,false);
	}

	public function deleteDir($path)
	{
		$path=Path::clean($path);
		foreach ($this->query($path,0) as $key => $file) {
			$this->put($key, false);
		}
		$this->complete($path,false);

	}
	function move($from,$to)
	{
		if(!$from || !$to){
			throw new RuntimeException("Invalid path $from -> $to");
		}
		$from=Path::clean($from);
		$to=Path::clean($to);
		//Destination tree changed we should clean up all parent
		//This need for onedrive because we not keep track of parents in cache
		$this->forgetBranch($to);
		foreach ($this->query($from,0) as $path => $file ){
			$newPath=Path::replace($from, $to, $path);
			if($path!==$newPath){
				$this->put($newPath,$file);
				$this->put($path,false);
			}
		}

		foreach ($this->getCompleted($from) as $key => $value) {
			$newKey = Path::replace($from, $to, $key);
			if ($newKey !== $key) {
				$this->complete($newKey,$value);
				$this->complete($key,false);
			}
		}
	}

	public function getCompleted($path){
		return $this->completed;
	}
	public function complete($path, $isCompleted = true)
	{
		$path = Path::clean($path);
		if($isCompleted) {
			$this->completed[$path] = $isCompleted;
		}else{
			$path=Path::clean($path);
			foreach ($this->getCompleted($path) as $key => $value) {
				if (strpos($key, $path) === 0) {
					unset($this->completed[$key]);
				}
			}
		}
	}

	public function isCompleted($path)
	{
		$path = Path::clean($path);
		return $this->completed[$path] ?? false;
	}
}
