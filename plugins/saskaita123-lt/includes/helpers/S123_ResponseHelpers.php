<?php
/**
 * @link https://www.invoice123.com
 * @package Saskaita123Plugin
 *
 * Class Description: Custom response messages
 */

declare(strict_types=1);

namespace S123\Includes\Helpers;

if (!defined('ABSPATH')) exit;

class S123_ResponseHelpers
{
    public static function s123_sendResponse($data)
    {
        header('Content-type: application/json');
        wp_die(json_encode($data));
    }

    public static function s123_sendSuccessResponse($message = null)
    {
        self::s123_sendResponse([
            'success' => true,
            'message' => $message ?: 'OK',
        ]);
    }

    public static function s123_sendErrorResponse($message)
    {
        self::s123_sendResponse([
            'success' => false,
            'message' => $message,
        ]);
    }

}