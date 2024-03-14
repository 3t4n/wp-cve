<?php


require_once(plugin_dir_path(__FILE__) . 'MetaDataHandler.php');
require_once(plugin_dir_path(__FILE__) . 'SubstitutionHandler.php');
require_once(plugin_dir_path(__FILE__) . 'PagebuilderHandler.php');
require_once(plugin_dir_path(__FILE__) . 'SeoPluginHandler.php');
require_once(plugin_dir_path(__FILE__) . 'InputParamProvider.php');
require_once(plugin_dir_path(__FILE__) . 'CustomSanitizer.php');
require_once(plugin_dir_path(__FILE__) . 'DynamicPageAttributeHandler.php');
require_once(plugin_dir_path(__FILE__) . '../data/LPageryDao.php');
require_once(plugin_dir_path(__FILE__) . '../utils/Utils.php');
require_once(plugin_dir_path(__FILE__) . 'WpmlHandler.php');


class LPageryPageUpdateDataHandler
{
    public static function lpagery_get_post_to_be_updated($element, $process_id)
    {
        if(!$process_id) {
            return null;
        }
        $process_data = LPageryDao::lpagery_get_process_by_id($process_id);
        if (!$process_data) {
            return null;
        }
        $slug = maybe_unserialize($process_data->data)["slug"];
        $params = LPageryInputParamProvider::lpagery_get_input_params_without_images($element);
        $slug = LPagerySubstitutionHandler::lpagery_substitute($params, $slug);
        $slug = sanitize_title($slug);
        $existing_post_by_slug = LPageryDao::lpagery_get_existing_post_by_slug_in_process($process_id, $slug);
        if(!empty($existing_post_by_slug)) {
            return $existing_post_by_slug;
        }

        return null;

    }

}