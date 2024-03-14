<?php

namespace SPEX_post_export\exportclass;

define('SPE_VERSION', '1.0');

class SPEX_SelectPostExport
{
    /**
     * Constructor
     */
    public function __construct($post_ids)
    {
        global $wpdb;
        $filename = $this->make_file_name();
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Content-Type: text/xml; charset=' . get_option('blog_charset'), true);
        echo '<?xml version="1.0" encoding="' . get_bloginfo('charset') . "\" ?>\n";
?>
        <rss version="2.0" xmlns:excerpt="http://wordpress.org/export/<?php echo SPE_VERSION; ?>/excerpt/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wp="http://wordpress.org/export/<?php echo SPE_VERSION; ?>/">
            <channel>

                <title><?php bloginfo_rss('name'); ?></title>
                <link><?php bloginfo_rss('url'); ?></link>
                <description><?php bloginfo_rss('description'); ?></description>
                <pubDate><?php echo gmdate('D, d M Y H:i:s +0000'); ?></pubDate>
                <language><?php bloginfo_rss('language'); ?></language>
                <wp:wxr_version><?php echo SPE_VERSION; ?></wp:wxr_version>
                <wp:base_site_url><?php echo $this->wxr_site_url(); ?></wp:base_site_url>
                <wp:base_blog_url><?php bloginfo_rss('url'); ?></wp:base_blog_url>
                <?php
                $this->do_authors($post_ids);

                foreach ($post_ids as $p) {
                    $is_sticky = is_sticky($p) ? 1 : 0;
                    $post = get_post($p);
                    $title = $this->wxr_cdata(apply_filters('the_title_export', $post->post_title));
                    $content = $this->wxr_cdata(apply_filters('the_content_export', $post->post_content));
                    $excerpt = $this->wxr_cdata(apply_filters('the_excerpt_export', $post->post_excerpt));


                ?>
                    <item>
                        <title><?php echo $title; ?></title>
                        <link><?php the_permalink_rss(); ?></link>
                        <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
                        <dc:creator><?php echo $this->wxr_cdata(get_the_author_meta('login')); ?></dc:creator>
                        <guid isPermaLink="false"><?php the_guid(); ?></guid>
                        <description></description>
                        <content:encoded><?php echo $content; ?></content:encoded>
                        <excerpt:encoded><?php echo $excerpt; ?></excerpt:encoded>
                        <wp:post_id><?php echo (int) $post->ID; ?></wp:post_id>
                        <wp:post_date><?php echo $this->wxr_cdata($post->post_date); ?></wp:post_date>
                        <wp:post_date_gmt><?php echo $this->wxr_cdata($post->post_date_gmt); ?></wp:post_date_gmt>
                        <wp:post_modified><?php echo $this->wxr_cdata($post->post_modified); ?></wp:post_modified>
                        <wp:post_modified_gmt><?php echo $this->wxr_cdata($post->post_modified_gmt); ?></wp:post_modified_gmt>
                        <wp:comment_status><?php echo $this->wxr_cdata($post->comment_status); ?></wp:comment_status>
                        <wp:ping_status><?php echo $this->wxr_cdata($post->ping_status); ?></wp:ping_status>
                        <wp:post_name><?php echo $this->wxr_cdata($post->post_name); ?></wp:post_name>
                        <wp:status><?php echo $this->wxr_cdata($post->post_status); ?></wp:status>
                        <wp:post_parent><?php echo (int) $post->post_parent; ?></wp:post_parent>
                        <wp:menu_order><?php echo (int) $post->menu_order; ?></wp:menu_order>
                        <wp:post_type><?php echo $this->wxr_cdata($post->post_type); ?></wp:post_type>
                        <wp:post_password><?php echo $this->wxr_cdata($post->post_password); ?></wp:post_password>
                        <wp:is_sticky><?php echo (int) $is_sticky; ?></wp:is_sticky>
                        <?php if ('attachment' === $post->post_type) : ?>
                            <wp:attachment_url><?php echo $this->wxr_cdata(wp_get_attachment_url($post->ID)); ?></wp:attachment_url>
                        <?php endif; ?>
                        <?php $this->wxr_post_taxonomy($post); ?>
                        <?php
                        $postmeta = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE post_id = %d", $post->ID));
                        foreach ($postmeta as $meta) :
                            /**
                             * Filters whether to selectively skip post meta used for WXR exports.
                             *
                             * Returning a truthy value from the filter will skip the current meta
                             * object from being exported.
                             *
                             * @since 3.3.0
                             *
                             * @param bool   $skip     Whether to skip the current post meta. Default false.
                             * @param string $meta_key Current meta key.
                             * @param object $meta     Current meta object.
                             */
                            if (apply_filters('wxr_export_skip_postmeta', false, $meta->meta_key, $meta)) {
                                continue;
                            }
                        ?>
                            <wp:postmeta>
                                <wp:meta_key><?php echo $this->wxr_cdata($meta->meta_key); ?></wp:meta_key>
                                <wp:meta_value><?php echo $this->wxr_cdata($meta->meta_value); ?></wp:meta_value>
                            </wp:postmeta>
                        <?php
                        endforeach;

                        $_comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d AND comment_approved <> 'spam'", $post->ID));
                        $comments  = array_map('get_comment', $_comments);
                        foreach ($comments as $c) :
                        ?>
                            <wp:comment>
                                <wp:comment_id><?php echo (int) $c->comment_ID; ?></wp:comment_id>
                                <wp:comment_author><?php echo $this->wxr_cdata($c->comment_author); ?></wp:comment_author>
                                <wp:comment_author_email><?php echo $this->wxr_cdata($c->comment_author_email); ?></wp:comment_author_email>
                                <wp:comment_author_url><?php echo esc_url_raw($c->comment_author_url); ?></wp:comment_author_url>
                                <wp:comment_author_IP><?php echo $this->wxr_cdata($c->comment_author_IP); ?></wp:comment_author_IP>
                                <wp:comment_date><?php echo $this->wxr_cdata($c->comment_date); ?></wp:comment_date>
                                <wp:comment_date_gmt><?php echo $this->wxr_cdata($c->comment_date_gmt); ?></wp:comment_date_gmt>
                                <wp:comment_content><?php echo $this->wxr_cdata($c->comment_content); ?></wp:comment_content>
                                <wp:comment_approved><?php echo $this->wxr_cdata($c->comment_approved); ?></wp:comment_approved>
                                <wp:comment_type><?php echo $this->wxr_cdata($c->comment_type); ?></wp:comment_type>
                                <wp:comment_parent><?php echo (int) $c->comment_parent; ?></wp:comment_parent>
                                <wp:comment_user_id><?php echo (int) $c->user_id; ?></wp:comment_user_id>
                                <?php
                                $c_meta = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->commentmeta WHERE comment_id = %d", $c->comment_ID));
                                foreach ($c_meta as $meta) :
                                    /**
                                     * Filters whether to selectively skip comment meta used for WXR exports.
                                     *
                                     * Returning a truthy value from the filter will skip the current meta
                                     * object from being exported.
                                     *
                                     * @since 4.0.0
                                     *
                                     * @param bool   $skip     Whether to skip the current comment meta. Default false.
                                     * @param string $meta_key Current meta key.
                                     * @param object $meta     Current meta object.
                                     */
                                    if (apply_filters('wxr_export_skip_commentmeta', false, $meta->meta_key, $meta)) {
                                        continue;
                                    }
                                ?>
                                    <wp:commentmeta>
                                        <wp:meta_key><?php echo $this->wxr_cdata($meta->meta_key); ?></wp:meta_key>
                                        <wp:meta_value><?php echo $this->wxr_cdata($meta->meta_value); ?></wp:meta_value>
                                    </wp:commentmeta>
                                <?php endforeach; ?>
                            </wp:comment>
                        <?php endforeach; ?>
                    </item>
                <?php


                }
                ?>
            </channel>
        </rss>
<?php
    }
    function wxr_post_taxonomy($post)
    {


        $taxonomies = get_object_taxonomies($post->post_type);
        if (empty($taxonomies)) {
            return;
        }
        $terms = wp_get_object_terms($post->ID, $taxonomies);

        foreach ((array) $terms as $term) {
            echo "\t\t<category domain=\"{$term->taxonomy}\" nicename=\"{$term->slug}\">" . $this->wxr_cdata($term->name) . "</category>\n";
        }
    }
    function wxr_site_url()
    {
        if (is_multisite()) {
            // Multisite: the base URL.
            return network_home_url();
        } else {
            // WordPress (single site): the blog URL.
            return get_bloginfo_rss('url');
        }
    }
    function do_authors(array $post_ids = null)
    {
        global $wpdb;

        if (!empty($post_ids)) {
            $post_ids = array_map('absint', $post_ids);
            $and      = 'AND ID IN ( ' . implode(', ', $post_ids) . ')';
        } else {
            $and = '';
        }

        $authors = array();
        $results = $wpdb->get_results("SELECT DISTINCT post_author FROM $wpdb->posts WHERE post_status != 'auto-draft' $and");
        foreach ((array) $results as $result) {
            $authors[] = get_userdata($result->post_author);
        }

        $authors = array_filter($authors);

        foreach ($authors as $author) {
            echo "\t<wp:author>";
            echo '<wp:author_id>' . (int) $author->ID . '</wp:author_id>';
            echo '<wp:author_login>' . $this->wxr_cdata($author->user_login) . '</wp:author_login>';
            echo '<wp:author_email>' . $this->wxr_cdata($author->user_email) . '</wp:author_email>';
            echo '<wp:author_display_name>' . $this->wxr_cdata($author->display_name) . '</wp:author_display_name>';
            echo '<wp:author_first_name>' . $this->wxr_cdata($author->first_name) . '</wp:author_first_name>';
            echo '<wp:author_last_name>' . $this->wxr_cdata($author->last_name) . '</wp:author_last_name>';
            echo "</wp:author>\n";
        }
    }
    function wxr_cdata($str)
    {
        if (!seems_utf8($str)) {
            $str = utf8_encode($str);
        }
        // $str = ent2ncr(esc_html($str));
        $str = '<![CDATA[' . str_replace(']]>', ']]]]><![CDATA[>', $str) . ']]>';

        return $str;
    }
    function make_file_name()
    {

        $sitename = sanitize_key(get_bloginfo('name'));
        if (!empty($sitename))
            $sitename .= '.';

        $date = gmdate('Y-m-d');
        $wp_filename = $sitename . 'Wordpress.' . $date . '.xml';

        $file_name = apply_filters('export_wp_filename', $wp_filename, $sitename, $date);


        return ($file_name);
    }
}
function handle_the_action($redirect_url, $action, $post_ids)
{
    $the_count = count($post_ids);

    if ($the_count > 0) {



        new  SPEX_SelectPostExport($post_ids);
        exit();
    }
    return $redirect_url;
}

function handle_the_options()
{
    $the_options = get_option('select_post_options');

    if (!empty($the_options)) {
        foreach ($the_options as $key => $o) {
            if (strcmp($key, 'media') == 0) {
                $action = 'bulk_actions-upload';
            } else {
                $action = 'bulk_actions-edit-' . $key;
            }

            add_filter(
                $action,
                function ($bulk_actions) {
                    $bulk_actions['post_export'] = __('Export Individual Posts', 'select_post_export');
                    return $bulk_actions;
                }
            );
            if (strcmp($key, 'media') == 0)
                $action = 'handle_bulk_actions-upload';
            else
                $action = 'handle_bulk_actions-edit-' . $key;


            add_filter($action, function ($redirect_url, $action, $post_ids) {
                return handle_the_action($redirect_url, $action, $post_ids);
            }, 10, 3);
        }
    }
}
add_action('admin_init', 'SPEX_post_export\\exportclass\\handle_the_options');
