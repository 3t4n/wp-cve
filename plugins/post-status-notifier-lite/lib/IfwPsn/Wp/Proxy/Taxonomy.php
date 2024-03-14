<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 *
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Taxonomy.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 */
class IfwPsn_Wp_Proxy_Taxonomy 
{
    /**
     * @return array
     */
    public static function getAllRaw()
    {
        global $wp_taxonomies;
        return $wp_taxonomies;
    }

    /**
     * @return array
     */
    public static function getAllPublicRaw()
    {
        $result = self::getAllRaw();

        foreach($result as $name => $tax) {
            if ($tax->public == false) {
                unset($result[$name]);
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public static function getAllPublicNames()
    {
        $result = array();

        foreach(self::getAllRaw() as $name => $tax) {
            if ($tax->public == true) {
                array_push($result, $tax->name);
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public static function getAllNames()
    {
        return array_keys(self::getAllRaw());
    }

    /**
     * @return array
     */
    public static function getCategoriesRaw()
    {
        $result = self::getAllRaw();

        foreach($result as $name => $tax) {
            if ($tax->hierarchical == false) {
                unset($result[$name]);
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public static function getCategoriesNames()
    {
        $result = array();

        foreach(self::getAllRaw() as $name => $tax) {
            if ($tax->hierarchical == true) {
                array_push($result, $tax->name);
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public static function getPublicCategoriesNames()
    {
        $result = array();

        foreach(self::getAllRaw() as $name => $tax) {
            if ($tax->hierarchical == true && $tax->public == true) {
                array_push($result, $tax->name);
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public static function getTagsRaw()
    {
        $result = self::getAllRaw();

        foreach($result as $name => $tax) {
            if ($tax->hierarchical == true) {
                unset($result[$name]);
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public static function getTagsNames()
    {
        $result = array();

        foreach(self::getAllRaw() as $name => $tax) {
            if ($tax->hierarchical == false) {
                array_push($result, $tax->name);
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public static function getPublicTagsNames()
    {
        $result = array();

        foreach(self::getAllRaw() as $name => $tax) {
            if ($tax->hierarchical == false && $tax->public == true) {
                array_push($result, $tax->name);
            }
        }
        return $result;
    }

    /**
     * @param $taxonomy
     */
    public static function getFromDb($taxonomy)
    {
        global $wpdb;

        $result = $wpdb->get_results(
            sprintf('SELECT * FROM `%1$sterm_taxonomy` wtt INNER JOIN `%1$sterms` wt ON wtt.term_id = wt.term_id WHERE wtt.`taxonomy` = "%2$s";', $wpdb->prefix, sanitize_text_field($taxonomy)),
            ARRAY_A
        );

        return $result;
    }
}
 