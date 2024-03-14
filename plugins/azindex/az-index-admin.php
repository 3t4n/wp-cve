<?php
/*
    Plugin Name: AZIndex
    Plugin URI: http://azindex.englishmike.net/
    Description: A highly customizable and user friendly plugin to create one or more alphabetical indexes of selected posts in your Wordpress blog.
    Version: 0.8.1
    Author: English Mike
    Author URI: http://englishmike.net
*/

/*  Copyright 2008  Michael J. Walker  (email : azindex@englishmike.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// KNOWN BUGS AND ISSUES
//
// FIXME : Paging back after creating an index will add another index.
// FIXME : Need a better way to set the custom CSS to default.
// FIXME : Disable the unselected custom CSS (single/groups). Blocked by Firefox bug 441930.
// TODO : Finish adding internationalization to the plugin.
// TODO : Finish adding nonce support.
// TODO : Remove all debug functions.
// TODO : More support for sorting national languages - use build it sort algorithms
// TODO : Revisit the CSS styling before 1.0 release
// TODO : Add filters for sorted head, subhead, and description, and links.  
// TODO : Rename heading filter.
// TODO : Index settings templates.
// TODO : Ability to sort indexes alphabetically by name.

require_once('az-index-content.php');
require_once('az-index-cache.php');

global $wpdb;

define('AZ_DEBUG', false);
define('AZ_PLUGIN_VERSION', '0.8.1');
define('AZ_NLS_OPTIONS', 17);
define('AZ_ADVANCED_OPTIONS', 20);
define('AZ_PLUGIN_FILE', 'azindex/az-index-admin.php');
define('AZ_TABLE', $wpdb->prefix.'az_indexes');
define('AZ_PAGENAME', ($wp_version >= 2.7 ? 'tools.php' : 'edit.php').'?page=az-index-manager');
define('AZ_INDEXCHARS', '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
define('AZ_MAXPAGELINKS', 10);  // Note: Must be an even number.
define('AZ_OS_WIN', substr(PHP_OS, 0, 3) == 'WIN');
define('AZ_DEFAULT_LANGUAGE_TABLE', 'General European');

register_activation_hook(__FILE__, 'az_plugin_activate');
register_deactivation_hook(__FILE__, 'az_plugin_deactivate');

add_action('admin_menu', 'az_add_admin_page');
add_shortcode('az-index', 'az_insert_index');

// Only add this action if we're on the index management page.
// Avoids function getting called unnecessarily.
if ($_GET['page'] == 'az-index-manager') {
    add_action('init', 'az_process_requests');
}

/**
 * Add the manage indexes admin page to the admin menu, and if there
 * was a problem installing this version of AZIndex, then hook into
 * the admin_notices action to display the error message.
 */
function az_add_admin_page() {
    add_management_page('Manage Indexes', 'AZIndex', 8, 'az-index-manager', 'az_display_admin_page');
    $error = get_option('az_plugin_error');
    if (!empty($error)) {
        add_action('admin_notices', 'az_admin_notices');
    }
}

/**
 * Set up all the available options as keys to an array with the values being
 * the description of what each option does. 
 *
 * @param $keys if true, create an array of the keys (option names) only
 * @return array of options
 */
function az_get_options($keys = false) {
    $options = array('child-cats' => 'Include child categories',
                     'include-pages' => 'Include blog pages',
                     'include-pages-exclude-posts' => 'Exclude blog posts (i.e. <em>only</em> include blog pages)',
                     'multipage' => 'Use multiple pages',
                     'multipage-links-above' => 'Display page links above the index (default)',
                     'multipage-links-below' => 'Display page links below the index',
                     'group-subhead' => 'Group items with the same heading under one main entry (subheadings must be selected)',
                     'alpha-head' => 'Display alphabetical headings',
                     'alpha-head-page' => 'Display an alphabetical heading at the start of every page',
                     'alpha-head-col' => 'Display an alphabetical heading at the start of every column',
                     'add-spaces' => 'Insert a gap between entries beginning with a different character',
                     'alpha-links' => 'Display alphabetical links above the index',
                     'alpha-links-unused' => 'Include unused characters in the alphabetical links',
                     'alpha-links-two-rows' => 'Use two rows for the alphabetical links',
                     'ignore-chars' => 'Ignore specified characters, like quotes, when sorting the index',
                     'non-alpha-end' => 'Put all index items starting with non-alphanumeric characters at the end of the index',
                     'nls' => 'Turn on additional support for national languages',
                     'nls-equiv' => 'Set collation table to use for grouping index items',
                     'nls-locale' => 'Set locale to be used while sorting index',
                     'custom-css' => 'Use customized stylesheets for the index',
                     'custom-css-striping' => 'Enable CSS striping of index entries (every other index entry is tagged with the "azalt" CSS class)',
                     'custom-links' => 'Customize the alphabetical links to be displayed',
                     'custom-sort' => 'Use a customized comparison function for sorting the index',
                     'disable-cache' => 'Disable caching for the index. (Use only as last resort. Does not disable caching plugins like WP-Super-Cache.)'
    );
    if ($keys) {
        $options = array_keys($options);
    }
    return $options;
}

/**
 * Called when the plugin is activated. Create an empty indexes table in the WordPress 
 * database, if one does not already exist.  If we are upgrading then we create a 
 * wp_az_indexes_upgrade table first and then, if the table is successfully upgraded then
 * we rename it to wp_az_indexes.  This prevents data loss if something goes badly wrong
 * during the upgrade.
 */
function az_plugin_activate() {
    az_trace("fn:az_plugin_activate : start");
    
    include_once(ABSPATH.'/wp-admin/includes/upgrade.php');
    global $wpdb;

    // Check to see if we need to upgrade the database table.
    if (az_is_table_present() && !az_is_compatible_version() && !az_is_alpha_version()) {
        az_trace("upgrading...");
        $upgrade = '_upgrade';    // Suffix of new table during the upgrade
        $old = '_old';            // Suffix of old table during the upgrade
    	$indexes = $wpdb->get_results("SELECT * FROM ".AZ_TABLE);
    }

    // Create the AZIndex database table.
    $options = az_get_options(true);
    $sql = "CREATE TABLE ".AZ_TABLE.$upgrade." ( name text NOT NULL, idindex int NOT NULL, categories text,"
                               ." tags text, heading text, subheading text, description text,"
                               ." cols tinyint, headingseparator text, itemsperpage smallint," 
                               ." ignorechars text, nlslocale text, nlsequiv text," 
                               ." customcsssingle text, customcssgroup text,"
                               ." customlinks text, customsort text," 
                               ." options set(";
    for ($i = 0; $i < count($options); $i++) {
        $sql .= ($i > 0 ? ", " : "")."'".$options[$i]."'";                                       
    }   
    // Always put the cache columns at the end of the table.
    $sql .= "), itemcache mediumblob, linkcache blob, PRIMARY KEY(idindex))";
    
    // If the database supports the default character set then add it to
    // the creation query (Note: might be giving a false positive in some cases).
    if ($wpdb->supports_collation()) {
        $charset = " DEFAULT CHARACTER SET $wpdb->charset;";
    }

    // Try creating with the default charset clause, but if that fails try again without it 
    $created = maybe_create_table(AZ_TABLE.$upgrade, $sql.$charset);
    if (!$create) {
        $created = maybe_create_table(AZ_TABLE.$upgrade, $sql);
    }
    
    // Only create the table if old does not already exist.
    if ($created) {
        if (!empty($upgrade)) {
            az_trace("upgrading...");
            $cr = chr(10);
            $css30 = $cr.'.azindex ul li.azalt {float:left; width:100%; background-color:lightgray;}';
            $css40 = $cr.'.azindex h2 { padding-top:0;margin-top:0}'
                    .$cr.'.azindex h2 .azcont {font-size:50%;font-style:italic;}';
            $css40g = $cr.'.azindex .head .azcont {font-size:90%;font-style:italic;}'
                    .$cr.'.azindex .subhead .azcont {font-size:90%;font-style:italic;}';
            foreach ($indexes as $index) {
                // Upgrade the custom css with new styles added since version 0.3, if necessary.
                // Note: must assign back to $index for this to work in PHP4.
                $index = az_maybe_add_css($index, 'li.azalt', $css30);       
                $index = az_maybe_add_css($index, 'h2 .azcont', $css40);       
                $index = az_maybe_add_css($index, '.head .azcont', $css40g, true);       
            
                // Upgrade the database table
                if ($wpdb->query("INSERT INTO ".AZ_TABLE.$upgrade." VALUES ('$index->name', '$index->idindex', '$index->categories'," 
                            ." '$index->tags', '$index->heading', '$index->subheading', '$index->description', '$index->cols'," 
                            ." '$index->headingseparator', '$index->itemsperpage', '$index->ignorechars',"
                            ." '$index->nlslocale', '$index->nlsequiv',"
                            ." '$index->customcsssingle', '$index->customcssgroup',"
                            ." '$index->customlinks', '$index->customsort', '$index->options', null, null)") === false) {
                    $error = 'upgrade-table-failed';
                    break;                
                }
    	    }
        }
        // Only adds and sets the value if the option does not exist.
        add_option("az_max_index", '0');
        az_trace("fn:az_plugin_activate : end");
    } else {
        $error = !empty($upgrade) ? 'create-table-failed' : 'fresh-create-table-failed';
    }

    // Perform the final step of the upgrade if everything has gone well so far, by
    // renaming the upgraded database table to  wp_az_indexes.
    if (!empty($upgrade) && empty($error)) {
        $rc = $wpdb->query("RENAME TABLE ".AZ_TABLE." TO ".AZ_TABLE.$old.', '.AZ_TABLE.$upgrade." TO ".AZ_TABLE);
        if ($rc !== false) {
            $rc = $wpdb->query("DROP TABLE IF EXISTS ".AZ_TABLE.$old);
        } else {
            $error = 'rename-table-failed';
        }
    }

    // If there was an error then remove the extra tables created during
    // the upgrade and save the error to be display during the admin-notices hook.
    if (!empty($error)) {
        if (!empty($upgrade)) {
            $wpdb->query("DROP TABLE IF EXISTS ".AZ_TABLE.$upgrade);
            $wpdb->query("DROP TABLE IF EXISTS ".AZ_TABLE.$old);
        }
        add_option('az_plugin_error', $error);
    }
    
    // If we're activating but didn't have to upgrade the database
    // we still have to flush all the caches because they could
    // be out of date.
    if (empty($upgrade)) {
        $indexes = az_get_indexes("idindex");
        foreach ($indexes as $index) {
            az_flush_cache($index->idindex);
        }
    }
    update_option("az_plugin_version", AZ_PLUGIN_VERSION);
}

