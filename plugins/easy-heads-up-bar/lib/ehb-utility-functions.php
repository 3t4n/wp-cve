<?php
/**
* Include RSS Feed function
* **/
if (is_admin()) {
  include_once(ABSPATH . WPINC . '/feed.php');
}
/**
* @name ehb_get_feeds
**/
function ehb_get_feeds($url=null, $args)
{
  $defaults = array(
                'id'=>null,
                'ele_class'=>'easy-rss',
                'feed_items'=>5,
                'show_sub_link'=>true,
                'show_content'=>false,
                'debug'=>false,
              );
  $args = wp_parse_args($args, $defaults); 
  $args = (object)$args;
  if ($url==null) return false;
  // Get a SimplePie feed object from the specified feed source.
  $rss = fetch_feed($url);// http://feeds.feedburner.com/easysignup
  if ( is_wp_error($rss) ) return; // bad feed

  if (!is_wp_error( $rss ) ) : // Checks that the object is created correctly 
    // Figure out how many total items there are, but limit it to 5. 
    $maxitems = $rss->get_item_quantity($args->feed_items); 
    // Build an array of all the items, starting with element 0 (first element).
    $rss_items = $rss->get_items(0, $maxitems); 
  endif;
  
  $return  = '';
  $return .= "<ul class='{$args->ele_class}' id='{$args->id}'>";
  if ($maxitems == 0) $return .= '<li>No items.</li>';
  
  // Loop through each feed item and display each item as a hyperlink.
  foreach ( $rss_items as $item ) :
    $return .= "<li>";
      $return .= "<a href='{$item->get_permalink()}'";
      $return .= "title='Posted ".$item->get_date('j F Y | g:i a')."'>";
      $return .= esc_html($item->get_title());
      $return .= "</a>";
      if ($args->show_content):
        $return .= "<ul>";
          $return .= "<li>";
            $return .= $item->get_content();
          $return .= "</li>";
        $return .= "</ul>";
      endif;
    $return .= "</li>";
  endforeach; 
  if($args->debug): $return .= "<pre>"; $return .= print_r( $rss_items, true ); $return .= "</pre>"; endif; // debug
  $return .= "</ul>";
  if ($args->show_sub_link):
    $return .= "<p>";
      $return .= "<a href='{$url}' rel='alternate' type='application/rss+xml'><img src='http://www.feedburner.com/fb/images/pub/feed-icon16x16.png' alt='' style='vertical-align:middle;border:0'></a>&nbsp;";
      $return .= "<a href='{$url}' rel='alternate' type='application/rss+xml'>Subscribe in a reader</a>";
    $return .= "</p>";
  endif;
  return $return;
} // ends the function ehb_get_feeds


// RSS Feeds for Help tabs
function ehb_news() // has news feed
{ 
  $args = array(
    'id'=>'ehb_news_feed',
    'ele_class'=>'easy-rss',
    'feed_items'=>5,
    'show_sub_link'=>true,
    'show_content'=>true
  );
  return ehb_get_feeds("http://feeds.feedburner.com/EasySignUpPluginNews",$args);
}

function ehb_pro()
{
  $args = array(
    'id'=>'ehb_extras_feed',
    'ele_class'=>'easy-rss',
    'feed_items'=>5,
    'show_sub_link'=>true,
    'show_content'=>true
  );
  $url = "http://feeds.feedburner.com/EasySignUpExtras";
  return ehb_get_feeds($url,$args); 
}

function ehb_plugin_url($path='')
{
  global $wp_version;
  if ( version_compare( $wp_version, '2.8', '<' ) ) { // Using WordPress 2.7
    $folder = dirname( plugin_basename( __FILE__ ) );
    if ( '.' != $folder )
      $path = path_join( ltrim( $folder, '/' ), $path );
    return plugins_url( $path );
  }
  return plugins_url( $path, __FILE__ );
}


/**
 * Function to calculate date or time difference.
 * 
 * Function to calculate date or time difference. Returns an array or
 * false on error.
 *
 * @author       J de Silva                             <giddomains@gmail.com>
 * @copyright    Copyright &copy; 2005, J de Silva
 * @link         http://www.gidnetwork.com/b-16.html    Get the date / time difference with PHP
 * @param        string                                 $start
 * @param        string                                 $end
 * @return       array
 */
function ehu_check_date( $start, $end )
{
    $uts['start']      =    strtotime( $start );
    $uts['end']        =    strtotime( $end );
    if( $uts['start']!==-1 && $uts['end']!==-1 )
    {
        if( $uts['end'] > $uts['start'] )
        {
            $diff    =    $uts['end'] - $uts['start'];
            if( $days=intval((floor($diff/86400))) )
                $diff = $diff % 86400;
            if( $hours=intval((floor($diff/3600))) )
                $diff = $diff % 3600;
            if( $minutes=intval((floor($diff/60))) )
                $diff = $diff % 60;
            $diff    =    intval( $diff );            
            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
    return false;
}

function array_random($arr, $num = 1) {
    shuffle($arr);
    
    $r = array();
    for ($i = 0; $i < $num; $i++) {
        $r[] = $arr[$i];
    }
    return $num == 1 ? $r[0] : $r;
}


//EOF 