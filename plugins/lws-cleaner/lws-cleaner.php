<?php

/**
 * Plugin Name:       LWS Cleaner
 * Plugin URI:        https://www.lws.fr/
 * Description:       With LWS Cleaner, clean your website, it's fast and easy. Clean your posts, comments, terms, users or even unused medias with this plugin.
 * Version:           2.4
 * Author:            LWS
 * Author URI:        https://www.lws.fr
 * Tested up to:      6.2
 * Domain Path:       /languages
 *
 * @since             1.0
 * @package           lws-cleaner
*/

if (! defined('ABSPATH')) {
    exit; //Exit if accessed directly
}

/**
 * Load translations
 */
add_action('init', 'lws_cl_traduction');
function lws_cl_traduction()
{
    define('LWS_CL_URL', plugin_dir_url(__FILE__));
    define('LWS_CL_DIR', plugin_dir_path(__FILE__));
    load_plugin_textdomain('lws-cleaner', false, dirname(plugin_basename(__FILE__)) . '/languages');

    // By keithgreer ; https://keithgreer.uk/wordpress-code-completely-disable-comments-using-functions-php
    if (get_option('lws_cl_deactivate_comments')) {
        add_action('admin_init', 'lws_cl_disable_comments_post_types_support');
        add_filter('comments_open', 'lws_cl_disable_comments_status', 20, 2);
        add_filter('pings_open', 'lws_cl_disable_comments_status', 20, 2);
    }
    if (get_option('lws_cl_hide_comments')) {
        add_filter('comments_array', 'lws_cl_disable_comments_hide_existing_comments', 10, 2);
    }
}

/**
 * Enqueue any CSS or JS script needed
 */
add_action('admin_enqueue_scripts', 'lws_cl_scripts');
function lws_cl_scripts()
{
    if (get_current_screen()->base == ('toplevel_page_lws-cl-config')) {
        wp_enqueue_style('lws_cl_css', LWS_CL_URL . "css/lws_cl_style.css");
        wp_enqueue_style('lws_cl-Poppins', 'https://fonts.googleapis.com/css?family=Poppins');
    }
    else{
        wp_enqueue_style('lws_cl_css_out', LWS_CL_URL . "css/lws_cl_style_out.css");
        if (!get_transient('lwscleaner_remind_me') && !get_option('lwscleaner_do_not_ask_again')){
            add_action( 'admin_notices', 'lwscl_review_ad_plugin' );
        }
    }
}

register_activation_hook(__FILE__, 'lws_cl_on_activation');
function lws_cl_on_activation()
{
    global $wpdb;
    include_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
    include_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';

    set_transient('lwscleaner_remind_me', 1296000);

    $lws_cl_table_name = $wpdb->prefix . 'lws_cl_ignore';

    $charset_collate = $wpdb->get_charset_collate();

    $wpdb->query("CREATE TABLE IF NOT EXISTS $lws_cl_table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    IDmedia mediumint(9),
    PRIMARY KEY  (id)
    ) $charset_collate;");
}

register_uninstall_hook(__FILE__, 'lws_cl_on_delete');
function lws_cl_on_delete()
{
    global $wpdb;
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}lws_cl_ignore");
    delete_option("my_plugin_db_version");
}

// First, this will disable support for comments and trackbacks in post types
function lws_cl_disable_comments_post_types_support()
{
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}

// Then close any comments open comments on the front-end just in case
function lws_cl_disable_comments_status()
{
    return false;
}


// Finally, hide any existing comments that are on the site.
function lws_cl_disable_comments_hide_existing_comments($comments)
{
    $comments = array();
    return $comments;
}
//


function lwscl_review_ad_plugin(){
    ?>
    <script>
        function lws_cl_remind_me(){
            var data = {                
                _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('reminder_for_cleaner')); ?>',        
                action: "lws_cleaner_reminder_ajax",
                data: true,
            };
            jQuery.post(ajaxurl, data, function(response){
                jQuery("#lws_cl_review_notice").addClass("animationFadeOut");
                setTimeout(() => {
                    jQuery("#lws_cl_review_notice").addClass("lws_hidden");
                }, 800);            
            });

        }

        function lws_cl_do_not_bother_me(){
            var data = {           
                _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('donotask_for_cleaner')); ?>',        
                action: "lws_cleaner_donotask_ajax",
                data: true,
            };
            jQuery.post(ajaxurl, data, function(response){
                jQuery("#lws_cl_review_notice").addClass("animationFadeOut");     
                setTimeout(() => {
                    jQuery("#lws_cl_review_notice").addClass("lws_hidden");
                }, 800);
            });            
        }
    </script>

    <div class="notice notice-info is-dismissible lwscl_review_block_general" id="lws_cl_review_notice">
        <div class="lws_cl_circle">
            <img class="lwscl_review_block_image" src="<?php echo esc_url(plugins_url('images/plugin_lws-cleaner.svg', __FILE__))?>" width="40px" height="40px">
        </div>
        <div style="padding:16px">
            <h1 class="lwscl_review_block_title"> <?php esc_html_e('Thank you for using LWS Cleaner!', 'lws-cleaner'); ?></h1>
            <p class="lwscl_review_block_desc"><?php _e('Evaluate our plugin to help others clean their WordPress website!', 'lws-cleaner' ); ?></p>
            <a class="lwscl_button_rate_plugin" href="https://wordpress.org/support/plugin/lws-cleaner/reviews/" target="_blank" ><img style="margin-right: 8px;" src="<?php echo esc_url(plugins_url('images/noter.svg', __FILE__))?>" width="15px" height="15px"><?php esc_html_e('Rate', 'lws-cleaner'); ?></a>
            <a class="lwscl_review_button_secondary" onclick="lws_cl_remind_me()"><?php esc_html_e('Remind me later', 'lws-cleaner'); ?></a>
            <a class="lwscl_review_button_secondary" onclick="lws_cl_do_not_bother_me()"><?php esc_html_e('Do not ask again', 'lws-cleaner'); ?></a>
        </div>
    </div>
    <?php
}

/**
 * Create plugin menu in wp-admin
 */
add_action('admin_menu', 'lws_cl_menu_admin');
function lws_cl_menu_admin()
{
    $menu_slug = 'lws-cl-config';
    add_menu_page(__('LWS Cleaner', 'lws-cleaner'), 'LWS Cleaner', 'manage_options', $menu_slug, 'lws_cl_page', LWS_CL_URL . 'images/plugin_lws_cleaner.svg');
}