/**
 * Called when the plugin is deactivated.  Currently does nothing.
 */
function az_plugin_deactivate() {
    az_trace("fn:az_plugin_deactivate : start");
    az_trace("fn:az_plugin_deactivate : end");
}

/**
 * Called when the plugin is uninstalled.  Removes the database table
 * and all the AZIndex options.  
 */
function az_plugin_uninstall() {
    require_once(ABSPATH.'wp-admin/includes/plugin.php');
    az_trace("fn:az_plugin_uninstall : start");
    global $wpdb;
    deactivate_plugins(AZ_PLUGIN_FILE);
    delete_option('az_plugin_version');
    delete_option('az_plugin_error');    
    delete_option('az_sort_index_table');    
    delete_option('az_max_index');
    delete_option('az_cache_dirty');    
    $wpdb->query("DROP TABLE IF EXISTS ".AZ_TABLE);
    az_trace("fn:az_plugin_uninstall : end");
}

/**
 * Check for the first compatible level of the database. 
 * This is currently version 0.7.1 when the CREATE TABLE statement was modified.
 * 
 * NOTE: The following is the way to test for a field change (if there are no option changes)
 *       $compatible = $wpdb->query("SHOW COLUMNS FROM ".AZ_TABLE." LIKE 'itemcache'");
 *
 *       The following is the way to test for an option change (i.e. just a new option was added:
 *       $compatible = false;
 *       $results = $wpdb->get_col("SHOW COLUMNS FROM ".AZ_TABLE." LIKE 'options'", 1);    
 *       if (!empty($results)) {
 *          $compatible = strpos($results[0], 'child-cats') !== false;         
 *       }
 *  
 * @return true if this is a compatible version of the database
 */
function az_is_compatible_version() {
    global $wpdb;
    $version = get_option('az_plugin_version');
    $compatible = version_compare($version, '0.7.1', '>=');
    return $compatible;
}

/**
 * Check for version of the database we cannot upgrade from. 
 * This is version 0.2.1 or lower.
 *
 * @return true if this is an incompatible alpha version of the database
 */
function az_is_alpha_version() {
    global $wpdb;
    return !$wpdb->query("SHOW COLUMNS FROM ".AZ_TABLE." LIKE 'idindex'");
}

/**
 * Detect the wp_az_indexes table to make sure it is present.
 *
 * @return true if the table is found in the blog's database
 */
function az_is_table_present() {
    global $wpdb;
    return $wpdb->query("DESCRIBE ".AZ_TABLE);
}

/**
 * Helper function for tacking CSS styles on to the custom CSS if
 * they are missing from a previous version.
 *
 * @param class $index
 * @param string $needle
 * @param string $style
 * @param boolean $grouponly
 */
function az_maybe_add_css($index, $needle, $style, $grouponly = false) {
    if (!$grouponly && !empty($index->customcsssingle) && strpos($index->customcsssingle, $needle) === false) {
        $index->customcsssingle .= $style;
    }
    if (!empty($index->customcssgroup) && strpos($index->customcssgroup, $needle) === false) {
        $index->customcssgroup .= $style;
    }
    return $index;
}

/**
 * Process the action requested by the user, then redirect to the main indexes admin page.  
 * If no action is specified, then skip all processing.
 */
function az_process_requests() {

    //az_trace('POST keys = '.implode('|', array_keys($_POST)));
    //az_trace('POST vals = '.implode('|', $_POST));
    //az_trace(' GET keys = '.implode('|', array_keys($_GET)));
    //az_trace(' GET vals = '.implode('|', $_GET));
    
    az_trace('--- init --------------------------------------------');
    az_trace('fn:az_process_requests : start');
    global $az_req;
    
    $req = new az_request();
        
    az_trace('fn:az_process_requests : action = '.$req->action);
    if (!empty($req->action) && ($req->action == 'az-add-index' || $req->action == 'az-update-index' 
                              || $req->action == 'az-delete-index' || $req->action == 'az-clear-cache' 
                              || $req->action == 'cancel' || $req->action == 'az-uninstall-plugin')) {

        if ($req->action == 'az-update-index' || $req->action == 'az-add-index') {

            // Collect the parameters from the POST data returned from the user.
            $req->set_vars_from_post();
        
            if (empty($req->error_field)) {
                az_trace("fn:az_process_requests : no errors found");
            
                $req->escape_vars();
                        
                if ($req->head == 'custom' || 
                   (($req->head == 'tags' || $req->head == 'cats') 
                          && !empty($req->headkey))) $req->head .= ':'.$req->headkey;
                if ($req->subhead == 'custom') $req->subhead .= ':'.$req->subheadkey;
                if ($req->desc == 'custom') $req->desc .= ':'.$req->desckey;
            }
        
        } else if ($req->action == 'az-delete-index' || $req->action == 'az-clear-cache') {
            // Collect the parameters from the GET data returned from the user and
            // fill the rest in from the database entry for the index.
            $req->set_vars_from_get();
        }
    
        if (empty($req->error_field)) {
    
            global $wpdb;
            $msgno = 0;
            $wpdb->show_errors();

            if ($req->action == 'az-update-index') {
                 
                // Update the settings for the index being edited.
                az_trace("fn:az_process_requests : updating index for indexid : ".$req->id." options: ".$req->options);
                
                $query = "UPDATE ".AZ_TABLE." SET name = '$req->name', categories = '$req->catids', tags = '$req->tagids', heading = '$req->head', "
                                                ."subheading = '$req->subhead', description = '$req->desc', cols = $req->cols, " 
                                                ."headingseparator = '$req->headsep', itemsperpage = $req->perpage, ignorechars = '$req->ignorechars', " 
                                                ."nlslocale = '$req->nlslocale', nlsequiv = '$req->nlsequiv', " 
                                                ."customcsssingle = '$req->csssingle', customcssgroup = '$req->cssgroup', " 
                                                ."customlinks = '$req->customlinks', customsort = '$req->customsort', "
                                                ."options = '$req->options', itemcache = NULL, linkcache = NULL WHERE idindex = $req->id";
                $rc = $wpdb->query($query);
                // Remove all HTML cache instances of the index page.
                az_flush_html_cache($req->id);
                $msgno = 1;
                az_trace("fn:az_process_requests : rc = ".$rc." query = ".$query);
                
            } else if ($req->action == 'az-add-index') {

            	$msgno = 2;            	
                // Create a new index and index page using the settings provided by the user.
                az_trace("fn:az_process_requests : adding index for indexid : ".$req->id);
                
                // Get the next available index id -- always increment, even if previous indexes are deleted.
                $idindex = intval(get_option('az_max_index')) + 1;
                $rc = $wpdb->query("INSERT INTO ".AZ_TABLE." VALUES ('$req->name', '$idindex', '$req->catids', '$req->tagids'," 
                                  ."'$req->head', '$req->subhead', '$req->desc', '$req->cols', '$req->headsep', '$req->perpage'," 
                                  ."'$req->ignorechars', '$req->nlslocale', '$req->nlsequiv', '$req->csssingle', '$req->cssgroup', '$req->customlinks'," 
                                  ."'$req->customsort', '$req->options', null, null)");
                if ($rc) {
                    update_option('az_max_index', $idindex);
                	$pageid = wp_insert_post(array('post_title' => $req->name, 'post_type' => 'page', 
                	                               'post_content' => '[az-index id="'.$idindex.'"]'));
                	if (empty($pageid)) {
                		$msgno = 22;
                	}
                } else {
                	$msgno = 21;
                }
                az_trace("fn:az_process_requests : az-add-index : rc = ".$rc."");
                
            } else if ($req->action == 'az-delete-index') {
                
            	// Delete the index selected by the user.
                az_trace("fn:az_process_requests : az-delete index : ".$req->id);
                
                $rc = $wpdb->query("DELETE FROM ".AZ_TABLE." WHERE idindex = $req->id");
                $msgno = $rc ? 3 : 31;
                az_flush_html_cache($req->id);

            } else if ($req->action == 'az-clear-cache') {
                
                // Clear the cache for the selected index
                az_trace("fn:az_process_requests : clearing cache for indexid : ".$req->id." options: ".$req->options);
                
                az_flush_cache($req->id);
                
                az_trace("fn:az_process_requests : az-clear-cache : rc = ".$rc." query = ".$query);
                $msgno = 4;
                                
            } else if ($req->action == 'az-uninstall-plugin') {
                az_plugin_uninstall();
                wp_redirect('plugins.php');
                return;
            }

            // Redirect the browser to the main admin index page.
            $req->action = 'az-redirect';
            az_redirect($msgno);
        }
    } else {
        // Nothing to do but fetch the parameters from the 'GET' request. 
        $req->set_vars_from_get();   
    }
    // Save the request parameters in a global variable for later use.
    $az_req = $req;
    az_trace('fn:az_process_requests : end');
}

