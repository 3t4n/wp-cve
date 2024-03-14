<?php


namespace As247\CloudStorages\Cache\Stores;

use As247\CloudStorages\Contracts\Cache\Store;
use Exception;
use PDO;

class SqliteCache implements Store
{
	protected $pdo;

	/**
	 * SqliteCache constructor.
	 * @param null $dataFile
	 * @throws Exception
	 */
	public function __construct($dataFile=null)
	{
		if($dataFile===null){
			$dataFile=sys_get_temp_dir().'/'.md5(static::class).'';
		}
		$isNewDB=!file_exists($dataFile);
		$this->pdo=new PDO('sqlite:' . $dataFile);
		if($isNewDB) {
			$this->createTable();
		}else{
			$this->checkMalformed();
		}
	}

	/**
	 * @throws Exception
	 */
	protected function checkMalformed(){
		$this->pdo->prepare("select 1 from cache where 0=1");
		$error = $this->pdo->errorInfo();
		if($error[0] !=='00000'){
			throw new Exception(sprintf("SQLSTATE[%s]: Error [%s] %s",$error[0],$error[1],$error[2]));
		}
	}
	protected function createTable(){
		$this->pdo->query("
			CREATE TABLE IF NOT EXISTS `cache` (
				`key` varchar(500) not null,
				`value` text not null,
				`expiration` integer(11),
				PRIMARY KEY (`key`)
			)
		");
		$this->pdo->query("
			CREATE TABLE IF NOT EXISTS `completed` (
				`path` varchar(500) not null,
				`expiration` integer(11),
				PRIMARY KEY (`path`)
			)
		");
	}
	public function get($key){
		$statement=$this->pdo->prepare("SELECT * FROM cache WHERE key=? limit 1");
		$statement->bindValue(1,$key);
		$statement->execute();
		$cache=$statement->fetch(PDO::FETCH_OBJ);
		if(!$cache){
			return null;
		}
		if ($this->currentTime() >= $cache->expiration) {
			$this->forget($key);
			return null;
		}
		return unserialize($cache->value);
	}

	public function put($key, $value, $seconds=3600){
		$value=serialize($value);
		$statement=$this->pdo->prepare(
		"insert into cache (`key`,`value`,`expiration`) values (?,?,?)"
		);
		if($seconds===-1){
			$expire=2147483647;
		}else{
			$expire=$this->currentTime()+$seconds;
		}
		$statement->bindValue(1,$key);
		$statement->bindValue(2,$value);
		$statement->bindValue(3,$expire);
		if(!$statement->execute()){
			$statement=$this->pdo->prepare("UPDATE cache SET value=:value,expiration=:expiration  WHERE key=:key");
			$statement->bindValue(':key',$key);
			$statement->bindValue(':value',$value);
			$statement->bindValue(':expiration',$expire);
			return $statement->execute();
		}else{
			return true;
		}
	}
	public function forget($key){
		$statement=$this->pdo->prepare("DELETE FROM cache WHERE key=?");
		$statement->bindValue(1,$key);
		$statement->execute();
		return true;
	}
	/**
	 * Store an item in the cache indefinitely.
	 *
	 * @param  string  $path
	 * @param  mixed  $id
	 * @return bool
	 */
	public function forever($path, $value)
	{
		return $this->put($path, $value, -1);
	}
	public function getCompleted($key=''){
		if($key) {
			$statement = $this->pdo->prepare("SELECT * FROM completed WHERE path like ?");
			$statement->bindValue(1, $key . '%');
		}else{
			$statement = $this->pdo->prepare("SELECT * FROM completed");
		}
		$statement->execute();
		$allRecords=$statement->fetchAll(PDO::FETCH_OBJ);
		if(!$allRecords){
			return [];
		}
		$results=[];
		foreach ($allRecords as $cache) {
			if ($this->currentTime() >= $cache->expiration) {
				$this->forget($key);
			}else{
				$results[$cache->path]=true;
			}
		}
		return $results;
	}
	public function isCompleted($key){
		$statement=$this->pdo->prepare("SELECT * FROM completed WHERE path=? limit 1");
		$statement->bindValue(1,$key);
		$statement->execute();
		$cache=$statement->fetch(PDO::FETCH_OBJ);
		if(!$cache){
			return false;
		}
		if ($this->currentTime() >= $cache->expiration) {
			$this->complete($key,false);
			return false;
		}
		return true;
	}
	public function complete($key, $completed=true, $seconds=3600){
		if($completed){
			$statement=$this->pdo->prepare(
				"insert into completed (`path`,`expiration`) values (?,?)"
			);
			if($seconds===-1){
				$expire=2147483647;
			}else{
				$expire=$this->currentTime()+$seconds;
			}
			$statement->bindValue(1,$key);
			$statement->bindValue(2,$expire);
			if(!$statement->execute()){
				$statement=$this->pdo->prepare("UPDATE completed SET expiration=:expiration  WHERE path=:key");
				$statement->bindValue(':key',$key);
				$statement->bindValue(':expiration',$expire);
				return $statement->execute();
			}else{
				return true;
			}
		}else{
			return $this->forgetComplete($key);
		}
	}
	protected function forgetComplete($key){
		$key.='%';
		$statement=$this->pdo->prepare("DELETE FROM completed WHERE path like ?");
		$statement->bindValue(1,$key);
		return $statement->execute();
	}

	public function flush(){
		$statement=$this->pdo->prepare("DELETE FROM cache");
		$statement->execute();
		return true;
	}

	public function clearExpires(){
		$statement1=$this->pdo->prepare("DELETE FROM cache WHERE expiration < ?");
		$statement2=$this->pdo->prepare("DELETE FROM completed WHERE expiration < ?");
		$statement1->bindValue(1,$this->currentTime());
		$statement2->bindValue(1,$this->currentTime());
		$statement1->execute();
		$statement2->execute();
	}
	public function getPdo(){
		return $this->pdo;
	}
	public function pathQuery($key, $deep=1){
		$like=$key.'%';
		$notLike='';
		if($deep>0){
			$notLike=$like;
			while($deep-->0){
				$notLike.='/%';
			}
		}
		if($notLike){
			$statement=$this->pdo->prepare("SELECT * FROM cache WHERE key like ? and key not like ?");
			$statement->bindValue(2,$notLike);
		}else{
			$statement=$this->pdo->prepare("SELECT * FROM cache WHERE key like ?");
		}
		$statement->bindValue(1,$like);
		//$statement->bindValue(2,$notLike);
		$statement->execute();
		$allRecords=$statement->fetchAll(PDO::FETCH_OBJ);
		if(!$allRecords){
			return [];
		}
		$results=[];
		foreach ($allRecords as $cache) {
			if ($this->currentTime() >= $cache->expiration) {
				$this->forget($key);
			}else{
				$results[$cache->key]=unserialize($cache->value);
			}
		}
		return $results;
	}
	/**
	 * Get the current system time as a UNIX timestamp.
	 *
	 * @return int
	 */
	protected function currentTime()
	{
		return time();
	}
}
