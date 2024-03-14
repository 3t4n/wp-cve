<?php
/*
  Plugin Name: Simple QR Code Widget
  Plugin URI: https://nemezisproject.co.uk/2012/01/29/simple-qr-code-widget-for-wordpress
  Description: Simple QR Code Generator Widget based on <a href="http://code.google.com/apis/chart/infographics/docs/qr_codes.html" title="Google Chart Tools">Google Chart Tools</a>
  Author: Pawel Zareba
  Version: 1.10
  Author URI: http://nemezisproject.co.uk/
  Docs: http://code.google.com/apis/chart/infographics/docs/qr_codes.html
 */

function qrCodeGenerator(
  $width = '100', 
  $height = '100', 
  $uri = null
) {
    $title = get_the_title();

    if(empty($title)){
      $title = get_bloginfo('name');
    }

    if(empty($uri)){
      global $wp;
      $uri = home_url(add_query_arg(array(), $wp->request));
    }
    
    echo sprintf(
      '<img src="//chart.apis.google.com/chart?cht=%1$s&chs=%2$dx%3$d&chl=%4$s&choe=%5$s" alt="%6$s" />', 
      'qr', 
      (int) $width, 
      (int) $height, 
      htmlspecialchars($uri), 
      'UTF-8', 
      htmlspecialchars($title)
    );

}

function qrCode_widget($args) {

    extract($args);

    $options = get_option('qrCode_widget');

    if (!is_array($options)) {
        $options = array(
            'title' => 'QR Code',
            'width' => '100',
            'height' => '100',
            'uri' => null
            );
    }

    echo $before_widget;
    echo $before_title;
    echo $options['title'];
    echo $after_title;

    qrCodeGenerator($options['width'], $options['height'], $options['uri']);

    echo $after_widget;
}

function qrCode_control() {

    $options = get_option('qrCode_widget');

    if (!is_array($options)) {
        $options = array(
            'title' => 'QR Code',
            'width' => '100',
            'height' => '100',
            'uri' => null
            );
    }

    if (isset($_POST['qrCodeSubmit'])) {

        $options['title'] = htmlspecialchars($_POST['widgetTitle']);
        $options['height'] = (int) $_POST['widgetHeight'];
        $options['width'] = (int) $_POST['widgetWidth'];
        $options['uri'] = htmlspecialchars($_POST['widgetUri']);

        update_option('qrCode_widget', $options);
    }
    ?>
    <div class="widget-content">

       <p>
           <label for="widgetTitle">Widget Title: </label>
           <input type="text" 
                  id="widgetTitle" 
                  name="widgetTitle" 
                  class="widefat" 
                  value="<?php echo $options['title']; ?>"/>
       </p>
       
       <p>
           <label for="widgetHeight">
            Widget Height (<output id="widgetHeightInfo"><?php echo $options['height']; ?></output>):
           </label>
           <input type ="range" 
                  min ="10" 
                  max="450" 
                  step ="1"
                  pattern="\d+"
                  onchange="widgetHeightInfo.value=value"
                  id="widgetHeight" 
                  name="widgetHeight" 
                  class="widefat" 
                  value="<?php echo $options['height']; ?>"/>   
       </p>

       <p>
           <label for="widgetWidth">
            Widget Width (<output id="widgetWidthInfo"><?php echo $options['width']; ?></output>):
           </label>
           <input type ="range" 
                  min ="10" 
                  max="450" 
                  step ="1" 
                  pattern="\d+"
                  onchange="widgetWidthInfo.value=value"
                  id="widgetWidth"
                  name="widgetWidth" 
                  class="widefat" 
                  value="<?php echo $options['width']; ?>"/>
       </p>

       <p>
           <label for="widgetUri">URl: </label>
           <input type="text" 
                  id="widgetUri" 
                  name="widgetUri" 
                  class="widefat" 
                  value="<?php echo $options['uri']; ?>"/>
           <small>When empty defaults to the current page url.</small>
       </p>

       <p>  
           <input type="hidden" id="qrCodeSubmit" name="qrCodeSubmit" value="1" />
       </p>

   </div>

<?php
}

function qrCode_init() {
    wp_register_sidebar_widget('qr_widget', 'QR Code Widget', 'qrCode_widget', null);
    wp_register_widget_control('qr_widget', 'QR Code Widget', 'qrCode_control', null, null);
}

add_action('plugins_loaded', 'qrCode_init');