function az_get_indexes($fields, $sortby = false) {
    global $wpdb;
    
    $current_sort = get_option('az_sort_index_table');
    
    if (!empty($sortby)) {
        if (strpos($current_sort, $sortby) !== false) {
            if (strpos($current_sort, "ASC") !== false) {
                $current_sort = str_replace("ASC", "DESC", $current_sort);
            } else {
                $current_sort = str_replace("DESC", "ASC", $current_sort);
            }
        } else {
            $current_sort = " ORDER BY ".$sortby." ASC";
        }
        update_option('az_sort_index_table', $current_sort);
    }
    az_trace("az_get_indexes : SELECT $fields FROM ".AZ_TABLE.$current_sort);
    return $wpdb->get_results("SELECT $fields FROM ".AZ_TABLE.$current_sort);
}

function az_flush_cache($idindex) {
    global $wpdb;
    $query = "UPDATE ".AZ_TABLE." SET itemcache = NULL, linkcache = NULL WHERE idindex = $idindex";
    $rc = $wpdb->query($query);
    az_flush_html_cache($idindex);
}

function az_get_index($idindex, $fields) {
    global $wpdb;
    return $wpdb->get_results("SELECT $fields FROM ".AZ_TABLE." WHERE idindex = $idindex");
}

/**
 * Fetch the names of the categories/tags being used by the current index.
 * If the specified category/tag has been deleted by the user, an error
 * string is returned instead.  There may be a ~ character in front of the
 * number which signifies that it should be excluded from the search.
 *
 * @param $term_id_string string of term ids (either categories or tags)
 * @param $taxonomy type of terms
 * @return the names of the categories or tags in the supplied string
 */
