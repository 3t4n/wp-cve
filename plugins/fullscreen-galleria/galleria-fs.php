<?php
/******************************************************************************

Plugin Name: Fullscreen Galleria
Plugin URI: https://petridamsten.com/
Description: Fullscreen gallery for Wordpress
Version: 1.6.12
Author: Petri Damstén
Author URI: https://petridamsten.com/
License: MIT

******************************************************************************/

$fsg_ver = '1.6.12';
$fsg_db_key = 'fsg_plugin_settings';

$fsg_sites = array(
  "flickr.com" => array('Show in Flickr', 'galleria-link-flickr'),
  "1x.com" => array('Show in 1x.com', 'galleria-link-1x'),
  "500px.com" => array('Show in 500px', 'galleria-link-500px'),
  "oneeyeland.com" => array('Show in One Eyeland', 'galleria-link-oneeyeland')
);

if (file_exists(dirname(__FILE__).'/mygear.php')) {
  include 'mygear.php';
}
if (file_exists(dirname(__FILE__).'/myportfolio.php')) {
  include 'myportfolio.php';
}
if (file_exists(dirname(__FILE__).'/mydescription.php')) {
  include 'mydescription.php';
}

include 'xmp.php';

function fsg_remove_settings()
{
  global $fsg_db_key;
	delete_option($fsg_db_key);
}

class FSGPlugin {
  protected $options = array();
  protected $gps = FALSE;
  protected $photoboxid = 0;
  protected $groupid = 0;
  protected $firstpostid = -1;
  protected $used = array();
  protected $share_img_url = '';
  protected $defaults = array();

  // Helper functions

  function startswith(&$str, &$starts)
  {
    return (strncmp($str, $starts, strlen($starts)) == 0);
  }

  function endswith(&$str, &$ends)
  {
    return (substr($str, -strlen($ends)) === $ends);
  }

  function get_attachment_id_from_src($src)
  {
		global $wpdb;
    //error_log('* id for: '.$src);
		$id = $wpdb->get_var("SELECT ID FROM {$wpdb->posts} WHERE guid='$src'");
    if ($id == NULL) {
      $upload_dir = wp_upload_dir();
      $media = $upload_dir['baseurl'];
      $src = str_replace($media, "%", $src);
      //error_log('* id null. trying: '.$src);
  		$id = $wpdb->get_var("SELECT ID FROM {$wpdb->posts} WHERE guid LIKE '$src'");
    }
    return $id;
  }

  function tagarg(&$tag, $arg)
  {
    $i = stripos($tag, $arg);
    if ($i != FALSE) {
      $len = strlen($tag);
      while ($tag[$i] != "'" && $tag[$i] != '"') {
        ++$i;
        if ($i >= $len) {
          return '';
        }
      }
      ++$i;
      $j = stripos($tag, $tag[$i - 1], $i);
      if ($j != FALSE) {
        return substr($tag, $i, $j - $i);
      }
    }
    return '';
  }

  function clean_url($url)
  {
    # remove wp servers from the href
    $url = preg_replace("/i\d\.wp\..*?\//i", "", $url);
    # remove any parameters from the href
    $url = preg_replace("/\?.*/i", "", $url);
    return $url;
  }

  function href($str)
  {
    $href = $this->tagarg($str, 'href');
    if (WP_DEBUG) {
      // Make local copy work in DEBUG mode
      $bloginfo = get_bloginfo('url');
      if (strrpos($bloginfo, "local") !== FALSE) {
        $href = str_replace(array(".org", ".net", ".com"), ".local", $href);
      }
      $href = str_replace("https", "http", $href);
    }
    return $this->clean_url($href);
  }

  function links($content)
  {
    $links = array();
    $re = "/\<a[^\>]+\>/mi";
    preg_match_all($re, $content, $links);
    $links = $links[0];
    return $links;
  }

  function ob_log($ob)
  {
    ob_start();
    var_dump($ob);
    $contents = ob_get_contents();
    ob_end_clean();
    error_log($contents);
  }

  // Plugin construct

