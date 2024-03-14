<?php

/**
 * This file contains all functions for displaying the publication sources page in admin menu
 * 
 * @package teachpress\admin\publications
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 */

include_once(__DIR__ . '/../core/constants.php');

/**
 * Add help tab for sources page
 */
function tp_import_publication_sources_help() {
    $screen = get_current_screen();  
    $screen->add_help_tab( array(
        'id'        => 'tp_import_publication_sources_help',
        'title'     => __('Sources'),
        'content'   => '<p><strong>' . __('Publication sources') . '</strong></p>
                        <p>' . __("Additional publication sources to scan regularly.",'teachpress') . '</p>',
     ) );
}

/**
 * Auxiliary function to get source url from dict.
 */
function tp_get_source_url($source) {
    return trim($source['src_url']);
}
    
/**
 * The controller for the import page of teachPress
 * @since 9.0.0
*/ 
function tp_show_publication_sources_page() {
    if ( isset($_POST['tp_sources_save']) ) {
        TP_Publication_Sources_Page::sources_actions($_POST);
    }
    
    TP_Publication_Sources_Page::sources_tab();      
}
        
/**
 * This function is the REST call implementation for updating sources.
 * @since 9.0.0
 */
function tp_rest_update_sources() {
    sleep(2);  // insert small delay in case of repeated calls
    return new WP_REST_Response(TP_Publication_Sources_Page::update_sources());
}

/**
 * This class contains functions for generating the publication sources page.
 * @since 9.0.0
 */
class TP_Publication_Sources_Page {
    /**
     * Returns current sources.
     */
    public static function get_current_sources() {
        global $wpdb;
        $source_urls = $wpdb->get_results("SELECT * FROM " . TEACHPRESS_MONITORED_SOURCES);
        $result = array();
        
        foreach ($source_urls as $src_url) {
            $result[] = array("src_url" => $src_url->name,
                              "last_res" => $src_url->last_res,
                              "update_time" => $src_url->update_time);
        }
        
        return $result;
    }
    
    /**
     * Returns the table rows for sources rendering
     */
    public static function get_pages_rows($current_pages) {
        $result = "";
        
        $alternate = true;
        
        foreach ($current_pages as $src_url) {
            $last_res = $src_url['last_res'];
            if (strlen($last_res) == 0) {
                $last_res = __("URL not scanned yet.", "teachpress");
            }
            $result .= sprintf("<tr class='%s'><td class='tp_url'>%s</td><td>%s</td><td>%s</td></tr>",
                               $alternate ? "alternate" : "", $src_url['src_url'],
                               __($last_res, "teachpress"), $src_url['update_time']);
            $alternate = ! $alternate;
        }
        
        return $result;
    }
    
