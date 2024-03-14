<?php
// Exit if accessed directly.
if(!defined('ABSPATH')) exit;

if(!class_exists('RTCORE_OCDI')):

/**
 * RTCORE One Click Demo Importer Class.
 *
 * @class RTCORE_OCDI
 * @version	1.0.0
 */
class RTCORE_OCDI extends RTCORE_Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function init()
    {
        $theme = wp_get_theme();

        if($theme->get_template() === 'sesame') (new RTCORE_OCDI_Sesame())->init();
        elseif($theme->get_template() === 'listy') (new RTCORE_OCDI_Listy())->init();
        elseif($theme->get_template() === 'idxed') (new RTCORE_OCDI_IDXed())->init();
    }

    public function plugins($plugins)
    {
        return $plugins;
    }

    public function prepare($demo)
    {
        $sidebars_widgets = get_option('sidebars_widgets');

        $empty_widgets = array();
        foreach($sidebars_widgets as $sidebar => $widgets) $empty_widgets[$sidebar] = array();

        update_option('sidebars_widgets', $empty_widgets);
    }

    public function listings()
    {
        if(!function_exists('_wpl_import')) return false;

        $fields = wpl_flex::get_fields('', 0, 0, '', '', "AND `enabled` >= 1 AND `kind` = 0 
			AND ((`id` IN (2,3,6,7,8,9,10,11,12,13,14,17)) 
			OR (`category` = '4' AND `type` = 'feature') 
			OR (`category` = '5' AND `type` = 'feature') 
			OR (`category` = '6' AND `type` = 'neighborhood') 
			OR (`category` = '11' AND `type` = 'tag'))");

        $post = array('command' => 'wpl_sample', 'format' => 'json');
        $data = json_decode(wpl_global::get_web_page('http://billing.realtyna.com/io/io.php', $post));
        $states = wpl_locations::get_locations(2, 254, '');

        for($i = 0; $i < 6; $i++)
        {
            $query = '';
            $pid = wpl_property::create_property_default();

            foreach($fields as $field)
            {
                if($field->type == 'listings')
                {
                    $types = wpl_global::get_listings();
                    $pos = array_rand($types);
                    $value = $types[$pos]['id'];
                }
                elseif($field->type == 'property_types')
                {
                    $types = wpl_global::get_property_types();
                    $pos = array_rand($types);
                    $value = $types[$pos]['id'];
                }
                elseif($field->type == 'price')
                {
                    $value = rand(100, 999) * 1000;
                }
                elseif($field->type == 'select')
                {
                    $params = wpl_flex::get_field_options($field->id);
                    $params = array_keys($params['params']);
                    $value = array_rand($params);
                }
                elseif($field->type == 'number')
                {
                    $value = ($field->id == 12) ? rand(1950, 2015) : rand(1, 9);
                }
                elseif($field->type == 'area')
                {
                    $value = rand(200, 999);
                }
                elseif($field->type == 'feature' or $field->type == 'tag')
                {
                    $value = rand(0, 1);
                }
                elseif($field->type == 'neighborhood')
                {
                    $value = rand(0, 1);

                    if($value == 1)
                    {
                        $dist = rand(5, 90);
                        $dist_by = rand(1, 3);
                        $query .= "`{$field->table_column}_distance` = '{$dist}', `{$field->table_column}_distance_by` = '{$dist_by}', ";
                    }
                }

                $query .= "`{$field->table_column}` = '{$value}', ";
            }

            $state = array_rand($states);
            $state_id = $states[$state]->id;
            $state_name = $states[$state]->name;
            $county = ($data ? $data->counties[array_rand($data->counties)] : '');
            $city = ($data ? $data->cities[array_rand($data->cities)] : '');
            $street = ($data ? $data->streets[array_rand($data->streets)] : '');
            $street_no = rand(500, 3000);
            $zipcode = rand(10000, 90000);

            $query .= "`field_42` = '{$street}', `street_no` = '{$street_no}', `post_code` = '{$zipcode}', ";
            $query .= "`location2_id` = '{$state_id}', `location2_name` = '{$state_name}', `location3_name` = '{$county}', `location4_name` = '{$city}'";

            wpl_db::q("UPDATE `#__wpl_properties` SET $query WHERE `id` = '$pid'");
            wpl_property::finalize($pid);

            $image = ($data ? $data->images[array_rand($data->images)] : '');
            $image_data = wpl_global::get_web_page($image);
            $image_file = wpl_global::get_upload_base_path().$pid.DS.basename($image);
            wpl_file::write($image_file, $image_data);

            $item = array('parent_id'=>$pid, 'parent_kind'=>0, 'item_type'=>'gallery', 'item_cat'=>'image', 'item_name'=>basename($image), 'creation_date'=>date("Y-m-d H:i:s"), 'index'=>0);
            wpl_items::save($item);
            wpl_property::update_numbs($pid);
        }
    }

    public function menu($setup)
    {
        $setup['page_title'] = esc_html__('Demo Import' , 'realtyna-core');
        $setup['menu_title'] = esc_html__('Demo Import' , 'realtyna-core');

        return $setup;
    }

    public function intro($intro)
    {
        return $intro;
    }

    public function title($title)
    {
        return '<div class="ocdi__title-container">
			<h1 class="ocdi__title-container-title">'.esc_html__('Realtyna Demo Importer', 'realtyna-core').'</h1>
			<a href="https://ocdi.com/user-guide/" target="_blank" rel="noopener noreferrer">
				<img class="ocdi__title-container-icon" src="'.plugins_url().'/one-click-demo-import/assets/images/icons/question-circle.svg" alt="Questionmark icon">
			</a>
		</div>';
    }
}

endif;