<?php
/**
 * Filter contents of single comic post
 *
 *
 * @param string $content Post content
 * @return string
 */
function toocheke_universal_single_comic_content_filter($content)
{
    global $post;

    if (get_post_type($post) !== 'comic') {
        return $content;
    }

    $thumbnail_size = isset($image_sizes['comic-page']) ? $image_sizes['comic-page'] : 'large';

    remove_filter('the_content', 'toocheke_universal_single_comic_content_filter');

    ob_start();
    require TOOCHEKE_COMPANION_PLUGIN_DIR . 'templates/content-singlecomic.php';
    $generated_content = ob_get_contents();
    ob_end_clean();

    $content = $generated_content . $content;

    return $content;
}

/* Get Comic Image */
function toocheke_universal_get_first_image()
{
    global $post, $posts;
    $first_img = '';
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+?src=[\'"]([^\'"]+)[\'"].*?>/i', $post->post_content, $matches);
    if ($output) {
        $first_img = $matches[1][0];
    }

    if (empty($first_img)) {
        $first_img = esc_attr(plugins_url('toocheke-companion' . '/img/default-thumbnail-image.png'));
    }
    return $first_img;
}
/**
 * Toocheke Navigation
 *
 * Displays navigation for post specified by $post_id.
 *
 * @since 0.1b
 *
 * @global object $wpdb
 *
 * @param array $args Arguments for navigation output
 * @param bool $echo Specifies whether to echo comic navigation or return it as a string
 * @return string Returns navigation string if $echo is set to false.
 */
if (!function_exists('toocheke_universal_get_comic_link')):
    function toocheke_universal_get_comic_link($order, $font, $collection_id = 0, $display_default_button = null, $image_button = null, $series_id = null)
{
        //global $post;
        $current_permalink = esc_url(get_permalink());
        $placeholder = $GLOBALS['post'];
        $image_button_url = null !== $image_button && strlen($image_button) > 0 ? get_option('toocheke-' . $image_button . '-button') : "";
        $button = $display_default_button ? '<i class="fas fa-lg fa-step-' . $font . '"></i>' : '<img class="comic-image-nav" src="' . esc_attr($image_button_url) . '" />';
        $args = array(
            'post_parent' => $series_id,
            'post_type' => 'comic',
            'numberposts' => 1,
            'offset' => 0,
            'orderby' => 'post_date',
            'order' => $order,
            'post_status' => 'publish');
        if ($collection_id > 0) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'collections',
                    'field' => 'term_id',
                    'terms' => $collection_id,
                ),
            );
        }

        $sorted_posts = get_posts($args);
        $permalink = esc_url(get_permalink($sorted_posts[0]->ID));
        if ($permalink == $current_permalink) {
            return;
        }

        $permalink = esc_url($collection_id > 0 ? add_query_arg('col', $collection_id, get_permalink($sorted_posts[0]->ID)) : get_permalink($sorted_posts[0]->ID));
        //add series id parameter
        $permalink = add_query_arg('sid', $series_id, $permalink);
        $title = esc_attr($sorted_posts[0]->post_title);
        $post = $placeholder;
        $font = esc_attr($font);
        $latest_link_html = '<a href="' . $permalink . '" title="' . $title . '" >' . $button . '</a>';
        return $latest_link_html;
    }
