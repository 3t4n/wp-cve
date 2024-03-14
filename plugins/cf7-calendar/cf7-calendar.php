<?php
/*
  Plugin Name: Contact Form 7 Calendar
  Plugin URI: http://blog.inhs.web.id/cf7-calendar
  Description: JavaScript Calendar for Content, Widget and also work for Contact Form 7. To use it just type [datetimepicker input-name-field] for outside contact form 7 and [cf7cal input-name-field] inside contact form 7 form with input-name-field Unique!
  Author: Harry Sudana, I Nym
  Version: 3.0.1
  Author URI: http://inhs.web.id/
 */

add_action('activate_cf7-calendar/cf7-calendar.php', 'cf7activate');
add_action('deactivate_cf7-calendar/cf7-calendar.php', 'cf7deactivate');
add_action('admin_menu', 'cf7registeradminsettingmenu');
add_action('wp_head', 'FEloadjs', 1002);
add_filter('the_content', 'page_text_filter', 1003);
add_filter('widget_text', 'page_text_filter', 1004);

function cf7activate() {
    $opt['theme'] = 'gold';
    $opt['language'] = 'en';
    $opt['format'] = '%A, %B %e, %Y';
    $opt['showtime'] = false;
    update_option('wpcf7_calendar', $opt);
}

function cf7deactivate() {
    delete_option('wpcf7_calendar');
}

function cf7loadsetting() {
    global $wpdb, $blog_id;
    return get_option('wpcf7_calendar');
}

function cf7updatesetting($dataupdate) {
    $opt['theme'] = $dataupdate['theme'];
    $opt['language'] = $dataupdate['language'];
    $opt['format'] = $dataupdate['format'];
    $opt['showtime'] = $dataupdate['showtime'];
    update_option('wpcf7_calendar', $opt);
}

function cf7registeradminsettingmenu() {

    if (function_exists('wpcf7_admin_menu')) {
        add_submenu_page('wpcf7', 'CF7 Calendar Setting', 'CF7 Calendar', 10, basename(__FILE__), 'cf7adminsettinghtml');
    } elseif (function_exists('add_options_page')) {
        add_options_page('CF7 Calendar Setting', 'CF7 Calendar', 10, basename(__FILE__), 'cf7adminsettinghtml');
    }
}

function cf7readdirlang() {
    if ($handle = opendir(ABSPATH . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__)) . '/inc/js/lang/')) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                $thefile[] = substr($file, 0, strlen($file) - 3);
            }
        }
        closedir($handle);
        return $thefile;
    }
}

