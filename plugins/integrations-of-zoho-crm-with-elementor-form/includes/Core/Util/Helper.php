<?php
namespace FormInteg\IZCRMEF\Core\Util;

use FormInteg\IZCRMEF\Triggers\TriggerController;

/**
 * bit-integration helper class
 *
 * @since 1.0.0
 */
final class Helper
{
    /**
     * string to array convert with separator
     */
    public static function splitStringToarray($data)
    {
        $params = new \stdClass();
        $params->id = $data['bit-integrator%trigger_data%']['triggered_entity_id'];
        $trigger = $data['bit-integrator%trigger_data%']['triggered_entity'];
        $fields = TriggerController::getTriggerField($trigger, $params);
        if (count($fields) > 0) {
            foreach ($fields as $field) {
                if (isset($data[$field['name']])) {
                    if (gettype($data[$field['name']]) === 'string' && isset($field['separator'])) {
                        if (!empty($field['separator'])) {
                            $data[$field['name']] = $field['separator'] === 'str_array' ? json_decode($data[$field['name']]) : explode($field['separator'], $data[$field['name']]);
                        }
                    }
                }
            }
        }
        return $data;
    }

    public static function dd($data)
    {
        echo '<pre>';
        var_dump($data); // or var_dump($data);
        echo '</pre>';
    }

    public static function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