function az_get_term_names($term_id_string, $taxonomy) {
    az_trace('fn:az_get_term_names: term_id_string = '.$term_id_string.", taxonomy = ".$taxonomy);

    if (!empty($term_id_string)) {
        $items = preg_split('/([,]+)/', $term_id_string, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($items as $item) {
            $item = trim($item);
            if ($item == ',') {
                $output .= $item.($item == ',' ? ' ' : '');
            } else {
                az_trace('fn:az_get_term_names: item = '.$item);
                $not = '';
                if ($item[0] == '~') {
                    $not = '~'; 
                    $item = trim($item, '~');
                }
                $term = get_term_field('name', $item, $taxonomy, 'edit');
                if (!empty($term)) {
                    $output .= $not.$term;
                } else {
                    $output .= '*deleted '.$taxonomy.'*';
                }
            }
        }
    }
    return $output;
}

/**
 * Make an http redirect request to put us back on the main indexes admin page.
 * 
 * @param $msg number of the message to be displayed, if non-zero. 
 */
function az_redirect($msg) {
    az_trace('fn:az_redirect : redirect to '.AZ_PAGENAME.($msg != 0 ? '&msg='.$msg : ''));
    wp_redirect(AZ_PAGENAME.($msg != 0 ? '&msg='.$msg : ''));
}

/**
 * Display the manage index administration page.  The page will only
 * be displayed if the supplied action isn't 'az-redirect'.
 */
function az_display_admin_page() {
        
    az_trace('--- admin --------------------------------------------');
    az_trace('fn:az_manage_indexes : start');
    global $az_req;
    az_trace('fn:az_manage_indexes : action = '.$az_req->action);
    
    if ($az_req->action == 'az-sort-indexes' || empty($az_req->action) && empty($az_req->error_field)) {
        // By default, display the table of indexes.
        az_display_index_table($az_req); 
    } else if ($az_req->action != 'az-redirect') {
        // Otherwise, display the edit panel for an index. 
        az_display_index_dialog($az_req);        
    }
    az_trace('fn:az_manage_indexes : end');
}

/**
 * Display the table of indexes.
 *
 * @param $req request parameters used to fill in the table contents on the page
 */
function az_display_index_table($req) { 

    global $wpdb; 
    ?>

    <style type="text/css">
        .az-hide {
            display: none;
        }
        .az-error { 
            background-color: #fcc;
            color: #f00;
            font-weight: bold;          
        }
        #uninstall-button:disabled, #uninstall-button:disabled:hover {
            border-color: #ccc;
            background-color : #ddd;
            color : #fff;
        }
    </style>
    <div class="wrap">
        <?php if (az_is_table_present() && az_is_compatible_version()) { ?>
            <h2>Manage Indexes (<a href="<?php echo AZ_PAGENAME; ?>&amp;action=az-new-index">add new</a>)</h2>
            <?php if (!empty($req->info_message)) { ?>
                <div id="message" class="updated fade <?php echo ($req->info_message > 9 ? 'az-error' : '')?>">
                    <p><?php echo az_get_info_message($req->info_message); ?></p>
                </div>
            <?php }
            // Fetch the index definitions from the database.
            $indexes = az_get_indexes("*", $req->sortby);
            if (count($indexes) > 0) { ?>
                <br style="clear" />
                <table class="widefat">
                    <thead>
                        <tr>
                            <th><a href='<?php echo AZ_PAGENAME; ?>&amp;action=az-sort-indexes&amp;by=idindex' class='reset'>Index ID</a></th>
                            <th><a href='<?php echo AZ_PAGENAME; ?>&amp;action=az-sort-indexes&amp;by=name' class='reset'>Index Name</a></th>
                            <th>Included</th>
                            <th><a href='<?php echo AZ_PAGENAME; ?>&amp;action=az-sort-indexes&amp;by=categories' class='reset'>Categories</a></th>               
                            <th><a href='<?php echo AZ_PAGENAME; ?>&amp;action=az-sort-indexes&amp;by=tags' class='reset'>Tags</a></th>
                            <th><a href='<?php echo AZ_PAGENAME; ?>&amp;action=az-sort-indexes&amp;by=heading' class='reset'>Heading Field</a></th>
                            <th><a href='<?php echo AZ_PAGENAME; ?>&amp;action=az-sort-indexes&amp;by=subheading' class='reset'>Subheading Field</a></th>
                            <th><a href='<?php echo AZ_PAGENAME; ?>&amp;action=az-sort-indexes&amp;by=description' class='reset'>Description Field</a></th>
                            <th colspan="3">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="manage_index_pages">
                        <?php foreach ($indexes as $index) { 
                            // Decode for Javascript function call 
                            $name = addslashes(html_entity_decode($index->name));
                            if (az_begins_with($index->heading, "tags:")) {
                                $index->heading = "tags(".az_get_term_names(substr($index->heading, strpos($index->heading, ":") + 1), 'post_tag').")";
                            }
                            if (az_begins_with($index->heading, "cats:")) {
                                $index->heading = "cats(".az_get_term_names(substr($index->heading, strpos($index->heading, ":") + 1), 'category').")";
                            }
                            ?> 
                            <tr>
                                <td><?php echo $index->idindex; ?></td>
                                <td><?php echo $index->name; ?></td>
                                <td><?php echo az_get_included($index->options); ?>
                                <td><?php echo $index->categories != null ? az_get_term_names($index->categories, 'category') : '-'; ?></td>
                                <td><?php echo $index->tags != null ? az_get_term_names($index->tags, 'post_tag') : '-'; ?></td>
                                <td><?php echo $index->heading; ?></td>
                                <td><?php echo $index->subheading != 'none' ? $index->subheading : '-'; ?></td>
                                <td><?php echo $index->description != 'none' ? $index->description : '-'; ?></td>
                                <td><a href='<?php echo AZ_PAGENAME; ?>&amp;action=az-edit-index&amp;indexid=<?php echo $index->idindex; ?>' class='edit'>Edit</a></td>
                                <td><a href='<?php echo AZ_PAGENAME; ?>&amp;action=az-delete-index&amp;indexid=<?php echo $index->idindex; ?>' onclick='return delete_index(<?php echo $index->idindex.", \"".$name."\""; ?>)' class="reset">Delete</a></td>
                                <td><a href='<?php echo AZ_PAGENAME; ?>&amp;action=az-clear-cache&amp;indexid=<?php echo $index->idindex; ?>' class='reset'>Clear Cache</a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p>No index pages found.</p>
            <?php } 
        } else { ?>
            <div id="message" class="error fade az-error"><p>
                <?php if (!az_is_table_present()) {
                    echo az_get_admin_error_message('database-not-found'); 
                } else if (!az_is_alpha_version()) {
                    echo az_get_admin_error_message('version-out-of-date');
                } else {
                    echo az_get_admin_error_message('version-incompatible'); 
                } ?>
            </p></div>
        <?php } ?>
        <script type="text/javascript">
            function delete_index(id, name) {
                return confirm('Are you sure you want to delete the index "'+name+'"?');
            }
            function uninstall_plugin() {
                link = document.getElementById('uninstall');
                link.className = link.className == '' ? 'az-hide' : ''; 
                document.getElementById('uninstall-check').checked = false;
                document.getElementById('uninstall-button').disabled = true;
            }
            function uninstall_confirmed() {
                disable = !document.getElementById('uninstall-check').checked;
                document.getElementById('uninstall-button').disabled = disable;
            }
        </script>
        <div style="margin-top:20px;border:2px solid #ffcc99;background-color:#ffeecc;padding:10px;">
             For more help with AZIndex, please visit the 
             <a href="http://azindex.englishmike.net">AZIndex Plugin Website</a>
             where you will find tutorials, examples, and plenty of other information
             on using the plugin. 
        </div>
        <div class="tablenav" />
        <br class="clear" />
    </div>
    <?php az_display_uninstall_form(); ?>
    <br/>
</div>  <!-- Extra end-div pushes footer to bottom of screen. -->
<?php }

/**
 * Display the uninstall form in the current admin page.
 */
function az_display_uninstall_form() {  ?>
    <br/>
    <a style="font-size:8pt" href="#" onclick='uninstall_plugin()'>Uninstall AZIndex Plugin</a>
    <div id='uninstall' class='az-hide'>
        <p>You are about to uninstall the AZIndex plugin.
        <p>All indexes and settings created using the AZIndex plugin will be deleted, including the database table used by the plugin (wp_az_indexes).</p>
        <p>If you just want to deactivate the plugin temporarily, go to the <a href="plugins.php">Plugin Management</a> page and deactivate it from there</p>  
        <p style="color:red">Once you click uninstall, it cannot be undone.</p>
        <p>Note: this will not delete the plugin from the system. The plugin can still be reactivated from the Plugin Management page later.</p>
        <div style="margin:20px 0 0 40px;padding:30px;text-align:center;width:500px;border 1px dotted #ddd;background-color:#eee;">
            <p>Are you sure you want to uninstall the AZIndex plugin?</p>
            <input type="checkbox" name="uninstall-check" id="uninstall-check" onclick='uninstall_confirmed()'>&nbsp;Yes 
            <br/>
            <form name="uninstall" id="uninstall" class="add validate" method="post" action="<?php echo AZ_PAGENAME; ?>">
                <input type="hidden" name="action" value="az-uninstall-plugin" />
                <input style="margin-top: 20px;" disabled id="uninstall-button" type="submit" class="button" name="submit" value="Uninstall AZIndex" />
            </form>
        </div>  
    </div>
<?php }

/**
 * Display the add/edit index dialog, and set the fields specified in
 * $req if necessary. 
 *
 * @param $req parameters to be used in the dialog fields
 */
function az_display_index_dialog($req) { 

    $language = array("General European", "Czech", "Danish", "Esperanto", "Estonian", 
                      "Hungarian", "Icelandic", "Latvian", "Lithuanian", "Polish", "Romanian", 
                      "Roman", "Slovak", "Slovenian", "Spanish", "Swedish", "Turkish");
    ?>
    <style type="text/css">
        .az-error { 
            background-color: #fcc;                 
        }
        .az-hide {
            display: none;
        }
        .az-disabled {
            color: #AAA;
        }
        #az-form-table th {
            font-weight:bold;
            width:150px;
            padding-top:6px;
        }
    </style>
    <div class="wrap">
        <?php
        if (!empty($req->error_messages)) { ?>
            <div id="message" class="updated fade"><h3>Error message(s):</h3>
                <ul>
                    <?php foreach ($req->error_messages as $message) {
                       echo '- '.$message."<br/>";
                    } ?>
                </ul>
                <p>Please correct the highlighted field(s).</p>
            </div>
        <?php }
        if ($req->action == 'az-edit-index' || $req->action == 'az-update-index') {
            echo '<h2>Edit Index</h2>';
        } else {
            echo '<h2>Add Index</h2>';
        } ?>
            
        <div id="ajax-response"></div>
        <span id="autosave"></span>
        <form name="addindex" id="addindex" class="add validate" method="post" action="<?php echo AZ_PAGENAME; ?>">
            <table id="az-form-table" class="form-table">
                <tr class="form-field form-required">
                    <th scope="row"><label for="name">Index name</label></th>
                    <td class="<?php echo $req->error_field['name']; ?>" >
                        <input name="name" id="name" type="text" value="<?php echo $req->name; ?>" size="40" />
                        <?php if ($req->action == 'az-edit-index' || $req->action == 'az-update-index') { ?>
                            <br/>Modifying this field will not change the title of the page on which the index is located. (Use Manage =&gt; Pages instead.)
                        <?php } else { ?>
                            <br/>This name will be used as the title for the blog page which will be created for the index.
                        <?php } ?>
                    </td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label for="cats">Include/exclude posts in these categories</label></th>
                    <td class="<?php echo $req->error_field['cats']; ?>">
                        <input name="cats" id="cats" type="text" value="<?php echo $req->cats; ?>" size="40" />
                        <br/>Zero, one, or more categories, separated by commas (e.g. "tv, radio, movies").&nbsp; 
                             To exclude posts in a specific category from the index, put a '~' character in front of the category name (e.g. "~reviews, ~news") 
                    </td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label for="tags">Include/exclude posts with these tags</label></th>
                    <td class="<?php echo $req->error_field['tags']; ?>">
                        <input name="tags" id="tags" type="text" value="<?php echo $req->tags; ?>" size="40" />
                        <br/>Zero, one, or more tags, separated by commas (e.g. "american, canadian, british").&nbsp;
                             To exclude posts with a specific tag from the index, put a '~' character in front of the category name (e.g. "~culture, ~music")
                    </td>
                    
                </tr>
                <tr class="form-field">
                    <th scope="row"><label for="head">Index headings</label></th>
                    <td class="<?php echo $req->error_field['headkey']; ?>" >
                        <select id="head" name="head" style="width:10em;margin: 3px 0" 
                                onchange="selchanged('head-key-field', this[this.selectedIndex].value);">
                            <?php az_add_options($req->head, false); ?> 
                        </select>
                        <span id="head-key-field">
                            <span id="head-key-custom" style="padding-left:10px">Enter custom field key name:</span>
                            <span id="head-key-tags" style="padding-left:10px">Enter tags to exclude from index:</span>
                            <span id="head-key-cats" style="padding-left:10px">Enter categories to exclude from index:</span>
                            <input name="head-key" id="head-key" type="text" style="width:20em" 
                                   value="<?php echo $req->headkey; ?>" size="30" />
                        </span>
                    </td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label for="subhead">Index subheadings</label></th>
                    <td class="<?php echo $req->error_field['subheadkey']; ?>">
                        <select id="subhead" name="subhead" style="width:10em;margin:3px 0" 
                                onchange="selchanged('subhead-key-field', this[this.selectedIndex].value);
                                          selchanged('group-subhead', this[this.selectedIndex].value);"> 
                            <?php az_add_options($req->subhead); ?> 
                        </select>
                        <span id="subhead-key-field">
                            <span style="padding-left:10px">Enter custom field key name:</span>
                            <input name="subhead-key" id="subhead-key" type="text" style="width:20em" 
                                   value="<?php echo $req->subheadkey; ?>" size="20" />
                        </span>
                    </td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label for="desc">Description</label></th>
                    <td class="<?php echo $error_field['desckey']; ?>" >
                        <select id="desc" name="desc" style="width:10em;margin:3px 0" 
                                onchange="selchanged('desc-key-field', this[this.selectedIndex].value);">
                            <?php az_add_options($req->desc); ?> 
                        </select>
                        <span id="desc-key-field">
                            <span style="padding-left:10px">Enter custom field key name:</span>
                            <input name="desc-key" id="subhead-key" type="text" style="width:20em" 
                                   value="<?php echo $req->desckey; ?>" size="20" />
                        </span>
                    </td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label for="cols">Number of columns</label></th>
                    <td>
                        <select name="cols" style="float: left; width: 50px;">
                            <?php az_add_columns($req->cols); ?> 
                        </select>
                    </td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label>Options</label></th>
                    <td>
                        <ul style="list-style:none;padding:0;">
                            <li style="padding-top:5px;padding-bottom:5px;font-weight:bold;font-style:italic">
                                Standard Options:
                            </li>
                            <?php 
                            // Indent any option that starts with the name of the previous option in the list.
                            // (Lazy way to achieve some nice formatting of the options checkboxes :)                            
                            $options = az_get_options();
                            $prevoption = "noop";
                            $optindex = 0;
                            foreach ($options as $option => $text) {
                                if (az_begins_with($option, $prevoption)) {
                                    $padleft = 50;
                                } else {
                                    $padleft = 25;
                                    $prevoption = $option;
                                }
                                if (++$optindex == AZ_ADVANCED_OPTIONS) { ?>  
                                    <li style="padding-top:10px;padding-bottom:5px;font-weight:bold;;font-style:italic">
                                        Advanced Options: (handle with care)
                                    </li>
                                <?php } elseif ($optindex == AZ_NLS_OPTIONS) { ?>  
                                    <li style="padding-top:10px;padding-bottom:5px;font-weight:bold;;font-style:italic">
                                        National Language Support:
                                    </li>
                                <?php } ?>
                                    <li style="padding-left:<?php echo $padleft; ?>px">
                                    <input style="width:18px" type="checkbox" <?php echo az_check_value($option, $req->options); ?>/>&nbsp;
                                    <label style="font-size:11px" for="<?php echo $option; ?>"><?php echo $text; ?></label>
                                </li>
                            <?php } ?>
                        </ul>
                    </td>
                </tr>
                <tr class="form-field" id="headsep-row">
                    <th scope="row"><label for="headsep" id="headsep-head">Heading seperator</label></th>  
                    <td>
                        <input name="headsep" id="headsep" type="text" value="<?php echo $req->headsep; ?>" size="40" />
                        <br/>This string of characters will be inserted between headings and subheadings.&nbsp;
                             Any spaces added will be included. The default separator is ' - '.
                    </td>
                </tr>
                <tr class="form-field" id="perpage-row">
                    <th scope="row"><label for="perpage" id="perpage-head">Number of items per page</label></th>  
                    <td>
                        <input name="perpage" id="perpage" type="text" value="<?php echo $req->perpage; ?>" size="10" />
                    </td>
                </tr>
                <tr class="form-field" id="ignorechars-row">
                    <th scope="row"><label for="ignorechars" id="ignorechars-head">Ignore these characters when sorting</label></th>  
                    <td>
                        <input name="ignorechars" id="ignorechars" type="text" 
                               value="<?php echo (empty($req->ignorechars) ? '&quot;\'' : $req->ignorechars); ?>" size="20" />
                        <br/>Enter any characters at the start of headings that you want ignored while the index is sorted.&nbsp;
                             The default (when the option is selected) is single and double quote characters.
                    </td>
                </tr>
                <tr class="form-field" id="nlsequiv-row">
                    <th scope="row"><label for="nlsequiv" id="nlsequiv-head">Language table used for grouping index items</label></th>  
                    <td>
                        <select id="nlsequiv" name="nlsequiv" style="width:15em">
                            <?php foreach ($language as $lang) { ?> 
                                <option value="<?php echo $lang; ?>"<?php if (!strcmp($lang, $req->nlsequiv)) echo ' selected '; ?>><?php echo $lang; ?></option>
                            <?php } ?>                           
                        </select>
                        <br/>If your index is being sorted correctly, but the items are not grouped together the way they should be (e.g. for Swedish or Hungarian indexes),
                        then you may be able to select a more suitable language table for your index from this list.  
                    </td>
                </tr>
                <tr class="form-field" id="nlslocale-row">
                    <th scope="row"><label for="nlslocale" id="nlslocale-head">Locale used for sorting index</label></th>  
                    <td>
                        <input name="nlslocale" id="nlslocale" type="text" value="<?php echo $req->nlslocale; ?>" size="20" />
                        <br/>Enter the name of the NLS locale to be used when sorting the index.  You should only need to specify one if your index contains
                        items that are not being sorted into the correct order.  For example, if you have some foreign titles beginning with accented characters,
                        they may not be grouped correctly with the same, unaccented, characters.  The locale must be valid for the server WordPress is running on.
                    </td>
                </tr>
                <tr class="form-field" id="customlinks-row">
                    <th scope="row"><label for="customlinks" id="customlinks-head">Customized alphabetical links:</label></th>  
                    <td>
                        <input name="customlinks" id="customlinks" type="text" value="<?php echo empty($req->customlinks) ? AZ_INDEXCHARS : $req->customlinks; ?>" size="80" />
                        <br/>This is the list of characters you want to appear in the alphabetical links at the top of the page.  
                        You can add or remove characters from the list (e.g. accented characters), but they <b>all must appear in the order in which the index is sorted</b>.  
                        This is useful if you want to display non-English alphanumeric characters in your index or if you are using a custom sort function. 
                    </td>
                </tr>
                <tr class="form-field" id="customsort-row">
                    <th scope="row"><label for="customsort" id="customsort-head">Custom comparison function</label></th>  
                    <td>
                        <input name="customsort" id="customsort" type="text" value="<?php echo $req->customsort; ?>" size="40" />
                        <br/>Enter the name of the comparison function to be used in the custom sort -- e.g. <em>my_numeric_compare</em>  
                        <br/><br/>NOTE: The function must have the following signature: 
                        <br/><code>&nbsp;&nbsp;&nbsp;&nbsp;function &lt;name&gt;(&lt;item1&gt;, &lt;item2&gt;)</code> 
                        <br/>where &lt;item1&gt; and &lt;item2&gt; are arrays with the named fields: 'head', 'subhead', and 'desc'.  
                             The function should return less than zero, zero, or greater than zero depending on whether the first item comes before, equal to, or after the second. For example to sort item headings in numerical order: 
                        <br/><code>&nbsp;&nbsp;&nbsp;&nbsp;function my_numeric_compare($item1, $item2) {
                        <br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return (intval($item1['head']) - intval($item2['head']));
                        <br/>&nbsp;&nbsp;&nbsp;&nbsp;}</code>
                        <br/>Make sure to add your custom function to the WordPress code base.  One good place to put it is functions.php in your current theme. 
                    </td>
                </tr>
                <tr class="form-field" id="customcss-row">
                    <th scope="row"><label for="customcss" id="customcss-head">Custom stylesheets</label></th>  
                    <td>
                        <ul style="list-style:none;padding:0;float:left">
                            <li><label for="custom-single">Stylesheet for ungrouped index items:</label></li>
                            <li><textarea id="custom-single" name="custom-single" rows="8" cols="80"><?php echo az_get_stylesheet('custom-css', 0, $req->csssingle); ?></textarea></li>
                            <li>To reset the stylesheet to the default value, delete all of it, save the index, and then edit the index again.</li>  
                        </ul>  
                        <ul style="list-style:none;padding:0;float:left">
                            <li><label for="custom-group">Stylesheet for grouped index items:</label></li>
                            <li><textarea id="custom-group" name="custom-group" rows="8" cols="80"><?php echo az_get_stylesheet('custom-css,group-subhead', 0, $req->cssgroup); ?></textarea></li>
                            <li>To reset the stylesheet to the default value, delete all of it, save the index, and then edit the index again.</li>  
                        </ul>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <?php if ($req->action == 'az-edit-index' || $req->action == 'az-update-index') { ?>
                    <?php wp_nonce_field('az_plugin_update'); ?>
                    <input type="hidden" name="indexid" value="<?php echo $req->id; ?>" />
                    <input type="hidden" name="action" value="az-update-index" />
                    <input type="submit" class="button" name="submit" value="Save Changes" />&nbsp;
                    <input type="submit" class="button" name="cancel" value="Cancel" />
                <?php } else { ?>                    
                    <?php wp_nonce_field('az_plugin_add'); ?>
                    <input type="hidden" name="action" value="az-add-index" />
                    <input type="submit" class="button" name="submit" value="Add Index" />&nbsp;
                    <input type="submit" class="button" name="cancel" value="Cancel" />
                <?php } ?>                    
            </p>
        </form>
        <script type="text/javascript">
        
            // This function controls the hiding/showing, enabling/disabling
            // of selected items in the admin panel. 
            function selchanged(field, value) {
                if (field == 'group-subhead') {
                    value = value == 'none' ? 'disable' : 'enable';
                } else if (field == 'head-key-field') {
                    if (value == 'custom') {
                        document.getElementById('head-key-cats').className='az-hide';
                        document.getElementById('head-key-tags').className='az-hide';
                        document.getElementById('head-key-custom').className='';
                    } else if (value == 'tags') {
                        document.getElementById('head-key-cats').className='az-hide';
                        document.getElementById('head-key-tags').className='';
                        document.getElementById('head-key-custom').className='az-hide';
                    } else if (value == 'cats') {
                        document.getElementById('head-key-cats').className='';
                        document.getElementById('head-key-tags').className='az-hide';
                        document.getElementById('head-key-custom').className='az-hide';
                    }
                }
                if (value == 'custom' || value == 'tags' || value == 'cats' || value == 'checked') {
                    document.getElementById(field).className='';
                } else if (value == 'disable') { 
                    document.getElementById(field).disabled = true;
                    document.getElementById(field).parentNode.className = 'az-disabled';
                } else if (value == 'enable') { 
                    document.getElementById(field).disabled = false;
                    document.getElementById(field).parentNode.className = '';
                } else if (value == 'uncheck') {
                    document.getElementById(field).checked = false;
                } else { 
                    document.getElementById(field).className='az-hide';
                }
            }
            
            // Set the correct initial states of the dynamic part of the form. 
            selchanged('head-key-field', document.getElementById('head').value);
            selchanged('subhead-key-field', document.getElementById('subhead').value);
            selchanged('desc-key-field', document.getElementById('desc').value);
            selchanged('headsep-row', document.getElementById('group-subhead').checked ? '' : 'checked');
            selchanged('perpage-row', document.getElementById('multipage').checked ? 'checked' : '');
            selchanged('ignorechars-row', document.getElementById('ignore-chars').checked ? 'checked' : '');
            selchanged('nlslocale-row', document.getElementById('nls').checked && document.getElementById('nls-locale').checked ? 'checked' : '');
            selchanged('nlsequiv-row', document.getElementById('nls').checked && document.getElementById('nls-equiv').checked ? 'checked' : '');
            selchanged('customcss-row', document.getElementById('custom-css').checked ? 'checked' : '');
            selchanged('customsort-row', document.getElementById('custom-sort').checked ? 'checked' : '');
            selchanged('customlinks-row', document.getElementById('custom-links').checked ? 'checked' : '');
            selchanged('include-pages-exclude-posts', document.getElementById('include-pages').checked ? 'enable' : 'disable');     
            selchanged('multipage-links-above', document.getElementById('multipage').checked ? 'enable' : 'disable');     
            selchanged('multipage-links-below', document.getElementById('multipage').checked ? 'enable' : 'disable');
            selchanged('alpha-head-page', document.getElementById('alpha-head').checked ? 'enable' : 'disable');     
            selchanged('alpha-head-col', document.getElementById('alpha-head').checked ? 'enable' : 'disable');
            selchanged('alpha-links-unused', document.getElementById('alpha-links').checked ? 'enable' : 'disable');     
            selchanged('alpha-links-two-rows', document.getElementById('alpha-links').checked ? 'enable' : 'disable');
            selchanged('custom-css-striping', document.getElementById('custom-css').checked ? 'enable' : 'disable');
            selchanged('nls-locale', document.getElementById('nls').checked ? 'enable' : 'disable');
            selchanged('nls-equiv', document.getElementById('nls').checked ? 'enable' : 'disable');
            dropdown = document.getElementById('subhead');
            selchanged('group-subhead', dropdown[dropdown.selectedIndex].value);
// TODO Add back in when FF 3.0 bug is fixed. 
//          selchanged('custom-single', document.getElementById('group-subhead').checked ? 'disable' : 'enable');
//          selchanged('custom-group', document.getElementById('group-subhead').checked ? 'enable' : 'disable');
            selchanged('custom-links', document.getElementById('alpha-links').checked ? 'enable' : 'disable');
        </script>
    </div>
<?php }

