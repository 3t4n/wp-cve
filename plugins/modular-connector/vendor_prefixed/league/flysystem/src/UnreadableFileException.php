<?php

namespace Modular\ConnectorDependencies\League\Flysystem;

use SplFileInfo;
/** @internal */
class UnreadableFileException extends Exception
{
    public static function forFileInfo(SplFileInfo $fileInfo)
    {
        return new static(\sprintf('Unreadable file encountered: %s', $fileInfo->getRealPath()));
    }
}
