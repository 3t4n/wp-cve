<?php
defined('ABSPATH') or  die('Exit');
class Truepush_Initialize
{
    public function __construct()
    {
    }
    public static function init()
    {
        add_action('wp_head', array(__CLASS__, 'tpHeaderContent'), 10);
    }
     public static function tpHeaderContent()
    {
        $tpSettings = self::getTpSettings();
       ?>    
    <script type="application/javascript" src='<?php echo esc_url("https://sdki.truepush.com/sdk/v2.0.4/app.js") ?>' async></script>
    <script>
    var truepush = window.Truepush || [];
        truepush.push( function() {
        truepush.Init({
            <?php
            echo "id : \"".esc_html($tpSettings['platform_id'])."\",\n";
            echo "fromWordpress : true,\n";
        
            echo "local : false,\n";
            echo "wordpresspath : \"".esc_html(TRUEPUSH_URL)."\",\n";
           ?>
        },function(error){
          if(error) console.error(error);
        })
    });
    </script>
<?php
    }
    public static function getTpSettings() { 
    $initvalue = array(
                  'platform_id' => '',
                  'welcomeNotification' => true,
                  'welcomeNotificationTitle' => 'Welcome :)',
                  'welcomeNotificationMessage' => 'Thanks for subscribing',
                  'welcomeNotificationUrl' => '',
                  'welcomeNotificationUserInteraction' => '',
                  'tp_publishNotification' => true,
                  'truepush_api_key' => "",
                  'iconFromPost' => true,
                  'imageFromPost' => true,
                  'notificationTitle' => Truepush_Initialize::string_to_html(get_bloginfo('name'))
                  );
   $is_new_user = false;
    $tpSettings = get_option("TruepushSetting");
    if (empty( $tpSettings )) {
        $is_new_user = true;
        $tpSettings = array();
    }
    reset($initvalue);
    foreach ($initvalue as $key => $value) {

          if (!array_key_exists($key, $tpSettings)) {
              $tpSettings[$key] = $value;
          }    
    }
    if (!array_key_exists('imageFromPost', $tpSettings)) {
      if ( $is_new_user ) {
       $tpSettings['imageFromPost'] = true;
      } else {
      $tpSettings['imageFromPost'] = false;
      }
    }
    return apply_filters( 'truepush_get_settings', $tpSettings );
  }
    public static function saveTpSettings($settings) {
    $tpSettings = $settings;
    update_option("TruepushSetting", $tpSettings);
  }
  public static function maskedApiKey($rest_api_key) {
    return str_repeat('*', 50) . substr($rest_api_key, -4);
  }
  public static function string_to_html($string) {
		$CONVERSION_FLAG = ENT_QUOTES;
		if (defined('ENT_HTML401')) {
			$CONVERSION_FLAG = ENT_HTML401 | $CONVERSION_FLAG;
		}
		return html_entity_decode(str_replace("&apos;", "'", $string), $CONVERSION_FLAG, 'UTF-8');
  }
  public static function is_authorised() {
        return current_user_can('delete_users');
      }
}
?>