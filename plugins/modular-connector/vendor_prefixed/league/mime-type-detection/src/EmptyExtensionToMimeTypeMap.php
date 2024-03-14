<?php

declare (strict_types=1);
namespace Modular\ConnectorDependencies\League\MimeTypeDetection;

/** @internal */
class EmptyExtensionToMimeTypeMap implements ExtensionToMimeTypeMap
{
    public function lookupMimeType(string $extension) : ?string
    {
        return null;
    }
}
