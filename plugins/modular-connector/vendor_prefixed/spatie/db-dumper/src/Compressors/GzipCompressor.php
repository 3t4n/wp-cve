<?php

namespace Modular\ConnectorDependencies\Spatie\DbDumper\Compressors;

/** @internal */
class GzipCompressor implements Compressor
{
    public function useCommand() : string
    {
        return 'gzip';
    }
    public function useExtension() : string
    {
        return 'gz';
    }
}
