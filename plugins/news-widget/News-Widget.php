<?php
/*
Plugin Name: News Widget
Plugin URI: wordpress.org
Description: This plugin will show latest news from Mashable
Version: 5.2
Author: smplug-in
Author URI: https://howtodoright.com
License: GPL3
*/

function newswidget()
{
  $options = get_option("widget_newswidget");
  if (!is_array($options)){
    $options = array(
      'title' => 'Latest News',
      'news' => '7',
      'chars' => '30'
    );
  }

  // RSS 
  $rss = simplexml_load_file( 
  'http://feeds.mashable.com/Mashable'); 
  ?> 
  
  <ul> 
  
  <?php 
  // maximum number of news
  $max_widget_news = $options['news'];
  // maximum length of title
  $max_widget_length = $options['chars'];
  
  // RSS Elements 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_widget_news > 0 AND $cnt >= $max_widget_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Title
    $title = $i->title;
   
    $length = strlen($title);
        
    if($length > $max_widget_length){
      $title = substr($title, 0, $max_widget_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  </ul>
<?php  
}

function widget_newswidget($args)
{
  extract($args);
  
  $options = get_option("widget_newswidget");
  if (!is_array($options)){
    $options = array(
      'title' => 'Latest News',
      'news' => '7',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  newswidget();
  echo $after_widget;
  echo '<div style="width:10px; height:0px;overflow:hidden;">';  	
  echo "Latest news from Mashable on the WordPress platform";
  echo '</div>';
}

function newswidget_control()
{
  $options = get_option("widget_newswidget");
  if (!is_array($options)){
    $options = array(
      'title' => 'Latest News',
      'news' => '7',
      'chars' => '30'
    );
  }
  
  if($_POST['newswidget-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['newswidget-newsTitle']);
    $options['news'] = htmlspecialchars($_POST['newswidget-maxNews']);
    $options['chars'] = htmlspecialchars($_POST['newswidget-maxChar']);
    update_option("widget_newswidget", $options);
  }
?> 
  <p>
    <label for="newswidget-newsTitle">News Widget Title: </label>
    <input type="text" id="newswidget-newsTitle" name="newswidget-newsTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="newswidget-maxNews">Maximum Number of News: </label>
    <input type="text" id="newswidget-maxNews" name="newswidget-maxNews" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="newswidget-maxChar">Maximum Number of Characters: </label>
    <input type="text" id="newswidget-maxChar" name="newswidget-maxChar" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="newswidget-Submit"  name="newswidget-Submit" value="1" />
  </p>
  
<?php
}

function newswidget_init()
{
  register_sidebar_widget(__('News Widget'), 'widget_newswidget');    
  register_widget_control('News Widget', 'newswidget_control', 300, 200);
}
add_action("plugins_loaded", "newswidget_init");
?>