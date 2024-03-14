<?php
/**
 * Admin Page: Dashboard
 */

namespace FDSUS\Controller\Admin;

use FDSUS\Model\Sheet as SheetModel;

class Dashboard
{

    public function __construct()
    {
        add_filter('dashboard_glance_items', array(&$this, 'addToGlance'), 10, 1);
    }

    /**
     * Add to "At a Glance" items
     *
     * @param $items
     *
     * @return array
     */
    function addToGlance($items = array())
    {
        $postTypesToAdd = array(SheetModel::POST_TYPE);
        foreach ($postTypesToAdd as $type) {
            if (!post_type_exists($type)) {
                continue;
            }
            $numPosts = wp_count_posts($type);
            if ($numPosts) {
                $published = intval($numPosts->publish);
                $postType = get_post_type_object($type);
                $label = $published === 1 ? $postType->labels->singular_name : $postType->labels->name;
                $text = sprintf('%s %s', number_format_i18n($published), $label);
                if (current_user_can($postType->cap->edit_posts)) {
                    $output = '<a href="edit.php?post_type=' . esc_attr($postType->name) . '">' . esc_html($text) . '</a>';
                } else {
                    $output = '<span>' . esc_html($text) . '</span>';
                }
                echo '<li class="' . esc_attr($postType->name) . '-count">' . $output . '</li>';
            }
        }

        return $items;
    }

}