/**
 * Add all the options to the index heading/description selection lists.
 *
 * @param $selected selected item
 * @param $hasnone true if the list has a "none" option
 */
function az_add_options($selected, $hasnone = true) {
    if ($hasnone) {
        az_add_option('none', 'none', $selected == 'none');
    }
    az_add_option('title', 'Title', $selected == 'title');
    if (!$hasnone) {
        az_add_option('cats', 'Categories', $selected == 'cats');
        az_add_option('tags', 'Tags', $selected == 'tags');
    }
    az_add_option('excerpt', 'Excerpt', $selected == 'excerpt');
    az_add_option('author', 'Author', $selected == 'author');
    az_add_option('custom', 'Custom field', $selected == 'custom');
}

/**
 * Add an option field to one of the index heading/description selection lists.
 *
 * @param $name name of option field
 * @param $title title of option filed
 * @param $sel true if item is to be selected
 */
function az_add_option($name, $title, $sel) {
    echo '<option value="'.$name.'" '.($sel ? 'selected ' : '').'>'.$title.'</option>';
}

/**
 * Add the numeric options within the column selection box, and set one as selected, if necessary. 
 *
 * @param $sel option to be selected
 */
function az_add_columns($sel) {
    for ($i = 1; $i < 5; $i++) {
        echo '<option '.($sel == $i ? 'selected ' : '').'>'.$i.'</option>';
    }
}