  public function __construct()
  {
    global $fsg_db_key;
    // run after gallery processed
    add_filter('the_content', array(&$this, 'content'), 99);
    add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
    add_action('wp_head', array(&$this, 'header'));
    add_action('wp_footer', array(&$this, 'footer'));
    add_filter('attachment_fields_to_edit', array(&$this, 'fields_to_edit'), 10, 2);
    add_filter('attachment_fields_to_save', array(&$this, 'fields_to_save'), 10, 2);
    add_filter('wp_read_image_metadata', array(&$this, 'add_additional_metadata'), '', 5);
    add_filter('sharing_permalink', array(&$this, 'sharing_permalink'), '', 5);
    add_shortcode('fsg_photobox', array(&$this, 'photobox_shortcode'));
    add_shortcode('fsg_photolist', array(&$this, 'photolist_shortcode'));
    add_shortcode('fsg_link', array(&$this, 'link_shortcode'));
    add_shortcode('fsg_portfolio', array(&$this, 'portfolio_shortcode'));
    add_action('admin_init', array(&$this, 'admin_init'));
    add_action('admin_menu', array(&$this, 'admin_menu'));
    register_uninstall_hook(__FILE__, 'fsg_remove_settings');
    $plugin = plugin_basename(__FILE__);
    add_filter('plugin_action_links_'.$plugin, array(&$this, 'settings_link'));

    $this->defaults = array();
    foreach ($this->settings as $key => $setting) {
      $this->defaults[$key] = $this->settings[$key]['default'];
    }
    $this->options = get_option($fsg_db_key, $this->defaults);
    if (!is_array($this->options)) {
      $this->options = $this->defaults;
    }
    //$this->ob_log($this->options);
    //$this->ob_log($this->defaults);
    foreach ($this->settings as $key => $setting) {
      if (!array_key_exists($key, $this->options)) {
        $this->options[$key] = "";
      }
      if ($setting['type'] == 'checkbox') {
        $this->options[$key] = ($this->options[$key] == 'on') ? true : false;
      }
      if ($setting['type'] == 'combobox' && empty($this->options[$key])) {
        $this->options[$key] = $this->defaults[$key];
      }
    }
    $this->options['w3tc'] = defined('W3TC');
    // This is for testing but not removing it yet
    $this->options['load_in_header'] = false;
  }

  // Settings
  protected $settings = array(
  	'theme' => array(
      'title' => 'Theme',
      'type' => 'combobox',
      'default' => 'b',
    	'items' => array('Black' => 'b', 'White' => 'w')
    ),
  	'transition' => array(
      'title' => 'Transition Type',
      'type' => 'combobox',
      'default' => 'slide',
    	'items' => array('Slide' => 'slide', 'Fade' => 'fade', 'Flash' => 'flash', 'Pulse' => 'pulse',
                       'Fade and Slide' => 'fadeslide')
    ),
  	'overlay_time' => array(
      'title' => 'Show Overlay',
      'type' => 'combobox',
      'default' => 2000,
    	'items' => array('Never' => 0, '1s' => 1000, '2s' => 2000, '4s' => 4000, '8s' => 8000,
                       'Allways' => 1000000)
    ),
  	'show_title' => array(
      'title' => 'Show Title',
      'type' => 'checkbox',
      'note' => '',
      'default' => 'on'
    ),
  	'show_caption' => array(
      'title' => 'Show Caption',
      'type' => 'checkbox',
      'note' => '',
      'default' => ''
    ),
  	'show_description' => array(
      'title' => 'Show Description',
      'type' => 'checkbox',
      'note' => '',
      'default' => 'on'
    ),
  	'show_camera_info' => array(
      'title' => 'Show Camera Info',
      'type' => 'checkbox',
      'note' => '',
      'default' => 'on'
    ),
  	'show_thumbnails' => array(
      'title' => 'Show Thumbnails',
      'type' => 'checkbox',
      'note' => '',
      'default' => 'on'
    ),
  	'show_permalink' => array(
      'title' => 'Show Permalink',
      'type' => 'combobox',
      'default' => 'page',
    	'items' => array('No' => 'no', 'Page' => 'page', 'Image' => 'image')
    ),
  	'show_sharing' => array(
      'title' => 'Show Sharing Buttons',
      'type' => 'checkbox',
      'note' => 'Needs Jetpack to work. Use "Icon + Text" or "Icon Only" for button style.',
      'default' => ''
    ),
  	'show_attachment' => array(
      'title' => 'Open FSG for Attachments pages',
      'type' => 'checkbox',
      'note' => 'Useful for sharing links so all attachment pages show fullscreen galleria.',
      'default' => 'on'
    ),
  	'show_map' => array(
      'title' => 'Show Map Button',
      'type' => 'checkbox',
      'note' => 'if GPS coordinates are present',
      'default' => 'on'
    ),
  	'image_nav' => array(
      'title' => 'Disable Image Navigation',
      'type' => 'checkbox',
      'note' => 'Show only one image at the time.',
      'default' => ''
    ),
  	'auto_start_slideshow' => array(
      'title' => 'Autostart slideshow',
      'type' => 'checkbox',
      'note' => '',
      'default' => ''
    ),
  	'true_fullscreen' => array(
      'title' => 'True fullscreen',
      'type' => 'checkbox',
      'note' => 'Experimental',
      'default' => ''
    ),
  	'load_on_demand' => array(
      'title' => 'Load FSG only when needed.',
      'type' => 'checkbox',
      'note' => 'Experimental. Seems to break some installations',
      'default' => ''
    )
  );

  function admin_init()
  {
    global $fsg_db_key;
  	register_setting($fsg_db_key, $fsg_db_key,
                     array(&$this, 'plugin_settings_validate'));
  	add_settings_section('main_section', 'Main Settings', NULL, __FILE__);
    foreach ($this->settings as $key => $setting) {
      add_settings_field($key, $setting['title'], array(&$this, $setting['type']),
                         __FILE__, 'main_section', $key);
    }
  }