function lws_cl_page()
{
    global $wpdb;

    $tabs_list = array(
        array('posts', __('Posts', 'lws-cleaner')),
        array('comments', __('Comments', 'lws-cleaner')),
        array('terms', __('Terms', 'lws-cleaner')),
        array('users', __('Users', 'lws-cleaner')),
        array('settings', __('Settings', 'lws-cleaner')),
        array('pluginsandthemes', __('Plugins/Themes', 'lws-cleaner')),
        // array('medias', __('Medias', 'lws-cleaner')),
        array('files', __('Files', 'lws-cleaner')),
        array('plugins', __('Our plugins', 'lws-cleaner')),
    );

    $first_tabs = array(
        array('posts', __('Posts', 'lws-cleaner')),
        array('comments', __('Comments', 'lws-cleaner')),
        array('terms', __('Terms', 'lws-cleaner')),
        array('users', __('Users', 'lws-cleaner')),
        array('settings', __('Settings', 'lws-cleaner')),
        array('pluginsandthemes', __('Plugins/Themes', 'lws-cleaner')),
    );

    $bottom_thumb_key = array(
        'trash',
        'trash_comments',
        'plugins',
        'themes'
    );

    $button_is_blue = array(
        'oembed_posts',
        'deactivate_comments',
        'hide_comments',
        'transients',
        'crons'
    );


    //POSTS//

    $revision_number = $wpdb->query("SELECT * FROM $wpdb->posts WHERE post_type='revision'");
    $draft_number = $wpdb->query("SELECT * FROM $wpdb->posts WHERE post_status='auto-draft'");
    $trash_number = $wpdb->query("SELECT * FROM $wpdb->posts WHERE post_status='trash'");
    $orphan_number = $wpdb->query("SELECT * FROM $wpdb->postmeta WHERE post_id NOT IN(SELECT ID FROM $wpdb->posts)");
    $oembed_number = $wpdb->query("SELECT * FROM $wpdb->postmeta WHERE meta_key LIKE('%_oembed_%')");
    $duplicate_number = $wpdb->query("SELECT GROUP_CONCAT(meta_id ORDER BY meta_id DESC) AS ids, post_id, COUNT(*) AS count FROM $wpdb->postmeta 
    GROUP BY post_id, meta_key, meta_value HAVING count > 1");

    $posts_lists = array(
        'revision_posts' => array(
            __('<strong>%d revision(s)</strong>', 'lws-cleaner'),
            __('No <strong>revisions</strong>', 'lws-cleaner'),
            __('Delete revisions (%d)', 'lws-cleaner'),
            __('No revisions', 'lws-cleaner'),
            $revision_number,
            __('Revisions are older versions of your posts. Useful for backups, revisions nonetheless congest the database', 'lws-cleaner'),
        ),

        'auto_draft_posts' => array(
            __('<strong>%d auto-draft(s)</strong>', 'lws-cleaner'),
            __('No <strong>auto-drafts</strong>', 'lws-cleaner'),
            __('Delete drafts (%d)', 'lws-cleaner'),
            __('No auto-drafts', 'lws-cleaner'),
            $draft_number,
            __('Auto-drafts are saves of your posts while you are editing them. If you do not publish your post, it will be saved as an auto-draft. Over time, those drafts will accumulate and take useless space in your database', 'lws-cleaner'),
        ),

        'trash_posts' => array(
            __('<strong>%d post(s)</strong> in the trash', 'lws-cleaner'),
            __('No <strong>posts</strong> in the trash', 'lws-cleaner'),
            __('Delete posts (%d)', 'lws-cleaner'),
            __('No posts', 'lws-cleaner'),
            $trash_number,
            __('When deleting a post, those are not completely deleted and are instead put in the trash. By default, the trash is emptied every 30 days which is fine in most cases but you may want to empty it manually to gain space and delete those post for real', 'lws-cleaner'),
        ),

        'orphan_posts' => array(
            __('<strong>%d orphaned metadata</strong> in posts', 'lws-cleaner'),
            __('No orphaned <strong>metadata</strong> in posts', 'lws-cleaner'),
            __('Delete metadata (%d)', 'lws-cleaner'),
            __('No metadata', 'lws-cleaner'),
            $orphan_number,
            __('Metadata are informations about the post provided to your vistors. In some cases, metadata can be orphaned, meaning those do not link to any post and are just a waste of space. It is recommended to delete those', 'lws-cleaner'),
        ),
        
        'duplicate_posts' => array(
            __('<strong>%d duplicated metadata</strong> in posts', 'lws-cleaner'),
            __('No duplicated <strong>metadata</strong> in posts', 'lws-cleaner'),
            __('Delete metadata (%d)', 'lws-cleaner'),
            __('No metadata', 'lws-cleaner'),
            $duplicate_number,
            __('Metadata are informations about the post provided to your vistors. In some cases, metadata can get duplicated, which means there is a least 2 copies of one metadata. not just to your performances, it can be detrimental for your SEO and should be removed', 'lws-cleaner'),
        ),

        'oembed_posts' => array(
            __('Delete the oEmbed cache of posts metadata on your website', 'lws-cleaner'),
            __('Delete the oEmbed cache of posts metadata on your website', 'lws-cleaner'),
            __('Delete caches (%d)', 'lws-cleaner'),
            __('No cache', 'lws-cleaner'),
            $oembed_number,
            __('WordPress uses embed code for various external content such as videos or external pages (Youtube, Facebook posts...). In some cases, this code can be broken or you might want to regenerate it for various reasons. You can do so from here', 'lws-cleaner'),
        ),
    );

    //COMMENTS//

    $unapproved_number = $wpdb->query("SELECT * FROM $wpdb->comments WHERE comment_approved='0'");
    $spam_number = $wpdb->query("SELECT * FROM $wpdb->comments WHERE comment_approved='spam'");
    $trashed_number = $wpdb->query("SELECT * FROM $wpdb->comments WHERE comment_approved='trash'");
    $orphan_comments_number = $wpdb->query("SELECT * FROM $wpdb->commentmeta WHERE comment_id NOT IN(SELECT comment_ID FROM $wpdb->comments)");
    $duplicate_comments_number = $wpdb->query("SELECT GROUP_CONCAT(meta_id ORDER BY meta_id DESC) 
    AS ids, comment_id, COUNT(*) AS count FROM $wpdb->commentmeta GROUP BY comment_id, meta_key, meta_value HAVING count > 1");

    $comments_lists = array(
        'approved_comments' => array(
            __('<strong>%d</strong> unapproved <strong>comment(s)</strong>', 'lws-cleaner'),
            __('No unapproved <strong>comments</strong>', 'lws-cleaner'),
            __('Delete comments (%d)', 'lws-cleaner'),
            __('No comments', 'lws-cleaner'),
            $unapproved_number,
            __('Comments that you did not validate and are waiting moderation are called "Unapproved comments". Be careful if you decide to delete those, unapproved comments could be spam as well as legitimate comments, and this plugin will not make any distinction', 'lws-cleaner'),
        ),
        'spam_comments' => array(
            __('<strong>%d</strong> spam <strong>comment(s)</strong>', 'lws-cleaner'),
            __('No spam <strong>comments</strong>', 'lws-cleaner'),
            __('Delete comments (%d)', 'lws-cleaner'),
            __('No comments', 'lws-cleaner'),
            $spam_number,
            __('Comments qualified as "Spam" are comments that you or a plugin categorized as useless, harmful, innapropriate, ads... In most cases, those comments can be deleted with no worries and free some space', 'lws-cleaner'),
        ),
        'trash_comments' => array(
            __('<strong>%d</strong> trashed <strong>comment(s)</strong>', 'lws-cleaner'),
            __('No trashed <strong>comments</strong>', 'lws-cleaner'),
            __('Delete comments (%d)', 'lws-cleaner'),
            __('No comments', 'lws-cleaner'),
            $trashed_number,
            __('Trashed comments are comments that have been put in the trashcan, are deleted. You can generally delete them without worries; it is recommended to do so if those accumulate and take up space', 'lws-cleaner'),
        ),
        'orphan_comments' => array(
            __('<strong>%d</strong> orphaned comments <strong>metadata</strong>', 'lws-cleaner'),
            __('No orphaned comments <strong>metadata</strong>', 'lws-cleaner'),
            __('Delete metadata (%d)', 'lws-cleaner'),
            __('No metadata', 'lws-cleaner'),
            $orphan_comments_number,
            __('Metadata can become orphaned when the comment linked to this data is deleted. Generally, those metadata are useless and should be deleted to free up space', 'lws-cleaner'),
        ),
        'duplicate_comments' => array(
            __('<strong>%d</strong> duplicated comments <strong>metadata</strong>', 'lws-cleaner'),
            __('No duplicated comments <strong>metadata</strong>', 'lws-cleaner'),
            __('Delete metadata (%d)', 'lws-cleaner'),
            __('No metadata', 'lws-cleaner'),
            $duplicate_comments_number,
            __('Metadata can sometimes get duplicated, meaning there exist at least 2 instances of one metadata. The duplicated metadata is useless and should be deleted to free up space', 'lws-cleaner'),
        ),
        'deactivate_comments' => array(
            __('<strong>Deactivate</strong> comments on your website', 'lws-cleaner'),
            __('', 'lws-cleaner'),
            __('Deactivate comments', 'lws-cleaner'),
            __('', 'lws-cleaner'),
            1,
            __('This option will completely deactivate comments on all pages and posts of this website. Already posted comments will still appear, but you will not be able to submit new ones. Not recommended for blogs or shops, for example, but may be useful for showcase sites', 'lws-cleaner'),
        ),
        'hide_comments' => array(
            __('<strong>Hide</strong> comments on your website', 'lws-cleaner'),
            __('', 'lws-cleaner'),
            __('Hide comments', 'lws-cleaner'),
            __('', 'lws-cleaner'),
            1,
            __('This option will hide every comments on your website. Visitors will still be able to submit comments but will not be able to see any, be it theirs or others', 'lws-cleaner'),
        ),
    );

    //TERMS//

    $unused_terms = $wpdb->query("SELECT * FROM $wpdb->terms a INNER JOIN $wpdb->term_taxonomy b ON a.term_id = b.term_id WHERE b.count = 0");
    $orphan_terms = $wpdb->query("SELECT * FROM $wpdb->termmeta WHERE term_id NOT IN(SELECT term_id FROM $wpdb->terms)");
    $duplicate_terms = $wpdb->query("SELECT GROUP_CONCAT(meta_id ORDER BY meta_id DESC) AS ids, term_id, COUNT(*) AS count FROM $wpdb->termmeta GROUP BY term_id, meta_key, meta_value HAVING count > 1");
    $orphan_relationship_terms = $wpdb->query("SELECT * FROM $wpdb->term_relationships WHERE term_taxonomy_id NOT IN(SELECT term_taxonomy_id FROM $wpdb->term_taxonomy)");

    $terms_lists = array(
        'unused_terms' => array(
            __('<strong>%d unused term(s)</strong>', 'lws-cleaner'),
            __('No unused <strong>terms</strong>', 'lws-cleaner'),
            __('Delete terms (%d)', 'lws-cleaner'),
            __('No terms', 'lws-cleaner'),
            $unused_terms,
            __("Terms are items in a taxonomy (e.g. \"Fish\" and \"Cat\" are terms of the taxonomy \"Animals\"). Some terms that WordPress or yourself have created might not be used anymore and are then uselessly taking space. If you are sure that every unused terms are truly unused, you can delete those", 'lws-cleaner'),
        ),

        'orphan_terms' => array(
            __('<strong>%d</strong> orphaned terms <strong>metadata</strong>', 'lws-cleaner'),
            __('No orphaned terms <strong>metadata</strong>', 'lws-cleaner'),
            __('Delete metadata (%d)', 'lws-cleaner'),
            __('No metadata', 'lws-cleaner'),
            $orphan_terms,
            __('Metadata can become orphaned when the terms linked to this data is deleted. Generally, those metadata are useless and should be deleted to free up space', 'lws-cleaner'),
        ),
    
        'duplicate_terms' => array(
            __('<strong>%d duplicated terms metadata(s)</strong>', 'lws-cleaner'),
            __('No duplicated terms <strong>metadata</strong>', 'lws-cleaner'),
            __('Delete metadata (%d)', 'lws-cleaner'),
            __('No metadata', 'lws-cleaner'),
            $duplicate_terms,
            __('Terms metadata can sometimes get duplicated, meaning there exist at least 2 instances of one metadata. It can happen for different reasons but the duplicated metadata is useless and should be deleted to free up space', 'lws-cleaner'),
        ),

        'orphan_relationship_terms' => array(
            __('<strong>%d</strong> orphaned terms <strong>relationship(s)</strong>', 'lws-cleaner'),
            __('No orphaned terms <strong>relationships</strong>', 'lws-cleaner'),
            __('Delete relationships (%d)', 'lws-cleaner'),
            __('No relationships', 'lws-cleaner'),
            $orphan_relationship_terms,
            __('When you create a post and add some terms to it, relationships are created between the post and the terms. If you delete said post, then the relationship will not be deleted and will stay in the database. If you delete a lot of post, you may have bloated your datatable of relationship, reducing performances. Orphaned relationship are useless and should be deleted', 'lws-cleaner'),
        ),
    );

    //USERS//

    $orphan_users_number = $wpdb->query("SELECT * FROM $wpdb->usermeta WHERE user_id NOT IN(SELECT ID FROM $wpdb->users)");
    $duplicate_users_number = $wpdb->query("SELECT GROUP_CONCAT(umeta_id ORDER BY umeta_id DESC) AS ids, user_id, COUNT(*) AS count FROM $wpdb->usermeta GROUP BY user_id, meta_key, meta_value HAVING count > 1");

    $users_lists = array(
        'duplicate_user' => array(
            __('<strong>%d</strong> duplicated users <strong>metadata</strong>', 'lws-cleaner'),
            __('No duplicated users <strong>metadata</strong>', 'lws-cleaner'),
            __('Delete metadata (%d)', 'lws-cleaner'),
            __('No metadata', 'lws-cleaner'),
            $duplicate_users_number,
        __('Users metadata are all sorts of information on you: Name, surname, last connexion, nickname... You generally have no reasons to modify those directly but sometimes metadata can get duplicated, meaning you have multiple times a same data. It can cause performance issues and take useless space on the server. It is recommended to delete those', 'lws-cleaner')),

        'orphan_user_data' => array(
            __('<strong>%d</strong> orphaned users <strong>metadata</strong>', 'lws-cleaner'),
            __('No orphaned users <strong>metadata</strong>', 'lws-cleaner'),
            __('Delete metadata (%d)', 'lws-cleaner'),
            __('No metadata', 'lws-cleaner'),
            $orphan_users_number,
            __('When users are deleted from your website, their metadata may not be completely deleted and will stay in your database, unused and taking up space for nothing. It is recommended to delete those', 'lws-cleaner')
        ),
    );

    //TRANSIENTS//

    $transient_number = $wpdb->query("SELECT * FROM $wpdb->options WHERE option_name LIKE '%_transient_%';");

    $settings_lists = array(
        'transients' => array(
            __('<strong>%d transients</strong>', 'lws-cleaner'),
            __('No <strong>transients</strong>', 'lws-cleaner'),
            __('Delete transients (%d)', 'lws-cleaner'),
            __('No transients', 'lws-cleaner'),
            $transient_number,
            __('Transients are data saved in database for a defined period of time, similarly to a cache. Once expired, those transients disappear from the database but it is not always the case, and you can quickly end up with tens or hundreds of those, bloating your database. When deleted, useful transients will get recreated so there is no danger of breaking the website', 'lws-cleaner'),
        ),

        'crons' => array(
            __('Delete every crons on your website', 'lws-cleaner'),
            __('', 'lws-cleaner'),
            __('Delete all crons', 'lws-cleaner'),
            __('', 'lws-cleaner'),
            1,
            __('Crons are automated tasks created and maintained by WordPress. Every interval of time (defined at creation time), the task execute its code. The only way to stop a cron is by destroying it or deactivating it but sometimes you cannot manually do it. In those cases, you can delete every crons here. Useful crons will get recreated immediately, there is no danger to your website', 'lws-cleaner'),
        ),
    );

    //PLUGINS AND THEMES//

    //Get every unused plugins and the number of used plugins
    $unused_plugins= array();
    foreach(get_plugins() as $slug => $plugin) {
        if (!is_plugin_active($slug) && !is_plugin_active_for_network($slug)) {
            $unused_plugins[] = array('name' => $plugin['Name'], 'author' => $plugin['AuthorName'], 'version' => $plugin['Version'], 'slug' => $plugin['TextDomain'], 'package' => $slug);
        }
    }
    
    $unused_themes = array();
    $all_themes = wp_get_themes();
    $my_theme = wp_get_theme();
    $count_inactive_themes = count($all_themes) - 1;

    //Get every unused themes
    foreach($all_themes as $slug => $theme) {
        if ($theme['Name'] != $my_theme->name) {
            $unused_themes[] = array('name' => $theme['Name'], 'author' => $theme['Author'], 'version' => $theme['Version'], 'slug' => $slug);
        }
    }

    $pandt_lists = array(
        'plugins' => array(
            __('<strong>%d</strong> unused <strong>plugins</strong>', 'lws-cleaner'),
            __('No unused <strong>plugins</strong>', 'lws-cleaner'),
            __('Delete plugins (%d)', 'lws-cleaner'),
            __('No plugins', 'lws-cleaner'),
            count($unused_plugins),
            __('Unused plugins are generally not a problem but in case of a hack of your website, those might be used to install and execute cede without your knowledge. It is recommended to delete all but the plugins you use often', 'lws-cleaner'),
        ),

        'themes' => array(
            __('<strong>%d</strong> unused <strong>themes</strong>', 'lws-cleaner'),
            __('No unused <strong>themes</strong>', 'lws-cleaner'),
            __('Delete themes (%d)', 'lws-cleaner'),
            __('No themes', 'lws-cleaner'),
            $count_inactive_themes,
            __('As you can only have one active theme on your website, there is no purpose in having multiple themes, as it could create security breach that a hacker could use. It is recommended to delete unused themes', 'lws-cleaner'),
        ),
    );


    // //
    $lws_content_6_pages = array(
        'pluginsandthemes' => $pandt_lists,
        'settings' => $settings_lists,
        'users' => $users_lists,
        'terms' => $terms_lists,
        'comments' => $comments_lists,
        'posts' => $posts_lists,
    );
    // //

    //MEDIA//

    // if (isset($_POST['lws_cl_ignore_attachment']) || isset($_POST['lws_cl_unignore_attachment']) || isset($_POST['lws_cl_delete_attachment'])){
    //     $change_tab = "nav-medias";
    // }

    // $lws_table = $wpdb->prefix . 'lws_cl_ignore';
    // $ignored = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}lws_cl_ignore");
    // $array_ignored = array();
    // foreach($ignored as $ignore) {
    //     if (!empty($wpdb->get_results("SELECT * FROM $wpdb->posts WHERE ID = {$ignore->IDmedia}"))) {
    //         $author = $wpdb->get_results("SELECT display_name FROM $wpdb->users WHERE ID = (SELECT post_author FROM $wpdb->posts WHERE ID = $ignore->IDmedia)");
    //         if (!empty($author)) {
    //             $author = $author[0]->display_name;
    //         } else {
    //             $author = __('Author', 'lws-cleaner');
    //         }
    //         $post = $wpdb->get_results("SELECT post_title, post_date FROM $wpdb->posts WHERE ID = $ignore->IDmedia")[0];
    //         $array_ignored[] =  array(
    //             'ID' => $ignore->IDmedia,
    //             'link' => "https://stagiaire-wordpress.site/WordPress/wp-admin/upload.php?item=" . $ignore->IDmedia,
    //             'image' => wp_get_attachment_image($ignore->IDmedia, array('90', '90'), true),
    //             'file_name_ignored' => esc_attr($post->post_title),
    //             'author' => esc_attr($author),
    //             'date' => esc_attr($post->post_date),
    //         );
    //     }
    // }

    // $ids = $wpdb->get_results(
    //     "SELECT * FROM $wpdb->posts i
    //         WHERE i.post_type = 'attachment'
    //         AND i.post_parent = 0
    //         AND NOT EXISTS (SELECT * FROM $wpdb->posts p WHERE p.ID = i.post_parent)
    //         AND NOT EXISTS (SELECT * FROM $wpdb->postmeta pm WHERE pm.meta_key = '_thumbnail_id' AND pm.meta_value = i.ID)
    //         AND NOT EXISTS (SELECT * FROM $wpdb->postmeta pm WHERE pm.meta_key = '_product_image_gallery' AND pm.meta_value LIKE CONCAT('%', i.ID,'%'))
    //         AND NOT EXISTS (SELECT * FROM $wpdb->posts p WHERE p.post_type <> 'attachment' AND p.post_content LIKE CONCAT('%', REVERSE(SUBSTRING(REVERSE(i.guid), LOCATE('.', REVERSE(i.guid)) + 1, LENGTH(i.guid))),'%'))
    //         AND NOT EXISTS (SELECT * FROM $wpdb->postmeta pm WHERE pm.meta_value LIKE CONCAT('%', i.guid,'%'))
    //         AND NOT EXISTS (SELECT * FROM $lws_table c WHERE c.IDmedia = i.ID )"
    // );
    // $array = array();
    // foreach ($ids as $id) {
    //     $author = $wpdb->get_results("SELECT display_name FROM $wpdb->users WHERE ID = $id->post_author");
    //     if (!empty($author)) {
    //         $author = $author[0]->display_name;
    //     } else {
    //         $author = __('Author', 'lws-cleaner');
    //     }
    //     $array[] =  array(
    //         'ID' => $id->ID,
    //         'link' => "https://stagiaire-wordpress.site/WordPress/wp-admin/upload.php?item=" . $id->ID,
    //         'image' => wp_get_attachment_image($id->ID, array('90', '90'), true),
    //         'file_name' => esc_attr($id->post_title),
    //         'author' => esc_attr($author),
    //         'date' => esc_attr($id->post_date),
    //     );
    // }
    
    // $table_ignored = new LwsCL_MediaList_Ignored();
    // $table_ignored->items = $array_ignored;
    

    // $table = new LwsCL_MediaList();
    // $table->items = $array;


    //FILES//
    
    include __DIR__ . '/views/lws_cl_tabs.php';
}

// AJAX Related to Files //

function lws_cl_sort_files($a, $b)
{
    if (key($a) == 'dir') {
        return -1;
    } else {
        return 1;
    }
}

function folderSize($dir)
{
    $count_size = 0;
    $count = 0;
    $dir_array = scandir($dir);
    foreach($dir_array as $key=>$filename) {
        if($filename!=".." && $filename!=".") {
            if(is_dir($dir."/".$filename)) {
                $new_foldersize = folderSize($dir."/".$filename);
                $count_size = $count_size+ $new_foldersize;
            } elseif(is_file($dir."/".$filename)) {
                $count_size = $count_size + filesize($dir."/".$filename);
                $count++;
            }
        }
    }
    return $count_size;
}

function lws_cleaner_convert($size)
{
    $unit=array(__('b', 'lws-cleaner'),__('K', 'lws-cleaner'),__('M', 'lws-cleaner'),__('G', 'lws-cleaner'),__('T', 'lws-cleaner'),__('P', 'lws-cleaner'));
    if ($size <= 0) {
        return '0 ' . $unit[1];
    }
    return @round($size/pow(1024, ($i=floor(log($size, 1024)))), 2).''.$unit[$i];
}


function recursive_reading($path)
{
    global $max_path, $all_files;
    $min_path = (count(explode('/', ABSPATH)));
    $max_path = $min_path + 2;
    $list_files = array();
    foreach (list_files($path, 1) as $files) {
        if (is_dir($files)) {
            $list_files[] = array('dir' => $files);
        } else {
            $list_files[] = array('file' => $files);
        }
    }

    usort($list_files, "lws_cl_sort_files");
    foreach ($list_files as $files) {
        $files = reset($files);
        $is_deletable = true;
        $path_to_file = $files;
        $clean_path = str_replace(ABSPATH, '', $path_to_file);

        if (is_dir($files)) {
            $files = explode('/', $files);
            end($files);
            $files = prev($files);

            foreach ($all_files as $name => $checksums) {
                if (preg_match('/' . $files . '/', $name) || preg_match("/^" . $files . '/', $name)) {
                    $is_deletable = false;
                    break;
                }
            }

            $actual_path_size = count(explode('/', $path_to_file));
            if ($actual_path_size < $max_path - 2) {
                $class = 'lws_cl_dir_accordion lws_cl_dir_accordion_white';
                $can_go = true;
            } elseif ($actual_path_size == $max_path - 1) {
                $class = 'lws_cl_dir_accordion lws_cl_dir_accordion_black';
                $can_go = true;
            } elseif ($actual_path_size == $max_path) {
                $class = 'lws_cl_dir_accordion lws_cl_dir_accordion_blue';
                $can_go = true;
            } else {
                $class = 'lws_cl_dir_accordion_nope';
                $can_go = false;
            }
            ?>
    <div class='<?php echo esc_attr($class)?>'>
        <span class="lws_cl_dir_block_name lws_cl_block_child">
            <img style="vertical-align:middle" alt="0"
                src=<?php echo esc_url(plugin_dir_url(__FILE__) . 'images/dossier.svg')?>>
            <strong><?php echo esc_html($files)?></strong>
            <?php if ($can_go) : ?>
            <img class="" width="15px" alt="chevron"
                src=<?php echo esc_url(plugin_dir_url(__FILE__) . 'images/chevron.svg')?>>
            <?php endif ?>
        </span>

        <span class="lws_cl_block_child">
            <?php echo esc_html(lws_cleaner_convert(folderSize($path_to_file))); ?>
        </span>

        <span class="lws_cl_block_child">
            <?php if ($is_deletable) : ?>
            <span class="lws_cl_not_native">
                <img class="lws_cl_image_button" width="20px" height="20px"
                    src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'images/non_natif.svg')?>">
                <span>
                    <?php esc_html_e('Not native in WordPress', 'lws-cleaner'); ?>
                </span>
            </span>
            <?php else : ?>
            <span class="lws_cl_native">
                <img class="lws_cl_image_button" width="20px" height="20px"
                    src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'images/securiser.svg')?>">
                <span>
                    <?php esc_html_e('Native in WordPress', 'lws-cleaner'); ?></span>
            </span>
            <?php endif ?>
        </span>

        <?php if ($is_deletable) : ?>
            <span class="lws_cl_block_child">
                <button id="lws_cl_button_<?php echo esc_attr($files); ?>"
                    class="lws_cl_files_delete_element lws_is_dir"
                    value='<?php echo esc_attr($path_to_file); ?>'
                    onclick="delete_element(this)">
                    <span class="" name="update">
                        <img class="lws_cl_image_button" width="20px" height="20px"
                            src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'images/supprimer.svg')?>">
                        <?php esc_html_e('Delete', 'lws-cleaner'); ?>
                    </span>
                    <span class="hidden" name="loading">
                        <img class="lws_cl_image_button" width="15px" height="15px"
                            src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'images/loading.svg')?>">
                        <span
                            id="loading_1"><?php esc_html_e("Deletion...", "lws-cleaner");?></span>
                    </span>
                    <span class="hidden" name="validated">
                        <img class="lws_cl_image_button" width="18px" height="18px"
                            src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'images/check_blanc.svg')?>">
                        <?php esc_html_e('Deleted', 'lws-cleaner'); ?>
                        &nbsp;
                    </span>
                </button>
            </span>
        <?php else : ?>
            <span><?php esc_html_e('', 'lws-cleaner'); ?></span>
        <?php endif ?>
    </div>
    <?php if ($can_go) : ?>
    <div class="lws_cl_inblock">
        <?php recursive_reading($path_to_file); ?>
    </div>
    <?php endif ?>
    <?php
        } else {
            $files = explode('/', $files);
            $files = end($files);
            $is_deletable = !array_key_exists($clean_path, $all_files);
            ?>
            <div class="lws_cl_dir_accordion">
                <span class="lws_cl_file_block_name lws_cl_block_child">
                    <?php $ext = sanitize_text_field(pathinfo($files, PATHINFO_EXTENSION));?>
                    <?php if (in_array($ext, array('php', 'html', 'js', 'py', 'ts' ))) : ?>
                    <img alt="1"
                        src=<?php echo esc_url(plugin_dir_url(__FILE__) . 'images/fichier_code.svg')?>>
                    <?php elseif (in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'))) : ?>
                    <img alt="1"
                        src=<?php echo esc_url(plugin_dir_url(__FILE__) . 'images/fichier_image.svg')?>>
                    <?php else : ?>
                    <img alt="1"
                        src=<?php echo esc_url(plugin_dir_url(__FILE__) . 'images/fichier_texte.svg')?>>
                    <?php endif?>
                    <span
                        style="vertical-align:text-bottom"><?php echo esc_html($files)?></span>
                </span>

                <span class="lws_cl_block_child">
                    <?php echo esc_html(filesize($path_to_file) <= 0 ? '0b' : lws_cleaner_convert(filesize($path_to_file)));?>
                </span>

                <span class="lws_cl_block_child">
                    <?php if ($is_deletable) : ?>
                    <span class="lws_cl_not_native">
                        <img class="lws_cl_image_button" width="20px" height="20px"
                            src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'images/non_natif.svg')?>">
                        <span>
                            <?php esc_html_e('Not native in WordPress', 'lws-cleaner'); ?>
                        </span>
                    </span>
                    <?php else : ?>
                    <span class="lws_cl_native">
                        <img class="lws_cl_image_button" width="20px" height="20px"
                            src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'images/securiser.svg')?>">
                        <span>
                            <?php esc_html_e('Native in WordPress', 'lws-cleaner'); ?></span>
                    </span>
                    <?php endif ?>
                </span>

                <span class="lws_cl_block_child">
                    <?php if ($is_deletable) : ?>
                    <button class="lws_cl_files_delete_element lws_is_file"
                        value='<?php echo esc_attr($path_to_file); ?>'
                        onclick="delete_element(this)">
                        <span class="" name="update">
                            <img class="lws_cl_image_button" width="20px" height="20px"
                                src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'images/supprimer.svg')?>">
                            <?php esc_html_e('Delete', 'lws-cleaner'); ?>
                        </span>
                        <span class="hidden" name="loading">
                            <img class="lws_cl_image_button" width="15px" height="15px"
                                src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'images/loading.svg')?>">
                            <span
                                id="loading_1"><?php esc_html_e("Deletion...", "lws-cleaner");?></span>
                        </span>
                        <span class="hidden" name="validated">
                            <img class="lws_cl_image_button" width="18px" height="18px"
                                src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'images/check_blanc.svg')?>">
                            <?php esc_html_e('Deleted', 'lws-cleaner'); ?>
                            &nbsp;
                        </span>
                    </button>
                    <?php else : ?>
                    <span><?php esc_html_e('', 'lws-cleaner'); ?></span>
                    <?php endif ?>
                </span>
            </div>
        <?php
        }
    }
}