    /**
     * Shows the sources
     * @since 9.0.0
     * @access public
    */
    public static function sources_tab () {
        ?>

        <div class="wrap">
            <h2><?php echo __('Auto-publish','teachpress'); ?></h2>
            <div class="teachpress_message teachpress_message_blue">
            <?php echo __("The following URLs can be scanned regularly and their bibtex entries
               automatically imported if they have changed. The publication log can
               be consulted on the Import/Export page.", "teachpress");?><br/><br/>
            <?php echo __("Zotero group bibliographies can be downloaded in BibTeX format by using special URLs such as <code>zotero://group/&lt;group_id&gt;/</code>,
                where <code>group_id</code> is the group id (numerical) found on zotero.org.", "teachpress");?>
            </div>
            <form id="tp_sources" name="tp_sources"
                  action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data" method="post">
                <p>
                    <label for="tp_source_freq"><? echo __("Update frequency:", "teachpress");?></label>

                    <select name="tp_source_freq" id="tp_source_freq" onchange="tp_source_freq_changed()" >
                        <?php
                            $cur_freq = TP_Publication_Sources_Page::get_update_freq();
                            $all_freqs = array("never" => __("Never (disable updates)", "teachpress"),
                                               "hourly" => __("Hourly", "teachpress"),
                                               "twicedaily" => __("Twice a day", "teachpress"),
                                               "daily" => __("Daily (recommended)", "teachpress"));
                            foreach ($all_freqs as $val => $render) {
                                print(sprintf("<option value='%s' %s>%s</option>", $val, $val == $cur_freq ? "selected='selected'" : "", $render));
                            }
                        ?>
                    </select>
                </p>
                
                <p id="tp_sources_holder">
                    <table id="tp_sources_table" class="widefat" cellspacing="0" cellpadding="0" border="0">
                        <thead>
                            <tr>
                                <td>URL</td>
                                <td><?php echo __("Previous update result", "teachpress");?></td>
                                <td><?php echo __("Date", "teachpress") . " (UTC)";?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $cur_sources = TP_Publication_Sources_Page::get_current_sources();
                                   print(TP_Publication_Sources_Page::get_pages_rows($cur_sources)); ?>
                        <tbody>
                    </table>
                    <label style="display:none;" id="tp_sources_area_lbl" for="tp_sources_area">
                        <?php echo __("One URL per line. Start each URL with http://, https:// or zotero://.", "teachpress"); ?></label>
                <textarea id="tp_sources_area" name="tp_sources_area" style="width: 100%; display: none;"><?php
                              $cur_sources = TP_Publication_Sources_Page::get_current_sources();
                              print(implode("\n", array_map('tp_get_source_url', $cur_sources)));
                    ?></textarea>
                </p>

                <p><button class="button-secondary" name="tp_edit_sources" id="tp_edit_sources"
                           type="button" onclick="teachpress_edit_sources()">
                                <?php echo __("Edit URL list", "teachpress");?></button>
                    <button class="button-secondary" name="tp_sources_cancel" id="tp_sources_cancel"
                    type="button" onclick="teachpress_edit_sources()" style="display: none;">
                        <?php echo __("Cancel", "teachpress");?></button></p>

                <p style="margin-top: 60px;"><button class="button-primary disabled"
                   name="tp_sources_save" id="tp_sources_save" type="submit" >
                    <?php echo __("Save configuration", "teachpress");?></button></p>
                <?php wp_nonce_field( 'verify_teachpress_auto_publish', 'tp_nonce', false, true ); ?>
            </form>
        </div>

        <?php
    }
    
    /**
     * This function executes all source publication action calls
     * @global object $current_user
     * @param array $post                   The $_POST array
     * @since 9.0.0
     * @access public
     */
    public static function sources_actions ($post) {
        // Check nonce field
        TP_Publication_Sources_Page::check_nonce_field();
        
        $sources_area = isset($post['tp_sources_area']) ? trim($post['tp_sources_area']) : '';
        $sources_to_monitor = array_filter(preg_split("/\r\n|\n|\r/", $sources_area),
                                           function($k) { return strlen(trim($k)) > 0; });
        $sources_to_monitor = array_map(function ($k) { return trim($k); }, $sources_to_monitor);
        $new_freq = isset($post['tp_source_freq']) ? trim($post['tp_source_freq']) : 'hourly';
        
        $installed = TP_Publication_Sources_Page::install_sources($sources_to_monitor);
                
        // manage cron hook
        if (count($sources_to_monitor) == 0 || $new_freq == 'never') {
            TP_Publication_Sources_Page::uninstall_cron(); // not needed anymore
        } else {
            TP_Publication_Sources_Page::install_cron($new_freq); // no problem if cron already installed
        }
        
        $new_freq = TP_Publication_Sources_Page::get_update_freq();
        get_tp_message( sprintf(__('Configuration updated with %d URL(s) at frequency "%s".', "teachpress"),
                                 count($sources_to_monitor), $new_freq) );
    }

    /**
     * Finds the current frequency of schedule.
     * @return Current frequency, or 'never' if none scheduled.
     * @since 9.0.0
     * @access public
     */
    public static function get_update_freq() {
        $result = wp_get_schedule(TEACHPRESS_CRON_SOURCES_HOOK);
        if ($result === false) {
            $result = 'never';
        }
        return $result;
    }
            
    /**
     * This function installs monitored bibtex sources. Sources present in the db but not
     * in the sources specified as a parameter are removed from the db.
     * @global object $wpdb
     * @param array $sources    An array of source URL strings.
     * @return Only the newly added URLs to monitor - can be the empty array.
     * @since 9.0.0
     * @access public
     */
    public static function install_sources($sources) {
        // find current sources already installed so as not to install them uselessly
        $cur_sources = TP_Publication_Sources_Page::get_current_sources();
        $cur_source_names = array();
        foreach ($cur_sources as $cur_src) {
            $cur_source_names[] = $cur_src['src_url'];
        }
        
        // start installing sources not present in database
        global $wpdb;
        $toremove = array();
        
        foreach ( $cur_source_names as $existing_source ) {
            if (!in_array($existing_source, $sources)) {
                $toremove[] = $existing_source;
            }
        }
        
        // create the filter set for the delete instruction
        $filter_set = "''"; // empty set
        if (count($toremove) > 0) {
            $filter_set = implode(",", array_map(function ($k) { return "'" . esc_sql($k) . "'"; }, $toremove));
        }

        // remove useless entries
        $wpdb->query( "DELETE FROM " . TEACHPRESS_MONITORED_SOURCES . " WHERE name IN ( " . $filter_set . " )");
        
        // write new entries -- could be done in a single statement
        $result = array();
        foreach( $sources as $element ) {
            if (! in_array($element, $cur_source_names) ) {
                $wpdb->insert(TEACHPRESS_MONITORED_SOURCES, array('name' => $element, 'md5' => 0),
                              array('%s', '%d'));
                $result[] = $element;
            }
        }
        
        return $result;
    }
            
    /**
     * This function installs the cron hook.
     * @param string $freq    Frequency of cron.
     * @since 9.0.0
     * @access public
     */
    public static function install_cron($freq) {
        // install action if not alreay installed
        if ( ! has_action( TEACHPRESS_CRON_SOURCES_HOOK, 'TP_Publication_Sources_Page::tp_cron_exec' ) ) {
            add_action( TEACHPRESS_CRON_SOURCES_HOOK, 'TP_Publication_Sources_Page::tp_cron_exec' );
        }
        
        // schedule hook if freq has changed and freq is not never
        if ( TP_Publication_Sources_Page::get_update_freq() != $freq && $freq != 'never' ) {
            TP_Publication_Sources_Page::uninstall_cron();
            wp_schedule_event( time(), $freq, TEACHPRESS_CRON_SOURCES_HOOK );
        }
    }

    /**
     * This function uninstalls the cron hook.
     * @since 9.0.0
     * @access public
     */
    public static function uninstall_cron() {
        $timestamp = wp_next_scheduled( TEACHPRESS_CRON_SOURCES_HOOK );
        wp_unschedule_event( $timestamp, TEACHPRESS_CRON_SOURCES_HOOK );
    }
            
    /**
     * Execute the scheduled sources update.
     * @since 9.0.0
     * @access public
     */
    public static function tp_cron_exec() {
        TP_Publication_Sources_Page::update_sources();
    }
        
    /**
     * Performs update for all sources registered in the db.
     * @since 9.0.0
     */
    public static function update_sources() {
        $result = array();
        
        // list all sources
        global $wpdb;
        $source_urls = $wpdb->get_results("SELECT * FROM " . TEACHPRESS_MONITORED_SOURCES);
        
        foreach ($source_urls as $src_url) {
            $result[] = array_merge(TP_Publication_Sources_Page::update_source($src_url->name, $src_url->md5),
                                    array('src_id' => $src_url->src_id, 'src_name' => $src_url->name));
        }
        
        foreach ($result as $cur_res) {
            $r = $wpdb->update(TEACHPRESS_MONITORED_SOURCES,
                array('md5' => $cur_res[0], 'last_res' => $cur_res[2], 'update_time' => current_time('mysql', 1)),
                array('src_id' => $cur_res['src_id']));
        }
        
        return $result;
    }

    /**
     * Performs update for a single source.
     * @param $url   The URL of the source. URL protocols supported: http://, https://.
     * @param previous_sig   Digest the last time the file was polled, 0 if this is the first time.
     * @param this_req   Http Request (callee assigned), by reference.
     * @return new_signature, nb_updates, status_message, success
     * @since 9.0.0
     */
    public static function update_source_http($url, $previous_sig, &$this_req) {
        $new_signature = '';
        $nb_updates = 0;
        $status_message = 'Unknown error.';
        $success = false;
        
        $req = wp_remote_get($url, array('sslverify' => false));
        $this_req = $req;
        if (is_wp_error($req)) {
            $status_message = 'Error while retrieving URL.';
        } else {
            $code = $req["response"]["code"];
            if (!preg_match("#^2\d+$#", $code)) {
                $status_message = sprintf('Error code %s while connecting to URL %s.', $code, $url);
            } else {
                $body = wp_remote_retrieve_body($req);
                if ($body) {
                    $new_signature = md5($body);
                    if ($new_signature != $previous_sig) {
                        if ( TP_Bibtex::is_utf8($body) === false ) {
                            $body = utf8_encode($body);
                        }
                        
                        if ( !TP_Bibtex::looks_like_bibtex($body) ) {
                            $status_message = "Content does not look like BibTeX.";
                        } else {
                            $settings = array(
                                'keyword_separator' => ',',
                                'author_format'     => 'dynamic',
                                'overwrite'         => true,
                                'ignore_tags'       => false,
                            );

                            $entries = TP_Bibtex_Import::init($body, $settings);
                            $status_message = 'Successfully read and imported.';
                            $nb_updates = count($entries);
                            $success = true;
                        }
                    } else {
                        $status_message = 'File unchanged.';
                        $new_signature = $previous_sig;
                        $success = true;
                    }
                } else {
                    $status_message = 'Invalid body in server response.';
                }
            }
        }
        
        return array($new_signature, $nb_updates, $status_message, $success);
    }

    /**
     * Performs update for a single source.
     * @param $url   The URL of the source. URL protocols supported:
                     zotero://group/<group_id> is special and downloads all group items in group <group_id>
     * @param previous_sig   String signature of the last file polled, 0 if this is the first time.
     * @return new_signature, nb_updates, status_message, success
     * @since 9.0.0
     * @see Zotero api https://www.zotero.org/support/dev/web_api/v3/basics
     */
    public static function update_source_zotero($url, $previous_sig) {
        $result = array('', 0, 'Zotero group download failed.', false);
        
        // be robust
        if (is_int($previous_sig)) {
            $previous_sig = strval($previous_sig);
        }
        
        // find group id
        $parts = explode("/", $url);
        
        if (count($parts) >= 3 && $parts[0] == "zotero:" && $parts[2] == "group") {
            $group_id = $parts[3];
            
            // has the group changed since the last poll?
            $previous_version = 0;
            if (is_numeric($previous_sig)) {
                $previous_version = (int) $previous_sig;
            }

            $current_version = 0;
            $req = wp_remote_get('https://api.zotero.org/groups/' . $group_id . '/items?since=0&limit=1', array('sslverify' => false));
            $headers = wp_remote_retrieve_headers($req);
            if (isset($headers['Last-Modified-Version'])) {
                $current_version = (int) $headers['Last-Modified-Version'];
            }
            
            $group_has_changed = $current_version > $previous_version || $current_version == 0;

            // main loop
            if (!$group_has_changed) {
                $result = array(strval($current_version), 0, 'Publications already synchronized with Zotero.', true);
            } else {
                // prepare pagination loop
                $has_more_results = true;
                $error_encountered = false;
                $current_offset = 0;
                $page_size = 30;
                $total_results = -1;
                
                while ($has_more_results && !$error_encountered) {
                    // download a single page
                    $page_url = sprintf("https://api.zotero.org/groups/%s/items?since=%d&format=bibtex&limit=%d&start=%d",
                                        $group_id, $previous_version, $page_size, $current_offset);
                    $page_result = TP_Publication_Sources_Page::update_source_http($page_url, '', $req);
                    if ($total_results == -1) {  // set on first loop
                        $total_results = intval($req["headers"]["total-results"]);
                    }
                    
                    $result[3] = $page_result[3];
                    $error_encountered = !$result[3];
                    
                    if ($error_encountered) {
                        $result[2] = 'Zotero group download failed. Error was: ' . $page_result[2];
                        $result[0] = $previous_version;
                    } else {
                        $result[1] += $page_result[1];
                        $result[2] = $page_result[2];

                        usleep(100000); // stay awhile and listen
                        $current_offset += $page_size;
                        $has_more_results = $current_offset < $total_results;
                    }
                }
                
                if (!$error_encountered) {
                    $result[0] = strval($current_version);
                }
            }
        } else {
            $result = array('', 0, 'Zotero URL format is incorrect.', false);
        }
        
        return $result;
    }
            
    /**
     * Performs update for a single source.
     * @param $url   The URL of the source. URL protocols supported: http://, https://, zotero://
                     zotero://group/<group_id> is special and downloads all group items in group <group_id>
     * @param previous_sig   Digest the last time the file was polled, 0 if this is the first time.
     * @return new_signature, nb_updates, status_message, success
     * @since 9.0.0
     */
    public static function update_source($url, $previous_sig) {
        // what is the protocol?
        $url_parts = explode("://", strtolower(trim($url)));
        $result = false;
        if (count($url_parts) > 1) {
            switch ($url_parts[0]) {
                case "http":
                case "https":
                    $http_req = NULL;
                    $result = TP_Publication_Sources_Page::update_source_http($url, $previous_sig, $http_req);
                    break;
                case "zotero":
                    $result = TP_Publication_Sources_Page::update_source_zotero($url, $previous_sig);
                    break;
                default:
                    $result = array('', 0, 'Invalid protocol.', false);
                    break;
            }
        }
        
        return $result;
    }
    
    /**
     * Checks the nonce field of the form. If the check fails wp_die() will be executed
     * @since 9.0.5
     */
    private static function check_nonce_field () {
        if ( ! isset( $_POST['tp_nonce'] ) 
            || ! wp_verify_nonce( $_POST['tp_nonce'], 'verify_teachpress_auto_publish' ) 
        ) {
           wp_die('teachPress error: This request could not be verified!');
           exit;
        }
    }

}
