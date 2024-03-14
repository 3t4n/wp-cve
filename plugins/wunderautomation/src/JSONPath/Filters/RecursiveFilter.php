<?php
namespace WunderAuto\JSONPath\Filters;

/**
 * MIT License
 * Copyright (c) 2018 Flow Communications
 * https://github.com/FlowCommunications/JSONPath
 */

use WunderAuto\JSONPath\AccessHelper;

class RecursiveFilter extends AbstractFilter
{
    /**
     * @param $collection
     * @return array
     */
    public function filter($collection)
    {
        $result = [];

        $this->recurse($result, $collection);

        return $result;
    }

    private function recurse(&$result, $data)
    {
        $result[] = $data;

        if (AccessHelper::isCollectionType($data)) {
            foreach (AccessHelper::arrayValues($data) as $key => $value) {
                $results[] = $value;

                if (AccessHelper::isCollectionType($value)) {
                    $this->recurse($result, $value);
                }
            }
        }
    }
}