/**
 * Generate an <option> field, and set to checked if necessary.
 * Also adds javascript to select items in order to dynamically show/enable related fields.
 *
 * @param $value value of the option field
 * @param $sel set to checked if option is contained within this string.
 * @return string to the included in the option tag
 */
function az_check_value($value, $sel) {
   $output = 'name="options[]" id="'.$value.'" value="'.$value.'" '.(strpos($sel, $value) !== false ? 'checked ' : '');
   if ($value == 'group-subhead') {
       $output .= 'onclick="selchanged(\'headsep-row\', this.checked ? \'\' : \'checked\');'
// TODO - Add back in when FF 3.0 bug is fixed.
//                         .'selchanged(\'custom-single\', this.checked ? \'disable\' : \'enable\');'
//                         .'selchanged(\'custom-group\', this.checked ? \'enable\' : \'disable\');'
                           .'" ';
   }
   if ($value == 'multipage') {
       $output .= 'onclick="selchanged(\'perpage-row\', this.checked ? \'checked\' : \'\');'
                          .'selchanged(\'multipage-links-above\', this.checked ? \'enable\' : \'disable\');'     
                          .'selchanged(\'multipage-links-below\', this.checked ? \'enable\' : \'disable\');" ';
   }
   if ($value == 'ignore-chars') {
       $output .= 'onclick="selchanged(\'ignorechars-row\', this.checked ? \'checked\' : \'\');" ';
   }
   if ($value == 'alpha-head') {
       $output .= 'onclick="selchanged(\'alpha-head-page\', this.checked ? \'enable\' : \'disable\');'
                          .'selchanged(\'alpha-head-col\', this.checked ? \'enable\' : \'disable\');" ';
   }
   if ($value == 'alpha-links') {
       $output .= 'onclick="selchanged(\'alpha-links-unused\', this.checked ? \'enable\' : \'disable\');'
                          .'selchanged(\'alpha-links-two-rows\', this.checked ? \'enable\' : \'disable\');'     
                          .'selchanged(\'custom-links\', this.checked ? \'enable\' : \'disable\');" ';
   }
   if ($value == 'include-pages') {
       $output .= 'onclick="selchanged(\'include-pages-exclude-posts\', this.checked ? \'enable\' : \'disable\');" ';
   }
   if ($value == 'nls') {
       $output .= 'onclick="selchanged(\'nls-locale\', this.checked ? \'enable\' : \'disable\');'
                          .'selchanged(\'nls-equiv\', this.checked ? \'enable\' : \'disable\');'     
                          .'selchanged(\'nlslocale-row\', this.checked && document.getElementById(\'nls-locale\').checked ? \'checked\' : \'\');'
                          .'selchanged(\'nlsequiv-row\', this.checked && document.getElementById(\'nls-equiv\').checked ? \'checked\' : \'\');" ';
   }
   if ($value == 'nls-locale') {
       $output .= 'onclick="selchanged(\'nlslocale-row\', this.checked && document.getElementById(\'nls\').checked ? \'checked\' : \'\');" ';
   }
   if ($value == 'nls-equiv') {
       $output .= 'onclick="selchanged(\'nlsequiv-row\', this.checked && document.getElementById(\'nls\').checked ? \'checked\' : \'\');" ';
   }
   if ($value == 'custom-css') {
       $output .= 'onclick="selchanged(\'customcss-row\', this.checked ? \'checked\' : \'\');'
                          .'selchanged(\'custom-css-striping\', this.checked ? \'enable\' : \'disable\');" ';
   }
   if ($value == 'custom-sort') {
       $output .= 'onclick="selchanged(\'customsort-row\', this.checked ? \'checked\' : \'\');" ';
   }
   if ($value == 'custom-links') {
       $output .= 'onclick="selchanged(\'customlinks-row\', this.checked ? \'checked\' : \'\');" ';
   }
   return $output;
}

/**
 * Get the information message to display (if any).
 */
