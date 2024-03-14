<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //



// Portal Abstraction Layer For Wordpress
// i_pal_wordpress.php

$dms_mysqli_db = 0;

// Database specific function calls
class dms_pal_db
{
	var $num_rows;

    //private $use_mysqli = false;

    function connect()
    {
//print ("CONNECT");
/*
        if(function_exists ('mysqli_connect')) $this->use_mysqli = true;

        if($this->use_mysqli == true)
            {
*/

        global $dms_mysqli_db;

        $db_host = DB_HOST;

        if(strpos($db_host,":") == false)
            {
            //  Connect without port number
            $dms_mysqli_db = new mysqli($db_host,DB_USER,DB_PASSWORD,DB_NAME);
            }
        else
            {
            //  Connect with port number
            $db_info = explode(":",$db_host);

            $db_host = $db_info[0];
            $db_port = $db_info[1];

            $dms_mysqli_db = new mysqli($db_host,DB_USER,DB_PASSWORD,DB_NAME,$db_port);
            }

        if($dms_mysqli_db->connect_errno > 0)
            {
            die('Unable to connect to database [' . $db->connect_error . ']');
            }



/*
            }
        else
            {
            $conn = mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
            mysql_select_db(DB_NAME,$conn);
            }
*/
    }

	function getarray($result)
	{
        return mysqli_fetch_array($result);

/*
        if($this->use_mysqli == true)
            {
            return mysqli_fetch_array($result);
            }

		return mysql_fetch_array($result);
*/
	}

	function getid()
	{
        global $dms_mysqli_db;

        return $dms_mysqli_db->insert_id;

/*
        if($this->use_mysqli == true)
            {
            global $dms_mysqli_db;

            return $dms_mysqli_db->insert_id;
            }

		return mysql_insert_id();
*/
	}

	function getobject($result)
	{
        $result->fetch_object();

/*
        if($this->use_mysqli == true)
            {
            $result->fetch_object();
            }

		return mysql_fetch_object($result);
*/
	}

	function getnumrows($result = "")
	{
		return $this->num_rows;
	}

    function mysql_extension()
    {
        return ("MySQLi");
/*
        if($this->use_mysqli == TRUE) return ("MySQLi");
        else return ("MySQL");
  */
    }

	function prefix($table)
	{
        global $wpdb;

//echo "PREFIX:";
//echo $wpdb->prefix;
        //return ($wpdb->prefix . $table);    //  Use this....
		return ($table);
	}

	function query($query, $instruct = "")
	{
//print "A";
    global $dms_mysqli_db;

//print gettype($dms_mysqli_db);

        if(gettype($dms_mysqli_db) == "integer")
        {
        //print "INTEGER";
            if($dms_mysqli_db == 0)
            {
                //print "CONNECT";
                $this->connect();
            }
        }

        //  Sanitize query.
//print "B";
        //  Remove ;
        $query = str_replace(";","",$query);
        // $position = stripos($query,";");
        // if($position !== false) $query = substr($query,0,$position);

        //   Remove --
        //$query = str_replace("--","",$query);    Screws up some websites.

        //  Remove exec and everything after
        $test_query = strtolower($query);
        $position = stripos($query,"exec");
        if($position !== false) $query = substr($query,0,$position);

        //  Remove Drop Table and everything after
        $test_query = strtolower($query);
        $position = stripos($query,"drop table");
        if($position !== false) $query = substr($query,0,$position);

        //  Remove \
        //$query = str_replace("\\","",$query);     Causes problems with Windows servers.
        //var_dump ($query);

//print "C";
        //global $dms_mysqli_db;
        $result = $dms_mysqli_db->query($query);
//print "D";

        if(
            ($result != FALSE) &&
            (stripos($query, "SELECT") == 0) &&
            (stristr($query, "SELECT") != false ))
            {
            $this->num_rows = $result->num_rows;
            }
        else
            {
            $this->num_rows = 0;
            }
//print "E";
        if ( ($this->num_rows == 1) && (strlen($instruct) > 0 ) )
            {
            $result = mysqli_fetch_object($result);

            if($instruct == "ROW") return $result;
            $result = $result->$instruct;
            }

        return $result;
    }
}


