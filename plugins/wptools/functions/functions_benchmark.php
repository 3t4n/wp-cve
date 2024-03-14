<?php
/**
 * PHP Script to benchmark PHP and MySQL-Server
 *
 */
// -----------------------------------------------------------------------------
// set_time_limit(120); // 2 minutes
/*
$arr_cfg = array();
// optional: mysql performance test
$arr_cfg['db.host'] = DB_HOST;
$arr_cfg['db.user'] = DB_USER;
$arr_cfg['db.pw'] = DB_PASSWORD;
$arr_cfg['db.name'] = DB_NAME;
*/

// -----------------------------------------------------------------------------
// Benchmark functions
// -----------------------------------------------------------------------------
function wptools_performance_share($benchmarkResult){
    /*
    if(get_transient('wptools_performance_share'))
       return get_transient('wptools_performance_share');
    */


    if( gettype($benchmarkResult) != 'array' or count($benchmarkResult) < 2)
       return '';

     /////  ob_start();
       $myarray = array('wptools_performance' => json_encode(  $benchmarkResult['benchmark'])  );
       // $myarray = array('wptools_performance' => 'Test');
       $url = "https://wptoolsplugin.com/API/bill-api.php";
       $response = wp_remote_post($url, array(
           'method' => 'POST',
           'timeout' => 5,
           'redirection' => 5,
           'httpversion' => '1.0',
           'blocking' => true,
           'headers' => array(),
           'body' => $myarray,
           'cookies' => array()
       ));
       if (is_wp_error($response)) {
           $error_message = $response->get_error_message();
           // echo "Something went wrong: $error_message";
           // set_transient('termina', DAY_IN_SECONDS, DAY_IN_SECONDS);
           ob_end_clean();
           return '';
       }
       $r = trim($response['body']);



      ///// ob_end_clean();
       set_transient('wptools_performance_share', $r, (10 * DAY_IN_SECONDS));
         return $r;
}
function wptools_test_benchmark($arr_cfg)
{
    //global $arr_cfg;
    $time_start = microtime(true);
    $arr_return = array();
    $arr_return['version'] = '1.1';
    $arr_return['sysinfo']['time'] = date("Y-m-d H:i:s");
    $arr_return['sysinfo']['php_version'] = PHP_VERSION;
    $arr_return['sysinfo']['platform'] = PHP_OS;
    $arr_return['sysinfo']['server_name'] = sanitize_text_field($_SERVER['SERVER_NAME']);
    $arr_return['sysinfo']['server_addr'] = sanitize_text_field($_SERVER['SERVER_ADDR']);
    wptools_test_matwptools_h($arr_return);
    wptools_test_string($arr_return);
    wptools_test_loops($arr_return);
    wptools_test_ifelse($arr_return);
    //$result['benchmark']['calculation'] = wptools_timer_diff($timeStart) . ' sec.';
   // $arr_cfg['db.host'] = DB_HOST;
    if (isset($arr_cfg['db.host'])) {
        wptools_test_mysql($arr_return, $arr_cfg);
    }
    $arr_return['total'] = wptools_timer_diff($time_start);
    return $arr_return;
}
function wptools_test_matwptools_h(&$arr_return, $count = 99999)
{
    $time_start = microtime(true);
    // $mathFunctions = array("abs", "acos", "asin", "atan", "bindec", "floor", "exp", "sin", "tan", "pi", "is_finite", "is_nan", "sqrt");
    // $mathFunctions = array("abs", "acos", "asin", "atan", "bindec", "floor", "exp", "sin", "tan", "is_finite", "is_nan", "sqrt");
       $mathFunctions = array("abs", "acos", "asin", "atan", "floor", "exp", "sin", "tan", "is_finite", "is_nan", "sqrt");
    for ($i = 0; $i < count($mathFunctions); $i++) {
        if(!function_exists($mathFunctions[$i])) {
            echo "<strong>Error: PHP function ".esc_attr($mathFunctions[$i]). " doesn't exist.<br>Talk with your hosting company to enable it!<br><br></strong>";
            $arr_return['benchmark']['math'] = wptools_timer_diff($time_start);        
            return;
        }
    }
    /*
    $wptools_init_erros =  ini_get('log_errors');
    ini_set('log_errors', 0); // disable error logging
    define('WP_DISABLE_FATAL_ERROR_HANDLER',true);
    */
    for ($i = 0; $i < $count; $i++) {
        foreach ($mathFunctions as $function) {
               try {
                      if( !empty($function) and function_exists($function))
                        $r = @call_user_func_array($function, array($i));
                        //Unknown error type: [8192] 
                        //Invalid characters passed for attempted conversion, 
                        // these have been ignored On line 162 
                        // in file /home/carplugi/public_html/wp-content/plugins/wptools/functions/functions_benchmark.php
                }
                catch(Exception $e) {
                         // echo 'Message: ' .$e->getMessage();
                         echo "<strong>Error: ";
                         echo esc_attr($e->getMessage());
                         echo "<br><br></strong>";                  
                         $arr_return['benchmark']['math'] = wptools_timer_diff($time_start);        
                        //$wptools_init_erros =  init_get('log_errors');
                        // ini_set('log_errors', $wptools_init_erros); 
                         return;
                }
        }
    }
    //$wptools_init_erros =  init_get('log_errors');
    //ini_set('log_errors', $wptools_init_erros); 
    $arr_return['benchmark']['math'] = wptools_timer_diff($time_start);
}
function wptools_test_string(&$arr_return, $count = 99999)
{
    $time_start = microtime(true);
    $stringFunctions = array("addslashes", "chunk_split", "metaphone", "strip_tags", "md5", "sha1", "strtoupper", "strtolower", "strrev", "strlen", "soundex", "ord");
    $string = 'the quick brown fox jumps over the lazy dog';
    for ($i = 0; $i < $count; $i++) {
        foreach ($stringFunctions as $function) {
            $r = call_user_func_array($function, array($string));
        }
    }
    $arr_return['benchmark']['string'] = wptools_timer_diff($time_start);
}
function wptools_test_loops(&$arr_return, $count = 999999)
{
    $time_start = microtime(true);
    for ($i = 0; $i < $count; ++$i)
        ;
    $i = 0;
    while ($i < $count) {
        ++$i;
    }
    $arr_return['benchmark']['loops'] = wptools_timer_diff($time_start);
}
function wptools_test_ifelse(&$arr_return, $count = 999999)
{
    $time_start = microtime(true);
    for ($i = 0; $i < $count; $i++) {
        if ($i == -1) {
        } elseif ($i == -2) {
        } else if ($i == -3) {
        }
    }
    $arr_return['benchmark']['ifelse'] = wptools_timer_diff($time_start);
}
function wptools_test_mysql(&$arr_return, $arr_cfg)
{
    $time_start = microtime(true);
    //detect socket connection
    if(stripos($arr_cfg['db.host'], '.sock')!==false){
        //parse socket location
        //set a default guess
        $socket = "/var/lib/mysql.sock";
        $serverhost = explode(':', $arr_cfg['db.host']);
        if(count($serverhost) == 2 && $serverhost[0] == 'localhost'){
            $socket = $serverhost[1];
        }
        $link = mysqli_connect('localhost', $arr_cfg['db.user'], $arr_cfg['db.pw'], $arr_cfg['db.name'], null, $socket);
    }else{
        //parse out port number if exists
        $port = 3306;//default
        if(stripos($arr_cfg['db.host'],':')){
            $port = substr($arr_cfg['db.host'], stripos($arr_cfg['db.host'],':')+1);
            $arr_cfg['db.host'] = substr($arr_cfg['db.host'], 0, stripos($arr_cfg['db.host'],':'));
        }
        $link = mysqli_connect($arr_cfg['db.host'], $arr_cfg['db.user'], $arr_cfg['db.pw'], $arr_cfg['db.name'], $port);
    }
    $arr_return['benchmark']['mysql_connect'] = wptools_timer_diff($time_start);
    // //$arr_return['sysinfo']['mysql_version'] = '';
    $arr_return['benchmark']['mysql_select_db'] = wptools_timer_diff($time_start);

    try {
        $result = mysqli_query($link, 'SELECT VERSION() as version;');
        $arr_row = mysqli_fetch_assoc($result);
        $arr_return['sysinfo']['mysql_version'] = $arr_row['version'];
        $arr_return['benchmark']['mysql_query_version'] = wptools_timer_diff($time_start);
        $query = "SELECT BENCHMARK(100000,ENCODE('hello',RAND()));"; // 1000000
        $result = mysqli_query($link, $query);
        $arr_return['benchmark']['mysql_query_benchmark'] = wptools_timer_diff($time_start);
        mysqli_close($link);
    } catch (Exception $e) {
        error_log('MySQL Error: ' . $e->getMessage());
        $arr_return['sysinfo']['mysql_version'] = '';
        $arr_return['benchmark']['mysql_query_version'] = '';
        $arr_return['benchmark']['mysql_query_benchmark'] = '';
    }

    $arr_return['benchmark']['mysql_total'] = wptools_timer_diff($time_start);
    return $arr_return;
}
function wptools_test_wordpress(){
    //create dummy text to insert into database
    $dummytextseed = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sollicitudin iaculis libero id pellentesque. Donec sodales nunc id lorem rutrum molestie. Duis ac ornare diam. In hac habitasse platea dictumst. Donec nec mi ipsum. Aenean dictum imperdiet erat, at lacinia mi ultrices ut. Phasellus quis nibh ornare, pulvinar dui sit amet, venenatis arcu. Suspendisse eget vehicula ligula, et placerat sapien. Cras enim erat, scelerisque sit amet tellus vel, tempor venenatis risus. In ultricies tristique ante, eu lobortis leo. Cras ullamcorper eleifend libero, quis sollicitudin massa venenatis a. Vestibulum sed pellentesque urna, nec consectetur nulla. Vestibulum sodales purus metus, non scelerisque.";
    $dummytext = "";
    for($x=0; $x<100; $x++){
        $dummytext .= str_shuffle($dummytextseed);
    }
    //start timing wordpress mysql functions
    $time_start = microtime(true);
    global $wpdb;
    $table = $wpdb->prefix . 'options';
    $optionname = 'wpperformancetesterbenchmark_';
    $count = 250;
    for($x=0; $x<$count;$x++){
        //insert
        $data = array('option_name' => $optionname . $x, 'option_value' => $dummytext);
        $wpdb->insert($table, $data);
        //select
        $select = "SELECT option_value FROM $table WHERE option_name='$optionname" . $x . "'";
        $wpdb->get_var($select);
        //update
        $data = array('option_value' => $dummytextseed);
        $where =  array('option_name' => $optionname . $x);
        $wpdb->update($table, $data, $where);
        //delete
        $where = array('option_name'=>$optionname.$x);
        $wpdb->delete($table,$where);    
    }
    $time = wptools_timer_diff($time_start);
    $queries = ($count * 4) / $time;
    return array('time'=>$time,'queries'=>$queries);     
}
function wptools_timer_diff($time_start)
{
    return number_format(microtime(true) - $time_start, 3);
}
function wptools_array_to_html($my_array)
{
    $strReturn = '';
    if (is_array($my_array)) {
        $strReturn .= '<table>';
        foreach ($my_array as $k => $v) {
            $strReturn .= "\n<tr><td style=\"vertical-align:top;\">";
            $strReturn .= '<strong>' . htmlentities($k) . "</strong></td><td>";
            $strReturn .= wptools_array_to_html($v);
            $strReturn .= "</td></tr>";
        }
        $strReturn .= "\n</table>";
    } else {
        $strReturn = htmlentities($my_array);
    }
    return $strReturn;
}
// Default value for parameters with a class type hint can only be NULL in /home/avantagecapital/public_html/wp-content/plugins/wptools/functions/functions_benchmark.php on line 358
function wptools_print_html_result($title, array $data, bool $showServerName = true)
{
    //echo "<!DOCTYPE html>\n<html><head>\n";
    echo "<style>
       table a:link {
        color: #666;
        font-weight: bold;
        text-decoration:none;
    }
    table a:visited {
        color: #999999;
        font-weight:bold;
        text-decoration:none;
    }
    table a:active,
    table a:hover {
        color: #bd5a35;
        text-decoration:underline;
    }
    table {
        font-family:Arial, Helvetica, sans-serif;
        color:#666;
        font-size:12px;
        text-shadow: 1px 1px 0px #fff;
        background:#eaebec;
        margin:20px;
        border:#ccc 1px solid;
        -moz-border-radius:3px;
        -webkit-border-radius:3px;
        border-radius:3px;
        -moz-box-shadow: 0 1px 2px #d1d1d1;
        -webkit-box-shadow: 0 1px 2px #d1d1d1;
        box-shadow: 0 1px 2px #d1d1d1;
    }
    table th {
        padding:8px 15px 8px 8px;
        border-top:1px solid #fafafa;
        border-bottom:1px solid #e0e0e0;
        text-align: left;
        background: #ededed;
        background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));
        background: -moz-linear-gradient(top,  #ededed,  #ebebeb);
    }
    table th:first-child {
        text-align: left;
        padding-left:10px;
    }
    table tr:first-child th:first-child {
        -moz-border-radius-topleft:3px;
        -webkit-border-top-left-radius:3px;
        border-top-left-radius:3px;
    }
    table tr:first-child th:last-child {
        -moz-border-radius-topright:3px;
        -webkit-border-top-right-radius:3px;
        border-top-right-radius:3px;
    }
    table tr {
        padding-left:10px;
    }
    table td:first-child {
        text-align: left;
        padding-left:10px;
        border-left: 0;
    }
    table td {
        padding:8px;
        border-top: 1px solid #ffffff;
        border-bottom:1px solid #e0e0e0;
        border-left: 1px solid #e0e0e0;
        background: #fafafa;
        background: -webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#fafafa));
        background: -moz-linear-gradient(top,  #fbfbfb,  #fafafa);
    }
    table tr.even td {
        background: #f6f6f6;
        background: -webkit-gradient(linear, left top, left bottom, from(#f8f8f8), to(#f6f6f6));
        background: -moz-linear-gradient(top,  #f8f8f8,  #f6f6f6);
    }
    table tr:last-child td {
        border-bottom:0;
    }
    table tr:last-child td:first-child {
        -moz-border-radius-bottomleft:3px;
        -webkit-border-bottom-left-radius:3px;
        border-bottom-left-radius:3px;
    }
    table tr:last-child td:last-child {
        -moz-border-radius-bottomright:3px;
        -webkit-border-bottom-right-radius:3px;
        border-bottom-right-radius:3px;
    }
    table tr:hover td {
        background: #f2f2f2;
        background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#f0f0f0));
        background: -moz-linear-gradient(top,  #f2f2f2,  #f0f0f0);	
    }
    </style>";
    $result = '<table cellspacing="0">';
    $result .= '<thead><tr><th>'.esc_attr($title).'</th><th></th></tr></thead>';
    $result .= '<tbody>';
   // $result .= '<tr class="even"><td>Version</td><td>' . wptools_h($data['version']) . '</td></tr>';
    if($title != 'Industry Average Data')
      $result .= '<tr class="even"><td>Time</td><td>' . wptools_h($data['sysinfo']['time']) . '</td></tr>';
    else
      $result .= '<tr class="even"><td></td><td>-</td></tr>';
    if($title != 'Industry Average Data')
      $result .= '<tr class="even"><td>PHP Version</td><td>' . wptools_h($data['sysinfo']['php_version']) . '</td></tr>';
    else
      $result .= '<tr class="even"><td></td><td>-</td></tr>';
    if($title != 'Industry Average Data')
       $result .= '<tr class="even"><td>Platform</td><td>' . wptools_h($data['sysinfo']['platform']) . '</td></tr>';
    else
       $result .= '<tr class="even"><td></td><td>-</td></tr>';
    if ($showServerName == true) {
      //  $result .= '<tr class="even"><td>Server name</td><td>' . wptools_h($data['sysinfo']['server_name']) . '</td></tr>';
      if($title != 'Industry Average Data')
        $result .= '<tr class="even"><td>Server address</td><td>' . wptools_h($data['sysinfo']['server_addr']) . '</td></tr>';
      else
        $result .= '<tr class="even"><td></td><td>-</td></tr>';
    }
    $result .= '</tbody>';
    $result .= '<thead><tr><th>Benchmark</th><th></th></tr></thead>';
    $result .= '<tbody>';
    $result .= '<tr><td>Math</td><td>' . wptools_h(number_format($data['benchmark']['math'],2)) . '</td></tr>';
    $result .= '<tr><td>String</td><td>' . wptools_h(number_format($data['benchmark']['string'],2)) . '</td></tr>';
    $result .= '<tr><td>Loops</td><td>' . wptools_h(number_format($data['benchmark']['loops'],2)) . '</td></tr>';
    $result .= '<tr><td>Conditionals</td><td>' . wptools_h(number_format($data['benchmark']['ifelse'],2)) . '</td></tr>';
    //$result .= '<tr class="even"><td>Total Time</td><td>' . wptools_h(
    //        $data['total'] )
    $result .= '</td></tr>';
    $result .= '</tbody>';
        $result .= '<thead><tr><th>MySQL</th><th></th></tr></thead>';
        $result .= '<tbody>';
        $result .= '<tr><td>MySQL Version</td><td>' . wptools_h($data['sysinfo']['mysql_version']) . '</td></tr>';
        $result .= '<tr><td>MySQL Connect</td><td>' . wptools_h(number_format($data['benchmark']['mysql_connect'],2)) . '</td></tr>';
        $result .= '<tr><td>MySQL Select DB</td><td>' . wptools_h(number_format($data['benchmark']['mysql_select_db'],2)) . '</td></tr>';
        $result .= '<tr><td>MySQL Query Version</td><td>' . wptools_h(number_format($data['benchmark']['mysql_query_version'],2)) . '</td></tr>';
        $result .= '<tr><td>MySQL Benchmark</td><td>' . wptools_h(number_format($data['benchmark']['mysql_query_benchmark'],2)) . '</td></tr>';
        $result .= '</tbody>';
    
    //    $result .= '<thead><tr><th>Total Time (seconds) </th><th>' . wptools_h(number_format($data['total']+$data['benchmark']['mysql_total'],2)) . '</th></tr></thead>';
   
    $result .= '<thead><tr><th>Total Time (seconds) </th><th>' . wptools_h(number_format($data['total'],2)) . '</th></tr></thead>';
   
    $result .= '</table>';
    $allowed_atts = array(
        'align'      => array(),
        'class'      => array(),
        'type'       => array(),
        'id'         => array(),
        'dir'        => array(),
        'lang'       => array(),
        'style'      => array(),
        'xml:lang'   => array(),
        'src'        => array(),
        'alt'        => array(),
        'href'       => array(),
        'rel'        => array(),
        'rev'        => array(),
        'target'     => array(),
        'novalidate' => array(),
        'type'       => array(),
        'value'      => array(),
        'name'       => array(),
        'tabindex'   => array(),
        'action'     => array(),
        'method'     => array(),
        'for'        => array(),
        'width'      => array(),
        'height'     => array(),
        'data'       => array(),
        'title'      => array(),
        'checked' => array(),
        'selected' => array(),
    );
    $my_allowed['form'] = $allowed_atts;
    $my_allowed['select'] = $allowed_atts;
    // select options
    $my_allowed['option'] = $allowed_atts;
    $my_allowed['style'] = $allowed_atts;
    $my_allowed['label'] = $allowed_atts;
    $my_allowed['input'] = $allowed_atts;
    $my_allowed['textarea'] = $allowed_atts;
    //more...future...
    $my_allowed['form']     = $allowed_atts;
    $my_allowed['label']    = $allowed_atts;
    $my_allowed['input']    = $allowed_atts;
    $my_allowed['textarea'] = $allowed_atts;
    $my_allowed['iframe']   = $allowed_atts;
    $my_allowed['script']   = $allowed_atts;
    $my_allowed['style']    = $allowed_atts;
    $my_allowed['strong']   = $allowed_atts;
    $my_allowed['small']    = $allowed_atts;
    $my_allowed['table']    = $allowed_atts;
    $my_allowed['span']     = $allowed_atts;
    $my_allowed['abbr']     = $allowed_atts;
    $my_allowed['code']     = $allowed_atts;
    $my_allowed['pre']      = $allowed_atts;
    $my_allowed['div']      = $allowed_atts;
    $my_allowed['img']      = $allowed_atts;
    $my_allowed['h1']       = $allowed_atts;
    $my_allowed['h2']       = $allowed_atts;
    $my_allowed['h3']       = $allowed_atts;
    $my_allowed['h4']       = $allowed_atts;
    $my_allowed['h5']       = $allowed_atts;
    $my_allowed['h6']       = $allowed_atts;
    $my_allowed['ol']       = $allowed_atts;
    $my_allowed['ul']       = $allowed_atts;
    $my_allowed['li']       = $allowed_atts;
    $my_allowed['em']       = $allowed_atts;
    $my_allowed['hr']       = $allowed_atts;
    $my_allowed['br']       = $allowed_atts;
    $my_allowed['tr']       = $allowed_atts;
    $my_allowed['thead']       = $allowed_atts;
    $my_allowed['td']       = $allowed_atts;
    $my_allowed['th']       = $allowed_atts;
    $my_allowed['p']        = $allowed_atts;
    $my_allowed['a']        = $allowed_atts;
    $my_allowed['b']        = $allowed_atts;
    $my_allowed['i']        = $allowed_atts;
    // echo $result;
     echo wp_kses($result, $my_allowed);
    echo "\n</body></html>";
}
function wptools_h($v)
{
    return htmlentities($v);
}