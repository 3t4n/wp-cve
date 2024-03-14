<?php

namespace Modular\ConnectorDependencies\League\Flysystem\Adapter\Polyfill;

/** @internal */
trait StreamedTrait
{
    use StreamedReadingTrait;
    use StreamedWritingTrait;
}
