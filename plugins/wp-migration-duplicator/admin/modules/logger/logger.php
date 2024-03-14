<?php

/**
 * @author  advename
 * @since   October 27, 2019
 * @link    https://github.com/advename/Simple-PHP-Logger
 * @license MIT
 * @version 1.0.0
 * 
 * Description:
 * The simple php logger is a single-file logwriter with the features of:
 * - single file
 * - singleton pattern
 * - six log levels (info, notice, debug, warning, error, fatal)
 * - logs the line where the Logger method is executed (good for troubleshooting)
 * - logs the relative filepath of the source file, not the required one (good for troubleshooting)
 *
 */
if (!defined('ABSPATH')) {
    exit;
}
class Webtoffe_logger
{
    
    /**
     * $log_file - path and log file name
     * @var string
     */
    protected static $log_file;
    public static $history_id='';
    private static $file_path='';
	private static $file_pointer=null;
	private static $mode='';
        public static $log_dir=WP_CONTENT_DIR.'/webtoffee_migrations/logs';

    /**
     * $file - file
     * @var string
     */
    protected static $file;

    /**
     * $options - settable options
     * @var array $dateFormat of the format used for the log.txt file; $logFormat used for the time of a single log event
     */
    protected static $options = [
        'dateFormat' => 'd-M-Y',
        'logFormat' => 'H:i:s d-M-Y'
    ];

    private static $instance;

    /**
     * Create the log file
     * @param string $log_file - path and filename of log
     * @param array $params - settable options
     */
    public static function createLogFile()
    {
        $time = date(static::$options['dateFormat']);
        static::$log_file =  Wp_Migration_Duplicator::$backup_dir . "/logs/log.log";
      
        //Check if directory /logs exists
        if (!file_exists(Wp_Migration_Duplicator::$backup_dir . '/logs')) {
            mkdir(Wp_Migration_Duplicator::$backup_dir . '/logs', 0777, true);
        }

        //Create log file if it doesn't exist.
        if (!file_exists(static::$log_file)) {
            fopen(static::$log_file, 'w') or exit("Can't create {static::log_file}!");
        }

        //Check permissions of file.
        if (!is_writable(static::$log_file)) {
            //throw exception if not writable
            throw new Exception("ERROR: Unable to write to file!", 1);
        }
    }

    /**
     * Set logging options (optional)
     * @param array $options Array of settable options
     * 
     * Options:
     *  [
     *      'dateFormat' => 'value of the date format the .txt file should be saved int'
     *      'logFormat' => 'value of the date format each log event should be saved int'
     *  ]
     */
    public static function setOptions($options = [])
    {
        static::$options = array_merge(static::$options, $options);
    }

    /**
     * Info method (write info message)
     * 
     * Used for e.g.: "The user example123 has created a post".
     * 
     * @param string $message Descriptive text of the debug
     * @param string $context Array to expend the message's meaning
     * @return void
     */
    public static function info($message, array $context = [])
    {
        // grab the line and file path where the log method has been executed ( for troubleshooting )
        $bt = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);