add_action("wp_ajax_lws_cleaner_recursive_reading", "lws_cleaner_recursive_reading");
function lws_cleaner_recursive_reading(){
    global $max_path, $all_files;
        check_ajax_referer('cleaner_recursive_reading', '_ajax_nonce');

    $min_path = (count(explode('/', ABSPATH)));
    $max_path = $min_path + 2;
    $version = get_bloginfo('version');
    $locale = get_bloginfo('language');
    $locale = str_replace('-', '_', $locale);
    $all_files = get_core_checksums($version, $locale);
    if ($all_files === false) {
        $version = explode('.', $version)[0];
        $version .= ".0";
        $all_files = get_core_checksums($version, $locale);
    }
    if ($all_files === false) {
        $version = explode('.', $version)[0];
        $version = ($version - 1) . '.0';
        $all_files = get_core_checksums($version, $locale);
    }
    $all_files['wp-config.php'] = '';
    recursive_reading(ABSPATH);
    wp_die();
}


//AJAX Reminder//
add_action("wp_ajax_lws_cleaner_reminder_ajax", "lws_cleaner_remind_me_later");
function lws_cleaner_remind_me_later(){
    check_ajax_referer('reminder_for_cleaner', '_ajax_nonce');
    if (isset($_POST['data'])){
        set_transient('lwscleaner_remind_me', 1296000);        
    }
}

