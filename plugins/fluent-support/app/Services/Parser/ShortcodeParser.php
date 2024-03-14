<?php

namespace FluentSupport\App\Services\Parser;

use FluentSupport\App\Services\Helper;
use FluentSupport\Framework\Support\Arr;

class ShortcodeParser
{
    public function parse($templateString, $data)
    {
        $result = [];
        $isSingle = false;

        if (!is_array($templateString)) {
            $isSingle = true;
        }

        foreach ((array)$templateString as $key => $string) {
            $result[$key] = $this->parseShortcode($string, $data);
        }

        if ($isSingle) {
            return reset($result);
        }

        return $result;
    }

    public function parseShortcode($string, $data)
    {
        return preg_replace_callback('/({{|##)+(.*?)(}}|##)/', function ($matches) use ($data) {
            return (string) $this->replace($matches, $data);
        }, $string);
    }

    protected function replace($matches, $data)
    {
        if (empty($matches[2])) {
            return apply_filters('fluentsupport/smartcode_fallback', $matches[0], $data);
        }

        $matches[2] = trim($matches[2]);

        $matched = explode('.', $matches[2]);

        if (count($matched) <= 1) {
            return apply_filters('fluentsupport/smartcode_fallback', $matches[0], $data);
        }

        $dataKey = trim(array_shift($matched));

        $valueKey = trim(implode('.', $matched));

        if (!$valueKey) {
            return apply_filters('fluentsupport/smartcode_fallback', $matches[0], $data);
        }

        $valueKeys = explode('|', $valueKey);

        $valueKey = $valueKeys[0];
        $defaultValue = '';
        if (isset($valueKeys[1])) {
            $defaultValue = trim($valueKeys[1]);
        }

        if(empty($data[$dataKey])) {
            return $matches[0];
        }

        if ($dataKey == 'customer' || $dataKey == 'agent' || $dataKey == 'assigner') {
            return $this->getPersonValue($data[$dataKey], $valueKey, $defaultValue);
        }

        if($dataKey == 'ticket') {
            return $this->getTicketData($data['ticket'], $valueKey, $defaultValue);
        }

        if($dataKey == 'response') {
            return $this->getResponseData($data['response'], $valueKey, $defaultValue);
        }


        if ($dataKey == 'business') {
            $business = $data[$dataKey];
            return $this->getBusinessValue($valueKey, $defaultValue, $business);
        }

        return apply_filters('fluentsupport/smartcode_fallback_callback_' . $dataKey, $matches[0], $valueKey, $defaultValue, $data);
    }

    protected function getTicketData($ticket, $valueKey, $defaultValue)
    {
        $valueKeys = explode('.', $valueKey);

        if (count($valueKeys) == 1) {
            if($valueKey == 'public_url') {
                return Helper::getTicketViewSignedUrl($ticket);
            }

            if($valueKey == 'admin_url') {
                return Helper::getTicketAdminUrl($ticket);
            }

            $accesors = ['id', 'title', 'content', 'priority', 'client_priority', 'status', 'created_at'];
            if(in_array($valueKey, $accesors)) {
                return $ticket->{$valueKey};
            }
        }

        return $defaultValue;
    }

    protected function getResponseData($response, $valueKey, $defaultValue)
    {
        $valueKeys = explode('.', $valueKey);

        if (count($valueKeys) == 1) {
            $accesors = ['id', 'title', 'content'];

            if($valueKey == 'full_content') {
                // todo: We have to attach the attachments too.
                // Maybe in the next version
                $valueKey = 'content';
            }

            if(in_array($valueKey, $accesors)) {
                return $response->{$valueKey};
            }
        }

        return $defaultValue;
    }

    protected function getBusinessValue($valueKey, $defaultValue, $business)
    {
        $accessKeys = ['name', 'id', 'email'];

        if(in_array($valueKey, $accessKeys)) {
            return $business->{$valueKey};
        }

        return $defaultValue;
    }

    protected function getPersonValue($person, $valueKey, $defaultValue)
    {
        $valueKeys = explode('.', $valueKey);

        if (count($valueKeys) == 1) {
            $data = $person->toArray();
            $extraAccessors = ['full_name', 'photo'];

            if(in_array($valueKey, $extraAccessors)) {
                return $person->{$valueKey};
            }

            $value = Arr::get($data, $valueKey);
            return ($value) ? $value : $defaultValue;
        }

        return $defaultValue;
    }
}