        //execute the writeLog method with passing the arguments
        static::writeLog([
            'message' => $message,
            'bt' => $bt,
            'severity' => 'INFO',
            'context' => $context
        ]);
    }

    /**
     * Notice method (write notice message)
     * 
     * Used for e.g.: "The user example123 has created a post".
     * 
     * @param string $message Descriptive text of the debug
     * @param string $context Array to expend the message's meaning
     * @return void
     */
    public static function notice($message, array $context = [])
    {
        // grab the line and file path where the log method has been executed ( for troubleshooting )
        $bt = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);

        //execute the writeLog method with passing the arguments
        static::writeLog([
            'message' => $message,
            'bt' => $bt,
            'severity' => 'NOTICE',
            'context' => $context
        ]);
    }

    /**
     * Debug method (write debug message)
     * 
     * Used for debugging, could be used instead of echo'ing values
     * 
     * @param string $message Descriptive text of the debug
     * @param string $context Array to expend the message's meaning
     * @return void
     */
    public static function debug($message, array $context = [])
    {

        // grab the line and file path where the log method has been executed ( for troubleshooting )
        $bt = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);

        //execute the writeLog method with passing the arguments
        static::writeLog([
            'message' => $message,
            'bt' => $bt,
            'severity' => 'DEBUG',
            'context' => $context
        ]);
    }

    /**
     * Warning method (write warning message)
     * 
     * Used for warnings which is not fatal to the current operation
     * 
     * @param string $message Descriptive text of the warning
     * @param string $context Array to expend the message's meaning
     * @return void
     */
    public static function warning($message, array $context = [])
    {
        // grab the line and file path where the log method has been executed ( for troubleshooting )
        $bt = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);

        //execute the writeLog method with passing the arguments
        static::writeLog([
            'message' => $message,
            'bt' => $bt,
            'severity' => 'WARNING',
            'context' => $context
        ]);
    }

    /**
     * Error method (write error message)
     * 
     * Used for e.g. file not found,...
     * 
     * @param string $message Descriptive text of the error
     * @param string $context Array to expend the message's meaning
     * @return void
     */
    public static function error($message, array $context = [])
    {
        // grab the line and file path where the log method has been executed ( for troubleshooting )
        $bt = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);

        //execute the writeLog method with passing the arguments
        static::writeLog([
            'message' => $message,
            'bt' => $bt,
            'severity' => 'ERROR',
            'context' => $context
        ]);
    }
    
    /**
	* 	create a history entry before starting export/import
	*	@param 	$file_name String export/import file name
	*	@param 	$form_data Array export/import formdata
	*	@param 	$action String export or import
	*	@return $history_id Int DB id if success otherwise zero
	*/
	public static function create_history_entry($file_name, $action)
	{
		global $wpdb;

		$tb=$wpdb->prefix.'wt_mgdp_action_history';
		$insert_data=array(
			'item_type'=>$action, //item type Eg: product
			'file_name'=>$file_name, //export/import file name
			'created_at'=>time(), //craeted time
		);
		$insert_data_type=array(
			'%s','%s','%d'
		);
		
		$insert_response=$wpdb->insert($tb, $insert_data, $insert_data_type);
		
		/* check for auto delete */
//		self::auto_delete_history_entry();

		if($insert_response) //success
		{
			return $wpdb->insert_id;
		}
		return 0;
	}
        
        public static function update_history_entry($history_id, $update_data, $update_data_type)
	{
		global $wpdb;
		//updating the data
		$tb=$wpdb->prefix.'wt_mgdp_action_history';
		$update_where=array(
			'id'=>$history_id
		);
		$update_where_type=array(
			'%d'
		);
		if($wpdb->update($tb, $update_data, $update_where, $update_data_type, $update_where_type)!==false)
		{
			return true;
		}
		return false;
	}
              	/**
	* 	Taking history entry by ID
	*/
	public static function get_logname_entry_by_history_id($id)
	{
		global $wpdb;
		$tb=$wpdb->prefix.'wt_mgdp_action_history';
		if($id)
		{
			$where="=%d";
			$where_data=array($id);
		}
		$qry=$wpdb->prepare("SELECT file_name FROM $tb WHERE id".$where, $where_data);

		return $wpdb->get_var($qry); 
	}

    /**
     * Fatal method (write fatal message)
     * 
     * Used for e.g. database unavailable, system shutdown
     * 
     * @param string $message Descriptive text of the error
     * @param string $context Array to expend the message's meaning
     * @return void
     */
    public static function fatal($message, array $context = [])
    {
        // grab the line and file path where the log method has been executed ( for troubleshooting )
        $bt = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);

        //execute the writeLog method with passing the arguments
        static::writeLog([
            'message' => $message,
            'bt' => $bt,
            'severity' => 'FATAL',
            'context' => $context
        ]);
    }

    /**
     * Write to log file
     * @param array $args Array of message (for log file), line (of log method execution), severity (for log file) and displayMessage (to display on frontend for the used)
     * @return void
     */
    // public function writeLog($message, $line = null, $displayMessage = null, $severity)
    public static  function writeLog($args = [])
    {
        //Create the log file
        static::createLogFile();

        // open log file
        if (!is_resource(static::$file)) {
            static::openLog();
        }

        // // grab the url path
        // $path = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];

        //Grab time - based on the time format
        $time = date(static::$options['logFormat']);

        // Convert context to json
        $context = json_encode($args['context']);

        $caller = array_shift($args['bt']);
        $btLine = $caller['line'];
        $btPath = $caller['file'];

        // Convert absolute path to relative path (using UNIX directory seperators)
        $path = static::absToRelPath($btPath);

        // Create log variable = value pairs
        $timeLog = is_null($time) ? "[N/A] " : "[{$time}] ";
        $pathLog = is_null($path) ? "[N/A] " : "[{$path}] ";
        $lineLog = is_null($btLine) ? "[N/A] " : "[{$btLine}] ";
        $severityLog = is_null($args['severity']) ? "[N/A]" : "[{$args['severity']}]";
        $messageLog = is_null($args['message']) ? "N/A" : "{$args['message']}";
        $contextLog = empty($args['context']) ? "" : "{$context}";

        // Write time, url, & message to end of file
        fwrite(static::$file, "{$timeLog}{$pathLog}{$lineLog}: {$severityLog} - {$messageLog} {$contextLog}" . PHP_EOL);

        // Close file stream
        static::closeFile();
    }

    /**
     * Open log file
     * @return void
     */
    private static function openLog()
    {
        $openFile = static::$log_file;
        // 'a' option = place pointer at end of file
        static::$file = fopen($openFile, 'a') or exit("Can't open $openFile!");
    }

    /**
     *  Close file stream
     */
    public static function closeFile()
    {
        if (static::$file) {
            fclose(static::$file);
        }
    }

    /**
     * Convert absolute path to relative url (using UNIX directory seperators)
     * 
     * E.g.:
     *      Input:      D:\development\htdocs\public\todo-list\index.php
     *      Output:     localhost/todo-list/index.php
     * 
     * @param string Absolute directory/path of file which should be converted to a relative (url) path
     * @return string Relative path
     */
    public static function absToRelPath($pathToConvert)
    {
        $pathAbs = str_replace(['/', '\\'], '/', $pathToConvert);
        $documentRoot = str_replace(['/', '\\'], '/', $_SERVER['DOCUMENT_ROOT']);
        return $_SERVER['SERVER_NAME'] . str_replace($documentRoot, '', $pathAbs);
    }

    /**
     * The Singleton's constructor should always be private to prevent direct
     * construction calls with the `new` operator.
     */
    protected function __construct()
    { }

    /**
     * Singletons should not be cloneable.
     */
    protected function __clone()
    { }

    /**
     * Singletons should not be restorable from strings.
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * The Singleton's constructor should always be private to prevent direct
     * construction calls with the `new` operator.
     */
    private function __destruct()
    { }
    
    
    public static function init($file_path, $mode="a+")
	{
		self::$file_path=$file_path;
		self::$mode=$mode;
		self::$file_pointer=@fopen($file_path, $mode);
	}
	public static function write_row($text, $is_writing_finished=false)
	{
		if(is_null(self::$file_pointer))
		{
			return;
		}
		@fwrite(self::$file_pointer, $text.PHP_EOL);
		if($is_writing_finished)
		{
			self::close_file_pointer();
		}
	}
	public static function close_file_pointer()
	{
		if(self::$file_pointer!=null)
		{
			fclose(self::$file_pointer);
		}
	}
	
	/**
	*	Debug log writing function
	*	@param string 	$post_type 		post type
	*	@param string 	$action_type	action type
	*	@param mixed 	$data			array/string of data to write
	*/
	public static function write_log($action_type, $data)
	{
            //if(Wt_Import_Export_For_Woo_Common_Helper::get_advanced_settings('enable_import_log')==1){
		/**
		*	Checks log file created for the current day
		*/
            if($action_type == 'Fatal'){
            $randomstring = Wp_Migration_Duplicator_Security_Helper::generateRandomString();

            $fatal_error_log_file =  Wp_Migration_Duplicator::$backup_dir . "/logs/" . $randomstring . "FATAL_ERROR_LOG_" . date("d_M_Y") . ".log";
            //Check if directory /logs exists
            if (!file_exists(Wp_Migration_Duplicator::$backup_dir . '/logs')) {
                mkdir(Wp_Migration_Duplicator::$backup_dir . '/logs', 0777, true);
            }
            if (!file_exists($fatal_error_log_file)) {
                touch($fatal_error_log_file);
            }
            $file_name = $randomstring . "FATAL_ERROR_LOG_" . date("d_M_Y") . ".log";
            }else{
                $historyid = self::$history_id ? self::$history_id : get_option('wp_mgdp_log_id',true);
		$old_file_name=self::get_logname_entry_by_history_id($historyid);
		if(!$old_file_name) 
		{
                        delete_option('wp_mgdp_log_id');
			$file_name=self::generate_file_name($action_type, self::$history_id);
                        $update_data=array(
				'file_name'=>$file_name,
			);
			$update_data_type=array(
				'%s',
			);
			self::update_history_entry(self::$history_id, $update_data, $update_data_type);
                        add_option('wp_mgdp_log_id',self::$history_id);
		}else
		{
			$file_name=$old_file_name;
		}
            }
		$file_path=self::get_file_path($file_name);
		self::init($file_path);
		$date_string=date_i18n('m-d-Y @ H:i:s');
		if(is_array($data))
		{
			foreach ($data as $value) 
			{
				self::write_row($date_string." - ".maybe_serialize($value));
			}
		}else
		{
			self::write_row($date_string." - ".$data);	
		}
            //}
	}

	/**
	*	Import response log
	*	@param array 	$data		array/string of import response data
	*	@param string 	$file_path	import log file
	*/
	public static function write_import_log($data_arr, $file_path)
	{
		self::init($file_path);
		foreach($data_arr as $key => $data)
		{
			self::write_row(maybe_serialize($data));
		}
		self::close_file_pointer();
	}
        
        /**
	* 	Get given temp file path.
	*	If file name is empty then file path will return
	*/
	public static function get_file_path($file_name="")
	{
		if(!is_dir(self::$log_dir))
        {
            if(!mkdir(self::$log_dir, 0700))
            {
            	return false;
            }else
            {
            	$files_to_create=array('.htaccess' => 'deny from all', 'index.php'=>'<?php // Silence is golden');
		        foreach($files_to_create as $file=>$file_content)
		        {
		        	if(!file_exists(self::$log_dir.'/'.$file))
			        {
			            $fh=@fopen(self::$log_dir.'/'.$file, "w");
			            if(is_resource($fh))
			            {
			                fwrite($fh, $file_content);
			                fclose($fh);
			            }
			        }
		        } 
            }
        }
        return self::$log_dir.'/'.$file_name;
	}
        
  
	/**
	*	Generate log file name
	*
	*/
	public static function generate_file_name( $action_type='', $history_id='')
	{
        $randomstring = Wp_Migration_Duplicator_Security_Helper::generateRandomString();

		$arr=array($history_id);
        $arr[]=$randomstring;
		$arr[]=$action_type;
		$arr[]=date('Y-m-d_h_i_s_A'); /* if changing this format please consider `check_log_exists_for_entry` method */

		$arr=array_filter($arr);
		return implode("_", $arr).'.log';
	}
}