endif;
if (!function_exists('toocheke_universal_adjacent_comic_link')):
    function toocheke_universal_adjacent_comic_link($current_post_id, $collection_id, $direction, $display_default_button = null, $series_id = null)
{

        // Info
        $postIDs = array();

        $args = array(
            'post_parent' => $series_id,
            'post_type' => 'comic',
            'nopaging' => true,
            'offset' => 0,
            'orderby' => 'post_date',
            'order' => 'ASC',
            'post_status' => 'publish');

        if ($collection_id > 0) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'collections',
                    'field' => 'term_id',
                    'terms' => $collection_id,
                ),
            );
        }

        $comic_posts = get_posts($args);
        $image_button_url = null !== $direction && strlen($direction) > 0 ? get_option('toocheke-' . $direction . '-button') : "";

        // Get post IDs
        foreach ($comic_posts as $thepost):
            $postIDs[] = $thepost->ID;
        endforeach;

        // Get prev and next post ID
        $currentIndex = array_search($current_post_id, $postIDs);
        if ($currentIndex > 0) {
            $prevID = $postIDs[$currentIndex - 1];
            $prev_title = esc_attr($comic_posts[$currentIndex - 1]->post_title);
        }
        if ($currentIndex < count($comic_posts) - 1) {
            $nextID = $postIDs[$currentIndex + 1];
            $next_title = esc_attr($comic_posts[$currentIndex + 1]->post_title);
        }

        // Return information
        if ($direction == 'next' and !empty($nextID)):
            $button = $display_default_button ? '<i class="fas fa-lg fa-chevron-right" aria-hidden="true"></i>' : '<img class="comic-image-nav" src="' . esc_attr($image_button_url) . '" />';
            $permalink = esc_url($collection_id > 0 ? add_query_arg('col', $collection_id, get_permalink($nextID)) : get_permalink($nextID));
            //add series id parameter
            $permalink = add_query_arg('sid', $series_id, $permalink);
            $link_html = '<a class="' . $direction . '-comic" href="' . $permalink . '" title="' . $next_title . '" >' . $button . '</a>';

        elseif ($direction == 'previous' and !empty($prevID)):
            $button = $display_default_button ? '<i class="fas fa-lg fa-chevron-left" aria-hidden="true"></i>' : '<img class="comic-image-nav" src="' . esc_attr($image_button_url) . '" />';
            $permalink = esc_url($collection_id > 0 ? add_query_arg('col', $collection_id, get_permalink($prevID)) : get_permalink($prevID));
            //add series id parameter
            $permalink = add_query_arg('sid', $series_id, $permalink);
            $link_html = '<a class="' . $direction . '-comic" href="' . $permalink . '" title="' . $prev_title . '" >' . $button . '</a>';

        else:
            return false;
        endif;
        return $link_html;
    }
endif;
/**
 * Toocheke Calendar
 *
 * Displays calendar for post type
 * toocheke_get_calendar() :: Extends get_calendar() by including custom post types.
 * Derived from get_calendar() code in /wp-includes/general-template.php.
 */