function az_get_info_message($id) {
    switch ($id) {
        case 1: $message = 'Index updated successfully.'; break;
        case 2: $message = 'Index added successfully.'; break;
        case 3: $message = 'Index deleted successfully.'; break;
        case 4: $message = 'Index cache has been cleared successfully.'; break;
        case 11: $message = 'ERROR: Unable to update the index'; break;
        case 21: $message = 'ERROR: Unable to add the new index'; break;
        case 22: $message = 'WARNING: Index added successfully, but unable to create blog page'; break;
        case 31: $message = 'ERROR: Unable to delete the index'; break;
        case 41: $message = 'ERROR: Unable to clear the index cache'; break;
    }
    return $message;
}

/**
 * Display serious error messages to the user.
 *
 * @param string $id the id of the error message to display.
 * @return none
 */
function az_get_admin_error_message($id) {
    global $wpdb;
    $error = 'AZINDEX_PLUGIN_ERROR: ';
    switch ($id) {
        case 'database-not-found': 
            $error .= '101 - The required AZIndex database table - '.AZ_TABLE.' - does not exist. '
                     .'Please deactivate then re-activate the AZIndex plugin to correct the problem.';
                     break;
        case 'version-out-of-date': 
            $error .= '102 - The AZIndex database table is out-of-date with this version of the plugin. '  
                     .'Please deactivate then re-activate the AZIndex plugin to upgrade the database. '
                     .'All existing index settings in the AZIndex table will be saved.';
            break;
        case 'version-incompatible': 
            $error .= '103 - The AZIndex database table is incompatible with this version of the plugin. '  
                     .'Please UNINSTALL the plugin using the link below then reactive the AZIndex plugin to rectify the problem. '
                     .'All existing index settings in the AZIndex table will be lost.';
            break;
        case 'rename-table-failed': 
            $error .= '201 - The AZIndex plugin was unable to finish upgrading your existing index settings. ';  
            break;
        case 'create-table-failed': 
            $error .= '202 - The AZIndex plugin was unable to create the new settings database table while upgrading to the latest version. ';
            break;  
        case 'fresh-create-table-failed': 
            $error .= '203 - The AZIndex plugin was unable to create the new settings database table.';  
            break;
        case 'upgrade-table-failed':
            $error .= '204 - The AZIndex plugin was unable to upgrade the new settings while upgrading to the latest version. ';
            break;  
    }
    
    // If this is an upgrade error, then display more information to help assure the user 
    // that no data has been lost, and to point them to somewhere where they can get some help.
    if (strpos($id, 'table-failed') !== false) {
        $error = '<p>'.$error.'</p>'
                .(strpos($id, 'fresh') === false ? '<p>All your existing settings have been saved, but you will not be able to use the new version of AZIndex plugin. '
                .'If you want to continue running AZIndex, please reinstall the <a href="http://wordpress.org/extend/plugins/azindex/download/">previous version of AZIndex</a> '
                .'you were running on this blog.</p>' : '')
                .'<p>For more assistance please visit to the <a href="http://englishmike.net/http://englishmike.net/azindex-plugin-section/azindex-plugin/">AZindex Plugin Home Page</a>. '
                .'or contact the plugin author at <a href="mailto:azindex@englishmike.net">azindex@englishmike.net</a> with this information.</p>'
                .'<p>WordPress version = '.get_bloginfo('version')
                .'<br/>Database version = '.$wpdb->db_version()
                .'<br/>PHP version = '.phpversion()
                .'<br/>OS version = '.PHP_OS.'</p>';
    }
    return $error;
}

/**
 * Action hook used to display an error message on the plugins page 
 * if something has gone wrong with the database during the install or 
 * upgrade.
 */
function az_admin_notices() {

    $error = get_option('az_plugin_error');
    if (!empty($error)) {
        $message = az_get_admin_error_message($error);
    }

    if (!empty($message)) {
        echo '<div class="error" style="padding:10px 10px">';
        echo '<strong>Fatal AZIndex Plugin Upgrade Error</strong>';
        echo '<p>'.$message.'</p>';
        
        if (function_exists('deactivate_plugins') ) {
            deactivate_plugins( AZ_PLUGIN_FILE);
            echo '<p>The AZIndex plugin has been deactivated.</p>';
        }
        echo '</div>';
    }
    
    // Remember to delete the option so the message is not shown again.
    delete_option('az_plugin_error');
}


function az_get_included($options) {
	$included = "posts";
    if (az_is_set($options, 'include-pages')) {
        if (az_is_set($options, 'include-pages-exclude-posts')) {
            $included = "pages";
        } else {
            $included .= " & pages";
        }
    }
    return $included;
}

/**
 * htmlspecialchars_decode is a >= PHP5.1-only function.  This version of the function 
 * will allow the plugin to work on PHP4 installations. 
 */
function az_htmlspecialchars_decode($string, $style=ENT_COMPAT) {
    if (floatval(phpversion()) >= 5.1) {
        $string = htmlspecialchars_decode($string, $style); 
    } else {
        $translation = array_flip(get_html_translation_table(HTML_SPECIALCHARS,$style));
        if ($style === ENT_QUOTES) { 
            $translation['&#039;'] = '\''; 
        }
        $string = strtr($string,$translation);
    }     
    return $string;  
}

/**
 * Class containing all the data sent in a POST or GET message or
 * an entry retrieved from the indexes database.
 */
class az_request { 
    var $action;       // Current action to be processed
    var $sortby;       // Set if the index table is to be sorted
    var $name;         // Name of the index
    var $id;           // Unique ID of the index
    var $cats;         // Included categories
    var $catids;       // Ids of specified categories
    var $tags;         // Included tags
    var $tagids;       // Ids of specified tags
    var $head;         // Source of the index's headings
    var $headkey;      // Name of the custom field key used for a custom heading
    var $headkeyids;   // Copy of the original, non-converted headkey field
    var $subhead;      // Source of the index's subheadings
    var $subheadkey;   // Name of the custom field key used for a custom subheading
    var $desc;         // Source of the index's descriptions
    var $desckey;      // Name of the custom field key used for a custom description
    var $cols;         // Number of columns on the index page
    var $headsep;      // String separating headings from the subheadings
    var $perpage;      // Number of items per page (if multipage option selected
    var $ignorechars;  // Characters to be ignored at the start of headings when they are being sorted 
    var $nlslocale;    // Locale to be used for sorting the index 
    var $nlsequiv;     // Collation equivalence table to be used for grouping the index
    var $csssingle;    // Custom CSS for single index items
    var $cssgroup;     // Custom CSS for grouped index items
    var $customlinks;  // Custom links string
    var $customsort;   // Name of custom sort function.
    var $options;      // Options for the index
    var $itemcache;    // Item cache
    var $linkcache;    // Link cache
    var $info_message; // Informational message to the output to the user
    var $error_field = array();    // Input fields currently in error 
    var $error_messages = array(); // Error message to be output to the admin user

    /**
     * Constructor for class. Initialize the action instance variable immediately. 
     */
    function az_request() {
        az_trace('fn:az_request');
        $this->action = $_GET['action'];
        if (empty($this->action)) {
            if (empty($_POST['cancel'])) {
                $this->action = $_POST['action'];
            } else {
                $this->action = 'cancel';
            }
        }
    }
    
    /**
     * Populate the instance data for the class using the GET request parameters.
     * Also generate the appropriate error messages should problems be found.
     */
    function set_vars_from_post() {
        az_trace('fn:set_vars_from_post');
        $this->name = stripslashes(trim($_POST['name']));
        $this->id = $_POST['indexid'];
        $this->cats = attribute_escape(stripslashes(trim($_POST['cats'])));
        $this->tags = attribute_escape(stripslashes(trim($_POST['tags'])));
        $this->head = $_POST['head'];
        $this->headkey = attribute_escape(stripslashes(trim($_POST['head-key'])));
        $this->subhead = $_POST['subhead'];
        $this->subheadkey = attribute_escape(stripslashes(trim($_POST['subhead-key'])));
        $this->desc = $_POST['desc'];
        $this->desckey = attribute_escape(stripslashes(trim($_POST['desc-key'])));
        $this->cols = intval($_POST['cols']);
        $this->headsep = $_POST['headsep'];
        $this->perpage = intval($_POST['perpage']);
        $this->ignorechars = attribute_escape(stripslashes(trim($_POST['ignorechars'])));
        $this->nlslocale = attribute_escape(stripslashes(trim($_POST['nlslocale'])));
        $this->nlsequiv = $_POST['nlsequiv'];
        $this->csssingle = $_POST['custom-single'];
        $this->cssgroup = $_POST['custom-group'];
        $this->customlinks = $_POST['customlinks'];
        $this->customsort = $_POST['customsort'];
        $this->options = !empty($_POST['options']) ? implode(',', $_POST['options']) : '';
        $this->info_message = intval($_POST['msg']);

        if (empty($this->name)) {
            $this->error_field['name'] = 'az-error';
            $this->error_messages[] = 'A name for the index must be specified.';
        }
        if ($this->head == 'custom' && empty($this->headkey)) {
            $this->error_field['headkey'] = 'az-error';
            $this->error_messages[] = 'A key name for the heading custom field must be specified.';
        }
        if ($this->subhead == 'custom' && empty($this->subheadkey)) {
            $this->error_field['subheadkey'] = 'az-error';
            $this->error_messages[] = 'A key name for the subheading custom field must be specified.';
        }
        if ($this->desc == 'custom' && empty($this->desckey)) {
            $this->error_field['desckey'] = 'az-error';
            $this->error_messages[] = 'A key name for the description custom field must be specified.';
        }
        if (!empty($this->cats)) {
            $cats = $this->validate_taxonomy_field($this->cats, 'category', 'cats', 'category');
            if (!empty($cats)) {
                $this->catids = $cats;
            }
        }
        if (!empty($this->tags)) {
            $tags = $this->validate_taxonomy_field($this->tags, 'post_tag', 'tags', 'tag');
            if (!empty($tags)) {
                $this->tagids = $tags;
            }
        }
        if ($this->head == 'cats' && !empty($this->headkey)) {
            $cats = $this->validate_taxonomy_field($this->headkey, 'category', 'headkey', 'category', true);
            if (!empty($cats) && empty($this->error_messages)) {
                $this->headkey = $cats;
            }
        }
        if ($this->head == 'tags' && !empty($this->headkey)) {
            $tags = $this->validate_taxonomy_field($this->headkey, 'post_tag', 'headkey', 'tag', true);
            if (!empty($tags) && empty($this->error_messages)) {
                $this->headkey = $tags;
            }
        }
    }

