<?php
/*
Plugin Name: ravpage
Plugin URI: http://responder.co.il
Description: plugin to easy page publishing for ravpage clients
Version: 2.31
Author: Mati Skiba @ Rav Messer
*/

require_once("URLNormalizer.php");
if( !class_exists( 'WP_Http' ) )
  require_once( ABSPATH . WPINC. '/class-http.php' );

function sanitized_unserialize_base64_or_null($source) {
  $source = ravxx_sanitize_string_or_null($source);
  if ( $source ) {
    // base64_decode returns false if invalid
    $source = base64_decode($source,true);
    if ( !$source )
      $source = null;
  }
  if ( $source ) {
    // unserialize returns false if invalid
    $source = @unserialize($source);
    if ( !$source )
      $source = null;
  }
  return $source;
}

function ravxx_escape_html($html,$filters) {
    // definition of filters tgo be used
    $filtersDef = array(
        "script+content+iframe"=>array("html"=>array("lang"=>array()),"HTML"=>array("lang"=>array()),"pre"=>array(),"head"=>array(),"HEAD"=>array(),"meta"=>array("name"=>array(),"content"=>array()),"META"=>array("name"=>array(),"content"=>array()),"script"=>array(),"SCRIPT"=>array(),"link"=>array("rel"=>array(),"type"=>array(),"href"=>array()),"LINK"=>array("rel"=>array(),"type"=>array(),"href"=>array()),"style"=>array(),"STYLE"=>array(),"noscript"=>array(),"NOSCRIPT"=>array(),"title"=>array(),"TITLE"=>array(),"body"=>array("data-mobile-threshold"=>array(),"data-content-language"=>array(),"class"=>array(),"style"=>array()),"BODY"=>array("data-mobile-threshold"=>array(),"data-content-language"=>array(),"class"=>array(),"style"=>array()),"div"=>array("id"=>array()),"DIV"=>array("id"=>array()),"svg"=>array("xmlns"=>array(),"viewBox"=>array(),"class"=>array()),"SVG"=>array("xmlns"=>array(),"viewBox"=>array(),"class"=>array()),"path"=>array("d"=>array()),"PATH"=>array("d"=>array()),"defs"=>array(),"DEFS"=>array(),"clippath"=>array("id"=>array()),"CLIPPATH"=>array("id"=>array()),"rect"=>array("class"=>array(),"width"=>array(),"height"=>array(),"y"=>array()),"RECT"=>array("class"=>array(),"width"=>array(),"height"=>array(),"y"=>array()),"p"=>array("class"=>array(),"style"=>array()),"P"=>array("class"=>array(),"style"=>array()),"span"=>array("class"=>array(),"contenteditable"=>array(),"data-field"=>array()),"SPAN"=>array("class"=>array(),"contenteditable"=>array(),"data-field"=>array()),"b"=>array(),"B"=>array(),"img"=>array("loading"=>array(),"srcset"=>array(),"src"=>array(),"alt"=>array()),"IMG"=>array("loading"=>array(),"srcset"=>array(),"src"=>array(),"alt"=>array()),"br"=>array(),"BR"=>array(),"a"=>array("target"=>array(),"href"=>array()),"A"=>array("target"=>array(),"href"=>array()),"form"=>array("field-count"=>array(),"class"=>array(),"action"=>array(),"method"=>array(),"novalidate"=>array()),"FORM"=>array("field-count"=>array(),"class"=>array(),"action"=>array(),"method"=>array(),"novalidate"=>array()),"input"=>array("type"=>array(),"class"=>array(),"name"=>array(),"value"=>array()),"INPUT"=>array("type"=>array(),"class"=>array(),"name"=>array(),"value"=>array()),"textarea"=>array("aria-label"=>array(),"rows"=>array(),"style"=>array(),"name"=>array(),"caption"=>array(),"maxlength"=>array()),"TEXTAREA"=>array("aria-label"=>array(),"rows"=>array(),"style"=>array(),"name"=>array(),"caption"=>array(),"maxlength"=>array()),"label"=>array("class"=>array()),"LABEL"=>array("class"=>array()),"button"=>array("type"=>array(),"class"=>array(),"name"=>array()),"BUTTON"=>array("type"=>array(),"class"=>array(),"name"=>array()),"iframe"=>array("name"=>array(),"title"=>array(),"id"=>array(),"src"=>array(),"style"=>array()),"IFRAME"=>array("name"=>array(),"title"=>array(),"id"=>array(),"src"=>array(),"style"=>array())),
        "script+content"=>array("html"=>array("lang"=>array()),"HTML"=>array("lang"=>array()),"pre"=>array(),"head"=>array(),"HEAD"=>array(),"meta"=>array("name"=>array(),"content"=>array()),"META"=>array("name"=>array(),"content"=>array()),"script"=>array(),"SCRIPT"=>array(),"link"=>array("rel"=>array(),"type"=>array(),"href"=>array()),"LINK"=>array("rel"=>array(),"type"=>array(),"href"=>array()),"style"=>array(),"STYLE"=>array(),"noscript"=>array(),"NOSCRIPT"=>array(),"title"=>array(),"TITLE"=>array(),"body"=>array("data-mobile-threshold"=>array(),"data-content-language"=>array(),"class"=>array(),"style"=>array()),"BODY"=>array("data-mobile-threshold"=>array(),"data-content-language"=>array(),"class"=>array(),"style"=>array()),"div"=>array("id"=>array()),"DIV"=>array("id"=>array()),"svg"=>array("xmlns"=>array(),"viewBox"=>array(),"class"=>array()),"SVG"=>array("xmlns"=>array(),"viewBox"=>array(),"class"=>array()),"path"=>array("d"=>array()),"PATH"=>array("d"=>array()),"defs"=>array(),"DEFS"=>array(),"clippath"=>array("id"=>array()),"CLIPPATH"=>array("id"=>array()),"rect"=>array("class"=>array(),"width"=>array(),"height"=>array(),"y"=>array()),"RECT"=>array("class"=>array(),"width"=>array(),"height"=>array(),"y"=>array()),"p"=>array("class"=>array(),"style"=>array()),"P"=>array("class"=>array(),"style"=>array()),"span"=>array("class"=>array(),"contenteditable"=>array(),"data-field"=>array()),"SPAN"=>array("class"=>array(),"contenteditable"=>array(),"data-field"=>array()),"b"=>array(),"B"=>array(),"img"=>array("loading"=>array(),"srcset"=>array(),"src"=>array(),"alt"=>array()),"IMG"=>array("loading"=>array(),"srcset"=>array(),"src"=>array(),"alt"=>array()),"br"=>array(),"BR"=>array(),"a"=>array("target"=>array(),"href"=>array()),"A"=>array("target"=>array(),"href"=>array()),"form"=>array("field-count"=>array(),"class"=>array(),"action"=>array(),"method"=>array(),"novalidate"=>array()),"FORM"=>array("field-count"=>array(),"class"=>array(),"action"=>array(),"method"=>array(),"novalidate"=>array()),"input"=>array("type"=>array(),"class"=>array(),"name"=>array(),"value"=>array()),"INPUT"=>array("type"=>array(),"class"=>array(),"name"=>array(),"value"=>array()),"textarea"=>array("aria-label"=>array(),"rows"=>array(),"style"=>array(),"name"=>array(),"caption"=>array(),"maxlength"=>array()),"TEXTAREA"=>array("aria-label"=>array(),"rows"=>array(),"style"=>array(),"name"=>array(),"caption"=>array(),"maxlength"=>array()),"label"=>array("class"=>array()),"LABEL"=>array("class"=>array()),"button"=>array("type"=>array(),"class"=>array(),"name"=>array()),"BUTTON"=>array("type"=>array(),"class"=>array(),"name"=>array())),
        "content"=>array("html"=>array("lang"=>array()),"HTML"=>array("lang"=>array()),"pre"=>array(),"head"=>array(),"HEAD"=>array(),"meta"=>array("name"=>array(),"content"=>array()),"META"=>array("name"=>array(),"content"=>array()),"link"=>array("rel"=>array(),"type"=>array(),"href"=>array()),"LINK"=>array("rel"=>array(),"type"=>array(),"href"=>array()),"style"=>array(),"STYLE"=>array(),"noscript"=>array(),"NOSCRIPT"=>array(),"title"=>array(),"TITLE"=>array(),"body"=>array("data-mobile-threshold"=>array(),"data-content-language"=>array(),"class"=>array(),"style"=>array()),"BODY"=>array("data-mobile-threshold"=>array(),"data-content-language"=>array(),"class"=>array(),"style"=>array()),"div"=>array("id"=>array()),"DIV"=>array("id"=>array()),"svg"=>array("xmlns"=>array(),"viewBox"=>array(),"class"=>array()),"SVG"=>array("xmlns"=>array(),"viewBox"=>array(),"class"=>array()),"path"=>array("d"=>array()),"PATH"=>array("d"=>array()),"defs"=>array(),"DEFS"=>array(),"clippath"=>array("id"=>array()),"CLIPPATH"=>array("id"=>array()),"rect"=>array("class"=>array(),"width"=>array(),"height"=>array(),"y"=>array()),"RECT"=>array("class"=>array(),"width"=>array(),"height"=>array(),"y"=>array()),"p"=>array("class"=>array(),"style"=>array()),"P"=>array("class"=>array(),"style"=>array()),"span"=>array("class"=>array(),"contenteditable"=>array(),"data-field"=>array()),"SPAN"=>array("class"=>array(),"contenteditable"=>array(),"data-field"=>array()),"b"=>array(),"B"=>array(),"img"=>array("loading"=>array(),"srcset"=>array(),"src"=>array(),"alt"=>array()),"IMG"=>array("loading"=>array(),"srcset"=>array(),"src"=>array(),"alt"=>array()),"br"=>array(),"BR"=>array(),"a"=>array("target"=>array(),"href"=>array()),"A"=>array("target"=>array(),"href"=>array()),"form"=>array("field-count"=>array(),"class"=>array(),"action"=>array(),"method"=>array(),"novalidate"=>array()),"FORM"=>array("field-count"=>array(),"class"=>array(),"action"=>array(),"method"=>array(),"novalidate"=>array()),"input"=>array("type"=>array(),"class"=>array(),"name"=>array(),"value"=>array()),"INPUT"=>array("type"=>array(),"class"=>array(),"name"=>array(),"value"=>array()),"textarea"=>array("aria-label"=>array(),"rows"=>array(),"style"=>array(),"name"=>array(),"caption"=>array(),"maxlength"=>array()),"TEXTAREA"=>array("aria-label"=>array(),"rows"=>array(),"style"=>array(),"name"=>array(),"caption"=>array(),"maxlength"=>array()),"label"=>array("class"=>array()),"LABEL"=>array("class"=>array()),"button"=>array("type"=>array(),"class"=>array(),"name"=>array()),"BUTTON"=>array("type"=>array(),"class"=>array(),"name"=>array()),"iframe"=>array("name"=>array(),"title"=>array(),"id"=>array(),"src"=>array(),"style"=>array()),"IFRAME"=>array("name"=>array(),"title"=>array(),"id"=>array(),"src"=>array(),"style"=>array()))
    );


    // default is empty filter - all will be escaped
    $filtersString = "";

    foreach ( $filtersDef as $key=>$value ) {
        if  ( $key == $filters ) {

            foreach ( $value as $k=>$v ) {
              if ( isset($v) && count($v) > 0 ) {
                $s = "";
                foreach ( $v as  $k2 => $v2 ) {
                  $s .= ( $s ? "," : "" ) . $k2;
                }
                $filtersString .= ( $filtersString ? "," : "" ) . $k . "[" . $s . "]";
              }
              else {
                $filtersString .= ( $filtersString ? "," : "" ) . $k;
              }
            }
        }
    }

    require_once(dirname(__FILE__) . '/htmlpurifier/library/HTMLPurifier.auto.php');

    $config = HTMLPurifier_Config::createDefault();
    //$config->set('HTML', 'Allowed', $filtersString);
    //$config->set('Core', 'DefinitionCache', null);

    $purifier = new HTMLPurifier($config);

    $html = $purifier->purify($html);

    return $html;
}


