<?php
/**
 * The Database class
 *
 * @package		LetAProDoIT.Helpers
 * @filename	Database.php
 * @version		2.0.0
 * @author		Sharron Denice, Let A Pro Do IT! (www.letaprodoit.com)
 * @copyright	Copyright 2016 Let A Pro Do IT! (www.letaprodoit.com). All rights reserved
 * @license		APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @brief		Class to store Connection objects
 *
 */
class LAPDI_Database
{
	public static $connection;

	/**
	 * The DB's name
	 *
	 * @var string
	 */
	public $name;
	/**
	 * The DB's user
	 *
	 * @var string
	 */
	public $user;
	/**
	 * The DB's user's password
	 *
	 * @var string
	 */
	public $pass;
	/**
	 * The DB's host
	 *
	 * @var string
	 */
	public $host;
	/**
	 * The DB's port
	 *
	 * @var integer
	 */
	public $port;
	/**
	 * The DB's type
	 *
	 * @var string
	 */
	public $type;

    /**
     * Constructor
     * @throws Exception
     */
	public function __construct($db_key, $select_db = true)
	{		
        try 
        {
    		if (array_key_exists($db_key, LAPDI_Config::get('app.databases')))
    		{
    			$db_conn = LAPDI_Config::get('app.databases.' . $db_key);
    			$this->Connect($db_conn, $select_db);
    		}
        }
        catch (Exception $e) 
        {
            throw new Exception("Error Occurred in " . __FUNCTION__ . ": " . $e->getMessage() . PHP_EOL);
        }
	}

    /**
     *
     * @throws Exception
     *
     * @return resource
     */
	public function Connect($db_conn, $select_db = true)
	{
		$db_found = false;
		
    	try
    	{
    		if (!empty($db_conn))
    		{
    			$this->name = 	$db_conn->name;
    			$this->host =   $db_conn->host;
    			$this->user = 	$db_conn->user;
    			$this->pass = 	$db_conn->pass;
    			$this->port =	$db_conn->port;
    			$this->type = 	$db_conn->type;

    			switch ($this->type)
    			{
    				case LAPDI_Settings::$database_mysql:
    				case LAPDI_Settings::$database_mysqli:
                        self::$connection = mysqli_connect($this->host, $this->user, $this->pass, "", $this->port);
    					
    					if (self::$connection)
    					{
    						$connected = true;
    						
    						if (mysqli_select_db(self::$connection, $this->name))
    						{
    							$db_found = true;
    						}
    					}
    					break;
    				case LAPDI_Settings::$database_mongo:
    					self::$connection = new MongoClient("mongodb://{$this->user}:{$this->pass}@{$this->host}:{$this->port}");
    					
    					if (self::$connection)
    					{
    						$connected = true;

                            if ($select_db)
                            {
                                if (mysqli_select_db(self::$connection, $this->name))
                                    $db_found = true;
                            }
                            else
                                $db_found = true;
    					}
    					break;
        			case LAPDI_Settings::$database_mssql:
    					self::$connection = mssql_connect($this->host, $this->user, $this->pass);
    					
    					if (self::$connection)
    					{
    						$connected = true;
    						
    						if (mssql_select_db($this->name, self::$connection))
    						{
    							$db_found = true;
    						}
    					}
        				break;
    				default:
    					break;
    			}			
    		}
    		
            if (!$connected)
    		{
    			throw new Exception("Error Occurred: Could not connect to the database. Please edit LAPDI_Settings with your database configuration.");
    		}
    		else if (!$db_found)
    		{
    			throw new Exception("Error Occurred: Could not find the specified database ".$this->name.". Please edit LAPDI_Settings.");
    		}
    	}
        catch (Exception $e) 
        {
            throw new Exception("Error Occurred in " . __FUNCTION__ . ": " . $e->getMessage() . PHP_EOL);
        }
	}

    /**
     * @param unknown $result
     *
     * @throws Exception
     *
     * @return boolean|number
     */
	public function FetchArray($result)
	{
		$row = null;
		
    	try
    	{
    		switch ($this->type)
    		{
    			case LAPDI_Settings::$database_mysql:
     			case LAPDI_Settings::$database_mysqli:
    				$row = mysqli_fetch_array($result);
    				break;
    			case LAPDI_Settings::$database_mssql:
    				$row = mssql_fetch_array($result);
    				break;
    			default:
    				break;
    		}
    		
    		if ($result == -1)
    		{
    			return false;
    		}
    		
    		return $row;
    	}
        catch (Exception $e) 
        {
            throw new Exception("Error Occurred in " . __FUNCTION__ . ": " . $e->getMessage() . PHP_EOL);
        }
	}