$dmsdb = new dms_pal_db();




// Portal specific function calls
class dms_pal_group
{
	var $grp_source;

	function grp_details($grp_id)
		{
			return $this->dms_grp_details($grp_id);
		}

	function grp_list($usr_id = 0)
		{
		global $dms_user_id;

		if($usr_id == 0) $usr_id = $dms_user_id;

			return $this->portal_grp_list($usr_id);
		}

	function grp_list_all()
		{
			return $this->portal_grp_list_all();
		}

	function grp_create($grp_name, $grp_descript = "")
		{
			return $this->portal_grp_create($grp_name,$grp_descript);
		}

	function grp_delete($grp_id)
		{
			return $this->portal_grp_delete($grp_id);
		}

    function grp_rename($grp_id,$grp_name)
        {
            return $this->portal_grp_rename($grp_id,$grp_name);
        }

	function usr_list($grp_id)
		{
			return $this->portal_usr_list($grp_id);
		}

	function usr_list_all()
		{
			return $this->portal_usr_list_all();
		}


	function usr_add($grp_id, $usr_id)
		{
			$this->portal_usr_add($grp_id,$usr_id);
		}

	function usr_delete($grp_id, $usr_id)
		{
			$this->portal_usr_delete($grp_id,$usr_id);
		}

	function usr_delete_all($grp_id)
		{
			$this->portal_usr_delete_all($grp_id);
		}

	// "Private", portal specific functions
	function dms_grp_details($grp_id)
		{
		global $dmsdb;

		$details = array();

		$query = "SELECT group_name,group_description,group_type FROM ".$dmsdb->prefix("dms_groups")." WHERE group_id='".$grp_id."'";
		$result = $dmsdb->query($query,"ROW");

		$details['name'] = $result->group_name;
		$details['descript'] = $result->group_description;
		$details['type'] = $result->group_type;

		return $details;
		}

	function portal_grp_list($usr_id = 0)
		{
		global $dmsdb;

		$query = "SELECT group_id FROM ".$dmsdb->prefix("dms_groups_users_link")." WHERE user_id='".$usr_id."'";
		$result = $dmsdb->query($query);

		$index = 0;
		while($result_data = $dmsdb->getarray($result))
			{
			$group_list[$index]=$result_data['group_id'];
			$index++;
			}

		$group_list['num_rows'] = $index--;

		return $group_list;
		}

	function portal_grp_list_all()
		{
		global $dmsdb;
		$group_list = array();

		$query = "SELECT group_id,group_name FROM ".$dmsdb->prefix("dms_groups");
		$result = $dmsdb->query($query);

		while($result_data = $dmsdb->getarray($result))
			{
			$group_list[$result_data['group_id']]=$result_data['group_name'];
			}

		return $group_list;
		}

	function portal_grp_create($grp_name,$grp_descript)
		{
		global $dmsdb;
		$query  = "INSERT INTO ".$dmsdb->prefix("dms_groups")." ";
		$query .= "(group_name,group_description) VALUES ('".$grp_name."','".$grp_descript."')";
		$dmsdb->query($query);

		return $dmsdb->getid();
		}

	function portal_grp_delete($grp_id)
		{
		global $dmsdb;
		$query  = "DELETE FROM ".$dmsdb->prefix("dms_groups")." ";
		$query .= "WHERE group_id='".$grp_id."'";
		$dmsdb->query($query);


//  SHOULD ENTRIES IN GROUPS_USERS_LINK BE DELETED AS WELL??
		}


    function portal_grp_rename($grp_id,$grp_name)
        {
        global $dmsdb;
        $query  = "UPDATE ".$dmsdb->prefix("dms_groups")." SET group_name='".$grp_name."' ";
        $query .= "WHERE group_id='".$grp_id."'";
        $dmsdb->query($query);

        return(0);
        }

