<?php
/**
 * Copyright © 2017 SeQura Engineering. All rights reserved.
 */


namespace Sequra\PhpClient;

class Helper
{
    const ISO8601_PATTERN = '^((\d{4})-([0-1]\d)-([0-3]\d))+$|P(\d+Y)?(\d+M)?(\d+W)?(\d+D)?(T(\d+H)?(\d+M)?(\d+S)?)?$';

    public static function isConsistentCart($cart)
    {
        $totals = self::totals($cart);
        return $cart['order_total_with_tax'] == $totals['with_tax'];
    }

    public static function totals($cart)
    {
        $total_with_tax = 0;
        foreach ($cart['items'] as $item) {
            $total_with_tax    += $item['total_with_tax'];
        }

        return array('with_tax' => $total_with_tax);
    }

    public static function removeNulls($data)
    {
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            } else {
                if (is_array($value)) {
                    $data[$key] = self::removeNulls($value);
                }
            }
        }

        return $data;
    }

    public static function removeHtmlEntities($data)
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = html_entity_decode($value, ENT_COMPAT | ENT_HTML401, 'UTF-8');
            } else {
                if (is_array($value)) {
                    $data[$key] = self::removeHtmlEntities($value);
                }
            }
        }
        return $data;
    }

    public static function notNull($value1, $value2)
    {
        return is_null($value1) ? $value2 : $value1;
    }

    public static function sign($value, $pass)
    {
        $signature = hash_hmac('sha256', $value, $pass);
        if ($signature) {
            return $signature;
        }

        return sha1($value . $pass);
    }
}