function toocheke_universal_get_calendar($calendar_output = "", $initial = true, $echo = true)
{
    global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;

    $posttype = 'comic';

    // Quick check. If we have no posts at all, abort!
    if (!$posts) {
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $gotsome = $wpdb->get_var("SELECT 1 as test FROM $wpdb->posts WHERE post_type = '$posttype' AND post_status = 'publish' LIMIT 1");
    }

    if (isset($_GET['w'])) {
        $w = (int) $_GET['w'];
    }

    // week_begins = 0 stands for Sunday
    $week_begins = (int) get_option('start_of_week');
    $ts = current_time('timestamp');

    // Let's figure out when we are
    if (!empty($monthnum) && !empty($year)) {
        $thismonth = zeroise(intval($monthnum), 2);
        $thisyear = (int) $year;
    } elseif (!empty($w)) {
        // We need to get the month from MySQL
        $thisyear = (int) substr($m, 0, 4);
        // it seems MySQL's weeks disagree with PHP's
        $d = (($w - 1) * 7) + 6;
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $thismonth = $wpdb->get_var("SELECT DATE_FORMAT((DATE_ADD('{$thisyear}0101', INTERVAL $d DAY) ), '%m')");
    } elseif (!empty($m)) {
        $thisyear = (int) substr($m, 0, 4);
        if (strlen($m) < 6) {
            $thismonth = '01';
        } else {
            $thismonth = zeroise((int) substr($m, 4, 2), 2);
        }
    } else {
        $thisyear = gmdate('Y', $ts);
        $thismonth = gmdate('m', $ts);
    }

    $unixmonth = mktime(0, 0, 0, $thismonth, 1, $thisyear);
    $last_day = date('t', $unixmonth);

    // Get the next and previous month and year with at least one post
    // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    $previous = $wpdb->get_row("SELECT MONTH(post_date) AS month, YEAR(post_date) AS year FROM $wpdb->posts WHERE post_date < '$thisyear-$thismonth-01' AND post_type = '$posttype' AND post_status = 'publish' ORDER BY post_date DESC LIMIT 1");
    // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    $next = $wpdb->get_row("SELECT MONTH(post_date) AS month, YEAR(post_date) AS year FROM $wpdb->posts WHERE post_date > '$thisyear-$thismonth-{$last_day} 23:59:59' AND post_type = '$posttype' AND post_status = 'publish' ORDER BY post_date ASC LIMIT 1");

    /* translators: Calendar caption: 1: month name, 2: 4-digit year */
    $calendar_caption = _x('%1$s %2$s', 'calendar caption', 'toocheke');
    $calendar_output = '<table id="toocheke-calendar">
		<caption>' . sprintf(
        $calendar_caption,
        $wp_locale->get_month($thismonth),
        date('Y', $unixmonth)
    ) . '</caption>
		<thead>
		<tr>';

    $myweek = array();

    for ($wdcount = 0; $wdcount <= 6; $wdcount++) {
        $myweek[] = $wp_locale->get_weekday(($wdcount + $week_begins) % 7);
    }

    foreach ($myweek as $wd) {
        $day_name = $initial ? $wp_locale->get_weekday_initial($wd) : $wp_locale->get_weekday_abbrev($wd);
        $wd = esc_attr($wd);
        $calendar_output .= "\n\t\t<th scope=\"col\" title=\"$wd\">$day_name</th>";
    }
    add_filter('month_link', 'tooocheke_universal_month_link', 10, 3);
    $calendar_output .= '
		</tr>
		</thead>

		<tfoot>
		<tr>';

    if ($previous) {
        $calendar_output .= "\n\t\t" . '<td colspan="3" id="prev"><a href="' . get_month_link($previous->year, $previous->month) . '">&laquo; ' .
        $wp_locale->get_month_abbrev($wp_locale->get_month($previous->month)) .
            '</a></td>';
    } else {
        $calendar_output .= "\n\t\t" . '<td colspan="3" id="prev" class="pad">&nbsp;</td>';
    }

    $calendar_output .= "\n\t\t" . '<td class="pad">&nbsp;</td>';

    if ($next) {
        $calendar_output .= "\n\t\t" . '<td colspan="3" id="next"><a href="' . get_month_link($next->year, $next->month) . '">' .
        $wp_locale->get_month_abbrev($wp_locale->get_month($next->month)) .
            ' &raquo;</a></td>';
    } else {
        $calendar_output .= "\n\t\t" . '<td colspan="3" id="next" class="pad">&nbsp;</td>';
    }

    $calendar_output .= '
		</tr>
		</tfoot>

		<tbody>
		<tr>';

    $daywithpost = array();

    // Get days with posts
    // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    $dayswithposts = $wpdb->get_results("SELECT DISTINCT DAYOFMONTH(post_date) FROM $wpdb->posts WHERE post_date >= '{$thisyear}-{$thismonth}-01 00:00:00' AND post_type = '$posttype' AND post_status = 'publish' AND post_date <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59'", ARRAY_N);
    if ($dayswithposts) {
        foreach ((array) $dayswithposts as $daywith) {
            $daywithpost[] = $daywith[0];
        }
    }

    // See how much we should pad in the beginning
    $pad = calendar_week_mod(date('w', $unixmonth) - $week_begins);
    if (0 != $pad) {
        $calendar_output .= "\n\t\t" . '<td colspan="' . esc_attr($pad) . '" class="pad">&nbsp;</td>';
    }

    $newrow = false;
    $daysinmonth = (int) date('t', $unixmonth);

    for ($day = 1; $day <= $daysinmonth; ++$day) {
        if (isset($newrow) && $newrow) {
            $calendar_output .= "\n\t</tr>\n\t<tr>\n\t\t";
        }
        $newrow = false;

        if ($day == gmdate('j', current_time('timestamp')) &&
            $thismonth == gmdate('m', current_time('timestamp')) &&
            $thisyear == gmdate('Y', current_time('timestamp'))) {
            $calendar_output .= '<td id="today">';
        } else {
            $calendar_output .= '<td>';
        }

        if (in_array($day, $daywithpost)) {
            // any posts today?
            // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
            $date_format = date(_x('F j, Y', 'daily archives date format', 'toocheke'), strtotime("{$thisyear}-{$thismonth}-{$day}"));
            // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
            $label = sprintf(__('Posts published on %s', 'toocheke'), $date_format);
            add_filter('day_link', 'tooocheke_universal_day_link', 10, 4);
            $calendar_output .= sprintf(
                '<a href="%s" aria-label="%s">%s</a>',
                get_day_link($thisyear, $thismonth, $day),
                // $this->get_comic_day_link( $posttype, $thisyear, $thismonth, $day ),
                esc_attr($label),
                $day
            );
            remove_filter('day_link', 'tooocheke_universal_day_link');
        } else {
            $calendar_output .= $day;
        }
        $calendar_output .= '</td>';

        if (6 == calendar_week_mod(date('w', mktime(0, 0, 0, $thismonth, $day, $thisyear)) - $week_begins)) {
            $newrow = true;
        }
    }

    $pad = 7 - calendar_week_mod(date('w', mktime(0, 0, 0, $thismonth, $day, $thisyear)) - $week_begins);
    if (0 != $pad && 7 != $pad) {
        $calendar_output .= "\n\t\t" . '<td class="pad" colspan="' . esc_attr($pad) . '">&nbsp;</td>';
    }
    remove_filter('month_link', 'tooocheke_universal_month_link');
    $calendar_output .= "\n\t</tr>\n\t</tbody>\n\t</table>";

    if ($echo) {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $calendar_output;
    } else {
        return $calendar_output;
    }
}