  function admin_menu()
  {
  	add_options_page('Fullscreen Galleria Settings', 'Fullscreen Galleria',
                     'administrator', __FILE__, array(&$this, 'settings_page'));
  }

  function settings_page()
  {
    global $fsg_ver;
    global $fsg_db_key;
  ?>
  	<div class="wrap">
  		<div class="icon32" id="icon-options-general"><br></div>
  		<h2>Fullscreen Galleria Settings</h2>
  		<form action="options.php" method="post">
  		<?php settings_fields($fsg_db_key); ?>
  		<?php do_settings_sections(__FILE__); ?>
  		<p class="submit">
  			<input name="submit" type="submit" id="submit" class="button-primary"
               value="<?php esc_attr_e('Save Changes'); ?>" />
  		</p>
  		</form>
      <div style="text-align: center; width: 256px; line-height: 175%;">

      <img width=256 height=28 src="<?php echo plugins_url('logo.svg', __FILE__); ?>"><br>
      Version <?php echo $fsg_ver; ?><br>
      <div style="font-size: 12pt;">
      <a href="mailto:petri.damsten@gmail.com">Petri Damstén</a><br>
      <a href="https://petridamsten.com/">Website / Blog</a>
    	</div>
    	</div>
  	</div>
  <?php
  }

  function plugin_settings_validate($input)
  {
  	return $input;
  }

  function combobox($key)
  {
    global $fsg_db_key;
  	$options = get_option($fsg_db_key, $this->defaults);
  	$items = $this->settings[$key]['items'];
  	echo '<select id="'.$key.'" name="'.$fsg_db_key.'['.$key.']">';
  	foreach ($items as $k => $item) {
  		$selected = ($options[$key] == $item) ? 'selected="selected"' : '';
  		echo '<option value="'.$item.'" '.$selected.'>'.$k.'</option>';
  	}
  	echo '</select>';
  }

  function checkbox($key)
  {
    global $fsg_db_key;
  	$options = get_option($fsg_db_key, $this->defaults);
  	if (array_key_exists($key, $options) && $options[$key] == 'on') {
      $checked = ' checked="checked" ';
    } else {
      $checked = '';
    }
  	echo '<input '.$checked.' id="'.$key.'" name="'.$fsg_db_key.'['.$key.']" type="checkbox" />';
    echo '&nbsp;&nbsp;&nbsp;<i>'.$this->settings[$key]['note'].'</i>';
  }

  function settings_link($links)
  {
    $settings_link = '<a href="options-general.php?page=fullscreen-galleria/galleria-fs.php">'.
                     'Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
  }

  // Rest of the plugin

  function exifv($s)
  {
    $e = explode('/', $s);
    if (count($e) < 2 ) {
      $b = 1;
    } else {
      $b = intval($e[1]);
    }
    $a = intval($e[0]);
    $a = ($a == 0) ? 1: $a;
    $b = ($b == 0) ? 1: $b;
    return array($a, $b);
  }

  function my_gear($type, $value, $default = NULL)
  {
    global $fsg_my_serial;
    global $fsg_my_model;
    global $fsg_my_lenses;

    $name = 'fsg_my_'.$type;
    $value = preg_replace('/[\x00-\x1F]/', '', $value); # Remove non printable characters
    if (isset($$name)) {
      if (array_key_exists($value, $$name)) {
        return ${$name}[$value];
      }
    }
    return is_null($default) ? $value : $default;
  }

  function camera_round($v, $limit)
  {
    if ($v > $limit) {
      return round($v, 0);
    } else {
      return round($v, 1);
    }
  }

  function camera_info($exif)
  {
    global $fsg_my_lenses;
    $camera = '';
    $make = '';
    $lens = '';
    $focal = '';
    $f = '';
    $s = '';
    $iso = '';
    #$this->ob_log($exif);
    if (!empty($exif['UndefinedTag:0xA431'])) {
      $camera = $this->my_gear('serial', $exif['UndefinedTag:0xA431'], '');
    }
    if (empty($camera)) {
      if (!empty($exif['Model'])) {
        $camera = $this->my_gear('model', $exif['Model']);
      }
      if (!empty($exif['Make'])) {
        $make = $this->my_gear('make', $exif['Make']);
      }
    }
    if (substr($camera, 0, 4) == substr($make, 0, 4) or empty($make)) {
      $camera = $camera;
    } else {
      $camera = $make.' '.$camera;
    }
    if (!empty($exif['UndefinedTag:0xA434'])) {
      $lens = $exif['UndefinedTag:0xA434'];
    } else if (!empty($exif['LensModel'])) {
      $lens = $exif['LensModel'];
    } else if (!empty($exif['LensInfo'])) {
      $lens = $exif['LensInfo'];
    }
    $lens = $this->my_gear('lenses', $lens);
    if ($lens != '') {
      $lens = ' with '.$lens;
    }
    if (!empty($exif['FNumber'])) {
      $f = $this->exifv($exif['FNumber']);
      $f = $this->camera_round($f[0] / $f[1], 10);
      $f = ' and f/'.$f;
    }
    if (!empty($exif['ExposureTime'])) {
      $s = $this->exifv($exif['ExposureTime']);
      if ($s[0] > $s[1]) {
        $s = $this->camera_round($s[0] / $s[1], 10);
      } else {
        $s = $this->camera_round($s[1] / $s[0], 5);
        $s = '1/'.$s;
      }
      $s = ', '.$s.'sec';
    }
    if (!empty($exif['FocalLength'])) {
      $focal = $this->exifv($exif['FocalLength']);
      $focal = round($focal[0] / $focal[1]);
      $focal = ' at '.$focal.'mm';
    }
    if (!empty($exif['ISOSpeedRatings'])) {
      $iso = $exif['ISOSpeedRatings'];
      if ($iso != '') {
        $iso = ', ISO '.$iso;
      }
    }
    #error_log($camera.$lens.$focal.$f.$s.$iso);
    return $camera.$lens.$focal.$f.$s.$iso;
  }

