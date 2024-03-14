<?php

declare (strict_types=1);
namespace Modular\ConnectorDependencies\Doctrine\Inflector;

/** @internal */
class NoopWordInflector implements WordInflector
{
    public function inflect(string $word) : string
    {
        return $word;
    }
}