function ravxx_url_path_encode($url,$encodeAll=false,$encodeDomain=false) {
  if (strpos($url,'%') !== false)
    $url = rawurldecode($url);
  $url = preg_replace("~\s+(\$|\?|#)~","?",$url);
  $path = parse_url($url, PHP_URL_PATH);
  $domain = parse_url($url, PHP_URL_HOST);
  $query = parse_url($url, PHP_URL_QUERY);
  $fragment = parse_url($url, PHP_URL_FRAGMENT);
  $encoded_path = array_map('rawurlencode', explode('/', $path));
  if ( $encodeDomain )
    $domain = rawurlencode($domain);
  $url = $domain . implode("/",$encoded_path);
  if ( $encodeAll )
  {
    if ( $query )
      $url .= "?" . rawurlencode($query);
    if ( $fragment )
      $url .= "#" . rawurlencode($fragment);
  }
  return $url;
}

function ravxx_normalizeURL($url,$keepParams=false,$keepProtocol=false,$encodeAll=false,$encodeDomain=false)
{
  $url = str_replace("https://","https://www.",preg_replace("~\:(\\d+)~","",$url));
  $url = str_replace("https://www.www.","https://www.",$url);
  $url = str_replace("https://","http://",$url);
  $un = new ravxx_URLNormalizer();
  $un->setUrl( $url );
  $s = ravxx_url_path_encode($un->normalize(),$encodeAll,$encodeDomain);
  if ( !$keepProtocol )
    $s = preg_replace("~^[^:]+://~","",$s);
  if ( !$keepParams )
  {
    $s = explode("?",$s);
    $s = explode("#",$s[0]);
    $s = $s[0];
  }
  $s = preg_replace("~\/$~","",$s);

  return $s;
}