  function add_additional_metadata($meta, $file, $sourceImageType)
  {
    $xmp = new XMPMetadata($file);
    if (empty($meta['longitude'])) $meta['longitude'] = $xmp->longitude();
    if (empty($meta['latitude'])) $meta['latitude'] = $xmp->latitude();
    if (empty($meta['info'])) $meta['info'] = $xmp->value('pdplus:FullInfo');
    if (empty($meta["aperture"])) $meta["aperture"] = $xmp->value('exif:Photo.FNumber');
    if (empty($meta["credit"] )) $meta["credit"] = $xmp->value('cc:attributionName');
    if (empty($meta["camera"])) $meta["camera"] = $xmp->value('exif:Image.Model');
    if (empty($meta["caption"])) $meta["caption"] = $xmp->value('exif:Image.ImageDescription');
    if (empty($meta["created_timestamp"])) $meta["created_timestamp"] = 
                                                $xmp->value('xmp:CreateDate');
    if (empty($meta["copyright"])) $meta["copyright"] = $xmp->value('exif:Image.Copyright');
    if (empty($meta["focal_length"])) $meta["focal_length"] = $xmp->value('exif:Photo.FocalLength');
    if (empty($meta["iso"])) $meta["iso"] = $xmp->value('exif:Photo.ISOSpeedRatings');
    if (empty($meta["shutter_speed"])) $meta["shutter_speed"] = 
                                            $xmp->value('exif:Photo.ExposureTime');
    if (empty($meta["title"])) $meta["title"] = $xmp->value('dc:title');
    if (empty($meta["orientation"])) $meta["orientation"] = $xmp->value('exif:Image.Orientation');
    if (empty($meta["keywords"])) $meta["keywords"] = $xmp->value('dc:subject');
    if (empty($meta['square-pos'])) $meta['square-pos'] = $xmp->value('pdplus:SquarePosition');
    if (empty($meta['hero-pos'])) $meta['hero-pos'] = $xmp->value('pdplus:HeroPosition');

    return $meta;
  }

  function photo_images($post, $postid, $order, $orderby, $include)
  {
      if (!empty($postid)) {
      		  $photos = get_children(array('post_parent' => $postid, 'post_status' => 'inherit',
         		    'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order,
                'orderby' => $orderby));
      } else {
        if (!empty($include)) {
          $photos = get_posts(array('post_type' => 'attachment',
              'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby,
              'include' => $include));
        } else {
          $photos = get_children(array('post_parent' => $post->ID, 'post_status' => 'inherit',
              'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order,
              'orderby' => $orderby));
        }
      }
      $images = array();
      foreach ($photos as $key => $val) {
        $images[$this->href(wp_get_attachment_link($val->ID, 'full'))] =
            array('post_id' => $val->ID, 'id' => 0, 'data' => $val,
                  'permalink' => get_permalink($val->ID).'#0');
      }
      return $images;
  }

  function photobox_shortcode($attr, $content = null)
  {
    global $post;

    extract(shortcode_atts(array(
      'rows'       => 2,
      'cols'       => 3,
      'border'     => 2,
      'maxtiles'   => 20,
      'tile'       => 0,
      'postid'     => '',
      'repeat'     => true,
      'order'      => 'ASC',
      'orderby'    => 'post__in',
      'include'    => '',
    ), $attr));

    $rows = absint($rows);
    $cols = absint($cols);
    $border = absint($border);
    $maxtiles = absint($maxtiles);
    $tile = absint($tile);
    $repeat = sanitize_key($repeat);

    $images = $this->photo_images($post, $postid, $order, $orderby, $include);
    $id = 'fsg_photobox_'.$post->ID.'_'.$this->photoboxid;
    $photobox = "fsg_photobox['".$id."'] = {rows: ".$rows.", cols: ".$cols.", border: ".
                       $border.", maxtiles: ".$maxtiles.", tile: ".
                       $tile.", repeat: ".$repeat."};";
    ++$this->photoboxid;
    return "<div id='".$id."' class='galleria-photobox'></div><script>".$photobox."</script>" .
           $this->append_json($id, $images, true);
  }