function tooocheke_universal_day_link($daylink, $year = '', $month = '', $day = '')
{

    $slug = 'comic';

    $relative = "/{$slug}/{$year}/{$month}/{$day}";
    $day_permalink = home_url($relative);

    return $day_permalink;
}
function tooocheke_universal_month_link($monthlink, $year = '', $month = '')
{
    $slug = 'comic';

    $month_permalink = home_url("/{$slug}/{$year}/{$month}");
    return $month_permalink;
}
/**
 * Chapter navigation functions
 */
if (!function_exists('toocheke_universal_get_previous_comic')):
    function toocheke_universal_get_previous_comic($in_chapter = false)
{
        return toocheke_universal_get_adjacent_comic(true, $in_chapter);
    }
endif;
if (!function_exists('toocheke_universal_get_previous_comic_permalink')):
    function toocheke_universal_get_previous_comic_permalink()
{
        $prev_comic = toocheke_universal_get_previous_comic(false);
        if (is_object($prev_comic) && isset($prev_comic->ID)) {
            return get_permalink($prev_comic->ID);
        }
        return false;
    }
endif;
if (!function_exists('toocheke_universal_get_previous_comic_in_chapter_permalink')):
    function toocheke_universal_get_previous_comic_in_chapter_permalink()
{
        $prev_comic = toocheke_universal_get_previous_comic(true);
        if (is_object($prev_comic) && isset($prev_comic->ID)) {
            return get_permalink($prev_comic->ID);
        }
        //    Go to last comic of previous chapter if possible.

        $chapter = toocheke_universal_get_adjacent_chapter(true);
        if (is_object($chapter)) {
            $terminal = toocheke_universal_get_chapter_comic_post($chapter->term_id, false);
            return !empty($terminal) ? get_permalink($terminal->ID) : false;
        }

        return false;
    }
endif;
if (!function_exists('toocheke_universal_get_next_comic')):
    function toocheke_universal_get_next_comic($in_chapter = false)
{
        return toocheke_universal_get_adjacent_comic(false, $in_chapter);
    }
endif;
if (!function_exists('toocheke_universal_get_next_comic_permalink')):
    function toocheke_universal_get_next_comic_permalink()
{
        $next_comic = toocheke_universal_get_next_comic(false);
        if (is_object($next_comic) && isset($next_comic->ID)) {
            return get_permalink($next_comic->ID);
        }
        return false;
    }
endif;
if (!function_exists('toocheke_universal_get_next_comic_in_chapter_permalink')):
    function toocheke_universal_get_next_comic_in_chapter_permalink()
{
        $next_comic = toocheke_universal_get_next_comic(true);
        if (is_object($next_comic) && isset($next_comic->ID)) {
            return get_permalink($next_comic->ID);
        }
        // go to first comic of next chapter if possible

        $chapter = toocheke_universal_get_adjacent_chapter(false);
        if (is_object($chapter)) {
            $terminal = toocheke_universal_get_chapter_comic_post($chapter->term_id, true);
            return !empty($terminal) ? get_permalink($terminal->ID) : false;
        }

        return false;
    }
endif;
// 0 means get the first of them all, no matter chapter, otherwise 0 = this chapter.
if (!function_exists('toocheke_universal_get_chapter_comic_post')):
    function toocheke_universal_get_chapter_comic_post($chapterID = 0, $first = true)
{

        $sortOrder = $first ? "asc" : "desc";

        if (!empty($chapterID)) {
            $chapter = get_term_by('id', $chapterID, 'chapters');
            $chapter_slug = $chapter->slug;
            $args = array(
                'chapters' => $chapter_slug,
                'order' => $sortOrder,
                'posts_per_page' => 1,
                'post_type' => 'comic',
            );
        } else {
            $args = array(
                'order' => $sortOrder,
                'posts_per_page' => 1,
                'post_type' => 'comic',
            );
        }

        $terminalComicQuery = new WP_Query($args);

        $terminalPost = false;
        if ($terminalComicQuery->have_posts()) {
            $terminalPost = reset($terminalComicQuery->posts);
        }
        return $terminalPost;
    }
endif;
/**
 * Retrieve adjacent post link.
 *
 */
