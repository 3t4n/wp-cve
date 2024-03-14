<?php

/*
    "Form to Post" Copyright (C) 2014 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This file is part of WordPress Plugin "Form to Post".

    Form to Post is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Form to Post is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

include_once('FormToPost_LifeCycle.php');

class FormToPost_Plugin extends FormToPost_LifeCycle {

    /**
     * See: http://plugin.michael-simpson.com/?page_id=31
     * @return array of option meta data.
     */
    public function getOptionMetaData() {
        //  http://plugin.michael-simpson.com/?page_id=31
        return array(//'_version' => array('Installed Version'), // Leave this one commented-out. Uncomment to test upgrades.
        );
    }

    public function getPluginDisplayName() {
        return 'Form to Post';
    }

    protected function getMainPluginFileName() {
        return 'form-to-post.php';
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Called by install() to create any database tables if needed.
     * Best Practice:
     * (1) Prefix all table names with $wpdb->prefix
     * (2) make table names lower case only
     * @return void
     */
    protected function installDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("CREATE TABLE IF NOT EXISTS `$tableName` (
        //            `id` INTEGER NOT NULL");
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Drop plugin-created tables on uninstall.
     * @return void
     */
    protected function unInstallDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("DROP TABLE IF EXISTS `$tableName`");
    }


    /**
     * Perform actions when upgrading from version X to version Y
     * See: http://plugin.michael-simpson.com/?page_id=35
     * @return void
     */
    public function upgrade() {
    }


    public function addActionsAndFilters() {

        // Add options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        //add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));
        add_action('wpcf7_before_send_mail', array(&$this, 'saveCF7FormToPost'));
        add_action('fsctf_mail_sent', array(&$this, 'saveFormToPost'));
        add_action('cfdb_submit', array(&$this, 'saveFormToPost'));

        // Example adding a script & style just for the options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        //        if (strpos($_SERVER['REQUEST_URI'], $this->getSettingsSlug()) !== false) {
        //            wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));
        //            wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        }


        // Add Actions & Filters
        // http://plugin.michael-simpson.com/?page_id=37


        // Adding scripts & styles to all pages
        // Examples:
        //        wp_enqueue_script('jquery');
        //        wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));


        // Register short codes
        // http://plugin.michael-simpson.com/?page_id=39
        // Shortcode to save data from non-CF7/FSCF forms
        require_once('FormToPost_CapturePostDataShortCode.php');
        $sc = new FormToPost_CapturePostDataShortCode();
        $sc->register('capture-form-to-post');


        // Register AJAX hooks
        // http://plugin.michael-simpson.com/?page_id=41
    }

    /**
     * Callback from Contact Form 7.
     * @param $cf7 WPCF7_ContactForm
     * @return bool
     */
    public function saveCF7FormToPost($cf7) {
        if (!isset($cf7->posted_data) && class_exists('WPCF7_Submission')) {
            // Contact Form 7 version 3.9 removed $cf7->posted_data and now
            // we have to retrieve it from an API
            $submission = WPCF7_Submission::get_instance();
            if ($submission) {
                $data = array();
                $data['title'] = $cf7->title();
                $data['posted_data'] = $submission->get_posted_data();
                $data['uploaded_files'] = $submission->uploaded_files();
                $this->saveFormToPost((object)$data);
            }
        } else {
            $this->saveFormToPost($cf7);
        }
        return true;
    }


    /**
     * Callback from Contact Form 7. CF7 passes an object with the posted data which is inserted into the database
     * by this function.
     * Also callback from Fast Secure Contact Form
     * @param $cf7 WPCF7_ContactForm|object the former when coming from CF7, the latter $fsctf_posted_data object variable
     * if coming from FSCF
     * @return bool
     */
    public function saveFormToPost($cf7) {

        $newCf7 = apply_filters('form_to_post_form_data', $cf7);
        if ($newCf7) {
            $cf7 = $newCf7;
        }

        if (!isset($cf7->posted_data) ||
                !isset($cf7->posted_data['post_title']) ||
                !isset($cf7->posted_data['post_content'])
        ) {
            // not a form submission intended to be made into a post
            return true;
        }


        $post = array(
                'post_status' => 'publish',
                'post_type' => 'post',
                'post_category' => array(0)
        );

        global $user_ID;
        if (!isset($user_ID)) {
            $user_ID = 0; // non logged in
        }
        if ($user_ID != 0) {
            $post['post_author'] = $user_ID;
        }


        // See http://codex.wordpress.org/Function_Reference/wp_insert_post
        $simpleFields = array(
                'ID', // => [ <post id> ] //Are you updating an existing post?
                'menu_order', // => [ <order> ] //If new post is a page, sets the order should it appear in the tabs.
                'comment_status', // => [ 'closed' | 'open' ] // 'closed' means no comments.
                'ping_status', // => [ 'closed' | 'open' ] // 'closed' means pingbacks or trackbacks turned off
                'pinged', // => [ ? ] //?
                'post_author', // => [ <user ID> ] //The user ID number of the author.
                'post_content', // => [ <the text of the post> ] //The full text of the post.
                'post_date', // => [ Y-m-d H:i:s ] //The time post was made.
                'post_date_gmt', // => [ Y-m-d H:i:s ] //The time post was made, in GMT.
                'post_excerpt', // => [ <an excerpt> ] //For all your post excerpt needs.
                'post_name', // => [ <the name> ] // The name (slug) for your post
                'post_parent', // => [ <post ID> ] //Sets the parent of the new post.
                'post_password', // => [ ? ] //password for post?
                'post_status', // => [ 'draft' | 'publish' | 'pending'| 'future' | 'private' ] //Set the status of the new post.
                'post_title', // => [ <the title> ] //The title of your post.
                'post_type', // => [ 'post' | 'page' | 'link' | 'nav_menu_item' | custom post type ] //You may want to insert a regular post, page, link, a menu item or some custom post type
                'tags_input', // => [ '<tag>, <tag>, <...>' ] //For tags.
                'to_ping', // => [ ? ] //?
        );

        if (!isset($post['post_date_gmt']) && !isset($post['post_date'])) {
            $post['post_date_gmt'] = date('Y-m-d H:i:s');
        }

        foreach ($cf7->posted_data as $key => $val) {
            if (is_array($val) && $key != 'post_category' && $key != 'post_category_name') {
                // Radio and check boxes values are in arrays
                // Convert array values into a string
                $cf7->posted_data[$key] = implode(',', $val);
            }
        }

        // Add in any additional fields
        foreach ($simpleFields as $field) {
            if (isset($cf7->posted_data[$field])) {
                $post[$field] = stripslashes($cf7->posted_data[$field]);
            }
        }

        // 'post_category', // => [ array(<category id>, <...>) ] //Add some categories.
        if (isset($cf7->posted_data['post_category'])) {
            if (is_array($cf7->posted_data['post_category'])) {
                $post['post_category'] = $cf7->posted_data['post_category'];
            } else {
                $post['post_category'] = preg_split('/, /', stripslashes($cf7->posted_data['post_category']));
            }
        }

        // But really nobody is going to use category ids, so
        if (isset($cf7->posted_data['post_category_name'])) {
            $categoryNames = null;
            if (is_array($cf7->posted_data['post_category_name'])) {
                $categoryNames = $cf7->posted_data['post_category_name'];
            } else {
                $categoryNames = preg_split('/, /', stripslashes($cf7->posted_data['post_category_name']));
            }
            $categoryIds = array();
            foreach ($categoryNames as $catName) {
                $idObj = get_category_by_slug($catName);
                $categoryIds[] = $idObj->term_id;
            }
            $post['post_category'] = $categoryIds;
        }

        // Alternative to using post_author=<user id>
        if (isset($cf7->posted_data['post_author_name']) && $cf7->posted_data['post_author_name']) {
            $authorUser = get_user_by('login', $cf7->posted_data['post_author_name']);
            if ($authorUser && isset($authorUser->ID)) {
                $post['post_author'] = $authorUser->ID;
            }
        }

        // If user is logged in, pick up his login id as the author
        if (!isset($post['post_author'])) {
            $current_user = wp_get_current_user();
            if ($current_user->ID) {
                // logged in
                $post['post_author'] = $current_user->ID;
            }
        }

        // Allow for a default author to be set if not logged in or otherwise specified
        if (!isset($post['post_author']) &&
                isset($cf7->posted_data['post_author_default']) && $cf7->posted_data['post_author_default']
        ) {
            $authorUser = get_user_by('login', $cf7->posted_data['post_author_default']);
            if ($authorUser && isset($authorUser->ID)) {
                $post['post_author'] = $authorUser->ID;
            }
        }


        // NOTE: 'tax_input' not supported
        // 'tax_input' // => [ array( 'taxonomy_name' => array( 'term', 'term2', 'term3' ) ) ] // support for custom taxonomies.


        $new_post = apply_filters('form_to_post_before_create_post', $post);
        if ($new_post && is_array($new_post)) {
            $post = $new_post;
        }

        // Create the post
        //error_log(print_r($post, true)); // debug
        $post_id = wp_insert_post($post);


        // Allow setting the page template
        if ($post_id && isset($cf7->posted_data['page_template'])) {
            update_post_meta($post_id, '_wp_page_template', $cf7->posted_data['page_template']);
        }

        // Gather Meta fields for setting custom fields
        $meta_fields = array();
        foreach ($cf7->posted_data as $key => $val) {
            if (strpos($key, 'meta_') === 0) {
                $metaKey = substr($key, 5);
                $meta_fields[$metaKey] = $val;
            }
        }

        // Allow setting meta values on the server side
        $new_meta = apply_filters('form_to_post_before_update_post_meta', $meta_fields);
        if ($new_meta && is_array($new_meta)) {
            $meta_fields = $new_meta;
        }

        // Set Meta tags (Custom fields)
        foreach ($meta_fields as $metaKey => $val) {
            update_post_meta($post_id, $metaKey, $val);
            //error_log("$metaKey => $val"); // debug
        }

        return true;
    }
}
