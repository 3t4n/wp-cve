<?php

defined('ABSPATH') or die('No script kiddies please!');

/*
* $excluded_path need to do logs more short. Log line show path to file. Without exclude it will show full physical path to file that can be quite long.
*
* $logger = new SirvLogger($path_to_plugin_dir, $excluded_path, $log_filename = null, $dir_to_logs = null, $is_append_logs_to_file = true)
*
* Default logs path: sirv/logs/
* Default log filename: debug.log
*
* Possible methods: debug, error, warning, info
*
* $logger->debug($msg, $var_name ='')->print($is_die=false);
* $logger->error($msg, $var_name = '')->write();
*
*
* Possible to use chaining style. Possible chaining methods: filename, mode, dir, dir_path, filepath
*
* $logger->debug($array_var, '$array_var')->filename('test.log')->write();
* $logger->debug($array_var, '$array_var')->filename('test.log')->mode('a+')->write();
* $logger->info($object_var, '$object_var')->dir('another_logs')->filename('info.log')->write();
* $logger->warning('Some text')->dir_path('/var/www/site/wp-content/plugins/plugin/somelogs')->write();
* $logger->warning($bool_var)->filepath('/var/www/site/wp-content/plugins/plugin/somelogs/log_name.log')->write();
*
* Quick methods. Write log in to main plugin folder
*
* qdebug($msg, $var_name = '');
* qprint($msg, $var_name = '', $is_die = false);
*
*
* Performance methods
*
* $logger->time_start($timer_name);
* //some code
* $logger->time_chank($timer_name, $chank_name = null);
* //some code
* $logger->time_end($timer_name, $decimals = 6);
*
* Possible to use multiple time mesuarments with diff $timer_name.
*
*
*
*/

//TODO: method dir_path add possibility to add end slash in the path
class SirvLogger
{
  protected $excluded_path = '';
  protected $plugin_dir_path = '';
  protected $logs_dir = 'logs';
  protected $logs_dir_path = '';
  protected $log_filename = 'debug.log';
  protected $performance_filename = 'performance.log';
  protected $network_filename = 'network.log';
  protected $plugin_filename = 'sirv.log';
  protected $log_filepath = '';
  protected $is_append_file = true;
  protected $timers = array();
  protected $time_chunk = 1;

  protected $current_logs_dir = null;
  protected $current_logs_dir_path = null;
  protected $current_log_filename = null;
  protected $current_log_filepath = null;
  protected $current_msg = null;
  protected $current_mode = null;


  public function __construct( $plugin_dir_path, $excluded_path='', $filename=null, $logs_dir = null, $is_append_file=true ){
    $this->plugin_dir_path = $plugin_dir_path;
    $this->excluded_path = $excluded_path;
    if( $filename ) $this->log_filename = $filename;
    if( $logs_dir ) $this->logs_dir = $logs_dir;
    $this->is_append_file = $is_append_file;

    $this->logs_dir_path = $this->get_logs_dir_path();
    $this->log_filepath = $this->get_log_filepath();

    if(! is_dir($this->logs_dir_path) ) mkdir($this->logs_dir_path, 0755);
  }


  protected function get_logs_dir_path(){
    $logs_dir = $this->get_value($this->logs_dir, $this->current_logs_dir);

    return $this->plugin_dir_path . $logs_dir;
  }


  protected function get_log_filepath(){
    $logs_dir_path = $this->get_value($this->logs_dir_path, $this->current_logs_dir_path);
    $filename = $this->get_value($this->log_filename, $this->current_log_filename);

    return $logs_dir_path . DIRECTORY_SEPARATOR . $filename;
  }


  protected function get_value( $value, $current_value ){
    return $current_value ? $current_value : $value;
  }


  protected function clear_current_state(){
    $this->current_logs_dir = null;
    $this->current_logs_dir_path = null;
    $this->current_log_filename = null;
    $this->current_log_filepath = null;
    $this->current_msg = null;
    $this->current_mode = null;
  }


  public function performance( $msg, $var_name = '', $func_lvl = 1 ){
    $this->current_msg = $this->get_log_msg($msg, $var_name, 'performance', $func_lvl);
    return $this;
  }


  public function debug( $msg, $var_name = '', $func_lvl = 1 ){
    $this->current_msg = $this->get_log_msg($msg, $var_name, 'debug', $func_lvl);
    return $this;
  }


  public function error( $msg, $var_name = '', $func_lvl = 1 ){
    $this->current_msg = $this->get_log_msg($msg, $var_name, 'error', $func_lvl);
    return $this;
  }


  public function info( $msg, $var_name = '', $func_lvl = 1 ){
    $this->current_msg = $this->get_log_msg($msg, $var_name, 'info', $func_lvl);
    return $this;
  }


  public function warning( $msg, $var_name = '', $func_lvl = 1 ){
    $this->current_msg = $this->get_log_msg($msg, $var_name, 'warning', $func_lvl);
    return $this;
  }


