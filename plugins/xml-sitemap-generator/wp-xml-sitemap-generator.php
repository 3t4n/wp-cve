<?php
/*
Plugin Name: Xml Sitemap Generator
Plugin URI: http://www.responsive-mind.fr/wp-xml-sitemap-generator
Description: Xml sitemap generator for WordPress.
Version: 1.1
Author: Renaud Mariage-Gaudron
Author URI: http://www.responsive-mind.fr
License: GPL2
*/

/*  Copyright 2013  Renaud Mariage-Gaudron

    This program is free software; you can redistribute it and/or modify
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


define('TABLE_CONFIG', 'xml_sitemap_generator');
define('FILENAME', 'sitemap.xml');


class wp_xml_sitemap_generator {
    
   private $useTags;
   private $useCategories;
   private $usePosts;
   private $usePages;
   
 
  
    /**
     * Constructor for the class
     * @global type $wpdb
     * @param type $mode
     */
    function __construct($mode = 'install') {
        global $wpdb;
        $wpdb->tbl_SSW_CONF = $wpdb->prefix.'xml_sitemap_generator_config';
        if ($mode == 'install') {
            add_action('admin_menu', array(&$this, 'admin_menu'));
        }
    }
    
    /**
     * Creates a link to the plugin in the "extensions" menu
     */
    function admin_menu() {
        add_submenu_page('plugins.php', 'wp_xml_sitemap_generator', 'Xml Sitemap Generator', 8, __FILE__, array(&$this, 'home_page'));
    }
    
    /**
     * Installs the plugin, updates the settings and display the settings scrren
     */
    function home_page() {
        global $post;
        // Installs the plugin
        $this->install();
        // Uses the global database object
        global $wpdb;
        // Updates settings
        if (isset($_POST['action']) && ($_POST['action'] == 'settings')) {
            //- usePosts
            if ($_POST['usePosts'] == 'yes') {
                $this->usePosts = true;
                $update_nb = $wpdb -> query('UPDATE '.$wpdb->prefix.TABLE_CONFIG.' SET usePosts = 1');
            }else {
                $this->usePosts = false;
                $update_nb = $wpdb -> query('UPDATE '.$wpdb->prefix.TABLE_CONFIG.' SET usePosts = 0');
            }   
            //- usePages
            if ($_POST['usePages'] == 'yes') {
                $this->usePages = true;
                $update_nb = $wpdb -> query('UPDATE '.$wpdb->prefix.TABLE_CONFIG.' SET usePages = 1');
            }else {
                $this->usePages = false;
                $update_nb = $wpdb -> query('UPDATE '.$wpdb->prefix.TABLE_CONFIG.' SET usePages = 0');
            }   
            //- useTags
            if ($_POST['useTags'] == 'yes') {
                $this->useTags = true;
                $update_nb = $wpdb -> query('UPDATE '.$wpdb->prefix.TABLE_CONFIG.' SET useTags = 1');
            }else {
                $this->useTags = false;
                $update_nb = $wpdb -> query('UPDATE '.$wpdb->prefix.TABLE_CONFIG.' SET useTags = 0');
            }   
            //- useCategories
            if ($_POST['useCategories'] == 'yes') {
                $this->useCategories = true;
                $update_nb = $wpdb -> query('UPDATE '.$wpdb->prefix.TABLE_CONFIG.' SET useCategories = 1');
            }else {
                $this->useCategories = false;
                $update_nb = $wpdb -> query('UPDATE '.$wpdb->prefix.TABLE_CONFIG.' SET useCategories = 0');
            }   
        }
        
        // Sitemap generation
        if (isset($_POST['action']) && ($_POST['action'] == 'sitemap')) {
            $filename = dirname(__FILE__).'/'.FILENAME;
            
            if (is_writable($filename)) {
                $config = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.TABLE_CONFIG);
                $this->usePosts = $config[0]->usePosts;
                $this->usePages = $config[0]->usePages;
                $this->useCategories = $config[0]->useCategories;
                $this->useTags = $config[0]->useTags;
                
                if (!$handle = fopen($filename, 'w')) {
                    echo 'Unable to open file "'.$filename.'"';
                    exit;
                }
                
                fwrite($handle, '<?xml version="1.0" encoding="UTF-8"?>'."\r\n");
                fwrite($handle, '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\r\n");
                fwrite($handle, '<!-- Created with the Xml Sitemap Generator by Renaud Mariage-Gaudron. See http://www.responsive-mind.fr/wp-xml-sitemap-generator for more details -->'."\r\n");
                if($this->usePosts) {
                    fwrite($handle, "<!-- Posts list -->\r\n");
                    $the_query = new WP_Query( array('post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => '-1', 'orderby' => 'date', 'order' => 'DESC', 'post_status' => array( 'publish', 'private') ) );
                    while ( $the_query->have_posts() ) {
                        $the_query->the_post();
                        fwrite($handle, "\t".'<url>'."\r\n"
                            ."\t\t".'<lastmod>'.get_the_date('Y-m-d').'</lastmod>'."\r\n"
                            ."\t\t".'<loc>'.get_permalink().'</loc>'."\r\n"
                        ."\t".'</url>'."\r\n");
                    }
                }
                if($this->usePages) {
                    fwrite($handle, "<!-- Pages list -->\r\n");
                    $the_query = new WP_Query( array('post_type' => 'page', 'post_status' => 'publish', 'posts_per_page' => '-1', 'orderby' => 'date', 'order' => 'DESC', 'post_status' => array( 'publish', 'private') ) );
                    while ( $the_query->have_posts() ) {
                        $the_query->the_post();
                        fwrite($handle, "\t".'<url>'."\r\n"
                                ."\t\t".'<lastmod>'.get_the_date('Y-m-d').'</lastmod>'."\r\n"
                                ."\t\t".'<loc>'.get_permalink().'</loc>'."\r\n"
                        ."\t".'</url>'."\r\n");
                    }
                }
                
                if ($this->useCategories) {
                    fwrite($handle, "<!-- Categories list -->\r\n");
                    $categories = get_categories();
                    foreach($categories as $category) {
                        fwrite($handle, "\t".'<url>'."\r\n"
                                //."\t\t".'<lastmod>'.get_the_date('Y-m-d').'</lastmod>'."\r\n"
                                ."\t\t".'<loc>'.get_category_link($category->term_id).'</loc>'."\r\n"
                        ."\t".'</url>'."\r\n");
                    }
                }
                
                if ($this->useTags) {
                    fwrite($handle, "<!-- Tags list -->\r\n");
                    $tags = get_tags();
                    foreach ( $tags as $tag ) {
                        fwrite($handle, "\t".'<url>'."\r\n"
                                //."\t\t".'<lastmod>'.get_the_date('Y-m-d').'</lastmod>'."\r\n"
                                ."\t\t".'<loc>'.get_tag_link($tag->term_id).'</loc>'."\r\n"
                        ."\t".'</url>'."\r\n");
                    }
                }
                
                fwrite($handle, '</urlset>');
                fclose($handle);
                $wpdb -> query('UPDATE '.$wpdb->prefix.TABLE_CONFIG.' SET lastGenerated = \''.time().'\'');
            } else {
                echo "File ".$filename." could not be written. Please check your rights";
            }
        }
        
        // Displays the backoffice screen
        echo "<div class='wrap'>";
	echo '<h2>' . __('Xml Sitemap Generator') . '</h2>';
        echo '<p>Xml Sitemap Generator for Wordpress by <a href="http://www.responsive-mind.fr">Renaud Mariage-Gaudron</a>.</p>';
	$this->main_form();
        $this->sitemap_form();
	echo("</div>");
    }
    
    /**
     * Displays the settings section
     */
    function main_form() {
        global $wpdb;
        // Retrieves the settings
        $config = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.TABLE_CONFIG);
        $this->usePosts = $config[0]->usePosts;
        $this->usePages = $config[0]->usePages;
        $this->useCategories = $config[0]->useCategories;
        $this->useTags = $config[0]->useTags;
        // Diplays the settings screen
        ?>
        <h2 style="color: #000;"><?php echo __('Options');?></h2>
        <form method="post">
            <table  width="100%">
                <tr>
                    <td>Add posts to the sitemap</td>
                    <td>
                        <input type="radio" name="usePosts" value="yes" <?php if ($this->usePosts == 1) { echo 'checked="checked"'; }?>> Yes
                        <input type="radio" name="usePosts" value="no" <?php if ($this->usePosts != 1) { echo 'checked="checked"'; }?>> No                        
                    </td>
                </tr>
                <tr>
                    <td>Add pages to the sitemap</td>
                    <td>
                        <input type="radio" name="usePages" value="yes" <?php if ($this->usePages == 1) { echo 'checked="checked"'; }?>> Yes
                        <input type="radio" name="usePages" value="no" <?php if ($this->usePages != 1) { echo 'checked="checked"'; }?>> No                        
                    </td>
                </tr>
                <tr>
                    <td>Add categories to the sitemap</td>
                    <td>
                        <input type="radio" name="useCategories" value="yes" <?php if ($this->useCategories == 1) { echo 'checked="checked"'; }?>> Yes
                        <input type="radio" name="useCategories" value="no" <?php if ($this->useCategories != 1) { echo 'checked="checked"'; }?>> No                        
                    </td>
                </tr>
                <tr>
                    <td>Add tags to the sitemap</td>
                    <td>
                        <input type="radio" name="useTags" value="yes" <?php if ($this->useTags == 1) { echo 'checked="checked"'; }?>> Yes
                        <input type="radio" name="useTags" value="no" <?php if ($this->useTags != 1) { echo 'checked="checked"'; }?>> No                        
                    </td>
                </tr>
            </table>
            <input type="hidden" name="action" value="settings">
            <p class="submit"><input type="submit" name="submit" value="<?php echo __(Update); ?>"></p>
        </form>
        <?php
    }
    
    /**
     * Displays the sitemap generation section
     * @global type $wpdb
     */
    function sitemap_form() {
        
        global $wpdb;
        // Retrieves the settings
        $config = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.TABLE_CONFIG);
        $this->usePosts = $config[0]->usePosts;
        $this->usePages = $config[0]->usePages;
        $this->useCategories = $config[0]->useCategories;
        $this->useTags = $config[0]->useTags;
        $lastGenerated = $config[0]->lastGenerated;
        if ($lastGenerated == 0) {
            ?><strong>No sitemap generated yet !</strong><br>Please click the <em>"Go baby"</em> button to create your sitemap.<?php
        } else {
            ?>
				<p><strong>Sitemap generated on <?php echo date(DATE_RSS, $lastGenerated); ?>.</strong><br>Please click the <em>"Go baby"</em> button to create a new sitemap.</p>
				<p>The sitemap file is located on : <em><a href="<?php echo plugin_dir_url( __FILE__ ); ?>sitemap.xml" target="_blank"><?php echo plugin_dir_url( __FILE__ ); ?>sitemap.xml</a></em></p>
			<?php
        }
        ?>
            <form method="post">
                <input type="hidden" name="action" value="sitemap">
                <p class="submit"><input type="submit" name="submit" value="Go baby"></p>
            </form>
        <?php
    }
    
    /**
     * Creates the needed table in database and populate default settings
     * @global type $wpdb
     */
    function install(){
        global $wpdb;       
        if(!get_option('wp_xml_sitemap_generator_init')){
            $charset_collate = '';
            if ( version_compare(mysql_get_server_info(), '4.1.0', '>=') ) {
                    if (!empty($wpdb->charset)) {
                            $charset_collate .= " DEFAULT CHARACTER SET $wpdb->charset";
                    }
                    if (!empty($wpdb->collate)) {
                            $charset_collate .= " COLLATE $wpdb->collate";
                    }
            }
            // Create settings table     
            $result = $wpdb->query("CREATE TABLE `".$wpdb->prefix.TABLE_CONFIG."` (
                    `xmlSGSettingId` int(1) unsigned NOT NULL auto_increment,
                    `usePosts` int(1) NOT NULL default '1',
                    `usePages` int(1) NOT NULL default '1',
                    `useCategories` int(1) NOT NULL default '1',
                    `useTags` int(1) NOT NULL default '1',
                    `lastGenerated` int(11) NOT NULL default '0',
                    PRIMARY KEY  (`xmlSGSettingId`)
                ) $charset_collate");
            // Check if there's a setting
            $search = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.TABLE_CONFIG);
            if($search == null) {
                // Create default setting
                $result = $wpdb->query('INSERT INTO `'.$wpdb->prefix.TABLE_CONFIG.'` (usePosts, usePages, useCategories, useTags) VALUES (1, 1, 1, 1)');
            }
            $this->mem_option('wp_xml_sitemap_generator_init', true);
        }
    }
    
    /**
     * Creates or updates an option value
     * @param type $name
     * @param type $value
     */
    function mem_option($name, $value='') {
        if(!add_option($name, $value)){
            update_option($name, $value);
        }
    }

    
    
} // end of class


//Use from plugin manager
function wp_xml_sitemap_generator_init() {
	$object = new wp_xml_sitemap_generator;
}
add_action('plugins_loaded','wp_xml_sitemap_generator_init');
?>