function ravxx_full_url()
{
  $s = empty(ravxx_safe_access_or_null($_SERVER,"HTTPS")) ? '' : ( (ravxx_sanitize_string_or_null(ravxx_safe_access_or_null($_SERVER,"HTTPS")) == "on") ? "s" : "" );
  $sp = strtolower(ravxx_sanitize_string_or_null(ravxx_safe_access_or_null($_SERVER,"SERVER_PROTOCOL")));
  $protocol = substr($sp, 0, strpos($sp, "/")) . $s;
  $port = (ravxx_sanitize_int_or_null($_SERVER["SERVER_PORT"]) == "80") ? "" : (":".ravxx_sanitize_int_or_null($_SERVER["SERVER_PORT"]));
  $host = (isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST']))? ravxx_sanitize_string_or_null(ravxx_safe_access_or_null($_SERVER,'HTTP_HOST')):ravxx_sanitize_string_or_null(ravxx_safe_access_or_null($_SERVER,'SERVER_NAME'));
  
  return esc_url($protocol . "://" . $host . $port . str_replace("\'","'",ravxx_safe_access_or_null($_SERVER,'REQUEST_URI')));
}

global $ravxx_jal_db_version;
$ravxx_jal_db_version = "1.0";

function ravxx_jal_install() {
  global $wpdb;
  global $ravxx_jal_db_version;

  $table_name = $wpdb->prefix . "ravpage_urls";

  $sql = "CREATE TABLE $table_name (
    url varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL PRIMARY KEY
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);

  add_option("jal_db_version", $ravxx_jal_db_version);
}

