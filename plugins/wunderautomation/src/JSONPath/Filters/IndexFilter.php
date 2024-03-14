<?php
namespace WunderAuto\JSONPath\Filters;

/**
 * MIT License
 * Copyright (c) 2018 Flow Communications
 * https://github.com/FlowCommunications/JSONPath
 */

use WunderAuto\JSONPath\AccessHelper;

/**
 * Class IndexFilter
 *
 * @package WunderAuto\JSONPath\Filters
 */
class IndexFilter extends AbstractFilter
{
    /**
     * @param array $collection
     * @return array
     * @throws \WunderAuto\JSONPath\JSONPathException
     */
    public function filter($collection)
    {
        if (AccessHelper::keyExists($collection, $this->token->value, $this->magicIsAllowed)) {
            return [
                AccessHelper::getValue($collection, $this->token->value, $this->magicIsAllowed)
            ];
        }

        if ($this->token->value === "*") {
            return AccessHelper::arrayValues($collection);
        }

        return [];
    }
}