    /**
     * @param unknown $result
     *
     * @throws Exception
     *
     * @return boolean|number
     */
	public function FetchHash($result)
	{
		$row = null;
		
    	try
    	{
    		switch ($this->type)
    		{
    			case LAPDI_Settings::$database_mysql:
    			case LAPDI_Settings::$database_mysqli:
    				$row = mysqli_fetch_assoc($result);
    				break;
    			case LAPDI_Settings::$database_mssql:
    				$row = mssql_fetch_assoc($result);
    				break;
    			default:
    				break;
    		}
    		if ($result == -1)
    		{
    			return false;
    		}
    		
    		return $row;
    	}
        catch (Exception $e) 
        {
            throw new Exception("Error Occurred in " . __FUNCTION__ . ": " . $e->getMessage() . PHP_EOL);
        }
	}

    /**
     * @param unknown $result
     *
     * @throws Exception
     *
     * @return boolean|number
     */
	public function FetchObject($result)
	{
		$row = null;
		
    	try
    	{
    		switch ($this->type)
    		{
    			case LAPDI_Settings::$database_mysql:
     			case LAPDI_Settings::$database_mysqli:
    				$row = mysqli_fetch_object($result);
    				break;
    			case LAPDI_Settings::$database_mssql:
    				$row = mssql_fetch_object($result);
    				break;
    			default:
    				break;
    		}
     		if ($result == -1)
    		{
    			return false;
    		}
    		
    		return $row;
   	    }
        catch (Exception $e) 
        {
            throw new Exception("Error Occurred in " . __FUNCTION__ . ": " . $e->getMessage() . PHP_EOL);
        }
	}

    /**
     * @param unknown $query
     * @param unknown $connection
     *
     * @throws Exception
     *
     * @return number
     */
	public function InsertOrUpdate($query)
	{
		$id = null;
		
    	try
    	{
    		switch ($this->type)
    		{
    			case LAPDI_Settings::$database_mysql:
    			case LAPDI_Settings::$database_mysqli:
    				$result = mysqli_query(self::$connection, $query);
    				$id = $this->LastInsertID();
    				break;
    			case LAPDI_Settings::$database_mssql:
    				$result = mssql_query($query, self::$connection);
    				$id = $this->LastInsertID();
    				break;
    			default:
    				break;
    		}
    		
		    return $id;
    	}
        catch (Exception $e) 
        {
            throw new Exception("Error Occurred in " . __FUNCTION__ . ": " . $e->getMessage() . PHP_EOL);
        }
	}


    /**
     * @param string $query
     *
     * @throws Exception
     *
     * @return string
     */
    public function PrepareStatement($query)
    {
		$sql = $query;
		
    	try
    	{
    		switch ($this->type)
    		{
    			case LAPDI_Settings::$database_mysql:
     			case LAPDI_Settings::$database_mysqli:
                    break;
    			case LAPDI_Settings::$database_mssql:
    				$sql = preg_replace("/\`(.*?)\`/", "[$1]", $sql);
    				break;
    			default:
    				break;
    		}
    		
		    return $sql;
    	}
        catch (Exception $e) 
        {
            throw new Exception("Error Occurred in " . __FUNCTION__ . ": " . $e->getMessage() . PHP_EOL);
        }
    }

    /**
     * @param unknown $query
     * @param unknown $connection
     *
     * @throws Exception
     *
     * @return number
     */
	public function LastInsertID()
	{
		$id = null;
		
    	try
    	{
    		// do NOT use mysql_insert_id here it does not handle BIGINT
    		switch ($this->type)
    		{
    			case LAPDI_Settings::$database_mysql:
    			case LAPDI_Settings::$database_mysqli:
    				$result = mysqli_query(self::$connection, 'SELECT LAST_INSERT_ID();');
    				$row = mysqli_fetch_array($result);
    				$id = $row[0];
    				break;
    			case LAPDI_Settings::$database_mssql:
    				$result = mssql_query('select @@IDENTITY;', self::$connection);
    				$row = mssql_fetch_array($result);
    				$id = $row[0];
    				break;
    			default:
    				break;
    		}
    		
		    return $id;
    	}
        catch (Exception $e) 
        {
            throw new Exception("Error Occurred in " . __FUNCTION__ . ": " . $e->getMessage() . PHP_EOL);
        }
	}

    /**
     * @param unknown $cursor
     *
     * @throws Exception
     *
     * @return multitype
     */
	public function Read($cursor)
	{
		$read = null;
		
    	try
    	{
    		switch ($this->type)
    		{
    			case LAPDI_Settings::$database_mysql:
    			case LAPDI_Settings::$database_mysqli:
    				$read = mysqli_fetch_assoc($cursor);
    				break;
    			case LAPDI_Settings::$database_mssql:
    				$read = mssql_fetch_assoc($cursor);
    				break;
    			default:
    				break;
    		}
    		
		    return $read;
    	}
        catch (Exception $e) 
        {
            throw new Exception("Error Occurred in " . __FUNCTION__ . ": " . $e->getMessage() . PHP_EOL);
        }
	}