	function portal_usr_list($grp_id)
		{
		$user_list = array();
		global $dmsdb, $dms_global;

		$query  = "SELECT user_id FROM ".$dmsdb->prefix("dms_groups_users_link")." ";
		$query .= "WHERE group_id='".$grp_id."'";
		$result = $dmsdb->query($query);

		while($result_data = $dmsdb->getarray($result))
			{
			$query  = "SELECT user_login FROM ".$dms_global['wpdb_prefix']."users ";
			$query .= "WHERE ID='".$result_data['user_id']."'";
			$uname = $dmsdb->query($query,"user_login");

			$user_list[$result_data['user_id']] = $uname;
			}

		return $user_list;
		}

	function portal_usr_list_all()
		{
		$user_list = array();
		global $dmsdb, $dms_global;

		$query = "SELECT ID,user_login FROM ".$dms_global['wpdb_prefix']."users";
		$result = $dmsdb->query($query);

		while($result_data = $dmsdb->getarray($result))
			{
			$user_list[$result_data['ID']]=$result_data['user_login'];
			}

		return $user_list;
		}

	function portal_usr_add($grp_id, $usr_id)
		{
		global $dmsdb;

		$query  = "INSERT INTO ".$dmsdb->prefix("dms_groups_users_link")." ";
		$query .= "(group_id,user_id) VALUES ('".$grp_id."','".$usr_id."')";
		$dmsdb->query($query);
		}

	function portal_usr_delete($grp_id, $usr_id)
		{
		global $dmsdb;

		$query  = "DELETE FROM ".$dmsdb->prefix("dms_groups_users_link")." ";
		$query .= "WHERE group_id='".$grp_id."' AND user_id='".$usr_id."'";
		$dmsdb->query($query);
		}

	function portal_usr_delete_all($grp_id)
		{
		global $dmsdb;

		$query  = "DELETE FROM ".$dmsdb->prefix("dms_groups_users_link")." ";
		$query .= "WHERE group_id='".$grp_id."'";
		$dmsdb->query($query);
		}

}

$dms_groups = new dms_pal_group();



class dms_pal_user
{
    function admin()
        {
        global $dms_wp_admin_flag;

        if($dms_wp_admin_flag == TRUE) return TRUE;
        return FALSE;
        }

    function get_current_user_id()
        {
        global $dms_wp_user_id;
        return $dms_wp_user_id;
        }

	function get_email_addr($user_id)
		{
		global $dmsdb, $dms_global;

		$query  = "SELECT user_email FROM ".$dms_global['wpdb_prefix']."users ";
		$query .= "WHERE id='".$user_id."'";
		return $dmsdb->query($query,"user_email");
		}

	function get_username($user_id)
		{
		global $dmsdb, $dms_global;
		$query  = "SELECT user_login FROM ".$dms_global['wpdb_prefix']."users ";
		$query .= "WHERE id='".$user_id."'";
		return $dmsdb->query($query,"user_login");
		}

	function list_all()
		{
		$user_list = array();
		global $dmsdb, $dms_global;

		$query = "SELECT ID,user_login FROM ".$dms_global['wpdb_prefix']."users ";
		$result = $dmsdb->query($query);

		while($result_data = $dmsdb->getarray($result))
			{
			$user_list[$result_data['ID']]=$result_data['user_login'];
			}

		return $user_list;
		}
}

$dms_users = new dms_pal_user();



//  Class for WordPress-specific methods.
class dms_pal_wordpress
{
    function dms_dir()
        {
        return DMS_DIR;
        }

/*
    function dms_images()
        {
        return;
        }
*/

    function dms_querystring($query_item = "")
        {
        $query_string = $_SERVER['QUERY_STRING'];

        $result = array();
        $result = explode("&",$query_string);

        //  Returns entire array.
        if($query_item == "") return $result;

        //  Search for and return value for item requested.
        foreach ($result as $key=>$value)
            {
            $item = array();
            $item = explode ("=",$value);

            if($query_item == trim($item[0])) return trim($item[1]);
            }

        //  Nothing found, return 0
        return 0;
        }

    function dms_url()
        {
        global $dms_pal_wp_url;

        $cms_url = dms_var_cache_get("cms_url");

        if($cms_url == 0) return $dms_pal_wp_url;
        return $cms_url;
        }

    function wp_prefix($table)
        {
        global $wpdb;

        return ($wpdb->prefix . $table);
        }

}

$dms_cms = new dms_pal_wordpress;

?>
