<?php

namespace Modular\ConnectorDependencies\Spatie\DbDumper\Compressors;

/** @internal */
interface Compressor
{
    public function useCommand() : string;
    public function useExtension() : string;
}