  function photolist_shortcode($attr, $content = null)
  {
    global $post;

    extract(shortcode_atts(array(
      'cols'       => 3,
      'border'     => 2,
      'tile'       => 50,
      'postid'     => '',
      'extlinks'   => 'false',
      'fixed'      => 'width',
      'order'      => 'ASC',
      'orderby'    => 'post__in',
      'include'    => '',
    ), $attr));

    $cols = absint($cols);
    $border = absint($border);
    $tile = absint($tile);
    $extlinks = sanitize_key($extlinks);
    $fixed = sanitize_key($fixed);

    $images = $this->photo_images($post, $postid, $order, $orderby, $include);
    $id = 'fsg_photobox_'.$post->ID.'_'.$this->photoboxid;
    $photolist = "fsg_photolist['".$id."'] = {cols: '".$cols."', border: ".$border.
                 ", tile: ".$tile.", extlinks: ".$extlinks.", fixed: '".$fixed."'};";
    ++$this->photoboxid;
    return "<div id='".$id."' class='galleria-photolist'></div><script>".$photolist."</script>" .
           $this->append_json($id, $images, true);
  }

  function portfolio_shortcode($attr, $content = null)
  {
    global $fsg_portfolio;
    global $wp;

    if (isset($fsg_portfolio) && array_key_exists($wp->request, $fsg_portfolio)) {
      $content = $fsg_portfolio[$wp->request]['header'];
      $includes = explode(";", $fsg_portfolio[$wp->request]['include']);
      foreach ($includes as $i => $include) {
        $shortcode = sprintf(
          '[fsg_photolist tile="%d" cols="%s" border="%d" include="%s" extlinks="%s" fixed="%s"]',
          $fsg_portfolio[$wp->request]['tile'], $fsg_portfolio[$wp->request]['cols'],
          $fsg_portfolio[$wp->request]['border'], $include,
          $fsg_portfolio[$wp->request]['extlinks'], $fsg_portfolio[$wp->request]['fixed']);
        $content .= do_shortcode($shortcode);
        if ($i < count($includes) - 1) { // not last item
          $content .= $fsg_portfolio[$wp->request]['spacer'];
        }
      }
      $content .= $fsg_portfolio[$wp->request]['footer'];
      #error_log($content);
    } else {
      $content = "Portfolio content not found.";
    }
    return $content;
  }

  function link_shortcode($attr, $content = null)
  {
    global $post;

    extract(shortcode_atts(array(
      'include'    => '',
      'class'      => '',
      'order'      => 'ASC',
      'orderby'    => 'post__in',
      'postid'     => '',
    ), $attr));

    $class = sanitize_key($class);

    if (!empty($postid)) {
		  $photos = get_children(array('post_parent' => $postid, 'post_status' => 'inherit',
   		    'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order,
          'orderby' => $orderby));
      $id = "fsg_post_".$postid;
      if (!empty($photos)) {
        $imgid = array_shift(array_values($photos))->ID;
      } else {
        $imgid = 0;
      }
    } else {
      if (!empty($include)) {
        $photos = get_posts(array('post_type' => 'attachment',
            'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby,
            'include' => $include));
        $id = "fsg_group_".$this->groupid;
        ++$this->groupid;
        $imgid = 0;
      } else {
        $photos = get_children(array('post_parent' => $post->ID, 'post_status' => 'inherit',
            'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order,
            'orderby' => $orderby));
        $id = "fsg_post_".$post->ID;
        if (!empty($photos)) {
          $imgid = array_shift(array_values($photos))->ID;
        } else {
          $imgid = 0;
        }
      }
    }
    $images = array();
    foreach ($photos as $key => $val) {
      $images[$this->href(wp_get_attachment_link($val->ID, 'full'))] =
          array('post_id' => $val->ID, 'id' => 0, 'data' => $val,
                'permalink' => get_permalink($val->ID).'#0');
    }
    $json = $this->append_json($id, $images);
    if (!empty($include)) {
      $this->used = array_merge($this->used, wp_parse_id_list($include));
    }
    reset($images);
    $first = key($images);
    if (!empty($class)) {
      $class = " class='".$class."'";
    }
    return "<a data-postid='".$id."' data-imgid='".$imgid."' href='".$first."'".
           $class.">".$content."</a>" . $json;
  }

  function fields_to_edit($form_fields, $post)
  {
    $meta = wp_get_attachment_metadata($post->ID);
    $form_fields["camera-info"] = array(
        "label" => __("Camera Info"),
        "input" => "text",
        "value" => empty($meta['image_meta']['info']) ? '' : $meta['image_meta']['info'],
        "helps" => __('Camera info for Fullscreen Galleria plugin.')
    );
    $form_fields["custom-link"] = array(
        "label" => __("Custom Link"),
        "input" => "text",
        "value" => empty($meta['image_meta']['link']) ? '' : $meta['image_meta']['link'],
        "helps" => __('Custom link for Fullscreen Galleria plugin.')
    );
    $form_fields["latitude"] = array(
        "label" => __("Latitude"),
        "input" => "text",
        "value" => empty($meta['image_meta']['latitude']) ? '' : $meta['image_meta']['latitude'],
        "helps" => __('Latitude for Fullscreen Galleria plugin.')
    );
    $form_fields["longitude"] = array(
        "label" => __("Longitude"),
        "input" => "text",
        "value" => empty($meta['image_meta']['longitude']) ? '' : $meta['image_meta']['longitude'],
        "helps" => __('Longitude for Fullscreen Galleria plugin.')
    );
    return $form_fields;
  }