function cf7adminsettinghtml() {
    if (isset($_POST['cf7save'])) {
        cf7updatesetting($_POST);
    }

    $cf7setting = cf7loadsetting();

    $cf7showtime = array('true', 'false');
    $cf7lang = cf7readdirlang();
    $cf7themes = array('gold', 'matrix', 'steel', 'win2k');
    ?>
    <div class="wrap"> 
        <h2>CF7 Calendar</h2> 

        <form method="post" action="" > 

            <table class="widefat"> 
                <tbody> 
                    <tr> 
                        <td>
                            Select Theme
                        </td>
                        <td>
                            <select name="theme">
    <?php
    foreach ($cf7themes as $row) {
        if ($row == $cf7setting['theme'])
            $selected = "selected";
        else
            $selected = "";
        echo "<option value='" . $row . "' " . $selected . " >" . $row . "</option>";
    }
    ?>
                            </select>
                        </td>
                    </tr>
                    <tr> 
                        <td>
                            Select Languange
                        </td>
                        <td>
                            <select name="language">
    <?php
    foreach ($cf7lang as $row) {
        if ($row == $cf7setting['language'])
            $selected = "selected";
        else
            $selected = "";
        echo "<option value='" . $row . "' " . $selected . " >" . $row . "</option>";
    }
    ?>
                            </select>
                        </td>
                    </tr>
                    <tr> 
                        <td>
                            Show Time
                        </td>
                        <td>
                            <select name="showtime">
    <?php
    foreach ($cf7showtime as $row) {
        if ($row == $cf7setting['showtime'])
            $selected = "selected";
        else
            $selected = "";
        echo "<option value='" . $row . "' " . $selected . " >" . $row . "</option>";
    }
    ?>
                            </select>
                        </td>
                    </tr>
                    <tr> 
                        <td>
                            Calendar Format
                        </td>
                        <td>
                            <input name="format" id="format" type="text" value="<?php echo $cf7setting['format']; ?>" />
                            <p>
                                Legend :
                                <br />
                                %a	abbreviated weekday name<br />
                                %A	full weekday name<br />
                                %b	abbreviated month name<br />
                                %B	full month name<br />
                                %C	century number<br />
                                %d	the day of the month ( 00 .. 31 )<br />
                                %e	the day of the month ( 0 .. 31 )<br />
                                %H	hour ( 00 .. 23 )<br />
                                %I	hour ( 01 .. 12 )<br />
                                %j	day of the year ( 000 .. 366 )<br />
                                %k	hour ( 0 .. 23 )<br />
                                %l	hour ( 1 .. 12 )<br />
                                %m	month ( 01 .. 12 )<br />
                                %M	minute ( 00 .. 59 )<br />
                                %n	a newline character<br />
                                %p	``PM'' or ``AM''<br />
                                %P	``pm'' or ``am''<br />
                                %S	second ( 00 .. 59 )<br />
                                %s	number of seconds since Epoch (since Jan 01 1970 00:00:00 UTC)<br />
                                %t	a tab character<br />
                                %U, %W, %V	the week number<br />
                                %u	the day of the week ( 1 .. 7, 1 = MON )<br />
                                %w	the day of the week ( 0 .. 6, 0 = SUN )<br />
                                %y	year without the century ( 00 .. 99 )<br />
                                %Y	year including the century ( ex. 1979 )<br />
                                %% a literal % character<br />
                            </p>
                        </td>
                    </tr>
                    <tr> 
                        <td>&nbsp;

                        </td>
                        <td>
                            <input name="cf7save" id="cf7save" type="submit" value="Save Setting" class="button" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>

    <?php
}

function FEloadjs() {
    //$loadsetting = cf7loadsetting();
    $cf7setting = cf7loadsetting(); //explode(";",$loadsetting->option_value);
    $plugins_url = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__));
    ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $plugins_url; ?>/inc/css/jscal2.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo $plugins_url; ?>/inc/css/border-radius.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $plugins_url; ?>/inc/css/<?php echo $cf7setting['theme']; ?>/<?php echo $cf7setting['theme']; ?>.css" />
        <script src="<?php echo $plugins_url; ?>/inc/js/jscal2.js"></script>
        <script src="<?php echo $plugins_url; ?>/inc/js/unicode-letter.js"></script>
        <script src="<?php echo $plugins_url; ?>/inc/js/lang/<?php echo $cf7setting['language']; ?>.js"></script>

    <?php
}

function page_text_filter($content) {
    $regex = '/\[datetimepicker\s(.*?)\]/';
    return preg_replace_callback($regex, 'page_text_filter_callback', $content);
}

function page_text_filter_callback($matches) {
    $cf7setting = cf7loadsetting();
    if ($cf7setting['showtime'] == 'true')
        $htmlshowtime = "showTime : true,";
    else
        $htmlshowtime = "showTime : false,";

    $string = "<input type=\"text\" name=\"" . $matches[1] . "\" id=\"" . $matches[1] . "\" /><button type=\"reset\" id=\"f_" . $matches[1] . "\">...</button>
		<script type=\"text/javascript\">                 
                Calendar.setup({
                    inputField : \"" . $matches[1] . "\",
                    dateFormat   : \"" . $cf7setting['format'] . "\",
                    trigger    : \"f_" . $matches[1] . "\",
                    " . $htmlshowtime . "
                    onSelect   : function() { this.hide() }
                });
		</script> 
		";
    return($string);
}

