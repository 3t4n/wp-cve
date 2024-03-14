<?php
namespace WunderAuto\JSONPath\Filters;

/**
 * MIT License
 * Copyright (c) 2018 Flow Communications
 * https://github.com/FlowCommunications/JSONPath
 */

use WunderAuto\JSONPath\AccessHelper;

class IndexesFilter extends AbstractFilter
{
    /**
     * @param $collection
     * @return array
     */
    public function filter($collection)
    {
        $return = [];
        foreach ($this->token->value as $index) {
            if (AccessHelper::keyExists($collection, $index, $this->magicIsAllowed)) {
                $return[] = AccessHelper::getValue($collection, $index, $this->magicIsAllowed);
            }
        }
        return $return;
    }
}
