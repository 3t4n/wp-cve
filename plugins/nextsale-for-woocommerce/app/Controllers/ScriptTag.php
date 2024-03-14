<?php

namespace App\Controllers;

use App\Base\Controller;
use WP_Error;

class ScriptTag extends Controller
{
    /**
     * List registered script tags
     * @return array
     */
    public static function list()
    {
        if (!self::verifyToken()) {
            return new WP_Error('unauthorized', 'Authentication failed.', [
                'status' => 401
            ]);
        }

        $scripts = json_decode(get_option('nextsale_script_tags'), true);

        if (!$scripts || !is_array($scripts)) {
            $scripts = [];
        }

        return $scripts;
    }

    /**
     * Add new script tag
     * @return array
     */
    public static function add($request)
    {
        if (!self::verifyToken()) {
            return new WP_Error('unauthorized', 'Authentication failed.', [
                'status' => 401
            ]);
        }

        $data = json_decode($request->get_body());

        if (!isset($data->src)) {
            return new WP_Error('invalid_data', 'Src parameter is required.', [
                'status' => 422
            ]);
        }

        if (!self::validateSource($data->src)) {
            return new WP_Error('invalid_data', 'Src parameter is not valid.', [
                'status' => 422
            ]);
        }

        $sources = json_decode(get_option('nextsale_script_tags'), true);

        if (!$sources || !is_array($sources)) {
            $sources = [];
        }

        if (!in_array($data->src, $sources)) {
            $sources[] = $data->src;
        }

        update_option('nextsale_script_tags', json_encode($sources));

        return [
            'success' => true
        ];
    }

    /**
     * Delete script tag
     *
     * @param [type] $request
     * @return array
     */
    public static function delete($request)
    {
        if (!self::verifyToken()) {
            return new WP_Error('unauthorized', 'Authentication failed.', [
                'status' => 401
            ]);
        }

        $data = json_decode($request->get_body());

        if (!isset($data->src)) {
            return new WP_Error('invalid_data', 'Src parameter is required.', [
                'status' => 422
            ]);
        }

        if (!self::validateSource($data->src)) {
            return new WP_Error('invalid_data', 'Src parameter is not valid.', [
                'status' => 422
            ]);
        }

        $sources = json_decode(get_option('nextsale_script_tags'), true);

        if (!$sources || !is_array($sources)) {
            $sources = [];
        }

        $key = array_search($data->src, $sources);
        if ($key !== false) {
            unset($sources[$key]);
            $sources = array_values($sources);
        }

        update_option('nextsale_script_tags', json_encode($sources));

        return [
            'success' => true
        ];
    }

    /**
     * Validate the script source url
     *
     * @param string $src
     * @return boolean
     */
    private static function validateSource(string $src)
    {
        $url_parts = parse_url($src);

        if (!isset($url_parts['scheme']) || !isset($url_parts['host'])) {
            return false;
        }

        if (!in_array($url_parts['scheme'], ['http', 'https'])) {
            return false;
        }

        return true;
    }
}
