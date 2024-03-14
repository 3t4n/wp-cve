<?php
    /*
    Plugin Name: CrazyRocket Pop-ups
    Plugin URI: https://www.crazyrocket.io/
    Description: Wheel, Slot and Scratch Card Popup for WooCommerce! Grow your email list and boost sales.
    Version: 2.0.0
    Author: CrazyRocket
    License: GPL2
    Copyright 2023 Andrea De Santis (email : hello@crazyrocket.io)
    This program is free trial software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    */
    register_uninstall_hook(__FILE__, 'crazyrocket_uninstall');

    add_action( "wp_ajax_crazyrocket_apply_coupon", "crazyrocket_apply_coupon" );
    add_action( "wp_ajax_nopriv_crazyrocket_apply_coupon", "crazyrocket_apply_coupon" );

    function crazyrocket_apply_coupon(){
      if (isset($_POST['couponcode']))
          {
            global $woocommerce; WC()->cart->remove_coupons();

            if( WC()->cart->get_cart_contents_count() > 0) {
              $ret = WC()->cart->add_discount( $_POST['couponcode'] );
              print_r("ok");
            } else{
              print_r("no-products");
            }
         };

      wp_die(); // ajax call must die to avoid trailing 0 in your response
    }

    function crazyrocket_check_regex($path, $patterns)
    {
        $to_replace = array(
        '/(\r\n?|\n)/',
        '/\\\\\*/',
      );
        $replacements = array('|','.*');
        $patterns_fixed = preg_quote($patterns, '/');
        $regexps[$patterns] = '/^(' . preg_replace($to_replace, $replacements, $patterns_fixed) . ')$/';
        return (bool) preg_match($regexps[$patterns], $path);
    }

    function crazyrocket_show_page()
    {
        $mode = trim(get_option("CrazyRocketPageFilterMode"));
        $matched = false;
        $pagelist = trim(get_option("CrazyRocketPageFilterList"));
        if ($mode == "0" || $mode =="") {
            return true;
        }
        if ($pagelist != '') {
            if (function_exists('mb_strtolower')) {
                $pagelist = mb_strtolower($pagelist);
                $currentpath = mb_strtolower($_SERVER['REQUEST_URI']);
            } else {
                $pagelist = strtolower($pagelist);
                $currentpath = strtolower($_SERVER['REQUEST_URI']);
            }
            if (crazyrocket_check_regex($currentpath, "/wp-admin/*")) {
                return true;
            }
            $matched = crazyrocket_check_regex($currentpath, $pagelist);
            $matched = ($mode == '2')?(!$matched):$matched;
        } elseif ($mode == '2') {
            $matched = true;
        }
        return $matched;
    }

    function crazyrocket_uninstall()
    {
        if (get_option('CrazyRocketApplicationID')) {
            delete_option('CrazyRocketApplicationID');
        }

        if (get_option('CrazyRocketHideInDashboard')) {
            delete_option('CrazyRocketHideInDashboard');
        }
        if (get_option('CrazyRocketPageFilterList')) {
            delete_option('CrazyRocketPageFilterList');
      }

        if (get_option('CrazyRocketPageFilterMode')) {
            delete_option('CrazyRocketPageFilterMode');
        }


        if (get_option('CrazyRocketHideOnMobile')) {
            delete_option('CrazyRocketHideOnMobile');
        }
        if (get_option('CrazyRocketAPISecretKey')) {
            delete_option('CrazyRocketAPISecretKey');
        }
        if (get_option('CrazyRocketLanguage')) {
            delete_option('CrazyRocketLanguage');
        }


    }




    class CrazyRocketWidget
    {
        public $crazyrocketroot;

        public function __construct()
        {
            $this->crazyrocketroot = "https://www.crazyrocket.io/";
            add_action('wp_logout', 'crazyrocket_logout');
            add_action('wp_footer', array(
                &$this,
                "embedCrazyRocket"
            ));

            if (is_admin()) {
                add_action("admin_menu", array(
                    &$this,
                    "adminMenu"
                ));

                add_action('admin_init', array(
                    &$this,
                    "setOptions"
                ));

                if (get_option('CrazyRocketHideInDashboard') != true) {
                    $Path = $_SERVER['REQUEST_URI'];

                    if (strpos($Path, 'wp-admin/plugin-install.php?tab=plugin-information') === false) {
                        add_action('admin_footer', array(
                            $this,
                            'embedCrazyRocket'
                        ));
                    }
                }
            }
        }

        public function setOptions()
        {
            register_setting('crazyrocket-options', 'CrazyRocketApplicationID');
            register_setting('crazyrocket-options', 'CrazyRocketHideInDashboard');
            register_setting('crazyrocket-options', 'CrazyRocketPageFilterList');
            register_setting('crazyrocket-options', 'CrazyRocketPageFilterMode');
            register_setting('crazyrocket-options', 'CrazyRocketHideOnMobile');
            register_setting('crazyrocket-options', 'CrazyRocketAPISecretKey');
            register_setting('crazyrocket-options', 'CrazyRocketLanguage');
        }

        public function adminMenu()
        {
            add_menu_page('CrazyRocket', 'CrazyRocket', 'manage_options', 'crazyrocket', array(
                $this,
                'createAdminPage'
            ), plugins_url('images/crazyrocket-icon.png', __FILE__));
        }

        public function getSignupUrl()
        {
            return $this->crazyrocketroot . 'home/install?utm_source=wordpress&utm_medium=admin&s=pro&t=12&fzsiteurl=' . urlencode(site_url()) . '&p=wordpress&e=' . urlencode(get_option('admin_email')) . '&sip=' . $_SERVER['REMOTE_ADDR'] . '&un=' . urlencode(wp_get_current_user()->display_name);
        }



        public function createAdminPage()
        {
            $code = get_option('CrazyRocketApplicationID'); ?>
<style>
        #crazyrocket-options ul { margin-left: 10px; }
        #crazyrocket-options ul li { margin-left: 15px; list-style-type: disc;}
        #crazyrocket-options h1 {margin-top: 5px; margin-bottom:10px; color: #00557f}
        .fz-span { margin-left: 23px;}


    .crazyrocket-signup-button {
      float: left;
      vertical-align: top;
      width: auto;
      height: 30px;
      line-height: 30px;
      padding: 10px;
      font-size: 22px;
      color: white;
      text-align: center;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
      background: #c0392b;
      border-radius: 5px;
      border-bottom: 2px solid #b53224;
      cursor: pointer;
      -webkit-box-shadow: inset 0 -2px #b53224;
      box-shadow: inset 0 -2px #b53224;
      text-decoration: none;
      margin-top: 10px;
      margin-bottom: 10px;
      clear: both;
    }

    a.crazyrocket-signup-button:hover {
      cursor: pointer;
      color: #f8f8f8;
    }


</style>
<div id="crazyrocket-options" style="width:500px;margin-top:10px;">
    <div style="float: left; width: 500px; text-align:center; margin-bottom:20px">
        <?php
            echo '<a target="_blank" href="https://www.crazyrocket.io?utm_source=wp-plugin">';
            echo '<img style=" border-radius:5px;max-width:200px; border:0px;" src="' . plugins_url('images/logo-small.png', __FILE__) . '" > ';
            echo '</a>'; ?>
            <div style='clear:both'>
        <?php

            if ($code != '') {
                echo '<a style="margin-right:20px" target="_blank" href="https://dashboard.crazyrocket.io?utm_source=wp-plugin">';
                echo 'Dashboard ';
                echo '</a>';
            }

            $support_ref="";
            if($code !="")   {
                $support_ref="&ref=".$code;
            }
            echo '<a target="_blank" href="https://www.crazyrocket.io/support?utm_source=wp-plugin-help'. $support_ref.'">';
            echo 'Support & Docs';
            echo '</a>'; ?>
</div>
    </div>
    <div style="float: left; margin-left: 10px; width: 500px; background-color:#f8f8f8; padding: 10px; border-radius: 5px;">
        <h2>App ID and Secret Key</h2>

        <?php

            if ($code == '') {
                echo "<a class='crazyrocket-signup-button' target='_blank' href='" . $this->getSignupUrl() . "'>Click here to create your account!</a>'";
            } ?>

        <script type="text/javascript">
            jQuery(document).ready(function () {
                    function updateFilterPanel() {
                if (jQuery("#CrazyRocketPageFilterMode").val() != "0") {
                    jQuery("#CrazyRocketPageFilterDiv").show()
                } else {
                    jQuery("#CrazyRocketPageFilterDiv").hide();
                }
            };
                jQuery("#CrazyRocketPageFilterMode").change(function () {
                    updateFilterPanel();
                });
                  updateFilterPanel();
            })
        </script>
        <div style="clear: both"></div>

        <form method="post" action="options.php">
            <?php
                settings_fields('crazyrocket-options'); ?>
            <h4>Enter your App ID</h4>
            <p>
                <input type="text" style="width: 400px" name="CrazyRocketApplicationID" id="CrazyRocketApplicationID" value="<?php echo(get_option("CrazyRocketApplicationID")); ?>" maxlength="999" />

            </p>
            <h4>Enter your Secret Key</h4>
            <p>
                <input type="text" style="width: 400px" name="CrazyRocketAPISecretKey" id="CrazyRocketAPISecretKey" value="<?php echo(get_option("CrazyRocketAPISecretKey")); ?>" maxlength="50" />

            </p>

            <input type="submit" value="<?php
       echo(_e("Save Changes")); ?>" /><br /> <br />
            <hr /><br />
            <h1>Options</h1>
            <br />

                              <!-- Hide in Admin -->
                <input type="checkbox" id="CrazyRocketHideInDashboard" name="CrazyRocketHideInDashboard" <?php echo(get_option("CrazyRocketHideInDashboard") == true ? 'checked="checked"' : ''); ?>>
                <strong>Hide in WordPress Admin</strong> - check this to hide the widget on the WP Admin. <br />
                <br />
                <!-- Hide on Mobile -->
                <input type="checkbox" id="CrazyRocketHideOnMobile" name="CrazyRocketHideOnMobile" <?php echo(get_option("CrazyRocketHideOnMobile") == true ? 'checked="checked"' : ''); ?>>
                <strong>Hide on Mobile Devices</strong> - check this to hide the widget on mobile devices<br />
                <br />
                <strong>PAGE FILTERS</strong><br /><br>
           Show CrazyRocket
                <select id="CrazyRocketPageFilterMode" name="CrazyRocketPageFilterMode">
                    <option <?php echo(get_option("CrazyRocketPageFilterMode") == "0" || get_option("CrazyRocketPageFilterMode") == "" ? 'selected="selected"' : ''); ?> value="0">on all pages</option>
                    <option <?php echo(get_option("CrazyRocketPageFilterMode") == "1" ? 'selected="selected"' : ''); ?> value="1">only on the listed pages</option>
                    <option <?php echo(get_option("CrazyRocketPageFilterMode") == "2" ? 'selected="selected"' : ''); ?> value="2">on all pages except those listed</option>
                </select>
                <div id="CrazyRocketPageFilterDiv" style="display:none">
                    <ul>
                        <li>Enter only one path per line</li>
                        <li>Always start the path with a forward slash (/)</li>
                        <li>Use '*' for the wildcard (Ex. /2014/posts/* to select all the posts)</li>
                    </ul>


                    <textarea style="width: 450px; height: 90px" name="CrazyRocketPageFilterList" id="CrazyRocketPageFilterList"><?php echo(get_option("CrazyRocketPageFilterList")); ?></textarea>

                </div><br /><br>
                  <strong>WIDGET LANGUAGE</strong><br /><br>
             <select id="CrazyRocketLanguage" name="CrazyRocketLanguage">
                  <option <?php echo(get_option("CrazyRocketLanguage") == "auto" || get_option("CrazyRocketLanguage") == "" ? 'selected="selected"' : ''); ?> value="auto">auto (use browser language)</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "en" ? 'selected="selected"' : ''); ?> value="en">English</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "es" ? 'selected="selected"' : ''); ?> value="es">Spanish</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "fr" ? 'selected="selected"' : ''); ?> value="fr">French</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "it" ? 'selected="selected"' : ''); ?> value="it">Italian</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "bg" ? 'selected="selected"' : ''); ?> value="bg">Bulgarian</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "cs" ? 'selected="selected"' : ''); ?> value="cs">Czech</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "da" ? 'selected="selected"' : ''); ?> value="da">Danish</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "de" ? 'selected="selected"' : ''); ?> value="de">German (Standard)</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "fa" ? 'selected="selected"' : ''); ?> value="fa">Farsi</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "fi" ? 'selected="selected"' : ''); ?> value="fi">Finnish</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "he" ? 'selected="selected"' : ''); ?> value="he">Hebrew</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "ja" ? 'selected="selected"' : ''); ?> value="ja">Japanese</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "nl" ? 'selected="selected"' : ''); ?> value="nl">Dutch</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "pl" ? 'selected="selected"' : ''); ?> value="pl">Polish</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "pt-BR" ? 'selected="selected"' : ''); ?> value="pt-BR">Portuguese (Brazil)</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "pt-PT" ? 'selected="selected"' : ''); ?> value="pt-PT">Portuguese (Portugal)</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "ru" ? 'selected="selected"' : ''); ?> value="ru">Russian</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "sk" ? 'selected="selected"' : ''); ?> value="sk">Slovak</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "sr" ? 'selected="selected"' : ''); ?> value="sr">Serbian</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "sv" ? 'selected="selected"' : ''); ?> value="sv">Swedish</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "tr" ? 'selected="selected"' : ''); ?> value="tr">Turkish</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "vi" ? 'selected="selected"' : ''); ?> value="vi">Vietnamese</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "zh" ? 'selected="selected"' : ''); ?> value="zh">Chinese</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "el" ? 'selected="selected"' : ''); ?> value="el">Greek</option>
                  <option <?php echo(get_option("CrazyRocketLanguage") == "ro" ? 'selected="selected"' : ''); ?> value="ro">Romanian</option>


                </select>

                 <br /><br />
                <input type="submit" value="<?php
       echo(_e("Save Changes")); ?>" /><br /> <br />
        </form>
    </div>
</div>
<?php
        }
        public function embedCrazyRocket()
        {
            $e    = '';
            $code = get_option('CrazyRocketApplicationID');

            if ($code == '') {
                return;
            }

            $secret = get_option('CrazyRocketAPISecretKey');
            $loadscript = "https://cdn.crazyrocket.io/widget/scripts/crazyrocket.start.js?id=".  $code;

            if (wp_is_mobile() &&  get_option('CrazyRocketHideOnMobile')) {
                return;
            }

            if (!crazyrocket_show_page()) {
                return;
            }

            if (get_option('CrazyRocketLanguage') != "auto") {
                $api = '<script type="text/javascript">' . 'var CrazyRocket = CrazyRocket || { };' .
                      'CrazyRocket.language = ' . json_encode(get_option('CrazyRocketLanguage')) . ';'.
                      '</script>';
                echo($api);
            }

            $e = '<!-- CrazyRocket -->' .
             '<script type="text/javascript">' .
             'var crazyrocket_ajax_url ="'. admin_url( 'admin-ajax.php' ) .'";'.
             '(function () { ' .
             'var crz = document.createElement("script"); crz.type = "text/javascript"; crz.async = true;' .
             'crz.src = "'.  $loadscript . '";' .
             'var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(crz, s)})()' .
             '</script>';
            echo($e);

        }
    }
    new CrazyRocketWidget();
?>