  function fields_to_save($post, $attachment)
  {
    $meta = wp_get_attachment_metadata($post['ID']);
    $modified = false;
    if (isset($attachment['camera-info'])) {
      $meta['image_meta']['info'] = $attachment['camera-info'];
      $modified = true;
    }
    if (isset($attachment['custom-link'])) {
      $meta['image_meta']['link'] = $attachment['custom-link'];
      $modified = true;
    }
    if (isset($attachment['latitude'])) {
      $meta['image_meta']['latitude'] = $attachment['latitude'];
      $modified = true;
    }
    if (isset($attachment['longitude'])) {
      $meta['image_meta']['longitude'] = $attachment['longitude'];
      $modified = true;
    }
    if ($modified) {
      wp_update_attachment_metadata($post['ID'], $meta);
    }
    return $post;
  }

  function int2base($n, $b)
  {
    $validchars = 'uYn6yzcXhHIALrZ8DRdVwBSGkmsxqQJg2j9ObUM5v73NaWlCtK0ipPf14EFoTe';

    if ($n == 0) return [0];

    $result = '';
    while ($n) {
      $result .= $validchars[$n % $b];
      $n = floor($n / $b);
    }
    return $result;
  }

  function file_version($fname)
  {
    global $fsg_ver;
    return $fsg_ver.'-'.$this->int2base(filectime($fname), 62);
  }

  function enqueue_scripts()
  {
    global $post;
    $plugin_dir = WP_PLUGIN_DIR . '/fullscreen-galleria';

    if (!$this->options['load_on_demand'] ||
        !is_singular() ||
        has_shortcode($post->post_content, 'gallery') ||
        has_shortcode($post->post_content, 'fsg_photobox') ||
        has_shortcode($post->post_content, 'fsg_photolist') ||
        has_shortcode($post->post_content, 'fsg_link')) {
      $in_footer = !$this->options['load_in_header'];

      wp_enqueue_script('galleria', plugins_url('galleria-1.6.1.min.js', __FILE__), array('jquery'), '1.6.1', $in_footer);
      $v = $this->file_version($plugin_dir.'/galleria-fs.js');
      wp_enqueue_script('galleria-fs', plugins_url('galleria-fs.js', __FILE__), array('galleria'), $v, $in_footer);
      $v = $this->file_version($plugin_dir.'/galleria-fs-theme.js');
      wp_enqueue_script('galleria-fs-theme', plugins_url('galleria-fs-theme.js', __FILE__), array('galleria-fs'), $v, $in_footer);
      // register here and print conditionally
      wp_register_script('open-layers', plugins_url('ol.js', __FILE__), array('galleria-fs'), '6.5.0', $in_footer);
      wp_register_style('open-layers', plugins_url('ol.css', __FILE__), array(), '6.5.0');
      wp_enqueue_style('open-layers');
      $v = $this->file_version($plugin_dir.'/galleria-fs-'.$this->options['theme'].'.css');
      wp_register_style('galleria-fs-clr', plugins_url('galleria-fs-'.$this->options['theme'].'.css', __FILE__), array(), $v);
      wp_enqueue_style('galleria-fs-clr');
      $v = $this->file_version($plugin_dir.'/galleria-fs.css');
      wp_register_style('galleria-fs', plugins_url('galleria-fs.css', __FILE__), array(), $v);
      wp_enqueue_style('galleria-fs');
    }
  }

  function header()
  {
    //error_log('header');
    if ($this->options['load_in_header']) {
      // We don't know in header if we need this or not so always include.
      wp_print_scripts('open-layers');
    }
    echo "<script>fsg_json = {}; fsg_photobox = {}; fsg_photolist = {};</script>";
  }

  function footer()
  {
    //error_log('footer');
    global $post;
    // We call wp_print_scripts here also when gps is false so scripts get printed before
    // json/galleria loading code
    $options = '';
    $url = '';

    if (!$this->options['load_in_header']) {
      wp_print_scripts(($this->gps) ? 'open-layers' : '');
    }
  	$options = 'fsg_settings = '.json_encode($this->options).";\n";
    if ($this->gps) { // Open layers needs this
      $url = "fullscreen_galleria_url='".plugin_dir_url(__FILE__)."';\n";
    }
    $postid = "fullscreen_galleria_postid=".$this->firstpostid.";\n";
    $attachment = "fullscreen_galleria_attachment=false;\n";
    if (isset($post)) {
      if ($this->options['show_attachment'] && get_post_type($post->ID) == "attachment") {
        $type = get_post_mime_type($post->ID);
        switch ($type) {
          case 'image/jpeg':
          case 'image/png':
          case 'image/gif':
            $attachment = "fullscreen_galleria_attachment=true;\n";
            break;
        }
      }
    }
    echo "<div id=\"galleria\"></div><script>".
         $url.$postid.$options.$attachment."</script>";
  }