  public function qdebug( $msg, $var_name = '', $mode = 'a+', $func_lvl = 2 ){
    $this->debug($msg, $var_name, $func_lvl)->dir_path($this->plugin_dir_path)->mode($mode)->write();
  }


  public function qprint( $msg, $var_name= '', $is_die = false ){
    $this->debug($msg, $var_name, 2)->print($is_die);
  }


  protected function get_write_mode(){
    return $this->is_append_file ? 'a' : 'w';
  }


  protected function write_file( $filepath, $msg, $mode ){
    $fn = fopen($filepath, $mode);
    fwrite($fn, $msg . PHP_EOL);
    fclose($fn);

  }


  public function print( $is_die = false ){
    print($this->current_msg);

    if( $is_die ) die();
  }


  public function write(){
    $filepath = $this->get_current_filepath();
    $mode = $this->current_mode ? $this->current_mode : $this->get_write_mode();

    $this->write_file($filepath, $this->current_msg, $mode);

    $this->clear_current_state();
  }


  public function filename( $filename ){
    $this->current_log_filename = $filename;
    return $this;
  }


  public function mode( $mode ){
    $this->current_mode = $mode;
    return $this;
  }

  public function dir( $dir ){
    $this->current_logs_dir = $dir;
    return $this;
  }

  public function dir_path( $dir_path ){
    $this->current_logs_dir_path = $dir_path;
    return $this;
  }


  public function filepath( $filepath ){
    $this->current_log_filepath = $filepath;
    return $this;
  }


  protected function get_current_filepath(){
    $filepath = '';

    if( $this->current_log_filepath ){
      $filepath =  $this->current_log_filepath;
    }else if( $this->current_logs_dir_path ){
      $filepath = $this->get_log_filepath();
    }else{
      $this->current_logs_dir_path = $this->get_logs_dir_path();
      $filepath = $this->get_log_filepath();
    }

    if( $this->logs_dir_path == $this->current_logs_dir_path ) return $filepath;

    $file_dir = pathinfo($filepath, PATHINFO_DIRNAME);

    if(! is_dir($file_dir) ) mkdir($file_dir, 0755, true);

    return $filepath;

  }


  /*
  * $func_lvl need to get correct line if we run func from other func
  */
  protected function get_log_msg( $msg, $var_name = '', $log_lvl = 'debug', $func_lvl = 0 ){
    $log_line_data = array(
      "timestamp"  => time(),
      "file"  => '',
      "line"  => null,
      "level" => $log_lvl,
      "var_name" => $var_name,
      "msg"   => $msg,
    );

    $dbg_backtrace = debug_backtrace();
    $log_line_data['file'] = $dbg_backtrace[$func_lvl]['file'];
    $log_line_data['line'] = $dbg_backtrace[$func_lvl]['line'];

    return $this->format_log_data($log_line_data);
  }


  protected function format_log_data( $log_data ){
    $log_line = '';

    if(! empty($log_data) ){
      $log_line .= '[' . date('d-M-Y H:i:s e', $log_data['timestamp']) . "] ";
      $log_line .= "file: " . $this->get_short_path($log_data['file']);
      $log_line .= ":" . $log_data['line'] . " ";
      $log_line .= "[" . strtoupper($log_data['level']) . "] : ";
      if($log_data['var_name']){
        $log_line .= $log_data['var_name'] . ' => ';
      }
      $log_line .= $this->stringify_data($log_data['msg']);
    }

    return $log_line;
  }


  protected function stringify_data( $data ){
    return is_bool($data) ? var_export($data, true) : print_r($data, true);
  }


  protected function get_short_path( $path ){
    return str_replace($this->excluded_path, '', $path);
  }


  public function time_start( $timer_name ){

    if (! isset($this->timers[$timer_name]) ) {
      $this->timers[$timer_name] = microtime(true);
      //return $this->timers[$timer_name];
    }
    //return false;
  }


  public function time_chunk( $timer_name, $chunk_name = null, $decimals = 6 ){
    $chunk_time = microtime(true);

    if (isset($this->timers[$timer_name])) {
      $start = $this->timers[$timer_name];

      $chunk_name = $chunk_name ? $chunk_name : "Time chunk #$this->time_chunk";
      $this->time_chunk ++;

      $elapsed_chunk_time = number_format(($chunk_time - $start), $decimals);

      $this->performance($elapsed_chunk_time, "Timer name: $timer_name ($chunk_name)", 2)->filename($this->performance_filename)->write();

      return $elapsed_chunk_time;
    }

    return false;

  }


  public function time_end( $timer_name, $decimals = 6 ){
    $end = microtime(true);

    if( isset($this->timers[$timer_name]) ){
      $start = $this->timers[$timer_name];

      $elapsed_time = number_format(($end - $start), $decimals);
      unset($this->timers[$timer_name]);
      $this->time_chunk = 1;

      $this->performance($elapsed_time, "Timer name: $timer_name (END)", 2)->filename($this->performance_filename)->write();

      return $elapsed_time;
    }

    return false;
  }

}
