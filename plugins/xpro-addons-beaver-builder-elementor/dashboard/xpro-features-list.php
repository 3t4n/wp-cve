<?php

class Xpro_Beaver_Features_List
{


    /**
     * Instance
     *
     * @since 1.5.0
     * @access private
     * @static
     *
     * @var Xpro_Beaver_Features_List The single instance of the class.
     */

    private static $instance = null;
    private static $list = array(
        'xpro-features'       => array(
            'slug'    => 'xpro_features',
            'title'   => 'Xpro Features',
            'package' => 'pro-disabled',
        ),
        'row-separators'       => array(
            'slug'    => 'row_separators',
            'title'   => 'Row Separators',
            'package' => 'pro-disabled',
        ),
    );

    /**
     * Check if a widget is active or not, free package are considered inactive
     *
     *
     * @param $features - widget slug
     *
     * @return bool
     */
    public function is_active($features)
    {

        $act = self::instance()->get_list(true, $features, 'active');

        return empty($act['package']) ? false : (('free' === $act['package'] || 'pro-disabled' === $act['package']));
    }

    /**
     *
     * Usage :
     *  get full list >> get_list() []
     *  get full list of active widgets >> get_list(true, '', 'active') // []
     *  get specific widget data >> get_list(true, 'image-accordion') [] or false
     *  get specific widget data (if active) >> get_list(true, 'image-accordion', 'active') [] or false
     *
     * @param bool $filtered
     * @param string $features
     * @param string $check_method - active|list
     *
     * @return array|bool|mixed
     */
    public function get_list($filtered = true, $features = '', $check_method = 'list')
    {
        $all_list = self::$list;

        if (true === $filtered) {
            $all_list = apply_filters('xpro_beaver_addons_widgets_list', self::$list);
        }

        if (did_action('xpro_addons_for_bb_pro_loaded')) {
            $widget_pro =Xpro_Beaver_Features_Pro_List::instance()->get_list();
            $all_list = array_merge($all_list, $widget_pro);
        }

        if ('active' === $check_method) {
            $active_list = Xpro_Beaver_Dashboard::instance()->utils->get_option('Xpro_Beaver_Modules_List', array_keys($all_list));

            foreach ($all_list as $widget_slug => $info) {
                if (!in_array($widget_slug, $active_list, true)) {
                    unset($all_list[$widget_slug]);
                }
            }
        }

        if ('' !== $features) {
            return (isset($all_list[$features]) ? $all_list[$features] : false);
        }

        return $all_list;
    }

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @return Xpro_Beaver_Modules_List An instance of the class.
     * @since 1.2.0
     * @access public
     *
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