function wpcf7_cf7cal_shortcode_handler($tag) {
    global $wpcf7_contact_form;

    if (!is_array($tag))
        return '';

    $type = $tag['type'];
    $name = $tag['name'];
    $options = (array) $tag['options'];
    $values = (array) $tag['values'];

    if (empty($name))
        return '';

    $atts = '';
    $id_att = '';
    $class_att = '';
    $size_att = '';
    $maxlength_att = '';

    if ('cf7cal' == $type || 'cf7cal*' == $type) {
        if (!function_exists('page_text_filter_callback')) {
            return '<em>' . __('To use Calendar, you need <a href="http://webwoke.com/wp-content/uploads/2009/07/cf7-calendar.php.txt">Calendar Module for Contact Form 7</a> uploaded') . '</em>';
        }
    }

    if ('cf7cal*' == $type)
        $class_att .= ' wpcf7-validates-as-required';

    foreach ($options as $option) {
        if (preg_match('%^id:([-0-9a-zA-Z_]+)$%', $option, $matches)) {
            $id_att = $matches[1];
        } elseif (preg_match('%^class:([-0-9a-zA-Z_]+)$%', $option, $matches)) {
            $class_att .= ' ' . $matches[1];
        } elseif (preg_match('%^([0-9]*)[/x]([0-9]*)$%', $option, $matches)) {
            $size_att = (int) $matches[1];
            $maxlength_att = (int) $matches[2];
        }
    }

    if ($id_att)
        $atts .= ' id="' . trim($id_att) . '"';

    if ($class_att)
        $atts .= ' class="' . trim($class_att) . '"';

    if ($size_att)
        $atts .= ' size="' . $size_att . '"';
    else
        $atts .= ' size="40"'; // default size

    if ($maxlength_att)
        $atts .= ' maxlength="' . $maxlength_att . '"';

    // Value
    if (is_a($wpcf7_contact_form, 'WPCF7_ContactForm') && $wpcf7_contact_form->is_posted()) {
        if (isset($_POST['_wpcf7_mail_sent']) && $_POST['_wpcf7_mail_sent']['ok'])
            $value = '';
        else
            $value = $_POST[$name];
    } else {
        $value = $values[0];
    }

    //$html ='<input type="text" name="' . $name . '" value="' . esc_attr( $value ) . '"' . $atts . ' />';
    $html = page_text_filter_callback(array('', $name));
    $validation_error = '';
    if (is_a($wpcf7_contact_form, 'WPCF7_ContactForm'))
        $validation_error = $wpcf7_contact_form->validation_error($name);

    $html = '<span class="wpcf7-form-control-wrap ' . $name . '">' . str_replace('<p>', '', $html) . $validation_error . '</span>';

    return $html;
}

if (!function_exists('wpcf7_add_shortcode')) {
    if (is_file(WP_PLUGIN_DIR . "/contact-form-7/includes/shortcodes.php")) {
        include WP_PLUGIN_DIR . "/contact-form-7/includes/shortcodes.php";
        wpcf7_add_shortcode('cf7cal', 'wpcf7_cf7cal_shortcode_handler', true);
        wpcf7_add_shortcode('cf7cal*', 'wpcf7_cf7cal_shortcode_handler', true);
    }
}
/* Validation filter */

function wpcf7_cf7cal_validation_filter($result, $tag) {
    global $wpcf7_contact_form;

    $type = $tag['type'];
    $name = $tag['name'];

    $_POST[$name] = trim(strtr((string) $_POST[$name], "\n", " "));

    if ('cf7cal*' == $type) {
        if ('' == $_POST[$name]) {
            $result['valid'] = false;
            $result['reason'][$name] = $wpcf7_contact_form->message('invalid_required');
        }
    }

    return $result;
}

add_filter('wpcf7_validate_cf7cal', 'wpcf7_cf7cal_validation_filter', 10, 2);
add_filter('wpcf7_validate_cf7cal*', 'wpcf7_cf7cal_validation_filter', 10, 2);
?>