//AJAX Reminder//
add_action("wp_ajax_lws_cleaner_donotask_ajax", "lws_cleaner_do_not_ask");
function lws_cleaner_do_not_ask(){
    check_ajax_referer('donotask_for_cleaner', '_ajax_nonce');
    if (isset($_POST['data'])){
        update_option('lwscleaner_do_not_ask_again', true);        
    }
}


// AJAX PART FOR THE DOWNLOAD //
/*AJAX DOWNLOAD AND ACTIVATE PLUGINS*/

//AJAX DL Plugin//
add_action("wp_ajax_lws_cl_downloadPlugin", "wp_ajax_install_plugin");
//

//AJAX Activate Plugin//
add_action("wp_ajax_lws_cl_activatePlugin", "lws_cleaner_activate_plugin");
function lws_cleaner_activate_plugin()
{
    check_ajax_referer('activateplugin_cleaner', '_ajax_nonce');
    if (isset($_POST['ajax_slug'])) {
        switch (sanitize_textarea_field($_POST['ajax_slug'])) {
            case 'lws-hide-login':
                activate_plugin('lws-hide-login/lws-hide-login.php');
                break;
            case 'lws-sms':
                activate_plugin('lws-sms/lws-sms.php');
                break;
            case 'lws-tools':
                activate_plugin('lws-tools/lws-tools.php');
                break;
            case 'lws-affiliation':
                activate_plugin('lws-affiliation/lws-affiliation.php');
                break;
            case 'lws-cleaner':
                activate_plugin('lws-cleaner/lws-cleaner.php');
                break;
            case 'lwscache':
                activate_plugin('lwscache/lwscache.php');
                break;
            case 'lws-optimize':
                activate_plugin('lws-optimize/lws-optimize.php');
                break;
        }
    }
    wp_die();
}
//