    function validate_taxonomy_field($terms, $type, $field, $name, $excludes_only = false) {
        $ids = $this->validate_terms($terms, $type, $excludes_only);
        if (empty($ids)) {
            $this->error_field[$field] = 'az-error';
            $this->error_messages[] = "Only valid $name names and/or slugs can be specified.";
        }
        return $ids;
    }
    
    /**
     * Populate the instance data for the class using the GET request parameters
     * and, where necessary, the selected entry in the index database table.
     */
    function set_vars_from_get() {
        az_trace('fn:set_vars_from_get');
        $this->id = intval($_GET['indexid']);
        $this->name = $_GET['name'];
        $this->info_message = intval($_GET['msg']);
        
        if ($this->action == 'az-edit-index') {
            $this->set_vars_from_table($this->id);
        } else if ($this->action == 'az-sort-indexes') {
            $this->sortby = $_GET['by'];
        } 
    }

    /**
     * Fetch the settings for the current index from the database.
     *
     * @param $id id of index page
     * @return number of indexes found (i.e. if zero, then there is no index) 
     */
    function set_vars_from_table($id) {
        az_trace('fn:set_vars_from_table');
        global $wpdb;
        $indexes = $wpdb->get_results("SELECT * FROM ".AZ_TABLE." WHERE idindex = $id");
        if (count($indexes) != 0) {
            $this->set_vars_from_index($indexes[0]);
        }
        return count($indexes);
    }
    /**
     * Fetch the settings for the supplied index object
     *
     * @param $id id of index page
     * @return number of indexes found (i.e. if zero, then there is no index) 
     */
    function set_vars_from_index($index) {
        az_trace('fn:set_vars_from_index');
        $this->id = $index->idindex;
        $this->name = $index->name;
        $this->catids = $index->categories;
        $this->cats = az_get_term_names($this->catids, 'category');
        $this->tagids = $index->tags;
        $this->tags = az_get_term_names($this->tagids, 'post_tag');
        $this->head = $index->heading;
        
        if (az_begins_with($this->head, 'custom:') || az_begins_with($this->head, 'tags:') || az_begins_with($this->head, 'cats:')) {
            $this->head = substr($index->heading, 0, strpos($index->heading, ':'));
            $this->headkey = substr($index->heading, strpos($index->heading, ':') + 1);
            if ($this->head == 'cats') {
                $this->headkeyids = $this->headkey;
                $this->headkey = az_get_term_names($this->headkey, 'category');
            }
            if ($this->head == 'tags') {
                $this->headkeyids = $this->headkey;
                $this->headkey = az_get_term_names($this->headkey, 'post_tag');
            }
        }
        $this->subhead = $index->subheading; 
        if (az_begins_with($this->subhead, 'custom:')) {
            $this->subhead = 'custom';
            $this->subheadkey = substr($index->subheading, strpos($index->subheading, ':') + 1);
        }
        $this->desc = $index->description;
        if (az_begins_with($this->desc, 'custom:')) {
            $this->desc = 'custom';
            $this->desckey = substr($index->description,  strpos($index->description, ':') + 1);
        }
        $this->cols = $index->cols;
        $this->headsep = $index->headingseparator; 
        $this->perpage = $index->itemsperpage; 
        $this->ignorechars = $index->ignorechars; 
        $this->nlslocale = $index->nlslocale; 
        $this->nlsequiv = $index->nlsequiv; 
        $this->csssingle = $index->customcsssingle;
        $this->cssgroup = $index->customcssgroup;
        $this->customlinks = $index->customlinks;
        $this->customsort = $index->customsort;
        $this->options = $index->options;
        $this->itemcache = $index->itemcache;
        $this->linkcache = $index->linkcache;
    }
    
    /**
     * Escape all the strings that might contain illegal characters,
     * ready for insertion into the wordpress database.
     */
    function escape_vars() {
        $this->name = attribute_escape($this->name);
        $this->cats = attribute_escape($this->cats);
        $this->tags = attribute_escape($this->tags);
        $this->headkey = attribute_escape($this->headkey);
        $this->subheadkey = attribute_escape($this->subheadkey);
        $this->desckey = attribute_escape($this->desckey);
    }

    /**
     * Validate the terms (categories/tags) by fetching ids.  A term can be prefixed by a ~ character
     * which notes that the search should exclude posts with that term.  
     *
     * @param $term_string string of term names/slugs
     * @param $taxonomy type of term
     * @return string of valid term ids 
     */
    function validate_terms($term_string, $taxonomy, $excludes_only = false) {
        az_trace('fn:validate_terms: term_string = '.$term_string.", taxonomy = ".$taxonomy);
        
        $items = preg_split('/([,]+)/', $term_string, -1, PREG_SPLIT_DELIM_CAPTURE);
        
        foreach ($items as $item) {
            $item = az_htmlspecialchars_decode(trim($item));
            if ($item == ',') {
                $output .= $item;
            } else {
                $exclude = $item[0] == '~';
                if ($exclude) {
                    $item = trim($item,'~');
                }
                $term = get_term_by('name', $item, $taxonomy);
                if (empty($term)) {
                    $term = get_term_by('slug', $item, $taxonomy);
                }
                if (!empty($term)) {
                    $output .= (($exclude && !$excludes_only) ? '~' : '').$term->term_id;
                } else {
                    unset($output);
                    break;
                }
            }
        }
        return $output;
    }
}

/**
 * Helper string function.
 *
 * @param $str haystack
 * @param $sub needle
 * @return true if the haystack starts with the needle
 */
function az_begins_with($str, $sub) {
    return substr($str, 0, strlen($sub)) == $sub;
}

/**
 * Helper string function.
 *
 * @param $str haystack
 * @param $sub needle
 * @return true if the haystack ends with the needle
 */
function az_ends_with($str, $sub) {
    return substr($str, strlen($str) - strlen($sub)) == $sub;
}

/**
 * DEBUG function to send trace output to a trace file on the server.
 * For debugging purposes only.
 *
 * @param $output output to be sent to trace file
 */
function az_trace($output, $array = 0) {
    if (AZ_DEBUG) {
        //az_println($output);
        $handle = fopen(ABSPATH.'trace', 'a+');
        fwrite($handle, $output);
        if (!empty($array)) {
            fwrite($handle, $implode(", ", $array));
        }
        fwrite($handle, "\n");
        fclose($handle);
    }
}

/**
 * DEBUG function for println out information on the web page. 
 * Does not work in cases where the webpage is not being generated. 
 *
 * @param $value string to be output.
 */
function az_println($value) {
    echo '<span style="color:red;background:yellow">'.$value.'</span><br/>';
}

function az_print_r($var) {
    echo str_replace(chr(10), '<br>', print_r($var, true));
}

function az_print_t($var) {
    az_trace(print_r($var, true));
}

function add_tons() {
	for ($i = 967; $i < 1000; $i++) {
		addp("Painting number $i", "Artist number $i", "This is the description of the painting");
	}
}

function addp($w, $a, $e) {
    $id = wp_insert_post(array('post_title' => $w.' by '.$a, 'post_status' => 'publish', 'post_excerpt' => $e, 'post_category' => array(get_cat_ID('cars'))));
    wp_set_post_tags($id, 'paintings');
    add_post_meta($id, 'Artist', $a);
    add_post_meta($id, 'Work', $w);
}
?>