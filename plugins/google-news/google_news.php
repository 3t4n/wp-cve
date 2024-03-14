<?php
/*
Plugin Name: Google News
Description: Displays a selectable Google News RSS feed, inline, widget or in theme.
Version:     2.5.1
Author:      Olav Kolbu
Author URI:  http://www.kolbu.com/
Plugin URI:  http://www.kolbu.com/2008/04/07/google-news-plugin/
License:     GPL

Minor parts of WordPress-specific code from various other GPL plugins.

TODO: Multiple widget instances support (possibly)
      Internationalize more output
      See if nofollow can/should be added on links
*/
/*
Copyright (C) 2009 kolbu.com (olav AT kolbu DOT com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

include_once(ABSPATH . WPINC . '/rss.php');

global $google_news_instance;

if ( ! class_exists('google_news_plugin')) {
    class google_news_plugin {

        // So we don't have to query database on every replacement
        var $settings;

        var $regions = array(
            'Australia' => 'au',
            'India' => 'in',
            'Israel' => 'en_il',
            'Malaysia' => 'en_my',
            'New Zealand' => 'nz',
            'Pakistan' => 'en_pk',
            'Philippines' => 'en_ph',
            'Singapore' => 'en_sg',
            '&#1575;&#1604;&#1593;&#1575;&#1604;&#1605; &#1575;&#1604;&#1593;&#1585;&#1576;&#1610; (Arabic)' => 'ar_me',
            '&#20013;&#22269;&#29256; (China)' => 'cn',
            '&#39321;&#28207;&#29256; (Hong Kong)' => 'hk',
            '&#2349;&#2366;&#2352;&#2340; (India)' => 'hi_in',
            '&#2980;&#2990;&#3007;&#2996;&#3021; (India)' => 'ta_in',
            '&#3374;&#3378;&#3375;&#3390;&#3379;&#3330; (India)' => 'ml_in',
            '&#3108;&#3142;&#3122;&#3137;&#3095;&#3137; (India)' => 'te_in',
            '&#1497;&#1513;&#1512;&#1488;&#1500; (Israel)' => 'iw_il',
            '&#26085;&#26412; (Japan)' => 'jp',
            '&#54620;&#44397; (Korea)' => 'kr',
            '&#21488;&#28771;&#29256; (Taiwan)' => 'tw',
	    'Việt Nam (Vietnam)' => 'vi_vn',
            '-------------' => 'us',
            'België' => 'nl_be',
            'Belgique' => 'fr_be',
            'Botswana' => 'en_bw',
            'Česká republika' => 'cs_cz',
            'Deutschland' => 'de',
            'España' => 'es',
            'Ethiopia' => 'en_et',
            'France' => 'fr',
            'Ghana' => 'en_gh',
            'Ireland' => 'en_ie',
            'Italia' => 'it',
            'Kenya' => 'en_ke',
            'Magyarország' => 'hu_hu',
            'Namibia' => 'en_na',
            'Nederland' => 'nl_nl',
            'Nigeria' => 'en_ng',
            'Norge' => 'no_no',
            'Österreich' => 'de_at',
            'Polska' => 'pl_pl',
            'Portugal' => 'pt:PT_pt',
            'Schweiz' => 'de_ch',
            'South Africa' => 'en_za',
            'Suisse' => 'fr_ch',
            'Sverige' => 'sv_se',
            'Tanzania' => 'en_tz',
            'Türkiye' => 'tr_tr',
            'Uganda' => 'en_ug',
            'U.K.' => 'uk',
            'Zimbabwe' => 'en_zw',
            '&#917;&#955;&#955;&#940;&#948;&#945; (Greece)' => 'el_gr',
            '&#1056;&#1086;&#1089;&#1089;&#1080;&#1103; (Russia)' => 'ru_ru',
	    '&#1059;&#1082;&#1088;&#1072;&#1080;&#1085;&#1072; (Ukraine)' => 'ru_ua',
	    '&#1059;&#1082;&#1088;&#1072;&#1111;&#1085;&#1072; (Ukraine)' => 'uk_ua',
            '------------' => 'us',
            'Argentina' => 'es_ar',
            'Brasil' => 'pt:BR_br',
            'Canada English' => 'ca',
            'Canada Français' => 'fr_ca',
            'Chile' => 'es_cl',
            'Colombia' => 'es_co',
            'Cuba' => 'es_cu',
            'Estados Unidos' => 'es_us',
            'México' => 'es_mx',
            'Perú' => 'es_pe',
            'U.S.' => 'us',
            'Venezuela' => 'es_ve',
        );

        var $newstypes = array(
            'All' => '',
            'Top News' => 'h',
            'Foreign' => 'w',
            'Domestic' => 'n',
            'Business' => 'b',
            'Sci/Tech' => 't',
            'Health' => 'm',
            'Sports' => 's',
            'Entertainment' => 'e',
        );

        var $outputtypes = array(
            'Standard' => '',
            'Text Only' => 't',
            'With Images' => '&imv=1',
        );

        var $desctypes = array(
            'Short' => '',
            'Long' => 'l',
        );

        // Constructor
        function google_news_plugin() {

            // Form POSTs dealt with elsewhere
            if ( is_array($_POST) ) {
                if ( $_POST['google_news-widget-submit'] ) {
                    $tmp = $_POST['google_news-widget-feed'];
                    $alloptions = get_option('google_news');
                    if ( $alloptions['widget-1'] != $tmp ) {
                        if ( $tmp == '*DEFAULT*' ) {
                            $alloptions['widget-1'] = '';
                        } else {
                            $alloptions['widget-1'] = $tmp;
                        }
                        update_option('google_news', $alloptions);
                    }
                } else if ( $_POST['google_news-options-submit'] ) {
                    // noop
                } else if ( $_POST['google_news-submit'] ) {
                    // noop
                }
            }

	    add_filter('the_content', array(&$this, 'insert_news')); 
            add_action('admin_menu', array(&$this, 'admin_menu'));
            add_action('plugins_loaded', array(&$this, 'widget_init'));

            // Hook for theme coders/hackers
            add_action('google_news', array(&$this, 'display_feed'));

            // Makes it backwards compat pre-2.5 I hope
            if ( function_exists('add_shortcode') ) {
                add_shortcode('google-news', array(&$this, 'my_shortcode_handler'));
             }

        }

        // *************** Admin interface ******************

        // Callback for admin menu
        function admin_menu() {
            add_options_page('Google News Options', 'Google News',
                             'administrator', __FILE__, 
                              array(&$this, 'plugin_options'));
            add_management_page('Google News', 'Google News', 
                                'administrator', __FILE__,
                                array(&$this, 'admin_manage'));
               
        }

        // Settings -> Google News
        function plugin_options() {

           if (get_bloginfo('version') >= '2.7') {
               $manage_page = 'tools.php';
            } else {
               $manage_page = 'edit.php';
            }
            print <<<EOT
            <div class="wrap">
            <h2>Google News</h2>
            <p>This plugin allows you to define a number of Google News 
               feeds and have them displayed anywhere in content, in a widget
               or in a theme. Any number of inline replacements or theme
               inserts can be made, but only one widget instance is
               permitted in this release. To use the feeds insert one or more
               of the following special html comments or Shortcodes 
               anywhere in user content. Note that Shortcodes, i.e. the
               ones using square brackets, are only available in 
               WordPress 2.5 and above.<p>
               <ul><li><b>&lt;--google-news--&gt</b> (for default feed)</li>
               <li><b>&lt;--google-news#feedname--&gt</b></li>
               <li><b>[google-news]</b> (also for default feed)</li>
               <li><b>[google-news name="feedname"]</b></li></ul><p>
               To insert in a theme call <b>do_action('google_news');</b> or 
               alternatively <b>do_action('google_news', 'feedname');</b><p>
               To manage feeds, go to <a href="$manage_page?page=google-news/google_news.php">Manage -> Google News</a>, where you will also find more information.<p>
               <a href="http://www.kolbu.com/donations/">Donations Page</a>... ;-)<p>
               <a href="http://www.kolbu.com/2008/04/07/google-news-plugin/">Widget Home Page</a>, leave a comment if you have questions etc.<p>
               <a href="http://www.google.com/support/news/bin/answer.py?hl=en&answer=59255">Google Terms Of Use</a><p>
    

EOT;
        }

        // Manage -> Google News
        function admin_manage() {
            // Edit/delete links
            $mode = trim($_GET['mode']);
            $id = trim($_GET['id']);

            $this->upgrade_options();

            $alloptions = get_option('google_news');

            $flipregions     = array_flip($this->regions);
            $flipnewstypes   = array_flip($this->newstypes);
            $flipoutputtypes = array_flip($this->outputtypes);
            $flipdesctypes   = array_flip($this->desctypes);

            if ( is_array($_POST) && $_POST['google_news-submit'] ) {

                $newoptions = array();
                $id                       = $_POST['google_news-id'];

                $newoptions['name']       = $_POST['google_news-name'];
                $newoptions['title']      = $_POST['google_news-title'];
                $newoptions['region']     = $_POST['google_news-region'];
                $newoptions['newstype']   = $_POST['google_news-newstype'];
                $newoptions['outputtype'] = $_POST['google_news-outputtype'];
                $newoptions['desctype']   = $_POST['google_news-desctype'];
                $newoptions['numnews']    = $_POST['google_news-numnews'];
                $newoptions['query']      = $_POST['google_news-query'];
                $newoptions['feedtype']   = $flipregions[$newoptions['region']].' : '.
                                            $flipnewstypes[$newoptions['newstype']];

                if ( $alloptions['feeds'][$id] == $newoptions ) {
                    $text = 'No change...';
                    $mode = 'main';
                } else {
                    $alloptions['feeds'][$id] = $newoptions;
                    update_option('google_news', $alloptions);
 
                    $mode = 'save';
                }
            } else if ( is_array($_POST) && $_POST['google_news-options-cachetime-submit'] ) {
                if ( $_POST['google_news-options-cachetime'] != $alloptions['cachetime'] ) {
                    $alloptions['cachetime'] = $_POST['google_news-options-cachetime'];
                    update_option('google_news', $alloptions);
                    $text = "Cache time changed to {$alloptions[cachetime]} seconds.";
                } else {
                    $text = "No change in cache time...";
                }
                $mode = 'main';
            }

            if ( $mode == 'newfeed' ) {
                $newfeed = 0;
                foreach ($alloptions['feeds'] as $k => $v) {
                    if ( $k > $newfeed ) {
                        $newfeed = $k;
                    }
                }
                $newfeed += 1;

                $text = "Please configure new feed and press Save.";
                $mode = 'main';
            }

            if ( $mode == 'save' ) {
                $text = "Saved feed {$alloptions[feeds][$id][name]} [$id].";
                $mode = 'main';
            }

            if ( $mode == 'edit' ) {
                if ( ! empty($text) ) {
                     echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>';
                }
                $text = "Editing feed {$alloptions[feeds][$id][name]} [$id].";

                $edit_id = $id;
                $mode = 'main';
            }

            if ( $mode == 'delete' ) {

                $text = "Deleted feed {$alloptions[feeds][$id][name]} [$id].";
                
                unset($alloptions['feeds'][$id]);

                update_option('google_news', $alloptions);
 
                $mode = 'main';
            }

            // main
            if ( empty($mode) or ($mode == 'main') ) {

                if ( ! empty($text) ) {
                     echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>';
                }
                print '<div class="wrap">';
                print ' <h2>';
                print _e('Manage Google News Feeds','google_news');
                print '</h2>';
                print ' <table id="the-list-x" width="100%" cellspacing="3" cellpadding="3">';
                print '  <thead>';
                print '   <tr>';
                print '    <th scope="col">';
                print _e('Key','google_news');
                print '</th>';
                print '    <th scope="col">';
                print _e('Name','google_news');
                print '</th>';
                print '    <th scope="col">';
                print _e('Admin-defined title','google_news');
                print '</th>';
                print '    <th scope="col">';
                print _e('Region','google_news');
                print '</th>';
                print '    <th scope="col">';
                print _e('Type','google_news');
                print '</th>';
                print '    <th scope="col">';
                print _e('Output','google_news');
                print '</th>';
                print '    <th scope="col">';
                print _e('Item length','google_news');
                print '</th>';
                print '    <th scope="col">';
                print _e('Max items','google_news');
                print '</th>';
                print '    <th scope="col">';
                print _e('Optional query filter','google_news');
                print '</th>';
                print '    <th scope="col" colspan="3">';
                print _e('Action','google_news');
                print '</th>';
                print '   </tr>';
                print '  </thead>';

                if (get_bloginfo('version') >= '2.7') {
                    $manage_page = 'tools.php';
                } else {
                    $manage_page = 'edit.php';
                }

                if ( $alloptions['feeds'] || $newfeed ) {
                    $i = 0;

                    foreach ($alloptions['feeds'] as $key => $val) {
                        if ( $i % 2 == 0 ) {
                            print '<tr class="alternate">';
                        } else {
                            print '<tr>';
                        }
                        if ( isset($edit_id) && $edit_id == $key ) {
                            print "<form name=\"google_news_options\" action=\"".
                                  htmlspecialchars($_SERVER['REQUEST_URI']).
                                  "\" method=\"post\" id=\"google_news_options\">";
                                    
                            print "<th scope=\"row\">".$key."</th>";
                            print '<td><input size="10" maxlength="20" id="google_news-name" name="google_news-name" type="text" value="'.$val['name'].'" /></td>';
                            print '<td><input size="20" maxlength="20" id="google_news-title" name="google_news-title" type="text" value="'.$val['title'].'" /></td>';
                            print '<td><select name="google_news-region">';
                            $region = $val['region'];
                            foreach ($this->regions as $k => $v) {
                                print '<option '.(strcmp($v,$region)?'':'selected').' value="'.$v.'" >'.$k.'</option>';
                            }
                            print '</select></td>';
                            print '<td><select name="google_news-newstype">';
                            $newstype = $val['newstype'];
                            foreach ($this->newstypes as $k => $v) {
                                print '<option '.(strcmp($v,$newstype)?'':'selected').' value="'.$v.'" >'.$k.'</option>';
                            }
                            print '</select></td>';
                            print '<td><select name="google_news-outputtype">';
                            $outputtype = $val['outputtype'];
                            foreach ($this->outputtypes as $k => $v) {
                                print '<option '.(strcmp($v,$outputtype)?'':'selected').' value="'.$v.'" >'.$k.'</option>';
                            }
                            print '</select></td>';
                            print '<td><select name="google_news-desctype">';
                            $desctype = $val['desctype'];
                            foreach ($this->desctypes as $k => $v) {
                                print '<option '.(strcmp($v,$desctype)?'':'selected').' value="'.$v.'" >'.$k.'</option>';
                            }
                            print '</select></td>';
                            print '<td><input size="3" maxlength="3" id="google_news-numnews" name="google_news-numnews" type="text" value="'.$val['numnews'].'" /></td>';
                            print '<td><input size="10" maxlength="50" id="google_news-query" name="google_news-query" type="text" value="'.$val['query'].'" /></td>';
                            print '<td><input type="submit" value="Save  &raquo;">';
                            print "</td>";
                            print "<input type=\"hidden\" id=\"google_news-id\" name=\"google_news-id\" value=\"$edit_id\" />";
                            print "<input type=\"hidden\" id=\"google_news-submit\" name=\"google_news-submit\" value=\"1\" />";
                            print "</form>";
                        } else {
                            print "<th scope=\"row\">".$key."</th>";
                            print "<td>".$val['name']."</td>";
                            print "<td>".$val['title']."</td>";
                            print "<td>".$flipregions[$val['region']]."</td>";
                            print "<td>".$flipnewstypes[$val['newstype']]."</td>";
                            print "<td>".$flipoutputtypes[$val['outputtype']]."</td>";
                            print "<td>".$flipdesctypes[$val['desctype']]."</td>";
                            print "<td>".$val['numnews']."</td>";
                            print "<td>".$val['query']."</td>";
                            print "<td><a href=\"$manage_page?page=google-news/google_news.php&amp;mode=edit&amp;id=$key\" class=\"edit\">";
                            print __('Edit','google_news');
                            print "</a></td>\n";
                            print "<td><a href=\"$manage_page?page=google-news/google_news.php&amp;mode=delete&amp;id=$key\" class=\"delete\" onclick=\"javascript:check=confirm( '".__("This feed entry will be erased. Delete?",'google_news')."');if(check==false) return false;\">";
                            print __('Delete', 'google_news');
                            print "</a></td>\n";
                        }
                        print '</tr>';

                        $i++;
                    }
                    if ( $newfeed ) {

                        print "<form name=\"google_news_options\" action=\"".
                              htmlspecialchars($_SERVER['REQUEST_URI']).
                              "\" method=\"post\" id=\"google_news_options\">";
                                
                        print "<th scope=\"row\">".$newfeed."</th>";
                        print '<td><input size="10" maxlength="20" id="google_news-name" name="google_news-name" type="text" value="NEW" /></td>';
                        print '<td><input size="20" maxlength="20" id="google_news-title" name="google_news-title" type="text" value="" /></td>';
                        print '<td><select name="google_news-region">';
                        $region = 'us';
                        foreach ($this->regions as $k => $v) {
                            print '<option '.(strcmp($v,$region)?'':'selected').' value="'.$v.'" >'.$k.'</option>';
                        }
                        print '</select></td>';
                        print '<td><select name="google_news-newstype">';
                        foreach ($this->newstypes as $k => $v) {
                            print '<option value="'.$v.'" >'.$k.'</option>';
                        }
                        print '</select></td>';
                        print '<td><select name="google_news-outputtype">';
                        foreach ($this->outputtypes as $k => $v) {
                            print '<option value="'.$v.'" >'.$k.'</option>';
                        }
                        print '</select></td>';
                        print '<td><select name="google_news-desctype">';
                        foreach ($this->desctypes as $k => $v) {
                            print '<option value="'.$v.'" >'.$k.'</option>';
                        }
                        print '</select></td>';
                        print '<td><input size="3" maxlength="3" id="google_news-numnews" name="google_news-numnews" type="text" value="5" /></td>';
                        print '<td><input size="10" maxlength="50" id="google_news-query" name="google_news-query" type="text" value="" /></td>';
                        print '<td><input type="submit" value="Save  &raquo;">';
                        print "</td>";
                        print "<input type=\"hidden\" id=\"google_news-id\" name=\"google_news-id\" value=\"$newfeed\" />";
                        print "<input type=\"hidden\" id=\"google_news-newfeed\" name=\"google_news-newfeed\" value=\"1\" />";
                        print "<input type=\"hidden\" id=\"google_news-submit\" name=\"google_news-submit\" value=\"1\" />";
                        print "</form>";
                    } else {
                        print "</tr><tr><td colspan=\"12\"><a href=\"$manage_page?page=google-news/google_news.php&amp;mode=newfeed\" class=\"newfeed\">";
                        print __('Add extra feed','google_news');
                        print "</a></td></tr>";

                    }
                } else {
                    print '<tr><td colspan="12" align="center"><b>';
                    print __('No feeds found(!)','google_news');
                    print '</b></td></tr>';
                    print "</tr><tr><td colspan=\"12\"><a href=\"$manage_page?page=google-news/google_news.php&amp;mode=newfeed\" class=\"newfeed\">";
                    print __('Add feed','google_news');
                    print "</a></td></tr>";
                }
                print ' </table>';
                print '<h2>';
                print _e('Global configuration parameters','google_news');
                print '</h2>';
                print ' <form method="post">';
                print ' <table id="the-cachetime" cellspacing="3" cellpadding="3">';
                print '<tr><td><b>Cache time:</b></td>';
                print '<td><input size="6" maxlength="6" id="google_news-options-cachetime" name="google_news-options-cachetime" type="text" value="'.$alloptions['cachetime'].'" /> seconds</td>';
                print '<input type="hidden" id="google_news-options-cachetime-submit" name="google_news-options-cachetime-submit" value="1" />';
                print '<td><input type="submit" value="Save  &raquo;"></td></tr>';
                print ' </table>';
                print '</form>'; 

                print '<h2>';
                print _e('Information','google_news');
                print '</h2>';
                print ' <table id="the-list-x" width="100%" cellspacing="3" cellpadding="3">';
                print '<tr><td><b>Key</b></td><td>Unique identifier used internally.</td></tr>';
                print '<tr><td><b>Name</b></td><td>Optional name to be able to reference a specific feed as e.g. ';
                print ' <b>&lt;!--google_news#myname--&gt;</b>. ';
                print ' If more than one feed shares the same name, a random among these will be picked each time. ';
                print ' The one(s) without a name will be treated as the default feed(s), i.e. used for <b>&lt;!--google_news--&gt;</b> ';
                print ' or widget feed type <b>*DEFAULT*</b>. If you have Wordpress 2.5 ';
                print ' or above, you can also use Shortcodes on the form <b>[google-news]</b> ';
                print ' (for default feed) or <b>[google-news name="feedname"]</b>. And finally ';
                print ' you can use <b>do_action(\'google_news\');</b> or <b>do_action(\'google_news\', \'feedname\');</b> ';
                print ' in themes.</td></tr>';
                print '<tr><td><b>Admin-defined title</b></td><td>Optional feed title. If not set, a reasonable title based on ';
                print 'Region and Type will be used. Note Google Terms of Service require you to show that the feeds come from ';
                print 'Google News.</td></tr>';
                print '<tr><td><b>Region</b></td><td>The region/language of the feed.</td></tr>';
                print '<tr><td><b>Type</b></td><td>The type of news to present.</td></tr>';
                print '<tr><td><b>Output</b></td><td>Text only, allow for images or images with most news items. Note that ';
                print 'there will be text in all three cases.</td></tr>';
                print '<tr><td><b>Item length</b></td><td>Single sentence news items or 2-3 lines of text.</td></tr>';
                print '<tr><td><b>Max items</b></td><td>Maximum number of news items to show for this feed. If the feed contains ';
                print 'less than the requested items, only the number of items in the feed will obviously be displayed.</td></tr>';
                print '<tr><td><b>Optional query filter</b></td><td>Pass the requested news through a query filter for very ';
                print 'detailed control over the type of news to show. E.g. only sports news about the Yankees.</td></tr>';
                print '<tr><td colspan="12">In all cases, output will depend on original news source and can and will ';
                print 'differ from source to source. Google hasn\'t really done a great job with respect to formatting. ';
                print 'Note specifically that a query filter will change the output slightly, as this is how Google wants it.</td></tr>';
                print '<tr><td><b>Cache time</b></td><td>Minimum number of seconds that WordPress should cache a Google News feed before fetching it again.</td></tr>';
                print ' </table>';
                print '</div>';
            }
        }

        // ************* Output *****************

        // The function that gets called from themes
        function display_feed($data) {
	    global $settings;
	    $settings = get_option('google_news');
            print $this->random_feed($data);
            unset($settings);
        }

        // Callback for inline replacement
        function insert_news($data) {
            global $settings;

            // Allow for multi-feed sites
            $tag = '/<!--google-news(|#.*?)-->/';

            // We may have old style options
            $this->upgrade_options();

            // Avoid getting this for each callback
            $settings   = get_option('google_news');

            $result = preg_replace_callback($tag, 
                              array(&$this, 'inline_replace_callback'), $data);

            unset($settings);

            return $result;
        }


        // *********** Widget support **************
        function widget_init() {

            // Check for the required plugin functions. This will prevent fatal
            // errors occurring when you deactivate the dynamic-sidebar plugin.
            if ( !function_exists('register_sidebar_widget') )
                return;

            register_widget_control('Google News', 
                                   array(&$this, 'widget_control'), 200, 100);

            // wp_* has more features, presumably fixed at a later date
            register_sidebar_widget('Google News',
                                   array(&$this, 'widget_output'));

        }

        function widget_control() {

            // We may have old style options
            $this->upgrade_options();

            $alloptions = get_option('google_news');
            $thisfeed = $alloptions['widget-1'];

            print '<p><label for="google_news-feed">Select feed:</label>';
            print '<select style="vertical-align:middle;" name="google_news-widget-feed">';

            $allfeeds = array();
            foreach ($alloptions['feeds'] as $k => $v) {
                $allfeeds[strlen($v['name'])?$v['name']:'*DEFAULT*'] = 1;
            } 
            foreach ($allfeeds as $k => $v) {
                print '<option '.($k==$thisfeed?'':'selected').' value="'.$k.'" >'.$k.'</option>';
            }
            print '</select><p>';
            print '<input type="hidden" id="google_news-widget-submit" name="google_news-widget-submit" value="1" />';


        }

        // Called every time we want to display ourselves as a sidebar widget
        function widget_output($args) {
            extract($args); // Gives us $before_ and $after_ I presume
                        
            // We may have old style options
            $this->upgrade_options();

            $alloptions = get_option('google_news');
            $matching_feeds = array();
            foreach ($alloptions['feeds'] as $k => $v) {
                if ( (string)$v['name'] == $alloptions['widget-1'] ) { 
                    $matching_feeds[] = $k;
                } 
            }
            if ( ! count($matching_feeds) ) {
                if ( ! strlen($alloptions['widget-1']) ) {
                    $content = '<ul><b>No default feed available</b></ul>';
                } else {
                    $content = "<ul>Unknown feed name <b>{$alloptions[widget-1]}</b> used</ul>";
                }
                echo $before_widget;
                echo $before_title . __('Google News<br>Error','google_news') . $after_title . '<div>';
                echo $content;
                echo '</div>' . $after_widget;
                return;
            }
            $feed_id = $matching_feeds[rand(0, count($matching_feeds)-1)];
            $options = $alloptions['feeds'][$feed_id];

            $feedtype   = $options['feedtype'];
            $cachetime  = $alloptions['cachetime'];

            if ( strlen($options['title']) ) {
                $title = $options['title'];
            } else {
                $title = 'Google News<br>'.$feedtype;
            }

            echo $before_widget;
            echo $before_title . $title . $after_title . '<div>';
            echo $this->get_feed($options, $cachetime);
            echo '</div>' . $after_widget;
        }

        // ************** The actual work ****************
        function get_feed(&$options, $cachetime) {

            if ( ! isset($options['region']) ) {
                return 'Options not set, visit plugin configuation screen.'; 
            }

            $region     = $options['region'] ? $options['region'] : 'us';
            $newstype   = $options['newstype'];
            $outputtype = $options['outputtype'];
            $query      = $options['query'];
            $numnews    = $options['numnews'] ? $options['numnews'] : 5;
            $desctype   = $options['desctype'];

            $result = '<ul>';
            $feedurl = 'http://news.google.com/news?output=rss';

            // This will also handle mixed mode text/image, when
            // we get the parsing under control...
            if ( $outputtype == 't' ) { 
                $region = 't'.$region;  // Consistent API, wassat?
            } else if ( strlen($outputtype) ) {
                $feedurl .= $outputtype;
            }
            $feedurl .= "&ned=$region"; 
            if ( strlen($newstype) ) {
                $feedurl .= "&topic=$newstype";
            }
            if ( strlen($query) ) {
                if ( substr($query,0,3) == 'OR ' ) {
                    $squery = urlencode(strtolower(substr($query,3)));
                    $feedurl .= "&as_oq=$squery";
                } else {
                    $squery = urlencode(strtolower($query));
                    $feedurl .= "&q=$squery";
                }
            }

            // Using the WP RSS fetcher (MagpieRSS). It has serious
            // GC problems though.
            define('MAGPIE_CACHE_AGE', $cachetime);
            define('MAGPIE_CACHE_ON', 1);
            define('MAGPIE_DEBUG', 1);

            $rss = fetch_rss($feedurl);

            if ( ! is_object($rss) ) {
                return 'Google News unavailable</ul>';
            }
            $rss->items = array_slice($rss->items, 0, $numnews);
            foreach ( $rss->items as $item ) {
                $description = $this->html_decode($item['description']);

                // All this is bound to break, but Google 
                // doesn't know usable markup from squat
    
                // As per Google TOC, we need to retain related link
                preg_match('|(<a class=p [^>]+><nobr>[^<]+</nobr></a>)|', 
                           $description, $related);
        
                // Try some tricks to lose useless markup
                $bloc = strpos($description, '<font');
                if ( $bloc ) {
                    $description = substr($description, $bloc);
                }
                $eloc = strpos($description, '<a href=',
                                        strpos($description, '<a href=')+1);
                if ( $eloc ) {
                    $description = substr($description,0,$eloc);
                }
        
                // No markup in tooltips
                $tooltip = preg_replace('/<[^>]+>/','',$description);
                $patterns = array(
                            '/<(td|tr|table|div|font|ul|li)[^>]*>/',
                            '/<.(td|tr|table|div|font|ul|li)[^>]*>/',
                            );
                $replacements = array(
                            '',
                            '',
                            );
                $description = preg_replace($patterns, $replacements, 
                                            $description);
                $description = preg_replace('|<br>|', '', $description, 1);
                $description = preg_replace('|(<img src[^>]+>)<br>([^<]+</a>)|',
                                            '\\1\\2<br>', $description, 1);
                $description = preg_replace('|</div><br><div|', '</div><div', 
                                            $description);
                $description .= $related[1];

                $title = $this->html_decode($item['title']);
                $date = $item['pubdate'];
                $link = $item['link'];
                if ( strlen($desctype) ) {
                    $result .= "<li>$description</li>";
                } else {
                        $result .= "<li><a href=\"$link\" target=\"_blank\" ".
                                   "title=\"$tooltip\">$title<br>$related[1]</a></li>";
                }
            } 
            return $result.'</ul>';
        }

        // *********** Shortcode support **************
        function my_shortcode_handler($atts, $content=null) {
            global $settings;
            $settings = get_option('google_news');
            return $this->random_feed($atts['name']);
            unset($settings);
        }

        
        // *********** inline replacement callback support **************
        function inline_replace_callback($matches) {

            if ( ! strlen($matches[1]) ) { // Default
                $feedname = '';
            } else {
                $feedname = substr($matches[1], 1); // Skip #
            }
            return $this->random_feed($feedname);
        }

        // ************** Support functions ****************

        function random_feed($name) {
            global $settings;

            $matching_feeds = array();
            foreach ($settings['feeds'] as $k => $v) {
                if ( (string)$v['name'] == $name ) { 
                    $matching_feeds[] = $k;
                } 
            }
            if ( ! count($matching_feeds) ) {
                if ( ! strlen($name) ) {
                    return '<ul><b>No default feed available</b></ul>';
                } else {
                    return "<ul>Unknown feed name <b>$name</b> used</ul>";
                }
            }
            $feed_id = $matching_feeds[rand(0, count($matching_feeds)-1)];
            $feed = $settings['feeds'][$feed_id];

            if ( strlen($feed['title']) ) {
                $title = $feed['title'];
            } else {
                $title = 'Google News : '.$feed['feedtype'];
            }

            $result = '<!-- Start Google News code -->';
            $result .= "<div id=\"google-news-inline\"><h3>$title</h3>";
            $result .= $this->get_feed($feed, $settings['cachetime']);
            $result .= '</div><!-- End Google News code -->';
            return $result;
        }

        function html_decode($in) {
            $patterns = array(
                '/&amp;/',
                '/&quot;/',
                '/&lt;/',
                '/&gt;/',
            );
            $replacements = array(
                '&',
                '"',
                '<',
                '>',
            );
            $tmp = preg_replace($patterns, $replacements, $in);
            return preg_replace('/&#39;/','\'',$tmp);

        }

        // Unfortunately, we didn't finalize on a data structure
        // until version 2.1ish of the plugin so we need to upgrade
        // if needed
        function upgrade_options() {
            $options = get_option('google_news');

            if ( !is_array($options) ) {

                // From 1.0
                $oldoptions = get_option('widget_google_news_widget');
                if ( is_array($oldoptions) ) {
                    $flipregions     = array_flip($this->regions);
                    $flipnewstypes   = array_flip($this->newstypes);

                    $tmpfeed = array();
                    $tmpfeed['title']      = $oldoptions['title'];
                    $tmpfeed['name']       = '';
                    $tmpfeed['numnews']    = $oldoptions['numnews'];
                    $tmpfeed['region']     = $oldoptions['region'];
                    $tmpfeed['newstype']   = $oldoptions['newstype'];
                    $tmpfeed['outputtype'] = $oldoptions['outputtype'];
                    $tmpfeed['query']      = $oldoptions['query'];
                    $tmpfeed['feedtype']   = $flipregions[$tmpfeed['region']].
                                             ' : '.
                                             $flipnewstypes[$tmpfeed['newstype']];

                    $options = array();
                    $options['feeds']     = array( $tmpfeed );
                    $options['widget-1']  = 0;
                    $options['cachetime'] = 300;
                    
                    delete_option('widget_google_news_widget');
                    update_option('google_news', $options);
                } else {
                    // First time ever
                    $options = array();
                    $options['feeds']     = array( $this->default_feed() );
                    $options['widget-1']  = 0;
                    $options['cachetime'] = 300;
                    update_option('google_news', $options);
                }
            } else {
                // From 2.0/2.0.1 to 2.1
                if ( array_key_exists('region', $options) ) {
                    $newoptions = array('feeds' => array( $options));
                    $newoptions['feeds'][0]['name'] = '';
                    $newoptions['widget-1']         = 0;
                    $newoptions['cachetime']        = 300;
                    update_option('google_news', $newoptions);

                } else if ( 0 ) {
                    // Messed up options, start from scratch
                    $options = array();
                    $options['feeds']     = array( $this->default_feed() );
                    $options['widget-1']  = 0;
                    $options['cachetime'] = 300;
                    update_option('google_news', $options);
                }
            }
        }

        function default_feed() {
            return array( 'numnews' => 5,
                          'region' => 'us',
                          'name' => '',
                          'feedtype' => 'U.S. : All');
        }
    }

    // Instantiate
    $google_news_instance &= new google_news_plugin();

}
?>