/*END AJAX*/

//AJAX Modal//
add_action("wp_ajax_lws_cl_in_cache_modal", "lws_cl_modal_in_cache");
function lws_cl_modal_in_cache()
{
    check_ajax_referer('incache_modal_lws_cleaner', '_ajax_nonce');

    if (get_transient('lws_cl_incache_modal')) {
        wp_die('Already set');
    }
    set_transient('lws_cl_incache_modal', true, 302400);
}


//AJAX Posts//
add_action("wp_ajax_lws_cleaner_posts_ajax", "lws_cl_post");
function lws_cl_post()
{
    global $wpdb;
    check_ajax_referer('lws_cleaner_posts', '_ajax_nonce');

    if (isset($_POST['data'])) {
        $data = sanitize_text_field($_POST['data']);

        switch($data) {
            case 'revision_posts':
                $wpdb->get_results("DELETE FROM `" . $wpdb->posts . "` WHERE post_type='revision'");
                break;
            case 'auto_draft_posts':
                $wpdb->get_results("DELETE FROM `" . $wpdb->posts . "` WHERE post_status='auto-draft'");
                break;
            case 'trash_posts':
                $wpdb->get_results("DELETE FROM `" . $wpdb->posts . "` WHERE post_status='trash'");
                break;
            case 'orphan_posts':
                $wpdb->get_results("DELETE FROM $wpdb->postmeta WHERE post_id NOT IN(SELECT ID FROM $wpdb->posts)");
                break;
            case 'oembed_posts':
                $wpdb->get_results("DELETE FROM $wpdb->postmeta WHERE meta_key LIKE('%_oembed_%')");
                break;
            case 'duplicate_posts':
                $duplicate_number = $wpdb->get_results("SELECT GROUP_CONCAT(meta_id ORDER BY meta_id DESC) 
                AS ids, post_id, COUNT(*) AS count FROM $wpdb->postmeta GROUP BY post_id, meta_key, meta_value HAVING count > 1");
                foreach ($duplicate_number as $key => $duplicate) {
                    $to_delete = array();
                    $tmp = explode(',', $duplicate->ids);
                    while (count($tmp) > 1) {
                        $to_delete[] = array_pop($tmp);
                    } 
                    $wpdb->get_results("DELETE FROM $wpdb->postmeta WHERE meta_id 
                    IN (" . implode(',', $to_delete) . ") AND post_id = " . $duplicate->post_id);
                }
                break;
        }
        wp_die(true);
    }
    wp_die(false);
}

//AJAX Comments//
add_action("wp_ajax_lws_cleaner_comments_ajax", "lws_cl_comment");
function lws_cl_comment()
{
    global $wpdb;
    check_ajax_referer('lws_cleaner_comments', '_ajax_nonce');
    if (isset($_POST['data'])) {
        $data = sanitize_text_field($_POST['data']);

        switch($data) {
            case 'approved_comments':
                $wpdb->get_results("DELETE FROM $wpdb->comments WHERE comment_approved='0'");
                break;
            case 'spam_comments':
                $wpdb->get_results("DELETE FROM $wpdb->comments WHERE comment_approved='spam'");
                break;
            case 'trash_comments':
                $wpdb->get_results("DELETE FROM $wpdb->comments WHERE comment_approved='trash'");
                break;
            case 'orphan_comments':
                $wpdb->get_results("DELETE FROM $wpdb->commentmeta WHERE comment_id NOT IN(SELECT comment_ID FROM $wpdb->comments)");
                break;
            case 'duplicate_comments':
                $duplicate_comments_number = $wpdb->get_results("SELECT GROUP_CONCAT(meta_id ORDER BY meta_id DESC) 
                AS ids, comment_id, COUNT(*) AS count FROM $wpdb->commentmeta GROUP BY comment_id, meta_key, meta_value HAVING count > 1");
                foreach ($duplicate_comments_number as $key => $duplicate) {
                    $to_delete = array();
                    $tmp = explode(',', $duplicate->ids);
                    while (count($tmp) > 1) {
                        $to_delete[] = array_pop($tmp);
                    }           
                    $wpdb->get_results("DELETE FROM $wpdb->commentmeta WHERE meta_id 
                    IN (" . implode(',', $to_delete) . ") AND comment_id = " . $duplicate->comment_id);
                }
                break;
            case 'hide_comments':
                if (isset($_POST['checked'])) {
                    $checked = sanitize_text_field($_POST['checked']);
                    if ($checked == "true") {
                        update_option('lws_cl_hide_comments', 1);
                    } else {
                        delete_option('lws_cl_hide_comments');
                    }
                }
                break;
            case 'deactivate_comments':
                if (isset($_POST['checked'])) {
                    $checked = sanitize_text_field($_POST['checked']);
                    if ($checked == "true") {
                        update_option('lws_cl_deactivate_comments', 1);
                    } else {
                        delete_option('lws_cl_deactivate_comments');
                    }
                }
                break;
        }
        wp_die(true);
    }
    wp_die(false);
}

//AJAX Terms//
add_action("wp_ajax_lws_cleaner_terms_ajax", "lws_cl_term");
function lws_cl_term()
{
    global $wpdb;
    check_ajax_referer('lws_cleaner_terms', '_ajax_nonce');

    if (isset($_POST['data'])) {
        $data = sanitize_text_field($_POST['data']);
        switch($data) {
            case 'unused_terms':
                $wpdb->get_results("DELETE a FROM {$wpdb->terms} a INNER JOIN {$wpdb->term_taxonomy} b ON a.term_id = b.term_id WHERE b.count = 0");
                break;
            case 'orphan_terms':
                $wpdb->get_results("DELETE FROM $wpdb->termmate WHERE term_id NOT IN(SELECT term_id FROM $wpdb->terms)");
                break;
            case 'duplicate_terms':
                $duplicate_terms = $wpdb->get_results("SELECT GROUP_CONCAT(meta_id ORDER BY meta_id DESC) AS ids, term_id, COUNT(*) 
                AS count FROM $wpdb->termmeta GROUP BY term_id, meta_key, meta_value HAVING count > 1");
                foreach ($duplicate_terms as $key => $duplicate) {
                    $to_delete = array();
                    $tmp = explode(',', $duplicate->ids);
                    while (count($tmp) > 1) {
                        $to_delete[] = array_pop($tmp);
                    }           
                    $wpdb->get_results("DELETE FROM $wpdb->termmeta WHERE meta_id IN (" . implode(',', $to_delete) . ") AND term_id = " . $duplicate->term_id);
                }
                break;
            case 'orphan_relationship_terms':
                $wpdb->get_results("DELETE FROM $wpdb->term_relationships WHERE term_taxonomy_id NOT IN(SELECT term_taxonomy_id FROM $wpdb->term_taxonomy)");
                break;
        }
        wp_die(true);
    }
    wp_die(false);
}

//AJAX Users//
add_action("wp_ajax_lws_cleaner_users_ajax", "lws_cl_user");
function lws_cl_user()
{
    global $wpdb;
    check_ajax_referer('lws_cleaner_users', '_ajax_nonce');
    if (isset($_POST['data'])) {
        $data = sanitize_text_field($_POST['data']);

        switch($data) {
            case 'duplicate_user':
                $duplicate_users_number = $wpdb->get_results("SELECT GROUP_CONCAT(umeta_id ORDER BY umeta_id DESC) AS ids, user_id, COUNT(*) AS count FROM $wpdb->usermeta GROUP BY user_id, meta_key, meta_value HAVING count > 1");
                foreach ($duplicate_users_number as $key => $duplicate) {
                    $to_delete = array();
                    $tmp = explode(',', $duplicate->ids);
                    while (count($tmp) > 1) {
                        $to_delete[] = array_pop($tmp);
                    } 
                    $wpdb->get_results("DELETE FROM $wpdb->usermeta WHERE umeta_id IN (" . implode(',', $to_delete) . ") AND user_id = " . $duplicate->user_id);
                }
                break;
            case 'orphan_user_data':
                $wpdb->get_results("DELETE FROM $wpdb->usermeta WHERE user_id NOT IN(SELECT ID FROM $wpdb->users)");
                break;
        }
        wp_die(true);
    }
    wp_die(false);
}

//AJAX Settings//
add_action("wp_ajax_lws_cleaner_settings_ajax", "lws_cl_settings");
function lws_cl_settings()
{
    global $wpdb;
    check_ajax_referer('lws_cleaner_settings', '_ajax_nonce');
    if (isset($_POST['data'])) {
        $data = sanitize_text_field($_POST['data']);

        switch($data) {
            case 'transients':
                $wpdb->get_results("DELETE FROM $wpdb->options WHERE option_name LIKE '%_transient_%';");
                break;
            case 'crons':
                update_option('cron', '');
                break;
        }
        wp_die(true);
    }
    wp_die(false);
}

//AJAX Plugin & Theme//
add_action("wp_ajax_lws_cleaner_pluginsandthemes_ajax", "lws_cl_pandt");
function lws_cl_pandt()
{
    global $wpdb;
    check_ajax_referer('lws_cleaner_pluginsandthemes', '_ajax_nonce');
    if (isset($_POST['data'])) {
        $data = sanitize_text_field($_POST['data']);

        switch($data) {
            case 'plugins':
                $to_delete = array();
                foreach(get_plugins() as $slug => $plugin) {
                    if (!is_plugin_active($slug) && !is_plugin_active_for_network($slug)) {
                        $to_delete[] = $slug;
                    }
                }
                delete_plugins($to_delete);
                break;
            case 'themes':
                foreach(wp_get_themes() as $slug => $theme) {
                    if ($theme['Name'] != wp_get_theme()->name) {
                        delete_theme($slug);
                    }
                }
                break;
        }
        wp_die(true);
    }
    wp_die(false);
}

//AJAX Files//
add_action("wp_ajax_lws_cleaner_delete", "lws_cl_delete_file");
function lws_cl_delete_file()
{
    check_ajax_referer('lws_cleaner_deletefiles', '_ajax_nonce');
    if (isset($_POST['lws_cl_path']) && isset($_POST['lws_cl_type'])) {
        require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';

        $wp_filesystem = new WP_Filesystem_Direct(null);
        $path = sanitize_text_field($_POST['lws_cl_path']);
        $type = sanitize_text_field($_POST['lws_cl_type']);
        if ($type == 'file') {
            $wp_filesystem->delete($path, false, 'f');
        } else {
            $wp_filesystem->delete($path, true, 'd');
        }
        wp_die(true);
    }
    wp_die(false);
}

//AJAX Medias//
add_action("wp_ajax_lws_cleaner_ignore_element", "lws_cl_ignore_element");
function lws_cl_ignore_element()
{
    check_ajax_referer('lws_cleaner_ignoreelmt', '_ajax_nonce');
    if (isset($_POST['data'])) {
        global $wpdb;
        $value = sanitize_text_field($_POST['data']);
        $wpdb->get_results("INSERT INTO {$wpdb->prefix}lws_cl_ignore (IDmedia) VALUES($value)");
        wp_die();
    }

    wp_die();
}

add_action("wp_ajax_lws_cleaner_delete_element", "lws_cl_delete_element");
function lws_cl_delete_element()
{
    check_ajax_referer('lws_cleaner_dlteelmt', '_ajax_nonce');
    if (isset($_POST['data'])) {
        global $wpdb;
        $value = sanitize_text_field($_POST['data']);
        $wpdb->get_results("DELETE FROM $wpdb->posts WHERE ID = $value");
        wp_die();
    }

    wp_die();
}

add_action("wp_ajax_lws_cleaner_unignore_element", "lws_cl_unignore_element");
function lws_cl_unignore_element()
{
    check_ajax_referer('lws_cleaner_unignrelmt', '_ajax_nonce');
    if (isset($_POST['data'])) {
        global $wpdb;
        $value = sanitize_text_field($_POST['data']);
        $wpdb->get_results("DELETE FROM {$wpdb->prefix}lws_cl_ignore WHERE IDmedia = $value");
        wp_die();
    }

    wp_die();
}

if (! class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Create Table
 */
if (! class_exists('LwsCL_MediaList')) :
    class LwsCL_MediaList extends WP_List_Table
    {
        public function __construct()
        {
            parent::__construct(
                array(
                    'singular' => 'singular_form',
                    'plural'   => 'plural_form',
                    'ajax'     => false
                )
            );
        }

        /**
         * Get a list of columns.
         *
         * @return array
         */
        public function get_columns()
        {
            return array(
                'cb'        => '<input type="checkbox" />',
                'image'      => wp_strip_all_tags(__('Image', 'lws-cleaner')),
                'file_name'      => wp_strip_all_tags(__('File name', 'lws-cleaner')),
                'author'   => wp_strip_all_tags(__('Author', 'lws-cleaner')),
                'date'   => wp_strip_all_tags(__('Date', 'lws-cleaner')),
                'action'   => wp_strip_all_tags(__('Action', 'lws-cleaner')),
            );
        }

        /**
         * Prepares the list of items for displaying.
         */
        public function prepare_items()
        {
            $this->process_bulk_action();
            $columns  = $this->get_columns();
            $hidden   = array();
            $sortable = array();
            $primary  = 'file_name';
            $this->_column_headers = array( $columns, $hidden, $sortable, $primary );
        }

        /**
         * Define our bulk actions
         *
         * @since 1.2
         * @returns array() $actions Bulk actions
         */
        public function get_bulk_actions()
        {
            $actions = array(
                'bulk-delete' => __('Delete'),
                'bulk-ignore' => __('Ignore', 'lws-cleaner'),
            );

            return $actions;
        }

        public function column_file_name($item)
        {
            ?>
<a href="<?php echo esc_url($item['link']); ?>">
    <?php echo esc_html($item['file_name']); ?></a>
<?php
        }

        public function column_action($item)
        {
            $actions = [
                'delete' => include __DIR__ . '/views/lws_cl_form_delete_media.php',
                'ignore' => include __DIR__ . '/views/lws_cl_form_ignore_media.php'
            ];
            echo $this->row_actions($actions);
        }

        /**
         * Process our bulk actions
         *
         * @since 1.2
         */
        public function process_bulk_action()
        {
            global $wpdb;
            $action = $this->current_action();

            switch ($action) {
                case 'bulk-delete':
                    if (isset($_POST['bulk-delete'])) {
                        $data = array();
                        foreach ($_POST['bulk-delete'] as $d) {
                            $data[] = sanitize_text_field($d);
                        }
                        $wpdb->get_results("DELETE FROM $wpdb->posts WHERE ID IN(" . implode(',', $data) . ")");
                        header("Refresh:0");
                    }
                    break;
            
                case 'bulk-ignore':
                    if (isset($_POST['bulk-delete'])) {
                        foreach ($_POST['bulk-delete'] as $i) {
                            $data = sanitize_text_field($i);
                            $wpdb->get_results("INSERT INTO {$wpdb->prefix}lws_cl_ignore (IDmedia) VALUES ('$data')");
                        }
                        header("Refresh:0");
                    }
                    break;

                            
            
                default:
                    // do nothing or something else
                    return;
                    break;
            }
            
            return;
            //$wpdb->get_results( "DELETE FROM $wpdb->posts WHERE ID = $id_attachment" );
        }

        public function column_cb($item)
        {
            return sprintf(
                '<input type="checkbox" name="bulk-delete[]" value="%s" />',
                $item['ID']
            );
        }

        /**
         * Generates content for a single row of the table.
         *
         * @param object $item The current item.
         * @param string $column_name The current column name.
         */
        protected function column_default($item, $column_name)
        {
            switch ($column_name) {
                case 'image':
                    return wp_kses($item['image'], array('img' => array('src' => array(), 'class' => array(), 'loading' => array(), 'sizes' => array(), 'srcset' => array() )));
                case 'author':
                    return esc_html($item['author']);
                case 'date':
                    return esc_html($item['date']);
                    return 'Unknown';
            }
        }
                


        /**
         * Generates custom table navigation to prevent conflicting nonces.
         *
         * @param string $which The location of the bulk actions: 'top' or 'bottom'.
         */
        protected function display_tablenav($which)
        {
            ?>
<div class="tablenav <?php echo esc_attr($which); ?>">

    <div class="alignleft actions bulkactions">
        <?php $this->bulk_actions($which); ?>
    </div>
    <?php
                        $this->extra_tablenav($which);
            $this->pagination($which);
                                
            ?>

    <br class="clear" />
</div>
<?php
        }

        /**
         * Generates content for a single row of the table.
         *
         * @param object $item The current item.
         */
        public function single_row($item)
        {
            echo '<tr>';
            $this->single_row_columns($item);
            echo '</tr>';
        }
    }
endif;

/**
 * Create Table
 */
if (! class_exists('LwsCL_MediaList_Ignored')) :
    class LwsCL_MediaList_Ignored extends WP_List_Table
    {
        public function __construct()
        {
            parent::__construct(
                array(
                    'singular' => 'singular_form',
                    'plural'   => 'plural_form',
                    'ajax'     => false
                )
            );
        }

        /**
         * Get a list of columns.
         *
         * @return array
         */
        public function get_columns()
        {
            return array(
                'cb'        => '<input type="checkbox" />',
                'image'      => wp_strip_all_tags(__('Image', 'lws-cleaner')),
                'file_name_ignored'      => wp_strip_all_tags(__('File name', 'lws-cleaner')),
                'author'   => wp_strip_all_tags(__('Author', 'lws-cleaner')),
                'date'   => wp_strip_all_tags(__('Date', 'lws-cleaner')),
                'action'   => wp_strip_all_tags(__('Action', 'lws-cleaner')),
            );
        }

        public function get_sortable_columns()
        {
            return array(
                'file_name_ignored' => array(__('File name', 'lws-cleaner'), true)
            );
        }

        /**
        * Prepares the list of items for displaying.
        */
        public function prepare_items()
        {
            $this->process_bulk_action();
            $columns  = $this->get_columns();
            $hidden   = array();
            $sortable = array();
            $primary  = 'file_name_ignored';
            $this->_column_headers = array( $columns, $hidden, $sortable, $primary );
        }

        /**
         * Define our bulk actions
         *
         * @since 1.2
         * @returns array() $actions Bulk actions
         */
        public function get_bulk_actions()
        {
            $actions = array(
                'bulk-unignore' => __('Unignore', 'lws-cleaner'),
            );

            return $actions;
        }

        public function column_file_name_ignored($item)
        {
            ?>
<a href="<?php echo esc_url($item['link']); ?>">
    <?php echo esc_html($item['file_name_ignored']); ?></a>
<?php
        }

        public function column_action($item)
        {
            $actions = [
                'unignore' => include __DIR__ . '/views/lws_cl_form_unignore_media.php'
            ];
            echo $this->row_actions($actions);
        }

        /**
         * Process our bulk actions
         *
         * @since 1.2
         */
        public function process_bulk_action()
        {
            global $wpdb;
            $action = $this->current_action();

            switch ($action) {
                case 'bulk-unignore':
                    if (isset($_POST['bulk-unignore'])) {
                        $data = array();
                        foreach ($_POST['bulk-unignore'] as $d) {
                            $data[] = sanitize_text_field($d);
                        }
                        $wpdb->get_results("DELETE FROM {$wpdb->prefix}lws_cl_ignore WHERE IDmedia IN(" . implode(',', $data) . ")");
                        header("Refresh:0");
                    }
                    break;
                default:
                    // do nothing or something else
                    return;
                    break;
            }
    
            return;
            //$wpdb->get_results( "DELETE FROM $wpdb->posts WHERE ID = $id_attachment" );
        }

        public function column_cb($item)
        {
            return sprintf(
                '<input type="checkbox" name="bulk-unignore[]" value="%s" />',
                $item['ID']
            );
        }

        /**
         * Generates content for a single row of the table.
         *
         * @param object $item The current item.
         * @param string $column_name The current column name.
         */
        protected function column_default($item, $column_name)
        {
            switch ($column_name) {
                case 'image':
                    return wp_kses($item['image'], array('img' => array('src' => array(), 'class' => array(), 'loading' => array(), 'sizes' => array(), 'srcset' => array() )));
                case 'author':
                    return esc_html($item['author']);
                case 'date':
                    return esc_html($item['date']);
                    return 'Unknown';
            }
        }
        


        /**
         * Generates custom table navigation to prevent conflicting nonces.
         *
         * @param string $which The location of the bulk actions: 'top' or 'bottom'.
         */
        protected function display_tablenav($which)
        {
            ?>
<div class="tablenav <?php echo esc_attr($which); ?>">

    <div class="alignleft actions bulkactions">
        <?php $this->bulk_actions($which); ?>
    </div>
    <?php
                            $this->extra_tablenav($which);
            $this->pagination($which);
                            
            ?>

    <br class="clear" />
</div>
<?php
        }

        /**
         * Generates content for a single row of the table.
         *
         * @param object $item The current item.
         */
        public function single_row($item)
        {
            echo '<tr>';
            $this->single_row_columns($item);
            echo '</tr>';
        }
    }
endif;
?>
