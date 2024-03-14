<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 *
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Categories.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 */
class IfwPsn_Wp_Proxy_Categories
{
    /**
     * Get all categories of a Post type
     *
     * @param string $posttype
     * @return array
     */
    public static function getByPosttype($posttype)
    {
        $result = array();

        $taxonomies = get_object_taxonomies($posttype, 'objects');

        foreach ($taxonomies as $taxonomy) {
            if ($taxonomy->hierarchical == false) {
                continue;
            }
            foreach(get_terms($taxonomy->name, 'hide_empty=0') as $term) {
                array_push($result, $term);
            }
        }

        return $result;
    }

    /**
     * @param $tax
     * @return array
     */
    public static function getByTaxonomy($tax)
    {
        $args = array(
            'taxonomy' => $tax,
            'orderby' => 'name',
            'order'   => 'ASC'
        );

        return get_categories($args);
    }
}
 