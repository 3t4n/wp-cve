<?php

namespace WPPayForm\App\Models;

use WPPayForm\Framework\Support\Arr;


class Meta extends Model
{
    public $table = 'wpf_meta';

    public function updateOrderMeta($metaGroup, $optionId, $key, $value, $formId = null)
    {
        $value = maybe_serialize($value);
        $exists = $this->where('meta_group', $metaGroup)
            ->where('meta_key', $key)
            ->where('option_id', $optionId)
            ->where('form_id', $formId)
            ->first();

        if ($exists) {
            $this->where('id', $exists->id)
                ->update([
                    'meta_group' => $metaGroup,
                    'option_id' => $optionId,
                    'meta_key' => $key,
                    'meta_value' => $value,
                    'form_id' => $formId,
                    'updated_at' => current_time('mysql')
                ]);
            return $exists->id;
        }

        return $this->insert([
            'meta_group' => $metaGroup,
            'option_id' => $optionId,
            'meta_key' => $key,
            'meta_value' => $value,
            'form_id' => $formId,
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql')
        ]);
    }

    public static function getFormMeta($formId, $metaKey, $default = '', $group = false)
    {
        $metaQuery = Meta::where('meta_key', $metaKey)
        ->where('form_id', $formId);

        if ($group) {
            $metaQuery = $metaQuery->where('meta_group', $group);
        }

        $meta = $metaQuery->first();

        if (!$meta || !$meta->meta_value) {
            return $default;
        }

        $metaValue = $meta->meta_value;
        // decode the JSON data
        $result = json_decode($metaValue, true);

        if (json_last_error() == JSON_ERROR_NONE) {
            return $result;
        }
        return $metaValue;
    }

    public static function migrate()
    {
        global $wpdb;

        $stat = $wpdb->get_results("DESCRIBE {$wpdb->prefix}wpf_meta");

        $message = "Activated, Please reload this page.";

        foreach($stat as $column) {
           if ( $column->Field == 'form_id') {
                $message = "form_id column already altered!";
                return array(
                    "status" => false,
                    "message" => $message
                );
           }
        }

        $tableName = $wpdb->prefix . 'wpf_meta';
        $sql = "ALTER TABLE $tableName
            ADD form_id int(11) NULL";
            
        $upgrade = $wpdb->query($sql);

        if (!$upgrade && $wpdb->last_error !== '') {
			$message = $wpdb->last_error;
		}

        return array(
            "status" => $upgrade,
            "message" => $message
        );
    }

    public function saveIntegration($integrationData, $formId)
    {
        $data = [
            'meta_key' => sanitize_text_field(Arr::get($integrationData, 'meta_key')),
            'meta_value' => Arr::get($integrationData, 'value'),
            'meta_group' => 'integration',
            'form_id' => intVal($formId)
        ];

        if ($id = Arr::get($integrationData, 'id', false)) {
            return $this->where('id', $id)->update($data);
        } else {
            return $this->create($data)->id;
        }
    }

    public function getIntegration($formId)
    {
        $data = $this->where('form_id', $formId)->where('meta_key', 'slack')->first();

        if (!$data) {
            return [
                'settings' => [],
                'id' => 0
            ];
        }

        return array(
            'settings' => json_decode($data->meta_value, true),
            'id' => $data->id
        );
    }

    public function updateCurrencyRates($rates, $key)
    {
        $value = maybe_serialize($rates);
        $exists = $this->where('meta_key', $key)->first();
        if ($exists) {
            $this->where('id', $exists->id)
                ->update([
                    'meta_key' => $key,
                    'meta_value' => $value,
                    'updated_at' => current_time('mysql')
                ]);
            return $exists->id;
        }

        return $this->insert([
            'meta_key' => $key,
            'meta_value' => $value,
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql')
        ]);

    }

    public function getCurrencyMeta($key)
    {
        return $this->where('meta_key', $key)->first();
    }

}