  function imginfo(&$meta)
  {
    // TODO Generate info from exif if there is a way to read lens id from makernote
    if (!empty($meta['image_meta']['info'])) {
      return $meta['image_meta']['info'];
    }
    return '';
  }

  function sharing_permalink($permalink, $post_id, $share_id)
  {
    if ($this->share_img_url != '') {
      return $this->share_img_url;
    }
    return $permalink;
  }

  function js_string($str)
  {
    $str = addslashes($str);
    $str = str_replace("\r", "", $str);
    $str = str_replace("\n", "<br/>", $str);
    $str = str_replace("\t", " ", $str);
    $str = str_replace("\xE2\x80\xA8", "<br/>", $str);
    $str = str_replace("\x3F", "<br/>", $str);
    return $str;
  }

  function append_json($id, &$images, $extra = false)
  {
    global $fsg_sites;
    // Write json data for galleria
    if (empty($images)) {
      return;
    }
    $json = "<script>fsg_json['".$id."'] = [\n";
    foreach ($images as $key => $val) {
      if (!in_array($val['post_id'], $this->used)) {
        $meta = wp_get_attachment_metadata($val['post_id']);
        $thumb = wp_get_attachment_image_src($val['post_id'], 'thumbnail');
        $thumb = $thumb[0];
        $bookmark = '';
        $layer_has_info = false;
        if ($this->options['overlay_time'] != 0) {
          if ($this->options['show_title'] && !empty($val['data']->post_title)) {
            $layer_has_info = true;
            $title = '<h1>'.$this->js_string($val['data']->post_title).'</h1>';
          } else {
            $title = '';
          }
          if ($this->options['show_caption'] && !empty($val['data']->post_excerpt)) {
            $layer_has_info = true;
            $caption = '<h1>'.$this->js_string($val['data']->post_excerpt).'</h1>';
          } else {
            $caption = '';
          }
          $description = '';
          if ($this->options['show_description']) {
            if (!empty($val['data']->post_content)) {
              $description .= $this->js_string($val['data']->post_content);
            }
            if (function_exists('fsg_description')) {
              $d = $this->js_string(fsg_description($val['data']->post_title));
              if ($d != '') {
                $description .= '<p class="galleria-info-extra">'.$d.'</p>';
              }
            }
            if (!empty($description)) {
              $layer_has_info = true;
              $description = "<p class=\"galleria-info-description\">".$description."</p>";
            }
          }
          if ($this->options['show_camera_info'] && !empty($meta['image_meta']['info'])) {
            $layer_has_info = true;
            $info = "<p class=\"galleria-info-camera\">".$meta['image_meta']['info']."</p>";
            $info = $this->js_string($info);
          } else {
            $info = '';
          }
          if (!empty($meta['image_meta']['link'])) {
            $layer_has_info = true;
            $links = explode(';', $meta['image_meta']['link']);
            $firstlink = $links[0];
            $link = '';
            foreach ($links as $l) {
              $t = $l;
              $c = 'galleria-link';
              foreach ($fsg_sites as $url => $sitedata) {
                //error_log($l.' '.$url);
                if (strpos($l, $url) != FALSE) {
                  $t = $sitedata[0];
                  $c = $sitedata[1];
                  break;
                }
              }
              //error_log($t.' '.$l.' '.$c);
              $link .= "<div class=\"galleria-layeritem\">".
                       "<a target=\"_blank\" title=\"".$t."\" href=\"".$l."\">".
                       "<div class=\"".$c."\"></div></a>".
                       "</div>";
            }
          } else {
            $link = '';
            $firstlink = '';
          }
          if ($this->options['show_map'] && !empty($meta['image_meta']['longitude'])) {
            $layer_has_info = true;
            $c = $meta['image_meta']['latitude'].",".$meta['image_meta']['longitude'];
            $map = "<div class=\"galleria-layeritem\">".
                        "<a id=\"fsg_map_btn\" title=\"Open Map\"".
                        " href=\"#\" onclick=\"open_map(".$c.");\">".
                        "<div class=\"galleria-link-map\"></div></a>".
                    "</div>";
            $this->gps = TRUE;
          } else {
            $map = '';
          }
          if ($this->options['show_permalink'] != 'no') {
            $layer_has_info = true;
            if ($this->options['show_permalink'] == 'page') {
              $permalink = $val['permalink'];
            } else {
              $permalink = wp_get_attachment_image_src($val['post_id'], 'full')[0];
            }
            if (!empty($permalink)) {
              $bookmark = "<div class=\"galleria-layeritem\">".
                        "<a title=\"Permalink\" href=\"".$permalink."\">".
                        "<div class=\"galleria-link-bookmark\"></div></a>".
                    "</div>";
            }
          } else {
            $bookmark = '';
          }
          $share = '';
          # Sharing_Service is a JetPack class and it must be installed for sharing to work.
          if (class_exists('Sharing_Service') && $this->options['show_sharing']) {
            $layer_has_info = true;
            $sharer = new Sharing_Service();
      		  $enabled = $sharer->get_blog_services();
            $this->share_img_url = $val['permalink'];
            $i = 0;
            $div = '<div style="padding-bottom: 20px;"></div>'; # Give room for the sharing buttons
            $div .= '<div class="galleria-layeritem sharedaddy sd-sharing-enabled">';
            $div .= '<div class="robots-nocontent sd-block sd-social sd-social-icon sd-sharing">';
            $div .= '<div class="sd-content">';
            $share = $div.'<ul>';
    			  foreach ($enabled['visible'] as $id => $service) {
    				  $share .= '<li class="share-'.$service->get_class().'">'.
                        $service->get_display(get_post($val['post_id'])).'</li>';
              ++$i;
            }
            $share .= '</ul></div></div></div>';
            $this->share_img_url = '';
            //$this->ob_log($share);
          }
        }
        $json .= "{id: ".$val['post_id'].
                       ", image: '".$key.
                       "', extlink: '".$firstlink.
                       "', thumb: '".$thumb.
                       "', permalink: '".$bookmark."'";
        if ($layer_has_info) {
          $json .= ', layer: \'<div class="galleria-infolayer">'.
                     '<div class="galleria-layeritem" style="padding-right: 20px;">'.
                     $title.$caption.$description.$info.'</div>'.
                     $link.$map.$bookmark.
                     '<div class="galleria-layeritem" style="padding-right: 20px;"></div>'.
                     $share."'";
        }
        if ($extra) {
          foreach (array("thumbnail", "medium", "large", "full") as $size) {
            $img = wp_get_attachment_image_src($val['post_id'], $size);
            $json .= ", ".$size.": ['".$img[0]."', ".$img[1].", ".$img[2]."]";
          }
        }
        $json .= "},\n";
        $images[$key]['id'] = $val['post_id'];
      }
    }
    $json = rtrim($json, ",\n");
    $json .= "\n];</script>\n";
    return $json;
  }