function ravxx_safe_access_or_null($collection,$key) {
        if ( !array_key_exists($key,$collection) )
                return null;
        else
                return $collection[$key];
}

function ravxx_sanitize_string_or_null($value) {
        if ( !is_string($value) )
                return null;
        else
                return $value;
}

function ravxx_sanitize_int_or_null($value) {
        if ( !is_int($value) )
                return null;
        else
                return $value;
}

function ravxx_my_template() {
  global $wpdb;
  global $ravpageContent;


  // table name in db
  $table_name = $wpdb->prefix . "ravpage_urls";

  $debug = isset($_REQUEST["debug"]);

  // handle case of api special address

  if ( preg_match("~/__ravpage/api(#|\?|\$)~",ravxx_sanitize_string_or_null(ravxx_safe_access_or_null($_SERVER,"REQUEST_URI"))) )
  {
    // general url case
    ravxx_jal_install();

    status_header(200);

    if ( $debug )
      echo ravxx_escape_html("<pre>"."! handling api request\n"."</pre>","content");

    if ( $debug )
    {
      echo ravxx_escape_html("<pre>"."params:\n"."</pre>","content");
      echo ravxx_escape_html("<pre>".htmlentities(print_r($_REQUEST, true))."</pre>","content");
    }

    if ( $debug )
      echo ravxx_escape_html("<pre>"."! checking for proper action\n"."</pre>","content");

    if ( isset( $_REQUEST["action"] ) )
      $action = ravxx_sanitize_string_or_null($_REQUEST["action"]);
    else
      die("nok - no action");

    if ( $debug )
      echo ravxx_escape_html("<pre>"."! checking for proper timestamp\n"."</pre>","content");

    if ( isset( $_REQUEST["timestampMajor"] ) && isset( $_REQUEST["timestampMinor"] ) )
    {
      $val = ravxx_sanitize_string_or_null($_REQUEST["timestampMajor"]);
      if ( !is_numeric($val) )
        die("nok - timestamp major invalid - not numeric '$val'");
      $timestampMajor = intval($val);
      if ( $timestampMajor != $val )
        die("nok - timestamp major invalid - not int '$timestampMajor' != '$val'");

      $val = ravxx_sanitize_string_or_null($_REQUEST["timestampMinor"]);
      if ( !is_numeric($val) )
        die("nok - timestamp minor invalid - not numeric '$val'");
      $timestampMinor = intval($val);
      if ( $timestampMinor != $val )
        die("nok - timestamp minor invalid - not int '$timestampMinor' != '$val'");

      if ( $lastTimestampMajor = get_option("ravpageLastTimestampMajor") )
      {
        $lastTimestampMinor = get_option("ravpageLastTimestampMinor");

        if ( ( $timestampMajor < $lastTimestampMajor ) || ( $timestampMajor == $lastTimestampMajor ) && ( $timestampMinor <= $lastTimestampMinor ) )
          die("nok - timestamp old. timestampMajor=$timestampMajor. lastTimestampMajor=$lastTimestampMajor. timestampMinor=$timestampMinor. lastTimestampMinor=$lastTimestampMinor.");
      }

      update_option("ravpageLastTimestampMinor",$timestampMinor);
      update_option("ravpageLastTimestampMajor",$timestampMajor);
    }
    else
      die("nok - no timestamp");
    if ( $debug )
      echo ravxx_escape_html("<pre>"."! checking for params\n"."</pre>","content");
    if ( isset( $_REQUEST["paramsv2"] ) )
    {
      if ( $debug )
      {
        echo ravxx_escape_html("<pre>"."! checking for params - unserializing\nparams: "."</pre>","content");
        echo ravxx_escape_html("<pre>".htmlentities(base64_decode(ravxx_sanitize_string_or_null($_REQUEST["paramsv2"]),true))."</pre>","content");
        echo ravxx_escape_html("<pre>"."\n"."</pre>","content");
      }

      $params = sanitized_unserialize_base64_or_null($_REQUEST["paramsv2"]);
      if ( $debug )
      {
        echo ravxx_escape_html("<pre>"."params:\n"."</pre>","content");
                    echo ravxx_escape_html("<pre>".htmlentities(print_r($params, true))."</pre>","content");
      }
    }
    else
      die("nok - no params");
    if ( $debug )
      echo ravxx_escape_html("<pre>"."! check for signature\n"."</pre>","content");
    if ( isset( $_REQUEST["signature"] ) )
    {
      $signature = md5(base64_decode(ravxx_sanitize_string_or_null($_REQUEST["paramsv2"]),true) . $timestampMajor . $timestampMinor . $action . ravxx_getKey());
      if ( $debug )
        echo ravxx_escape_html("<pre>"."- raw signature (without key at the end): ***" . htmlentities(base64_decode(ravxx_sanitize_string_or_null($_REQUEST["paramsv2"]),true)) . $timestampMajor . $timestampMinor . $action . "***\n- signature: $signature\n"."</pre>","content");

      if ( !isset($_REQUEST["signature"]) || $signature != ravxx_sanitize_string_or_null($_REQUEST["signature"]) )
        die("nok - bad signature");
    }
    else
      die("nok - no signature");

    if ( $debug )
      echo ravxx_escape_html("<pre>"."! all good - performing action '" . htmlentities($action) . "'\n"."</pre>","content");

    switch ( $action )
    {
      case "isKeyValid":
        echo ravxx_escape_html("ok","content");
        break;
      case "syncurls":
        $wpdb->query("START TRANSACTION");
        $wpdb->query("DELETE FROM $table_name");
        if ( $params ) {
          foreach ($params as $rawurl)
          {
            $url = ravxx_normalizeURL($rawurl);
            //$url = preg_replace("~^[^:]+://~","",$rawurl);
            //$urlNoArgs = preg_replace("~(#|\?).*~","",$url);

            $rows_affected = $wpdb->insert( $table_name, array( 'url' => $url ) );
          }
        }
        $wpdb->query("COMMIT");
        echo ravxx_escape_html("ok","content");
        break;
      default:
        echo ravxx_escape_html("nok","content");
        break;
    }
    exit();
  }

  // get the the access url address
  $url = ravxx_full_url();
  if ( $debug )
    echo ravxx_escape_html("<pre>"."! url = " . htmlentities($url) . "\n"."</pre>","content");
  $urlNoArgs = ravxx_normalizeURL($url);
  $urlNoArgsNoWWW = preg_replace("~^www\.~","",$urlNoArgs);
  if ( $debug )
    echo ravxx_escape_html("<pre>"."! urlNoArgs = " . htmlentities($urlNoArgs) . "\n"."</pre>","content");
  $urlArgs = preg_replace("~^[^#\?]+~","",$url);
  if ( $debug )
    echo ravxx_escape_html("<pre>"."! urlArgs = " . htmlentities($urlArgs) . "\n"."</pre>","content");

  if ( $debug )
  {
    $rows = $wpdb->get_results("select * from $table_name");
    foreach ( $rows as $row )
      echo ravxx_escape_html("<pre>"."# stored url: " . htmlentities($row->{"url"}) . "\n"."</pre>","content");
  }


  // check if the access url is in the "ravpage urls" list
  $rows = $wpdb->get_results("select * from $table_name where url='$urlNoArgs' OR REPLACE(url,'www.','')='$urlNoArgsNoWWW'");
  if ( count($rows) > 0 )
  {
    $url = $rows[0]->{"url"};

    if ( preg_match("~\?~",$urlArgs) )
      $urlArgs = str_replace("?","?wpurl=" . htmlspecialchars($url) . "&",$urlArgs);
    else
      $urlArgs = "?wpurl=" . htmlspecialchars($url) . "&" . $urlArgs;
    // pass on the $_SERVER variable, to allow detection of browser/smartphone
    $fields = array("HTTP_USER_AGENT","HTTP_ACCEPT","HTTP_REFERER","HTTP_X_WAP_PROFILE","HTTP_PROFILE");
    $serverOverride = array();
    foreach ( $fields as $field )
      if ( isset($_SERVER[$field]) )
        $serverOverride[$field] = $_SERVER[$field];
    if ( $debug )
      echo ravxx_escape_html("<pre>"."Server override data: " . htmlentities(print_r($serverOverride,true)) . "\n"."</pre>","content");
    $urlArgs .= "&__requestOverride=" . rawurlencode(json_encode($serverOverride));
    $request = new WP_Http;

    if ( isset($_SERVER["HTTPS"]) && (!empty($_SERVER['HTTPS']) && ravxx_sanitize_string_or_null($_SERVER['HTTPS']) !== 'off') || isset($_SERVER['SERVER_PORT']) && ravxx_sanitize_int_or_null($_SERVER['SERVER_PORT']) == 443 )
      $result = $request->request( "https://wp.ravpage.co.il" . $urlArgs , array("sslverify"=>false));
    else
      $result = $request->request( "http://wp.ravpage.co.il" . $urlArgs );

    if ( $debug )
      echo ravxx_escape_html("<pre>"."Sending request to http://wp.ravpage.co.il" . htmlentities($urlArgs) . "\n"."</pre>","content");

    if ( is_wp_error($result) )
    {
      if ( $debug )
      {
        echo ravxx_escape_html("<pre>"."error!!!\n"."</pre>","content");
        echo ravxx_escape_html("<pre>". htmlentities(print_r($result,true))."</pre>","content");
      }
      else
        die("internal error");
    }

    status_header(200);
    $ravpageContent = $result["body"];

    if ( preg_match("~<title>(.*)</title>~Ums",$ravpageContent,$match) )
        echo ravxx_escape_html(str_replace("</title>",'</title><script>document.title = ' . json_encode($match[1]) . ';</script>',$ravpageContent),"script+content");
    else
        echo ravxx_escape_html($ravpageContent,"script+content");


    // Remove the REST API endpoint.
    remove_action( 'rest_api_init', 'wp_oembed_register_route' );

    // Turn off oEmbed auto discovery.
    add_filter( 'embed_oembed_discover', '__return_false' );

    // Don't filter oEmbed results.
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

    // Remove oEmbed discovery links.
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    add_filter( 'tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin' );

    // Remove all embeds rewrite rules.
    add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );

    // Remove filter of the oEmbed result before any HTTP requests are made.
    remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );

    // remove queued scripts
    global $wp_scripts;
    if(!empty($wp_scripts))
      $wp_scripts->queue = array();

    // remove queued styles
    global $wp_styles;
    if(!empty($wp_styles))
      $wp_styles->queue = array();

    die();
  }

}

