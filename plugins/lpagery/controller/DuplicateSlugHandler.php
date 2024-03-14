<?php
class LPageryDuplicateSlugHandler
{
    public static function lpagery_get_duplicated_slugs($data,$process_id, $slug)
    {
        if (is_string($data)) {
            $json_decode = LPagerySubstitutionDataPreparator::prepare_data($data);
        } else{
            $json_decode = $data;
        }
        if(!$slug) {
            $process_data = LPageryDao::lpagery_get_process_by_id($process_id);
            $slug = maybe_unserialize($process_data->data)["slug"];
        }

        $slugs = array_map(function ($element) use ($slug, $process_id) {
            $params = LPageryInputParamProvider::lpagery_get_input_params_without_images($element);
            $substituted_slug = LPagerySubstitutionHandler::lpagery_substitute($params, $slug);
            return sanitize_title($substituted_slug);
        }, $json_decode);

        $existing_slugs = LPageryDao::lpagery_get_existing_posts_by_slug($slugs, $process_id);
        $duplicates = self::lpagery_find_array_duplicates($slugs);
        return array("duplicates"=>$duplicates, "existing_slugs"=> $existing_slugs);

    }

    private static function lpagery_find_array_duplicates($arr) {
        $duplicates = array();
        $countedValues = array_count_values($arr);

        foreach ($countedValues as $value => $count) {
            if ($count > 1) {
                $duplicates[] = $value;
            }
        }

        return $duplicates;
    }

}