  // Handle gallery
  function content($content)
  {
    //error_log('** content **');

    // do not apply gallery to feed content
    if (is_feed()) {
      return $content;
    }

    global $post;
    if ($this->firstpostid == -1) {
      $this->firstpostid = $post->ID;
    }
    // Get children (images) of the post
    $children = get_children(array('post_parent' => $post->ID, 'post_status' => 'inherit',
        'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC',
        'orderby' => 'menu_order ID'));
    if (empty($children)) {
      if (substr(get_post_mime_type($post->ID), 0, 5) == 'image') {
        $children = get_posts(array('post_type' => 'attachment',
            'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID',
            'include' => $post->ID));
      }
    }
    $images = array();
    foreach ($children as $key => $val) {
      $images[$this->href(wp_get_attachment_link($key, 'full'))] =
          array('post_id' => $key, 'id' => $key, 'data' => $val,
                'permalink' => get_permalink($post->ID).'#'.$key);
    }

    //error_log('----------------------------------------------------------');
    //error_log($content);
    $links = $this->links($content);
    //$this->ob_log($links);

    // Add needed data to links
    $upload_dir = wp_upload_dir();
    $media = $upload_dir['baseurl'];
    //error_log('Media: '.$media);
    $fsg_post = array();
    foreach ($links as $link) {
      //error_log('Link: '.$link);
      if (strpos($link, 'data-postid') === false) { // test if link already has the data
        $href = $this->href($link);
        //error_log('* href: '.$href);
        if (!array_key_exists($href, $images) && $this->startswith($href, $media)) {
          // We have images from other posts (include)
          $id = $this->get_attachment_id_from_src($href);
          //error_log('* id: '.$id);
          if ($id != NULL) {
            $photos = get_posts(array('post_type' => 'attachment',
                'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID',
                'include' => $id));
            //error_log('* photos: '.count($photos));
            if (count($photos) > 0) {
                $fsg_post[$href] = array('post_id' => $id, 'id' => $id, 'data' => $photos[0],
                                         'permalink' => get_permalink($id).'#0');
            }
          }
        } else if (array_key_exists($href, $images)) {
          //error_log('* in images');
          $fsg_post[$href] = $images[$href];
        }
        if (array_key_exists($href, $fsg_post)) {
          //error_log('* in fsg_post');
          $tmp = str_replace('<a ', '<a data-postid="fsg_post_'.$post->ID.
                             '" data-imgid="'.$fsg_post[$href]['id'].'" ', $link);
          //error_log($link);
          //error_log($tmp);
          $content = str_replace($link, $tmp, $content);
        }
      }
    }
    //error_log('* append json: '.$post->ID);
    //$this->ob_log($images);
    $content = $this->append_json('fsg_post_'.$post->ID, $fsg_post) . $content;
    return $content;
  }
}

$fsgplugin = new FSGPlugin();

?>