// call function 'my_template' on every page access
add_action("init","ravxx_my_template");

function ravxx_my_plugin_menu() {
  add_options_page( 'Ravpage', 'Ravpage', 'activate_plugins', 'ravpage', 'ravxx_my_plugin_options' );
  //print_r($GLOBALS['menu']);
  //die("shit");
}

function ravxx_getKey()
{
  $key = get_option("ravpageKey");
  if ( !$key )
  {
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $key = "";
    for ($i = 0; $i < 64; $i++)
    {
      $key .= $characters[rand(0, strlen($characters)-1)];
    }
    add_option("ravpageKey", $key, null, 'no');
  }

  return $key;
}

function ravxx_my_plugin_options() {
  if ( !current_user_can( 'activate_plugins' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }

  $key = ravxx_getKey();

  echo ravxx_escape_html('<div class="wrap" style="direction:rtl">',"content");
  echo ravxx_escape_html("<p> <center> ברוכים הבאים לדף קבלת הקוד של רב דף:  </center> </p>","content");
  echo ravxx_escape_html("<p>הקוד למערכת רב-דף הוא: " . htmlentities($key) . "</p>","content");
  //$pluginRoot =  get_bloginfo('url');   in case we want to get the plugin root \ABSPATH for the folders
}

add_action( 'admin_menu', 'ravxx_my_plugin_menu' );
?>