if (!function_exists('toocheke_universal_get_adjacent_comic')):
    function toocheke_universal_get_adjacent_comic($previous = true, $in_same_chapter = false, $taxonomy = 'comic')
{
        global $post, $wpdb;
        if (empty($post)) {
            return null;
        }

        $current_post_date = $post->post_date;

        $join = '';

        if ($in_same_chapter) {
            $join = " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";

            if ($in_same_chapter) {
                $chapt_array = wp_get_object_terms($post->ID, 'chapters', array('fields' => 'ids'));
                if (!empty($chapt_array)) {
                    $join .= " AND tt.taxonomy = 'chapters' AND tt.term_id IN (" . implode(',', $chapt_array) . ")";
                }

            }
        }

        $adjacent = $previous ? 'previous' : 'next';
        $op = $previous ? '<' : '>';
        $order = $previous ? 'DESC' : 'ASC';

        $where = apply_filters("get_{$adjacent}_{$taxonomy}_where", $wpdb->prepare("WHERE p.post_date %s %s AND p.post_type = %s AND p.post_status = 'publish'", $op, $current_post_date, $post->post_type), $in_same_chapter);
        $sort = apply_filters("get_{$adjacent}_{$taxonomy}_sort", "ORDER BY p.post_date $order LIMIT 1");

        $query = "SELECT p.* FROM $wpdb->posts AS p $join $where $sort";
        $query_key = "adjacent_{$taxonomy}_{$post->ID}_{$previous}_{$in_same_chapter}"; // . md5($query);
        $result = wp_cache_get($query_key, 'counts');
        if (false !== $result) {
            return $result;
        }

        $result = $wpdb->get_row($wpdb->prepare("SELECT p.* FROM $wpdb->posts AS p %s %s %s", $join, $where, $sort));
        if (null === $result) {
            $result = '';
        }

        wp_cache_set($query_key, $result, 'counts');
        return $result;
    }
endif;
if (!function_exists('toocheke_universal_get_adjacent_chapter')):
    function toocheke_universal_get_adjacent_chapter($prev = false)
{
        global $post;

        $current_chapter = get_the_terms($post->ID, 'chapters');

        if (is_array($current_chapter)) {$current_chapter = reset($current_chapter);} else {return;}

        // cache the calculation of the desired chapter - workaround for bug with w3 total cache's object cache
        $current_order = wp_cache_get('toocheke_universal_current_order_' . $current_chapter->slug);
        if (false === $current_order) {
            $current_order = (int) get_term_meta($current_chapter->term_id, 'chapter-order', true);
            //$current_order = $current_chapter->chapter-order;
            wp_cache_set('toocheke_universal_current_order_' . $current_chapter->slug, $current_order);
        }

        $find_order = (bool) $prev ? $current_order - 1 : $current_order + 1;

        if (!$find_order) {
            return false;
        }

        $args = array(
            'orderby' => 'chapter-order',
            'order' => 'DESC',
            'hide_empty' => 1,
            'chapter-order' => $find_order,
        );

        $all_chapters = get_terms('chapters', $args);
        if (!is_null($all_chapters)) {

            foreach ($all_chapters as $chapter) {
                $chapter_order = (int) get_term_meta($chapter->term_id, 'chapter-order', true);
                if ($chapter_order == $find_order) {
                    return $chapter;
                }

            }
        }
        return false;
    }
endif;
if (!function_exists('toocheke_universal_get_previous_chapter')):
    function toocheke_universal_get_previous_chapter()
{
        $chapter = toocheke_universal_get_adjacent_chapter(true);

        if (is_object($chapter)) {

            $child_args = array(
                'numberposts' => 1,
                'post_type' => 'comic',
                'orderby' => 'post_date',
                'order' => 'ASC',
                'post_status' => 'publish',
                'chapters' => $chapter->slug,
            );
            $chapter_posts = get_posts($child_args);
            if (is_array($chapter_posts)) {
                $chapter_posts = reset($chapter_posts);

                return get_permalink($chapter_posts->ID);
            }

        }

        return false;
    }
endif;
if (!function_exists('toocheke_universal_get_next_chapter')):
    function toocheke_universal_get_next_chapter()
{
        $chapter = toocheke_universal_get_adjacent_chapter(false);
        if (is_object($chapter)) {
            $child_args = array(
                'numberposts' => 1,
                'post_type' => 'comic',
                'orderby' => 'post_date',
                'order' => 'ASC',
                'post_status' => 'publish',
                'chapters' => $chapter->slug,
            );
            $chapter_posts = get_posts($child_args);
            if (is_array($chapter_posts)) {
                $chapter_posts = reset($chapter_posts);
                return get_permalink($chapter_posts->ID);
            }
        }
        return false;
    }
endif;