    /**
     * @param unknown $query
     * @param unknown $connection
     *
     * @throws Exception
     *
     * @return resource
     */
	public function Reader($query)
	{
		$cursor = null;
		
    	try
    	{
    		switch ($this->type)
    		{
    			case LAPDI_Settings::$database_mysql:
     			case LAPDI_Settings::$database_mysqli:
    				$cursor = mysqli_query(self::$connection, $query);
    				break;
    			case LAPDI_Settings::$database_mssql:
    				$cursor = mssql_query($query, self::$connection);
    				break;
    			default:
    				break;
    		}
    		
		    return $cursor;
    	}
        catch (Exception $e) 
        {
            throw new Exception("Error Occurred in " . __FUNCTION__ . ": " . $e->getMessage() . PHP_EOL);
        }
	}

    /**
     * @param unknown $query
     *
     * @throws Exception
     *
     * @return boolean|number
     */
	public function RunQuery($query)
	{
		$result = null;
		
    	try
    	{
    		switch ($this->type)
    		{
    			case LAPDI_Settings::$database_mysql:
    			case LAPDI_Settings::$database_mysqli:
    				$result = mysqli_query(self::$connection, $query);
    				break;
    			case LAPDI_Settings::$database_mssql:
    				$result = mssql_query($query, self::$connection);
    				break;
    			default:
    				break;
    		}
    		if ($result == -1)
    		{
    			return false;
    		}
    		
    		return $result;
    	}
        catch (Exception $e) 
        {
            throw new Exception("Error Occurred in " . __FUNCTION__ . ": " . $e->getMessage() . PHP_EOL);
        }
	}

    /**
     * @param unknown $query
     * @param unknown $connection
     *
     * @throws Exception
     *
     * @return boolean|number
     */
	public function NonQuery($query)
	{
		$result = null;
		
    	try
    	{
    		switch ($this->type)
    		{
    			case LAPDI_Settings::$database_mysql:
    			case LAPDI_Settings::$database_mysqli:
    				mysqli_query(self::$connection, $query);
    				$result = mysqli_affected_rows(self::$connection);
    				break;
    			case LAPDI_Settings::$database_mssql:
    				mssql_query($query, self::$connection);
    				$result = mssql_rows_affected(self::$connection);
    				break;
    			default:
    				break;
    		}
    		
     		if ($result == -1)
    		{
    			return false;
    		}
    		
    		return $result;
        }
        catch (Exception $e) 
        {
            throw new Exception("Error Occurred in " . __FUNCTION__ . ": " . $e->getMessage() . PHP_EOL);
        }
	}

    /**
     * @param unknown $query
     * @param unknown $connection
     *
     * @throws Exception
     *
     * @return number
     */
	public function Query($query)
	{
		$rows = null;
		
    	try
    	{
    		switch ($this->type)
    		{
    			case LAPDI_Settings::$database_mysql:
    			case LAPDI_Settings::$database_mysqli:
    				$result = mysqli_query(self::$connection, $query);
    				$rows = mysqli_num_rows($result);
    				break;
    			case LAPDI_Settings::$database_mssql:
    				$result = mssql_query($query, self::$connection);
    				$rows = mssql_num_rows($result);
    				break;
    			default:
    				break;
    		}
    		
		    return $rows;
    	}
        catch (Exception $e) 
        {
            throw new Exception("Error Occurred in " . __FUNCTION__ . ": " . $e->getMessage() . PHP_EOL);
        }
	}

    /**
     * @param unknown $query
     * @param unknown $connection
     *
     * @throws Exception
     *
     * @return number
     */
    public function MultiQuery($query)
    {
        $result = null;

        try
        {
            switch ($this->type)
            {
                case LAPDI_Settings::$database_mysql:
                case LAPDI_Settings::$database_mysqli:
                    $result = mysqli_multi_query(self::$connection, $query);
                    break;
                case LAPDI_Settings::$database_mssql:
                    $result = mssql_query($query, self::$connection);
                    break;
                default:
                    break;
            }

            return $result;
        }
        catch (Exception $e)
        {
            throw new Exception("Error Occurred in " . __FUNCTION__ . ": " . $e->getMessage() . PHP_EOL);
        }
    }
}


/**
 * TSP_Database
 *
 * @since 1.0.0
 *
 * @deprecated 2.0.0 Please use LAPDI_Database instead
 *
 * @return void
 *
 */
class TSP_Database extends LAPDI_Database
{

}// end class
